<?php
//Start the session so we can keep track of data from page to page
session_start();
include("globals.php");

//Make sure the name field wasn't blank
if ($_POST['name'] == "" || $_POST['name'] == "Enter name...") {
    header('Location: create.php?error=1');
} else {
    //Select the iNotes database
    $success = mysqli_select_db($link, $dbname);

    //Create a session variable from the POST name string
    $_SESSION['name'] = mysqli_real_escape_string($link, trim( $_POST['name'] ));

    //Try to create the new piece - EDIT: Just check if it exists.
    //store in session whether or not it already existed
    //$_SESSION['success'] = mysql_query("CREATE TABLE `" . $_SESSION['name'] . "`(MeasureNumber int, NumSeconds int)");
    $tables = mysqli_query($link, "SHOW TABLES LIKE '" . $_SESSION['name'] . "'");

    if ($tables !== false) {
        $_SESSION['exists'] = (mysqli_num_rows( $tables ) == 1);
    }
}

if (empty($_SESSION['exists']) || !$_SESSION['exists']) {
    header('Location: uploadCSV.php');
} else {
    header('Location: edit.php');
}

mysqli_close($link);
	
?>
