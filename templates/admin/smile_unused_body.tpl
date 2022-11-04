{SMILEY_MENU}{POST_MENU}
{MOD_CP_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<script language="JavaScript" type="text/javascript">
<!--
	function check_switch(val)
	{
		for( i = 0; i < document.post.elements.length; i++ )
		{
			document.post.elements[i].checked = val;
		}
	}
//-->
</script>

<h1>{L_TITLE}</h1>

<p>{L_DESCRIPTION}</p>

<table cellspacing="1" cellpadding="4" width="100%" align="center" class="forumline"><form method="post" action="{S_SMILEY_CAT_ACTION}">
<tr>
	<th class="thHead" colspan="2">{L_TITLE}</th>
</tr>
<tr>
	<td class="row1" width="38%"><b>{L_SMILEY_FILENAME_CODE}:</b></td>
	<td class="row2"><input type="radio" name="code" value="1"{CODE1} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="code" value="0"{CODE2} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_PER_PAGE}:</b></td>
	<td class="row2"><input class="post" type="text" name="per_page" value="{PER_PAGE}" size="5" maxlength="3" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_SELECT_CAT}:</b></td>
	<td class="row2"><select name="cat">{S_CAT_LIST}</select></td>
</tr>
<tr>
	<td colspan="2" class="catBottom" align="center"><input type="submit" value="{L_REFRESH}" name="unused_smilies" class="mainoption" /></td>
</tr>
</form></table>
<br />

<table cellspacing="1" cellpadding="4" width="100%" align="center" class="forumline"><form method="post" action="{S_SMILEY_ACTION}" name="post">
<tr>
	<th nowrap="nowrap" class="thCornerL">&nbsp;{L_CODE}&nbsp;</th>
	<th nowrap="nowrap" class="thTop" width="60%">&nbsp;{L_SMILE}&nbsp;</th>
	<th nowrap="nowrap" class="thTop">&nbsp;{L_EMOTION}&nbsp;</th>
	<th nowrap="nowrap" class="thTop">&nbsp;{L_CATEGORY}&nbsp;</th>
	<th nowrap="nowrap" class="thTop">&nbsp;#&nbsp;</th>
	<th nowrap="nowrap" class="thCornerR">&nbsp;{L_ADD}&nbsp;</th>
</tr>
<!-- BEGIN smiles -->
<tr>
	<td class="{smiles.ROW_CLASS}"><input class="post" type="text" size="15" name="code{smiles.SMILEY_ID}" value="{smiles.SMILEY_CODE}" /></td>
	<td class="{smiles.ROW_CLASS}" align="center"><img src="{smiles.SMILEY_IMG}" alt="{smiles.SMILEY_URL}" title="{smiles.SMILEY_URL}" /"></td>
	<td class="{smiles.ROW_CLASS}"><input class="post" type="text" size="25" name="emot{smiles.SMILEY_ID}" value="" /></td>
	<td class="{smiles.ROW_CLASS}"><select name="cat{smiles.SMILEY_ID}">{smiles.CATEGORY_LIST}</select></td>
	<td class="{smiles.ROW_CLASS}" align="center"><input type="hidden" name="url{smiles.SMILEY_ID}" value="{smiles.SMILEY_URL}" /><span class="genmed">{smiles.SMILEY_ORDER}</span></td>
	<td class="{smiles.ROW_CLASS}" align="center"><input type="checkbox" name="add{smiles.SMILEY_ID}" value="1" /></td>
</tr>
<!-- END smiles -->
<tr>
	<td colspan="6" class="catBottom" align="center">{S_HIDDEN_FIELDS}<input type="submit" value="{L_SUBMIT}" name="unused_submit" class="mainoption" /></td>
</tr>
</form></table>
<table cellspacing="2" cellpadding="2" width="100%" align="center">
<tr>
	<td class="nav">{S_PAGINATION}</td>
	<td align="right" nowrap="nowrap"><b class="gensmall"><a href="javascript:check_switch(true);" class="gensmall">{L_TICK_ALL}</a> :: <a href="javascript:check_switch();" class="gensmall">{L_UNTICK_ALL}</a></td>
</tr>
</table>
<br />
<div align="center" class="copyright">Smiley Categories 2.0.4</a> &copy; 2004, {COPYRIGHT_YEAR} <a href="http://mods.afkamm.co.uk" target="_blank" class="copyright">Afkamm</a></div>
