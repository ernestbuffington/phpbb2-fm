<!-- BEGIN switch_list_staff -->
<table width="100%" cellspacing="2" cellpadding="2" align="center">
  <tr> 
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></td>
  </tr>
</table>

<table width="100%" cellpadding="4" cellspacing="1" class="forumline" align="center">
  <tr>
        <th class="thCornerL">&nbsp;{L_USERNAME}&nbsp;</th>
        <th class="thTop">&nbsp;{L_FORUMS}&nbsp;</th>
        <th class="thTop">&nbsp;{L_LOCATION}&nbsp;</th>
        <th class="thTop">&nbsp;{L_CONTACT}&nbsp;</th>
        <th class="thCornerR">&nbsp;{L_MESSENGER}&nbsp;</th>
  </tr>
  <!-- BEGIN user_level -->
  <tr>
        <td class="catSides" colspan="6" align="left"><span class="cattitle">{switch_list_staff.user_level.USER_LEVEL}</span></td>
  </tr>
  <!-- BEGIN staff -->
  <tr> 
        <td align="left" class="{switch_list_staff.user_level.staff.ROW_CLASS}" valign="top">
                <span class="gen"><a href="{switch_list_staff.user_level.staff.U_PROFILE}" onclick="window.open('{switch_list_staff.user_level.staff.U_PROFILE}', 'view_profile', 'HEIGHT=400,top=10,left=10,status=no,resizable=yes,menubar=no,scrollbars=yes,toolbar=no,WIDTH=700');return false;" class="gen">{switch_list_staff.user_level.staff.USERNAME}</a></span>
                <span class="gensmall"> {switch_list_staff.user_level.staff.USER_STATUS}<br />{switch_list_staff.user_level.staff.RANK}<br />{switch_list_staff.user_level.staff.RANK_IMAGE}<br />
                {switch_list_staff.user_level.staff.AVATAR}</span></td>
        <td align="left" class="{switch_list_staff.user_level.staff.ROW_CLASS}" width="30%" valign="top"><span class="gen">{switch_list_staff.user_level.staff.FORUMS}</span></td>
        <td class="{switch_list_staff.user_level.staff.ROW_CLASS}" valign="top" align="center"><span class="genmed">{switch_list_staff.user_level.staff.LOCATION}</span></td>
        <td class="{switch_list_staff.user_level.staff.ROW_CLASS}" width="16%" valign="top">{switch_list_staff.user_level.staff.EMAIL} {switch_list_staff.user_level.staff.PM} {switch_list_staff.user_level.staff.WWW} <div style="position:relative"><div style="position:relative;">{switch_list_staff.user_level.staff.SKYPE_IMG}</div><div style="position:absolute;left:3px;top:-1px">{switch_list_staff.user_level.staff.SKYPE_USER}</div></div>
        <td class="{switch_list_staff.user_level.staff.ROW_CLASS}" width="16%" valign="top" align="center">{switch_list_staff.user_level.staff.MSN} {switch_list_staff.user_level.staff.YIM} {switch_list_staff.user_level.staff.AIM} {switch_list_staff.user_level.staff.ICQ} {switch_list_staff.user_level.staff.GTALK} {switch_list_staff.user_level.staff.XFI}</td>
  </tr>
  <!-- END staff -->
  <!-- END user_level -->
  <tr>
        <td class="catBottom" colspan="5">&nbsp;</td>
  </tr>
</table>
<br />
<table width="100%">
  <tr> 
	<td align="right" valign="top">{JUMPBOX}</td>
  </tr>
</table>
<br />
<div align="center" class="copyright">Staff Site 2.2.3 &copy; 2002, {COPYRIGHT_YEAR} Acid</div>
<!-- END switch_list_staff -->

<!-- BEGIN switch_view_profile -->
<table cellspacing="1" cellpadding="4" align="center" width="100%" class="forumline">
  <tr> 
        <th colspan="2" class="thHead">{L_ABOUT_USER}</th>
  </tr>
  <tr> 
        <td valign="middle" class="row1"><span class="genmed">{L_POSTS}</span></td>
        <td valign="middle" class="row2" nowrap="nowrap">&nbsp;<span class="gen"> {POSTS} ({POST_PERCENT},&nbsp;* {POSTS_PER_DAY})</span></td>
  </tr>
  <tr> 
        <td valign="middle" class="row1"><span class="genmed">{L_TOPICS}</span></td>
        <td valign="middle" class="row2" nowrap="nowrap">&nbsp;<span class="gen"> {TOPICS} ({TOPIC_PERCENT},&nbsp;* {TOPICS_PER_DAY})</span></td>
  </tr>
<!-- BEGIN last_posts -->
  <tr>
        <td colspan="2" class="row3"><table cellspacing="0" cellpadding="0" align="center" width="93%">
        <tr>
        	<td align="left" width="30%"><span class="genmed"><a href="{switch_view_profile.last_posts.FORUM_URL}" target="_blank" onclick="opener.location.href='{switch_view_profile.last_posts.FORUM_URL}'; return false;" class="genmed"><b>{switch_view_profile.last_posts.FORUM_NAME}</b></a></span></td>
                <td align="left" width="40%"><span class="genmed"><a href="{switch_view_profile.last_posts.LAST_POST_URL}" target="_blank" onclick="opener.location.href='{switch_view_profile.last_posts.LAST_POST_URL}'; return false;" class="genmed">{switch_view_profile.last_posts.LAST_POST_TITLE}</a></span></td>
                <td align="right"><span class="gensmall">{switch_view_profile.last_posts.LAST_POST_TIME}<br />{switch_view_profile.last_posts.LAST_POST_PERIOD}</span></td>
	</tr>
        </table></td>
  </tr>
<!-- END last_posts -->
  <tr> 
        <td valign="middle" class="row1"><span class="genmed">{L_JOINED}</span></td>
        <td valign="middle" class="row2" nowrap="nowrap">&nbsp;<span class="gen">{JOINED}&nbsp; {JOINED_PERIOD}</span></td>
  </tr>
<!-- BEGIN view_signature -->
  <tr> 
        <td class="spaceRow" colspan="2" height="1"><img src="images/spacer.gif" height="1" width="1" alt="" title="" /></td>
  </tr>
  <tr>
        <td colspan="2" class="row2"><span class="gensmall">{SIGNATURE}</span></td>
  </tr>
<!-- END view_signature -->
  <tr>
        <td colspan="2" align="center" class="catBottom"><span class="genmed"><a href="javascript:window.close();" class="genmed">{L_CLOSE_WINDOW}</a></span></td>
  </tr>
</table>
<br/>
<div align="center" class="copyright">Staff Site 2.2.3 &copy; 2002, {COPYRIGHT_YEAR} Acid</div>
<!-- END switch_view_profile -->