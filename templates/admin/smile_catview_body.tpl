{SMILEY_MENU}{POST_MENU}
{MOD_CP_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<!-- BEGIN cat_select -->
<h1>{L_CAT_VIEW_TITLE}</h1>

<p>{L_CAT_VIEW_DESC}</p>

<table align="center" cellpadding="4" cellspacing="1" width="100%" class="forumline"><form action="{cat_select.S_SMILEY_ACTION}" method="post">
<tr>
	<th class="thHead">{L_CAT_VIEW_TITLE}</th>
</tr>
<tr>
	<td align="center" class="row1"><select multiple="multiple" name="multi_cats[]" size="8">
		{cat_select.S_CAT_VIEW}
	</select></td>
</tr>
<tr>
	<td align="center" class="catBottom"><input type="submit" value="{L_SUBMIT}" name="cat_view" class="mainoption" /></td>
</tr>
</form></table>
<br />
<!-- END cat_select -->

<!-- BEGIN cat_view -->
<script language="javascript" type="text/javascript">
<!--
	function editsmiley(url)
	{
		document.location.href = url + "&multi_cats={cat_view.MULTI_CATS}";
	}
//-->
</script>

<h1>{L_PAGE_TITLE}</h1>

<p>{L_PAGE_DESCRIPTION}</p>

<table cellpadding="4" cellspacing="1" width="100%" align="center" class="forumline">
<tr>
	<th nowrap="nowrap" class="thCornerL">&nbsp;#&nbsp;</th>
	<th nowrap="nowrap" class="thTop">&nbsp;{L_CATEGORY_NAME}&nbsp;</th>
	<th nowrap="nowrap" class="thTop">&nbsp;{L_CATEGORY_DESC}&nbsp;</th>
	<th nowrap="nowrap" class="thTop">&nbsp;{L_SMILEY_COUNT}&nbsp;</th>
	<th nowrap="nowrap" class="thCornerR">&nbsp;{L_CATEGORY_OPTIONS}&nbsp;</th> 
</tr>
<!-- BEGIN smile_categories -->
<tr> 
	<td class="{cat_view.smile_categories.ROW_CLASS}" align="center">{cat_view.smile_categories.CATEGORY_NUM}</td>
	<td class="{cat_view.smile_categories.ROW_CLASS}">{cat_view.smile_categories.CATEGORY_NAME}</td>
	<td width="55%" class="{cat_view.smile_categories.ROW_CLASS}">{cat_view.smile_categories.CATEGORY_DESC}</td>
	<td nowrap="nowrap" class="{cat_view.smile_categories.ROW_CLASS}" align="center">{cat_view.smile_categories.SMILEY_COUNT}</td>
	<td nowrap="nowrap" class="{cat_view.smile_categories.ROW_CLASS}" align="center"><a href="{cat_view.smile_categories.S_CATEGORY_EDIT}">{L_MASS_EDIT}</a></td>
</tr>
<tr>
	<td class="{cat_view.smile_categories.ROW_CLASS}" colspan="5">{cat_view.smile_categories.S_SMILEY_LIST}</td>
</tr>
<tr>
	<td colspan="5" height="1" class="spaceRow"><img src="../images/spacer.gif" alt="" title="" width="1" height="1" /></td>
</tr>
<!-- END smile_categories -->
</table>
<!-- END cat_view -->
<br />
<div align="center" class="copyright">Smiley Categories 2.0.4</a> &copy; 2004, {COPYRIGHT_YEAR} <a href="http://mods.afkamm.co.uk" target="_blank" class="copyright">Afkamm</a></div>
