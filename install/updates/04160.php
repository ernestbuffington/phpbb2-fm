<?php
	
if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}

	
//
// Modify phpBB core-scema	
//
$sql = 'ALTER TABLE ' . $table_prefix . 'auth_access ADD COLUMN `auth_ban` TINYINT(1) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'auth_access ADD COLUMN `auth_greencard` TINYINT(1) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'auth_access ADD COLUMN `auth_bluecard` TINYINT(1) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $table_prefix . 'forums ADD COLUMN `auth_ban` TINYINT(2) NOT NULL DEFAULT "3"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'forums ADD COLUMN `auth_greencard` TINYINT(2) NOT NULL DEFAULT "5"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'forums ADD COLUMN `auth_bluecard` TINYINT(2) NOT NULL DEFAULT "1"';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $table_prefix . 'posts ADD COLUMN `user_avatar` VARCHAR(100)';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'posts ADD COLUMN `user_avatar_type` TINYINT NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'posts ADD COLUMN `rating_rank_id` SMALLINT UNSIGNED NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'posts ADD COLUMN `post_bluecard` TINYINT(1)';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'posts ADD KEY `posts_ratingrankid` (rating_rank_id)';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $table_prefix . 'themes ADD COLUMN `adminfontcolor` VARCHAR(6) DEFAULT "FFA34F"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'themes ADD COLUMN `supermodfontcolor` VARCHAR(6) DEFAULT "009900"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'themes ADD COLUMN `modfontcolor` VARCHAR(6) DEFAULT "006600"';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $table_prefix . 'themes_name ADD COLUMN `adminfontcolor_name` CHAR(50) DEFAULT "Administrator font color"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'themes_name ADD COLUMN `supermodfontcolor_name` CHAR(50) DEFAULT "Super Moderator font color"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'themes_name ADD COLUMN `modfontcolor_name` CHAR(50) DEFAULT "Moderator font color"';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $table_prefix . 'topics ADD COLUMN `rating_rank_id` SMALLINT UNSIGNED NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'topics ADD KEY `topics_ratingrankid` (rating_rank_id)';
_sql($sql, $errored, $error_ary);


//
// Modify Fully Modded core-schema
//
$sql = 'ALTER TABLE ' . $table_prefix . 'album ADD COLUMN `pic_desc` TEXT';
_sql($sql, $errored, $error_ary);
	
$sql = 'ALTER TABLE ' . $table_prefix . 'album_cat ADD COLUMN `cat_view_groups` VARCHAR(255)';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'album_cat ADD COLUMN `cat_upload_groups` VARCHAR(255)';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'album_cat ADD COLUMN `cat_rate_groups` VARCHAR(255)';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'album_cat ADD COLUMN `cat_comment_groups` VARCHAR(255)';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'album_cat ADD COLUMN `cat_edit_groups` VARCHAR(255)';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'album_cat ADD COLUMN `cat_delete_groups` VARCHAR(255)';
_sql($sql, $errored, $error_ary);
				
$sql = 'ALTER TABLE ' . $table_prefix . 'foing_artists ADD COLUMN `artist_prefix` VARCHAR(16) DEFAULT "" NOT NULL';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $table_prefix . 'users ADD COLUMN `user_sound_pm` TINYINT(1) NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'users ADD COLUMN `rating_status` TINYINT UNSIGNED';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'users ADD COLUMN `user_warnings` SMALLINT(5) DEFAULT "0"';			
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'users ADD COLUMN `avatar_sticky` TINYINT NOT NULL';
_sql($sql, $errored, $error_ary);


