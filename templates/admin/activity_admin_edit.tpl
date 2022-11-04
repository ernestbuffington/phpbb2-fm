{GAMES_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_TITLE}</h1>

<p>{L_EXPLAIN}</p>
	
<!-- BEGIN cat_choice -->
<table align="center" width="60%" class="forumline" cellpadding="4" cellspacing="1">	
<!-- BEGIN rows -->
<tr>
	<th class="thHead">{cat_choice.rows.ONE}</th>
</tr>
<tr>
	<td align="center" class="row1">{cat_choice.rows.TWO}</td>	
</tr>
<!-- END rows -->
</table>
<br />
<!-- END cat_choice -->

<!-- BEGIN editing -->
<table align="center" cellpadding="4" cellspacing="1" width="100%" class="forumline"><form name="save" method="post" action="{RETURN}">
<tr>
	<th class="thHead" colspan="2">{V_TWO}</th>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_ONE}</td>
	<td class="row2"><input type="text" name="game_name" value="{V_ONE}" class="post" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_TWO}</td>
	<td class="row2"><input type="text" name="game_proper" value="{V_TWO}" class="post" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_CATEGORY}</td>
	<td class="row2">{CAT}</td>
</tr>	
<tr>
	<td class="row1"><b>{L_THREE}</td>
	<td class="row2"><input type="text" name="game_path" value="{V_THREE}" size="30" class="post" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_TYPE}</td>
	<td class="row2">{V_TYPE}</td>
</tr>	
<tr>
	<td class="row1"><b>{L_SIZE}</td>
	<td class="row2">{L_SIX} <input type="text" name="game_width" value="{V_SIX}" size="6" class="post" /> x {L_SEVEN} <input type="text" name="game_height" value="{V_SEVEN}" size="6" class="post" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_FUNCTIONS}:</b><br /><span class="gensmall">{L_FUNCTIONS_EXP}</span></td>
	<td class="row2"><input type="checkbox" name="game_mouse" {MOUSE} /> {L_MOUSE}&nbsp;&nbsp;<input type="checkbox" name="game_keyboard" {KEYBOARD} /> {L_KEYBOARD}</td>
</tr>	
<tr>
	<td class="row1"><b>{L_EIGHT}</td>
	<td class="row2"><input type="text" name="game_bonus" value="{V_EIGHT}" size="10" class="post" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_NINE}</td>
	<td class="row2"><input type="text" name="game_reward" value="{V_NINE}" size="10" class="post" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_TEN}</td>
	<td class="row2"><input type="text" name="game_charge" value="{V_TEN}" size="10" class="post" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_GE_COST}:</b><br /><span class="gensmall">{L_GE_COST_EXP}</span></td>
	<td class="row2"><input type="text" name="game_ge_cost" value="{V_GE_COST}" size="10" class="post" /></td>
</tr>		
<tr>
	<td class="row1"><b>{L_ELEVEN}</td>
	<td class="row2"><input type="text" name="game_highscore" value="{V_ELEVEN}" size="10" class="post" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_LINKS}</td>
	<td class="row2"><textarea rows="5" cols="30" class="post" name="game_links">{V_LINKS}</textarea></td>
</tr>	
<tr>
	<td class="row1"><b>{L_FOUR}</td>
	<td class="row2"><textarea rows="5" cols="30" class="post" name="game_description">{V_FOUR}</textarea></td>
</tr>	
<tr>
	<td class="row1"><b>{L_FIVE}</td>
	<td class="row2"><textarea rows="5" cols="30" size="" class="post" name="game_instructions">{V_FIVE}</textarea></td>
</tr>
<tr>
	<td class="row1"><b>{L_REVERSE}</td>
	<td class="row2"><input type="radio" name="game_reverse" value="1" {REVERSE_Y}> {L_YES}&nbsp;&nbsp;<input type="radio" name="game_reverse" value="0" {REVERSE_N}> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_SCORES}</td>
	<td class="row2"><input type="radio" name="game_showscores" value="1" {SCORES_Y}> {L_YES}&nbsp;&nbsp;<input type="radio" name="game_showscores" value="0" {SCORES_N}> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_FLASH}</td>
	<td class="row2"><input type="radio" name="game_flash" value="1" {FLASH_Y}> {L_YES}&nbsp;&nbsp;<input type="radio" name="game_flash" value="0" {FLASH_N}> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_GLIB}</td>
	<td class="row2"><input type="radio" name="game_glib" value="1" {GLIB_Y}> {L_YES}&nbsp;&nbsp;<input type="radio" name="game_glib" value="0" {GLIB_N}> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_DISABLE}</td>
	<td class="row2"><input type="radio" name="game_disable" value="1" {DIS_Y}> {L_YES}&nbsp;&nbsp;<input type="radio" name="game_disable" value="0" {DIS_N}> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_PARENT}</td>
	<td class="row2"><input type="radio" name="game_parent" value="1" {PARENT_Y}> {L_YES}&nbsp;&nbsp;<input type="radio" name="game_parent" value="0" {PARENT_N}> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_POPUP}</td>
	<td class="row2"><input type="radio" name="game_popup" value="1" {POPUP_Y}> {L_YES}&nbsp;&nbsp;<input type="radio" name="game_popup" value="0" {POPUP_N}> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_RESET_SCORES}</td>
	<td class="row2"><input type="checkbox" name="reset_scores" /> {L_YES}</td>
</tr>
<tr>
	<td class="row1"><b>{L_RESET_JACKPOT}</td>
	<td class="row2"><input type="checkbox" name="reset_jackpot" /> {L_YES}</td>
</tr>
<tr>
	<td class="row1"><b>{L_DELETE_GAME}</td>
	<td class="row2"><input type="checkbox" name="delete_game" /> {L_YES}</td>
</tr>
<tr>
	<td colspan="2" class="catBottom" align="center"><input type="hidden" name="game_id" value="{ID}"><input type="hidden" name="action" value="save"><input type="submit" class="mainoption" value="{L_SUBMIT}" onclick="document.save.submit()" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</form></table>
<!-- END editing -->