{BAN_MENU}
{USER_MENU}
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
	<td class="row1" width="50%"><b>{L_ENABLE_BANCARDS}:</b></td>
	<td class="row2"><input type="radio" name="enable_bancards" value="1" {ENABLE_BANCARDS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="enable_bancards" value="0" {ENABLE_BANCARDS_NO} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_MAX_USER_BANCARD}:</b><br /><span class="gensmall">{L_MAX_USER_BANCARD_EXPLAIN}</span></td> 
	<td class="row2"><input class="post" type="text" size="4" maxlength="4" name="max_user_bancard" value="{MAX_USER_BANCARD}" /></td> 
</tr> 
<tr> 
	<td class="row1"><b>{L_MAX_USER_VOTEBANCARD}:</b><br /><span class="gensmall">{L_MAX_USER_VOTEBANCARD_EXPLAIN}</span></td> 
	<td class="row2"><input class="post" type="text" size="4" maxlength="4" name="max_user_votebancard" value="{MAX_USER_VOTEBANCARD}" /></td> 
</tr> 
<tr> 
	<td class="row1"><b>{L_BLUECARD_LIMIT_2}:</b><br /><span class="gensmall">{L_BLUECARD_LIMIT_2_EXPLAIN}</span></td> 
	<td class="row2"><input class="post" type="text" size="4" maxlength="4" name="bluecard_limit_2" value="{BLUECARD_LIMIT_2}" /></td> 
</tr> 
<tr> 
	<td class="row1"><b>{L_BLUECARD_LIMIT}:</b><br /><span class="gensmall">{L_BLUECARD_LIMIT_EXPLAIN}</span></td> 
	<td class="row2"><input class="post" type="text" size="4" maxlength="4" name="bluecard_limit" value="{BLUECARD_LIMIT}" /></td> 
</tr> 
<tr> 
	<td class="row1"><b>{L_REPORT_FORUM}:</b><br /><span class="gensmall">{L_REPORT_FORUM_EXPLAIN}</span></td> 
	<td class="row2">{REPORT_FORUM_SELECT}</td> 
</tr>
<tr>
	<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</form></table>
<br />
<div align="center" class="copyright">Yellow Card Admin 1.3.9 &amp; Black Card 1.1.0 &copy; 2002, {COPYRIGHT_YEAR} Niels</div>
