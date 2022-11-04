<script language="javascript" type="text/javascript">
<!--
function emoticon(text) {
	text = ' ' + text + ' ';
	if (opener.document.forms['post'].message.createTextRange && opener.document.forms['post'].message.caretPos) {
		var caretPos = opener.document.forms['post'].message.caretPos;
		caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? text + ' ' : text;
		opener.document.forms['post'].message.focus();
	} else {
	opener.document.forms['post'].message.value  += text;
	opener.document.forms['post'].message.focus();
	}
}
//-->
</script>

<form action="{S_ACTION}" method="post">
<table width="100%" cellspacing="2" cellpadding="2">
  <tr> 
<!-- BEGIN new_note -->
	<td valign="middle"><a href="{new_note.U_POST_NEW_TOPIC}"><img src="{new_note.POST_IMG}" align="middle" alt="{L_POST_NEW_NOTE}" /></a></td>
<!-- END new_note -->
<!-- BEGIN switch_no_popup -->
	<td width="100%">&nbsp;&nbsp;&nbsp;<a href="{U_INDEX}" class="nav">{L_INDEX}</a></td>
<!-- END switch_no_popup -->
  	<td valign="middle" align="right" class="genmed" nowrap="nowrap">{SEARCH_IN}&nbsp;&nbsp;<input type="text" name="search_string" size="30" maxlength="50">&nbsp;&nbsp;<input type="submit" class="liteoption" value="{L_SEARCH}">&nbsp;&nbsp;{SORT_BY}&nbsp;&nbsp;{SORT_ORDER}&nbsp;&nbsp;<input type="submit" class="mainoption" value="{L_SORT}" /></td>  </tr>
</table>

<!-- BEGIN switch_no_popup -->
{CPL_MENU_OUTPUT}
<!-- END switch_no_popup -->

<div align="center" class="gensmall"><i>{L_FILTER}</i></div>

<table class="forumline" width="100%" cellspacing="1" cellpadding="4">
<tr>
	<th class="thHead">{L_NOTES}</th>
</tr>
<!-- BEGIN postrow -->
<tr> 
	<td class="{postrow.ROW_CLASS}" width="100%" height="28"><table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td width="100%"><span class="postdetails">{L_SUBJECT}:</span> <span class=gen><b>{postrow.POST_SUBJECT}</b></span><br /><span class="postdetails">{L_POSTED}: {postrow.POST_DATE}</span></td>
		<td valign="top" nowrap="nowrap">{postrow.EDIT_IMG} {postrow.DELETE_IMG} <a href="#bottom"><img src="templates/{T_NAV_STYLE}/icon_down.gif" alt="{L_BACK_TO_BOTTOM}" /></a></td>
	</tr>
	<tr>
		<td colspan="2"><hr /></td>
	</tr>
	<tr>
		<td colspan="2" valign="top"><span class="postbody">{postrow.MESSAGE}</span></td>
	</tr>
	</table></td>
</tr>
<tr> 
	<td class="{postrow.ROW_CLASS}" valign="middle"><span class="nav"><a href="#top"><img src="{ICON_UP}" alt="{L_BACK_TO_TOP}" title="{L_BACK_TO_TOP}" /></a></span></td>
</tr>
<tr> 
	<td class="spaceRow" height="1"><img src="images/spacer.gif" alt="" width="1" height="1" /></td>
</tr>
<!-- END postrow -->
<!-- BEGIN no_notes -->
<tr>
	<td class="row1" align="center"><b class="gensmall">{no_notes.L_NONE}</b></td>
</tr>
<!-- END no_notes -->
<!-- BEGIN switch_popup -->
<tr>
	<td class="catBottom" align="center"><input type="submit" name="cancel" class="liteoption" value="{L_CLOSE}" onClick="javascript:window.close()"></td>
</tr>
<!-- END switch_popup -->
</table>

<!-- BEGIN new_note -->
<table width="100%" cellspacing="2" cellpadding="2" align="center">
  <tr> 
	<td valign="middle" nowrap="nowrap"><span class="nav"><a href="{new_note.U_POST_NEW_TOPIC}"><img src="{new_note.POST_IMG}" align="middle" alt="{L_POST_NEW_NOTE}" /></a></span></td>
  </tr>
</table>
<!-- END new_note -->
</form>

<!-- BEGIN switch_no_popup -->
	</td>
</tr>
</table>
<table width="100%" cellspacing="2" align="center"> 
  <tr> 
	<td align="right" valign="middle" nowrap>{JUMPBOX}</td> 
  </tr> 
</table> 
<!-- END switch_no_popup -->
<br />
<div align="center" class="copyright">Personal Notes 1.4.6 &copy; 2003, {COPYRIGHT_YEAR} <a href="http://www.oxpus.de" class="copyright" target="_blank">OXPUS</a></div>
