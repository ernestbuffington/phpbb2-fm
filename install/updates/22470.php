<?php

if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}

//
// Modify phpBB core-scema	
//
$sql = 'ALTER TABLE ' . $prefix . 'forums CHANGE `forum_template` `forum_template` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT "0"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'forums ADD COLUMN `forum_toggle` TINYINT(1) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);


//
// Modify Fully Modded core-schema
//
$sql = 'ALTER TABLE ' . $prefix . 'portal ADD COLUMN `portal_donors` TINYINT(1) DEFAULT "0"';
_sql($sql, $errored, $error_ary);

// 22470 new installs need this field fixed!
$sql = 'ALTER TABLE ' . $prefix . 'users CHANGE `email_vaildation` `email_validation` TINYINT(1) NOT NULL DEFAULT "0"';
_sql($sql, $errored, $error_ary);


//
// Modify phpBB core-data
//
// phpbb_config data
$config_data = array( 
	'message_minlength' => 2, 
	'viewtopic_style' => 0,
	'collapse_userinfo' => 0,
	'points_viewtopic' => 0,
	'viewtopic_buddyimg' => 0,
	'viewtopic_usergroups' => 0,
	'ina_hof_viewtopic' => 0,
	'viewtopic_flag' => 0,
	'viewtopic_yearstars' => 0,
	'viewtopic_memnum' => 0,
	'viewtopic_extrastats' => 0,
	'usertime_viewtopic' => 0,
	'birthday_viewtopic' => 0,
	'gender_viewtopic' => 0,
	'jobs_viewtopic' => 0
);
while ( list ( $config_name, $config_value ) = each ( $config_data ) )
{
	$sql = "INSERT INTO " . $prefix . "config (`config_name`, `config_value`) 
		VALUES ('" . $config_name . "', '" . $config_value . "')";
	_sql($sql, $errored, $error_ary);
}		


//
// Change default values to sync with FullyModded setup
//
// Missing from New Install 22470
$sql = 'UPDATE ' . $prefix . 'config SET `config_name` = "message_maxlength" WHERE `config_name` = "message_length"';
_sql($sql, $errored, $error_ary);

$sql = 'UPDATE ' . $prefix . 'config SET `config_value` = "0" WHERE `config_name` = "viewtopic"';
_sql($sql, $errored, $error_ary);

?>