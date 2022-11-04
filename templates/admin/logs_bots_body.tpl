{BOT_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_PAGE_TITLE}</h1>

<p>{L_PAGE_EXPLAIN}</p>

<table width="100%" cellpadding="2" cellspacing="2"><form action="{S_ACTION}" method="post">
<tr>
	<td><input type="submit" name="clear" value="{L_DELETE_ALL}" class="liteoption" /></td>
</tr>
</form></table>
<table width="100%" cellpadding="4" cellspacing="1" align="center" class="forumline">
<tr>
	<th class="thCornerL">{L_BOT_NAME}</th>
	<th class="thTop">{L_BOT_DATE}</th>
	<th class="thCornerR">{L_BOT_URL}</th>
</tr>
<!-- BEGIN bots -->
<tr>
	<td class="{bots.ROW_CLASS}">{bots.BOT_NAME}</td>
	<td class="{bots.ROW_CLASS}"><span class="postdetails">{bots.BOT_TIME}</span></td>
	<td class="{bots.ROW_CLASS}"><span class="postdetails">{bots.BOT_URL}</span></td>
</tr>
<!-- END bots -->
<!-- BEGIN no_bots -->
<tr>
	<td class="row1" colspan="3" align="center">{no_bots.L_NONE}</td>
</tr>
<!-- END no_bots -->
</table>
<table width="100%" cellpadding="2" cellspacing="2" align="center">
<tr>
	<td class="nav">{page.PAGE_NUMBER}</td>
	<td align="right" class="nav">{page.PAGINATION}</td>
</tr>
</table>
