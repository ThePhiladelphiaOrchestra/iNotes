<?php
function connectDB() {
	//EDIT SERVER INFO
	$server="localhost"; $username="inotes"; $password="inotes"; $datebase_name="content"
    $con = mysql_connect($server,$username,$password);
    mysql_set_charset('utf8', $con);
    if (!$con) {
        die('Could not connect: '.mysql_error());
    }
    mysql_select_db($datebase_name,$con);
}

function disconnectDB() {
    mysql_close();
}
?>
