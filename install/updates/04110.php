<?php
	
if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}


//
// Modify phpBB core-scema	
//
$sql = 'ALTER TABLE ' . $prefix . 'auth_access ADD COLUMN `auth_suggest_event` tinyint(2) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $prefix . 'forums ADD COLUMN `auth_suggest_event` tinyint(2) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'forums ADD COLUMN `is_default` tinyint(2) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'forums ADD COLUMN `events_forum` TINYINT(1) DEFAULT "0"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'forums ADD COLUMN `points_disabled` TINYINT(1) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $prefix . 'posts ADD COLUMN `post_icon` TINYINT(2) UNSIGNED DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $prefix . 'topics ADD COLUMN `topic_icon` tinyint(2) UNSIGNED DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $prefix . 'users ADD COLUMN `user_items` TEXT';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'users ADD COLUMN `user_effects` CHAR(255)';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'users ADD COLUMN `user_privs` CHAR(255)';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'users ADD COLUMN `user_custitle` TEXT';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'users ADD COLUMN `user_specmsg` TEXT';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'users ADD COLUMN `user_notify_donation` TINYINT(1) NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'users ADD COLUMN `user_points` MEDIUMINT(8) UNSIGNED NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'users ADD COLUMN `user_zipcode` VARCHAR(10)';
_sql($sql, $errored, $error_ary);
		

//
// Create new Fully Modded core-schema
//
$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'album';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $prefix . 'album (`pic_id` INT(11) UNSIGNED NOT NULL auto_increment, `pic_filename` VARCHAR(255) NOT NULL, `pic_thumbnail` VARCHAR(255), `pic_title` VARCHAR(255) NOT NULL, `pic_user_id` MEDIUMINT(8) NOT NULL, `pic_user_ip` char(8) DEFAULT "0" NOT NULL, `pic_time` int(11) UNSIGNED NOT NULL, `pic_cat_id` MEDIUMINT(8) UNSIGNED DEFAULT "1" NOT NULL, `pic_view_count` int(11) UNSIGNED DEFAULT "0" NOT NULL, `pic_lock` tinyint(3) DEFAULT "0" NOT NULL, `pic_username` VARCHAR(32), `pic_approval` tinyint(3) DEFAULT "1" NOT NULL, PRIMARY KEY (pic_id), KEY pic_cat_id(pic_cat_id), KEY pic_user_id(pic_user_id), KEY pic_time(pic_time))';
_sql($sql, $errored, $error_ary);
			
$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'album_cat';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $prefix . 'album_cat (`cat_id` MEDIUMINT(8) UNSIGNED NOT NULL auto_increment, `cat_title` VARCHAR(255) NOT NULL, cat_desc TEXT, cat_order MEDIUMINT(8) NOT NULL, cat_view_level tinyint(3) DEFAULT "-1" NOT NULL, cat_upload_level tinyint(3) DEFAULT "0" NOT NULL, cat_rate_level tinyint(3) DEFAULT "0" NOT NULL, cat_comment_level tinyint(3) DEFAULT "0" NOT NULL, cat_edit_level tinyint(3) DEFAULT "0" NOT NULL, cat_delete_level tinyint(3) DEFAULT "2" NOT NULL, cat_moderator_groups VARCHAR(255), cat_approval tinyint(3) DEFAULT "0" NOT NULL, PRIMARY KEY (cat_id), KEY cat_order(cat_order))';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'album_config';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $prefix . 'album_config (`config_name` VARCHAR(255) NOT NULL, `config_value` VARCHAR(255) NOT NULL, PRIMARY KEY (config_name))';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'album_comment';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $prefix . 'album_comment (comment_id INT(11) UNSIGNED NOT NULL auto_increment, comment_pic_id int(11) UNSIGNED NOT NULL, comment_user_id MEDIUMINT(8) NOT NULL, comment_user_ip char(8) NOT NULL, comment_time int(11) UNSIGNED NOT NULL, comment_text TEXT, comment_edit_time int(11) UNSIGNED, comment_edit_count smallint(5) UNSIGNED DEFAULT "0" NOT NULL, comment_edit_user_id MEDIUMINT(8), comment_username VARCHAR(32), PRIMARY KEY (comment_id), KEY comment_pic_id(comment_pic_id), KEY comment_user_id(comment_user_id), KEY comment_user_ip(comment_user_ip), KEY comment_time(comment_time))';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'album_rate';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $prefix . 'album_rate (rate_pic_id INT(11) UNSIGNED NOT NULL, rate_user_id MEDIUMINT(8) NOT NULL, rate_user_ip char(8) NOT NULL, rate_point tinyint(3) UNSIGNED NOT NULL, KEY rate_pic_id (rate_pic_id), KEY rate_user_ip(rate_user_ip), KEY rate_point(rate_point))';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'bank';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $prefix . 'bank (id int UNSIGNED NOT NULL auto_increment, name CHAR(50) NOT NULL, holding int(20) UNSIGNED DEFAULT "0", totalwithdrew int(20) UNSIGNED DEFAULT "0", totaldeposit int(20) UNSIGNED DEFAULT "0", opentime int(20) UNSIGNED NOT NULL, fees char(32) DEFAULT "on" NOT NULL, PRIMARY KEY (id), INDEX(name)) TYPE=MYISAM';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'foing_config';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $prefix . 'foing_config (config_name VARCHAR(255) DEFAULT "" NOT NULL, config_value VARCHAR(255) DEFAULT "" NOT NULL, PRIMARY KEY (config_name)) TYPE=MYISAM';
_sql($sql, $errored, $error_ary);
	
