{SUBSCRIPTION_MENU}{GROUP_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_SUB_SETTINGS_TITLE}</h1>

<p>{L_SUB_SETTINGS_EXPLAIN}</p>

<table width="100%" align="center"><form action="{S_ACTION}" method="post">
<tr>
	<td align="right">{CONFIG_SELECT} <input type="submit" name="config" value="{L_GO}" class="liteoption" /></td>
</tr>
</form></table>
<table width="100%" cellpadding="4" cellspacing="1" align="center" class="forumline"><form action="{S_ACTION}" method="post">

<!-- BEGIN switch_ipn -->
<tr>
	<th class="thHead" colspan="2">{L_LW_PAYPAL_SETTINGS}</th>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_LW_OUR_PAYPAL_ACCT}:</b><br /><span class="gensmall">{L_LW_OUR_PAYPAL_ACCT_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="25" maxlength="255" name="paypal_p_acct" value="{LW_PAYPAL_P_ACCT}" /></td>
</tr>
<tr>
	<td class="row1"<b>{L_LW_BUSINESS_PAYPAL_ACCT}:</b><br /><span class="gensmall">{L_LW_BUSINESS_PAYPAL_ACCT_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="25" maxlength="255" name="paypal_b_acct" value="{LW_BUSINESS_PAYPAL_ACCT}" /></td>
</tr>
<!-- END switch_ipn -->

<!-- BEGIN switch_currency -->
<tr>
	<th class="thHead" colspan="2">{L_CURRENCY_GENERAL_SETTINGS}</th>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_LW_PAYPAL_CURRENCY_CODE}:</b><br /><span class="gensmall">{L_LW_PAYPAL_CURRENCY_CODE_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="5" name="paypal_currency_code" maxlength="10" value="{LW_PAYPAL_CURRENCY_CODE}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_DONATE_CURRENCY}:</b><br /><span class="gensmall">{L_DONATE_CURRENCY_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" maxlength="255" size="30" name="donate_currencies" value="{DONATE_CURRENCY}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_DONATE_USD_TO_PRI}:</b><br /><span class="gensmall">{L_DONATE_USD_TO_PRI_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" maxlength="255" size="10" name="usd_to_primary" value="{DONATE_USD_TO_PRI}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_DONATE_EUR_TO_PRI}:</b><br /><span class="gensmall">{L_DONATE_EUR_TO_PRI_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" maxlength="255" size="10" name="eur_to_primary" value="{DONATE_EUR_TO_PRI}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_DONATE_GBP_TO_PRI}:</b><br /><span class="gensmall">{L_DONATE_GBP_TO_PRI_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" maxlength="255" size="10" name="gbp_to_primary" value="{DONATE_GBP_TO_PRI}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_DONATE_CAD_TO_PRI}:</b><br /><span class="gensmall">{L_DONATE_CAD_TO_PRI_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" maxlength="255" size="10" name="cad_to_primary" value="{DONATE_CAD_TO_PRI}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_DONATE_JPY_TO_PRI}:</b><br /><span class="gensmall"></span></td>
	<td class="row2"><input class="post" type="text" maxlength="255" size="10" name="jpy_to_primary" value="{DONATE_JPY_TO_PRI}" /></td>
</tr>
<!-- END switch_currency -->

<!-- BEGIN switch_donate -->
<tr>
	<th class="thHead" colspan="3">{L_DONATION_SETTINGS}</th>
</tr>
<tr>
	<td class="row1" width="50%" colspan="2"><b>{L_LW_DISPLAY_X_DONORS}:</b><br /><span class="gensmall">{L_LW_DISPLAY_X_DONORS_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="5" maxlength="100" name="dislay_x_donors" value="{LW_DISPLAY_X_DONORS}" /></td>
</tr>
<tr>
	<td class="row1" colspan="2"><b>{L_LW_TOP_DONORS}:</b></td>
	<td class="row2"><input type="radio" name="list_top_donors" value="0" {TOP_DONORS_NO} /> {L_LAST}&nbsp;&nbsp;<input type="radio" name="list_top_donors" value="1" {TOP_DONORS_YES} /> {L_TOP}</td>
</tr>
<tr>
	<td class="row1" colspan="2"><b>{L_LW_DONATION_DESCRIPTION}:</b><br /><span class="gensmall">{L_LW_DONATION_DESCRIPTION_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="30" maxlength="100" name="donate_description" value="{LW_DONATION_DESCRIPTION}" /></td>
</tr>
<tr>
	<td class="row1" colspan="2"><b>{L_LW_DONATION_GOAL}:</b><br /><span class="gensmall">{L_LW_DONATION_GOAL_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="5" maxlength="100" name="donate_cur_goal" value="{LW_DONATION_GOAL}" /></td>
</tr>
<tr>
	<td class="row1" colspan="2"><b>{L_LW_DONATION_START}:</b><br /><span class="gensmall">{L_LW_DONATION_START_EXPLAIN}</span></td>
	<td class="row2">&nbsp;<input class="post" type="text" size="12" maxlength="10" name="donate_start_time" value="{LW_DONATION_START}" /> {L_STARTS}<br />&nbsp;<input class="post" type="text" size="12" maxlength="10" name="donate_end_time" value="{LW_DONATION_END}" /> {L_ENDS}</td>
</tr>
<tr>
	<td class="row2" width="5%"></td>
	<td class="row1"><b>{L_LW_DONATION_POINTS}:</b><br /><span class="gensmall">{L_LW_DONATION_POINTS_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="5" maxlength="100" name="donate_to_points" value="{LW_DONATION_POINTS}" /></td>
</tr>
<tr>
	<td class="row2"></td>
	<td class="row1"><b>{L_LW_POSTS_COUNTS}:</b><br /><span class="gensmall">{L_LW_POSTS_COUNTS_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="5" maxlength="3" name="donate_to_posts" value="{LW_POSTS_COUNTS}" /></td>
</tr>
<tr>
	<td class="row1" colspan="2"><b>{L_LW_DONATE_TOGRP_ONE}:</b><br /><span class="gensmall">{L_LW_DONATE_TOGRP_ONE_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="5" maxlength="3" name="donate_to_grp_one" value="{LW_DONATE_TOGRP_ONE}" /></td>
</tr>
<tr>
	<td class="row1" colspan="2"><b>{L_LW_TOGRPONE_AMOUNT}:</b><br /><span class="gensmall">{L_LW_TOGRPONE_AMOUNT_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="5" maxlength="3" name="to_grp_one_amount" value="{LW_TOGRPONE_AMOUNT}" /></td>
</tr>
<tr>
	<td class="row1" colspan="2"><b>{L_LW_DONATE_TOGRP_TWO}:</b><br /><span class="gensmall">{L_LW_DONATE_TOGRP_TWO_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="5" maxlength="3" name="donate_to_grp_two" value="{LW_DONATE_TOGRP_TWO}" /></td>
</tr>
<tr>
	<td class="row1" colspan="2"><b>{L_LW_TOGRPTWO_AMOUNT}:</b><br /><span class="gensmall">{L_LW_TOGRPTWO_AMOUNT_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="5" maxlength="3" name="to_grp_two_amount" value="{LW_TOGRPTWO_AMOUNT}" /></td>
</tr>
<tr>
	<td class="row1" colspan="2"><b>{L_LW_TORANK_ID}:</b><br /><span class="gensmall">{L_LW_TORANK_ID_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="5" maxlength="100" name="donor_rank_id" value="{LW_TORANK_ID}" /></td>
</tr>
<!-- END switch_donate -->

<!-- BEGIN switch_subscribe -->
<tr>
	<th class="thHead" colspan="2">{L_SUB_SETTINGS}</th>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_LW_TRIAL_PERIOD}:</b><br /><span class="gensmall">{L_LW_TRIAL_PERIOD_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="5" name="lw_trial_period" value="{LW_TRIAL_PERIOD}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_SUB_EXTRA_DAYS}:</b><br /><span class="gensmall">{L_SUB_EXTRA_DAYS_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="5" maxlength="10" name="extra_days_for_sub" value="{SUB_EXTRA_DAYS}" /></td>
</tr>
<!-- END switch_subscribe -->

<tr>
	<td class="catBottom" colspan="3" align="center"><input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</form></table>
<br />
<div align="center" class="copyright">Paypal IPN Subscription with Group 1.0.3 &copy; 2004, {COPYRIGHT_YEAR} <a href="http://bbs.loewen.com.sg" target="_blank" class="copyright">Loewen Enterprise</a></div>
