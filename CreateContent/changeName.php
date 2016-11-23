<?php
session_start();
include("globals.php");

//Select the iNotes database
$success = mysql_select_db($dbname);

//Sanatize the user input for the SQL query
$newName = mysql_escape_string($_POST["edit_piece_name"]);

//Send the query with the appropriate variables	
$response =  mysql_query("RENAME TABLE `" . $_SESSION["name"] . "`
TO `" . $newName . "`");

//Let the user know whether or not the update succeded
if ($response == false)
{
	echo ("Database Update Failed: " . mysql_error());
} else {
	//echo ("success");
	$_SESSION["name"] = $newName;
	header("Location: edit.php");
}

mysql_close($link);	
?>