{POST_MENU}{ATTACH_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_TITLE}</h1>

<p>{L_EXPLAIN}</p>

<table width="100%" cellspacing="1" cellpadding="4" align="center" class="forumline"><form method="post" name="post" action="{S_ACTION}">
<tr>
	<th class="thHead" colspan="2">{L_USER_SELECT}</th>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_GUEST_NAME}:</b><br /><span class="gensmall">{L_GUEST_NAME_EXPLAIN}</span></td>
	<td class="row1"><input type="text" class="post" name="guest_username" maxlength="50" size="35" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_USER_NAME}:</b><br /><span class="gensmall">{L_USER_NAME_EXPLAIN}</span></td>
	<td class="row1"><input type="text" class="post" name="username"{AJAXED_USER_LIST} maxlength="50" size="35" /> <input type="submit" name="usersubmit" value="{L_FIND_USERNAME}" class="liteoption" onClick="window.open('{U_SEARCH_USER}', '_phpbbsearch', 'HEIGHT=250,resizable=yes,WIDTH=400');return false;" />{AJAXED_USER_LIST_BOX}</td>
</tr>
<tr>
	<td class="catbottom" align="center" colspan="2"><input type="hidden" name="mode" value="edit" />{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</form></table>
<br />
<div align="center" class="copyright">Post Associator v1.0.0 &copy; 2005, {COPYRIGHT_YEAR} <a href="http://www.phpbbsmith.com" target="_blank" class="copyright">Thoul</a></div>
