<script language="Javascript" type="text/javascript">
<!--
function openguestpopup(meeting, user)
{
	window.open('{U_GUEST_POPUP}&id='+meeting+'&u='+user, '_blank', 'HEIGHT=600,resizable=yes,WIDTH=400');
}
//-->
</script>

<table width="100%" cellspacing="2" cellpadding="2">
  <tr>
	<td class="nav"><a href="{U_INDEX}" class="gen">{L_INDEX}</a> -> <a href="{U_MEETING}" class="nav">{L_MEETING}</a> -> <a href="{U_MEETING_DETAIL}" class="nav">{L_MEETING_DETAIL}</a></td>
  </tr>
</table>

<table width="100%" cellpadding="4" cellspacing="1" class="forumline"><form action="{S_ACTION}" method="post" name="own_signs">
<tr>
	<th class="thHead" colspan="2">{MEETING_SUBJECT}{MEETING_CLOSED_STRING}</th>
</tr>
<tr>
	<td class="row1"><table width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td class="row1" align="center" width="100%" colspan="2"><span class="genmed">{MEETING_BY_USER}&nbsp;&nbsp;&nbsp;{MEETING_EDIT_BY_USER}</span></td>
	</tr>
	<tr>
		<td class="row1" align="center" width="50%"><span class="gen"><br /><b>{L_MEETING_LOCATION}:</b> {MEETING_LOCATION}</span><br /><br /></td>
		<td class="row1" align="center" width="50%"><span class="gen"><br /><b>{L_MEETING_LINK}:</b> <a href="{MEETING_LINK}" class="nav" target="_blank">{MEETING_LINK}</a></span><br /><br /></td>
	</tr>
	<tr>
		<td class="row1" align="center"><span class="gen"><b><br />{L_MEETING_TIME}:</b> {MEETING_TIME}</span><br /><br /></td>
		<td class="row1" align="center"><span class="gen"><b><br />{L_MEETING_UNTIL}:</b> {MEETING_UNTIL}</span><br /><br /></td>
	</tr>
	<tr>
		<td class="row1" align="center" colspan="2"><span class="gen"><b><br />{L_MEETING_PLACES}:</b> {MEETING_PLACES}{L_MEETING_REMAIN_GUESTS}{L_MEETING_REMAIN_GUESTS_PLACES}</span><br /><br /></td>
	</tr>
	<tr>
		<td class="row2" align="center" colspan="2"><span class="genmed"><br />{MEETING_DESC}</b><br /><br /></span></td>
	</tr>
	<tr>
		<td class="row1" align="center" valign="top" colspan="2"><br /><span class="genmed">{U_MEETING_USER}</span><br /><br /></td>
	</tr>
	</table></td>
</tr>
<tr>
	<td align="center" class="catBottom"><span class="nav">{S_HIDDEN_FIELDS}{SIGNED_ON_EDIT}{MEETING_SURE_USER}{S_REMAIN_GUESTS}{SIGNED_OFF}</span></td>
</tr>
<!-- BEGIN guest_names_block -->
<tr>
	<td class="catHead" colspan="2"><span class="cattitle">{L_GUESTS}</span></td>
</tr>
<tr>
	<th class="thCornerL">{L_GUEST_PRENAMES}</th>
	<th class="thCornerR">{L_GUEST_NAMES}</th>
</tr>
<!-- BEGIN guest_name_row -->
<tr>
	<td class="{guest_names_block.guest_name_row.ROW_CLASS}" align="right"><input type="text" name="meeting_guest_prename[]" class="post" size="50" maxlength="255" value="{guest_names_block.guest_name_row.GUEST_PRENAME}" /></td>
	<td class="{guest_names_block.guest_name_row.ROW_CLASS}"><input type="text" name="meeting_guest_name[]" class="post" size="50" maxlength="255" value="{guest_names_block.guest_name_row.GUEST_NAME}" /></td>
</tr>
<!-- END guest_name_row -->
<!-- BEGIN guest_name_row_read_only -->
<tr>
	<td class="{guest_names_block.guest_name_row_read_only.ROW_CLASS}"><input type="hidden" name="meeting_guest_prename[]" value="{guest_names_block.guest_name_row_read_only.GUEST_PRENAME}" /><span class="topictitle">{guest_names_block.guest_name_row_read_only.GUEST_PRENAME}</span</td>
	<td class="{guest_names_block.guest_name_row_read_only.ROW_CLASS}"><input type="hidden" name="meeting_guest_name[]" value="{guest_names_block.guest_name_row_read_only.GUEST_NAME}" /><span class="topictitle">{guest_names_block.guest_name_row_read_only.GUEST_NAME}</span</td>
