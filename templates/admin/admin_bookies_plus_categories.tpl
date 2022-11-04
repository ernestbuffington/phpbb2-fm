{BOOKIE_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{HEADER}</h1>

<p>{HEADER_EXPLAIN}</p>

<table width="100%" align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>{IMG_NEW_CATEGORY}</td>
</tr>
</table>
<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline"><form action="{URL}" method="post">
<tr>
	<th class="thCornerL" nowrap="nowrap" width="80%">{CATEGORY}</th>
	<th class="thCornerR" nowrap="nowrap" width="15%">{L_ACTION}</th>
</tr>
<!-- BEGIN cats -->
<tr>
	<td class="row1"><span class="forumlink">{cats.CAT}</span></td>
	<td class="row2" align="right" nowrap="nowrap">{cats.EDIT_IMG}
	<!-- BEGIN delete_allow -->
	<input type="checkbox" name="{cats.CHECK_NAME}" value="1" />
	<!-- END delete_allow -->
	</td>
</tr>
<!-- END cats -->
<tr>
	<td colspan="2" class="catBottom" align="right"><input type="submit" name="delete_all" value="{DELETE_ALL}" class="liteoption" />&nbsp;&nbsp;<input type="submit" name="delete_marked" value="{DELETE_MARKED}" class="mainoption" /></td>
</tr>
</form></table>
<br />
<div align="center" class="copyright">Forum Bookmakers {BOOKIE_VERSION} &copy 2004, {COPYRIGHT_YEAR} <a href="http://www.majormod.com" class="copyright" target="_blank">Majorflam</a></div>


