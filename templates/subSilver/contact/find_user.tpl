<!-- BEGIN find_user -->
<br />
<table width="100%" cellpadding="4" cellspacing="1" class="forumline"><form method="post" action="{find_user.S_FORM_ACTION}" name="post">
<tr>
	<th colspan="2" class="thHead">{find_user.L_ADD_A_USER}</th>
</tr>
<tr>
	<td class="row1" width="38%"><b>{L_USERNAME}:</b></td>
	<td class="row2"><input type="text" class="post" tabindex="1" name="username" size="25" maxlength="{LIMIT_USERNAME_MAX_LENGTH}" value="{find_user.USERNAME}" /> <input type="submit" name="usersubmit" value="{find_user.L_FIND_USERNAME}" class="liteoption" onClick="window.open('{find_user.U_SEARCH_USER}', '_phpbbsearch', 'HEIGHT=250,resizable=yes,WIDTH=400');return false;" /></td>
</tr>
<!-- BEGIN alert -->
<tr>
	<td class="row1"><b>{L_ALERT}:</b></td>
	<td class="row2"><input type="checkbox" name="alert"> {L_YES}</td>
</tr>
<!-- END alert -->
<tr>
	<td class="catBottom" align="center" colspan="2">{find_user.S_HIDDEN_FIELDS}<input type="submit" name="single" value="{find_user.L_ADD_USER}" class="mainoption" /></td>
</tr>
</form></table>
<!-- END find_user -->
	</td>
</tr>
</table>
<br />
<div align="center" class="copyright">Prillian 0.7.0 Beta &copy; 2003, {COPYRIGHT_YEAR} <a href="http://darkmods.sourceforge.net" class="copyright" target="_blank">Thoul</a></div>
<br />
<table width="100%" cellpadding="2" cellspacing="2">
  <tr> 
	<td align="right">{JUMPBOX}</td>
  </tr>
</table>
