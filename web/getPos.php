<?php

//include("include/no_caching.php"); //located in include folder in directory,
require_once('dbConnect.php');

connectDB();
$currentMeas_Query = "SELECT * FROM currentMeasure";

$resMeas = mysql_query($currentMeas_Query);

$rowMeas = mysql_fetch_array($resMeas);

$currentMeas = $rowMeas['currentMeasure'];

$currentPiece = $rowMeas['currentPiece'];


disconnectDB();
echo $currentPiece.";".$currentMeas;

