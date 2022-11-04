{PERMS_MENU}{FORUM_MENU}{VOTE_MENU}
</div></td>
<td valign="top" width="78%">

<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
<script type="text/javascript" language="JavaScript" src="../templates/js/auth_overall_overlib.js"></script>
<script type="text/javascript" language="JavaScript" src="../templates/js/auth_overall_forum.js"></script>

<h1>{L_FORUM_TITLE}</h1>

<p>{L_FORUM_EXPLAIN} {L_FORUM_EXPLAIN_EDIT}</p>

<table width="100%" cellpadding="2" cellspacing="2" align="center"><form method="post" action="{S_FORUM_ACTION}">
<tr>
  	<td><a href="javascript:void(0);" onClick="return start_restore();">{L_FORUM_OVERALL_RESTORE}</a> &bull; <a href="javascript:void(0);" onClick="return stop_edit();">{L_FORUM_OVERALL_STOP}</a></td>
</tr>
</table>
<table width="100%" cellpadding="4" cellspacing="1" class="forumline" align="center">
<tr>
	<th class="thHead" colspan="19">{L_FORUM_TITLE}</th>
</tr>
<tr>
  	<td class="row1" height="30" align="center" valign="middle" colspan="19">
	<!-- BEGIN authedit -->
	<a href="javascript:void(0);" onClick="return start_edit('{authedit.VALUE}', '{authedit.NAME}');" class="nav" title="{authedit.NAME}"><img src="../images/admin_perms/{authedit.NAME}.gif" alt="{authedit.NAME}" title="{authedit.NAME}" hspace="5" />{authedit.NAME}</a>&nbsp; 
	<!-- END authedit -->
	</td>	
</tr>
<tr>
	<th class="thCornerL">{L_FORUM}</th>
	<th class="thTop">{L_VIEW}</th>
	<th class="thTop">{L_READ}</th>
	<th class="thTop">{L_POST}</th>
	<th class="thTop">{L_REPLY}</th>
	<th class="thTop">{L_EDIT}</th>
	<th class="thTop">{L_DELETE}</th>
	<th class="thTop">{L_STICKY}</th>
	<th class="thTop">{L_ANNOUNCE}</th>
	<th class="thTop">{L_GLOBAL}</th>
	<th class="thTop">{L_VOTE}</th>
	<th class="thTop">{L_POLL}</th>
	<th class="thTop">{L_EVENT}</th>
	<th class="thTop">{L_BAN}</th>
	<th class="thTop">{L_VOTE_BAN}</th>
	<th class="thTop">{L_UNBAN}</th>
	<th class="thTop">{L_REPORT}</th>
	<th class="thTop">{L_UPLOAD}</th>
	<th class="thCornerR">{L_DLOAD}</th>
</tr>
<!-- BEGIN catrow -->
<tr>
	<td colspan="19" class="cat"><b class="cattitle"><a href="{catrow.U_VIEWCAT}">{catrow.CAT_DESC}</a></span></td>
