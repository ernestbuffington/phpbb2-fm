{MOD_CP_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_FORUM_STATS}</h1>

<p>{L_FORUM_STATS_EXPLAIN}</p>

<table cellpadding="4" cellspacing="1" width="100%" align="center">
  <tr> 
	<th width="25%" class="thCornerL">&nbsp;{L_STATISTIC}&nbsp;</th>
	<th width="25%" class="thTop">&nbsp;{L_VALUE}&nbsp;</th>
	<th width="25%" class="thTop">&nbsp;{L_STATISTIC}&nbsp;</th>
	<th width="25%" class="thCornerR">&nbsp;{L_VALUE}&nbsp;</th>
  </tr>
  <tr> 
	<td class="row2">{L_BOARD_STARTED}:</td>
	<td class="row1"><b>{START_DATE}</b></td>
	<td class="row2">{L_TABLES}:</td>
	<td class="row1"><b>{TABLECOUNT}</b></td>
  </tr>
  <tr> 
	<td class="row2">{L_PHPBB_VERSION}:</td>
	<td class="row1"><b>{PHPBB_VERSION}</b></td>
	<td class="row2">{L_DB_SIZE}:</td>
	<td class="row1"><b>{DB_SIZE}</b></td>
  </tr>
  <tr> 
	<td class="row2">{L_PHPBBFM_VERSION}:</td>
	<td class="row1"><b>{PHPBBFM_VERSION}</b></td>
	<td class="row2">{L_SIZE_POSTS_TABLES}:</td>
	<td class="row1"><b>{SIZE_POSTS_TABLES}</b></td>
  </tr>
  <tr> 
	<td class="row2">{L_AVATAR_DIR_SIZE}:</td>
	<td class="row1"><b>{AVATAR_DIR_SIZE}</b></td>
	<td class="row2">{L_SIZE_SEARCH_TABLES}:</td>
	<td class="row1"><b>{SIZE_SEARCH_TABLES}</b></td>
  </tr>
  <tr> 
	<td class="row2">{L_ATTACHMENT_DIR_SIZE}:</td>
	<td class="row1"><b>{ATTACHMENT_DIR_SIZE}</b></td>
	<td class="row2">{L_SIZE_USERS_TABLES}:</td>
	<td class="row1"><b>{SIZE_USERS_TABLES}</b></td>
  </tr>
  <tr> 
	<td class="row2"></td>
	<td class="row1"><b></b></td>
	<td class="row2">{L_SIZE_PVT_TABLES}:</td>
	<td class="row1"><b>{SIZE_PVT_TABLES}</b></td>
  </tr>
  <tr> 
	<td colspan="4" height="1" class="row3"><img src="../images/spacer.gif" width="1" height="1" alt="" title="" /></td>
  </tr>
  <tr> 
	<td class="row2">{L_NUMBER_POSTS}:</td>
	<td class="row1"><b>{NUMBER_OF_POSTS}</b></td>
	<td class="row2">{L_POSTS_PER_DAY}:</td>
	<td class="row1"><b>{POSTS_PER_DAY}</b></td>
  </tr>
  <tr> 
	<td class="row2">{L_NUMBER_TOPICS}:</td>
	<td class="row1"><b>{NUMBER_OF_TOPICS}</b></td>
	<td class="row2">{L_TOPICS_PER_DAY}:</td>
	<td class="row1"><b>{TOPICS_PER_DAY}</b></td>
  </tr>
	<tr>
	<td class="row2">{L_NUMBER_USERS}:</td>
	<td class="row1"><b>{NUMBER_OF_USERS}</b></td>
	<td class="row2">{L_USERS_PER_DAY}:</td>
	<td class="row1"><b>{USERS_PER_DAY}</b></td>
  </tr>
  <tr> 
	<td class="row2">{L_NUMBER_FEMALE} / {L_NUMBER_MALE}:</td>
	<td class="row1"><b>{TOTAL_FEMALE} / {TOTAL_MALE}</b></td>
	<td class="row2">{L_GENDER_RATIO}:</td>
	<td class="row1"><b>{GENDER_RATIO}</b></td>
  </tr>
  <tr> 
	<td class="row2">{L_NUMBER_PVT}:</td>
	<td class="row1"><b>{NUMBER_OF_PVT}</b></td>
	<td class="row2">{L_PVT_PER_DAY}:</td>
	<td class="row1"><b>{PVT_PER_DAY}</b></td>
  </tr>
  <tr> 
	<td class="row2">{L_MAX_PVT_PER_USER}:</td>
	<td class="row1"><b>{MAX_PVT_PER_USER}</b></td>
	<td class="row2">{L_PVT_PER_USER}:</td>
	<td class="row1"><b>{PVT_PER_USER}</b></td>
  </tr>
  <tr> 
	<td class="row2">{L_NUMBER_DATA_DISABLE}:</td>
	<td class="row1"><b>{NUMBER_DATA_DISABLE}</b></td>
	<td class="row2">{L_PERC_PVT}:</td>
	<td class="row1"><b>{PERC_PVT}</b></td>
	</tr>
	<tr>
	<td class="row2">{L_NUMBER_ATTACHMENTS}:</td>
	<td class="row1"><b>{NUMBER_OF_ATTACHMENTS}</b></td>
	<td class="row2">{L_ATTACHMENTS_PER_DAY}:</td>
	<td class="row1"><b>{ATTACHMENTS_PER_DAY}</b></td>
	</tr>
