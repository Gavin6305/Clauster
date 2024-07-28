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
<section class="bg-custom py-3 py-md-5">
    <div class="container">
        <div class="row justify-content-center card-container">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12">
                <div class="card border border-dark rounded-3 shadow-sm">
                    <div class="card-bg-custom card-body p-3 p-md-5 p-xl-5">
                        <!-- Active competitions label -->
                        <h1 class="active-comp-label">Active Competitions</h1>
                        <!-- Competitions table -->
                        <div>
                            <table class="table">
                                <thead class="table-heading text-center">
                                    <th>Name</th>
                                    <th>Expires</th>
                                    <th>Join Fee</th>
                                    <th></th>
                                </thead>
                                <?php if (count($comps_p) > 0) : ?>
                                <tbody class="table-body text-center">
                                    <?php foreach ($comps_p as $comp) : ?>
                                        <tr>    
                                            <td>
                                                <?php 
                                                    $comp_id = se($comp, "id", 0, false);
                                                    $comp_name = $comp["name"];
                                                    include(__DIR__ . "/../../partials/comp_link.php"); 
                                                ?>
                                            </td>
                                            <td><?php se($comp, "expires", "-"); ?></td>
                                            <td><?php se($comp, "join_fee", 0); ?></td>
                                            <td>
                                                <form onsubmit="return validate(this)" method="POST">
                                                    <input class="btn-custom" type= "submit" name = "join" value = "Join"/>
                                                    <input type = "hidden" name = "comp_join" value = "<?php se($comp, 'id', 0) ?>" />
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
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
                </div>
            </div>
        </div>
    </div>
</section>
<style>    
    .active-comp-label {
        text-align: center;
        padding-bottom: 2vh;
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