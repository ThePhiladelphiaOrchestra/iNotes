<?php 
include("password_protectinotes.php"); 
log('TestMessage');
session_start();
$_SESSION = array();
session_destroy();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="lib/style.css" />

<title>iNotes Authoring | Menu</title>
</head>

<body>
<div class="wrapper">
    <div class="constrain_home">
    <!-- START CONTENT -->
		<h1>iNotes Authoring</h1>
        <img src="lib/imgs/spacing.png" height="93px" alt="blank"/>
        <br/>
        <a class="button" href="create.php">Create New</a>
        <a class="button" href="selectEdit.php">Edit</a>
        <!--<br/>
        <a class="button" href="perform.php">Perform</a>-->
    <!-- END CONTENT -->
   		<br/><br/><a href="index.php?logout=1">Logout</a>
    </div>
</div>
</body>
</html>