$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'foing_albums';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $prefix . 'foing_albums (album_id MEDIUMINT(9) NOT NULL auto_increment, artist_id smallint(6) DEFAULT "0" NOT NULL, album_title VARCHAR(96) DEFAULT "" NOT NULL, album_year int(4) DEFAULT NULL, PRIMARY KEY (album_id)) TYPE=MYISAM';
_sql($sql, $errored, $error_ary);
	
$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'foing_artists';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $prefix . 'foing_artists (artist_id smallint(11) NOT NULL auto_increment, artist_name VARCHAR(64) DEFAULT "" NOT NULL, artist_url VARCHAR(64) DEFAULT "" NOT NULL, PRIMARY KEY (artist_id)) TYPE=MYISAM';
_sql($sql, $errored, $error_ary);
	
$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'foing_genres';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $prefix . 'foing_genres (genre_id int(3) DEFAULT "0" NOT NULL, genre_name VARCHAR(32) DEFAULT "" NOT NULL, PRIMARY KEY (genre_id)) TYPE=MYISAM';
_sql($sql, $errored, $error_ary);
	
$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'foing_playlist';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $prefix . 'foing_playlist (list_id MEDIUMINT(9) NOT NULL auto_increment, user_id MEDIUMINT(9) DEFAULT "0" NOT NULL, list_title VARCHAR(64) DEFAULT "" NOT NULL, list_desc tinytext NOT NULL, list_pub INT(1) DEFAULT "0" NOT NULL, PRIMARY KEY (list_id), KEY list_id (list_id)) TYPE=MYISAM';
_sql($sql, $errored, $error_ary);
		
$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'foing_playlist_data';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $prefix . 'foing_playlist_data (list_id MEDIUMINT(9) DEFAULT "0" NOT NULL, song_id MEDIUMINT(9) DEFAULT "0" NOT NULL)';
_sql($sql, $errored, $error_ary);
	
$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'foing_songs';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $prefix . 'foing_songs (song_id MEDIUMINT(9) NOT NULL auto_increment, artist_id smallint(6) DEFAULT "0" NOT NULL, genre_id int(3) DEFAULT "0" NOT NULL, user_id MEDIUMINT(9) DEFAULT "0" NOT NULL, album_id MEDIUMINT(9) DEFAULT "0" NOT NULL, status_id int(1) DEFAULT "0" NOT NULL, song_title VARCHAR(96) DEFAULT "" NOT NULL, song_url VARCHAR(255) DEFAULT "" NOT NULL, song_quality int(3) DEFAULT "0" NOT NULL, song_size VARCHAR(16) DEFAULT "" NOT NULL, song_length MEDIUMINT(9) DEFAULT "0" NOT NULL, PRIMARY KEY (song_id)) TYPE=MYISAM';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS '. $prefix . 'foing_status';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $prefix . 'foing_status (status_id int(11) DEFAULT "1" NOT NULL, status_name VARCHAR(32) DEFAULT "" NOT NULL, PRIMARY KEY (status_id), UNIQUE KEY status_id(status_id), KEY status_id_2 (status_id)) TYPE=MYISAM';
_sql($sql, $errored, $error_ary);
	
