<?php
	
if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}


//
// Create new phpBB core-scema
//
$sql = 'CREATE TABLE ' . $prefix . 'confirm (`confirm_id` char(32) DEFAULT "" NOT NULL, `session_id` char(32) DEFAULT "" NOT NULL, `code` char(6) DEFAULT "" NOT NULL, PRIMARY KEY (session_id, confirm_id))';
_sql($sql, $errored, $error_ary);


//
// Modify phpBB core-scema	
//
$sql = 'ALTER TABLE ' . $prefix . 'auth_access ADD COLUMN `auth_voteban` TINYINT(1) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
			
$sql = 'ALTER TABLE ' . $prefix . 'categories ADD COLUMN `cat_icon` VARCHAR(255)';
_sql($sql, $errored, $error_ary);
			
$sql = 'ALTER TABLE ' . $prefix . 'forums ADD COLUMN `auth_voteban` TINYINT(2) DEFAULT "1" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'forums ADD COLUMN `forum_icon` VARCHAR(255)';
_sql($sql, $errored, $error_ary);
			
$sql = 'ALTER TABLE ' . $prefix . 'posts_text MODIFY COLUMN `post_subject` VARCHAR(120) NOT NULL';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $prefix . 'topics MODIFY COLUMN `topic_title` CHAR(120) NOT NULL';
_sql($sql, $errored, $error_ary);
			
$sql = 'ALTER TABLE ' . $prefix . 'users ADD COLUMN user_votewarnings SMALLINT(5) DEFAULT "0" NULL';
_sql($sql, $errored, $error_ary);
			

//
// Modify Fully Modded core-schema
//
$sql = 'ALTER TABLE ' . $prefix . 'banner ADD COLUMN `banner_forum` MEDIUMINT(8) UNSIGNED NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'banner ADD COLUMN `banner_level` TINYINT(1) NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'banner ADD COLUMN `banner_level_type` TINYINT(1) NOT NULL';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $prefix . 'pa_cat ADD COLUMN `parents_data` TEXT NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'pa_cat ADD COLUMN `cat_allow_file` TINYINT(2) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'pa_cat ADD COLUMN `auth_view` TINYINT(2) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'pa_cat ADD COLUMN `auth_read` TINYINT(2) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'pa_cat ADD COLUMN `auth_view_file` TINYINT(2) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'pa_cat ADD COLUMN `auth_upload` TINYINT(2) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'pa_cat ADD COLUMN `auth_download` TINYINT(2) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'pa_cat ADD COLUMN `auth_rate` TINYINT(2) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'pa_cat ADD COLUMN `auth_email` TINYINT(2) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'pa_cat ADD COLUMN `auth_view_comment` TINYINT(2) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'pa_cat ADD COLUMN `auth_post_comment` TINYINT(2) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'pa_cat ADD COLUMN `auth_edit_comment` TINYINT(2) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'pa_cat ADD COLUMN `auth_delete_comment` TINYINT(2) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'pa_cat DROP COLUMN `cat_1xid`';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'pa_cat DROP COLUMN `cat_files`';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $prefix . 'pa_custom ADD COLUMN `data` TEXT NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'pa_custom ADD COLUMN `regex` VARCHAR(255) NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'pa_custom ADD COLUMN `field_order` INT(20) NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'pa_custom ADD COLUMN `field_type` TINYINT(2) NOT NULL';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $prefix . 'pa_custom_data RENAME ' . $prefix . 'pa_customdata';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $prefix . 'pa_files ADD COLUMN `user_id` MEDIUMINT(8) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'pa_files ADD COLUMN `poster_ip` VARCHAR(8) NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'pa_files ADD COLUMN `file_sshot_link` TINYINT(2) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'pa_files ADD COLUMN `file_update_time` INT(50) NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'pa_files MODIFY COLUMN `file_approved` TINYINT(2) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'pa_files DROP COLUMN `file_rating`';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'pa_files DROP COLUMN `file_totalvotes`';
_sql($sql, $errored, $error_ary);
			
$sql = 'ALTER TABLE ' . $prefix . 'pa_votes ADD COLUMN `user_id` MEDIUMINT(8) DEFAULT "0" NOT NULL FIRST';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'pa_votes ADD COLUMN `rate_point` TINYINT(3) UNSIGNED NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'pa_votes ADD COLUMN `voter_os` VARCHAR(255) NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'pa_votes ADD COLUMN `voter_browser` VARCHAR(255) NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'pa_votes ADD COLUMN `browser_version` VARCHAR(8) NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'pa_votes ADD KEY `user_id` (user_id)';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'pa_votes ADD KEY `votes_file` (votes_file)';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'pa_votes ADD KEY `votes_ip` (votes_ip)';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'pa_votes ADD KEY `voter_os` (voter_os)';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'pa_votes ADD KEY `voter_browser` (voter_browser)';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'pa_votes ADD KEY `browser_version` (browser_version)';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'pa_votes ADD KEY `browser_version_2` (browser_version)';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'pa_votes ADD KEY `rate_point` (rate_point)';
_sql($sql, $errored, $error_ary);
			

//
// Create new Fully Modded core-schema
//
$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'advance_html';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $prefix . 'advance_html (`config_name` VARCHAR(255) DEFAULT "" NOT NULL, `config_value` LONGTEXT)';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'forums_watch';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $prefix . 'forums_watch (`forum_id` MEDIUMINT(8) UNSIGNED DEFAULT "0" NOT NULL, `user_id` MEDIUMINT(8) DEFAULT "0" NOT NULL, `notify_status` TINYINT(1) DEFAULT "0" NOT NULL, KEY `forum_id` (forum_id), KEY `user_id` (user_id), KEY `notify_status` (notify_status))';
_sql($sql, $errored, $error_ary);
			
