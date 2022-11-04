<?php
	
if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}


//
// Modify phpBB core-scema	
//
$sql = 'ALTER TABLE ' . $prefix . 'banlist DROP COLUMN `date`';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'banlist DROP COLUMN `time`';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $prefix . 'forums ADD COLUMN `display_moderators` TINYINT(1) DEFAULT "1" NOT NULL';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $prefix . 'search_results MODIFY COLUMN search_array MEDIUMTEXT NOT NULL';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $prefix . 'themes ADD COLUMN `hr_color1` VARCHAR(6) DEFAULT "E8F3FC"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'themes ADD COLUMN `hr_color2` VARCHAR(6) DEFAULT "D5E8F9"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'themes ADD COLUMN `hr_color3` VARCHAR(6) DEFAULT "B7D9F6"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'themes ADD COLUMN `hr_color4` VARCHAR(6) DEFAULT "FCECE8"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'themes ADD COLUMN `hr_color5` VARCHAR(6) DEFAULT "F9DDD5"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'themes ADD COLUMN `hr_color6` VARCHAR(6) DEFAULT "FACCBF"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'themes ADD COLUMN `hr_color7` VARCHAR(6) DEFAULT "E9FAEA"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'themes ADD COLUMN `hr_color8` VARCHAR(6) DEFAULT "D5F9D6"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'themes ADD COLUMN `hr_color9` VARCHAR(6) DEFAULT "B2EEB4"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'themes ADD COLUMN `jb_color1` VARCHAR(6) DEFAULT "006EBB"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'themes ADD COLUMN `jb_color2` VARCHAR(6) DEFAULT "FF6428"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'themes ADD COLUMN `jb_color3` VARCHAR(6) DEFAULT "329600"';
_sql($sql, $errored, $error_ary);
	
$sql = 'ALTER TABLE ' . $prefix . 'themes_name ADD COLUMN `hr_color1_name` CHAR(50)';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'themes_name ADD COLUMN `hr_color2_name` CHAR(50)';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'themes_name ADD COLUMN `hr_color3_name` CHAR(50)';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'themes_name ADD COLUMN `hr_color4_name` CHAR(50)';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'themes_name ADD COLUMN `hr_color5_name` CHAR(50)';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'themes_name ADD COLUMN `hr_color6_name` CHAR(50)';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'themes_name ADD COLUMN `hr_color7_name` CHAR(50)';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'themes_name ADD COLUMN `hr_color8_name` CHAR(50)';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'themes_name ADD COLUMN `hr_color9_name` CHAR(50)';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'themes_name ADD COLUMN `jb_color1_name` CHAR(50)';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'themes_name ADD COLUMN `jb_color2_name` CHAR(50)';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'themes_name ADD COLUMN `jb_color3_name` CHAR(50)';
_sql($sql, $errored, $error_ary);
	
$sql = 'ALTER TABLE ' . $prefix . 'topics ADD COLUMN `title_compl_color` VARCHAR(6) DEFAULT "" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'topics ADD COLUMN `title_pos` TINYINT(1) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $prefix . 'users MODIFY COLUMN `username` VARCHAR(99) NOT NULL';
_sql($sql, $errored, $error_ary);

		
//
// Create new Fully Modded core-schema
//
$sql = 'DROP TABLE IF EXISTS '. $prefix .'backup';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE '. $prefix .'backup (`backup_skill` int(1) NOT NULL, `email_true` int(1) NOT NULL, `email` text NOT NULL, `ftp_true` int(1) NOT NULL, `ftp_server` text NOT NULL, `ftp_user_name` text NOT NULL, `ftp_user_pass` text NOT NULL, `ftp_directory` text NOT NULL, `write_backups_true` int(1) NOT NULL, `files_to_keep` varchar(255) NOT NULL, `cron_time` text NOT NULL, `delay_time` text NOT NULL, `backup_type` text NOT NULL, `phpbb_only` int(1) NOT NULL, `no_search` int(1) NOT NULL, `ignore_tables` text NOT NULL, `last_run` int(11) NOT NULL,  `finished` int(1) NOT NULL) TYPE=MYISAM';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'link_votes';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $prefix . 'link_votes (user_id mediumint(8) NOT NULL default "0", votes_ip varchar(50) NOT NULL default "0", votes_link int(50) NOT NULL default "0", rate_point tinyint(3) unsigned NOT NULL default "0", KEY user_id (user_id)) TYPE=MyISAM';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'link_comments';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $prefix . 'link_comments (comments_id int(10) NOT NULL auto_increment, link_id int(10) NOT NULL default "0", comments_text text NOT NULL, comments_title text NOT NULL, comments_time int(50) NOT NULL default "0", comment_bbcode_uid varchar(10) default NULL, poster_id mediumint(8) NOT NULL default "0", PRIMARY KEY (comments_id), FULLTEXT KEY comment_bbcode_uid (comment_bbcode_uid)) TYPE=MyISAM';
_sql($sql, $errored, $error_ary);
		
