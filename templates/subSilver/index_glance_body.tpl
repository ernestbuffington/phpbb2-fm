<!-- BEGIN switch_glance_news -->
<table width="100%" cellpadding="4" cellspacing="1" class="forumline" align="center">
<tr>
	<td class="catHead"><span class="cattitle">{switch_glance_news.NEWS_HEADING}</span></td>
</tr>
<tr>
	<td class="row1">{switch_glance_news.SCROLL_BEGIN}<span class="genmed">
	<!-- END switch_glance_news -->
	<!-- BEGIN news -->
	{news.BULLET} <a href="{news.TOPIC_LINK}" class="genmed" title="{news.TOPIC_TITLE_ALT}">{news.TOPIC_TITLE}</a><br />{news.TOPIC_TIME}<br />
	<!-- END news -->
	<!-- BEGIN none -->
	{none.BULLET} {none.L_NONE}
	<!-- END none -->
	<!-- BEGIN switch_glance_news -->
	</span>{switch_glance_news.SCROLL_END}</td>
</tr>
</table>
<div style="padding: 2px;"></div>
<!-- END switch_glance_news -->

<!-- BEGIN switch_glance_recent -->
<table width="100%" cellpadding="4" cellspacing="1" class="forumline" align="center">
<tr>
	<td class="catHead"><span class="cattitle">{switch_glance_recent.RECENT_HEADING}</span></td>
</tr>
<tr> 
	<td class="row1">{switch_glance_recent.SCROLL_BEGIN}<span class="genmed">
	<!-- END switch_glance_recent -->
	<!-- BEGIN recent -->
	{recent.BULLET} <a href="{recent.TOPIC_LINK}" class="genmed" title="{recent.TOPIC_TITLE_ALT}">{recent.TOPIC_TITLE}</a><br />
	<!-- END recent -->
	<!-- BEGIN none2 -->
	{none2.BULLET} {none2.L_NONE}
	<!-- END none2 -->
	<!-- BEGIN switch_glance_recent -->
	</span>{switch_glance_recent.SCROLL_END}</td>
</tr>
</table>
<div style="padding: 2px;"></div>
<!-- END switch_glance_recent -->