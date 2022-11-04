{USERCOM_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_IRC}</h1>

<p>{L_IRC_CHAT_EXPLAIN}</p>

<table width="100%" align="center"><form action="{S_CHAT_ACTION}" method="post">
<tr>
	<td align="right">{CONFIG_SELECT} <input type="submit" name="config" value="{L_GO}" class="liteoption" /></td>
</tr>
</form></table>
<table width="100%" cellpadding="4" cellspacing="1" align="center" class="forumline"><form action="{S_CHAT_ACTION}" method="post">

<!-- BEGIN switch_config -->
<tr>
        <th class="thHead" colspan="2">{L_IRC}</th>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_STATUS}:</b></td>
        <td class="row2"><input type="radio" name="irc_status" value="1" {IRC_STATUS_OPEN} /> {L_STATUS_OPEN} &nbsp;<input type="radio" name="irc_status" value="0" {IRC_STATUS_CLOSED} /> {L_STATUS_CLOSED}</td>
</tr>
<tr>
	<td class="row1"><b>{L_SERVER}:</b></td>
	<td class="row2"><input class="post" type="text" name="irc_server" value="{IRC_SERVER}" size="25" maxlength="255" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_PORT}:</b></td>
	<td class="row2"><input class="post" type="text" name="irc_port" value="{IRC_PORT}" size="5" maxlength="5" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_CHANNEL}:</b></td>
	<td class="row2"><input class="post" type="text" name="irc_channel" value="{IRC_CHANNEL}" size="25" maxlength="255" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_IRC_LANGUAGE}:</b></td>
	<td class="row2">{IRC_LANGUAGE_LIST}</td>
</tr>
<tr>
	<td class="row1"><b>{L_IRC_TEMPLATE}:</b></td>
	<td class="row2">{IRC_TEMPLATE_LIST}</td>
</tr>
<!-- END switch_config -->

