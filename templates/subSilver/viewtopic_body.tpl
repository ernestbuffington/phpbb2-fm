<script language="Javascript">
<!--
function who(topicid)
{
        window.open("viewtopic_posted.php?t="+topicid, "whoposted", "toolbar=no,scrollbars=yes,resizable=yes,width=230,height=300");
}
-->
</script>

<table width="100%" cellspacing="0" cellpadding="0">
  <tr>
	<td id="topic">
<table width="100%" cellspacing="2" cellpadding="2">
<!-- BEGIN switch_forum_rules -->
<tr> 
	<td colspan="2"><table width="100%" cellpadding="4" cellspacing="1" class="bodyline"><tr><td class="row1"><b class="gen" style="color: {T_BODY_HLINK}">{L_FORUM_RULES}</b><span class="gensmall"><br /><br />{FORUM_RULES}</span></td></tr></table><br /></td>
</tr>
<!-- END switch_forum_rules -->
<tr> 
	<td valign="top"><a class="maintitle" href="{U_VIEW_TOPIC}"><div id="topic_title">{TOPIC_TITLE}</div></a><br /><br /><b class="gensmall">{TOTAL_USERS_ONLINE}<br />{LOGGED_IN_USER_LIST}</b></td>
	<td align="right" valign="top" nowrap="nowrap">
	<!-- BEGIN ratingsbox -->
	<form method="get" name="ratingsbox" action="{ratingsbox.U_RATINGS}">
	<input type="hidden" name="f" value="{FORUM_ID}">
	<select name="type">
	<option value="">{ratingsbox.L_LATEST_RATINGS}</option>
	<option value="p">{ratingsbox.L_HIGHEST_RANKED_POSTS}</option>
	<option value="t">{ratingsbox.L_HIGHEST_RANKED_TOPICS}</option>
	<option value="u">{ratingsbox.L_HIGHEST_RANKED_POSTERS}</option>
	</select> <input type="submit" value="{L_GO}" class="liteoption" /><br /><br />
	</form>	
	<!-- END ratingsbox -->
	{S_TOPIC_ADMIN}{SUBSCRIBE_MEMBERS}<b>{S_VIEW_KICKED}</b><br />{BANNER_14_IMG}<br /><b class="gensmall">{PAGINATION}</b></td>
</tr>
</table>
<table width="100%" cellspacing="2" cellpadding="2">
<tr> 
	<td align="left" valign="bottom" id="ttop" nowrap="nowrap" class="nav"><a href="{U_POST_NEW_TOPIC}"><img src="{POST_IMG}" alt="{L_POST_NEW_TOPIC}" title="{L_POST_NEW_TOPIC}" align="middle" /></a>{U_POST_REPLY_TOPIC}{THANK_IMG}</td>
	<td valign="bottom" id="ttop_nav" width="100%" class="nav">&nbsp;&nbsp;&nbsp;<a href="{U_INDEX}" class="nav">{L_INDEX}</a> 
      	<!-- BEGIN navrow -->
      	-> <a href="{navrow.U_SUBINDEX}" class="nav">{navrow.L_SUBINDEX}</a>
      	<!-- END navrow -->
      	<br />&nbsp;&nbsp;&nbsp;-> <a href="{U_VIEW_FORUM}" class="nav">{FORUM_NAVNAME}</a></td>
	<td id="ttop_nav" valign="bottom" nowrap="nowrap"><table width="100%" cellspacing="0" cellpadding="0"> 
      	<tr> 
		<td><a href="{U_VIEW_OLDER_TOPIC}"><img src="{PREVIOUS_TOPIC_IMG}" alt="{L_VIEW_PREVIOUS_TOPIC}" title="{L_VIEW_PREVIOUS_TOPIC}" /></a></td> 
        	<td nowrap="nowrap"><span class="nav" id="watch_topic">{SEARCH_IMG}{ANSWER_STATUS}{TELLAFRIEND}<a href="{U_PRINT}"><img src="{PRINT_TOPIC_IMG}" alt="{L_PRINT}" title="{L_PRINT}" /></a><a href="{U_DOWNLOAD_TOPIC}"><img src="{DOWNLOAD_TOPIC_IMG}" alt="{L_DOWNLOAD_TOPIC}" title="{L_DOWNLOAD_TOPIC}" /></a>{S_WATCH_TOPIC_IMG}{TOPIC_VIEW_IMG}<a href="{U_PRIVATEMSGS}"><img src="{PRIVMSG_IMG}" alt="{L_PRIVATEMSGS}" title="{L_PRIVATEMSGS}" /></a><a href="JavaScript:window.location.reload()"><img src="{REFRESH_TOPIC_IMG}" alt="{L_REFRESH_PAGE}" title="{L_REFRESH_PAGE}" /></a><script language="Javascript" type="text/javascript"><!--
		if (navigator.appName == 'Microsoft Internet Explorer' && parseInt(navigator.appVersion) >= 4)
		{
			document.write('<a href=\"#\" onclick=\"javascript:window.external.AddFavorite(location.href,document.title)\" title=\"{L_TOPIC_BOOKMARK}\">');
			document.write('<img src=\"{BOOKMARK_TOPIC_IMG}\" alt=\"{L_TOPIC_BOOKMARK}\" title=\"{L_TOPIC_BOOKMARK}\"></a>');
		}
		else
		{
			var msg = "";
			if(navigator.appName == "Netscape") msg += "<img src='{BOOKMARK_TOPIC_IMG}' alt='{L_TOPIC_BOOKMARK} (CTRL-D)' title='{L_TOPIC_BOOKMARK} (CTRL-D)'>";
			document.write(msg);
		}
		// -->
		</script></span></td> 
		<td><a href="{U_VIEW_NEWER_TOPIC}"><img src="{NEXT_TOPIC_IMG}" alt="{L_VIEW_NEXT_TOPIC}" title="{L_VIEW_NEXT_TOPIC}" /></a></td> 
        </tr> 
	</table></td> 
  </tr>
