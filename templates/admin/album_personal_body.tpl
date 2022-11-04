{ALBUM_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_ALBUM_PERSONAL_TITLE}</h1>

<p>{L_ALBUM_PERSONAL_EXPLAIN}</p>

<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline"><form action="{S_ALBUM_ACTION}" method="post">
	<tr>
		<th class="thHead" colspan="2">&nbsp;{L_GROUP_CONTROL}&nbsp;</th>
	</tr>
	<!-- BEGIN grouprow -->
	<tr>
		<td class="row1" width="50%"><b>{grouprow.GROUP_NAME}:</b></td>
		<td class="row2"><input name="private[]" type="checkbox" {grouprow.PRIVATE_CHECKED} value="{grouprow.GROUP_ID}" /></td>
	</tr>
	<!-- END grouprow -->
	<tr>
		<td class="catBottom" align="center" colspan="2"><input name="submit" type="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
	</tr>
</form></table>
<br />
<div align="center" class="copyright">Photo Album {ALBUM_VERSION} &copy; 2002, {COPYRIGHT_YEAR} <a href="http://smartor.is-root.com" class="copyright" target="_blank">Smartor</a></div>
