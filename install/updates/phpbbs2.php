<?php

if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}

$sql[] = 'DROP TABLE IF EXISTS ' . $table_prefix . 'account_hist';
$sql[] = 'CREATE TABLE ' . $table_prefix . 'account_hist (`user_id` mediumint(8) DEFAULT "0", `lw_post_id` mediumint(8) DEFAULT "0", `lw_money` float DEFAULT "0", `lw_plus_minus` smallint(5) DEFAULT "0", `MNY_CURRENCY` varchar(8) DEFAULT "", `lw_date` int(11) DEFAULT "0", `comment` varchar(255) DEFAULT "", `status` varchar(64) DEFAULT "", `txn_id` varchar(64) DEFAULT "", `lw_site` varchar(10) DEFAULT "") TYPE=MyISAM';

$sql[] = 'DROP TABLE IF EXISTS ' . $table_prefix . 'advance_html';
$sql[] = 'CREATE TABLE ' . $table_prefix . 'advance_html (`config_name` VARCHAR(255) DEFAULT "" NOT NULL, `config_value` LONGTEXT, PRIMARY KEY (config_name))';

$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'album';
$sql[] = 'CREATE TABLE '. $table_prefix .'album (pic_id int(11) UNSIGNED NOT NULL auto_increment, pic_filename VARCHAR(255) NOT NULL, pic_thumbnail VARCHAR(255), pic_title VARCHAR(255) NOT NULL, pic_user_id MEDIUMINT(8) NOT NULL, pic_user_ip char(8) DEFAULT "0" NOT NULL, pic_time int(11) UNSIGNED NOT NULL, pic_cat_id MEDIUMINT(8) UNSIGNED DEFAULT "1" NOT NULL, pic_view_count int(11) UNSIGNED DEFAULT "0" NOT NULL, pic_lock tinyint(3) DEFAULT "0" NOT NULL, pic_username VARCHAR(32), pic_approval tinyint(3) DEFAULT "1" NOT NULL, pic_desc text, PRIMARY KEY (pic_id), KEY pic_time(pic_time), KEY pic_cat_id(pic_cat_id), KEY pic_user_id(pic_user_id)) TYPE=MYISAM';
	
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'album_cat';
$sql[] = 'CREATE TABLE '. $table_prefix .'album_cat (cat_id MEDIUMINT(8) UNSIGNED NOT NULL auto_increment, cat_title VARCHAR(255) NOT NULL, cat_desc TEXT, cat_order MEDIUMINT(8) NOT NULL, cat_view_level tinyint(3) DEFAULT "-1" NOT NULL, cat_upload_level tinyint(3) DEFAULT "0" NOT NULL, cat_rate_level tinyint(3) DEFAULT "0" NOT NULL, cat_comment_level tinyint(3) DEFAULT "0" NOT NULL, cat_edit_level tinyint(3) DEFAULT "0" NOT NULL, cat_delete_level tinyint(3) DEFAULT "2" NOT NULL, cat_moderator_groups VARCHAR(255), cat_approval tinyint(3) DEFAULT "0" NOT NULL, cat_view_groups VARCHAR(255), cat_upload_groups VARCHAR(255), cat_rate_groups VARCHAR(255), cat_comment_groups VARCHAR(255), cat_edit_groups VARCHAR(255), cat_delete_groups VARCHAR(255), PRIMARY KEY (cat_id), KEY cat_order(cat_order)) TYPE=MYISAM';
	
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'album_comment';
$sql[] = 'CREATE TABLE '. $table_prefix .'album_comment (comment_id int(11) UNSIGNED NOT NULL auto_increment, comment_pic_id int(11) UNSIGNED NOT NULL, comment_cat_id INT(11) NOT NULL, comment_user_id MEDIUMINT(8) NOT NULL, comment_user_ip char(8) NOT NULL, comment_time int(11) UNSIGNED NOT NULL, comment_text TEXT, comment_edit_time int(11) UNSIGNED, comment_edit_count smallint(5) UNSIGNED DEFAULT "0" NOT NULL, comment_edit_user_id MEDIUMINT(8), comment_username VARCHAR(32), PRIMARY KEY (comment_id), KEY comment_pic_id(comment_pic_id), KEY comment_user_id(comment_user_id), KEY comment_user_ip(comment_user_ip), KEY comment_time(comment_time)) TYPE=MYISAM';

$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'album_config';
$sql[] = 'CREATE TABLE '. $table_prefix .'album_config (config_name VARCHAR(255) NOT NULL, config_value VARCHAR(255) NOT NULL, PRIMARY KEY (config_name)) TYPE=MYISAM';
	
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'album_rate';
$sql[] = 'CREATE TABLE '. $table_prefix .'album_rate (rate_pic_id int(11) UNSIGNED NOT NULL, rate_user_id MEDIUMINT(8) NOT NULL, rate_user_ip char(8) NOT NULL, rate_point tinyint(3) UNSIGNED NOT NULL, rate_hon_point TINYINT(3) DEFAULT "0" NOT NULL, KEY rate_pic_id (rate_pic_id), KEY rate_user_ip(rate_user_ip), KEY rate_point(rate_point)) TYPE=MYISAM';

$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'attach_quota';
$sql[] = 'CREATE TABLE '. $table_prefix .'attach_quota (user_id MEDIUMINT(8) UNSIGNED DEFAULT "0" NOT NULL, group_id MEDIUMINT(8) UNSIGNED DEFAULT "0" NOT NULL, quota_type SMALLINT(2) DEFAULT "0" NOT NULL, quota_limit_id MEDIUMINT(8) UNSIGNED DEFAULT "0" NOT NULL, KEY quota_type (quota_type)) TYPE=MYISAM';

$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'attachments';
$sql[] = 'CREATE TABLE '. $table_prefix .'attachments (attach_id MEDIUMINT(8) UNSIGNED DEFAULT "0" NOT NULL, post_id MEDIUMINT(8) UNSIGNED DEFAULT "0" NOT NULL, privmsgs_id MEDIUMINT(8) UNSIGNED DEFAULT "0" NOT NULL, user_id_1 MEDIUMINT(8) NOT NULL, user_id_2 MEDIUMINT(8) NOT NULL, KEY attach_id_post_id (attach_id, post_id), KEY attach_id_privmsgs_id (attach_id, privmsgs_id), KEY post_id (post_id), KEY privmsgs_id (privmsgs_id)) TYPE=MYISAM';
	
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'attachments_config';
$sql[] = 'CREATE TABLE '. $table_prefix .'attachments_config (config_name VARCHAR(255) NOT NULL, config_value VARCHAR(255) NOT NULL, PRIMARY KEY (config_name)) TYPE=MYISAM';
	
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'attachments_desc';
$sql[] = 'CREATE TABLE '. $table_prefix .'attachments_desc (attach_id MEDIUMINT(8) UNSIGNED NOT NULL auto_increment, physical_filename VARCHAR(255) NOT NULL, real_filename VARCHAR(255) NOT NULL, download_count MEDIUMINT(8) UNSIGNED DEFAULT "0" NOT NULL, comment VARCHAR(255), extension VARCHAR(100), mimetype VARCHAR(100), filesize INT(20) NOT NULL, filetime INT(11) DEFAULT "0" NOT NULL, thumbnail TINYINT(1) DEFAULT "0" NOT NULL, width smallint(5) UNSIGNED DEFAULT "0" NOT NULL, height smallint(5) UNSIGNED DEFAULT "0" NOT NULL, border tinyint(1) DEFAULT "0" NOT NULL, PRIMARY KEY (attach_id), KEY filetime (filetime), KEY physical_filename(physical_filename(10)), KEY filesize(filesize), INDEX (filetime), INDEX (physical_filename(10)), INDEX (filesize)) TYPE=MYISAM';

$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'avatartoplist';
$sql[] = 'CREATE TABLE ' . $table_prefix . 'avatartoplist (`avatar_filename` TEXT NOT NULL, `avatar_type` tinyint(4) NOT NULL default "0", `voter_id` mediumint(8) NOT NULL, `voting` mediumint(8) NOT NULL, `comment` text NOT NULL, INDEX `voter_id` (`voter_id`)) TYPE=MyISAM';

$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'backup';
$sql[] = 'CREATE TABLE '. $table_prefix .'backup (`backup_skill` int(1) NOT NULL, `email_true` int(1) NOT NULL, `email` text NOT NULL, `ftp_true` int(1) NOT NULL, `ftp_server` text NOT NULL, `ftp_user_name` text NOT NULL, `ftp_user_pass` text NOT NULL, `ftp_directory` text NOT NULL, `write_backups_true` int(1) NOT NULL, `files_to_keep` varchar(255) NOT NULL, `cron_time` text NOT NULL, `delay_time` text NOT NULL, `backup_type` text NOT NULL, `phpbb_only` int(1) NOT NULL, `no_search` int(1) NOT NULL, `ignore_tables` text NOT NULL, `last_run` int(11) NOT NULL,  `finished` int(1) NOT NULL) TYPE=MYISAM';

$sql[] = 'DROP TABLE IF EXISTS ' . $table_prefix . 'banned_sites';
$sql[] = 'CREATE TABLE ' . $table_prefix . 'banned_sites (`site_id` INT(15) NOT NULL AUTO_INCREMENT, `site_url` VARCHAR(150) NOT NULL, `reason` VARCHAR(15) NOT NULL, INDEX (`site_id`)) TYPE=MyISAM';

$sql[] = 'DROP TABLE IF EXISTS ' . $table_prefix . 'banned_visitors';
$sql[] = 'CREATE TABLE ' . $table_prefix . 'banned_visitors (`count` INT(15) NOT NULL AUTO_INCREMENT, `refer` VARCHAR(150) NOT NULL, `ip` VARCHAR(255) NOT NULL, `ip_owner` VARCHAR(100) NOT NULL, `browser` VARCHAR(150) NOT NULL, `user_id` MEDIUMINT(8) NOT NULL, `user` VARCHAR(50) NOT NULL, INDEX (`count`)) TYPE=MyISAM';

$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'banner';
$sql[] = 'CREATE TABLE '. $table_prefix .'banner (banner_id MEDIUMINT(8) UNSIGNED NOT NULL, banner_name TEXT NOT NULL, banner_spot smallint(1) UNSIGNED NOT NULL, banner_forum MEDIUMINT(8) UNSIGNED NOT NULL, banner_description VARCHAR(255) NOT NULL, banner_url VARCHAR(255) NOT NULL, banner_owner MEDIUMINT(8) NOT NULL, banner_click MEDIUMINT(8) UNSIGNED NOT NULL, banner_view MEDIUMINT(8) UNSIGNED NOT NULL, banner_weigth tinyint(1) UNSIGNED DEFAULT "50" NOT NULL, banner_active tinyint(1) NOT NULL, banner_timetype tinyint(1) NOT NULL, time_begin int(11) NOT NULL, time_end int(11) NOT NULL, date_begin int(11) NOT NULL, date_end int(11) NOT NULL, banner_level TINYINT(1) NOT NULL, banner_level_type TINYINT(1) NOT NULL, banner_comment VARCHAR(100) NOT NULL, banner_type MEDIUMINT(5) NOT NULL, banner_width VARCHAR(5) DEFAULT "0" NOT NULL, banner_height VARCHAR(5) DEFAULT "0" NOT NULL, banner_filter TINYINT(1) NOT NULL, banner_filter_time MEDIUMINT(5) DEFAULT "600" NOT NULL, PRIMARY KEY (banner_id), KEY `banner_active` (`banner_active`), KEY `banner_level` (`banner_level`), KEY `banner_timetype` (`banner_timetype`)) TYPE=MYISAM';
	
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'banner_stats';
$sql[] = 'CREATE TABLE '. $table_prefix .'banner_stats (banner_id MEDIUMINT(8) UNSIGNED NOT NULL, click_date INT(11) NOT NULL, click_ip CHAR(8) NOT NULL, click_user MEDIUMINT(8) NOT NULL, user_duration INT(11) NOT NULL)';
	
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'bank';
$sql[] = 'CREATE TABLE '. $table_prefix .'bank (`user_id` MEDIUMINT(8) NOT NULL, `holding` bigint(20) UNSIGNED NOT NULL DEFAULT "0", `totalwithdrew` bigint(20) UNSIGNED NOT NULL DEFAULT "0", `totaldeposit` bigint(20) UNSIGNED NOT NULL DEFAULT "0", `opentime` int(11) NOT NULL DEFAULT "0", `fees` TINYINT(1) DEFAULT "1" NOT NULL, PRIMARY KEY (`user_id`)) TYPE=MYISAM';
	
