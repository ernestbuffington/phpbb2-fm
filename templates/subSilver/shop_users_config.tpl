<table width="100%" cellspacing="2" cellpadding="2" align="center">
<tr> 
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></td>
</tr>
</table>

{CPL_MENU_OUTPUT}

<table width="100%" cellpadding="4" cellspacing="1" align="center" class="forumline"><form method="post" action="{U_SHOP_USER_CONFIG}">
<input type="hidden" name="action" value="update_config" />
<tr> 
	<th class="thHead" colspan="2">{L_EDIT_SHOP}</th>
</tr>
<!-- BEGIN switch_are_shops -->
<tr>
	<td class="row1" width="38%"><b>{L_SHOP_OPENED}:</b></td>
	<td class="row2">{switch_are_shops.SHOP_OPENED}</td>
</tr>
<tr>
	<td class="row1"><b>{L_SHOP_NAME}:</b></td>
	<td class="row2"><input type="text" name="shop_name" class="post" size="38" maxlength="32" value="{switch_are_shops.SHOP_NAME}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_SHOP_TYPE}:</b></td>
	<td class="row2"><input type="text" name="shop_type" class="post" size="38" maxlength="32" value="{switch_are_shops.SHOP_TYPE}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_SHOP_STATUS}:</b></td>
	<td class="row2"><select name="shop_status">
		<option value="0" {switch_are_shops.STATUS_SELECT_1}>{L_OPENED}</option>
		<option value="1" {switch_are_shops.STATUS_SELECT_2}>{L_CLOSED}</option>
		<option value="2" {switch_are_shops.STATUS_SELECT_3}>{L_RESTOCKING}</option>
	</select></td>
</tr>
<tr>
	<td class="row1"><b>{L_POINTS_EARNT}:</b></td>
	<td class="row2">{switch_are_shops.SHOP_EARNT}</td>
</tr>
<tr>
	<td class="row1"><b>{L_POINTS_HOLDING}:</b></td>
	<td class="row2">{switch_are_shops.SHOP_HOLDING}
<!-- END switch_are_shops -->
	<!-- BEGIN switch_withdraw_holdings -->
	&nbsp; [<a href="{switch_withdraw_holdings.WITHDRAW_URL}" class="gensmall">{switch_withdraw_holdings.L_WITHDRAW_HOLDINGS}</a>]
	<!-- END switch_withdraw_holdings -->
<!-- BEGIN switch_are_shops -->
	</td>
</tr>
<tr>
	<td class="row1"><b>{L_ITEMS_LEFT}:</b></td>
	<td class="row2">{switch_are_shops.SHOP_ITEMS_LEFT}</td>
</tr>
<tr>
	<td class="row1"><b>{L_ITEMS_SOLD}:</b></td>
	<td class="row2">{switch_are_shops.SHOP_ITEMS_SOLD}</td>
</tr>
<tr>
	<td class="catBottom" colspan="2" align="center"><input type="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
<!-- END switch_are_shops -->
<!-- BEGIN switch_special_msgs -->
<tr>
	<td class="row1" colspan="2" align="center"><b style="color: #FF0000">{switch_special_msgs.SPECIAL_MSGS}</b></td>
</tr>
<tr>
	<td class="row2" colspan="2" align="center">[ <a href="{switch_special_msgs.SPECIAL_MSGS_URL}">{switch_special_msgs.L_CLEAR}</a> ]</td>
</tr>
<!-- END switch_special_msgs -->
</form></table>
<br />

<!-- BEGIN switch_are_shops -->
<table width="100%" cellpadding="4" cellspacing="1" align="center" class="forumline">
<tr> 
	<th class="thHead" colspan="4">{L_SHOP_ITEMS}</th>
</tr>
<!-- END switch_are_shops -->
<!-- BEGIN switch_are_items -->
<tr> 
	<td class="catLeft" align="center" colspan="2"><span class="cattitle">{L_ITEM}</span></td>
	<td class="cat" align="center" width="150"><span class="cattitle">{L_COST}</span></td>
	<td class="catRight" align="center" width="15%"><span class="cattitle">{L_ACTION}</span></td>
