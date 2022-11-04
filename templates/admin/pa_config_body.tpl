{DOWNLOAD_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_PAGE_TITLE}</h1>

<p>{L_PAGE_EXPLAIN}</p>

<table width="100%" align="center"><form action="{S_CONFIG_ACTION}" method="post">
<tr>
	<td align="right">{CONFIG_SELECT} <input type="submit" name="config" value="{L_GO}" class="liteoption" /></td>
</tr>
</form></table>
<table width="100%" cellpadding="4" cellspacing="1" class="forumline" align="center"><form action="{S_CONFIG_ACTION}" method="post">

<!-- BEGIN switch_config -->
<tr>
	<th colspan="2" class="thHead">{L_PAGE_TITLE}</th>
</tr> 
<tr>
	<td width="50%" class="row1"><b>{L_DBNAME}:</b></td>
	<td class="row2"><input type="text" class="post" size="35" name="settings_dbname" value="{SETTINGS_DBNAME}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_NFDAYS}:</b><br /><span class="gensmall">{L_NFDAYSINFO}</span></td>
	<td class="row2"><input type="text" class="post" size="5" maxlength="5" name="settings_newdays" value="{SETTINGS_NEWDAYS}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_FILE_IN_PAGE}:</b></td>
	<td class="row2"><input type="text" class="post" size="5" maxlength="5" name="settings_file_page" value="{SETTINGS_FILE_PAGE}" /></td>
