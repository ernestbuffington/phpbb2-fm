{ATTACH_MENU}{POST_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_CONTROL_PANEL_TITLE}</h1>

<p>{L_CONTROL_PANEL_EXPLAIN}</p>

<table class="forumline" width="100%" align="center" cellpadding="4" cellspacing="1"><form method="post" action="{S_MODE_ACTION}">

	<tr> 
	  <td align="center" colspan="2" class="catHead"><span class="genmed">{L_VIEW}:&nbsp;{S_VIEW_SELECT}&nbsp;&nbsp;
		<input type="submit" name="submit" value="{L_SUBMIT}" class="liteoption" />
		</span></td>
	</tr>
	<tr> 
		<th class="thHead" colspan="2">{L_ATTACH_SEARCH_QUERY}</th>
	</tr>
	<tr> 
		<td class="row1" width="50%"><b>{L_FILENAME}:</b><br /><span class="gensmall">{L_WILDCARD_EXPLAIN}</span></td>
		<td class="row2"><input type="text" style="width: 200px" class="post" name="search_keyword_fname" size="20" /></td>
	</tr>
	<tr> 
		<td class="row1"><b>{L_COMMENT}:</b><br /><span class="gensmall">{L_WILDCARD_EXPLAIN}</span></td>
		<td class="row2"><input type="text" style="width: 200px" class="post" name="search_keyword_comment" size="20" /></td>
	</tr>
	<tr> 
		<td class="row1"><b>{L_SEARCH_AUTHOR}:</b><br /><span class="gensmall">{L_WILDCARD_EXPLAIN}</span></td>
		<td class="row2"><input type="text" style="width: 200px" class="post" name="search_author" size="20" /></td>
	</tr>
	<tr> 
		<td class="row1"><b>{L_SIZE_SMALLER_THAN}:</b></td>
		<td class="row2"><input type="text" style="width: 100px" class="post" name="search_size_smaller" size="10" /></td>
	</tr>
	<tr> 
		<td class="row1"><b>{L_SIZE_GREATER_THAN}:</b></td>
		<td class="row2"><input type="text" style="width: 100px" class="post" name="search_size_greater" size="10" /></td>
	</tr>
	<tr> 
		<td class="row1"><b>{L_COUNT_SMALLER_THAN}:</b></td>
		<td class="row2"><input type="text" style="width: 100px" class="post" name="search_count_smaller" size="10" /></td>
	</tr>
	<tr> 
		<td class="row1"><b>{L_COUNT_GREATER_THAN}:</b></td>
		<td class="row2"><input type="text" style="width: 100px" class="post" name="search_count_greater" size="10" /></td>
	</tr>
	<tr> 
		<td class="row1"><b>{L_MORE_DAYS_OLD}:</b></td>
		<td class="row2"><input type="text" style="width: 100px" class="post" name="search_days_greater" size="10" /></td>
	</tr>
 	<tr> 
		<th class="thHead" colspan="2">{L_SEARCH_OPTIONS}</th>
	</tr>
	<tr> 
		<td class="row1"><b>{L_FORUM}:</b></td>
		<td class="row2"><select class="post" name="search_forum">{S_FORUM_OPTIONS}</select></td>
	</tr>
	<tr> 
		<td class="row1"><b>{L_SORT_BY}:</b></td>
		<td class="row2">{S_SORT_OPTIONS}</td>
	<tr>
		<td class="row1"><b>{L_ORDER}:</b></td>
		<td class="row2">{S_SORT_ORDER}</td>
	</tr>
	<tr> 
		<td class="catBottom" colspan="2" align="center" height="28">{S_HIDDEN_FIELDS}<input class="mainoption" type="submit" name="search" value="{L_SEARCH}" /></td>
	</tr>
</form></table>