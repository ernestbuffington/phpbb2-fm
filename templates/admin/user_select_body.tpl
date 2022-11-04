{USER_MENU}{CUSTOM_PROFILE_MENU}{PERMS_MENU}{BAN_MENU}
{MOD_CP_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_USER_TITLE}</h1>

<p>{L_USER_EXPLAIN}</p>

<p>{L_USER_LOOKUP_EXPLAIN}</p>

<table width="100%" cellspacing="1" cellpadding="4" align="center" class="forumline"><form method="post" name="post" action="{S_USER_ACTION}">
<tr>
	<th class="thHead" colspan="2">{L_LOOK_UP}</th>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_USERNAME}:</b></td>
	<td class="row2"><input type="text" class="post" name="username"{AJAXED_USER_LIST} maxlength="99" size="20" value="{FORCEEMAIL_USERNAME}" />{AJAXED_USER_LIST_BOX}</td>
</tr>
<tr>
	<td class="row1"><b>{L_EMAIL_ADDRESS}:</b></td>
	<td class="row2"><input type="text" class="post" name="email" maxlength="255" size="30" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_POSTS}:</b></td>
	<td class="row2"><input type="text" class="post" name="posts" maxlength="12" size="10" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_JOINED}:</b><br /><span class="gensmall">{L_JOINED_EXPLAIN}</span></td>
	<td class="row2"><input type="text" class="post" name="joined" maxlength="50" size="30" /></td>
</tr>
<tr>
	<td class="catBottom" align="center" colspan="2"><table width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td align="right" width="50%"><input type="hidden" name="mode" value="lookup" />{S_HIDDEN_FIELDS}<input type="submit" value="{L_LOOK_UP}" class="mainoption" />&nbsp;</td>
		</form>
		<form action="{S_PROFILE_ACTION}" {S_FORM_ENCTYPE} method="post">
		<td width="50%">&nbsp;<input type="submit" value="{L_CREATE_USER}" class="mainoption" /></td>
	</tr>
	</table></td>
</tr>
</form></table>
<br />
<div align="center" class="copyright">Enhanced Admin User Lookup 1.1.2 &copy; 2003, {COPYRIGHT_YEAR} <a href="http://matthijs.net" target="_blank" class="copyright">Matthijs</a></div>
