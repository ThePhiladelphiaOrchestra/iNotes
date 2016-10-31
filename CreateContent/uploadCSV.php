<?php 
include("password_protectinotes.php"); 

//Populate PHP upload error messages so we can give useful feedback
$upload_errors = array( 
    UPLOAD_ERR_OK          => "No errors.", 
    UPLOAD_ERR_INI_SIZE    => "Larger than upload_max_filesize.", 
    UPLOAD_ERR_FORM_SIZE   => "Larger than form MAX_FILE_SIZE.", 
    UPLOAD_ERR_PARTIAL     => "Partial upload.", 
    UPLOAD_ERR_NO_FILE     => "No file.", 
    UPLOAD_ERR_NO_TMP_DIR  => "No temporary directory.", 
    UPLOAD_ERR_CANT_WRITE  => "Can't write to disk.", 
    UPLOAD_ERR_EXTENSION   => "File upload stopped by extension.", 
    UPLOAD_ERR_EMPTY       => "File is empty.", // add this to avoid an offset 
	9	   				   => "Incorrect filetype.  Please save file as .csv",
	10					   => "Incorrect column headers.  Please use \"MeasureNumber\" and \"NumSeconds\"",
	11					   => "Couldn't create table in database.  Please try again.",
	12					   => "Failure on insert with measure #".$_REQUEST['failMeasure']
  ); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="lib/style.css" />
<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'/>

<title>iNotes Authoring | Create New</title>
<script type="text/javascript">
function ClearTextField(field)
{
	field.value = "";
}
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
        <h3>Create New</h3><br/>
        <?php
		if(isset($_REQUEST['error']))
			echo ('<font style="color:red; font-weight:bold;">ERROR: '.$upload_errors[$_REQUEST['error']].'</font><br/>');
		?>
        Please generate an excel spreadsheet with <b>the first row consisting of track names. <br/>  The first two columns must be &quot;MeasureNumber&quot; and &quot;NumSeconds&quot;, respectively</b>.  All columns after that will be used as track names.  <br/>The following image shows what the file might look like in Microsoft Excel:<br/>
        <img src="lib/imgs/instruction.png" /><br/>
        Please select a file to upload
        	<form method="post" action="fillNew.php" enctype="multipart/form-data">
            	<label for="file">Filename:</label>
                <input type="file" name="file" id="file"><br>
                <input type="submit" name="submit" class="button_small" value="Upload" />
        	</form>
         
            
        </div>
        <!-- END WHITE CONTENT BOX -->
        <a href="index.php?logout=1">Logout</a>
        <!-- END CONTENT -->
    </div>
</div>

</body>
</html>