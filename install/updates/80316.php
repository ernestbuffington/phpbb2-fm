<?php
	
if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}

//
// Modify phpBB core-data
//
// phpbb_config data
$config_data = array( 
	'forum_module_shoutcast' => 0,				
	'forum_module_shoutcast_height' => '',
	'shoutcast_server' => '',
	'shoutcast_port' => '',
	'shoutcast_pass' => '',
	'subchk_enable' => 0,
	'subchk_bypass' => 1,
	'subchk_strict' => 0,
	'subchk_locked' => 1,
	'subchk_limit' => 5,
	'subchk_admin' => 0,
	'subchk_mod' => 0,
	'subchk_postcount' => 0
);
while ( list ( $config_name, $config_value ) = each ( $config_data ) )
{
	$sql = "INSERT INTO " . $table_prefix . "config (`config_name`, `config_value`) 
		VALUES ('" . $config_name . "', '" . $config_value . "')";
	_sql($sql, $errored, $error_ary);
}

$sql = 'ALTER TABLE ' . $table_prefix . 'forums ADD COLUMN `forum_subject_check` TINYINT(1) DEFAULT "0"';
_sql($sql, $errored, $error_ary);


//
// Modify Fully Modded core-data
// phpbb_portal
$sql = 'ALTER TABLE ' . $table_prefix . 'portal ADD COLUMN `portal_latest_exclude_forums` VARCHAR(100) NOT NULL';
_sql($sql, $errored, $error_ary);

// phpbb_album_config data
$sql = 'INSERT INTO ' . $table_prefix . 'album_config (`config_name`, `config_value`) VALUES ("slidepics_per_page", "0")';
_sql($sql, $errored, $error_ary);
 
?>