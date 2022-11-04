{SMILEY_MENU}{POST_MENU}
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
<table width="100%" cellpadding="4" cellspacing="1" align="center" class="forumline"><form action="{S_CONFIG_ACTION}" method="post" name="smile">
<tr>
	<th class="thHead" colspan="2">{L_PAGE_TITLE}</th>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_ALLOW_SMILIES}:</b></td>
	<td class="row2"><input type="radio" name="allow_smilies" value="1"{SMILE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_smilies" value="0"{SMILE_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_SMILIES_REMOVAL1}:</b></td>
	<td class="row2"><input type="radio" name="smilie_removal1" value="1"{REMOVAL1_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="smilie_removal1" value="0"{REMOVAL1_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_SMILIES_REMOVAL2}:</b></td>
	<td class="row2"><input type="radio" name="smilie_removal2" value="1"{REMOVAL2_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="smilie_removal2" value="0"{REMOVAL2_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_SMILIES_RANDOM}:</b></td>
	<td class="row2"><input type="radio" name="smilie_random" value="1"{RANDOM_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="smilie_random" value="0"{RANDOM_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_ALLOW_USERGROUPS}:</b><br /><span class="gensmall">{L_ALLOW_USERGROUPS_EXPLAIN}</span></span></td>
	<td class="row2"><input type="radio" name="smilie_usergroups" value="1"{USERGROUPS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="smilie_usergroups" value="0"{USERGROUPS_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_SMILEY_TABLE_COLUMNS}:</b></td>
	<td class="row2"><input class="post" type="text" size="5" maxlength="3" name="smilie_columns" value="{SMILEY_COLUMNS}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_SMILEY_TABLE_ROWS}:</b></td>
	<td class="row2"><input class="post" type="text" size="5" maxlength="3" name="smilie_rows" value="{SMILEY_ROWS}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_SMILEY_POSTING}:</b></td>
	<td class="row2"><select name="smilie_posting">
		<option value="0"{SMILEY_NOTHING1}>{L_SMILEY_NOTHING}</option>
		<option value="1"{SMILEY_BUTTONS1}>{L_SMILEY_BUTTON}</option>
		<option value="2"{SMILEY_DROPDOWN1}>{L_SMILEY_DROPDOWN}</option>
	</select></td>
</tr>
<tr>
	<td class="row1"><b>{L_SMILEY_POPUP}:</b></td>
	<td class="row2"><select name="smilie_popup">
		<option value="0"{SMILEY_NOTHING2}>{L_SMILEY_NOTHING}</option>
		<option value="1"{SMILEY_BUTTONS2}>{L_SMILEY_BUTTON}</option>
		<option value="2"{SMILEY_DROPDOWN2}>{L_SMILEY_DROPDOWN}</option>
	</select></td>
</tr>
<tr> 
	<td class="row1"><b>{L_SMILEY_BUTTONS}:</b></b></td>
	<td class="row2"><input type="radio" name="smilie_buttons" value="1"{SMILEY_BUTTONS_NAME} /> {L_BUTTONS_NAME}&nbsp;&nbsp;<input type="radio" name="smilie_buttons" value="0"{SMILEY_BUTTONS_NUMBER} /> {L_BUTTONS_NUMBER}&nbsp;&nbsp;<input type="radio" name="smilie_buttons" value="2"{SMILEY_BUTTONS_IMAGE} /> {L_BUTTONS_IMAGE}</td>
</tr>
<tr>
	<td class="row1"><b>{L_SMILIES_PATH}:</b><br /><span class="gensmall">{L_SMILIES_PATH_EXPLAIN}</span></span></td>
	<td class="row2"><input class="post" type="text" size="25" maxlength="255" name="smilies_path" value="{SMILIES_PATH}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_SMILIES_IMAGE_PATH}:</b><br /><span class="gensmall">{L_SMILIES_IMAGE_PATH_EXPLAIN}</span></span></td>
	<td class="row2"><input class="post" type="text" size="25" maxlength="255" name="smilie_icon_path" value="{SMILIES_IMAGE_PATH}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_SMILIES_FILESIZE}:</b><br /><span class="gensmall">{L_SMILIES_FILESIZE_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="10" maxlength="10" name="smilie_max_filesize" value="{SMILIE_FILESIZE}" /> Bytes</td>
</tr>
<tr>
	<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</form></table>
<br />
<div align="center" class="copyright">Smiley Categories 2.0.4</a> &copy; 2004, {COPYRIGHT_YEAR} <a href="http://mods.afkamm.co.uk" target="_blank" class="copyright">Afkamm</a></div>
