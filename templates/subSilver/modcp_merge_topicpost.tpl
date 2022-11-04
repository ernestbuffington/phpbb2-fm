<table width="100%" cellspacing="2" cellpadding="2" align="center">
<tr> 
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> <a href="{U_VIEW_FORUM}" class="nav">{FORUM_NAME}</a></td>
 	<td align="right" valign="top" class="nav">{PAGE_NUMBER}<br />{PAGINATION}</td>
</tr>
</table>

<table width="100%" cellpadding="4" cellspacing="1" class="forumline"><form method="post" action="{S_MODCP_ACTION}">
<tr> 
	<td class="catHead" colspan="6"><span class="cattitle">{L_MOD_CP}</span></td>
</tr>
<tr> 
	<td class="row2" colspan="6" align="center"><span class="gensmall">{L_MOD_CP_EXPLAIN}</span></td>
</tr>
<tr> 
	<th class="thCornerL" colspan="2" nowrap="nowrap">&nbsp;{L_TOPICS}&nbsp;</th>
	<th class="thTop" width="100" nowrap="nowrap">&nbsp;{L_AUTHOR}&nbsp;</th>
	<th width="50" class="thTop" nowrap="nowrap">&nbsp;{L_REPLIES}&nbsp;</th>
	<th width="150" class="thTop" nowrap="nowrap">&nbsp;{L_LASTPOST}&nbsp;</th>
	<th width="5%" class="thCornerR" nowrap="nowrap">&nbsp;{L_SELECT}&nbsp;</th>
</tr>
<!-- BEGIN topicrow -->
<tr> 
	<td width="4%" class="row1" align="center" valign="middle"><img src="{topicrow.TOPIC_FOLDER_IMG}" alt="{topicrow.L_TOPIC_FOLDER_ALT}" title="{topicrow.L_TOPIC_FOLDER_ALT}" /></td>
	<td class="row1">&nbsp;<span class="topictitle">{topicrow.TOPIC_ATTACHMENT_IMG}{topicrow.TOPIC_TYPE}<a href="{topicrow.U_VIEW_TOPIC}" title="{topicrow.L_TOPIC_STARTED}: {topicrow.FIRST_POST_TIME}" class="topictitle">{topicrow.TOPIC_TITLE}</a></span></td>
	<td class="row1" align="center"><span class="postdetails">{topicrow.USERNAME}</span></td>
	<td class="row2" align="center" valign="middle"><span class="postdetails">{topicrow.REPLIES}</span></td>
	<td class="row1" align="center" valign="middle"><span class="postdetails">{topicrow.LAST_POST_TIME}</span></td>
	<td class="row2" align="center" valign="middle"><input type="radio" name="topic_id_to" value="{topicrow.TOPIC_ID}" /></td>
</tr>
<!-- END topicrow -->
<tr align="right"> 
	<td class="catBottom" colspan="6" height="29">{S_HIDDEN_FIELDS}
	<!-- BEGIN switch_shadow_topic --> 
	<span class="genmed"><input type="checkbox" name="merge_leave_shadow" />{L_LEAVESHADOW}</span>
	&nbsp;&nbsp;
	<!-- END switch_shadow_topic -->
	<input class="mainoption" type="submit" name="confirm" value="{L_MERGE}" /></td>
</tr>
</table>
<table width="100%" cellspacing="2" align="center" cellpadding="2">
<tr> 
	<td valign="middle" class="nav">{PAGE_NUMBER}</td>
	<td align="right" nowrap="nowrap" class="nav">{PAGINATION}</td>
</tr>
</form></table>
<table width="100%" cellspacing="0" cellpadding="0">
<tr> 
	<td align="right">{JUMPBOX}</td>
</tr>
</table>