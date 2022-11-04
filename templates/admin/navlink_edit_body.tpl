{CONFIG_MENU}{USERCOM_MENU}{SERVER_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<script language="javascript" type="text/javascript">
<!--
function update_menu(newimage)
{
	document.menu_image.src = "{S_MENU_BASEDIR}/" + newimage;
}
//-->
</script>

<h1>{L_MENU_TITLE}</h1>

<p>{L_MENU_EXPLAIN}</p>

<table width="100%" class="forumline" cellspacing="1" cellpadding="4" align="center"><form method="post" action="{S_MENU_ACTION}">
	<tr>
		<th class="thHead" colspan="2">{L_MENU_TITLE}</th>
	</tr>
	<tr>
		<td class="row1" width="50%"><b>{L_MENU_NAME}:</b></td>
		<td class="row2"><input class="post" type="text" name="menu_alt" value="{MENU_NAME}" /></td>
	</tr>
	<tr>
		<td class="row1"><b>{L_MENU_LANG}:</b><br /><span class="gensmall">{L_MENU_LANG_EXPLAIN}</span></td>
		<td class="row2"><input type="radio" name="menu_lang" value="1"{MENU_LANG_YES} /> {L_ENABLED}&nbsp;&nbsp;<input type="radio" name="menu_lang" value="0"{MENU_LANG_NO} /> {L_DISABLED}</td>
	</tr>
	<tr>
		<td class="row1"><b>{L_MENU_IMG}:</b></td>
		<td class="row2"><select name="menu_img" onchange="update_menu(this.options[selectedIndex].value);">{S_FILENAME_OPTIONS}</select> &nbsp; <img name="menu_image" src="{MENU_IMG}" alt="" /> &nbsp;</td>
	</tr>
	<tr>
		<td class="row1"><b>{L_MENU_URL}:</b></td>
		<td class="row2"><input class="post" type="text" name="menu_url" maxlength="255" size="40" value="{MENU_URL}" /></td>
	</tr>
	<tr>
		<td class="row1"><b>{L_MENU_ACTIVE}:</b></td>
		<td class="row2"><input type="radio" name="menu_value" value="1"{MENU_ON} /> {L_ENABLED}&nbsp;&nbsp;<input type="radio" name="menu_value" value="0"{MENU_OFF} /> {L_DISABLED}</td>
	</tr>
	<tr>
		<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input class="mainoption" type="submit" value="{L_SUBMIT}" /></td>
	</tr>
</form></table>
<br />
<div align="center" class="copyright">Admin Navlink Configuration 1.0.0 &copy; 2003, {COPYRIGHT_YEAR} <a href="http://phpbb-fm.com" target="_blank" class="copyright">MJ</a></div>