$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'foing_song_desc';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $prefix . 'foing_song_desc (song_id MEDIUMINT(9) DEFAULT "0" NOT NULL, song_desc mediumtext NOT NULL, PRIMARY KEY (song_id), UNIQUE KEY song_id(song_id), KEY song_id_2 (song_id)) TYPE=MYISAM';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'logs';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $prefix . 'logs (id_log MEDIUMINT(10) NOT NULL auto_increment, mode VARCHAR(50) DEFAULT "" NULL, topic_id MEDIUMINT(10) DEFAULT "0" NULL, user_id MEDIUMINT(8) DEFAULT "0" NULL, username VARCHAR(255) DEFAULT "" NULL, user_ip char(8) DEFAULT "0" NOT NULL, time INT(11) DEFAULT "0" NULL, PRIMARY KEY (id_log)) TYPE=MYISAM';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'module_admin_panel';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $prefix . 'module_admin_panel (module_id MEDIUMINT(8) UNSIGNED DEFAULT "0" NOT NULL, config_name VARCHAR(255) DEFAULT "" NOT NULL, config_value VARCHAR(255) DEFAULT "" NOT NULL, config_type VARCHAR(20) DEFAULT "" NOT NULL, config_title VARCHAR(100) DEFAULT "" NOT NULL, config_explain VARCHAR(100) DEFAULT NULL, config_trigger VARCHAR(20) DEFAULT "" NOT NULL)';
_sql($sql, $errored, $error_ary);
		
$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'module_cache';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $prefix . 'module_cache (module_id MEDIUMINT(8) DEFAULT "0" NOT NULL, module_cache_time int(12) DEFAULT "0" NOT NULL, db_cache TEXT NOT NULL, priority MEDIUMINT(8) UNSIGNED DEFAULT "0" NOT NULL, PRIMARY KEY (module_id)) TYPE=MYISAM';
_sql($sql, $errored, $error_ary);
		
$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'module_group_auth';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $prefix . 'module_group_auth (module_id MEDIUMINT(8) UNSIGNED NOT NULL, group_id MEDIUMINT(8) UNSIGNED NOT NULL)';
_sql($sql, $errored, $error_ary);
		
$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'module_info';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $prefix . 'module_info (module_id MEDIUMINT(8) DEFAULt "0" NOT NULL, long_name VARCHAR(100) DEFAULT "" NOT NULL, author VARCHAR(50) DEFAULT NULL, email VARCHAR(50) DEFAULT NULL, url VARCHAR(100) DEFAULT NULL, version VARCHAR(10) DEFAULT "" NOT NULL, update_site VARCHAR(100) DEFAULT NULL, extra_info VARCHAR(255) DEFAULT "" NOT NULL, PRIMARY KEY (module_id)) TYPE=MYISAM';	
_sql($sql, $errored, $error_ary);
	
