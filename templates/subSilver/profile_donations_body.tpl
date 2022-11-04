<table width="100%" cellspacing="2" cellpadding="2" align="center">
<tr> 
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></td>
</tr>
</table>

{CPL_MENU_OUTPUT}

<table align="center" cellpadding="4" cellspacing="1" width="100%" class="forumline"><form method="post" action="{S_RECORDS_DAYS_ACTION}">
<tr> 
	<th width="50%" class="thCornerL" nowrap="nowrap">&nbsp;{L_USERNAME}&nbsp;</th>
	<th class="thTop" nowrap="nowrap">&nbsp;{L_LW_MONEY}&nbsp;</th>
	<th width="150" class="thCornerR" nowrap="nowrap">&nbsp;{L_DATE}&nbsp;</th>
</tr>
<!-- BEGIN switch_records -->
<tr>
	<td colspan="7" align="center" class="row3"><table width="100%" cellspacing="1">
	<tr>
		<td class="nav" nowrap="nowrap">{PAGE_NUMBER}</td>
		<td class="gensmall" nowrap="nowrap">&nbsp;[ {TOTAL_RECORDS} ]&nbsp;</td>
	  	<td align="right" class="nav" width="100%" nowrap="nowrap">{PAGINATION}</td>
	</tr>
	</table></td>
</tr>
<!-- END switch_records -->
<!-- BEGIN donation -->
<tr> 
	<td class="{donation.ROW_CLASS}" valign="middle"><span class="postdetails">{donation.LW_DONORS_NAME}</span></td>
	<td class="{donation.ROW_CLASS}" align="center" valign="middle"><span class="postdetails">{donation.LW_MONEY}</span></td>
	<td class="{donation.ROW_CLASS}" align="center" valign="middle" nowrap="nowrap"><span class="postdetails">{donation.LW_DATE}</span></td>
</tr>
<!-- END donation -->
<!-- BEGIN switch_lw_no_records -->
<tr> 
	<td class="row1" colspan="7" align="center"><b class="gensmall">{L_LW_NO_RECORDS}</b></td>
</tr>
<!-- END switch_lw_no_records -->
<tr> 
	<td class="catBottom" align="center" colspan="7"><span class="genmed">{L_DISPLAY_TOPICS}&nbsp;{S_SELECT_TOPIC_DAYS}&nbsp;{LW_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SORT}" class="mainoption" /></span></td>
</tr>
</form></table></td>
</tr>
</table>
<br />
<div align="center" class="copyright">Donation 1.0.2 &copy; 2004, {COPYRIGHT_YEAR} <a href="http://bbs.loewen.com.sg" target="_blank" class="copyright">Loewen Enterprise</a></div>
<br />
<table width="100%" cellspacing="2" align="center">
  <tr> 
	<td valign="top" align="right">{JUMPBOX}</td>
  </tr> 
</table> 
