<!-- ------------LANGUAGE SELECTOR------------- -->
<!-- BEGIN switch_user_logged_out -->
<table width="100%" align="center" cellpadding="2" cellspacing="2"><form method="post" action="{U_PORTAL}">
<tr>
	<td class="gensmall" align="right">{L_SELECT_LANG}: {LANGUAGE_SELECT} <input type="submit" class="liteoption" name="cangenow" value="{L_GO}" /></td>
</tr>
</form></table>
<!-- END switch_user_logged_out -->
<!-- ------------LANGUAGE SELECTOR------------- -->

<!-- ----------------------------------------------------- -->
<!-- ------------COLUMN 1 DO NOT EDIT OR MOVE------------- -->
<table width="100%" cellpadding="5" cellspacing="2" align="center">
  <tr>
	<td align="left" valign="top" width="{COLUMN_WIDTH}">
<!-- ------------COLUMN 1 DO NOT EDIT OR MOVE------------- -->
<!-- ----------------------------------------------------- -->


<!-- ------------NAVIGATION------------- -->
<!-- BEGIN displaynavbar -->
<table cellpadding="4" cellspacing="1" width="{COLUMN_WIDTH}" class="forumline">
<tr> 
	<td class="catHead"><span class="cattitle">{L_NAVIGATE}</span></td>
</tr>
<tr>
	<td valign="middle" class="row2"><b style="line-height: 175%;">{INDEX_LINKS}</b></td>
</tr>
</table>
<div style="padding: 2px;"></div>
<!-- END displaynavbar -->
<!-- ------------NAVIGATION------------- -->

<!-- ------------WHOS ONLINE------------- -->
<!-- BEGIN displayonline -->
<table cellpadding="4" cellspacing="1" width="{COLUMN_WIDTH}" class="forumline">
  <tr> 
	<td class="catHead"><a href="{U_VIEWONLINE}" class="cattitle">{L_WHO_IS_ONLINE}</a></td>
  </tr>
  <tr> 
	<td class="row1"><span class="gensmall">{TOTAL_USERS_ONLINE}<br /><br />{LOGGED_IN_USER_LIST}</span></td>
  </tr>
   <tr> 
	<td class="row1"><span class="gensmall">{RECORD_USERS}{VISIT_COUNTER}</span></td>
  </tr>
</table>
<div style="padding: 2px;"></div>
<!-- END displayonline -->
<!-- ------------WHOS ONLINE------------- -->

<!-- ------------WHOS ONLINE TODAY------------- -->
<!-- BEGIN displayonlinetoday -->
<table cellpadding="4" cellspacing="1" width="{COLUMN_WIDTH}" class="forumline">
  <tr> 
	<td class="catHead"><span class="cattitle">{L_ONLINETODAY}</a></span></td>
  </tr>
 <tr> 
	<td class="row1"><span class="gensmall">{L_USERS_TODAY}{L_USERS_LASTHOUR}<br /><br />{USERS_TODAY_LIST}</span></td>    
  </tr> 
</table>
<div style="padding: 2px;"></div>
<!-- END displayonlinetoday -->
<!-- ------------WHOS ONLINE TODAY------------- -->

<!-- ------------NEWEST MEMBERS------------- -->
<!-- BEGIN displaynewusers -->
<table cellpadding="4" cellspacing="1" width="{COLUMN_WIDTH}" class="forumline">
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
  <tr>
	<td class="row1"><span class="gensmall">{displaynewusers.TOTAL_USERS}</span></td>
  </tr>
</table>
<div style="padding: 2px;"></div>
<!-- END displaynewusers -->
<!-- ------------NEWEST MEMBERS------------- -->

<!-- ------------RANDOM USER------------- -->
<!-- BEGIN displayrandomuser -->
<table cellpadding="4" cellspacing="1" width="{COLUMN_WIDTH}" class="forumline">
  <tr> 
	<td class="catHead"><span class="cattitle">{L_RANDOM_USER}</span></td>
  </tr>
  <tr>
	<td class="row1"><span class="gensmall">{L_RANDOM_USER_EXPLAIN}<br /><center>{RANDOM_NAME}<br />{RANDOM_AVATAR}</center><br /><b>{L_POSTS}:</b> {RANDOM_POSTS}<br /><b>{L_JOINED}:</b> {RANDOM_JOINED}<br /><b>{L_LOCATION}:</b> {RANDOM_LOCATION}<br /><b>{L_VISITS}:</b> {RANDOM_VISITS}<br /><b>{L_LAST_VISIT}:</b> {RANDOM_LAST_VISIT}</span></td> 
  </tr>
