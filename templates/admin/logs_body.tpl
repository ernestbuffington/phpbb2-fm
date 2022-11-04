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

<table width="100%" align="center" cellpadding="2" cellspacing="2"><form method="post" action="{S_MODE_ACTION}">
<tr>
	<td align="right"><span class="genmed">{L_CHOOSE_SORT} {S_MODE_SELECT} &nbsp;{L_ORDER} {S_ORDER_SELECT}&nbsp;&nbsp;<input type="submit" name="submit" value="{L_SUBMIT}" class="liteoption" /></span></td>
</tr>
</form></table>
<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline"><form method="post" name="log_list" action="{S_MODE_ACTION}">
<tr>
	<th class="thCornerL">{L_DONE_BY}</th>
	<th class="thTop">{L_USER_IP}</th>
	<th class="thTop">{L_DATE}</th>
	<th class="thTop" width="5%">{L_ACTION}</th>
	<th class="thTop">{L_TOPIC}</th>
	<th class="thCornerR" width="5%">{L_DELETE_LOG}</th>
</tr>
<!-- BEGIN record_row -->
<tr>
	<td class="{record_row.ROW_CLASS}" align="center">{record_row.USERNAME}</td>
	<td class="{record_row.ROW_CLASS}" align="center"><a href="{record_row.U_WHOIS_IP}" target="_blank">{record_row.USER_IP}</a></td>
	<td class="{record_row.ROW_CLASS}" align="center" nowrap="nowrap"><span class="postdetails">{record_row.DATE}</span></td>
	<td class="{record_row.ROW_CLASS}" align="center"><img src="{record_row.ACTION}" alt="{record_row.ACTION_ALT}" title="{record_row.ACTION_ALT}" /></td>
	<td class="{record_row.ROW_CLASS}">{record_row.TOPIC}</td>
	<td class="{record_row.ROW_CLASS}" align="center" valign="middle"><input type="checkbox" name="log_list[]" value="{record_row.ID_LOG}" /></td>
</tr>
<!-- END record_row -->
</table>
<table width="100%" align="center" cellspacing="2" cellpadding="2">
  <tr> 
	<td class="gensmall"><b>{PAGE_NUMBER}</b> [ <b>{TOTAL_LOGS}</b> ]<br /><span class="nav">{PAGINATION}</span></td>
	<td valign="top" align="right" nowrap="nowrap"><b class="gensmall"><a href="javascript:select_switch(true);" class="gensmall">{L_MARK_ALL}</a> :: <a href="javascript:select_switch(false);" class="gensmall">{L_UNMARK_ALL}</a></b></td>
  </tr>
<tr>
	<td valign="top" colspan="2" align="right"><input type="submit" name="delete" class="liteoption" value="{L_DELETE}" /></td>
</tr>
</form></table>
