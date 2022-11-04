<script language="JavaScript" type="text/javascript"> 
<!--
function who(topicid)
{
        window.open("viewtopic_posted.php?t="+topicid, "whoposted", "toolbar=no,scrollbars=yes,resizable=yes,width=230,height=300");
}

function who_viewed(topicid)
{
        window.open("viewtopic_viewed.php?t="+topicid, "whoviewed", "toolbar=no,scrollbars=yes,resizable=yes,width=460,height=300");
}
-->
</script>

<table width="100%" cellspacing="2" cellpadding="2" align="center">
<!-- BEGIN switch_search_forum -->
<form method="post" action="search.php?mode=results">
<input type="hidden" name="search_forum" value="{FORUM_ID}">
<input type="hidden" name="show_results" value="topics">
<input type="hidden" name="search_terms" value="any">
<input type="hidden" name="search_fields" value="all">
<!-- END switch_search_forum -->
<!-- BEGIN switch_forum_rules -->
<tr> 
	<td colspan="2"><table width="100%" cellpadding="4" cellspacing="1" class="bodyline"><tr><td class="row1"><b class="gen" style="color: {T_BODY_HLINK}">{L_FORUM_RULES}</b><span class="gensmall"><br /><br />{FORUM_RULES}</span></td></tr></table><br /></td>
</tr>
<!-- END switch_forum_rules -->
<tr> 
	<td colspan="2"><a class="maintitle" href="{U_VIEW_FORUM}" title="{FORUM_DESC}">{FORUM_NAME}</a><br /><span class="genmed">{FORUM_DESC}</span></td>
</tr>
<tr> 
	<td valign="bottom"><span class="gensmall"><b>{L_MODERATOR} {MODERATORS}<br /><br />{TOTAL_USERS_ONLINE}<br/>{LOGGED_IN_USER_LIST}</b><br />&nbsp;</span></td>
	<td align="right" valign="top" nowrap="nowrap">{BANNER_13_IMG}<br /><span class="gensmall"><b>{PAGE_NUMBER}</b> &nbsp;[ {FORUM_TOPICS} {L_TOPICS} ]<br /><b>{PAGINATION}</b></span>
	<!-- BEGIN switch_search_forum -->
	<br /><br /><span class="genmed">{L_SEARCH_FOR}:&nbsp;<input class="post" type="text" name="search_keywords" value="" size="20" maxlength="150" /> <input type="submit" name="submit" value="{L_GO}" class="liteoption" /></span>
	</form>
	<!-- END switch_search_forum -->
	<!-- BEGIN ratingsbox -->
	<form method="get" name="ratingsbox" action="{ratingsbox.U_RATINGS}">
	<input type="hidden" name="forum_id" value="{FORUM_ID}">
	<br /><br /><select name="type">
		<option value="">{ratingsbox.L_LATEST_RATINGS}</option>
		<option value="p">{ratingsbox.L_HIGHEST_RANKED_POSTS}</option>
		<option value="t">{ratingsbox.L_HIGHEST_RANKED_TOPICS}</option>
		<option value="u">{ratingsbox.L_HIGHEST_RANKED_POSTERS}</option>
	</select> <input type="submit" value="{L_GO}" class="liteoption" />
	<!-- END ratingsbox -->
	</td>
</tr>
</form></table>

