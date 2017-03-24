<?php
	
	//EDIT SERVER INFO
	$server="localhost"; $username="inotes"; $password="inotes"; $database_name="content";
	$formSubmit = !empty($_POST['formSubmit']) ? $_POST['formSubmit'] : null;
	$varTitle = "";
	$varBody = "";
	$varButton = "";
	$varRemovePiece = "";
	$varAddPiece = "";

	if($formSubmit == "Measure Update") {
		$varCurrentPiece = !empty($_POST['formCurrentPiece']) ? $_POST['formCurrentPiece'] : null;
		$varCurrentMeasure = !empty($_POST['formCurrentMeasure']) ? $_POST['formCurrentMeasure'] : null;

		$db = mysqli_connect($server,$username,$password);
		if(!$db) die("Error connecting to MySQL database.");
		mysqli_select_db($db, $database_name);

		$sql = "UPDATE currentMeasure SET currentMeasure=" . PrepSQL($db, $varCurrentMeasure) . ", currentPiece=" . PrepSQL($db, $varCurrentPiece);
		mysqli_query($db, $sql);
	}


	if($formSubmit == "Push Message"){
   		$varTitle = $_POST['formMessageTitle'];
		$varBody = $_POST['formMessageBody'];
		$varType = $_POST['formMessageType'];
		$varButton = $_POST['formButtonText'];
		$varPushMessage = "PUSH|" . $varTitle . "|" . $varBody . "|" . $varType . "|" . $varButton . "|";

		$db = mysqli_connect($server,$username,$password);
		if(!$db) die("Error connecting to MySQL database.");
		mysqli_select_db($db, $database_name);

		$sql = "UPDATE currentMeasure SET currentNotification=" . PrepSQL($db, $varPushMessage);
		mysqli_query($db, $sql);
	}

	if($formSubmit == "Clear"){
    	//debug_to_console("SubmitMessage");
   		$varTitle = "";
		$varBody = "";
		$varType = "BUTTON";
		$varButton = "";
		$varPushMessage = "PUSH|" . $varTitle . "|" . $varBody . "|" . $varType . "|" . $varButton . "|";
		
		$db = mysqli_connect($server,$username,$password);
		if(!$db) die("Error connecting to MySQL database.");
		mysqli_select_db($db, $database_name);

		$sql = "UPDATE currentMeasure SET currentNotification=" . PrepSQL($db, $varPushMessage);
		mysqli_query($db, $sql);
	}

	if($formSubmit == "  Up  "){
		$varCurrentPiece = !empty($_POST['formCurrentPiece']) ? $_POST['formCurrentPiece'] : null;
		$varCurrentMeasure = !empty($_POST['formCurrentMeasure']) ? $_POST['formCurrentMeasure'] : null;

		$varCurrentMeasure = $varCurrentMeasure + 1;

		$db = mysqli_connect($server,$username,$password);
		if(!$db) die("Error connecting to MySQL database.");
		mysqli_select_db($db, $database_name);

		$sql = "UPDATE currentMeasure SET currentMeasure=" . PrepSQL($db, $varCurrentMeasure);
		mysqli_query($db, $sql);
	}

	if($formSubmit == "Down"){
		$varCurrentPiece = !empty($_POST['formCurrentPiece']) ? $_POST['formCurrentPiece'] : null;
		$varCurrentMeasure = !empty($_POST['formCurrentMeasure']) ? $_POST['formCurrentMeasure'] : null;

		if($varCurrentMeasure>2){
			$varCurrentMeasure = $varCurrentMeasure - 1;
		}

		$db = mysqli_connect($server,$username,$password);
		if(!$db) die("Error connecting to MySQL database.");
		mysqli_select_db($db, $database_name);

		$sql = "UPDATE currentMeasure SET currentMeasure=" . PrepSQL($db, $varCurrentMeasure);
		mysqli_query($db, $sql);
	}

	if($formSubmit == "Add"){
		$varCurrentPiece = !empty($_POST['formCurrentPiece']) ? $_POST['formCurrentPiece'] : null;
		$varCurrentMeasure = !empty($_POST['formCurrentMeasure']) ? $_POST['formCurrentMeasure'] : null;
		$varAddPiece = !empty($_POST['formAddPiece']) ? $_POST['formAddPiece'] : null;
		$varRemovePiece = !empty($_POST['formRemovePiece']) ? $_POST['formRemovePiece'] : null;

		if($varCurrentMeasure>2){
			$varCurrentMeasure = $varCurrentMeasure - 1;
		}

		$db = mysqli_connect($server,$username,$password);
		if(!$db) die("Error connecting to MySQL database.");
		mysqli_select_db($db, $database_name);

		$sql = 'INSERT INTO CurrentConcert (`PieceName`, `Order`) VALUES (' . PrepSQL($db, $varAddPiece) . ', 0)';
		mysqli_query($db, $sql);

		$sql = "ALTER TABLE CurrentConcert ORDER BY PieceName";
		mysqli_query($db, $sql);
	}

	if($formSubmit == "Remove"){
		$varCurrentPiece = !empty($_POST['formCurrentPiece']) ? $_POST['formCurrentPiece'] : null;
		$varCurrentMeasure = !empty($_POST['formCurrentMeasure']) ? $_POST['formCurrentMeasure'] : null;
		$varAddPiece = !empty($_POST['formAddPiece']) ? $_POST['formAddPiece'] : null;
		$varRemovePiece = !empty($_POST['formRemovePiece']) ? $_POST['formRemovePiece'] : null;

		$db = mysqli_connect($server,$username,$password);
		if(!$db) die("Error connecting to MySQL database.");
		mysqli_select_db($db, $database_name);

		$sql = "DELETE from CurrentConcert WHERE PieceName=" . PrepSQL($db, $varRemovePiece);

		mysqli_query($db, $sql);
		$sql = "ALTER TABLE  CurrentConcert ORDER BY  PieceName";
		mysqli_query($db, $sql);
	}

            
    // function: PrepSQL()
    // use stripslashes and mysql_real_escape_string PHP functions
    // to sanitize a string for use in an SQL query
    //
    // also puts single quotes around the string
    //
    function PrepSQL($db, $value){
        // Stripslashes
        if(get_magic_quotes_gpc()) {
            $value = stripslashes($value);
        }

        // Quote
        $value = "'" . mysqli_real_escape_string($db, $value) . "'";

        return($value);
    }
    
    function debug_to_console( $data ) {
    	if ( is_array( $data ) )
        	$output = "<script>console.log( 'Debug Objects: " . implode( ',', $data) . "' );</script>";
    	else
        	$output = "<script>console.log( 'Debug Objects: " . $data . "' );</script>";

    	echo $output;
	}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
