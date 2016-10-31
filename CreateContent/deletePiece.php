<?php
session_start();
include("globals.php");

//Select the iNotes database
$success = mysql_select_db($dbname);

//Send the query with the appropriate variables	
$response =  mysql_query("DROP TABLE `" . $_SESSION["name"] . "`");

//Let the user know whether or not the update succeded
if ($response == false)
{
	echo ("Database Update Failed: " . mysql_error());
} else {
	//echo ("success");
	header("Location: index.php");
}

mysql_close($link);	
?>