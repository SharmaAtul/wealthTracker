<?PHP

require_once "../include/constants.php";

$action = $_POST['form_action'];

//print_r($_POST);

if(!empty($action))
{
	include "{$action}.php";
}
else {
	die ("action error");
	exit;
}

?>