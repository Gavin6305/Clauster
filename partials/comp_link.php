<?php
if (!isset($comp_id)) {
    $comp_id = 0;
}
if (!isset($comp_name)) {
    $comp_name = "";
}
?>
<a href="<?php 
            echo get_url("view_comp.php?id=");
            se($comp_id); 
        ?>"
        ><?php echo $comp_name; ?></a>