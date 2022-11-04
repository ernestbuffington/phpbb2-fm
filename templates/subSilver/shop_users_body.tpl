<table width="100%" cellspacing="2" cellpadding="2" align="center"><form method="get" action="{U_SHOP_USERS}">
<tr> 
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a>{SHOPLOCATION}</td>
	<!-- BEGIN switch_is_shops -->
	<td align="right" class="genmed">{L_SEARCH}: <input type="text" name="search_string" size="25" maxlength="32" class="post" /> <input type="submit" value="{L_SUBMIT}" class="liteoption" /></td>
	<!-- END switch_is_shops -->
</tr>
</form></table>

<table width="100%" cellpadding="4" cellspacing="1" align="center" class="forumline">
<tr> 
	<th class="thHead" colspan="3">{L_SHOP_TITLE}</th>
</tr>
<!-- BEGIN switch_user_shop -->
<tr>
	<td class="row2" height="30" colspan="3" align="center"><span class="gen">{switch_user_shop.U_OPEN_SHOP}</span></td>
</tr>
<!-- END switch_user_shop -->
<!-- BEGIN switch_is_shops -->
<tr>
	<td class="catLeft" align="center"><span class="cattitle">{L_SHOP_NAME}</span></td>
	<td class="cat" align="center"><span class="cattitle">{L_SHOP_TYPE}</span></td>
	<td class="catRight" align="center"><span class="cattitle">{L_USERNAME}</span></td>
</tr>
<!-- END switch_is_shops -->
<!-- BEGIN shop_row -->
<tr>
	<td class="{shop_row.ROW_CLASS}"><a href="{shop_row.SHOP_URL}" title="{shop_row.SHOP_NAME}" class="nav">{shop_row.SHOP_NAME}</a></td>
	<td class="{shop_row.ROW_CLASS}">{shop_row.SHOP_TYPE}</td>
	<td class="{shop_row.ROW_CLASS}"><a href="{shop_row.U_VIEWPROFILE}" class="genmed">{shop_row.SHOP_OWNER}</a></td>
</tr>
<!-- END shop_row -->
<!-- BEGIN switch_no_shops -->
<tr>
	<td class="row1" colspan="3" height="30" align="center"><span class="gen">{switch_no_shops.L_NO_SHOPS}</span></td>
</tr>
<!-- END switch_no_shops -->
<tr>
	<td class="catBottom" colspan="3" align="center"><span class="nav"><a href="{U_INVENTORY}" class="nav">{L_INVENTORY}</a> {SHOP_TRANS}</span></td>
</tr> 
<!-- BEGIN switch_special_msgs -->
<tr>
	<td class="row1" colspan="3" align="center"><b style="color: #FF0000">{switch_special_msgs.SPECIAL_MSGS}</b></td>
</tr>
<tr>
	<td class="row2" colspan="3" align="center">[ <a href="{switch_special_msgs.SPECIAL_MSGS_URL}">{switch_special_msgs.L_CLEAR}</a> ]</td>
</tr>
<!-- END switch_special_msgs -->
</table>
<br />
<table width="100%" cellspacing="2" align="center"> 
<tr> 
	<td align="right" valign="middle" nowrap>{JUMPBOX}</td> 
</tr> 
</table> 
<br />
<div align="center" class="copyright">Shop 2.6.0 &copy; 2003, {COPYRIGHT_YEAR} <a href="http://www.zarath.com/" class="copyright" target="_blank">Zarath Technologies</a></div>
