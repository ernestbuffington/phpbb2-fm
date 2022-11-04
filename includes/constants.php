<?php
/** 
*
* @package includes
* @version $Id: constants.php,v 1.47.2.5 2004/11/18 17:49:42 acydburn Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
	
if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}

// Required <- Do not change these values
define('PHP_REQ', '4.4.1');	// Min. PHP Version Requirement
define('TABLES_REQ', 199); // Tablecount
define('REGISTER_MODE', 'joinup'); // Registration Mode
define('LOG_ACTIONS_VERSION', '1.1.2');


// User Levels <- Do not change these values
define('DELETED', -1);
define('ANONYMOUS', -1);
define('USER', 0);
define('ADMIN', 1);
define('LESS_ADMIN', 2);
define('MOD', 3);


// User related <- Do not change these values
define('USER_ACTIVATION_NONE', 0);
define('USER_ACTIVATION_SELF', 1);
define('USER_ACTIVATION_ADMIN', 2);
define('USER_ACTIVATION_DISABLE', 3);

define('USER_REGISTRATION_NOTIFY_NONE', 0);
define('USER_REGISTRATION_NOTIFY_MOD', 2);
define('USER_REGISTRATION_NOTIFY_ADMIN', 1);

define('USER_AVATAR_NONE', 0);
define('USER_AVATAR_UPLOAD', 1);
define('USER_AVATAR_REMOTE', 2);
define('USER_AVATAR_GALLERY', 3);
define('USER_AVATAR_GENERATOR', 4);


// Group settings <- Do not change these values
define('GROUP_OPEN', 0);
define('GROUP_CLOSED', 1);
define('GROUP_HIDDEN', 2);
define('GROUP_PAYMENT', 3);

define('VIP_RANK_TITLE', 'VIP');
define('AUTH_PAID_VIEW', 25);


// Forum state <- Do not change these values
define('FORUM_UNLOCKED', 0);
define('FORUM_LOCKED', 1);
define('FORUM_WEBLOGS', $board_config['journal_forum_id']);

define('SHOW_FORUM', 0);
define('HIDE_FORUM', 1);
define('SHOW_CAT', 0);
define('HIDE_CAT', 1);

// Forum Thanks state <- Do not change these values
define('FORUM_UNTHANKABLE', 0);
define('FORUM_THANKABLE', 1);

// Forum issub-state <- Do not change these values
define('FORUM_ISNOSUB', 0);
define('FORUM_ISSUB', 1);


// Hierachie order <- Do not change these values
define('HIERARCHIE_NO_RESTRICTION', 0);
define('HIERARCHIE_NO_INFERIOR', 1); 


// Topic status <- Do not change these values
define('TOPIC_UNLOCKED', 0);
define('TOPIC_LOCKED', 1);
define('TOPIC_MOVED', 2);
define('TOPIC_LINKED', 3);
define('TOPIC_WATCH_NOTIFIED', 1);
define('TOPIC_WATCH_UN_NOTIFIED', 0);


// Topic types <- Do not change these values
define('POST_NORMAL', 0);
define('POST_STICKY', 1);
define('POST_ANNOUNCE', 2);
define('POST_GLOBAL_ANNOUNCE', 3);


// SQL codes <- Do not change these values
define('BEGIN_TRANSACTION', 1);
define('END_TRANSACTION', 2);


// Error codes <- Do not change these values
define('GENERAL_MESSAGE', 200);
define('GENERAL_ERROR', 202);
define('CRITICAL_MESSAGE', 203);
define('CRITICAL_ERROR', 204);


// Private messaging <- Do not change these values
define('PRIVMSGS_READ_MAIL', 0);
define('PRIVMSGS_NEW_MAIL', 1);
define('PRIVMSGS_SENT_MAIL', 2);
define('PRIVMSGS_SAVED_IN_MAIL', 3);
define('PRIVMSGS_SAVED_OUT_MAIL', 4);
define('PRIVMSGS_UNREAD_MAIL', 5);
define('PRIVMSGS_REPLY_MAIL', 6);


// URL PARAMETERS <- Do not change these values
define('POST_TOPIC_URL', 't');
define('POST_CAT_URL', 'c');
define('POST_FORUM_URL', 'f');
define('POST_USERS_URL', 'u');
define('POST_POST_URL', 'p');
define('POST_GROUPS_URL', 'g');
define('POST_HIERARCHIE_URL', 'h');
define('POST_PARENTFORUM_URL', 'pf');
define('STYLE_URL', 's');
define('POST_MEDAL_URL', 'm');
define('MEDAL_CAT_URL', 'mc');


// Session parameters <- Do not change these values
define('SESSION_METHOD_COOKIE', 100);
define('SESSION_METHOD_GET', 101);


// Page numbers for session handling <- Do not change these values
define('PAGE_INDEX', 0);
define('PAGE_LOGIN', -1);
define('PAGE_SEARCH', -2);
define('PAGE_REGISTER', -3);
define('PAGE_PROFILE', -4);
define('PAGE_VIEWPROFILE', -5);
define('PAGE_VIEWONLINE', -6);
define('PAGE_VIEWMEMBERS', -7); 
define('PAGE_FAQ', -8);
define('PAGE_POSTING', -9);
define('PAGE_PRIVMSGS', -10);
define('PAGE_GROUPCP', -11);
define('PAGE_SMILES', -12);
define('PAGE_TELLFRIEND', -13);
define('PAGE_LINKDB', -14);
define('PAGE_DOWNLOAD', -15);
define('PAGE_TOPIC_VIEW', -16);
define('PAGE_DIGEST', -17);
define('PAGE_STAFF', -18);
// 19 - 22 defined in mods/album/album_constants.php
define('PAGE_ATTACHMENTS', -23);
define('PAGE_BOOKIES', -24);
define('PAGE_BOOKIE_ALLSTATS', -25);
define('PAGE_BOOKIE_YOURSTATS', -26);
define('PAGE_CALENDAR', -27);
define('PAGE_BANK', -28);
define('PAGE_SHOP', -29);
define('PAGE_STATISTICS', -30);
define('PAGE_CARD', -31);
define('PAGE_RATINGS', -32);
define('PAGE_PORTAL', -33);
define('PAGE_CHATROOM', -34);
define('PAGE_IMLIST', -35);
define('PAGE_TOPLIST', -36);
define('PAGE_LOTTERY', -37);
define('PAGE_ACTIVITY', -38);
define('PAGE_PLAYING_GAMES', -39);
define('PAGE_CHARTS', -40);
define('PAGE_RSS', -41);
define('PAGE_KB', -42);
define('PAGE_BANLIST', -43);
define('PAGE_TOPICS_STARTED', -44);
define('PAGE_MEETING', -45);
define('PAGE_FORUM_TOUR', -46);
define('PAGE_HELPDESK', -47);
define('PAGE_THREAD_KICKER', -48);
define('PAGE_SHOUTBOX', -49); 
define('PAGE_SHOUTBOX_MAX', -49); 
define('PAGE_REDIRECT', -50);
define('PAGE_LEXICON', -51);
define('PAGE_SITEMAP', -52);
// 53 - 54 defined in mods/attachments/constants.php
define('PAGE_AUCTIONS', -55);
define('PAGE_MEDALS', -56);
define('PAGE_JOBS', -57);
define('PAGE_AVATAR_TOPLIST', -58);
define('PAGE_AVATAR_LIST', -59);
define('PAGE_GUESTBOOK', -60);
define('PAGE_ALBUM_RSS', -61);

define('PAGE_TOPIC_OFFSET', 5000);

define('PAGE_FMINDEX', -1000); // Used only @ phpBBFM for Main Page


// Auth settings <- Do not change these values
define('AUTH_LIST_ALL', 0);
define('AUTH_ALL', 0);

define('AUTH_REG', 1);
define('AUTH_ACL', 2);
define('AUTH_MOD', 3);
define('AUTH_ADMIN', 5);

define('AUTH_VIEW', 1);
define('AUTH_READ', 2);
define('AUTH_POST', 3);
define('AUTH_REPLY', 4);
define('AUTH_EDIT', 5);
define('AUTH_DELETE', 6);
define('AUTH_ANNOUNCE', 7);
define('AUTH_STICKY', 8);
define('AUTH_POLLCREATE', 9);
define('AUTH_VOTE', 10);
define('AUTH_ATTACH', 11);
define('AUTH_SUGGEST_EVENT', 12);
define('AUTH_GLOBALANNOUNCE', 13); 

define('PAGE_AUTH_GUEST', 0);
define('PAGE_AUTH_REG', 1);
define('PAGE_AUTH_PRIVATE', 2);
define('PAGE_AUTH_MOD', 3);
define('PAGE_AUTH_ADMIN', 4);

// Custom Profile Fields <- Do not change these values
define('XD_AUTH_ALLOW', 1);
define('XD_AUTH_DENY', 0);
define('XD_AUTH_DEFAULT', 2);

define('XD_DISPLAY_NORMAL', 1);
define('XD_DISPLAY_ROOT', 2);
define('XD_DISPLAY_NONE', 0);

// Site Announcements
define('ANNOUNCEMENTS_SHOW_ALL', 0);
define('ANNOUNCEMENTS_SHOW_REG', 1);
define('ANNOUNCEMENTS_SHOW_MOD', 2);
define('ANNOUNCEMENTS_SHOW_ADM', 3);
define('ANNOUNCEMENTS_SHOW_YES', 1);
define('ANNOUNCEMENTS_SHOW_NO', 0);
define('ANNOUNCEMENTS_GUEST_YES', 1);
define('ANNOUNCEMENTS_GUEST_NO', 0);

// Birthday zodiacs <- Do not change these values
$zodiacdates = array ('0101', '0120', '0121', '0219', '0220', '0320', '0321', '0420', '0421', '0520', '0521', '0621', '0622', '0722', '0723', '0823', '0824', '0922', '0923', '1022', '1023', '1122', '1123', '1221', '1222', '1231');
$zodiacs = array ('Capricorn','Aquarius', 'Pisces', 'Aries', 'Taurus', 'Gemini', 'Cancer', 'Leo', 'Virgo', 'Libra', 'Scorpio', 'Sagittarius','Capricorn');

// Table names <- Do not change these values
define('ACCT_HIST_TABLE', $table_prefix.'account_hist');
define('ADVANCE_HTML_TABLE', $table_prefix.'advance_html');
define('ATTACH_QUOTA_TABLE', $table_prefix.'attach_quota');
define('ATTACHMENTS_TABLE', $table_prefix.'attachments');
define('ATTACHMENTS_CONFIG_TABLE', $table_prefix.'attachments_config');
define('ATTACHMENTS_DESC_TABLE', $table_prefix.'attachments_desc');
define('AUTH_ACCESS_TABLE', $table_prefix.'auth_access');
define('BACKUP_TABLE', $table_prefix.'backup');
define('BANLIST_TABLE', $table_prefix.'banlist');
define('BANNED_SITES', $table_prefix.'banned_sites');
define('BANNED_VISITORS', $table_prefix.'banned_visitors');
define('BANVOTE_VOTERS_TABLE', $table_prefix.'banvote_voters');
define('BANK_TABLE', $table_prefix.'bank');
define('BANNERS_TABLE', $table_prefix.'banner');
define('BANNER_STATS_TABLE', $table_prefix.'banner_stats');
define('BOOKIE_ADMIN_BETS_TABLE', $table_prefix.'bookie_admin_bets');
define('BOOKIE_BETS_TABLE', $table_prefix.'bookie_bets');
define('BOOKIE_BET_SETTER_TABLE',  $table_prefix.'bookie_bet_setter');
define('BOOKIE_CAT_TABLE',  $table_prefix.'bookie_categories');
define('BOOKIE_MEETINGS_TABLE',  $table_prefix.'bookie_meetings');
define('BOOKIE_SELECTIONS_TABLE',  $table_prefix.'bookie_selections');
define('BOOKIE_SELECTIONS_DATA_TABLE',  $table_prefix.'bookie_selections_data');
define('BOOKIE_STATS_TABLE', $table_prefix.'bookie_stats');
define('BOTS_TABLE', $table_prefix.'bots');
define('BOTS_ARCHIVE_TABLE', $table_prefix.'bots_archive');
define('CAT_REL_CAT_PARENTS_TABLE', $table_prefix.'cat_rel_cat_parents');
define('CAT_REL_FORUM_PARENTS_TABLE', $table_prefix.'cat_rel_forum_parents');
define('CATEGORIES_TABLE', $table_prefix.'categories');
define('CHARTS_TABLE', $table_prefix.'charts');
define('CHARTS_VOTERS_TABLE', $table_prefix.'charts_voters');
define('CHATBOX_TABLE', $table_prefix.'chatbox');
define('CHATBOX_SESSION_TABLE', $table_prefix.'chatbox_session');
define('CONFIG_TABLE', $table_prefix.'config');
define('CONFIG_NAV_TABLE', $table_prefix.'config_nav');
define('CONFIRM_TABLE', $table_prefix.'confirm');
define('DIGEST_TABLE', $table_prefix.'digests');
define('DIGEST_CONFIG_TABLE', $table_prefix.'digests_config');
define('DIGEST_FORUMS_TABLE', $table_prefix.'digests_forums');
define('DIGEST_LOG_TABLE', $table_prefix.'digests_log');
define('DISALLOW_TABLE', $table_prefix.'disallow');
define('EXTENSION_GROUPS_TABLE', $table_prefix.'extension_groups');
define('EXTENSIONS_TABLE', $table_prefix.'extensions');
define('FLAG_TABLE', $table_prefix.'flags');
define('FORBIDDEN_EXTENSIONS_TABLE', $table_prefix.'forbidden_extensions');
define('FORUM_TOUR_TABLE', $table_prefix.'forum_tour');
define('FORUMS_TABLE', $table_prefix.'forums');
define('FORUMS_DESC_TABLE', $table_prefix.'forums_descrip');
define('FORUMS_WATCH_TABLE', $table_prefix.'forums_watch');
define('GROUPS_TABLE', $table_prefix.'groups');
define('GUESTBOOK', $table_prefix.'guestbook');
define('GUESTBOOK_CONFIG_TABLE', $table_prefix.'guestbook_config');
define('HELPDESK_E', $table_prefix.'helpdesk_emails');
define('HELPDESK_I', $table_prefix.'helpdesk_importance');
define('HELPDESK_M', $table_prefix.'helpdesk_msgs');
define('HELPDESK_R', $table_prefix.'helpdesk_reply');
define('HIDE_TABLE', $table_prefix.'posts_ignore_sigav');
define('BUDDY_LIST_TABLE', $table_prefix.'im_buddy_list');
define('iNA', $table_prefix.'ina_data');
define('iNA_GAMES', $table_prefix.'ina_games');
define('iNA_SCORES', $table_prefix.'ina_scores');
define('ADS_TABLE', $table_prefix.'inline_ads');
define('IP_TABLE', $table_prefix.'ip');
define('JOBS_TABLE', $table_prefix.'jobs');
define('EMPLOYED_TABLE', $table_prefix.'jobs_employed');
define('KB_ARTICLES_TABLE', $table_prefix.'kb_articles');
define('KB_CATEGORIES_TABLE', $table_prefix.'kb_categories');
define('KB_MATCH_TABLE', $table_prefix.'kb_wordmatch');
define('KB_SEARCH_TABLE', $table_prefix.'kb_results');
define('KB_TYPES_TABLE', $table_prefix.'kb_types');
define('KB_WORD_TABLE', $table_prefix.'kb_wordlist');
define('LEXICON_CAT_TABLE', $table_prefix.'lexicon_cat');
define('LEXICON_ENTRY_TABLE', $table_prefix.'lexicon');
define('LOGS_TABLE', $table_prefix.'logs');
define('LOTTERY_TABLE', $table_prefix.'lottery');
define('LOTTERY_HISTORY_TABLE', $table_prefix.'lottery_history');
define('MEDAL_TABLE', $table_prefix.'medal');
define('MEDAL_MOD_TABLE', $table_prefix.'medal_mod');
define('MEDAL_USER_TABLE', $table_prefix.'medal_user');
define('MEDAL_CAT_TABLE', $table_prefix.'medal_cat');
define('MEETING_COMMENT_TABLE', $table_prefix.'meeting_comment');
define('MEETING_CONFIG_TABLE', $table_prefix.'meeting_config');
define('MEETING_DATA_TABLE', $table_prefix.'meeting_data');
define('MEETING_GUESTNAMES_TABLE', $table_prefix.'meeting_guestnames');
define('MEETING_USER_TABLE', $table_prefix.'meeting_user');
define('MEETING_USERGROUP_TABLE', $table_prefix.'meeting_usergroup');
define('MODULES_TABLE', $table_prefix.'modules');
define('MODULE_ADMIN_PANEL_TABLE', $table_prefix.'module_admin_panel');
define('MODULE_CACHE_TABLE', $table_prefix.'module_cache');
define('MODULE_INFO_TABLE', $table_prefix.'module_info');
define('MODULE_GROUP_AUTH_TABLE', $table_prefix.'module_group_auth');
define('MOVE_TABLE', $table_prefix.'forum_move');
define('MYCALENDAR_TABLE', $table_prefix.'mycalendar');
define('MYCALENDAR_EVENT_TYPES_TABLE', $table_prefix.'mycalendar_event_types');
define('OPTIMIZE_DB_TABLE', $table_prefix.'optimize_db');
define('PAGES_TABLE', $table_prefix.'pages');
define('PA_CATEGORY_TABLE', $table_prefix.'pa_cat');
define('PA_CONFIG_TABLE', $table_prefix.'pa_config');
define('PA_COMMENTS_TABLE', $table_prefix.'pa_comments');
define('PA_CUSTOM_TABLE', $table_prefix.'pa_custom');
define('PA_CUSTOM_DATA_TABLE', $table_prefix.'pa_customdata');
define('PA_DOWNLOAD_INFO_TABLE', $table_prefix.'pa_download_info');
define('PA_FILES_TABLE', $table_prefix.'pa_files');
define('PA_LICENSE_TABLE', $table_prefix.'pa_license');
define('PA_VOTES_TABLE', $table_prefix.'pa_votes');
define('PJIRC_TABLE', $table_prefix.'pjirc');
define('POINTS_LOGGER_TABLE', $table_prefix.'points_logger');
define('PORTAL_TABLE', $table_prefix.'portal');
define('POSTS_TABLE', $table_prefix.'posts');
define('POSTS_EDIT_TABLE', $table_prefix.'posts_edit');
define('POSTS_TEXT_TABLE', $table_prefix.'posts_text');
define('PRIVMSGS_TABLE', $table_prefix.'privmsgs');
define('PRIVMSGS_TEXT_TABLE', $table_prefix.'privmsgs_text');
define('PRIVMSGS_IGNORE_TABLE', $table_prefix.'privmsgs_ignore');
define('PROFILE_VIEW_TABLE', $table_prefix.'profile_view');
define('PRUNE_TABLE', $table_prefix.'forum_prune');
define('QUOTA_LIMITS_TABLE', $table_prefix.'quota_limits');
define('RANKS_TABLE', $table_prefix.'ranks');
define('RATING_TABLE', $table_prefix.'rating');
define('RATING_CONFIG_TABLE', $table_prefix.'rating_config');
define('RATING_OPTION_TABLE', $table_prefix.'rating_option');
define('RATING_RANK_TABLE', $table_prefix.'rating_rank');
define('RATING_TEMP_TABLE', $table_prefix.'rating_temp');
define('RATING_BIAS_TABLE', $table_prefix.'rating_bias');
define('REFERERS_TABLE', $table_prefix.'referers');
define('REFERRAL_TABLE', $table_prefix.'referral');
define('SEARCH_TABLE', $table_prefix.'search_results');
define('SEARCH_WORD_TABLE', $table_prefix.'search_wordlist');
define('SEARCH_MATCH_TABLE', $table_prefix.'search_wordmatch');
define('SERVERLOAD_TABLE', $table_prefix.'serverload');
define('SESSIONS_TABLE', $table_prefix.'sessions');
define('SESSIONS_KEYS_TABLE', $table_prefix.'sessions_keys');
define('SHOPS_TABLE', $table_prefix.'shops');
define('SHOPITEMS_TABLE', $table_prefix.'shop_items');
define('SHOPTRANS_TABLE', $table_prefix.'shop_transactions');
define('SHOUTBOX_TABLE', $table_prefix.'shout');
define('SMILIES_TABLE', $table_prefix.'smilies');
define('SMILIES_CAT_TABLE', $table_prefix.'smilies_cat');
define('SMILIE_INDEX_TABLE', $table_prefix.'stats_smilies_index');
define('SMILIE_INFO_TABLE', $table_prefix.'stats_smilies_info');
define('THANKS_TABLE', $table_prefix.'thanks');
define('THEMES_TABLE', $table_prefix.'themes');
define('THEMES_NAME_TABLE', $table_prefix.'themes_name');
define('THREAD_KICKER_TABLE', $table_prefix.'thread_kicker');
define('TITLE_INFOS_TABLE', $table_prefix.'title_infos');
define('TOPICS_TABLE', $table_prefix.'topics');
define('TOPICS_VIEWDATA_TABLE', $table_prefix.'topics_viewdata');
define('TOPICS_WATCH_TABLE', $table_prefix.'topics_watch');
define('TOPLIST_TABLE', $table_prefix.'toplist');
define('TOPLIST_ANTI_FLOOD_TABLE', $table_prefix.'toplist_anti_flood');
define('TRANSACTION_TABLE', $table_prefix.'transactions');
define('UNIQUE_HITS_TABLE', $table_prefix.'unique_hits');
define('USER_GROUP_TABLE', $table_prefix.'user_group');
define('NOTES_TABLE', $table_prefix.'user_notes');
define('TABLE_USER_SHOPS', $table_prefix.'user_shops');
define('TABLE_USER_SHOP_ITEMS', $table_prefix.'user_shops_items');
define('USERS_TABLE', $table_prefix.'users');
define('USERS_COMMENTS_TABLE', $table_prefix.'users_comments');
define('WORDS_TABLE', $table_prefix.'words');
define('VOTE_DESC_TABLE', $table_prefix.'vote_desc');
define('VOTE_RESULTS_TABLE', $table_prefix.'vote_results');
define('VOTE_USERS_TABLE', $table_prefix.'vote_voters');
define('XDATA_FIELDS_TABLE', $table_prefix.'xdata_fields');
define('XDATA_DATA_TABLE', $table_prefix.'xdata_data');
define('XDATA_AUTH_TABLE', $table_prefix.'xdata_auth');

include($phpbb_root_path . 'mods/album/album_constants.' . $phpEx);
include_once($phpbb_root_path . 'includes/constants_prillian.'.$phpEx);
include_once($phpbb_root_path . 'includes/constants_contact.'.$phpEx);

// Lite board installed ?
if ( file_exists($phpbb_root_path . 'lite') )
{
	include($phpbb_root_path . 'lite/includes/constants.' . $phpEx);
}

?>