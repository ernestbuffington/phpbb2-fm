<table width="100%" cellspacing="2" cellpadding="2" align="center">
<tr> 
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> <a href="{S_LW_TOPUP}" class="nav">{L_LW_TOPUP}</a></td>
</tr>
</table>

<table cellpadding="4" cellspacing="1" class="forumline" width="100%"><form action="{LW_PAYPAL_ACTION}" method="post">
<tr> 
	<th colspan="2" class="thHead">{L_LW_TOPUP_TITLE}</th>
</tr>
<tr> 
	<td class="row2" height="30" align="center">{L_LW_AMOUNT_TO_PAY}</td>
</tr>
<tr> 
	<td class="row1" height="30" align="center">
	  	<input type="image" src="{LW_PAYPAL_LOGO}" name="submit" alt="" title="" />
		<input type="hidden" name="cmd" value="_xclick" />
		<input type="hidden" name="amount" value="{LW_PAY_AMOUNT}" />
		<input type="hidden" name="currency_code" value="{LW_PAY_CURRENCY}" />
		<input type="hidden" name="business" value="{LW_BUSINESS_ACCT}" />
		<input type="hidden" name="item_name" value="{LW_ITEM_NAME}" />
		<input type="hidden" name="item_number" value="{LW_ITEM_NUMBER}" />
		<input type="hidden" name="no_shipping" value="1" />	
		<input type="hidden" name="notify_url" value="{LW_NOTIFY_URL}" />
		<input type="hidden" name="return" value="{LW_RETURN_URL}" />
		<input type="hidden" name="cancel_return" value="{LW_CANCEL_RETURN_URL}" />
	</td>
</tr>
<tr>
	<td class="catBottom">&nbsp;</td>
</tr>
</form></table>
<br />
<table width="100%" cellspacing="2" align="center"> 
  <tr> 
	<td align="right" valign="middle" nowrap>{JUMPBOX}</td> 
  </tr> 
</table> 
<br />
<div align="center" class="copyright">Donation 1.0.2 &copy; 2004, {COPYRIGHT_YEAR} <a href="http://bbs.loewen.com.sg" target="_blank" class="copyright">Loewen Enterprise</a></div>

