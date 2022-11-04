
{ERROR_BOX}

<table width="100%" cellspacing="2" cellpadding="2" align="center">
<tr> 
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></td>
</tr>
</table>
<table cellpadding="4" cellspacing="1" width="100%" align="center" class="forumline"><form action="{S_PROFILE_ACTION}" {S_FORM_ENCTYPE} method="post">
<tr> 
	<th class="thHead" colspan="2">{L_REGISTRATION_INFO}</th>
</tr>
<tr> 
	<td class="row2" colspan="2"><span class="gensmall">{L_ITEMS_REQUIRED}</span></td>
</tr>
<tr> 
	<td class="row1" width="38%"><b class="gen">{L_USERNAME}: *</b><br /><span class="gensmall">{L_LIMIT_USERNAME_LENGTH_EXPLAIN}</span></td>
	<td class="row2"><input type="text" class="post" style="width: 200px" name="username" size="25" maxlength="{LIMIT_USERNAME_MAX_LENGTH}" value="{USERNAME}" /></td>
</tr>
<tr> 
	<td class="row1"><b class="gen">{L_EMAIL_ADDRESS}: *</b></td>
	<td class="row2"><input type="text" class="post" style="width: 200px" name="email" size="25" maxlength="255" value="{EMAIL}" /></td>
</tr>
<tr> 
	<td class="row1"><b class="gen">{L_NEW_PASSWORD}: *</b><br /><span class="gensmall">{PASS_GEN}</span></td>
	<td class="row2"><input type="password" class="post" style="width: 200px" name="new_password" size="25" maxlength="32" value="{PASSWORD}" /></td>
</tr>
<tr> 
	<td class="row1"><b class="gen">{L_CONFIRM_PASSWORD}: * </b></td>
	<td class="row2"><input type="password" class="post" style="width: 200px" name="password_confirm" size="25" maxlength="32" value="{PASSWORD_CONFIRM}" /></td>
</tr>
<!-- BEGIN switch_gender -->
<tr> 
	<td class="row1"><b class="gen">{L_GENDER}:{GENDER_REQUIRED}</b></td> 
    	<td class="row2"><input type="radio" name="gender" value="1" {GENDER_MALE_CHECKED} /> {L_GENDER_MALE}&nbsp;&nbsp;<input type="radio" name="gender" value="2" {GENDER_FEMALE_CHECKED} /> {L_GENDER_FEMALE}</td> 
</tr> 
<!-- END switch_gender -->
<!-- BEGIN switch_birthday -->
<tr> 
	<td class="row1"><b class="gen">{L_BIRTHDAY}:{BIRTHDAY_REQUIRED}</b></td> 
	<td class="row2"><span class="gen">{S_BIRTHDAY}</span></td> 
</tr>
<!-- END switch_birthday -->
<!-- BEGIN xdata -->
<!-- BEGIN switch_type_text -->
<tr>
	<td class="row1"><b class="gen">{xdata.NAME}:</b><br /><span class="gensmall">{xdata.DESCRIPTION}</span></td>
	<td class="row2"><input type="text" class="post" style="width: 200px" name="{xdata.CODE_NAME}" size="35" maxlength="{xdata.MAX_LENGTH}" value="{xdata.VALUE}" /></td>
</tr>
<!-- END switch_type_text -->
<!-- BEGIN switch_type_textarea -->
<tr>
	<td class="row1"><b class="gen">{xdata.NAME}:</b><br /><span class="gensmall">{xdata.DESCRIPTION}</span></td>
	<td class="row2"><textarea name="{xdata.CODE_NAME}" style="width: 300px" rows="6" cols="30" class="post">{xdata.VALUE}</textarea></td>
</tr>
<!-- END switch_type_textarea -->
<!-- BEGIN switch_type_select -->
<tr>
	<td class="row1"><b class="gen">{xdata.NAME}:</b><br /><span class="gensmall">{xdata.DESCRIPTION}</span></td>
	<td class="row2"><select name="{xdata.CODE_NAME}">
	<!-- BEGIN options -->
	<option value="{xdata.switch_type_select.options.OPTION}" {xdata.switch_type_select.options.SELECTED}>{xdata.switch_type_select.options.OPTION}</option>
	<!-- END options -->
	</select></td>