<!-- BEGIN switch_access -->
<tr>
        <th class="thHead" colspan="2">{L_IRC_ACCESS}</th>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_POPUP_ONOFF}:</b></td>
	<td class="row2"><input type="radio" name="irc_popup_onoff" value="1" {IRC_POPUP_ONOFF_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="irc_popup_onoff" value="0" {IRC_POPUP_ONOFF_NO} /> {L_NO}</td>
</tr>
<tr>
        <td class="row1"><b>{L_ALLOW_GUESTS}:</b></td>
	<td class="row2"><input type="radio" name="irc_allow_guests" value="1" {IRC_ALLOW_GUESTS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="irc_allow_guests" value="0" {IRC_ALLOW_GUESTS_NO} /> {L_NO}</td>
</tr>
<tr>
        <td class="row1"><b>{L_GUESTNAME}:</b><br /><span class="gensmall">{L_GUESTNAME_EXPLAIN}</span></td>
        <td class="row2"><input class="post" type="text" name="irc_guestname" value="{IRC_GUESTNAME}" size="15" maxlength="20" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_AUTH_JOINLIST}:</b><br /><span class="gensmall">{L_AUTH_JOINLIST_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" name="irc_auth_joinlist" value="{IRC_AUTH_JOINLIST}" size="25" maxlength="255" /></td>
</tr>
<!-- END switch_access -->

<!-- BEGIN switch_baf -->
<tr>
	<th class="thHead" colspan="2">{L_IRC_BUTTONS}</th>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_SHOW_CONNECT}:</b></td>
	<td class="row2"><input type="radio" name="irc_show_connect" value="1" {IRC_SHOW_CONNECT_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="irc_show_connect" value="0" {IRC_SHOW_CONNECT_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_SHOW_CHANLIST}:</b></td>
	<td class="row2"><input type="radio" name="irc_show_chanlist" value="1" {IRC_SHOW_CHANLIST_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="irc_show_chanlist" value="0" {IRC_SHOW_CHANLIST_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_SHOW_ABOUT}:</b></td>
	<td class="row2"><input type="radio" name="irc_show_about" value="1" {IRC_SHOW_ABOUT_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="irc_show_about" value="0" {IRC_SHOW_ABOUT_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_SHOW_HELP}:</b></td>
	<td class="row2"><input type="radio" name="irc_show_help" value="1" {IRC_SHOW_HELP_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="irc_show_help" value="0" {IRC_SHOW_HELP_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_SHOW_CLOSE}:</b></td>
	<td class="row2"><input type="radio" name="irc_show_close" value="1" {IRC_SHOW_CLOSE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="irc_show_close" value="0" {IRC_SHOW_CLOSE_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_SHOW_STATUS}:</b></td>
	<td class="row2"><input type="radio" name="irc_show_status" value="1" {IRC_SHOW_STATUS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="irc_show_status" value="0" {IRC_SHOW_STATUS_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_SHOW_DOCK}:</b></td>
	<td class="row2"><input type="radio" name="irc_show_dock" value="1" {IRC_SHOW_DOCK_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="irc_show_dock" value="0" {IRC_SHOW_DOCK_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_SHOW_NICKFIELD}:</b></td>
	<td class="row2"><input type="radio" name="irc_show_nickfield" value="1" {IRC_SHOW_NICKFIELD_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="irc_show_nickfield" value="0" {IRC_SHOW_NICKFIELD_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_TIME_STAMP}:</b></td>
	<td class="row2"><input type="radio" name="irc_time_stamp" value="1" {IRC_TIME_STAMP_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="irc_time_stamp" value="0" {IRC_TIME_STAMP_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_TOPICSCROLLER}:</b><br /><span class="gensmall">{L_TOPICSCROLLER_DEFINITION_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" name="irc_topicscroller" value="{IRC_TOPICSCROLLER}" size="5" maxlength="2" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_QUIT}:</b></td>
	<td class="row2"><input class="post" type="text" name="irc_quit" value="{IRC_QUIT}" size="20" maxlength="255" /></td>
</tr>
<!-- END switch_bnf -->

<!-- BEGIN switch_smiley -->
<tr>
	<th class="thHead" colspan="2">{L_SMILIES_CONTROL}</th>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_SMILIES}:</b></td>
	<td class="row2"><input type="radio" name="irc_smilies" value="1" {IRC_SMILIES_ON} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="irc_smilies" value="0" {IRC_SMILIES_OFF} /> {L_NO}</td>
</tr>
<tr> 
        <td class="row1"><b>{L_SMILEY_ENTER}:</b></td>
        <td class="row2"><input type="radio" name="irc_smilies_enter" value="1" {IRC_SMILEY_ENTER_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="irc_smilies_enter" value="0" {IRC_SMILEY_ENTER_NO} /> {L_NO}</td> 
</tr>
<tr>
        <td class="row1"><b>{L_SMILEY_COUNT}:</b><br /><span class="gensmall">{L_SMILEY_COUNT_EXPLAIN}</span></td>
        <td class="row2"><input class="post" type="text" name="irc_smilies_count" value="{IRC_SMILEY_COUNT}" size="5" maxlength="5" /></td>
</tr>
<tr>
        <td class="row1"><b>{L_SMILIES_LINES}:</b></td>
        <td class="row2"><input class="post" type="text" name="irc_smilies_lines" value="{IRC_SMILIES_LINES}" size="5" maxlength="5" /></td>
</tr>
<!-- END switch_smiley -->

<!-- BEGIN switch_sound -->
<tr>
	<th class="thHead" colspan="2">{L_IRC_SOUNDS}</th>
</tr>
<tr>
	<td class="row2" colspan="2"><span class="gensmall">{L_IRC_SOUND_EXPLAIN}</span></td>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_SOUND_BEEP}:</b></td>
	<td class="row2"><input class="post" type="text" name="irc_sound_beep" value="{IRC_SOUND_BEEP}" size="20" maxlength="255" /></td>
</tr>
<tr> 
        <td class="row1"><b>{L_SOUND_BEEP_DELAY}:</b></td>
        <td class="row2"><input class="post" type="text" name="irc_enter_timer" value="{IRC_ENTER_TIMER}" size="5" maxlength="255" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_SOUND_QUERY}:</b></td>
	<td class="row2"><input class="post" type="text" name="irc_sound_query" value="{IRC_SOUND_QUERY}" size="20" maxlength="255" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_SOUND_PROFILE}:</b></td>
	<td class="row2"><input class="post" type="text" name="irc_sound_profile" value="{IRC_SOUND_PROFILE}" size="20" maxlength="255" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_SOUND_IM}:</b></td>
	<td class="row2"><input class="post" type="text" name="irc_sound_im" value="{IRC_SOUND_IM}" size="20" maxlength="255" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_SOUND_IGNORE}:</b></td>
	<td class="row2"><input class="post" type="text" name="irc_sound_ignore" value="{IRC_SOUND_IGNORE}" size="20" maxlength="255" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_SOUND_UNIGNORE}:</b></td>
	<td class="row2"><input class="post" type="text" name="irc_sound_unignore" value="{IRC_SOUND_UNIGNORE}" size="20" maxlength="255" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_SOUND_AWAY}:</b></td>
	<td class="row2"><input class="post" type="text" name="irc_sound_away" value="{IRC_SOUND_AWAY}" size="20" maxlength="255" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_SOUND_BACK}:</b></td>
	<td class="row2"><input class="post" type="text" name="irc_sound_back" value="{IRC_SOUND_BACK}" size="20" maxlength="255" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_SOUND_CLEAR}:</b></td>
	<td class="row2"><input class="post" type="text" name="irc_sound_clear" value="{IRC_SOUND_CLEAR}" size="20" maxlength="255" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_SOUND_WHOIS}:</b></td>
	<td class="row2"><input class="post" type="text" name="irc_sound_whois" value="{IRC_SOUND_WHOIS}" size="20" maxlength="255" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_SOUND_HELP}:</b></td>
	<td class="row2"><input class="post" type="text" name="irc_sound_help" value="{IRC_SOUND_HELP}" size="20" maxlength="255" /></td>