</table>
<div style="padding: 2px;"></div>
<!-- END displayrandomuser -->
<!-- ------------RANDOM USER------------- -->

<!-- ------------CLOCK------------- -->
<!-- BEGIN displayclock -->
<table cellpadding="4" cellspacing="1" width="{COLUMN_WIDTH}" class="forumline">
  <tr> 
	<td class="catHead"><span class="cattitle">{L_CLOCK}</span></td>
  </tr>
  <tr>
 	<td class="row1" align="center">
	<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0" width="{CLOCK_WIDTH}">                             
	<param name=movie value="images/clock/{PORTAL_CLOCK_FORMAT}">                                                                                                                                                                  
	<param name=quality value=high>                                                                                                                                                                         
	<embed bgcolor="{T_TR_COLOR1}" src="images/clock/{PORTAL_CLOCK_FORMAT}" quality=high pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="{CLOCK_WIDTH}"></embed>                                                                                                                                                                                                
	</object>
	</td>
  </tr>
</table>
<div style="padding: 2px;"></div>
<!-- END displayclock -->
<!-- ------------CLOCK------------- -->

<!-- ------------CALENDAR------------- -->
<!-- BEGIN displaycalendar -->
{MINI_CAL_OUTPUT}
<div style="padding: 2px;"></div>
<!-- END displaycalendar -->
<!-- ------------CALENDAR------------- -->

<!-- ------------SEARCH ENGINE------------- -->
<!-- BEGIN displaysearch -->
<table cellpadding="4" cellspacing="1" width="{COLUMN_WIDTH}" class="forumline">
   <tr> 
     <td class="catHead"><span class="cattitle">{L_SEARCH_ENGINE}</span></td> 
   </tr> 
   <tr> 
	<form action="http://www.google.com/search" name="gs" method="get" target="_blank">
	<td class="row1"><span class="gensmall">Web:&nbsp;<input type="text" class="post" value="" name="q" size="15" maxlength="2048"> <input name="" type="submit" value="{L_GO}" class="liteoption" />&nbsp;
	<br /><a href="{U_SEARCH}" class="gensmall">{L_SITE_SEARCH}</a></span></td>
	</form> 
   </tr>
</table>
<div style="padding: 2px;"></div>
<!-- END displaysearch -->
<!-- ------------SEARCH ENGINE------------- -->

<!-- ------------LINK TO US------------- -->
<!-- BEGIN displayourlink -->
<table cellpadding="4" cellspacing="1" width="{COLUMN_WIDTH}" class="forumline">
 <tr> 
     <td class="catHead"><span class="cattitle">{L_LINK_TO_US} {SITENAME}</span></td> 
   </tr> 
  <tr> 
	<td class="row1"><span class="gensmall"><center><img src="{U_SITE_LOGO}" alt="{SITENAME}" title="{SITENAME}" width="{SITE_LOGO_WIDTH}" height="{SITE_LOGO_HEIGHT}" /></center>{L_LINK_TO_US_EXPLAIN}</span><br />
	<table width="100%" cellspacing="1" cellpadding="1">
    	  <tr>
		<td class="row1"><input type="text" value="{PORTAL_OURLINK}" class="post" style="width: 175px;" /></td>
	  </tr>
	</table>
	</td>
  </tr>
</table>
<div style="padding: 2px;"></div>
<!-- END displayourlink -->
<!-- ------------LINK TO US------------- -->