$sql[] = 'DROP TABLE IF EXISTS ' . $table_prefix . 'banvote_voters';
$sql[] = 'CREATE TABLE ' . $table_prefix . 'banvote_voters (`banvote_user_id` MEDIUMINT(8) DEFAULT "0" NOT NULL, `banvote_banner_id` MEDIUMINT(8) DEFAULT "0" NOT NULL)';

$sql[] = 'DROP TABLE IF EXISTS ' . $table_prefix . 'bookie_admin_bets';
$sql[] = 'CREATE TABLE ' . $table_prefix . 'bookie_admin_bets (`bet_id` INT(11) NOT NULL auto_increment, `bet_cat` INT(3) DEFAULT "1" NOT NULL, `bet_time` INT(11) DEFAULT "0" NOT NULL, `bet_selection` VARCHAR(100) DEFAULT "" NOT NULL, `bet_meeting` VARCHAR(50) DEFAULT "" NOT NULL, `odds_1` INT(11) DEFAULT "0" NOT NULL, `odds_2` INT(11) DEFAULT "0" NOT NULL, `checked` INT(2) DEFAULT "0" NOT NULL, `multi` INT(11) DEFAULT "-1" NOT NULL, `starbet` INT(2) DEFAULT "0" NOT NULL, `each_way` INT(2) DEFAULT "0" NOT NULL, KEY bet_id (bet_id)) TYPE=MYISAM';

$sql[] = 'DROP TABLE IF EXISTS ' . $table_prefix . 'bookie_bet_setter';
$sql[] = 'CREATE TABLE ' . $table_prefix . 'bookie_bet_setter (`setter` INT(11) NOT NULL DEFAULT "0", `bet_id` INT(11) DEFAULT "0" NOT NULL, `commission` INT(11) DEFAULT "0" NOT NULL, `paid` INT(2) DEFAULT "0" NOT NULL) TYPE=MYISAM';
	
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'bookie_bets';
$sql[] = 'CREATE TABLE '. $table_prefix .'bookie_bets (`bet_id` int(5) NOT NULL auto_increment, `bet_cat` INT(3) NOT NULL DEFAULT "1", `user_id` int(11) NOT NULL DEFAULT "0", `time` int(11) NOT NULL DEFAULT "0", `meeting` VARCHAR(50) NOT NULL DEFAULT "", `selection` VARCHAR(100) NOT NULL DEFAULT "", `bet` int(11) NOT NULL DEFAULT "0", `win_lose` int(11) NOT NULL DEFAULT "0", `odds_1` int(8) NOT NULL DEFAULT "0", `odds_2` int(8) NOT NULL DEFAULT "0", `checked` int(2) NOT NULL DEFAULT "0", `edit_time` int(11) NOT NULL DEFAULT "0", `orig_time` int(11) NOT NULL DEFAULT "0", `admin_betid` INT(11) DEFAULT "0" NOT NULL, `each_way` INT(2) DEFAULT "0" NOT NULL, `bet_result` INT(2) NOT NULL DEFAULT "0", PRIMARY KEY (bet_id)) TYPE=MYISAM';

$sql[] = 'DROP TABLE IF EXISTS ' . $table_prefix . 'bookie_categories';
$sql[] = 'CREATE TABLE ' . $table_prefix . 'bookie_categories (`cat_id` INT(11) NOT NULL auto_increment, `cat_name` VARCHAR(30) DEFAULT "" NOT NULL, PRIMARY KEY (cat_id)) TYPE=MYISAM';

$sql[] = 'DROP TABLE IF EXISTS ' . $table_prefix . 'bookie_meetings';
$sql[] = 'CREATE TABLE ' . $table_prefix . 'bookie_meetings (`meeting_id` INT(5) NOT NULL auto_increment, `meeting` VARCHAR(50) DEFAULT "" NOT NULL, PRIMARY KEY (meeting_id)) TYPE=MYISAM';

$sql[] = 'DROP TABLE IF EXISTS ' . $table_prefix . 'bookie_selections';
$sql[] = 'CREATE TABLE ' . $table_prefix . 'bookie_selections (`selection_id` INT(11) NOT NULL DEFAULT "0", `selection_name` VARCHAR(100) DEFAULT "" NOT NULL, PRIMARY KEY (selection_id)) TYPE=MYISAM';

$sql[] = 'DROP TABLE IF EXISTS ' . $table_prefix . 'bookie_selections_data';
$sql[] = 'CREATE TABLE ' . $table_prefix . 'bookie_selections_data (`selection_id` INT(11) NOT NULL DEFAULT "0", `selection` VARCHAR(100) DEFAULT "" NOT NULL) TYPE=MYISAM';
	
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'bookie_stats';
$sql[] = 'CREATE TABLE ' . $table_prefix . 'bookie_stats (`user_id` int(11) NOT NULL DEFAULT "0", `total_win` int(11) NOT NULL DEFAULT "0", `total_lose` int(11) NOT NULL DEFAULT "0", `netpos` int(11) NOT NULL DEFAULT "0", `bets_placed` int(6) NOT NULL DEFAULT "0") TYPE=MYISAM';

$sql[] = 'DROP TABLE IF EXISTS ' . $table_prefix . 'bots';
$sql[] = 'CREATE TABLE ' . $table_prefix . 'bots (`bot_id` mediumint(8) UNSIGNED NOT NULL auto_increment, `bot_name` varchar(255) DEFAULT "" NOT NULL, `bot_agent` varchar(255) DEFAULT "" NOT NULL, `last_visit` varchar(255) NOT NULL, `bot_visits` mediumint(8) NOT NULL, `bot_pages` mediumint(8) NOT NULL, `pending_agent` text NOT NULL, `pending_ip` text NOT NULL, `bot_ip` text NOT NULL, `bot_style` mediumint(8) NOT NULL, PRIMARY KEY (`bot_id`)) TYPE=MyISAM';

$sql[] = 'DROP TABLE IF EXISTS ' . $table_prefix . 'bots_archive';
$sql[] = 'CREATE TABLE ' . $table_prefix . 'bots_archive (`bot_id` mediumint(8) NOT NULL auto_increment, `bot_name` VARCHAR(255), `bot_time` int(11) NOT NULL default "0", `bot_url` varchar(255) NOT NULL default "", PRIMARY KEY (`bot_id`)) TYPE=MyISAM';

$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'cat_rel_cat_parents';
$sql[] = 'CREATE TABLE '. $table_prefix .'cat_rel_cat_parents (cat_id MEDIUMINT(8) UNSIGNED DEFAULT "0" NOT NULL, parent_cat_id MEDIUMINT(8) UNSIGNED DEFAULT "0" NOT NULL, PRIMARY KEY (cat_id,parent_cat_id)) TYPE=MYISAM';
	
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'cat_rel_forum_parents';
$sql[] = 'CREATE TABLE '. $table_prefix .'cat_rel_forum_parents (cat_id MEDIUMINT(8) UNSIGNED DEFAULT "0" NOT NULL, parent_forum_id SMALLINT(5) UNSIGNED DEFAULT "0" NOT NULL, PRIMARY KEY (cat_id,parent_forum_id)) TYPE=MYISAM';

$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'charts';
$sql[] = 'CREATE TABLE '. $table_prefix .'charts (chart_id MEDIUMINT(8) NOT NULL auto_increment, chart_song_name VARCHAR(100) NOT NULL, chart_artist VARCHAR(100) NOT NULL, chart_album VARCHAR(100) DEFAULT "", chart_label VARCHAR(100) NOT NULL, chart_catno VARCHAR(50) NOT NULL, chart_website VARCHAR(100) NOT NULL, chart_poster_id VARCHAR(100) NOT NULL, chart_hot MEDIUMINT(8) DEFAULT "0", chart_not MEDIUMINT(8) DEFAULT "0", chart_curr_pos MEDIUMINT(8) DEFAULT "0", chart_last_pos MEDIUMINT(8) DEFAULT "0", chart_best_pos MEDIUMINT(8) DEFAULT "0", PRIMARY KEY (chart_id)) TYPE=MYISAM';
	
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'charts_voters';
$sql[] = 'CREATE TABLE '. $table_prefix .'charts_voters (vote_id MEDIUMINT(8) NOT NULL auto_increment, vote_user_id MEDIUMINT(8) NOT NULL, vote_chart_id MEDIUMINT(8) NOT NULL, vote_rate SMALLINT(2) NOT NULL, PRIMARY KEY (vote_id), FOREIGN KEY (vote_chart_id) REFERENCES '. $table_prefix .'charts (chart_id)) TYPE=MYISAM';

$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'chatbox';
$sql[] = 'CREATE TABLE '. $table_prefix .'chatbox (id INT(11) NOT NULL auto_increment, name VARCHAR(99) NOT NULL, msg VARCHAR(255) NOT NULL, timestamp INT(10) UNSIGNED NOT NULL, PRIMARY KEY (id)) TYPE=MYISAM';
	
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'chatbox_session';
$sql[] = 'CREATE TABLE '. $table_prefix .'chatbox_session (username VARCHAR(99) NOT NULL, lastactive INT(10) DEFAULT "0" NOT NULL, laststatus VARCHAR(8) NOT NULL, UNIQUE username (username)) TYPE=MYISAM';

$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'config_nav';
$sql[] = 'CREATE TABLE '. $table_prefix .'config_nav (navlink_id MEDIUMINT(8) UNSIGNED NOT NULL auto_increment, img VARCHAR(100) DEFAULT "" NOT NULL, alt VARCHAR(100) DEFAULT "" NOT NULL, use_lang TINYINT(1) DEFAULT "1" NOT NULL, url VARCHAR(255) DEFAULT "" NOT NULL, nav_order MEDIUMINT(8) UNSIGNED DEFAULT "1" NOT NULL, value TINYINT(1) DEFAULT "0" NOT NULL, PRIMARY KEY (navlink_id)) TYPE=MYISAM';

$sql[] = 'DROP TABLE IF EXISTS ' . $table_prefix . 'digests';
$sql[] = 'CREATE TABLE ' . $table_prefix . 'digests (digest_id int(6) NOT NULL auto_increment, digest_name varchar(25) NULL DEFAULT "", user_id mediumint(8) NOT NULL DEFAULT "0", digest_type tinyint(1) NOT NULL DEFAULT "0", digest_activity tinyint(1) NOT NULL DEFAULT "1", digest_frequency mediumint(8) NOT NULL DEFAULT "0", last_digest int(11) NOT NULL DEFAULT "0", digest_format smallint(4) NOT NULL DEFAULT "0", digest_show_text smallint(4) NOT NULL DEFAULT "0", digest_show_mine smallint(4) NOT NULL DEFAULT "0", digest_new_only smallint(4) NOT NULL DEFAULT "0", digest_send_on_no_messages smallint(4) NOT NULL DEFAULT "1", digest_moderator tinyint(1) NOT NULL DEFAULT "0", digest_include_forum tinyint(1) NOT NULL DEFAULT "1", PRIMARY KEY  (digest_id), KEY user_id (user_id)) TYPE=MyISAM';

$sql[] = 'DROP TABLE IF EXISTS ' . $table_prefix . 'digests_config';
$sql[] = 'CREATE TABLE ' . $table_prefix . 'digests_config (config_name varchar(255) NOT NULL DEFAULT "", config_value varchar(255) NOT NULL DEFAULT "0", PRIMARY KEY  (config_name)) TYPE=MyISAM';

$sql[] = 'DROP TABLE IF EXISTS ' . $table_prefix . 'digests_forums';
$sql[] = 'CREATE TABLE ' . $table_prefix . 'digests_forums (user_id mediumint(8) NOT NULL DEFAULT "0", forum_id smallint(5) NOT NULL DEFAULT "0", digest_id int(11) NOT NULL DEFAULT "0", KEY user_id (user_id), KEY forum_id (forum_id)) TYPE=MyISAM';

$sql[] = 'DROP TABLE IF EXISTS ' . $table_prefix . 'digests_log';
$sql[] = 'CREATE TABLE ' . $table_prefix . 'digests_log (log_time int(11) NOT NULL DEFAULT "0", run_type tinyint(1) NOT NULL DEFAULT "0", user_id mediumint(8) NOT NULL DEFAULT "0", digest_frequency mediumint(8) NOT NULL DEFAULT "0", digest_type tinyint(1) NOT NULL DEFAULT "0", group_id mediumint(8) NOT NULL DEFAULT "1", log_status mediumint(2) NOT NULL DEFAULT "0", log_posts int(11) NULL DEFAULT "0", KEY user_id (user_id)) TYPE=MyISAM';

