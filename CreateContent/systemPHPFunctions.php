<?php

//Set the variable 
function getNames() {
	//Select the information_schemas database
	include("globals.php");
	$success = mysql_select_db($info_schemas_name);
				
	//Grab all the table names and exclude the tables we don't want
	$sqlTables = mysql_query("
	SELECT table_name FROM COLUMNS
	WHERE TABLE_SCHEMA = '".$dbname."'
	AND column_name = 'MeasureNumber'
	ORDER BY table_name ASC
	");
	
	$index = 0;
	$tables = array();
                
	if( $sqlTables ) {		
		while($current = mysql_fetch_row($sqlTables)) {
			$tables[$index] = $current[0];
			$index++;
			//echo($current[0]);
		}
		return $tables;
	}
	else
		return "Error!";
	//mysql_close($link);
}

function printTrackRadios() {
	session_start();
	include("globals.php");
	$success = mysql_select_db($info_schemas_name);
	
	if($success) 
	{
		//Collect field names, excluding measure # and numSeconds
		$columns = mysql_query("
		SELECT column_name FROM `COLUMNS`
		WHERE TABLE_SCHEMA ='".$dbname."'
		AND table_name='" . $_SESSION['name'] . "' 
		AND column_name<>'MeasureNumber' 
		AND column_name<>'NumSeconds'
		");
		
		if ( mysql_num_rows($columns) > 0 )
		{
			$count = 0;
			
			//Print the first track and make it selected
			$first = mysql_fetch_row($columns);
			echo ('<input type="radio" name="track" id="'. $first[0] . '" value="' . $first[0] . '" onClick="showTrack(this.value)" checked/>   (' .chr(97+$count).') '. $first[0] . ' <img src="lib/imgs/delete.png" onClick="deleteTrack(\''.$first[0].'\')"/> | ');
			$count++;
			
			//Print the rest of the fields as radio buttons, unselected
			while($current = mysql_fetch_row($columns)) {
					echo ('<input type="radio" name="track" id="'. $current[0] . '" value="' . $current[0] . '" onClick="showTrack(this.value)"/>   (' .chr(97+$count).') '. $current[0] . ' <img src="lib/imgs/delete.png" onClick="deleteTrack(\''.$current[0].'\')"/> | ');
				$count++;
			}
			
			echo ("
					<script type='text/javascript'>showTrack('".$first[0]."');</script>");
		}
		else {
			//$_SESSION["track"] = "";
			echo ("No tracks currently in piece.");
		}
	}
	else
		echo("No piece selected.");
}

?>