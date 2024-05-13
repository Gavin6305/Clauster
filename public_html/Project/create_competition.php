<?php
    require(__DIR__ . "/../../partials/nav.php");
    is_logged_in(true);
?>
<?php
    if (isset($_POST["compname"]) && isset($_POST["1reward"]) && isset($_POST["2reward"]) && isset($_POST["3reward"]) && 
    isset($_POST["compcost"]) && isset($_POST["duration"]) && isset($_POST["minscore"]) && isset($_POST["minplayers"])) {
        //Values to put into table
        try {
            $compname = se($_POST, "compname", "", false);
            $reward1 = se($_POST, "1reward", "", false);
            $reward2 = se($_POST, "2reward", "", false);
            $reward3 = se($_POST, "3reward", "", false);
            $compcost = se($_POST, "compcost", "", false);
            $duration = se($_POST, "duration", "", false);
            $minscore = se($_POST, "minscore", "", false);
            $minplayers = se($_POST, "minplayers", "", false);
            $compcreatecost = 2;
        }  
        catch (Exception $e) {
            flash("<pre>" . "Could not submit competition" . "</pre>", "danger");
        }
        //end values to put in table
        $hasError = false;
        $compcreationsuccess = false;
        /* User friendly messages */
        if (empty($compname)) {
            flash("Competition must have a name", "warning");
            $hasError = true;
        }
        if (empty($reward1)) {
            flash("Include a first place reward", "warning");
            $hasError = true;
        }
        if (empty($reward2)) {
            flash("Include a second place reward", "warning");
            $hasError = true;
        }
        if (empty($reward3)) {
            flash("Include a third place reward", "warning");
            $hasError = true;
        }
        if (($reward1 + $reward2 + $reward3) != 100) {
            flash ("The rewards must equal a total of 100%", "warning");
            $hasError = true;
        } else {
            $reward1 /= 100;
            $reward1 = round($reward1, 2);
            $reward1 *= 100;
            $reward2 /= 100;
            $reward2 = round($reward2, 2);
            $reward2 *= 100;
            $reward3 /= 100;
            $reward3 = round($reward3, 2);
            $reward3 *= 100;
        }
        if (empty($compcost) && $compcost != "0") {
            flash("Competition must have a cost", "warning");
            $hasError = true;
        } else {
            $compcost = (int)$compcost;
        }
        if (empty($duration)) {
            flash("Competition must have a duration", "warning");
            $hasError = true;
        } else {
            $duration = (int)$duration;
        }
        if (empty($minscore)) {
            flash("Competition must have a minimum score to qualify", "warning");
            $hasError = true;
        } else {
            $minscore = (int)$minscore;
        }
        if (empty($minplayers)) {
            flash("Specify a minimum amount of players for payout", "warning");
            $hasError = true;
        } else {
            $minplayers = (int)$minplayers;
        }
        //end user friendly messages

        //submitting to Competitions table
        if (!$hasError) {                   
            $db = getDB();
            $user_id = get_user_id();
            $stmt = $db->prepare(
                "INSERT INTO Competitions (name, duration, starting_reward, join_fee, min_participants, min_score, first_place_per, second_place_per, third_place_per, cost_to_create,
                                            expires, current_reward, current_participants, paid_out)

                VALUES (:name, :duration, :startreward, :joinfee, :minplayer, :minscore, :reward1, :reward2, :reward3, :cost, 
                    ((DATE_ADD(CURRENT_TIMESTAMP, INTERVAL :duration DAY))), :startreward, 1, false);"
            );
            try {
                try {
                    $fetchuserpoints = getPoints($user_id);
                    if ($fetchuserpoints >= $compcreatecost) { //Checks if user has enough points
                        try {
                            //Adds the competition to the table
                            $stmt->execute([
                                ":name" => $compname, ":duration" => $duration, ":startreward" => 1, ":joinfee" => $compcost, ":minplayer" => $minplayers,
                                ":minscore" => $minscore, ":reward1" => $reward1, ":reward2" => $reward2, ":reward3" => $reward3, ":cost" => $compcreatecost
                            ]);
                            //Deducts the cost
                            add_points($user_id, -1 * $compcreatecost, "Created competition $compname");
                            
                            flash("Competition Created!", "success");
                            $compcreationsuccess = true;
                        } 
                        catch (Exception $e) {
                            flash("Could not submit competition: " . $reward1 + $reward2 + $reward3);
                            $compcreationsuccess = false;
                        }
                    } 
                    else {
                        flash("You don't have enough points", "warning");
                        $compcreationsuccess = false;
                    }
                } 
                catch (Exception $e) {
                    flash( "Couldn't retrieve data", "danger");
                    $compcreationsuccess = false;
                }
            } 
            catch (Exception $e) {
                flash( "Unknown Error", "danger");
                $compcreationsuccess = false;
            }
            if ($compcreationsuccess) {
                //Joins the creator to the competition
                try {
                    $findcomp = $db->prepare("SELECT id FROM Competitions WHERE (name=:name AND duration=:duration AND join_fee=:joinfee AND min_participants=:minplayer AND 
                                                paid_out=0 AND min_score=:minscore AND first_place_per BETWEEN (:reward1m-0.000001) AND (:reward1p+0.000001) AND second_place_per BETWEEN (:reward2m-0.000001) AND (:reward2p+0.000001));");
                    $findcomp->execute([":name" => $compname, ":duration" => $duration, ":joinfee" => $compcost, ":minplayer" => $minplayers,
                                        ":minscore" => $minscore, ":reward1m" => ($reward1-0.000001), ":reward1p" => ($reward1+0.000001), ":reward2m" => ($reward2-0.000001), ":reward2p" => ($reward2+0.000001)]);
            
                    $compid = $findcomp->fetchAll(PDO::FETCH_ASSOC);
                    $addusertocomp = $db->prepare("INSERT INTO CompetitionParticipants (comp_id, user_id) VALUES (:compid, :uid);");
                    $addusertocomp->execute([":compid" => $compid[0]["id"], ":uid" => get_user_id()]);
                
                } catch (Exception $e) {
                    flash( "Could not join User to competition", "danger");
                    $compcreationsuccess = false;
                }
                //Ensures that the variables don't get carried over into the next session
                echo "<script> (function() {var clear = document.getElementsByClassName('tobecleared'); 
                    var test = document.getElementById('TEST'); test.innerHTML = clear; }) </script>";
                $compname = "";
                $reward1 = "";
                $reward2 = "";
                $reward3 = "";
                $compcost = "";
                $duration = "";
                $minscore = "";
                $minplayers = "";
            }
        }
    }?>

