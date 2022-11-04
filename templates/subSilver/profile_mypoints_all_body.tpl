<table width="100%" cellspacing="2" cellpadding="2" align="center">
<tr> 
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></td>
</tr>
</table>

{CPL_MENU_OUTPUT}
  
<table align="center" width="100%" cellpadding="4" cellspacing="1" class="forumline"><form method="post" action="{S_MODE_ACTION}">
<tr> 
	<th class="thCornerL" nowrap="nowrap">&nbsp;#&nbsp;</th>	  	  		
	<th class="thTop" nowrap="nowrap">&nbsp;{L_TRANS}&nbsp;</th>
	<th class="thTop" nowrap="nowrap">&nbsp;{L_TRANS_REASON}&nbsp;</th>
	<th class="thTop" nowrap="nowrap">&nbsp;{L_TRANS_DATE}&nbsp;</th>	  
	<th class="thCornerR" nowrap="nowrap">&nbsp;{L_TRANS_AMOUNT}&nbsp;</th>
</tr>
<!-- BEGIN trans -->
<tr>
	<td colspan="5" align="center" class="row3"><table width="100%" cellspacing="1">
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
	<td class="{transrow.ROW_CLASS}" align="center">{transrow.ROW_NUMBER}</td>
	<td class="{transrow.ROW_CLASS}" align="center">{transrow.TRANS}</td>
	<td class="{transrow.ROW_CLASS}" align="center">{transrow.TRANS_REASON}</td>
	<td class="{transrow.ROW_CLASS}" align="center">{transrow.TRANS_DATE}</td>	  	  
	<td class="{transrow.ROW_CLASS}" align="center">{transrow.TRANS_AMOUNT}</td>	  	  
</tr>
<!-- END transrow -->
<!-- BEGIN totalsend -->			
<tr> 
	<td class="row2" colspan="4" align="right"><b>{L_TOTAL_TRANS}:</b></td> 
	<td class="row3" align="center"><b>{totalsend.TOTAL_SENT}</b></td>	  	  
</tr>
<!-- END totalsend -->			
<tr> 
	<td class="catBottom" colspan="5" align="center"><span class="genmed">{L_SELECT_SORT_METHOD}:&nbsp;{S_MODE_SELECT}&nbsp;&nbsp;{L_ORDER}:&nbsp;{S_ORDER_SELECT}&nbsp;&nbsp; <input type="submit" name="submit" value="{L_SORT}" class="mainoption" /></span></td>	  	  
</tr>
</form></table>
	</td>
  </tr>
</table>
<br />
<table width="100%" cellspacing="2" align="center">
  <tr> 
	<td valign="top" align="right">{JUMPBOX}</td>
  </tr>
</table>