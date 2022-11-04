{USER_MENU}{CUSTOM_PROFILE_MENU}{PERMS_MENU}{BAN_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<script language="Javascript" type="text/javascript">
<!-- 
function setCheckboxes(theForm, elementName, isChecked)
{
    var chkboxes = document.forms[theForm].elements[elementName];
    var count = chkboxes.length;

    if (count) 
	{
        for (var i = 0; i < count; i++) 
		{
            chkboxes[i].checked = isChecked;
    	}
    } 
	else 
	{
    	chkboxes.checked = isChecked;
    } 

    return true;
} 
//--> 
</script>

<h1>{L_USER_LIST_TITLE}</h1>

<p>{L_USER_LIST_DESCRIPTION}</p>

<p>{L_MESSAGE}</p>

<table width="100%" align="center" cellpadding="2" cellspacing="2"><form method="post" action="{S_MODE_ACTION}">
<tr>
	<td align="right"><span class="genmed">{L_SHOW}: <input type="text" name="amount" value="{S_AMOUNT}" size="2" class="post"> {L_SELECT_SORT_METHOD}:&nbsp;{S_MODE_SELECT}&nbsp;&nbsp;{L_ORDER}:&nbsp;{S_ORDER_SELECT}&nbsp;&nbsp;<input type="submit" name="submit" value="{L_SORT}" class="liteoption" /></span></td>
</tr>
</form></table>
<table width="100%" class="forumline" align="center" cellspacing="1" cellpadding="4" align="center">
<tr> 
	<!-- BEGIN alphanumsearch --> 
	<td class="row1" align="center" width="{alphanumsearch.SEARCH_SIZE}"><a href="{alphanumsearch.SEARCH_LINK}">{alphanumsearch.SEARCH_TERM}</a></td> 
	<!-- END alphanumsearch --> 
</tr> 
</table>
<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline"><form method="post" action="{S_ACTION}" name="member_form" id="member_form">
<tr>
	
	<th class="thCornerL" nowrap="nowrap">{L_USERNAME}</th>
	<th class="thTop" nowrap="nowrap">{L_ACTIVE} </th>
	<th class="thTop" nowrap="nowrap">{L_JOINED}</th>
	<th class="thTop" nowrap="nowrap">{L_LAST_VISIT}</th>
	<th class="thTop" nowrap="nowrap">{L_POSTS}</th>	  
	<th class="thTop" nowrap="nowrap">{L_USERGROUP}</th>
	<th colspan="2" class="thCornerR" nowrap="nowrap">{L_ACTION}</th>
</tr>
<!-- BEGIN memberrow -->
<tr>
	<td class="{memberrow.ROW_CLASS}" width="20%"><a href="{memberrow.U_VIEWPROFILE}">{memberrow.USERNAME}</a></td>
	<td class="{memberrow.ROW_CLASS}" align="center">{memberrow.ACTIVE}</td>
	<td class="{memberrow.ROW_CLASS}" align="center"><span class="gensmall">{memberrow.JOINED}</span></td>
	<td class="{memberrow.ROW_CLASS}" align="center"><span class="gensmall">{memberrow.LAST_VISIT}</span></td>
	<td class="{memberrow.ROW_CLASS}" align="center"><a href="{memberrow.U_SEARCH_POST}">{memberrow.POSTS}</a></td>
	<td class="{memberrow.ROW_CLASS}">{memberrow.GROUP}&nbsp;</td>
	<td class="{memberrow.ROW_CLASS}" align="right">{memberrow.PERMISSION} {memberrow.EDIT_PROFILE} {memberrow.EMAIL} {memberrow.PM}</td>	  
	<td class="{memberrow.ROW_CLASS}" align="center"><input type="checkbox" name="user_id_list[]" value="{memberrow.USER_ID}"></td>
</tr>
<!-- END memberrow -->
</table>
<table align="center" width="100%" cellspacing="2" cellpadding="2">
<tr>
	<td align="right" colspan="2"><select name="mode">
		<option value="NONE">{S_SELECT_ONE}</option>
		<option value="activate">{S_ACTIVATE}</option>
		<option value="ban">{S_BAN}</option>
		<option value="delete">{L_DELETE}</option>
	</select> &nbsp;<input type="submit" value="{L_SUBMIT}" name="submit" class="liteoption" /></td>
</tr>
<tr> 
	<td class="nav">{PAGE_NUMBER}<br />{PAGINATION}</td>
	<td align="right"><b class="gensmall"><a href="#" onclick="setCheckboxes('member_form', 'user_id_list[]', true); return false;" class="gensmall">{L_MARK_ALL}</a> :: <a href="#" onclick="setCheckboxes('member_form', 'user_id_list[]', false); return false;" class="gensmall">{L_UNMARK_ALL}</a></b></td>
</tr>
</form></table>
