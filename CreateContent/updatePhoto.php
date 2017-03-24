<?php ini_set("memory_limit", "200000000"); // for large images so that we do not get "Allowed memory exhausted"?>
<?php
// upload the file
if ((isset($_POST["submitted_form"])) && ($_POST["submitted_form"] == "image_upload_form")) {
	
	// file needs to be jpg,gif,x-png and 4 MB max
	if (($_FILES["image_upload_box"]["type"] == "image/jpeg" || $_FILES["image_upload_box"]["type"] == "image/pjpeg" || $_FILES["image_upload_box"]["type"] == "image/gif" || $_FILES["image_upload_box"]["type"] == "image/x-png" || $_FILES["image_upload_box"]["type"] == "image/png"))
	{
		if ($_FILES["image_upload_box"]["size"] < 4000000) {
			// Maximum Sizes for the thumbnails and photos
			$max_photo_width = 800;
			$max_photo_height = 800;
	
			
			// if uploaded image was JPG/JPEG
			if($_FILES["image_upload_box"]["type"] == "image/jpeg" || $_FILES["image_upload_box"]["type"] == "image/pjpeg"){	
				$image_source = imagecreatefromjpeg($_FILES["image_upload_box"]["tmp_name"]);
			}		
			// if uploaded image was GIF
			if($_FILES["image_upload_box"]["type"] == "image/gif"){	
				$image_source = imagecreatefromgif($_FILES["image_upload_box"]["tmp_name"]);
			}				
			// if uploaded image was PNG
			if($_FILES["image_upload_box"]["type"] == "image/x-png" || $_FILES["image_upload_box"]["type"] == "image/png"){
				$image_source = imagecreatefrompng($_FILES["image_upload_box"]["tmp_name"]);
				// turning off alpha blending (to ensure alpha channel information 
	        // is preserved, rather than removed (blending with the rest of the 
	        // image in the form of black))
	       		imagealphablending($image_source, false);

	        // turning on alpha channel information saving (to ensure the full range 
	        // of transparency is preserved)
        		imagesavealpha($image_source, true);
			}
			
	
			$remote_photo_file = "../images/".$_FILES["image_upload_box"]["name"];
			debug_to_console($remote_photo_file);
			$workedUpload = FALSE;
			if($_FILES["image_upload_box"]["type"] == "image/png")
			{
				debug_to_console("Photo is PNG");
				$workedUpload = imagepng($image_source,$remote_photo_file);
			}
			else
			{
				debug_to_console("Defaulting to JPG");
				$workedUpload = imagejpeg($image_source,$remote_photo_file,100);
			}

			if($workedUpload)
			{
				debug_to_console("...Uploaded");
			}
			else
			{
				debug_to_console("...Upload Error");
			}

			chmod($remote_photo_file,0644);
			debug_to_console($remote_photo_file);
			// If photo isn't a gif, resize it
			if($_FILES["image_upload_box"]["type"] != "image/gif" && $_FILES["image_upload_box"]["type"] != "image/png") {
				debug_to_console("Photo is not gif");
				list($image_width, $image_height) = getimagesize($remote_photo_file);
			
				if($image_width>$max_photo_width || $image_height >$max_photo_height){
					$proportions = $image_width/$image_height;
					debug_to_console("Photo is bigger than needed");
					if($image_width>$image_height){
						debug_to_console("... Image is wider than tall");
						$new_width = $max_photo_width;
						$new_height = round($max_photo_width/$proportions);
					}		
					else{
						debug_to_console("... image is taller than wide");
						$new_height = $max_photo_height;
						$new_width = round($max_photo_height*$proportions);
					}		
					
					
					$new_image = imagecreatetruecolor($new_width , $new_height);
					$image_source = imagecreatefromjpeg($remote_photo_file);
					
					imagecopyresampled($new_image, $image_source, 0, 0, 0, 0, $new_width, $new_height, $image_width, $image_height);
					imagejpeg($new_image,$remote_photo_file,100);
					imagedestroy($new_image);
				}
			}
			imagedestroy($image_source);
			
			include("globals.php");
			//Select the iNotes database
			$success = mysqli_select_db($link, $dbname);
			
			
			session_start();
			$_SESSION["photo"] = $_FILES["image_upload_box"]["name"];
			
			$newAnnotation = $_SESSION["annotation"]."$".$_SESSION["photo"].",".$_SESSION["caption"];	
					
			//$newAnnotation = $contentMatches[0]."$".$_SESSION["photo"].", ";
			
			//Sanatize the user input for the SQL query
			$newAnnotation = mysqli_escape_string($newAnnotation);
			
			$response =  mysql_query("UPDATE `" . $_SESSION["name"] . "` SET `" . $_SESSION["track"] . "` = '" . $newAnnotation . "' WHERE MeasureNumber = '" . $_SESSION["measure"] . "'");
			
			if ($response)
			{	
				header("Location: edit.php");
				exit;
			}
			else
			{
					header("Location: edit.php?upload_message=Database update error&upload_message_type=error");
			exit;
			}
		}
		else{
		header("Location: edit.php?upload_message=Please make sure the file is smaller than 4Mb&upload_message_type=error");
		exit;
	}
	}
	else{
		header("Location: edit.php?upload_message=Please make sure the file is in JPG, PNG, or GIF format&upload_message_type=error");
		exit;
	}
}
function debug_to_console( $data ) {

    if ( is_array( $data ) )
        $output = "<script>console.log( 'Debug Objects: " . implode( ',', $data) . "' );</script>";
    else
        $output = "<script>console.log( 'Debug Objects: " . $data . "' );</script>";

    echo $output;
}
?>


