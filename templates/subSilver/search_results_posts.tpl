 
<table width="100%" cellspacing="2" cellpadding="2" align="center">
  <tr> 
	<td align="left" valign="bottom"><span class="maintitle">{L_SEARCH_MATCHES}</span><br /></td>
  </tr>
</table>

<table width="100%" cellspacing="2" cellpadding="2" align="center">
  <tr> 
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> <a href="{U_SEARCH}" class="nav">{L_SEARCH}</a></td>
  </tr>
</table>

<table cellpadding="4" cellspacing="1" width="100%" class="forumline" align="center">
  <tr> 
	<th width="150" class="thCornerL" nowrap="nowrap">{L_AUTHOR}</th>
	<th width="100%" class="thCornerR" nowrap="nowrap">{L_MESSAGE}</th>
  </tr>
  <!-- BEGIN searchresults -->
  <tr> 
	<td class="catHead" colspan="2"><span class="topictitle"><img src="{searchresults.FOLDER_IMG}" align="absmiddle" />&nbsp; {searchresults.L_TOPIC}<a href="{searchresults.U_TOPIC}" class="topictitle">{searchresults.TOPIC_TITLE}</a></span></td>
  </tr>
  <tr> 
	<td width="150" valign="top" class="row1" rowspan="2"><span class="name"><b>{searchresults.POSTER_NAME}</b></span><br />
	  <br />
	  <span class="postdetails">{L_REPLIES}: <b>{searchresults.TOPIC_REPLIES}</b><br />
	  {L_VIEWS}: <b>{searchresults.TOPIC_VIEWS}</b></span><br />
	</td>
	<td width="100%" valign="top" class="row1"><img src="{searchresults.MINI_POST_IMG}" width="12" height="9" alt="{searchresults.L_MINI_POST_ALT}" title="{searchresults.L_MINI_POST_ALT}" /><span class="gen"><b>&nbsp;<a href="{searchresults.U_POST}">{searchresults.POST_SUBJECT}</a></b></span><br /><span class="postdetails">{searchresults.POST_DATE}&nbsp;&nbsp;&nbsp;{L_FORUM}:&nbsp;<b><a href="{searchresults.U_FORUM}" class="postdetails">{searchresults.FORUM_NAME}</a></b></span></td>
  </tr>
  <tr>
	<td valign="top" class="row1"><span class="postbody">{searchresults.MESSAGE}</span></td>
  </tr>
  <!-- END searchresults -->
  <tr> 
	<td class="catBottom" colspan="2">&nbsp;</td>
  </tr>
</table>

<table width="100%" cellspacing="2" align="center" cellpadding="2">
  <tr> 
	<td valign="top" class="nav">{PAGE_NUMBER}</td>
	<td align="right" valign="top" nowrap="nowrap" class="nav">{PAGINATION}</td>
  </tr>
</table>

<table width="100%" cellspacing="2" align="center">
  <tr> 
	<td valign="top" align="right">{JUMPBOX}</td>
  </tr>
</table>
