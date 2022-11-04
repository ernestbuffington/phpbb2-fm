{ATTACH_MENU}{POST_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_ATTACHMENT_SETTINGS}</h1>

<p>{L_MANAGE_EXPLAIN}</p>

{ERROR_BOX}

<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline"><form action="{S_ATTACH_ACTION}" method="post">
<tr>
	<th class="thHead" colspan="2">{L_ATTACHMENT_SETTINGS}</th>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_DISABLE_MOD}:</b></td>
	<td class="row2"><input type="radio" name="disable_mod" value="0" {DISABLE_MOD_NO} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="disable_mod" value="1" {DISABLE_MOD_YES} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_PM_ATTACH}:</b></td>
	<td class="row2"><input type="radio" name="allow_pm_attach" value="1" {PM_ATTACH_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_pm_attach" value="0" {PM_ATTACH_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_ATTACHMENT_TOPIC_REVIEW}:</b></td>
	<td class="row2"><input type="radio" name="attachment_topic_review" value="1" {TOPIC_REVIEW_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="attachment_topic_review" value="0" {TOPIC_REVIEW_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_UPLOAD_DIR}:</b><br /><span class="gensmall">{L_UPLOAD_DIR_EXPLAIN}</span></td>
	<td class="row2"><input type="text" size="25" maxlength="100" name="upload_dir" class="post" value="{UPLOAD_DIR}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_DISPLAY_ORDER}:</b><br /><span class="gensmall">{L_DISPLAY_ORDER_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="display_order" value="0" {DISPLAY_ORDER_DESC} /> {L_DESC}&nbsp;&nbsp;<input type="radio" name="display_order" value="1" {DISPLAY_ORDER_ASC} /> {L_ASC}</td>
</tr>
<tr>
	<td class="row1"><b>{L_ATTACH_QUOTA}:</b><br /><span class="gensmall">{L_ATTACH_QUOTA_EXPLAIN}</span></td>
	<td class="row2"><input type="text" size="5" maxlength="15" name="attachment_quota" class="post" value="{ATTACHMENT_QUOTA}" /> {S_FILESIZE_QUOTA}</td>
</tr>
<tr>
	<td class="row1"><b>{L_MAX_FILESIZE}:</b><br /><span class="gensmall">{L_MAX_FILESIZE_EXPLAIN}</span></td>
	<td class="row2"><input type="text" size="5" maxlength="15" name="max_filesize" class="post" value="{MAX_FILESIZE}" /> {S_FILESIZE}</td>
</tr>
<tr>
	<td class="row1"><b>{L_MAX_FILESIZE_PM}:</b><br /><span class="gensmall">{L_MAX_FILESIZE_PM_EXPLAIN}</span></td>
	<td class="row2"><input type="text" size="5" maxlength="15" name="max_filesize_pm" class="post" value="{MAX_FILESIZE_PM}" /> {S_FILESIZE_PM}</td>
</tr>
<tr>
	<td class="row1"><b>{L_DEFAULT_QUOTA_LIMIT}:</b><br /><span class="gensmall">{L_DEFAULT_QUOTA_LIMIT_EXPLAIN}</span></td>
	<td class="row2"><table width="100%" align="center" cellpadding="2" cellspacing="1">
	<tr>
		<td nowrap="nowrap">{S_DEFAULT_UPLOAD_LIMIT}</td>
		<td nowrap="nowrap" width="90%"><span class="gensmall">&nbsp;{L_UPLOAD_QUOTA}&nbsp;</span></td>
	</tr>
	<tr>
		<td nowrap="nowrap">{S_DEFAULT_PM_LIMIT}</td>
		<td nowrap="nowrap"><span class="gensmall">&nbsp;{L_PM_QUOTA}&nbsp;</span></td>
	</tr>
	</table></td>
</tr>
<tr>
	<td class="row1"><b>{L_MAX_ATTACHMENTS}:</b></td>
	<td class="row2"><input type="text" size="5" maxlength="3" name="max_attachments" class="post" value="{MAX_ATTACHMENTS}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_MAX_ATTACHMENTS_PM}:</b></td>
	<td class="row2"><input type="text" size="5" maxlength="3" name="max_attachments_pm" class="post" value="{MAX_ATTACHMENTS_PM}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_SHOW_APCP}:</b><br /><span class="gensmall">{L_SHOW_APCP_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="show_apcp" value="1" {SHOW_APCP_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="show_apcp" value="0" {SHOW_APCP_NO} /> {L_NO}</td>
</tr>
<!-- BEGIN switch_ftp -->
<tr>
	<td class="row1"><b>{L_FTP_UPLOAD}:</b><br /><span class="gensmall">{L_FTP_UPLOAD_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="allow_ftp_upload" value="1" {FTP_UPLOAD_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_ftp_upload" value="0" {FTP_UPLOAD_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_ATTACHMENT_FTP_SERVER}:</b><br /><span class="gensmall">{L_ATTACHMENT_FTP_SERVER_EXPLAIN}</span></td>
	<td class="row2"><input type="text" size="20" maxlength="100" name="ftp_server" class="post" value="{FTP_SERVER}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_ATTACHMENT_FTP_PATH}:</b><br /><span class="gensmall">{L_ATTACHMENT_FTP_PATH_EXPLAIN}</span></td>
	<td class="row2"><input type="text" size="20" maxlength="100" name="ftp_path" class="post" value="{FTP_PATH}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_DOWNLOAD_PATH}:</b><br /><span class="gensmall">{L_DOWNLOAD_PATH_EXPLAIN}</span></td>
	<td class="row2"><input type="text" size="20" maxlength="100" name="download_path" class="post" value="{DOWNLOAD_PATH}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_FTP_PASSIVE_MODE}:</b><br /><span class="gensmall">{L_FTP_PASSIVE_MODE_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="ftp_pasv_mode" value="1" {FTP_PASV_MODE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="ftp_pasv_mode" value="0" {FTP_PASV_MODE_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_ATTACHMENT_FTP_USER}:</b></td>
	<td class="row2"><input type="text" size="20" maxlength="100" name="ftp_user" class="post" value="{FTP_USER}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_ATTACHMENT_FTP_PASS}:</b></td>
	<td class="row2"><input type="password" size="10" maxlength="20" name="ftp_pass" class="post" value="{FTP_PASS}" /></td>
</tr>
<!-- END switch_ftp -->
<!-- BEGIN switch_no_ftp -->
<input type="hidden" name="allow_ftp_upload" value="0" />
<tr>
	<td class="row1" colspan="2" align="center"><span class="gen">{L_NO_FTP_EXTENSIONS}</span></td>
</tr>
<!-- END switch_no_ftp -->
<tr>
	<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" />&nbsp;&nbsp;<input type="submit" name="settings" value="{L_TEST_SETTINGS}" class="liteoption" /></td>
</tr>
</form></table>