</tr>
<!-- END switch_are_items -->
<!-- BEGIN switch_edit_item -->
<tr> 
	<form action="{switch_edit_item.UPDATE_URL}" method="post">
	<td class="row1" width="2%" align="center"><img src="images/shop/{switch_edit_item.ITEM_NAME}.{switch_edit_item.ITEM_FILEEXT}" title="{switch_edit_item.ITEM_NAME}" alt="{switch_edit_item.ITEM_NAME}" /></td>
	<td class="row1"><b>{switch_edit_item.ITEM_NAME}</b><br /><textarea class="post" name="item_notes" cols="40" rows="2">{switch_edit_item.ITEM_NOTES}</textarea></td>
	<td class="row1" align="center"><input type="text" size="10" maxlength="10" name="item_cost" class="post" value="{switch_edit_item.ITEM_COST}" /></td>
	<td class="row1" align="center"><input type="submit" class="liteoption" value="Update Item" /></td>
	</form>
</tr>
<!-- END switch_edit_item -->
<!-- BEGIN list_items -->
<tr>
	<td class="{list_items.ROW_CLASS}" width="2%" align="center"><img src="images/shop/{list_items.ITEM_NAME}.{list_items.ITEM_FILEEXT}" title="{list_items.ITEM_NAME}" alt="{list_items.ITEM_NAME}" /></td>
	<td class="{list_items.ROW_CLASS}"><span class="forumlink">{list_items.ITEM_NAME}</span><br />{list_items.ITEM_NOTES}</td>
	<td class="{list_items.ROW_CLASS}" align="center">{list_items.ITEM_COST}</td>
	<td class="{list_items.ROW_CLASS}" align="center"><a href="{list_items.EDIT_URL}"><img src="{list_items.EDIT_IMG}" alt="{L_EDIT}" title="{L_EDIT}" /></a> <a href="{list_items.DELETE_URL}"><img src="{list_items.DELETE_IMG}" alt="{L_DELETE}" title="{L_DELETE}" /></a></td>
</tr>
<!-- END list_items -->
<!-- BEGIN switch_no_items -->
<tr> 
	<td class="row1" align="center"><b>{switch_no_items.L_NO_ITEMS}</b></td>
</tr>
<!-- END switch_no_items -->
<!-- BEGIN switch_are_shops -->
</table>
<!-- END switch_are_shops -->

<!-- BEGIN switch_are_a_items -->
<br />
<table width="45%" cellpadding="4" cellspacing="1" align="center" class="forumline"><form method="post" action="{U_SHOP_USER_CONFIG}">
<input type="hidden" name="action" value="change_items" />
<input type="hidden" name="sub_action" value="add_item" />
<tr> 
	<th class="thHead" colspan="2">{L_ADD_ITEMS}</th>
</tr>
<tr>
	<td class="row1" width="38%"><b>{L_ITEM}:</b></td>
	<td class="row2">
	<select name="item_id">
<!-- END switch_are_a_items -->
		<!-- BEGIN list_add_items -->
		<option value="{list_add_items.ITEM_ID}">{list_add_items.ITEM_NAME}</option>
		<!-- END list_add_items -->
<!-- BEGIN switch_are_a_items -->
	</select></td>
</tr>
<tr>
	<td class="row1"><b>{L_COST}:</b></td>
	<td class="row2"><input type="text" name="item_cost" class="post" size="10" maxlength="10" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_NOTES}:</b></td>
	<td class="row2"><textarea class="post" name="item_notes" cols="35" rows="5" /></textarea></td>
</tr>
<tr>
	<td class="catBottom" colspan="2" align="center"><input type="submit" value="{L_SUBMIT}" class="liteoption" /></td>
</tr>
</table>
<!-- END switch_are_a_items -->
	</td>
</tr>
</table>
<br />
<div align="center" class="copyright">Shop 2.6.0 &copy; 2003, {COPYRIGHT_YEAR} <a href="http://www.zarath.com/" class="copyright" target="_blank">Zarath Technologies</a></div>
<br />
<table width="100%" cellspacing="2" align="center"> 
<tr> 
	<td align="right" valign="middle" nowrap>{JUMPBOX}</td> 
</tr> 
</table> 
