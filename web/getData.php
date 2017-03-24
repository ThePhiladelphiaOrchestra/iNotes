<?php
    //mb_internal_encoding("UTF-8");
    //header('Content-Type: text/html; charset=utf-8');
    //include("include/no_caching.php"); //located in include folder in directory,
    require_once('dbConnect.php');
    require_once('Encoding.php');
    $con = connectDB(); //method in dbConnect

    $xml_output  = "<?xml version=\"1.0\"?>";

    $xml_output .= "<concert>";

    $i = 1;

    while(!empty($_GET['database'.$i])){
        $database = $_GET['database'.$i];
        $query = "SELECT * FROM `$database`";
        $resMeas = mysqli_query($con, $query);
        $rows = mysqli_num_rows($resMeas);
        $cols = mysqli_num_fields($resMeas);

        $xml_output .= "<piece>";

        $xml_output .= "<name>";
        $xml_output .= $database ;
        $xml_output .= "</name>";

        $xml_output .= "<measures>";
        $xml_output .= $rows;
        $xml_output .= "</measures>";


        for($y = 1 ; $y < $cols ; $y++){

            $query = "SELECT * FROM `$database`";

            $resMeas = mysqli_query($con, $query);

            $xml_output .= "<track>";
            $xml_output .= "<name>";
            $trackRow = mysqli_fetch_field_direct($resMeas, $y);
            $trackName = $trackRow->name;
            $xml_output .= $trackName;
            $xml_output .= "</name>";
            
            for($x =  1 ;  $x <=  $rows ; $x++){
                $row = mysqli_fetch_assoc($resMeas);
                $measure = $row['MeasureNumber'];
                $time = $row['NumSeconds'];

                $text = $row[$trackName];
             
                if(trim($text) != "" && $text != "NaN"){
                    $xml_output .= "<page>";

                    $xml_output .= "<time>";
                    $xml_output .= $time;
                    $xml_output .= "</time>";
                    
                    $xml_output .= "<measure>";
                    $xml_output .= $measure;
                    $xml_output .= "</measure>";
                    
                    $xml_output .= "<text>";
                    $xml_output .= $text;
                    $xml_output .= "</text>";
                    
                    $xml_output .= "</page>";
                }

            } 
            $xml_output .= "</track>";
        }
        $xml_output  .=  "</piece>"; 
        $i++;
    }
     
    $xml_output .= "</concert>";

    echo $xml_output;
    disconnectDB($con);

?>
