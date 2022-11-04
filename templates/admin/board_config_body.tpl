{CONFIG_MENU}{USERCOM_MENU}{SERVER_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<script language="JavaScript" type="text/javascript">
<!--
function clocks() 
{
	x=(screen.width/2)-225;
	y=(screen.height/2)-200;
	window.open('../profile_clocks.php','_colors','left=' + x + ',top=' + y +',screenX=x,screenY=y,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=500,height=400')
}

function setValue(textObj, chkObj, viewlevel) 
{
	var chkObjValue = chkObj.checked
        var txtObjValue = parseInt(textObj.value)
                
        if (chkObjValue == true) 
	{
        	textObj.value = txtObjValue + viewlevel
        } 
        else 
	{
        	textObj.value = txtObjValue - viewlevel
        } 
}
        
function selectContents(fieldObj) 
{
	fieldObj.select();
}

//-->
</script>

<h1>{L_PAGE_TITLE}</h1>

<p>{L_PAGE_EXPLAIN}</p>

<table width="100%" align="center"><form action="{S_CONFIG_ACTION}" method="post">
<tr>
	<td align="right">{CONFIG_SELECT} <input type="submit" name="config" value="{L_GO}" class="liteoption" /></td>
</tr>
</form></table>

<!-- BEGIN switch_server -->
<table cellpadding="4" cellspacing="1" width="100%" align="center">
  <tr> 
	<th width="25%" class="thCornerL">&nbsp;{L_STATISTIC}&nbsp;</th>
	<th width="25%" class="thTop">&nbsp;{L_VALUE}&nbsp;</th>
	<th width="25%" class="thTop">&nbsp;{L_STATISTIC}&nbsp;</th>
	<th width="25%" class="thCornerR">&nbsp;{L_VALUE}&nbsp;</th>
  </tr>
  <tr>
	<td class="row2">{L_SERVER}:</td>
	<td class="row1"><b>{SERVER}</b></td>
	<td class="row2">{L_SERVER_TYPE}:</td>
	<td class="row1"><b>{SERVER_TYPE}</b></td>
  </tr>
  <tr> 
	<td class="row2">{L_PHP_VERSION}:</td>
	<td class="row1"><b>{PHP_VERSION}</b></td>
	<td class="row2">{L_GD_SUPPORT}:</td>
	<td class="row1" nowrap="nowrap"><b>{GD_SUPPORT}</b></td>
  </tr>
  <tr> 
	<td class="row2">{L_MEMORY_LIMIT}:</td>
	<td class="row1"><b>{MEMORY_LIMIT}</b></td>
	<td class="row2">{L_PHP_FILESIZE}:</td>
	<td class="row1"><b>{PHP_FILESIZE}</b></td>
  </tr>
  <tr> 
	<td class="row2">{L_UPLOAD_MAX_FILESIZE}:</td>
	<td class="row1"><b>{MAX_FILESIZE}</b></td>
	<td class="row2">{L_GZIP_COMPRESSION}:</td>
	<td class="row1"><b>{GZIP_COMPRESSION}</b></td>
  </tr>
  <tr> 
	<td class="row2">{L_DATABASE_TYPE}:</td>
	<td class="row1"><b>{DATABASE}</b></td>
	<td class="row2">{L_SAFE_MODE}:</td>
	<td class="row1"><b>{SAFE_MODE}{SAFE_MODE_XTRA}</b></td>
  </tr>
  <tr> 
	<td class="row2">{L_PKT_SIZE}:</td>
	<td class="row1"><b>{PKT_SIZE}</b></td>
	<td class="row2">{L_REGISTER_GLOBALS}:</td>
	<td class="row1"><b>{REGISTER_GLOBALS}</b></td>
  </tr>
</table>
<br />
<!-- END switch_server -->

<!-- BEGIN switch_search -->
<table width="100%" cellspacing="1" cellpadding="4" align="center" class="forumline">
<tr>
	<th class="thCornerL">{L_FUNCTION}</th>
	<th class="thCornerR">{L_FUNCTION_DESC}</th>
</tr>
<tr>
	<td class="row1"><a href="{U_FUNCTION_URL}" class="nav">{FUNCTION_NAME}</a></td>
	<td class="row2">{FUNCTION_DESC}</td>
</tr>
<tr>
	<td class="row1"><a href="{U_FUNCTION_URL1}" class="nav">{FUNCTION_NAME1}</a></td>
	<td class="row2">{FUNCTION_DESC1}</td>
</tr>
<tr>
	<td class="row1"><a href="{U_FUNCTION_URL2}" class="nav">{FUNCTION_NAME2}</a></td>
	<td class="row2">{FUNCTION_DESC2}</td>
</tr>
</table>
<br />
<!-- END switch_search -->

<table width="100%" cellspacing="1" cellpadding="4" align="center" class="forumline"><form method="post" action="{S_CONFIG_ACTION}">
<tr>
	<th colspan="2" class="thHead">{L_PAGE_TITLE}</th>
</tr>

<!-- BEGIN switch_ajax -->
<tr>
	<td class="row2" colspan="2"><span class="gensmall">{L_AJAXED_EXPLAIN}</span></td>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_AJAXED_STATUS}:</b><br /><span class="gensmall">{L_AJAXED_STATUS_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="AJAXed_status" value="1" {STATUS_ENABLE} /> {L_ENABLED}&nbsp;&nbsp;<input type="radio" name="AJAXed_status" value="0" {STATUS_DISABLE} /> {L_DISABLED}</td>
</tr>
<tr>
	<td class="row1"><b>{L_AJAXED_POST_TITLE}:</b><br /><span class="gensmall">{L_AJAXED_POST_TITLE_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="AJAXed_post_title" value="1" {POST_TITLE_ENABLE} /> {L_ENABLED}&nbsp;&nbsp;<input type="radio" name="AJAXed_post_title" value="0" {POST_TITLE_DISABLE} /> {L_DISABLED}</td>
</tr>
<tr>
	<td class="row1"><b>{L_AJAXED_POST_PREVIEW}:</b><br /><span class="gensmall">{L_AJAXED_POST_PREVIEW_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="AJAXed_post_preview" value="1" {POST_PREVIEW_ENABLE} /> {L_ENABLED}&nbsp;&nbsp;<input type="radio" name="AJAXed_post_preview" value="0" {POST_PREVIEW_DISABLE} /> {L_DISABLED}</td>
</tr>
<tr>
	<td class="row1"><b>{L_AJAXED_POST_IP}:</b><br /><span class="gensmall">{L_AJAXED_POST_IP_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="AJAXed_post_ip" value="1" {POST_IP_ENABLE} /> {L_ENABLED}&nbsp;&nbsp;<input type="radio" name="AJAXed_post_ip" value="0" {POST_IP_DISABLE} /> {L_DISABLED}</td>
</tr>
<tr>
	<td class="row1"><b>{L_AJAXED_POST_MENU}:</b><br /><span class="gensmall">{L_AJAXED_POST_MENU_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="AJAXed_post_menu" value="1" {POST_MENU_ENABLE} /> {L_ENABLED}&nbsp;&nbsp;<input type="radio" name="AJAXed_post_menu" value="0" {POST_MENU_DISABLE} /> {L_DISABLED}</td>
</tr>
<tr>
	<td class="row1"><b>{L_AJAXED_INLINE_POST_EDITING}:</b><br /><span class="gensmall">{L_AJAXED_INLINE_POST_EDITING_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="AJAXed_inline_post_editing" value="1" {INLINE_POST_EDITING_ENABLE} /> {L_ENABLED}&nbsp;&nbsp;<input type="radio" name="AJAXed_inline_post_editing" value="0" {INLINE_POST_EDITING_DISABLE} /> {L_DISABLED}</td>
</tr>
<tr>
	<td class="row1"><b>{L_AJAXED_POLL_TITLE}:</b><br /><span class="gensmall">{L_AJAXED_POLL_TITLE_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="AJAXed_poll_title" value="1" {POLL_TITLE_ENABLE} /> {L_ENABLED}&nbsp;&nbsp;<input type="radio" name="AJAXed_poll_title" value="0" {POLL_TITLE_DISABLE} /> {L_DISABLED}</td>
</tr>
<tr>
	<td class="row1"><b>{L_AJAXED_POLL_MENU}:</b><br /><span class="gensmall">{L_AJAXED_POLL_MENU_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="AJAXed_poll_menu" value="1" {POLL_MENU_ENABLE} /> {L_ENABLED}&nbsp;&nbsp;<input type="radio" name="AJAXed_poll_menu" value="0" {POLL_MENU_DISABLE} /> {L_DISABLED}</td>
</tr>
<tr>
	<td class="row1"><b>{L_AJAXED_POLL_OPTIONS}:</b><br /><span class="gensmall">{L_AJAXED_POLL_OPTIONS_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="AJAXed_poll_options" value="1" {POLL_OPTIONS_ENABLE} /> {L_ENABLED}&nbsp;&nbsp;<input type="radio" name="AJAXed_poll_options" value="0" {POLL_OPTIONS_DISABLE} /> {L_DISABLED}</td>
</tr>
<tr>
	<td class="row1"><b>{L_AJAXED_PM_PREVIEW}:</b><br /><span class="gensmall">{L_AJAXED_PM_PREVIEW_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="AJAXed_pm_preview" value="1" {PM_PREVIEW_ENABLE} /> {L_ENABLED}&nbsp;&nbsp;<input type="radio" name="AJAXed_pm_preview" value="0" {PM_PREVIEW_DISABLE} /> {L_DISABLED}</td>
</tr>
<tr>
	<td class="row1"><b>{L_AJAXED_USER_LIST}:</b><br /><span class="gensmall">{L_AJAXED_USER_LIST_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="AJAXed_user_list" value="1" {USER_LIST_ENABLE} /> {L_ENABLED}&nbsp;&nbsp;<input type="radio" name="AJAXed_user_list" value="0" {USER_LIST_DISABLE} /> {L_DISABLED}</td>
</tr>
<tr>
	<td class="row1"><b>{L_AJAXED_USER_LIST_NUMBER}:</b><br /><span class="gensmall">{L_AJAXED_USER_LIST_NUMBER_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="5" maxlength="4" name="AJAXed_user_list_number" value="{USER_LIST_NUMBER}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_AJAXED_FORUM_DELETE}:</b><br /><span class="gensmall">{L_AJAXED_FORUM_DELETE_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="AJAXed_forum_delete" value="1" {FORUM_DELETE_ENABLE} /> {L_ENABLED}&nbsp;&nbsp;<input type="radio" name="AJAXed_forum_delete" value="0" {FORUM_DELETE_DISABLE} /> {L_DISABLED}</td>
</tr>
<tr>
	<td class="row1"><b>{L_AJAXED_DISPLAY}:</b><br /><span class="gensmall">{L_AJAXED_DISPLAY_DELETE}</span></td>
	<td class="row2"><input type="radio" name="AJAXed_display_delete" value="1" {DISPLAY_DELETE_ENABLE} /> {L_ENABLED}&nbsp;&nbsp;<input type="radio" name="AJAXed_display_delete" value="0" {DISPLAY_DELETE_DISABLE} /> {L_DISABLED}</td>
</tr>
<tr>
	<td class="row1"><b>{L_AJAXED_FORUM_MOVE}:</b><br /><span class="gensmall">{L_AJAXED_FORUM_MOVE_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="AJAXed_forum_move" value="1" {FORUM_MOVE_ENABLE} /> {L_ENABLED}&nbsp;&nbsp;<input type="radio" name="AJAXed_forum_move" value="0" {FORUM_MOVE_DISABLE} /> {L_DISABLED}</td>
</tr>
<tr>
	<td class="row1"><b>{L_AJAXED_DISPLAY}:</b><br /><span class="gensmall">{L_AJAXED_DISPLAY_MOVE}</span></td>
	<td class="row2"><input type="radio" name="AJAXed_display_move" value="1" {DISPLAY_MOVE_ENABLE} /> {L_ENABLED}&nbsp;&nbsp;<input type="radio" name="AJAXed_display_move" value="0" {DISPLAY_MOVE_DISABLE} /> {L_DISABLED}</td>
</tr>
<tr>
	<td class="row1"><b>{L_AJAXED_FORUM_LOCK}:</b><br /><span class="gensmall">{L_AJAXED_FORUM_LOCK_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="AJAXed_forum_lock" value="1" {FORUM_LOCK_ENABLE} /> {L_ENABLED}&nbsp;&nbsp;<input type="radio" name="AJAXed_forum_lock" value="0" {FORUM_LOCK_DISABLE} /> {L_DISABLED}</td>
</tr>
<tr>
	<td class="row1"><b>{L_AJAXED_POST_DELETE}:</b><br /><span class="gensmall">{L_AJAXED_POST_DELETE_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="AJAXed_post_delete" value="1" {POST_DELETE_ENABLE} /> {L_ENABLED}&nbsp;&nbsp;<input type="radio" name="AJAXed_post_delete" value="0" {POST_DELETE_DISABLE} /> {L_DISABLED}</td>
</tr>
<tr>
	<td class="row1"><b>{L_AJAXED_TOPIC_DELETE}:</b><br /><span class="gensmall">{L_AJAXED_TOPIC_DELETE_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="AJAXed_topic_delete" value="1" {TOPIC_DELETE_ENABLE} /> {L_ENABLED}&nbsp;&nbsp;<input type="radio" name="AJAXed_topic_delete" value="0" {TOPIC_DELETE_DISABLE} /> {L_DISABLED}</td>
</tr>
<tr>
	<td class="row1"><b>{L_AJAXED_TOPIC_MOVE}:</b><br /><span class="gensmall">{L_AJAXED_TOPIC_MOVE_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="AJAXed_topic_move" value="1" {TOPIC_MOVE_ENABLE} /> {L_ENABLED}&nbsp;&nbsp;<input type="radio" name="AJAXed_topic_move" value="0" {TOPIC_MOVE_DISABLE} /> {L_DISABLED}</td>
</tr>
<tr>
	<td class="row1"><b>{L_AJAXED_TOPIC_LOCK}:</b><br /><span class="gensmall">{L_AJAXED_TOPIC_LOCK_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="AJAXed_topic_lock" value="1" {TOPIC_LOCK_ENABLE} /> {L_ENABLED}&nbsp;&nbsp;<input type="radio" name="AJAXed_topic_lock" value="0" {TOPIC_LOCK_DISABLE} /> {L_DISABLED}</td>
</tr>
<tr>
	<td class="row1"><b>{L_AJAXED_TOPIC_WATCH}:</b><br /><span class="gensmall">{L_AJAXED_TOPIC_WATCH_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="AJAXed_topic_watch" value="1" {TOPIC_WATCH_ENABLE} /> {L_ENABLED}&nbsp;&nbsp;<input type="radio" name="AJAXed_topic_watch" value="0" {TOPIC_WATCH_DISABLE} /> {L_DISABLED}</td>
</tr>
<tr>
	<td class="row1"><b>{L_AJAXED_USERNAME_CHECK}:</b><br /><span class="gensmall">{L_AJAXED_USERNAME_CHECK_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="AJAXed_username_check" value="1" {USERNAME_CHECK_ENABLE} /> {L_ENABLED}&nbsp;&nbsp;<input type="radio" name="AJAXed_username_check" value="0" {USERNAME_CHECK_DISABLE} /> {L_DISABLED}</td>
</tr>
<tr>
	<td class="row1"><b>{L_AJAXED_PASSWORD_CHECK}:</b><br /><span class="gensmall">{L_AJAXED_PASSWORD_CHECK_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="AJAXed_password_check" value="1" {PASSWORD_CHECK_ENABLE} /> {L_ENABLED}&nbsp;&nbsp;<input type="radio" name="AJAXed_password_check" value="0" {PASSWORD_CHECK_DISABLE} /> {L_DISABLED}</td>
</tr>
<!-- END switch_ajax -->

<!-- BEGIN switch_amazon -->
<tr>
	<td colspan="2" class="row2"><span class="gensmall">{L_INFO_TEXT}</td>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_ENABLE}:</b></td>
	<td class="row2"><input type="radio" name="amazon_enable" value="1" {S_ENABLE_AMAZON_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="amazon_enable" value="0" {S_ENABLE_AMAZON_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_COUNTRY}:</b></td>
	<td class="row2"><select name="amazon_country">
		<option value="0" {S_UK_SELECTED}>{L_UK}</option>
		<option value="1" {S_US_SELECTED}>{L_US}</option>
		<option value="2" {S_CANADA_SELECTED}>{L_CANADA}</option>
		<option value="3" {S_GERMANY_SELECTED}>{L_GERMANY}</option>
		<option value="4" {S_FRANCE_SELECTED}>{L_FRANCE}</option>
        </select></td>
</tr>
<tr>
	<td class="row1"><b>{L_POSTS}:</b></td>
	<td class="row2">
		<input type="checkbox" name="amazon_normal" value="1" {S_ENABLED_NORMAL} /> {L_NORMAL}<br />
		<input type="checkbox" name="amazon_sticky" value="1" {S_ENABLED_STICKY} /> {L_STICKY}<br />
		<input type="checkbox" name="amazon_announce" value="1" {S_ENABLED_ANNOUNCE} /> {L_ANNOUNCE}<br />
		<input type="checkbox" name="amazon_global_announce" value="1" {S_ENABLED_GLOBAL_ANNOUNCE} /> {L_GLOBAL_ANNOUNCE}
	</td>
</tr>
<tr>
	<td class="row1"><b>{L_USERNAME}:</b></td>
	<td class="row2"><input name="amazon_username" type="text" value="{AFFILIATE}" class="post" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_WINDOW}:</b></td>
	<td class="row2"><input type="radio" name="amazon_window" value="1" {S_NEW_WINDOW_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="amazon_window" value="0" {S_NEW_WINDOW_NO} /> {L_NO}</td>
</tr>
<!-- END switch_amazon -->

<!-- BEGIN switch_autoprune -->
{FAKE_DELETE_TEXT}
<tr> 
   	<td class="row1" width="50%"><b>{L_PRUNE_EMAIL}:</b><br /><span class="gensmall">{L_PRUNE_EMAIL_EXPLAIN}</span></td> 
   	<td class="row2"><input type="radio" name="user_prune_notify" value="1" {PRUNE_EMAIL_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="user_prune_notify" value="0" {PRUNE_EMAIL_NO} /> {L_NO}</td> 
</tr> 
<tr>
	<td class="row1"><b>{L_AUTO_MINS}:</b><br /><span class="gensmall">{L_AUTO_MINS_DESC}</span></td>
	<td class="row2"><input class="post" type="text" maxlength="255" size="5" name="admin_auto_delete_minutes" value="{S_AUTO_MINS}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_AUTO_TOTAL}:</b><br /><span class="gensmall">{L_AUTO_TOTAL_DESC}</span></td>
	<td class="row2"><b>{S_AUTO_TOTAL}</b></td>
</tr>
<tr>
	<th class="thHead" colspan="2">{L_NON_VISIT}</th>
</tr>
<tr>
	<td class="row2" colspan="2"><span class="gensmall">{L_NON_VISIT_DESC}</span></td>
</tr>
<tr>
	<td class="row1"><b>{L_ENABLED}:</b></td>
	<td class="row2"><input type="radio" name="admin_auto_delete_non_visit" value="1" {S_NON_VISIT_Y} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="admin_auto_delete_non_visit" value="0" {S_NON_VISIT_N} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_AUTO_DAYS}:</b></td>
	<td class="row2"><input class="post" type="text" maxlength="255" size="5" name="admin_auto_delete_days" value="{S_AUTO_DAYS}"></td>
</tr>
<tr>
	<th class="thHead" colspan="2">{L_INACTIVE}</th>
</tr>
<tr>
	<td class="row2" colspan="2"><span class="gensmall">{L_INACTIVE_DESC}</span></td>
</tr>
<tr>
	<td class="row1"><b>{L_ENABLED}:</b></td>
	<td class="row2"><input type="radio" name="admin_auto_delete_inactive" value="1" {S_INACTIVE_Y} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="admin_auto_delete_inactive" value="0" {S_INACTIVE_N} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_AUTO_DAYS}:</b></td>
	<td class="row2"><input class="post" type="text" maxlength="255" size="5" name="admin_auto_delete_days_inactive" value="{S_AUTO_DAYS_INACTIVE}" /></td>
</tr>
<tr>
	<th class="thHead" colspan="2">{L_NO_POST}</th>
</tr>
<tr>
	<td class="row2" colspan="2"><span class="gensmall">{L_NO_POST_DESC}</span></td>
</tr>
<tr>
	<td class="row1"><b>{L_ENABLED}:</b></td>
	<td class="row2"><input type="radio" name="admin_auto_delete_no_post" value="1" {S_NO_POST_Y} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="admin_auto_delete_no_post" value="0" {S_NO_POST_N} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_AUTO_DAYS}:</b></td>
	<td class="row2"><input class="post" type="text" maxlength="255" size="5" name="admin_auto_delete_days_no_post" value="{S_AUTO_DAYS_NO_POST}" /></td>
</tr>
<!-- END switch_autoprune -->

<!-- BEGIN switch_bday -->
<tr> 
      	<td class="row1" width="50%"><b>{L_BIRTHDAY_REQUIRED}:</b></td> 
      	<td class="row2"><input type="radio" name="birthday_required" value="1" {BIRTHDAY_REQUIRED_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="birthday_required" value="0" {BIRTHDAY_REQUIRED_NO} /> {L_NO}</td> 
</tr>  
<tr> 
	<td class="row1"><b>{L_ENABLE_BIRTHDAY_GREETING}:</b><br /><span class="gensmall">{L_BIRTHDAY_GREETING_EXPLAIN}</span></td> 
	<td class="row2"><input type="radio" name="birthday_greeting" value="1" {BIRTHDAY_GREETING_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="birthday_greeting" value="0" {BIRTHDAY_GREETING_NO} /> {L_NO}</td> 
</tr> 
<tr> 
	<td class="row1"><b>{L_MIN_USER_AGE}:</b><br /><span class="gensmall">{L_MIN_USER_AGE_EXPLAIN}</span></td> 
	<td class="row2"><input class="post" type="text" size="5" maxlength="4" name="min_user_age" value="{MIN_USER_AGE}" /> {L_MIN}&nbsp;&nbsp;<input class="post" type="text" size="5" maxlength="4" name="max_user_age" value="{MAX_USER_AGE}" /> {L_MAX}</td> 
</tr> 
<tr> 
	<td class="row1"><b>{L_BIRTHDAY_LOOKFORWARD}:</b><br /><span class="gensmall">{L_BIRTHDAY_LOOKFORWARD_EXPLAIN}</span></td> 
	<td class="row2"><input class="post" type="text" size="5" maxlength="3" name="birthday_check_day" value="{BIRTHDAY_LOOKFORWARD}" /></td> 
</tr>
<tr> 
	<td class="row1"><b>{L_BIRTHDAY_VIEWTOPIC}:</b></td> 
	<td class="row2"><input type="radio" name="birthday_viewtopic" value="1" {BIRTHDAY_VIEWTOPIC_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="birthday_viewtopic" value="0" {BIRTHDAY_VIEWTOPIC_NO} /> {L_NO}</td> 
</tr>
<!-- END switch_bday -->

<!-- BEGIN switch_config -->
<tr>
	<td class="row1" width="50%"><b>{L_SITE_NAME}:</b><br /><span class="gensmall">{L_SITE_NAME_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="35" maxlength="100" name="sitename" value="{SITENAME}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_SITE_DESCRIPTION}:</b><br /><span class="gensmall">{L_SITE_DESCRIPTION_EXPLAIN}</span></td>
	<td class="row2"><textarea wrap="virtual" class="post" name="site_desc" rows="5" cols="35">{SITE_DESCRIPTION}</textarea></td>
</tr>
<tr>
       	<td class="row1"><b>{L_SITE_LOGO}:</b><br /><span class="gensmall">{L_SITE_LOGO_EXPLAIN}</span></td>
       	<td class="row2"><input class="post" type="text" size="35" maxlength="255" name="logo_url" value="{LOGO_URL}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_DEFAULT_LANGUAGE}:</b></td>
	<td class="row2">{LANG_SELECT}</td>
</tr>
<tr>
	<td class="row1"><b>{L_DATE_FORMAT}:</b></td>
	<td class="row2">{DEFAULT_DATEFORMAT}</td>
</tr>
<tr>
	<td class="row1"><b>{L_SYSTEM_TIMEZONE}:</b></td>
	<td class="row2">{TIMEZONE_SELECT}</td>
</tr>
<tr>
	<td class="row1"><b>{L_DEFAULT_STYLE}:</b></td>
	<td class="row2">{STYLE_SELECT}</td>
</tr>
<tr>
	<td class="row1"><b>{L_OVERRIDE_STYLE}:</b><br /><span class="gensmall">{L_OVERRIDE_STYLE_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="override_user_style" value="1" {OVERRIDE_STYLE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="override_user_style" value="0" {OVERRIDE_STYLE_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_PAGE_TRANSITION}:</b></td>
	<td class="row2"><span class="gensmall">{PAGE_TRANSITION}</span></td>
</tr>
<tr> 
	<td class="row1"><b>{L_CLOCK_FORMAT}:</b><br /><span class="gensmall">{L_CLOCK_FORMAT_EXPLAIN}</span></td>
	<td class="row2">{CLOCK_SELECT}</td>
</tr>
<tr> 
   	<td class="row1"><b>{L_SITEMAP}:</b><br /><span class="gensmall">{L_SITEMAP_EXPLAIN}</span></td> 
   	<td class="row2"><input type="radio" name="board_sitemap" value="1" {S_SITEMAP_ENABLED} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="board_sitemap" value="0" {S_SITEMAP_DISABLED} /> {L_NO}</td> 
</tr>
<tr> 
	<td class="row1"><b>{L_ENABLE_IP_LOGGER}:</b></td> 
	<td class="row2"><input type="radio" name="enable_ip_logger" value="1" {IP_LOGGER_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="enable_ip_logger" value="0" {IP_LOGGER_NO} /> {L_NO}</td> 
</tr> 
<tr>
	<td class="row1"><b>{L_DISABLE_CALLHOME}:</b></td>
	<td class="row2"><input type="radio" name="callhome_disable" value="0" {S_DISABLE_CALLHOME_NO} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="callhome_disable" value="1" {S_DISABLE_CALLHOME_YES} /> {L_NO}</td>
</tr>
<!-- END switch_config -->

<!-- BEGIN switch_disable -->
<tr>
	<td class="row1" width="50%"><b>{L_DISABLE_BOARD}:</b><br /><span class="gensmall">{L_DISABLE_BOARD_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="board_disable" value="1" {S_DISABLE_BOARD_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="board_disable" value="0" {S_DISABLE_BOARD_NO} /> {L_NO}<br /><br />&nbsp;<textarea wrap="virtual" class="post" name="board_disable_text" rows="5" cols="35">{BOARD_DISABLE_TEXT}</textarea></td>
</tr>
<tr>
	<td class="row1"><b>{L_BOARD_DISABLE_MODE}:</b><br /><span class="gensmall">{L_BOARD_DISABLE_MODE_EXPLAIN}</span></td>
	<td class="row2">{BOARD_DISABLE_MODE}</td>
</tr>
<!-- END switch_disable -->

<!-- BEGIN switch_load -->
<tr>
	<td class="row2" colspan="2"><span class="gensmall">{L_LOAD_SETTINGS_EXPLAIN}</span></td>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_INDEX_CHAT}:</b></td>
	<td class="row2"><input type="radio" name="chat_index" value="1" {INDEX_CHAT_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="chat_index" value="0" {INDEX_CHAT_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_INDEX_NEW_USERS}:</b><br /><span class="gensmall">{L_INDEX_NEW_USERS_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="index_new_reg_users" value="1" {INDEX_NEW_USERS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="index_new_reg_users" value="0" {INDEX_NEW_USERS_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_INDEX_GROUPS}:</b></td>
	<td class="row2"><input type="radio" name="index_groups" value="1" {INDEX_GROUPS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="index_groups" value="0" {INDEX_GROUPS_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_INDEX_ACTIVE_FORUMS}:</b><br /><span class="gensmall">{L_INDEX_ACTIVE_FORUMS_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="index_active_in_forum" value="1" {INDEX_ACTIVE_FORUMS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="index_active_in_forum" value="0" {INDEX_ACTIVE_FORUMS_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_BOT_WHOSONLINE}:</b><br /><span class="gensmall">{L_BOT_WHOSONLINE_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="enable_bots_whosonline" value="1" {BOT_WHOSONLINE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="enable_bots_whosonline" value="0" {BOT_WHOSONLINE_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_JUMP_TOPIC}:</b></td>
	<td class="row2"><input type="radio" name="jump_to_topic" value="1" {JUMP_TOPIC_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="jump_to_topic" value="0" {JUMP_TOPIC_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_TOPLIST_TOPLIST_TOP10}:</b><br /><span class="gensmall">{L_TOPLIST_TOPLIST_TOP10_EXPLAIN}</span></td>
   	<td class="row2"><input type="radio" name="toplist_toplist_top10" value="1" {TOP10EN} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="toplist_toplist_top10" value="0" {TOP10DI} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"<b>{L_STAT_INDEX}:</b><br /><span class="gensmall">{L_STAT_INDEX_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="5" maxlength="10" name="stat_index" value="{STAT_INDEX}" /></td>
</tr>
<tr> 
	<td class="row1"><b>{L_TOTAL_VISITORS}:</b><br /><span class="gensmall">{L_TOTAL_VISITORS_EXPLAIN}</span></td> 
	<td class="row2"><input type="radio" name="visit_counter_index" value="1" {VISIT_COUNTER_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="visit_counter_index" value="0" {VISIT_COUNTER_NO} /> {L_NO}<br /><br />&nbsp;<input class="post" type="text" size="10" maxlength="10" name="visit_counter" value="{TOTAL_VISITORS}" /></td> 
</tr>
<tr>
	<td class="spaceRow" colspan="2"><img src="../images/spacer.gif" alt="" width="1" /></td>
</tr>
<tr> 
	<td class="row1"><b>{L_VIEWTOPIC_EXTRASTATS}:</b></td> 
	<td class="row2"><input type="radio" name="viewtopic_extrastats" value="1" {VIEWTOPIC_EXTRASTATS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="viewtopic_extrastats" value="0" {VIEWTOPIC_EXTRASTATS_NO} /> {L_NO}</td> 
</tr>
<tr> 
	<td class="row1"><b>{L_USERTIME_VIEWTOPIC}:</b></td> 
	<td class="row2"><input type="radio" name="usertime_viewtopic" value="1" {USERTIME_VIEWTOPIC_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="usertime_viewtopic" value="0" {USERTIME_VIEWTOPIC_NO} /> {L_NO}</td> 
</tr>
<tr> 
	<td class="row1"><b>{L_VIEWTOPIC_MEMNUM}:</b></td> 
	<td class="row2"><input type="radio" name="viewtopic_memnum" value="1" {VIEWTOPIC_MEMNUM_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="viewtopic_memnum" value="0" {VIEWTOPIC_MEMNUM_NO} /> {L_NO}</td> 
</tr>
<tr> 
	<td class="row1"><b>{L_VIEWTOPIC_FLAG}:</b></td> 
	<td class="row2"><input type="radio" name="viewtopic_flag" value="1" {VIEWTOPIC_FLAG_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="viewtopic_flag" value="0" {VIEWTOPIC_FLAG_NO} /> {L_NO}</td> 
</tr>
<tr> 
	<td class="row1"><b>{L_VIEWTOPIC_STYLE}:</b></td> 
	<td class="row2"><input type="radio" name="viewtopic_style" value="1" {VIEWTOPIC_STYLE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="viewtopic_style" value="0" {VIEWTOPIC_STYLE_NO} /> {L_NO}</td> 
</tr>
<tr> 
	<td class="row1"><b>{L_VIEWTOPIC_STATUS}:</b></td> 
	<td class="row2"><input type="radio" name="viewtopic_status" value="1" {VIEWTOPIC_STATUS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="viewtopic_status" value="0" {VIEWTOPIC_STATUS_NO} /> {L_NO}</td> 
</tr>
<tr> 
	<td class="row1"><b>{L_BIRTHDAY_VIEWTOPIC}:</b></td> 
	<td class="row2"><input type="radio" name="birthday_viewtopic" value="1" {BIRTHDAY_VIEWTOPIC_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="birthday_viewtopic" value="0" {BIRTHDAY_VIEWTOPIC_NO} /> {L_NO}</td> 
</tr>
<tr> 
	<td class="row1"><b>{L_GENDER_VIEWTOPIC}:</b></td> 
	<td class="row2"><input type="radio" name="gender_viewtopic" value="1" {GENDER_VIEWTOPIC_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="gender_viewtopic" value="0" {GENDER_VIEWTOPIC_NO} /> {L_NO}</td> 
</tr>
<tr>
	<td class="row1"><b>{L_VIEWTOPIC_PROFILEPHOTO}:</b></td>
	<td class="row2"><input type="radio" name="viewtopic_profilephoto" value="1" {VIEWTOPIC_PROFILEPHOTO_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="viewtopic_profilephoto" value="0" {VIEWTOPIC_PROFILEPHOTO_NO} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_VIEWTOPIC_USERGROUPS}:</b></td> 
	<td class="row2"><input type="radio" name="viewtopic_usergroups" value="1" {VIEWTOPIC_USERGROUPS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="viewtopic_usergroups" value="0" {VIEWTOPIC_USERGROUPS_NO} /> {L_NO}</td> 
</tr>
<tr>
	<td class="row1"<b>{L_ALLOW_MEDAL}:</b></td>
	<td class="row2"><input type="radio" name="allow_medal_display_viewtopic" value="1" {MEDAL_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_medal_display_viewtopic" value="0" {MEDAL_NO} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_VIEWTOPIC_BUDDYIMG}:</b></td> 
	<td class="row2"><input type="radio" name="viewtopic_buddyimg" value="1" {VIEWTOPIC_BUDDYIMG_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="viewtopic_buddyimg" value="0" {VIEWTOPIC_BUDDYIMG_NO} /> {L_NO}</td> 
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_REFERRAL_VIEWTOPIC}:</b></td>
	<td class="row2"><input type="radio" name="referral_viewtopic" value="1" {REFERRAL_VIEWTOPIC_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="referral_viewtopic" value="0" {REFERRAL_VIEWTOPIC_NO} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_VIEWTOPIC_EDITDATE}:</b></td> 
	<td class="row2"><input type="radio" name="viewtopic_editdate" value="1" {VIEWTOPIC_EDITDATE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="viewtopic_editdate" value="0" {VIEWTOPIC_EDITDATE_NO} /> {L_NO}</td> 
</tr>
<tr>
	<td class="row1"><b>{L_DISPLAY_LAST_EDITED}:</b></td>
	<td class="row2"><input type="radio" name="display_last_edited" value="1" {DISPLAY_LAST_EDITED_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="display_last_edited" value="0" {DISPLAY_LAST_EDITED_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_VIEWTOPIC_DOWNPOST}:</b></td>
	<td class="row2"><input type="radio" name="viewtopic_downpost" value="1" {VIEWTOPIC_DOWNPOST_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="viewtopic_downpost" value="0" {VIEWTOPIC_DOWNPOST_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_VIEWTOPIC_VIEWPOST}:</b></td>
	<td class="row2"><input type="radio" name="viewtopic_viewpost" value="1" {VIEWTOPIC_VIEWPOST_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="viewtopic_viewpost" value="0" {VIEWTOPIC_VIEWPOST_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_YEAR_STARS}:</b><br /><span class="gensmall">{L_YEAR_STARS_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="viewtopic_yearstars" value="1" {VIEWTOPIC_YEARSTARS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="viewtopic_yearstars" value="0" {VIEWTOPIC_YEARSTARS_NO} /> {L_NO}<br /><br />&nbsp;<input class="post" type="text" size="10" maxlength="10" name="year_stars" value="{YEAR_STARS}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_REDUCE_IMGS}:</b><br /><span class="gensmall">{L_REDUCE_IMGS_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="reduce_bbcode_imgs" value="1" {REDUCE_IMGS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="reduce_bbcode_imgs" value="0" {REDUCE_IMGS_NO} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_ENABLE_TOPIC_VIEW_USERS}:</b><br /><span class="gensmall">{L_ENABLE_TOPIC_VIEW_USERS_EXPLAIN}</span></td>
	<td class="row2" nowrap="nowrap"><input type="radio" name="enable_topic_view_users" value="1" {TOPIC_VIEW_USERS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="enable_topic_view_users" value="0" {TOPIC_VIEW_USERS_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_ENABLE_QUICK_TITLES}:</b></td>
	<td class="row2"><input type="radio" name="enable_quick_titles" value="1" {ENABLE_QUICK_TITLES_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="enable_quick_titles" value="0" {ENABLE_QUICK_TITLES_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="spaceRow" colspan="2"><img src="../images/spacer.gif" alt="" width="1" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_PROFILE_SIG}:</b></td>
	<td class="row2"><input type="radio" name="profile_show_sig" value="1" {PROFILE_SIG_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="profile_show_sig" value="0" {PROFILE_SIG_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_ALLOW_MEDAL2}:</b></td>
	<td class="row2"><input type="radio" name="allow_medal_display_viewprofile" value="1" {MEDAL2_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_medal_display_viewprofile" value="0" {MEDAL2_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_BBUS_ENABLE}:</b></td>
	<td class="row2"><input type="radio" name="bb_usage_stats_enable" value="1" {BBUS_ENABLE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="bb_usage_stats_enable" value="0" {BBUS_ENABLE_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_VIEWPROFILE_PROFILEPHOTO}:</b></td>
	<td class="row2"><input type="radio" name="viewprofile_profilephoto" value="1" {VIEWPROFILE_PROFILEPHOTO_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="viewprofile_profilephoto" value="0" {VIEWPROFILE_PROFILEPHOTO_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="spaceRow" colspan="2"><img src="../images/spacer.gif" alt="" width="1" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_BOARD_HITS}:</b><br /><span class="gensmall">{L_BOARD_HITS_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="board_hits" value="1" {BOARD_HITS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="board_hits" value="0" {BOARD_HITS_NO} /> {L_NO}</td>
</tr>
<tr> 
   	<td class="row1"><b>{L_BOARD_SERVERLOAD}:</b><br /><span class="gensmall">{L_BOARD_SERVERLOAD_EXPLAIN}</span></td> 
   	<td class="row2"><input type="radio" name="board_serverload" value="1" {BOARD_SERVERLOAD_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="board_serverload" value="0" {BOARD_SERVERLOAD_NO} /> {L_NO}</td> 
</tr> 
<!-- END switch_load -->

<!-- BEGIN switch_usage -->
<tr>
	<td class="row2" colspan="2"><span class="gensmall">{L_BBUS_SETTINGS_EXPLAIN}</span></td>
</tr>
<tr>
	<td class="row1"><b>{L_BBUS_ENABLE}:</b></td>
	<td class="row2"><input type="radio" name="bb_usage_stats_enable" value="1" {BBUS_ENABLE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="bb_usage_stats_enable" value="0" {BBUS_ENABLE_NO} /> {L_NO}</td>
</tr>

  <tr>
	<td class="row1" width="50%"><b>{L_BBUS_SETTING_VIEWLEVEL}:</b><br /><span class="gensmall">{L_BBUS_SETTING_VIEWLEVEL_CAPTION}</span></td>
	<td class="row2"><input class="post" type="text" readonly onFocus="javascript: selectContents(this)" maxlength="10" size="5" name="bb_usage_stats_viewlevel" value="{BBUS_SETTING_VIEWLEVEL_VALUE}"/></td>
  </tr>
  <tr>
	<td class="row1"><b>{L_BBUS_VIEWLEVEL_ANONYMOUS_CAPTION}:</b><br /><span class="gensmall">{L_BBUS_VIEWLEVEL_ANONYMOUS_EXPLAIN}</span></td>
	<td class="row2"><input id="chkVLAnonymous" {BBUS_VIEWLEVEL_ANONYMOUS_CHKED} type="checkbox" onclick="javascript: setValue(bb_usage_stats_viewlevel, chkVLAnonymous,{BBUS_VIEWLEVEL_ANONYMOUS_FLAGVALUE})"></input></td>
  </tr>
  <tr>
	<td class="row1"><b>{L_BBUS_VIEWLEVEL_SELF_CAPTION}:</b><br /><span class="gensmall">{L_BBUS_VIEWLEVEL_SELF_EXPLAIN}</span></td>	
	<td class="row2"><input id="chkVLSelf" {BBUS_VIEWLEVEL_SELF_CHKED} type="checkbox" onclick="javascript: setValue(bb_usage_stats_viewlevel, chkVLSelf,{BBUS_VIEWLEVEL_SELF_FLAGVALUE})"></input></td>
  </tr>
  <tr>
	<td class="row1"><b>{L_BBUS_VIEWLEVEL_USERS_CAPTION}:</b><br /><span class="gensmall">{L_BBUS_VIEWLEVEL_USERS_EXPLAIN}</span></td>	
	<td class="row2"><input id="chkVLUsers" {BBUS_VIEWLEVEL_USERS_CHKED} type="checkbox" onclick="javascript: setValue(bb_usage_stats_viewlevel, chkVLUsers,{BBUS_VIEWLEVEL_USERS_FLAGVALUE})"></input></td>
  </tr>
  <tr>
	<td class="row1"><b>{L_BBUS_VIEWLEVEL_MODERATORS_CAPTION}:</b><br /><span class="gensmall">{L_BBUS_VIEWLEVEL_MODERATORS_EXPLAIN}</span></td>
	<td class="row2"><input id="chkVLModerators" {BBUS_VIEWLEVEL_MODERATORS_CHKED} type="checkbox" onclick="javascript: setValue(bb_usage_stats_viewlevel, chkVLModerators,{BBUS_VIEWLEVEL_MODERATORS_FLAGVALUE})"></input></td>
  </tr>
  <tr>
	<td class="row1"><b>{L_BBUS_VIEWLEVEL_ADMINS_CAPTION}:</b><br /><span class="gensmall">{L_BBUS_VIEWLEVEL_ADMINS_EXPLAIN}</span></td>
	<td class="row2"><input id="chkVLAdmins" {BBUS_VIEWLEVEL_ADMINS_CHKED} type="checkbox" onclick="javascript: setValue(bb_usage_stats_viewlevel, chkVLAdmins,{BBUS_VIEWLEVEL_ADMINS_FLAGVALUE})"></input></td>
  </tr>
  <tr>
	<td class="row1"><b>{L_BBUS_VIEWLEVEL_SPECIALGRP_CAPTION}:</b><br/><span class="gensmall">{L_BBUS_VIEWLEVEL_SPECIALGRP_EXPLAIN}</span></td>
	<td class="row2"><input name="chkVLSpecialGrp" {BBUS_VIEWLEVEL_SPECIALGRP_CHKED} type="checkbox" onclick="javascript: setValue(bb_usage_stats_viewlevel, chkVLSpecialGrp,{BBUS_VIEWLEVEL_SPECIALGRP_FLAGVALUE})"></input> &nbsp;{BBUS_SETTING_SPECIALGRP_SELECT}</td>
  </tr>
  <tr>
	<td class="row1"><b>{L_BBUS_SETTING_VIEWOPTIONS_CAPTION}:</b><br /><span class="gensmall">{L_BBUS_SETTING_VIEWOPTIONS_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" readonly onFocus="javascript: selectContents(this)" maxlength="10" size="5" name="bb_usage_stats_viewoptions" value="{BBUS_SETTING_VIEWOPTIONS_VALUE}"/></td>
  </tr>
  <tr>
	<td class="row1"><b>{L_BBUS_VIEWOPTION_SHOW_ALL_FORUMS_CAPTION}:</b></td>	
	<td class="row2"><input id="chkVOShowAllForums" {BBUS_VIEWOPTION_SHOW_ALL_FORUMS_CHKED} type="checkbox" onclick="javascript: setValue(bb_usage_stats_viewoptions, chkVOShowAllForums,{BBUS_VIEWOPTION_SHOW_ALL_FORUMS_FLAGVALUE})"></input></td>
  </tr>
  <tr>
	<td class="row1"><b>{L_BBUS_VIEWOPTION_PCTUTUP_COLUMN_VISIBLE_CAPTION}:</b></td>
	<td class="row2"><input id="chkVOPctUTUPColumnVisible" {BBUS_VIEWOPTION_PCTUTUP_COLUMN_VISIBLE_CHKED} type="checkbox" onclick="javascript: setValue(bb_usage_stats_viewoptions, chkVOPctUTUPColumnVisible,{BBUS_VIEWOPTION_PCTUTUP_COLUMN_VISIBLE_FLAGVALUE})"></input></td>
  </tr>
  <tr>
	<td class="row1"><b>{L_BBUS_VIEWOPTION_MISC_SECTION_VISIBLE_CAPTION}:</b></td>
	<td class="row2"><input id="chkVOMiscSectionVisible" {BBUS_VIEWOPTION_MISC_SECTION_VISIBLE_CHKED} type="checkbox" onclick="javascript: setValue(bb_usage_stats_viewoptions, chkVOMiscSectionVisible,{BBUS_VIEWOPTION_MISC_SECTION_VISIBLE_FLAGVALUE})"></input></td>
  </tr>
  <tr>
	<td class="row1"><b>{L_BBUS_VIEWOPTION_MISC_TOTPRUNEDPOSTS_CAPTION}:</b></td>
	<td class="row2"><input id="chkVOMiscTotPrunedPosts" {BBUS_VIEWOPTION_MISC_TOTPRUNEDPOSTS_CHKED} type="checkbox" onclick="javascript: setValue(bb_usage_stats_viewoptions, chkVOMiscTotPrunedPosts,{BBUS_VIEWOPTION_MISC_TOTPRUNEDPOSTS_FLAGVALUE})"></input></td>
  </tr>
  <tr>
	<td class="row1"><b>{L_BBUS_VIEWOPTION_VIEWER_SCALABLE_PR_CAPTION}:</b></td>
	<td class="row2"><input id="chkVOViewerScalablePR" {BBUS_VIEWOPTION_VIEWER_SCALABLE_PR_CHKED} type="checkbox" onclick="javascript: setValue(bb_usage_stats_viewoptions, chkVOViewerScalablePR,{BBUS_VIEWOPTION_VIEWER_SCALABLE_PR_FLAGVALUE})"></input></td>
  </tr>
  <tr>
	<td class="row1"><b>{L_BBUS_VIEWOPTION_VIEWER_SCALABLE_TR_CAPTION}:</b></td>
	<td class="row2"><input id="chkVOViewerScalableTR" {BBUS_VIEWOPTION_VIEWER_SCALABLE_TR_CHKED} type="checkbox" onclick="javascript: setValue(bb_usage_stats_viewoptions, chkVOViewerScalableTR,{BBUS_VIEWOPTION_VIEWER_SCALABLE_TR_FLAGVALUE})"></input></td>
  </tr>
  <tr>
	<td class="row1"><b>{L_BBUS_DEFAULT_POST_RATE_SCALING_CAPTION}:</b><br /><span class="gensmall">{L_BBUS_DEFAULT_POST_RATE_SCALING_EXPLAIN}</span></td>
	<td class="row2">{BBUS_DEFAULT_POST_RATE_SCALING_SELECT}</td>
  </tr>
  <tr>
	<td class="row1"><b>{L_BBUS_DEFAULT_TOPIC_RATE_SCALING_CAPTION}:</b><br /><span class="gensmall">{L_BBUS_DEFAULT_TOPIC_RATE_SCALING_EXPLAIN}</span></td>
	<td class="row2">{BBUS_DEFAULT_TOPIC_RATE_SCALING_SELECT}</td>
  </tr>
<!-- END switch_usage -->

<!-- BEGIN switch_calendar -->
<tr>
	<td class="row1" width="50%"><b>{L_CAL_EVENT_LMT}:</b><br /><span class="gensmall">{L_CAL_EVENT_LMT_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" maxlength="3" size="5" name="minical_event_lmt" value="{CAL_EVENT_LMT}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_CAL_UPCOMING}:</b><br /><span class="gensmall">{L_CAL_UPCOMING_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" maxlength="3" size="5" name="minical_upcoming" value="{CAL_UPCOMING}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_CAL_SEARCH}:</b><br /><span class="gensmall">{L_CAL_SEARCH_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="minical_search" value="POSTS" {CAL_SEARCH_POSTS} /> {L_POSTS}&nbsp;&nbsp;<input type="radio" name="minical_search" value="EVENTS" {CAL_SEARCH_EVENTS} /> {L_EVENTS}</td>
</tr>
<!-- END switch_calendar -->

<!-- BEGIN switch_cookie -->
<tr>
	<td class="row2" colspan="2"><span class="gensmall">{L_COOKIE_SETTINGS_EXPLAIN}</span></td>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_COOKIE_DOMAIN}:</b></td>
	<td class="row2"><input class="post" type="text" maxlength="255" name="cookie_domain" value="{COOKIE_DOMAIN}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_COOKIE_NAME}:</b></td>
	<td class="row2"><input class="post" type="text" maxlength="16" name="cookie_name" value="{COOKIE_NAME}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_COOKIE_PATH}:</b></td>
	<td class="row2"><input class="post" type="text" maxlength="255" name="cookie_path" value="{COOKIE_PATH}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_COOKIE_SECURE}:</b><br /><span class="gensmall">{L_COOKIE_SECURE_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="cookie_secure" value="1" {S_COOKIE_SECURE_ENABLED} /> {L_ENABLED}&nbsp;&nbsp;<input type="radio" name="cookie_secure" value="0" {S_COOKIE_SECURE_DISABLED} /> {L_DISABLED}</td>
</tr>
<!-- END switch_cookie -->

<!-- BEGIN switch_ebay -->
<tr>
	<td class="row1" width="50%"><b>{L_AUCTIONS_ENABLE}:</b><br /><span class="gensmall">{L_AUCTIONS_ENABLE_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="auction_enable" value="1" {S_AUCTIONS_ENABLE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="auction_enable" value="0" {S_AUCTIONS_ENABLE_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_AUCTIONS_USERID}:</b></td>
	<td class="row2"><input class="post" type="text" size="35" name="auction_ebay_user_id" value="{S_AUCTIONS_USERID}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_AUCTIONS_EBAYURL}:</b><br /><span class="gensmall">{L_AUCTIONS_EBAYURL_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="35" name="auction_ebay_url" value="{S_AUCTIONS_EBAYURL}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_AUCTIONS_TZ}:</b><br /><span class="gensmall">{L_AUCTIONS_TZ_EXPLAIN}</span></td>
	<td class="row2">{S_AUCTIONS_TZ}</td>
</tr>
<tr>
	<td class="row1"><b>{L_AUCTIONS_AMT}:</b><br /><span class="gensmall">{L_AUCTIONS_AMT_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" maxlength="10" size="5" name="auction_amt" value="{S_AUCTIONS_AMT}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_AUCTIONS_ENDED}:</b><br /><span class="gensmall">{L_AUCTIONS_ENDED_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" maxlength="3" size="5" name="auction_enable_ended" value="{S_AUCTIONS_ENDED}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_AUCTIONS_ORDER}:</b></td>
	<td class="row2"><select name="auction_sort_order">
		<option value="{S_AUCTIONS_ORDER}" selected="selected">{L_AUCTIONS_ORDER0}</option>
		<option value="1">{L_AUCTIONS_ORDER1}</option>
		<option value="2">{L_AUCTIONS_ORDER2}</option>
		<option value="3">{L_AUCTIONS_ORDER3}</option>
		<option value="4">{L_AUCTIONS_ORDER4}</option>
		<option value="8">{L_AUCTIONS_ORDER5}</option>
	</select>
</td>
</tr>
<tr>
	<td class="row1"><b>{L_AUCTIONS_THUMBS}:</b></td>
	<td class="row2"><input type="radio" name="auction_enable_thumbs" value="1" {S_AUCTIONS_ENABLE_THUMBS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="auction_enable_thumbs" value="0" {S_AUCTIONS_ENABLE_THUMBS_NO} /> {L_NO}</td>
</tr>
<!-- END switch_ebay -->

<!-- BEGIN switch_gender -->
<tr> 
	<td class="row1" width="50%"><b>{L_GENDER_REQUIRED}:</b></td> 
	<td class="row2"><input type="radio" name="gender_required" value="1" {GENDER_REQUIRED_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="gender_required" value="0" {GENDER_REQUIRED_NO} /> {L_NO}</td> 
</tr>
<tr> 
	<td class="row1"><b>{L_GENDER_INDEX}:</b></td> 
	<td class="row2"><input type="radio" name="gender_index" value="1" {GENDER_INDEX_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="gender_index" value="0" {GENDER_INDEX_NO} /> {L_NO}</td> 
</tr>
<tr> 
	<td class="row1"><b>{L_GENDER_VIEWTOPIC}:</b></td> 
	<td class="row2"><input type="radio" name="gender_viewtopic" value="1" {GENDER_VIEWTOPIC_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="gender_viewtopic" value="0" {GENDER_VIEWTOPIC_NO} /> {L_NO}</td> 
</tr>
<!-- END switch_gender -->

<!-- BEGIN switch_karma -->
<tr>
	<td class="row1" width="50%"><b>{L_KARMA_ALLOW}:</b></td>
	<td class="row2"><input type="radio" name="allow_karma" value="1" {KARMA_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_karma" value="0" {KARMA_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_KARMA_FLOOD_INTERVAL}:</b><br /><span class="gensmall">{L_KARMA_FLOOD_INTERVAL_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="5" maxlength="4" name="karma_flood_interval" value="{KARMA_FLOOD_INTERVAL}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_KARMA_ALLOW_ADMINS}:</b><br /><span class="gensmall">{L_KARMA_ALLOW_ADMINS_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="karma_admins" value="0" {KARMA_ADMINS_NO} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="karma_admins" value="4" {KARMA_ADMINS_YES} /> {L_NO}</td>
</tr>
<!-- END switch_karma -->

<!-- BEGIN switch_login -->
<tr>
	<td class="row1" width="50%"><b>{L_ALLOW_AUTOLOGIN}:</b><br /><span class="gensmall">{L_ALLOW_AUTOLOGIN_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="allow_autologin" value="1" {ALLOW_AUTOLOGIN_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_autologin" value="0" {ALLOW_AUTOLOGIN_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_AUTOLOGIN_CHECK}:</b><br /><span class="gensmall">{L_AUTOLOGIN_CHECK_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="autologin_check" value="1" {AUTOLOGIN_CHECK_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="autologin_check" value="0" {AUTOLOGIN_CHECK_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_AUTOLOGIN_TIME}:</b><br /><span class="gensmall">{L_AUTOLOGIN_TIME_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="5" maxlength="4" name="max_autologin_time" value="{AUTOLOGIN_TIME}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_MAX_LOGIN_ATTEMPTS}:</b><br /><span class="gensmall">{L_MAX_LOGIN_ATTEMPTS_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="5" maxlength="4" name="max_login_attempts" value="{MAX_LOGIN_ATTEMPTS}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_LOGIN_RESET_TIME}:</b><br /><span class="gensmall">{L_LOGIN_RESET_TIME_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="5" maxlength="4" name="login_reset_time" value="{LOGIN_RESET_TIME}" /></td>
</tr>
<tr> 
   	<td class="row1"><b>{L_HIDDE_LAST_LOGON}:</b><br /><span class="gensmall">{L_HIDDE_LAST_LOGON_EXPLAIN}</span></td> 
   	<td class="row2"><input type="radio" name="hidde_last_logon" value="1" {HIDDE_LAST_LOGON_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="hidde_last_logon" value="0" {HIDDE_LAST_LOGON_NO} /> {L_NO}</td> 
</tr> 
<tr>
  	<td class="row1"><b>{L_ADMIN_LOGIN}:</b><br /><span class="gensmall">{L_ADMIN_LOGIN_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="admin_login" value="1" {ADMIN_LOGIN_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="admin_login" value="0" {ADMIN_LOGIN_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_PASSWORD_CHANGE}:</b><br /><span class="gensmall">{L_PASSWORD_CHANGE_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="5" maxlength="10" name="password_update_days" value="{PASSWORD_DAYS}" /> {L_DAYS}</td>
</tr>
<!-- END switch_login -->

<!-- BEGIN switch_meta_tags -->
<tr>
	<td class="row2" colspan="2"><span class="gensmall">{L_META_SETTINGS_EXPLAIN}</span></td>
</tr>
<tr> 
	<td class="row1" width="50%"><b>{L_META_AUTHOR}:</b><br /><span class="gensmall">{L_META_AUTHOR_EXPLAIN}</span></td> 
    	<td class="row2"><input class="post" type="text" size="35" maxlength="100" name="meta_author" value="{META_AUTHOR}" /></td> 
</tr>
<tr> 
	<td class="row1"><b>{L_META_OWNER}:</b><br /><span class="gensmall">{L_META_OWNER_EXPLAIN}</span></td> 
    	<td class="row2"><input class="post" type="text" size="35" maxlength="100" name="meta_owner" value="{META_OWNER}" /></td> 
</tr>
<tr> 
	<td class="row1"><b>{L_META_REPLYTO}:</b><br /><span class="gensmall">{L_META_REPLYTO_EXPLAIN}</span></td> 
    	<td class="row2"><input class="post" type="text" size="35" maxlength="255" name="meta_reply_to" value="{META_REPLYTO}" /></td> 
</tr>
<tr> 
	<td class="row1"><b>{L_META_COPYRIGHT}:</b><br /><span class="gensmall">{L_META_COPYRIGHT_EXPLAIN}</span></td> 
    	<td class="row2"><input class="post" type="text" size="35" maxlength="255" name="meta_copyright" value="{META_COPYRIGHT}" /></td> 
</tr>
<tr> 
	<td class="row1"><b>{L_META_GENERATOR}:</b><br /><span class="gensmall">{L_META_GENERATOR_EXPLAIN}</span></td> 
    	<td class="row2"><input class="post" type="text" size="35" maxlength="255" name="meta_generator" value="{META_GENERATOR}" /></td> 
</tr>
<tr> 
	<td class="row1"><b>{L_META_CREATION_DATE}:</b><br /><span class="gensmall">{L_META_CREATION_DATE_EXPLAIN}</span></td> 
    	<td class="row2"><input class="post" type="text" size="3" maxlength="2" name="meta_date_creation_day" value="{META_CREATION_DAY}" /> / <input class="post" type="text" size="3" maxlength="2" name="meta_date_creation_month" value="{META_CREATION_MONTH}" /> / <input class="post" type="text" size="5" maxlength="4" name="meta_date_creation_year" value="{META_CREATION_YEAR}" /></td> 
</tr>
<tr> 
	<td class="row1"><b>{L_META_REVISION_DATE}:</b><br /><span class="gensmall">{L_META_REVISION_DATE_EXPLAIN}</span></td> 
    	<td class="row2"><input class="post" type="text" size="3" maxlength="2" name="meta_date_revision_day" value="{META_REVISION_DAY}" /> / <input class="post" type="text" size="3" maxlength="2" name="meta_date_revision_month" value="{META_REVISION_MONTH}" />  / <input class="post" type="text" size="5" maxlength="4" name="meta_date_revision_year" value="{META_REVISION_YEAR}" /></td> 
</tr>
<tr> 
	<td class="row1"><b>{L_META_KEYWORDS}:</b><br /><span class="gensmall">{L_META_KEYWORDS_EXPLAIN}</span></td> 
        <td class="row2"><textarea wrap="virtual" class="post" name="meta_keywords" rows="5" cols="35">{META_KEYWORDS}</textarea></td>
</tr> 
<tr> 
	<td class="row1"><b>{L_META_DESCRIPTION}:</b><br /><span class="gensmall">{L_META_DESCRIPTION_EXPLAIN}</span></td> 
        <td class="row2"><textarea wrap="virtual" class="post" name="meta_description" rows="5" cols="35">{META_DESCRIPTION}</textarea></td>
</tr>
<tr> 
	<td class="row1"><b>{L_META_ABSTRACT}:</b><br /><span class="gensmall">{L_META_ABSTRACT_EXPLAIN}</span></td> 
    	<td class="row2"><input class="post" type="text" size="35" maxlength="255" name="meta_abstract" value="{META_ABSTRACT}" /></td> 
</tr>
<tr> 
	<td class="row1"><b>{L_META_RATING}:</b><br /><span class="gensmall">{L_META_RATING_EXPLAIN}</span></td> 
    	<td class="row2"><input class="post" type="text" size="20" maxlength="255" name="meta_rating" value="{META_RATING}" /></td> 
</tr>
<tr> 
	<td class="row1"><b>{L_META_REVISIT}:</b><br /><span class="gensmall">{L_META_REVISIT_EXPLAIN}</span></td> 
    	<td class="row2"><input class="post" type="text" size="5" maxlength="10" name="meta_revisit" value="{META_REVISIT}" /></td> 
</tr>
<tr> 
	<td class="row1"><b>{L_META_DISTRIBUTION}:</b><br /><span class="gensmall">{L_META_DISTRIBUTION_EXPLAIN}</span></td> 
    	<td class="row2"><input class="post" type="text" maxlength="255" name="meta_distribution" value="{META_DISTRIBUTION}" /></td> 
</tr>
<tr> 
	<td class="row1"><b>{L_META_ROBOTS}:</b><br /><span class="gensmall">{L_META_ROBOTS_EXPLAIN}</span></td> 
    	<td class="row2"><input class="post" type="text" maxlength="255" name="meta_robots" value="{META_ROBOTS}" /></td> 
</tr>
<tr> 
	<td class="row1"><b>{L_META_IDENTIFIER}:</b><br /><span class="gensmall">{L_META_IDENTIFIER_EXPLAIN}</span></td> 
    	<td class="row2"><input class="post" type="text" size="30" maxlength="255" name="meta_identifier_url" value="{META_IDENTIFIER}" /></td> 
</tr>
<tr> 
	<td class="row1"><b>{L_META_CATEGORY}:</b><br /><span class="gensmall">{L_META_CATEGORY_EXPLAIN}</span></td> 
    	<td class="row2"><input class="post" type="text" size="20" maxlength="255" name="meta_category" value="{META_CATEGORY}" /></td> 
</tr>
<tr>
	<th class="thHead" colspan="2">{L_META_HTTP_SETTINGS}</th>
</tr>
<tr>
	<td colspan="2" class="row2"><span class="gensmall">{L_META_HTTP_SETTINGS_EXPLAIN}</span></td>
</tr>
<tr> 
	<td class="row1"><b>{L_META_REFRESH}:</b><br /><span class="gensmall">{L_META_REFRESH_EXPLAIN}</span></td> 
    	<td class="row2"><input class="post" type="text" size="5" maxlength="3" name="meta_refresh" value="{META_REFRESH}" /></td> 
</tr>
<tr> 
	<td class="row1"><b>{L_META_REDIRECT_URL}:</b><br /><span class="gensmall">{L_META_REDIRECT_URL_EXPLAIN}</span></td> 
    	<td class="row2"><input class="post" type="text" size="35" maxlength="255" name="meta_redirect_url_adress" value="{META_REDIRECT_URL}" /></td> 
</tr>
<tr> 
	<td class="row1"><b>{L_META_REDIRECT_TIME}:</b><br /><span class="gensmall">{L_META_REDIRECT_TIME_EXPLAIN}</span></td> 
    	<td class="row2"><input class="post" type="text" size="5" maxlength="3" name="meta_redirect_url_time" value="{META_REDIRECT_TIME}" /></td> 
</tr>
<tr> 
	<td class="row1"><b>{L_META_PRAGMA}:</b><br /><span class="gensmall">{L_META_PRAGMA_EXPLAIN}</span></td> 
    	<td class="row2"><input class="post" type="text" size="10" maxlength="8" name="meta_pragma" value="{META_PRAGMA}" /></td> 
</tr>
<tr> 
	<td class="row1"><b>{L_META_LANGUAGE}:</b><br /><span class="gensmall">{L_META_LANGUAGE_EXPLAIN}</span></td> 
    	<td class="row2"><input class="post" type="text" size="5" maxlength="10" name="meta_language" value="{META_LANGUAGE}" /></td> 
</tr>
<!-- END switch_meta_tags -->

<!-- BEGIN switch_modcp -->
<tr>
	<td class="row1" width="50%"><b>{L_MODS_VIEWIPS}:</b><br /><span class="gensmall">{L_MODS_VIEWIPS_EXPLAIN}</span><br /></td>
	<td class="row2"><input type="radio" name="mods_viewips" value="1"{MODS_VIEWIPS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="mods_viewips" value="0"{MODS_VIEWIPS_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_MODULE_AVDELETE}:</b><br /><span class="gensmall">{L_MODULE_AVDELETE_EXPLAIN}</span><br /></td>
	<td class="row2"><input type="radio" name="enable_module_avdelete" value="1"{MODULE_AVDELETE_ENABLE} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="enable_module_avdelete" value="0"{MODULE_AVDELETE_DISABLE} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_MODULE_BACKUP}:</b><br /><span class="gensmall">{L_MODULE_BACKUP_EXPLAIN}</span><br /></td>
	<td class="row2"><input type="radio" name="enable_module_backup" value="1"{MODULE_BACKUP_ENABLE} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="enable_module_backup" value="0"{MODULE_BACKUP_DISABLE} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_MODULE_USER_BAN}:</b><br /><span class="gensmall">{L_MODULE_USER_BAN_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="enable_module_user_ban" value="1"{MODULE_USER_BAN_ENABLE} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="enable_module_user_ban" value="0"{MODULE_USER_BAN_DISABLE} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_MODULE_DISALLOW}:</b><br /><span class="gensmall">{L_MODULE_DISALLOW_EXPLAIN}</span><br /></td>
	<td class="row2"><input type="radio" name="enable_module_disallow" value="1"{MODULE_DISALLOW_ENABLE} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="enable_module_disallow" value="0"{MODULE_DISALLOW_DISABLE} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_MODULE_MASS_EMAIL}:</b><br /><span class="gensmall">{L_MODULE_MASS_EMAIL_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="enable_module_mass_email" value="1"{MODULE_MASS_EMAIL_ENABLE} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="enable_module_mass_email" value="0"{MODULE_MASS_EMAIL_DISABLE} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_MODULE_RANKS}:</b><br /><span class="gensmall">{L_MODULE_RANKS_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="enable_module_ranks" value="1"{MODULE_RANKS_ENABLE} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="enable_module_ranks" value="0"{MODULE_RANKS_DISABLE} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_MODULE_SMILIES}:</b><br /><span class="gensmall">{L_MODULE_SMILIES_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="enable_module_smilies" value="1"{MODULE_SMILIES_ENABLE} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="enable_module_smilies" value="0"{MODULE_SMILIES_DISABLE} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_MODULE_USERS}:</b><br /><span class="gensmall">{L_MODULE_USERS_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="enable_module_users" value="1"{MODULE_USERS_ENABLE} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="enable_module_users" value="0"{MODULE_USERS_DISABLE} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_MODULE_WORDS}:</b><br /><span class="gensmall">{L_MODULE_WORDS_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="enable_module_words" value="1"{MODULE_WORDS_ENABLE} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="enable_module_words" value="0"{MODULE_WORDS_DISABLE} /> {L_NO}</td>
</tr>
<!-- END switch_modcp -->

<!-- BEGIN switch_myinfo -->
<tr>
	<td class="row1" width="50%"><b>{L_ENABLE_MYINFO}:</b></td>
	<td class="row2"><input type="radio" name="myInfo_enable" value="1" {MYINFO_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="myInfo_enable" value="0" {MYINFO_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_MYINFO_NAME}:</b><br /><span class="gensmall">{L_MYINFO_NAME_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="35" maxlength="100" name="myInfo_name" value="{MYINFO_NAME}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_MYINFO_INSTRUCTIONS}:</b><br /><span class="gensmall">{L_MYINFO_INSTRUCTIONS_EXPLAIN}</span></td>
	<td class="row2"><textarea wrap="virtual" class="post" cols="35" rows="5" name="myInfo_instructions">{MYINFO_INSTRUCTIONS}</textarea></td>
</tr>
<!-- END switch_myinfo -->

<!-- BEGIN switch_newsbar -->
<tr> 
	<td class="row1" width="50%"><b>{L_ENABLE_NEWSBAR}:</b><br /><span class="gensmall">{L_ENABLE_NEWSBAR_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="forum_module_newsbar" value="1" {S_NEWSBAR_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="forum_module_newsbar" value="0" {S_NEWSBAR_NO} /> {L_NO}</td>
</tr>
  <tr> 
	<td class="row1"><b>{L_NEWS_TITLE}:</b></td> 
	<td class="row2"><input class="post" name="news_title" size="35" maxlength="100" value="{NEWS_TITLE}" /></td> 
  </tr>    
  <tr>
	<td class="row1"><b>{L_NEWS_BLOCK}:</b><br /><span class="gensmall">{L_NEWS_BLOCK_EXPLAIN}</span></td> 
	<td class="row2"><textarea wrap="virtual" class="post" name="news_block" rows="5" cols="35" />{NEWS_BLOCK}</textarea></td> 
  </tr>  
  <tr>
	<td class="row1"><b>{L_NEWS_SIZE}:</b></td>
	<td class="row2">{NEWS_SIZE_SELECT}</td>
  </tr>
  <tr>
	<td class="row1"><b>{L_NEWS_BOLD}:</b></td>
	<td class="row2">{NEWS_BOLD_SELECT}</td>
  </tr>
  <tr>
	<td class="row1"><b>{L_NEWS_ITAL}:</b></td>
	<td class="row2">{NEWS_ITAL_SELECT}</td>
  </tr>
  <tr>
	<td class="row1"><b>{L_NEWS_UNDER}:</b></td>
	<td class="row2">{NEWS_UNDER_SELECT}</td>
  </tr>
  <tr>
	<td class="row1"><b>{L_NEWS_STYLE}:</b></td>
	<td class="row2">{NEWS_STYLE_SELECT}</td>
  </tr>
  <tr>
	<td class="row1"><b>{L_SCROLL_BEHAVIOR}:</b></td> 
	<td class="row2">{SCROLL_BEHAVIOR_SELECT}</td>
  </tr>
  <tr>
	<td class="row1"><b>{L_SCROLL_ACTION}:</b></td> 
	<td class="row2">{SCROLL_ACTION_SELECT}</td>
  </tr>
  <tr>
	<td class="row1"><b>{L_SCROLL_SIZE}:</b></td> 
	<td class="row2">{SCROLL_SIZE_SELECT}</td>
  </tr>
  <tr>
	<td class="row1"><b>{L_SCROLL_SPEED}:</b></td> 
	<td class="row2">{SCROLL_SPEED_SELECT}</td>
  </tr>		 
 	
<!-- END switch_newsbar -->

<!-- BEGIN switch_notes -->
<tr>
	<td class="row1" width="50%"><b>{L_ENABLE_NOTES}:</b><br /><span class="gensmall">{L_ENABLE_NOTES_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="enable_user_notes" value="1" {ENABLE_NOTES_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="enable_user_notes" value="0" {ENABLE_NOTES_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_NOTES}:</b></td>
	<td class="row2"><input class="post" type="text" name="notes" size="5" maxlength="5" value="{NOTES}" /></td>
</tr>
<!-- END switch_notes -->

<!-- BEGIN switch_passgen -->
<tr>
	<td class="row1" width="50%"><b>{L_PASSWORD_CHANGE}:</b><br /><span class="gensmall">{L_PASSWORD_CHANGE_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="5" maxlength="10" name="password_update_days" value="{PASSWORD_DAYS}" /> {L_DAYS}</td>
</tr>
<tr>
	<td class="row1"><b>{L_PASS_GEN_ENABLE}:</b></td>
	<td class="row2"><input type="radio" name="pass_gen_enable" value="1" {PASS_GEN_ENABLE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="pass_gen_enable" value="0" {PASS_GEN_ENABLE_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_PASS_GEN_LENGTH}:</b><br /><span class="gensmall">{L_PASS_GEN_LENGTH_EXPLAIN}</span></td>
	<td class="row2"><input type="text" class="post" name="pass_gen_length" size="5" maxlength="3" value="{PASS_GEN_LENGTH}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_PASS_GEN_ALPHA}:</b></td>
	<td class="row2"><input type="radio" name="pass_gen_alphanumerical" value="1" {PASS_GEN_ALPHA_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="pass_gen_alphanumerical" value="0" {PASS_GEN_ALPHA_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_PASS_GEN_SPECIAL}:</b></td>
	<td class="row2"><input type="radio" name="pass_gen_specialchars" value="1" {PASS_GEN_SPECIAL_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="pass_gen_specialchars" value="0" {PASS_GEN_SPECIAL_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_PASS_GEN_UPPERCASE}:</b></td>
	<td class="row2"><input type="radio" name="pass_gen_uppercase" value="1" {PASS_GEN_UPPERCASE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="pass_gen_uppercase" value="0" {PASS_GEN_UPPERCASE_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_PASS_GEN_LOWERCASE}:</b></td>
	<td class="row2"><input type="radio" name="pass_gen_lowercase" value="1" {PASS_GEN_LOWERCASE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="pass_gen_lowercase" value="0" {PASS_GEN_LOWERCASE_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_PASS_GEN_NUMBERS}:</b></td>
	<td class="row2"><input type="radio" name="pass_gen_numbers" value="1" {PASS_GEN_NUMBERS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="pass_gen_numbers" value="0" {PASS_GEN_NUMBERS_NO} /> {L_NO}</td>
</tr>
<!-- END switch_passgen -->

<!-- BEGIN switch_points -->
<tr> 
	<td class="row1" width="50%"><b>{L_POINTS_NAME}:</b><br /><span class="gensmall">{L_POINTS_NAME_EXPLAIN}</span></td>
    	<td class="row2"><input class="post" type="text" maxlength="100" size="30" name="points_name" value="{S_POINTS_NAME}" /></td>
</tr>
<tr> 
    	<td class="row1"><b>{L_ENABLE_POST}:</b><br /><span class="gensmall">{L_ENABLE_POST_EXPLAIN}</span></td>
    	<td class="row2"><input type="radio" name="points_post" value="1" {S_POINTS_POST_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="points_post" value="0" {S_POINTS_POST_NO} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_ENABLE_BROWSE}:</b><br /> <span class="gensmall">{L_ENABLE_BROWSE_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="points_browse" value="1" {S_POINTS_BROWSE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="points_browse" value="0" {S_POINTS_BROWSE_NO} /> {L_NO} </td>
</tr>
<tr> 
	<td class="row1"><b>{L_ENABLE_DONATION}:</b><br /><span class="gensmall">{L_ENABLE_DONATION_EXPLAIN}</span></td>
      	<td class="row2"><input type="radio" name="points_donate" value="1" {S_POINTS_DONATE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="points_donate" value="0" {S_POINTS_DONATE_NO} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_POINTS_VIEWTOPIC}:</b></td>
      	<td class="row2"><input type="radio" name="points_viewtopic" value="1" {S_POINTS_VIEWTOPIC_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="points_viewtopic" value="0" {S_POINTS_VIEWTOPIC_NO} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_PER_DEFAULT}:</b><br /><span class="gensmall">{L_PER_DEFAULT_EXPLAIN}</span></td>
    	<td class="row2"><input class="post" type="text" name="points_default" size="4" maxlength="4" value="{S_POINTS_DEFAULT}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_PER_TOPIC}:</b><br /><span class="gensmall">{L_PER_TOPIC_EXPLAIN}</span></td>
    	<td class="row2"><input class="post" type="text" name="points_topic" size="4" maxlength="4" value="{S_POINTS_TOPIC}" /></td>
</tr>
<tr> 
	<td class="row1"><b>{L_PER_REPLY}:</b><br /><span class="gensmall">{L_PER_REPLY_EXPLAIN}</span></td>
    	<td class="row2"><input class="post" type="text" name="points_reply" size="4" maxlength="4" value="{S_POINTS_REPLY}" /></td>
</tr>
<tr> 
	<td class="row1"><b>{L_PER_PAGE}:</b><br /><span class="gensmall">{L_PER_PAGE_EXPLAIN}</span></td>
      	<td class="row2"><input class="post" type="text" name="points_page" size="4" maxlength="4" value="{S_POINTS_PAGE}" /></td>
</tr>
<tr> 
	<td class="row1"><b>{L_PER_BANNER}:</b><br /><span class="gensmall">{L_PER_BANNER_EXPLAIN}</span></td>
      	<td class="row2"><input class="post" type="text" name="points_banner" size="4" maxlength="4" value="{S_POINTS_BANNER}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_REFERRAL_REWARD}:</b><br /><span class="gensmall">{L_REFERRAL_REWARD_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" name="referral_reward" size="4" maxlength="4" value="{S_POINTS_REFERRAL}" /></td>
</tr>
<tr> 
	<td class="row1"><b>{L_USER_GROUP_AUTH}:</b><br /><span class="gensmall">{L_USER_GROUP_AUTH_EXPLAIN}</span></td>
    	<td class="row2"><textarea wrap="virtual" class="post" name="points_user_group_auth_ids" cols="35" rows="5">{S_USER_GROUP_AUTH}</textarea></td>
</tr>
<tr> 
	<td class="row1"><b>{L_POINTS_RESET}:</b><br /><span class="gensmall">{L_POINTS_RESET_EXPLAIN}</span></td>
      	<td class="row2"><input class="post" size="10" type="text" maxlength="100" name="reset_points" /></td>
</tr>
<!-- END switch_points -->

<!-- BEGIN switch_profile_photo -->
<tr>
	<td class="row1" width="50%"><b>{L_VIEWPROFILE_PROFILEPHOTO}:</b></td>
	<td class="row2"><input type="radio" name="viewprofile_profilephoto" value="1" {VIEWPROFILE_PROFILEPHOTO_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="viewprofile_profilephoto" value="0" {VIEWPROFILE_PROFILEPHOTO_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_VIEWTOPIC_PROFILEPHOTO}:</b></td>
	<td class="row2"><input type="radio" name="viewtopic_profilephoto" value="1" {VIEWTOPIC_PROFILEPHOTO_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="viewtopic_profilephoto" value="0" {VIEWTOPIC_PROFILEPHOTO_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_ALLOW_PHOTO_REMOTE}:</b><br /><span class="gensmall">{L_ALLOW_PHOTO_REMOTE_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="allow_photo_remote" value="1" {PHOTO_REMOTE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_photo_remote" value="0" {PHOTO_REMOTE_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_ALLOW_PHOTO_UPLOAD}:</b></td>
	<td class="row2"><input type="radio" name="allow_photo_upload" value="1" {PHOTO_UPLOAD_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_photo_upload" value="0" {PHOTO_UPLOAD_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_PHOTO_MAX_FILESIZE}:</b><br /><span class="gensmall">{L_PHOTO_MAX_FILESIZE_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="5" maxlength="10" name="photo_filesize" value="{PHOTO_FILESIZE}" /> Bytes</td>
</tr>
<tr>
	<td class="row1"><b>{L_MAX_PHOTO_SIZE}:</b><br /><span class="gensmall">{L_MAX_AVATAR_SIZE_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="5" maxlength="4" name="photo_max_height" value="{PHOTO_MAX_HEIGHT}" /> x <input class="post" type="text" size="5" maxlength="4" name="photo_max_width" value="{PHOTO_MAX_WIDTH}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_PHOTO_STORAGE_PATH}:</b><br /><span class="gensmall">{L_PHOTO_STORAGE_PATH_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="25" maxlength="255" name="photo_path" value="{PHOTO_PATH}" /></td>
</tr>
<!-- END switch_profile_photo -->

<!-- BEGIN switch_referral -->
<tr>
	<td class="row1" width="50%"><b>{L_REFERRAL_ENABLE}:</b></td>
	<td class="row2"><input type="radio" name="referral_enable" value="1" {S_DISABLE_REFERRAL_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="referral_enable" value="0" {S_DISABLE_REFERRAL_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_REFERRAL_VIEWTOPIC}:</b></td>
	<td class="row2"><input type="radio" name="referral_viewtopic" value="1" {REFERRAL_VIEWTOPIC_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="referral_viewtopic" value="0" {REFERRAL_VIEWTOPIC_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_REFERRAL_DEFAULT_USER_ID}:</b><br /><span class="gensmall">{L_REFERRAL_DEFAULT_USER_ID_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" name="referral_id" size="5" maxlength="10" value="{REFERRAL_DEFAULT_USER_ID}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_REFERRAL_REWARD}:</b><br /><span class="gensmall">{L_REFERRAL_REWARD_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" name="referral_reward" size="4" maxlength="4" value="{S_POINTS_REFERRAL}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_TOP_REFERRALS}:</b><br /><span class="gensmall">{L_TOP_REFERRALS_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" name="referral_top_limit" size="5" maxlength="4" value="{TOP_REFERRALS_VALUE}" /></td>
</tr>
<!-- END switch_referral -->

<!-- BEGIN switch_register -->
<tr>
	<td class="row1" width="50%"><b>{L_ALLOW_NAME_CHANGE}:</b></td>
	<td class="row2"><input type="radio" name="allow_namechange" value="1" {NAMECHANGE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_namechange" value="0" {NAMECHANGE_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_LIMIT_USERNAME_LENGTH}:</b><br /><span class="gensmall">{L_LIMIT_USERNAME_LENGTH_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="5" maxlength="2" name="limit_username_min_length" value="{LIMIT_USERNAME_MIN_LENGTH}" /> {L_MIN}&nbsp;&nbsp;<input class="post" type="text" size="5" maxlength="2" name="limit_username_max_length" value="{LIMIT_USERNAME_MAX_LENGTH}" /> {L_MAX}</td>
</tr>
<tr>
	<td class="row1"><b>{L_ACCT_ACTIVATION}:</b><br /><span class="gensmall">{L_ACCT_ACTIVATION_EXPLAIN}</span></td> 
	<td class="row2">&nbsp;<input type="radio" name="require_activation" value="{ACTIVATION_DISABLE}" {ACTIVATION_DISABLE_CHECKED} /> {L_DISABLED}<br />&nbsp;<input type="radio" name="require_activation" value="{ACTIVATION_NONE}" {ACTIVATION_NONE_CHECKED} /> {L_NONE}&nbsp; &nbsp;<input type="radio" name="require_activation" value="{ACTIVATION_USER}" {ACTIVATION_USER_CHECKED} /> {L_USER}&nbsp; &nbsp;<input type="radio" name="require_activation" value="{ACTIVATION_ADMIN}" {ACTIVATION_ADMIN_CHECKED} /> {L_ADMIN}<br /><br />&nbsp;<textarea wrap="virtual" class="post" name="disable_reg_msg" rows="5" cols="35">{DISABLE_REG_MSG}</textarea></td>
</tr>
<tr>
	<td class="row1"><b>{L_USER_ACCOUNT_LIMIT}:</b><br /><span class="gensmall">{L_USER_ACCOUNT_LIMIT_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="5" maxlength="10" name="user_accounts_limit" value="{USER_ACCOUNT_LIMIT}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_VISUAL_CONFIRM}:</b><br /><span class="gensmall">{L_VISUAL_CONFIRM_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="enable_confirm" value="1" {CONFIRM_ENABLE} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="enable_confirm" value="0" {CONFIRM_DISABLE} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_VIP_ENABLE}:</b><br /><span class="gensmall">{L_VIP_ENABLE_EXPLAIN}</span></td>
	<td class="row2">&nbsp;<input type="radio" name="vip_enable" value="1" {VIP_ENABLE} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="vip_enable" value="0" {VIP_DISABLE} /> {L_NO}<br /><br />&nbsp;<input class="post" type="text" size="10" maxlength="10" name="vip_code" value="{VIP_CODE}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_COPPA_ENABLE}:</b><br /><span class="gensmall">{L_COPPA_ENABLE_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="enable_coppa" value="1" {COPPA_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="enable_coppa" value="0" {COPPA_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_AVATAR_REGISTER}:</b><br /><span class="gensmall">{L_AVATAR_REGISTER_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="enable_avatar_register" value="1" {AVATAR_REGISTER_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="enable_avatar_register" value="0" {AVATAR_REGISTER_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_REGISTRATION_NOTIFY}:</b><br /><span class="gensmall">{L_REGISTRATION_NOTIFY_EXPLAIN}</span></td>
	<td class="row2">&nbsp;<input type="radio" name="registration_notify" value="{REGISTRATION_NOTIFY_NONE}" {REGISTRATION_NOTIFY_NONE_CHECKED} /> {L_DISABLED}<br />&nbsp;<input type="radio" name="registration_notify" value="{REGISTRATION_NOTIFY_MOD}" {REGISTRATION_NOTIFY_MOD_CHECKED} /> {L_MOD}&nbsp;&nbsp;<input type="radio" name="registration_notify" value="{REGISTRATION_NOTIFY_LESS_ADMIN}" {REGISTRATION_NOTIFY_LESS_ADMIN_CHECKED} /> {L_SUPERMOD}&nbsp;&nbsp;<input type="radio" name="registration_notify" value="{REGISTRATION_NOTIFY_ADMIN}" {REGISTRATION_NOTIFY_ADMIN_CHECKED} /> {L_ADMIN}</td>
</tr>
<tr>
	<td class="row1"><b>{L_PASSWORD_CHANGE}:</b><br /><span class="gensmall">{L_PASSWORD_CHANGE_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="5" maxlength="10" name="password_update_days" value="{PASSWORD_DAYS}" /> {L_DAYS}</td>
</tr>
<tr>
	<td class="row1"><b>{L_AUTO_GROUP}:</b><br /><span class="gensmall">{L_AUTO_GROUP_EXPLAIN}</span></td>
	<td class="row2">{AUTO_GROUP_ID}</td>
</tr>
<tr>
	<td class="row1"><b>{L_DISABLE_NEWUSER_PRIVATE_MESSAGING}:</b><br /><span class="gensmall">{L_DISABLE_NEWUSER_PRIVATE_MESSAGING_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="privmsg_newuser_disable" value="1" {S_PRIVMSG_NEWUSER_ENABLED} /> {L_ENABLED}&nbsp;&nbsp;<input type="radio" name="privmsg_newuser_disable" value="0" {S_PRIVMSG_NEWUSER_DISABLED} /> {L_DISABLED}</td>
</tr>
<tr>
	<td class="row1"><b>{L_DISABLE_WPM}:</b><br /><span class="gensmall">{L_DISABLE_WPM_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="wpm_disable" value="0" {S_WPM_ENABLED} /> {L_ENABLED}&nbsp;&nbsp;<input type="radio" name="wpm_disable" value="1" {S_WPM_DISABLED} /> {L_DISABLED}</td>
</tr>
<!-- END switch_register -->

<!-- BEGIN switch_search -->
<tr>
	<td class="row1" width="50%"><b>{L_SEARCH_ENABLE}:</b></td>
	<td class="row2"><input type="radio" name="search_enable" value="1" {SEARCH_ENABLE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="search_enable" value="0" {SEARCH_ENABLE_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_SEARCH_FLOOD_INTERVAL}:</b><br /><span class="gensmall">{L_SEARCH_FLOOD_INTERVAL_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="4" maxlength="4" name="search_flood_interval" value="{SEARCH_FLOOD_INTERVAL}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_SEARCH_FOOTER}:</b></td>
	<td class="row2"><input type="radio" name="search_footer" value="1" {SEARCH_FOOTER_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="search_footer" value="0" {SEARCH_FOOTER_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_SEARCH_FORUM}:</b></td>
	<td class="row2"><input type="radio" name="search_forum" value="1" {SEARCH_FORUM_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="search_forum" value="0" {SEARCH_FORUM_NO} /> {L_NO}</td>
</tr>
<!-- END switch_search -->

<!-- BEGIN switch_server -->
<tr>
	<td class="row1" width="50%"><b>{L_ENABLE_GZIP}:</b><br /><span class="gensmall">{L_ENABLE_GZIP_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="gzip_compress" value="1" {GZIP_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="gzip_compress" value="0" {GZIP_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_GZIP_LEVEL}:</b><br /><span class="gensmall">{L_GZIP_LEVEL_EXPLAIN}</span></td>
	<td class="row2"><input type="text" class="post" name="gzip_level" value="{GZIP_LEVEL}" size="5" maxlength="1" />
</tr>
<tr> 
   	<td class="row1"><b>{L_DEBUG_VALUE}:</b><br /><span class="gensmall">{L_DEBUG_VALUE_EXPLAIN}</span></td> 
   	<td class="row2"><input type="radio" name="debug_value" value="1" {S_DEBUG_VALUE_ENABLED} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="debug_value" value="0" {S_DEBUG_VALUE_DISABLED} /> {L_NO}</td> 
</tr>
<tr> 
   	<td class="row1"><b>{L_DEBUG_EMAIL}:</b><br /><span class="gensmall">{L_DEBUG_EMAIL_EXPLAIN}</span></td> 
   	<td class="row2"><input type="radio" name="debug_email" value="1" {S_DEBUG_EMAIL_ENABLED} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="debug_email" value="0" {S_DEBUG_EMAIL_DISABLED} /> {L_NO}</td> 
</tr> 
<tr> 
   	<td class="row1"><b>{L_BOARD_SERVERLOAD}:</b><br /><span class="gensmall">{L_BOARD_SERVERLOAD_EXPLAIN}</span></td> 
   	<td class="row2"><input type="radio" name="board_serverload" value="1" {BOARD_SERVERLOAD_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="board_serverload" value="0" {BOARD_SERVERLOAD_NO} /> {L_NO}</td> 
</tr> 
<tr>
	<td class="row1"><b>{L_SERVER_NAME}:</b><br /><span class="gensmall">{L_SERVER_NAME_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" maxlength="255" size="35" name="server_name" value="{SERVER_NAME}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_SERVER_PORT}:</b><br /><span class="gensmall">{L_SERVER_PORT_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" maxlength="5" size="5" name="server_port" value="{SERVER_PORT}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_SCRIPT_PATH}:</b><br /><span class="gensmall">{L_SCRIPT_PATH_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="35" maxlength="255" name="script_path" value="{SCRIPT_PATH}" /></td>
</tr>
<tr>
	<th class="thHead" colspan="2">{L_PATH_SETTINGS}</th>
</tr>
<tr>
	<td class="row1"><b>{L_SMILIES_PATH}:</b><br /><span class="gensmall">{L_SMILIES_PATH_EXPLAIN}</span></span></td>
	<td class="row2"><input class="post" type="text" size="25" maxlength="255" name="smilies_path" value="{SMILIES_PATH}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_AVATAR_GALLERY_PATH}:</b><br /><span class="gensmall">{L_AVATAR_GALLERY_PATH_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="25" maxlength="255" name="avatar_gallery_path" value="{AVATAR_GALLERY_PATH}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_AVATAR_GENERATOR_TEMPLATE_PATH}:</b><br /><span class="gensmall">{L_AVATAR_GENERATOR_TEMPLATE_PATH_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="25" maxlength="255" name="avatar_generator_template_path" value="{AVATAR_GENERATOR_TEMPLATE_PATH}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_AVATAR_STORAGE_PATH}:</b><br /><span class="gensmall">{L_AVATAR_STORAGE_PATH_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="25" maxlength="255" name="avatar_path" value="{AVATAR_PATH}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_PHOTO_STORAGE_PATH}:</b><br /><span class="gensmall">{L_PHOTO_STORAGE_PATH_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="25" maxlength="255" name="photo_path" value="{PHOTO_PATH}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_XS_CACHE_DIR}:</b><br /><span class="gensmall">{L_XS_CACHE_DIR_EXPLAIN}</span></td>
	<td class="row2">&nbsp;<input class="post" type="text" size="25" maxlength="255" name="xs_cache_dir" value="{XS_CACHE_DIR}" /><br /><br />&nbsp;<input type="radio" name="xs_cache_dir_absolute" value="0" {XS_CACHE_DIR_ABSOLUTE_NO}/> {L_XS_DIR_RELATIVE}<br /><span class="gensmall">{L_XS_DIR_RELATIVE_EXPLAIN}</span><br /><br />&nbsp;<input type="radio" name="xs_cache_dir_absolute" value="1" {XS_CACHE_DIR_ABSOLUTE_YES}/> {L_XS_DIR_ABSOLUTE}<br /><span class="gensmall">{L_XS_DIR_ABSOLUTE_EXPLAIN}</span></td>
</tr>
<!-- END switch_server -->

<!-- BEGIN switch_shoutbox -->
<tr>
	<td class="row1" width="50%"><b>{L_ENABLE_SHOUTBOX}:</b></td>
	<td class="row2"><input type="radio" name="shoutbox_enable" value="1" {SHOUTBOX_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="shoutbox_enable" value="0" {SHOUTBOX_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_SHOUT_POSITION}:</b><br /><span class="gensmall">{L_SHOUT_POSITION_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="shoutbox_position" value="0" {SHOUTBOX_POS_TOP} /> {L_SHOUT_TOP}&nbsp;&nbsp;<input type="radio" name="shoutbox_position" value="2" {SHOUTBOX_POS_BOTTOM} /> {L_SHOUT_BOTTOM}&nbsp;&nbsp;<input type="radio" name="shoutbox_position" value="1" {SHOUTBOX_POS_MODULE} /> {L_SHOUT_MODULE}&nbsp;&nbsp;</td>
</tr>
<tr>
	<td class="row1"><b>{L_SHOUT_HEIGHT}</b></td>
	<td class="row2"><input class="post" type="text" size="5" maxlength="4" name="shoutbox_height" value="{SHOUTBOX_HEIGHT}" /> px</td>
</tr>
<tr>
	<td class="row1"><b>{L_PRUNE_SHOUTS}:</b><br /><span class="gensmall">{L_PRUNE_SHOUTS_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="5" maxlength="4" name="prune_shouts" value="{PRUNE_SHOUTS}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_SHOUT_REFRESH_RATE}:</b><br /><span class="gensmall">{L_SHOUT_REFRESH_RATE_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="5" maxlength="4" name="shoutbox_refresh_rate" value="{SHOUTBOX_REFRESH_RATE}" /></td>
</tr>
<!-- END switch_shoutbox -->

<!-- BEGIN switch_shoutcast -->
<tr>
	<td class="row1" width="50%"><b>{L_ENABLE_FORUM_MODULE_SHOUTCAST}:</b><br /><span class="gensmall">{L_ENABLE_FORUM_MODULE_EXPLAIN}</span></td>
      	<td class="row2"><input type="radio" name="forum_module_shoutcast" value="1" {S_FORUM_MODULE_SHOUTCAST_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="forum_module_shoutcast" value="0" {S_FORUM_MODULE_SHOUTCAST_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_SHOUT_HEIGHT}</b></td>
	<td class="row2"><input class="post" type="text" size="5" maxlength="4" name="forum_module_shoutcast_height" value="{SHOUTCAST_HEIGHT}" /> px</td>
</tr>
<tr>
	<td class="row1"><b>{L_TS_SERVER_ADDRESS}:</b></td>
	<td class="row2"><input class="post" type="text" size="20" maxlength="50" name="shoutcast_server" value="{SHOUTCAST_SERVER}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_TS_SERVER_PASSWORD}:</b></td>
	<td class="row2"><input class="post" type="text" size="20" maxlength="32" name="shoutcast_pass" value="{SHOUTCAST_PASS}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_TS_SERVER_QUERY_PORT}:</b></td>
	<td class="row2"><input class="post" type="text" size="8" maxlength="10" name="shoutcast_port" value="{SHOUTCAST_PORT}" /></td>
</tr>

<!-- END switch_shoutcast -->

<!-- BEGIN switch_signature -->
<tr>
	<td class="row1" width="50%"><b>{L_ALLOW_SIG}:</b></td>
	<td class="row2"><input type="radio" name="allow_sig" value="1" {SIG_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_sig" value="0" {SIG_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_MAX_SIG_LENGTH}:</b><br /><span class="gensmall">{L_MAX_SIG_LENGTH_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="5" maxlength="5" name="max_sig_chars" value="{SIG_SIZE}" /></td>
</tr>
<tr> 
	<td class="row1"><b>{L_MAX_SIG_LINES}:</b><br /><span class="gensmall">{L_MAX_SIG_LINES_EXPLAIN}</span></td> 
	<td class="row2"><input class="post" type="text" size="5" maxlength="5" name="max_sig_lines" value="{SIG_LINES}" /></td> 
</tr> 
<tr>
	<td class="row1"><b>{L_MAX_SIG_IMAGES_LIMIT}:</b></td>
	<td class="row2"><input class="post" type="text" size="5" maxlength="4" name="sig_images_max_limit" value="{SIG_IMAGES_MAX_LIMIT}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_MAX_SIG_IMAGES_SIZE}:</b><br /><span class="gensmall">{L_MAX_AVATAR_SIZE_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="5" maxlength="4" name="sig_images_max_height" value="{SIG_IMAGES_MAX_HEIGHT}" /> x <input class="post" type="text" size="5" maxlength="4" name="sig_images_max_width" value="{SIG_IMAGES_MAX_WIDTH}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_PROFILE_SIG}:</b></td>
	<td class="row2"><input type="radio" name="profile_show_sig" value="1" {PROFILE_SIG_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="profile_show_sig" value="0" {PROFILE_SIG_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_IGNORE_SIGAV}:</b><br /><span class="gensmall">{L_IGNORE_SIGAV_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="enable_ignore_sigav" value="1" {IGNORE_SIGAV_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="enable_ignore_sigav" value="0" {IGNORE_SIGAV_NO} /> {L_NO}</td>
</tr>
<!-- END switch_signature -->

<!-- BEGIN switch_teamspeak -->
<tr>
	<td class="row1" width="50%"><b>{L_ENABLE_FORUM_MODULE_TEAMSPEAK}:</b><br /><span class="gensmall">{L_ENABLE_FORUM_MODULE_TEAMSPEAK_EXPLAIN}</span></td>
      	<td class="row2"><input type="radio" name="forum_module_teamspeak" value="1" {S_FORUM_MODULE_TEAMSPEAK_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="forum_module_teamspeak" value="0" {S_FORUM_MODULE_TEAMSPEAK_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_TS_WIN_HEIGHT}:</b></td>
	<td class="row2"><input class="post" type="text" size="5" maxlength="4" name="ts_winheight" value="{TS_WIN_HEIGHT}" /> px</td>
</tr>
<tr>
	<td class="row1"><b>{L_TS_SITE_TITLE}:</b><br /><span class="gensmall">{L_TS_SITE_TITLE_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="35" maxlength="100" name="ts_sitetitle" value="{TS_SITE_TITLE}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_TS_SERVER_ADDRESS}:</b><br /><span class="gensmall">{L_TS_SERVER_ADDRESS_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="20" maxlength="50" name="ts_serveraddress" value="{TS_SERVER_ADDRESS}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_TS_SERVER_PASSWORD}:</b><br /><span class="gensmall">{L_TS_SERVER_PASSWORD_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="20" maxlength="32" name="ts_serverpasswort" value="{TS_SERVER_PASSWORD}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_TS_SERVER_QUERY_PORT}:</b><br /><span class="gensmall">{L_TS_SERVER_QUERY_PORT_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="8" maxlength="10" name="ts_serverqueryport" value="{TS_SERVER_QUERY_PORT}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_TS_SERVER_UDP_PORT}:</b><br /><span class="gensmall">{L_TS_SERVER_UDP_PORT_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="8" maxlength="10" name="ts_serverudpport" value="{TS_SERVER_UDP_PORT}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_TS_REFRESH_TIME}:</b><br /><span class="gensmall">{L_TS_REFRESH_TIME_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="5" maxlength="4" name="ts_refreshtime" value="{TS_REFRESH_TIME}" /></td>
</tr>
<!-- END switch_teamspeak -->

<!-- BEGIN switch_whosonline -->
<tr> 
   	<td class="row1" width="50%"><b>{L_ENABLE_INVISIBLE}:</b></td> 
   	<td class="row2"><input type="radio" name="allow_invisible_link" value="1" {ENABLE_INVISIBLE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_invisible_link" value="0" {ENABLE_INVISIBLE_NO} /> {L_NO}</td> 
</tr> 
<tr>
	<td class="row1"><b>{L_SESSION_LENGTH}:</b><br /><span class="gensmall">{L_SESSION_LENGTH_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" maxlength="5" size="5" name="session_length" value="{SESSION_LENGTH}" /></td>
</tr>
<tr> 
	<td class="row1"><b>{L_WHOSONLINE_TIME}:</b><br /><span class="gensmall">{L_WHOSONLINE_TIME_EXPLAIN}</span></td> 
	<td class="row2"><input class="post" type="text" size="5" maxlength="3" name="whosonline_time" value="{WHOSONLINE_TIME}" /></td> 
</tr>
<tr>
	<td class="row1"><b>{L_BOARD_HITS}:</b><br /><span class="gensmall">{L_BOARD_HITS_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="board_hits" value="1" {BOARD_HITS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="board_hits" value="0" {BOARD_HITS_NO} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_UNIQUEHITS_TIME}:</b><br /><span class="gensmall">{L_UNIQUEHITS_TIME_EXPLAIN}</span></td> 
	<td class="row2"><input class="post" type="text" size="5" maxlength="5" name="uniquehits_time" value="{UNIQUEHITS_TIME}" /></td> 
</tr>
<tr> 
	<td class="row1"><b>{L_RECORD_ONLINE_USERS}:</b></td> 
	<td class="row2"><input class="post" type="text" size="10" maxlength="10" name="record_online_users" value="{RECORD_ONLINE_USERS}" /></td> 
</tr>
<tr> 
	<td class="row1"><b>{L_RECORD_ONLINE_USERS_ONEDAY}:</b></td> 
	<td class="row2"><input class="post" type="text" size="10" maxlength="10" name="record_day_users" value="{RECORD_DAY_USERS}" /></td> 
</tr>
<!-- END switch_whosonline -->

<tr>
	<td colspan="2" align="center" class="catBottom">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" name="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</form></table>