{LINKDB_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<script language="JavaScript" type="text/javascript">
<!--
function disableFileMode()
{
	if(document.form.file_mode[0].checked)
	{
		document.form.file_to_cat_id.disabled = true;
	}
	if(document.form.file_mode[1].checked)
	{
		document.form.file_to_cat_id.disabled = false;
	}
	document.form.cat_id.disabled = true;
}

function disableSubcatMode()
{
	if(document.form.subcat_mode[0].checked)
	{
		document.form.subcat_to_cat_id.disabled = true;
	}

	if(document.form.subcat_mode[1].checked)
	{
		document.form.subcat_to_cat_id.disabled = false;
	}	
}
	
function checkDelete()
{
	var error_msg = ""
	if (document.form.file_to_cat_id.value == -1 && document.form.file_mode[1].checked)
	{
		error_msg += "You can't move the link to a category that doesn't allow link on it";			
	}
		
	if(document.form.cat_id.options[document.form.cat_id.selectedIndex].value == document.form.file_to_cat_id.options[document.form.file_to_cat_id.selectedIndex].value && document.form.file_mode[1].checked)
	{
		if(error_msg != "")
		{
			error_msg += "\n";
		}
		error_msg += "{LINK_SAME_CAT}";
	}
		
	if(document.form.cat_id.options[document.form.cat_id.selectedIndex].value == document.form.subcat_to_cat_id.options[document.form.subcat_to_cat_id.selectedIndex].value && document.form.subcat_mode[1].checked)
	{
		if(error_msg != "")
		{
			error_msg += "\n";
		}
		error_msg += "{LINK_MOVE_CAT}";
	}
		
	if(error_msg != "")
	{
		alert(error_msg);
		return false;
	}
	else
	{
		return true;
	}
}
// -->
</script>

<body onLoad="disableFileMode(); disableSubcatMode();">

<form action="{S_DELETE_CAT_ACTION}" method="post" name="form" onsubmit="return checkDelete();">

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

<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline">
<tr>
	<th class="thHead">{L_CAT_TITLE}</th>
</tr>
<tr>
	<td class="row1" align="center" valign="middle">{L_SELECT_CAT} &nbsp;<select name="cat_id">{S_SELECT_CAT}</select></td>
</tr>
<tr>
	<td class="row2">{L_DO_FILE} &nbsp;<input type="radio" name="file_mode" value="delete" checked onClick="disableFileMode();" /> {L_DELETE}&nbsp;&nbsp;<input type="radio" name="file_mode" value="move" onClick="disableFileMode();" /> {L_MOVE}</td>
</tr>
<tr>
	<td class="row2" align="center" valign="middle">{L_MOVE_TO}: &nbsp;<select name="file_to_cat_id">{S_FILE_SELECT_CAT}</select></td>
</tr>
<tr>
	<td class="row2">{L_DO_CAT} &nbsp;<input type="radio" name="subcat_mode" value="delete" checked onClick="disableSubcatMode();" /> {L_DELETE}&nbsp;&nbsp;<input type="radio" name="subcat_mode" value="move" onClick="disableSubcatMode();" /> {L_MOVE}</td>
</tr>
<tr>
	<td class="row2" align="center" valign="middle">{L_MOVE_TO}: &nbsp;<select name="subcat_to_cat_id">{S_SELECT_CAT}</select></td>
</tr>    
<tr>
	<td align="center" class="catBottom">{S_HIDDEN_FIELDS}<input class="mainoption" type="submit" value="{L_CAT_TITLE}" name="submit" /></td>
</tr>
</form></table>
