<table width="100%" cellspacing="2" cellpadding="2" align="center">
<tr> 
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></td>
</tr>
</table>
<table width="100%" cellpadding="4" cellspacing="1" class="forumline" align="center"><form action="{S_LOGIN_ACTION}" method="post">
<tr> 
	<th class="thHead">{L_LOGIN}</th>
</tr>
<tr> 
	<td class="row1"><table cellpadding="10" cellspacing="2" align="center">
	<tr> 
		<td><table>
		<tr> 
			<td><b class="gen">{L_USERNAME}:</b></td>
			<td><input tabindex="1" class="post" type="text" name="username" size="25" maxlength="{LIMIT_USERNAME_MAX_LENGTH}" value="{USERNAME}" /></td>
	  	</tr>
	  	<tr> 
			<td><b class="gen">{L_PASSWORD}:</b></td>
			<td><input tabindex="2" class="post" type="password" name="password" size="25" maxlength="32" /><td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><a href="{U_SEND_PASSWORD}" class="gensmall">{L_SEND_PASSWORD}</a></td>
		</tr>
		<!-- BEGIN switch_allow_autologin -->
		<tr> 
			<td>&nbsp;</td>
			<td class="gensmall"><input type="checkbox" name="autologin"{AUTOLOGIN_CHECKED} /> {L_LOG_ME_IN}</td>
		</tr>
		<!-- END switch_allow_autologin -->
		</table></td>
		<td nowrap="nowrap" valign="top">
		<a href="{U_REGISTER}" class="gensmall">{L_REGISTER}</a><br />
		<a href="{U_REGISTER}" class="gensmall">{L_TERMS}</a><br />
		<a href="{U_SEND_ACTIVATION}" class="gensmall">{L_SEND_ACTIVATION}</a><br />
		</td>
	</tr>
	</table></td>
</tr>
<tr> 
	<td class="catBottom" align="center">{S_HIDDEN_FIELDS}<input tabindex="3" type="submit" name="login" class="mainoption" value="{L_LOGIN}" /></td>
</tr>
</form></table>