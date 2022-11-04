{TOPIC_MENU}{FORUM_MENU}{VOTE_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_FTR_SETTINGS}</h1>

<p>{L_FTR_SETTINGS_EXPLAIN}</p>

<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline"><form action="{S_CONFIG_ACTION}" method="post">
<tr>
	<th class="thCornerL">&nbsp;#&nbsp;</th>
	<th class="thTop">&nbsp;{L_USERNAME} {USERNAME_ORDER}&nbsp;</th>
	<th class="thTop">&nbsp;{L_DATE} &nbsp; {DATE_ORDER}&nbsp;</th>
	<th class="thCornerR" width="15%">&nbsp;{L_ACTION}&nbsp;</th>
</tr>
<!-- BEGIN userrow -->
<tr>
	<td class="{userrow.ROW_CLASS}" align="center" width="5%">{userrow.ROW_NUM}</td>
	<td class="{userrow.ROW_CLASS}"><a href="{userrow.U_EDITUSER}" class="genmed">{userrow.USERNAME}</a></td>
	<td class="{userrow.ROW_CLASS}">{userrow.DATE}</td>
	<td class="{userrow.ROW_CLASS}" align="right"><a href="{userrow.U_DELETE}">{userrow.DELETE}</a></td>
</tr>
<!-- END userrow -->
<!-- BEGIN none -->
<tr>
	<td colspan="4" align="center" class="row1" height="30">{none.L_NONE}</td>
</tr>
<!-- END none -->
</form></table>
<table width="100%" align="center" cellpadding="2" cellspacing="2">
<tr>
	<td class="gensmall"><b>{PAGE_NUMBER}</b> [ <b>{TOTAL_USERS}</b> ]</td>
	<td class="nav" align="right">{PAGINATION}</td>
</tr>
</table>
<br />
<div align="center" class="copyright">Force Topic Read 1.0.3 &copy 2003, {COPYRIGHT_YEAR} <a href="http://phpbb-tweaks.com" class="copyright" target="_blank">aUsTiN</a></div>
