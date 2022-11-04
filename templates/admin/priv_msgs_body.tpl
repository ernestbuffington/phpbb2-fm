{USERCOM_MENU}{PM_MENU}
</div></td>
<td valign="top" width="78%">

<script language="JavaScript">
<!--
function toggle_check_all()
{
	var archive_text = "archive_id";
	
	for (var i=0; i < document.msgrow_values.elements.length; i++)
	{
		var checkbox_element = document.msgrow_values.elements[i];
		if ((checkbox_element.name != 'check_all_del_box') && (checkbox_element.name != 'check_all_arch_box') && (checkbox_element.type == 'checkbox'))
		{
			if (checkbox_element.name.search("archive_id") != -1)
			{		
				checkbox_element.checked = document.msgrow_values.check_all_arch_box.checked;
			}
			else
			{			
				checkbox_element.checked = document.msgrow_values.check_all_del_box.checked;			
			}
		}
	}
}
-->
</script>

<!-- BEGIN statusrow -->
<table width="100%" cellspacing="1" cellpadding="4" class="forumline" align="center">
<tr>
	<td class="successpage">{I_STATUS_MESSAGE}</td>
</tr>
</table>
<br />
<!-- END statusrow -->

{PM_MESSAGE}

<h1>{L_PAGE_NAME}</h1>

<p>{L_PAGE_DESC}</p>

<p>
<b>{L_SHOW_IP}:</b> - {URL_SHOW_IP_ON} | {URL_SHOW_IP_OFF}<br />
<b>{L_ROWS_PER_PAGE}:</b> ({L_CURRENT}: {CURRENT_ROWS}) - {URL_ROWS_PLUS_5} | {URL_ROWS_MINUS_5}<br />
<b>{L_ARCHIVE_FEATURE}:</b> - {URL_ARCHIVE_ENABLE_LINK} | {URL_ARCHIVE_DISABLE_LINK}
</p>

<p>{URL_SWITCH_MODE}</p>
  
<table width="100%" cellspacing="2" cellpadding="2" align="center"><form method="post" action="{S_MODE_ACTION}" name="sort_and_pmtype">
<input type="hidden" name="mode" value="{S_MODE}">
<tr>
	<td width="40%"><b class="gen">{L_UTILS}</b>
	<ul>
		<li><a href="{URL_ORPHAN}" class="genmed">{L_REMOVE_OLD}</a>
		<li><a href="{URL_SENT}" class="genmed">{L_REMOVE_SENT}</a>
	</ul></td>
	<td align="right" nowrap="nowrap"><span class="genmed">{L_FILTER_BY}:&nbsp;{S_PMTYPE_SELECT}<br /><br />
	{L_SELECT_SORT_METHOD}:&nbsp;{S_MODE_SELECT}&nbsp;&nbsp;{L_ORDER}&nbsp;{S_ORDER_SELECT}<br /><br />
	{L_TO}:&nbsp;<input type="text" class="post" size="10" maxlength="32" name="filter_to" value="{S_FILTER_TO}">&nbsp;&nbsp;{L_FROM}:&nbsp;<input type="text" class="post" size="10" maxlength="32" name="filter_from" value="{S_FILTER_FROM}"></span></td>
	<td align="center" valign="middle" rowspan="2"><input type="submit" name="submit" value="{L_SORT}" class="liteoption" /></td>
</tr>
</form></table>

<table width="100%" cellspacing="2" cellpadding="2" align="center"><form method="post" action="{S_MODE_ACTION}" name="msgrow_values">
<tr> 
 	<td><input type="hidden" name="mode" value="{S_MODE}"><input type="submit" value="{L_SUBMIT}" class="mainoption">&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption"></td>
 </tr>
</table>

<table width="100%" cellpadding="4" cellspacing="1" class="forumline" align="center">
<tr> 
	<th class="thCornerL" width="5%" nowrap="nowrap">{L_DELETE} <input type="checkbox" name="check_all_del_box" onClick="JavaScript:{JS_ARCHIVE_COMMENT_1}check_all_arch_box.checked = false;{JS_ARCHIVE_COMMENT_2} toggle_check_all();"></th>
<!-- BEGIN archive_avail_switch -->
	<th class="thTop" width="5%" nowrap="nowrap">{L_ARCHIVE} <input type="checkbox" name="check_all_arch_box" onClick="JavaScript:check_all_del_box.checked = false; toggle_check_all();"></th>
<!-- END archive_avail_switch -->
	<th class="thTop" align="left">{L_SUBJECT}</th>
	<th class="thTop">{L_FROM}</th>
	<th class="thTop">{L_TO}</th>
	<th class="thTop">{L_SENT_DATE}</th>
	<th class="thCornerR">{L_PM_TYPE}</th>
</tr>
<tr>
	<td colspan="7" class="row3"><table width="100%" cellspacing="2" cellpadding="2" align="center">
	<tr> 
		<td valign="bottom" class="nav">{PAGE_NUMBER}</td>
		<td align="right" valign="bottom" class="nav">{PAGINATION}</td>
	  </tr>
	</table></td>
</tr>
<!-- BEGIN msgrow -->
<tr>  
	<td class="{msgrow.ROW_CLASS}" align="center"><input type="checkbox" name="delete_id_{msgrow.PM_ID}" onClick="JavaScript:{JS_ARCHIVE_COMMENT_1}archive_id_{msgrow.PM_ID}.checked = false{JS_ARCHIVE_COMMENT_2};"></td>
	<!-- BEGIN archive_avail_switch_msg -->
	<td class="{msgrow.ROW_CLASS}" align="center"><input type="checkbox" name="archive_id_{msgrow.PM_ID}" onClick="JavaScript:delete_id_{msgrow.PM_ID}.checked = false;"></td>
	<!-- END archive_avail_switch_msg -->
	<td class="{msgrow.ROW_CLASS}"><a href="{msgrow.U_INLINE_VIEWMSG}" onClick="{msgrow.U_VIEWMSG}">{msgrow.SUBJECT}</a></td>
	<td class="{msgrow.ROW_CLASS}" align="center">{msgrow.FROM}<span class="gensmall">{msgrow.FROM_IP}</span></td>
	<td class="{msgrow.ROW_CLASS}" align="center">{msgrow.TO}<span class="gensmall"></span></td>
	<td class="{msgrow.ROW_CLASS}" align="center"><span class="gensmall">{msgrow.DATE}</span></td>
	<td class="{msgrow.ROW_CLASS}" align="center">{msgrow.PM_TYPE}</td>
</tr>
<!-- END msgrow -->
<!-- BEGIN empty_switch -->
<tr>
	<td colspan="7" class="errorpage">{L_NO_PMS}</td>
</tr>
<!-- END empty_switch -->
</table></form>