<!-- ------------REFERRERS------------- -->
<!-- BEGIN displayreferrers -->
<table cellpadding="4" cellspacing="1" width="{COLUMN_WIDTH}" class="forumline">
  <tr> 
	<td class="catHead"><span class="cattitle">{L_RECENT_REFERRALS}</span></td>
  </tr> 
  <tr> 
	<td class="row1"><span class="genmed"> 
	<!-- BEGIN linkrow --> 
	<b>&bull;</b> <a href="{linkrow.U_LINK_TEXT}" class="gensmall" target="_blank" title="{linkrow.LINK_TEXT}">{linkrow.LINK_TEXT}</a><br /> 
	<!-- END linkrow --> 
	</span></td> 
  </tr> 
</table>
<div style="padding: 2px;"></div>
<!-- END displayreferrers -->
<!-- ------------REFERRERS------------- -->

<!-- ------------------------------------------------------- -->
<!-- ------------COLUMN 2 DO NOT EDIT OR MOVE--------------- -->
	</td>
	<td valign="top" align="center">
<!-- ------------COLUMN 2 DO NOT EDIT OR MOVE--------------- -->
<!-- ------------------------------------------------------- -->

<!-- ------------NEWS BLOCK------------- -->
<!-- BEGIN displaynewsfader -->
<table cellpadding="4" cellspacing="1" width="100%" class="forumline">
  <tr>
	<th class="thHead">{NEWS_TITLE}</th>
  </tr>
  <tr>
	<td height="28" class="row1"><{NEWS_STYLE} behavior="{SCROLL_BEHAVIOR}" direction="{SCROLL_ACTION}" width="{SCROLL_SIZE}" scrollamount="{SCROLL_SPEED}"><{NEWS_BOLD}><{NEWS_ITAL}><{NEWS_UNDER}><span style="color: {NEWS_COLOR}; font-size: {NEWS_SIZE}px">{NEWS_BLOCK}</span></{NEWS_UNDER}></{NEWS_ITAL}></{NEWS_BOLD}></{NEWS_STYLE}></td>
  </tr>
</table>
<div style="padding: 2px;"></div>
<!-- END displaynewsfader -->
<!-- ------------NEWS BLOCK------------- -->

<!-- ------------SITE NEWS------------- -->
<!-- BEGIN topicrow -->
<table cellpadding="4" cellspacing="1" width="100%" class="forumline">
  <tr>
	<th class="thHead"><a href="{topicrow.U_VIEW_TOPIC}" class="thsort">{topicrow.TOPIC_TITLE}</th>
  </tr>
  <tr>
	<td class="{topicrow.ROW_CLASS}"><span class="postbody"><span style="color: #{topicrow.CUSTOM_POST_COLOR}">{topicrow.POST_TEXT}</span></span><br />&nbsp;</td>
  </tr>
  <tr>
	<td class="catBottom" width="100%" valign="middle" align="center"><span class="gensmall">{L_POSTED_BY} <b>{topicrow.TOPIC_POSTER}</b>{topicrow.TOPIC_TIME}<br />[ <b>{topicrow.REPLIES}</b> {L_REPLIES}, <b>{topicrow.VIEWS}</b> {L_VIEWS} | <a href="{topicrow.U_POST_COMMENT}" class="gensmall" title="{L_COMMENT}">{L_COMMENT}</a> | <a href="{U_ARCHIVE}" class="gensmall" title="{L_ARCHIVE}">{L_ARCHIVE}</a> ]</td>
  </tr>
</table>
<div style="padding: 2px;"></div>
<!-- END topicrow -->

<!-- BEGIN notopicsrow -->
<table cellpadding="4" cellspacing="1" width="100%" class="forumline">
  <tr>
	<th class="thHead">{L_INFORMATION}</th>
  </tr>
  <tr> 
	<td class="row1" align="center"><span class="postbody">&nbsp;<br />{L_NO_TOPICS}<br />&nbsp;</span></td>
  </tr>
</table>
<table cellpadding="2" cellspacing="2" width="100%">
  <tr> 
	<td valign="middle"><span class="nav"><a href="{NEW_POST}" class="nav">{NEW_POST_IMG}</a></span></td>
  </tr>
</table>
<!-- END notopicsrow -->
<!-- ------------SITE NEWS------------- -->

<!-- ------------WORLD NEWS------------- -->
<!-- BEGIN displaymoreover -->
<table cellpadding="4" cellspacing="1" width="100%" class="forumline">
<tr>
	<th class="thHead">{L_WORLD_NEWS}</th>
