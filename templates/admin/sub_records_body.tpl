{SUBSCRIPTION_MENU}{GROUP_MENU}
</ul>
</div></td>
<td valign="top" width="78%">
<h1>{L_PAGE_TITLE}</h1>

<p>{L_PAGE_EXPLAIN}</h1>

<table width="100%" cellspacing="2" align="center" cellpadding="2"><form method="post" action="{S_RECORDS_DAYS_ACTION}">

<tr> 
	<td align="right"><span class="genmed">{LW_HIDDEN_FIELDS}{L_DISPLAY_TOPICS} {S_SELECT_TOPIC_DAYS} <input type="submit" class="liteoption" value="{L_SUBMIT}" name="submit" /></span></td>
</tr>
</form></table>

<table cellpadding="4" cellspacing="1" width="100%" class="forumline" align="center">
<tr> 
	<th class="thCornerL" nowrap="nowrap">&nbsp;{L_LW_USERNAME}&nbsp;</th>
	<th class="thTop" nowrap="nowrap">&nbsp;{L_LW_CURRENCY}&nbsp;</th>
	<th class="thTop" nowrap="nowrap">&nbsp;{L_LW_MONEY}&nbsp;</th>
	<th class="thTop" nowrap="nowrap">&nbsp;{L_LW_PLUS_MINUS}&nbsp;</th>
	<th class="thTop" nowrap="nowrap">&nbsp;{L_LW_TXNID}&nbsp;</th>
	<th class="thTop" nowrap="nowrap">&nbsp;{L_LW_STATUS}&nbsp;</th>
	<th class="thTop" nowrap="nowrap">&nbsp;{L_LW_DATE}&nbsp;</th>
	<th class="thCornerR" nowrap="nowrap">&nbsp;{L_LW_COMMENT}&nbsp;</th>
</tr>
<!-- BEGIN topicrow -->
<tr> 
	<td class="{topicrow.ROW_CLASS}" align="center" valign="middle"><span class="postdetails">{topicrow.LW_USERNAME}</span></td>
	<td class="{topicrow.ROW_CLASS}" align="center" valign="middle"><span class="name">{topicrow.LW_CURRENCY}</span></td>
	<td class="{topicrow.ROW_CLASS}" align="center" valign="middle"><span class="postdetails">{topicrow.LW_MONEY}</span></td>
	<td class="{topicrow.ROW_CLASS}" align="center" valign="middle"><span class="name">{topicrow.LW_PLUS_MINUS}</span></td>
	<td class="{topicrow.ROW_CLASS}" align="center" valign="middle"><span class="postdetails">{topicrow.LW_TXNID}</span></td>
	<td class="{topicrow.ROW_CLASS}" align="center" valign="middle"><span class="postdetails">{topicrow.LW_STATUS}</span></td>
	<td class="{topicrow.ROW_CLASS}" align="center" valign="middle" nowrap="nowrap"><span class="postdetails">{topicrow.LW_DATE}</span></td>
	<td class="{topicrow.ROW_CLASS}" align="center" valign="middle"><span class="postdetails">{topicrow.LW_COMMENT}</span></td>
</tr>
<!-- END topicrow -->
<!-- BEGIN switch_lw_no_records -->
<tr> 
	<td class="row1" colspan="8" height="30" align="center" valign="middle"><span class="gen">{L_LW_NO_RECORDS}</span></td>
</tr>
<!-- END switch_lw_no_records -->
</table>

<table width="100%" cellspacing="2" align="center" cellpadding="2">
<tr> 
  	<td class="nav">{PAGE_NUMBER}</td>
	<td align="right" class="nav">{PAGINATION}</td>
</tr>
</table>
<br />
<div align="center" class="copyright">Paypal IPN Subscription with Group 1.0.3 &copy; 2004, {COPYRIGHT_YEAR} <a href="http://bbs.loewen.com.sg" target="_blank" class="copyright">Loewen Enterprise</a></div>