$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'extension_groups';
$sql[] = 'CREATE TABLE '. $table_prefix .'extension_groups (group_id MEDIUMINT(8) NOT NULL auto_increment, group_name CHAR(20) NOT NULL, cat_id TINYINT(2) DEFAULT "0" NOT NULL, allow_group TINYINT(1) DEFAULT "0" NOT NULL, download_mode TINYINT(1) UNSIGNED DEFAULT "1" NOT NULL, upload_icon VARCHAR(100) DEFAULT "", max_filesize INT(20) DEFAULT "0" NOT NULL, forum_permissions VARCHAR(255) DEFAULT "" NOT NULL, PRIMARY KEY group_id (group_id)) TYPE=MYISAM';
	
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'extensions';
$sql[] = 'CREATE TABLE '. $table_prefix .'extensions (ext_id MEDIUMINT(8) UNSIGNED NOT NULL auto_increment, group_id MEDIUMINT(8) UNSIGNED DEFAULT "0" NOT NULL, extension VARCHAR(100) NOT NULL, comment VARCHAR(100), PRIMARY KEY ext_id (ext_id)) TYPE=MYISAM';

$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'flags';
$sql[] = 'CREATE TABLE '. $table_prefix .'flags (flag_id INT(10) NOT NULL auto_increment, flag_name VARCHAR(255), flag_image VARCHAR(255), PRIMARY KEY (flag_id)) TYPE=MYISAM';

$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'forbidden_extensions';
$sql[] = 'CREATE TABLE '. $table_prefix .'forbidden_extensions (ext_id MEDIUMINT(8) UNSIGNED NOT NULL auto_increment, extension VARCHAR(100) NOT NULL, PRIMARY KEY (ext_id)) TYPE=MYISAM';

$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'forum_move';
$sql[] = 'CREATE TABLE '. $table_prefix .'forum_move (`move_id` MEDIUMINT(8) UNSIGNED NOT NULL auto_increment, `forum_id` SMALLINT(5) UNSIGNED DEFAULT "0" NOT NULL, `forum_dest` SMALLINT(5) UNSIGNED DEFAULT "0" NOT NULL, `move_days` smallint(5) UNSIGNED NOT NULL DEFAULT "0", `move_freq` smallint(5) UNSIGNED NOT NULL DEFAULT "0", PRIMARY KEY (move_id), KEY (forum_id)) TYPE=MYISAM';

$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'forum_tour';
$sql[] = 'CREATE TABLE '. $table_prefix .'forum_tour (page_id MEDIUMINT(8) UNSIGNED NOT NULL, page_subject VARCHAR(60) null, page_text text, page_sort MEDIUMINT(8) NOT NULL, bbcode_uid CHAR(10) DEFAULT "" NOT NULL, page_access MEDIUMINT(8) NOT NULL, KEY (page_id)) TYPE=MYISAM';

$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'forums_descrip';
$sql[] = 'CREATE TABLE '. $table_prefix .'forums_descrip (`forum_id` INT(11) DEFAULT "0", `user_id` INT(11) DEFAULT "0", `view` TINYINT(4) DEFAULT "1")';
	
$sql[] = 'DROP TABLE IF EXISTS ' . $table_prefix . 'forums_watch';
$sql[] = 'CREATE TABLE ' . $table_prefix . 'forums_watch (`forum_id` MEDIUMINT(8) UNSIGNED DEFAULT "0" NOT NULL, `user_id` MEDIUMINT(8) DEFAULT "0" NOT NULL, `notify_status` TINYINT(1) DEFAULT "0" NOT NULL, KEY forum_id (forum_id), KEY user_id (user_id), KEY notify_status (notify_status)) TYPE=MYISAM';
	
$sql[] = 'DROP TABLE IF EXISTS ' . $table_prefix . 'guestbook';
$sql[] = 'CREATE TABLE ' . $table_prefix . 'guestbook (`id` mediumint(8) unsigned NOT NULL auto_increment, `user_id` mediumint(8) NOT NULL default "0", `nick` varchar(25) NOT NULL default "", `data_ora` int(11) NOT NULL default "0", `email` varchar(255) default NULL, `sito` varchar(100) default NULL, `comento` text, `bbcode_uid` varchar(10) default NULL, `ipi` varchar(8) NOT NULL default "", `agent` varchar(255) NOT NULL default "", `hide` tinyint(1) default "0", PRIMARY KEY (`id`)) TYPE=MyISAM';

$sql[] = 'DROP TABLE IF EXISTS ' . $table_prefix . 'guestbook_config';
$sql[] = 'CREATE TABLE ' . $table_prefix . 'guestbook_config (`config_name` varchar(255) NOT NULL default "", `config_value` varchar(255) NOT NULL default "", PRIMARY KEY (`config_name`)) TYPE=MyISAM';

$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'helpdesk_emails';
$sql[] = 'CREATE TABLE '. $table_prefix .'helpdesk_emails (e_id int(10) DEFAULT "0" NOT NULL, e_addr VARCHAR(255) DEFAULT NULL)';
		
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'helpdesk_importance';
$sql[] = 'CREATE TABLE '. $table_prefix .'helpdesk_importance (value int(10) DEFAULT "0", data text)';
	
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'helpdesk_msgs';
$sql[] = 'CREATE TABLE '. $table_prefix .'helpdesk_msgs (e_id int(10) DEFAULT "0", e_msg VARCHAR(255) DEFAULT NULL)';
	
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'helpdesk_reply';
$sql[] = 'CREATE TABLE '. $table_prefix .'helpdesk_reply (value int(10) DEFAULT "0", data text)';
	
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'ina_ban';
$sql[] = 'CREATE TABLE '. $table_prefix .'ina_ban (id int(10) DEFAULT "0" NOT NULL, username VARCHAR(40) DEFAULT NULL)';

$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'ina_categories';
$sql[] = 'CREATE TABLE '. $table_prefix .'ina_categories (cat_id MEDIUMINT(9) NOT NULL auto_increment, cat_name VARCHAR(25) DEFAULT NULL, cat_desc text NOT NULL, cat_img VARCHAR(255) DEFAULT "" NOT NULL, PRIMARY KEY (cat_id)) TYPE=MYISAM';
	
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'ina_challenge_tracker';
$sql[] = 'CREATE TABLE '. $table_prefix .'ina_challenge_tracker (user int(10) DEFAULT "0", count int(25) DEFAULT "0")';
	
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'ina_challenge_users';
$sql[] = 'CREATE TABLE '. $table_prefix .'ina_challenge_users (user_to int(10) DEFAULT "0", user_from int(10) DEFAULT "0", count int(25) DEFAULT "0")';
	
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'ina_chat';
$sql[] = 'CREATE TABLE '. $table_prefix .'ina_chat (`chat_date` DATE NOT NULL DEFAULT "0000-00-00", `chat_text` TEXT NOT NULL)';

$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'ina_cheat_fix';
$sql[] = 'CREATE TABLE '. $table_prefix .'ina_cheat_fix (`game_id` int(10) DEFAULT "0" NOT NULL, `player` int(10) DEFAULT "0") TYPE=MYISAM';
	
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'ina_data';
$sql[] = 'CREATE TABLE '. $table_prefix .'ina_data (version VARCHAR(255) DEFAULT NULL)';
	
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'ina_favorites';
$sql[] = 'CREATE TABLE '. $table_prefix .'ina_favorites (user int(10) DEFAULT "0" NOT NULL, games text)';
	
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'ina_gamble';
$sql[] = 'CREATE TABLE '. $table_prefix .'ina_gamble (game_id int(20) DEFAULT "0", sender_id int(11) DEFAULT "0", reciever_id int(11) DEFAULT "0", amount int(10) DEFAULT "0", winner_id int(11) DEFAULT "0", loser_id int(11) DEFAULT "0", winner_score int(11) DEFAULT "0", loser_score int(11) DEFAULT "0", date int(20) DEFAULT NULL, been_paid int(11) DEFAULT "0")';
	
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'ina_gamble_in_progress';
$sql[] = 'CREATE TABLE '. $table_prefix .'ina_gamble_in_progress (game_id int(20) DEFAULT "0", sender_id int(11) DEFAULT "0", reciever_id int(11) DEFAULT "0", sender_score int(20) DEFAULT "0", reciever_score int(20) DEFAULT "0", sender_playing int(1) DEFAULT "0" NOT NULL, reciever_playing int(1) DEFAULT "0" NOT NULL)';
	
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'ina_games';
$sql[] = 'CREATE TABLE '. $table_prefix .'ina_games (game_id MEDIUMINT(9) NOT NULL auto_increment, game_name VARCHAR(25) DEFAULT NULL, game_path VARCHAR(255) DEFAULT NULL, game_desc VARCHAR(255) DEFAULT NULL, game_charge int(11) UNSIGNED DEFAULT "0", game_reward int(11) UNSIGNED NOT NULL DEFAULT "0", game_bonus smallint(5) UNSIGNED DEFAULT "0", game_use_gl tinyint(3) UNSIGNED DEFAULT "0", game_flash tinyint(1) UNSIGNED NOT NULL DEFAULT "0", game_show_score tinyint(1) NOT NULL DEFAULT "1", win_width smallint(6) NOT NULL DEFAULT "0", win_height smallint(6) NOT NULL DEFAULT "0", highscore_limit VARCHAR(255) DEFAULT NULL, reverse_list tinyint(1) NOT NULL DEFAULT "0", played int(10) UNSIGNED NOT NULL DEFAULT "0", instructions text, disabled int(1) NOT NULL DEFAULT "1", install_date int(20) NOT NULL DEFAULT "0", proper_name text NOT NULL, cat_id int(4) NOT NULL DEFAULT "0", `jackpot` INT(10) NOT NULL DEFAULT "0", `game_popup` SMALLINT(1) NOT NULL DEFAULT "0", `game_parent` SMALLINT(1) NOT NULL DEFAULT "0", `game_type` SMALLINT(1) DEFAULT "1" NOT NULL, `game_links` TEXT NOT NULL, `game_ge_cost` INT(10) DEFAULT "0" NOT NULL, `game_keyboard` SMALLINT(1) DEFAULT "0" NOT NULL, `game_mouse` SMALLINT(1) DEFAULT "0" NOT NULL, PRIMARY KEY (game_id)) TYPE=MYISAM';

