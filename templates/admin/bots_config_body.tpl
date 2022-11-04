{BOT_MENU}
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
<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline"><form action="{S_CONFIG_ACTION}" method="post">
<tr>
	<th class="thHead" colspan="2">{L_PAGE_TITLE}</th>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_BOT_WHOSONLINE}:</b><br /><span class="gensmall">{L_BOT_WHOSONLINE_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="enable_bots_whosonline" value="1" {BOT_WHOSONLINE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="enable_bots_whosonline" value="0" {BOT_WHOSONLINE_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_BOT_TRACKING}:</b></td>
	<td class="row2"><input type="radio" name="enable_bot_tracking" value="1" {BOT_TRACKING_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="enable_bot_tracking" value="0" {BOT_TRACKING_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1""><b>{L_BOT_EMAIL}:</b><br /><span class="gensmall">{L_BOT_EMAIL_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="enable_bot_email" value="1" {BOT_EMAIL_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="enable_bot_email" value="0" {BOT_EMAIL_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</form></table>
