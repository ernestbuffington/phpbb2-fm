<?php

if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}


//
// Modify phpBB core-scema	
//
$sql = 'ALTER TABLE ' . $table_prefix . 'forums ADD COLUMN `index_lasttitle` TINYINT(1) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'forums ADD COLUMN `display_pic_alert` TINYINT(1) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $table_prefix . 'groups ADD COLUMN `group_members_count` TINYINT(1) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $table_prefix . 'themes ADD COLUMN `playersfontcolor` VARCHAR(6) DEFAULT "0099CC"';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $table_prefix . 'themes_name DROP COLUMN `adminfontcolor_name`';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'themes_name DROP COLUMN `supermodfontcolor_name`';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'themes_name DROP COLUMN `modfontcolor_name`';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $table_prefix . 'users ADD COLUMN `user_topic_moved_mail` TINYINT(1) DEFAULT "0"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'users ADD COLUMN `user_topic_moved_pm` TINYINT(1) DEFAULT "0"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'users ADD COLUMN `user_topic_moved_pm_notify` TINYINT(1) DEFAULT "0"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'users ADD COLUMN `daily_post_count` MEDIUMINT(8) UNSIGNED DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'users ADD COLUMN `daily_post_limit` MEDIUMINT(8) UNSIGNED NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'users ADD COLUMN `daily_post_period` INT(11) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'users ADD COLUMN `user_wordwrap` SMALLINT(3) DEFAULT "80" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'users ADD COLUMN `user_allowsig` TINYINT(1) DEFAULT "1" NOT NULL';
_sql($sql, $errored, $error_ary);


//
// Modify Fully Modded core-schema
//
$sql = 'DROP TABLE IF EXISTS '. $table_prefix .'admin_categories';
_sql($sql, $errored, $error_ary);
$sql = 'DROP TABLE IF EXISTS '. $table_prefix .'admin_nav_module';
_sql($sql, $errored, $error_ary);
$sql = 'DROP TABLE IF EXISTS '. $table_prefix .'admin_notes';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $table_prefix . 'advance_html ADD PRIMARY KEY (`config_name`)'; 
_sql($sql, $errored, $error_ary);
	
$sql = 'DELETE FROM ' . $table_prefix . 'forums WHERE `forum_id` = "-5"';
_sql($sql, $errored, $error_ary);
$sql = 'DELETE FROM ' . $table_prefix . 'forums WHERE `forum_id` = "-7"';
_sql($sql, $errored, $error_ary);
$sql = 'DELETE FROM ' . $table_prefix . 'forums WHERE `forum_id` = "-42"';
_sql($sql, $errored, $error_ary);
$sql = 'DELETE FROM ' . $table_prefix . 'forums WHERE `forum_id` = "-51"';
_sql($sql, $errored, $error_ary);

// 22473 new installs need this 'portal_donors' field fixed!
$sql = 'ALTER TABLE ' . $table_prefix . 'portal ADD COLUMN `portal_donors` TINYINT(1) DEFAULT "0"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'portal ADD COLUMN `portal_referrers` TINYINT(1) DEFAULT "0"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'portal ADD COLUMN `portal_shoutbox` TINYINT(1) DEFAULT "0"';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $table_prefix . 'pjirc ADD PRIMARY KEY (`pjirc_name`)'; 
_sql($sql, $errored, $error_ary);


//
// Create new Fully Modded core-schema
//
$sql = 'DROP TABLE IF EXISTS '. $table_prefix .'pages';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE '. $table_prefix .'pages (page_id mediumint(5) unsigned auto_increment, page_name varchar(32) NOT NULL, page_parm_name varchar(32) default "", page_parm_value varchar(32) default "", page_key varchar(255) default "", member_views int(11) unsigned default "0", guest_views int(11) unsigned default "0", disable_page tinyint(1) unsigned default "0", auth_level tinyint(2) unsigned default "0", min_post_count mediumint(8) unsigned default "0", max_post_count mediumint(8) unsigned default "0", group_list varchar(255) default "", disabled_message	varchar(255) default "", primary key (page_id), unique key (page_key)) TYPE=MyISAM';
_sql($sql, $errored, $error_ary);