$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'modules';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $prefix . 'modules (module_id MEDIUMINT(8) UNSIGNED NOT NULL auto_increment, short_name VARCHAR(30) DEFAULT NULL, update_time MEDIUMINT(8) DEFAULT "0" NOT NULL, module_order MEDIUMINT(8) DEFAULT "0" NOT NULL, active tinyint(2) DEFAULT "0" NOT NULL, perm_all tinyint(2) UNSIGNED DEFAULT "1" NOT NULL, perm_reg tinyint(2) UNSIGNED DEFAULT "1" NOT NULL, perm_mod tinyint(2) UNSIGNED DEFAULT "1" NOT NULL, perm_admin tinyint(2) UNSIGNED DEFAULT "1" NOT NULL, PRIMARY KEY (module_id)) TYPE=MYISAM';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'mycalendar';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $prefix . 'mycalendar (cal_id int(12) NOT NULL auto_increment, topic_id INT(20) NOT NULL DEFAULT "0", cal_date DATETIME DEFAULT "00-00-00 00:00:00" NOT NULL, cal_interval TINYINT(3) DEFAULT "1" NOT NULL, cal_interval_units ENUM("DAY", "WEEK", "MONTH", "YEAR") DEFAULT "DAY" NOT NULL, cal_repeat TINYINT(3) DEFAULT "1" NOT NULL, forum_id INT(5) NOT NULL DEFAULT "0", confirmed enum("Y","N") DEFAULT "Y" NOT NULL, event_type_id tinyint(4) DEFAULT "0" NOT NULL, PRIMARY KEY (cal_id), UNIQUE (topic_id)) TYPE=MYISAM';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'mycalendar_event_types';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $prefix . 'mycalendar_event_types (forum_id MEDIUMINT(8) UNSIGNED DEFAULT "0" NOT NULL, event_type_id tinyint(4) NOT NULL, event_type_text VARCHAR(255) NOT NULL, highlight_color VARCHAR(7) NOT NULL)';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS '. $prefix .'rating';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE '. $prefix .'rating (post_id INT UNSIGNED DEFAULT "0" NOT NULL, user_id INT UNSIGNED DEFAULT "0" NOT NULL, rating_time INT UNSIGNED DEFAULT "0" NOT NULL, option_id SMALLINT UNSIGNED DEFAULT "0" NOT NULL, KEY rating_postid(post_id), KEY rating_userid(user_id), KEY rating_ratingoptionid(option_id)) TYPE=MYISAM';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS '. $prefix .'rating_config';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE '. $prefix .'rating_config (label VARCHAR(100) DEFAULT NULL, num_value INT UNSIGNED DEFAULT "0" NOT NULL, text_value VARCHAR(255) DEFAULT NULL, config_id INT UNSIGNED DEFAULT "0" NOT NULL, input_type TINYINT UNSIGNED DEFAULT "0" NOT NULL, list_order SMALLINT(5) UNSIGNED DEFAULT "0" NOT NULL, PRIMARY KEY (config_id)) TYPE=MYISAM';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS '. $prefix .'rating_option';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE '. $prefix .'rating_option (option_id SMALLINT UNSIGNED NOT NULL auto_increment, points TINYINT DEFAULT "0" NOT NULL, label VARCHAR(100) DEFAULT NULL, weighting SMALLINT UNSIGNED DEFAULT "0" NOT NULL, user_type TINYINT UNSIGNED UNSIGNED DEFAULT "0" NOT NULL, PRIMARY KEY (option_id), KEY ratingoption_rating(points), KEY ratingoption_weighting (weighting)) TYPE=MYISAM';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS '. $prefix .'rating_rank';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE '. $prefix .'rating_rank (rating_rank_id SMALLINT UNSIGNED NOT NULL auto_increment, type TINYINT UNSIGNED DEFAULT "0" NOT NULL, average_threshold TINYINT DEFAULT "0" NOT NULL, sum_threshold INT DEFAULT "0" NOT NULL, label VARCHAR(100) DEFAULT NULL, icon VARCHAR(255) DEFAULT NULL, user_rank INT UNSIGNED NOT NULL, PRIMARY KEY (rating_rank_id), KEY ratingrank_type(type)) TYPE=MYISAM';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'serverload';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $prefix . 'serverload (time INT(14) DEFAULT "0" NOT NULL) TYPE=MYISAM';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'shop_items';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $prefix . 'shop_items (id INT UNSIGNED NOT NULL auto_increment, name CHAR(32) NOT NULL, shop CHAR(32) NOT NULL, sdesc CHAR(80) NOT NULL, ldesc TEXT NOT NULL, cost INT(20) UNSIGNED DEFAULT "100", startprice INT(20) UNSIGNED DEFAULT "100", raise INT(20) UNSIGNED DEFAULT "100", stock TINYINT(3) UNSIGNED DEFAULT "10", startstock TINYINT(3) UNSIGNED DEFAULT "100", maxstock TINYINT(3) UNSIGNED DEFAULT "100", sold INT(5) UNSIGNED DEFAULT "0" NOT NULL, PRIMARY KEY (id), INDEX(name)) TYPE=MYISAM';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'shop_transactions';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $prefix . 'shop_transactions (shoptrans_date INT(11) DEFAULT "0" NOT NULL, trans_user VARCHAR(25) DEFAULT "" NOT NULL, trans_item VARCHAR(32) DEFAULT "" NOT NULL, trans_type VARCHAR(255) DEFAULT "" NOT NULL, trans_total MEDIUMINT(8) DEFAULT "0" NOT NULL, PRIMARY KEY(shoptrans_date)) TYPE=MYISAM';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'shops';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $prefix . 'shops (id int UNSIGNED NOT NULL auto_increment, shopname char(32) NOT NULL, shoptype CHAR(32) NOT NULL, type CHAR(32) NOT NULL, restocktime INT(20) UNSIGNED DEFAULT "86400", restockedtime INT(20) UNSIGNED DEFAULT "0", restockamount INT(4) UNSIGNED DEFAULT "5", amountearnt INT(20) UNSIGNED DEFAULT "0", PRIMARY KEY (id), INDEX(shopname)) TYPE=MYISAM';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'stats_smilies_index';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $prefix . 'stats_smilies_index (code VARCHAR(50) DEFAULT NULL, smile_url VARCHAR(100) DEFAULT NULL, smile_count MEDIUMINT(8) DEFAULT "0")';
_sql($sql, $errored, $error_ary);
	
