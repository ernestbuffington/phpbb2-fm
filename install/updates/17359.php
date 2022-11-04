<?php
	
if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}


//
// Modify phpBB core-scema	
//
$sql = 'ALTER TABLE ' . $table_prefix . 'forums ADD COLUMN `forum_rules` TEXT NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'forums ADD COLUMN `amazon_display` TINYINT(1)';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'forums ADD COLUMN `forum_thank` TINYINT(1) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'forums ADD COLUMN `forum_sort` TEXT NOT NULL';
_sql($sql, $errored, $error_ary);
			
$sql = 'ALTER TABLE ' . $table_prefix . 'topics ADD COLUMN `topic_priority` SMALLINT DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'topics TYPE=MYISAM';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'topics ADD FULLTEXT KEY topic_title (`topic_title`)';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $table_prefix . 'users MODIFY COLUMN `rating_status` TINYINT UNSIGNED';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'users ADD COLUMN `user_post_color` VARCHAR(6)';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'users ADD COLUMN `user_custom_post_color` VARCHAR(6)';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'users ADD COLUMN `user_xfi` VARCHAR(255)';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'users ADD COLUMN `user_skype` VARCHAR(255)';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'users ADD FULLTEXT (`user_skype`)';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'users ADD COLUMN `kick_ban` INT(2) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'users ADD COLUMN `user_info` TEXT';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'users ADD COLUMN `user_gtalk` VARCHAR(255)';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'users ADD COLUMN `user_stumble` VARCHAR(100)';
_sql($sql, $errored, $error_ary);


//
// Modify Fully Modded core-schema
//
$sql = 'ALTER TABLE ' . $table_prefix . 'attachments ADD KEY `post_id` (`post_id`)';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'attachments ADD KEY `privmsgs_id` (`privmsgs_id`)';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $table_prefix . 'banner ADD PRIMARY KEY (`banner_id`)';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'banner DROP INDEX `banner_id`';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $table_prefix . 'bookie_bets ADD COLUMN `admin_betid` INT(11) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'bookie_bets ADD COLUMN `each_way` INT(2) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'bookie_bets ADD COLUMN `bet_result` INT(2) DEFAULT "0" NOT NULL';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $table_prefix . 'ina_cheat_fix ADD COLUMN `game_count` INT(100) NOT NULL auto_increment PRIMARY KEY';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $table_prefix . 'ina_top_scores MODIFY COLUMN `player` MEDIUMINT(8) NOT NULL'; 
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $table_prefix . 'ina_games ADD COLUMN `jackpot` INT(10) NOT NULL DEFAULT "0"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'ina_games ADD COLUMN `game_popup` SMALLINT(1) NOT NULL DEFAULT "1"';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'ina_games ADD COLUMN `game_parent` SMALLINT(1) NOT NULL DEFAULT "1"';
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $table_prefix . 'portal DROP COLUMN `portal_latestx`';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'portal DROP COLUMN `portal_latestxresponses`';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'portal MODIFY COLUMN `portal_polls` VARCHAR(100) NOT NULL'; 
_sql($sql, $errored, $error_ary);

$sql = 'ALTER TABLE ' . $table_prefix . 'shop_transactions MODIFY COLUMN `trans_item` VARCHAR(32) NOT NULL';
_sql($sql, $errored, $error_ary);
$sql = 'ALTER TABLE ' . $table_prefix . 'shop_transactions MODIFY COLUMN `trans_type` VARCHAR(255) NOT NULL';
_sql($sql, $errored, $error_ary);


