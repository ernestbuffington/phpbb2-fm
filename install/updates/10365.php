<?php
	
if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}


//
// Modify phpBB core-scema	
//
$sql = 'ALTER TABLE ' . $table_prefix . 'forums ADD COLUMN `forum_enter_limit` MEDIUMINT(8) UNSIGNED DEFAULT "0"';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $table_prefix . 'ranks DROP COLUMN `rank_reward`';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $table_prefix . 'users DROP COLUMN `user_money`';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'users ADD COLUMN `user_trophies` INT(10) NOT NULL DEFAULT "0"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'users ADD COLUMN `ina_cheat_fix` INT(100) NOT NULL DEFAULT "0"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'users ADD COLUMN `ina_game_playing` INT(10) NOT NULL DEFAULT "0"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'users ADD COLUMN `ina_last_visit_page` VARCHAR(255) NOT NULL DEFAULT ""';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'users ADD COLUMN `ina_games_today` INT(10) NOT NULL DEFAULT "0"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'users ADD COLUMN `ina_last_playtype` VARCHAR(255) DEFAULT "parent" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'users ADD COLUMN `ina_games_played` INT(10) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'users ADD COLUMN `admin_allow_points` TINYINT(1) DEFAULT "1" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'users MODIFY COLUMN `user_points` INT NOT NULL';
_sql($sql, $errored, $error_ary);
			 

//
// Modify Fully Modded core-schema
//
$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'gk_data';
_sql($sql, $errored, $error_ary);
$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'gk_quiz';
_sql($sql, $errored, $error_ary);
$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'gk_quiz_as';
_sql($sql, $errored, $error_ary);
$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'gk_quiz_qs';
_sql($sql, $errored, $error_ary);
$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'gk_quiz_quizers';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $table_prefix . 'lottery_history MODIFY COLUMN `id` INT UNSIGNED NOT NULL auto_increment';
_sql($sql, $errored, $error_ary);
			
$sql = 'ALTER TABLE ' . $table_prefix . 'shout DROP INDEX `shout_id`, ADD PRIMARY KEY (shout_id)';
_sql($sql, $errored, $error_ary);
						

//
// Create new Fully Modded core-schema
//
$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'bookie_bets';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'bookie_bets (bet_id int(5) NOT NULL auto_increment, user_id int(11) NOT NULL DEFAULT "0", time int(11) NOT NULL DEFAULT "0", meeting VARCHAR(50) NOT NULL DEFAULT "", selection VARCHAR(100) NOT NULL DEFAULT "", bet int(11) NOT NULL DEFAULT "0", win_lose int(11) NOT NULL DEFAULT "0", odds_1 int(8) NOT NULL DEFAULT "0", odds_2 int(8) NOT NULL DEFAULT "0", checked int(2) NOT NULL DEFAULT "0", edit_time int(11) NOT NULL DEFAULT "0", orig_time int(11) NOT NULL DEFAULT "0", PRIMARY KEY (bet_id))';
_sql($sql, $errored, $error_ary);
			
