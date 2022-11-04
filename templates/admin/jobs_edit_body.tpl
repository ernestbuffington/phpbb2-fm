{CONFIG_MENU}{USERCOM_MENU}{SERVER_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{TITLE}</h1>

<p>{EXPLAIN}</p>

<table width="100%" cellpadding="4" cellspacing="1" align="center" class="forumline"><form action="{S_CONFIG_ACTION}" method="post"><input type="hidden" name="action" value="updatejob" /><input type="hidden" name="jobid" value="{JOB_ID}" />
<tr> 
	<th class="thHead" colspan="2">{TABLE_TITLE}</th>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_JOB_NAME}:</b></td>
	<td class="row2"><input type="text" name="name" size="35" maxlength="32" value="{JOB_NAME}" class="post" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_TYPE}:</b></td>
	<td class="row2"><select name="type">
		<option value="public" {JOB_PUBLIC_SELECTED}>{L_PUBLIC}</option>
		<option value="private" {JOB_PRIVATE_SELECTED}>{L_PRIVATE}</option>
	</select></td>
</tr>
<tr>
	<td class="row1"><b>{L_PAY_AMOUNT}:</b></td>
	<td class="row2"><input type="text" name="pay" size="10" maxlength="10" value="{JOB_PAY}" class="post" /> {POINTS_NAME}</td>
</tr>
<tr>
	<td class="row1"><b>{L_PAY_TIME}:</b></td>
	<td class="row2"><input type="text" name="paytime" size="10" maxlength="30" value="{JOB_PAYTIME}" class="post" /> {L_SECONDS}</td>
</tr>
<tr>
	<td class="row1"><b>{L_MAX_POSITIONS}:</b></td>
	<td class="row2"><input type="text" name="positions" size="5" maxlength="5" value="{JOB_POSITIONS}" class="post" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_REQUIREMENTS}:</b></td>
	<td class="row2"><textarea class="post" name="requirements" rows="5" cols="35">{JOB_REQUIREMENTS}</textarea></td>
</tr>
<tr>
	<td class="catBottom" align="center" colspan="2"><input type="submit" name="update" value="{L_UPDATE_JOB}" class="mainoption" />&nbsp;&nbsp;<input type="submit" name="delete" value="{L_DELETE_JOB}" class="liteoption" /></td>
</tr>
</form></table>
<br />
<div align="center" class="copyright">Jobs 1.1.3 &copy; 2003, {COPYRIGHT_YEAR} <a href="http://www.zarath.com/" class="copyright" target="_blank">Zarath Technologies</a></div>
