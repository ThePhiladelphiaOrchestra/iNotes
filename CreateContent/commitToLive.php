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
		mysql_select_db($dbname_live);
		
		//Drop the table from the Live database if it exists
		$exists = (mysql_num_rows( mysql_query("SHOW TABLES LIKE '".$_SESSION['name']."'") ) == 1);
		if ($exists)
			mysql_query('DROP TABLE '.$dbname_live.'.`'.$_SESSION['name'].'`');

		//echo('CREATE TABLE `'.$_SESSION['name'].'` LIKE '.$dbname.'.`'.$_SESSION['name'].'`');
		//die();
		//Create  the table in the live database	
		$success = mysql_query('CREATE TABLE '.$dbname_live.'.`'.$_SESSION['name'].'` LIKE '.$dbname.'.`'.$_SESSION['name'].'`');
		
		if (!$success)
			header('Location: selectEdit.php?error=1050');
		else
		{
			//Add all rows from the dev database table to the live database table
			//$success = mysql_query('CREATE TABLE `'.$_SESSION['name'].'` LIKE '.$dbname.'.`'.$_SESSION['name'].'`');	
			$success = mysql_query('INSERT INTO '.$dbname_live.'.`'.$_SESSION['name'].'` SELECT * FROM '.$dbname.'.`'.$_SESSION['name'].'`');	
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