//
// Create new Fully Modded core-schema
//
$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'banner';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'banner (banner_id MEDIUMINT(8) UNSIGNED NOT NULL, banner_name VARCHAR(60) NOT NULL, banner_spot SMALLINT(1) UNSIGNED NOT NULL, banner_description VARCHAR(30) NOT NULL, banner_url VARCHAR(100) NOT NULL, banner_owner MEDIUMINT(8) NOT NULL, banner_click MEDIUMINT(8) UNSIGNED NOT NULL, banner_view MEDIUMINT(8) UNSIGNED NOT NULL, banner_weigth TINYINT(1) UNSIGNED DEFAULT "50" NOT NULL, banner_active TINYINT(1) NOT NULL, banner_timetype TINYINT(1) NOT NULL, time_begin INT(11) NOT NULL, time_end INT(11) NOT NULL, date_begin INT(11) NOT NULL, date_end INT(11) NOT NULL, banner_comment VARCHAR(50) NOT NULL, INDEX (banner_id))';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS '. $table_prefix .'forums_config';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE '. $table_prefix .'forums_config (config_name VARCHAR(255) NOT NULL, config_value VARCHAR(255) NOT NULL, PRIMARY KEY (config_name)) TYPE=MYISAM';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'links';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'links (link_id MEDIUMINT(8) UNSIGNED NOT NULL auto_increment, link_title VARCHAR(255) DEFAULT "" NOT NULL, link_desc VARCHAR(255) DEFAULT NULL, link_category MEDIUMINT(8) UNSIGNED DEFAULT "0" NOT NULL, link_url VARCHAR(100) DEFAULT "" NOT NULL, link_logo_src VARCHAR(100) DEFAULT NULL, link_joined int(11) DEFAULT "0" NOT NULL, link_active tinyint(1) DEFAULT "0" NOT NULL, link_hits int(10) UNSIGNED DEFAULT "0" NOT NULL, user_id MEDIUMINT(8) DEFAULT "0" NOT NULL, user_ip VARCHAR(8) DEFAULT "" NOT NULL, last_user_ip VARCHAR(8) DEFAULT "" NOT NULL, PRIMARY KEY (link_id)) TYPE=MYISAM';
_sql($sql, $errored, $error_ary);
		
$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'links_categories';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'links_categories (`cat_id` MEDIUMINT(8) UNSIGNED NOT NULL auto_increment, `cat_title` VARCHAR(100) DEFAULT "" NOT NULL, `cat_order` MEDIUMINT(8) UNSIGNED DEFAULT "0" NOT NULL, PRIMARY KEY (cat_id), KEY cat_order (cat_order)) TYPE=MYISAM';
_sql($sql, $errored, $error_ary);
	
$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'portal';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'portal (portal_id MEDIUMINT(8) NOT NULL auto_increment, portal_order MEDIUMINT(8) NOT NULL, portal_title VARCHAR(60) NOT NULL, portal_bbcode_id VARCHAR(10) NOT NULL, portal_use_url TINYINT(1), portal_forum MEDIUMINT(8) NOT NULL, portal_url VARCHAR(255) NOT NULL, portal_list_limit MEDIUMINT(8) NOT NULL, portal_ascending TINYINT(1), portal_nodate TINYINT(1), portal_navbar_name VARCHAR(100) NOT NULL, portal_newsfader TINYINT(1) DEFAULT "0", portal_navbar TINYINT(1) DEFAULT "0", portal_moreover TINYINT(1) DEFAULT "0", portal_calendar TINYINT(1) DEFAULT "0", portal_online TINYINT(1) DEFAULT "0", portal_onlinetoday TINYINT(1) DEFAULT "0", portal_latest TINYINT(1) DEFAULT "0", portal_latestx TINYINT(1) DEFAULT "0", portal_latestxresponses TINYINT(1), portal_poll TINYINT(1) DEFAULT "0", portal_polls TINYINT(5) DEFAULT "1" NOT NULL, portal_shoutbox TINYINT(1) DEFAULT "0", portal_photo TINYINT(1) DEFAULT "0", portal_birthday VARCHAR(6) DEFAULT "999999", portal_search TINYINT(1) DEFAULT "0", portal_quote TINYINT(1) DEFAULT "0", portal_links TINYINT(1) DEFAULT "0", portal_links_height TINYINT(4) DEFAULT "100" NOT NULL, portal_ourlink TINYINT(1) DEFAULT "0", portal_downloads TINYINT(1) DEFAULT "0", portal_randomuser TINYINT(1) DEFAULT "0", portal_mostpoints TINYINT(1) DEFAULT "0", portal_topposters TINYINT(1) DEFAULT "0", portal_newusers TINYINT(1) DEFAULT "0", PRIMARY KEY(portal_id))';
_sql($sql, $errored, $error_ary);


//
// Modify phpBB core-data
// phpbb_config data
$sql = 'UPADTE ' . $table_prefix . 'config SET `config_name` = "allow_photo_upload" WHERE `config_name` = "\"allow_photo_upload"';
_sql($sql, $errored, $error_ary);