//
// Modify phpBB core-data
//
// phpbb_config data
$config_data = array( 
	'enable_mod_logger' => 0,
	'vip_enable' => 1,
	'vip_code' => 2486,
	'index_active_in_forum' => 0,
	'sig_images_max_width' => 400,
	'sig_images_max_height' => 300,
	'sig_images_max_limit' => 1,
	'index_new_reg_users' => 0,
	'max_sig_lines' => 4,
	'enable_sig_editor' => 0,
	'profile_show_sig' => 0,
	'board_hits' => 0,
	'board_serverload' => 0,
	'post_images_max_width' => 400,
	'post_images_max_height' => 300,
	'post_images_max_limit' => 3,
	'search_enable' => 1,
	'search_footer' => 0,
	'search_forum' => 0,
	'shoutbox_position' => 0,
	'jump_to_topic' => 0,
	'reduce_bbcode_imgs' => 0,
	'wrap_enable' => 0,
	'wrap_min' => 50,
	'wrap_max' => 99,
	'wrap_def' => 80,
	'enable_custom_post_color' => 0,
	'viewtopic_profilephoto' => 0,
	'visit_counter_index' => 0,
	'daily_post_limit' => 0,
	'viewtopic_editdate' => 0,
	'viewtopic_status' => 0,
	'ina_players_index' => 0,
	'enable_bancards' => 0, 
	'enable_kicker' => 0, 
	'viewtopic_downpost' => 0, 
	'viewtopic_viewpost' => 0
);
while ( list ( $config_name, $config_value ) = each ( $config_data ) )
{
	$sql = "INSERT INTO " . $table_prefix . "config (`config_name`, `config_value`) 
		VALUES ('" . $config_name . "', '" . $config_value . "')";
	_sql($sql, $errored, $error_ary);
}		

// Remove config fields... we don't need these anymore
$rconfig_data = array( 
	'ina_online_list_color',
	'ina_online_list_text'
);
	
for ( $i = 0; $i < sizeof($rconfig_data); $i++ )
{
	$sql = "DELETE FROM " . $table_prefix . "config 
		WHERE `config_name` = '" . $rconfig_data[$i] . "'";
	_sql($sql, $errored, $error_ary);
}

// Move phpbb_forums_config data to phpbb_config
$sql = "INSERT INTO " . $table_prefix . "config SELECT * FROM " . $table_prefix . "forums_config";
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS '. $table_prefix .'forums_config';
_sql($sql, $errored, $error_ary);

// Move phpbb_news data to phpbb_config
$sql = "INSERT INTO " . $table_prefix . "config SELECT * FROM " . $table_prefix . "news";
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS '. $table_prefix .'news';
_sql($sql, $errored, $error_ary);

// Move phpbb_kb_config data to phpbb_config, and rename all fields with kb_ prefix
$sql = "UPDATE " . $table_prefix . "kb_config SET `config_name` = 'kb_admin_id' WHERE `config_name` = 'admin_id'";
_sql($sql, $errored, $error_ary);

$sql = "UPDATE " . $table_prefix . "kb_config SET `config_name` = 'kb_approve_edit' WHERE `config_name` = 'approve_edit'";
_sql($sql, $errored, $error_ary);

$sql = "UPDATE " . $table_prefix . "kb_config SET `config_name` = 'kb_approve_new' WHERE `config_name` = 'approve_new'";
_sql($sql, $errored, $error_ary);

$sql = "UPDATE " . $table_prefix . "kb_config SET `config_name` = 'kb_allow_anon' WHERE `config_name` = 'allow_anon'";
_sql($sql, $errored, $error_ary);

$sql = "UPDATE " . $table_prefix . "kb_config SET `config_name` = 'kb_allow_edit' WHERE `config_name` = 'allow_edit'";
_sql($sql, $errored, $error_ary);

