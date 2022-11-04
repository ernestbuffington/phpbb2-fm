<?php
	
if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}


//
// Modify phpBB core-scema	
//
$sql = 'ALTER TABLE ' . $table_prefix . 'sessions ADD COLUMN `session_admin` TINYINT(2) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'TRUNCATE TABLE ' . $table_prefix . 'sessions';
_sql($sql, $errored, $error_ary);

?>