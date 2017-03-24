<?php
$user="inotes";
$pswd="inotes";
$dbname="content_dev";
$dbname_live="content";
$info_schemas_name="information_schema";

$link = mysqli_connect('localhost',$user,$pswd);

/**** To select info schemas ****/
//Select the information_schemas database
//$success = mysql_select_db($info_schemas_name);

/**** To select iNotes database ****/
//Select the iNotes database
//$success = mysql_select_db($dbname);

?>