	<table width="100%" cellspacing="2" cellpadding="2" align="center">
	<tr>
		<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> <a href="{U_KB}" class="nav">{L_KB}</a> {PATH}</td>
	</tr>
	</table>
	<table width="100%" cellpadding="4" cellspacing="1" class="forumline">
	<tr>
	  	<th class="thHead" nowrap="nowrap">&nbsp;{ARTICLE_TITLE}&nbsp;</th>
	</tr>
  	<tr> 
  		<td class="row1" wrap="wrap"><span class="maintitle">{ARTICLE_TITLE}<br /></span><span class="postbody"><i>{ARTICLE_DESCRIPTION} ...</i><br /><br />{ARTICLE_TEXT}</span></td>
  	</tr>
  	<!-- BEGIN switch_pages -->
  	<tr>
		<td class="row1" align="center"><span class="nav">{L_GOTO_PAGE} 
		<!-- BEGIN pages -->
		{switch_pages.pages.PAGE_LINK}
		<!-- END pages -->
		</span></td>
	</tr>
	<!-- END switch_pages -->
  	<tr>
  		<td class="spaceRow" height="1"><img src="images/spacer.gif" alt="" title="" width="1" height="1" /></td>
  	</tr>
	<tr>
	  	<td class="row1"><span class="gen"><b>{L_ARTICLE_AUTHOR}:</b> {ARTICLE_AUTHOR}</span></td>
	</tr>
	<tr>
	  	<td class="row1"><span class="gen"><b>{L_ARTICLE_DATE}:</b> {ARTICLE_DATE}</span></td>
	</tr>
	<tr>
	  	<td class="row1"><span class="gen"><b>{L_ARTICLE_TYPE}:</b> {ARTICLE_TYPE}</span></td>
	</tr>
	<tr>
  		<td class="row1"><span class="gen"><b>{L_ARTICLE_CATEGORY}:</b> {ARTICLE_CATEGORY}</span></td>
  	</tr>
  	<!-- BEGIN switch_comments -->
  	<tr>
  		<td class="row1"><span class="gen">{COMMENTS}</span></td>
  	</tr>
  	<!-- END switch_comments -->
  	<tr>
  		<td class="row1"><span class="gen">{VIEWS}</span></td>
  	</tr>
	<tr>
	  	<td class="catBottom" align="center">{EDIT_IMG}</td>
	</tr>
	</table>
	</td>
</tr>
</table>
<br />
<table width="100%" cellspacing="2" align="center">
<tr> 
	<td align="right">{JUMPBOX}</td>
</tr>
</table>
<br />
<div align="center" class="copyright">Knowledge Base 0.7.6 Beta &copy; 2003, {COPYRIGHT_YEAR} <a href="http://eric.best-1.biz/" class="copyright" target="_blank">wGEric</a></div>