$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'stats_smilies_info';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $prefix . 'stats_smilies_info (last_post_id MEDIUMINT(8) DEFAULT "0" NOT NULL, last_update_time int(12) DEFAULT "0" NOT NULL, update_time MEDIUMINT(8) DEFAULT "10080" NOT NULL)';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'stats_config';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $prefix . 'stats_config (config_name VARCHAR(100) DEFAULT "" NOT NULL, config_value VARCHAR(100) DEFAULT "" NOT NULL, PRIMARY KEY (config_name)) TYPE=MYISAM';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'subscriptions_list';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $prefix . 'subscriptions_list (user_id BIGINT, forum BIGINT, thread BIGINT)';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'transactions';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $prefix . 'transactions (trans_date int(11) DEFAULT "0" NOT NULL, trans_from VARCHAR(30) DEFAULT "" NOT NULL, trans_to VARCHAR(30) DEFAULT "" NOT NULL, trans_amount MEDIUMINT(8) DEFAULT "0" NOT NULL, PRIMARY KEY (trans_date)) TYPE=MYISAM';
_sql($sql, $errored, $error_ary);



//
// Modify phpBB core-data
// phpbb_config data
$config_data = array( 'bankfees' => 2, 'bankinterest' => 2, 'banklastrestocked' => 0, 'bankname' => 'Bank', 'bankopened' => 0, 'bankpayouttime' => 86400, 'disable_reg_msg' => '', 'multibuys' => 0, 'points_donate' => 0, 'points_name' => 'Points', 'points_post' => 0, 'points_system_version' => '2.1.1', 'points_reply' => 1, 'points_topic' => 2, 'points_user_group_auth_ids' => '', 'restocks' => 0, 'sellrate' => 75, 'specialshop' => 'ßstoreÞdisabledßnameÞEffects StoreßoffÞ1ßoffÞ1ßoffÞ1ßoffÞ1ßoffÞ1ßoffÞ1ßoffÞ1ßoffÞ1ßoffÞ1ßoffÞ1ßoffÞ1ßoffÞ1ßoffÞ1', 'viewinventory' => 'grouped', 'viewprofile' => 'images', 'viewtopic' => 'link', 'viewtopiclimit' => 2 );
while ( list ( $config_name, $config_value ) = each ( $config_data ) )
{
	$sql = "INSERT INTO " . $prefix . "config (`config_name`, `config_value`) VALUES ('" . $config_name . "', '" . $config_value . "')";
	_sql($sql, $errored, $error_ary);
}	

$sql = 'UPDATE ' . $prefix . 'config SET `config_name` = "birthday_check_day" WHERE `config_name` = "birthday_check_date"'; 
_sql($sql, $errored, $error_ary);
			
// Modify Fully Modded core-data
// phpbb_album_config data
$album_config_data = array( 'album_version' => '.0.0 BETA 4', 'cols_per_page' => 4, 'comment' => 1, 'gd_version' => 2, 'gif_allowed' => 0, 'hotlink_allowed' => '', 'hotlink_prevent' => 0, 'jpg_allowed' => 1, 'max_file_size' => 128000, 'max_pics' => 1024, 'mod_pics_limit' => 250, 'max_height' => 600, 'max_width' => 800, 'personal_gallery' => 0, 'personal_gallery_limit' => 10, 'personal_gallery_private' => 0, 'personal_gallery_view' => -1, 'png_allowed' => 1, 'rate' => 1, 'rows_per_page' => 3, 'thumbnail_cache' => 1, 'thumbnail_quality' => 100, 'thumbnail_size' => 125, 'user_pics_limit' => 50 );
while ( list ( $config_name, $config_value ) = each ( $album_config_data ) )
{
	$sql = "INSERT INTO " . $prefix . "album_config (`config_name`, `config_value`) VALUES ('" . $config_name . "', '" . $config_value . "')";
	_sql($sql, $errored, $error_ary);
}
	
