<?php
	
if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}


//
// Modify phpBB core-scema	
//
$sql = 'ALTER TABLE ' . $table_prefix . 'banlist ADD COLUMN `user_name` VARCHAR(50) NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'banlist ADD COLUMN `reason` VARCHAR(75) NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'banlist ADD COLUMN `baned_by` VARCHAR(50) NOT NULL';
_sql($sql, $errored, $error_ary);
			
$sql = 'ALTER TABLE ' . $table_prefix . 'topics ADD COLUMN `answer_status` TINYINT(1) UNSIGNED DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);


//
// Modify Fully Modded core-schema
//
$sql = 'ALTER TABLE ' . $table_prefix . 'attachments_desc ADD COLUMN `width` SMALLINT(5) UNSIGNED DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'attachments_desc ADD COLUMN `height` SMALLINT(5) UNSIGNED DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'attachments_desc ADD COLUMN `border` TINYINT(1) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'attachments_desc ADD INDEX (filetime)';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'attachments_desc ADD INDEX (physical_filename(10))';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'attachments_desc ADD INDEX (filesize)';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $table_prefix . 'foing_songs ADD COLUMN `song_played` BIGINT DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
			
$sql = 'ALTER TABLE ' . $table_prefix . 'foing_status ADD COLUMN `status_pub` INT(1) DEFAULT "1" NOT NULL';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $table_prefix . 'pa_files ADD COLUMN `file_approved` TINYINT';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $table_prefix . 'portal ADD COLUMN `portal_games` TINYINT(1) DEFAULT "0"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'portal ADD COLUMN `portal_referrers` TINYINT(1) DEFAULT "0"';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $table_prefix . 'quota_limits MODIFY COLUMN `quota_limit` BIGINT(20) UNSIGNED DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
	
$sql = 'ALTER TABLE ' . $table_prefix . 'transactions ADD COLUMN `trans_reason` VARCHAR(255) DEFAULT "" NOT NULL';
_sql($sql, $errored, $error_ary);
			

//
// Create new Fully Modded core-schema
//
$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'buddy_list';
_sql($sql, $errored, $error_ary);
			
$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'counter';
_sql($sql, $errored, $error_ary);
		
$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'charts';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'charts (chart_id MEDIUMINT(8) NOT NULL auto_increment, chart_song_name VARCHAR(100) NOT NULL, chart_artist VARCHAR(100) NOT NULL, chart_album VARCHAR(100) DEFAULT "", chart_label VARCHAR(100) NOT NULL, chart_catno VARCHAR(50) NOT NULL, chart_website VARCHAR(100) NOT NULL, chart_poster_id VARCHAR(100) NOT NULL, chart_hot MEDIUMINT(8) DEFAULT "0", chart_not MEDIUMINT(8) DEFAULT "0", chart_curr_pos MEDIUMINT(8) DEFAULT "0", chart_last_pos MEDIUMINT(8) DEFAULT "0", chart_best_pos MEDIUMINT(8) DEFAULT "0", PRIMARY KEY (chart_id)) TYPE=MYISAM';
_sql($sql, $errored, $error_ary);
			
