<?php
require_once(__DIR__ . "/../../partials/nav.php");
//is_logged_in(true);

// Get user id from $_GET (the profile page's user)
$user_id = se($_GET, "id", get_user_id(), false);
error_log("user id $user_id");

// Compare $user_id from page to user id from $_SESSION
$isMe = $user_id === get_user_id();

// If edit is present in page's variables allow edit
$edit = !!se($_GET, "edit", false, false); 

// If $user_id from page is not valid, redirect away
if ($user_id < 1) {
    flash("Invalid user", "danger");
    redirect("home.php");
    //die(header("Location: home.php"));
}
?>

<?php
/* Update user account information from edit form */
if (isset($_POST["save"]) && $isMe && $edit) {
    // Connect to DB
    $db = getDB();

    // Get user info from $_POST (update profile form)
    $email = se($_POST, "email", null, false);
    $username = se($_POST, "username", null, false);
    $visibility = !!se($_POST, "visibility", false, false) ? 1 : 0;

    // Error checking
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
        // Update user information
        $params = [":email" => $email, ":username" => $username, ":id" => get_user_id(), ":vis" => $visibility];
        $stmt = $db->prepare("UPDATE Users set email = :email, username = :username, is_public = :vis where id = :id");
        try {
            $stmt->execute($params);
            flash("Profile successfully updated!", "success");
        } 
        catch (Exception $e) {
            users_check_duplicate($e->errorInfo);
        }
    }

    // Set $_SESSION user's email and username with fresh data from table
    $stmt = $db->prepare("SELECT id, email, IFNULL(username, email) as `username` from Users where id = :id LIMIT 1");
    try {
        $stmt->execute([":id" => get_user_id()]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            //$_SESSION["user"] = $user;
            $_SESSION["user"]["email"] = $user["email"];
            $_SESSION["user"]["username"] = $user["username"];
        } 
        else {
            flash("User doesn't exist", "danger");
        }
    } 
    catch (Exception $e) {
        flash("An unexpected error occurred, please try again", "danger");
        //echo "<pre>" . var_export($e->errorInfo, true) . "</pre>";
    }

    // Check/update password
    $current_password = se($_POST, "currentPassword", null, false);
    $new_password = se($_POST, "newPassword", null, false);
    $confirm_password = se($_POST, "confirmPassword", null, false);
    if (!empty($current_password) && !empty($new_password) && !empty($confirm_password)) {
        if ($new_password === $confirm_password) {
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
            } 
            catch (Exception $e) {
                echo "<pre>" . var_export($e->errorInfo, true) . "</pre>";
            }
        } 
        else {
            flash("New passwords don't match", "warning");
        }
    }
}

/* Display user account information */

// If user viewing their own page, then get from $_SESSION, otherwise get from page ($_GET)
$user_id = $isMe ? get_user_id() : se($_GET, "id", -1, false);

// Public info
$username = "";
$created = "";
$best_score = get_best_score($user_id);

// Private info
$email = "";
$points = "";
$public = false;

// Get fresh data from table for user from page
$db = getDB();
$stmt = $db->prepare("SELECT username, created, email, points, is_public from Users where id = :id");