</tr>
<!-- BEGIN forumrow -->
<tr> 
	<td class="row1" nowrap="nowrap"><a href="{catrow.forumrow.U_VIEWFORUM}">{catrow.forumrow.FORUM_NAME}</a></td>
	<td class="row2" align="center"><img src="../images/admin_perms/{catrow.forumrow.AUTH_VIEW_IMG}.gif" onClick="return change_auth(this,'{catrow.forumrow.AUTH_VIEW_IMG}',{catrow.forumrow.FORUM_ID},'VIEW');"><input type="hidden" id="auth_{catrow.forumrow.FORUM_ID}_VIEW" name="auth[{catrow.forumrow.FORUM_ID}][VIEW]"></td>
	<td class="row2" align="center"><img src="../images/admin_perms/{catrow.forumrow.AUTH_READ_IMG}.gif"  onClick="return change_auth(this,'{catrow.forumrow.AUTH_READ_IMG}',{catrow.forumrow.FORUM_ID},'READ');"><input type="hidden" id="auth_{catrow.forumrow.FORUM_ID}_READ" name="auth[{catrow.forumrow.FORUM_ID}][READ]"></td>
	<td class="row2" align="center"><img src="../images/admin_perms/{catrow.forumrow.AUTH_POST_IMG}.gif" onClick="return change_auth(this,'{catrow.forumrow.AUTH_POST_IMG}',{catrow.forumrow.FORUM_ID},'POST');"><input type="hidden" id="auth_{catrow.forumrow.FORUM_ID}_POST" name="auth[{catrow.forumrow.FORUM_ID}][POST]"></td>
	<td class="row2" align="center"><img src="../images/admin_perms/{catrow.forumrow.AUTH_REPLY_IMG}.gif" onClick="return change_auth(this,'{catrow.forumrow.AUTH_REPLY_IMG}',{catrow.forumrow.FORUM_ID},'REPLY');"><input type="hidden" id="auth_{catrow.forumrow.FORUM_ID}_REPLY" name="auth[{catrow.forumrow.FORUM_ID}][REPLY]"></td>
	<td class="row2" align="center"><img src="../images/admin_perms/{catrow.forumrow.AUTH_EDIT_IMG}.gif" onClick="return change_auth(this,'{catrow.forumrow.AUTH_EDIT_IMG}',{catrow.forumrow.FORUM_ID},'EDIT');"><input type="hidden" id="auth_{catrow.forumrow.FORUM_ID}_EDIT" name="auth[{catrow.forumrow.FORUM_ID}][EDIT]"></td>
	<td class="row2" align="center"><img src="../images/admin_perms/{catrow.forumrow.AUTH_DELETE_IMG}.gif" onClick="return change_auth(this,'{catrow.forumrow.AUTH_DELETE_IMG}',{catrow.forumrow.FORUM_ID},'DELETE');"><input type="hidden" id="auth_{catrow.forumrow.FORUM_ID}_DELETE" name="auth[{catrow.forumrow.FORUM_ID}][DELETE]"></td>
	<td class="row2" align="center"><img src="../images/admin_perms/{catrow.forumrow.AUTH_STICKY_IMG}.gif" onClick="return change_auth(this,'{catrow.forumrow.AUTH_STICKY_IMG}',{catrow.forumrow.FORUM_ID},'STICKY');"><input type="hidden" id="auth_{catrow.forumrow.FORUM_ID}_STICKY" name="auth[{catrow.forumrow.FORUM_ID}][STICKY]"></td>
	<td class="row2" align="center"><img src="../images/admin_perms/{catrow.forumrow.AUTH_ANNOUNCE_IMG}.gif" onClick="return change_auth(this,'{catrow.forumrow.AUTH_ANNOUNCE_IMG}',{catrow.forumrow.FORUM_ID},'ANNOUNCE');"><input type="hidden" id="auth_{catrow.forumrow.FORUM_ID}_ANNOUNCE" name="auth[{catrow.forumrow.FORUM_ID}][ANNOUNCE]"></td>
	<td class="row2" align="center"><img src="../images/admin_perms/{catrow.forumrow.AUTH_GLOBALANNOUNCE_IMG}.gif" onClick="return change_auth(this,'{catrow.forumrow.AUTH_GLOBALANNOUNCE_IMG}',{catrow.forumrow.FORUM_ID},'GLOBALANNOUNCE');"><input type="hidden" id="auth_{catrow.forumrow.FORUM_ID}_GLOBALANNOUNCE" name="auth[{catrow.forumrow.FORUM_ID}][GLOBALANNOUNCE]"></td>
	<td class="row2" align="center"><img src="../images/admin_perms/{catrow.forumrow.AUTH_VOTE_IMG}.gif" onClick="return change_auth(this,'{catrow.forumrow.AUTH_VOTE_IMG}',{catrow.forumrow.FORUM_ID},'VOTE');"><input type="hidden" id="auth_{catrow.forumrow.FORUM_ID}_VOTE" name="auth[{catrow.forumrow.FORUM_ID}][VOTE]"></td>
	<td class="row2" align="center"><img src="../images/admin_perms/{catrow.forumrow.AUTH_POLLCREATE_IMG}.gif" onClick="return change_auth(this,'{catrow.forumrow.AUTH_POLLCREATE_IMG}',{catrow.forumrow.FORUM_ID},'POLLCREATE');"><input type="hidden" id="auth_{catrow.forumrow.FORUM_ID}_POLLCREATE" name="auth[{catrow.forumrow.FORUM_ID}][POLLCREATE]"></td>
	<td class="row2" align="center"><img src="../images/admin_perms/{catrow.forumrow.AUTH_SUGGESTEVENT_IMG}.gif" onClick="return change_auth(this,'{catrow.forumrow.AUTH_SUGGESTEVENT_IMG}',{catrow.forumrow.FORUM_ID},'SUGGESTEVENT');"><input type="hidden" id="auth_{catrow.forumrow.FORUM_ID}_SUGGESTEVENT" name="auth[{catrow.forumrow.FORUM_ID}][SUGGESTEVENT]"></td>
	<td class="row2" align="center"><img src="../images/admin_perms/{catrow.forumrow.AUTH_BAN_IMG}.gif" onClick="return change_auth(this,'{catrow.forumrow.AUTH_BAN_IMG}',{catrow.forumrow.FORUM_ID},'BAN');"><input type="hidden" id="auth_{catrow.forumrow.FORUM_ID}_BAN" name="auth[{catrow.forumrow.FORUM_ID}][BAN]"></td>
	<td class="row2" align="center"><img src="../images/admin_perms/{catrow.forumrow.AUTH_VOTEBAN_IMG}.gif" onClick="return change_auth(this,'{catrow.forumrow.AUTH_VOTEBAN_IMG}',{catrow.forumrow.FORUM_ID},'VOTEBAN');"><input type="hidden" id="auth_{catrow.forumrow.FORUM_ID}_VOTEBAN" name="auth[{catrow.forumrow.FORUM_ID}][VOTEBAN]"></td>
	<td class="row2" align="center"><img src="../images/admin_perms/{catrow.forumrow.AUTH_UNBAN_IMG}.gif" onClick="return change_auth(this,'{catrow.forumrow.AUTH_UNBAN_IMG}',{catrow.forumrow.FORUM_ID},'UNBAN');"><input type="hidden" id="auth_{catrow.forumrow.FORUM_ID}_UNBAN" name="auth[{catrow.forumrow.FORUM_ID}][UNBAN]"></td>
	<td class="row2" align="center"><img src="../images/admin_perms/{catrow.forumrow.AUTH_REPORT_IMG}.gif" onClick="return change_auth(this,'{catrow.forumrow.AUTH_REPORT_IMG}',{catrow.forumrow.FORUM_ID},'REPORT');"><input type="hidden" id="auth_{catrow.forumrow.FORUM_ID}_REPORT" name="auth[{catrow.forumrow.FORUM_ID}][REPORT]"></td>
	<td class="row2" align="center"><img src="../images/admin_perms/{catrow.forumrow.AUTH_UPLOAD_IMG}.gif" onClick="return change_auth(this,'{catrow.forumrow.AUTH_UPLOAD_IMG}',{catrow.forumrow.FORUM_ID},'UPLOAD');"><input type="hidden" id="auth_{catrow.forumrow.FORUM_ID}_UPLOAD" name="auth[{catrow.forumrow.FORUM_ID}][UPLOAD]"></td>
	<td class="row2" align="center"><img src="../images/admin_perms/{catrow.forumrow.AUTH_DLOAD_IMG}.gif" onClick="return change_auth(this,'{catrow.forumrow.AUTH_DLOAD_IMG}',{catrow.forumrow.FORUM_ID},'DLOAD');"><input type="hidden" id="auth_{catrow.forumrow.FORUM_ID}_DLOAD" name="auth[{catrow.forumrow.FORUM_ID}][DLOAD]"></td>
</tr>
<!-- END forumrow -->
<!-- END catrow -->
<tr>
	<td colspan="19" class="catBottom" align="center"><input type="submit" class="mainoption" name="submit" value="{L_SUBMIT}" /></td>
</tr>
</form></table>
