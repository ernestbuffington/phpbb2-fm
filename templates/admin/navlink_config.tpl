{CONFIG_MENU}{USERCOM_MENU}{SERVER_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_MENU_TITLE}</h1>

<P>{L_MENU_TEXT}</p>

<table width="100%" cellspacing="2" cellpadding="2" align="center"><form method="post" action="{S_MENU_ACTION}">
<tr>
	<td><input type="submit" name="add" value="{L_MENU_ADD}" class="liteoption" /></td>
</tr>
</form></table>
<table width="100%" cellspacing="1" cellpadding="4" align="center" class="forumline">
<tr>
	<th class="thCornerL">&nbsp;{L_NAME}&nbsp;</th>
	<th class="thTop">&nbsp;{L_URL}&nbsp;</th>
	<th class="thCornerR" width="15%">&nbsp;{L_ACTION}&nbsp;</th>
</tr>
<!-- BEGIN menu -->
<tr>
	<td class="{menu.ROW_CLASS}" valign="middle"><img src="{menu.MENU_IMG}" alt="{menu.MENU_ALT}" title="{menu.MENU_ALT}" hspace="3" />{menu.NAME}</td>
	<td class="{menu.ROW_CLASS}">{menu.URL}</td>
	<td class="{menu.ROW_CLASS}" align="right" nowrap="nowrap"><a href="{menu.U_MENU_STATUS}">{menu.L_STATUS}</a> <a href="{menu.U_MOVE_UP}">{L_MOVE_UP}</a> <a href="{menu.U_MOVE_DOWN}">{L_MOVE_DOWN}</a> <a href="{menu.U_MENU_EDIT}">{L_EDIT}</a> <a href="{menu.U_MENU_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- END menu -->
</table>
<br />
<div align="center" class="copyright">Admin Navlink Configuration 1.0.0 &copy; 2003, {COPYRIGHT_YEAR} <a href="http://phpbb-fm.com" target="_blank" class="copyright">MJ</a></div>