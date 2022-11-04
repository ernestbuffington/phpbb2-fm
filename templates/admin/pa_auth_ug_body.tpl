{DOWNLOAD_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<table width="100%" cellpadding="4" cellspacing="1" class="forumline" align="center">
<tr>
	<!-- BEGIN pertype -->	
	<td class="cat" align="center" nowrap="nowrap"><a href="{pertype.U_NAME}" class="cattitle">{pertype.L_NAME}</a></td> 
	<!-- END pertype -->
</tr>
</table>  
<br />

<h1>{L_AUTH_TITLE}</h1>

<h2>{L_USER_OR_GROUPNAME}: {USERNAME}</h2>

<form method="post" action="{S_AUTH_ACTION}">

<!-- IF USER -->
<p>{USER_LEVEL}</p>
<p>{USER_GROUP_MEMBERSHIPS}</p>
<!-- ELSE -->
<p>{GROUP_MEMBERSHIP}</p>
<!-- ENDIF -->

<h2>{L_PERMISSIONS}</h2>

<p>{L_AUTH_EXPLAIN}</p>

<table width="100%" cellpadding="4" cellspacing="1" class="forumline" align="center">
<tr> 
	<th width="30%" class="thCornerL">{L_CAT}</th>
	<!-- BEGIN acltype -->
	<th class="thTop">{acltype.L_UG_ACL_TYPE}</th>
	<!-- END acltype -->
	<!-- IF SHOW_MOD -->
	<th class="thCornerR">{L_MODERATOR_STATUS}</th>
	<!-- ENDIF -->
</tr>
<!-- BEGIN cat_row -->
	<tr> 
	<!-- IF cat_row.IS_HIGHER_CAT -->	
      	<td class="cat" align="left" nowrap="nowrap">{cat_row.PRE}&nbsp;&raquo;&nbsp;<a href="{cat_row.U_CAT}">{cat_row.CAT_NAME}</a></td> 
	<!-- ELSE -->
	<td class="row1" align="left" nowrap="nowrap">{cat_row.PRE}&nbsp;&raquo;&nbsp;<a href="{cat_row.U_CAT}">{cat_row.CAT_NAME}</a></td> 
	<!-- ENDIF -->
	  
	<!-- BEGIN aclvalues -->
	<!-- IF cat_row.IS_HIGHER_CAT -->	
	<td class="cat" align="center">{cat_row.aclvalues.S_ACL_SELECT}</td>
	<!-- ELSE -->
	<td class="row1" align="center">{cat_row.aclvalues.S_ACL_SELECT}</td>
	<!-- ENDIF -->
	<!-- END aclvalues -->
	<!-- IF SHOW_MOD -->	
	<!-- IF cat_row.IS_HIGHER_CAT -->	
	<td class="cat" align="center">{cat_row.S_MOD_SELECT}</td>
	<!-- ELSE -->
	<td class="row1" align="center">{cat_row.S_MOD_SELECT}</td>
	<!-- ENDIF -->
	<!-- ENDIF -->
</tr>
<!-- END cat_row -->
<tr>
	<td colspan="{S_COLUMN_SPAN}" class="catBottom" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" name="reset" /></td>
</tr>
</form></table>
<br />
<div align="center" class="copyright">paFileDB phpBB Integration 0.0.9d &amp; <a href="http://www.mx-system.com/" target="_blank" class="copyright">MX Addon 1.2</a> &copy; 2003, {COPYRIGHT_YEAR} Mohd & Haplo</a></div>
