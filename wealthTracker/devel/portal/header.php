<?

// refuse access if not logged in
//print_r($_SESSION);
if(!isset($_SESSION['user_id'])){
	if(!(preg_match('/login\.php/', $_SERVER['SCRIPT_NAME']) == true)){
		// redirect to portal home
		header ("Location: ".ROOT_WEB."portal/login.php");
		exit;
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>IFSWA Referral Tool</title>
<link href="portal.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="javascript/functions.js"></script>
 
<style type="text/css">@import url(javascript/jscalendar/calendar-win2k-cold-1.css);</style>
  <script src="javascript/jscalendar/calendar.js" type="text/javascript" /></script>
  <script src="javascript/jscalendar/lang/calendar-en.js" type="text/javascript"/></script>
  <script src="javascript/jscalendar/calendar-setup.js" type="text/javascript"/></script>

</head>

<body onload="jsOnLoad();" bgcolor="#EEEEEE" background="../images/background.jpg">
       <center>
       <p>
 <? 	

	if(is_devel() && DB_NAME == "ifswacom_devel"){
		?><p><font color="#FF0000"><b>DEVELOPMENT</b></font></p><?
	}
?>
 
<?
	if(!(preg_match('/login\.php/', $_SERVER['SCRIPT_NAME']) == true)){ ?>
  				<p style="color:#FFFFFF"><strong>
    			Logged in as: <?=$_SESSION['user_firstname']?>&nbsp;<?=$_SESSION['user_lastname']?></strong>
                  <form name="logout" method="post" action="<?=ACTION_SCRIPT?>">
                  <input type="hidden" name="form_action" value="logout" />
                  <p><input class="buttonstyle" type="submit" value="Logout"   /></p>
                  </form>
                  </p>
<? } ?>

<?
if($_SESSION['user_role'] == "ADMIN"){
	// display admin user management link
	?>
	<p style="color:#FFFFFF"><a class="main" href="user.php"><strong>Manage Users</strong></a></p>
	<p style="color:#FFFFFF"><strong>Reports:&nbsp;&nbsp;&nbsp;</strong><a class="main" href="report_consultant.php">Client List by Consultant</a>&nbsp;&nbsp;&nbsp;<a class="main" href="report_product.php">Client List by Product</a></p>
    <?
}
?>
 </center>
