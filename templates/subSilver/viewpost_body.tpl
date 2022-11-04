
<table class="forumline" width="100%" cellspacing="1" cellpadding="4">
<tr>
	<td class="catLeft" height="28" nowrap="nowrap" align="center"><span class="nav">{L_VIEW_SINGLE}</td>
      	<td class="catRight" height="28" nowrap="nowrap" align="right"><span class="nav">&nbsp;{L_TOPIC}: "<a class="nav" href="{U_VIEW_TOPIC}">{TOPIC_TITLE}</a>"</span>&nbsp;</td>
</tr>
<tr>
	<td id="poll_display" colspan="2">{POLL_DISPLAY}</td>
</tr>
<tr>
	<th width="150" class="thCornerL" nowrap="nowrap">{L_AUTHOR}</th>
	<th width="100%"class="thCornerR" nowrap="nowrap">{L_MESSAGE}</th>
</tr>
<!-- BEGIN postrow -->
<tr id="postrow_{postrow.AJAXED_I}"> 
	<td valign="top" class="{postrow.ROW_CLASS}"><span class="name"><a name="{postrow.U_POST_ID}"></a><b>{postrow.POSTER_NAME}</b></span><br /><span class="postdetails">{postrow.POSTER_RANK}<br />{postrow.RANK_IMAGE}{postrow.POSTER_AVATAR}<br /></span><br /></td>
	<td class="{postrow.ROW_CLASS}" width="100%" valign="top"><table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td onDblClick="{postrow.AJAXED_EDIT_SUBJECT}" width="100%"><a href="{postrow.U_MINI_POST}"><img src="{postrow.MINI_POST_IMG}" width="12" height="9" alt="{postrow.L_MINI_POST_ALT}" title="{postrow.L_MINI_POST_ALT}" /></a><span class="postdetails">{L_POSTED}: {postrow.POST_DATE}<span class="gen">&nbsp;</span>&nbsp; &nbsp;{L_POST_SUBJECT}: {postrow.POST_SUBJECT}</span></td>
		<td valign="top" nowrap="nowrap">{U_POST_REPLY_MINI_TOPIC} {postrow.THREAD_KICK_IMG} {postrow.QUOTE_IMG} {postrow.EDIT_IMG} {postrow.EDIT_DATE_IMG} {postrow.DELETE_IMG} {postrow.IP_IMG}</td>
	</tr>
	<tr> 
		<td colspan="2"><hr /></td>
	</tr>
	<tr>
		<td colspan="2" ondblclick="{postrow.AJAXED_POST_MENU}"><span class="postbody"><span style="color: #{postrow.CUSTOM_POST_COLOR}"><div id="p_{postrow.U_POST_ID}_message" style="display: inline;">{postrow.MESSAGE}</div></span></span>{postrow.ATTACHMENTS}</td>
	</tr>
	<tr>
		<td colspan="2" class="{postrow.ROW_CLASS}" valign="bottom"><span class="gensmall" id="p_{postrow.U_POST_ID}_edited">{postrow.EDITED_MESSAGE}{postrow.SIGNATURE}</span></td>
	</tr>
	</table></td>
</tr>
<tr id="postrow_{postrow.AJAXED_I}_second"> 
	<td class="{postrow.ROW_CLASS}" align="left" valign="middle"></td>
	<td class="{postrow.ROW_CLASS}" height="28" valign="bottom" nowrap="nowrap"><table cellspacing="0" cellpadding="0" height="18" width="18">
	<tr> 
		<td valign="middle" nowrap="nowrap"><a href="#top"><img src="{ICON_UP}" alt="{L_BACK_TO_TOP}" title="{L_BACK_TO_TOP}" /></a> {postrow.PROFILE_IMG} {postrow.PM_IMG} {postrow.EMAIL_IMG} {postrow.WWW_IMG} {postrow.AIM_IMG} {postrow.YIM_IMG} {postrow.MSN_IMG} {postrow.ICQ_IMG}</td>
	</tr>
	</table></td>
</tr>
<tr> 
	<td class="catBottom" colspan="2">&nbsp;</td>
</tr>
<!-- END postrow -->
</table>