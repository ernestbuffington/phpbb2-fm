<?php
	
if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}


//
// Modify phpBB core-scema	
//
$sql = 'ALTER TABLE ' . $prefix . 'topics ADD COLUMN `title_compl_infos` VARCHAR(255)';
_sql($sql, $errored, $error_ary);
			

//
// Modify Fully Modded core-schema
//
$sql = 'ALTER TABLE ' . $prefix . 'pa_cat ADD COLUMN `auth_edit_file` TINYINT(1) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'pa_cat ADD COLUMN `auth_delete_file` TINYINT(1) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $prefix . 'portal ADD COLUMN `portal_horoscopes` TINYINT(1) DEFAULT "0"';
_sql($sql, $errored, $error_ary);
			

//
// Create new Fully Modded core-schema
//
$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'title_infos';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $prefix . 'title_infos (`id` INT NOT NULL auto_increment, `title_info` VARCHAR(255) NOT NULL, `date_format` VARCHAR(25), `admin_auth` TINYINT(1) DEFAULT "0", `supermod_auth` TINYINT(1) DEFAULT "0", `mod_auth` TINYINT(1) DEFAULT "0", `poster_auth` TINYINT(1) DEFAULT "0", UNIQUE (id))';
_sql($sql, $errored, $error_ary);


//
// Modify phpBB core-data
// phpbb_config data
$sql = 'INSERT INTO ' . $prefix . 'config (`config_name`, `config_value`) VALUES ("shoutbox_enable", "0")';
_sql($sql, $errored, $error_ary);
			
$sql = 'INSERT INTO ' . $prefix . 'config (`config_name`, `config_value`) VALUES ("disable_avatar_approve", "1")';
_sql($sql, $errored, $error_ary);

?>