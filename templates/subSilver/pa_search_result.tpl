<!-- INCLUDE pa_header.tpl -->
<table width="100%" cellspacing="2" cellpadding="2" align="center">
  <tr> 
	<td align="left" valign="bottom"><span class="maintitle">{L_SEARCH_MATCHES}</span><br /></td>
  </tr>
</table>

<table width="100%" cellpadding="2" cellspacing="2">
  <tr>
	<td valign="bottom">
		<span class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> <a href="{U_DOWNLOAD}" class="nav">{DOWNLOAD}</a> -> {L_SEARCH}</span>
	</td>
  </tr>
</table>

<table width="100%" cellpadding="3" cellspacing="1" class="forumline">
  <tr> 
	<th class="thCornerL" colspan="2" nowrap="nowrap">{L_FILE}&nbsp;</th>
	<th class="thTop" nowrap="nowrap">&nbsp;{L_CATEGORY}&nbsp;</th>
	<th class="thTop" nowrap="nowrap">&nbsp;{L_SUBMITER}&nbsp;</th>
	<th class="thTop" nowrap="nowrap">&nbsp;{L_DATE}&nbsp;</th>
	<th class="thTop" nowrap="nowrap">&nbsp;{L_DOWNLOADS}&nbsp;</th>
	<th class="thCornerR" nowrap="nowrap">&nbsp;{L_RATE}&nbsp;</th>
  </tr>

 <!-- BEGIN searchresults -->
  <tr> 
	<td width="4%" class="row1" align="center" valign="middle"><a href="{searchresults.U_FILE}" class="topictitle"><img src="{searchresults.PIN_IMAGE}" alt="" title="" /></a></td>
	<td class="row1" width="38%" valign="middle"><a href="{searchresults.U_FILE}" class="topictitle">{searchresults.FILE_NAME}</a>&nbsp;<!-- IF searchresults.IS_NEW_FILE --><img src="{searchresults.FILE_NEW_IMAGE}" alt="{L_NEW_FILE}"><!-- ENDIF --><br><span class="genmed">{searchresults.FILE_DESC}</span></td>
	<td class="row1"><span class="forumlink"><a href="{searchresults.U_CAT}" class="forumlink">{searchresults.CAT_NAME}</a></span></td>
	<td class="row1" align="center" valign="middle"><span class="name">{searchresults.FILE_SUBMITER}</span></td>
	<td class="row2" align="center" valign="middle"><span class="postdetails">{searchresults.DATE}</span></td>
	<td class="row1" align="center" valign="middle"><span class="postdetails">{searchresults.DOWNLOADS}</span></td>
	<td class="row2" align="center" valign="middle" nowrap="nowrap"><span class="postdetails">{searchresults.RATING}</span></td>
  </tr> 
 <!-- END searchresults -->
  <tr> 
	<td class="cat" colspan="7">&nbsp;</td>
  </tr>
</table>
<table width="100%" cellspacing="2" align="center" cellpadding="2">
  <tr> 
	<td align="left" valign="top"><span class="nav">{PAGE_NUMBER}</span></td>
	<td align="right" valign="top" nowrap="nowrap"><span class="nav">{PAGINATION}</span></td>
  </tr>
</table>
<!-- INCLUDE pa_footer.tpl -->