</tr>
<tr>
	<td class="row2" colspan="2"><span class="gensmall">{L_IRC_SOUNDWORDS_EXPLAIN}</span></td>
</tr>
<tr> 
        <td class="row1"><b>{L_SOUND_SOUND1}:</b></td>
        <td class="row2"><input class="post" type="text" name="irc_sound1" value="{IRC_SOUND1}" size="35" maxlength="255" /></td>
</tr>
<tr>
        <td class="row1"><b>{L_SOUND_SOUND1WORDS}:</b></td>
        <td class="row2"><input class="post" type="text" name="irc_soundwords1" value="{IRC_SOUNDWORDS1}" size="35" maxlength="255" /></td>
</tr>
<tr>
        <td class="row1"><b>{L_SOUND_SOUND2}:</b></td>
        <td class="row2"><input class="post" type="text" name="irc_sound2" value="{IRC_SOUND2}" size="35" maxlength="255" /></td>
</tr>
<tr>
        <td class="row1"><b>{L_SOUND_SOUND2WORDS}:</b></td>
        <td class="row2"><input class="post" type="text" name="irc_soundwords2" value="{IRC_SOUNDWORDS2}" size="35" maxlength="255" /></td>
</tr>
<!-- END switch_sound -->

<!-- BEGIN switch_bot -->
<tr>
	<th class="thHead" colspan="2">{L_BOT_CONTROL}</th>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_BOT_OVERALL}:</b><br /><span class="gensmall">{L_BOT_OVERALL_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="irc_bot_overall" value="1" {IRC_BOT_OVERALL_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="irc_bot_overall" value="0" {IRC_BOT_OVERALL_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_BOT_SWITCH1}:</b></td>
	<td class="row2"><input type="radio" name="irc_bot_switch1" value="1" {IRC_BOT_SWITCH1_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="irc_bot_switch1" value="0" {IRC_BOT_SWITCH1_NO} /> {L_NO}</td>
</tr>
<tr> 
        <td class="row1"><b>{L_BOT_OVERALL_TIMER}:</b></td>
        <td class="row2"><input class="post" type="text" name="irc_bot_timer" value="{IRC_BOT_OVERALL_TIMER}" size="5" maxlength="255" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_BOT_SWITCH2}:</b></td>
	<td class="row2"><input type="radio" name="irc_bot_switch2" value="1" {IRC_BOT_SWITCH2_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="irc_bot_switch2" value="0" {IRC_BOT_SWITCH2_NO} /> {L_NO}</td>
</tr>
<!-- END switch_bot -->

<!-- BEGIN switch_extra -->
<tr>
	<th class="thHead" colspan="2">{L_IRC_ADVANCED}</th>
</tr>
<tr>
        <td class="row1" width="50%"><b>{L_CHANNEL2}:</b><br /><span class="gensmall">{L_CHANNEL2_DEFINITION_EXPLAIN}</span></td>
        <td class="row2"><input type="radio" name="irc_channel2_on" value="0" {IRC_CHANNEL2_NO} /> {L_CHANNEL2_DISABLE}&nbsp;&nbsp;<input type="radio" name="irc_channel2_on" value="1" {IRC_CHANNEL2_YES} /> <input class="post" type="text" name="irc_channel2" value="{IRC_CHANNEL2}" size="25" maxlength="255" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_CHANNEL3}:</b><br /><span class="gensmall">{L_CHANNEL3_DEFINITION_EXPLAIN}</span></td>
        <td class="row2"><input type="radio" name="irc_channel3_on" value="0" {IRC_CHANNEL3_NO} /> {L_CHANNEL3_DISABLE}&nbsp;&nbsp;<input type="radio" name="irc_channel3_on" value="1" {IRC_CHANNEL3_YES} />&nbsp;<input class="post" type="text" name="irc_channel3" value="{IRC_CHANNEL3}" size="25" maxlength="255" /></td>
</tr>
<tr>
        <td class="row1"><b>{L_MULTISERVER}:</b><br /><span class="gensmall">{L_MULTISERVER_EXPLAIN}</span></td>
        <td class="row2"><input type="radio" name="irc_multiserver" value="1" {IRC_MULTISERVER_YES} /> {L_YES} &nbsp;<input type="radio" name="irc_multiserver" value="0" {IRC_MULTISERVER_NO} /> {L_NO}</td>
</tr>
<tr> 
        <td class="row1"><b>{L_MULTISERVER_SERVER}:</b></td>
        <td class="row2"><input class="post" type="text" name="irc_multiserver_server" value="{IRC_MULTISERVER_SERVER}" size="25" maxlength="255" /></td>
</tr>
<tr> 
        <td class="row1"><b>{L_MULTISERVER_PORT}:</b></td>
        <td class="row2"><input class="post" type="text" name="irc_multiserver_port" value="{IRC_MULTISERVER_PORT}" size="5" maxlength="5" /></td>
</tr>
<tr> 
        <td class="row1"><b>{L_MULTISERVER_DELAY}:</b><br /><span class="gensmall">{L_MULTISERVER_DELAY_EXPLAIN}</span></td>
        <td class="row2"><input class="post" type="text" name="irc_multiserver_delay" value="{IRC_MULTISERVER_DELAY}" size="5" maxlength="255" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_USE_INFO}:</b></td>
	<td class="row2"><input type="radio" name="irc_use_info" value="1" {IRC_USE_INFO_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="irc_use_info" value="0" {IRC_USE_INFO_NO} /> {L_NO}</td>
</tr>
<!-- END switch_extra -->

<!-- BEGIN switch_style -->
<tr>
	<th class="thHead" colspan="2">{L_IRC_LOOKS}</th>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_STYLE_SELECTOR}:</b></td>
	<td class="row2"><input type="radio" name="irc_style_selector" value="1" {IRC_STYLE_SELECTOR_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="irc_style_selector" value="0" {IRC_STYLE_SELECTOR_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_STYLE_SELECTOR_DEFINITION}:</b><br /><span class="gensmall">{L_STYLE_SELECTOR_DEFINITION_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" name="irc_style_selector_definition" value="{IRC_STYLE_SELECTOR_DEFINITION}" size="20" maxlength="255" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_FONT_STYLE}:</b></td>
	<td class="row2"><input type="radio" name="irc_font_style" value="1" {IRC_FONT_STYLE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="irc_font_style" value="0" {IRC_FONT_STYLE_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_FONT_STYLE_DEFINITION}:</b><br /><span class="gensmall">{L_FONT_STYLE_DEFINITION_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" name="irc_font_style_definition" value="{IRC_FONT_STYLE_DEFINITION}" size="20" maxlength="255" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_STYLE_NICK}:</b><br /><span class="gensmall">{L_STYLE_NICK_EXPLAIN}</span></td>
	<td class="row2">&nbsp;<input class="post" type="text" name="irc_style_nick_left" value="{IRC_STYLE_NICK_LEFT}" size="15" maxlength="255" /><br /><br />&nbsp;<input class="post" type="text" name="irc_style_nick_right" value="{IRC_STYLE_NICK_RIGHT}" size="15" maxlength="255" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_SHOW_HIGHLIGHT}:</b></td>
	<td class="row2"><input type="radio" name="irc_show_highlight" value="1" {IRC_SHOW_HIGHLIGHT_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="irc_show_highlight" value="0" {IRC_SHOW_HIGHLIGHT_NO} /> {L_NO}</td>
</tr>
<tr> 
        <td class="row1"><b>{L_HIGHLIGHTCOLOR}:</b><br /><span class="gensmall">{L_HIGHLIGHTCOLOR_DEFINITION_EXPLAIN}</span></td>
        &nbsp;<td class="row2">{IRC_HIGHLIGHTCOLOR_LIST}</td>
</tr>
<tr>
	<td class="row1"><b>{L_HIGHLIGHTWORDS}:</b><br /><span class="gensmall">{L_HIGHLIGHTWORDS_DEFINITION_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" name="irc_highlightwords" value="{IRC_HIGHLIGHTWORDS}" size="20" maxlength="255" /></td>
</tr>
<tr>
        <td class="row1"><b>{L_BACKGROUND_WHICH}:</b><br /><span class="gensmall">{L_BACKGROUND_CUSTOM_EXPLAIN}</span></td>
        <td class="row2">
        &nbsp;<input type="radio" name="irc_background_which" value="0" {IRC_BACKGROUND_WHICH} /> {L_BACKGROUND_WHICH_0}<br>
        &nbsp;<input type="radio" name="irc_background_which" value="1" {IRC_BACKGROUND_WHICH_1} /> {L_BACKGROUND_WHICH_1}<br>
        &nbsp;<input type="radio" name="irc_background_which" value="2" {IRC_BACKGROUND_WHICH_2} /> {L_BACKGROUND_WHICH_2}<br>
        &nbsp;<input type="radio" name="irc_background_which" value="3" {IRC_BACKGROUND_WHICH_CUSTOM} /> {L_BACKGROUND_CUSTOM}: <input class="post" type="text" name="irc_background_file" value="{IRC_BACKGROUND_FILE}" size="15" maxlength="50" /></td>
        </td>
</tr>
<!-- END switch_style -->

<tr>
	<td class="catBottom" colspan="2" align="center"><input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</form></table>
<br />
<div align="center" class="copyright"><a href="http://www.phpbb.com/phpBB/viewtopic.php?t=201400" target="_blank" class="copyright">PJIRC Chat</a> {PJIRC_MOD_VERSION} &copy; 2004, {COPYRIGHT_YEAR}</div>
