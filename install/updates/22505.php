<?php
	
if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}

//
// Modify phpBB core-schema	
//
$sql = 'ALTER TABLE ' . $table_prefix . 'forums ADD COLUMN `forum_regdate_limit` MEDIUMINT(8) UNSIGNED DEFAULT "0"';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $table_prefix . 'themes ADD COLUMN `fontcolor4` VARCHAR(6)';
_sql($sql, $errored, $error_ary);

$sql = 'UPDATE ' . $table_prefix . 'themes SET `fontcolor4` = `fontcolor3`';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $table_prefix . 'themes_name ADD COLUMN `fontcolor4_name` CHAR(50)';
_sql($sql, $errored, $error_ary);

// phpbb_config data
$sql = "INSERT INTO " . $table_prefix . "config (`config_name`, `config_value`) VALUES ('missing_bbcode_imgs', '0')";
_sql($sql, $errored, $error_ary);

?>