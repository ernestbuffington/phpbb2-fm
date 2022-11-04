<script language="Javascript" src="templates/js/toggle.js"></script>

{S_READ_COMMENTS}

<table width="100%" cellspacing="2" cellpadding="2" align="center">
<tr> 
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></td>
	<td align="right">{U_VISITS}</td>
</tr>
</table>

<table class="forumline" width="100%" cellspacing="1" cellpadding="4" align="center">
<tr>
	<th class="thHead" colspan="2">{L_VIEWING_PROFILE}</th>
</tr>
<tr> 
	<td class="catLeft" width="45%" align="center"><b class="gen">{L_AVATAR}</b></td>
	<td class="catRight" width="55%" align="center"><b class="gen">{L_ABOUT_USER}</b></td>
</tr>
<tr> 
	<td class="row1" valign="top" align="center"><b class="gen">{USERNAME}</b><br /><span class="postdetails">{POSTER_RANK}<br />{RANK_IMAGE}{VIEW_STATUS}<br /><br />{AVATAR_IMG}</span></td>
	<td class="row1" valign="top"><table width="100%" cellspacing="1" cellpadding="2">
	<tr> 
		<td valign="middle" align="right" nowrap="nowrap"><span class="gen">{L_JOINED}:&nbsp;</span></td>
		<td width="100%"><b class="gen">{JOINED}</b></td>
	</tr>
	<tr> 
		<td valign="middle" align="right" nowrap="nowrap"><span class="gen">{L_MEMBER_FOR}:&nbsp;</span></td>
		<td><b class="gen">{MEMBER_FOR} {L_DAYS}</b></td>
	</tr>
	<tr> 
		<td valign="middle" align="right" nowrap="nowrap"><span class="gen">{L_LOGON}:&nbsp;</span></td> 
		<td><b class="gen">{LAST_LOGON}</b></td> 
	</tr> 
	<!-- BEGIN switch_user_admin -->
	<tr> 
		<td valign="middle" align="right" nowrap="nowrap"><span class="gen">{L_NUMBER_OF_VISIT}:&nbsp;</span></td> 
		<td><b class="gen">{NUMBER_OF_VISIT}</b></td> 
	</tr> 
	<tr> 
		<td valign="middle" align="right" nowrap="nowrap"><span class="gen">{L_NUMBER_OF_PAGES}:&nbsp;</span></td> 
		<td><b class="gen">{NUMBER_OF_PAGES}</b></td> 
	</tr> 
	<tr>
		<td valign="middle" align="right" nowrap="nowrap"><span class="gen">{L_TOTAL_ONLINE_TIME}:&nbsp;</span></td> 
		<td><b class="gen">{TOTAL_ONLINE_TIME}</b></td> 
	</tr> 
	<tr> 
		<td valign="middle" align="right" nowrap="nowrap"><span class="gen">{L_LAST_ONLINE_TIME}:&nbsp;</span></td> 
		<td><b class="gen">{LAST_ONLINE_TIME}</b></td> 
	</tr> 
	<!-- END switch_user_admin -->
 	<tr> 
		<td valign="top" align="right" nowrap="nowrap"><span class="gen">{L_TOTAL_POSTS}:&nbsp;</span></td>
		<td valign="top" nowrap="nowrap"><b class="gen">{POSTS}</b><br /><span class="genmed">[{POST_PERCENT_STATS} / {POST_DAY_STATS}]</span><br /><span class="genmed"><a href="{U_SEARCH_USER}" class="genmed">{L_SEARCH_USER_POSTS}</a><br />
		<!-- BEGIN rating -->
		<a href="{rating.U_LINK}" class="genmed">{rating.L_LINK}</a><br />
		<!-- END rating -->
		<a href="{U_PERSONAL_GALLERY}" class="genmed">{L_PERSONAL_GALLERY}</a><br />
		{INVENTORYLINK}
		</span></td>
	</tr>
	<!-- BEGIN switch_referral_on -->
	<tr> 
		<td valign="top" align="right" nowrap="nowrap"><span class="gen">{switch_referral_on.L_REFERRAL_TOTAL}:&nbsp;</span></td>
		<td><b class="gen">{switch_referral_on.REFERRAL_URL}</b></td>
	</tr>
	<!-- END switch_referral_on -->
	<!-- BEGIN switch_user_admin -->
       	<tr>
        	<td valign="middle" align="right" nowrap="nowrap"><span class="gen">{L_ZIPCODE}:&nbsp;</span></td>
		<td><b class="gen">{ZIPCODE}</td>
	</tr>
	<!-- END switch_user_admin -->
	</table></td>
