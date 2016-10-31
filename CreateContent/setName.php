<?php
//include("password_protectinotes.php");
	//Start the session so we can keep track of data from page to page
	session_start();
	
	//Connect to the SQL server and the iNotes database
	//include('connectToDB.php');

	if (isset($_REQUEST['index']) && isset($_SESSION['tableNames']))
	{
		$index = $_REQUEST['index'];
		
		//Create a session variable from the POST name string
		$_SESSION['name'] = $_SESSION['tableNames'][$index];
		if( isset($_SESSION['track']) && isset($_SESSION['measure']) )
		{
			unset($_SESSION['track']);
			unset($_SESSION['measure']);
		}
		//$_SESSION['success'] = true;
		
		//echo($_SESSION['name']);
		//echo("<br/>".$_SESSION['success']);
		unset($_SESSION['tableNames']);
		header('Location: edit.php');
	}
	else {
		header('Location: selectEdit.php');
		//print_r($_SESSION);
	}
	//mysql_close($link);
?>
