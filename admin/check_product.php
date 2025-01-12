<?php
include('../condb.php');

if(isset($_POST['p_id'])) {
    $p_id = mysqli_real_escape_string($condb, $_POST['p_id']);
    
    $query = mysqli_query($condb, "SELECT p_id FROM tbl_product WHERE p_id = '$p_id'");
    
    if(mysqli_num_rows($query) > 0) {
        echo 'found';
    } else {
        echo 'not_found';
    }
} else {
    echo 'not_found';
}

exit();
?>