</tr>
<tr> 
	<td class="catLeft" align="center"><b class="gen">{L_CONTACT}</b></td>
	<td class="catRight" align="center"><b class="gen">{L_USER_PROFILE}</b></td>
</tr>
<tr> 
	<td class="row1" valign="top"><table width="100%" cellspacing="1" cellpadding="2">
	<tr> 
		<td valign="middle" nowrap="nowrap" align="right"><span class="gen">{L_BUDDY}:</span></td>
		<td class="row1" valign="middle" width="100%"><span class="gen">{BUDDY_IMG}</span></td>
	</tr>
	<tr> 
		<td valign="middle" align="right" nowrap="nowrap"><span class="gen">{L_EMAIL_ADDRESS}:</span></td>
		<td class="row1" valign="middle"><span class="gen">{EMAIL_IMG}</span></td>
	</tr>
	<tr> 
		<td valign="middle" nowrap="nowrap" align="right"><span class="gen">{L_PM}:</span></td>
		<td class="row1" valign="middle"><span class="gen">{PM_IMG}</span></td>
	</tr>
	<!-- BEGIN switch_msnm -->
	<tr> 
		<td valign="middle" nowrap="nowrap" align="right"><span class="gen">{switch_msnm.L_MESSENGER}:</span></td>
		<td class="row1" valign="middle"><span class="gen">{switch_msnm.MSN_IMG}</span></td>
	</tr>
	<!-- END switch_msnm -->
	<!-- BEGIN switch_yim -->
	<tr> 
		<td valign="middle" nowrap="nowrap" align="right"><span class="gen">{switch_yim.L_YAHOO}:</span></td>
		<td class="row1" valign="middle"><span class="gen">{switch_yim.YIM_IMG}</span></td>
	</tr>
	<!-- END switch_yim -->
	<!-- BEGIN switch_aim -->
	<tr> 
		<td valign="middle" nowrap="nowrap" align="right"><span class="gen">{switch_aim.L_AIM}:</span></td>
		<td class="row1" valign="middle"><span class="gen">{switch_aim.AIM_IMG}</span></td>
	</tr>
	<!-- END switch_aim -->
 	<!-- BEGIN switch_xfi -->
   	<tr>
    		<td valign="middle" nowrap="nowrap" align="right"><span class="gen">{switch_xfi.L_XFI}:</span></td>
    		<td class="row1" valign="middle"><span class="gen">{switch_xfi.XFI_IMG}</span></td>
    	</tr>
	<!-- END switch_xfi -->
 	<!-- BEGIN switch_skype -->
	<tr> 
		<td valign="middle" nowrap="nowrap" align="right"><span class="gen">{switch_skype.L_SKYPE}:</span></td>
		<td class="row1" valign="middle"><table cellspacing="0" cellpadding="0"><tr><td nowrap="nowrap"><div style="position:relative;height:18px"><div style="position:absolute">{switch_skype.SKYPE_IMG}</div><div style="position:absolute;left:3px;top:-1px">{switch_skype.SKYPE_USER}</div></div></td></tr></table></td>
	</tr>
 	<!-- END switch_skype -->
  	<!-- BEGIN switch_gtalk -->
  	<tr>
    		<td valign="middle" nowrap="nowrap" align="right"><span class="gen">{switch_gtalk.L_GTALK}:</span></td>
    		<td class="row1" valign="middle"><span class="gen">{switch_gtalk.GTALK}</span></td>
    	</tr>
 	<!-- END switch_gtalk -->
  	<!-- BEGIN switch_icq -->
	<tr> 
		<td valign="middle" nowrap="nowrap" align="right"><span class="gen">{switch_icq.L_ICQ_NUMBER}:</span></td>
		<td class="row1"><script language="JavaScript" type="text/javascript"><!-- 
		if ( navigator.userAgent.toLowerCase().indexOf('mozilla') != -1 && navigator.userAgent.indexOf('5.') == -1 && navigator.userAgent.indexOf('6.') == -1 )
			document.write(' {switch_icq.ICQ_IMG}');
		else
			document.write('<table cellspacing="0" cellpadding="0"><tr><td nowrap="nowrap"><div style="position:relative;height:18px"><div style="position:absolute">{switch_icq.ICQ_IMG}</div><div style="position:absolute;left:3px;top:-1px">{switch_icq.ICQ_STATUS_IMG}</div></div></td></tr></table>');
		//--></script><noscript>{switch_icq.ICQ_IMG}</noscript></td>
	</tr>
  	<!-- END switch_icq -->
  	<!-- BEGIN switch_www -->
	<tr> 
		<td valign="middle" align="right" nowrap="nowrap"><span class="gen">{switch_www.L_WEBSITE}:&nbsp;</span></td>
		<td class="row1" valign="middle"><span class="gen">{switch_www.WWW_IMG}</span></td>
	</tr>
    	<!-- END switch_www -->
   	<!-- BEGIN switch_stumble -->
 	<tr> 
    		<td valign="middle" align="right" nowrap="nowrap"><span class="gen">{switch_stumble.L_STUMBLE}:&nbsp;</span></td>
        	<td class="row1" valign="middle"><span class="gen">{switch_stumble.STUMBLE_IMG}</span></td>
	</tr>
  	<!-- END switch_stumble -->
	</table></td>
	<td class="row1" valign="top"><table width="100%" cellspacing="1" cellpadding="2">
   	<!-- BEGIN switch_realname -->
	<tr> 
		<td valign="middle" align="right" nowrap="nowrap"><span class="gen">{switch_realname.L_REALNAME}:&nbsp;</span></td> 
		<td><b class="gen">{switch_realname.REALNAME}</b></td> 
	</tr>
   	<!-- END switch_realname -->
   	<!-- BEGIN switch_groups -->
	<tr> 
		<td valign="middle" align="right" nowrap="nowrap"><span class="gen">{switch_groups.L_USER_GROUP}:&nbsp;</span></td> 
		<td valign="middle"><b class="gen">{SHOW_USERGROUPS}</b></td> 
	</tr>
   	<!-- END switch_groups -->
   	<!-- BEGIN switch_jobs -->
	<tr> 
		<td valign="middle" align="right" nowrap="nowrap"><span class="gen">{switch_jobs.L_JOBS}:&nbsp;</span></td>
		<td><b class="gen">{switch_jobs.JOBS}</b></td>
	</tr>
   	<!-- END switch_jobs -->
   	<!-- BEGIN switch_location -->
	<tr> 
		<td valign="middle" align="right" nowrap="nowrap"><span class="gen">{switch_location.L_LOCATION}:&nbsp;</span></td>
		<td><b class="gen">{switch_location.LOCATION}</b></td>
	</tr>
   	<!-- END switch_location -->
	<tr> 
		<td valign="middle" align="right" nowrap="nowrap"><span class="gen">{L_LOCATION_TIME}:&nbsp;</span></td>
		<td width="100%"><b class="gen">{LOCATION_TIME}</b></td>
	</tr>
   	<!-- BEGIN switch_occ -->
	<tr> 
		<td valign="middle" align="right" nowrap="nowrap"><span class="gen">{switch_occ.L_OCCUPATION}:&nbsp;</span></td>
		<td><b class="gen">{switch_occ.OCCUPATION}</b></td>
	</tr>
   	<!-- END switch_occ -->
   	<!-- BEGIN switch_interests -->
	<tr> 
		<td valign="middle" align="right" nowrap="nowrap"><span class="gen">{switch_interests.L_INTERESTS}:&nbsp;</span></td>
		<td><b class="gen">{switch_interests.INTERESTS}</b></td>
	</tr>
   	<!-- END switch_interests -->
   	<!-- BEGIN switch_gender -->
	<tr> 
		<td valign="middle" align="right" nowrap="nowrap"><span class="gen">{switch_gender.L_GENDER}:&nbsp;</span></td>
	  	<td width="100%"><b class="gen">{switch_gender.GENDER}</b></td>
	</tr>
   	<!-- END switch_gender -->
   	<!-- BEGIN switch_bday -->
	<tr> 
		<td valign="middle" align="right" nowrap="nowrap"><span class="gen">{switch_bday.L_BIRTHDAY}:&nbsp;</span></td>
		<td><b class="gen" nowrap="nowrap">{switch_bday.BIRTHDAY}{switch_bday.CAKE}</b></td>
	</tr>
	<tr> 
		<td valign="middle" align="right" nowrap="nowrap"><span class="gen">{switch_bday.L_ZODIAC}:&nbsp;</span></td>
		<td><b class="gen">{switch_bday.ZODIAC} &nbsp;{switch_bday.ZODIAC_IMG}</b></td>
	</tr>
	<tr>
		<td valign="middle" align="right" nowrap="nowrap"><span class="gen">{switch_bday.L_CHINESE}:&nbsp;</span></td>
		<td><b class="gen">{switch_bday.CHINESE} &nbsp;{switch_bday.CHINESE_IMG}</b></td>
	</tr>
   	<!-- END switch_bday -->
	<!-- BEGIN switch_karma -->
	<tr>
		<td valign="middle" align="right" nowrap="nowrap"><span class="gen">{switch_karma.L_KARMA}:&nbsp;</span></td>
    		<td><b class="gen">{switch_karma.KARMA}</b></td>
	</tr>
	<!-- END switch_karma -->
	<!-- BEGIN xdata -->
	<tr>
	  <td valign="top" align="right" nowrap="nowrap"><span class="gen">{xdata.NAME}:&nbsp;</span></td>
	  <td><b class="gen">{xdata.VALUE}</b></td>
	</tr>
	<!-- END xdata -->
	</table></td>
