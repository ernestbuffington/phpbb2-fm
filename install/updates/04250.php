<?php
	
if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}


//
// Modify phpBB core-scema	
//
$sql = 'ALTER TABLE ' . $table_prefix . 'banlist ADD COLUMN `ban_time` INT(11) DEFAULT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'banlist ADD COLUMN `ban_expire_time` INT(11) DEFAULT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'banlist ADD COLUMN `ban_by_userid` MEDIUMINT(8) DEFAULT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'banlist ADD COLUMN `ban_priv_reason` TEXT';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'banlist ADD COLUMN `ban_pub_reason_mode` TINYINT(1) DEFAULT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'banlist ADD COLUMN `ban_pub_reason` TEXT';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $table_prefix . 'privmsgs ADD COLUMN `privmsgs_from_username` VARCHAR(25) DEFAULT "" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'privmsgs ADD COLUMN `privmsgs_to_username` VARCHAR(25) DEFAULT "" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'privmsgs ADD COLUMN `site_id` MEDIUMINT(8) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'privmsgs ADD COLUMN `room_id` MEDIUMINT(8) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'privmsgs ADD KEY `room_id` (room_id)';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'privmsgs ADD KEY `site_id` (site_id)';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $table_prefix . 'users ADD COLUMN `user_view_log` INT DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'users ADD COLUMN `user_profile_view` SMALLINT(5) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'users ADD COLUMN `user_last_profile_view` INT(11) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
				

//
// Modify Fully Modded core-schema
//
$sql = 'ALTER TABLE ' . $table_prefix . 'im_prefs ADD COLUMN `user_allow_s2s` TINYINT(1) DEFAULT "1" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'im_prefs ADD COLUMN `admin_allow_s2s` TINYINT(1) DEFAULT "1" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'im_prefs ADD COLUMN `s2s_user_list` TINYINT(1) DEFAULT "1" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'im_prefs ADD COLUMN `open_pms` TINYINT(1) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'im_prefs ADD COLUMN `auto_delete` TINYINT(1) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $table_prefix . 'kb_articles ADD COLUMN `topic_link` VARCHAR(255) NOT NULL DEFAULT ""';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $table_prefix . 'portal ADD COLUMN `portal_use_iframe` TINYINT(1)';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'portal ADD COLUMN `portal_column_width` VARCHAR(3) DEFAULT "200" NOT NULL';
_sql($sql, $errored, $error_ary);
		

//
// Create new Fully Modded core-schema
//
$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'anti_robotic_reg';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS '. $table_prefix .'chatbox';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE '. $table_prefix .'chatbox (id INT(11) NOT NULL auto_increment, name VARCHAR(99) NOT NULL, msg VARCHAR(255) NOT NULL, timestamp INT(10) UNSIGNED NOT NULL, PRIMARY KEY (id)) TYPE=MYISAM';
_sql($sql, $errored, $error_ary);
	
$sql = 'DROP TABLE IF EXISTS '. $table_prefix .'chatbox_session';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE '. $table_prefix .'chatbox_session (username VARCHAR(99) NOT NULL, lastactive INT(10) DEFAULT "0" NOT NULL, laststatus VARCHAR(8) NOT NULL, UNIQUE username (username)) TYPE=MYISAM';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS '. $table_prefix .'im_sessions';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE '. $table_prefix .'im_sessions (session_user_id MEDIUMINT(8) DEFAULT "0" NOT NULL, session_id CHAR(32) DEFAULT "" NOT NULL, session_time INT(11) DEFAULT "0" NOT NULL, session_popup TINYINT(1) DEFAULT "0" NOT NULL, PRIMARY KEY (session_id), KEY session_user_id (session_user_id)) TYPE=MYISAM';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'kb_pretext';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS '. $table_prefix .'link_config';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE '. $table_prefix .'link_config (config_name VARCHAR(255) DEFAULT "" NOT NULL, config_value VARCHAR(255) DEFAULT "" NOT NULL)';
_sql($sql, $errored, $error_ary);
			
$sql = 'DROP TABLE IF EXISTS '. $table_prefix .'logs_config';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE '. $table_prefix .'logs_config (config_name VARCHAR(255) NOT NULL, config_value VARCHAR(255) NOT NULL)';
_sql($sql, $errored, $error_ary);
		
$sql = 'DROP TABLE IF EXISTS '. $table_prefix .'news';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE '. $table_prefix .'news (config_name VARCHAR(255) NOT NULL, config_value VARCHAR(255) NOT NULL)';
_sql($sql, $errored, $error_ary);
			
$sql = 'DROP TABLE IF EXISTS '. $table_prefix .'posts_edit';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE '. $table_prefix .'posts_edit (post_id MEDIUMINT(8) UNSIGNED DEFAULT "0" NOT NULL, user_id MEDIUMINT(8) UNSIGNED DEFAULT "0" NOT NULL, post_edit_count SMALLINT(5) UNSIGNED DEFAULT "0" NOT NULL, post_edit_time INT(11) DEFAULT NULL, KEY (post_id)) TYPE=MYISAM';
_sql($sql, $errored, $error_ary);
		
