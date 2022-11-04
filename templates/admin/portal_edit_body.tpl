{PORTAL_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_PAGE_TITLE}</h1>

<p>{L_PAGE_EXPLAIN}</p>

<table align="center" width="100%" class="forumline" cellpadding="4" cellspacing="1" align="center"><form method="post" name="post" action="{S_FORM_ACTION}">
<tr> 
	<th class="thHead" colspan="2">{L_PAGE_TITLE}</th>
</tr>
<tr>
	<td colspan="2" class="row2"><span class="gensmall">{L_ITEMS_REQUIRED}</span></td>
</tr>
<tr> 
	<td class="row1" width="50%" ><b>{L_NAVBAR_NAME}: *</b><br /><span class="gensmall">{L_NAVBAR_NAME_EXPLAIN}</span></td>
	<td class="row2"><input type="text" class="post" style="width: 200px" name="navbar_name" size="25" maxlength="100" value="{NAVBAR_NAME}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_PORTAL_DESTINATION}:</b><br /><span class="gensmall">{L_PORTAL_DESTINATION_EXPLAIN}</span></td>
	<td class="row2">&nbsp;<input type="radio" name="use_url" value="1" {USE_FORUM} /> {L_DEST_FORUM}<br />&nbsp;{FORUM_SELECT}<br /><br />&nbsp;<input type="radio" name="use_url" value="0" {USE_URL} /> {L_DEST_URL}<br />&nbsp;<input type="text" class="post" name="portal_url" size="35" maxlength="255" value="{PORTAL_URL}" /><br /><br />&nbsp;{L_USE_IFRAME} <input type="radio" name="use_iframe" value="1" {USE_IFRAME_YES} /> {L_NO}&nbsp;&nbsp;<input type="radio" name="use_iframe" value="0" {USE_IFRAME_NO} /> {L_YES}<br />&nbsp;{L_IFRAME_HEIGHT} <input type="text" class="post" name="iframe_height" size="5" maxlength="4" value="{IFRAME_HEIGHT}" /> px</td>
</tr>
<tr> 
	<td class="row1"><b>{L_POSTS_LIMIT}:</b><br /><span class="gensmall">{L_POSTS_LIMIT_EXPLAIN}</span></td>
	<td class="row2"><input type="text" class="post" name="list_limit" size="5" maxlength="3" value="{LIST_LIMIT}" /></td>
</tr>
<tr> 
	<td class="row1"><b>{L_CHAR_LIMIT}:</b><br /><span class="gensmall">{L_CHAR_LIMIT_EXPLAIN}</span></td>
	<td class="row2"><input type="text" class="post" name="char_limit" size="5" maxlength="10" value="{CHAR_LIMIT}" /></td>
