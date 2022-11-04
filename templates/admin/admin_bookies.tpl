{BOOKIE_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{PROCESS_HEADER}</h1>

<p>{PROCESS_EXPLAIN}</p>

<!-- BEGIN normal_need -->
<h1>{PROCESS_CURRENT}</h1>
<!-- END normal_need -->
<!-- BEGIN normal_not_need -->
<h1>{PROCESS_CURRENT_NO}</h1>
<!-- END normal_not_need -->

<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline">
<!-- BEGIN normal_need -->	
<tr>
	<th class="thCornerL" nowrap="nowrap" width="20%">{PROCESS_TIME}</th>
	<th class="thTop" nowrap="nowrap" width="20%">{PROCESS_MEETING}</th>
	<th class="thTop" nowrap="nowrap" width="25%">{PROCESS_SELECTION}</th>
	<th class="thTop" nowrap="nowrap" width="20%">{ODDS_ODDS}</th>
	<th class="thCornerR" nowrap="nowrap" width="10%">{WINNER}</th>
</tr>
<!-- END normal_need -->
<!-- BEGIN processbet -->
<form action="{URL}" method="post">
<tr> 
	<td class="{processbet.ROW_CLASS}" align="center" valign="middle"><span class="gen">{processbet.TIME}</span></td>
	<td class="{processbet.ROW_CLASS}" align="center" valign="middle"><span class="gen">{processbet.MEETING}</span></td>
	<td class="{processbet.ROW_CLASS}" align="center" valign="middle"><span class="gen">{processbet.SELECTION}</span></td>
	<td class="{processbet.ROW_CLASS}" align="center" valign="middle"><span class="gen">{processbet.ODDS}</span></td>
	<td class="{processbet.ROW_CLASS}" align="center" valign="middle"><table>
	<tr>
		<td><input type="radio" name="{processbet.WINNER}" value="yes" /> {L_YES}</td>
	</tr>
	<tr>
		<td><input type="radio" name="{processbet.WINNER}" value="no" /> {L_NO}</td>
	</tr>
	<tr>
		<td><input type="radio" name="{processbet.WINNER}" value="ref" /> {L_REF}</td>
	</tr>
	</table></td>
</tr>
<!-- END processbet -->
<!-- BEGIN normal_need -->
<tr>
	<td class="catBottom" colspan="5" align="center"><input type="submit" name="process_na" value="{PROCESS_GO}" class="mainoption" /><input type="hidden" name="betid_arr" value="{NA_BETID_ARR}" /></td>
</tr>
<!-- END normal_need -->
</form></table>
<br />

<!-- BEGIN ew_need -->
<h1>{PROCESS_CURRENT_EW}</h1>
<!-- END ew_need -->
<!-- BEGIN ew_not_need -->
<h1>{PROCESS_CURRENT_EW_NO}</h1>
<!-- END ew_not_need -->

<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline">
<!-- BEGIN ew_need -->
<tr>
	<th class="thCornerL" nowrap="nowrap" width="20%">{PROCESS_TIME}</th>
	<th class="thTop" nowrap="nowrap" width="20%">{PROCESS_MEETING}</th>
	<th class="thTop" nowrap="nowrap" width="25%">{PROCESS_SELECTION}</th>
	<th class="thTop" nowrap="nowrap" width="20%">{ODDS_ODDS}</th>
	<th class="thCornerR" nowrap="nowrap" width="10%">{WINNER}</th>
</tr>
<!-- END ew_need -->
<!-- BEGIN process_ew_bet -->
<form action="{URL}" method="post">
<tr> 
	<td class="{process_ew_bet.ROW_CLASS}" align="center" valign="middle"><span class="gen">{process_ew_bet.TIME}</span></td>
	<td class="{process_ew_bet.ROW_CLASS}" align="center" valign="middle"><span class="gen">{process_ew_bet.MEETING}</span></td>
	<td class="{process_ew_bet.ROW_CLASS}" align="center" valign="middle"><span class="gen">{process_ew_bet.SELECTION}</span></td>
	<td class="{process_ew_bet.ROW_CLASS}" align="center" valign="middle"><span class="gen">{process_ew_bet.ODDS}</span></td>
	<td class="{process_ew_bet.ROW_CLASS}" align="center" valign="middle"><table>
	<tr>
		<td><input type="radio" name="{process_ew_bet.WINNER}" value="eww" /> {L_EWW}</td>
	</tr>
	<tr>
		<td><input type="radio" name="{process_ew_bet.WINNER}" value="ewp" /> {L_EWP}</td>
	</tr>
	<tr>
		<td><input type="radio" name="{process_ew_bet.WINNER}" value="no" /> {L_NO}</td>
	</tr>
	<tr>
		<td><input type="radio" name="{process_ew_bet.WINNER}" value="ref" /> {L_REF}</td>
	</tr>
	</table></td>
</tr>
<!-- END process_ew_bet -->
<!-- BEGIN ew_need -->
<tr>
	<td class="catBottom" colspan="5" align="center"><input type="submit" name="process_na" value="{PROCESS_GO}" class="mainoption" /><input type="hidden" name="betid_arr" value="{AE_BETID_ARR}" /></td>
</tr>
<!-- END ew_need -->
</form></table>
<!-- BEGIN user_normal_need -->
<br />

<h1>{PROCESS_USER_NORM}</h1>

<p>{PROCESS_USER_NORM_EXP}</p>

<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline">
<tr>
	<th class="thCornerL" nowrap="nowrap" width="20%">{PROCESS_TIME}</th>
	<th class="thTop" nowrap="nowrap" width="20%">{PROCESS_MEETING}</th>
	<th class="thTop" nowrap="nowrap" width="25%">{PROCESS_SELECTION}</th>
	<th class="thTop" nowrap="nowrap" width="20%">{ODDS_ODDS}</th>
	<th class="thCornerR" nowrap="nowrap" width="10%">{WINNER}</th>
</tr>
<!-- END user_normal_need -->
<!-- BEGIN processbet_user_norm -->
<form action="{URL}" method="post">
<tr> 
	<td class="{processbet_user_norm.ROW_CLASS}" align="center" valign="middle"><span class="gen">{processbet_user_norm.TIME}</span></td>
	<td class="{processbet_user_norm.ROW_CLASS}" align="center" valign="middle"><span class="gen">{processbet_user_norm.MEETING}</span></td>
	<td class="{processbet_user_norm.ROW_CLASS}" align="center" valign="middle"><span class="gen">{processbet_user_norm.SELECTION}</span></td>
	<td class="{processbet_user_norm.ROW_CLASS}" align="center" valign="middle"><span class="gen">{processbet_user_norm.ODDS}</span></td>
	<td class="{processbet_user_norm.ROW_CLASS}" align="center" valign="middle"><table>
	<tr>
		<td><input type="radio" name="{processbet_user_norm.WINNER}" value="yes" /> {L_YES}</td>
	</tr>
	<tr>
		<td><input type="radio" name="{processbet_user_norm.WINNER}" value="no" /> {L_NO}</td>
	</tr>
	<tr>
		<td><input type="radio" name="{processbet_user_norm.WINNER}" value="ref" /> {L_REF}</td>
	</tr>
	</table></td>
</tr>
<!-- END processbet_user_norm -->
<!-- BEGIN user_normal_need -->
<tr>
	<td class="catBottom" colspan="5" align="center"><input type="submit" name="process_na" value="{PROCESS_GO}" class="mainoption" /><input type="hidden" name="betid_arr" value="{NU_BETID_ARR}" /></td>
</tr>
</form></table>
<!-- END user_normal_need -->
<!-- BEGIN user_ew_need -->
<br />

<h1>{PROCESS_USER_EW}</h1>

<p>{PROCESS_USER_EW_EXP}</p>

<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline">
<tr>
	<th class="thCornerL" nowrap="nowrap" width="20%">{PROCESS_TIME}</th>
	<th class="thTop" nowrap="nowrap" width="20%">{PROCESS_MEETING}</th>
	<th class="thTop" nowrap="nowrap" width="25%">{PROCESS_SELECTION}</th>
	<th class="thTop" nowrap="nowrap" width="20%">{ODDS_ODDS}</th>
	<th class="thCornerR" nowrap="nowrap" width="10%">{WINNER}</th>
</tr>
<!-- END user_ew_need -->
<!-- BEGIN processbet_user_ew -->
<form action="{URL}" method="post">
<tr> 
	<td class="{processbet_user_ew.ROW_CLASS}" align="center" valign="middle"><span class="gen">{processbet_user_ew.TIME}</span></td>
	<td class="{processbet_user_ew.ROW_CLASS}" align="center" valign="middle"><span class="gen">{processbet_user_ew.MEETING}</span></td>
	<td class="{processbet_user_ew.ROW_CLASS}" align="center" valign="middle"><span class="gen">{processbet_user_ew.SELECTION}</span></td>
	<td class="{processbet_user_ew.ROW_CLASS}" align="center" valign="middle"><span class="gen">{processbet_user_ew.ODDS}</span></td>
	<td class="{processbet_user_ew.ROW_CLASS}" align="center" valign="middle"><table>
	<tr>
		<td><input type="radio" name="{processbet_user_ew.WINNER}" value="eww" /> {L_EWW}</td>
	</tr>
	<tr>
		<td><input type="radio" name="{processbet_user_ew.WINNER}" value="ewp" /> {L_EWP}</td>
	</tr>
	<tr>
		<td><input type="radio" name="{processbet_user_ew.WINNER}" value="no" /> {L_NO}</td>
	</tr>
	<tr>
		<td><input type="radio" name="{processbet_user_ew.WINNER}" value="ref" /> {L_REF}</td>
	</tr>
	</table></td>
</tr>
<!-- END processbet_user_ew -->
<!-- BEGIN user_ew_need -->
<tr>
	<td class="catBottom" colspan="5" align="center"><input type="submit" name="process_na" value="{PROCESS_GO}" class="mainoption" /><input type="hidden" name="betid_arr" value="{UE_BETID_ARR}" /></td>
</tr>
</form></table>
<!-- END user_ew_need -->

<br />
<div align="center" class="copyright">Forum Bookmakers {BOOKIE_VERSION} &copy 2004, {COPYRIGHT_YEAR} <a href="http://www.majormod.com" class="copyright" target="_blank">Majorflam</a></div>

