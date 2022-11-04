<table width="100%" cellspacing="2" cellpadding="2">
<tr>
	<td valign="middle" class="nav" width="100%"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></td>
	<td valign="bottom" align="right">{NEW_BET}&nbsp;&nbsp;&nbsp;{YOUR_STATS}&nbsp;&nbsp;&nbsp;{ALL_STATS}</td>
</tr>
</table>
<!-- BEGIN switch_welcome_message -->
<table width="100%" cellpadding="4" cellspacing="1" class="forumline">
<tr>
	<th class="thHead">{BOOKIES_HEADER}</th>
</tr>
<tr>
	<td class="row1" align="center">{BOOKIES_WELCOME}</td>
</tr>
</table>
<br />
<!-- END switch_welcome_message -->

<!-- Leaderboard -->
<!-- BEGIN switch_yes_stats -->
<table width="100%" cellpadding="4" cellspacing="1" class="forumline">
<tr>
	<th class="thHead" colspan="6">{BOOKIES_LEADER_HEADER}</th>
</tr>
<tr>
	<td class="row1" align="center" colspan="6">{LEADER_INFO}</td>
</tr>
<tr>
	<th class="thCornerL" nowrap="nowrap" width="5%">#</th>
	<th class="thTop" nowrap="nowrap">{LEADER_USERNAME}</th>
	<th class="thTop" nowrap="nowrap">{LEADER_TOTALBETS}</th>
	<th class="thTop" nowrap="nowrap">{LEADER_TOTALWIN}</th>
	<th class="thTop" nowrap="nowrap">{LEADER_TOTALLOSE}</th>
	<th class="thCornerR" nowrap="nowrap">{LEADER_NETPOS}</th>		
</tr>
<!-- END switch_yes_stats -->
<!-- BEGIN leader -->
<tr> 
	<td class="{leader.ROW_CLASS}" align="center"><b>{leader.POSITION}</b></td>
	<td class="{leader.ROW_CLASS}"><{leader.USERNAME}</td>
	<td class="{leader.ROW_CLASS}" align="center">{leader.TOTALBETS}<</td>
	<td class="{leader.ROW_CLASS}" align="center">{leader.TOTALWIN}<</td>
	<td class="{leader.ROW_CLASS}" align="center">{leader.TOTALLOSE}</td>
	<td class="{leader.ROW_CLASS}" align="center"><b>{leader.NETPOS}</b></td>
</tr>
<!-- END leader -->
<!-- BEGIN switch_yes_stats -->
<tr>
	<th class="thCornerL" nowrap="nowrap" colspan="2">BOOKMAKER TOTALS</th>
	<th class="thTop" nowrap="nowrap">{BK_TOTALBETS}</th>
	<th class="thTop" nowrap="nowrap">{BK_LOSSES}</th>
	<th class="thTop" nowrap="nowrap">{BK_WINNINGS}</th>
	<th class="thCornerR" nowrap="nowrap">{BK_NET_POSITION}</th>		
	</tr>
</table>
<br />
<!-- END switch_yes_stats -->
<!-- Leaderboard finish -->

<!-- BEGIN switch_bets_pending -->
<table width="100%" cellpadding="4" cellspacing="1" class="forumline"><form action="{S_ACTION}" method="post">
<tr>
	<th class="thHead" colspan="6"><span class="name"><a name="{PENDING_ANCHOR}"></a></span>{PENDING_HEADER}</th>
</tr>
<tr>
	<td class="row1" align="center" colspan="6">{PENDING_INFO}<br /><br />{POINTS_INFO}</td>
</tr>
<tr>
	<th class="thCornerL" nowrap="nowrap" width="5%">#</th>
	<th class="thTop" nowrap="nowrap">{BOOKIES_SLIP_MEETING}</th>
	<th class="thTop" nowrap="nowrap">{BOOKIES_SLIP_DATE}</th>
	<th class="thTop" nowrap="nowrap">{BOOKIES_SLIP_SELECTION}</th>
	<th class="thTop" nowrap="nowrap">{BOOKIE_SLIP_ODDS}</th>
	<th class="thCornerR" nowrap="nowrap">{BOOKIES_SLIP_STAKE}</th>	
</tr>
<!-- END switch_bets_pending -->
<!-- BEGIN pending -->
<tr>
	<td class="{pending.ROW_CLASS}" align="center">{pending.DEL_IMG}</td>
	<td class="{pending.ROW_CLASS}" align="center">{pending.MEETING}</td>
	<td class="{pending.ROW_CLASS}" align="center">{pending.DATE}</td>
	<td class="{pending.ROW_CLASS}" align="center">{pending.SELECTION}</td>
	<td class="{pending.ROW_CLASS}" align="center">{pending.ODDS}</td>
	<td class="{pending.ROW_CLASS}" align="center">{pending.STAKE_BOX_PEND} {POINTS_NAME}</td>
</tr>
<!-- END pending -->
<!-- BEGIN switch_bets_pending -->
<tr>
	<td class="catBottom" align="center" colspan="6"><input type="submit" name="change_stake" value="{BOOKIES_CHANGE_STAKE}" class="mainoption" /></td>
</tr>
</form></table>

<table width="100%" cellspacing="2" cellpadding="2">
<tr>
	<td valign="middle" class="nav" width="100%"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></td>
	<td valign="bottom" align="right">{NEW_BET}&nbsp;&nbsp;&nbsp;{YOUR_STATS}&nbsp;&nbsp;&nbsp;{ALL_STATS}</td>
</tr>
</table>
<!-- END switch_bets_pending -->
<br />
<div align="center" class="copyright">Forum Bookmakers {BOOKIE_VERSION} &copy; 2004, {COPYRIGHT_YEAR} Majorflam</div>
