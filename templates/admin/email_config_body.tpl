{USERCOM_MENU}{EMAIL_MENU}{DIGESTS_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_PAGE_TITLE}</h1>

<p>{L_PAGE_EXPLAIN} {L_EMAIL_SETTINGS_EXPLAIN}</p>

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
	<td class="row1" width="50%"><b>{L_BOARD_EMAIL_FORM}:</b><br /><span class="gensmall">{L_BOARD_EMAIL_FORM_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="board_email_form" value="1" {BOARD_EMAIL_FORM_ENABLE} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="board_email_form" value="0" {BOARD_EMAIL_FORM_DISABLE} /> {L_NO}</td>
</tr>
<tr> 
   	<td class="row1"><b>{L_DEBUG_EMAIL}:</b><br /><span class="gensmall">{L_DEBUG_EMAIL_EXPLAIN}</span></td> 
   	<td class="row2"><input type="radio" name="debug_email" value="1" {S_DEBUG_EMAIL_ENABLED} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="debug_email" value="0" {S_DEBUG_EMAIL_DISABLED} /> {L_NO}</td> 
</tr> 
<tr>
	<td class="row1"><b>{L_REGISTRATION_NOTIFY}:</b><br /><span class="gensmall">{L_REGISTRATION_NOTIFY_EXPLAIN}</span></td>
	<td class="row2">&nbsp;<input type="radio" name="registration_notify" value="{REGISTRATION_NOTIFY_NONE}" {REGISTRATION_NOTIFY_NONE_CHECKED} /> {L_DISABLED}<br />&nbsp;<input type="radio" name="registration_notify" value="{REGISTRATION_NOTIFY_MOD}" {REGISTRATION_NOTIFY_MOD_CHECKED} /> {L_MOD}&nbsp;&nbsp;<input type="radio" name="registration_notify" value="{REGISTRATION_NOTIFY_LESS_ADMIN}" {REGISTRATION_NOTIFY_LESS_ADMIN_CHECKED} /> {L_SUPERMOD}&nbsp;&nbsp;<input type="radio" name="registration_notify" value="{REGISTRATION_NOTIFY_ADMIN}" {REGISTRATION_NOTIFY_ADMIN_CHECKED} /> {L_ADMIN}</td>
</tr>
<tr> 
   	<td class="row1"><b>{L_PRUNE_EMAIL}:</b><br /><span class="gensmall">{L_PRUNE_EMAIL_EXPLAIN}</span></td> 
   	<td class="row2"><input type="radio" name="user_prune_notify" value="1" {PRUNE_EMAIL_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="user_prune_notify" value="0" {PRUNE_EMAIL_NO} /> {L_NO}</td> 
</tr> 
<tr>
	<td class="row1"><b>{L_ADMIN_EMAIL}:</b><br /><span class="gensmall">{L_ADMIN_EMAIL_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="25" maxlength="100" name="board_email" value="{EMAIL_FROM}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_EMAIL_SIG}:</b><br /><span class="gensmall">{L_EMAIL_SIG_EXPLAIN}</span></td>
	<td class="row2"><textarea wrap="virtual" class="post" name="board_email_sig" rows="5" cols="35">{EMAIL_SIG}</textarea></td>
</tr>
<tr>
	<th class="thHead" colspan="2">{L_SMTP_SETTINGS}</th>
</tr>
<tr>
	<td class="row1"><b>{L_USE_SMTP}:</b><br /><span class="gensmall">{L_USE_SMTP_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="smtp_delivery" value="1" {SMTP_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="smtp_delivery" value="0" {SMTP_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_SMTP_SERVER}:</b></td>
	<td class="row2"><input class="post" type="text" name="smtp_host" value="{SMTP_HOST}" size="25" maxlength="50" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_SMTP_USERNAME}:</b><br /><span class="gensmall">{L_SMTP_USERNAME_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" name="smtp_username" value="{SMTP_USERNAME}" size="25" maxlength="255" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_SMTP_PASSWORD}:</b><br /><span class="gensmall">{L_SMTP_PASSWORD_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="password" name="smtp_password" value="{SMTP_PASSWORD}" size="25" maxlength="255" /></td>
</tr>
<tr>
	<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</form></table>
