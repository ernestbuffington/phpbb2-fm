<table width="100%" cellspacing="2" cellpadding="2" align="center">
<tr> 
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></td>
</tr>
</table>

{CPL_MENU_OUTPUT}

<table width="100%" cellpadding="4" cellspacing="1" class="forumline">
<tr>
	<td class="catHead" colspan="5" align="center"><span class="cattitle">{L_RECENT_TRANS_TO}</span></td>	  	  
</tr>
<tr>
	<td colspan="5" align="center" class="row3"><table width="100%" cellspacing="1">
	<tr>
		<td class="nav" nowrap="nowrap"><a href="{U_MYPOINTS_TO}" class="nav">{L_VIEW_ALL}</a></td>
	</tr>
	</table></td>
</tr>
<tr> 
	<th class="thCornerL" nowrap="nowrap">&nbsp;#&nbsp;</th>	  	  		
	<th class="thTop" nowrap="nowrap">&nbsp;{L_TRANS_FROM}&nbsp;</th>	  
	<th class="thTop" nowrap="nowrap">&nbsp;{L_TRANS_REASON}&nbsp;</th>
	<th class="thTop" nowrap="nowrap">&nbsp;{L_TRANS_DATE}&nbsp;</th>	  
	<th class="thCornerR" nowrap="nowrap">&nbsp;{L_TRANS_AMOUNT}&nbsp;</th>
</tr>
<!-- BEGIN transtorow -->
<tr> 
	<td class="{transtorow.ROW_CLASS}" align="center">{transtorow.ROW_NUMBER}</td>
	<td class="{transtorow.ROW_CLASS}" align="center">{transtorow.TRANS_FROM}</td>	  
	<td class="{transtorow.ROW_CLASS}" align="center">{transtorow.TRANS_REASON}</td>
	<td class="{transtorow.ROW_CLASS}" align="center">{transtorow.TRANS_DATE}</td>	  	  
	<td class="{transtorow.ROW_CLASS}" align="center">{transtorow.TRANS_AMOUNT}</td>	  	  
</tr>
<!-- END transtorow -->	
<!-- BEGIN totalsendto -->			
<tr> 
	<td class="row2" colspan="4" align="right"><b>{L_TOTAL_TRANS}: </b></td>	  	  
	<td class="row3" align="center"><b>{totalsendto.TOTAL_SENT_TO}</b></td>	  	  
</tr>
<!-- END totalsendto -->	
</table>
<br /> 

<table width="100%" cellpadding="4" cellspacing="1" class="forumline">
<tr> 	  
	<td class="catHead" colspan="5" align="center"><span class="cattitle">{L_RECENT_TRANS_FROM}</span></td>	  	  
</tr>	
<tr>
	<td colspan="5" align="center" class="row3"><table width="100%" cellspacing="1">
	<tr>
		<td class="nav" nowrap="nowrap"><a href="{U_MYPOINTS_FROM}" class="nav">{L_VIEW_ALL}</a></td>
	</tr>
	</table></td>
</tr>       
<tr> 
	<th class="thCornerL" nowrap="nowrap">&nbsp;#&nbsp;</th>	  	  			  
	<th class="thTop" nowrap="nowrap">&nbsp;{L_TRANS_TO}&nbsp;</th>
	<th class="thTop" nowrap="nowrap">&nbsp;{L_TRANS_REASON}&nbsp;</th>
	<th class="thTop" nowrap="nowrap">&nbsp;{L_TRANS_DATE}&nbsp;</th>	  
	<th class="thCornerR" nowrap="nowrap">&nbsp;{L_TRANS_AMOUNT}&nbsp;</th>
</tr>
<!-- BEGIN transfromrow -->
<tr> 
	<td class="{transfromrow.ROW_CLASS}" align="center">{transfromrow.ROW_NUMBER}</td>
	<td class="{transfromrow.ROW_CLASS}" align="center">&nbsp;{transfromrow.TRANS_TO}</td>
	<td class="{transfromrow.ROW_CLASS}" align="center">{transfromrow.TRANS_REASON}</td>
	<td class="{transfromrow.ROW_CLASS}" align="center">{transfromrow.TRANS_DATE}</td>	  	  
	<td class="{transfromrow.ROW_CLASS}" align="center">{L_CUSTOM_POINT_NAME}{transfromrow.TRANS_AMOUNT}</td>	  	  
</tr>
<!-- END transfromrow -->	
<!-- BEGIN totalsendfrom -->			
<tr>
	<td class="row2" align="right" colspan="4"><b>{L_TOTAL_TRANS}:</b></td>
	<td class="row3" align="center"><b>{totalsendfrom.TOTAL_SENT_FROM}</b></td>
</tr>
<!-- END totalsendfrom -->	
</table>
	</td>
  </tr>
</table>
<br />
<table width="100%" cellspacing="2" cellspacing="2" align="center">
  <tr> 
	<td align="right">{JUMPBOX}</td>
  </tr>
</table>
