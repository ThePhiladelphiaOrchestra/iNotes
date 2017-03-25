<?php
//MODIFY LINE 19 TO CHANGE TO APPROPRIATE DATABASES

function printSQLTracks($trks)
{
	$call = "";
	for ($i=0; $i<count($trks)-1;$i++)
		$call = $call." `".$trks[$i]."` <> '' OR";
	$call = $call." `".$trks[$i]."` <> '' ";
	return $call;
}

if (!isset($_SESSION)) {
    session_start();
}
include("globals.php");
//Select the information_schemas database
$success = mysqli_select_db($link, $info_schemas_name);

$name = !empty($_SESSION['name']) ? $_SESSION['name'] : '';

//Collect field names, excluding measure # and numSeconds
$columns = mysqli_query($link, "
SELECT column_name FROM `COLUMNS` 
WHERE TABLE_SCHEMA ='".$dbname."'
AND table_name='" . $name . "' 
AND column_name<>'MeasureNumber' 
AND column_name<>'NumSeconds'
");

if ( mysqli_num_rows($columns) > 0 )
{
	//Fill an array with the track names
	$tracks = array();
	$index = 0;
	while($current = mysqli_fetch_row($columns))
	{
		$tracks[$index] = $current[0];
		$index++;
	}	
	

	//Select the iNotes database
	$success = mysqli_select_db($link, $dbname);
	
	$annotatedMeasures = mysqli_query($link, "
	SELECT * 
	FROM `".$_SESSION["name"]."` 
	WHERE".printSQLTracks($tracks)."");
	
	if ( $annotatedMeasures && (mysqli_num_rows($annotatedMeasures) > 0) ) {
	
		echo ('<table id="nav_table"><tr><td>#</td><td colspan="'.count($tracks).'">Track</td></tr><tr><td></td>');
		for ($i=0;$i<count($tracks);$i++) {
			echo("<td>(".chr(97+$i).")</td>");
		}
		echo('</tr>');
		
		while($current = mysqli_fetch_row($annotatedMeasures)){
			echo ('<tr id="navRow'.$current[0].'"><td>'.$current[0].'</td>');
			for ($i=2; $i<count($current); $i++) {
				if ($current[$i] != NULL) {
					echo ('<td><a href="#" id="'.$tracks[$i-2].'.'.$current[0].'" onclick="jumpTo(\''.addslashes($tracks[$i-2]).'\',\''.$current[0].'\')"> &#10003; </a></td>');
				} else {
					echo ('<td><a href="#" id="'.$tracks[$i-2].'.'.$current[0].'" onclick="jumpTo(\''.addslashes($tracks[$i-2]).'\',\''.$current[0].'\')"> &minus; </a></td>');
				}
			}
			echo ('</tr>');
		}
		
		echo('</table>');
	} else {
		echo ("No annotations.");
    }
} else {
	echo ("No tracks. Please add one.");
}

mysqli_close($link);	
?>
