<script language="Javascript" type="text/javascript">
<!--
function select_switch(status)
{
	for (i = 0; i < document.unwatch_topics.length; i++)
	{
		document.unwatch_topics.elements[i].checked = status;
	}
}

function select_switch2(status)
{
	for (i = 0; i < document.unwatch_forums.length; i++)
	{
		document.unwatch_forums.elements[i].checked = status;
	}
}

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
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></td>
</tr>
</table>

{CPL_MENU_OUTPUT}

<table align="center" width="100%" cellpadding="4" cellspacing="1" class="forumline"><form name="unwatch_topics" id="unwatch_topics" method="post" action="{S_FORM_ACTION}"><input type="hidden" name="mode" value="editprofile" />
<tr> 
	<th class="thCornerL" nowrap="nowrap">&nbsp;{L_TOPICS}&nbsp;</th>
	<th width="50" class="thTop" nowrap="nowrap">&nbsp;{L_REPLIES}&nbsp;</th>
	<th width="50" class="thTop" nowrap="nowrap">&nbsp;{L_VIEWS}&nbsp;</th>
	<th width="150" class="thTop" nowrap="nowrap">&nbsp;{L_LAST_POST}&nbsp;</th>
	<th width="1%" class="thCornerR" nowrap="nowrap">&nbsp;{L_MARK}&nbsp;</th>
</tr>	
<!-- BEGIN switch_watched_topics_block -->
<tr> 
	<td colspan="5" align="center" class="row3"><table width="100%" cellspacing="1">
	<tr>
	<td class="nav" nowrap="nowrap">{switch_watched_topics_block.PAGE_NUMBER}</td>
		<td class="gensmall" nowrap="nowrap">&nbsp;[ {switch_watched_topics_block.TOTAL_TOPICS} ]&nbsp;</td>
  	<td align="right" class="nav" width="100%" nowrap="nowrap">{switch_watched_topics_block.PAGINATION}</td>
	</tr>
	</table></td>
</tr>
<!-- END switch_watched_topics_block -->
<!-- BEGIN topic_watch_row -->
<tr>
	<td class="{topic_watch_row.ROW_CLASS}"><a href="{topic_watch_row.U_WATCHED_TOPIC}" class="gen">{topic_watch_row.S_WATCHED_TOPIC}</a><br /><span class="gensmall"><b>{L_FORUM}:</b> <a href="{topic_watch_row.U_FORUM_LINK}" class="gensmall">{topic_watch_row.S_FORUM_TITLE}</a><br />{L_BY} {topic_watch_row.TOPIC_POSTER} {L_ON} {topic_watch_row.S_WATCHED_TOPIC_START}<br />{topic_watch_row.GOTO_PAGE}</span></td>
	<td class="{topic_watch_row.ROW_CLASS}" align="center"><span class="gensmall">{topic_watch_row.S_WATCHED_TOPIC_REPLIES}</span></td>
	<td class="{topic_watch_row.ROW_CLASS}" align="center"><span class="gensmall">{topic_watch_row.S_WATCHED_TOPIC_VIEWS}</span></td>
	<td class="{topic_watch_row.ROW_CLASS}" align="center"><span class="gensmall">{topic_watch_row.S_WATCHED_TOPIC_LAST}<br />{topic_watch_row.LAST_POSTER}</span></td>
	<td class="{topic_watch_row.ROW_CLASS}" align="center"><input type="checkbox" name="unwatch_list[]" value="{topic_watch_row.S_WATCHED_TOPIC_ID}" /></td>
</tr>
<!-- END topic_watch_row -->
<!-- BEGIN switch_no_watched_topics -->
<tr>
	<td colspan="5" class="row1" align="center"><b class="gensmall">{switch_no_watched_topics.L_NO_WATCHED_TOPICS}</b></td>
</tr>   
<tr> 
	<td class="catBottom" align="center" colspan="7">&nbsp;</td>
</tr>
<!-- END switch_no_watched_topics -->
<!-- BEGIN switch_watched_topics_block -->
<tr>
	<td colspan="5" class="catBottom" align="right"><input type="submit" name="unwatch_topics" class="liteoption" value="{L_STOP_WATCH}" /></td>
</tr>    
</table>
<table width="100%" cellspacing="2" align="center" cellpadding="2">
<tr> 
  	<td align="right" nowrap="nowrap"><b class="gensmall"><a href="javascript:select_switch(true);" class="gensmall">{L_MARK_ALL}</a> :: <a href="javascript:select_switch(false);" class="gensmall">{L_UNMARK_ALL}</a></b></td>
