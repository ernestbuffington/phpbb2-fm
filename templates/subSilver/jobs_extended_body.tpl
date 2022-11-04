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
<!-- BEGIN switch_has_job -->
<tr>
	<td class="catHead" colspan="5"><span class="cattitle">{L_CURRENT_JOBS}</span</td>
</tr>
<!-- END switch_has_job -->
<!-- BEGIN switch_can_get_job -->
<tr>
	<td class="catHead" colspan="5"><span class="cattitle">{L_AVAILABLE_JOBS}</span></td>
</tr>
<!-- END switch_can_get_job -->
<!-- BEGIN switch_cant_get_job -->
<tr>
	<td class="row2" colspan="5" align="center"><b class="gensmall">{L_CANT_BE_EMPLOYED}</b></td>
</tr>
<!-- END switch_cant_get_job -->
<!-- BEGIN switch_has_no_job -->
<tr>
	<td class="row2" colspan="5" align="center"><b class="gensmall">{L_YOURE_UNEMPLOYED}</b></td>
</tr>
<!-- END switch_has_no_job -->
<!-- BEGIN switch_has_job -->
<tr>
	<th class="thCornerL">&nbsp;{L_JOB}&nbsp;</th>
	<th class="thTop" width="150">&nbsp;{L_PAY}&nbsp;</th>
	<th class="thTop" width="150">&nbsp;{L_PAY_LENGTH}&nbsp;</th>
	<th class="thTop" width="150">&nbsp;{L_STARTED}&nbsp;</th>
	<th class="thCornerR" width="150">&nbsp;{L_LAST_PAID}&nbsp;</th>
</tr>
<!-- END switch_has_job -->
<!-- BEGIN listrow -->
<tr>
	<td class="{listrow.ROW}"><a href="{listrow.S_MODE_ACTION}" class="genmed">{listrow.JOB_NAME}</a></td>
	<td class="{listrow.ROW}" align="center">{listrow.JOB_PAY}</td>
	<td class="{listrow.ROW}" align="center">{listrow.JOB_LENGTH}</td>
	<td class="{listrow.ROW}" align="center">{listrow.JOB_STARTED}</td>
	<td class="{listrow.ROW}" align="center">{listrow.JOB_LAST_PAID}</td>
</tr>
<!-- END listrow -->

<!-- BEGIN switch_can_get_job -->
<tr>
	<th class="thCornerR">&nbsp;{L_JOB}&nbsp;</th>
	<th class="thTop" width="150">&nbsp;{L_PAY}&nbsp;</th>
	<th class="thTop" width="150">&nbsp;{L_PAY_LENGTH}&nbsp;</th>
	<th class="thCornerR" width="100">&nbsp;{L_POSITIONS}&nbsp;</th>
</tr>
<!-- END switch_can_get_job -->
<!-- BEGIN listrow2 -->
<tr>
	<td class="{listrow2.ROW}"><a href="{listrow2.S_MODE_ACTION}" class="genmed">{listrow2.JOB_NAME}</a></td>
	<td class="{listrow2.ROW}" align="center">{listrow2.JOB_PAY}</td>
	<td class="{listrow2.ROW}" align="center">{listrow2.JOB_PAY_TIME}</td>
	<td class="{listrow2.ROW}" align="center">{listrow2.JOB_LEFT} / {listrow2.JOB_POSITIONS}</td>
</tr>
<!-- END listrow2 -->
<tr>
	<td class="catBottom" colspan="5">&nbsp;</td>
</tr>
</table>
<br />
<table width="100%" cellspacing="2" align="center">
  <tr> 
	<td align="right">{JUMPBOX}</td>
  </tr>
</table>
<br />
<div align="center" class="copyright">Jobs 1.1.3 &copy; 2003, {COPYRIGHT_YEAR} <a href="http://www.zarath.com/" class="copyright" target="_blank">Zarath Technologies</a></div>
