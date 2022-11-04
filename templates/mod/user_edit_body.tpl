{MOD_CP_MENU}
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
<!-- BEGIN switch_change_disallowed -->
<tr>
	<td class="row1" width="42%"><b>{L_USERNAME}: *</b></td>
	<td class="row2"><input type="hidden" name="username" value="{USERNAME}"{DISABLE_CHANGE} /><b class="genmed">{USERNAME}</b></b></td>
</tr>
<tr>
	<td class="row1"><b>{L_EMAIL_ADDRESS}: *</b></td>
	<td class="row2"><input type="hidden" name="email" value="{EMAIL}"{DISABLE_CHANGE} /><b class="genmed">{EMAIL}</b></b></td>
</tr>
<tr>
	<td class="row1"><b>{L_NEW_PASSWORD}: *</b><br /><span class="gensmall">{L_PASSWORD_IF_CHANGED}</span></td>	
	<td class="row2"><input type="hidden" name="password" value=""{DISABLE_CHANGE} /><b>******</b></td>
</tr>
<tr>
	<td class="row1"><b>{L_CONFIRM_PASSWORD}: *</b><br /><span class="gensmall">{L_PASSWORD_CONFIRM_IF_CHANGED}</span></td>
	<td class="row2"><input type="hidden" name="password_confirm" value=""{DISABLE_CHANGE} /><b>******</b></td>
</tr>
<!-- END switch_change_disallowed -->
<!-- BEGIN switch_change_allowed -->
<tr>
	<td class="row1" width="42%"><b>{L_USERNAME}: *</b></td>
	<td class="row2"><input class="post" type="text" name="username" size="35" maxlength="40" value="{USERNAME}" autocomplete="OFF"{DISABLE_CHANGE} /></td>
</tr>
<tr>
	<td class="row1"><b>{L_EMAIL_ADDRESS}: *</b></td>
	<td class="row2"><input class="post" type="text" name="email" size="35" maxlength="255" value="{EMAIL}" {DISABLE_CHANGE} /></td>
</tr>
<tr>
	<td class="row1"><b class="gen">{L_NEW_PASSWORD}: *</b><br /><span class="gensmall">{L_PASSWORD_IF_CHANGED}</span></td>
	<td class="row2"><input class="post" type="password" name="password" size="35" maxlength="32" value="" autocomplete="OFF"{DISABLE_CHANGE} /></td>
</tr>
<tr>
	<td class="row1"><b class="gen">{L_CONFIRM_PASSWORD}: *</b><br /><span class="gensmall">{L_PASSWORD_CONFIRM_IF_CHANGED}</span></td>
	<td class="row2"><input class="post" type="password" name="password_confirm" size="35" maxlength="32" value="" autocomplete="OFF"{DISABLE_CHANGE} /></td>
</tr>
<!-- END switch_change_allowed -->
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
	<td class="row1"><b>{L_USER_ACTIVE}:</b></td>
	<td class="row2"><input type="radio" name="user_status" value="1"{USER_ACTIVE_YES}{DISABLE_CHANGE} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="user_status" value="0"{USER_ACTIVE_NO}{DISABLE_CHANGE} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_ALLOW_PM}:</b></td>
	<td class="row2"><input type="radio" name="user_allowpm" value="1"{ALLOW_PM_YES}{DISABLE_CHANGE} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="user_allowpm" value="0"{ALLOW_PM_NO}{DISABLE_CHANGE} /> {L_NO}</td>
</tr>
<!-- BEGIN switch_change_disallowed -->
<tr>
	<td class="row1"><b>{L_DAILY_LIMIT}:</b><br /><span class="gensmall">{L_DAILY_LIMIT_EXPLAIN}</span></td>
	<td class="row2"><input type="hidden" name="daily_post_limit" value="{DAILY_POST_LIMIT}"{DISABLE_CHANGE} />{DAILY_POST_LIMIT}</td>
