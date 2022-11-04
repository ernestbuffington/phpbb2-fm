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

<!-- IF ERROR neq '' -->
<table width="100%" cellpadding="2" cellspacing="2" align="center">
  <tr>
	<td align="center">{ERROR}</td>
  </tr>
</table>
<!-- ENDIF -->

<table width="100%" cellpadding="4" cellspacing="1" class="forumline" align="center"><form action="{S_CAT_ACTION}" method="post" name="form" onsubmit="return checkAddForm();">
  <tr>
	<th colspan="2" class="thHead">{L_CAT_TITLE}</th>
  </tr>
  <tr>
	<td width="50%" class="row1"><b>{L_CAT_NAME}:</b><br /><span class="gensmall">{L_CAT_NAME_INFO}</span></td>
	<td class="row2"><input type="text" class="post" size="35" name="cat_name" value="{CAT_NAME}" /></td>
  </tr>
  <tr>
	<td class="row1"><b>{L_CAT_DESC}:</b><br /><span class="gensmall">{L_CAT_DESC_INFO}</span></td>
	<td class="row2"><textarea wrap="virtual" class="post" name="board_email_sig" rows="5" cols="35">{CAT_DESC}</textarea></td>
  </tr>
  <tr>
	<td class="row1"><b>{L_CAT_PARENT}:</b><br /><span class="gensmall">{L_CAT_PARENT_INFO}</span></td>
	<td class="row2"><select name="cat_parent">{S_CAT_LIST}</select></td>
  </tr>
  <tr>
	<td class="row1"><b>{L_CAT_ALLOWFILE}:</b><br /><span class="gensmall">{L_CAT_ALLOWFILE_INFO}</span></td>
	<td class="row2"><input type="radio" name="cat_allow_file" value="1" {CHECKED_YES}> {L_YES}&nbsp;&nbsp;<input type="radio" name="cat_allow_file" value="0" {CHECKED_NO}> {L_NO}</td>
  </tr>  
  <tr>
	<td class="row1"><b>{L_CAT_ALLOWCOMMENTS}:</b><br /><span class="gensmall">{L_CAT_ALLOWCOMMENTS_INFO}</span></td>
	<td class="row2"><input type="radio" name="cat_allow_comments" value="1" {CHECKED_ALLOWCOMMENTS_YES}> {L_YES}&nbsp;&nbsp;<input type="radio" name="cat_allow_comments" value="0" {CHECKED_ALLOWCOMMENTS_NO}> {L_NO}</td>
  </tr>  
  <tr>
	<td class="row1"><b>{L_CAT_ALLOWRATINGS}:</b><br /><span class="gensmall">{L_CAT_ALLOWRATINGS_INFO}</span></td>
	<td class="row2"><input type="radio" name="cat_allow_ratings" value="1" {CHECKED_ALLOWRATINGS_YES}> {L_YES}&nbsp;&nbsp;<input type="radio" name="cat_allow_ratings" value="0" {CHECKED_ALLOWRATINGS_NO}> {L_NO}</td>
  </tr>  
  <tr>
	<td align="center" class="catBottom" colspan="2">{S_HIDDEN_FIELDS}<input class="mainoption" type="submit" value="{L_CAT_TITLE}" name="submit" /></td>
  </tr>
</form></table>
<br />
<div align="center" class="copyright">paFileDB phpBB Integration 0.0.9d &amp; <a href="http://www.mx-system.com/" target="_blank" class="copyright">MX Addon 1.2</a> &copy; 2003, {COPYRIGHT_YEAR} Mohd & Haplo</a></div>
