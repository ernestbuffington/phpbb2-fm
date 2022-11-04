<?php

if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}

//
// Modify phpBB core-schema
//
$sql = 'ALTER TABLE '. $table_prefix .'groups ADD COLUMN `group_colored` TINYINT(1) NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE '. $table_prefix .'groups ADD COLUMN `group_colors` TEXT NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE '. $table_prefix .'groups ADD COLUMN `group_order` INTEGER(255) NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE '. $table_prefix .'users ADD COLUMN `group_priority` INTEGER(255) NOT NULL';
_sql($sql, $errored, $error_ary);


//
// Create new Fully Modded core-schema
//
$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'posts_ignore_sigav';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'posts_ignore_sigav (`user_id` mediumint(8) UNSIGNED DEFAULT "0" NOT NULL, `hid_id` mediumint(8) UNSIGNED DEFAULT "0" NOT NULL) TYPE=MyISAM';
_sql($sql, $errored, $error_ary);

$sql = 'CREATE TABLE IF NOT EXISTS ' . $table_prefix . 'avatartoplist (`avatar_filename` TEXT NOT NULL, `avatar_type` tinyint(4) NOT NULL default "0", `voter_id` mediumint(8) NOT NULL, `voting` mediumint(8) NOT NULL, `comment` text NOT NULL, INDEX `voter_id` (`voter_id`)) TYPE=MyISAM';
_sql($sql, $errored, $error_ary);

$sql = 'CREATE TABLE IF NOT EXISTS  ' . $table_prefix . 'privmsgs_archive (privmsgs_id mediumint(8) unsigned NOT NULL AUTO_INCREMENT, privmsgs_type tinyint(4) NOT NULL default "0", privmsgs_subject varchar(255) NOT NULL default "0", privmsgs_from_userid mediumint(8) NOT NULL default "0", privmsgs_to_userid mediumint(8) NOT NULL default "0", privmsgs_date int(11) NOT NULL default "0", privmsgs_ip varchar(8) NOT NULL default "", privmsgs_enable_bbcode tinyint(1) NOT NULL default "1", privmsgs_enable_html tinyint(1) NOT NULL default "0", privmsgs_enable_smilies tinyint(1) NOT NULL default "1", privmsgs_attach_sig tinyint(1) NOT NULL default "1", PRIMARY KEY (privmsgs_id), KEY privmsgs_from_userid (privmsgs_from_userid), KEY privmsgs_to_userid (privmsgs_to_userid)) TYPE=MyISAM';
_sql($sql, $errored, $error_ary);


//
// Modify Fully Modded core-schema
//
$sql = 'ALTER TABLE '. $table_prefix .'lottery_history DROP COLUMN `currency`';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE '. $table_prefix .'portal ADD `portal_iframe_height` VARCHAR(4) NOT NULL DEFAULT "600"';
_sql($sql, $errored, $error_ary);


//
// Modify phpBB core-data
//
// phpbb_config data
$config_data = array( 
	'enable_user_notes' => 0,
	'enable_ignore_sigav' => 0,
	'shoutbox_height' => 250,
	'minical_upcoming' => 0,
	'minical_event_lmt' => 5,
	'minical_search' => 'POSTS',
	'enable_http_referrers' => 0,
	'enable_avatar_register' => 0,
	'enable_coppa' => 0,
	'ts_sitetitle' => 'Teamspeak',
	'ts_serveraddress' => '127.0.0.1',
	'ts_serverqueryport' => 51234,
	'ts_serverudpport' => 8767,
	'ts_refreshtime' => 300,
	'ts_winheight' => '',
	'ts_serverpasswort' => 'password',
	'forum_module_teamspeak' => 0,				
	'vote_min_posts' => 0,
	'referral_viewtopic' => 0,
	'hl_enable' => 0,
	'hl_necessary_post_number' => 0,
	'hl_mods_priority' => 1,
	'enable_tellafriend' => 0,
	'enable_topic_view_users' => 0
);
while ( list ( $config_name, $config_value ) = each ( $config_data ) )
{
	$sql = "INSERT INTO " . $table_prefix . "config (`config_name`, `config_value`) 
		VALUES ('" . $config_name . "', '" . $config_value . "')";
	_sql($sql, $errored, $error_ary);
}		

// Remove config fields... we don't need these anymore
$rconfig_data = array( 
	'aprvmView',
	'aprvmVersion'
);
	
for ( $i = 0; $i < sizeof($rconfig_data); $i++ )
{
	$sql = "DELETE FROM " . $table_prefix . "config 
		WHERE `config_name` = '" . $rconfig_data[$i] . "'";
	_sql($sql, $errored, $error_ary);
}


//
// Modify Fully Modded core-data
//
// phpbb_config_nav data
// lets get this right... phpBB updaters missed out last build
$sql = 'DELETE FROM ' . $table_prefix . 'config_nav WHERE `url` = "members.php"';
_sql($sql, $errored, $error_ary);
$sql = 'DELETE FROM ' . $table_prefix . 'config_nav WHERE `url` = "memberlist.php"';
_sql($sql, $errored, $error_ary);
$sql = 'DELETE FROM ' . $table_prefix . 'config_nav WHERE `url` = "kb.php"';
_sql($sql, $errored, $error_ary);
$sql = 'DELETE FROM ' . $table_prefix . 'config_nav WHERE `url` = "lexicon.php"';
_sql($sql, $errored, $error_ary);

$config_nav_data = array( 
	array( Img => 'icon_mini_members.gif', Alt => 'Members', Url => 'memberlist.php' ),
	array( Img => 'icon_mini_faq.gif', Alt => 'Knowledge Base', Url => 'kb.php' ),
	array( Img => 'icon_mini_faq.gif', Alt => 'Lexicon', Url => 'lexicon.php' )
);
for ( $row = 0; $row < sizeof($config_nav_data); $row++ )
{
	for ( $column = 0; $column < 1; $column++ )
	{
		$sql = "INSERT INTO " . $table_prefix . "config_nav (`img`, `alt`, `use_lang`, `url`, `nav_order`, `value`) VALUES ('" . $config_nav_data[$row]['Img'] . "', '" . $config_nav_data[$row]['Alt'] . "', '1', '" . $config_nav_data[$row]['Url'] . "', '300', '0')";
		_sql($sql, $errored, $error_ary);
	}
}

// phpbb_im_sites data
$sql = "DELETE FROM " . $table_prefix . "im_sites WHERE `site_name` = 'Fully Modded phpBB'";
_sql($sql, $errored, $error_ary);

// Move phpbb_logs_config data to phpbb_config
$sql = "INSERT INTO " . $table_prefix . "config SELECT * FROM " . $table_prefix . "logs_config";
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS '. $table_prefix .'logs_config';
_sql($sql, $errored, $error_ary);


//
// Change default values to sync with FullyModded setup
//
$sql = "UPDATE " . $table_prefix . "config SET `config_value` = 'cache' WHERE `config_name` = 'newsfeed_cache'";
_sql($sql, $errored, $error_ary);

$sql = "UPDATE " . $table_prefix . "pa_config SET `config_value` = 'file_time' WHERE `config_name` = 'sort_method'";
_sql($sql, $errored, $error_ary);
	
@rename($phpbb_root_path . 'cache/page_perms.'.$phpEx, $phpbb_root_path . 'cache/config_page_perms.'.$phpEx);	

?>