// phpbb_ablum_cat data
$sql = "INSERT INTO " . $prefix . "album_cat (`cat_title`, `cat_desc`, `cat_order`, `cat_view_level`, `cat_upload_level`, `cat_rate_level`, `cat_comment_level`, `cat_edit_level`, `cat_delete_level`, `cat_moderator_groups`, `cat_approval`) VALUES ('Test Category 1', 'This is just a test photo album', '10', '-1', '0', '0', '0', '0', '1', 'NULL', '0')";
_sql($sql, $errored, $error_ary);

// phpbb_foing_status data
$id = 1;
$foing_status_data = array( 'pending', 'not accepted', 'accepted', 'downloadable', 'feature' );
for ( $i = 0; $i < sizeof($foing_status_data); $i++ )
{
	$sql = "INSERT INTO " . $prefix . "foing_status (`status_id`, `status_name`) VALUES ('" . $id . "', '" . $foing_status_data[$i] . "')";
	_sql($sql, $errored, $error_ary);
	$id++;
}

// phpbb_foing_config data
$foing_config_data = array( 'add_level' => -1, 'feature_status' => 5, 'flash_background_color' => 'D1D7DC', 'flash_button_color' => '006699', 'flash_button_color2' => 'FFFFFF', 'flash_font_color' => '000000', 'flash_font_face' => 'Verdana, Helvetica, sans-serif', 'flash_font_size' => 12, 'flash_link_color' => '006699', 'flash_logo_file' => '../images/logo_flash.jpg', 'flash_status' => 2, 'flash_textbg_color' => 'EFEFEF', 'max_songs' => 40, 'mod_group' => 0, 'psw_flash' => 'secret', 'remote_getid3' => '' );
while ( list ( $config_name, $config_value ) = each ( $foing_config_data ) )
{
	$sql = "INSERT INTO " . $prefix . "foing_config (`config_name`, `config_value`) VALUES ('" . $config_name . "', '" . $config_value . "')";
	_sql($sql, $errored, $error_ary);
}
	
// phpbb_foing_albums data
$sql = 'INSERT INTO ' . $prefix . 'foing_albums (`album_id`, `artist_id`, `album_title`) VALUES ("0", "-1", "--")';
_sql($sql, $errored, $error_ary);
			
$sql = 'UPDATE ' . $prefix . 'foing_albums SET `album_id` = "0" WHERE `album_id` = "1"';
_sql($sql, $errored, $error_ary);

