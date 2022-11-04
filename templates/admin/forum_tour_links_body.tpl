{FORUM_MENU}
</div></td>
<td valign="top" width="78%">

<script language="javascript" type="text/javascript">
<!--
function forum(text) {
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

<table width="100%" cellpadding="4" cellspacing="1" class="forumline">
<tr>
  	<th class="thHead">{L_INDEX}</th>
</tr>
<!-- BEGIN catrow -->
<tr>
  	<td align="left" class="catSides"><a href="javascript:forum('{catrow.CAT_DESC}')" class="cattitle">{catrow.CAT_NAME}</a></td>
</tr>
<!-- BEGIN forumrow -->
<tr>
	<td class="{catrow.forumrow.ROW_CLASS}"><span class="gen"><a href="javascript:forum('{catrow.forumrow.U_FORUM_LINK}')">{catrow.forumrow.SUBJECT}</a></span></td>
</tr>
<!-- END forumrow -->
<!-- END catrow -->
</table>
<br />
<div align="center" class="copyright">Forum Tour 1.1.2 &copy; 2004, {COPYRIGHT_YEAR} <a href="http://www.oxpus.de" target="_blank" class="copyright">OXPUS</a></div>