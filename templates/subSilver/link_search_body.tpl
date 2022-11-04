<table width="100%" cellpadding="2" cellspacing="2">
  <tr>
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> <a href="{U_LINK}" class="nav">{LINKS}</a> -> {L_SEARCH}</td>
  </tr>
</table>
<table width="100%" cellpadding="4" cellspacing="1" class="forumline"><form action="{S_SEARCH_ACTION}" method="post">
  <tr> 
	<th class="thHead" colspan="2">{L_SEARCH}</th>
  </tr>
  <tr> 
	<td class="row1" width="50%"><b>{L_SEARCH_KEYWORDS}:</b><br /><span class="gensmall">{L_SEARCH_KEYWORDS_EXPLAIN}</span></td>
	<td class="row2" valign="top"><input type="text" style="width: 300px" class="post" name="search_keywords" size="30" /><br /><input type="radio" name="search_terms" value="any" checked="checked" /> {L_SEARCH_ANY_TERMS}<br /><input type="radio" name="search_terms" value="all" /> {L_SEARCH_ALL_TERMS}</td>
  </tr>
  <tr> 
	<td class="row1"><b>{L_SEARCH_AUTHOR}:</b><br /><span class="gensmall">{L_SEARCH_AUTHOR_EXPLAIN}</span></td>
	<td class="row2" valign="middle"><input type="text" style="width: 300px" class="post" name="search_author" size="30" /></td>
  </tr>
  <tr> 
	<th class="thHead" colspan="2">{L_SEARCH_OPTIONS}</th>
  </tr>
  <tr> 
	<td class="row1"><b>{L_CHOOSE_CAT}</b></td>
	<td class="row2"><select name="cat_id""><option value="0" selected>{L_ALL}</option>{S_CAT_MENU}</select></td>
  </tr>
  <tr> 
	<td class="row1"><b>{L_INCLUDE_COMMENTS}:</b></td>
	<td class="row2"><input type="radio" name="comments_search" value="YES" checked="checked" /> {L_YES}&nbsp;&nbsp;<input type="radio" name="comments_search" value="NO" /> {L_NO}</td>
  </tr>
  <tr>
	<td class="row1"><b>{L_SORT_BY}:</b></td>
	<td class="row2"><select name="sort_method">
		<option value='link_name'>{L_NAME}</option>
		<option selected="selected" value='link_time'>{L_DATE}</option>
		<option value='link_longdesc'>{L_RATING}</option>
		<option value='link_hits'>{L_DOWNLOADS}</option>
	</select></td>
  </tr>
  <tr>
	<td class="row1"><b>{L_SORT_DIR}:&nbsp;</b></td>
	<td class="row2"><input type="radio" name="sort_order" value="ASC" /> {L_SORT_ASCENDING}&nbsp;&nbsp;<input type="radio" name="sort_order" value="DESC" checked="checked" /> {L_SORT_DESCENDING}</td>
  </tr>  
  <tr>   
	<td class="catBottom" align="center" colspan="2"><input type="hidden" name="action" value="search"><input class="liteoption" type="submit" name="submit" value="{L_SEARCH}"></td>
  </tr>
</form></table>
