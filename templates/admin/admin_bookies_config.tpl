{BOOKIE_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{CONFIG_HEADER}</h1>

<p>{CONFIG_EXPLAIN}</p>

<table width="100%" align="center"><form action="{S_BOOKIE_ADMIN}" method="post">
<tr>
	<td align="right">{CONFIG_SELECT} <input type="submit" name="config" value="{L_GO}" class="liteoption" /></td>
</tr>
</form></table>
<table cellpadding="4" cellspacing="1" width="100%" align="center" class="forumline"><form action="{S_BOOKIE_ADMIN}" method="post">

<!-- BEGIN switch_config -->
<tr>
	<th class="thHead" colspan="2">{CONFIG_HEADER}</th>		
</tr>
<tr> 
  	<td class="row1" width="50%"><b>{WELCOME}:</b><br /><span class="gensmall">{WELCOME_EXP}</span></td>
  	<td class="row2"><textarea name="welcome_text" rows="5" cols="35" class="post" />{WELCOME_TEXT}</textarea></td>
</tr>
<tr> 
  	<td class="row1"><b>{LEADERBOARD}:</b><br /><span class="gensmall">{LEADERBOARD_EXP}</span></td>
  	<td class="row2"><select name="leader">{LEADER_BOX}</td>
</tr>
<!-- END switch_config -->

<!-- BEGIN switch_game -->
<tr>
	<th class="thHead" colspan="2">{BOOKIE_SETTINGS}</th>
</tr>
<tr> 
  	<td class="row1" width="50%"><b>{FRAC_DEC}:</b><br /><span class="gensmall">{FRAC_DEC_EXP}</span></td>
  	<td class="row2"><input type="radio" name="fracdec" value="0" {ALLOW_FRACTIONAL_YES} /> {L_FRAC}&nbsp;&nbsp;<input type="radio" name="fracdec" value="1" {ALLOW_FRACTIONAL_NO} /> {L_DEC}</td>
</tr>
<tr> 
 	<td class="row1"><b>{ALLOW_USER_BETS}:</b><br /><span class="gensmall">{ALLOW_USER_BETS_EXP}</span></td>
  	<td class="row2">&nbsp;<input type="radio" name="allowuserbets" value="1" {ALLOW_USER_BETS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allowuserbets" value="0" {ALLOW_USER_BETS_NO} /> {L_NO}<br />&nbsp;<input type="radio" name="allowuserbets" value="2" {ALLOW_USER_BETS_COND} /> {L_CONDITION}</td>
</tr>
<tr> 
  	<td class="row1"><b>{ALLOW_EACH_WAY}:</b><br /><span class="gensmall">{ALLOW_EACH_WAY_EXP}</span></td>
  	<td class="row2"><input type="radio" name="alloweachway" value="1" {ALLOW_EACH_WAY_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="alloweachway" value="0" {ALLOW_EACH_WAY_NO} /> {L_NO}</td>
</tr>
<tr> 
  	<td class="row1"><b>{ALLOW_EDIT_STAKE}:</b><br /><span class="gensmall">{ALLOW_EDIT_STAKE_EXP}</span></td>
  	<td class="row2"><input type="radio" name="editstake" value="1" {ALLOW_EDIT_STAKE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="editstake" value="0" {ALLOW_EDIT_STAKE_NO} /> {L_NO}</td>
</tr>
<tr> 
 	<td class="row1"><b>{ALLOW_SEND_PM}:</b><br /><span class="gensmall">{ALLOW_SEND_PM_EXP}</span></td>
  	<td class="row2"><input type="radio" name="allowpm" value="1" {ALLOW_PM_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allowpm" value="0" {ALLOW_PM_NO} /> {L_NO}</td>
</tr>
<tr> 
 	<td class="row1"><b>{ALLOW_MIN_BET}:</b><br /><span class="gensmall">{ALLOW_MIN_BET_EXP}</span></td>
  	<td class="row2""><input class="post" type="text" maxlength="6" size="5" name="min_stake" value="{MIN_STAKE}" /></td>
</tr>
<tr> 
 	<td class="row1"><b>{ALLOW_MAX_BET}:</b><br /><span class="gensmall">{ALLOW_MAX_BET_EXP}</span></td>
  	<td class="row2"><input class="post" type="text" maxlength="6" size="5" name="max_stake" value="{MAX_STAKE}" /></td>
</tr>
<!-- END switch_game -->

<!-- BEGIN switch_misc -->
<tr>
	<th class="thHead" colspan="2">{MISC_SETTINGS}</th>
</tr>
<tr> 
 	<td class="row1" width="50%"><b>{ALLOW_COMMISSION}:</b><br /><span class="gensmall">{ALLOW_COMMISSION_EXP}</span></td>
  	<td class="row2"><input type="radio" name="allow_commission" value="1" {ALLOW_COM_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_commission" value="0" {ALLOW_COM_NO} /> {L_NO}</td>
</tr>
<tr> 
  	<td class="row1"><b>{COMMISSION}:</b><br /><span class="gensmall">{COMMISSION_EXP}</span></td>
  	<td class="row2"><select name="commission">{COMMISSION_BOX}</td>
</tr>
<tr> 
  	<td class="row1"><b>{BET_RESTRICT}:</b><br /><span class="gensmall">{BET_RESTRICT_EXP}</span></td>
  	<td class="row2"><input type="radio" name="restrict" value="1" {ALLOW_RESTRICT_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="restrict" value="0" {ALLOW_RESTRICT_NO} /> {L_NO}</td>
</tr>
<!-- END switch_misc -->

<tr>
	<td class="catBottom" colspan="2" align="center"><input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</form></table>
<br />
<div align="center" class="copyright">Forum Bookmakers {BOOKIE_VERSION} &copy 2004, {COPYRIGHT_YEAR} <a href="http://www.majormod.com" class="copyright" target="_blank">Majorflam</a></div>
