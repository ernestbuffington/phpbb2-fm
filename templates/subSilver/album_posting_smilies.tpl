<script language="javascript" type="text/javascript">
<!--
function emoticon(text) {
	text = ' ' + text + ' ';
	if (opener.document.commentform.comment.createTextRange && opener.document.commentform.comment.caretPos) {
		var caretPos = opener.document.commentform.comment.caretPos;
		caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? text + ' ' : text;
		opener.document.commentform.comment.focus();
	} else {
	opener.document.commentform.comment.value  += text;
	opener.document.commentform.comment.focus();
	}
}
//-->
</script>

<table width="100%" cellspacing="0" cellpadding="10">
	<tr>
		<td><table width="100%" cellspacing="1" cellpadding="4" class="forumline">
			<tr>
				<th class="thHead">{L_EMOTICONS}</th>
			</tr>
			<tr>
				<td class="row1"><table width="100" cellspacing="0" cellpadding="5">
					<!-- BEGIN smilies_row -->
					<tr align="center" valign="middle"> 
						<!-- BEGIN smilies_col -->
						<td><img src="{smilies_row.smilies_col.SMILEY_IMG}" onmouseover="this.style.cursor='hand';" onclick="emoticon('{smilies_row.smilies_col.SMILEY_CODE}');javascript:window.close();" alt="{smilies_row.smilies_col.SMILEY_DESC}" title="{smilies_row.smilies_col.SMILEY_DESC}" /></a></td> 
						<!-- END smilies_col -->
					</tr>
					<!-- END smilies_row -->
					<!-- BEGIN switch_smilies_extra -->
					<tr align="center"> 
						<td colspan="{S_SMILIES_COLSPAN}"><span  class="nav"><a href="{U_MORE_SMILIES}" onclick="open_window('{U_MORE_SMILIES}', 250, 300);return false" target="_smilies" class="nav">{L_MORE_SMILIES}</a></td>
					</tr>
					<!-- END switch_smilies_extra -->
				</table></td>
			</tr>
			<tr>
				<td class="catBottom" align="center"><a href="javascript:window.close();" class="cattitle">{L_CLOSE_WINDOW}</a></td>
			</tr>
		</table></td>
	</tr>
</table>
