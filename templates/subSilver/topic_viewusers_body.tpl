
<table width="100%" cellspacing="2" cellpadding="2" align="center"><form method="post" action="{S_MODE_ACTION}">
<tr> 
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> <a href="{U_VIEWTOPIC}" class="nav">{L_VIEWTOPIC}</a></td>
</tr>
</table>
<table width="100%" cellpadding="4" cellspacing="1" class="forumline">
<tr> 
	<th class="thCornerL" nowrap="nowrap">#</th>
	<th class="thTop" nowrap="nowrap">{L_PM}</th>
	<th class="thTop" nowrap="nowrap">{L_USERNAME}</th>
	<th class="thTop" nowrap="nowrap">{L_EMAIL}</th>
	<th class="thTop" nowrap="nowrap">{L_VIEWS}</th>
	<th class="thTop" nowrap="nowrap">{L_JOINED}</th>
	<th class="thTop" nowrap="nowrap">{L_LAST_VIEWED}</th>
	<th class="thCornerR" nowrap="nowrap">{L_WEBSITE}</th>
</tr>
<!-- BEGIN memberrow -->
<tr> 
	<td class="{memberrow.ROW_CLASS}" align="center">&nbsp;{memberrow.ROW_NUMBER}&nbsp;</td>
	<td class="{memberrow.ROW_CLASS}" align="center">&nbsp;{memberrow.PM_IMG}&nbsp;</td>
	<td class="{memberrow.ROW_CLASS}" align="center">{memberrow.USERNAME}</td>
	<td class="{memberrow.ROW_CLASS}" align="center" valign="middle">&nbsp;{memberrow.EMAIL_IMG}&nbsp;</td>
	<td class="{memberrow.ROW_CLASS}" align="center" valign="middle">{memberrow.FROM}</td>
	<td class="{memberrow.ROW_CLASS}" align="center" valign="middle">{memberrow.JOINED}</td>
 	<td class="{memberrow.ROW_CLASS}" align="center" valign="middle">{memberrow.POSTS}</td>
	<td class="{memberrow.ROW_CLASS}" align="center">&nbsp;{memberrow.WWW_IMG}&nbsp;</td>
</tr>
<!-- END memberrow -->
<tr> 
	<td align="center" class="catBottom" colspan="8"><span class="genmed">{L_SELECT_SORT_METHOD}:&nbsp;{S_MODE_SELECT}&nbsp;&nbsp;{L_ORDER}&nbsp;{S_ORDER_SELECT}&nbsp;&nbsp;<input type="submit" name="submit" value="{L_SUBMIT}" class="liteoption" /></span></td>
</tr>
</table>
<table width="100%" cellspacing="2" cellpadding="2">
<tr> 
	<td class="nav">{PAGE_NUMBER}</td>
	<td align="right" class="nav">{PAGINATION}</td>
</tr>
</table></form>
	
<table width="100%" cellspacing="2" align="center">
<tr> 
	<td valign="top" align="right">{JUMPBOX}</td>
</tr>
</table>
