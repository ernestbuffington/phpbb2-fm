<script language="JavaScript" type="text/javascript" src="templates/js/colorpicker.js"></script>
<script language="JavaScript" type="text/javascript">
<!--
var cp = new ColorPicker();

function spawn() 
{
	x=(screen.width/2)-225;
	y=(screen.height/2)-200;
	window.open('mods/weather/popup.htm','_weather','left=' + x + ',top=' + y +',screenX=x,screenY=y,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=450,height=400')
}

function clocks() 
{
	x=(screen.width/2)-225;
	y=(screen.height/2)-200;
	window.open('profile_clocks.php','_colors','left=' + x + ',top=' + y +',screenX=x,screenY=y,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=500,height=400')
}
//-->
</script>

<table width="100%" cellspacing="2" cellpadding="2" align="center"><form name="view">
<tr> 
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></td>
	<!-- BEGIN switch_user_logged_in -->
	<td align="right"><select name="user">
		<option value="{U_SEARCH_NEW}">{L_SEARCH_NEW}</option>
		<option value="{U_VIEW_RANDOM_TOPIC}">{L_VIEW_RANDOM_TOPIC}</option>
		<option value="{U_SEARCH_DAILY}">{L_VIEW_LAST_24_HOURS}</option>
		<option value="{U_SEARCH_UNANSWERED}">{L_SEARCH_UNANSWERED}</option>
	</select> <input class="liteoption" type="button" value="{L_GO}" onClick="location=document.view.user.options[document.view.user.selectedIndex].value"></td>
	<!-- END switch_user_logged_in -->
</tr>
</form></table>

<!-- BEGIN switch_cpl_menu -->
{CPL_MENU_OUTPUT}
<!-- END switch_cpl_menu -->

{ERROR_BOX}

<table cellpadding="4" cellspacing="1" width="100%" class="forumline"><form action="{S_PROFILE_ACTION}" {S_FORM_ENCTYPE} method="post" name="myform">
<!-- BEGIN switch_cpl_main -->
<tr> 
	<th class="thHead">{L_USERCP}</th>
</tr>
<tr> 
	<td class="row2" align="center"><span class="gensmall">{L_USERCP_EXPLAIN}</span></td>
</tr>
<tr>
	<td class="row1"><table cellpadding="2" cellspacing="1" align="center">
	<tr>
		<td align="right" nowrap="nowrap"><b>{L_JOINED}:</b>&nbsp;</td>
		<td width="70%">{JOINED}</td>
	</tr>
	<tr> 
		<td align="right" nowrap="nowrap"><b>{L_MEMBER_FOR}:</b>&nbsp;</td>
		<td>{MEMBER_FOR} {L_DAYS}</td>
	</tr>
	<tr>
		<td align="right" nowrap="nowrap"><b>{L_LOGON}:</b>&nbsp;</td>
		<td>{LAST_LOGON}</td>
	</tr>
	<tr>
		<td align="right" nowrap="nowrap"><b>{L_IP_ADDRESS}:</b>&nbsp;</td>
		<td><a href="http://nwtools.com/default.asp?host={IP_ADDRESS}" target="_blank" class="genmed">{IP_ADDRESS}</a> ({IP_HOST})</td>
	</tr>
	<tr>
		<td align="right" nowrap="nowrap"><b>{L_POSTS}:</b>&nbsp;</td>
		<td>{POSTS} | <a href="{U_SEARCH_POSTS}" class="genmed">{L_SEARCH_SELF}</a> | <a href="{U_SEARCH_TOPICS}" class="genmed">{L_SEARCH_TOPICS}</a></td>
	</tr>
	<!-- BEGIN switch_bank_on -->
	<tr>
		<td align="right" nowrap="nowrap"><b>{switch_bank_on.L_BANK_BALANCE}:</b>&nbsp;</td>
		<td><a href="{switch_bank_on.U_BANK}" class="genmed">{switch_bank_on.BANK_BALANCE}</a></td>
	</tr>
	<!-- END switch_bank_on -->
	<!-- BEGIN switch_referral_on -->
	<tr>
		<td align="right" nowrap="nowrap"><b>{switch_referral_on.L_REFERRAL}:</b>&nbsp;</td>
		<td>{switch_referral_on.U_REFERRAL}</td>
	</tr>
	<!-- END switch_referral_on -->
	</table></td>
</tr>
<tr> 
	<td class="catBottom" colspan="2">&nbsp;</td>
</tr>
<!-- END switch_cpl_main -->
<!-- BEGIN switch_cpl_reg_info -->
<tr> 
	<th class="thHead" colspan="2">{L_REGISTRATION_INFO}</th>
</tr>
<tr> 
	<td class="row2" colspan="2"><span class="gensmall">{L_ITEMS_REQUIRED}</span></td>
