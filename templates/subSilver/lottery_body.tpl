<table width="60%" cellspacing="2" cellpadding="2" align="center">
<tr> 
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> <a href="{S_CONFIG_ACTION}" class="nav">{L_NAME}</a> {LAST_LOCATION}</td>
</tr>
</table>

<table width="60%" cellpadding="4" cellspacing="1" align="center" class="forumline">
<!-- BEGIN switch_are_actions -->
<form method="post" action="{S_MODE_CONFIG}">
<!-- END switch_are_actions -->
<tr> 
	<th class="thHead">{L_INFO_TITLE}</th>
</tr>
<tr>
	<td class="row1"><table width="100%" cellspacing="1" cellpadding="2">
	<tr> 
		<td valign="middle" align="right" nowrap="nowrap" width="50%"><span class="gen">{L_TICKET_OWNED}:&nbsp;</span></td>
	  	<td><b class="gen">{TICKETS_OWNED}</b></td>
	</tr>
	<tr> 
		<td valign="middle" align="right" nowrap="nowrap"><span class="gen">{L_TICKETS_COST}:&nbsp;</span></td>
	  	<td><b class="gen">{L_TICKET_COST} {L_CURRENCY}</b></td>
	</tr>
	<tr> 
		<td valign="middle" align="right" nowrap="nowrap"><span class="gen">{L_BASE_POOL}:&nbsp;</span></td>
	  	<td><b class="gen">{L_PRIZE_BASE} {L_CURRENCY}</b></td>
	</tr>
	<!-- BEGIN switch_full_display -->
	<tr> 
		<td valign="middle" align="right" nowrap="nowrap"><span class="gen">{L_CURRENT_POOL}:&nbsp;</span></td>
	  	<td><b class="gen">{L_CURRENT_ENTRIES}</b></td>
	</tr>
	<tr> 
		<td valign="middle" align="right" nowrap="nowrap"><span class="gen">{L_TOTAL_POOL}:&nbsp;</span></td>
		<td><b class="gen">{L_TOTAL_PRIZE} {L_CURRENCY}</b></td>
	</tr>
	<tr> 
		<td valign="middle" align="right" nowrap="nowrap"><span class="gen">{L_WIN_QUOTA}:&nbsp;</span></td> 
		<td><b class="gen">{L_LOTTOQUOTE}%</b></td> 
	</tr>
	<!-- END switch_full_display -->
	<!-- BEGIN switch_items -->
	<tr> 
		<td valign="middle" align="right" nowrap="nowrap"><span class="gen">{L_ITEM_DRAW}:&nbsp;</span></td>
		<td><b class="gen">{L_ITEM_PRIZE}</b></td>
	</tr>
	<!-- END switch_items -->
	<tr> 
		<td valign="middle" align="right" nowrap="nowrap"><span class="gen">{L_TIME_DRAW}:&nbsp;</span></td>
		<td><b class="gen">{L_DURATION}</b></td>
	</tr>
	<!-- BEGIN switch_last_winner -->
	<tr> 
		<td valign="middle" align="right" nowrap="nowrap"><span class="gen">{L_LAST_WINNER}:&nbsp;</span></td>
		<td><b class="gen"><a href="{switch_last_winner.U_VIEWPROFILE}" class="gen">{switch_last_winner.WINNER_NAME}</a></b></td>
	</tr>
	<!-- END switch_last_winner -->
	</table></td>
</tr>
<!-- BEGIN switch_tickets_single -->
<tr>
	<td class="catBottom" align="center"><input type="submit" name="buy_ticket" value="{I_BUY_TICKET}" class="mainoption" /></td>
</tr>
<!-- END switch_tickets_single -->
<!-- BEGIN switch_tickets_multi -->
<tr>
	<td class="catBottom" align="center"><input type="text" name="amount" size="5" maxlength="5" value="1" class="post" /> <input type="submit" name="buy_tickets" value="{I_BUY_TICKETS}" class="mainoption" /></td>
</tr>
<!-- END switch_tickets_multi -->

<!-- BEGIN switch_are_actions -->
<input type="hidden" name="action" value="options" />
<tr>
	<td class="catBottom" align="center">
<!-- END switch_are_actions -->
	<!-- BEGIN switch_view_history -->
	<input type="submit" name="view_history" value="{I_VIEW_HISTORY}" class="liteoption" />&nbsp;
	<!-- END switch_view_history -->
	<!-- BEGIN switch_view_personal -->
	&nbsp;<input type="submit" name="view_personal" value="{I_VIEW_PHISTORY}" class="liteoption" />
	<!-- END switch_view_personal -->
<!-- BEGIN switch_are_actions -->
	</td>
</tr>
</form>
<!-- END switch_are_actions -->
</table>
<br />
<table width="100%" cellpadding="2" cellspacing="2">
<tr> 
	<td align="right" valign="top">{JUMPBOX}</td>
</tr>
</table>
<br />
<div align="center" class="copyright">Lottery 2.2.0 &copy; 2004, {COPYRIGHT_YEAR} <a href="http://www.zarath.com" class="copyright" target="_blank">Zarath Technologies</a></div>
