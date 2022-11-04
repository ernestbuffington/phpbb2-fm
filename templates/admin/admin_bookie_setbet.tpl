{BOOKIE_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{SET_HEADER}</h1>

<p>{SET_HEADER_EXPLAIN}</p>

<p>{DEF_DATE_EXPLAIN}</p>

<table width="100%" align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>{IMG_NEW_MEETING}</td>
</tr>
</table>
<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline"><form action="" method="post">
<tr>
	<th class="thHead" colspan="2">{DEFAULT_DATE}</th>
</tr>
<tr>
	<td class="row1" width="50%"><b>{DEFAULT_VARS}:</b><br /><span class="gensmall">{DEFAULT_DATE_EXPLAIN}</span></td>
	<td class="row2"><select name="def_day">{DAY_BOX} -
		<select name="def_month">{MONTH_BOX} -
		<select name="def_year">{YEAR_BOX} @
		<select name="def_hour">{BETHOUR_BOX} :
		<select name="def_minute">{BETMINUTE_BOX}
	</td>							
</tr>
<tr>
	<td class="row1"><b>{DEFAULT_CAT}:</b><br /><span class="gensmall">{DEFAULT_CAT_EXPLAIN}</span></td>
	<td class="row2"><select name="def_cat">{CATEGORY_BOX}</td>
</tr>
<tr>
	<td class="catBottom" align="center" colspan="2"><input type="submit" name="update_def_date" value="{UPDATE}" class="mainoption" /></td>
</tr>
</form></table>
<br />

<!-- BEGIN switch_no_bets -->
<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline">
<tr>
	<th class="thHead">{INFORMATION}</th>
</tr>	
<tr>
	<td class="row1" align="center" height="30"><span class="gen">{NO_BETS}</span></td>
</tr>
</table>
<br />
<!-- END switch_no_bets -->

<!-- BEGIN switch_bets_set -->
<h1>{CURRENT_BETS}</h1>

<p>{CURRENT_BETS_EXPLAIN}</p>

<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline">
<tr>
	<th class="thCornerL" nowrap="nowrap">{CATEGORY}</th>
	<th class="thTop" nowrap="nowrap" colspan="3">{TIME} - {MEETING} -> <i>{SELECTION}</i></th>
	<th class="thTop" nowrap="nowrap">{ODDS}</th>	
	<th class="thCornerR" nowrap="nowrap" width="15%">{EDIT_DELETE}</th>
</tr>
<!-- END switch_bets_set -->
<!-- BEGIN processbet -->
<tr>
	<td class="catLeft"><a href="{processbet.EXPAND_URL}" class="cattitle">{processbet.CATEGORY}</a></td>
	<td class="cat" colspan="3"><span class="name"><a name="{processbet.ANCHOR}"></a></span>
	<table width="100%">
	<tr>
		<td align="center">{processbet.STAR_IMAGE}</td>
		<td><a href="{processbet.EXPAND_URL}" class="cattitle">{processbet.TIME} - {processbet.MEETING}</a></td>
	</tr>
	</table>
	</td>
	<td class="catRight" align="center" colspan="2">{processbet.ADD_IMAGE} {processbet.DROP_IMAGE}</td>
</tr>
<!-- BEGIN expansion -->
<tr> 
	<td class="{processbet.expansion.ROW_CLASS}"><b>{processbet.expansion.CATEGORY}</b></td>
	<td class="{processbet.expansion.ROW_CLASS}" align="center" colspan="3">{processbet.expansion.SELECTION}</td>
	<td class="{processbet.expansion.ROW_CLASS}" align="center">{processbet.expansion.ODDS}</td>
	<td class="{processbet.expansion.ROW_CLASS}" align="right" nowrap="nowrap">{processbet.expansion.EDIT} {processbet.expansion.DELETE}</td>
</tr>
<!-- END expansion -->
<!-- END processbet -->
<!-- BEGIN switch_bets_set -->
</table>
<!-- END switch_bets_set -->
<br />
<div align="center" class="copyright">Forum Bookmakers {BOOKIE_VERSION} &copy 2004, {COPYRIGHT_YEAR} <a href="http://www.majormod.com" class="copyright" target="_blank">Majorflam</a></div>