</tr>
<tr> 
	<td class="catLeft" align="center"><b class="gen">{L_INVENTORY}</b></td>
	<td class="catRight" align="center"><b class="gen">{L_ATTACHMENTS}</b></td>
</tr>
<tr>
	<td class="row1" valign="top"><table width="100%" cellspacing="1" cellpadding="2">
	<!-- BEGIN switch_points -->
	<tr>
		<td valign="middle" align="right" nowrap="nowrap"><span class="gen">{switch_points.L_POINTS}:&nbsp;</span></td>
		<td width="100%"><b class="gen">{switch_points.POINTS}</b><span class="genmed">{switch_points.DONATE_POINTS}</span></td>
	</tr>
	<!-- END switch_points -->
	<!-- BEGIN switch_items -->
	<tr>
		<td valign="middle" align="right" nowrap="nowrap"><span class="gen">{switch_items.L_ITEMS}:&nbsp;</span></td>
		<td width="100%"><b class="gen">{switch_items.INVENTORYPICS}</b></td>
	</tr>
	<!-- END switch_items -->
	<!-- BEGIN trophy -->
	<tr> 
		<td valign="middle" align="right" nowrap="nowrap"><span class="gen">{trophy.TROPHY_TITLE}:&nbsp;</span></td>	
		<td width="100%"><b class="gen">{trophy.PROFILE_TROPHY}</b></td>
	</tr>
	<!-- END trophy -->
	</table></td>
	<td class="row1" valign="top" align="center">
	<!-- BEGIN switch_upload_limits -->
	<table width="100%" cellspacing="1" cellpadding="2">
	<tr> 
		<td valign="top" align="right" nowrap="nowrap"><span class="gen">{L_UPLOAD_QUOTA}:&nbsp;</span></td>	
		<td><table width="175" cellspacing="1" cellpadding="2" class="bodyline">
		<tr> 
			<td colspan="3" width="100%" class="row2"><table cellspacing="0" cellpadding="1">
			<tr> 
				<td bgcolor="{T_TD_COLOR2}"><img src="images/spacer.gif" width="{UPLOAD_LIMIT_IMG_WIDTH}" height="8" alt="{UPLOAD_LIMIT_PERCENT}" /></td>
			</tr>
			</table></td>
		</tr>
		<tr> 
			<td width="33%" class="row1"><span class="gensmall">0%</span></td>
			<td width="34%" align="center" class="row1"><span class="gensmall">50%</span></td>
			<td width="33%" align="right" class="row1"><span class="gensmall">100%</span></td>
		</tr>
		</table>
		<span class="genmed">[{UPLOADED} / {QUOTA} / {PERCENT_FULL}]<br /><a href="{U_UACP}" class="genmed">{L_UACP}</a><br /><a href="{U_ATTACHMENTS}" class="genmed">{L_ALL_ATTACHMENTS}</a></span></td>
	</tr>
	</table>
	<!-- END switch_upload_limits -->
	</td>
