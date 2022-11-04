{STATS_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_CONFIG_TITLE}</h1>

<p>{L_CONFIG_EXPLAIN}</p>

{MESSAGE}

{ERROR_BOX}

<table width="100%" cellpadding="4" cellspacing="1" align="center" class="forumline"><form action="{S_ACTION}" method="post">
<tr>
	<th class="thHead" colspan="2">{L_CONFIG_TITLE}</th>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_STAT_INDEX}:</b><br /><span class="gensmall">{L_STAT_INDEX_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="5" maxlength="10" name="stat_index" value="{STAT_INDEX}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_STAT_ALL_OR_ONE}:</b><br /><span class="gensmall">{L_STAT_ALL_OR_ONE_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="5" maxlength="10" name="stat_all_or_one" value="{STAT_ALL_OR_ONE}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_RETURN_LIMIT}:</b><br /><span class="gensmall">{L_RETURN_LIMIT_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="5" maxlength="10" name="return_limit" value="{RETURN_LIMIT}" /></td>
</tr>
<tr>
	<th class="thHead" colspan="2">{L_RESET_SETTINGS_TITLE}</th>
</tr>
<tr>
	<td class="row1"><b>{L_RESET_CACHE}:</b><br /><span class="gensmall">{L_RESET_CACHE_EXPLAIN}</span></td>
	<td class="row2"><input type="checkbox" name="reset_cache" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_RESET_VIEW_COUNT}:</b><br /><span class="gensmall">{L_RESET_VIEW_COUNT_EXPLAIN}</span></td>
	<td class="row2"><input type="checkbox" name="reset_view_count" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_RESET_INSTALL_DATE}:</b><br /><span class="gensmall">{L_RESET_INSTALL_DATE_EXPLAIN}</span></td>
	<td class="row2"><input type="checkbox" name="reset_install_date" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_PURGE_MODULE_DIRECTORY}:</b><br /><span class="gensmall">{L_PURGE_MODULE_DIRECTORY_EXPLAIN}</span></td>
	<td class="row2"><input type="checkbox" name="purge_module_directory" /></td>
</tr>
<tr>
	<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</form></table>
<br />
<div align="center" class="copyright">{INSTALL_INFO}<br />{VIEWED_INFO}</div>
