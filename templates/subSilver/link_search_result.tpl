<table width="100%" cellspacing="2" cellpadding="2" align="center">
  <tr> 
	<td valign="bottom"><span class="maintitle">{L_SEARCH_MATCHES}</span><br /></td>
  </tr>
</table>

<table width="100%" cellpadding="2" cellspacing="2">
  <tr>
	<td valign="bottom">
		<span class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> <a href="{U_LINK}" class="nav">{LINKS}</a> -> {L_SEARCH}</span>
	</td>
  </tr>
</table>

<!-- BEGIN no_split_links -->
<table width="100%" cellpadding="3" cellspacing="1" class="forumline">
<!-- END no_split_links -->
  <!-- tr> 
	<th colspan="2" class="thTop" width="57%">{L_FILE}</th>
  </tr -->
<!-- BEGIN searchresults -->
  <!-- BEGIN split_links -->
  <table width="100%" cellpadding="3" cellspacing="1" class="forumline">
  <!-- END split_links -->
  <tr>
	<td rowspan="2" class="{searchresults.COLOR}" valign="top" align="center">&nbsp;{searchresults.LINK_LOGO}<br /><span class="gensmall">{searchresults.RECOM_LINK}</span></td>
	<td width="100%" class="{searchresults.COLOR}">
	<a href="{searchresults.U_FILE}" class="topictitle" target="_blank">{searchresults.FILE_NAME}</a>&nbsp;
	<!-- BEGIN IS_NEW_FILE -->
	<img src="{searchresults.FILE_NEW_IMAGE}" alt="{L_NEW_FILE}" title="{L_NEW_FILE}" />
	<!-- END IS_NEW_FILE -->
	<br><span class="genmed">{searchresults.FILE_DESC}</span>
	</td>
  </tr>
  <tr>
	<td valign="top" align="center" class="{searchresults.COLOR}"><span class="genmed">{L_DATE}:&nbsp;{searchresults.DATE}&nbsp;,&nbsp;{L_DOWNLOADS}: {searchresults.FILE_DLS}&nbsp;,&nbsp;{L_SUBMITER}&nbsp;{searchresults.FILE_SUBMITER}&nbsp;
	<!-- BEGIN LINK_VOTE -->
	,&nbsp;{searchresults.L_RATING}: {searchresults.RATING}<!-- ({searchresults.FILE_VOTES} {L_VOTES}) -->
	<!-- END LINK_VOTE -->
	<!-- BEGIN LINK_COMMENT -->
	,&nbsp;{searchresults.L_COMMENTS}: {searchresults.FILE_COMMENTS}
	<!-- END LINK_COMMENT -->
	,&nbsp;{L_CATEGORY}:&nbsp;{searchresults.CAT_NAME}
	<br />
	<!-- BEGIN custom_field -->
	{searchresults.custom_field.CUSTOM_NAME}: {searchresults.custom_field.DATA},
	<!-- END custom_field -->
	<!-- BEGIN AUTH_EDIT -->  
	&nbsp;<a href="{searchresults.U_EDIT}"><img src="{EDIT_IMG}" alt="{L_EDIT}" title="{L_EDIT}" /></a>
	<!-- END AUTH_EDIT -->
	<!-- BEGIN AUTH_DELETE -->  
	&nbsp;<a href="{searchresults.U_DELETE}"><img src="{DELETE_IMG}" alt="{L_DELETE}" title="{L_DELETE}" /></a>
	<!-- END AUTH_DELETE -->
	</span></td>
  </tr>
  <!-- BEGIN split_links -->
  </table><br />
  <!-- END split_links -->
 <!-- END searchresults -->

<!-- BEGIN no_split_links -->
</table>
<!-- END no_split_links -->

<table width="100%" cellspacing="2" align="center" cellpadding="2">
  <tr> 
	<td valign="top"><span class="nav">{PAGE_NUMBER}</span></td>
	<td align="{S_CONTENT_DIR_RIGHT}" valign="top" nowrap="nowrap"><span class="nav">{PAGINATION}</span></td>
  </tr>
</table>