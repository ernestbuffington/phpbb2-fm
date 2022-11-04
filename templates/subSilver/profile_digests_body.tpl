<table width="100%" cellpadding="2" cellspacing="2">
<tr>
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></td>
</tr>
</table>

{CPL_MENU_OUTPUT}

<table width="100%" cellpadding="4" cellspacing="1" class="forumline" align="center">
<tr> 
	<th class="thHead" colspan="7">{L_CONTROL_PANEL}</th>
</tr>
<tr> 
	<td class="row2" colspan="7" align="center"><span class="gensmall">{L_PAGE_DESCRIPTION}</span></td>
</tr>
<tr> 
	<th class="thCornerL"">&nbsp;{L_MY_DIGESTS}&nbsp;</th>
	<th class="thTop">&nbsp;{L_LAST_DIGEST}&nbsp;</th>
	<th class="thTop">&nbsp;{L_FREQUENCY}&nbsp;</th>
	<th class="thTop">&nbsp;{L_STATUS}&nbsp;</th>
	<th class="thTop">&nbsp;{L_EDIT}&nbsp;</th>
	<th class="thTop">&nbsp;{L_UNSUBSCRIBE}&nbsp;</th>
	<th class="thCornerR">&nbsp;{L_RESET}&nbsp;</th>
</tr>	
<!-- BEGIN digest_row -->
<tr> 
	<td class="{digest_row.ROW_CLASS}"><span class="gen">{digest_row.DIGEST_NAME}</span></td>
	<td class="{digest_row.ROW_CLASS}" align="center" valign="middle"><span class="gen">{digest_row.LAST_DATE}<br />{digest_row.LAST_TIME}</span></td>
	<td class="{digest_row.ROW_CLASS}" align="center" valign="middle"><span class="gen">{digest_row.FREQUENCY}</span></td>
	<td class="{digest_row.ROW_CLASS}" align="center" valign="middle"><span class="gen">{digest_row.ACTIVITY}<br /><a href="{digest_row.ACTIVITY_URL}">[{digest_row.ALT_ACTIVITY}]</a></span></td>
	<td class="{digest_row.ROW_CLASS}" align="center" valign="middle"><span class="gen"><a href="{digest_row.EDIT_URL}">{L_EDIT}</a></span></td>
	<td class="{digest_row.ROW_CLASS}" align="center" valign="middle"><span class="gen"><a href="{digest_row.UNSUBSCRIBE_URL}">{L_UNSUBSCRIBE}</a></span></td>
	<td class="{digest_row.ROW_CLASS}" align="center" valign="middle"><span class="gen"><a href="{digest_row.RESET_URL}">{L_RESET}</a></span></td>
</tr>
<!-- END digest_row -->
<tr> 
	<td colspan="7" class="catBottom" align="center"><a href="{CREATE_NEW_URL}" class="cattitle">{L_CREATE_NEW}</a></td>
</tr>
</table>
<br />
<table width="100%" cellpadding="4" cellspacing="1" class="forumline" align="center">
<tr> 
	<th class="thCornerL">&nbsp;{L_GROUP_DIGESTS}&nbsp;</th>
	<th class="thTop">&nbsp;{L_LAST_DIGEST}&nbsp;</th>
	<th class="thTop">&nbsp;{L_FREQUENCY}&nbsp;</th>
	<th class="thTop">&nbsp;{L_STATUS}&nbsp;</th>
	<th class="thTop">&nbsp;{L_EDIT}&nbsp;</th>
	<th class="thTop">&nbsp;{L_UNSUBSCRIBE}&nbsp;</th>
	<th class="thCornerR">&nbsp;{L_RESET}&nbsp;</th>
</tr>	
<!-- BEGIN digest_group_row -->
<tr> 
	<td class={digest_group_row.ROW_CLASS}><span class="gen">{digest_group_row.DIGEST_NAME}</span></td>
	<td class={digest_group_row.ROW_CLASS} align="center" valign="middle"><span class="gen">{digest_group_row.LAST_DATE}<br />{digest_group_row.LAST_TIME}</span></td>
	<td class={digest_group_row.ROW_CLASS} align="center" valign="middle"><span class="gen">{digest_group_row.FREQUENCY}</span></td>
	<td class={digest_group_row.ROW_CLASS} align="center" valign="middle"><span class="gen">{digest_group_row.ACTIVITY}<br /><a href="{digest_group_row.ACTIVITY_URL}">{digest_group_row.ALT_ACTIVITY}</a></span></td>
	<td class={digest_group_row.ROW_CLASS} align="center" valign="middle"><span class="gen"><a href="{digest_group_row.EDIT_URL}">{digest_group_row.EDIT}</a></span></td>
	<td class={digest_group_row.ROW_CLASS} align="center" valign="middle"><span class="gen"><a href="{digest_group_row.UNSUBSCRIBE_URL}">{digest_group_row.UNSUBSCRIBE}</a></span></td>
	<td class={digest_group_row.ROW_CLASS} align="center" valign="middle"><span class="gen"><a href="{digest_group_row.RESET_URL}">{digest_group_row.RESET}</a></span></td>
</tr>
<!-- END digest_group_row -->
<tr> 
	<td colspan="7" class="catBottom">&nbsp;</td>
</tr>
</table>
	</td>
</tr>
</table>
<br />
<table width="100%" cellpadding="2" cellspacing="2">
<tr>
	<td align="right">{JUMPBOX}</td>
</tr>
</table>