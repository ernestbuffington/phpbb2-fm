<?php
	
if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}


//
// Modify phpBB core-scema	
//
$sql = 'ALTER TABLE ' . $table_prefix . 'categories MODIFY COLUMN `cat_icon` VARCHAR(255) NOT NULL';			
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $table_prefix . 'forums MODIFY COLUMN `forum_icon` VARCHAR(255) NOT NULL';			
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'forums ADD COLUMN `image_ever_thumb` TINYINT(1) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'forums MODIFY COLUMN `forum_id` MEDIUMINT(5) NOT NULL auto_increment';
_sql($sql, $errored, $error_ary);
			
$sql = 'ALTER TABLE ' . $table_prefix . 'themes ADD COLUMN `theme_public` TINYINT(1) DEFAULT "1" NOT NULL';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $table_prefix . 'users ADD COLUMN `user_profile_view_popup` TINYINT(1) DEFAULT "1"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'users ADD COLUMN `user_popup_notes` TINYINT(1) DEFAULT "0"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'users ADD COLUMN `user_trade` TEXT';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'users ADD COLUMN `user_showavatars` TINYINT(1) DEFAULT "1"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'users ADD COLUMN `karma_plus` MEDIUMINT DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'users ADD COLUMN `karma_minus` MEDIUMINT DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'users ADD COLUMN `karma_time` BIGINT DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'users ADD COLUMN `user_realname` VARCHAR(50)';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'users ADD COLUMN `user_showsigs` TINYINT(1) DEFAULT "1"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'users ADD COLUMN `irc_commands` VARCHAR(255) NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'users MODIFY COLUMN `user_profile_view` SMALLINT(5) UNSIGNED DEFAULT "0" NOT NULL';			
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'users MODIFY COLUMN `user_birthday` MEDIUMINT(6) DEFAULT "999999" NOT NULL';			
_sql($sql, $errored, $error_ary);
			

//
// Modify Fully Modded core-schema
//
$sql = 'ALTER TABLE ' . $table_prefix . 'album_rate ADD COLUMN `rate_hon_point` TINYINT(3) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
			
$sql = 'ALTER TABLE ' . $table_prefix . 'album_comment ADD COLUMN `comment_cat_id` INT(11) NOT NULL';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $table_prefix . 'banner ADD COLUMN `banner_type` MEDIUMINT(5) NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'banner ADD COLUMN `banner_width` MEDIUMINT(5) UNSIGNED NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'banner ADD COLUMN `banner_height` MEDIUMINT(5) UNSIGNED NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'banner ADD COLUMN `banner_filter` TINYINT(1) NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'banner ADD COLUMN `banner_filter_time` MEDIUMINT(5) DEFAULT "600" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'banner MODIFY COLUMN `banner_name` TEXT NOT NULL';			
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $table_prefix . 'config_nav ADD COLUMN `use_lang` TINYINT(1) DEFAULT "1" NOT NULL';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $table_prefix . 'notes ADD COLUMN `bbcode_uid` VARCHAR(10) DEFAULT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'notes ADD COLUMN `bbcode` TINYINT(1) DEFAULT "1" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'notes ADD COLUMN `smilies` TINYINT(1) DEFAULT "1" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'notes ADD COLUMN `acronym` TINYINT(1) DEFAULT "1" NOT NULL';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $table_prefix . 'pa_config DROP PRIMARY KEY';
_sql($sql, $errored, $error_ary);
			
$sql = 'ALTER TABLE ' . $table_prefix . 'pa_votes DROP INDEX `votes_file`';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'pa_votes DROP INDEX `votes_ip`';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'pa_votes DROP INDEX `voter_os`';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'pa_votes DROP INDEX `voter_browser`';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'pa_votes DROP INDEX `browser_version`';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'pa_votes DROP INDEX `browser_version_2`';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'pa_votes DROP INDEX `rate_point`';
_sql($sql, $errored, $error_ary);
			 
$sql = 'ALTER TABLE ' . $table_prefix . 'pa_cat ADD COLUMN `cat_allow_ratings` TINYINT(2) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'pa_cat ADD COLUMN `cat_allow_comments` TINYINT(2) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'pa_cat ADD COLUMN `cat_files` MEDIUMINT(8) DEFAULT "-1" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'pa_cat ADD COLUMN `cat_last_file_id` MEDIUMINT(8) UNSIGNED DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'pa_cat ADD COLUMN `cat_last_file_name` VARCHAR(255) NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'pa_cat ADD COLUMN `cat_last_file_time` INT(50) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
			 