</tr>
<!-- BEGIN switch_namechange_disallowed -->
<tr> 
	<td class="row1" width="50%"><b class="gen">{L_USERNAME}: *</b></td>
	<td class="row2"><input type="hidden" name="username" value="{USERNAME}" /><span class="gen"><b>{USERNAME}</b></span></td>
</tr>
<!-- END switch_namechange_disallowed -->
<!-- BEGIN switch_namechange_allowed -->
<tr> 
	<td class="row1" width="38%"><b class="gen">{L_USERNAME}: *</b><br /><span class="gensmall">{L_LIMIT_USERNAME_LENGTH_EXPLAIN}</span></td>
	<td class="row2"><input type="text" class="post" style="width: 200px" name="username" size="25" maxlength="{LIMIT_USERNAME_MAX_LENGTH}" {USERNAME_ONKEY}value="{USERNAME}" />{USERNAME_CHECK}</td>
</tr>
<!-- END switch_namechange_allowed -->
<tr> 
	<td class="row1"><b class="gen">{L_EMAIL_ADDRESS}: *</b></td>
	<td class="row2"><input type="text" class="post" style="width: 200px" name="email" size="25" maxlength="255" value="{EMAIL}" /></td>
</tr>
<tr> 
  	<td class="row1"><b class="gen">{L_CURRENT_PASSWORD}: *</b><br /><span class="gensmall">{L_CONFIRM_PASSWORD_EXPLAIN}</span></td>
  	<td class="row2"><input type="password" class="post" style="width: 200px" name="cur_password" size="25" maxlength="32" value="{CUR_PASSWORD}" /></td>
</tr>
<tr> 
  	<td class="row1"><b class="gen">{L_NEW_PASSWORD}: *</b><br /><span class="gensmall">{L_PASSWORD_IF_CHANGED}</span></td>
  	<td class="row2"><input type="password" class="post" style="width: 200px" name="new_password" size="25" maxlength="32" {PASSWORD_ONKEY}value="{NEW_PASSWORD}" /></td>
</tr>
<tr> 
  	<td class="row1"><b class="gen">{L_CONFIRM_PASSWORD}: * </b><br /><span class="gensmall">{L_PASSWORD_CONFIRM_IF_CHANGED}</span></td>
  	<td class="row2"><input type="password" class="post" style="width: 200px" name="password_confirm" size="25" maxlength="32" {PASSWORD_ONKEY}value="{PASSWORD_CONFIRM}" />{PASSWORD_CHECK}</td>
</tr>
<!-- END switch_cpl_reg_info -->
<!-- BEGIN switch_cpl_profile_info -->
<tr> 
	<th class="thHead" colspan="2">{L_PROFILE_INFO}</th>
</tr>
<tr> 
	<td class="row2" colspan="2"><span class="gensmall">{L_ITEMS_REQUIRED} {L_PROFILE_INFO_NOTICE}</span></td>
</tr>
<!-- BEGIN xdata -->
<!-- BEGIN switch_is_realname -->
<tr> 
	<td class="row1" width="50%"><b class="gen">{L_REALNAME}:</b></td>
	<td class="row2"><input type="text" name="realname" class="post" style="width: 200px" size="25" maxlength="50" value="{REALNAME}" /></td>
</tr>
<!-- END switch_is_realname -->
<!-- BEGIN switch_is_skype -->
<tr> 
	<td class="row1"><b class="gen">{L_SKYPE}:</b></td>
	<td class="row2"><input type="text" class="post" style="width: 200px" name="skype" size="25" maxlength="255" value="{SKYPE}" /></td>
</tr>
<!-- END switch_is_skype -->
<!-- BEGIN switch_is_icq -->
<tr> 
	<td class="row1"><b class="gen">{L_ICQ_NUMBER}:</b></td>
	<td class="row2"><input type="text" name="icq" class="post" style="width: 200px" size="25" maxlength="15" value="{ICQ}" /></td>
</tr>
<!-- END switch_is_icq -->
<!-- BEGIN switch_is_aim -->
<tr> 
	<td class="row1"><b class="gen">{L_AIM}:</b></td>
	<td class="row2"><input type="text" class="post" style="width: 200px" name="aim" size="25" maxlength="255" value="{AIM}" /></td>
</tr>
<!-- END switch_is_aim -->
<!-- BEGIN switch_is_xfi -->
<tr>
	<td class="row1"><b class="gen">{L_XFIRE}:</b></td>
	<td class="row2"><input type="text" class="post" style="width: 200px" name="xfi" size="20" maxlength="255" value="{XFI}" /></td>
</tr>
<!-- END switch_is_xfi -->
<!-- BEGIN switch_is_msn -->
<tr> 
	<td class="row1"><b class="gen">{L_MESSENGER}:</b></td>
	<td class="row2"><input type="text" class="post" style="width: 200px" name="msn" size="25" maxlength="255" value="{MSN}" /></td>
