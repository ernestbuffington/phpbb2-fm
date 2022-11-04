<table width="100%" cellspacing="2" cellpadding="2">
<tr>
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> <a href="{U_MEETING}" class="nav">{L_MEETING}</a></td>
</tr>
</table>

<table width="100%" cellpadding="4" cellspacing="1" class="forumline">
<tr>
	<th class="thCornerL">{L_MEETING_SUBJECT}</th>
	<th class="thTop">{L_MEETING_TIME}</th>
	<th class="thTop">{L_MEETING_UNTIL}</th>
	<th class="thTop">{L_MEETING_LOCATION}</th>
	<th class="thCornerR">{L_MEETING_USERS}</th>
</tr>
<!-- BEGIN meeting_overview_row -->
<tr>
	<td class="{meeting_overview_row.ROW_CLASS}"><span class="genmed">{meeting_overview_row.MEETING_SUBJECT}</span></td>
	<td class="{meeting_overview_row.ROW_CLASS}" align="center"><span class="genmed">{meeting_overview_row.MEETING_TIME}</span></td>
	<td class="{meeting_overview_row.ROW_CLASS}" align="center"><span class="genmed">{meeting_overview_row.MEETING_UNTIL}</span></td>
	<td class="{meeting_overview_row.ROW_CLASS}" align="center"><span class="genmed">{meeting_overview_row.MEETING_LOCATION}</span></td>
	<td class="{meeting_overview_row.ROW_CLASS}" align="center"><span class="genmed">{meeting_overview_row.MEETING_DETAIL}</span></td>
</tr>
<!-- END meeting_overview_row -->
<!-- BEGIN no_meeting_row -->
<tr>
	<td class="row1" colspan="5" align="center"><span class="genmed">{no_meeting_row.L_NO_MEETING}</span></td>
</tr>
<!-- END no_meeting_row -->
<tr>
	<td class="catBottom" colspan="5">&nbsp;</td>
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

