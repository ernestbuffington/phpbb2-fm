{DIGESTS_MENU}{EMAIL_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_VERIFY_TITLE}</h1>

<p>{L_VERIFY_START}</p>

<table width="100%" cellpadding="4" cellspacing="1" class="forumline">
<tr> 
	<th class="thCornerL">{L_USER_ID}</th>
	<th class="thTop">{L_USERNAME}</th>
	<th class="thTop">{L_MESSAGE}</th>
	<th class="thCornerR">{L_OUTCOME}</th>
</tr>
<!-- BEGIN verify_user_row -->
  <tr> 
	<td class="{verify_user_row.ROW_CLASS}" align="center" valign="top"><span class="gensmall">{verify_user_row.USER_ID}</span></td>
	<td class="{verify_user_row.ROW_CLASS}" align="center" valign="top"><span class="gensmall">{verify_user_row.USERNAME}</span></td>
	<td class="{verify_user_row.ROW_CLASS}" align="left" valign="top"><span class="gensmall">{verify_user_row.MESSAGE}</span></td>
	<td class="{verify_user_row.ROW_CLASS}" align="center" valign="top"><span class="gensmall">{verify_user_row.OUTCOME}</span></td>
  </tr>
<!-- END verify_user_row -->
</table>
<br />

<table width="100%" cellpadding="4" cellspacing="1" class="forumline">
<tr> 
	<th class="thCornerL">{L_GROUP_ID}</th>
	<th class="thTop">{L_GROUPNAME}</th>
	<th class="thTop">{L_MESSAGE}</th>
	<th class="thCornerR">{L_OUTCOME}</th>
</tr>
<!-- BEGIN verify_group_row -->
  <tr> 
	<td class="{verify_group_row.ROW_CLASS}" align="center" valign="top"><span class="gensmall">{verify_group_row.GROUP_ID}</span></td>
	<td class="{verify_group_row.ROW_CLASS}" align="left" valign="top"><span class="gensmall">{verify_group_row.GROUPNAME}</span></td>
	<td class="{verify_group_row.ROW_CLASS}" align="left" valign="top"><span class="gensmall">{verify_group_row.MESSAGE}</span></td>
	<td class="{verify_group_row.ROW_CLASS}" align="center" valign="top"><span class="gensmall">{verify_group_row.OUTCOME}</span></td>
  </tr>
<!-- END verify_group_row -->
</table>

<p>{L_VERIFY_END}</p>