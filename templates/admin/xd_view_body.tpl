{USER_MENU}
{CUSTOM_PROFILE_MENU}
{BAN_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_XDATA_ADMIN}</h1>

<p>{L_FORM_DESCRIPTION}</p>

<table width="100%" cellpadding="4" cellspacing="1" class="forumline" align="center">
<tr>
	<th width="60%" class="thCornerL" nowrap="nowrap">&nbsp;{L_FIELD_NAME}&nbsp;</th>
	<th width="15%" class="thTop" nowrap="nowrap">&nbsp;{L_FIELD_TYPE}&nbsp;</th>
	<th width="15%" class="thCornerR" nowrap="nowrap">&nbsp;{L_ACTION}&nbsp;</th>
</tr>
<!-- BEGIN xd_field -->
<tr>
	<td class="row1">{xd_field.FIELD_NAME}</td>
	<td class="row2" align="center">{xd_field.FIELD_TYPE}</td>
	<td class="row2" align="right" nowrap="nowrap">
	<a href="{xd_field.U_MOVE_UP}">{L_MOVE_UP}</a> <a href="{xd_field.U_MOVE_DOWN}">{L_MOVE_DOWN}</a> <a href="{xd_field.U_EDIT}">{L_EDIT}</a> 
	<!-- BEGIN normal -->
	<a href="{xd_field.U_DELETE}">{L_DELETE}</a>
	<!-- END normal -->
	</td>
</tr>
<!-- END xd_field -->
<!-- BEGIN switch_no_fields -->
<tr>
	<td colspan="3" class="row1" align="center" valign="middle" height="30">{L_NO_FIELDS}</td>
</tr>
<!-- END switch_no_fields -->
</table>
<br />
<div align="center" class="copyright">Custom Profiles 0.1.1 &copy; 2003, {COPYRIGHT_YEAR} zayin</div>
