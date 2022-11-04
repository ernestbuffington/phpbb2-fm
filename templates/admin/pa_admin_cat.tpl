{DOWNLOAD_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_CAT_TITLE}</h1>

<p>{L_CAT_EXPLAIN}</p>

<table width="100%" cellpadding="4" cellspacing="1" class="forumline" align="center"><form method="post" action="{S_CAT_ACTION}">
  <tr> 
	<td class="catHead" align="center" colspan="2">{S_HIDDEN_FIELDS}<input type="submit" class="mainoption" name="addcategory" value="{L_CREATE_CATEGORY}" /></td>
  </tr>
  <tr>
	<th class="thHead" colspan="2">{L_CAT_TITLE}</th>
  </tr>
<!-- BEGIN cat_row -->
<!-- IF cat_row.IS_HIGHER_CAT -->
<tr>
	<td class="catLeft" valign="middle">{cat_row.PRE}&nbsp;&raquo;&nbsp;<a href="{cat_row.U_CAT}" class="cattitle">{cat_row.CAT_NAME}</a></td>
	<td class="catRight" width="15%" align="right" nowrap="nowrap"><a href="{cat_row.U_CAT_MOVE_UP}">{L_MOVE_UP}</a> <a href="{cat_row.U_CAT_MOVE_DOWN}">{L_MOVE_DOWN}</a> <a href="{cat_row.U_CAT_EDIT}">{L_EDIT}</a> <a href="{cat_row.U_CAT_RESYNC}">{L_RESYNC}</a> <a href="{cat_row.U_CAT_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- ELSE -->
<tr>
	<td class="row1" width="85%" valign="middle">{cat_row.PRE}&nbsp;&raquo;&nbsp;<a href="{cat_row.U_CAT}" class="cattitle">{cat_row.CAT_NAME}</a></td>
	<td class="row2" width="15%" nowrap="nowrap" align="right"><a href="{cat_row.U_CAT_MOVE_UP}">{L_MOVE_UP}</a> <a href="{cat_row.U_CAT_MOVE_DOWN}">{L_MOVE_DOWN}</a> <a href="{cat_row.U_CAT_EDIT}">{L_EDIT}</a> <a href="{cat_row.U_CAT_RESYNC}">{L_RESYNC}</a> <a href="{cat_row.U_CAT_DELETE}">{L_DELETE}</a></td>	
</tr>
<!-- ENDIF -->
<!-- END cat_row -->
</form></table>
<br />
<div align="center" class="copyright">paFileDB phpBB Integration 0.0.9d &amp; <a href="http://www.mx-system.com/" target="_blank" class="copyright">MX Addon 1.2</a> &copy; 2003, {COPYRIGHT_YEAR} Mohd & Haplo</a></div>
