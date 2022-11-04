<form action="javascript://" name="move_topic_{TOPIC_ID}" id="move_topic_{TOPIC_ID}"><table width="300" cellpadding="4" cellspacing="1" class="forumline">
<tr> 
	<th class="thHead" onClick="uf();" onmousedown="xb(ad('misc'))" style="cursor:move;"><b>{MESSAGE_TITLE}</b></th>
</tr>
<tr> 
	<td class="row1" onClick="uf();"><table width="100%" cellspacing="0" cellpadding="1">
	<tr> 
		<td align="center"><span class="gen" onClick="uf('1');" onMouseOver="xe(1);" onMouseOut="xe();">{L_MOVE_TO_FORUM} &nbsp; {S_FORUM_SELECT}<br /><br />
		<input type="checkbox" name="{TOPIC_ID}_leave_shadow" id="{TOPIC_ID}_leave_shadow" /><label for="{TOPIC_ID}_leave_shadow">{L_LEAVESHADOW}</label><br /><br />{MESSAGE_TEXT}<br />
		<input class="mainoption" type="submit" onClick="mt({TOPIC_ID},{B}); mc(); return false;" value="&nbsp;&nbsp;{L_YES}&nbsp;&nbsp;" /></span></td>
	</tr>
	</table></td>
</tr>
</table>
</form>
