
<table width="100%" cellspacing="2" cellpadding="2" align="center"><form method="post" action="{S_MODE_ACTION}">
<tr> 
	<td valign="bottom"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></td>
	<td align="right" nowrap="nowrap"><span class="genmed">{L_FORUM}:&nbsp;{S_FORUM_SELECT}<br />{L_SELECT_SORT_METHOD}:&nbsp;{S_MODE_SELECT}&nbsp;&nbsp;{L_ORDER}:&nbsp;{S_ORDER_SELECT}&nbsp;&nbsp;
	<input type="submit" name="submit" value="{L_SORT}" class="liteoption" />
	</span></td>
</tr>
</table>

{ERROR_BOX}

<table width="100%" cellpadding="4" cellspacing="1" class="forumline">
<tr> 
	<th class="thCornerL" nowrap="nowrap">&nbsp;#&nbsp;</th>
	<th class="thTop" nowrap="nowrap">&nbsp;{L_FILENAME}&nbsp;</th>
	<th class="thTop" nowrap="nowrap">&nbsp;{L_FILECOMMENT}&nbsp;</th>
	<th class="thTop" nowrap="nowrap">&nbsp;{L_SIZE}&nbsp;</th>
	<th class="thTop" nowrap="nowrap">&nbsp;{L_DOWNLOADS}&nbsp;</th>
	<th class="thTop" nowrap="nowrap">&nbsp;{L_POST_TIME}&nbsp;</th>
	<th class="thCornerR" nowrap="nowrap">&nbsp;{L_POSTED_IN_TOPIC}&nbsp;</th>
</tr>
<!-- BEGIN attachrow --> 
<tr> 
	<td class="{attachrow.ROW_CLASS}" align="center"><span class="gen">&nbsp;{attachrow.ROW_NUMBER}&nbsp;</span></td>
	<td class="{attachrow.ROW_CLASS}">{attachrow.VIEW_ATTACHMENT}</td> 
	<td class="{attachrow.ROW_CLASS}"><span class="genmed">{attachrow.COMMENT}</span></td> 
	<td class="{attachrow.ROW_CLASS}" align="center" valign="middle"><span class="gen">{attachrow.SIZE}</span></td> 
	<td class="{attachrow.ROW_CLASS}" align="center" valign="middle"><span class="gen">{attachrow.DOWNLOAD_COUNT}</span></td> 
	<td class="{attachrow.ROW_CLASS}" align="center" valign="middle"><span class="genmed">{attachrow.POST_TIME}</span></td> 
	<td class="{attachrow.ROW_CLASS}" valign="middle">{attachrow.POST_TITLE}</td> 
</tr> 
<!-- END attachrow --> 
<tr> 
	<td class="catbottom" colspan="8">&nbsp;</td>
</tr>
</table>
 
<table width="100%" cellspacing="0" cellpadding="0">
<tr> 
	<td class="nav">{PAGE_NUMBER}</td>
	<td align="right" class="nav">{PAGINATION}</td>
</tr>
</form></table>

<table width="100%" cellspacing="2" align="center"> 
<tr> 
	<td align="right" valign="middle" nowrap="nowrap">{JUMPBOX}</td> 
</tr> 
</table> 

