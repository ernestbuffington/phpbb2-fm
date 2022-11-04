{USER_MENU}{CUSTOM_PROFILE_MENU}{BAN_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<script language="JavaScript" type="text/javascript" src="../templates/js/colorpicker.js"></script>
<script language="JavaScript" type="text/javascript">
<!--
var cp = new ColorPicker();

function spawn() 
{
	x=(screen.width/2)-225;
	y=(screen.height/2)-200;
	window.open('../mods/weather/popup.htm','_weather','left=' + x + ',top=' + y +',screenX=x,screenY=y,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=450,height=400')
}

function clocks() 
{
	x=(screen.width/2)-225;
	y=(screen.height/2)-200;
	window.open('../profile_clocks.php','_colors','left=' + x + ',top=' + y +',screenX=x,screenY=y,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=500,height=400')
}
//-->
</script>

<h1>{L_USER_TITLE}</h1>

<p>{L_USER_EXPLAIN}</p>

{ERROR_BOX}

<table width="100%" cellspacing="1" cellpadding="4" align="center" class="forumline"><form action="{S_PROFILE_ACTION}" {S_FORM_ENCTYPE} method="post" name="myform">
<tr> 
	<th class="thHead" colspan="2">{L_REGISTRATION_INFO}</th>
</tr>
<tr> 
	<td class="row2" colspan="2"><span class="gensmall">{L_ITEMS_REQUIRED}</span></td>
</tr>
<tr> 
	<td class="row1" width="42%"><b>{L_USERNAME}: *</b></td>
	<td class="row2"><input class="post" type="text" name="username" size="35" maxlength="40" value="{USERNAME}" autocomplete="off" /></td>
</tr>
<tr> 
	<td class="row1"><b>{L_EMAIL_ADDRESS}: *</b></td>
	<td class="row2"><input class="post" type="text" name="email" size="35" maxlength="255" value="{EMAIL}" /></td>
</tr>
<tr> 
	<td class="row1"><b>{L_NEW_PASSWORD}: *</b><br /><span class="gensmall">{L_PASSWORD_IF_CHANGED}</span></td>
	<td class="row2"><input class="post" type="password" name="password" size="35" maxlength="32" value="" autocomplete="off" /></td>
</tr>
<tr> 
	<td class="row1"><b>{L_CONFIRM_PASSWORD}: *</b><br /><span class="gensmall">{L_PASSWORD_CONFIRM_IF_CHANGED}</span></td>
	<td class="row2"><input class="post" type="password" name="password_confirm" size="35" maxlength="32" value="" autocomplete="off" /></td>
</tr>
<tr>
	<th class="thSides" colspan="2">{L_SPECIAL}</th>
</tr>
<tr>
	<td class="row2" colspan="2"><span class="gensmall">{L_SPECIAL_EXPLAIN}</span></td>
</tr>
<tr> 
	<td class="row1"><b>{L_USER_REGDATE}:</b></td>
	<td class="row2"><b>{USER_REGDATE}</b></td>
</tr>
<tr> 
	<td class="row1"><b>{L_USER_LASTLOGON}:</b></td>
	<td class="row2"><b>{USER_LASTLOGON}</b></td>
</tr>
<tr> 
	<td class="row1"><b>{L_NUMBER_OF_VISIT}:</b></td> 
	<td class="row2"><b>{NUMBER_OF_VISIT}</b></td> 
</tr> 
<tr> 
	<td class="row1"><b>{L_NUMBER_OF_PAGES}:</b></td>
	<td class="row2"><b>{NUMBER_OF_PAGES}</b></td> 
</tr> 
<tr>
	<td class="row1"><b>{L_TOTAL_ONLINE_TIME}:</b></td>
	<td class="row2"><b>{TOTAL_ONLINE_TIME}</b></td> 
</tr> 
<tr> 
	<td class="row1"><b>{L_LAST_ONLINE_TIME}:</b></td>
	<td class="row2"><b>{LAST_ONLINE_TIME}</b></td> 
</tr> 
<tr> 
	<td class="row1"><b>{L_USER_ACTIVE}:</b></td>
	<td class="row2"><input type="radio" name="user_status" value="1" {USER_ACTIVE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="user_status" value="0" {USER_ACTIVE_NO} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_ALLOW_PM}:</b></td>
	<td class="row2"><input type="radio" name="user_allowpm" value="1" {ALLOW_PM_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="user_allowpm" value="0" {ALLOW_PM_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_DAILY_LIMIT}:</b><br /><span class="gensmall">{L_DAILY_LIMIT_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" name="daily_post_limit" value="{DAILY_POST_LIMIT}" size="4" /></td>
</tr>
<tr> 
	<td class="row1"><b>{L_PM_QUOTA}:</b></td>
	<td class="row2">{S_SELECT_PM_QUOTA}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_UPLOAD_QUOTA}:</b></td>
	<td class="row2">{S_SELECT_UPLOAD_QUOTA}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_EMAIL_VALIDATION}:</b></td>
	<td class="row2"><input type="radio" name="email_validation" value="1" {EMAIL_VALIDATION_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="email_validation" value="0" {EMAIL_VALIDATION_NO} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b><a name="approve_avatar"></a>{L_ALLOW_AVATAR}:</b></td>
	<td class="row2"><input type="radio" name="user_allowavatar" value="1" {ALLOW_AVATAR_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="user_allowavatar" value="0" {ALLOW_AVATAR_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_ALLOW_SIG}:</b></td>
	<td class="row2"><input type="radio" name="user_allowsig" value="1" {ALLOW_SIG_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="user_allowsig" value="0" {ALLOW_SIG_NO} /> {L_NO}</td>
</tr>
<tr> 
	  <td class="row1"><b>{L_KICKER_BAN}:</b></td>
	  <td class="row2"><input type="radio" name="kicker_ban" value="0" {KICKER_BAN_NO} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="kicker_ban" value="1" {KICKER_BAN_YES} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_ALLOW_PROFILE}:</b></td>
	<td class="row2"><input type="radio" name="user_allow_profile" value="1" {ALLOW_PROFILE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="user_allow_profile" value="0" {ALLOW_PROFILE_NO} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_ALLOW_POINTS}:</b></td>
	<td class="row2"><input type="radio" name="allow_points" value="1" {ALLOW_POINTS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_points" value="0" {ALLOW_POINTS_NO} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_POINTS}:</b></td>
	<td class="row2"><input class="post" type="text" name="points" maxlength="12" value="{POINTS}" size="15" /></td>
</tr>
<tr> 
	<td class="row1"><b>{L_BANCARD}:</b><br /><span class="gensmall">{L_BANCARD_EXPLAIN}</span></td> 
	<td class="row2"><input class="post" type="text" style="width: 40px" name="user_ycard" size="4" maxlength="4" value="{BANCARD}" /></td> 
</tr> 
<tr> 
	<td class="row1"><b>{L_VOTEBANCARD}:</b><br /><span class="gensmall">{L_VOTEBANCARD_EXPLAIN}<br /></td> 
	<td class="row2"><input class="post" type="text" style="width: 40px"  name="user_bkcard" size="4" maxlength="4" value="{VOTEBANCARD}" /></td> 
</tr> 
<tr> 
	<td class="row1"><b>{L_KARMA}:</b></td>
	<td class="row2"><b><input class="post" type="text" name="karma_plus" maxlength="10" size="8" value="{KARMA_PLUS}" /> + / <input class="post" type="text" name="karma_minus" maxlength="10" size="8" value="{KARMA_MINUS}" /> -</b></td>
</tr>
<tr>
	<td class="row1"><b>{L_SELECT_RANK}:</b></td>
	<td class="row2"><select name="user_rank">{RANK_SELECT_BOX}</select></td>
</tr>
<tr> 
	<td class="row1"><b>{L_DELETE_USER}?</b></td>
	<td class="row2"><input type="checkbox" name="deleteuser"> {L_DELETE_USER_EXPLAIN}</td>
</tr>
<tr> 
	<th class="thSides" colspan="2">{L_PROFILE_INFO}</th>
</tr>
<tr> 
	<td class="row2" colspan="2"><span class="gensmall">{L_PROFILE_INFO_NOTICE}</span></td>
</tr>
<!-- BEGIN xdata -->
<!-- BEGIN switch_is_realname -->
<tr> 
	  <td class="row1"><b>{L_REALNAME}:</b></td>
	  <td class="row2"><input class="post" type="text" name="realname" size="35" maxlength="40" value="{REALNAME}" /></td>
</tr>
<!-- END switch_is_realname -->
<!-- BEGIN switch_is_skype -->
<tr> 
	<td class="row1"><b>{L_SKYPE}:</b></td>
	<td class="row2"><input class="post" type="text" name="skype" size="35" maxlength="255" value="{SKYPE}" /></td>
</tr>
<!-- END switch_is_skype -->
<!-- BEGIN switch_is_icq -->
<tr> 
	<td class="row1"><b>{L_ICQ_NUMBER}:</b></td>
	<td class="row2"><input class="post" type="text" name="icq" size="35" maxlength="15" value="{ICQ}" /></td>
</tr>
<!-- END switch_is_icq -->
<!-- BEGIN switch_is_aim -->
<tr> 
	<td class="row1"><b>{L_AIM}:</b></td>
	<td class="row2"><input class="post" type="text" name="aim" size="35" maxlength="255" value="{AIM}" /></td>
</tr>
<!-- END switch_is_aim -->
<!-- BEGIN switch_is_xfi -->
<tr>
	<td class="row1"><b>{L_XFIRE}:</b></td>
    	<td class="row2"><input class="post" type="text" name="xfi" size="35" maxlength="255" value="{XFI}" /></td>
</tr>
<!-- END switch_is_xfi -->
<!-- BEGIN switch_is_msn -->
<tr> 
	<td class="row1"><b>{L_MESSENGER}:</b></td>
	<td class="row2"><input class="post" type="text" name="msn" size="35" maxlength="255" value="{MSN}" /></td>
</tr>
<!-- END switch_is_msn -->
<!-- BEGIN switch_is_yim -->
<tr> 
	<td class="row1"><b>{L_YAHOO}:</b></td>
	<td class="row2"><input class="post" type="text" name="yim" size="35" maxlength="255" value="{YIM}" /></td>
</tr>
<!-- END switch_is_yim -->
<!-- BEGIN switch_is_gtalk -->
<tr>
	<td class="row1"><b>{L_GTALK}:</b></td>
    	<td class="row2"><input class="post" type="text" name="gtalk" size="35" maxlength="255" value="{GTALK}" /></td>
</tr>
<!-- END switch_is_gtalk -->
<!-- BEGIN switch_is_website -->
<tr> 
	<td class="row1"><b>{L_WEBSITE}:</b></td>
	<td class="row2"><input class="post" type="text" name="website" size="35" maxlength="255" value="{WEBSITE}" /></td>
</tr>
<!-- END switch_is_website -->
<!-- BEGIN switch_is_stumble -->
<tr> 
	<td class="row1"><b>{L_STUMBLE}:</b></td>
	<td class="row2"><input class="post" type="text" name="stumble" size="35" maxlength="255" value="{STUMBLE}" /></td>
</tr>
<!-- END switch_is_stumble -->
<!-- BEGIN switch_is_location -->
<tr> 
	<td class="row1"><b>{L_LOCATION}:</b></td>
	<td class="row2"><input class="post" type="text" name="location" size="35" maxlength="100" value="{LOCATION}" /></td>
</tr>
<!-- END switch_is_location -->
<!-- BEGIN switch_is_zipcode -->
<tr>
	<td class="row1"><b>{L_ZIPCODE}:</b><br /><span class="gensmall">{L_ZIPCODE_VIEWABLE}</span></td>
	<td class="row2"><input class="post" type="text" name="zipcode" size="10" maxlength="10" value="{ZIPCODE}" /></td>
</tr>
<!-- END switch_is_zipcode -->
<!-- BEGIN switch_is_flag -->
<tr>
	<td class="row1"><b>{L_FLAG}:</b></td>
	<td class="row2"><span class="gensmall"><table><tr><td>{FLAG_SELECT}&nbsp;&nbsp;&nbsp;&nbsp;</td><td><img src="../images/flags/{FLAG_START}" width="32" height="20" name="user_flag" /></td></tr></table></span></td>
</tr>
<!-- END switch_is_flag -->
<!-- BEGIN switch_is_occupation -->
<tr> 
	<td class="row1"><b>{L_OCCUPATION}:</b></td>
	<td class="row2"><textarea name="occupation" rows="5" cols="35" class="post">{OCCUPATION}</textarea></td>
</tr>
<!-- END switch_is_occupation -->
<!-- BEGIN switch_is_interests -->
<tr> 
	<td class="row1"><b>{L_INTERESTS}:</b></td>
	<td class="row2"><textarea name="interests" rows="5" cols="35" class="post">{INTERESTS}</textarea></td>
</tr>
<!-- END switch_is_interests -->
<!-- BEGIN switch_is_gender -->
<tr> 
	<td class="row1"><b>{L_GENDER}:</b></td> 
	<td class="row2"><input type="radio" name="gender" value="1" {GENDER_MALE_CHECKED} /> {L_GENDER_MALE}&nbsp;&nbsp;<input type="radio" name="gender" value="2" {GENDER_FEMALE_CHECKED} /> {L_GENDER_FEMALE}</td> 
</tr>
<!-- END switch_is_gender -->
<!-- BEGIN switch_is_bday -->
<tr> 
	<td class="row1"><b>{L_BIRTHDAY}:</b></td>
	<td class="row2">{S_BIRTHDAY}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_NEXT_BIRTHDAY_GREETING}:</b><br /><span class="gensmall">{L_NEXT_BIRTHDAY_GREETING_EXPLAIN}</span></td> 
	<td class="row2"><input class="post" type="text" name="next_birthday_greeting" size="5" maxlength="4" value="{NEXT_BIRTHDAY_GREETING}" /></td> 
</tr> 
<!-- END switch_is_bday -->
<!-- BEGIN switch_type_text -->
<tr>
	<td class="row1"><b>{xdata.NAME}:</b><br /><span class="gensmall">{xdata.DESCRIPTION}</span></td>
	<td class="row2"><input type="text" class="post" name="{xdata.CODE_NAME}" size="35" maxlength="{xdata.MAX_LENGTH}" value="{xdata.VALUE}" /></td>
</tr>
<!-- END switch_type_text -->
<!-- BEGIN switch_type_textarea -->
<tr>
	<td class="row1"><b>{xdata.NAME}:</b><br /><span class="gensmall">{xdata.DESCRIPTION}</span></td>
	<td class="row2"><textarea name="{xdata.CODE_NAME}" rows="5" cols="35" class="post">{xdata.VALUE}</textarea></td>
</tr>
<!-- END switch_type_textarea -->
<!-- BEGIN switch_type_select -->
<tr>
	<td class="row1"><b>{xdata.NAME}:</b><br /><span class="gensmall">{xdata.DESCRIPTION}</span></td>
	<td class="row2"><select name="{xdata.CODE_NAME}">
		<!-- BEGIN options -->
		<option value="{xdata.switch_type_select.options.OPTION}" {xdata.switch_type_select.options.SELECTED}>{xdata.switch_type_select.options.OPTION}</option>
		<!-- END options -->
	</select></td>
</tr>
<!-- END switch_type_select -->
<!-- BEGIN switch_type_radio -->
<tr>
	<td class="row1"><b>{xdata.NAME}:</b><br /><span class="gensmall">{xdata.DESCRIPTION}</span></td>
	<td class="row2">
   	<!-- BEGIN options -->
	<input type="radio" name="{xdata.CODE_NAME}" value="{xdata.switch_type_radio.options.OPTION}" {xdata.switch_type_radio.options.CHECKED} /> {xdata.switch_type_radio.options.OPTION}&nbsp;&nbsp;
	<!-- END options -->
	</td>
</tr>
<!-- END switch_type_radio -->
<!-- END xdata -->
<tr> 
	<td class="row1"><b>{L_SIGNATURE}:</b><br /><span class="gensmall">{L_SIGNATURE_EXPLAIN}<br /><br />
	{HTML_STATUS}<br />
	{BBCODE_STATUS}<br />
	{SMILIES_STATUS}</span></td>
	<td class="row2"><textarea class="post" name="signature" rows="5" cols="35">{SIGNATURE}</textarea></td>
</tr>
<tr> 
	<th class="thSides" colspan="2">{L_PREFERENCES}</th>
</tr>
<tr> 
	<td class="row1"><b>{L_PUBLIC_VIEW_EMAIL}:</b></td>
	<td class="row2"><input type="radio" name="viewemail" value="1" {VIEW_EMAIL_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="viewemail" value="0" {VIEW_EMAIL_NO} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_HIDE_USER}:</b></td>
	<td class="row2"><input type="radio" name="hideonline" value="1" {HIDE_USER_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="hideonline" value="0" {HIDE_USER_NO} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_NOTIFY_ON_REPLY}:</b></td>
	<td class="row2"><input type="radio" name="notifyreply" value="1" {NOTIFY_REPLY_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="notifyreply" value="0" {NOTIFY_REPLY_NO} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_NOTIFY_ON_PRIVMSG}:</b></td>
	<td class="row2"><input type="radio" name="notifypm" value="1" {NOTIFY_PM_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="notifypm" value="0" {NOTIFY_PM_NO} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_NOTIFY_ON_PRIVMSG_TEXT}:</b></td>
	<td class="row2"><input type="radio" name="notifypmtext" value="1" {NOTIFY_PM_TEXT_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="notifypmtext" value="0" {NOTIFY_PM_TEXT_NO} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_POPUP_ON_PRIVMSG}:</b></td>
	<td class="row2"><input type="radio" name="popup_pm" value="1" {POPUP_PM_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="popup_pm" value="0" {POPUP_PM_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_SOUND_ON_PRIVMSG}:</b></td>
	<td class="row2"><input type="radio" name="sound_pm" value="1" {SOUND_PM_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="sound_pm" value="0" {SOUND_PM_NO} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_PROFILE_VIEW_POPUP}:</b></td>
	<td class="row2"><input type="radio" name="profile_view_popup" value="1" {PROFILE_VIEW_POPUP_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="profile_view_popup" value="0" {PROFILE_VIEW_POPUP_NO} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_POPUP_NOTES}:</b></td>
	<td class="row2"><input type="radio" name="popup_notes" value="1" {POPUP_NOTES_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="popup_notes" value="0" {POPUP_NOTES_NO} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_ALWAYS_ADD_SIGNATURE}:</b></td>
	<td class="row2"><input type="radio" name="attachsig" value="1" {ALWAYS_ADD_SIGNATURE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="attachsig" value="0" {ALWAYS_ADD_SIGNATURE_NO} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_ALWAYS_ALLOW_BBCODE}:</b></td>
	<td class="row2"><input type="radio" name="allowbbcode" value="1" {ALWAYS_ALLOW_BBCODE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allowbbcode" value="0" {ALWAYS_ALLOW_BBCODE_NO} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_ALWAYS_ALLOW_HTML}:</b></td>
	<td class="row2"><input type="radio" name="allowhtml" value="1" {ALWAYS_ALLOW_HTML_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allowhtml" value="0" {ALWAYS_ALLOW_HTML_NO} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_ALWAYS_ALLOW_SMILIES}:</b></td>
	<td class="row2"><input type="radio" name="allowsmilies" value="1" {ALWAYS_ALLOW_SMILIES_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allowsmilies" value="0" {ALWAYS_ALLOW_SMILIES_NO} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_ALWAYS_ALLOW_SIGS}:</b></td>
	<td class="row2"><input type="radio" name="allowsigs" value="1" {ALWAYS_ALLOW_SIGS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allowsigs" value="0" {ALWAYS_ALLOW_SIGS_NO} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_WORD_WRAP}:</b><br /><span class="gensmall">{L_WORD_WRAP_EXPLAIN}</span></td>
	<td class="row2"><input type="text" name="user_wordwrap" value="{WRAP_ROW}" size="4" maxlength="2" class="post" /> {L_WORD_WRAP_EXTRA}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_BOARD_LANGUAGE}:</b></td>
	<td class="row2">{LANGUAGE_SELECT}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_BOARD_STYLE}:</b></td>
	<td class="row2">{STYLE_SELECT}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_TIMEZONE}:</b></td>
	<td class="row2">{TIMEZONE_SELECT}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_DATE_FORMAT}:</b></td>
	<td class="row2">{DATE_FORMAT_SELECT}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_CLOCK_FORMAT}:</b><br /><span class="gensmall">{L_CLOCK_FORMAT_EXPLAIN}</span></td>
	<td class="row2">{CLOCK_FORMAT_SELECT}</td>
</tr>
<!-- BEGIN switch_color_groups -->
<tr> 
	<td class="row1"><b>{L_GROUP_PRIORITY}:</b></td>
	<td class="row2">{GROUP_PRIORITY_SELECT}</td>
</tr>
<!-- END switch_color_groups -->
<tr> 
	<td class="row1"><b>{L_CUSTOM_POST_COLOR}:</b><br /><span class="gensmall">{L_CUSTOM_POST_COLOR_EXPLAIN}</span></td> 
    	<td class="row2">#<input class="post" type="text" name="custom_post_color" size="8" maxlength="6" value="{CUSTOM_POST_COLOR}" />
	<!-- BEGIN no_info_color -->
	<input class="post" type="text" size="1" style="background-color:#{CUSTOM_POST_COLOR}" disabled="yes" />
	<!-- END no_info_color -->
	<a href="javascript:cp.select(document.forms[0].custom_post_color,'pick');" name="pick" id="pick"><img src="{I_PICK_COLOR}" width="9" height="9" alt="" title="" /></a></td> 
</tr>
<tr> 
	<td class="row1"><b>{L_MYINFO_PROFILE}:</b><br /><span class="gensmall">{L_MYINFO_PROFILE_EXPLAIN}</span><br /><br /><br /></td>
	<td class="row2"><textarea name="myInfo" rows="5" cols="35" class="post">{MYINFO}</textarea></td>
</tr>
<tr> 
	<th class="thSides" colspan="2">{L_AVATAR_PANEL}</th>
</tr>
<tr align="center"> 
	<td class="row1" colspan="2"><table width="70%" cellspacing="2" cellpadding="0">
	<tr> 
		<td width="65%"><span class="gensmall">{L_AVATAR_EXPLAIN}</span></td>
		<td align="center"><span class="gensmall">{L_CURRENT_IMAGE}</span><br />{AVATAR}<br /><input type="checkbox" name="avatardel" />&nbsp;<span class="gensmall">{L_DELETE_AVATAR}</span></td>
	</tr>
	</table></td>
</tr>
<!-- BEGIN avatar_local_upload -->
<tr> 
	<td class="row1"><b>{L_UPLOAD_AVATAR_FILE}:</b></td>
	<td class="row2"><input type="hidden" name="MAX_FILE_SIZE" value="{AVATAR_SIZE}" /><input type="file" name="avatar" class="post" style="width: 200px"  /></td>
</tr>
<!-- END avatar_local_upload -->
<!-- BEGIN avatar_remote_upload -->
<tr> 
	<td class="row1"><b>{L_UPLOAD_AVATAR_URL}:</b></td>
	<td class="row2"><input class="post" type="text" name="avatarurl" size="40" style="width: 200px"  /></td>
</tr>
<!-- END avatar_remote_upload -->
<!-- BEGIN avatar_remote_link -->
<tr> 
	<td class="row1"><b>{L_LINK_REMOTE_AVATAR}:</b></td>
	<td class="row2"><input class="post" type="text" name="avatarremoteurl" size="40" style="width: 200px"  /></td>
</tr>
<!-- END avatar_remote_link -->
<!-- BEGIN avatar_local_gallery -->
<tr> 
	<td class="row1"><b>{L_AVATAR_GALLERY}:</b></td>
	<td class="row2"><input type="submit" name="avatargallery" value="{L_SHOW_GALLERY}" class="liteoption" /></td>
</tr>
<!-- END avatar_local_gallery -->
<tr> 
	<td class="row1"><b>{L_ALWAYS_ALLOW_AVATARS}:</b></td>
	<td class="row2"><input type="radio" name="allowavatars" value="1" {ALWAYS_ALLOW_AVATARS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allowavatars" value="0" {ALWAYS_ALLOW_AVATARS_NO} /> {L_NO}</td>
</tr>
<tr> 
	<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS} <input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</form></table>

<script language="JavaScript" type="text/javascript">
<!--
cp.writeDiv()
//-->
</script>
