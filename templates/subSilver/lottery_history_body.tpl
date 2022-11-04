<table width="100%" cellspacing="2" cellpadding="2" align="center">
<tr> 
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a>{LOCATION}</td>
</tr>
</table>

<table width="100%" cellpadding="4" cellspacing="1" align="center" class="forumline"><form method="post" action="{S_MODE_ACTION}">
<tr>
	<th class="thCornerL">&nbsp;#&nbsp;</th>
	<th class="thTop">&nbsp;{L_WINNER}&nbsp;</th>
	<th class="thTop">&nbsp;{L_AMOUNT_WON}&nbsp;</th>
	<th class="thCornerR">&nbsp;{L_TIME_WON}&nbsp;</th>
</tr>
<!-- BEGIN listrow -->
<tr>
	<td class="{listrow.ROW_CLASS}" align="center"><span class="gen">{listrow.HISTORY_NUM}</span></td>
	<td class="{listrow.ROW_CLASS}" align="center"><span class="gen"><a href="{listrow.U_VIEWPROFILE}" class="gen">{listrow.HISTORY_WINNER}</a></span></td>
	<td class="{listrow.ROW_CLASS}" align="center"><span class="gen">{listrow.HISTORY_AMOUNT} {listrow.HISTORY_CURRENCY}</span></td>
	<td class="{listrow.ROW_CLASS}" align="center"><span class="gen">{listrow.HISTORY_TIME}</span></td>
</tr>
<!-- END listrow -->
<!-- BEGIN switch_no_history -->
<tr>
	<td class="row1" colspan="4" align="center" height="30"><span class="gen">{switch_no_history.MESSAGE}</span></td>
</tr>
<!-- END switch_no_history -->
<tr>
	<td class="catBottom" colspan="4">&nbsp;</td>
</tr>
</form></table>

<!-- BEGIN switch_title_info -->
<table width="100%" cellspacing="0" cellpadding="0">
<tr> 
	<td class="nav">{PAGE_NUMBER}</td>
	<td align="right" class="nav">{PAGINATION}</td>
</tr>
</table>
<!-- END switch_title_info -->
<br />
<table width="100%" cellpadding="2" cellspacing="2">
  <tr> 
	<td align="right" valign="top">{JUMPBOX}</td>
  </tr>
</table>
<br />
<div align="center" class="copyright">Lottery 2.2.0 &copy; 2004, {COPYRIGHT_YEAR} <a href="http://www.zarath.com" class="copyright" target="_blank">Zarath Technologies</a></div>

