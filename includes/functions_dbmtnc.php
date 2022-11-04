<?php
/** 
*
* @package includes
* @version $Id: functions_dbmtnc.php,v 1.0.0 2004 Philipp Kordowich Exp $
* @copyright (c) 2004 Philipp Kordowich
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

if ( !defined('IN_PHPBB') )
{
	die('Hacking attempt');
	exit;
}

// List of tables used
$tables = array('auth_access', 'banlist', 'categories', 'config', 'disallow', 'forums', 'forum_prune', 'groups', 'posts', 'posts_text', 'privmsgs', 'privmsgs_text', 'ranks', 'search_results', 'search_wordlist', 'search_wordmatch', 'sessions', 'sessions_keys', 'smilies', 'themes', 'themes_name', 'topics', 'topics_watch', 'user_group', 'users', 'vote_desc', 'vote_results', 'vote_voters', 'words');
// List of configuration data required
$config_data = array('dbmtnc_disallow_postcounter', 'dbmtnc_disallow_rebuild', 'dbmtnc_rebuildcfg_maxmemory', 'dbmtnc_rebuildcfg_minposts', 'dbmtnc_rebuildcfg_php3only', 'dbmtnc_rebuildcfg_php3pps', 'dbmtnc_rebuildcfg_php4pps', 'dbmtnc_rebuildcfg_timeoverwrite', 'dbmtnc_rebuildcfg_timelimit', 'dbmtnc_rebuild_end', 'dbmtnc_rebuild_pos');
// Default configuration records - from installation file
$default_config = array(
	'config_id' => 1,
	'board_disable' => 0,
	'sitename' => 'yourdomain.com',
	'site_desc' => 'A _little_ text to describe your forum',
	'cookie_name' => 'phpbb2mysql',
	'cookie_path' => '/',
	'cookie_domain' => '',
	'cookie_secure' => 0,
	'session_length' => 3600,
	'allow_autologin' => 1,
	'allow_html' => 0,
	'allow_html_tags' => 'b,i,u,pre',
	'allow_bbcode' => 1,
	'allow_smilies' => 1,
	'allow_sig' => 1,
	'allow_namechange' => 0,
	'allow_theme_create' => 0,
	'allow_avatar_local' => 0,
	'allow_avatar_remote' => 0,
	'allow_avatar_upload' => 0,
	'enable_confirm' => 0,
	'override_user_style' => 0,
	'posts_per_page' => 15,
	'topics_per_page' => 50,
	'hot_threshold' => 25,
	'max_autologin_time' => 0,
	'max_login_attempts' => 5,
	'max_poll_options' => 10,
	'max_sig_chars' => 255,
	'max_inbox_privmsgs' => 50,
	'max_sentbox_privmsgs' => 25,
	'max_savebox_privmsgs' => 50,
	'board_email_sig' => 'Thanks, The Management',
	'board_email' => 'youraddress@yourdomain.com',
	'smtp_delivery' => 0,
	'smtp_host' => '',
	'smtp_username' => '',
	'smtp_password' => '',
	'sendmail_fix' => 0,
	'require_activation' => 0,
	'flood_interval' => 15,
	'board_email_form' => 0,
	'avatar_filesize' => 6144,
	'avatar_max_width' => 80,
	'avatar_max_height' => 80,
	'avatar_path' => 'uploads/user_avatars',
	'avatar_gallery_path' => 'images/avatars',
	'smilies_path' => 'images/smiles',
	'default_style' => 1,
	'default_dateformat' => 'D M d, Y g:i a',
	'board_timezone' => 0,
	'prune_enable' => 1,
	'privmsg_disable' => 0,
	'gzip_compress' => 0,
	'coppa_fax' => '',
	'coppa_mail' => '',
	'login_reset_time' => 30,
	'record_online_users' => 0,
	'record_online_date' => 0,
	'server_name' => 'www.yourdomain.com',
	'server_port' => 80,
	'script_path' => '/phpBB2/',
	'default_lang' => 'english',
	'board_startdate' => 0,
	'search_min_chars' => 3,

	// Fully Modded specific entries
		'ad_after_post' => 0,
		'ad_post_threshold' => '',
		'ad_every_post' => 0,	
		'ad_who' => 1,
		'ad_no_forums' => '',
		'ad_no_groups' => '',
		'ad_old_style' => 0,	
		'admin_login' => 1,
		'admin_auto_delete_days' => 999,
		'admin_auto_delete_days_inactive' => 999,
		'admin_auto_delete_inactive' => 0,
		'admin_auto_delete_non_visit' => 0,
		'admin_auto_delete_total' => 0,
		'admin_auto_delete_minutes' => 1440,
		'last_auto_delete_users_attempt' => 0,
		'admin_auto_delete_days_no_post' => 999,
		'admin_auto_delete_no_post' => 0,
		'AJAXed_status' => 0,
		'AJAXed_status_prem' => 0,
		'AJAXed_inline_post_editing' => 0,
		'AJAXed_inline_post_editing_prem' => 0,
		'AJAXed_post_title' => 0,
		'AJAXed_post_ip' => 0,
		'AJAXed_post_menu' => 0,
		'AJAXed_poll_menu' => 0,
		'AJAXed_poll_title' => 0,
		'AJAXed_poll_options' => 0,
		'AJAXed_post_preview' => 0,
		'AJAXed_pm_preview' => 0,
		'AJAXed_user_list' => 0, 
		'AJAXed_user_list_number' => 30,
		'AJAXed_forum_delete' => 0,
		'AJAXed_display_delete' => 0,
		'AJAXed_forum_move' => 0,
		'AJAXed_display_move' => 0,
		'AJAXed_forum_lock' => 0,
		'AJAXed_post_delete' => 0,
		'AJAXed_topic_delete' => 0,
		'AJAXed_topic_move' => 0,
		'AJAXed_topic_lock' => 0,
		'AJAXed_topic_watch' => 0,
		'AJAXed_username_check' => 0,
		'AJAXed_password_check' => 0,	
		'all_admin' => 0,	
		'allow_avatar_generator' => 0,
		'allow_avatar_sticky' => 0,
		'allow_custom_post_color' => 0,
		'allow_invisible_link' => 0,
		'allow_karma' => 0,
		'allow_photo_remote' => 0,
		'allow_photo_upload' => 0,
		'allow_swearywords' => 0,
		'allow_medal_display_viewtopic' => 0,
		'allow_medal_display_viewprofile' => 0,
		'medal_display_row' => 1,
		'medal_display_col' => 1,
		'medal_display_width' => '',
		'medal_display_height' => '',
		'medal_display_order' => '',
		'amazon_enable' => 0, 
		'amazon_username' => 'your_amazon_user_id',
		'amazon_global_announce' => 0, 
		'amazon_announce' => 0, 
		'amazon_sticky' => 0, 
		'amazon_normal' => 0, 
		'amazon_window' => 1, 
		'amazon_country' => 0, 
		'auction_enable' => 0, 
		'auction_ebay_user_id' => 'your_ebay_user_id', 
		'auction_enable_thumbs' => 0, 
		'auction_timezone_offset' => 0, 
		'auction_enable_ended' => -1, 
		'auction_sort_order' => 3, 
		'auction_amt' => 10, 
		'auction_ebay_url' => 'http://cgi.ebay.com',
		'autologin_check' => 0,
		'auto_group_id' => 0,
		'avatar_generator_template_path' => 'images/avatar_generator',
		'avatar_posts' => 0,
		'avatar_toplist' => 0, 
		'avatar_voting_viewtopic' => 0,
		'avatars_per_page' => 20,
		'bankfees' => 2,
		'bankinterest' => 2,
		'banklastrestocked' => 0,
		'bankname' => 'Bank',
		'bankopened' => 0,
		'bankpayouttime' => 86400,
		'bank_minwithdraw' => 0,
		'bank_mindeposit' => 0,
		'bank_interestcut' => 0,
		'bb_usage_stats_enable' => 0,
		'bb_usage_stats_prscale' => 1,
		'bb_usage_stats_specialgrp' => -1,
		'bb_usage_stats_viewlevel' => 16,
		'bb_usage_stats_trscale' => 1,
		'bin_forum' => 0,
		'birthday_check_day' => 7,
		'birthday_greeting' => 1,
		'birthday_required' => 0,
		'birthday_viewtopic' => 0,
		'bluecard_limit' => 3,
		'bluecard_limit_2' => 1,
		'board_disable_text' => '',
		'board_disable_mode' => '-1,0',
		'board_sig' => '',
		'board_sitemap' => 0,
		'board_hits' => 0,
		'board_serverload' => 0,
		'bookie_allow_commission' => 0,
		'bookie_commission' => 3,
		'bookie_def_cat' => 1,
		'bookie_default_date' => '',
		'bookie_eachway' => 0,
		'bookie_edit_stake' => 1,
		'bookie_frac_or_dec' => 0,
		'bookie_leader' => 10,
		'bookie_min_bet' => '',
		'bookie_max_bet' => '',
		'bookie_pm' => 1,
		'bookie_restrict' => 0,
		'bookie_user_bets' => 0,
		'bookie_welcome' => 'Welcome to the Bookmakers',
		'bookie_version' => '3.0.0', 
		'callhome_disable' => 0, 
		'capitalization' => 0,
		'challenges_sent' => 0,
		'chat_index' => 0,
		'charts_week_num' => 1,
		'collapse_userinfo' => 0,
		'current_ina_date' => time(),
		'custom_overall_footer' => '',
		'custom_overall_header' => '',
		'custom_simple_footer' => '',
		'custom_simple_header' => '',
		'daily_post_limit' => 0,
		'debug_email' => 0,
		'debug_value' => 0,
		'default_avatar_guests_url' => 'images/avatars/none.gif',
		'default_avatar_set' => 3,
		'default_avatar_users_url' => 'images/avatars/none.gif',
		'default_clock' => 'clock001.swf',
		'disable_avatar_approve' => 1,
		'disable_reg_msg' => '',
		'display_last_edited' => 1,
		'dbmtnc_rebuild_end' => 0,
		'dbmtnc_rebuild_pos' => -1,
		'dbmtnc_rebuildcfg_maxmemory' => 500,
		'dbmtnc_rebuildcfg_minposts' => 3,
		'dbmtnc_rebuildcfg_php3only' => 0,
		'dbmtnc_rebuildcfg_php3pps' => 1,
		'dbmtnc_rebuildcfg_php4pps' => 8,
		'dbmtnc_rebuildcfg_timelimit' => 240,
		'dbmtnc_rebuildcfg_timeoverwrite' => 0,
		'dbmtnc_disallow_postcounter' => 0,
		'dbmtnc_disallow_rebuild' => 0,
		'disable_topic_watching' => 1,
		'enable_autobackup' => 0,
		'enable_confirm_posting' => 1, 
		'enable_ip_logger' => 0,
		'enable_module_avdelete' => 0,
		'enable_module_backup' => 0,
		'enable_module_disallow' => 0, 
		'enable_module_mass_email' => 0, 
		'enable_module_ranks' => 0, 
		'enable_module_smilies' => 0, 
		'enable_module_user_ban' => 0, 
		'enable_module_users' => 0, 
		'enable_module_words' => 0, 
		'enable_quick_reply' => 0,
 		'enable_similar_topics' => 0,
 		'enable_spellcheck' => 0,
 		'enable_bancards' => 0, 
		'enable_kicker' => 0, 
		'enable_mod_logger' => 0,
		'enable_topic_view_users' => 0,
		'enable_tellafriend' => 0,
		'enable_coppa' => 0,
		'enable_avatar_register' => 0,
		'enable_http_referrers' => 0,
		'enable_ignore_sigav' => 0,
		'enable_user_notes' => 0,
		'enable_quick_titles' => 0,
		'enable_bot_tracking' => 0,
		'enable_bot_email' => 0,
		'enable_bots_whosonline' => 0,
		'extra_days_for_sub' => 0,
		'forum_module_birthday' => 0,
		'forum_module_calendar' => 0,
		'forum_module_clock' => 0,
		'forum_module_disable' => 0,
		'forum_module_dloads' => 0,
		'forum_module_game' => 0,
		'forum_module_glance' => 0,
		'forum_module_karma' => 0,
		'forum_module_links' => 0,
		'forum_module_newsbar' => 0,
		'forum_module_newusers' => 0,
		'forum_module_photo' => 0,
		'forum_module_points' => 0,
		'forum_module_quote' => 0,
		'forum_module_randomuser' => 0,
		'forum_module_topposters' => 0,
		'forum_module_wallpaper' => 0,
		'forum_module_weather' => 0,
		'forum_module_width' => 200,
		'forum_module_donors' => 0,
		'glance_forum_id' => 1,
		'glance_forum_discuss_title' => 'Recent Discussions',	
		'glance_news_num' => 5,
		'glance_forum_news_title' => 'Important News',
		'glance_recent_num' => 5,
		'glance_news_scroll' => 0,
		'glance_recent_scroll' => 0,
		'gzip_level' => 9,
		'ftr_msg' => 'Sorry *u*, you need to read our topic: "*t*" for new users. After you read it, you can proceed to browse our posts normally. Please click *l* to view the post.',
		'ftr_topic' => 1,
		'ftr_active' => 0,
		'ftr_who' => 1,
		'ftr_installed' => time(),
		'games_path' => '',
		'games_per_page' => 20,
		'gamelib_path' => '',
		'gender_index' => 0,
		'gender_required' => 0,
		'gender_viewtopic' => 0,
		'hidde_last_logon' => 0,
		'hl_enable' => 0,
		'hl_necessary_post_number' => 0,
		'hl_mods_priority' => 1,
		'post_images_max_width' => 400,
		'post_images_max_height' => 300,
		'post_images_max_limit' => 3,
		'ina_button_option' => 2,
		'ina_cash_name' => 'Tokens',
		'ina_challenge' => 1,
		'ina_challenge_msg' => 'Your Trophy For %g% Has Been Challenged By %n%.',
		'ina_challenge_sub' => 'Trophy Challenge In Progress',
		'ina_daily_game_date' => date('Y-m-d'),
		'ina_daily_game_id' => 1,
		'ina_daily_game_random' => 1,
		'ina_default_charge' => 50,
		'ina_default_g_height' => 400,
		'ina_default_g_path' => 'mods/games/',
		'ina_default_g_reward' => 20,
		'ina_default_g_width' => 550,
		'ina_default_increment' => 5,
		'ina_default_order' => 1,
		'ina_delete' => 0,
		'ina_disable_challenges_page' => 0,
		'ina_disable_cheat' => 1,
		'ina_disable_comments_page' => 0,
		'ina_disable_everything' => 0,
		'ina_disable_gamble_page' => 0,
		'ina_disable_submit_scores_g' => 0,
		'ina_disable_submit_scores_m' => 0,
		'ina_disable_top5_page' => 0,
		'ina_disable_trophy_page' => 0,
		'ina_email_sent' => 0,
		'ina_force_registration' => 0,
		'ina_guest_play' => 2,
		'ina_jackpot_pool' => 0,
		'ina_join_block' => 2,
		'ina_join_block_count' => 14,
		'ina_max_gamble' => 100,
		'ina_max_games_per_day' => 100,
		'ina_max_games_per_day_date' => '',
		'ina_new_game_count' => 3,
		'ina_new_game_limit' => 7,
		'ina_pm_trophy' => 1,
		'ina_pm_trophy_msg' => '%n% Has Taken Your Trophy For %g%.',
		'ina_pm_trophy_sub' => 'Trophy Lost!',
		'ina_pop_game_limit' => 5,
		'ina_post_block' => 2,
		'ina_post_block_count' => 10,
		'ina_rating_reward' => 0,
		'ina_show_view_profile' => 1,
		'ina_show_view_topic' => '',
		'ina_trophy_king' => 2,
		'ina_use_daily_game' => 1,
		'ina_use_max_games_per_day' => 2,
		'ina_use_newest' => 1,
		'ina_use_online' => 1,
		'ina_use_rating_reward' => 0,
		'ina_use_shoutbox' => 1,
		'ina_use_trophy' => 1,
		'ina_version' => '1.1.0',
		'ina_char_change_char_cost' => 0,
		'ina_char_change_gender_cost' => 0,
		'ina_char_change_age_cost' => 0,
		'ina_char_change_name_cost' => 0,
		'ina_char_change_from_cost' => 0,
		'ina_char_change_intrests_cost' => 0,
		'ina_char_ge_per_game' => 1,
		'ina_char_ge_per_beat_score' => 2,
		'ina_char_ge_per_trophy' => 3,
		'ina_char_show_viewtopic' => 1,
		'ina_char_show_viewprofile' => 1,
		'ina_char_change_title_cost' => 0,
		'ina_char_change_saying_cost' => 0,
		'ina_char_name_effects_costs' => '7,5,9,3,10,20',
		'ina_char_title_effects_costs' => '5,4,3,2,1,1',
		'ina_char_saying_effects_costs' => '2,2,2,2,2,2',
		'ina_game_pass_cost' => 0, 
		'ina_game_pass_length' => 5,	
		'ina_hof_viewtopic' => 0,
		'ina_players_index' => 0,
		'index_groups' => 0,
		'index_new_reg_users' => 0,
		'index_active_in_forum' => 0,
		'jobs_status' => 0,
		'jobs_limit' => 2,
		'jobs_pay_type' => 0,
		'jobs_index_body' => 0,
		'jobs_viewtopic' => 0,
		'journal_forum_id' => 0,
		'jump_to_topic' => 0,
		'karma_admins' => 1,
		'karma_flood_interval' => 1,
		'kb_admin_id' => 2, 
		'kb_allow_anon' => 0, 
		'kb_allow_edit' => 1, 
		'kb_allow_new' => 1, 
		'kb_approve_edit' => 1,
		'kb_approve_new' => 1, 
		'kb_comments' => 1, 
		'kb_del_topic' => 1,			
		'kb_forum_id' => 1, 
		'kb_notify' => 1, 
		'kb_pt_body' => 'Please check your references and include as much information as you can.',
		'kb_show_pt' => 0, 		
		'kicker_setting' => 0,
		'kicker_view_setting' => 0,
		'lexicon_title' => 'Lexicon',
		'lexicon_description' => 'A little text to describe your lexicon',
		'limit_username_min_length' => 2,
		'limit_username_max_length' => 25,
		'locked_last' => 0,
		'logo_url' => 'index.php',
		'lottery_base' => 500,
		'lottery_cost' => 1,
		'lottery_currency' => '',
		'lottery_history' => 1,
		'lottery_item_mcost' => 1,
		'lottery_item_xcost' => 500,
		'lottery_items' => 0,
		'lottery_length' => 500000,
		'lottery_mb' => 0,
		'lottery_mb_amount' => 1,
		'lottery_name' => 'Lottery',
		'lottery_random_shop' => '',
		'lottery_reset' => 0,
		'lottery_show_entries' => 0,
		'lottery_start' => 0,
		'lottery_status' => 0,
		'lottery_ticktype' => 0,
		'lottery_win_items' => '',
		'lw_trial_period' => 0, 
		'max_sig_lines' => 4,
		'max_user_age' => 100,
		'max_user_bancard' => 10,
		'max_user_votebancard' => 50,
		'message_maxlength' => 10000,
		'message_minlength' => 2,
		'meta_abstract' => 'best forum',
		'meta_author' => 'youraddress@yourdomain.com',
		'meta_description' => 'best phpBB forum',
		'meta_distribution' => 'global',
		'meta_keywords' => 'phpBB, phpbb, best, forum',
		'meta_owner' => 'youraddress@yourdomain.com',
		'meta_robots' => 'index, follow',
		'meta_revisit' => 20,
		'meta_language' => 'en',
		'meta_rating' => 'General',
		'meta_identifier_url' => '',
		'meta_reply_to' => $board_config['board_email'],
		'meta_category' => 'phpBB',
		'meta_copyright' => '',
		'meta_generator' => '',
		'meta_date_creation_day' => '',
		'meta_date_creation_month' => '',
		'meta_date_creation_year' => '',
		'meta_date_revision_day' => '',
		'meta_date_revision_month' => '',
		'meta_date_revision_year' => '',
		'meta_redirect_url_time' => '',
		'meta_redirect_url_adress' => '',
		'meta_refresh' => '',
		'meta_pragma' => 'no-cache',
		'min_user_age' => 5,
		'minical_upcoming' => 0,
		'minical_event_lmt' => 5,
		'minical_search' => 'POSTS',
		'missing_bbcode_imgs' => 0,
		'mods_viewips' => 0,
		'multibuys' => 0,
		'myInfo_version' => '1.0.0', 
		'myInfo_enable' => 0, 
		'myInfo_name' => 'myInfo', 
		'myInfo_instructions' => 'This is the myInfo text box.',
		'news_bold' => 'B',
		'news_block' => 'Your news title',
		'news_ital' => 'none',
		'news_size' => 12,
		'news_style' => 'marquee',
		'news_title' => 'Title',
		'news_under' => 'none',
		'scroll_action' => 'left',
		'scroll_behavior' => 'scroll',
		'scroll_size' => '100%',
		'scroll_speed' => 5,
		'newsfeed_rss' => 'http://p.moreover.com/cgi-local/page?c=Webmaster%20tips&o=xml',
		'newsfeed_cache' => 'cache',
		'newsfeed_cachetime' => '10',
		'newsfeed_amt' => '11',
		'newsfeed_field_article' => 'ARTICLE',
		'newsfeed_field_url' => 'URL',
		'newsfeed_field_text' => 'HEADLINE_TEXT',
		'newsfeed_field_source' => 'SOURCE',
		'newsfeed_field_time' => 'HARVEST_TIME',
		'no_post_count_forum_id' => '',
		'notes' => 20,
		'null_vote' => 0,
		'page_transition' => '',
		'pass_gen_length' => 6,
		'pass_gen_enable' => 0,
		'pass_gen_alphanumerical' => 1,
		'pass_gen_uppercase' => 1,
		'pass_gen_lowercase' => 1,
		'pass_gen_specialchars' => 1,
		'pass_gen_numbers' => 1,
		'password_update_days' => 0,
		'paypal_p_acct' => 'paypal_address@paypal.com', 
		'paypal_currency_code' => 'USD', 
		'dislay_x_donors' => 10,
		'donate_start_time' => '',
		'donate_end_time' => '',
		'donate_cur_goal' => 0,
		'donate_description' => '',
		'donate_to_points' => 0,
		'paypal_b_acct' => 'paypal_address@paypal.com',
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
		'photo_filesize' => 6144,
		'photo_max_height' => 80,
		'photo_max_width' => 80,
		'photo_path' => 'uploads/user_photos',
		'points_banner' => 0,
		'points_browse' => 0,
		'points_default' => 0,
		'points_donate' => 0,
		'points_name' => 'Points',
		'points_page' => 1,
		'points_post' => 0,
		'points_system_version' => '2.1.1',
		'points_reply' => 1,
		'points_topic' => 2,
		'points_user_group_auth_ids' => '',
		'points_viewtopic' => 0,
		'post_edit_time_limit' => -1,
		'privmsg_newuser_disable' => 1,
		'privmsg_self' => 1,
		'profile_show_sig' => 0,
		'prune_shouts' => 0,
		'record_day_date' => 0,
		'record_day_users' => 0,
		'reduce_bbcode_imgs' => 0,
		'referral_id' => -1,
		'referral_enable' => 0,
		'referral_top_limit' => 5,
		'referral_reward' => 50,
		'referral_viewtopic' => 0,
		'registration_notify' => 0,
		'report_cheater' => 0,
		'report_forum' => 0,
		'restocks' => 0,
		'search_enable' => 1,
		'search_footer' => 0,
		'search_forum' => 0,
		'sellrate' => 75,
		'shop_give' => 0,
		'shop_invlimit' => 0,
		'shop_orderby' => 'name',
		'shop_trade' => 0,
		'shop_trans_enable' => 0, 
		'shoutbox_enable' => 0,
		'shoutbox_refresh_rate' => 120,
		'shoutbox_position' => 0,
		'shoutbox_height' => '',
		'forum_module_shoutcast' => 0,				
		'forum_module_shoutcast_height' => '',
		'shoutcast_server' => '',
		'shoutcast_port' => '',
		'shoutcast_pass' => '',
		'sig_images_max_width' => 400,
		'sig_images_max_height' => 300,
		'sig_images_max_limit' => 1,
		'smilie_rows' => 4,
		'smilie_columns' => 6,
		'smilie_max_filesize' => 6144,
		'smilie_icon_path' => $board_config['smilies_path'],
		'smilie_posting' => 1,
		'smilie_popup' => 1,
		'smilie_buttons' => 1,
		'smilie_random' => 0,
		'smilie_removal1' => 0,
		'smilie_removal2' => 0,
		'smilie_usergroups' => 0,
		'specialshop' => 'ßstoreÞdisabledßnameÞEffects StoreßoffÞ1ßoffÞ1ßoffÞ1ßoffÞ1ßoffÞ1ßoffÞ1ßoffÞ1ßoffÞ1ßoffÞ1ßoffÞ1ßoffÞ1ßoffÞ1ßoffÞ1',
		'split_global_announce' => 0,
		'split_announce' => 0,
		'split_sticky' => 0,
		'stop_bumping' => 1,
		'stat_install_date' => time(),
		'stat_page_views' => 0,
		'stat_return_limit' => 10,
		'stat_index' => 0,
		'stat_all_or_one' => 0,
		'subchk_enable' => 0,
		'subchk_bypass' => 1,
		'subchk_strict' => 0,
		'subchk_locked' => 1,
		'subchk_limit' => 5,
		'subchk_admin' => 0,
		'subchk_mod' => 0,
		'subchk_postcount' => 0,
		'topic_redirect' => 1,
		'toplist_anti_flood_hin_hits_interval' => 0,
		'toplist_anti_flood_img_hits_interval' => 0,
		'toplist_anti_flood_out_hits_interval' => 0,
		'toplist_button_1' => 'images/toplist/button.gif',
		'toplist_button_1_l' => 'http://www.yourdomain.com',
		'toplist_button_2' => 'images/toplist/button.gif',
		'toplist_button_2_l' => 'http://www.yourdomain.com',
		'toplist_count_hin_hits' => 1,
		'toplist_count_img_hits' => 1,
		'toplist_count_out_hits' => 1,
		'toplist_dimensions' => '',
		'toplist_hin_activation' => 0,
		'toplist_imge_dis' => 5,
		'toplist_prune_hin_hits_interval' => 0,
		'toplist_prune_hin_hits_last' => '',
		'toplist_prune_img_hits_interval' => 0,
		'toplist_prune_img_hits_last' => '',
		'toplist_prune_out_hits_interval' => 0,
		'toplist_prune_out_hits_last' => '',
		'toplist_toplist_top10' => 0,
		'toplist_version' => '1.3.8',
		'toplist_view_hin_hits' => 1,
		'toplist_view_img_hits' => 1,
		'toplist_view_out_hits' => 1,		
		'ts_sitetitle' => 'Teamspeak',
		'ts_serveraddress' => '127.0.0.1',
		'ts_serverqueryport' => 51234,
		'ts_serverudpport' => 8767,
		'ts_refreshtime' => 300,
		'ts_winheight' => '',
		'ts_serverpasswort' => 'password',
		'forum_module_teamspeak' => 0,				
		'u_shops_enabled' => 0,
		'u_shops_open_cost' => 0,
		'u_shops_tax_percent' => 1,
		'u_shops_max_items' => 100,
		'usertime_viewtopic' => 0,
		'uniquehits_time' => 1440,
		'use_gamelib' => 0,
		'use_gk_shop' => 0,
		'user_prune_notify' => 1,
		'user_accounts_limit' => 0,
		'viewinventory' => 'grouped',
		'viewprofile' => 'images',
		'viewtopic' => 0,
		'viewtopic_extrastats' => 0,
		'viewtopic_memnum' => 0,
		'viewtopic_yearstars' => 0,
		'viewtopic_flag' => 0,
		'viewtopic_usergroups' => 0,
		'viewtopic_buddyimg' => 0,
		'viewtopic_style' => 0,
		'viewtopiclimit' => 2,
		'viewtopic_downpost' => 0, 
		'viewtopic_viewpost' => 0,
		'viewtopic_status' => 0,
		'viewtopic_editdate' => 0,
		'viewtopic_profilephoto' => 0,
		'viewprofile_profilephoto' => 0,
		'vip_enable' => 1,
		'vip_code' => 2486,
		'visit_counter_index' => 0,
		'visit_counter' => 1,
		'vote_min_posts' => 0,
		'warn_cheater' => 0,
		'whosonline_time' => 5,
		'wpm_disable' => 1,
		'wrap_enable' => 0,
		'wrap_min' => 50,
		'wrap_max' => 99,
		'wrap_def' => 80,
		'xs_cache_dir' => 'cache/tpls',
		'xs_cache_dir_absolute' => 0,
		'xs_auto_compile' => 1,
		'xs_auto_recompile' => 1,
		'xs_use_cache' => 1,
		'xs_separator' => '/',
		'xs_php' => 'php',
		'xs_def_template' => 'subSilver',
		'xs_use_isset' => 1,
		'xs_check_switches' => 0,
		'xs_version' => 2,
		'year_stars' => 1
);

//
// Function for updating the config_table
//
function update_config($name, $value)
{
	global $db, $board_config, $phpbb_root_path;

	$sql = 'UPDATE ' . CONFIG_TABLE . " 
		SET config_value = '$value' 
		WHERE config_name = '$name'";
	$result = $db->sql_query($sql);
	if( !$result )
	{
		throw_error("Couldn't update forum configuration!", __LINE__, __FILE__, $sql);
	}
	
	// Remove cache file
	@unlink($phpbb_root_path . 'cache/config_board.'.$phpEx);

	$board_config[$name] = $value;
}

//
// This is the equivalent function for message_die. Since we do not use the template system when doing database work, message_die() will not work.
//
function throw_error($msg_text = '', $err_line = '', $err_file = '', $sql = '')
{
	global $db, $template, $lang, $phpEx, $phpbb_root_path, $theme;
	global $list_open;

	$sql_store = $sql;

	//
	// Get SQL error if we are debugging. Do this as soon as possible to prevent
	// subsequent queries from overwriting the status of sql_error()
	//
	if ( DEBUG )
	{
		$sql_error = $db->sql_error();

		$debug_text = '';

		if ( $sql_error['message'] != '' )
		{
			$debug_text .= '<br /><br />SQL Error : ' . $sql_error['code'] . ' ' . $sql_error['message'];
		}

		if ( $sql_store != '' )
		{
			$debug_text .= "<br /><br />$sql_store";
		}

		if ( $err_line != '' && $err_file != '' )
		{
			$debug_text .= '</br /><br />Line : ' . $err_line . '<br />File : ' . $err_file;
		}
	}
	else
	{
		$debug_text = '';
	}

	//
	// Close the list if one is still open
	//
	if ( $list_open )
	{
		echo("</ul></span>\n");
	}

	if ( $msg_text == '' )
	{
		$msg_text = $lang['An_error_occured'];
	}

	echo('<p class="gen"><b><span style="color:#' . $theme['fontcolor3'] . '">' . $lang['Error'] . ":</span></b> $msg_text$debug_text</p>\n");

	//
	// Include Tail and exit
	//
	echo("<p class=\"gen\"><a href=\"" . append_sid("admin_db_maintenance.$phpEx") . "\">" . $lang['Back_to_DB_Maintenance'] . "</a></p>\n");
	include('./page_footer_admin.'.$phpEx);
	exit;
}

//
// Locks or unlocks the database
//
function lock_db($unlock = FALSE, $delay = TRUE, $ignore_default = FALSE)
{
	global $board_config, $db, $lang;
	static $db_was_locked = FALSE;

	if ($unlock)
	{
		echo('<p class="gen"><b>' . $lang['Unlock_db'] . "</b></p>\n");
		if ( $db_was_locked && !$ignore_default )
		{
			// The database was locked and we were not told to ignore the default. So we exit
			echo('<p class="gen">' . $lang['Ignore_unlock_command'] . "</p>\n");
			return;
		}
	}
	else
	{
		echo('<p class="gen"><b>' . $lang['Lock_db'] . "</b></p>\n");
		// Check current lock state
		if ( $board_config['board_disable'] == 1 )
		{
			// DB is already locked. Write this to var and exit
			$db_was_locked = TRUE;
			echo('<p class="gen">' . $lang['Already_locked'] . "</p>\n");
			return $db_was_locked;
		}
		else
		{
			$db_was_locked = FALSE;
		}
	}

	// OK, now we can update the settings
	update_config('board_disable', ($unlock) ? '0' : '1');

	//
	// Delay 3 seconds to allow database to finish operation
	//
	if (!$unlock && $delay)
	{
		global $timer;
		echo('<p class="gen">' . $lang['Delay_info'] . "</p>\n");
		sleep(3);
		$timer += 3; // remove delaying time from timer
	}
	else
	{
		echo('<p class="gen">' . $lang['Done'] . "</p>\n");
	}
	return $db_was_locked;
}

//
// Checks several conditions for the menu
//
function check_condition($check)
{
	global $db, $board_config;

	switch ($check)
	{
		case 0: // No check
			return TRUE;
			break;
		case 1: // MySQL >= 3.23.17
			return check_mysql_version();
			break;
		case 2: // Session Table not HEAP
			if (!check_mysql_version())
			{
				return FALSE;
			}
			$sql = "SHOW TABLE STATUS
				LIKE '" . SESSIONS_TABLE . "'";
			$result = $db->sql_query($sql);
			if( !$result )
			{
				return FALSE; // Status unknown
			}
			$row = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			if( !$row )
			{
				return FALSE; // Status unknown
			}
			if ( $row['Type'] == 'HEAP' || $row['Engine'] == 'HEAP' )
			{
				return FALSE;
			}
			else
			{
				return TRUE;
			}
			break;
		case 3: // DB locked
			if ( $board_config['board_disable'] == 1 )
			{
				// DB is locked
				return TRUE;
			}
			else
			{
				return FALSE;
			}
			break;
		case 4: // Search index in recreation
			if( $board_config['dbmtnc_rebuild_pos'] <> -1 )
			{
				// Rebuilding was interrupted - check for end position
				if ( $board_config['dbmtnc_rebuild_end'] >= $board_config['dbmtnc_rebuild_pos'] )
				{
					return TRUE;
				}
				else
				{
					return FALSE;
				}
			}
			else
			{
				// Rebuilding was not interrupted
				return FALSE;
			}
			break;
		case 5: // Configuration disabled
			return (CONFIG_LEVEL != 0) ? TRUE : FALSE;
			break;
		case 6: // User post counter disabled
			return ($board_config['dbmtnc_disallow_postcounter'] != 1) ? TRUE : FALSE;
			break;
		case 7: // Rebuilding disabled
			return ($board_config['dbmtnc_disallow_rebuild'] != 1) ? TRUE : FALSE;
			break;
		case 8: // Seperator for rebuilding
			return (check_condition(4) || check_condition(7)) ? TRUE : FALSE;
			break;
		default:
			return FALSE;
	}
}

//
// Checks whether MySQL supports HEAP-Tables, ANSI compatible INNER JOINs and other commands
//
function check_mysql_version()
{
	global $db;

	$sql = 'SELECT VERSION() AS mysql_version';
	$result = $db->sql_query($sql);
	if( !$result )
	{
		throw_error("Couldn't obtain MySQL Version", __LINE__, __FILE__, $sql);
	}
	$row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);

	$version = $row['mysql_version'];

	if ( preg_match("/^3\.23\.([0-9]$|[0-9]-|1[0-3]$|1[0-6]-)/", $version) ) // Version from 3.23.0 to 3.23.16
	{
		return FALSE;
	}
	elseif ( preg_match("/^(3\.23)|(4\.)/", $version) )
	{
		return TRUE;
	}
	else // Versions before 3.23.0
	{
		return FALSE;
	}
}

//
// Gets the current time in microseconds
//
function getmicrotime()
{
	list($usec, $sec) = explode(" ", microtime()); 
	return ((float)$usec + (float)$sec); 
}


//
// Gets table statistics
//
function get_table_statistic()
{
	global $db, $prefix;
	global $tables;

	$stat['all']['count'] = 0;
	$stat['all']['records'] = 0;
	$stat['all']['size'] = 0;
	$stat['advanced']['count'] = 0;
	$stat['advanced']['records'] = 0;
	$stat['advanced']['size'] = 0;
	$stat['core']['count'] = 0;
	$stat['core']['records'] = 0;
	$stat['core']['size'] = 0;
	
	$sql = 'SHOW TABLE STATUS';
	$result = $db->sql_query($sql);
	if( !$result )
	{
		throw_error("Couldn't obtain table data", __LINE__, __FILE__, $sql);
	}
	while( $row = $db->sql_fetchrow($result) )
	{
		$stat['all']['count']++;
		$stat['all']['records'] += intval($row['Rows']);
		$stat['all']['size'] += intval($row['Data_length']) + intval($row['Index_length']);
		if ( $prefix == substr($row['Name'], 0, strlen($prefix)) )
		{
			$stat['advanced']['count']++;
			$stat['advanced']['records'] += intval($row['Rows']);
			$stat['advanced']['size'] += intval($row['Data_length']) + intval($row['Index_length']);
		}
		for ($i = 0; $i < count($tables); $i++)
		{
			if ($prefix . $tables[$i] == $row['Name'])
			{
				$stat['core']['count']++;
				$stat['core']['records'] += intval($row['Rows']);
				$stat['core']['size'] += intval($row['Data_length']) + intval($row['Index_length']);
			}
		}
	}
	$db->sql_freeresult($result);
	return $stat;
}

//
// Converts Bytes to a apropriate Value
//
function convert_bytes($bytes)
{
	if( $bytes >= 1048576 )
	{
		return sprintf("%.2f MB", ( $bytes / 1048576 ));
	}
	else if( $bytes >= 1024 )
	{
		return sprintf("%.2f KB", ( $bytes / 1024 ));
	}
	else
	{
		return sprintf("%.2f Bytes", $bytes);
	}
}

//
// Creates a new category
//
function create_cat()
{
	global $db, $lang;

	static $cat_created = FALSE;
	static $cat_id = 0;

	if (!$cat_created)
	{
		// Höchten Wert von cat_order ermitteln
		$sql = 'SELECT Max(cat_order) AS cat_order
			FROM ' . CATEGORIES_TABLE;
		$result = $db->sql_query($sql);
		if( !$result )
		{
			throw_error("Couldn't get categories data!", __LINE__, __FILE__, $sql);
		}
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);
		if( !$row )
		{
			throw_error("Couldn't get categories data!", __LINE__, __FILE__, $sql);
		}
		$next_cat_order = $row['cat_order'] + 10;

		$sql = 'INSERT INTO ' . CATEGORIES_TABLE . ' (cat_title, cat_order)
			VALUES (\'' . $lang['New_cat_name'] . "', $next_cat_order)";
		$result = $db->sql_query($sql);
		if( !$result )
		{
			throw_error("Couldn't update categories data!", __LINE__, __FILE__, $sql);
		}
		$cat_id = $db->sql_nextid();
		$cat_created = TRUE;
	}
	return $cat_id;
}

//
// Creates a new forum
//
function create_forum()
{
	global $db, $lang;

	static $forum_created = FALSE;
	static $forum_id = 0;
	$cat_id = create_cat();

	if (!$forum_created)
	{
		// Höchten Wert von forum_id ermitteln
		$sql = 'SELECT Max(forum_id) AS forum_id
			FROM ' . FORUMS_TABLE;
		$result = $db->sql_query($sql);
		if( !$result )
		{
			throw_error("Couldn't get forum data!", __LINE__, __FILE__, $sql);
		}
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);
		if( !$row )
		{
			throw_error("Couldn't get forum data!", __LINE__, __FILE__, $sql);
		}
		$next_forum_id = $row['forum_id'] + 1;
		// Höchten Wert von forum_order ermitteln
		$sql = 'SELECT Max(forum_order) AS forum_order
			FROM ' . FORUMS_TABLE . "
			WHERE cat_id = $cat_id";
		$result = $db->sql_query($sql);
		if( !$result )
		{
			throw_error("Couldn't get forum data!", __LINE__, __FILE__, $sql);
		}
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);
		if( !$row )
		{
			throw_error("Couldn't get forum data!", __LINE__, __FILE__, $sql);
		}
		$next_forum_order = $row['forum_order'] + 10;

		$forum_permission = AUTH_ADMIN;
		$sql = 'INSERT INTO ' . FORUMS_TABLE . " (forum_id, cat_id, forum_name, forum_desc, forum_status, forum_order, forum_posts, forum_topics, forum_last_post_id, prune_next, prune_enable, auth_view, auth_read, auth_post, auth_reply, auth_edit, auth_delete, auth_sticky, auth_announce, auth_vote, auth_pollcreate, auth_attachments)
			VALUES ($next_forum_id, $cat_id, '" . $lang['New_forum_name'] . "', '', " . FORUM_LOCKED . ", $next_forum_order, 0, 0, 0, NULL, 0, $forum_permission, $forum_permission, $forum_permission, $forum_permission, $forum_permission, $forum_permission, $forum_permission, $forum_permission, $forum_permission, $forum_permission, 0)";
		$result = $db->sql_query($sql);
		if( !$result )
		{
			throw_error("Couldn't update forums data!", __LINE__, __FILE__, $sql);
		}
		$forum_id = $next_forum_id;
		$forum_created = TRUE;
	}
	return $forum_id;
}

//
// Create a new topic
//
function create_topic()
{
	global $db, $lang;

	static $topic_created = FALSE;
	static $topic_id = 0;
	$forum_id = create_forum();

	if (!$topic_created)
	{
		$sql = 'INSERT INTO ' . TOPICS_TABLE . " (forum_id, topic_title, topic_poster, topic_time, topic_views, topic_replies, topic_status, topic_vote, topic_type, topic_first_post_id, topic_last_post_id, topic_moved_id)
			VALUES ($forum_id, '" . $lang['New_topic_name'] . "', -1, " . time() . ", 0, 0, " . TOPIC_UNLOCKED . ", 0, " . POST_NORMAL . ", 0, 0, 0)";
		$result = $db->sql_query($sql);
		if( !$result )
		{
			throw_error("Couldn't update topics data!", __LINE__, __FILE__, $sql);
		}
		$topic_id = $db->sql_nextid();
		$topic_created = TRUE;
	}
	return $topic_id;
}

//
// Gets the poster of a topic
//
function get_poster($topic_id)
{
	global $db;
	
	$sql = 'SELECT MIN(post_id) AS first_post
		FROM ' . POSTS_TABLE . "
		WHERE topic_id = $topic_id";
	$result = $db->sql_query($sql);
	if( !$result )
	{
		throw_error("Couldn't get post data!", __LINE__, __FILE__, $sql);
	}
	$row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	if( !$row || $row['first_post'] == '')
	{
		return DELETED;
	}
	$sql = 'SELECT poster_id
		FROM ' . POSTS_TABLE . '
		WHERE post_id = ' . $row['first_post'];
	$result = $db->sql_query($sql);
	if( !$result )
	{
		throw_error("Couldn't get post data!", __LINE__, __FILE__, $sql);
	}
	$row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	if( !$row )
	{
		throw_error("Couldn't get post data!", __LINE__, __FILE__, $sql);
	}
	return $row['poster_id'];
}

//
// Error handler when trying to reset timelimit
//
function catch_error($errno, $errstr)
{
	global $execution_time;
	
	$execution_time = ini_get('max_execution_time'); // Will only get executet when running on PHP 4+
}

//
// Gets the ID of a word or creates it
//
function get_word_id($word)
{
	global $board_config, $db, $lang, $phpEx, $template, $theme;
	global $stopword_array, $synonym_array;
	
	// Check whether word is in stopword array
	if ( in_array($word, $stopword_array) )
	{
		return NULL;
	}
	if ( in_array($word, $synonym_array[1]) )
	{
		$key = array_search($word, $synonym_array[1]);
		$word = $synonym_array[0][$key];
	}
	
	$sql = "SELECT word_id, word_common
		FROM " . SEARCH_WORD_TABLE . "  
		WHERE word_text = '$word'";
	$result = $db->sql_query($sql);
	if ( !$result )
	{
		include('./page_header_admin.'.$phpEx);
		throw_error("Couldn't get search word data!", __LINE__, __FILE__, $sql);
	}
	if ( $row = $db->sql_fetchrow($result) ) // Word was found
	{
		if ( $row['word_common'] ) // Common word
		{
			return NULL;
		}
		else // Not a common word
		{
			return $row['word_id'];
		}
	}
	else // Word was not found
	{
		$sql = "INSERT INTO " . SEARCH_WORD_TABLE . " (word_text, word_common)
			VALUES ('$word', 0)";
		if ( !$db->sql_query($sql) )
		{
			include('./page_header_admin.'.$phpEx);
			throw_error("Couldn't insert search word data!", __LINE__, __FILE__, $sql);
		}
		return $db->sql_nextid();
	}
	$db->sql_freeresult($result);
}

//
// Resets the auto increment for a table
//
function set_autoincrement($table, $column, $length, $unsigned = TRUE)
{
	global $db, $lang;

	$sql = "ALTER IGNORE TABLE $table MODIFY $column mediumint($length) " . (($unsigned) ? 'unsigned ' : '') . "NOT NULL auto_increment";
	if (check_mysql_version())
	{
		$sql2 = "SHOW COLUMNS FROM $table LIKE '$column'";
		$result = $db->sql_query($sql2);
		if( !$result )
		{
			throw_error("Couldn't get table status!", __LINE__, __FILE__, $sql2);
		}
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);
		if( !$row )
		{
			throw_error("Couldn't get table status!", __LINE__, __FILE__, $sql2);
		}
		if (strpos($row['Extra'], 'auto_increment') !== FALSE)
		{
			echo("<li>$table: " . $lang['Ai_message_no_update'] . "</li>\n");
		}
		else
		{
			echo("<li>$table: <b>" . $lang['Ai_message_update_table'] . "</b></li>\n");
			$result = $db->sql_query($sql);
			if( !$result )
			{
				throw_error("Couldn't alter table!", __LINE__, __FILE__, $sql);
			}
		}
	}
	else // old Version of MySQL - do the update in any case
	{
		echo("<li>$table: <b>" . $lang['Ai_message_update_table_old_mysql'] . "</b></li>\n");
		$result = $db->sql_query($sql);
		if( !$result )
		{
			throw_error("Couldn't alter table!", __LINE__, __FILE__, $sql);
		}
	}
}

//
// Functions for Emergency Recovery Console
//
function erc_throw_error($msg_text = '', $err_line = '', $err_file = '', $sql = '')
{
	global $db, $lang;

	$sql_store = $sql;

	//
	// Get SQL error if we are debugging. Do this as soon as possible to prevent
	// subsequent queries from overwriting the status of sql_error()
	//
	if ( DEBUG )
	{
		$sql_error = $db->sql_error();

		$debug_text = '';

		if ( $sql_error['message'] != '' )
		{
			$debug_text .= '<br /><br />SQL Error : ' . $sql_error['code'] . ' ' . $sql_error['message'];
		}

		if ( $sql_store != '' )
		{
			$debug_text .= "<br /><br />$sql_store";
		}

		if ( $err_line != '' && $err_file != '' )
		{
			$debug_text .= '</br /><br />Line : ' . $err_line . '<br />File : ' . $err_file;
		}
	}
	else
	{
		$debug_text = '';
	}

	if ( $msg_text == '' )
	{
		$msg_text = $lang['An_error_occured'];
	}

	echo('<p class="gen"><b>' . $lang['Error'] . ":</b> $msg_text$debug_text</p>\n");

	exit;
}

function language_select($default, $select_name = "language", $file_to_check = "main", $dirname="language")
{
	global $phpEx, $phpbb_root_path, $lang;

	$dir = opendir($phpbb_root_path . $dirname);

	$lg = array();
	while ( $file = readdir($dir) )
	{
		if (preg_match('#^lang_#i', $file) && !is_file(@phpbb_realpath($phpbb_root_path . $dirname . '/' . $file)) && !is_link(@phpbb_realpath($phpbb_root_path . $dirname . '/' . $file)) && is_file(@phpbb_realpath($phpbb_root_path . $dirname . '/' . $file . '/lang_' . $file_to_check . '.' . $phpEx)) )
		{
			$filename = trim(str_replace("lang_", "", $file));
			$displayname = preg_replace("/^(.*?)_(.*)$/", "\\1 [ \\2 ]", $filename);
			$displayname = preg_replace("/\[(.*?)_(.*)\]/", "[ \\1 - \\2 ]", $displayname);
			$lg[$displayname] = $filename;
		}
	}

	closedir($dir);

	@asort($lg);
	@reset($lg);

	if ( count($lg) )
	{
		$lang_select = '<select name="' . $select_name . '">';
		while ( list($displayname, $filename) = @each($lg) )
		{
			$selected = ( strtolower($default) == strtolower($filename) ) ? ' selected="selected"' : '';
			$lang_select .= '<option value="' . $filename . '"' . $selected . '>' . ucwords($displayname) . '</option>';
		}
		$lang_select .= '</select>';
	}
	else
	{
		$lang_select = $lang['No_selectable_language'];
	}

	return $lang_select;
}

function style_select($default_style, $select_name = "style", $dirname = "templates")
{
	global $db;

	$sql = "SELECT themes_id, style_name
		FROM " . THEMES_TABLE . "
		ORDER BY template_name, themes_id";
	if ( !($result = $db->sql_query($sql)) )
	{
		erc_throw_error('Couldn\'t query themes table', __LINE__, __FILE__, $sql);
	}

	$style_select = '<select name="' . $select_name . '">';
	while ( $row = $db->sql_fetchrow($result) )
	{
		$selected = ( $row['themes_id'] == $default_style ) ? ' selected="selected"' : '';
		$style_select .= '<option value="' . $row['themes_id'] . '"' . $selected . '>' . htmlspecialchars($row['style_name']) . '</option>';
	}
	$db->sql_freeresult($result);
	$style_select .= "</select>";

	return $style_select;
}

function check_authorisation($die = TRUE)
{
	global $db, $lang, $dbuname, $dbpasswd, $option, $HTTP_POST_VARS;

	$auth_method = ( isset($HTTP_POST_VARS['auth_method']) ) ? htmlspecialchars($HTTP_POST_VARS['auth_method']) : '';
	$board_user = isset($HTTP_POST_VARS['board_user']) ? trim(htmlspecialchars($HTTP_POST_VARS['board_user'])) : '';
	$board_user = substr(str_replace("\\'", "'", $board_user), 0, 25);
	$board_user = str_replace("'", "\\'", $board_user);
	$board_password = ( isset($HTTP_POST_VARS['board_password']) ) ? $HTTP_POST_VARS['board_password'] : '';
	$db_user = ( isset($HTTP_POST_VARS['db_user']) ) ? $HTTP_POST_VARS['db_user'] : '';
	$db_password = ( isset($HTTP_POST_VARS['db_password']) ) ? $HTTP_POST_VARS['db_password'] : '';
	// Change authentication mode if selected option does not allow database authentication
	if ( $option == 'rld' || $option == 'rtd' )
	{
		$auth_method = 'board';
	}

	switch ($auth_method)
	{
		case 'board':
			$sql = "SELECT user_id, username, user_password, user_active, user_level
				FROM " . USERS_TABLE . "
				WHERE username = '" . str_replace("\\'", "''", $board_user) . "'";
			if ( !($result = $db->sql_query($sql)) )
			{
				erc_throw_error('Error in obtaining userdata', __LINE__, __FILE__, $sql);
			}
			if( $row = $db->sql_fetchrow($result) )
			{
				if( md5($board_password) == $row['user_password'] && $row['user_active'] && $row['user_level'] == ADMIN )
				{
					$allow_access = TRUE;
				}
				else
				{
					$allow_access = FALSE;
				}
			}
			else
			{
				$allow_access = FALSE;
			}
			$db->sql_freeresult($result);
			break;
		case 'db':
			if ($db_user == $dbuname && $db_password == $dbpasswd)
			{
				$allow_access = TRUE;
			}
			else
			{
				$allow_access = FALSE;
			}
			break;
		default:
			$allow_access = FALSE;
	}
	if ( !$allow_access && $die )
	{
?>
	<p><span style="color:red"><?php echo $lang['Auth_failed']; ?></span></p>
</body>
</html>
<?php
		exit;
	}
	return $allow_access;
}

function get_config_data($option)
{
	global $db;
	
	$sql = "SELECT config_value
		FROM " . CONFIG_TABLE . "
		WHERE config_name = '$option'";
	$result = $db->sql_query($sql);
	if ( !$result )
	{
		erc_throw_error("Couldn't get config data!", __LINE__, __FILE__, $sql);
	}
	$row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	if ( !$row )
	{
		erc_throw_error("Config data does not exist!", __LINE__, __FILE__, $sql);
	}

	return $row['config_value'];
}

function success_message($text)
{
	global $lang, $lg, $HTTP_SERVER_VARS;
	
?>
	<p><?php echo $text; ?></p>
	<p style="text-align:center"><a href="<?php echo $HTTP_SERVER_VARS['PHP_SELF'] . '?lg=' . $lg; ?>"><?php echo $lang['Return_ERC']; ?></a></p>
<?php
}

?>