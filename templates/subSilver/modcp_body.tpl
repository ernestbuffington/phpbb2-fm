<script language="Javascript" type="text/javascript">
function select_switch(status)
{
	for (i = 0; i < document.modcp.length; i++)
	{
		document.modcp.elements[i].checked = status;
	}
}
</script>

<table width="100%" cellspacing="2" cellpadding="2" align="center">
  <tr> 
	<td class="nav" valign="bottom"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> <a href="{U_VIEW_FORUM}" class="nav">{FORUM_NAME}</a></td>
 	<td align="right" valign="top" class="nav">{PAGE_NUMBER}<br />{PAGINATION}</td>
 </tr>
</table>

<table width="100%" cellpadding="4" cellspacing="1" class="forumline"><form method="post" name="modcp" action="{S_MODCP_ACTION}">
<tr> 
	<td class="catHead" colspan="7" align="center"><span class="cattitle">{L_MOD_CP}</span></td>
</tr>
<tr> 
	  <td class="row2" colspan="7" align="center"><span class="gensmall">{L_MOD_CP_EXPLAIN}</span></td>
</tr>
<tr> 
	  <th class="thCornerL" nowrap="nowrap" colspan="2">&nbsp;{L_TOPICS}&nbsp;</th>
	  <th class="thTop" width="100" nowrap="nowrap">&nbsp;{L_AUTHOR}&nbsp;</th>
	  <th class="thTop" width="50" nowrap="nowrap">&nbsp;{L_REPLIES}&nbsp;</th>
	  <th class="thTop" width="150" nowrap="nowrap">&nbsp;{L_LASTPOST}&nbsp;</th>
	  <th width="50" class="thTop" nowrap="nowrap">&nbsp;{L_PRIORITY}&nbsp;</th>
	  <th width="50" class="thCornerR" nowrap="nowrap">&nbsp;{L_SELECT}&nbsp;</th>
</tr>
<!-- BEGIN topicrow -->
<tr> 
	  <td width="4%" class="row1" align="center" valign="middle"><img src="{topicrow.TOPIC_FOLDER_IMG}" alt="{topicrow.L_TOPIC_FOLDER_ALT}" title="{topicrow.L_TOPIC_FOLDER_ALT}" /></td>
	  <td class="row1">&nbsp;<span class="topictitle">{topicrow.TOPIC_ATTACHMENT_IMG}{topicrow.TOPIC_TYPE}<a href="{topicrow.U_VIEW_TOPIC}" title="{topicrow.L_TOPIC_STARTED}: {topicrow.FIRST_POST_TIME}" class="topictitle">{topicrow.TOPIC_TITLE}</a></span></td>
	  <td class="row1" align="center"><span class="postdetails">{topicrow.USERNAME}</span></td>
	  <td class="row2" align="center" valign="middle"><span class="postdetails">{topicrow.REPLIES}</span></td>
	  <td class="row1" align="center" valign="middle"><span class="postdetails">{topicrow.LAST_POST_TIME}</span></td>
	  <td class="row2" align="center" valign="middle"><input type="text" class="post" name="topic_cement:{topicrow.TOPIC_ID}" value="{topicrow.TOPIC_PRIORITY}" maxlength="5" size="5" /></td>
	  <td class="row1" align="center" valign="middle"><input type="checkbox" name="topic_id_list[]" value="{topicrow.TOPIC_ID}" /></td>
</tr>
<!-- END topicrow -->
<tr align="right"> 
	<td class="catBottom" colspan="7"> {S_HIDDEN_FIELDS} 
	<!-- BEGIN switch_quick_title -->
	{SELECT_TITLE}
	<input type="submit" name="quick_title_edit" class="liteoption" value="{switch_quick_title.L_EDIT_TITLE}" />
	&nbsp;
	<!-- END switch_quick_title -->
	<input type="submit" name="delete" class="liteoption" value="{L_DELETE}" />
	&nbsp; 
	<input type="submit" name="move" class="liteoption" value="{L_MOVE}" />
	&nbsp; 
	<input type="submit" name="lock" class="liteoption" value="{L_LOCK}" />
	&nbsp; 
	<input type="submit" name="unlock" class="liteoption" value="{L_UNLOCK}" />
	<!-- BEGIN switch_bin -->
	&nbsp; 
	<input type="submit" class="liteoption" name="recycle" value="{switch_bin.L_RECYCLE}" />
	<!-- END switch_bin -->
	&nbsp; 
	<input type="submit" name="link" class="liteoption" value="{L_LINK}" />
	&nbsp; 
	<input type="submit" name="mergetopic" class="liteoption" value="{L_MERGE}" />
	&nbsp; 
	<input type="submit" name="cement" class="liteoption" value="{L_PRIORITIZE}" />
	</td>
</tr>
</form></table>
<table width="100%" cellspacing="2" align="center" cellpadding="2">
  <tr> 
	<td valign="top" class="nav">{PAGE_NUMBER}<br />{PAGINATION}</td>
	<td align="right" nowrap="nowrap"><b class="gensmall"><a href="javascript:select_switch(true);" class="gensmall">{L_MARK_ALL}</a> :: <a href="javascript:select_switch(false);" class="gensmall">{L_UNMARK_ALL}</a></b></td>
  </tr>
  <tr> 
	<td colspan="2" align="right"><br />{JUMPBOX}</td>
  </tr>
</table>
