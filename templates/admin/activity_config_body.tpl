{GAMES_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_ACTIVITY_CONFIG}</h1>

<p>{L_ACTIVITY_CONFIG_EXPLAIN} {L_ACTIVITY_CONFIG_EXPLAIN1}</p>
	
<table width="100%" cellpadding="4" cellspacing="1" align="center" class="forumline"><form action="{S_CONFIG_ACTION}" method="post">
<tr>
	<th class="thHead" colspan="2">{L_ACTIVITY_CONFIG}</td>
</tr>
<tr>
	<td width="50%" class="row1"><b>{L_GAMES_PER_PAGE}:</b><br /><span class="gensmall">{L_GAMES_PER_INFO}</span></td>
   	<td class="row2"><input class="post" type="text" size="5" name="games_per_page" value="{S_GAMES_PER_PAGE}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_MAX_CHARGE}:</b></td>
	<td class="row2"><input class="post" type="text" size="5" name="ina_default_charge" value="{S_MAX_CHARGE}" /></td>	
</tr>
<tr>
	<td class="row1"><b>{L_INCREMENT}:</b></td>
	<td class="row2"><input class="post" size="5" type="text" name="ina_default_increment" value="{S_INCREMENT}" /></td>	
</tr>
<tr>
	<td class="row1"><b>{L_PATH}:</b></td>
	<td class="row2"><input class="post" type="text" name="ina_default_g_path" value="{S_GAME_PATH}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_REWARD}:</b></td>
	<td class="row2"><input class="post" size="5" type="text" name="ina_default_g_reward" value="{S_REWARD}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_HEIGHT}:</b></td>
	<td class="row2"><input class="post" size="5" type="text" name="ina_default_g_height" value="{S_HEIGHT}" /></td>	
</tr>
<tr>
	<td class="row1"><b>{L_WIDTH}:</b></td>
	<td class="row2"><input class="post" size="5" type="text" name="ina_default_g_width" value="{S_WIDTH}" /></td>	
</tr>
<tr>
	<td class="row1"><b>{L_CASH_NAME}:</b></td>
	<td class="row2"><input class="post" type="text" name="ina_cash_name" value="{S_CASH_NAME}" /></td>	
</tr>
<tr>
	<td class="row1"><b>{L_JACKPOT}:</b></td>
	<td class="row2"><input class="post" size="5" type="text" name="ina_jackpot_pool" value="{S_JACKPOT}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_MAX_GAMBLE}:</b></td>
	<td class="row2"><input class="post" size="5" type="text" name="ina_max_gamble" value="{S_MAX_GAMBLE}" /></td>	
</tr>
<tr>
	<td class="row1"><b>{L_CURRENT}:</b><br /><span class="gensmall">{L_CURRENT_EXPLAIN}</span></td>
	<td class="row2"><span class="gensmall">{S_CURRENT}</span><br /><select name="ina_default_order">
		<option selected value="{CURRENT_OPTION}">{L_CURRENT_OPTION}</option>
		<option value="1">{L_CURRENT_OPTION_1}</option>	
		<option value="2">{L_CURRENT_OPTION_2}</option>		
		<option value="3">{L_CURRENT_OPTION_3}</option>			
		<option value="4">{L_CURRENT_OPTION_4}</option>
		<option value="5">{L_CURRENT_OPTION_5}</option>			
		<option value="6">{L_CURRENT_OPTION_6}</option>
		<option value="7">{L_CURRENT_OPTION_7}</option>			
		<option value="8">{L_CURRENT_OPTION_8}</option>
		<option value="9">{L_CURRENT_OPTION_9}</option>
		<option value="10">{L_CURRENT_OPTION_10}</option>								
		<option value="11">{L_CURRENT_OPTION_11}</option>								
		<option value="12">{L_CURRENT_OPTION_12}</option>								
	</select></td>	