</tr>
<tr>
	<td class="row1"><span class="genmed">{displaymoreover.MY_NEWS_CODE}</span></td>
</tr>
</table>
<div style="padding: 2px;"></div>
<!-- END displaymoreover -->
<!-- ------------WORLD NEWS------------- -->

<!-- ------------SHOUTBOX------------- -->
<!-- BEGIN displayshoutbox -->
<table cellpadding="4" cellspacing="1" width="100%" class="forumline">
<tr> 
	<th class="thHead"><a href="{U_SHOUTBOX_MAX}" class="thsort">{L_SHOUTBOX}</a></th>
</tr>
<tr>
	<td class="row1"><iframe name="shoutbox" bgcolor="row1" src="{U_SHOUTBOX}" scrolling="no" width="100%" height="{SHOUTBOX_HEIGHT}" frameborder="0" marginheight="0" marginwidth="0" allowtransparency="true"></iframe></td>
</tr>
</table>
<div style="padding: 2px;"></div>
<!-- END displayshoutbox -->
<!-- ------------SHOUTBOX------------- -->

<!-- ------------COLUMN 3 DO NOT EDIT OR MOVE------------- -->
	</td>
	<td align="right" valign="top" width="{COLUMN_WIDTH}">
<!-- ------------COLUMN 3 DO NOT EDIT OR MOVE------------- -->


<!-- ------------WELCOME BACK------------- -->
<!-- BEGIN switch_user_logged_in -->
<table cellpadding="4" cellspacing="1" width="{COLUMN_WIDTH}" class="forumline">
  <tr> 
	<td class="catHead"><a name="login" class="mainmenu"></a><span class="cattitle">{L_NAME_WELCOME} {U_NAME_LINK}</span></td>
  </tr>
  <tr> 
	<td class="row1" valign="middle" align="center">{AVATAR_IMG}</td>
  </tr>
</table>
<div style="padding: 2px;"></div>
<!-- END switch_user_logged_in -->
<!-- ------------WELCOME BACK------------- -->

<!-- ------------LATEST POSTS------------- -->
<!-- BEGIN displaylatest -->
<table cellpadding="4" cellspacing="1" width="{COLUMN_WIDTH}" class="forumline">
<tr>
	<td class="catHead"><a href="{U_INDEX}" class="cattitle">{L_LATEST_POSTS}</a></td>
</tr>
<tr>
	<td class="row1" valign="middle">{displaylatest.SCROLL_BEGIN}
<!-- END displaylatest -->
	<!-- BEGIN lasttopicrow -->
	<b>&bull;</b> <span class="gensmall"><b><a href="{lasttopicrow.U_VIEW_TOPIC}" class="gensmall">{lasttopicrow.TOPIC_TITLE}</a></b><br />{lasttopicrow.POST_TIME}<br />{L_LAST_POST_BY}{lasttopicrow.POST_USERNAME}</span><br /><br />
	<!-- END lasttopicrow -->
<!-- BEGIN lastnotopicsrow -->
<span class="gensmall">{lastnotopicsrow.L_NO_TOPICS}</span>
<!-- END lastnotopicsrow -->

<!-- BEGIN displaylatest -->
	{displaylatest.SCROLL_END}</td>
</tr>
</table>
<div style="padding: 2px;"></div>
<!-- END displaylatest -->
<!-- ------------LATEST POSTS------------- -->

<!-- ------------TOP DONORS------------- -->
<!-- BEGIN displaytopdonors -->
<table cellpadding="4" cellspacing="1" width="{COLUMN_WIDTH}" class="forumline">
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
<!-- ------------TOP DONORS------------- -->

<!-- ------------POLL------------- -->
<!-- BEGIN displaypoll -->
<table cellpadding="4" cellspacing="1" width="{COLUMN_WIDTH}" class="forumline"><form method="post" action="{displaypoll.S_POLL_ACTION}" />
<tr> 
  	<td class="catHead"><span class="cattitle">{displaypoll.S_POLL_TITLE}</span></td>