$sql = 'ALTER TABLE ' . $table_prefix . 'pa_files ADD COLUMN `file_size` INT(20) NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'pa_files ADD COLUMN `unique_name` VARCHAR(255) NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'pa_files ADD COLUMN `real_name` VARCHAR(255) NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'pa_files ADD COLUMN `file_dir` VARCHAR(255) NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'pa_files ADD COLUMN `file_broken` TINYINT(1) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $table_prefix . 'portal ADD COLUMN `portal_karma` TINYINT(1)';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'portal DROP COLUMN `portal_order`';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'portal DROP COLUMN `portal_bbcode_id`';
_sql($sql, $errored, $error_ary);
			
$sql = 'ALTER TABLE ' . $table_prefix . 'referers ADD COLUMN `referer_host` VARCHAR(255) NOT NULL DEFAULT ""';			
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'referers ADD COLUMN `referer_url` VARCHAR(255) DEFAULT "" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'referers ADD COLUMN `referer_ip` VARCHAR(8) DEFAULT "" NOT NULL';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $table_prefix . 'shop_items ADD COLUMN `accessforum` INT(4) DEFAULT "0"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'shop_items DROP COLUMN `startprice`';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'shop_items DROP COLUMN `raise`';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'shop_items DROP COLUMN `startstock`';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $table_prefix . 'toplist ADD COLUMN `ip` VARCHAR(8) NOT NULL';
_sql($sql, $errored, $error_ary);


