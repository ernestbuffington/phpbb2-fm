{BOOKIE_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{BUILD_HEADER}</h1>

<p>{BUILD_EXPLAIN}</p>

<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline"><form action="{URL}" method="post">
<tr>
	<th class="thCornerL" nowrap="nowrap">{SELECTION}</th>
	<th class="thTop" nowrap="nowrap">{ODDS}</th>	
</tr>
<!-- BEGIN selections -->
<tr>
	<td class="{selections.ROW_CLASS}" align="center" valign="middle"><textarea name="{selections.SELECTION_NAME}" style="width: 150px"  rows="5" cols="10" class="post">{selections.SELECTION_VALUE}</textarea></td>
	<td class="{selections.ROW_CLASS}" align="center" valign="middle"><span class="gen">{selections.ODDS}</td>
</tr>
<!-- END selections -->
<tr>
	<td class="catBottom" align="center" colspan="2"><input type="submit" name="build_bet" value="{SUBMIT}" class="mainoption" /></td>
</tr>
</form></table>
<br />
{SELECTION_REVIEW}
<br />
<div align="center" class="copyright">Forum Bookmakers {BOOKIE_VERSION} &copy 2004, {COPYRIGHT_YEAR} <a href="http://www.majormod.com" class="copyright" target="_blank">Majorflam</a></div>

