{ALBUM_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_ALBUM_AUTH_TITLE}</h1>

<p>{L_ALBUM_AUTH_EXPLAIN}</p>

<table align="center" cellpadding="4" cellspacing="1" class="forumline"><form action="{S_ALBUM_ACTION}" method="post">
	<tr>
		<th class="thHead">{L_SELECT_CAT}</th>
	</tr>
	<tr>
		<td class="row1">
		<select name="cat_id">
		<!-- BEGIN catrow -->
			<option value="{catrow.CAT_ID}">{catrow.CAT_TITLE}</option>
		<!-- END catrow -->
		</select>
		</td>
	</tr>
	<tr>
		<td class="catBottom" align="center"><input name="submit" type="submit" value="{L_LOOK_UP_CAT}" class="mainoption" /></td>
	</tr>
</form></table>
<br />
<div align="center" class="copyright">Photo Album {ALBUM_VERSION} &copy; 2002, {COPYRIGHT_YEAR} <a href="http://smartor.is-root.com" class="copyright" target="_blank">Smartor</a></div>

