{LINKDB_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<script language='javascript'>
<!--
var add_file = false;
var deletefile = false;
	
function set_add_file(status)
{
	add_file = status;
}

function set_delete_file(status)
{
	deletefile = status;
}
	
function delete_file(theURL) 
{
	if (confirm('{L_DELETE_APPROVE}'))
	{
		window.location.href=theURL;
	}
	else
	{
		alert ('{L_DELETE_CANCEL}');
	} 
}
	
function disable_cat_list()
{
	if(document.form.mode_js.options[document.form.mode_js.selectedIndex].value != 'file_cat')
	{
		document.form.cat_id.disabled = true;
	}
	if(document.form.mode_js.options[document.form.mode_js.selectedIndex].value == 'file_cat')
	{
		document.form.cat_id.disabled = false;
	}
}
	
//
// Taking from the Attachment MOD of Acyd Burn
//
function select(status)
{
	for (i = 0; i < document.file_ids.length; i++)
	{
		document.file_ids.elements[i].checked = status;
	}
}

function check()
{
	if(add_file)
	{
		return true;
	}

	for (i = 0; i < document.file_ids.length; i++)
	{
		if(document.file_ids.elements[i].checked == true)
		{
			if(deletefile)
			{
				if (confirm('{L_DELETE_LINKS}')) 
			  	{
					return true;
			  	}
			  	else
			  	{
					return false;
				}
			}
			return true;
		}
	}
	alert('{L_SELECT_LINKS}');
	return false;
}
-->
</script>

<h1>{L_FILE_TITLE}</h1>

<p>{L_FILE_EXPLAIN}</p>

<body onLoad="disable_cat_list();">

<table width="100%" align="center" cellpadding="2" cellspacing="2"><form method="post" action="{S_FILE_ACTION}" name="form">
<tr>
	<td align="right">{L_MODE} <select name="mode_js" onchange="disable_cat_list();">{S_MODE_SELECT}</select> {L_CATEGORY} {S_CAT_LIST} <input type="submit" class="mainoption" name="go" value="{L_GO}" /></td>
</tr>
</form></table>

<form method="post" action="{S_FILE_ACTION}" name="file_ids" onsubmit="return check();">

<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline">
<tr>
	<td>{S_HIDDEN_FIELDS}<input type="submit" class="liteoption" name="addfile" value="{L_ADD_FILE}" onClick="set_add_file(true); set_delete_file(false);" />&nbsp;&nbsp;<input type="submit" class="liteoption" name="delete" value="{L_DELETE_FILE}" onClick="set_add_file(false); set_delete_file(true);" />&nbsp;&nbsp;<input type="submit" class="liteoption" name="approve" value="{L_APPROVE_FILE}" onClick="set_add_file(false); set_delete_file(false);" />&nbsp;&nbsp;<input type="submit" class="liteoption" name="unapprove" value="{L_UNAPPROVE_FILE}" onClick="set_add_file(false); set_delete_file(false);" /></td>
  </tr>
</table>

<!-- BEGIN file_mode -->
<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline">
<tr>
	<th colspan="5" class="thHead">{file_mode.L_FILE_MODE}</span></th>
</tr>
<!-- BEGIN file_row -->
<tr>
	<td class="{file_mode.file_row.COLOR}" align="center" width="5%"><span class="genmed">{file_mode.file_row.FILE_NUMBER}</span></td>
	<td class="{file_mode.file_row.COLOR}" width="40%"><span class="genmed">{file_mode.file_row.FILE_NAME}</span></td>
	<td class="{file_mode.file_row.COLOR}" align="center" width="10%"><span class="genmed">{file_mode.file_row.FILE_SUBMITED_BY}</span></td>
	<td class="{file_mode.file_row.COLOR}" width="15%" align="right"><a href="{file_mode.file_row.U_FILE_APPROVE}">{file_mode.file_row.APPROVE}</a> <a href="{file_mode.file_row.U_FILE_EDIT}">{EDIT}</a> <a href="javascript:delete_file('{file_mode.file_row.U_FILE_DELETE}')">{DELETE}</a></span></td>
	<td class="{file_mode.file_row.COLOR}" align="center" width="5%"><span class="genmed"><input type="checkbox" name="file_ids[]" value="{file_mode.file_row.FILE_ID}" /></span></td>
</tr>
<!-- END file_row -->
<!-- BEGIN no_data -->
<tr>
	<td class="row1" align="center" colspan="5"><span class="gen">{L_NO_FILES}</span></td>
</tr>
<!-- END no_data -->
</table>
<br />

<!-- END file_mode -->

<table width="100%" cellspacing="2" cellpadding="2">
  <tr>
	<td nowrap="nowrap" class="nav">{PAGINATION}</td>
	<td align="right" nowrap="nowrap" class="nav">{PAGE_NUMBER}</td>
  </tr>
</form></table>
