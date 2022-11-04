<table width="100%" cellpadding="4" cellspacing="1" class="forumline" id="posting_body" onMouseOver="th(true);" onMouseOut="th(false);">
<tr>
	<th class="thTop" nowrap="nowrap" colspan="2">{L_EDIT_OPTIONS}</th>
</tr>
<tbody>
<!-- BEGIN poll_option_rows -->
<tr>
	<td class="row1"><b class="gen">{L_POLL_OPTION}:</b></td>
	<td class="row2"><span class="genmed"><input type="text" name="poll_option_text[{poll_option_rows.S_POLL_OPTION_NUM}]" id="poll_option_text[{poll_option_rows.S_POLL_OPTION_NUM}]" size="50" class="post" maxlength="255" value="{poll_option_rows.POLL_OPTION}" /></span> &nbsp;<input type="submit" name="edit_poll_option" value="{L_UPDATE_OPTION}" class="liteoption" onClick="tj('{poll_option_rows.S_POLL_OPTION_NUM}'); return false;" /> <input type="submit" onClick="tk('{poll_option_rows.S_POLL_OPTION_NUM}'); kc(this); return false;" name="del_poll_option[{poll_option_rows.S_POLL_OPTION_NUM}]" value="{L_DELETE_OPTION}" class="liteoption" /></td>
</tr>
<!-- END poll_option_rows -->
</tbody>
<tr>
	<td class="catBottom" colspan="2" align="center"><input class="mainoption" type="button" value=" {L_CANCEL} " onClick="ti(true); return false;" /></td>
</tr>
</table>
