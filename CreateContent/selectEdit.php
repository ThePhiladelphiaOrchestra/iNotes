<?php 
include("password_protectinotes.php");
include("globals.php");
include("systemPHPFunctions.php");
//MODIFY LINE 39 TO CHANGE TO APPROPRIATE DATABASES

session_start();
$_SESSION = array();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="lib/style.css" />
<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'/>

<title>iNotes Authoring | Select Piece</title>
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
        <?php
		if(isset($_REQUEST['error']) && $_REQUEST['error'] == 1050)
			echo ('<font style="color:red; font-weight:bold;">ERROR: Error creating table in live database.</font><br/>');
		else if(isset($_REQUEST['error']) && $_REQUEST['error'] == 1076)
			echo ('<font style="color:red; font-weight:bold;">ERROR: Error copying data to table in new database.</font><br/>');
		else if(isset($_REQUEST['success']) && $_REQUEST['success'] == "true")
			echo ('<font style="color:rgb(7,177,7); font-weight:bold;">Table committed successfully!!</font><br/>');
		?>
            <h3>Edit Exisiting</h3><br/>
            <b>Which piece would you like to edit?</b><br/>
            <table class="bordered" style="width:100%;">
            	<tr>
                	<td style="width:50%"><b>Piece Name</b></td><td><b>Edit Dev</b></td><td><b>Commit to Live</b></td>
                </tr>
                <!--<tr> 
     				<td class="piece_name">Test Table</td><td><a href="#" class="button_small">Edit</a></td><td><a class="button_small" href="#">Commit</a></td>
                </tr>-->
            
            
            <?php 
				// Get the names of the pieces and print them in a table with an "Add" button
				$tables = getNames();
				if( is_array($tables)  )
				{
					$lastChar = 'A';
					$_SESSION['tableNames'] = array();
					for($index = 0; $index < count($tables);$index++) {
						/*if ( substr($tables[$index],0,1) > $lastChar) {
							$lastChar = substr($tables[$index],0,1);
							echo($lastChar);
						}*/
					echo( '<tr> 
     				<td class="piece_name">'.$tables[$index].'</td><td><a href="setName.php?index='.$index.'" class="button_small">Edit</a></td><td><a class="button_small" href="commitToLive.php?index='.$index.'">Commit</a></td>
                </tr>');
						$_SESSION['tableNames'][$index] = $tables[$index];
					}
				}
				else
					echo ("Error: Information Schemas query invalid");
			
			/*
				//Select the information_schemas database
				$success = mysql_select_db($info_schemas_name);

				//Grab all the table names and exclude the tables we don't want
                $tables = mysql_query("
				SELECT DISTINCT table_name FROM COLUMNS
				WHERE TABLE_SCHEMA = '".$dbname."'
				AND column_name = 'MeasureNumber'
				ORDER BY table_name ASC
				");
				
				$index = 0;
				$_SESSION['tableNames'] = array();
                
				if( $tables && $success )
				{
					while($current = mysql_fetch_row($tables)) {
					//echo ("<a href='setName.php?index=" . $index . "'>" . $current[0] . "</a><hr/>\n");
					
					
					$_SESSION['tableNames'][$index] = $current[0];
					$index++;
					}
				}
				else
					echo ("Error: Information Schemas query invalid");
				
           		mysql_close($link);
				*/
			?> 
            </table>    
            
        </div>
        <!-- END WHITE CONTENT BOX -->
        <!-- END CONTENT -->
        <a href="index.php?logout=1">Logout</a>
    </div>
</div>

</body>
</html>