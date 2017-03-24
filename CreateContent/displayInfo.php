<?php
session_start();
include("globals.php");

//Select the iNotes database
$success = mysqli_select_db($link, $dbname);

//If the user tried to select a measure numbeer higher than the available number of measures, tell them
if ($_SESSION["measure"] > $_SESSION["measures"] || $_SESSION["measure"] < 0)
{
	echo ("I'm sorry, that is not a valid measure number");
}
else
{
	//Grab the current value
	$response =  mysqli_query($link, "SELECT `" . $_SESSION["track"] . "` FROM `" . $_SESSION["name"] . "` WHERE MeasureNumber=" . $_SESSION["measure"]);
	$response = mysqli_fetch_row($response);
	$response = $response[0];
	
	//If there is no current value, print "blank...", otherwise print the data
	if ($response == NULL)
		echo ("Blank...");
	else {
		$_SESSION['measure_response'] = htmlentities($response);
		echo (htmlentities($response));
	}
}

//echo ("SELECT `" . $_SESSION["track"] . "` FROM `" . $_SESSION["name"] . "` WHERE MeasureNumber=" . $_SESSION["measure"]);

mysqli_close($link);	
?>