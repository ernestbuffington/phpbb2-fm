{LINKDB_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<script language="JavaScript" type="text/javascript">
<!--
var error_msg = "";
function checkAddForm() 
{
	error_msg = "";

	if(document.form.cat_name.value == "")
	{
		error_msg += "{L_CAT_NAME_FIELD_EMPTY}";
	}
		
	if(error_msg != "")
	{
		alert(error_msg);
		error_msg = "";
		return false;
	}
	else
	{
		return true;
	}
}
// -->
</script>

<h1>{L_CAT_TITLE}</h1>

<p>{L_CAT_EXPLAIN}</p>

<!-- BEGIN linkdb_error -->
<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline">
<tr>
	<td class="row2" align="center">{ERROR}</td>
</tr>
</table>
<br />
<!-- END linkdb_error -->

<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline"><form action="{S_CAT_ACTION}" method="post" name="form" onsubmit="return checkAddForm();">
<tr>
	<th colspan="2" class="thHead">{L_CAT_TITLE}</th>
</tr>
<tr>
	<td width="50%" class="row1"><b>{L_CAT_NAME}:</b><br /><span class="gensmall">{L_CAT_NAME_INFO}</span></td>
	<td class="row2"><input type="text" class="post" size="50" name="cat_name" value="{CAT_NAME}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_CAT_PARENT}:</b><br /><span class="gensmall">{L_CAT_PARENT_INFO}</span></td>
	<td class="row2"><select name="cat_parent">{S_CAT_LIST}</select></td>
</tr>
<tr>
	<td align="center" class="catBottom" colspan="2">{S_HIDDEN_FIELDS}<input class="liteoption" type="submit" value="{L_CAT_TITLE}" name="submit" /></td>
</tr>
</form></table>
