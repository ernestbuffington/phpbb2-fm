<?php
	
if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}


//
// Modify phpBB core-scema	
//
$sql = 'ALTER TABLE ' . $prefix . 'forums ADD COLUMN `forum_template` MEDIUMINT(8) UNSIGNED DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'forums ADD COLUMN `stop_bumping` TINYINT(1) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $prefix . 'smilies CHANGE `smilies_order` `smilies_order` SMALLINT(5) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'smilies ADD COLUMN `cat_id` SMALLINT(5) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $prefix . 'themes ADD COLUMN `adminbold` TINYINT(1) DEFAULT "1" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'themes ADD COLUMN `supermodbold` TINYINT(1) DEFAULT "1" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'themes ADD COLUMN `modbold` TINYINT(1) DEFAULT "1" NOT NULL';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $prefix . 'user_group ADD COLUMN `group_moderator` TINYINT(1) NOT NULL';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $prefix . 'users ADD COLUMN `user_jobs` INT(11) NOT NULL DEFAULT "0"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'users ADD COLUMN `user_notify_pm_text` tinyint(1) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'users ADD COLUMN `user_ftr` SMALLINT(1) NOT NULL DEFAULT "0"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'users ADD COLUMN `user_ftr_time` INT(11) NOT NULL DEFAULT "0"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'users ADD COLUMN `email_validation` tinyint(1) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);


//
// Create new Fully Modded core-schema
//
$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'guestbook';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $prefix . 'guestbook (`id` mediumint(8) unsigned NOT NULL auto_increment, `user_id` mediumint(8) NOT NULL default "0", `nick` varchar(25) NOT NULL default "", `data_ora` int(11) NOT NULL default "0", `email` varchar(255) default NULL, `sito` varchar(100) default NULL, `comento` text, `bbcode_uid` varchar(10) default NULL, `ipi` varchar(8) NOT NULL default "", `agent` varchar(255) NOT NULL default "", `hide` tinyint(1) default "0", PRIMARY KEY (`id`)) TYPE=MyISAM';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'guestbook_config';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $prefix . 'guestbook_config (`config_name` varchar(255) NOT NULL default "", `config_value` varchar(255) NOT NULL default "", PRIMARY KEY (`config_name`)) TYPE=MyISAM';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'jobs';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $prefix . 'jobs (`id` mediumint(8) NOT NULL auto_increment, `name` varchar(32) NOT NULL default "", `pay` mediumint(8) default "100", `type` varchar(32) default "public", `requires` text, `payouttime` mediumint(8) default "500000", `positions` mediumint(8) NOT NULL default "0", PRIMARY KEY (`id`), KEY `name` (`name`)) TYPE=MyISAM';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'jobs_employed';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $prefix . 'jobs_employed (`id` mediumint(8) NOT NULL auto_increment, `user_id` mediumint(8) NOT NULL, `job_name` varchar(32) NOT NULL default "", `job_pay` mediumint(8) NOT NULL, `job_length` mediumint(8) NOT NULL, `last_paid` mediumint(8) NOT NULL, `job_started` mediumint(8) NOT NULL, PRIMARY KEY (`id`)) TYPE=MyISAM';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'smilies_cat';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $prefix . 'smilies_cat (`cat_id` smallint(3) unsigned NOT NULL auto_increment, `cat_name` varchar(50) NOT NULL default "", `description` varchar(100) NOT NULL default "", `cat_order` smallint(3) NOT NULL default "0", `cat_perms` tinyint(2) NOT NULL default "10", `cat_group` varchar(255) default NULL, `cat_forum` mediumtext NOT NULL, `cat_special` tinyint(1) NOT NULL default "-2", `cat_open` tinyint(1) NOT NULL default "1", `cat_icon_url` varchar(100) default NULL, `smilies_popup` varchar(20) NOT NULL default "", PRIMARY KEY (`cat_id`))';
_sql($sql, $errored, $error_ary);


