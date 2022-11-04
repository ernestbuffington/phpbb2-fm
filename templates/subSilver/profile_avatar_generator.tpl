<table width="100%" cellspacing="2" cellpadding="2" align="center">
  <tr> 
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></td>
  </tr>
</table>

{CPL_MENU_OUTPUT}

<table cellpadding="4" cellspacing="1" width="100%" class="forumline"><form action="{S_PROFILE_ACTION}" method="post">
<tr>
	<th class="thHead" colspan="5">{L_AVATAR_GENERATOR}</th>
</tr>
<tr>
	<td width="20%" class="row1" align="center"><img src="{AVATAR_TEMPLATE_PATH}/blue.gif" width="80" height="80" alt="" title="" /></td>
	<td width="20%" class="row1" align="center"><img src="{AVATAR_TEMPLATE_PATH}/gray.gif" width="80" height="80" alt="" title="" /></td>
	<td width="20%" class="row1" align="center"><img src="{AVATAR_TEMPLATE_PATH}/green.gif" width="80" height="80" alt="" title="" /></td>
	<td width="20%" class="row1" align="center"><img src="{AVATAR_TEMPLATE_PATH}/pink.gif" width="80" height="80" alt="" title="" /></td>
	<td width="20%" class="row1" align="center"><img src="{AVATAR_TEMPLATE_PATH}/purple.gif" width="80" height="80" alt="" title="" /></td>
</tr>
<tr>
	<td class="row2" align="center"><input type="radio" name="avatarimage" value="Blue"></td>
	<td class="row2" align="center"><input type="radio" name="avatarimage" value="Gray"></td>
	<td class="row2" align="center"><input type="radio" name="avatarimage" value="Green"></td>
	<td class="row2" align="center"><input type="radio" name="avatarimage" value="Pink"></td>
	<td class="row2" align="center"><input type="radio" name="avatarimage" value="Purple"></td>
</tr>
<tr>
	<td class="row1" align="center"><img src="{AVATAR_TEMPLATE_PATH}/red.gif" width="80" height="80" alt="" title="" /></td>
	<td class="row1" align="center"><img src="{AVATAR_TEMPLATE_PATH}/sblue.gif" width="80" height="80" alt="" title="" /></td>
	<td class="row1" align="center"><img src="{AVATAR_TEMPLATE_PATH}/aphrodite.gif" width="80" height="80" alt="" title="" /></td>
	<td class="row1" align="center"><img src="{AVATAR_TEMPLATE_PATH}/opera.gif" width="80" height="80" alt="" title="" /></td>
	<td class="row1" align="center"><img src="{AVATAR_TEMPLATE_PATH}/firefox.gif" width="80" height="80" alt="" title="" /></td>
</tr>
<tr>
	<td class="row2" align="center"><input type="radio" name="avatarimage" value="Red"></td>
	<td class="row2" align="center"><input type="radio" name="avatarimage" value="SteelBlue"></td>
	<td class="row2" align="center"><input type="radio" name="avatarimage" value="Aphrodite"></td>
	<td class="row2" align="center"><input type="radio" name="avatarimage" value="Opera"></td>
	<td class="row2" align="center"><input type="radio" name="avatarimage" value="Firefox"></td>
</tr>
<tr>
	<th class="thSides" colspan="5">{L_RANDOM}</th>
</tr>
<tr>
	<td class="row1" colspan="5" align="center"><input type="radio" name="avatarimage" value="Random" checked="checked"> {L_YES}</td>
</tr>
<tr>
	<th colspan="5">{L_YOUR_AVATAR} ({S_IMAGE_NAME} - {S_IMAGE_TEXT})</th>
</tr>
<tr>
	<td colspan="4" class="row1" align="center"><span class="gen">{L_AVATAR_TEXT}</span>&nbsp;&nbsp;<input class="post" type="text" name="avatartext" value="{S_IMAGE_TEXT}" size="32" maxlength="50" />&nbsp;<input type="submit" name="avatargenerator" value="{L_PREVIEW_AVATAR}" class="mainoption" /></td>
	<td class="row2" align="center"><img src="{S_FILENAME}?filename={AVATAR_FILENAME}&amp;imagename={S_IMAGE_NAME}&imagetext={S_IMAGE_TEXT}"><br /><br /></td>
</tr>
<tr> 
	<td class="catBottom" colspan="5" align="center">
	<!-- BEGIN switch_user_logged_in -->
	{S_HIDDEN_FIELDS}<input type="submit" name="submitgenava" value="{L_SELECT_AVATAR}" class="mainoption" />&nbsp;&nbsp;<input type="submit" name="cancelavatar" value="{L_RETURN_PROFILE}" class="liteoption" />
	<!-- END switch_user_logged_in -->
	</td>
</tr>
</form></table>

	</td>
</tr>
</table>
<div align="center" class="copyright">Avatar Generator v2.0.2 &copy 2005, {COPYRIGHT_YEAR} <a href="http://www.petesplace-online.co.uk" target="_blank" class="copyright">Pete's Place</a><br />Original concept & v1.x &copy; <a href="http://www.winsrev.com" target="_blank" class="copyright">WinSrev</a> & <a href="http://www.loyalistbears.co.uk" target="_blank" class="copyright">Just|Say|Media</a><br />Avatar Template Images &copy; <a href="http://www.phpbbstyles.com" target="_blank" class="copyright">phpBBStyles</a></div>
