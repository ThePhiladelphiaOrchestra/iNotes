<?php 
include("password_protectinotes.php");
ini_set("memory_limit", "20000000"); // for large files so that we do not get "Allowed memory exhausted"
ini_set("auto_detect_line_endings",true);  // automatically determine \n, \r, \r\n
session_start();
include("globals.php");

if (isset($_POST["submit"])) {

   if (isset($_FILES["file"])) {

        //If there was an error uploading the file
        if ($_FILES["file"]["error"] > 0) {
			header("Location: uploadCSV.php?error=".$_FILES["file"]["error"]);

        } else {

			//Grab file extension and make sure it's .csv
			$filePieces = explode(".", $_FILES["file"]["name"]);
			$extension = end($filePieces);
			if (strtolower($extension) != "csv") {
				header("Location: uploadCSV.php?error=9");

			} else {
				//Store the file temporarily
				$file = @fopen($_FILES["file"]['tmp_name'],'r');
				
				/*while (!feof($file))
					echo(fgetss($file)."<br/>");*/
				
				//Get the column headers, check the names to make sure they followed instructions
				$firstRow = fgetcsv($file);
				
				//First column name should be "MeasureNumber" or "Measure Number" (not case-sensitive).  Second column name should be "NumSeconds" or "Num Seconds" (not case-sensitive).
				if( (strtolower($firstRow[0]) != "measurenumber" && strtolower($firstRow[0]) != "measure number") || (strtolower($firstRow[1]) != "numseconds" && strtolower($firstRow[1]) != "num seconds") ) {
					header("Location: uploadCSV.php?error=10");

				} else { // The column headers are alright, continue
    				$sessionName = !empty($_SESSION['name']) ? $_SESSION['name'] : null;
    				
					$table_create_query = "CREATE TABLE `" . $sessionName . "`(MeasureNumber int, NumSeconds double";
					$includedTracks = 0;
					
					// If the user included some tracks, append the extra columns to the SQL call
					if ( count($firstRow) > 2 )
					{					
						for ($i=2; $i<count($firstRow); $i++) {
							if ($firstRow[$i] != ""){
								$table_create_query = $table_create_query . ", `".$firstRow[$i]."` text";
								$includedTracks++;
							}
						}
					}
					
					$table_create_query = $table_create_query.")"; //Close the sql call
					
					//echo("Table Create Query: ".$table_create_query."<br/>");
										
					// Let's create the table!
					// Select the iNotes database
					mysqli_select_db($link, $dbname);
					$success = mysqli_query($link, $table_create_query);

					if ( !$success ) {
						header("Location: uploadCSV.php?error=11");  // If we couldn't create the table, tell them to try again

					} else {
						$measure = 0;
						$insert_row_template = "INSERT INTO `" . $_SESSION['name'] . "` VALUES ("; // The string we'll append to in order to add each row
						//Read until the end of the file
						while (!feof($file))
						{
							$currentLine = fgetcsv($file);
							
							if ($currentLine[0] != '' && $currentLine[1] != '')
							{
								$measure++;
								$insert_current_row = $insert_row_template . $currentLine[0] . ", " . $currentLine[1];
								
								// If the file includes more than just measure number and measure time, append the annotations to the query
								if ( $includedTracks > 0 )
								{						
									for ($i=2; $i<2+$includedTracks; $i++)
										$insert_current_row = $insert_current_row . ", " . ( $currentLine[$i] != '' ? ("'".mysqli_real_escape_string($link, $currentLine[$i])."'" ): 'NULL' );
								}
								$insert_current_row = $insert_current_row . ")";
								//echo("Compiled query: ".$insert_current_row."<br/>");
								
								
								$success = mysqli_query($link, $insert_current_row);
								
								//echo($insert_current_row);
								//|| $currentLine[0] != $measure
								if (!$success ) {
									mysqli_query($link, "DROP TABLE `".$_SESSION['name']."`");
									header("Location: uploadCSV.php?error=12&failMeasure=".$currentLine[0]);
								}
							}
						}
						fclose($file);
						header("Location: edit.php");
					}
				}
			}
        }
    } else {
        echo "No file selected <br />";
    } 
}
?>