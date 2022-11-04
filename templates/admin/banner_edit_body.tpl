{BANNER_MENU}{INLINE_AD_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_BANNER_TITLE}</h1>

<p>{L_BANNER_TEXT}</p>

{ERROR_BOX}

<table align="center" width="100%" class="forumline" cellpadding="4" cellspacing="1" align="center"><form method="post" name="post" action="{S_BANNER_ACTION}">
<tr>
	<th class="thHead" colspan="2">{L_BANNER_TITLE}</th>
</tr>
<tr>
	<td width="50%" class="row1"><b>{L_BANNER_ACTIVATE}:</b></td>
	<td class="row2"><input type="radio" name="banner_active" value="1" {BANNER_ACTIVE} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="banner_active" value="0" {BANNER_NOT_ACTIVE} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_BANNER_TYPE}:</b><br /><span class="gensmall">{L_BANNER_TYPE_EXPLAIN}</span></td>
	<td class="row2">{BANNER_TYPE}</td>
</tr>
<tr>
	<td class="row1"><b>{L_BANNER_NAME}:</b><br /><span class="gensmall">{L_BANNER_NAME_EXPLAIN}</span></td>
	<td class="row2"><textarea name="banner_name" rows="5" cols="35" class="post">{BANNER_NAME}</textarea></td>
</tr>
<tr>
	<td class="row1"><b>{L_BANNER_DESCRIPTION}:</b><br /><span class="gensmall">{L_BANNER_DESCRIPTION_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" name="banner_description" size="35" maxlength="255" value="{BANNER_DESCRIPTION}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_BANNER_EXAMPLE}:</b><br /><span class="gensmall">{L_BANNER_EXAMPLE_EXPLAIN}</span></td>
	<td class="row2">{BANNER_EXAMPLE}</td>
</tr>
<tr>
	<td class="row1"><b>{L_BANNER_URL}:</b><br /><span class="gensmall">{L_BANNER_URL_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" name="banner_url" size="35" maxlength="255" value="{BANNER_URL}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_BANNER_SIZE}:</b><br /><span class="gensmall">{L_BANNER_SIZE_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" name="banner_height" size="4" maxlength="4" value="{BANNER_HEIGHT}" /> x <input class="post" type="text" name="banner_width" size="4" maxlength="4" value="{BANNER_WIDTH}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_BANNER_CLICK}:</b><br /><span class="gensmall">{L_BANNER_CLICK_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" name="banner_click" size="8" maxlength="8" value="{BANNER_CLICK}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_BANNER_VIEW}:</b></td>
	<td class="row2"><input class="post" type="text" name="banner_view" size="8" maxlength="8" value="{BANNER_VIEW}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_BANNER_OWNER}:</b><br /><span class="gensmall">{L_BANNER_OWNER_EXPLAIN}</span></td>
	<td class="row2"><input type="text" class="post" name="username" maxlength="50" size="20" value="{BANNER_OWNER}"/> <input type="submit" name="usersubmit" value="{L_FIND_USERNAME}" class="liteoption" onClick="window.open('{U_SEARCH_USER}', '_phpbbsearch', 'HEIGHT=250,resizable=yes,WIDTH=400');return false;" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_BANNER_SPOT}:</b><br /><span class="gensmall">{L_BANNER_SPOT_EXPLAIN}</span></td>
	<td class="row2"><select name="banner_spot">{S_BANNER_SPOT}</select><br />{S_BANNER_FORUM}</td>
</tr>
<tr>
	<td class="row1"><b>{L_BANNER_SHOW_TO}:</b><br /><span class="gensmall">{L_BANNER_SHOW_TO_EXPLAIN}</span></td>
	<td class="row2">{S_BANNER_SHOW_TO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_BANNER_WEIGTH}:</b><br /><span class="gensmall">{L_BANNER_WEIGTH_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" name="banner_weigth" size="2" maxlength="2" value="{BANNER_WEIGTH}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_BANNER_FILTER}:</b><br /><span class="gensmall">{L_BANNER_FILTER_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="banner_filter" value="1" {BANNER_FILTER_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="banner_filter" value="0" {BANNER_FILTER_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_BANNER_FILTER_TIME}:</b><br /><span class="gensmall">{L_BANNER_FILTER_TIME_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" name="banner_filter_time" size="6" maxlength="6" value="{BANNER_FILTER_TIME}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_TIME_TYPE}:</b><br /><span class="gensmall">{L_TIME_TYPE_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="time_type" value="0" {C_NO_TIME}/> {L_TIME_NO}&nbsp;&nbsp;<input type="radio" name="time_type" value="2"{C_BY_TIME}/> {L_TIME_TIME}<br /><input type="radio" name="time_type" value="4"{C_BY_WEEK}/> {L_TIME_WEEK}&nbsp;&nbsp;<input type="radio" name="time_type" value="6"{C_BY_DATE}/> {L_TIME_DATE}</td> 
</tr>
<tr> 
	<td class="row1"><b>{L_TIME_INTERVAL}:</b><br /><span class="gensmall">{L_TIME_INTERVAL_EXPLAIN}</span></td>
	<td class="row2"><table>	
	<tr>
		<td align="right"><br/><b>{L_START}:&nbsp; <br />{L_END}:&nbsp; </b></td>
		<td>{L_YEAR}<br /><select name="date_begin_year">{S_YEAR_BEGIN}</select><br /><select name="date_end_year">{S_YEAR_END}</select></td>
		<td>{L_MONTH}<br /><select name="date_begin_month">{S_MONTHS_BEGIN}</select><br /><select name="date_end_month">{S_MONTHS_END}</select></td>
		<td>{L_DATE}<br /><select name="date_begin_day">{S_DATE_BEGIN}</select><br /><select name="date_end_day">{S_DATE_END}</select></td>
		<td>{L_WEEKDAY}<br /><select name="date_begin_week">{S_WEEK_BEGIN}</select><br /><select name="date_end_week">{S_WEEK_END}</select></td>
		<td>{L_HOURS}<br /><select name="time_begin_hour">{S_HOURS_BEGIN}</select><br /><select name="time_end_hour">{S_HOURS_END}</select></td>
		<td>{L_MIN}<br /><select name="time_begin_min">{S_MIN_BEGIN}</select><br /><select name="time_end_min">{S_MIN_END}</select></td>
	</tr>
	</table></td>
</tr>
<tr>
	<td class="row1"><b>{L_BANNER_COMMENT}:</b><br /><span class="gensmall">{L_BANNER_COMMENT_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" name="banner_comment" size="35" maxlength="100" value="{BANNER_COMMENT}" /></td>
</tr>
<tr>
	<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</form></table>
<br />
<div align="center" class="copyright">Complete Banner 1.3.6 &copy; 2003, {COPYRIGHT_YEAR} Niels</div>

