<?php 

include ("include/constants.php");

if($_SESSION['user_role'] != "ADMIN"){
	// refuse access to non-admin logins, redirect to portal login
	header ("Location: ".ROOT_WEB."portal/");
	exit;
}

include("header.php");

$message = "";
if((int)param('error') > 0)
{
	switch((int)param('error'))
	{
		case 1:
			$error_message = "Invalid Login or Password.";
			break;
		case 2:
			$error_message = "Error retreiving Login and Password.";
			break;
		case 3:
			$error_message = "Login and Password entries not sent.";
			break;
		case 4:
			$error_message = "Your account has not been activated.";
			break;
	}
}

// default status
if(strlen(param('user_status')) > 0){
	$usr_status = param('user_status');
} else {
	$usr_status = 0;
}


// do the query
$sql = "SELECT * 
			FROM user ";
//echo $sql;
$result = db_query($sql);

?>
<script type="text/javascript">

function edit_user(f, user_id){
	f.user_id.value = user_id;
	f.user_mode.value = "edit";
	f.action="<?= $_SERVER['SCRIPT_NAME']; ?>";
	f.submit();
}

function new_user(f){
	f.user_mode.value="new";
	f.action="<?= $_SERVER['SCRIPT_NAME']; ?>";
	f.submit();
}

function create_user(f){
	if(checkFields(f)){
		f.user_mode.value="new";
		f.form_action.value="user_update";
		// set the role target elements as selected for form submission
		var targetObjs = new Array();
		targetObjs[0] = document.getElementsByName("role_target[]")[0];
		for (var i =0;i<targetObjs.length;i++){
			targetObj = targetObjs[i];
			for(var k=0;k<targetObj.length;k++){
				targetObj.options[k].selected = true;
			}
		}
		//f.submit();
	} else {
		return false;
	}
}

function update_user(f){
	if(checkFields(f)){
		f.user_mode.value="update";
		f.form_action.value="user_update";
		// set the role target elements as selected for form submission
		var targetObjs = new Array();
		targetObjs[0] = document.getElementsByName("role_target[]")[0];
		for (var i =0;i<targetObjs.length;i++){
			targetObj = targetObjs[i];
			for(var k=0;k<targetObj.length;k++){
				targetObj.options[k].selected = true;
			}
		}
		//f.submit();
	} else {
		return false;
	}
}

function delete_user(f,user_id, logname){
	var agree = confirm("Are you sure you want to the login "+logname+"?");
	if(agree){
		f.action = "<?=ACTION_SCRIPT?>";
		f.user_mode.value="delete";
		f.user_id.value = user_id;
		f.form_action.value="user_update";
		f.submit();
	} 
}

function checkFields(f){
	if (f.user_login.value !="" && f.user_password.value !=""){
		return true;
	}
	alert("Please enter all required fields");
	return false;
}