</table>
<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td id="poll_display" colspan="2">{POLL_DISPLAY}</td>
</tr>
<tr>
	<th width="150" class="thCornerL" nowrap="nowrap">{L_AUTHOR}</th>
	<th width="100%" class="thCornerR" nowrap="nowrap">{L_MESSAGE}</th>
</tr>
<!-- BEGIN postrow -->
<tr id="postrow_{postrow.AJAXED_I}"> 
	<td colspan="2" ondblclick="{postrow.AJAXED_POST_MENU}"><table width="100%" cellspacing="0" cellpadding="4">
	<tr>
		<td rowspan="2" width="150" nowrap="nowrap" valign="top" class="{postrow.ROW_CLASS}" style="border-right: 1px solid {T_TD_COLOR2}"><center><span class="name"><a name="{postrow.U_POST_ID}"></a><b>{postrow.POSTER_NAME}</b></span><br /><span class="postdetails">{postrow.POSTER_RANK}<br />{postrow.RANK_IMAGE}{postrow.POSTER_AVATAR}<br />{postrow.POSTER_STATUS}</center></span><br />
		<span class="postdetails">
		<!-- BEGIN collapse_userinfo -->
		<div onclick="document.getElementById('divDet{postrow.U_POST_ID}').style.display = document.getElementById('divDet{postrow.U_POST_ID}').style.display == 'block' ? 'none' : 'block';"style="padding: 1px; cursor: hand;">
                &darr; &nbsp;{collapse_userinfo.L_DETAILS}</div>
                <div id="divDet{postrow.U_POST_ID}" style="display: none; margin-top: 1px; padding: 2px;"> 
		<!-- END collapse_userinfo -->
			{postrow.CARD_IMG}
			{postrow.POSTER_JOINED}<br />
			{postrow.POSTER_USER_ID}
			{postrow.YEARSTARS}
			{postrow.SEARCH}
			{postrow.POST_DAY_STATS}
			{postrow.POST_PERCENT_STATS}
			{postrow.IP}
			{postrow.POSTER_FROM}
			{postrow.POSTER_TIME}
			{postrow.POSTER_FROM_FLAG}
			{postrow.POSTER_GENDER}
			{postrow.POSTER_AGE}
			{postrow.ZODIAC_IMG}{postrow.CHINESE_IMG}
			{postrow.CAKE}
			{postrow.POSTER_KARMA} <a href="{postrow.U_APPLAUD}">{postrow.APPLAUD_IMG}</a><a href="{postrow.U_SMITE}">{postrow.SMITE_IMG}</a>
			{postrow.JOBS}
			{postrow.POSTER_STYLE}
			{postrow.POINTS}
			{postrow.BANK_AMOUNT}
			{postrow.PROFILE_PHOTO}
			{postrow.POSTER_TROPHY}
			{postrow.ITEMSNAME}
			{postrow.ITEMS}
			{postrow.POSTER_MEDAL_COUNT}
			<!-- BEGIN medal -->
			<table cellspacing="0" cellpadding="5">
			<!-- BEGIN medal_row -->
			<tr align="left" valign="middle"> 
				<!-- BEGIN medal_col -->
				<td><img src="{postrow.medal.medal_row.medal_col.MEDAL_IMAGE}" alt="{postrow.medal.medal_row.medal_col.MEDAL_NAME} {postrow.medal.medal_row.medal_col.MEDAL_COUNT}" title="{postrow.medal.medal_row.medal_col.MEDAL_NAME} {postrow.medal.medal_row.medal_col.MEDAL_COUNT}" {postrow.medal.medal_row.medal_col.MEDAL_WIDTH} {postrow.medal.medal_row.medal_col.MEDAL_HEIGHT} /></td>
				<!-- END medal_col -->
			</tr>
			<!-- END medal_row -->
			</table>
			<!-- END medal -->
			<!-- BEGIN xdata -->
			<b>{postrow.xdata.NAME}:</b> {postrow.xdata.VALUE}<br />
			<!-- END xdata -->
			<b>{postrow.L_USER_GROUP}{postrow.L_NO_USER_GROUP}</b> {postrow.SHOW_USERGROUPS}{postrow.L_NO_USERGROUPS}
		<!-- BEGIN collapse_userinfo -->
		</div> 
		<!-- END collapse_userinfo -->
		</span></td>
		</form>
		<td class="{postrow.ROW_CLASS}" height="28" valign="top"><table cellspacing="0" cellpadding="0" width="100%">
		<tr>
			<td onDblClick="{postrow.AJAXED_EDIT_SUBJECT}" valign="top">{postrow.ICON} <b class="gen"><div id="p_{postrow.AJAXED_I}_subject" style="display: inline;">{postrow.POST_SUBJECT}</div></b><form action="" method="post" id="p_{postrow.AJAXED_I}_edit_subject" style="display: none;" onSubmit="ss({postrow.U_POST_ID},{postrow.AJAXED_I}); oc('p_{postrow.AJAXED_I}_subject', 'p_{postrow.AJAXED_I}_edit_subject'); return false;"><input type="text" name="{postrow.U_POST_ID}_subject" id="{postrow.U_POST_ID}_subject" class="post" size="30" value="{postrow.POST_SUBJECT}" /></form></td>
			<form method="post" action="{postrow.S_CARD}">
			<td align="right" nowrap="nowrap">{U_POST_REPLY_MINI_TOPIC} {postrow.THREAD_KICK_IMG} {postrow.QUOTE_IMG} {postrow.EDIT_IMG} {postrow.EDIT_DATE_IMG} {postrow.DELETE_IMG} {postrow.U_R_CARD} {postrow.U_Y_CARD} {postrow.U_BK_CARD} {postrow.U_G_CARD} {postrow.U_B_CARD}{postrow.CARD_EXTRA_SPACE}{postrow.CARD_HIDDEN_FIELDS} {postrow.IP_IMG} <a href="#bottom"><img src="{ICON_DOWN}" alt="{L_BACK_AT_BOTTOM}" title="{L_BACK_AT_BOTTOM}" /></a></td>
 			</form>
		</tr>
		<tr>
			<td class="gensmall" nowrap="nowrap"><a href="{postrow.U_MINI_POST}"><img src="{postrow.MINI_POST_IMG}" width="12" height="9" alt="{postrow.L_MINI_POST_ALT}" title="{postrow.L_MINI_POST_ALT}" /></a><span class="postdetails"><b>{L_POSTED}:</b> {postrow.POST_DATE}</span></td>
			<td align="right">{postrow.MINI_SINGLE_POST}{postrow.DOWNLOAD_POST}{postrow.POST_RATING}</td>
		</tr>
		</table>
		<hr /><span class="postbody"><span style="color: #{postrow.CUSTOM_POST_COLOR}"><div id="p_{postrow.U_POST_ID}_message" style="display: inline;">{postrow.MESSAGE}</div></span></span>{postrow.ATTACHMENTS}</td>
	</tr>
	<tr>
		<td class="{postrow.ROW_CLASS}" valign="bottom"><span class="gensmall" id="p_{postrow.U_POST_ID}_edited">{postrow.EDITED_MESSAGE}{postrow.SIGNATURE}</span></td>
	</tr>
	</table></td>
