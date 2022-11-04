{POST_MENU}{ATTACH_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_PRUNE_TITLE}</h1>

<P>{L_PRUNE_DESC}</p>

<table cellspacing="1" cellpadding="4" align="center" class="forumline"><form name="post" method="post" action="{S_PRUNE_ACTION}">
<tr>
	<th colspan="2" class="thHead">{L_PRUNE_TITLE}</th>
</tr>
<tr>
	<td class="row1" width="38%"><b>{L_USER_NAME}:</b></td>
	<td class="row2"><input type="text" class="post" name="username"{AJAXED_USER_LIST} maxlength="50" size="20" /> <input type="submit" name="usersubmit" value="{L_FIND_USERNAME}" class="liteoption" onclick="window.open('{U_SEARCH_USER}', '_phpbbsearch', 'HEIGHT=250,resizable=yes,WIDTH=400');return false;" />{AJAXED_USER_LIST_BOX}</td>
</tr>
<tr>
	<td class="row1"><b>{L_FORUM_NAME}:</b></td>
	<td class="row2"><select name="forum_id">
	  	<option value="all">All Forums</option>
		<!-- BEGIN forums -->
		<option value="{forums.FORUM_ID}">{forums.FORUM_NAME}</option>
		<!-- END forums -->
	</select></td>
</tr>
<tr>
	<td colspan="2" class="catBottom" align="center"><input type="hidden" name="doprune" value="yes"><input type="submit" name="Submit" value="{L_BUTTON}" class="mainoption">&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" name="reset" /></td>
</tr>
</form></table>