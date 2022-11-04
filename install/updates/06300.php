<?php
	
if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}


//
// Modify phpBB core-scema	
//
$sql = 'ALTER TABLE ' . $prefix . 'auth_access ADD COLUMN `auth_globalannounce` TINYINT(1)';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'auth_access MODIFY COLUMN `forum_id` SMALLINT(5) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $prefix . 'forums ADD COLUMN `answered_enable` TINYINT(1) NOT NULL DEFAULT "0"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'forums ADD COLUMN `auth_globalannounce` TINYINT(2) DEFAULT "3" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'forums ADD COLUMN `icon_enable` TINYINT(1) NOT NULL DEFAULT "0"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'forums ADD COLUMN `move_next` INT(11) DEFAULT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'forums ADD COLUMN `move_enable` TINYINT(1) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'forums ADD COLUMN `forum_external` TINYINT(4) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'forums ADD COLUMN `forum_redirect_url` TEXT';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'forums ADD COLUMN `forum_redirects_user` MEDIUMINT(8) UNSIGNED DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'forums ADD COLUMN `forum_redirects_guest` MEDIUMINT(8) UNSIGNED DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'forums MODIFY COLUMN `forum_id` MEDIUMINT(5) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'forums MODIFY COLUMN `cat_id` MEDIUMINT(8) DEFAULT "0"'; 
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $prefix . 'groups ADD COLUMN `group_allow_pm` TINYINT(2) DEFAULT "5" NOT NULL';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $prefix . 'smilies ADD COLUMN `smilies_order` INT(5) NOT NULL';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $prefix . 'users ADD COLUMN `user_allow_mass_pm` TINYINT(1) DEFAULT "2"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'users ADD COLUMN `user_allowswearywords` TINYINT(1) NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'users ADD COLUMN `user_inactive_emls` TINYINT(1) NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'users ADD COLUMN `user_inactive_last_eml` INT(11) NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'users ADD COLUMN `user_clockformat` VARCHAR(10) DEFAULT "Clock01.swf" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'users ADD COLUMN `user_allow_profile` TINYINT(1) DEFAULT "1" NOT NULL';
_sql($sql, $errored, $error_ary);
			

//
// Modify Fully Modded core-schema
//
$sql = 'ALTER TABLE ' . $prefix . 'kb_articles ADD COLUMN `username` VARCHAR(255)';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'kb_articles ADD COLUMN `topic_id` MEDIUMINT(8) UNSIGNED DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'kb_articles ADD COLUMN `views` MEDIUMINT(8) UNSIGNED DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'kb_articles MODIFY COLUMN `article_id` MEDIUMINT(8) UNSIGNED NOT NULL auto_increment';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'kb_articles MODIFY COLUMN `article_category_id` MEDIUMINT(8) UNSIGNED DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'kb_articles MODIFY COLUMN `article_title` VARCHAR(255) BINARY DEFAULT "" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'kb_articles MODIFY COLUMN `article_description` VARCHAR(255) BINARY DEFAULT "" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'kb_articles MODIFY COLUMN `article_date` VARCHAR(255) BINARY DEFAULT "" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'kb_articles MODIFY COLUMN `article_author_id` MEDIUMINT(8) UNSIGNED DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'kb_articles MODIFY COLUMN `bbcode_uid` VARCHAR(10) BINARY DEFAULT "" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'kb_articles MODIFY COLUMN `article_type` MEDIUMINT(8) UNSIGNED DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'kb_articles MODIFY COLUMN `approved` tinyint(1) UNSIGNED DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'kb_articles DROP `article_keywords`';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'kb_articles DROP `topic_link`';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'kb_articles DROP INDEX `article_id`, ADD KEY `article_id` (article_id)';
_sql($sql, $errored, $error_ary);
		     
$sql = 'ALTER TABLE ' . $prefix . 'kb_categories ADD COLUMN `parent` MEDIUMINT(8)';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'kb_categories ADD COLUMN `cat_order` MEDIUMINT(8) UNSIGNED NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'kb_categories MODIFY COLUMN `category_id` MEDIUMINT(8) UNSIGNED NOT NULL auto_increment';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'kb_categories MODIFY COLUMN `category_name` VARCHAR(255) BINARY NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'kb_categories MODIFY COLUMN `category_details` VARCHAR(255) BINARY NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'kb_categories MODIFY COLUMN `number_articles` MEDIUMINT(8) UNSIGNED';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'kb_categories DROP INDEX `category_id`, ADD KEY `category_id` (category_id)';
_sql($sql, $errored, $error_ary);
		      