//
// Create new Fully Modded core-schema
//
$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'album_sp_config';			
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'banner_stats';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'banner_stats (banner_id MEDIUMINT(8) UNSIGNED NOT NULL, click_date INT(11) NOT NULL, click_ip CHAR(8) NOT NULL, click_user MEDIUMINT(8) NOT NULL, user_duration INT(11) NOT NULL)';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'forum_tour';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'forum_tour (page_id MEDIUMINT(8) UNSIGNED NOT NULL, page_subject VARCHAR(60) null, page_text text, page_sort MEDIUMINT(8) NOT NULL, bbcode_uid VARCHAR(10) null, page_access MEDIUMINT(8) NOT NULL, KEY (page_id))';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'helpdesk_emails';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'helpdesk_emails (e_id int(10) DEFAULT "0" NOT NULL, e_addr VARCHAR(255) DEFAULT NULL)';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'helpdesk_importance';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'helpdesk_importance (value int(10) DEFAULT "0", data text)';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'helpdesk_msgs';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'helpdesk_msgs (e_id int(10) DEFAULT "0", e_msg VARCHAR(255) DEFAULT NULL)';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'helpdesk_reply';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'helpdesk_reply (value int(10) DEFAULT "0", data text)';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'im_config';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'im_config (config_name VARCHAR(255) DEFAULT "" NOT NULL, config_value VARCHAR(255) DEFAULT "" NOT NULL, PRIMARY KEY (config_name))';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'im_buddy_list';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'im_buddy_list (user_id MEDIUMINT(8) DEFAULT "0" NOT NULL, contact_id MEDIUMINT(8) DEFAULT "0" NOT NULL, user_ignore TINYINT(1) DEFAULT "0" NOT NULL, alert TINYINT(1) DEFAULT "0" NOT NULL, alert_status TINYINT(1) DEFAULT "0" NOT NULL, disallow TINYINT(1) DEFAULT "0" NOT NULL, display_name VARCHAR(25) DEFAULT "" NOT NULL, KEY contact_id (contact_id), KEY user_id (user_id))';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'im_prefs';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'im_prefs (user_id MEDIUMINT(8) DEFAULT "0" NOT NULL, user_allow_ims TINYINT(1) DEFAULT "0" NOT NULL, user_allow_shout TINYINT(1) DEFAULT "1" NOT NULL, user_allow_chat TINYINT(1) DEFAULT "1" NOT NULL, user_allow_network TINYINT(1) DEFAULT "0" NOT NULL, admin_allow_ims TINYINT(1) DEFAULT "1" NOT NULL, admin_allow_shout TINYINT(1) DEFAULT "1" NOT NULL, admin_allow_chat TINYINT(1) DEFAULT "1" NOT NULL, admin_allow_network TINYINT(1) DEFAULT "1" NOT NULL, new_ims SMALLINT(5) UNSIGNED DEFAULT "0" NOT NULL, unread_ims SMALLINT(5) UNSIGNED DEFAULT "0" NOT NULL, read_ims SMALLINT(5) UNSIGNED DEFAULT "0" NOT NULL, total_sent MEDIUMINT(8) UNSIGNED DEFAULT "0" NOT NULL, attach_sig TINYINT(1) DEFAULT "1" NOT NULL, refresh_rate SMALLINT(5) UNSIGNED DEFAULT "120" NOT NULL, success_close TINYINT(1) DEFAULT "1" NOT NULL, refresh_method TINYINT(1) DEFAULT "1" NOT NULL, auto_launch TINYINT(1) DEFAULT "1" NOT NULL, popup_ims TINYINT(1) DEFAULT "1" NOT NULL, list_ims TINYINT(1) DEFAULT "0" NOT NULL, show_controls TINYINT(1) DEFAULT "1" NOT NULL, list_all_online TINYINT(1) DEFAULT "1" NOT NULL, default_mode TINYINT(1) DEFAULT "1" NOT NULL, current_mode TINYINT(1) DEFAULT "1" NOT NULL, mode1_height VARCHAR(4) DEFAULT "400" NOT NULL, mode1_width VARCHAR(4) DEFAULT "230" NOT NULL, mode2_height VARCHAR(4) DEFAULT "225" NOT NULL, mode2_width VARCHAR(4) DEFAULT "400" NOT NULL, mode3_height VARCHAR(4) DEFAULT "100" NOT NULL, mode3_width VARCHAR(4) DEFAULT "400" NOT NULL, prefs_width VARCHAR(4) DEFAULT "500" NOT NULL, prefs_height VARCHAR(4) DEFAULT "350" NOT NULL, read_height VARCHAR(4) DEFAULT "3000" NOT NULL, read_width VARCHAR(4) DEFAULT "400" NOT NULL, send_height VARCHAR(4) DEFAULT "365" NOT NULL, send_width VARCHAR(4) DEFAULT "460" NOT NULL, play_sound TINYINT(1) DEFAULT "1" NOT NULL, default_sound TINYINT(1) DEFAULT "1" NOT NULL, sound_name VARCHAR(255) DEFAULT NULL, themes_id MEDIUMINT(8) UNSIGNED DEFAULT "1" NOT NULL, network_user_list TINYINT(1) DEFAULT "1" NOT NULL, open_pms TINYINT(1) DEFAULT "0" NOT NULL, auto_delete TINYINT(1) DEFAULT "0" NOT NULL, use_frames TINYINT(1) DEFAULT "1" NOT NULL, user_override TINYINT(1) DEFAULT "0" NOT NULL, PRIMARY KEY (user_id))';
_sql($sql, $errored, $error_ary);
		
$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'im_sitetosite';
_sql($sql, $errored, $error_ary);
$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'im_sessions';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'im_sessions (session_user_id MEDIUMINT(8) DEFAULT "0" NOT NULL, session_id CHAR(32) DEFAULT "" NOT NULL, session_time INT(11) DEFAULT "0" NOT NULL, session_popup TINYINT(1) DEFAULT "0" NOT NULL, PRIMARY KEY (session_id), KEY session_user_id (session_user_id))';
_sql($sql, $errored, $error_ary);
			
