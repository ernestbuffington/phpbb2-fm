{BAN_MENU}
{USER_MENU}
{MOD_CP_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_DISALLOW_TITLE}</h1>

<p>{L_DISALLOW_EXPLAIN}</p>

<table width="100%" cellspacing="1" cellpadding="4" align="center" class="forumline"><form method="post" name="post" action="{S_FORM_ACTION}">
<tr> 
		<th class="thHead" colspan="2">{L_ADD_DISALLOW}</th>
	</tr>
	<tr> 
		<td class="row1" width="50%"><b>{L_USERNAME}:</b><br /><span class="gensmall">{L_ADD_EXPLAIN}</span></td>
		<td class="row2"><input class="post" type="text" name="username"{AJAXED_USER_LIST} maxlength="50" size="20" />&nbsp;<input type="submit" name="add_name" value="{L_ADD}" class="mainoption" />{AJAXED_USER_LIST_BOX}</td>
	</tr>
	<tr> 
		<th class="thHead" colspan="2">{L_DELETE_DISALLOW}</th>
	</tr>
	<tr> 
		<td class="row1"><b>{L_USERNAME}:</b><br /><span class="gensmall">{L_DELETE_EXPLAIN}</span></td>
		<td class="row2">{S_DISALLOW_SELECT}&nbsp;<input type="submit" name="delete_name" value="{L_DELETE}" class="liteoption" /></td>
	</tr>
	<tr> 
		<td class="catBottom" colspan="2">&nbsp;</td>
	</tr>
</form></table>
