{ATTACH_MENU}{POST_MENU}
{MOD_CP_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<script language="Javascript" type="text/javascript">
function select_switch(status)
{
	for (i = 0; i < document.shadow_list.length; i++)
	{
		document.shadow_list.elements[i].checked = status;
	}
}
</script>

<h1>{L_SHADOW_TITLE}</h1>

<p>{L_SHADOW_EXPLAIN}</p>

{ERROR_BOX}

<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline"><form method="post" name="shadow_list" action="{S_ATTACH_ACTION}">
<tr> 
	<th class="thCornerL">&nbsp;{L_ATTACHMENT}&nbsp;</th>
	<th class="thTop">&nbsp;{L_COMMENT}&nbsp;</th>
	<th class="thCornerR" width="5%">&nbsp;{L_DELETE}&nbsp;</th>
</tr>
<tr> 
	<td class="row2" colspan="3"><span class="gensmall">{L_EXPLAIN_FILE}</span></td>
</tr>
<!-- BEGIN file_shadow_row -->
<tr> 
	<td class="row2" align="center" valign="middle"><span class="postdetails"><a class="postdetails" href="{file_shadow_row.U_ATTACHMENT}" target="_blank">{file_shadow_row.ATTACH_FILENAME}</a></span></td>
	<td class="row1" align="center" valign="middle"><span class="postdetails">{file_shadow_row.ATTACH_COMMENT}</span></td>
	<td class="row2" align="center" valign="middle"><input type="checkbox" name="attach_file_list[]" value="{file_shadow_row.ATTACH_ID}" /></td>
</tr>
<!-- END file_shadow_row -->
<tr> 
	<th class="thCornerL">&nbsp;{L_ATTACHMENT}&nbsp;</th>
	<th class="thTop">&nbsp;{L_COMMENT}&nbsp;</th>
	<th class="thCornerR">&nbsp;{L_DELETE}&nbsp;</th>
</tr>
<tr> 
	<td class="row2" colspan="3"><span class="gensmall">{L_EXPLAIN_ROW}</span></td>
</tr>
<!-- BEGIN table_shadow_row -->
<tr> 
	<td class="row2" align="center" valign="middle"><span class="postdetails">{table_shadow_row.ATTACH_FILENAME}</span></td>
	<td class="row1" align="center" valign="middle"><span class="postdetails">{table_shadow_row.ATTACH_COMMENT}</span></td>
	<td class="row2" align="center" valign="middle"><input type="checkbox" name="attach_id_list[]" value="{table_shadow_row.ATTACH_ID}" /></td>
</tr>
<!-- END table_shadow_row -->
<tr> 
	<td class="catBottom" colspan="3" align="right">
	<input type="button" name="markall" value="{L_MARK_ALL}" onclick="javascript:select_switch(true);" class="liteoption" />
	&nbsp; 
	<input type="button" name="unmarkall" value="{L_UNMARK_ALL}" onclick="javascript:select_switch(false);" class="liteoption" />
	&nbsp;
	<input type="submit" name="submit" class="mainoption" value="{L_DELETE_MARKED}" />
	</td>
</tr>
{S_HIDDEN}</form></table>
