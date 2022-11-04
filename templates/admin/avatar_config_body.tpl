{AVATAR_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_PAGE_TITLE}</h1>

<p>{L_PAGE_EXPLAIN} {L_AVATAR_SETTINGS_EXPLAIN}</p>

<table width="100%" align="center"><form action="{S_CONFIG_ACTION}" method="post">
<tr>
	<td align="right">{CONFIG_SELECT} <input type="submit" name="config" value="{L_GO}" class="liteoption" /></td>
</tr>
</form></table>
<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline"><form action="{S_CONFIG_ACTION}" method="post">
<!-- BEGIN switch_avatars -->
<tr>
	<th class="thHead" colspan="2">{L_PAGE_TITLE}</th>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_DEFAULT_AVATAR}:</b><br /><span class="gensmall">{L_DEFAULT_AVATAR_EXPLAIN}</span></td>
	<td class="row2"><table width="100%" cellpadding="2" cellspacing="0">
	<tr>
		<td colspan="2"><input type="radio" name="default_avatar_set" value="3" {DEFAULT_AVATAR_NONE} /> {L_DEFAULT_AVATAR_NONE}</td>
	</tr>
	<tr>
		<td nowrap="nowrap"><input type="radio" name="default_avatar_set" value="1" {DEFAULT_AVATAR_USERS} /> {L_DEFAULT_AVATAR_USERS}</td>
		<td width="100%"><input class="post" type="text" name="default_avatar_users_url" maxlength="255" size="30" value="{DEFAULT_AVATAR_USERS_URL}" />
	</tr>
	<tr>
		<td nowrap="nowrap"><input type="radio" name="default_avatar_set" value="0" {DEFAULT_AVATAR_GUESTS} /> {L_DEFAULT_AVATAR_GUESTS}</td>
		<td><input class="post" type="text" name="default_avatar_guests_url" maxlength="255" size="30" value="{DEFAULT_AVATAR_GUESTS_URL}" /></td>
	</tr>
	<tr>
		<td colspan="2"><input type="radio" name="default_avatar_set" value="2" {DEFAULT_AVATAR_BOTH} /> {L_DEFAULT_AVATAR_BOTH}</td>
	</tr>
	</table></td>
