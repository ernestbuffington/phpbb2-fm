<!-- INCLUDE pa_header.tpl -->
<table align="center" width="100%" cellpadding="2" cellspacing="2">
  <tr>
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> <a href="{U_DOWNLOAD}" class="nav">{DOWNLOAD}</a> -> {L_STATISTICS}</td>
  </tr>
</table>

<table align="center" width="100%" cellpadding="4" cellspacing="1" class="forumline">
  <tr> 
	<th colspan="2" class="thHead">{L_STATISTICS}</th>
  </tr>
  <tr> 
	<td colspan="2" class="catSides" align="center"><span class="cattitle">{L_GENERAL_INFO}</span></td>
  </tr>  
  <tr>
	<td colspan="2" class="row1"><span class="gen">{STATS_TEXT}</span></td>
  </tr>
  <tr> 
	<th width="50%" class="thCornerL">{L_DOWNLOADS_STATS}</th>
	<th width="50%" class="thCornerR">{L_RATING_STATS}</th>
  </tr>  
  <tr> 
	<td colspan="2" class="catSides" align="center"><span class="cattitle">{L_OS}</span></td>
  </tr>    
  <tr> 
	<td class="row1" align="center"><table cellspacing="0" cellpadding="2">
	<!-- BEGIN downloads_os -->
	<tr> 
		<td><img src="{downloads_os.OS_IMG}" alt="" />&nbsp;<span class="gen">{downloads_os.OS_NAME}</span></td>
		<td><table cellspacing="0" cellpadding="0">
		<tr> 
			<td><img src="{U_VOTE_LCAP}" width="4" alt="" height="12" alt="{downloads_os.OS_OPTION_RESULT}" title="{downloads_os.OS_OPTION_RESULT}" /></td>
			<td><img src="{downloads_os.OS_OPTION_IMG}" width="{downloads_os.OS_OPTION_IMG_WIDTH}" height="12" alt="{downloads_os.OS_OPTION_RESULT}" title="{downloads_os.OS_OPTION_RESULT}" /></td>
			<td><img src="{U_VOTE_RCAP}" width="4" alt="" height="12" alt="{downloads_os.OS_OPTION_RESULT}" title="{downloads_os.OS_OPTION_RESULT}" /></td>
		</tr>
		</table></td>
		<td align="center"><span class="gen">[ {downloads_os.OS_OPTION_RESULT} ]</span></td>
	</tr>
	<!-- END downloads_os -->
	</table></td>
	<td class="row1" align="center"><table cellspacing="0" cellpadding="2">
	<!-- BEGIN rating_os -->
	<tr> 
		<td class="gen"><img src="{rating_os.OS_IMG}" alt="" title="" />&nbsp;{rating_os.OS_NAME}</td>
		<td><table cellspacing="0" cellpadding="0">
		<tr> 
			<td><img src="{U_VOTE_LCAP}" width="4" alt="{rating_os.OS_OPTION_RESULT}" title="{rating_os.OS_OPTION_RESULT}" height="12" /></td>
			<td><img src="{rating_os.OS_OPTION_IMG}" width="{rating_os.OS_OPTION_IMG_WIDTH}" height="12" alt="{rating_os.OS_OPTION_RESULT}" title="{rating_os.OS_OPTION_RESULT}" /></td>
			<td><img src="{U_VOTE_RCAP}" width="4" alt="{rating_os.OS_OPTION_RESULT}" title="{rating_os.OS_OPTION_RESULT}" height="12" /></td>
		</tr>
		</table></td>
		<td align="center"><span class="gen">[ {rating_os.OS_OPTION_RESULT} ]</span></td>
	</tr>
	<!-- END rating_os -->
	</table></td>
  </tr>
  <tr> 
	<td colspan="2" class="catSides" align="center"><span class="cattitle">{L_BROWSERS}</span></td>
  </tr>
  <tr> 
	<td class="row1" align="center"><table cellspacing="0" cellpadding="2">
	<!-- BEGIN downloads_b -->
	<tr> 
		<td class="gen"><img src="{downloads_b.B_IMG}" alt="" title="" />&nbsp;{downloads_b.B_NAME}</td>
		<td><table cellspacing="0" cellpadding="0">
		<tr> 
			<td><img src="{U_VOTE_LCAP}" width="4" alt="{downloads_b.B_OPTION_RESULT}" title="{downloads_b.B_OPTION_RESULT}" height="12" /></td>
			<td><img src="{downloads_b.B_OPTION_IMG}" width="{downloads_b.B_OPTION_IMG_WIDTH}" height="12" alt="{downloads_b.B_OPTION_RESULT}" title="{downloads_b.B_OPTION_RESULT}" /></td>
			<td><img src="{U_VOTE_RCAP}" width="4" alt="{downloads_b.B_OPTION_RESULT}" title="{downloads_b.B_OPTION_RESULT}" height="12" /></td>
		</tr>
		</table></td>
		<td align="center"><span class="gen">[ {downloads_b.B_OPTION_RESULT} ]</span></td>
	</tr>
	<!-- END downloads_b -->
	</table></td>
	<td class="row1" align="center"><table cellspacing="0" cellpadding="2">
	<!-- BEGIN rating_b -->
	<tr> 
		<td class="gen"><img src="{rating_b.B_IMG}" alt="" title="" />&nbsp;{rating_b.B_NAME}</td>
		<td><table cellspacing="0" cellpadding="0">
		<tr> 
			<td><img src="{U_VOTE_LCAP}" width="4" alt="{rating_b.B_OPTION_RESULT}" title="{rating_b.B_OPTION_RESULT}" height="12" /></td>
			<td><img src="{rating_b.B_OPTION_IMG}" width="{rating_b.B_OPTION_IMG_WIDTH}" height="12" alt="{rating_b.B_OPTION_RESULT}" title="{rating_b.B_OPTION_RESULT}" /></td>
			<td><img src="{U_VOTE_RCAP}" width="4" alt="{rating_b.B_OPTION_RESULT}" title="{rating_b.B_OPTION_RESULT}" height="12" /></td>
		</tr>
		</table></td>
		<td align="center"><span class="gen">[ {rating_b.B_OPTION_RESULT} ]</span></td>
	</tr>
	<!-- END rating_b -->
	</table></td>
  </tr>  
  <tr> 
	<td colspan="2" class="cat" height="28">&nbsp;</td>
  </tr>
</table>
<!-- INCLUDE pa_footer.tpl -->