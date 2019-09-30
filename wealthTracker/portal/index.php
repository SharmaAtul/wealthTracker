<?php
	include ("include/constants.php");
	include("header.php");
?>

<div id="L1_root">
        <!-- div blanket layers -->
        <div id="popUpDiv" style="background-color:#0033CC;">
        <table  border="5" cellpadding="0" cellspacing="0"> 
              <tr>
                <td><iframe id="L1iFrame"></iframe></td>
              </tr>
          </table>
        </div>
<table border="0" align="center" cellpadding="20" cellspacing="0">
        <tr>
        <td width="300px"></td>
        <td width="400px">
        <div class="portal_pie">
            <!-- text layers -->
            <!-- level 1 -->
            <div class="level1" id="L1_fp_menu"><a class="l1" href="#" onmouseover="javascript:popup2('L1_fp');" onMouseOut="javascript:popup2('L1_fp');">Financial<br />Planning</a></div>
            <div class="level1" id="L1_im_menu"><a class="l1" href="#" onmouseover="javascript:popup2('L1_im');" onMouseOut="javascript:popup2('L1_im');">Investment<br />Management</a></div>
            <div class="level1" id="L1_pa_menu"><a class="l1" href="#" onmouseover="javascript:popup2('L1_pa');" onMouseOut="javascript:popup2('L1_pa');">Portfolio<br />Administration</a></div>
            <div class="level1" id="L1_su_menu"><a class="l1" href="#" onmouseover="javascript:popup2('L1_su');" onMouseOut="javascript:popup2('L1_su');">Superannuation<br />SMSF</a></div>
           
           	<!-- level 2 -->

           	<!-- Financial Planning -->
           	<div class="level2_wide" style="top: -95px;	left: 145px;"><a class="l2" href="product.php?pg=L2_fp_ts.html" target="_self" title="Click to Show Details">Taxation</a></div>
           	<div class="level2_narrow" style="top: -175px; left: 300px;"><a class="l2" href="product.php?pg=L2_fp_rp.html" target="_self" title="Click to Show Details">Retirement<br />Planning</a></div>
           	<div class="level2_wide" style="top: -185px; left: 350px;"><a class="l2" href="product.php?pg=L2_fp_pi.html" target="_self" title="Click to Show Details">Personal<br />Insurance&nbsp;Advice</a></div>
           	<div class="level2_wide" style="top: -180px; left: 110px;"><a class="l2" href="product.php?pg=L2_fp_cf.html" target="_self" title="Click to Show Details">Cash Flow</a></div>
           	<div class="level2_wide" style="top: -225px; left: 390px;"><a class="l2" href="product.php?pg=L2_fp_se.html" target="_self" title="Click to Show Details">Succesion &amp;<br />Estate&nbsp;Planning</a></div>
           	<div class="level2_narrow" style="top: -335px; left: 230px;"><a class="l2" href="product.php?pg=L2_fp_wc.html" target="_self" title="Click to Show Details">Wealth<br />Creation</a></div>
             
           	<!-- Investment Management -->			
         	<div class="level2_wide" style="top: -210px; left: 30px;"><a class="l2" href="product.php?pg=L2_im_rp.html" target="_self" title="Click to Show Details">Investment Risk<br />Assessment</a></div>
            <div class="level2_wide" style="top: -190px; left: 0px;"><a class="l2" href="product.php?pg=L2_im_aa.html" target="_self" title="Click to Show Details">Asset<br />Allocation</a></div>
          	<div class="level2_wide" style="top: -190px; left: -5px; visibility:hidden;"><a class="l2" href="product.php?pg=L2_im_as.html" target="_self" title="Click to Show Details">Australian<br /> Shares</a></div>
          	<div class="level2_wide" style="top: -200px; left: -5px;"><a class="l2" href="product.php?pg=L2_im_dv.html" target="_self" title="Click to Show Details">Diversification</a></div>
			<div class="level2_wide" style="top: -180px; left: 0px;"><a class="l2" href="product.php?pg=L2_im_mo.html" target="_self" title="Click to Show Details">Monitoring</a></div>
			<div class="level2_wide" style="top: -170px; left: 30px;"><a class="l2" href="product.php?pg=L2_im_ri.html" target="_self" title="Click to Show Details">Responsible<br />Investment</a></div>

           	<!-- Portfolio Administration -->
          	<div class="level2_wide" style="top: -450px; left: 475px;"><a class="l2" href="product.php?pg=L2_pa_sb.html" target="_self" title="Click to Show Details">Share Brokerage<br />Facility</a></div> 
          	<div class="level2_wide" style="top: -410px; left: 510px;"><a class="l2" href="product.php?pg=L2_pa_sm.html" target="_self" title="Click to Show Details">Separately<br />Managed<br />Accounts</a></div>
          	<div class="level2_wide" style="top: -380px; left: 515px;"><a class="l2" href="product.php?pg=L2_pa_va.html" target="_self" title="Click to Show Details">Virtual<br />Administration<br />Platform</a></div> 
          	<div class="level2_wide" style="top: -330px; left: 485px;"><a class="l2" href="product.php?pg=L2_pa_id.html" target="_self" title="Click to Show Details">Investor Directed<br />Portfolio Service</a></div>
          	<div class="level2_wide" style="top: -390px; left: 495px; visibility:hidden;"><a class="l2" href="product.php?pg=L2_pa_3.html" target="_self" title="Click to Show Details">PA Link 3</a></div> 
          	<div class="level2_wide" style="top: -370px; left: 475px; visibility:hidden;"><a class="l2" href="product.php?pg=L2_pa_4.html" target="_self" title="Click to Show Details">PA Link 4</a></div>

           	<!-- Superannuation SMSF -->
          	<div class="level2_wide" style="top: -330px; left: 160px;"><a class="l2" href="product.php?pg=L2_su_sm.html" target="_self" title="Click to Show Details">SMSF<br />Management</a></div>
          	<div class="level2_wide" style="top: -330px; left: 250px;"><a class="l2" href="product.php?pg=L2_su_ss.html" target="_self" title="Click to Show Details">SMSF Setup</a></div>
          	<div class="level2_wide" style="top: -410px; left: 350px;"><a class="l2" href="product.php?pg=L2_su_is.html" target="_self" title="Click to Show Details">Investment<br />Strategy</a></div>
          	<div class="level2_wide" style="top: -410px; left: 300px; visibility:hidden;"><a class="l2" href="product.php?pg=L2_su_2.html" target="_self" title="Click to Show Details">SU Link 2</a></div>
          	<div class="level2_wide" style="top: -480px; left: 340px; visibility:hidden;"><a class="l2" href="product.php?pg=L2_su_3.html" target="_self" title="Click to Show Details">SU Link 3</a></div>
          	<div class="level2_wide" style="top: -550px; left: 380px; visibility:hidden;"><a class="l2" href="product.php?pg=L2_su_4.html" target="_self" title="Click to Show Details">SU Link 4</a></div>
             
             <!-- web link -->
          	<div class="level2_wide" style="top: -850px; left: 250px;"><a class="ahide" href="<?=BASE_WEB?>" target="_blank" title="Click to visit the IFSWA Website">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br />
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br />
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br />
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br />
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br />
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br />
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></div>
          </div>

          <!-- end text layers -->
        </td>
        <td width="300px" nowrap="nowrap" valign="top">
            <table width="200px" border="0" cellspacing="0" cellpadding="10">
             <tr>
                <td align="center"><p><a class="main" href="list.php"><strong>Quick List</strong></a></p></td>
              </tr>
          <tr>
            <td  width="250px">
                <form name="cart" method="post" action="<?=ACTION_SCRIPT?>">
                <input type="hidden" name="form_action" value="cart_update">
                <input type="hidden" name="mode" value="remove">
                <input type="hidden" name="cart_id" value="">
                <input type="hidden" name="source" value="<?=$_SERVER['REQUEST_URI']?>">
    <div class="cart_wrapper">
                <table class="shoppingcart" width="100%" border="0" cellspacing="0" cellpadding="5">
                  <tr>
                    <td colspan="2"align="center" height="70"><h3>Selected Products</h3></td>
                  </tr>
                  <?=get_cart("cart")?>
                </table>
                </div>
                </form>
            </td>
          </tr>
        </table>
	</td>
        </tr>
        </table>
                        <div id="L1_fp" style="display: none; position: relative; left: 10px; top: 10px;"><iframe src="pages/L1_fp.html" style="position:relative; left: 0px; top: -450px; width: 800px; height: 450px;" ></iframe></div>
                        <div id="L1_im" style="display: none; position: relative; left: 10px; top: 10px;"><iframe src="pages/L1_im.html" style="position:relative; left: 320px; top: -450px; width: 400px; height: 350px;" ></iframe></div>
                        <div id="L1_pa" style="display: none; position: relative; left: 10px; top: 10px;"><iframe src="pages/L1_pa.html" style="position:relative; left: 70px; top: -450px; width: 400px; height: 300px;" ></iframe></div>
                        <div id="L1_su" style="display: none; position: relative; left: 10px; top: 10px;"><iframe src="pages/L1_su.html" style="position:relative; left: 200px; top: -550px; width: 400px; height: 300px;" ></iframe></div>

        </div>

<?php include("footer.php"); ?>