</tr>
<!-- END switch_is_msn -->
<!-- BEGIN switch_is_yim -->
<tr> 
	<td class="row1"><b class="gen">{L_YAHOO}:</b></td>
	<td class="row2"><input type="text" class="post" style="width: 200px" name="yim" size="25" maxlength="255" value="{YIM}" /></td>
</tr>
<!-- END switch_is_yim -->
<!-- BEGIN switch_is_gtalk -->
<tr>
	<td class="row1"><b class="gen">{L_GTALK}:</b></td>
	<td class="row2"><input type="text" class="post" style="width: 200px" name="gtalk" size="25" maxlength="255" value="{GTALK}" /></td>
</tr>
<!-- END switch_is_gtalk -->
<!-- BEGIN switch_is_website -->
<tr> 
	<td class="row1"><b class="gen">{L_WEBSITE}:</b></td>
	<td class="row2"><input type="text" class="post" style="width: 200px" name="website" size="25" maxlength="255" value="{WEBSITE}" /></td>
</tr>
<!-- END switch_is_website -->
<!-- BEGIN switch_is_stumble -->
<tr> 
	<td class="row1"><b class="gen">{L_STUMBLE}:</b></td>
	<td class="row2"><input type="text" class="post" style="width: 200px" name="stumble" size="25" maxlength="255" value="{STUMBLE}" /></td>
</tr>
<!-- END switch_is_stumble -->
<!-- BEGIN switch_is_location -->
<tr> 
	<td class="row1"><b class="gen">{L_LOCATION}:</b></td>
	<td class="row2"><input type="text" class="post" style="width: 200px" name="location" size="25" maxlength="100" value="{LOCATION}" /></td>
</tr>
<!-- END switch_is_location -->
<!-- BEGIN switch_is_zipcode -->
<tr>
	<td class="row1"><b class="gen">{L_ZIPCODE}:</b><br /><span class="gensmall">{L_ZIPCODE_VIEWABLE}</span></td>
	<td class="row2"><input class="post" type="text" style="width: 50px" name="zipcode" size="5" maxlength="4" value="{ZIPCODE}" /></td>
</tr>
<!-- END switch_is_zipcode -->
<!-- BEGIN switch_is_flag -->
<tr>
	<td class="row1"><b class="gen">{L_FLAG}:</b></td>
	<td class="row2"><table>
	<tr>
		<td>{FLAG_SELECT}&nbsp;&nbsp;&nbsp;&nbsp;</td>
	  	<td><img src="images/flags/{FLAG_START}" width="32" height="20" name="user_flag" alt="" title="" /></td>
	</tr>
	</table></td>
</tr>
<!-- END switch_is_flag -->
<!-- BEGIN switch_is_occupation -->
<tr> 
	<td class="row1"><b class="gen">{L_OCCUPATION}:</b></td>
	<td class="row2"><textarea name="occupation" style="width: 200px" rows="3" cols="30" class="post">{OCCUPATION}</textarea></td>
</tr>
<!-- END switch_is_occupation -->
<!-- BEGIN switch_is_interests -->
<tr> 
	<td class="row1"><b class="gen">{L_INTERESTS}:</b></td>
	<td class="row2"><textarea name="interests" style="width: 200px" rows="3" cols="30" class="post">{INTERESTS}</textarea></td>
</tr>  
<!-- END switch_is_interests -->
<!-- BEGIN switch_myInfo_active -->
<tr> 
	<td class="row1"><b class="gen">{L_MYINFO_PROFILE}:</b><br /><span class="gensmall">{L_MYINFO_PROFILE_EXPLAIN}</span><br /><br /><br /></td>
	<td class="row2"><textarea name="myInfo" style="width: 200px" rows="3" cols="30" class="post">{MYINFO}</textarea></td>
</tr>
<!-- END switch_myInfo_active -->
<!-- BEGIN switch_is_gender -->
<tr> 
	<td class="row1"><b class="gen">{L_GENDER}:{GENDER_REQUIRED}</b></td> 
        <td class="row2">
        <input type="radio" name="gender" value="1" {GENDER_MALE_CHECKED} /> 
	<span class="gen">{L_GENDER_MALE}</span>&nbsp;&nbsp; 
	<input type="radio" name="gender" value="2" {GENDER_FEMALE_CHECKED} /> 
	<span class="gen">{L_GENDER_FEMALE}</span></td> 
</tr> 
<!-- END switch_is_gender -->
<!-- BEGIN switch_is_bday -->
<tr> 
	<td class="row1"><b class="gen">{L_BIRTHDAY}:{BIRTHDAY_REQUIRED}</b><br /><span class="gensmall">{L_BIRTHDAY_EXPLAIN}</span></td> 
	<td class="row2"><span class="gen">{S_BIRTHDAY}</span></td> 
