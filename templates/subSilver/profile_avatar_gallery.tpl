
<table width="100%" cellspacing="2" cellpadding="2" align="center">
  <tr> 
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></td>
  </tr>
</table>

{CPL_MENU_OUTPUT}

<table cellpadding="4" cellspacing="1" width="100%" class="forumline"><form action="{S_PROFILE_ACTION}" method="post">
<tr> 
	<th class="thHead" colspan="{S_COLSPAN}">{L_AVATAR_GALLERY}</th>
</tr>
<tr> 
	<td class="catBottom" align="center" colspan="6"><span class="genmed">{L_CATEGORY}:&nbsp;{S_CATEGORY_SELECT}&nbsp;<input type="submit" class="liteoption" value="{L_GO}" name="avatargallery" /></span></td>
</tr>
<!-- BEGIN avatar_row -->
<tr> 
	<!-- BEGIN avatar_column -->
	<td class="row1" align="center"><img src="{avatar_row.avatar_column.AVATAR_IMAGE}" alt="{avatar_row.avatar_column.AVATAR_NAME}" title="{avatar_row.avatar_column.AVATAR_NAME}" /></td>
	<!-- END avatar_column -->
</tr>
<tr>
	<!-- BEGIN avatar_option_column -->
	<td class="row2" align="center"><input type="radio" name="avatarselect" value="{avatar_row.avatar_option_column.S_OPTIONS_AVATAR}" /></td>
	<!-- END avatar_option_column -->
</tr>
<!-- END avatar_row -->
<tr> 
	<td class="catBottom" colspan="{S_COLSPAN}" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submitavatar" value="{L_SELECT_AVATAR}" class="mainoption" />&nbsp;&nbsp;<input type="submit" name="cancelavatar" value="{L_RETURN_PROFILE}" class="liteoption" /> </td>
</tr>
</table>
<table width="100%" cellspacing="2" cellpadding="2">
    <tr>
      <td class="nav">{PAGE_NUMBER}</td>
      <td align="right" class="nav">{PAGINATION}</td>
    </tr>
  </table></form>
	</td>
  </tr>
</table>
<br />
<div align="center" class="copyright">Gallery Page Numbers 1.0.2 &copy; 2004, {COPYRIGHT_YEAR} <a href="http://mods.mosymuis.nl" target="_blank" class="copyright">mosymuis</a></div>

