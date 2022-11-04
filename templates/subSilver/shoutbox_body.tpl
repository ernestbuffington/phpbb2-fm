<meta http-equiv="Content-Type" content="text/html; charset={S_CONTENT_ENCODING}" />
<script language="JavaScript" src="templates/js/bbcode.js" type="text/javascript" ></script>

{ERROR_BOX}

<table width="100%" cellpadding="1" cellspacing="0" bgcolor="{T_TR_COLOR1}"><form method="post" action="{U_SHOUTBOX}" name="post" onsubmit="return checkForm(this)">
  <tr>
	<td class="row1" align="center"><iframe bgcolor="{T_TR_COLOR1}" src="{U_SHOUTBOX_VIEW}" width="100%" height="{SHOUTBOX_FRAME_HEIGHT}" frameborder="0" marginheight="0" marginwidth="0" allowtransparency="true"></iframe></td>
  </tr>
  <tr>
	<td height="70" class="row1" align="center" style="line-height: 150%"><span class="gensmall">
 	<!-- BEGIN switch_auth_post -->
	<!-- BEGIN switch_bbcode -->
	<input type="button" class="button" accesskey="b" name="addbbcode0" value=" B " style="font-weight:bold; width: 30px" onClick="bbstyle(0)" />&nbsp;
	<input type="button" class="button" accesskey="i" name="addbbcode2" value=" i " style="font-style:italic; width: 30px" onClick="bbstyle(2)" />&nbsp;
	<input type="button" class="button" accesskey="u" name="addbbcode4" value=" u " style="text-decoration: underline; width: 30px" onClick="bbstyle(4)" />&nbsp;
	<input type="button" class="button" accesskey="q" name="addbbcode6" value="Quote" style="width: 50px" onClick="bbstyle(6)" />&nbsp;
	<a href="javascript:bbstyle(-1)" title="{L_BBCODE_CLOSE_TAGS}" class="gensmall">{L_BBCODE_CLOSE_TAGS}</a> &bull; <a href="{U_MORE_SMILIES}" onclick="window.open('{U_MORE_SMILIES}', '_phpbbsmilies', 'HEIGHT=300,resizable=yes,scrollbars=yes,WIDTH=250');return false;" target="_phpbbsmilies" title="{L_SMILIES}" class="gensmall">{L_SMILIES}</a><br />
	<!-- END switch_bbcode -->
	<input type="text" class="post" name="message" value="{MESSAGE}" size="35" onselect="storeCaret(this);" onclick="storeCaret(this);" onkeyup="storeCaret(this);" /><br /><input type="submit" class="mainoption" value="{L_SUBMIT}" name="shout" onclick="this.onclick = new Function('return false');" />
	<!-- END switch_auth_post -->
	<!-- BEGIN switch_auth_no_post -->
	{L_SHOUTBOX_LOGIN}&nbsp;
	<!-- END switch_auth_no_post -->
	<input type="submit" class="liteoption" value="{L_REFRESH}" name="refresh" /></td>
  </tr>
</form></table>