</script>
<? if(param('user_mode') == "edit" || param('user_mode') == "new"){ ?>
<link href="portal.css" rel="stylesheet" type="text/css" />
<form name="user" method="post" action="<?=ACTION_SCRIPT?>">
<? } else { ?>
<form name="user" method="post" action="<?=$_SERVER['SCRIPT_NAME']?>">
<? } ?>
<input type="hidden" name="form_action" value="">
<input type="hidden" name="user_id" value="<?=param('user_id')?>">
<input type="hidden" name="user_status" value="<?=param('user_status')?>">
<input type="hidden" name="user_mode" value="">
<table   border="0" cellpadding="2" cellspacing="0" align="center" bgcolor="#FFFFFF">
<? 
if(param('user_mode')){
	 if(param('user_mode') == "edit"){
		$editSql = "select * from user where user_id = ".(int)param('user_id');
		$editResult = db_query($editSql);
		$editRecord = db_fetch_array($editResult);
		$user_status = $editRecord['user_status'];
	 }
	
?>
    <tr>
      <td height="100%" valign="top">
	  <?=$message?>
	  <table cellpadding="2" align="center">
        <tr> 
          <td colspan="2"> 
            <? if(param('user_mode') == "new") { ?>
            New User 
            <? } else { ?>
            Update User 
            <? } ?>
            </span></td>
        </tr>
        <tr> 
          <td>Login: <font color="#FF0000" size="3">*</font></td>
          <td><input type="text" name="user_login" value="<?= htmlspecialchars($editRecord['user_login']); ?>" style="width:250px"></td>
        </tr>
        <tr> 
          <td>Password: <font color="#FF0000" size="3">*</font></td>
          <td><input type="text" name="user_password" value="<?= htmlspecialchars($editRecord['user_password']); ?>" style="width:250px"></td>
        </tr>
        <tr> 
          <td>First Name:  <font color="#FF0000" size="3">*</font></td>
          <td><input type="text" name="user_firstname" value="<?= htmlspecialchars($editRecord['user_firstname']); ?>" style="width:250px"></td>
        </tr>
        <tr> 
          <td>Last Name: </td>
          <td><input type="text" name="user_lastname" value="<?= htmlspecialchars($editRecord['user_lastname']); ?>" style="width:250px"></td>
        </tr>
        <tr> 
          <td>Email: <font color="#FF0000" size="3">*</font></td>
          <td><input type="text" name="user_email" value="<?= htmlspecialchars($editRecord['user_email']); ?>" style="width:250px"></td>
        </tr>
        <tr> 
          <td>Receive Referral Emails: </td>
          <td><input name="user_refer_email" type="checkbox" <?=($editRecord['user_refer_email']=="1"?"checked":"")?> /></td>
        </tr>
        <tr> 
          <td>Status: </td>
          <td><select name="user_status">
              <option value="1" <?=($user_status==1?" selected":"")?>>Active</option>
              <option value="0" <?=($user_status==0?" selected":"")?>>Disabled</option>
            </select> </td>
        </tr>
        <tr> 
          <td colspan="2" align="center"> 
            <? if (param('user_mode') == "new"){ ?>
            <input type="submit" name="submit" value="Create User" onclick="return create_user(this.form);"> 
            <? } else { ?>
            <input type="submit" name="submit" value="Update User" onclick="return update_user(this.form);"> 
            <? } ?>
            <input type="button" name="cancel" value="Cancel" onclick="javascript:history.go(-1);"> 
          </td>
        </tr>
      </table>
		<br />
       
<?
} else {
	// show the list of users
?>
    <tr>
      <td height="100%" valign="top" class="contentPadding">
			<table  width="100%" border="0" cellpadding="5" cellspacing="0">
            	<tr >
	  			<td height="25"><p><strong>&nbsp;Login Accounts</strong></p></td>
				  <td align="right">[<a href="javascript:new_user(document.user);">New User</a>]</td>
                </tr>
			</table>

                <? //display_msgs('<p align="center">', '</p>'); ?>
                <? //display_errors('<p align="center">', '</p>'); ?>

			<table width="100%" border="0" cellpadding="5" cellspacing="0" class="std">
            <tr><td><strong>Login</strong></td>
            <td><strong>Name</strong></td>
            <td><strong>Email</strong></td>
            <td><strong>Status</strong></td>
            <td style="width: 50px;"><strong>Receive Referral Emails?</strong></td>
            <td>&nbsp;</td>
            </tr>
			<?
                 // write the table body
                while($record = db_fetch_array($result))
                {
                ?>					
                    <tr>
                        <td><a href="javascript:edit_user(document.user,<?=$record['user_id']?>);"><?= htmlspecialchars($record['user_login']); ?></a></td>
                        <td><?= htmlspecialchars($record['user_firstname']); ?>&nbsp;<?= htmlspecialchars($record['user_lastname']); ?></td>
                        <td><?= htmlspecialchars($record['user_email']); ?></td>
                         <td>
                        <? 
                        if ((int)$record['user_status'] == 1){
                            ?>Active<?
                        } else {
                            ?>Disabled<?
                        }
                       ?>
                        </td>
                        <td style="text-align: center;"><?=($record['user_refer_email']=="1"?"Yes":"No")?></td>		
                        <td>
                       <? if ((int)$record['user_id'] != 1){ ?>
                        <a href="javascript: delete_user(document.user,<?=$record['user_id']?>,'<?=$record['user_login']?>');">Delete</a>
                    <? } else {
						?>&nbsp;<?
						}	
					?></td>
                    </tr><?
                }
             ?>
             </table>
<?
}
?>

</td></tr>
<tr colspan="5" align="center" class="main"><td><p><a href="index.php">Return to Product Wheel</a></p></td></tr>
</table>
</form>
<?php include("footer.php"); ?>
