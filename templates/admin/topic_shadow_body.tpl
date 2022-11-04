{TOPIC_MENU}{FORUM_MENU}{VOTE_MENU}
{MOD_CP_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<script language="JavaScript">
<!--
function toggle_check_all()
{
	for (var i=0; i < document.delete_ids.elements.length; i++)
	{
		var checkbox_element = document.delete_ids.elements[i];
		if ((checkbox_element.name != 'check_all_box') && (checkbox_element.type == 'checkbox'))
		{
			checkbox_element.checked = document.delete_ids.check_all_box.checked;
		}
	}
}
-->
</script>

<h1>{L_PAGE_NAME}</h1>

<p>{L_PAGE_DESC}</p>

<!-- BEGIN statusrow -->
<table width="100%" cellspacing="1" cellpadding="4" align="center" class="forumline">
<tr> 
	<th class="thHead">{L_STATUS}</th>
</tr>
<tr>
	<td class="row1" align="center"><b>{I_STATUS_MESSAGE}</b></td>
</tr>
</table>
<br />
<!-- END statusrow -->

<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline">
<tr>
	<form method="post" action="{S_MODE_ACTION}" name="sort_and_mode">
	<td align="center" class="catHead" colspan="6"><span class="genmed">{L_SELECT_SORT_METHOD}:&nbsp;{S_MODE_SELECT}&nbsp;&nbsp;{L_ORDER}:&nbsp;{S_ORDER_SELECT}&nbsp;&nbsp;<input type="submit" name="submit" value="{L_SUBMIT}" class="liteoption" /></span></td>
	</form>
</tr>
<tr> 
	<form method="post" action="{S_MODE_ACTION}" name="delete_ids">
	<th class="thCornerL" align="center" width="3%"><input type="checkbox" name="check_all_box" onClick="toggle_check_all()"></th>
	<th class="thTop" width="45%">{L_TITLE}</th>
	<th class="thTop">{L_POSTER}</th>
	<th class="thTop">{L_TIME}</th>
	<th class="thTop">{L_MOVED_FROM}</th>
	<th class="thCornerR">{L_MOVED_TO}</th>
</tr>
<!-- BEGIN topicrow -->
<tr> 
	<td class="{topicrow.ROW_CLASS}" align="right"><input type="checkbox" name="delete_id_{topicrow.TOPIC_ID}"></td>
	<td class="{topicrow.ROW_CLASS}"  align="left">{topicrow.TITLE}</td>
	<td class="{topicrow.ROW_CLASS}" align="center" valign="middle">{topicrow.POSTER}</td>
	<td class="{topicrow.ROW_CLASS}" align="center" valign="middle"><span class="gensmall">{topicrow.TIME}</span></td>
	<td class="{topicrow.ROW_CLASS}" align="center" valign="middle"><span class="gensmall">{topicrow.MOVED_FROM}</span></td>
	<td class="{topicrow.ROW_CLASS}" align="center" valign="middle"><span class="gensmall">{topicrow.MOVED_TO}</span></td>
</tr>
<!-- END topicrow -->
<!-- BEGIN emptyrow -->
<tr>
	<td class="row1" align="center" height="30" colspan="6"><b>{L_NO_TOPICS_FOUND}</b></td>
</tr>
<!-- END emptyrow -->
<tr> 
	<td class="catBottom" colspan="6"><input type="submit" class="mainoption" value="{L_DELETE}" />&nbsp;&nbsp;<input type="reset" class="liteoption" value="{L_RESET}" /></td>
</tr>
</form></table>
<br />
  
<table align="center" cellpadding="4" cellspacing="1" class="forumline"><form method="post" action="{S_MODE_ACTION}" name="delete_all_before">
<tr>
	<th class="thHead" colspan="3">{L_DELETE_FROM_EXPLAN}</th>
</tr>
<tr>
	<td class="catLeft" align="center"><span class="cattitle">{L_MONTH}: 01 - 12</span></td>
	<td class="cat" align="center"><span class="cattitle">{L_DAY}: 01 - 31</span></td>
	<td class="catRight" align="center"><span class="cattitle">{L_YEAR}: 1970 - 2038</span></td>
</tr>
<tr>
	<td class="row1" width="33%" align="center"><input class="post" type="text" name="del_month" value="{S_MONTH}" size="2" maxlength="2"></td>
	<td class="row1" width="33%" align="center"><input class="post" type="text" name="del_day" value="{S_DAY}" size="2" maxlength="2"></td>
	<td class="row1" width="34%" align="center"><input class="post" type="text" name="del_year" value="{S_YEAR}" size="4" maxlength="4"></td>
</tr>
<tr> 
	<td class="catBottom" colspan="3" align="center"><input type="hidden" name="delete_all_before_date" value="1"><input type="hidden" name="mode" value="{S_MODE}"><input type="hidden" name="order" value="{S_ORDER}"><input type="submit" value="{L_DELETE_BEFORE}" class="mainoption" /></td>
</tr>
</form></table>
<br />
<div class="copyright" align="center">Topic Shadow 2.13 &copy; 2001, {COPYRIGHT_YEAR} <a href="http://www.nivisec.com" class="copyright" target="_blank">Nivisec.com</a></div>
