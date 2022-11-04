{SMILEY_MENU}{POST_MENU}
{MOD_CP_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<script language="javascript" type="text/javascript">
<!--
function update_smiley(newimage)
{
	document.smiley_image.src = "{S_SMILEY_BASEDIR}/" + newimage;
}
//-->
</script>

<h1>{L_SMILEY_TITLE}</h1>

<p>{L_SMILEY_DESCRIPTION}</p>

<table cellspacing="1" cellpadding="4" align="center" width="100%" class="forumline"><form method="post" action="{S_SMILEY_ACTION}">
<tr> 
	<th class="thHead" colspan="2">{L_SMILEY_TITLE}</th>
</tr>
<tr> 
	<td class="row2" colspan="2"><span class="gensmall">{L_ITEMS_REQUIRED}</span></td>
</tr>
<tr> 
	<td class="row1" width="38%"><b>{L_SMILEY_CODE}: *</b></td>
	<td class="row2"><input type="text" name="smile_code" value="{SMILEY_CODE}" class="post" /></td>
</tr>
<tr> 
	<td class="row1"><b>{L_SMILEY_URL}:</b></td>
	<td class="row2"><select name="smile_url" onchange="update_smiley(this.options[selectedIndex].value);">{S_FILENAME_OPTIONS}</select> <img name="smiley_image" src="{SMILEY_IMG}" alt="" title="" /></td>
</tr>
<tr> 
	<td class="row1"><b>{L_SMILEY_EMOTION}: *</b></td>
	<td class="row2"><input type="text" name="smile_emoticon" value="{SMILEY_EMOTICON}" class="post" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_SMILEY_CATEGORY}:</b></td>
	<td class="row2"><select name="smile_category">{S_CATEGORY_OPTIONS}</select></td>
</tr>
<!-- BEGIN delete -->
<tr>
	<td class="row1"><b>{L_SMILEY_DELETE}:</b></td>
	<td class="row2"><input type="checkbox" name="delete" value="1" /></td>
</tr>
<!-- END delete -->
<tr> 
	<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input class="mainoption" name="smiley_add_submit" type="submit" value="{L_SUBMIT}" />&nbsp;&nbsp;<input type="reset" name="reset" value="{L_RESET}" class="liteoption" /</td>
</tr>
</form></table>
<br />
<div align="center" class="copyright">Smiley Categories 2.0.4</a> &copy; 2004, {COPYRIGHT_YEAR} <a href="http://mods.afkamm.co.uk" target="_blank" class="copyright">Afkamm</a></div>
