<form action="{S_LOGIN_ACTION}" method="post">
<table width="98%" cellpadding="4" cellspacing="1" class="forumline" align="center">
	<tr>
		<th height="25" class="thHead">{L_ENTER_PASSWORD}</th>
	</tr>
	<tr>
		<td class="row1">
			<table cellpadding="3" cellspacing="1" width="100%">
				<tr>
					<td align="center">&nbsp;</td>
				</tr>
				<tr>
					<td align="center" width="45%"><span class="gen">{L_USERNAME}</span></td>
				</tr>
				<tr>
					<td>
						<input type="text" name="username" size="25" maxlength="40" value="{USERNAME}" />
					</td>
				</tr>
				<tr>
					<td align="center"><span class="gen">{L_PASSWORD}</span></td>
				</tr>
				<tr>
					<td>
						<input type="password" name="password" size="25" maxlength="25" />
					</td>
				</tr>
				<tr align="center">
					<td><span class="gensmall">{L_AUTO_LOGIN}: <input type="checkbox" name="autologin" /></span></td>
				</tr>
				<tr align="center">
					<td>{S_HIDDEN_FIELDS}<input type="submit" name="login" class="mainoption" value="{L_LOGIN}" /></td>
				</tr>
				<tr align="center">
					<td><span class="gensmall"><a href="{U_SEND_PASSWORD}" class="gensmall">{L_SEND_PASSWORD}</a></span></td>
				</tr>
			</table>
		</td>
	</tr>
</table>

</form>
