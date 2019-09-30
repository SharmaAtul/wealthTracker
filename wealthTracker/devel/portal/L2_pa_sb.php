<?php
	include ("./include/constants.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="portal.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="javascript/functions.js"></script>
</head>

<body>
<div id="L2_root">
<table border="0" align="center" cellpadding="20" cellspacing="0">
<tr>
<td  width="300px">&nbsp;</td>
<td width="400px">
        <form name="products" method="post" action="<?=ACTION_SCRIPT?>">
        <input type="hidden" name="form_action" value="cart_update">
        <input type="hidden" name="mode" value="add">
        <input type="hidden" name="product_title" value="">
        <input type="hidden" name="source" value="<?=$_SERVER['REQUEST_URI']?>">

<div class="textbox">

  <h2><strong>Share Brokerage Administration Facility</strong></h2>
  <p>&nbsp;</p>
  <h4>Definition:</h4>
   <p>Once an account is set up with a direct share brokerage firm a client generally can get access to their share portfolio through the internet and can place orders,
    research the shares and check the financial news. </p>
  <h4>Target:</strong></h4>
   <p>Clients that wish to have basic access to an administration platform to purchase direct shares only, however with the introduction of Project Aqua from February 2012 Exchange traded managed funds can be bought, managed and sold using this administration platform as well.</p>  
    <h4>Reason:</h4>
    <p>When buying and selling direct shares to place the order, access basic research, check the status of the order and check the value of the shares held.</p>
   <h4><u>How we can help:</u></h4>
   
<table width="100%" cellpadding="3" cellspacing="0" class="hideshow">
      <tr><td  width="95%"><p>Indian Ocean has access to a range of share brokerage firms that offer these facilities to their clients. The basic third party 
      brokerage transaction cost $10 per trade and we only offer these services as part of our ongoing service.  Additional services can be purchased from the 
      broker which can include real time values, share market depth, real time news to one of the financial news wires.
 Note that these share brokerage administration facilities does not include an overview of all your investments and do not provide any tax reporting 
</p></td>
      <td align="right" width="5%"><!--<a name="planning1" id="plAnchor1" href="javascript:setVisible('plAnchor1','plcover1'); " >Show</a>-->&nbsp;</td>
      <td nowrap="nowrap" valign="top"><a href="javascript:addCart('Share Brokerage Administration Facility')" >Add to Cart</a></td>
      </tr>
             
       </table>

</div>
</form>
</td>
<td  width="300px" valign="top">
<table width="200px" border="0" cellspacing="0" cellpadding="10">
  <tr>
    <td align="center"><p><a href="index.php">Return to Product Wheel</a></p>
      <p>&nbsp;</p></td>
  </tr>
  <tr>
    <td>
        <form name="cart" method="post" action="<?=ACTION_SCRIPT?>">
        <input type="hidden" name="form_action" value="cart_update">
        <input type="hidden" name="mode" value="remove">
        <input type="hidden" name="cart_id" value="">
        <input type="hidden" name="source" value="<?=$_SERVER['REQUEST_URI']?>">
    	<table class="shoppingcart" width="100%" border="0" cellspacing="0" cellpadding="5">
          <tr>
            <td colspan="2"align="center"><h4>Selected Products</h4></td>
          </tr>
          <?=get_cart("cart")?>
        </table>
        </form>
    </td>
  </tr>
</table>

</td>
</tr>
</table>
</div>
</body>
</html>
