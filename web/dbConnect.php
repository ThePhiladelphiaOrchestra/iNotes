<?php
function connectDB() {
    //EDIT SERVER INFO
    $server="localhost"; $username="inotes"; $password="inotes"; $datebase_name="content";
    $con = mysqli_connect($server,$username,$password);
    mysqli_set_charset($con, 'utf8');
    if (!$con) {
        die('Could not connect: '.mysql_error());
    }
    mysqli_select_db($con, $datebase_name);
    return $con;
}

function disconnectDB($con) {
    mysqli_close($con);
}
?>
