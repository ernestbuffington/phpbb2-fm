{USERCOM_MENU}{EMAIL_MENU}{DIGESTS_MENU}
{MOD_CP_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_ADMIN_USERS_LIST_MAIL_TITLE}</h1>

<p>{L_ADMIN_USERS_LIST_MAIL_EXPLAIN}</p>

<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline">
<tr>
	<th width="38%" class="thCornerL">&nbsp;{L_USERNAME}&nbsp;</th>
	<th class="thCornerR">&nbsp;{L_EMAIL}&nbsp;</th>
</tr>
<!-- BEGIN userrow -->
<tr>
	<td class="{userrow.ROW_CLASS}"><a href="{userrow.U_ADMIN_USER}" class="genmed">{userrow.USERNAME}</a></td>
	<td class="{userrow.ROW_CLASS}"><a href="mailto:{userrow.EMAIL}" class="genmed">{userrow.EMAIL}</a></td>
</tr>
<!-- END userrow -->
</table>