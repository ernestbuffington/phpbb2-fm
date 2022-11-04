{FORUM_MENU}
{MOD_CP_MENU}
</div></td>
<td valign="top" width="78%">

<script language="JavaScript" type="text/javascript" src="../templates/js/colorpicker.js"></script>
<script language="javascript" type="text/javascript">
<!--
var cp = new ColorPicker();

function update_icon(newimage)
{
	document.icon_image.src = "{S_ICON_BASEDIR}/" + newimage;
}
//-->
</script>

<h1>{L_FORUM_TITLE}</h1>

<p>{L_FORUM_EXPLAIN}</p>

<table width="100%" cellpadding="4" cellspacing="1" class="forumline" align="center"><form action="{S_FORUM_ACTION}" method="post">
<tr> 
	<th class="thHead" colspan="2">{L_FORUM_SETTINGS}</th>
</tr>
<tr> 
	<td class="row1" width="45%"><b>{L_FORUM_NAME}:</b></td>
	<td class="row2"><input class="post" type="text" size="35" name="forumname" value="{FORUM_NAME}" /></td>
</tr>
<tr> 
	<td class="row1"><b>{L_FORUM_DESCRIPTION}:</b><br /><span class="gensmall">{L_FORUM_DESCRIPTION_EXPLAIN}</span></td>
	<td class="row2"><textarea rows="5" cols="35" wrap="virtual" name="forumdesc" class="post">{DESCRIPTION}</textarea></td>
</tr>
<tr> 
	<td class="row1"><b>{L_FORUM_RULES}:</b><br /><span class="gensmall">{L_FORUM_DESCRIPTION_EXPLAIN}</span></td>
	<td class="row2"><textarea rows="5" cols="35" wrap="virtual" name="forum_rules" class="post">{FORUM_RULES}</textarea></td>
</tr>
<tr> 
	<td class="row1"><b>{L_CATEGORY}:</b></td>
	<td class="row2"><select name="c">{S_CAT_LIST}</select></td>
</tr>
<tr> 
	<td class="row1"><b>{L_FORUM_STATUS}:</b></td>
	<td class="row2"><select name="forumstatus">{S_STATUS_LIST}</select></td>
</tr>
<tr> 
	<td class="row1"><b>{L_SORT}:</b></td>
	<td class="row2"><select name="forumsort">{S_SORT_ORDER}</select></td>
</tr>
<tr> 
	<td class="row1"><b>{L_FORUM_ICON}:</b></td>
	<td class="row2"><select name="forumicon" onchange="update_icon(this.options[selectedIndex].value);">{S_FILENAME_OPTIONS}</select> &nbsp; <img name="icon_image" src="{FORUM_ICON}" alt="" title="" /> &nbsp;</td>
</tr>
<tr>
	<td class="row1"><b>{L_SUBTEMPLATE}:</b></td>
	<td class="row2">{FORUM_TEMPLATE}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_PASSWORD}:</b></td>
	<td class="row2"><input class="post" type="text" name="password" value="{FORUM_PASSWORD}" size="30" maxlength="20" /></td>
