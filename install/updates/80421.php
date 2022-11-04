<?php
	
if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}

//
// Modify phpBB core-schema	
//
// These should not exist
$sql = 'ALTER TABLE ' . $table_prefix . 'auth_access DROP COLUMN `auth_auth_suggest_events`';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $table_prefix . 'users DROP COLUMN `user_post_color`';
_sql($sql, $errored, $error_ary);

// Special shop value screwed on last few builds due to UTF8 setting in code editor
// delete and re-install
$sql = 'DELETE FROM ' . $table_prefix . 'config WHERE `config_name` = "specialshop"';
_sql($sql, $errored, $error_ary);

$sql = 'INSERT INTO ' . $table_prefix . 'config (`config_name`, `config_value`) VALUES ("specialshop", "ßstoreÞdisabledßnameÞEffects StoreßoffÞ1ßoffÞ1ßoffÞ1ßoffÞ1ßoffÞ1ßoffÞ1ßoffÞ1ßoffÞ1ßoffÞ1ßoffÞ1ßoffÞ1ßoffÞ1ßoffÞ1")';
_sql($sql, $errored, $error_ary);

?>