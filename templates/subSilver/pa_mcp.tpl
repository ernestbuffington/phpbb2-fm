<script language="Javascript" type="text/javascript">
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
       if (confirm('Are you sure you want to delete this file??')) 
	   {
          window.location.href=theURL;
       }
       else
	   {
          alert ('No Action has been taken.');
       } 
    }
	
	function disable_cat_list()
	{
		if(document.form.mode_js.options[document.form.mode_js.selectedIndex].value != 'file_cat')
		{
			document.form.cat_js_id.disabled = true;
		}
		if(document.form.mode_js.options[document.form.mode_js.selectedIndex].value == 'file_cat')
		{
			document.form.cat_js_id.disabled = false;
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
			       if (confirm('Are you sure you want to delete these files??')) 
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
		alert('Please Select at least one file.');
		return false;
	}
	-->
</script>
<!-- INCLUDE pa_header.tpl -->

<h1>{L_MCP_TITLE}</h1>

<p>{L_MCP_EXPLAIN}</p>

<body onLoad="disable_cat_list();">
<table align="center" width="100%" cellpadding="2" cellspacing="2"><form method="post" action="{S_FILE_ACTION}" name="form">
  <tr>
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> <a href="{U_DOWNLOAD}" class="nav">{DOWNLOAD}</a> -> {L_MCP_TITLE}</td>
	<td align="right" class="genmed">{L_MODE}:&nbsp;<select name="mode_js" onchange="disable_cat_list();">{S_MODE_SELECT}</select>&nbsp;&nbsp;{L_CATEGORY}:&nbsp;{S_CAT_LIST}&nbsp;&nbsp; {S_HIDDEN_FIELDS}<input type="submit" class="liteoption" name="go" value="{L_GO}" /><td>
  </tr>
</form>
<form method="post" action="{S_FILE_ACTION}" name="file_ids" onsubmit="return check();">
</table>
<!-- BEGIN file_mode -->
<table align="center" width="100%" cellpadding="4" cellspacing="1" class="forumline">
  <tr>
	<th colspan="4" class="thHead">{file_mode.L_FILE_MODE}</span></th>
  </tr>
  <!-- IF file_mode.DATA -->
  <!-- BEGIN file_row -->
  <tr>
	<td class="row1" align="center" width="5%"><span class="genmed">{file_mode.file_row.FILE_NUMBER}</span></td>
	<td class="row1" width="100%"><span class="genmed">{file_mode.file_row.FILE_NAME}</span></td>
	<td class="row1" align="center" width="200" nowrap="nowrap"><span class="gen"><a href="{file_mode.file_row.U_FILE_EDIT}">{L_EDIT}</a> | <a href="javascript:delete_file('{file_mode.file_row.U_FILE_DELETE}')">{L_DELETE}</a> | <a href="{file_mode.file_row.U_FILE_APPROVE}">{file_mode.file_row.L_APPROVE}</a></span></td>
	<td class="row1" align="center" width="1%"><span class="genmed"><input type="checkbox" name="file_ids[]" value="{file_mode.file_row.FILE_ID}" /></span></td>
  </tr>
   <!-- END file_row -->
   <!-- ELSE -->
  <tr>
	  <td colspan="4" class="row1" align="center"><span class="gen">{L_NO_FILES}</span></td>
  </tr>
   <!-- ENDIF -->
</table>
<br />
<!-- END file_mode -->
<table align="center" width="100%" cellpadding="4" cellspacing="1" class="forumline">
  <tr>
	<td class="catBottom" align="center">{S_HIDDEN_FIELDS}<input type="submit" class="liteoption" name="approve" value="{L_APPROVE_FILE}" onClick="set_add_file(false); set_delete_file(false);" />&nbsp;&nbsp;<input type="submit" class="liteoption" name="unapprove" value="{L_UNAPPROVE_FILE}" onClick="set_add_file(false); set_delete_file(false);" /></td>
  </tr>
</form></table>
<table width="100%" cellspacing="2" cellpadding="2">
  <tr>
	<td nowrap="nowrap" class="nav">{PAGINATION}</td>
	<td align="right" nowrap="nowrap" class="nav">{PAGE_NUMBER}</td>
  </tr>
</table>

<!-- INCLUDE pa_footer.tpl --> 