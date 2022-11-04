<?php
	
if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}

//
// Modify phpBB core-schema	
//
$sql = 'INSERT INTO ' . $table_prefix . 'config (`config_name`, `config_value`) VALUES ("privmsg_self", "1")';
_sql($sql, $errored, $error_ary);

?>