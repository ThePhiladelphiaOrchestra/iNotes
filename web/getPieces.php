<?php
	//EDIT SERVER INFO
	$server="localhost"; $username="inotes"; $password="inotes"; $datebase_name="content"
	mysql_connect($server,$username,$password) or die(mysql_error());
	mysql_select_db($datebase_name) or die(mysql_error());
	$i = 1;
	$output .= $_GET['database'];
		$result = mysql_query("SELECT * from CurrentConcert") or die(mysql_error());
		while($row = mysql_fetch_array($result)){
			$temp = $row['PieceName'];
			if($i>1)
			{
				$output .= ";$temp";
				$i++;
			}
			else
			{
				$output .= "$temp";
				$i++;
			}
		}
	// Uncomment this to debug when looking at this script in a browser
	//$output = htmlentities($output);
	echo $output; 
	//disconnectDB();
	mysql_close();

?>
