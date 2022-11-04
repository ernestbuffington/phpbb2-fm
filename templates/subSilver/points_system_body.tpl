{ERROR_BOX} 

<table width="100%" cellspacing="2" cellpadding="2" align="center">
<tr> 
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></td>
</tr>
</table>

{CPL_MENU_OUTPUT}

<table width="100%" cellpadding="4" cellspacing="1" align="center" class="forumline"><form action="{S_POST_ACTION}" method="post" name="post">
<tr> 
	<th class="thHead" colspan="2">{L_POINTS_TITLE}</th>
</tr>
<!-- BEGIN switch_points_cp -->
<tr> 
	<td class="row1"><b class="gen">{L_USERNAME}:</b></td>
	<td class="row2"><input type="text" class="post" name="username"{AJAXED_USER_LIST_1} maxlength="{LIMIT_USERNAME_MAX_LENGTH}" size="25" tabindex="1" value="{USERNAME}" /> &nbsp;<input type="submit" name="usersubmit" value="{L_FIND_USERNAME}" class="liteoption" onClick="window.open('{U_SEARCH_USER}', '_phpbbsearch', 'HEIGHT=250,resizable=yes,WIDTH=400');return false;" />{AJAXED_USER_LIST_2}</td>
</tr>
<tr> 
	<td class="row1"><b class="gen">{L_MASS_EDIT}:</b><br /><span class="gensmall">{L_MASS_EDIT_EXPLAIN}</span></td>
	<td class="row2"><textarea tabindex="2" class="post" name="mass_username" rows="5" cols="50"></textarea></td>
</tr>
<tr>
	<td class="row1"><b class="gen">{L_METHOD}:</b><br /><span class="gensmall">{L_ADD_SUBTRACT}</span></td>
	<td class="row2"><input type="radio" name="method" value="1" checked /> {L_ADD}&nbsp;&nbsp;<input type="radio" name="method" value="0" /> {L_SUBTRACT}</td>
</tr>
<tr> 
	<td class="row1"><b class="gen">{L_AMOUNT}:</b><br /><span class="gensmall">{L_AMOUNT_GIVE_TAKE}</span></td>
	<td class="row2"><input tabindex="3" type="text" class="post" name="amount" maxlength="11" value="0" size="11" /></td>
</tr>
<tr> 
	<td class="row1"><b class="gen">{L_REASON}:</b><br /><span class="gensmall">{L_REASON_DONATE}</span></td>
	<td class="row2"><input tabindex="4" class="post" type="text" name="reason_donate" maxlength="50" value="" size="30" /></td>
	</tr>
<!-- END switch_points_cp -->
<!-- BEGIN switch_points_donate -->
<tr> 
	<td class="row1" width="38%"><b class="gen">{L_USERNAME}:</b><br /><span class="gensmall">{L_DONATE_TO}</span></td>
	<td class="row2"><input type="text" class="post" name="username"{AJAXED_USER_LIST_1} maxlength="{LIMIT_USERNAME_MAX_LENGTH}" size="25" tabindex="1" value="{USERNAME}" /> &nbsp;<input type="submit" name="usersubmit" value="{L_FIND_USERNAME}" class="liteoption" onClick="window.open('{U_SEARCH_USER}', '_phpbbsearch', 'HEIGHT=250,resizable=yes,WIDTH=400');return false;" />{AJAXED_USER_LIST_2}</td>
</tr>
<tr> 
	<td class="row1"><b class="gen">{L_AMOUNT}:</b><br /><span class="gensmall">{L_AMOUNT_GIVE}</span></td>
	<td class="row2"><input tabindex="2" type="text" class="post" name="amount" maxlength="11" value="" size="11" /></td>
</tr>
<tr> 
	<td class="row1"><b class="gen">{L_REASON}:</b><br /><span class="gensmall">{L_REASON_DONATE}</span></td>
	<td class="row2"><input tabindex="3" class="post" type="text" name="reason_donate" maxlength="50" value="" size="30" /></td>
</tr>
<!-- END switch_points_donate -->
<tr> 
	<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" tabindex="5" class="mainoption" />&nbsp;&nbsp;<input tabindex="6" type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</form></table>
<!-- BEGIN switch_points_donate -->
	</td>
</tr>
</table>
<!-- END switch_points_donate -->
<br />
<div align="center" class="copyright">Points System {MOD_VERSION} &copy; 2002, {COPYRIGHT_YEAR} <a href="http://www.robbieshields.net" class="copyright" target="_blank">Robbie Shields</a></div>
<br />
<table width="100%" align="center"> 
  <tr> 
	<td align="right" >{JUMPBOX}</td> 
  </tr> 
</table> 
