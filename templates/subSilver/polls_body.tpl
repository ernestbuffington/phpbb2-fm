
<table width="100%" cellpadding="2" cellspacing="2" align="center">
<tr>
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></td>
</tr>
</table>
<table cellpadding="4" cellspacing="1" align="center" width="100%" class="forumline">
<tr>
	<th class="thHead">{L_OVERVIEW}</th>
</tr>
<!-- BEGIN post_poll -->
<tr>
	<td class="{post_poll.ROW_CLASS}"><table cellspacing="0" cellpadding="4" align="center">
        <tr>
        	<td align="center"><a href="{post_poll.U_TOPIC}" class="forumlink">{post_poll.TOPIC_TITLE}</a></td>
	</tr>
        <tr>
        	<td align="center"><b class="gensmall">{post_poll.POLL_QUESTION}</b></td>
	</tr>
        <tr>
        	<td align="center"><span class="gensmall">{post_poll.L_TOTAL_VOTES} : {post_poll.TOTAL_VOTES}</span></td>
	</tr>
	<tr>
		<td align="center"><table cellspacing="0" cellpadding="2">
		<!-- BEGIN poll_option -->
		<tr>
			<td class="gen">{post_poll.poll_option.POLL_OPTION_CAPTION}</td>
			<td><table cellspacing="0" cellpadding="0">
			<tr>
				<td><img src="templates/{T_NAV_STYLE}/vote_lcap.gif" width="4" alt="" height="12" /></td>
				<td><img src="{post_poll.poll_option.POLL_OPTION_IMG}" width="{post_poll.poll_option.POLL_OPTION_IMG_WIDTH}" height="12" alt="{post_poll.poll_option.POLL_OPTION_PERCENT}" /></td>
				<td><img src="templates/{T_NAV_STYLE}/vote_rcap.gif" width="4" alt="" height="12" /></td>
			</tr>
	                </table></td>
			<td align="center"><b class="gen">&nbsp;{post_poll.poll_option.POLL_OPTION_PERCENT}&nbsp;</b></td>
			<td align="center" class="gen">[ {post_poll.poll_option.POLL_OPTION_RESULT} ]</td>
		</tr>
		<!-- END poll_option -->
		</table></td>
	</tr>
	</table></td>
</tr>
<!-- END post_poll -->
</table>
<br />
<div align="center" class="copyright">Poll Overview 1.0 &copy; 2004, {COPYRIGHT_YEAR} <a href="http://www.tememento.de" target="_blank" class="copyright">FR</a></div>