$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'charts_voters';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'charts_voters (vote_id MEDIUMINT(8) NOT NULL auto_increment, vote_user_id MEDIUMINT(8) NOT NULL, vote_chart_id MEDIUMINT(8) NOT NULL, vote_rate SMALLINT(2) NOT NULL, PRIMARY KEY (vote_id), FOREIGN KEY (vote_chart_id) REFERENCES '. $table_prefix .'charts (chart_id)) TYPE=MYISAM';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'foing_artist_desc';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'foing_artist_desc (artist_id MEDIUMINT(9) DEFAULT "0" NOT NULL, artist_desc mediumtext NOT NULL, PRIMARY KEY (artist_id), UNIQUE KEY artist_id(artist_id), KEY artist_id_2 (artist_id)) TYPE=MYISAM';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'foing_song_stats';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'im_buddy_list';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'im_buddy_list (user_id MEDIUMINT(8) DEFAULT "0" NOT NULL, buddy_id MEDIUMINT(8) DEFAULT "0" NOT NULL, user_ignore TINYINT(1) DEFAULT "0" NOT NULL, alert TINYINT(1) DEFAULT "0" NOT NULL, alert_status TINYINT(1) DEFAULT "0" NOT NULL, disallow TINYINT(1) DEFAULT "0" NOT NULL, KEY buddy_id (buddy_id), KEY user_id (user_id))';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'im_prefs';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'im_prefs (user_id MEDIUMINT(8) DEFAULT "0" NOT NULL, user_allow_ims TINYINT(1) DEFAULT "0" NOT NULL, user_allow_shout TINYINT(1) DEFAULT "1" NOT NULL, user_allow_chat TINYINT(1) DEFAULT "1" NOT NULL, attach_sig TINYINT(1) DEFAULT "0" NOT NULL, admin_allow_ims TINYINT(1) DEFAULT "1" NOT NULL, admin_allow_shout TINYINT(1) DEFAULT "1" NOT NULL, admin_allow_chat TINYINT(1) DEFAULT "1" NOT NULL, new_ims SMALLINT(5) UNSIGNED DEFAULT "0" NOT NULL, unread_ims SMALLINT(5) UNSIGNED DEFAULT "0" NOT NULL, read_ims SMALLINT(5) UNSIGNED DEFAULT "0" NOT NULL, total_sent MEDIUMINT(8) UNSIGNED DEFAULT "0" NOT NULL, refresh_rate SMALLINT(5) UNSIGNED DEFAULT "120" NOT NULL, success_close TINYINT(1) DEFAULT "1" NOT NULL, refresh_method TINYINT(1) DEFAULT "1" NOT NULL, auto_launch TINYINT(1) DEFAULT "1" NOT NULL, popup_ims TINYINT(1) DEFAULT "1" NOT NULL, list_ims TINYINT(1) DEFAULT "0" NOT NULL, show_controls TINYINT(1) DEFAULT "1" NOT NULL, list_all_online TINYINT(1) DEFAULT "1" NOT NULL, win_main_height VARCHAR(4) DEFAULT "400" NOT NULL, win_main_width VARCHAR(4) DEFAULT "225" NOT NULL, win_online_height VARCHAR(4) DEFAULT "225" NOT NULL, win_online_width VARCHAR(4) DEFAULT "400" NOT NULL, win_read_height VARCHAR(4) DEFAULT "225" NOT NULL, win_read_width VARCHAR(4) DEFAULT "400" NOT NULL, win_send_height VARCHAR(4) DEFAULT "365" NOT NULL, win_send_width VARCHAR(4) DEFAULT "460" NOT NULL, play_sound TINYINT(1) DEFAULT "1" NOT NULL, default_sound TINYINT(1) DEFAULT "1" NOT NULL, sound_name VARCHAR(255) DEFAULT NULL, themes_id MEDIUMINT(8) UNSIGNED DEFAULT "0" NOT NULL, PRIMARY KEY (user_id))';
_sql($sql, $errored, $error_ary);
			
$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'ip';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'ip (id tinyint(4) NOT NULL auto_increment, ip VARCHAR(200) DEFAULT "0" NOT NULL, host VARCHAR(200) DEFAULT "0" NOT NULL, date VARCHAR(200) DEFAULT "0" NOT NULL, username VARCHAR(200) DEFAULT "0" NOT NULL, referrer VARCHAR(200) DEFAULT "0" NOT NULL, forum VARCHAR(200) DEFAULT "0" NOT NULL, browser VARCHAR(200) DEFAULT "0" NOT NULL, KEY id(id)) TYPE=MYISAM';
_sql($sql, $errored, $error_ary);
			
$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'kb_articles';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'kb_articles (article_id TINYINT(4) NOT NULL auto_increment, article_category_id TINYINT(4) NOT NULL, article_title VARCHAR(255) NOT NULL, article_description VARCHAR(255) NOT NULL, article_date VARCHAR(255) NOT NULL, article_author_id MEDIUMINT(8) NOT NULL, bbcode_uid VARCHAR(10) NOT NULL, article_body TEXT NOT NULL, article_type TINYINT(4) NOT NULL, article_keywords VARCHAR(255) NOT NULL, approved TINYINT(1) DEFAULT "0" NOT NULL, UNIQUE article_id (article_id)) TYPE=MYISAM';
_sql($sql, $errored, $error_ary);
	