<table width="100%" cellspacing="2" cellpadding="2" align="center">
<tr>
	<td valign="bottom" width="50"><a href="{U_POST_NEW_TOPIC}"><img src="{POST_IMG}" alt="{L_POST_NEW_TOPIC}" title="{L_POST_NEW_TOPIC}" /></a></td>
	<td valign="bottom" class="nav" width="100%">&nbsp;&nbsp;&nbsp;<a href="{U_INDEX}" class="nav">{L_INDEX}</a>
    	<!-- BEGIN navrow -->
    	<br />&nbsp;&nbsp;&nbsp;-> <a href="{navrow.U_SUBINDEX}" class="nav">{navrow.L_SUBINDEX}</a>
    	<!-- END navrow -->
 	<br />&nbsp;&nbsp;&nbsp;-> <a class="nav" href="{U_VIEW_FORUM}">{FORUM_NAVNAME}</a></td>
	<td align="right" valign="bottom" class="gensmall" nowrap="nowrap"><b>
	<!-- BEGIN switch_user_logged_in -->
	<a href="{U_MARK_READ}" class="gensmall" title="{L_MARK_TOPICS_READ}">{L_MARK_TOPICS_READ}</a> ::
	<!-- END switch_user_logged_in -->
 	{S_WATCH_FORUM}</b></td>
	<td align="right" valign="bottom" width="1"><a href="JavaScript:window.location.reload()"><img src="templates/{T_NAV_STYLE}/icon_refresh.gif" alt="{L_REFRESH_PAGE}" title="{L_REFRESH_PAGE}" /></a></td>
</tr>
</table>

<table cellpadding="4" cellspacing="1" width="100%" class="forumline" id="view_forum">
<tr> 
	<th colspan="{COLSPAN}" align="center" class="thCornerL" nowrap="nowrap">&nbsp;{L_TOPICS}&nbsp;</th>
	<th width="100" align="center" class="thTop" nowrap="nowrap">&nbsp;{L_AUTHOR}&nbsp;</th>
	<th width="50" align="center" class="thTop" nowrap="nowrap">&nbsp;{L_REPLIES}&nbsp;</th>
	<th width="50" align="center" class="thTop" nowrap="nowrap">&nbsp;{L_VIEWS}&nbsp;</th>
	<th colspan="2" align="center" class="thCornerR" nowrap="nowrap">&nbsp;{L_LASTPOST}&nbsp;</th>
</tr>
<tr style="display:none;" id="No_topics"> 
	<td class="row1" colspan="8" height="30" align="center" valign="middle"><span class="gen">{AJAXED_NO_TOPICS}</span></td>
</tr>
<!-- BEGIN topicrow -->
<!-- BEGIN topictype -->
<tr id="topic_{topicrow.TOPIC_ID}" name="topic_{topicrow.TOPIC_ID}">
	<td colspan="8" align="left" class="catLeft"><span class="cattitle">{topicrow.topictype.TITLE}</span></td>
</tr>
<!-- END topictype -->
<tr> 
	<td class="row1" align="center" valign="middle" width="5%" id="topic_{topicrow.TOPIC_ID}_mod" nowrap="nowrap"><img src="{topicrow.TOPIC_FOLDER_IMG}"{topicrow.LOCK_UNLOCK_JS} id="t_{topicrow.TOPIC_ID}" name="t_{topicrow.TOPIC_ID}" alt="{topicrow.L_TOPIC_FOLDER_ALT}" title="{topicrow.L_TOPIC_FOLDER_ALT}" hspace="2" />{topicrow.AJAXED_MOD}</td>
	{topicrow.ICON}
	<td class="row1" width="100%" onMouseOver="this.style.backgroundColor='{T_TR_COLOR2}'; this.style.cursor='hand';" onMouseOut=this.style.backgroundColor="{T_TR_COLOR1}" onclick="window.location.href='{topicrow.U_VIEW_TOPIC}'"><table width="100%" cellpadding="0" cellspacing="0">
      	<tr>
		<td><span class="topictitle">{topicrow.NEWEST_POST_IMG}{topicrow.TOPIC_ATTACHMENT_IMG}<span id="topic_{topicrow.TOPIC_ID}_type" name="topic_{topicrow.TOPIC_ID}_type" class="topictitle">{topicrow.TOPIC_TYPE}</span><a href="{topicrow.U_VIEW_TOPIC}" class="topictitle" title="{topicrow.TOPIC_CONTENT}">{topicrow.TOPIC_TITLE}</a></span> {topicrow.RATING}<span class="gensmall"><br />{L_POSTED}: {topicrow.FIRST_POST_TIME}<br />{topicrow.GOTO_PAGE}</span></td>
       		<td width="50" valign="middle">{topicrow.AMAZON_LINK}</td>
      	</tr>
	</table></td>
	<td class="row2" align="center" valign="middle"><span class="name">{topicrow.TOPIC_AUTHOR}</span></td>
	<td class="row1" align="center" valign="middle"><span class="postdetails">{topicrow.REPLIES}</span></td>
	<td class="row2" align="center" valign="middle"><span class="postdetails">{topicrow.VIEWS}</span></td>
	<td class="row1" align="center" valign="middle" nowrap="nowrap"><span class="postdetails">{topicrow.LAST_POST_TIME}<br />{topicrow.LAST_POST_AUTHOR} {topicrow.LAST_POST_IMG}</span></td>
	{topicrow.ANSWERED}