$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'im_sites';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'im_sites (site_id MEDIUMINT(8) NOT NULL auto_increment, site_name VARCHAR(50) NOT NULL, site_url VARCHAR(100) NOT NULL, site_phpex VARCHAR(4) DEFAULT "php" NOT NULL, site_profile VARCHAR(50) DEFAULT "profile" NOT NULL, site_enable TINYINT(1) DEFAULT "1" NOT NULL, PRIMARY KEY (site_id))';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'lottery_winner';
_sql($sql, $errored, $error_ary);
$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'lottery_history';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'lottery_history (id int UNSIGNED NOT NULL auto_increment, user_id int(20) NOT NULL, amount int(20) NOT NULL, currency char(32) NOT NULL, time int(20) NOT NULL, PRIMARY KEY (id), INDEX (user_id))';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'meeting_comment';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'meeting_comment (meeting_id MEDIUMINT(8) UNSIGNED NOT NULL, user_id MEDIUMINT(8) DEFAULT "0" NOT NULL, meeting_comment text NOT NULL, meeting_edit_time int(11) DEFAULT "0" NOT NULL)';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'meeting_data';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'meeting_data (meeting_id MEDIUMINT(8) UNSIGNED NOT NULL, meeting_time int(11) DEFAULT "0" NOT NULL, meeting_until int(11) DEFAULT "0" NOT NULL, meeting_location VARCHAR(255) NOT NULL, meeting_subject VARCHAR(255) NOT NULL, meeting_desc text NOT NULL, meeting_link VARCHAR(255) NOT NULL, meeting_places MEDIUMINT(8) DEFAULT "0" NOT NULL, PRIMARY KEY (meeting_id))';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'meeting_user';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'meeting_user (meeting_id MEDIUMINT(8) UNSIGNED NOT NULL, user_id MEDIUMINT(8) DEFAULT "0" NOT NULL, meeting_sure tinyint(4) DEFAULT "0" NOT NULL)';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'meeting_usergroup';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'meeting_usergroup (meeting_id MEDIUMINT(8) UNSIGNED NOT NULL, meeting_group MEDIUMINT(8) NOT NULL)';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'notes_admin';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'notes_admin (id MEDIUMINT(8) NOT NULL, text text)';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'pa_auth';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE '. $table_prefix . 'pa_auth (group_id MEDIUMINT(8) DEFAULT "0" NOT NULL, cat_id SMALLINT(5) UNSIGNED DEFAULT "0" NOT NULL, auth_view TINYINT(1) DEFAULT "0" NOT NULL, auth_read TINYINT(1) DEFAULT "0" NOT NULL, auth_view_file TINYINT(1) DEFAULT "0" NOT NULL, auth_edit_file TINYINT(1) NOT NULL, auth_delete_file TINYINT(1) NOT NULL, auth_upload TINYINT(1) DEFAULT "0" NOT NULL, auth_download TINYINT(1) DEFAULT "0" NOT NULL, auth_rate TINYINT(1) DEFAULT "0" NOT NULL, auth_email TINYINT(1) DEFAULT "0" NOT NULL, auth_view_comment TINYINT(1) DEFAULT "0" NOT NULL, auth_post_comment TINYINT(1) DEFAULT "0" NOT NULL, auth_edit_comment TINYINT(1) DEFAULT "0" NOT NULL, auth_delete_comment TINYINT(1) DEFAULT "0" NOT NULL, auth_mod TINYINT(1) DEFAULT "1" NOT NULL, auth_search TINYINT(1) DEFAULT "1" NOT NULL, auth_stats TINYINT(1) NOT NULL, auth_toplist TINYINT(1) NOT NULL, auth_viewall TINYINT(1) DEFAULT "1" NOT NULL, KEY group_id (group_id), KEY cat_id (cat_id))';
_sql($sql, $errored, $error_ary);
			
$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'pa_mirrors';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'pa_mirrors (mirror_id MEDIUMINT(8) NOT NULL auto_increment, file_id INT(10) NOT NULL, unique_name VARCHAR(255) DEFAULT "" NOT NULL, file_dir VARCHAR(255) NOT NULL, file_dlurl VARCHAR(255) DEFAULT "" NOT NULL, mirror_location VARCHAR(255) DEFAULT "" NOT NULL, PRIMARY KEY (mirror_id), KEY file_id (file_id)) TYPE=MYISAM';
_sql($sql, $errored, $error_ary);
				
$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'points_logger';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'points_logger (id MEDIUMINT(8) auto_increment, admin VARCHAR(25) NOT NULL, person VARCHAR(25) NOT NULL, add_sub VARCHAR(50) NOT NULL, total MEDIUMINT(8) DEFAULT "0" NOT NULL, time int(11) DEFAULT "0" NOT NULL, PRIMARY KEY (id))';
_sql($sql, $errored, $error_ary);


