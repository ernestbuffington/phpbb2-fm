<!-- BEGIN announcement_displayed -->
<table width="100%" cellpadding="4" cellspacing="1" class="forumline"> 
<tr> 
	<th class="thHead" nowrap="nowrap">&nbsp;{L_ANNOUNCEMENT}&nbsp;</th> 
</tr> 
<tr> 
	<td class="row1" align="center"><span class="gen">{SITE_ANNOUNCEMENTS}</span></td> 
</tr> 
</table>
<div style="padding: 2px;"></div>
<!-- END announcement_displayed -->
<!-- BEGIN guest_announcement_displayed -->
<table width="100%" cellpadding="4" cellspacing="1" class="forumline"> 
<tr> 
	<th class="thHead" nowrap="nowrap">&nbsp;{L_ANNOUNCEMENT}&nbsp;</th> 
</tr> 
<tr> 
	<td class="row1" align="center"><span class="gen">{GUEST_ANNOUNCEMENTS}</span></td> 
</tr> 
</table>
<div style="padding: 2px;"></div>
<!-- END guest_announcement_displayed -->

<!-- BEGIN shoutbox_top -->
{SHOUTBOX_TPL}
<!-- END shoutbox_top -->

<table width="100%" cellspacing="2" cellpadding="2" align="center">
<!-- BEGIN switch_user_logged_out -->
<tr> 
	<td colspan="2" class="mainmenu"><a href="{U_SEARCH_UNANSWERED}" class="mainmenu">{L_SEARCH_UNANSWERED}</a> | <a href="{U_SEARCH_DAILY}" class="mainmenu">{L_VIEW_LAST_24_HOURS}</a></td>
</tr>
<!-- END switch_user_logged_out -->
<!-- BEGIN bookie_bets_due --> 
<tr> 
	<td colspan="2" align="right" class="gensmall">{BOOKIE_IMAGE} {BOOKIE_START} <b>{BOOKIE_COUNT}</b> {BOOKIE_END}</td>
</tr>
<!-- END bookie_bets_due -->
<tr> 
	<td valign="bottom" class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> <a href="{U_ALL_FORUMS}" class="nav">{L_ALL_FORUMS}</a>
        <!-- BEGIN navrow -->
	<br />-> <a href="{navrow.U_SUBINDEX}" class="nav">{navrow.L_SUBINDEX}</a>
	<!-- END navrow -->
	</td>
	<!-- BEGIN switch_user_logged_in -->
	<form name="view">
	<!-- END switch_user_logged_in -->
	<!-- BEGIN switch_user_logged_out -->
	<form method="post" action="{U_INDEX}">
	<!-- END switch_user_logged_out -->
	<td align="right" valign="bottom" class="gensmall">
	<!-- BEGIN switch_user_logged_in -->
	<select name="user">
		<option value="{U_SEARCH_NEW}">{L_SEARCH_NEW}</option>
		<option value="{U_SEARCH_SELF}">{L_SEARCH_SELF}</option>
		<option value="{U_VIEW_RANDOM_TOPIC}">{L_VIEW_RANDOM_TOPIC}</option>
		<option value="{U_SEARCH_DAILY}">{L_VIEW_LAST_24_HOURS}</option>
		<option value="{U_SEARCH_UNANSWERED}">{L_SEARCH_UNANSWERED}</option>
	</select> <input class="liteoption" type="button" value="{L_GO}" onClick="location=document.view.user.options[document.view.user.selectedIndex].value">
	<!-- END switch_user_logged_in -->
	<!-- BEGIN switch_user_logged_out -->
	{L_SELECT_LANG}: {LANGUAGE_SELECT} <input type="submit" class="liteoption" name="cangenow" value="{L_GO}" />
	<!-- END switch_user_logged_out -->
	</td>
</tr>
</form></table>

