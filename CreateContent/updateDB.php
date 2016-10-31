<?php
session_start();
include('globals.php');

//Select the iNotes database
$success = mysql_select_db($dbname);

if ($_SESSION["photo"] == "")
	$newAnnotation = $_SESSION["annotation"];
else
	$newAnnotation = $_SESSION["annotation"]."$".$_SESSION["photo"].",".$_SESSION["caption"];

//Sanatize the user input for the SQL query
$newAnnotation = mysql_escape_string($newAnnotation);
$sql_query_string = ("UPDATE `" . $_SESSION["name"] . "` 
SET `" . $_SESSION["track"] . "` = '" . $newAnnotation . "' 
WHERE MeasureNumber = '" . $_SESSION["measure"] . "'");

//Send the query with the appropriate variables	
mysql_query("SET NAMES 'utf8'");
mysql_query("SET CHARACTER SET 'utf8'");
$response =  mysql_query($sql_query_string);

//Let the user know whether or not the update succeded
if ($response == false)
	echo ("Annotation Update Failed");
else
	echo ("Annotation Update Succeeded");
	//echo($sql_query_string);
mysql_close($link);	
?>