<table width="100%" cellspacing="2" cellpadding="2">
<tr>
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> <a href="{U_BOOKIES}" class="nav">{L_BOOKIES}</a></td>
	<td align="right">{NEW_BET}&nbsp;&nbsp;&nbsp;{YOUR_STATS}&nbsp;&nbsp;&nbsp;{ALL_STATS}</td>
	</tr>
</table>

<table width="100%" cellpadding="4" cellspacing="1" class="forumline">
<tr>
	<td class="catHead" colspan="8" align="center"><span class="cattitle">{BOOKIES_ALLSTATS_HEADER}</span></td>
</tr>
<!-- BEGIN stats_available -->
<tr>
	<td class="row1" colspan="8">{ALL_COMPLETE_STATS}</td>
</tr>
<tr>
	<td class="catBottom" colspan="8">&nbsp;</td>
</tr>
</table>

<table width="100%" cellspacing="2" cellpadding="2"><form action="" method="post">
<tr> 
	<td class="nav">{PAGE_NUMBER}<br />{PAGINATION}</td> 
	<td align="right" class="genmed">{L_SELECT_SORT_METHOD}:&nbsp;{S_MODE_SELECT}&nbsp;&nbsp;{L_ORDER}&nbsp;{S_ORDER_SELECT}&nbsp;&nbsp;<input type="submit" name="submit" value="{L_SUBMIT}" class="liteoption" /></td>
</tr> 
</form></table> 

<table width="100%" cellpadding="4" cellspacing="1" class="forumline">
<!-- END stats_available -->
<tr>
	<th class="thCornerL" nowrap="nowrap">{BOOKIES_SLIP_USERNAME}</th>
	<th class="thTop" nowrap="nowrap">{BOOKIES_SLIP_TIME_STATS}</th>
	<th class="thTop" nowrap="nowrap">{BOOKIES_SLIP_MEETING}</th>
	<th class="thTop" nowrap="nowrap">{BOOKIES_SLIP_SELECTION}</th>
	<th class="thTop" nowrap="nowrap">{BOOKIES_SLIP_STAKE}</th>
	<th class="thTop" nowrap="nowrap">{BOOKIES_SLIP_ODDS}</th>
	<th class="thTop" nowrap="nowrap">{BOOKIES_SLIP_RESULT}</th>
	<th class="thCornerR" nowrap="nowrap">{BOOKIES_SLIP_WINLOSS}</th>		
</tr>
<!-- BEGIN allstats -->
<tr> 
	<td class="{allstats.ROW_CLASS}">{allstats.USERNAME}</td>
	<td class="{allstats.ROW_CLASS}" align="center">{allstats.TIME}</td>
	<td class="{allstats.ROW_CLASS}" align="center">{allstats.MEETING}</td>
	<td class="{allstats.ROW_CLASS}" align="center">{allstats.SELECTION}</td>
	<td class="{allstats.ROW_CLASS}" align="center">{allstats.STAKE}</td>
	<td class="{allstats.ROW_CLASS}" align="center">{allstats.ODDS}</td>
	<td class="{allstats.ROW_CLASS}" align="center">{allstats.RESULT}</td>
	<td class="{allstats.ROW_CLASS}" align="center"><b>{allstats.WINLOSS}</b></td>
</tr>
<!-- END allstats -->
<tr>
	<td class="catBottom" align="center" colspan="8"><b class="gen">{SWITCH_INFO}</b></td>
</tr>
</table> 

<table width="100%" cellspacing="2" cellpadding="2"> 
<tr> 
	<td class="nav">{PAGE_NUMBER}</td> 
	<td align="right" class="nav">{PAGINATION}</td> 
</tr> 
</table>
<br />
<div align="center" class="copyright">Forum Bookmakers {BOOKIE_VERSION} &copy; 2004, {COPYRIGHT_YEAR} Majorflam</div>