$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'kb_categories';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'kb_categories (`category_id` TINYINT(3) NOT NULL auto_increment, `category_name` VARCHAR(255) NOT NULL, `category_details` VARCHAR(255) NOT NULL, `number_articles` TINYINT(3) NOT NULL, UNIQUE `category_id` (category_id)) TYPE=MYISAM';
_sql($sql, $errored, $error_ary);
	
$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'kb_config';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'kb_config (`config_name` VARCHAR(255), `config_value` VARCHAR(255)) TYPE=MYISAM';
_sql($sql, $errored, $error_ary);
	
$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'kb_types';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'kb_types (`id` INT(4) NOT NULL auto_increment, `type` VARCHAR(255) DEFAULT "0" NOT NULL, UNIQUE KEY `id` (id)) TYPE=MYISAM';
_sql($sql, $errored, $error_ary);
		
$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'lottery';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'lottery (id int UNSIGNED NOT NULL auto_increment, user_id int(20) NOT NULL, PRIMARY KEY (id), INDEX (user_id)) TYPE=MYISAM';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'moddb';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'referral';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'referral (`referral_id` MEDIUMINT(8) UNSIGNED NOT NULL auto_increment, `ruid` MEDIUMINT(8) UNSIGNED DEFAULT "0" NOT NULL, `nuid` MEDIUMINT(8) UNSIGNED DEFAULT "0" NOT NULL, `referral_time` VARCHAR(10) DEFAULT "" NOT NULL, KEY `referraler_id` (referral_id)) TYPE=MYISAM';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'toplist';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'toplist (`id` INT(255) NOT NULL auto_increment, `nam` VARCHAR(255) NOT NULL DEFAULT "", `inf` VARCHAR(255) NOT NULL DEFAULT "", `hin` INT(255) NOT NULL DEFAULT "0", `lin` VARCHAR(255) NOT NULL DEFAULT "", `out` INT(255) NOT NULL DEFAULT "0", `img` INT(255) NOT NULL DEFAULT "0", `ban` VARCHAR(255) NOT NULL DEFAULT "http://", `owner` INT(255) NOT NULL DEFAULT "0", `tot` INT(255) NOT NULL DEFAULT "0", `imgfile` VARCHAR(50) NOT NULL DEFAULT "button1", PRIMARY KEY (id)) TYPE=MYISAM';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'unique_hits';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'unique_hits (`user_ip` CHAR(8) DEFAULT "0" NOT NULL, `time` INT(11) DEFAULT "0" NOT NULL, INDEX (user_ip)) TYPE=MYISAM';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'users_comments';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'users_comments (`comment_id` MEDIUMINT(8) auto_increment NOT NULL, `user_id` MEDIUMINT(8) NOT NULL, `poster_id` MEDIUMINT(8) NOT NULL, `comments` TEXT NOT NULL, `time` INT(11) DEFAULT NULL, PRIMARY KEY (comment_id)) TYPE=MYISAM';
_sql($sql, $errored, $error_ary);


//
// Modify phpBB core-data
// phpbb_config data
$config_data = array( 'lottery_base' => 500, 'lottery_cost' => 1, 'lottery_length' => 500000, 'lottery_name' => 'Lottery', 'lottery_reset' => 0, 'lottery_start' => 0, 'lottery_status' => 0, 'lottery_ticktype' => 0, 'message_length' => 10000, 'toplist_button_1' => 'images/toplist/button.gif', 'toplist_button_1_l' => 'http://' . $board_config['server_name'], 'toplist_button_2' => 'images/toplist/button.gif', 'toplist_button_2_l' => 'http://' . $board_config['server_name'], 'toplist_imge_dis' => 5, 'toplist_view_in_hits' => 1, 'toplist_view_img_hits' => 1, 'toplist_view_out_hits' => 1, 'use_allowance_system' => 0, 'use_point_system' => 1 );
while ( list ( $config_name, $config_value ) = each ( $config_data ) )
{
	$sql = "INSERT INTO " . $table_prefix . "config (`config_name`, `config_value`) VALUES ('" . $config_name . "', '" . $config_value . "')";
	_sql($sql, $errored, $error_ary);
}	


