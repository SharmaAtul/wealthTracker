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
   <p>Clients and families with superannuation balances over $200,000 as this would make it generally more cost effective or clients that wish to purchase business real property.</p>
  <h4>Target:</strong></h4>
   <p>The main reasons for setting up a SMSF were: 
   <ul>
	<li>more control over investments; </li>
	<li>poor performance of other super funds; </li>
	<li>advice from accountant; </li>
	<li>advice from financial planner;</li> 
	<li>save money on fees; </li>
	<li>wider range of investments to choose from; </li>
	<li>saw what existing super funds were charging; and </li>
	<li>tax planning</li>
    </ul>
    </p>
</p>  
   <h4><u>How we can help:</u></h4>
   
<table width="100%" cellpadding="3" cellspacing="0" class="hideshow">
      <tr><td  width="95%"><h4>Setup the SMSF</h4></td>
      <td align="right" width="5%"><a name="planning1" id="plAnchor1" href="javascript:setVisible('plAnchor1','plcover1'); " >Show</a></td>
      <td nowrap="nowrap"><a href="javascript:addCart('Setup the SMSF')" >Add to Cart</a></td>
      </tr>
             <tr><td colspan="2">
           <div id="plcover1" class="hidecover">
            <div id="pltext1" class="hidetext">
                <p>Indian Ocean can assist in:</p>
                    <p>1.	Obtaining a Trust Deed – SMSF is a special type of trust and therefore requires a Trust Deed. The trust deed covers areas such as</p>
                    <ul>                    
                    	<li>The fund’s objectives</li>
                        <li>Who the trustees are</li>
                        <li>Who can be a trustee</li>
                        <li>How trustees are appointed or removed</li>
                        <li>Who can be a member</li>
                        <li>When contributions can be made</li>
                        <li>How benefits can be paid (pension or lump sum) within SIS Act requirements</li>
                        <li>When benefits can be paid</li>
                        <li>How to appoint professional advisers (such as an auditor)</li>
                        <li>The procedures for winding up the fund.</li>
                    </ul>
                    <p>2.	Appointing a trustee – All members need to be trustees or director of the corporate trustee</p>
                    <p>3.	Signing the trustee declaration – This is to declare that the trustee understands the duties and responsibilities as trustee.</p>
                    <p>4.	Lodging election with regulator – After signing the Deed, the trustees need to lodge the election notice, within 60 days, with the
                     regulator in order to become a regulated superannuation fund.</p>
                    <p>5.	Advice and arrangement of Contribution of Fund Assets – The trustees hold the fund’s assets in trust for the benefit of the 
                    members. Member can contribute fund assets in cash or transfer of assets. Member can also rollover the retail superfund to their SMSF.</p>
                    <p>6.	Nominate members – Record each member’s TFN</p>
                    <p>7.	Apply TFN, ABN, GST (optional) with ATO</p>
                    <p>8.	Open a bank account</p>
                    </p>

            </div>
            </td></tr>
             
             <tr><td><h4><strong>Management of the SMSF</strong></h4></td>
            <td align="right" ><a name="planning2" id="plAnchor2" href="javascript:setVisible('plAnchor2','plcover2')" >Show</a> </td>
            <td nowrap="nowrap"><a href="javascript:addCart('Management of the SMSF')" >Add to Cart</a></td>
            </tr>
             <tr><td colspan="2">
           <div id="plcover2" class="hidecover">
            <div id="pltext2" class="hidetext">
                <p>When managing the Self-managed superannuation fund (SMSF), trustees are required to ensure that the investments are not breaching 
                the investment restrictions contained in the SIS Act. These restrictions aim to protect member’s benefits. </p>
				<p>We can assist in the creation of a proper investment strategy that suit your needs and includes investment like:</p>
                <ul>
                <li>Shares;</li>
                <li>Bonds;</li>
                <li>Managed Fund;</li>
                <li>Direct property or property trust;</li>
                <li>Art & collectable;</li>
                <li>Foreign currency, gold and silver;</li>
                <li>Derivatives;</li>
                <li>Business real property;</li>
                </ul>
            </div>
            </div>
            </td></tr>       
             
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
