
<table width="100%" cellspacing="2" cellpadding="2" align="center">
<tr> 
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></yd>
</tr>
</table>
<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline"><form action="{S_PROFILE_ACTION}" method="post">
<tr> 
	<th class="thHead" colspan="2">{L_SEND_PASSWORD}</th>
</tr>
<tr> 
	<td class="row2" colspan="2"><span class="gensmall">{L_ITEMS_REQUIRED}</span></td>
</tr>
<tr> 
	<td class="row1" width="38%"><b>{L_USERNAME}: *</b></td>
	<td class="row2"><input type="text" class="post" style="width: 200px" name="username" size="{LIMIT_USERNAME_MAX_LENGTH}" maxlength="40" value="{USERNAME}" /></td>
</tr>
<tr> 
	<td class="row1"><b>{L_EMAIL_ADDRESS}: *</b></td>
	<td class="row2"><input type="text" class="post" style="width: 200px" name="email" size="25" maxlength="255" value="{EMAIL}" /></td>
</tr>
<!-- BEGIN switch_confirm -->
<tr> 
	<th class="thSides" colspan="2">{L_CONFIRM_CODE_TITLE}</th> 
</tr> 
<tr>
	<td class="row2" colspan="2"><span class="gensmall">{L_CONFIRM_CODE_IMPAIRED}</span></td>
</tr>
<tr>
	<td class="row1"><b class="gen">{L_CONFIRM_CODE}: </b><br /><span class="gensmall">{L_CONFIRM_CODE_EXPLAIN}</span></td>
	<td class="row2"><table cellpadding="2" cellspacing="0">
	<tr>
		<td><input type="text" class="post" name="confirm_code" size="10" maxlength="6" value="" /></td>
		<td>&nbsp; &nbsp; &nbsp;{CONFIRM_IMG}</td>
	</tr>
	</table></td>
</tr>
<!-- END switch_confirm -->
<tr> 
	<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" name="reset" class="liteoption" /></td>
</tr>
</form></table>