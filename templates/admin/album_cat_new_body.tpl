{ALBUM_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_ALBUM_CAT_TITLE}</h1>

<p>{L_ALBUM_CAT_EXPLAIN}</p>

<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline"><form action="{S_ALBUM_ACTION}" method="post">
	<tr>
		<th class="thHead" colspan="2">{L_PANEL_TITLE}</th>
	</tr>
	<tr>
		<td class="row1" width="50%"><b>{L_CAT_TITLE}:</b></td>
		<td class="row2"><input name="cat_title" type="text" class="post" size="35" value="{S_CAT_TITLE}" /></td>
	</tr>
	<tr>
		<td class="row1"><b>{L_CAT_DESC}:</b></td>
		<td class="row2"><textarea name="cat_desc" class="post" cols="50" rows="5">{S_CAT_DESC}</textarea></td>
	</tr>
	<tr>
		<th class="thSides" colspan="2">{L_CAT_PERMISSIONS}</th>
	</tr>
	<tr>
		<td class="row1"><b>{L_VIEW_LEVEL}:</b></td>
		<td class="row2"><select name="cat_view_level"><option {VIEW_GUEST} value="{S_GUEST}">{L_GUEST}</option><option {VIEW_REG} value="{S_USER}">{L_REG}</option><option {VIEW_PRIVATE} value="{S_PRIVATE}">{L_PRIVATE}</option><option {VIEW_MOD} value="{S_MOD}">{L_MOD}</option><option {VIEW_ADMIN} value="{S_ADMIN}">{L_ADMIN}</option></select></td>
	</tr>
	<tr>
		<td class="row1"><b>{L_UPLOAD_LEVEL}:</b></td>
		<td class="row2"><select name="cat_upload_level"><option {UPLOAD_GUEST} value="{S_GUEST}">{L_GUEST}</option><option {UPLOAD_REG} value="{S_USER}">{L_REG}</option><option {UPLOAD_PRIVATE} value="{S_PRIVATE}">{L_PRIVATE}</option><option {UPLOAD_MOD} value="{S_MOD}">{L_MOD}</option><option {UPLOAD_ADMIN} value="{S_ADMIN}">{L_ADMIN}</option></select></td>
	</tr>
	<tr>
		<td class="row1"><b>{L_RATE_LEVEL}:</b></td>
		<td class="row2"><select name="cat_rate_level"><option {RATE_GUEST} value="{S_GUEST}">{L_GUEST}</option><option {RATE_REG} value="{S_USER}">{L_REG}</option><option {RATE_PRIVATE} value="{S_PRIVATE}">{L_PRIVATE}</option><option {RATE_MOD} value="{S_MOD}">{L_MOD}</option><option {RATE_ADMIN} value="{S_ADMIN}">{L_ADMIN}</option></select></td>
	</tr>
	<tr>
		<td class="row1"><b>{L_COMMENT_LEVEL}:</b></td>
		<td class="row2"><span class="gen"><select name="cat_comment_level"><option {COMMENT_GUEST} value="{S_GUEST}">{L_GUEST}</option><option {COMMENT_REG} value="{S_USER}">{L_REG}</option><option {COMMENT_PRIVATE} value="{S_PRIVATE}">{L_PRIVATE}</option><option {COMMENT_MOD} value="{S_MOD}">{L_MOD}</option><option {COMMENT_ADMIN} value="{S_ADMIN}">{L_ADMIN}</option></select></span></td>
	</tr>
	<tr>
		<td class="row1"><b>{L_EDIT_LEVEL}:</b></td>
		<td class="row2"><select name="cat_edit_level"><option {EDIT_REG} value="{S_USER}">{L_REG}</option><option {EDIT_PRIVATE} value="{S_PRIVATE}">{L_PRIVATE}</option><option {EDIT_MOD} value="{S_MOD}">{L_MOD}</option><option {EDIT_ADMIN} value="{S_ADMIN}">{L_ADMIN}</option></select></td>
	</tr>
	<tr>
		<td class="row1"><b>{L_DELETE_LEVEL}:</b></td>
		<td class="row2"><select name="cat_delete_level"><option {DELETE_REG} value="{S_USER}">{L_REG}</option><option {DELETE_PRIVATE} value="{S_PRIVATE}">{L_PRIVATE}</option><option {DELETE_MOD} value="{S_MOD}">{L_MOD}</option><option {DELETE_ADMIN} value="{S_ADMIN}">{L_ADMIN}</option></select></td>
	</tr>
	<tr>
		<td class="row1"><b>{L_PICS_APPROVAL}:</b></td>
		<td class="row2"><select name="cat_approval"><option {APPROVAL_DISABLED} value="{S_USER}">{L_DISABLED}</option><option {APPROVAL_MOD} value="{S_MOD}">{L_MOD}</option><option {APPROVAL_ADMIN} value="{S_ADMIN}">{L_ADMIN}</option></select></td>
	</tr>
	<tr>
		<td class="catBottom" align="center" colspan="2"><input type="hidden" value="{S_MODE}" name="mode" /><input name="submit" type="submit" value="{L_PANEL_TITLE}" class="liteoption" /></td>
	</tr>
</form></table>
<br />
<div align="center" class="copyright">Photo Album {ALBUM_VERSION} &copy; 2002, {COPYRIGHT_YEAR} <a href="http://smartor.is-root.com" class="copyright" target="_blank">Smartor</a></div>
