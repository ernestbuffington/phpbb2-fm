{ATTACH_MENU}{POST_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_CONTROL_PANEL_TITLE}</h1>

<p>{L_CONTROL_PANEL_EXPLAIN}</p>

<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline"><form method="post" action="{S_MODE_ACTION}">
<tr> 
	<td align="center" colspan="4" class="catHead"><span class="genmed">{L_VIEW}:&nbsp;{S_VIEW_SELECT}&nbsp;&nbsp;{L_SELECT_SORT_METHOD}:&nbsp;{S_MODE_SELECT}&nbsp;&nbsp;{L_ORDER}:&nbsp;{S_ORDER_SELECT}&nbsp;&nbsp;<input type="submit" name="submit" value="{L_SUBMIT}" class="liteoption" /></span></td>
</tr>
<tr> 
	<th class="thCornerL">#</th>
	<th class="thTop">{L_USERNAME}</th>
	<th class="thTop">{L_ATTACHMENTS}</th>
	<th class="thCornerR">{L_TOTAL_SIZE}</th>
</tr>
<!-- BEGIN memberrow -->
<tr> 
	<td class="{memberrow.ROW_CLASS}" align="center">{memberrow.ROW_NUMBER}</td>
	<td class="{memberrow.ROW_CLASS}" align="center"><a href="{memberrow.U_VIEW_MEMBER}" class="genmed">{memberrow.USERNAME}</a></td>
	<td class="{memberrow.ROW_CLASS}" align="center"><b>{memberrow.TOTAL_ATTACHMENTS}</b></td>
	<td class="{memberrow.ROW_CLASS}" align="center"><b>{memberrow.TOTAL_SIZE}</b></td>
</tr>
<!-- END memberrow -->
</table>
<table width="100%" align="center" cellspacing="2" cellpadding="2">
  <tr> 
	<td class="nav">{PAGE_NUMBER}</td>
	<td align="right" class="nav">{PAGINATION}</td>
  </tr>
</form></table>
