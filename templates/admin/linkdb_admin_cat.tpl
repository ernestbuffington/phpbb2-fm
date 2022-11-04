{LINKDB_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_CAT_TITLE}</h1>

<p>{L_CAT_EXPLAIN}</p>

<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline"><form method="post" action="{S_CAT_ACTION}">
<tr>
	<th class="thHead" colspan="5">{L_CAT_TITLE}</th>
</tr>
<!-- BEGIN cat_row -->
<tr>
	<td class="{cat_row.COLOR}" valign="middle" nowrap="nowrap">{cat_row.PRE}&nbsp;&raquo;&nbsp;<a href="{cat_row.U_CAT}" class="cattitle">{cat_row.CAT_NAME}</a></td>
	<td class="{cat_row.COLOR}" align="center" valign="middle"><span class="gen"><a href="{cat_row.U_CAT_EDIT}">{L_EDIT}</a></span></td>
	<td class="{cat_row.COLOR}" align="center" valign="middle"><span class="gen"><a href="{cat_row.U_CAT_DELETE}">{L_DELETE}</a></span></td>
	<td class="{cat_row.COLOR}" align="center" valign="middle" nowrap="nowrap"><span class="gen"><a href="{cat_row.U_CAT_MOVE_UP}">{L_MOVE_UP}</a> <a href="{cat_row.U_CAT_MOVE_DOWN}">{L_MOVE_DOWN}</a></span></td>
	<td class="{cat_row.COLOR}" align="center" valign="middle"><span class="gen"><a href="{cat_row.U_CAT_RESYNC}">{L_RESYNC}</a></span></td>
</tr>
<!-- END cat_row -->
<tr> 
	<td class="catBottom" align="center" colspan="5">{S_HIDDEN_FIELDS}<input type="submit" class="mainoption"  name="addcategory" value="{L_CREATE_CATEGORY}" /></td>
</tr>
</form></table>