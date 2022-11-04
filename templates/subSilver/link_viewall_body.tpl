<table width="100%" cellpadding="2" cellspacing="2"><form action="{S_VIEWALL_ACTION}" method="post"><input type="hidden" name="action" value="viewall"><input type="hidden" name="start" value="{START}">
<tr> 
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> <a href="{U_LINK}" class="nav">{LINKS}</a> -> {L_VIEWALL}</td>
	<!-- BEGIN FILELIST -->
	<td align="right" colspan="2" class="genmed">{L_SELECT_SORT_METHOD}: &nbsp;<select name="sort_method">
		<option {SORT_NAME} value="link_name">{L_NAME}</option>
		<option {SORT_TIME} value="link_time">{L_DATE}</option>
		<option {SORT_LONGDESC} value="link_longdesc">{L_LINK_SITE_DESC}</option>
		<option {SORT_DOWNLOADS} value="link_hits">{L_DOWNLOADS}</option>
	</select> &nbsp;{L_ORDER}: &nbsp;<select name="sort_order">
		<option {SORT_ASC} value="ASC">{L_ASC}</option>
		<option {SORT_DESC} value="DESC">{L_DESC}</option>
	</select> &nbsp;<input type="submit" name="submit" value="{L_SORT}" class="liteoption" /></td>
	<!-- END FILELIST -->
</tr>
</form></table>

<!-- BEGIN FILELIST -->
<!-- BEGIN no_split_links -->
<table width="100%" cellpadding="4" cellspacing="1" class="forumline">
<tr> 
	<th colspan="3" class="thHead">{L_FILE}</th>
</tr>
<!-- END no_split_links -->
<!-- BEGIN file_rows -->
<!-- BEGIN split_links -->
<table width="100%" cellpadding="4" cellspacing="1" class="forumline">
<!-- END split_links -->
<tr>
	<td rowspan="2" class="{FILELIST.file_rows.COLOR}" align="center">&nbsp;{FILELIST.file_rows.LINK_LOGO}<br /><span class="gensmall">{FILELIST.file_rows.RECOM_LINK}</span></td>
	<td class="{FILELIST.file_rows.COLOR}"><table width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td class="postdetails"><img src="{FILELIST.file_rows.POST_IMAGE}" alt="{FILELIST.file_rows.POST_IMAGE_ALT}" title="{FILELIST.file_rows.POST_IMAGE_ALT}" /><b>{L_POSTED}:</b> {FILELIST.file_rows.DATE} <b>{L_SUBMITED_BY}:</b> {FILELIST.file_rows.POSTER} <b>{L_DOWNLOADS}:</b> {FILELIST.file_rows.FILE_DLS}
		<!-- BEGIN LINK_VOTE -->
		<b>{FILELIST.file_rows.L_RATING}:</b> {FILELIST.file_rows.RATING} <!-- ({FILELIST.file_rows.FILE_VOTES} {L_VOTES}) -->
		<!-- END LINK_VOTE -->
		<!-- BEGIN LINK_COMMENT -->
		<b>{FILELIST.file_rows.L_COMMENTS}:</b> {FILELIST.file_rows.FILE_COMMENTS}
		<!-- END LINK_COMMENT -->
		<b>{L_CATEGORY}:</b> <a href="{FILELIST.file_rows.U_CAT}" class="postdetails">{FILELIST.file_rows.CAT_NAME}</a>
		<!-- BEGIN custom_field -->
		<b>{FILELIST.file_rows.custom_field.CUSTOM_NAME}:</b> {FILELIST.file_rows.custom_field.DATA}
		<!-- END custom_field -->
		</td>
		<td align="right" class="gensmall">
		<!-- BEGIN AUTH_EDIT -->  
		<a href="{FILELIST.file_rows.U_EDIT}"><img src="{EDIT_IMG}" alt="{L_EDIT}" title="{L_EDIT}" /></a>
		<!-- END AUTH_EDIT -->
		<!-- BEGIN AUTH_DELETE -->  
		<a href="{FILELIST.file_rows.U_DELETE}"><img src="{DELETE_IMG}" alt="{L_DELETE}" title="{L_DELETE}" /></a>
		<!-- END AUTH_DELETE -->
		</td>
	</tr>
	</table></td>
</tr>
<tr>
	<td colspan="2" width="100%" class="{FILELIST.file_rows.COLOR}">
	<a href="{FILELIST.file_rows.U_FILE}" class="topictitle" target="_blank">{FILELIST.file_rows.FILE_NAME}</a>&nbsp;
	<!-- BEGIN IS_NEW_FILE -->
	<img src="{FILELIST.file_rows.FILE_NEW_IMAGE}" alt="{L_NEW_FILE}" title="{L_NEW_FILE}" />
	<!-- END IS_NEW_FILE -->
	<br /><span class="genmed">{FILELIST.file_rows.FILE_DESC}</span></td>
</tr>
<tr> 
	<td class="spaceRow" colspan="3" height="1"><img src="./images/spacer.gif" alt="" title="" width="1" height="1" /></td>
</tr>
<!-- BEGIN split_links -->
</table>
<br />
<!-- END split_links -->
<!-- END file_rows -->
<!-- BEGIN no_split_links -->
<tr>
	<td class="catBottom" colspan="3">&nbsp;</td>
</tr>
</table>
<!-- END no_split_links -->

<table width="100%" cellspacing="2" align="center" cellpadding="2">
<tr> 
	<td valign="top" class="nav">{PAGE_NUMBER}</td>
	<td align="right" valign="top" nowrap="nowrap" class="nav">{PAGINATION}</td>
</tr>
</table>
<!-- END FILELIST -->

<!-- BEGIN NO_FILE -->
<table class="forumline" width="100%" cellspacing="1" cellpadding="4">
<tr>
	<th class="thHead">{L_NO_FILES}</th>
</tr>
<tr> 
	<td class="row1" align="center" height="30"><span class="genmed">{L_NO_FILES_CAT}</span></td>
</tr>
</table> 
<!-- END NO_FILE -->