$sql = "UPDATE " . $table_prefix . "kb_config SET `config_name` = 'kb_allow_new' WHERE `config_name` = 'allow_new'";
_sql($sql, $errored, $error_ary);

$sql = "UPDATE " . $table_prefix . "kb_config SET `config_name` = 'kb_comments' WHERE `config_name` = 'comments'";
_sql($sql, $errored, $error_ary);

$sql = "UPDATE " . $table_prefix . "kb_config SET `config_name` = 'kb_del_topic' WHERE `config_name` = 'del_topic'";
_sql($sql, $errored, $error_ary);

$sql = "UPDATE " . $table_prefix . "kb_config SET `config_name` = 'kb_forum_id' WHERE `config_name` = 'forum_id'";
_sql($sql, $errored, $error_ary);

$sql = "UPDATE " . $table_prefix . "kb_config SET `config_name` = 'kb_notify' WHERE `config_name` = 'notify'";
_sql($sql, $errored, $error_ary);

$sql = "UPDATE " . $table_prefix . "kb_config SET `config_name` = 'kb_pt_body' WHERE `config_name` = 'pt_body'";
_sql($sql, $errored, $error_ary);

$sql = "UPDATE " . $table_prefix . "kb_config SET `config_name` = 'kb_show_pt' WHERE `config_name` = 'show_pretext'";
_sql($sql, $errored, $error_ary);
		
$sql = "INSERT INTO " . $table_prefix . "config SELECT * FROM " . $table_prefix . "kb_config";
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS '. $table_prefix .'kb_config';
_sql($sql, $errored, $error_ary);


//
// Modify Fully Modded core-data
//
// phpbb_advance_html data
$advance_html_data = array('announcement_text_draft', 'announcement_text', 'announcement_guest_text', 'admin_notes');
for ( $i = 0; $i < 4; $i++ )
{
	$sql = "INSERT INTO " . $table_prefix . "advance_html (`config_name`, `config_value`) VALUES ('" . $advance_html_data[$i] . "', '')";
	_sql($sql, $errored, $error_ary);
}

$sql = "INSERT INTO " . $table_prefix . "advance_html (`config_name`, `config_value`) VALUES ('announcement_status', '0')";
_sql($sql, $errored, $error_ary);

$sql = "INSERT INTO " . $table_prefix . "advance_html (`config_name`, `config_value`) VALUES ('announcement_access', '0')";
_sql($sql, $errored, $error_ary);
	
$sql = "INSERT INTO " . $table_prefix . "advance_html (`config_name`, `config_value`) VALUES ('announcement_guest_status', '0')";
_sql($sql, $errored, $error_ary);