</tr>
<tr>
	<td class="row1"><b>{L_USE_MAX_GAMES_PER_DAY}</b></td>
   	<td class="row2"><input type="radio" name="ina_use_max_games_per_day" value="1" {S_USE_MAX_GAMES_PER_DAY_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="ina_use_max_games_per_day" value="0" {S_USE_MAX_GAMES_PER_DAY_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_MAX_PLAYED_COUNT}</b></td>
	<td class="row2"><input size="5" type="text" class="post" name="ina_max_games_per_day" value="{S_MAX_PLAYED_COUNT}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_REQ_POST_COUNT}:</b></td>
   	<td class="row2"><input type="radio" name="ina_post_block" value="1" {S_USE_POSTS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="ina_post_block" value="0" {S_USE_POSTS_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_POST_COUNT}:<b></td>
	<td class="row2"><input size="5" type="text" class="post" name="ina_post_block_count" value="{S_POST_COUNT}" /></td>
</tr>	
<tr>
	<td class="row1"><b>{L_REQ_USER_TIME}:</b></td>
   	<td class="row2"><input type="radio" name="ina_join_block" value="1" {S_USE_TIME_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="ina_join_block" value="0" {S_USE_TIME_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_USER_TIME}:</b></td>
	<td class="row2"><input size="5" type="text" class="post" name="ina_join_block_count" value="{S_TIME_COUNT}" /></td>	
</tr>
<tr>
	<td class="row1"><b>{L_CHALLENGE}:</b></td>
   	<td class="row2"><input type="radio" name="ina_challenge" value="1" {S_USE_CHALLENGE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="ina_challenge" value="0" {S_USE_CHALLENGE_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_CHALLENGE_MSG}</td>
	<td class="row2"><input size="35" type="text" class="post" name="ina_challenge_msg" value="{S_CHALLENGE_MSG}" /></td>	
</tr>
<tr>
	<td class="row1"><b>{L_CHALLENGE_SUB_MSG}:</b></td>
	<td class="row2"><input size="35" type="text" class="post" name="ina_challenge_sub" value="{S_CHALLENGE_SUB_MSG}" /></td>	
</tr>
<tr>
	<td class="row1"><b>{L_TROPHY}:</b></td>
   	<td class="row2"><input type="radio" name="ina_pm_trophy" value="1" {S_USE_TROPHY_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="ina_pm_trophy" value="0" {S_USE_TROPHY_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_TROPHY_MSG}</td>
	<td class="row2"><input size="35" type="text" class="post" name="ina_pm_trophy_msg" value="{S_TROPHY_MSG}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_TROPHY_SUB_MSG}:</b></td>
	<td class="row2"><input type="text" size="35" class="post" name="ina_pm_trophy_sub" value="{S_TROPHY_SUB_MSG}" /></td>	
</tr>
<tr>
	<td class="row1"><b>{L_SHOW_NEW}:</b></td>
   	<td class="row2"><input type="radio" name="ina_use_newest" value="1" {S_USE_NEWEST_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="ina_use_newest" value="0" {S_USE_NEWEST_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_SHOW_NEW_LIMIT}:</b></td>
	<td class="row2"><input size="5" class="post" type="text" name="ina_new_game_count" value="{S_GAME_COUNT}" /></td>	
</tr>
<tr>
	<td class="row1"><b>{L_BUTTON_LINK}:</b></td>
   	<td class="row2"><input type="radio" name="ina_button_option" value="1" {S_USE_BUTTON_YES} /> {L_POPUP}&nbsp;&nbsp;<input type="radio" name="ina_button_option" value="0" {S_USE_BUTTON_NO} /> {L_PARENT}</td>
</tr>
<tr>
	<td class="row1"><b>{L_GAME_LENGTH}:</b><br /><span class="gensmall">{L_GAME_LENGTH_EXPLAIN}</span></td>
	<td class="row2"><input size="5" type="text" class="post" name="ina_new_game_limit" value="{S_GAME_LIMIT}" /></td>	
</tr>
<tr>
	<td class="row1"><b>{L_POP_LIMIT}:</b><br /><span class="gensmall">{L_POP_LIMIT_EXPLAIN}</span></td>
	<td class="row2"><input type="text" class="post" size="5" name="ina_pop_game_limit" value="{S_POP_LIMIT}" /></td>	
</tr>
<tr>
	<td class="row1"><b>{L_USE_RATING_REWARD}</b></td>
   	<td class="row2"><input type="radio" name="ina_use_rating_reward" value="1" {S_USE_RATING_REWARD_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="ina_use_rating_reward" value="0" {S_USE_RATING_REWARD_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_USE_RATING_REWARD_VALUE}</b></td>
	<td class="row2"><input size="5" type="text" class="post" name="ina_rating_reward" value="{S_RATING_REWARD}" /></td>	
</tr>
<tr>
	<td class="row1"><b>{L_DAILY_GAME}:</b></td>
   	<td class="row2"><input type="radio" name="ina_use_daily_game" value="1" {S_USE_DAILY_GAME_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="ina_use_daily_game" value="0" {S_USE_DAILY_GAME_NO} /> {L_NO}</td>
</tr>		
<tr>
	<td class="row1"><b>{L_RANDOM_DAILY}</td>
   	<td class="row2"><input type="radio" name="ina_daily_game_random" value="1" {S_USE_DAILY_RANDOM_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="ina_daily_game_random" value="0" {S_USE_DAILY_RANDOM_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_DAILY_ID}:</b></td>
	<td class="row2"><select name="daily_game_id">{S_OPTIONS}</select></td>	
</tr>		
<tr>
	<th class="thHead" colspan="2">{L_CONFIG_EXTRAS}</th>
</tr>
<tr>
	<td class="row1"><b>{L_FORCE_REGO}</td>
   	<td class="row2"><input type="radio" name="ina_force_registration" value="1" {S_FORCE_REGO_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="ina_force_registration" value="0" {S_FORCE_REGO_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_ALLOW_GUEST}</td>
   	<td class="row2"><input type="radio" name="ina_guest_play" value="1" {S_GUEST_PLAY_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="ina_guest_play" value="0" {S_GUEST_PLAY_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_DISABLE_SUBMIT_SCORES_M}</b><br /><span class="gensmall">{L_DISABLE_SUBMIT_SCORES_M_EXPLAIN}</span></td>
   	<td class="row2"><input type="radio" name="ina_disable_submit_scores_m" value="1" {S_DISABLE_SUBMIT_SCORES_M_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="ina_disable_submit_scores_m" value="0" {S_DISABLE_SUBMIT_SCORES_M_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_DISABLE_SUBMIT_SCORES_G}</b><br /><span class="gensmall">{L_DISABLE_SUBMIT_SCORES_G_EXPLAIN}</span></td>
   	<td class="row2"><input type="radio" name="ina_disable_submit_scores_g" value="1" {S_DISABLE_SUBMIT_SCORES_G_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="ina_disable_submit_scores_g" value="0" {S_DISABLE_SUBMIT_SCORES_G_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_USE_ONLINE}:</b></td>
   	<td class="row2"><input type="radio" name="ina_use_online" value="1" {S_USE_ONLINE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="ina_use_online" value="0" {S_USE_ONLINE_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_DISABLE_CHEAT}:</b></td>
   	<td class="row2"><input type="radio" name="ina_disable_cheat" value="1" {S_DISABLE_CHEAT_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="ina_disable_cheat" value="0" {S_DISABLE_CHEAT_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_SHOW_PROFILE}:</b></td>
   	<td class="row2"><input type="radio" name="ina_show_view_profile" value="1" {S_SHOW_VIEW_PROFILE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="ina_show_view_profile" value="0" {S_SHOW_VIEW_PROFILE_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_SHOW_VIEW_TOPIC}:</b></td>
   	<td class="row2"><input type="radio" name="ina_show_view_topic" value="1" {S_SHOW_VIEW_TOPIC_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="ina_show_view_topic" value="0" {S_SHOW_VIEW_TOPIC_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_HOF_VIEWTOPIC}:</b></td>
   	<td class="row2"><input type="radio" name="ina_hof_viewtopic" value="1" {S_HOF_VIEWTOPIC_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="ina_hof_viewtopic" value="0" {S_HOF_VIEWTOPIC_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_PLAYERS_INDEX}:</b></td>
   	<td class="row2"><input type="radio" name="ina_players_index" value="1" {S_PLAYERS_INDEX_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="ina_players_index" value="0" {S_PLAYERS_INDEX_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_USE_SHOUTBOX}</td>
   	<td class="row2"><input type="radio" name="ina_use_shoutbox" value="1" {S_USE_SHOUTBOX_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="ina_use_shoutbox" value="0" {S_USE_SHOUTBOX_NO} /> {L_NO}</td>
</tr>	
<tr>
	<td class="row1"><b>{L_DISABLE_EVERYTHING}</b><br /><span class="gensmall">{L_DISABLE_WHY}</span></td>
   	<td class="row2"><input type="radio" name="ina_disable_everything" value="1" {S_DISABLE_EVERYTHING_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="ina_disable_everything" value="0" {S_DISABLE_EVERYTHING_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_DISABLE_TROPHY}</b><br /><span class="gensmall">{L_DISABLE_WHY}</span></td>
   	<td class="row2"><input type="radio" name="ina_disable_trophy_page" value="1" {S_DISABLE_TROPHY_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="ina_disable_trophy_page" value="0" {S_DISABLE_TROPHY_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_DISABLE_COMMENTS}</b><br /><span class="gensmall">{L_DISABLE_WHY}</span></td>
   	<td class="row2"><input type="radio" name="ina_disable_comments_page" value="1" {S_DISABLE_COMMENTS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="ina_disable_comments_page" value="0" {S_DISABLE_COMMENTS_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_DISABLE_GAMBLE}</b><br /><span class="gensmall">{L_DISABLE_WHY}</span></td>
   	<td class="row2"><input type="radio" name="ina_disable_gamble_page" value="1" {S_DISABLE_GAMBLE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="ina_disable_gamble_page" value="0" {S_DISABLE_GAMBLE_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_DISABLE_CALLENGES}</b><br /><span class="gensmall">{L_DISABLE_WHY}</span></td>
   	<td class="row2"><input type="radio" name="ina_disable_challenges_page" value="1" {S_DISABLE_CHALLENGES_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="ina_disable_challenges_page" value="0" {S_DISABLE_CHALLENGES_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_DISABLE_TOP5}</b><br /><span class="gensmall">{L_DISABLE_WHY}</span></td>
   	<td class="row2"><input type="radio" name="ina_disable_top5_page" value="1" {S_DISABLE_TOP5_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="ina_disable_top5_page" value="0" {S_DISABLE_TOP5_NO} /> {L_NO}</td>
</tr>	
<tr>
	<td class="row1"><b>{L_USE_TROPHY}</b><br /><span class="gensmall">{L_USE_TROPHY_EXPLAIN}</span></td>
   	<td class="row2"><input type="radio" name="ina_use_trophy" value="1" {S_USE_TROPHY_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="ina_use_trophy" value="0" {S_USE_TROPHY_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_USE_GAMELIB}:</b><br /><span class="gensmall">{L_USE_GL_INFO}</span></td>
   	<td class="row2"><input type="radio" name="use_gamelib" value="1" {S_USE_GL_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="use_gamelib" value="0" {S_USE_GL_NO} /> {L_NO}</td>
</tr>
<!-- BEGIN display_gamelib_menu -->
<tr>
	<td class="row1"><b>{L_GL_GAME_PATH}:</b><br /><span class="gensmall">{L_GL_PATH_INFO}</span></td>
   	<td class="row2"><input class="post" type="text" size="20" name="games_path" value="{S_GAMES_PATH}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_GL_LIB_PATH}:</b><br /><span class="gensmall">{L_GL_LIB_INFO}</span></td>
	<td class="row2"><input class="post" type="text" size="20" name="gamelib_path" value="{S_GAMELIB_PATH}" /></td>
</tr>
<!-- END display_gamelib_menu -->
<tr>
	<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</form></table>
