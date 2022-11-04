{BAN_MENU}
{USER_MENU}
{MOD_CP_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<script type="text/javascript">
<!--

function mark(status)
{
	for (i = 0; i < document.unbanform.length; i++)
	{
		document.unbanform.elements[i].checked = status;
	}
}

function checkmark()
{
	document.unbanform.markall.checked = false;

	var allchecked = true;
	var checkbox = false;
	for (i = 0; i < document.unbanform.length; i++)
	{
		if( document.unbanform.elements[i].type == 'checkbox' && document.unbanform.elements[i].name != 'markall' )
		{
			checkbox = true;
			
			if( !document.unbanform.elements[i].checked )
			{
				allchecked = false;
			}
		}
	}

	if( checkbox && allchecked )
	{
		document.unbanform.markall.checked = true;
	}
}
//-->
</script>

<h1>{L_BANS}</h1>

<p>{L_BANS_EXPLAIN}</p>

<table align="center" width="100%" cellpadding="2" cellspacing="2"><form name="modechange1" action="{S_MODE_ACTION}" method="post">
  <tr>
	<td colspan="5"><span class="genmed">{L_BAN_MODE}: 
	<select name="mode" onChange="document.forms['modechange1'].submit();">
		<option value="user"{S_MODE_USER_SELECTED}>{L_USERNAME}</option>
		<option value="email"{S_MODE_EMAIL_SELECTED}>{L_EMAIL}</option>
		<option value="ip"{S_MODE_IP_SELECTED}>{L_IP}</option>
	</select><noscript> <input type="submit" value="{L_GO}" /></noscript></span></td>
	{S_MODE_HIDDEN_FIELDS}
  </tr>
</form></table>
<table align="center" width="100%" cellpadding="4" cellspacing="1" class="forumline"><form name="unbanform" action="{S_FORM_ACTION}" method="post">
	<!-- BEGIN switch_mode_email -->
	  <tr>
		<th class="thCornerL" colspan="4"><a href="{U_EMAIL_ORDER}" class="thsort">{L_EMAIL}{EMAIL_ORDER_IMG}</a></th>
		<th class="thCornerR" align="center" width="5%"><input type="checkbox" name="markall" value="1" onClick="mark(this.checked)" /></th>
	  </tr>
	<!-- END switch_mode_email -->
	<!-- BEGIN switch_mode_ip -->
	  <tr>
		<th class="thCornerL" colspan="4"><a href="{U_IP_ORDER}" class="thsort">{L_IP}{IP_ORDER_IMG}</a></th>
		<th class="thCornerR" align="center" width="5%"><input type="checkbox" name="markall" value="1" onClick="mark(this.checked)" /></th>
	  </tr>
	<!-- END switch_mode_ip -->
	<!-- BEGIN switch_mode_user -->
	  <tr>
		<th class="thCornerL"><a href="{U_USERNAME_ORDER}" class="thsort">{L_USERNAME}{USERNAME_ORDER_IMG}</a></th>
		<th class="thTop"><a href="{U_EMAIL_ORDER}" class="thsort">{L_EMAIL}{EMAIL_ORDER_IMG}</a></th>
		<th class="thTop"><a href="{U_POSTS_ORDER}" class="thsort">{L_POSTS}{POSTS_ORDER_IMG}</a></th>
		<th class="thTop"><a href="{U_LAST_VISIT_ORDER}" class="thsort">{L_LAST_VISIT}{LAST_VISIT_ORDER_IMG}</a></th>
		<th class="thCornerR" align="center" width="5%"><input type="checkbox" name="markall" value="1" onClick="mark(this.checked)" /></th>
	  </tr>
	<!-- END switch_mode_user -->
	<!-- BEGIN ban_email_row -->
	  <tr>
		<td class="{ban_email_row.ROW_CLASS}" colspan="4">{ban_email_row.EMAIL}</td>
		<td class="{ban_email_row.ROW_CLASS}" align="center"><input type="checkbox" name="unban[]" value="{ban_email_row.BAN_ID}" onClick="checkmark()" {ban_email_row.S_UNBAN_CHECKED} /></td>
	  </tr>
	<!-- END ban_email_row -->
	<!-- BEGIN ban_ip_row -->
	  <tr>
		<td class="{ban_ip_row.ROW_CLASS}" colspan="4">{ban_ip_row.IP}</td>
		<td class="{ban_ip_row.ROW_CLASS}" align="center"><input type="checkbox" name="unban[]" value="{ban_ip_row.BAN_ID}" onClick="checkmark()" {ban_ip_row.S_UNBAN_CHECKED} /></td>
	  </tr>
	<!-- END ban_ip_row -->
	<!-- BEGIN ban_user_row -->
	  <tr>
		<td class="{ban_user_row.ROW_CLASS}"><a href="{ban_user_row.U_USER_PROFILE}">{ban_user_row.USERNAME}</a></td>
		<td class="{ban_user_row.ROW_CLASS}">{ban_user_row.EMAIL}</td>
		<td class="{ban_user_row.ROW_CLASS}" align="center">{ban_user_row.POSTS}</td>
		<td class="{ban_user_row.ROW_CLASS}" align="center">{ban_user_row.LAST_VISIT}</td>
		<td class="{ban_user_row.ROW_CLASS}" align="center"><input type="checkbox" name="unban[]" value="{ban_user_row.BAN_ID}" onClick="checkmark()" {ban_user_row.S_UNBAN_CHECKED} /></td>
	  </tr>
	<!-- END ban_user_row -->
	<!-- BEGIN ban_none_row -->
	  <tr>
		<td class="row1" height="30" align="center" colspan="5">{ban_none_row.MESSAGE}</td>
	  </tr>
	<!-- END ban_none_row -->
	  <tr>
		<td colspan="5" align="right">
			<!-- BEGIN switch_prev -->
			<input type="submit" name="prev_submit" value="&laquo; {L_PREV}" class="mainoption" />
			<!-- END switch_prev -->
			<!-- BEGIN switch_export -->
			<input type="submit" name="export_submit" value="{L_EXPORT}" class="mainoption" />&nbsp;<input type="submit" name="export_all_submit" value="{L_EXPORT_ALL}" class="mainoption" />
			<!-- END switch_export -->
			<input type="submit" name="unban_submit" value="{L_UNBAN}" class="mainoption" />&nbsp;<input type="submit" name="unban_all_submit" value="{L_UNBAN_ALL}" class="mainoption" />
			<!-- BEGIN switch_next -->
			<input type="submit" name="next_submit" value="{L_NEXT} &raquo;" class="mainoption" /></td>
			<!-- END switch_next -->
		</td>
	  </tr>
</table>
<table align="center" width="100%" cellpadding="2" cellspacing="2">
  <tr>
	<td nowrap="nowrap" class="nav">{PAGE}</td>
	<td align="right" class="nav">{PAGINATION}</td>
  </tr>
  {S_HIDDEN_FIELDS}
</form></table>
<br />

<script type="text/javascript">
<!--

checkmark();

//-->
</script>
<table align="center" width="100%" cellpadding="4" cellspacing="1" class="forumline"><form action="{S_ADD_BAN_ACTION}" method="POST">
  <tr>
	<th class="thHead" colspan="2">{L_ADD_BAN}</th>
  </tr>
  <!-- BEGIN switch_mode_ip -->
  <tr>
	<td class="row1" width="50%"><span class="gensmall">{L_ADD_BAN_EXPLAIN}</span></td>
	<td class="row2"><textarea class="post" name="ban_ip" rows="5" cols="40" style="width: 100%;"></textarea></td>
  </tr>
  <!-- END switch_mode_ip -->
  <!-- BEGIN switch_mode_email -->
  <tr>
	<td class="row1" width="50%"><span class="gensmall">{L_ADD_BAN_EXPLAIN}</span></td>
	<td class="row2"><textarea class="post" name="ban_email" rows="5" cols="40" style="width: 100%;"></textarea></td>
  </tr>
  <!-- END switch_mode_email -->
  <!-- BEGIN switch_mode_user -->
  <tr>
	<td class="row1" width="50%"><span class="gensmall">{L_ADD_BAN_EXPLAIN}</span></td>
	<td class="row2">{S_USER_SELECT}</td>
  </tr>
  <!-- END switch_mode_user -->
  <tr>
	<td class="catBottom" colspan="2" align="center">{S_ADD_BAN_HIDDEN_FIELDS}<input type="submit" value="{L_SUBMIT}" class="mainoption" /></td>
  </tr>
</form></table>
