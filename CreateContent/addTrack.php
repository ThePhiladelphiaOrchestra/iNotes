<?php
session_start();
include("globals.php");

//Select the iNotes database
$success = mysql_select_db($dbname);

//Sanatize the user input for the SQL query
$newTrack = mysql_escape_string($_POST["newTrack"]);

//Send the query with the appropriate variables	
$response =  mysql_query("ALTER TABLE `" . $_SESSION["name"] . "`
ADD `" . $newTrack . "` text");

//Let the user know whether or not the update succeded
if ($response == false)
{
	echo ("Database Update Failed: " . mysql_error());
} else {
	echo ("success");
}

mysql_close($link);	
?>