</tr>
<!-- END guest_name_row_read_only -->
<!-- BEGIN guest_block_footer -->
<tr>
	<td colspan="2" class="catBottom"><span class="nav">{guest_names_block.guest_block_footer.L_GUESTNAMES_EXPLAIN}</span></td>
</tr>
<!-- END guest_block_footer -->
<!-- END guest_names_block -->
</form></table>
<br />

<!-- BEGIN sign_off_user -->
<table width="100%" cellpadding="2" cellspacing="2"><form action="{S_ACTION}" method="post" name="signoff_user">
<tr>
	<td align="center">{sign_off_user.S_MEETING_SIGNOFFS}{sign_off_user.SIGNED_OFF}</td>
</tr>
</form></table>
<br />
<!-- END sign_off_user -->

<!-- BEGIN sign_on_other_user -->
<table width="100%" cellpadding="2" cellspacing="2"><form action="{S_ACTION}" method="post" name="sign_on_other_user">
<tr>
	<td align="center">{sign_on_other_user.S_NEW_USERS}{sign_on_other_user.S_SIGNED_ON_OTHER}</td>
</tr>
</form></table>
<br />
<!-- END sign_on_other_user -->

<!-- BEGIN meeting_email -->
<table width="100%" cellpadding="2" cellspacing="2">
<tr>
	<td align="center"><a href="{meeting_email.U_MEETING_EMAIL}" class="nav">{meeting_email.L_MEETING_EMAIL}</a></td>
</tr>
</table>
<br />
<!-- END meeting_email -->

<table width="100%" cellpadding="4" cellspacing="1" class="forumline">
<tr>
	<th class="thHead" colspan="3">{L_MEETING_STATISTIC}</th>
</tr>
<tr>
	<td height="30" class="row1" align="left" width="33%"><span class="genmed"><b>{L_MEETING_FREE_PLACES}: </b>{MEETING_FREE_PLACES}{L_MEETING_FREE_GUESTS}</span></td>
	<td class="row1" align="center" width="34%"><span class="genmed"><b>{L_MEETING_SURE_TOTAL}: </b>{MEETING_SURE_TOTAL}%</span></td>
	<td class="row1" align="right" width="33%"><span class="genmed"><b>{L_MEETING_SURE_TOTAL_USER}: </b>{MEETING_SURE_TOTAL_USER}%</span></td>
</tr>
</table>
<br />

<!-- BEGIN meeting_comments_title -->
<table width="100%" cellpadding="4" cellspacing="0" class="forumline">
<tr>
	<th class="cathead" height="28" colspan="2"><span class="cathead">{meeting_comments_title.L_MEETING_COMMENT_TITLE}</span></th>
</tr>
<!-- BEGIN meeting_comments -->
<tr>
	<td class="{meeting_comments_title.meeting_comments.ROW_CLASS}"><span class="genmed">{meeting_comments_title.meeting_comments.MEETING_COMMENT_USER}</span></td>
	<td class="{meeting_comments_title.meeting_comments.ROW_CLASS}" align="right"><span class="genmed">{meeting_comments_title.meeting_comments.MEETING_DELETE}&nbsp;&nbsp;&nbsp;{meeting_comments_title.meeting_comments.MEETING_EDIT}</span></td>
</tr>
<tr>
	<td class="{meeting_comments_title.meeting_comments.ROW_CLASS}" colspan="2"><span class="genmed">{meeting_comments_title.meeting_comments.MEETING_COMMENT}</span></td>
</tr>
<!-- END meeting_comments -->
</table>
<!-- END meeting_comments_title -->

<!-- BEGIN set_comment -->
<br />
<table width="100%" cellpadding="4" cellspacing="0"><form action="{set_comment.S_ENTER_COMMENT}" method="post">
<tr>
	<td align="center"><input type="submit" name="edit_comment" class="liteoption" value="{set_comment.L_ENTER_COMMENT}"></td>
</tr>
</form></table>
<!-- END set_comment -->

<table width="100%" cellspacing="2" align="center"> 
  <tr> 
	<td align="right" valign="middle" nowrap>{JUMPBOX}</td> 
  </tr> 
</table> 
<br />
<div align="center" class="copyright">Meeting 1.3.18 &copy; 2004, {COPYRIGHT_YEAR} <a href="http://www.oxpus.de/" class="copyright" target="_blank">OXPUS</a></div>
