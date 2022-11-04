{DB_MENU}{UTILS_MENU}{LOG_MENU}{LANG_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_DATABASE_RESTORE}</h1>

<P>{L_RESTORE_EXPLAIN}</p>

<table width="50%" cellspacing="1" cellpadding="4" align="center" class="forumline"><form enctype="multipart/form-data" method="post" action="{S_DBUTILS_ACTION}">
<tr>
	<th class="thHead">{L_SELECT_FILE}</th>
</tr>
<tr>
	<td class="row1" align="center"><input class="post" type="file" size="40" name="backup_file"></td>
</tr>
<tr>
	<td class="catBottom" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="restore_start" value="{L_START_RESTORE}" class="mainoption" /></td>
</tr>
</form></table>
