{ALBUM_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_ALBUM_CAT_TITLE}</h1>

<p>{L_ALBUM_CAT_EXPLAIN}</p>

<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline"><form action="{S_ALBUM_ACTION}" method="post">
<tr>
	<th class="thHead" colspan="2">{L_ALBUM_CAT_TITLE}</th>
</tr>
<!-- BEGIN catrow -->
<tr>
	<td class="row1" height="25"><span class="gen">{catrow.TITLE}<br /></span><span class="gensmall">{catrow.DESC}</span></td>
	<td width="15%" class="row2" align="right" nowrap="nowrap"><a href="{catrow.S_MOVE_UP}">{L_MOVE_UP}</a> <a href="{catrow.S_MOVE_DOWN}">{L_MOVE_DOWN}</a> <a href="{catrow.S_EDIT_ACTION}">{L_EDIT}</a> <a href="{catrow.S_DELETE_ACTION}">{L_DELETE}</a></td>
</tr>
<!-- END catrow -->
<tr>
	<td class="catBottom" align="center" colspan="2"><input type="hidden" value="new" name="mode" /><input name="submit" type="submit" value="{L_CREATE_CATEGORY}" class="liteoption"></td>
</tr>
</form></table>
<br />
<div align="center" class="copyright">Photo Album {ALBUM_VERSION} &copy; 2002, {COPYRIGHT_YEAR} <a href="http://smartor.is-root.com" class="copyright" target="_blank">Smartor</a></div>
