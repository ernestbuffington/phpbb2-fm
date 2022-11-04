{UTILS_MENU}{LOG_MENU}{DB_MENU}{LANG_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<script language="Javascript" type="text/javascript">
function select_switch(status)
{
	for (i = 0; i < document.table_list.length; i++)
	{
		document.table_list.elements[i].checked = status;
	}
}
</script>

<h1>{HEADER}</h1>

<p>{L_EXPLAIN}</p>

<table width="100%" align="center" cellpadding="2" cellspacing="2">
<tr>
	<td width="50%" valign="top" align="center">
	<table cellspacing="1" cellpadding="4" align="center" class="forumline"><form action="{CRON_ACTION}" method="post" name="tablesForm">
	<tr>
		<th class="thHead" colspan="2">{L_CRON_TITLE}</th>
	</tr>
	<tr>
		<td class="row1" width="50%"><b>{L_ENABLE_CRON}:</b></td>
		<td class="row2"><input type="radio" name="enable_optimize_cron" value="1" {S_ENABLE_CRON_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="enable_optimize_cron" value="0" {S_ENABLE_CRON_NO} /> {L_NO}</td>
	</tr>
	<tr>
		<td class="row1"><b>{L_CRON_EVERY}:</b></td>
		<td class="row2">
		<!-- BEGIN sel_cron_every -->
		<select name="cron_every">
			<option value="1800" {sel_cron_every.30MINUTES}>30 Minutes</option>
			<option value="3600" {sel_cron_every.HOUR}>Hour</option>
			<option value="14400" {sel_cron_every.4HOURS}>4 Hours</option>
			<option value="28800" {sel_cron_every.8HOURS}>8 Hours</option>
			<option value="86400" {sel_cron_every.DAY}>Day</option>
			<option value="259200" {sel_cron_every.3DAYS}>3 Days</option>
			<option value="604800" {sel_cron_every.WEEK}>Week</option>
			<option value="1296000" {sel_cron_every.2WEEKS}>2 Weeks</option>
			<option value="2592000" {sel_cron_every.MONTH}>Month</option>
		</select>
		<!-- END sel_cron_every -->
		</td>
	</tr>
	<!-- BEGIN switch_ip_logger -->
	<tr>
		<td class="row1"><b>{L_EMPTY_TABLES}:</b></td>
		<td class="row2"><input type="radio" name="empty_table" value="1" {S_EMPTY_TABLE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="empty_table" value="0" {S_EMPTY_TABLE_NO} /> {L_NO}</td>
	</tr>
	<!-- END switch_ip_logger -->
	<tr>
		<td class="row1"><b>{L_NEXT_CRON_ACTION}:<br />{L_PERFORMED_CRON}:</b></td>
		<td class="row2">{NEXT_CRON}<br />{PERFORMED_CRON}</td>
	</tr>
	<tr>
		<td class="row1"><b>{L_SHOW_TABLE}:</b></td>
		<td class="row2"><input class="post" type="text" maxlength="255" name="only_tables" value="{S_ONLY_TABLES_VALUE}" /></td>
	</tr>
	<tr>	
		<td class="catBottom" align="center" colspan="2"><input type="submit" name="cron" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
	</tr>	
	</form></table>
	</td>
	<td width="50%" valign="top" align="center">
	<table cellspacing="1" cellpadding="4" align="center" class="forumline"><form action="{SQL_ACTION}" method="post">
	<tr>
		<th class="thHead">{QUERY_TITLE}</th>
	</tr>
	<tr>
		<td class="row1"><textarea name="this_query" rows="8" cols="50" class="post"></textarea></td>
	</tr>
	<tr>
		<td align="center" class="catBottom"><input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
	</tr>
	</form></table>
	</td>
</tr>
</table>
<br />
	
<!-- BEGIN switch_submit_result -->
<table width="95%" align="center" cellspacing="1" cellpadding="4" class="forumline">
<tr>
	<th colspan="{SUBMIT_RESULT_FIELD_COUNT}" class="thHead">{SUBMIT_RESULT_QUERY}</td>
</tr>
<tr>
	<!-- BEGIN submit_result_fields -->
	<td align="center" class="cat"><span class="cattitle">{switch_submit_result.submit_result_fields.SUBMIT_RESULT_FIELD_NAME}</span></td>
	<!-- END submit_result_fields -->
</tr>
<!-- BEGIN submit_result_data -->
<tr>
	<!-- BEGIN submit_result_data_row -->
	<td align="center" class="row1">{switch_submit_result.submit_result_data.submit_result_data_row.SUBMIT_RESULT_DATA}</td>
	<!-- END submit_result_data_row -->
</tr>
<!-- END submit_result_data -->
</table>
<br />
<!-- END switch_submit_result -->

<!-- BEGIN switch_table_browse -->
<table width="95%" align="center" cellspacing="1" cellpadding="4" class="forumline">
<!-- BEGIN table_browse_menu -->
<tr>
	<td colspan="{switch_table_browse.table_browse_menu.BROWSE_MENU_COLSPAN}" align="right" class="catHead"><span class="nav"><a href="{switch_table_browse.table_browse_menu.FIRST_PAGE}" class="nav">{switch_table_browse.table_browse_menu.L_FIRST_PAGE}</a> | <a href="{switch_table_browse.table_browse_menu.NEXT_PAGE}" class="nav">{switch_table_browse.table_browse_menu.L_NEXT_PAGE}</a> | <a href="{switch_table_browse.table_browse_menu.PREVIOUS_PAGE}" class="nav">{switch_table_browse.table_browse_menu.L_PREVIOUS_PAGE}</a> | <a href="{switch_table_browse.table_browse_menu.SORT_ASC}" class="nav">{switch_table_browse.table_browse_menu.L_SORT_ASC}</a>	| <a href="{switch_table_browse.table_browse_menu.SORT_DESC}" class="nav">{switch_table_browse.table_browse_menu.L_SORT_DESC}</a></span></td>
</tr>
<!-- END table_browse_menu -->
<tr>
	<th class="thCornerL">&nbsp;{L_ACTION}&nbsp;</th>
	<!-- BEGIN table_browse_fields -->
	<th class="thTop">&nbsp;<a href="{switch_table_browse.table_browse_fields.TABLE_BROWSE_FIELD_ORDER}" class="thsort">{switch_table_browse.table_browse_fields.TABLE_BROWSE_FIELD_NAME}</a>&nbsp;</th>
	<!-- END table_browse_fields -->
</tr>
<!-- BEGIN table_browse_data -->
<tr>
	<td class="{switch_table_browse.table_browse_data.ROW_CLASS}"><span class="gensmall"><a href="{switch_table_browse.table_browse_data.TABLE_BROWSE_DELETE}">{L_TABLE_BROWSE_DELETE}</a>&nbsp;</span></td>
	<!-- BEGIN table_browse_data_field -->
	<td class="{switch_table_browse.table_browse_data.table_browse_data_field.ROW_CLASS}"><span class="gensmall">{switch_table_browse.table_browse_data.table_browse_data_field.TABLE_BROWSE_DATA}&nbsp;</span></td>
	<!-- END table_browse_data_field -->
</tr>
<!-- END table_browse_data -->
</table>
<br />
<!-- END switch_table_browse -->

<!-- BEGIN switch_table_structure -->
<table width="100%" align="center" cellspacing="1" cellpadding="4" class="forumline">
<tr>
	<td colspan="7" align="center" class="catHead"><span class="cattitle">{L_TABLE_STRUCTURE_TABLENAME}</span></td>
</tr>
<tr>
	<th class="thCornerL">&nbsp;{L_ACTION}&nbsp;</td>
	<th class="thTop">&nbsp;{L_TABLE_STRUCTURE_FIELD}&nbsp;</td>
	<th class="thTop">&nbsp;{L_TABLE_STRUCTURE_TYPE}&nbsp;</td>
	<th class="thTop">&nbsp;{L_TABLE_STRUCTURE_NULL}&nbsp;</td>
	<th class="thTop">&nbsp;{L_TABLE_STRUCTURE_KEY}&nbsp;</td>
	<th class="thTop">&nbsp;{L_TABLE_STRUCTURE_DEFAULT}&nbsp;</td>
	<th class="thCornerR">&nbsp;{L_TABLE_STRUCTURE_EXTRA}&nbsp;</td>
</tr>
<!-- BEGIN actual_table_structure -->
<tr>
	<td class="row1"><a href="{switch_table_structure.actual_table_structure.TABLE_STRUCTURE_DROP}">{L_TABLE_STRUCTURE_DROP}</a></td>
	<td class="row1">{switch_table_structure.actual_table_structure.TABLE_STRUCTURE_FIELD}</td>
	<td class="row1">{switch_table_structure.actual_table_structure.TABLE_STRUCTURE_TYPE}</td>
	<td class="row1">{switch_table_structure.actual_table_structure.TABLE_STRUCTURE_NULL}</td>
	<td class="row1">{switch_table_structure.actual_table_structure.TABLE_STRUCTURE_KEY}&</td>
	<td class="row1">{switch_table_structure.actual_table_structure.TABLE_STRUCTURE_DEFAULT}</td>
	<td class="row1">{switch_table_structure.actual_table_structure.TABLE_STRUCTURE_EXTRA}</td>
</tr>
<!-- END actual_table_structure -->
</table>
<br />
<!-- END switch_table_structure -->

<table width="100%" cellspacing="2" cellpadding="2" align="center"><form action="{SQL_ACTION}" method="post" name="table_list">
<tr>
	<td>{L_WITH_SELECTED_WORD}: <select name="with_selected">
		<option value=optimize>{L_WITH_SELECTED_OPTIMIZE}
		<option value=repair>{L_WITH_SELECTED_REPAIR}
		<option value=empty>{L_WITH_SELECTED_EMPTY}
		<option value=drop>{L_WITH_SELECTED_DROP}
	</select> 
	&nbsp;
        <input type="button" name="markall" value="{L_MARK_ALL}" onclick="javascript:select_switch(true);" class="liteoption" />
	&nbsp;
	<input type="button" name="unmarkall" value="{L_UNMARK_ALL}" onclick="javascript:select_switch(false);" class="liteoption" />
	&nbsp;
        <input type="submit" name="go_with_selected" value="{L_SUBMIT}" class="mainoption" /></td>
	<td align="right"><input type="submit" name="repairall" value="{REPAIR_ALL_BUTTON}" class="liteoption" />&nbsp;&nbsp;<input type="submit" name="optimizeall" value="{OPTIMIZE_ALL_BUTTON}" class="liteoption" /></td>
</tr>
</table>
<table width="100%" align="center" cellspacing="1" cellpadding="4" class="forumline">
<tr>
	<td colspan="7" align="center" class="catHead"><span class="cattitle">{TABLE_TITLE}</span></td>
</tr>
<tr>
	<th colspan="2" class="thCornerL">&nbsp;{L_TABLE_NAME}&nbsp;</th>
	<th class="thTop">&nbsp;{L_TABLE_ACTIONS}&nbsp;</th>
	<th class="thTop">&nbsp;{L_TABLE_TYPE}&nbsp;</th>
	<th class="thTop">&nbsp;{L_TABLE_ROWS}&nbsp;</td>
	<th class="thTop">&nbsp;{L_TABLE_DATA_LENGTH}&nbsp;</th>
	<th class="thCornerR">&nbsp;{L_TABLE_OPTIMIZATION_LEVEL}&nbsp;</th>
</tr>
<!-- BEGIN table_list -->
<tr>
	<td class="{table_list.ROW_CLASS}" align="center"><input type="checkbox" name="with_selected_table_list[]" value="{table_list.TABLE_NAME}"></td>
	<td class="{table_list.ROW_CLASS}"><span class="gensmall">{table_list.TABLE_NAME}</span></td>
	<td class="{table_list.ROW_CLASS}" nowrap="nowrap"><span class="gensmall"><a href="{table_list.TABLE_STRUCTURE}">{L_TABLE_STRUCTURE}</a> | <a href="{table_list.TABLE_BROWSE}">{L_TABLE_BROWSE}</a> | <a href="{table_list.TABLE_OPTIMIZE}">{L_WITH_SELECTED_OPTIMIZE}</a> | <a href="{table_list.TABLE_REPAIR}">{L_TABLE_REPAIR}</a> | <a href="{table_list.TABLE_EMPTY}">{L_TABLE_EMPTY}</a> | <a href="{table_list.TABLE_DROP}">{L_TABLE_DROP}</a></span></td>
	<td class="{table_list.ROW_CLASS}">{table_list.TABLE_TYPE}</td>
	<td class="{table_list.ROW_CLASS}" align="right">{table_list.TABLE_ROWS}</td>
	<td class="{table_list.ROW_CLASS}" align="right">{table_list.TABLE_DATA_LENGTH}</td>
	<td class="{table_list.ROW_CLASS}" align="right">{table_list.TABLE_OPTIMIZATION_LEVEL}%</td>
</tr>
<!-- END table_list -->
</form></table>
<br />
<div align="center" class="copyright">phpBBMyAdmin 0.3.5 &copy; 2003, {COPYRIGHT_YEAR} <a href="http://www.phpbbsupport.co.uk" target="_blank" class="copyright">Armin Altorffer</a></div>