//
// Create new Fully Modded core-schema
//
$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'bookie_admin_bets';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'bookie_admin_bets (`bet_id` INT(11) NOT NULL auto_increment, `bet_cat` INT(3) DEFAULT "1" NOT NULL, `bet_time` INT(11) DEFAULT "0" NOT NULL, `bet_selection` VARCHAR(100) DEFAULT "" NOT NULL, `bet_meeting` VARCHAR(50) DEFAULT "" NOT NULL, `odds_1` INT(11) DEFAULT "0" NOT NULL, `odds_2` INT(11) DEFAULT "0" NOT NULL, `checked` INT(2) DEFAULT "0" NOT NULL, `multi` INT(11) DEFAULT "-1" NOT NULL, `starbet` INT(2) DEFAULT "0" NOT NULL, `each_way` INT(2) DEFAULT "0" NOT NULL, KEY bet_id (bet_id)) TYPE=MYISAM';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'bookie_bet_setter';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'bookie_bet_setter (`setter` INT(11) NOT NULL DEFAULT "0", `bet_id` INT(11) DEFAULT "0" NOT NULL, `commission` INT(11) DEFAULT "0" NOT NULL, `paid` INT(2) DEFAULT "0" NOT NULL) TYPE=MYISAM';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'bookie_categories';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'bookie_categories (`cat_id` INT(11) NOT NULL auto_increment, `cat_name` VARCHAR(30) DEFAULT "" NOT NULL, PRIMARY KEY (cat_id)) TYPE=MYISAM';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'bookie_meetings';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'bookie_meetings (`meeting_id` INT(5) NOT NULL auto_increment, `meeting` VARCHAR(50) DEFAULT "" NOT NULL, PRIMARY KEY (meeting_id)) TYPE=MYISAM';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'bookie_selections';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'bookie_selections (`selection_id` INT(11) NOT NULL DEFAULT "0", `selection_name` VARCHAR(100) DEFAULT "" NOT NULL, PRIMARY KEY (selection_id)) TYPE=MYISAM';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'bookie_selections_data';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'bookie_selections_data (`selection_id` INT(11) NOT NULL DEFAULT "0", `selection` VARCHAR(100) DEFAULT "" NOT NULL) TYPE=MYISAM';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'ina_chat';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'ina_chat (`chat_date` DATE NOT NULL DEFAULT "0000-00-00", `chat_text` TEXT NOT NULL)';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'ina_hall_of_fame';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'ina_hall_of_fame (`game_id` MEDIUMINT(8) NOT NULL DEFAULT "0", `current_user_id` MEDIUMINT(8) NOT NULL DEFAULT "0", `current_score` FLOAT(10,2) NOT NULL DEFAULT "0.00", `date_today` INT(10) NOT NULL DEFAULT "0", `old_user_id` MEDIUMINT(8) NOT NULL DEFAULT "0", `old_score` FLOAT(10,2) NOT NULL DEFAULT "0.00", `old_date` INT(10) NOT NULL DEFAULT "0")';		
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'ina_sessions';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'ina_sessions (`playing_time` INT(15) NOT NULL DEFAULT "0", `playing_id` INT(10) NOT NULL DEFAULT "0", `playing` INT(11) NOT NULL DEFAULT "0")';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'lexicon'; 
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'lexicon (`id` BIGINT(8) NOT NULL auto_increment, `keyword` VARCHAR(250) NOT NULL DEFAULT "", `explanation` LONGTEXT NOT NULL, `bbcode_uid` VARCHAR(10) DEFAULT NULL, `cat` INT(8) NOT NULL DEFAULT "1", PRIMARY KEY (id), KEY (cat)) TYPE=MYISAM';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'lexicon_cat';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'lexicon_cat (`cat_id` INT(8) NOT NULL auto_increment, `cat_titel` VARCHAR(80) NOT NULL DEFAULT "", PRIMARY KEY (cat_id)) TYPE=MYISAM';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'lexicon_config'; 
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'links_cat';
_sql($sql, $errored, $error_ary);
$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'links_url';
_sql($sql, $errored, $error_ary);
$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'links_urlincat';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'rating_bias';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'rating_bias (`bias_id` INT UNSIGNED NOT NULL auto_increment, `user_id` INT UNSIGNED NOT NULL DEFAULT "0", `target_user` INT UNSIGNED NOT NULL DEFAULT "0", `bias_status` TINYINT UNSIGNED NOT NULL DEFAULT "0", `bias_time` INT UNSIGNED NOT NULL DEFAULT "0", `post_id` INT UNSIGNED NOT NULL DEFAULT "0", `option_id` SMALLINT UNSIGNED NOT NULL DEFAULT "0", PRIMARY KEY (`bias_id`), KEY `ratingbias_userid_targetuser` (user_id, target_user),  KEY `ratingbias_targetuser` (target_user), KEY `ratingbias_postid` (post_id), KEY `ratingbias_optionid` (option_id))';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'rating_temp';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'rating_temp (`topic_id` INT UNSIGNED NOT NULL, `points` TINYINT NOT NULL, KEY `ratingtemp_topicid` (topic_id))';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'thanks';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'thanks(`topic_id` MEDIUMINT(8) NOT NULL, `user_id` MEDIUMINT(8) NOT NULL, `thanks_time` INT(11) NOT NULL) TYPE=MYISAM';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'thread_kicker';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'thread_kicker(`kick_id` INT(11) NOT NULL auto_increment, `user_id` INT(11) NOT NULL DEFAULT "0", `topic_id` INT(11) NOT NULL DEFAULT "0", `kicker` INT(11) NOT NULL DEFAULT "0", `post_id` INT(11) NOT NULL DEFAULT "0", `kick_time` INT(11) NOT NULL DEFAULT "0", `kicker_status` INT(2) NOT NULL DEFAULT "0", `kicker_username` VARCHAR(25) NOT NULL DEFAULT "", `kicked_username` VARCHAR(25) NOT NULL DEFAULT "", `topic_title` VARCHAR(120) NOT NULL DEFAULT "", PRIMARY KEY (`kick_id`)) TYPE=MYISAM';
_sql($sql, $errored, $error_ary);

