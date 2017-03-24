<?php
session_start();
include("globals.php");

//Select the iNotes database
$success = mysqli_select_db($link, $dbname);

//Sanatize the user input for the SQL query
$newName = mysqli_escape_string($link, $_POST["edit_piece_name"]);

//Send the query with the appropriate variables
$response =  mysqli_query($link, "RENAME TABLE `" . $_SESSION["name"] . "` TO `" . $newName . "`");

//Let the user know whether or not the update succeded
if ($response == false) {
	echo ("Database Update Failed: " . mysqli_error($link));
} else {
	//echo ("success");
	$_SESSION["name"] = $newName;
	header("Location: edit.php");
}

mysqli_close($link);	
?>
