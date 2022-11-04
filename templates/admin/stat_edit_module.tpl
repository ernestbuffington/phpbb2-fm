{STATS_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_EDIT_MODULE}</h1>

<p>{L_EDIT_MODULE_EXPLAIN}</p>

{MESSAGE}

<table class="forumline" cellspacing="1" cellpadding="4" align="center" width="100%"><form action="{S_ACTION}" method="post">
<tr> 
	<th class="thHead" colspan="2">{L_EDIT_MODULE}</th>
</tr>
<tr> 
	<td class="row1" colspan="2" align="center"><span class="gen">-&gt;&nbsp;<a href="{U_PREVIEW_MODULE}" target="_blank" class="gen">{L_PREVIEW_MODULE}</a>&nbsp;&lt;-</span></td>
</tr>
<tr>
	<th class="thHead" colspan="2">{L_CONFIGURATION}</th>
</tr>
<tr>
	<td class="row1"><b>{L_UPDATE_TIME}:</b><br /><span class="gensmall">{L_UPDATE_TIME_EXPLAIN}</span></td>
	<td class="row2"><input type="text" size="10" maxlength="10" name="update_time" value="{UPDATE_TIME}" class="post" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_CLEAR_MODULE_CACHE}:</b><br /><span class="gensmall">{L_CLEAR_MODULE_CACHE_EXPLAIN}</span></td>
	<td class="row2"><input type="checkbox" name="clear_module_cache" /></td>
</tr>
<!-- BEGIN module_admin_fields -->
<tr>
	<td class="row1"><b>{module_admin_fields.L_TITLE}:</b><br /><span class="gensmall">{module_admin_fields.L_EXPLAIN}</span></td>
	<td class="row2">{module_admin_fields.S_OPTION_FIELD}</td>
</tr>
<!-- END module_admin_fields -->
<tr>
	<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</table>
<br />

<table class="forumline" cellspacing="1" cellpadding="4" align="center" width="100%">
<tr> 
	<th class="thHead" colspan="2">{L_PERMISSIONS}</th>
</tr>
<tr> 
	<td class="row2" colspan="2"><span class="gensmall">{L_PERMISSIONS_TITLE}</span></td>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_PERM_ALL}:</b></td>
	<td class="row2"><input type="checkbox" name="perm_all" {PERM_ALL} /></td>
</tr>
<tr>
	<td class="row1"><b>{L_PERM_REG}:</b></td>
	<td class="row2"><input type="checkbox" name="perm_reg" {PERM_REG} /></td>
</tr>
<tr>
	<td class="row1"><b>{L_PERM_MOD}:</b></td>
	<td class="row2"><input type="checkbox" name="perm_mod" {PERM_MOD} /></td>
</tr>
<tr>
	<td class="row1"><b>{L_PERM_ADMIN}:</b></td>
	<td class="row2"><input type="checkbox" name="perm_admin" {PERM_ADMIN} /></td>
</tr>
<tr> 
	<th class="thHead" colspan="2">{L_GROUPS_TITLE}</th>
</tr>
<tr> 
	<td class="row1"><b>{L_ADDED_GROUPS}:</b></td>
	<td class="row2">{S_SELECTED_GROUPS}&nbsp;
	<!-- BEGIN switch_groups_selected -->
	<input type="submit" name="delete_group" value="{L_REMOVE}" class="liteoption" /></td>
	<!-- END switch_groups_selected -->
</tr>
<tr> 
	<td class="row1"><b>{L_GROUPS}:</b></td>
	<td class="row2">{S_GROUP_SELECT}&nbsp;
	<!-- BEGIN switch_groups_there -->
	<input type="submit" name="add_group" value="{L_ADD}" class="liteoption" /></td>
	<!-- END switch_groups_there -->
	</td>
</tr>
<tr>
	<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</form></table>
<br />

<table class="forumline" cellspacing="1" cellpadding="4" align="center" width="100%">
<tr> 
	<th class="thHead" colspan="2">{L_MODULE_INFORMATIONS}</th>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_MODULE_NAME}:</b></td>
	<td class="row2">{MODULE_NAME}</td>
</tr>
<tr>
	<td class="row1"><b>{L_MODULE_DESCRIPTION}:</b></td>
	<td class="row2">{MODULE_DESCRIPTION}</td>
</tr>
<tr>
	<td class="row1"><b>{L_MODULE_VERSION}:</b></td>
	<td class="row2">{MODULE_VERSION}</td>
</tr>
<tr>
	<td class="row1"><b>{L_MODULE_AUTHOR}:</b></td>
	<td class="row2">{MODULE_AUTHOR}</td>
</tr>
<tr>
	<td class="row1"><b>{L_AUTHOR_EMAIL}:</b></td>
	<td class="row2"><a href="mailto:{AUTHOR_EMAIL}" target="_blank">{AUTHOR_EMAIL}</a></td>
</tr>
<tr>
	<td class="row1"><b>{L_MODULE_URL}:</b></td>
	<td class="row2"><a href="{U_MODULE_URL}" target="_blank">{MODULE_URL}</a></td>
</tr>
<tr>
	<td class="row1"><b>{L_UPDATE_URL}:</b></td>
	<td class="row2"><a href="{U_UPDATE_URL}" target="_blank">{UPDATE_URL}</a></span></td>
</tr>
<tr>
	<td class="row1"><b>{L_MODULE_LANGUAGES}:</b></td>
	<td class="row2">{MODULE_LANGUAGES}</td>
</tr>
<tr>
	<td class="row1"><b>{L_MODULE_STATUS}:</b></td>
	<td class="row2">{MODULE_STATUS}</td>
</tr>
</table>
<br />

<table class="forumline" cellspacing="1" cellpadding="4" align="center" width="100%"><form action="{S_ACTION_UPDATE}" method="post" enctype="multipart/form-data">
<tr> 
	<th class="thHead" colspan="2">{L_UPDATE_MODULE}</th>
</tr>
<!-- BEGIN switch_pak_select -->
<tr>
	<td class="row1" width="50%"><b>{L_SELECT_MODULE}:</b></td>
	<td class="row2">{S_SELECT_MODULE}</td>
</tr>
<!-- END switch_pak_select -->
<tr>
	<td class="row1" width="50%"><b>{L_UPLOAD_MODULE}:</b></td>
	<td class="row2"><input type="file" name="package" size="20" value="" class="post" /></td>
</tr>
<tr>
	<td class="catBottom" colspan="2" align="center">{S_SELECT_HIDDEN_FIELDS}{S_UPLOAD_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_UPDATE}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</form></table>
