

<?php
require_once(__DIR__ . "/../../partials/nav.php");

    $comp_id = $_GET['id'];
    $comp_info = get_info_comp($comp_id);

    $name = $comp_info["name"];
    $expires = $comp_info["expires"];
    $reward = $comp_info["current_reward"];
    $cost = $comp_info["join_fee"];
    $current_part = $comp_info["current_participants"];
    $min_part = $comp_info["min_participants"];
    $min_score = $comp_info["min_score"];
    $rew1 = $comp_info["first_place_per"];
    $rew2 = $comp_info["second_place_per"];
    $rew3 = $comp_info["third_place_per"];

    $scores = get_top_10_during($comp_id);
?>

<section class="bg-custom py-3 py-md-5">
    <div class="container">
        <div class="row justify-content-center card-container">
            <div class="col-12 col-sm-10 col-md-12 col-lg-12 col-xl-12 col-xxl-12">
                <div class="card border border-dark rounded-3 shadow-sm">
                    <div class="card-bg-custom card-body p-3 p-md-5 p-xl-5">
                        <!-- Competition name -->
                        <h1 class="view-comp-name"><?php echo $name; ?></h1>
                        <!-- Competition information -->
                        <div class="view-comp-info-container">
                            <table class="table text-dark">
                                <thead class="table-heading text-center">
                                    <th colspan="2">Information</th>
                                </thead>
                                <tbody class="table-body">
                                    <tr><td class="text-start">Expires:</td><td class="text-start"><?php echo readable_time($expires) ?></td></tr>
                                    <tr><td class="text-start">Current Reward:</td><td class="text-start"><?php echo $reward ?></td></tr>
                                    <tr><td class="text-start">Cost to Join:</td><td class="text-start"><?php echo $cost ?></td></tr>
                                    <tr><td class="text-start">Current Participants:</td><td class="text-start"><?php echo $current_part ?></td></tr>
                                    <tr><td class="text-start">Min. Participants for Payout:</td><td class="text-start"><?php echo $min_part ?></td></tr>
                                    <tr><td class="text-start">Min. Score to Qualify:</td><td class="text-start"><?php echo $min_score ?></td></tr>
                                    <tr><td class="text-start">1st Place Reward:</td><td class="text-start"><?php echo $rew1 ?>%</td></tr>
                                    <tr><td class="text-start">2nd Place Reward:</td><td class="text-start"><?php echo $rew2 ?>%</td></tr>
                                    <tr><td class="text-start">3rd Place Reward:</td><td class="text-start"><?php echo $rew3 ?>%</td></tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- Top 10 score table -->
                        <div>
                            <!-- Top 10 scores table -->
                            <?php if (count($scores) > 0): ?>
                            <table class="table text-dark">
                                <thead class="table-heading text-center">
                                    <th colspan="4">Top 10 Scores</th>
                                </thead>
                                <tbody class="table-body text-center">
                                    <?php $i = 1; ?>
                                    <?php foreach ($scores as $score) : ?>
                                        <tr>
                                            <td><?php echo $i++ ?></td>
                                            <td>
                                                <?php 
                                                    $user_id = se($score, "user_id", 0, false);
                                                    $username = get_info_user($user_id)['username'];
                                                    include(__DIR__ . "/../../partials/user_profile_link.php"); 
                                                ?>
                                            </td>
                                            <td><?php se($score, "score", 0); ?></td>
                                            <td><?php echo readable_time(se($score, "created", "-", false)); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <?php endif ?>
                            <?php if (count($scores) <= 0 && is_logged_in()): ?>
                                <a href="<?php echo get_url('game.php'); ?>">Be the first to set a score in this competition!</a>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<style>
    .view-comp-name {
        text-align: center;
        padding-bottom: 2vh;
    }
    .view-comp-info-container {
        padding-bottom: 2vh;
    }
    .view-comp-top10-label {
        padding-bottom: 1vh;
    }
</style>
