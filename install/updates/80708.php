<?php
	
if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}

//
// Modify phpBB core-schema	
//
// Special shop value screwed on last few builds due to UTF8 setting in code editor
// delete and re-install
$sql = 'DELETE FROM ' . $prefix . 'config WHERE `config_name` = "specialshop"';
_sql($sql, $errored, $error_ary);

$sql = 'INSERT INTO ' . $prefix . 'config (`config_name`, `config_value`) VALUES ("specialshop", "ßstoreÞdisabledßnameÞEffects StoreßoffÞ1ßoffÞ1ßoffÞ1ßoffÞ1ßoffÞ1ßoffÞ1ßoffÞ1ßoffÞ1ßoffÞ1ßoffÞ1ßoffÞ1ßoffÞ1ßoffÞ1")';
_sql($sql, $errored, $error_ary);

?>