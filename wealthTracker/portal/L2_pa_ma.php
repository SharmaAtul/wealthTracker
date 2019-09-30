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

  <h2><strong>Separately Managed Accounts (SMA) </strong></h2>
  <p>&nbsp;</p>
  <h4>Definition:</h4>
   <p>Separately Managed Accounts (SMA) is a customised portfolio solutions that allows clients to gain exposure to shares across a range of 
   professional managed investment models, according to our client’s personal and financial goals.</p>
  <h4>Target:</strong></h4>
   <p>Clients that like to invest directly into the stock market, but don’t want the responsibility of managing their portfolio.</p>  
    <h4>Reason:</h4>
    <p>The SMA features a structure where clients invest according to a chosen model portfolio or a range of model portfolios. They get all the positives of holding direct shares like dividend reinvestment and corporate 
    actions, but also enjoy the expertise from some of the best company research houses of Australia. </p>
   <h4><u>How we can help:</u></h4>
   
<table width="100%" cellpadding="3" cellspacing="0" class="hideshow">
      <tr><td  width="95%"><p>We can set up a SMA account for our clients trading in their personal names, or trust, company or superannuation fund. Our solution offers a range 
                of research houses model portfolios and includes portfolio objectives like high imputation and dividend companies to sustainable investment portfolio or the ASX 20. The SMA accounts provide 
                comprehensive reporting features which include detailed reporting on tax, capital gains, values, asset allocation and individual shares. </p></td>
      <td align="right" width="5%"><!--<a name="planning1" id="plAnchor1" href="javascript:setVisible('plAnchor1','plcover1'); " >Show</a>-->&nbsp;</td>
      <td nowrap="nowrap" valign="top"><a href="javascript:addCart('Separately Managed Accounts (SMA)')" >Add to Cart</a></td>
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
