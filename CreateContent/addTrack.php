<?php
session_start();
include("globals.php");

//Select the iNotes database
$success = mysqli_select_db($link, $dbname);

//Sanatize the user input for the SQL query
$newTrack = mysqli_escape_string($_POST["newTrack"]);

//Send the query with the appropriate variables	
$response =  mysqli_query($link, "ALTER TABLE `" . $_SESSION["name"] . "`
ADD `" . $newTrack . "` text");

//Let the user know whether or not the update succeded
if ($response == false)
{
	echo ("Database Update Failed: " . mysqli_error($link));
} else {
	echo ("success");
}

mysqli_close($link);	
?>