$sql = 'DROP TABLE IF EXISTS '. $table_prefix .'profile_view';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE '. $table_prefix .'profile_view (user_id MEDIUMINT(8) NOT NULL, viewername VARCHAR(255) NOT NULL, viewer_id INT(11) NOT NULL, view_stamp INT(11) NOT NULL, counter MEDIUMINT(8) NOT NULL)';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS '. $table_prefix .'referers';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE '. $table_prefix .'referers (referer_id MEDIUMINT(8) UNSIGNED NOT NULL auto_increment, referer_http VARCHAR(255) DEFAULT "" NOT NULL, referer_hits INT(10) DEFAULT "1" NOT NULL, referer_firstvisit INT(11) DEFAULT "0" NOT NULL, referer_lastvisit INT(11) DEFAULT "0" NOT NULL, PRIMARY KEY (referer_id))';
_sql($sql, $errored, $error_ary);


//
// Modify phpBB core-data
// phpbb_config data
$config_data = array( 'enable_confirm' => 1, 'meta_abstract' => 'best forum', 'meta_author' => $board_config['board_email'], 'meta_description' => 'best phpBB forum', 'meta_distribution' => 'global', 'meta_keywords' => 'phpBB, phpbb, best, forum', 'meta_owner' => $board_config['board_email'], 'meta_robots' => 'index, follow', 'meta_revisit' => 20, 'referral_id' => -1, 'referral_enable' => 0, 'journal_forum_id' => 0, 'sendmail_fix' => 0 );
while ( list ( $config_name, $config_value ) = each ( $config_data ) )
{
	$sql = "INSERT INTO " . $table_prefix . "config (`config_name`, `config_value`) VALUES ('" . $config_name . "', '" . $config_value . "')";
	_sql($sql, $errored, $error_ary);
}	


//
// Modify Fully Modded core-data
// phpbb_forums_config data
$sql = "INSERT INTO " . $table_prefix . "forums_config (`config_name`, `config_value`) VALUES ('glance_forum_id', '1')";
_sql($sql, $errored, $error_ary);

// phpbb_kb_config data
$sql = 'INSERT INTO ' . $table_prefix . 'kb_config (`config_name`, `config_value`) VALUES ("pt_header", "Article Submission Instructions")';
_sql($sql, $errored, $error_ary);
			
$sql = 'INSERT INTO ' . $table_prefix . 'kb_config (`config_name`, `config_value`) VALUES ("pt_body", "Please check your references and include as much information as you can.")';
_sql($sql, $errored, $error_ary);

// phpbb_link_config data
$link_config_data = array( 'display_interval' => 6000, 'display_logo_num' => 2, 'height' => 31, 'linkspp' => 10, 'site_logo' => 'images/links/yourlogo.gif', 'site_url'  => 'http://' . $board_config['server_name'], 'width' => 88 );
while ( list ( $config_name, $config_value ) = each ( $link_config_data ) )
{
	$sql = "INSERT INTO " . $table_prefix . "link_config (`config_name`, `config_value`) VALUES ('" . $config_name . "', '" . $config_value . "')";
	_sql($sql, $errored, $error_ary);
}

// phpbb_logs_config data
$sql = "INSERT INTO " . $table_prefix . "logs_config (`config_name`, `config_value`) VALUES ('all_admin', '0')";
_sql($sql, $errored, $error_ary);

// phpbb_news data
$news_config_data = array( 'news_bold' => 'B', 'news_block' => 'Your news title', 'news_color' => 'black', 'news_ital' => 'none', 'news_size' => 2, 'news_style' => 'marquee', 'news_title' => 'Title', 'news_under' => 'none', 'scroll_action' => 'left', 'scroll_behavior' => 'scroll', 'scroll_size' => '100%', 'scroll_speed' => 5 );
while ( list ( $config_name, $config_value ) = each ( $news_config_data ) )
{
	$sql = "INSERT INTO " . $table_prefix . "news (`config_name`, `config_value`) VALUES ('" . $config_name . "', '" . $config_value . "')";
	_sql($sql, $errored, $error_ary);
}


//
// Change default values to sync with FullyModded setup
//
$sql = 'UPDATE ' . $table_prefix . 'foing_status SET `status_pub` = "0" WHERE `status_id` = "1"';
_sql($sql, $errored, $error_ary);

$sql = 'UPDATE ' . $table_prefix . 'foing_status SET `status_pub` = "0" WHERE `status_id` = "2"';
_sql($sql, $errored, $error_ary);

$sql = 'UPDATE ' . $table_prefix . 'foing_config SET `config_value` = "remote_getid3/remote_getid3.php" WHERE `config_name` = "remote_getid3"';
_sql($sql, $errored, $error_ary);

$sql = 'UPDATE ' . $table_prefix . 'foing_config SET `config_value` = "../templates/subSilver/foing/images/logo_flash.jpg" WHERE `config_name` = "flash_logo_file"';
_sql($sql, $errored, $error_ary);

?>