// phpbb_pages data
$pages_data = array( 
	array( PageName => 'activity.php', PageKey => 'activity.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'album.php', PageKey => 'album.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'attachments.php', PageKey => 'attachments.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'auctions.php', PageKey => 'auctions.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'avatarsuite_listavatars.php', PageKey => 'avatarsuite_listavatars.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'avatarsuite_toplist.php', PageKey => 'avatarsuite_toplist.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'avatarsuite_vote.php', PageKey => 'avatarsuite_vote.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'bank.php', PageKey => 'bank.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'banlist.php', PageKey => 'banlist.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'bookies.php', PageKey => 'bookies.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'bookie_allstats.php', PageKey => 'bookie_allstats.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'calendar.php', PageKey => 'calendar.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'charts.php', PageKey => 'charts.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'chatroom.php', PageKey => 'chatroom.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'digests.php', PageKey => 'digests.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'dload.php', PageKey => 'dload.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'donate.php', PageKey => 'donate.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'faq.php', PageKey => 'faq.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'getdaily.php', PageKey => 'getdaily.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'groupcp.php', PageKey => 'groupcp.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'groupmsg.php', PageKey => 'groupmsg.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'helpdesk.php', PageKey => 'helpdesk.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'index.php', PageKey => 'index.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'index_all.php', PageKey => 'index_all.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'imclient.php', PageKey => 'imclient.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'imlist.php', PageKey => 'imlist.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'jobs.php', PageKey => 'jobs.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'kb.php', PageKey => 'kb.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'kb_search.php', PageKey => 'kb_search.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'lexicon.php', PageKey => 'lexicon.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'linkdb.php', PageKey => 'linkdb.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'lottery.php', PageKey => 'lottery.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'meeting.php', PageKey => 'meeting.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'memberlist.php', PageKey => 'memberlist.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'modcp.php', PageKey => 'modcp.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'pointscp.php', PageKey => 'pointscp.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'portal.php', PageKey => 'portal.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'privmsg.php', PageKey => 'privmsg.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'privmsg_export.php', PageKey => 'privmsg_export.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'profile.php', PageKey => 'profile.php?mode=viewprofile', ParmName => 'mode', ParmValue => 'viewprofile' ),
	array( PageName => 'profile_attachments.php', PageKey => 'profile_attachments.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'profile_biorhythm.php', PageKey => 'profile_biorhythm.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'profile_donations.php', PageKey => 'profile_donations.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'profile_myinfo.php', PageKey => 'profile_myinfo.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'profile_mypoints.php', PageKey => 'profile_mypoints.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'profile_mypoints_from.php', PageKey => 'profile_mypoints_from.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'profile_mypoints_to.php', PageKey => 'profile_mypoints_to.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'profile_notes.php', PageKey => 'profile_notes.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'profile_sig_editor.php', PageKey => 'profile_sig_editor.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'profile_subscriptions.php', PageKey => 'profile_subscriptions.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'profile_topics.php', PageKey => 'profile_topics.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'profile_topics_watched.php', PageKey => 'profile_topics_watched.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'profile_views.php', PageKey => 'profile_views.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'ratings.php', PageKey => 'ratings.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'rss.php', PageKey => 'rss.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'search.php', PageKey => 'search.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'shop.php', PageKey => 'shop.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'shop_transactions.php', PageKey => 'shop_transactions.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'shop_users.php', PageKey => 'shop_users.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'shoutbox.php', PageKey => 'shoutbox.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'shoutbox_max.php', PageKey => 'shoutbox_max.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'sitemap.php', PageKey => 'sitemap.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'smilies.php', PageKey => 'smilies.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'staff.php', PageKey => 'staff.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'statistics.php', PageKey => 'statistics.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'tellafriend.php', PageKey => 'tellafriend.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'thread_kicker.php', PageKey => 'thread_kicker.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'transactions.php', PageKey => 'transactions.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'top_referrals.php', PageKey => 'top_referrals.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'topic_view_users.php', PageKey => 'topic_view_users.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'toplist.php', PageKey => 'toplist.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'tour.php', PageKey => 'tour.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'viewtopic_posted.php', PageKey => 'viewtopic_posted.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'viewforum.php', PageKey => 'viewforum.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'viewonline.php', PageKey => 'viewonline.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'viewpost.php', PageKey => 'viewpost.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'viewtopic.php', PageKey => 'viewtopic.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'viewtopic_print.php', PageKey => 'viewtopic_print.php', ParmName => '', ParmValue => '' ),
	array( PageName => 'viewtopic_viewed.php', PageKey => 'viewtopic_viewed.php', ParmName => '', ParmValue => '' )
);
for ( $row = 0; $row < sizeof($pages_data); $row++ )
{
	for ( $column = 0; $column < 1; $column++ )
	{
		$sql = "INSERT INTO " . $table_prefix . "pages (`page_name`, `page_key`, `page_parm_name`, `page_parm_value`) 
		VALUES ('" . $pages_data[$row]['PageName'] . "', '" . $pages_data[$row]['PageKey'] . "', '" . $pages_data[$row]['ParmName'] . "', '" . $pages_data[$row]['ParmValue'] . "')";
		_sql($sql, $errored, $error_ary);
	}
}	


//
// Change default values to sync with FullyModded setup
//
$sql = "UPDATE " . $table_prefix . "config SET `config_value` = 'cache' WHERE `config_name` = 'newsfeed_cache'";
_sql($sql, $errored, $error_ary);

?>