</tr>
<tr> 
	<td class="row1"><b>{L_POSTS_ORDER}:</b><br /><span class="gensmall">{L_POSTS_ORDER_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="posts_order" value="1" {POSTS_ORDER_ASC} /> {L_ASC}&nbsp;&nbsp;<input type="radio" name="posts_order" value="0" {POSTS_ORDER_DSC} /> {L_DSC}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_DISPLAY_DATE}:</b><br /><span class="gensmall">{L_DISPLAY_DATE_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="display_date" value="1" {DISPLAY_DATE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="display_date" value="0" {DISPLAY_DATE_NO} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_COLUMN_WIDTH}:</b><br /><span class="gensmall">{L_COLUMN_WIDTH_EXPLAIN}</span></td> 
	<td class="row2"><input type="text" class="post" name="portal_column_width" size="5" maxlength="4" value="{COLUMN_WIDTH}" /> px</td> 
</tr> 
<tr> 
	<th class="thHead" colspan="2">{L_BLOCK_OPTIONS}</th>
</tr>
<tr> 
	<td colspan="2" class="catSides"><b class="gensmall">{L_CTR_EXPLAIN}</b></td> 
</tr> 
<tr> 
	<td class="row1"><b>{L_SHOW_NEWSFADER}:</b><br /><span class="gensmall">{L_SHOW_NEWSFADER_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="show_newsfader" value="1" {SHOW_NEWSFADER_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="show_newsfader" value="0" {SHOW_NEWSFADER_NO} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_SHOW_MOREOVER}:</b><br /><span class="gensmall">{L_SHOW_MOREOVER_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="show_moreover" value="1" {SHOW_MOREOVER_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="show_moreover" value="0" {SHOW_MOREOVER_NO} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_SHOW_SHOUTBOX}:</b><br /><span class="gensmall">{L_SHOW_SHOUTBOX_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="show_shoutbox" value="1" {SHOW_SHOUTBOX_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="show_shoutbox" value="0" {SHOW_SHOUTBOX_NO} /> {L_NO}</td>
</tr>
<tr> 
	<td colspan="2" class="catSides"><b class="gensmall">{L_LHS_EXPLAIN}</b></td> 
</tr> 
<tr> 
	<td class="row1"><b>{L_SHOW_NAVBAR}:</b><br /><span class="gensmall">{L_SHOW_NAVBAR_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="show_navbar" value="1" {SHOW_NAVBAR_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="show_navbar" value="0" {SHOW_NAVBAR_NO} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_SHOW_ONLINE}:</b></td>
	<td class="row2"><input type="radio" name="show_online" value="1" {SHOW_ONLINE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="show_online" value="0" {SHOW_ONLINE_NO} /> {L_NO} </td>
</tr>
<tr> 
	<td class="row1"><b>{L_SHOW_ONLINETODAY}:</b></td>
	<td class="row2"><input type="radio" name="show_onlinetoday" value="1" {SHOW_ONLINETODAY_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="show_onlinetoday" value="0" {SHOW_ONLINETODAY_NO} /> {L_NO} </td>
</tr>
<tr>
	<td class="row1"><b>{L_SHOW_NEWUSERS}:</b></td>
	<td class="row2"><input type="radio" name="show_newusers" value="1" {SHOW_NEWUSERS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="show_newusers" value="0" {SHOW_NEWUSERS_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_SHOW_RANDOMUSER}:</b></td>
	<td class="row2"><input type="radio" name="show_randomuser" value="1" {SHOW_RANDOMUSER_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="show_randomuser" value="0" {SHOW_RANDOMUSER_NO} /> {L_NO}</td>
</tr>
<tr> 
      	<td class="row1"><b>{L_SHOW_CLOCK}:</b></td>
      	<td class="row2"><input type="radio" name="show_clock" value="1" {SHOW_CLOCK_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="show_clock" value="0" {SHOW_CLOCK_NO} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_SHOW_CALENDAR}:</b></td>
	<td class="row2"><input type="radio" name="show_calendar" value="1" {SHOW_CALENDAR_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="show_calendar" value="0" {SHOW_CALENDAR_NO} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_SHOW_OURLINK}:</b></td>
	<td class="row2"><input type="radio" name="show_ourlink" value="1" {SHOW_OURLINK_YES} /> {L_YES}</span>&nbsp;&nbsp;<input type="radio" name="show_ourlink" value="0" {SHOW_OURLINK_NO} /> {L_NO} </td>
</tr>
<tr> 
	<td class="row1"><b>{L_SHOW_SEARCH}:</b></td>
	<td class="row2"><input type="radio" name="show_search" value="1" {SHOW_SEARCH_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="show_search" value="0" {SHOW_SEARCH_NO} /> {L_NO}</td>
</tr>
<tr> 
	<td colspan="2" class="catSides"><b class="gensmall">{L_RHS_EXPLAIN}</b></td> 
</tr> 
<tr> 
	<td class="row1"><b>{L_SHOW_LATEST}:</b></td>
	<td class="row2"><input type="radio" name="show_latest" value="1" {SHOW_LATEST_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="show_latest" value="0" {SHOW_LATEST_NO} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_SHOW_LATEST_EXCLUDE_FORUMS}:</b><br /><span class="gensmall">{L_SHOW_LATEST_EXCLUDE_FORUMS_EXPLAIN}</span></td> 
	<td class="row2"><input type="text" class="post" name="show_latest_exclude_forums" size="20" maxlength="100" value="{SHOW_LATEST_EXCLUDE_FORUMS}" /></td> 
</tr> 
<tr> 
	<td class="row1"><b>{L_SHOW_LATEST_SCROLLING}:</b></td> 
	<td class="row2"><input type="radio" name="show_latest_scrolling" value="1" {SHOW_LATEST_SCROLLING_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="show_latest_scrolling" value="0" {SHOW_LATEST_SCROLLING_NO} /> {L_NO}</td>
</tr> 
<tr> 
	<td class="row1"><b>{L_SHOW_LATEST_AMT}:</b><br /><span class="gensmall">{L_SHOW_LATEST_AMT_EXPLAIN}</span></td> 
	<td class="row2"><input type="text" class="post" name="show_latest_amt" size="5" maxlength="5" value="{SHOW_LATEST_AMT}" /></td> 
</tr> 
<tr>
	<td class="spaceRow" colspan="2"><img src="../images/spacer.gif" alt="" width="1" /></td>
</tr>
<tr> 
	<td class="row1"><b>{L_SHOW_POLL}:</b></td>
	<td class="row2"><input type="radio" name="show_poll" value="1" {SHOW_POLL_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="show_poll" value="0" {SHOW_POLL_NO} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_PORTAL_POLLS}:</b><br /><span class="gensmall">{L_PORTAL_POLLS_EXPLAIN}</span></td> 
	<td class="row2">{PORTAL_POLLS_SELECT}<input type="text" class="post" name="portal_polls" size="20" maxlength="100" value="{PORTAL_POLLS}" /></td> 
</tr> 
<tr>
	<td class="spaceRow" colspan="2"><img src="../images/spacer.gif" alt="" width="1" /></td>
</tr>
<tr> 
	<td class="row1"><b>{L_SHOW_LINKS}:</b></td>
	<td class="row2"><input type="radio" name="show_links" value="1" {SHOW_LINKS_YES} /> {L_YES}</span>&nbsp;&nbsp;<input type="radio" name="show_links" value="0" {SHOW_LINKS_NO} /> {L_NO} </td>
</tr>
<tr> 
	<td class="row1"><b>{L_LINKS_HEIGHT}:</b></td> 
	<td class="row2"><input type="text" class="post" name="portal_links_height" size="5" maxlength="4" value="{LINKS_HEIGHT}" /> px</td> 
</tr> 
<tr>
	<td class="spaceRow" colspan="2"><img src="../images/spacer.gif" alt="" width="1" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_SHOW_TOPPOSTERS}:</b></td>
	<td class="row2"><input type="radio" name="show_topposters" value="1" {SHOW_TOPPOSTERS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="show_topposters" value="0" {SHOW_TOPPOSTERS_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_SHOW_MOSTPOINTS}:</b></td>
	<td class="row2"><input type="radio" name="show_mostpoints" value="1" {SHOW_MOSTPOINTS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="show_mostpoints" value="0" {SHOW_MOSTPOINTS_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_SHOW_DOWNLOADS}:</b></td>
	<td class="row2"><input type="radio" name="show_downloads" value="1" {SHOW_DOWNLOADS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="show_downloads" value="0" {SHOW_DOWNLOADS_NO} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_SHOW_PHOTO}:</b></td>
	<td class="row2"><input type="radio" name="show_photo" value="1" {SHOW_PHOTO_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="show_photo" value="0" {SHOW_PHOTO_NO} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_SHOW_QUOTE}:</b></td>
	<td class="row2"><input type="radio" name="show_quote" value="1" {SHOW_QUOTE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="show_quote" value="0" {SHOW_QUOTE_NO} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_SHOW_GAMES}:</b></td>
	<td class="row2"><input type="radio" name="show_games" value="1" {SHOW_GAMES_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="show_games" value="0" {SHOW_GAMES_NO} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_SHOW_KARMA}:</b><br /><span class="gensmall">{L_SHOW_KARMA_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="show_karma" value="1" {SHOW_KARMA_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="show_karma" value="0" {SHOW_KARMA_NO} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_SHOW_HOROSCOPES}:</b></td>
	<td class="row2"><input type="radio" name="show_horoscopes" value="1" {SHOW_HOROSCOPES_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="show_horoscopes" value="0" {SHOW_HOROSCOPES_NO} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_SHOW_WALLPAPER}:</b></td>
	<td class="row2"><input type="radio" name="show_wallpaper" value="1" {SHOW_WALLPAPER_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="show_wallpaper" value="0" {SHOW_WALLPAPER_NO} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_SHOW_DONORS}:</b></td>
	<td class="row2"><input type="radio" name="show_donors" value="1" {SHOW_DONORS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="show_donors" value="0" {SHOW_DONORS_NO} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_SHOW_REFERRERS}:</b></td>
	<td class="row2"><input type="radio" name="show_referrers" value="1" {SHOW_REFERRERS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="show_referrers" value="0" {SHOW_REFERRERS_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</form></table>
