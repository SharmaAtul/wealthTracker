<?

// LOGIN USER

// reset user array
unset($_SESSION['user_id']);
unset($_SESSION['user_login']);	
unset($_SESSION['user_role']);	
//print_r($_POST);

if((int)strlen(form_param('user_login')) > 0 && (int)strlen(form_param('user_password')) > 0)
{
	$sql = "SELECT * 
				FROM user
				WHERE user_login='" . db_escape_string(param('user_login'))."' 
				AND user_password='" . db_escape_string(param('user_password'))."' ";
	$result = db_query($sql);
	//echo $sql;
	if($result)
	{
		if(db_num_rows($result) == 1)
		{
			$record = db_fetch_array($result);
			
			// reject disabled accounts
			if(!((int)$record['user_status'] == 1)) {
				header("Location: " . ROOT_WEB . "portal/login.php?error=4");
				exit;				
			}
			
			$_SESSION['user_id'] = (int)$record['user_id'];
			$_SESSION['user_login'] = $record['user_login'];	
			$_SESSION['user_firstname'] = $record['user_firstname'];	
			$_SESSION['user_lastname'] = $record['user_lastname'];	
			$_SESSION['user_email'] = $record['user_email'];
			$_SESSION['user_refer_email'] = $record['user_refer_email'];
			$_SESSION['user_role'] = $record['user_role'];	
			// update user
			$update_record['*user_audit'] = "NOW()";
			$result = db_update('user', $update_record, ' user_id=' . (int)$record['user_id']);			
			header("Location: " . ROOT_WEB . "portal/index.php");
			exit;				
		}
		else 
		{
			Header("Location: " . ROOT_WEB . "portal/login.php?error=1");
			exit;
		}
	}
	else 
	{
		die ('there was an error logging in');	
	}
	Header("Location: " . ROOT_WEB . "portal/login.php?error=2");
	exit;
	
}
Header("Location: " . ROOT_WEB . "portal/login.php?error=3");
exit;


?>