$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'ina_hall_of_fame';
$sql[] = 'CREATE TABLE '. $table_prefix .'ina_hall_of_fame (`game_id` MEDIUMINT(8) NOT NULL DEFAULT "0", `current_user_id` MEDIUMINT(8) NOT NULL DEFAULT "0", `current_score` FLOAT(10,2) NOT NULL DEFAULT "0.00", `date_today` INT(10) NOT NULL DEFAULT "0", `old_user_id` MEDIUMINT(8) NOT NULL DEFAULT "0", `old_score` FLOAT(10,2) NOT NULL DEFAULT "0.00", `old_date` INT(10) NOT NULL DEFAULT "0")';
	
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'ina_last_game_played';
$sql[] = 'CREATE TABLE '. $table_prefix .'ina_last_game_played (`game_id` int(20) DEFAULT "0", `user_id` mediumint(8) NOT NULL, `date` int(20) DEFAULT NULL)';
	
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'ina_rating_votes';
$sql[] = 'CREATE TABLE '. $table_prefix .'ina_rating_votes (game_id int(15) NOT NULL DEFAULT "0", rating int(15) NOT NULL DEFAULT "0", date int(15) NOT NULL DEFAULT "0", player int(15) NOT NULL DEFAULT "0")';
	
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'ina_scores';
$sql[] = 'CREATE TABLE '. $table_prefix .'ina_scores (game_name VARCHAR(255) DEFAULT NULL, player VARCHAR(40) DEFAULT NULL, score float(10,2) NOT NULL DEFAULT "0.00", date int(11) DEFAULT NULL, `user_plays` INT(6) DEFAULT "0" NOT NULL, `play_time` INT(10) DEFAULT "0" NOT NULL)';
	
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'ina_sessions';
$sql[] = 'CREATE TABLE '. $table_prefix .'ina_sessions (playing_time int(15) NOT NULL DEFAULT "0", playing_id int(10) NOT NULL DEFAULT "0", playing int(11) NOT NULL DEFAULT "0")';
	
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'ina_top_scores';
$sql[] = 'CREATE TABLE '. $table_prefix .'ina_top_scores (game_name VARCHAR(255) DEFAULT NULL, player MEDIUMINT(8) NOT NULL, score float(10,2) NOT NULL DEFAULT "0.00", date int(11) DEFAULT NULL)';
	
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'ina_trophy_comments';
$sql[] = 'CREATE TABLE '. $table_prefix .'ina_trophy_comments (game VARCHAR(255) NOT NULL DEFAULT "", player int(10) DEFAULT NULL, comment text NOT NULL, date int(15) NOT NULL DEFAULT "0", score int(20) NOT NULL DEFAULT "0")';
	
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'im_buddy_list';
$sql[] = 'CREATE TABLE '. $table_prefix .'im_buddy_list (user_id MEDIUMINT(8) DEFAULT "0" NOT NULL, contact_id MEDIUMINT(8) DEFAULT "0" NOT NULL, user_ignore TINYINT(1) DEFAULT "0" NOT NULL, alert TINYINT(1) DEFAULT "0" NOT NULL, alert_status TINYINT(1) DEFAULT "0" NOT NULL, disallow TINYINT(1) DEFAULT "0" NOT NULL, display_name VARCHAR(25) DEFAULT "" NOT NULL, KEY contact_id (contact_id), KEY user_id (user_id)) TYPE=MYISAM';
	
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'im_config';
$sql[] = 'CREATE TABLE '. $table_prefix .'im_config (config_name VARCHAR(255) DEFAULT "" NOT NULL, config_value VARCHAR(255) DEFAULT "" NOT NULL, PRIMARY KEY (config_name)) TYPE=MYISAM';
	
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'im_prefs';
$sql[] = 'CREATE TABLE '. $table_prefix .'im_prefs (user_id MEDIUMINT(8) DEFAULT "0" NOT NULL, user_allow_ims TINYINT(1) DEFAULT "0" NOT NULL, user_allow_shout TINYINT(1) DEFAULT "1" NOT NULL, user_allow_chat TINYINT(1) DEFAULT "1" NOT NULL, user_allow_network TINYINT(1) DEFAULT "0" NOT NULL, admin_allow_ims TINYINT(1) DEFAULT "1" NOT NULL, admin_allow_shout TINYINT(1) DEFAULT "1" NOT NULL, admin_allow_chat TINYINT(1) DEFAULT "1" NOT NULL, admin_allow_network TINYINT(1) DEFAULT "1" NOT NULL, new_ims SMALLINT(5) UNSIGNED DEFAULT "0" NOT NULL, unread_ims SMALLINT(5) UNSIGNED DEFAULT "0" NOT NULL, read_ims SMALLINT(5) UNSIGNED DEFAULT "0" NOT NULL, total_sent MEDIUMINT(8) UNSIGNED DEFAULT "0" NOT NULL, attach_sig TINYINT(1) DEFAULT "1" NOT NULL, refresh_rate SMALLINT(5) UNSIGNED DEFAULT "120" NOT NULL, success_close TINYINT(1) DEFAULT "1" NOT NULL, refresh_method TINYINT(1) DEFAULT "1" NOT NULL, auto_launch TINYINT(1) DEFAULT "1" NOT NULL, popup_ims TINYINT(1) DEFAULT "1" NOT NULL, list_ims TINYINT(1) DEFAULT "0" NOT NULL, show_controls TINYINT(1) DEFAULT "1" NOT NULL, list_all_online TINYINT(1) DEFAULT "1" NOT NULL, DEFAULT_mode TINYINT(1) DEFAULT "1" NOT NULL, current_mode TINYINT(1) DEFAULT "1" NOT NULL, mode1_height VARCHAR(4) DEFAULT "400" NOT NULL, mode1_width VARCHAR(4) DEFAULT "230" NOT NULL, mode2_height VARCHAR(4) DEFAULT "225" NOT NULL, mode2_width VARCHAR(4) DEFAULT "400" NOT NULL, mode3_height VARCHAR(4) DEFAULT "100" NOT NULL, mode3_width VARCHAR(4) DEFAULT "400" NOT NULL, prefs_width VARCHAR(4) DEFAULT "500" NOT NULL, prefs_height VARCHAR(4) DEFAULT "350" NOT NULL, read_height VARCHAR(4) DEFAULT "3000" NOT NULL, read_width VARCHAR(4) DEFAULT "400" NOT NULL, send_height VARCHAR(4) DEFAULT "365" NOT NULL, send_width VARCHAR(4) DEFAULT "460" NOT NULL, play_sound TINYINT(1) DEFAULT "1" NOT NULL, DEFAULT_sound TINYINT(1) DEFAULT "1" NOT NULL, sound_name VARCHAR(255) DEFAULT NULL, themes_id MEDIUMINT(8) UNSIGNED DEFAULT "1" NOT NULL, network_user_list TINYINT(1) DEFAULT "1" NOT NULL, open_pms TINYINT(1) DEFAULT "0" NOT NULL, auto_delete TINYINT(1) DEFAULT "0" NOT NULL, use_frames TINYINT(1) DEFAULT "1" NOT NULL, user_override TINYINT(1) DEFAULT "0" NOT NULL, PRIMARY KEY (user_id)) TYPE=MYISAM';
	
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'im_sessions';
$sql[] = 'CREATE TABLE '. $table_prefix .'im_sessions (session_user_id MEDIUMINT(8) DEFAULT "0" NOT NULL, session_id CHAR(32) DEFAULT "" NOT NULL, session_time INT(11) DEFAULT "0" NOT NULL, session_popup TINYINT(1) DEFAULT "0" NOT NULL, PRIMARY KEY (session_id), KEY session_user_id (session_user_id)) TYPE=MYISAM';

$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'im_sites';
$sql[] = 'CREATE TABLE '. $table_prefix .'im_sites (site_id MEDIUMINT(8) NOT NULL auto_increment, site_name VARCHAR(50) NOT NULL, site_url VARCHAR(100) NOT NULL, site_phpex VARCHAR(4) DEFAULT "php" NOT NULL, site_profile VARCHAR(50) DEFAULT "profile" NOT NULL, site_enable TINYINT(1) DEFAULT "1" NOT NULL, PRIMARY KEY (site_id)) TYPE=MYISAM';

$sql[] = 'DROP TABLE IF EXISTS ' . $table_prefix . 'inline_ads'; 
$sql[] = 'CREATE TABLE ' . $table_prefix . 'inline_ads (`ad_id` TINYINT(5) NOT NULL auto_increment, `ad_code` TEXT NOT NULL, `ad_name` CHAR(25) NOT NULL, PRIMARY KEY (ad_id)) TYPE=MYISAM';

$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'ip';
$sql[] = 'CREATE TABLE '. $table_prefix .'ip (`id` mediumint(8) NOT NULL auto_increment, `ip` VARCHAR(200) DEFAULT "0" NOT NULL, `host` VARCHAR(200) DEFAULT "0" NOT NULL, `date` INT(11) DEFAULT "0" NOT NULL, `username` VARCHAR(200) DEFAULT "0" NOT NULL, `referrer` VARCHAR(200) DEFAULT "0" NOT NULL, `forum` VARCHAR(200) DEFAULT "0" NOT NULL, `browser` VARCHAR(200) DEFAULT "0" NOT NULL, KEY `username` (`username`), PRIMARY KEY (`id`)) TYPE=MYISAM';

$sql[] = 'DROP TABLE IF EXISTS ' . $table_prefix . 'jobs';
$sql[] = 'CREATE TABLE ' . $table_prefix . 'jobs (`id` mediumint(8) NOT NULL auto_increment, `name` varchar(32) NOT NULL default "", `pay` mediumint(8) default "100", `type` varchar(32) default "public", `requires` text, `payouttime` mediumint(8) default "500000", `positions` mediumint(8) NOT NULL default "0", PRIMARY KEY (`id`), KEY `name` (`name`)) TYPE=MyISAM';

$sql[] = 'DROP TABLE IF EXISTS ' . $table_prefix . 'jobs_employed';
$sql[] = 'CREATE TABLE ' . $table_prefix . 'jobs_employed (`id` mediumint(8) NOT NULL auto_increment, `user_id` mediumint(8) NOT NULL, `job_name` varchar(32) NOT NULL default "", `job_pay` mediumint(8) NOT NULL, `job_length` mediumint(8) NOT NULL, `last_paid` mediumint(8) NOT NULL, `job_started` mediumint(8) NOT NULL, PRIMARY KEY (`id`)) TYPE=MyISAM';
	
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'kb_articles';
$sql[] = 'CREATE TABLE '. $table_prefix .'kb_articles (article_id MEDIUMINT(8) UNSIGNED NOT NULL auto_increment, article_category_id MEDIUMINT(8) UNSIGNED DEFAULT "0" NOT NULL, article_title VARCHAR(255) BINARY DEFAULT "" NOT NULL, article_description VARCHAR(255) BINARY DEFAULT "" NOT NULL, article_date VARCHAR(255) BINARY DEFAULT "" NOT NULL, article_author_id MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT "0", username VARCHAR(255), bbcode_uid CHAR(10) DEFAULT "" NOT NULL, article_body TEXT NOT NULL, article_type MEDIUMINT(8) UNSIGNED DEFAULT "0" NOT NULL, approved TINYINT(1) UNSIGNED DEFAULT "0" NOT NULL, topic_id MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT "0", views BIGINT(8) DEFAULT "0" NOT NULL, KEY article_id (article_id)) TYPE=MYISAM';
	
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'kb_categories';
$sql[] = 'CREATE TABLE '. $table_prefix .'kb_categories (category_id MEDIUMINT(8) UNSIGNED NOT NULL auto_increment, category_name VARCHAR(255) BINARY NOT NULL, category_details VARCHAR(255) BINARY NOT NULL, number_articles MEDIUMINT(8) UNSIGNED NOT NULL, parent MEDIUMINT(8) UNSIGNED NOT NULL, cat_order MEDIUMINT(8) UNSIGNED NOT NULL, KEY category_id (category_id)) TYPE=MYISAM';
	
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'kb_types';
$sql[] = 'CREATE TABLE '. $table_prefix .'kb_types (id INT(4) NOT NULL auto_increment, type VARCHAR(255) DEFAULT "0" NOT NULL, UNIQUE KEY id (id)) TYPE=MYISAM';

$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'kb_results';
$sql[] = 'CREATE TABLE '. $table_prefix .'kb_results (search_id INT(11) UNSIGNED DEFAULT "0" NOT NULL, session_id VARCHAR(32) DEFAULT "" NOT NULL, search_array mediumtext NOT NULL, PRIMARY KEY (search_id), KEY session_id (session_id)) TYPE=MYISAM';

$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'kb_wordlist';
$sql[] = 'CREATE TABLE '. $table_prefix .'kb_wordlist (word_text VARCHAR(50) BINARY DEFAULT "0" NOT NULL, word_id MEDIUMINT(8) UNSIGNED NOT NULL auto_increment, word_common TINYINT(1) UNSIGNED DEFAULT "0" NOT NULL, PRIMARY KEY (word_text), KEY word_id (word_id)) TYPE=MYISAM';

$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'kb_wordmatch';
$sql[] = 'CREATE TABLE '. $table_prefix .'kb_wordmatch (article_id MEDIUMINT(8) UNSIGNED DEFAULT "0" NOT NULL, word_id MEDIUMINT(8) UNSIGNED DEFAULT "0" NOT NULL, title_match TINYINT(1) DEFAULT "0" NOT NULL, KEY post_id (article_id), KEY word_id (word_id)) TYPE=MYISAM';

$sql[] = 'DROP TABLE IF EXISTS ' . $table_prefix . 'lexicon'; 
$sql[] = 'CREATE TABLE ' . $table_prefix . 'lexicon (`id` BIGINT(8) NOT NULL auto_increment, `keyword` VARCHAR(250) NOT NULL DEFAULT "", `explanation` LONGTEXT NOT NULL, `bbcode_uid` CHAR(10) DEFAULT "" NOT NULL, `cat` INT(8) NOT NULL DEFAULT "1", PRIMARY KEY (id), KEY (cat)) TYPE=MYISAM';
			
$sql[] = 'DROP TABLE IF EXISTS ' . $table_prefix . 'lexicon_cat';
$sql[] = 'CREATE TABLE ' . $table_prefix . 'lexicon_cat (`cat_id` INT(8) NOT NULL auto_increment, `cat_titel` VARCHAR(80) NOT NULL DEFAULT "", PRIMARY KEY (cat_id)) TYPE=MYISAM';

