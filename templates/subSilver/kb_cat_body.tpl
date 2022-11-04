	<table width="100%" cellspacing="2" cellpadding="2" align="center">
	<tr>
		<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> <a href="{U_KB}" class="nav">{L_KB}</a> {PATH}</td>
	</tr>
	</table>

	<!-- BEGIN switch_sub_cats -->
	<table width="100%" cellpadding="4" cellspacing="1" class="forumline">
	<tr> 
  	   	<th class="thCornerL" nowrap="nowrap">&nbsp;{L_CATEGORY}&nbsp;</th>
	   	<th width="50" class="thCornerR" nowrap="nowrap">&nbsp;{L_ARTICLES}&nbsp;</th>
	</tr>
	<!-- BEGIN catrow -->
	<tr> 
	  	<td width="100%" class="row1"><span class="forumlink"><a href="{switch_sub_cats.catrow.U_CATEGORY}" class="forumlink">{switch_sub_cats.catrow.CATEGORY}</a></span><br /><span class="genmed">{switch_sub_cats.catrow.CAT_DESCRIPTION}</span></td>
		<td class="row2" align="center" valign="middle"><span class="genmed">{switch_sub_cats.catrow.CAT_ARTICLES}</span></td>
	</tr>
	<!-- END catrow -->
	<tr>
	  	<td class="catBottom" colspan="2">&nbsp;</td>
	</tr>
	</table>
	<br />
	<!-- END switch_sub_cats -->
	<table width="100%" cellpadding="4" cellspacing="1" class="forumline">
	<tr> 
	  	<th class="thCornerL" nowrap="nowrap">&nbsp;{L_ARTICLE}&nbsp;</th>
	  	<th class="thTop" width="100" nowrap="nowrap">&nbsp;{L_ARTICLE_TYPE}&nbsp;</th>
		<th class="thCornerR" width="50" nowrap="nowrap">&nbsp;{L_VIEWS}&nbsp;</th>
  		<th class="thTop nowrap="nowrap" width="150">&nbsp;{L_ARTICLE_DATE}&nbsp;</th>
	</tr>
	<!-- BEGIN articlerow -->
	<tr> 
	  	<td class="row1" width="100%">{articlerow.ARTICLE}<br /><span class="genmed">{articlerow.ARTICLE_DESCRIPTION}</span></td>
		<td class="row1" align="center" valign="middle">&nbsp;<span class="postdetails">{articlerow.ARTICLE_TYPE}</span>&nbsp;</td>
		<td class="row2" align="center" valign="middle"><span class="postdetails">{articlerow.ART_VIEWS}</span></td>
		<td class="row1" align="center" valign="middle" nowrap="nowrap"><span class="postdetails">{articlerow.ARTICLE_DATE}<br />{articlerow.ARTICLE_AUTHOR}</span></td>
  	</tr>
  	{articlerow.VIEWS}
  	<!-- END articlerow -->
  	<tr>
  		<td class="catBottom" colspan="4">&nbsp;</td>
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
