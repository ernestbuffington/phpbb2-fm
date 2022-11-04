{BAN_MENU}
{USER_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_PAGE_TITLE}</h1>

<p>{L_PAGE_EXPLAIN}</p>

<h1>{L_BANNED_SITES}</h1>

<table width="100%" cellspacing="2" cellpadding="2" align="center"><form method="post" action="{S_ACTION}">
<tr>
	<td align="right" class="genmed">{L_BAN_SITE}:&nbsp;<input class="post" type="text" name="site_url" />&nbsp;{L_REASON}:&nbsp;<input class="post" type="text" name="site_reason" />&nbsp;<input type="submit" name="add" value="{L_SUBMIT}" class="mainoption" /></span></td>
</tr>
</form></table>
<table width="100%" cellspacing="1" cellpadding="4" align="center" class="forumline">
<tr>
	<th class="thCornerL">&nbsp;{L_SITE_URL}&nbsp;</th>
	<th class="thTop">&nbsp;{L_REASON}&nbsp;</th>		
	<th class="thCornerR" width="15%">&nbsp;{L_ACTION}&nbsp;</hd>		
</tr>
<!-- BEGIN row -->
<tr>
	<td class="{row.ROW_CLASS}">{row.SITE_URL}</td>
	<td class="{row.ROW_CLASS}">{row.REASON}</td>
	<td class="{row.ROW_CLASS}" align="right"><a href="{LIST_REMOVE}&amp;site={row.SITE_ID}">{DELETE}</a></td>
</tr>
<!-- END row -->
</table>
<br />

<h1>Banned Visitors</h1>

<p><a href="{LIST_DELETE}">{L_DELETE_ALL}</a></p>

<table width="100%" cellspacing="1" cellpadding="4" align="center" class="forumline">
<tr>
	<th class="thCornerL">&nbsp;{L_REFERER}&nbsp;</th>
	<th class="thTop">&nbsp;{L_IP}&nbsp;</th>		
	<th class="thTop">&nbsp;{L_IP_OWNER}&nbsp;</th>		
	<th class="thTop">&nbsp;{L_BROWSER}&nbsp;</th>		
	<th class="thCornerR">&nbsp;{L_USERNAME}&nbsp;</th>		
</tr>
<!-- BEGIN row2 -->
<tr>
	<td class="{row2.ROW_CLASS}">{row2.REFER}</td>
	<td class="{row2.ROW_CLASS}" align="center">{row2.IP}</td>
	<td class="{row2.ROW_CLASS}" align="center">{row2.IPOWNER}</td>
	<td class="{row2.ROW_CLASS}">{row2.BROWSER}</td>
	<td class="{row2.ROW_CLASS}" align="center">{row2.USER}</td>
</tr>
<!-- END LIST2 -->
</table>