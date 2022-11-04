{POST_MENU}{VOTE_MENU}{ATTACH_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_PAGE_TITLE}</h1>

<p>{L_PAGE_EXPLAIN}</p>

<table width="100%" align="center"><form action="{S_CONFIG_ACTION}" method="post">
<tr>
	<td align="right">{CONFIG_SELECT} <input type="submit" name="config" value="{L_GO}" class="liteoption" /></td>
</tr>
</form></table>
<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline"><form action="{S_CONFIG_ACTION}" method="post">
<tr>
	<th class="thHead" colspan="2">{L_PAGE_TITLE}</th>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_VISUAL_CONFIRM_POSTING}:</b><br /><span class="gensmall">{L_VISUAL_CONFIRM_POSTING_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="enable_confirm_posting" value="1" {CONFIRM_POSTING_ENABLE} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="enable_confirm_posting" value="0" {CONFIRM_POSTING_DISABLE} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_DAILY_POST_LIMIT}:</b><br /><span class="gensmall">{L_DAILY_POST_LIMIT_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="daily_post_limit" value="1" {DAILY_POST_LIMIT_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="daily_post_limit" value="0" {DAILY_POST_LIMIT_NO} /> {L_NO}</td>
</tr>
<tr>
      	<td class="row1"><b>{L_DISABLE_POST_EDITING}:</b><br /><span class="gensmall">{L_DISABLE_POST_EDITING_EXPLAIN}</span></td>
      	<td class="row2"><input class="post" type="text" size="5" maxlength="3" name="post_edit_time_limit" value="{EDITING_TIME}" /> {L_HOURS}</td>
</tr>
<tr>
	<td class="row1"><b>{L_FLOOD_INTERVAL}:</b><br /><span class="gensmall">{L_FLOOD_INTERVAL_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="5" maxlength="4" name="flood_interval" value="{FLOOD_INTERVAL}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_MAX_POLL_OPTIONS}:</b></td>
	<td class="row2"><input class="post" type="text" name="max_poll_options" size="5" maxlength="4" value="{MAX_POLL_OPTIONS}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_VOTE_MIN_POSTS}:</b></td>
	<td class="row2"><input class="post" type="text" maxlength="10" size="5" name="vote_min_posts" value="{VOTE_MIN_POSTS}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_ENABLE_NULL_VOTE}:</b><br /><span class="gensmall">{L_ENABLE_NULL_VOTE_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="null_vote" value="1" {NULL_VOTE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="null_vote" value="0" {NULL_VOTE_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_MESSAGE_LENGTH}:</b><br /><span class="gensmall">{L_MESSAGE_LENGTH_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="4" maxlength="8" name="message_minlength" value="{MESSAGE_MINLENGTH}" /> {L_MIN}&nbsp;&nbsp;<input class="post" type="text" size="4" maxlength="8" name="message_maxlength" value="{MESSAGE_MAXLENGTH}" /> {L_MAX}</td>
</tr>
<tr>
	<td class="row1"><b>{L_ALLOW_BBCODE}:</b></td>
	<td class="row2"><input type="radio" name="allow_bbcode" value="1" {BBCODE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_bbcode" value="0" {BBCODE_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_MAX_IMAGES_LIMIT}:</b></td>
	<td class="row2"><input class="post" type="text" size="5" maxlength="4" name="post_images_max_limit" value="{IMAGES_MAX_LIMIT}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_MAX_IMAGES_SIZE}:</b><br /><span class="gensmall">{L_MAX_AVATAR_SIZE_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="5" maxlength="4" name="post_images_max_height" value="{IMAGES_MAX_HEIGHT}" /> x <input class="post" type="text" size="5" maxlength="4" name="post_images_max_width" value="{IMAGES_MAX_WIDTH}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_REDUCE_IMGS}:</b><br /><span class="gensmall">{L_REDUCE_IMGS_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="reduce_bbcode_imgs" value="1" {REDUCE_IMGS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="reduce_bbcode_imgs" value="0" {REDUCE_IMGS_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_MISSING_IMGS}:</b><br /><span class="gensmall">{L_MISSING_IMGS_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="missing_bbcode_imgs" value="1" {MISSING_IMGS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="missing_bbcode_imgs" value="0" {MISSING_IMGS_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_ALLOW_HTML}:</b></td>
	<td class="row2"><input type="radio" name="allow_html" value="1" {HTML_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_html" value="0" {HTML_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_ALLOWED_TAGS}:</b><br /><span class="gensmall">{L_ALLOWED_TAGS_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="30" maxlength="255" name="allow_html_tags" value="{HTML_TAGS}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_ALLOW_SWEARYWORDS}:</b><br /><span class="gensmall">{L_ALLOW_SWEARYWORDS_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="allow_swearywords" value="1" {SWEARYWORDS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_swearywords" value="0" {SWEARYWORDS_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_ALLOW_CUSTOM_POST_COLOR}:</b><br /><span class="gensmall">{L_ALLOW_CUSTOM_POST_COLOR_EXPLAIN}</span></td>
	<td class="row2"><input type="text" class="post" size="4" maxlength="10" name="allow_custom_post_color" value="{CUSTOM_POST_COLOR}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_STOP_BUMPING}:</b><br /><span class="gensmall">{L_STOP_BUMPING_EXPLAIN}</span></td>
	<td class="row2">&nbsp;<input type="radio" name="stop_bumping" value="0" {STOP_BUMPING_NO} /> {L_NO}<br />&nbsp;<input type="radio" name="stop_bumping" value="1" {STOP_BUMPING_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="stop_bumping" value="2" {STOP_BUMPING_FS} /> {L_FS}</td>
</tr>
<tr>
	<td class="row1"><b>{L_TOPIC_REDIRECT}:</b><br /><span class="gensmall">{L_TOPIC_REDIRECT_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="topic_redirect" value="1" {TOPIC_REDIRECT_YES} /> {L_TOPIC}&nbsp;&nbsp;<input type="radio" name="topic_redirect" value="0" {TOPIC_REDIRECT_NO} /> {L_FORUM}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_VIEWTOPIC_USERINFO}:</b></td> 
	<td class="row2"><input type="radio" name="collapse_userinfo" value="1" {VIEWTOPIC_USERINFO_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="collapse_userinfo" value="0" {VIEWTOPIC_USERINFO_NO} /> {L_NO}</td> 
</tr>
<tr>
	<td class="row1"><b>{L_IGNORE_SIGAV}:</b><br /><span class="gensmall">{L_IGNORE_SIGAV_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="enable_ignore_sigav" value="1" {IGNORE_SIGAV_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="enable_ignore_sigav" value="0" {IGNORE_SIGAV_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_MODS_VIEWIPS}:</b><br /><span class="gensmall">{L_MODS_VIEWIPS_EXPLAIN}</span><br /></td>
	<td class="row2"><input type="radio" name="mods_viewips" value="1"{MODS_VIEWIPS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="mods_viewips" value="0"{MODS_VIEWIPS_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_DISABLE_TOPIC_WATCHING}:</b><br /><span class="gensmall">{L_DISABLE_TOPIC_WATCHING_EXPLAIN}</span><br /></td>
	<td class="row2"><input type="radio" name="disable_topic_watching" value="0"{DISABLE_TOPIC_WATCHING_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="disable_topic_watching" value="1"{DISABLE_TOPIC_WATCHING_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="spaceRow" colspan="2"><img src="../images/spacer.gif" alt="" width="1" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_HL_ENABLE}:</b></td>
	<td class="row2"><input type="radio" name="hl_enable" value="1" {HL_ENABLE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="hl_enable" value="0" {HL_ENABLE_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_HL_NECESSARY_POST_NUMBER}:</b></td>
	<td class="row2"><input class="post" type="text" name="hl_necessary_post_number" size="5" maxlength="4" value="{HL_NECESSARY_POST_NUMBER}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_HL_MODS_PRIORITY}:</b><br /><span class="gensmall">{L_HL_MODS_PRIORITY_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="hl_mods_priority" value="1" {HL_MODS_PRIORITY_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="hl_mods_priority" value="0" {HL_MODS_PRIORITY_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="spaceRow" colspan="2"><img src="../images/spacer.gif" alt="" width="1" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_ENABLE_WRAP}:</b></td>
	<td class="row2"><input type="radio" name="wrap_enable" value="1" {WRAP_ENABLE} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="wrap_enable" value="0" {WRAP_DISABLE} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_WRAP_MIN}:</b></td>
	<td class="row2"><input class="post" type="text" size="4" maxlength="3" name="wrap_min" value="{WRAP_MIN}" /> {L_WRAP_UNITS}</td>
</tr>
<tr>
	<td class="row1"><b>{L_WRAP_DEF}:</b></td>
	<td class="row2"><input class="post" type="text" size="4" maxlength="3" name="wrap_def" value="{WRAP_DEF}" /> {L_WRAP_UNITS}</td>
</tr>
<tr>
	<td class="row1"><b>{L_WRAP_MAX}:</b></td>
	<td class="row2"><input class="post" type="text" size="4" maxlength="3" name="wrap_max" value="{WRAP_MAX}" /> {L_WRAP_UNITS}</td>
</tr>
<tr>
	<td class="spaceRow" colspan="2"><img src="../images/spacer.gif" alt="" width="1" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_SUBCHK_FORUM}</b><br /><span class="gensmall">{L_SUBCHK_FORUM_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="subchk_enable" value="1" {SUBCHK_ENABLE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="subchk_enable" value="0" {SUBCHK_ENABLE_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_SUBCHK_LOCKED}</b></td>
	<td class="row2"><input type="radio" name="subchk_locked" value="1" {SUBCHK_LOCKED_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="subchk_locked" value="0" {SUBCHK_LOCKED_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_SUBCHK_STRICT}</b><br /><span class="gensmall">{L_SUBCHK_STRICT_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="subchk_strict" value="1" {SUBCHK_STRICT_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="subchk_strict" value="0" {SUBCHK_STRICT_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_SUBCHK_BYPASS}</b><br /><span class="gensmall">{L_SUBCHK_BYPASS_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="subchk_bypass" value="1" {SUBCHK_BYPASS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="subchk_bypass" value="0" {SUBCHK_BYPASS_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_SUBCHK_ADMIN}</b></td>
	<td class="row2"><input type="radio" name="subchk_admin" value="1" {SUBCHK_ADMIN_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="subchk_admin" value="0" {SUBCHK_ADMIN_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_SUBCHK_MOD}</b></td>
	<td class="row2"><input type="radio" name="subchk_mod" value="1" {SUBCHK_MOD_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="subchk_mod" value="0" {SUBCHK_MOD_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_SUBCHK_LIMIT}:</b><br /><span class="gensmall">{L_SUBCHK_LIMIT_EXPLAIN}</span></td>
	<td class="row2"><input type="text" name="subchk_limit" class="post" value="{SUBCHK_LIMIT}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_SUBCHK_COUNT}:</b><br /><span class="gensmall">{L_SUBCHK_COUNT_EXPLAIN}</span></td>
	<td class="row2"><input type="text" name="subchk_postcount" size="5" class="post" value="{SUBCHK_POSTCOUNT}" /></td>
</tr>
<tr>
	<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</form></table>