</tr>
<!-- BEGIN switch_user_sig_block -->
<tr> 
	<td class="catSides" align="center" colspan="2"><b class="gen">{L_SIGNATURE}</b></td>
</tr>
<tr>
	<td class="row1" colspan="2"><table cellspacing="0" cellpadding="10" align="center">
	<tr>
		<td><b class="gen">{USER_SIG}</b></td>
  	</tr>
	</table></td>
</tr>
<!-- END switch_user_sig_block -->
<!-- BEGIN switch_profile_photo -->
<tr> 
	<td class="catSides" align="center" colspan="2"><b class="gen">{switch_profile_photo.L_PHOTO}</b></td>
</tr> 
<tr>
	<td class="row1" colspan="2"><a name="photo"></a><table cellspacing="0" cellpadding="10" align="center">
	<tr>
		<td align="center">{switch_profile_photo.PHOTO_IMG}</td>
  	</tr>
	</table></td>
</tr>
<!-- END switch_profile_photo -->
</table>
<br />

<!-- BEGIN switch_display_medal -->
<table class="forumline" width="100%" cellspacing="1" cellpadding="4" align="center">
  <tr> 
	<td class="catHead" align="center" colspan="2"><b class="gen">{L_MEDAL_INFORMATION}</b></td>
  </tr>
  <tr>
	<td class="row1" align="center" valign="middle" width="10%"><span class="gen">{L_USER_MEDAL}: <b class="gen">{USER_MEDAL_COUNT}</b></span>
		<!-- BEGIN medal -->
		<br /><br />{switch_display_medal.medal.MEDAL_BUTTON}
		<!-- END medal -->
	</td>
	<td class="row1" valign="middle" align="left" nowrap="nowrap" width="100%">
		<!-- BEGIN details -->
		&nbsp;{switch_display_medal.details.MEDAL_IMAGE_SMALL}&nbsp;
		<!-- END details -->
	</td>
  </tr>
