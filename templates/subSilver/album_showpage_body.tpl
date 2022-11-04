<table width="100%" cellspacing="2" cellpadding="2">
<tr>
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> <a class="nav" href="{U_ALBUM}">{L_ALBUM}</a> -> <a class="nav" href="{U_VIEW_CAT}">{CAT_TITLE}</a></td>
  	<form name="search" action="album_search.php">
   	<td align="right" class="gensmall">{L_SEARCH}:&nbsp;
	<select name="mode">
		<option>{L_USERNAME}</option>
		<option>{L_PIC_TITLE}</option>
		<option>{L_PIC_DESC}</option>
	</select>
	&nbsp;{L_THAT_CONTAINS}:&nbsp; 
	<input class="post" type="text" name="search" maxlength="20">&nbsp;<input type="submit" class="liteoption" value="{L_GO}">
	</td>
	</form>
</tr>
</table>

<table width="100%" cellpadding="4" cellspacing="1" class="forumline">
  <tr>
	<th class="thHead" colspan="2"><a href="{U_PREVIOUS}" class="thsort">&laquo;</a>&nbsp; &nbsp;{PIC_TITLE}&nbsp; &nbsp;<a href="{U_NEXT}" class="thsort">&raquo;</a></th>
  </tr>
  <tr>
	<td class="row1" align="center" colspan="2">{U_PIC_L1}<img src="{U_PIC}" vspace="10" alt="{PIC_TITLE}" title="{PIC_TITLE}" />{U_PIC_L2}<br /><span class="genmed">{U_PIC_CLICK}</span></td>
  </tr>
  <tr>
	<td class="row2" colspan="2"><table width="90%" align="center" cellpadding="3" cellspacing="2">
	<tr>
		<td width="25%" align="right"><span class="genmed">{L_POSTER}:</span></td>
		<td><span class="genmed"><b>{POSTER}</b></span></td>
	  </tr>
	  <tr>
		<td valign="top" align="right"><span class="genmed">{L_PIC_TITLE}:</span></td>
		<td valign="top"><b><span class="genmed">{PIC_TITLE}</span></b></td>
	  </tr>
	  <tr>
		<td align="right"><span class="genmed">{L_POSTED}:</span></td>
		<td><b><span class="genmed">{PIC_TIME}</span></b></td>
	  </tr>
	  <tr>
		<td align="right"><span class="genmed">{L_VIEW}:</span></td>
		<td><b><span class="genmed">{PIC_VIEW}</span></b></td>
	  </tr>
	  <!-- BEGIN rate_switch -->
	  <tr>
		<td valign="top" align="right"><span class="genmed">{L_RATING}:</span></td>
		<td><b><span class="genmed">{PIC_RATING}</span></b></td>
	  </tr>
	  <!-- END rate_switch -->
	  <tr>
		<td valign="top" align="right"><span class="genmed">{L_PIC_DESC}:</span></td>
		<td valign="top"><b><span class="genmed">{PIC_DESC}</span></b></td>
	  </tr>
	</table></td>
  </tr>
<!-- BEGIN coment_switcharo_top -->	
  <tr> 
  	<th class="thCornerL" nowrap="nowrap" width="150">{L_AUTHOR}</th>
	<th class="thCornerR" nowrap="nowrap">{L_MESSAGE}</th>
  </tr>
<!-- END coment_switcharo_top -->	
  
<!-- BEGIN commentrow -->
  <tr> 
	<td width="150" valign="top" class="row1"><span class="name"><b>{commentrow.POSTER_NAME}</b></span><br /><span class="postdetails">{commentrow.POSTER_RANK}<br />{commentrow.POSTER_RANK_IMAGE}{commentrow.POSTER_AVATAR}<br /><br />{commentrow.POSTER_JOINED}<br />{commentrow.POSTER_POSTS}<br />{commentrow.POSTER_FROM}</span><br /></td>
	<td class="row1" width="100%" height="28" valign="top"><table width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<td width="100%"><a href="{commentrow.U_MINI_POST}"><img src="{commentrow.MINI_POST_IMG}" alt="{commentrow.L_MINI_POST_ALT}" title="{commentrow.L_MINI_POST_ALT}" /></a><span class="postdetails">{L_POSTED}: {commentrow.TIME}</span></td>
			<td valign="top" nowrap="nowrap">{commentrow.EDIT} {commentrow.DELETE} {commentrow.IP}</td>
		</tr>
		<tr> 
			<td colspan="2"><hr /></td>
		</tr>
		<tr>
			<td colspan="2"><span class="postbody">{commentrow.TEXT}</span></td>
		</tr>
	</table></td>
</tr>
<tr> 
	<td class="row1" width="150" valign="middle"><a href="#top"><img src="templates/{T_NAV_STYLE}/icon_up.gif" alt="{L_BACK_TO_TOP}" title="{L_BACK_TO_TOP}" /></a></td>
	<td class="row1" width="100%" height="28" valign="bottom" nowrap="nowrap">{commentrow.PROFILE_IMG} {commentrow.PM_IMG} {commentrow.EMAIL_IMG} {commentrow.WWW_IMG} {commentrow.AIM_IMG} {commentrow.YIM_IMG} {commentrow.MSNM_IMG} {commentrow.ICQ_IMG}</td>
