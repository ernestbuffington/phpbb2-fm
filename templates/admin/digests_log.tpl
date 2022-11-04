{DIGESTS_MENU}{EMAIL_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<script language="javascript" type="text/javascript">
<!--

function NewWindow(mypage,myname)
{
	settings='width=300,height=200,top=0,left=0,toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=no,scrollbars=no';
	PopupWin=window.open(mypage,myname,settings);
	PopupWin.focus();
}
// -->
</script>

<h1>{L_PAGE_TITLE}</h1>

<p>{L_PAGE_DESCRIPTION}</p>

<table width="100%" cellspacing="2" cellpadding="2" align="center"><form method="post" action="{S_MODE_ACTION}">
<tr>
	<td align="right" colspan="2" valign="bottom"><span class="genmed">{L_USER_FILTER}:&nbsp;{S_USER_FILTER}&nbsp;&nbsp;{L_STATUS_FILTER}:&nbsp;{S_STATUS_FILTER}</td>
</tr>
<tr> 
	<td align="left" valign="bottom"><span class="nav"><b>{PAGINATION}</span></td>	
	<td align="right" nowrap="nowrap"><span class="genmed">{L_SELECT_SORT_METHOD}:&nbsp;{S_MODE_SELECT}&nbsp;&nbsp;{L_ORDER}:&nbsp;{S_ORDER_SELECT}&nbsp;&nbsp;<input type="submit" name="submit" value="{L_SUBMIT}" class="liteoption" /></span></td>
</tr>
</table>

<table width="100%" cellpadding="4" cellspacing="1" class="forumline">
<tr> 
	<th class="thCornerL">{L_LOG_TIME}</th>
	<th class="thTop">{L_RUN_TYPE}</th>
	<th class="thTop">{L_USERNAME}</th>
	<th class="thTop">{L_USERTYPE}</th>
	<th class="thTop">{L_TYPE}</th>
	<th class="thTop">{L_LOG_STATUS}</th>
	<th class="thCornerR">#</th>
</tr>
<!-- BEGIN log_row -->
<tr> 
	<td class="{log_row.ROW_CLASS}" align="center" valign="top"><span class="gensmall"><a href="{log_row.U_DIGEST_TIMES}" onclick="NewWindow(this.href,'PopupWin');return false" onfocus="this.blur()"; title="{L_POPUP_MESSAGE}">{log_row.LOG_TIME}</a></span></td>
	<td class="{log_row.ROW_CLASS}" align="center" valign="top"><span class="gensmall">{log_row.RUN_TYPE}</span></td>
	<td class="{log_row.ROW_CLASS}" align="center" valign="top"><span class="gensmall">{log_row.USERNAME}</span></td>
	<td class="{log_row.ROW_CLASS}" align="left" valign="top"><span class="gensmall">{log_row.DIGEST_TYPE}</span></td>
	<td class="{log_row.ROW_CLASS}" align="center" valign="top"><span class="gensmall">{log_row.DIGEST_FREQUENCY}</span></td>
	<td class="{log_row.ROW_CLASS}" align="left" valign="top"><span class="gensmall">{log_row.LOG_STATUS}</span></td>
	<td class="{log_row.ROW_CLASS}" align="center" valign="top"><span class="gensmall">{log_row.POSTS}</span></td>
</tr>
<!-- END log_row -->	
</form></table>

<table width="100%" cellspacing="2" cellpadding="2" align="center">
<tr> 
	<td align="right" nowrap="nowrap" class="nav">{PAGINATION}</td>
</tr>
</table>