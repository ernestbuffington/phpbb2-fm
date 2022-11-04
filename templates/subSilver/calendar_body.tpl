<script language="Javascript" src="mods/calendar/calendar_flyover.js"></script>
<form method="get" action="posting.php">
<input type="hidden" name="mode" value="newtopic">
<input type="hidden" name="sid" value="{SESSION_ID}">
<input type="hidden" name="back_to_calendar" value="{BACK_TO_CALENDAR}">
<table width="100%" cellspacing="2" cellpadding="2"> 
  <tr> 
    <td align="left" valign="bottom"><span class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></span></td> 
    <td align="right" valign="bottom"><span class="nav">{LEGEND}</span></td> 
  </tr> 
</table> 
<table class="forumline" width="100%" cellspacing="1" cellpadding="0">
  <tr>
    <td colspan="7" class="catHead" height="28">
      <table width="100%" cellspacing="0" cellpadding="0">
        <tr>
		<td width="33%"><a href="{U_PREV_YEAR}">{I_PREV_YEAR}</a><a href="{U_PREV_MONTH}">{I_PREV_MONTH}</a></td> 
		<td align="center" style="text-align: center;" width="33%"><span class="cattitle">{L_CURRENT_MONTH} {L_CURRENT_YEAR}</span></td> 
		<td align="right" style="text-align: right;" width="33%"><span class="genmed">{FORUM_CHOOSER}</span><a href="{U_NEXT_MONTH}">{I_NEXT_MONTH}</a><a href="{U_NEXT_YEAR}">{I_NEXT_YEAR}</a></td> 
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <!-- BEGIN switch_sunday_beginning -->
	<th width="14%" class="thTop">{L_SUNDAY}</th>
    <!-- END switch_sunday_beginning -->
    <th width="14%" class="thTop">{L_MONDAY}</th>
    <th width="14%" class="thTop">{L_TUESDAY}</th>
    <th width="14%" class="thTop">{L_WEDNESDAY}</th>
    <th width="14%" class="thTop">{L_THURSDAY}</th>
    <th width="14%" class="thTop">{L_FRIDAY}</th>
    <th width="14%" class="thTop">{L_SATURDAY}</th>
    <!-- BEGIN switch_sunday_end -->
	<th width="14%" class="thTop">{L_SUNDAY}</th>
    <!-- END switch_sunday_end -->
  </tr>
  <!-- BEGIN date_row -->
  <tr>
    <!-- BEGIN date_cell -->
      <!-- BEGIN switch_blank_cells -->
    <td width="14%" height="90" valign="top" class="row1" colspan="{date_row.date_cell.BLANK_COLSPAN}">&nbsp;</td>
      <!-- END switch_blank_cells -->
      <!-- BEGIN switch_date_cells -->
    <td width="14%" height="90" valign="top" class="row1">
      <table cellpadding="0" cellspacing="0" width="100%" height="100%">
        <tr>
	  <!-- BEGIN switch_date_today -->
          <td class="daterowtoday" align="top" height="1" align="left">
	  <!-- END switch_date_today -->
	  <!-- BEGIN switch_date_otherday -->
          <td class="date{date_row.date_cell.DATE_CLASS}" align="top" height="1" align="left">
	  <!-- END switch_date_otherday -->
	  <table width="100%" cellspacing="0" cellpadding="0">
	  <tr>
		  <td class="mainmenu">
		  <!-- BEGIN switch_date_today -->
		  <span class="mainmenu" style="color: #FFFFFF; background-color: {T_TH_COLOR2};"><b>{date_row.date_cell.DATE}</b></span></td>
		  <!-- END switch_date_today -->
		  <!-- BEGIN switch_date_otherday -->
		  <span class="mainmenu"><b>{date_row.date_cell.DATE}</b></span></td>
		  <!-- END switch_date_otherday -->
		  </td>
		  <td class="mainmenu" align="right">
		  <input type="image" valign="middle" name="event_day({date_row.date_cell.EVENT_DATE})" src="{I_ADD_EVENT}" alt="{L_ADD_EVENT}" />
		  </td>
	  </tr></table>
	</td>
        </tr>
        <tr>
          <td valign="top">
          <!-- BEGIN date_event -->
            {date_row.date_cell.switch_date_cells.date_event.U_EVENT}
          <!-- END date_event -->
        </tr>
      </table>
    </td>
      <!-- END switch_date_cells -->
    <!-- END date_cell -->
  </tr>
  <!-- END date_row -->
  <tr> 
	</form>
	<form action="{U_CALENDAR}" method="get"> 
	<td align="center" colspan="7" class="catBottom"><span class="genmed">{L_JUMP_TO}: {S_MONTHS}&nbsp;{S_YEARS}&nbsp; <input type="submit" value="{L_SUBMIT}" class="liteoption"></span></td> 
  </tr>
</table></form>

<table width="100%" cellspacing="2" align="center"> 
  <tr> 
	<td align="right" valign="middle">{JUMPBOX}</td> 
  </tr> 
</table> 
