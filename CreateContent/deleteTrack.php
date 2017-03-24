<?php
session_start();
include("globals.php");

//Select the iNotes database
$success = mysqli_select_db($link, $dbname);

$trackName = mysqli_escape_string($_REQUEST['track']);

//Send the query with the appropriate variables	
$response =  mysqli_query($link, "ALTER TABLE `".$_SESSION['name']."` DROP COLUMN `".$trackName."`");

//Let the user know whether or not the update succeded
if ($response == false)
	echo ("Annotation Update Failed");
else
	echo ("Annotation Update Succeeded");

mysqli_close($link);	
?>