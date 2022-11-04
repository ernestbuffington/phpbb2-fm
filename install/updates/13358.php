<?php
	
if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}


//
// Modify Fully Modded core-schema
//
$sql = 'ALTER TABLE ' . $prefix . 'logs MODIFY COLUMN `id_log` MEDIUMINT(10) NOT NULL auto_increment';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'forum_move MODIFY COLUMN `move_id` MEDIUMINT(8) UNSIGNED NOT NULL auto_increment';
_sql($sql, $errored, $error_ary);

?>