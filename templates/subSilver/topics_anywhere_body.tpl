<table width="100%" cellspacing="2" cellpadding="2">
<tr>
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></td>
</tr>
</table>
<table width="100%" cellspacing="1" cellpadding="4" align="center" class="forumline"><form action="{S_FORM_ACTION}" method="post">
<input type="hidden" name="total_forums" value="{H_TOTAL_FORUMS}">
<tr>
	<th class="thHead" colspan="2">{L_TOPICS_ANYWHERE}</th>
</tr>
<tr>
	<td class="row2" colspan="2"><span class="gensmall">{L_TOPICS_ANYWHERE_EXPLAIN}</span></td>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_OUTPUT}:</b><br /><span class="gensmall">{L_OUTPUT_EXPLAIN}</span></td>
	<td class="row2">{OUTPUT_SELECT}</td>
</tr>
<tr>
	<td class="row1"><b>{L_SELECT_FORUM}:</b><br /><span class="gensmall">{L_SELECT_FORUM_EXPLAIN}</span></td>
	<td class="row2"><span class="gen">{SELECT_FORUM_SELECT} &nbsp;&nbsp;<b>{L_OR}</b>&nbsp;&nbsp;<input type="checkbox" name="allfora">&nbsp;{L_CHECK_ALLFORA}</span></td>
</tr>
<tr>
	<td class="row1"><b>{L_AMOUNT_TOPICS}</b><br /><span class="gensmall">{L_AMOUNT_TOPICS_EXPLAIN}</span></td>
	<td class="row2"><input type="text" class="post" name="amount_topics" size="10" maxlength="2" value="10" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_TOPICS_LIFESPAN}:</b><br /><span class="gensmall">{L_TOPICS_LIFESPAN_EXPLAIN}</span></td>
	<td class="row2"><span class="gen"><input type="checkbox" name="noreply">&nbsp;{L_TOPICS_LIFESPAN_NOREPLY}&nbsp;&nbsp;<input type="text" class="post" name="noreply_timespan" size="5" maxlength="3" value="0" />&nbsp;&nbsp;<select name="noreply_unit"><option value="hour">{L_HOURS}</option><option value="day">{L_DAYS}</option></select><br /><input type="checkbox" name="startdate">&nbsp;{L_TOPICS_LIFESPAN_STARTDATE}&nbsp;&nbsp;<input type="text" class="post" name="startdate_timespan" size="5" maxlength="3" value="0" />&nbsp;&nbsp;<select name="startdate_unit"><option value="hour">{L_HOURS}</option><option value="day">{L_DAYS}</option></select></span></td>
