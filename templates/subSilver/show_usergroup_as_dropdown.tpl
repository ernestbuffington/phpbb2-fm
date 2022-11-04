<table cellpadding="0" cellspacing="0"><form action="{U_GROUP_CP}" name="ShowGroupFrm" method="post">
<tr> 
	<!-- BEGIN group -->
	<td align="center"><a href="{group.U_GROUP}" class="gensmall">{group.GROUP_IMG}</a></td>
	<!-- END group -->
</tr>
<tr>
	<td><select name="g" onChange="submit();">
	<!-- BEGIN multi_group -->
	<option value="">{multi_group.L_SELECT_GROUP}</option>
	<!-- END multi_group -->
	<!-- BEGIN group -->
	<option value="{group.GROUP_ID}">
		<!-- BEGIN is_hidden -->
		*
		<!-- END is_hidden -->
		{group.GROUP_NAME}</option>
	<!-- END group -->
	</select>
	</td> 
</tr>
</form></table>