</tr>
<!-- END switch_watched_topics_block -->
</form></table>
<br />

<table align="center" width="100%" cellpadding="4" cellspacing="1" class="forumline"><form name="unwatch_forums" id="unwatch_forums" method="post" action="{S_FORM_ACTION}"><input type="hidden" name="mode" value="editprofile" />
<tr> 
	<th class="thCornerL" nowrap="nowrap">&nbsp;{L_FORUM}&nbsp;</th>
	<th width="100" class="thTop" nowrap="nowrap">&nbsp;{L_POSTS2}&nbsp;/&nbsp;{L_TOPICS}&nbsp;</th>
	<th width="50" class="thTop" nowrap="nowrap">&nbsp;{L_VIEWS}&nbsp;</th>
	<th width="150" class="thTop" nowrap="nowrap">&nbsp;{L_LAST_POST}&nbsp;</th>
	<th width="1" class="thCornerR" nowrap="nowrap">&nbsp;{L_MARK}&nbsp;</th>
</tr>	
<!-- BEGIN switch_watched_forums_block -->
<tr> 
	<td colspan="5" align="center" class="row3"><table width="100%" cellspacing="1">
	<tr>
	<td class="nav" nowrap="nowrap">{switch_watched_forums_block.PAGE_NUMBER}</td>
		<td class="gensmall" nowrap="nowrap">&nbsp;[ {switch_watched_forums_block.TOTAL_FORUMS} ]&nbsp;</td>
  	<td align="right" class="nav" width="100%" nowrap="nowrap">{switch_watched_forums_block.PAGINATION}</td>
	</tr>
	</table></td>
</tr>
<!-- END switch_watched_forums_block -->
<!-- BEGIN forum_watch_row -->
<tr>
	<td class="{forum_watch_row.ROW_CLASS}" onMouseOver="this.style.backgroundColor='{T_TR_COLOR2}'; this.style.cursor='hand';" onMouseOut=this.style.backgroundColor="{T_TR_COLOR1}" onclick="window.location.href='{forum_watch_row.U_FORUM_LINK}'"><a href="{forum_watch_row.U_FORUM_LINK}" class="gen">{forum_watch_row.S_FORUM_TITLE}</td>
	<td class="{forum_watch_row.ROW_CLASS}" align="center"><span class="gensmall">{forum_watch_row.S_WATCHED_FORUM_POSTS} {L_WITHIN} {forum_watch_row.S_WATCHED_FORUM_TOPICS}</span></td>
	<td class="{forum_watch_row.ROW_CLASS}" align="center"><span class="gensmall">{forum_watch_row.S_WATCHED_FORUM_VIEWS}</span></td>
	<td class="{forum_watch_row.ROW_CLASS}" align="center"><span class="gensmall">{forum_watch_row.S_WATCHED_FORUM_LAST}</span></td>
	<td class="{forum_watch_row.ROW_CLASS}" align="center"><input type="checkbox" name="unwatch_list[]" value="{forum_watch_row.S_WATCHED_FORUM_ID}" /></td>
</tr>
<!-- END forum_watch_row -->
<!-- BEGIN switch_no_watched_forums -->
<tr>
	<td colspan="5" class="row1" align="center"><b class="gensmall">{switch_no_watched_forums.L_NO_WATCHED_FORUMS}</b></td>
</tr>   
<tr> 
	<td class="catBottom" align="center" colspan="5">&nbsp;</td>
</tr>
<!-- END switch_no_watched_forums -->
<!-- BEGIN switch_watched_forums_block -->
<tr>
	<td colspan="5" class="catBottom" align="right"><input type="submit" name="unwatch_forums" class="liteoption" value="{L_STOP_WATCH_FORUMS}" /></td>
</tr>    
</table>
<table width="100%" cellspacing="2" align="center" cellpadding="2">
<tr> 
  	<td align="right" nowrap="nowrap"><b class="gensmall"><a href="javascript:select_switch2(true);" class="gensmall">{L_MARK_ALL}</a> :: <a href="javascript:select_switch2(false);" class="gensmall">{L_UNMARK_ALL}</a></b></td>
</tr>
<!-- END switch_watched_topics_block -->
</form></table>
	</td>
</tr>
</table>
<br />
<table width="100%" cellspacing="2"  align="center"> 
<tr> 
	<td align="right">{JUMPBOX}</td> 
</tr> 
</table> 
<br />