</tr>
<!-- END switch_change_disallowed -->
<!-- BEGIN switch_change_allowed -->
<tr>
	<td class="row1"><b>{L_DAILY_LIMIT}:</b><br /><span class="gensmall">{L_DAILY_LIMIT_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" name="daily_post_limit" value="{DAILY_POST_LIMIT}" size="4"{DISABLE_CHANGE} /></td>
</tr>
<!-- END switch_change_allowed -->
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
	<td class="row2"><input type="radio" name="email_validation" value="1" {EMAIL_VALIDATION_YES}{DISABLE_CHANGE} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="email_validation" value="0" {EMAIL_VALIDATION_NO}{DISABLE_CHANGE} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b><a name="approve_avatar"></a>{L_ALLOW_AVATAR}:</b></td>
	<td class="row2"><input type="radio" name="user_allowavatar" value="1"{ALLOW_AVATAR_YES}{DISABLE_CHANGE} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="user_allowavatar" value="0"{ALLOW_AVATAR_NO}{DISABLE_CHANGE} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_KICKER_BAN}:</b></td>
	<td class="row2"><input type="radio" name="kicker_ban" value="0" {KICKER_BAN_NO}{DISABLE_CHANGE} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="kicker_ban" value="1" {KICKER_BAN_YES}{DISABLE_CHANGE} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_ALLOW_PROFILE}:</b></td>
	<td class="row2"><input type="radio" name="user_allow_profile" value="1" {ALLOW_PROFILE_YES}{DISABLE_CHANGE} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="user_allow_profile" value="0" {ALLOW_PROFILE_NO}{DISABLE_CHANGE} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_ALLOW_POINTS}:</b></td>
	<td class="row2"><input type="radio" name="allow_points" value="1" {ALLOW_POINTS_YES}{DISABLE_CHANGE} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_points" value="0" {ALLOW_POINTS_NO}{DISABLE_CHANGE} /> {L_NO}</td>
</tr>
<!-- BEGIN switch_change_disallowed -->
<tr> 
	<td class="row1"><b>{L_POINTS}:</b></td>
	<td class="row2"><input type="hidden" name="points" value="{POINTS}"{DISABLE_CHANGE} />{POINTS}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_BANCARD}:</b><br /><span class="gensmall">{L_BANCARD_EXPLAIN}</span></td> 
	<td class="row2"><input type="hidden" name="user_ycard" value="{BANCARD}"{DISABLE_CHANGE} />{BANCARD}</td> 
</tr> 
<tr> 
	<td class="row1"><b>{L_VOTEBANCARD}:</b><br /><span class="gensmall">{L_VOTEBANCARD_EXPLAIN}<br /></td> 
	<td class="row2"><input type="hidden" name="user_bkcard" value="{VOTEBANCARD}"{DISABLE_CHANGE} />{VOTEBANCARD}</td> 
</tr> 
<tr> 
	<td class="row1"><b>{L_KARMA}:</b></td>
	<td class="row2"><input type="hidden" name="karma_plus" value="{KARMA_PLUS}"{DISABLE_CHANGE} />{KARMA_PLUS} <b>+ /</b> <input type="hidden" name="karma_minus" value="{KARMA_MINUS}"{DISABLE_CHANGE} />{KARMA_MINUS} <b>-</b></td>
</tr>
<!-- END switch_change_disallowed -->
<!-- BEGIN switch_change_allowed -->
<tr> 
	<td class="row1"><b>{L_POINTS}:</b></td>
	<td class="row2"><input class="post" type="text" name="points" maxlength="12" value="{POINTS}" size="15"{DISABLE_CHANGE} /></td>
</tr>
<tr> 
	<td class="row1"><b>{L_BANCARD}:</b><br /><span class="gensmall">{L_BANCARD_EXPLAIN}</span></td> 
	<td class="row2"><input class="post" type="text" style="width: 40px" name="user_ycard" size="4" maxlength="4" value="{BANCARD}"{DISABLE_CHANGE} /></td> 
</tr> 
<tr> 
	<td class="row1"><b>{L_VOTEBANCARD}:</b><br /><span class="gensmall">{L_VOTEBANCARD_EXPLAIN}<br /></td> 
	<td class="row2"><input class="post" type="text" style="width: 40px"  name="user_bkcard" size="4" maxlength="4" value="{VOTEBANCARD}"{DISABLE_CHANGE} /></td> 
</tr> 
<tr> 
	<td class="row1"><b>{L_KARMA}:</b></td>
	<td class="row2"><b><input class="post" type="text" name="karma_plus" maxlength="10" size="8" value="{KARMA_PLUS}"{DISABLE_CHANGE} /> + / <input class="post" type="text" name="karma_minus" maxlength="10" size="8" value="{KARMA_MINUS}"{DISABLE_CHANGE} /> -</b></td>