<link rel="stylesheet" type="text/css" href="bootstrap_css/bootstrap.css" />
<head>
	<title>LiveNote Web Update: Met-Lab Dev Test</title>
<!-- define some style elements-->
<style>
label,a 
{
	font-family : Arial, Helvetica, sans-serif;
	font-size : 12px; 
}

</style>	
</head>


<body>
	<H1>LiveNote, Met-Lab Dev Test: Live Stream Update Tool</H1>

       <?php
       		//EDIT SERVER INFO
       		$server="localhost"; $username="inotes"; $password="inotes"; $database_name="content";
		    if(!empty($errorMessage)) {
			    echo("<p>There was an error with your form:</p>\n");
			    echo("<ul>" . $errorMessage . "</ul>\n");
            }
            $db = mysqli_connect($server,$username,$password);
			if(!$db) die("Error connecting to MySQL database.");
			mysqli_select_db($db, $database_name);
		    $query = "SELECT currentMeasure FROM currentMeasure";
			$res = mysqli_query($db, $query);
			while ($row = mysqli_fetch_assoc($res)){
				$varCurrentMeasure = $row['currentMeasure'];
			}
			

        ?>
        <div id="position" style="height:500px;width:300px;float:left;">
		<form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
			<H3>Live Position</H3>
			<p>
				<!--<label for='formCurrentPiece'>CurrentPiece</label><input type="text" name="formCurrentPiece" maxlength="100" value="<?php // $varCurrentPiece; ?>" /> -->
				<label for='formCurrentMeasure'>CurrentMeasure</label></br>
				<input type="text" name="formCurrentMeasure" maxlength="100" value="<?=$varCurrentMeasure;?>" />
				<!-- <input type="submit" name="formSubmit" id="up" value="&#x25B2" />
				<input type="submit" name="formSubmit" id="down" value="&#x25BC" /> -->
				<input type="submit" name="formSubmit" accesskey=" " id="up" value="  Up  " />
				<input type="submit" name="formSubmit" accesskey="d" id="down" value="Down" /></br></br>				<label for='formCurrentMeasure'>Auto Increment</label></br>
				MAC: [ctrl] + [option] + [space] </br>
				WIN: [alt] + [space] or [shift] + [alt] + [space]

			</p>
			<p>
				<label for='formCurrentPiece'>CurrentPiece</label></br>
				<?php
					$db = mysqli_connect($server,$username,$password);
					if(!$db) die("Error connecting to MySQL database.");
					mysqli_select_db($db, $database_name);
		    		$query = "SELECT PieceName FROM CurrentConcert";
					$res = mysqli_query($db, $query);
					echo '<select name="formCurrentPiece">';
					//while (($row = mysql_fetch_row($res)) != null)
					while ($row = mysqli_fetch_assoc($res)){
					    echo '<option value="' . $row['PieceName'] . '"';
					    if ($varCurrentPiece == $row['PieceName'] )
					        echo(' selected="selected"');
					    echo '>"'.$row['PieceName'].'"</option>';
					}
					echo '</select>';
        		?>
			</p>
			<input type="submit" name="formSubmit" value="Measure Update" />
			</br></br>
			<H3>Current Concert</H3>
			<table border="1">
			<?php
				//EDIT SERVER INFO
				$server="localhost"; $username="inotes"; $password="inotes"; $datebase_name="content";
				$db = mysqli_connect($server,$username,$password);
				if(!$db) die("Error connecting to MySQL database.");
				mysqli_select_db($db, $database_name);
		    	$query = "SELECT PieceName FROM CurrentConcert";
				$res = mysqli_query($db, $query);
                $i = 0;
                $data = array();
                while($row = mysqli_fetch_assoc($res)){
                    $data[] = $row;
                }
                
                if ($data) {
                    $colNames = array_keys(reset($data));
                } else {
        			$colNames = [];
                }

			?>
			<tr>
				<th>Piece Name</th>
			</tr>

			<?php
			    //print the rows
			    foreach($data as $row){
			        echo "<tr>";
			        foreach($colNames as $colName)
			        {
			           echo "<td>".$row[$colName]."</td>";
			        }
			        echo "</tr>";
			    }
			 ?>
			 
			</table>

			<p></br></p>
			<label for='formAddPiece'>Add to Live List</label></br>
				<?php
					$server="localhost"; $username="inotes"; $password="inotes"; $datebase_name="content";
					$db = mysqli_connect($server,$username,$password);
					if(!$db) die("Error connecting to MySQL database.");
					mysqli_select_db($db, $database_name);
					//SELECT * FROM information_schema.tables
		    		$query = "SHOW TABLES";
					$res = mysqli_query($db, $query);
					echo '<select name="formAddPiece">';
					
					while ($row = mysqli_fetch_assoc($res)){
    					var_dump($row);
					    echo '<option value="' . $row['Tables_in_content'] .'"';
					    if ($varRemovePiece == $row['Tables_in_content'] )
					        echo(' selected="selected"');
					    echo '>"'.$row['Tables_in_content'].'"</option>';
					}
					echo "</select>";
        		?></br>
        		<input type="submit" name="formSubmit" id="add" value="Add" />
        		
        		<p></br><p/>
        		<label for='formRemovePiece'>Remove from Live List</label></br>
				<?php
					$db = mysqli_connect($server,$username,$password);
					if(!$db) die("Error connecting to MySQL database.");
					mysqli_select_db($db, $database_name);
		    		$query = "SELECT PieceName FROM CurrentConcert";
					$res = mysqli_query($db, $query);
					echo "<select name=\"formRemovePiece\">";
					while ($row = mysqli_fetch_assoc($res)){
					    echo "<option value=\"" . $row['PieceName'] ."\"";
					    if ($varAddPiece == $row['PieceName'] )
					        echo(" selected=\"selected\"");
					    echo ">\"".$row['PieceName']."\"</option>";
					}
					echo "</select>";
        		?></br>
        		<input type="submit" name="formSubmit" id="remove" value="Remove" />


		</form>


	</div>

	<div id="message" style="height:300px;width:300px;float:right;">
		<form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
			<H3>Push Message</H3>
			<p>
				<label for='formMessageTitle'>Title</label><br/>
				<input type="text" name="formMessageTitle" maxlength="50" value="<?=$varTitle;?>" />
			</p>
			<p>
				<label for='formMessageBody'>Body</label><br/>
				<input type="text" name="formMessageBody" maxlength="100" value="<?=$varBody;?>" />
			</p>
			<p>
				<label for='formMessageType'>Message Type</label><br/>
				<select name="formMessageType">
					<option value="BUTTON"<? if($varType=="BUTTON") echo(" selected=\"selected\"");?>>BUTTON</option>
					<option value="AUTO"<? if($varType=="AUTO") echo(" selected=\"selected\"");?>>AUTO</option>
				</select>
			</p>
			<p>
				<label for='formButtonText'>Button Text</label><br/>
				<input type="text" name="formButtonText" maxlength="100" value="<?=$varButton;?>" />
			</p>

			<input type="submit" name="formSubmit" value="Push Message" />
			<input type="submit" name="formSubmit" value="Clear" />
			
			</br>
			</br>
			<p>
				<label for='formMessageTitle'>Current Message</label><br/>
				<table border="1">
				
				<?php
					//EDIT SERVER INFO
					$server="localhost"; $username="inotes"; $password="inotes"; $datebase_name="content";
					$db = mysqli_connect($server,$username,$password);
					if(!$db) die("Error connecting to MySQL database.");
					mysqli_select_db($db, $database_name);
			    	$query = "SELECT currentNotification FROM currentMeasure";
					$res = mysqli_query($db, $query);
				  	$i = 0;
				  	while ($row = mysqli_fetch_assoc($res)){
						//echo $row['currentNotification'];
						$notifyParts = explode("|", $row['currentNotification']);
					}
				?>

				<?php
                    if (!$notifyParts) {
                        echo "<tr>";
                        echo "<th><div style=\"text-align:center\">No available messages</div></th>";
                        echo "</tr>";
                    } else {
                        $cnt = 1;
                        //print the rows
                        foreach($notifyParts as $data){
                            if($cnt==2){
                                echo "<tr>";
                                echo "<th><div style=\"text-align:center\">".$data."</div></th>";
                                echo "</tr>";
                            }
                            elseif($cnt>2 && $cnt!=4){
                                echo "<tr>";
                                echo "<td><div style=\"text-align:center\">".$data."</div></td>";
                                echo "</tr>";
                            }
                            $cnt=$cnt+1;
                        }
                    }

				 ?>
				</div>
				</table>
			</p>
			<p></br></br></br></br>Created by: Matthew Prockup</p>
  			Contact information: <a href="mailto:mprockup@gmail.com">
  			mprockup@gmail.com</a></p>
		</form>
</div>
</body>
</html>
