<table width="100%" cellpadding="2" cellspacing="2">
<tr>
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> <a href="{U_LINK}" class="nav">{LINKS}</a>
	<!-- BEGIN navlinks -->
	-> <a href="{navlinks.U_VIEW_CAT}" class="nav">{navlinks.CAT_NAME}</a>
	<!-- END navlinks -->
	-> {FILE_NAME}</td>
</tr>
</table>

<table width="100%" cellpadding="4" cellspacing="1" class="forumline">
<tr>
	<th class="thHead" colspan="2">{FILE_NAME}</th>
</tr>
<tr>
	<td rowspan="2" class="row1" align="center">{LINK_LOGO}</td>
	<td class="row1"><span class="gensmall"><img src="{POST_IMAGE}" alt="{POST_IMAGE_ALT}" title="{POST_IMAGE_ALT}" /><b>{L_DATE}:</b> {DATE} <b>{L_SUBMITED_BY}:</b> {POSTER} <b>{L_DOWNLOADS}:</b> {FILE_DLS}</span></td>
</tr>
<tr>
	<td width="100%" class="row1"><a href="{U_FILE}" class="topictitle" target="_blank">{FILE_NAME}</a><br /><span class="genmed">{FILE_DESC}</span></td>
</tr>
</table>
<br />

<table width="100%" cellpadding="4" cellspacing="1" class="forumline">
<tr>
	<th class="thCornerL">{L_AUTHOR}</th>
	<th class="thCornerR">{L_COMMENTS}</th>
</tr>
<!-- BEGIN NO_COMMENTS -->
<tr>
	<td colspan="2" class="row1" align="center"><span class="genmed">{NO_COMMENTS.L_NO_COMMENTS}</span></td>
</tr>
<!-- END NO_COMMENTS -->
<!-- BEGIN text -->
<tr>
	<td width="150" valign="top" class="row1"><span class="name"><b>{text.POSTER}</b></span><br /><span class="postdetails">{text.POSTER_RANK}<br />{text.RANK_IMAGE}{text.POSTER_AVATAR}<br /><br />{text.POSTER_JOINED}<br />{text.POSTER_POSTS}<br />{text.POSTER_FROM}</span><br />&nbsp;</td>
	<td class="row1" height="28" valign="top"><table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td width="100%" valign="middle"><span class="postdetails"><img src="{text.ICON_MINIPOST_IMG}" width="12" height="9" alt="" title="" />{L_POSTED}: {text.TIME}<span class="gen">&nbsp;</span>&nbsp; &nbsp;{L_COMMENT_SUBJECT}: {text.TITLE}</span></td>
		<td align="{S_CONTENT_DIR_RIGHT}">
		<!-- BEGIN AUTH_COMMENT_DELETE -->
		<a href="{text.U_COMMENT_DELETE}"><img src="{text.DELETE_IMG}" alt="{L_COMMENT_DELETE}" title="{L_COMMENT_DELETE}" /></a>
		<!-- END AUTH_COMMENT_DELETE -->
		</td>
	</tr>
	<tr> 
		<td colspan="2"><hr /></td>
	</tr>
	<tr>
		<td colspan="2" valign="top"><span class="postbody">{text.TEXT}</span></td>
	</tr>
	</table></td>
</tr>
<tr>
	<td class="row1" width="150" valign="middle"><span class="nav"><a href="#top" class="nav">{L_BACK_TO_TOP}</a></span></td>
	<td class="row1">&nbsp;</td>
</tr>
<tr> 
	<td class="spaceRow" colspan="2" height="1"><img src="./images/spacer.gif" alt="" title="" width="1" height="1" /></td>
</tr>
<!-- END text -->
<tr>
	<td colspan="2" class="catBottom">&nbsp;</td>
</tr>
</table>

<table width="100%" cellspacing="2" cellpadding="2" align="center">
<tr>
	<td><a href="{U_COMMENT_DO}"><img src="{REPLY_IMG}" alt="{L_COMMENT_ADD}" title="{L_COMMENT_ADD}" align="middle" /></a></td>
</tr>
</table>
<br />