<?php
	
if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}


//
// Modify phpBB core-scema	
//
$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'session_keys'; 
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $prefix . 'session_keys (`key_id` varchar(32) DEFAULT "0" NOT NULL, `user_id` mediumint(8) DEFAULT "0" NOT NULL, `last_ip` varchar(8) DEFAULT "0" NOT NULL, `last_login` int(11) DEFAULT "0" NOT NULL, PRIMARY KEY (`key_id`, `user_id`), KEY last_login (`last_login`))';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $prefix . 'themes MODIFY COLUMN `th_class1` VARCHAR(100) DEFAULT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'themes MODIFY COLUMN `th_class2` VARCHAR(100) DEFAULT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'themes MODIFY COLUMN `th_class3` VARCHAR(100) DEFAULT NULL';
_sql($sql, $errored, $error_ary);


//
// Modify Fully Modded core-schema
//
$sql = 'ALTER TABLE ' . $prefix . 'bank MODIFY COLUMN `id` MEDIUMINT(8) NOT NULL auto_increment';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'bank MODIFY COLUMN `holding` BIGINT(20) UNSIGNED DEFAULT "0"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'bank MODIFY COLUMN `totalwithdrew` BIGINT(20) UNSIGNED DEFAULT "0"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'bank MODIFY COLUMN `totaldeposit` BIGINT(20) UNSIGNED DEFAULT "0"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'bank MODIFY COLUMN `opentime` INT(11) NOT NULL DEFAULT "0"';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $prefix . 'banner MODIFY COLUMN `banner_width` VARCHAR(5) NOT NULL DEFAULT "0"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'banner MODIFY COLUMN `banner_height` VARCHAR(5) NOT NULL DEFAULT "0"';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $prefix . 'ina_scores ADD COLUMN `user_plays` INT(6) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'ina_scores ADD COLUMN `play_time` INT(10) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $prefix . 'users MODIFY COLUMN `user_points` BIGINT(20) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);


//
// Modify phpBB core-data
// phpbb_config data
$config_data = array( 'debug_email' => 0, 'xs_cache_dir' => 'cache/tpls', 'xs_cache_dir_absolute' => 0, 'xs_auto_compile' => 1, 'xs_auto_recompile' => 1, 'xs_use_cache' => 1, 'xs_separator' => '/', 'xs_php' => 'php', 'xs_def_template' => 'subSilver', 'xs_use_isset' => 1, 'xs_check_switches' => 0, 'xs_version' => 2 );
while ( list ( $config_name, $config_value ) = each ( $config_data ) )
{
	$sql = "INSERT INTO " . $prefix . "config (`config_name`, `config_value`) VALUES ('" . $config_name . "', '" . $config_value . "')";
	_sql($sql, $errored, $error_ary);
}

// phpbb_users data
$sql = 'UPDATE ' . $prefix . 'users SET `user_active` = "0" WHERE `user_id` = "-1"';
_sql($sql, $errored, $error_ary);


//
// Modify Fully Modded core-data
// phpbb_config_nav data
$sql = 'DELETE FROM ' . $prefix . 'config_nav WHERE `url` = "lexicon.php"';
_sql($sql, $errored, $error_ary);

// phpbb_forums_config data
$sql = "INSERT INTO " . $prefix . "forums_config (`config_name`, `config_value`) VALUES ('forum_module_game', '0')";
_sql($sql, $errored, $error_ary);

// phpbb_lexicon data
$sql = 'DELETE FROM ' . $prefix . 'lexicon WHERE `cat` = "0"';
_sql($sql, $errored, $error_ary);


//
// Change default values to sync with FullyModded setup
//
$sql = 'UPDATE ' . $prefix . 'banner SET `banner_width` = "" WHERE `banner_width` = "0"';
_sql($sql, $errored, $error_ary);
			
$sql = 'UPDATE ' . $prefix . 'banner SET `banner_height` = "" WHERE `banner_height` = "0"';
_sql($sql, $errored, $error_ary);

$sql = 'UPDATE ' . $prefix . 'im_sites SET `site_url` = "http://phpbb-fm.com" WHERE `site_url` = "http://phpbbfm.net/phpBBFM/"';
_sql($sql, $errored, $error_ary);

$sql = 'UPDATE '. $prefix . 'themes SET `body_background` = "background: url(templates/subSilver/images/background.gif) repeat-x;" WHERE `style_name` = "subSilver"';	
_sql($sql, $errored, $error_ary);

?>