<?php
require_once(__DIR__ . "/db.php");
$BASE_PATH = '/Project/'; //This is going to be a helper for redirecting to our base project path since it's nested in another folder
function se($v, $k = null, $default = "", $isEcho = true)
{
    if (is_array($v) && isset($k) && isset($v[$k])) {
        $returnValue = $v[$k];
    } else if (is_object($v) && isset($k) && isset($v->$k)) {
        $returnValue = $v->$k;
    } else {
        $returnValue = $v;
        //added 07-05-2021 to fix case where $k of $v isn't set
        //this is to kep htmlspecialchars happy
        if (is_array($returnValue) || is_object($returnValue)) {
            $returnValue = $default;
        }
    }
    if (!isset($returnValue)) {
        $returnValue = $default;
    }
    if ($isEcho) {
        //https://www.php.net/manual/en/function.htmlspecialchars.php
        echo htmlspecialchars($returnValue, ENT_QUOTES);
    } else {
        //https://www.php.net/manual/en/function.htmlspecialchars.php
        return htmlspecialchars($returnValue, ENT_QUOTES);
    }
}
//TODO 2: filter helpers
function sanitize_email($email = "")
{
    return filter_var(trim($email), FILTER_SANITIZE_EMAIL);
}
function is_valid_email($email = "")
{
    return filter_var(trim($email), FILTER_VALIDATE_EMAIL);
}
//TODO 3: User Helpers
function is_logged_in($redirect = false, $destination = "login.php")
{
    $isLoggedIn = isset($_SESSION["user"]);
    if ($redirect && !$isLoggedIn) {
        flash("You must be logged in to view this page", "warning");
        die(header("Location: $destination"));
    }
    return $isLoggedIn; //se($_SESSION, "user", false, false);
}
function has_role($role)
{
    if (is_logged_in() && isset($_SESSION["user"]["roles"])) {
        foreach ($_SESSION["user"]["roles"] as $r) {
            if ($r["name"] === $role) {
                return true;
            }
        }
    }
    return false;
}
function get_username()
{
    if (is_logged_in()) { //we need to check for login first because "user" key may not exist
        return se($_SESSION["user"], "username", "", false);
    }
    return "";
}
function get_user_email()
{
    if (is_logged_in()) { //we need to check for login first because "user" key may not exist
        return se($_SESSION["user"], "email", "", false);
    }
    return "";
}
function get_user_id()
{
    if (is_logged_in()) { //we need to check for login first because "user" key may not exist
        return se($_SESSION["user"], "id", false, false);
    }
    return false;
}
//TODO 4: Flash Message Helpers
function flash($msg = "", $color = "info")
{
    $message = ["text" => $msg, "color" => $color];
    if (isset($_SESSION['flash'])) {
        array_push($_SESSION['flash'], $message);
    } else {
        $_SESSION['flash'] = array();
        array_push($_SESSION['flash'], $message);
    }
}

function getMessages()
{
    if (isset($_SESSION['flash'])) {
        $flashes = $_SESSION['flash'];
        $_SESSION['flash'] = array();
        return $flashes;
    }
    return array();
}
//TODO generic helpers
function reset_session()
{
    session_unset();
    session_destroy();
}
function users_check_duplicate($errorInfo)
{
    if ($errorInfo[1] === 1062) {
        //https://www.php.net/manual/en/function.preg-match.php
        preg_match("/Users.(\w+)/", $errorInfo[2], $matches);
        if (isset($matches[1])) {
            flash("The chosen " . $matches[1] . " is not available.", "warning");
        } else {
            //TODO come up with a nice error message
            flash("<pre>" . var_export($errorInfo, true) . "</pre>");
        }
    } else {
        //TODO come up with a nice error message
        flash("<pre>" . var_export($errorInfo, true) . "</pre>");
    }
}
function get_url($dest)
{
    global $BASE_PATH;
    if (str_starts_with($dest, "/")) {
        //handle absolute path
        return $dest;
    }
    //handle relative path
    return $BASE_PATH . $dest;
}

