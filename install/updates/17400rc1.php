<?php
	
if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}


//
// Modify Fully Modded core-schema
//
$sql = 'ALTER TABLE ' . $prefix . 'optimize_db ADD COLUMN `empty_tables` TINYINT(1) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $prefix . 'portal ADD COLUMN `portal_order` MEDIUMINT(8) UNSIGNED DEFAULT "1" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'portal DROP COLUMN `portal_title`';
_sql($sql, $errored, $error_ary);

?>