{GROUP_MENU}{PERMS_MENU}{SUBSCRIPTION_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_GROUP_TITLE}</h1>

<p>{L_GROUP_EXPLAIN}</p>
	
<table width="100%" cellpadding="4" cellspacing="1" class="forumline" align="center"><form action="{S_GROUP_ACTION}" method="post" name="post">
<tr> 
	<th class="thHead" colspan="2">{L_GROUP_EDIT_DELETE}</th>
</tr>
<tr> 
	<td class="row1" width="50%"><b>{L_GROUP_NAME}:</b></td>
	<td class="row2"><input class="post" type="text" name="group_name" size="35" maxlength="40" value="{GROUP_NAME}" /></td>
</tr>
<tr> 
	<td class="row1"><b>{L_GROUP_DESCRIPTION}:</b><br /><span class="gensmall">{L_GROUP_DESCRIPTION_EXPLAIN}</span></td>
	<td class="row2"><textarea class="post" name="group_description" wrap="virtual" rows="5" cols="35" />{GROUP_DESCRIPTION}</textarea></td>
</tr>
<tr> 
	<td class="row1"><b>{L_GROUP_MODERATOR}:</b></td>
	<td class="row2"><input class="post" type="text" class="post" name="username" maxlength="35" size="20" value="{GROUP_MODERATOR}" /> &nbsp; <input type="submit" name="usersubmit" value="{L_FIND_USERNAME}" class="liteoption" onClick="window.open('{U_SEARCH_USER}', '_phpbbsearch', 'HEIGHT=250,resizable=yes,WIDTH=400');return false;" /></td>
</tr>
<tr> 
	<td class="row1"><b>{L_GROUP_STATUS}:</b></td>
	<td class="row2">&nbsp;<input type="radio" name="group_type" value="{S_GROUP_OPEN_TYPE}" {S_GROUP_OPEN_CHECKED} /> {L_GROUP_OPEN}&nbsp;&nbsp;<input type="radio" name="group_type" value="{S_GROUP_CLOSED_TYPE}" {S_GROUP_CLOSED_CHECKED} /> {L_GROUP_CLOSED}<br />&nbsp;<input type="radio" name="group_type" value="{S_GROUP_HIDDEN_TYPE}" {S_GROUP_HIDDEN_CHECKED} /> {L_GROUP_HIDDEN}&nbsp;&nbsp;<input type="radio" name="group_type" value="{S_GROUP_PAYMENT_TYPE}" {S_GROUP_PAYMENT_CHECKED} /> {L_GROUP_PAYMENT}</td> 
