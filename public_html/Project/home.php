<?php
require(__DIR__ . "/../../partials/nav.php");
?>
<?php
require(__DIR__ . "/../../partials/flash.php");
?>
<section class="bg-custom py-3 py-md-5">
    <div class="container">
        <div class="row justify-content-center card-container">
            <div class="col-12 col-sm-10 col-md-10 col-lg-10 col-xl-10 col-xxl-10">
                <div class="card border border-dark rounded-3 shadow-sm">
                    <div class="card-bg-custom card-body p-3 p-md-5 p-xl-5">
                        <!-- Home label -->
                        <h1 class="clauster-logo">Clauster</h1>
                        <!-- Weekly scores table -->
                        <div class="top-scores-container">
                            <?php $scoresW = get_top_10("week"); ?>
                            <h5>Top Weekly Scores</h5>
                            <table class="table">
                                <thead class="table-heading text-center">
                                    <th></th>
                                    <th>User</th>
                                    <th>Score</th>
                                    <th>Time</th>
                                </thead>
                                <tbody class="table-body text-center">
                                    <?php $r = 1; ?>
                                    <?php foreach ($scoresW as $score) : ?>
                                        <tr>
                                            <td><?php echo $r++; ?></td>
                                            <td>
                                                <?php 
                                                    $user_id = se($score, "user_id", 0, false);
                                                    $username = se($score, "username", "", false);
                                                    include(__DIR__ . "/../../partials/user_profile_link.php"); 
                                                ?>
                                            </td>
                                            <td><?php se($score, "score", 0); ?></td>
                                            <td><?php echo readable_time(se($score, "created", "-", false)); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- Monthly scores table -->
                        <div class="top-scores-container">
                            <?php $scoresM = get_top_10("month"); ?>
                            <h5>Top Monthly Scores</h5>
                            <table class="table">
                                <thead class="table-heading text-center">
                                    <th></th>
                                    <th>User</th>
                                    <th>Score</th>
                                    <th>Time</th>
                                </thead>
                                <tbody class="table-body text-center">
                                    <?php $r = 1; ?>
                                    <?php foreach ($scoresM as $score) : ?>
                                        <tr>
                                            <td><?php echo $r++; ?></td>
                                            <td>
                                                <?php 
                                                    $user_id = se($score, "user_id", 0, false);
                                                    $username = se($score, "username", "", false);
                                                    include(__DIR__ . "/../../partials/user_profile_link.php"); 
                                                ?>
                                            </td>
                                            <td><?php se($score, "score", 0); ?></td>
                                            <td><?php echo readable_time(se($score, "created", "-", false)); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- Lifetime scores table -->
                        <div class="top-scores-container">
                            <?php $scoresL = get_top_10("lifetime"); ?>
                            <h5>Top Lifetime Scores</h5>
                            <table class="table">
                                <thead class="table-heading text-center">
                                    <th></th>
                                    <th>User</th>
                                    <th>Score</th>
                                    <th>Time</th>
                                </thead>
                                <tbody class="table-body text-center">
                                    <?php $r = 1; ?>
                                    <?php foreach ($scoresL as $score) : ?>
                                        <tr>
                                            <td><?php echo $r++; ?></td>
                                            <td>
                                                <?php 
                                                    $user_id = se($score, "user_id", 0, false);
                                                    $username = se($score, "username", "", false);
                                                    include(__DIR__ . "/../../partials/user_profile_link.php"); 
                                                ?>
                                            </td>
                                            <td><?php se($score, "score", 0); ?></td>
                                            <td><?php echo readable_time(se($score, "created", "-", false)); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<style>
    .top-scores-container {
    padding-bottom: 1vw;
    }

    .clauster-logo {
        padding-bottom: 1vh;
    }
    h1 {
        text-align: center;
    }
</style>