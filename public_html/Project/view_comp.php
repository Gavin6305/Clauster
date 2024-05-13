

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

<div class="container-fluid">
    <h1><?php echo $name; ?></h1>
    <div>
        <table class="table text-dark">
            <thead>
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
                <tr>
                    <td><?php echo $expires ?></td>
                    <td><?php echo $reward ?></td>
                    <td><?php echo $cost ?></td>
                    <td><?php echo $current_part ?></td>
                    <td><?php echo $min_part ?></td>
                    <td><?php echo $min_score ?></td>
                    <td><?php echo $rew1 ?></td>
                    <td><?php echo $rew2 ?></td>
                    <td><?php echo $rew3 ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div>
        <h3>Top 10 Scores</h3>
        <?php if (count($scores) > 0): ?>
        <table class="table text-dark">
            <thead>
                <th>User</th>
                <th>Score</th>
                <th>Time</th>
            </thead>
            <tbody>
                <?php foreach ($scores as $score) : ?>
                    <tr>
                        <td>
                            <?php 
                                $user_id = se($score, "user_id", 0, false);
                                $username = get_info_user($user_id)['username'];
                                include(__DIR__ . "/../../partials/user_profile_link.php"); 
                            ?>
                        </td>
                        <td><?php se($score, "score", 0); ?></td>
                        <td><?php se($score, "created", "-"); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif ?>
        <?php if (count($scores) <= 0): ?>
            <a href="<?php echo get_url('game.php'); ?>">Be the first to set a score in this competition!</a>
        <?php endif ?>
    </div>
</div>