</tr>
<tr id="postrow_{postrow.AJAXED_I}_second"> 
	<td class="{postrow.ROW_CLASS}" colspan="2" height="28" align="left" valign="bottom"><table cellspacing="0" cellpadding="4">
	<tr> 
		<td valign="middle" nowrap="nowrap"><a href="#top"><img src="{ICON_UP}" alt="{L_BACK_TO_TOP}" title="{L_BACK_TO_TOP}" /></a> {postrow.HIDE_IMG} {postrow.PROFILE_IMG} 
		<!-- BEGIN switch_myInfo_active -->
		{postrow.MY_INFO_IMG}
		<!-- END switch_myInfo_active -->
		{postrow.PM_IMG} {postrow.EMAIL_IMG} {postrow.BUDDY_IMG} {postrow.REFERRAL_IMG} {postrow.GALLERY_IMG} {postrow.WWW_IMG} {postrow.STUMBLE_IMG} {postrow.MSN_IMG} {postrow.AIM_IMG} {postrow.YIM_IMG} {postrow.GTALK_IMG} {postrow.XFI_IMG}</td><td valign="top" nowrap="nowrap"><div style="position:relative"><div style="position:relative;">{postrow.SKYPE_IMG}</div><div style="position:absolute;left:3px;top:-1px">{postrow.SKYPE_USER}</div></div><script language="JavaScript" type="text/javascript"><!-- 
		if ( navigator.userAgent.toLowerCase().indexOf('mozilla') != -1 && navigator.userAgent.indexOf('5.') == -1 && navigator.userAgent.indexOf('6.') == -1 )
			document.write('{postrow.ICQ_IMG}');
		else
			document.write('</td><td valign="top" nowrap="nowrap"><div style="position:relative"><div style="position:absolute">{postrow.ICQ_IMG}</div><div style="position:absolute;left:3px;top:-1px">{postrow.ICQ_STATUS_IMG}</div></div>');
		//--></script><noscript>{postrow.ICQ_IMG}</noscript></td>
	</tr>
	</table></td>
