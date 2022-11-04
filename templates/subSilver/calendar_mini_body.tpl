<table width="100%" cellpadding="4" cellspacing="1" class="forumline">
<!-- BEGIN switch_mini_cal_add_events -->
<form name="mini_cal" id ="mini_cal" action="{U_MINI_CAL_ADD_EVENT}" method="post">
<!-- END switch_mini_cal_add_events -->
  <tr>
  	<td class="catHead"><a href="{U_CALENDAR}" class="cattitle">{L_CALENDAR}</a></td>
  </tr>
  <tr>
	<td class="row1" align="center">
  	<table width="100%">
        <tr>
		<td align="left" colspan="2">{U_PREV_MONTH}</td>
		<td colspan="3" align="center"><b class="gensmall">{L_MINI_CAL_MONTH}</b></td>
		<td align="right" colspan="2">{U_NEXT_MONTH}</td>
	  </tr>
	  <tr>
			<th class="thCornerL" align="center" width="14%">{L_MINI_CAL_SUN}</th>
			<th class="thTop" align="center" width="14%">{L_MINI_CAL_MON}</th>
			<th class="thTop" align="center" width="14%">{L_MINI_CAL_TUE}</th>
			<th class="thTop" align="center" width="14%">{L_MINI_CAL_WED}</th>
			<th class="thTop" align="center" width="14%">{L_MINI_CAL_THU}</th>
			<th class="thTop" align="center" width="14%">{L_MINI_CAL_FRI}</th>
			<th class="thCornerR" align="center" width="14%">{L_MINI_CAL_SAT}</th>
	  </tr>
	  <!-- BEGIN mini_cal_row -->
	  <tr>
	  <!-- BEGIN mini_cal_days -->
		<td class="row1" align="center"><span class="gensmall">{mini_cal_row.mini_cal_days.MINI_CAL_DAY}</span></td>
	  <!-- END mini_cal_days -->
	  </tr>
	  <!-- END mini_cal_row -->
	</table>
	</td>
  </tr>
  <!-- BEGIN switch_mini_cal_birthdays -->
  <tr>
	<td class="row1"><span class="gensmall">{L_WHOSBIRTHDAY_TODAY}</span></td>
	</tr>
  <tr>
	<td class="row1"><span class="gensmall">{L_WHOSBIRTHDAY_WEEK}</span></td>
  </tr> 
  <!-- END switch_mini_cal_birthdays -->
</table>
<div style="padding: 2px;"></div>

<table width="100%" cellpadding="4" cellspacing="1" class="forumline">
  <!-- BEGIN switch_mini_cal_events -->
  <tr>
	<td class="catHead"><span class="cattitle">{L_MINI_CAL_EVENTS}</span></td>
  </tr>
  <!-- END switch_mini_cal_events -->
  <!-- BEGIN mini_cal_events -->
  <tr>
	<td class="row1"><span class="gensmall"><b>&bull;</b> {mini_cal_events.MINI_CAL_EVENT_DATE} - <a href="{mini_cal_events.U_MINI_CAL_EVENT}" class="gensmall">{mini_cal_events.S_MINI_CAL_EVENT}</a></span></td>
  </tr>
  <!-- END mini_cal_events -->
  <!-- BEGIN mini_cal_no_events -->
  <tr>
	<td class="row1" align="center"><span class="gensmall">{L_MINI_CAL_NO_EVENTS}</span></td>
  </tr>
  <!-- END mini_cal_no_events -->
  <!-- BEGIN switch_mini_cal_add_events -->
  <tr>
	<td class="row1" align="center">{S_MINI_CAL_EVENTS_FORUMS_LIST} <input type="submit" value="{L_MINI_CAL_ADD_EVENT}" class="liteoption" /><input type="hidden" name="mode" id="mode" value="newtopic" /></td>
</form>
  </tr>
  <!-- END switch_mini_cal_add_events -->
</table>
