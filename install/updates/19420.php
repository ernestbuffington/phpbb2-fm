<?php
	
if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}


//
// Modify phpBB core-scema	
//
$sql = 'ALTER TABLE ' . $prefix . 'forums ADD COLUMN `forum_digest` TINYINT(1) DEFAULT "1" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'forums ADD COLUMN `forum_password` VARCHAR(20) DEFAULT "" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'forums ADD COLUMN `hide_forum_on_index` TINYINT(1) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'forums ADD COLUMN `hide_forum_in_cat` TINYINT(1) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $prefix . 'groups ADD COLUMN `group_digest` TINYINT(1) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'groups ADD COLUMN `group_amount` float DEFAULT "0"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'groups ADD COLUMN `group_period` integer DEFAULT "1"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'groups ADD COLUMN `group_period_basis` VARCHAR(10) DEFAULT "M"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'groups ADD COLUMN `group_first_trial_fee` float DEFAULT "0"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'groups ADD COLUMN `group_first_trial_period` integer DEFAULT "0"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'groups ADD COLUMN `group_first_trial_period_basis` VARCHAR(10) DEFAULT "0"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'groups ADD COLUMN `group_second_trial_fee` float DEFAULT "0"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'groups ADD COLUMN `group_second_trial_period` integer DEFAULT "0"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'groups ADD COLUMN `group_second_trial_period_basis` VARCHAR(10) DEFAULT "0"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'groups ADD COLUMN `group_sub_recurring` integer DEFAULT "1"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'groups ADD COLUMN `group_sub_recurring_stop` integer DEFAULT "0"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'groups ADD COLUMN `group_sub_recurring_stop_num` integer DEFAULT "0"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'groups ADD COLUMN `group_sub_reattempt` integer DEFAULT "1"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'groups MODIFY COLUMN `group_description` TEXT NOT NULL DEFAULT ""';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $prefix . 'posts ADD COLUMN `urgent_post` TINYINT(1) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $prefix . 'search_results ADD COLUMN `search_time` int(11) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $prefix . 'user_group ADD COLUMN `digest_confirm_date` INT(11) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'user_group ADD COLUMN `ug_expire_date` int(11) DEFAULT "0"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'user_group ADD COLUMN `ug_active_date` int(11) DEFAULT "0"';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $prefix . 'users ADD COLUMN `user_digest_status` TINYINT(1) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'users ADD COLUMN `user_actviate_date` int(11) DEFAULT "0"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'users ADD COLUMN `user_expire_date` int(11) DEFAULT "0"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'users MODIFY COLUMN `user_clockformat` VARCHAR(12) DEFAULT "clock001.swf" NOT NULL';
_sql($sql, $errored, $error_ary);


//
// Modify Fully Modded core-schema
//
// Previous 2019.4.20 New Installers need this column added!
$sql = 'ALTER TABLE ' . $prefix . 'bookie_bets ADD COLUMN `bet_cat` INT(3) DEFAULT "1" NOT NULL';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $prefix . 'themes ADD COLUMN `style_version` VARCHAR(6)';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $prefix . 'users MODIFY COLUMN `user_sound_pm` TINYINT(1) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);


