<?php
	
if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}

//
// Modify phpBB core-schema	
//
// phpbb_config data
$sql = "INSERT INTO " . $table_prefix . "config (`config_name`, `config_value`) VALUES ('charts_week_num', '1')";
_sql($sql, $errored, $error_ary);

// Some early downloaders of 71122 will need this field
$sql = 'ALTER TABLE ' . $table_prefix . 'users ADD COLUMN `user_transition` TINYINT(1) NOT NULL DEFAULT "1"';
_sql($sql, $errored, $error_ary);

?>