{KB_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_PAGE_TITLE}</h1>

<p>{L_PAGE_EXPLAIN}</p>

<table width="100%" align="center"><form action="{S_CONFIG_ACTION}" method="post">
<tr>
	<td align="right">{CONFIG_SELECT} <input type="submit" name="config" value="{L_GO}" class="liteoption" /></td>
</tr>
</form></table>
<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline"><form action="{S_CONFIG_ACTION}" method="post">
<!-- BEGIN switch_config -->
<tr>
	<th class="thHead" colspan="2">{L_PAGE_TITLE}</th>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_NEW_NAME}:</b><br /><span class="gensmall">{L_NEW_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="kb_allow_new" value="1" {S_NEW_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="kb_allow_new" value="0" {S_NEW_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_APPROVE_NEW_NAME}:</b><br /><span class="gensmall">{L_APPROVE_NEW_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="kb_approve_new" value="1" {S_APPROVE_NEW_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="kb_approve_new" value="0" {S_APPROVE_NEW_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_EDIT_NAME}:</b><br /><span class="gensmall">{L_EDIT_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="kb_allow_edit" value="1" {S_EDIT_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="kb_allow_edit" value="0" {S_EDIT_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_APPROVE_EDIT_NAME}:</b><br /><span class="gensmall">{L_APPROVE_EDIT_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="kb_approve_edit" value="1" {S_APPROVE_EDIT_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="kb_approve_edit" value="0" {S_APPROVE_EDIT_NO} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_ANON_NAME}:</b><br /><span class="gensmall">{L_ANON_EXPLAIN}</span></td> 
        <td class="row2"><input type="radio" name="kb_allow_anon" value="1" {S_ANON_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="kb_allow_anon" value="0" {S_ANON_NO} /> {L_NO}</td> 
</tr>
<tr>
	<td class="row1"><b>{L_NOTIFY_NAME}:</b><br /><span class="gensmall">{L_NOTIFY_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="kb_notify" value="0" {S_NOTIFY_NONE} /> {L_NONE}&nbsp;&nbsp;<input type="radio" name="kb_notify" value="2" {S_NOTIFY_EMAIL} /> {L_EMAIL}&nbsp;&nbsp;<input type="radio" name="kb_notify" value="1" {S_NOTIFY_PM} /> {L_PM}</td>
</tr>
<tr>
	<td class="row1"><b>{L_ADMIN_ID_NAME}:</b><br /><span class="gensmall">{L_ADMIN_ID_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" name="kb_admin_id" value="{ADMIN_ID}" size="5" maxlength="4" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_COMMENTS}:</b><br /><span class="gensmall">{L_COMMENTS_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="kb_comments" value="1" {S_COMMENTS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="kb_comments" value="0" {S_COMMENTS_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_FORUM_ID}:</b><br /><span class="gensmall">{L_FORUM_ID_EXPLAIN}</span></td>
	<td class="row2">{FORUMS}</td>
</tr>
<tr>
	<td class="row1"><b>{L_DEL_TOPIC}:</b><br /><span class="gensmall">{L_DEL_TOPIC_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="kb_del_topic" value="1" {S_DEL_TOPIC_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="kb_del_topic" value="0" {S_DEL_TOPIC_NO} /> {L_NO}</td>
</tr>
<!-- END switch_config -->

<!-- BEGIN switch_article -->
<tr>
	<th class="thSides" colspan="2">{L_PAGE_TITLE}</th>
</tr>
<tr>
	<td colspan="2" class="row2"><span class="gensmall">{L_PRE_TEXT_EXPLAIN}</span></td>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_PRE_TEXT_HEADER}:</b></td>
	<td class="row2"><input type="radio" name="kb_show_pt" value="1" {S_SHOW_PRETEXT} /> {L_ENABLED}&nbsp;&nbsp;<input type="radio" name="kb_show_pt" value="0" {S_HIDE_PRETEXT} /> {L_DISABLED}</td>
</tr>
<tr>
	<td class="row1"><b>{L_PRE_TEXT_BODY}:</b></td>
	<td class="row2"><textarea class="post" name="kb_pt_body" cols="35" rows="5">{L_PT_BODY}</textarea></td>
</tr>
<!-- END switch_article -->

<tr>
	<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</form></table>
<br />
<div align="center" class="copyright">Knowledge Base 0.7.6 Beta &copy; 2003, {COPYRIGHT_YEAR} <a href="http://eric.best-1.biz/" class="copyright" target="_blank">wGEric</a></div>