$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'topics_viewdata';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $prefix . 'topics_viewdata (`viewed_id` INT(10) UNSIGNED NOT NULL auto_increment, `user_id` MEDIUMINT(8) DEFAULT "0" NOT NULL, `topic_id` MEDIUMINT(8) UNSIGNED DEFAULT "0" NOT NULL, `num_views` TINYINT(4) UNSIGNED DEFAULT "1" NOT NULL, `last_viewed` INT(10) UNSIGNED DEFAULT "0" NOT NULL, PRIMARY KEY (viewed_id), KEY `user_id` (user_id, topic_id), KEY `last_viewed` (last_viewed))';
_sql($sql, $errored, $error_ary);
	
$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'lottery_winner';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $prefix . 'lottery_winner (`win_date` INT(11) DEFAULT "0" NOT NULL, `winner_name` VARCHAR(25) DEFAULT "" NOT NULL, `prize_amount` VARCHAR(25) DEFAULT "" NOT NULL, PRIMARY KEY (win_date))';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'banvote_voters';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $prefix . 'banvote_voters (`banvote_user_id` MEDIUMINT(8) DEFAULT "0" NOT NULL, `banvote_banner_id` MEDIUMINT(8) DEFAULT "0" NOT NULL)';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'pa_download_info';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $prefix . 'pa_download_info (`file_id` MEDIUMINT(8) DEFAULT "0" NOT NULL, `user_id` MEDIUMINT(8) DEFAULT "0" NOT NULL, `downloader_ip` VARCHAR(8) NOT NULL, `downloader_os` VARCHAR(8) NOT NULL, `downloader_browser` VARCHAR(255) DEFAULT "" NOT NULL, `browser_version` VARCHAR(8) NOT NULL)';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'pa_config';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $prefix . 'pa_config (`config_name` VARCHAR(255) NOT NULL, `config_value` VARCHAR(255) NOT NULL, PRIMARY KEY (config_name))';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'pa_settings';
_sql($sql, $errored, $error_ary);


//
// Modify phpBB core-data
//
$sql = 'SELECT `user_id`, `username` FROM ' . $prefix . 'users';
$result = _sql($sql, $errored, $error_ary);
while ($row = $db->sql_fetchrow($result))
{
	if (!preg_match('#(&gt;)|(&lt;)|(&quot)|(&amp;)#', $row['username']))
	{
		if ($row['username'] != htmlspecialchars($row['username']))
		{
			$sql = "UPDATE " . $prefix . "users SET `username` = '" . str_replace("'", "''", htmlspecialchars($row['username'])) . "' WHERE `user_id` = " . $row['user_id'];
			_sql($sql, $errored, $error_ary);
		}
	}
}
$db->sql_freeresult($result);

// phpbb_config data
$config_data = array( 'debug_value' => 0, 'page_transition' => '', 'max_user_votebancard' => 50, 'visit_counter' => 1, 'year_stars' => 1 );
while ( list ( $config_name, $config_value ) = each ( $config_data ) )
{
	$sql = "INSERT INTO " . $prefix . "config (`config_name`, `config_value`) VALUES ('" . $config_name . "', '" . $config_value . "')";
	_sql($sql, $errored, $error_ary);
}

//				
// Modify Fully Modded core-data
//
// phpbb_advance_html data
$advance_html_data = array('custom_body', 'custom_body_header', 'custom_footer', 'custom_header');
for ( $i = 0; $i < 4; $i++ )
{
	$sql = "INSERT INTO " . $prefix . "advance_html (`config_name`, `config_value`) VALUES ('" . $advance_html_data[$i] . "', '')";
	_sql($sql, $errored, $error_ary);
}

// phpbb_pa_config data
$pa_config_data = array( 'allow_bbcode' => 1, 'allow_comment_images' => 0, 'allow_comment_links' => 1, 'allow_html' => 1, 'allow_smilies' => 1, 'hotlink_allowed' => '', 'hotlink_prevent' => 0, 'max_comment_chars' => 5000, 'no_comment_image_message' => '[No images please]', 'no_comment_link_message' => '[No links please]', 'settings_dbdescription' => '', 'settings_dbname' => 'Download Database', 'settings_disable' => 0, 'settings_file_page' => 20, 'settings_newdays' => 7, 'settings_stats' => '', 'settings_topnumber' => 10, 'settings_viewall' => 1, 'sort_method' => 'file_time', 'sort_order' => 'DESC', 'tpl_php' => 0 );
while ( list ( $config_name, $config_value ) = each ( $pa_config_data ) )
{
	$sql = "INSERT INTO " . $prefix . "pa_config (`config_name`, `config_value`) VALUES ('" . $config_name . "', '" . $config_value . "')";
	_sql($sql, $errored, $error_ary);
}
			

//
// Change default values to sync with FullyModded setup
//
$sql = 'UPDATE ' . $prefix . 'config SET `config_value` = "1" WHERE `config_name` = "gzip_compress"';
_sql($sql, $errored, $error_ary);
			
$sql = 'UPDATE ' . $prefix . 'pa_files SET `file_approved` = "1"';
_sql($sql, $errored, $error_ary);
			
$sql = 'UPDATE ' . $prefix . 'pa_files SET `user_id` = "2"';
_sql($sql, $errored, $error_ary);
			
$sql = 'UPDATE ' . $prefix . 'pa_cat SET `cat_allow_file` = "1"';
_sql($sql, $errored, $error_ary);

?>