//
// Modify Fully Modded core-schema
//
// Preivious FM updaters need this column type added!
$sql = 'ALTER TABLE ' . $prefix . 'forums ADD COLUMN `index_posts` TINYINT(1) DEFAULT "1" NOT NULL';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $prefix . 'portal ADD COLUMN `portal_char_limit` MEDIUMINT(8) NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'portal ADD COLUMN `portal_latest_scrolling` TINYINT(1) NOT NULL DEFAULT "0"';					
_sql($sql, $errored, $error_ary);


//
// Modify phpBB core-data
//
// phpbb_config data
$config_data = array( 
	'dislay_x_donors' => 10, 
	'donate_start_time' => '', 
	'donate_end_time' => '', 
	'donate_cur_goal' => 0, 
	'donate_description' => '', 
	'donate_to_points' => 0, 
	'paypal_b_acct' => $board_config['board_email'], 
	'donate_to_posts' => 0, 
	'list_top_donors' => 0, 
	'donate_to_grp_one' => 0, 
	'to_grp_one_amount' => 0, 
	'donate_to_grp_two' => 0, 
	'to_grp_two_amount' => 0, 
	'donor_rank_id' => 0, 
	'donate_currencies' => '', 
	'usd_to_primary' => 0, 
	'eur_to_primary' => 0, 
	'gbp_to_primary' => 0, 
	'cad_to_primary' => 0, 
	'jpy_to_primary' => 0, 
	'message_minlength' => 2, 
	'enable_autobackup' => 0, 
	'avatar_toplist' => 0, 
	'avatar_voting_viewtopic' => 0, 
	'stop_bumping' => 0, 
	'enable_confirm_posting' => 1, 
	'ftr_msg' => 'Sorry *u*, you need to read our topic: "*t*" for new users. After you read it, you can proceed to browse our posts normally. Please click *l* to view the post.', 
	'ftr_topic' => 0, 
	'ftr_active' => 0, 
	'ftr_who' => 1, 
	'ftr_installed' => time(), 
	'post_edit_time_limit' => -1, 
	'enable_quick_reply' => 0, 
	'chat_index' => 0, 
	'gender_index' => 0, 
	'smilie_icon_path' => $board_config['smilies_path'], 
	'smilie_posting' => 1, 
	'smilie_popup' => 1, 
	'smilie_buttons' => 1, 
	'smilie_random' => 0, 
	'smilie_removal1' => 0, 
	'smilie_removal2' => 0, 
	'smilie_usergroups' => 0, 
	'capitalization' => 0, 
	'jobs_status' => 0, 
	'jobs_limit' => 2, 
	'jobs_pay_type' => 0, 
	'jobs_index_body' => 0, 
	'enable_spellcheck' => 0, 
	'privmsg_newuser_disable' => 1 
);
while ( list ( $config_name, $config_value ) = each ( $config_data ) )
{
	$sql = "INSERT INTO " . $prefix . "config (`config_name`, `config_value`) 
		VALUES ('" . $config_name . "', '" . $config_value . "')";
	_sql($sql, $errored, $error_ary);
}		
	
// Remove bank config fields again! Missed removing these from 'includes/functions_dbmtnc.php' config check
// Just in case anyone used this between updates....
$rconfig_data = array( 
	'smilies_insert', 
	'smilie_max_width', 
	'smilie_max_height', 
	'smilie_window_columns', 
	'bankholdings', 
	'banktotaldeposits', 
	'banktotalwithdrew' 
);
for ( $i = 0; $i < sizeof($rconfig_data); $i++ )
{
	$sql = "DELETE FROM " . $prefix . "config 
		WHERE `config_name` = '" . $rconfig_data[$i] . "'";
	_sql($sql, $errored, $error_ary);
}