$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'bookie_stats';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'bookie_stats (`user_id` int(11) NOT NULL DEFAULT "0", `total_win` int(11) NOT NULL DEFAULT "0", `total_lose` int(11) NOT NULL DEFAULT "0", `netpos` int(11) NOT NULL DEFAULT "0", `bets_placed` int(6) NOT NULL DEFAULT "0") TYPE=MYISAM';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'ina_ban';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'ina_ban (id int(10) DEFAULT "0" NOT NULL, username VARCHAR(40) DEFAULT NULL)';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'ina_categories';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'ina_categories (cat_id MEDIUMINT(9) NOT NULL auto_increment, cat_name VARCHAR(25) DEFAULT NULL, cat_desc text NOT NULL, cat_img VARCHAR(255) DEFAULT "" NOT NULL, PRIMARY KEY (cat_id))';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'ina_challenge_tracker';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'ina_challenge_tracker (`user` INT(10) DEFAULT "0", `count` INT(25) DEFAULT "0")';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'ina_challenge_users';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'ina_challenge_users (`user_to` INT(10) DEFAULT "0", `user_from` INT(10) DEFAULT "0", `count` INT(25) DEFAULT "0")';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'ina_cheat_fix';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'ina_cheat_fix (game_id int(10) DEFAULT "0" NOT NULL, player int(10) DEFAULT "0")';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'ina_data';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'ina_data (`version` VARCHAR(255) DEFAULT NULL)';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'ina_favorites';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'ina_favorites (`user` INT(10) DEFAULT "0" NOT NULL, `games` TEXT)';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'ina_gamble';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'ina_gamble (game_id int(20) DEFAULT "0", sender_id int(11) DEFAULT "0", reciever_id int(11) DEFAULT "0", amount int(10) DEFAULT "0", winner_id int(11) DEFAULT "0", loser_id int(11) DEFAULT "0", winner_score int(11) DEFAULT "0" NOT NULL, loser_score int(11) DEFAULT "0" NOT NULL, date int(20) DEFAULT NULL, been_paid int(11) DEFAULT "0" NOT NULL)';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'ina_gamble_in_progress';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'ina_gamble_in_progress (game_id int(20) DEFAULT "0", sender_id int(11) DEFAULT "0", reciever_id int(11) DEFAULT "0", sender_score int(20) DEFAULT "0", reciever_score int(20) DEFAULT "0", sender_playing int(1) DEFAULT "0" NOT NULL, reciever_playing int(1) DEFAULT "0" NOT NULL)';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'ina_games';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'ina_games (game_id MEDIUMINT(9) NOT NULL auto_increment, game_name VARCHAR(25) DEFAULT NULL, game_path VARCHAR(255) DEFAULT NULL, game_desc VARCHAR(255) DEFAULT NULL, game_charge int(11) UNSIGNED DEFAULT "0", game_reward int(11) UNSIGNED NOT NULL DEFAULT "0", game_bonus smallint(5) UNSIGNED DEFAULT "0", game_use_gl tinyint(3) UNSIGNED DEFAULT "0", game_flash tinyint(1) UNSIGNED NOT NULL DEFAULT "0", game_show_score tinyint(1) UNSIGNED NOT NULL DEFAULT "1", win_width smallint(6) NOT NULL DEFAULT "0", win_height smallint(6) NOT NULL DEFAULT "0", highscore_limit VARCHAR(255) DEFAULT NULL, reverse_list tinyint(1) NOT NULL DEFAULT "0", played int(10) UNSIGNED NOT NULL DEFAULT "0", instructions text, disabled int(1) NOT NULL DEFAULT "1", install_date int(20) NOT NULL DEFAULT "0", proper_name text NOT NULL, cat_id int(4) NOT NULL DEFAULT "0", PRIMARY KEY (game_id))';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'ina_last_game_played';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix .'ina_last_game_played (game_id int(20) DEFAULT NULL, user_id int(11) DEFAULT NULL, date int(20) DEFAULT NULL)';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'ina_rating_votes';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix .'ina_rating_votes (game_id int(15) NOT NULL DEFAULT "0", rating int(15) NOT NULL DEFAULT "0", date int(15) NOT NULL DEFAULT "0", player int(15) NOT NULL DEFAULT "0")';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'ina_scores';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix .'ina_scores (game_name VARCHAR(255) DEFAULT NULL, player VARCHAR(40) DEFAULT NULL, score float(10,2) NOT NULL DEFAULT "0.00", date int(11) DEFAULT NULL)';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'ina_sessions';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix .'ina_sessions (playing_time int(15) NOT NULL DEFAULT "0", playing_id int(10) NOT NULL DEFAULT "0", playing int(11) NOT NULL DEFAULT "0")';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'ina_top_scores';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'ina_top_scores (game_name VARCHAR(255) DEFAULT NULL, player MEDIUMINT(8) NOT NULL, score float(10,2) NOT NULL DEFAULT "0.00", date int(11) DEFAULT NULL)';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'ina_trophy_comments';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'ina_trophy_comments (game VARCHAR(255) NOT NULL DEFAULT "", player int(10) DEFAULT NULL, comment text NOT NULL, date int(15) NOT NULL DEFAULT "0", score int(20) NOT NULL DEFAULT "0")';
_sql($sql, $errored, $error_ary);

