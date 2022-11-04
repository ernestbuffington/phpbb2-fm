<!-- BEGIN links -->
<table width="100%" align="center" cellpadding="2" cellspacing="2">				   
<tr>	
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> {links.U_ACTIVITY}{links.U_GAMBLING}{links.U_GAMBLING_2}</td>		
</tr>
</table>	
<!-- END links -->

<!-- BEGIN stats -->
<table class="forumline" align="center" width="100%" cellpadding="4" cellspacing="1">
<tr>
	<th class="thCornerL" width="5%">#</th>
	<th class="thTop" width="20%">{stats.L_TITLE_2}</th>
	<th class="thTop" width="20%">{stats.L_TITLE_3}</th>
	<th class="thTop" width="15%">{stats.L_TITLE_4}</th>
	<th class="thTop" width="20%">{stats.L_TITLE_5}</th>
	<th class="thCornerR" width="20%">{stats.L_TITLE_6}</th>							
</tr>	
<!-- END stats -->
<!-- BEGIN stats_rows -->
<tr>	
	<td align="center" class="{stats_rows.ROW_CLASS}">{stats_rows.GAME_NUMBER}</td>
	<td align="center" class="{stats_rows.ROW_CLASS}">{stats_rows.WINNER_LINK}</td>
	<td align="center" class="{stats_rows.ROW_CLASS}">{stats_rows.LOSER_LINK}</td>
	<td align="center" class="{stats_rows.ROW_CLASS}">{stats_rows.GAME_IMAGE}</td>
	<td align="center" class="{stats_rows.ROW_CLASS}">{stats_rows.AMOUNT}</td>
	<td align="center" class="{stats_rows.ROW_CLASS}">{stats_rows.DATE}</td>									
</tr>	
<!-- END stats_rows -->
<!-- BEGIN stats -->
<tr>
	<td colspan="6" class="catBottom">&nbsp;</td>
</tr>
</table>
<!-- END stats -->

<form method="post" name="gambling" action="activity.php?page=gambling">
<!-- BEGIN user_selection -->
<table class="forumline" align="center" width="100%" cellpadding="4" cellspacing="1">
<tr>
	<th class="thHead">{user_selection.L_USER_SELECTION_TITLE}</th>
</tr>
<tr>
	<td align="center" class="row1"><select name="user_option_one">
		<option selected value="0">{user_selection.L_USER_SELECTION_DEFAULT}</option>
		<!-- END user_selection -->				
		<!-- BEGIN user_selection_array -->				
		<option value="{user_selection_array.USER_ID}">{user_selection_array.USERNAME}</option>
		<!-- END user_selection_array -->
		<!-- BEGIN user_selection -->								
	</select> / <input type="text" class="post" name="user_option_two" value="{user_selection.L_TEXT_BOX_DEFAULT}" /></td>			
</tr>	
</table>
<br />
<!-- END user_selection -->

<!-- BEGIN game_selection -->	
<table class="forumline" align="center" width="100%" cellpadding="4" cellspacing="1">
<tr>
	<th class="thCornerL" width="5%">#</th>
	<th class="thTop" width="10%">{game_selection.L_GAME_RADIO}</th>
	<th class="thTop" width="30%">{game_selection.L_GAME_IMAGE}</th>
	<th class="thCornerR" width="40%">{game_selection.L_GAME_DESC}</th>			
</tr>
<!-- END game_selection -->	
<!-- BEGIN game_selection_rows -->
<tr>	
	<td align="center" class="{game_selection_rows.ROW_CLASS}">{game_selection_rows.GAME_NUMBERS}</td>
	<td align="center" class="{game_selection_rows.ROW_CLASS}"><input type="radio" name="game_selected" value="{game_selection_rows.GAME_ID}"></td>
	<td align="center" class="{game_selection_rows.ROW_CLASS}">{game_selection_rows.GAME_IMAGE}</td>
	<td align="center" class="{game_selection_rows.ROW_CLASS}">{game_selection_rows.GAME_DESC}</td>					
</tr>
<!-- END game_selection_rows -->
<!-- BEGIN bet_selection -->		
<tr>
	<th colspan="4" class="thTop">{bet_selection.L_BET_TITLE}</th>
</tr>
<tr>
	<td colspan="4" class="row1"><input type="radio" name="bet_selection" value="1"> {bet_selection.L_BET_FOR_FUN}&nbsp;&nbsp;<input type="radio" name="bet_selection" value="2"> {bet_selection.L_BET_FOR_FEE}</td>
</tr>
<tr>
	<td colspan="4" class="row1">{bet_selection.L_BET_DESC} <span class="gensmall"><i>({bet_selection.L_MAX_BET_DESC})</i>{bet_selection.L_POINTS_OFF}</span> &nbsp;<input type="text" name="bet_amount" value="" size="10" class="post" /></td>
</tr>
<tr>
	<th colspan="4" class="thTop">{bet_selection.L_SUBMIT_TITLE}</th>
</tr>
<tr>
	<td colspan="4" align="center" class="catBottom"><input class="mainoption" type="hidden" name="mode" value="submit_gamble"><input class="mainoption" type="submit" value="{bet_selection.L_GAME_SUBMIT}" onchange="document.gambling.submit()" /></td>	
</tr>
</form></table>
<!-- END bet_selection -->
<br />
<table width="100%" cellspacing="2" align="center"> 
<tr> 
	<td align="right" valign="middle" nowrap="nowrap">{JUMPBOX}</td> 
</tr> 
</table> 
<br />
<div align="center" class="copyright">Activity Mod Plus v1.1.0 &copy; 2003, {COPYRIGHT_YEAR} <a href="http://phpbb-amod.com" target="_blank" class="copyright">aUsTiN</a></div>
		