$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'user_shops';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'user_shops (`id` INT(5) NOT NULL auto_increment, `user_id` INT(10) NOT NULL, `username` VARCHAR(32) NOT NULL, `shop_name` VARCHAR(32) NOT NULL, `shop_type` VARCHAR(32) NOT NULL, `shop_opened` INT(30) NOT NULL, `shop_updated` INT(30) NOT NULL, `shop_status` INT(1) DEFAULT "0" NOT NULL, `amount_earnt` INT(10) DEFAULT "0" NOT NULL, `amount_holding` INT(10) DEFAULT "0" NOT NULL, `items_sold` INT(10) DEFAULT "0" NOT NULL, `items_holding` INT(10) DEFAULT "0" NOT NULL, PRIMARY KEY (`user_id`), INDEX (`id`)) TYPE=MYISAM';
_sql($sql, $errored, $error_ary);
  			
$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'user_shops_items';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'user_shops_items (`id` INT(10) NOT NULL auto_increment, `shop_id` INT(10) NOT NULL, `item_id` INT(10) NOT NULL, `seller_notes` VARCHAR(255) NOT NULL, `cost` INT(10) NOT NULL, `time_added` MEDIUMINT(30) NOT NULL, INDEX (`shop_id`), PRIMARY KEY (`id`)) TYPE=MYISAM';		
_sql($sql, $errored, $error_ary);



//
// Modify phpBB core-data
// phpbb_config data
$rconfig_data = array( 'disable_reg', 'fm_authors', 'fm_support', 'fm_type', 'ina_trophy_king_old', 'use_points_system', 'use_point_system' );
for ( $i = 0; $i < sizeof($rconfig_data); $i++ )
{
	$sql = "DELETE FROM " . $table_prefix . "config WHERE `config_name` = '" . $rconfig_data[$i] . "'";
	_sql($sql, $errored, $error_ary);
}

