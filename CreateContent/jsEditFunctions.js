var currentTrack="";
var currentMeasure="";
var currentAnnotation="";
var currentPhoto="";
var currentCaption="";
var currentCaptionLabel="";
var trackSelected=false;
var hasPhoto=false;

// Clear the measure number field if the value is "#"
function ClearTextField(field)
{
	if (field.value == "#")
		field.value = "";
}

// Clear the measure number field if the value is "#"
function clearBlank(field)
{
	if (field.value == "Blank...")
		field.value = "";
}

// Update the session with the current track and display the info for that track
function showTrack(track)
{
	currentTrack=track;
	trackSelected=true;
	try //Catch the errors that happen on page load -- do nothing with them
	{
		if (document.getElementById("measure").value == "#" || document.getElementById("measure".value === '') || document.getElementById("measure").value === null)
		{
			return;
		}
	}
	catch (e) {
		return;
	}
	updateSession('up-to-date');
	displayInfo();
}

// Update the session with the current measure and display the info for that measure
function showMeasure(measure)
{
	currentMeasure=measure;
	if (measure === "" || !trackSelected) {
		// document.getElementById("measure_content").value="Please enter a measure number and select a track...";
		alert("Please enter a measure number and select a track...");
		return;
	}
	updateSession('up-to-date');
	displayInfo();
}

// Take the current information from the elements in the page and update the session with them
function updateSession(status) //-- To incorperate updating status ball
{
	// Set the javascript variables to be passed.
	currentAnnotation = document.getElementById("annotation").value;
	//alert("3:"+currentAnnotation);
	//currentAnnotation = $('<div/>').text(currentAnnotation).html();
	//alert("4:"+currentAnnotation);
	if ( document.getElementById("photo_wrapper").style.display == "block" ) {
		currentPhoto = document.getElementById("photo").src;
		currentPhoto = currentPhoto.substring( currentPhoto.lastIndexOf("/") + 1 );
		currentCaption = document.getElementById("caption").value;
        currentCaptionLabel = "Image Caption:";
	} else {
		currentPhoto = "";
		currentCaption = "";
        currentCaptionLabel = "";
	}
	if ( status == "pending" ) {
		setStatus("pending");
	} else {
		setStatus("up-to-date");
    }

	// Send the data to update the session
	if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	} else {// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			response = xmlhttp.responseText;
			//alert(response);
			document.getElementById("debug").value = response;
		}
	};
	xmlhttp.open("POST","updateSession.php",false);
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlhttp.send("track="+currentTrack+"&measure="+currentMeasure+"&annotation="+currentAnnotation+"&photo="+currentPhoto+"&caption="+currentCaption+"&captionLabel="+currentCaptionLabel);
}


// Grab the annotation for the current measure and call updatePage with it
function displayInfo()
{
	if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	} else {// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			response = xmlhttp.responseText;	
			//document.getElementById("annotation").value = response;
			//alert("1: "+response);
			response = $('<div/>').html(response).text();
			//alert("2: "+response);
			updatePage(response);
			
		}
	};
	xmlhttp.open("POST","displayInfo.php",false);
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlhttp.send();	
}

// Break down the raw annotation and update the page accordingly
function updatePage(rawAnnotation)
{
	photoIndex = rawAnnotation.indexOf("$");
	captionIndex = rawAnnotation.indexOf(",",photoIndex);
	
	if (photoIndex == -1) // No photo -- Hide photo and caption
	{
		document.getElementById("annotation").value = rawAnnotation;
		document.getElementById("annotation").style.width = "545px";
		document.getElementById("photo_wrapper").style.display = "none";
		/*document.getElementById("photo").style.display = "none";
		document.getElementById("caption").style.display = "none";
        document.getElementById("captionLabel").style.display = "none";*/
	}
	else // Has photo -- Show photo and caption, update their source and content, respectively
	{
		document.getElementById("annotation").value = rawAnnotation.substring(0, photoIndex);
		document.getElementById("annotation").style.width = "257px";
		/*document.getElementById("photo").style.display = "inline-block";*/
		document.getElementById("photo_wrapper").style.display = "block";
		document.getElementById("photo").src = ( "../web/images/" + rawAnnotation.substring(photoIndex+1, captionIndex) );
		/*document.getElementById("caption").style.display = "inline-block";*/
		document.getElementById("caption").value = rawAnnotation.substring(captionIndex+1);
        //document.getElementById("captionLabel").value = "Image Caption:";
        /*document.getElementById("captionLabel").style.display = "block";*/
	}
	updateSession('up-to-date'); // Update the session with the newly broken down info
	document.getElementById("annotation_error").style.display = "none";
}

// Take the current session information and update the database with it
function updateDB() 
{
	if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	} else { // code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			response = xmlhttp.responseText;
			document.getElementById("debug2").value = response;
			if (response == "Annotation Update Succeeded") {
				setStatus('up-to-date');
				if (document.getElementById("navRow"+currentMeasure) === null) {
					location.reload();
				} else if (document.getElementById(currentTrack+"."+currentMeasure).innerHTML == " - " ) {
					document.getElementById(currentTrack+"."+currentMeasure).innerHTML = " &#10003; ";
				}
			}
			if (response === "Annotation Update Failed") {
				setStaus('failed');
            }
		}
	};
	xmlhttp.open("POST","updateDB.php",false);
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlhttp.send();	
}

