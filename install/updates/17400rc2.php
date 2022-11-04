<?php
	
if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}


//
// Modify phpBB core-scema	
//
$sql = 'ALTER TABLE ' . $table_prefix . 'users ADD COLUMN `ina_game_pass` int(10) NOT NULL DEFAULT "0"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'users ADD COLUMN `ina_games_pass_day` date NOT NULL DEFAULT "0000-00-00"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'users ADD COLUMN `ina_time_playing` varchar(20) NOT NULL DEFAULT ""';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'users ADD COLUMN `ina_settings` varchar(255) NOT NULL DEFAULT "info-1;;daily-1;;newest-1;;newest_count-3;;games-1;;games_count-40;;online-1"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'users ADD COLUMN `ina_char_name` text NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'users ADD COLUMN `ina_char_age` int(10) NOT NULL DEFAULT "1"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'users ADD COLUMN `ina_char_from` varchar(255) NOT NULL DEFAULT ""';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'users ADD COLUMN `ina_char_intrests` varchar(255) NOT NULL DEFAULT ""';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'users ADD COLUMN `ina_char_img` varchar(255) NOT NULL DEFAULT ""';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'users ADD COLUMN `ina_char_gender` smallint(1) NOT NULL DEFAULT "1"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'users ADD COLUMN `ina_char_ge` int(10) NOT NULL DEFAULT "0"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'users ADD COLUMN `ina_char_name_effects` text NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'users ADD COLUMN `ina_char_title_effects` text NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'users ADD COLUMN `ina_char_saying_effects` text NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'users ADD COLUMN `ina_char_views` int(10) NOT NULL DEFAULT "0"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'users ADD COLUMN `ina_char_title` varchar(255) NOT NULL DEFAULT ""';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'users ADD COLUMN `ina_char_saying` varchar(255) NOT NULL DEFAULT ""';
_sql($sql, $errored, $error_ary);
  		

//
// Modify Fully Modded core-schema
//
$sql = 'ALTER TABLE ' . $table_prefix . 'ina_cheat_fix DROP COLUMN `game_count`';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'inline_ads'; 
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'inline_ads (`ad_id` TINYINT(5) NOT NULL auto_increment, `ad_code` TEXT NOT NULL, `ad_name` CHAR(25) NOT NULL, PRIMARY KEY (ad_id)) TYPE=MYISAM';
_sql($sql, $errored, $error_ary);
		
$sql = 'ALTER TABLE ' . $table_prefix . 'ina_games ADD COLUMN `game_type` SMALLINT(1) DEFAULT "1" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'ina_games ADD COLUMN `game_links` TEXT NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'ina_games ADD COLUMN `game_ge_cost` INT(10) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'ina_games ADD COLUMN `game_keyboard` SMALLINT(1) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'ina_games ADD COLUMN `game_mouse` SMALLINT(1) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'ina_games MODIFY COLUMN `game_show_score` TINYINT(1) NOT NULL DEFAULT "1"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'ina_games MODIFY COLUMN `game_popup` SMALLINT(1) NOT NULL DEFAULT "1"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'ina_games MODIFY COLUMN `game_parent` SMALLINT(1) NOT NULL DEFAULT "1"';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $table_prefix . 'ina_last_game_played MODIFY COLUMN `game_id` INT(20) DEFAULT "0"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'ina_last_game_played MODIFY COLUMN `user_id` MEDIUMINT(8) NOT NULL';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $table_prefix . 'ina_gamble MODIFY COLUMN `winner_score` INT(11) DEFAULT "0"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'ina_gamble MODIFY COLUMN `loser_score` INT(11) DEFAULT "0"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'ina_gamble MODIFY COLUMN `been_paid` INT(11) DEFAULT "0"';
_sql($sql, $errored, $error_ary);


//
// Modify phpBB core-data
// phpbb_config data
$config_data = array( 'ad_after_post' => 0, 'ad_post_threshold' => '', 'ad_every_post' => 0, 'ad_who' => 1, 'ad_no_forums' => '', 'ad_no_groups' => '', 'ad_old_style' => 0, 'admin_login' => 1, 'ina_char_change_char_cost' => 0, 'ina_char_change_gender_cost' => 0, 'ina_char_change_age_cost' => 0, 'ina_char_change_name_cost' => 0, 'ina_char_change_from_cost' => 0, 'ina_char_change_intrests_cost' => 0, 'ina_char_ge_per_game' => 1, 'ina_char_ge_per_beat_score' => 2, 'ina_char_ge_per_trophy' => 3, 'ina_char_show_viewtopic' => 1, 'ina_char_show_viewprofile' => 1, 'ina_char_change_title_cost' => 0, 'ina_char_change_saying_cost' => 0, 'ina_char_name_effects_costs' => '7,5,9,3,10,20', 'ina_char_title_effects_costs' => '5,4,3,2,1,1', 'ina_char_saying_effects_costs' => '2,2,2,2,2,2', 'ina_game_pass_cost' => 0, 'ina_game_pass_length' => 5 );
while ( list ( $config_name, $config_value ) = each ( $config_data ) )
{
	$sql = "INSERT INTO " . $table_prefix . "config (`config_name`, `config_value`) VALUES ('" . $config_name . "', '" . $config_value . "')";
	_sql($sql, $errored, $error_ary);
}


//
// Modify Fully Modded core-data
// phpbb_inline_ads data
$sql = 'INSERT INTO ' . $table_prefix . 'inline_ads (`ad_code`, `ad_name`) VALUES ("Your banner code goes here", "Default")';
_sql($sql, $errored, $error_ary);


//
// Change default values to sync with FullyModded setup
//
$sql = 'UPDATE ' . $table_prefix . 'config SET `config_value` = "1.1.0" WHERE `config_name` = "ina_version"';
_sql($sql, $errored, $error_ary);

?>