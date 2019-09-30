// JavaScript Document

//we show the box by setting the visibility of the element and incrementing the height smoothly
function jsOnLoad(){

}


function validateForm(f){
	
	if(f.client_firstname.value.length == 0){
		alert("Please Enter the client's first name.");
		return false;
	}
	if(f.client_lastname.value.length == 0){
		alert("Please Enter the client's last name.");
		return false;
	}
	if(f.client_phone.value.length == 0){
		alert("Please Enter the client's phone number.");
		return false;
	}
	return true;
}


function showDiv(divId)
{ 	
	var objDiv = document.getElementById(divId);
	objDiv.style.display = (objDiv.style.display == 'block') ? 'none' : 'block';
}

function mouseRoll(itemID, showDiv){
	var objDiv = document.getElementById('contentOverview');
	window_pos('mouseOverDiv');

	switch(itemID){
		case "L1_fp":
			objDiv.innerHTML = "1111";
			break;
		case "L1_im":
			objDiv.innerHTML = "2222";
			break;
		case "L1_pa":
			objDiv.innerHTML = "3333";
			break;
		case "L1_su":
			objDiv.innerHTML = "4444";
			break;
		default:
			objDiv.innerHTML = "zzzzz";
			break;
	}
	
	if(showDiv)
		objDiv.style.visibility = "visible";
	else {
		objDiv.style.visibility = "hidden";
	}
}

function replace_str(string,text,by) {
	// Replaces text with by in string
	var strLength = string.length, txtLength = text.length;
	if ((strLength == 0) || (txtLength == 0)) {
		return string;
	}
	
	var i = string.indexOf(text);
	if ((!i) && (text != string.substring(0,txtLength))) {
		return string;
	}
	
	if (i == -1) {
		return string;
	}
	
	var newstr = string.substring(0,i) + by;

	if (i+txtLength < strLength){
		newstr += replace_str(string.substring(i+txtLength,strLength),text,by);
	}
	return newstr;
} 

/* screen DIV controls */

var photoDir ='.\/images\/';

function toggle(div_id) {
	var el = document.getElementById(div_id);
	if ( el.style.display == 'none' ) {	el.style.display = 'block';}
	else {el.style.display = 'none';}
}

function blanket_size(popUpDivVar) {
	if (typeof window.innerWidth != 'undefined') {
		viewportheight = window.innerHeight;
	} else {
		viewportheight = document.documentElement.clientHeight;
	}
	if ((viewportheight > document.body.parentNode.scrollHeight) && (viewportheight > document.body.parentNode.clientHeight)) {
		blanket_height = viewportheight;
	} else {
		if (document.body.parentNode.clientHeight > document.body.parentNode.scrollHeight) {
			blanket_height = document.body.parentNode.clientHeight;
		} else {
			blanket_height = document.body.parentNode.scrollHeight;
		}
	}
	var blanket = document.getElementById('blanket');
	blanket.style.height = blanket_height + 'px';
	var popUpDiv = document.getElementById(popUpDivVar);
	popUpDiv_height=blanket_height/2-150; //150 is half popup's height
	popUpDiv.style.top = popUpDiv_height + 'px';
}

function window_pos(popUpDivVar) {
	if (typeof window.innerWidth != 'undefined') {
		viewportwidth = window.innerHeight;
	} else {
		viewportwidth = document.documentElement.clientHeight;
	}
	if ((viewportwidth > document.body.parentNode.scrollWidth) && (viewportwidth > document.body.parentNode.clientWidth)) {
		window_width = viewportwidth;
	} else {
		if (document.body.parentNode.clientWidth > document.body.parentNode.scrollWidth) {
			window_width = document.body.parentNode.clientWidth;
		} else {
			window_width = document.body.parentNode.scrollWidth;
		}
	}
	var popUpDiv = document.getElementById(popUpDivVar);
	window_width=window_width/2-150; //150 is half popup's width
	popUpDiv.style.left = window_width + 'px';
}

function popup(windowName, divId, itemId) {
	//blanket_size(windowName);
	//window_pos(windowName);
	//toggle('blanket');
	divVisible(windowName, divId, itemId);		
}

function divVisible(objName, divId, itemId)
{
	objLyr = document.getElementById(objName);
	//objIFrame = document.getElementById('L1iFrame');
	//objIFrame.src = "";
	if(itemId != ""){
		getContent(objName, divId, itemId);
	}
	objLyr.style.visibility = (objLyr.style.visibility == 'visible') ? 'hidden' : 'visible';
	//objLyr.style.display = (objLyr.style.display == 'block') ? 'none' : 'block';
}

/* text detail */

function getContent(parentId, divId, itemId){
	
	objParent = document.getElementById(divId);
	objContent = document.getElementById(divId);
	objIFrame = document.getElementById('L1iFrame');
	//objDiv = document.getElementById(itemId);
	//objDiv = document.getElementById('L1div');
	//objCell = document.getElementById('L1cell');

	switch (itemId) {
		case "L1_fp":
			//objTD = document.getElementById('L1_fp');
			//objCell.innerHTML = objTD.innerHTML;
			//objDiv.style.display = (objDiv.style.display == 'block') ? 'none' : 'block';
			objIFrame.src = "";
			objIFrame.src = "pages/L1_fp.html";
			objParent.style.position = "relative";
			objParent.style.top = "250px";
			objParent.style.left = "325px";
			//objContent.style.position = "relative";
			//objContent.style.top = "250px";
			//objContent.style.left = "325px";
			objIFrame.width = "350px";
			objIFrame.height = "300px";
			break;
		case "L1_im":
			objIFrame.src = "";
			objIFrame.src = "pages/L1_im.html";
			objParent.style.position = "relative";
			objParent.style.top = "280px";
			objParent.style.left = "340px";
			objIFrame.width = "350px";
			objIFrame.height = "360px";
			break;
		case "L1_pa":
			objIFrame.src = "";
			objIFrame.src = "pages/L1_pa.html";
			objParent.style.position = "relative";
			objParent.style.top = "280px";
			objParent.style.left = "100px";
			objIFrame.width = "350px";
			objIFrame.height = "350px";
			break;
		case "L1_su":
			objIFrame.src = "";
			objIFrame.src = "pages/L1_su.html";
			objParent.style.position = "relative";
			objParent.style.top = "150px";
			objParent.style.left = "325px";
			objIFrame.width = "350px";
			objIFrame.height = "300px";
			break;
		default:
			objIFrame.src = "";
		break;
	}
	
	//return content;
}

function setVisible(anchorId,divId)
{ 	
	var anchorObj = document.getElementById(anchorId);
	var objDiv = document.getElementById(divId);

	objDiv.style.display = (objDiv.style.display == 'block') ? 'none' : 'block';
	anchorObj.innerHTML = (anchorObj.innerHTML == 'Show') ? 'Hide' : 'Show';
}

function addCart(product_id, product_name){
	var f = document.forms["cart"];
	f.mode.value = "add";
	f.product_id.value = product_id;
	//f.product_name.value = product_name;
	//alert(f.product_name.value);
	f.submit();
}

function removeCart(cart_id){
	var f = document.forms["cart"];
	f.mode.value = "remove";
	f.cart_id.value = cart_id;
	f.submit();
}

function submitCart(){
	var f = document.forms["cart"];
	f.submit();
}

function popup2(divId){
	var objDiv = document.getElementById(divId);
	objDiv.style.display = (objDiv.style.display == 'block') ? 'none' : 'block';
		
}

