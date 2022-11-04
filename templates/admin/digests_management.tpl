{DIGESTS_MENU}{EMAIL_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<script language="javascript" type="text/javascript">
<!--

function handleClick(id)
{
	var obj = "";	

	// Check browser compatibility
	if(document.getElementById)
		obj = document.getElementById(id);
	else if(document.all)
		obj = document.all[id];
	else if(document.layers)
		obj = document.layers[id];
	else
		return 1;

	if (!obj) 
	{
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

<h1>{L_PAGE_TITLE}</h1>

<p>{L_PAGE_DESCRIPTION}</p>

<table width="100%" cellpadding="2" cellspacing="2" align="center"><form action="{S_ACTION}" method="post">
<tr> 
	<td valign="bottom" class="nav"><b>{PAGINATION}</td>	
	<td align="right" nowrap="nowrap"><span class="genmed">{L_SELECT_SORT_METHOD}:&nbsp;{S_MODE_SELECT}&nbsp;&nbsp;{L_ORDER}:&nbsp;{S_ORDER_SELECT}&nbsp;&nbsp; <input type="submit" name="submit" value="{L_SUBMIT}" class="liteoption" /><input type="hidden" name="alpha" value="{S_ALPHA_VALUE}" /></span></td>
</tr>
</table>

<table width="100%" cellspacing="1" cellpadding="4" align="center" class="forumline"> 
<tr> 
	<!-- BEGIN alpha_search --> 
	<td class="row1" width="{alpha_search.SEARCH_SIZE}" align="center"><a href="{alpha_search.SEARCH_LINK}">{alpha_search.SEARCH_TERM}</a></span></td> 
	<!-- END alpha_search --> 
</tr> 
</table>

<table width="100%" cellpadding="4" cellspacing="1" class="forumline" align="center">
<tr> 
	<th class="thHead" colspan="8">{L_USER_CONTROL_PANEL}</th>
</tr>
<tr> 
	<td class="catLeft" align="center"><span class="cattitle">#</span></td>
	<td class="cat" align="center"><span class="cattitle">{L_USERNAME}</span></td>
	<td class="cat" align="center"><span class="cattitle">{L_LAST_DIGEST}</span></td>
	<td class="cat" align="center"><span class="cattitle">{L_FREQUENCY}</span></td>
	<td class="cat" align="center" nowrap="nowrap"><span class="cattitle">{L_STATUS}</span></td>
	<td class="catRight" align="center" width="15%"><span class="cattitle">{L_ACTION}</span></td>
</tr>
<!-- BEGIN digest_row -->
<tr> 
	<td class="{digest_row.ROW_CLASS}" align="center">{digest_row.COUNT}</td>
	<td class="{digest_row.ROW_CLASS}"><a href="javascript:handleClick('digest{digest_row.DIGEST_ID}');" class="genmed">{digest_row.USERNAME}</a></td>
	<td class="{digest_row.ROW_CLASS}" align="center">{digest_row.LAST_DATE}<br />{digest_row.LAST_TIME}</td>
	<td class="{digest_row.ROW_CLASS}" align="center">{digest_row.FREQUENCY}</td>
	<td class="{digest_row.ROW_CLASS}" align="center">{digest_row.ACTIVITY}<br /><a href="{digest_row.ACTIVITY_URL}" class="genmed">[{digest_row.ALT_ACTIVITY}]</a></td>
	<td class="{digest_row.ROW_CLASS}" align="right" nowrap="nowrap"><a href="{digest_row.UNSUBSCRIBE_URL}" class="genmed">{L_UNSUBSCRIBE}</a> <a href="{digest_row.RESET_URL}" class="genmed">{L_RESET}</a> <a href="{digest_row.EDIT_URL}">{L_EDIT}</a></td>
</tr>
<tr id="digest{digest_row.DIGEST_ID}" style="display: none">
	<td class="{digest_row.ROW_CLASS}" width="10%">&nbsp;</td>
	<td class="{digest_row.ROW_CLASS}" colspan="5" width="100%"><table width="100%" cellpadding="4" cellspacing="1">
	<tr> 
		<td class="catLeft" colspan="4" align="center" width="45%"><span class="cattitle">{L_DIGEST_DATA}</span></td>
		<td class="cat" align="center" width="40%"><span class="cattitle">{L_FORUMS}</span></td>
		<td class="catRight" align="center" width="15%"><span class="cattitle">{L_CONFIRM_STATUS}</span></td>
	</tr>
	<tr>
		<td class="{digest_row.ROW_CLASS}" width="16%" valign="top"><b>{L_NAME}:</b></td>
		<td class="{digest_row.ROW_CLASS}" colspan="3" valign="top">{digest_row.DIGEST_NAME}</td>				
		<td class="{digest_row.ROW_CLASS}" width="40%" rowspan="4" valign="top">{digest_row.FORUM_NAME}</td>
		<td class="{digest_row.ROW_CLASS}" align="center" width="15%" valign="top">{digest_row.CONFIRM_STATUS}</td>
	</tr>
	<tr>
		<td class="{digest_row.ROW_CLASS}" valign="top"><b>{L_FORMAT}:</b></td>
		<td class="{digest_row.ROW_CLASS}" width="6%" valign="top">{digest_row.DIGEST_FORMAT}</td>
		<td class="{digest_row.ROW_CLASS}" width="17%" valign="top"><b>{L_TEXT}:</b></td>
		<td class="{digest_row.ROW_CLASS}" width="6%" valign="top">{digest_row.DIGEST_SHOW_TEXT}</td>
	</tr>
	<tr>
		<td class="{digest_row.ROW_CLASS}" valign="top"><b>{L_NEW}:</b></td>
		<td class="{digest_row.ROW_CLASS}" width="6%" valign="top">{digest_row.DIGEST_NEW_ONLY}</td>
		<td class="{digest_row.ROW_CLASS}" width="17%" valign="top"><b>{L_MINE}&nbsp;:</b></td>
		<td class="{digest_row.ROW_CLASS}" width="6%" valign="top">{digest_row.DIGEST_SHOW_MINE}</td>
	</tr>
	<tr>
		<td class="{digest_row.ROW_CLASS}" valign="top"><b>{L_NO_MESSAGE}:</b></td>
		<td class="{digest_row.ROW_CLASS}" width="6%" valign="top">{digest_row.DIGEST_NO_MESSAGE}</td>
		<td class="{digest_row.ROW_CLASS}" width="16%" valign="top"><b>{L_FORUMS_INCLUDED}</b></td>
		<td class="{digest_row.ROW_CLASS}" width="6%" valign="top">{digest_row.INCLUDE_FORUM}</td>
	</tr>
	</table></td>
</tr>
<!-- END digest_row -->
</form></table>
<br />

<table width="100%" cellpadding="4" cellspacing="1" class="forumline" align="center">
<tr> 
	<th class="thHead" colspan="7">{L_GROUP_CONTROL_PANEL}</th>
</tr>
<tr> 
	<td class="catLeft"><span class="cattitle">{L_GROUP_NAME}</span></td>
	<td class="cat" align="center"><span class="cattitle">{L_LAST_DIGEST}</span></td>
	<td class="cat" align="center"><span class="cattitle">{L_FREQUENCY}</span></td>
	<td class="cat" align="center" nowrap="nowrap"><span class="cattitle">{L_STATUS}</span></td>
	<td class="cat" align="center"><span class="cattitle">{L_ACTION}</span></td>
	<td class="catRight" align="center"><span class="cattitle">{L_RESET}</span></td>
</tr>
<!-- BEGIN digest_group_row -->
<tr> 
	<td class="{digest_group_row.ROW_CLASS}"><a href="javascript:handleClick('group{digest_group_row.DIGEST_ID}');" class="genmed">{digest_group_row.GROUP_NAME}</a></td>
	<td class="{digest_group_row.ROW_CLASS}" align="center">{digest_group_row.LAST_DATE}<br />{digest_group_row.LAST_TIME}</td>
	<td class="{digest_group_row.ROW_CLASS}" align="center">{digest_group_row.FREQUENCY}</td>
	<td class="{digest_group_row.ROW_CLASS}" align="center">{digest_group_row.ACTIVITY}<br /><a href="{digest_group_row.ACTIVITY_URL}" class="genmed">{digest_group_row.ALT_ACTIVITY}</a></td>
	<td class="{digest_group_row.ROW_CLASS}" align="center"><a href="{digest_group_row.EDIT_URL}">{L_EDIT}</a></td>
	<td class="{digest_group_row.ROW_CLASS}" align="center"><a href="{digest_group_row.RESET_URL}" class="genmed">{L_RESET}</a></td>
</tr>
<tr id="group{digest_group_row.DIGEST_ID}" style="display: none">
	<td class="{digest_group_row.ROW_CLASS}" width="10%">&nbsp;</td>
	<td class="{digest_group_row.ROW_CLASS}" colspan="7" width="100%"><table width="100%" cellpadding="4" cellspacing="1">
	<tr> 
		<td class="catLeft" colspan="4" align="center" width="45%"><span class="cattitle">{L_DIGEST_DATA}</td>
		<td class="cat" align="center" width="25%"><span class="cattitle">{L_FORUMS}</td>
		<td class="cat" align="center" width="15%"><span class="cattitle">{L_GROUP_MEMBERS}</td>
		<td class="catRight" align="center" width="15%"><span class="cattitle">{L_CONFIRM_STATUS}</td>
	</tr>
	<tr>
		<td class="{digest_group_row.ROW_CLASS}" width="10%" valign="top"><b>{L_NAME}:</b></td>
		<td class="{digest_group_row.ROW_CLASS}" width="20%" valign="top" colspan="3">{digest_group_row.DIGEST_NAME}</td>				
		<td class="{digest_group_row.ROW_CLASS}" width="25%" rowspan="4" valign="top">{digest_group_row.FORUM_NAME}</td>
		<td class="{digest_group_row.ROW_CLASS}" width="15%" rowspan="4" valign="top">{digest_group_row.USER_NAME_LIST}</td>
		<td class="{digest_group_row.ROW_CLASS}" align="center" width="15%" rowspan="4" valign="top">{digest_group_row.CONFIRM_LIST}</td>
	</tr>
	<tr>
		<td class="{digest_group_row.ROW_CLASS}" width="10%" valign="top"><b>{L_FORMAT}:</b></td>
		<td class="{digest_group_row.ROW_CLASS}" width="20%" valign="top">{digest_group_row.DIGEST_FORMAT}</td>
		<td class="{digest_group_row.ROW_CLASS}" width="10%" valign="top"><b>{L_TEXT}:</b></td>
		<td class="{digest_group_row.ROW_CLASS}" width="5%" valign="top">{digest_group_row.DIGEST_SHOW_TEXT}</td>
	</tr>
	<tr>
		<td class={digest_group_row.ROW_CLASS}" width="10%" valign="top"><b>{L_NEW}:</b></td>
		<td class={digest_group_row.ROW_CLASS}" width="20%" valign="top">{digest_group_row.DIGEST_NEW_ONLY}</td>
		<td class={digest_group_row.ROW_CLASS}" width="10%" valign="top"><b>{L_MINE}:</b></td>
		<td class={digest_group_row.ROW_CLASS}" width="5%" valign="top">{digest_group_row.DIGEST_SHOW_MINE}</td>
	</tr>
	<tr>
		<td class={digest_group_row.ROW_CLASS}" width="10%" valign="top"><b>{L_NO_MESSAGE}:</b></td>
		<td class={digest_group_row.ROW_CLASS}" width="5%" valign="top">{digest_group_row.DIGEST_NO_MESSAGE}</td>
		<td class={digest_row.ROW_CLASS}" width="16%" valign="top"><b>{L_FORUMS_INCLUDED}&nbsp;:</b></td>
		<td class={digest_row.ROW_CLASS}" width="6%" valign="top">{digest_row.INCLUDE_FORUM}</td>
	</tr>
	</table></td>
</tr>
<!-- END digest_group_row -->
</table>

<table width="100%" cellspacing="2" cellpadding="2" align="center">
<tr> 
	<td align="right" nowrap="nowrap" class="nav">{PAGINATION}</td>
</tr>
</table>