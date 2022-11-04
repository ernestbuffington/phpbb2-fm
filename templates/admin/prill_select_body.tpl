{USERCOM_MENU}{PRILL_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_PRILLIAN} {L_USER_TITLE}</h1>

<p>{L_USER_EXPLAIN}</p>

<p>{L_USER_LOOKUP_EXPLAIN}</p>

<table cellspacing="1" cellpadding="4" align="center" class="forumline"><form method="post" name="post" action="{S_USER_ACTION}">
<tr>
	<th class="thHead" colspan="2">{L_LOOK_UP}</th>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_USERNAME}:</b></td>
	<td class="row2" align="left"><input type="text" class="post" name="username"{AJAXED_USER_LIST} maxlength="50" size="20" />{AJAXED_USER_LIST_BOX}</td>
</tr>
<tr>
	<td class="row1"><b>{L_EMAIL_ADDRESS}:</b></td>
	<td class="row2" align="left"><input type="text" class="post" name="email" maxlength="50" size="30" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_POSTS}:</b></td>
	<td class="row2" align="left"><input type="text" class="post" name="posts" maxlength="12" size="12" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_JOINED}:</b><br /><span class="gensmall">{L_JOINED_EXPLAIN}</span></td>
	<td class="row2" align="left"><input type="text" class="post" name="joined" maxlength="50" size="30" /></td>
</tr>
<tr>
	<td class="catBottom" align="center" colspan="2"><input type="hidden" name="mode" value="lookup" />{S_HIDDEN_FIELDS}<input type="submit" value="{L_LOOK_UP}" class="mainoption" /></td>
</tr>
</form></table>
<br />
<div align="center" class="copyright">Prillian 0.7.0 Beta &copy; 2003, {COPYRIGHT_YEAR} <a href="http://darkmods.sourceforge.net" class="copyright" target="_blank">Thoul</a></div>
