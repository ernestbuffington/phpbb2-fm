<!-- INCLUDE pa_header.tpl -->

<table width="100%" cellpadding="2" cellspacing="2">
  <tr>
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> <a href="{U_DOWNLOAD}" class="nav">{DOWNLOAD}</a><!-- BEGIN navlinks --> -> <a href="{navlinks.U_VIEW_CAT}" class="nav">{navlinks.CAT_NAME}</a><!-- END navlinks --></td>
  </tr>
</table>

<!-- IF CAT_PARENT -->
<table width="100%" cellpadding="4" cellspacing="1" class="forumline">
<tr> 
	<th class="thCornerL" colspan="2" nowrap="nowrap">&nbsp;{L_CATEGORY}&nbsp;</th>
	<th class="thTop" width="150" nowrap="nowrap">&nbsp;{L_LAST_FILE}&nbsp;</th>
	<th class="thCornerR" width="50" nowrap="nowrap">&nbsp;{L_FILES}&nbsp;</th>
</tr>
<!-- BEGIN no_cat_parent -->
<!-- IF no_cat_parent.IS_HIGHER_CAT -->
<tr>
	<td class="cat" colspan="2" valign="middle"><a href="{no_cat_parent.U_CAT}" class="cattitle">{no_cat_parent.CAT_NAME}</a></td>
	<td class="rowpic" colspan="2" align="right">&nbsp;</td>
</tr>
<!-- ELSE -->
<tr> 
	<td class="row1" align="center" valign="middle"><a href="{no_cat_parent.U_CAT}" class="cattitle"><img src="{no_cat_parent.CAT_IMAGE}" alt="{no_cat_parent.CAT_NEW_FILE}" title="{no_cat_parent.CAT_NEW_FILE}" ></a></td>
	<td class="row1" valign="middle" width="100%"><a href="{no_cat_parent.U_CAT}" class="topictitle">{no_cat_parent.CAT_NAME}</a><br /><span class="genmed">{no_cat_parent.CAT_DESC}</span><br /><span class="gensmall"><b>{L_SUB_CAT}:</b> </span><span class="gensmall">{no_cat_parent.SUB_CAT}</span></td>
	<td class="row2" align="center" valign="middle" nowrap="nowrap"><span class="genmed">{no_cat_parent.LAST_FILE}</span></td>
	<td class="row2" align="center" valign="middle"><span class="genmed">{no_cat_parent.FILECAT}</span></td>
</tr>
<!-- ENDIF -->  
<!-- END no_cat_parent -->
<tr> 
	<td class="catBottom" colspan="4">&nbsp;</td>
</tr>
</table>
<br />
<!-- ENDIF -->

<!-- IF FILELIST -->
<table width="100%" cellpadding="4" cellspacing="1" class="forumline"><form action="{S_ACTION_SORT}" method="post">
<tr> 
	<th class="thCornerL" colspan="2" nowrap="nowrap">&nbsp;{L_FILE}&nbsp;</th>
	<th class="thTop" width="150" nowrap="nowrap">&nbsp;{L_DATE}&nbsp;</th>
	<th class="thTop" width="50" nowrap="nowrap">&nbsp;{L_DOWNLOADS}&nbsp;</th>
	<th class="thTop" width="50" nowrap="nowrap">&nbsp;{L_RATING}&nbsp;</th>
	<th class="thCornerR" width="1%">&nbsp;</th>
</tr>
<!-- BEGIN file_rows -->
  <tr> 
	<td class="row1" align="center" valign="middle"><a href="{file_rows.U_FILE}"><img src="{file_rows.PIN_IMAGE}" alt="" title="" /></a></td>
	<td class="row1" valign="middle" width="100%"><a href="{file_rows.U_FILE}" class="topictitle">{file_rows.FILE_NAME}</a>&nbsp;<!-- IF file_rows.IS_NEW_FILE --><img src="{file_rows.FILE_NEW_IMAGE}" alt="{file_rows.L_NEW_FILE}" title="{file_rows.L_NEW_FILE}" /><!-- ENDIF --><br /><span class="genmed">{file_rows.FILE_DESC}</span></td>
	<td class="row2" align="center" valign="middle" nowrap="nowrap"><span class="postdetails">{file_rows.DATE}</span></td>
	<td class="row1" align="center" valign="middle"><span class="postdetails">{file_rows.FILE_DLS}</span></td>
	<td class="row2" align="center" valign="middle" nowrap="nowrap"><span class="postdetails">{file_rows.RATING}</span></td>
	<td class="row2" align="center" valign="middle">
	<!-- IF file_rows.HAS_SCREENSHOTS -->
		<!-- IF file_rows.SS_AS_LINK -->
		<a href="{file_rows.FILE_SCREENSHOT}" target="_blank"><img src="{file_rows.FILE_SCREENSHOT_URL}" alt="{L_SCREENSHOTS}" title="{L_SCREENSHOTS}" /></a>
		<!-- ELSE -->
		<a href="javascript:mpFoto('{file_rows.FILE_SCREENSHOT}')"><img src="{file_rows.FILE_SCREENSHOT_URL}" alt="{L_SCREENSHOTS}" title="{L_SCREENSHOTS}" /></a>
		<!-- ENDIF -->
	<!-- ELSE -->
	&nbsp;
	<!-- ENDIF -->
	</td>
  </tr>
<!-- END file_rows -->
<tr> 
	<td class="catBottom" align="center" colspan="6"><input type="hidden" name="action" value="category"><input type="hidden" name="cat_id" value="{ID}"><input type="hidden" name="start" value="{START}"><span class="genmed">{L_SELECT_SORT_METHOD}:&nbsp;
	<select name="sort_method">
		<option {SORT_NAME} value="file_name">{L_NAME}</option>
		<option {SORT_TIME} value="file_time">{L_DATE}</option>
		<option {SORT_RATING} value="file_rating">{L_RATING}</option>
		<option {SORT_DOWNLOADS} value="file_dls">{L_DOWNLOADS}</option>
		<option {SORT_UPDATE_TIME} value="file_update_time">{L_UPDATE_TIME}</option>
	</select>
		&nbsp;{L_ORDER}:
		<select name="sort_order">
			<option {SORT_ASC} value="ASC">{L_ASC}</option>
			<option {SORT_DESC} value="DESC">{L_DESC}</option>
		</select>
	&nbsp;<input type="submit" name="submit" value="{L_SORT}" class="liteoption" />
	</span></td>
</tr>
</form></table>
<table width="100%" cellspacing="2" cellpadding="2">
  <tr>
	<td nowrap="nowrap" class="nav">{PAGINATION}</td>
	<td align="right" nowrap="nowrap" class="nav">{PAGE_NUMBER}</td>
  </tr>
</table>
<!-- ENDIF -->

<!-- IF NO_FILE -->
<table class="forumline" width="100%" cellspacing="1" cellpadding="4">
<tr>
	<th class="thHead">{L_NO_FILES}</th>
</tr>
<tr> 
	<td class="row1" align="center" height="30"><span class="gen">{L_NO_FILES_CAT}</span></td>
</tr>
</table> 
<!-- ENDIF -->

<!-- INCLUDE pa_footer.tpl -->