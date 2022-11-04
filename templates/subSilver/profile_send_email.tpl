<script language="JavaScript" src="mods/spelling/spellmessage.js"></script>
<script language="JavaScript" type="text/javascript">
<!--
function checkForm(formObj) 
{
	formErrors = false;    

	if (formObj.message.value.length < 2) 
	{
		formErrors = "{L_EMPTY_MESSAGE_EMAIL}";
	}
	else if ( formObj.subject.value.length < 2)
	{
		formErrors = "{L_EMPTY_SUBJECT_EMAIL}";
	}

	if (formErrors) 
	{
		alert(formErrors);
		return false;
	}
}

function highlightmetasearch() 
{ 
	document.post.message.select(); document.post.message.focus(); 
} 

function copymetasearch() 
{ 
	highlightmetasearch(); 
	textRange = document.post.message.createTextRange(); 
	textRange.execCommand("RemoveFormat"); 
	textRange.execCommand("Copy"); 
	alert("This post has been copied to your clipboard.\nIf this post is lost when you submit it you can easily repost it.\nAlways use this feature before posting!"); 
} 
//-->
</script>

{ERROR_BOX}

<table width="100%" cellspacing="2" cellpadding="2" align="center">
<tr>
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></td>
</tr>
</table>

<table cellpadding="4" cellspacing="1" width="100%" class="forumline"><form action="{S_POST_ACTION}" method="post" name="post" onSubmit="return checkForm(this)">
<tr> 
	<th class="thHead" colspan="2">{L_SEND_EMAIL_MSG}</th>
</tr>
<tr> 
	<td class="row1" width="22%"><b class="gen">{L_RECIPIENT}:</b></td>
	<td class="row2"><b class="gen">{USERNAME}</b></td>
</tr>
<tr> 
	<td class="row1"><b class="gen">{L_SUBJECT}:</b></td>
	<td class="row2"><input type="text" name="subject" size="45" maxlength="100" style="width:450px" tabindex="2" class="post" value="{SUBJECT}" /></td>
</tr>
<tr> 
	<td class="row1"><b class="gen">{L_MESSAGE_BODY}:</b><br /><span class="gensmall">{L_MESSAGE_BODY_DESC}</span></td>
	<td class="row2"><textarea name="message" rows="25" cols="40" wrap="virtual" style="width:500px" tabindex="3" class="post">{MESSAGE}</textarea></td>
</tr>
<tr> 
	<td class="row1"><b class="gen">{L_OPTIONS}:</b></td>
	<td class="row2"><table cellspacing="0" cellpadding="1">
	<tr> 
		<td><input type="checkbox" name="cc_email" value="1" checked="checked" /></td>
		<td><span class="gen">{L_CC_EMAIL}</span></td>
	</tr>
	<tr>
    	<td><input type="checkbox" name="read_receipt" value="1" /></td>
    	<td><span class="gen">{L_READ_RECEIPT}</span></td>
    </tr>

	</table></td>
</tr>
<tr>
	<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" tabindex="6" name="submit" class="mainoption" value="{L_SEND_EMAIL}" />&nbsp;
	  <script language="JavaScript" type="text/javascript"> 
	  <!-- 
	  if ((navigator.appName=="Microsoft Internet Explorer")&&(parseInt(navigator.appVersion)>=4)) 
	  { 
	  	document.write('<input type="button" class="liteoption" value="{L_COPY_TO_CLIPBOARD}" onClick="copymetasearch();">'); 
	  } 
	  else 
	  { 
	  	document.write('<input type="button" class="liteoption" value="{L_HIGHLIGHT_TEXT}" onClick="highlightmetasearch();">'); 
	  } 
	  // --> 
	  </script> 
	  &nbsp;<input type="button" class="liteoption" value="{L_SPELLCHECK}" name="button" onclick="openspell();" />&nbsp;&nbsp;<input type="reset" name="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</form></table>
<br />

<table width="100%" cellspacing="2" align="center">
<tr>
	<td align="right">{JUMPBOX}</td>
</tr>
</table>
