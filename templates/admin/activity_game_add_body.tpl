{GAMES_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_TITLE}</h1>
	
<p>{L_EXPLAIN}</p>
	
<table width="100%" cellpadding="4" cellspacing="1" align="center" class="forumline"><form method="post" action="{S_GAME_ACTION}" name="add_game">
<tr>
	<th class="thHead" colspan="2">{L_TITLE}</td>
</tr>
<tr>
	<td colspan="2" class="row2"><span class="gensmall">{L_ITEMS_REQUIRED}</span></td>
</tr>		
<tr>
	<td class="row1" width="50%"><b>{L_NAME}: *</b><br /><span class="gensmall">{L_NAME_INFO}</span></td>
	<td class="row2"><select name="game_name">
		<option selected value="">{V_DEFAULT}</option>
		<!-- BEGIN drop -->						
		<option value="{drop.D_SELECT}">{drop.D_SELECT}</option>
		<!-- END drop -->						
	</select></td>
</tr>
<tr>
	<td class="row1"><b>{L_PROPER_NAME}:</b><br /><span class="gensmall">{L_PROPER_NAME_INFO}</span></td>
	<td class="row2"><input class="post" type="text" size="35" name="game_proper" value="" /></td>
</tr>
<tr>
	<td class="row1"><b>{C_SHORT}:</b><br /><span class="gensmall">{C_EXPLAIN}</span></td>
	<td class="row2"><select name="game_cat">
		<option selected value="">{C_DEFAULT}</option>
		<!-- BEGIN cat -->						
		<option value="{cat.C_SELECT_1}">{cat.C_SELECT_2}</option>
		<!-- END cat -->						
	</select></td>
</tr>
<tr>
	<td class="row1"><b>{L_GAME_PATH}:</b><br /><span class="gensmall">{L_GAME_PATH_INFO}</span></td>
	<td class="row2"><input type="hidden" name="game_path" value="{V_GAME_PATH}" /><b>{V_GAME_PATH}</b></td>
</tr>
<tr>
	<td class="row1"><b>{L_TYPE}</td>
	<td class="row2">{V_TYPE}</td>
</tr>
<tr>
	<td class="row1"><b>{L_GAME_DESC}:</b><br /><span class="gensmall">{L_GAME_DESC_INFO}</span></td>
	<td class="row2"><input class="post" type="text" size="35" name="game_description" value="{DESC}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_GAME_CHARGE}:</b><br /><span class="gensmall">{L_GAME_CHARGE_INFO}</span></td>
	<td class="row2"><select name="game_charge">
		<option selected value="">{V_DEFAULT_2}</option>
		<option value="{V_INC_1}">{V_INC_1}</option>		
		<!-- BEGIN charge -->						
		<option value="{charge.D_SELECT}">{charge.D_SELECT}</option>
		<!-- END charge -->						
	</select></td>
</tr>
<tr>
	<td class="row1"><b>{L_GE_COST}:</b><br /><span class="gensmall">{L_GE_COST_EXP}</span></td>
	<td class="row2"><input class="post" type="text" size="20" name="game_ge_cost" /></td>
</tr>	
<tr>
	<td class="row1"><b>{L_LINKS}</td>
	<td class="row2"><textarea rows="5" cols="35" class="post" name="game_links">{V_LINKS}</textarea></td>
</tr>
<tr>
	<td class="row1"><b>{L_GAME_PER}:</b><br /><span class="gensmall">{L_GAME_PER_INFO}</span></td>
	<td class="row2"><input class="post" type="text" size="20" name="game_reward" value="{REWARD}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_GAME_BONUS}:</b><br /><span class="gensmall">{L_GAME_BONUS_INFO}</span></td>
	<td class="row2"><select name="game_bonus">
		<option selected value="">{V_DEFAULT_3}</option>
		<option value="{V_INC_2}">{V_INC_2}</option>		
		<!-- BEGIN bonus -->						
		<option value="{bonus.D_SELECT}">{bonus.D_SELECT}</option>
		<!-- END bonus -->						
	</select></td>
</tr>
<tr>
	<td class="row1"><b>{L_DISABLE_DES}:</b><br /><span class="gensmall">{L_DISABLE_DS}</span></td>
	<td class="row2"><input type="radio" name="game_disable" value="2"> {L_YES}&nbsp;&nbsp;<input type="radio" name="game_disable" checked="checked" value="1"> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_GAME_GAMELIB}:</b><br /><span class="gensmall">{L_GAME_GAMELIB_INFO}</span></td>
	<td class="row2"><input type="radio" name="game_glib" value="1" {S_USE_GL_YES}> {L_YES}&nbsp;&nbsp;<input type="radio" name="game_glib" checked="checked" value="0" {S_USE_GL_NO}> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_GAME_FLASH}:</b><br /><span class="gensmall">{L_GAME_FLASH_INFO}</span></td>
	<td class="row2"><input type="radio" name="game_flash" checked="checked" value="1" {S_USE_FLASH_YES}> {L_YES}&nbsp;&nbsp;<input type="radio" name="game_flash" value="0" {S_USE_FLASH_NO}> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_GAME_SHOW_SCORE}:</b><br /><span class="gensmall">{L_GAME_SHOW_INFO}</span></td>
	<td class="row2"><input type="radio" name="game_highscore" checked="checked" value="1" {S_SHOW_SCORE_YES}> {L_YES}&nbsp;&nbsp;<input type="radio" name="game_highscore" value="0" {S_SHOW_SCORE_NO}> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_GAME_REVERSE}:</b><br /><span class="gensmall">{L_GAME_REVERSE_INFO}</span></td>
	<td class="row2"><input type="radio" name="game_reverse" value="1" {S_REVERSE_LIST_YES}> {L_YES}&nbsp;&nbsp;<input type="radio" name="game_reverse" checked="checked" value="0" {S_REVERSE_LIST_NO}> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_HIGHSCORE_LIMIT}:</b><br /><span class="gensmall">{L_HIGHSCORE_INFO}</span></td>
	<td class="row2"><input class="post" type="text" size="5" name="game_highscore" value="{HIGHSCORE_LIMIT}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_GAME_SIZE}:</b><br /><span class="gensmall">{L_GAME_SIZE_INFO}</span></td>
	<td class="row2">{L_WIDTH} <input class="post" type="text" size="5" name="game_width" value="{V_GAME_WIDTH}"> x {L_HEIGHT} <input class="post" type="text" size="5" name="game_height" value="{V_GAME_HEIGHT}"></td>
</tr>
<tr>
	<td class="row1"><b>{L_FUNCTIONS}:</b><br /><span class="gensmall">{L_FUNCTIONS_EXP}</span></td>
	<td class="row2"><input type="checkbox" name="game_mouse"> {L_MOUSE}&nbsp;&nbsp;<input type="checkbox" name="game_keyboard"> {L_KEYBOARD}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_INSTRUCTIONS}:</b><br /><span class="gensmall">{L_INSTRUCTIONS_INFO}</span></td>
	<td class="row2"><textarea rows="5" cols="35" wrap="virtual" name="game_instructions" class="post">{GAME_INSTRUCTIONS}</textarea></td>
</tr>
<tr>
	<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input class="mainoption" type="submit" value="{L_SUBMIT}" onclick="document.add_game.submit()" />&nbsp;&nbsp;<input class="liteoption" type="reset" value="{L_RESET}" /></td>
</tr>
</form></table>