try {
    // Get user attributes from query
    $stmt->execute([":id" => $user_id]);
    $r = $stmt->fetch(PDO::FETCH_ASSOC);
    error_log("user: " . var_export($r, true));
    $username = se($r, "username", "", false);
    $created = se($r, "created", "", false);
    $email = se($r, "email", "", false);
    $points = se($r, "points", "", false);
    $public = se($r, "is_public", "", false);

    // If user is not viewing their own page, and page's user is private
    if (!$isMe && !$public) {
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
    <!-- Profile card -->
    <section class="bg-custom py-3 py-md-5">
        <div class="container">
            <div class="row justify-content-center card-container">
                <div class="col-12 col-sm-10 col-md-12 col-lg-10 col-xl-6 col-xxl-10">
                    <div class="card border border-dark rounded-3 shadow-sm">
                        <div class="card-bg-custom card-body p-3 p-md-5 p-xl-5">
                            <!-- Public info -->
                            <?php if (!$edit) : ?>
                                <!-- Username -->
                                <div class="text-center mb-3">
                                    <h1><?php se($username); ?></h1>
                                </div>
                            <?php endif; ?>
                            <!-- Edit profile button -->
                            <?php if ($isMe) : ?>
                                <div class="col-12">
                                    <div class="d-grid my-3">
                                        <?php if ($edit) : ?>
                                            <div class="text-center mb-3">
                                                <h2>Edit profile</h2>
                                            </div>
                                            <a class="btn btn-custom" href="?">View</a>
                                        <?php else : ?>
                                            <a class="btn btn-custom" href="?edit=true">Edit Profile</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if (!$edit) : ?>
                                <!-- Public info -->
                                <div class="fs-6 fw-normal text-start text-secondary mb-4">
                                    <!-- Best score -->
                                    <?php if ($best_score > 0) : ?>
                                        <h5><?php echo "Best score: " . $best_score; ?></h5>
                                    <?php endif; ?>
                                    <!-- Joined -->
                                    <h5>Joined: <?php se($created); ?></h5>
                                </div>
                                <!-- Private info -->
                                <?php if ($isMe) : ?>
                                    <div class="fs-6 fw-normal text-start text-secondary mb-4">
                                        <!-- Points -->
                                        <h5><?php echo "Points: " . $points; ?></h5>
                                        <!-- Email -->
                                        <h5>Email: <?php se($email); ?></h5>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                            <!-- Tables -->
                            <?php if (!$edit) : ?>
                                <div>
                                    <?php if (count($all_scores) > 0) : ?>
                                        <h2>Scores</h2>
                                        <!-- Score history table -->
                                        <table class="table">
                                            <!-- Heading -->
                                            <thead class="table-heading text-center">
                                                <th>Score</th>
                                                <th>Time</th>
                                            </thead>
                                            <!-- Records -->
                                            <tbody class="table-body text-center">
                                                <?php foreach ($scores_p as $score) : ?>
                                                    <tr>
                                                        <td><?php se($score, "score", 0); ?></td>
                                                        <td><?php echo readable_time(se($score, "created", "-", false)); ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    <?php endif ?> 
                                    <?php if (count($all_scores) <= 0 && $isMe) : ?>
                                        <a href="<?php echo get_url('game.php'); ?>">Set your first score!</a>
                                    <?php endif ?>
                                </div>
                            <?php endif; ?>
                            <!-- Competition history -->
                            <?php if (!$edit) : ?>
                                <div>
                                    <?php if (count($all_comps) > 0) : ?>
                                        <h2>Competitions</h2>
                                        <table class="table">
                                            <thead class="table-heading text-center">
                                                <th>Name</th>
                                                <th>Expires</th>
                                            </thead>
                                            <tbody class="table-body text-center">
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
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>   
                                    <?php endif ?>
                                    <?php if (count($all_comps) <= 0 && $isMe) : ?>
                                        <a href="<?php echo get_url('active_competitions.php'); ?>">Join a competition!</a>
                                    <?php endif ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Edit account section -->
    <?php if ($isMe && $edit) : ?>
    <section class="bg-custom py-3 py-md-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-sm-10 col-md-9 col-lg-7 col-xl-6 col-xxl-10">
                    <div class="card border border-dark rounded-3 shadow-sm">
                        <div class="card-bg-custom card-body p-3 p-md-4 p-xl-5">
                            <!-- Edit account form -->
                            <form method="POST" onsubmit="return validate(this);">
                                <!-- Make profile public/private -->
                                <div class="col-12">
                                    <div class="form-floating mb-3">
                                        <div class="form-check form-switch">
                                            <input name="visibility" class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" <?php if ($public) echo "checked"; ?>>
                                            <label class="form-check-label" for="flexSwitchCheckDefault">Make Profile Public</label>
                                        </div>
                                    </div>
                                </div>
                                <!-- Email input -->
                                <div class="col-12">
                                    <div class="form-floating mb-3">
                                        <input class="form-control" type="email" name="email" id="email" value="<?php se($email); ?>" />
                                        <label class="form-label" for="email">Email</label>
                                    </div>
                                </div>
                                <!-- Username input -->
                                <div class="col-12">
                                    <div class="form-floating mb-3">
                                        <input class="form-control" type="text" name="username" id="username" value="<?php se($username); ?>" />
                                        <label class="form-label" for="username">Username</label>
                                    </div>
                                </div>
                                <!-- Password reset label -->
                                <div class="col-12">
                                    <div class="form-floating mb-3">Password Reset</div>
                                </div>
                                <!-- Current password input -->
                                <div class="col-12">
                                    <div class="form-floating mb-3">
                                        <input class="form-control" type="password" name="currentPassword" id="cp" />
                                        <label class="form-label" for="cp">Current Password</label>
                                    </div>
                                </div>
                                <!-- New password input -->
                                <div class="col-12">
                                    <div class="form-floating mb-3">
                                        <input class="form-control" type="password" name="newPassword" id="np" />
                                        <label class="form-label" for="np">New Password</label>
                                    </div>
                                </div>
                                <!-- Confirm password input -->
                                <div class="col-12">
                                    <div class="form-floating mb-3">
                                        <input class="form-control" type="password" name="confirmPassword" id="conp" />
                                        <label class="form-label" for="conp">Confirm Password</label>  
                                    </div>
                                </div>
                                <!-- Update profile button -->
                                <div class="col-12">
                                    <div class="d-grid my-3">
                                        <input type="submit" class="btn btn-custom" value="Update Profile" name="save" />
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
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