</tr>
<tr> 
	<td class="row1"><b>{L_FORUM_REGDATE_LIMIT}:</b><br /><span class="gensmall">{L_FORUM_REGDATE_LIMIT_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" name="forum_regdate_limit" value="{FORUM_REGDATE_LIMIT}" size="10" maxlength="5" /></td>
</tr>
<tr> 
	<td class="row1"><b>{L_FORUM_ENTER_LIMIT}:</b><br /><span class="gensmall">{L_FORUM_ENTER_LIMIT_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" name="forum_enter_limit" value="{FORUM_ENTER_LIMIT}" size="10" maxlength="8" /></td>
</tr>
<tr> 
	<td class="row1"><b>{L_FORUM_VIEWS}:</b></td>
	<td class="row2"><input type="text" size="10" maxlength="50" name="forum_views" value="{FORUM_VIEWS}" class="post" /></td>
</tr>
<tr> 
	<td class="row1"><b>{L_AUTO_PRUNE}:</b></td>
	<td class="row2"><table cellspacing="0" cellpadding="1">
	<tr> 
		<td align="right" valign="middle">{L_ENABLED}</td>
		<td valign="middle"><input type="checkbox" name="prune_enable" value="1" {S_PRUNE_ENABLED} /></td>
	</tr>
	<tr> 
		<td align="right" valign="middle">{L_PRUNE_DAYS}</td>
		<td valign="middle">&nbsp;<input type="text" name="prune_days" value="{PRUNE_DAYS}" size="5" class="post" />&nbsp;{L_DAYS}</td>
	</tr>
	<tr> 
		<td align="right" valign="middle">{L_PRUNE_FREQ}</td>
		<td valign="middle">&nbsp;<input type="text" name="prune_freq" value="{PRUNE_FREQ}" size="5" class="post" />&nbsp;{L_DAYS}</td>
	</tr>
	</table></td>
</tr>
<tr> 
	<td class="row1"><b>{L_AUTO_MOVE}:</b></td>
	<td class="row2"><table cellspacing="0" cellpadding="1">
	<tr> 
		<td align="right" valign="middle">{L_ENABLED}</td>
		<td valign="middle"><input type="checkbox" name="move_enable" value="1" {S_MOVE_ENABLED} /></td>
	</tr>
	<tr> 
		<td align="right" valign="middle">{L_MOVE_DAYS}</td>
		<td valign="middle">&nbsp;<input type="text" name="move_days" value="{MOVE_DAYS}" size="5" class="post" />&nbsp;{L_DAYS}</td>
	</tr>
	<tr> 
		<td align="right" valign="middle">{L_MOVE_FREQ}</td>
		<td valign="middle">&nbsp;<input type="text" name="move_freq" value="{MOVE_FREQ}" size="5" class="post" />&nbsp;{L_DAYS}</td>
	</tr>
	<tr> 
		<td align="right" valign="middle">{L_MOVE_DEST}</td>
		<td valign="middle">&nbsp;{S_FORUM_LIST}</td>
	</tr>
	</table></td>
</tr>
<tr> 
	<td class="row1"><b>{L_FORUM_TOPICS} / {L_FORUM_POSTS}:</b></td>
	<td class="row2">{TOPICS} / {POSTS}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_FORUM_ISSUB}:</b></td>
	<td class="row2"><input type="checkbox" name="issub_enable" {S_ISSUB_ENABLED} disabled="disabled" /></select></td>
</tr>
<tr> 
	<th class="thHead" colspan="2">{L_EXTERNAL_SETTINGS}</th>
</tr>
<tr> 
	<td class="row1"><b>{L_FORUM_EXTERNAL}:</b></td>
	<td class="row2"><input type="radio" name="forum_external" value="1" {FORUM_EXTERNAL_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="forum_external" value="0" {FORUM_EXTERNAL_NO} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_FORUM_EXT_NEWWIN}:</b></td>
	<td class="row2"><input type="radio" name="forum_ext_newwin" value="1" {FORUM_EXT_NEWWIN_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="forum_ext_newwin" value="0" {FORUM_EXT_NEWWIN_NO} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_FORUM_REDIRECT_URL}:</b></td>
	<td class="row2"><input type="text" name="forum_redirect_url" value="{FORUM_REDIRECT_URL}" size="35" class="post" /></td>
</tr>
<tr> 
	<td class="row1"><b>{L_FORUM_EXT_IMAGE}:</b></td>
	<td class="row2"><input type="text" name="forum_ext_image" value="{FORUM_EXT_IMAGE}" size="35" class="post" /></td>
</tr>
<tr> 
	<th class="thHead" colspan="2">{L_FORUM_TOGGLES}</th>
</tr>
<tr>
	<td class="row1"><b>{L_HIDE_STATUS}:</b></td>
	<td class="row2"><input type="radio" name="hide_forum_on_index" value="1" {HIDE_STATUS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="hide_forum_on_index" value="0" {HIDE_STATUS_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_HIDE_CAT_STATUS}:</b><br /><span class="gensmall">{L_HIDE_CAT_STATUS_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="hide_forum_in_cat" value="1" {HIDE_CAT_STATUS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="hide_forum_in_cat" value="0" {HIDE_CAT_STATUS_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_DISPLAY_MODERATORS}:</b></td>
	<td class="row2"><input type="radio" name="display_moderators" value="1" {MODERATORS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="display_moderators" value="0" {MODERATORS_NO} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_FORUM_TOGGLE}:</b></td>
	<td class="row2"><input type="radio" name="forum_toggle" value="1" {FORUM_TOGGLE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="forum_toggle" value="0" {FORUM_TOGGLE_NO} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_INDEX_LASTTITLE}:</b></td>
	<td class="row2"><input type="radio" name="index_lasttitle" value="1" {INDEX_LASTTITLE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="index_lasttitle" value="0" {INDEX_LASTTITLE_NO} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_INDEX_POSTS}:</b></td>
	<td class="row2"><input type="radio" name="index_posts" value="1" {INDEX_POSTS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="index_posts" value="0" {INDEX_POSTS_NO} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_SUBJECT_CHECK}</b></td>
	<td class="row2"><input type="radio" name="forum_subject_check" value="1" {SUBJECT_CHECK_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="forum_subject_check" value="0" {SUBJECT_CHECK_NO} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_STOP_BUMPING}:</b><br /><span class="gensmall">{L_STOP_BUMPING_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="stop_bumping" value="1" {STOP_BUMPING_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="stop_bumping" value="0" {STOP_BUMPING_NO} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_ICON_ENABLED}:</b></td> 
 	<td class="row2"><input type="radio" name="icon_enable" value="1" {ICON_ENABLE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="icon_enable" value="0" {ICON_ENABLE_NO} /> {L_NO}</td>
</tr> 
<tr> 
	<td class="row1"><b>{L_ANSWERED_ENABLED}:</b></td> 
    	<td class="row2"><input type="radio" name="answered_enable" value="1" {ANSWERED_ENABLE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="answered_enable" value="0" {ANSWERED_ENABLE_NO} /> {L_NO}</td>
</tr> 
<tr> 
	<td class="row1"><b>{L_POINTS_DISABLED}:</b></td>
    	<td class="row2"><input type="radio" name="points_disabled" value="0" {POINTS_DISABLED_NO} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="points_disabled" value="1" {POINTS_DISABLED_YES} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_FORUM_THANK}:</b></td>
    	<td class="row2"><input type="radio" name="forumthank" value="1" {THANK_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="forumthank" value="0" {THANK_NO} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_TOPIC_PASSWORD}:</b><br /><span class="gensmall">{L_TOPIC_PASSWORD_EXPLAIN}</span></td>
    	<td class="row2"><input type="radio" name="topic_password" value="1" {TOPIC_PASSWORD_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="topic_password" value="0" {TOPIC_PASSWORD_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_AMAZON_DISPLAY}:</b></td>
	<td class="row2"><input type="radio" name="amazon_display" value="1" {AMAZON_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="amazon_display" value="0" {AMAZON_NO} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_DISPLAY_PIC_ALERT}:</b><br /><span class="gensmall">{L_DISPLAY_PIC_ALERT_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="display_pic_alert" value="1" {DISPLAY_PIC_ALERT_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="display_pic_alert" value="0" {DISPLAY_PIC_ALERT_NO} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_IMAGE_EVER_THUMBS}:</b><br /><span class="gensmall">{L_IMAGE_EVER_THUMBS_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="image_ever_thumb" value="1" {IMAGE_EVER_THUMBS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="image_ever_thumb" value="0" {IMAGE_EVER_THUMBS_NO} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_DIGEST}:</b></td> 
	<td class="row2"><input type="radio" name="forum_digest" value="1" {FORUM_DIGEST_CHECKED_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="forum_digest" value="0" {FORUM_DIGEST_CHECKED_NO} /> {L_NO}</td> 
</tr>
<tr>
	<td class="row1"><b>{L_EVENTS_FORUM}:</b></td>
	<td class="row2"><input type="radio" name="events_forum" value="1" {CAL_EVENTS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="events_forum" value="0" {CAL_EVENTS_NO} /> {L_NO}</td>
</tr>
{EVENT_TYPES_BOX}
<tr> 
	<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{S_SUBMIT_VALUE}" class="mainoption" />&nbsp;&nbsp;<input name="reset" type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</form></table>

<script language="JavaScript" type="text/javascript">
<!--
cp.writeDiv()
//-->
</script>