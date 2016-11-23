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

session_start();
include("globals.php");
//Select the information_schemas database
$success = mysql_select_db($info_schemas_name);

//Collect field names, excluding measure # and numSeconds
$columns = mysql_query("
SELECT column_name FROM `COLUMNS` 
WHERE TABLE_SCHEMA ='".$dbname."'
AND table_name='" . $_SESSION["name"] . "' 
AND column_name<>'MeasureNumber' 
AND column_name<>'NumSeconds'
");

if ( mysql_num_rows($columns) > 0 )
{
	//Fill an array with the track names
	$tracks = array();
	$index = 0;
	while($current = mysql_fetch_row($columns))
	{
		$tracks[$index] = $current[0];
		$index++;
	}	
	

	//Select the iNotes database
	$success = mysql_select_db($dbname);
	
	$annotatedMeasures = mysql_query("
	SELECT * 
	FROM `".$_SESSION["name"]."` 
	WHERE".printSQLTracks($tracks)."");
	
	if ( $annotatedMeasures && (mysql_num_rows($annotatedMeasures) > 0) )
	{
	
		echo ('<table id="nav_table"><tr><td>#</td><td colspan="'.count($tracks).'">Track</td></tr><tr><td></td>');
		for ($i=0;$i<count($tracks);$i++)
			echo("<td>(".chr(97+$i).")</td>");	
		echo('</tr>');
		
		while($current = mysql_fetch_row($annotatedMeasures))
		{
			echo ('
								<tr id="navRow'.$current[0].'"><td>'.$current[0].'</td>');
			for ($i=2; $i<count($current); $i++)
			{
				if ($current[$i] != NULL)
				{
					echo ('
									<td><a href="#" id="'.$tracks[$i-2].'.'.$current[0].'" onclick="jumpTo(\''.addslashes($tracks[$i-2]).'\',\''.$current[0].'\')"> &#10003; </a></td>');
				}
				else
				{
					echo ('
									<td><a href="#" id="'.$tracks[$i-2].'.'.$current[0].'" onclick="jumpTo(\''.addslashes($tracks[$i-2]).'\',\''.$current[0].'\')"> &minus; </a></td>');
				}
			}
			echo ('
								</tr>');
		}
		
		echo('
							</table>
		');
	}
	else
		echo ("No annotations.");
}
else
	echo ("No tracks. Please add one.");

mysql_close($link);	
?>