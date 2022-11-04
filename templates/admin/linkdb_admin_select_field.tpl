{LINKDB_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<script language='javascript'>
<!--
function check()
{
	for (i = 0; i < document.field_ids.length; i++)
	{
		if(document.field_ids.elements[i].checked == true)
		{
			return true;
		}
	}
	alert('{L_SELECT_FIELD}');
	return false;
}
-->
</script>

<h1>{L_FIELD_TITLE}</h1>

<p>{L_FIELD_EXPLAIN}</p>

<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline"><form action="{S_FIELD_ACTION}" method="post" name="field_ids">
<tr>
	<th colspan="2" class="thHead">{L_FIELD_TITLE}</th>
</tr>
<!-- BEGIN field_row -->
<tr>
	<td width="3%" class="row1" align="center" valign="middle"><input type="radio" name="field_id" value="{field_row.FIELD_ID}" /></td>
	<td width="97%" class="row1"><b>{field_row.FIELD_NAME}</b><br /><span class="gensmall">{field_row.FIELD_DESC}</span></td>
</tr>
<!-- END field_row -->
<tr>
	<td align="center" class="catBottom" colspan="2">{S_HIDDEN_FIELDS}<input class="liteoption" type="submit" value="{L_FIELD_TITLE}" name="submit" onClick="return check();" /></td>
</tr>
</form></table>