function redirect($path)
{ //header headache
    //https://www.php.net/manual/en/function.headers-sent.php#90160
    /*headers are sent at the end of script execution otherwise they are sent when the buffer reaches it's limit and emptied */
    if (!headers_sent()) {
        //php redirect
        die(header("Location: " . get_url($path)));
    }
    //javascript redirect
    echo "<script>window.location.href='" . get_url($path) . "';</script>";
    //metadata redirect (runs if javascript is disabled)
    echo "<noscript><meta http-equiv=\"refresh\" content=\"0;url=" . get_url($path) . "\"/></noscript>";
    die();
}

function save_score($score, $user_id, $showFlash = false)
{
    if ($user_id < 1) {
        //flash("Error saving score, you may not be logged in", "warning");
        return;
    }
    if ($score <= 0) {
        //flash("Scores of zero are not recorded", "warning");
        return;
    }
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO Scores (user_id, score) VALUES (:uid, :score)");
    try {
        $stmt->execute([":uid" => $user_id, ":score" => $score]);
        if ($showFlash) {
            //flash("Saved score of $score", "success");
        }
    } catch (PDOException $e) {
        flash("Error saving score: " . var_export($e->errorInfo, true), "danger");
    }
}

function get_top_10($duration = "day")
{
    $d = "day";
    if (in_array($duration, ["day", "week", "month", "lifetime"])) {
        //variable is safe
        $d = $duration;
    }
    $db = getDB();
    $query = "SELECT user_id, username, score, Scores.created from Scores join Users on Scores.user_id = Users.id";
    if ($d !== "lifetime") {
        //be very careful passing in a variable directly to SQL, I ensure it's a specific value from the in_array() above
        $query .= " WHERE Scores.created >= DATE_SUB(NOW(), INTERVAL 1 $d)";
    }
    //remember to prefix any ambiguous columns (Users and Scores both have created)
    $query .= " ORDER BY score Desc, Scores.created desc LIMIT 10"; //newest of the same score is ranked higher
    error_log($query);
    $stmt = $db->prepare($query);
    $results = [];
    try {
        $stmt->execute();
        $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($r) {
            $results = $r;
        }
    } catch (PDOException $e) {
        error_log("Error fetching scores for $d: " . var_export($e->errorInfo, true));
    }
    return $results;
}

function get_best_score($user_id)
{
    $query = "SELECT score from Scores WHERE user_id = :id ORDER BY score desc LIMIT 1";
    $db = getDB();
    $stmt = $db->prepare($query);
    try {
        $stmt->execute([":id" => $user_id]);
        $r = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($r) {
            return (int)se($r, "score", 0, false);
        }
    } catch (PDOException $e) {
        error_log("Error fetching best score for user $user_id: " . var_export($e->errorInfo, true));
    }
    return 0;
}

function get_latest_scores($user_id, $limit = 10)
{
    if ($limit < 1 || $limit > 50) {
        $limit = 10;
    }
    $query = "SELECT score, created from Scores where user_id = :id ORDER BY created desc LIMIT :limit";
    $db = getDB();
    //IMPORTANT: this is required for the execute to set the limit variables properly
    //otherwise it'll convert the values to a string and the query will fail since LIMIT expects only numerical values and doesn't cast
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    //END IMPORTANT

    $stmt = $db->prepare($query);
    try {
        $stmt->execute([":id" => $user_id, ":limit" => $limit]);
        $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($r) {
            return $r;
        }
    } catch (PDOException $e) {
        error_log("Error getting latest $limit scores for user $user_id: " . var_export($e->errorInfo, true));
    }
    return [];
}

function updatePoints($user_id) {
    $db = getDB();
    try {
        $update = $db->prepare("UPDATE Users SET points = (SELECT IFNULL(SUM(point_change), 0) FROM PointsHistory WHERE user_id = :uid) WHERE id = :uid");
        $update->execute([":uid" => $user_id]);
    }
    catch (PDOException $e) {
        flash("Error saving result: " . var_export($e->errorInfo, true), "danger");
    }
    error_log("Transferring"); 
}

