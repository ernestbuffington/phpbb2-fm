{DIGESTS_MENU}{EMAIL_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_USER_TITLE}</h1>

<p>{L_USER_EXPLAIN}</p>

<p>{L_USER_LOOKUP_EXPLAIN}</p>

<table width="50%" cellspacing="1" cellpadding="4" align="center" class="forumline"><form method="post" name="post" action="{S_USER_ACTION}">
<tr>
	<th class="thHead" colspan="2">{L_USER_TITLE}</th>
</tr>
<tr>
	<td class="row1" width="38%"><b>{L_USERNAME}:</b></td>
	<td class="row2"><input type="text" class="post" name="username"{AJAXED_USER_LIST} maxlength="50" size="35" />{AJAXED_USER_LIST_BOX}</td>
</tr>
<tr>
	<td class="catBottom" align="center" colspan="2"><input type="hidden" name="mode" value="lookup" />{S_HIDDEN_FIELDS}<input type="submit" value="{L_SUBMIT}" class="mainoption" /></td>
</tr>
</form></table>