</tr>
<tr> 
	<td class="row1"><b>{L_GROUP_VALIDATE}:</b><br /><span class="gensmall">{L_GROUP_VALIDATE_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="group_validate" value="1"{GROUP_VALIDATE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="group_validate" value="0"{GROUP_VALIDATE_NO} /> {L_NO}</td> 
</tr>
<tr>
	<td class="row1"><b>{L_GROUP_MEMBERS_COUNT}:</b></td>
	<td class="row2"><input type="radio" name="group_members_count" value="1" {GROUP_MEMBERS_COUNT_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="group_members_count" value="0" {GROUP_MEMBERS_COUNT_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_GROUP_DIGESTS}:</b><br /><span class="gensmall">{L_GROUP_DIGESTS_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="group_digest" value="1" {S_GROUP_DIGEST_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="group_digest" value="0" {S_GROUP_DIGEST_NO} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_GROUP_ALLOW_PM}:</b><br /><span class="gensmall">{L_GROUP_ALLOW_PM_EXPLAIN}</span></td>
	<td class="row2">&nbsp;<input type="radio" name="group_allow_pm" value="{S_GROUP_ALL_ALLOW_PM}" {S_GROUP_ALL_ALLOW_PM_CHECKED} /> {L_GROUP_ALL_ALLOW_PM}&nbsp;&nbsp;<input type="radio" name="group_allow_pm" value="{S_GROUP_REG_ALLOW_PM}" {S_GROUP_REG_ALLOW_PM_CHECKED} /> {L_GROUP_REG_ALLOW_PM}<br />&nbsp;<input type="radio" name="group_allow_pm" value="{S_GROUP_PRIVATE_ALLOW_PM}" {S_GROUP_PRIVATE_ALLOW_PM_CHECKED} /> {L_GROUP_PRIVATE_ALLOW_PM}&nbsp;&nbsp;<input type="radio" name="group_allow_pm" value="{S_GROUP_MOD_ALLOW_PM}" {S_GROUP_MOD_ALLOW_PM_CHECKED} /> {L_GROUP_MOD_ALLOW_PM}&nbsp;&nbsp;<input type="radio" name="group_allow_pm" value="{S_GROUP_ADMIN_ALLOW_PM}" {S_GROUP_ADMIN_ALLOW_PM_CHECKED} /> {L_GROUP_ADMIN_ALLOW_PM}</td> 
</tr>
<!-- BEGIN group_edit -->
<tr> 
	<td class="row1"><b>{L_UPLOAD_QUOTA}:</b></td>
	<td class="row2">{S_SELECT_UPLOAD_QUOTA}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_PM_QUOTA}:</b></td>
	<td class="row2">{S_SELECT_PM_QUOTA}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_DELETE_MODERATOR}</b><br /><span class="gensmall">{L_DELETE_MODERATOR_EXPLAIN}</span></td>
	<td class="row2"><input type="checkbox" name="delete_old_moderator" value="1"> {L_YES}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_GROUP_DELETE}?</b></td>
	<td class="row2"><input type="checkbox" name="group_delete" value="1"> {L_YES}, {L_GROUP_DELETE_CHECK}</td>
</tr>
<!-- END group_edit -->
<tr> 
	<th class="thHead" colspan="2">{L_COLOR_GROUPS}</th>
</tr>
<tr> 
	<td class="row2" colspan="2"><span class="gensmall">{L_COLOR_GROUPS_EXPLAIN}</span></td>
</tr>
<!-- BEGIN styles_block -->
<tr> 
	<td class="row1"><b>{L_COLOR_FOR} {styles_block.STYLE_NAME}:</b></td>
	<td class="row2"><input class="post" type="text" name="color_{styles_block.STYLE_ID}" maxlength="50" size="20" value="{styles_block.STYLE_COLOR}" />
</td>
</tr>
<!-- END styles_block -->
<tr> 
	<td class="row1"><b>{L_COLOR_GROUPS_ORDER}:</b></td>
	<td class="row2"><select name="color_group_order">
		<!-- BEGIN group_row -->
		<option value="{group_row.GROUP_ID}" {group_row.CHECKED}>{group_row.GROUP_NAME}</option>
		<!-- END group_row -->
	</select></td> 
</tr>
<tr> 
	<td class="row1"><b>{L_COLOR_GROUPS_ON}:</b></td>
	<td class="row2"><input type="checkbox" name="group_colored" value="{S_COLOR_GROUPS_ON}" {S_COLOR_GROUPS_ON_CHECKED} /> {L_COLOR_GROUPS_ON}</td> 
</tr>
<tr> 
	<th class="thHead" colspan="2">{L_GROUP_PAYMENT_OPTIONS}</th>
</tr>
<tr>
	<td colspan="2" class="row2"><span class="gensmall">{L_GROUP_PAYMENTS_EXPLAIN}</span></td>
</tr>
<tr> 
	<td class="row1"><b>{L_GROUP_PAYMENTS_LW}</b><br /><span class="gensmall">{L_GROUP_PAYMENTS_LW_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" name="group_amount" maxlength="50" size="10" value="{GROUP_AMOUNT_LW}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_GROUP_PAYMENTS_RECUR}:</b></td>
	<td class="row2">{LW_SUB_RECUR}</td>
</tr>
<tr>
	<td class="row1"><b>{L_GROUP_PAYMENTS_RECUR_LENGTH}:</b></td>
	<td class="row2">{LW_BILLING_CIRCLE_PERIOD} {LW_BILLING_PERIOD_BASIS}</td>
</tr>
<tr>
	<td class="row1"><b>{L_GROUP_PAYMENTS_STOP_RECUR}:</td>
	<td class="row2">{LW_STOP_RECURRING}</td>
</tr>
<tr>
	<td class="row1"><b>{L_GROUP_PAYMENTS_STOP_RECUR_AMT}:</b></td>
	<td class="row2">{LW_STOP_RECURRING_NUM}</td>
</tr>
<tr>
	<td class="row1"><b>{L_GROUP_PAYMENTS_FAIL_REATTEMPT}:</b></td>
	<td class="row2">{LW_SUBCRIBE_REATTEMPT}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_GROUP_PAYMENTS_TRIAL1}:</b><br /><span class="gensmall">{L_GROUP_PAYMENTS_TRIAL1_EXPLAIN}</span></td>
	<td class="row2">{L_GROUP_PAYMENTS_BILLNOW} <input class="post" type="text" name="group_first_trial_fee" maxlength="50" size="10" value="{GROUP_TRIAL_PERIOD_ONE_FEE_LW}" /> <span class="gensmall">{L_GROUP_PAYMENTS_BILLNOW_EXPLAIN}</span><br />{L_GROUP_PAYMENTS_TRIAL_PERIOD} {LW_FIRST_TRIAL_PERIOD} {LW_FIRST_TRIAL_PERIOD_BASIS}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_GROUP_PAYMENTS_TRIAL2}:</b><br /><span class="gensmall">{L_GROUP_PAYMENTS_TRIAL2_EXPLAIN}</span></td>
	<td class="row2">{L_GROUP_PAYMENTS_BILLNOW} <input class="post" type="text" name="group_second_trial_fee" maxlength="50" size="10" value="{GROUP_TRIAL_PERIOD_TWO_FEE_LW}" /><br />{L_GROUP_PAYMENTS_TRIAL_PERIOD} {LW_SECOND_TRIAL_PERIOD} {LW_SECOND_TRIAL_PERIOD_BASIS}</td>
</tr>
<tr> 
	<td class="catBottom" colspan="2" align="center"><input type="submit" name="group_update" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" name="reset" class="liteoption" /></td>
</tr>
</table>
{S_HIDDEN_FIELDS}
</form>
