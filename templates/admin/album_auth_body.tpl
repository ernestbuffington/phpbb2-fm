{ALBUM_MENU}
</ul>
</div></td>
<td valign="top" width="78%">
<h1>{L_ALBUM_AUTH_TITLE}</h1>

<p>{L_ALBUM_AUTH_EXPLAIN}</p>

<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline"><form action="{S_ALBUM_ACTION}" method="post">
	<tr>
		<th class="thCornerL">{L_GROUPS}</th>
		<th class="thTop">{L_VIEW}</th>
		<th class="thTop">{L_UPLOAD}</th>
		<th class="thTop">{L_RATE}</th>
		<th class="thTop">{L_COMMENT}</th>
		<th class="thTop">{L_EDIT}</th>
		<th class="thTop">{L_DELETE}</th>
		<th class="thCornerR">{L_IS_MODERATOR}</th>
	</tr>
	<!-- BEGIN grouprow -->
	<tr>
		<td class="row1"><b>{grouprow.GROUP_NAME}</b>&nbsp;</td>
		<td class="row2" align="center"><input name="view[]" type="checkbox" {grouprow.VIEW_CHECKED} value="{grouprow.GROUP_ID}" /></td>
		<td class="row2" align="center"><input name="upload[]" type="checkbox" {grouprow.UPLOAD_CHECKED} value="{grouprow.GROUP_ID}" /></td>
		<td class="row2" align="center"><input name="rate[]" type="checkbox" {grouprow.RATE_CHECKED} value="{grouprow.GROUP_ID}" /></td>
		<td class="row2" align="center"><input name="comment[]" type="checkbox" {grouprow.COMMENT_CHECKED} value="{grouprow.GROUP_ID}" /></td>
		<td class="row2" align="center"><input name="edit[]" type="checkbox" {grouprow.EDIT_CHECKED} value="{grouprow.GROUP_ID}" /></td>
		<td class="row2" align="center"><input name="delete[]" type="checkbox" {grouprow.DELETE_CHECKED} value="{grouprow.GROUP_ID}" /></td>
		<td class="row2" align="center"><input name="moderator[]" type="checkbox" {grouprow.MODERATOR_CHECKED} value="{grouprow.GROUP_ID}" /></td>
	</tr>
	<!-- END grouprow -->
	<tr>
		<td class="catBottom" align="center" colspan="8"><input name="submit" type="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
	</tr>
</form></table>
<br />
<div align="center" class="copyright">Photo Album {ALBUM_VERSION} &copy; 2002, {COPYRIGHT_YEAR} <a href="http://smartor.is-root.com" class="copyright" target="_blank">Smartor</a></div>
