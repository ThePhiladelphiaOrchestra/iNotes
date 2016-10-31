<?php 
// include("password_protectinotes.php");
ini_set("memory_limit", "20000000"); // for large files so that we do not get "Allowed memory exhausted"
session_start();
// $myFile = "/var/www/CreateContent/testFile.log";
// error_log("You messed up!", 3, $myFile);
if ( isset($_POST["submit"]) ) 
{
   if ( isset($_FILES["file"])) 
   {
        //If there was an error uploading the file
        if ($_FILES["file"]["error"] > 0)
			header("Location: uploadCSV.php?error=".$_FILES["file"]["error"]);
        else 
		{
			//Grab file extension and make sure it's .csv
			$extension = end(explode(".", $_FILES["file"]["name"]));
			if($extension != "csv")
				header("Location: uploadCSV.php?error=9");				
			else
			{
				//Store the file temporarily
				$file = @fopen($_FILES["file"]['tmp_name'],'r');
				
				//Get the column headers, check the names to make sure they followed instructions
				$firstRow = explode(',', trim(fgets($file)));
				
				//First column name should be "MeasureNumber" or "Measure Number" (not case-sensitive).  Second column name should be "NumSeconds" or "Num Seconds" (not case-sensitive).
				if( (strtolower($firstRow[0]) != "measurenumber" && strtolower($firstRow[0]) != "measurenumber") && (strtolower($firstRow[1]) != "numseconds" && strtolower($firstRow[1]) != "num seconds") )
					header("Location: uploadCSV.php?error=10");
				else // The column headers are alright, continue
				{
					$table_create_query = "CREATE TABLE `" . $_SESSION['name'] . "`(MeasureNumber int, NumSeconds double";
					
					If the user included some tracks append the extra columns to the SQL call
					if ( count($firstRow) > 2 )
					{
						$includesTracks = true;						
						for ($i=2; $i<count($firstRow); $i++)
						{
							$table_create_query = $table_create_query . ", `$firstRow[$i]` text";
							// sleep(1);
							// header($i);
						}
							
					}
					
					$table_create_query = $table_create_query.")"; //Close the sql call
					//error_log("Creating Table", 3, $myFile);
					//$_SESSION['success'] = mysql_query("CREATE TABLE `" . $_SESSION['name'] . "`(MeasureNumber int, NumSeconds double)");
					// Let's create the table!
					include("connectToDB.php");
					$success = mysql_query($table_create_query);
					
					if ( !$success )
						header("Location: uploadCSV.php?error=11");  // If we couldn't create the table, tell them to try again
					else
					{
						$insert_row_template = "INSERT INTO `" . $_SESSION['name'] . "` (`MeasureNumber`, `NumSeconds`) VALUES ("; // The string we'll append to in order to add each row
						//Read until the end of the file
						while (!feof($file))
						{
							$currentLine = explode(',',trim(fgets($file)));
							if ($currentLine[0] != '' && $currentLine[1] != '')
							{
								$insert_current_row = $insert_row_template . $currentLine[0] . ", " . $currentLine[1];
								
								If the file includes more than just measure number and measure time, append the annotations to the query
								if ( $includesTracks )
								{						
									for ($i=2; $i<count($currentLine); $i++) {
										$insert_current_row = $insert_current_row . ", " . ( $currentLine[$i] != '' ? ("'".mysql_real_escape_string($currentLine[$i])."'" ): 'NULL' );
									}
								}
								echo "<script type='text/javascript'>alert('$insert_current_row')</script>";
								$insert_current_row = $insert_current_row . ")";
								$success = mysql_query($insert_current_row);
								
								// error_log("You messed up!", 3, $myFile);
								//$success = !$success;

								// header($insert_current_row);
								// sleep(1);
								if (!$success)
									header("Location: uploadCSV.php?error=12&failMeasure=".$currentLine[0]);
							}
						}
						
						header("Location: edit.php");
					}
				}
			}
        }
     } 
	 else 
	 	echo "No file selected <br />";
}
?>