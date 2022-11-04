<!-- INCLUDE pa_header.tpl -->
<table align="center" width="100%" cellpadding="2" cellspacing="2">
<tr>
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> <a href="{U_DOWNLOAD_HOME}" class="nav">{DOWNLOAD}</a><!-- BEGIN navlinks --> -> <a href="{navlinks.U_VIEW_CAT}" class="nav">{navlinks.CAT_NAME}</a><!-- END navlinks --> -> {FILE_NAME}</td>
</tr>
</table>

<table width="100%" cellpadding="4" cellspacing="1" align="center" class="forumline">
<tr> 
	<th class="thHead" colspan="2">{L_FILE} - {FILE_NAME}</th>
</tr>
<tr> 
	<td class="row1" width="38%"><b>{L_DESC}:</b></td>
	<td class="row2">{FILE_LONGDESC}</td>
</tr>
<tr>
	<td class="row1"><b>{L_SUBMITED_BY}:</b></td>
	<td class="row2">{FILE_SUBMITED_BY}</td>
</tr>  
<!-- IF SHOW_AUTHOR -->
<tr>
	<td class="row1"><b>{L_AUTHOR}:</b></td>
	<td class="row2">{FILE_AUTHOR}</td>
</tr>  
<!-- ENDIF -->
<!-- IF SHOW_VERSION -->
<tr> 
	<td class="row1"><b>{L_VERSION}:</b></td>
	<td class="row2">{FILE_VERSION}</td>
</tr>  
<!-- ENDIF -->  
<!-- IF SHOW_SCREENSHOT -->
<tr> 
	<td class="row1"><b>{L_SCREENSHOT}:</b></td>
	<!-- IF SS_AS_LINK -->
	<td class="row2"><a href="{FILE_SCREENSHOT}" target="_blank" title="{L_CLICK_HERE}" />{L_CLICK_HERE}</a></td>
	<!-- ELSE -->
	<td class="row2"><a href="javascript:mpFoto('{FILE_SCREENSHOT}')"><img src="{FILE_SCREENSHOT}" width="100" hight="100" alt="{L_SCREENSHOT}" title="{L_SCREENSHOT}" /></a></td>
	<!-- ENDIF -->
  </tr>  
<!-- ENDIF -->
<!-- IF SHOW_WEBSITE -->
  <tr> 
	<td class="row1"><b>{L_WEBSITE}:</b></td>
	<td class="row2"><a href="{FILE_WEBSITE}" target="_blank" title="{L_CLICK_HERE}" />{L_CLICK_HERE}</a></td>
  </tr>
<!-- ENDIF --> 
<tr> 
	<td class="row1"><b>{L_DATE}:</b></td>
	<td class="row2">{TIME}</td>
  </tr>
<tr> 
	<td class="row1"><b>{L_UPDATE_TIME}:</b></td>
	<td class="row2">{UPDATE_TIME}</td>
  </tr>
  <tr> 
	<td class="row1"><b>{L_LASTTDL}:</b></td>
	<td class="row2">{LAST}</td>
  </tr>
  <tr> 
	<td class="row1"><b>{L_SIZE}:</b></td>
	<td class="row2">{FILE_SIZE}</td>
  </tr>  
  <tr> 
	<td class="row1"><b>{L_RATING}:</b></td>
	<td class="row2">{RATING} ({FILE_VOTES} {L_VOTES})</td>
  </tr>
  <tr> 
	<td class="row1"><b>{L_DLS}:</b></td>
	<td class="row2"><span class="genmed">{FILE_DLS}</td>
  </tr>
<!-- BEGIN custom_field -->
  <tr>
	<td class="row1"><b>{custom_field.CUSTOM_NAME}:</b></td>
	<td class="row2">{custom_field.DATA}</td>
  </tr>
<!-- END custom_field -->
  <tr> 
	<td class="catBottom" colspan="2" align="right">
	<!-- IF AUTH_EDIT -->  
	<a href="{U_EDIT}"><img src="{EDIT_IMG}" alt="{L_EDIT}" title="{L_EDIT}" /></a>
	<!-- ENDIF -->
	<!-- IF AUTH_DELETE -->  
	<a href="javascript:delete_file('{U_DELETE}')"><img src="{DELETE_IMG}" alt="{L_DELETE}" title="{L_DELETE}" /></a>
	<!-- ENDIF -->
	&nbsp;</td>
  </tr>
</table>
<br />
<table width="100%" align="center" cellpadding="2" cellspacing="2">
  <tr>
	<!-- IF AUTH_DOWNLOAD -->  
	<td width="33%" align="center"><a href="{U_DOWNLOAD}"><img src="{DOWNLOAD_IMG}" alt="{L_DOWNLOAD}" title="{L_DOWNLOAD}" /></a></td>
	<!-- ENDIF -->
	<!-- IF AUTH_RATE -->  
	<td width="34%" align="center"><a href="{U_RATE}"><img src="{RATE_IMG}" alt="{L_RATE}" title="{L_RATE}" /></a></td>
	<!-- ENDIF -->
	<!-- IF AUTH_EMAIL -->  
	<td width="33%" align="center"><a href="{U_EMAIL}"><img src="{EMAIL_IMG}" alt="{L_EMAIL}" title="{L_EMAIL}" /></a></td>
	<!-- ENDIF -->
  </tr>
</table>
<br />
<!-- IF INCLUDE_COMMENTS -->
	<!-- INCLUDE pa_comment_body.tpl -->
<!-- ENDIF -->
<!-- INCLUDE pa_footer.tpl -->
