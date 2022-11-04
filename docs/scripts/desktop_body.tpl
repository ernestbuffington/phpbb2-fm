<meta http-equiv="refresh" content="60; url=desktop.php">

<table width="200" cellpadding="4" cellspacing="1" class="forumline">
<tr>
	<th class="thHead">&nbsp;{L_USERCP}&nbsp;</th>
</tr>
<tr>
	<td class="row1" height="30">
	<a href="{U_LOGIN_LOGOUT}" title="{L_LOGIN_LOGOUT}" class="gensmall">{L_LOGIN_LOGOUT}</a><br />
	<a href="{U_PRIVATEMSGS}" class="gensmall" title="{L_PRIVATEMSGS}">{PRIVATE_MESSAGE_INFO}</a><br />
	<a href="{U_USERCP}" class="gensmall" title="{L_USERCP}">{L_USERCP}</a>	</td>
</tr>
</table>

<table width="200" cellpadding="4" cellspacing="1" class="forumline">
<tr>
	<th class="thHead">&nbsp;{L_RECENT_TOPICS}&nbsp;</th>
</tr>
<tr>
	<td class="row1"><span class="gensmall">
	<marquee id="recent_topics" behavior="scroll" direction="up" height="175" scrolldelay="100" scrollamount="2">
	<!-- BEGIN recent_topic_row -->
	<b>&#0187;</b> <a href="{recent_topic_row.U_TITLE}" onMouseOver="document.all.recent_topics.stop()" onMouseOut="document.all.recent_topics.start()" class="gensmall">{recent_topic_row.L_TITLE}</a><br />
	{L_BY} <a href="{recent_topic_row.U_POSTER}" onMouseOver="document.all.recent_topics.stop()" onMouseOut="document.all.recent_topics.start()" class="gensmall">{recent_topic_row.S_POSTER}</a> {L_ON} {recent_topic_row.S_POSTTIME}<br /><br />
	<!-- END recent_topic_row -->
	</marquee>
	</span></td>
</tr>
</table>

<table width="200" cellpadding="4" cellspacing="1" class="forumline">
<tr>
	<th class="thHead">&nbsp;{L_WHO_IS_ONLINE}&nbsp;</th>
</tr>
<tr>
	<td class="row1"><span class="gensmall">{TOTAL_USERS_ONLINE}<br /><br />{LOGGED_IN_USER_LIST}<br /><br />{RECORD_USERS}<br /><br />
	<b>{L_LEGEND}: {L_WHOSONLINE_ADMIN}, {L_WHOSONLINE_SUPERMOD}, {L_WHOSONLINE_MOD}{L_WHOSONLINE_GAMES}{GROUP_LEGEND}</b></span></td>
</tr>
</table>