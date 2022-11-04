
<script language="javascript" type="text/javascript">
<!--
function emoticon(text)
{
	var txtarea = parent.document.forms['post'].message;
	text = ' ' + text + ' ';
	if( txtarea.createTextRange && txtarea.caretPos )
	{
		var caretPos = txtarea.caretPos;
		caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? text + ' ' : text;
		window.txtarea.focus();
	}
	else if( txtarea.selectionEnd && (txtarea.selectionStart | txtarea.selectionStart == 0) )
	{ 
		mozInsert(txtarea, text, "");
	}
	else 
	{
		txtarea.value  += text;
		window.txtarea.focus();
	}
}

// The mozInsert function allows Mozilla users to insert smilies/bbcode tags at the cursor position
// and not just at the end like previously

function mozInsert(txtarea, openTag, closeTag)
{
        if( txtarea.selectionEnd > txtarea.value.length )
	{
		txtarea.selectionEnd = txtarea.value.length;
	}
       
        var startPos = txtarea.selectionStart; 
        var endPos = txtarea.selectionEnd+openTag.length; 
       
        txtarea.value = txtarea.value.slice(0,startPos)+openTag+txtarea.value.slice(startPos); 
        txtarea.value = txtarea.value.slice(0,endPos)+closeTag+txtarea.value.slice(endPos); 
         
        txtarea.selectionStart = startPos+openTag.length; 
        txtarea.selectionEnd = endPos; 
        window.txtarea.focus(); 
}
//-->
</script>
<table width="100%" cellspacing="1" cellpadding="4" class="forumline">
	<tr>
		<td align="center"><span class="gensmall"><b>{L_EMOTICONS}</b></span></td>
	</tr>
	<tr>
		<!-- BEGIN smiley_group -->
		<td><table width="100%" cellspacing="0" cellpadding="5">
			<!-- BEGIN smilies_row -->
			<tr align="center">
				<!-- BEGIN smilies_col -->
				<td align="center" valign="middle"><img src="{smiley_group.smilies_row.smilies_col.SMILEY_IMG}" alt="{smiley_group.smilies_row.smilies_col.SMILEY_DESC}" onMouseOver="this.style.cursor='hand';" onClick="emoticon('{smiley_group.smilies_row.smilies_col.SMILEY_CODE}');" title="{smiley_group.smilies_row.smilies_col.SMILEY_DESC}" /></td>
				<!-- END smilies_col -->
			</tr>
			<!-- END smilies_row -->
		</table></td>
		<!-- END smiley_group -->
		<!-- BEGIN smiley_list -->
		<td><table width="100%" cellspacing="1" cellpadding="4">
			<!-- BEGIN smilies_row -->
			<tr>
				<!-- BEGIN smilies_col -->
				<td class="row1" align="center" valign="middle"><img src="{smiley_list.smilies_row.smilies_col.SMILEY_IMG}" alt="{smiley_list.smilies_row.smilies_col.SMILEY_DESC}" onMouseOver="this.style.cursor='hand';" onClick="emoticon('{smiley_list.smilies_row.smilies_col.SMILEY_CODE}');" title="{smiley_list.smilies_row.smilies_col.SMILEY_DESC}" /></td>
				<td class="row2" align="center" valign="middle" nowrap="nowrap"><span class="genmed">{smiley_list.smilies_row.smilies_col.SMILEY_CODE2}</span></td>
				<!-- END smilies_col -->
				<!-- BEGIN smilies_odd -->
				<td class="row2" colspan="{smiley_list.smilies_row.smilies_odd.S_SMILIES_ODD_COLSPAN}">&nbsp;</td>
				<!-- END smilies_odd -->
			</tr>
			<!-- END smilies_row -->
		</table></td>
		<!-- END smiley_list -->
	</tr>
	<tr>
		<td align="center"><span class="genmed">{PAGINATION}</span></td>
	</tr>
	<!-- BEGIN smiley_category -->
	<tr>
		<td align="center"><span class="gensmall"><b>{L_SMILEY_CATEGORIES}</b></span></td>
	</tr>
	<tr>
		<td align="center">
		<!-- BEGIN buttons -->
		<input {smiley_category.buttons.TYPE} {smiley_category.buttons.VALUE} onClick="window.location='{smiley_category.buttons.CAT_MORE_SMILIES}'; return false;" />
		<!-- END buttons -->
		<!-- BEGIN dropdown -->
		{smiley_category.dropdown.OPTIONS}
		<!-- END dropdown -->
		</td>
	</tr>
	<!-- END smiley_category -->
</table>