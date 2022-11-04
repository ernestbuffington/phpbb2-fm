{FORUM_MENU}
{MOD_CP_MENU}
</div></td>
<td valign="top" width="78%">

<h1>{L_PRUNE_TITLE}</h1>

<p>{L_PRUNE_TEXT}</p>

<table width="100%" cellpadding="2" cellspacing="2" align="center"><form action="{S_PRUNE_ACTION}" method="post">
<tr>
	<td>{L_ENABLE_PRUNE}: <input type="checkbox" name="enable_prune" value="{ENABLE_PRUNE}" {ENABLE_PRUNE} /> <input type="submit" name="submit" class="mainoption" value="{L_SUBMIT}" /></td>
</tr>
</table>

<table width="100%" cellpadding="4" cellspacing="1" class="forumline" align="center">
<tr> 
	<th class="thCornerL">&nbsp;{L_PRUNE_FORUM}&nbsp;</th>
	<th class="thTop">&nbsp;{L_PRUNE_FREQ} *&nbsp;</th>
 	<th class="thTop">&nbsp;{L_PRUNE_CHECK}&nbsp;</th>
 	<th class="thCornerR">&nbsp;{L_PRUNE_ACTIVE}&nbsp;</th>
</tr>
<tr>
	<td colspan="4" class="row2"><span class="gensmall">{L_DAYS_EXPLAIN}</span></td>
</tr>
<!-- BEGIN prune_overview -->
<tr> 
	<td class="{prune_overview.ROW_CLASS}"><input type="hidden" name="forum_id[{prune_overview.S_PRUNE_INDEX}]" value="{prune_overview.FORUM_ID}" /><span class="genmed">&nbsp;{prune_overview.PRUNE_FORUM}&nbsp;</span></td>
	<td align="center" class="{prune_overview.ROW_CLASS}"><span class="gensmall"><input type="text" class="post" maxlength="5" name="prune_days[{prune_overview.S_PRUNE_INDEX}]" size="5" value="{prune_overview.PRUNE_DAYS}" /> {L_DAYS}</span></td>
    	<td align="center" class="{prune_overview.ROW_CLASS}"><span class="gensmall"><input type="text" class="post" maxlength="5" size="5" name="prune_freq[{prune_overview.S_PRUNE_INDEX}]" value="{prune_overview.PRUNE_FREQ}" /> {L_DAYS}</span></td>
    	<td align="center" class="{prune_overview.ROW_CLASS}" valign="middle"><input type="checkbox" name="prune_enable[{prune_overview.S_PRUNE_INDEX}]" value="1" {prune_overview.S_PRUNE_ENABLED} /></td>
</tr>
<!-- END prune_overview -->
</form></table>
<br />
<div align="center" class="copyright">Prune Overview 1.0.0 &copy; 2004, {COPYRIGHT_YEAR} <a href="http://www.leuchte.net" class="copyright" target="_blank">Leuchte</a></div>
