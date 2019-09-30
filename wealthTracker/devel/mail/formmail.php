<?php

	define('PROD',false);
	if(PROD){
		$devDir = "";
	} else {
		$devDir = "devel/";	
	}
	
	// email address to send form data to
	$emailTo = "info@ifswa.com.au";
	if($_POST['mode'] == "brochure"){
		$emailFrom = "IFSWA Brochure Request <webenquiry@ifswa.com.au>";
		$subject = "Website Brochure Request";
	} else {
		$emailFrom = "IFSWA Web Enquiry <webenquiry@ifswa.com.au>";
		$subject = "Website Enquiry";
	}	
	
	// form data content
  	$formName = $_POST['name'];
  	$formEmail = $_POST['email'];
  	$formPhone = $_POST['phone'];
  	$formMobile = $_POST['mobile'];
  	$formMessage = $_POST['message'];

	// construct the email
	$body = "Name: ".$formName."
Email: ".$formEmail."
Phone: ".$formPhone."
Mobile: ".$formMobile."

Message: ".$formMessage;
	$headers = "From: ".$emailFrom."\r\n";

	// check for email injection
	if ( preg_match( "/[\r\n]/", $name ) || preg_match( "/[\r\n]/", $email ) || preg_match( "/cc\:/i", $email )|| preg_match( "/bcc\:/i", $email ) ) {
  		header( "Location: http://www.ifswa.com.au/".$devDir."enquiryerror.html" );
	}
	
	// validate form
	if (strlen(trim($formEmail)) == 0) {
  		header( "Location: http://www.ifswa.com.au/".$devDir."enquiryerror.html" );
		exit;
	}

	// send the email				
  	mail( $emailTo, $subject, $body, $headers);

	// redirect to website
  	header( "Location: http://www.ifswa.com.au/".$devDir."enquirysent.html" );
  	//header( "Location: http://www.ifswa.com.au/sent.html" );
	exit;
	
?>