</tr>
<!-- END switch_change_allowed -->
<tr>
	<td class="row1"><b>{L_SELECT_RANK}:</b></td>
	<td class="row2"><select name="user_rank"{DISABLE_CHANGE}>{RANK_SELECT_BOX}</select></td>
</tr>
<tr>
	<th class="thSides" colspan="2">{L_PROFILE_INFO}</th>
</tr>
<tr>
	<td class="row2" colspan="2"><span class="gensmall">{L_PROFILE_INFO_NOTICE}</span></td>
</tr>
<!-- BEGIN switch_change_disallowed -->
<tr> 
	<td class="row1"><b>{L_REALNAME}:</b></td>
	<td class="row2"><input type="hidden" name="realname" value="{REALNAME}"{DISABLE_CHANGE} />{REALNAME}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_SKYPE}:</b></td>
	<td class="row2"><input type="hidden" name="skype" value="{SKYPE}"{DISABLE_CHANGE} />{SKYPE}</td>
</tr>
<tr>
	<td class="row1"><b>{L_ICQ_NUMBER}:</b></td>
	<td class="row2"><input type="hidden" name="icq" value="{ICQ}"{DISABLE_CHANGE} />{ICQ}</td>
</tr>
<tr>
	<td class="row1"><b>{L_AIM}:</b></td>
	<td class="row2"><input type="hidden" name="aim" value="{AIM}"{DISABLE_CHANGE} />{AIM}</td>
</tr>
<tr>
	<td class="row1"><b>{L_XFIRE}:</b></td>
    	<td class="row2"><input type="hidden" name="xfi" value="{XFI}"{DISABLE_CHANGE} />{XFI}</td>
</tr>
<tr>
	<td class="row1"><b>{L_MESSENGER}:</b></td>
	<td class="row2"><input type="hidden" name="msn" value="{MSN}"{DISABLE_CHANGE} />{MSN}</td>
</tr>
<tr>
	<td class="row1"><b>{L_YAHOO}:</b></td>
	<td class="row2"><input type="hidden" name="yim" value="{YIM}"{DISABLE_CHANGE} />{YIM}</td>
</tr>
<tr>
	<td class="row1"><b>{L_GTALK}:</b></td>
	<td class="row2"><input type="hidden" name="gtalk" value="{GTALK}"{DISABLE_CHANGE} />{GTALK}</td>
</tr>
<tr>
	<td class="row1"><b>{L_WEBSITE}:</b></td>
	<td class="row2"><input type="hidden" name="website" value="{WEBSITE}"{DISABLE_CHANGE} />{WEBSITE}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_STUMBLE}:</b></td>
	<td class="row2"><input type="hidden" name="stumble" value="{STUMBLE}"{DISABLE_CHANGE} />{STUMBLE}</td>
</tr>
<tr>
	<td class="row1"><b>{L_LOCATION}:</b></td>
	<td class="row2"><input type="hidden" name="location" value="{LOCATION}"{DISABLE_CHANGE} />{LOCATION}</td>
</tr>
<tr>
	<td class="row1"><b>{L_ZIPCODE}:</b><br /><span class="gensmall">{L_ZIPCODE_VIEWABLE}</span></td>
	<td class="row2"><input type="hidden" name="zipcode" value="{ZIPCODE}"{DISABLE_CHANGE}  />****</td>
</tr>
<tr>
	<td class="row1"><b>{L_FLAG}:</b></td>
	<td class="row2"><span class="gensmall"><table><tr><td>{FLAG_SELECT}&nbsp;&nbsp;&nbsp;&nbsp;</td><td><img src="../images/flags/{FLAG_START}" width="32" height="20" name="user_flag" /></td></tr></table></span></td>
</tr>
<tr>
	<td class="row1"><b>{L_OCCUPATION}:</b></td>
	<td class="row2"><input type="hidden" name="occupation" value="{OCCUPATION}"{DISABLE_CHANGE} /><span class="genmed">{OCCUPATION}</span></td>