$config_data = array( 'allow_custom_post_color' => 250, 'allow_invisible_link' => 0, 'allow_karma' => 0, 'bin_forum' => 0, 'bookie_edit_stake' => 1, 'bookie_leader' => 10, 'bookie_pm' => 1, 'bookie_eachway' => 0, 'bookie_user_bets' => 0, 'bookie_frac_or_dec' => 0, 'bookie_welcome' => 'Welcome to the Bookmakers', 'bookie_commission' => 3, 'bookie_allow_commission' => 0, 'bookie_default_date' => '', 'bookie_min_bet' => '', 'bookie_max_bet' => '', 'bookie_def_cat' => 1, 'bookie_restrict' => 0, 'bookie_version' => '2.0.8', 'default_avatar_guests_url' => 'images/avatars/none.gif', 'default_avatar_set' => 3, 'default_avatar_users_url' => 'images/avatars/none.gif', 'display_last_edited' => 1, 'dbmtnc_rebuild_end' => 0, 'dbmtnc_rebuild_pos' => -1, 'dbmtnc_rebuildcfg_maxmemory' => 500, 'dbmtnc_rebuildcfg_minposts' => 3, 'dbmtnc_rebuildcfg_php3only' => 0, 'dbmtnc_rebuildcfg_php3pps' => 1, 'dbmtnc_rebuildcfg_php4pps' => 8, 'dbmtnc_rebuildcfg_timelimit' => 240, 'dbmtnc_rebuildcfg_timeoverwrite' => 0, 'dbmtnc_disallow_postcounter' => 0, 'dbmtnc_disallow_rebuild' => 0, 'enable_module_avdelete' => 0, 'enable_module_backup' => 0, 'enable_module_disallow' => 0, 'enable_module_mass_email' => 0, 'enable_module_ranks' => 0, 'enable_module_smilies' => 0, 'enable_module_user_ban' => 0, 'enable_module_users' => 0, 'enable_module_words' => 0, 'kicker_setting' => 0, 'kicker_view_setting' => 0, 'lexicon_title' => 'Lexicon v2', 'lexicon_description' => 'This MOD based on a script by eXperienZ.NET', 'myInfo_version' => '1.0.0', 'myInfo_enable' => 0, 'myInfo_name' => 'myInfo', 'myInfo_instructions' => 'This is the myInfo text box.', 'newsfeed_rss' => 'http://p.moreover.com/cgi-local/page?c=Webmaster%20tips&o=xml', 'newsfeed_cache' => 'cache/', 'newsfeed_cachetime' => 10, 'newsfeed_amt' => 11, 'newsfeed_field_article' => 'ARTICLE', 'newsfeed_field_url' => 'URL', 'newsfeed_field_text' => 'HEADLINE_TEXT', 'newsfeed_field_source' => 'SOURCE', 'newsfeed_field_time' => 'HARVEST_TIME', 'points_banner' => 0, 'points_default' => 0, 'shop_trans_enable' => 0, 'topic_redirect' => 1, 'u_shops_enabled' => 0, 'u_shops_open_cost' => 0, 'u_shops_tax_percent' => 1, 'u_shops_max_items' => 100, 'use_point_system' => 1, 'whosonline_time' => 5 );
while ( list ( $config_name, $config_value ) = each ( $config_data ) )
{
	$sql = "INSERT INTO " . $table_prefix . "config (`config_name`, `config_value`) VALUES ('" . $config_name . "', '" . $config_value . "')";
	_sql($sql, $errored, $error_ary);
}

// phpbb_forums data
$sql = 'INSERT IGNORE INTO ' . $table_prefix . 'forums (`forum_id`, `cat_id`, `forum_name`, `forum_desc`, `forum_status`, `auth_view`, `auth_read`) VALUES ("-51", "0", "* LEXICON PERMISSIONS", "Lexicon Control", 1, 5, 5)';
_sql($sql, $errored, $error_ary);
	

//
// Modify Fully Modded core-data
// phpbb_album_config data
$sql = 'INSERT INTO ' . $table_prefix . 'album_config (`config_name`, `config_value`) VALUES ("posts", "0")';
_sql($sql, $errored, $error_ary);

// phpbb_config_nav data
$sql = 'DELETE FROM ' . $table_prefix . 'config_nav WHERE `url` = "bank.php"';
_sql($sql, $errored, $error_ary);

$sql = 'DELETE FROM ' . $table_prefix . 'config_nav WHERE `url` = "lottery.php"';
_sql($sql, $errored, $error_ary);
			
