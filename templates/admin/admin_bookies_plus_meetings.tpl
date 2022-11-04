{BOOKIE_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{HEADER}</h1>

<p>{HEADER_EXPLAIN}</p>

<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline"><form action="{URL}" method="post">
<tr>
	<th class="thCornerL" nowrap="nowrap" width="80%">{MEETING}</th>
	<th class="thTop" nowrap="nowrap" width="10%">{EDIT}</th>
	<th class="thCornerR" nowrap="nowrap" width="10%">{DELETE}</th>
</tr>
<!-- BEGIN meetings -->
<tr>
	<td class="{meetings.ROW_CLASS}">{meetings.MEETING}</td>
	<td class="{meetings.ROW_CLASS}" align="center">{meetings.EDIT_IMG}</td>
	<td class="{meetings.ROW_CLASS}" align="center"><input type="checkbox" name="{meetings.CHECK_NAME}" value="1" /></td>
</tr>
<!-- END meetings -->
<tr>
	<td class="catLeft" align="left"><input type="submit" name="delete_all" value="{DELETE_ALL}" class="mainoption" /></td>
	<td class="catRight" colspan="2" align="right"><input type="submit" name="delete_marked" value="{DELETE_MARKED}" class="mainoption" /></td>
</tr>
</form></table>
<br />
<div align="center" class="copyright">Forum Bookmakers {BOOKIE_VERSION} &copy 2004, {COPYRIGHT_YEAR} <a href="http://www.majormod.com" class="copyright" target="_blank">Majorflam</a></div>


