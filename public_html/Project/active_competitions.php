<?php
    require_once(__DIR__ . "/../../partials/nav.php");
    is_logged_in(true);
?>

<?php
    //Join user to comp
    if (isset($_POST["comp_join"])) {
        $compsArr = [];
        $user_id = get_user_id();
        $comp_id = se($_POST, "comp_join", "", false);
        join_to_comp($user_id, $comp_id);
    }

    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM Competitions WHERE paid_out = 0 ORDER BY expires ASC");
    $stmt->execute();
    $all_comps = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $start = 0;  
    $per_page = 10;
    $page_counter = 0;
    $next = $page_counter + 1;
    $previous = $page_counter - 1;

    if(isset($_GET['start'])){
        $start = $_GET['start'];
        $page_counter =  $_GET['start'];
        $start = $start * $per_page;
        $next = $page_counter + 1;
        $previous = $page_counter - 1;
    }

    $stmt2 = $db->prepare("SELECT * FROM Competitions WHERE paid_out = 0 AND expires > CURRENT_TIMESTAMP ORDER BY expires ASC LIMIT $start, $per_page");
    $stmt2->execute();
    $comps_p = $stmt2->fetchAll(PDO::FETCH_ASSOC);

    $paginations = ceil(count($all_comps) / $per_page);


?>



<div class="container-fluid">
<h1>Active Competitions</h1>
    <div>
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
                <th>Join Here</th>
            </thead>
            <?php if (count($comps_p) > 0) : ?>
            <tbody>
                <?php foreach ($comps_p as $comp) : ?>
                    <tr>    
                        <td>
                            <?php 
                                $comp_id = se($comp, "id", 0, false);
                                $comp_name = se($comp, "name", "", false);
                                include(__DIR__ . "/../../partials/comp_link.php"); 
                            ?>
                        </td>
                        <td><?php se($comp, "expires", "-"); ?></td>
                        <td><?php se($comp, "current_reward", 0); ?></td>
                        <td><?php se($comp, "join_fee", 0); ?></td>
                        <td><?php se($comp, "current_participants", 0); ?></td>
                        <td><?php se($comp, "min_participants", 0); ?></td>
                        <td><?php se($comp, "min_score", 0); ?></td>
                        <td><?php se($comp, "first_place_per", 0); ?></td>
                        <td><?php se($comp, "second_place_per", 0); ?></td>
                        <td><?php se($comp, "third_place_per", 0); ?></td>
                        <td>
                            <form onsubmit="return validate(this)" method="POST">
                                <input type= "submit" name = "join" value = "Join"/>
                                <input type = "hidden" name = "comp_join" value = "<?php se($comp, 'id', 0) ?>" />
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <?php endif ?>
            <?php if (!$comps_p) : ?>
                <h5>No scores for this page</h5>
            <?php endif ?>
        </table>
        <center>
            <ul class="pagination">
            <?php
                $url = get_url("active_competitions.php?start=");
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
    </div>   
</div>


<style>    
    h1 {
        text-align: center;
    }
    .pagination {
        display: inline-block;
        padding-left: 0;
        margin: 20px 0;
        border-radius: 4px;
    }
    .pagination>li {
        display: inline;
    }

    .pagination>li>a,.pagination>li>span{
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

<?php
    require(__DIR__ . "/../../partials/flash.php");
?>