</tr>
<!-- BEGIN thanks -->
<tr>
	<th class="thHead" colspan="2">{postrow.thanks.THANKFUL}</th>
</tr>
<tr>
	<td colspan="2" class="row1" height="30"><span id="hide_thank" style="display: block;" class="gensmall"><a href="javascript: void(0);" onclick="document.all.show_thank.style.display = 'block';document.all.hide_thank.style.display = 'none'">{postrow.thanks.THANKS_TOTAL}</a> {postrow.thanks.THANKED}</span><span id="show_thank" style="display: none;" class="gensmall">{postrow.thanks.THANKS}&nbsp;<br /><br /><div align="right"><a href="javascript: void(0);" onClick="document.all.show_thank.style.display = 'none';document.all.hide_thank.style.display = 'block'">[ {postrow.thanks.HIDE} ]</a></div></span></td>
</tr>
<!-- END thanks -->
<tr> 
	<td class="spaceRow" colspan="2" height="1"><img src="images/spacer.gif" alt="" width="1" height="7" /></td>
</tr>
<!-- BEGIN switch_ad -->
<tr> 
	<td colspan="2" class="row1"><table width="100%" cellspacing="0" cellpadding="4">
	<tr>
		<td rowspan="2" width="150" nowrap="nowrap" valign="top" class="row3" style="border-right: 1px solid {T_TD_COLOR2}"><center><b class="name">{postrow.switch_ad.L_SPONSOR}</b><br /><span class="postdetails">{postrow.switch_ad.L_SPONSOR}</span></center><br />&nbsp;</td>
		<td class="row3" height="28" valign="middle" align="center"><span class="postbody">{postrow.switch_ad.INLINE_AD}</span></td>
	</tr>
	</table></td>
</tr>
<tr> 
	<td class="spaceRow" colspan="2" height="1"><img src="images/spacer.gif" alt="" title="" width="1" height="7" /></td>
</tr>
<!-- END switch_ad -->
<!-- BEGIN switch_ad_style2 -->
<tr>
	<td colspan="2" class="row3" align="center">{postrow.switch_ad_style2.INLINE_AD}</td>
