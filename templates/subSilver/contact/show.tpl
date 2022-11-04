<script language="JavaScript" type="text/javascript">
<!--
function select_switch(status)
{
	for (i = 0; i < document.user_list.length; i++)
	{
		document.user_list.elements[i].checked = status;
	}
}
//-->
</script>

{CONTACT_CP_LINKS}

<!-- BEGIN list -->
<table width="100%" cellpadding="4" cellspacing="1" class="forumline"><form name="user_list" method="post" action="{list.S_FORM_ACTION}">
{list.S_HIDDEN_FIELDS}
<tr>
	<th class="thHead" colspan="5">{TYPE_TITLE}</th>
</tr>
<tr>
	<td class="row2" colspan="5" align="center"><b class="gensmall">{TOTAL_TEXT}</b></td>
</tr>
<tr>
	<th class="thCornerL" nowrap="nowrap">&nbsp;#&nbsp;</th>
	<th class="thTop" nowrap="nowrap">&nbsp;{L_USERNAME}&nbsp;</th>
	<th class="thTop" nowrap="nowrap">&nbsp;{L_PROFILE}&nbsp;</th>
	<th class="thTop" nowrap="nowrap">&nbsp;{L_PM}&nbsp;</th>
	<th class="thCornerR" nowrap="nowrap">&nbsp;{L_SELECT}&nbsp;</th>
</tr>
<!-- BEGIN list_row -->
<tr>
	<td width="5%" class="{list.list_row.ROW_CLASS}" align="center"><span class="gen">{list.list_row.ROW_NUMBER}</span></td>
	<td width="60%" class="{list.list_row.ROW_CLASS}"><span class="gen"><a href="{list.list_row.U_PROFILE}" class="gen">{list.list_row.USERNAME}</a></span></td>
	<td width="15%" class="{list.list_row.ROW_CLASS}" align="center">{list.list_row.PROFILE_IMG}</td>
	<td width="15%" class="{list.list_row.ROW_CLASS}" align="center">{list.list_row.PM_IMG}</td>
	<td width="5%" class="{list.list_row.ROW_CLASS}" align="center">
	<!-- BEGIN mark -->
	<input type="checkbox" name="mark[]2" value="{list.list_row.mark.S_MARK_ID}" />
	<!-- END mark -->
	</td>
</tr>
<!-- END list_row -->
<tr>
	<td class="catBottom" align="right" colspan="5"><input type="submit" name="submit" value="{list.L_ADD_TO_BUDDY}" class="mainoption" /></td>
</tr>
</form></table>

<table width="100%" cellspacing="2" cellpadding="2"><form method="post" action="{S_SORT_ACTION}">
<tr>
	<td nowrap="nowrap" class="genmed">{L_ORDER}: {S_ORDER_SELECT}</td>
	<td align="right" nowrap="nowrap"><b class="gensmall"><a href="javascript:select_switch(true);" class="gensmall">{L_MARK_ALL}</a> :: <a href="javascript:select_switch(false);" class="gensmall">{L_UNMARK_ALL}</a></b><br /><span class="nav">{PAGINATION}</span></td>
</tr>
<tr>
	<td class="nav">{PAGE_NUMBER}</td>
	<td class="nav" align="right">{PAGINATION}</td>
</tr>
</form></table>
<!-- END list -->
	</td>
</tr>
</table>
<br />
<div align="center" class="copyright">Prillian 0.7.0 Beta &copy; 2003, {COPYRIGHT_YEAR} <a href="http://darkmods.sourceforge.net" class="copyright" target="_blank">Thoul</a></div>
<br />
<table width="100%" cellpadding="2" cellspacing="2">
  <tr> 
	<td align="right">{JUMPBOX}</td>
  </tr>
</table>