$sql = 'TRUNCATE TABLE ' . $table_prefix . 'lottery_history';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS '. $table_prefix .'pjirc';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE '. $table_prefix .'pjirc (pjirc_name VARCHAR(255) DEFAULT "" NOT NULL, pjirc_value VARCHAR(255) DEFAULT "" NOT NULL, PRIMARY KEY (pjirc_name)) TYPE=MYISAM';		
_sql($sql, $errored, $error_ary);


//
// Modify phpBB core-data
// phpbb_config data
$config_data = array( 'challenges_sent' => 0, 'current_ina_date' => time(), 'games_path' => '', 'games_per_page' => 20, 'gamelib_path' => '', 'ina_button_option' => 2, 'ina_cash_name' => 'Tokens', 'ina_challenge' => 1, 'ina_challenge_msg' => 'Your Trophy For %g% Has Been Challenged By %n%.', 'ina_challenge_sub' => 'Trophy Challenge In Progress', 'ina_default_charge' => 50, 'ina_default_g_height' => 400, 'ina_default_g_path' => 'mods/games/', 'ina_default_g_reward' => 20, 'ina_default_g_width' => 550, 'ina_default_increment' => 5, 'ina_default_order' => 1, 'ina_delete' => 0, 'ina_disable_challenges_page' => 0, 'ina_disable_cheat' => 1, 'ina_disable_comments_page' => 0, 'ina_disable_everything' => 0, 'ina_disable_gamble_page' => 0, 'ina_disable_submit_scores_g' => 0, 'ina_disable_submit_scores_m' => 0, 'ina_disable_top5_page' => 0, 'ina_disable_trophy_page' => 0, 'ina_email_sent' => 0, 'ina_guest_play' => 2, 'ina_join_block' => 2, 'ina_join_block_count' => 14, 'ina_max_games_per_day' => 100, 'ina_max_games_per_day_date' => '', 'ina_new_game_count' => 3, 'ina_new_game_limit' => 7, 'ina_pm_trophy' => 1, 'ina_pm_trophy_msg' => '%n% Has Taken Your Trophy For %g%.', 'ina_pm_trophy_sub' => 'Trophy Lost!', 'ina_pop_game_limit' => 5, 'ina_post_block' => 2, 'ina_post_block_count' => 10, 'ina_rating_reward' => 0, 'ina_show_view_profile' => 1, 'ina_show_view_topic' => '', 'ina_trophy_king' => 2, 'ina_use_max_games_per_day' => 2, 'ina_use_newest' => 1, 'ina_use_online' => 1, 'ina_use_rating_reward' => 0, 'ina_use_trophy' => 1, 'ina_version' => '1.0.9', 'points_browse' => 0, 'points_page' => 1, 'report_cheater' => 0, 'shoutbox_refresh_rate' => 300, 'use_cash_system' => 0, 'use_gamelib' => 0, 'use_gk_shop' => 0, 'use_rewards_mod' => 1, 'warn_cheater' => 0 );
while ( list ( $config_name, $config_value ) = each ( $config_data ) )
{
	$sql = "INSERT INTO " . $table_prefix . "config (`config_name`, `config_value`) VALUES ('" . $config_name . "', '" . $config_value . "')";
	_sql($sql, $errored, $error_ary);
}

$rconfig_data = array( 'irc_server', 'irc_port', 'irc_channel', 'irc_quit', 'irc_time_stamp', 'irc_show_connect', 'irc_show_chanlist', 'irc_about', 'irc_help', 'irc_style_selector', 'irc_font_style', 'irc_show_close', 'irc_show_status', 'irc_show_dock', 'irc_use_info', 'irc_sound_beep', 'irc_sound_query', 'use_rank_reward_system', 'use_forum_reward_system', 'pm_reward', 'pm_charge', 'nt_reward', 'nt_charge', 'pr_reward', 'pr_charge', 'allowance_payday', 'allowance', 'default_money', 'money_name', 'sig_charge' );
for ( $i = 0; $i < sizeof($rconfig_data); $i++ )
{
	$sql = "DELETE FROM " . $table_prefix . "config WHERE `config_name` = '" . $rconfig_data[$i] . "'";
	_sql($sql, $errored, $error_ary);
}
					

