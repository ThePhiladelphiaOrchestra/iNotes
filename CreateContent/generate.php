<?php
	//Start the session so we can keep track of data from page to page
	session_start();
	include("globals.php");

	//Make sure the name field wasn't blank	
	if($_POST['name'] == "" || $_POST['name'] == "Enter name...")
		header('Location: create.php?empty=1');
		
	//Make sure the measures field wasn't blank	
	if($_REQUEST['measures'] == "")
		header('Location: create.php?error=2');
		
	else
	{
		$measures = $_REQUEST['measures'];
		
		//Select the iNotes database
		$success = mysql_select_db($dbname);
		
		//Create a session variable from the POST name string
		$_SESSION['name'] = mysql_real_escape_string(trim( $_POST['name'] ));
		
		//Try to create the new piece - EDIT: Just check if it exists.
		//store in session whether or not it already existed
		//$_SESSION['success'] = mysql_query("CREATE TABLE `" . $_SESSION['name'] . "`(MeasureNumber int, NumSeconds int)");
		$_SESSION['exists'] = (mysqli_num_rows( mysqli_query($link, "SHOW TABLES LIKE '" . $_SESSION['name'] . "'") ) == 1);
		
		if ($_SESSION['exists'])
		{
			header('Location: edit.php');
		}
		else
		{
			mysqli_select_db($link, $dbname);
			
			//Create the table and fill it with numbers and stuff!			
			$success = mysqli_query($link, "CREATE TABLE `" . $_SESSION['name'] . "`(MeasureNumber int, NumSeconds double)");
			
			for($i=1; $i<=$measures; $i++)
			{
				mysqli_query($link, "INSERT INTO `" . $_SESSION['name'] . "` VALUES (".$i.",NULL)");	
			}
			
			header('Location: edit.php');
			
		}
		
		mysqli_close($link);
	}
?>
