<table width="100%" cellspacing="2" cellpadding="2" align="center">
  <tr> 
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></td>
  </tr>
</table>

<table width="100%" cellpadding="4" cellspacing="1" class="forumline"><form method="post" action="{S_MODE_ACTION}">
<tr> 
	<th class="thCornerL" nowrap="nowrap">&nbsp;{L_USERNAME}&nbsp;</th>	
	<th class="thTop" nowrap="nowrap">&nbsp;{L_BAN_BY}&nbsp;</th>
	<th class="thTop" nowrap="nowrap">&nbsp;{L_BAN_REASON}&nbsp;</th>
	<th class="thCornerR" nowrap="nowrap">&nbsp;{L_BANNED}&nbsp;</th>
</tr>
<!-- BEGIN banlistrow -->
<tr> 
	<td class="{banlistrow.ROW_CLASS}" align="center" nowrap="nowrap"><span class="gen"><a href="{banlistrow.U_VIEWPROFILE}" class="gen">{banlistrow.USERNAME}</a></span></td>	
	<td class="{banlistrow.ROW_CLASS}" align="center" valign="middle" nowrap="nowrap"><a href="{banlistrow.U_VIEWPROFILE2}" class="gen">{banlistrow.BY}</a></td>
	<td class="{banlistrow.ROW_CLASS}" width="60%" valign="middle"><span class="genmed">{banlistrow.REASON}</span></td>
	<td class="{banlistrow.ROW_CLASS}" align="center" valign="middle" nowrap="nowrap"><span class="gensmall">{banlistrow.BANNED}</span></td>
</tr>
<!-- END banlistrow -->
<tr> 
	<td class="catBottom" colspan="4" align="center"><span class="genmed">{L_SELECT_SORT_METHOD}:&nbsp;{S_MODE_SELECT}&nbsp;&nbsp;{L_ORDER}:&nbsp;{S_ORDER_SELECT}&nbsp;&nbsp;<input type="submit" name="submit" value="{L_SUBMIT}" class="liteoption" /></span></td>
</tr>
</form></table>

<table width="100%" cellspacing="2" cellpadding="2">
<tr> 
	<td class="nav">{PAGE_NUMBER}</td>
	<td align="right" class="nav">{PAGINATION}</td>
</tr>
</table>
<br />
<table width="100%" cellspacing="2" cellpadding="2" align="center">
<tr> 
	<td align="right">{JUMPBOX}</td>
</tr>
</table>

