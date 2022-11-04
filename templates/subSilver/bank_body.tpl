
<table width="100%" cellspacing="2" cellpadding="2" align="center">
  <tr> 
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></td>
	<td align="right" class="nav"><a href="{U_MY_TRANS}" class="nav">{L_MY_TRANS}</a></td>
  </tr>
</table>

<table width="100%" cellpadding="4" cellspacing="1" align="center" class="forumline">
<tr> 
 	<th class="thHead">{L_BANK_TITLE}</th>
</tr>
<tr>
	<td class="row1"><table width="100%" cellspacing="1" cellpadding="2">
	<tr>
		<td valign="middle" align="right" nowrap="nowrap" width="50%"><span class="gen">{L_TOTAL_ACCS}:&nbsp;</span></td>
	  	<td><b class="gen">{BANK_ACCOUNTS}</b></td>
	</tr>
	<tr>
		<td valign="middle" align="right" nowrap="nowrap"><span class="gen">{L_HOLDING}:&nbsp;</span></td>
	  	<td><b class="gen">{BANK_HOLDINGS}</b></td>
	</tr>
	<!-- BEGIN switch_min_depo -->
	<tr>
		<td valign="middle" align="right" nowrap="nowrap"><span class="gen">{L_MIN_DEPO}:&nbsp;</span></td>
		<td><b class="gen">{BANK_MIN_DEPO}</b></td>
	</tr>
	<!-- END switch_min_depo -->
	<tr>
		<td valign="middle" align="right" nowrap="nowrap"><span class="gen">{L_INTEREST_RATE}:&nbsp;</span></td>
	  	<td><b class="gen">{BANK_INTEREST} %</b></td>
	</tr>
	<!-- BEGIN switch_min_with -->
	<tr>
		<td valign="middle" align="right" nowrap="nowrap"><span class="gen">{L_MIN_WITH}:&nbsp;</span></td>
		<td><b class="gen">{BANK_MIN_WITH}</b></td>
	</tr>
	<!-- END switch_min_with -->
	<!-- BEGIN switch_withdraw_fees -->
	<tr>
		<td valign="middle" align="right" nowrap="nowrap"><span class="gen">{L_WITHDRAW_RATE}:&nbsp;</span></td>
	  	<td><b class="gen">{BANK_FEES} %</b></td>
	</tr>
	<!-- END switch_withdraw_fees -->
	</table></td>
	</tr>
	<tr>
		<th class="thSides">{L_BANK_INFO}</th>
	</tr>
	<tr>
		<td class="row1"><table width="100%" cellspacing="1" cellpadding="2">
	<tr>			
		<td valign="middle" align="right" nowrap="nowrap" width="50%"><span class="gen">{L_ACC_OPEN}:&nbsp;</span></td>
	  	<td><b class="gen">{ACC_OPENED}</b></td>
	</tr>
	<tr>
		<td valign="middle" align="right" nowrap="nowrap"><span class="gen">{L_USER_BALANCE}:&nbsp;</span></td>
	  	<td><b class="gen">{USER_BALANCE}</b></td>
	</tr>
	<tr>
		<form method="post" action="{U_DEPOSIT}">
		<td valign="middle" align="right" nowrap="nowrap"><span class="gen">{L_DEPOSIT_POINTS}:&nbsp;</span><br /><span class="gensmall">{L_MAX_DEPO}</span></td>
	  	<td><input class="post" type="text" name="deposit" size="20" maxlength="100" value="{USER_GOLD}">&nbsp; <input class="liteoption" type="submit" name="Deposit" value="{L_DEPOSIT}" /></td>
		</form>
	</tr>
	<tr>
		<form method="post" action="{U_WITHDRAW}">
		<td valign="middle" align="right" nowrap="nowrap"><span class="gen">{L_WITHDRAW_POINTS}:&nbsp;</span><br /><span class="gensmall">{L_MAX_WITH}</span></td>
	  	<td><input type="text" class="post" name="withdraw" size="20" maxlength="100" value="{USER_WITHDRAW}">&nbsp; <input class="liteoption" type="submit" name="Withdraw" value="{L_WITHDRAW}" /></td>
		</form>
	</tr>
	</table></td>
</tr>	
<tr>
	<td class="catBottom">&nbsp;</td>
</tr>	
</table>
<br />

<table width="100%" cellspacing="2" align="center"> 
  <tr> 
	<td align="right" valign="middle" nowrap>{JUMPBOX}</td> 
  </tr> 
</table> 
<br />
<div align="center" class="copyright">Bank 2.0.1 &copy; 2003, {COPYRIGHT_YEAR} <a href="http://www.zarath.com/" class="copyright" target="_blank">Zarath Technologies</a></div>
