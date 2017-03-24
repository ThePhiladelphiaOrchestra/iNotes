<?php

//include("include/no_caching.php"); //located in include folder in directory,
require_once('dbConnect.php');

$con = connectDB();
$currentMeas_Query = "SELECT * FROM currentMeasure";

$resMeas = mysqli_query($con, $currentMeas_Query);

$rowMeas = mysqli_fetch_array($resMeas);

$currentMeas = $rowMeas['currentMeasure'];

$currentPiece = $rowMeas['currentPiece'];

disconnectDB($con);
echo $currentPiece.";".$currentMeas;
