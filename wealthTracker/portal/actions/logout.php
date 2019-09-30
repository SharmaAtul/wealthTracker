<?

// LOGOUT
unset($_SESSION['user_id']);
unset($_SESSION['user_login']);	
unset($_SESSION['user_role']);	

Header("Location: " . ROOT_WEB . "portal/login.php");

?>