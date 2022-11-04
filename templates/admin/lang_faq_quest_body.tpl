{LANG_MENU}{UTILS_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_TITLE}</h1>

<p>{L_EXPLAIN}</p>

<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline"><form action="{S_ACTION}" method="post">
<tr> 
	<th class="thHead" colspan="2">{L_TITLE}</th>
</tr>
<tr> 
	<td class="row1" width="38%"><b>{L_BLOCK}:</b></td>
	<td class="row2"><select name="block">{S_BLOCK_LIST}</select></td>
</tr>
<tr> 
	<td class="row1"><b>{L_QUESTION}:</b></td>
	<td class="row2"><input type="text" size="35" name="quest_title" value="{QUESTION}" class="post" /></td>
</tr>
<tr> 
	<td class="row1"><b>{L_ANSWER}:</b></td>
	<td class="row2"><textarea name="answer" class="post" cols="50" rows="15" />{ANSWER}</textarea></td>
</tr>
<tr> 
	<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</form></table>