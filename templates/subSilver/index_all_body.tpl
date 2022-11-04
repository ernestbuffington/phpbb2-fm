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
  <tr> 
	<td align="left" valign="middle" class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> <a href="{U_ALL_FORUMS}" class="nav">{L_ALL_FORUMS}</a></td>
	<td align="right" valign="bottom" nowrap="nowrap"><b class="gensmall">{PAGINATION}</b></td>
  </tr>
</table>

<table cellpadding="4" cellspacing="1" width="100%" class="forumline">
  <tr> 
	<th colspan="3" align="center" class="thCornerL" nowrap="nowrap">&nbsp;{L_TOPICS}&nbsp;</th>
	<th width="100" align="center" class="thTop" nowrap="nowrap">&nbsp;{L_AUTHOR}&nbsp;</th>
	<th width="50" align="center" class="thTop" nowrap="nowrap">&nbsp;{L_REPLIES}&nbsp;</th>
	<th width="50" align="center" class="thTop" nowrap="nowrap">&nbsp;{L_VIEWS}&nbsp;</th>
	<th align="center" class="thCornerR" nowrap="nowrap">&nbsp;{L_LASTPOST}&nbsp;</th>
  </tr>
<!-- BEGIN topicrow -->
<!-- BEGIN topictype -->
  <tr>
	<td colspan="7" align="left" class="catLeft"><span class="cattitle">{topicrow.topictype.TITLE}</span></td>
  </tr>
<!-- END topictype -->
  <tr> 
<form method="post" action="{S_POST_DAYS_ACTION}">
	<td class="row1" align="center" valign="middle" width="5%"><a href="{topicrow.U_VIEW_TOPIC}"><img src="{topicrow.TOPIC_FOLDER_IMG}" alt="{topicrow.L_TOPIC_FOLDER_ALT}" title="{topicrow.L_TOPIC_FOLDER_ALT}" /></a></td>
	<td class="row1" align="center" valign="middle" width="19" nowrap>{topicrow.ICON}</td> 
	<td class="row1" width="100%" onMouseOver="this.style.backgroundColor='{T_TR_COLOR2}'; this.style.cursor='hand';" onMouseOut=this.style.backgroundColor="{T_TR_COLOR1}" onclick="window.location.href='{topicrow.U_VIEW_TOPIC}'"><span class="topictitle">{topicrow.NEWEST_POST_IMG}{topicrow.TOPIC_ATTACHMENT_IMG}{topicrow.TOPIC_TYPE}<a href="{topicrow.U_VIEW_TOPIC}" class="topictitle" title="{topicrow.TOPIC_CONTENT}">{topicrow.TOPIC_TITLE}</a> {topicrow.RATING}</span><span class="gensmall"><br />{L_POSTED}: {topicrow.FIRST_POST_TIME}<br />{topicrow.GOTO_PAGE}</span></td>
	<td class="row2" align="center" valign="middle"><span class="name">{topicrow.TOPIC_AUTHOR}</span></td>
	<td class="row1" align="center" valign="middle"><span class="postdetails">{topicrow.REPLIES}</span></td>
	<td class="row2" align="center" valign="middle"><span class="postdetails">{topicrow.VIEWS}</span></td>
	<td class="row1" align="center" valign="middle" nowrap="nowrap"><span class="postdetails">{topicrow.LAST_POST_TIME}<br />{topicrow.LAST_POST_AUTHOR} {topicrow.LAST_POST_IMG}</span></td>
  </tr>
<!-- END topicrow -->
<!-- BEGIN switch_no_topics -->
  <tr> 
	<td class="row1" colspan="7" height="30" align="center" valign="middle"><span class="gen">{L_NO_TOPICS}</span></td>
  </tr>
<!-- END switch_no_topics -->
  <tr> 
	<td class="catBottom" align="center" valign="middle" colspan="8" height="28"><span class="genmed">{L_DISPLAY_TOPICS}:&nbsp;{S_SELECT_TOPIC_DAYS}&nbsp; 
		<input type="submit" class="liteoption" value="{L_GO}" name="submit" />
	</span></td>
</form>
  </tr>
</table>

<table width="100%" cellspacing="2" align="center" cellpadding="2">
  <tr> 
	<td align="left" valign="middle" width="100%"><span class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></span></td>
	<td align="right" valign="middle" nowrap="nowrap"><span class="nav">{PAGINATION}</span></td>
  </tr>
  <tr>
	<td align="left" colspan="2"><span class="nav">{PAGE_NUMBER}</span></td>
  </tr>
</table>

<table width="100%" cellspacing="0" align="center" cellpadding="0">
	<tr>
		<td align="left" valign="top"><table cellspacing="3" cellpadding="0">
			<tr>
				<td align="left"><img src="{FOLDER_NEW_IMG}" alt="{L_NEW_POSTS}"></td>
				<td class="gensmall">{L_NEW_POSTS}</td>
				<td>&nbsp;&nbsp;</td>
				<td align="center"><img src="{FOLDER_IMG}" alt="{L_NO_NEW_POSTS}"></td>
				<td class="gensmall">{L_NO_NEW_POSTS}</td>
				<td>&nbsp;&nbsp;</td>
				<td align="center"><img src="{FOLDER_GLOBAL_ANNOUNCE_IMG}" alt="{L_GLOBAL_ANNOUNCEMENT}"></td>
				<td class="gensmall">{L_GLOBAL_ANNOUNCEMENT}</td>
			</tr>
			<tr> 
				<td align="center"><img src="{FOLDER_HOT_NEW_IMG}" alt="{L_NEW_POSTS_HOT}"></td>
				<td class="gensmall">{L_NEW_POSTS_HOT}</td>
				<td>&nbsp;&nbsp;</td>
				<td align="center"><img src="{FOLDER_HOT_IMG}" alt="{L_NO_NEW_POSTS_HOT}"></td>
				<td class="gensmall">{L_NO_NEW_POSTS_HOT}</td>
				<td>&nbsp;&nbsp;</td>
				<td align="center"><img src="{FOLDER_ANNOUNCE_IMG}" alt="{L_ANNOUNCEMENT}"></td>
				<td class="gensmall">{L_ANNOUNCEMENT}</td>
			</tr>
			<tr>
				<td align="center"><img src="{FOLDER_LOCKED_NEW_IMG}" alt="{L_NEW_POSTS_LOCKED}"></td>
				<td class="gensmall">{L_NEW_POSTS_LOCKED}</td>
				<td>&nbsp;&nbsp;</td>
				<td align="center"><img src="{FOLDER_LOCKED_IMG}" alt="{L_NO_NEW_POSTS_LOCKED}"></td>
				<td class="gensmall">{L_NO_NEW_POSTS_LOCKED}</td>
				<td>&nbsp;&nbsp;</td>
				<td align="center"><img src="{FOLDER_STICKY_IMG}" alt="{L_STICKY}"></td>
				<td class="gensmall">{L_STICKY}</td>
			</tr>
			<tr>
				<td align="center"><img src="{FOLDER_MOVED_IMG}" alt="{L_MOVED}"></td>
				<td class="gensmall">{L_MOVED}</td>
				<td>&nbsp;&nbsp;</td>
				<td align="center"><img src="{FOLDER_LINK_IMG}" alt="{L_LINK}"></td>
				<td class="gensmall">{L_LINK}</td>
				<td>&nbsp;&nbsp;</td>
				<td align="center"><img src="{FOLDER_POLL_IMG}" alt="{L_POLL}"></td>
				<td class="gensmall">{L_POLL}</td>
			</tr>
		</table></td>
		<td align="right">{JUMPBOX}</td>
	</tr>
</table>

