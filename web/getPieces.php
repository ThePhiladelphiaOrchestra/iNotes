<?php
	//EDIT SERVER INFO
	$server="localhost"; $username="inotes"; $password="inotes"; $datebase_name="content";
	$con = mysqli_connect($server,$username,$password) or die(mysqli_error($con));
	mysqli_select_db($con, $datebase_name) or die(mysqli_error($con));
	$i = 1;
	$output = !empty($_GET['database']) ? $_GET['database'] : '';
		$result = mysqli_query($con, "SELECT * from CurrentConcert") or die(mysqli_error($con));
		while($row = mysqli_fetch_array($result)){
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
	mysqli_close($con);

?>
