<script language="javascript" type="text/javascript">
<!--
	function checkForm()
	{
		errors = '';
		if( document.post.comments.value.length < 2 )
		{
			errors += '{L_EMPTY_COMMENTS}';
		}

		if( errors )
		{
			alert(errors);
			return false;
		}
		else
		{
			return true;
		}
	}
//-->
</script>

<table width="100%" cellpadding="4" cellspacing="1" align="center" class="forumline"><form action="{S_POST_ACTION}" method="post" name="post" onsubmit="return checkForm();">
 <tr>
	<th colspan="2" class="thHead" align="center">{L_USER_SPECIAL}</th>
 </tr>
 <tr>
	<td colspan="2" class="row1"><span class="gensmall">{L_USER_ACTIVE_INACTIVE} &bull; {L_BANNED_USERNAME} &bull; {L_BANNED_EMAIL}<br /><a href="{U_ADMIN_EDIT_PROFILE}" class="gensmall">{L_ADMIN_EDIT_PROFILE}</a> &bull; <a href="{U_ADMIN_EDIT_PERMS}" class="gensmall">{L_ADMIN_EDIT_PERMS}</a> &bull; <a href="{U_READ_COMMENTS}" class="gensmall">{L_READ_COMMENTS}</a></span></td>
  </tr>
 <tr>
	<th colspan="2" class="thHead" align="center">{L_COMMENTS}</th>
 </tr>
  <!-- BEGIN comments_row -->
  <tr>
	<td valign="top" class="{comments_row.ROW_CLASS}" width="38%"><b>{L_POSTER}:</b> {comments_row.POSTER}<br /><span class="postdetails"><b>{L_TIME}:</b> {comments_row.TIME}</span></td>
	<td valign="top" class="{comments_row.ROW_CLASS}">{comments_row.COMMENTS}</td>
  </tr>
  <!-- END comments_row -->
  <!-- BEGIN switch_no_comments -->
  <tr>
	<td class="row1" colspan="2" height="30" align="center" valign="middle"><span class="gen">{L_NO_COMMENTS}</span></td>
  </tr>
  <!-- END switch_no_comments -->
  <tr>
	<td colspan="2" class="row1" align="center"><textarea class="post" name="comments" rows="5" cols="70" wrap="virtual"></textarea></td>
  </tr>
  <tr>
	<td colspan="2" align="center" class="catBottom" height="28">{S_HIDDEN_FIELDS}<input class="mainoption" type="submit" name="submit" value="{L_SUBMIT}" onclick="this.onclick = new Function('return false');" />&nbsp;&nbsp;<input class="liteoption" type="reset" name="reset" value="{L_RESET}" /></td>
  </tr>
</form></table>