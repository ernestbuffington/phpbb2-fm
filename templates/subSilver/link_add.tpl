<script language="JavaScript" type="text/javascript">
<!--
	function checkAddForm() 
	{
		error_msg = "";
		if (document.form.cat_id.value == -1)
		{
			error_msg = "{LINK_CAT_NOT_ALLOW}";
		}

		if({ALLOW_GUEST} && document.form.post_username.value == "")
		{
			if(error_msg != "")
			{
				error_msg += "\n";
			}
			error_msg += "{LINK_GUEST_FIELD}";
		}
		
		if(document.form.name.value == "")
		{
			if(error_msg != "")
			{
				error_msg += "\n";
			}
			error_msg += "{LINK_NAME_FIELD}";
		}
		
		if(document.form.link_url.value == "" || document.form.link_url.value == "http://")
		{
			if(error_msg != "")
			{
				error_msg += "\n";
			}
			error_msg += "{LINK_URL_FIELD}";
		}
		
		if(document.form.link_logo_src.value == "")
		{
			if(!{ALLOW_NO_LOGO})
			{
				if(error_msg != "")
				{
					error_msg += "\n";
				}
				error_msg += "{LINK_LOGO_FIELD}";
			}
		}
		
		if(document.form.long_desc.value == "")
		{
			if(error_msg != "")
			{
				error_msg += "\n";
			}
			error_msg += "{LINK_LONG_DES_FIELD}";
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

<table width="100%" cellpadding="2" cellspacing="2">
  <tr>
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> <a href="{U_LINK}" class="nav">{LINKS}</a> -> {L_ADD_EDIT_LINK}</td>
  </tr>
</table>

<!-- BEGIN guestname -->
<table width="100%" cellpadding="4" cellspacing="1" class="forumline" align="center"><form action="{S_LOGIN_ACTION}" method="post">
  <tr> 
	<th class="thHead">{L_ENTER_PASSWORD}</th>
  </tr>
  <tr> 
	<td class="row1"><table cellpadding="3" cellspacing="1" width="100%">
		  <tr> 
			<td align="center">
			<span class="gen">{LINK_GUEST_REG}<br />
			{L_USERNAME}:
			<input type="text" name="username" size="15" maxlength="40" value="{USERNAME}" />
			&nbsp;&nbsp;&nbsp;{L_PASSWORD}:
			<input type="password" name="password" size="15" maxlength="32" />
			&nbsp;&nbsp;&nbsp;{L_AUTO_LOGIN}: 
			<input type="checkbox" name="autologin" />{S_LOGIN_FIELDS}
			&nbsp;&nbsp;&nbsp;
			<input type="submit" name="login" class="mainoption" value="{L_LOGIN}" />
			</td>
		  </tr>
		</table></td>
  </tr>
</form></table>
<br />
<!-- END guestname -->

<table width="100%" cellpadding="4" cellspacing="1" class="forumline"><form enctype="multipart/form-data" action="{S_ADD_FILE_ACTION}" method="post" name="form" onsubmit="return checkAddForm();">
  <tr>
	<th colspan="2" class="thHead">{L_FILE_TITLE}</th>
  </tr>
  <!-- BEGIN guestname -->
  <tr> 
	<td class="row1"><b>{L_GUESTNAME}</span></td>
	<td class="row2"><input type="text" class="post" name="post_username" size="25" maxlength="25" /></td>
  </tr>
  <!-- END guestname -->
  <tr>
	<td class="row1" width="40%"><b>{L_FILE_NAME}:</b><br /><span class="gensmall">{L_FILE_NAME_INFO}</span></td>
	<td class="row2"><input type="text" class="post" size="50" name="name" value="{FILE_NAME}" /></td>
  </tr>
  <tr>
	<td class="row1"><b>{L_FILE_URL}:</b><br /><span class="gensmall">{L_FILE_URL_INFO}</span></td>
	<td class="row2"><input type="text" class="post" size="50" name="link_url" value="{LINK_URL}" />
	<!-- BEGIN notvalidurl -->
	<br /><span class="gen">{L_NOT_VALID_URL}&nbsp;{TESTED_URL}</span>
	<!-- END notvalidurl -->
	</td>
  </tr>
  <tr>
	<td class="row1"><b>{L_LINK_LOGO}:</b><br /><span class="gensmall">{L_LINK_LOGO_SRC}</span></td>
	<td class="row2"><input type="text" size="50" name="link_logo_src" value="{LINK_LOGO_SRC}" class="post" /><span class="mainmenu">&nbsp;[<a href="javascript: void(0)" onclick="var img_src=document.form.link_logo_src.value;if(img_src=='' || img_src=='http://') img_src='images/links/yourlogo.gif';_preview=window.open(img_src, '_preview', 'toolbar=no,width=200,height=100,top=300,left=300');" class="mainmenu">{L_PREVIEW}</a>]</span></td>
  </tr>
  <tr>
	<td class="row1"><b>{L_FILE_LONG_DESC}:</b><br /><span class="gensmall">{L_FILE_LONG_DESC_INFO}</span></td>
	<td class="row2"><textarea rows="6" name="long_desc" cols="32" class="post">{FILE_LONG_DESC}</textarea></td>
  </tr>
  <tr>
	<td class="row1"><b>{L_FILE_CAT}:</b><br /><span class="gensmall">{L_FILE_CAT_INFO}</span></td>
	<td class="row2"><select name="cat_id">{S_CAT_LIST}</select></td>
  </tr>
  <!-- BEGIN ADMIN -->
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
	<td class="row2"><input type="radio" name="approved" value="1" {APPROVED_CHECKED_YES}> {L_YES}&nbsp;&nbsp;<input type="radio" name="approved" value="0" {APPROVED_CHECKED_NO}> {L_NO}</td>
  </tr>
  <!-- END ADMIN -->

  <!-- BEGIN custom_exit -->
  <tr>
	<th class="thHead" colspan="2">{L_ADDTIONAL_FIELD}</th>
  </tr>
  <!-- END custom_exit -->
  <!-- BEGIN input -->
  <tr>
	<td class="row1"><b>{input.FIELD_NAME}</b><br /><span class="gensmall">{input.FIELD_DESCRIPTION}</span></td>
	<td class="row2"><input type="text" class="post" size="50" name="field[{input.FIELD_ID}]" value="{input.FIELD_VALUE}" /></td>
  </tr>
  <!-- END input -->
  <!-- BEGIN textarea -->
  <tr>
	<td class="row1"><b>{textarea.FIELD_NAME}</b><br /><span class="gensmall">{textarea.FIELD_DESCRIPTION}</span></td>
	<td class="row2"><textarea rows="6" class="post" name="field[{textarea.FIELD_ID}]" cols="32">{textarea.FIELD_VALUE}</textarea></td>
  </tr>
  <!-- END textarea -->
  <!-- BEGIN radio -->
  <tr>
	<td class="row1"><b>{radio.FIELD_NAME}:</b><br /><span class="gensmall">{radio.FIELD_DESCRIPTION}</span></td>
	<td class="row2">
	<!-- BEGIN row -->	
	<input type="radio" name="field[{radio.FIELD_ID}]" value="{radio.row.FIELD_VALUE}" {radio.row.FIELD_SELECTED} /> {radio.row.FIELD_VALUE}&nbsp;
	<!-- END row -->
	</td>
  </tr>	
  <!-- END radio -->
  <!-- BEGIN select -->
  <tr>
	<td class="row1"><b>{select.FIELD_NAME}:</b><br /><span class="gensmall">{select.FIELD_DESCRIPTION}</span></td>
	<td class="row2"><select name="field[{select.FIELD_ID}]">
		<!-- BEGIN row -->	
		<option value="{select.row.FIELD_VALUE}"{radio.row.FIELD_SELECTED} title="{select.row.FIELD_VALUE}">{select.row.FIELD_VALUE}</option>
		<!-- END row -->
	</select></td>
  </tr>	
  <!-- END select -->
  <!-- BEGIN select_multiple -->
  <tr>
	<td class="row1"><b>{select_multiple.FIELD_NAME}:</b><br /><span class="gensmall">{select_multiple.FIELD_DESCRIPTION}</span></td>
	<td class="row2"><select name="field[{select_multiple.FIELD_ID}][]" multiple="multiple" size="4">
		<!-- BEGIN row -->	
		<option value="{select_multiple.row.FIELD_VALUE}"{select_multiple.row.FIELD_SELECTED} title="{select_multiple.row.FIELD_VALUE}">{select_multiple.row.FIELD_VALUE}</option>
		<!-- END row -->
	</select></td>
  </tr>	
  <!-- END select_multiple -->
  <!-- BEGIN checkbox -->
  <tr>
	<td class="row1"><b>{checkbox.FIELD_NAME}:</b><br /><span class="gensmall">{checkbox.FIELD_DESCRIPTION}</span></td>
	<td class="row2">
	<!-- BEGIN row -->	
	<input type="checkbox" name="field[{checkbox.FIELD_ID}][{checkbox.row.FIELD_VALUE}]" value="{checkbox.row.FIELD_VALUE}" {checkbox.row.FIELD_CHECKED}> {checkbox.row.FIELD_VALUE}&nbsp;
	<!-- END row -->
	</td>
  </tr>	
  <!-- END checkbox -->
  <tr>
	<td align="center" class="catBottom" colspan="2">{S_HIDDEN_FIELDS}<input class="mainoption" type="submit" value="{L_FILE_TITLE}" name="submit" /></td>
  </tr>
</form></table>