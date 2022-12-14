<!-- INCLUDE pa_header.tpl -->
<script language="JavaScript" type="text/javascript">
<!--
	var error_msg = "";
	function checkAddForm() 
	{
		error_msg = "";
		if (document.form.cat_id.value == -1)
		{
			error_msg = "You can't add a file to a category that does not allow files";
		}

		if(document.form.name.value == "")
		{
			if(error_msg != "")
			{
				error_msg += "\n";
			}
			error_msg += "Please fill the file name field";
		}
		
		if(document.form.long_desc.value == "")
		{
			if(error_msg != "")
			{
				error_msg += "\n";
			}
			error_msg += "Please fill the file long description field";
		}

		<!-- IF MODE eq 'ADD' -->
		if(document.form.userfile.value == "" && document.form.download_url.value == "")
		{
			if(error_msg != "")
			{
				error_msg += "\n";
			}
			error_msg += "Please fill the file url field or click browse to upload file from your machine";
		}
		<!-- ENDIF -->
		
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

<table width="100%" cellpadding="2" cellspacing="2">
<tr>
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> <a href="{U_DOWNLOAD}" class="nav">{DOWNLOAD}</a> -> {L_UPLOAD}</td>
</tr>
</table>
<table width="100%" cellpadding="4" cellspacing="1" class="forumline"><form enctype="multipart/form-data" action="{S_ADD_FILE_ACTION}" method="post" name="form" onsubmit="return checkAddForm();">
<tr>
	<th colspan="2" class="thHead">{L_FILE_TITLE}</th>
</tr>
<tr> 
	<td class="row2" colspan="2"><span class="gensmall">{L_ITEMS_REQUIRED}</span></td>
</tr>
<tr>
	<td width="38%" class="row1"><b>{L_FILE_NAME}: *</b><br /><span class="gensmall">{L_FILE_NAME_INFO}</span></td>
	<td class="row2"><input type="text" class="post" size="40" style="width: 200px" name="name" value="{FILE_NAME}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_FILE_LONG_DESC}: *</b><br /><span class="gensmall">{L_FILE_LONG_DESC_INFO}</span></td>
	<td class="row2"><textarea class="post" rows="4" name="long_desc" cols="40" style="width: 200px">{FILE_LONG_DESC}</textarea></td>
</tr>
<tr>
	<td class="row1"><b>{L_FILE_SHORT_DESC}:</b><br /><span class="gensmall">{L_FILE_SHORT_DESC_INFO}</span></td>
	<td class="row2"><input type="text" class="post" size="40" style="width: 200px" name="short_desc" value="{FILE_DESC}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_FILE_AUTHOR}:</b><br /><span class="gensmall">{L_FILE_AUTHOR_INFO}</span></td>
	<td class="row2"><input type="text" class="post" size="40" style="width: 200px" name="author" value="{FILE_AUTHOR}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_FILE_VERSION}:</b><br /><span class="gensmall">{L_FILE_VERSION_INFO}</span></td>
	<td class="row2"><input type="text" class="post" size="20" name="version" value="{FILE_VERSION}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_FILE_WEBSITE}:</b><br /><span class="gensmall">{L_FILE_WEBSITE_INFO}</span></td>
	<td class="row2"><input type="text" class="post" size="40" style="width: 200px" name="website" value="{FILE_WEBSITE}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_FILE_POSTICONS}:</b><br /><span class="gensmall">{L_FILE_POSTICONS_INFO}</span></td>
	<td class="row2">{S_POSTICONS}</td>
</tr>
<tr>
	<td class="row1"><b>{L_FILE_CAT}:</b><br /><span class="gensmall">{L_FILE_CAT_INFO}</span></td>
	<td class="row2"><select name="cat_id">{S_CAT_LIST}</select></td>
</tr>
<tr>
	<td class="row1"><b>{L_FILE_LICENSE}:</b><br /><span class="gensmall">{L_FILE_LICENSE_INFO}</span></td>
	<td class="row2"><select name="license">{S_LICENSE_LIST}</select></td>
</tr>

<!-- IF IS_ADMIN or IS_MOD --> 
<tr>
	<td class="row1"><b>{L_FILE_PINNED}:</b><br /><span class="gensmall">{L_FILE_PINNED_INFO}</span></td>
	<td class="row2"><input type="radio" name="pin" value="1"{PIN_CHECKED_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="pin" value="0"{PIN_CHECKED_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_FILE_DOWNLOAD}:</b></td>
	<td class="row2"><input type="text" class="post" size="10" name="file_download" value="{FILE_DOWNLOAD}" /></td>
</tr> 
<!-- ENDIF -->

<tr>
	<th class="thHead" colspan="2">{L_SCREENSHOT}</th>
</tr>  
<tr>
	<td class="row1"><b>{L_FILESS_UPLOAD}:</b><br /><span class="gensmall">{L_FILESSINFO_UPLOAD}</span></td>
	<td class="row2"><input type="file" size="40" style="width: 200px" name="screen_shot" maxlength="{FILESIZE}" class="post" /></td>
</tr>  
<tr>
	<td class="row1"><b>{L_FILESS}:</b><br /><span class="gensmall">{L_FILESSINFO}</span></td>
	<td class="row2"><input type="text" class="post" size="40" style="width: 200px" name="screen_shot_url" value="{FILE_SSURL}"></td>
</tr>
<tr>
	<td class="row1"><b>{L_FILE_SSLINK}:</b><br /><span class="gensmall">{L_FILE_SSLINK_INFO}</span></td>
	<td class="row2"><input type="radio" name="sshot_link" value="1" {SS_CHECKED_YES}> {L_YES}&nbsp;&nbsp;<input type="radio" name="sshot_link" value="0" {SS_CHECKED_NO}> {L_NO}</td>
</tr>  
<tr>
	<th class="thHead" colspan="2">{L_FILES}</th>
</tr>  
<tr>
	<td class="row1"><b>{L_FILE_UPLOAD}:</b><br /><span class="gensmall">{L_FILEINFO_UPLOAD}</span></td>
	<td class="row2"><input type="file" size="40" style="width: 200px" name="userfile" maxlength="{FILESIZE}" class="post" /></td>
</tr>  
<tr>
	<td class="row1"><b>{L_FILE_URL}:</b><br /><span class="gensmall">{L_FILE_URL_INFO}</span></td>
	<td class="row2"><input type="text" class="post" size="40" style="width: 200px" name="download_url" value="{FILE_DLURL}"></td>
</tr>

<!-- IF CUSTOM_EXIST -->
<tr>
	<th class="thHead" colspan="2">{L_ADDTIONAL_FIELD}</th>
</tr>
<!-- ENDIF -->
<!-- INCLUDE pa_custom_field.tpl -->

<tr>
	<td align="center" class="catBottom" colspan="2">{S_HIDDEN_FIELDS}<input class="mainoption" type="submit" value="{L_FILE_TITLE}" name="submit" />&nbsp;&nbsp;<input type="reset" name="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</form></table>	

<!-- INCLUDE pa_footer.tpl -->