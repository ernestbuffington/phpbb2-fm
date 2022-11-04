{BAN_MENU}
{USER_MENU}
{MOD_CP_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_BM_TITLE}</h1> 

<p>{L_BM_EXPLAIN}</p>

<table width="100%" cellpadding="4" cellspacing="1" align="center" class="forumline"><form action="{S_BANCENTER_ACTION}" method="post">
<tr>
	<td><input type="submit" name="add" value="{L_ADD_NEW}" class="liteoption" /></td>
	<td align="right" class="genmed">{L_SHOW_BANS_BY} <select name="show"> 
		<option value="username"{USERNAME_SELECTED}>{L_USERNAME}</option>
		<option value="ip"{IP_SELECTED}>{L_IP}</option>
		<option value="email"{EMAIL_SELECTED}>{L_EMAIL}</option>
		<option value="all"{ALL_SELECTED}>{L_ALL}</option>
	</select> {L_ORDER} <select name="order"> 
		<option value="ASC"{ASC_SELECTED}>{L_ASCENDING}</option>
		<option value="DESC"{DESC_SELECTED}>{L_DESCENDING}</option>
	</select> <input type="submit" class="liteoption" name="sort_submit" value="{L_SORT}"></td>
</tr>
</form></table>
<table width="100%" cellpadding="4" cellspacing="1" align="center" class="forumline"><form action="{S_BANCENTER_ACTION}" method="post">
<tr>
	<!-- BEGIN username_header -->
	<th class="thCornerL" nowrap="nowrap">{username_header.L_USERNAME}</th>
	<!-- END username_header -->
	<!-- BEGIN ip_header -->
	<th class="thCornerL" nowrap="nowrap">IP</th>
	<!-- END ip_header -->
	<!-- BEGIN email_header -->
	<th class="thCornerL" nowrap="nowrap">{email_header.L_EMAIL}</th>
	<!-- END email_header -->
	<th class="thTop" nowrap="nowrap">{L_BANNED}</th>
	<th class="thTop" nowrap="nowrap">{L_EXPIRES}</th>
	<th class="thTop" nowrap="nowrap">{L_BY}</th>
	<th class="thTop" nowrap="nowrap">{L_REASONS}</th>
	<th class="thCornerR" nowrap="nowrap" width="15%">{L_ACTION}</th>
</tr>
<!-- BEGIN rowlist -->
<tr>
	<!-- BEGIN username_content -->
	<td class="{rowlist.ROW_CLASS}"><a href="{rowlist.username_content.U_VIEWPROFILE}">{rowlist.username_content.USERNAME}</a></td>
	<!-- END username_content -->
	<!-- BEGIN ip_content -->
	<td class="{rowlist.ROW_CLASS}" align="center">{rowlist.ip_content.IP}</td>
	<!-- END ip_content -->
	<!-- BEGIN email_content -->
	<td class="{rowlist.ROW_CLASS}">{rowlist.email_content.EMAIL}</td>
	<!-- END email_content -->
	<td class="{rowlist.ROW_CLASS}" align="center"><span class="gensmall">{rowlist.BAN_TIME}</span></td>
	<td class="{rowlist.ROW_CLASS}" align="center"><span class="gensmall">{rowlist.BAN_EXPIRE_TIME}</span></td>
	<td class="{rowlist.ROW_CLASS}" align="center">{rowlist.BAN_BY}</td>
	<td class="{rowlist.ROW_CLASS}" align="center">{rowlist.BAN_REASON}</td>
	<td class="{rowlist.ROW_CLASS}" align="right" nowrap="nowrap"><a href="{rowlist.U_BAN_EDIT}">{L_EDIT}</a> <input type="checkbox" name="ban_delete[]" value="{rowlist.BAN_ID}"></td>
</tr>
<!-- END rowlist -->
<tr>
</table>
<table width="100%" align="center" cellspacing="2" cellpadding="2">
<tr>
	<td align="right"><input type="submit" name="delete_submit" value="{L_DELETE}" class="liteoption" /></td>
</tr>
</table>
<table width="100%" align="center" cellspacing="2" cellpadding="2">
  <tr>
	<td class="nav">{PAGE_NUMBER}</td>
	<td align="right" class="nav">{PAGINATION}</td>
  </tr>
</form></table>