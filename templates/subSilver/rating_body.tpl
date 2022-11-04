<table class="forumline">
<tr>
	<td>
	<p class="maintitle">{HEADING}</p>
	<p class="gen">{L_POSTER}: <b>{POSTER}</b><br />
	<br />
	{L_TOPIC}: <b>{TOPIC_TITLE}</b><br />
	{L_TOPIC_RANK}: {TOPIC_RANK}<br />
	{MESSAGE}</p>

	<table cellspacing="10"><form name="rating_form" method="post" action="{FORM_ACTION}">
	<input type="hidden" name="rating_form_submitted" value="y">
	<tr>
		<td valign="top"><table>
		<tr>
			<td colspan="2"><span class="gen">{RATE_POST_MSG}</span></td>
		</tr>
		<!-- BEGIN option -->
		<tr>
			<td><input type="radio" name="rating" value="{option.ID}" {option.SELECTED}></td>
			<td class="gen">{option.LABEL}</td>
		</tr>
		<!-- END option -->
		</table>
		{SUBMIT_BUTTON}
		</td>
	</tr>
	<tr>
		<td align="center"><table cellpadding="4" cellspacing="1" class="forumline">
		<b class="gen">{L_POST_RANK}: {POST_RANK}</b>
		<tr>
			<th class="thCornerL" nowrap="nowrap">&nbsp;{L_RATED_BY}&nbsp;</th>
			<th class="thTop" nowrap="nowrap">&nbsp;{L_BIAS}&nbsp;</th>
			<th class="thTop" nowrap="nowrap">&nbsp;{L_RATED_ON}&nbsp;</th>
			<th class="thCornerR" nowrap="nowrap">&nbsp;{L_RATING}&nbsp;</th>
		</tr>
		<!-- BEGIN current -->
		<tr>
			<td class="{current.ROWCSS}"><span class="gen">{current.WHO}</span></td>
			<td class="{current.ROWCSS}" nowrap="nowrap">{current.BIAS}</td>
			<td class="{current.ROWCSS}" nowrap="nowrap"><span class="gensmall">{current.RATING_TIME}</span></td>
			<td class="{current.ROWCSS}">{current.RATING}</td>
		</tr>
		<!-- END current -->
		</table></td>
	</tr>
	</form></table>
	<p class="genmed"><a href="{U_END_LINK}">{L_END_LINK}</a></p>
	</td>
</tr>
</table>
<br />
<div align="center" class="copyright">Rating System 1.1 &copy; 2003, 2005 <a href="http://www.mywebcommunities.com" class="copyright" target="_blank">Gentle Giant</a></div>