</tr>
<!-- END topicrow -->
<!-- BEGIN switch_no_topics -->
<tr> 
	<td class="row1" colspan="8" height="30" align="center" valign="middle"><span class="gen">{L_NO_TOPICS}</span></td>
</tr>
<!-- END switch_no_topics -->
<form method="post" action="{S_POST_DAYS_ACTION}">
<tr> 
	<td class="catBottom" align="center" valign="middle" colspan="8"><span class="genmed">{L_DISPLAY_TOPICS}:&nbsp;{S_SELECT_TOPIC_DAYS}&nbsp; 
	<input type="submit" class="liteoption" value="{L_GO}" name="submit" /></span></td>
</tr>
</table>

<table width="100%" cellspacing="2" align="center" cellpadding="2">
<tr> 
	<td valign="top" width="50"><a href="{U_POST_NEW_TOPIC}"><img src="{POST_IMG}" alt="{L_POST_NEW_TOPIC}" title="{L_POST_NEW_TOPIC}" /></a></td>
	<td valign="top" class="nav" width="100%">&nbsp;&nbsp;&nbsp;<a href="{U_INDEX}" class="nav">{L_INDEX}</a>
    	<!-- BEGIN navrow -->
    	<br />&nbsp;&nbsp;&nbsp;-> <a href="{navrow.U_SUBINDEX}" class="nav">{navrow.L_SUBINDEX}</a>
    	<!-- END navrow -->
 	<br />&nbsp;&nbsp;&nbsp;-> <a class="nav" href="{U_VIEW_FORUM}">{FORUM_NAVNAME}</a></span></td>
	<td align="right" valign="top" class="gensmall" nowrap="nowrap"><b>
	<!-- BEGIN switch_user_logged_in -->
	<a href="{U_MARK_READ}" class="gensmall" title="{L_MARK_TOPICS_READ}">{L_MARK_TOPICS_READ}</a> ::
	<!-- END switch_user_logged_in -->
 	{S_WATCH_FORUM}</b></td>
	<td align="right" valign="top" width="1"><a href="JavaScript:window.location.reload()"><img src="templates/{T_NAV_STYLE}/icon_refresh.gif" alt="{L_REFRESH_PAGE}" title="{L_REFRESH_PAGE}" /></a></td>
</tr>
<tr>
	<td valign="top" colspan="2" nowrap="nowrap"><span class="gensmall"><b>{PAGE_NUMBER}</b> &nbsp;[ {FORUM_TOPICS} {L_TOPICS} ]</span></td>
 	<td align="right" colspan="2" nowrap="nowrap"><b class="gensmall">{PAGINATION}</span></b></td>
