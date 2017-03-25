<?php
session_start();
include('globals.php');

//Select the iNotes database
$success = mysqli_select_db($link, $dbname);

if ($_SESSION["photo"] == "")
	$newAnnotation = $_SESSION["annotation"];
else
	$newAnnotation = $_SESSION["annotation"]."$".$_SESSION["photo"].",".$_SESSION["caption"];

//Sanatize the user input for the SQL query
$newAnnotation = mysqli_escape_string($link, $newAnnotation);
$sql_query_string = ("UPDATE `" . $_SESSION["name"] . "` SET `" . $_SESSION["track"] . "` = '" . $newAnnotation . "' WHERE MeasureNumber = '" . $_SESSION["measure"] . "'");

//Send the query with the appropriate variables	
mysqli_query($link, "SET NAMES 'utf8'");
mysqli_query($link, "SET CHARACTER SET 'utf8'");
$response =  mysqli_query($link, $sql_query_string);

//Let the user know whether or not the update succeded
if ($response == false)
	echo ("Annotation Update Failed");
else
	echo ("Annotation Update Succeeded");
	//echo($sql_query_string);
mysqli_close($link);	
?>