{BOOKIE_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{EDIT_HEADER}</h1>

<p>{EDIT_HEADER_EXPLAIN}</p>

<table width="100%" cellpadding="4" cellspacing="1" class="forumline"><form action="{URL}" method="post">
<tr>
	<th class="thCornerL" nowrap="nowrap">{TIME}</th>
	<th class="thTop" nowrap="nowrap">{MEETING}</th>
	<th class="thTop" nowrap="nowrap">{SELECTION}</th>
	<th class="thCornerR" nowrap="nowrap">{ODDS}</th>	
</tr>
<!-- BEGIN editbet -->
<tr>
	<td class="{editbet.ROW_CLASS}" align="center" valign="middle"><span class="gen">{editbet.CURRENT_TIME}</span></td>
	<td class="{editbet.ROW_CLASS}" align="center" valign="middle"><span class="gen">{editbet.MEETING}</span></td>
	<td class="{editbet.ROW_CLASS}" align="center" valign="middle"><span class="gen">{editbet.SELECTION}</span></td>
	<td class="{editbet.ROW_CLASS}" align="center" valign="middle"><span class="gen">{editbet.ODDS}</span></td>
</tr>
<!-- END editbet -->
<tr>
	<td class="catBottom" align="center" colspan="4"><input type="submit" name="deletebet" value="{SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="submit" name="cancel" value="{CANCEL}" class="liteoption" /></td>
</tr>
</form></table>
<br />
<div align="center" class="copyright">Forum Bookmakers {BOOKIE_VERSION} &copy 2004, {COPYRIGHT_YEAR} <a href="http://www.majormod.com" class="copyright" target="_blank">Majorflam</a></div>

