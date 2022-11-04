{SMILEY_MENU}{POST_MENU}
{MOD_CP_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<script language="javascript" type="text/javascript">
<!--
function unlock()
{
	eval("document.list.ordernum.disabled = false");
}

function lock()
{
	eval("document.list.ordernum.disabled = true");
}

function update_smiley(newimage)
{
	document.smiley_image.src = "{U_SMILEY_BASEDIR}/" + newimage;
}
// -->
</script>

<h1>{L_ADD}</h1>

<p>{L_ADD_DESC}</p>

<table cellspacing="1" cellpadding="4" width="100%" align="center" class="forumline"><form action="{U_SMILEY_ACTION}" method="post" name="list">
<tr>
	<th colspan="2" class="thHead">{L_ADD}</th>
</tr>
<tr> 
	<td class="row2" colspan="2"><span class="gensmall">{L_ITEMS_REQUIRED}</span></td>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_CAT_NAME}: *</b><br /><span class="gensmall">{L_CAT_NAME_EXPLAIN}</span></td>
	<td class="row2"><input type="text" name="cat_name" value="" size="40" maxlength="50" class="post" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_CAT_DESC}: *</b><br /><span class="gensmall">{L_CAT_DESC_EXPLAIN}</span></td>
	<td class="row2"><input type="text" name="cat_desc" value="" size="40" maxlength="100" class="post" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_CAT_ICON}:</b></td>
	<td class="row2"><select name="cat_icon" onchange="update_smiley(this.options[selectedIndex].value);">{S_CAT_ICON}</select> <img name="smiley_image" src="{U_SMILEY_IMG}" alt="" title="" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_VIEWABLE_BY}:</b><br /><span class="gensmall">{L_VIEWABLE_BY_EXPLAIN}</span></td>
	<td class="row2"><select name="cat_view_perms">{S_VIEW_PERMS}</select></td>
</tr>
<!-- BEGIN usergroups -->
<tr>
	<td class="row1"><b>{L_USERGROUPS}:</b><br /><span class="gensmall">{L_USERGROUPS_EXPLAIN}</span></td>
	<td class="row2"><select name="cat_groups[]" multiple="multiple" size="{usergroups.SIZE1}">	
		{usergroups.S_USERGROUPS}
	</select></td>
</tr>
<!-- END usergroups -->
<tr>
	<td class="row1"><b>{L_FORUMS}:</b><br /><span class="gensmall">{L_FORUMS_EXPLAIN}</span></td>
	<td class="row2"><select name="cat_forums[]" multiple="multiple" size="{SIZE2}">
		{S_FORUMS}
	</select></td>
</tr>
<tr>
	<td class="row1"><b>{L_ORDER}:</b></td>
	<td class="row2"><input type="radio" name="order" value="first" onClick="lock()" /> {L_FIRST}&nbsp;&nbsp;<input type="radio" name="order" value="last" onClick="lock()" checked="checked" /> {L_LAST}&nbsp;&nbsp;<input type="radio" name="order" value="after" onClick="unlock()" /> {L_AFTER}&nbsp;&nbsp;<select name="ordernum" disabled="disabled">{S_CAT_ORDER}</select></td>
</tr>
<tr>
	<td class="row1"><b>{L_CAT_SPECIAL}:</b><br /><span class="gensmall">{L_CAT_SPECIAL_EXPLAIN}</span></td>
	<td class="row2"><select name="special">
		{S_CAT_SPECIAL}
	</select></td>
</tr>
<tr>
	<td class="row1"><b>{L_CAT_OPEN}:</b><br /><span class="gensmall">{L_CAT_OPEN_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="cat_open" value="1" checked="checked" /> {L_YES}&nbsp;&nbsp;<input type="radio" name="cat_open" value="0" /> {L_NO}</td>
</tr>
<tr>
	<th colspan="2" class="thSides">{L_POPUP_WINDOW}</th>
</tr>
<tr>
	<td colspan="2" valign="top" class="row2"><span class="gensmall">{L_POPUP_DESCRIPTION}</span></td>
</tr>
<tr>
	<td class="row1"><b>{L_POPUP_GROUP_LIST}:</b></td>
	<td class="row2"><select name="popup_group_list">
		<option value="0">{L_POPUP_GROUP}</option>
		<option value="1">{L_POPUP_LIST}</option>
	</select></td>
</tr>
<tr>
	<td class="row1"><b>{L_POPUP_GROUP_COLS}:</b></td>
	<td class="row2"><input type="text" name="popup_group_cols" value="8" size="5" maxlength="2" class="post" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_POPUP_LIST_COLS}:</b></td>
	<td class="row2"><input type="text" name="popup_list_cols" value="1" size="5" maxlength="2" class="post" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_PER_PAGE}:</b><br /><span class="gensmall">{L_PER_PAGE_LIMIT}</span></td>
	<td class="row2"><input type="text" name="popup_per_page" value="0" size="5" maxlength="3" class="post" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_POPUP_SIZE}:</b><br /><span class="gensmall">{L_POPUP_SIZE_ATTRIBS}</span></td>
	<td class="row2"><input type="text" name="popup_width" value="410" size="5" maxlength="3" class="post" /> x <input type="text" name="popup_height" value="300" size="5" maxlength="3" class="post" /></td>
</tr>
<tr>
	<td colspan="2" align="center" class="catBottom">{S_HIDDEN_FIELDS}<input type="submit" value="{L_SUBMIT}" name="cat_add_submit" class="mainoption" />&nbsp;&nbsp;<input type="reset" name="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</form></table>
<br />
<div align="center" class="copyright">Smiley Categories 2.0.4</a> &copy; 2004, {COPYRIGHT_YEAR} <a href="http://mods.afkamm.co.uk" target="_blank" class="copyright">Afkamm</a></div>