$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'links';
$sql[] = 'CREATE TABLE '. $table_prefix .'links (link_id MEDIUMINT(8) UNSIGNED NOT NULL auto_increment, link_name TEXT, link_longdesc TEXT, link_catid MEDIUMINT(8) UNSIGNED DEFAULT "0" NOT NULL, link_url VARCHAR(100) DEFAULT "" NOT NULL, link_logo_src VARCHAR(100) DEFAULT NULL, link_time int(25) DEFAULT "0", link_approved tinyint(1) DEFAULT "0" NOT NULL, link_hits int(10) UNSIGNED DEFAULT "0" NOT NULL, user_id MEDIUMINT(8) DEFAULT "0" NOT NULL, poster_ip VARCHAR(8) NOT NULL, last_user_ip VARCHAR(8) DEFAULT "" NOT NULL, post_username VARCHAR(25) DEFAULT NULL, link_pin INT(2) DEFAULT "0", PRIMARY KEY (link_id)) TYPE=MYISAM';
	
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'link_categories';
$sql[] = 'CREATE TABLE '. $table_prefix .'link_categories (cat_id INT(10) NOT NULL auto_increment, cat_name TEXT DEFAULT NULL, cat_order INT(50), cat_parent INT(50) DEFAULT NULL, parents_data TEXT NOT NULL, cat_links MEDIUMINT(8) NOT NULL DEFAULT "-1", PRIMARY KEY (cat_id), KEY cat_order (cat_order)) TYPE=MYISAM';

$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'link_config';
$sql[] = 'CREATE TABLE '. $table_prefix .'link_config (`config_name` VARCHAR(255) DEFAULT "" NOT NULL, `config_value` VARCHAR(255) DEFAULT "" NOT NULL, PRIMARY KEY(`config_name`)) TYPE=MYISAM';

$sql[] = 'DROP TABLE IF EXISTS ' . $table_prefix . 'link_comments';
$sql[] = 'CREATE TABLE ' . $table_prefix . 'link_comments (comments_id int(10) NOT NULL auto_increment, link_id int(10) NOT NULL default "0", comments_text text NOT NULL, comments_title text NOT NULL, comments_time int(50) NOT NULL default "0", comment_bbcode_uid varchar(10) default NULL, poster_id mediumint(8) NOT NULL default "0", PRIMARY KEY (comments_id), FULLTEXT KEY comment_bbcode_uid (comment_bbcode_uid)) TYPE=MyISAM';

$sql[] = 'DROP TABLE IF EXISTS ' . $table_prefix . 'link_custom';
$sql[] = 'CREATE TABLE ' . $table_prefix . 'link_custom (custom_id int(50) NOT NULL auto_increment, custom_name text NOT NULL, custom_description text NOT NULL, data text NOT NULL, field_order int(20) NOT NULL default "0", field_type tinyint(2) NOT NULL default "0", regex varchar(255) NOT NULL default "", PRIMARY KEY  (custom_id)) TYPE=MyISAM';
			
$sql[] = 'DROP TABLE IF EXISTS ' . $table_prefix . 'link_customdata';
$sql[] = 'CREATE TABLE ' . $table_prefix . 'link_customdata (`customdata_file` int(50) NOT NULL default "0", `customdata_custom` int(50) NOT NULL default "0", `data` text NOT NULL) TYPE=MyISAM';

$sql[] = 'DROP TABLE IF EXISTS ' . $table_prefix . 'link_votes';
$sql[] = 'CREATE TABLE ' . $table_prefix . 'link_votes (`user_id` mediumint(8) NOT NULL default "0", `votes_ip` varchar(50) NOT NULL default "0", `votes_link` int(50) NOT NULL default "0", `rate_point` tinyint(3) unsigned NOT NULL default "0", KEY `user_id` (`user_id`)) TYPE=MyISAM';	
		
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'logs';
$sql[] = 'CREATE TABLE '. $table_prefix .'logs (`id_log` MEDIUMINT(10) NOT NULL auto_increment, `mode` VARCHAR(50) DEFAULT "" NULL, `topic_id` MEDIUMINT(10) DEFAULT "0" NULL, `user_id` MEDIUMINT(8) DEFAULT "0" NULL, `username` VARCHAR(255) DEFAULT "" NULL, `user_ip` char(8) DEFAULT "0" NOT NULL, `time` INT(11) DEFAULT "0" NULL, PRIMARY KEY (`id_log`)) TYPE=MYISAM';
	
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'lottery_history';
$sql[] = 'CREATE TABLE '. $table_prefix .'lottery_history (`id` int UNSIGNED NOT NULL auto_increment, `user_id` int(10) NOT NULL, `amount` int(10) NOT NULL, `time` int(10) NOT NULL, PRIMARY KEY (`id`), INDEX (user_id)) TYPE=MYISAM';
	
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'lottery';
$sql[] = 'CREATE TABLE '. $table_prefix .'lottery (`id` int UNSIGNED NOT NULL auto_increment, `user_id` int(10) NOT NULL, PRIMARY KEY (`id`), INDEX(user_id)) TYPE=MYISAM';

$sql[] = 'DROP TABLE IF EXISTS ' . $table_prefix . 'medal';
$sql[] = 'CREATE TABLE ' . $table_prefix . 'medal (`medal_id` mediumint(8) UNSIGNED NOT NULL auto_increment, `cat_id` mediumint(8) UNSIGNED NOT NULL default "1", `medal_name` varchar(40) NOT NULL, `medal_description` varchar(255) NOT NULL, `medal_image` varchar(40) NULL, PRIMARY KEY (`medal_id`)) TYPE=MyISAM';

$sql[] = 'DROP TABLE IF EXISTS ' . $table_prefix . 'medal_cat';
$sql[] = 'CREATE TABLE ' . $table_prefix . 'medal_cat (`cat_id` mediumint(8) UNSIGNED NOT NULL auto_increment, `cat_title` varchar(100) NOT NULL, `cat_order` mediumint(8) UNSIGNED NOT NULL default "0", PRIMARY KEY (`cat_id`), KEY `cat_order` (`cat_order`)) TYPE=MyISAM';

$sql[] = 'DROP TABLE IF EXISTS ' . $table_prefix . 'medal_mod';
$sql[] = 'CREATE TABLE ' . $table_prefix . 'medal_mod (`mod_id` mediumint(8) UNSIGNED NOT NULL auto_increment, `medal_id` mediumint(8) UNSIGNED NOT NULL, `user_id` mediumint(8) UNSIGNED NOT NULL, PRIMARY KEY (`mod_id`)) TYPE=MyISAM';

$sql[] = 'DROP TABLE IF EXISTS ' . $table_prefix . 'medal_user';
$sql[] = 'CREATE TABLE ' . $table_prefix . 'medal_user (`issue_id` mediumint(8) UNSIGNED NOT NULL auto_increment, `medal_id` mediumint(8) UNSIGNED NOT NULL, `user_id` mediumint(8) UNSIGNED NOT NULL, `issue_reason` varchar(255) NOT NULL, `issue_time` int(11) NOT NULL, PRIMARY KEY (`issue_id`)) TYPE=MyISAM';

$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'meeting_comment';
$sql[] = 'CREATE TABLE '. $table_prefix .'meeting_comment (`comment_id` MEDIUMINT(8) auto_increment, `meeting_id` MEDIUMINT(8) UNSIGNED NOT NULL, `user_id` MEDIUMINT(8) DEFAULT "0" NOT NULL, `meeting_comment` TEXT NOT NULL, `meeting_edit_time` INT(11) DEFAULT "0" NOT NULL, `bbcode_uid` CHAR(10) DEFAULT "" NOT NULL, PRIMARY KEY (`comment_id`)) TYPE=MYISAM';
	
$sql[] = 'DROP TABLE IF EXISTS ' . $table_prefix . 'meeting_config';
$sql[] = 'CREATE TABLE ' . $table_prefix . 'meeting_config (`config_name` VARCHAR(255) DEFAULT "" NOT NULL, `config_value` VARCHAR(255) DEFAULT "" NOT NULL, PRIMARY KEY (config_name))';

$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'meeting_data';
$sql[] = 'CREATE TABLE '. $table_prefix .'meeting_data (`meeting_id` MEDIUMINT(8) UNSIGNED NOT NULL, `meeting_time` int(11) DEFAULT "0" NOT NULL, `meeting_until` int(11) DEFAULT "0" NOT NULL, `meeting_location` VARCHAR(255) NOT NULL, `meeting_subject` VARCHAR(255) NOT NULL, `meeting_desc` TEXT NOT NULL, `meeting_link` VARCHAR(255) NOT NULL, `meeting_places` MEDIUMINT(8) DEFAULT "0" NOT NULL, `meeting_by_user` MEDIUMINT(8) DEFAULT "0" NOT NULL, `meeting_edit_by_user` MEDIUMINT(8) DEFAULT "0" NOT NULL, `meeting_start_value` MEDIUMINT(8) DEFAULT "0" NOT NULL, `meeting_recure_value` MEDIUMINT(8) DEFAULT "5" NOT NULL, `meeting_notify` TINYINT(1) NOT NULL DEFAULT "0", `meeting_guest_overall` MEDIUMINT(8) NOT NULL DEFAULT "0", `meeting_guest_single` MEDIUMINT(8) NOT NULL DEFAULT "0", `meeting_guest_names` TINYINT(1) NOT NULL DEFAULT "0", `bbcode_uid` CHAR(10) DEFAULT "" NOT NULL, PRIMARY KEY (`meeting_id`)) TYPE=MYISAM';

$sql[] = 'DROP TABLE IF EXISTS ' . $table_prefix . 'meeting_guestnames';
$sql[] = 'CREATE TABLE ' . $table_prefix . 'meeting_guestnames (`meeting_id` MEDIUMINT(8) NOT NULL DEFAULT "0", `user_id` MEDIUMINT(8) NOT NULL DEFAULT "0", `guest_prename` VARCHAR(255) NOT NULL DEFAULT "", `guest_name` VARCHAR(255) NOT NULL DEFAULT "") TYPE=MyISAM';
	
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'meeting_user';
$sql[] = 'CREATE TABLE '. $table_prefix .'meeting_user (`meeting_id` MEDIUMINT(8) UNSIGNED NOT NULL, `user_id` MEDIUMINT(8) DEFAULT "0" NOT NULL, `meeting_sure` tinyint(4) DEFAULT "0" NOT NULL, `meeting_guests` MEDIUMINT(8) NOT NULL DEFAULT "0")';
	
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'meeting_usergroup';
$sql[] = 'CREATE TABLE '. $table_prefix .'meeting_usergroup (`meeting_id` MEDIUMINT(8) UNSIGNED NOT NULL, `meeting_group` MEDIUMINT(8) NOT NULL)';

$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'module_admin_panel';
$sql[] = 'CREATE TABLE '. $table_prefix .'module_admin_panel (`module_id` MEDIUMINT(8) UNSIGNED DEFAULT "0" NOT NULL, `config_name` VARCHAR(255) DEFAULT "" NOT NULL, `config_value` VARCHAR(255) DEFAULT "" NOT NULL, `config_type` VARCHAR(20) DEFAULT "" NOT NULL, `config_title` VARCHAR(100) DEFAULT "" NOT NULL, `config_explain` VARCHAR(100) DEFAULT NULL, `config_trigger` VARCHAR(20) DEFAULT "" NOT NULL)';
	
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'module_cache';
$sql[] = 'CREATE TABLE '. $table_prefix .'module_cache (module_id MEDIUMINT(8) DEFAULT "0" NOT NULL, module_cache_time int(12) DEFAULT "0" NOT NULL, db_cache TEXT NOT NULL, priority MEDIUMINT(8) UNSIGNED DEFAULT "0" NOT NULL, PRIMARY KEY (module_id)) TYPE=MYISAM';
	
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'module_group_auth';
$sql[] = 'CREATE TABLE '. $table_prefix .'module_group_auth (module_id MEDIUMINT(8) UNSIGNED NOT NULL, group_id MEDIUMINT(8) UNSIGNED NOT NULL)';
	
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'module_info';
$sql[] = 'CREATE TABLE '. $table_prefix .'module_info (module_id MEDIUMINT(8) DEFAULt "0" NOT NULL, long_name VARCHAR(100) DEFAULT "" NOT NULL, author VARCHAR(50) DEFAULT NULL, email VARCHAR(50) DEFAULT NULL, url VARCHAR(100) DEFAULT NULL, version VARCHAR(10) DEFAULT "" NOT NULL, update_site VARCHAR(100) DEFAULT NULL, extra_info LONGTEXT DEFAULT "" NOT NULL, PRIMARY KEY (module_id)) TYPE=MYISAM';

