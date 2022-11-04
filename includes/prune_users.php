<?php
/** 
*
* @package includes
* @version $Id: prune_users.php,v 1.3 2003/07/03 15:13:16 nivisec Exp $
* @copyright (c) 2003 Nivisec.com
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

if (!defined('IN_PHPBB'))
{
	die('Hacking attempt');
}

/* This is fake delete, it won't actually delete the user but mark it
as deleted...Made for my demo board :) You'll need to run the following sql
command to get it working, otherwise it will give an error:
ALTER TABLE `phpbb_users` ADD `user_fake_delete` TINYINT( 1 ) DEFAULT '0' NOT NULL ;
*/
define('FAKE_DELETE', false);

if (!function_exists('auto_delete_specific_user'))
{
	function auto_delete_specific_user($user_id, $username, $user_email, $user_lang)
	{
		global $db, $board_config, $phpbb_root_path, $phpEx, $table_prefix;
		
		// Update the deletion count
		$new_total = intval($board_config['admin_auto_delete_total']) + 1;
		$sql = "UPDATE " . CONFIG_TABLE . "
			  SET config_value = '$new_total'
			  WHERE config_name = 'admin_auto_delete_total'";
		$db->sql_query($sql);
		
		// Remove cache file
		@unlink($phpbb_root_path . 'cache/config_board.'.$phpEx);

		$board_config['admin_auto_delete_total'] = $new_total;
		
		if (FAKE_DELETE)
		{
			$sql = "UPDATE " . USERS_TABLE . "
				SET user_fake_delete = 1
				WHERE user_id = $user_id";
			if(!$db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not fake delete this user', '', __LINE__, __FILE__, $sql);
			}
		}
		else
		{
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
				SET poster_id = " . DELETED . ", post_username = '$username' 
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

			$sql = "SELECT group_id
				FROM " . GROUPS_TABLE . "
				WHERE group_moderator = $user_id";
			if( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not select groups where user was moderator', '', __LINE__, __FILE__, $sql);
			}
			
			while ( $row_group = $db->sql_fetchrow($result) )
			{
				$group_moderator[] = $row_group['group_id'];
			}
			$db->sql_freeresult($result);

			if ( sizeof($group_moderator) )
			{
				// Find the first admin we can in the database
				$sql = "SELECT user_id 
					FROM " . USERS_TABLE . "
					WHERE user_level = " . ADMIN . "
					ORDER BY user_id ASC
					LIMIT 1";
				if(!$result = $db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'Could not update group moderators', '', __LINE__, __FILE__, $sql);
				}
				$arow = $db->sql_fetchrow($result);
				
				$update_moderator_id = implode(', ', $group_moderator);
				
				$sql = "UPDATE " . GROUPS_TABLE . "
					SET group_moderator = " . $arow['user_id'] . "
					WHERE group_moderator IN ($update_moderator_id)";
				if( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'Could not update group moderators', '', __LINE__, __FILE__, $sql);
				}
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
					OR username = '" . phpbb_clean_username($username) . "'";
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
				WHERE trans_from = '" . phpbb_clean_username($username) . "'
					OR trans_to = '" . phpbb_clean_username($username) . "'";
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
				message_die(GENERAL_ERROR, 'Could not delete user from digests table', '', __LINE__, __FILE__, $sql);
			}

			$sql = "DELETE FROM " . DIGEST_FORUMS_TABLE . "
				WHERE user_id = $user_id";
			if (!$db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not delete user from digest forums table', '', __LINE__, __FILE__, $sql);
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
				if ( !$db->sql_query($delete_text_sql) )
				{
					message_die(GENERAL_ERROR, 'Could not delete private message text', '', __LINE__, __FILE__, $delete_text_sql);
				}

				$delete_sql = "DELETE FROM " . PRIVMSGS_TABLE . "
					WHERE privmsgs_id IN ($delete_sql_id)";
				
				if ( !$db->sql_query($delete_sql) )
				{
					message_die(GENERAL_ERROR, 'Could not delete private message info', '', __LINE__, __FILE__, $delete_sql);
				}
			}
			
			$sql = "UPDATE " . PRIVMSGS_TABLE . "
				SET privmsgs_to_userid = " . DELETED . "
				WHERE privmsgs_to_userid = $user_id";
			if ( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not update private messages saved to the user', '', __LINE__, __FILE__, $sql);
			}
				
			$sql = "UPDATE " . PRIVMSGS_TABLE . "
				SET privmsgs_from_userid = " . DELETED . "
				WHERE privmsgs_from_userid = $user_id";
			if ( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not update private messages saved from the user', '', __LINE__, __FILE__, $sql);
			}

			if ($board_config['user_prune_notify'] && !empty($user_email))
			{
				$script_name = preg_replace('/^\/?(.*?)\/?$/', '\1', trim($board_config['script_path'])) . '/profile.'.$phpEx.'?mode=' . REGISTER_MODE;
				$server_name = trim($board_config['server_name']);
				$server_protocol = ( $board_config['cookie_secure'] ) ? 'https://' : 'http://';
				$server_port = ( $board_config['server_port'] <> 80 ) ? ':' . trim($board_config['server_port']) . '/' : '/';
		
		        $emailer = new emailer($board_config['smtp_delivery']); 
			    $emailer->email_address($user_email); 
		      	$email_headers = "To: \"" . $username . "\" <" . $user_email. ">\r\n"; 
			    $email_headers .= "From: \"" . $board_config['sitename'] . "\" <" . $board_config['board_email'] . ">\r\n"; 
		      	$email_headers .= "Return-Path: " . (($userdata['user_email'] && $userdata['user_viewemail']) ? $userdata['user_email'] . "\r\n" : "\r\n"); 
		        $email_headers .= "X-AntiAbuse: Board servername - " . $server_name . "\r\n"; 
			    $email_headers .= "X-AntiAbuse: User_id - " . $userdata['user_id'] . "\r\n"; 
		      	$email_headers .= "X-AntiAbuse: Username - " . $userdata['username'] . "\r\n"; 
		        $email_headers .= "X-AntiAbuse: User IP - " . decode_ip($user_ip) . "\r\n"; 
			    $emailer->use_template('user_inactive_delete', (file_exists($phpbb_root_path . 'language/lang_' . $user_lang . '/email/user_inactive_delete.tpl')) ? $user_lang : ''); 
			    $emailer->extra_headers($email_headers); 
   			   	
   		   		$emailer->assign_vars(array( 
					'U_REGISTER' => $server_protocol . $server_name . $server_port . $script_name,
			    	'USER' => $userdata['username'],
					'USERNAME' =>  $username,
			        'SITENAME' => $board_config['sitename'], 
		      	    'BOARD_EMAIL' => $board_config['board_email'])
		      	); 
	   	     
   			     $emailer->send(); 
			    $emailer->reset(); 
			}
		}
	}
}

