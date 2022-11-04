{DOWNLOAD_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<script language="JavaScript" type="text/javascript">
<!--
	var error_msg = "";
	function checkAddForm() 
	{
		error_msg = "";
		if (document.form.cat_id.value == -1)
		{
			error_msg = "There is no file in this category";
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
<h1>{L_DFILETITLE}</h1>

<p>{L_FILEEXPLAIN}</p>

<form action="{S_DELETE_FILE_ACTION}" method="post" name="form" onsubmit="return checkAddForm();">
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
	<th colspan="2" class="thHead">{L_DFILETITLE}</th>
  </tr>
  <tr>
	<td colspan="2" class="cat" align="center" valign="middle">
	<select name="cat_id" class="forminput">
	{S_CAT_SELECT}
	</select>
	&nbsp;&nbsp;<input class="liteoption" type="submit" value="{L_GO}" name="select_cat">
	</td>
  </tr>
<!-- BEGIN file_list -->
  <tr>
  	<td width="3%" class="row1" align="center" valign="middle"><input type="checkbox" name="select[{file_list.FILE_ID}]" value="yes" {file_list.CHECKBOX}></td>
	<td width="97%" class="row1"><span class="gen"> {file_list.FILE_NAME}</span>&nbsp;<span class="gensmall">{file_list.FILE_APPROVED}</span><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="gensmall">{file_list.FILE_DESC}</span></td>
  </tr>		
<!-- END file_list -->  
  <tr>
	<td align="center" class="cat" colspan="2"><input class="liteoption" type="submit" value="{L_DFILETITLE}" name="submit">
	<input type="hidden" name="file" value="delete">
	</td>
  </tr>
</form></table>
<br />
<div align="center" class="copyright">paFileDB phpBB Integration 0.0.9d &amp; <a href="http://www.mx-system.com/" target="_blank" class="copyright">MX Addon 1.2</a> &copy; 2003, {COPYRIGHT_YEAR} Mohd & Haplo</a></div>