</tr>
<!-- END switch_is_bday -->
<!-- BEGIN switch_type_text -->
<tr>
	<td class="row1"><b class="gen">{xdata.NAME}:</b><br /><span class="gensmall">{xdata.DESCRIPTION}</span></td>
	<td class="row2"><input type="text" class="post" style="width: 200px" name="{xdata.CODE_NAME}" size="35" maxlength="{xdata.MAX_LENGTH}" value="{xdata.VALUE}" /></td>
</tr>
<!-- END switch_type_text -->
<!-- BEGIN switch_type_textarea -->
<tr>
	<td class="row1"><b class="gen">{xdata.NAME}:</b><br /><span class="gensmall">{xdata.DESCRIPTION}</span></td>
	<td class="row2"><textarea name="{xdata.CODE_NAME}" style="width: 300px" rows="6" cols="30" class="post">{xdata.VALUE}</textarea></td>
</tr>
<!-- END switch_type_textarea -->
<!-- BEGIN switch_type_select -->
<tr>
	<td class="row1"><b class="gen">{xdata.NAME}:</b><br /><span class="gensmall">{xdata.DESCRIPTION}</span></td>
	<td class="row2"><select name="{xdata.CODE_NAME}">
	<!-- BEGIN options -->
	<option value="{xdata.switch_type_select.options.OPTION}" title="{xdata.switch_type_select.options.OPTION}" {xdata.switch_type_select.options.SELECTED}>{xdata.switch_type_select.options.OPTION}</option>
	<!-- END options -->
	</select></td>
</tr>
<!-- END switch_type_select -->
<!-- BEGIN switch_type_radio -->
<tr>
	<td class="row1"><b class="gen">{xdata.NAME}:</b><br /><span class="gensmall">{xdata.DESCRIPTION}</span></td>
	<td class="row2">
    	<!-- BEGIN options -->
	<input type="radio" name="{xdata.CODE_NAME}" value="{xdata.switch_type_radio.options.OPTION}" {xdata.switch_type_radio.options.CHECKED} />
	<span class="gen">{xdata.switch_type_radio.options.OPTION}</span>&nbsp;&nbsp;
	<!-- END options -->
	</td>
</tr>
<!-- END switch_type_radio -->
<!-- END xdata -->
<!-- END switch_cpl_profile_info -->
<!-- BEGIN switch_cpl_preferences -->
<tr> 
	<th class="thHead" colspan="2">{L_PREFERENCES}</th>
</tr>
<tr> 
	<td class="row1" width="50%"><b class="gen">{L_PUBLIC_VIEW_EMAIL}:</b></td>
	<td class="row2"> 
	<input type="radio" name="viewemail" value="1" {VIEW_EMAIL_YES} />
	<span class="gen">{L_YES}</span>&nbsp;&nbsp; 
	<input type="radio" name="viewemail" value="0" {VIEW_EMAIL_NO} />
	<span class="gen">{L_NO}</span></td>
</tr>
<tr> 
	<td class="row1"><b class="gen">{L_HIDE_USER}:</b></td>
	<td class="row2"> 
	<input type="radio" name="hideonline" value="1" {HIDE_USER_YES} />
	<span class="gen">{L_YES}</span>&nbsp;&nbsp; 
	<input type="radio" name="hideonline" value="0" {HIDE_USER_NO} />
	<span class="gen">{L_NO}</span></td>
</tr>
<!-- BEGIN switch_can_disable_mass_pm --> 
<tr> 
	<td class="row1"><b class="gen">{L_ENABLE_MASS_PM}:</b><br /><span class="gensmall">{L_ENABLE_MASS_PM_EXPLAIN}</span></td>
	<td class="row2"> 
	<input type="radio" name="allow_mass_pm" value="4" {ALLOW_MASS_PM_NOTIFY_CHECKED}/> 
	<span class="gen">{L_YES}</span>&nbsp;&nbsp;
	<input type="radio" name="allow_mass_pm" value="2" {ALLOW_MASS_PM_CHECKED}/> 
	<span class="gen">{L_NO}</span>&nbsp;&nbsp; 
	<input type="radio" name="allow_mass_pm" value="0" {DISABLE_MASS_PM_CHECKED}/> 
	<span class="gen">{L_NO_MASS_PM}</span></td>  
</tr>
<!-- END switch_can_disable_mass_pm --> 
<!-- BEGIN switch_can_not_disable_mass_pm --> 
<tr> 
	<td class="row1"><b class="gen">{L_ENABLE_MASS_PM}:</b><br /><span class="gensmall">{L_ENABLE_MASS_PM_EXPLAIN}</span></td> 
	<td class="row2"> 
	<input type="radio" name="allow_mass_pm" value="4" {ALLOW_MASS_PM_NOTIFY_CHECKED}/> 
	<span class="gen">{L_YES}</span>&nbsp;&nbsp; 
	<input type="radio" name="allow_mass_pm" value="2" {ALLOW_MASS_PM_CHECKED}/> 
	<span class="gen">{L_NO}</span></td> 