</table>
<div style="width: 100%; overflow: hidden; display: none;" id="toggle_medal">
<a name="medal"></a>
<table class="forumline" width="100%" cellspacing="1" cellpadding="4" align="center">
  <tr>
	<th class="thCornerL">&nbsp;{L_MEDAL_NAME}&nbsp;</th>
	<th class="thCornerR">&nbsp;{L_MEDAL_DETAIL}&nbsp;</th>
  </tr>
<!-- BEGIN details -->
  <tr>
	<td class="row1" nowrap="nowrap"><table width="100%" cellspacing="1" cellpadding="4">
	<tr>
		<td align="center" class="gen">{switch_display_medal.details.MEDAL_CAT}</td>
	</tr>
	<tr>
		<td align="center" class="genmed">{switch_display_medal.details.MEDAL_NAME}</td>
	</tr>
	<tr>
		<td align="center">{switch_display_medal.details.MEDAL_IMAGE}</td>
	</tr>
	<tr>
		<td align="center" class="gen">{switch_display_medal.details.MEDAL_COUNT}</td>
	</tr>
	</table></td>
	<td class="row1" valign="top"><table width="100%" cellspacing="1" cellpadding="4">
	<tr>
		<td valign="middle" align="right" nowrap="nowrap"><span class="gen">{switch_display_medal.details.L_MEDAL_DESCRIPTION}:&nbsp;</td>
		<td width="100%"><b class="gen">{switch_display_medal.details.MEDAL_DESCRIPTION}</b></span></td>
	</tr>
	{switch_display_medal.details.MEDAL_ISSUE}
	</table></td>
</tr>
<!-- END details -->
</table>
</div>
<!-- END switch_display_medal -->


{USER_POSTS_VIEW_DATA}
{BB_USAGE_STATS_TEMPLATE}

<!-- BEGIN profile_char -->
<br />
{profile_char.CHAR_PROFILE}
<!-- END profile_char -->
<br />

<table width="100%" cellspacing="2" align="center"> 
<tr> 
	<td align="right" valign="middle" nowrap>{JUMPBOX}</td> 
</tr> 
</table> 



