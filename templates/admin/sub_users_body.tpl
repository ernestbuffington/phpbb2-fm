{SUBSCRIPTION_MENU}{GROUP_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_LW_SUBSCRIPTIONS}</h1>

<p>{L_LW_SUB_EXPLAIN}</p>

<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline"><form method="post" name="post" action="{S_ACTION}">
<tr> 
	<th width="25%" class="thCornerL">&nbsp;{L_LW_SUB_GROUP_NAME}&nbsp;</th>
	<th width="25%" class="thTop">&nbsp;{L_LW_SUB_GROUP_INORNOT}&nbsp;</th>
	<th width="25%" class="thTop">&nbsp;{L_LW_SUB_EXPIRATION}&nbsp;</th>
	<th width="25%" class="thCornerR">&nbsp;{L_LW_SUB_ACTION}&nbsp;</th>
</tr>
<!-- BEGIN row -->
<tr> 
  	{row.LW_SUB_GRP_FORM_ACTION_S}
	<td class="{row.ROW_CLASS}"><a href="{row.LW_SUB_GRP_PROFILE}" class="gen">{row.LW_SUB_GRP_NAME}</a></td>
	<td align="center" class="{row.ROW_CLASS}">{row.LW_SUB_GRP_INORNOT}</td>
	<td align="center" nowrap="nowrap" class="{row.ROW_CLASS}">&nbsp;<span class="gen">{row.LW_SUB_EXPTIME}</span>&nbsp;</td>
	<td class="{row.ROW_CLASS}" align="center">{row.LW_SUB_ACTION}</td>
</tr>
{row.S_HIDDEN_FIELDS}
</form>
<!-- END row -->
</table>
<br />
<div align="center" class="copyright">Paypal IPN Subscription with Group 1.0.3 &copy; 2004, {COPYRIGHT_YEAR} <a href="http://bbs.loewen.com.sg" target="_blank" class="copyright">Loewen Enterprise</a></div>