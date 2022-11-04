<table width="100%" cellspacing="2" cellpadding="2" align="center">
<tr> 
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></td>
</tr>
</table>

<table width="100%" cellpadding="4" cellspacing="1" align="center" class="forumline">
<tr> 
	<th width="25%" class="thCornerL">&nbsp;{L_STATISTIC}&nbsp;</th>
	<th width="25%" class="thTop">&nbsp;{L_VALUE}&nbsp;</th>
	<th width="25%" class="thTop">&nbsp;{L_STATISTIC}&nbsp;</th>
	<th width="25%" class="thCornerR">&nbsp;{L_VALUE}&nbsp;</th>
</tr>
<tr>
	<td class="row1">{L_TOTAL_JOBS}:</td>
	<td class="row2"><b>{TOTAL_JOBS}</b></td>
	<td class="row1">{L_EMPLOYED}:</td>
	<td class="row2"><b>{TOTAL_EMPLOYED}</b></td>
</tr>
<tr>
	<td class="row1">{L_TOTAL_POSITIONS}:</td>
	<td class="row2"><b>{TOTAL_JOB_POSITIONS}</b></td>
	<td class="row1">{L_TAKEN}:</td>
	<td class="row2"><b>{TOTAL_JOBS_TAKEN}</b></td>
</tr>
<tr>
	<td class="row1">{L_REMAINING}:</td>
	<td class="row2"><b>{TOTAL_FREE_JOBS}</b></td>
	<td class="row1">&nbsp;</td>
	<td class="row2">&nbsp;</td>
</tr>
</table>
<br />

<table width="100%" cellpadding="4" cellspacing="1" align="center" class="forumline">
<tr> 
	<th class="thHead" colspan="2">{L_TITLE}</th>
</tr>
<!-- BEGIN switch_cant_get_job -->
<tr>
	<td class="row2" colspan="2" align="center"><b class="gensmall">{L_CANT_BE_EMPLOYED}</b></td>
</tr>
<!-- END switch_cant_get_job -->
<!-- BEGIN switch_has_job -->
<tr>
	<form method="post" action="{S_MODE_ACTION}"><input type="hidden" name="action" value="quit">
	<td class="row1" width="38%"><b>{L_CURRENT_JOBS}:</b></td>
	<td class="row2"><select name="job">
<!-- END switch_has_job -->
		<!-- BEGIN listrow -->
		<option value="{listrow.JOB_NAME}">{listrow.JOB_NAME}</option>
		<!-- END listrow -->
<!-- BEGIN switch_has_job -->
	</select></td>
</tr>
<tr>
	<td class="catBottom" align="center" colspan="2"><input type="submit" value="{L_B_QUIT}" class="mainoption" /></td>
	</form>
</tr>
<!-- END switch_has_job -->
<!-- BEGIN switch_has_no_job -->
<tr>
	<td class="row2" colspan="2" align="center"><b class="gensmall">{L_YOURE_UNEMPLOYED}</b></td>
</tr>
<!-- END switch_has_no_job -->
<!-- BEGIN switch_can_get_job -->
<tr>
	<form method="post" action="{S_MODE_ACTION}"><input type="hidden" name="action" value="start">
	<td class="row1" width="38%"><b>{L_AVAILABLE_JOBS}:</b></td>
	<td class="row2"><select name="job">
<!-- END switch_can_get_job -->
		<!-- BEGIN listrow2 -->
		<option value="{listrow2.JOB_ID}">{listrow2.JOB_NAME}</option>
		<!-- END listrow2 -->
<!-- BEGIN switch_can_get_job -->
	</select></td>
</tr>
<tr>
	<td class="catBottom" align="center" colspan="2"><input type="submit" value="{L_B_ACCEPT}" class="mainoption" /></td>
	</form>
</tr>
<!-- END switch_can_get_job -->
</table>
<br />
<table width="100%" cellspacing="2" align="center">
  <tr> 
	<td align="right">{JUMPBOX}</td>
  </tr>
</table>
<br />
<div align="center" class="copyright">Jobs 1.1.3 &copy; 2003, {COPYRIGHT_YEAR} <a href="http://www.zarath.com/" class="copyright" target="_blank">Zarath Technologies</a></div>