$sql = 'ALTER TABLE ' . $prefix . 'kb_types MODIFY COLUMN `id` MEDIUMINT(8) UNSIGNED NOT NULL auto_increment';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'kb_types MODIFY COLUMN `type` VARCHAR(255) BINARY DEFAULT "" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'kb_types DROP INDEX `id`, ADD KEY `id` (id)';			
_sql($sql, $errored, $error_ary);
		      
$sql = 'ALTER TABLE ' . $prefix . 'portal ADD COLUMN `portal_clock` TINYINT(1) DEFAULT "0"';	
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $prefix . 'referral MODIFY COLUMN `ruid` VARCHAR(7) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $prefix . 'referral MODIFY COLUMN `nuid` VARCHAR(7) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);


//
// Create new Fully Modded core-schema
//
$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'forum_move';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $prefix . 'forum_move (`move_id` MEDIUMINT(8) UNSIGNED NOT NULL auto_increment, `forum_id` SMALLINT(5) UNSIGNED DEFAULT "0" NOT NULL, `forum_dest` SMALLINT(5) UNSIGNED DEFAULT "0" NOT NULL, `move_days` smallint(5) UNSIGNED NOT NULL DEFAULT "0", `move_freq` SMALLINT(5) UNSIGNED NOT NULL DEFAULT "0", PRIMARY KEY (move_id), KEY (forum_id))';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'optimize_db';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $prefix . 'optimize_db (`cron_enable` ENUM("0","1") DEFAULT "0" NOT NULL, `cron_every` INT(7) DEFAULT "86400" NOT NULL, `cron_next` INT(11) DEFAULT "0" NOT NULL, `cron_count` INT(5) DEFAULT "0" NOT NULL, `show_tables` VARCHAR(150) DEFAULT "" NOT NULL)';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'notes';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $prefix . 'notes (`post_id` MEDIUMINT(8) UNSIGNED NOT NULL auto_increment, `poster_id` MEDIUMINT(8) DEFAULT "0" NOT NULL, `post_subject` VARCHAR(120) DEFAULT NULL, `post_text` TEXT, `post_time` INT(11) DEFAULT "0" NOT NULL, PRIMARY KEY (post_id), KEY `poster_id` (poster_id), KEY `post_time` (post_time)) TYPE=MYISAM';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'toplist_anti_flood';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $prefix . 'toplist_anti_flood (`id` int(255) NOT NULL, `ip` VARCHAR(8) NOT NULL, `time` INT(11) NOT NULL, `type` VARCHAR(3) NOT NULL) TYPE=MYISAM';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'kb_results';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $prefix . 'kb_results (`search_id` INT(11) UNSIGNED DEFAULT "0" NOT NULL, `session_id` VARCHAR(32) DEFAULT "" NOT NULL, `search_array` TEXT NOT NULL, PRIMARY KEY (search_id), KEY `session_id` (session_id))';
_sql($sql, $errored, $error_ary);
			
$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'kb_wordlist';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $prefix . 'kb_wordlist (`word_text` VARCHAR(50) BINARY DEFAULT "0" NOT NULL, `word_id` MEDIUMINT(8) UNSIGNED NOT NULL auto_increment, `word_common` TINYINT(1) UNSIGNED DEFAULT "0" NOT NULL, PRIMARY KEY (word_text), KEY `word_id` (word_id))';
_sql($sql, $errored, $error_ary);
			
$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'kb_wordmatch';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $prefix . 'kb_wordmatch (`article_id` MEDIUMINT(8) UNSIGNED DEFAULT "0" NOT NULL, `word_id` MEDIUMINT(8) UNSIGNED DEFAULT "0" NOT NULL, `title_match` TINYINT(1) DEFAULT "0" NOT NULL, KEY `post_id` (article_id), KEY `word_id` (word_id))';
_sql($sql, $errored, $error_ary);
			
