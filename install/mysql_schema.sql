-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 04, 2022 at 05:46 PM
-- Server version: 10.2.44-MariaDB
-- PHP Version: 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `phpbbfm_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_account_hist`
--

CREATE TABLE `phpbb_account_hist` (
  `user_id` mediumint(8) DEFAULT 0,
  `lw_post_id` mediumint(8) DEFAULT 0,
  `lw_money` float DEFAULT 0,
  `lw_plus_minus` smallint(5) DEFAULT 0,
  `MNY_CURRENCY` varchar(8) DEFAULT '',
  `lw_date` int(11) DEFAULT 0,
  `comment` varchar(255) DEFAULT '',
  `status` varchar(64) DEFAULT '',
  `txn_id` varchar(64) DEFAULT '',
  `lw_site` varchar(10) DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_advance_html`
--

CREATE TABLE `phpbb_advance_html` (
  `config_name` varchar(255) NOT NULL DEFAULT '',
  `config_value` longtext NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_album`
--

CREATE TABLE `phpbb_album` (
  `pic_id` int(11) UNSIGNED NOT NULL,
  `pic_filename` varchar(255) NOT NULL DEFAULT '',
  `pic_thumbnail` varchar(255) DEFAULT NULL,
  `pic_title` varchar(255) NOT NULL DEFAULT '',
  `pic_user_id` mediumint(8) NOT NULL,
  `pic_user_ip` char(8) NOT NULL DEFAULT '0',
  `pic_time` int(11) UNSIGNED NOT NULL,
  `pic_cat_id` mediumint(8) UNSIGNED NOT NULL DEFAULT 1,
  `pic_view_count` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `pic_lock` tinyint(3) NOT NULL DEFAULT 0,
  `pic_username` varchar(32) DEFAULT NULL,
  `pic_approval` tinyint(3) NOT NULL DEFAULT 1,
  `pic_desc` text DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_album_cat`
--

CREATE TABLE `phpbb_album_cat` (
  `cat_id` mediumint(8) UNSIGNED NOT NULL,
  `cat_title` varchar(255) NOT NULL DEFAULT '',
  `cat_desc` text DEFAULT NULL,
  `cat_order` mediumint(8) NOT NULL,
  `cat_view_level` tinyint(3) NOT NULL DEFAULT -1,
  `cat_upload_level` tinyint(3) NOT NULL DEFAULT 0,
  `cat_rate_level` tinyint(3) NOT NULL DEFAULT 0,
  `cat_comment_level` tinyint(3) NOT NULL DEFAULT 0,
  `cat_edit_level` tinyint(3) NOT NULL DEFAULT 0,
  `cat_delete_level` tinyint(3) NOT NULL DEFAULT 2,
  `cat_moderator_groups` varchar(255) DEFAULT NULL,
  `cat_approval` tinyint(3) NOT NULL DEFAULT 0,
  `cat_view_groups` varchar(255) DEFAULT NULL,
  `cat_upload_groups` varchar(255) DEFAULT NULL,
  `cat_rate_groups` varchar(255) DEFAULT NULL,
  `cat_comment_groups` varchar(255) DEFAULT NULL,
  `cat_edit_groups` varchar(255) DEFAULT NULL,
  `cat_delete_groups` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_album_comment`
--

CREATE TABLE `phpbb_album_comment` (
  `comment_id` int(11) UNSIGNED NOT NULL,
  `comment_pic_id` int(11) UNSIGNED NOT NULL,
  `comment_cat_id` int(11) NOT NULL,
  `comment_user_id` mediumint(8) NOT NULL,
  `comment_user_ip` char(8) NOT NULL,
  `comment_time` int(11) UNSIGNED NOT NULL,
  `comment_text` text DEFAULT NULL,
  `comment_edit_time` int(11) UNSIGNED DEFAULT NULL,
  `comment_edit_count` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `comment_edit_user_id` mediumint(8) DEFAULT NULL,
  `comment_username` varchar(32) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_album_config`
--

CREATE TABLE `phpbb_album_config` (
  `config_name` varchar(255) NOT NULL DEFAULT '',
  `config_value` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_album_rate`
--

CREATE TABLE `phpbb_album_rate` (
  `rate_pic_id` int(11) UNSIGNED NOT NULL,
  `rate_user_id` mediumint(8) NOT NULL,
  `rate_user_ip` char(8) NOT NULL,
  `rate_point` tinyint(3) UNSIGNED NOT NULL,
  `rate_hon_point` tinyint(3) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_attachments`
--

CREATE TABLE `phpbb_attachments` (
  `attach_id` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `post_id` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `privmsgs_id` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `user_id_1` mediumint(8) NOT NULL,
  `user_id_2` mediumint(8) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_attachments_config`
--

CREATE TABLE `phpbb_attachments_config` (
  `config_name` varchar(255) NOT NULL DEFAULT '',
  `config_value` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_attachments_desc`
--

CREATE TABLE `phpbb_attachments_desc` (
  `attach_id` mediumint(8) UNSIGNED NOT NULL,
  `physical_filename` varchar(255) NOT NULL DEFAULT '',
  `real_filename` varchar(255) NOT NULL DEFAULT '',
  `download_count` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `comment` varchar(255) DEFAULT NULL,
  `extension` varchar(100) DEFAULT NULL,
  `mimetype` varchar(100) DEFAULT NULL,
  `filesize` int(20) NOT NULL,
  `filetime` int(11) NOT NULL DEFAULT 0,
  `thumbnail` tinyint(1) NOT NULL DEFAULT 0,
  `width` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `height` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `border` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_attach_quota`
--

CREATE TABLE `phpbb_attach_quota` (
  `user_id` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `group_id` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `quota_type` smallint(2) NOT NULL DEFAULT 0,
  `quota_limit_id` mediumint(8) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_auth_access`
--

CREATE TABLE `phpbb_auth_access` (
  `group_id` mediumint(8) NOT NULL DEFAULT 0,
  `forum_id` smallint(5) NOT NULL DEFAULT 0,
  `auth_view` tinyint(1) NOT NULL DEFAULT 0,
  `auth_read` tinyint(1) NOT NULL DEFAULT 0,
  `auth_post` tinyint(1) NOT NULL DEFAULT 0,
  `auth_reply` tinyint(1) NOT NULL DEFAULT 0,
  `auth_edit` tinyint(1) NOT NULL DEFAULT 0,
  `auth_delete` tinyint(1) NOT NULL DEFAULT 0,
  `auth_sticky` tinyint(1) NOT NULL DEFAULT 0,
  `auth_announce` tinyint(1) NOT NULL DEFAULT 0,
  `auth_globalannounce` tinyint(1) NOT NULL DEFAULT 0,
  `auth_vote` tinyint(1) NOT NULL DEFAULT 0,
  `auth_pollcreate` tinyint(1) NOT NULL DEFAULT 0,
  `auth_attachments` tinyint(1) NOT NULL DEFAULT 0,
  `auth_mod` tinyint(1) NOT NULL DEFAULT 0,
  `auth_download` tinyint(1) NOT NULL DEFAULT 0,
  `auth_suggest_event` tinyint(2) NOT NULL DEFAULT 0,
  `auth_ban` tinyint(1) NOT NULL DEFAULT 0,
  `auth_voteban` tinyint(1) NOT NULL DEFAULT 0,
  `auth_greencard` tinyint(1) NOT NULL DEFAULT 0,
  `auth_bluecard` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_avatartoplist`
--

CREATE TABLE `phpbb_avatartoplist` (
  `avatar_filename` text NOT NULL,
  `avatar_type` tinyint(4) NOT NULL DEFAULT 0,
  `voter_id` mediumint(8) NOT NULL,
  `voting` mediumint(8) NOT NULL,
  `comment` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_backup`
--

CREATE TABLE `phpbb_backup` (
  `backup_skill` int(1) NOT NULL DEFAULT 1,
  `email_true` varchar(255) NOT NULL DEFAULT '0',
  `email` text NOT NULL DEFAULT 'webmaster@yourdomain.com',
  `ftp_true` int(1) NOT NULL DEFAULT 0,
  `ftp_server` text NOT NULL DEFAULT 'yourftpserver',
  `ftp_user_name` text NOT NULL DEFAULT 'yourftpname',
  `ftp_user_pass` text NOT NULL DEFAULT 'yourftpassword',
  `ftp_directory` text NOT NULL DEFAULT 'yourftpdir',
  `write_backups_true` int(1) NOT NULL DEFAULT 1,
  `files_to_keep` varchar(255) NOT NULL DEFAULT '7',
  `cron_time` text NOT NULL DEFAULT '00 ***',
  `delay_time` text NOT NULL DEFAULT '120',
  `backup_type` text NOT NULL DEFAULT 'full',
  `phpbb_only` int(1) NOT NULL DEFAULT 120,
  `no_search` int(1) NOT NULL DEFAULT 0,
  `ignore_tables` text NOT NULL DEFAULT '0',
  `last_run` int(11) DEFAULT NULL,
  `finished` int(1) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_bank`
--

CREATE TABLE `phpbb_bank` (
  `user_id` mediumint(8) NOT NULL,
  `holding` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `totalwithdrew` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `totaldeposit` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `opentime` int(11) NOT NULL DEFAULT 0,
  `fees` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_banlist`
--

CREATE TABLE `phpbb_banlist` (
  `ban_id` mediumint(8) UNSIGNED NOT NULL,
  `ban_userid` mediumint(8) NOT NULL,
  `user_name` varchar(50) NOT NULL DEFAULT '',
  `reason` varchar(75) NOT NULL DEFAULT '',
  `baned_by` varchar(50) NOT NULL DEFAULT '',
  `ban_ip` char(8) NOT NULL,
  `ban_email` varchar(255) NOT NULL DEFAULT '',
  `ban_time` int(11) DEFAULT NULL,
  `ban_expire_time` int(11) DEFAULT NULL,
  `ban_by_userid` mediumint(8) DEFAULT NULL,
  `ban_priv_reason` text DEFAULT NULL,
  `ban_pub_reason_mode` tinyint(1) DEFAULT NULL,
  `ban_pub_reason` text DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_banned_sites`
--

CREATE TABLE `phpbb_banned_sites` (
  `site_id` int(15) NOT NULL,
  `site_url` varchar(150) NOT NULL,
  `reason` varchar(15) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_banned_visitors`
--

CREATE TABLE `phpbb_banned_visitors` (
  `count` int(15) NOT NULL,
  `refer` varchar(150) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `ip_owner` varchar(100) NOT NULL,
  `browser` varchar(150) NOT NULL,
  `user_id` mediumint(8) NOT NULL,
  `user` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_banner`
--

CREATE TABLE `phpbb_banner` (
  `banner_id` mediumint(8) UNSIGNED NOT NULL,
  `banner_name` text NOT NULL,
  `banner_spot` smallint(1) UNSIGNED NOT NULL,
  `banner_forum` mediumint(8) UNSIGNED NOT NULL,
  `banner_description` varchar(255) NOT NULL DEFAULT '',
  `banner_url` varchar(255) NOT NULL DEFAULT '',
  `banner_owner` mediumint(8) NOT NULL,
  `banner_click` mediumint(8) UNSIGNED NOT NULL,
  `banner_view` mediumint(8) UNSIGNED NOT NULL,
  `banner_weigth` tinyint(1) UNSIGNED NOT NULL DEFAULT 50,
  `banner_active` tinyint(1) NOT NULL,
  `banner_timetype` tinyint(1) NOT NULL,
  `time_begin` int(11) NOT NULL,
  `time_end` int(11) NOT NULL,
  `date_begin` int(11) NOT NULL,
  `date_end` int(11) NOT NULL,
  `banner_level` tinyint(1) NOT NULL,
  `banner_level_type` tinyint(1) NOT NULL,
  `banner_comment` varchar(100) NOT NULL DEFAULT '',
  `banner_type` mediumint(5) NOT NULL,
  `banner_width` varchar(5) NOT NULL DEFAULT '0',
  `banner_height` varchar(5) NOT NULL DEFAULT '0',
  `banner_filter` tinyint(1) NOT NULL,
  `banner_filter_time` mediumint(5) NOT NULL DEFAULT 600
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_banner_stats`
--

CREATE TABLE `phpbb_banner_stats` (
  `banner_id` mediumint(8) UNSIGNED NOT NULL,
  `click_date` int(11) NOT NULL,
  `click_ip` char(8) NOT NULL,
  `click_user` mediumint(8) NOT NULL,
  `user_duration` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_banvote_voters`
--

CREATE TABLE `phpbb_banvote_voters` (
  `banvote_user_id` mediumint(8) NOT NULL DEFAULT 0,
  `banvote_banner_id` mediumint(8) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_bookie_admin_bets`
--

CREATE TABLE `phpbb_bookie_admin_bets` (
  `bet_id` int(11) NOT NULL,
  `bet_cat` int(3) NOT NULL DEFAULT 1,
  `bet_time` int(11) NOT NULL DEFAULT 0,
  `bet_selection` varchar(100) NOT NULL DEFAULT '',
  `bet_meeting` varchar(50) NOT NULL DEFAULT '',
  `odds_1` int(11) NOT NULL DEFAULT 0,
  `odds_2` int(11) NOT NULL DEFAULT 0,
  `checked` int(2) NOT NULL DEFAULT 0,
  `multi` int(11) NOT NULL DEFAULT -1,
  `starbet` int(2) NOT NULL DEFAULT 0,
  `each_way` int(2) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_bookie_bets`
--

CREATE TABLE `phpbb_bookie_bets` (
  `bet_id` int(5) NOT NULL,
  `bet_cat` int(3) NOT NULL DEFAULT 1,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `time` int(11) NOT NULL DEFAULT 0,
  `meeting` varchar(50) NOT NULL DEFAULT '',
  `selection` varchar(100) NOT NULL DEFAULT '',
  `bet` int(11) NOT NULL DEFAULT 0,
  `win_lose` int(11) NOT NULL DEFAULT 0,
  `odds_1` int(8) NOT NULL DEFAULT 0,
  `odds_2` int(8) NOT NULL DEFAULT 0,
  `checked` int(2) NOT NULL DEFAULT 0,
  `edit_time` int(11) NOT NULL DEFAULT 0,
  `orig_time` int(11) NOT NULL DEFAULT 0,
  `admin_betid` int(11) NOT NULL DEFAULT 0,
  `each_way` int(2) NOT NULL DEFAULT 0,
  `bet_result` int(2) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_bookie_bet_setter`
--

CREATE TABLE `phpbb_bookie_bet_setter` (
  `setter` int(11) NOT NULL DEFAULT 0,
  `bet_id` int(11) NOT NULL DEFAULT 0,
  `commission` int(11) NOT NULL DEFAULT 0,
  `paid` int(2) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_bookie_categories`
--

CREATE TABLE `phpbb_bookie_categories` (
  `cat_id` int(11) NOT NULL,
  `cat_name` varchar(30) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_bookie_meetings`
--

CREATE TABLE `phpbb_bookie_meetings` (
  `meeting_id` int(5) NOT NULL,
  `meeting` varchar(50) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_bookie_selections`
--

CREATE TABLE `phpbb_bookie_selections` (
  `selection_id` int(11) NOT NULL,
  `selection_name` varchar(25) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_bookie_selections_data`
--

CREATE TABLE `phpbb_bookie_selections_data` (
  `selection_id` int(11) NOT NULL DEFAULT 0,
  `selection` varchar(100) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_bookie_stats`
--

CREATE TABLE `phpbb_bookie_stats` (
  `user_id` int(11) NOT NULL DEFAULT 0,
  `total_win` int(11) NOT NULL DEFAULT 0,
  `total_lose` int(11) NOT NULL DEFAULT 0,
  `netpos` int(11) NOT NULL DEFAULT 0,
  `bets_placed` int(6) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_bots`
--

CREATE TABLE `phpbb_bots` (
  `bot_id` mediumint(8) UNSIGNED NOT NULL,
  `bot_name` varchar(255) NOT NULL DEFAULT '',
  `bot_agent` varchar(255) NOT NULL DEFAULT '',
  `last_visit` varchar(255) NOT NULL DEFAULT '',
  `bot_visits` mediumint(8) DEFAULT NULL,
  `bot_pages` mediumint(8) NOT NULL DEFAULT 0,
  `pending_agent` text NOT NULL DEFAULT '',
  `pending_ip` text NOT NULL DEFAULT '',
  `bot_ip` text NOT NULL DEFAULT '',
  `bot_style` mediumint(8) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_bots_archive`
--

CREATE TABLE `phpbb_bots_archive` (
  `bot_id` mediumint(8) NOT NULL,
  `bot_name` varchar(255) DEFAULT NULL,
  `bot_time` int(11) NOT NULL DEFAULT 0,
  `bot_url` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_categories`
--

CREATE TABLE `phpbb_categories` (
  `cat_id` mediumint(8) UNSIGNED NOT NULL,
  `cat_title` varchar(100) DEFAULT NULL,
  `cat_order` mediumint(8) UNSIGNED NOT NULL,
  `cat_sponsor_img` varchar(255) NOT NULL DEFAULT '',
  `cat_sponsor_alt` varchar(255) NOT NULL DEFAULT '',
  `cat_sponsor_url` varchar(255) NOT NULL DEFAULT '',
  `parent_forum_id` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `cat_hier_level` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `cat_icon` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_cat_rel_cat_parents`
--

CREATE TABLE `phpbb_cat_rel_cat_parents` (
  `cat_id` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `parent_cat_id` mediumint(8) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_cat_rel_forum_parents`
--

CREATE TABLE `phpbb_cat_rel_forum_parents` (
  `cat_id` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `parent_forum_id` smallint(5) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_charts`
--

CREATE TABLE `phpbb_charts` (
  `chart_id` mediumint(8) NOT NULL,
  `chart_song_name` varchar(100) NOT NULL DEFAULT '',
  `chart_artist` varchar(100) NOT NULL DEFAULT '',
  `chart_album` varchar(100) DEFAULT '',
  `chart_label` varchar(100) NOT NULL DEFAULT '',
  `chart_catno` varchar(50) NOT NULL DEFAULT '',
  `chart_website` varchar(100) NOT NULL DEFAULT '',
  `chart_poster_id` varchar(100) NOT NULL DEFAULT '',
  `chart_hot` mediumint(8) DEFAULT 0,
  `chart_not` mediumint(8) DEFAULT 0,
  `chart_curr_pos` mediumint(8) DEFAULT 0,
  `chart_last_pos` mediumint(8) DEFAULT 0,
  `chart_best_pos` mediumint(8) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_charts_voters`
--

CREATE TABLE `phpbb_charts_voters` (
  `vote_id` mediumint(8) NOT NULL,
  `vote_user_id` mediumint(8) NOT NULL,
  `vote_chart_id` mediumint(8) NOT NULL,
  `vote_rate` smallint(2) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_chatbox`
--

CREATE TABLE `phpbb_chatbox` (
  `id` int(11) NOT NULL,
  `name` varchar(99) NOT NULL DEFAULT '',
  `msg` varchar(255) NOT NULL DEFAULT '',
  `timestamp` int(10) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_chatbox_session`
--

CREATE TABLE `phpbb_chatbox_session` (
  `username` varchar(99) NOT NULL DEFAULT '',
  `lastactive` int(10) NOT NULL DEFAULT 0,
  `laststatus` varchar(8) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_config`
--

CREATE TABLE `phpbb_config` (
  `config_name` varchar(255) NOT NULL DEFAULT '',
  `config_value` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_config_nav`
--

CREATE TABLE `phpbb_config_nav` (
  `navlink_id` mediumint(8) UNSIGNED NOT NULL,
  `img` varchar(100) NOT NULL DEFAULT '',
  `alt` varchar(100) NOT NULL DEFAULT '',
  `use_lang` tinyint(1) NOT NULL DEFAULT 1,
  `url` varchar(255) NOT NULL DEFAULT '',
  `nav_order` mediumint(8) NOT NULL DEFAULT 1,
  `value` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_confirm`
--

CREATE TABLE `phpbb_confirm` (
  `confirm_id` char(32) NOT NULL DEFAULT '',
  `session_id` char(32) NOT NULL DEFAULT '',
  `code` char(6) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_digests`
--

CREATE TABLE `phpbb_digests` (
  `digest_id` int(6) NOT NULL,
  `digest_name` varchar(25) DEFAULT '',
  `user_id` mediumint(8) NOT NULL DEFAULT 0,
  `digest_type` tinyint(1) NOT NULL DEFAULT 0,
  `digest_activity` tinyint(1) NOT NULL DEFAULT 1,
  `digest_frequency` mediumint(8) NOT NULL DEFAULT 0,
  `last_digest` int(11) NOT NULL DEFAULT 0,
  `digest_format` smallint(4) NOT NULL DEFAULT 0,
  `digest_show_text` smallint(4) NOT NULL DEFAULT 0,
  `digest_show_mine` smallint(4) NOT NULL DEFAULT 0,
  `digest_new_only` smallint(4) NOT NULL DEFAULT 0,
  `digest_send_on_no_messages` smallint(4) NOT NULL DEFAULT 1,
  `digest_moderator` tinyint(1) NOT NULL DEFAULT 0,
  `digest_include_forum` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_digests_config`
--

CREATE TABLE `phpbb_digests_config` (
  `config_name` varchar(255) NOT NULL DEFAULT '',
  `config_value` varchar(255) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_digests_forums`
--

CREATE TABLE `phpbb_digests_forums` (
  `user_id` mediumint(8) NOT NULL DEFAULT 0,
  `forum_id` smallint(5) NOT NULL DEFAULT 0,
  `digest_id` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_digests_log`
--

CREATE TABLE `phpbb_digests_log` (
  `log_time` int(11) NOT NULL DEFAULT 0,
  `run_type` tinyint(1) NOT NULL DEFAULT 0,
  `user_id` mediumint(8) NOT NULL DEFAULT 0,
  `digest_frequency` mediumint(8) NOT NULL DEFAULT 0,
  `digest_type` tinyint(1) NOT NULL DEFAULT 0,
  `group_id` mediumint(8) NOT NULL DEFAULT 1,
  `log_status` mediumint(2) NOT NULL DEFAULT 0,
  `log_posts` int(11) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_disallow`
--

CREATE TABLE `phpbb_disallow` (
  `disallow_id` mediumint(8) UNSIGNED NOT NULL,
  `disallow_username` varchar(25) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_extensions`
--

CREATE TABLE `phpbb_extensions` (
  `ext_id` mediumint(8) UNSIGNED NOT NULL,
  `group_id` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `extension` varchar(100) NOT NULL DEFAULT '',
  `comment` varchar(100) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_extension_groups`
--

CREATE TABLE `phpbb_extension_groups` (
  `group_id` mediumint(8) NOT NULL,
  `group_name` char(20) NOT NULL,
  `cat_id` tinyint(2) NOT NULL DEFAULT 0,
  `allow_group` tinyint(1) NOT NULL DEFAULT 0,
  `download_mode` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
  `upload_icon` varchar(100) DEFAULT '',
  `max_filesize` int(20) NOT NULL DEFAULT 0,
  `forum_permissions` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_flags`
--

CREATE TABLE `phpbb_flags` (
  `flag_id` int(10) NOT NULL,
  `flag_name` varchar(25) DEFAULT NULL,
  `flag_image` varchar(25) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_forbidden_extensions`
--

CREATE TABLE `phpbb_forbidden_extensions` (
  `ext_id` mediumint(8) UNSIGNED NOT NULL,
  `extension` varchar(100) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_forums`
--

CREATE TABLE `phpbb_forums` (
  `forum_id` smallint(5) NOT NULL,
  `cat_id` mediumint(8) DEFAULT 0,
  `forum_name` varchar(150) DEFAULT NULL,
  `forum_desc` text DEFAULT NULL,
  `forum_status` tinyint(4) NOT NULL DEFAULT 0,
  `forum_order` mediumint(8) UNSIGNED NOT NULL DEFAULT 1,
  `forum_posts` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `forum_topics` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `forum_views` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `forum_last_post_id` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `forum_hier_level` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `forum_issub` tinyint(1) NOT NULL DEFAULT 0,
  `prune_next` int(11) DEFAULT NULL,
  `prune_enable` tinyint(1) NOT NULL DEFAULT 0,
  `move_next` int(11) DEFAULT NULL,
  `move_enable` tinyint(1) NOT NULL DEFAULT 0,
  `points_disabled` tinyint(1) NOT NULL DEFAULT 0,
  `auth_view` tinyint(2) NOT NULL DEFAULT 0,
  `auth_read` tinyint(2) NOT NULL DEFAULT 0,
  `auth_post` tinyint(2) NOT NULL DEFAULT 0,
  `auth_reply` tinyint(2) NOT NULL DEFAULT 0,
  `auth_edit` tinyint(2) NOT NULL DEFAULT 0,
  `auth_delete` tinyint(2) NOT NULL DEFAULT 0,
  `auth_sticky` tinyint(2) NOT NULL DEFAULT 0,
  `auth_announce` tinyint(2) NOT NULL DEFAULT 0,
  `auth_globalannounce` tinyint(2) NOT NULL DEFAULT 3,
  `auth_vote` tinyint(2) NOT NULL DEFAULT 0,
  `auth_pollcreate` tinyint(2) NOT NULL DEFAULT 0,
  `auth_attachments` tinyint(2) NOT NULL DEFAULT 0,
  `auth_download` tinyint(2) NOT NULL DEFAULT 0,
  `auth_suggest_event` tinyint(2) NOT NULL DEFAULT 0,
  `is_default` tinyint(2) NOT NULL DEFAULT 0,
  `events_forum` tinyint(1) DEFAULT 0,
  `auth_ban` tinyint(2) DEFAULT 3,
  `auth_voteban` tinyint(2) NOT NULL DEFAULT 1,
  `auth_greencard` tinyint(2) DEFAULT 5,
  `auth_bluecard` tinyint(2) DEFAULT 1,
  `forum_icon` varchar(255) NOT NULL DEFAULT '',
  `icon_enable` tinyint(1) NOT NULL DEFAULT 0,
  `answered_enable` tinyint(1) NOT NULL DEFAULT 0,
  `forum_external` tinyint(1) NOT NULL DEFAULT 0,
  `forum_redirect_url` text DEFAULT NULL,
  `forum_redirects_user` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `forum_redirects_guest` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `forum_ext_newwin` tinyint(1) NOT NULL DEFAULT 0,
  `forum_ext_image` text DEFAULT NULL,
  `image_ever_thumb` tinyint(1) NOT NULL DEFAULT 0,
  `forum_enter_limit` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `forum_rules` text DEFAULT NULL,
  `amazon_display` tinyint(1) DEFAULT NULL,
  `forum_thank` tinyint(1) NOT NULL DEFAULT 0,
  `forum_sort` text NOT NULL DEFAULT '',
  `forum_digest` tinyint(1) NOT NULL DEFAULT 1,
  `forum_password` varchar(20) NOT NULL DEFAULT '',
  `hide_forum_on_index` tinyint(1) NOT NULL DEFAULT 0,
  `hide_forum_in_cat` tinyint(1) NOT NULL DEFAULT 0,
  `display_moderators` tinyint(1) NOT NULL DEFAULT 1,
  `index_posts` tinyint(1) NOT NULL DEFAULT 1,
  `forum_template` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `stop_bumping` tinyint(1) NOT NULL DEFAULT 0,
  `forum_toggle` tinyint(1) NOT NULL DEFAULT 0,
  `index_lasttitle` tinyint(1) NOT NULL DEFAULT 0,
  `display_pic_alert` tinyint(1) NOT NULL DEFAULT 0,
  `forum_regdate_limit` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `forum_subject_check` tinyint(1) DEFAULT 0,
  `topic_password` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_forums_descrip`
--

CREATE TABLE `phpbb_forums_descrip` (
  `forum_id` int(11) DEFAULT 0,
  `user_id` int(11) DEFAULT 0,
  `view` tinyint(4) DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_forums_watch`
--

CREATE TABLE `phpbb_forums_watch` (
  `forum_id` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `user_id` mediumint(8) NOT NULL DEFAULT 0,
  `notify_status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_forum_move`
--

CREATE TABLE `phpbb_forum_move` (
  `move_id` mediumint(8) NOT NULL,
  `forum_id` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `forum_dest` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `move_days` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `move_freq` smallint(5) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_forum_prune`
--

CREATE TABLE `phpbb_forum_prune` (
  `prune_id` mediumint(8) UNSIGNED NOT NULL,
  `forum_id` smallint(5) UNSIGNED NOT NULL,
  `prune_days` smallint(5) UNSIGNED NOT NULL,
  `prune_freq` smallint(5) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_forum_tour`
--

CREATE TABLE `phpbb_forum_tour` (
  `page_id` mediumint(8) UNSIGNED NOT NULL,
  `page_subject` varchar(60) DEFAULT NULL,
  `page_text` text DEFAULT NULL,
  `page_sort` mediumint(8) NOT NULL,
  `bbcode_uid` char(10) NOT NULL DEFAULT '',
  `page_access` mediumint(8) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_groups`
--

CREATE TABLE `phpbb_groups` (
  `group_id` mediumint(8) NOT NULL,
  `group_type` tinyint(4) NOT NULL DEFAULT 1,
  `group_name` varchar(40) NOT NULL DEFAULT '',
  `group_description` text NOT NULL DEFAULT '',
  `group_moderator` mediumint(8) NOT NULL DEFAULT 0,
  `group_single_user` tinyint(1) NOT NULL DEFAULT 1,
  `group_allow_pm` tinyint(2) NOT NULL DEFAULT 5,
  `group_digest` tinyint(1) NOT NULL DEFAULT 0,
  `group_amount` float DEFAULT 0,
  `group_period` int(11) DEFAULT 1,
  `group_period_basis` varchar(10) DEFAULT 'M',
  `group_first_trial_fee` float DEFAULT 0,
  `group_first_trial_period` int(11) DEFAULT 0,
  `group_first_trial_period_basis` varchar(10) DEFAULT '0',
  `group_second_trial_fee` float DEFAULT 0,
  `group_second_trial_period` int(11) DEFAULT 0,
  `group_second_trial_period_basis` varchar(10) DEFAULT '0',
  `group_sub_recurring` int(11) DEFAULT 1,
  `group_sub_recurring_stop` int(11) DEFAULT 0,
  `group_sub_recurring_stop_num` int(11) DEFAULT 0,
  `group_sub_reattempt` int(11) DEFAULT 1,
  `allow_create_meeting` tinyint(1) NOT NULL DEFAULT 0,
  `group_members_count` tinyint(1) NOT NULL DEFAULT 0,
  `group_colored` text NOT NULL DEFAULT '',
  `group_colors` text NOT NULL DEFAULT '',
  `group_order` int(255) NOT NULL DEFAULT 0,
  `group_rank_order` mediumint(8) NOT NULL DEFAULT 0,
  `group_validate` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_guestbook`
--

CREATE TABLE `phpbb_guestbook` (
  `id` mediumint(8) UNSIGNED NOT NULL,
  `user_id` mediumint(8) NOT NULL DEFAULT 0,
  `nick` varchar(25) NOT NULL DEFAULT '',
  `data_ora` int(11) NOT NULL DEFAULT 0,
  `email` varchar(255) NOT NULL DEFAULT '',
  `sito` varchar(100) DEFAULT NULL,
  `comento` text DEFAULT NULL,
  `bbcode_uid` varchar(10) DEFAULT NULL,
  `ipi` varchar(8) NOT NULL DEFAULT '',
  `agent` varchar(255) NOT NULL DEFAULT '',
  `hide` tinyint(1) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_guestbook_config`
--

CREATE TABLE `phpbb_guestbook_config` (
  `config_name` varchar(255) NOT NULL DEFAULT '',
  `config_value` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_helpdesk_emails`
--

CREATE TABLE `phpbb_helpdesk_emails` (
  `e_id` int(10) NOT NULL DEFAULT 0,
  `e_addr` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_helpdesk_importance`
--

CREATE TABLE `phpbb_helpdesk_importance` (
  `value` int(10) DEFAULT 0,
  `data` text DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_helpdesk_msgs`
--

CREATE TABLE `phpbb_helpdesk_msgs` (
  `e_id` int(10) DEFAULT 0,
  `e_msg` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_helpdesk_reply`
--

CREATE TABLE `phpbb_helpdesk_reply` (
  `value` int(10) DEFAULT 0,
  `data` text DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_im_buddy_list`
--

CREATE TABLE `phpbb_im_buddy_list` (
  `user_id` mediumint(8) NOT NULL DEFAULT 0,
  `contact_id` mediumint(8) NOT NULL DEFAULT 0,
  `user_ignore` tinyint(1) NOT NULL DEFAULT 0,
  `alert` tinyint(1) NOT NULL DEFAULT 0,
  `alert_status` tinyint(1) NOT NULL DEFAULT 0,
  `disallow` tinyint(1) NOT NULL DEFAULT 0,
  `display_name` varchar(25) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_im_config`
--

CREATE TABLE `phpbb_im_config` (
  `config_name` varchar(255) NOT NULL DEFAULT '',
  `config_value` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_im_prefs`
--

CREATE TABLE `phpbb_im_prefs` (
  `user_id` mediumint(8) NOT NULL DEFAULT 0,
  `user_allow_ims` tinyint(1) NOT NULL DEFAULT 1,
  `user_allow_shout` tinyint(1) NOT NULL DEFAULT 1,
  `user_allow_chat` tinyint(1) NOT NULL DEFAULT 1,
  `user_allow_network` tinyint(1) NOT NULL DEFAULT 1,
  `admin_allow_ims` tinyint(1) NOT NULL DEFAULT 1,
  `admin_allow_shout` tinyint(1) NOT NULL DEFAULT 1,
  `admin_allow_chat` tinyint(1) NOT NULL DEFAULT 1,
  `admin_allow_network` tinyint(1) NOT NULL DEFAULT 1,
  `new_ims` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `unread_ims` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `read_ims` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `total_sent` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `attach_sig` tinyint(1) NOT NULL DEFAULT 0,
  `refresh_rate` smallint(5) UNSIGNED NOT NULL DEFAULT 60,
  `success_close` tinyint(1) NOT NULL DEFAULT 1,
  `refresh_method` tinyint(1) NOT NULL DEFAULT 2,
  `auto_launch` tinyint(1) NOT NULL DEFAULT 1,
  `popup_ims` tinyint(1) NOT NULL DEFAULT 1,
  `list_ims` tinyint(1) NOT NULL DEFAULT 0,
  `show_controls` tinyint(1) NOT NULL DEFAULT 1,
  `list_all_online` tinyint(1) NOT NULL DEFAULT 1,
  `default_mode` tinyint(1) NOT NULL DEFAULT 1,
  `current_mode` tinyint(1) NOT NULL DEFAULT 1,
  `mode1_height` varchar(4) NOT NULL DEFAULT '400',
  `mode1_width` varchar(4) NOT NULL DEFAULT '225',
  `mode2_height` varchar(4) NOT NULL DEFAULT '230',
  `mode2_width` varchar(4) NOT NULL DEFAULT '400',
  `mode3_height` varchar(4) NOT NULL DEFAULT '100',
  `mode3_width` varchar(4) NOT NULL DEFAULT '400',
  `prefs_width` varchar(4) NOT NULL DEFAULT '500',
  `prefs_height` varchar(4) NOT NULL DEFAULT '350',
  `read_height` varchar(4) NOT NULL DEFAULT '300',
  `read_width` varchar(4) NOT NULL DEFAULT '400',
  `send_height` varchar(4) NOT NULL DEFAULT '365',
  `send_width` varchar(4) NOT NULL DEFAULT '460',
  `play_sound` tinyint(1) NOT NULL DEFAULT 1,
  `default_sound` tinyint(1) NOT NULL DEFAULT 1,
  `sound_name` varchar(255) DEFAULT NULL,
  `themes_id` mediumint(8) UNSIGNED NOT NULL DEFAULT 1,
  `network_user_list` tinyint(1) NOT NULL DEFAULT 1,
  `open_pms` tinyint(1) NOT NULL DEFAULT 0,
  `auto_delete` tinyint(1) NOT NULL DEFAULT 0,
  `use_frames` tinyint(1) NOT NULL DEFAULT 1,
  `user_override` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_im_sessions`
--

CREATE TABLE `phpbb_im_sessions` (
  `session_user_id` mediumint(8) NOT NULL DEFAULT 0,
  `session_id` char(32) NOT NULL DEFAULT '',
  `session_time` int(11) NOT NULL DEFAULT 0,
  `session_popup` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_im_sites`
--

CREATE TABLE `phpbb_im_sites` (
  `site_id` mediumint(8) NOT NULL,
  `site_name` varchar(50) NOT NULL DEFAULT '',
  `site_url` varchar(100) NOT NULL DEFAULT '',
  `site_phpex` varchar(4) NOT NULL DEFAULT 'php',
  `site_profile` varchar(50) NOT NULL DEFAULT 'profile',
  `site_enable` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_ina_ban`
--

CREATE TABLE `phpbb_ina_ban` (
  `id` int(10) NOT NULL DEFAULT 0,
  `username` varchar(40) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_ina_categories`
--

CREATE TABLE `phpbb_ina_categories` (
  `cat_id` mediumint(9) NOT NULL,
  `cat_name` varchar(25) DEFAULT NULL,
  `cat_desc` text NOT NULL,
  `cat_img` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_ina_challenge_tracker`
--

CREATE TABLE `phpbb_ina_challenge_tracker` (
  `user` int(10) DEFAULT 0,
  `count` int(25) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_ina_challenge_users`
--

CREATE TABLE `phpbb_ina_challenge_users` (
  `user_to` int(10) DEFAULT 0,
  `user_from` int(10) DEFAULT 0,
  `count` int(25) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_ina_chat`
--

CREATE TABLE `phpbb_ina_chat` (
  `chat_date` date NOT NULL DEFAULT '0000-00-00',
  `chat_text` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_ina_cheat_fix`
--

CREATE TABLE `phpbb_ina_cheat_fix` (
  `game_id` int(10) NOT NULL DEFAULT 0,
  `player` int(10) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_ina_data`
--

CREATE TABLE `phpbb_ina_data` (
  `version` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_ina_favorites`
--

CREATE TABLE `phpbb_ina_favorites` (
  `user` int(10) NOT NULL DEFAULT 0,
  `games` text DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_ina_gamble`
--

CREATE TABLE `phpbb_ina_gamble` (
  `game_id` int(20) DEFAULT 0,
  `sender_id` int(11) DEFAULT 0,
  `reciever_id` int(11) DEFAULT 0,
  `amount` int(10) DEFAULT 0,
  `winner_id` int(11) DEFAULT 0,
  `loser_id` int(11) DEFAULT 0,
  `winner_score` int(11) DEFAULT 0,
  `loser_score` int(11) DEFAULT 0,
  `date` int(20) DEFAULT NULL,
  `been_paid` int(11) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_ina_gamble_in_progress`
--

CREATE TABLE `phpbb_ina_gamble_in_progress` (
  `game_id` int(20) DEFAULT 0,
  `sender_id` int(11) DEFAULT 0,
  `reciever_id` int(11) DEFAULT 0,
  `sender_score` int(20) DEFAULT 0,
  `reciever_score` int(20) DEFAULT 0,
  `sender_playing` int(1) NOT NULL DEFAULT 0,
  `reciever_playing` int(1) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_ina_games`
--

CREATE TABLE `phpbb_ina_games` (
  `game_id` mediumint(9) NOT NULL,
  `game_name` varchar(25) DEFAULT NULL,
  `game_path` varchar(255) DEFAULT NULL,
  `game_desc` varchar(255) DEFAULT NULL,
  `game_charge` int(11) UNSIGNED DEFAULT 0,
  `game_reward` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `game_bonus` smallint(5) UNSIGNED DEFAULT 0,
  `game_use_gl` tinyint(3) UNSIGNED DEFAULT 0,
  `game_flash` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `game_show_score` tinyint(1) NOT NULL DEFAULT 1,
  `win_width` smallint(6) NOT NULL DEFAULT 0,
  `win_height` smallint(6) NOT NULL DEFAULT 0,
  `highscore_limit` varchar(255) DEFAULT NULL,
  `reverse_list` tinyint(1) NOT NULL DEFAULT 0,
  `played` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `instructions` text DEFAULT NULL,
  `disabled` int(1) NOT NULL DEFAULT 1,
  `install_date` int(20) NOT NULL DEFAULT 0,
  `proper_name` text NOT NULL DEFAULT '',
  `cat_id` int(4) NOT NULL DEFAULT 0,
  `jackpot` int(10) NOT NULL DEFAULT 0,
  `game_popup` smallint(1) NOT NULL DEFAULT 1,
  `game_parent` smallint(1) NOT NULL DEFAULT 1,
  `game_type` smallint(1) NOT NULL DEFAULT 1,
  `game_links` smallint(1) NOT NULL DEFAULT 0,
  `game_ge_cost` int(10) NOT NULL DEFAULT 0,
  `game_keyboard` smallint(1) NOT NULL DEFAULT 0,
  `game_mouse` smallint(1) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_ina_hall_of_fame`
--

CREATE TABLE `phpbb_ina_hall_of_fame` (
  `game_id` mediumint(8) NOT NULL DEFAULT 0,
  `current_user_id` mediumint(8) NOT NULL DEFAULT 0,
  `current_score` float(10,2) NOT NULL DEFAULT 0.00,
  `date_today` int(10) NOT NULL DEFAULT 0,
  `old_user_id` mediumint(8) NOT NULL DEFAULT 0,
  `old_score` float(10,2) NOT NULL DEFAULT 0.00,
  `old_date` int(10) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_ina_last_game_played`
--

CREATE TABLE `phpbb_ina_last_game_played` (
  `game_id` int(20) DEFAULT 0,
  `user_id` mediumint(8) NOT NULL,
  `date` int(20) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_ina_rating_votes`
--

CREATE TABLE `phpbb_ina_rating_votes` (
  `game_id` int(15) NOT NULL DEFAULT 0,
  `rating` int(15) NOT NULL DEFAULT 0,
  `date` int(15) NOT NULL DEFAULT 0,
  `player` int(15) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_ina_scores`
--

CREATE TABLE `phpbb_ina_scores` (
  `game_name` varchar(255) DEFAULT NULL,
  `player` varchar(40) DEFAULT NULL,
  `score` float(10,2) NOT NULL DEFAULT 0.00,
  `date` int(11) DEFAULT NULL,
  `user_plays` int(6) NOT NULL DEFAULT 0,
  `play_time` int(10) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_ina_sessions`
--

CREATE TABLE `phpbb_ina_sessions` (
  `playing_time` int(15) NOT NULL DEFAULT 0,
  `playing_id` int(10) NOT NULL DEFAULT 0,
  `playing` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_ina_top_scores`
--

CREATE TABLE `phpbb_ina_top_scores` (
  `game_name` varchar(255) DEFAULT NULL,
  `player` mediumint(8) NOT NULL,
  `score` float(10,2) NOT NULL DEFAULT 0.00,
  `date` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_ina_trophy_comments`
--

CREATE TABLE `phpbb_ina_trophy_comments` (
  `game` varchar(255) NOT NULL DEFAULT '',
  `player` int(10) DEFAULT NULL,
  `comment` text NOT NULL DEFAULT '',
  `date` int(15) NOT NULL DEFAULT 0,
  `score` int(20) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_inline_ads`
--

CREATE TABLE `phpbb_inline_ads` (
  `ad_id` tinyint(5) NOT NULL,
  `ad_code` text NOT NULL,
  `ad_name` char(25) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_ip`
--

CREATE TABLE `phpbb_ip` (
  `id` mediumint(8) NOT NULL,
  `ip` varchar(200) NOT NULL DEFAULT '0',
  `host` varchar(200) NOT NULL DEFAULT '0',
  `date` int(11) NOT NULL DEFAULT 0,
  `username` varchar(200) NOT NULL DEFAULT '0',
  `referrer` varchar(200) NOT NULL DEFAULT '0',
  `forum` varchar(200) NOT NULL DEFAULT '0',
  `browser` varchar(200) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_jobs`
--

CREATE TABLE `phpbb_jobs` (
  `id` mediumint(8) NOT NULL,
  `name` varchar(32) NOT NULL DEFAULT '',
  `pay` mediumint(8) DEFAULT 100,
  `type` varchar(32) DEFAULT 'public',
  `requires` text DEFAULT NULL,
  `payouttime` mediumint(8) DEFAULT 500000,
  `positions` mediumint(8) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_jobs_employed`
--

CREATE TABLE `phpbb_jobs_employed` (
  `id` mediumint(8) NOT NULL,
  `user_id` mediumint(8) NOT NULL,
  `job_name` varchar(32) NOT NULL DEFAULT '',
  `job_pay` mediumint(8) NOT NULL,
  `job_length` mediumint(8) NOT NULL,
  `last_paid` mediumint(8) NOT NULL,
  `job_started` mediumint(8) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_kb_articles`
--

CREATE TABLE `phpbb_kb_articles` (
  `article_id` mediumint(8) UNSIGNED NOT NULL,
  `article_category_id` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `article_title` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  `article_description` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  `article_date` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  `article_author_id` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `username` varchar(255) DEFAULT NULL,
  `bbcode_uid` char(10) NOT NULL DEFAULT '',
  `article_body` text NOT NULL,
  `article_type` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `approved` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `topic_id` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `views` bigint(8) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_kb_categories`
--

CREATE TABLE `phpbb_kb_categories` (
  `category_id` mediumint(8) UNSIGNED NOT NULL,
  `category_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `category_details` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `number_articles` mediumint(8) UNSIGNED NOT NULL,
  `parent` mediumint(8) UNSIGNED NOT NULL,
  `cat_order` mediumint(8) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_kb_results`
--

CREATE TABLE `phpbb_kb_results` (
  `search_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `session_id` varchar(32) NOT NULL DEFAULT '',
  `search_array` mediumtext NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_kb_types`
--

CREATE TABLE `phpbb_kb_types` (
  `id` mediumint(8) UNSIGNED NOT NULL,
  `type` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_kb_wordlist`
--

CREATE TABLE `phpbb_kb_wordlist` (
  `word_text` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  `word_id` mediumint(8) UNSIGNED NOT NULL,
  `word_common` tinyint(1) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_kb_wordmatch`
--

CREATE TABLE `phpbb_kb_wordmatch` (
  `article_id` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `word_id` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `title_match` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_lexicon`
--

CREATE TABLE `phpbb_lexicon` (
  `id` bigint(8) NOT NULL,
  `keyword` varchar(250) NOT NULL DEFAULT '',
  `explanation` longtext NOT NULL,
  `bbcode_uid` char(10) NOT NULL DEFAULT '',
  `cat` int(8) NOT NULL DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_lexicon_cat`
--

CREATE TABLE `phpbb_lexicon_cat` (
  `cat_id` int(8) NOT NULL,
  `cat_titel` varchar(80) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_links`
--

CREATE TABLE `phpbb_links` (
  `link_id` mediumint(8) UNSIGNED NOT NULL,
  `link_name` text DEFAULT NULL,
  `link_longdesc` text DEFAULT NULL,
  `link_catid` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `link_url` varchar(100) NOT NULL DEFAULT '',
  `link_logo_src` varchar(100) DEFAULT NULL,
  `link_time` int(25) NOT NULL DEFAULT 0,
  `link_approved` tinyint(1) NOT NULL DEFAULT 0,
  `link_hits` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `user_id` mediumint(8) NOT NULL DEFAULT 0,
  `poster_ip` varchar(8) NOT NULL DEFAULT '',
  `last_user_ip` varchar(8) NOT NULL DEFAULT '',
  `post_username` varchar(25) DEFAULT NULL,
  `link_pin` int(2) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_link_categories`
--

CREATE TABLE `phpbb_link_categories` (
  `cat_id` int(10) NOT NULL,
  `cat_name` text NOT NULL DEFAULT '',
  `cat_order` int(50) DEFAULT NULL,
  `cat_parent` int(50) DEFAULT NULL,
  `parents_data` text NOT NULL DEFAULT '',
  `cat_links` mediumint(8) NOT NULL DEFAULT -1
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_link_comments`
--

CREATE TABLE `phpbb_link_comments` (
  `comments_id` int(10) NOT NULL,
  `link_id` int(10) NOT NULL DEFAULT 0,
  `comments_text` text NOT NULL,
  `comments_title` text NOT NULL,
  `comments_time` int(50) NOT NULL DEFAULT 0,
  `comment_bbcode_uid` varchar(10) DEFAULT NULL,
  `poster_id` mediumint(8) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_link_config`
--

CREATE TABLE `phpbb_link_config` (
  `config_name` varchar(255) NOT NULL DEFAULT '',
  `config_value` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_link_custom`
--

CREATE TABLE `phpbb_link_custom` (
  `custom_id` int(50) NOT NULL,
  `custom_name` text NOT NULL,
  `custom_description` text NOT NULL,
  `data` text NOT NULL,
  `field_order` int(20) NOT NULL DEFAULT 0,
  `field_type` tinyint(2) NOT NULL DEFAULT 0,
  `regex` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_link_customdata`
--

CREATE TABLE `phpbb_link_customdata` (
  `customdata_file` int(50) NOT NULL DEFAULT 0,
  `customdata_custom` int(50) NOT NULL DEFAULT 0,
  `data` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_link_votes`
--

CREATE TABLE `phpbb_link_votes` (
  `user_id` mediumint(8) NOT NULL DEFAULT 0,
  `votes_ip` varchar(50) NOT NULL DEFAULT '0',
  `votes_link` int(50) NOT NULL DEFAULT 0,
  `rate_point` tinyint(3) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_logs`
--

CREATE TABLE `phpbb_logs` (
  `id_log` mediumint(10) NOT NULL,
  `mode` varchar(50) DEFAULT '',
  `topic_id` mediumint(10) DEFAULT 0,
  `user_id` mediumint(8) DEFAULT 0,
  `username` varchar(255) DEFAULT '',
  `user_ip` char(8) NOT NULL DEFAULT '0',
  `time` int(11) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_lottery`
--

CREATE TABLE `phpbb_lottery` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_lottery_history`
--

CREATE TABLE `phpbb_lottery_history` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) NOT NULL,
  `amount` int(10) NOT NULL,
  `time` int(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_medal`
--

CREATE TABLE `phpbb_medal` (
  `medal_id` mediumint(8) UNSIGNED NOT NULL,
  `cat_id` mediumint(8) UNSIGNED NOT NULL DEFAULT 1,
  `medal_name` varchar(40) NOT NULL,
  `medal_description` varchar(255) NOT NULL,
  `medal_image` varchar(40) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_medal_cat`
--

CREATE TABLE `phpbb_medal_cat` (
  `cat_id` mediumint(8) UNSIGNED NOT NULL,
  `cat_title` varchar(100) NOT NULL,
  `cat_order` mediumint(8) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_medal_mod`
--

CREATE TABLE `phpbb_medal_mod` (
  `mod_id` mediumint(8) UNSIGNED NOT NULL,
  `medal_id` mediumint(8) UNSIGNED NOT NULL,
  `user_id` mediumint(8) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_medal_user`
--

CREATE TABLE `phpbb_medal_user` (
  `issue_id` mediumint(8) UNSIGNED NOT NULL,
  `medal_id` mediumint(8) UNSIGNED NOT NULL,
  `user_id` mediumint(8) UNSIGNED NOT NULL,
  `issue_reason` varchar(255) NOT NULL,
  `issue_time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_meeting_comment`
--

CREATE TABLE `phpbb_meeting_comment` (
  `comment_id` mediumint(8) NOT NULL,
  `meeting_id` mediumint(8) UNSIGNED NOT NULL,
  `user_id` mediumint(8) NOT NULL DEFAULT 0,
  `meeting_comment` text NOT NULL,
  `meeting_edit_time` int(11) NOT NULL DEFAULT 0,
  `bbcode_uid` char(10) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_meeting_config`
--

CREATE TABLE `phpbb_meeting_config` (
  `config_name` varchar(255) NOT NULL DEFAULT '',
  `config_value` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_meeting_data`
--

CREATE TABLE `phpbb_meeting_data` (
  `meeting_id` mediumint(8) UNSIGNED NOT NULL,
  `meeting_time` int(11) NOT NULL DEFAULT 0,
  `meeting_until` int(11) NOT NULL DEFAULT 0,
  `meeting_location` varchar(255) NOT NULL DEFAULT '',
  `meeting_subject` varchar(255) NOT NULL DEFAULT '',
  `meeting_desc` text NOT NULL,
  `meeting_link` varchar(255) NOT NULL DEFAULT '',
  `meeting_places` mediumint(8) NOT NULL DEFAULT 0,
  `meeting_by_user` mediumint(8) NOT NULL DEFAULT 0,
  `meeting_edit_by_user` mediumint(8) NOT NULL DEFAULT 0,
  `meeting_start_value` mediumint(8) NOT NULL DEFAULT 0,
  `meeting_recure_value` mediumint(8) NOT NULL DEFAULT 5,
  `meeting_notify` tinyint(1) NOT NULL DEFAULT 0,
  `meeting_guest_overall` mediumint(8) NOT NULL DEFAULT 0,
  `meeting_guest_single` mediumint(8) NOT NULL DEFAULT 0,
  `meeting_guest_names` tinyint(1) NOT NULL DEFAULT 0,
  `bbcode_uid` char(10) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_meeting_guestnames`
--

CREATE TABLE `phpbb_meeting_guestnames` (
  `meeting_id` mediumint(8) NOT NULL DEFAULT 0,
  `user_id` mediumint(8) NOT NULL DEFAULT 0,
  `guest_prename` varchar(255) NOT NULL DEFAULT '',
  `guest_name` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_meeting_user`
--

CREATE TABLE `phpbb_meeting_user` (
  `meeting_id` mediumint(8) UNSIGNED NOT NULL,
  `user_id` mediumint(8) NOT NULL DEFAULT 0,
  `meeting_sure` tinyint(4) NOT NULL DEFAULT 0,
  `meeting_guests` mediumint(8) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_meeting_usergroup`
--

CREATE TABLE `phpbb_meeting_usergroup` (
  `meeting_id` mediumint(8) UNSIGNED NOT NULL,
  `meeting_group` mediumint(8) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_modules`
--

CREATE TABLE `phpbb_modules` (
  `module_id` mediumint(8) UNSIGNED NOT NULL,
  `short_name` varchar(100) DEFAULT NULL,
  `update_time` mediumint(8) NOT NULL DEFAULT 0,
  `module_order` mediumint(8) NOT NULL DEFAULT 0,
  `active` tinyint(2) NOT NULL DEFAULT 0,
  `perm_all` tinyint(2) UNSIGNED NOT NULL DEFAULT 1,
  `perm_reg` tinyint(2) UNSIGNED NOT NULL DEFAULT 1,
  `perm_mod` tinyint(2) UNSIGNED NOT NULL DEFAULT 1,
  `perm_admin` tinyint(2) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_module_admin_panel`
--

CREATE TABLE `phpbb_module_admin_panel` (
  `module_id` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `config_name` varchar(255) NOT NULL DEFAULT '',
  `config_value` varchar(255) NOT NULL DEFAULT '',
  `config_type` varchar(20) NOT NULL DEFAULT '',
  `config_title` varchar(100) NOT NULL DEFAULT '',
  `config_explain` varchar(100) DEFAULT NULL,
  `config_trigger` varchar(20) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_module_cache`
--

CREATE TABLE `phpbb_module_cache` (
  `module_id` mediumint(8) NOT NULL DEFAULT 0,
  `module_cache_time` int(12) NOT NULL DEFAULT 0,
  `db_cache` text NOT NULL,
  `priority` mediumint(8) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_module_group_auth`
--

CREATE TABLE `phpbb_module_group_auth` (
  `module_id` mediumint(8) UNSIGNED NOT NULL,
  `group_id` mediumint(8) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_module_info`
--

CREATE TABLE `phpbb_module_info` (
  `module_id` mediumint(8) NOT NULL DEFAULT 0,
  `long_name` varchar(100) NOT NULL DEFAULT '',
  `author` varchar(50) DEFAULT NULL,
  `email` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(100) DEFAULT NULL,
  `version` varchar(10) NOT NULL DEFAULT '',
  `update_site` varchar(100) DEFAULT NULL,
  `extra_info` longtext NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_mycalendar`
--

CREATE TABLE `phpbb_mycalendar` (
  `cal_id` int(12) NOT NULL,
  `topic_id` int(20) NOT NULL DEFAULT 0,
  `cal_date` datetime DEFAULT '0000-00-00 00:00:00',
  `cal_interval` tinyint(3) NOT NULL DEFAULT 1,
  `cal_interval_units` enum('DAY','WEEK','MONTH','YEAR') NOT NULL DEFAULT 'DAY',
  `cal_repeat` tinyint(3) NOT NULL DEFAULT 1,
  `forum_id` int(5) NOT NULL DEFAULT 0,
  `confirmed` enum('Y','N') NOT NULL DEFAULT 'Y',
  `event_type_id` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_mycalendar_event_types`
--

CREATE TABLE `phpbb_mycalendar_event_types` (
  `forum_id` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `event_type_id` tinyint(4) NOT NULL,
  `event_type_text` varchar(255) NOT NULL DEFAULT '',
  `highlight_color` varchar(7) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_optimize_db`
--

CREATE TABLE `phpbb_optimize_db` (
  `cron_enable` enum('0','1') NOT NULL DEFAULT '0',
  `cron_every` int(7) NOT NULL DEFAULT 86400,
  `cron_next` int(11) NOT NULL DEFAULT 0,
  `cron_count` int(5) NOT NULL DEFAULT 0,
  `show_tables` varchar(150) NOT NULL DEFAULT '',
  `empty_tables` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_pages`
--

CREATE TABLE `phpbb_pages` (
  `page_id` mediumint(5) UNSIGNED NOT NULL,
  `page_name` varchar(32) NOT NULL,
  `page_parm_name` varchar(32) DEFAULT '',
  `page_parm_value` varchar(32) DEFAULT '',
  `page_key` varchar(255) DEFAULT '',
  `member_views` int(11) UNSIGNED DEFAULT 0,
  `guest_views` int(11) UNSIGNED DEFAULT 0,
  `disable_page` tinyint(1) UNSIGNED DEFAULT 0,
  `auth_level` tinyint(2) UNSIGNED DEFAULT 0,
  `min_post_count` mediumint(8) UNSIGNED DEFAULT 0,
  `max_post_count` mediumint(8) UNSIGNED DEFAULT 0,
  `group_list` varchar(255) DEFAULT '',
  `disabled_message` varchar(255) DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_pa_auth`
--

CREATE TABLE `phpbb_pa_auth` (
  `group_id` mediumint(8) NOT NULL DEFAULT 0,
  `cat_id` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `auth_view` tinyint(1) NOT NULL DEFAULT 0,
  `auth_read` tinyint(1) NOT NULL DEFAULT 0,
  `auth_view_file` tinyint(1) NOT NULL DEFAULT 0,
  `auth_edit_file` tinyint(1) NOT NULL DEFAULT 0,
  `auth_delete_file` tinyint(1) NOT NULL DEFAULT 0,
  `auth_upload` tinyint(1) NOT NULL DEFAULT 0,
  `auth_download` tinyint(1) NOT NULL DEFAULT 0,
  `auth_rate` tinyint(1) NOT NULL DEFAULT 0,
  `auth_email` tinyint(1) NOT NULL DEFAULT 0,
  `auth_view_comment` tinyint(1) NOT NULL DEFAULT 0,
  `auth_post_comment` tinyint(1) NOT NULL DEFAULT 0,
  `auth_edit_comment` tinyint(1) NOT NULL DEFAULT 0,
  `auth_delete_comment` tinyint(1) NOT NULL DEFAULT 0,
  `auth_mod` tinyint(1) NOT NULL DEFAULT 0,
  `auth_search` tinyint(1) NOT NULL DEFAULT 1,
  `auth_stats` tinyint(1) NOT NULL DEFAULT 1,
  `auth_toplist` tinyint(1) NOT NULL DEFAULT 1,
  `auth_viewall` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_pa_cat`
--

CREATE TABLE `phpbb_pa_cat` (
  `cat_id` int(10) NOT NULL,
  `cat_name` text DEFAULT NULL,
  `cat_desc` text DEFAULT NULL,
  `cat_parent` int(50) DEFAULT NULL,
  `parents_data` text NOT NULL DEFAULT '',
  `cat_order` int(50) DEFAULT NULL,
  `cat_allow_file` tinyint(2) NOT NULL DEFAULT 0,
  `cat_allow_ratings` tinyint(2) NOT NULL DEFAULT 1,
  `cat_allow_comments` tinyint(2) NOT NULL DEFAULT 1,
  `cat_files` mediumint(8) NOT NULL DEFAULT -1,
  `cat_last_file_id` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `cat_last_file_name` varchar(255) NOT NULL DEFAULT '',
  `cat_last_file_time` int(50) UNSIGNED NOT NULL DEFAULT 0,
  `auth_view` tinyint(2) NOT NULL DEFAULT 0,
  `auth_read` tinyint(2) NOT NULL DEFAULT 0,
  `auth_view_file` tinyint(2) NOT NULL DEFAULT 0,
  `auth_edit_file` tinyint(1) NOT NULL DEFAULT 0,
  `auth_delete_file` tinyint(1) NOT NULL DEFAULT 0,
  `auth_upload` tinyint(2) NOT NULL DEFAULT 0,
  `auth_download` tinyint(2) NOT NULL DEFAULT 0,
  `auth_rate` tinyint(2) NOT NULL DEFAULT 0,
  `auth_email` tinyint(2) NOT NULL DEFAULT 0,
  `auth_view_comment` tinyint(2) NOT NULL DEFAULT 0,
  `auth_post_comment` tinyint(2) NOT NULL DEFAULT 0,
  `auth_edit_comment` tinyint(2) NOT NULL DEFAULT 0,
  `auth_delete_comment` tinyint(2) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_pa_comments`
--

CREATE TABLE `phpbb_pa_comments` (
  `comments_id` int(10) NOT NULL,
  `file_id` int(10) NOT NULL DEFAULT 0,
  `comments_text` text NOT NULL DEFAULT '',
  `comments_title` text NOT NULL DEFAULT '',
  `comments_time` int(50) NOT NULL DEFAULT 0,
  `comment_bbcode_uid` char(10) NOT NULL DEFAULT '',
  `poster_id` mediumint(8) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_pa_config`
--

CREATE TABLE `phpbb_pa_config` (
  `config_name` varchar(255) NOT NULL DEFAULT '',
  `config_value` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_pa_custom`
--

CREATE TABLE `phpbb_pa_custom` (
  `custom_id` int(50) NOT NULL,
  `custom_name` text NOT NULL DEFAULT '',
  `custom_description` text NOT NULL DEFAULT '',
  `data` text NOT NULL DEFAULT '',
  `field_order` int(20) NOT NULL DEFAULT 0,
  `field_type` tinyint(2) NOT NULL DEFAULT 0,
  `regex` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_pa_customdata`
--

CREATE TABLE `phpbb_pa_customdata` (
  `customdata_file` int(50) NOT NULL DEFAULT 0,
  `customdata_custom` int(50) NOT NULL DEFAULT 0,
  `data` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_pa_download_info`
--

CREATE TABLE `phpbb_pa_download_info` (
  `file_id` mediumint(8) NOT NULL DEFAULT 0,
  `user_id` mediumint(8) NOT NULL DEFAULT 0,
  `downloader_ip` varchar(8) NOT NULL DEFAULT '',
  `downloader_os` varchar(255) NOT NULL DEFAULT '',
  `downloader_browser` varchar(255) NOT NULL DEFAULT '',
  `browser_version` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_pa_files`
--

CREATE TABLE `phpbb_pa_files` (
  `file_id` int(10) NOT NULL,
  `user_id` mediumint(8) NOT NULL DEFAULT 0,
  `poster_ip` varchar(8) NOT NULL DEFAULT '',
  `file_name` text DEFAULT NULL,
  `file_size` int(20) NOT NULL DEFAULT 0,
  `unique_name` varchar(255) NOT NULL DEFAULT '',
  `real_name` varchar(255) NOT NULL DEFAULT '',
  `file_dir` varchar(255) NOT NULL DEFAULT '',
  `file_desc` text DEFAULT NULL,
  `file_creator` text DEFAULT NULL,
  `file_version` text DEFAULT NULL,
  `file_longdesc` text DEFAULT NULL,
  `file_ssurl` text DEFAULT NULL,
  `file_sshot_link` tinyint(2) NOT NULL DEFAULT 0,
  `file_dlurl` text DEFAULT NULL,
  `file_time` int(50) DEFAULT NULL,
  `file_update_time` int(50) NOT NULL DEFAULT 0,
  `file_catid` int(10) DEFAULT NULL,
  `file_posticon` text DEFAULT NULL,
  `file_license` int(10) DEFAULT NULL,
  `file_dls` int(10) DEFAULT NULL,
  `file_last` int(50) DEFAULT NULL,
  `file_pin` int(2) DEFAULT NULL,
  `file_docsurl` text DEFAULT NULL,
  `file_approved` int(11) DEFAULT NULL,
  `file_broken` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_pa_license`
--

CREATE TABLE `phpbb_pa_license` (
  `license_id` int(10) NOT NULL,
  `license_name` text DEFAULT NULL,
  `license_text` text DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_pa_mirrors`
--

CREATE TABLE `phpbb_pa_mirrors` (
  `mirror_id` mediumint(8) NOT NULL,
  `file_id` int(10) NOT NULL,
  `unique_name` varchar(255) NOT NULL DEFAULT '',
  `file_dir` varchar(255) NOT NULL DEFAULT '',
  `file_dlurl` varchar(255) NOT NULL DEFAULT '',
  `mirror_location` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_pa_votes`
--

CREATE TABLE `phpbb_pa_votes` (
  `user_id` mediumint(8) NOT NULL DEFAULT 0,
  `votes_ip` varchar(50) NOT NULL DEFAULT '0',
  `votes_file` int(50) NOT NULL DEFAULT 0,
  `rate_point` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `voter_os` varchar(255) NOT NULL DEFAULT '',
  `voter_browser` varchar(255) NOT NULL DEFAULT '',
  `browser_version` varchar(8) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_pjirc`
--

CREATE TABLE `phpbb_pjirc` (
  `pjirc_name` varchar(255) NOT NULL DEFAULT '',
  `pjirc_value` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_points_logger`
--

CREATE TABLE `phpbb_points_logger` (
  `id` mediumint(8) NOT NULL,
  `admin` varchar(25) NOT NULL DEFAULT '',
  `person` varchar(25) NOT NULL DEFAULT '',
  `add_sub` varchar(50) NOT NULL DEFAULT '',
  `total` mediumint(8) NOT NULL DEFAULT 0,
  `time` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_portal`
--

CREATE TABLE `phpbb_portal` (
  `portal_id` mediumint(8) NOT NULL,
  `portal_order` mediumint(8) UNSIGNED NOT NULL DEFAULT 1,
  `portal_use_url` tinyint(1) NOT NULL DEFAULT 0,
  `portal_iframe_height` varchar(4) NOT NULL DEFAULT '600',
  `portal_use_iframe` tinyint(1) NOT NULL DEFAULT 0,
  `portal_forum` mediumint(8) NOT NULL,
  `portal_url` varchar(255) NOT NULL DEFAULT '',
  `portal_list_limit` mediumint(8) NOT NULL,
  `portal_char_limit` mediumint(8) NOT NULL DEFAULT 0,
  `portal_ascending` tinyint(1) NOT NULL DEFAULT 0,
  `portal_nodate` tinyint(1) NOT NULL DEFAULT 0,
  `portal_navbar_name` varchar(100) NOT NULL DEFAULT '',
  `portal_newsfader` tinyint(1) NOT NULL DEFAULT 0,
  `portal_column_width` varchar(3) NOT NULL DEFAULT '200',
  `portal_navbar` tinyint(1) NOT NULL DEFAULT 0,
  `portal_moreover` tinyint(1) NOT NULL DEFAULT 0,
  `portal_calendar` tinyint(1) NOT NULL DEFAULT 0,
  `portal_online` tinyint(1) NOT NULL DEFAULT 0,
  `portal_onlinetoday` tinyint(1) NOT NULL DEFAULT 0,
  `portal_latest` tinyint(1) NOT NULL DEFAULT 0,
  `portal_latest_exclude_forums` varchar(100) NOT NULL DEFAULT '',
  `portal_latest_amt` varchar(5) NOT NULL DEFAULT '5',
  `portal_latest_scrolling` tinyint(1) NOT NULL DEFAULT 0,
  `portal_poll` tinyint(1) NOT NULL DEFAULT 0,
  `portal_polls` varchar(100) NOT NULL DEFAULT '',
  `portal_photo` tinyint(1) NOT NULL DEFAULT 0,
  `portal_birthday` mediumint(6) NOT NULL DEFAULT 999999,
  `portal_search` tinyint(1) NOT NULL DEFAULT 0,
  `portal_quote` tinyint(1) NOT NULL DEFAULT 0,
  `portal_links` tinyint(1) NOT NULL DEFAULT 0,
  `portal_links_height` varchar(4) NOT NULL DEFAULT '100',
  `portal_ourlink` tinyint(1) NOT NULL DEFAULT 0,
  `portal_downloads` tinyint(1) NOT NULL DEFAULT 0,
  `portal_randomuser` tinyint(1) NOT NULL DEFAULT 0,
  `portal_mostpoints` tinyint(1) NOT NULL DEFAULT 0,
  `portal_topposters` tinyint(1) NOT NULL DEFAULT 0,
  `portal_newusers` tinyint(1) NOT NULL DEFAULT 0,
  `portal_games` tinyint(1) NOT NULL DEFAULT 0,
  `portal_clock` tinyint(1) NOT NULL DEFAULT 0,
  `portal_karma` tinyint(1) NOT NULL DEFAULT 0,
  `portal_horoscopes` tinyint(1) NOT NULL DEFAULT 0,
  `portal_wallpaper` tinyint(1) NOT NULL DEFAULT 0,
  `portal_donors` tinyint(1) NOT NULL DEFAULT 0,
  `portal_referrers` tinyint(1) NOT NULL DEFAULT 0,
  `portal_shoutbox` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_posts`
--

CREATE TABLE `phpbb_posts` (
  `post_id` mediumint(8) UNSIGNED NOT NULL,
  `topic_id` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `forum_id` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `poster_id` mediumint(8) NOT NULL DEFAULT 0,
  `post_time` int(11) NOT NULL DEFAULT 0,
  `poster_ip` char(8) NOT NULL,
  `post_username` varchar(25) DEFAULT NULL,
  `enable_bbcode` tinyint(1) NOT NULL DEFAULT 1,
  `enable_html` tinyint(1) NOT NULL DEFAULT 0,
  `enable_smilies` tinyint(1) NOT NULL DEFAULT 1,
  `enable_sig` tinyint(1) NOT NULL DEFAULT 1,
  `post_edit_time` int(11) DEFAULT NULL,
  `post_edit_count` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `post_attachment` tinyint(1) NOT NULL DEFAULT 0,
  `post_edit_user` mediumint(8) DEFAULT NULL,
  `post_icon` tinyint(2) UNSIGNED NOT NULL DEFAULT 0,
  `post_bluecard` tinyint(1) DEFAULT NULL,
  `rating_rank_id` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `user_avatar` varchar(100) NOT NULL DEFAULT '',
  `user_avatar_type` tinyint(4) NOT NULL DEFAULT 0,
  `urgent_post` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_posts_edit`
--

CREATE TABLE `phpbb_posts_edit` (
  `post_id` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `user_id` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `post_edit_count` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `post_edit_time` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_posts_ignore_sigav`
--

CREATE TABLE `phpbb_posts_ignore_sigav` (
  `user_id` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `hid_id` mediumint(8) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_posts_text`
--

CREATE TABLE `phpbb_posts_text` (
  `post_id` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `bbcode_uid` char(10) NOT NULL DEFAULT '',
  `post_subject` varchar(120) DEFAULT NULL,
  `post_text` text DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_privmsgs`
--

CREATE TABLE `phpbb_privmsgs` (
  `privmsgs_id` mediumint(8) UNSIGNED NOT NULL,
  `privmsgs_type` tinyint(4) NOT NULL DEFAULT 0,
  `privmsgs_subject` varchar(255) NOT NULL DEFAULT '0',
  `privmsgs_from_userid` mediumint(8) NOT NULL DEFAULT 0,
  `privmsgs_to_userid` mediumint(8) NOT NULL DEFAULT 0,
  `privmsgs_date` int(11) NOT NULL DEFAULT 0,
  `privmsgs_ip` char(8) NOT NULL,
  `privmsgs_enable_bbcode` tinyint(1) NOT NULL DEFAULT 1,
  `privmsgs_enable_html` tinyint(1) NOT NULL DEFAULT 0,
  `privmsgs_enable_smilies` tinyint(1) NOT NULL DEFAULT 1,
  `privmsgs_attach_sig` tinyint(1) NOT NULL DEFAULT 1,
  `privmsgs_attachment` tinyint(1) NOT NULL DEFAULT 0,
  `privmsgs_from_username` varchar(25) NOT NULL DEFAULT '',
  `privmsgs_to_username` varchar(25) NOT NULL DEFAULT '',
  `site_id` mediumint(8) NOT NULL DEFAULT 0,
  `room_id` mediumint(8) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_privmsgs_archive`
--

CREATE TABLE `phpbb_privmsgs_archive` (
  `privmsgs_id` mediumint(8) UNSIGNED NOT NULL,
  `privmsgs_type` tinyint(4) NOT NULL DEFAULT 0,
  `privmsgs_subject` varchar(255) NOT NULL DEFAULT '0',
  `privmsgs_from_userid` mediumint(8) NOT NULL DEFAULT 0,
  `privmsgs_to_userid` mediumint(8) NOT NULL DEFAULT 0,
  `privmsgs_date` int(11) NOT NULL DEFAULT 0,
  `privmsgs_ip` varchar(8) NOT NULL DEFAULT '',
  `privmsgs_enable_bbcode` tinyint(1) NOT NULL DEFAULT 1,
  `privmsgs_enable_html` tinyint(1) NOT NULL DEFAULT 0,
  `privmsgs_enable_smilies` tinyint(1) NOT NULL DEFAULT 1,
  `privmsgs_attach_sig` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_privmsgs_text`
--

CREATE TABLE `phpbb_privmsgs_text` (
  `privmsgs_text_id` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `privmsgs_bbcode_uid` char(10) NOT NULL DEFAULT '',
  `privmsgs_text` text DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_profile_view`
--

CREATE TABLE `phpbb_profile_view` (
  `user_id` mediumint(8) NOT NULL,
  `viewer_id` mediumint(8) NOT NULL,
  `view_stamp` int(11) NOT NULL,
  `counter` mediumint(8) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_quota_limits`
--

CREATE TABLE `phpbb_quota_limits` (
  `quota_limit_id` mediumint(8) UNSIGNED NOT NULL,
  `quota_desc` varchar(20) NOT NULL DEFAULT '',
  `quota_limit` bigint(20) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_ranks`
--

CREATE TABLE `phpbb_ranks` (
  `rank_id` smallint(5) UNSIGNED NOT NULL,
  `rank_title` varchar(50) NOT NULL DEFAULT '',
  `rank_min` mediumint(8) NOT NULL DEFAULT 0,
  `rank_special` tinyint(1) DEFAULT 0,
  `rank_image` varchar(255) DEFAULT NULL,
  `rank_group` mediumint(8) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_rating`
--

CREATE TABLE `phpbb_rating` (
  `post_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `rating_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `option_id` smallint(5) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_rating_bias`
--

CREATE TABLE `phpbb_rating_bias` (
  `bias_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `target_user` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `bias_status` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `bias_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `post_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `option_id` smallint(5) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_rating_config`
--

CREATE TABLE `phpbb_rating_config` (
  `label` varchar(100) DEFAULT NULL,
  `num_value` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `text_value` varchar(255) DEFAULT NULL,
  `config_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `input_type` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `list_order` smallint(5) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_rating_option`
--

CREATE TABLE `phpbb_rating_option` (
  `option_id` smallint(5) UNSIGNED NOT NULL,
  `points` tinyint(4) NOT NULL DEFAULT 0,
  `label` varchar(100) DEFAULT NULL,
  `weighting` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `user_type` tinyint(3) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_rating_rank`
--

CREATE TABLE `phpbb_rating_rank` (
  `rating_rank_id` smallint(5) UNSIGNED NOT NULL,
  `type` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `average_threshold` tinyint(4) NOT NULL DEFAULT 0,
  `sum_threshold` int(11) NOT NULL DEFAULT 0,
  `label` varchar(100) DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `user_rank` int(10) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_rating_temp`
--

CREATE TABLE `phpbb_rating_temp` (
  `topic_id` int(10) UNSIGNED NOT NULL,
  `points` tinyint(4) NOT NULL
) ENGINE=MEMORY DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_referers`
--

CREATE TABLE `phpbb_referers` (
  `referer_id` mediumint(8) UNSIGNED NOT NULL,
  `referer_host` varchar(255) NOT NULL DEFAULT '',
  `referer_url` varchar(255) NOT NULL DEFAULT '',
  `referer_ip` varchar(8) NOT NULL DEFAULT '',
  `referer_hits` int(10) NOT NULL DEFAULT 1,
  `referer_firstvisit` int(11) NOT NULL DEFAULT 0,
  `referer_lastvisit` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_referral`
--

CREATE TABLE `phpbb_referral` (
  `referral_id` mediumint(8) UNSIGNED NOT NULL,
  `ruid` varchar(7) NOT NULL DEFAULT '0',
  `nuid` varchar(7) NOT NULL DEFAULT '0',
  `referral_time` varchar(10) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_search_results`
--

CREATE TABLE `phpbb_search_results` (
  `search_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `session_id` char(32) NOT NULL DEFAULT '',
  `search_array` mediumtext NOT NULL DEFAULT '',
  `search_time` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_search_wordlist`
--

CREATE TABLE `phpbb_search_wordlist` (
  `word_text` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  `word_id` mediumint(8) UNSIGNED NOT NULL,
  `word_common` tinyint(1) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_search_wordmatch`
--

CREATE TABLE `phpbb_search_wordmatch` (
  `post_id` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `word_id` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `title_match` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_serverload`
--

CREATE TABLE `phpbb_serverload` (
  `time` int(14) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_sessions`
--

CREATE TABLE `phpbb_sessions` (
  `session_id` char(32) NOT NULL DEFAULT '',
  `session_user_id` mediumint(8) NOT NULL DEFAULT 0,
  `session_start` int(11) NOT NULL DEFAULT 0,
  `session_time` int(11) NOT NULL DEFAULT 0,
  `session_ip` char(8) NOT NULL DEFAULT '0',
  `session_page` int(11) NOT NULL DEFAULT 0,
  `session_topic` int(11) NOT NULL DEFAULT 0,
  `session_logged_in` tinyint(1) NOT NULL DEFAULT 0,
  `session_admin` tinyint(2) NOT NULL DEFAULT 0,
  `is_robot` varchar(255) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_sessions_keys`
--

CREATE TABLE `phpbb_sessions_keys` (
  `key_id` varchar(32) NOT NULL DEFAULT '0',
  `user_id` mediumint(8) NOT NULL DEFAULT 0,
  `last_ip` varchar(8) NOT NULL DEFAULT '0',
  `last_login` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_shops`
--

CREATE TABLE `phpbb_shops` (
  `id` int(10) UNSIGNED NOT NULL,
  `shopname` char(32) NOT NULL,
  `shoptype` char(32) NOT NULL,
  `type` char(32) NOT NULL,
  `restocktime` int(20) UNSIGNED DEFAULT 86400,
  `restockedtime` int(20) UNSIGNED DEFAULT 0,
  `restockamount` int(4) UNSIGNED DEFAULT 5,
  `amountearnt` int(20) UNSIGNED DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_shop_items`
--

CREATE TABLE `phpbb_shop_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` char(32) NOT NULL,
  `shop` char(32) NOT NULL,
  `sdesc` char(80) NOT NULL,
  `ldesc` text NOT NULL,
  `cost` int(20) UNSIGNED DEFAULT 100,
  `stock` tinyint(3) UNSIGNED DEFAULT 10,
  `maxstock` tinyint(3) UNSIGNED DEFAULT 100,
  `sold` int(5) UNSIGNED NOT NULL DEFAULT 0,
  `accessforum` int(4) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_shop_transactions`
--

CREATE TABLE `phpbb_shop_transactions` (
  `shoptrans_date` int(11) NOT NULL DEFAULT 0,
  `trans_user` mediumint(8) NOT NULL,
  `trans_item` varchar(32) NOT NULL DEFAULT '',
  `trans_type` varchar(255) NOT NULL DEFAULT '',
  `trans_total` mediumint(8) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_shout`
--

CREATE TABLE `phpbb_shout` (
  `shout_id` mediumint(8) UNSIGNED NOT NULL,
  `shout_username` varchar(25) NOT NULL DEFAULT '',
  `shout_user_id` mediumint(8) NOT NULL,
  `shout_group_id` mediumint(8) NOT NULL,
  `shout_session_time` int(11) NOT NULL,
  `shout_ip` char(8) NOT NULL,
  `shout_text` text NOT NULL,
  `shout_active` mediumint(8) NOT NULL,
  `enable_bbcode` tinyint(1) NOT NULL,
  `enable_html` tinyint(1) NOT NULL,
  `enable_smilies` tinyint(1) NOT NULL,
  `enable_sig` tinyint(1) NOT NULL,
  `shout_bbcode_uid` char(10) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_smilies`
--

CREATE TABLE `phpbb_smilies` (
  `smilies_id` smallint(5) UNSIGNED NOT NULL,
  `cat_id` smallint(5) UNSIGNED NOT NULL,
  `code` varchar(50) DEFAULT NULL,
  `smile_url` varchar(100) DEFAULT NULL,
  `emoticon` varchar(75) DEFAULT NULL,
  `smilies_order` smallint(5) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_smilies_cat`
--

CREATE TABLE `phpbb_smilies_cat` (
  `cat_id` smallint(3) UNSIGNED NOT NULL,
  `cat_name` varchar(50) NOT NULL DEFAULT '',
  `description` varchar(100) NOT NULL DEFAULT '',
  `cat_order` smallint(3) NOT NULL DEFAULT 0,
  `cat_perms` tinyint(2) NOT NULL DEFAULT 10,
  `cat_group` varchar(255) DEFAULT NULL,
  `cat_forum` mediumtext NOT NULL,
  `cat_special` tinyint(1) NOT NULL DEFAULT -2,
  `cat_open` tinyint(1) NOT NULL DEFAULT 1,
  `cat_icon_url` varchar(100) DEFAULT NULL,
  `smilies_popup` varchar(20) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_stats_smilies_index`
--

CREATE TABLE `phpbb_stats_smilies_index` (
  `code` varchar(50) DEFAULT NULL,
  `smile_url` varchar(100) DEFAULT NULL,
  `smile_count` mediumint(8) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_stats_smilies_info`
--

CREATE TABLE `phpbb_stats_smilies_info` (
  `last_post_id` mediumint(8) NOT NULL DEFAULT 0,
  `last_update_time` int(12) NOT NULL DEFAULT 0,
  `update_time` mediumint(8) NOT NULL DEFAULT 10080
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_thanks`
--

CREATE TABLE `phpbb_thanks` (
  `topic_id` mediumint(8) NOT NULL,
  `user_id` mediumint(8) NOT NULL,
  `thanks_time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_themes`
--

CREATE TABLE `phpbb_themes` (
  `themes_id` mediumint(8) UNSIGNED NOT NULL,
  `template_name` varchar(30) NOT NULL DEFAULT '',
  `theme_public` tinyint(1) NOT NULL DEFAULT 1,
  `theme_header` tinyint(1) NOT NULL DEFAULT 0,
  `theme_footer` tinyint(1) NOT NULL DEFAULT 0,
  `style_name` varchar(30) NOT NULL DEFAULT '',
  `style_version` varchar(6) DEFAULT NULL,
  `image_cfg` varchar(100) NOT NULL DEFAULT '',
  `head_stylesheet` varchar(100) DEFAULT NULL,
  `body_background` varchar(100) DEFAULT NULL,
  `body_bgcolor` varchar(6) DEFAULT NULL,
  `body_text` varchar(6) DEFAULT NULL,
  `body_link` varchar(6) DEFAULT NULL,
  `body_vlink` varchar(6) DEFAULT NULL,
  `body_alink` varchar(6) DEFAULT NULL,
  `body_hlink` varchar(6) DEFAULT NULL,
  `tr_color1` varchar(6) DEFAULT NULL,
  `tr_color2` varchar(6) DEFAULT NULL,
  `tr_color3` varchar(6) DEFAULT NULL,
  `tr_class1` varchar(25) DEFAULT NULL,
  `tr_class2` varchar(25) DEFAULT NULL,
  `tr_class3` varchar(25) DEFAULT NULL,
  `th_color1` varchar(6) DEFAULT NULL,
  `th_color2` varchar(6) DEFAULT NULL,
  `th_color3` varchar(6) DEFAULT NULL,
  `th_class1` varchar(100) DEFAULT NULL,
  `th_class2` varchar(100) DEFAULT NULL,
  `th_class3` varchar(100) DEFAULT NULL,
  `td_color1` varchar(6) DEFAULT NULL,
  `td_color2` varchar(6) DEFAULT NULL,
  `td_color3` varchar(6) DEFAULT NULL,
  `td_class1` varchar(25) DEFAULT NULL,
  `td_class2` varchar(25) DEFAULT NULL,
  `td_class3` varchar(25) DEFAULT NULL,
  `fontface1` varchar(50) DEFAULT NULL,
  `fontface2` varchar(50) DEFAULT NULL,
  `fontface3` varchar(50) DEFAULT NULL,
  `fontsize1` tinyint(4) DEFAULT NULL,
  `fontsize2` tinyint(4) DEFAULT NULL,
  `fontsize3` tinyint(4) DEFAULT NULL,
  `fontcolor1` varchar(6) DEFAULT NULL,
  `fontcolor2` varchar(6) DEFAULT NULL,
  `fontcolor3` varchar(6) DEFAULT NULL,
  `fontcolor4` varchar(6) DEFAULT NULL,
  `span_class1` varchar(25) DEFAULT NULL,
  `span_class2` varchar(25) DEFAULT NULL,
  `span_class3` varchar(25) DEFAULT NULL,
  `img_size_poll` smallint(5) UNSIGNED DEFAULT NULL,
  `img_size_privmsg` smallint(5) UNSIGNED DEFAULT NULL,
  `hr_color1` varchar(6) NOT NULL DEFAULT 'E8F3FC',
  `hr_color2` varchar(6) NOT NULL DEFAULT 'D5E8F9',
  `hr_color3` varchar(6) NOT NULL DEFAULT 'B7D9F6',
  `hr_color4` varchar(6) NOT NULL DEFAULT 'FCECE8',
  `hr_color5` varchar(6) NOT NULL DEFAULT 'F9DDD5',
  `hr_color6` varchar(6) NOT NULL DEFAULT 'FACCBF',
  `hr_color7` varchar(6) NOT NULL DEFAULT 'E9FAEA',
  `hr_color8` varchar(6) NOT NULL DEFAULT 'D5F9D6',
  `hr_color9` varchar(6) NOT NULL DEFAULT 'B2EEB4',
  `jb_color1` varchar(6) NOT NULL DEFAULT '006EBB',
  `jb_color2` varchar(6) NOT NULL DEFAULT 'FF6428',
  `jb_color3` varchar(6) NOT NULL DEFAULT '329600',
  `adminfontcolor` varchar(6) NOT NULL DEFAULT 'FFA34F',
  `adminbold` tinyint(1) NOT NULL DEFAULT 1,
  `supermodfontcolor` varchar(6) NOT NULL DEFAULT '009900',
  `supermodbold` tinyint(1) NOT NULL DEFAULT 1,
  `modfontcolor` varchar(6) NOT NULL DEFAULT '006600',
  `modbold` tinyint(1) NOT NULL DEFAULT 1,
  `playersfontcolor` varchar(6) NOT NULL DEFAULT '0099CC',
  `botfontcolor` varchar(6) NOT NULL DEFAULT '9E8DA7'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_themes_name`
--

CREATE TABLE `phpbb_themes_name` (
  `themes_id` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `tr_color1_name` char(50) DEFAULT NULL,
  `tr_color2_name` char(50) DEFAULT NULL,
  `tr_color3_name` char(50) DEFAULT NULL,
  `tr_class1_name` char(50) DEFAULT NULL,
  `tr_class2_name` char(50) DEFAULT NULL,
  `tr_class3_name` char(50) DEFAULT NULL,
  `th_color1_name` char(50) DEFAULT NULL,
  `th_color2_name` char(50) DEFAULT NULL,
  `th_color3_name` char(50) DEFAULT NULL,
  `th_class1_name` char(50) DEFAULT NULL,
  `th_class2_name` char(50) DEFAULT NULL,
  `th_class3_name` char(50) DEFAULT NULL,
  `td_color1_name` char(50) DEFAULT NULL,
  `td_color2_name` char(50) DEFAULT NULL,
  `td_color3_name` char(50) DEFAULT NULL,
  `td_class1_name` char(50) DEFAULT NULL,
  `td_class2_name` char(50) DEFAULT NULL,
  `td_class3_name` char(50) DEFAULT NULL,
  `fontface1_name` char(50) DEFAULT NULL,
  `fontface2_name` char(50) DEFAULT NULL,
  `fontface3_name` char(50) DEFAULT NULL,
  `fontsize1_name` char(50) DEFAULT NULL,
  `fontsize2_name` char(50) DEFAULT NULL,
  `fontsize3_name` char(50) DEFAULT NULL,
  `fontcolor1_name` char(50) DEFAULT NULL,
  `fontcolor2_name` char(50) DEFAULT NULL,
  `fontcolor3_name` char(50) DEFAULT NULL,
  `fontcolor4_name` char(50) DEFAULT NULL,
  `span_class1_name` char(50) DEFAULT NULL,
  `span_class2_name` char(50) DEFAULT NULL,
  `span_class3_name` char(50) DEFAULT NULL,
  `hr_color1_name` char(50) DEFAULT NULL,
  `hr_color2_name` char(50) DEFAULT NULL,
  `hr_color3_name` char(50) DEFAULT NULL,
  `hr_color4_name` char(50) DEFAULT NULL,
  `hr_color5_name` char(50) DEFAULT NULL,
  `hr_color6_name` char(50) DEFAULT NULL,
  `hr_color7_name` char(50) DEFAULT NULL,
  `hr_color8_name` char(50) DEFAULT NULL,
  `hr_color9_name` char(50) DEFAULT NULL,
  `jb_color1_name` char(50) DEFAULT NULL,
  `jb_color2_name` char(50) DEFAULT NULL,
  `jb_color3_name` char(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_thread_kicker`
--

CREATE TABLE `phpbb_thread_kicker` (
  `kick_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `topic_id` int(11) NOT NULL DEFAULT 0,
  `kicker` int(11) NOT NULL DEFAULT 0,
  `post_id` int(11) NOT NULL DEFAULT 0,
  `kick_time` int(11) NOT NULL DEFAULT 0,
  `kicker_status` int(2) NOT NULL DEFAULT 0,
  `kicker_username` varchar(25) NOT NULL DEFAULT '',
  `kicked_username` varchar(25) NOT NULL DEFAULT '',
  `topic_title` varchar(120) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_title_infos`
--

CREATE TABLE `phpbb_title_infos` (
  `id` int(11) NOT NULL,
  `title_info` varchar(255) NOT NULL DEFAULT '',
  `info_color` varchar(6) NOT NULL DEFAULT '',
  `date_format` varchar(25) DEFAULT NULL,
  `title_pos` tinyint(1) NOT NULL DEFAULT 0,
  `admin_auth` tinyint(1) DEFAULT 0,
  `supermod_auth` tinyint(1) DEFAULT 0,
  `mod_auth` tinyint(1) DEFAULT 0,
  `poster_auth` tinyint(1) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_topics`
--

CREATE TABLE `phpbb_topics` (
  `topic_id` mediumint(8) UNSIGNED NOT NULL,
  `forum_id` smallint(8) UNSIGNED NOT NULL DEFAULT 0,
  `topic_title` char(120) NOT NULL DEFAULT '',
  `topic_poster` mediumint(8) NOT NULL DEFAULT 0,
  `topic_time` int(11) NOT NULL DEFAULT 0,
  `topic_views` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `topic_replies` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `topic_status` tinyint(3) NOT NULL DEFAULT 0,
  `topic_vote` tinyint(1) NOT NULL DEFAULT 0,
  `topic_type` tinyint(3) NOT NULL DEFAULT 0,
  `topic_first_post_id` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `topic_last_post_id` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `answer_status` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `topic_moved_id` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `topic_attachment` tinyint(1) NOT NULL DEFAULT 0,
  `topic_icon` tinyint(2) UNSIGNED NOT NULL DEFAULT 0,
  `rating_rank_id` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `title_compl_infos` varchar(255) DEFAULT NULL,
  `title_compl_color` varchar(6) NOT NULL DEFAULT '',
  `title_pos` tinyint(1) NOT NULL DEFAULT 0,
  `topic_priority` smallint(6) NOT NULL DEFAULT 0,
  `topic_password` varchar(20) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_topics_viewdata`
--

CREATE TABLE `phpbb_topics_viewdata` (
  `viewed_id` int(10) UNSIGNED NOT NULL,
  `user_id` mediumint(8) NOT NULL DEFAULT 0,
  `topic_id` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `num_views` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `last_viewed` int(10) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_topics_watch`
--

CREATE TABLE `phpbb_topics_watch` (
  `topic_id` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `user_id` mediumint(8) NOT NULL DEFAULT 0,
  `notify_status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_toplist`
--

CREATE TABLE `phpbb_toplist` (
  `id` int(255) NOT NULL,
  `nam` varchar(255) NOT NULL DEFAULT '',
  `inf` varchar(255) NOT NULL DEFAULT '',
  `hin` int(255) NOT NULL DEFAULT 0,
  `lin` varchar(255) NOT NULL DEFAULT '',
  `out` int(255) NOT NULL DEFAULT 0,
  `img` int(255) NOT NULL DEFAULT 0,
  `ban` varchar(255) NOT NULL DEFAULT 'http://',
  `owner` int(255) NOT NULL DEFAULT 0,
  `tot` int(255) NOT NULL DEFAULT 0,
  `imgfile` varchar(50) NOT NULL DEFAULT 'button1',
  `ip` varchar(8) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_toplist_anti_flood`
--

CREATE TABLE `phpbb_toplist_anti_flood` (
  `id` int(255) NOT NULL,
  `ip` varchar(8) NOT NULL DEFAULT '',
  `time` int(11) NOT NULL,
  `type` varchar(3) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_transactions`
--

CREATE TABLE `phpbb_transactions` (
  `trans_date` int(11) NOT NULL DEFAULT 0,
  `trans_from` varchar(30) NOT NULL DEFAULT '',
  `trans_to` varchar(30) NOT NULL DEFAULT '',
  `trans_amount` mediumint(8) NOT NULL DEFAULT 0,
  `trans_reason` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_unique_hits`
--

CREATE TABLE `phpbb_unique_hits` (
  `user_ip` char(8) NOT NULL DEFAULT '0',
  `time` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_users`
--

CREATE TABLE `phpbb_users` (
  `user_id` mediumint(8) NOT NULL,
  `user_active` tinyint(1) DEFAULT 1,
  `username` varchar(99) NOT NULL DEFAULT '',
  `user_password` varchar(32) NOT NULL DEFAULT '',
  `user_session_time` int(11) NOT NULL DEFAULT 0,
  `user_session_page` smallint(5) NOT NULL DEFAULT 0,
  `user_session_topic` int(11) NOT NULL DEFAULT 0,
  `user_lastvisit` int(11) NOT NULL DEFAULT 0,
  `user_regdate` int(11) NOT NULL DEFAULT 0,
  `user_level` tinyint(4) DEFAULT 0,
  `user_posts` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `user_timezone` decimal(5,2) NOT NULL DEFAULT 0.00,
  `user_style` tinyint(4) DEFAULT NULL,
  `user_lang` varchar(255) DEFAULT NULL,
  `user_dateformat` varchar(14) NOT NULL DEFAULT 'd M Y H:i',
  `user_new_privmsg` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `user_unread_privmsg` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `user_last_privmsg` int(11) NOT NULL DEFAULT 0,
  `user_emailtime` int(11) DEFAULT NULL,
  `user_viewemail` tinyint(1) DEFAULT NULL,
  `user_attachsig` tinyint(1) DEFAULT NULL,
  `user_allowhtml` tinyint(1) DEFAULT 1,
  `user_allowbbcode` tinyint(1) DEFAULT 1,
  `user_allowsmile` tinyint(1) DEFAULT 1,
  `user_allowswearywords` tinyint(1) NOT NULL DEFAULT 0,
  `user_allowavatar` tinyint(1) NOT NULL DEFAULT 1,
  `user_allow_pm` tinyint(1) NOT NULL DEFAULT 1,
  `user_allow_mass_pm` tinyint(1) DEFAULT 2,
  `user_allow_viewonline` tinyint(1) NOT NULL DEFAULT 1,
  `user_notify` tinyint(1) NOT NULL DEFAULT 1,
  `user_notify_pm` tinyint(1) NOT NULL DEFAULT 0,
  `user_notify_pm_text` tinyint(1) NOT NULL DEFAULT 0,
  `user_notify_donation` tinyint(1) NOT NULL DEFAULT 0,
  `user_popup_pm` tinyint(1) NOT NULL DEFAULT 0,
  `user_sound_pm` tinyint(1) NOT NULL DEFAULT 0,
  `user_rank` int(11) DEFAULT 0,
  `user_avatar` varchar(100) DEFAULT NULL,
  `user_avatar_type` tinyint(4) NOT NULL DEFAULT 0,
  `user_email` varchar(255) DEFAULT NULL,
  `user_icq` varchar(15) DEFAULT NULL,
  `user_website` varchar(100) DEFAULT NULL,
  `user_from` varchar(100) DEFAULT NULL,
  `user_from_flag` varchar(25) DEFAULT NULL,
  `user_sig` text DEFAULT NULL,
  `user_sig_bbcode_uid` char(10) NOT NULL DEFAULT '',
  `user_aim` varchar(255) DEFAULT NULL,
  `user_yim` varchar(255) DEFAULT NULL,
  `user_msnm` varchar(255) DEFAULT NULL,
  `user_xfi` varchar(255) DEFAULT NULL,
  `user_skype` varchar(255) DEFAULT NULL,
  `user_occ` varchar(100) DEFAULT NULL,
  `user_interests` varchar(255) DEFAULT NULL,
  `user_actkey` varchar(32) DEFAULT NULL,
  `user_newpasswd` varchar(32) DEFAULT NULL,
  `user_lastlogon` int(11) DEFAULT 0,
  `user_totaltime` int(11) DEFAULT 0,
  `user_totallogon` smallint(5) DEFAULT 0,
  `user_totalpages` int(11) NOT NULL DEFAULT 0,
  `user_birthday` mediumint(6) NOT NULL DEFAULT 999999,
  `user_next_birthday_greeting` int(11) NOT NULL DEFAULT 0,
  `user_gender` tinyint(4) NOT NULL DEFAULT 0,
  `user_photo` varchar(100) DEFAULT NULL,
  `user_photo_type` tinyint(4) NOT NULL DEFAULT 0,
  `user_zipcode` varchar(10) DEFAULT NULL,
  `user_points` bigint(20) NOT NULL DEFAULT 0,
  `admin_allow_points` tinyint(1) NOT NULL DEFAULT 1,
  `user_items` text DEFAULT NULL,
  `user_effects` char(255) DEFAULT NULL,
  `user_privs` char(255) DEFAULT NULL,
  `user_custitle` text DEFAULT NULL,
  `user_specmsg` text DEFAULT NULL,
  `user_trade` text DEFAULT NULL,
  `user_warnings` smallint(5) DEFAULT 0,
  `rating_status` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `avatar_sticky` tinyint(4) NOT NULL DEFAULT 0,
  `user_profile_view` smallint(5) NOT NULL DEFAULT 0,
  `user_last_profile_view` int(11) NOT NULL DEFAULT 0,
  `user_profile_view_popup` tinyint(1) DEFAULT 1,
  `user_view_log` tinyint(4) NOT NULL DEFAULT 0,
  `user_votewarnings` smallint(5) DEFAULT 0,
  `user_allow_profile` tinyint(1) NOT NULL DEFAULT 1,
  `user_clockformat` varchar(12) NOT NULL DEFAULT 'clock001.swf',
  `user_inactive_emls` tinyint(1) NOT NULL DEFAULT 0,
  `user_inactive_last_eml` int(11) NOT NULL DEFAULT 0,
  `user_popup_notes` tinyint(1) DEFAULT 0,
  `user_showsigs` tinyint(1) DEFAULT 1,
  `irc_commands` varchar(255) NOT NULL DEFAULT '',
  `user_showavatars` tinyint(1) DEFAULT 1,
  `karma_plus` mediumint(9) NOT NULL DEFAULT 0,
  `karma_minus` mediumint(9) NOT NULL DEFAULT 0,
  `karma_time` bigint(20) NOT NULL DEFAULT 0,
  `user_realname` varchar(50) DEFAULT NULL,
  `user_trophies` int(10) NOT NULL DEFAULT 0,
  `ina_cheat_fix` int(100) NOT NULL DEFAULT 0,
  `ina_game_playing` int(10) NOT NULL DEFAULT 0,
  `ina_last_visit_page` varchar(255) NOT NULL DEFAULT '',
  `ina_games_today` int(10) NOT NULL DEFAULT 0,
  `ina_last_playtype` varchar(255) NOT NULL DEFAULT 'parent',
  `ina_games_played` int(10) NOT NULL DEFAULT 0,
  `user_custom_post_color` varchar(6) DEFAULT NULL,
  `kick_ban` int(2) NOT NULL DEFAULT 0,
  `user_info` text DEFAULT NULL,
  `user_gtalk` varchar(255) DEFAULT NULL,
  `user_stumble` varchar(100) DEFAULT NULL,
  `ina_game_pass` int(10) NOT NULL DEFAULT 0,
  `ina_games_pass_day` date NOT NULL DEFAULT '0000-00-00',
  `ina_time_playing` varchar(20) NOT NULL DEFAULT '',
  `ina_settings` varchar(255) NOT NULL DEFAULT 'info-1;;daily-1;;newest-1;;newest_count-3;;games-1;;games_count-40;;online-1',
  `ina_char_name` text NOT NULL DEFAULT '',
  `ina_char_age` int(10) NOT NULL DEFAULT 1,
  `ina_char_from` varchar(255) NOT NULL DEFAULT '',
  `ina_char_intrests` varchar(255) NOT NULL DEFAULT '',
  `ina_char_img` varchar(255) NOT NULL DEFAULT '',
  `ina_char_gender` smallint(1) NOT NULL DEFAULT 1,
  `ina_char_ge` int(10) NOT NULL DEFAULT 0,
  `ina_char_name_effects` text NOT NULL DEFAULT '',
  `ina_char_title_effects` text NOT NULL DEFAULT '',
  `ina_char_saying_effects` text NOT NULL DEFAULT '',
  `ina_char_views` int(10) NOT NULL DEFAULT 0,
  `ina_char_title` varchar(255) NOT NULL DEFAULT '',
  `ina_char_saying` varchar(255) NOT NULL DEFAULT '',
  `user_login_tries` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `user_last_login_try` int(11) NOT NULL DEFAULT 0,
  `user_digest_status` tinyint(1) NOT NULL DEFAULT 0,
  `user_actviate_date` int(11) DEFAULT 0,
  `user_expire_date` int(11) DEFAULT 0,
  `user_jobs` int(11) NOT NULL DEFAULT 0,
  `user_ftr` smallint(1) NOT NULL DEFAULT 0,
  `user_ftr_time` int(11) NOT NULL DEFAULT 0,
  `email_validation` tinyint(1) NOT NULL DEFAULT 0,
  `user_topic_moved_mail` tinyint(1) DEFAULT 0,
  `user_topic_moved_pm` tinyint(1) DEFAULT 0,
  `user_topic_moved_pm_notify` tinyint(1) DEFAULT 0,
  `daily_post_count` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `daily_post_limit` mediumint(8) NOT NULL DEFAULT 0,
  `daily_post_period` int(11) NOT NULL DEFAULT 0,
  `user_wordwrap` smallint(3) NOT NULL DEFAULT 80,
  `user_allowsig` tinyint(1) NOT NULL DEFAULT 1,
  `group_priority` int(255) NOT NULL DEFAULT 0,
  `user_lastpassword` varchar(32) DEFAULT NULL,
  `user_lastpassword_time` int(11) DEFAULT NULL,
  `user_transition` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_users_comments`
--

CREATE TABLE `phpbb_users_comments` (
  `comment_id` mediumint(8) NOT NULL,
  `user_id` mediumint(8) NOT NULL,
  `poster_id` mediumint(8) NOT NULL,
  `comments` text NOT NULL,
  `time` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_user_group`
--

CREATE TABLE `phpbb_user_group` (
  `group_id` mediumint(8) NOT NULL DEFAULT 0,
  `user_id` mediumint(8) NOT NULL DEFAULT 0,
  `user_pending` tinyint(1) DEFAULT NULL,
  `digest_confirm_date` int(11) NOT NULL DEFAULT 0,
  `ug_expire_date` int(11) DEFAULT 0,
  `ug_active_date` int(11) DEFAULT 0,
  `group_moderator` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_user_notes`
--

CREATE TABLE `phpbb_user_notes` (
  `post_id` mediumint(8) UNSIGNED NOT NULL,
  `poster_id` mediumint(8) NOT NULL DEFAULT 0,
  `post_subject` varchar(120) DEFAULT NULL,
  `post_text` text DEFAULT NULL,
  `post_time` int(11) NOT NULL DEFAULT 0,
  `bbcode_uid` char(10) NOT NULL DEFAULT '',
  `bbcode` tinyint(1) NOT NULL DEFAULT 1,
  `smilies` tinyint(1) NOT NULL DEFAULT 1,
  `acronym` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_user_shops`
--

CREATE TABLE `phpbb_user_shops` (
  `id` int(5) NOT NULL,
  `user_id` mediumint(8) NOT NULL,
  `username` varchar(32) NOT NULL DEFAULT '',
  `shop_name` varchar(32) NOT NULL DEFAULT '',
  `shop_type` varchar(32) NOT NULL DEFAULT '',
  `shop_opened` int(30) NOT NULL,
  `shop_updated` int(30) NOT NULL,
  `shop_status` int(1) NOT NULL DEFAULT 0,
  `amount_earnt` int(10) NOT NULL DEFAULT 0,
  `amount_holding` int(10) NOT NULL DEFAULT 0,
  `items_sold` int(10) NOT NULL DEFAULT 0,
  `items_holding` int(10) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_user_shops_items`
--

CREATE TABLE `phpbb_user_shops_items` (
  `id` int(10) NOT NULL,
  `shop_id` int(10) NOT NULL,
  `item_id` int(10) NOT NULL,
  `seller_notes` varchar(255) NOT NULL DEFAULT '',
  `cost` int(10) NOT NULL,
  `time_added` mediumint(30) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_vote_desc`
--

CREATE TABLE `phpbb_vote_desc` (
  `vote_id` mediumint(8) UNSIGNED NOT NULL,
  `topic_id` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `vote_text` text NOT NULL DEFAULT '',
  `vote_start` int(11) NOT NULL DEFAULT 0,
  `vote_length` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_vote_results`
--

CREATE TABLE `phpbb_vote_results` (
  `vote_id` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `vote_option_id` tinyint(4) UNSIGNED NOT NULL DEFAULT 0,
  `vote_option_text` varchar(255) NOT NULL DEFAULT '',
  `vote_result` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_vote_voters`
--

CREATE TABLE `phpbb_vote_voters` (
  `vote_id` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `vote_user_id` mediumint(8) NOT NULL DEFAULT 0,
  `vote_user_ip` char(8) NOT NULL,
  `vote_cast` tinyint(4) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_words`
--

CREATE TABLE `phpbb_words` (
  `word_id` mediumint(8) UNSIGNED NOT NULL,
  `word` char(100) NOT NULL,
  `replacement` char(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_xdata_auth`
--

CREATE TABLE `phpbb_xdata_auth` (
  `field_id` smallint(5) UNSIGNED NOT NULL,
  `group_id` mediumint(8) UNSIGNED NOT NULL,
  `auth_value` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `phpbb_xdata_data`
--

CREATE TABLE `phpbb_xdata_data` (
  `field_id` smallint(5) UNSIGNED NOT NULL,
  `user_id` mediumint(8) UNSIGNED NOT NULL,
  `xdata_value` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phpbb_xdata_fields`
--

CREATE TABLE `phpbb_xdata_fields` (
  `field_id` smallint(5) UNSIGNED NOT NULL,
  `field_name` varchar(255) NOT NULL DEFAULT '',
  `field_desc` text NOT NULL DEFAULT '',
  `field_type` varchar(255) NOT NULL DEFAULT '',
  `field_order` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `code_name` varchar(255) NOT NULL DEFAULT '',
  `field_length` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `field_values` text NOT NULL DEFAULT '',
  `field_regexp` text NOT NULL DEFAULT '',
  `default_auth` tinyint(1) NOT NULL DEFAULT 1,
  `display_register` tinyint(1) NOT NULL DEFAULT 1,
  `display_viewprofile` tinyint(1) NOT NULL DEFAULT 0,
  `display_posting` tinyint(1) NOT NULL DEFAULT 0,
  `handle_input` tinyint(1) NOT NULL DEFAULT 0,
  `allow_html` tinyint(1) NOT NULL DEFAULT 0,
  `allow_bbcode` tinyint(1) NOT NULL DEFAULT 0,
  `allow_smilies` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `phpbb_advance_html`
--
ALTER TABLE `phpbb_advance_html`
  ADD PRIMARY KEY (`config_name`);

--
-- Indexes for table `phpbb_album`
--
ALTER TABLE `phpbb_album`
  ADD PRIMARY KEY (`pic_id`),
  ADD KEY `pic_cat_id` (`pic_cat_id`),
  ADD KEY `pic_user_id` (`pic_user_id`),
  ADD KEY `pic_time` (`pic_time`);

--
-- Indexes for table `phpbb_album_cat`
--
ALTER TABLE `phpbb_album_cat`
  ADD PRIMARY KEY (`cat_id`),
  ADD KEY `cat_order` (`cat_order`);

--
-- Indexes for table `phpbb_album_comment`
--
ALTER TABLE `phpbb_album_comment`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `comment_pic_id` (`comment_pic_id`),
  ADD KEY `comment_user_id` (`comment_user_id`),
  ADD KEY `comment_user_ip` (`comment_user_ip`),
  ADD KEY `comment_time` (`comment_time`);

--
-- Indexes for table `phpbb_album_config`
--
ALTER TABLE `phpbb_album_config`
  ADD PRIMARY KEY (`config_name`);

--
-- Indexes for table `phpbb_album_rate`
--
ALTER TABLE `phpbb_album_rate`
  ADD KEY `rate_pic_id` (`rate_pic_id`),
  ADD KEY `rate_user_ip` (`rate_user_ip`),
  ADD KEY `rate_point` (`rate_point`);

--
-- Indexes for table `phpbb_attachments`
--
ALTER TABLE `phpbb_attachments`
  ADD KEY `attach_id_post_id` (`attach_id`,`post_id`),
  ADD KEY `attach_id_privmsgs_id` (`attach_id`,`privmsgs_id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `privmsgs_id` (`privmsgs_id`);

--
-- Indexes for table `phpbb_attachments_config`
--
ALTER TABLE `phpbb_attachments_config`
  ADD PRIMARY KEY (`config_name`);

--
-- Indexes for table `phpbb_attachments_desc`
--
ALTER TABLE `phpbb_attachments_desc`
  ADD PRIMARY KEY (`attach_id`),
  ADD KEY `filetime` (`filetime`),
  ADD KEY `physical_filename` (`physical_filename`(10)),
  ADD KEY `filesize` (`filesize`),
  ADD KEY `filetime_2` (`filetime`),
  ADD KEY `physical_filename_2` (`physical_filename`(10)),
  ADD KEY `filesize_2` (`filesize`);

--
-- Indexes for table `phpbb_attach_quota`
--
ALTER TABLE `phpbb_attach_quota`
  ADD KEY `quota_type` (`quota_type`);

--
-- Indexes for table `phpbb_auth_access`
--
ALTER TABLE `phpbb_auth_access`
  ADD KEY `group_id` (`group_id`),
  ADD KEY `forum_id` (`forum_id`);

--
-- Indexes for table `phpbb_avatartoplist`
--
ALTER TABLE `phpbb_avatartoplist`
  ADD KEY `voter_id` (`voter_id`);

--
-- Indexes for table `phpbb_bank`
--
ALTER TABLE `phpbb_bank`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `phpbb_banlist`
--
ALTER TABLE `phpbb_banlist`
  ADD PRIMARY KEY (`ban_id`),
  ADD KEY `ban_ip_user_id` (`ban_ip`,`ban_userid`);

--
-- Indexes for table `phpbb_banned_sites`
--
ALTER TABLE `phpbb_banned_sites`
  ADD KEY `site_id` (`site_id`);

--
-- Indexes for table `phpbb_banned_visitors`
--
ALTER TABLE `phpbb_banned_visitors`
  ADD KEY `count` (`count`);

--
-- Indexes for table `phpbb_banner`
--
ALTER TABLE `phpbb_banner`
  ADD PRIMARY KEY (`banner_id`),
  ADD KEY `banner_active` (`banner_active`),
  ADD KEY `banner_level` (`banner_level`),
  ADD KEY `banner_timetype` (`banner_timetype`);

--
-- Indexes for table `phpbb_bookie_admin_bets`
--
ALTER TABLE `phpbb_bookie_admin_bets`
  ADD KEY `bet_id` (`bet_id`);

--
-- Indexes for table `phpbb_bookie_bets`
--
ALTER TABLE `phpbb_bookie_bets`
  ADD PRIMARY KEY (`bet_id`);

--
-- Indexes for table `phpbb_bookie_categories`
--
ALTER TABLE `phpbb_bookie_categories`
  ADD PRIMARY KEY (`cat_id`);

--
-- Indexes for table `phpbb_bookie_meetings`
--
ALTER TABLE `phpbb_bookie_meetings`
  ADD PRIMARY KEY (`meeting_id`);

--
-- Indexes for table `phpbb_bookie_selections`
--
ALTER TABLE `phpbb_bookie_selections`
  ADD PRIMARY KEY (`selection_id`);

--
-- Indexes for table `phpbb_bots`
--
ALTER TABLE `phpbb_bots`
  ADD PRIMARY KEY (`bot_id`);

--
-- Indexes for table `phpbb_bots_archive`
--
ALTER TABLE `phpbb_bots_archive`
  ADD PRIMARY KEY (`bot_id`);

--
-- Indexes for table `phpbb_categories`
--
ALTER TABLE `phpbb_categories`
  ADD PRIMARY KEY (`cat_id`),
  ADD KEY `cat_order` (`cat_order`);

--
-- Indexes for table `phpbb_cat_rel_cat_parents`
--
ALTER TABLE `phpbb_cat_rel_cat_parents`
  ADD PRIMARY KEY (`cat_id`,`parent_cat_id`);

--
-- Indexes for table `phpbb_cat_rel_forum_parents`
--
ALTER TABLE `phpbb_cat_rel_forum_parents`
  ADD PRIMARY KEY (`cat_id`,`parent_forum_id`);

--
-- Indexes for table `phpbb_charts`
--
ALTER TABLE `phpbb_charts`
  ADD PRIMARY KEY (`chart_id`);

--
-- Indexes for table `phpbb_charts_voters`
--
ALTER TABLE `phpbb_charts_voters`
  ADD PRIMARY KEY (`vote_id`),
  ADD KEY `vote_chart_id` (`vote_chart_id`);

--
-- Indexes for table `phpbb_chatbox`
--
ALTER TABLE `phpbb_chatbox`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `phpbb_chatbox_session`
--
ALTER TABLE `phpbb_chatbox_session`
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `phpbb_config`
--
ALTER TABLE `phpbb_config`
  ADD PRIMARY KEY (`config_name`);

--
-- Indexes for table `phpbb_config_nav`
--
ALTER TABLE `phpbb_config_nav`
  ADD PRIMARY KEY (`navlink_id`);

--
-- Indexes for table `phpbb_confirm`
--
ALTER TABLE `phpbb_confirm`
  ADD PRIMARY KEY (`session_id`,`confirm_id`);

--
-- Indexes for table `phpbb_digests`
--
ALTER TABLE `phpbb_digests`
  ADD PRIMARY KEY (`digest_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `phpbb_digests_config`
--
ALTER TABLE `phpbb_digests_config`
  ADD PRIMARY KEY (`config_name`);

--
-- Indexes for table `phpbb_digests_forums`
--
ALTER TABLE `phpbb_digests_forums`
  ADD KEY `user_id` (`user_id`),
  ADD KEY `forum_id` (`forum_id`);

--
-- Indexes for table `phpbb_digests_log`
--
ALTER TABLE `phpbb_digests_log`
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `phpbb_disallow`
--
ALTER TABLE `phpbb_disallow`
  ADD PRIMARY KEY (`disallow_id`);

--
-- Indexes for table `phpbb_extensions`
--
ALTER TABLE `phpbb_extensions`
  ADD PRIMARY KEY (`ext_id`);

--
-- Indexes for table `phpbb_extension_groups`
--
ALTER TABLE `phpbb_extension_groups`
  ADD PRIMARY KEY (`group_id`);

--
-- Indexes for table `phpbb_flags`
--
ALTER TABLE `phpbb_flags`
  ADD PRIMARY KEY (`flag_id`);

--
-- Indexes for table `phpbb_forbidden_extensions`
--
ALTER TABLE `phpbb_forbidden_extensions`
  ADD PRIMARY KEY (`ext_id`);

--
-- Indexes for table `phpbb_forums`
--
ALTER TABLE `phpbb_forums`
  ADD PRIMARY KEY (`forum_id`),
  ADD KEY `forums_order` (`forum_order`),
  ADD KEY `cat_id` (`cat_id`),
  ADD KEY `forum_last_post_id` (`forum_last_post_id`);

--
-- Indexes for table `phpbb_forums_watch`
--
ALTER TABLE `phpbb_forums_watch`
  ADD KEY `forum_id` (`forum_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `notify_status` (`notify_status`);

--
-- Indexes for table `phpbb_forum_move`
--
ALTER TABLE `phpbb_forum_move`
  ADD PRIMARY KEY (`move_id`),
  ADD KEY `forum_id` (`forum_id`);

--
-- Indexes for table `phpbb_forum_prune`
--
ALTER TABLE `phpbb_forum_prune`
  ADD PRIMARY KEY (`prune_id`),
  ADD KEY `forum_id` (`forum_id`);

--
-- Indexes for table `phpbb_forum_tour`
--
ALTER TABLE `phpbb_forum_tour`
  ADD KEY `page_id` (`page_id`);

--
-- Indexes for table `phpbb_groups`
--
ALTER TABLE `phpbb_groups`
  ADD PRIMARY KEY (`group_id`),
  ADD KEY `group_single_user` (`group_single_user`);

--
-- Indexes for table `phpbb_guestbook`
--
ALTER TABLE `phpbb_guestbook`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `phpbb_guestbook_config`
--
ALTER TABLE `phpbb_guestbook_config`
  ADD PRIMARY KEY (`config_name`);

--
-- Indexes for table `phpbb_im_buddy_list`
--
ALTER TABLE `phpbb_im_buddy_list`
  ADD KEY `contact_id` (`contact_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `phpbb_im_config`
--
ALTER TABLE `phpbb_im_config`
  ADD PRIMARY KEY (`config_name`);

--
-- Indexes for table `phpbb_im_prefs`
--
ALTER TABLE `phpbb_im_prefs`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `phpbb_im_sessions`
--
ALTER TABLE `phpbb_im_sessions`
  ADD PRIMARY KEY (`session_id`),
  ADD KEY `session_user_id` (`session_user_id`);

--
-- Indexes for table `phpbb_im_sites`
--
ALTER TABLE `phpbb_im_sites`
  ADD PRIMARY KEY (`site_id`);

--
-- Indexes for table `phpbb_ina_categories`
--
ALTER TABLE `phpbb_ina_categories`
  ADD PRIMARY KEY (`cat_id`);

--
-- Indexes for table `phpbb_ina_games`
--
ALTER TABLE `phpbb_ina_games`
  ADD PRIMARY KEY (`game_id`);

--
-- Indexes for table `phpbb_inline_ads`
--
ALTER TABLE `phpbb_inline_ads`
  ADD PRIMARY KEY (`ad_id`);

--
-- Indexes for table `phpbb_ip`
--
ALTER TABLE `phpbb_ip`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`);

--
-- Indexes for table `phpbb_jobs`
--
ALTER TABLE `phpbb_jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`);

--
-- Indexes for table `phpbb_jobs_employed`
--
ALTER TABLE `phpbb_jobs_employed`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `phpbb_kb_articles`
--
ALTER TABLE `phpbb_kb_articles`
  ADD KEY `article_id` (`article_id`);

--
-- Indexes for table `phpbb_kb_categories`
--
ALTER TABLE `phpbb_kb_categories`
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `phpbb_kb_results`
--
ALTER TABLE `phpbb_kb_results`
  ADD PRIMARY KEY (`search_id`),
  ADD KEY `session_id` (`session_id`);

--
-- Indexes for table `phpbb_kb_types`
--
ALTER TABLE `phpbb_kb_types`
  ADD KEY `id` (`id`);

--
-- Indexes for table `phpbb_kb_wordlist`
--
ALTER TABLE `phpbb_kb_wordlist`
  ADD PRIMARY KEY (`word_text`),
  ADD KEY `word_id` (`word_id`);

--
-- Indexes for table `phpbb_kb_wordmatch`
--
ALTER TABLE `phpbb_kb_wordmatch`
  ADD KEY `post_id` (`article_id`),
  ADD KEY `word_id` (`word_id`);

--
-- Indexes for table `phpbb_lexicon`
--
ALTER TABLE `phpbb_lexicon`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cat` (`cat`);

--
-- Indexes for table `phpbb_lexicon_cat`
--
ALTER TABLE `phpbb_lexicon_cat`
  ADD PRIMARY KEY (`cat_id`);

--
-- Indexes for table `phpbb_links`
--
ALTER TABLE `phpbb_links`
  ADD PRIMARY KEY (`link_id`);

--
-- Indexes for table `phpbb_link_categories`
--
ALTER TABLE `phpbb_link_categories`
  ADD PRIMARY KEY (`cat_id`),
  ADD KEY `cat_order` (`cat_order`);

--
-- Indexes for table `phpbb_link_comments`
--
ALTER TABLE `phpbb_link_comments`
  ADD PRIMARY KEY (`comments_id`);
ALTER TABLE `phpbb_link_comments` ADD FULLTEXT KEY `comment_bbcode_uid` (`comment_bbcode_uid`);

--
-- Indexes for table `phpbb_link_config`
--
ALTER TABLE `phpbb_link_config`
  ADD PRIMARY KEY (`config_name`);

--
-- Indexes for table `phpbb_link_custom`
--
ALTER TABLE `phpbb_link_custom`
  ADD PRIMARY KEY (`custom_id`);

--
-- Indexes for table `phpbb_link_votes`
--
ALTER TABLE `phpbb_link_votes`
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `phpbb_logs`
--
ALTER TABLE `phpbb_logs`
  ADD PRIMARY KEY (`id_log`);

--
-- Indexes for table `phpbb_lottery`
--
ALTER TABLE `phpbb_lottery`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `phpbb_lottery_history`
--
ALTER TABLE `phpbb_lottery_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `phpbb_medal`
--
ALTER TABLE `phpbb_medal`
  ADD PRIMARY KEY (`medal_id`);

--
-- Indexes for table `phpbb_medal_cat`
--
ALTER TABLE `phpbb_medal_cat`
  ADD PRIMARY KEY (`cat_id`),
  ADD KEY `cat_order` (`cat_order`);

--
-- Indexes for table `phpbb_medal_mod`
--
ALTER TABLE `phpbb_medal_mod`
  ADD PRIMARY KEY (`mod_id`);

--
-- Indexes for table `phpbb_medal_user`
--
ALTER TABLE `phpbb_medal_user`
  ADD PRIMARY KEY (`issue_id`);

--
-- Indexes for table `phpbb_meeting_comment`
--
ALTER TABLE `phpbb_meeting_comment`
  ADD PRIMARY KEY (`comment_id`);

--
-- Indexes for table `phpbb_meeting_config`
--
ALTER TABLE `phpbb_meeting_config`
  ADD PRIMARY KEY (`config_name`);

--
-- Indexes for table `phpbb_meeting_data`
--
ALTER TABLE `phpbb_meeting_data`
  ADD PRIMARY KEY (`meeting_id`);

--
-- Indexes for table `phpbb_modules`
--
ALTER TABLE `phpbb_modules`
  ADD PRIMARY KEY (`module_id`);

--
-- Indexes for table `phpbb_module_cache`
--
ALTER TABLE `phpbb_module_cache`
  ADD PRIMARY KEY (`module_id`);

--
-- Indexes for table `phpbb_module_info`
--
ALTER TABLE `phpbb_module_info`
  ADD PRIMARY KEY (`module_id`);

--
-- Indexes for table `phpbb_mycalendar`
--
ALTER TABLE `phpbb_mycalendar`
  ADD PRIMARY KEY (`cal_id`),
  ADD UNIQUE KEY `topic_id` (`topic_id`);

--
-- Indexes for table `phpbb_pages`
--
ALTER TABLE `phpbb_pages`
  ADD PRIMARY KEY (`page_id`),
  ADD UNIQUE KEY `page_key` (`page_key`);

--
-- Indexes for table `phpbb_pa_auth`
--
ALTER TABLE `phpbb_pa_auth`
  ADD KEY `group_id` (`group_id`),
  ADD KEY `cat_id` (`cat_id`);

--
-- Indexes for table `phpbb_pa_cat`
--
ALTER TABLE `phpbb_pa_cat`
  ADD PRIMARY KEY (`cat_id`);

--
-- Indexes for table `phpbb_pa_comments`
--
ALTER TABLE `phpbb_pa_comments`
  ADD PRIMARY KEY (`comments_id`);
ALTER TABLE `phpbb_pa_comments` ADD FULLTEXT KEY `comment_bbcode_uid` (`comment_bbcode_uid`);

--
-- Indexes for table `phpbb_pa_config`
--
ALTER TABLE `phpbb_pa_config`
  ADD PRIMARY KEY (`config_name`);

--
-- Indexes for table `phpbb_pa_custom`
--
ALTER TABLE `phpbb_pa_custom`
  ADD PRIMARY KEY (`custom_id`);

--
-- Indexes for table `phpbb_pa_files`
--
ALTER TABLE `phpbb_pa_files`
  ADD PRIMARY KEY (`file_id`);

--
-- Indexes for table `phpbb_pa_license`
--
ALTER TABLE `phpbb_pa_license`
  ADD PRIMARY KEY (`license_id`);

--
-- Indexes for table `phpbb_pa_mirrors`
--
ALTER TABLE `phpbb_pa_mirrors`
  ADD PRIMARY KEY (`mirror_id`),
  ADD KEY `file_id` (`file_id`);

--
-- Indexes for table `phpbb_pa_votes`
--
ALTER TABLE `phpbb_pa_votes`
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `phpbb_pjirc`
--
ALTER TABLE `phpbb_pjirc`
  ADD PRIMARY KEY (`pjirc_name`);

--
-- Indexes for table `phpbb_points_logger`
--
ALTER TABLE `phpbb_points_logger`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `phpbb_portal`
--
ALTER TABLE `phpbb_portal`
  ADD PRIMARY KEY (`portal_id`);

--
-- Indexes for table `phpbb_posts`
--
ALTER TABLE `phpbb_posts`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `forum_id` (`forum_id`),
  ADD KEY `topic_id` (`topic_id`),
  ADD KEY `poster_id` (`poster_id`),
  ADD KEY `post_time` (`post_time`),
  ADD KEY `posts_ratingrankid` (`rating_rank_id`);

--
-- Indexes for table `phpbb_posts_edit`
--
ALTER TABLE `phpbb_posts_edit`
  ADD KEY `post_id` (`post_id`);

--
-- Indexes for table `phpbb_posts_text`
--
ALTER TABLE `phpbb_posts_text`
  ADD PRIMARY KEY (`post_id`);

--
-- Indexes for table `phpbb_privmsgs`
--
ALTER TABLE `phpbb_privmsgs`
  ADD PRIMARY KEY (`privmsgs_id`),
  ADD KEY `privmsgs_from_userid` (`privmsgs_from_userid`),
  ADD KEY `privmsgs_to_userid` (`privmsgs_to_userid`),
  ADD KEY `room_id` (`room_id`),
  ADD KEY `site_id` (`site_id`);

--
-- Indexes for table `phpbb_privmsgs_archive`
--
ALTER TABLE `phpbb_privmsgs_archive`
  ADD PRIMARY KEY (`privmsgs_id`),
  ADD KEY `privmsgs_from_userid` (`privmsgs_from_userid`),
  ADD KEY `privmsgs_to_userid` (`privmsgs_to_userid`);

--
-- Indexes for table `phpbb_privmsgs_text`
--
ALTER TABLE `phpbb_privmsgs_text`
  ADD PRIMARY KEY (`privmsgs_text_id`);

--
-- Indexes for table `phpbb_quota_limits`
--
ALTER TABLE `phpbb_quota_limits`
  ADD PRIMARY KEY (`quota_limit_id`);

--
-- Indexes for table `phpbb_ranks`
--
ALTER TABLE `phpbb_ranks`
  ADD PRIMARY KEY (`rank_id`);

--
-- Indexes for table `phpbb_rating`
--
ALTER TABLE `phpbb_rating`
  ADD KEY `rating_postid` (`post_id`),
  ADD KEY `rating_userid` (`user_id`),
  ADD KEY `rating_ratingoptionid` (`option_id`);

--
-- Indexes for table `phpbb_rating_bias`
--
ALTER TABLE `phpbb_rating_bias`
  ADD PRIMARY KEY (`bias_id`),
  ADD KEY `ratingbias_userid_targetuser` (`user_id`,`target_user`),
  ADD KEY `ratingbias_targetuser` (`target_user`),
  ADD KEY `ratingbias_postid` (`post_id`),
  ADD KEY `ratingbias_optionid` (`option_id`);

--
-- Indexes for table `phpbb_rating_config`
--
ALTER TABLE `phpbb_rating_config`
  ADD PRIMARY KEY (`config_id`);

--
-- Indexes for table `phpbb_rating_option`
--
ALTER TABLE `phpbb_rating_option`
  ADD PRIMARY KEY (`option_id`),
  ADD KEY `ratingoption_rating` (`points`),
  ADD KEY `ratingoption_weighting` (`weighting`);

--
-- Indexes for table `phpbb_rating_rank`
--
ALTER TABLE `phpbb_rating_rank`
  ADD PRIMARY KEY (`rating_rank_id`),
  ADD KEY `ratingrank_type` (`type`);

--
-- Indexes for table `phpbb_rating_temp`
--
ALTER TABLE `phpbb_rating_temp`
  ADD KEY `ratingtemp_topicid` (`topic_id`);

--
-- Indexes for table `phpbb_referers`
--
ALTER TABLE `phpbb_referers`
  ADD PRIMARY KEY (`referer_id`);

--
-- Indexes for table `phpbb_referral`
--
ALTER TABLE `phpbb_referral`
  ADD KEY `referraler_id` (`referral_id`);

--
-- Indexes for table `phpbb_search_results`
--
ALTER TABLE `phpbb_search_results`
  ADD PRIMARY KEY (`search_id`),
  ADD KEY `session_id` (`session_id`);

--
-- Indexes for table `phpbb_search_wordlist`
--
ALTER TABLE `phpbb_search_wordlist`
  ADD PRIMARY KEY (`word_text`),
  ADD KEY `word_id` (`word_id`);

--
-- Indexes for table `phpbb_search_wordmatch`
--
ALTER TABLE `phpbb_search_wordmatch`
  ADD KEY `post_id` (`post_id`),
  ADD KEY `word_id` (`word_id`);

--
-- Indexes for table `phpbb_sessions`
--
ALTER TABLE `phpbb_sessions`
  ADD PRIMARY KEY (`session_id`),
  ADD KEY `session_user_id` (`session_user_id`),
  ADD KEY `session_id_ip_user_id` (`session_id`,`session_ip`,`session_user_id`);

--
-- Indexes for table `phpbb_sessions_keys`
--
ALTER TABLE `phpbb_sessions_keys`
  ADD PRIMARY KEY (`key_id`,`user_id`),
  ADD KEY `last_login` (`last_login`);

--
-- Indexes for table `phpbb_shops`
--
ALTER TABLE `phpbb_shops`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shopname` (`shopname`);

--
-- Indexes for table `phpbb_shop_items`
--
ALTER TABLE `phpbb_shop_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`);

--
-- Indexes for table `phpbb_shop_transactions`
--
ALTER TABLE `phpbb_shop_transactions`
  ADD PRIMARY KEY (`shoptrans_date`);

--
-- Indexes for table `phpbb_shout`
--
ALTER TABLE `phpbb_shout`
  ADD PRIMARY KEY (`shout_id`);

--
-- Indexes for table `phpbb_smilies`
--
ALTER TABLE `phpbb_smilies`
  ADD PRIMARY KEY (`smilies_id`);

--
-- Indexes for table `phpbb_smilies_cat`
--
ALTER TABLE `phpbb_smilies_cat`
  ADD PRIMARY KEY (`cat_id`);

--
-- Indexes for table `phpbb_themes`
--
ALTER TABLE `phpbb_themes`
  ADD PRIMARY KEY (`themes_id`);

--
-- Indexes for table `phpbb_themes_name`
--
ALTER TABLE `phpbb_themes_name`
  ADD PRIMARY KEY (`themes_id`);

--
-- Indexes for table `phpbb_thread_kicker`
--
ALTER TABLE `phpbb_thread_kicker`
  ADD PRIMARY KEY (`kick_id`);

--
-- Indexes for table `phpbb_title_infos`
--
ALTER TABLE `phpbb_title_infos`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `phpbb_topics`
--
ALTER TABLE `phpbb_topics`
  ADD PRIMARY KEY (`topic_id`),
  ADD KEY `forum_id` (`forum_id`),
  ADD KEY `topic_moved_id` (`topic_moved_id`),
  ADD KEY `topic_status` (`topic_status`),
  ADD KEY `topic_type` (`topic_type`),
  ADD KEY `topics_ratingrankid` (`rating_rank_id`);
ALTER TABLE `phpbb_topics` ADD FULLTEXT KEY `topic_title` (`topic_title`);

--
-- Indexes for table `phpbb_topics_viewdata`
--
ALTER TABLE `phpbb_topics_viewdata`
  ADD PRIMARY KEY (`viewed_id`),
  ADD KEY `user_id` (`user_id`,`topic_id`),
  ADD KEY `last_viewed` (`last_viewed`);

--
-- Indexes for table `phpbb_topics_watch`
--
ALTER TABLE `phpbb_topics_watch`
  ADD KEY `topic_id` (`topic_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `notify_status` (`notify_status`);

--
-- Indexes for table `phpbb_toplist`
--
ALTER TABLE `phpbb_toplist`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `phpbb_transactions`
--
ALTER TABLE `phpbb_transactions`
  ADD PRIMARY KEY (`trans_date`);

--
-- Indexes for table `phpbb_unique_hits`
--
ALTER TABLE `phpbb_unique_hits`
  ADD KEY `user_ip` (`user_ip`);

--
-- Indexes for table `phpbb_users`
--
ALTER TABLE `phpbb_users`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `user_session_time` (`user_session_time`);
ALTER TABLE `phpbb_users` ADD FULLTEXT KEY `user_skype` (`user_skype`);

--
-- Indexes for table `phpbb_users_comments`
--
ALTER TABLE `phpbb_users_comments`
  ADD PRIMARY KEY (`comment_id`);

--
-- Indexes for table `phpbb_user_group`
--
ALTER TABLE `phpbb_user_group`
  ADD KEY `group_id` (`group_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `phpbb_user_notes`
--
ALTER TABLE `phpbb_user_notes`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `poster_id` (`poster_id`),
  ADD KEY `post_time` (`post_time`);

--
-- Indexes for table `phpbb_user_shops`
--
ALTER TABLE `phpbb_user_shops`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `phpbb_user_shops_items`
--
ALTER TABLE `phpbb_user_shops_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shop_id` (`shop_id`);

--
-- Indexes for table `phpbb_vote_desc`
--
ALTER TABLE `phpbb_vote_desc`
  ADD PRIMARY KEY (`vote_id`),
  ADD KEY `topic_id` (`topic_id`);

--
-- Indexes for table `phpbb_vote_results`
--
ALTER TABLE `phpbb_vote_results`
  ADD KEY `vote_option_id` (`vote_option_id`),
  ADD KEY `vote_id` (`vote_id`);

--
-- Indexes for table `phpbb_vote_voters`
--
ALTER TABLE `phpbb_vote_voters`
  ADD KEY `vote_id` (`vote_id`),
  ADD KEY `vote_user_id` (`vote_user_id`),
  ADD KEY `vote_user_ip` (`vote_user_ip`);

--
-- Indexes for table `phpbb_words`
--
ALTER TABLE `phpbb_words`
  ADD PRIMARY KEY (`word_id`);

--
-- Indexes for table `phpbb_xdata_fields`
--
ALTER TABLE `phpbb_xdata_fields`
  ADD PRIMARY KEY (`field_id`),
  ADD UNIQUE KEY `code_name` (`code_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `phpbb_album`
--
ALTER TABLE `phpbb_album`
  MODIFY `pic_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_album_cat`
--
ALTER TABLE `phpbb_album_cat`
  MODIFY `cat_id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_album_comment`
--
ALTER TABLE `phpbb_album_comment`
  MODIFY `comment_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_attachments_desc`
--
ALTER TABLE `phpbb_attachments_desc`
  MODIFY `attach_id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_banlist`
--
ALTER TABLE `phpbb_banlist`
  MODIFY `ban_id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_banned_sites`
--
ALTER TABLE `phpbb_banned_sites`
  MODIFY `site_id` int(15) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_banned_visitors`
--
ALTER TABLE `phpbb_banned_visitors`
  MODIFY `count` int(15) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_bookie_admin_bets`
--
ALTER TABLE `phpbb_bookie_admin_bets`
  MODIFY `bet_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_bookie_bets`
--
ALTER TABLE `phpbb_bookie_bets`
  MODIFY `bet_id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_bookie_categories`
--
ALTER TABLE `phpbb_bookie_categories`
  MODIFY `cat_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_bookie_meetings`
--
ALTER TABLE `phpbb_bookie_meetings`
  MODIFY `meeting_id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_bookie_selections`
--
ALTER TABLE `phpbb_bookie_selections`
  MODIFY `selection_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_bots`
--
ALTER TABLE `phpbb_bots`
  MODIFY `bot_id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_bots_archive`
--
ALTER TABLE `phpbb_bots_archive`
  MODIFY `bot_id` mediumint(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_categories`
--
ALTER TABLE `phpbb_categories`
  MODIFY `cat_id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_charts`
--
ALTER TABLE `phpbb_charts`
  MODIFY `chart_id` mediumint(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_charts_voters`
--
ALTER TABLE `phpbb_charts_voters`
  MODIFY `vote_id` mediumint(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_chatbox`
--
ALTER TABLE `phpbb_chatbox`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_config_nav`
--
ALTER TABLE `phpbb_config_nav`
  MODIFY `navlink_id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_digests`
--
ALTER TABLE `phpbb_digests`
  MODIFY `digest_id` int(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_disallow`
--
ALTER TABLE `phpbb_disallow`
  MODIFY `disallow_id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_extensions`
--
ALTER TABLE `phpbb_extensions`
  MODIFY `ext_id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_extension_groups`
--
ALTER TABLE `phpbb_extension_groups`
  MODIFY `group_id` mediumint(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_flags`
--
ALTER TABLE `phpbb_flags`
  MODIFY `flag_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_forbidden_extensions`
--
ALTER TABLE `phpbb_forbidden_extensions`
  MODIFY `ext_id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_forums`
--
ALTER TABLE `phpbb_forums`
  MODIFY `forum_id` smallint(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_forum_move`
--
ALTER TABLE `phpbb_forum_move`
  MODIFY `move_id` mediumint(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_forum_prune`
--
ALTER TABLE `phpbb_forum_prune`
  MODIFY `prune_id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_groups`
--
ALTER TABLE `phpbb_groups`
  MODIFY `group_id` mediumint(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_guestbook`
--
ALTER TABLE `phpbb_guestbook`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_im_sites`
--
ALTER TABLE `phpbb_im_sites`
  MODIFY `site_id` mediumint(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_ina_categories`
--
ALTER TABLE `phpbb_ina_categories`
  MODIFY `cat_id` mediumint(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_ina_games`
--
ALTER TABLE `phpbb_ina_games`
  MODIFY `game_id` mediumint(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_inline_ads`
--
ALTER TABLE `phpbb_inline_ads`
  MODIFY `ad_id` tinyint(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_ip`
--
ALTER TABLE `phpbb_ip`
  MODIFY `id` mediumint(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_jobs`
--
ALTER TABLE `phpbb_jobs`
  MODIFY `id` mediumint(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_jobs_employed`
--
ALTER TABLE `phpbb_jobs_employed`
  MODIFY `id` mediumint(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_kb_articles`
--
ALTER TABLE `phpbb_kb_articles`
  MODIFY `article_id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_kb_categories`
--
ALTER TABLE `phpbb_kb_categories`
  MODIFY `category_id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_kb_types`
--
ALTER TABLE `phpbb_kb_types`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_kb_wordlist`
--
ALTER TABLE `phpbb_kb_wordlist`
  MODIFY `word_id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_lexicon`
--
ALTER TABLE `phpbb_lexicon`
  MODIFY `id` bigint(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_lexicon_cat`
--
ALTER TABLE `phpbb_lexicon_cat`
  MODIFY `cat_id` int(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_links`
--
ALTER TABLE `phpbb_links`
  MODIFY `link_id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_link_categories`
--
ALTER TABLE `phpbb_link_categories`
  MODIFY `cat_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_link_comments`
--
ALTER TABLE `phpbb_link_comments`
  MODIFY `comments_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_link_custom`
--
ALTER TABLE `phpbb_link_custom`
  MODIFY `custom_id` int(50) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_logs`
--
ALTER TABLE `phpbb_logs`
  MODIFY `id_log` mediumint(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_lottery`
--
ALTER TABLE `phpbb_lottery`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_lottery_history`
--
ALTER TABLE `phpbb_lottery_history`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_medal`
--
ALTER TABLE `phpbb_medal`
  MODIFY `medal_id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_medal_cat`
--
ALTER TABLE `phpbb_medal_cat`
  MODIFY `cat_id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_medal_mod`
--
ALTER TABLE `phpbb_medal_mod`
  MODIFY `mod_id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_medal_user`
--
ALTER TABLE `phpbb_medal_user`
  MODIFY `issue_id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_meeting_comment`
--
ALTER TABLE `phpbb_meeting_comment`
  MODIFY `comment_id` mediumint(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_modules`
--
ALTER TABLE `phpbb_modules`
  MODIFY `module_id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_mycalendar`
--
ALTER TABLE `phpbb_mycalendar`
  MODIFY `cal_id` int(12) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_pages`
--
ALTER TABLE `phpbb_pages`
  MODIFY `page_id` mediumint(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_pa_cat`
--
ALTER TABLE `phpbb_pa_cat`
  MODIFY `cat_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_pa_comments`
--
ALTER TABLE `phpbb_pa_comments`
  MODIFY `comments_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_pa_custom`
--
ALTER TABLE `phpbb_pa_custom`
  MODIFY `custom_id` int(50) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_pa_files`
--
ALTER TABLE `phpbb_pa_files`
  MODIFY `file_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_pa_license`
--
ALTER TABLE `phpbb_pa_license`
  MODIFY `license_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_pa_mirrors`
--
ALTER TABLE `phpbb_pa_mirrors`
  MODIFY `mirror_id` mediumint(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_points_logger`
--
ALTER TABLE `phpbb_points_logger`
  MODIFY `id` mediumint(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_portal`
--
ALTER TABLE `phpbb_portal`
  MODIFY `portal_id` mediumint(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_posts`
--
ALTER TABLE `phpbb_posts`
  MODIFY `post_id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_privmsgs`
--
ALTER TABLE `phpbb_privmsgs`
  MODIFY `privmsgs_id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_privmsgs_archive`
--
ALTER TABLE `phpbb_privmsgs_archive`
  MODIFY `privmsgs_id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_quota_limits`
--
ALTER TABLE `phpbb_quota_limits`
  MODIFY `quota_limit_id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_ranks`
--
ALTER TABLE `phpbb_ranks`
  MODIFY `rank_id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_rating_bias`
--
ALTER TABLE `phpbb_rating_bias`
  MODIFY `bias_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_rating_option`
--
ALTER TABLE `phpbb_rating_option`
  MODIFY `option_id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_rating_rank`
--
ALTER TABLE `phpbb_rating_rank`
  MODIFY `rating_rank_id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_referers`
--
ALTER TABLE `phpbb_referers`
  MODIFY `referer_id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_referral`
--
ALTER TABLE `phpbb_referral`
  MODIFY `referral_id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_search_wordlist`
--
ALTER TABLE `phpbb_search_wordlist`
  MODIFY `word_id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_shops`
--
ALTER TABLE `phpbb_shops`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_shop_items`
--
ALTER TABLE `phpbb_shop_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_shout`
--
ALTER TABLE `phpbb_shout`
  MODIFY `shout_id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_smilies`
--
ALTER TABLE `phpbb_smilies`
  MODIFY `smilies_id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_smilies_cat`
--
ALTER TABLE `phpbb_smilies_cat`
  MODIFY `cat_id` smallint(3) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_thread_kicker`
--
ALTER TABLE `phpbb_thread_kicker`
  MODIFY `kick_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_title_infos`
--
ALTER TABLE `phpbb_title_infos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_topics`
--
ALTER TABLE `phpbb_topics`
  MODIFY `topic_id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_topics_viewdata`
--
ALTER TABLE `phpbb_topics_viewdata`
  MODIFY `viewed_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_toplist`
--
ALTER TABLE `phpbb_toplist`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_users_comments`
--
ALTER TABLE `phpbb_users_comments`
  MODIFY `comment_id` mediumint(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_user_notes`
--
ALTER TABLE `phpbb_user_notes`
  MODIFY `post_id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_user_shops`
--
ALTER TABLE `phpbb_user_shops`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_user_shops_items`
--
ALTER TABLE `phpbb_user_shops_items`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_vote_desc`
--
ALTER TABLE `phpbb_vote_desc`
  MODIFY `vote_id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phpbb_words`
--
ALTER TABLE `phpbb_words`
  MODIFY `word_id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;
