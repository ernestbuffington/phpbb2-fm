{ATTACH_MENU}{POST_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_MANAGE_QUOTAS_TITLE}</h1>

<p>{L_MANAGE_QUOTAS_EXPLAIN}</p>

<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline"><form method="post" action="{S_ATTACH_ACTION}">
<tr> 
	<td class="catHead" colspan="5" align="center"><span class="cattitle">{L_MANAGE_QUOTAS_TITLE}</span></td>
</tr>
<tr>
	<th class="thCornerL">&nbsp;{L_DESCRIPTION}&nbsp;</th>
	<th class="thTop">&nbsp;{L_SIZE}&nbsp;</th>
	<th class="thCornerR">&nbsp;{L_ADD_NEW}&nbsp;</th>
</tr>
<tr>
	<td class="row1" align="center" valign="middle"><input type="text" size="20" maxlength="25" name="quota_description" class="post" value=""/></td>
	<td class="row2" align="center" valign="middle"><input type="text" size="8" maxlength="15" name="add_max_filesize" class="post" value="{MAX_FILESIZE}" /> {S_FILESIZE}</td>
	<td class="row1" align="center" valign="middle"><input type="checkbox" name="add_quota_check" /></td>
</tr>
<tr>
	<td class="catBottom" colspan="5" align="right">{S_HIDDEN_FIELDS}<input type="submit" name="submit" class="mainoption" value="{L_SUBMIT}" /></td>
</tr>
<tr>
	<th class="thCornerL">&nbsp;{L_DESCRIPTION}&nbsp;</th>
	<th class="thTop">&nbsp;{L_SIZE}&nbsp;</th>
	<th class="thCornerR">&nbsp;{L_DELETE}&nbsp;</th>
</tr>
<!-- BEGIN limit_row -->
<tr> 
	<td class="row1" align="center" valign="middle">
	<input type="hidden" name="quota_change_list[]" value="{limit_row.QUOTA_ID}" />
      	<table width="100%" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<td class="row1" align="center" valign="middle" width="10%" wrap="nowrap"><b><span class="gensmall"><a href="{limit_row.U_VIEW}" class="gensmall">{L_VIEW}</a></span></b></td>
		<td class="row1" align="left" valign="middle"><input type="text" size="20" maxlength="25" name="quota_desc_list[]" class="post" value="{limit_row.QUOTA_NAME}" /></td>
	</tr>
	</table>
	</td>	
	<td class="row2" align="center" valign="middle"><input type="text" size="8" maxlength="15" name="max_filesize_list[]" class="post" value="{limit_row.MAX_FILESIZE}" /> {limit_row.S_FILESIZE}</td>
	<td class="row1" align="center" valign="middle"><input type="checkbox" name="quota_id_list[]" value="{limit_row.QUOTA_ID}" /></td>
</tr>
<!-- END limit_row -->
<tr>
	<td class="catBottom" colspan="5" align="right"><input type="submit" name="submit" class="mainoption" value="{L_SUBMIT}" /></td>
</tr>
</table>
</form>
<!-- {QUOTA_LIMIT_SETTINGS} -->

<!-- BEGIN switch_quota_limit_desc -->
<center><h1>{L_QUOTA_LIMIT_DESC}</h1></center>

<table width="100%" cellspacing="0" cellpadding="0" align="center">
<tr>
	<td align="left" width="49%">
	<table width="100%" class="forumline" cellspacing="1" cellpadding="4" align="left">
	<tr>
		<th class="thHead">{L_ASSIGNED_USERS} - {L_UPLOAD_QUOTA}</th>
	</tr>
	<tr>
		<td class="row1" align="center">
		<select style="width:99%" name="entries[]" multiple="multiple" size="5">
<!-- END switch_quota_limit_desc -->
		<!-- BEGIN users_upload_row -->
		<option value="{users_upload_row.USER_ID}">{users_upload_row.USERNAME}</option>
		<!-- END users_upload_row -->
<!-- BEGIN switch_quota_limit_desc -->
		</select>
		</td>
	</tr>
	</table>
	</td>
	<td width="2%">&nbsp;&nbsp;&nbsp;</td>
	<td align="right" width="49%">
	<table width="100%" class="forumline" cellspacing="1" cellpadding="4" align="right">
	<tr>
		<th class="thHead">{L_ASSIGNED_GROUPS} - {L_UPLOAD_QUOTA}</th>
	</tr>
	<tr>
		<td class="row1" align="center">
		<select style="width:99%" name="entries[]" multiple="multiple" size="5">
<!-- END switch_quota_limit_desc -->
		<!-- BEGIN groups_upload_row -->
		<option value="{groups_upload_row.GROUP_ID}">{groups_upload_row.GROUPNAME}</option>
		<!-- END groups_upload_row -->
<!-- BEGIN switch_quota_limit_desc -->
		</select>
		</td>
		</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td colspan="3">&nbsp;&nbsp;&nbsp;</td>
	</tr>
	<tr>
		<td align="left" width="49%">
		<table width="100%" class="forumline" cellspacing="1" cellpadding="4" align="left">
		<tr>
			<th class="thHead">{L_ASSIGNED_USERS} - {L_PM_QUOTA}</th>
		</tr>
		<tr>
			<td class="row1" align="center">
			<select style="width:99%" name="entries[]" multiple="multiple" size="5">
<!-- END switch_quota_limit_desc -->
			<!-- BEGIN users_pm_row -->
			<option value="{users_pm_row.USER_ID}">{users_pm_row.USERNAME}</option>
			<!-- END users_pm_row -->
<!-- BEGIN switch_quota_limit_desc -->
			</select>
			</td>
			</tr>
			</table>
		</td>
		<td width="2%">&nbsp;&nbsp;&nbsp;</td>
		<td align="right" width="49%">
		<table width="100%" class="forumline" cellspacing="1" cellpadding="4" align="right">
		<tr>
			<th class="thHead">{L_ASSIGNED_GROUPS} - {L_PM_QUOTA}</th>
		</tr>
		<tr>
			<td class="row1" align="center">
			<select style="width:99%" name="entries[]" multiple="multiple" size="5">
<!-- END switch_quota_limit_desc -->
			<!-- BEGIN groups_pm_row -->
			<option value="{groups_pm_row.GROUP_ID}">{groups_pm_row.GROUPNAME}</option>
			<!-- END groups_pm_row -->
<!-- BEGIN switch_quota_limit_desc -->
			</select>
			</td>
			</tr>
			</table>
		</td>
	</tr>
</table>
<!-- END switch_quota_limit_desc -->
