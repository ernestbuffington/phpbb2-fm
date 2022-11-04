<tr>
	<th class="thHead" colspan="2">{L_ADD_EVENT_TYPE}</th>
</tr>
<tr>
	<td class="row2" colspan="3"><span class="gensmall">{L_ADD_EVENT_TYPE_EXPLAIN}</span></td>
</tr>
<tr>
	<td class="row1"><b>{L_NEW} {L_EVENT_TYPE_TEXT}:<br />{L_EVENT_TYPE_COLOR}:</b></td>
	<td class="row2">&nbsp;<input type="text" name="add_event_type_option_text" size="35" maxlength="255" class="post" value="{ADD_EVENT_TYPE_OPTION}" /><br />&nbsp;<input type="text" name="add_event_type_color" size="8" class="post" maxlength="6" value="{ADD_EVENT_TYPE_COLOR}" />&nbsp;&nbsp;<a href="javascript:cp.select(document.forms[0].add_event_type_color,'pick');" name="pick" id="pick"><img src="{I_PICK_COLOR}" width="9" height="9" alt="" title="" /></a>&nbsp;&nbsp;<input type="submit" name="add_event_type_option" value="{L_ADD_CATEGORY_OPTION}" class="liteoption" /></td>
</tr>
<!-- BEGIN event_type_option_rows -->
<tr>
	<td class="row1"><b>{L_EVENT_TYPE_TEXT}:<br />{L_EVENT_TYPE_COLOR}:</b></td>
	<td class="row2">&nbsp;<input type="text" name="event_type_option_text[{event_type_option_rows.S_EVENT_TYPE_OPTION_NUM}]" size="35" class="post" maxlength="255" value="{event_type_option_rows.EVENT_TYPE_OPTION}" /><br />&nbsp;<input type="text" name="event_type_color[{event_type_option_rows.S_EVENT_TYPE_OPTION_NUM}]" size="8" class="post" maxlength="6" value="{event_type_option_rows.EVENT_TYPE_COLOR}" />&nbsp;&nbsp;<input class="post" type="text" size="1" style="background-color: #{event_type_option_rows.EVENT_TYPE_COLOR}" title="{event_type_option_rows.EVENT_TYPE_COLOR}" disabled="disabled" />&nbsp;&nbsp;<input type="submit" name="edit_event_type_option" value="{L_UPDATE_OPTION}" class="liteoption" />&nbsp;&nbsp;<input type="submit" name="del_event_type_option[{event_type_option_rows.S_EVENT_TYPE_OPTION_NUM}]" value="{L_DELETE_OPTION}" class="liteoption" /></td>
</tr>
<!-- END event_type_option_rows -->
<!-- BEGIN switch_event_type_delete_toggle -->
<tr>
	<td class="row1"><b>{L_EVENT_TYPE_DELETE}:</b></td>
	<td class="row2"><input type="checkbox" name="event_type_delete" /> {L_YES}</td>
</tr>
<!-- END switch_event_type_delete_toggle -->
