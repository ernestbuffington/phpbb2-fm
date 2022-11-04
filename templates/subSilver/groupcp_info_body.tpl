<table width="100%" cellspacing="2" cellpadding="2" align="center">
<tr>
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> <a href="{U_GROUP_CP}" class="nav">{L_USERGROUPS}</a></td>
	<td align="right" class="nav">{PAGINATION}</td>
</tr>
</table>

<table class="forumline" width="100%" cellspacing="1" cellpadding="4"><form action="{S_GROUPCP_ACTION}" method="post">
<tr> 
	<th class="thHead" colspan="7">{L_GROUP_INFORMATION}</th>
</tr>
<tr> 
	<td class="row1" width="20%"><span class="gen">{L_GROUP_NAME}:</span></td>
	<td class="row2"><b class="gen">{GROUP_NAME}</b></td>
</tr>
<tr> 
	<td class="row1"><span class="gen">{L_GROUP_DESC}:</span></td>
	<td class="row2"><span class="gen">{GROUP_DESC}</span></td>
</tr>
<!-- BEGIN group_member_count -->
<tr>
	<td class="row1"><span class="gen">{L_GROUP_MEMBERS}:</span></td>
	<td class="row2"><span class="gen">{GROUP_COUNT}</span></td>
</tr>
<!-- END group_member_count -->
<tr> 
	<td class="row1"><span class="gen">{L_GROUP_MEMBERSHIP}:</span></td>
	<td class="row2"><span class="gen">{GROUP_DETAILS} &nbsp;&nbsp;
	<!-- BEGIN switch_subscribe_group_input -->
	{L_JOIN_GROUP}
	<!-- END switch_subscribe_group_input -->
	<!-- BEGIN switch_unsubscribe_group_input -->
	{L_UNSUBSCRIBE_GROUP}
	<!-- END switch_unsubscribe_group_input -->
	<!-- BEGIN digest_confirm_input -->
	<input class="mainoption" type="submit" name="digest_confirm" value="{L_DIGEST_CONFIRM}" />
	<!-- END digest_confirm_input -->
	</span></td>
</tr>
<!-- BEGIN switch_mod_option -->
<tr> 
	<td class="row1"><span class="gen">{L_GROUP_TYPE}:</span></td>
	<td class="row2"><span class="gen"><input type="radio" name="group_type" value="{S_GROUP_OPEN_TYPE}" {S_GROUP_OPEN_CHECKED} /> {L_GROUP_OPEN} &nbsp;&nbsp;<input type="radio" name="group_type" value="{S_GROUP_CLOSED_TYPE}" {S_GROUP_CLOSED_CHECKED} /> {L_GROUP_CLOSED}&nbsp;&nbsp;<input type="radio" name="group_type" value="{S_GROUP_HIDDEN_TYPE}" {S_GROUP_HIDDEN_CHECKED} /> {L_GROUP_HIDDEN}&nbsp;&nbsp;<input type="radio" name="group_type" value="{S_GROUP_PAYMENT_TYPE}" {S_GROUP_PAYMENT_CHECKED} /> {L_GROUP_PAYMENT}&nbsp;&nbsp;<input class="mainoption" type="submit" name="groupstatus" value="{L_UPDATE}" /></span></td>
</tr>
<!-- END switch_mod_option -->
<!-- BEGIN switch_digest_option -->
<tr>
	<td class="row1"><span class="gen">{L_GROUP_DIGEST}:</span></td>
	<td class="row2"><input class="mainoption" type="submit" name="digest_options" value="{L_DIGEST_OPTIONS}" /></td>
</tr>
<!-- END switch_digest_option -->
</table>

{S_HIDDEN_FIELDS}

</form>

<table width="100%" cellpadding="4" cellspacing="1" class="forumline"><form action="{S_GROUPCP_ACTION}" method="post" name="post">
<tr> 
	<th class="thCornerL">{L_PM}</th>
	<th class="thTop">{L_USERNAME}</th>
	<th class="thTop">{L_POSTS}</th>
	<th class="thTop">{L_FROM}</th>
	<th class="thTop">{L_ONLINE_STATUS}</th>
	<th class="thTop">{L_EMAIL}</th>
	<th class="thTop">{L_WEBSITE}</th>
	<th class="thCornerR">{L_SELECT}</th>
</tr>
<tr> 
	<td class="catSides" colspan="9"><span class="cattitle">{L_GROUP_OWNER}</span></td>
