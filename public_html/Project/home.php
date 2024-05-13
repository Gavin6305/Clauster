<?php
require(__DIR__ . "/../../partials/nav.php");
?>
<?php
if (is_logged_in(true)) {
    echo "Welcome home, " . get_username();
    //comment this out if you don't want to see the session variables 
    //echo "<pre>" . var_export($_SESSION, true) . "</pre>";
}
?>
<?php
require(__DIR__ . "/../../partials/flash.php");
?>
<h1>Home</h1>
    <div>
        <h3>Game: <a href="<?php echo get_url('game.php'); ?>">Clauster</a></h3>
    </div>
    <div>
        <?php $scoresW = get_top_10("week"); ?>
        <h3>Top Weekly Scores</h3>
        <table class="table text-dark">
            <thead>
                <th>User</th>
                <th>Score</th>
                <th>Time</th>
            </thead>
            <tbody>
                <?php foreach ($scoresW as $score) : ?>
                    <tr>
                        <td>
                            <?php 
                                $user_id = se($score, "user_id", 0, false);
                                $username = se($score, "username", "", false);
                                include(__DIR__ . "/../../partials/user_profile_link.php"); 
                            ?>
                        </td>
                        <td><?php se($score, "score", 0); ?></td>
                        <td><?php se($score, "created", "-"); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div>
        <?php $scoresM = get_top_10("month"); ?>
        <h3>Top Monthly Scores</h3>
        <table class="table text-dark">
            <thead>
                <th>User</th>
                <th>Score</th>
                <th>Time</th>
            </thead>
            <tbody>
                <?php foreach ($scoresM as $score) : ?>
                    <tr>
                        <td>
                            <?php 
                                $user_id = se($score, "user_id", 0, false);
                                $username = se($score, "username", "", false);
                                include(__DIR__ . "/../../partials/user_profile_link.php"); 
                            ?>
                        </td>
                        <td><?php se($score, "score", 0); ?></td>
                        <td><?php se($score, "created", "-"); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div>
        <?php $scoresL = get_top_10("month"); ?>
        <h3>Top Lifetime Scores</h3>
        <table class="table text-dark">
            <thead>
                <th>User</th>
                <th>Score</th>
                <th>Time</th>
            </thead>
            <tbody>
                <?php foreach ($scoresL as $score) : ?>
                    <tr>
                        <td>
                            <?php 
                                $user_id = se($score, "user_id", 0, false);
                                $username = se($score, "username", "", false);
                                include(__DIR__ . "/../../partials/user_profile_link.php"); 
                            ?>
                        </td>
                        <td><?php se($score, "score", 0); ?></td>
                        <td><?php se($score, "created", "-"); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<style>
    h1 {
        text-align: center;
    }
</style>