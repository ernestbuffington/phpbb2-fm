<?php

//
// Modify phpBB core-scema	
//
$sql = 'ALTER TABLE ' . $prefix . 'forums MODIFY COLUMN `forum_external` TINYINT(1) NOT NULL DEFAULT "0"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'forums ADD COLUMN `forum_ext_newwin` TINYINT(1) NOT NULL DEFAULT "0"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'forums ADD COLUMN `forum_ext_image` TEXT';
_sql($sql, $errored, $error_ary);
			

//
// Modify Fully Modded core-schema
//
$sql = 'ALTER TABLE ' . $prefix . 'portal ADD COLUMN `portal_latest_amt` VARCHAR(5) DEFAULT "5" NOT NULL';
_sql($sql, $errored, $error_ary);

?>