$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'modules';
$sql[] = 'CREATE TABLE '. $table_prefix .'modules (module_id MEDIUMINT(8) UNSIGNED NOT NULL auto_increment, short_name VARCHAR(100) DEFAULT NULL, update_time MEDIUMINT(8) DEFAULT "0" NOT NULL, module_order MEDIUMINT(8) DEFAULT "0" NOT NULL, active tinyint(2) DEFAULT "0" NOT NULL, perm_all tinyint(2) UNSIGNED DEFAULT "1" NOT NULL, perm_reg tinyint(2) UNSIGNED DEFAULT "1" NOT NULL, perm_mod tinyint(2) UNSIGNED DEFAULT "1" NOT NULL, perm_admin tinyint(2) UNSIGNED DEFAULT "1" NOT NULL, PRIMARY KEY (module_id)) TYPE=MYISAM';
	
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'mycalendar';
$sql[] = 'CREATE TABLE '. $table_prefix .'mycalendar (cal_id int(12) NOT NULL auto_increment, topic_id INT(20) NOT NULL DEFAULT "0", cal_date DATETIME DEFAULT "00-00-00 00:00:00" NOT NULL, cal_interval TINYINT(3) DEFAULT "1" NOT NULL, cal_interval_units ENUM("DAY", "WEEK", "MONTH", "YEAR") DEFAULT "DAY" NOT NULL, cal_repeat TINYINT(3) DEFAULT "1" NOT NULL, forum_id INT(5) NOT NULL DEFAULT "0", confirmed enum("Y","N") DEFAULT "Y" NOT NULL, event_type_id tinyint(4) DEFAULT "0" NOT NULL, PRIMARY KEY (cal_id), UNIQUE (topic_id)) TYPE=MYISAM';

$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'mycalendar_event_types';
$sql[] = 'CREATE TABLE '. $table_prefix .'mycalendar_event_types (forum_id MEDIUMINT(8) UNSIGNED DEFAULT "0" NOT NULL, event_type_id tinyint(4) NOT NULL, event_type_text VARCHAR(255) NOT NULL, highlight_color VARCHAR(7) NOT NULL)';

$sql[] = 'DROP TABLE IF EXISTS ' . $table_prefix . 'optimize_db';
$sql[] = 'CREATE TABLE ' . $table_prefix . 'optimize_db (`cron_enable` ENUM("0","1") DEFAULT "0" NOT NULL, `cron_every` INT(7) DEFAULT "86400" NOT NULL, `cron_next` INT(11) DEFAULT "0" NOT NULL, `cron_count` INT(5) DEFAULT "0" NOT NULL, `show_tables` VARCHAR(150) DEFAULT "" NOT NULL, `empty_tables` TINYINT(1) DEFAULT "0" NOT NULL)';
	
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'pa_auth';
$sql[] = 'CREATE TABLE '. $table_prefix .'pa_auth (group_id MEDIUMINT(8) DEFAULT "0" NOT NULL, cat_id SMALLINT(5) UNSIGNED DEFAULT "0" NOT NULL, auth_view TINYINT(1) DEFAULT "0" NOT NULL, auth_read TINYINT(1) DEFAULT "0" NOT NULL, auth_view_file TINYINT(1) DEFAULT "0" NOT NULL, auth_edit_file TINYINT(1) DEFAULT "0" NOT NULL, auth_delete_file TINYINT(1) DEFAULT "0" NOT NULL, auth_upload TINYINT(1) DEFAULT "0" NOT NULL, auth_download TINYINT(1) DEFAULT "0" NOT NULL, auth_rate TINYINT(1) DEFAULT "0" NOT NULL, auth_email TINYINT(1) DEFAULT "0" NOT NULL, auth_view_comment TINYINT(1) DEFAULT "0" NOT NULL, auth_post_comment TINYINT(1) DEFAULT "0" NOT NULL, auth_edit_comment TINYINT(1) DEFAULT "0" NOT NULL, auth_delete_comment TINYINT(1) DEFAULT "0" NOT NULL, auth_mod TINYINT(1) DEFAULT "1" NOT NULL, auth_search TINYINT(1) DEFAULT "1" NOT NULL, auth_stats TINYINT(1) NOT NULL, auth_toplist TINYINT(1) NOT NULL, auth_viewall TINYINT(1) DEFAULT "1" NOT NULL, KEY group_id (group_id), KEY cat_id (cat_id)) TYPE=MYISAM';
	
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'pa_cat';
$sql[] = 'CREATE TABLE '. $table_prefix .'pa_cat (cat_id INT(10) NOT NULL auto_increment, cat_name TEXT, cat_desc TEXT, cat_parent INT(50) DEFAULT NULL, parents_data TEXT NOT NULL, cat_order INT(50) DEFAULT NULL, cat_allow_file TINYINT(2) DEFAULT "0" NOT NULL, cat_allow_ratings TINYINT(2) DEFAULT "1" NOT NULL, cat_allow_comments TINYINT(2) DEFAULT "1" NOT NULL, cat_files MEDIUMINT(8) DEFAULT "-1" NOT NULL, cat_last_file_id MEDIUMINT(8) UNSIGNED DEFAULT "0" NOT NULL, cat_last_file_name VARCHAR(255) NOT NULL, cat_last_file_time INT(50) UNSIGNED DEFAULT "0" NOT NULL, auth_view TINYINT(2) DEFAULT "0" NOT NULL, auth_read TINYINT(2) DEFAULT "0" NOT NULL, auth_view_file TINYINT(2) DEFAULT "0" NOT NULL, auth_edit_file TINYINT(1) DEFAULT "0" NOT NULL, auth_delete_file TINYINT(1) DEFAULT "0" NOT NULL, auth_upload TINYINT(2) DEFAULT "0" NOT NULL, auth_download TINYINT(2) DEFAULT "0" NOT NULL, auth_rate TINYINT(2) DEFAULT "0" NOT NULL, auth_email TINYINT(2) DEFAULT "0" NOT NULL, auth_view_comment TINYINT(2) DEFAULT "0" NOT NULL, auth_post_comment TINYINT(2) DEFAULT "0" NOT NULL, auth_edit_comment TINYINT(2) DEFAULT "0" NOT NULL, auth_delete_comment TINYINT(2) DEFAULT "0" NOT NULL, PRIMARY KEY (cat_id)) TYPE=MYISAM';
	
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'pa_comments';
$sql[] = 'CREATE TABLE '. $table_prefix .'pa_comments (comments_id INT(10) NOT NULL auto_increment, file_id INT(10) DEFAULT "0" NOT NULL, comments_text TEXT NOT NULL, comments_title TEXT NOT NULL, comments_time INT(50) DEFAULT "0" NOT NULL, comment_bbcode_uid CHAR(10) DEFAULT "" NOT NULL, poster_id MEDIUMINT(8) DEFAULT "0" NOT NULL, PRIMARY KEY (comments_id), FULLTEXT KEY comment_bbcode_uid (comment_bbcode_uid)) TYPE=MYISAM';
	
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'pa_config';
$sql[] = 'CREATE TABLE '. $table_prefix .'pa_config (`config_name` VARCHAR(255) NOT NULL, `config_value` VARCHAR(255) NOT NULL, PRIMARY KEY(`config_name`)) TYPE=MYISAM';
	
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'pa_custom';
$sql[] = 'CREATE TABLE '. $table_prefix .'pa_custom (custom_id INT(50) NOT NULL auto_increment, custom_name TEXT NOT NULL, custom_description TEXT NOT NULL, data TEXT NOT NULL, regex VARCHAR(255) NOT NULL, field_order INT(20) NOT NULL, field_type TINYINT(2) NOT NULL, PRIMARY KEY (custom_id)) TYPE=MYISAM';
	
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'pa_customdata';
$sql[] = 'CREATE TABLE '. $table_prefix .'pa_customdata (customdata_file INT(50) DEFAULT "0" NOT NULL, customdata_custom INT(50) DEFAULT "0" NOT NULL, data TEXT NOT NULL) TYPE=MYISAM';
	
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'pa_download_info';
$sql[] = 'CREATE TABLE '. $table_prefix .'pa_download_info (file_id MEDIUMINT(8) DEFAULT "0" NOT NULL, user_id MEDIUMINT(8) DEFAULT "0" NOT NULL, downloader_ip VARCHAR(8) NOT NULL, downloader_os VARCHAR(8) NOT NULL, downloader_browser VARCHAR(255) DEFAULT "" NOT NULL, browser_version VARCHAR(8) NOT NULL)';
	
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'pa_files';
$sql[] = 'CREATE TABLE '. $table_prefix .'pa_files (file_id INT(10) NOT NULL auto_increment, user_id MEDIUMINT(8) DEFAULT "0" NOT NULL, poster_ip VARCHAR(8) NOT NULL, file_name TEXT, file_size INT(20) NOT NULL, unique_name VARCHAR(255) NOT NULL, real_name VARCHAR(255) NOT NULL, file_dir VARCHAR(255) NOT NULL, file_desc TEXT, file_creator TEXT, file_version TEXT, file_longdesc TEXT, file_ssurl TEXT, file_sshot_link TINYINT(2) DEFAULT "0" NOT NULL, file_dlurl TEXT, file_time INT(50) DEFAULT NULL, file_update_time INT(50) NOT NULL, file_catid INT(10) DEFAULT NULL, file_posticon TEXT, file_license INT(10) DEFAULT NULL, file_dls INT(10) DEFAULT NULL, file_last INT(50) DEFAULT NULL, file_pin INT(2) DEFAULT NULL, file_docsurl TEXT, file_approved TINYINT(2) DEFAULT "0" NOT NULL, file_broken TINYINT(1) DEFAULT "0" NOT NULL, PRIMARY KEY (file_id)) TYPE=MYISAM';
	
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'pa_license';
$sql[] = 'CREATE TABLE '. $table_prefix .'pa_license (license_id INT(10) NOT NULL auto_increment, license_name TEXT, license_text TEXT, PRIMARY KEY (license_id)) TYPE=MYISAM';
	
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'pa_mirrors';
$sql[] = 'CREATE TABLE '. $table_prefix .'pa_mirrors (mirror_id MEDIUMINT(8) NOT NULL auto_increment, file_id INT(10) NOT NULL, unique_name VARCHAR(255) DEFAULT "" NOT NULL, file_dir VARCHAR(255) NOT NULL, file_dlurl VARCHAR(255) DEFAULT "" NOT NULL, mirror_location VARCHAR(255) DEFAULT "" NOT NULL, PRIMARY KEY (mirror_id), KEY file_id (file_id)) TYPE=MYISAM';
	
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'pa_votes';
$sql[] = 'CREATE TABLE '. $table_prefix .'pa_votes (user_id MEDIUMINT(8) DEFAULT "0" NOT NULL, votes_ip VARCHAR(50) DEFAULT "0" NOT NULL, votes_file INT(50) DEFAULT "0" NOT NULL, rate_point TINYINT(3) UNSIGNED NOT NULL,  voter_os VARCHAR(255) NOT NULL, voter_browser VARCHAR(255) NOT NULL, browser_version VARCHAR(8) NOT NULL, KEY user_id (user_id)) TYPE=MYISAM';

$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'pages';
$sql[] = 'CREATE TABLE '. $table_prefix .'pages (page_id mediumint(5) unsigned auto_increment, page_name varchar(32) NOT NULL, page_parm_name varchar(32) default "", page_parm_value varchar(32) default "", page_key varchar(255) default "", member_views int(11) unsigned default "0", guest_views int(11) unsigned default "0", disable_page tinyint(1) unsigned default "0", auth_level tinyint(2) unsigned default "0", min_post_count mediumint(8) unsigned default "0", max_post_count mediumint(8) unsigned default "0", group_list varchar(255) default "", disabled_message	varchar(255) default "", primary key (page_id), unique key (page_key)) TYPE=MyISAM';

$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'pjirc';
$sql[] = 'CREATE TABLE '. $table_prefix .'pjirc (pjirc_name VARCHAR(255) DEFAULT "" NOT NULL, pjirc_value VARCHAR(255) DEFAULT "" NOT NULL, PRIMARY KEY (pjirc_name)) TYPE=MYISAM';		
	
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'points_logger';
$sql[] = 'CREATE TABLE '. $table_prefix .'points_logger (id MEDIUMINT(8) auto_increment, admin VARCHAR(25) NOT NULL, person VARCHAR(25) NOT NULL, add_sub VARCHAR(50) NOT NULL, total MEDIUMINT(8) DEFAULT "0" NOT NULL, time int(11) DEFAULT "0" NOT NULL, PRIMARY KEY (id)) TYPE=MYISAM';
	