<div class="container-fluid">
    <h1>Create Competition</h1>
    <div class="column" id="newcomp">
        <form onsubmit="return validate(this)" method="POST">
            <div>
                <label for="compname" class="tobecleared">Competition Name:</label>
                <input type="text" name="compname" required minlength="2" required value="<?php if(!(empty($compname))) {se($compname);} ?>"/>
            </div>
            <div>
                <label for="1reward" class="tobecleared">First Place Reward: %</label>
                <input type="number" name="1reward" min="0" max="100" required value="<?php if(!(empty($reward1))) {se($reward1);} ?>"/>
            </div>
            <div>
                <label for="2reward" class="tobecleared">Second Place Reward: %</label>
                <input type="number" name="2reward" min="0" max="100" required value="<?php if(!(empty($reward2))) {se($reward2);} ?>"/>
            </div>
            <div>
                <label for="3reward" class="tobecleared">Third Place Reward: %</label>
                <input type="number" name="3reward" min="0" max="100" required value="<?php if(!(empty($reward3))) {se($reward3);} ?>"/>
            </div>
            <div>
                <label for="checkfree">Free to join?</label>
                <input type="checkbox" id="isfree" name="checkfree" onclick="freeclick()"/>
            </div>
            <div id="notfreecost">
                <label for="compcost" class="tobecleared">Competition Cost:</label>
                <input type="number" id="notfreecostinput" name="compcost" min="0" required value="<?php if(!(empty($compcost))) {se($compcost);} ?>"/>
            </div>
            <div>
                <label for="duration" class="tobecleared">Duration (in days):</label>
                <input type="number" name="duration" min="1" required value="<?php if(!(empty($duration))) {se($duration);} ?>"/>
            </div>
            <div>
                <label for="minscore" class="tobecleared">Minimum Score to Qualify:</label>
                <input type="number" name="minscore" min="0" required value="<?php if(!(empty($minscore))) {se($minscore);} ?>"/>
            </div>
            <div>
                <label for="minplayers" class="tobecleared">Minimum Amount of Players for Payout:</label>
                <input type="number" name="minplayers" min="3" required value="<?php if(!(empty($minplayers))) {se($minplayers);} ?>"/>
            </div>
            <div><p><?php echo "The cost of creating the competition is: " . 1+1;?></p></div>
            <input type="submit" value="Create" />
        </form>

<?php
    require(__DIR__ . "/../../partials/flash.php");
?>