</tr>
<!-- END switch_type_select -->
<!-- BEGIN switch_type_radio -->
<tr>
	<td class="row1"><b class="gen">{xdata.NAME}:</b><br /><span class="gensmall">{xdata.DESCRIPTION}</span></td>
	<td class="row2">
    	<!-- BEGIN options -->
	<input type="radio" name="{xdata.CODE_NAME}" value="{xdata.switch_type_radio.options.OPTION}" {xdata.switch_type_radio.options.CHECKED} />
	<span class="gen">{xdata.switch_type_radio.options.OPTION}</span>&nbsp;&nbsp;
	<!-- END options -->
	</td>
</tr>
<!-- END switch_type_radio -->
<!-- END xdata -->
<tr> 
	<td class="row1"><b class="gen">{L_BOARD_LANGUAGE}:</b></td>
	<td class="row2">{LANGUAGE_SELECT}</td>
</tr>
<!-- BEGIN override_user_style_block -->
<tr> 
  	<td class="row1"><b class="gen">{L_BOARD_STYLE}:</b></td>
  	<td class="row2">{STYLE_SELECT}</td>
</tr>
<!-- END override_user_style_block -->
<tr> 
	<td class="row1"><b class="gen">{L_TIMEZONE}:</b></td>
	<td class="row2">{TIMEZONE_SELECT}</td>
</tr>
<tr> 
	<td class="row1"><b class="gen">{L_DATE_FORMAT}:</b></td>
	<td class="row2">{DATE_FORMAT_SELECT}</td>
</tr>
<!-- BEGIN switch_avatar_select -->
<tr> 
	<td class="row1"><b class="gen">{L_AVATAR_SELECT}:</b><br /><span class="gensmall">{L_AVATAR_SELECT_EXPLAIN}</span></td> 
	<td class="row2"><table cellpadding="2" cellspacing="0">
	<tr> 
		<td><select name="avatar_select" onChange="if (this.value!='') document.images['avatar_select'].src = '{AVATAR_GALLERY_PATH}/' + this.value; else document.images['avatar_select'].src = '{AVATAR_SELECT_START}';" >
			<option value="">{L_NO_AVATAR}</option>
			{AVATAR_SELECT_OPTIONS}
		</select></td> 
		<td>&nbsp; &nbsp; &nbsp;<img src="{AVATAR_SELECT_START}" name="avatar_select" alt="" title="" /></td> 
	</tr>
	</table></td> 
</tr> 
<!-- END switch_avatar_select -->
<!-- BEGIN switch_auto_subscribe_digest -->
<tr> 
	<td class="row1"><b class="gen">{L_DIGEST_AUTO}:</b></td>
	<td class="row2"><span class="gen"><input type="radio" name="digest_auto" value="1" {DIGEST_AUTO_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="digest_auto" value="0" {DIGEST_AUTO_NO} /> {L_NO}</span></td>
</tr>
<!-- END switch_auto_subscribe_digest -->
<!-- BEGIN switch_new_sign_up -->
<tr> 
	<td class="row1"><b class="gen">{L_DIGEST_NEW}:</b></td>
	<td class="row2"><span class="gen"><input type="radio" name="digest_new" value="2" {DIGEST_NEW_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="digest_new" value="0" {DIGEST_NEW_NO} /> {L_NO}</span></td>
</tr>
<!-- END switch_new_sign_up -->
<!-- BEGIN switch_confirm -->
<tr> 
	<th class="thSides" colspan="2">{L_CONFIRM_CODE_TITLE}</th> 
</tr> 
<tr>
	<td class="row2" colspan="2"><span class="gensmall">{L_CONFIRM_CODE_IMPAIRED}</span></td>
</tr>
<tr>
	<td class="row1"><b class="gen">{L_CONFIRM_CODE}: *</b><br /><span class="gensmall">{L_CONFIRM_CODE_EXPLAIN}</span></td>
	<td class="row2"><table cellpadding="2" cellspacing="0">
	<tr>
		<td><input type="text" class="post" name="confirm_code" size="10" maxlength="6" value="" /></td>
		<td>&nbsp; &nbsp; &nbsp;{CONFIRM_IMG}</td>
	</tr>
	</table></td>
</tr>
<!-- END switch_confirm -->
<!-- BEGIN switch_vipcode -->
<tr> 
	<td class="row1"><b class="gen">{L_VIP_CODE}: *</b><br /><span class="gensmall">{L_VIP_CODE_EXPLAIN}</span></td>
	<td class="row2"><input type="text" class="post" name="vip_code" size="10" maxlength="10" value="" /></td>
</tr>
<!-- END switch_vipcode -->
<tr>
	<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" name="reset" class="liteoption" /></td>
</tr>
</form></table>
