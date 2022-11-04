{USER_MENU}
{CUSTOM_PROFILE_MENU}
{BAN_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_XDATA_ADMIN}</h1>

<p></p>

<table cellpadding="4" cellspacing="1" width="100%" class="forumline" align="center"><form action="{U_FORM_ACTION}" method="post">
<tr>
	<th class="thHead" nowrap="nowrap" colspan="2">&nbsp;{L_BASIC_OPTIONS}&nbsp;</th>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_NAME}:</b></td>
	<td class="row2"><input class="post" type="text" name="field_name" value="{NAME}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_DESCRIPTION}:</b></td>
	<td class="row2"><textarea class="post" name="field_desc" style="width: 300px" rows="3" cols="30">{DESCRIPTION}</textarea></td>
</tr>
<tr>
	<td class="row1"><b>{L_TYPE}:</b></td>
	<td class="row2"><select name="field_type">
		<option value="text" {TEXT_SELECTED}>{L_TEXT}</option>
		<option value="textarea" {TEXTAREA_SELECTED}>{L_TEXTAREA}</option>
		<option value="radio" {RADIO_SELECTED}>{L_RADIO}</option>
		<option value="select" {SELECT_SELECTED}>{L_SELECT}</option>
		<option value="custom" {CUSTOM_SELECTED}>{L_CUSTOM}</option>
	</select></td>
</tr>
<tr>
	<td class="row1"><b>{L_LENGTH}:</b><br /><span class="gensmall">{L_LENGTH_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" name="field_length" value="{LENGTH}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_VALUES}:</b><br /><span class="gensmall">{L_VALUES_EXPLAIN}</span></td>
	<td class="row2"><textarea class="post" name="field_values" value="{VALUES}" rows="6" cols="30" style="width: 300px">{VALUES}</textarea></td>
</tr>
<tr>
	<td class="row1"><b>{L_ALLOW_BBCODE}:</b></td>
	<td class="row2"><input type="radio" name="allow_bbcode" value="1"{ALLOW_BBCODE_YES_CHECKED} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_bbcode" value="0"{ALLOW_BBCODE_NO_CHECKED} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_ALLOW_SMILIES}:</b></td>
	<td class="row2"><input type="radio" name="allow_smilies" value="1"{ALLOW_SMILIES_YES_CHECKED} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_smilies" value="0"{ALLOW_SMILIES_NO_CHECKED} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_ALLOW_HTML}:</b></td>
	<td class="row2"><input type="radio" name="allow_html" value="1"{ALLOW_HTML_YES_CHECKED} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_html" value="0"{ALLOW_HTML_NO_CHECKED} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_DEFAULT_AUTH}:</b><br /><span class="gensmall">{L_DEFAULT_AUTH_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="default_auth" value="{AUTH_ALLOW}"{DEFAULT_AUTH_ALLOW_CHECKED} /> {L_ALLOW}&nbsp;&nbsp;<input type="radio" name="default_auth" value="{AUTH_DENY}"{DEFAULT_AUTH_DENY_CHECKED} /> {L_DENY}</td>
</tr>
<tr>
	<td class="catBottom" colspan="2" align="center"><input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</table>
<br />

<table cellpadding="4" cellspacing="1" width="100%" class="forumline" align="center">
<tr>
	<th class="thHead" nowrap="nowrap" colspan="2">&nbsp;{L_ADVANCED_OPTIONS}&nbsp;</th>
</tr>
<tr>
	<td colspan="2" class="row2"><span class="gensmall">{L_ADVANCED_NOTICE}</span></td>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_DISPLAY_TYPE}:</b><br /><span class="gensmall">{L_DISPLAY_REGISTER_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="display_register" value="1"{DISPLAY_REGISTER_NORMAL_CHECKED} /> {L_NORMAL}&nbsp;&nbsp;<input type="radio" name="display_register" value="0"{DISPLAY_REGISTER_NONE_CHECKED} /> {L_NONE}&nbsp;&nbsp;<input type="radio" name="display_register" value="2"{DISPLAY_REGISTER_ROOT_CHECKED} /> {L_ROOT}</td>
</tr>
<tr>
	<td class="row1"><b>{L_DISPLAY_TYPE}:</b><br /><span class="gensmall">{L_DISPLAY_PROFILE_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="display_viewprofile" value="1"{DISPLAY_PROFILE_NORMAL_CHECKED} /> {L_NORMAL}&nbsp;&nbsp;<input type="radio" name="display_viewprofile" value="0"{DISPLAY_PROFILE_NONE_CHECKED} /> {L_NONE}&nbsp;&nbsp;<input type="radio" name="display_viewprofile" value="2"{DISPLAY_PROFILE_ROOT_CHECKED} /> {L_ROOT}</td>
</tr>
<tr>
	<td class="row1"><b>{L_DISPLAY_TYPE}:</b><br /><span class="gensmall">{L_DISPLAY_POSTING_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="display_posting" value="1"{DISPLAY_POSTING_NORMAL_CHECKED} /> {L_NORMAL}&nbsp;&nbsp;<input type="radio" name="display_posting" value="0"{DISPLAY_POSTING_NONE_CHECKED} /> {L_NONE}&nbsp;&nbsp;<input type="radio" name="display_posting" value="2"{DISPLAY_POSTING_ROOT_CHECKED} /> {L_ROOT}</td>
</tr>
<tr>
	<td class="row1"><b>{L_CODE_NAME}:</b><br /><span class="gensmall">{L_CODE_NAME_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" name="new_code_name" value="{CODE_NAME}" size="25" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_HANDLE_INPUT}:</b><br /><span class="gensmall">{L_HANDLE_INPUT_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="handle_input" value="1"{HANDLE_INPUT_YES_CHECKED} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="handle_input" value="0"{HANDLE_INPUT_NO_CHECKED} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_REGEXP}:</b><br /><span class="gensmall">{L_REGEXP_EXPLAIN}</span></td>
	<td class="row2"><textarea class="post" name="regexp" rows="5" cols="30" style="width: 300px;">{REGEXP}</textarea></td>
</tr>
<tr>
	<td class="catBottom" colspan="2" align="center"><input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
{S_HIDDEN_FIELDS}
</form></table>
<br />
<div align="center" class="copyright">Custom Profiles 0.1.1 &copy; 2003, {COPYRIGHT_YEAR} zayin</div>