// phpbb_forums data
$sql = 'INSERT IGNORE INTO ' . $prefix . 'forums (`forum_id`, `cat_id`, `forum_name`, `forum_desc`, `forum_status`, `auth_view`) 
	VALUES ("-5", "0", "* VIEWPROFILE PERMISSIONS", "Viewprofile Control", "1", "1")';
_sql($sql, $errored, $error_ary);


//
// Modify Fully Modded core-data
//
// phpbb_config_nav data
$sql = "INSERT INTO " . $prefix . "config_nav (`img`, `alt`, `use_lang`, `url`, `nav_order`, `value`) 
	VALUES ('icon_mini_forums.gif', 'Guestbook', 1, 'guestbook.php', 1000, 0)";
_sql($sql, $errored, $error_ary);

// phpbb_forums_config data
$forum_config_data = array( 
	'forum_module_donors' => 0, 
	'glance_news_scroll' => 0, 
	'glance_recent_scroll' => 0 
);
while ( list ( $config_name, $config_value ) = each ( $forum_config_data ) )
{
	$sql = "INSERT INTO " . $prefix . "forums_config (`config_name`, `config_value`) 
		VALUES ('" . $config_name . "', '" . $config_value . "')";
	_sql($sql, $errored, $error_ary);
}		

// phpbb_guestbook_config data
$gbook_config_data = array( 
	'version' => '2.2.0', 
	'enable_guestbook' => 1, 
	'maxlenght_posts' => 500, 
	'hide_posts' => 0, 
	'smilies_column' => 6, 
	'smilies_row' => 3, 
	'no_only_smilies' => 1, 
	'no_only_quote' => 1, 
	'word_wrap' => 1, 
	'contatore' => 0, 
	'word_wrap_length' => 32, 
	'session_posting' => 60, 
	'password' => 12345, 	
	'permit_mod' => 0 
);
while ( list ( $config_name, $config_value ) = each ( $gbook_config_data ) )
{
	$sql = "INSERT INTO " . $prefix . "guestbook_config (`config_name`, `config_value`) 
		VALUES ('" . $config_name . "', '" . $config_value . "')";
	_sql($sql, $errored, $error_ary);
}		


//
// Change default values to sync with FullyModded setup
//
$sql = 'UPDATE ' . $prefix . 'config 
	SET `config_name` = "message_maxlength" 
	WHERE `config_name` = "message_length"';
_sql($sql, $errored, $error_ary);

// phpbb_smilies_cat data
$sql = "INSERT INTO " . $prefix . "smilies_cat (`cat_id`, `cat_name`, `description`, `cat_order`, `cat_perms`, `cat_forum`, `cat_special`, `smilies_popup`) 
	VALUES ('1', 'phpBB', 'The default phpBB2 Smilies', '1', '10', '1 999', '-2', '410|300|8|1|0|0')";
_sql($sql, $errored, $error_ary);

// Update users avatars
$sql = "UPDATE " . $prefix . "posts p, " . $prefix . "users u 
	SET p.user_avatar = u.user_avatar, p.user_avatar_type = u.user_avatar_type 
	WHERE p.poster_id = u.user_id 
		AND u.user_avatar != '' 
		AND p.user_avatar = ''";
_sql($sql, $errored, $error_ary);


//
// Add existing smilies to default category
//
$sql = "SELECT * FROM " . $prefix . "smilies 
	ORDER BY `smilies_id`";
$result = _sql($sql, $errored, $error_ary, '');

$smilies = $db->sql_fetchrowset($result);
$total_smilies = sizeof($smilies);

for($i = 0; $i < $total_smilies; $i++)
{
	$smilies_id = $smilies[$i]['smilies_id'];

	$sql = "UPDATE " . $prefix . "smilies 
		SET `cat_id` = 1, `smilies_order` = " . ($i + 1) . " 
		WHERE `smilies_id` = " . $smilies_id;
	_sql($sql, $errored, $error_ary);
}
$db->sql_freeresult($result);
	
?>