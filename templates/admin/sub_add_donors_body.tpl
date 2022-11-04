{SUBSCRIPTION_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_DONOR_CONFIGURATION_TITLE}</h1>

<p>{L_DONOR_CONFIGURATION_EXPLAIN}</p>

<table width="100%" cellpadding="4" cellspacing="1" align="center" class="forumline"><form action="{S_DONOR_CONFIG_ACTION}" method="post">
<tr>
	<th class="thHead" colspan="2">{L_DONOR_GENERAL_SETTINGS}</th>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_USER_ACCOUNT}:</b></td>
	<td class="row2"><input class="post" type="text" maxlength="99" size="20" name="username"{AJAXED_USER_LIST} value="{USER_ACCOUNT}" />{AJAXED_USER_LIST_BOX}</td>
</tr>
<tr>
	<td class="row1"><b>{L_DONATE_MONEY}:</b></td>
	<td class="row2"><input class="post" type="text" maxlength="255" size="10" name="lw_money" value="{DONATE_MONEY}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_DONATE_DATE}:</b><br /><span class="gensmall">{L_DONATE_DATE_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" maxlength="19" size="25" name="lw_date" value="{SCRIPT_PATH}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_TRANSACTION_ID}:</b></td>
	<td class="row2"><input class="post" type="text" size="40" maxlength="100" name="txn_id" value="{TRANSACTION_ID}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_DONOR_PAY_ACCOUNT}:</b><br /><span class="gensmall">{L_DONOR_PAY_ACCOUNT_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="40" maxlength="255" name="donor_pay_acct" value="{DONOR_PAY_ACCOUNT}" /></td>
</tr>
<tr>
	<td class="catBottom" colspan="2" align="center"><input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</form></table>
<br />
<div align="center" class="copyright">Dontaion 1.0.2 &copy; 2004, {COPYRIGHT_YEAR} <a href="http://bbs.loewen.com.sg" target="_blank" class="copyright">Loewen Enterprise</a></div>
