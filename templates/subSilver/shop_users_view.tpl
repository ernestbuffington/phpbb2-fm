<table width="100%" cellspacing="2" cellpadding="2"  align="center">
<tr> 
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a>{SHOPLOCATION}</td>
</tr>
</table>

<table width="100%" cellpadding="4" cellspacing="1" align="center" class="forumline">
<!-- BEGIN switch_main_list -->
<tr> 
	<th class="thHead" colspan="3">{L_SHOP_TITLE}</th>
</tr>
<tr>
	<td class="catLeft" align="center"><span class="cattitle">{L_ICON}</span></td>
	<td class="cat" align="center"><span class="cattitle">{L_ITEM}</span></td>
	<td class="catRight" width="50" align="center"><span class="cattitle">{L_COST}</span></td>
</tr>
<!-- END switch_main_list -->
<!-- BEGIN list_items -->
<tr>
	<td class="{list_items.ROW_CLASS}" width="2%" align="center"><a href="{list_items.ITEM_URL}"><img src="images/shop/{list_items.ITEM_NAME}.{list_items.ITEM_FILEEXT}" alt="{list_items.L_ITEM_INFO} {list_items.ITEM_NAME}" title="{list_items.L_ITEM_INFO} {list_items.ITEM_NAME}" /></a></td>
	<td class="{list_items.ROW_CLASS}"><a href="{list_items.ITEM_URL}" title="{list_items.L_ITEM_INFO} {list_items.ITEM_NAME}" class="forumlink">{list_items.ITEM_NAME}</a><br />{list_items.ITEM_S_DESC} <span class="gensmall"><i>[ {L_NOTES}: {list_items.ITEM_NOTES} ]</i></span></td>
	<td class="{list_items.ROW_CLASS}" align="center" nowrap="nowrap">{list_items.ITEM_COST}</td>
</tr>
<!-- END list_items -->
<!-- BEGIN switch_item_info -->
<tr> 
	<th class="thHead" colspan="5">{L_TABLE_TITLE}</th>
</tr>
<tr>
	<td class="catLeft" align="center"><span class="cattitle">{L_ICON}</span></td>
	<td class="cat" align="center"><span class="cattitle">{L_ITEM}</span></td>
	<td class="cat" width="50" align="center"><span class="cattitle">{L_COST}</span></td>
	<td class="cat" width="50" align="center"><span class="cattitle">{L_OWNED}</span></td>
	<td class="catRight" width="100" align="center"><span class="cattitle">{L_ACTION}</span></td>
</tr>
<tr>
	<td width="2%" class="row1"><img src="images/shop/{switch_item_info.ITEM_NAME}.{switch_item_info.ITEM_FILEEXT}" title="{switch_item_info.ITEM_NAME}" alt="{switch_item_info.ITEM_NAME}"></td>
	<td class="row1"><span class="forumlink">{switch_item_info.ITEM_NAME}</span></br />{switch_item_info.ITEM_L_DESC}<br /><span class="gensmall"><i>[ {L_NOTES}: {switch_item_info.ITEM_NOTES} ]</i></span></td>
	<td class="row2" align="center" nowrap="nowrap">{switch_item_info.ITEM_COST}</td>
	<td class="row1" align="center">{switch_item_info.ITEM_OWNED}</td>
	<td class="row1" align="center" nowrap="nowrap"><a href="{switch_item_info.ITEM_URL}" title="Buy {switch_item_info.ITEM_NAME}">Buy {switch_item_info.ITEM_NAME}</a></td>
</tr>
<!-- END switch_item_info -->
<tr>
	<td class="catBottom" colspan="5" align="center"><span class="nav"><a href="{U_INVENTORY}" class="nav">{L_INVENTORY}</a> {SHOP_TRANS}</span></td>
</tr> 
<!-- BEGIN switch_special_msgs -->
<tr>
	<td class="row1" colspan="5" align="center"><b style="color: #FF0000">{switch_special_msgs.SPECIAL_MSGS}</b></td>
</tr>
<tr>
	<td class="row2" colspan="5" align="center">[ <a href="{switch_special_msgs.SPECIAL_MSGS_URL}">{switch_special_msgs.L_CLEAR}</a> ]</td>
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
