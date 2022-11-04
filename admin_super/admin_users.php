<?php
/** 
*
* @package admin_super
* @version $Id: admin_users.php,v 1.57.2.26 2004/03/25 15:57:20 acydburn Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	$module['Users']['Manage'] = $filename;

	return;
}

if ( isset($HTTP_POST_VARS['mode']) )
{
	if ( $HTTP_POST_VARS['mode'] == 'lookup' )
	{
		$no_page_header = true;
	}
}

//
// Let's set the root dir for phpBB
//
$phpbb_root_path = './../';
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);
require($phpbb_root_path . 'includes/bbcode.'.$phpEx);
require($phpbb_root_path . 'includes/functions_post.'.$phpEx);
require($phpbb_root_path . 'includes/functions_selects.'.$phpEx);
require($phpbb_root_path . 'includes/functions_validate.'.$phpEx);

//
// Include language
//
$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_thread_kicker.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_thread_kicker.' . $phpEx);
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_myinfo.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_myinfo.' . $phpEx);


$html_entities_match = array('#<#', '#>#');
$html_entities_replace = array('&lt;', '&gt;');

//
// Set mode
//
if( isset( $HTTP_POST_VARS['mode'] ) || isset( $HTTP_GET_VARS['mode'] ) )
{
	$mode = ( isset( $HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
	$mode = htmlspecialchars($mode); 
}
else
{
	$mode = '';
}

//
// Quick Administrator Edit return to forum
//
if ( isset( $HTTP_POST_VARS['returntoprofile'] ) || isset( $HTTP_GET_VARS['returntoprofile'] ) )
{
	$return_to_profile = ( isset( $HTTP_POST_VARS['returntoprofile'] ) ) ? $HTTP_POST_VARS['returntoprofile'] : $HTTP_GET_VARS['returntoprofile'];
	$return_to_profile = intval($return_to_profile);
}

else
{
	$return_to_profile = 0;
}

//
// Begin program
//
	
// 
// Set username
// 
if( isset( $HTTP_GET_VARS['username'] ) )
{
	$HTTP_POST_VARS['username'] = $HTTP_GET_VARS['username'];
}

if( $mode == 'edit' || $mode == 'save' && ( isset($HTTP_POST_VARS['username']) || isset($HTTP_GET_VARS[POST_USERS_URL]) || isset( $HTTP_POST_VARS[POST_USERS_URL]) ) )
{
	attachment_quota_settings('user', $HTTP_POST_VARS['submit'], $mode);

	//
	// Ok, the profile has been modified and submitted, let's update
	//
	if( ( $mode == 'save' && isset( $HTTP_POST_VARS['submit'] ) ) || isset( $HTTP_POST_VARS['avatargallery'] ) || isset( $HTTP_POST_VARS['submitavatar'] ) || isset( $HTTP_POST_VARS['cancelavatar'] ) )
	{
		$user_id = intval( $HTTP_POST_VARS['id'] );

		if (!($this_userdata = get_userdata($user_id)))
		{
			message_die(GENERAL_MESSAGE, $lang['No_user_id_specified'] );
		}

		$this_userdata['xdata'] = get_user_xdata($user_id);

		if( $HTTP_POST_VARS['deleteuser'] && ( $userdata['user_id'] != $user_id ) )
		{
			if( $this_userdata['user_level'] == ADMIN ) 
			{ 
				message_die(GENERAL_MESSAGE, $lang['Cannot_delete_admin']); 
			} 

			$sql = "SELECT g.group_id 
				FROM " . USER_GROUP_TABLE . " ug, " . GROUPS_TABLE . " g  
				WHERE ug.user_id = $user_id 
					AND g.group_id = ug.group_id 
					AND g.group_single_user = 1";
			if( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not obtain group information for this user', '', __LINE__, __FILE__, $sql);
			}
			$row = $db->sql_fetchrow($result);
			
			$sql = "UPDATE " . POSTS_TABLE . "
				SET poster_id = " . DELETED . ", post_username = '" . str_replace("\\'", "''", addslashes($this_userdata['username'])) . "' 
				WHERE poster_id = $user_id";
			if( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not update posts for this user', '', __LINE__, __FILE__, $sql);
			}

			$sql = "UPDATE " . TOPICS_TABLE . "
				SET topic_poster = " . DELETED . " 
				WHERE topic_poster = $user_id";
			if( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not update topics for this user', '', __LINE__, __FILE__, $sql);
			}
			
			$sql = "UPDATE " . VOTE_USERS_TABLE . "
				SET vote_user_id = " . DELETED . "
				WHERE vote_user_id = $user_id";
			if( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not update votes for this user', '', __LINE__, __FILE__, $sql);
			}
			
			$sql = "UPDATE " . GROUPS_TABLE . "
				SET group_moderator = " . $userdata['user_id'] . "
			WHERE group_moderator = $user_id";
			if( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not update group moderators', '', __LINE__, __FILE__, $sql);
			}

			$sql = "DELETE FROM " . USERS_TABLE . "
				WHERE user_id = $user_id";
			if( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not delete user', '', __LINE__, __FILE__, $sql);
			}

			$sql = "DELETE FROM " . USER_GROUP_TABLE . "
				WHERE user_id = $user_id";
			if( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not delete user from user_group table', '', __LINE__, __FILE__, $sql);
			}

			$sql = "DELETE FROM " . GROUPS_TABLE . "
				WHERE group_id = " . $row['group_id'];
			if( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not delete group for this user', '', __LINE__, __FILE__, $sql);
			}

			$sql = "DELETE FROM " . AUTH_ACCESS_TABLE . "
				WHERE group_id = " . $row['group_id'];
			if( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not delete group for this user', '', __LINE__, __FILE__, $sql);
			}

			$sql = "DELETE FROM " . FORUMS_WATCH_TABLE . "
				WHERE user_id = $user_id";
			if ( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not delete user from forum watch table', '', __LINE__, __FILE__, $sql);
			}

			$sql = "DELETE FROM " . TOPICS_WATCH_TABLE . "
				WHERE user_id = $user_id";
			if ( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not delete user from topic watch table', '', __LINE__, __FILE__, $sql);
			}
			
			$sql = "DELETE FROM " . BANLIST_TABLE . "
				WHERE ban_userid = $user_id";
			if ( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not delete user from banlist table', '', __LINE__, __FILE__, $sql);
			}

			$sql = "DELETE FROM " . SESSIONS_TABLE . "
            	WHERE session_user_id = $user_id";
         	if ( !$db->sql_query($sql) )
         	{
         		message_die(GENERAL_ERROR, 'Could not delete sessions for this user', '', __LINE__, __FILE__, $sql);
         	}
         
         	$sql = "DELETE FROM " . SESSIONS_KEYS_TABLE . "
         		WHERE user_id = $user_id";
         	if ( !$db->sql_query($sql) )
         	{
         		message_die(GENERAL_ERROR, 'Could not delete auto-login keys for this user', '', __LINE__, __FILE__, $sql);
         	}
			
			$sql = "DELETE FROM " . BANK_TABLE . "
				WHERE user_id = $user_id";
			if( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not delete user from bank table', '', __LINE__, __FILE__, $sql);
			}

			$sql = "DELETE FROM " . REFERRAL_TABLE . " 
				WHERE nuid = $user_id"; 
			if( !$db->sql_query($sql) ) 
			{ 
				message_die(GENERAL_ERROR, 'Could not delete user from referral table', '', __LINE__, __FILE__, $sql); 
			}

    		$sql = "SELECT pic_filename, pic_thumbnail 
    	    	FROM " . ALBUM_TABLE . " 
    	    	WHERE pic_user_id = $user_id
    	    		AND pic_cat_id = 0"; 
    	  	if( !($result = $db->sql_query($sql)) ) 
      		{	 
        		message_die(GENERAL_ERROR, 'Could not obtain album information for this user', '', __LINE__, __FILE__, $sql); 
      		} 
      			
      		while ($row = $db->sql_fetchrow($result)) 
      		{ 
      			@unlink($phpbb_root_path . ALBUM_UPLOAD_PATH . $row['pic_filename']); 
      			@unlink($phpbb_root_path . ALBUM_CACHE_PATH . $row['pic_thumbnail']); 
      		} 
			$db->sql_freeresult($result);

			$sql = "DELETE FROM " . ALBUM_TABLE . " 
				WHERE pic_user_id = $user_id
					AND pic_cat_id = 0";
			if( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not delete album information for this user', '', __LINE__, __FILE__, $sql);
			}

         	$sql = "UPDATE " . ALBUM_TABLE . " 
				SET pic_user_id = -1               
        		WHERE pic_user_id = $user_id"; 
	         if( !($result = $db->sql_query($sql)) ) 
        	{ 
    			message_die(GENERAL_ERROR, 'Could not update album information for this user', '', __LINE__, __FILE__, $sql); 
        	} 

			$sql = "UPDATE " . ALBUM_COMMENT_TABLE . "
				SET comment_user_id = -1
         		WHERE comment_user_id = $user_id"; 
         	if( !$db->sql_query($sql) ) 
         	{ 
				message_die(GENERAL_ERROR, 'Could not update album comment information for this user', '', __LINE__, __FILE__, $sql);
         	} 

			$sql = "UPDATE " . ALBUM_RATE_TABLE . "
				SET rate_user_id = -1
         	   WHERE rate_user_id = $user_id"; 
         	if( !$db->sql_query($sql) ) 
         	{ 
				message_die(GENERAL_ERROR, 'Could not update album rating information for this user', '', __LINE__, __FILE__, $sql);
         	}

			$sql = "DELETE FROM " . BUDDY_LIST_TABLE . "
				WHERE user_id = $user_id
					OR contact_id = $user_id";
			if( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not delete user buddylist', '', __LINE__, __FILE__, $sql);
			}

			$sql = "DELETE FROM " . IM_PREFS_TABLE . "
				WHERE user_id = $user_id";
			if( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not delete user IM prefs', '', __LINE__, __FILE__, $sql);
			}

			$sql = "DELETE FROM " . $table_prefix . "ina_ban
				WHERE id = $user_id
					OR username = '" . phpbb_clean_username($this_userdata['username']) . "'";
			if( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not delete game ban', '', __LINE__, __FILE__, $sql);
			}

			$sql = "DELETE FROM " . $table_prefix . "ina_challenge_tracker
				WHERE user = $user_id";
			if( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not delete game challenge count', '', __LINE__, __FILE__, $sql);
			}

			$sql = "DELETE FROM " . $table_prefix . "ina_challenge_users
				WHERE user_to = $user_id
					OR user_from = $user_id";
			if( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not delete game challenges', '', __LINE__, __FILE__, $sql);
			}

			$sql = "DELETE FROM " . $table_prefix . "ina_favorites
				WHERE user = $user_id";
			if( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not delete game favorites', '', __LINE__, __FILE__, $sql);
			}
			
			$sql = "DELETE FROM " . $table_prefix . "ina_rating_votes
				WHERE player = $user_id";
			if( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not delete game votes', '', __LINE__, __FILE__, $sql);
			}

			$sql = "DELETE FROM " . iNA_SCORES . "
				WHERE player = $user_id";
			if( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not delete game scores', '', __LINE__, __FILE__, $sql);
			}

			$sql = "DELETE FROM " . $table_prefix . "ina_sessions
				WHERE playing_id = $user_id";
			if( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not delete game sessions', '', __LINE__, __FILE__, $sql);
			}

			$sql = "DELETE FROM " . $table_prefix . "ina_top_scores
				WHERE player = $user_id";
			if( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not delete game top scores', '', __LINE__, __FILE__, $sql);
			}

			$sql = "DELETE FROM " . $table_prefix . "ina_trophy_comments
				WHERE player = $user_id";
			if( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not delete game trophy comments', '', __LINE__, __FILE__, $sql);
			}

			$sql = "DELETE FROM " . KB_ARTICLES_TABLE . "
				WHERE article_author_id = $user_id";
			if( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not delete user Knowledge Base articles', '', __LINE__, __FILE__, $sql);
			}

	$sql = "DELETE FROM " . $table_prefix . "links
				WHERE user_id = $user_id";
			if( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not delete user links', '', __LINE__, __FILE__, $sql);
			}

	$sql = "DELETE FROM " . $table_prefix . "link_comments
		WHERE poster_id = $user_id";
	if( !$db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Could not delete user link comments', '', __LINE__, __FILE__, $sql);
	}

	$sql = "DELETE FROM " . $table_prefix . "link_votes
		WHERE user_id = $user_id";
	if( !$db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Could not delete user link votes', '', __LINE__, __FILE__, $sql);
	}

			$sql = "DELETE FROM " . SHOPTRANS_TABLE . "
				WHERE trans_user = $user_id";
			if( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not delete user shop transactions', '', __LINE__, __FILE__, $sql);
			}

			$sql = "DELETE FROM " . TRANSACTION_TABLE . "
				WHERE trans_from = '" . phpbb_clean_username($this_userdata['username']) . "'
					OR trans_to = '" . phpbb_clean_username($this_userdata['username']) . "'";
			if( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not delete user transactions', '', __LINE__, __FILE__, $sql);
			}

			$sql = "DELETE FROM " . TOPICS_VIEWDATA_TABLE . "
				WHERE user_id = $user_id";
			if( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not delete user topic viewdata', '', __LINE__, __FILE__, $sql);
			}

			$sql = "DELETE FROM " . TABLE_USER_SHOPS . "
				WHERE user_id = $user_id";
			if( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not delete user personal shop', '', __LINE__, __FILE__, $sql);
			}

			$sql = "DELETE FROM " . DIGEST_TABLE . "
				WHERE user_id = $user_id";
			if (!$db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not delete user digests', '', __LINE__, __FILE__, $sql);
			}

			$sql = "DELETE FROM " . DIGEST_FORUMS_TABLE . "
				WHERE user_id = $user_id";
			if (!$db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not delete user from digest forums', '', __LINE__, __FILE__, $sql);
			}

			$sql = "DELETE FROM " . NOTES_TABLE . "
				WHERE poster_id = $user_id";
			if (!$db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not delete user notes', '', __LINE__, __FILE__, $sql);
			}

         	$sql = "UPDATE " . ACCT_HIST_TABLE . " 
				SET user_id = -1               
				WHERE user_id = $user_id";
			if (!$db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not update user subscription history', '', __LINE__, __FILE__, $sql);
			}

			$sql = "DELETE FROM " . LOTTERY_HISTORY_TABLE . "
				WHERE user_id = $user_id";
			if (!$db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not delete user lottery history', '', __LINE__, __FILE__, $sql);
			}

			$sql = "DELETE FROM " . FORUMS_DESC_TABLE . "
				WHERE user_id = $user_id";
			if (!$db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not delete user forum toggle settings', '', __LINE__, __FILE__, $sql);
			}

			$sql = "DELETE FROM " . PROFILE_VIEW_TABLE . "
				WHERE user_id = $user_id
					OR viewer_id = $user_id";
			if( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not delete profile views', '', __LINE__, __FILE__, $sql);
			}

         	$sql = "UPDATE " . PA_COMMENTS_TABLE . " 
				SET poster_id = -1               
         		WHERE poster_id = $user_id"; 
			if (!$db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not update pafiledb comments for this user', '', __LINE__, __FILE__, $sql);
			}

			$sql = "DELETE FROM " . PA_VOTES_TABLE . "
				WHERE user_id = $user_id";
			if( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not delete pafiledb votes', '', __LINE__, __FILE__, $sql);
			}

         	$sql = "UPDATE " . PA_DOWNLOAD_INFO_TABLE . " 
				SET user_id = -1               
         		WHERE user_id = $user_id"; 
			if (!$db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not update pafiledb download history', '', __LINE__, __FILE__, $sql);
			}

         	$sql = "UPDATE " . PA_FILES_TABLE . " 
				SET user_id = -1               
         		WHERE user_id = $user_id"; 
			if (!$db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not update pafiledb file uploader', '', __LINE__, __FILE__, $sql);
			}

			$sql = "SELECT privmsgs_id
				FROM " . PRIVMSGS_TABLE . "
				WHERE privmsgs_from_userid = $user_id 
					OR privmsgs_to_userid = $user_id";
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not select all users private messages', '', __LINE__, __FILE__, $sql);
			}

			// This little bit of code directly from the private messaging section.
			while ( $row_privmsgs = $db->sql_fetchrow($result) )
			{
				$mark_list[] = $row_privmsgs['privmsgs_id'];
			}
			$db->sql_freeresult($result);
			
			if ( sizeof($mark_list) )
			{
				$delete_sql_id = implode(', ', $mark_list);
				
				$delete_text_sql = "DELETE FROM " . PRIVMSGS_TEXT_TABLE . "
					WHERE privmsgs_text_id IN ($delete_sql_id)";
				$delete_sql = "DELETE FROM " . PRIVMSGS_TABLE . "
					WHERE privmsgs_id IN ($delete_sql_id)";
				
				if ( !$db->sql_query($delete_sql) )
				{
					message_die(GENERAL_ERROR, 'Could not delete private message info', '', __LINE__, __FILE__, $delete_sql);
				}
				
				if ( !$db->sql_query($delete_text_sql) )
				{
					message_die(GENERAL_ERROR, 'Could not delete private message text', '', __LINE__, __FILE__, $delete_text_sql);
				}
			}

			$message = $lang['User_deleted'] . '<br /><br />' . sprintf($lang['Click_return_useradmin'], '<a href="' . append_sid("admin_users.$phpEx") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.$phpEx?pane=right") . '">', '</a>');

			message_die(GENERAL_MESSAGE, $message);
		}

		//
		// Main Admin's profile can't be modified
		//
		if ( $user_id == 2 && $userdata['user_id'] != 2 )
		{
		   message_die(GENERAL_MESSAGE, $lang['Main_Admin_Unchanged_Profile'] );
		}

		$username = ( !empty($HTTP_POST_VARS['username']) ) ? phpbb_clean_username($HTTP_POST_VARS['username']) : '';
		$realname = ( !empty($HTTP_POST_VARS['realname']) ) ? trim(strip_tags( $HTTP_POST_VARS['realname'] ) ) : '';
		$email = ( !empty($HTTP_POST_VARS['email']) ) ? trim(strip_tags(htmlspecialchars( $HTTP_POST_VARS['email'] ) )) : '';
		$zipcode = ( !empty($HTTP_POST_VARS['zipcode']) ) ? trim(strip_tags( $HTTP_POST_VARS['zipcode'] ) ) : '';
		$password = ( !empty($HTTP_POST_VARS['password']) ) ? trim(strip_tags(htmlspecialchars( $HTTP_POST_VARS['password'] ) )) : '';
		$password_confirm = ( !empty($HTTP_POST_VARS['password_confirm']) ) ? trim(strip_tags(htmlspecialchars( $HTTP_POST_VARS['password_confirm'] ) )) : '';
		$points = intval($HTTP_POST_VARS['points']);
		$allow_points = ( !empty($HTTP_POST_VARS['allow_points']) ) ? intval( $HTTP_POST_VARS['allow_points'] ) : 0;
		$icq = ( !empty($HTTP_POST_VARS['icq']) ) ? trim(strip_tags( $HTTP_POST_VARS['icq'] ) ) : '';
		$aim = ( !empty($HTTP_POST_VARS['aim']) ) ? trim(strip_tags( $HTTP_POST_VARS['aim'] ) ) : '';
		$msn = ( !empty($HTTP_POST_VARS['msn']) ) ? trim(strip_tags( $HTTP_POST_VARS['msn'] ) ) : '';
		$yim = ( !empty($HTTP_POST_VARS['yim']) ) ? trim(strip_tags( $HTTP_POST_VARS['yim'] ) ) : '';
      	$xfi = ( !empty($HTTP_POST_VARS['xfi']) ) ? trim(strip_tags( $HTTP_POST_VARS['xfi'] ) ) : '';
		$skype = ( !empty($HTTP_POST_VARS['skype']) ) ? trim(strip_tags( $HTTP_POST_VARS['skype'] ) ) : '';
		$gtalk = ( !empty($HTTP_POST_VARS['gtalk']) ) ? trim(strip_tags( $HTTP_POST_VARS['gtalk'] ) ) : '';
		$website = ( !empty($HTTP_POST_VARS['website']) ) ? trim(strip_tags( $HTTP_POST_VARS['website'] ) ) : '';
		$stumble = ( !empty($HTTP_POST_VARS['stumble']) ) ? trim(strip_tags( $HTTP_POST_VARS['stumble'] ) ) : '';
		$location = ( !empty($HTTP_POST_VARS['location']) ) ? trim(strip_tags( $HTTP_POST_VARS['location'] ) ) : '';
		$occupation = ( !empty($HTTP_POST_VARS['occupation']) ) ? trim(strip_tags( $HTTP_POST_VARS['occupation'] ) ) : '';
		$interests = ( !empty($HTTP_POST_VARS['interests']) ) ? trim(strip_tags( $HTTP_POST_VARS['interests'] ) ) : '';
		if (isset($HTTP_POST_VARS['birthday']) )
		{
			$birthday = intval ($HTTP_POST_VARS['birthday']);
			$b_day = realdate('j',$birthday); 
			$b_md = realdate('n',$birthday); 
			$b_year = realdate('Y',$birthday);
		} 
		else
		{
			$b_day = ( isset($HTTP_POST_VARS['b_day']) ) ? intval ($HTTP_POST_VARS['b_day']) : 0;
			$b_md = ( isset($HTTP_POST_VARS['b_md']) ) ? intval ($HTTP_POST_VARS['b_md']) : 0;
			$b_year = ( isset($HTTP_POST_VARS['b_year']) ) ? intval ($HTTP_POST_VARS['b_year']) : 0;
			$birthday = mkrealdate($b_day,$b_md,$b_year);
		}
		$next_birthday_greeting = ( !empty($HTTP_POST_VARS['next_birthday_greeting']) ) ? intval( $HTTP_POST_VARS['next_birthday_greeting'] ) : 0;
		$gender = ( isset($HTTP_POST_VARS['gender']) ) ? intval ($HTTP_POST_VARS['gender']) : 0;
		$signature = ( !empty($HTTP_POST_VARS['signature']) ) ? trim(str_replace('<br />', "\n", $HTTP_POST_VARS['signature'] ) ) : '';
		$custom_post_color = ( !empty($HTTP_POST_VARS['custom_post_color']) ) ? trim(strip_tags( $HTTP_POST_VARS['custom_post_color'] ) ) : ''; 
		$karma_plus = intval($HTTP_POST_VARS['karma_plus']);
		$karma_minus = intval($HTTP_POST_VARS['karma_minus']);

		$xdata = array();
		$xd_meta = get_xd_metadata();
		foreach ($xd_meta as $name => $info)
		{
			if ( !empty($HTTP_POST_VARS[$name]) && $info['handle_input'] )
			{
				$xdata[$name] = trim(str_replace('<br />', "\n", $HTTP_POST_VARS[$name] ) );
			}
		}

		validate_optional_fields($icq, $aim, $msn, $yim, $xfi, $skype, $gtalk, $website, $stumble, $location, $occupation, $interests, $signature, $irc_commands, $custom_post_color);

		$viewemail = ( isset( $HTTP_POST_VARS['viewemail']) ) ? ( ( $HTTP_POST_VARS['viewemail'] ) ? TRUE : 0 ) : 0;
		$popup_notes = ( isset( $HTTP_POST_VARS['popup_notes']) ) ? ( ( $HTTP_POST_VARS['popup_notes'] ) ? TRUE : 0 ) : 0;
		$profile_view_popup = ( isset( $HTTP_POST_VARS['profile_view_popup']) ) ? ( ( $HTTP_POST_VARS['profile_view_popup'] ) ? TRUE : 0 ) : 0;
		$allowviewonline = ( isset( $HTTP_POST_VARS['hideonline']) ) ? ( ( $HTTP_POST_VARS['hideonline'] ) ? 0 : TRUE ) : TRUE;
		$notifyreply = ( isset( $HTTP_POST_VARS['notifyreply']) ) ? ( ( $HTTP_POST_VARS['notifyreply'] ) ? TRUE : 0 ) : 0;
		$notifypm = ( isset( $HTTP_POST_VARS['notifypm']) ) ? ( ( $HTTP_POST_VARS['notifypm'] ) ? TRUE : 0 ) : TRUE;
		$notifypmtext = ( isset( $HTTP_POST_VARS['notifypmtext']) ) ? ( ( $HTTP_POST_VARS['notifypmtext'] ) ? TRUE : 0 ) : TRUE;
		$popuppm = ( isset( $HTTP_POST_VARS['popup_pm']) ) ? ( ( $HTTP_POST_VARS['popup_pm'] ) ? TRUE : 0 ) : TRUE;
		$soundpm = ( isset( $HTTP_POST_VARS['sound_pm']) ) ? ( ( $HTTP_POST_VARS['sound_pm'] ) ? TRUE : 0 ) : 0; 
		$attachsig = ( isset( $HTTP_POST_VARS['attachsig']) ) ? ( ( $HTTP_POST_VARS['attachsig'] ) ? TRUE : 0 ) : 0;
		$allowhtml = ( isset( $HTTP_POST_VARS['allowhtml']) ) ? intval( $HTTP_POST_VARS['allowhtml'] ) : $board_config['allow_html'];
		$allowbbcode = ( isset( $HTTP_POST_VARS['allowbbcode']) ) ? intval( $HTTP_POST_VARS['allowbbcode'] ) : $board_config['allow_bbcode'];
		$allowsmilies = ( isset( $HTTP_POST_VARS['allowsmilies']) ) ? intval( $HTTP_POST_VARS['allowsmilies'] ) : $board_config['allow_smilies'];
		$allowsigs = ( isset( $HTTP_POST_VARS['allowsigs']) ) ? intval( $HTTP_POST_VARS['allowsigs'] ) : $board_config['allow_sig'];
		$allowavatars = ( isset( $HTTP_POST_VARS['allowavatars']) ) ? intval( $HTTP_POST_VARS['allowavatars'] ) : '';
		$user_style = ( isset( $HTTP_POST_VARS['style']) ) ? intval( $HTTP_POST_VARS['style'] ) : $board_config['default_style'];
		$user_lang = ( $HTTP_POST_VARS['language'] ) ? $HTTP_POST_VARS['language'] : $board_config['default_lang'];
		$user_timezone = ( isset( $HTTP_POST_VARS['timezone']) ) ? doubleval( $HTTP_POST_VARS['timezone'] ) : $board_config['board_timezone'];
		$user_flag = ( !empty($HTTP_POST_VARS['user_flag']) ) ? $HTTP_POST_VARS['user_flag'] : '' ;
		$user_dateformat = ( $HTTP_POST_VARS['dateformat'] ) ? trim( $HTTP_POST_VARS['dateformat'] ) : $board_config['default_dateformat'];
		$user_clockformat = ( $HTTP_POST_VARS['clockformat'] ) ? trim( $HTTP_POST_VARS['clockformat'] ) : $board_config['default_clock'];
		$user_avatar_local = ( isset( $HTTP_POST_VARS['avatarselect'] ) && !empty($HTTP_POST_VARS['submitavatar'] ) && $board_config['allow_avatar_local'] ) ? $HTTP_POST_VARS['avatarselect'] : ( ( isset( $HTTP_POST_VARS['avatarlocal'] )  ) ? $HTTP_POST_VARS['avatarlocal'] : '' );
		$user_avatar_category = ( isset($HTTP_POST_VARS['avatarcatname']) && $board_config['allow_avatar_local'] ) ? htmlspecialchars($HTTP_POST_VARS['avatarcatname']) : '' ;
		$user_avatar_remoteurl = ( !empty($HTTP_POST_VARS['avatarremoteurl']) ) ? trim( $HTTP_POST_VARS['avatarremoteurl'] ) : '';
		$user_avatar_url = ( !empty($HTTP_POST_VARS['avatarurl']) ) ? trim( $HTTP_POST_VARS['avatarurl'] ) : '';
		$user_avatar_loc = ( $HTTP_POST_FILES['avatar']['tmp_name'] != "none") ? $HTTP_POST_FILES['avatar']['tmp_name'] : '';
		$user_avatar_name = ( !empty($HTTP_POST_FILES['avatar']['name']) ) ? $HTTP_POST_FILES['avatar']['name'] : '';
		$user_avatar_size = ( !empty($HTTP_POST_FILES['avatar']['size']) ) ? $HTTP_POST_FILES['avatar']['size'] : 0;
		$user_avatar_filetype = ( !empty($HTTP_POST_FILES['avatar']['type']) ) ? $HTTP_POST_FILES['avatar']['type'] : '';
		$user_avatar = ( empty($user_avatar_loc) ) ? $this_userdata['user_avatar'] : '';
		$user_avatar_type = ( empty($user_avatar_loc) ) ? $this_userdata['user_avatar_type'] : '';		
		$user_status = ( !empty($HTTP_POST_VARS['user_status']) ) ? intval( $HTTP_POST_VARS['user_status'] ) : 0;
		$user_ycard = ( !empty($HTTP_POST_VARS['user_ycard']) ) ? intval( $HTTP_POST_VARS['user_ycard'] ) : 0;
		$user_bkcard = ( !empty($HTTP_POST_VARS['user_bkcard']) ) ? intval( $HTTP_POST_VARS['user_bkcard'] ) : 0;
		$user_allowpm = ( !empty($HTTP_POST_VARS['user_allowpm']) ) ? intval( $HTTP_POST_VARS['user_allowpm'] ) : 0;
		$user_allow_profile = ( !empty($HTTP_POST_VARS['user_allow_profile']) ) ? intval( $HTTP_POST_VARS['user_allow_profile'] ) : 0;
		$user_rank = ( !empty($HTTP_POST_VARS['user_rank']) ) ? intval( $HTTP_POST_VARS['user_rank'] ) : 0;
		$user_allowavatar = ( !empty($HTTP_POST_VARS['user_allowavatar']) ) ? intval( $HTTP_POST_VARS['user_allowavatar'] ) : 0;
		$kicker_ban = ( !empty($HTTP_POST_VARS['kicker_ban']) ) ? intval( $HTTP_POST_VARS['kicker_ban'] ) : 0;
		$myInfo = ( !empty($HTTP_POST_VARS['myInfo']) ) ? trim(str_replace('<br />', "\n", $HTTP_POST_VARS['myInfo'] ) ) : '';
		$email_validation = ( !empty($HTTP_POST_VARS['email_validation']) ) ? intval( $HTTP_POST_VARS['email_validation'] ) : 0;
		$daily_post_limit = ( !empty($HTTP_POST_VARS['daily_post_limit']) ) ? trim(strip_tags( $HTTP_POST_VARS['daily_post_limit'] ) ) : 0;
		$user_wordwrap = ( $HTTP_POST_VARS['user_wordwrap'] ) ? intval( $HTTP_POST_VARS['user_wordwrap'] ) : $board_config['wrap_def'];
		$user_allowsig = ( !empty($HTTP_POST_VARS['user_allowsig']) ) ? intval( $HTTP_POST_VARS['user_allowsig'] ) : 0;
		$group_priority = ( isset($HTTP_POST_VARS['group_priority']) ) ? intval($HTTP_POST_VARS['group_priority']) : 0;

		if( isset( $HTTP_POST_VARS['avatargallery'] ) || isset( $HTTP_POST_VARS['submitavatar'] ) || isset( $HTTP_POST_VARS['cancelavatar'] ) )
		{
			$username = stripslashes($username);
			$realname = stripslashes($realname);
			$email = stripslashes($email);
			$zipcode = stripslashes($zipcode);
			$password = $password_confirm = '';
			$points = intval($points);
			$icq = stripslashes($icq);
			$aim = htmlspecialchars(stripslashes($aim));
			$msn = htmlspecialchars(stripslashes($msn));
			$yim = htmlspecialchars(stripslashes($yim));
         	$xfi = htmlspecialchars(stripslashes($xfi));
			$skype = htmlspecialchars(stripslashes($skype));
			$gtalk = htmlspecialchars(stripslashes($gtalk));
			$website = htmlspecialchars(stripslashes($website));
			$stumble = htmlspecialchars(stripslashes($stumble));
			$location = htmlspecialchars(stripslashes($location));
			$occupation = htmlspecialchars(stripslashes($occupation));
			$interests = htmlspecialchars(stripslashes($interests));
			$signature = htmlspecialchars(stripslashes($signature));
			$func = create_function('$a', 'return htmlspecialchars(stripslashes($a));');
			$xdata = array_map($func, $xdata);
			$user_lang = stripslashes($user_lang);
			$user_dateformat = htmlspecialchars(stripslashes($user_dateformat));
			$user_clockformat = htmlspecialchars(stripslashes($user_clockformat));
			$custom_post_color = htmlspecialchars(stripslashes($custom_post_color));
			$karma_plus = intval($karma_plus);
			$karma_minus = intval($karma_minus);
			$myInfo = htmlspecialchars(stripslashes($myInfo));

			if ( !isset($HTTP_POST_VARS['cancelavatar'])) 
			{
				$user_avatar = $user_avatar_category . '/' . $user_avatar_local;
				$user_avatar_type = USER_AVATAR_GALLERY;
			}
		}
	}

	if( isset( $HTTP_POST_VARS['submit'] ) )
	{
		include($phpbb_root_path . 'includes/usercp_avatar.'.$phpEx);

		$error = FALSE;

		if( stripslashes($username) != $this_userdata['username'] )
		{
			unset($rename_user);

			if ( stripslashes(strtolower($username)) != strtolower($this_userdata['username']) ) 
			{
				$result = validate_username($username);
				if ( $result['error'] )
				{
					$error = TRUE;
					$error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . $result['error_msg'];
				}
				else if ( strtolower(str_replace("\\'", "''", $username)) == strtolower($userdata['username']) )
				{
					$error = TRUE;
					$error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . $lang['Username_taken'];
				}
			}

			if (!$error)
			{
				$username_sql = "username = '" . str_replace("\\'", "''", $username) . "', ";
				$rename_user = $username; // Used for renaming usergroup
			}
		}

		$passwd_sql = '';
		if( !empty($password) && !empty($password_confirm) )
		{
			//
			// Awww, the user wants to change their password, isn't that cute..
			//
			if($password != $password_confirm)
			{
				$error = TRUE;
				$error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . $lang['Password_mismatch'];
			}
			else
			{
				$password = md5($password);
				$passwd_sql = "user_password = '$password', ";
			}
		}
		else if( $password && !$password_confirm )
		{
			$error = TRUE;
			$error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . $lang['Password_mismatch'];
		}
		else if( !$password && $password_confirm )
		{
			$error = TRUE;
			$error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . $lang['Password_mismatch'];
		}

		if( $signature != '' )
		{
			$sig_length_check = preg_replace('/(\[.*?)(=.*?)\]/is', '\\1]', stripslashes($signature));
			if ( $allowhtml )
			{
				$sig_length_check = preg_replace('/(\<.*?)(=.*?)( .*?=.*?)?([ \/]?\>)/is', '\\1\\3\\4', $sig_length_check);
			}

			// Only create a new bbcode_uid when there was no uid yet.
			if ( $signature_bbcode_uid == '' )
			{
				$signature_bbcode_uid = ( $allowbbcode ) ? make_bbcode_uid() : '';
			}
			
			// Limit siganture rows....
			if (!empty($board_config['max_sig_lines']) && $board_config['max_sig_lines'] > 0)
			{
				$sig_lines = explode("\n", $signature);
				if (sizeof($sig_lines) > $board_config['max_sig_lines']) 
				{ 
					$error = TRUE;
					$error_msg .=  ( ( isset($error_msg) ) ? '<br>' : '' ) . sprintf($lang['Signature_too_high'], (sizeof($sig_lines) - $board_config['max_sig_lines']));
				}
			}
	
			$signature = prepare_message($signature, $allowhtml, $allowbbcode, $allowsmilies, $signature_bbcode_uid);

			if ( strlen($sig_length_check) > $board_config['max_sig_chars'] )
			{ 
				$error = TRUE;
				$error_msg .=  ( ( isset($error_msg) ) ? '<br />' : '' ) . $lang['Signature_too_long'];
			}
			else
			{
				if( preg_match_all("#\[img(:$signature_bbcode_uid)?\]((ht|f)tp://)([^\r\n\t<\"]*?)\[/img(:$signature_bbcode_uid)?\]#sie", $signature, $matches) )
				{
					if (sizeof($matches[0]) > $board_config['sig_images_max_limit'] )
					{
						$error = TRUE;
						$l_too_many_images = ( $board_config['sig_images_max_limit'] == 1 ) ? sprintf($lang['Too_many_sig_image'], $board_config['sig_images_max_limit']) : sprintf($lang['Too_many_sig_images'], $board_config['sig_images_max_limit']);
						$error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . $l_too_many_images;
					}
					else
					{
						for( $i = 0; $i < sizeof($matches[0]); $i++ )
						{
							$image = preg_replace("#\[img(:$signature_bbcode_uid)?\]((ht|f)tp://)([^\r\n\t<\"]*?)\[/img(:$signature_bbcode_uid)?\]#si", "\\2\\4", $matches[0][$i]);
							list($width, $height) = @getimagesize($image);
							if( $width > $board_config['sig_images_max_width'] || $height > $board_config['sig_images_max_height'] )
							{
								$error = TRUE;
								$l_image_too_large = sprintf($lang['Sig_image_too_large'], $board_config['sig_images_max_width'], $board_config['sig_images_max_height']);
								$error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . $l_image_too_large;
								break;
							}
						}
					}
				}
			}
		}

		//
		// Custom Profile Fields
		//
		$xd_meta = get_xd_metadata();
		while ( list($code_name, $meta) = each($xd_meta) )
		{
			if ( $meta['handle_input'] && ( ( $mode == REGISTER_MODE && $meta['default_auth'] == XD_AUTH_ALLOW ) || xdata_auth($code_name, $userdata['user_id']) ) )
			{
				if ( ($meta['field_length'] > 0) && (strlen($xdata[$code_name]) > $meta['field_length']) )
				{
        		   	$error = TRUE;
					$error_msg .=  ( ( isset($error_msg) ) ? '<br />' : '' ) . sprintf($lang['XData_too_long'], $meta['field_name']);
				}

				if ( ( count($meta['values_array']) > 0 ) && ( ! in_array($xdata[$code_name], $meta['values_array']) ) )
				{
    	    	   	$error = TRUE;
					$error_msg .=  ( ( isset($error_msg) ) ? '<br />' : '' ) . sprintf($lang['XData_invalid'], $meta['field_name']);
				}
		
				if ( ( strlen($meta['field_regexp']) > 0 ) && ( ! preg_match($meta['field_regexp'], $xdata[$code_name]) ) )
				{
				    $error = TRUE;
					$error_msg .=  ( ( isset($error_msg) ) ? '<br />' : '' ) . sprintf($lang['XData_invalid'], $meta['field_name']);
				}
	
				if ( $meta['allow_bbcode'] )
				{
					if ( $signature_bbcode_uid == '' )
					{
						$signature_bbcode_uid = ( $allowbbcode ) ? make_bbcode_uid() : '';
					}
				}
		
				$xdata[$code_name] = prepare_message($xdata[$code_name], $meta['allow_html'], $meta['allow_bbcode'], $meta['allow_smilies'], $signature_bbcode_uid);
			}
		}
			
		//
		// Avatar stuff
		//
		$avatar_sql = '';
		if( isset($HTTP_POST_VARS['avatardel']) )
		{
			if( $this_userdata['user_avatar_type'] == USER_AVATAR_UPLOAD && $this_userdata['user_avatar'] != "" )
			{
				if( @file_exists(@phpbb_realpath('./../' . $board_config['avatar_path'] . "/" . $this_userdata['user_avatar'])) )
				{
					@unlink('./../' . $board_config['avatar_path'] . "/" . $this_userdata['user_avatar']);
				}
			}
			$avatar_sql = ", user_avatar = '', user_avatar_type = " . USER_AVATAR_NONE;
		}
		else if( ( $user_avatar_loc != "" || !empty($user_avatar_url) ) && !$error )
		{
			//
			// Only allow one type of upload, either a
			// filename or a URL
			//
			if( !empty($user_avatar_loc) && !empty($user_avatar_url) )
			{
				$error = TRUE;
				if( isset($error_msg) )
				{
					$error_msg .= "<br />";
				}
				$error_msg .= $lang['Only_one_avatar'];
			}

			if( $user_avatar_loc != "" )
			{
				if( file_exists(@phpbb_realpath($user_avatar_loc)) && ereg(".jpg$|.gif$|.png$", $user_avatar_name) )
				{
					if( $user_avatar_size <= $board_config['avatar_filesize'] && $user_avatar_size > 0)
					{
						$error_type = false;

						//
						// Opera appends the image name after the type, not big, not clever!
						//
						preg_match("'image\/[x\-]*([a-z]+)'", $user_avatar_filetype, $user_avatar_filetype);
						$user_avatar_filetype = $user_avatar_filetype[1];

						switch( $user_avatar_filetype )
						{
							case "jpeg":
							case "pjpeg":
							case "jpg":
								$imgtype = '.jpg';
								break;
							case "gif":
								$imgtype = '.gif';
								break;
							case "png":
								$imgtype = '.png';
								break;
							default:
								$error = true;
								$error_msg = (!empty($error_msg)) ? $error_msg . "<br />" . $lang['Avatar_filetype'] : $lang['Avatar_filetype'];
								break;
						}

						if( !$error )
						{
							list($width, $height) = @getimagesize($user_avatar_loc);

							if( $width <= $board_config['avatar_max_width'] && $height <= $board_config['avatar_max_height'] )
							{
								$user_id = $this_userdata['user_id'];

								$avatar_filename = $user_id . $imgtype;

								if( $this_userdata['user_avatar_type'] == USER_AVATAR_UPLOAD && $this_userdata['user_avatar'] != "" )
								{
									if( @file_exists(@phpbb_realpath("./../" . $board_config['avatar_path'] . "/" . $this_userdata['user_avatar'])) )
									{
										@unlink("./../" . $board_config['avatar_path'] . "/". $this_userdata['user_avatar']);
									}
								}
								@copy($user_avatar_loc, "./../" . $board_config['avatar_path'] . "/$avatar_filename");

								$avatar_sql = ", user_avatar = '$avatar_filename', user_avatar_type = " . USER_AVATAR_UPLOAD;
							}
							else
							{
								$l_avatar_size = sprintf($lang['Avatar_imagesize'], $board_config['avatar_max_width'], $board_config['avatar_max_height']);

								$error = true;
								$error_msg = ( !empty($error_msg) ) ? $error_msg . "<br />" . $l_avatar_size : $l_avatar_size;
							}
						}
					}
					else
					{
						$l_avatar_size = sprintf($lang['Avatar_filesize'], round($board_config['avatar_filesize'] / 1024));

						$error = true;
						$error_msg = ( !empty($error_msg) ) ? $error_msg . "<br />" . $l_avatar_size : $l_avatar_size;
					}
				}
				else
				{
					$error = true;
					$error_msg = ( !empty($error_msg) ) ? $error_msg . "<br />" . $lang['Avatar_filetype'] : $lang['Avatar_filetype'];
				}
			}
			else if( !empty($user_avatar_url) )
			{
				//
				// First check what port we should connect
				// to, look for a :[xxxx]/ or, if that doesn't
				// exist assume port 80 (http)
				//
				preg_match("/^(http:\/\/)?([\w\-\.]+)\:?([0-9]*)\/(.*)$/", $user_avatar_url, $url_ary);

				if( !empty($url_ary[4]) )
				{
					$port = (!empty($url_ary[3])) ? $url_ary[3] : 80;

					$fsock = @fsockopen($url_ary[2], $port, $errno, $errstr);
					if( $fsock )
					{
						$base_get = "/" . $url_ary[4];

						//
						// Uses HTTP 1.1, could use HTTP 1.0 ...
						//
						@fputs($fsock, "GET $base_get HTTP/1.1\r\n");
						@fputs($fsock, "HOST: " . $url_ary[2] . "\r\n");
						@fputs($fsock, "Connection: close\r\n\r\n");

						unset($avatar_data);
						while( !@feof($fsock) )
						{
							$avatar_data .= @fread($fsock, $board_config['avatar_filesize']);
						}
						@fclose($fsock);

						if( preg_match("/Content-Length\: ([0-9]+)[^\/ ][\s]+/i", $avatar_data, $file_data1) && preg_match("/Content-Type\: image\/[x\-]*([a-z]+)[\s]+/i", $avatar_data, $file_data2) )
						{
							$file_size = $file_data1[1]; 
							$file_type = $file_data2[1];

							switch( $file_type )
							{
								case "jpeg":
								case "pjpeg":
								case "jpg":
									$imgtype = '.jpg';
									break;
								case "gif":
									$imgtype = '.gif';
									break;
								case "png":
									$imgtype = '.png';
									break;
								default:
									$error = true;
									$error_msg = (!empty($error_msg)) ? $error_msg . "<br />" . $lang['Avatar_filetype'] : $lang['Avatar_filetype'];
									break;
							}

							if( !$error && $file_size > 0 && $file_size < $board_config['avatar_filesize'] )
							{
								$avatar_data = substr($avatar_data, strlen($avatar_data) - $file_size, $file_size);

								$tmp_filename = tempnam ("/tmp", $this_userdata['user_id'] . "-");
								$fptr = @fopen($tmp_filename, "wb");
								$bytes_written = @fwrite($fptr, $avatar_data, $file_size);
								@fclose($fptr);

								if( $bytes_written == $file_size )
								{
									list($width, $height) = @getimagesize($tmp_filename);

									if( $width <= $board_config['avatar_max_width'] && $height <= $board_config['avatar_max_height'] )
									{
										$user_id = $this_userdata['user_id'];

										$avatar_filename = $user_id . $imgtype;

										if( $this_userdata['user_avatar_type'] == USER_AVATAR_UPLOAD && $this_userdata['user_avatar'] != "")
										{
											if( file_exists(@phpbb_realpath("./../" . $board_config['avatar_path'] . "/" . $this_userdata['user_avatar'])) )
											{
												@unlink("./../" . $board_config['avatar_path'] . "/" . $this_userdata['user_avatar']);
											}
										}
										@copy($tmp_filename, "./../" . $board_config['avatar_path'] . "/$avatar_filename");
										@unlink($tmp_filename);

										$avatar_sql = ", user_avatar = '$avatar_filename', user_avatar_type = " . USER_AVATAR_UPLOAD;
									}
									else
									{
										$l_avatar_size = sprintf($lang['Avatar_imagesize'], $board_config['avatar_max_width'], $board_config['avatar_max_height']);

										$error = true;
										$error_msg = ( !empty($error_msg) ) ? $error_msg . "<br />" . $l_avatar_size : $l_avatar_size;
									}
								}
								else
								{
									//
									// Error writing file
									//
									@unlink($tmp_filename);
									message_die(GENERAL_ERROR, "Could not write avatar file to local storage. Please contact the board administrator with this message", "", __LINE__, __FILE__);
								}
							}
						}
						else
						{
							//
							// No data
							//
							$error = true;
							$error_msg = ( !empty($error_msg) ) ? $error_msg . "<br />" . $lang['File_no_data'] : $lang['File_no_data'];
						}
					}
					else
					{
						//
						// No connection
						//
						$error = true;
						$error_msg = ( !empty($error_msg) ) ? $error_msg . "<br />" . $lang['No_connection_URL'] : $lang['No_connection_URL'];
					}
				}
				else
				{
					$error = true;
					$error_msg = ( !empty($error_msg) ) ? $error_msg . "<br />" . $lang['Incomplete_URL'] : $lang['Incomplete_URL'];
				}
			}
			else if( !empty($user_avatar_name) )
			{
				$l_avatar_size = sprintf($lang['Avatar_filesize'], round($board_config['avatar_filesize'] / 1024));

				$error = true;
				$error_msg = ( !empty($error_msg) ) ? $error_msg . "<br />" . $l_avatar_size : $l_avatar_size;
			}
		}
		else if( $user_avatar_remoteurl != "" && $avatar_sql == "" && !$error )
		{
			if( !preg_match("#^http:\/\/#i", $user_avatar_remoteurl) )
			{
				$user_avatar_remoteurl = "http://" . $user_avatar_remoteurl;
			}

			if( preg_match("#^(http:\/\/[a-z0-9\-]+?\.([a-z0-9\-]+\.)*[a-z]+\/.*?\.(gif|jpg|png)$)#is", $user_avatar_remoteurl) )
			{
				$avatar_sql = ", user_avatar = '" . str_replace("\'", "''", $user_avatar_remoteurl) . "', user_avatar_type = " . USER_AVATAR_REMOTE;
			}
			else
			{
				$error = true;
				$error_msg = ( !empty($error_msg) ) ? $error_msg . "<br />" . $lang['Wrong_remote_avatar_format'] : $lang['Wrong_remote_avatar_format'];
			}
		}
		else if( $user_avatar_local != "" && $avatar_sql == "" && !$error )
		{
			$avatar_sql = ", user_avatar = '" . str_replace("\'", "''", phpbb_ltrim(basename($user_avatar_category), "'") . '/' . phpbb_ltrim(basename($user_avatar_local), "'")) . "', user_avatar_type = " . USER_AVATAR_GALLERY;
		}
	
		//
		// Find the birthday values, reflected by the $lang['Submit_date_format'] 
		//
		if ($b_day || $b_md || $b_year) //if a birthday is submited, then validate it 
		{ 
		      $user_age=(date('md')>=$b_md.(($b_day <= 9) ? '0':'').$b_day) ? date('Y') - $b_year : date('Y') - $b_year - 1 ; 
		    
		      // Check date, maximum / minimum user age 
		      if (!checkdate($b_md,$b_day,$b_year)) 
		      { 
				$error = true;
				$error_msg = ( !empty($error_msg) ) ? $error_msg . "<br />" . $lang['Wrong_birthday_format'] : $lang['Wrong_birthday_format']; 
		    } 
		    else if ($user_age>$board_config['max_user_age'])
			{
				$error = true;
				$error_msg = ( !empty($error_msg) ) ? $error_msg . "<br />" . sprintf($lang['Birthday_to_high'], $board_config['max_user_age']) : sprintf($lang['Birthday_to_high'], $board_config['max_user_age']); 
			}
			else if ($user_age<$board_config['min_user_age'])
			{
				$error = true;
				$error_msg = ( !empty($error_msg) ) ? $error_msg . "<br />" . sprintf($lang['Birthday_to_low'], $board_config['min_user_age']) : sprintf($lang['Birthday_to_low'], $board_config['min_user_age']); 
			} 
			else 
		      { 
		         $birthday = ($error) ? $birthday : mkrealdate($b_day,$b_md,$b_year);
		      } 
		} 
		else 
		{
			$birthday = ($error) ? '' : 999999;
		}

		//
		// Update entry in DB
		//
		if( !$error )
		{
			// Yellow  & Black cards
			if ( $user_ycard > $board_config['max_user_bancard'] || $user_bkcard > $board_config['max_user_votebancard'] ) 
			{ 
				$sql = "SELECT ban_userid 
					FROM " . BANLIST_TABLE . " 
					WHERE ban_userid = $user_id"; 
				if( $result = $db->sql_query($sql) ) 
				{ 
					if (!$db->sql_fetchrowset($result)) 
					{ 
						// insert the user in the ban list 
						$sql = "INSERT INTO " . BANLIST_TABLE . " (ban_userid, user_name, reason, baned_by, ban_time, ban_by_userid, ban_pub_reason_mode, ban_priv_reason, ban_pub_reason) 
							VALUES ($user_id, '" . addslashes($user_name) . "', 'NULL', '" . $userdata['username'] . "', " . time() . ", " . $userdata['user_id'] . ", 0, 'NULL', 'NULL')"; 
						if (!$result = $db->sql_query($sql) ) 
						{
				            message_die(GENERAL_ERROR, "Couldn't insert ban_userid info into database", "", __LINE__, __FILE__, $sql); 
					} 
						else 
						{
							$no_error_ban = true; 
						} 
					}
					else 
					{
						$no_error_ban = true; 
					}	 
				}
				else 
				{
					message_die(GENERAL_ERROR, "Couldn't obtain banlist information", "", __LINE__, __FILE__, $sql); 
				} 
			} 
			else 
			{ 
				// Remove the ban, if there is any 
				$sql = "DELETE FROM " . BANLIST_TABLE . " 
					WHERE ban_userid = $user_id"; 
				if (!$result = $db->sql_query($sql) ) 
				{
				message_die(GENERAL_ERROR, "Couldn't remove ban_userid info into database", "", __LINE__, __FILE__, $sql); 
			} 
				else 
			{ 
					$no_error_ban = true;
					} 
				} 

			// Force e-mail update 
			if ( $email_validation == 1 )
			{
				$sql = "DELETE FROM " . TOPICS_WATCH_TABLE . "
					WHERE user_id = $user_id";
				if ( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'Could not delete topic watch information for forced email validation', '', __LINE__, __FILE__, $sql);
				}
				
				$notifypm = 0;
			}
			
			$sql = "UPDATE " . USERS_TABLE . "
				SET " . $username_sql . $passwd_sql . "user_email = '" . str_replace("\'", "''", $email) . "', user_wordwrap = $user_wordwrap, daily_post_limit = $daily_post_limit, user_zipcode = '" . str_replace("\'", "''", $zipcode) . "', user_icq = '" . str_replace("\'", "''", $icq) . "', user_website = '" . str_replace("\'", "''", $website) . "', user_stumble = '" . str_replace("\'", "''", $stumble) . "', user_occ = '" . str_replace("\'", "''", $occupation) . "', user_from = '" . str_replace("\'", "''", $location) . "', user_from_flag = '$user_flag', user_interests = '" . str_replace("\'", "''", $interests) . "',  user_custom_post_color = '" . str_replace("\'", "''", $custom_post_color) . "', user_popup_notes = $popup_notes, user_profile_view_popup = $profile_view_popup, user_birthday = '$birthday', user_next_birthday_greeting = $next_birthday_greeting, user_sig = '" . str_replace("\'", "''", $signature) . "', user_viewemail = $viewemail, user_aim = '" . str_replace("\'", "''", $aim) . "', user_yim = '" . str_replace("\'", "''", $yim) . "', user_msnm = '" . str_replace("\'", "''", $msn) . "', user_xfi = '" . str_replace("\'", "''", $xfi) . "', user_skype = '" . str_replace("\'", "''", $skype) . "', user_gtalk = '" . str_replace("\'", "''", $gtalk) . "', user_attachsig = $attachsig, user_sig_bbcode_uid = '$signature_bbcode_uid', user_allowsmile = $allowsmilies, user_showsigs = $allowsigs, user_showavatars = $allowavatars, user_allowhtml = $allowhtml, user_allowavatar = $user_allowavatar, user_allowsig = $user_allowsig, user_allowbbcode = $allowbbcode, user_allow_viewonline = $allowviewonline, user_notify = $notifyreply, user_allow_pm = $user_allowpm, user_allow_profile = $user_allow_profile, user_notify_pm = $notifypm, user_notify_pm_text = $notifypmtext, user_popup_pm = $popuppm, user_sound_pm = $soundpm, user_lang = '" . str_replace("\'", "''", $user_lang) . "', user_style = $user_style, user_timezone = $user_timezone, group_priority = '$group_priority', user_dateformat = '" . str_replace("\'", "''", $user_dateformat) . "', user_clockformat = '" . str_replace("\'", "''", $user_clockformat) . "', user_active = $user_status, user_warnings = $user_ycard, user_votewarnings = $user_bkcard, user_rank = $user_rank, user_gender = '$gender', user_points = $points, admin_allow_points = $allow_points, user_realname = '" . str_replace("\'", "''", $realname) . "', karma_plus = $karma_plus, karma_minus = $karma_minus, user_info = '" . str_replace("\'", "''", $myInfo) . "', kick_ban = $kicker_ban, email_validation = $email_validation" . $avatar_sql . "
				WHERE user_id = $user_id";
			if( $result = $db->sql_query($sql) )
			{
				if( isset($rename_user) )
				{
					$sql = "UPDATE " . GROUPS_TABLE . "
						SET group_name = '".str_replace("\'", "''", $rename_user)."'
						WHERE group_name = '".str_replace("'", "''", $this_userdata['username'] )."'";
					if( !$result = $db->sql_query($sql) )
					{
						message_die(GENERAL_ERROR, 'Could not rename users group', '', __LINE__, __FILE__, $sql);
					}
				}

				// Delete user session, to prevent the user navigating the forum (if logged in) when disabled
				if (!$user_status)
				{
					$sql = "DELETE FROM " . SESSIONS_TABLE . " 
						WHERE session_user_id = " . $user_id;

					if ( !$db->sql_query($sql) )
					{
						message_die(GENERAL_ERROR, 'Error removing user session', '', __LINE__, __FILE__, $sql);
					}
				}
				
				// Custom Profile Fields
				$xd_meta = get_xd_metadata();
				while ( list($code_name, $meta) = each($xd_meta) )
				{
					$xd_value = $xdata[$code_name];
					if ( ( in_array($xd_value, $meta['values_array']) || count($meta['values_array']) == 0 ) && $meta['handle_input'] )
					{
						set_user_xdata($user_id, $code_name, $xd_value);
					}
				}
			
				// We remove all stored login keys since the password has been updated
				// and change the current one (if applicable)
				if ( !empty($passwd_sql) )
				{
					session_reset_keys($user_id, $user_ip);
				}

				$message .= $lang['Admin_user_updated'];
			}
			else
			{
				message_die(GENERAL_ERROR, 'Admin_user_fail', '', __LINE__, __FILE__, $sql);
			}

			$message .= '<br /><br />' . sprintf($lang['Click_return_useradmin'], '<a href="' . append_sid("admin_users.$phpEx") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.$phpEx?pane=right") . '">', '</a>');

			if ( $return_to_profile )
			{
				$message = $lang['Admin_user_updated'] . '<br /><br />' . sprintf($lang['Click_return_viewprofile'], '<a href="' . append_sid($phpbb_root_path . "profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=$user_id") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.$phpEx") . '">', '</a>');
			}

			message_die(GENERAL_MESSAGE, $message);
		}
		else
		{
			$template->set_filenames(array(
				'reg_header' => 'admin/error_body.tpl')
			);

			$template->assign_vars(array(
				'ERROR_MESSAGE' => $error_msg)
			);

			$template->assign_var_from_handle('ERROR_BOX', 'reg_header');

			$username = htmlspecialchars(stripslashes($username));
			$realname = htmlspecialchars(stripslashes($realname));
			$zipcode = stripslashes($zipcode);
			$email = stripslashes($email);
			$password = $password_confirm = '';
			$points = intval($points);

			$icq = stripslashes($icq);
			$aim = htmlspecialchars(str_replace('+', ' ', stripslashes($aim)));
			$msn = htmlspecialchars(stripslashes($msn));
			$yim = htmlspecialchars(stripslashes($yim));
         	$xfi = htmlspecialchars(stripslashes($xfi));
			$skype = htmlspecialchars(stripslashes($skype));
			$gtalk = htmlspecialchars(stripslashes($gtalk));

			$website = htmlspecialchars(stripslashes($website));
			$stumble = htmlspecialchars(stripslashes($stumble));
			$location = htmlspecialchars(stripslashes($location));
			$occupation = htmlspecialchars(stripslashes($occupation));
			$interests = htmlspecialchars(stripslashes($interests));
			$signature = htmlspecialchars(stripslashes($signature));

			$func = create_function('$a', 'return htmlspecialchars(stripslashes($a));');
			$xdata = array_map($func, $xdata);

			$user_lang = stripslashes($user_lang);
			$user_dateformat = htmlspecialchars(stripslashes($user_dateformat));
			$user_clockformat = htmlspecialchars(stripslashes($user_clockformat));
			$custom_post_color = htmlspecialchars(stripslashes($custom_post_color));
			$karma_plus = intval($karma_plus);
			$karma_minus = intval($karma_minus);
			$myInfo = htmlspecialchars(stripslashes($myInfo));
		}
	}
	else if( !isset( $HTTP_POST_VARS['submit'] ) && $mode != 'save' && !isset( $HTTP_POST_VARS['avatargallery'] ) && !isset( $HTTP_POST_VARS['submitavatar'] ) && !isset( $HTTP_POST_VARS['cancelavatar'] ) )
	{
		if( isset( $HTTP_GET_VARS[POST_USERS_URL]) || isset( $HTTP_POST_VARS[POST_USERS_URL]) )
		{
			$user_id = ( isset( $HTTP_POST_VARS[POST_USERS_URL]) ) ? intval( $HTTP_POST_VARS[POST_USERS_URL]) : intval( $HTTP_GET_VARS[POST_USERS_URL]);
			$this_userdata = get_userdata($user_id);
			if( !$this_userdata )
			{
				message_die(GENERAL_MESSAGE, $lang['No_user_id_specified'] );
			}
			$this_userdata['xdata'] = get_user_xdata($user_id);
		}
		else
		{
			$this_userdata = get_userdata($HTTP_POST_VARS['username'], true);
			if( !$this_userdata )
			{
				message_die(GENERAL_MESSAGE, $lang['No_user_id_specified'] );
			}
			$this_userdata['xdata'] = get_user_xdata($HTTP_POST_VARS['username'], true);
		}

		//
		// Now parse and display it as a template
		//
		$user_id = $this_userdata['user_id'];
		$username = $this_userdata['username'];
		$realname = $this_userdata['user_realname'];
		$email = $this_userdata['user_email'];
		$zipcode = $this_userdata['user_zipcode'];
		$password = $password_confirm = '';
		$points = $this_userdata['user_points'];
		$allow_points = $this_userdata['admin_allow_points'];
		$icq = $this_userdata['user_icq'];
		$aim = htmlspecialchars(str_replace('+', ' ', $this_userdata['user_aim'] ));
		$msn = htmlspecialchars($this_userdata['user_msnm']);
		$yim = htmlspecialchars($this_userdata['user_yim']);
      	$xfi = htmlspecialchars($this_userdata['user_xfi']);
		$skype = htmlspecialchars($this_userdata['user_skype']);
		$gtalk = htmlspecialchars($this_userdata['user_gtalk']);
		$website = htmlspecialchars($this_userdata['user_website']);
		$stumble = htmlspecialchars($this_userdata['user_stumble']);
		$location = htmlspecialchars($this_userdata['user_from']);
		$user_flag = htmlspecialchars($this_userdata['user_from_flag']);	
		$occupation = htmlspecialchars($this_userdata['user_occ']);
		$interests = htmlspecialchars($this_userdata['user_interests']);
		$next_birthday_greeting = $this_userdata['user_next_birthday_greeting'];
		if ($this_userdata['user_birthday']!=999999)
		{
			$birthday = realdate($lang['Submit_date_format'], $this_userdata['user_birthday']); 
			$b_day = realdate('j', $this_userdata['user_birthday']); 
			$b_md = realdate('n', $this_userdata['user_birthday']); 
			$b_year = realdate('Y', $this_userdata['user_birthday']); 
		} 
		else
		{
			$b_day = $b_md = $b_year = $birthday = ''; 
		}          
		$gender = htmlspecialchars($this_userdata['user_gender']);
		$signature = ($this_userdata['user_sig_bbcode_uid'] != '') ? preg_replace('#:' . $this_userdata['user_sig_bbcode_uid'] . '#si', '', $this_userdata['user_sig']) : $this_userdata['user_sig'];
		$signature = preg_replace($html_entities_match, $html_entities_replace, $signature);
		foreach ($this_userdata['xdata'] as $name => $value)
		{
			$value = ($this_userdata['user_sig_bbcode_uid'] != '') ? preg_replace('#:' . $this_userdata['user_sig_bbcode_uid'] . '#si', '', $value) : $value;
			$xdata[$name] = preg_replace($html_entities_match, $html_entities_replace, $value);
		}
		$viewemail = $this_userdata['user_viewemail'];
		$popup_notes = $this_userdata['user_popup_notes'];
		$profile_view_popup = $this_userdata['user_profile_view_popup'];
		$notifypm = $this_userdata['user_notify_pm'];
		$notifypmtext = $this_userdata['user_notify_pm_text'];
		$popuppm = $this_userdata['user_popup_pm'];
		$soundpm = $this_userdata['user_sound_pm'];
		$notifyreply = $this_userdata['user_notify'];
		$attachsig = $this_userdata['user_attachsig'];
		$allowhtml = $this_userdata['user_allowhtml'];
		$allowbbcode = $this_userdata['user_allowbbcode'];
		$allowsmilies = $this_userdata['user_allowsmile'];
		$allowviewonline = $this_userdata['user_allow_viewonline'];
		$allowsigs = $this_userdata['user_showsigs'];
		$allowavatars = $this_userdata['user_showavatars'];
		$user_avatar = $this_userdata['user_avatar'];
		$user_avatar_type = $this_userdata['user_avatar_type'];
		$user_style = $this_userdata['user_style'];
		$user_lang = $this_userdata['user_lang'];
		$user_timezone = $this_userdata['user_timezone'];
		$user_dateformat = htmlspecialchars($this_userdata['user_dateformat']);
		$user_clockformat = htmlspecialchars($this_userdata['user_clockformat']);
		$user_status = $this_userdata['user_active'];
		$user_ycard = $this_userdata['user_warnings'];
		$user_bkcard = $this_userdata['user_votewarnings'];
		$user_allowavatar = $this_userdata['user_allowavatar'];
		$user_allowpm = $this_userdata['user_allow_pm'];
		$user_allow_profile = $this_userdata['user_allow_profile'];
		$user_level = $this_userdata['user_level']; 
		$custom_post_color = htmlspecialchars($this_userdata['user_custom_post_color']);
		$karma_plus = $this_userdata['karma_plus'];
		$karma_minus = $this_userdata['karma_minus'];
		$kicker_ban = $this_userdata['kick_ban'];
		$myInfo = $this_userdata['user_info'];
		$user_lastlogon	= $this_userdata['user_lastlogon'];	
		$user_regdate = $this_userdata['user_regdate'];	
		$email_validation = $this_userdata['email_validation'];
		$daily_post_limit = $this_userdata['daily_post_limit'];
		$COPPA = false;
		$html_status =  ($this_userdata['user_allowhtml'] ) ? $lang['HTML_is_ON'] : $lang['HTML_is_OFF'];
		$bbcode_status = ($this_userdata['user_allowbbcode'] ) ? $lang['BBCode_is_ON'] : $lang['BBCode_is_OFF'];
		$smilies_status = ($this_userdata['user_allowsmile'] ) ? $lang['Smilies_are_ON'] : $lang['Smilies_are_OFF'];
		$user_wordwrap = $this_userdata['user_wordwrap'];
		$user_allowsig = $this_userdata['user_allowsig'];

 		if ( is_array($color_groups['groupdata']) )
      	{ 
		foreach($color_groups['groupdata'] AS $color_group)
		{
			if ( in_array($this_userdata['user_id'], $color_group['group_users']) )
			{
				$group_priority_selected = ( $this_userdata['group_priority'] == $color_group['group_id'] ) ? ' selected="selected"' : '';
				$group_values .= '<option value="' . $color_group['group_id'] . '"' . $group_priority_selected . '>' . $color_group['group_name'] . '</option>';
				$group_count++;
			}
		}
		}
		
		if ( $group_values && $group_count > 1)
		{
			$group_drop_down = '<select name="group_priority">' . $group_values . '</select>';
			$template->assign_block_vars('switch_color_groups', array());
		}
		else
		{
			$group_priority = 0;
		}
	}

	if( isset($HTTP_POST_VARS['avatargallery']) && !$error )
	{
		if( !$error )
		{
			$user_id = intval($HTTP_POST_VARS['id']);

			$template->set_filenames(array(
				"body" => "admin/user_avatar_gallery.tpl")
			);

			$dir = @opendir("../" . $board_config['avatar_gallery_path']);

			$avatar_images = array();
			while( $file = @readdir($dir) )
			{
				if( $file != "." && $file != ".." && !is_file(phpbb_realpath("./../" . $board_config['avatar_gallery_path'] . "/" . $file)) && !is_link(phpbb_realpath("./../" . $board_config['avatar_gallery_path'] . "/" . $file)) )
				{
					$sub_dir = @opendir("../" . $board_config['avatar_gallery_path'] . "/" . $file);

					$avatar_row_count = 0;
					$avatar_col_count = 0;

					while( $sub_file = @readdir($sub_dir) )
					{
						if( preg_match("/(\.gif$|\.png$|\.jpg)$/is", $sub_file) )
						{
							$avatar_images[$file][$avatar_row_count][$avatar_col_count] = $sub_file;

							$avatar_col_count++;
							if( $avatar_col_count == 5 )
							{
								$avatar_row_count++;
								$avatar_col_count = 0;
							}
						}
					}
				}
			}
	
			@closedir($dir);

			if( isset($HTTP_POST_VARS['avatarcategory']) )
			{
				$category = htmlspecialchars($HTTP_POST_VARS['avatarcategory']);
			}
			else
			{
				list($category, ) = each($avatar_images);
			}
			@reset($avatar_images);

			$s_categories = "";
			while( list($key) = each($avatar_images) )
			{
				$selected = ( $key == $category ) ? "selected=\"selected\"" : "";
				if( count($avatar_images[$key]) )
				{
					$s_categories .= '<option value="' . $key . '"' . $selected . '>' . ucfirst($key) . '</option>';
				}
			}

			$s_colspan = 0;
			for($i = 0; $i < count($avatar_images[$category]); $i++)
			{
				$template->assign_block_vars("avatar_row", array());

				$s_colspan = max($s_colspan, count($avatar_images[$category][$i]));

				for($j = 0; $j < count($avatar_images[$category][$i]); $j++)
				{
					$template->assign_block_vars("avatar_row.avatar_column", array(
						"AVATAR_IMAGE" => "../" . $board_config['avatar_gallery_path'] . '/' . $category . '/' . $avatar_images[$category][$i][$j])
					);

					$template->assign_block_vars("avatar_row.avatar_option_column", array(
						"S_OPTIONS_AVATAR" => $avatar_images[$category][$i][$j])
					);
				}
			}

			$coppa = ( ( !$HTTP_POST_VARS['coppa'] && !$HTTP_GET_VARS['coppa'] ) || $mode == "register") ? 0 : TRUE;

			$s_hidden_fields = '<input type="hidden" name="mode" value="edit" /><input type="hidden" name="agreed" value="true" /><input type="hidden" name="coppa" value="' . $coppa . '" /><input type="hidden" name="avatarcatname" value="' . $category . '" />';
			$s_hidden_fields .= '<input type="hidden" name="id" value="' . $user_id . '" />';

			$s_hidden_fields .= '<input type="hidden" name="username" value="' . str_replace("\"", "&quot;", $username) . '" />';
			$s_hidden_fields .= '<input type="hidden" name="realname" value="' . str_replace("\"", "&quot;", $realname) . '" />';
			$s_hidden_fields .= '<input type="hidden" name="zipcode" value="' . str_replace("\"", "&quot;", $zipcode) . '" />';
			$s_hidden_fields .= '<input type="hidden" name="email" value="' . str_replace("\"", "&quot;", $email) . '" />';
			$s_hidden_fields .= '<input type="hidden" name="icq" value="' . str_replace("\"", "&quot;", $icq) . '" />';
			$s_hidden_fields .= '<input type="hidden" name="aim" value="' . str_replace("\"", "&quot;", $aim) . '" />';
			$s_hidden_fields .= '<input type="hidden" name="msn" value="' . str_replace("\"", "&quot;", $msn) . '" />';
			$s_hidden_fields .= '<input type="hidden" name="yim" value="' . str_replace("\"", "&quot;", $yim) . '" />';
         	$s_hidden_fields .= '<input type="hidden" name="xfi" value="' . str_replace("\"", "&quot;", $xfi) . '" />';
			$s_hidden_fields .= '<input type="hidden" name="skype" value="' . str_replace("\"", "&quot;", $skype) . '" />';
			$s_hidden_fields .= '<input type="hidden" name="website" value="' . str_replace("\"", "&quot;", $website) . '" />';
			$s_hidden_fields .= '<input type="hidden" name="stumble" value="' . str_replace("\"", "&quot;", $stumble) . '" />';
			$s_hidden_fields .= '<input type="hidden" name="location" value="' . str_replace("\"", "&quot;", $location) . '" />';
			$s_hidden_fields .= '<input type="hidden" name="user_flag" value="' . $user_flag . '" />';
			$s_hidden_fields .= '<input type="hidden" name="occupation" value="' . str_replace("\"", "&quot;", $occupation) . '" />';
			$s_hidden_fields .= '<input type="hidden" name="interests" value="' . str_replace("\"", "&quot;", $interests) . '" />';
			$s_hidden_fields .= '<input type="hidden" name="birthday" value="' . $birthday . '" />';
			$s_hidden_fields .= '<input type="hidden" name="next_birthday_greeting" value="' . $next_birthday_greeting . '" />';
			$s_hidden_fields .= '<input type="hidden" name="signature" value="' . str_replace("\"", "&quot;", $signature) . '" />';
			$s_hidden_fields .= '<input type="hidden" name="viewemail" value="' . $viewemail . '" />';
			$s_hidden_fields .= '<input type="hidden" name="popup_notes" value="' . $popup_notes . '" />';
			$s_hidden_fields .= '<input type="hidden" name="profile_view_popup" value="' . $profile_view_popup . '" />';
			$s_hidden_fields .= '<input type="hidden" name="gender" value="' . $gender . '" />';
			$s_hidden_fields .= '<input type="hidden" name="notifypm" value="' . $notifypm . '" />';
			$s_hidden_fields .= '<input type="hidden" name="notifypmtext" value="' . $notifypmtext . '" />';
			$s_hidden_fields .= '<input type="hidden" name="popup_pm" value="' . $popuppm . '" />';
			$s_hidden_fields .= '<input type="hidden" name="sound_pm" value="' . $soundpm . '" />'; 
			$s_hidden_fields .= '<input type="hidden" name="notifyreply" value="' . $notifyreply . '" />';
			$s_hidden_fields .= '<input type="hidden" name="attachsig" value="' . $attachsig . '" />';
			$s_hidden_fields .= '<input type="hidden" name="allowhtml" value="' . $allowhtml . '" />';
			$s_hidden_fields .= '<input type="hidden" name="allowbbcode" value="' . $allowbbcode . '" />';
			$s_hidden_fields .= '<input type="hidden" name="allowsmilies" value="' . $allowsmilies . '" />';
			$s_hidden_fields .= '<input type="hidden" name="allowsigs" value="' . $allowsigs . '" />';
			$s_hidden_fields .= '<input type="hidden" name="allowavatars" value="' . $allowavatars . '" />';
			$s_hidden_fields .= '<input type="hidden" name="hideonline" value="' . !$allowviewonline . '" />';
			$s_hidden_fields .= '<input type="hidden" name="style" value="' . $user_style . '" />'; 
			$s_hidden_fields .= '<input type="hidden" name="language" value="' . $user_lang . '" />';
			$s_hidden_fields .= '<input type="hidden" name="timezone" value="' . $user_timezone . '" />';
			$s_hidden_fields .= '<input type="hidden" name="dateformat" value="' . str_replace("\"", "&quot;", $user_dateformat) . '" />';
			$s_hidden_fields .= '<input type="hidden" name="clockformat" value="' . str_replace("\"", "&quot;", $user_clockformat) . '" />';
			$s_hidden_fields .= '<input type="hidden" name="user_status" value="' . $user_status . '" />';
			$s_hidden_fields .= '<input type="hidden" name="user_ycard" value="' . $user_ycard . '" />'; 
			$s_hidden_fields .= '<input type="hidden" name="user_bkcard" value="' . $user_bkcard . '" />'; 
			$s_hidden_fields .= '<input type="hidden" name="user_allowpm" value="' . $user_allowpm . '" />';
			$s_hidden_fields .= '<input type="hidden" name="user_allow_profile" value="' . $user_allow_profile . '" />';
			$s_hidden_fields .= '<input type="hidden" name="user_allowavatar" value="' . $user_allowavatar . '" />';
			$s_hidden_fields .= '<input type="hidden" name="user_rank" value="' . $user_rank . '" />';
			$s_hidden_fields .= '<input type="hidden" name="points" value="' . $points . '" />';
			$s_hidden_fields .= '<input type="hidden" name="allow_points" value="' . $allow_points . '" />';
			$s_hidden_fields .= '<input type="hidden" name="custom_post_color" value="' . str_replace("\"", "&quot;", $custom_post_color) . '" />'; 
			$s_hidden_fields .= '<input type="hidden" name="karma_plus" value="' . $karma_plus . '" />';
			$s_hidden_fields .= '<input type="hidden" name="karma_minus" value="' . $karma_minus . '" />';
			$s_hidden_fields .= '<input type="hidden" name="kicker_ban" value="' . $kicker_ban . '" />';
			$s_hidden_fields .= '<input type="hidden" name="myInfo" value="' . str_replace("\"", "&quot;", $myInfo) . '" />';
			$s_hidden_fields .= '<input type="hidden" name="gtalk" value="' . str_replace("\"", "&quot;", $gtalk) . '" />';
			$s_hidden_fields .= '<input type="hidden" name="email_validation" value="' . $email_validation . '" />';
			$s_hidden_fields .= '<input type="hidden" name="daily_post_limit" value="' . $daily_post_limit . '" />';
			$s_hidden_fields .= '<input type="hidden" name="wrap" value="' . $user_wordwrap .'" />';
			$s_hidden_fields .= '<input type="hidden" name="user_allowsig" value="' . $user_allowsig . '" />';

			reset($xdata);
			while ( list($key, $value) = each($xdata) )
			{
				$s_hidden_fields .= '<input type="hidden" name="' . $key . '" value="' . str_replace("\"", "&quot;", $value) . '" />';
			}
			
			$template->assign_vars(array(
				"L_USER_TITLE" => $lang['User_admin'],
				"L_USER_EXPLAIN" => $lang['User_admin_explain'],
				"L_AVATAR_GALLERY" => $lang['Avatar_gallery'], 
				"L_SELECT_AVATAR" => $lang['Select_avatar'], 
				"L_RETURN_PROFILE" => $lang['Return_profile'], 
				"L_CATEGORY" => $lang['Select_category'], 
				"L_GO" => $lang['Go'],

				"S_OPTIONS_CATEGORIES" => $s_categories, 
				"S_COLSPAN" => $s_colspan, 
				"S_PROFILE_ACTION" => append_sid("admin_users.$phpEx?mode=$mode"), 
				"S_HIDDEN_FIELDS" => $s_hidden_fields)
			);
		}
	}
	else
	{
		$s_hidden_fields = '<input type="hidden" name="mode" value="save" />';
		$s_hidden_fields .= '<input type="hidden" name="agreed" value="true" />';
		$s_hidden_fields .= '<input type="hidden" name="coppa" value="' . $coppa . '" />';
		$s_hidden_fields .= '<input type="hidden" name="id" value="' . $this_userdata['user_id'] . '" />';
		$s_hidden_fields .= '<input type="hidden" name="returntoprofile" value="' . $return_to_profile .'" />';

		if( !empty($user_avatar_local) )
		{
			$s_hidden_fields .= '<input type="hidden" name="avatarlocal" value="' . $user_avatar_local . '" /><input type="hidden" name="avatarcatname" value="' . $user_avatar_category . '" />';
		}

		if( $user_avatar_type )
		{
			switch( $user_avatar_type )
			{
				case USER_AVATAR_UPLOAD:
					$avatar = '<img src="../' . $board_config['avatar_path'] . '/' . $user_avatar . '" alt="" />';
					break;
				case USER_AVATAR_REMOTE:
					$avatar = '<img src="' . $user_avatar . '" alt="" />';
					break;
				case USER_AVATAR_GALLERY:
					$avatar = '<img src="../' . $board_config['avatar_gallery_path'] . '/' . $user_avatar . '" alt="" />';
					break;
			}
		}
		else
		{
			$avatar = "";
		}

		//
		// Rank
		//
		$sql = "SELECT * FROM " . RANKS_TABLE . "
			WHERE rank_special = 1
			ORDER BY rank_title";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not obtain ranks data', '', __LINE__, __FILE__, $sql);
		}

		$rank_select_box = '<option value="0">' . $lang['No_assigned_rank'] . '</option>';
		while( $row = $db->sql_fetchrow($result) )
		{
			$rank = $row['rank_title'];
			$rank_id = $row['rank_id'];
			
			$selected = ( $this_userdata['user_rank'] == $rank_id ) ? ' selected="selected"' : '';
			$rank_select_box .= '<option value="' . $rank_id . '"' . $selected . '>' . $rank . '</option>';
		}

		$template->set_filenames(array(
			"body" => "admin/user_edit_body.tpl")
		);
	
		// 
		// Custom Profile Fields
		//
		$xd_meta = get_xd_metadata();
		while ( list($code_name, $info) = each($xd_meta) )
		{
			if ( xdata_auth($code_name, $userdata['user_id']) || intval($userdata['user_level']) == ADMIN )
			{
				if ($info['display_register'] == XD_DISPLAY_NORMAL)
				{
					$template->assign_block_vars('xdata', array(
						'CODE_NAME' => $code_name,
						'NAME' => $info['field_name'],
						'DESCRIPTION' => $info['field_desc'],
	    				'VALUE' => isset($xdata[$code_name]) ? str_replace('"', '&quot;', $xdata[$code_name]) : '',
						'MAX_LENGTH' => ( $info['field_length'] > 0) ? ( $info['field_length'] ) : '')
					);
		
					switch ($info['field_type'])
					{
						case 'text':
							$template->assign_block_vars('xdata.switch_type_text', array());
							break;
		
						case 'textarea':
							$template->assign_block_vars('xdata.switch_type_textarea', array());
							break;
			
						case 'radio':
							$template->assign_block_vars('xdata.switch_type_radio', array());
		
							while ( list( , $option) = each($info['values_array']) )
							{
			                	$template->assign_block_vars('xdata.switch_type_radio.options', array(
			                		'OPTION' => $option,
			                		'CHECKED' => ($xdata[$code_name] == $option) ? 'checked="checked"' : '')
			                	);
							}
							break;
			
						case 'select':
							$template->assign_block_vars('xdata.switch_type_select', array());
							
							while ( list( , $option) = each($info['values_array']) )
							{
			                	$template->assign_block_vars('xdata.switch_type_select.options', array(
			                		'OPTION' => $option,
			                		'SELECTED' => ($xdata[$code_name] == $option) ? 'selected="selected"' : '')
			                	);
							}
							break;
					}
				}
				elseif ($info['display_register'] == XD_DISPLAY_ROOT)
				{
		            $template->assign_block_vars('xdata', array(
				  		'CODE_NAME' => $code_name,
			  			'NAME' => $xd_meta[$code_name]['field_name'],
			  			'DESCRIPTION' => $xd_meta[$code_name]['field_desc'],
	       				'VALUE' => isset($xdata[$code_name]) ? str_replace('"', '&quot;', $xdata[$code_name]) : '')
	       			);
			  	
			  		$template->assign_block_vars('xdata.switch_is_'.$code_name, array());
			  	
			  		switch ($info['field_type'])
					{
						case 'radio':
							while ( list( , $option) = each($info['values_array']) )
							{
			                	$template->assign_block_vars('xdata.switch_is_'.$code_name.'.options', array(
			                		'OPTION' => $option,
			                		'CHECKED' => ($xdata[$code_name] == $option) ? 'checked="checked"' : '')
			                	);
							}
							break;
		
						case 'select':
							while ( list( , $option) = each($info['values_array']) )
							{
			                	$template->assign_block_vars('xdata.switch_is_'.$code_name.'.options', array(
		                			'OPTION' => $option,
		                			'SELECTED' => ($xdata[$code_name] == $option) ? 'selected="selected"' : '')
		                		);
							}
							break;
					}
				}
			}	
		}
		
		// 
		// Birthday
		//
		$s_b_day = $lang['Day'] . '&nbsp;<select name="b_day">
			<option value="0">--</option>
			<option value="1">01</option>
			<option value="2">02</option>
			<option value="3">03</option>
			<option value="4">04</option>
			<option value="5">05</option>
			<option value="6">06</option>
			<option value="7">07</option>
			<option value="8">08</option>
			<option value="9">09</option>
			<option value="10">10</option>
			<option value="11">11</option>
			<option value="12">12</option>
			<option value="13">13</option>
			<option value="14">14</option>
			<option value="15">15</option>
			<option value="16">16</option>
			<option value="17">17</option>
			<option value="18">18</option>
			<option value="19">19</option>
			<option value="20">20</option>
			<option value="21">21</option>
			<option value="22">22</option>
			<option value="23">23</option>
			<option value="24">24</option>
			<option value="25">25</option>
			<option value="26">26</option>
			<option value="27">27</option>
			<option value="28">28</option>
			<option value="29">29</option>
			<option value="30">30</option>
			<option value="31">31</option>
		  	</select>&nbsp;&nbsp;';
		$s_b_md = $lang['Month'] . '&nbsp;<select name="b_md"> 
			<option value="0">--</option> 
		<option value="1">'.$lang['datetime']['January'].'</option> 
		<option value="2">'.$lang['datetime']['February'].'</option> 
		<option value="3">'.$lang['datetime']['March'].'</option> 
		<option value="4">'.$lang['datetime']['April'].'</option> 
		<option value="5">'.$lang['datetime']['May'].'</option> 
		<option value="6">'.$lang['datetime']['June'].'</option> 
		<option value="7">'.$lang['datetime']['July'].'</option> 
		<option value="8">'.$lang['datetime']['August'].'</option> 
		<option value="9">'.$lang['datetime']['September'].'</option> 
		<option value="10">'.$lang['datetime']['October'].'</option> 
		<option value="11">'.$lang['datetime']['November'].'</option> 
		<option value="12">'.$lang['datetime']['December'].'</option> 
		</select>&nbsp;&nbsp;'; 
		$s_b_day= str_replace("value=\"".$b_day."\">", "value=\"".$b_day."\" SELECTED>" ,$s_b_day);
		$s_b_md = str_replace("value=\"".$b_md."\">", "value=\"".$b_md."\" SELECTED>" ,$s_b_md);
		$s_b_year = $lang['Year'] . '&nbsp;<input type="text" class="post" name="b_year" size="5" maxlength="4" value="' . $b_year . '" />';
		$i = 0;
		$s_birthday='';
		for ($i=0; $i <= strlen($lang['Submit_date_format']); $i++) 
		{ 
			switch ($lang['Submit_date_format'][$i]) 
		      { 
		       	case d:  
		       		$s_birthday .= $s_b_day; 
		       		break; 
		        case m:  
		        	$s_birthday .= $s_b_md; 
		        	break; 
		      	case Y:  
		      		$s_birthday .= $s_b_year; 
		      		break; 
		      }
		}
		
		//
		// Gender 
		//
		switch ($gender) 
		{ 
		   	case 1: 
		   		$gender_male_checked = "checked=\"checked\"";
		   		break; 
		   	case 2: 
		   		$gender_female_checked = "checked=\"checked\"";
		   		break; 
		   	default:
		   		$gender_no_specify_checked = "checked=\"checked\""; 
		} 

        if (!empty($custom_post_color))
		{
			$template->assign_block_vars('no_info_color', array());
		}


		//
		// Let's do an overall check for settings/versions which would prevent
		// us from doing file uploads....
		//
		$ini_val = ( phpversion() >= '4.0.0' ) ? 'ini_get' : 'get_cfg_var';
		$form_enctype = ( !@$ini_val('file_uploads') || phpversion() == '4.0.4pl1' || !$board_config['allow_avatar_upload'] || ( phpversion() < '4.0.3' && @$ini_val('open_basedir') != '' ) ) ? '' : 'enctype="multipart/form-data"';

		//
		// Get the list of flags
		//
		$sql = "SELECT *
			FROM " . FLAG_TABLE . "
			ORDER BY flag_id";
		if(!$flags_result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not obtain flags information.', '', __LINE__, __FILE__, $sql);
		}
		$flag_row = $db->sql_fetchrowset($ranksresult);
		$num_flags = $db->sql_numrows($ranksresult) ;
	
		// Build the html select statement
		$flag_start_image = 'blank.gif' ;
		$selected = ( isset($user_flag) ) ? '' : ' selected="selected"'  ;
		$flag_select = "<select name=\"user_flag\" onChange=\"document.images['user_flag'].src = '../images/flags/' + this.value;\" >";
		$flag_select .= '<option value="blank.gif"' . $selected . '>' . $lang['Select_Country'] . '</option>';
		for ($i = 0; $i < $num_flags; $i++)
		{
			$flag_name = $flag_row[$i]['flag_name'];
			$flag_image = $flag_row[$i]['flag_image'];
			$selected = ( isset( $user_flag) ) ? (($user_flag == $flag_image) ? 'selected="selected"' : '' ) : '' ;
			$flag_select .= "\t<option value=\"$flag_image\"$selected>$flag_name</option>";
			if ( isset( $user_flag) && ($user_flag == $flag_image))
			{
				$flag_start_image = $flag_image ;
			}
		}
		$flag_select .= '</select>';

		$template->assign_vars(array(
			'USERNAME' => $username,
			'ZIPCODE' => $zipcode,
			'EMAIL' => $email,
			'YIM' => $yim,
			'ICQ' => $icq,
			'MSN' => $msn,
			'AIM' => $aim,
         	'XFI' => $xfi, 
			'SKYPE' => $skype, 
			'GTALK' => $gtalk,
			'OCCUPATION' => $occupation,
			'INTERESTS' => $interests,
			'NEXT_BIRTHDAY_GREETING' => $next_birthday_greeting,
			'S_BIRTHDAY' => $s_birthday,
			'GENDER' => $gender, 
			'GENDER_NO_SPECIFY_CHECKED' => $gender_no_specify_checked, 
			'GENDER_MALE_CHECKED' => $gender_male_checked, 
			'GENDER_FEMALE_CHECKED' => $gender_female_checked,
			'LOCATION' => $location,
			'FLAG_SELECT' => $flag_select,
			'FLAG_START' => $flag_start_image,
			'WEBSITE' => $website,
			'STUMBLE' => $stumble,
			'SIGNATURE' => str_replace('<br />', "\n", $signature),
			'VIEW_EMAIL_YES' => ($viewemail) ? 'checked="checked"' : '',
			'VIEW_EMAIL_NO' => (!$viewemail) ? 'checked="checked"' : '',
			'HIDE_USER_YES' => (!$allowviewonline) ? 'checked="checked"' : '',
			'HIDE_USER_NO' => ($allowviewonline) ? 'checked="checked"' : '',
			'NOTIFY_PM_YES' => ($notifypm) ? 'checked="checked"' : '',
			'NOTIFY_PM_NO' => (!$notifypm) ? 'checked="checked"' : '',
			'NOTIFY_PM_TEXT_YES' => ($notifypmtext) ? 'checked="checked"' : '',
			'NOTIFY_PM_TEXT_NO' => (!$notifypmtext) ? 'checked="checked"' : '',
			'POPUP_PM_YES' => ($popuppm) ? 'checked="checked"' : '',
			'POPUP_PM_NO' => (!$popuppm) ? 'checked="checked"' : '',
			'SOUND_PM_YES' => ($soundpm) ? 'checked="checked"' : '',
			'SOUND_PM_NO' => (!$soundpm) ? 'checked="checked"' : '',
			'ALWAYS_ADD_SIGNATURE_YES' => ($attachsig) ? 'checked="checked"' : '',
			'ALWAYS_ADD_SIGNATURE_NO' => (!$attachsig) ? 'checked="checked"' : '',
			'NOTIFY_REPLY_YES' => ( $notifyreply ) ? 'checked="checked"' : '',
			'NOTIFY_REPLY_NO' => ( !$notifyreply ) ? 'checked="checked"' : '',
			'ALWAYS_ALLOW_BBCODE_YES' => ($allowbbcode) ? 'checked="checked"' : '',
			'ALWAYS_ALLOW_BBCODE_NO' => (!$allowbbcode) ? 'checked="checked"' : '',
			'ALWAYS_ALLOW_HTML_YES' => ($allowhtml) ? 'checked="checked"' : '',
			'ALWAYS_ALLOW_HTML_NO' => (!$allowhtml) ? 'checked="checked"' : '',
			'ALWAYS_ALLOW_SMILIES_YES' => ($allowsmilies) ? 'checked="checked"' : '',
			'ALWAYS_ALLOW_SMILIES_NO' => (!$allowsmilies) ? 'checked="checked"' : '',
			'ALWAYS_ALLOW_SIGS_YES' => ($allowsigs) ? 'checked="checked"' : '',
			'ALWAYS_ALLOW_SIGS_NO' => (!$allowsigs) ? 'checked="checked"' : '',
			'ALWAYS_ALLOW_AVATARS_YES' => ($allowavatars) ? 'checked="checked"' : '',
			'ALWAYS_ALLOW_AVATARS_NO' => (!$allowavatars) ? 'checked="checked"' : '',
			'AVATAR' => $avatar,
			'LANGUAGE_SELECT' => language_select($user_lang),
			'TIMEZONE_SELECT' => admin_tz_select($user_timezone),
			'STYLE_SELECT' => style_select($user_style, 'style'),
			'DATE_FORMAT_SELECT' => date_format_select($user_dateformat, $user_timezone),
			'CLOCK_FORMAT_SELECT' => clock_format_select($user_clockformat),
			'ALLOW_PM_YES' => ($user_allowpm) ? 'checked="checked"' : '',
			'ALLOW_PM_NO' => (!$user_allowpm) ? 'checked="checked"' : '',
			'ALLOW_PROFILE_YES' => ($user_allow_profile) ? 'checked="checked"' : '',
			'ALLOW_PROFILE_NO' => (!$user_allow_profile) ? 'checked="checked"' : '',
			'ALLOW_AVATAR_YES' => ($user_allowavatar) ? 'checked="checked"' : '',
			'ALLOW_AVATAR_NO' => (!$user_allowavatar) ? 'checked="checked"' : '',
			'USER_ACTIVE_YES' => ($user_status) ? 'checked="checked"' : '',
			'USER_ACTIVE_NO' => (!$user_status) ? 'checked="checked"' : '', 
			'RANK_SELECT_BOX' => $rank_select_box,
			'POINTS' => $points,
			'ALLOW_POINTS_YES' => ($allow_points) ? 'checked="checked"' : '',
			'ALLOW_POINTS_NO' => (!$allow_points) ? 'checked="checked"' : '', 
			'BANCARD' => $user_ycard, 
			'VOTEBANCARD' => $user_bkcard, 
			'PROFILE_VIEW_POPUP_YES' => ($profile_view_popup) ? 'checked="checked"' : '',
			'PROFILE_VIEW_POPUP_NO' => (!$profile_view_popup) ? 'checked="checked"' : '',
			'POPUP_NOTES_YES' => ($popup_notes) ? 'checked="checked"' : '',
			'POPUP_NOTES_NO' => (!$popup_notes) ? 'checked="checked"' : '',
			'REALNAME' => $realname,
			'CUSTOM_POST_COLOR' => $custom_post_color,
			'KARMA_PLUS' => $karma_plus,
			'KARMA_MINUS' => $karma_minus,
			'KICKER_BAN_YES' => ($kicker_ban) ? 'checked="checked"' : '',
			'KICKER_BAN_NO' => (!$kicker_ban) ? 'checked="checked"' : '',
			'MYINFO' => str_replace('<br />', "\n", $myInfo),
			'USER_LASTLOGON' => (!empty($user_lastlogon)) ? create_date($board_config['default_dateformat'], $user_lastlogon, $board_config['board_timezone']) : $lang['Never_last_logon'],
			'USER_REGDATE' => create_date($board_config['default_dateformat'], $user_regdate, $board_config['board_timezone']),
			'I_PICK_COLOR' => $phpbb_root_path . $images['acp_icon_pickcolor'],
			'EMAIL_VALIDATION_YES' => ($email_validation) ? 'checked="checked"' : '',
			'EMAIL_VALIDATION_NO' => (!$email_validation) ? 'checked="checked"' : '',
			'DAILY_POST_LIMIT' => $daily_post_limit,
			'WRAP_ROW' => $user_wordwrap,
			'ALLOW_SIG_YES' => ($user_allowsig) ? 'checked="checked"' : '',
			'ALLOW_SIG_NO' => (!$user_allowsig) ? 'checked="checked"' : '',
			'GROUP_PRIORITY_SELECT' => $group_drop_down,

			'L_USERNAME' => $lang['Username'],
			'L_ZIPCODE' => $lang['Zip_code'],
      		'L_ZIPCODE_VIEWABLE' => sprintf($lang['Zip_code_viewable'], '<a href="javascript:void(0);" onclick="spawn();">', '</a>'),
			'L_USER_TITLE' => $lang['User_admin'],
			'L_USER_EXPLAIN' => $lang['User_admin_explain'],
			'L_NEW_PASSWORD' => $lang['New_password'], 
			'L_PASSWORD_IF_CHANGED' => $lang['password_if_changed'],
			'L_CONFIRM_PASSWORD' => $lang['Confirm_password'],
			'L_PASSWORD_CONFIRM_IF_CHANGED' => $lang['password_confirm_if_changed'],
			'L_SUBMIT' => $lang['Submit'],
			'L_RESET' => $lang['Reset'],
			'L_ICQ_NUMBER' => $lang['ICQ'],
			'L_MESSENGER' => $lang['MSNM'],
			'L_YAHOO' => $lang['YIM'],
			'L_AIM' => $lang['AIM'],
         	'L_XFIRE' => $lang['XFI'],
			'L_SKYPE' => $lang['skype'], 
			'L_GTALK' => $lang['GTALK'],
			'L_WEBSITE' => $lang['Website'],
			'L_STUMBLE' => $lang['Stumble'],
			'L_LOCATION' => $lang['Location'],
			'L_FLAG' => $lang['Country_Flag'],
			'L_OCCUPATION' => $lang['Occupation'],
			'L_BOARD_LANGUAGE' => $lang['Board_lang'],
			'L_BOARD_STYLE' => $lang['Board_style'],
			'L_TIMEZONE' => $lang['Timezone'],
			'L_DATE_FORMAT' => $lang['Date_format'],
			'L_DATE_FORMAT_EXPLAIN' => $lang['Date_format_explain'],
			'L_CLOCK_FORMAT' => $lang['Clock_format'],
			'L_CLOCK_FORMAT_EXPLAIN' => sprintf($lang['Clock_format_explain'], '<a href="javascript:void(0);" onclick="clocks();">', '</a>'),
			'L_INTERESTS' => $lang['Interests'],
			'L_BIRTHDAY' => $lang['Birthday'], 
			'L_NEXT_BIRTHDAY_GREETING' => $lang['Next_birthday_greeting'], 
			'L_NEXT_BIRTHDAY_GREETING_EXPLAIN' => $lang['Next_birthday_greeting_expain'], 
			'L_ALWAYS_ALLOW_SMILIES' => $lang['Always_smile'],
			'L_GENDER' => $lang['Gender'], 
			'L_GENDER_MALE' => $lang['Male'], 
			'L_GENDER_FEMALE' => $lang['Female'],
			'L_GENDER_NOT_SPECIFY' => $lang['No_gender_specify'],
			'L_ALWAYS_ALLOW_BBCODE' => $lang['Always_bbcode'],
			'L_ALWAYS_ALLOW_HTML' => $lang['Always_html'],
			'L_HIDE_USER' => $lang['Hide_user'],
			'L_ALWAYS_ADD_SIGNATURE' => $lang['Always_add_sig'],
			'L_POINTS' => $board_config['points_name'],
			'L_ALLOW_POINTS' => sprintf($lang['Allow_Points'], $board_config['points_name']),
			'L_BANCARD' => $lang['ban_card'], 
			'L_BANCARD_EXPLAIN' => sprintf($lang['ban_card_explain'], $board_config['max_user_bancard']), 
			'L_VOTEBANCARD' => $lang['voteban_card'], 
			'L_VOTEBANCARD_EXPLAIN' => sprintf($lang['voteban_card_explain'], $board_config['max_user_votebancard']), 
			'L_PROFILE_VIEW_POPUP' => $lang['Profile_view_option'],
			'L_ALWAYS_ALLOW_SIGS' => $lang['Always_sigs'],
			'L_ALWAYS_ALLOW_AVATARS' => $lang['Show_avatars'],
			'L_POPUP_NOTES' => $lang['popup_notes'],
			'L_EMAIL_VALIDATION' => $lang['email_validation'],
			'L_SPECIAL' => $lang['User_special'],
			'L_SPECIAL_EXPLAIN' => $lang['User_special_explain'],
			'L_USER_ACTIVE' => $lang['User_status'],
			'L_ALLOW_PM' => $lang['User_allowpm'],
			'L_ALLOW_PROFILE' => $lang['User_allowprofile'],
			'L_ALLOW_AVATAR' => $lang['User_allowavatar'],
			'L_AVATAR_PANEL' => $lang['Avatar_panel'],
			'L_AVATAR_EXPLAIN' => $lang['Admin_avatar_explain'],
			'L_DELETE_AVATAR' => $lang['Delete_Image'],
			'L_CURRENT_IMAGE' => $lang['Current_Image'],
			'L_UPLOAD_AVATAR_FILE' => $lang['Upload_Avatar_file'],
			'L_UPLOAD_AVATAR_URL' => $lang['Upload_Avatar_URL'],
			'L_AVATAR_GALLERY' => $lang['Select_from_gallery'],
			'L_SHOW_GALLERY' => $lang['View_avatar_gallery'],
			'L_LINK_REMOTE_AVATAR' => $lang['Link_remote_Avatar'],
			'L_SIGNATURE' => $lang['Signature'],
			'L_SIGNATURE_EXPLAIN' => sprintf($lang['Signature_explain'], $board_config['max_sig_chars'] ),
			'L_NOTIFY_ON_PRIVMSG' => $lang['Notify_on_privmsg'],
			'L_NOTIFY_ON_PRIVMSG_TEXT' => $lang['Notify_on_privmsg_text'],
			'L_NOTIFY_ON_REPLY' => $lang['Always_notify'],
			'L_POPUP_ON_PRIVMSG' => $lang['Popup_on_privmsg'],
			'L_SOUND_ON_PRIVMSG' => $lang['Sound_on_privmsg'], 
			'L_PREFERENCES' => $lang['Preferences'],
			'L_PUBLIC_VIEW_EMAIL' => $lang['Public_view_email'],
			'L_ITEMS_REQUIRED' => $lang['Items_required'],
			'L_REGISTRATION_INFO' => $lang['Registration_info'],
			'L_PROFILE_INFO' => $lang['Profile_info'],
			'L_PROFILE_INFO_NOTICE' => $lang['Profile_info_warn'],
			'L_EMAIL_ADDRESS' => $lang['Email_address'],
			'L_ZIP_CODE' =>$lang['zip_code'],
			'L_REALNAME' => $lang['real_name'],
			'L_CUSTOM_POST_COLOR' => $lang['Custom_post_color'],
            'L_CUSTOM_POST_COLOR_EXPLAIN' => $lang['Custom_post_color_explain'],
			'L_KARMA' => $lang['Karma'],
			'L_KICKER_BAN' => $lang['tk_kicker_ban'],
			'L_MYINFO_PROFILE' => $lang['myInfo_profile'],
			'L_MYINFO_PROFILE_EXPLAIN' => $lang['myInfo_profile_explain'],
			'L_USER_LASTLOGON' => $lang['Last_logon'],
			'L_USER_REGDATE' => $lang['Joined'],
			'L_DELETE_USER' => $lang['User_delete'],
			'L_DELETE_USER_EXPLAIN' => $lang['User_delete_explain'],
			'L_SELECT_RANK' => $lang['RankFAQ_Title'],
			'L_DAILY_LIMIT' => $lang['Daily_limit'],
			'L_DAILY_LIMIT_EXPLAIN' => sprintf($lang['Daily_limit_explain'], '', ''),
			'L_WORD_WRAP' => $lang['Word_Wrap'],
			'L_WORD_WRAP_EXPLAIN' => strtr($lang['Word_Wrap_Explain'], array('%min%' => $board_config['wrap_min'], '%max%' => $board_config['wrap_max'])),
			'L_WORD_WRAP_EXTRA' => $lang['Word_Wrap_Extra'],
			'L_ALLOW_SIG' => $lang['User_allowsig'],
			'L_GROUP_PRIORITY' => $lang['Group_priority'],

			'HTML_STATUS' => $html_status,
			'BBCODE_STATUS' => sprintf($bbcode_status, '<a href="../' . append_sid("faq.$phpEx?mode=bbcode") . '" target="_phpbbcode">', '</a>'), 
			'SMILIES_STATUS' => $smilies_status,

			'S_FORM_ENCTYPE' => $form_enctype,
			'S_HIDDEN_FIELDS' => $s_hidden_fields,
			'S_PROFILE_ACTION' => append_sid("admin_users.$phpEx"))
		);

		if( file_exists(@phpbb_realpath('./../' . $board_config['avatar_path'])) && ($board_config['allow_avatar_upload'] == TRUE) )
		{
			if ( $form_enctype != '' )
			{
				$template->assign_block_vars('avatar_local_upload', array() );
			}
			$template->assign_block_vars('avatar_remote_upload', array() );
		}

		if( file_exists(@phpbb_realpath('./../' . $board_config['avatar_gallery_path'])) && ($board_config['allow_avatar_local'] == TRUE) )
		{
			$template->assign_block_vars('avatar_local_gallery', array() );
		}
		
		if( $board_config['allow_avatar_remote'] == TRUE )
		{
			$template->assign_block_vars('avatar_remote_link', array() );
		}
	}

	$template->pparse('body');
}
else if ( $mode == 'lookup' )
{
  	//
  	// Lookup user
  	//
  	$username = ( !empty($HTTP_POST_VARS['username']) ) ? str_replace('%', '%%', trim(strip_tags( $HTTP_POST_VARS['username'] ) )) : '';
  	$email = ( !empty($HTTP_POST_VARS['email']) ) ? trim(strip_tags(htmlspecialchars( $HTTP_POST_VARS['email'] ) )) : '';
  	$posts = ( !empty($HTTP_POST_VARS['posts']) ) ? intval(trim(strip_tags( $HTTP_POST_VARS['posts'] ) )) : '';
  	$joined = ( !empty($HTTP_POST_VARS['joined']) ) ? trim(strtotime( $HTTP_POST_VARS['joined'] ) ) : 0;
	
	$sql_where = ( !empty($username) ) ? 'u.username LIKE "%' . str_replace("\'", "''", $username) . '%"' : '';
	$sql_where .= ( !empty($email) ) ? ( ( !empty($sql_where) ) ? ' AND u.user_email LIKE "%' . $email . '%"' : 'u.user_email LIKE "%' . $email . '%"' ) : '';
	$sql_where .= ( !empty($posts) ) ? ( ( !empty($sql_where) ) ? ' AND u.user_posts >= ' . $posts : 'u.user_posts >= ' . $posts ) : '';
	$sql_where .= ( $joined ) ? ( ( !empty($sql_where) ) ? ' AND u.user_regdate >= ' . $joined : 'u.user_regdate >= ' . $joined ) : '';

  	if ( !empty($sql_where) )
  	{
  		$sql = "SELECT u.user_id, u.username, u.user_level, u.user_email, u.user_posts, u.user_active, u.user_regdate
      		FROM " . USERS_TABLE . " u
      		WHERE $sql_where
      			AND u.user_id <> " . ANONYMOUS . "
      		ORDER BY u.username ASC";
    	if ( !( $result = $db->sql_query($sql) ) )
    	{
      		message_die(GENERAL_ERROR, 'Unable to query users', '', __LINE__, __FILE__, $sql);
    	}
    	else if ( !$db->sql_numrows($result) )
    	{
    		$message = $lang['No_user_id_specified'] . '<br /><br />' . sprintf($lang['Click_return_useradmin'], '<a href="' . append_sid("admin_users.$phpEx") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.$phpEx?pane=right") . '">', '</a>');

      		message_die(GENERAL_MESSAGE, $message);
    	}
    	else if ( $db->sql_numrows($result) == 1 )
    	{
	      // Redirect to this user
    	  $row = $db->sql_fetchrow($result);

     		$template->assign_vars(array(
    			"META" => '<meta http-equiv="refresh" content="0;url=' . append_sid("admin_users.$phpEx?mode=edit&" . POST_USERS_URL . "=" . $row['user_id']) . '">')
	    	);

     		$message .= $lang['One_user_found'] . '<br /><br />' . sprintf($lang['Click_goto_user'], '<a href="' . append_sid("admin_users.$phpEx?mode=edit&" . POST_USERS_URL . "=" . $row['user_id']) . '">', '</a>');

     		message_die(GENERAL_MESSAGE, $message);
    	}
    	else
    	{
      		// Show select screen
    		include('../admin/page_header_admin.'.$phpEx);

    		$template->set_filenames(array(
		    	'body' => 'admin/user_lookup_body.tpl')
	    	);

	    	$template->assign_vars(array(
  	  			'L_USER_TITLE' => $lang['User_admin'],
	  			'L_USER_EXPLAIN' => $lang['User_admin_explain'],
				'L_USERNAME' => $lang['Username'],
				'L_POSTS' => $lang['Posts'],
				'L_JOINED' => $lang['Joined'],
				'L_ACTIVE' => $lang['Active'],
    			'L_EMAIL_ADDRESS' => $lang['Email_address'])
	    	);

	    	$i = 0;
	      	while ( $row = $db->sql_fetchrow($result) )
    	  	{
				$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

				$template->assign_block_vars('user_row', array(
					'ROW_CLASS' => $row_class,
					'USERNAME' => username_level_color($row['username'], $row['user_level'], $row['user_id']),
					'EMAIL' => $row['user_email'],
					'POSTS' => $row['user_posts'],
					'ACTIVE' => ( $row['user_active'] ) ? '<img src="' . $phpbb_root_path . $images['acp_disable'] . '" alt="' . $lang['Yes'] . '" title="' . $lang['Yes'] . '" />' : '<img src="' . $phpbb_root_path . $images['acp_delete'] . '" alt="' . $lang['No'] . '" title="' . $lang['No'] . '" />',
					'JOINED' => create_date($lang['DATE_FORMAT'], $row['user_regdate'], $board_config['board_timezone']),

					'U_USERNAME' => append_sid("admin_users.$phpEx?mode=edit&amp;" . POST_USERS_URL . "=" . $row['user_id']))
        		);

       			$i++;
      		}
     		$template->pparse('body');
    	}
  	}
  	else
  	{
		$message = $lang['No_user_id_specified'] . '<br /><br />' . sprintf($lang['Click_return_useradmin'], '<a href="' . append_sid("admin_users.$phpEx") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.$phpEx?pane=right") . '">', '</a>');
		
		message_die(GENERAL_MESSAGE, $message);
  	}
}
else
{
	//
	// Force e-mail update 
	//
	if ( !empty($HTTP_GET_VARS['forceemail_username']) )
	{
		$forceemail_user_id = $HTTP_GET_VARS['forceemail_username'];
	
		$sql = "SELECT username 
			FROM " . USERS_TABLE . "
			WHERE user_id = $forceemail_user_id";
		if( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not obtain username information for force email update', '', __LINE__, __FILE__, $sql);
		}
		$row = $db->sql_fetchrow($result);
		
		$force_email_username = $row['username'];
	}
	

	//
	// Default user selection box
	//
	$template->set_filenames(array(
		'body' => 'admin/user_select_body.tpl')
	);

	$template->assign_vars(array(
		'L_USER_TITLE' => $lang['User_admin'],
		'L_USER_EXPLAIN' => $lang['User_admin_explain'],
		'L_USER_LOOKUP_EXPLAIN' => $lang['User_lookup_explain'],
		'L_LOOK_UP' => $lang['Look_up_User'],
		'L_CREATE_USER' => $lang['Create_new_User'],
		'L_USERNAME' => $lang['Username'],
		'L_EMAIL_ADDRESS' => $lang['Email_address'],
		'L_POSTS' => $lang['Posts'],
		'L_JOINED' => $lang['Joined'],
		'L_JOINED_EXPLAIN' => $lang['User_joined_explain'],

		'FORCEEMAIL_USERNAME' =>  $force_email_username, 
		'U_SEARCH_USER' => append_sid("./../search.$phpEx?mode=searchuser"), 

		'S_PROFILE_ACTION' => append_sid($phpbb_root_path . 'profile.'.$phpEx.'?mode=' . REGISTER_MODE),
		'S_USER_ACTION' => append_sid("admin_users.$phpEx"),
		'S_USER_SELECT' => $select_list)
	);
		
	$template->pparse('body');
}

include('../admin/page_footer_admin.'.$phpEx);

?>