$sql = 'DELETE FROM ' . $table_prefix . 'config_nav WHERE `url` = "link.php"';
_sql($sql, $errored, $error_ary);

$sql = "INSERT INTO " . $table_prefix . "config_nav (`img`, `alt`, `use_lang`, `url`, `nav_order`, `value`) VALUES ('icon_mini_links.gif', 'Links', 1, 'links.php', 310, 0)";
_sql($sql, $errored, $error_ary);
					
// phpbb_forums_config data
$forum_config_data = array( 'forum_module_dloads' => 0, 'forum_module_karma' => 0, 'forum_module_newusers' => 0, 'forum_module_points' => 0, 'forum_module_randomuser' => 0, 'forum_module_topposters' => 0 );
while ( list ( $config_name, $config_value ) = each ( $forum_config_data ) )
{
	$sql = "INSERT INTO " . $table_prefix . "forums_config (`config_name`, `config_value`) VALUES ('" . $config_name . "', '" . $config_value . "')";
	_sql($sql, $errored, $error_ary);
}		

// phpbb_helpdesk_reply data
$id = 8;
$helpdesk_reply_data = array( 'Skype Me', 'Xfire Messenger' );
for ( $i = 0; $i < sizeof($helpdesk_reply_data); $i++ )
{
	$sql = "INSERT INTO " . $table_prefix . "helpdesk_reply (`value`, `data`) VALUES ('" . $id . "', '" . $helpdesk_reply_data[$i] . "')";
	_sql($sql, $errored, $error_ary);
	$id++;
}		

// phpbb_ina_data data
$sql = 'INSERT INTO ' . $table_prefix . 'ina_data (`version`) VALUES ("v2.0.0")';
_sql($sql, $errored, $error_ary);

// phpbb_kb_config data
$sql = 'DELETE FROM ' . $table_prefix . 'kb_config WHERE `config_name` = "pt_header"';
_sql($sql, $errored, $error_ary);

// phpbb_lexicon data
$lexicon_data = array( 
	array( Keyword => '1st example', Explanation => 'This is a test entry to example the character-navigation.\r\nBy default the numbers do not shown. They will only display if an entry exists.', Bbcode_uid => '5d424e3a4a', Cat => 1 ),
	array( Keyword => 'Testentry', Explanation => 'This is just another example of a lexicon entry.', Bbcode_uid => '', Cat => 1 )
);
for ( $row = 0; $row < sizeof($lexicon_data); $row++ )
{
	for ( $column = 0; $column < 1; $column++ )
	{
		$sql = "INSERT INTO " . $table_prefix . "lexicon (`keyword`, `explanation`, `bbcode_uid`, `cat`) VALUES ('" . $lexicon_data[$row]['Keyword'] . "', '" . $lexicon_data[$row]['Explanation'] . "', '" . $lexicon_data[$row]['Bbcode_uid'] . "', '" . $lexicon_data[$row]['Cat'] . "')";
		_sql($sql, $errored, $error_ary);
	}
}

// phpbb_lexicon_cat data
$sql = 'INSERT INTO ' . $table_prefix . 'lexicon_cat (`cat_titel`) VALUES ("default")';
_sql($sql, $errored, $error_ary);

// phpbb_link_config data
$link_config_data = array( 'display_links_logo' => '1', 'email_notify' => '1', 'pm_notify' => '0', 'lock_submit_site' => '0', 'allow_no_logo' => '0' );
while ( list ( $config_name, $config_value ) = each ( $link_config_data ) )
{
	$sql = "INSERT INTO " . $table_prefix . "link_config (`config_name`, `config_value`) VALUES ('" . $config_name . "', '" . $config_value . "')";
	_sql($sql, $errored, $error_ary);
}