</tr>
<tr>
	<td class="row1"><b>{L_INTERESTS}:</b></td>
	<td class="row2"><input type="hidden" name="interests" value="{INTERESTS}"{DISABLE_CHANGE} /><span class="genmed">{INTERESTS}</span></td>
</tr>
<tr>
	<td class="row1"><b>{L_SIGNATURE}:</b><br /><span class="gensmall">{L_SIGNATURE_EXPLAIN}<br /><br />
	{HTML_STATUS}<br />
	{BBCODE_STATUS}<br />
	{SMILIES_STATUS}</span></td>
	<td class="row2"><input type="hidden" name="signature"{DISABLE_CHANGE} />{SIGNATURE}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_GENDER}:</b></td> 
	<td class="row2"><input type="radio" name="gender" value="1" {GENDER_MALE_CHECKED}{DISABLE_CHANGE} /> {L_GENDER_MALE}&nbsp;&nbsp;<input type="radio" name="gender" value="2" {GENDER_FEMALE_CHECKED}{DISABLE_CHANGE} /> {L_GENDER_FEMALE}</td> 
</tr>
<tr> 
	<td class="row1"><b>{L_BIRTHDAY}:</b></td>
	<td class="row2">{S_BIRTHDAY}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_NEXT_BIRTHDAY_GREETING}:</b><br /><span class="gensmall">{L_NEXT_BIRTHDAY_GREETING_EXPLAIN}</span></td> 
	<td class="row2"><input type="hidden" name="next_birthday_greeting" value="{NEXT_BIRTHDAY_GREETING}" />{NEXT_BIRTHDAY_GREETING}</td> 
</tr> 
<!-- END switch_change_disallowed -->
<!-- BEGIN switch_change_allowed -->
<tr> 
	  <td class="row1"><b>{L_REALNAME}:</b></td>
	  <td class="row2"><input class="post" type="text" name="realname" size="35" maxlength="40" value="{REALNAME}"{DISABLE_CHANGE} /></td>
</tr>
<tr> 
	<td class="row1"><b>{L_SKYPE}:</b></td>
	<td class="row2"><input class="post" type="text" name="skype" size="35" maxlength="255" value="{SKYPE}"{DISABLE_CHANGE} /></td>
</tr>
<tr>
	<td class="row1"><b>{L_ICQ_NUMBER}:</b></td>
	<td class="row2"><input class="post" type="text" name="icq" size="35" maxlength="15" value="{ICQ}"{DISABLE_CHANGE} /></td>
</tr>
<tr>
	<td class="row1"><b>{L_AIM}:</b></td>
	<td class="row2"><input class="post" type="text" name="aim" size="35" maxlength="255" value="{AIM}"{DISABLE_CHANGE} /></td>
</tr>
<tr>
	<td class="row1"><b>{L_XFIRE}:</b></td>
    	<td class="row2"><input class="post" type="text" name="xfi" size="35" maxlength="255" value="{XFI}"{DISABLE_CHANGE} /></td>
</tr>
<tr>
	<td class="row1"><b>{L_MESSENGER}:</b></td>
	<td class="row2"><input class="post" type="text" name="msn" size="35" maxlength="255" value="{MSN}"{DISABLE_CHANGE} /></td>
</tr>
<tr>
	<td class="row1"><b>{L_YAHOO}:</b></td>
	<td class="row2"><input class="post" type="text" name="yim" size="35" maxlength="255" value="{YIM}"{DISABLE_CHANGE} /></td>
</tr>
<tr>
	<td class="row1"><b>{L_GTALK}:</b></td>
    	<td class="row2"><input class="post" type="text" name="gtalk" size="35" maxlength="255" value="{GTALK}"{DISABLE_CHANGE} /></td>
</tr>
<tr>
	<td class="row1"><b>{L_WEBSITE}:</b></td>
	<td class="row2"><input class="post" type="text" name="website" size="35" maxlength="255" value="{WEBSITE}"{DISABLE_CHANGE} /></td>
</tr>
<tr> 
	<td class="row1"><b>{L_STUMBLE}:</b></td>
	<td class="row2"><input class="post" type="text" name="stumble" size="35" maxlength="255" value="{STUMBLE}"{DISABLE_CHANGE} /></td>
