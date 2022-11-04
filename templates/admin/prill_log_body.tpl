{USERCOM_MENU}{PRILL_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<script language="JavaScript" src="{U_IM_PATH}main.js"></script>
<script language="JavaScript" type="text/javascript">
<!--
// Used to check or uncheck a list of check boxes
function select_switch(status)
{
	for (i = 0; i < document.newmsg_list.length; i++)
	{
		document.newmsg_list.elements[i].checked = status;
	}
}
//-->
</script>

<h1>{L_ML_TITLE}</h1>

<p>{L_ML_EXPLAIN}</p>

<table width="100%" cellpadding="4" cellspacing="1" class="bodyline"><form name="newmsg_list" method="post" action="{S_FORM_ACTION}">
<tr>
	<td class="row1" align="center"><a href="{U_RECEIVED}" class="nav">{L_RECEIVED}</a></td>
	<td class="row1" align="center"><a href="{U_SENT}" class="nav">{L_SENT}</a></td>
	<td class="row1" align="center"><a href="{U_OFF_RECEIVED}" class="nav">{L_OFF_RECEIVED}</a></td>
	<td class="row1" align="center"><a href="{U_OFF_SENT}" class="nav">{L_OFF_SENT}</a></td>
</tr>
</table>
<table width="100%" cellpadding="4" cellspacing="1" class="forumline">
<tr>
	<th colspan="5" class="thTop" height="25" nowrap="nowrap">{MESSAGES}</th>
</tr>
<!-- BEGIN switch_msg_list -->
<tr>
	<td class="catLeft" align="center"><span class="cattitle">{switch_msg_list.L_SUBJECT}</span></td>
	<td class="cat" align="center"><span class="cattitle">{switch_msg_list.L_FROM}</span></td>
	<td class="cat" align="center"><span class="cattitle">{switch_msg_list.L_TO}</span></td>
	<td class="cat" align="center"><span class="cattitle">{switch_msg_list.L_DATE}</span></td>
	<td class="catRight" align="center"><span class="cattitle">{switch_msg_list.L_MARK}</span></td>
</tr>
<!-- BEGIN switch_msg_row -->
<tr>
	<td class="{switch_msg_list.switch_msg_row.ROW_CLASS}"><a href="{switch_msg_list.switch_msg_row.U_IMMSGS}" target="read[{switch_msg_list.switch_msg_row.IMNUM}]" onClick="javascript:launch_spawn('{switch_msg_list.switch_msg_row.U_IMMSGS}', '{switch_msg_list.switch_msg_row.LEFT_PX}', '{switch_msg_list.switch_msg_row.TOP_PX}', '{READ_WIDTH}', '{READ_HEIGHT}', '{switch_msg_list.switch_msg_row.IMNUM}'); return false" title="{switch_msg_list.switch_msg_row.SUBJECT}">{switch_msg_list.switch_msg_row.SUBJECT}</a></td>
	<td class="{switch_msg_list.switch_msg_row.ROW_CLASS}" align="center"><a href="{switch_msg_list.switch_msg_row.U_SENDER}" target="_blank" title="{switch_msg_list.switch_msg_row.SENDER}">{switch_msg_list.switch_msg_row.SENDER}</a></td>
	<td class="{switch_msg_list.switch_msg_row.ROW_CLASS}" align="center"><a href="{switch_msg_list.switch_msg_row.U_RECEIVER}" target="_blank" title="{switch_msg_list.switch_msg_row.RECEIVER}">{switch_msg_list.switch_msg_row.RECEIVER}</a></td>
	<td class="{switch_msg_list.switch_msg_row.ROW_CLASS}" align="center">{switch_msg_list.switch_msg_row.DATE}</td>
	<td class="{switch_msg_list.switch_msg_row.ROW_CLASS}" align="center"><input type="checkbox" name="mark[]" value="{switch_msg_list.switch_msg_row.S_MARK_ID}"></td>
</tr>
<!-- END switch_msg_row -->
<tr>
	<td class="catBottom" colspan="5" align="center">{switch_msg_list.S_HIDDEN_FIELDS}<input type="submit" name="delete" value="{switch_msg_list.L_DELETE}" class="mainoption" /></td>
</tr>
<!-- END switch_msg_list -->
<!-- BEGIN switch_no_msg -->
<tr>
	<td class="row1" colspan="5" align="center" height="30">{switch_no_msg.NO_MSG}</td>
</tr>
<!-- END switch_no_msg -->
</form></table>

<table width="100%" cellspacing="2" cellpadding="2">
<tr>
	<td><span class="nav">{PAGE_NUMBER}<br />{PAGINATION}</td>
	<!-- BEGIN switch_msg_list -->
	<td align="right"><b class="gensmall"><a href="javascript:select_switch(true);" class="gensmall">{switch_msg_list.L_MARK_ALL}</a> :: <a href="javascript:select_switch(false);" class="gensmall">{switch_msg_list.L_UNMARK_ALL}</a></b></td>
	<!-- END switch_msg_list -->
</tr>
</table>
<br />
<div align="center" class="copyright">Prillian 0.7.0 Beta &copy; 2003, {COPYRIGHT_YEAR} <a href="http://darkmods.sourceforge.net" class="copyright" target="_blank">Thoul</a></div>
