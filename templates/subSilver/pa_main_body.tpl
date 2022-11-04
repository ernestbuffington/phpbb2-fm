<!-- INCLUDE pa_header.tpl -->
<table align="center" width="100%" cellpadding="2" cellspacing="2">
  <tr>
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> <a href="{U_DOWNLOAD}" class="nav">{DOWNLOAD}</a></td>
  </tr>
</table>

<table align="center" width="100%" cellpadding="4" cellspacing="1" class="forumline">
  <tr>
	<th colspan="2" class="thCornerL" nowrap="nowrap">&nbsp;{L_CATEGORY}&nbsp;</th>
	<th class="thTop" width="150" nowrap="nowrap">&nbsp;{L_LAST_FILE}&nbsp;</th>	
	<th class="thCornerR" width="50" nowrap="nowrap">&nbsp;{L_FILES}&nbsp;</th>
  </tr>
<!-- BEGIN no_cat_parent -->
<!-- IF no_cat_parent.IS_HIGHER_CAT -->
<tr>
	<td class="cat" colspan="2" valign="middle"><a href="{no_cat_parent.U_CAT}" class="cattitle">{no_cat_parent.CAT_NAME}</a></td>
	<td class="rowpic" colspan="2">&nbsp;</td>
</tr>
	<!-- ELSE -->
<tr>
	<td class="row1" valign="middle" align="center"><a href="{no_cat_parent.U_CAT}"><img src="{no_cat_parent.CAT_IMAGE}" alt="{no_cat_parent.CAT_NEW_FILE}" title="{no_cat_parent.CAT_NEW_FILE}" /></a></td>
	<td class="row1" valign="middle" width="100%"><a href="{no_cat_parent.U_CAT}" class="forumlink">{no_cat_parent.CAT_NAME}</a><br /><span class="genmed">{no_cat_parent.CAT_DESC}</span><br /><span class="gensmall"><b>{L_SUB_CAT}:</b> </span><span class="gensmall">{no_cat_parent.SUB_CAT}</span></b></td>
	<td class="row2" align="center" valign="middle" nowrap="nowrap"><span class="postdetails">{no_cat_parent.LAST_FILE}</span></td>
	<td class="row2" align="center" valign="middle"><span class="genmed">{no_cat_parent.FILECAT}</span></td>
</tr>
	<!-- ENDIF -->
<!-- END no_cat_parent -->
  <tr> 
	<td class="catBottom" colspan="4">&nbsp;</td>
  </tr>
</table>
<!-- INCLUDE pa_footer.tpl -->