<table cellpadding="4" cellspacing="1" width="100%" class="forumline"><form method="post" action="profile.{PHPEX}?mode=viewprofile&u={USER_ID}" name="sort_and_order">
  <tr>
	<td class="catHead" colspan="4" align="center"><b class="gen">{L_USER_VIEWED}</b></td>
  </tr>
  <tr>
	<th class="thCornerL">{L_TOPICS}</th>
	<th class="thTop">{L_FORUM}</th>
	<th class="thTop">{L_LAST_VIEWED}</th>
	<th class="thCornerR">{L_VIEWS}</th>
</tr>
<!-- BEGIN viewedrow -->
  <tr>
	<td class="row1"><span class="genmed"><a href="viewtopic.{PHPEX}?{TOPIC_URL_CODE}={viewedrow.TOPIC_ID}">{viewedrow.TOPIC_TITLE}</a></span></td>
	<td align="center" class="row2"><span class="genmed"><a href="viewforum.{PHPEX}?{FORUM_URL_CODE}={viewedrow.FORUM_ID}">{viewedrow.FORUM_NAME}</a></span></td>
	<td align="center" class="row1"><span class="genmed">{viewedrow.LAST_VIEWED}</span></td>
	<td align="center" class="row2"><span class="genmed">{viewedrow.NUM_VIEWS}</span></td>
  </tr>
<!-- END viewedrow -->
  <tr>
	<td class="catHead" colspan="4" align="center"><span class="genmed">{L_SELECT_SORT_METHOD}:&nbsp;{S_MODE_SELECT}&nbsp;&nbsp;{L_ORDER}:&nbsp;{S_ORDER_SELECT}&nbsp;&nbsp;<input type="submit" name="submit" value="{L_SUBMIT}" class="liteoption"></span></td>
  </tr>
</table>
<table width="100%" cellspacing="2" cellpadding="2">
  <tr> 
	<td><span class="nav">{PAGE_NUMBER}</span></td>
	<td align="right"><span class="nav">{PAGINATION}&nbsp;</span></td>
  </tr>
</form></table>
