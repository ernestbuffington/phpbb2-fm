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

<p>{L_AUTH_EXPLAIN}</p>

<h2>{L_CATEGORY}<!-- IF CATEGORY_NAME neq '' --> : {CATEGORY_NAME}<!-- ENDIF --></h2>

<table width="100%" cellpadding="4" cellspacing="1" class="forumline" align="center"><form method="post" action="{S_CATAUTH_ACTION}">
<tr>
	<th class="thTop">{L_CATEGORY}</th>
	<!-- BEGIN cat_auth_titles -->
	<th class="thTop">{cat_auth_titles.CELL_TITLE}</th>
	<!-- END cat_auth_titles -->
</tr>
<!-- BEGIN cat_row -->
<tr>
	<!-- IF cat_row.IS_HIGHER_CAT -->	
      	<td class="cat" align="left" nowrap="nowrap">{cat_row.PRE}&nbsp;&raquo;&nbsp;<a href="{cat_row.U_CAT}">{cat_row.CATEGORY_NAME}</a></td> 
	<!-- ELSE -->
	<td class="row1" align="left" nowrap="nowrap">{cat_row.PRE}&nbsp;&raquo;&nbsp;<a href="{cat_row.U_CAT}">{cat_row.CATEGORY_NAME}</a></td> 
	<!-- ENDIF -->
	  
	<!-- BEGIN cat_auth_data -->
	<!-- IF cat_row.IS_HIGHER_CAT -->	
	<td class="cat" align="center">{cat_row.cat_auth_data.S_AUTH_LEVELS_SELECT}</td>
	<!-- ELSE -->
	<td class="row1" align="center">{cat_row.cat_auth_data.S_AUTH_LEVELS_SELECT}</td>
	<!-- ENDIF -->
	<!-- END cat_auth_data -->
</tr>
<!-- END cat_row -->
<tr>
	<td colspan="{S_COLUMN_SPAN}" class="catBottom" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" name="reset" class="liteoption" /></td>
</tr>
</form></table>
<br />
<div align="center" class="copyright">paFileDB phpBB Integration 0.0.9d &amp; <a href="http://www.mx-system.com/" target="_blank" class="copyright">MX Addon 1.2</a> &copy; 2003, {COPYRIGHT_YEAR} Mohd & Haplo</a></div>
