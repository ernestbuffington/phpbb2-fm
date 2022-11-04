<?php
	
if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}

//
// Modify phpBB core-schema	
//
// phpBB updaters had issues with this table not installing on build 80220
// Create it if it does not exist
$sql[] = 'CREATE TABLE IF NOT EXISTS '. $table_prefix .'bank (`user_id` MEDIUMINT(8) NOT NULL, `holding` bigint(20) UNSIGNED NOT NULL DEFAULT "0", `totalwithdrew` bigint(20) UNSIGNED NOT NULL DEFAULT "0", `totaldeposit` bigint(20) UNSIGNED NOT NULL DEFAULT "0", `opentime` int(11) NOT NULL DEFAULT "0", `fees` TINYINT(1) DEFAULT "1" NOT NULL, PRIMARY KEY (`user_id`)) TYPE=MYISAM';
 
?>