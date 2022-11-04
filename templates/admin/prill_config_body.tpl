{USERCOM_MENU}{PRILL_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_CONFIGURATION_TITLE}</h1>

<p>{L_CONFIGURATION_EXPLAIN}</p>

<table width="100%" cellpadding="4" cellspacing="1" align="center" class="forumline"><form action="{S_CONFIG_ACTION}" method="post">
<input type="hidden" name="allow_shout" value="1" /><input type="hidden" name="allow_chat" value="1" />
<tr>
	<th class="thHead" colspan="2">{L_CONFIGURATION_TITLE}</th>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_ALLOW_IMS}:</b></td>
	<td class="row2"><input type="radio" name="allow_ims" value="1" {ALLOW_IMS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_ims" value="0" {ALLOW_IMS_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_ALLOW_NETWORK}:</b></td>
	<td class="row2"><input type="radio" name="allow_network" value="1" {ALLOW_NETWORK_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_network" value="0" {ALLOW_NETWORK_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_NETWORK_PROFILE}:</b><br /><span class="gensmall">{L_NETWORK_PROFILE_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="20" maxlength="255" name="network_profile" value="{NETWORK_PROFILE}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_OPEN_PMS}:</b></td>
	<td class="row2"><input type="radio" name="open_pms" value="1" {OPEN_PMS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="open_pms" value="0" {OPEN_PMS_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_ENABLE_FLOOD}:</b></td>
	<td class="row2"><input type="radio" name="enable_flood" value="1" {ENABLE_FLOOD_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="enable_flood" value="0" {ENABLE_FLOOD_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_FLOOD_INTERVAL}:</b><br /><span class="gensmall">{L_FLOOD_INTERVAL_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="5" maxlength="4" name="flood_interval" value="{FLOOD_INTERVAL}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_AUTO_DEL}:</b></td>
	<td class="row2"><input type="radio" name="auto_delete" value="1" {AUTO_DELETE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="auto_delete" value="0" {AUTO_DELETE_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_ENABLE_LIMIT}:</b></td>
	<td class="row2"><input type="radio" name="enable_im_limit" value="1" {ENABLE_IM_LIMIT_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="enable_im_limit" value="0" {ENABLE_IM_LIMIT_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_BOX_LIMIT}:</b><br /></td>
	<td class="row2"><input class="post" type="text" size="5" maxlength="4" name="box_limit" value="{BOX_LIMIT}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_OVERRIDE_USERS}:</b><br /><span class="gensmall">{L_OVERRIDE_USERS_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="override_users" value="1" {OVERRIDE_USERS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="override_users" value="0" {OVERRIDE_USERS_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_REFRESH_DROP}:</b></td>
	<td class="row2"><input type="radio" name="refresh_drop" value="1" {REFRESH_DROP_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="refresh_drop" value="0" {REFRESH_DROP_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_REFRESH_RATE}:</b><br /><span class="gensmall">{L_REFRESH_RATE_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="5" maxlength="5" name="refresh_rate" value="{REFRESH_RATE}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_SESS_LEN}:</b><br /><span class="gensmall">{L_SESS_LEN_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="5" maxlength="5" name="session_length" value="{SESSION_LENGTH}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_REFRESH_METHOD}:</b><br /><span class="gensmall">{L_REFRESH_METHOD_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="refresh_method" value="1" {REFRESH_METHOD_YES} /> {L_JAVASCRIPT}&nbsp;&nbsp;<input type="radio" name="refresh_method" value="0" {REFRESH_METHOD_NO} /> {L_META}&nbsp;&nbsp;<input type="radio" name="refresh_method" value="2" {REFRESH_METHOD_BOTH} /> {L_BOTH}</td>
</tr>
<tr>
	<td class="row1"><b>{L_USE_FRAMES}:</b><br /><span class="gensmall">{L_USE_FRAMES_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="use_frames" value="1" {USE_FRAMES_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="use_frames" value="0" {USE_FRAMES_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_DEFAULT_MODE}:</b></td>
	<td class="row2">{DEFAULT_MODE_SELECT}</td>
</tr>
<tr>
	<td class="row1"><b>{L_ALLOW_MODE_SWITCH}:</b></td>
	<td class="row2"><input type="radio" name="allow_mode_switch" value="1" {ALLOW_MODE_SWITCH_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_mode_switch" value="0" {ALLOW_MODE_SWITCH_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_AUTO_LAUNCH}:</b></td>
	<td class="row2"><input type="radio" name="auto_launch" value="1" {AUTO_LAUNCH_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="auto_launch" value="0" {AUTO_LAUNCH_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_POPUP_IMS}:</b></td>
	<td class="row2"><input type="radio" name="popup_ims" value="1" {POPUP_IMS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="popup_ims" value="0" {POPUP_IMS_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_LIST_IMS}:</b></td>
	<td class="row2"><input type="radio" name="list_ims" value="1" {LIST_IMS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="list_ims" value="0" {LIST_IMS_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_PLAY_SOUND}:</b></td>
	<td class="row2"><input type="radio" name="play_sound" value="1" {PLAY_SOUND_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="play_sound" value="0" {PLAY_SOUND_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_SOUND_NAME}:</b></td>
	<td class="row2"><input class="post" type="text" size="25" maxlength="100" name="sound_name" value="{SOUND_NAME}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_DEFAULT_SOUND}:</b></td>
	<td class="row2"><input type="radio" name="default_sound" value="1" {DEFAULT_SOUND_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="default_sound" value="0" {DEFAULT_SOUND_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_STYLE_ALLOW}:</b></td>
	<td class="row2"><input type="radio" name="themes_allow" value="1" {THEMES_ALLOW_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="themes_allow" value="0" {THEMES_ALLOW_NO} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_STYLE}:</b></td>
	<td class="row2"><span class="gensmall">{STYLE_SELECT}</span></td>
</tr>
<tr>
	<td class="row1"><b>{L_SUCCESS_CLOSE}:</b></td>
	<td class="row2"><input type="radio" name="success_close" value="1" {SUCCESS_CLOSE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="success_close" value="0" {SUCCESS_CLOSE_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_SHOW_CONTROLS}:</b></td>
	<td class="row2">{SHOW_CONTROLS}</td>
</tr>
<tr>
	<td class="row1"><b>{L_WHO_TO_LIST}:</b></td>
	<td class="row2">{LIST_ALL_ONLINE}</td>
</tr>
<tr>
	<td class="row1"><b>{L_NETWORK_USER_SELECT}:</b></td>
	<td class="row2">{NETWORK_USER_SELECT}</td>
</tr>
<tr>
	<th class="thHead" colspan="2">{L_SET_WINDOW_SIZES}</th>
</tr>
<tr>
	<td class="row2" colspan="2"><span class="gensmall">{L_SET_WINDOW_SIZES_EXPLAIN}</span></td>
</tr>
<tr>
	<td class="row1"><b>{L_MAIN_WINDOW}:</b></td>
	<td class="row2"><input class="post" type="text" size="5" maxlength="4" name="mode1_width" value="{MODE1_WIDTH}" /> x <input class="post" type="text" size="5" maxlength="4" name="mode1_height" value="{MODE1_HEIGHT}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_WIDE_WINDOW}:</b></td>
	<td class="row2"><input class="post" type="text" size="5" maxlength="4" name="mode2_width" value="{MODE2_WIDTH}" /> x <input class="post" type="text" size="5" maxlength="4" name="mode2_height" value="{MODE2_HEIGHT}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_MINI_WINDOW}:</b></td>
	<td class="row2"><input class="post" type="text" size="5" maxlength="4" name="mode3_width" value="{MODE3_WIDTH}" /> x <input class="post" type="text" size="5" maxlength="4" name="mode3_height" value="{MODE3_HEIGHT}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_PREFS}:</b></td>
	<td class="row2"><input class="post" type="text" size="5" maxlength="4" name="prefs_width" value="{PREFS_WIDTH}" /> x <input class="post" type="text" size="5" maxlength="4" name="prefs_height" value="{PREFS_HEIGHT}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_READ_WINDOW}:</b></td>
	<td class="row2"><input class="post" type="text" size="5" maxlength="4" name="read_width" value="{READ_WIDTH}" /> x <input class="post" type="text" size="5" maxlength="4" name="read_height" value="{READ_HEIGHT}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_SEND_WINDOW}:</b></td>
	<td class="row2"><input class="post" type="text" size="5" maxlength="4" name="send_width" value="{SEND_WIDTH}" /> x <input class="post" type="text" size="5" maxlength="4" name="send_height" value="{SEND_HEIGHT}" /></td>
</tr>
<tr>
	<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</form></table>
<br />
<div align="center" class="copyright">Prillian 0.7.0 Beta &copy; 2003, {COPYRIGHT_YEAR} <a href="http://darkmods.sourceforge.net" class="copyright" target="_blank">Thoul</a></div>