</tr>
<tr> 
	<td class="row1" align="center">{MOD_PM_IMG}</td>
	<td class="row1" align="center"><span class="gen"><a href="{U_MOD_VIEWPROFILE}" class="gen">{MOD_USERNAME}</a></span></td>
	<td class="row1" align="center" valign="middle"><span class="gen">{MOD_POSTS}</span></td>
	<td class="row1" align="center" valign="middle"><span class="gen">{MOD_FROM}</span></td>
	<td class="row1" align="center">{MOD_ONLINE_STATUS_IMG}</td>
	<td class="row1" align="center">{MOD_EMAIL_IMG}</td>
	<td class="row1" align="center">{MOD_WWW_IMG}</td>
	<td class="row1" align="center"> &nbsp;</td>
</tr>
<!-- BEGIN member_row -->
<!-- BEGIN member_type -->
<tr>
	<td class="catSides" colspan="8"><span class="cattitle">{member_row.member_type.L_TYPE}</span></td>
</tr>
<!-- END member_type -->
<tr> 
	<td class="{member_row.ROW_CLASS}" align="center">{member_row.PM_IMG}</td>
	<td class="{member_row.ROW_CLASS}" align="center"><span class="gen"><a href="{member_row.U_VIEWPROFILE}" class="gen">{member_row.USERNAME}</a></span></td>
	<td class="{member_row.ROW_CLASS}" align="center"><span class="gen">{member_row.POSTS}</span></td>
	<td class="{member_row.ROW_CLASS}" align="center"><span class="gen"> {member_row.FROM}</span></td>
	<td class="{member_row.ROW_CLASS}" align="center">{member_row.ONLINE_STATUS_IMG}</td>
	<td class="{member_row.ROW_CLASS}" align="center">{member_row.EMAIL_IMG}</td>
	<td class="{member_row.ROW_CLASS}" align="center">{member_row.WWW_IMG}</td>
	<td class="{member_row.ROW_CLASS}" align="center"> 
	<!-- BEGIN switch_mod_option -->
	<input type="checkbox" name="members[]" value="{member_row.USER_ID}" /> 
	<!-- END switch_mod_option -->
	</td>
</tr>
<!-- END member_row -->
<!-- BEGIN switch_no_members -->
<tr>
	<td class="catSides" colspan="8"><span class="cattitle">{L_GROUP_MEMBERS}</span></td>
</tr>
<tr> 
	<td class="row1" colspan="8" align="center"><span class="gen">{L_NO_MEMBERS}</span></td>
</tr>
<!-- END switch_no_members -->
<!-- BEGIN switch_hidden_group -->
<tr> 
	<td class="row1" colspan="8" align="center"><span class="gen">{L_HIDDEN_MEMBERS}</span></td>
</tr>
<!-- END switch_hidden_group -->
<!-- BEGIN switch_mod_option -->
<tr>
	<td class="catBottom" colspan="9" align="right"><input type="submit" name="remove" value="{L_REMOVE_SELECTED}" class="mainoption" />
			<!-- BEGIN switch_owner_option -->
			<input type="submit" name="grant_ungrant" value="{L_GRANT_UNGRANT_SELECTED}" class="liteoption" />
			<!-- END switch_owner_option -->
</td>
</tr>
<!-- END switch_mod_option -->
</table>

<table width="100%" cellspacing="2" align="center" cellpadding="2">
<tr>
	<td align="left" valign="top" class="nav">
	<!-- BEGIN switch_mod_option -->
	<span class="genmed"><input type="text" class="post" name="username" {AJAXED_USER_LIST} maxlength="50" size="20" /> <input type="submit" name="add" value="{L_ADD_MEMBER}" class="mainoption" /> <input type="submit" name="usersubmit" value="{L_FIND_USERNAME}" class="liteoption" onClick="window.open('{U_SEARCH_USER}', '_phpbbsearch', 'HEIGHT=250,resizable=yes,WIDTH=400');return false;" />{AJAXED_USER_LIST_BOX}</span><br /><br />
	<!-- END switch_mod_option -->
	{PAGE_NUMBER}</td>
	<td align="right" valign="top" class="nav">{PAGINATION}</td>
	</tr>
</table>

{PENDING_USER_BOX}

{S_HIDDEN_FIELDS}</form>

<table width="100%" cellspacing="2" align="center">
<tr> 
	<td valign="top" align="right">{JUMPBOX}</td>
</tr>
</table>
