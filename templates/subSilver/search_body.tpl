
<table width="100%" cellspacing="2" cellpadding="2" align="center">
	<tr> 
		<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></td>
	</tr>
</table>
<table class="forumline" width="100%" cellpadding="4" cellspacing="1"><form action="{S_SEARCH_ACTION}" method="post" name="post">
	<tr> 
		<th class="thHead" colspan="4">{L_SEARCH_QUERY}</th>
	</tr>
	<tr> 
		<td class="row1" colspan="2" width="50%"><b class="gen">{L_SEARCH_KEYWORDS}:</b><br /><span class="gensmall">{L_SEARCH_KEYWORDS_EXPLAIN}</span></td>
		<td class="row2" colspan="2"><input type="text" style="width: 300px" class="post" name="search_keywords" size="30" /><br /><input type="radio" name="search_terms" value="any" checked="checked" /> {L_SEARCH_ANY_TERMS}<br /><input type="radio" name="search_terms" value="all" /> {L_SEARCH_ALL_TERMS}{L_ONLY_BLUECARDS}</td>
	</tr>
	<tr> 
		<td class="row1" colspan="2"><b class="gen">{L_SEARCH_AUTHOR}:</b><br /><span class="gensmall">{L_SEARCH_AUTHOR_EXPLAIN}</span></td>
		<td class="row2" colspan="2" valign="middle"><input type="text" style="width: 200px" class="post" name="username"{AJAXED_USER_LIST_1} size="30" /> &nbsp;<input type="submit" name="usersubmit" value="{L_FIND_USERNAME}" class="liteoption" onClick="window.open('{U_SEARCH_USER}', '_phpbbsearch', 'HEIGHT=250,resizable=yes,WIDTH=400');return false;" />{AJAXED_USER_LIST_2}</td>
 	</tr>
    	<tr> 
   		<td class="row1" colspan="2"><b class="gen">{L_TOPIC_STARTER}:</b></td> 
  		<td class="row2" colspan="2"><input type="radio" name="topic_starter" value="1" /> {L_YES}&nbsp;&nbsp;<input type="radio" name="topic_starter" value="0" checked="checked" /> {L_NO}</td> 
    	</tr> 
	<tr> 
		<th class="thHead" colspan="4">{L_SEARCH_OPTIONS}</th>
	</tr>
	<tr> 
		<td class="row1" align="right"><b class="gen">{L_FORUM}:&nbsp;</b></td>
		<td class="row2"><select name="search_forum">{S_FORUM_OPTIONS}</select></td>
		<td class="row1" align="right"><b class="gen">{L_SEARCH_PREVIOUS}:&nbsp;</b></td>
		<td class="row2"><select name="search_time">{S_TIME_OPTIONS}</select><br /><input type="radio" name="search_fields" value="all" checked="checked" /> {L_SEARCH_MESSAGE_TITLE}<br /><input type="radio" name="search_fields" value="msgonly" /> {L_SEARCH_MESSAGE_ONLY}</td>
	</tr>
	<tr> 
		<td class="row1" align="right"><b class="gen">{L_CATEGORY}:&nbsp;</b></td>
		<td class="row2"><select name="search_cat">{S_CATEGORY_OPTIONS}</select></td>
		<td class="row1" align="right"><b class="gen">{L_SORT_BY}:&nbsp;</b></td>
		<td class="row2"><select class="post" name="sort_by">{S_SORT_OPTIONS}</select><br /><input type="radio" name="sort_dir" value="ASC" /> {L_SORT_ASCENDING}&nbsp;&nbsp;<input type="radio" name="sort_dir" value="DESC" checked="checked" /> {L_SORT_DESCENDING}</td>
	</tr>
	<tr> 
		<td class="row1" align="right"><b class="gen">{L_DISPLAY_RESULTS}:&nbsp;</b></td>
		<td class="row2"><input type="radio" name="show_results" value="posts" /> {L_POSTS}&nbsp;&nbsp;<input type="radio" name="show_results" value="topics" checked="checked" /> {L_TOPICS}</td>
		<td class="row1" align="right"><b class="gen">{L_RETURN_FIRST}:&nbsp;</b></td>
		<td class="row2"><select name="return_chars">{S_CHARACTER_OPTIONS}</select> {L_CHARACTERS}</td>
	</tr>
	<tr> 
		<td class="catBottom" colspan="4" align="center">{S_HIDDEN_FIELDS}<input class="mainoption" type="submit" name="view" value="{L_SEARCH}" />&nbsp;&nbsp;<input type="reset" name="reset" value="{L_RESET}" class="liteoption" /></td>
	</tr>
</form></table>
<br />
<table width="100%" cellspacing="2" align="center"> 
	<tr>
		<td align="right" valign="top">{JUMPBOX}</td>
	</tr>
</table>