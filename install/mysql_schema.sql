#
# phpBB2 - MySQL schema
#
# $Id: mysql_schema.sql,v 1.35.2.7 2003/06/10 12:42:31 psotfx Exp $
#

# --------------------------------------------------------
#
# Table structure for table 'phpbb_account_hist'
#
CREATE TABLE phpbb_account_hist (
  user_id mediumint(8) default '0',
  lw_post_id mediumint(8) default '0',
  lw_money float default '0',
  lw_plus_minus smallint(5) default '0',
  MNY_CURRENCY varchar(8) default '',
  lw_date int(11) default '0',
  `comment` varchar(255) default '',
  status varchar(64) default '',
  txn_id varchar(64) default '',
  lw_site varchar(10) default ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_advance_html'
#
CREATE TABLE phpbb_advance_html (
   config_name varchar(255) NOT NULL default '', 
   config_value longtext NOT NULL default '',
   PRIMARY KEY (config_name)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_album'
#
CREATE TABLE phpbb_album (
   pic_id int(11) UNSIGNED NOT NULL auto_increment, 
   pic_filename varchar(255) NOT NULL default '', 
   pic_thumbnail varchar(255), 
   pic_title varchar(255) NOT NULL default '', 
   pic_user_id mediumint(8) NOT NULL, 
   pic_user_ip char(8) DEFAULT '0' NOT NULL, 
   pic_time int(11) UNSIGNED NOT NULL, 
   pic_cat_id mediumint(8) UNSIGNED DEFAULT '1' NOT NULL, 
   pic_view_count int(11) UNSIGNED DEFAULT '0' NOT NULL, 
   pic_lock tinyint(3) DEFAULT '0' NOT NULL, 
   pic_username varchar(32), 
   pic_approval tinyint(3) DEFAULT '1' NOT NULL,
   pic_desc TEXT, 
   PRIMARY KEY (pic_id), 
   KEY pic_cat_id(pic_cat_id), 
   KEY pic_user_id(pic_user_id), 
   KEY pic_time(pic_time)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_album_cat'
#
CREATE TABLE phpbb_album_cat (
   cat_id mediumint(8) UNSIGNED NOT NULL auto_increment, 
   cat_title varchar(255) NOT NULL default '', 
   cat_desc TEXT, 
   cat_order mediumint(8) NOT NULL, 
   cat_view_level tinyint(3) DEFAULT '-1' NOT NULL, 
   cat_upload_level tinyint(3) DEFAULT '0' NOT NULL, 
   cat_rate_level tinyint(3) DEFAULT '0' NOT NULL, 
   cat_comment_level tinyint(3) DEFAULT '0' NOT NULL, 
   cat_edit_level tinyint(3) DEFAULT '0' NOT NULL, 
   cat_delete_level tinyint(3) DEFAULT '2' NOT NULL, 
   cat_moderator_groups varchar(255), 
   cat_approval tinyint(3) DEFAULT '0' NOT NULL,
   cat_view_groups varchar(255),
   cat_upload_groups varchar(255),
   cat_rate_groups varchar(255),
   cat_comment_groups varchar(255),
   cat_edit_groups varchar(255),
   cat_delete_groups varchar(255),
   PRIMARY KEY (cat_id), 
   KEY cat_order(cat_order)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_album_comment'
#
CREATE TABLE phpbb_album_comment (
   comment_id int(11) UNSIGNED NOT NULL auto_increment, 
   comment_pic_id int(11) UNSIGNED NOT NULL, 
   comment_cat_id int(11) NOT NULL,
   comment_user_id mediumint(8) NOT NULL, 
   comment_user_ip char(8) NOT NULL, 
   comment_time int(11) UNSIGNED NOT NULL, 
   comment_text TEXT, 
   comment_edit_time int(11) UNSIGNED, 
   comment_edit_count smallint(5) UNSIGNED DEFAULT '0' NOT NULL, 
   comment_edit_user_id mediumint(8), 
   comment_username varchar(32),
   PRIMARY KEY (comment_id), 
   KEY comment_pic_id(comment_pic_id), 
   KEY comment_user_id(comment_user_id), 
   KEY comment_user_ip(comment_user_ip), 
   KEY comment_time(comment_time)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_album_config'
#
CREATE TABLE phpbb_album_config (
   config_name varchar(255) NOT NULL default '', 
   config_value varchar(255) NOT NULL default '', 
   PRIMARY KEY (config_name)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_album_rate'
#
CREATE TABLE phpbb_album_rate (
   rate_pic_id int(11) UNSIGNED NOT NULL, 
   rate_user_id mediumint(8) NOT NULL, 
   rate_user_ip char(8) NOT NULL, 
   rate_point tinyint(3) UNSIGNED NOT NULL, 
   rate_hon_point tinyint(3) DEFAULT '0' NOT NULL,
   KEY rate_pic_id (rate_pic_id), 
   KEY rate_user_ip(rate_user_ip), 
   KEY rate_point(rate_point)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_attach_quota'
#
CREATE TABLE phpbb_attach_quota (
   user_id mediumint(8) UNSIGNED NOT NULL default '0',
   group_id mediumint(8) UNSIGNED NOT NULL default '0',
   quota_type smallint(2) NOT NULL default '0',
   quota_limit_id mediumint(8) UNSIGNED NOT NULL default '0',
   KEY quota_type (quota_type)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_attachments'
#
CREATE TABLE phpbb_attachments (
   attach_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL, 
   post_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL, 
   privmsgs_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
   user_id_1 mediumint(8) NOT NULL,
   user_id_2 mediumint(8) NOT NULL,
   KEY attach_id_post_id (attach_id, post_id),
   KEY attach_id_privmsgs_id (attach_id, privmsgs_id),
   KEY post_id (post_id),
   KEY privmsgs_id (privmsgs_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_attachments_config'
#
CREATE TABLE phpbb_attachments_config (
   config_name varchar(255) NOT NULL default '',
   config_value varchar(255) NOT NULL default '',
   PRIMARY KEY (config_name)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_attachments_desc'
#
CREATE TABLE phpbb_attachments_desc (
   attach_id mediumint(8) UNSIGNED NOT NULL auto_increment,
   physical_filename varchar(255) NOT NULL default '',
   real_filename varchar(255) NOT NULL default '',
   download_count mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
   comment varchar(255),
   extension varchar(100),
   mimetype varchar(100),
   filesize int(20) NOT NULL,
   filetime int(11) DEFAULT '0' NOT NULL,
   thumbnail tinyint(1) DEFAULT '0' NOT NULL,
   width smallint(5) UNSIGNED DEFAULT '0' NOT NULL,
   height smallint(5) UNSIGNED DEFAULT '0' NOT NULL,
   border tinyint(1) DEFAULT '0' NOT NULL,
   PRIMARY KEY (attach_id),
   KEY filetime (filetime),
   KEY physical_filename (physical_filename(10)),
   KEY filesize (filesize),
   INDEX (filetime),
   INDEX (physical_filename(10)),
   INDEX (filesize)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_auth_access'
#
CREATE TABLE phpbb_auth_access (
   group_id mediumint(8) DEFAULT '0' NOT NULL,
   forum_id smallint(5) DEFAULT '0' NOT NULL,
   auth_view tinyint(1) DEFAULT '0' NOT NULL,
   auth_read tinyint(1) DEFAULT '0' NOT NULL,
   auth_post tinyint(1) DEFAULT '0' NOT NULL,
   auth_reply tinyint(1) DEFAULT '0' NOT NULL,
   auth_edit tinyint(1) DEFAULT '0' NOT NULL,
   auth_delete tinyint(1) DEFAULT '0' NOT NULL,
   auth_sticky tinyint(1) DEFAULT '0' NOT NULL,
   auth_announce tinyint(1) DEFAULT '0' NOT NULL,
   auth_globalannounce tinyint(1) DEFAULT '0' NOT NULL, 
   auth_vote tinyint(1) DEFAULT '0' NOT NULL,
   auth_pollcreate tinyint(1) DEFAULT '0' NOT NULL,
   auth_attachments tinyint(1) DEFAULT '0' NOT NULL,
   auth_mod tinyint(1) DEFAULT '0' NOT NULL,
   auth_download tinyint(1) DEFAULT '0' NOT NULL,
   auth_suggest_event tinyint(2) DEFAULT '0' NOT NULL,
   auth_ban tinyint(1) DEFAULT '0' NOT NULL,
   auth_voteban tinyint(1) DEFAULT '0' NOT NULL,
   auth_greencard tinyint(1) DEFAULT '0' NOT NULL,
   auth_bluecard tinyint(1) DEFAULT '0' NOT NULL,
   KEY group_id (group_id),
   KEY forum_id (forum_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_avatartoplist'
#
CREATE TABLE phpbb_avatartoplist (
  avatar_filename TEXT NOT NULL, 
  avatar_type tinyint(4) NOT NULL default '0', 
  voter_id mediumint(8) NOT NULL, 
  voting mediumint(8) NOT NULL, 
  comment text NOT NULL, 
  INDEX voter_id (voter_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_backup'
#
CREATE TABLE phpbb_backup (
  backup_skill int(1) NOT NULL,
  email_true varchar(255) NOT NULL DEFAULT '',
  email text NOT NULL,
  ftp_true int(1) NOT NULL,
  ftp_server text NOT NULL,
  ftp_user_name text NOT NULL,
  ftp_user_pass text NOT NULL,
  ftp_directory text NOT NULL,
  write_backups_true int(1) NOT NULL,
  files_to_keep varchar(255) NOT NULL,
  cron_time text NOT NULL,
  delay_time text NOT NULL,
  backup_type text NOT NULL,
  phpbb_only int(1) NOT NULL,
  no_search int(1) NOT NULL,
  ignore_tables text NOT NULL,	
  last_run int(11) NOT NULL,
  finished int(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_bank'
#
CREATE TABLE phpbb_bank (
   user_id mediumint(8) NOT NULL,
   holding bigint(20) UNSIGNED DEFAULT '0' NOT NULL, 
   totalwithdrew bigint(20) UNSIGNED DEFAULT '0' NOT NULL, 
   totaldeposit bigint(20) UNSIGNED DEFAULT '0' NOT NULL, 
   opentime int(11) DEFAULT '0' NOT NULL, 
   fees tinyint(1) DEFAULT '1' NOT NULL, 
   PRIMARY KEY (user_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_banlist'
#
CREATE TABLE phpbb_banlist (
   ban_id mediumint(8) UNSIGNED NOT NULL auto_increment,
   ban_userid mediumint(8) NOT NULL,
   user_name varchar(50) NOT NULL default '', 
   reason varchar(75) NOT NULL default '', 
   baned_by varchar(50) NOT NULL default '',
   ban_ip char(8) NOT NULL,
   ban_email varchar(255) NOT NULL DEFAULT '',
   ban_time int(11) default NULL,
   ban_expire_time int(11) default NULL,
   ban_by_userid mediumint(8) default NULL,
   ban_priv_reason text,
   ban_pub_reason_mode tinyint(1) default NULL,
   ban_pub_reason text,
   PRIMARY KEY (ban_id),
   KEY ban_ip_user_id (ban_ip, ban_userid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_banned_sites'
#
CREATE TABLE phpbb_banned_sites (
   site_id INT(15) NOT NULL AUTO_INCREMENT, 
   site_url VARCHAR(150) NOT NULL, 
   reason VARCHAR(15) NOT NULL, 
   INDEX (site_id) 
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_banned_visitors'
#
CREATE TABLE phpbb_banned_visitors (
   count INT(15) NOT NULL AUTO_INCREMENT, 
   refer VARCHAR(150) NOT NULL, 
   ip VARCHAR(255) NOT NULL, 
   ip_owner VARCHAR(100) NOT NULL, 
   browser VARCHAR(150) NOT NULL, 
   user_id MEDIUMINT(8) NOT NULL, 
   user VARCHAR(50) NOT NULL, 
   INDEX (count) 
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_banner'
#
CREATE TABLE phpbb_banner (
    banner_id mediumint(8) UNSIGNED NOT NULL, 
    banner_name text NOT NULL, 
    banner_spot smallint(1) UNSIGNED NOT NULL, 
    banner_forum mediumint(8) UNSIGNED NOT NULL, 
    banner_description varchar(255) NOT NULL default '', 
    banner_url varchar(255) NOT NULL default '', 
    banner_owner mediumint(8) NOT NULL, 
    banner_click mediumint(8) UNSIGNED NOT NULL, 
    banner_view mediumint(8) UNSIGNED NOT NULL, 
    banner_weigth tinyint(1) UNSIGNED DEFAULT '50' NOT NULL, 
    banner_active tinyint(1) NOT NULL, 
    banner_timetype tinyint(1) NOT NULL, 
    time_begin int(11) NOT NULL, 
    time_end int(11) NOT NULL, 
    date_begin int(11) NOT NULL, 
    date_end int(11) NOT NULL, 
    banner_level tinyint(1) NOT NULL,
    banner_level_type tinyint(1) NOT NULL,
    banner_comment varchar(100) NOT NULL default '', 
    banner_type mediumint(5) NOT NULL, 
    banner_width varchar(5) NOT NULL DEFAULT '0',
    banner_height varchar(5) NOT NULL DEFAULT '0',
    banner_filter tinyint(1) NOT NULL,
    banner_filter_time mediumint(5) DEFAULT '600' NOT NULL,
    PRIMARY KEY (banner_id),
    KEY banner_active (banner_active),
    KEY banner_level (banner_level),
    KEY banner_timetype (banner_timetype)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_banner_stats'
#
CREATE TABLE phpbb_banner_stats (
   banner_id mediumint(8) UNSIGNED NOT NULL,
   click_date int(11) NOT NULL,
   click_ip char(8) NOT NULL,
   click_user mediumint(8) NOT NULL,
   user_duration int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_banvote_voters'
#
CREATE TABLE phpbb_banvote_voters (
   banvote_user_id mediumint(8) DEFAULT '0' NOT NULL, 
   banvote_banner_id mediumint(8) DEFAULT '0' NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_bookie_admin_bets'
#
CREATE TABLE phpbb_bookie_admin_bets (
   bet_id INT(11) NOT NULL auto_increment, 
   bet_cat INT(3) DEFAULT '1' NOT NULL,
   bet_time INT(11) DEFAULT '0' NOT NULL, 
   bet_selection VARCHAR(100) DEFAULT '' NOT NULL, 
   bet_meeting VARCHAR(50) DEFAULT '' NOT NULL, 
   odds_1 INT(11) DEFAULT '0' NOT NULL, 
   odds_2 INT(11) DEFAULT '0' NOT NULL, 
   checked INT(2) DEFAULT '0' NOT NULL, 
   multi INT(11) DEFAULT '-1' NOT NULL, 
   starbet INT(2) DEFAULT '0' NOT NULL,
   each_way INT(2) DEFAULT '0' NOT NULL,
   KEY bet_id (bet_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_bookie_bet_setter'
#
CREATE TABLE phpbb_bookie_bet_setter (
  setter int(11) NOT NULL default '0',
  bet_id int(11) NOT NULL default '0',
  commission int(11) NOT NULL default '0',
  paid int(2) NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_bookie_bets'
#
CREATE TABLE phpbb_bookie_bets (
   bet_id int(5) NOT NULL auto_increment, 
   bet_cat int(3) NOT NULL default '1',
   user_id int(11) NOT NULL default '0', 
   time int(11) NOT NULL default '0', 
   meeting varchar(50) NOT NULL default '', 
   selection varchar(100) NOT NULL default '', 
   bet int(11) NOT NULL default '0', 
   win_lose int(11) NOT NULL default '0', 
   odds_1 int(8) NOT NULL default '0', 
   odds_2 int(8) NOT NULL default '0', 
   checked int(2) NOT NULL default '0', 
   edit_time int(11) NOT NULL default '0', 
   orig_time int(11) NOT NULL default '0', 
   admin_betid INT(11) DEFAULT '0' NOT NULL,
   each_way int(2) NOT NULL default '0',
   bet_result int(2) NOT NULL default '0',
   PRIMARY KEY (bet_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_bookie_categories'
#
CREATE TABLE phpbb_bookie_categories (
   cat_id int(11) NOT NULL auto_increment,
   cat_name varchar(30) NOT NULL default '',
   PRIMARY KEY (cat_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_bookie_meetings'
#
CREATE TABLE phpbb_bookie_meetings (
   meeting_id int(5) NOT NULL auto_increment,
   meeting varchar(50) NOT NULL default '',
   PRIMARY KEY (meeting_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_bookie_selections'
#
CREATE TABLE phpbb_bookie_selections (
   selection_id int(11) NOT NULL auto_increment,
   selection_name varchar(25) NOT NULL default '',
   PRIMARY KEY (selection_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_bookie_selections_data'
#
CREATE TABLE phpbb_bookie_selections_data (
   selection_id int(11) NOT NULL default '0',
   selection varchar(100) NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_bookie_stats'
#
CREATE TABLE phpbb_bookie_stats (
   user_id int(11) NOT NULL default '0', 
   total_win int(11) NOT NULL default '0', 
   total_lose int(11) NOT NULL default '0', 
   netpos int(11) NOT NULL default '0', 
   bets_placed int(6) NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_bots'
#
CREATE TABLE phpbb_bots (
   bot_id mediumint(8) UNSIGNED NOT NULL auto_increment,
   bot_name varchar(255) DEFAULT '' NOT NULL,
   bot_agent varchar(255) DEFAULT '' NOT NULL,
   last_visit varchar(255) NOT NULL,
   bot_visits mediumint(8) NOT NULL,
   bot_pages mediumint(8) NOT NULL,
   pending_agent text NOT NULL,
   pending_ip text NOT NULL,
   bot_ip text NOT NULL,
   bot_style mediumint(8) NOT NULL,	
   PRIMARY KEY (bot_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_bots_archive'
#
CREATE TABLE phpbb_bots_archive (
   bot_id mediumint(8) NOT NULL auto_increment,
   bot_name VARCHAR(255),
   bot_time int(11) NOT NULL default '0',
   bot_url varchar(255) NOT NULL default '',
   PRIMARY KEY (bot_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_cat_rel_cat_parents'
#
CREATE TABLE phpbb_cat_rel_cat_parents (
   cat_id mediumint(8) UNSIGNED NOT NULL DEFAULT '0', 
   parent_cat_id mediumint(8) UNSIGNED NOT NULL DEFAULT '0', 
   PRIMARY KEY (cat_id,parent_cat_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_cat_rel_forum_parents'
#
CREATE TABLE phpbb_cat_rel_forum_parents (
   cat_id mediumint(8) UNSIGNED NOT NULL DEFAULT '0', 
   parent_forum_id smallint(5) UNSIGNED NOT NULL DEFAULT '0', 
   PRIMARY KEY (cat_id, parent_forum_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_categories'
#
CREATE TABLE phpbb_categories (
   cat_id mediumint(8) UNSIGNED NOT NULL auto_increment,
   cat_title varchar(100),
   cat_order mediumint(8) UNSIGNED NOT NULL,
   cat_sponsor_img varchar(255) NOT NULL default '',
   cat_sponsor_alt varchar(255) NOT NULL default '',
   cat_sponsor_url varchar(255) NOT NULL default '',
   parent_forum_id smallint(5) UNSIGNED NOT NULL DEFAULT '0',
   cat_hier_level tinyint UNSIGNED NOT NULL DEFAULT '0',
   cat_icon varchar(255) NOT NULL default '',
   PRIMARY KEY (cat_id),
   KEY cat_order (cat_order)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_charts'
#
CREATE TABLE phpbb_charts (
   chart_id mediumint(8) NOT NULL auto_increment, 
   chart_song_name varchar(100) NOT NULL default '', 
   chart_artist varchar(100) NOT NULL default '', 
   chart_album varchar(100) DEFAULT '',
   chart_label VARCHAR(100) NOT NULL default '',
   chart_catno VARCHAR(50) NOT NULL default '',
   chart_website VARCHAR(100) NOT NULL default '',
   chart_poster_id varchar(100) NOT NULL default '',
   chart_hot mediumint(8) DEFAULT '0', 
   chart_not mediumint(8) DEFAULT '0', 
   chart_curr_pos mediumint(8) DEFAULT '0', 
   chart_last_pos mediumint(8) DEFAULT '0', 
   chart_best_pos mediumint(8) DEFAULT '0', 
   PRIMARY KEY (chart_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_charts_voters'
#
CREATE TABLE phpbb_charts_voters (
   vote_id mediumint(8) NOT NULL auto_increment, 
   vote_user_id mediumint(8) NOT NULL, 
   vote_chart_id mediumint(8) NOT NULL, 
   vote_rate smallint(2) NOT NULL, 
   PRIMARY KEY (vote_id), 
   FOREIGN KEY (vote_chart_id) REFERENCES phpbb_charts(chart_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_chatbox'
#
CREATE TABLE phpbb_chatbox(
   id int(11) NOT NULL auto_increment,
   name varchar(99) NOT NULL default '',
   msg varchar(255) NOT NULL default '',
   timestamp int(10) unsigned NOT NULL,
   PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_chatbox_session'
#
CREATE TABLE phpbb_chatbox_session (
   username varchar(99) NOT NULL default '',
   lastactive int(10) DEFAULT '0' NOT NULL,
   laststatus varchar(8) NOT NULL default '',
   UNIQUE username (username)
) ENGINE=MyISAM DEFAULT CHARSET=utf8; 


# --------------------------------------------------------
#
# Table structure for table 'phpbb_config'
#
CREATE TABLE phpbb_config (
    config_name varchar(255) NOT NULL default '',
    config_value varchar(255) NOT NULL default '',
    PRIMARY KEY (config_name)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_config_nav'
#
CREATE TABLE phpbb_config_nav (
  navlink_id mediumint(8) unsigned NOT NULL auto_increment,
  img varchar(100) NOT NULL default '',
  alt varchar(100) NOT NULL default '',
  use_lang tinyint(1) NOT NULL default '1',
  url varchar(255) NOT NULL default '',
  nav_order mediumint(8) NOT NULL default '1',
  value tinyint(1) NOT NULL default '0',
  PRIMARY KEY (navlink_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_confirm'
#
CREATE TABLE phpbb_confirm (
  confirm_id char(32) DEFAULT '' NOT NULL,
  session_id char(32) DEFAULT '' NOT NULL,
  code char(6) DEFAULT '' NOT NULL, 
  PRIMARY KEY (session_id,confirm_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_digests'
#
CREATE TABLE phpbb_digests (
  digest_id int(6) NOT NULL auto_increment,
  digest_name varchar(25) NULL default '',
  user_id mediumint(8) NOT NULL default '0',
  digest_type tinyint(1) NOT NULL default '0',
  digest_activity tinyint(1) NOT NULL default '1',
  digest_frequency mediumint(8) NOT NULL default '0',
  last_digest int(11) NOT NULL default '0',
  digest_format smallint(4) NOT NULL default '0',
  digest_show_text smallint(4) NOT NULL default '0',
  digest_show_mine smallint(4) NOT NULL default '0',
  digest_new_only smallint(4) NOT NULL default '0',
  digest_send_on_no_messages smallint(4) NOT NULL default '1',
  digest_moderator tinyint(1) NOT NULL default '0',
  digest_include_forum tinyint(1) NOT NULL default '1',
  PRIMARY KEY (digest_id),
  KEY user_id (user_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_digests_forums'
#
CREATE TABLE phpbb_digests_forums (
  user_id mediumint(8) NOT NULL default '0',
  forum_id smallint(5) NOT NULL default '0',
  digest_id int(11) NOT NULL default '0',
  KEY user_id (user_id),
  KEY forum_id (forum_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_digests_log'
#
CREATE TABLE phpbb_digests_log (
  log_time int(11) NOT NULL default '0',
  run_type tinyint(1) NOT NULL default '0',
  user_id mediumint(8) NOT NULL default '0',
  digest_frequency mediumint(8) NOT NULL default '0',
  digest_type tinyint(1) NOT NULL default '0',
  group_id mediumint(8) NOT NULL default '1',
  log_status mediumint(2) NOT NULL default '0',
  log_posts int(11) NULL default '0',
  KEY user_id (user_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_digests_config'
#
CREATE TABLE phpbb_digests_config (
  config_name varchar(255) NOT NULL default '',
  config_value varchar(255) NOT NULL default '0',
  PRIMARY KEY (config_name)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_disallow'
#
CREATE TABLE phpbb_disallow (
   disallow_id mediumint(8) UNSIGNED NOT NULL auto_increment,
   disallow_username varchar(25) DEFAULT '' NOT NULL,
   PRIMARY KEY (disallow_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_extension_groups'
#
CREATE TABLE phpbb_extension_groups (
   group_id mediumint(8) NOT NULL auto_increment,
   group_name char(20) NOT NULL,
   cat_id tinyint(2) DEFAULT '0' NOT NULL, 
   allow_group tinyint(1) DEFAULT '0' NOT NULL,
   download_mode tinyint(1) UNSIGNED DEFAULT '1' NOT NULL,
   upload_icon varchar(100) DEFAULT '',
   max_filesize int(20) DEFAULT '0' NOT NULL,
   forum_permissions varchar(255) DEFAULT '' NOT NULL,
   PRIMARY KEY group_id (group_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_extensions'
#
CREATE TABLE phpbb_extensions (
   ext_id mediumint(8) UNSIGNED NOT NULL auto_increment,
   group_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
   extension varchar(100) NOT NULL default '',
   comment varchar(100),
   PRIMARY KEY ext_id (ext_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_flags'
#
CREATE TABLE phpbb_flags (
   flag_id int(10) NOT NULL auto_increment,
   flag_name varchar(25),
   flag_image varchar(25),
   PRIMARY KEY (flag_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_forbidden_extensions'
#
CREATE TABLE phpbb_forbidden_extensions (
   ext_id mediumint(8) UNSIGNED NOT NULL auto_increment, 
   extension varchar(100) NOT NULL default '', 
   PRIMARY KEY (ext_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_forum_move'
#
CREATE TABLE phpbb_forum_move (
   move_id mediumint(8) NOT NULL auto_increment, 
   forum_id smallint(5) UNSIGNED DEFAULT '0' NOT NULL, 
   forum_dest smallint(5) UNSIGNED DEFAULT '0' NOT NULL, 
   move_days smallint(5) UNSIGNED NOT NULL DEFAULT '0', 
   move_freq smallint(5) UNSIGNED NOT NULL DEFAULT '0', 
   PRIMARY KEY (move_id), 
   KEY (forum_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_forum_prune'
#
CREATE TABLE phpbb_forum_prune (
   prune_id mediumint(8) UNSIGNED NOT NULL auto_increment,
   forum_id smallint(5) UNSIGNED NOT NULL,
   prune_days smallint(5) UNSIGNED NOT NULL,
   prune_freq smallint(5) UNSIGNED NOT NULL,
   PRIMARY KEY(prune_id),
   KEY forum_id (forum_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_forum_tour'
#
CREATE TABLE phpbb_forum_tour (
  page_id mediumint(8) unsigned NOT NULL,
  page_subject varchar(60) NULL,
  page_text text,
  page_sort mediumint(8) NOT NULL,
  bbcode_uid char(10) DEFAULT '' NOT NULL,
  page_access mediumint(8) NOT NULL,
  KEY page_id (page_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_forums'
#
CREATE TABLE phpbb_forums (
   forum_id smallint(5) NOT NULL auto_increment,
   cat_id mediumint(8) DEFAULT '0',
   forum_name varchar(150),
   forum_desc text,
   forum_status tinyint(4) DEFAULT '0' NOT NULL,
   forum_order mediumint(8) UNSIGNED DEFAULT '1' NOT NULL,
   forum_posts mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
   forum_topics mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
   forum_views mediumint(8) UNSIGNED DEFAULT '0' NOT NULL, 
   forum_last_post_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
   forum_hier_level tinyint UNSIGNED DEFAULT '0' NOT NULL, 
   forum_issub tinyint(1) DEFAULT '0' NOT NULL, 
   prune_next int(11),
   prune_enable tinyint(1) DEFAULT '0' NOT NULL,
   move_next int(11) DEFAULT NULL,
   move_enable tinyint(1) DEFAULT '0' NOT NULL,
   points_disabled tinyint(1) NOT NULL default '0',
   auth_view tinyint(2) DEFAULT '0' NOT NULL,
   auth_read tinyint(2) DEFAULT '0' NOT NULL,
   auth_post tinyint(2) DEFAULT '0' NOT NULL,
   auth_reply tinyint(2) DEFAULT '0' NOT NULL,
   auth_edit tinyint(2) DEFAULT '0' NOT NULL,
   auth_delete tinyint(2) DEFAULT '0' NOT NULL,
   auth_sticky tinyint(2) DEFAULT '0' NOT NULL,
   auth_announce tinyint(2) DEFAULT '0' NOT NULL,
   auth_globalannounce tinyint(2) DEFAULT '3' NOT NULL, 
   auth_vote tinyint(2) DEFAULT '0' NOT NULL,
   auth_pollcreate tinyint(2) DEFAULT '0' NOT NULL,
   auth_attachments tinyint(2) DEFAULT '0' NOT NULL,
   auth_download tinyint(2) DEFAULT '0' NOT NULL,
   auth_suggest_event tinyint(2) DEFAULT '0' NOT NULL,
   is_default tinyint(2) DEFAULT '0' NOT NULL,
   events_forum tinyint(1) DEFAULT '0',
   auth_ban tinyint(2) DEFAULT '3',
   auth_voteban tinyint(2) DEFAULT '1' NOT NULL,
   auth_greencard tinyint(2) DEFAULT '5',
   auth_bluecard tinyint(2) DEFAULT '1',
   forum_icon varchar(255) NOT NULL default '',
   icon_enable tinyint(1) DEFAULT '0' NOT NULL,
   answered_enable tinyint(1) DEFAULT '0' NOT NULL,
   forum_external tinyint(1) DEFAULT '0' NOT NULL,
   forum_redirect_url text,
   forum_redirects_user mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
   forum_redirects_guest mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
   forum_ext_newwin tinyint(1) DEFAULT '0' NOT NULL,
   forum_ext_image text,
   image_ever_thumb tinyint(1) DEFAULT '0' NOT NULL,
   forum_enter_limit mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
   forum_rules text,
   amazon_display tinyint(1),
   forum_thank tinyint(1) DEFAULT '0' NOT NULL, 
   forum_sort text NOT NULL,
   forum_digest tinyint(1) default '1' NOT NULL,
   forum_password varchar(20) NOT NULL DEFAULT '',
   hide_forum_on_index TINYINT(1) DEFAULT '0' NOT NULL,
   hide_forum_in_cat TINYINT(1) DEFAULT '0' NOT NULL,
   display_moderators TINYINT(1) DEFAULT '1' NOT NULL,
   index_posts TINYINT(1) DEFAULT '1' NOT NULL,
   forum_template mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
   stop_bumping TINYINT(1) DEFAULT '0' NOT NULL,
   forum_toggle TINYINT(1) DEFAULT '0' NOT NULL,
   index_lasttitle TINYINT(1) DEFAULT '0' NOT NULL,
   display_pic_alert TINYINT(1) DEFAULT '0' NOT NULL,
   forum_regdate_limit mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
   forum_subject_check tinyint(1) DEFAULT '0',
   topic_password tinyint(1) DEFAULT '0' NOT NULL,
   PRIMARY KEY (forum_id),
   KEY forums_order (forum_order),
   KEY cat_id (cat_id),
   KEY forum_last_post_id (forum_last_post_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_forums_descrip'
#
CREATE TABLE phpbb_forums_descrip (
   forum_id int(11) DEFAULT '0',
   user_id int(11) DEFAULT '0',
   view tinyint(4) DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8; 


# --------------------------------------------------------
#
# Table structure for table 'phpbb_forums_watch'
#
CREATE TABLE phpbb_forums_watch (
   forum_id mediumint(8) unsigned NOT NULL default '0', 
   user_id mediumint(8) NOT NULL default '0', 
   notify_status tinyint(1) NOT NULL default '0', 
   KEY forum_id (forum_id), 
   KEY user_id (user_id), 
   KEY notify_status (notify_status)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_groups'
#
CREATE TABLE phpbb_groups (
   group_id mediumint(8) NOT NULL auto_increment,
   group_type tinyint(4) DEFAULT '1' NOT NULL,
   group_name varchar(40) NOT NULL default '',
   group_description text NOT NULL default '',
   group_moderator mediumint(8) DEFAULT '0' NOT NULL,
   group_single_user tinyint(1) DEFAULT '1' NOT NULL,
   group_allow_pm tinyint(2) DEFAULT '5' NOT NULL,
   group_digest tinyint(1) default '0' NOT NULL,
   group_amount float default '0',
   group_period integer default '1',
   group_period_basis VARCHAR(10) default 'M',
   group_first_trial_fee float default '0',
   group_first_trial_period integer default '0',
   group_first_trial_period_basis VARCHAR(10) default '0',
   group_second_trial_fee float default '0',
   group_second_trial_period integer default '0',
   group_second_trial_period_basis VARCHAR(10) default '0',
   group_sub_recurring integer default '1',
   group_sub_recurring_stop integer default '0',
   group_sub_recurring_stop_num integer default '0',
   group_sub_reattempt integer default '1',
   allow_create_meeting TINYINT(1) DEFAULT '0' NOT NULL,
   group_members_count tinyint(1) DEFAULT '0' NOT NULL,
   group_colored TINYINT(1) NOT NULL,
   group_colors TEXT NOT NULL,
   group_order INTEGER(255) NOT NULL,
   group_rank_order MEDIUMINT(8) default '0' NOT NULL,
   group_validate tinyint(1) NOT NULL DEFAULT '1',
   PRIMARY KEY (group_id),
   KEY group_single_user (group_single_user)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_guestbook'
#
CREATE TABLE phpbb_guestbook (
  id mediumint(8) unsigned NOT NULL auto_increment,
  user_id mediumint(8) NOT NULL default '0',
  nick varchar(25) NOT NULL default '',
  data_ora int(11) NOT NULL default '0',
  email varchar(255) NOT NULL DEFAULT '',
  sito varchar(100) default NULL,
  comento text,
  bbcode_uid varchar(10) default NULL,
  ipi varchar(8) NOT NULL default '',
  agent varchar(255) NOT NULL default '',
  hide tinyint(1) default '0',
  PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_guestbook_config'
#
CREATE TABLE phpbb_guestbook_config (
  config_name varchar(255) NOT NULL default '',
  config_value varchar(255) NOT NULL default '',
  PRIMARY KEY (config_name)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_helpdesk_emails'
#
CREATE TABLE phpbb_helpdesk_emails (
   e_id int(10) DEFAULT '0' NOT NULL, 
   e_addr varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_helpdesk_importance'
#
CREATE TABLE phpbb_helpdesk_importance (
   value int(10) DEFAULT '0', 
   data text
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_helpdesk_msgs'
#
CREATE TABLE phpbb_helpdesk_msgs (
   e_id int(10) DEFAULT '0', 
   e_msg varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_helpdesk_reply'
#
CREATE TABLE phpbb_helpdesk_reply (
   value int(10) DEFAULT '0', 
   data text
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_im_buddy_list'
#
CREATE TABLE phpbb_im_buddy_list (
  user_id mediumint(8) NOT NULL default '0',
  contact_id mediumint(8) NOT NULL default '0',
  user_ignore tinyint(1) NOT NULL default '0',
  alert tinyint(1) NOT NULL default '0',
  alert_status tinyint(1) NOT NULL default '0',
  disallow tinyint(1) NOT NULL default '0',
  display_name varchar(25) NOT NULL default '',
  KEY contact_id (contact_id),
  KEY user_id (user_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_im_config'
#
CREATE TABLE phpbb_im_config (
  config_name varchar(255) NOT NULL default '',
  config_value varchar(255) NOT NULL default '',
  PRIMARY KEY (config_name)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_im_prefs'
#
CREATE TABLE phpbb_im_prefs (
  user_id mediumint(8) NOT NULL default '0',
  user_allow_ims tinyint(1) NOT NULL default '1',
  user_allow_shout tinyint(1) NOT NULL default '1',
  user_allow_chat tinyint(1) NOT NULL default '1',
  user_allow_network tinyint(1) NOT NULL default '1',
  admin_allow_ims tinyint(1) NOT NULL default '1',
  admin_allow_shout tinyint(1) NOT NULL default '1',
  admin_allow_chat tinyint(1) NOT NULL default '1',
  admin_allow_network tinyint(1) NOT NULL default '1',
  new_ims smallint(5) unsigned NOT NULL default '0',
  unread_ims smallint(5) unsigned NOT NULL default '0',
  read_ims smallint(5) unsigned NOT NULL default '0',
  total_sent mediumint(8) unsigned NOT NULL default '0',
  attach_sig tinyint(1) NOT NULL default '0',
  refresh_rate smallint(5) unsigned NOT NULL default '60',
  success_close tinyint(1) NOT NULL default '1',
  refresh_method tinyint(1) NOT NULL default '2',
  auto_launch tinyint(1) NOT NULL default '1',
  popup_ims tinyint(1) NOT NULL default '1',
  list_ims tinyint(1) NOT NULL default '0',
  show_controls tinyint(1) NOT NULL default '1',
  list_all_online tinyint(1) NOT NULL default '1',
  default_mode tinyint(1) NOT NULL default '1',
  current_mode tinyint(1) NOT NULL default '1',
  mode1_height varchar(4) NOT NULL default '400',
  mode1_width varchar(4) NOT NULL default '225',
  mode2_height varchar(4) NOT NULL default '230',
  mode2_width varchar(4) NOT NULL default '400',
  mode3_height varchar(4) NOT NULL default '100',
  mode3_width varchar(4) NOT NULL default '400',
  prefs_width varchar(4) NOT NULL default '500',
  prefs_height varchar(4) NOT NULL default '350',
  read_height varchar(4) NOT NULL default '300',
  read_width varchar(4) NOT NULL default '400',
  send_height varchar(4) NOT NULL default '365',
  send_width varchar(4) NOT NULL default '460',
  play_sound tinyint(1) NOT NULL default '1',
  default_sound tinyint(1) NOT NULL default '1',
  sound_name varchar(255) default NULL,
  themes_id mediumint(8) unsigned NOT NULL default '1',
  network_user_list tinyint(1) NOT NULL default '1',
  open_pms tinyint(1) NOT NULL default '0',
  auto_delete tinyint(1) NOT NULL default '0',
  use_frames tinyint(1) NOT NULL default '1',
  user_override tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (user_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_im_sessions'
#
CREATE TABLE phpbb_im_sessions (
   session_user_id mediumint(8) DEFAULT '0' NOT NULL,
   session_id char(32) DEFAULT '' NOT NULL,
   session_time int(11) DEFAULT '0' NOT NULL,
   session_popup tinyint(1) DEFAULT '0' NOT NULL,
   PRIMARY KEY (session_id),
   KEY session_user_id (session_user_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_im_sites'
#
CREATE TABLE phpbb_im_sites (
  site_id mediumint(8) NOT NULL auto_increment,
  site_name varchar(50) NOT NULL default '',
  site_url varchar(100) NOT NULL default '',
  site_phpex varchar(4) NOT NULL default 'php',
  site_profile varchar(50) NOT NULL default 'profile',
  site_enable tinyint(1) NOT NULL default '1',
  PRIMARY KEY (site_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# -------------------------------------------------------- 
# 
# Table structure for table 'phpbb_ina_ban' 
# 
CREATE TABLE phpbb_ina_ban ( 
  id int(10) NOT NULL default '0', 
  username varchar(40) default NULL 
) ENGINE=MyISAM DEFAULT CHARSET=utf8; 


# -------------------------------------------------------- 
# 
# Table structure for table 'phpbb_ina_categories' 
# 
CREATE TABLE phpbb_ina_categories ( 
  cat_id mediumint(9) NOT NULL auto_increment, 
  cat_name varchar(25) default NULL, 
  cat_desc text NOT NULL, 
  cat_img varchar(255) NOT NULL default '', 
  PRIMARY KEY (cat_id) 
) ENGINE=MyISAM DEFAULT CHARSET=utf8; 


# -------------------------------------------------------- 
# 
# Table structure for table 'phpbb_ina_challenge_tracker' 
# 
CREATE TABLE phpbb_ina_challenge_tracker ( 
  user int(10) default '0', 
  count int(25) default '0' 
) ENGINE=MyISAM DEFAULT CHARSET=utf8; 


# -------------------------------------------------------- 
# 
# Table structure for table 'phpbb_ina_challenge_users' 
# 
CREATE TABLE phpbb_ina_challenge_users ( 
  user_to int(10) default '0', 
  user_from int(10) default '0', 
  count int(25) default '0' 
) ENGINE=MyISAM DEFAULT CHARSET=utf8; 


# -------------------------------------------------------- 
# 
# Table structure for table 'phpbb_ina_chat' 
# 
CREATE TABLE phpbb_ina_chat (
  chat_date DATE NOT NULL DEFAULT '0000-00-00', 
  chat_text TEXT NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# -------------------------------------------------------- 
# 
# Table structure for table 'phpbb_ina_cheat_fix' 
# 
CREATE TABLE phpbb_ina_cheat_fix ( 
  game_id int(10) NOT NULL default '0', 
  player int(10) default '0' 
) ENGINE=MyISAM DEFAULT CHARSET=utf8; 


# -------------------------------------------------------- 
# 
# Table structure for table 'phpbb_ina_data' 
# 
CREATE TABLE phpbb_ina_data ( 
  version varchar(255) default NULL 
) ENGINE=MyISAM DEFAULT CHARSET=utf8; 


# -------------------------------------------------------- 
# 
# Table structure for table 'phpbb_ina_favorites' 
# 
CREATE TABLE phpbb_ina_favorites ( 
  user int(10) NOT NULL default '0', 
  games text 
) ENGINE=MyISAM DEFAULT CHARSET=utf8; 


# -------------------------------------------------------- 
# 
# Table structure for table 'phpbb_ina_gamble' 
# 
CREATE TABLE phpbb_ina_gamble ( 
  game_id int(20) default '0', 
  sender_id int(11) default '0', 
  reciever_id int(11) default '0', 
  amount int(10) default '0', 
  winner_id int(11) default '0', 
  loser_id int(11) default '0', 
  winner_score int(11) default '0', 
  loser_score int(11) default '0', 
  date int(20) default NULL, 
  been_paid int(11) default '0' 
) ENGINE=MyISAM DEFAULT CHARSET=utf8; 


# -------------------------------------------------------- 
# 
# Table structure for table 'phpbb_ina_gamble_in_progress' 
# 
CREATE TABLE phpbb_ina_gamble_in_progress ( 
  game_id int(20) default '0', 
  sender_id int(11) default '0', 
  reciever_id int(11) default '0', 
  sender_score int(20) default '0', 
  reciever_score int(20) default '0', 
  sender_playing int(1) NOT NULL default '0', 
  reciever_playing int(1) NOT NULL default '0' 
) ENGINE=MyISAM DEFAULT CHARSET=utf8; 


# -------------------------------------------------------- 
# 
# Table structure for table 'phpbb_ina_games' 
# 
CREATE TABLE phpbb_ina_games ( 
  game_id mediumint(9) NOT NULL auto_increment, 
  game_name varchar(25) default NULL, 
  game_path varchar(255) default NULL, 
  game_desc varchar(255) default NULL, 
  game_charge int(11) unsigned default '0', 
  game_reward int(11) unsigned NOT NULL default '0', 
  game_bonus smallint(5) unsigned default '0', 
  game_use_gl tinyint(3) unsigned default '0', 
  game_flash tinyint(1) unsigned NOT NULL default '0', 
  game_show_score tinyint(1) NOT NULL default '1', 
  win_width smallint(6) NOT NULL default '0', 
  win_height smallint(6) NOT NULL default '0', 
  highscore_limit varchar(255) default NULL, 
  reverse_list tinyint(1) NOT NULL default '0', 
  played int(10) unsigned NOT NULL default '0', 
  instructions text, 
  disabled int(1) NOT NULL default '1', 
  install_date int(20) NOT NULL default '0', 
  proper_name text NOT NULL default '', 
  cat_id int(4) NOT NULL default '0', 
  jackpot int(10) NOT NULL default '0',
  game_popup smallint(1) NOT NULL default '1',
  game_parent smallint(1) NOT NULL default '1',
  game_type smallint(1) NOT NULL default '1',
  game_links smallint(1) NOT NULL default '0',
  game_ge_cost int(10) NOT NULL default '0',
  game_keyboard smallint(1) NOT NULL default '0',
  game_mouse smallint(1) NOT NULL default '0',
  PRIMARY KEY (game_id) 
) ENGINE=MyISAM DEFAULT CHARSET=utf8; 


# -------------------------------------------------------- 
# 
# Table structure for table 'phpbb_ina_last_game_played' 
# 
CREATE TABLE phpbb_ina_last_game_played ( 
  game_id int(20) default '0', 
  user_id mediumint(8) NOT NULL, 
  date int(20) default NULL 
) ENGINE=MyISAM DEFAULT CHARSET=utf8; 


# -------------------------------------------------------- 
# 
# Table structure for table 'phpbb_ina_hall_of_fame' 
# 
CREATE TABLE phpbb_ina_hall_of_fame (
  game_id MEDIUMINT(8) NOT NULL DEFAULT '0',
  current_user_id MEDIUMINT(8) NOT NULL DEFAULT '0',
  current_score FLOAT(10,2) NOT NULL DEFAULT '0.00', 
  date_today INT(10) NOT NULL DEFAULT '0', 
  old_user_id MEDIUMINT(8) NOT NULL DEFAULT '0',
  old_score FLOAT(10,2) NOT NULL DEFAULT '0.00',
  old_date INT(10) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# -------------------------------------------------------- 
# 
# Table structure for table 'phpbb_ina_rating_votes' 
# 
CREATE TABLE phpbb_ina_rating_votes ( 
  game_id int(15) NOT NULL default '0', 
  rating int(15) NOT NULL default '0', 
  date int(15) NOT NULL default '0', 
  player int(15) NOT NULL default '0' 
) ENGINE=MyISAM DEFAULT CHARSET=utf8; 


# -------------------------------------------------------- 
# 
# Table structure for table 'phpbb_ina_scores' 
# 
CREATE TABLE phpbb_ina_scores ( 
  game_name varchar(255) default NULL, 
  player varchar(40) default NULL, 
  score float(10,2) NOT NULL default '0.00', 
  date int(11) default NULL,
  user_plays int(6) default '0' NOT NULL,
  play_time int(10) default '0' NOT NULL  
) ENGINE=MyISAM DEFAULT CHARSET=utf8; 


# -------------------------------------------------------- 
# 
# Table structure for table 'phpbb_ina_sessions' 
# 
CREATE TABLE phpbb_ina_sessions ( 
  playing_time int(15) NOT NULL default '0', 
  playing_id int(10) NOT NULL default '0', 
  playing int(11) NOT NULL default '0' 
) ENGINE=MyISAM DEFAULT CHARSET=utf8; 


# -------------------------------------------------------- 
# 
# Table structure for table 'phpbb_ina_top_scores' 
# 

CREATE TABLE phpbb_ina_top_scores ( 
  game_name varchar(255) default NULL, 
  player mediumint(8) NOT NULL, 
  score float(10,2) NOT NULL default '0.00', 
  date int(11) default NULL 
) ENGINE=MyISAM DEFAULT CHARSET=utf8; 


# -------------------------------------------------------- 
# 
# Table structure for table 'phpbb_ina_trophy_comments' 
# 
CREATE TABLE phpbb_ina_trophy_comments ( 
  game varchar(255) NOT NULL default '', 
  player int(10) default NULL, 
  comment text NOT NULL default '', 
  date int(15) NOT NULL default '0', 
  score int(20) NOT NULL default '0' 
) ENGINE=MyISAM DEFAULT CHARSET=utf8; 


# --------------------------------------------------------
#
# Table structure for table 'phpbb_inline_ads'
#
CREATE TABLE phpbb_inline_ads (
   ad_id TINYINT(5) NOT NULL auto_increment,
   ad_code TEXT NOT NULL,
   ad_name CHAR(25) NOT NULL default '',
   PRIMARY KEY (ad_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8; 


# --------------------------------------------------------
#
# Table structure for table 'phpbb_ip'
#
CREATE TABLE phpbb_ip (
   id mediumint(8) NOT NULL auto_increment, 
   ip varchar(200) DEFAULT '0' NOT NULL, 
   host varchar(200) DEFAULT '0' NOT NULL, 
   date int(11) DEFAULT '0' NOT NULL, 
   username varchar(200) DEFAULT '0' NOT NULL, 
   referrer varchar(200) DEFAULT '0' NOT NULL, 
   forum varchar(200) DEFAULT '0' NOT NULL, 
   browser varchar(200) DEFAULT '0' NOT NULL, 
   PRIMARY KEY (id),
   KEY username (username)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_jobs'
#
CREATE TABLE phpbb_jobs (
  id mediumint(8) NOT NULL auto_increment,
  name varchar(32) NOT NULL default '',
  pay mediumint(8) default '100',
  type varchar(32) default 'public',
  requires text,
  payouttime mediumint(8) default '500000',
  positions mediumint(8) NOT NULL default '0',
  PRIMARY KEY (id),
  KEY name (name)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_jobs_employed'
#
CREATE TABLE phpbb_jobs_employed (
  id mediumint(8) NOT NULL auto_increment,
  user_id mediumint(8) NOT NULL,
  job_name varchar(32) NOT NULL default '',
  job_pay mediumint(8) NOT NULL,
  job_length mediumint(8) NOT NULL,
  last_paid mediumint(8) NOT NULL,
  job_started mediumint(8) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_kb_articles'
#
CREATE TABLE phpbb_kb_articles (
   article_id mediumint(8) UNSIGNED NOT NULL auto_increment, 
   article_category_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL, 
   article_title varchar(255) binary DEFAULT '' NOT NULL, 
   article_description varchar(255) binary DEFAULT '' NOT NULL, 
   article_date varchar(255) binary DEFAULT '' NOT NULL, 
   article_author_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL, 
   username varchar(255), 
   bbcode_uid char(10) DEFAULT '' NOT NULL, 
   article_body text NOT NULL, 
   article_type mediumint(8) UNSIGNED DEFAULT '0' NOT NULL, 
   approved tinyint(1) UNSIGNED DEFAULT '0' NOT NULL, 
   topic_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
   views bigint(8) DEFAULT '0' NOT NULL,
   KEY article_id (article_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_kb_categories'
#
CREATE TABLE phpbb_kb_categories (
   category_id mediumint(8) UNSIGNED NOT NULL auto_increment, 
   category_name varchar(255) binary NOT NULL, 
   category_details varchar(255) binary NOT NULL, 
   number_articles mediumint(8) UNSIGNED NOT NULL, 
   parent mediumint(8) UNSIGNED NOT NULL,
   cat_order mediumint(8) UNSIGNED NOT NULL,
   KEY category_id (category_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_kb_types'
#
CREATE TABLE phpbb_kb_types (
   id mediumint(8) UNSIGNED NOT NULL auto_increment, 
   type varchar(255) binary DEFAULT '' NOT NULL, 
   KEY id (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_kb_results'
#
CREATE TABLE phpbb_kb_results (
   search_id int(11) UNSIGNED DEFAULT '0' NOT NULL, 
   session_id varchar(32) DEFAULT '' NOT NULL, 
   search_array mediumtext NOT NULL DEFAULT '',
   PRIMARY KEY (search_id),
   KEY session_id (session_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_kb_wordlist'
#
CREATE TABLE phpbb_kb_wordlist (
   word_text varchar(50) binary DEFAULT '' NOT NULL, 
   word_id mediumint(8) UNSIGNED NOT NULL auto_increment, 
   word_common tinyint(1) UNSIGNED DEFAULT '0' NOT NULL, 
   PRIMARY KEY (word_text),
   KEY word_id (word_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_kb_wordmatch'
#
CREATE TABLE phpbb_kb_wordmatch (
   article_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL, 
   word_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL, 
   title_match tinyint(1) DEFAULT '0' NOT NULL,
   KEY post_id (article_id),
   KEY word_id (word_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_lexicon'
#
CREATE TABLE phpbb_lexicon ( 
  id bigint(8) NOT NULL auto_increment, 
  keyword VARCHAR(250) NOT NULL DEFAULT '', 
  explanation LONGTEXT NOT NULL, 
  bbcode_uid CHAR(10) DEFAULT '' NOT NULL, 
  cat INT(8) NOT NULL DEFAULT '1', 
  PRIMARY KEY  (id), 
  KEY cat (cat) 
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# --------------------------------------------------------
#
# Table structure for table 'phpbb_lexicon_cat'
#
CREATE TABLE phpbb_lexicon_cat (
  cat_id INT(8) NOT NULL auto_increment,
  cat_titel VARCHAR(80) NOT NULL DEFAULT '',
  PRIMARY KEY  (cat_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_links'
#
CREATE TABLE phpbb_links (
   link_id mediumint(8) UNSIGNED NOT NULL auto_increment, 
   link_name text, 
   link_longdesc text, 
   link_catid mediumint(8) UNSIGNED DEFAULT '0' NOT NULL, 
   link_url varchar(100) DEFAULT '' NOT NULL, 
   link_logo_src varchar(100) DEFAULT NULL, 
   link_time int(25) DEFAULT '0' NOT NULL, 
   link_approved tinyint(1) DEFAULT '0' NOT NULL, 
   link_hits int(10) UNSIGNED DEFAULT '0' NOT NULL, 
   user_id mediumint(8) DEFAULT '0' NOT NULL, 
   poster_ip varchar(8) DEFAULT '' NOT NULL, 
   last_user_ip varchar(8) DEFAULT '' NOT NULL,
   post_username varchar(25) DEFAULT NULL,
   link_pin INT(2) DEFAULT "0", 
   PRIMARY KEY (link_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_link_categories'
#
CREATE TABLE phpbb_link_categories (
   cat_id int(10) NOT NULL auto_increment, 
   cat_name text DEFAULT '' NOT NULL, 
   cat_order int(50), 
   cat_parent int(50) DEFAULT NULL,
   parents_data text NOT NULL,
   cat_links mediumint(8) NOT NULL DEFAULT '-1',
   PRIMARY KEY (cat_id), 
   KEY cat_order (cat_order)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_link_comments'
#
CREATE TABLE phpbb_link_comments (
  comments_id int(10) NOT NULL auto_increment,
  link_id int(10) NOT NULL default '0',
  comments_text text NOT NULL,
  comments_title text NOT NULL,
  comments_time int(50) NOT NULL default '0',
  comment_bbcode_uid varchar(10) default NULL,
  poster_id mediumint(8) NOT NULL default '0',
  PRIMARY KEY (comments_id),
  FULLTEXT KEY comment_bbcode_uid (comment_bbcode_uid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_link_config'
#
CREATE TABLE phpbb_link_config (
   config_name varchar(255) NOT NULL default '', 
   config_value varchar(255) NOT NULL default '',
   PRIMARY KEY (config_name) 
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_link_custom'
#
CREATE TABLE phpbb_link_custom (
	custom_id int(50) NOT NULL auto_increment,
	custom_name text NOT NULL,
	custom_description text NOT NULL,
	data text NOT NULL,
	field_order int(20) NOT NULL default '0',
	field_type tinyint(2) NOT NULL default '0',
	regex varchar(255) NOT NULL default '',
	PRIMARY KEY  (custom_id)	
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_link_customdata'
#
CREATE TABLE phpbb_link_customdata (
	customdata_file int(50) NOT NULL default '0',
	customdata_custom int(50) NOT NULL default '0',
	data text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_link_votes'
#
CREATE TABLE phpbb_link_votes (
  user_id mediumint(8) NOT NULL default '0',
  votes_ip varchar(50) NOT NULL default '0',
  votes_link int(50) NOT NULL default '0',
  rate_point tinyint(3) unsigned NOT NULL default '0',
  KEY user_id (user_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_logs'
#
CREATE TABLE phpbb_logs (
   id_log mediumint(10) NOT NULL auto_increment, 
   mode varchar(50) DEFAULT '' NULL, 
   topic_id mediumint(10) DEFAULT '0' NULL, 
   user_id mediumint(8) DEFAULT '0' NULL, 
   username varchar(255) DEFAULT '' NULL, 
   user_ip char(8) DEFAULT '0' NOT NULL, 
   time INT(11) DEFAULT '0' NULL, 
   PRIMARY KEY (id_log)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_lottery'
#
CREATE TABLE phpbb_lottery (
   id int UNSIGNED NOT NULL auto_increment, 
   user_id int(10) NOT NULL, 
   PRIMARY KEY (id), 
   INDEX (user_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_lottery_history'
#
CREATE TABLE phpbb_lottery_history (
   id int UNSIGNED NOT NULL auto_increment,
   user_id int(10) NOT NULL,
   amount int(10) NOT NULL,
   time int(10) NOT NULL,
   PRIMARY KEY (id),
   INDEX (user_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_medal'
#
CREATE TABLE phpbb_medal (
   medal_id mediumint(8) UNSIGNED NOT NULL auto_increment,
   cat_id mediumint(8) UNSIGNED NOT NULL default '1',
   medal_name varchar(40) NOT NULL,
   medal_description varchar(255) NOT NULL,
   medal_image varchar(40) NULL,
  PRIMARY KEY (medal_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_medal_cat'
#
CREATE TABLE phpbb_medal_cat (
   cat_id mediumint(8) UNSIGNED NOT NULL auto_increment,
   cat_title varchar(100) NOT NULL,
   cat_order mediumint(8) UNSIGNED NOT NULL default '0',
  PRIMARY KEY (cat_id),
  KEY cat_order (cat_order)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_medal_mod'
#
CREATE TABLE phpbb_medal_mod (
   mod_id mediumint(8) UNSIGNED NOT NULL auto_increment,
   medal_id mediumint(8) UNSIGNED NOT NULL,
   user_id mediumint(8) UNSIGNED NOT NULL,
  PRIMARY KEY (mod_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

# --------------------------------------------------------
#
# Table structure for table 'phpbb_medal_user'
#
CREATE TABLE phpbb_medal_user (
   issue_id mediumint(8) UNSIGNED NOT NULL auto_increment,
   medal_id mediumint(8) UNSIGNED NOT NULL,
   user_id mediumint(8) UNSIGNED NOT NULL,
   issue_reason varchar(255) NOT NULL,
   issue_time int(11) NOT NULL,
  PRIMARY KEY (issue_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_meeting_comment'
#
CREATE TABLE phpbb_meeting_comment (
   comment_id mediumint(8) auto_increment,
   meeting_id mediumint(8) unsigned NOT NULL, 
   user_id mediumint(8) DEFAULT '0' NOT NULL, 
   meeting_comment text NOT NULL, 
   meeting_edit_time int(11) DEFAULT '0' NOT NULL,
   bbcode_uid char(10) DEFAULT '' NOT NULL,
   PRIMARY KEY (comment_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# --------------------------------------------------------
#
# Table structure for table 'phpbb_meeting_config'
#
CREATE TABLE phpbb_meeting_config (
    config_name varchar(255) NOT NULL DEFAULT '',
    config_value varchar(255) NOT NULL DEFAULT '',
    PRIMARY KEY (config_name)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_meeting_data'
#
CREATE TABLE phpbb_meeting_data (
   meeting_id mediumint(8) unsigned NOT NULL, 
   meeting_time int(11) DEFAULT '0' NOT NULL, 
   meeting_until int(11) DEFAULT '0' NOT NULL, 
   meeting_location varchar(255) NOT NULL DEFAULT '', 
   meeting_subject varchar(255) NOT NULL DEFAULT '', 
   meeting_desc text NOT NULL, 
   meeting_link varchar(255) NOT NULL DEFAULT '', 
   meeting_places mediumint(8) DEFAULT '0' NOT NULL, 
   meeting_by_user MEDIUMINT(8) DEFAULT '0' NOT NULL,
   meeting_edit_by_user MEDIUMINT(8) DEFAULT '0' NOT NULL,
   meeting_start_value MEDIUMINT(8) DEFAULT '0' NOT NULL,
   meeting_recure_value MEDIUMINT(8) DEFAULT '5' NOT NULL,
   meeting_notify tinyint(1) NOT NULL DEFAULT '0',
   meeting_guest_overall MEDIUMINT(8) NOT NULL DEFAULT '0',
   meeting_guest_single MEDIUMINT(8) NOT NULL DEFAULT '0',
   meeting_guest_names TINYINT(1) NOT NULL DEFAULT '0',
   bbcode_uid char(10) DEFAULT '' NOT NULL,
   PRIMARY KEY (meeting_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_meeting_guestnames'
#
CREATE TABLE phpbb_meeting_guestnames (
	meeting_id MEDIUMINT(8) NOT NULL DEFAULT '0',
	user_id MEDIUMINT(8) NOT NULL DEFAULT '0',
	guest_prename VARCHAR(255) NOT NULL DEFAULT '',
	guest_name VARCHAR(255) NOT NULL DEFAULT ''
);


# --------------------------------------------------------
#
# Table structure for table 'phpbb_meeting_user'
#
CREATE TABLE phpbb_meeting_user (
   meeting_id mediumint(8) unsigned NOT NULL, 
   user_id mediumint(8) DEFAULT '0' NOT NULL,
   meeting_sure tinyint(4) DEFAULT '0' NOT NULL,
   meeting_guests MEDIUMINT(8) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_meeting_usergroup'
#
CREATE TABLE phpbb_meeting_usergroup (
   meeting_id mediumint(8) unsigned NOT NULL, 
   meeting_group mediumint(8) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

# --------------------------------------------------------
#
# Table structure for table 'phpbb_module_admin_panel'
#
CREATE TABLE phpbb_module_admin_panel (
   module_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL, 
   config_name varchar(255) DEFAULT '' NOT NULL, 
   config_value varchar(255) DEFAULT '' NOT NULL, 
   config_type varchar(20) DEFAULT '' NOT NULL, 
   config_title varchar(100) DEFAULT '' NOT NULL, 
   config_explain varchar(100) default NULL, 
   config_trigger varchar(20) DEFAULT '' NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_module_cache'
#
CREATE TABLE phpbb_module_cache (
   module_id mediumint(8) DEFAULT '0' NOT NULL, 
   module_cache_time int(12) DEFAULT '0' NOT NULL, 
   db_cache TEXT NOT NULL, 
   priority mediumint(8) UNSIGNED DEFAULT '0' NOT NULL, 
   PRIMARY KEY (module_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_module_group_auth'
#
CREATE TABLE phpbb_module_group_auth (
   module_id mediumint(8) UNSIGNED NOT NULL, 
   group_id mediumint(8) UNSIGNED NOT NULL 
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_module_info'
#
CREATE TABLE phpbb_module_info (
   module_id mediumint(8) DEFAULT '0' NOT NULL, 
   long_name varchar(100) DEFAULT '' NOT NULL, 
   author varchar(50) default NULL,
   email varchar(255) NOT NULL DEFAULT '',
   url varchar(100) default NULL, 
   version varchar(10) DEFAULT '' NOT NULL, 
   update_site varchar(100) default NULL, 
   extra_info longtext DEFAULT '' NOT NULL, 
   PRIMARY KEY (module_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_modules'
#
CREATE TABLE phpbb_modules (
   module_id mediumint(8) UNSIGNED NOT NULL auto_increment, 
   short_name varchar(100) default NULL, 
   update_time mediumint(8) DEFAULT '0' NOT NULL, 
   module_order mediumint(8) DEFAULT '0' NOT NULL, 
   active tinyint(2) DEFAULT '0' NOT NULL,
   perm_all tinyint(2) UNSIGNED DEFAULT '1' NOT NULL, 
   perm_reg tinyint(2) UNSIGNED DEFAULT '1' NOT NULL, 
   perm_mod tinyint(2) UNSIGNED DEFAULT '1' NOT NULL, 
   perm_admin tinyint(2) UNSIGNED DEFAULT '1' NOT NULL, 
   PRIMARY KEY (module_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_mycalendar'
#
CREATE TABLE phpbb_mycalendar (
   cal_id int(12) NOT NULL auto_increment, 
   topic_id int(20) DEFAULT '0' NOT NULL,
   cal_date DATETIME DEFAULT '00-00-00 00:00:00' NULL,
   cal_interval tinyint(3) DEFAULT '1' NOT NULL,
   cal_interval_units enum('DAY', 'WEEK', 'MONTH', 'YEAR') DEFAULT 'DAY' NOT NULL,
   cal_repeat tinyint(3) DEFAULT '1' NOT NULL,
   forum_id int(5) DEFAULT '0' NOT NULL,
   confirmed enum( 'Y', 'N' ) DEFAULT 'Y' NOT NULL,
   event_type_id tinyint(4) DEFAULT '0' NOT NULL,
   PRIMARY KEY (cal_id),
   UNIQUE (topic_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

# --------------------------------------------------------
#
# Table structure for table 'phpbb_mycalendar_event_types'
#
CREATE TABLE phpbb_mycalendar_event_types (
   forum_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
   event_type_id tinyint(4) NOT NULL,
   event_type_text varchar(255) NOT NULL default '', 
   highlight_color VARCHAR(7) NOT NULL default '' 
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_optimize_db'
#
CREATE TABLE phpbb_optimize_db (
   cron_enable enum('0','1') NOT NULL default '0',
   cron_every int(7) NOT NULL default '86400',
   cron_next int(11) NOT NULL default '0',
   cron_count int(5) NOT NULL default '0',
   show_tables varchar(150) NOT NULL default '',
   empty_tables tinyint(1) NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_pa_auth'
#
CREATE TABLE phpbb_pa_auth (
   group_id mediumint(8) DEFAULT '0' NOT NULL,
   cat_id smallint(5) UNSIGNED DEFAULT '0' NOT NULL,
   auth_view tinyint(1) DEFAULT '0' NOT NULL,
   auth_read tinyint(1) DEFAULT '0' NOT NULL,
   auth_view_file tinyint(1) DEFAULT '0' NOT NULL,
   auth_edit_file tinyint(1) DEFAULT '0' NOT NULL,
   auth_delete_file tinyint(1) DEFAULT '0' NOT NULL,
   auth_upload tinyint(1) DEFAULT '0' NOT NULL,
   auth_download tinyint(1) DEFAULT '0' NOT NULL,
   auth_rate tinyint(1) DEFAULT '0' NOT NULL,
   auth_email tinyint(1) DEFAULT '0' NOT NULL,
   auth_view_comment tinyint(1) DEFAULT '0' NOT NULL,
   auth_post_comment tinyint(1) DEFAULT '0' NOT NULL,
   auth_edit_comment tinyint(1) DEFAULT '0' NOT NULL,
   auth_delete_comment tinyint(1) DEFAULT '0' NOT NULL,
   auth_mod tinyint(1) DEFAULT '0' NOT NULL,
   auth_search tinyint(1) DEFAULT '1' NOT NULL,
   auth_stats tinyint(1) DEFAULT '1' NOT NULL,
   auth_toplist tinyint(1) DEFAULT '1' NOT NULL,
   auth_viewall tinyint(1) DEFAULT '1' NOT NULL,
   KEY group_id (group_id),
   KEY cat_id (cat_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_pa_cat'
#
CREATE TABLE phpbb_pa_cat (
  cat_id int(10) NOT NULL auto_increment,
  cat_name text,
  cat_desc text,
  cat_parent int(50) default NULL,
  parents_data text NOT NULL default '',
  cat_order int(50) default NULL,
  cat_allow_file tinyint(2) NOT NULL default '0',
  cat_allow_ratings tinyint(2) NOT NULL default '1',
  cat_allow_comments tinyint(2) NOT NULL default '1',
  cat_files mediumint(8) NOT NULL default '-1',
  cat_last_file_id mediumint(8) unsigned NOT NULL default '0',
  cat_last_file_name varchar(255) NOT NULL default '',
  cat_last_file_time INT(50) UNSIGNED DEFAULT '0' NOT NULL,
  auth_view tinyint(2) NOT NULL default '0',
  auth_read tinyint(2) NOT NULL default '0',
  auth_view_file tinyint(2) NOT NULL default '0',
  auth_edit_file tinyint(1) NOT NULL default '0',
  auth_delete_file tinyint(1) NOT NULL default '0',
  auth_upload tinyint(2) NOT NULL default '0',
  auth_download tinyint(2) NOT NULL default '0',
  auth_rate tinyint(2) NOT NULL default '0',
  auth_email tinyint(2) NOT NULL default '0',
  auth_view_comment tinyint(2) NOT NULL default '0',
  auth_post_comment tinyint(2) NOT NULL default '0',
  auth_edit_comment tinyint(2) NOT NULL default '0',
  auth_delete_comment tinyint(2) NOT NULL default '0',
  PRIMARY KEY (cat_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_pa_comments'
#
CREATE TABLE phpbb_pa_comments (
   comments_id int(10) NOT NULL auto_increment, 
   file_id int(10) NOT NULL DEFAULT '0', 
   comments_text text NOT NULL default '', 
   comments_title text NOT NULL default '', 
   comments_time int(50) NOT NULL DEFAULT '0', 
   comment_bbcode_uid char(10) DEFAULT '' NOT NULL, 
   poster_id mediumint(8) NOT NULL DEFAULT '0', 
   PRIMARY KEY (comments_id), 
   FULLTEXT KEY comment_bbcode_uid (comment_bbcode_uid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_pa_config'
#
CREATE TABLE phpbb_pa_config (
  config_name varchar(255) NOT NULL default '',
  config_value varchar(255) NOT NULL default '',
  PRIMARY KEY  (config_name) 
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------#
# Table structure for table 'phpbb_pa_custom'
#
CREATE TABLE phpbb_pa_custom (
   custom_id int(50) NOT NULL auto_increment, 
   custom_name text NOT NULL default '', 
   custom_description text NOT NULL default '', 
   data text NOT NULL default '',
   field_order int(20) NOT NULL default '0',
   field_type tinyint(2) NOT NULL default '0',
   regex varchar(255) NOT NULL default '',
   PRIMARY KEY (custom_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_pa_customdata'
#
CREATE TABLE phpbb_pa_customdata (
   customdata_file int(50) NOT NULL DEFAULT '0', 
   customdata_custom int(50) NOT NULL DEFAULT '0', 
   data text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# --------------------------------------------------------
#
# Table structure for table 'phpbb_pa_download_info'
#
CREATE TABLE phpbb_pa_download_info (
  file_id mediumint(8) NOT NULL default '0',
  user_id mediumint(8) NOT NULL default '0',
  downloader_ip varchar(8) NOT NULL default '',
  downloader_os varchar(255) NOT NULL default '',
  downloader_browser varchar(255) NOT NULL default '',
  browser_version varchar(255) NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_pa_files'
#
CREATE TABLE phpbb_pa_files (
  file_id int(10) NOT NULL auto_increment,
  user_id mediumint(8) NOT NULL default '0',
  poster_ip varchar(8) NOT NULL default '',
  file_name text,
  file_size int(20) NOT NULL default '0',
  unique_name varchar(255) NOT NULL default '',
  real_name VARCHAR(255) NOT NULL default '',
  file_dir VARCHAR(255) NOT NULL default '',
  file_desc text,
  file_creator text,
  file_version text,
  file_longdesc text,
  file_ssurl text,
  file_sshot_link tinyint(2) NOT NULL default '0',
  file_dlurl text,
  file_time int(50) default NULL,
  file_update_time int(50) NOT NULL default '0',
  file_catid int(10) default NULL,
  file_posticon text,
  file_license int(10) default NULL,
  file_dls int(10) default NULL,
  file_last int(50) default NULL,
  file_pin int(2) default NULL,
  file_docsurl text,
  file_approved int(11) default NULL,
  file_broken TINYINT(1) DEFAULT '0' NOT NULL,
  PRIMARY KEY (file_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_pa_license'
#
CREATE TABLE phpbb_pa_license (
   license_id int(10) NOT NULL auto_increment, 
   license_name text, 
   license_text text, 
   PRIMARY KEY (license_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_pa_mirrors'
#
CREATE TABLE phpbb_pa_mirrors (
  mirror_id mediumint(8) NOT NULL auto_increment, 
  file_id int(10) NOT NULL,
  unique_name varchar(255) NOT NULL default '',
  file_dir VARCHAR(255) NOT NULL default '', 
  file_dlurl varchar(255) NOT NULL default '',
  mirror_location VARCHAR(255) NOT NULL default '',
  PRIMARY KEY (mirror_id),
  KEY file_id (file_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_pa_votes'
#
CREATE TABLE phpbb_pa_votes (
  user_id mediumint(8) NOT NULL default '0',
  votes_ip varchar(50) NOT NULL default '0',
  votes_file int(50) NOT NULL default '0',
  rate_point tinyint(3) unsigned NOT NULL default '0',
  voter_os varchar(255) NOT NULL default '',
  voter_browser varchar(255) NOT NULL default '',
  browser_version varchar(8) NOT NULL default '',
  KEY user_id (user_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_pages' 
#
CREATE TABLE phpbb_pages (
  page_id mediumint(5) unsigned auto_increment,
  page_name varchar(32) NOT NULL,
  page_parm_name varchar(32) default '',
  page_parm_value varchar(32) default '',
  page_key varchar(255) default '',
  member_views int(11) unsigned default '0',
  guest_views int(11) unsigned default '0',
  disable_page tinyint(1) unsigned default '0',
  auth_level tinyint(2) unsigned default '0',
  min_post_count mediumint(8) unsigned default '0',
  max_post_count mediumint(8) unsigned default '0',
  group_list varchar(255) default '',
  disabled_message	varchar(255) default '',
  primary key (page_id),
  unique key (page_key)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_pjirc'
#
CREATE TABLE phpbb_pjirc (
   pjirc_name varchar(255) NOT NULL default '', 
   pjirc_value varchar(255) NOT NULL default '',
   PRIMARY KEY (pjirc_name)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_points_logger'
#
CREATE TABLE phpbb_points_logger (
   id mediumint(8) NOT NULL auto_increment, 
   admin varchar(25) NOT NULL default '', 
   person varchar(25) NOT NULL default '', 
   add_sub varchar(50) NOT NULL default '', 
   total mediumint(8) DEFAULT '0' NOT NULL, 
   time int(11) DEFAULT '0' NOT NULL, 
   PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_portal'
#
CREATE TABLE phpbb_portal (
   portal_id mediumint(8) NOT NULL auto_increment, 
   portal_order mediumint(8) UNSIGNED DEFAULT '1' NOT NULL,
   portal_use_url tinyint(1) NOT NULL default '0', 
   portal_iframe_height VARCHAR(4) NOT NULL DEFAULT '600',
   portal_use_iframe tinyint(1) NOT NULL default '0', 
   portal_forum mediumint(8) NOT NULL, 
   portal_url varchar(255) NOT NULL default '', 
   portal_list_limit mediumint(8) NOT NULL, 
   portal_char_limit mediumint(8) NOT NULL, 
   portal_ascending tinyint(1) NOT NULL default '0', 
   portal_nodate tinyint(1) NOT NULL default '0', 
   portal_navbar_name varchar(100) NOT NULL default '', 
   portal_newsfader tinyint(1) NOT NULL default '0',
   portal_column_width varchar(3) DEFAULT '200' NOT NULL,
   portal_navbar tinyint(1) NOT NULL default '0', 
   portal_moreover tinyint(1) NOT NULL default '0', 
   portal_calendar tinyint(1) NOT NULL default '0', 
   portal_online tinyint(1) NOT NULL default '0', 
   portal_onlinetoday tinyint(1) NOT NULL default '0', 
   portal_latest tinyint(1) NOT NULL default '0', 
   portal_latest_exclude_forums varchar(100) NOT NULL default '', 
   portal_latest_amt varchar(5) DEFAULT '5' NOT NULL, 
   portal_latest_scrolling TINYINT(1) NOT NULL DEFAULT '0',
   portal_poll tinyint(1) NOT NULL default '0', 
   portal_polls varchar(100) NOT NULL default '', 
   portal_photo tinyint(1) NOT NULL default '0', 
   portal_birthday mediumint(6) DEFAULT '999999' NOT NULL, 
   portal_search tinyint(1) NOT NULL default '0', 
   portal_quote tinyint(1) NOT NULL default '0', 
   portal_links tinyint(1) NOT NULL default '0', 
   portal_links_height varchar(4) DEFAULT '100' NOT NULL, 
   portal_ourlink tinyint(1) NOT NULL default '0', 
   portal_downloads tinyint(1) NOT NULL default '0', 
   portal_randomuser tinyint(1) NOT NULL default '0', 
   portal_mostpoints tinyint(1) NOT NULL default '0',
   portal_topposters tinyint(1) NOT NULL default '0',  
   portal_newusers tinyint(1) NOT NULL default '0', 
   portal_games tinyint(1) NOT NULL default '0',
   portal_clock tinyint(1) NOT NULL default '0',
   portal_karma tinyint(1) NOT NULL default '0',
   portal_horoscopes tinyint(1) NOT NULL default '0',
   portal_wallpaper tinyint(1) NOT NULL default '0',
   portal_donors tinyint(1) NOT NULL default '0',
   portal_referrers tinyint(1) NOT NULL default '0',
   portal_shoutbox tinyint(1) NOT NULL default '0',
   PRIMARY KEY (portal_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_posts'
#
CREATE TABLE phpbb_posts (
   post_id mediumint(8) UNSIGNED NOT NULL auto_increment,
   topic_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
   forum_id smallint(5) UNSIGNED DEFAULT '0' NOT NULL,
   poster_id mediumint(8) DEFAULT '0' NOT NULL,
   post_time int(11) DEFAULT '0' NOT NULL,
   poster_ip char(8) NOT NULL,
   post_username varchar(25),
   enable_bbcode tinyint(1) DEFAULT '1' NOT NULL,
   enable_html tinyint(1) DEFAULT '0' NOT NULL,
   enable_smilies tinyint(1) DEFAULT '1' NOT NULL,
   enable_sig tinyint(1) DEFAULT '1' NOT NULL,
   post_edit_time int(11),
   post_edit_count smallint(5) UNSIGNED DEFAULT '0' NOT NULL,
   post_attachment TINYINT(1) DEFAULT '0' NOT NULL,
   post_edit_user mediumint(8) default NULL,
   post_icon tinyint(2) UNSIGNED DEFAULT '0' NOT NULL,
   post_bluecard tinyint(1),
   rating_rank_id smallint UNSIGNED NOT NULL,
   user_avatar varchar(100) DEFAULT '' NOT NULL,
   user_avatar_type tinyint NOT NULL,
   urgent_post tinyint(1) default '0' NOT NULL,
   PRIMARY KEY (post_id),
   KEY forum_id (forum_id),
   KEY topic_id (topic_id),
   KEY poster_id (poster_id),
   KEY post_time (post_time),
   KEY posts_ratingrankid (rating_rank_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_posts_edit'
#
CREATE TABLE phpbb_posts_edit (
   post_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL, 
   user_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL, 
   post_edit_count smallint(5) UNSIGNED DEFAULT '0' NOT NULL, 
   post_edit_time INT(11) default NULL, 
   KEY (post_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_posts_ignore_sigav'
#
CREATE TABLE phpbb_posts_ignore_sigav (
   user_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL, 
   hid_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_posts_text'
#
CREATE TABLE phpbb_posts_text (
   post_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
   bbcode_uid char(10) DEFAULT '' NOT NULL,
   post_subject varchar(120),
   post_text text,
   PRIMARY KEY (post_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_privmsgs'
#
CREATE TABLE phpbb_privmsgs (
   privmsgs_id mediumint(8) UNSIGNED NOT NULL auto_increment,
   privmsgs_type tinyint(4) DEFAULT '0' NOT NULL,
   privmsgs_subject varchar(255) DEFAULT '0' NOT NULL,
   privmsgs_from_userid mediumint(8) DEFAULT '0' NOT NULL,
   privmsgs_to_userid mediumint(8) DEFAULT '0' NOT NULL,
   privmsgs_date int(11) DEFAULT '0' NOT NULL,
   privmsgs_ip char(8) NOT NULL,
   privmsgs_enable_bbcode tinyint(1) DEFAULT '1' NOT NULL,
   privmsgs_enable_html tinyint(1) DEFAULT '0' NOT NULL,
   privmsgs_enable_smilies tinyint(1) DEFAULT '1' NOT NULL,
   privmsgs_attach_sig tinyint(1) DEFAULT '1' NOT NULL,
   privmsgs_attachment TINYINT(1) DEFAULT '0' NOT NULL,
   privmsgs_from_username varchar(25) DEFAULT '' NOT NULL,
   privmsgs_to_username varchar(25) DEFAULT '' NOT NULL,
   site_id mediumint(8) DEFAULT '0' NOT NULL,
   room_id mediumint(8) DEFAULT '0' NOT NULL,
   PRIMARY KEY (privmsgs_id),
   KEY privmsgs_from_userid (privmsgs_from_userid),
   KEY privmsgs_to_userid (privmsgs_to_userid),
   KEY room_id (room_id),
   KEY site_id (site_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_privmsgs_text'
#
CREATE TABLE phpbb_privmsgs_text (
   privmsgs_text_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
   privmsgs_bbcode_uid char(10) DEFAULT '' NOT NULL,
   privmsgs_text text,
   PRIMARY KEY (privmsgs_text_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_privmsgs_text'
#
CREATE TABLE phpbb_privmsgs_archive (
   privmsgs_id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
   privmsgs_type tinyint(4) NOT NULL default '0',
   privmsgs_subject varchar(255) NOT NULL default '0',
   privmsgs_from_userid mediumint(8) NOT NULL default '0',
   privmsgs_to_userid mediumint(8) NOT NULL default '0',
   privmsgs_date int(11) NOT NULL default '0',
   privmsgs_ip varchar(8) NOT NULL default '',
   privmsgs_enable_bbcode tinyint(1) NOT NULL default '1',
   privmsgs_enable_html tinyint(1) NOT NULL default '0',
   privmsgs_enable_smilies tinyint(1) NOT NULL default '1',
   privmsgs_attach_sig tinyint(1) NOT NULL default '1',
   PRIMARY KEY (privmsgs_id),
   KEY privmsgs_from_userid (privmsgs_from_userid),
   KEY privmsgs_to_userid (privmsgs_to_userid) 
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_profile_view'
#
CREATE TABLE phpbb_profile_view(
   user_id mediumint(8) NOT NULL,
   viewer_id mediumint(8) NOT NULL,
   view_stamp int(11) NOT NULL, 
   counter mediumint(8) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_quota_limits'
#
CREATE TABLE phpbb_quota_limits (
   quota_limit_id mediumint(8) UNSIGNED NOT NULL auto_increment,
   quota_desc varchar(20) NOT NULL default '',
   quota_limit bigint(20) UNSIGNED NOT NULL default '0',
   PRIMARY KEY (quota_limit_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_ranks'
#
CREATE TABLE phpbb_ranks (
   rank_id smallint(5) UNSIGNED NOT NULL auto_increment,
   rank_title varchar(50) NOT NULL default '',
   rank_min mediumint(8) DEFAULT '0' NOT NULL,
   rank_special tinyint(1) DEFAULT '0',
   rank_image varchar(255),
   rank_group MEDIUMINT(8) NOT NULL,
   PRIMARY KEY (rank_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_rating_config'
#
CREATE TABLE phpbb_rating_config (
   label varchar(100) default NULL,
   num_value int UNSIGNED DEFAULT '0' NOT NULL,
   text_value varchar(255) default NULL,
   config_id int UNSIGNED DEFAULT '0' NOT NULL,
   input_type tinyint UNSIGNED DEFAULT '0' NOT NULL,
   list_order smallint(5) UNSIGNED DEFAULT '0' NOT NULL,
   PRIMARY KEY (config_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_rating_option'
#
CREATE TABLE phpbb_rating_option (
   option_id smallint UNSIGNED NOT NULL auto_increment,
   points tinyint NOT NULL DEFAULT '0',
   label varchar(100) default NULL,
   weighting smallint UNSIGNED NOT NULL DEFAULT '0',
   user_type tinyint UNSIGNED NOT NULL DEFAULT '0',
   PRIMARY KEY (option_id),
   KEY ratingoption_rating (points),
   KEY ratingoption_weighting (weighting)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_rating_rank'
#
CREATE TABLE phpbb_rating_rank (
   rating_rank_id smallint UNSIGNED NOT NULL auto_increment,
   type tinyint UNSIGNED NOT NULL DEFAULT '0',
   average_threshold tinyint NOT NULL DEFAULT '0',
   sum_threshold int NOT NULL DEFAULT '0',
   label varchar(100) default NULL,
   icon varchar(255) default NULL,
   user_rank int UNSIGNED NOT NULL,
   PRIMARY KEY (rating_rank_id),
   KEY ratingrank_type (type)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_rating'
#
CREATE TABLE phpbb_rating (
   post_id int UNSIGNED NOT NULL DEFAULT '0',
   user_id int UNSIGNED NOT NULL DEFAULT '0',
   rating_time int UNSIGNED NOT NULL DEFAULT '0',
   option_id smallint UNSIGNED NOT NULL DEFAULT '0',
   KEY rating_postid (post_id),
   KEY rating_userid (user_id),
   KEY rating_ratingoptionid (option_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_rating_bias'
#
CREATE TABLE phpbb_rating_bias (
  bias_id int unsigned NOT NULL auto_increment,
  user_id int unsigned NOT NULL default '0',
  target_user int unsigned NOT NULL default '0',
  bias_status tinyint unsigned NOT NULL default '0',
  bias_time int unsigned NOT NULL default '0',
  post_id int unsigned NOT NULL default '0',
  option_id smallint unsigned NOT NULL default '0',
  PRIMARY KEY (bias_id),
  KEY ratingbias_userid_targetuser (user_id, target_user),
  KEY ratingbias_targetuser (target_user),
  KEY ratingbias_postid (post_id),
  KEY ratingbias_optionid (option_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_rating_temp'
#
CREATE TABLE phpbb_rating_temp (
  topic_id int unsigned not null, 
  points tinyint not null,
  KEY ratingtemp_topicid(topic_id)
) Engine = Memory;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_referral'
#
CREATE TABLE phpbb_referral (
   referral_id mediumint(8) UNSIGNED NOT NULL auto_increment, 
   ruid varchar(7) DEFAULT '0' NOT NULL, 
   nuid varchar(7) DEFAULT '0' NOT NULL, 
   referral_time varchar(10) DEFAULT '' NOT NULL, 
   KEY referraler_id (referral_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_referers'
#
CREATE TABLE phpbb_referers ( 
    referer_id mediumint(8) UNSIGNED NOT NULL auto_increment,
    referer_host varchar(255) NOT NULL default '', 
    referer_url varchar(255) NOT NULL default '',
    referer_ip varchar(8) NOT NULL default '',
    referer_hits int(10) NOT NULL default '1',
    referer_firstvisit int(11) DEFAULT '0' NOT NULL,
    referer_lastvisit int(11) DEFAULT '0' NOT NULL,
    PRIMARY KEY (referer_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_search_results'
#
CREATE TABLE phpbb_search_results (
  search_id int(11) UNSIGNED NOT NULL default '0',
  session_id char(32) NOT NULL default '',
  search_array mediumtext NOT NULL default '',
  search_time int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (search_id),
  KEY session_id (session_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_search_wordlist'
#
CREATE TABLE phpbb_search_wordlist (
  word_text varchar(50) binary NOT NULL default '',
  word_id mediumint(8) UNSIGNED NOT NULL auto_increment,
  word_common tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY (word_text),
  KEY word_id (word_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_search_wordmatch'
#
CREATE TABLE phpbb_search_wordmatch (
  post_id mediumint(8) UNSIGNED NOT NULL default '0',
  word_id mediumint(8) UNSIGNED NOT NULL default '0',
  title_match tinyint(1) NOT NULL default '0',
  KEY post_id (post_id),
  KEY word_id (word_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_serverload'
#
CREATE TABLE phpbb_serverload (
   time int(14) DEFAULT '0' NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_sessions'
#
# Note that if you're running 3.23.x you may want to make
# this table a type HEAP. This type of table is stored
# within system memory and therefore for big busy boards
# is likely to be noticeably faster than continually
# writing to disk ...
#
CREATE TABLE phpbb_sessions (
   session_id char(32) DEFAULT '' NOT NULL,
   session_user_id mediumint(8) DEFAULT '0' NOT NULL,
   session_start int(11) DEFAULT '0' NOT NULL,
   session_time int(11) DEFAULT '0' NOT NULL,
   session_ip char(8) DEFAULT '0' NOT NULL,
   session_page int(11) DEFAULT '0' NOT NULL,
   session_topic int(11) DEFAULT '0' NOT NULL,
   session_logged_in tinyint(1) DEFAULT '0' NOT NULL,
   session_admin tinyint(2) DEFAULT '0' NOT NULL,
   is_robot varchar(255) DEFAULT '0' NOT NULL,
   PRIMARY KEY (session_id),
   KEY session_user_id (session_user_id),
   KEY session_id_ip_user_id (session_id, session_ip, session_user_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_session_keys'
#
CREATE TABLE phpbb_sessions_keys (
	key_id varchar(32) DEFAULT '0' NOT NULL,
	user_id mediumint(8) DEFAULT '0' NOT NULL,
	last_ip varchar(8) DEFAULT '0' NOT NULL,
	last_login int(11) DEFAULT '0' NOT NULL,
	PRIMARY KEY (key_id, user_id),
	KEY last_login (last_login)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_shops'
#
CREATE TABLE phpbb_shops (
   id int UNSIGNED NOT NULL auto_increment, 
   shopname char(32) NOT NULL, 
   shoptype char(32) NOT NULL, 
   type char(32) NOT NULL, 
   restocktime int(20) UNSIGNED DEFAULT '86400', 
   restockedtime int(20) UNSIGNED DEFAULT '0', 
   restockamount int(4) UNSIGNED DEFAULT '5', 
   amountearnt int(20) UNSIGNED DEFAULT '0', 
   PRIMARY KEY (id), 
   INDEX (shopname)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_shop_items'
#
CREATE TABLE phpbb_shop_items (
   id int UNSIGNED NOT NULL auto_increment, 
   name char(32) NOT NULL, 
   shop char(32) NOT NULL, 
   sdesc char(80) NOT NULL, 
   ldesc text NOT NULL, 
   cost int(20) UNSIGNED DEFAULT '100', 
   stock tinyint(3) UNSIGNED DEFAULT '10', 
   maxstock tinyint(3) UNSIGNED DEFAULT '100', 
   sold int(5) UNSIGNED DEFAULT '0' NOT NULL, 
   accessforum int(4) DEFAULT '0',
   PRIMARY KEY (id), 
   INDEX (name)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_shop_transactions'
#
CREATE TABLE phpbb_shop_transactions (
   shoptrans_date int(11) DEFAULT '0' NOT NULL, 
   trans_user mediumint(8) NOT NULL, 
   trans_item varchar(32) DEFAULT '' NOT NULL, 
   trans_type varchar(255) DEFAULT '' NOT NULL, 
   trans_total mediumint(8) DEFAULT '0' NOT NULL, 
   PRIMARY KEY (shoptrans_date)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_shout'
#
CREATE TABLE phpbb_shout (
   shout_id mediumint(8) UNSIGNED NOT NULL auto_increment, 
   shout_username varchar(25) NOT NULL default '', 
   shout_user_id mediumint(8) NOT NULL, 
   shout_group_id mediumint(8) NOT NULL, 
   shout_session_time int(11) NOT NULL, 
   shout_ip char(8) NOT NULL, 
   shout_text text NOT NULL, 
   shout_active mediumint(8) NOT NULL, 
   enable_bbcode tinyint(1) NOT NULL, 
   enable_html tinyint(1) NOT NULL, 
   enable_smilies tinyint(1) NOT NULL, 
   enable_sig tinyint(1) NOT NULL, 
   shout_bbcode_uid char(10) DEFAULT '' NOT NULL, 
   PRIMARY KEY (shout_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_smilies'
#
CREATE TABLE phpbb_smilies (
   smilies_id smallint(5) UNSIGNED NOT NULL auto_increment,
   cat_id SMALLINT(5) UNSIGNED NOT NULL,
   code varchar(50),
   smile_url varchar(100),
   emoticon varchar(75),
   smilies_order smallint(5) UNSIGNED NOT NULL,
   PRIMARY KEY (smilies_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_smilies_cat'
#
CREATE TABLE phpbb_smilies_cat (
  cat_id smallint(3) unsigned NOT NULL auto_increment,
  cat_name varchar(50) NOT NULL default '',
  description varchar(100) NOT NULL default '',
  cat_order smallint(3) NOT NULL default '0',
  cat_perms tinyint(2) NOT NULL default '10',
  cat_group varchar(255) default NULL,
  cat_forum mediumtext NOT NULL,
  cat_special tinyint(1) NOT NULL default '-2',
  cat_open tinyint(1) NOT NULL default '1',
  cat_icon_url varchar(100) default NULL,
  smilies_popup varchar(20) NOT NULL default '',
  PRIMARY KEY (cat_id)
);


# --------------------------------------------------------
#
# Table structure for table 'phpbb_stats_smilies_index'
#
CREATE TABLE phpbb_stats_smilies_index (
   code varchar(50) default NULL, 
   smile_url varchar(100) default NULL, 
   smile_count mediumint(8) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_stats_smilies_info'
#
CREATE TABLE phpbb_stats_smilies_info (
   last_post_id mediumint(8) DEFAULT '0' NOT NULL, 
   last_update_time int(12) DEFAULT '0' NOT NULL, 
   update_time mediumint(8) DEFAULT '10080' NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_thanks'
#
CREATE TABLE phpbb_thanks (
  topic_id MEDIUMINT(8) NOT NULL,
  user_id MEDIUMINT(8) NOT NULL,
  thanks_time INT(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_themes'
#
CREATE TABLE phpbb_themes (
   themes_id mediumint(8) UNSIGNED NOT NULL,
   template_name varchar(30) NOT NULL default '',
   theme_public tinyint(1) DEFAULT '1' NOT NULL,
   theme_header tinyint(1) DEFAULT '0' NOT NULL,
   theme_footer tinyint(1) DEFAULT '0' NOT NULL,
   style_name varchar(30) NOT NULL default '',
   style_version varchar(6),
   image_cfg varchar(100) NOT NULL default '',
   head_stylesheet varchar(100) default NULL,
   body_background varchar(100) default NULL,
   body_bgcolor varchar(6) default NULL,
   body_text varchar(6) default NULL,
   body_link varchar(6) default NULL,
   body_vlink varchar(6) default NULL,
   body_alink varchar(6) default NULL,
   body_hlink varchar(6) default NULL,
   tr_color1 varchar(6) default NULL,
   tr_color2 varchar(6) default NULL,
   tr_color3 varchar(6) default NULL,
   tr_class1 varchar(25) default NULL,
   tr_class2 varchar(25) default NULL,
   tr_class3 varchar(25) default NULL,
   th_color1 varchar(6) default NULL,
   th_color2 varchar(6) default NULL,
   th_color3 varchar(6) default NULL,
   th_class1 varchar(100) default NULL,
   th_class2 varchar(100) default NULL,
   th_class3 varchar(100) default NULL,
   td_color1 varchar(6) default NULL,
   td_color2 varchar(6) default NULL,
   td_color3 varchar(6) default NULL,
   td_class1 varchar(25) default NULL,
   td_class2 varchar(25) default NULL,
   td_class3 varchar(25) default NULL,
   fontface1 varchar(50) default NULL,
   fontface2 varchar(50) default NULL,
   fontface3 varchar(50) default NULL,
   fontsize1 tinyint(4) default NULL,
   fontsize2 tinyint(4) default NULL,
   fontsize3 tinyint(4) default NULL,
   fontcolor1 varchar(6) default NULL,
   fontcolor2 varchar(6) default NULL,
   fontcolor3 varchar(6) default NULL,
   fontcolor4 varchar(6) default NULL,
   span_class1 varchar(25) default NULL,
   span_class2 varchar(25) default NULL,
   span_class3 varchar(25) default NULL,
   img_size_poll smallint(5) UNSIGNED,
   img_size_privmsg smallint(5) UNSIGNED,
   hr_color1 varchar(6) NOT NULL default 'E8F3FC',
   hr_color2 varchar(6) NOT NULL default 'D5E8F9',
   hr_color3 varchar(6) NOT NULL default 'B7D9F6',
   hr_color4 varchar(6) NOT NULL default 'FCECE8',
   hr_color5 varchar(6) NOT NULL default 'F9DDD5',
   hr_color6 varchar(6) NOT NULL default 'FACCBF',
   hr_color7 varchar(6) NOT NULL default 'E9FAEA',
   hr_color8 varchar(6) NOT NULL default 'D5F9D6',
   hr_color9 varchar(6) NOT NULL default 'B2EEB4',   
   jb_color1 varchar(6) NOT NULL default '006EBB',
   jb_color2 varchar(6) NOT NULL default 'FF6428',
   jb_color3 varchar(6) NOT NULL default '329600',
   adminfontcolor varchar(6) NOT NULL default 'FFA34F',
   adminbold tinyint(1) default '1' NOT NULL,
   supermodfontcolor varchar(6) NOT NULL default '009900',
   supermodbold tinyint(1) default '1' NOT NULL,
   modfontcolor varchar(6) NOT NULL default '006600',
   modbold tinyint(1) default '1' NOT NULL,
   playersfontcolor varchar(6) NOT NULL default '0099CC',
   botfontcolor varchar(6) NOT NULL default '9E8DA7',
   PRIMARY KEY (themes_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_themes_name'
#
CREATE TABLE phpbb_themes_name (
   themes_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
   tr_color1_name char(50),
   tr_color2_name char(50),
   tr_color3_name char(50),
   tr_class1_name char(50),
   tr_class2_name char(50),
   tr_class3_name char(50),
   th_color1_name char(50),
   th_color2_name char(50),
   th_color3_name char(50),
   th_class1_name char(50),
   th_class2_name char(50),
   th_class3_name char(50),
   td_color1_name char(50),
   td_color2_name char(50),
   td_color3_name char(50),
   td_class1_name char(50),
   td_class2_name char(50),
   td_class3_name char(50),
   fontface1_name char(50),
   fontface2_name char(50),
   fontface3_name char(50),
   fontsize1_name char(50),
   fontsize2_name char(50),
   fontsize3_name char(50),
   fontcolor1_name char(50),
   fontcolor2_name char(50),
   fontcolor3_name char(50),
   fontcolor4_name char(50),
   span_class1_name char(50),
   span_class2_name char(50),
   span_class3_name char(50),
   hr_color1_name char(50),
   hr_color2_name char(50),
   hr_color3_name char(50),
   hr_color4_name char(50),
   hr_color5_name char(50),
   hr_color6_name char(50),
   hr_color7_name char(50),
   hr_color8_name char(50),
   hr_color9_name char(50),   
   jb_color1_name char(50),
   jb_color2_name char(50),
   jb_color3_name char(50),
   PRIMARY KEY (themes_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_thread_kicker'
#
CREATE TABLE phpbb_thread_kicker (
  kick_id int(11) NOT NULL auto_increment,
  user_id int(11) NOT NULL default '0',
  topic_id int(11) NOT NULL default '0',
  kicker int(11) NOT NULL default '0',
  post_id int(11) NOT NULL default '0',
  kick_time int(11) NOT NULL default '0',
  kicker_status int(2) NOT NULL default '0',
  kicker_username varchar(25) NOT NULL default '',
  kicked_username varchar(25) NOT NULL default '',
  topic_title varchar(120) NOT NULL default '',
  PRIMARY KEY (kick_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_title_infos'
#
CREATE TABLE phpbb_title_infos (
   id int(11) NOT NULL AUTO_INCREMENT ,
   title_info varchar(255) NOT NULL default '',
   info_color VARCHAR(6) default '' NOT NULL,
   date_format varchar(25),
   title_pos TINYINT(1) DEFAULT '0' NOT NULL,
   admin_auth tinyint(1) DEFAULT '0',
   supermod_auth tinyint(1) DEFAULT '0',
   mod_auth tinyint(1) DEFAULT '0',
   poster_auth tinyint(1) DEFAULT '0',
   UNIQUE (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_topics'
#
CREATE TABLE phpbb_topics (
   topic_id mediumint(8) UNSIGNED NOT NULL auto_increment,
   forum_id smallint(8) UNSIGNED DEFAULT '0' NOT NULL,
   topic_title char(120) NOT NULL default '',
   topic_poster mediumint(8) DEFAULT '0' NOT NULL,
   topic_time int(11) DEFAULT '0' NOT NULL,
   topic_views mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
   topic_replies mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
   topic_status tinyint(3) DEFAULT '0' NOT NULL,
   topic_vote tinyint(1) DEFAULT '0' NOT NULL,
   topic_type tinyint(3) DEFAULT '0' NOT NULL,
   topic_first_post_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
   topic_last_post_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
   answer_status tinyint(1) UNSIGNED DEFAULT '0'  NOT NULL,
   topic_moved_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
   topic_attachment tinyint(1) DEFAULT '0' NOT NULL,
   topic_icon tinyint(2) UNSIGNED DEFAULT '0' NOT NULL,
   rating_rank_id smallint UNSIGNED NOT NULL,
   title_compl_infos varchar(255),
   title_compl_color VARCHAR(6) default '' NOT NULL,
   title_pos TINYINT(1) DEFAULT '0' NOT NULL,
   topic_priority smallint DEFAULT '0' NOT NULL,
   topic_password VARCHAR(20) DEFAULT '' NOT NULL,
   PRIMARY KEY (topic_id),
   KEY forum_id (forum_id),
   KEY topic_moved_id (topic_moved_id),
   KEY topic_status (topic_status),
   KEY topic_type (topic_type),
   KEY topics_ratingrankid (rating_rank_id),
   FULLTEXT KEY topic_title (topic_title)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_topics_viewdata'
#
CREATE TABLE phpbb_topics_viewdata (
  viewed_id int(10) unsigned NOT NULL auto_increment,
  user_id mediumint(8) NOT NULL default '0',
  topic_id mediumint(8) unsigned NOT NULL default '0',
  num_views tinyint(3) unsigned NOT NULL default '1',
  last_viewed int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (viewed_id),
  KEY user_id (user_id, topic_id),
  KEY last_viewed (last_viewed)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_topics_watch'
#
CREATE TABLE phpbb_topics_watch (
  topic_id mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  user_id mediumint(8) NOT NULL DEFAULT '0',
  notify_status tinyint(1) NOT NULL default '0',
  KEY topic_id (topic_id),
  KEY user_id (user_id),
  KEY notify_status (notify_status)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'toplist'
#
CREATE TABLE phpbb_toplist (
   id int(255) NOT NULL auto_increment, 
   nam varchar(255) NOT NULL DEFAULT '', 
   inf varchar(255) NOT NULL DEFAULT '', 
   hin int(255) NOT NULL DEFAULT '0', 
   lin varchar(255) NOT NULL DEFAULT '', 
   `out` int(255) NOT NULL DEFAULT '0', 
   img int(255) NOT NULL DEFAULT '0', 
   ban varchar(255) NOT NULL default 'http://', 
   owner int(255) NOT NULL DEFAULT '0', 
   tot int(255) NOT NULL DEFAULT '0', 
   imgfile varchar(50) NOT NULL default 'button1', 
   ip varchar(8) NOT NULL default '',
   PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# --------------------------------------------------------
#
# Table structure for table 'toplist_anti_flood'
#
CREATE TABLE phpbb_toplist_anti_flood (
   id int(255) NOT NULL, 
   ip varchar(8) NOT NULL default '', 
   time int(11) NOT NULL,
   type varchar(3) NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_transactions'
#
CREATE TABLE phpbb_transactions (
   trans_date int(11) DEFAULT '0' NOT NULL, 
   trans_from varchar(30) DEFAULT '' NOT NULL, 
   trans_to varchar(30) DEFAULT '' NOT NULL, 
   trans_amount mediumint(8) DEFAULT '0' NOT NULL, 
   trans_reason varchar(255) DEFAULT '' NOT NULL,
   PRIMARY KEY (trans_date)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_unique_hits'
#
CREATE TABLE phpbb_unique_hits (
   user_ip char(8) DEFAULT '0' NOT NULL, 
   time int(11) DEFAULT '0' NOT NULL, 
   INDEX (user_ip)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_user_group'
#
CREATE TABLE phpbb_user_group (
   group_id mediumint(8) DEFAULT '0' NOT NULL,
   user_id mediumint(8) DEFAULT '0' NOT NULL,
   user_pending tinyint(1),
   digest_confirm_date int(11) default '0' NOT NULL,
   ug_expire_date int(11) default '0',
   ug_active_date int(11) default '0',
   group_moderator TINYINT(1) NOT NULL,
   KEY group_id (group_id),
   KEY user_id (user_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_user_notes'
#
CREATE TABLE phpbb_user_notes (
   post_id mediumint(8) UNSIGNED NOT NULL auto_increment,
   poster_id mediumint(8) DEFAULT '0' NOT NULL,
   post_subject varchar(120) default NULL,
   post_text text,
   post_time int(11) DEFAULT '0' NOT NULL,
   bbcode_uid char(10) DEFAULT '' NOT NULL,
   bbcode tinyint(1) DEFAULT '1' NOT NULL,
   smilies tinyint(1) DEFAULT '1' NOT NULL,
   acronym tinyint(1) DEFAULT '1' NOT NULL,
   PRIMARY KEY (post_id),
   KEY poster_id (poster_id),
   KEY post_time (post_time)
) ENGINE=MyISAM DEFAULT CHARSET=utf8; 



# --------------------------------------------------------
#
# Table structure for table 'phpbb_user_shops'
#
CREATE TABLE phpbb_user_shops (
  id INT(5) NOT NULL auto_increment,
  user_id mediumint(8) NOT NULL,
  username VARCHAR(32) NOT NULL default '',
  shop_name VARCHAR(32) NOT NULL default '',
  shop_type VARCHAR(32) NOT NULL default '',
  shop_opened INT(30) NOT NULL,
  shop_updated INT(30) NOT NULL,
  shop_status INT(1) DEFAULT '0' NOT NULL,
  amount_earnt INT(10) DEFAULT '0' NOT NULL,
  amount_holding INT(10) DEFAULT '0' NOT NULL,
  items_sold INT(10) DEFAULT '0' NOT NULL,
  items_holding INT(10) UNSIGNED DEFAULT '0' NOT NULL,
  PRIMARY KEY (user_id),
  INDEX (id) 
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_user_shops_items'
#
CREATE TABLE phpbb_user_shops_items (
  id INT(10) NOT NULL auto_increment,
  shop_id INT(10) NOT NULL,
  item_id INT(10) NOT NULL,
  seller_notes VARCHAR(255) NOT NULL default '',
  cost INT(10) NOT NULL,
  time_added MEDIUMINT(30) NOT NULL,
  INDEX (shop_id),
  PRIMARY KEY (id) 
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_users'
#
CREATE TABLE phpbb_users (
   user_id mediumint(8) NOT NULL,
   user_active tinyint(1) DEFAULT '1',
   username varchar(99) NOT NULL default '',
   user_password varchar(32) NOT NULL default '',
   user_session_time int(11) DEFAULT '0' NOT NULL,
   user_session_page smallint(5) DEFAULT '0' NOT NULL,
   user_session_topic int(11) NOT NULL,
   user_lastvisit int(11) DEFAULT '0' NOT NULL,
   user_regdate int(11) DEFAULT '0' NOT NULL,
   user_level tinyint(4) DEFAULT '0',
   user_posts mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
   user_timezone decimal(5,2) DEFAULT '0' NOT NULL,
   user_style tinyint(4),
   user_lang varchar(255),
   user_dateformat varchar(14) DEFAULT 'd M Y H:i' NOT NULL,
   user_new_privmsg smallint(5) UNSIGNED DEFAULT '0' NOT NULL,
   user_unread_privmsg smallint(5) UNSIGNED DEFAULT '0' NOT NULL,
   user_last_privmsg int(11) DEFAULT '0' NOT NULL,
   user_emailtime int(11),
   user_viewemail tinyint(1),
   user_attachsig tinyint(1),
   user_allowhtml tinyint(1) DEFAULT '1',
   user_allowbbcode tinyint(1) DEFAULT '1',
   user_allowsmile tinyint(1) DEFAULT '1',
   user_allowswearywords tinyint(1) NOT NULL default '0',
   user_allowavatar tinyint(1) DEFAULT '1' NOT NULL,
   user_allow_pm tinyint(1) DEFAULT '1' NOT NULL,
   user_allow_mass_pm tinyint(1) DEFAULT '2',
   user_allow_viewonline tinyint(1) DEFAULT '1' NOT NULL,
   user_notify tinyint(1) DEFAULT '1' NOT NULL,
   user_notify_pm tinyint(1) DEFAULT '0' NOT NULL,
   user_notify_pm_text tinyint(1) DEFAULT '0' NOT NULL,
   user_notify_donation tinyint(1) NOT NULL default '0',
   user_popup_pm tinyint(1) DEFAULT '0' NOT NULL,
   user_sound_pm tinyint(1) DEFAULT '0' NOT NULL,
   user_rank int(11) DEFAULT '0',
   user_avatar varchar(100),
   user_avatar_type tinyint(4) DEFAULT '0' NOT NULL,
   user_email varchar(255),
   user_icq varchar(15),
   user_website varchar(100),
   user_from varchar(100),
   user_from_flag varchar(25) default NULL,
   user_sig text,
   user_sig_bbcode_uid char(10) DEFAULT '' NOT NULL,
   user_aim varchar(255),
   user_yim varchar(255),
   user_msnm varchar(255),
   user_xfi varchar(255),
   user_skype varchar(255),
   user_occ varchar(100),
   user_interests varchar(255),
   user_actkey varchar(32),
   user_newpasswd varchar(32),
   user_lastlogon int(11) DEFAULT '0',
   user_totaltime int(11) DEFAULT '0',
   user_totallogon smallint(5) DEFAULT '0',
   user_totalpages int(11) NOT NULL,
   user_birthday mediumint(6) DEFAULT '999999' NOT NULL,
   user_next_birthday_greeting int DEFAULT '0' NOT NULL,
   user_gender tinyint DEFAULT '0' NOT NULL,
   user_photo varchar(100),
   user_photo_type tinyint(4) DEFAULT '0' NOT NULL,
   user_zipcode varchar(10),
   user_points bigint(20) NOT NULL,
   admin_allow_points tinyint(1) DEFAULT '1' NOT NULL,
   user_items text,
   user_effects char(255),
   user_privs char(255),
   user_custitle text,
   user_specmsg text,
   user_trade text,
   user_warnings smallint(5) DEFAULT '0',
   rating_status tinyint UNSIGNED NOT NULL,
   avatar_sticky tinyint NOT NULL default '0',
   user_profile_view smallint(5) DEFAULT '0' NOT NULL,
   user_last_profile_view int(11) DEFAULT '0' NOT NULL,
   user_profile_view_popup tinyint(1) DEFAULT '1',
   user_view_log tinyint DEFAULT '0' NOT NULL,
   user_votewarnings smallint(5) DEFAULT '0' NULL,
   user_allow_profile tinyint(1) DEFAULT '1' NOT NULL,
   user_clockformat varchar(12) DEFAULT 'clock001.swf' NOT NULL,
   user_inactive_emls tinyint(1) NOT NULL default '0',
   user_inactive_last_eml int(11) NOT NULL default '0',
   user_popup_notes tinyint(1) DEFAULT '0',
   user_showsigs tinyint(1) DEFAULT '1',
   irc_commands varchar(255) NOT NULL default '',
   user_showavatars tinyint(1) DEFAULT '1',
   karma_plus mediumint DEFAULT '0' NOT NULL,
   karma_minus mediumint DEFAULT '0' NOT NULL,
   karma_time bigint DEFAULT '0' NOT NULL,
   user_realname varchar(50), 
   user_trophies int(10) NOT NULL DEFAULT '0',
   ina_cheat_fix int(100) NOT NULL DEFAULT '0',
   ina_game_playing int(10) NOT NULL DEFAULT '0',
   ina_last_visit_page varchar(255) NOT NULL DEFAULT '',
   ina_games_today int(10) NOT NULL DEFAULT '0',
   ina_last_playtype varchar(255) DEFAULT 'parent' NOT NULL,
   ina_games_played int(10) DEFAULT '0' NOT NULL,
   user_custom_post_color varchar(6),
   kick_ban int(2) DEFAULT '0' NOT NULL,
   user_info text,
   user_gtalk varchar(255),
   user_stumble varchar(100),
   ina_game_pass int(10) NOT NULL default '0',
   ina_games_pass_day date NOT NULL default '0000-00-00',
   ina_time_playing varchar(20) NOT NULL default '',
   ina_settings varchar(255) NOT NULL default 'info-1;;daily-1;;newest-1;;newest_count-3;;games-1;;games_count-40;;online-1',
   ina_char_name text NOT NULL default '',
   ina_char_age int(10) NOT NULL default '1',
   ina_char_from varchar(255) NOT NULL default '',
   ina_char_intrests varchar(255) NOT NULL default '',
   ina_char_img varchar(255) NOT NULL default '',
   ina_char_gender smallint(1) NOT NULL default '1',
   ina_char_ge int(10) NOT NULL default '0',
   ina_char_name_effects text NOT NULL default '',
   ina_char_title_effects text NOT NULL default '',
   ina_char_saying_effects text NOT NULL default '',
   ina_char_views int(10) NOT NULL default '0',
   ina_char_title varchar(255) NOT NULL default '',
   ina_char_saying varchar(255) NOT NULL default '',
   user_login_tries smallint(5) UNSIGNED DEFAULT '0' NOT NULL,
   user_last_login_try int(11) DEFAULT '0' NOT NULL,
   user_digest_status tinyint(1) default '0' NOT NULL,
   user_actviate_date int(11) default '0',
   user_expire_date int(11) default '0',
   user_jobs int(11) DEFAULT '0' NOT NULL,
   user_ftr smallint(1) DEFAULT '0' NOT NULL,
   user_ftr_time int(11) DEFAULT '0' NOT NULL,
   email_validation tinyint(1) default '0' NOT NULL,
   user_topic_moved_mail TINYINT(1) DEFAULT '0',
   user_topic_moved_pm TINYINT(1) DEFAULT '0',
   user_topic_moved_pm_notify TINYINT(1) DEFAULT '0',
   daily_post_count mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
   daily_post_limit mediumint(8) UNSIGNED NOT NULL,
   daily_post_period int(11) DEFAULT '0' NOT NULL,
   user_wordwrap smallint(3) DEFAULT '80' NOT NULL,
   user_allowsig TINYINT(1) NOT NULL DEFAULT '1',
   group_priority INTEGER(255) NOT NULL,
   user_lastpassword varchar(32) NULL,
   user_lastpassword_time int(11) NULL,
   user_transition TINYINT(1) DEFAULT '1' NOT NULL,
   PRIMARY KEY (user_id),
   KEY user_session_time (user_session_time),
   FULLTEXT KEY user_skype (user_skype)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_users_comments'
#
CREATE TABLE phpbb_users_comments (
   comment_id mediumint(8) auto_increment NOT NULL, 
   user_id mediumint(8) NOT NULL, 
   poster_id mediumint(8) NOT NULL, 
   comments text NOT NULL, 
   time int (11) default NULL,
   PRIMARY KEY (comment_id) 
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_vote_desc'
#
CREATE TABLE phpbb_vote_desc (
  vote_id mediumint(8) UNSIGNED NOT NULL auto_increment,
  topic_id mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  vote_text text NOT NULL default '',
  vote_start int(11) NOT NULL DEFAULT '0',
  vote_length int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY  (vote_id),
  KEY topic_id (topic_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_vote_results'
#
CREATE TABLE phpbb_vote_results (
  vote_id mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  vote_option_id tinyint(4) UNSIGNED NOT NULL DEFAULT '0',
  vote_option_text varchar(255) NOT NULL default '',
  vote_result int(11) NOT NULL DEFAULT '0',
  KEY vote_option_id (vote_option_id),
  KEY vote_id (vote_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_vote_voters'
#
CREATE TABLE phpbb_vote_voters (
  vote_id mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  vote_user_id mediumint(8) NOT NULL DEFAULT '0',
  vote_user_ip char(8) NOT NULL,
  vote_cast tinyint(4) UNSIGNED NOT NULL DEFAULT '0',
  KEY vote_id (vote_id),
  KEY vote_user_id (vote_user_id),
  KEY vote_user_ip (vote_user_ip)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_words'
#
CREATE TABLE phpbb_words (
  word_id mediumint(8) UNSIGNED NOT NULL auto_increment,
  word char(100) NOT NULL,
  replacement char(100) NOT NULL,
  PRIMARY KEY (word_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
#
# Table structure for table 'phpbb_xdata_auth'
#
CREATE TABLE phpbb_xdata_auth (
  field_id smallint(5) UNSIGNED NOT NULL,
  group_id mediumint(8) UNSIGNED NOT NULL,
  auth_value tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

# --------------------------------------------------------
#
# Table structure for table 'phpbb_xdata_data'
#
CREATE TABLE phpbb_xdata_data (
  field_id smallint(5) UNSIGNED NOT NULL,
  user_id mediumint(8) UNSIGNED NOT NULL,
  xdata_value text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

# --------------------------------------------------------
#
# Table structure for table 'phpbb_xdata_fields'
#
CREATE TABLE phpbb_xdata_fields (
  field_id smallint(5) UNSIGNED NOT NULL,
  field_name varchar(255) NOT NULL default '',
  field_desc text NOT NULL default '',
  field_type varchar(255) NOT NULL default '',
  field_order smallint(5) UNSIGNED NOT NULL default '0',
  code_name varchar(255) NOT NULL default '',
  field_length mediumint(8) UNSIGNED NOT NULL default '0',
  field_values text NOT NULL default '',
  field_regexp text NOT NULL default '',
  default_auth tinyint(1) NOT NULL default '1',
  display_register tinyint(1) NOT NULL default '1',
  display_viewprofile tinyint(1) NOT NULL default '0',
  display_posting tinyint(1) NOT NULL default '0',
  handle_input tinyint(1) NOT NULL default '0',
  allow_html tinyint(1) NOT NULL default '0',
  allow_bbcode tinyint(1) NOT NULL default '0',
  allow_smilies tinyint(1) NOT NULL default '0',
  PRIMARY KEY (field_id),
  UNIQUE KEY code_name (code_name)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;