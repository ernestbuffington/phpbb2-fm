{USERCOM_MENU}{EMAIL_MENU}{DIGESTS_MENU}
{MOD_CP_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_EMAIL_TITLE}</h1>

<p>{L_EMAIL_EXPLAIN}</p>

{ERROR_BOX}

<table width="100%" cellspacing="1" cellpadding="4" align="center" class="forumline"><form method="post" action="{S_USER_ACTION}">
<tr> 
	<th class="thHead" colspan="2">{L_COMPOSE}</th>
</tr>
<tr> 
	<td class="row1" width="38%"><b>{L_RECIPIENTS}:</b></td>
	<td class="row2">{S_GROUP_SELECT}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_EMAIL_SUBJECT}: *</b></td>
	<td class="row2"><input class="post" type="text" name="subject" size="35" maxlength="100" tabindex="2" class="post" value="{SUBJECT}" /></td>
</tr>
<tr> 
	<td class="row1"><b>{L_EMAIL_MSG}: *</b></td> 
	<td class="row2"><textarea name="message" rows="15" cols="35" wrap="virtual" style="width:450px" tabindex="3" class="post">{MESSAGE}</textarea></td> 
</tr>
<tr> 
	<td class="catBottom" align="center" colspan="2"><input type="submit" value="{L_EMAIL}" name="submit" class="mainoption" /></td>
</tr>
</form></table>
