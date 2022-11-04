<script language="JavaScript" type="text/javascript">
<!--
function checkAlbumForm() 
{
	formErrors = false;

	if (document.upload.pic_title.value.length < 2)
	{
		formErrors = "{L_UPLOAD_NO_TITLE}";
	}
	else if (document.upload.pic_file.value.length < 2)
	{
		formErrors = "{L_UPLOAD_NO_FILE}";
	}
	else if (document.upload.pic_desc.value.length > {S_PIC_DESC_MAX_LENGTH})
	{
		formErrors = "{L_DESC_TOO_LONG}";
	}

	if (formErrors) {
		alert(formErrors);
		return false;
	} else {
		return true;
	}
}
// -->
</script>

<table width="100%" cellspacing="2" cellpadding="2">
  <tr>
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> <a class="nav" href="{U_ALBUM}">{L_ALBUM}</a> -> <a class="nav" href="{U_VIEW_CAT}">{CAT_TITLE}</a></td>
  </tr>
</table>

<table width="100%" cellpadding="4" cellspacing="1" class="forumline"><form name="upload" action="{S_ALBUM_ACTION}" method="post" enctype="multipart/form-data" onSubmit="return checkAlbumForm()">
  <tr>
	<th class="thTop" colspan="2">{L_UPLOAD_PIC}</th>
  </tr>
<!-- BEGIN switch_user_logged_out -->
  <tr>
	<td class="row1" width="30%"><span class="gen">{L_USERNAME}:</span></td>
	<td class="row2"><input class="post" type="text" name="pic_username" size="32" maxlength="32" /></td>
  </tr>
<!-- END switch_user_logged_out -->
  <tr>
	<td class="row1"><span class="gen">{L_PIC_TITLE}:</span></td>
	<td class="row2"><input class="post" type="text" name="pic_title" size="60" /></td>
  </tr>
  <tr>
	<td class="row1"><span class="gen">{L_PIC_DESC}:<br />
	</span><span class="genmed">{L_PLAIN_TEXT_ONLY}<br />{L_MAX_LENGTH}: <b>{S_PIC_DESC_MAX_LENGTH}</b></span></td>
	<td class="row2"><textarea class="post" cols="60" rows="4" name="pic_desc" size="60"></textarea></td>
  </tr>
  <tr>
	<td class="row1"><span class="gen">{L_UPLOAD_PIC_FROM_MACHINE}:</span></td>
	<td class="row2"><input class="post" type="file" name="pic_file" size="49" /></td>
  </tr>
<!-- BEGIN switch_manual_thumbnail -->
  <tr>
	<td class="row1"><span class="gen">{L_UPLOAD_THUMBNAIL}:<br /></span><span class="gensmall">{L_UPLOAD_THUMBNAIL_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="file" name="pic_thumbnail" size="49" /></td>
  </tr>
  <tr>
	<td class="row1"><span class="gen">{L_THUMBNAIL_SIZE}:</span></td>
	<td class="row2"><span class="gen"><b>{S_THUMBNAIL_SIZE}</b></span></td>
  </tr>
<!-- END switch_manual_thumbnail -->
  <tr>
	<td class="row1"><span class="gen">{L_UPLOAD_TO_CATEGORY}:</span></td>
	<td class="row2">{SELECT_CAT}</td>
  </tr>
  <tr>
	<td class="row1"><span class="gen">{L_MAX_FILESIZE}:</span></td>
	<td class="row2"><span class="gen"><b>{S_MAX_FILESIZE}</b></span></td>
  </tr>
  <tr>
	<td class="row1"><span class="gen">{L_MAX_WIDTH}:</span></td>
	<td class="row2"><span class="gen"><b>{S_MAX_WIDTH}</b></span></td>
  </tr>
  <tr>
	<td class="row1"><span class="gen">{L_MAX_HEIGHT}:</span></td>
	<td class="row2"><span class="gen"><b>{S_MAX_HEIGHT}</b></span></td>
  </tr>
  <tr>
	<td class="row1"><span class="gen">{L_ALLOWED_JPG}:</span></td>
	<td class="row2"><span class="gen"><b>{S_JPG}</b></span></td>
  </tr>
  <tr>
	<td class="row1"><span class="gen">{L_ALLOWED_PNG}:</span></td>
	<td class="row2"><span class="gen"><b>{S_PNG}</b></span></td>
  </tr>
  <tr>
	<td class="row1"><span class="gen">{L_ALLOWED_GIF}:</span></td>
	<td class="row2"><span class="gen"><b>{S_GIF}</b></span></td>
  </tr>
  <tr>
	<td class="catBottom" align="center" colspan="2"><input type="reset" value="{L_RESET}" class="liteoption" />&nbsp;&nbsp;&nbsp;<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" /></td>
  </tr>
</form></table>
<br />
<div align="center" class="copyright">Photo Album {ALBUM_VERSION} &copy; 2002, {COPYRIGHT_YEAR} <a href="http://smartor.is-root.com" class="copyright" target="_blank">Smartor</a></div>

