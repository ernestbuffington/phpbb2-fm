<table width="100%" cellspacing="2" cellpadding="2" align="center"><form action="{S_THREAD_KICKER_ADMIN}" method="post">
<tr>
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> {KICKER_TABLE}</td>
	<td align="right" nowrap="nowrap" class="genmed">{L_SELECT_SORT_METHOD}:&nbsp;{S_MODE_SELECT}&nbsp;&nbsp;{L_ORDER}&nbsp;{S_ORDER_SELECT}&nbsp;&nbsp;<input type="submit" name="submit" value="{L_SUBMIT}" class="liteoption" /></td>
</tr>
</table>

<table width="100%" cellpadding="4" cellspacing="1" class="forumline">	
<tr>
	<th class="thCornerL" nowrap="nowrap">{KICKED}</th>
	<th class="thTop" nowrap="nowrap">{DATE}</th>
	<th class="thTop" nowrap="nowrap">{KICKED_BY}</th>		
	<th class="thCornerR" nowrap="nowrap">{UNKICK}</th>
</tr>
<!-- BEGIN kicker -->
<tr> 
	<td class="{kicker.ROW_CLASS}" align="center">{kicker.KICKED}</td>
	<td class="{kicker.ROW_CLASS}" align="center">{kicker.DATE}</td>
	<td class="{kicker.ROW_CLASS}" align="center">{kicker.KICKED_BY}</td>
	<td class="{kicker.ROW_CLASS}" align="center">{kicker.CHECKBOX}</td>
</tr>
<!-- END kicker -->
<tr>
	<td colspan="4" class="catBottom" align="right"><input type="submit" name="unkick_marked" value="{KICK_MARKED}" class="mainoption" /></td>
</tr>
</form></table>
<table width="100%" align="center" cellpadding="2" cellspacing="2">
<tr> 
	<td class="nav">{PAGE_NUMBER}</td> 
	<td align="right" class="nav">{PAGINATION}</td> 
</tr> 
</table> 
<br />
<div align="center" class="copyright">Thread Kicker 1.0.4 by &copy; 2004, 2005 <a href="http://www.majormod.com" class="copyright" target="_blank">Majorflam</a></div>

