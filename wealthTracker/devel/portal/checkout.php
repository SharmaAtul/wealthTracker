<?php
	include ("./include/constants.php");
	include("header.php");
	// there are cart contents, if not redirect to home page
	/*
	$sql = "SELECT * 
				FROM cart 
				WHERE user_id = ".(int)$_SESSION['user_id']." AND status = '1' ");
	echo $sql;
	$result = db_query($sql);
	$count = db_num_rows($result);
	*/

?>

<div id="L2_root">
<form name="cart" method="post" action="<?=ACTION_SCRIPT?>" onsubmit="return validateForm(this);">
<input type="hidden" name="form_action" value="cart_submit">
<table border="0" align="center" cellpadding="20" cellspacing="0">
<tr>
<td  width="300px">&nbsp;</td>
<td  width="400px" align="center">
<table  class="shoppingcart" cellspacing="0" cellpadding="5" border="0" align="center">
    <tr>
        <td nowrap colspan="2" align="center" height="70"><h2><strong>Product List Confirmation</strong></h2></td>
    </tr>
    <tr>
        <td nowrap colspan="2" align="center">
          <p>Please enter the Client details:
          <table border="0" cellpadding="2" cellspacing="0" class="std">
              <tr>
                <td>First Name(s): <span style="color:#FF0000">*</span></td>
                <td ><input type="text" name="client_firstname" value="" size="30"></td>
              </tr>
              <tr>
                <td>Last Name: <span style="color:#FF0000">*</span></td>
                <td ><input type="text" name="client_lastname" value="" size="30"></td>
              </tr>
              <tr>
                <td>Entity/Business Name:</td>
                <td ><input type="text" name="client_entity" value="" size="30"></td>
              </tr>
              <tr>
                <td>Preferred Phone: <span style="color:#FF0000">*</span></td>
                <td><input type="text" name="client_phone" value="" size="30"></td>
              </tr>
              <tr>
                <td>Preferred Contact Time:</td>
                <td>  Between             
                    <select name="client_contact_time_from">
                        <option value=""></option>
                        <option value="8:00 AM">8:00 AM</option>
                        <option value="9:00 AM">9:00 AM</option>
                        <option value="10:00 AM">10:00 AM</option>
                        <option value="11:00 AM">11:00 AM</option>
                        <option value="12:00 AM">12:00 AM</option>
                        <option value="1:00 PM">1:00 PM</option>
                        <option value="2:00 PM">2:00 PM</option>
                        <option value="3:00 PM">3:00 PM</option>
                        <option value="4:00 PM">4:00 PM</option>
                        <option value="5:00 PM">5:00 PM</option>
                        <option value="6:00 PM">6:00 PM</option>
                        <option value="7:00 PM">7:00 PM</option>
                        <option value="8:00 PM">8:00 PM</option>
                        <option value="9:00 PM">9:00 PM</option>
                  </select>
                  And
                   <select name="client_contact_time_to">
                        <option value=""></option>
                        <option value="8:00 AM">8:00 AM</option>
                        <option value="9:00 AM">9:00 AM</option>
                        <option value="10:00 AM">10:00 AM</option>
                        <option value="11:00 AM">11:00 AM</option>
                        <option value="12:00 AM">12:00 AM</option>
                        <option value="1:00 PM">1:00 PM</option>
                        <option value="2:00 PM">2:00 PM</option>
                        <option value="3:00 PM">3:00 PM</option>
                        <option value="4:00 PM">4:00 PM</option>
                        <option value="5:00 PM">5:00 PM</option>
                        <option value="6:00 PM">6:00 PM</option>
                        <option value="7:00 PM">7:00 PM</option>
                        <option value="8:00 PM">8:00 PM</option>
                        <option value="9:00 PM">9:00 PM</option>
                  </select>
				</td>
              </tr>
             <tr>
                <td>Preferred Contact Date:</td>
                <td>
					<input type="text" name="client_contact_date" size="10" readonly="1" id="f_date_c" />
                     <img onmouseout="this.style.background=''" onmouseover="this.style.background='red';" title="Date selector" style="cursor: pointer; border: 1px solid red; margin-top:2px;" id="f_trigger_c" src="images/calendar.gif"/>
                <script type="text/javascript">
					Calendar.setup({
						inputField     :    "f_date_c",     // id of the input field
						ifFormat       :    "%d/%m/%Y",      // format of the input field
						button         :    "f_trigger_c",  // trigger for the calendar (button ID)
						align          :    "Tl",           // alignment (defaults to "Bl")
						singleClick    :    true
					});
				</script>
                </td>
              </tr>
              <tr>
                <td>Email:</td>
                <td><input type="text" name="client_email" value="" size="30"></td>
              </tr>
              <tr>
                <td>Tax Return Stage:</td>
                <td>
                    <select name="client_tax_stage">
                        <option value="">Choose...</option>
                        <option value="0 - Tax Lodgement Applies to This Client">0 - Tax Lodgement Applies to This Client</option>
                        <option value="1 - Information Received">1 - Information Received</option>
                        <option value="2 - Query List Sent">2 - Query List Sent</option>
                        <option value="3 - Return/Financial Preparation">3 - Return/Financial Preparation</option>
                        <option value="4 - Data Entry">4 - Data Entry</option>
                        <option value="5 - Tax Return Review">5 - Tax Return Review</option>
                        <option value="6 - Sent For Signature">6 - Sent For Signature</option>
                        <option value="7 - Return Lodged">7 - Return Lodged</option>
                        <option value="8 - Assessment Received">8 - Assessment Received</option>
                        <option value="9 - Notice of Assessment to Client">9 - Notice of Assessment to Client</option>
                    </select>
                </td>
                </tr>
              <tr>
                <td>Comments:</td>
                <td><textarea name="client_comments" cols="23" rows="6"></textarea></td>
              </tr>
          </table>
          </p>
            <p>You may return to the  Product List to change your selection, otherwise your selection is ready to submit.</p>
            <p><a href="javascript: history.go(-1)">Change the Product Selection</a></p>
          <p>&nbsp;</p></td>
    </tr>
          <?=get_cart()?>
       <tr>
        	<td align="center" valign="middle" colspan="2" height="70px">
       	    <p><!--<a href="javascript: submitCart();" style="color:#333333; font-size:18px"><strong>Submit Product List</strong></a>-->
            <input type="submit" value="Submit"   />
            </p></td>
        </tr>
        <tr><td align="center"><a href="index.php" style="font-size:16px"><strong>Return to Product Wheel</strong></a></td></tr>
	</table>
</td>
<td  width="300px">&nbsp;</td>
</tr>
</table>
</form>
</div>
</body>
</html>
