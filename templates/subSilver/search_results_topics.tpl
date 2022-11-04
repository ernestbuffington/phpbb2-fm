<script language="Javascript" type="text/javascript">
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
	<td valign="bottom"><span class="maintitle">{L_SEARCH_MATCHES}</span><br /></td>
  </tr>
</table>

<table width="100%" cellspacing="2" cellpadding="2" align="center">
  <tr> 
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> <a href="{U_SEARCH}" class="nav">{L_SEARCH}</a></td>
  </tr>
</table>

<table width="100%" cellpadding="4" cellspacing="1" class="forumline" align="center">
  <tr> 
	<th class="thCornerL" colspan="2" nowrap="nowrap">&nbsp;{L_TOPICS}&nbsp;</th>
	<th class="thTop" nowrap="nowrap">&nbsp;{L_FORUM}&nbsp;</th>
	<th class="thTop" width="100" nowrap="nowrap">&nbsp;{L_AUTHOR}&nbsp;</th>
	<th class="thTop" width="50" nowrap="nowrap">&nbsp;{L_REPLIES}&nbsp;</th>
	<th class="thTop" width="50" nowrap="nowrap">&nbsp;{L_VIEWS}&nbsp;</th>
	<th class="thCornerR" width="150" nowrap="nowrap">&nbsp;{L_LASTPOST}&nbsp;</th>
  </tr>
  <!-- BEGIN searchresults -->
  <tr> 
	<td width="5%" class="row1" align="center" valign="middle"><img src="{searchresults.TOPIC_FOLDER_IMG}" alt="{searchresults.L_TOPIC_FOLDER_ALT}" title="{searchresults.L_TOPIC_FOLDER_ALT}" /></td>
	<td width="60%" class="row1" onMouseOver=this.style.backgroundColor="{T_TR_COLOR2}" onMouseOut=this.style.backgroundColor="{T_TR_COLOR1}" onclick="window.location.href='{searchresults.U_VIEW_TOPIC}'"><span class="topictitle">{searchresults.NEWEST_POST_IMG}{searchresults.TOPIC_TYPE}<a href="{searchresults.U_VIEW_TOPIC}" class="topictitle" title="{searchresults.TOPIC_CONTENT}">{searchresults.TOPIC_TITLE}</a></span><br /><span class="gensmall">{searchresults.GOTO_PAGE}</span></td>
	<td width="35%" class="row2"><span class="forumlink"><a href="{searchresults.U_VIEW_FORUM}" class="forumlink">{searchresults.FORUM_NAME}</a></span></td>
	<td class="row1" align="center" valign="middle"><span class="name">{searchresults.TOPIC_AUTHOR}</span></td>
	<td class="row2" align="center" valign="middle"><span class="postdetails">{searchresults.REPLIES}</span></td>
	<td class="row1" align="center" valign="middle"><span class="postdetails">{searchresults.VIEWS}</span></td>
	<td class="row2" align="center" valign="middle" nowrap="nowrap"><span class="postdetails">{searchresults.LAST_POST_TIME}<br />{searchresults.LAST_POST_AUTHOR} {searchresults.LAST_POST_IMG}</span></td>
  </tr>
  <!-- END searchresults -->
  <tr> 
	<td class="catBottom" colspan="7" height="28" valign="middle">&nbsp; </td>
  </tr>
</table>

<table width="100%" cellspacing="2" align="center" cellpadding="2">
  <tr> 
	<td class="nav">{PAGE_NUMBER}</span></td>
	<td align="right" nowrap="nowrap" class="nav">{PAGINATION}</span></td>
  </tr>
</table>
<br />

<table width="100%" cellspacing="2" align="center">
  <tr> 
	<td valign="top" align="right">{JUMPBOX}</td>
  </tr>
</table>