$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'link_custom';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $prefix . 'link_custom (custom_id int(50) NOT NULL auto_increment, custom_name text NOT NULL, custom_description text NOT NULL, data text NOT NULL, field_order int(20) NOT NULL default "0", field_type tinyint(2) NOT NULL default "0", regex varchar(255) NOT NULL default "", PRIMARY KEY  (custom_id)) TYPE=MyISAM';
_sql($sql, $errored, $error_ary);
			
$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'link_customdata';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $prefix . 'link_customdata (customdata_file int(50) NOT NULL default "0", customdata_custom int(50) NOT NULL default "0", data text NOT NULL) TYPE=MyISAM';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'meeting_guestnames';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $prefix . 'meeting_guestnames (`meeting_id` MEDIUMINT(8) NOT NULL DEFAULT "0", `user_id` MEDIUMINT(8) NOT NULL DEFAULT "0", `guest_prename` VARCHAR(255) NOT NULL DEFAULT "", `guest_name` VARCHAR(255) NOT NULL DEFAULT "") TYPE=MyISAM';
_sql($sql, $errored, $error_ary);


//
// Modify Fully Modded core-schema
//
$sql = 'ALTER TABLE ' . $prefix . 'bank DROP COLUMN `id`';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'bank CHANGE `name` `user_id` MEDIUMINT(8) NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'bank CHANGE `fees` `fees` TINYINT(1) NOT NULL DEFAULT "1"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'bank DROP INDEX `name`';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'bank ADD PRIMARY KEY (`user_id`)';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'foing_albums';
_sql($sql, $errored, $error_ary);
$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'foing_artists';
_sql($sql, $errored, $error_ary);
$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'foing_artist_desc';
_sql($sql, $errored, $error_ary);
$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'foing_genres';
_sql($sql, $errored, $error_ary);
$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'foing_config';
_sql($sql, $errored, $error_ary);
$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'foing_playlist';
_sql($sql, $errored, $error_ary);
$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'foing_playlist_data';
_sql($sql, $errored, $error_ary);
$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'foing_songs';
_sql($sql, $errored, $error_ary);
$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'foing_song_desc';
_sql($sql, $errored, $error_ary);
$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'foing_status';
_sql($sql, $errored, $error_ary);
			
$sql = 'ALTER TABLE ' . $prefix . 'kb_config MODIFY COLUMN `config_name` VARCHAR(255) NOT NULL DEFAULT ""';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'kb_config MODIFY COLUMN `config_value` VARCHAR(255) NOT NULL DEFAULT ""';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'kb_config ADD PRIMARY KEY (`config_name`)'; 
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $prefix . 'kb_results MODIFY COLUMN search_array MEDIUMTEXT NOT NULL';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $prefix . 'links CHANGE `link_title` `link_name` TEXT';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'links CHANGE `link_desc` `link_longdesc` TEXT';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'links CHANGE `link_category` `link_catid` MEDIUMINT(8) UNSIGNED DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'links CHANGE `link_joined` `link_time` INT(25) DEFAULT "0"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'links CHANGE `link_active` `link_approved` TINYINT(1) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'links CHANGE `user_ip` `poster_ip` VARCHAR(8) NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'links ADD COLUMN `post_username` varchar(25) default NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'links ADD COLUMN `link_pin` int(2) default "0"';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $prefix . 'link_config ADD PRIMARY KEY (`config_name`)'; 
_sql($sql, $errored, $error_ary);

