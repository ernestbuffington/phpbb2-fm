<?php
	
if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}

//
// Modify phpBB core-schema	
//
$sql = 'ALTER TABLE ' . $table_prefix . 'forums ADD COLUMN `topic_password` TINYINT(1) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $table_prefix . 'topics ADD COLUMN `topic_password` VARCHAR(20) DEFAULT "" NOT NULL';
_sql($sql, $errored, $error_ary);

?>