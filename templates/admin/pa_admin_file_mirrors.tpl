<script language="JavaScript" type="text/javascript">
<!--
	var error_msg = "";
	var check_for_delete = false;
	var delete_mirror = false;
	var add_file = false;
	
	
	function set_check_delete(status)
	{
		check_for_delete = status;
	}
	
	function set_add_file(status)
	{
		add_file = status;
	}
	
	function checkAddForm() 
	{
		if(check_for_delete)
		{
			for (i = 0; i < document.form.length; i++)
			{
				if(document.form.elements[i].checked == true)
				{
					if (confirm('Are you sure you want to delete these files??')) 
					{
						return true;
					}
					else
					{
						return false;
					}
					return true;
				}
			}
			alert('Please Select at least one file.');
			return false;		
		}

		if(add_file)
		{
			if(document.form.new_location.value == "")
			{
				if(error_msg != "")
				{
					error_msg += "\n";
				}
				error_msg += "Please fill the file name field";
			}

			if(document.form.new_userfile.value == "" && document.form.new_download_url.value == "")
			{
				if(error_msg != "")
				{
					error_msg += "\n";
				}
				error_msg += "Please fill the file url field or click browse to upload file from your machine";
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
		return true;
	}
// -->
</script>

<h1>{L_FILE_TITLE}</h1>

<p>{L_FILE_EXPLAIN}</p>
<form action="{S_FILE_ACTION}" method="post" name="form" enctype="multipart/form-data" onsubmit="return checkAddForm();">
<!-- IF ERROR neq '' -->
<table width="100%" cellpadding="4" cellspacing="1" class="forumline" align="center">
  <tr>
	<td class="row2" align="center">{ERROR}</td>
  </tr>
</table>
<br />
<!-- ENDIF -->
<table width="100%" cellpadding="4" cellspacing="1" class="forumline" align="center">
  <tr>
	<th colspan="2" class="thHead">{L_FILE_TITLE}</th>
  </tr>
<!-- BEGIN row -->
  <tr>
	<td width="50%" class="row1">{L_MIRROR_LOCATION}</td>
	<td class="row2"><input type="text" class="post" size="50" name="location[{row.MIRROR_ID}]" value="{row.LOCATION}" /></td>
  </tr>
  <tr>
	<td class="row1"><span class="genmed">{L_FILE_UPLOAD}</span><br><span class="gensmall">{L_FILEINFO_UPLOAD}</span></td>
	<td class="row2">
		<input type="file" size="50" name="userfile[{row.MIRROR_ID}]" maxlength="{FILESIZE}" class="post" />
	</td>
  </tr>
  <tr>
	<td class="row1"><span class="genmed">{L_FILE_DELETE}</span></td>
	<td class="row2"><input type="checkbox" name="mirror_ids[]" value="{row.MIRROR_ID}" /></td>
  </tr>
<!-- IF row.MIRROR_FILE -->  
  <tr>
	<td class="row1"><span class="genmed">{L_UPLOADED_FILE}</span></td>
	<td class="row2"><a href="{row.U_UPLOADED_MIRROR}">{row.MIRROR_FILE}</a></td>
  </tr>
<!-- ENDIF -->
  <tr>
	<td class="row1"><span class="genmed">{L_FILE_URL}</span><br><span class="gensmall">{L_FILE_URL_INFO}</span></td>
	<td class="row2">
		<input type="text" class="post" size="50" name="download_url[{row.MIRROR_ID}]" value="{row.MIRROR_URL}">
	</td>
  </tr>
  <tr>
	<td colspan="2" class="row3" height="2"></td>
  </tr>
<!-- END cat_row -->
<!-- IF ROW_NOT_EMPTY -->  
  <tr>
	<td align="center" class="cat" colspan="2"><input class="mainoption" type="submit" value="{L_MODIFY}" name="modify" onClick="set_check_delete(false); set_add_file(false);" />&nbsp;<input class="mainoption" type="submit" value="{L_DELETE}" name="delete_mirrors" onClick="set_check_delete(true);" /></td>
  </tr>
  <tr>
	<td colspan="2" class="row3" height="2"></td>
  </tr>
<!-- ENDIF -->
  <tr>
	<td class="cat" colspan="2" align="center"><span class="cattitle">{L_ADD_NEW_MIRROR}</span></td>
  </tr>  
  <tr>
	<td width="50%" class="row1">{L_MIRROR_LOCATION}</td>
	<td class="row2"><input type="text" class="post" size="50" name="new_location" /></td>
  </tr>
  <tr>
	<td class="row1"><span class="genmed">{L_FILE_UPLOAD}</span><br><span class="gensmall">{L_FILEINFO_UPLOAD}</span></td>
	<td class="row2">
		<input type="file" size="50" name="new_userfile" maxlength="{FILESIZE}" class="post" />
	</td>
  </tr>  
  <tr>
	<td class="row1"><span class="genmed">{L_FILE_URL}</span><br><span class="gensmall">{L_FILE_URL_INFO}</span></td>
	<td class="row2">
		<input type="text" class="post" size="50" name="new_download_url" />
	</td>
  </tr>
  <tr>
	<td align="center" class="cat" colspan="2">{S_HIDDEN_FIELDS}<input class="mainoption" type="submit" value="{L_ADD_NEW}" name="add_new" onClick="set_check_delete(false); set_add_file(true);" /></td>
  </tr>
</form></table>
<br />
<div align="center" class="copyright">paFileDB phpBB Integration 0.0.9d &amp; <a href="http://www.mx-system.com/" target="_blank" class="copyright">MX Addon 1.2</a> &copy; 2003, {COPYRIGHT_YEAR} Mohd & Haplo</a></div>
