{CONFIG_MENU}{USERCOM_MENU}{SERVER_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{THIS_PAGE_NAME}</h1>

<p>{THIS_PAGE_EXPLAIN}</p>

<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline"><form method="post" action="{S_ACTION}">
<tr>
	<th colspan="2" class="thHead">{L_EDIT}</th>
</tr>
<tr> 
	<td class="row2" colspan="2"><span class="gensmall">{L_ITEMS_REQUIRED}</span></td>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_PAGE_ID}: *</b></td>
	<td class="row2"><b>{PAGE_ID}</b></td>
</tr>
<tr>
	<td class="row1"><b>{L_PAGE_NAME}: *</b></td>
	<td class="row2"><input class="post" type="text" name="page_name" size="32" maxlength="255" value="{PAGE_NAME}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_PAGE_PARM_NAME}:</b><br /><span class="gensmall">{L_PAGE_PARM_NAME_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" name="page_parm_name" size="32" maxlength="255" value="{PAGE_PARM_NAME}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_PAGE_PARM_VALUE}:</b></td>
	<td class="row2"><input class="post" type="text" name="page_parm_value" size="32" maxlength="255" value="{PAGE_PARM_VALUE}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_MEMBER_VIEWS}:</b><br /><span class="gensmall">{L_MEMBER_VIEWS_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="10" name="member_views" value="{MEMBER_VIEWS}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_GUEST_VIEWS}:</b><br /><span class="gensmall">{L_GUEST_VIEWS_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="10" name="guest_views" value="{GUEST_VIEWS}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_DISABLE_PAGE}:</b><br /><span class="gensmall">{L_DISABLE_PAGE_EXPLAIN}</span></td>
	<td class="row2">{CB_DISABLE_PAGE}</td>
</tr>
<tr>
	<td class="row1"><b>{L_PAGE_DISABLED_MESSAGE}:</b><br /><span class="gensmall">{L_PAGE_DISABLED_MESSAGE_EXPLAIN}</span></td>
	<td class="row2"><textarea class="post" name="disabled_message" rows="5" cols="35" wrap="virtual">{DISABLED_MESSAGE}</textarea></td>
</tr>
<tr>
	<td class="row1"><b>{L_AUTH_LEVEL}:</b></td>
	<td class="row2">{S_AUTH_LEVEL_SELECTOR}</td>
</tr>
<tr>
	<td class="row1"><b>{L_MIN_POST_COUNT}:</b><br /><span class="gensmall">{L_MIN_POST_COUNT_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="10" name="min_post_count" value="{MIN_POST_COUNT}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_MAX_POST_COUNT}:</b><br /><span class="gensmall">{L_MAX_POST_COUNT_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="10" name="max_post_count" value="{MAX_POST_COUNT}" /></td>
</tr>
<!-- BEGIN switch_group_selector -->
<tr>
	<td class="row1"><b>{L_PAGE_GROUP}:</b><br /><span class="gensmall">{L_PAGE_GROUP_EXPLAIN}</span></td>
	<td class="row2">{S_PAGE_GROUP_SELECTOR}</td>
</tr>
<!-- END switch_group_selector -->
<tr>
	<td colspan="2" align="center" class="catBottom">{S_HIDDEN_FIELDS}<input type="submit" name="save" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</form></table>
<br />
<div align="center" class="copyright">Page Permissions 1.2.2 &copy; {COPYRIGHT_YEAR} <a href="http://www.phpbbdoctor.com/" target="_blank" class="copyright">drathbun</a></div>
