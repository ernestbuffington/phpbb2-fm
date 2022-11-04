{CONFIG_MENU}{USERCOM_MENU}{SERVER_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{TABLE_TITLE}</h1>

<p>{L_PAGE_EXPLAIN}</p>

<table width="100%" cellpadding="4" cellspacing="1" align="center" class="forumline"><form action="{S_CONFIG_ACTION}" method="post"> <input type="hidden" name="action" value="update_globals" />
<tr> 
	<th class="thHead" colspan="2">{TABLE_TITLE}</th>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_JOB_STATUS}:</b></td>
	<td class="row2"><input type="radio" name="jobs_status" value="1"{JOBS_STATUS_ENABLED}> {L_ENABLED}&nbsp;&nbsp;<input type="radio" name="jobs_status" value="0"{JOBS_STATUS_DISABLED}> {L_DISABLED}</td>
</tr>
<tr>
	<td class="row1"><b>{L_JOB_INDEX}:</b></td>
	<td class="row2"><select name="index_type">
		<option value="0"{INDEX_1}>{L_COMPACT}</option>
		<option value="1"{INDEX_2}>{L_EXTENDED}</option>
	</select></td>
</tr>
<tr>
	<td class="row1"><b>{L_PAY_TYPE}:</b></td>
	<td class="row2"><select name="pay_type">	
		<option value="0"{PAY_TYPE_1}>{L_PAY_PP}</option>
		<option value="1"{PAY_TYPE_2}>{L_PAY_ALL}</option>
	</select></td>
</tr>
<tr>
	<td class="row1"><b>{L_MAX_JOBS}:</b></td>
	<td class="row2"><input type="text" name="limit" size="5" value="{MAX_JOBS}" maxlength="5" class="post" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_JOB_VIEWTOPIC}:</b></td>
	<td class="row2"><input type="radio" name="jobs_viewtopic" value="1"{VIEWTOPIC_YES}> {L_YES}&nbsp;&nbsp;<input type="radio" name="jobs_viewtopic" value="0"{VIEWTOPIC_NO}> {L_NO}</td>
</tr>
<tr>
	<td class="catBottom" colspan="2" align="center"><input type="submit" value="{L_SUBMIT}" name="Update" class="mainoption" />&nbsp;&nbsp;<input type="reset" name="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</form></table>
<br />

<table width="100%" cellpadding="4" cellspacing="1" align="center" class="forumline"><form method="post" action="{S_CONFIG_ACTION}" name="post"> <input type="hidden" name="action" value="edit_user" />
<tr> 
	<th class="thHead">{USER_TABLE_TITLE}</th>
</tr>
<tr>
	<td class="row1" align="center"><b>{L_USERNAME}:</b> <input type="text" class="post" name="username" maxlength="25" size="25" /> <input type="submit" value="{L_EDIT_JOBS}" class="liteoption" /> <input type="submit" name="usersubmit" value="{L_FIND_USERNAME}" class="liteoption" onClick="window.open('./../search.php?mode=searchuser', '_phpbbsearch', 'HEIGHT=250,resizable=yes,WIDTH=400');return false;" /></td>
</tr>
</form></table>
<br />

<table width="100%" cellpadding="4" cellspacing="1" align="center" class="forumline"><form method="post" action="{S_CONFIG_ACTION}"><input type="hidden" name="action" value="editjob" />
<tr> 
	<th class="thHead" colspan="2">{JOB_TABLE_TITLE}</th>
</tr>
<tr>
	<td class="row1" width="50%"><select name="job">
		<!-- BEGIN listrow -->
		<option value="{listrow.JOB_ID}">{listrow.JOB_NAME}</option>
		<!-- END listrow -->
	</select></td>
	<td class="row2"><input type="submit" value="{L_EDIT_JOB}" class="liteoption" /></td>
</tr>
</form></table>
<br />

<table width="100%" cellpadding="4" cellspacing="1" align="center" class="forumline"><form method="post" action="{S_CONFIG_ACTION}"><input type="hidden" name="action" value="createjob" />
<tr>
	<th colspan="2" class="thHead" align="center">{ADD_TABLE_TITLE}</th>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_JOB_NAME}:</b></td>
	<td class="row2"><input type="text" name="name" size="35" maxlength="32" class="post" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_TYPE}:</b></td>
	<td class="row2"><select name="type"><option value="public">{L_PUBLIC}</option><option value="private">{L_PRIVATE}</option></select></td>
</tr>
<tr>
	<td class="row1"><b>{L_PAY_AMOUNT}:</b></td>
	<td class="row2"><input type="text" name="pay" size="10" maxlength="10" class="post" /> {POINTS_NAME}</td>
</tr>
<tr>
	<td class="row1"><b>{L_PAY_TIME}:</b></td>
	<td class="row2"><input type="text" name="paytime" size="10" maxlength="30" value="500000" class="post" /> {L_SECONDS}</td>
</tr>
<tr>
	<td class="row1"><b>{L_MAX_POSITIONS}:</b></td>
	<td class="row2"><input type="text" name="positions" size="5" maxlength="5" class="post" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_REQUIREMENTS}:</b></td>
	<td class="row2"><textarea class="post" name="requirements" rows="5" cols="50"></textarea></td>
</tr>
<tr>
	<td class="catBottom" colspan="2" align="center"><input type="submit" value="{L_CREATE_JOB}" class="mainoption" />&nbsp;&nbsp;<input type="reset" name="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</form></table>
<br />
<div align="center" class="copyright">Jobs 1.1.3 &copy; 2003, {COPYRIGHT_YEAR} <a href="http://www.zarath.com/" class="copyright" target="_blank">Zarath Technologies</a></div>