$sql[] = 'DROP TABLE IF EXISTS ' . $table_prefix . 'posts_ignore_sigav';
$sql[] = 'CREATE TABLE ' . $table_prefix . 'posts_ignore_sigav (`user_id` mediumint(8) UNSIGNED DEFAULT "0" NOT NULL, `hid_id` mediumint(8) UNSIGNED DEFAULT "0" NOT NULL) TYPE=MyISAM';

$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'portal';
$sql[] = 'CREATE TABLE '. $table_prefix .'portal (portal_id MEDIUMINT(8) NOT NULL auto_increment, `portal_order` MEDIUMINT(8) UNSIGNED DEFAULT "1" NOT NULL, portal_use_url TINYINT(1), portal_use_iframe TINYINT(1) DEFAULT "0", `portal_iframe_height` VARCHAR(4) NOT NULL DEFAULT "600", portal_forum MEDIUMINT(8) NOT NULL, portal_url VARCHAR(255) NOT NULL, portal_list_limit MEDIUMINT(8) NOT NULL, portal_char_limit MEDIUMINT(8) NOT NULL, portal_ascending TINYINT(1), portal_nodate TINYINT(1), portal_navbar_name VARCHAR(100) NOT NULL, portal_newsfader TINYINT(1) DEFAULT "0", portal_column_width VARCHAR(3) DEFAULT "200" NOT NULL, portal_navbar TINYINT(1) DEFAULT "0", portal_moreover TINYINT(1) DEFAULT "0", portal_calendar TINYINT(1) DEFAULT "0", portal_online TINYINT(1) DEFAULT "0", portal_onlinetoday TINYINT(1) DEFAULT "0", portal_latest TINYINT(1) DEFAULT "0", portal_latest_exclude_forums VARCHAR(100) NOT NULL, portal_latest_amt VARCHAR(5) DEFAULT "5" NOT NULL, `portal_latest_scrolling` TINYINT(1) NOT NULL DEFAULT "0", portal_poll TINYINT(1), portal_polls VARCHAR(100) NOT NULL, portal_photo TINYINT(1) DEFAULT "0", portal_search TINYINT(1) DEFAULT "0", portal_quote TINYINT(1) DEFAULT "0", portal_links TINYINT(1) DEFAULT "0", portal_links_height VARCHAR(3) DEFAULT "100" NOT NULL, portal_ourlink TINYINT(1) DEFAULT "0", portal_downloads TINYINT(1) DEFAULT "0", portal_randomuser TINYINT(1) DEFAULT "0", portal_mostpoints TINYINT(1) DEFAULT "0", portal_topposters TINYINT(1) DEFAULT "0", portal_newusers TINYINT(1) DEFAULT "0", portal_games TINYINT(1) DEFAULT "0", portal_clock TINYINT(1) DEFAULT "0", portal_karma TINYINT(1) DEFAULT "0", portal_horoscopes TINYINT(1) DEFAULT "0", portal_wallpaper TINYINT(1) DEFAULT "0", `portal_donors` TINYINT(1) DEFAULT "0", `portal_referrers` TINYINT(1) DEFAULT "0", `portal_shoutbox` TINYINT(1) DEFAULT "0", PRIMARY KEY(portal_id)) TYPE=MYISAM';
	
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'posts_edit';
$sql[] = 'CREATE TABLE '. $table_prefix .'posts_edit (post_id MEDIUMINT(8) UNSIGNED DEFAULT "0" NOT NULL, user_id MEDIUMINT(8) UNSIGNED DEFAULT "0" NOT NULL, post_edit_count SMALLINT(5) UNSIGNED DEFAULT "0" NOT NULL, post_edit_time INT(11) DEFAULT NULL, KEY (post_id)) TYPE=MYISAM';

$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'privmsgs_archive';
$sql[] = 'CREATE TABLE '. $table_prefix .'privmsgs_archive (privmsgs_id mediumint(8) unsigned NOT NULL AUTO_INCREMENT, privmsgs_type tinyint(4) NOT NULL default "0", privmsgs_subject varchar(255) NOT NULL default "0", privmsgs_from_userid mediumint(8) NOT NULL default "0", privmsgs_to_userid mediumint(8) NOT NULL default "0", privmsgs_date int(11) NOT NULL default "0", privmsgs_ip varchar(8) NOT NULL default "", privmsgs_enable_bbcode tinyint(1) NOT NULL default "1", privmsgs_enable_html tinyint(1) NOT NULL default "0", privmsgs_enable_smilies tinyint(1) NOT NULL default "1", privmsgs_attach_sig tinyint(1) NOT NULL default "1", PRIMARY KEY (privmsgs_id), KEY privmsgs_from_userid (privmsgs_from_userid), KEY privmsgs_to_userid (privmsgs_to_userid)) TYPE=MyISAM';
	
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'profile_view';
$sql[] = 'CREATE TABLE '. $table_prefix .'profile_view (`user_id` MEDIUMINT(8) NOT NULL, `viewer_id` INT(11) NOT NULL, `view_stamp` INT(11) NOT NULL, `counter` MEDIUMINT(8) NOT NULL)';

$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'quota_limits';
$sql[] = 'CREATE TABLE '. $table_prefix .'quota_limits (quota_limit_id MEDIUMINT(8) UNSIGNED NOT NULL auto_increment, quota_desc VARCHAR(20) DEFAULT "" NOT NULL, quota_limit BIGINT(20) UNSIGNED DEFAULT "0" NOT NULL, PRIMARY KEY (quota_limit_id)) TYPE=MYISAM';

$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'rating';
$sql[] = 'CREATE TABLE '. $table_prefix .'rating (post_id INT UNSIGNED DEFAULT "0" NOT NULL, user_id INT UNSIGNED DEFAULT "0" NOT NULL, rating_time INT UNSIGNED DEFAULT "0" NOT NULL, option_id SMALLINT UNSIGNED DEFAULT "0" NOT NULL, KEY rating_postid(post_id), KEY rating_userid(user_id), KEY rating_ratingoptionid(option_id)) TYPE=MYISAM';

$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'rating_bias';
$sql[] = 'CREATE TABLE '. $table_prefix .'rating_bias (`bias_id` INT UNSIGNED NOT NULL auto_increment, `user_id` INT UNSIGNED NOT NULL DEFAULT "0", `target_user` INT UNSIGNED NOT NULL DEFAULT "0", `bias_status` TINYINT UNSIGNED NOT NULL DEFAULT "0", `bias_time` INT UNSIGNED NOT NULL DEFAULT "0", `post_id` INT UNSIGNED NOT NULL DEFAULT "0", `option_id` SMALLINT UNSIGNED NOT NULL DEFAULT "0", PRIMARY KEY (`bias_id`), KEY `ratingbias_userid_targetuser` (user_id, target_user),  KEY `ratingbias_targetuser` (target_user), KEY `ratingbias_postid` (post_id), KEY `ratingbias_optionid` (option_id))';

$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'rating_config';
$sql[] = 'CREATE TABLE '. $table_prefix .'rating_config (label VARCHAR(100) DEFAULT NULL, num_value INT UNSIGNED DEFAULT "0" NOT NULL, text_value VARCHAR(255) DEFAULT NULL, config_id INT UNSIGNED DEFAULT "0" NOT NULL, input_type TINYINT UNSIGNED DEFAULT "0" NOT NULL, list_order SMALLINT(5) UNSIGNED DEFAULT "0" NOT NULL, PRIMARY KEY (config_id)) TYPE=MYISAM';

$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'rating_option';
$sql[] = 'CREATE TABLE '. $table_prefix .'rating_option (option_id SMALLINT UNSIGNED NOT NULL auto_increment, points TINYINT DEFAULT "0" NOT NULL, label VARCHAR(100) DEFAULT NULL, weighting SMALLINT UNSIGNED DEFAULT "0" NOT NULL, user_type TINYINT UNSIGNED UNSIGNED DEFAULT "0" NOT NULL, PRIMARY KEY (option_id), KEY ratingoption_rating(points), KEY ratingoption_weighting (weighting)) TYPE=MYISAM';

$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'rating_rank';
$sql[] = 'CREATE TABLE '. $table_prefix .'rating_rank (rating_rank_id SMALLINT UNSIGNED NOT NULL auto_increment, type TINYINT UNSIGNED DEFAULT "0" NOT NULL, average_threshold TINYINT DEFAULT "0" NOT NULL, sum_threshold INT DEFAULT "0" NOT NULL, label VARCHAR(100) DEFAULT NULL, icon VARCHAR(255) DEFAULT NULL, user_rank INT UNSIGNED NOT NULL, PRIMARY KEY (rating_rank_id), KEY ratingrank_type(type)) TYPE=MYISAM';

$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'rating_temp';
$sql[] = 'CREATE TABLE '. $table_prefix .'rating_temp (`topic_id` INT UNSIGNED NOT NULL, `points` TINYINT NOT NULL, KEY `ratingtemp_topicid` (topic_id))';

$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'referers';
$sql[] = 'CREATE TABLE '. $table_prefix .'referers (referer_id MEDIUMINT(8) UNSIGNED NOT NULL auto_increment, referer_host VARCHAR(255) DEFAULT "" NOT NULL, referer_url VARCHAR(255) DEFAULT "" NOT NULL, referer_ip VARCHAR(8) DEFAULT "" NOT NULL, referer_hits INT(10) DEFAULT "1" NOT NULL, referer_firstvisit INT(11) DEFAULT "0" NOT NULL, referer_lastvisit INT(11) DEFAULT "0" NOT NULL, PRIMARY KEY (referer_id)) TYPE=MYISAM';

$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'referral';
$sql[] = 'CREATE TABLE '. $table_prefix .'referral (referral_id MEDIUMINT(8) UNSIGNED NOT NULL auto_increment, ruid VARCHAR(7) DEFAULT "0" NOT NULL, nuid VARCHAR(7) DEFAULT "0" NOT NULL, referral_time VARCHAR(10) DEFAULT "" NOT NULL, KEY referraler_id(referral_id)) TYPE=MYISAM';
	
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'serverload';
$sql[] = 'CREATE TABLE '. $table_prefix .'serverload (time INT(14) DEFAULT "0" NOT NULL) TYPE=MYISAM';
	
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'shop_items';
$sql[] = 'CREATE TABLE '. $table_prefix .'shop_items (id INT UNSIGNED NOT NULL auto_increment, name CHAR(32) NOT NULL, shop CHAR(32) NOT NULL, sdesc CHAR(80) NOT NULL, ldesc TEXT NOT NULL, cost INT(20) UNSIGNED DEFAULT "100", stock TINYINT(3) UNSIGNED DEFAULT "10", maxstock TINYINT(3) UNSIGNED DEFAULT "100", sold INT(5) UNSIGNED DEFAULT "0" NOT NULL, accessforum INT(4) DEFAULT "0", PRIMARY KEY (id), INDEX(name)) TYPE=MYISAM';

$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'shop_transactions';
$sql[] = 'CREATE TABLE '. $table_prefix .'shop_transactions (shoptrans_date INT(11) DEFAULT "0" NOT NULL, trans_user MEDIUMINT(8) NOT NULL, trans_item VARCHAR(32) DEFAULT "" NOT NULL, trans_type VARCHAR(255) DEFAULT "" NOT NULL, trans_total MEDIUMINT(8) DEFAULT "0" NOT NULL, PRIMARY KEY(shoptrans_date)) TYPE=MYISAM';
	
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'shops';
$sql[] = 'CREATE TABLE '. $table_prefix .'shops (id int UNSIGNED NOT NULL auto_increment, shopname char(32) NOT NULL, shoptype CHAR(32) NOT NULL, type CHAR(32) NOT NULL, restocktime INT(20) UNSIGNED DEFAULT "86400", restockedtime INT(20) UNSIGNED DEFAULT "0", restockamount INT(4) UNSIGNED DEFAULT "5", amountearnt INT(20) UNSIGNED DEFAULT "0", PRIMARY KEY (id), INDEX(shopname)) TYPE=MYISAM';
	
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'shout';
$sql[] = 'CREATE TABLE '. $table_prefix .'shout (shout_id MEDIUMINT(8) UNSIGNED NOT NULL auto_increment, shout_username VARCHAR(25) NOT NULL, shout_user_id MEDIUMINT(8) NOT NULL, shout_group_id MEDIUMINT(8) NOT NULL, shout_session_time INT(11) NOT NULL, shout_ip CHAR(8) NOT NULL, shout_text TEXT NOT NULL, shout_active MEDIUMINT(8) NOT NULL, enable_bbcode TINYINT (1) NOT NULL, enable_html TINYINT (1) NOT NULL, enable_smilies TINYINT (1) NOT NULL, enable_sig TINYINT (1) NOT NULL, shout_bbcode_uid CHAR(10) DEFAULT "" NOT NULL, PRIMARY KEY (shout_id)) TYPE=MYISAM';
	
