<?php
session_start();

$_SESSION["track"]      = $_POST["track"];
$_SESSION["measure"]    = $_POST["measure"];
//$_SESSION["annotation"] = html_entity_decode($_POST["annotation"]);
$_SESSION["annotation"] = $_POST["annotation"];
$_SESSION["photo"]      = $_POST["photo"];
$_SESSION["caption"]    = $_POST["caption"];
$_SESSION["captionLabel"]    = $_POST["captionLabel"];

// Echo the status of the variables -- for debugging purposes
echo("Session varables status:
name = ".$_SESSION["name"]."
track = ".$_SESSION["track"]."
measure = ".$_SESSION["measure"]."
annotation = ".$_SESSION["annotation"]."
photo = ".$_SESSION["photo"]."
caption = ".$_SESSION["caption"]."
captionLabel =".$_SESSION["captionLabel"]);
?>