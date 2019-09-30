<?php
	//print_r($_POST);

	// record the client details
	$client_record = array();
	$client_record['*client_date'] = "NOW()";
	$client_record['client_firstname'] = form_param('client_firstname');
	$client_record['client_lastname'] = form_param('client_lastname');
	$client_record['client_entity'] = form_param('client_entity');
	$client_record['client_phone'] = form_param('client_phone');
	$client_record['client_contact_time_from'] = form_param('client_contact_time_from');
	$client_record['client_contact_time_to'] = form_param('client_contact_time_to');
	$client_record['client_contact_date'] = convert_date_to_sql(form_param('client_contact_date'));
	$client_record['client_email'] = form_param('client_email');
	$client_record['client_tax_stage'] = form_param('client_tax_stage');
	$client_record['client_comments'] = form_param('client_comments');
	$client_result = db_insert('client', $client_record, $clientId);

	$cart_record = array();
	$cart_record['client_id'] = $clientId;
	$cart_result = db_update("cart", $cart_record, " user_id=" . (int)$_SESSION['user_id']." AND status = '1' ");
 
	$sql = "SELECT *, DATE_FORMAT(order_date, '%W, %d %M %Y' ) as date_string
				FROM `cart`  c, `product` p
				WHERE c.product_id = p.product_id
				AND user_id = ".(int)$_SESSION['user_id']." 
				AND status = '1' ";
	//echo $sql;
	$result = db_query($sql);
	
	// To send HTML mail, the Content-type header must be set
	$headers = 'From: IFSWA Referral Tool <noreply@ifswa.com.au>' . "\r\n";
	
	$product_list = "";
	while($record = db_fetch_array($result)){
		$product_list .= $record['product_name']."\r\n";
		$date_string = $record['date_string'];
	}
	
	/***************   Referral email to Mick Steffan ************/
	//$to = "info@vaughanmasters.com.au";
	$to = "micksteffan@ifswa.com.au";
	$subject = "IFSWA Product Referral from ".$_SESSION['user_firstname']." " .$_SESSION['user_lastname'];
	
	$body  = "The following product request has been made from Adam Hunter:
	
Consultant Name: ".$_SESSION['user_firstname']." ".$_SESSION['user_lastname']."
Consultant Email: ".$_SESSION['user_email']." 

Client Details:
------------------	
Name: ".form_param('client_firstname')." ".form_param('client_lastname')."
Entity: ".form_param('client_entity')."
Phone: ".form_param('client_phone')."
Preferred Contact Time: ".form_param('client_contact_time_from')." ".(form_param('client_contact_time_to')!=""?"- ":"").form_param('client_contact_time_to')." ".form_param('client_contact_date')." 
MYOB Stage: ".form_param('client_tax_stage')."
Email: ".form_param('client_email')."
Comments: ".form_param('client_comments')."

Products Requested:
--------------------------
";
	$body .= $product_list;
	$body .= "--------------------------\r\n\r\n";
	mail($to,$subject,$body,$headers);

	/************** Client letter emailed to the accountant **************/
	// check the user is set to receive referral emails
	if($_SESSION['user_refer_email'] == "1"){	
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: IFSWA Referral Tool <noreply@ifswa.com.au>' . "\r\n";
		
		$to = $_SESSION['user_email'];
		$subject = "IFSWA Product Referral for ".db_escape_string(form_param('client_firstname'))." ".db_escape_string(form_param('client_lastname'));
		$body = "
<html>
<head>
</head>
<body style=\"	font-family:Arial, Helvetica, sans-serif; font-size:13px;\">
<p>Dear ".db_escape_string(form_param('client_firstname')).",</p>
<p>Thank you for completing your assessment of your financial planning needs with me on ".$date_string.".</p>
<p>Based on our discussions and our interactive financial planning software we concluded that you require urgent personal financial or product advice in the following financial planning areas:</p>
<p>&nbsp;</p>
";
		$body .= $product_list;
		$body .= "
<p>&nbsp;</p>
<p>As I am not specialised in financial planning I have asked our in-house Certified Financial Planner, Mick Steffan to contact you within the next few days to discuss your financial advice needs. </p>
<p>Meanwhile if you like to know more about financial advice and our financial planning arm please visit our website: <a href=\"".BASE_WEB."\">".BASE_WEB."</a></p>
<p>Finally, Mick will be working closely with me to ensure the most appropriate financial planning and taxation advice. </p>
<p>&nbsp;</p>
<p>Yours faithfully,</p>
<p>ADAM HUNTER PTY LTD<br />
".$_SESSION['user_firstname']." " .$_SESSION['user_lastname']."</p>
</body>
</html>";
		mail($to,$subject,$body,$headers);
	}

	// empty the cart
	//$sql = "DELETE FROM cart WHERE user_id = ".(int)$_SESSION['user_id'];
	//$result = db_query($sql);
	$cart_record = array();
	$cart_record['status'] = "0";
	$cart_result = db_update("cart", $cart_record, " user_id=" . (int)$_SESSION['user_id']." AND status = '1' ");
	
	header("Location: ".ROOT_WEB."portal/sent.php");
	
?>