<?php
include("globals.php");
	//Start the session so we can keep track of data from page to page
	session_start();
	
	//Connect to the SQL server and the iNotes database
	//include('connectToDB.php');

	if (isset($_REQUEST['index']) && isset($_SESSION['tableNames']))
	{
		$index = $_REQUEST['index'];
		
		//Create a session variable from the POST name string
		$_SESSION['name'] = $_SESSION['tableNames'][$index];
		
		//Connect to the iNotes database
		mysqli_select_db($link, $dbname_live);
		
		//Drop the table from the Live database if it exists
		$exists = (mysqli_num_rows( mysqli_query($link, "SHOW TABLES LIKE '".$_SESSION['name']."'") ) == 1);
		if ($exists)
			mysqli_query($link, 'DROP TABLE '.$dbname_live.'.`'.$_SESSION['name'].'`');

		//echo('CREATE TABLE `'.$_SESSION['name'].'` LIKE '.$dbname.'.`'.$_SESSION['name'].'`');
		//die();
		//Create  the table in the live database	
		$success = mysqli_query($link, 'CREATE TABLE '.$dbname_live.'.`'.$_SESSION['name'].'` LIKE '.$dbname.'.`'.$_SESSION['name'].'`');
		
		if (!$success)
			header('Location: selectEdit.php?error=1050');
		else
		{
			//Add all rows from the dev database table to the live database table
			//$success = mysql_query('CREATE TABLE `'.$_SESSION['name'].'` LIKE '.$dbname.'.`'.$_SESSION['name'].'`');	
			$success = mysqli_query($link, 'INSERT INTO '.$dbname_live.'.`'.$_SESSION['name'].'` SELECT * FROM '.$dbname.'.`'.$_SESSION['name'].'`');	
			if (!$success)
				header('Location: selectEdit.php?error=1076');
		}
		
		//echo($_SESSION['name']);
		//echo("<br/>".$_SESSION['success']);
		header('Location: selectEdit.php?success=true');
	}
	else
		print_r($_SESSION);
	//mysql_close($link);
?>
