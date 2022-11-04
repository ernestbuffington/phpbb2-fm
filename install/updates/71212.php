<?php
	
if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}

//
// Modify phpBB core-schema	
//
// Change this to fix SQL backups without using complete inserts
$sql = 'ALTER TABLE ' . $prefix . 'ina_hall_of_fame CHANGE `current_date` `date_today` INT(10) NOT NULL DEFAULT "0"';
_sql($sql, $errored, $error_ary);

// Remove duplicate KEY
$sql = 'ALTER TABLE ' . $prefix . 'link_comments DROP INDEX `comments_id`'; 
_sql($sql, $errored, $error_ary);
 
?>