{MEETING_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_MEETING}</h1>

<p>{L_MEETING_EXPLAIN}</p>

<table width="100%" cellpadding="2" cellspacing="2" align="center"><form action="{S_ACTION}" method="post">
<tr>
	<td align="right" class="genmed">{L_SORT_BY_FIELD}:&nbsp;{SORT_BY_FIELD}&nbsp;&nbsp;{L_SORT_BY_ORDER}:&nbsp;{SORT_BY_ORDER}&nbsp;&nbsp;{L_FILTER_BY_FIELD}:&nbsp;{FILTER_BY_FIELD}&nbsp;&nbsp;<input class="post" type="text" name="filter" value="{FILTER_FIELD}" size="20" maxlength="50" /></td>
</tr>
<tr>
	<td align="right" class="genmed">{L_CLOSED_NO}:<input type="radio" name="closed" value="1" {CLOSED_NO}>&nbsp;&nbsp;{L_CLOSED_YES}:<input type="radio" name="closed" value="2" {CLOSED_YES}>&nbsp;&nbsp;{L_CLOSED_PERIOD}:<input type="radio" name="closed" value="3" {CLOSED_PERIOD}>&nbsp;&nbsp;{L_CLOSED_NONE}:<input type="radio" name="closed" value="4" {CLOSED_NONE}>&nbsp;&nbsp;<input type="submit" name="submit" class="liteoption" value="{L_GO}" /></td>
</tr>
</form></table>

<table width="100%" cellpadding="4" cellspacing="1" class="forumline" align="center">
<tr>
	<th class="thCornerL">&nbsp;{L_MEETING_SUBJECT}&nbsp;</th>
	<th class="thtop">&nbsp;{L_MEETING_TIME}&nbsp;</th>
	<th class="thtop">&nbsp;{L_MEETING_UNTIL}&nbsp;</th>
	<th class="thtop">&nbsp;{L_MEETING_LOCATION}&nbsp;</th>
	<th class="thtop">&nbsp;{L_MEETING_CLOSED}&nbsp;</th>
	<th class="thCornerR" width="15%">&nbsp;{L_ACTION}&nbsp;</th>
</tr>
<!-- BEGIN meeting_overview_row -->
<tr>
	<td class="{meeting_overview_row.ROW_CLASS}">{meeting_overview_row.MEETING_SUBJECT}</td>
	<td class="{meeting_overview_row.ROW_CLASS}" align="center">{meeting_overview_row.MEETING_TIME}</td>
	<td class="{meeting_overview_row.ROW_CLASS}" align="center">{meeting_overview_row.MEETING_UNTIL}</td>
	<td class="{meeting_overview_row.ROW_CLASS}" align="center">{meeting_overview_row.MEETING_LOCATION}</td>
	<td class="{meeting_overview_row.ROW_CLASS}" align="center">{meeting_overview_row.MEETING_CLOSED}</td>
	<td class="{meeting_overview_row.ROW_CLASS}" align="right" nowrap="nowrap"">{meeting_overview_row.MEETING_EDIT} {meeting_overview_row.MEETING_DELETE}</td>
</tr>
<!-- END meeting_overview_row -->
<!-- BEGIN no_meeting_row -->
<tr>
	<td class="row1" colspan="6" align="center">{no_meeting_row.L_NO_MEETING}</td>
</tr>
<!-- END no_meeting_row -->
</table>
<table width="100%" align="center" cellpadding="0" cellspacing="0">
<tr>
	<td align="right" class="nav">{PAGINATION}</td>
</tr>
</table>
<br />
<div align="center" class="copyright">Meeting 1.3.18 &copy; 2004, {COPYRIGHT_YEAR} <a href="http://www.oxpus.de/" class="copyright" target="_blank">OXPUS</a></div>



