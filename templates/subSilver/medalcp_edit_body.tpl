<table width="100%" cellspacing="2" cellpadding="2"align="center">
<tr>
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> <a href="{U_MEDALS}" class="nav">{L_MEDALS}</a> -> <a href="{U_MEDAL_CP}" class="nav">{L_MEDAL_CP}</a></td>
</tr>
</table>
<table class="forumline" width="100%" cellspacing="1" cellpadding="4"><form action="{S_MEDAL_ACTION}" method="post">
<tr> 
	<th class="thHead" colspan="2">{L_MEDAL_INFORMATION}</th>
</tr>
<tr> 
	<td class="row1"><b>{MEDAL_NAME}:</b></td>
	<td class="row2">{MEDAL_DESCRIPTION}</td>
</tr>
<!-- BEGIN medaledit -->
<tr> 
	<td class="row1" width="38%"><b>{medaledit.L_MEDAL_TIME}:</b></td>
	<td class="row2">{medaledit.ISSUE_TIME}</span></td>
</tr>
<tr> 
	<td class="row1"><b>{medaledit.L_MEDAL_REASON}:</b></td>
	<td class="row2"><textarea class="post" name="{medaledit.L_ISSUE_REASON}" rows="6" cols="35">{medaledit.ISSUE_REASON}</textarea></td>
</tr>
<!-- END medaledit -->
<tr>
	<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</form></table>
<br />
<table width="100%" cellspacing="2" align="center">
  <tr> 
	<td valign="top" align="right">{JUMPBOX}</td>
  </tr>
</table>
<br />
<div align="center" class="copyright">Medal System 0.4.6 &copy; 2003, {COPYRIGHT_YEAR} <a href="http://macphpbbmod.sourceforge.net" target="_blank" class="copyright">ycl6</a></div>
