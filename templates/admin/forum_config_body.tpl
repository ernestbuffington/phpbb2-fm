{FORUM_MENU}
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
<tr>
	<th class="thHead" colspan="2">{L_PAGE_TITLE}</th>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_TOPICS_PER_PAGE}:</b></td>
	<td class="row2"><input class="post" type="text" name="topics_per_page" size="4" maxlength="4" value="{TOPICS_PER_PAGE}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_POSTS_PER_PAGE}:</b></td>
	<td class="row2"><input class="post" type="text" name="posts_per_page" size="4" maxlength="4" value="{POSTS_PER_PAGE}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_HOT_THRESHOLD}:</b></td>
	<td class="row2"><input class="post" type="text" name="hot_threshold" size="4" maxlength="4" value="{HOT_TOPIC}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_CAPITALIZATION}:</b><br /><span class="gensmall">{L_CAPITALIZATIONEXPLAIN}</span></td>
	<td class="row2">&nbsp;<input type="radio" name="capitalization" value="0" {CAPITALIZATION_NONE_CHECKED} /> {L_DISABLED}<br />&nbsp;<input type="radio" name="capitalization" value="1" {CAPITALIZATION_UPPERCASE_CHECKED} /> {L_CAPITALIZATION_UPPERCASE}&nbsp;&nbsp;<input type="radio" name="capitalization" value="2" {CAPITALIZATION_LOWERCASE_CHECKED} /> {L_CAPITALIZATION_LOWERCASE}<br />&nbsp;<input type="radio" name="capitalization" value="3" {CAPITALIZATION_FIRSTCHAR_CHECKED} /> {L_CAPITALIZATION_FIRSTCHAR}&nbsp;&nbsp;<input type="radio" name="capitalization" value="4" {CAPITALIZATION_FIRSTCHARPERWORD_CHECKED} /> {L_CAPITALIZATION_FIRSTCHARPERWORD}</td>
</tr>
<tr>
	<td class="row1"><b>{L_ENABLE_PRUNE}:</b></td>
	<td class="row2"><input type="radio" name="prune_enable" value="1" {PRUNE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="prune_enable" value="0" {PRUNE_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_SIMILAR_TOPICS}:</b></td>
	<td class="row2"><input type="radio" name="enable_similar_topics" value="1" {S_SIMILAR_TOPICS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="enable_similar_topics" value="0" {S_SIMILAR_TOPICS_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_SPLIT_GLOBAL_ANNOUNCE}:</b></td>
	<td class="row2"><input type="radio" name="split_global_announce" value="1" {SPLIT_GLOBAL_ANNOUNCE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="split_global_announce" value="0" {SPLIT_GLOBAL_ANNOUNCE_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_SPLIT_ANNOUNCE}:</b></td>
	<td class="row2"><input type="radio" name="split_announce" value="1" {SPLIT_ANNOUNCE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="split_announce" value="0" {SPLIT_ANNOUNCE_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_SPLIT_STICKY}:</b></td>
	<td class="row2"><input type="radio" name="split_sticky" value="1" {SPLIT_STICKY_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="split_sticky" value="0" {SPLIT_STICKY_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_LOCKED_LAST}:</b><br /><span class="gensmall">{L_LOCKED_LAST_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="locked_last" value="1" {LOCKED_LAST_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="locked_last" value="0" {LOCKED_LAST_NO} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_ENABLE_TELLAFRIEND}:</b></td>
	<td class="row2" nowrap="nowrap"><input type="radio" name="enable_tellafriend" value="1" {TELLAFRIEND_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="enable_tellafriend" value="0" {TELLAFRIEND_NO} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_ENABLE_QUICK_REPLY}:</b><br /><span class="gensmall">{L_ENABLE_QUICK_REPLY_EXPLAIN}</span></td>
	<td class="row2" nowrap="nowrap"><input type="radio" name="enable_quick_reply" value="1" {QUICK_REPLY_ENABLE} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="enable_quick_reply" value="0" {QUICK_REPLY_DISABLE} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_ENABLE_TOPIC_VIEW_USERS}:</b><br /><span class="gensmall">{L_ENABLE_TOPIC_VIEW_USERS_EXPLAIN}</span></td>
	<td class="row2" nowrap="nowrap"><input type="radio" name="enable_topic_view_users" value="1" {TOPIC_VIEW_USERS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="enable_topic_view_users" value="0" {TOPIC_VIEW_USERS_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_DISABLE_TOPIC_WATCHING}:</b><br /><span class="gensmall">{L_DISABLE_TOPIC_WATCHING_EXPLAIN}</span><br /></td>
	<td class="row2"><input type="radio" name="disable_topic_watching" value="0"{DISABLE_TOPIC_WATCHING_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="disable_topic_watching" value="1"{DISABLE_TOPIC_WATCHING_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_INDEX_ACTIVE_FORUMS}:</b><br /><span class="gensmall">{L_INDEX_ACTIVE_FORUMS_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="index_active_in_forum" value="1" {INDEX_ACTIVE_FORUMS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="index_active_in_forum" value="0" {INDEX_ACTIVE_FORUMS_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_NO_POST_COUNT}:</b><br /><span class="gensmall">{L_NO_POST_COUNT_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" name="no_post_count_forum_id" size="30" maxlength="255" value="{NO_POST_COUNT_FORUM_ID}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_JOURNAL_FORUM}:</b><br /><span class="gensmall">{L_JOURNAL_FORUM_EXPLAIN}</span></td>
	<td class="row2">{JOURNAL_SELECT}</td>
</tr>
<tr>
	<td class="row1"><b>{L_BIN_FORUM}:</b><br /><span class="gensmall">{L_BIN_FORUM_EXPLAIN}</span></td>
	<td class="row2">{BIN_FORUM_SELECT}</td>
</tr>
<tr>
	<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</form></table>