</tr>
<tr>
	<td class="row1" style="line-height=150%"><center><b>{displaypoll.S_POLL_QUESTION}</b></center>
	<table cellspacing="1" cellpadding="2" align="center" width="70%">
<!-- END displaypoll -->
	<!-- BEGIN poll_option_row -->
	<tr> 
		<td><input type="radio" name="vote_id" value="{poll_option_row.OPTION_ID}" /></td>
		<td width="100%" align="left">{poll_option_row.OPTION_TEXT}&nbsp;({poll_option_row.VOTE_RESULT})</td>
	</tr>
	<!-- END poll_option_row -->
<!-- BEGIN displaypoll -->
	<tr>
		<td colspan="2" align="center">{displaypoll.SUBMIT_BUTTON}</td>
	</tr>	
	</table>
	</td>
</tr>
</form></table>
<div style="padding: 2px;"></div>
<!-- END displaypoll -->
<!-- ------------POLL------------- -->

<!-- ------------TOP POSTERS------------- -->
<!-- BEGIN displaytopposters -->
<table cellpadding="4" cellspacing="1" width="{COLUMN_WIDTH}" class="forumline">
  <tr> 
	<td class="catHead"><span class="cattitle">{L_TOP_POSTERS}</span></td> 
  </tr> 
  <tr>
	<td class="row1"><span class="gesmall">
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
<!-- ------------TOP POSTERS------------- -->

<!-- ------------MOST POINTS------------- -->
<!-- BEGIN displaymostpoints -->
<table cellpadding="4" cellspacing="1" width="{COLUMN_WIDTH}" class="forumline">
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
<!-- ------------MOST POINTS------------- -->

<!-- ------------HIGHEST KARMA------------- -->
<!-- BEGIN displaykarma -->
<table cellpadding="4" cellspacing="1" width="{COLUMN_WIDTH}" class="forumline">
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
<!-- ------------HIGHEST KARMA------------- -->

<!-- ------------TOP DOWNLOAD------------- -->
<!-- BEGIN displaydownloads -->
<table cellpadding="4" cellspacing="1" width="{COLUMN_WIDTH}" class="forumline">
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
<!-- ------------TOP DOWNLOAD------------- -->

<!-- ------------RANDOM PHOTO------------- -->
<!-- BEGIN displayphoto -->
<table cellpadding="4" cellspacing="1" width="{COLUMN_WIDTH}" class="forumline">
  <tr> 
	<td class="catHead"><a href="{U_ALBUM}" class="cattitle">{L_LATEST_PIC}</a></td>
  </tr>
  <tr>
	<td class="row1"><span class="gensmall" style="line-height:150%"><center>{U_PIC_LINK}<img src="{PIC_IMAGE}" alt="{PIC_DESCR}" title="{PIC_DESCR}" onload="javascript:if(this.width > {COLUMN_WIDTH})this.width = ({COLUMN_WIDTH}-15)"></a><br /></center>
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
<!-- END displayphoto -->
<!-- ------------RANDOM PHOTO------------- -->

<!-- ------------RANDOM QUOTE------------------- -->
<!-- BEGIN displayquote -->
<table cellpadding="4" cellspacing="1" width="{COLUMN_WIDTH}" class="forumline">
  <tr> 
	<td class="catHead"><span class="cattitle">{L_QUOTE}</span></td>
  </tr>
  <tr> 
	<td class="row1"><span class="gensmall">{QUOTE}</span></td>
  </tr>
</table>
<div style="padding: 2px;"></div>
<!-- END displayquote -->
<!-- ------------RANDOM QUOTE------------------- -->

