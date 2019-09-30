<?php 

	include ("include/constants.php");
	include("header.php");

	$error_message = "";
	if((int)param('error') > 0)
	{
		switch((int)param('error'))
		{
			case 1:
				$error_message = "Invalid Username or Password.";
				break;
			case 2:
				$error_message = "Error retreiving Username and Password.";
				break;
			case 3:
				$error_message = "Username and Password entries not sent.";
				break;
			case 4:
				$error_message = "Your account has not been activated.";
				break;
			case 5:
				$error_message = "Access denied.";
				break;
		}
	}
	
	if(strlen($error_message) > 0)
	{
	?><p style="color: #FF0000; font-weight: bold; text-align:center;"><?= htmlspecialchars($error_message); ?></p><?
	}
	
	?>
<br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
		<table border="0" cellpadding="10" cellspacing="0" align="center">
		  <tr>
		  <td width="100%">
		 <form name="login" method="post" action="<?=ACTION_SCRIPT?>">
          <input type="hidden" name="form_action" value="login">
            <table border="0" cellpadding="5" cellspacing="0" width="100%" height="100%">
            <tr><td colspan="2" bgcolor="#E00719"><img src="../images/logo.png" /></td></tr>
            	<tr><td height="80" colspan="2" align="center"><h2 style="color:#FFFFFF;">IFSWA Referral Tool Login</h2></td></tr>
              <tr>
                <td><strong  style="color:#FFFFFF;">Username:</strong></td>
                <td><input name="user_login" type="text" style="width:150px"></td>
               </tr>
              <tr>
                <td><strong style="color:#FFFFFF;">Password:</strong></td>
                <td><input name="user_password" type="password" style="width:150px"></strong></td>
              </tr>
              <tr>
                 <td colspan="2" align="center"><input class="buttonstyle"  type="submit" value="Login"></td>
              </tr>
          </table>
            </form>          
		  </td>
        </tr>
	   </table>

<?php include("footer.php"); ?>