$sql = 'RENAME TABLE ' . $prefix . 'links_categories TO ' . $prefix . 'link_categories';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'link_categories CHANGE `cat_id` `cat_id` INT(10) NOT NULL AUTO_INCREMENT';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'link_categories CHANGE `cat_title` `cat_name` TEXT DEFAULT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'link_categories CHANGE `cat_order` `cat_order` INT(50)';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'link_categories ADD COLUMN `cat_parent` int(50) default NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'link_categories ADD COLUMN `parents_data` text NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'link_categories ADD COLUMN `cat_links` mediumint(8) NOT NULL default "-1"';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $prefix . 'logs_config ADD PRIMARY KEY (`config_name`)'; 
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $prefix . 'lottery CHANGE `user_id` `user_id` INT(10) NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'lottery_history CHANGE `user_id` `user_id` INT(10) NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'lottery_history CHANGE `amount` `amount` INT(10) NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'lottery_history CHANGE `time` `time` INT(10) NOT NULL';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $prefix . 'meeting_data ADD COLUMN `meeting_guest_overall` MEDIUMINT(8) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'meeting_data ADD COLUMN `meeting_guest_single` MEDIUMINT(8) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'meeting_data ADD COLUMN `meeting_guest_names` TINYINT(1) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $prefix . 'meeting_user ADD COLUMN `meeting_guests` MEDIUMINT(8) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $prefix . 'news ADD PRIMARY KEY (`config_name`)'; 
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $prefix . 'pa_config ADD PRIMARY KEY (`config_name`)'; 
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $prefix . 'user_shops CHANGE `items_holding` `items_holding` INT(10) UNSIGNED NOT NULL DEFAULT "0"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'title_infos ADD COLUMN `info_color` VARCHAR(6) DEFAULT "" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'title_infos ADD COLUMN `title_pos` TINYINT(1) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'topic_view';
_sql($sql, $errored, $error_ary);


//
// Modify phpBB core-data
// phpbb_backup data
$sql = "INSERT INTO " . $prefix . "backup (`backup_skill`, `email_true`, `email`, `ftp_true`, `write_backups_true`, `files_to_keep`, `cron_time`, `delay_time`, `backup_type`, `phpbb_only`, `no_search`, `last_run`) VALUES (1, 0, '" . $board_config['board_email'] . "', 0, 1, 7, '0    0    *    *    *', 120, 'full', 1, 0, " . time() . ")";
_sql($sql, $errored, $error_ary);

// phpbb_config data
$config_data = array( 'meta_identifier_url' => '', 'meta_reply_to' => $board_config['board_email'], 'meta_category' => 'phpBB', 'meta_copyright' => '', 'meta_generator' => 'Fully Modded phpBB', 'meta_date_creation_day' => '', 'meta_date_creation_month' => '', 'meta_date_creation_year' => '', 'meta_date_revision_day' => '', 'meta_date_revision_month' => '', 'meta_date_revision_year' => '', 'meta_redirect_url_time' => '', 'meta_redirect_url_adress' => '', 'meta_refresh' => '', 'meta_pragma' => 'no-cache', 'meta_language' => 'en', 'meta_rating' => 'General', 'AJAXed_status' => 0, 'AJAXed_status_prem' => 0, 'AJAXed_inline_post_editing' => 0, 'AJAXed_inline_post_editing_prem' => 0, 'AJAXed_post_title' => 0, 'AJAXed_post_ip' => 0, 'AJAXed_post_menu' => 0, 'AJAXed_poll_menu' => 0, 'AJAXed_poll_title' => 0, 'AJAXed_poll_options' => 0, 'AJAXed_post_preview' => 0, 'AJAXed_pm_preview' => 0, 'AJAXed_user_list' => 0, 'AJAXed_user_list_number' => 30, 'AJAXed_forum_delete' => 0, 'AJAXed_display_delete' => 0, 'AJAXed_forum_move' => 0, 'AJAXed_display_move' => 0, 'AJAXed_forum_lock' => 0, 'AJAXed_post_delete' => 0, 'AJAXed_topic_delete' => 0, 'AJAXed_topic_move' => 0, 'AJAXed_topic_lock' => 0, 'AJAXed_topic_watch' => 0, 'AJAXed_username_check' => 0, 'AJAXed_password_check' => 0, 'autologin_check' => 0, 'bank_minwithdraw' => 0, 'bank_mindeposit' => 0, 'bank_interestcut' => 0, 'board_disable_mode' => '-1,0', 'callhome_disable' => 0, 'limit_username_min_length' => 2, 'limit_username_max_length' => 25, 'pass_gen_enable' => 0, 'pass_gen_length' => 6, 'pass_gen_alphanumerical' => 1, 'pass_gen_uppercase' => 1, 'pass_gen_lowercase' => 1, 'pass_gen_specialchars' => 1, 'pass_gen_numbers' => 1 );
while ( list ( $config_name, $config_value ) = each ( $config_data ) )
{
	$sql = "INSERT INTO " . $prefix . "config (`config_name`, `config_value`) VALUES ('" . $config_name . "', '" . $config_value . "')";
	_sql($sql, $errored, $error_ary);
}			

