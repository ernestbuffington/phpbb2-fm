{BOOKIE_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{SELECTION}</h1>

<p></p>

<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline"><form action="{URL}" method="post">
<tr>
	<th class="thHead" nowrap="nowrap" colspan="2">{SELECTION}</th>
</tr>
<tr>
	<td class="catLeft"><span class="cattitle">{THIS_TEMPL_NAME}</span></td>
</tr>
<!-- BEGIN templ_selections -->
<tr>
	<td class="{templ_selections.ROW_CLASS}" align="center" colspan="2">
	<table cellpadding="0" cellspacing="0">
	<tr>
		<td align="right" valign="top" class="gen"><b>{templ_selections.NUMBER}.</b> </td>
		<td align="left"><textarea name="{templ_selections.SELECTION_NAME}" rows="5" cols="10" class="post" />{templ_selections.SELECTION_VALUE}</textarea></td>
	</tr>
	</table></td>
</tr>
<!-- END templ_selections -->
<tr>
	<td class="catBottom" align="center" colspan="2"><input type="submit" name="build_templ" value="{SUBMIT}" class="mainoption" /></td>
</tr>
</form></table>
<br />
<div align="center" class="copyright">Forum Bookmakers {BOOKIE_VERSION} &copy 2004, {COPYRIGHT_YEAR} <a href="http://www.majormod.com" class="copyright" target="_blank">Majorflam</a></div>