</tr>
<tr>
	<td class="row1"><b>{L_JUMP_LAST_POST}</b><br /><span class="gensmall">{L_JUMP_LAST_POST_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="jump_last_post" value="1" /><span class="gen"> {L_YES}</span>&nbsp;&nbsp;<input type="radio" name="jump_last_post" value="0" checked /><span class="gen"> {L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><b>{L_SHOW_FORUM_NAME}:</b><br /><span class="gensmall">{L_SHOW_FORUM_NAME_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="show_forum_name" value="1" checked /><span class="gen"> {L_YES}</span>&nbsp;&nbsp;<input type="radio" name="show_forum_name" value="0" /><span class="gen"> {L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><b>{L_LINK_FORUM_NAME}:</b><br /><span class="gensmall">{L_LINK_FORUM_NAME_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="link_forum_name" value="1" checked /><span class="gen"> {L_YES}</span>&nbsp;&nbsp;<input type="radio" name="link_forum_name" value="0" /><span class="gen"> {L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><b>{L_SHOW_REPLIES}</b><br /><span class="gensmall">{L_SHOW_REPLIES_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="show_replies" value="1" checked /><span class="gen"> {L_YES}</span>&nbsp;&nbsp;<input type="radio" name="show_replies" value="0" /><span class="gen"> {L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><b>{L_SHOW_REPLIES_WORD}</b><br /><span class="gensmall">{L_SHOW_REPLIES_WORD_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="show_replies_word" value="1" checked /><span class="gen"> {L_YES}</span>&nbsp;&nbsp;<input type="radio" name="show_replies_word" value="0" /><span class="gen"> {L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><b>{L_SHOW_REGULAR}</b></td>
	<td class="row2"><input type="radio" name="show_regular" value="1" checked /><span class="gen"> {L_YES}</span>&nbsp;&nbsp;<input type="radio" name="show_regular" value="0" /><span class="gen"> {L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><b>{L_SHOW_ANNOUNCEMENTS}</b></td>
	<td class="row2"><input type="radio" name="show_announce" value="1" /><span class="gen"> {L_YES}</span>&nbsp;&nbsp;<input type="radio" name="show_announce" value="0" checked /><span class="gen"> {L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><b>{L_SHOW_STICKYS}</b></td>
	<td class="row2"><input type="radio" name="show_sticky" value="1" /><span class="gen"> {L_YES}</span>&nbsp;&nbsp;<input type="radio" name="show_sticky" value="0" checked /><span class="gen"> {L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><b>{L_SHOW_LOCKED}</b></td>
	<td class="row2"><input type="radio" name="show_locked" value="1" /><span class="gen"> {L_YES}</span>&nbsp;&nbsp;<input type="radio" name="show_locked" value="0" checked /><span class="gen"> {L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><b>{L_SHOW_MOVED}</b></td>
	<td class="row2"><input type="radio" name="show_moved" value="1" /><span class="gen"> {L_YES}</span>&nbsp;&nbsp;<input type="radio" name="show_moved" value="0" checked /><span class="gen"> {L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><b>{L_HIDE_LABELS}:</b><br /><span class="gensmall">{L_HIDE_LABELS_EXPLAIN}</span></td>
	<td class="row2"><span class="gen"><input type="checkbox" name="hidelabel['a']">&nbsp;{O_ANNOUNCEMENT}<br /><input type="checkbox" name="hidelabel['s']">&nbsp;{O_STICKY}<br /><input type="checkbox" name="hidelabel['m']">&nbsp;{O_MOVED}<br /><input type="checkbox" name="hidelabel['p']">&nbsp;{O_POLL}<br /><input type="checkbox" name="hidelabel['l']">&nbsp;{O_LOCKED}</span></td>
</tr>
<tr>
	<td class="row1"><b>{L_SORT_ORDER}:</b><br /><span class="gensmall">{L_SORT_ORDER_EXPLAIN}</span></td>
	<td class="row2"><select name="sort_order">
		<option value="priority">{O_PRIORITY}</option>
		<option value="ondate">{O_ON_DATE}</option>
	</select></td>
</tr>
<tr>
	<td class="row1"><b>{L_BULLET_TYPE}</b><br /><span class="gensmall">{L_BULLET_TYPE_EXPLAIN}</span></td>
	<td class="row2">{BULLET_TYPE_SELECT}</td>
</tr>
<tr>
	<td class="row1"><b>{L_SHOW_LASTPOSTBY}</b><br /><span class="gensmall">{L_SHOW_LASTPOSTBY_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="show_lastpostby" value="1" checked /><span class="gen"> {L_YES}</span>&nbsp;&nbsp;<input type="radio" name="show_lastpostby" value="0" /><span class="gen"> {L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><b>{L_LASTPOSTBY_FORMAT}:</b><br /><span class="gensmall">{L_LASTPOSTBY_FORMAT_EXPLAIN}</span></td>
	<td class="row2">{LASTPOSTBY_FORMAT_SELECT}</td>
</tr>
<tr>
	<td class="row1"><b>{L_SHOW_LASTPOSTDATE}</b><br /><span class="gensmall">{L_SHOW_LASTPOSTDATE_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="show_lastpostdate" value="1" checked /><span class="gen"> {L_YES}</span>&nbsp;&nbsp;<input type="radio" name="show_lastpostdate" value="0" /><span class="gen"> {L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><b>{L_LASTPOSTDATE_FORMAT}:</b><br /><span class="gensmall">{L_LASTPOSTDATE_FORMAT_EXPLAIN}</span></td>
	<td class="row2">{LASTPOSTDATE_FORMAT_SELECT}</td>
</tr>
<tr>
	<td class="row1"><b>{L_SHOW_LASTPOSTICON}</b><br /><span class="gensmall">{L_SHOW_LASTPOSTICON_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="show_lastposticon" value="1" checked /><span class="gen"> {L_YES}</span>&nbsp;&nbsp;<input type="radio" name="show_lastposticon" value="0" /><span class="gen"> {L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><b>{L_LASTPOSTICON_AS_BULLET}:</b><br /><span class="gensmall">{L_LASTPOSTICON_AS_BULLET_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="lastposticon_bullet" value="1" /><span class="gen"> {L_YES}</span>&nbsp;&nbsp;<input type="radio" name="lastposticon_bullet" value="0" checked/><span class="gen"> {L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><b>{L_ADD_BREAK}:</b><br /><span class="gensmall">{L_ADD_BREAK_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="add_break" value="1" /><span class="gen"> {L_YES}</span>&nbsp;&nbsp;<input type="radio" name="add_break" value="0" checked /><span class="gen"> {L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><b>{L_ADD_BLANK_LINE}:</b><br /><span class="gensmall">{L_ADD_BLANK_LINE_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="add_blank_line" value="1" /><span class="gen"> {L_YES}</span>&nbsp;&nbsp;<input type="radio" name="add_blank_line" value="0" checked /><span class="gen"> {L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><b>{L_LIMIT_LENGTH}:</b><br /><span class="gensmall">{L_LIMIT_LENGTH_EXPLAIN}</span></td>
	<td class="row2"><input type="text" class="post" name="limit_length" size="10" maxlength="3" value="0" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_LIMIT_WHERE}</b><br /><span class="gensmall">{L_LIMIT_WHERE_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="limit_where" value="p" /><span class="gen"> {L_PREVIOUS_SPACE}</span>&nbsp;&nbsp;<input type="radio" name="limit_where" value="e" checked /><span class="gen"> {L_EXACT}</span>&nbsp;&nbsp;<input type="radio" name="limit_where" value="n" /><span class="gen"> {L_NEXT_SPACE}</span></td>
</tr>
<tr>
	<td class="row1"><b>{L_CSS_LINK}:</b><br /><span class="gensmall">{L_CSS_LINK_EXPLAIN}</span></td>
	<td class="row2"><input type="text" class="post" name="css_link" size="35" maxlength="25" value="" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_CSS_TEXT}:</b><br /><span class="gensmall">{L_CSS_TEXT_EXPLAIN}</span></td>
	<td class="row2"><input type="text" class="post" name="css_text" size="35" maxlength="25" value="" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_TARGET_LINK}:</b><br /><span class="gensmall">{L_TARGET_LINK_EXPLAIN}</span></td>
	<td class="row2"><input type="text" class="post" name="target_link" size="35" maxlength="10" value="" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_ADV_FORM}</b><br /><span class="gensmall">{L_ADV_FORM_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="adv_form_enable" value="1" /><span class="gen"> {L_YES}</span>&nbsp;&nbsp;<input type="radio" name="adv_form_enable" value="0" checked /><span class="gen"> {L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><b>{L_ADV_FORM_STRING}:</b><br /><span class="gensmall">{L_ADV_FORM_STRING_EXPLAIN}</span></td>
	<td class="row2"><input type="text" class="post" name="adv_form" size="50" maxlength="100" value="{L_ADV_FORM_DEFAULT}" /><br /><span class="gensmall">{L_ADV_FORM_VARS}</span></td>
</tr>
<tr>
	<td class="catBottom" colspan="2" align="center"><input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</form></table>
<br />
<table width="100%" cellspacing="2" align="center"> 
<tr> 
	<td align="right" valign="middle" nowrap>{JUMPBOX}</td> 
</tr> 
</table> 
