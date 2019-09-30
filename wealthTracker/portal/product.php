<?php
	include ("include/constants.php");
	include("header.php");
?>

<form name="cart" method="post" action="<?=ACTION_SCRIPT?>">
<input type="hidden" name="form_action" value="cart_update">
<input type="hidden" name="mode" value="">
<input type="hidden" name="cart_id" value="">
<input type="hidden" name="product_id" value="">
<input type="hidden" name="source" value="<?=$_SERVER['REQUEST_URI']?>">
<div id="L2_root">
<table border="0" align="center" cellpadding="2" cellspacing="0" width="1000px">
<tr>
<td  width="150px">&nbsp;</td>
<td width="600px" align="center">
    
   <? include("pages/".$_GET['pg']); ?>
    
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
</form>
<?php include("footer.php"); ?>
