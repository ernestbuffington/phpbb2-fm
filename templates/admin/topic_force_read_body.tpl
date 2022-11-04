{TOPIC_MENU}{FORUM_MENU}{VOTE_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_FTR_SETTINGS}</h1>

<p>{L_FTR_SETTINGS_EXPLAIN}</p>

<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline"><form action="{S_CONFIG_ACTION}" method="post">
<tr>
	<th class="thHead" colspan="2">{L_FTR_SETTINGS}</th>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_BOARD_FTR}:</b></td>
	<td class="row2"><input type="radio" name="ftr_active" value="1" {BOARD_FTR_ENABLE} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="ftr_active" value="0" {BOARD_FTR_DISABLE} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_WHO}:</b><br /><span class="gensmall">{L_WHO_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="ftr_who" value="1" {WHO_NO} /> {L_WHO_NEW}&nbsp;&nbsp;<input type="radio" name="ftr_who" value="2" {WHO_YES} /> {L_WHO_BOTH}</td>
</tr>
<tr>
	<td class="row1"><b>{L_FTR_RESET}:</b><br /><span class="gensmall">{L_FTR_RESET_EXPLAIN}</span></td>
	<td class="row2"><a href="{U_FTR_RESET}">{L_YES}</a></td>
</tr>
<tr>
	<th class="thHead" colspan="2">{L_FTR_POST_SETTINGS}</th>
</tr>
<tr>
	<td class="row1">{L_FTR_FORUM}</td>
	<td class="row2" rowspan="2"><a href="{U_CHANGE_POST}">{L_CHANGE}</a></td>
</tr>
<tr>
	<td class="row1">{L_FTR_TOPIC}</td>
</tr>
<tr>
	<td class="row1"><b>{L_FTR_MSG}:</b><br /><span class="gensmall">{L_FTR_MSG_EXPLAIN}</span></td>
	<td class="row2"><textarea class="post" name="ftr_msg" rows="5" cols="35">{FTR_MSG}</textarea></td>
</tr>
<tr>
	<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</form></table>
<br />
<div align="center" class="copyright">Force Topic Read 1.0.3 &copy 2003, {COPYRIGHT_YEAR} <a href="http://phpbb-tweaks.com" class="copyright" target="_blank">aUsTiN</a></div>