</tr>
<!-- END switch_ad_style2 -->
<!-- END postrow -->
<tr> 
	<td class="catBottom" colspan="2" align="center"><table cellspacing="0" cellpadding="0"><form method="post" action="{S_POST_DAYS_ACTION}">
	<tr>
		<td><span class="gensmall">{L_DISPLAY_POSTS}: {S_SELECT_POST_DAYS}&nbsp;{S_SELECT_POST_ORDER}&nbsp;<input type="submit" value="{L_GO}" class="liteoption" name="submit" /></span></td>
	</tr>
	</form></table></td>
</tr>
</table>
<table width="100%" cellspacing="2" cellpadding="2" align="center">
<tr> 
	<td id="bottom" align="left" valign="top" nowrap="nowrap" class="nav"><a href="{U_POST_NEW_TOPIC}"><img src="{POST_IMG}" alt="{L_POST_NEW_TOPIC}" title="{L_POST_NEW_TOPIC}" align="middle" /></a>{U_POST_REPLY_TOPIC}{THANK_IMG}</td>
	<td id="bottom_nav" align="left" valign="top" width="100%"><span class="nav">&nbsp;&nbsp;&nbsp;<a href="{U_INDEX}" class="nav">{L_INDEX}</a> 
      	<!-- BEGIN navrow -->
      	-> <a href="{navrow.U_SUBINDEX}" class="nav">{navrow.L_SUBINDEX}</a>
      	<!-- END navrow -->
      	<br />&nbsp;&nbsp;&nbsp;-> <a href="{U_VIEW_FORUM}" class="nav">{FORUM_NAVNAME}</a></span></td>
	<td valign="top" nowrap="nowrap" align="right"><table width="100%" cellspacing="0" cellpadding="0"> 
    	<tr> 
    		<td><a href="{U_VIEW_OLDER_TOPIC}"><img src="{PREVIOUS_TOPIC_IMG}" alt="{L_VIEW_PREVIOUS_TOPIC}" title="{L_VIEW_PREVIOUS_TOPIC}" width="15" height="25" /></a></td> 
        	<td nowrap="nowrap"><span class="nav" id="watch_topic">{SEARCH_IMG}{ANSWER_STATUS}{TELLAFRIEND}<a href="{U_PRINT}"><img src="{PRINT_TOPIC_IMG}" alt="{L_PRINT}" title="{L_PRINT}" /></a><a href="{U_DOWNLOAD_TOPIC}"><img src="{DOWNLOAD_TOPIC_IMG}" alt="{L_DOWNLOAD_TOPIC}" title="{L_DOWNLOAD_TOPIC}" /></a>{S_WATCH_TOPIC_IMG}{TOPIC_VIEW_IMG}<a href="{U_PRIVATEMSGS}"><img src="{PRIVMSG_IMG}" alt="{L_PRIVATEMSGS}" title="{L_PRIVATEMSGS}" /></a><a href="JavaScript:window.location.reload()"><img src="{REFRESH_TOPIC_IMG}" alt="{L_REFRESH_PAGE}" title="{L_REFRESH_PAGE}" /></a><script language="Javascript" type="text/javascript"><!--
		if (navigator.appName == 'Microsoft Internet Explorer' && parseInt(navigator.appVersion) >= 4)
		{
			document.write('<a href=\"#\" onclick=\"javascript:window.external.AddFavorite(location.href,document.title)\" title=\"{L_TOPIC_BOOKMARK}\">');
			document.write('<img src=\"{BOOKMARK_TOPIC_IMG}\" border=\"0\" alt=\"{L_TOPIC_BOOKMARK}\" title=\"{L_TOPIC_BOOKMARK}\"></a>');
		}
		else
		{
			var msg = "";
			if(navigator.appName == "Netscape") msg += "<img src='{BOOKMARK_TOPIC_IMG}' alt='{L_TOPIC_BOOKMARK} (CTRL-D)' title='{L_TOPIC_BOOKMARK} (CTRL-D)'>";
			document.write(msg);
		}
		// -->
		</script></span></td> 
        	<td><a href="{U_VIEW_NEWER_TOPIC}"><img src="{NEXT_TOPIC_IMG}" alt="{L_VIEW_NEXT_TOPIC}" title="{L_VIEW_NEXT_TOPIC}" width="14" height="25" /></a></td> 
    	</tr> 
	</table>
	<br /><b>{S_VIEW_KICKED}</b></td> 
  </tr>