//
// Modify phpBB core-data
// phpbb_config data
$config_data = array( 'avatar_posts' => 0, 'avatars_per_page' => 20, 'karma_admins' => 1, 'karma_flood_interval' => 1, 'lottery_currency' => '', 'lottery_history' => 1, 'lottery_item_mcost' => 1, 'lottery_item_xcost' => 500, 'lottery_items' => 0, 'lottery_mb' => 0, 'lottery_mb_amount' => 1, 'lottery_random_shop' => '', 'lottery_show_entries' => 0, 'lottery_win_items' => '', 'registration_notify' => 0, 'shop_give' => 0, 'shop_invlimit' => 0, 'shop_orderby' => 'name', 'shop_trade' => 0, 'smilie_max_filesize' => 6144, 'smilie_max_height' => 30, 'smilie_max_width' => 30, 'toplist_count_hin_hits' => 1, 'toplist_count_img_hits' => 1, 'toplist_count_out_hits' => 1, 'uniquehits_time' => 1440 );
while ( list ( $config_name, $config_value ) = each ( $config_data ) )
{
	$sql = "INSERT INTO " . $table_prefix . "config (`config_name`, `config_value`) VALUES ('" . $config_name . "', '" . $config_value . "')";
	_sql($sql, $errored, $error_ary);
}
			
$rconfig_data = array( 'im_allow_chat', 'im_allow_ims', 'im_allow_s2s', 'im_allow_shout', 'im_allow_sound', 'im_auto_delete', 'im_auto_launch', 'im_box_limit', 'im_default_sound', 'im_enable_flood', 'im_enable_im_limit', 'im_flood_interval', 'im_list_all_online', 'im_list_ims', 'im_main_height', 'im_main_width', 'im_online_height', 'im_online_width', 'im_open_pms', 'im_override_users', 'im_popup_ims', 'im_read_height', 'im_read_width', 'im_refresh_drop', 'im_refresh_method', 'im_refresh_rate', 'im_s2s_user_list', 'im_send_height', 'im_send_width', 'im_session_length', 'im_show_controls', 'im_sound_name', 'im_success_close', 'im_themes_allow', 'im_themes_id', 'im_version', 'lottery_lastwon', 'lottery_explain', 'logo_width', 'logo_height', 'logo_align', 'site_logo', 'shopstopper' );
for ( $i = 0; $i < sizeof($rconfig_data); $i++ )
{
	$sql = "DELETE FROM " . $table_prefix . "config WHERE `config_name` = '" . $rconfig_data[$i] . "'";
	_sql($sql, $errored, $error_ary);
}

// phpbb_forums data
$sql = "INSERT IGNORE INTO " . $table_prefix . "forums (`forum_id`, `cat_id`, `forum_name`, `forum_desc`, `forum_status`, `auth_view`, `auth_read`) VALUES ('-42', '0', '* KNOWLEDGE BASE PERMISSIONS', 'Knowledge Base Control', '1', '5', '5')";
_sql($sql, $errored, $error_ary);
			
// Modify Fully Modded core-data
// phpbb_album_config data
$album_config_data = array( 'disp_high' => 1, 'disp_late' => 1, 'disp_rand' => 1, 'disp_watermark_at' => 3, 'hon_rate_sep' => 1, 'hon_rate_times' => 1, 'hon_rate_users' => 1, 'hon_rate_where' => '', 'img_cols' => 4, 'img_rows' => 2, 'midthumb_cache' => 1, 'midthumb_use' => 1, 'midthumb_height' => 600, 'midthumb_width' => 800, 'rate_type' => 2, 'use_watermark' => 0, 'wut_users' => 0 );
while ( list ( $config_name, $config_value ) = each ( $album_config_data ) )
{
	$sql = "INSERT INTO " . $table_prefix . "album_config (`config_name`, `config_value`) VALUES ('" . $config_name . "', '" . $config_value . "')";
	_sql($sql, $errored, $error_ary);
}
	
// phpbb_config_nav data
$config_nav_data = array( 
	array( Img => 'icon_mini_faq.gif', Alt => 'Tour', Url => 'javascript:tour()', Value => 0 ),
	array( Img => 'icon_mini_staff.gif', Alt => 'Helpdesk', Url => 'helpdesk.php', Value => 0 )
);
for ( $row = 0; $row < sizeof($config_nav_data); $row++ )
{
	for ( $column = 0; $column < 1; $column++ )
	{
		$sql = "INSERT INTO " . $table_prefix . "config_nav (`img`, `alt`, `use_lang`, `url`, `nav_order`, `value`) VALUES ('" . $config_nav_data[$row]['Img'] . "', '" . $config_nav_data[$row]['Alt'] . "', '1', '" . $config_nav_data[$row]['Url'] . "', '300', '" . $config_nav_data[$row]['Value'] . "')";
		_sql($sql, $errored, $error_ary);
	}
}
			
