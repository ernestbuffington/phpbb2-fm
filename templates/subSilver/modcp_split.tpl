<script language="Javascript" type="text/javascript">
function select_switch(status)
{
	for (i = 0; i < document.modcp.length; i++)
	{
		document.modcp.elements[i].checked = status;
	}
}
</script>
<table width="100%" cellspacing="2" cellpadding="2" align="center">
<tr> 
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a><span class="nav"> -> <a href="{U_VIEW_FORUM}" class="nav">{FORUM_NAME}</a></td>
</tr>
</table>
<table width="100%" cellpadding="4" cellspacing="1" class="forumline"><form method="post" name="modcp" action="{S_SPLIT_ACTION}">
<tr> 
	<th class="thHead" colspan="3" nowrap="nowrap">{L_SPLIT_TOPIC}</th>
</tr>
<tr> 
	<td class="row2" colspan="3" align="center"><span class="gensmall">{L_SPLIT_TOPIC_EXPLAIN}</span></td>
</tr>
<tr> 
	<td class="row1" nowrap="nowrap"><span class="gen">{L_SPLIT_SUBJECT}</span></td>
	<td class="row2" colspan="2"><input class="post" type="text" size="35" style="width: 350px" maxlength="120" name="subject" /></td>
</tr>
<tr> 
	<td class="row1" nowrap="nowrap"><span class="gen">{L_SPLIT_FORUM}</span></td>
	<td class="row2" colspan="2">{S_FORUM_SELECT}</td>
</tr>
<tr> 
	<td class="catHead" colspan="3"><table width="60%" cellspacing="0" cellpadding="0" align="center">
	<tr> 
		<td width="50%" align="center"><input class="liteoption" type="submit" name="split_type_all" value="{L_SPLIT_POSTS}" /></td>
		<td width="50%" align="center"><input class="liteoption" type="submit" name="split_type_beyond" value="{L_SPLIT_AFTER}" /></td>
	</tr>
	</table></td>
</tr>
<tr> 
	<th class="thCornerL" nowrap="nowrap">{L_AUTHOR}</th>
	<th class="thTop" nowrap="nowrap">{L_MESSAGE}</th>
	<th class="thCornerR" nowrap="nowrap">{L_SELECT}</th>
</tr>
<!-- BEGIN postrow -->
<tr> 
	<td align="left" valign="top" class="{postrow.ROW_CLASS}"><span class="name"><a name="{postrow.U_POST_ID}"></a>{postrow.POSTER_NAME}</span></td>
	<td width="100%" valign="top" class="{postrow.ROW_CLASS}"><table width="100%" cellspacing="0" cellpadding="3">
	<tr> 
		<td valign="middle"><img src="templates/{T_NAV_STYLE}/icon_minipost.gif" alt="{L_POST}"><span class="postdetails">{L_POSTED}: {postrow.POST_DATE}&nbsp;&nbsp;&nbsp;&nbsp;{L_POST_SUBJECT}: {postrow.POST_SUBJECT}</span></td>
	</tr>
	<tr> 
		<td valign="top"><hr size="1" /><span class="postbody">{postrow.MESSAGE}</span></td> 
	</tr>
	</table></td>
	<td width="5%" align="center" class="{postrow.ROW_CLASS}">{postrow.S_SPLIT_CHECKBOX}</td>
</tr>
<tr> 
	<td colspan="3" height="1" class="row3"><img src="images/spacer.gif" width="1" height="1" alt="" title="" /></td>
</tr>
<!-- END postrow -->
<tr> 
	<td class="catBottom" colspan="3"><table width="60%" cellspacing="0" cellpadding="0" align="center">
	<tr> 
		<td width="50%" align="center"><input class="liteoption" type="submit" name="split_type_all" value="{L_SPLIT_POSTS}" /></td>
		<td width="50%" align="center"><input class="liteoption" type="submit" name="split_type_beyond" value="{L_SPLIT_AFTER}" />{S_HIDDEN_FIELDS}</td>
	</tr>
	</table></td>
</tr>
</table>
<table width="100%" cellspacing="2" align="center" cellpadding="2">
<tr> 
	<td align="right" valign="top"><b class="gensmall"><a href="javascript:select_switch(true);" class="gensmall">{L_MARK_ALL}</a> :: <a href="javascript:select_switch(false);" class="gensmall">{L_UNMARK_ALL}</a></b></td>
</tr>
</form></table>
