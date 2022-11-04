{STATS_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_MANAGE_MODULES}</h1>

<p>{L_MANAGE_MODULES_EXPLAIN}</p>

<table width="100%" cellpadding="4" cellspacing="1" class="forumline" align="center"><form method="post" action="{S_ACTION}">
<tr>
	<th class="thCornerL">{L_MANAGE_MODULES}</th>
	<th class="thCornerR" width="15%">{L_ACTION}</th>
</tr>
<!-- BEGIN modulerow -->
<tr> 
	<td class="row1"><a href="{modulerow.U_VIEW_MODULE}" target="_blank" class="forumlink">{modulerow.MODULE_NAME} {modulerow.MODULE_VERSION}</a><br /><span class="gensmall">{modulerow.MODULE_DESC}</span></td>
	<td class="row2" align="right" nowrap="nowrap"><a href="{modulerow.U_MODULE_ACTIVATE}">{modulerow.ACTIVATE}</a> <a href="{modulerow.U_MODULE_MOVE_UP}">{L_MOVE_UP}</a> <a href="{modulerow.U_MODULE_MOVE_DOWN}">{L_MOVE_DOWN}</a> <a href="{modulerow.U_MODULE_EDIT}">{L_EDIT}</a> <a href="{modulerow.U_MODULE_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- END modulerow -->
</form></table>
