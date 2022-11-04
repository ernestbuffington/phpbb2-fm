<script language="Javascript" type="text/javascript">
<!--
function select_switch(status)
{
	for (i = 0; i < document.attach_list.length; i++)
	{
		document.attach_list.elements[i].checked = status;
	}
}
-->
</script>

<table width="100%" cellspacing="2" cellpadding="2"  align="center">
<tr> 
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></td>
</tr>
</table>

{CPL_MENU_OUTPUT}

<table align="center" width="100%" cellpadding="4" cellspacing="1" class="forumline"><form method="post" name="attach_list" action="{S_MODE_ACTION}">
<tr> 
	<th class="thCornerL" nowrap="nowrap" width="2%">&nbsp;#&nbsp;</th>
	<th class="thTop" nowrap="nowrap">&nbsp;{L_FILENAME}&nbsp;</th>
	<th class="thTop" nowrap="nowrap" width="5%">&nbsp;{L_POST_TIME}&nbsp;</th>
	<th class="thTop" nowrap="nowrap" width="5%">&nbsp;{L_SIZE}&nbsp;</th>
	<th class="thTop" nowrap="nowrap" width="5%">&nbsp;{L_DOWNLOADS}&nbsp;</th>
	<th class="thCornerR" nowrap="nowrap" width="2%">&nbsp;{L_DELETE}&nbsp;</th>
</tr>
<!-- BEGIN attach -->
<tr>
	<td colspan="6" align="center" class="row3"><table width="100%" cellspacing="1">
	<tr>
		<td class="nav" nowrap="nowrap">{PAGE_NUMBER}</td>
		<td class="gensmall" nowrap="nowrap">&nbsp;[ {TOTAL_ATTACHMENTS} ]&nbsp;</td>
	  	<td align="right" class="nav" width="100%" nowrap="nowrap">{PAGINATION}</td>
	</tr>
	</table></td>
</tr>
<!-- END attach -->
<!-- BEGIN attachrow -->
<tr> 
	<td class="{attachrow.ROW_CLASS}" align="center"><span class="gensmall">&nbsp;{attachrow.ROW_NUMBER}&nbsp;</span></td>
	<td class="{attachrow.ROW_CLASS}"><a href="{attachrow.U_VIEW_ATTACHMENT}" class="gen" target="_blank">{attachrow.FILENAME}</a><br /><span class="gensmall"><b>{L_TOPIC}:</b> {attachrow.POST_TITLE}</span></td>
	<td class="{attachrow.ROW_CLASS}" align="center" nowrap="nowrap"><span class="gensmall">{attachrow.POST_TIME}</span></td>
	<td class="{attachrow.ROW_CLASS}" align="center" nowrap="nowrap">{attachrow.SIZE} KB</td>
	<td class="{attachrow.ROW_CLASS}" align="center">{attachrow.DOWNLOAD_COUNT}</td>
	<td class="{attachrow.ROW_CLASS}" align="center">{attachrow.S_DELETE_BOX}</td>
	{attachrow.S_HIDDEN}
</tr>
<!-- END attachrow -->
<!-- BEGIN noattachrow -->
<tr>
	<td colspan="6" align="center" class="row1"><b class="gensmall">{noattachrow.L_NONE}</b></td>
</tr>
<!-- END noattachrow -->
<!-- BEGIN attach -->
<tr> 
	<td class="catBottom" colspan="6" align="center">
	<table width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td class="genmed">{L_SELECT_SORT_METHOD}:&nbsp;{S_MODE_SELECT}&nbsp;{S_ORDER_SELECT}&nbsp;<input type="submit" name="submit" value="{L_SORT}" class="mainoption" /></td>
		<td align="right">{S_USER_HIDDEN}<input type="submit" name="delete" value="{L_DELETE_MARKED}" class="liteoption" />
		</td>
	</tr></table>
	</td>
</tr>
<!-- END attach -->
</form></table>

<!-- BEGIN attach -->
<table width="100%" align="center" cellpadding="2" cellspacing="2">
<tr>
	<td align="right"><b class="gensmall"><a href="javascript:select_switch(true);" class="gensmall">{L_MARK_ALL}</a> :: <a href="javascript:select_switch(false);" class="gensmall">{L_UNMARK_ALL}</a></b></td>
</tr>
</table>
<!-- END attach -->
</td>
</tr>
</table>
<br />
<table width="100%" cellspacing="2" align="center">
  <tr> 
	<td valign="top" align="right">{JUMPBOX}</td>
  </tr>
</table>
