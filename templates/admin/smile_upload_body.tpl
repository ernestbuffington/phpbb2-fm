{SMILEY_MENU}{POST_MENU}
{MOD_CP_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<script language="javascript" type="text/javascript">
<!--
	function reloadPage()
	{
		document.count_form.submit();
		return false;
	}
// -->
</script>

<h1>{L_UPLOAD_TITLE}</h1>

<p>{L_UPLOAD_DESCRIPTION}{L_UPLOAD_WARNING}</p>

<table cellspacing="1" cellpadding="4" align="center" width="100%" class="forumline"><form enctype="multipart/form-data" action="{S_SMILEY_CAT_ACTION}" method="post"  name="count_form"><input type="hidden" name="MAX_FILE_SIZE" value="{S_MAX_FILE_SIZE}" /><input type="hidden" name="file_count" value="{S_FILE_COUNT}" />
<tr>
	<th class="thHead">{L_UPLOAD_TITLE}</th>
</tr>
<tr> 
	<td class="row2" align="center"><b>{L_UPLOAD_AMOUNT}:</b> <select name="upload_amount" onChange='reloadPage()'>{S_UPLOAD_BOXES}</select></td>
</tr>
<!-- BEGIN files -->
<tr> 
	<td class="row1" align="center"><input class="post" name="userfile[]" type="file" size="40" /></td>
</tr>
<!-- END files -->
<tr> 
	<td class="catBottom" align="center"><input class="mainoption" name="uploading" type="submit" value="{L_SUBMIT}" /></td>
</tr>
</form></table>
<br />
<div align="center" class="copyright">Smiley Categories 2.0.4</a> &copy; 2004, {COPYRIGHT_YEAR} <a href="http://mods.afkamm.co.uk" target="_blank" class="copyright">Afkamm</a></div>