</tr>
<tr> 
	<td class="spaceRow" colspan="2" height="1"><img src="images/spacer.gif" alt="" width="1" height="1" /></td>
</tr>
<!-- END commentrow -->

<!-- BEGIN switch_comment -->
  <tr>
	<form action="{S_ALBUM_ACTION}" method="post">
	<td class="catBottom" align="center" colspan="2"><span class="gensmall">{L_ORDER}:</span>
	<select name="sort_order">
		<option {SORT_ASC} value='ASC'>{L_ASC}</option>
		<option {SORT_DESC} value='DESC'>{L_DESC}</option>
	</select>&nbsp;<input type="submit" name="submit" value="{L_GO}" class="liteoption" /></td>
	</form>
  </tr>
<!-- END switch_comment -->
</table>

<!-- BEGIN switch_comment -->
<table width="100%" cellspacing="2" cellpadding="2">
  <tr>
	<td width="100%" class="nav">{PAGE_NUMBER}</td>
	<td align="right" nowrap="nowrap" class="nav">{PAGINATION}</td>
  </tr>
</table>
<!-- END switch_comment -->

<script language="JavaScript" type="text/javascript">
<!--
function checkForm() 
{
	formErrors = false;

	if ((document.commentform.comment.value.length < 2) && (document.commentform.rate.value == -1))
	{
		formErrors = "{L_COMMENT_NO_TEXT}";
	}
	else if (document.commentform.comment.value.length > {S_MAX_LENGTH})
	{
		formErrors = "{L_COMMENT_TOO_LONG}";
	}

	if (formErrors) {
		alert(formErrors);
		return false;
	} else {
		return true;
	}
}

function storeCaret(textEl) 
{
	if (textEl.createTextRange) textEl.caretPos = document.selection.createRange().duplicate();
}
        
//how to add smilies
function emotions(text) 
{
        if (document.commentform.comment.createTextRange && document.commentform.comment.caretPos) 
        {
        	var caretPos = document.commentform.comment.caretPos;
        	caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? text + ' ' : text;
                document.commentform.comment.focus();
	} 
        else 
        {
        	document.commentform.comment.value  += text;
                document.commentform.comment.focus();
	}
}
        
// Pops up a window with all smilies
function openAllSmiles()
{
	smiles = window.open('album_showpage.php?mode=smilies', '_phpbbsmilies', 'HEIGHT=600,resizable=yes,scrollbars=yes,WIDTH=470');
	smiles.focus();
	return true;
}
// -->
</script>

<!-- BEGIN switch_comment_post -->
<form name="commentform" action="{S_ALBUM_ACTION}" method="post" onsubmit="return checkForm();">
<table width="100%" cellpadding="4" cellspacing="1" class="forumline">
  <tr>
	<th class="thHead" colspan="3">{L_POST_YOUR_COMMENT}</th>
  </tr>
  <!-- BEGIN logout -->
  <tr>
	<td class="row1" width="30%" height="28"><span class="genmed">{L_USERNAME}</span></td>
	<td class="row2" colspan="3"><input class="post" type="text" name="comment_username" size="32" maxlength="32" /></td>
  </tr>
  <!-- END logout -->
  <tr>
	<td class="row1" valign="top" width="20%"><span class="genmed">{L_MESSAGE}<br />{L_MAX_LENGTH}: <b>{S_MAX_LENGTH}</b></span></td>
	<td class="row2" valign="top"><span class="genmed">{L_PLEASE_RATE_IT}:&nbsp;</span>
	<select name="rate">
		<option value="-1">{S_RATE_MSG}</option>
		<!-- BEGIN rate_row -->
		<option value="{switch_comment_post.rate_row.POINT}">{switch_comment_post.rate_row.POINT}</option>
		<!-- END rate_row -->
	</select>
	<br /><textarea name="comment" class="post" cols="60" rows="9" wrap='virtual' class='post' onselect='storeCaret(this);' onclick='storeCaret(this);' onkeyup='storeCaret(this);'>{S_MESSAGE}</textarea></td>
	<td class="row2" valign="middle" width="40%">  
	<table align="center" cellspacing="0" cellpadding="5">
        <tr>
		<!-- BEGIN smilies -->
		<td><img src="{switch_comment_post.smilies.URL}" onmouseover="this.style.cursor='hand';" onclick="emotions(' {switch_comment_post.smilies.CODE} ');" alt="{switch_comment_post.smilies.DESC}" title="{switch_comment_post.smilies.DESC}" /></td>
		<!-- BEGIN new_col -->
		</tr><tr>
		<!-- END new_col -->
		<!-- END smilies -->
		</td>
	  </tr>
	 </table>	       
	<br /><div align="center"><input type="button" class="liteoption" name="SmilesButt" value="{L_ALL_SMILIES}" onclick="openAllSmiles();">
	 </td>
  </tr>
  <tr>
	<td class="catBottom" align="center" colspan="3"><input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" /></td>
  </tr>
</table>
</form>
<!-- END switch_comment_post -->
<br />
<div align="center" class="copyright">Photo Album {ALBUM_VERSION} &copy; 2002, {COPYRIGHT_YEAR} <a href="http://smartor.is-root.com" class="copyright" target="_blank">Smartor</a></div>

