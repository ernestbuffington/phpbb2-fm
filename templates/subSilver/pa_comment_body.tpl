
<table width="100%" cellpadding="3" cellspacing="1" class="forumline">
  <tr>
	<th class="thCornerL">{L_AUTHOR}</th>
	<th class="thCornerR">{L_COMMENTS}</th>
  </tr>
<!-- IF NO_COMMENTS -->
  <tr>
	<td colspan="2" class="row1" align="center"><span class="gen">{L_NO_COMMENTS}</span></td>
  </tr>
<!-- ENDIF -->
<!-- BEGIN text -->
  <tr>
	<td width="150" valign="top" class="row1"><span class="name"><b>{text.POSTER}</b></span><br /><span class="postdetails">{text.POSTER_RANK}<br />{text.RANK_IMAGE}{text.POSTER_AVATAR}<br /><br />{text.POSTER_JOINED}<br />{text.POSTER_POSTS}<br />{text.POSTER_FROM}</span><br />&nbsp;</td>
	<td class="row1" height="28" valign="top"><table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td width="100%" valign="middle"><span class="postdetails"><img src="{text.ICON_MINIPOST_IMG}" width="12" height="9" alt="" title="" />{L_POSTED}: {text.TIME}<span class="gen">&nbsp;</span>&nbsp; &nbsp;{L_COMMENT_SUBJECT}: {text.TITLE}</span></td>
		<td align="right">
		<!-- IF text.AUTH_COMMENT_DELETE -->
		<a href="{text.U_COMMENT_DELETE}"><img src="{text.DELETE_IMG}" alt="{L_COMMENT_DELETE}" title="{L_COMMENT_DELETE}" /></a>
		<!-- ENDIF -->
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
	<td nowrap="nowrap" class="row1" valign="middle"><a href="#top"><img src="{ICON_UP}" alt="{L_BACK_TO_TOP}" title="{L_BACK_TO_TOP}" /></a></td>
	<td class="row1"></td>
  </tr>
  <tr> 
	<td class="spaceRow" colspan="2" height="1"><img src="images/spacer.gif" alt="" width="1" height="1" alt="" title="" /></td>
  </tr>
<!-- END text -->
  <tr>
	<td colspan="2" class="catBottom">&nbsp;</td>
  </tr>
</table>
<!-- IF AUTH_POST -->
<table width="100%" cellspacing="2" cellpadding="2" align="center">
  <tr>
	<td><a href="{U_COMMENT_DO}"><img src="{REPLY_IMG}" alt="{L_COMMENT_ADD}" title="{L_COMMENT_ADD}" /></a></td>
  </tr>
</table>
<!-- ENDIF -->




