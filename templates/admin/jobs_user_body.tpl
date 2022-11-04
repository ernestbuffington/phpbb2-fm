{CONFIG_MENU}{USERCOM_MENU}{SERVER_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{TITLE}</h1>

<p>{EXPLAIN}</p>

<table width="50%" cellpadding="4" cellspacing="1" align="center" class="forumline">
<tr> 
	<th class="thHead" colspan="2">{TITLE}</th>
</tr>
<tr>
	<form action="{S_CONFIG_ACTION}" method="post"><input type="hidden" name="action" value="updateuser" /><input type="hidden" name="userid" value="{USER_ID}" />
	<td class="row1"><select name="job">
		<!-- BEGIN listrow2 -->
		<option value="{listrow2.JOB_NAME}">{listrow2.JOB_NAME}</option>
		<!-- END listrow2 -->
	</select></td>
	<td class="row2"><input type="submit" name="remjob" value="{L_FIRE}" class="liteoption" /></td>
	</form>
</tr>
<tr>
	<form method="post" action="{S_CONFIG_ACTION}"><input type="hidden" name="action" value="updateuser" /><input type="hidden" name="userid" value="{USER_ID}" />
	<td class="row1"><select name="job">
		<!-- BEGIN listrow -->
		<option value="{listrow.JOB_NAME}">{listrow.JOB_NAME}</option>
		<!-- END listrow -->
	</select></td>
	<td class="row2"><input type="submit" name="addjob" value="{L_ADD_JOB}" class="liteoption" /></td>
	</form>
</tr>
</table>
<br />
<div align="center" class="copyright">Jobs 1.1.3 &copy; 2003, {COPYRIGHT_YEAR} <a href="http://www.zarath.com/" class="copyright" target="_blank">Zarath Technologies</a></div>
