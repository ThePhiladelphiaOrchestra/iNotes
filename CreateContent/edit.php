<?php 
header('Content-type: text/html; charset=utf-8');
include("password_protectinotes.php"); 
include("systemPHPFunctions.php");
?>
<?php 
	session_start();
	if(isset($_SESSION['name']))
	{
		$name = $_SESSION['name'];
		include("globals.php");
		// Connect to the DB and grab the number of measures
		$db = mysqli_select_db($link, $dbname_live);
		$measures = mysqli_query($link, "SELECT COUNT(MeasureNumber) FROM `" . $name . "`");
		if ($measures) {
			$measures = mysqli_fetch_row($measures);
			$measures = $measures[0];
			$_SESSION['measures'] = $measures;
		}
		else {
			echo ("<center><h4 style=\"color:red; font-weight:bold; font-size: 18px; padding-top:20px;\">ERROR: Upload failed.  Please return to <a href='create.php'>Create</a> and try again.</h4></center>");
			unset($_SESSION['name']);
		}	
		mysqli_close($link);
	}
	else
	{
    	$name = '';
    	$measures = '';
		echo ("<center><h4 style=\"color:red; font-weight:bold; font-size: 18px; padding-top:20px;\">ERROR: Session doesn't exist.  Please return to <a href='selectEdit.php'>Selector</a> and choose a piece.</h4></center>");
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="lib/style.css" />
<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'/>

<title>iNotes Authoring | Edit</title>
<script type="text/javascript" src="jsEditFunctions.js"></script>
<script type="text/javascript" src="lib/jquery-1.9.1.min.js"></script>
<script type="text/javascript">
	//Set Debug "on" or "off" 
	var debug = "off";
</script>
</head>

<body>
<div class="wrapper">
    <div class="constrain">
        <!-- START CONTENT -->
        <h2>iNotes Authoring</h2>
        <a href="index.php">Home</a><br/>
        <!-- START WHITE CONTENT BOX -->
        <div class="content_box">
			<?php
                //If the piece already existed, tell them, then take it away once they've
                //started editing it
                if(!empty($_SESSION['exists'])) {
                    echo ('Piece "' .  $name . '" already existed.<br/>');
                    $_SESSION['exists'] = true;
					unset($_SESSION['exists']);
                }
            ?>
            <table class="full">
            <!-- Editing info -->
            <tr><td>
            <div class="float_left">
            	<form action="changeName.php" method="post"><h3>Editing <input type="text" id="edit_piece_name" name="edit_piece_name" value="<?php echo($name); ?>" /></h3></form>
            </div>
            <div class="float_right">
            	<?php echo ($measures . " measures  ");	?>
            </div>
            </td></tr>       
                        
            <!-- Current Tracks Selector -->
            <tr><td>
            <div class="float_left">
            <b>Tracks</b>
            <form>
		<?php 
			//include('printTrackRadios.php'); 
			printTrackRadios();	
		?>
            
            </form>
            <a id="add_track"></a>        
        	</div>
            <div class="float_left"></br>
            <img src="lib/imgs/add.png" onclick="addTrack()"/>
            <!--<button class="button_small" onclick="showAddTrack()">Add</button>-->
            </div>
            </td></tr>
            
        	<!-- Annotations and Preview -->
            <tr><td>
	 		<div id="nav_float">
				<div style="height: 600px; overflow:auto">
                	<?php include('annotationNav.php'); ?>
                </div>
                <button class="button_small" id="newButton" onclick="jumpPrompt()">New</button>
             </div>
             <div id="info_float">
                	<div style="text-align:right; vertical-align:top; width:575px; margin:0 auto;">
                        <div style="float:left;">
                        <b>Measure Number: </b>
                            <input type="text" id="measure" onfocus="ClearTextField(this)" onchange="showMeasure(this.value)" maxlength="4" value="#" />
                        </div>
                    	Status:&nbsp;<a id="status">Updated</a>&nbsp;<img id="statusball" src="lib/imgs/statusball_green.png" />
                    </div> 
                    <div id="annotation_error" style="display:none;"></div>
                	<div id="current">
                        <div id="photo_wrapper" style="display:none">
                        	<img src='lib/imgs/delete.png' onClick="deletePhoto()" style="float:right; position:absolute; padding:5px;" alt="delete"/>
                            <img id="photo" onError="photoNotFound(this)" alt="annotation photo"/> <br/>
                            <textarea id="caption" onchange="updateSession('pending')" onkeypress="setStatus('pending')" lang="en, de">Enter an image caption...</textarea>
                        </div>
                        <textarea id="annotation" onfocus="clearBlank(this)" onchange="updateSession('pending')" onkeypress="setStatus('pending')" style="font-size: 28px; width:545px;">Please select a track and measure number...</textarea>
                        <div id="change_font" onclick="changeFont()"></div>
                    </div>
                    <div style="text-align:left; vertical-align:top; width:600px; margin:0 auto;">
                        <div style="float:left;">
                            <!--<div id="captionLabel">Image Caption</div>
                            <textarea id="caption" onchange="updateSession('pending')">Enter an image caption...</textarea>-->
                    		<b>Upload new photo or replace old:</b>
							<?php if(isset($_REQUEST['upload_message'])){?>
                            <div class="message_<?php echo $_REQUEST['upload_message_type'];?>">
                            <?php echo htmlentities($_REQUEST['upload_message']);?>
                            </div><?php }?>
                            <form action="updatePhoto.php" method="post" enctype="multipart/form-data" name="image_upload_form" id="image_upload_form" style="margin-bottom:0px;">
                                <label>Image file, maximum 4MB. Must be in jpg, png, or gif.</label><br />
                                <input name="image_upload_box" type="file" id="image_upload_box" size="40" onchange="javascript:this.form.submit();" onclick="updateDB()" />
                                <input name="submitted_form" type="hidden" id="submitted_form" value="image_upload_form" onclick="updateDB()" />
                            </form>
                        </div>
                        <div style="float:right;">
                        <button class="button_small" onclick="updateDB()">Commit</button>
                        </div>
                        <div style="clear:both; text-align:center;"><br/><button class="button_small" onclick="clearAnnotation()">Clear Annotation</button></div>
                    </div>
                    
                    
                </div> 
            	<textarea id="debug" cols="75" rows="15" style="display:none;"></textarea><br/>
                <textarea id="debug2" cols="75" rows="15" style="display:none;"></textarea>
            </td></tr>
            </table>
            <div style="text-align:center;"><br/><button class="button_small_red" onclick="deletePiece()">Delete Piece</button></div>
			<?php //If the page was called back, return to the last known measure
					if( isset($_SESSION['track']) && isset($_SESSION['measure']) )
					{
						echo '<script type="text/javascript">
								jumpTo("'.$_SESSION['track'].'","'.$_SESSION['measure'].'");
						</script>';
					}
			?>
        </div>
        <!-- END WHITE CONTENT BOX -->
        <a href="index.php?logout=1">Logout</a>
        <!-- END CONTENT -->
    </div>
</div>

<script type="text/javascript">setDebug(debug);</script>

</body>
</html>