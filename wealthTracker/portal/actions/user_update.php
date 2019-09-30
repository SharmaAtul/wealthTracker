<?

session_start();

if($_SESSION['user_role'] == "ADMIN"){
	
	// only the administrator can do this
	//if((int)$_SESSION['user_id'] != 1){
	//	header("Location: ".ROOT_WEB);
	//	exit;
	//}
	
	//print_r($_POST);
	//exit;
	
	// can't continue without the user id
	if ((int)form_param('user_id') < 0){
		die ("unspecified user_id, cannot continue");
	}
	
	// initialise the new user_id variable
	$edit_user_id = 0;
	
	if(((int)strlen(form_param('user_login')) > 0 && (int)strlen(form_param('user_password')) > 0) || (int)form_param('user_id') > 0)
	{
		$record = array();
		$record['user_login'] = form_param('user_login');
		$record['user_password'] = form_param('user_password');
		$record['user_firstname'] = form_param('user_firstname');
		$record['user_lastname'] = form_param('user_lastname');
		$record['user_email'] = form_param('user_email');
		$record['user_status'] = form_param('user_status');
		$record['user_refer_email'] = (form_param('user_refer_email')=="on"?"1":"0");
		$record['*user_audit'] = 'NOW()';
		
		// do the database operation
		switch(form_param('user_mode')){
			case "new":
				$result = db_insert('user', $record, $edit_user_id);
				break;
			case "update":
				$edit_user_id = (int)form_param('user_id');
				$result = db_update('user', $record, ' user_id=' . $edit_user_id);
				break;
			case "delete":
				$edit_user_id = (int)form_param('user_id');
				$result = db_delete('user', ' WHERE user_id=' . $edit_user_id . " AND user_id <> 1");
				break;
		}
	
		if(!$result){	
			die ("database operation failed");
		} else {
			header("Location: " . ROOT_WEB . "portal/user.php");
			exit;
		}
	} else {
		die ("insufficient parameters");
	}
} else {
			header("Location: " . ROOT_WEB . "portal");
			exit;
} 
?>