<!-- ------------HOROSCOPES------------------- -->
<!-- BEGIN displayhoroscopes -->
<table cellpadding="4" cellspacing="1" width="{COLUMN_WIDTH}" class="forumline">
  <tr> 
	<td class="catHead"><span class="cattitle">{L_HOROSCOPES}</span></td>
  </tr>
  <tr> 
	<td class="row1"><table align="center" cellpadding="2" cellspacing="2">
	<tr> 
        	<td align="center"><a href="mods/horoscopes/aquarius.php" onClick="PopUp=window.open('mods/horoscopes/aquarius.php', '_popup', 'resizable=yes, width=400, height=250, left=200, top=200, screenX=0, screenY=0'); PopUp.focus(); return false;"><img src="{AQUARIUS}" alt="{L_AQUARIUS}" title="{L_AQUARIUS}" /></a></td>
                <td><a href="mods/horoscopes/aquarius.php" onClick="PopUp=window.open('mods/horoscopes/aquarius.php', '_popup', 'resizable=yes, width=400, height=250, left=200, top=200, screenX=0, screenY=0'); PopUp.focus(); return false;" class="gensmall" title="{L_AQUARIUS}">{L_AQUARIUS}</a></td>
	</tr>
        <tr> 
                <td align="center"><a href="mods/horoscopes/aries.php" onClick="PopUp=window.open('mods/horoscopes/aries.php', '_popup', 'resizable=yes, width=400, height=250, left=200, top=200, screenX=0, screenY=0'); PopUp.focus(); return false;"><img src="{ARIES}" alt="{L_ARIES}" title="{L_ARIES}" /></a></td>
                <td><a href="mods/horoscopes/aries.php" onClick="PopUp=window.open('mods/horoscopes/aries.php', '_popup', 'resizable=yes, width=400, height=250, left=200, top=200, screenX=0, screenY=0'); PopUp.focus(); return false;" class="gensmall" title="{L_ARIES}">{L_ARIES}</a></td>
        </tr>
        <tr> 
                <td align="center"><a href="mods/horoscopes/cancer.php" onClick="PopUp=window.open('mods/horoscopes/cancer.php', '_popup', 'resizable=yes, width=400, height=250, left=200, top=200, screenX=0, screenY=0'); PopUp.focus(); return false;"><img src="{CANCER}" alt="{L_CANCER}" title="{L_CANCER}" /></a></td>
                <td><a href="mods/horoscopes/cancer.php" onClick="PopUp=window.open('mods/horoscopes/cancer.php', '_popup', 'resizable=yes, width=400, height=250, left=200, top=200, screenX=0, screenY=0'); PopUp.focus(); return false;" class="gensmall" title="{L_CANCER}">{L_CANCER}</a></td>
        </tr>
        <tr> 
                <td align="center"><a href="mods/horoscopes/capricorn.php" onClick="PopUp=window.open('mods/horoscopes/capricorn.php', '_popup', 'resizable=yes, width=400, height=250, left=200, top=200, screenX=0, screenY=0'); PopUp.focus(); return false;"><img src="{CAPRICORN}" alt="{L_CAPRICORN}" title="{L_CAPRICORN}" /></a></td>
                <td><a href="mods/horoscopes/capricorn.php" onClick="PopUp=window.open('mods/horoscopes/capricorn.php', '_popup', 'resizable=yes, width=400, height=250, left=200, top=200, screenX=0, screenY=0'); PopUp.focus(); return false;" class="gensmall" title="{L_CAPRICORN}">{L_CAPRICORN}</a></td>
        </tr>
        <tr> 
                <td align="center"><a href="mods/horoscopes/gemini.php" onClick="PopUp=window.open('mods/horoscopes/gemini.php', '_popup', 'resizable=yes, width=400, height=250, left=200, top=200, screenX=0, screenY=0'); PopUp.focus(); return false;"><img src="{GEMINI}" alt="{L_GEMINI}" title="{L_GEMINI}" /></a></td>
                <td><a href="mods/horoscopes/gemini.php" onClick="PopUp=window.open('mods/horoscopes/gemini.php', '_popup', 'resizable=yes, width=400, height=250, left=200, top=200, screenX=0, screenY=0'); PopUp.focus(); return false;" class="gensmall" title="{L_GEMINI}">{L_GEMINI}</a></td>
        </tr>
        <tr> 
                <td align="center"><a href="mods/horoscopes/leo.php" onClick="PopUp=window.open('mods/horoscopes/leo.php', '_popup', 'resizable=yes, width=400, height=250, left=200, top=200, screenX=0, screenY=0'); PopUp.focus(); return false;"><img src="{LEO}" alt="{L_LEO}" title="{L_LEO}" /></a></td>
                <td><a href="mods/horoscopes/leo.php" onClick="PopUp=window.open('mods/horoscopes/leo.php', '_popup', 'resizable=yes, width=400, height=250, left=200, top=200, screenX=0, screenY=0'); PopUp.focus(); return false;" class="gensmall" title="{L_LEO}">{L_LEO}</a></td>
        </tr>
        <tr> 
                <td align="center"><a href="mods/horoscopes/libra.php" onClick="PopUp=window.open('mods/horoscopes/libra.php', '_popup', 'resizable=yes, width=400, height=250, left=200, top=200, screenX=0, screenY=0'); PopUp.focus(); return false;"><img src="{LIBRA}" alt="{L_LIBRA}" title="{L_LIBRA}" /></a></td>
                <td><a href="mods/horoscopes/libra.php" onClick="PopUp=window.open('mods/horoscopes/libra.php', '_popup', 'resizable=yes, width=400, height=250, left=200, top=200, screenX=0, screenY=0'); PopUp.focus(); return false;" class="gensmall" title="{L_LIBRA}">{L_LIBRA}</a></td>
        </tr>
        <tr> 
                <td align="center"><a href="mods/horoscopes/pisces.php" onClick="PopUp=window.open('mods/horoscopes/pisces.php', '_popup', 'resizable=yes, width=400, height=250, left=200, top=200, screenX=0, screenY=0'); PopUp.focus(); return false;"><img src="{PISCES}" alt="{L_PISCES}" title="{L_PISCES}" /></a></td>
                <td><a href="mods/horoscopes/pisces.php" onClick="PopUp=window.open('mods/horoscopes/pisces.php', '_popup', 'resizable=yes, width=400, height=250, left=200, top=200, screenX=0, screenY=0'); PopUp.focus(); return false;" class="gensmall" title="{L_PISCES}">{L_PISCES}</a></td>
        </tr>
        <tr> 
                <td align="center"><a href="mods/horoscopes/sagittarius.php" onClick="PopUp=window.open('mods/horoscopes/sagittarius.php', '_popup', 'resizable=yes, width=400, height=250, left=200, top=200, screenX=0, screenY=0'); PopUp.focus(); return false;"><img src="{SAGITTARIUS}" alt="{L_SAGITTARIUS}" title="{L_SAGITTARIUS}" /></a></td>
                <td><a href="mods/horoscopes/sagittarius.php" onClick="PopUp=window.open('mods/horoscopes/sagittarius.php', '_popup', 'resizable=yes, width=400, height=250, left=200, top=200, screenX=0, screenY=0'); PopUp.focus(); return false;" class="gensmall" title="{L_SAGITTARIUS}">{L_SAGITTARIUS}</a></td>
        </tr>
        <tr> 
                <td align="center"><a href="mods/horoscopes/scorpio.php" onClick="PopUp=window.open('mods/horoscopes/scorpio.php', '_popup', 'resizable=yes, width=400, height=250, left=200, top=200, screenX=0, screenY=0'); PopUp.focus(); return false;"><img src="{SCORPIO}" alt="{L_SCORPIO}" title="{L_SCORPIO}" /></a></td>
                <td><a href="mods/horoscopes/scorpio.php" onClick="PopUp=window.open('mods/horoscopes/scorpio.php', '_popup', 'resizable=yes, width=400, height=250, left=200, top=200, screenX=0, screenY=0'); PopUp.focus(); return false;" class="gensmall" title="{L_SCORPIO}">{L_SCORPIO}</a></td>
        </tr>
        <tr> 
                <td align="center"><a href="mods/horoscopes/taurus.php" onClick="PopUp=window.open('mods/horoscopes/taurus.php', '_popup', 'resizable=yes, width=400, height=250, left=200, top=200, screenX=0, screenY=0'); PopUp.focus(); return false;"><img src="{TAURUS}" alt="{L_TAURUS}" title="{L_TAURUS}" /></a></td>
                <td><a href="mods/horoscopes/taurus.php" onClick="PopUp=window.open('mods/horoscopes/taurus.php', '_popup', 'resizable=yes, width=400, height=250, left=200, top=200, screenX=0, screenY=0'); PopUp.focus(); return false;" class="gensmall" title="{L_TAURUS}">{L_TAURUS}</a></td>
        </tr>
        <tr> 
                <td align="center"><a href="mods/horoscopes/virgo.php" onClick="PopUp=window.open('mods/horoscopes/virgo.php', '_popup', 'resizable=yes, width=400, height=250, left=200, top=200, screenX=0, screenY=0'); PopUp.focus(); return false;"><img src="{VIRGO}" alt="{L_VIRGO}" title="{L_VIRGO}" /></a></td>
                <td><a href="mods/horoscopes/virgo.php" onClick="PopUp=window.open('mods/horoscopes/virgo.php', '_popup', 'resizable=yes, width=400, height=250, left=200, top=200, screenX=0, screenY=0'); PopUp.focus(); return false;" class="gensmall" title="{L_VIRGO}">{L_VIRGO}</a></td>
        </tr>
	</table></td>
  </tr>
