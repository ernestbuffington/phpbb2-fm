{BAN_MENU}
{USER_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<script language="javascript" type="text/javascript">
<!--
function SetDays()	
{ 
	document.DaysFrm.submit() 
}
// -->
</script>

<h1>{L_PRUNE_USERS}</h1>

<p>{L_PRUNE_USERS_EXPLAIN}</p>

<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline"><form action="{S_CONFIG_ACTION}" method="post">
<tr>
	<th class="thHead" colspan="2">{L_CONFIGURATION}</th>
</tr>
<tr> 
   	<td class="row1" width="50%"><b>{L_PRUNE_EMAIL}:</b><br /><span class="gensmall">{L_PRUNE_EMAIL_EXPLAIN}</span></td> 
   	<td class="row2"><input type="radio" name="user_prune_notify" value="1" {PRUNE_EMAIL_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="user_prune_notify" value="0" {PRUNE_EMAIL_NO} /> {L_NO}</td> 
</tr> 
<tr>
	<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="config" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</form></table>
<br />

<table width="100%" cellspacing="1" cellpadding="4" align="center" class="forumline"><form name="DaysFrm" action="" method="post">
<tr>
	<th class="thCornerL" nowrap="nowrap">&nbsp;{L_DAYS}&nbsp;</th>
	<th class="thCornerR" nowrap="nowrap">&nbsp;{L_PRUNE_LIST}&nbsp;</td>
</tr>
<!-- BEGIN prune_list -->
<tr>
	<td class="row1" width="38%" valign="top"><b>{prune_list.U_PRUNE}:</b><br /><span class="gensmall">{prune_list.L_PRUNE_EXPLAIN}<br /><br />{L_SELECT}: {prune_list.S_DAYS}</span></td>
	<td class="row2">{L_USERS}: <b>{prune_list.USER_COUNT}</b><br /><span class="gensmall">{prune_list.LIST}</span></td>
</tr>
<!-- END prune_list -->
</form></table>
<br />
<div align="center" class="copyright">Prune Users &copy; 2002, {COPYRIGHT_YEAR} Niels</div>

