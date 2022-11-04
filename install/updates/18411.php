<?php
	
if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}


//
// Modify phpBB core-scema	
//
$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'session_keys'; 
_sql($sql, $errored, $error_ary);
$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'sessions_keys'; 
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'sessions_keys (`key_id` varchar(32) DEFAULT "0" NOT NULL, `user_id` mediumint(8) DEFAULT "0" NOT NULL, `last_ip` varchar(8) DEFAULT "0" NOT NULL, `last_login` int(11) DEFAULT "0" NOT NULL, PRIMARY KEY (`key_id`, `user_id`), KEY last_login (`last_login`))';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $table_prefix . 'themes ADD COLUMN `image_cfg` VARCHAR(100) DEFAULT "" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'themes ADD COLUMN `theme_header` TINYINT(1) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'themes ADD COLUMN `theme_footer` TINYINT(1) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $table_prefix . 'themes MODIFY COLUMN `themes_id` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT "0"';
_sql($sql, $errored, $error_ary);
		
$sql = 'ALTER TABLE ' . $table_prefix . 'users ADD COLUMN user_login_tries smallint(5) UNSIGNED DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'users ADD COLUMN user_last_login_try int(11) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);


//
// Modify Fully Modded core-schema
//
// Preivious phpBB updaters now on FM need this column type fixed!
$sql = 'ALTER TABLE ' . $table_prefix . 'bookie_bets CHANGE `bet cat` `bet_cat` INT(3) DEFAULT "1" NOT NULL';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $table_prefix . 'meeting_data ADD COLUMN `meeting_by_user` MEDIUMINT(8) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'meeting_data ADD COLUMN `meeting_edit_by_user` MEDIUMINT(8) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'meeting_data ADD COLUMN `meeting_start_value` MEDIUMINT(8) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'meeting_data ADD COLUMN `meeting_recure_value` MEDIUMINT(8) DEFAULT "5" NOT NULL';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $table_prefix . 'portal ADD COLUMN `portal_wallpaper` TINYINT(1) DEFAULT "0"';
_sql($sql, $errored, $error_ary);
			

//
// Create new Fully Modded core-schema
//
$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'meeting_config';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'meeting_config (config_name VARCHAR(255) DEFAULT "" NOT NULL, config_value VARCHAR(255) DEFAULT "" NOT NULL, PRIMARY KEY (config_name))';
_sql($sql, $errored, $error_ary);


//
// Modify phpBB core-data
// phpbb_config data
$config_data = array( 'allow_autologin' => 1, 'auction_enable' => 0, 'auction_ebay_user_id' => 'your_ebay_user_id', 'auction_enable_thumbs' => 0, 'auction_timezone_offset' => 0, 'auction_enable_ended' => -1, 'auction_sort_order' => 3, 'auction_amt' => 10, 'auction_ebay_url' => 'http://cgi.ebay.com', 'board_sitemap' => 0, 'enable_similar_topics' => 0, 'login_reset_time' => 30, 'max_autologin_time' => 0, 'max_login_attempts' => 5 );
while ( list ( $config_name, $config_value ) = each ( $config_data ) )
{
	$sql = "INSERT INTO " . $table_prefix . "config (`config_name`, `config_value`) VALUES ('" . $config_name . "', '" . $config_value . "')";
	_sql($sql, $errored, $error_ary);
}


//
// Modify Fully Modded core-data
// phpbb_news_config data
$sql = "DELETE FROM " . $table_prefix . "news WHERE `config_name` = 'news_color'";
_sql($sql, $errored, $error_ary);

// phpbb_forums_config data
$sql = "INSERT INTO " . $table_prefix . "forums_config (`config_name`, `config_value`) VALUES ('forum_module_wallpaper', '0')";
_sql($sql, $errored, $error_ary);

// phpbb_meeting_config data
$meeting_config_data = array( 'allow_user_enter_meeting' => 0, 'allow_user_edit_meeting' => 0 );
while ( list ( $config_name, $config_value ) = each ( $meeting_config_data ) )
{
	$sql = "INSERT INTO " . $table_prefix . "meeting_config (`config_name`, `config_value`) VALUES ('" . $config_name . "', '" . $config_value . "')";
	_sql($sql, $errored, $error_ary);
}


//
// Change default values to sync with FullyModded setup
//
$sql = 'UPDATE ' . $table_prefix . 'meeting_data SET `meeting_by_user` = "2"';
_sql($sql, $errored, $error_ary);
			
$sql = 'UPDATE ' . $table_prefix . 'meeting_data SET `meeting_edit_by_user` = "2"';
_sql($sql, $errored, $error_ary);

$sql = 'UPDATE ' . $table_prefix . 'config SET `config_value` = "php" WHERE `config_name` = "xs_php"';
_sql($sql, $errored, $error_ary);

?>