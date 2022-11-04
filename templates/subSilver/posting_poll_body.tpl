<!-- BEGIN switch_ajax -->
<script language="JavaScript" type="text/javascript">
<!--
config['AJAXed_Poll_counted'] = '{AJAXED_POLL_OPTION_COUNT}';
//-->
</script>
<!-- END switch_ajax -->

<tr>
	<th class="thHead" colspan="2">{L_ADD_A_POLL}</th>
</tr>
<tr>
	<td class="row2" colspan="2"><span class="gensmall">{L_ADD_POLL_EXPLAIN}</span></td>
</tr>
<tr>
	<td class="row1"><b class="gen">{L_POLL_QUESTION}:</b></td>
	<td class="row2"><input type="text" name="poll_title" size="50" maxlength="255" class="post" value="{POLL_TITLE}" /></td>
</tr>
<tbody id="all_polls">
<!-- BEGIN poll_option_rows -->
<tr>
	<td class="row1"><b class="gen">{L_POLL_OPTION}:</b></td>
	<td class="row2"><input type="text" name="poll_option_text[{poll_option_rows.S_POLL_OPTION_NUM}]" size="50" class="post" maxlength="255" value="{poll_option_rows.POLL_OPTION}" /></span> &nbsp;<input type="submit" name="edit_poll_option" value="{L_UPDATE_OPTION}" class="liteoption" /> <input type="submit"{poll_option_rows.POLL_AJAX_JS} name="del_poll_option[{poll_option_rows.S_POLL_OPTION_NUM}]" value="{L_DELETE_OPTION}" class="liteoption" /></td>
</tr>
<!-- END poll_option_rows -->
</tbody>
<tbody id="new_polls"></tbody>
<tr>
	<td class="row1"><b class="gen">{L_POLL_OPTION}:</b></td>
	<td class="row2"><input type="text" name="add_poll_option_text" size="50" maxlength="255" class="post" value="{ADD_POLL_OPTION}" /></span> &nbsp;<input type="submit"{POLL_AJAX_JS2} name="add_poll_option" value="{L_ADD_OPTION}" class="liteoption" /></td>
</tr>
<tr>
	<td class="row1"><b class="gen">{L_POLL_LENGTH}:</b></td>
	<td class="row2"><input type="text" name="poll_length" size="3" maxlength="3" class="post" value="{POLL_LENGTH}" />&nbsp;<span class="gen"><b>{L_DAYS}</b></span>&nbsp;<span class="gensmall">{L_POLL_LENGTH_EXPLAIN}</span></td>
</tr>
<!-- BEGIN switch_poll_delete_toggle -->
<tr>
	<td class="row1"><b class="gen">{L_POLL_DELETE}:</b></td>
	<td class="row2"><input type="checkbox" name="poll_delete" /></td>
</tr>
<!-- END switch_poll_delete_toggle -->