</tr>
</form></table>
<br />
<table width="100%" cellspacing="0" align="center" cellpadding="0">
<tr>
	<td valign="top"><table cellspacing="3" cellpadding="0">
	<tr>
		<td align="center"><img src="{FOLDER_NEW_IMG}" alt="{L_NEW_POSTS}" title="{L_NEW_POSTS}" /></td>
		<td class="gensmall">{L_NEW_POSTS}</td>
		<td>&nbsp;&nbsp;</td>
		<td align="center"><img src="{FOLDER_IMG}" alt="{L_NO_NEW_POSTS}" title="{L_NO_NEW_POSTS}" /></td>
		<td class="gensmall">{L_NO_NEW_POSTS}</td>
		<td>&nbsp;&nbsp;</td>
		<td align="center"><img src="{FOLDER_GLOBAL_ANNOUNCE_IMG}" alt="{L_GLOBAL_ANNOUNCEMENT}" title="{L_GLOBAL_ANNOUNCEMENT}" /></td>
		<td class="gensmall">{L_GLOBAL_ANNOUNCEMENT}</td>
	</tr>
	<tr> 
		<td align="center"><img src="{FOLDER_HOT_NEW_IMG}" alt="{L_NEW_POSTS_HOT}" title="{L_NEW_POSTS_HOT}" /></td>
		<td class="gensmall">{L_NEW_POSTS_HOT}</td>
		<td>&nbsp;&nbsp;</td>
		<td align="center"><img src="{FOLDER_HOT_IMG}" alt="{L_NO_NEW_POSTS_HOT}" title="{L_NO_NEW_POSTS_HOT}" /></td>
		<td class="gensmall">{L_NO_NEW_POSTS_HOT}</td>
		<td>&nbsp;&nbsp;</td>
		<td align="center"><img src="{FOLDER_ANNOUNCE_IMG}" alt="{L_ANNOUNCEMENT}" title="{L_ANNOUNCEMENT}" /></td>
		<td class="gensmall">{L_ANNOUNCEMENT}</td>
	</tr>
	<tr>
		<td align="center"><img src="{FOLDER_LOCKED_NEW_IMG}" alt="{L_NEW_POSTS_LOCKED}" title="{L_NEW_POSTS_LOCKED}" /></td>
		<td class="gensmall">{L_NEW_POSTS_LOCKED}</td>
		<td>&nbsp;&nbsp;</td>
		<td align="center"><img src="{FOLDER_LOCKED_IMG}" alt="{L_NO_NEW_POSTS_LOCKED}" title="{L_NO_NEW_POSTS_LOCKED}" /></td>
		<td class="gensmall">{L_NO_NEW_POSTS_LOCKED}</td>
		<td>&nbsp;&nbsp;</td>
		<td align="center"><img src="{FOLDER_STICKY_IMG}" alt="{L_STICKY}" title="{L_STICKY}" /></td>
		<td class="gensmall">{L_STICKY}</td>
	</tr>
	<tr>
		<td align="center"><img src="{FOLDER_MOVED_IMG}" alt="{L_MOVED}" title="{L_MOVED}" /></td>
		<td class="gensmall">{L_MOVED}</td>
		<td>&nbsp;&nbsp;</td>
		<td align="center"><img src="{FOLDER_LINK_IMG}" alt="{L_LINKED}" title="{L_LINKED}" /></td>
		<td class="gensmall">{L_LINKED}</td>
		<td>&nbsp;&nbsp;</td>
		<td align="center"><img src="{FOLDER_POLL_IMG}" alt="{L_POLL}" title="{L_POLL}" /></td>
		<td class="gensmall">{L_POLL}</td>
	</tr>
    	<!-- BEGIN switch_answered_images -->
	<tr>
		<td align="center"><img src="{ANSWERED_TOPIC_IMG}" alt="{L_ANSWERED_TOPIC}" title="{L_ANSWERED_TOPIC}" /></td>
		<td class="gensmall">{L_ANSWERED_TOPIC}</td>
		<td>&nbsp;&nbsp;</td>
		<td align="center"><img src="{UNANSWERED_TOPIC_IMG}" alt="{L_UNANSWERED_TOPIC}" title="{L_UNANSWERED_TOPIC}" /></td>
		<td class="gensmall">{L_UNANSWERED_TOPIC}</td>
		<td>&nbsp;&nbsp;</td>
		<td align="center">&nbsp;</td>
		<td class="gensmall">&nbsp;</td>
	</tr>
    	<!-- END switch_answered_images -->
	</table></td>
	<td valign="top" align="right"><span class="gensmall">{S_AUTH_LIST}<br /><br />{JUMPBOX}</span></td>
</tr>
</table>