{POST_MENU}{ATTACH_MENU}
{MOD_CP_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_REPLACE_TITLE}</h1>

<p>{L_REPLACE_TEXT}</p>

<!-- BEGIN switch_forum_sent -->
<table cellspacing="1" cellpadding="4" align="center" class="forumline" width="100%">
<tr>
	<th class="thCornerL" width="5%">#</th>
	<th class="thTop" width="25%">{L_FORUM}</th>
	<th class="thTop" width="30%">{L_TOPIC}</th>
	<th class="thTop" width="20%">{L_AUTHOR}</th>
	<th class="thCornerR" width="20%">{L_LINK}</th>
</tr>
<tr>
	<td class="catBottom" colspan="3" align="center"><b>{L_STR_OLD}:</b> {STR_OLD}</td>
	<td class="catBottom" colspan="2" align="center"><b>{L_STR_NEW}:</b> {STR_NEW}</td>
</tr>
<!-- BEGIN switch_no_results -->
<tr>
	<td class="row1" colspan="5" align="center">{L_NO_RESULTS}</td>
</tr>
<!-- END switch_no_results -->
<!-- BEGIN replaced -->
<tr>
	<td class="{switch_forum_sent.replaced.ROW_CLASS}" align="center">{switch_forum_sent.replaced.NUMBER}</td>
	<td class="{switch_forum_sent.replaced.ROW_CLASS}"><a href="{switch_forum_sent.replaced.U_FORUM}" target="_blank">{switch_forum_sent.replaced.FORUM_NAME}</a></td>
	<td class="{switch_forum_sent.replaced.ROW_CLASS}"><a href="{switch_forum_sent.replaced.U_TOPIC}" target="_blank">{switch_forum_sent.replaced.TOPIC_TITLE}</a></td>
	<td class="{switch_forum_sent.replaced.ROW_CLASS}"><a href="{switch_forum_sent.replaced.U_AUTHOR}" target="_blank">{switch_forum_sent.replaced.AUTHOR}</a></td>
	<td class="{switch_forum_sent.replaced.ROW_CLASS}" align="center"><a href="{switch_forum_sent.replaced.U_POST}" target="_blank"><img src="{POST_IMG}" alt="" title="" /></a> {switch_forum_sent.replaced.POST}</td>
</tr>
<!-- END replaced -->
<tr>
	<td class="catBottom" colspan="5" align="center"><b>{REPLACED_COUNT}</b></td>
</tr>
</table>
<br />
<!-- END switch_forum_sent -->

<table cellspacing="1" cellpadding="4" align="center" class="forumline" width="100%"><form method="post" action="{S_FORM_ACTION}">
<tr>
	<th class="thHead" colspan="2">{L_REPLACE_TITLE}</th>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_STR_OLD}:</b></td>
	<td class="row2"><input class="post" type="text" name="str_old" value="" size="35" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_STR_NEW}:</b></td>
	<td class="row2"><input class="post" type="text" name="str_new" value="" size="35" /></td>
</tr>
<tr>
	<td class="catBottom" colspan="2" align="center"><input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</form></table>
<br />
<div class="copyright" align="center">Replace Posts 1.0.0 &copy; 2003, {COPYRIGHT_YEAR} <a href="http://mods.mosymuis.nl" class="copyright" target="_blank">mosymuis</a></div>
