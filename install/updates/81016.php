<?php
	
if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}

//
// Modify phpBB core-schema	
//
// Fix faked passworded topics, caused by bug in MOD when editing...
$sql = 'UPDATE ' . $prefix . 'topics SET `topic_password` = "" WHERE `topic_password` = "0"';
_sql($sql, $errored, $error_ary);

?>