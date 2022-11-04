{CONFIG_MENU}{USERCOM_MENU}{SERVER_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_CONFIGURATION_TITLE}</h1>

<p>{L_CONFIGURATION_EXPLAIN}</p>

<table width="100%" cellpadding="4" cellspacing="1" align="center" class="forumline"><form action="{S_CONFIG_ACTION}" method="post">
<tr>
	<th class="thHead" colspan="2">{L_CONFIGURATION_TITLE}</th>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_ENABLE}:</b></td>
	<td class="row2"><input type="radio" name="enable_guestbook" value="1" {ENABLE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="enable_guestbook" value="0" {ENABLE_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_PASSWORD}:</b></td>
	<td class="row2"><input class="post" type="text" size="5" maxlength="10" name="password" value="{PASSWORD}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_SESSION_POSTING}:</b><br /><span class="gensmall">{L_SESSION_POSTING_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="5" maxlength="10" name="session_posting" value="{SESSION_POSTING}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_PERMIT_MOD}:</b></td>
	<td class="row2"><input type="radio" name="permit_mod" value="1" {PERMIT_MOD_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="permit_mod" value="0" {PERMIT_MOD_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_MAXLENGHT}:</b></td>
	<td class="row2"><input class="post" type="text" size="5" maxlength="4" name="maxlenght_posts" value="{MAXLENGHT}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_WORD_WRAP}:</b></td>
	<td class="row2"><input type="radio" name="word_wrap" value="1" {WORD_WRAP_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="word_wrap" value="0" {WORD_WRAP_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_WORD_WRAP_LENGTH}:</b></td>
	<td class="row2"><input class="post" type="text" size="5" maxlength="10" name="word_wrap_length" value="{WORD_WRAP_LENGTH}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_HIDE_POSTS}:</b></td>
	<td class="row2"><input type="radio" name="hide_posts" value="1" {HIDE_POSTS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="hide_posts" value="0" {HIDE_POSTS_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_NO_SMILIES}:</b></td>
	<td class="row2"><input type="radio" name="no_only_smilies" value="1" {NO_SMILIES_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="no_only_smilies" value="0" {NO_SMILIES_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_NO_QUOTE}:</b></td>
	<td class="row2"><input type="radio" name="no_only_quote" value="1" {NO_QUOTE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="no_only_quote" value="0" {NO_QUOTE_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{N_VIEW_SMILE}:</b></td>
	<td class="row2"><input class="post" type="text" size="5" maxlength="4" name="smilies_row" value="{SMILIES_ROW}" /> x <input class="post" type="text" size="5" maxlength="4" name="smilies_column" value="{SMILIES_COLUMN}" /></td>
</tr>
<tr>
	<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</form></table>
<br />
<div align="center" class="copyright">Cricca Guestbook {VERSION} &copy 2004, {COPYRIGHT_YEAR} -Nessuno-</div>

