<?php
	//Start the session so we can keep track of data from page to page
	session_start();
	include("globals.php");
	
	//Make sure the name field wasn't blank	
	if($_POST['name'] == "" || $_POST['name'] == "Enter name...")
		header('Location: create.php?error=1');
	else
	{
		//Select the iNotes database
		$success = mysql_select_db($dbname);
		
		//Create a session variable from the POST name string
		$_SESSION['name'] = mysql_real_escape_string(trim( $_POST['name'] ));
		
		//Try to create the new piece - EDIT: Just check if it exists.
		//store in session whether or not it already existed
		//$_SESSION['success'] = mysql_query("CREATE TABLE `" . $_SESSION['name'] . "`(MeasureNumber int, NumSeconds int)");
		$_SESSION['exists'] = (mysql_num_rows( mysql_query("SHOW TABLES LIKE '" . $_SESSION['name'] . "'") ) == 1);
		
		if (!$_SESSION['exists'])
		{
			header('Location: uploadCSV.php');
		}
		else
		{
			header('Location: edit.php');
		}
		
		mysql_close($link);
	}
	
?>
