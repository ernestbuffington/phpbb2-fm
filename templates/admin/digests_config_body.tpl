{DIGESTS_MENU}{EMAIL_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_DIGEST_CONFIG_TITLE}</h1>

<p>{L_DIGEST_CONFIG_EXPLAIN}</p>

<table width="100%" align="center"><form action="{S_CONFIG_ACTION}" method="post">
<tr>
	<td align="right">{CONFIG_SELECT} <input type="submit" name="config" value="{L_GO}" class="liteoption" /></td>
</tr>
</form></table>
<table width="100%" cellpadding="4" cellspacing="1" class="forumline" align="center"><form method="post" action="{S_CONFIG_ACTION}">

<!-- BEGIN switch_config -->
<tr>
	<th class="thHead" colspan="2">{L_DIGEST_CONFIG_TITLE}</th>
</tr>
<tr> 
	<td class="row1" width="50%"><b>{L_DIGEST_DISABLE_USER}:</b></td> 
	<td class="row2"><input type="radio" name="digest_disable_user" value="1" {DIGEST_DISABLE_USER_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="digest_disable_user" value="0" {DIGEST_DISABLE_USER_NO} /> {L_NO}</td> 
</tr>
<tr> 
	<td class="row1"><b>{L_DIGEST_DISABLE_GROUP}:</b></td> 
      	<td class="row2"><input type="radio" name="digest_disable_group" value="2" {DIGEST_DISABLE_GROUP_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="digest_disable_group" value="0" {DIGEST_DISABLE_GROUP_NO} /> {L_NO}</td> 
</tr>
<tr> 
	<td class="row1"><b>{L_DIGEST_TEST_MODE}:</b><br /><span class="gensmall">{L_DIGEST_TEST_MODE_EXPLAIN}</span></td> 
      	<td class="row2"><input type="radio" name="test_mode" value="1" {DIGEST_TEST_MODE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="test_mode" value="0" {DIGEST_TEST_MODE_NO} /> {L_NO}</td> 
</tr>
<tr> 
	<td class="row1"><b>{L_DIGEST_ALLOW_DIRECT}:</b><br /><span class="gensmall">{L_DIGEST_ALLOW_DIRECT_EXPLAIN}</span></td> 
      	<td class="row2"><input type="radio" name="allow_direct_run" value="1" {DIGEST_DIRECT_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_direct_run" value="0" {DIGEST_DIRECT_NO} /> {L_NO}</td> 
</tr>
<tr> 
	<td class="row1"><b>{L_DIGEST_DIRECT_PASS}:</b></td> 
      	<td class="row2"><input type="password" name="direct_password" size="20" class="post" /></td> 
</tr>
<tr> 
	<td class="row1"><b>{L_DIGEST_TIME_OPTION}:</b><br /><span class="gensmall">{L_DIGEST_TIME_EXPLAIN}</span></td> 
      	<td class="row2"><input type="radio" name="use_system_time" value="1" {DIGEST_USE_SYSTEM_TIME_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="use_system_time" value="0" {DIGEST_USE_SYSTEM_TIME_NO} /> {L_NO}</td> 
</tr>
<tr> 
	<td class="row1"><b>{L_DIGEST_TIME}:</b></td> 
      	<td class="row2"><input class="post" type="text" name="run_time" size="5" maxlength="2" value="{DIGEST_TIME}" />&nbsp;{L_DIGEST_HOUR}</td> 
</tr>
<tr> 
	<td class="row1"><b>{L_DIGEST_WEEKLY_DAY}:</b></td> 
      	<td class="row2">{DAYS_SELECT}</td> 
</tr>
<tr> 
	<td class="row1"><b>{L_DIGEST_MONTHLY_DAY}:</b></td> 
      	<td class="row2"><input class="post" type="text" name="monthly_day" size="5" maxlength="2" value="{DIGEST_MONTHLY_DAY}" /></td> 
</tr>
<tr> 
	<td class="row1"><b>{L_DIGEST_ALLOW_EXCLUDE}:</b><br /><span class="gensmall">{L_DIGEST_ALLOW_EXCLUDE_EXPLAIN}</span></td> 
      	<td class="row2"><input type="radio" name="allow_exclude" value="1" {DIGEST_ALLOW_EXCLUDE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_exclude" value="0" {DIGEST_ALLOW_EXCLUDE_NO} /> {L_NO}</td> 
</tr>
<tr> 
	<td class="row1"><b>{L_DIGEST_SHOW_DESC}:</b><br /><span class="gensmall">{L_DIGEST_SHOW_DESC_EXPLAIN}</span></td> 
      	<td class="row2"><input type="radio" name="show_forum_description" value="1" {DIGEST_SHOW_DESC_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="show_forum_description" value="0" {DIGEST_SHOW_DESC_NO} /> {L_NO}</td> 
</tr>
<tr> 
	<td class="row1"><b>{L_ALLOW_URGENT}:</b><br /><span class="gensmall">{L_ALLOW_URGENT_EXPLAIN}</span></td> 
      	<td class="row2"><input type="radio" name="allow_urgent" value="1" {ALLOW_URGENT_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_urgent" value="0" {ALLOW_URGENT_NO} /> {L_NO}</td> 
</tr>
<tr> 
	<td class="row1"><b>{L_RUN_URGENT_ONLY}:</b><br /><span class="gensmall">{L_RUN_URGENT_ONLY_EXPLAIN}</span></td> 
      	<td class="row2"><input type="radio" name="run_urgent_only" value="1" {RUN_URGENT_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="run_urgent_only" value="0" {RUN_URGENT_NO} /> {L_NO}</td> 
</tr>
<tr> 
	<td class="row1"><b>{L_DIGEST_SHORT_TEXT_LENGTH}:</b><br /><span class="gensmall">{L_TEXT_LENGTH_EXPLAIN}</span></td> 
      	<td class="row2">{DIGEST_SHORT_TEXT_LENGTH_SELECT}&nbsp;&nbsp;{L_CHARACTERS}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_DIGEST_SUBJECT}:</b><br /><span class="gensmall">{L_DIGEST_SUBJECT_EXPLAIN}</span></td> 
      	<td class="row2"><input class="post" type="text" name="digest_subject" size="30" maxlength="100" value="{DIGEST_SUBJECT}" /></td> 
</tr>
<tr> 
	<td class="row1"><b>{L_DIGEST_HOME}:</b><br /><span class="gensmall">{L_DIGEST_HOME_EXPLAIN}</span></td> 
      	<td class="row2"><input class="post" type="text" name="home_page" size="20" maxlength="50" value="{DIGEST_HOME}" /></td> 
</tr>
<tr> 
	<td class="row1"><b>{L_DIGEST_PM_NOTIFY}:</b></td> 
      	<td class="row2"><input type="radio" name="pm_notify" value="1" {DIGEST_PM_NOTIFY_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="pm_notify" value="0" {DIGEST_PM_NOTIFY_NO} /> {L_NO}</td> 
</tr>
<tr> 
	<td class="row1"><b>{L_DIGEST_PM_DISPLAY}:</b></td> 
      	<td class="row2"><input type="radio" name="pm_display" value="1" {DIGEST_PM_DISPLAY_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="pm_display" value="0" {DIGEST_PM_DISPLAY_NO} /> {L_NO}</td> 
</tr>
<tr> 
	<td class="row1"><b>{L_DIGEST_SHOW_STATS}:</b><br /><span class="gensmall">{L_DIGEST_SHOW_STATS_EXPLAIN}</span></td> 
      	<td class="row2"><input type="radio" name="show_stats" value="1" {DIGEST_SHOW_STATS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="show_stats" value="0" {DIGEST_SHOW_STATS_NO} /> {L_NO}</td> 
</tr>
<tr> 
	<td class="row1"><b>{L_DIGEST_SHOW_IP}:</b></td> 
      	<td class="row2"><input type="radio" name="show_ip" value="1" {DIGEST_SHOW_IP_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="show_ip" value="0" {DIGEST_SHOW_IP_NO} /> {L_NO}</td> 
</tr>
<tr> 
	<td class="row1"><b>{L_DIGEST_NEW_SIGN_UP}:</b><br /><span class="gensmall">{L_DIGEST_NEW_SIGN_UP_EXPLAIN}</span></td> 
      	<td class="row2"><input type="radio" name="new_sign_up" value="1" {DIGEST_NEW_SIGN_UP_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="new_sign_up" value="0" {DIGEST_NEW_SIGN_UP_NO} /> {L_NO}</td> 
</tr>
<tr> 
	<td class="row1"><b>{L_DIGEST_AUTO_SUBSCRIBE}:</b><br /><span class="gensmall">{L_DIGEST_AUTO_EXPLAIN}</span></td> 
      	<td class="row2"><input type="radio" name="auto_subscribe" value="1" {DIGEST_AUTO_SUBSCRIBE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="auto_subscribe" value="0" {DIGEST_AUTO_SUBSCRIBE_NO} /> {L_NO}</td> 
</tr>
<tr> 
	<td class="row1"><b>{L_DIGEST_AUTO_GROUP}:</b></td> 
      	<td class="row2">{S_GROUP_SELECT}</td> 
</tr>
<tr> 
	<td class="row1"><b>{L_DIGEST_THEME_TYPE}:</b></td> 
      	<td class="row2"><input type="radio" name="theme_type" value="0" {DIGEST_THEME_TYPE_CSS} /> {L_CSS}&nbsp;&nbsp;<input type="radio" name="theme_type" value="1" {DIGEST_THEME_TYPE_TABLE} /> {L_TABLE}&nbsp;&nbsp;<input type="radio" name="theme_type" value="2" {DIGEST_THEME_TYPE_HEADER} /> {L_HEADER}</td> 
</tr>
<tr> 
	<td class="row1"><b>{L_DIGEST_OVERRIDE}:</b></td> 
      	<td class="row2"><input type="radio" name="override_theme" value="1" {DIGEST_OVERRIDE_THEME_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="override_theme" value="0" {DIGEST_OVERRIDE_THEME_NO} /> {L_NO}</td> 
</tr>
<tr> 
	<td class="row1"><b>{L_DIGEST_THEME}:</b></td> 
      	<td class="row2">{DIGEST_STYLE_SELECT}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_DIGEST_DATE_FORMAT}:</b></td> 
      	<td class="row2">{S_DATE_FORMAT_SELECT}</td> 
</tr>
<tr> 
	<td class="row1"><b>{L_DIGEST_ACTIVITY}:</b><br /><span class="gensmall">{L_DIGEST_ACTIVITY_EXPLAIN}</span></td> 
      	<td class="row2"><input type="radio" name="check_user_activity" value="1" {USER_ACTIVITY_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="check_user_activity" value="0" {USER_ACTIVITY_NO} /> {L_NO}</td> 
</tr>
<tr> 
	<td class="row1"><b>{L_DIGEST_THRESHOLD}:</b></td> 
      	<td class="row2"><input class="post" type="text" name="activity_threshold" size="5" maxlength="3" value="{ACTIVITY_THRESHOLD}" /></td> 
</tr>
<tr> 
	<td class="row1"><b>{L_DIGEST_LOGGING}:</b></td> 
      	<td class="row2"><input type="radio" name="digest_logging" value="1" {DIGEST_LOGGING_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="digest_logging" value="0" {DIGEST_LOGGING_NO} /> {L_NO}</td> 
</tr>
<tr> 
	<td class="row1"><b>{L_DIGEST_LOG_DAYS}:</b><br /><span class="gensmall">{L_DIGEST_LOG_DAYS_EXPLAIN}</span></td> 
      	<td class="row2"><input class="post" type="text" name="log_days" size="5" maxlength="2" value="{DIGEST_LOG_DAYS}" /></td> 
</tr>
<tr> 
	<td class="row1"><b>{L_SUPRESS_CRON}:</b></td> 
      	<td class="row2"><input type="radio" name="supress_cron_output" value="1" {SUPRESS_CRON_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="supress_cron_output" value="0" {SUPRESS_CRON_NO} /> {L_NO}</td> 
</tr>
<!-- END switch_config -->

<!-- BEGIN switch_freq -->
<tr>
	<th class="thHead" colspan="2">{L_DIGEST_FREQUENCIES}</th>
</tr>
<tr> 
	<td class="row1" width="50%"><b>{L_DIGEST_HOURLY}:</b></td> 
      	<td class="row2"><input type="radio" name="allow_hours1" value="1" {DIGEST_ALLOW_HOURLY_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_hours1" value="0" {DIGEST_ALLOW_HOURLY_NO} /> {L_NO}</td> 
</tr>
<tr> 
	<td class="row1"><b>{L_DIGEST_2HOURLY}:</b></td> 
      	<td class="row2"><input type="radio" name="allow_hours2" value="1" {DIGEST_ALLOW_2HOURLY_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_hours2" value="0" {DIGEST_ALLOW_2HOURLY_NO} /> {L_NO}</td> 
</tr>
<tr> 
	<td class="row1"><b>{L_DIGEST_4HOURLY}:</b></td> 
      	<td class="row2"><input type="radio" name="allow_hours4" value="1" {DIGEST_ALLOW_4HOURLY_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_hours4" value="0" {DIGEST_ALLOW_4HOURLY_NO} /> {L_NO}</td> 
</tr>
<tr> 
	<td class="row1"><b>{L_DIGEST_6HOURLY}:</b></td> 
      	<td class="row2"><input type="radio" name="allow_hours6" value="1" {DIGEST_ALLOW_6HOURLY_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_hours6" value="0" {DIGEST_ALLOW_6HOURLY_NO} /> {L_NO}</td> 
</tr>
<tr> 
	<td class="row1"><b>{L_DIGEST_8HOURLY}:</b></td> 
      	<td class="row2"><input type="radio" name="allow_hours8" value="1" {DIGEST_ALLOW_8HOURLY_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_hours8" value="0" {DIGEST_ALLOW_8HOURLY_NO} /> {L_NO}</td> 
</tr>
<tr> 
	<td class="row1"><b>{L_DIGEST_12HOURLY}:</b></td> 
      	<td class="row2"><input type="radio" name="allow_hours12" value="1" {DIGEST_ALLOW_12HOURLY_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_hours12" value="0" {DIGEST_ALLOW_12HOURLY_NO} /> {L_NO}</td> 
</tr>
<tr> 
	<td class="row1"><b>{L_DIGEST_DAILY}:</b></td> 
      	<td class="row2"><input type="radio" name="allow_daily" value="1" {DIGEST_ALLOW_DAILY_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_daily" value="0" {DIGEST_ALLOW_DAILY_NO} /> {L_NO}</td> 
</tr>
<tr> 
	<td class="row1"><b>{L_DIGEST_WEEKLY}:</b></td> 
      	<td class="row2"><input type="radio" name="allow_weekly" value="1" {DIGEST_ALLOW_WEEKLY_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_weekly" value="0" {DIGEST_ALLOW_WEEKLY_NO} /> {L_NO}</td> 
</tr>
<tr> 
	<td class="row1"><b>{L_DIGEST_MONTHLY}:</b></td> 
      	<td class="row2"><input type="radio" name="allow_monthly" value="1" {DIGEST_ALLOW_MONTHLY_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_monthly" value="0" {DIGEST_ALLOW_MONTHLY_NO} /> {L_NO}</td> 
</tr>
<!-- END switch_freq -->

<!-- BEGIN switch_defaults -->
<tr>
	<th class="thHead" colspan="2">{L_DIGEST_DEFAULTS}</th>
</tr>
<tr> 
	<td class="row1" width="50%"><b>{L_DIGEST_DEFAULT_FREQUENCY}:</b></td> 
      	<td class="row2">{DIGEST_DEFAULT_FREQUENCY_SELECT}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_DIGEST_DEFAULT_FORMAT}:</b></td> 
      	<td class="row2"><input type="radio" name="default_format" value="1" {DIGEST_DEFAULT_FORMAT_HTML} /> {L_HTML}&nbsp;&nbsp;<input type="radio" name="default_format" value="0" {DIGEST_DEFAULT_FORMAT_TEXT} /> {L_TEXT}</td> 
</tr>
<tr> 
	<td class="row1"><b>{L_TEXT_LENGTH_OPTION}:</b></td> 
      	<td class="row2"><input type="radio" name="default_text_length_type" value="-1" {DIGEST_DEFAULT_TEXT_LENGTH_FULL} /> {L_FULL}&nbsp;&nbsp;<input type="radio" name="default_text_length_type" value="1" {DIGEST_DEFAULT_TEXT_LENGTH_SHORT} /> {L_SHORT}&nbsp;&nbsp;<input type="radio" name="default_text_length_type" value="0" {DIGEST_DEFAULT_TEXT_LENGTH_NONE} /> {L_NO_TEXT}</td> 
</tr>
<tr> 
	<td class="row1"><b>{L_DIGEST_DEFAULT_SHOW_MINE}:</b></td> 
      	<td class="row2"><input type="radio" name="default_show_mine" value="1" {DIGEST_DEFAULT_SHOW_MINE_TRUE} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="default_show_mine" value="0" {DIGEST_DEFAULT_SHOW_MINE_FALSE} /> {L_NO}</td> 
</tr>
<tr> 
	<td class="row1"><b>{L_DIGEST_DEFAULT_NEW_ONLY}:</b></td> 
      	<td class="row2"><input type="radio" name="default_new_only" value="1" {DIGEST_DEFAULT_NEW_ONLY_TRUE} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="default_new_only" value="0" {DIGEST_DEFAULT_NEW_ONLY_FALSE} /> {L_NO}</td> 
</tr>
<tr> 
	<td class="row1"><b>{L_DIGEST_DEFAULT_SEND_ON_NO_MESSAGES}:</b></td> 
      	<td class="row2"><input type="radio" name="default_send_on_no_messages" value="1" {DIGEST_DEFAULT_SEND_ON_NO_MESSAGES_TRUE} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="default_send_on_no_messages" value="0" {DIGEST_DEFAULT_SEND_ON_NO_MESSAGES_FALSE} /> {L_NO}</td> 
</tr>
<!-- END switch_defaults -->

<tr>
	<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</form></table>
