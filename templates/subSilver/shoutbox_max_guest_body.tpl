
<table width="100%" cellspacing="2" cellpadding="2" align="center">
  <tr> 
	<td><a href="{U_INDEX}" class="nav">{L_INDEX}</a></span></td>
	<td align="right" valign="bottom" class="nav">{PAGINATION}</td>
  </tr>
</table>

<table cellpadding="4" cellspacing="1" align="center" width="100%" class="forumline"><form action="{U_SHOUTBOX}" method="post" name="post" onsubmit="return checkForm(this)">
  <tr> 
	<th class="thHead" colspan="2">{S_HIDDEN_FORM_FIELDS}<input type="submit" tabindex="1" name="refresh" class="mainoption" value="{L_REFRESH}" /></th>
  </tr>
  <tr>
	<td class="catHead" colspan="2" align="center"><span class="cattitle">{L_SHOUTBOX}</span></td>
  </tr>
  <tr>
	<th class="thCornerL" width="150" nowrap="nowrap">{L_AUTHOR}</th>
	<th class="thCornerR" nowrap="nowrap">{L_MESSAGE}</th>
  </tr>
<!-- BEGIN shoutrow -->
  <tr> 
	<td height="100%" valign="top" class="{shoutrow.ROW_CLASS}"><span class="name"><b>{shoutrow.SHOUT_USERNAME}</b></span><br /><span class="postdetails">{shoutrow.USER_RANK}<br />{shoutrow.RANK_IMAGE}{shoutrow.USER_AVATAR}<br /><br />{shoutrow.USER_JOINED}<br />{shoutrow.USER_POSTS}<br />{shoutrow.USER_FROM}</span><br />&nbsp;</td>
	<td class="{shoutrow.ROW_CLASS}" height="28" valign="top" width="100%"><table width="100%" cellspacing="0" cellpadding="0">
	  <tr>
		<td width="100%"><a href="{shoutrow.U_MINI_POST}"><img src="{shoutrow.MINI_POST_IMG}" width="12" height="9" alt="{shoutrow.L_MINI_POST_ALT}" title="{shoutrow.L_MINI_POST_ALT}" /></a><span class="postdetails">{L_POSTED}: {shoutrow.TIME}</span></td>
		<td valign="top" align="right" nowrap="nowrap">{shoutrow.CENSOR_IMG}{shoutrow.DELETE_IMG}{shoutrow.IP_IMG} <a href="#bottom"><img src="{ICON_DOWN}" alt="{L_BACK_AT_BOTTOM}" title="{L_BACK_AT_BOTTOM}" /></a></td>
	  </tr>
	  <tr> 
		<td colspan="2"><hr /></td>
	  </tr>
	  <tr>
		<td colspan="2"><span class="postbody"><span style="color: #{shoutrow.CUSTOM_POST_COLOR}">{shoutrow.SHOUT}</span></span></td>
 	  </tr>
	</table></td>
  </tr>
  <tr> 
	<td class="spaceRow" colspan="2" height="1"><img src="images/spacer.gif" alt="" width="1" height="1" /></td>
  </tr>
<!-- END shoutrow -->
  <tr>
	<td class="catBottom" colspan="2">&nbsp;</td>
  </tr>
</form></table>
<table width="100%" cellspacing="2" align="center" cellpadding="2">
  <tr> 
	<td valign="top" class="nav">{PAGINATION}</td>
	<td align="right" valign="middle" nowrap="nowrap"><br />{JUMPBOX}</td> 
  </tr> 
</table> 
