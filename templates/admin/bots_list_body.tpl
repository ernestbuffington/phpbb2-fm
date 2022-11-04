{BOT_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_PAGE_TITLE}</h1>

<P>{L_PAGE_TEXT}</p>

<table width="100%" cellspacing="2" cellpadding="2" align="center"><form method="post" action="{S_ACTION}">
<tr>
	<td><input type="submit" name="add" value="{L_ADD}" class="liteoption" /></td>
</tr>
</form></table>
<table width="100%" cellspacing="1" cellpadding="4" align="center" class="forumline">
<tr>
	<th class="thCornerL">&nbsp;{L_NAME}&nbsp;</th>
	<th class="thTop">&nbsp;{L_BOT_VISITS}&nbsp;</th>
	<th class="thTop">&nbsp;{L_BOT_PAGES}&nbsp;</th>
	<th class="thTop" width="15%">&nbsp;{L_BOT_LAST_VISIT}&nbsp;</th>
	<th class="thCornerR" width="15%">&nbsp;{L_ACTION}&nbsp;</th>
</tr>
<!-- BEGIN bots -->
<tr>
	<td class="{bots.ROW_CLASS}" valign="middle">{bots.NAME}</td>
	<td class="{bots.ROW_CLASS}" align="center" nowrap="nowrap">{bots.VISITS}</td>
	<td class="{bots.ROW_CLASS}" align="center" nowrap="nowrap">{bots.PAGES}</td>
	<td class="{bots.ROW_CLASS}" align="center" nowrap="nowrap">{bots.LAST_VISIT}</td>
	<td class="{bots.ROW_CLASS}" align="right" nowrap="nowrap"><a href="{bots.U_BOT_EDIT}">{L_EDIT}</a> <a href="{bots.U_BOT_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- END bots -->
<!-- BEGIN nobotrow -->
<tr>
	<td class="row1" align="center" colspan="5">{nobotrow.NO_BOTS}</td>
</tr>
<!-- END nobotrow -->
</table>
<br />

<h1>{L_BOTS_TITLE_PENDING}</h1>

<p>{L_BOTS_EXPLAIN_PENDING}</p>

<table width="100%" cellpadding="4" cellspacing="1" align="center" class="forumline">
<tr>
	<th class="thCornerL">&nbsp;{L_NAME}&nbsp;</th>
	<th class="thTop">&nbsp;{L_BOT_IP}&nbsp;</th>
	<th class="thTop">&nbsp;{L_BOT_AGENT}&nbsp;</th>
	<th class="thCornerR">&nbsp;{L_ACTION}&nbsp;</th>
</tr>
<!-- BEGIN pendingrow -->
<tr>
	<td class="{pendingrow.ROW_CLASS}" width="30%">{pendingrow.BOT_NAME}</td>
	<td class="{pendingrow.ROW_CLASS}" width="20%" align="center" nowrap="nowrap">{pendingrow.IP}</td>
	<td class="{pendingrow.ROW_CLASS}" width="20%" align="center" nowrap="nowrap">{pendingrow.AGENT}</td>
	<td class="{pendingrow.ROW_CLASS}" width="3%" align="center">&nbsp;<a href="{pendingrow.U_BOT_IGNORE}">{pendingrow.L_IGNORE}</a> <a href="{pendingrow.U_BOT_ADD}">{pendingrow.L_ADD}</a>&nbsp;</td>
</tr>
<!-- END pendingrow -->
<!-- BEGIN nopendingrow -->
<tr>
	<td class="row1" align="center" colspan="5">{nopendingrow.NO_BOTS}</td>
</tr>
<!-- END nopendingrow -->
</table>
<br />