</table>
<div style="padding: 2px;"></div>
<!-- END displayhoroscopes -->
<!-- ------------HOROSCOPES------------------- -->

<!-- ------------LINKS------------- -->
<!-- BEGIN displaylinks -->
<table width="100%" cellpadding="4" cellspacing="1" class="forumline">
<tr>
	<td class="catHead"><span class="cattitle"><a href="{U_LINKDB}" class="cattitle">{L_LINKDB}</a></span></td>
</tr>
<tr>
	<td class="row1" align="center"><div align="center">
	<marquee id="scroll_minibanners" behavior="scroll" direction="up" height="{LINKS_HEIGHT}" scrolldelay="75" scrollamount="2" onMouseover="this.scrollAmount=0" onMouseout="this.scrollAmount=1">
	<!-- END displaylinks -->
	<!-- BEGIN q_link -->
	<a href="{q_link.QL_URL}" target ="_blank"><img src="{q_link.QL_IMAGE}" alt="{q_link.QL_NAME}" title="{q_link.QL_NAME}" width="{QL_WIDTH}" height="{QL_HEIGHT}" vspace="3"></a><br />
	<!-- END q_link -->
	<!-- BEGIN displaylinks -->
	</marquee>
	</div></td>
</tr>
<tr>
	<td class="row2" align="center">{QL_GO}{QL_SPEED}{QL_SLOW}{QL_STOP}</td>
