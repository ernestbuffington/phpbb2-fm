<?php
	
if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}


//
// Modify phpBB core-scema	
//
$sql = 'ALTER TABLE ' . $prefix . 'groups ADD COLUMN `allow_create_meeting` TINYINT(1) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);


//
// Modify Fully Modded core-schema
//
$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'amazon_config';
_sql($sql, $errored, $error_ary);

$sql = 'DELETE FROM ' . $prefix . 'attachments_config WHERE `config_name` = "upload_img"';
_sql($sql, $errored, $error_ary);
$sql = 'DELETE FROM ' . $prefix . 'attachments_config WHERE `config_name` = "topic_icon"';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $prefix . 'banner ADD KEY `banner_active` (`banner_active`)';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'banner ADD KEY `banner_level` (`banner_level`)';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'banner ADD KEY `banner_timetype` (`banner_timetype`)';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $prefix . 'meeting_comment ADD COLUMN `comment_id` MEDIUMINT(8) auto_increment PRIMARY KEY';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'meeting_comment ADD COLUMN `bbcode_uid` CHAR(10) DEFAULT "" NOT NULL';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $prefix . 'meeting_data ADD COLUMN `meeting_notify` TINYINT(1) DEFAULT "0"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'meeting_data ADD COLUMN `bbcode_uid` CHAR(10) DEFAULT "" NOT NULL';
_sql($sql, $errored, $error_ary);

$sql = 'RENAME TABLE ' . $prefix . 'notes_admin TO ' . $prefix . 'admin_notes';
_sql($sql, $errored, $error_ary);

$sql = 'RENAME TABLE ' . $prefix . 'notes TO ' . $prefix . 'user_notes';
_sql($sql, $errored, $error_ary);
			
$sql = 'TRUNCATE TABLE ' . $prefix . 'ip';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'ip MODIFY COLUMN `date` INT(11) NOT NULL DEFAULT "0"';
_sql($sql, $errored, $error_ary);

$sql = 'TRUNCATE TABLE ' . $prefix . 'shop_transactions';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'shop_transactions MODIFY COLUMN `trans_user` MEDIUMINT(8) NOT NULL';
_sql($sql, $errored, $error_ary);


//
// Modify phpBB core-data
// Previous 2020.4.35 phpBB & phpBBFM Updaters need some of these fields added!
// phpbb_config data
$config_data = array( 'allow_avatar_generator' => 0, 'amazon_enable' => 0, 'amazon_username' => 'your_amazon_user_id', 'amazon_global_announce' => 0, 'amazon_announce' => 0, 'amazon_sticky' => 0, 'amazon_normal' => 0, 'amazon_window' => 1, 'amazon_country' => 0, 'avatar_generator_template_path' => 'images/avatar_generator', 'wpm_disable' => 1, 'paypal_p_acct' => $board_config['board_email'], 'paypal_currency_code' => 'USD', 'lw_trial_period' => 0, 'extra_days_for_sub' => 0, 'search_min_chars' => 3 );
while ( list ( $config_name, $config_value ) = each ( $config_data ) )
{
	$sql = "INSERT INTO " . $prefix . "config (`config_name`, `config_value`) VALUES ('" . $config_name . "', '" . $config_value . "')";
	_sql($sql, $errored, $error_ary);
}

$sql = 'UPDATE ' . $prefix . 'config SET `config_name` = "images/links/yourlogo.gif" WHERE `config_name` = "site_logo"';
_sql($sql, $errored, $error_ary);

// Previous New Installers need these ina_ fields fixed!
$sql = 'UPDATE ' . $prefix . 'config SET `config_name` = "ina_default_g_path" WHERE `config_name` = "ina_default_g_path "';
_sql($sql, $errored, $error_ary);

$sql = 'UPDATE ' . $prefix . 'config SET `config_name` = "ina_pm_trophy_sub" WHERE `config_name` = "ina_pm_trophy_sub "';
_sql($sql, $errored, $error_ary);

$sql = 'UPDATE ' . $prefix . 'config SET `config_name` = "ina_use_online" WHERE `config_name` = "ina_use_online "';
_sql($sql, $errored, $error_ary);

$sql = 'UPDATE ' . $prefix . 'config SET `config_name` = "ina_max_games_per_day_date" WHERE `config_name` = "ina_max_games_per_day_date "';
_sql($sql, $errored, $error_ary);


//
// Modify Fully Modded core-data
// phpbb_meeting_config data
$meeting_config_data = array( 'allow_user_delete_meeting' => 0, 'allow_user_delete_meeting_comments' => 0, 'create_meeting' => 1, 'meeting_notify' => 1 );
while ( list ( $config_name, $config_value ) = each ( $meeting_config_data ) )
{
	$sql = "INSERT INTO " . $prefix . "meeting_config (`config_name`, `config_value`) VALUES ('" . $config_name . "', '" . $config_value . "')";
	_sql($sql, $errored, $error_ary);
}
			
// Previous 2020.4.35 New Installers need this entry fixed!
// phpbb_xdata_fields data
$sql = 'UPDATE ' . $prefix . 'xdata_fields SET `field_type` = "special", `field_desc` = "" WHERE `field_name` = "Real Name"';
_sql($sql, $errored, $error_ary);


//
// Change default values to sync with FullyModded setup
//
$sql = 'UPDATE '. $prefix . 'themes SET `body_text` = "323D4F" WHERE `style_name` = "subSilver"';	
_sql($sql, $errored, $error_ary);

// We reset those having autologin enabled and forcing the re-assignment of a session id
// since there have been changes to the way these are handled from previous versions
$sql = "DELETE FROM " . $prefix . "sessions";
_sql($sql, $errored, $error_ary);
	
$sql = "DELETE FROM " . $prefix . "sessions_keys";
_sql($sql, $errored, $error_ary);

?>