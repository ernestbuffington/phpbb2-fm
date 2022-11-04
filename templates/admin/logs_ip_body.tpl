{LOG_MENU}{UTILS_MENU}{DB_MENU}{LANG_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<script language="Javascript" type="text/javascript">
<!--
function select_switch(status)
{
	for (i = 0; i < document.log_list.length; i++)
	{
		document.log_list.elements[i].checked = status;
	}
}
//-->
</script>

<h1>{L_LOG_ACTIONS_TITLE}</h1>

<p>{L_LOG_ACTION_EXPLAIN}</p>

<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline"><form method="post" name="log_list" action="{S_MODE_ACTION}">
<tr>
	<th class="thCornerL">{L_USERNAME}</th>
	<th class="thTop">{L_DATE}</th>
	<th class="thTop">{L_IP}</th>
	<th class="thTop">{L_BROWSER}</th>
	<th class="thTop">{L_URL}</th>
	<th class="thTop">{L_ACTION}</th>
	<th class="thCornerR" width="5%">{L_DELETE_LOG}</th>
</tr>
<!-- BEGIN record_row -->
<tr>
	<td class="{record_row.ROW_CLASS}" align="center"><a href="#" onclick="JavaScript:window.open('{record_row.U_USERNAME}', '_phpbbprivmsg', 'HEIGHT=450, resizable=yes, scrollbars=yes, WIDTH=850')" class="gensmall">{record_row.USERNAME}</a></td>
	<td class="{record_row.ROW_CLASS}">{record_row.DATE}</td>
	<td class="{record_row.ROW_CLASS}" align="center"><a href="#" onclick="JavaScript:window.open('{record_row.U_IP}', '_phpbbprivmsg', 'HEIGHT=450, resizable=yes, scrollbars=yes, WIDTH=850')" class="gensmall">{record_row.IP}</a><br /><a href="#" onclick="JavaScript:window.open('{record_row.U_HOST}', '_phpbbprivmsg', 'HEIGHT=450, resizable=yes, scrollbars=yes, WIDTH=850')" class="gensmall">{record_row.HOST}</a></td>
	<td class="{record_row.ROW_CLASS}" align="center"><a href="#" onclick="JavaScript:window.open('{record_row.U_BROWSER}', '_phpbbprivmsg', 'HEIGHT=450, resizable=yes, scrollbars=yes, WIDTH=850')" class="gensmall">{record_row.BROWSER}</a></td>
	<td class="{record_row.ROW_CLASS}" align="center"><a href="#" onclick="JavaScript:window.open('{record_row.U_FORUM}', '_phpbbprivmsg', 'HEIGHT=450, resizable=yes, scrollbars=yes, WIDTH=850')" class="gensmall">{record_row.FORUM}</a></td>
	<td class="{record_row.ROW_CLASS}" align="center" nowrap="nowrap"><a href="javascript:alert('{record_row.REFERRER}')" class="gensmall">{record_row.L_SHOW_REFERRER}</a><br /><a href="#" onclick="JavaScript:window.open('{record_row.U_REFERRER}', '_phpbbprivmsg', 'HEIGHT=450, resizable=yes, scrollbars=yes, WIDTH=850')" class="gensmall">{record_row.L_SAME_REFERRER}?</a></td>
	<td class="{record_row.ROW_CLASS}" align="center" valign="middle"><input type="checkbox" name="log_list[]" value="{record_row.IP_ID}" /></td>
</tr>
<!-- END record_row -->
</table>
<table width="100%" align="center" cellspacing="2" cellpadding="2">
  <tr> 
	<td class="gensmall"><b>{PAGE_NUMBER}</b> [ <b>{TOTAL_LOGS}</b> ]<br /><span class="nav">{PAGINATION}</span></td>
	<td valign="top" align="right" nowrap="nowrap"><b class="gensmall"><a href="javascript:select_switch(true);" class="gensmall">{L_MARK_ALL}</a> :: <a href="javascript:select_switch(false);" class="gensmall">{L_UNMARK_ALL}</a></b></td>
  </tr>
<tr>
	<td><input type="submit" name="delete_all" class="liteoption" value="{L_DELETE_ALL}" /></td>
	<td align="right"><input type="submit" name="delete" class="liteoption" value="{L_DELETE}" /></td>
</tr>
</form></table>