</tr>
</table>
<div style="padding: 2px;"></div>
<!-- END displaylinks -->
<!-- ------------LINKS------------- -->

<!-- ------------RANDOM GAME------------- -->
<!-- BEGIN displayrandomgame -->
<table cellpadding="4" cellspacing="1" width="{COLUMN_WIDTH}" class="forumline">
  <tr> 
	<td class="catHead"><span class="cattitle"><a href="{U_ACTIVITY}" class="cattitle">{L_RANDOM_GAME}</a></span></td> 
  </tr> 
  <tr> 
	<td class="row1" align="center">{RANDOM_GAME}</td> 
  </tr>
</table>
<div style="padding: 2px;"></div>
<!-- END displayrandomgame -->
<!-- ------------RANDOM GAME------------- -->

<!-- ------------DAILY WALLPAPER------------- -->
<!-- BEGIN displaywallpaper -->
<table cellpadding="4" cellspacing="1" width="{COLUMN_WIDTH}" class="forumline">
  <tr> 
	<td class="catHead"><span class="cattitle">{L_DAILY_WALLPAPER}</span></td>
</tr>
<tr>
   <td align="center" class="row1"><span class="gensmall"><a href="javascript:getWallpaper();"><img src="http://www.gamewallpapers.com/wallpaperoftheday/wallpaperoftheday.jpg" width="120" height="90" onError="document.all.gw_wallpaperoftheday.style.visibility='hidden';" alt="" title="" /></a><br /><br />{L_DLOAD_WALLPAPER}</span></td>
</tr>
</table>
<div style="padding: 2px;"></div>
<!-- END displaywallpaper -->
<!-- ------------DAILY WALLPAPER------------- -->

<!-- ------------------------------------------------------- -->
<!-- ------------DO NOT EDIT BELOW HERE OR MOVE------------- -->
	</td>
  </tr>
</table>