$config_data = array( 'allow_avatar_sticky' => 0, 'bb_usage_stats_prscale' => 1, 'bb_usage_stats_specialgrp' => -1, 'bb_usage_stats_viewlevel' => 16, 'bb_usage_stats_trscale' => 1, 'bluecard_limit' => 3, 'bluecard_limit_2' => 1, 'max_user_bancard' => 10, 'no_post_count_forum_id' => '', 'report_forum' => 0, 'smilie_columns' => 6, 'smilie_rows' => 4, 'smilie_window_columns' => 8 );
while ( list ( $config_name, $config_value ) = each ( $config_data ) )
{
	$sql = "INSERT INTO " . $table_prefix . "config (`config_name`, `config_value`) VALUES ('" . $config_name . "', '" . $config_value . "')";
	_sql($sql, $errored, $error_ary);
}	
			
// Modify Fully Modded core-data
// phpbb_album_config data
$album_config_data = array( 'desc_length' => 512, 'fullpic_popup' => 0, 'rate_scale' => 10, 'sort_method' => 'pic_time', 'sort_order' => 'DESC' );
while ( list ( $config_name, $config_value ) = each ( $album_config_data ) )
{
	$sql = "INSERT INTO " . $table_prefix . "album_config (`config_name`, `config_value`) VALUES ('" . $config_name . "', '" . $config_value . "')";
	_sql($sql, $errored, $error_ary);
}

// phpbb_forums_config data
$forum_config_data = array( 'forum_module_birthday' => 0, 'forum_module_calendar' => 0, 'forum_module_disable' => 0, 'forum_module_glance' => 0, 'forum_module_links' => 0, 'forum_module_newsbar' => 0, 'forum_module_photo' => 0, 'forum_module_weather' => 0, 'forum_module_width' => 200 );
while ( list ( $config_name, $config_value ) = each ( $forum_config_data ) )
{
	$sql = "INSERT INTO " . $table_prefix . "forums_config (`config_name`, `config_value`) VALUES ('" . $config_name . "', '" . $config_value . "')";
	_sql($sql, $errored, $error_ary);
}

// phpbb_links data
$links_data = array( 
	array( Title => 'Fully Modded phpBB Official Website', Desc => 'Official Fully Modded Website', Url => 'http://phpbb-fm.com', Logo => 'images/links/phpbbfmcom.gif' ),
	array( Title => 'phpBB Official Website', Desc => 'Official phpBB Website', Url => 'http://phpbb.com', Logo => 'images/links/phpbbcom.gif' )
);
for ( $row = 0; $row < sizeof($links_data); $row++ )
{
	for ( $column = 0; $column < 1; $column++ )
	{
		$sql = "INSERT INTO " . $table_prefix . "links (`link_title`, `link_desc`, `link_category`, `link_url`, `link_logo_src`, `link_joined`, `link_active`, `link_hits`, `user_id`) VALUES ('" . $links_data[$row]['Title'] . "', '" . $links_data[$row]['Desc'] . "', '4', '" . $links_data[$row]['Url']. "', '" . $links_data[$row]['Logo'] . "', '" . time() . "', '1', '0', '2')";
		_sql($sql, $errored, $error_ary);
	}
}

// phpbb_links_categories data
$id = 1;
$link_cat_data = array('Arts', 'Business', 'Children and Teens', 'Computers', 'Games', 'Health', 'Home', 'Music', 'News', 'Recreation', 'Reference', 'Science', 'Shopping', 'Society', 'Sports', 'Travel', 'Unsorted');
for ( $i = 0; $i < sizeof($link_cat_data); $i++ )
{
	$sql = "INSERT INTO " . $table_prefix . "links_categories (`cat_title`, `cat_order`) VALUES ('" . $link_cat_data[$i] . "', '" . $id . "')";
	_sql($sql, $errored, $error_ary);
	$id++;
}
			
// phpbb_portal data
$sql = "INSERT INTO " . $table_prefix . "portal (`portal_title`, `portal_use_url`, `portal_forum`, `portal_list_limit`, `portal_navbar_name`, `portal_navbar`) VALUES ('home page', '0', '1', '3', 'Home', '1')";
_sql($sql, $errored, $error_ary);

