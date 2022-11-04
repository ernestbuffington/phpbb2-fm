
<table width="100%" cellspacing="2" cellpadding="2" align="center">
<tr> 
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></td>
</tr>
</table>

{CPL_MENU_OUTPUT}

<table align="center" width="100%" cellpadding="4" cellspacing="1" class="forumline"><form method="post" action="{S_MODE_ACTION}">
<tr> 
	<th class="thCornerL" nowrap="nowrap">&nbsp;#&nbsp;</th>	  	  		
	<th class="thTop" nowrap="nowrap">&nbsp;{L_TRANS_USER}&nbsp;</th>
	<th class="thTop" nowrap="nowrap">&nbsp;{L_TRANS_TYPE}&nbsp;</th>
	<th class="thTop" nowrap="nowrap">&nbsp;{L_TRANS_ITEM}&nbsp;</th>
	<th class="thTop" nowrap="nowrap">&nbsp;{L_TRANS_TOTAL}&nbsp;</th>
	<th class="thCornerR" nowrap="nowrap">&nbsp;{L_SHOPTRANS_DATE}&nbsp;</th>	  
</tr>
<!-- BEGIN trans -->
<tr>
	<td colspan="6" align="center" class="row3"><table width="100%" cellspacing="1">
	<tr>
		<td class="nav" nowrap="nowrap">{PAGE_NUMBER}</td>
		<td class="gensmall" nowrap="nowrap">&nbsp;[ {TOTAL_TRANSACTIONS} ]&nbsp;</td>
	  	<td align="right" class="nav" width="100%" nowrap="nowrap">{PAGINATION}</td>
	</tr>
	</table></td>
</tr>
<!-- END trans -->
<!-- BEGIN transrow -->
<tr> 
	<td class="{transrow.ROW_CLASS}" align="center">{transrow.ROW_NUMBER}</span></td>
	<td class="{transrow.ROW_CLASS}" align="center"><a href="{transrow.U_VIEWPROFILE}" class="genmed">{transrow.TRANS_USER}</a></td>
	<td class="{transrow.ROW_CLASS}" align="center">{transrow.TRANS_TYPE}</span></td>	
	<td class="{transrow.ROW_CLASS}" align="center">{transrow.TRANS_ITEM}</span></td>
	<td class="{transrow.ROW_CLASS}" align="center" valign="middle">{L_MONEY_SYMBOL}{transrow.TRANS_TOTAL}</span></td>	  	  
	<td class="{transrow.ROW_CLASS}" align="center" valign="middle">{transrow.SHOPTRANS_DATE}</span></td>	  
</tr>
<!-- END transrow -->
<!-- BEGIN none -->
<tr>
	<td colspan="6" align="center" class="row1"><b class="gensmall">{none.L_NONE}</b></td>
</tr>
<!-- END none -->
<!-- BEGIN trans -->
<tr> 
	<td class="catBottom" colspan="6" align="center"><span class="genmed">{L_SELECT_SORT_METHOD}:&nbsp;{S_MODE_SELECT}&nbsp;&nbsp;{L_ORDER}:&nbsp;{S_ORDER_SELECT}&nbsp;&nbsp;<input type="submit" name="submit" value="{L_SORT}" class="mainoption" /></span></td>
</tr>
<!-- END trans -->
</form></table>
	</td>
</tr>
</table>
<br />
<div align="center" class="copyright">Shop 2.6.0 &copy; 2003, {COPYRIGHT_YEAR} <a href="http://www.zarath.com/" class="copyright" target="_blank">Zarath Technologies</a></div>
<br />
<table width="100%" cellspacing="2" align="center">
<tr> 
	<td valign="top" align="right">{JUMPBOX}</td>
</tr>
</table>