</table>
	</td>
  </tr>
</table>

<table width="100%" cellspacing="2" cellpadding="2" align="center">
<tr>
	<td align="left" valign="top"><span class="gensmall"><b>{PAGE_NUMBER}</b> &nbsp;[ {TOPIC_POSTS} {L_POSTS} ]<br />&nbsp;<br /><br />{S_TOPIC_ADMIN}{SUBSCRIBE_MEMBERS}</span></td>
	<td align="right" valign="top" nowrap="nowrap"><b class="gensmall">{PAGINATION}</b><br />{BANNER_15_IMG}</td>
</tr>
<tr>
	<!-- BEGIN quick_reply -->
	<td valign="top" nowrap="nowrap" align="left"><form name='quick_reply' action='{quick_reply.U_QUICK_REPLY}' method='post'>      
	<!-- END quick_reply -->
	<!-- BEGIN switch_username_field --> 
	<b class="gensmall">{L_USERNAME}:</b> <input type="text" class="post" name="username" size="20" maxlength="25" value="" /><br /> 
    	<!-- END switch_username_field -->
    	<!-- BEGIN quick_reply -->
    	<textarea name="message" rows="7" cols="35" wrap="virtual" style="width:300px" class="post" onclick="{if(document.quick_reply.message.value=='{L_QUICK_REPLY_TOPIC}') document.quick_reply.message.value=''}">{L_QUICK_REPLY_TOPIC}</textarea><br /> 
    	<!-- END quick_reply -->
	<!-- BEGIN switch_confirm -->
	<b class="gensmall">{switch_confirm.L_CONFIRM_CODE}:</b> <input type="text" class="post" name="confirm_code" size="10" maxlength="6" value="" /><br />{switch_confirm.CONFIRM_IMG}<br />
	<!-- END switch_confirm -->
    
    	<!-- BEGIN quick_reply -->
	{quick_reply.U_HIDDEN_FORM_FIELDS}<input type="image" src="{quick_reply.QUICK_REPLY_IMG}" alt="{quick_reply.L_QUICK_REPLY_TOPIC}" title="{quick_reply.L_QUICK_REPLY_TOPIC}" onClick="if(document.quick_reply.message.value == '{quick_reply.L_QUICK_REPLY_TOPIC}' || document.quick_reply.message.value == ''){ alert('{quick_reply.L_EMPTY_MESSAGE}'); return false;}else{ return true;}" />
    	</form></td>
    	<!-- END quick_reply -->
	<td colspan="2" align="right" valign="top" nowrap="nowrap"><span class="gensmall">{JUMPBOX}{S_AUTH_LIST}</span></td>
</tr>
</table>

<!-- BEGIN similar -->
<br />
<table width="90%" cellspacing="1" cellpadding="4" align="center" class="forumline">
<tr>
	<td class="catHead" colspan="6" align="center"><span class="cattitle">{similar.L_SIMILAR}</span></td>
</tr>
<tr>
	<th colspan="2" class="thCornerL">{similar.L_TOPIC}</th>
  	<th class="thTop">{similar.L_FORUM}</th>
  	<th class="thTop">{similar.L_AUTHOR}</th>
  	<th class="thTop">{similar.L_REPLIES}</th>
  	<th class="thCornerR">{similar.L_LAST_POST}</th>
</tr>
<!-- BEGIN topics -->
<tr>
	<td class="row1" align="center" width="1%"><img src="{similar.topics.FOLDER}" alt="{similar.topics.ALT}" title="{similar.topics.ALT}" /></td>
 	<td class="row1">{similar.topics.NEWEST} <span class="topictitle">{similar.topics.TYPE}{similar.topics.TOPICS}</span></td>
 	<td class="row2" width="150" nowrap="nowrap"><span class="forumlink">{similar.topics.FORUM}</span></td>
 	<td class="row1" width="100" align="center"><span class="name">{similar.topics.AUTHOR}</span></td>
 	<td class="row2" width="50" align="center" nowrap="nowrap"><span class="postdatils">{similar.topics.REPLIES}</span></td>
 	<td class="row1" align="center" width="150" nowrap="nowrap"><span class="postdetails">{similar.topics.POST_TIME} {similar.topics.POST_URL}</span></td>
</tr>
<!-- END topics -->
</table>
<!-- END similar -->
