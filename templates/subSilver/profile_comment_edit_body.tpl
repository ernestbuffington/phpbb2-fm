<script language="JavaScript" type="text/javascript">
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

<table width="100%" cellspacing="2" cellpadding="2" align="center">
<tr>
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> <a href="{U_COMMENTS}" class="nav">{L_COMMENTS}</a></td>
</tr>
</table>

<table cellpadding="4" cellspacing="1" width="100%" class="forumline"><form action="{S_POST_ACTION}" method="post" name="post" onsubmit="return checkForm();">
  <tr>
	<th colspan="2" class="thHead" nowrap="nowrap">{MESSAGE_TITLE} :: {USERNAME}</th>
  </tr>
  <tr>
    <td class="row1" valign="top" width="22%"><b class="genmed">{L_COMMENTS}:</b></td>
    <td class="row2"><span class="gen"><textarea name="comments" rows="10" cols="70" wrap="virtual" class="post">{COMMENTS}</textarea></span></td>
  </tr>
  <tr>
	<td colspan="2" align="center" class="catBottom">{S_HIDDEN_FIELDS}<input class="mainoption" type="submit" name="submit" value="{L_SUBMIT}" onclick="this.onclick = new Function('return false');" />&nbsp;&nbsp;<input class="liteoption" type="reset" name="reset" value="{L_RESET}" /></td>
  </tr>
</form></table>

<table width="100%" cellspacing="2" align="center">
  <tr> 
	<td valign="top" align="right">{JUMPBOX}</td>
  </tr>
</table>