$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'forums_descrip';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $prefix . 'forums_descrip (`forum_id` INT(11) DEFAULT "0", `user_id` INT(11) DEFAULT "0", `view` TINYINT(4) DEFAULT "1")';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS shoutbox';
_sql($sql, $errored, $error_ary);
$sql = 'DROP TABLE IF EXISTS ' . $prefix . 'shout';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $prefix . 'shout (`shout_id` MEDIUMINT(8) UNSIGNED NOT NULL auto_increment, `shout_username` VARCHAR(25) NOT NULL, `shout_user_id` MEDIUMINT(8) NOT NULL, `shout_group_id` MEDIUMINT(8) NOT NULL, `shout_session_time` INT(11) NOT NULL, `shout_ip` CHAR(8) NOT NULL, `shout_text` TEXT NOT NULL, `shout_active` MEDIUMINT(8) NOT NULL, `enable_bbcode` TINYINT (1) NOT NULL, `enable_html` TINYINT (1) NOT NULL, `enable_smilies` TINYINT (1) NOT NULL, `enable_sig` TINYINT (1) NOT NULL, `shout_bbcode_uid` VARCHAR(10) NOT NULL, INDEX (shout_id))';
_sql($sql, $errored, $error_ary);


//
// Modify phpBB core-data
// phpbb_config data
$sql = 'UPDATE ' . $prefix . 'config SET `config_name` = "toplist_view_hin_hits" WHERE `config_name` = "toplist_view_in_hits"';
_sql($sql, $errored, $error_ary);

$config_data = array( 'allow_swearywords' => 0, 'default_clock' => 'Clock001.swf', 'gender_required' => 0, 'locked_last' => 0, 'logo_url' => 'index.php', 'notes' => 20, 'prune_shouts' => 0, 'referral_top_limit' => 5, 'smilies_insert' => 1, 'toplist_anti_flood_hin_hits_interval' => 0, 'toplist_anti_flood_img_hits_interval' => 0, 'toplist_anti_flood_out_hits_interval' => 0, 'toplist_dimensions' => '', 'toplist_prune_hin_hits_interval' => 0, 'toplist_prune_hin_hits_last' => '', 'toplist_prune_img_hits_interval' => 0, 'toplist_prune_img_hits_last' => '', 'toplist_prune_out_hits_interval' => 0, 'toplist_prune_out_hits_last' => '', 'toplist_toplist_top10' => 0, 'toplist_version' => '1.3.8' );
while ( list ( $config_name, $config_value ) = each ( $config_data ) )
{
	$sql = "INSERT INTO " . $prefix . "config (`config_name`, `config_value`) VALUES ('" . $config_name . "', '" . $config_value . "')";
	_sql($sql, $errored, $error_ary);
}	
			
// phpbb_forums data
$sql = 'INSERT IGNORE INTO ' . $prefix . 'forums (`forum_id`, `cat_id`, `forum_name`, `forum_desc`, `forum_status`) VALUES ("-7", "0", "* MEMBERLIST PERMISSIONS", "Memberlist Control", 1)';
_sql($sql, $errored, $error_ary);


//
// Modify Fully Modded core-data
// phpbb_attachments_config data
$sql = 'INSERT INTO '. $prefix .'attachments_config (config_name, config_value) VALUES ("use_gd2","0")';
_sql($sql, $errored, $error_ary);

// phpbb_forums_config data
$forum_config_data = array( 'forum_module_clock' => 0, 'forum_module_quote' => 0, 'glance_forum_discuss_title' => 'Recent Discussions', 'glance_news_num' => 5, 'glance_forum_news_title' => 'Important News', 'glance_recent_num' => 5 );
while ( list ( $config_name, $config_value ) = each ( $forum_config_data ) )
{
	$sql = "INSERT INTO " . $prefix . "forums_config (`config_name`, `config_value`) VALUES ('" . $config_name . "', '" . $config_value . "')";
	_sql($sql, $errored, $error_ary);
}

// phpbb_kb_config data
$kb_config_data = array( 'allow_anon' => 0, 'forum_id' => 1, 'comments' => 1, 'del_topic' => 1 );	
while ( list ( $config_name, $config_value ) = each ( $kb_config_data ) )
{
	$sql = "INSERT INTO " . $prefix . "kb_config (`config_name`, `config_value`) VALUES ('" . $config_name . "', '" . $config_value . "')";
	_sql($sql, $errored, $error_ary);
}

// phpbb_optimize_db data
$sql = "INSERT INTO " . $prefix . "optimize_db (`cron_enable`, `cron_every`, `cron_next`, `cron_count`) VALUES ('1', '86400', '0', '0')";
_sql($sql, $errored, $error_ary);
	
$sql = "UPDATE " . $prefix . "optimize_db SET `cron_enable` = '0'";
_sql($sql, $errored, $error_ary);


//
// Change default values to sync with FullyModded setup
//
$sql = 'UPDATE ' . $prefix . 'album_config SET `config_value` = "0" WHERE `config_name` = "fullpic_popup"';
_sql($sql, $errored, $error_ary);

?>