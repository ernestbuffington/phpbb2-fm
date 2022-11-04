
<table width="100%" cellspacing="2" cellpadding="2"><form action="{S_ACTION}" method="post">
<tr>
	<td class="nav" rowspan="2" valign="bottom"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></td>
	<td align="right" class="genmed">{L_SORT_BY_FIELD}:&nbsp;{SORT_BY_FIELD}&nbsp;&nbsp;{L_SORT_BY_ORDER}:&nbsp;{SORT_BY_ORDER}&nbsp;&nbsp;{L_FILTER_BY_FIELD}:&nbsp;{FILTER_BY_FIELD}&nbsp;&nbsp;<input class="post" type="text" name="filter" value="{FILTER_FIELD}" size="20" maxlength="50" /></td>
</tr>
<tr>
	<td align="right" class="genmed">{L_CLOSED_NO}:<input type="radio" name="closed" value="1" {CLOSED_NO}>&nbsp;&nbsp;{L_CLOSED_YES}:<input type="radio" name="closed" value="2" {CLOSED_YES}>&nbsp;&nbsp;{L_CLOSED_PERIOD}:<input type="radio" name="closed" value="3" {CLOSED_PERIOD}>&nbsp;&nbsp;{L_CLOSED_NONE}:<input type="radio" name="closed" value="4" {CLOSED_NONE}>&nbsp;&nbsp;<input type="submit" name="submit" class="mainoption" value="{L_GO}" /></td>
</tr>
</form></table>

<table width="100%" cellpadding="4" cellspacing="1" class="forumline">
<tr>
	<th class="thCornerL">{L_MEETING_SUBJECT}</th>
	<th class="thTop">{L_MEETING_TIME}</th>
	<th class="thTop">{L_MEETING_UNTIL}</th>
	<th class="thTop">{L_MEETING_LOCATION}</th>
	<th class="thTop">{L_MEETING_CLOSED}</th>
	<th class="thCornerR" colspan="3">{L_MEETING_USERS}</th>
</tr>
<!-- BEGIN meeting_overview_row -->
<tr>
	<td class="{meeting_overview_row.ROW_CLASS}"><span class="genmed">{meeting_overview_row.MEETING_SUBJECT}</span></td>
	<td class="{meeting_overview_row.ROW_CLASS}" align="center"><span class="genmed">{meeting_overview_row.MEETING_TIME}</span></td>
	<td class="{meeting_overview_row.ROW_CLASS}" align="center"><span class="genmed">{meeting_overview_row.MEETING_UNTIL}</span></td>
	<td class="{meeting_overview_row.ROW_CLASS}" align="center"><span class="genmed">{meeting_overview_row.MEETING_LOCATION}</span></td>
	<td class="{meeting_overview_row.ROW_CLASS}" align="center"><span class="genmed">{meeting_overview_row.MEETING_CLOSED}</span></td>
	<td class="{meeting_overview_row.ROW_CLASS}" align="center"><span class="genmed">{meeting_overview_row.MEETING_USERS}</span></td>
	<td class="{meeting_overview_row.ROW_CLASS}" align="center"><span class="genmed">{meeting_overview_row.MEETING_DETAIL}</span></td>
	<td class="{meeting_overview_row.ROW_CLASS}" align="center"><span class="genmed">{meeting_overview_row.MEETING_EDIT}&nbsp;{meeting_overview_row.MEETING_DELETE}</span></td>
</tr>
<!-- END meeting_overview_row -->
<!-- BEGIN no_meeting_row -->
<tr>
	<td class="row1" colspan="8" align="center" height="30"><span class="gen">{no_meeting_row.L_NO_MEETING}</span></td>
</tr>
<!-- END no_meeting_row -->
<tr>
	<td class="catBottom" colspan="8" align="center">
		<!-- BEGIN user_enter_on -->
		&nbsp;<a href="{user_enter_on.U_NEW_MEETING}" class="nav">{user_enter_on.L_NEW_MEETING}</a>&nbsp;<b>&laquo;&raquo;</b>
		<!-- END user_enter_on -->
		&nbsp;<a href="{U_MEETING_SIGNONS}" class="nav">{L_MEETING_SIGNONS}</a>&nbsp;
	</td>
</tr>
</table>

<table width="100%" cellspacing="2" align="center"> 
  <tr> 
	<td class="nav">{PAGINATION}</td>
	<td align="right" valign="middle" nowrap>{JUMPBOX}</td> 
  </tr> 
</table> 
<br />
<div align="center" class="copyright">Meeting 1.3.18 &copy; 2004, {COPYRIGHT_YEAR} <a href="http://www.oxpus.de/" class="copyright" target="_blank">OXPUS</a></div>