$sql = 'DELETE FROM ' . $table_prefix . 'config_nav WHERE `url` = "kb.php"';
_sql($sql, $errored, $error_ary);

// phpbb_helpdesk_emails data
$id = 1;
$helpdesk_emails_data = array($board_config['board_email'], $board_config['board_email']);
for ( $i = 0; $i < sizeof($helpdesk_emails_data); $i++ )
{
	$sql = "INSERT INTO " . $table_prefix . "helpdesk_emails (`e_id`, `e_addr`) VALUES ('" . $id. "', '" . $helpdesk_emails_data[$i] . "')";
	_sql($sql, $errored, $error_ary);
	$id++;
}
		
// phpbb_helpdesk_importance data
$id = 1;
$helpdesk_importance_data = array('Not Important', 'Urgent');
for ( $i = 0; $i < sizeof($helpdesk_importance_data); $i++ )
{
	$sql = "INSERT INTO " . $table_prefix . "helpdesk_importance (`value`, `data`) VALUES ('" . $id . "', '" . $helpdesk_importance_data[$i] . "')";
	_sql($sql, $errored, $error_ary);
	$id++;
}
		
// phpbb_helpdesk_msgs data
$id = 1;
$helpdesk_msgs_data = array('Forum Issues', 'E-mail Board Owner');
for ( $i = 0; $i < sizeof($helpdesk_msgs_data); $i++ )
{
	$sql = "INSERT INTO " . $table_prefix . "helpdesk_msgs (`e_id`, `e_msg`) VALUES ('" . $id . "', '" . $helpdesk_msgs_data[$i] . "')";
	_sql($sql, $errored, $error_ary);
	$id++;
}				

// phpbb_helpdesk_reply data
$id = 1;
$helpdesk_reply_data = array('None', 'E-mail Me', 'Private message', 'Yahoo Messenger', 'MSN Messenger', 'AOL Messenger', 'ICQ Message');
for ( $i = 0; $i < sizeof($helpdesk_reply_data); $i++ )
{
	$sql = "INSERT INTO " . $table_prefix . "helpdesk_reply (`value`, `data`) VALUES ('" . $id . "', '" . $helpdesk_reply_data[$i] . "')";
	_sql($sql, $errored, $error_ary);
	$id++;
}		

// phpbb_im_config data
$im_config_data = array( 'allow_chat' => 1, 'allow_ims' => 0, 'allow_network' => 1, 'allow_shout' => 1, 'allow_sound' => 1, 'auto_delete' => 0, 'auto_launch' => 0, 'box_limit' => 25, 'default_mode' => 1, 'default_sound' => 0, 'enable_flood' => 0, 'enable_im_limit' => 1, 'flood_interval' => 15, 'im_version' => '0.7.1', 'list_all_online' => 1, 'list_ims' => 0, 'mode1_height' => 400, 'mode1_width' => 230, 'mode2_height' => 2520, 'mode2_width' => 400, 'mode3_height' => 100, 'mode3_width' => 400, 'network_profile' => 'profile', 'network_user_list' => 1, 'open_pms' => 0, 'override_users' => 0, 'popup_ims' => 1, 'prefs_height' => 350, 'prefs_width' => 500, 'read_height' => 300, 'read_width' => 400, 'refresh_drop' => 1, 'refresh_method' => 2, 'refresh_rate' => 60, 'send_height' => 365, 'send_width' => 460, 'session_length' => 120, 'show_controls' => 1, 'sound_name' => '', 'success_close' => 1, 'themes_allow' => 1, 'themes_id' => 1, 'use_frames' => 1 );
while ( list ( $config_name, $config_value ) = each ( $im_config_data ) )
{
	$sql = "INSERT INTO " . $table_prefix . "im_config (`config_name`, `config_value`) VALUES ('" . $config_name . "', '" . $config_value . "')";
	_sql($sql, $errored, $error_ary);
}

