
<table width="100%" cellspacing="2" cellpadding="2">
<tr>
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> <a href="{U_BOOKIES}" class="nav">{L_BOOKIES}</a></td>
	<td align="right">{YOUR_STATS}&nbsp;&nbsp;&nbsp;{ALL_STATS}</td>
</tr>
</table>

<!-- BEGIN switch_user_bets_on -->
<br />
<table width="100%" cellpadding="4" cellspacing="1" class="forumline"><form action="" method="post">
<tr>
	<th class="thHead" colspan="2">{ENTER_DETAILS}</th>
</tr>
<!-- BEGIN timezone_warning -->
<tr>
	<td class="row1" colspan="2" height="30" align="center"><span class="gen">{WARNING}</span></td>
</tr>
<!-- END timezone_warning -->
<tr>
	<td class="row2" width="50%"><b>{TIME_MEETING}:</b><br /><span class="gensmall">{TIME_MEETING_EXPLAIN}</span></td>
	<td class="row2"><select name="bet_day">{DAY_BOX} -
		<select name="bet_month">{MONTH_BOX} -
		<select name="bet_year">{YEAR_BOX} @
		<select name="bet_hour">{BETHOUR_BOX} :
		<select name="bet_minute">{BETMINUTE_BOX}</td>
</tr>
<tr>
	<td class="row1"><b>{MEETING}:</b><br /><span class="gensmall">{MEETING_EXPLAIN}</span></td>
	<td class="row1"><input class="post" type="text" style="width: 300px" maxlength="50" size="11" name="bet_meeting" /></td>
</tr>
<tr>
	<td class="row2"><b>{SELECTION}:</b><br /><span class="gensmall">{SELECTION_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" style="width: 300px" maxlength="150" size="11" name="bet_selection" /></td>
</tr>
<tr>
	<td class="row1"><b>{STAKE}:</b><br /><span class="gensmall">{STAKE_EXPLAIN}</span></td>
	<td class="row1"><input class="post" type="text" style="width: 50px" maxlength="6" size="11" name="bet_stake" /> <b>{POINTS_NAME}</b> ({ON_HAND})</td>
</tr>
<!-- BEGIN switch_eachway_allowed -->
<tr> 
  	<td class="row2"><b>{EACHWAY_BET}:</b><br /><span class="gensmall">{EACHWAY_BET_EXP}</span></td>
	<td class="row2"><input type="radio" name="eachwaybet" value="1" /> {L_YES}&nbsp;&nbsp;<input type="radio" name="eachwaybet" value="0" checked="checked" /> {L_NO}</td>
</tr>
<!-- END switch_eachway_allowed -->
<tr>
	<td class="catBottom" align="center" colspan="2"><input type="submit" name="user_placebet" value="{BOOKIES_PLACE_BET}" class="mainoption" /></td>
</tr>
</form></table>
<br />
<!-- END switch_user_bets_on -->

<table width="100%" cellpadding="4" cellspacing="1" class="forumline">
<!-- BEGIN switch_yes_bets -->
<tr>
	<th class="thHead" colspan="5">{BET_HEADER}</th>
</tr>
<tr>
	<td class="row1" align="center" colspan="5" height="30"><span class="gen">{BET_INSTR}{USER_BET_INSTR}<br />{POINTS_INFO}</span></td>
</tr>
<!-- END switch_yes_bets -->
<!-- BEGIN switch_no_bets -->
<tr>
	<th class="thHead" colspan="">{BET_HEADER_NONE_TITLE}</th>
</tr>
<tr>
	<td class="row1" align="center" height="30"><span class="gen">{BET_HEADER_NONE} {USER_BET_INSTR_DEFAULT}</span></td>
</tr>
<!-- END switch_no_bets -->
<!-- BEGIN switch_yes_bets -->
<tr>
	<form action="" method="post">
	<th class="thCornerL" nowrap="nowrap">{BOOKIES_SLIP_MEETING}</th>
	<th class="thTop" nowrap="nowrap">{BOOKIES_SLIP_DATE}</th>
	<th class="thTop" nowrap="nowrap">{BOOKIES_SLIP_SELECTION}</th>
	<th class="thTop" nowrap="nowrap">{BOOKIES_SLIP_STAKE}</th>
	<!-- BEGIN switch_each_way -->
	<th class="thCornerR" nowrap="nowrap">{BOOKIES_SLIP_EACH_WAY}</th>
	<!-- END switch_each_way -->	
</tr>
<!-- END switch_yes_bets -->
<!-- BEGIN cats -->
<tr>
	<td class="catLeft" colspan="5" align="center"><span class="name"><a name="{cats.ANCHOR}"></a></span><span class="cattitle">{cats.CAT}</span></td>
</tr>
<!-- BEGIN bets -->
<tr> 
	<td class="{cats.bets.ROW_CLASS}" align="center"><table>
	<tr>
		<td align="center">{cats.bets.STAR}</td>
		<td align="left"><b>{cats.bets.MEETING}</b></td>
	<tr>
	</table></td>
	<td class="{cats.bets.ROW_CLASS}" align="center"><b>{cats.bets.DATE}</b></td>
	<td class="{cats.bets.ROW_CLASS}" align="center"><select name="{cats.bets.SELECT_NAME}">{cats.bets.SELECTION}</td>
	<td class="{cats.bets.ROW_CLASS}" align="center"><table>
	<tr>
		<td align="center">{cats.bets.STAKE_BOX}</td>
		<td align="center"><b>{POINTS_NAME}</b></td>
	</tr>
	</table></td>
	<!-- BEGIN switch_this_each_way -->
	<td class="{cats.bets.ROW_CLASS}" align="center">{cats.bets.EACH_WAY_CHECKBOX}</td>
	<!-- END switch_this_each_way -->
</tr>
<!-- END bets -->
<!-- END cats -->
<!-- BEGIN switch_yes_bets -->
<tr>
	<td class="catbottom" align="center" colspan="5">
	<!-- BEGIN catview -->
	<input type="submit" name="placebet" value="{BOOKIES_PLACE_BET}" class="mainoption" />
	<!-- END catview -->
	</td>
	</form>
</tr>
<!-- END switch_yes_bets -->
</table>
<br />
<div align="center" class="copyright">Forum Bookmakers {BOOKIE_VERSION} &copy; 2004, {COPYRIGHT_YEAR} Majorflam</div>