// phpbb_rating_config data
$rating_config_data = array(
	array( Label => 'Ratings system active', Num => 0, ID => 1, Type => 3, Order => 100 ),
	array( Label => 'Weighting method', Num => 1, ID => 3, Type => 3, Order => 300 ),
	array( Label => 'Users can change ratings', Num => 1, ID => 4, Type => 3, Order => 400 ),
	array( Label => 'Show who rated', Num => 1, ID => 6, Type => 3, Order => 600 ),
	array( Label => 'Allow users to hide name', Num => 1, ID => 7, Type => 3, Order => 700 ),
	array( Label => 'Rate first post only', Num => 0, ID => 2, Type => 3, Order => 200 ),
	array( Label => 'Overall ranking method: posts', Num => 1, ID => 8, Type => 3, Order => 800 ),
	array( Label => 'Overall ranking method: topics', Num => 1, ID => 9, Type => 3, Order => 900 ),
	array( Label => 'Overall ranking method: users', Num => 1, ID => 10, Type => 3, Order => 1000 ),
	array( Label => 'Max daily ratings per user', Num => 1, ID => 13, Type => 2, Order => 550 ),
	array( Label => 'Open in new window', Num => 1, ID => 14, Type => 3, Order => 1400 ),
	array( Label => 'Min. post count', Num => 5, ID => 15, Type => 2, Order => 240 ),
	array( Label => 'Min. days registered', Num => 7, ID => 16, Type => 2, Order => 250 )
);
for ( $row = 0; $row < sizeof($rating_config_data); $row++ )
{
	for ( $column = 0; $column < 1; $column++ )
	{
		$sql = "INSERT INTO " . $table_prefix . "rating_config (`label`, `num_value`, `config_id`, `input_type`, `list_order`) VALUES ('" . $rating_config_data[$row]['Label'] . "', '" . $rating_config_data[$row]['Num'] . "', '" . $rating_config_data[$row]['ID'] . "', '" . $rating_config_data[$row]['Type'] . "', '" . $rating_config_data[$row]['Order'] . "')";
		_sql($sql, $errored, $error_ary);
	}
}

// phpbb_rating_option data
$rating_option_data = array( 
	array( Points => 2, Label => 'Highly recommended', Weight => 5, Type => 1 ),
	array( Points => 1, Label => 'Recommended', Weight => 0, Type => 1 ),
	array( Points => 5, Label => 'Moderator-recommended', Weight => 0, Type => 3 )
);
for ( $row = 0; $row < sizeof($rating_option_data); $row++ )
{
	for ( $column = 0; $column < 1; $column++ )
	{
		$sql = "INSERT INTO " . $table_prefix . "rating_option (`points`, `label`, `weighting`, `user_type`) VALUES ('" . $rating_option_data[$row]['Points'] . "', '" . $rating_option_data[$row]['Label'] . "', '" . $rating_option_data[$row]['Weight'] . "', '" . $rating_option_data[$row]['Type'] . "')";
		_sql($sql, $errored, $error_ary);
	}
}
			
// phpbb_rating_rank data
$rating_rank_data = array( 
	array( Type => 5, AVG => 1, SUM => 1, Label => 'Acknowledged', Icon => '1star_green.gif' ),
	array( Type => 5, AVG => 2, SUM => 5, Label => 'Worth a look', Icon => '2star_green.gif' ),
	array( Type => 5, AVG => 3, SUM => 10, Label => 'Quality', Icon => '3star_green.gif' ),
	array( Type => 5, AVG => 4, SUM => 20, Label => 'Impressive', Icon => '4star_green.gif' ),
	array( Type => 5, AVG => 5, SUM => 40, Label => 'Inspired', Icon => '5star_green.gif' ),
	array( Type => 4, AVG => 1, SUM => 2, Label => 'Acknowledged', Icon => '1star_cyan.gif' ),
	array( Type => 4, AVG => 2, SUM => 10, Label => 'Worth a look', Icon => '2star_cyan.gif' ),
	array( Type => 4, AVG => 3, SUM => 20, Label => 'Quality', Icon => '3star_cyan.gif' ),
	array( Type => 4, AVG => 4, SUM => 40, Label => 'Impressive', Icon => '4star_cyan.gif' ),
	array( Type => 4, AVG => 5, SUM => 80, Label => 'Inspired', Icon => '5star_cyan.gif' )
);
for ( $row = 0; $row < sizeof($rating_rank_data); $row++ )
{
	for ( $column = 0; $column < 1; $column++ )
	{
		$sql = "INSERT INTO " . $table_prefix . "rating_rank (`type`, `average_threshold`, `sum_threshold`, `label`, `icon`, `user_rank`) VALUES ('" . $rating_rank_data[$row]['Type'] . "', '" . $rating_rank_data[$row]['AVG'] . "', '" . $rating_rank_data[$row]['SUM'] . "', '" . $rating_rank_data[$row]['Label'] . "', '" . $rating_rank_data[$row]['Icon'] . "', '0')";
		_sql($sql, $errored, $error_ary);
	}
}

?>