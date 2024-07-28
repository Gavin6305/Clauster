<?php
    require(__DIR__ . "/../../partials/nav.php");
    is_logged_in(true);
?>
<?php
    // Check if values from form are set
    if (isset($_POST["compname"]) && isset($_POST["1reward"]) && isset($_POST["2reward"]) && isset($_POST["3reward"]) && 
    isset($_POST["compcost"]) && isset($_POST["duration"]) && isset($_POST["minscore"]) && isset($_POST["minplayers"])) {
        // Values to put into table
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

        // Error checking
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

        // Submitting to Competitions table and adding the creator to the competition
        if (!$hasError) { 
            // Connect to DB and prepare query                  
            $db = getDB();
            $user_id = get_user_id();
            $stmt = $db->prepare(
                "INSERT INTO Competitions (name, duration, starting_reward, join_fee, min_participants, min_score, first_place_per, second_place_per, third_place_per, cost_to_create,
                                            expires, current_reward, current_participants, paid_out)

                VALUES (:name, :duration, :startreward, :joinfee, :minplayer, :minscore, :reward1, :reward2, :reward3, :cost, 
                    ((DATE_ADD(CURRENT_TIMESTAMP, INTERVAL :duration DAY))), :startreward, 1, false);"
            );

            // Add competition
            try {
                try {
                    // Check if user has enough points
                    $fetchuserpoints = getPoints($user_id);
                    if ($fetchuserpoints >= $compcreatecost) {
                        try {
                            // Add the competition to the table
                            $stmt->execute([
                                ":name" => $compname, ":duration" => $duration, ":startreward" => 1, ":joinfee" => $compcost, ":minplayer" => $minplayers,
                                ":minscore" => $minscore, ":reward1" => $reward1, ":reward2" => $reward2, ":reward3" => $reward3, ":cost" => $compcreatecost
                            ]);

                            //Deducts the cost
                            add_points($user_id, -1 * $compcreatecost, "Created competition $compname");
                            
                            // Show success message
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

            // Join creator to competition
            if ($compcreationsuccess) {
                try {
                    // Get competition from Competitions
                    $findcomp = $db->prepare("SELECT id FROM Competitions WHERE (name=:name AND duration=:duration AND join_fee=:joinfee AND min_participants=:minplayer AND 
                                                paid_out=0 AND min_score=:minscore AND first_place_per BETWEEN (:reward1m-0.000001) AND (:reward1p+0.000001) AND second_place_per BETWEEN (:reward2m-0.000001) AND (:reward2p+0.000001));");
                    $findcomp->execute([":name" => $compname, ":duration" => $duration, ":joinfee" => $compcost, ":minplayer" => $minplayers,
                                        ":minscore" => $minscore, ":reward1m" => ($reward1-0.000001), ":reward1p" => ($reward1+0.000001), ":reward2m" => ($reward2-0.000001), ":reward2p" => ($reward2+0.000001)]);
            
                    $compid = $findcomp->fetchAll(PDO::FETCH_ASSOC);

                    // Add creator to CompetitionParticipants
                    $addusertocomp = $db->prepare("INSERT INTO CompetitionParticipants (comp_id, user_id) VALUES (:compid, :uid);");
                    $addusertocomp->execute([":compid" => $compid[0]["id"], ":uid" => get_user_id()]);
                
                } catch (Exception $e) {
                    flash( "Could not join User to competition", "danger");
                    $compcreationsuccess = false;
                }
                
                // Ensures that the variables don't get carried over into the next session
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
    }
?>

<section class="bg-custom py-3 py-md-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-10 col-md-12 col-lg-10 col-xl-6 col-xxl-8">
                <div class="card border border-dark rounded-3 shadow-sm">
                    <div class="card-bg-custom card-body p-3 p-md-4 p-xl-5">
                        <!-- Create competition label -->
                        <div class="col-12">
                            <div class="text-center mb-3">
                                <h1>Create a Competition</h1>
                            </div>
                        </div>
                        <!-- Create competition form -->
                        <form onsubmit="return validate(this)" method="POST">
                            <div class="row gy-2 overflow-hidden">
                                <!-- Competition name input -->
                                <div class="col-12">
                                    <div class="form-floating mb-3">
                                        <input class="form-control" type="text" name="compname" required minlength="2" required value="<?php if(!(empty($compname))) {se($compname);} ?>"/>
                                        <label for="compname" class="tobecleared">Competition Name:</label>
                                    </div>
                                </div>
                                <!-- First place reward input -->
                                <div class="col-12">
                                    <div class="form-floating mb-3">
                                        <input class="form-control" type="number" name="1reward" min="0" max="100" required value="<?php if(!(empty($reward1))) {se($reward1);} ?>"/>
                                        <label for="1reward" class="tobecleared">First Place Reward: %</label>
                                    </div>
                                </div>
                                <!-- Second place reward input -->
                                <div class="col-12">
                                    <div class="form-floating mb-3">
                                        <input class="form-control" type="number" name="2reward" min="0" max="100" required value="<?php if(!(empty($reward2))) {se($reward2);} ?>"/>
                                        <label for="2reward" class="tobecleared">Second Place Reward: %</label> 
                                    </div>
                                </div>
                                <!-- Third place reward input -->
                                <div class="col-12">
                                    <div class="form-floating mb-3">
                                        <input class="form-control" type="number" name="3reward" min="0" max="100" required value="<?php if(!(empty($reward3))) {se($reward3);} ?>"/>
                                        <label for="3reward" class="tobecleared">Third Place Reward: %</label>
                                    </div>
                                </div>
                                <!-- Cost to join input -->
                                <div class="col-12">
                                    <div id="notfreecost" class="form-floating mb-3">
                                        <input class="form-control" type="number" id="notfreecostinput" name="compcost" min="0" required value="<?php if(!(empty($compcost))) {se($compcost);} ?>"/>
                                        <label for="compcost" class="tobecleared">Competition Cost:</label>
                                    </div>
                                </div>
                                <!-- Duration input -->
                                <div class="col-12">
                                    <div class="form-floating mb-3">
                                        <input class="form-control" type="number" name="duration" min="1" required value="<?php if(!(empty($duration))) {se($duration);} ?>"/>
                                        <label for="duration" class="tobecleared">Duration (in days):</label>
                                    </div>
                                </div>
                                <!-- Minimum score input -->
                                <div class="col-12">
                                    <div class="form-floating mb-3">
                                        <input class="form-control" type="number" name="minscore" min="0" required value="<?php if(!(empty($minscore))) {se($minscore);} ?>"/>
                                        <label for="minscore" class="tobecleared">Minimum Score to Qualify:</label>
                                    </div>
                                </div>
                                <!-- Minimum players input -->
                                <div class="col-12">
                                    <div class="form-floating mb-3">
                                        <input class="form-control" type="number" name="minplayers" min="3" required value="<?php if(!(empty($minplayers))) {se($minplayers);} ?>"/>
                                        <label for="minplayers" class="tobecleared">Minimum Amount of Players for Payout:</label>
                                    </div>
                                </div>
                                <!-- Create button -->
                                <div class="col-12">
                                    <div class="d-grid my-3">
                                        <button class="btn btn-custom btn-lg" type="submit">Create Competition (2 points)</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
    require(__DIR__ . "/../../partials/flash.php");
?>