</table>
<br />

<h1>{L_WHO_IS_ONLINE}</h1>

<table cellpadding="4" cellspacing="1" width="100%" align="center"><form method="post" name="post" action="{S_USER_ACTION}"><input type="hidden" name="mode" value="lookup" />
<tr>
	<td class="catHead" align="center" colspan="5"><span class="gensmall">{L_USERNAME}:</span> <input type="text" class="post" name="username"{AJAXED_USER_LIST} maxlength="99" size="20" /> <input type="submit" value="{L_LOOK_UP}" class="liteoption" />{AJAXED_USER_LIST_BOX}</td>
	</form>
</tr>
<tr>
	<th width="20%" class="thCornerL">&nbsp;{L_USERNAME}&nbsp;</th>
	<th width="20%" class="thTop">&nbsp;{L_STARTED}&nbsp;</th>
	<th width="20%" class="thTop">&nbsp;{L_LAST_UPDATE}&nbsp;</th>
	<th width="20%" class="thTop">&nbsp;{L_FORUM_LOCATION}&nbsp;</th>
	<th width="20%" class="thCornerR">&nbsp;{L_IP_ADDRESS}&nbsp;</th>
</tr>
<!-- BEGIN reg_user_row -->
<tr>
	<td class="{reg_user_row.ROW_CLASS}">&nbsp;<a href="{reg_user_row.U_USER_PROFILE}" class="genmed">{reg_user_row.USERNAME}</a>&nbsp;</td>
	<td align="center" nowrap="nowrap" class="{reg_user_row.ROW_CLASS}">&nbsp;<span class="genmed">{reg_user_row.STARTED}</span>&nbsp;</td>
	<td align="center" nowrap="nowrap" class="{reg_user_row.ROW_CLASS}">&nbsp;<span class="genmed">{reg_user_row.LASTUPDATE}</span>&nbsp;</td>
	<td class="{reg_user_row.ROW_CLASS}">&nbsp;<a href="{reg_user_row.U_FORUM_LOCATION}" class="genmed">{reg_user_row.FORUM_LOCATION}</a>&nbsp;</td>
	<td class="{reg_user_row.ROW_CLASS}">&nbsp;{reg_user_row.U_WHOIS_IP}&nbsp;</td>
</tr>
<!-- END reg_user_row -->
<tr>
	<td colspan="5" height="1" class="row3"><img src="../images/spacer.gif" width="1" height="1" alt="" title="" /></td>
</tr>
<!-- BEGIN guest_user_row -->
<tr>
	<td class="{guest_user_row.ROW_CLASS}">&nbsp;<span class="genmed">{guest_user_row.USERNAME}</span>&nbsp;</td>
	<td align="center" nowrap="nowrap" class="{guest_user_row.ROW_CLASS}">&nbsp;<span class="genmed">{guest_user_row.STARTED}</span>&nbsp;</td>
	<td align="center" nowrap="nowrap" class="{guest_user_row.ROW_CLASS}">&nbsp;<span class="genmed">{guest_user_row.LASTUPDATE}</span>&nbsp;</td>
	<td class="{guest_user_row.ROW_CLASS}">&nbsp;<a href="{guest_user_row.U_FORUM_LOCATION}" class="genmed">{guest_user_row.FORUM_LOCATION}</a>&nbsp;</td>
	<td class="{guest_user_row.ROW_CLASS}" nowrap="nowrap">&nbsp;{guest_user_row.U_WHOIS_IP} &nbsp;<span class="gensmall">{guest_user_row.U_GUEST_IP_LOOKUP}</span>&nbsp;</td>
</tr>
<!-- END guest_user_row -->
</table>
<br />