<!-- BEGIN displaymodules -->
<table width="100%" cellspacing="0" cellpadding="0" align="center">
  <tr> 
	<td width="{MODULE_WIDTH}" valign="top">
	<!-- END displaymodules -->

	<!-- BEGIN displayindexglance -->
	{GLANCE_OUTPUT}
	<!-- END displayindexglance -->

	<!-- BEGIN displayindexshoutcast -->
	<table width="100%" cellpadding="4" cellspacing="1" class="forumline">
	<tr> 
		<td class="catHead"><span class="cattitle">{L_SHOUTCAST}</span></td>
	</tr>
	<tr>
		<td class="row1"><iframe bgcolor="{T_TR_COLOR1}" name=shoutcast" src="{U_SHOUTCAST}" scrolling="no" width="100%" height="{SHOUTCAST_HEIGHT}" frameborder="0" marginheight="0" marginwidth="0" allowtransparency="true"></iframe></td>
	</tr>
	</table>
	<div style="padding: 2px;"></div>
	<!-- END displayindexshoutcast -->

	<!-- BEGIN displayindexteamspeak -->
	<table width="100%" cellpadding="4" cellspacing="1" class="forumline">
	  <tr> 
		<td class="catHead"><span class="cattitle">{L_TEAMSPEAK}</span></td>
        </tr>
	<tr> 
		<td class="row1"><iframe bgcolor="{T_TR_COLOR1}" name="teamspeak" width="100%" height="{TS_WIN_HEIGHT}" marginwidth="0" marginheight="0" scrolling="auto" src="{U_TEAMSPEAK}" frameborder="0" allowtransparency="true"></iframe></td>
        </tr>
  	</table>
	<div style="padding: 2px;"></div>
	<!-- END displayindexteamspeak -->

	<!-- BEGIN shoutbox_side -->
	{SHOUTBOX_TPL}
	<!-- END shoutbox_side -->

	<!-- BEGIN displaytopdonors -->
	<table cellpadding="4" cellspacing="1" width="100%" class="forumline">
	<tr> 
		<td class="catHead"><span class="cattitle">{displaytopdonors.L_LAST_DONORS}</span></td> 
	</tr> 
	<tr>
		<td class="row1"><span class="gensmall">{displaytopdonors.GOAL}{displaytopdonors.LAST_DONORS}</span></td>
	</tr>
	<tr>
		<td class="row1" colspan="2" align="center"><a href="{displaytopdonors.U_DONATE}"><img src="{displaytopdonors.PAYPAL_IMG}" alt="{displaytopdonors.L_DONATE}" title="{displaytopdonors.L_DONATE}" /></a></td>
	</tr>
	</table>
	<div style="padding: 2px;"></div>
	<!-- END displaytopdonors -->
 
	<!-- BEGIN displayindexphoto -->
	<table width="100%" cellpadding="4" cellspacing="1" class="forumline">
  	  <tr>
		<td class="catHead"><a href="{U_ALBUM}" class="cattitle" title="{L_ALBUM}">{L_LATEST_PIC}</a></td>
	  </tr>
	  <tr>
		<td class="row1"><span class="gensmall" style="line-height:150%"><center>{U_PIC_LINK}<img src="{PIC_IMAGE}" alt="{PIC_DESCR}" title="{PIC_DESCR}" onload="javascript:if(this.width > {MODULE_WIDTH})this.width = ({MODULE_WIDTH}-15)"></a><br /></center>
		<table align="center" cellpadding="2" cellspacing="1">
		  <tr>
			<td width="25%" class="gensmall"><b>{L_POSTER}:</b> {PIC_POSTER}</td>
		  </tr>
		  <tr>
			<td class="gensmall"><b>{L_PIC_TITLE}:</b> {PIC_TITLE}</td>
		  </tr>
		  <tr>
			<td class="gensmall"><b>{L_POSTED}:</b> {PIC_TIME}</td>
		  </tr>
		  <tr>
			<td class="gensmall"><b>{L_COMMENTS}:</b> {PIC_COMMENTS}</td>
		  </tr>
		  <tr>
			<td align="center" class="gensmall">[ <a href="{U_PIC_COMMENT}">{L_COMMENT}</a> ]</td>
		  </tr>
		</table></td>
	  </tr>
	</table>
	<div style="padding: 2px;"></div>
	<!-- END displayindexphoto -->

	<!-- BEGIN displayindexcalendar -->
	{MINI_CAL_OUTPUT}
	<div style="padding: 2px;"></div>
	<!-- END displayindexcalendar -->

	<!-- BEGIN displaynewusers -->
	<table cellpadding="4" cellspacing="1" width="100%" class="forumline">
	<tr> 
		<td class="catHead"><span class="cattitle">{L_NEWEST_MEMBERS}</span></td> 
	</tr> 
	<tr>
		<td class="row1"><span class="gensmall">
	<!-- END displaynewusers -->
		<!-- BEGIN newusersrow -->
		<b>&bull;</b> {newusersrow.NEWNAME} ({newusersrow.NEWNAME_JOINED})<br />
		<!-- END newusersrow -->
	<!-- BEGIN displaynewusers -->
		</span></td>
  	</tr>
	</table>
	<div style="padding: 2px;"></div>
	<!-- END displaynewusers -->

	<!-- BEGIN displayrandomuser -->
	<table cellpadding="4" cellspacing="1" width="100%" class="forumline">
	<tr> 
		<td class="catHead"><span class="cattitle">{L_RANDOM_USER}</span></td>
	  </tr>
	  <tr>
		<td class="row1"><span class="gensmall">{L_RANDOM_USER_EXPLAIN}<br /><center>{RANDOM_NAME}<br />{RANDOM_AVATAR}</center><br /><b>{L_POSTS}:</b> {RANDOM_POSTS}<br /><b>{L_JOINED}:</b> {RANDOM_JOINED}<br /><b>{L_LOCATION}:</b> {RANDOM_LOCATION}<br /><b>{L_VISITS}:</b> {RANDOM_VISITS}<br /><b>{L_LAST_VISIT}:</b> {RANDOM_LAST_VISIT}</span></td> 
	  </tr>
	</table>
	<div style="padding: 2px;"></div>
	<!-- END displayrandomuser -->
	
	<!-- BEGIN displaytopposters -->
	<table cellpadding="4" cellspacing="1" width="100%" class="forumline">
	<tr> 
		<td class="catHead"><span class="cattitle">{L_TOP_POSTERS}</span></td> 
	</tr> 
	<tr>
		<td class="row1"><span class="gensmall">
	<!-- END displaytopposters -->
		<!-- BEGIN toppostersrow --> 
		<b>&bull;</b> {toppostersrow.POSTER} ({toppostersrow.POSTS})<br /> 
		<!-- END toppostersrow -->
	<!-- BEGIN displaytopposters -->
		</span></td>
	</tr>
	</table>
	<div style="padding: 2px;"></div>
	<!-- END displaytopposters -->

	<!-- BEGIN displaymostpoints -->
	<table cellpadding="4" cellspacing="1" width="100%" class="forumline">
	<tr> 
		<td class="catHead"><span class="cattitle">{L_MOST_POINTS}</span></td> 
	</tr> 
	<tr>
		<td class="row1"><span class="gensmall">
	<!-- END displaymostpoints -->
		<!-- BEGIN pointsrow --> 
		<b>&bull;</b> {pointsrow.POINTS_POSTER} ({pointsrow.POINTS})<br /> 
		<!-- END pointsrow -->
	<!-- BEGIN displaymostpoints -->
		</span></td>
	</tr>
	</table>
	<div style="padding: 2px;"></div>
	<!-- END displaymostpoints -->

	<!-- BEGIN displayrandomgame -->
	<table cellpadding="4" cellspacing="1" width="100%" class="forumline">
	  <tr> 
		<td class="catHead"><span class="cattitle"><a href="{U_ACTIVITY}" class="cattitle">{L_RANDOM_GAME}</a></span></td> 
	  </tr> 
	  <tr> 
		<td class="row1" align="center">{RANDOM_GAME}</td> 
	  </tr>
	</table>
	<div style="padding: 2px;"></div>
	<!-- END displayrandomgame -->

	<!-- BEGIN displaykarma -->
	<table cellpadding="4" cellspacing="1" width="100%" class="forumline">
  	<tr> 
		<td class="catHead"><span class="cattitle">{L_KARMA}</span></td> 
  	</tr> 
  	<tr>
		<td class="row1"><span class="gensmall">
	<!-- END displaykarma -->
		<!-- BEGIN karmarow -->
		<b>&bull;</b> {karmarow.KARMA_USER} (+{karmarow.KARMA})<br />
		<!-- END karmarow -->
		<!-- BEGIN karma_none -->
		<b>&bull;</b> {karma_none.NONE}
		<!-- END karma_none -->
	<!-- BEGIN displaykarma -->
		</span></td>
  	</tr>
	</table>
	<div style="padding: 2px;"></div>
	<!-- END displaykarma -->

	<!-- BEGIN displaydownloads -->
	<table cellpadding="4" cellspacing="1" width="100%" class="forumline">
	<tr> 
		<td class="catHead"><a href="{U_DOWNLOAD}" class="cattitle">{L_DOWNLOAD}</a></td> 
	</tr> 
   	<tr>
		<td class="row1"><span class="gensmall">
	<!-- END displaydownloads -->
		<!-- BEGIN files -->
		<b>&bull;</b> {files.FILENAME} ({files.INFO})<br />
		<!-- END files -->
		<!-- BEGIN nofiles -->
		<center>{nofiles.L_NONE}</center>
		<!-- END nofiles -->
	<!-- BEGIN displaydownloads -->
		</span></td>
  	</tr>
	</table>
	<div style="padding: 2px;"></div>
	<!-- END displaydownloads -->

	<!-- BEGIN displayquote -->
	<table cellpadding="4" cellspacing="1" width="100%" class="forumline">
	  <tr> 
		<td class="catHead"><span class="cattitle">{L_QUOTE}</span></td>
	  </tr>
	  <tr> 
		<td class="row1"><span class="gensmall">{QUOTE}</span></td>
	  </tr>
	</table>
	<div style="padding: 2px;"></div>
	<!-- END displayquote -->

	<!-- BEGIN displayindexwallpaper -->
	<table cellpadding="4" cellspacing="1" width="100%" class="forumline">
	  <tr> 
		<td class="catHead"><span class="cattitle">{L_DAILY_WALLPAPER}</span></td>
	</tr>
	<tr>
	   <td align="center" class="row1"><span class="gensmall"><a href="javascript:getWallpaper();"><img src="http://www.gamewallpapers.com/wallpaperoftheday/wallpaperoftheday.jpg" width="120" height="90" onError="document.all.gw_wallpaperoftheday.style.visibility='hidden';" alt="" title="" /></a><br /><br />{L_DLOAD_WALLPAPER}</span></td>
	</tr>
	</table>
	<div style="padding: 2px;"></div>
	<!-- END displayindexwallpaper -->

	<!-- BEGIN displayindexclock -->
	<table width="100%" cellpadding="4" cellspacing="1" class="forumline">
	  <tr> 
		<td class="catHead"><span class="cattitle">{L_CLOCK}</span></td>
	  </tr>
	  <tr>
	 	<td class="row1" align="center">
		<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0" width="{CLOCK_WIDTH}" height="160">                             
		<param name=movie value="images/clock/{CLOCK_FORMAT}">                                                                                                                                                                  
		<param name=quality value=high>                                                                                                                                                                         
		<embed bgcolor="{T_TR_COLOR1}" src="images/clock/{CLOCK_FORMAT}" quality=high pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="{CLOCK_WIDTH}" height="160"></embed>                                                                                                                                                                                                
		</object>
		</td>
	  </tr>
	</table>
	<div style="padding: 2px;"></div>
	<!-- END displayindexclock -->

	<!-- BEGIN displaymodules -->
	</td>
	<td width="10" nowrap>&nbsp;</td>
	<td align="right" valign="top" width="*">
	<!-- END displaymodules -->

	<table width="100%" cellpadding="4" cellspacing="1" class="forumline">
	<!-- BEGIN displayindexnewsbar -->
	<tr> 
		<th colspan="5" class="thHead">{NEWS_TITLE}</th>
	</tr>
	<tr> 
		<td colspan="5" height="28" class="row1"><{NEWS_STYLE} behavior="{SCROLL_BEHAVIOR}" direction="{SCROLL_ACTION}" width="{SCROLL_SIZE}" scrollamount="{SCROLL_SPEED}"><{NEWS_BOLD}><{NEWS_ITAL}><{NEWS_UNDER}><span style="color: {NEWS_COLOR}; font-size: {NEWS_SIZE}px">{NEWS_BLOCK}</span></{NEWS_UNDER}></{NEWS_ITAL}></{NEWS_BOLD}></{NEWS_STYLE}></td>
	</tr>
	<!-- END displayindexnewsbar -->
	<tr> 
		<th colspan="2" class="thCornerL">&nbsp;{L_FORUM}&nbsp;</th>
		<th width="100" class="thTop">&nbsp;{L_POSTS}&nbsp;/&nbsp;{L_TOPICS}&nbsp;</th>
		<th width="50" class="thTop" nowrap="nowrap">&nbsp;{L_VIEWS}&nbsp;</th>
		<th class="thCornerR">&nbsp;{L_LASTPOST}&nbsp;</th>
	</tr>
	<!-- BEGIN catrow -->
	<tr> 
	  	<td class="catLeft" colspan="2" height="28">&nbsp;{catrow.CAT_ICON}&nbsp;<a href="{catrow.U_VIEWCAT}" class="cattitle">{catrow.CAT_DESC}</a></td>
		<td class="rowpic" colspan="3" align="right">{catrow.SPONSOR}</td>
	</tr>
	<!-- BEGIN forumrow -->
	<tr> 
		<td class="row1" align="center" valign="middle" nowrap="nowrap"><a href="{catrow.forumrow.U_VIEWFORUM}"><img src="{catrow.forumrow.FORUM_FOLDER_IMG}" alt="{catrow.forumrow.L_FORUM_FOLDER_ALT}" title="{catrow.forumrow.L_FORUM_FOLDER_ALT}" /></a></td>
	      	<td class="row1" width="100%" onMouseOver="this.style.backgroundColor='{T_TR_COLOR2}'; this.style.cursor='hand';" onMouseOut=this.style.backgroundColor="{T_TR_COLOR1}" onclick="window.location.href='{catrow.forumrow.U_VIEWFORUM}'">{catrow.forumrow.FORUM_ICON}<span class="forumlink"> <a href="{catrow.forumrow.U_VIEWFORUM}" class="forumlink" {catrow.forumrow.TARGET}>{catrow.forumrow.FORUM_NAME}</a></span> &nbsp; <a href="{catrow.forumrow.U_VIEWDESC}" class="gensmall" title="{catrow.forumrow.L_TOGGLE_DESC}">{catrow.forumrow.L_TOGGLE_DESC}</a>
        	<span class="genmed">{catrow.forumrow.FORUM_DESC}</span><span class="gensmall">
		<!-- BEGIN switch_display_moderators -->
		<br /><b>{catrow.forumrow.L_MODERATOR}</b> {catrow.forumrow.MODERATORS}	
		<!-- END switch_display_moderators -->
		{catrow.forumrow.ACTIVE_TOTAL} {catrow.forumrow.ACTIVE} {catrow.forumrow.ACTIVE_INFO}</span></td>
		<td class="row2" align="center" valign="middle" nowrap="nowrap"><span class="gensmall">{catrow.forumrow.POSTS} {L_WITHIN} {catrow.forumrow.TOPICS}</span><span class="copyright">{catrow.forumrow.NUM_NEW_POSTS}{catrow.forumrow.NUM_NEW_TOPICS}</span></td>
		<td class="row2" align="center" valign="middle" nowrap="nowrap"><span class="gensmall">{catrow.forumrow.VIEWS}</span></td>
		<td class="row2" align="center" valign="middle" nowrap="nowrap"> <span class="gensmall">{catrow.forumrow.LAST_POST}</span></td>
	</tr>
	<!-- END forumrow --> 
	<!-- END catrow -->
	</table>

	<table width="100%" cellpadding="2" cellspacing="2">
	  <tr> 
		<td class="gensmall">
		<!-- BEGIN switch_user_logged_in -->
		<a href="{U_MARK_READ}" title="{L_MARK_FORUMS_READ}" class="gensmall">{L_MARK_FORUMS_READ}</a>
		<!-- END switch_user_logged_in -->
		</td>
		<!-- BEGIN switch_jump_to_topic -->
		<form name="jump" method="GET" action="{U_VIEWTOPIC}">
		<td align="right" class="gensmall">{L_TOPIC_JUMP}: &nbsp;<input name="t" type="text" size="5" maxlength="12" class="post" /> <input class="liteoption" type="submit" value="{L_GO}" /></td>
		</form>
		<!-- END switch_jump_to_topic -->
	  </tr>
	</table>

	<!-- BEGIN shoutbox_btm -->
	{SHOUTBOX_TPL}
	<!-- END shoutbox_btm -->

	<!-- BEGIN displayindexlinks -->
	<table width="100%" cellpadding="4" cellspacing="1" class="forumline">
	  <tr> 
		<td class="catLeft" width="100%"><a href="{U_LINKDB}" class="cattitle">{L_LINKDB}</a></td>
		<td class="catRight" nowrap="nowrap" align="center"><span class="cattitle">{SITENAME}</span></td>
	  </tr>
	  <tr> 
		<td class="row1" nowrap="nowrap">
		<script language="JavaScript">
		<!-- 
		var linkrow = new Array({LINKS_LOGO});
		var link_interval = {DISPLAY_INTERVAL};
		var link_start = 0;
		var link_num = {DISPLAY_LOGO_NUM};
		document.write('<table width="100%" cellpadding="0" cellspacing="0"><tr><td><div id="links"></div></td></tr></table>');
		function writeDiv(){
			var link_innerHTML = '';
			if(linkrow.length > link_num)
			{
				for(var i=0; i<link_num; i++)
				{
					link_innerHTML += linkrow[(i + link_start) % linkrow.length];
				}
				document.all.links.innerHTML=link_innerHTML;
				(link_start < linkrow.length - 1) ? link_start ++ : link_start = 0;
				setTimeout("writeDiv()",link_interval);
			}
			else
			{
				for(var j=0; j<linkrow.length; j++)
				{
					link_innerHTML += linkrow[j];	
				}
				document.all.links.innerHTML=link_innerHTML;
			}
		}
		
		writeDiv();
		// -->
		</script>
		</td>
		<td class="row2" nowrap="nowrap" align="center"><img src="{U_SITE_LOGO}" alt="{SITENAME}" title="{SITENAME}" width="{SITE_LOGO_WIDTH}" height="{SITE_LOGO_HEIGHT}" /></td>
	  </tr>
	</table>
	<div style="padding: 2px;"></div>
	<!-- END displayindexlinks -->

	<table width="100%" cellpadding="4" cellspacing="1" class="forumline">
	  <tr> 
		<td colspan="2" class="catHead"><a href="{U_VIEWONLINE}" class="cattitle">{L_WHO_IS_ONLINE}</a></td>
	  </tr>
	  <tr> 
		<td class="row1" align="center" valign="middle" rowspan="{ROWSPAN}">&nbsp;<span class="mainmenu">{L_NAME_WELCOME}&nbsp;<br /><b>{U_NAME_LINK}</b></span><br /><br />{AVATAR_IMG}</td>
		<td class="row1" width="90%"><span class="gensmall">{TOTAL_USERS_ONLINE}<br />{RECORD_USERS}<br /><br />{LOGGED_IN_USER_LIST}<br /><br />{L_ONLINE_EXPLAIN}</span></td>
	  </tr>
	<!-- BEGIN show_chat -->
	  <tr>
		<td class="row1"><span class="gensmall">{show_chat.TOTAL_CHATTERS_ONLINE}&nbsp;&nbsp;&nbsp; [ {show_chat.LOGON_LOGIN} ]<br />{show_chat.CHATTERS_LIST}</span></td>
	  </tr>
	<!-- END show_chat -->
	  <tr> 
		<td class="row1"><b class="gensmall">{L_LEGEND} :: {L_WHOSONLINE_ADMIN}, {L_WHOSONLINE_SUPERMOD}, {L_WHOSONLINE_MOD}{L_WHOSONLINE_GAMES}{GROUP_LEGEND}</b></td>
	  </tr>
	</table>
	<div style="padding: 2px;"></div>

	<table width="100%" cellpadding="4" cellspacing="1" class="forumline">
	  <tr> 
		<td colspan="2" class="catHead"><a href="{U_STATISTICS}" class="cattitle">{L_STATISTICS}</a></td>
	  </tr>
	  <tr> 
		<td class="row1" align="center" valign="middle" rowspan="2"><img src="templates/{T_NAV_STYLE}/whosonline.gif" alt="{L_STATISTICS}" title="{L_STATISTICS}" /></td>
		<td class="row1" width="90%" style="line-height: 130%"><span class="gensmall">{VISIT_COUNTER}{TOTAL_POSTS} | {TOTAL_TOPICS} | {TOTAL_USERS}{TOTAL_MALE}{TOTAL_FEMALE}<br />
		{NEWEST_USER}{NEWEST_USER_TEXT} ({NEWEST_USER_SINCE})
		{REGISTERED_NEW}
		{TOTAL_GROUPS} {TYPE_GROUPS}
		{NEWEST_GROUP}
		</span></td>
	  </tr>
	  <tr>
		<td class="row1"><span class="gensmall">{L_USERS_TODAY}{L_USERS_LASTHOUR}<br />{RECORD_DAY_USERS}
		<!-- BEGIN switch_user_logged_in -->
		<br /><br />{USERS_TODAY_LIST}
		<!-- END switch_user_logged_in -->
		</span></td>
	  </tr>
	</table>

        <!-- BEGIN displayindexweather -->
	<div style="padding: 2px;"></div>
	<table width="100%" cellpadding="4" cellspacing="1" class="forumline">
	<tr>
		<td class="catHead"><a href="{U_WEATHER}" class="cattitle">{L_LOCAL_FORECAST}</a></td>
	</tr>
	<tr>
		<td class="row1" height="330"><iframe name="weather" width="100%" height="100%" marginwidth="0" marginheight="0" scrolling="no" src="{U_INDEX_WEATHER}" frameborder="0" allowtransparency="true"></iframe></td>
	</tr>
	</table>
	<!-- END displayindexweather -->

	{STATISTIC_BLOK}
	
	{TOPLIST_TOP10}
	
	<br />
	<table align="center">
	<tr> 
		<td width="20" align="center"><img src="{FORUM_NEWPOSTS_IMG}" alt="{L_NEW_POSTS}" title="{L_NEW_POSTS}" /></td>
         	<td><span class="gensmall">{L_NEW_POSTS}</span></td>
         	<td>&nbsp;&nbsp;</td>
         	<td width="20" align="center"><img src="{FORUM_NOPOSTS_IMG}" alt="{L_NO_NEW_POSTS}" title="{L_NO_NEW_POSTS}" /></td>
         	<td><span class="gensmall">{L_NO_NEW_POSTS}</span></td>
         	<td>&nbsp;&nbsp;</td>
         	<td width="20" align="center"><img src="{FORUM_LOCKED_IMG}" alt="{L_FORUM_LOCKED}" title="{L_FORUM_LOCKED}" /></td>
		<td><span class="gensmall">{L_FORUM_LOCKED}</span></td>
	</tr>
	</table>

<!-- BEGIN displaymodules -->
	</td>
  </tr>
</table>
<!-- END displaymodules -->