</tr>  
<tr>
	<td class="row1"><b>{L_TOPNUM}:</b><br /><span class="gensmall">{L_TOPNUMINFO}</span></td>
	<td class="row2"><input type="text" class="post" size="5" maxlength="5" name="settings_topnumber" value="{SETTINGS_TOPNUMBER}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_SHOW_VIEWALL}:</b><br /><span class="gensmall">{L_VIEWALL_INFO}</span></td>
  	<td class="row2"><input type="radio" name="settings_viewall" value="1" {S_VIEW_ALL_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="settings_viewall" value="0" {S_VIEW_ALL_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_DISABLE}:</b><br /><span class="gensmall">{L_DISABLE_INFO}</span></td>
  	<td class="row2"><input type="radio" name="settings_disable" value="1" {S_DISABLE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="settings_disable" value="0" {S_DISABLE_NO} /> {L_NO}</td></tr>
<tr>
	<td class="row1"><b>{L_HOTLINK}:</b><br /><span class="gensmall">{L_HOTLINK_INFO}</span></td>
  	<td class="row2"><input type="radio" name="hotlink_prevent" value="1" {S_HOTLINK_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="hotlink_prevent" value="0" {S_HOTLINK_NO} /> {L_NO}</td>
</tr> 
<tr>
	<td class="row1"><b>{L_HOTLINK_ALLOWED}:</b><br /><span class="gensmall">{L_HOTLINK_ALLOWED_INFO}</span></td>
	<td class="row2"><input type="text" class="post" size="35" name="hotlink_allowed" value="{HOTLINK_ALLOWED}" /></td>
</tr>  
<tr>
	<td class="row1"><b>{L_DEFAULT_SORT_METHOD}:</b></td>
	<td class="row2"><select name="sort_method">
		<option {SORT_NAME} value="file_name">{L_NAME}</option>
		<option {SORT_TIME} value="file_time">{L_DATE}</option>
		<option {SORT_RATING} value="rating">{L_RATING}</option>
		<option {SORT_DOWNLOADS} value="file_dls">{L_DOWNLOADS}</option>
		<option {SORT_UPDATE_TIME} value="file_update_time">{L_UPDATE_TIME}</option>
	</select></td>
</tr>
<tr>
	<td class="row1"><b>{L_DEFAULT_SORT_ORDER}:</b></td>
  	<td class="row2"><input type="radio" name="sort_order" value="ASC" {SORT_ASC} /> {L_ASC}&nbsp;&nbsp;<input type="radio" name="sort_order" value="DESC" {SORT_DESC} /> {L_DESC}</td>
</tr>
<tr>
	<td class="row1"><b>{L_PHP_TPL}:</b><br /><span class="gensmall">{L_PHP_TPL_INFO}</span></td>
  	<td class="row2"><input type="radio" name="settings_tpl_php" value="1" {S_PHP_TPL_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="settings_tpl_php" value="0" {S_PHP_TPL_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_MAX_FILE_SIZE}:</b><br /><span class="gensmall">{L_MAX_FILE_SIZE_INFO}</span></td>
	<td class="row2"><input type="text" class="post" size="8" maxlength="15" name="max_file_size" value="{MAX_FILE_SIZE}" /> {S_FILESIZE}</td>
</tr>
<tr>
	<td class="row1"><b>{L_UPLOAD_DIR}:</b><br /><span class="gensmall">{L_UPLOAD_DIR_EXPLAIN}</span></td>
	<td class="row2"><input type="text" class="post" size="25" maxlength="100" name="upload_dir" value="{UPLOAD_DIR}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_SCREENSHOT_DIR}:</b><br /><span class="gensmall">{L_SCREENSHOT_DIR_EXPLAIN}</span></td>
	<td class="row2"><input type="text" class="post" size="25" maxlength="100" name="screenshots_dir" value="{SCREENSHOT_DIR}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_FORBIDDEN_EXTENSIONS}:</b><br /><span class="gensmall">{L_FORBIDDEN_EXTENSIONS_EXPLAIN}</span></td>
	<td class="row2"><input type="text" class="post" size="35" maxlength="100" name="forbidden_extensions" value="{FORBIDDEN_EXTENSIONS}" /></td>
</tr>
<!-- END switch_config -->
<!-- BEGIN switch_perms -->
<tr>
	<th colspan="2" class="thHead">{L_PERMISSION_SETTINGS}</th>
</tr>
<tr>
	<td width="50%" class="row1"><b>{L_ATUH_SEARCH}:</b><br /><span class="gensmall">{L_ATUH_SEARCH_INFO}</span></td>
	<td class="row2">{S_ATUH_SEARCH}</td>
</tr>
<tr>
	<td class="row1"><b>{L_ATUH_STATS}:</b><br /><span class="gensmall">{L_ATUH_STATS_INFO}</span></td>
	<td class="row2">{S_ATUH_STATS}</td>
</tr>
<tr>
	<td class="row1"><b>{L_ATUH_TOPLIST}:</b><br /><span class="gensmall">{L_ATUH_TOPLIST_INFO}</span></td>
	<td class="row2">{S_ATUH_TOPLIST}</td>
</tr>
<tr>
	<td class="row1"><b>{L_ATUH_VIEWALL}:</b><br /><span class="gensmall">{L_ATUH_VIEWALL_INFO}</span></td>
	<td class="row2">{S_ATUH_VIEWALL}</td>
</tr>
<!-- END switch_perms -->

<!-- BEGIN switch_comments -->
<tr>
	<th colspan="2" class="thHead">{L_COMMENT_SETTINGS}</th>
</tr>
<tr>
	<td width="50%" class="row1"><b>{L_ALLOW_HTML}:</b></td>
  	<td class="row2"><input type="radio" name="allow_html" value="1" {S_ALLOW_HTML_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_html" value="0" {S_ALLOW_HTML_NO} /> {L_NO}</td>
</tr> 
<tr>
	<td class="row1"><b>{L_ALLOW_BBCODE}:</b></td>
  	<td class="row2"><input type="radio" name="allow_bbcode" value="1" {S_ALLOW_BBCODE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_bbcode" value="0" {S_ALLOW_BBCODE_NO} /> {L_NO}</td>
</tr> 
<tr>
	<td class="row1"><b>{L_ALLOW_SMILIES}:</b></td>
  	<td class="row2"><input type="radio" name="allow_smilies" value="1" {S_ALLOW_SMILIES_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_smilies" value="0" {S_ALLOW_SMILIES_NO} /> {L_NO}</td>
</tr> 
<tr>
	<td class="row1"><b>{L_ALLOW_LINKS}:</b></td>
  	<td class="row2"><input type="radio" name="allow_comment_links" value="1" {S_ALLOW_LINKS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_comment_links" value="0" {S_ALLOW_LINKS_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_LINKS_MESSAGE}:</b><br /><span class="gensmall">{L_LINKS_MESSAGE_INFO}</span></td>
	<td class="row2"><input type="text" class="post" size="35" maxlength="100" name="no_comment_link_message" value="{MESSAGE_LINK}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_ALLOW_IMAGE}:</b></td>
  	<td class="row2"><input type="radio" name="allow_comment_images" value="1" {S_ALLOW_IMAGES_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_comment_images" value="0" {S_ALLOW_IMAGES_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_IMAGE_MESSAGE}:</b><br /><span class="gensmall">{L_IMAGE_MESSAGE_INFO}</span></td>
	<td class="row2"><input type="text" class="post" size="35" maxlength="100" name="no_comment_image_message" value="{MESSAGE_IMAGE}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_MAX_CHAR}:</b><br /><span class="gensmall">{L_MAX_CHAR_INFO}</span></td>
	<td class="row2"><input type="text" class="post" size="5" maxlength="10" name="max_comment_chars" value="{MAX_CHAR}" /></td>
</tr>
<!-- END switch_comments -->

<!-- BEGIN switch_validate -->
<tr>
	<th colspan="2" class="thHead">{L_VALIDATION_SETTINGS}</th>
</tr>
<tr>
	<td width="50%" class="row1"><b>{L_NEED_VALIDATION}:</b></td>
  	<td class="row2"><input type="radio" name="need_validation" value="1" {S_NEED_VALIDATION_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="need_validation" value="0" {S_NEED_VALIDATION_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_VALIDATOR}:</b></td>
	<td class="row2"><select name="validator">
		<option {VALIDATOR_ADMIN} value="validator_admin">{L_VALIDATOR_ADMIN_OPTION}</option>
		<option {VALIDATOR_MOD} value="validator_mod">{L_VALIDATOR_MOD_OPTION}</option>
	</select></td>
</tr>
<tr>
	<td class="row1"><b class="invisible">{L_PM_NOTIFY}:</b></td>
  	<td class="row2"><input type="radio" name="pm_notify" value="1" disabled="disabled" {S_PM_NOTIFY_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="pm_notify" value="0" disabled="disabled" {S_PM_NOTIFY_NO} /> {L_NO}</td>
</tr>
<!-- END switch_validate -->

<tr>
	<td align="center" class="catBottom" colspan="2">{S_HIDDEN_FIELDS}<input class="mainoption" type="submit" value="{L_SUBMIT}" name="submit" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</form></table>	
<br />
<div align="center" class="copyright">paFileDB phpBB Integration 0.0.9d &amp; <a href="http://www.mx-system.com/" target="_blank" class="copyright">MX Addon 1.2</a> &copy; 2003, {COPYRIGHT_YEAR} Mohd & Haplo</a></div>
