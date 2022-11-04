{DOWNLOAD_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<script language="JavaScript" type="text/javascript">
<!--
	var win = null;
	var error_msg = "";
	var mirror_mode = false;
	
	function set_mirror_mode(status)
	{
		mirror_mode = status;
	}

	function new_window(mypage, myname, w, h, pos, infocus)
	{
		if(pos == "random")
		{
			myleft = (screen.width) ? Math.floor(Math.random()*(screen.width-w)) : 100;
			mytop = (screen.height) ? Math.floor(Math.random()*((screen.height-h)-75)) : 100;
		}
		if(pos == "center")
		{
			myleft = (screen.width) ? (screen.width-w) / 2 : 100;
			mytop = (screen.height) ? (screen.height-h) / 2 : 100;
		}
		else if((pos != 'center' && pos != "random") || pos == null)
		{
			myleft = 0;
			mytop = 20
		}
		settings = "width=" + w + ",height=" + h + ",top=" + mytop + ",left=" + myleft + ",scrollbars=yes,location=no,directories=no,status=yes,menubar=no,toolbar=no,resizable=no";
		win = window.open(mypage, myname, settings);
		win.focus();
	}
	
	if({ADD_MIRRORS})
	{
		new_window('{U_MIRRORS_PAGE}', 'fileupload', '600','450', 'center', 'front');
	}

	function checkAddForm() 
	{
		if(mirror_mode)
		{
			new_window('{U_MIRRORS_PAGE}', 'fileupload', '600','450', 'center', 'front');
			return false;
		}
		
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
		<!-- IF MODE eq 'add' -->
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

<h1>{L_FILE_TITLE}</h1>

<p>{L_FILE_EXPLAIN}</p>

<!-- IF ERROR neq '' -->
<table width="100%" cellpadding="2" cellspacing="2" align="center">
  <tr>
	<td align="center">{ERROR}</td>
  </tr>
</table>
<!-- ENDIF -->

<table width="100%" cellpadding="4" cellspacing="1" class="forumline" align="center"><form action="{S_FILE_ACTION}" method="post" name="form" enctype="multipart/form-data" onsubmit="return checkAddForm();">
  <tr>
	<th colspan="2" class="thHead">{L_FILE_TITLE}</th>
  </tr>
  <tr>
	<td width="50%" class="row1"><b>{L_FILE_NAME}:</b><br /><span class="gensmall">{L_FILE_NAME_INFO}</span></td>
	<td class="row2"><input type="text" class="post" size="35" name="name" value="{FILE_NAME}" /></td>
  </tr>
  <tr>
	<td class="row1"><b>{L_FILE_SHORT_DESC}:</b><br /><span class="gensmall">{L_FILE_SHORT_DESC_INFO}</span></td>
	<td class="row2"><input type="text" class="post" size="35" name="short_desc" value="{FILE_DESC}" /></td>
  </tr>
  <tr>
	<td class="row1"><b>{L_FILE_LONG_DESC}:</b><br /><span class="gensmall">{L_FILE_LONG_DESC_INFO}</span></td>
	<td class="row2"><textarea rows="5" name="long_desc" cols="35" class="post">{FILE_LONG_DESC}</textarea></td>
  </tr>
  <tr>
	<td class="row1"><b>{L_FILE_AUTHOR}:</b><br /><span class="gensmall">{L_FILE_AUTHOR_INFO}</span></td>
	<td class="row2"><input type="text" class="post" size="35" name="author" value="{FILE_AUTHOR}" /></td>
  </tr>
  <tr>
	<td class="row1"><b>{L_FILE_VERSION}:</b><br /><span class="gensmall">{L_FILE_VERSION_INFO}</span></td>
	<td class="row2"><input type="text" class="post" size="35" name="version" value="{FILE_VERSION}" /></td>
  </tr>
  <tr>
	<td class="row1"><b>{L_FILE_WEBSITE}:</b><br /><span class="gensmall">{L_FILE_WEBSITE_INFO}</span></td>
	<td class="row2"><input type="text" class="post" size="35" name="website" value="{FILE_WEBSITE}" /></td>
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
  <tr>
	<td class="row1"><b>{L_FILE_PINNED}:</b><br /><span class="gensmall">{L_FILE_PINNED_INFO}</span></td>
	<td class="row2"><input type="radio" name="pin" value="1"{PIN_CHECKED_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="pin" value="0"{PIN_CHECKED_NO} /> {L_NO}</td>
  </tr>
  <tr>
	<td class="row1"><b>{L_FILE_DOWNLOAD}:</b></td>
	<td class="row2"><input type="text" class="post" size="10" name="file_download" value="{FILE_DOWNLOAD}" /></td>
  </tr>
  <tr>
	<td class="row1"><b>{L_FILE_APPROVED}:</b><br /><span class="gensmall">{L_FILE_APPROVED_INFO}</span></td>
	<td class="row2"><input type="radio" name="approved" value="1" {APPROVED_CHECKED_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="approved" value="0" {APPROVED_CHECKED_NO} /> {L_NO}</td>
  </tr>
  <tr>
	<th class="thHead" colspan="2">{L_SCREENSHOT}</th>
  </tr>  
  <tr>
	<td class="row1"><b>{L_FILESS_UPLOAD}:</b><br /><span class="gensmall">{L_FILESSINFO_UPLOAD}</span></td>
	<td class="row2"><input type="file" class="post" size="30" name="screen_shot" maxlength="{FILESIZE}" /></td>
  </tr>  
  <tr>
	<td class="row1"><b>{L_FILESS}:</b><br /><span class="gensmall">{L_FILESSINFO}</span></td>
	<td class="row2"><input type="text" class="post" size="35" name="screen_shot_url" value="{FILE_SSURL}"></td>
  </tr>
  <tr>
	<td class="row1"><b>{L_FILE_SSLINK}:</b><br /><span class="gensmall">{L_FILE_SSLINK_INFO}</span></td>
	<td class="row2"><input type="radio" name="sshot_link" value="1" {SS_CHECKED_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="sshot_link" value="0" {SS_CHECKED_NO} /> {L_NO}</td>
  </tr>  
  <tr>
	<th class="thHead" colspan="2">{L_FILES}</th>
  </tr>  
  <tr>
	<td class="row1"><b>{L_FILE_UPLOAD}:</b><br /><span class="gensmall">{L_FILEINFO_UPLOAD}</span></td>
	<td class="row2"><input type="file" size="30" name="userfile" maxlength="{FILESIZE}" class="post" /></td>
  </tr>  
  <tr>
	<td class="row1"><b>{L_FILE_URL}:</b><br /><span class="gensmall">{L_FILE_URL_INFO}</span></td>
	<td class="row2"><input type="text" class="post" size="35" name="download_url" value="{FILE_DLURL}"></td>
  </tr>

<!-- IF MIRROR_FILE -->  
  <tr>
	<td class="row1"><b>{L_UPLOADED_FILE}:</b></td>
	<td class="row2"><a href="{U_UPLOADED_MIRROR}">{MIRROR_FILE}</a></td>
  </tr>
<!-- ENDIF -->  

  <tr>
	<td class="row1"><b>{L_MIRRORS}:</b><br /><span class="gensmall">{L_MIRRORS_INFO}</span></td>
	<td class="row2"><input class="liteoption" type="submit" value="{L_CLICK_HERE_MIRRORS}" name="mirrors" />
	</td>
  </tr>

<!-- IF CUSTOM_EXIST -->
  <tr>
	<th class="thHead" colspan="2">{L_ADDTIONAL_FIELD}</th>
  </tr>
<!-- ENDIF -->

<!-- BEGIN input -->
  <tr>
	<td class="row1"><b>{input.FIELD_NAME}:</b><br /><span class="gensmall">{input.FIELD_DESCRIPTION}</span></td>
	<td class="row2"><input type="text" class="post" size="50" name="field[{input.FIELD_ID}]" value="{input.FIELD_VALUE}" /></td>
  </tr>
<!-- END input -->
<!-- SPILT -->
<!-- BEGIN textarea -->
  <tr>
	<td class="row1"><b>{textarea.FIELD_NAME}:</b><br /><span class="gensmall">{textarea.FIELD_DESCRIPTION}</span></td>
	<td class="row2"><textarea rows="6" class="post" name="field[{textarea.FIELD_ID}]" cols="32">{textarea.FIELD_VALUE}</textarea></td>
  </tr>
<!-- END textarea -->
<!-- SPILT -->
<!-- BEGIN radio -->
  <tr>
	<td class="row1"><b>{radio.FIELD_NAME}:</b><br /><span class="gensmall">{radio.FIELD_DESCRIPTION}</span></td>
	<td class="row2">
	<!-- BEGIN row -->	
	<input type="radio" name="field[{radio.FIELD_ID}]" value="{radio.row.FIELD_VALUE}" {radio.row.FIELD_SELECTED} /><span class="gensmall">{radio.row.FIELD_VALUE}</span>&nbsp;
	<!-- END row -->
	</td>
  </tr>	
<!-- END radio -->
<!-- SPILT -->
<!-- BEGIN select -->
  <tr>
	<td class="row1"><b>{select.FIELD_NAME}:</b><br /><span class="gensmall">{select.FIELD_DESCRIPTION}</span></td>
	<td class="row2"><select name="field[{select.FIELD_ID}]" class="post">
		<!-- BEGIN row -->	
		<option value="{select.row.FIELD_VALUE}"{radio.row.FIELD_SELECTED}>{select.row.FIELD_VALUE}</option>
		<!-- END row -->
	</select></td>
  </tr>	
<!-- END select -->
<!-- SPILT -->
<!-- BEGIN select_multiple -->
  <tr>
	<td class="row1"><b">{select_multiple.FIELD_NAME}:</b><br /><span class="gensmall">{select_multiple.FIELD_DESCRIPTION}</span></td>
	<td class="row2"><select name="field[{select_multiple.FIELD_ID}][]" multiple="multiple" size="4" class="post">
		<!-- BEGIN row -->	
		<option value="{select_multiple.row.FIELD_VALUE}"{select_multiple.row.FIELD_SELECTED}>{select_multiple.row.FIELD_VALUE}</option>
		<!-- END row -->
	</select></td>
  </tr>	
<!-- END select_multiple -->
<!-- SPILT -->
<!-- BEGIN checkbox -->
  <tr>
	<td class="row1"><b>{checkbox.FIELD_NAME}:</b><br /><span class="gensmall">{checkbox.FIELD_DESCRIPTION}</span></td>
	<td class="row2">
	<!-- BEGIN row -->	
	<input type="checkbox" name="field[{checkbox.FIELD_ID}][{checkbox.row.FIELD_VALUE}]" value="{checkbox.row.FIELD_VALUE}" {checkbox.row.FIELD_CHECKED}><span class="gensmall">{checkbox.row.FIELD_VALUE}</span>&nbsp;
	<!-- END row -->
	</td>
  </tr>	
<!-- END checkbox -->
<tr>
	<td align="center" class="catBottom" colspan="2">{S_HIDDEN_FIELDS}<input class="mainoption" type="submit" value="{L_FILE_TITLE}" name="submit" onClick="set_mirror_mode(false);" /></td>
</tr>
</form></table>
<br />
<div align="center" class="copyright">paFileDB phpBB Integration 0.0.9d &amp; <a href="http://www.mx-system.com/" target="_blank" class="copyright">MX Addon 1.2</a> &copy; 2003, {COPYRIGHT_YEAR} Mohd & Haplo</a></div>
