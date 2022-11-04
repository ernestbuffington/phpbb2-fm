<?php
	
if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}

//
// Modify phpBB core-schema	
//
$sql = 'INSERT INTO ' . $prefix . 'pages (`page_name`, `page_key`, `page_parm_name`, `page_parm_value`) VALUES ("profile.php", "profile.php?mode=email", "mode", "email")';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $prefix . 'profile_view DROP COLUMN `viewername`';
_sql($sql, $errored, $error_ary);

$sql = 'DELETE FROM ' . $prefix . 'config WHERE `config_name` = "enable_sig_editor"';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE ' . $prefix . 'subscriptions_list';
_sql($sql, $errored, $error_ary);

$sql = 'DELETE FROM ' . $prefix . 'pa_votes WHERE `user_id` = "-1"';
_sql($sql, $errored, $error_ary);

// phpBB updaters missing this field
$sql = "INSERT INTO " . $prefix . "config (`config_name`, `config_value`) VALUES ('mods_viewips', '0')";
_sql($sql, $errored, $error_ary);

?>