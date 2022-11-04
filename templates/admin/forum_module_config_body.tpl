{FORUM_MENU}
</div></td>
<td valign="top" width="78%">

<script language="javascript" type="text/javascript">
function spawn() 
{
	x=(screen.width/2)-225;
	y=(screen.height/2)-200;
	window.open('../mods/weather/popup.htm','_weather','left=' + x + ',top=' + y +',screenX=x,screenY=y,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=450,height=400')
}
</script>


<h1>{L_PAGE_TITLE}</h1>

<p>{L_PAGE_EXPLAIN} {L_FORUM_MODULE_EXPLAIN}</p>

<table width="100%" align="center"><form action="{S_CONFIG_ACTION}" method="post">
<tr>
	<td align="right">{CONFIG_SELECT} <input type="submit" name="config" value="{L_GO}" class="liteoption" /></td>
</tr>
</form></table>
<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline"><form action="{S_CONFIG_ACTION}" method="post">
<tr>
	<th class="thHead" colspan="2">{L_PAGE_TITLE}</th>
</tr>
<tr> 
	<td class="row1" width="50%"><b>{L_FORUM_MODULE_DISABLE}:</b><br /><span class="gensmall">{L_FORUM_MODULE_DISABLE_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="forum_module_disable" value="1" {S_FORUM_MODULE_DISABLE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="forum_module_disable" value="0" {S_FORUM_MODULE_DISABLE_NO} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_FORUM_MODULE_WIDTH}:</b><br /><span class="gensmall">{L_FORUM_MODULE_WIDTH_EXPLAIN}</span></td>
	<td class="row2"><span class="gensmall"> <input class="post" type="text" name="forum_module_width" size="5" maxlength="5" value="{FORUM_MODULE_WIDTH}" /> px</span></td>
</tr>
<tr> 
      	<td class="row1"><b>{L_ENABLE_FORUM_MODULE_LINKS}:</b><br /><span class="gensmall">{L_ENABLE_FORUM_MODULE_LINKS_EXPLAIN}</span></td>
      	<td class="row2"><input type="radio" name="forum_module_links" value="1" {S_FORUM_MODULE_LINKS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="forum_module_links" value="0" {S_FORUM_MODULE_LINKS_NO} /> {L_NO}</td>
</tr>
<tr> 
      	<td class="row1"><b>{L_ENABLE_FORUM_MODULE_WEATHER}:</b><br /><span class="gensmall">{L_ENABLE_FORUM_MODULE_WEATHER_EXPLAIN}</span></td>
      	<td class="row2"><input type="radio" name="forum_module_weather" value="1" {S_FORUM_MODULE_WEATHER_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="forum_module_weather" value="0" {S_FORUM_MODULE_WEATHER_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_WEATHER_CODE}:</b><br /><span class="gensmall"><a href="javascript:void(0);" onclick="spawn();" title="{L_SELECT_CITY}">{L_SELECT_CITY}</a> {L_AS_DEFAULT}</span></td>
	<td class="row2"><input class="post" type="text" name="zipcode" size="10" maxlength="10" value="{ZIPCODE}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_TOPLIST_TOPLIST_TOP10}:</b><br /><span class="gensmall">{L_TOPLIST_TOPLIST_TOP10_EXPLAIN}</span></td>
   	<td class="row2"><input type="radio" name="toplist_toplist_top10" value="1" {TOP10EN} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="toplist_toplist_top10" value="0" {TOP10DI} /> {L_NO}</td>
</tr>
<tr> 
      	<td class="row1"><b>{L_ENABLE_FORUM_MODULE_GLANCE}:</b><br /><span class="gensmall">{L_ENABLE_FORUM_MODULE_EXPLAIN}</span></td>
      	<td class="row2"><input type="radio" name="forum_module_glance" value="1" {S_FORUM_MODULE_GLANCE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="forum_module_glance" value="0" {S_FORUM_MODULE_GLANCE_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_GLANCE_BLOCK_TITLE}:</b></td>
	<td class="row2"><input class="post" type="text" name="glance_forum_news_title" size="25" maxlength="50" value="{GLANCE_FORUM_NEWS_TITLE}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_GLANCE_FORUM_ID}{GLANCE_FORUM_NEWS_TITLE}:</b><br /><span class="gensmall">{L_GLANCE_FORUM_ID_EXPLAIN}</span></td>
	<td class="row2">{GLANCE_SELECT}</td>
</tr>
<tr> 
      	<td class="row1"><b>{L_GLANCE_SCROLL}:</b></td>
      	<td class="row2"><input type="radio" name="glance_news_scroll" value="1" {S_GLANCE_NEWS_SCROLL_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="glance_news_scroll" value="0" {S_GLANCE_NEWS_SCROLL_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_GLANCE_NUM}:</b><br /><span class="gensmall">{L_GLANCE_NUM_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" name="glance_news_num" size="4" maxlength="3" value="{GLANCE_NEWS_NUM}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_GLANCE_BLOCK_TITLE_BTM}:</b></span></td>
	<td class="row2"><input class="post" type="text" name="glance_forum_discuss_title" size="25" maxlength="50" value="{GLANCE_FORUM_DISCUSS_TITLE}" /></td>
</tr>
<tr> 
      	<td class="row1"><b>{L_GLANCE_SCROLL}:</b></td>
      	<td class="row2"><input type="radio" name="glance_recent_scroll" value="1" {S_GLANCE_RECENT_SCROLL_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="glance_recent_scroll" value="0" {S_GLANCE_RECENT_SCROLL_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_GLANCE_NUM}:</b><br /><span class="gensmall">{L_GLANCE_NUM_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" name="glance_recent_num" size="4" maxlength="3" value="{GLANCE_RECENT_NUM}" /></td>
</tr>
<tr> 
      	<td class="row1"><b>{L_ENABLE_FORUM_MODULE_CLOCK}:</b><br /><span class="gensmall">{L_ENABLE_FORUM_MODULE_EXPLAIN}</span></td>
      	<td class="row2"><input type="radio" name="forum_module_clock" value="1" {S_FORUM_MODULE_CLOCK_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="forum_module_clock" value="0" {S_FORUM_MODULE_CLOCK_NO} /> {L_NO}</td>
</tr>
<tr> 
      	<td class="row1"><b>{L_ENABLE_FORUM_MODULE_CALENDAR}:</b><br /><span class="gensmall">{L_ENABLE_FORUM_MODULE_EXPLAIN}</span></td>
      	<td class="row2"><input type="radio" name="forum_module_calendar" value="1" {S_FORUM_MODULE_CALENDAR_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="forum_module_calendar" value="0" {S_FORUM_MODULE_CALENDAR_NO} /> {L_NO}</td>
</tr>
<tr> 
      	<td class="row1"><b>{L_ENABLE_FORUM_MODULE_PHOTO}:</b><br /> <span class="gensmall">{L_ENABLE_FORUM_MODULE_EXPLAIN}</span></td>
      	<td class="row2"><input type="radio" name="forum_module_photo" value="1" {S_FORUM_MODULE_PHOTO_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="forum_module_photo" value="0" {S_FORUM_MODULE_PHOTO_NO} /> {L_NO}</td>
</tr>
<tr> 
      	<td class="row1"><b>{L_ENABLE_FORUM_MODULE_QUOTE}:</b><br /><span class="gensmall">{L_ENABLE_FORUM_MODULE_EXPLAIN}</span></td>
      	<td class="row2"><input type="radio" name="forum_module_quote" value="1" {S_FORUM_MODULE_QUOTE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="forum_module_quote" value="0" {S_FORUM_MODULE_QUOTE_NO} /> {L_NO}</td>
</tr>
<tr> 
      	<td class="row1"><b>{L_ENABLE_FORUM_MODULE_RANDOMUSER}:</b><br /><span class="gensmall">{L_ENABLE_FORUM_MODULE_EXPLAIN}</span></td>
      	<td class="row2"><input type="radio" name="forum_module_randomuser" value="1" {S_FORUM_MODULE_RANDOMUSER_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="forum_module_randomuser" value="0" {S_FORUM_MODULE_RANDOMUSER_NO} /> {L_NO}</td>
</tr>
<tr> 
      	<td class="row1"><b>{L_ENABLE_FORUM_MODULE_NEWUSERS}:</b><br /><span class="gensmall">{L_ENABLE_FORUM_MODULE_EXPLAIN}</span></td>
      	<td class="row2"><input type="radio" name="forum_module_newusers" value="1" {S_FORUM_MODULE_NEWUSERS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="forum_module_newusers" value="0" {S_FORUM_MODULE_NEWUSERS_NO} /> {L_NO}</td>
</tr>
<tr> 
      	<td class="row1"><b>{L_ENABLE_FORUM_MODULE_TOPPOSTERS}:</b><br /><span class="gensmall">{L_ENABLE_FORUM_MODULE_EXPLAIN}</span></td>
      	<td class="row2"><input type="radio" name="forum_module_topposters" value="1" {S_FORUM_MODULE_TOPPOSTERS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="forum_module_topposters" value="0" {S_FORUM_MODULE_TOPPOSTERS_NO} /> {L_NO}</td>
</tr>
<tr> 
      	<td class="row1"><b>{L_ENABLE_FORUM_MODULE_POINTS}:</b><br /><span class="gensmall">{L_ENABLE_FORUM_MODULE_EXPLAIN}</span></td>
      	<td class="row2"><input type="radio" name="forum_module_points" value="1" {S_FORUM_MODULE_POINTS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="forum_module_points" value="0" {S_FORUM_MODULE_POINTS_NO} /> {L_NO}</td>
</tr>
<tr> 
      	<td class="row1"><b>{L_ENABLE_FORUM_MODULE_KARMA}:</b><br /><span class="gensmall">{L_ENABLE_FORUM_MODULE_KARMA_EXPLAIN}</span></td>
      	<td class="row2"><input type="radio" name="forum_module_karma" value="1" {S_FORUM_MODULE_KARMA_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="forum_module_karma" value="0" {S_FORUM_MODULE_KARMA_NO} /> {L_NO}</td>
</tr>
<tr> 
      	<td class="row1"><b>{L_ENABLE_FORUM_MODULE_DLOADS}:</b><br /><span class="gensmall">{L_ENABLE_FORUM_MODULE_EXPLAIN}</span></td>
      	<td class="row2"><input type="radio" name="forum_module_dloads" value="1" {S_FORUM_MODULE_DLOADS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="forum_module_dloads" value="0" {S_FORUM_MODULE_DLOADS_NO} /> {L_NO}</td>
</tr>
<tr> 
      	<td class="row1"><b>{L_ENABLE_FORUM_MODULE_GAME}:</b><br /><span class="gensmall">{L_ENABLE_FORUM_MODULE_EXPLAIN}</span></td>
      	<td class="row2"><input type="radio" name="forum_module_game" value="1" {S_FORUM_MODULE_GAME_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="forum_module_game" value="0" {S_FORUM_MODULE_GAME_NO} /> {L_NO}</td>
</tr>
<tr> 
      	<td class="row1"><b>{L_ENABLE_FORUM_MODULE_WALLPAPER}:</b><br /><span class="gensmall">{L_ENABLE_FORUM_MODULE_EXPLAIN}</span></td>
      	<td class="row2"><input type="radio" name="forum_module_wallpaper" value="1" {S_FORUM_MODULE_WALLPAPER_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="forum_module_wallpaper" value="0" {S_FORUM_MODULE_WALLPAPER_NO} /> {L_NO}</td>
</tr>
<tr> 
      	<td class="row1"><b>{L_ENABLE_FORUM_MODULE_DONORS}:</b><br /><span class="gensmall">{L_ENABLE_FORUM_MODULE_EXPLAIN}</span></td>
      	<td class="row2"><input type="radio" name="forum_module_donors" value="1" {S_FORUM_MODULE_DONORS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="forum_module_donors" value="0" {S_FORUM_MODULE_DONORS_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_ENABLE_FORUM_MODULE_SHOUTBOX}:</b><br /><span class="gensmall">{L_ENABLE_FORUM_MODULE_SHOUTBOX_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="shoutbox_position" value="1" {SHOUTBOX_POS_MODULE} /> {L_SHOUT_MODULE}&nbsp;&nbsp;<input type="radio" name="shoutbox_position" value="0" {SHOUTBOX_POS_TOP} /> {L_SHOUT_TOP}&nbsp;&nbsp;<input type="radio" name="shoutbox_position" value="2" {SHOUTBOX_POS_BOTTOM} /> {L_SHOUT_BOTTOM}&nbsp;&nbsp;</td>
</tr>
<tr>
	<td class="row1"><b>{L_ENABLE_FORUM_MODULE_TEAMSPEAK}:</b><br /><span class="gensmall">{L_ENABLE_FORUM_MODULE_EXPLAIN}</span></td>
      	<td class="row2"><input type="radio" name="forum_module_teamspeak" value="1" {S_FORUM_MODULE_TEAMSPEAK_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="forum_module_teamspeak" value="0" {S_FORUM_MODULE_TEAMSPEAK_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_ENABLE_FORUM_MODULE_SHOUTCAST}:</b><br /><span class="gensmall">{L_ENABLE_FORUM_MODULE_EXPLAIN}</span></td>
      	<td class="row2"><input type="radio" name="forum_module_shoutcast" value="1" {S_FORUM_MODULE_SHOUTCAST_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="forum_module_shoutcast" value="0" {S_FORUM_MODULE_SHOUTCAST_NO} /> {L_NO}</td>
</tr>
<tr> 
      	<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /> </td>
</tr>
</form></table>
