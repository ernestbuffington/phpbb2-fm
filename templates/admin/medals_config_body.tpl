{MEDALS_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_PAGE_TITLE}</h1>

<p>{L_PAGE_EXPLAIN}</p>

<table width="100%" align="center"><form action="{S_CONFIG_ACTION}" method="post">
<tr>
	<td align="right">{CONFIG_SELECT} <input type="submit" name="config" value="{L_GO}" class="liteoption" /></td>
</tr>
</form></table>
<table width="100%" cellpadding="4" cellspacing="1" align="center" class="forumline"><form action="{S_CONFIG_ACTION}" method="post">
<tr>
	<th class="thHead" colspan="2">{L_PAGE_TITLE}</th>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_ALLOW_MEDAL2}:</b></td>
	<td class="row2"><input type="radio" name="allow_medal_display_viewprofile" value="1" {MEDAL2_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_medal_display_viewprofile" value="0" {MEDAL2_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"<b>{L_ALLOW_MEDAL}:</b></td>
	<td class="row2"><input type="radio" name="allow_medal_display_viewtopic" value="1" {MEDAL_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_medal_display_viewtopic" value="0" {MEDAL_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_MEDAL_RAND}:</b><br /><span class="gensmall">{L_MEDAL_RAND_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="medal_display_order" value="1" {RAND_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="medal_display_order" value="0" {RAND_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_MEDAL_DISPLAY}:</b><br /><span class="gensmall">{L_MEDAL_DISPLAY_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="5" maxlength="4" name="medal_display_row" value="{MEDAL_DISPALY_ROW}" /> x <input class="post" type="text" size="5" maxlength="4" name="medal_display_col" value="{MEDAL_DISPALY_COL}"></td>
</tr>
<tr>
	<td class="row1"><b>{L_MEDAL_SIZE}:</b><br /><span class="gensmall">{L_MEDAL_SIZE_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="5" maxlength="4" name="medal_display_height" value="{MEDAL_DISPALY_H}" /> x <input class="post" type="text" size="5" maxlength="4" name="medal_display_width" value="{MEDAL_DISPALY_W}"></td>
</tr>
<tr>
	<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</form></table>
<br />
<div align="center" class="copyright">Medal System 0.4.6 &copy; 2003, {COPYRIGHT_YEAR} <a href="http://macphpbbmod.sourceforge.net" target="_blank" class="copyright">ycl6</a></div>
