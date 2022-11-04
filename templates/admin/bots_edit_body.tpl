{BOT_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_PAGE_TITLE}</h1>

<p>{L_PAGE_EXPLAIN}</p>

<table width="100%" class="forumline" cellspacing="1" cellpadding="4" align="center"><form method="post" action="{S_ACTION}">
<tr>
	<th class="thHead" colspan="2">{L_PAGE_TITLE}</th>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_BOT_NAME}:</b><br /><span class="gensmall">{L_BOT_NAME_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" name="bot_name" value="{BOT_NAME}" maxlength="255" size="35" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_BOT_STYLE}:</b><br /><span class="gensmall">{L_BOT_STYLE_EXPLAIN}</span></td>
	<td class="row2">{BOT_STYLE}</td>
</tr>
<tr>
	<td class="row1"><b>{L_BOT_AGENT}:</b><br /><span class="gensmall">{L_BOT_AGENT_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" name="bot_agent" maxlength="255" size="35" value="{BOT_AGENT}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_BOT_IP}:</b><br /><span class="gensmall">{L_BOT_IP_EXPLAIN}</span></td>
	<td class="row2"><textarea class="post" name="bot_ip" rows="10" cols="35">{BOT_IP}</textarea></td>
</tr>
<tr>
	<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input class="mainoption" type="submit" value="{L_SUBMIT}" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</form></table>
