{META} 
<table width="100%" cellspacing="2" cellpadding="2" align="center">
  <tr> 
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></td>
  </tr>
</table>

<table width="100%" cellpadding="4" cellspacing="1" class="forumline">
  <tr> 
	<th width="25%" class="thCornerL">&nbsp;{L_USERNAME}&nbsp;</th>
 	<th width="20%" class="thTop">&nbsp;{L_STARTED}&nbsp;</th>
	<th width="20%" class="thTop">&nbsp;{L_LAST_UPDATE}&nbsp;</th>
	<th width="35%" class="thCornerR">&nbsp;{L_FORUM_LOCATION}&nbsp;</th>
  </tr>
  <tr> 
	<td class="catSides" colspan="4"><span class="gen"><b>{TOTAL_REGISTERED_USERS_ONLINE}</b></span></td>
  </tr>
  <!-- BEGIN reg_user_row -->
  <tr> 
	<td height="28" class="{reg_user_row.ROW_CLASS}">&nbsp;<span class="gen"><a href="{reg_user_row.U_USER_PROFILE}" class="gen" title="{reg_user_row.USER_IP}">{reg_user_row.USERNAME}</a></span>&nbsp;</td>
 	<td align="center" nowrap="nowrap" class="{reg_user_row.ROW_CLASS}">&nbsp;<span class="gen">{reg_user_row.STARTED}</span>&nbsp;</td>
	<td align="center" nowrap="nowrap" class="{reg_user_row.ROW_CLASS}">&nbsp;<span class="gen">{reg_user_row.LASTUPDATE}</span>&nbsp;</td>
	<td class="{reg_user_row.ROW_CLASS}">&nbsp;<span class="gen"><a href="{reg_user_row.U_FORUM_LOCATION}" class="gen">{reg_user_row.FORUM_LOCATION}</a></span>&nbsp;</td>
  </tr>
  <!-- END reg_user_row -->
  <tr> 
	<td class="catSides" colspan="4"><span class="gen"><b>{TOTAL_GUEST_USERS_ONLINE}</b></span></td>
  </tr>
  <!-- BEGIN guest_user_row -->
  <tr> 
	<td height="28" class="{guest_user_row.ROW_CLASS}">&nbsp;<span class="gen">{guest_user_row.USERNAME}</span>&nbsp;</td>
 	<td align="center" nowrap="nowrap" class="{guest_user_row.ROW_CLASS}">&nbsp;<span class="gen">{guest_user_row.STARTED}</span>&nbsp;</td>
	<td align="center" nowrap="nowrap" class="{guest_user_row.ROW_CLASS}">&nbsp;<span class="gen">{guest_user_row.LASTUPDATE}</span>&nbsp;</td>
	<td class="{guest_user_row.ROW_CLASS}">&nbsp;<span class="gen"><a href="{guest_user_row.U_FORUM_LOCATION}" class="gen">{guest_user_row.FORUM_LOCATION}</a></span>&nbsp;</td>
  </tr>
  <!-- END guest_user_row -->
  <tr>
	<td colspan="4" class="row1"><b class="gensmall">{L_LEGEND}: {L_WHOSONLINE_ADMIN}, {L_WHOSONLINE_SUPERMOD}, {L_WHOSONLINE_MOD}{L_WHOSONLINE_GAMES}{GROUP_LEGEND}</b></td>
  </tr>
</table>

<table width="100%" cellspacing="2" align="center" cellpadding="2">
  <tr> 
	<td valign="top" class="gensmall">{L_ONLINE_EXPLAIN}</td>
  </tr>
</table>
<br />
<table width="100%" cellspacing="2" align="center">
  <tr> 
	<td align="right">{JUMPBOX}</td>
  </tr>
</table>

