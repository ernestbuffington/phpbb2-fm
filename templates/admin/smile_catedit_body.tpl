{SMILEY_MENU}{POST_MENU}
{MOD_CP_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<script language="javascript" type="text/javascript">
<!--
function popupTest(url)
{
	var catid = document.list.cat_id.value.split("|");
	var winwidth = document.list.popup_width.value;
	var winheight = document.list.popup_height.value;
	var grolis = document.list.popup_group_list.value;
	var grocol = document.list.popup_group_cols.value;
	var liscol = document.list.popup_list_cols.value;
	var perpage = document.list.popup_per_page.value;

	if( catid[0] )
	{
		window.open(url + "&cat=" + catid[1] + "&grolis=" + grolis + "&gcol=" + grocol + "&lcol=" + liscol + "&width=" + winwidth + "&height=" + winheight + "&perp=" + perpage, "_phpbbsmilies", "HEIGHT=" + winheight + ",resizable=yes,scrollbars=yes,WIDTH=" + winwidth);
	}
	else
	{
		alert('{L_POPUP_ALERT}');
	}
}

function update_smiley(newimage)
{
	document.smiley_image.src = "{U_SMILEY_BASEDIR}/" + newimage;
}
// -->
</script>

<h1>{L_EDIT}</h1>

<p>{L_EDIT_DESC}</p>

<table cellspacing="1" cellpadding="4" width="100%" align="center" class="forumline"><form action="{U_SMILEY_ACTION1}" method="post"><input type="hidden" name="cat_edit" />
<tr>
	<th colspan="2" class="thHead">{L_EDIT}</th>
</tr>
<!-- BEGIN switch_items_req -->
<tr> 
	<td class="row2" colspan="2"><span class="gensmall">{L_ITEMS_REQUIRED}</span></td>
</tr>
<!-- END switch_items_req -->
<tr>
	<td width="50%" class="row1"><b>{L_SELECT_CAT}:</b></td>
	<td class="row2"><select name="selectcat">
		<option value="0">--</option>
		{S_CAT_LIST}
	</select> &nbsp<input type="submit" value="{L_GO}" class="mainoption" /></td>
	</form>
</tr>
<!-- BEGIN cat_edit -->
<tr>
	<form action="{cat_edit.U_SMILEY_ACTION2}" method="post" name="list">
	<td class="row1"><b>{L_CAT_NAME}: *</b><br /><span class="gensmall">{L_CAT_NAME_EXPLAIN}</span></td>
	<td class="row2"><input type="text" name="cat_name" size="40" maxlength="50" value="{cat_edit.NAME}" class="post" /></td>
</tr>
<tr>
	<td class="row1""><b>{L_CAT_DESC}: *</b><br /><span class="gensmall">{L_CAT_DESC_EXPLAIN}</span></td>
	<td class="row2"><input type="text" name="cat_desc" size="40" maxlength="100" value="{cat_edit.DESC}" class="post" /></td>
</tr>
<tr> 
	<td class="row1"><b>{L_CAT_ICON}:</b></td>
	<td class="row2"><select name="cat_icon" onchange="update_smiley(this.options[selectedIndex].value);">{cat_edit.S_CAT_ICON}</select> <img name="smiley_image" src="{cat_edit.U_SMILEY_IMG}" alt="" title="" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_VIEWABLE_BY}:</b><br /><span class="gensmall">{L_VIEWABLE_BY_EXPLAIN}</span></td>
	<td class="row2"><select name="cat_view_perms">
		{cat_edit.S_VIEW_PERMS}
	</select></td>
</tr>
<!-- BEGIN usergroups -->
<tr>
	<td class="row1"><b>{L_USERGROUPS}:</b><br /><span class="gensmall">{L_USERGROUPS_EXPLAIN}</span></td>
	<td class="row2"><select name="cat_groups[]" multiple="multiple" size="{cat_edit.usergroups.SIZE1}">
		{cat_edit.usergroups.S_USERGROUPS}
	</select></td>
