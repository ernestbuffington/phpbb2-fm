{DB_MENU}{UTILS_MENU}{LOG_MENU}{LANG_MENU}
{MOD_CP_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_DATABASE_BACKUP}</h1>

<P>{L_BACKUP_EXPLAIN}</p>

<table width="50%" cellspacing="1" cellpadding="4" align="center" class="forumline"><form method="post" action="{S_DBUTILS_ACTION}">
	<tr>
		<th colspan="2" class="thHead">{L_BACKUP_OPTIONS}</th>
	</tr>
	<tr>
		<td class="row1" width="50%"><b>{L_FULL_BACKUP}:</b></td>
		<td class="row2"><input type="radio" name="backup_type" value="full" checked /></td>
	</tr>
	<tr>
		<td class="row1"><b>{L_STRUCTURE_BACKUP}:</b></td>
		<td class="row2"><input type="radio" name="backup_type" value="structure" /></td>
	</tr>
	<tr>
		<td class="row1"><b>{L_DATA_BACKUP}:</b></td>
		<td class="row2"><input type="radio" name="backup_type" value="data" /></td>
	</tr>
	<tr>
		<td class="row1"><b>{L_ADDITIONAL_TABLES}:</b></td>
		<td class="row2"><input class="post" type="text" name="additional_tables" /></td>
	</tr>
	<tr>
		<td class="row1"><b>{L_GZIP_COMPRESS}:</b></td>
		<td class="row2"><input type="radio" name="gzipcompress" value="0" checked /> {L_NO}&nbsp;&nbsp;<input type="radio" name="gzipcompress" value="1" /> {L_YES}</td>
	</tr>
	<tr>
		<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="backupstart" value="{L_START_BACKUP}" class="mainoption" /></td>
	</tr>
</form></table>
