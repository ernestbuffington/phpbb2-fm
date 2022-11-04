{BOOKIE_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{EDIT_HEADER}</h1>

<p>{EDIT_HEADER_EXPLAIN}</p>

<table width="100%" cellpadding="4" cellspacing="1" class="forumline" align="center"><form action="{URL}" method="post">
<tr>
	<th colspan="2" class="thHead">&nbsp;{EDIT_HEADER}&nbsp;</th>
</tr>
<!-- BEGIN editbet -->
{editbet.SPLIT_ROW}
<tr>
	<td class="row1" width="50%"><b>{TIME}:</b></td>
	<td class="row2"><select name="bet_day">{editbet.DAY_BOX} - <select name="bet_month">{editbet.MONTH_BOX} - <select name="bet_year">{editbet.YEAR_BOX} @ <select name="bet_hour">{editbet.BETHOUR_BOX} : <select name="bet_minute">{editbet.BETMINUTE_BOX}<br /><span class="gensmall">({editbet.CURRENT_TIME})</span></td>							
</tr>
<tr>
	<td class="row1"><b>{MEETING}:</b></td>
	<td class="row2">{editbet.MEETING}</td>
</tr>
<tr>
	<td class="row1"><b>{SELECTION}:</b></td>
	<td class="row2">{editbet.SELECTION}</td>
</tr>
<tr>
	<td class="row1"><b>{ODDS}:</b></td>	
	<td class="row2">{editbet.ODDS}</td>
</tr>
<tr> 
	<td class="row1"><b>{editbet.CATEGORY}:</b><br /><span class="gensmall">{editbet.CATEGORY_EXPLAIN}</span></td>
	<td class="row2"><select name="edit_category">{editbet.CATEGORY_BOX}</td>
</tr>
<tr> 
	<td class="row1"><b>{editbet.STAR_BET}</b><br /><span class="gensmall">{editbet.STAR_BET_EXP}</span></td>
	<td class="row2"><input type="radio" name="starbet" value="1" {editbet.STAR_BET_ON} /> {editbet.L_YES}&nbsp;&nbsp;<input type="radio" name="starbet" value="0" {editbet.STAR_BET_OFF} /> {editbet.L_NO}</td>
</tr>
<!-- BEGIN eachway_allowed -->
<tr> 
	<td class="row1"><b>{editbet.EACHWAY_BET}:</b><br /><span class="gensmall">{editbet.EACHWAY_BET_EXP}</span></td>
	<td class="row2"><input type="radio" name="eachwaybet" value="1" {editbet.EACHWAY_BET_ON} /> {editbet.L_YES}&nbsp;&nbsp;<input type="radio" name="eachwaybet" value="0" {editbet.EACHWAY_BET_OFF} /> {editbet.L_NO}</td>
</tr>
<!-- END eachway_allowed -->
<!-- END editbet -->
<tr>
	<td class="catBottom" align="center" colspan="4"><input type="submit" name="editbet" value="{SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="submit" name="cancel" value="{CANCEL}" class="liteoption" /></td>
</tr>
</table>
<br />
{SELECTION_REVIEW}
<br />
<div align="center" class="copyright">Forum Bookmakers {BOOKIE_VERSION} &copy 2004, {COPYRIGHT_YEAR} <a href="http://www.majormod.com" class="copyright" target="_blank">Majorflam</a></div>

