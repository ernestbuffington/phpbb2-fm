{LOG_MENU}{UTILS_MENU}{DB_MENU}{LANG_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_LOG_CONFIG_TITLE}</h1>

<p>{L_LOG_CONFIG_TITLE_EXPLAIN}</p>

<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline"><form action="{S_CONFIG_ACTION}" method="post">
<tr>
	<th class="thHead" colspan="2">{L_LOG_CONFIG_TITLE}</th>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_ENABLE_LOGGER}:</b></td>
	<td class="row2"><input type="radio" name="enable_mod_logger" value="1" {LOGGER_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="enable_mod_logger" value="0" {LOGGER_NO} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_ENABLE_IP_LOGGER}:</b></td> 
	<td class="row2"><input type="radio" name="enable_ip_logger" value="1" {IP_LOGGER_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="enable_ip_logger" value="0" {IP_LOGGER_NO} /> {L_NO}</td> 
</tr> 
<tr>
	<td class="row1"><b>{L_ENABLE_REFERERS}:</b></td>
	<td class="row2"><input type="radio" name="enable_http_referrers" value="1" {ENABLE_REFERERS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="enable_http_referrers" value="0" {ENABLE_REFERERS_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_BOT_TRACKING}:</b></td>
	<td class="row2"><input type="radio" name="enable_bot_tracking" value="1" {BOT_TRACKING_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="enable_bot_tracking" value="0" {BOT_TRACKING_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_ALLOW_OTHER_ADMIN}:</b><br /><span class="gensmall">{L_ALLOW_OTHER_ADMIN_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="all_admin" value="1" {S_ALLOW_ALL_ADMIN} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="all_admin" value="0" {S_DISALLOW_ALL_ADMIN} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_ADD_ADMIN_USERNAME}:</b><br /><span class="gensmall">{L_USERNAME_ADD_ADMIN_EXPLAIN}</span></td>
	<td class="row2">{S_ADD_ADMIN}&nbsp;<input type="submit" name="add_admin" value="{L_ADD}" class="liteoption" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_DELETE_ADMIN_USERNAME}:</b><br /><span class="gensmall">{L_USERNAME_DELETE_ADMIN_EXPLAIN}</span></td>
	<td class="row2">{S_DELETE_ADMIN}&nbsp;<input type="submit" name="delete_admin" value="{L_DELETE}" class="liteoption" /></td>
</tr>
<tr>
	<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</form></table>