$rconfig_data = array( 'bankholdings', 'banktotaldeposits', 'banktotalwithdrew' );
for ( $i = 0; $i < sizeof($rconfig_data); $i++ )
{
	$sql = "DELETE FROM " . $prefix . "config WHERE `config_name` = '" . $rconfig_data[$i] . "'";
	_sql($sql, $errored, $error_ary);
}
			

//
// Modify Fully Modded core-data
// Previous New Installers need these ina_ fields fixed AGAIN! (forgot to change install script, doh!)
$sql = 'UPDATE ' . $prefix . 'config SET `config_name` = "ina_default_g_path" WHERE `config_name` = "ina_default_g_path "';
_sql($sql, $errored, $error_ary);

$sql = 'UPDATE ' . $prefix . 'config SET `config_name` = "ina_pm_trophy_sub" WHERE `config_name` = "ina_pm_trophy_sub "';
_sql($sql, $errored, $error_ary);

$sql = 'UPDATE ' . $prefix . 'config SET `config_name` = "ina_use_online" WHERE `config_name` = "ina_use_online "';
_sql($sql, $errored, $error_ary);

$sql = 'UPDATE ' . $prefix . 'config SET `config_name` = "ina_max_games_per_day_date" WHERE `config_name` = "ina_max_games_per_day_date "';
_sql($sql, $errored, $error_ary);

// phpbb_config_nav data
$sql = 'DELETE FROM ' . $prefix . 'config_nav WHERE `url` = "player/index.php"';
_sql($sql, $errored, $error_ary);

$sql = 'UPDATE ' . $prefix . 'config_nav SET `url` = "linkdb.php" WHERE `url` = "links.php"';
_sql($sql, $errored, $error_ary);

// Previous phpBB 2.x Updaters need some of these fields added
// phpbb_link_config data
$link_config_data = array('display_links_logo' => 1, 'email_notify' => 1, 'pm_notify' => 0, 'lock_submit_site' => 0, 'allow_no_logo' => 0, 'cat_col' => 3, 'sort_method' => 'link_time', 'sort_order' => 'DESC', 'allow_guest_submit_site' => 0, 'split_links' => 0, 'allow_vote' => 1, 'linkdb_versions' => '0.0.9', 'allow_edit_link' => 1, 'allow_delete_link' => 0, 'allow_comment' => 1, 'max_comment_chars' => 5000, 'need_validation' => 1, 'url_validation' => 1, 'url_validation_setting' => 's?:H?:S?:E-:F-:u?:P?:a+:I?:p?:f?:q?:r?' );
while ( list ( $config_name, $config_value ) = each ( $link_config_data ) )
{
	$sql = "INSERT INTO " . $prefix . "link_config (`config_name`, `config_value`) VALUES ('" . $config_name . "', '" . $config_value . "')";
	_sql($sql, $errored, $error_ary);
}

// Count links per category
$sql = 'SELECT `cat_id` FROM ' . $prefix . 'link_categories ORDER BY cat_order';
$result = _sql($sql, $errored, $error_ary);
if ( $row = $db->sql_fetchrow($result) )
{
	do
	{
		$sql2 = 'SELECT `link_catid` FROM ' . $prefix . 'links WHERE `link_approved` = 1 AND `link_catid` = ' . $row['cat_id'];
		$result2 = _sql($sql2, $errored, $error_ary);
					
		$links = $db->sql_fetchrowset($result2);
		$total_links = sizeof($links);

		$sql3 = 'UPDATE ' . $prefix . 'link_categories SET `cat_links` = ' . $total_links . ' WHERE `cat_id` = ' . $row['cat_id'];
		_sql($sql3, $errored, $error_ary);
	}
	while ( $row = $db->sql_fetchrow($result) );
}
$db->sql_freeresult($result);		

// phpbb_link_categories data
$sql = 'UPDATE ' . $prefix . 'link_categories SET `cat_parent` = "0"';
_sql($sql, $errored, $error_ary);


//
// Change default values to sync with FullyModded setup
//
$sql = 'UPDATE ' . $prefix . 'attachments_config SET `config_value` = "2.4.5" WHERE `config_name` = "attach_version"';
_sql($sql, $errored, $error_ary);

$sql = 'UPDATE ' . $prefix . 'bank SET `fees` = "1"';
_sql($sql, $errored, $error_ary);

$sql = 'UPDATE ' . $prefix . 'link_config SET `config_name` = "settings_link_page" WHERE `config_name` = "linkspp"';
_sql($sql, $errored, $error_ary);

?>