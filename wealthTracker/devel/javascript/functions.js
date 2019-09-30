// JavaScript Document

//we show the box by setting the visibility of the element and incrementing the height smoothly
function jsOnLoad(){
	showDiv();
}

function setVisible(anchorId,divId)
{ 	
	var anchorObj = document.getElementById(anchorId);
	var objDiv = document.getElementById(divId);

	objDiv.style.display = (objDiv.style.display == 'block') ? 'none' : 'block';
	anchorObj.innerHTML = (anchorObj.innerHTML == 'Show') ? 'Hide' : 'Show';
}

function showDiv(){
	var urlquery=location.href.split("#");
	
	if(urlquery[1]){
		var urlparam = "" + urlquery[1];
		var urlNum = urlparam.substring(urlparam.length,urlparam.length-1)
		
		if(urlparam.length > 0){
			var objDiv = document.getElementById(urlparam);
			var anchorObj = document.getElementById("plAnchor"+urlNum);
			objDiv.style.display =  'block';		
			anchorObj.innerHTML = (anchorObj.innerHTML == 'Show') ? 'Hide' : 'Show';
		} 
	}
}

function checkForm(f){
	
	var valid = false;
	var eml = f.email.value;
	var atpos = eml.indexOf("@");
	var dotpos = eml.lastIndexOf(".");
	
	if(f.name.value.length == 0){
		alert("The name you have entered is not valid");
		return false;
	} else if (atpos<1 || dotpos<atpos+2 || dotpos+2>=eml.length){
		alert("The email address you have entered is not valid");
		return false;
	} else if (f.message.value.length == 0){
		alert("The message you have entered is not valid");
		return false;
	} else {
		return true;
	}
	
	return false;
	
}
