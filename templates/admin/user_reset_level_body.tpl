{PERMS_MENU}{USER_MENU}{CUSTOM_PROFILE_MENU}{BAN_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_RESETUSER_TITLE}</h1> 

<p>{L_RESETUSER_EXPLAIN}</p>

<table width="50%" align="center" cellspacing="1" cellpadding="4" class="forumline"><form action="{S_USER_ACTION}" method="post" name="post">
  <tr>
	<th class="thHead" colspan="2">{L_RESETUSER_HEADER}</th>
  </tr>
  <tr>
	<td class="row1" width="38%"><b>{L_USERNAME}:</b>&nbsp;</td>
	<td class="row2"><input type="text" class="post" name="username"{AJAXED_USER_LIST} maxlength="50" size="35" value="{RESETUSER}" />{AJAXED_USER_LIST_BOX}</td>
  </tr>
  <tr>
	<td colspan="2" class="catBottom" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="reset" value="{L_RESET}" class="mainoption" /></td>
  </tr>
</form></table>
<br />
<div align="center" class="copyright">Reset User Level 1.0.0 &copy 2003, {COPYRIGHT_YEAR} JohnMcK</div>