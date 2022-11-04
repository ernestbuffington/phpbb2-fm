<?php
	
if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}


//
// Modify phpBB core-scema
//
$sql = 'ALTER TABLE ' . $table_prefix . 'categories ADD COLUMN `cat_sponsor_alt` VARCHAR(255) NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'categories ADD COLUMN `parent_forum_id` SMALLINT(5) UNSIGNED DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'categories ADD COLUMN `cat_hier_level` TINYINT UNSIGNED DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $table_prefix . 'forums ADD COLUMN `forum_hier_level` TINYINT UNSIGNED DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'forums ADD COLUMN `forum_issub` TINYINT(1) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
	
$sql = 'ALTER TABLE ' . $table_prefix . 'users ADD COLUMN `user_totalpages` INT(11) NOT NULL';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $table_prefix . 'vote_desc DROP COLUMN `vote_max`';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'vote_desc DROP COLUMN `vote_voted`';	
_sql($sql, $errored, $error_ary);


//
// Modify Fully Modded core-schema
//
$sql = 'ALTER TABLE ' . $table_prefix . 'attachments_desc ADD KEY filetime(filetime)';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'attachments_desc ADD KEY physical_filename(physical_filename(10))';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'attachments_desc ADD KEY filesize(filesize)';
_sql($sql, $errored, $error_ary);
			
$sql = 'ALTER TABLE ' . $table_prefix . 'extension_groups ADD COLUMN `forum_permissions` VARCHAR (255) DEFAULT "" NOT NULL';
_sql($sql, $errored, $error_ary);


//
// Create new Fully Modded core-schema
//
$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'attach_quota';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'attach_quota (user_id MEDIUMINT(8) UNSIGNED DEFAULT "0" NOT NULL, group_id MEDIUMINT(8) UNSIGNED DEFAULT "0" NOT NULL, quota_type SMALLINT(2) DEFAULT "0" NOT NULL, quota_limit_id MEDIUMINT(8) UNSIGNED DEFAULT "0" NOT NULL, KEY quota_type (quota_type))';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'cat_rel_cat_parents';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'cat_rel_cat_parents (cat_id MEDIUMINT(8) UNSIGNED DEFAULT "0" NOT NULL, parent_cat_id MEDIUMINT(8) UNSIGNED DEFAULT "0" NOT NULL, PRIMARY KEY (cat_id,parent_cat_id))';
_sql($sql, $errored, $error_ary);
			
$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'cat_rel_forum_parents';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'cat_rel_forum_parents (cat_id MEDIUMINT(8) UNSIGNED DEFAULT "0" NOT NULL, parent_forum_id SMALLINT(5) UNSIGNED DEFAULT "0" NOT NULL, PRIMARY KEY (cat_id,parent_forum_id))';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'pa_cat';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'pa_cat (cat_id INT(10) NOT NULL auto_increment, cat_name TEXT, cat_desc TEXT, cat_files INT(10) DEFAULT NULL, cat_1xid INT(10) DEFAULT NULL, cat_parent INT(50) DEFAULT NULL, cat_order INT(50) DEFAULT NULL, PRIMARY KEY (cat_id)) TYPE=MYISAM';
_sql($sql, $errored, $error_ary);
			
$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'pa_comments';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'pa_comments (comments_id INT(10) NOT NULL auto_increment, file_id INT(10) DEFAULT "0" NOT NULL, comments_text TEXT NOT NULL, comments_title TEXT NOT NULL, comments_time INT(50) DEFAULT "0" NOT NULL, comment_bbcode_uid VARCHAR(10) DEFAULT NULL, poster_id MEDIUMINT(8) DEFAULT "0" NOT NULL, PRIMARY KEY (comments_id), FULLTEXT KEY comment_bbcode_uid (comment_bbcode_uid)) TYPE=MYISAM';
_sql($sql, $errored, $error_ary);
			
$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'pa_custom';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'pa_custom (custom_id INT(50) NOT NULL auto_increment, custom_name TEXT NOT NULL, custom_description TEXT NOT NULL, PRIMARY KEY (custom_id)) TYPE=MYISAM';
_sql($sql, $errored, $error_ary);
			
