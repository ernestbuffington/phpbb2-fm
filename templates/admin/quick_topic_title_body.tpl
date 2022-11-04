{TOPIC_MENU}{FORUM_MENU}{VOTE_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{ADMIN_TITLE}</h1>

<p>{ADMIN_TITLE_EXPLAIN}</p>

<table width="100%" cellpadding="4" cellspacing="1" align="center" class="forumline"><form action="{U_CONFIG_ACTION}" method="post">	
<tr>
	<th class="thHead" colspan="2">{L_CONFIGURATION}</th>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_ENABLE_QUICK_TITLES}:</b></td>
	<td class="row2"><input type="radio" name="enable_quick_titles" value="1" {ENABLE_QUICK_TITLES_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="enable_quick_titles" value="0" {ENABLE_QUICK_TITLES_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="config" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</form></table>
<br />

<table width="100%" cellspacing="2" cellpadding="2" align="center"><form method="post" action="{S_TITLE_ACTION}">
<tr>
	<td><input type="submit" class="liteoption" name="add" value="{ADD_NEW}" /></td>
</tr>
</form></table>
<table width="100%" cellspacing="1" cellpadding="4" align="center" class="forumline">
<tr>
	<th class="thCornerL">&nbsp;{ADMIN_TITLE}&nbsp;</th>
	<th class="thTop">&nbsp;{HEAD_AUTH}&nbsp;</th>
	<th class="thTop">&nbsp;{HEAD_DATE}&nbsp;</th>
	<th class="thTop">&nbsp;{HEAD_POS}&nbsp;</th>
	<th class="thCornerR" width="15%">&nbsp;{L_ACTION}&nbsp;</th>
</tr>
<!-- BEGIN title -->
<tr>
	<td class="{title.ROW_CLASS}"><span style="color: #{title.COLOR_INFO}">{title.TITLE}</span></td>
	<td class="{title.ROW_CLASS}" align="center">{title.PERMISSIONS}</td>
	<td class="{title.ROW_CLASS}" align="center">{title.DATE_FORMAT}</td>
	<td class="{title.ROW_CLASS}" align="center">{title.TITLE_POS}</td>
	<td class="{title.ROW_CLASS}" align="right" nowrap="nowrap"><a href="{title.U_TITLE_EDIT}">{L_EDIT}</a> <a href="{title.U_TITLE_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- END title -->			
</table>
<table width="100%" cellspacing="2" cellpadding="2" align="center">
<tr>
	<td class="nav">{PAGINATION}</td>
</tr>
</table>
<br />
<div align="center" class="copyright">Quick Title Edition 1.5.1a &copy; 2003, {COPYRIGHT_YEAR} <a href="mailto:xavier@2037.biz" class="copyright" target="_blank">Xavier Olive</a></div>