// phpbb_rating_config data
$rating_config_data = array(
	array( Label => 'Bias system active', Num => 1, ID => 11, Type => 3, Order => 1100 ),
	array( Label => 'Show bias usernames?', Num => 1, ID => 17, Type => 3, Order => 1150 ),
	array( Label => 'Show dropdown in viewtopic?', Num => 0, ID => 18, Type => 3, Order => 1800 ),
	array( Label => 'Show dropdown in viewforum?', Num => 0, ID => 19, Type => 3, Order => 1900 )
);
for ( $row = 0; $row < sizeof($rating_config_data); $row++ )
{
	for ( $column = 0; $column < 1; $column++ )
	{
		$sql = "INSERT INTO " . $table_prefix . "rating_config (`label`, `num_value`, `config_id`, `input_type`, `list_order`) VALUES ('" . $rating_config_data[$row]['Label'] . "', '" . $rating_config_data[$row]['Num'] . "', '" . $rating_config_data[$row]['ID'] . "', '" . $rating_config_data[$row]['Type'] . "', '" . $rating_config_data[$row]['Order'] . "')";
		_sql($sql, $errored, $error_ary);
	}
}
	

//
// Change default values to sync with FullyModded setup
//
$sql = 'UPDATE ' . $table_prefix . 'config SET `config_value` = "1.3.8" WHERE `config_name` = "toplist_version"';
_sql($sql, $errored, $error_ary);

$sql = 'UPDATE ' . $table_prefix . 'config SET `config_value` = "0" WHERE `config_name` = "challenges_sent"';
_sql($sql, $errored, $error_ary);

$sql = 'UPDATE ' . $table_prefix . 'config SET `config_value` = "#0099CC" WHERE `config_name` = "ina_online_list_color"';
_sql($sql, $errored, $error_ary);

$sql = 'UPDATE ' . $table_prefix . 'config SET `config_value` = "Game Players" WHERE `config_name` = "ina_online_list_text"';	
_sql($sql, $errored, $error_ary);

$sql = 'UPDATE ' . $table_prefix . 'config SET `config_value` = "1440" WHERE `config_name` = "uniquehits_time"';
_sql($sql, $errored, $error_ary);

$sql = 'UPDATE ' . $table_prefix . 'config SET `config_value` = "uploads/user_photos" WHERE `config_name` = "photo_path"';
_sql($sql, $errored, $error_ary);

$sql = 'UPDATE ' . $table_prefix . 'config SET `config_value` = "uploads/user_avatars" WHERE `config_name` = "avatar_path"';
_sql($sql, $errored, $error_ary);

$sql = 'UPDATE ' . $table_prefix . 'config SET `config_value` = "images/avatars" WHERE `config_name` = "avatar_gallery_path"';
_sql($sql, $errored, $error_ary);
	
$sql = 'UPDATE ' . $table_prefix . 'themes SET `head_stylesheet` = "subSilver.css" WHERE `template_name` = "subSilver"';
_sql($sql, $errored, $error_ary);

$sql = 'UPDATE ' . $table_prefix . 'attachments_config SET `config_value` = "uploads/attachments" WHERE `config_name` = "upload_dir"';
_sql($sql, $errored, $error_ary);
			
$sql = 'UPDATE ' . $table_prefix . 'im_config SET `config_value` = "0.7.1" WHERE `config_name` = "im_version"';
_sql($sql, $errored, $error_ary);

$sql = 'UPDATE ' . $table_prefix . 'ina_games SET `game_parent` = "1"';
_sql($sql, $errored, $error_ary);

$sql = 'UPDATE ' . $table_prefix . 'pa_config SET `config_value` = "uploads/pafiledb/" WHERE `config_name` = "upload_dir"';
_sql($sql, $errored, $error_ary);

$sql = 'UPDATE ' . $table_prefix . 'pa_config SET `config_value` = "uploads/pafiledb/screenshots/" WHERE `config_name` = "screenshots_dir"';
_sql($sql, $errored, $error_ary);
			
$sql = 'UPDATE ' . $table_prefix . 'pa_files SET `file_dir` = "uploads/pafiledb/" WHERE `file_dir` = "pafiledb/uploads/"';
_sql($sql, $errored, $error_ary);

?>