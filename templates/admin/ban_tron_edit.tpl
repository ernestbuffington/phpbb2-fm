{BAN_MENU}
{USER_MENU}
{MOD_CP_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_BM_TITLE}</h1> 

<p>{L_BM_EXPLAIN}</p>

<table cellspacing="1" cellpadding="4" width="100%" align="center" class="forumline"><form action="{S_BANCENTER_ACTION}" name="post" method="post">
  <tr>
	<th class="thHead" colspan="2">{L_ADD_A_NEW_BAN}</th>
  </tr>
<!-- BEGIN username_row -->
  <tr>
	<td class="row1" width="50%"><b>{username_row.L_USERNAME}:</b></td>
	<td class="row2"><input class="post" type="text" class="post" name="username"{AJAXED_USER_LIST} value="{username_row.USERNAME}" maxlength="50" size="20" /><input type="hidden" name="mode" value="edit" /> <input type="submit" name="usersubmit" value="{username_row.L_FIND_USERNAME}" class="liteoption" onClick="window.open('{username_row.U_SEARCH_USER}', '_phpbbsearch', 'HEIGHT=250,resizable=yes,WIDTH=400');return false;" />{AJAXED_USER_LIST_BOX}</td>
  </tr>
<!-- END username_row -->
<!-- BEGIN ip_row -->
  <tr>
	<td class="row1"><b>{ip_row.L_IP_OR_HOSTNAME}:</b><br /><span class="gensmall">{ip_row.L_BAN_IP_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" name="ban_ip" value="{ip_row.BAN_IP}" size="35" /></td>
  </tr>
<!-- END ip_row -->
<!-- BEGIN email_row -->
  <tr>
	<td class="row1"><b>{email_row.L_EMAIL_ADDRESS}:</b><br /><span class="gensmall">{email_row.L_BAN_EMAIL_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" name="ban_email" value="{email_row.BAN_EMAIL}" size="35" /></td>
  </tr>
<!-- END email_row -->
  <tr>
	<td class="row1"><b>{L_PRIVATE_REASON}:</b><br /><span class="gensmall">{L_PRIVATE_REASON_EXPLAIN}</span></td>
	<td class="row2"><textarea name="ban_priv_reason" rows="5" cols="30" class="post">{BAN_PRIV_REASON}</textarea></td>
  </tr>
  <tr>
	<td class="row1"><b>{L_PUBLIC_REASON}:</b><br /><span class="gensmall">{L_PUBLIC_REASON_EXPLAIN}</span></td>
	<td class="row2">
	<table cellpadding="1" cellspacing="0">
  	  <tr>
		<td><input type="radio" name="ban_pub_reason_mode" value="0"{BAN_PUB_REASON_MODE_0} /> {L_GENERIC_REASON}</td>
 	  </tr>
 	  <tr>
		<td><input type="radio" name="ban_pub_reason_mode" value="1"{BAN_PUB_REASON_MODE_1} /> {L_MIRROR_PRIVATE_REASON}</td>
	  </tr>
	  <tr>
	 	<td><input type="radio" name="ban_pub_reason_mode" value="2"{BAN_PUB_REASON_MODE_2} /> {L_OTHER}</td>
	  </tr>
	  <tr>
		<td><textarea name="ban_pub_reason"rows="5" cols="30" class="post">{BAN_PUB_REASON}</textarea></td>
 	  </tr>
	</table>				
	</td>
  </tr>
  <tr>
	<td class="row1"><b>{L_EXPIRE_TIME}:</b><br /><span class="gensmall">{L_EXPIRE_TIME_EXPLAIN}</span></td>
	<td class="row2">
	<table cellpadding="1" cellspacing="0">
	  <tr>
		<td colspan="2"><input type="radio" name="ban_expire_time_mode" value="never"{BAN_EXPIRE_TIME_MODE_NEVER} /> {L_NEVER}</td>
	  </tr>
	  <tr>
		<td colspan="2"><input type="radio" name="ban_expire_time_mode" value="relative"{BAN_EXPIRE_TIME_MODE_RELATIVE} /> {L_AFTER_SPECIFIED_LENGTH_OF_TIME}</td>
	  </tr>
   	  <tr>
		<td width="30">&nbsp;</td>
		<td><input type="text" class="post" name="ban_expire_time_relative" maxlength="10" size="4" /> 
		<select name="ban_expire_time_relative_units"> 
			<option value="minutes">{L_MINUTES}</option>
			<option value="hours">{L_HOURS}</option>
			<option value="days">{L_DAYS}</option>
			<option value="weeks">{L_WEEKS}</option>
			<option value="months">{L_MONTHS}</option>
			<option value="years">{L_YEARS}</option>
		</select> 
		</td>
	  </tr>
	  <tr>
		<td colspan="2"><input type="radio" name="ban_expire_time_mode" value="absolute"{BAN_EXPIRE_TIME_MODE_ABSOLUTE} /> {L_AFTER_SPECIFIED_DATE}</td>
	  </tr>
	  <tr>
		<td width="30">&nbsp;</td>
		<td><input class="post" type="text" name="ban_expire_time_absolute_hour" value="{BAN_EXPIRE_TIME_ABSOLUTE_HOUR}" size="2" maxlength="2" style="width: 2em;" />&nbsp;:&nbsp;<input class="post" type="text" name="ban_expire_time_absolute_minute" value="{BAN_EXPIRE_TIME_ABSOLUTE_MINUTE}" size="2" maxlength="2" style="width: 2em;">&nbsp;<input type="radio" name="ban_expire_time_absolute_ampm" value="am"{BAN_EXPIRE_TIME_ABSOLUTE_AM} />{L_AM}&nbsp;<input type="radio" name="ban_expire_time_absolute_ampm" value="pm"{BAN_EXPIRE_TIME_ABSOLUTE_PM} />{L_PM}&nbsp;<input type="radio" name="ban_expire_time_absolute_ampm" value=""{BAN_EXPIRE_TIME_ABSOLUTE_24} />{L_24_HOUR}
		<br /><input class="post" type="text" name="ban_expire_time_absolute_month" value="{BAN_EXPIRE_TIME_ABSOLUTE_MONTH}" size="2" maxlength="2" style="width: 2em;" />&nbsp;/&nbsp;<input class="post" type="text" name="ban_expire_time_absolute_mday" value="{BAN_EXPIRE_TIME_ABSOLUTE_MDAY}" size="2" maxlength="2" style="width: 2em;">&nbsp;/&nbsp;<input class="post" type="text" name="ban_expire_time_absolute_year" value="{BAN_EXPIRE_TIME_ABSOLUTE_YEAR}" size="4" maxlength="4" style="width: 4em"></td>
 	  </tr>
	</table>
	</td>
  </tr>
  <tr>
	<td class="catBottom" align="center" colspan="2">
	<!-- BEGIN ban_id -->
	<input type="hidden" name="ban_id" value="{ban_id.BAN_ID}" />
	<!-- END ban_id -->
	<input type="submit" name="{SUBMIT}" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" name="{RESET}" value="{L_RESET}" class="liteoption" /></td>
  </tr>
</form></table>
