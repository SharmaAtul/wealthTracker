<?php

function get_cart($mode=""){

	$sql = "SELECT * 
				FROM cart c, `product` p
				WHERE c.product_id = p.product_id
				AND user_id = ".(int)$_SESSION['user_id']."
				AND status = '1' ";
	//echo $sql;
	$result = db_query($sql);
	$count = db_num_rows($result);
	
	if($count == 0){
		?>
			<tr>
				<td nowrap colspan="2" align="center">No Selection</td>
			</tr>
        <?
	} else {
		while($record = db_fetch_array($result)){
			?>
			<tr>
				<td <?=($mode != "cart"?"align=center":"")?>><strong><?=$record['product_name']?></strong></td>
                <? if ($mode == "cart"){ ?>
					<td nowrap align="center"><strong><a href="javascript: removeCart(<?=$record['cart_id']?>)" />Remove</a></strong></td>
                <? } ?>
			</tr>
 			 <?
		}
		if($mode == "cart"){
		?>
        <tr>
        	<td colspan="2" align="center"><strong><a href="checkout.php"><span style="font-size: 15px">Send Request</span></a></strong></td>
        </tr>
		<?
		}
	}
}

function cart_item_exists($product_id){
		// check for existing products with the same title
		$sql = "SELECT * 
					FROM cart 
					WHERE product_id = '".$product_id."' 
					AND user_id = ".(int)$_SESSION['user_id']."
					AND status = '1' ";
	    //echo $sql;
		$result = db_query($sql);
		$count = db_num_rows($result);
		return ($count>0?true:false);

}

function param($param_name)
{
	assert('is_string($param_name)');
	assert('(int)strlen($param_name) > 0');
	
	
	if(isset($_POST[$param_name]))
	{
		return stripslashes(trim($_POST[$param_name]));
	}
	elseif(isset($_GET[$param_name]))
	{
		return stripslashes(trim($_GET[$param_name]));
	}
	else 
	{
		return '';
	}
}

function form_param($param_name)
{
	assert(is_string($param_name));
	if(isset($_POST[$param_name]) == true)
	{
		return stripslashes(trim($_POST[$param_name]));
	}
	else 
	{
		return "";
	}
}

function is_devel(){
	if( preg_match('/\/devel\//', $_SERVER['SCRIPT_NAME']) == true){
		return true;
	}
	return false;
}


?>
