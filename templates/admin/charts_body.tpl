{CONFIG_MENU}{USERCOM_MENU}{SERVER_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_PAGE_TITLE}</h1>

<p>{L_PAGE_DESC}</p>

<table width="100%" cellspacing="1" cellpadding="4" align="center" class="forumline"><form method="post" action="{S_CHARTS_ACTION}">
<tr>
	<td><input type="submit" name="end_week" value="{L_END_WEEK}" class="liteoption" /></td>
</tr>
</form></table>
<table width="100%" cellspacing="1" cellpadding="4" align="center" class="forumline">
<tr>
	<th class="thCornerL">{L_TITLE}, <i>{L_ALBUM} - {L_ARTIST}<br />{L_LABEL} | {L_CAT_NO} | {L_WEBSITE}</th>
	<th class="thCornerR" width="15%">{L_ACTION}</th>
</tr>
<!-- BEGIN charts -->
<tr>
	<td class="{charts.ROW_CLASS}"><b>{charts.TITLE}</b>, {charts.ALBUM} - {charts.ARTIST}<br /><span class="gensmall">{charts.LABEL} | {charts.CAT_NO} | <a href="{charts.WEBSITE}" target="_blank" class="gensmall">{charts.WEBSITE}</a></span></td>
	<td class="{charts.ROW_CLASS}" align="right" nowrap="nowrap"><a href="{charts.U_EDIT}">{L_EDIT}</a> <a href="{charts.U_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- END charts -->
</table>
<br />
<div align="center" class="copyright">Charts 1.0 &copy; 2003, {COPYRIGHT_YEAR} <a href="mailto:dzidzius@forumbest.now.pl" target="_blank" class="copyright">dzidzius</a> &amp; <a href="http://phpbb-fm.com" target="_blank" class="copyright">MJ</a></div>
