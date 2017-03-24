<?php
session_start();
include("globals.php");

//Select the iNotes database
$success = mysqli_select_db($link, $dbname);

//Send the query with the appropriate variables	
$response =  mysqli_query($link, "DROP TABLE `" . $_SESSION["name"] . "`");

//Let the user know whether or not the update succeded
if ($response == false)
{
	echo ("Database Update Failed: " . mysqli_error($link));
} else {
	//echo ("success");
	header("Location: index.php");
}

mysqli_close($link);
?>
