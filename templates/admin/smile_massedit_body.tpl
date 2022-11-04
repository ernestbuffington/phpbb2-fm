{SMILEY_MENU}{POST_MENU}
{MOD_CP_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<script language="javascript" type="text/javascript">
<!--
	function orderVals(url, number)
	{
		var orderArray = number.split('|');
		document.location.href = url + "&old=" + orderArray[0] + "&new=" + orderArray[1];
	}

	function check_switch(val)
	{
		for( i = 0; i < document.smiley_list.elements.length; i++ )
		{
			document.smiley_list.elements[i].checked = val;
		}
	}
//-->
</script>

<h1>{L_PAGE_TITLE}</h1>

<p>{L_PAGE_DESCRIPTION}</p>

<table cellspacing="1" cellpadding="4" width="100%" align="center" class="forumline"><form method="post" action="{S_SMILEY_ACTION}" name="smiley_list">
<tr>
	<th class="thCornerL" nowrap="nowrap">&nbsp;{L_CATEGORY}&nbsp;</th>
	<th colspan="2" class="thTop" nowrap="nowrap">&nbsp;{L_CATEGORY_DESC}&nbsp;</th>
	<th colspan="3" class="thCornerR" nowrap="nowrap">&nbsp;{L_SMILEY_COUNT}&nbsp;</th>
</tr>
<tr>
	<td class="row1" nowrap="nowrap">{S_CAT_NAME}</td>
	<td width="60%" colspan="2" class="row1">{S_CAT_DESCRIPTION}</td>
	<td colspan="3" class="row1" align="center">{SMILEY_COUNT}</td>
</tr>
<tr>
	<th class="thCornerL" nowrap="nowrap">&nbsp;{L_CODE}&nbsp;</th>
	<th class="thTop" nowrap="nowrap">&nbsp;{L_SMILE}&nbsp;</th>
	<th class="thTop" nowrap="nowrap">&nbsp;{L_EMOT}&nbsp;</th>
	<th class="thTop" nowrap="nowrap">&nbsp;{L_MOVE}&nbsp;</th>
	<th class="thTop" nowrap="nowrap">&nbsp;{L_ORDER}&nbsp;</th>
	<th class="thCornerR" nowrap="nowrap">&nbsp;{L_DELETE}&nbsp;</th>
</tr>
<!-- BEGIN smiles -->
<tr>
	<td class="{smiles.ROW_CLASS}"><input class="post" type="text" size="15" name="code{smiles.SMILEY_ID}" value="{smiles.SMILEY_CODE}" /></td>
	<td class="{smiles.ROW_CLASS}" align="center" width="20%"><img src="{smiles.SMILEY_IMG}" alt="{smiles.SMILEY_URL}" title="{smiles.SMILEY_URL}" /></td>
	<td class="{smiles.ROW_CLASS}"><input class="post" type="text" size="25" name="emot{smiles.SMILEY_ID}" value="{smiles.SMILEY_EMOTICON}" /></td>
	<td class="{smiles.ROW_CLASS}"><select name="cat{smiles.SMILEY_ID}">{smiles.CATEGORY_LIST}</select></td>
	<td class="{smiles.ROW_CLASS}" align="center"><select name="order{smiles.SMILEY_ID}" onChange="orderVals('{smiles.SMILEY_ORDER_ACTION}',this.options[selectedIndex].value);">{smiles.SMILEY_ORDER}</select></td>
	<td class="{smiles.ROW_CLASS}" align="center"><input type="hidden" name="id{smiles.SMILEY_COUNT}" value="{smiles.SMILEY_ID}" /><input type="checkbox" name="del{smiles.SMILEY_ID}" value="1" /></td>
</tr>
<!-- END smiles -->
{S_CAT_EMPTY}
<tr>
	<td colspan="6" class="catBottom" align="center"><input class="liteoption" type="submit" name="smiley_add" value="{L_SMILEY_ADD}" />&nbsp;&nbsp;<input class="mainoption" type="submit" name="mass_edit_submit" value="{L_MASS_EDIT_SUBMIT}" /></td>
</tr>
{S_HIDDEN_FIELDS}
</form></table>
<table cellspacing="2" cellpadding="2" width="100%" align="center">
<tr>
	<td valign"top" class="nav">{S_PAGINATION}</span></td>
	<td align="right" nowrap="nowrap"><b class="gensmall"><a href="javascript:check_switch(true);" class="gensmall">{L_TICK_ALL}</a> :: <a href="javascript:check_switch();" class="gensmall">{L_UNTICK_ALL}</a></b></td>
</tr>
</table>
<br />
<div align="center" class="copyright">Smiley Categories 2.0.4</a> &copy; 2004, {COPYRIGHT_YEAR} <a href="http://mods.afkamm.co.uk" target="_blank" class="copyright">Afkamm</a></div>