// phpbb_im_prefs data
$sql = "INSERT INTO " . $table_prefix . "im_prefs (`user_id`) VALUES ('0')";
_sql($sql, $errored, $error_ary);

// phpbb_im_sites data
$sql = "INSERT INTO " . $table_prefix . "im_sites (`site_id`, `site_name`, `site_url`, `site_phpex`, `site_profile`, `site_enable`) VALUES ('1', 'Fully Modded phpBB', 'http://phpbb-fm.com/', 'php', 'profile', '1')";
_sql($sql, $errored, $error_ary);

// phpbb_notes_admin data
$sql = "INSERT INTO " . $table_prefix . "notes_admin (`id`, `text`) VALUES ('1', 'This is just an Admin test note.')";
_sql($sql, $errored, $error_ary);

// phpbb_pa_config data
$pa_config_data = array( 'auth_search' => 0, 'auth_stats' => 0, 'auth_toplist' => 0, 'auth_viewall' => 0, 'forbidden_extensions' => 'php, php3, php4, phtml, pl, asp, aspx, cgi', 'max_file_size' => 262144, 'need_validation' => 0, 'pm_notify' => 0, 'screenshots_dir' => 'uploads/pafiledb/screenshots/', 'upload_dir' => 'uploads/pafiledb/', 'validator' => 'validator_admin' );
while ( list ( $config_name, $config_value ) = each ( $pa_config_data ) )
{
	$sql = "INSERT INTO " . $table_prefix . "pa_config (`config_name`, `config_value`) VALUES ('" . $config_name . "', '" . $config_value . "')";
	_sql($sql, $errored, $error_ary);
}


//
// Change default values to sync with FullyModded setup
//
$sql = 'UPDATE ' . $table_prefix . 'forums SET `image_ever_thumb` = "0"';
_sql($sql, $errored, $error_ary);

$sql = 'UPDATE ' . $table_prefix . 'config SET `config_value` = "0" WHERE `config_name` = "lottery_ticktype"';
_sql($sql, $errored, $error_ary);

$sql = 'UPDATE ' . $table_prefix . 'config SET `config_value` = "0" WHERE `config_name` = "bankopened"';
_sql($sql, $errored, $error_ary);

$sql = 'UPDATE ' . $table_prefix . 'config SET `config_value` = "0" WHERE `config_name` = "lottery_reset"';
_sql($sql, $errored, $error_ary);

$sql = 'UPDATE ' . $table_prefix . 'config SET `config_value` = "0" WHERE `config_name` = "lottery_status"';
_sql($sql, $errored, $error_ary);

$sql = 'UPDATE ' . $table_prefix . 'config SET `config_value` = "1.3.7" WHERE `config_name` = "toplist_version"';
_sql($sql, $errored, $error_ary);

$sql = 'UPDATE '. $table_prefix . 'themes SET `head_stylesheet` = "subSilver.php" WHERE `template_name` = "subSilver"';	
_sql($sql, $errored, $error_ary);

$sql = 'UPDATE ' . $table_prefix . 'config_nav SET `use_lang` = "1"';
_sql($sql, $errored, $error_ary);

$sql = 'UPDATE ' . $table_prefix . 'users SET `user_gender` = "0" WHERE `user_gender` = "3"';
_sql($sql, $errored, $error_ary);

// Update photo comments
$sql = 'SELECT `comment_id`, `comment_pic_id` FROM ' . $table_prefix . 'album_comment';
$result = _sql($sql, $errored, $error_ary);
while ($row = $db->sql_fetchrow($result))
{
	$comment_id = $row['comment_id'];
	$pic_id = $row['comment_pic_id'];
		
	// Get category id from album table
	$sql = 'SELECT `pic_cat_id` FROM ' . $table_prefix . 'album WHERE `pic_id` = ' . $pic_id;
	$cat_result = _sql($sql, $errored, $error_ary);
	while ($row = $db->sql_fetchrow($cat_result))
	{
		$cat_id = $cat_row['pic_cat_id'];
		
		// Update the category id in comment table
		$sql = 'UPDATE ' . $table_prefix . 'album_comment SET `comment_cat_id` = ' . $cat_id . ' WHERE `comment_pic_id` = ' . $pic_id;
		$result = _sql($sql, $errored, $error_ary);
	}
	$db->sql_freeresult($cat_result);
}
$db->sql_freeresult($result);

?>