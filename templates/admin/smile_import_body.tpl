{SMILEY_MENU}{POST_MENU}
{MOD_CP_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<script language="javascript" type="text/javascript">
<!--
function unlockdrop(filename)
{
	var exten = filename.split(".");

	if( exten[1] == 'pak' )
	{
		eval("document.import.import_cat.disabled = false");
	}
	else
	{
		eval("document.import.import_cat.disabled = true");
	}
}
// -->
</script>

<h1>{L_IMPORT_TITLE}</h1>

<p>{L_IMPORT_DESCRIPTION}</p>

<table cellspacing="1" cellpadding="4" align="center" width="100%" class="forumline"><form action="{S_SMILEY_CAT_ACTION}" method="post" name="import">
<tr>
	<th class="thHead" colspan="2">{L_IMPORT_TITLE}</th>
</tr>
<tr>
	<td class="row2" colspan="2"><span class="gensmall">{L_ITEMS_REQUIRED}</span></td>
</tr>
<tr> 
	<td class="row1" width="50%"><b>{L_SELECT_PAK}:</b></td>
	<td class="row2"><select name="smile_pak" onChange="unlockdrop(this.options[selectedIndex].value)">
		{S_CAT_PAK}
	</select></td>
</tr>
<tr> 
	<td class="row1"><b>{L_DEL_EXISTING_ALL}:</b></td>
	<td class="row2"><input type="radio" name="del_all" value="1" /> {L_YES}&nbsp;&nbsp;<input type="radio" name="del_all" value="0" checked="checked" /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_SELECT_IMPORT}: *</b><br /><span class="gensmall">{L_IMPORT_DESC_EXPLAIN}</span></td>
	<td class="row2"><select name="import_cat" disabled="disabled">
		{S_CAT_IMPORT}
	</select></td>
</tr>
<tr> 
	<td class="row1"><b>{L_DEL_EXISTING}: *</b><br /><span class="gensmall">{L_IMPORT_DESC_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="del_smiley" value="1" /> {L_YES}&nbsp;&nbsp;<input type="radio" name="del_smiley" value="0" checked="checked" /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_CONFLICTS}: *</b><br /><span class="gensmall">{L_IMPORT_DESC_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="replace" value="1" checked="checked" /> {L_REPLACE_EXISTING}&nbsp;&nbsp;<input type="radio" name="replace" value="0" /> {L_KEEP_EXISTING}</td>
</tr>
<tr> 
	<td class="catBottom" colspan="2" align="center"><input class="mainoption" name="import_pack_submit" type="submit" value="{L_SUBMIT}" />&nbsp;<input type="reset" name="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</form></table>
<br />
<div align="center" class="copyright">Smiley Categories 2.0.4</a> &copy; 2004, {COPYRIGHT_YEAR} <a href="http://mods.afkamm.co.uk" target="_blank" class="copyright">Afkamm</a></div>