</tr>
<tr>
	<td class="row1"><b>{L_LOCATION}:</b></td>
	<td class="row2"><input class="post" type="text" name="location" size="35" maxlength="100" value="{LOCATION}"{DISABLE_CHANGE} /></td>
</tr>
<tr>
	<td class="row1"><b>{L_ZIPCODE}:</b><br /><span class="gensmall">{L_ZIPCODE_VIEWABLE}</span></td>
	<td class="row2"><input class="post" type="text" name="zipcode" size="10" maxlength="10" value="{ZIPCODE}"{DISABLE_CHANGE} /></td>
</tr>
<tr>
	<td class="row1"><b>{L_FLAG}:</b></td>
	<td class="row2"><span class="gensmall"><table><tr><td>{FLAG_SELECT}&nbsp;&nbsp;&nbsp;&nbsp;</td><td><img src="../images/flags/{FLAG_START}" width="32" height="20" name="user_flag" /></td></tr></table></span></td>
</tr>
<tr>
	<td class="row1"><b>{L_OCCUPATION}:</b></td>
	<td class="row2"><input class="post" type="text" name="occupation" size="35" maxlength="100" value="{OCCUPATION}"{DISABLE_CHANGE} /></td>
</tr>
<tr>
	<td class="row1"><b>{L_INTERESTS}:</b></td>
	<td class="row2"><input class="post" type="text" name="interests" size="35" maxlength="150" value="{INTERESTS}"{DISABLE_CHANGE} /></td>
</tr>
<tr>
	<td class="row1"><b>{L_SIGNATURE}:</b><br /><span class="gensmall">{L_SIGNATURE_EXPLAIN}<br /><br />
	{HTML_STATUS}<br />
	{BBCODE_STATUS}<br />
	{SMILIES_STATUS}</span></td>
	<td class="row2"><textarea class="post" name="signature" rows="3" cols="45">{SIGNATURE}</textarea></td>
</tr>
<tr> 
	<td class="row1"><b>{L_GENDER}:</b></td> 
	<td class="row2"><input type="radio" name="gender" value="1" {GENDER_MALE_CHECKED}{DISABLE_CHANGE} /> {L_GENDER_MALE}&nbsp;&nbsp;<input type="radio" name="gender" value="2" {GENDER_FEMALE_CHECKED}{DISABLE_CHANGE} /> {L_GENDER_FEMALE}</td> 
</tr>
<tr> 
	<td class="row1"><b>{L_BIRTHDAY}:</b></td>
	<td class="row2">{S_BIRTHDAY}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_NEXT_BIRTHDAY_GREETING}:</b><br /><span class="gensmall">{L_NEXT_BIRTHDAY_GREETING_EXPLAIN}</span></td> 
	<td class="row2"><input class="post" type="text" name="next_birthday_greeting" size="5" maxlength="4" value="{NEXT_BIRTHDAY_GREETING}"{DISABLE_CHANGE} /></td> 
</tr> 
<!-- END switch_change_allowed -->
<tr>
	<th class="thSides" colspan="2">{L_PREFERENCES}</th>
