<?php

include ("include/constants.php");

if($_SESSION['user_role'] != "ADMIN"){
	// refuse access to non-admin logins, redirect to portal login
	header ("Location: ".ROOT_WEB."portal/");
	exit;
}	

include("header.php");

// create year dropdown list elements
$thisYear = date('Y');
$startYear = 2012;
if(param('report_year') == ""){
	$selectYear = $thisYear;
} else {
	$selectYear = (int)param('report_year');
}

foreach (range($startYear,$thisYear) as $year) {
	$selected = "";
	if($year == $selectYear) { $selected = " selected"; }
	$yearOptions .= "<option value=\"".$year."\"". $selected . ">" . $year . "</option>";
}
$yearOptions = "<option value=\"0\">All</option>" . $yearOptions;

// create month dropdown list elements
$thisMonth = date('n');
if(param('report_month') == ""){
	$selectMonth = $thisMonth;
} else {
	$selectMonth = (int)param('report_month');
}

$monthNames = array(
					0 => "All",
 					1 => "Jan",
 					2 => "Feb",
 					3 => "Mar",
 					4 => "Apr",
 					5 => "May",
 					6 => "Jun",
 					7 => "Jul",
 					8 => "Aug",
 					9 => "Sep",
 					10 => "Oct",
 					11 => "Nov",
 					12 => "Dec" );
					
foreach (range(0,12) as $month) {
	$selected = "";
	if($month == $selectMonth) { $selected = " selected"; }
	$monthOptions .= "<option value=\"".$month."\"". $selected . ">" . $monthNames[$month] . "</option>";
}
?>

<div id="report_root">
<table border="0" align="center" cellpadding="2" cellspacing="0" width="95%">
<tr><td align="center"><p><a class="main" href="index.php"><strong>Return to Product Wheel</strong></a></p></td></tr>
<tr>
<td align="center">

<!-- report header -->
<div class="textbox_report">
<form name="report" method="get" action="<?=$_SERVER['REQUEST_URI']?>">
<table border="0" cellspacing="0" cellpadding="3" align="center">
	<tr><td colspan="8" align="center"><h2 style="padding-bottom:10px;">Client List by Consultant</h2></td></tr>
	<tr><td>Consultant Filter:</td>
	<?
		$sql_user = "SELECT * 
					FROM user 
					ORDER BY user_firstname, user_lastname ";
		$result_user = db_query($sql_user);
	?><td>
		<select name="user_id">
			<option value="all" <?=(param('user_id')=="all"?"selected":"")?>>All</option>
		<?	while ($row = db_fetch_array($result_user)){ ?>
			<option value="<?=$row['user_id']?>" <?=(param('user_id')==$row['user_id']?"selected":"")?>><?=$row['user_firstname']." ".$row['user_lastname']?></option>
		<?	} ?>
		</select>
        </td>
        <td>&nbsp;<td>
        <td>Month:<td>
        <td>
        <select name="report_month">
        	<?=$monthOptions?>
        </select>
        </td>
        <td>
        <select name="report_year">
        	<?=$yearOptions?>
		</select>
		</td>
        <td>&nbsp;</td>
        <td><input type="submit" value="Search" /></td>
    </tr>
</table>
</form>
</div>

<!-- report results -->
<div class="textbox_report">
<table border="0" cellspacing="0" cellpadding="3" align="center" class="report" width="100%"> <?
	$sql_cart = "SELECT distinct l.client_id, l.client_firstname, l.client_lastname, l.client_comments, l.client_tax_stage,  
						l.client_entity, DATE_FORMAT( c.order_date, '%d/%m/%Y' ) AS order_date, u.user_firstname, u.user_lastname, u.user_id
						FROM `user` u,  `product` p,  `cart` c
						LEFT OUTER JOIN `client` l ON c.client_id = l.client_id
						WHERE c.user_id = u.user_id 
						AND c.product_id = p.product_id
						AND c.status = '0'  " . (param('user_id')=="all" || param('user_id')==""?"":" AND c.user_id = ".param('user_id')) . "
						".($selectMonth > 0?" AND DATE_FORMAT(c.order_date,'%c') = '".$selectMonth."' ":"")."
						".($selectYear > 0?" AND DATE_FORMAT(c.order_date,'%Y') = '".$selectYear."' ":"")."
						ORDER BY u.user_firstname ASC, u.user_lastname ASC, l.client_firstname ASC, l.client_lastname ASC ";
	//echo $sql_cart."<br>";
	$result_cart  = db_query($sql_cart );
	$count = db_num_rows($result_cart );
	if($count ==0){
		?><tr><td colspan="4" align="center">No Records Found</td></tr><?
	} else {
		?>
        <tr>
        <td nowrap="nowrap" valign="top"><h3>Consultant</h3></td>
        <td nowrap="nowrap" valign="top"><h3>Client Name</h3></td>
        <td nowrap="nowrap" valign="top"><h3>Entity Name</h3></td>
        <td nowrap="nowrap" valign="top"><h3>Product Group</h3></td>
        <td nowrap="nowrap" valign="top"><h3>Product Name</h3></td>
        <td nowrap="nowrap" valign="top"><h3>MYOB Stage</h3></td>
        <td nowrap="nowrap" valign="top" align="center"><h3>Order Date</h3></td>
        <td nowrap="nowrap" valign="top"><h3>Comments</h3></td>
        </tr>
		<?
	}
	while($record_cart  = db_fetch_array($result_cart)){
		$sql_product = "	SELECT *
									FROM `cart` c, `product` p, `groupsub` s
									WHERE c.product_id = p.product_id
									AND s.groupsub_id = p.groupsub_id
									AND user_id =  ".$record_cart['user_id']. "
									AND client_id = ".$record_cart['client_id']. "
									AND DATE_FORMAT(order_date, '%d/%m/%Y' ) = '".$record_cart['order_date']. "' ";
		//echo $sql_product."<br>";
		$result_product  = db_query($sql_product);	
		$groupsub_list  = "";
		$product_list = "";
		while($record_product  = db_fetch_array($result_product)){
					$groupsub_list .= $record_product['groupsub_name']."<br />";
					$product_list .= $record_product['product_name']."<br />";
		}
    	?>
        <tr>
        <td valign="top"><?=$record_cart['user_firstname']." ".$record_cart['user_lastname']?></td>
        <td valign="top"><?=$record_cart['client_firstname']." ".$record_cart['client_lastname']?></td>
        <td valign="top" nowrap="nowrap"><?=$record_cart['client_entity']?></td>
        <td valign="top" nowrap="nowrap"><?=$groupsub_list?></td>
        <td valign="top" nowrap="nowrap"><?=$product_list?></td>
        <td valign="top" nowrap="nowrap"><?=$record_cart['client_tax_stage']?></td>
        <td align="center"  valign="top" nowrap="nowrap"><?=$record_cart['order_date']?></td>
        <td valign="top" width="30%"><?=$record_cart['client_comments']?></td>
        </tr><?
	}
	?>
</table>
</div>

</td>
</tr>
</table>
</div>
<?php include("footer.php"); ?>

