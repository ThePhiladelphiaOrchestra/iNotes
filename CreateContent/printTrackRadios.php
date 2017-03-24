<?php
//MODIFY LINE 10 TO CHANGE TO APPROPRIATE DATABASES
session_start();
include('globals.php');

//Select the iNotes database
$success = mysqli_select_db($link, $info_schemas_name);
                    
//Collect field names, excluding measure # and numSeconds
$columns = mysqli_query($link, "
SELECT column_name FROM `COLUMNS`
WHERE TABLE_SCHEMA ='".$dbname."'
AND table_name='" . $name . "' 
AND column_name<>'MeasureNumber' 
AND column_name<>'NumSeconds'
");
// WHERE table_schema='markkoh3_iNotes' 

if ( mysqli_num_rows($columns) > 0 )
{
	$count = 0;
	//Call the measure first
	$first = mysqli_fetch_row($columns);
	
	echo ("<input type='radio' name='track' id=\"". $first[0] . "\" value='" . $first[0] . "' onClick='showTrack(this.value)' checked/>   (" .chr(97+$count).") ". $first[0] . " <img src='lib/imgs/delete.png' onClick=\"deleteTrack('".$first[0]."')\"/> | ");
	$count++;
	
	//Print each field as a radiobutton
	while($current = mysqli_fetch_row($columns)) {
		echo ("
			<input type='radio' name='track' id=\"". $current[0] . "\" value='" . $current[0] . "' onClick='showTrack(this.value)' />   (" .chr(97+$count).") ". $current[0] . " <img src='lib/imgs/delete.png' onClick=\"deleteTrack('".$current[0]."')\"/> | ");
		$count++;
	}
	
	echo ("
			<script type='text/javascript'>showTrack('".$first[0]."');</script>");
}
else
	echo ("No tracks currently in piece.");

mysqli_close($link);

?>