</tr>
<tr>
	<td class="row1"><b>{L_PUBLIC_VIEW_EMAIL}:</b></td>
	<td class="row2"><input type="radio" name="viewemail" value="1"{VIEW_EMAIL_YES}{DISABLE_CHANGE} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="viewemail" value="0"{VIEW_EMAIL_NO}{DISABLE_CHANGE} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_HIDE_USER}:</b></td>
	<td class="row2"><input type="radio" name="hideonline" value="1"{HIDE_USER_YES}{DISABLE_CHANGE} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="hideonline" value="0"{HIDE_USER_NO}{DISABLE_CHANGE} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_NOTIFY_ON_REPLY}:</b></td>
	<td class="row2"><input type="radio" name="notifyreply" value="1"{NOTIFY_REPLY_YES}{DISABLE_CHANGE} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="notifyreply" value="0"{NOTIFY_REPLY_NO}{DISABLE_CHANGE} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_NOTIFY_ON_PRIVMSG}:</b></td>
	<td class="row2"><input type="radio" name="notifypm" value="1"{NOTIFY_PM_YES}{DISABLE_CHANGE} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="notifypm" value="0"{NOTIFY_PM_NO}{DISABLE_CHANGE} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_NOTIFY_ON_PRIVMSG_TEXT}:</b></td>
	<td class="row2"><input type="radio" name="notifypmtext" value="1" {NOTIFY_PM_TEXT_YES}{DISABLE_CHANGE} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="notifypmtext" value="0" {NOTIFY_PM_TEXT_NO}{DISABLE_CHANGE} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_POPUP_ON_PRIVMSG}:</b></td>
	<td class="row2"><input type="radio" name="popup_pm" value="1"{POPUP_PM_YES}{DISABLE_CHANGE} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="popup_pm" value="0"{POPUP_PM_NO}{DISABLE_CHANGE} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_SOUND_ON_PRIVMSG}:</b></td>
	<td class="row2"><input type="radio" name="sound_pm" value="1" {SOUND_PM_YES}{DISABLE_CHANGE} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="sound_pm" value="0" {SOUND_PM_NO}{DISABLE_CHANGE} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_PROFILE_VIEW_POPUP}:</b></td>
	<td class="row2"><input type="radio" name="profile_view_popup" value="1" {PROFILE_VIEW_POPUP_YES}{DISABLE_CHANGE} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="profile_view_popup" value="0" {PROFILE_VIEW_POPUP_NO}{DISABLE_CHANGE} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_POPUP_NOTES}:</b></td>
	<td class="row2"><input type="radio" name="popup_notes" value="1" {POPUP_NOTES_YES}{DISABLE_CHANGE} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="popup_notes" value="0" {POPUP_NOTES_NO}{DISABLE_CHANGE} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_ALWAYS_ADD_SIGNATURE}:</b></td>
	<td class="row2"><input type="radio" name="attachsig" value="1"{ALWAYS_ADD_SIGNATURE_YES}{DISABLE_CHANGE} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="attachsig" value="0"{ALWAYS_ADD_SIGNATURE_NO}{DISABLE_CHANGE} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_ALWAYS_ALLOW_BBCODE}:</b></td>
	<td class="row2"><input type="radio" name="allowbbcode" value="1"{ALWAYS_ALLOW_BBCODE_YES}{DISABLE_CHANGE} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allowbbcode" value="0"{ALWAYS_ALLOW_BBCODE_NO}{DISABLE_CHANGE} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_ALWAYS_ALLOW_HTML}:</b></td>
	<td class="row2"><input type="radio" name="allowhtml" value="1"{ALWAYS_ALLOW_HTML_YES}{DISABLE_CHANGE} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allowhtml" value="0"{ALWAYS_ALLOW_HTML_NO}{DISABLE_CHANGE} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_ALWAYS_ALLOW_SMILIES}:</b></td>
	<td class="row2"><input type="radio" name="allowsmilies" value="1"{ALWAYS_ALLOW_SMILIES_YES}{DISABLE_CHANGE} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allowsmilies" value="0"{ALWAYS_ALLOW_SMILIES_NO}{DISABLE_CHANGE} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_ALWAYS_ALLOW_SIGS}:</b></td>
	<td class="row2"><input type="radio" name="allowsigs" value="1" {ALWAYS_ALLOW_SIGS_YES}{DISABLE_CHANGE} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allowsigs" value="0" {ALWAYS_ALLOW_SIGS_NO}{DISABLE_CHANGE} /> {L_NO}</td>
</tr>
<!-- BEGIN switch_change_disallowed -->
<tr> 
	<td class="row1"><b>{L_WORD_WRAP}:</b><br /><span class="gensmall">{L_WORD_WRAP_EXPLAIN}</span></td>
	<td class="row2"><input type="hidden" name="user_wordwrap" value="{WRAP_ROW}"{DISABLE_CHANGE} />{WRAP_ROW} {L_WORD_WRAP_EXTRA}</td>
</tr>
<!-- END switch_change_disallowed -->
<!-- BEGIN switch_change_allowed -->
<tr> 
	<td class="row1"><b>{L_WORD_WRAP}:</b><br /><span class="gensmall">{L_WORD_WRAP_EXPLAIN}</span></td>
	<td class="row2"><input type="text" name="user_wordwrap" value="{WRAP_ROW}" size="4" maxlength="2" class="post"{DISABLE_CHANGE}  /> {L_WORD_WRAP_EXTRA}</td>
