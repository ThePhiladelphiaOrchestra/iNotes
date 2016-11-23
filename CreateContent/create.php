<?php 
include("password_protectinotes.php"); 
session_start();
$_SESSION = array();
session_destroy();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="lib/style.css" />
<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'/>

<title>iNotes Authoring | Create New</title>
<script type="text/javascript">
var cleared = 0;
function ClearTextField(field)
{
	if (!cleared) {
		field.value = "";
		cleared = 1;
	}
}

function GetMeasures()
{
	if (document.getElementById("name").value == "Enter name...")
		window.location.href = "create.php?error=1";
	else {
		measures = window.prompt("How many measures would you like to generate:",null);
		if (measures != null) {
			if (isNaN(measures))
				window.location.href = "create.php?error=3";
			else {
				document.getElementById("enter_name_form").action = "generate.php?measures=" + measures;
				document.getElementById("enter_name_form").submit();
			}
		}
		else
			window.location.href = "create.php?error=2";
	}
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
		if(isset($_REQUEST['error']) && $_REQUEST['error'] == 1)
			echo ('<font style="color:red; font-weight:bold;">ERROR: No name entered.  Please enter a name.</font><br/>');
		else if(isset($_REQUEST['error']) && $_REQUEST['error'] == 2)
			echo ('<font style="color:red; font-weight:bold;">ERROR: No measure number entered.  Please enter the number of measures when prompted.</font><br/>');
		else if(isset($_REQUEST['error']) && $_REQUEST['error'] == 3)
			echo ('<font style="color:red; font-weight:bold;">ERROR: Entered value is not a number.  Please enter a numeric value when prompted.</font><br/>');
		?>
        Please enter the name of the piece:
        	<form method="post" id="enter_name_form" >
            	<input type="text" class="text_field" name="name" onfocus="ClearTextField(this)" id="name" value="Enter name..." />
                <br/>
                <!--<input type="submit" name="submit" class="button_small" value="Upload" />-->
                <button type="submit" name="upload" class="button_small" formaction="checkExists.php"> <img src="lib/imgs/upload_arrow.png" alt="upload"/> Upload</button>
                <button type="button" name="create" onclick="GetMeasures()" class="button_small">Create</button>
                <!--<button type="submit" name="create" formaction="generate.php" class="button_small">Create</button>-->
                <a href="index.php" class="button_small">Cancel</a>
        	</form>
         
            
        </div>
        <!-- END WHITE CONTENT BOX -->
        <a href="index.php?logout=1">Logout</a>
        <!-- END CONTENT -->
    </div>
</div>

</body>
</html>