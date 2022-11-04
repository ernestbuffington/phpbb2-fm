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
	<td class="row1"><span class="gensmall">{RECORD_USERS} <br /><br /> {VISIT_COUNTER} {START_DATE}</span></td>
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

<!-- ------------IFRAME------------- -->
<!-- BEGIN pagedesc -->
<table width="100%" cellpadding="0" cellspacing="1" class="forumline">
  <tr>
	<th class="thHead">{pagedesc.U_PORTAL_SITE_NAME}</th>
  </tr>
  <tr>
	<td><iframe name="webpage" marginwidth="0" marginheight="0" src="{pagedesc.U_PORTAL_SITE_URL}" frameborder="0" width="100%" height="{pagedesc.IFRAME_HEIGHT}" allowtransparency="true"></iframe></td>
  </tr>
  <tr>
	<td class="catBottom">&nbsp;</td>
  </tr>
</table>
<!-- END pagedesc -->
<!-- ------------IFRAME------------- -->

<!-- ------------------------------------------------------- -->
<!-- ------------DO NOT EDIT BELOW HERE OR MOVE------------- -->
	</td>
  </tr>
</table>