{CONFIG_MENU}{USERCOM_MENU}{SERVER_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_BANK_TITLE}</h1>

<p>{L_BANK_EXPLAIN}</p>

<!-- BEGIN switch_config -->
<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline"><form action="{S_CONFIG_ACTION}" method="post">
<tr>
	<th class="thHead" colspan="2">{L_BANK_TITLE}</th>
</tr>
<tr> 
   	<td class="row1" width="50%"><b>{L_BANK_STATUS}:</b></td>
	<td class="row2"><input type="radio" name="bankopened" value="1"{S_BANKOPEN_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="bankopened" value="0"{S_BANKOPEN_NO} /> {L_NO}</td>
</tr>
<tr> 
  	<td class="row1"><b>{L_BANK_NAME}:</b></td>
  	<td class="row2"><input class="post" type="text" size="30" maxlength="32" name="bankname" value="{BANK_NAME}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_MIN_DEPO}:</b></td>
	<td class="row2"><input type="text" class="post" name="bank_mindeposit" size="5" value="{BANK_MIN_DEPO}" maxlength="10" /> {L_POINTS}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_BANK_INTEREST}:</b><br /><span class="gensmall">{L_BANK_INTEREST_EXPLAIN}</span></td>
	<td class="row2"> <input class="post" type="text" size="5" maxlength="3" name="bankinterest" value="{BANK_INTEREST}" /> %</td>
</tr>
<tr>
	  <td class="row1"><b>{L_DISABLE_INTEREST}:</b><br /><span class="gensmall">{L_ZERO_FOR_NONE}</span></td>
	  <td class="row2"><input type="text" class="post" name="bank_interestcut" size="5" maxlength="14" value="{BANK_DISABLE_INTEREST}" /> {L_POINTS}</td>
</tr>
<tr>
	<td class="row1"><b>{L_MIN_WITH}:</b></td>
	<td class="row2"><input type="text" class="post" name="bank_minwithdraw" size="5" value="{BANK_MIN_WITH}" maxlength="10" /> {L_POINTS}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_BANK_FEES}:</b><br /><span class="gensmall">{L_BANK_INTEREST_EXPLAIN}</span></td>
    	<td class="row2"><input class="post" type="text" size="5" maxlength="3" name="bankfees" value="{BANK_FEES}" /> %</td>
</tr>
<tr> 
	<td class="row1"><b>{L_BANK_PAYOUT_TIME}:</b><br /><span class="gensmall">{L_BANK_PAYOUT_TIME_EXPLAIN}</span></td>
    	<td class="row2"><input class="post" type="text" size="5" maxlength="10" name="bankpayouttime" value="{BANK_PAYOUT_TIME}" /></td>
</tr>
<tr>
	<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</form></table>
<br />

<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline"><form action="{S_CONFIG_ACTION}" method="post"><input type="hidden" name="action" value="editaccount"
<tr>
	<td class="catHead" align="center" colspan="4"><span class="gensmall">{L_USERNAME}:</span> <input type="text" class="post" class="post" name="username"{AJAXED_USER_LIST} maxlength="50" size="20"> &nbsp;<input type="submit" value="{L_LOOK_UP}" class="liteoption" />{AJAXED_USER_LIST_BOX}</td>
</tr>
<tr> 
	<th width="25%" class="thCornerL">&nbsp;{L_STATISTIC}&nbsp;</th>
	<th width="25%" class="thTop">&nbsp;{L_VALUE}&nbsp;</th>
	<th width="25%" class="thTop">&nbsp;{L_STATISTIC}&nbsp;</th>
	<th width="25%" class="thCornerR">&nbsp;{L_VALUE}&nbsp;</th>
</tr>
<tr> 
	<td class="row1">{L_BANK_TOTAL_ACCOUNTS}:</td>
    	<td class="row2"><b>{BANK_TOTAL_ACCOUNTS}</b></td>
	<td class="row1">{L_BANK_TOTAL_DEPOSITED}:</td>
    	<td class="row2"><b>{BANK_TOTAL_DEPOSITED}</b></td>
</tr>
<tr> 
	<td class="row1">{L_BANK_TOTAL_HOLDING}:</td>
    	<td class="row2"><b>{BANK_TOTAL_HOLDING}</b></td>
	<td class="row1">{L_BANK_TOTAL_WITHDRAWN}:</td>
    	<td class="row2"><b>{BANK_TOTAL_WITHDRAWN}</b></td>
</tr>
</form></table>
<br />
<!-- END switch_config -->
<!-- BEGIN edit_user -->
<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline"><form action="{S_CONFIG_ACTION}" method="post"><input type="hidden" name="action" value="updateaccount">
<tr> 
	<th class="thHead" colspan="2">{L_BANK_TITLE}</th>
</tr>
<tr>
	<td class="row1" width="38%"><b>{L_HOLDING}:</b></td>
	<td class="row2"><input type="text" class="post" name="holding" size="20" maxlength="10" value="{HOLDING}" /> {L_POINTS}</td>
</tr>
<tr>
	<td class="row1"><b>{L_DEPOSITED}:</b></td>
	<td class="row2"><input type="text" class="post" name="deposited" size="20" maxlength="10" value="{DEPOSITED}" /> {L_POINTS}</td>
</tr>
<tr>
	<td class="row1"><b>{L_WITHDRAWN}:</b></td>
	<td class="row2"><input type="text" class="post" name="withdrawn" size="20" maxlength="10" value="{WITHDRAWN}" /> {L_POINTS}</td>
</tr>
<tr>
	<td class="row1"><b>{L_FEES}:</b></td>
	<td class="row2"><input type="radio" value="1" name="fees"{S_FEES_ENABLED}> {L_ENABLED}&nbsp;&nbsp;<input type="radio" value="0" name="fees"{S_FEES_DISABLED}> {L_DISABLED}</td>
</tr>
<tr>
	<td class="catBottom" colspan="2" align="center"><input type="hidden" name="user_id" value="{USER_ID}"><input class="mainoption" type="submit" name="update" value="{L_SUBMIT}" />&nbsp;&nbsp;<input class="liteoption" type="reset" value="{L_RESET}" /></td>
</tr>
</form></table>
<br />
<!-- END edit_user -->
<div align="center" class="copyright">Bank 2.0.1 &copy; 2003, {COPYRIGHT_YEAR} <a href="http://www.zarath.com/" class="copyright" target="_blank">Zarath Technologies</a></div>
