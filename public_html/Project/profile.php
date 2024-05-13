<?php
require_once(__DIR__ . "/../../partials/nav.php");
//is_logged_in(true);
/**
 * Logic:
 * Check if query params have an id
 * If so, use that id
 * Else check logged in user id
 * otherwise redirect away
 */
$user_id = se($_GET, "id", get_user_id(), false);
error_log("user id $user_id");
$isMe = $user_id === get_user_id();
//!! makes the value into a true or false value regardless of the data https://stackoverflow.com/a/2127324
$edit = !!se($_GET, "edit", false, false); //if key is present allow edit, otherwise no edit
if ($user_id < 1) {
    flash("Invalid user", "danger");
    redirect("home.php");
    //die(header("Location: home.php"));
}
?>

<?php
if (isset($_POST["save"]) && $isMe && $edit) {
    $db = getDB();
    $email = se($_POST, "email", null, false);
    $username = se($_POST, "username", null, false);
    $visibility = !!se($_POST, "visibility", false, false) ? 1 : 0;
    $hasError = false;
    //sanitize
    $email = sanitize_email($email);
    //validate
    if (!is_valid_email($email)) {
        flash("Invalid email address", "danger");
        $hasError = true;
    }
    if (!preg_match('/^[a-z0-9_-]{3,16}$/i', $username)) {
        flash("Username must only be alphanumeric and can only contain - or _", "danger");
        $hasError = true;
    }
    if (!$hasError) {
        $params = [":email" => $email, ":username" => $username, ":id" => get_user_id(), ":vis" => $visibility];
        $stmt = $db->prepare("UPDATE Users set email = :email, username = :username, is_public = :vis where id = :id");
        try {
            $stmt->execute($params);
        } catch (Exception $e) {
            users_check_duplicate($e->errorInfo);
        }
    }
    //select fresh data from table
    $stmt = $db->prepare("SELECT id, email, IFNULL(username, email) as `username` from Users where id = :id LIMIT 1");
    try {
        $stmt->execute([":id" => get_user_id()]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            //$_SESSION["user"] = $user;
            $_SESSION["user"]["email"] = $user["email"];
            $_SESSION["user"]["username"] = $user["username"];
        } else {
            flash("User doesn't exist", "danger");
        }
    } catch (Exception $e) {
        flash("An unexpected error occurred, please try again", "danger");
        //echo "<pre>" . var_export($e->errorInfo, true) . "</pre>";
    }


    //check/update password
    $current_password = se($_POST, "currentPassword", null, false);
    $new_password = se($_POST, "newPassword", null, false);
    $confirm_password = se($_POST, "confirmPassword", null, false);
    if (!empty($current_password) && !empty($new_password) && !empty($confirm_password)) {
        if ($new_password === $confirm_password) {
            //TODO validate current
            $stmt = $db->prepare("SELECT password from Users where id = :id");
            try {
                $stmt->execute([":id" => get_user_id()]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                if (isset($result["password"])) {
                    if (password_verify($current_password, $result["password"])) {
                        $query = "UPDATE Users set password = :password where id = :id";
                        $stmt = $db->prepare($query);
                        $stmt->execute([
                            ":id" => get_user_id(),
                            ":password" => password_hash($new_password, PASSWORD_BCRYPT)
                        ]);

                        flash("Password reset", "success");
                    } else {
                        flash("Current password is invalid", "warning");
                    }
                }
            } catch (Exception $e) {
                echo "<pre>" . var_export($e->errorInfo, true) . "</pre>";
            }
        } else {
            flash("New passwords don't match", "warning");
        }
    }
}

$email = get_user_email();
$username = get_username();
$user_id = get_user_id();
$points = getPoints($user_id);
$created = "";
$public = false;

$db = getDB();
$stmt = $db->prepare("SELECT username, created, is_public from Users where id = :id");

try {
    $stmt->execute([":id" => $user_id]);
    $r = $stmt->fetch(PDO::FETCH_ASSOC);
    error_log("user: " . var_export($r, true));
    $username = se($r, "username", "", false);
    $created = se($r, "created", "", false);
    $public = se($r, "is_public", 0, false) > 0;
    if (!$public && !$isMe) {
        flash("User's profile is private", "warning");
        redirect("home.php");
        //die(header("Location: home.php"));
    }
} 
catch (Exception $e) {
    echo "<pre>" . var_export($e->errorInfo, true) . "</pre>";
}

//Pagination
$all_scores = get_all_latest_scores($user_id);

$start = 0;  
$per_page = 10;
$page_counter = 0;
$next = $page_counter + 1;
$previous = $page_counter - 1;

if(isset($_GET['s_start'])){
    $start = $_GET['s_start'];
    $page_counter =  $_GET['s_start'];
    $start = $start * $per_page;
    $next = $page_counter + 1;
    $previous = $page_counter - 1;
}

$stmt2 = $db->prepare("SELECT score, created FROM Scores WHERE user_id = :id ORDER BY created desc LIMIT $start, $per_page");
$stmt2->execute([":id" => $user_id]);
$scores_p = $stmt2->fetchAll(PDO::FETCH_ASSOC);

$paginations = ceil(count($all_scores) / $per_page);

$all_comps = get_latest_comps($user_id);
?>

<div class="container-fluid">
    <h1>Profile
    <?php if ($isMe) : ?>
        <?php if ($edit) : ?>
            <a class="btn btn-primary" href="?">View</a>
        <?php else : ?>
            <a class="btn btn-primary" href="?edit=true">Edit Profile</a>
        <?php endif; ?>
    <?php endif; ?>
    </h1>
    <div>
        <h4><?php echo  "<p>Points: ", $points . "</p>"; ?></h4>
    </div>
    <div>
        <h3>Score History</h3>
        <?php if (count($all_scores) > 0) : ?>
        <table class="table text-dark">
            <thead>
                <th>Score</th>
                <th>Time</th>
            </thead>
            <tbody>
                <?php foreach ($scores_p as $score) : ?>
                    <tr>
                        <td><?php se($score, "score", 0); ?></td>
                        <td><?php se($score, "created", "-"); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <center>
            <ul class="s_pagination">
            <?php
                $url = get_url("profile.php?s_start=");
                if($page_counter == 0){
                    echo "<li><a href=" . $url . "0 class='active'>0</a></li>";
                    for($j = 1; $j < $paginations; $j++) { 
                        echo "<li><a href=" . $url . "$j>".$j."</a></li>";
                    }
                }
                else {
                    echo "<li><a href=" . $url . "$previous>Previous</a></li>";
                    for($j=0; $j < $paginations; $j++) {
                        if($j == $page_counter) {
                            echo "<li><a href=" . $url . "$j class='active'>".$j."</a></li>";
                        }
                        else{
                            echo "<li><a href=" . $url . "$j>".$j."</a></li>";
                        } 
                    }
                    if($j != $page_counter+1) {
                        echo "<li><a href=" . $url . "$next>Next</a></li>";
                    } 
                } 
            ?>
            </ul>
        </center>
        <?php endif ?> 
        <?php if (count($all_scores) <= 0) : ?>
            <a href="<?php echo get_url('game.php'); ?>">Set your first score!</a>
        <?php endif ?>
    </div>
    <div>
        <h3>Competition History</h3>
        <?php if (count($all_comps) > 0) : ?>
            <table class="table text-dark">
                <thead>
                    <th>Name</th>
                    <th>Expires</th>
                    <th>Current Reward</th>
                    <th>Join Fee</th>
                    <th>Current Participants</th>
                    <th>Min. Participants</th>
                    <th>Score to Qualify</th>
                    <th>1st pl. Reward</th>
                    <th>2nd pl. Reward</th>
                    <th>3rd pl. Reward</th>
                </thead>
                <tbody>
                    <?php foreach ($all_comps as $comp) : ?>
                    <?php $comp_info = get_info_comp($comp['comp_id']); ?>
                        <tr>
                        <td>
                            <?php 
                                $comp_id = $comp_info['id'];
                                $comp_name = $comp_info['name'];
                                include(__DIR__ . "/../../partials/comp_link.php"); 
                            ?>
                        </td>
                        <td><?php echo $comp_info['expires']; ?></td>
                        <td><?php echo $comp_info['current_reward']; ?></td>
                        <td><?php echo $comp_info['join_fee']; ?></td>
                        <td><?php echo $comp_info['current_participants']; ?></td>
                        <td><?php echo $comp_info['min_participants']; ?></td>
                        <td><?php echo $comp_info['min_score']; ?></td>
                        <td><?php echo $comp_info['first_place_per']; ?></td>
                        <td><?php echo $comp_info['second_place_per']; ?></td>
                        <td><?php echo $comp_info['third_place_per']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>   
        <?php endif ?>
        <?php if (count($all_comps) <= 0) : ?>
            <a href="<?php echo get_url('active_competitions.php'); ?>">Join a competition!</a>
        <?php endif ?>
    </div>
    <?php if (!$edit) : ?>
        <div><h3>Username: <?php se($username); ?></h3></div>
        <div><h3>Joined: <?php se($created); ?></h3></div>
        <!-- TODO any other public info -->
    <?php endif; ?>

    <?php if ($isMe && $edit) : ?>
    <form method="POST" onsubmit="return validate(this);">
        <div class="mb-3">
            <div class="form-check form-switch">
                <input name="visibility" class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" <?php if ($public) echo "checked"; ?>>
                <label class="form-check-label" for="flexSwitchCheckDefault">Make Profile Public</label>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label" for="email">Email</label>
            <input class="form-control" type="email" name="email" id="email" value="<?php se($email); ?>" />
        </div>
        <div class="mb-3">
            <label class="form-label" for="username">Username</label>
            <input class="form-control" type="text" name="username" id="username" value="<?php se($username); ?>" />
        </div>
        <!-- DO NOT PRELOAD PASSWORD -->
        <div class="mb-3">Password Reset</div>
        <div class="mb-3">
            <label class="form-label" for="cp">Current Password</label>
            <input class="form-control" type="password" name="currentPassword" id="cp" />
        </div>
        <div class="mb-3">
            <label class="form-label" for="np">New Password</label>
            <input class="form-control" type="password" name="newPassword" id="np" />
        </div>
        <div class="mb-3">
            <label class="form-label" for="conp">Confirm Password</label>
            <input class="form-control" type="password" name="confirmPassword" id="conp" />
        </div>
        <input type="submit" class="mt-3 btn btn-primary" value="Update Profile" name="save" />
    </form>
    <?php endif; ?>

</div>
<script>
    function validate(form) {
        let pw = form.newPassword.value;
        let con = form.confirmPassword.value;
        let isValid = true;
        //TODO add other client side validation....

        //example of using flash via javascript
        //find the flash container, create a new element, appendChild
        if (pw !== con) {
            //find the container
            /*let flash = document.getElementById("flash");
            //create a div (or whatever wrapper we want)
            let outerDiv = document.createElement("div");
            outerDiv.className = "row justify-content-center";
            let innerDiv = document.createElement("div");
            //apply the CSS (these are bootstrap classes which we'll learn later)
            innerDiv.className = "alert alert-warning";
            //set the content
            innerDiv.innerText = "Password and Confirm password must match";
            outerDiv.appendChild(innerDiv);
            //add the element to the DOM (if we don't it merely exists in memory)
            flash.appendChild(outerDiv);*/
            flash("Password and Confirm password must match", "warning");
            isValid = false;
        }
        return isValid;
    }
</script>
<?php
require_once(__DIR__ . "/../../partials/flash.php");
?>

<style>
    .s_pagination {
        display: inline-block;
        padding-left: 0;
        margin: 20px 0;
        border-radius: 4px;
    }
    .s_pagination>li {
        display: inline;
    }

    .s_pagination>li>a,.s_pagination>li>span{
        position: relative;
        float: left;
        padding: 6px 12px;
        margin-left: -1px;
        line-height: 1.42857143;
        color: #337ab7;
        text-decoration: none;
        background-color: #fff;
        border: 1px solid #ddd;
    }
</style>
