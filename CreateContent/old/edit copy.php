<?php 
// include("password_protectinotes.php"); 
?>
<?php 
	session_start();
	if(isset($_SESSION['name']))
	{
		$name = $_SESSION['name'];
	
		// Connect to the DB and grab the number of measures
		include('connectToDB.php');
		$measures = mysql_fetch_row(mysql_query("SELECT COUNT(MeasureNumber) FROM `" . $name . "`"));
		$measures = $measures[0];
		$_SESSION['measures'] = $measures;
		mysql_close($link);
	}
	else
	{
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
                //If the piece already exited, tell them, then take it away once they've
                //started editing it
                if($_SESSION['exists']) {
                    echo ('Piece "' .  $name . '" already existed.<br/>');
                    $_SESSION['exists'] = true;
                }
            ?>
            <table class="full">
            <!-- Editing info -->
            <tr><td>
            <div class="float_left">
            	<h3>Editing "<?php echo($name); ?>"</h3>
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
		<?php include('printTrackRadios.php'); ?>
            
            </form>
            <a id="add_track"></a>
            <button class="button_small" onclick="showAddTrack()">Add</button>         
        	</div>
            </td></tr>
            
        	<!-- Annotations and Preview -->
            <tr><td>
 		<div id="nav_float">
                	<?php include('annotationNav.php'); ?>
                </div>
                <div id="info_float">
                	<div style="text-align:right; vertical-align:top; width:575px; margin:0 auto;">
                        <div style="float:left;">
                        <b>Measure Number: </b>
                            <input type="text" id="measure" onfocus="ClearTextField(this)" onchange="showMeasure(this.value)" maxlength="3" value="#" />
                        </div>
                    	Status:&nbsp;<a id="status">Updated</a>&nbsp;<img id="statusball" src="lib/imgs/statusball_green.png" />
                    </div>  
                	<div id="current">
						<img id="photo" src="" style="display:none;" />
                    	<textarea id="annotation" onchange="updateSession('pending')">Please select a track and measure number...</textarea>
                    </div>
                    <textarea id="caption" onc onchange="updateSession('pending')" style="display:none;">No caption yet...</textarea>
                    <br/>
                    <button class="button_small" onclick="updateDB()">Commit</button>
                    <br/>
                    <br/><b>Upload new photo or replace old:</b>
                    <?php if(isset($_REQUEST['upload_message'])){?>
                    <div class="upload_message_<?php echo $_REQUEST['upload_message_type'];?>">
                    <?php echo htmlentities($_REQUEST['upload_message']);?>
                    </div><?php }?>
                    <form action="updatePhoto.php" method="post" enctype="multipart/form-data" name="image_upload_form" id="image_upload_form" style="margin-bottom:0px;">
                        <label>Image file, maximum 4MB. It must be in jpg, png, or gif format:</label><br />
                        <input name="image_upload_box" type="file" id="image_upload_box" size="40" onchange="javascript:this.form.submit();" />
                        <input name="submitted_form" type="hidden" id="submitted_form" value="image_upload_form" />
                    </form>
                </div>           
            	<textarea id="debug" cols="75" rows="15" style="display:none;"></textarea><br/>
                <textarea id="debug2" cols="75" rows="15" style="display:none;"></textarea>
            </td></tr>
            </table>
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