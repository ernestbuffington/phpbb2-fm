<?php
	
if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}


//
// Modify phpBB core-data
//
$sql = "INSERT INTO " . $table_prefix . "config (`config_name`, `config_value`) VALUES ('enable_quick_titles', '0')";
_sql($sql, $errored, $error_ary);

$sql = 'DELETE FROM ' . $table_prefix . 'config WHERE `config_name` = "use_rewards_mod"';
_sql($sql, $errored, $error_ary);

$sql = 'DELETE FROM ' . $table_prefix . 'config WHERE `config_name` = "use_cash_system"';
_sql($sql, $errored, $error_ary);

$sql = 'DELETE FROM ' . $table_prefix . 'config WHERE `config_name` = "use_allowance_system"';
_sql($sql, $errored, $error_ary);

$sql = 'DELETE FROM ' . $table_prefix . 'config WHERE `config_name` = "use_point_system"';
_sql($sql, $errored, $error_ary);

$sql = 'DELETE FROM ' . $table_prefix . 'config WHERE `config_name` = "default_reward_dbfield"';
_sql($sql, $errored, $error_ary);

$sql = 'DELETE FROM ' . $table_prefix . 'config WHERE `config_name` = "default_cash"';
_sql($sql, $errored, $error_ary);


//
// Modify Fully Modded core-data
//
// Previous New Installers need this pa_config field added
$sql = "INSERT INTO " . $table_prefix . "pa_config (`config_name`, `config_value`) VALUES ('allow_comment_images', '0')";
_sql($sql, $errored, $error_ary);

// Previous New Installers need this forums field changed
$sql = 'ALTER TABLE ' . $table_prefix . 'forums MODIFY COLUMN `forum_template` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT "0"';
_sql($sql, $errored, $error_ary);

$sql = "INSERT INTO " . $table_prefix . "pages (`page_name`, `page_key`) VALUES ('polls.php', 'polls.php')";
_sql($sql, $errored, $error_ary);

?>