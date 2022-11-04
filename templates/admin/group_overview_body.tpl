{GROUP_MENU}{PERMS_MENU}{SUBSCRIPTION_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<style type="text/css">
<!--
.goline	{ background-color: #FFFFFF; border: 1px solid #000000; }
-->
</style>

<script language="javascript" type="text/javascript">
<!--
function handleClick(id) 
{
	var obj = '';	

	// Check browser compatibility
	if(document.getElementById)
		obj = document.getElementById(id);
	else if(document.all)
		obj = document.all[id];
	else if(document.layers)
		obj = document.layers[id];
	else
		return 1;

	if (!obj) {
		return 1;
	}
	else if (obj.style) 
	{			
		obj.style.display = ( obj.style.display != "none" ) ? "none" : "";
	}
	else 
	{ 
		obj.visibility = "show"; 
	}
}
//-->
</script>

<h1>{L_GO_TITLE}</h1>

<p>{L_GO_TEXT}</p>

<!-- BEGIN msg -->
<table width="100%" cellspacing="1" cellpadding="4" class="forumline" align="center">
<tr>
	<td class="successpage">{msg.MSG}</td>
</tr>
</table>
<br />
<!-- END msg -->

<table width="100%" cellpadding="2" cellspacing="2"><form action="{S_NEW_GROUP_FORM}" method="post">
<tr>
	<td><input type="submit" name="new" class="liteoption" value="{L_NEW_GROUP}" /></td>
</tr>
</form></table>

<table width="100%"cellpadding="4" cellspacing="1" class="forumline" align="center">
<tr> 
	<th class="thCornerL" align="center">&nbsp;{L_GO_GROUP}&nbsp;</th>
	<th class="thTop" align="center">&nbsp;{L_GO_MOD}&nbsp;</th>
 	<th class="thTop" align="center">&nbsp;{L_GO_USER}&nbsp;</th>
 	<th class="ThTop" align="center">&nbsp;{L_GO_STATUS}&nbsp;</th>
 	<th class="thCornerR" align="center">&nbsp;{L_ACTION}&nbsp;</th>
</tr>
<!-- BEGIN groups -->
<tr> 
<form action="{groups.S_GROUP_ACTION}" method="post" name="post">
	<td class="{groups.ROW_CLASS}"><b>{groups.GROUP}</b><br /><span class="gensmall">{groups.GROUP_DESCRIPTION}</span></td>
	<td align="center" class="{groups.ROW_CLASS}"><a href="{groups.U_MOD}" class="gensmall">{groups.MOD}</a></td>
    	<td align="center" class="{groups.ROW_CLASS}"><span class="postdetails">{groups.USERS}</span></td>
    	<td align="center" class="{groups.ROW_CLASS}" align="center"><span class="gensmall">{groups.STATUS}</span></td>
	<td width="15%" class="{groups.ROW_CLASS}" align="right" valign="middle"><a href="{groups.U_MOVE_UP}">{groups.L_MOVE_UP}</a> <a href="{groups.U_MOVE_DOWN}">{groups.L_MOVE_DOWN}</a> <a href="javascript:handleClick('group{groups.GROUP_ID}');">{L_GO_EDIT}</a></td>
</tr>
<tr id="group{groups.GROUP_ID}" style="display: none">
	<td class="{groups.ROW_CLASS}" valign="top" align="center"><span class="gensmall"><a href="{groups.U_PERMISSION}">{L_PERMISSION}</a><br /><a href="{groups.U_INFORM}">{L_INFORM}</a></span></td>
	<td class="{groups.ROW_CLASS}" colspan="4"><table width="100%" cellpadding="4" cellspacing="1" class="goline">
	<tr>
		<td class="{groups.ROW_CLASS}"><b>{L_GROUP_NAME}:</b><br /><input class="post" type="text" name="group_name" size="35" maxlength="40" value="{groups.GROUP}" /></td>
	</tr>
	<tr> 
		<td class="{groups.ROW_CLASS}"><b>{L_GROUP_DESCRIPTION}:</b><br /><textarea class="post" name="group_description" wrap="virtual" rows="5" cols="35" />{groups.GROUP_DESCRIPTION}</textarea></td>
	</tr>
	<tr> 
		<td class="{groups.ROW_CLASS}"><b>{L_GROUP_MODERATOR}:</b><br /><input class="post" type="text" class="post" name="group_mod" maxlength="35" size="20" value="{groups.MOD2}" /></td>
	</tr>
	<tr> 
	  	<td class="{groups.ROW_CLASS}"><b>{L_GROUP_STATUS}:</b><br /><input type="radio" name="group_type" value="{groups.S_GROUP_OPEN_TYPE}" {groups.S_GROUP_OPEN_CHECKED} /> {L_GROUP_OPEN}&nbsp;&nbsp;<input type="radio" name="group_type" value="{groups.S_GROUP_CLOSED_TYPE}" {groups.S_GROUP_CLOSED_CHECKED} /> {L_GROUP_CLOSED}&nbsp;&nbsp;<input type="radio" name="group_type" value="{groups.S_GROUP_HIDDEN_TYPE}" {groups.S_GROUP_HIDDEN_CHECKED} /> {L_GROUP_HIDDEN}&nbsp;&nbsp;<input type="radio" name="group_type" value="{groups.S_GROUP_PAYMENT_TYPE}" {groups.S_GROUP_PAYMENT_CHECKED} /> {L_GROUP_PAYMENT}</td> 
	</tr>
	<tr> 
		<td class="{groups.ROW_CLASS}"><b>{L_GROUP_VALIDATE}:</b><br /><input type="radio" name="group_validate" value="1"{groups.GROUP_VALIDATE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="group_validate" value="0"{groups.GROUP_VALIDATE_NO} /> {L_NO}</td> 
	</tr>
	<tr>
		<td class="{groups.ROW_CLASS}"><b>{L_GROUP_MEMBERS_COUNT}:</b><br /><input type="radio" name="group_members_count" value="1" {groups.GROUP_MEMBERS_COUNT_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="group_members_count" value="0" {groups.GROUP_MEMBERS_COUNT_NO} /> {L_NO}</td>
	</tr>
	<tr> 
	  	<td class="{groups.ROW_CLASS}"><b>{L_DELETE_MODERATOR}</b><br /><input type="checkbox" name="delete_old_moderator" value="1"> {L_YES}</td>
	</tr>
  	<tr> 
		<td class="{groups.ROW_CLASS}"><b>{L_MEMBERS}:</b>&nbsp;<span class="gensmall">{L_MEMBERS_EXPLAIN}</span><br />{groups.GROUP_MEMBERS}</td>
	</tr>
  	<tr>
		<td class="{groups.ROW_CLASS}"><b>{L_ADD_MEMBER}:</b><br /><input type="text" class="post" name="username" maxlength="35" size="20" /> <input type="submit" name="add" value="{L_ADD_NEW}" class="mainoption" /></td>
	</tr>
	<tr> 
		<td class="{groups.ROW_CLASS}"><b>{L_GROUP_DELETE_USERS}:</b>&nbsp;<span class="gensmall">{L_GROUP_DELETE_USERS_EXPLAIN}</span><br /><input type="checkbox" name="group_delete_users" value="1"> {L_GROUP_DELETE_USERS_CHECK}</td>
	</tr>
	<tr> 
		<td class="{groups.ROW_CLASS}"><b>{L_GROUP_DELETE}:</b><br /><input type="checkbox" name="group_delete" value="1"> {L_GROUP_DELETE_CHECK}</td>
	</tr>
	<tr>
 		<td class="catBottom" align="center">{groups.S_HIDDEN_FIELDS}<input type="submit" name="submit" class="mainoption" value="{L_SUBMIT}" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
	</tr>
  	</table></td>
</form>
</tr>
<!-- END groups -->
</table>
<br />
<div align="center" class="copyright">Group Overview 1.0.0 &copy 2004, {COPYRIGHT_YEAR} <a href="http://www.leuchte.net/" class="copyright" target="_blank">Leuchte</a></div>