//
// Create new Fully Modded core-schema
//
$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'account_hist';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $prefix . 'account_hist (`user_id` mediumint(8) DEFAULT "0", `lw_post_id` mediumint(8) DEFAULT "0", `lw_money` float DEFAULT "0", `lw_plus_minus` smallint(5) DEFAULT "0", `MNY_CURRENCY` varchar(8) DEFAULT "", `lw_date` int(11) DEFAULT "0", `comment` varchar(255) DEFAULT "", `status` varchar(64) DEFAULT "", `txn_id` varchar(64) DEFAULT "", `lw_site` varchar(10) DEFAULT "") TYPE=MyISAM';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'banned_sites';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $prefix . 'banned_sites (`site_id` INT(15) NOT NULL AUTO_INCREMENT, `site_url` VARCHAR(150) NOT NULL, `reason` VARCHAR(15) NOT NULL, INDEX (`site_id`)) TYPE=MyISAM';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'banned_visitors';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $prefix . 'banned_visitors (`count` INT(15) NOT NULL AUTO_INCREMENT, `refer` VARCHAR(150) NOT NULL, `ip` VARCHAR(255) NOT NULL, `ip_owner` VARCHAR(100) NOT NULL, `browser` VARCHAR(150) NOT NULL, `user_id` MEDIUMINT(8) NOT NULL, `user` VARCHAR(50) NOT NULL, INDEX (`count`)) TYPE=MyISAM';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'digests';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $prefix . 'digests (`digest_id` int(6) NOT NULL auto_increment, `digest_name` varchar(25) NULL DEFAULT "", `user_id` mediumint(8) NOT NULL DEFAULT "0", `digest_type` tinyint(1) NOT NULL DEFAULT "0", `digest_activity` tinyint(1) NOT NULL DEFAULT "1", `digest_frequency` mediumint(8) NOT NULL DEFAULT "0", `last_digest` int(11) NOT NULL DEFAULT "0", `digest_format` smallint(4) NOT NULL DEFAULT "0", `digest_show_text` smallint(4) NOT NULL DEFAULT "0", `digest_show_mine` smallint(4) NOT NULL DEFAULT "0", `digest_new_only` smallint(4) NOT NULL DEFAULT "0", `digest_send_on_no_messages` smallint(4) NOT NULL DEFAULT "1", `digest_moderator` tinyint(1) NOT NULL DEFAULT "0", `digest_include_forum` tinyint(1) NOT NULL DEFAULT "1", PRIMARY KEY (`digest_id`), KEY user_id (`user_id`)) TYPE=MyISAM';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'digests_config';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $prefix . 'digests_config (`config_name` varchar(255) NOT NULL DEFAULT "", `config_value` varchar(255) NOT NULL DEFAULT "0", PRIMARY KEY  (config_name)) TYPE=MyISAM';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'digests_forums';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $prefix . 'digests_forums (`user_id` mediumint(8) NOT NULL DEFAULT "0", `forum_id` smallint(5) NOT NULL DEFAULT "0", `digest_id` int(11) NOT NULL DEFAULT "0", KEY user_id (`user_id`), KEY forum_id (forum_id)) TYPE=MyISAM';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'digests_log';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $prefix . 'digests_log (`log_time` int(11) NOT NULL DEFAULT "0", `run_type` tinyint(1) NOT NULL DEFAULT "0", `user_id` mediumint(8) NOT NULL DEFAULT "0", `digest_frequency` mediumint(8) NOT NULL DEFAULT "0", `digest_type` tinyint(1) NOT NULL DEFAULT "0", `group_id` mediumint(8) NOT NULL DEFAULT "1", `log_status` mediumint(2) NOT NULL DEFAULT "0", `log_posts` int(11) NULL DEFAULT "0", KEY user_id (`user_id`)) TYPE=MyISAM';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'xdata_auth';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $prefix . 'xdata_auth (`field_id` SMALLINT(5) UNSIGNED NOT NULL, `group_id` MEDIUMINT(8) UNSIGNED NOT NULL, `auth_value` TINYINT(1) NOT NULL) TYPE=MyISAM';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'xdata_data';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $prefix . 'xdata_data (`field_id` SMALLINT(5) UNSIGNED NOT NULL, `user_id` MEDIUMINT(8) UNSIGNED NOT NULL, `xdata_value` TEXT NOT NULL) TYPE=MyISAM';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'xdata_fields';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $prefix . 'xdata_fields (`field_id` SMALLINT(5) UNSIGNED NOT NULL, `field_name` VARCHAR(255) NOT NULL DEFAULT "", `field_desc` TEXT NOT NULL DEFAULT "", `field_type` VARCHAR(255) NOT NULL DEFAULT "", `field_order` SMALLINT(5) UNSIGNED NOT NULL DEFAULT "0", `code_name` VARCHAR(255) NOT NULL DEFAULT "", `field_length` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT "0", `field_values` TEXT NOT NULL DEFAULT "", `field_regexp` TEXT NOT NULL DEFAULT "", `default_auth` TINYINT(1) NOT NULL DEFAULT "1", `display_register` TINYINT(1) NOT NULL DEFAULT "1", `display_viewprofile` TINYINT(1) NOT NULL DEFAULT "0", `display_posting` TINYINT(1) NOT NULL DEFAULT "0", `handle_input` TINYINT(1) NOT NULL DEFAULT "0", `allow_html` TINYINT(1) NOT NULL DEFAULT "0", `allow_bbcode` TINYINT(1) NOT NULL DEFAULT "0", `allow_smilies` TINYINT(1) NOT NULL DEFAULT "0", PRIMARY KEY (field_id), UNIQUE KEY code_name (code_name)) TYPE=MyISAM';
_sql($sql, $errored, $error_ary);


//
// Modify phpBB core-data
// phpbb_config data
$config_data = array( 'enable_ip_logger' => 0, 'null_vote' => 0, 'rand_seed' => 0, 'search_flood_interval' => 15, 'split_global_announce' => 0, 'split_announce' => 0, 'split_sticky' => 0, 'user_accounts_limit' => 0 );
while ( list ( $config_name, $config_value ) = each ( $config_data ) )
{
	$sql = "INSERT INTO " . $prefix . "config (`config_name`, `config_value`) VALUES ('" . $config_name . "', '" . $config_value . "')";
	_sql($sql, $errored, $error_ary);
}