function getPoints($user_id) {
    updatePoints($user_id);
    $db = getDB();
    $stmt = $db->prepare("SELECT points, created from Users where id = :id");
    try {
        $stmt->bindValue(":id", $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($result) {
            return $result[0]['points'];
        }
    }
    catch (PDOException $e) {
        flash("Error getting points for user: " . var_export($e->errorInfo, true), "danger");
    }
}

function get_active_comps($limit = 10)
{
    if ($limit < 1 || $limit > 50) {
        $limit = 10;
    }

    $query = "SELECT id, name, expires, current_reward, join_fee, current_participants, min_participants, min_score, first_place_per, second_place_per, third_place_per 
    FROM Competitions WHERE paid_out = false AND CURRENT_TIMESTAMP < expires ORDER BY expires ASC LIMIT :limit";

    $db = getDB();
    
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    
    $stmt = $db->prepare($query);
    try {
        $stmt->execute([":limit" => $limit]);
        $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($r) {
            return $r;
        }
    } catch (PDOException $e) {
        error_log("Error getting latest $limit Competitions " . var_export($e->errorInfo, true));
    }
    return [];
}

function add_points ($user_id, $points_to_add, $reason) {
    $db = getDB();
    try {
        $updatepoints = $db->prepare("INSERT INTO PointsHistory (user_id, point_change, reason) VALUES (:uid, :cost, :r);");
        $updatepoints->execute([":uid" => $user_id, ":cost" => $points_to_add, ":r" => $reason]);
        updatePoints($user_id);
    }
    catch (Exception $e) {
        flash( "Could not add points to $user_id", "danger");
    }
}

function join_to_comp ($user_id, $comp_id) {
    $db = getDB();
    update_comp($comp_id);
    $stmt = $db->prepare("SELECT comp_id from CompetitionParticipants where user_id = :uid");
    $stmt3 = $db->prepare("SELECT join_fee from Competitions where id = :compid");
    $isRegistered = false;
    try {
        //Checks if comp with this user exists
        $stmt->execute([":uid" => $user_id]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        for ($i = 0; $i < count($result); $i++) {
            if ($result[$i]['comp_id'] == $comp_id) {
                $isRegistered = true;
                flash("You are already registered for this competition", "warning");
            }
        }
        //Checks if user has enough points to join comp
        $stmt3->execute([":compid" => $comp_id]);
        $result3 = $stmt3->fetchAll(PDO::FETCH_ASSOC);
        $joinFee = $result3[0]['join_fee'];
        if (getPoints($user_id) < $joinFee && !$isRegistered) {
            flash("You do not have enough points to join this competition", "warning");
            $isRegistered = true;
        }

        if (!$isRegistered) { //If there is no comp with this user
            try {
                $stmt2 = $db->prepare("INSERT INTO CompetitionParticipants (user_id, comp_id) VALUES (:uid, :cid);"); //Adds entry to CompetitionParticipants
                $stmt2->execute([":uid" => $user_id, ":cid" => $comp_id]);
                flash("Successfully registered for competition!");
                //update competitions here
                update_comp($comp_id);
            }
            catch (Exception $e) {
                flash("Could not register user for competition " /*. var_export($e->errorInfo, true) */, "warning");
            }
        }
    }
    catch (Exception $e) {
        flash("Error occurred during checking user registration status " /*. var_export($e->errorInfo, true) */, "warning");
    }
}

function update_comp ($comp_id) {
    $db = getDB();
    $stmt = $db->prepare("SELECT user_id from CompetitionParticipants where comp_id = :cid");
    try {
        //gets participants from CompetitionParticipants that are in this comp
        $stmt->execute([":cid" => $comp_id]);
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $amount_of_users = count($users);
        //gets participants in comp from Competitions
        $prev_users = get_participants($comp_id);
    
        $points_to_add = $amount_of_users - $prev_users;
        $new_reward = get_current_reward($comp_id) + $points_to_add;

        try {
            $stmt2 = $db->prepare("UPDATE Competitions SET current_participants = (:u), current_reward = (:nr) WHERE id = :cid");
            $stmt2->execute([":u" => $amount_of_users, ":nr" => $new_reward, ":cid" => $comp_id]);
            //flash("Amount of users in $comp_id : $amount_of_users");
        }
        catch (Exception $e) {
            flash("Couldn't update competitions " /*. var_export($e->errorInfo, true) */, "warning");
        }
    }
    catch (Exception $e) {
        flash("Error 2 " /*. var_export($e->errorInfo, true) */, "warning");
    }
}

function get_participants ($comp_id) {
    $db = getDB();
    $stmt = $db->prepare("SELECT current_participants from Competitions where id = :cid");
    try {
        $stmt->execute([":cid" => $comp_id]);
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $users[0]['current_participants'];
    }
    catch (Exception $e) {
        flash("Couldn't get participants " /*. var_export($e->errorInfo, true) */, "warning");
    }
}

function get_current_reward ($comp_id) {
    $db = getDB();
    $stmt = $db->prepare("SELECT current_reward from Competitions where id = :cid");
    try {
        $stmt->execute([":cid" => $comp_id]);
        $reward = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $reward[0]['current_reward'];
    }
    catch (Exception $e) {
        flash("Couldn't get current reward " /*. var_export($e->errorInfo, true) */, "warning");
    }
}

function calc_comp_winners () {
    $db = getDB();
    //Get all expired and not paid_out competitions
    $stmt = $db->prepare("SELECT id, current_participants, min_participants, created, expires, current_reward, first_place_per, second_place_per, third_place_per 
    FROM Competitions WHERE expires < CURRENT_TIMESTAMP AND paid_out = false");
    try {
        $stmt->execute();
        $comps = $stmt->fetchAll(PDO::FETCH_ASSOC);
        //For each competition
        for ($i = 0; $i < count($comps); $i++) {
            $current_participants = $comps[$i]['current_participants'];
            $min_participants = $comps[$i]['min_participants'];
            //Check that the participant count against the minimum required
            if ($current_participants >= $min_participants) {
                $comp_id = $comps[$i]['id'];
                $comp_start = $comps[$i]['created'];
                $comp_expires = $comps[$i]['expires'];
                $first_percent = $comps[$i]['first_place_per'];
                $second_percent = $comps[$i]['second_place_per'];
                $third_percent = $comps[$i]['third_place_per'];
                $reward = $comps[$i]['current_reward'];
                //Get the top 3 winners: 
                //Scores are calculated by the sum of the score from the Scores table where it was earned/created between Competition start and Competition expires timestamps
                $stmt2 = $db->prepare("SELECT user_id FROM CompetitionParticipants WHERE comp_id = :cid");
                try {
                    $stmt2->execute([":cid" => $comp_id]);
                    $users = $stmt2->fetchAll(PDO::FETCH_ASSOC);
                    //Getting top 3 scores with method described above
                    $first_user = 0; $second_user = 0; $third_user = 0;
                    $first_score = 0; $second_score = 0; $third_score = 0;
                    for ($j = 0; $j < count($users); $j++) {
                        $user = $users[$j]['user_id'];
                        $score = get_score_during_comp($user, $comp_start, $comp_expires);
                        if ($score > $third_score) {
                            if ($score > $second_score) {
                                if ($score > $first_score) {
                                    $first_score = $score;
                                    $first_user = $user;
                                }
                                else {
                                    $second_score = $score;
                                    $second_user = $user;
                                }
                            }
                            else {
                                $third_score = $score;
                                $third_user = $user;
                            }
                        }
                    }
                    //Calculate the payout (reward * place_percent)
                    $first_payout = $reward * $first_percent / 100;
                    $second_payout = $reward * $second_percent / 100;
                    $third_payout = $reward * $third_percent / 100;
                    //Round up the value (it’s ok to pay out an extra point here and there)
                    $first_payout = ceil($first_payout);
                    $second_payout = ceil($second_payout);
                    $third_payout = ceil($third_payout);
                    //Create entries for the Users in the PointsHistory table (Updates points column in Users table as well)
                    add_points($first_user, $first_payout, "Competition: first place reward");
                    add_points($second_user, $second_payout, "Competition: second place reward");
                    add_points($third_user, $third_payout, "Competition: third place reward");
                    //Mark the competition as paid_out = true
                    $stmt3 = $db->prepare("UPDATE Competitions SET paid_out = true WHERE id = :cid");
                    try {
                        $stmt3->execute([":cid" => $comp_id]);
                    }
                    catch (Exception $e) {
                        flash("Could not update competition $comp_id as paid out", "warning");
                    }
                }
                catch (Exception $e) {
                    flash("Could not determine top 3 users for competition $comp_id", "warning");
                }
            }
        }
    }
    catch (Exception $e) {
        flash("Could not get expired competitions", "warning");
    }
}

function get_score_during_comp ($user_id, $comp_start, $comp_expires)  {
    $db = getDB();
    $stmt = $db->prepare("SELECT score FROM Scores WHERE user_id = :uid AND created > :cs AND created < :ce");
    try {
        $stmt->execute([":uid" => $user_id, ":cs" => $comp_start, ":ce" => $comp_expires]);
        $scores = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $sum = 0;
        for ($i = 0; $i < count($scores); $i++) {
            $sum += $scores[$i]['score'];
        }
        return $sum;
    }
    catch (Exception $e) {
        flash("Could not get scores for user.");
    }
}

function set_account_private ($user_id) {
    $db = getDB();
    try {
        $update = $db->prepare("UPDATE Users SET is_private = true WHERE user_id = :uid");
        $update->execute([":uid" => $user_id]);
        flash("Account is now private.");
    }
    catch (Exception $e) {
        flash("Could not make account private", "warning");
    }
}

function set_account_public ($user_id) {
    $db = getDB();
    try {
        $update = $db->prepare("UPDATE Users SET is_private = false WHERE user_id = :uid");
        $update->execute([":uid" => $user_id]);
        flash("Account is now public");
    }
    catch (Exception $e) {
        flash("Could not make account public", "warning");
    }
}

function delete_scores($score) {
    $db = getDB();
    try {
        $update = $db->prepare("DELETE FROM Scores WHERE score >= :s");
        $update->execute([":s" => $score]);
    }
    catch (Exception $e) {
        flash("Scores could not delete", "warning");
    }
}

function get_top_10_during ($comp_id) {
    $db = getDB();
    $stmt = $db->prepare("SELECT created, expires FROM Competitions WHERE id = :cid");
    try {
        $stmt->execute([":cid" => $comp_id]);
        $times = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $created = $times[0]['created'];
        $expires = $times[0]['expires'];
        $stmt2 = $db->prepare("SELECT score, user_id, created FROM Scores WHERE created > :c AND created < :e ORDER BY score desc LIMIT 10");
        try {
            $stmt2->execute([":c" => $created, ":e" => $expires]);
            $scores = $stmt2->fetchAll(PDO::FETCH_ASSOC);
            return $scores;
        }
        catch (Exception $e) {
            flash("Could not get top 10 scores for this competition", "warning");
        }
    }
    catch (Exception $e) {
        flash("Could not get timestamps for competition", "warning");
    }
}

function get_info_comp($comp_id) {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM Competitions WHERE id = :cid");
    try {
        $stmt->execute([":cid" => $comp_id]);
        $info = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $info[0];
    }
    catch (Exception $e) {
        flash("Could not get competition info", "danger");
    }
}

function get_info_user ($user_id) {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM Users WHERE id = :uid");
    try {
        $stmt->execute([":uid" => $user_id]);
        $info = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $info[0];
    }
    catch (Exception $e) {
        flash("Could not get user info", "danger");
    }
}

function get_all_latest_scores($user_id)
{

    $query = "SELECT score, created from Scores where user_id = :id ORDER BY created desc";
    $db = getDB();
    $stmt = $db->prepare($query);
    try {
        $stmt->execute([":id" => $user_id]);
        $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($r) {
            return $r;
        }
    } catch (PDOException $e) {
        error_log("Error getting latest scores for user $user_id: " . var_export($e->errorInfo, true));
    }
    return [];
}

function get_latest_comps($user_id, $limit = 10)
{
    if ($limit < 1 || $limit > 50) {
        $limit = 10;
    }
    $query = "SELECT * from CompetitionParticipants where user_id = :id ORDER BY created desc LIMIT :limit";
    $db = getDB();
    //IMPORTANT: this is required for the execute to set the limit variables properly
    //otherwise it'll convert the values to a string and the query will fail since LIMIT expects only numerical values and doesn't cast
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    //END IMPORTANT

    $stmt = $db->prepare($query);
    try {
        $stmt->execute([":id" => $user_id, ":limit" => $limit]);
        $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($r) {
            return $r;
        }
    } catch (PDOException $e) {
        error_log("Error getting latest $limit comps for user $user_id: " . var_export($e->errorInfo, true));
    }
    return [];
}