</tr>
<!-- END switch_change_allowed -->
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
<!-- BEGIN switch_change_disallowed -->
<tr> 
	<td class="row1"><b>{L_CUSTOM_POST_COLOR}:</b><br /><span class="gensmall">{L_CUSTOM_POST_COLOR_EXPLAIN}</span></td> 
        <td class="row2">#<input type="hidden" name="custom_post_color" value="{CUSTOM_POST_COLOR}"{DISABLE_CHANGE} />{CUSTOM_POST_COLOR}</td> 
	</tr>
<tr> 
	<td class="row1"><b>{L_MYINFO_PROFILE}:</b><br /><span class="gensmall">{L_MYINFO_PROFILE_EXPLAIN}</span><br /><br /><br /></td>
	<td class="row2"><input type="hidden" name="myInfo"{DISABLE_CHANGE}>{MYINFO}</td>
</tr>
<!-- END switch_change_disallowed -->
<!-- BEGIN switch_change_allowed -->
<tr> 
	<td class="row1"><b>{L_CUSTOM_POST_COLOR}:</b><br /><span class="gensmall">{L_CUSTOM_POST_COLOR_EXPLAIN}</span></td> 
        <td class="row2">#<input class="post" type="text" name="custom_post_color" size="8" maxlength="6" value="{CUSTOM_POST_COLOR}"{DISABLE_CHANGE} />
	<!-- BEGIN no_info_color -->
	<input class="post" type="text" size="1" style="background-color:#{CUSTOM_POST_COLOR}" disabled="yes" />
	<!-- END no_info_color -->
	<a href="javascript:cp.select(document.forms[0].custom_post_color,'pick');" name="pick" id="pick"><img src="{I_PICK_COLOR}" width="9" height="9" alt="" title="" /></a></td> 
</tr>
<tr> 
	<td class="row1"><b>{L_MYINFO_PROFILE}:</b><br /><span class="gensmall">{L_MYINFO_PROFILE_EXPLAIN}</span><br /><br /><br /></td>
	<td class="row2"><textarea name="myInfo" rows="3" cols="45" class="post">{MYINFO}</textarea></td>
</tr>
<!-- END switch_change_allowed -->
<tr>
	<th class="thSides" colspan="2">{L_AVATAR_PANEL}</th>
</tr>
	<tr align="center"> 
	<td class="row1" colspan="2"><table width="70%" cellspacing="2" cellpadding="0">
	<tr>
		<td width="65%"><span class="gensmall">{L_AVATAR_EXPLAIN}</span></td>
		<td align="center"><span class="gensmall">{L_CURRENT_IMAGE}</span><br />{AVATAR}<br /><input type="checkbox" name="avatardel"{DISABLE_CHANGE} />&nbsp;<span class="gensmall">{L_DELETE_AVATAR}</span></td>
	</tr>
	</table></td>
</tr>
<!-- BEGIN avatar_local_upload -->
<tr>
	<td class="row1"><b>{L_UPLOAD_AVATAR_FILE}:</b></td>
	<td class="row2"><input type="hidden" name="MAX_FILE_SIZE" value="{AVATAR_SIZE}" /><input type="file" name="avatar" class="post" style="width: 200px" /></td>
</tr>
<!-- END avatar_local_upload -->
<!-- BEGIN avatar_remote_upload -->
<tr>
	<td class="row1"><b>{L_UPLOAD_AVATAR_URL}:</b></td>
	<td class="row2"><input class="post" type="text" name="avatarurl" size="40" style="width: 200px" /></td>
</tr>
<!-- END avatar_remote_upload -->
<!-- BEGIN avatar_remote_link -->
<tr>
	<td class="row1"><b>{L_LINK_REMOTE_AVATAR}:</b></td>
	<td class="row2"><input class="post" type="text" name="avatarremoteurl" size="40" style="width: 200px" /></td>
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
	<td class="row2"><input type="radio" name="allowavatars" value="1" {ALWAYS_ALLOW_AVATARS_YES}{DISABLE_CHANGE} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allowavatars" value="0" {ALWAYS_ALLOW_AVATARS_NO}{DISABLE_CHANGE} /> {L_NO}</td>
</tr>
<tr>
	<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</form></table>

<script language="JavaScript" type="text/javascript">
<!--
cp.writeDiv()
//-->
</script>