if (!function_exists('auto_delete_users'))
{
	function auto_delete_users()
	{
		global $db, $board_config, $phpbb_root_path, $phpEx;
		
		$fake_delete_sql = (FAKE_DELETE) ? 'AND user_fake_delete <> 1' : '';
		
		/* Check if we are ready to search again */
		if (time() > $board_config['last_auto_delete_users_attempt'] + (60 * $board_config['admin_auto_delete_minutes']))
		{
			/* Update the board_config last attempt */
			$sql = "UPDATE " . CONFIG_TABLE . "
			   SET config_value = '" . time() . "'
			   WHERE config_name = 'last_auto_delete_users_attempt'";
			$db->sql_query($sql);
			
			// Remove cache file
			@unlink($phpbb_root_path . 'cache/config_board.'.$phpEx);

			/* Find our specified deletion day UNIX_TIME */
			/* 86400 Seconds Per Day */
			$deletion_time = $board_config['admin_auto_delete_days'] * 86400;
			$deletion_time_inactive = $board_config['admin_auto_delete_days_inactive'] * 86400;
			$deletion_time_no_post = $board_config['admin_auto_delete_days_no_post'] * 86400;
			
			if ($board_config['admin_auto_delete_inactive'])
			{
				/* Find In-Active Users */
				$sql = "SELECT user_id, username, user_regdate, user_email, user_lang 
					FROM " . USERS_TABLE . "
		   			WHERE user_active = 0
		   				AND user_id <> " . ANONYMOUS . "
						$fake_delete_sql
					HAVING (user_regdate + $deletion_time_inactive) < " . time();
				if (!$result = $db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, $lang['Error_Auto_Delete'], '', __LINE__, __FILE__, $sql);
				}
				while ($row = $db->sql_fetchrow($result))
				{
					auto_delete_specific_user($row['user_id'], $row['username'], $row['user_email'], $row['user_lang']);
				}
				$db->sql_freeresult($result);
			}
			
			if ($board_config['admin_auto_delete_no_post'])
			{
				/* Find In-Active Users */
				$sql = "SELECT user_id, username, user_regdate, user_posts, user_email, user_lang 
					FROM " . USERS_TABLE . "
		   			WHERE user_id <> " . ANONYMOUS . "
						AND user_posts = 0
						$fake_delete_sql
					HAVING (user_regdate + $deletion_time_no_post) < " . time();
				if (!$result = $db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, $lang['Error_Auto_Delete'], '', __LINE__, __FILE__, $sql);
				}
				while ($row = $db->sql_fetchrow($result))
				{
					auto_delete_specific_user($row['user_id'], $row['username'], $row['user_email'], $row['user_lang']);
				}
				$db->sql_freeresult($result);
			}
			
			if ($board_config['admin_auto_delete_non_visit'])
			{
				/* Find Non-Visiting Users */
				$sql = "SELECT user_id, username, user_lastvisit, user_email, user_lang 
					FROM " . USERS_TABLE . "
		   			WHERE user_id <> " . ANONYMOUS . "
						$fake_delete_sql
					HAVING (user_lastvisit + $deletion_time) < " . time();
				if (!$result = $db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, $lang['Error_Auto_Delete'], '', __LINE__, __FILE__, $sql);
				}
				
				while ($row = $db->sql_fetchrow($result))
				{
					auto_delete_specific_user($row['user_id'], $row['username'], $row['user_email'], $row['user_lang']);
				}
				$db->sql_freeresult($result);
			}
		}
	}
}

if (!function_exists('load_auto_delete_emailer_info'))
{
	function load_auto_delete_emailer_info()
	{
		global $template, $lang, $board_config, $phpbb_root_path, $phpEx;
	
		$language = $board_config['default_lang'];
		if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_admin_user_prune.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_admin_user_prune.' . $phpEx);
		
		$template->assign_var('DELETION_INFO_NON_VISITING', sprintf($lang['Deletion_of_Non_Visiting'], $board_config['admin_auto_delete_days']));
		$template->assign_var('DELETION_INFO_INACTIVE', sprintf($lang['Deletion_of_Inactive'], $board_config['admin_auto_delete_days_inactive']));
		$template->assign_var('DELETION_INFO_NON_POSTING', sprintf($lang['Deletion_of_Non_Posting'], $board_config['admin_auto_delete_no_post']));
	}
}

?>