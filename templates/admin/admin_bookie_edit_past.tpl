{BOOKIE_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{HEADER}</h1>

<p></p>

<!-- BEGIN select_mode -->
<table width="95%" cellpadding="4" cellspacing="1" align="center" class="forumline"><form action="{URL}" method="post">
<tr>
	<th class="thHead" colspan="2">{SELECT_BY_TIME}</th>
</tr>
<tr>
	<td class="row1" width="50%"><b>{SELECT_BY_TIME}:</b><br /><span class="gensmall">{SELECT_BY_TIME_EXP}</span></td>
	<td class="row2"><select name="bet_day">{DAY_BOX} -
		<select name="bet_month">{MONTH_BOX} -
		<select name="bet_year">{YEAR_BOX} @
		<select name="bet_hour">{BETHOUR_BOX} :
		<select name="bet_minute">{BETMINUTE_BOX}</td>
</tr>
<tr>
	<td class="catBottom" align="center" colspan="2"><input type="submit" name="select_bet" value="{SUBMIT}" class="mainoption" /></td>
</tr>
</form></table>
<!-- END select_mode -->

<!-- BEGIN edit_mode -->
<table width="100%" cellpadding="4" cellspacing="1" align="center" class="forumline">
<tr>
	<th class="thCornerL" nowrap="nowrap" width="20%">{PROCESS_TIME}</th>
	<th class="thTop" nowrap="nowrap" width="20%">{PROCESS_MEETING}</th>
	<th class="thTop" nowrap="nowrap" width="25%">{PROCESS_SELECTION}</th>
	<th class="thTop" nowrap="nowrap" width="20%">{ODDS_ODDS}</th>
	<th class="thTop" nowrap="nowrap" width="10%">{WINNER}</th>
	<th class="thCornerR" nowrap="nowrap" width="5%">{PROCESS_GO}</th>
</tr>
<!-- END edit_mode -->
<!-- BEGIN processbet -->
<tr> 
	<form action="{processbet.URL}" method="post">
	<td class="{processbet.ROW_CLASS}" align="center">{processbet.TIME}</td>
	<td class="{processbet.ROW_CLASS}" align="center">{processbet.MEETING}</td>
	<td class="{processbet.ROW_CLASS}" align="center">{processbet.SELECTION}</td>
	<td class="{processbet.ROW_CLASS}" align="center">{processbet.ODDS}</td>
	<td class="{processbet.ROW_CLASS}" align="center">
	<table>
	<!-- BEGIN normal -->
	<tr>
		<td><input type="radio" name="{processbet.WINNER}" value="yes" {processbet.YES_CHECKED}/> {L_YES}</td>
	</tr>
	<!-- END normal -->
	<!-- BEGIN eachway -->
	<tr>
		<td><input type="radio" name="{processbet.WINNER}" value="eww" {processbet.EWW_CHECKED} /> {L_EWW}</td>
	</tr>
	<tr>
		<td><input type="radio" name="{processbet.WINNER}" value="ewp" {processbet.EWP_CHECKED} /> {L_EWP}</td>
	</tr>
	<!-- END eachway -->
	<tr>
		<td><input type="radio" name="{processbet.WINNER}" value="no" {processbet.NO_CHECKED} /> {L_NO}</td>
	</tr>
	<tr>
		<td><input type="radio" name="{processbet.WINNER}" value="ref" {processbet.REF_CHECKED} /> {L_REF}</td>
	</tr>
	</table>
	</td>
	<td class="{processbet.ROW_CLASS}" align="center"><input type="submit" name="{processbet.GO}" value="{PROCESS_GO}" class="mainoption" /></td>
	</form>
</tr>
<!-- END processbet -->
<!-- BEGIN edit_mode -->
</table>
<!-- END edit_mode -->
<br />
<div align="center" class="copyright">Forum Bookmakers {BOOKIE_VERSION} &copy 2004, {COPYRIGHT_YEAR} <a href="http://www.majormod.com" class="copyright" target="_blank">Majorflam</a></div>