</tr>
<!-- END usergroups -->
<tr> 
	<td class="row1"><b>{L_FORUMS}:</b><br /><span class="gensmall">{L_FORUMS_EXPLAIN}</span></td>
	<td class="row2"><select name="cat_forums[]" multiple="multiple" size="{cat_edit.SIZE2}">
		{cat_edit.S_CAT_FORUMS}
	</select></td>
</tr>
<tr> 
	<td class="row1"><b>{L_ORDER}:</b><br /><span class="gensmall">{L_ORDER_CHANGE}</span></td>
	<td class="row2"><select name="ordernum">
		{cat_edit.S_CAT_ORDER}
	</select></td>
</tr>
<tr> 
	<td class="row1"><b>{L_CAT_SPECIAL}:</b><br /><span class="gensmall">{L_CAT_SPECIAL_EXPLAIN}</span></td>
	<td class="row2"><select name="special">
		{cat_edit.S_CAT_SPECIAL}
	</select></td>
</tr>
<tr>
	<td class="row1"><b>{L_CAT_OPEN}:</b><br /><span class="gensmall">{L_CAT_OPEN_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="cat_open" value="1"{cat_edit.CAT_OPEN_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="cat_open" value="0"{cat_edit.CAT_OPEN_NO} /> {L_NO}</td>
</tr>
<tr>
	<th colspan="2" class="thSides">{L_POPUP_WINDOW}</th>
</tr>
<tr>
	<td colspan="2" class="row2"><span class="gensmall">{L_POPUP_DESCRIPTION}</span></td>
</tr>
<tr>
	<td class="row1"><b>{L_POPUP_GROUP_LIST}:</b></td>
	<td class="row2"><select name="popup_group_list">
		<option value="0"{cat_edit.GROUP_SELECT}>{L_POPUP_GROUP}</option>
		<option value="1"{cat_edit.LIST_SELECT}>{L_POPUP_LIST}</option>
	</select></td>
</tr>
<tr>
	<td class="row1"><b>{L_POPUP_GROUP_COLS}:</b></td>
	<td class="row2"><input type="text" name="popup_group_cols" value="{cat_edit.GROUP_COLS}" size="5" maxlength="2" class="post" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_POPUP_LIST_COLS}:</b></td>
	<td class="row2"><input type="text" name="popup_list_cols" value="{cat_edit.LIST_COLS}" size="5" maxlength="2" class="post" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_PER_PAGE}:</b><br /><span class="gensmall">{L_PER_PAGE_LIMIT}</span></td>
	<td class="row2"><input type="text" name="popup_per_page" value="{cat_edit.PERPAGE}" size="5" maxlength="3" class="post" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_POPUP_SIZE}:</b><br /><span class="gensmall">{L_POPUP_SIZE_ATTRIBS} <a href="javascript:popupTest('{cat_edit.U_MORE_SMILIES}');" onClick="popupTest('{cat_edit.U_MORE_SMILIES}');" title="{L_POPUP_TEST}">{L_POPUP_TEST2}</a></span></td>
	<td class="row2"><input type="text" name="popup_width" value="{cat_edit.WIDTH}" size="5" maxlength="3" class="post" /> x <input type="text" name="popup_height" value="{cat_edit.HEIGHT}" size="5" maxlength="3" class="post" /></td>
</tr>
<tr> 
	<td class="row1"><b>{L_DELETE_CAT}?</b></td>
	<td class="row2"><input type="checkbox" value="1" name="delete" /> {L_DELETE}</td>
</tr>
<tr>
	<td colspan="2" align="center" class="catBottom"><input type="hidden" name="cat_id" value="{cat_edit.S_CAT_ID}" /><input type="submit" value="{L_SUBMIT}" name="cat_edit_submit" class="mainoption" /></td>
	</form>
</tr>
<!-- END cat_edit -->
</table>
<br />
<div align="center" class="copyright">Smiley Categories 2.0.4</a> &copy; 2004, {COPYRIGHT_YEAR} <a href="http://mods.afkamm.co.uk" target="_blank" class="copyright">Afkamm</a></div>