//
// Modify Fully Modded core-data
// phpbb_digests_config data
$digests_config_data = array( 'activity_threshold' => 90, 'allow_daily' => 1, 'allow_direct_run' => 0, 'allow_exclude' => 0, 'allow_hours1' => 0, 'allow_hours2' => 0, 'allow_hours4' => 0, 'allow_hours6' => 0, 'allow_hours8' => 0, 'allow_hours12' => 0, 'allow_monthly' => 0, 'allow_urgent' => 0, 'allow_weekly' => 1, 'auto_subscribe' => 0, 'auto_subscribe_group' => 0, 'check_user_activity' => 0, 'default_format' => 1, 'default_frequency' => 24, 'default_new_only' => 1, 'default_send_on_no_messages' => 0, 'default_show_mine' => 0, 'default_show_text' => 1, 'default_text_length_type' => 1, 'digest_date_format' => 'D d-M-Y \a\t H:i:s', 'digest_disable_group' => 1, 'digest_disable_user' => 1, 'digest_logging' => 0, 'digest_subject' => '', 'digest_theme' => 1, 'digest_version' => '1.3.4', 'direct_password' => '', 'home_page' => 'index', 'log_days' => 8, 'monthly_day' => 28, 'new_sign_up' => 0, 'override_theme' => 1, 'pm_notify' => 0, 'pm_display' => 0, 'run_urgent_only' => 1, 'run_time' => 18, 'short_text_length' => 150, 'show_forum_description' => 0, 'show_ip' => 0, 'show_stats' => 1, 'supress_cron_output' => 0, 'test_mode' => 1, 'theme_type' => 0, 'urgent_run_required' => 1, 'use_system_time' => 1, 'weekly_day' => 0 );
while ( list ( $config_name, $config_value ) = each ( $digests_config_data ) )
{
	$sql = "INSERT INTO " . $prefix . "digests_config (`config_name`, `config_value`) VALUES ('" . $config_name . "', '" . $config_value . "')";
	_sql($sql, $errored, $error_ary);
}

// phpbb_xdata_fields data
$id = 1;
$xdata_fields_data = array(
	array( Name => 'Real Name', Code => 'realname' ),
	array( Name => 'Skype Name', Code => 'skype' ),
	array( Name => 'ICQ Number', Code => 'icq' ),
	array( Name => 'AIM Address', Code => 'aim' ),
	array( Name => 'Xfire Address', Code => 'xfi' ),
	array( Name => 'MSN Messenger', Code => 'msn' ),
	array( Name => 'Yahoo Messenger', Code => 'yim' ),
	array( Name => 'Google Talk', Code => 'gtalk' ),
	array( Name => 'Website', Code => 'website' ),
	array( Name => 'StumbleUpon Profile', Code => 'stumble' ),
	array( Name => 'Location', Code => 'location' ),
	array( Name => 'Weather city code', Code => 'zipcode' ),
	array( Name => 'Country Flag', Code => 'flag' ),
	array( Name => 'Occupation', Code => 'occupation' ),
	array( Name => 'Interests', Code => 'interests' ),
	array( Name => 'Gender', Code => 'gender' ),
	array( Name => 'Birthday', Code => 'bday' )				
);
for ( $row = 0; $row < sizeof($xdata_fields_data); $row++ )
{
	for ( $column = 0; $column < 1; $column++ )
	{
		$sql = "INSERT INTO " . $prefix . "xdata_fields (`field_id`, `field_name`, `field_type`, `field_order`, `code_name`, `display_register`) VALUES ('" . $id . "', '" . $xdata_fields_data[$row]['Name'] . "', 'special', '" . $id . "', '" . $xdata_fields_data[$row]['Code'] . "', '2')";
		_sql($sql, $errored, $error_ary);
	}
	$id++;
}


//
// Change default values to sync with FullyModded setup
//
$sql = 'UPDATE ' . $prefix . 'config SET `config_value` = "clock001.swf" WHERE `config_name` = "default_clock"';
_sql($sql, $errored, $error_ary);

$sql = 'UPDATE ' . $prefix . 'config SET `config_value` = "3.0.0" WHERE `config_name` = "bookie_version"';
_sql($sql, $errored, $error_ary);

$sql = 'UPDATE ' . $prefix . 'users SET `user_clockformat` = "clock001.swf"';
_sql($sql, $errored, $error_ary);

?>