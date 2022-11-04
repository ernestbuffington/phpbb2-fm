{PORTAL_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_PAGE_TITLE}</h1>

<p>{L_PAGE_EXPLAIN}</p>

<table width="100%" cellpadding="4" cellspacing="1" align="center" class="forumline"><form action="{S_NEWSFEED_CONFIG_ACTION}" method="post">
  <tr>
	<th class="thHead" colspan="2">{L_PAGE_TITLE}</th>
  </tr>
  <tr> 
	<td class="row1" width="50%"><b>{L_NEWSFEED_RSS}:</b><br /><span class="gensmall">{L_NEWSFEED_RSS_EXPLAIN}</span></td> 
	<td class="row2"><input class="post" name="newsfeed_rss" size="35" maxlength="255" value="{NEWSFEED_RSS}" /></td> 
  </tr>    
  <tr> 
	<td class="row1"><b>{L_NEWSFEED_CACHE}:</b><br /><span class="gensmall">{L_NEWSFEED_CACHE_EXPLAIN}</span></td> 
	<td class="row2"><input class="post" name="newsfeed_cache" size="25" maxlength="255" value="{NEWSFEED_CACHE}" /></td> 
  </tr>    
  <tr> 
	<td class="row1"><b>{L_NEWSFEED_CACHETIME}:</b><br /><span class="gensmall">{L_NEWSFEED_CACHETIME_EXPLAIN}</span></td> 
	<td class="row2"><input class="post" name="newsfeed_cachetime" size="4" maxlength="4" value="{NEWSFEED_CACHETIME}" /></td> 
  </tr>    
  <tr> 
	<td class="row1"><b>{L_NEWSFEED_AMT}:</b></td> 
	<td class="row2"><input class="post" name="newsfeed_amt" size="4" maxlength="3" value="{NEWSFEED_AMT}" /></td> 
  </tr>   
  <tr>
	<th class="thHead" colspan="2">{L_FIELD_CONFIGURATION}</th>
  </tr>
  <tr>
	<td class="row2" colspan="2"><span class="gensmall">{L_FIELD_CONFIGURATION_EXPLAIN}</span></td>
  </tr>
  <tr> 
	<td class="row1"><b>{L_NEWSFEED_FIELD_ARTICLE}:</b></td> 
	<td class="row2"><input class="post" name="newsfeed_field_article" size="20" maxlength="255" value="{NEWSFEED_FIELD_ARTICLE}" /></td> 
  </tr>    
  <tr> 
	<td class="row1"><b>{L_NEWSFEED_FIELD_URL}:</b></td> 
	<td class="row2"><input class="post" name="newsfeed_field_url" size="20" maxlength="255" value="{NEWSFEED_FIELD_URL}" /></td> 
  </tr>    
  <tr> 
	<td class="row1"><b>{L_NEWSFEED_FIELD_TEXT}:</b></td> 
	<td class="row2"><input class="post" name="newsfeed_field_text" size="20" maxlength="255" value="{NEWSFEED_FIELD_TEXT}" /></td> 
  </tr>    
  <tr> 
	<td class="row1"><b>{L_NEWSFEED_FIELD_SOURCE}:</b><br /></td> 
	<td class="row2"><input class="post" name="newsfeed_field_source" size="20" maxlength="255" value="{NEWSFEED_FIELD_SOURCE}" /></td> 
  </tr>    
  <tr> 
	<td class="row1"><b>{L_NEWSFEED_FIELD_TIME}:</b></td> 
	<td class="row2"><input class="post" name="newsfeed_field_time" size="20" maxlength="255" value="{NEWSFEED_FIELD_TIME}" /></td> 
  </tr>    
  <tr>
	<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
  </tr>
</form></table>