$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'pa_customdata';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'pa_customdata (customdata_file INT(50) DEFAULT "0" NOT NULL, customdata_custom INT(50) DEFAULT "0" NOT NULL, data TEXT NOT NULL) TYPE=MYISAM';
_sql($sql, $errored, $error_ary);
			
$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'pa_files';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'pa_files (file_id INT(10) NOT NULL auto_increment, file_name TEXT, file_desc TEXT, file_creator TEXT, file_version TEXT, file_longdesc TEXT, file_ssurl TEXT, file_dlurl TEXT, file_time INT(50) DEFAULT NULL, file_catid INT(10) DEFAULT NULL, file_posticon TEXT, file_license INT(10) DEFAULT NULL, file_dls INT(10) DEFAULT NULL, file_last INT(50) DEFAULT NULL, file_pin INT(2) DEFAULT NULL, file_docsurl TEXT, file_rating DOUBLE(6,4) DEFAULT "0.0000" NOT NULL, file_totalvotes INT(255) DEFAULT NULL, PRIMARY KEY (file_id)) TYPE=MYISAM';
_sql($sql, $errored, $error_ary);
			
$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'pa_license';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'pa_license (license_id INT(10) NOT NULL auto_increment, license_name TEXT, license_text TEXT, PRIMARY KEY (license_id)) TYPE=MYISAM';
_sql($sql, $errored, $error_ary);
			
$sql = 'DROP TABLE IF EXISTS ' . $table_prefix .'pa_votes';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'pa_votes (votes_ip VARCHAR(50) DEFAULT "0" NOT NULL, votes_file INT(50) DEFAULT "0" NOT NULL) TYPE=MYISAM';
_sql($sql, $errored, $error_ary);
	
$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'quota_limits';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'quota_limits (quota_limit_id MEDIUMINT(8) UNSIGNED NOT NULL auto_increment, quota_desc VARCHAR(20) DEFAULT "" NOT NULL, quota_limit INT(20) UNSIGNED DEFAULT "0" NOT NULL, PRIMARY KEY (quota_limit_id))';
_sql($sql, $errored, $error_ary);


//
// Modify phpBB core-data
//
// phpbb_config data
$config_data = array( 'record_day_users' => 0, 'record_day_date' => 0 );
while ( list ( $config_name, $config_value ) = each ( $config_data ) )
{
	$sql = "INSERT INTO " . $table_prefix . "config (`config_name`, `config_value`) VALUES ('" . $config_name . "', '" . $config_value . "')";
	_sql($sql, $errored, $error_ary);
}			
			

//
// Modify Fully Modded core-data
//
// phpbb_attachments_config data
$attach_config_data = array( 'attach_version' => '2.3.5', 'default_pm_quota' => 0, 'default_upload_quota' => 0 );
while ( list ( $config_name, $config_value ) = each ( $attach_config_data ) )
{
	$sql = "INSERT INTO " . $table_prefix . "attachments_config (`config_name`, `config_value`) VALUES ('" . $config_name . "', '" . $config_value . "')";
	_sql($sql, $errored, $error_ary);
}

// phpbb_pa_cat data
$sql = "INSERT INTO " . $table_prefix . "pa_cat (`cat_name`, `cat_desc`, `cat_files`, `cat_1xid`, `cat_parent`, `cat_order`) VALUES ('Test category 1', 'This is just a test category', '0', '0', '0', '1')"; 
_sql($sql, $errored, $error_ary);

// phpbb_quota_limits data
$quota_limits_data = array( 'Low' => 262144, 'Medium' => 2097152, 'High' => 5242880 );
while ( list ( $quota_desc, $quota_limit) = each ( $quota_limits_data ) )
{
	$sql = "INSERT INTO " . $table_prefix . "quota_limits (`quota_desc`, `quota_limit`) VALUES ('" . $quota_desc. "', '" . $quota_limit . "')";
	_sql($sql, $errored, $error_ary);
}

	
?>