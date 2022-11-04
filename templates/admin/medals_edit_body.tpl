{MEDALS_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<script language="javascript" type="text/javascript">
<!--
function update_icon(newimage)
{
	document.medal_img.src = "{S_ICON_BASEDIR}/" + newimage;
}
//-->
</script>

<h1>{L_MEDAL_TITLE}</h1>

<p>{L_MEDAL_EXPLAIN}</p>

<table width="100%" class="forumline" cellpadding="4" cellspacing="1" align="center"><form action="{S_MEDAL_ACTION}" method="post">
<tr>
	<th class="thHead" colspan="2">{L_NEW_MEDAL}</th>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_MEDAL_NAME}:</b></td>
	<td class="row2"><input class="post" type="text" name="medal_name" size="35" maxlength="40" value="{MEDAL_NAME}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_MEDAL_DESCRIPTION}:</b></td>
	<td class="row2"><input class="post" type="text" name="medal_description" size="35" maxlength="255" value="{MEDAL_DESCRIPTION}" /></td>
</tr>
<tr> 
	<td class="row1"><b>{L_CATEGORY}:</b></td>
	<td class="row2"><select name="mc">{S_CAT_LIST}</select></td>
</tr>
<tr> 
	<td class="row1"><b>{L_MEDAL_IMAGE}:</b><br /><span class="gensmall">{L_MEDAL_IMAGE_EXPLAIN}</span></td>
	<td class="row2"><select name="medal_image" onchange="update_icon(this.options[selectedIndex].value);">{S_FILENAME_OPTIONS}</select> &nbsp; <img name="medal_img" src="{MEDAL_ICON}" alt="" title="" /> &nbsp;</td>
</tr>

<tr>
	<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</form></table>
<br />
<div align="center" class="copyright">Medal System 0.4.6 &copy; 2003 <a href="http://macphpbbmod.sourceforge.net" target="_blank" class="copyright">ycl6</a></div>