//
// Modify Fully Modded core-data
// phpbb_kb_config data
$kb_config_data = array( 'admin_id' => 2, 'approve_edit' => 1, 'approve_new' => 1, 'allow_edit' => 1, 'allow_new' => 1, 'notify' => 1, 'show_pretext' => 0 );
while ( list ( $config_name, $config_value ) = each ( $kb_config_data ) )
{
	$sql = "INSERT INTO " . $table_prefix . "kb_config (`config_name`, `config_value`) VALUES ('" . $config_name . "', '" . $config_value . "')";
	_sql($sql, $errored, $error_ary);
}
	
// phpbb_foing_config data
$sql = 'INSERT INTO ' . $table_prefix . 'foing_config (`config_name`, `config_value`) VALUES ("pending_status", "1")';
_sql($sql, $errored, $error_ary);

// phpbb_referral data
$sql = "INSERT INTO " . $table_prefix . "referral (`referral_id`, `ruid`, `nuid`, `referral_time`) VALUES ('1', '2', '2', '" . time() . "')";
_sql($sql, $errored, $error_ary);


//
// Change default values to sync with FullyModded setup
//
$sql = "UPDATE " . $table_prefix . "pa_files SET `file_approved` = '1'";
_sql($sql, $errored, $error_ary);
			
$sql = "UPDATE " . $table_prefix . "transactions SET `trans_reason` = 'N/A'";
_sql($sql, $errored, $error_ary);

$sql = 'UPDATE ' . $table_prefix . 'themes SET `body_bgcolor` = "FFFFFF", `tr_color1` = "ECECEC", `tr_color2` = "DCE1E5", `tr_color3` = "C7CFD7" WHERE `template_name` = "subSilver"';
_sql($sql, $errored, $error_ary);
	
$sql = 'UPDATE ' . $table_prefix . 'users SET `user_level` = "3" WHERE `user_level` = "2"';
_sql($sql, $errored, $error_ary);

// Update photo album data... 3 steps
// This needs to be checked if working correctly or not!!
// ... Step 1 of 3 - album
$sql = 'SELECT `pic_id`, `pic_title`, `pic_desc`, `pic_username` FROM ' . $table_prefix . 'album ORDER BY `pic_id` ASC';
$result = _sql($sql, $errored, $error_ary);
while ($row = $db->sql_fetchrow($result))
{
	$sql = 'UPDATE ' . $table_prefix . 'album SET `pic_title` = "' . str_replace("'", "''", stripslashes($row['pic_title'])) . '", `pic_desc` = "' . str_replace("'", "''", stripslashes($row['pic_desc'])) . '", `pic_username` = "' . str_replace("'", "''", stripslashes($row['pic_username'])) . '" WHERE `pic_id` = ' . $row['pic_id'];
	_sql($sql, $errored, $error_ary);
}
$db->sql_freeresult($result);
		
// ... Step 2 of 3 - album_comments
$sql = 'SELECT `comment_id`, `comment_username`, `comment_text` FROM ' . $table_prefix . 'album_comment ORDER BY `comment_id` ASC';
$result = _sql($sql, $errored, $error_ary);
while ($row = $db->sql_fetchrow($result))
{
	$sql = 'UPDATE ' . $table_prefix . 'album_comment SET `comment_username` = "' . str_replace("'", "''", stripslashes($row['comment_username'])) . '", `comment_text` = "' . str_replace("'", "''", stripslashes($row['comment_text'])) . '" WHERE `comment_id` = ' . $row['comment_id'];
	_sql($sql, $errored, $error_ary);
}
$db->sql_freeresult($result);
		
// ... Step 3 of 3 - album_cat
$sql = 'SELECT `cat_id`, `cat_title`, `cat_desc` FROM ' . $table_prefix . 'album_cat ORDER BY `cat_id` ASC';
$result = _sql($sql, $errored, $error_ary);
while ($row = $db->sql_fetchrow($result))
{
	$sql = 'UPDATE ' . $table_prefix . 'album_cat SET `cat_title` = "' . str_replace("'", "''", stripslashes($row['cat_title'])) . '", `cat_desc` = "' . str_replace("'", "''", stripslashes($row['cat_desc'])) . '" WHERE `cat_id` = ' . $row['cat_id'];
	_sql($sql, $errored, $error_ary);
}
$db->sql_freeresult($result);

?>