//For use with the annotation navigator and page callback.
//Jump to a specified track and measure
function jumpTo(track, measure)
{
	if (document.getElementById("navRow"+currentMeasure) !== null) {
		document.getElementById("navRow"+currentMeasure).style.background = "none";
    }
	currentTrack = track;
	selectTrack(track);
	document.getElementById("measure").value = measure;
	showMeasure(measure);
	if (document.getElementById("navRow"+measure) !== null) {
        document.getElementById("navRow"+measure).style.background = "rgb(255, 249, 203)";
    }
}

//For use with the annotation navigator and page callback.
//Jump to a specified track and measure
function jumpPrompt()
{
	if (currentTrack === "") {
		alert("Error: You must add a track before you may add a new annotation.");
	} else {
		var measure = window.prompt("Please enter Measure Number for the current track:\n\""+currentTrack+"\"",null);
		if (measure !== null && measure !== "" && !isNaN(measure)) {
			jumpTo(currentTrack,measure);
		}
	}
}

//Add the track in the "add track" textbox
function addTrack() {
	var newTrack = window.prompt("Please enter the name of the new track:",null);
	if (newTrack === "") {
		alert("Error: No track name entered. No track added."); 
		return;
	}

	if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	} else {// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange = function() {	
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			if(xmlhttp.responseText == "success")
			{
				//alert("Database Update Successful!");
				document.getElementById("add_track").value += ("<input type='radio' name='track' value='" + newTrack + "' onClick='showTrack(this.value)' />   " + newTrack + " | ");
				location.reload();
				showTrack(newTrack);
			}
			else
				alert(xmlhttp.responseText);
		}
	};
	xmlhttp.open("POST","addTrack.php",false);
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlhttp.send("newTrack="+newTrack);
}

//Delete a track
function deleteTrack(track)
{
	var conf = confirm("Are you sure you want to delete track \"" + track + "\"?  You will NOT be able to undo this action.");
	if ( conf === true ) {
		if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
		} else { // code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				response = xmlhttp.responseText;
				document.getElementById("debug2").value = response;
				if (response == "Annotation Update Succeeded")
				{
					setStatus('up-to-date');
					location.reload();
				}
				if (respose == "Annotation Update Failed")
					setStaus('failed');
			}
		};
		xmlhttp.open("POST","deleteTrack.php",false);
		xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		xmlhttp.send("track="+track);
	}
}

function deletePiece(name) {
	var confirmation = window.prompt("WARNING: Once this piece is deleted, it will NOT be able to be recovered.\nAre you SURE you want to do this?\nTo confirm deletion, please enter \"yes\" into the prompt:",null);
	if (confirmation == "yes")
		window.location =  "deletePiece.php";
}
	

function photoNotFound(element) {
	var lostImage = element.src;
	lostImage = lostImage.substring(lostImage.lastIndexOf("/")+1);
	document.getElementById("annotation_error").style.display = "block";
	document.getElementById("annotation_error").innerHTML = ("Error: Image '" + lostImage + "' not found.  Please re-upload.");
	element.src = 'lib/imgs/Image_not_found.jpg';
}

//Delete the photo and caption from the current annotation
function deletePhoto()
{
	document.getElementById("photo_wrapper").style.display = "none";
	document.getElementById("annotation").value = currentAnnotation;
	document.getElementById("annotation").style.width = "545px";
	updateSession("pending");
	//updateDB();
	//displayInfo();
}

//Clear all info out of the annotation
function clearAnnotation()
{
	var conf = confirm("Are you sure you want to clear this annotation?  You will NOT be able to undo this action.");
	if ( conf === true )
	{
		document.getElementById("photo_wrapper").style.display = "none";
		document.getElementById("annotation").value = "";
		updateSession();
		updateDB();
		location.reload();
	}
}

//Select the track that was naviagted to (generally by the annotation navigator)
function selectTrack(track)
{
		document.getElementById(track).checked = "checked";
}

//Change the status bubble on the screen.
function setStatus(status)
{
	if (status == "pending")
	{
		document.getElementById("status").innerHTML = "Pending";
		document.getElementById("statusball").src = "lib/imgs/statusball_yellow.png";
	}
	else if ( status == "failed" )
	{
		document.getElementById("status").innerHTML = "Update Failed";
		document.getElementById("statusball").src = "lib/imgs/statusball_red.png";
	}
	else
	{
		document.getElementById("status").innerHTML = "Up-to-date";
		document.getElementById("statusball").src = "lib/imgs/statusball_green.png";
	}
}

//Scale to the next font for the annotation
function changeFont()
{
	var currentSize = document.getElementById("annotation").style.fontSize;
	var fontSizes = ["20px","22px","24px","26px","28px"];
	//alert(currentSize);
	if (currentSize == "28px") 
		document.getElementById("annotation").style.fontSize = "20px";
	else if (fontSizes.indexOf(currentSize) != -1)
		document.getElementById("annotation").style.fontSize = fontSizes[fontSizes.indexOf(currentSize)+1];
	else
		document.getElementById("annotation").style.fontSize = "28px";	
}


//Show/hide the current session information and AJAX/SQL responses.
function setDebug(mode)
{
	if (mode=="on")
	{
		document.getElementById("debug").style.display = "inline-block";
		document.getElementById("debug2").style.display = "inline-block";
	}
	else
	{
		document.getElementById("debug").style.display = "none";
		document.getElementById("debug2").style.display = "none";
	}
}

//If server times out, log the error?
function log(msg) {
    setTimeout(function() {
        throw new Error(msg);
    }, 0);
}