// phpbb_foing_genres data
$foing_genres_data = array(
	0 => 'Blues',
	1 => 'Classic Rock',
	2 => 'Country',
	3 => 'Dance',
	4 => 'Disco',
	5 => 'Funk',
	6 => 'Grunge',
	7 => 'Hip-Hop',
	8 => 'Jazz',
	9 => 'Metal',
	10 => 'New Age',
	11 => 'Oldies',
	12 => 'Other',
	13 => 'Pop',
	14 => 'R&B',
	15 => 'Rap',
	16 => 'Reggae',
	17 => 'Rock',
	18 => 'Techno',
	19 => 'Industrial',
	20 => 'Alternative',
	21 => 'Ska',
	22 => 'Death Metal',
	23 => 'Pranks',
	24 => 'Soundtrack',
	25 => 'Euro-Techno',
	26 => 'Ambient',
	27 => 'Trip-Hop',
	28 => 'Vocal',
	29 => 'Jazz&Funk',
	30 => 'Fusion',
	31 => 'Trance',
	32 => 'Clasical',
	33 => 'Instrumental',
	34 => 'Acid',
	35 => 'House',
	36 => 'Game',
	37 => 'Sound Clip',
	38 => 'Gospel',
	39 => 'Noise',
	40 => 'Alt. Rock',
	41 => 'Bass',
	42 => 'Soul',
	43 => 'Punk',
	44 => 'Space',
	45 => 'Meditative',
	46 => 'Instrumental Pop',
	47 => 'Instrumental Rock',
	48 => 'Ethnic',
	49 => 'Gothic',
	50 => 'Darkwave',
	51 => 'Techo-Industrial',
	52 => 'Electronic',
	53 => 'Folk/Pop',
	54 => 'Eurodance',
	55 => 'Dream',
	56 => 'Southern Rock',
	57 => 'Comedy',
	58 => 'Cult',
	59 => 'Gangsta',
	60 => 'Top 40',
	61 => 'Christian Rap',
	62 => 'Pop/Funk',
	63 => 'Jungle',
	64 => 'Native American',
	65 => 'Cabaret',
	66 => 'New Wave',
	67 => 'Psychadelic',
	68 => 'Rave',
	69 => 'Showtunes',
	70 => 'Trailer',
	71 => 'Lo-Fi',
	72 => 'Tribal',
	73 => 'Acid Punk',
	74 => 'Acid Jazz',
	75 => 'Polka',
	76 => 'Retro',
	77 => 'Musical',
	78 => 'Rock & Roll',
	79 => 'Hard Rock',
	80 => 'Folk',
	81 => 'Folk/Rock',
	82 => 'National Folk',
	83 => 'Swing',
	84 => 'Fast-Fusion',
	85 => 'Bebob',
	86 => 'Latin',
	87 => 'Revival',
	88 => 'Celtic',
	89 => 'Bluegrass',
	90 => 'Avantgarde',
	91 => 'Gothic Rock',
	92 => 'Progressive Rock',
	93 => 'Psychedelic Rock',
	94 => 'Sumphonic Rock',
	95 => 'Slow Rock',
	96 => 'Big Band',
	97 => 'Chorus',
	98 => 'Easy Listening',
	99 => 'Accoustic',
	100 => 'Humour',
	101 => 'Speech',
	102 => 'Chanson',
	103 => 'Opera',
	104 => 'Sonata',
	105 => 'Symphony',
	106 => 'Booty Bass',
	107 => 'Primus',
	108 => 'Porn Groove',
	109 => 'Satire',
	110 => 'Slow Jam',
	111 => 'Club',
	112 => 'Tango',
	113 => 'Samba',
	114 => 'Folklore',
	115 => 'Ballad',
	116 => 'Power Ballad',
	117 => 'Rhythmic Soul',
	118 => 'Freestyle',
	119 => 'Duet',
	120 => 'Punk Rock',
	121 => 'Drum Solo',
	122 => 'A Cappella',
	123 => 'Euro-House',
	124 => 'Dance Hall',
	125 => 'Goa',
	126 => 'Drum & Bass',
	127 => 'Club-House',
	128 => 'Hardcore',
	129 => 'Terror',
	130 => 'Indie',
	131 => 'BritPop',
	132 => 'Negerpunk',
	133 => 'Polsk Punk',
	134 => 'Beat',
	135 => 'Christian Gangsta Rap',
	136 => 'Heavy Metal',
	137 => 'Black Metal',
	138 => 'Crossover',
	139 => 'Contemporary Christian',
	140 => 'Christian Rock',
	141 => 'Merenge',
	142 => 'Salsa',
	143 => 'Trash Metal',
	144 => 'Anime',
	145 => 'Jpop',
	146 => 'Synthpop',
	255 => 'Unknown'
);
while ( list ( $genre_id, $genre_name ) = each ( $foing_genres_data ) )
{
	$sql = "INSERT INTO " . $prefix . "foing_genres (`genre_id`, `genre_name`) VALUES ('" . $genre_id . "', '" . $genre_name . "')";
	_sql($sql, $errored, $error_ary);
}

// phpbb_stats_config data
$stats_config_data = array( 'install_date' => time(), 'page_views' => 0, 'return_limit' => 10, 'version' => '3.0.0' );
while ( list ( $config_name, $config_value ) = each ( $stats_config_data ) )
{
	$sql = "INSERT INTO " . $prefix . "stats_config (`config_name`, `config_value`) VALUES ('" . $config_name . "', '" . $config_value . "')";
	_sql($sql, $errored, $error_ary);
}

// phpbb_stats_smilies_info data
$sql = "INSERT INTO " . $prefix . "stats_smilies_info (`last_post_id`, `last_update_time`, `update_time`) VALUES ('0', '0', '10080')";
_sql($sql, $errored, $error_ary);

?>