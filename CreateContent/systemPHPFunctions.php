<?php

//Set the variable 
function getNames() {
	//Select the information_schemas database
	include("globals.php");
	$success = mysqli_select_db($link, $info_schemas_name);
				
	//Grab all the table names and exclude the tables we don't want
	$sqlTables = mysqli_query($link, "
	SELECT table_name FROM COLUMNS
	WHERE TABLE_SCHEMA = '".$dbname."'
	AND column_name = 'MeasureNumber'
	ORDER BY table_name ASC
	");
	
	$index = 0;
	$tables = array();
                
	if( $sqlTables ) {		
		while($current = mysqli_fetch_row($sqlTables)) {
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
    if (!isset($_SESSION)) {
        session_start();
    }
	include("globals.php");
	$success = mysqli_select_db($link, $info_schemas_name);
	
	if($success) 
	{
    	$table = !empty($_SESSION['name']) ? $_SESSION['name'] : '';
    	
		//Collect field names, excluding measure # and numSeconds
		$columns = mysqli_query($link, "
    		SELECT column_name FROM `COLUMNS`
    		WHERE TABLE_SCHEMA ='".$dbname."'
    		AND table_name='" . $table . "' 
    		AND column_name<>'MeasureNumber' 
    		AND column_name<>'NumSeconds'
		");
		
		if ( mysqli_num_rows($columns) > 0 )
		{
			$count = 0;
			
			//Print the first track and make it selected
			$first = mysqli_fetch_row($columns);
			echo ('<input type="radio" name="track" id="'. $first[0] . '" value="' . $first[0] . '" onClick="showTrack(this.value)" checked/>   (' .chr(97+$count).') '. $first[0] . ' <img src="lib/imgs/delete.png" onClick="deleteTrack(\''.$first[0].'\')"/> | ');
			$count++;
			
			//Print the rest of the fields as radio buttons, unselected
			while($current = mysqli_fetch_row($columns)) {
					echo ('<input type="radio" name="track" id="'. $current[0] . '" value="' . $current[0] . '" onClick="showTrack(this.value)"/>   (' .chr(97+$count).') '. $current[0] . ' <img src="lib/imgs/delete.png" onClick="deleteTrack(\''.$current[0].'\')"/> | ');
				$count++;
			}
			
			echo ("<script type='text/javascript'>showTrack('".$first[0]."');</script>");
		}
		else {
			echo ("No tracks currently in piece.");
		}
	}
	else
		echo("No piece selected.");
}

?>
