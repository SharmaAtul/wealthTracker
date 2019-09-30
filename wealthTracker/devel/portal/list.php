<?php
	include ("include/constants.php");
	include("header.php");
?>

<div id="L2_root">
<table border="0" align="center" cellpadding="2" cellspacing="0" width="1000px">
<tr>
<td  width="150px">&nbsp;</td>
<td width="600px" align="center">

<div class="textbox">
<form name="cart" method="post" action="<?=ACTION_SCRIPT?>">
<input type="hidden" name="form_action" value="cart_update">
<input type="hidden" name="mode" value="list">
<input type="hidden" name="cart_id" value="">
<input type="hidden" name="source" value="<?=$_SERVER['REQUEST_URI']?>">
<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr><td colspan="2" align="center"><h2>Quick Product List</h2></td></tr>
	<tr><td colspan="2" align="center">Please choose from the following products and click Submit</td></tr>
	<?
  	$sql_group = "SELECT * FROM `group` ";
	//echo $sql_group;
	$result_group = db_query($sql_group);
	while($record_group = db_fetch_array($result_group)){
       	?><tr><td>&nbsp;</td><td><u><h2 style="padding-top:10px;"><?=$record_group['group_name']?></h2></u></td></tr><?
		$sql_groupsub = "	SELECT * 
										FROM `groupsub` s, `group` g
										WHERE g.group_id = s.group_id
										AND g.group_id = ".$record_group['group_id']."
										ORDER BY groupsub_id ";
		//echo $sql__product;
		$result_groupsub = db_query($sql_groupsub);
		while($record_groupsub = db_fetch_array($result_groupsub)){
			?>
            <tr><td>&nbsp;</td><td><h4 style="padding-top:5px;"><?=$record_groupsub['groupsub_name']?></h4></td></tr><?
			$sql_product = "	SELECT * 
										FROM `product` p
										WHERE groupsub_id = ".$record_groupsub['groupsub_id']."
										ORDER BY product_id ";
			//echo $sql__product;
			$result_product = db_query($sql_product);
			while($record_product = db_fetch_array($result_product)){
				?>
				<tr>
				<td><input type="checkbox" name="products[]" value="<?=$record_product['product_id']?>" /></td>
				<td><?=$record_product['product_name']?></td>
				</tr><?
			}
		}
	}
	?>
   <tr><td colspan="2" align="center">&nbsp;</td></tr>
    <tr><td colspan="2" align="center"><input type="submit" value="Submit"</td></td></tr>
</table>
</form>
</div>
    
</td>
<td  width="250px" valign="top">
<table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td align="center"><p><a class="main" href="index.php"><strong>Return to Product Wheel</strong></a></p></td>
  </tr>
  <tr>
    <td align="center"><p><a class="main" href="list.php"><strong>Quick List</strong></a></p></td>
  </tr>


          <tr>
    <td  width="250px">
    <div class="cart_wrapper">
    	<table class="shoppingcart" width="100%" border="0" cellspacing="0" cellpadding="5">
          <tr>
            <td colspan="2"align="center" height="70"><h3>Selected Products</h3></td>
          </tr>
          <?=get_cart("cart")?>
        </table>
     </div>
     </td>
  </tr>
</table>

</td>
</tr>
</table>
</div>
<?php include("footer.php"); ?>