//
// Modify Fully Modded core-data
// phpbb_config_nav data
$sql = 'INSERT INTO '. $table_prefix .'config_nav (img, alt, use_lang, url, value) VALUES ("icon_mini_bank.gif", "Bookies", "1", "bookies.php", "0")';
_sql($sql, $errored, $error_ary);

$sql = 'DELETE FROM ' . $table_prefix . 'config_nav WHERE `alt` = "Chatroom"';
_sql($sql, $errored, $error_ary);

// phpbb_pjirc data
$pjirc_config_data = array( 'irc_allow_guests' => 0, 'irc_auth_joinlist' => '', 'irc_background_file' => '', 'irc_background_which' => 1, 'irc_bot_overall' => 1, 'irc_bot_switch1' => 1, 'irc_bot_switch2' => 1, 'irc_bot_timer' => 46000, 'irc_channel' => '#Midnightz', 'irc_channel2' => '', 'irc_channel2_on' => 0, 'irc_channel3' => '', 'irc_channel3_on' => 0, 'irc_enter_timer' => 45500, 'irc_font_style' => 1, 'irc_font_style_definition' => '', 'irc_guestname' => 'guest', 'irc_highlightcolor' => 4, 'irc_highlightwords' => 'word1 word2 word3', 'irc_language' => 'english', 'irc_mod_version' => '3.0.2', 'irc_multiserver' => 0, 'irc_multiserver_delay' => 60000, 'irc_multiserver_port' => 6667, 'irc_multiserver_server' => 'irc.freenode.net', 'irc_popup_onoff' => 0, 'irc_port' => 6667, 'irc_quit' => 'Goodbye', 'irc_server' => 'irc.blitzed.org', 'irc_show_about' => 0, 'irc_show_chanlist' => 1, 'irc_show_close' => 1, 'irc_show_connect' => 1, 'irc_show_dock' => 0, 'irc_show_help' => 1, 'irc_show_highlight' => 1, 'irc_show_nickfield' => 1, 'irc_show_status' => 1, 'irc_smilies' => 1, 'irc_smilies_count' => 20, 'irc_smilies_enter' => 1, 'irc_smilies_lines' => 20, 'irc_sound1' => 'cashregister.au', 'irc_sound2' => 'tone.au', 'irc_soundwords1' => 'word1 word2 word3 word4 word5', 'irc_soundwords2' => 'word1 word2 word3 word4 word5', 'irc_sound_away' => 'tone.au', 'irc_sound_back' => 'cashregister.au', 'irc_sound_beep' => 'boing.au', 'irc_sound_clear' => 'wipe.au', 'irc_sound_help' => 'lasertrill.au', 'irc_sound_ignore' => 'clunk.au', 'irc_sound_im' => 'cork.au', 'irc_sound_profile' => 'boingggg.au', 'irc_sound_query' => 'knocking.au', 'irc_sound_unignore' => 'chirp.au', 'irc_sound_whois' => 'drip.au', 'irc_status' => 1, 'irc_style_nick_left' => '', 'irc_style_nick_right' => '', 'irc_style_selector' => 1, 'irc_style_selector_definition' => '', 'irc_template' => 'subSilver', 'irc_time_stamp' => 1, 'irc_topicscroller' => 10, 'irc_use_info' => 0 );
while ( list ( $config_name, $config_value ) = each ( $pjirc_config_data ) )
{
	$sql = "INSERT INTO " . $table_prefix . "pjirc (`pjirc_name`, `pjirc_value`) VALUES ('" . $config_name . "', '" . $config_value . "')";
	_sql($sql, $errored, $error_ary);
}


//
// Change default values to sync with FullyModded setup
//
$sql = 'DELETE FROM ' . $table_prefix . 'forums WHERE `forum_id` = "-1035"';
_sql($sql, $errored, $error_ary);
			
$sql = 'UPDATE ' . $table_prefix . 'config SET `config_value` = "2.1.1" WHERE `config_name` = "points_system_version"';
_sql($sql, $errored, $error_ary);

$sql = 'UPDATE ' . $table_prefix . 'users SET `admin_allow_points` = "1" WHERE `user_id` > 1';
_sql($sql, $errored, $error_ary);

?>