$sql[] = 'DROP TABLE IF EXISTS ' . $table_prefix . 'smilies_cat';
$sql[] = 'CREATE TABLE ' . $table_prefix . 'smilies_cat (`cat_id` smallint(3) unsigned NOT NULL auto_increment, `cat_name` varchar(50) NOT NULL default "", `description` varchar(100) NOT NULL default "", `cat_order` smallint(3) NOT NULL default "0", `cat_perms` tinyint(2) NOT NULL default "10", `cat_group` varchar(255) default NULL, `cat_forum` mediumtext NOT NULL, `cat_special` tinyint(1) NOT NULL default "-2", `cat_open` tinyint(1) NOT NULL default "1", `cat_icon_url` varchar(100) default NULL, `smilies_popup` varchar(20) NOT NULL default "", PRIMARY KEY (`cat_id`))';
	
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'stats_smilies_index';
$sql[] = 'CREATE TABLE '. $table_prefix .'stats_smilies_index (code VARCHAR(50) DEFAULT NULL, smile_url VARCHAR(100) DEFAULT NULL, smile_count MEDIUMINT(8) DEFAULT "0")';
	
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'stats_smilies_info';
$sql[] = 'CREATE TABLE '. $table_prefix .'stats_smilies_info (last_post_id MEDIUMINT(8) DEFAULT "0" NOT NULL, last_update_time int(12) DEFAULT "0" NOT NULL, update_time MEDIUMINT(8) DEFAULT "10080" NOT NULL)';
	
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'thanks';
$sql[] = 'CREATE TABLE '. $table_prefix .'thanks(`topic_id` MEDIUMINT(8) NOT NULL, `user_id` MEDIUMINT(8) NOT NULL, `thanks_time` INT(11) NOT NULL) TYPE=MYISAM';

$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'thread_kicker';
$sql[] = 'CREATE TABLE '. $table_prefix .'thread_kicker(`kick_id` INT(11) NOT NULL auto_increment, `user_id` INT(11) NOT NULL DEFAULT "0", `topic_id` INT(11) NOT NULL DEFAULT "0", `kicker` INT(11) NOT NULL DEFAULT "0", `post_id` INT(11) NOT NULL DEFAULT "0", `kick_time` INT(11) NOT NULL DEFAULT "0", `kicker_status` INT(2) NOT NULL DEFAULT "0", `kicker_username` VARCHAR(25) NOT NULL DEFAULT "", `kicked_username` VARCHAR(25) NOT NULL DEFAULT "", `topic_title` VARCHAR(120) NOT NULL DEFAULT "", PRIMARY KEY (`kick_id`)) TYPE=MYISAM';

$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'title_infos';
$sql[] = 'CREATE TABLE '. $table_prefix .'title_infos (`id` INT(11) NOT NULL auto_increment, `title_info` VARCHAR(255) NOT NULL, `info_color` VARCHAR(6) default "" NOT NULL, `date_format` VARCHAR(25), `title_pos` TINYINT(1) DEFAULT "0" NOT NULL, `admin_auth` TINYINT(1) DEFAULT "0", `supermod_auth` TINYINT(1) DEFAULT "0", `mod_auth` TINYINT(1) DEFAULT "0", `poster_auth` TINYINT(1) DEFAULT "0", UNIQUE (`id`)) TYPE=MYISAM';
		
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'topics_viewdata';
$sql[] = 'CREATE TABLE '. $table_prefix . 'topics_viewdata (`viewed_id` INT(10) UNSIGNED NOT NULL auto_increment, `user_id` MEDIUMINT(8) DEFAULT "0" NOT NULL, `topic_id` MEDIUMINT(8) UNSIGNED DEFAULT "0" NOT NULL, `num_views` TINYINT(4) UNSIGNED DEFAULT "1" NOT NULL, `last_viewed` INT(10) UNSIGNED DEFAULT "0" NOT NULL, PRIMARY KEY (viewed_id), KEY user_id (user_id, topic_id), KEY last_viewed (last_viewed)) TYPE=MYISAM';

$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'toplist';
$sql[] = 'CREATE TABLE '. $table_prefix .'toplist (id int(255) NOT NULL auto_increment, nam VARCHAR(255) NOT NULL DEFAULT "", inf VARCHAR(255) NOT NULL DEFAULT "", hin int(255) NOT NULL DEFAULT "0", lin VARCHAR(255) NOT NULL DEFAULT "", `out` int(255) NOT NULL DEFAULT "0", img int(255) NOT NULL DEFAULT "0", ban VARCHAR(255) NOT NULL DEFAULT "http://", owner int(255) NOT NULL DEFAULT "0", tot int(255) NOT NULL DEFAULT "0", imgfile VARCHAR(50) NOT NULL DEFAULT "button1", ip VARCHAR(8) NOT NULL, PRIMARY KEY (id)) TYPE=MYISAM';
	
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'toplist_anti_flood';
$sql[] = 'CREATE TABLE '. $table_prefix .'toplist_anti_flood (id int(255) NOT NULL, ip VARCHAR(8) NOT NULL, time int(11) NOT NULL, type VARCHAR(3) NOT NULL) TYPE=MYISAM';

$sql[] = 'TRUNCATE TABLE '. $table_prefix .'themes';
$sql[] = 'TRUNCATE TABLE '. $table_prefix .'themes';

$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'transactions';
$sql[] = 'CREATE TABLE '. $table_prefix .'transactions (trans_date int(11) DEFAULT "0" NOT NULL, trans_from VARCHAR(30) DEFAULT "" NOT NULL, trans_to VARCHAR(30) DEFAULT "" NOT NULL, trans_amount MEDIUMINT(8) DEFAULT "0" NOT NULL, trans_reason VARCHAR(255) DEFAULT "" NOT NULL, PRIMARY KEY (trans_date)) TYPE=MYISAM';

$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'unique_hits';
$sql[] = 'CREATE TABLE '. $table_prefix .'unique_hits (user_ip char(8) DEFAULT "0" NOT NULL, time int(11) DEFAULT "0" NOT NULL, INDEX (user_ip)) TYPE=MYISAM';

$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'user_notes';
$sql[] = 'CREATE TABLE '. $table_prefix . 'user_notes (`post_id` MEDIUMINT(8) UNSIGNED NOT NULL auto_increment, `poster_id` MEDIUMINT(8) DEFAULT "0" NOT NULL, `post_subject` VARCHAR(120) DEFAULT NULL, `post_text` TEXT, `post_time` INT(11) DEFAULT "0" NOT NULL, `bbcode_uid` CHAR(10) DEFAULT "" NOT NULL, `bbcode` TINYINT(1) DEFAULT "1" NOT NULL, `smilies` TINYINT(1) DEFAULT "1" NOT NULL, `acronym` TINYINT(1) DEFAULT "1" NOT NULL, PRIMARY KEY (post_id), KEY poster_id (poster_id), KEY post_time (post_time)) TYPE=MYISAM';
	
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'user_shops';
$sql[] = 'CREATE TABLE '. $table_prefix . 'user_shops (`id` INT(5) NOT NULL auto_increment, `user_id` INT(10) NOT NULL, `username` VARCHAR(32) NOT NULL, `shop_name` VARCHAR(32) NOT NULL, `shop_type` VARCHAR(32) NOT NULL, `shop_opened` INT(30) NOT NULL, `shop_updated` INT(30) NOT NULL, `shop_status` INT(1) DEFAULT "0" NOT NULL, `amount_earnt` INT(10) DEFAULT "0" NOT NULL, `amount_holding` INT(10) DEFAULT "0" NOT NULL, `items_sold` INT(10) DEFAULT "0" NOT NULL, `items_holding` INT(10) UNSIGNED DEFAULT "0" NOT NULL, PRIMARY KEY (`user_id`), INDEX (`id`)) TYPE=MYISAM';
  			
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'user_shops_items';
$sql[] = 'CREATE TABLE '. $table_prefix . 'user_shops_items (`id` INT(10) NOT NULL auto_increment, `shop_id` INT(10) NOT NULL, `item_id` INT(10) NOT NULL, `seller_notes` VARCHAR(255) NOT NULL, `cost` INT(10) NOT NULL, `time_added` MEDIUMINT(30) NOT NULL, INDEX (`shop_id`), PRIMARY KEY (`id`)) TYPE=MYISAM';		
	
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'users_comments';
$sql[] = 'CREATE TABLE '. $table_prefix .'users_comments (comment_id MEDIUMINT(8) auto_increment NOT NULL, user_id MEDIUMINT(8) NOT NULL, poster_id MEDIUMINT(8) NOT NULL, comments TEXT NOT NULL, time INT(11) DEFAULT NULL, PRIMARY KEY (comment_id)) TYPE=MYISAM';
	
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'xdata_auth';
$sql[] = 'CREATE TABLE '. $table_prefix . 'xdata_auth (`field_id` SMALLINT(5) UNSIGNED NOT NULL, `group_id` MEDIUMINT(8) UNSIGNED NOT NULL, `auth_value` TINYINT(1) NOT NULL) TYPE=MyISAM';

$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'xdata_data';
$sql[] = 'CREATE TABLE '. $table_prefix . 'xdata_data (`field_id` SMALLINT(5) UNSIGNED NOT NULL, `user_id` MEDIUMINT(8) UNSIGNED NOT NULL, `xdata_value` TEXT NOT NULL) TYPE=MyISAM';

$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'xdata_fields';
$sql[] = 'CREATE TABLE '. $table_prefix . 'xdata_fields (`field_id` SMALLINT(5) UNSIGNED NOT NULL, `field_name` VARCHAR(255) NOT NULL DEFAULT "", `field_desc` TEXT NOT NULL DEFAULT "", `field_type` VARCHAR(255) NOT NULL DEFAULT "", `field_order` SMALLINT(5) UNSIGNED NOT NULL DEFAULT "0", `code_name` VARCHAR(255) NOT NULL DEFAULT "", `field_length` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT "0", `field_values` TEXT NOT NULL DEFAULT "", `field_regexp` TEXT NOT NULL DEFAULT "", `default_auth` TINYINT(1) NOT NULL DEFAULT "1", `display_register` TINYINT(1) NOT NULL DEFAULT "1", `display_viewprofile` TINYINT(1) NOT NULL DEFAULT "0", `display_posting` TINYINT(1) NOT NULL DEFAULT "0", `handle_input` TINYINT(1) NOT NULL DEFAULT "0", `allow_html` TINYINT(1) NOT NULL DEFAULT "0", `allow_bbcode` TINYINT(1) NOT NULL DEFAULT "0", `allow_smilies` TINYINT(1) NOT NULL DEFAULT "0", PRIMARY KEY (field_id), UNIQUE KEY code_name (code_name)) TYPE=MyISAM';

//
// Extra phpBB MOD tables that may be present but aren't required
// The attempted blind freddy clean-up ... bump!!
//
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'admin_categories';
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'admin_nav_module';
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'admin_notes';
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'album_sp_config';
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'anti_robotic_reg';
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'buddy_list';
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'counter';
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'foing_config';
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'foing_albums';
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'foing_artists';
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'foing_artist_desc';
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'foing_genres';
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'foing_playlist';
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'foing_playlist_data';
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'foing_song_status';
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'foing_songs';
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'foing_status';
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'foing_song_desc';
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'forums_config';
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'gk_data';
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'gk_quiz';
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'gk_quiz_as';
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'gk_quiz_qs';
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'gk_quiz_quizers';
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'im_sitetosite';
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'kb_config';
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'kb_pretext';
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'lexicon_config';
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'links_cat';
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'links_url';
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'links_urlincat';
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'lottery_winner';
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'moddb';
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'news';
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'notes_admin';
$sql[] = 'DROP TABLE IF EXISTS '. $table_prefix .'notes';

?>