</tr>
<!-- END switch_can_not_disable_mass_pm --> 
<!-- BEGIN switch_color_groups -->
<tr> 
	<td class="row1"><b class="gen">{L_GROUP_PRIORITY}:</b></td>
	<td class="row2">{GROUP_PRIORITY_SELECT}</td>
</tr>
<!-- END switch_color_groups -->
<tr> 
	<td class="row1"><b class="gen">{L_POPUP_ON_PRIVMSG}:</b></td>
	<td class="row2"> 
	<input type="radio" name="popup_pm" value="1" {POPUP_PM_YES} />
	<span class="gen">{L_YES}</span>&nbsp;&nbsp; 
	<input type="radio" name="popup_pm" value="0" {POPUP_PM_NO} />
	<span class="gen">{L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><b class="gen">{L_SOUND_ON_PRIVMSG}:</b></td>
	<td class="row2">
	<input type="radio" name="sound_pm" value="1" {SOUND_PM_YES} />
	<span class="gen">{L_YES}</span>&nbsp;&nbsp;
	<input type="radio" name="sound_pm" value="0" {SOUND_PM_NO} />
	<span class="gen">{L_NO}</span></td>
</tr>
<tr> 
	<td class="row1"><b class="gen">{L_NOTIFY_ON_PRIVMSG}:</b></td>
	<td class="row2"> 
	<input type="radio" name="notifypm" value="1" {NOTIFY_PM_YES} />
	<span class="gen">{L_YES}</span>&nbsp;&nbsp; 
	<input type="radio" name="notifypm" value="0" {NOTIFY_PM_NO} />
	<span class="gen">{L_NO}</span></td>
</tr>
<tr> 
	<td class="row1"><b class="gen">{L_NOTIFY_ON_PRIVMSG_TEXT}:</b></td>
	<td class="row2"> 
	<input type="radio" name="notifypmtext" value="1" {NOTIFY_PM_TEXT_YES} />
	<span class="gen">{L_YES}</span>&nbsp;&nbsp; 
	<input type="radio" name="notifypmtext" value="0" {NOTIFY_PM_TEXT_NO} />
	<span class="gen">{L_NO}</span></td>
</tr>
<tr> 
	<td class="row1"><b class="gen">{L_NOTIFY_DONATION}:</b><br /><span class="gensmall">{L_NOTIFY_DONATION_EXPLAIN}</span></td>
	<td class="row2"> 
	<input type="radio" name="notifydonation" value="1" {NOTIFY_DONATION_YES} />
	<span class="gen">{L_YES}</span>&nbsp;&nbsp; 
	<input type="radio" name="notifydonation" value="0" {NOTIFY_DONATION_NO} />
	<span class="gen">{L_NO}</span></td>
</tr>
<tr> 
	<td class="row1"><b class="gen">{L_NOTIFY_ON_REPLY}:</b><br /><span class="gensmall">{L_NOTIFY_ON_REPLY_EXPLAIN}</span></td>
	<td class="row2"> 
	<input type="radio" name="notifyreply" value="1" {NOTIFY_REPLY_YES} />
	<span class="gen">{L_YES}</span>&nbsp;&nbsp; 
	<input type="radio" name="notifyreply" value="0" {NOTIFY_REPLY_NO} />
	<span class="gen">{L_NO}</span></td>
</tr>
<tr> 
	<td class="row1"><b class="gen">{L_TOPIC_MOVED_MAIL}:</b></td>
	<td class="row2"> 
	<input type="radio" name="topic_moved_mail" value="1" {TOPIC_MOVED_MAIL_YES} />
	<span class="gen">{L_YES}</span>&nbsp;&nbsp; 
	<input type="radio" name="topic_moved_mail" value="0" {TOPIC_MOVED_MAIL_NO} />
	<span class="gen">{L_NO}</span></td>
</tr>
<tr> 
	<td class="row1"><b class="gen">{L_TOPIC_MOVED_PM}:</b></td>
	<td class="row2"> 
	<input type="radio" name="topic_moved_pm" value="1" {TOPIC_MOVED_PM_YES} />
	<span class="gen">{L_YES}</span>&nbsp;&nbsp; 
	<input type="radio" name="topic_moved_pm" value="0" {TOPIC_MOVED_PM_NO} />
	<span class="gen">{L_NO}</span></td>
</tr>
<tr> 
	<td class="row1"><b class="gen">{L_TOPIC_MOVED_PM_NOTIFY}:</b><br /><span class="gensmall">{L_TOPIC_MOVED_PM_NOTIFY_EXPLAIN}</span></td>
	<td class="row2"> 
	<input type="radio" name="topic_moved_pm_notify" value="1" {TOPIC_MOVED_PM_NOTIFY_YES} />
	<span class="gen">{L_YES}</span>&nbsp;&nbsp; 
	<input type="radio" name="topic_moved_pm_notify" value="0" {TOPIC_MOVED_PM_NOTIFY_NO} />
	<span class="gen">{L_NO}</span></td>
</tr>
<tr> 
	<td class="row1"><b class="gen">{L_PROFILE_VIEW_POPUP}:</b></td>
	<td class="row2"> 
	<input type="radio" name="profile_view_popup" value="1" {PROFILE_VIEW_POPUP_YES} />
	<span class="gen">{L_YES}</span>&nbsp;&nbsp; 
	<input type="radio" name="profile_view_popup" value="0" {PROFILE_VIEW_POPUP_NO} />
	<span class="gen">{L_NO}</span></td>
</tr>
<!-- BEGIN switch_notes -->
<tr> 
	<td class="row1"><b class="gen">{L_POPUP_NOTES}:</b></td>
	<td class="row2"> 
	<input type="radio" name="popup_notes" value="1" {POPUP_NOTES_YES} />
	<span class="gen">{L_YES}</span>&nbsp;&nbsp; 
	<input type="radio" name="popup_notes" value="0" {POPUP_NOTES_NO} />
	<span class="gen">{L_NO}</span></td>
</tr>
<!-- END switch_notes -->
<tr> 
	<td class="row1"><b class="gen">{L_ALWAYS_ALLOW_BBCODE}:</b></td>
	<td class="row2"> 
	<input type="radio" name="allowbbcode" value="1" {ALWAYS_ALLOW_BBCODE_YES} />
	<span class="gen">{L_YES}</span>&nbsp;&nbsp; 
	<input type="radio" name="allowbbcode" value="0" {ALWAYS_ALLOW_BBCODE_NO} />
	<span class="gen">{L_NO}</span></td>
</tr>
<tr> 
	<td class="row1"><b class="gen">{L_ALWAYS_ALLOW_HTML}:</b></td>
	<td class="row2"> 
	<input type="radio" name="allowhtml" value="1" {ALWAYS_ALLOW_HTML_YES} />
	<span class="gen">{L_YES}</span>&nbsp;&nbsp; 
	<input type="radio" name="allowhtml" value="0" {ALWAYS_ALLOW_HTML_NO} />
	<span class="gen">{L_NO}</span></td>
</tr>
<tr> 
	<td class="row1"><b class="gen">{L_ALWAYS_ALLOW_SMILIES}:</b></td>
	<td class="row2"> 
	<input type="radio" name="allowsmilies" value="1" {ALWAYS_ALLOW_SMILIES_YES} />
	<span class="gen">{L_YES}</span>&nbsp;&nbsp; 
	<input type="radio" name="allowsmilies" value="0" {ALWAYS_ALLOW_SMILIES_NO} />
	<span class="gen">{L_NO}</span></td>
</tr>
<!-- BEGIN switch_swearywords -->
<tr> 
	<td class="row1"><b class="gen">{L_ALWAYS_ALLOW_SWEARYWORDS}:</b></td> 
 	<td class="row2"> 
	<input type="radio" name="allowswearywords" value="0" {ALWAYS_ALLOW_SWEARYWORDS_NO} /> 
	<span class="gen">{L_YES}</span>&nbsp;&nbsp;
   	<input type="radio" name="allowswearywords" value="1" {ALWAYS_ALLOW_SWEARYWORDS_YES} /> 
	<span class="gen">{L_NO}</span></td> 
</tr> 
<!-- END switch_swearywords -->
<tr> 
	<td class="row1"><b class="gen">{L_ALWAYS_ADD_SIGNATURE}:</b></td>
	<td class="row2"> 
	<input type="radio" name="attachsig" value="1" {ALWAYS_ADD_SIGNATURE_YES} />
	<span class="gen">{L_YES}</span>&nbsp;&nbsp; 
	<input type="radio" name="attachsig" value="0" {ALWAYS_ADD_SIGNATURE_NO} />
	<span class="gen">{L_NO}</span></td>
</tr>
<tr> 
	<td class="row1"><b class="gen">{L_RETRO_SIG}:</b><br /><span class="gensmall">{L_RETRO_SIG_EXPLAIN}</span></td>
	<td class="row2"><input type="checkbox" name="retrosig" /> <span class="gen">{L_YES}</span></td>
</tr> 
<tr> 
	<td class="row1"><b class="gen">{L_ALWAYS_ALLOW_SIGS}:</b></td> 
	<td class="row2"> 
	<input type="radio" name="showsigs" value="1" {SHOW_SIGS_YES} /> 
	<span class="gen">{L_YES}</span>&nbsp;&nbsp; 
	<input type="radio" name="showsigs" value="0" {SHOW_SIGS_NO} /> 
	<span class="gen">{L_NO}</span></td> 
</tr> 
<tr>
	<td class="row1" width="50%"><b class="gen">{L_SHOW_AVATARS}:</b></td>
      	<td class="row2">
      	<input type="radio" name="showavatars" value="1" {SHOW_AVATARS_YES} /> 
      	<span class="gen">{L_YES}</span>&nbsp;&nbsp;
      	<input type="radio" name="showavatars" value="0" {SHOW_AVATARS_NO} />
      	<span class="gen">{L_NO}</span></td>
</tr>
<!-- BEGIN switch_avatar_sticky -->
<tr> 
	<td class="row1" width="50%"><b class="gen">{L_AVATAR_STICKY}:</b><br /><span class="gensmall">{L_AVATAR_STICKY_EXPLAIN}</span></td>
	<td class="row2"> 
	<input type="radio" name="avatar_sticky" value="1" {AVATAR_STICKY_YES} />
	<span class="gen">{L_YES}</span>&nbsp;&nbsp; 
	<input type="radio" name="avatar_sticky" value="0" {AVATAR_STICKY_NO} />
	<span class="gen">{L_NO}</span></td>
</tr>
<!-- END switch_avatar_sticky -->	

<!-- BEGIN switch_transition -->
<tr> 
	<td class="row1"><b class="gen">{L_ALWAYS_ALLOW_TRANSITION}:</b></td> 
 	<td class="row2"> 
	<input type="radio" name="user_transition" value="1" {ALWAYS_ALLOW_TRANSITION_YES} /> 
	<span class="gen">{L_YES}</span>&nbsp;&nbsp;
   	<input type="radio" name="user_transition" value="0" {ALWAYS_ALLOW_TRANSITION_NO} /> 
	<span class="gen">{L_NO}</span></td> 
</tr> 
<!-- END switch_transition -->
<!-- BEGIN force_word_wrapping -->
<tr> 
  	<td class="row1"><b class="gen">{L_WORD_WRAP}:</b><br /><span class="gensmall">{L_WORD_WRAP_EXPLAIN}</span></td>
  	<td class="row2"><input type="text" name="user_wordwrap" value="{WRAP_ROW}" size="4" maxlength="2" class="post" /> {L_WORD_WRAP_EXTRA}</td>
</tr>
<!-- END force_word_wrapping -->
<tr> 
	<td class="row1"><b class="gen">{L_BOARD_LANGUAGE}:</b></td>
	<td class="row2">{LANGUAGE_SELECT}</td>
</tr>
<!-- BEGIN override_user_style_block -->
<tr> 
	<td class="row1"><b class="gen">{L_BOARD_STYLE}:</b></td>
	<td class="row2">{STYLE_SELECT}</td>
</tr>
<!-- END override_user_style_block -->
<tr> 
	<td class="row1"><b class="gen">{L_TIMEZONE}:</b></td>
	<td class="row2">{TIMEZONE_SELECT}</td>
</tr>
<tr> 
	<td class="row1"><b class="gen">{L_DATE_FORMAT}:</b></td>
	<td class="row2">{DATE_FORMAT_SELECT}</td>
</tr>
<tr> 
	<td class="row1"><b class="gen">{L_CLOCK_FORMAT}:</b><br /><span class="gensmall">{L_CLOCK_FORMAT_EXPLAIN}</span></td>
	<td class="row2">{CLOCK_FORMAT_SELECT}</td>
</tr>
<!-- BEGIN switch_custom_post_color -->
<tr> 
	<td class="row1"><b class="gen">{switch_custom_post_color.L_CUSTOM_POST_COLOR}:</b><br /><span class="gensmall">{switch_custom_post_color.L_CUSTOM_POST_COLOR_EXPLAIN}</span></td> 
        <td class="row2">#<input type="text" class="post" name="custom_post_color" size="8" maxlength="6" value="{switch_custom_post_color.CUSTOM_POST_COLOR}" />
	<!-- BEGIN no_info_color -->
	<input class="post" type="text" size="1" style="background-color: #{switch_custom_post_color.no_info_color.CUSTOM_POST_COLOR}" title="{switch_custom_post_color.no_info_color.CUSTOM_POST_COLOR}" disabled="yes" />
	<!-- END no_info_color -->
	<a href="javascript:cp.select(document.myform.custom_post_color,'pick');" name="pick" id="pick"><img src="{switch_custom_post_color.I_PICK_COLOR}" width="9" height="9" alt="" title="" /></a>
	</td> 
</tr> 
<!-- END switch_custom_post_color -->
<!-- BEGIN switch_chat_commands -->
<tr> 
	<td class="row1"><b class="gen">{L_IRC_COMMANDS}:</b><br /><span class="gensmall">{L_IRC_EXPLAIN}</span></td>
	<td class="row2"><textarea name="irc_commands" style="width: 200px" rows="3" cols="30" class="post">{IRC_COMMANDS}</textarea></td>
</tr>
<!-- END switch_chat_commands -->
<!-- END switch_cpl_preferences -->
<!-- BEGIN switch_cpl_avatar -->
<tr> 
	<th class="thHead" colspan="2">{L_AVATAR_PANEL}</th>
</tr>	
<!-- BEGIN switch_avatar_block -->
<!-- BEGIN switch_avatar_posts_block -->
<tr>
	<td class="row1" colspan="2" style="text-align:center;padding:10px;"><span class="gensmall">{L_NO_AVATAR_POSTS}</span></td>
</tr>
<!-- END switch_avatar_posts_block -->
<tr> 
	<td class="row1" colspan="2"><table width="70%" cellspacing="2" cellpadding="0" align="center">
	<tr> 
		<td width="65%"><span class="gensmall">{L_AVATAR_EXPLAIN}</span></td>
		<td align="center"><span class="gensmall">{L_CURRENT_IMAGE}</span><br />{AVATAR}<br /><input type="checkbox" name="avatardel" />&nbsp;<span class="gensmall">{L_DELETE_AVATAR}</span></td>
	</tr>
	</table></td>
</tr>
<!-- BEGIN switch_avatar_local_upload -->
<tr> 
	<td class="row1" width="50%"><b class="gen">{L_UPLOAD_AVATAR_FILE}:</b></td>
	<td class="row2"><input type="file" name="avatar" class="post" style="width: 200px" /></td>
</tr>
<!-- END switch_avatar_local_upload -->
<!-- BEGIN switch_avatar_remote_upload -->
<tr> 
	<td class="row1" width="50%"><b class="gen">{L_UPLOAD_AVATAR_URL}:</b><br /><span class="gensmall">{L_UPLOAD_AVATAR_URL_EXPLAIN}</span></td>
	<td class="row2"><input type="text" name="avatarurl" size="40" class="post" style="width: 200px" /></td>
</tr>
<!-- END switch_avatar_remote_upload -->
<!-- BEGIN switch_avatar_remote_link -->
<tr> 
	<td class="row1" width="50%"><b class="gen">{L_LINK_REMOTE_AVATAR}:</b><br /><span class="gensmall">{L_LINK_REMOTE_AVATAR_EXPLAIN}</span></td>
	<td class="row2"><input type="text" name="avatarremoteurl" size="40" class="post" style="width: 200px" /></td>
</tr>
<!-- END switch_avatar_remote_link -->
<!-- BEGIN switch_avatar_local_gallery -->
<tr> 
	<td class="row1" width="50%"><b class="gen">{L_AVATAR_GALLERY}:</b></td>
	<td class="row2"><input type="submit" name="avatargallery" value="{L_SHOW_GALLERY}" class="liteoption" /></td>
</tr>
<!-- END switch_avatar_local_gallery -->
<!-- BEGIN switch_avatar_generator -->
<tr> 
	<td class="row1" width="50%"><b class="gen">{L_GENERATE_AVATAR}:</b></td>
	<td class="row2"><input type="submit" name="avatargenerator" value="{L_AVATAR_GENERATOR}" class="liteoption" /></td>
</tr>
<!-- END switch_avatar_generator -->
<!-- BEGIN switch_avatar_sticky -->
<tr> 
	<td class="row1" width="50%"><b class="gen">{L_AVATAR_STICKY}:</b><br /><span class="gensmall">{L_AVATAR_STICKY_EXPLAIN}</span></td>
	<td class="row2"> 
	<input type="radio" name="avatar_sticky" value="1" {AVATAR_STICKY_YES} />
	<span class="gen">{L_YES}</span>&nbsp;&nbsp; 
	<input type="radio" name="avatar_sticky" value="0" {AVATAR_STICKY_NO} />
	<span class="gen">{L_NO}</span></td>
</tr>
<!-- END switch_avatar_sticky -->	
<!-- END switch_avatar_block -->
<tr>
	<td class="row1" width="50%"><b class="gen">{L_SHOW_AVATARS}:</b></td>
      	<td class="row2">
      	<input type="radio" name="showavatars" value="1" {SHOW_AVATARS_YES} /> 
      	<span class="gen">{L_YES}</span>&nbsp;&nbsp;
      	<input type="radio" name="showavatars" value="0" {SHOW_AVATARS_NO} />
      	<span class="gen">{L_NO}</span></td>
</tr>
<!-- END switch_cpl_avatar -->
<!-- BEGIN switch_cpl_photo -->
{PHOTO_BOX}
<!-- END switch_cpl_photo -->
<!-- BEGIN switch_cpl_foot -->
<tr>
	<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" name="reset" class="liteoption" /></td>
</tr>
<!-- END switch_cpl_foot -->
</form></table>	

<script language="JavaScript" type="text/javascript">
<!--
cp.writeDiv()
//-->
</script>

<!-- BEGIN switch_cpl_menu -->
	</td>
  </tr>
</table>
<br />
<!-- END switch_cpl_menu -->

<table width="100%" cellspacing="2" align="center"> 
<tr> 
	<td align="right" valign="middle" nowrap>{JUMPBOX}</td> 
</tr> 
</table>

