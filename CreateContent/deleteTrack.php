<?php
session_start();
include("globals.php");

//Select the iNotes database
$success = mysql_select_db($dbname);

$trackName = mysql_escape_string($_REQUEST['track']);

//Send the query with the appropriate variables	
$response =  mysql_query("ALTER TABLE `".$_SESSION['name']."` DROP COLUMN `".$trackName."`");

//Let the user know whether or not the update succeded
if ($response == false)
	echo ("Annotation Update Failed");
else
	echo ("Annotation Update Succeeded");

mysql_close($link);	
?>