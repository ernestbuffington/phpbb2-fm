{BOOKIE_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{HEADER}</h1>

<p>{EXPLAIN}</p>

<table width="100%" cellpadding="4" cellspacing="1" class="forumline"><form action="{URL}" method="post">
<tr>
	<th class="thHead" nowrap="nowrap" colspan="2">{ENTER_DETAILS}</th>
</tr>
<tr>
	<td class="row1" width="50%"><b>{CATEGORY}:</b><br /><span class="gensmall">{CATEGORY_EXPLAIN}</span></td>
	<td class="row2"><select name="selected_category">{CATEGORY_BOX}</td>
</tr>
<tr>
	<td class="row1"><b>{TIME_MEETING}:</b><br /><span class="gensmall">{TIME_MEETING_EXPLAIN}</span></td>
	<td class="row2"><table width="100%" align="center">
	<tr>
		<td align="right"><table>
		<tr>
			<td><select name="bet_day">{DAY_BOX}</td>
			<td><b>&nbsp;-&nbsp;</b></td>
			<td><select name="bet_month">{MONTH_BOX}</td>
			<td><b>&nbsp;-&nbsp;</b></td>
			<td><select name="bet_year">{YEAR_BOX}</td>
		</tr>
		</table></td>
		<td align="center">@</td>
		<td align="left"><table>
		<tr>
			<td><select name="bet_hour">{BETHOUR_BOX}</td>
			<td><b>&nbsp;:&nbsp;</b></td>
			<td><select name="bet_minute">{BETMINUTE_BOX}</td>							
		</tr>
		</table></td>
	</tr>
	</table></td>
</tr>
<tr>
	<td class="row1"><b>{MEETING}:</b><br /><span class="gensmall">{MEETING_EXPLAIN}</span></td>
	<td class="row2"><table>
	<tr>
		<td align="center"><input class="post" type="text" style="width: 300px" maxlength="50" size="11" name="bet_meeting" /></td>
	</tr>
	<tr>
		<td align="center"><select name="selected_meeting">{MEETING_BOX}</td>
	</tr>
	</table></td>
</tr>
<tr> 
	<td class="row1"><b>{STAR_BET}:</b><br /><span class="gensmall">{STAR_BET_EXP}</span></td>
	<td class="row2"><input type="radio" name="starbet" value="1" /> {L_YES}&nbsp;&nbsp;<input type="radio" name="starbet" value="0" checked="checked" /> {L_NO}</td>
</tr>
<!-- BEGIN eachway_allowed -->
<tr> 
  	<td class="row1"><b>{EACHWAY_BET}:</b><br /><span class="gensmall">{EACHWAY_BET_EXP}</span></td>
	<td class="row2"><input type="radio" name="eachwaybet" value="1" /> {L_YES}&nbsp;&nbsp;<input type="radio" name="eachwaybet" value="0" checked="checked" /> {L_NO}</td>
</tr>
<!-- END eachway_allowed -->
<tr>
	<td class="catBottom" align="center" colspan="2"><input type="submit" name="submit" value="{SUBMIT}" class="mainoption" /></td>
</tr>
</form></table>
<br />
<div align="center" class="copyright">Forum Bookmakers {BOOKIE_VERSION} &copy 2004, {COPYRIGHT_YEAR} <a href="http://www.majormod.com" class="copyright" target="_blank">Majorflam</a></div>

