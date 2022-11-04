<!-- INCLUDE pa_header.tpl -->
<table width="100%" cellpadding="2" cellspacing="2">
  <tr>
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> <a href="{U_DOWNLOAD}" class="nav">{DOWNLOAD}</a> -> {L_VIEWALL}</td>
  </tr>
</table>

<table width="100%" cellpadding="4" cellspacing="1" class="forumline">
  <tr> 
	<th class="thCornerL" colspan="2" nowrap="nowrap">&nbsp;{L_FILE}&nbsp;</th>
	<th class="thTop" width="150" nowrap="nowrap">&nbsp;{L_CATEGORY}&nbsp;</th>
	<th class="thTop" width="150" nowrap="nowrap">&nbsp;{L_DATE}&nbsp;</th>
	<th class="thTop" width="50" nowrap="nowrap">&nbsp;{L_DOWNLOADS}&nbsp;</th>
	<th class="thTop" width="50" nowrap="nowrap">&nbsp;{L_RATING}&nbsp;</th>
	<th class="thCornerR" width="1%">&nbsp;</th>
  </tr>
<!-- BEGIN file_rows -->
  <tr> 
	<td class="row1" align="center" valign="middle" width="1%"><a href="{file_rows.U_FILE}" class="topictitle"><img src="{file_rows.PIN_IMAGE}" alt="" title="" /></a></td>
	<td class="row1" valign="middle" width="50%"><a href="{file_rows.U_FILE}" class="topictitle">{file_rows.FILE_NAME}</a>&nbsp;<!-- IF file_rows.IS_NEW_FILE --><img src="{file_rows.FILE_NEW_IMAGE}" alt="{file_rows.L_NEW_FILE}" title="{file_rows.L_NEW_FILE}" /><!-- ENDIF --><br /><span class="genmed">{file_rows.FILE_DESC}</span></td>
	<td class="row1" align="center" valign="middle"><span class="forumlink"><a href="{file_rows.U_CAT}" class="forumlink">{file_rows.CAT_NAME}</a></span></td>
	<td class="row2" align="center" valign="middle"><span class="postdetails">{file_rows.DATE}</span></td>
	<td class="row1" align="center" valign="middle"><span class="postdetails">{file_rows.FILE_DLS}</span></td>
	<td class="row2" align="center" valign="middle"><span class="postdetails">{file_rows.RATING}</span></td>
	<td class="row2" align="center" valign="middle">
	<!-- IF file_rows.HAS_SCREENSHOTS -->
		<!-- IF file_rows.SS_AS_LINK -->
		<a href="{file_rows.FILE_SCREENSHOT}" class="topictitle" target="_blank"><img src="{file_rows.FILE_SCREENSHOT_URL}" alt="{L_SCREENSHOTS}" title="{L_SCREENSHOTS}" /></a>
		<!-- ELSE -->
		<a href="javascript:mpFoto('{file_rows.FILE_SCREENSHOT}')" class="topictitle"><img src="{file_rows.FILE_SCREENSHOT_URL}" alt="{L_SCREENSHOTS}" title="{L_SCREENSHOTS}" /></a>
		<!-- ENDIF -->
	<!-- ELSE -->
	&nbsp;
	<!-- ENDIF -->
	</td>
  </tr>
<!-- END file_rows -->

<form action="{S_VIEWALL_ACTION}" method="post">
<input type="hidden" name="action" value="viewall"><input type="hidden" name="start" value="{START}">
  <tr> 
	<td class="catBottom" align="center" colspan="7"><span class="genmed">{L_SELECT_SORT_METHOD}:&nbsp;
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
</table>
<table width="100%" cellspacing="2" cellpadding="2">
  <tr>
	<td nowrap="nowrap" class="nav">{PAGINATION}</td>
	<td align="right" nowrap="nowrap" class="nav">{PAGE_NUMBER}</td>
  </tr>
</form></table>
<!-- INCLUDE pa_footer.tpl -->