</tr>
<tr>
	<td class="row1"><b>{L_AVATAR_POSTS}:</b><br /><span class="gensmall">{L_AVATAR_POSTS_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" name="avatar_posts" size="5" maxlength="4" value="{AVATAR_POSTS}" /></td>
</tr>
<tr> 
	<td class="row1"><b>{L_DISABLE_AVATAR_APPROVE}:</b><br /><span class="gensmall">{L_DISABLE_AVATAR_APPROVE_EXPLAIN}</span></td> 
	<td class="row2"><input type="radio" name="disable_avatar_approve" value="0" {DISABLE_AVATAR_APPROVE_NO} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="disable_avatar_approve" value="1" {DISABLE_AVATAR_APPROVE_YES} /> {L_NO}</td> 
</tr> 
<tr>
	<td class="row1"><b>{L_AVATAR_REGISTER}:</b><br /><span class="gensmall">{L_AVATAR_REGISTER_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="enable_avatar_register" value="1" {AVATAR_REGISTER_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="enable_avatar_register" value="0" {AVATAR_REGISTER_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_ALLOW_REMOTE}:</b><br /><span class="gensmall">{L_ALLOW_REMOTE_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="allow_avatar_remote" value="1" {AVATARS_REMOTE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_avatar_remote" value="0" {AVATARS_REMOTE_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_ALLOW_STICKY}:</b><br /><span class="gensmall">{L_ALLOW_STICKY_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="allow_avatar_sticky" value="1" {AVATARS_STICKY_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_avatar_sticky" value="0" {AVATARS_STICKY_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_ALLOW_TOPLIST}:</b><br /><span class="gensmall">{L_ALLOW_TOPLIST_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="avatar_toplist" value="1" {AVATARS_TOPLIST_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="avatar_toplist" value="0" {AVATARS_TOPLIST_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_ALLOW_VOTING_VIEWTOPIC}:</b><br /><span class="gensmall">{L_ALLOW_VOTING_VIEWTOPIC_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="avatar_voting_viewtopic" value="1" {AVATARS_VOTING_VIEWTOPIC_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="avatar_voting_viewtopic" value="0" {AVATARS_VOTING_VIEWTOPIC_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_IGNORE_SIGAV}:</b><br /><span class="gensmall">{L_IGNORE_SIGAV_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="enable_ignore_sigav" value="1" {IGNORE_SIGAV_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="enable_ignore_sigav" value="0" {IGNORE_SIGAV_NO} /> {L_NO}</td>
</tr>
<!-- END switch_avatars -->

<!-- BEGIN switch_avatar_upload -->
<tr>
	<th class="thHead" colspan="2">{L_UPLOAD}</th>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_ALLOW_UPLOAD}:</b></td>
	<td class="row2"><input type="radio" name="allow_avatar_upload" value="1" {AVATARS_UPLOAD_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_avatar_upload" value="0" {AVATARS_UPLOAD_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_MAX_FILESIZE}:</b><br /><span class="gensmall">{L_MAX_FILESIZE_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="5" maxlength="10" name="avatar_filesize" value="{AVATAR_FILESIZE}" /> Bytes</td>
</tr>
<tr>
	<td class="row1"><b>{L_MAX_AVATAR_SIZE}:</b><br /><span class="gensmall">{L_MAX_AVATAR_SIZE_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="5" maxlength="4" name="avatar_max_height" value="{AVATAR_MAX_HEIGHT}" /> x <input class="post" type="text" size="5" maxlength="4" name="avatar_max_width" value="{AVATAR_MAX_WIDTH}"></td>
</tr>
<tr>
	<td class="row1"><b>{L_AVATAR_STORAGE_PATH}:</b><br /><span class="gensmall">{L_AVATAR_STORAGE_PATH_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="25" maxlength="255" name="avatar_path" value="{AVATAR_PATH}" /></td>
</tr>
<!-- END switch_avatar_upload -->

<!-- BEGIN switch_avatar_gallery -->
<tr>
	<th class="thHead" colspan="2">{L_LOCAL}</th>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_ALLOW_LOCAL}:</b></td>
	<td class="row2"><input type="radio" name="allow_avatar_local" value="1" {AVATARS_LOCAL_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_avatar_local" value="0" {AVATARS_LOCAL_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_AVATARS_PER_PAGE}:</b></td>
	<td class="row2">{AVATARS_PER_PAGE_SELECT}</td>
</tr>
<tr>
	<td class="row1"><b>{L_AVATAR_GALLERY_PATH}:</b><br /><span class="gensmall">{L_AVATAR_GALLERY_PATH_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="25" maxlength="255" name="avatar_gallery_path" value="{AVATAR_GALLERY_PATH}" /></td>
</tr>
<!-- END switch_avatar_gallery -->

<!-- BEGIN switch_avatar_generator -->
<tr>
	<th class="thHead" colspan="2">{L_GENERATOR}</th>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_ALLOW_GENERATOR}:</b></td>
	<td class="row2"><input type="radio" name="allow_avatar_generator" value="1" {AVATAR_GENERATOR_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_avatar_generator" value="0" {AVATAR_GENERATOR_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_AVATAR_GENERATOR_TEMPLATE_PATH}:</b><br /><span class="gensmall">{L_AVATAR_GENERATOR_TEMPLATE_PATH_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="25" maxlength="255" name="avatar_generator_template_path" value="{AVATAR_GENERATOR_TEMPLATE_PATH}" /></td>
</tr>
<!-- END switch_avatar_generator -->

<tr>
	<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</form></table>
<br />
<div align="center" class="copyright">Avatar Suite 1.2.0 &copy; 2005, {COPYRIGHT_YEAR} <a href="http://www.1-4a.com/" class="copyright" target="_blank">knnknn</a></div>
