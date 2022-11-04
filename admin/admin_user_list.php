<?php
/** 
*
* @package admin
* @version $Id: admin_user_list.php,v 1.56.0 2003/07/08 15:57:20 acydburn Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	$module['Users']['Management_list'] = $filename;

	return;
}

//
// Let's set the root dir for phpBB
//
$phpbb_root_path = './../';
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);
include($phpbb_root_path . 'includes/functions_admin.'.$phpEx);

$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;
$start = ($start < 0) ? 0 : $start;

if ( isset($HTTP_GET_VARS['mode']) || isset($HTTP_POST_VARS['mode']) )
{
	$mode = ( isset($HTTP_POST_VARS['mode']) ) ? htmlspecialchars($HTTP_POST_VARS['mode']) : htmlspecialchars($HTTP_GET_VARS['mode']);
}
else
{
	$mode = '';
}

if ( isset($HTTP_GET_VARS['smode']) || isset($HTTP_POST_VARS['smode']) )
{
	$smode = ( isset($HTTP_POST_VARS['smode']) ) ? htmlspecialchars($HTTP_POST_VARS['smode']) : htmlspecialchars($HTTP_GET_VARS['smode']);
}
else
{
	$smode = 'joined';
}

if ( isset($HTTP_GET_VARS['amount']) || isset($HTTP_POST_VARS['amount']) )
{
	$amount = ( isset($HTTP_POST_VARS['amount']) ) ? intval($HTTP_POST_VARS['amount']) : intval($HTTP_GET_VARS['amount']);
}
else
{
	$amount = $board_config['topics_per_page'];
}

if(isset($HTTP_POST_VARS['order']))
{
	$sort_order = ($HTTP_POST_VARS['order'] == 'ASC') ? 'ASC' : 'DESC';
}
else if(isset($HTTP_GET_VARS['order']))
{
	$sort_order = ($HTTP_GET_VARS['order'] == 'ASC') ? 'ASC' : 'DESC';
}
else
{
	$sort_order = 'ASC';
}

if ( isset($HTTP_GET_VARS['alphanum']) || isset($HTTP_POST_VARS['alphanum']) ) 
{ 
   $alphanum = ( isset($HTTP_POST_VARS['alphanum']) ) ? $HTTP_POST_VARS['alphanum'] : $HTTP_GET_VARS['alphanum']; 
   $alpha_where = ( $alphanum == '#' ) ? "AND username NOT RLIKE '^[A-Z]'":"AND username LIKE '$alphanum%'"; 
}

switch ($mode)
{
	case "activate":
		$users = ( isset($HTTP_POST_VARS['user_id_list']) ) ?  $HTTP_POST_VARS['user_id_list'] : array();
    
    	if ( sizeof($users) > 0 )
    	{
		   	for($i = 0; $i < sizeof($users); $i++)
    	   	{
		    	$user_id = $users[$i];
		   	
		   	   	$sql = "SELECT user_active 
		   	   		FROM " . USERS_TABLE . " 
		   	   		WHERE user_id = " . $user_id;
		   	   	if( !($result = $db->sql_query($sql)) )
		   	   	{
			   		message_die(GENERAL_ERROR, 'Could not obtain user information', '', __LINE__, __FILE__, $sql);
		       	}

		   	   	$row = $db->sql_fetchrow($result);
	
		   	   	if ( $row['user_active'] )
		   	   	{
		       		$user_status = 0;
		       	}
		   	   	else
		   	   	{
		 	       	$user_status = 1;
		   	   	}	
		
		   	   	$sql = "UPDATE " . USERS_TABLE . "
					SET user_active = $user_status
					WHERE user_id = " . $user_id;
	
		       	if( !($result = $db->sql_query($sql)) )
		       	{
			   		message_die(GENERAL_ERROR, 'Could not update users status', '', __LINE__, __FILE__, $sql);
		       	}
		   	}	
			
			$message = $lang['User_status_updated'] . "<br /><br />" . sprintf($lang['Click_return_useradmin'], "<a href=\"" . append_sid("admin_user_list.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");	

	   		message_die(GENERAL_MESSAGE, $message);	
		}	
		else
    	{
    	    message_die(GENERAL_ERROR, "None selected.");
    	}	
 		break;
 
 	case "delete":
 		$hidden_fields = '';
	
		if ( isset($HTTP_POST_VARS['user_id_list']) )
		{
			$users = $HTTP_POST_VARS['user_id_list'];
			for($i = 0; $i < sizeof($users); $i++)
			{
				$hidden_fields .= '<input type="hidden" name="user_id_list[]" value="' . $users[$i] . '" />';
			}
		}
	
		$template->set_filenames(array(
		    'body' => 'admin/confirm_body.tpl')
    	  );
	
		$template->assign_vars(array(
			'MESSAGE_TITLE' => $lang['Delete'],
			'MESSAGE_TEXT' => $lang['Confirm_user_delete'],
		
			'U_INDEX' => append_sid('admin_user_list.$phpEx'),
			'L_INDEX' => $lang['User_list_title'],
		
			'L_YES' => $lang['Yes'],
			'L_NO' => $lang['No'],
		
			'S_CONFIRM_ACTION' => append_sid('admin_user_list.'.$phpEx.'?mode=confirm_delete'),
			'S_HIDDEN_FIELDS' => $hidden_fields)
		);	
  		break;
  
  	case "confirm_delete":
		$users = ( isset($HTTP_POST_VARS['user_id_list']) ) ?  $HTTP_POST_VARS['user_id_list'] : array();
    
    	if ( sizeof($users) > 0 )
    	{	
			for($i = 0; $i < sizeof($users); $i++)
       		{	
	   			$user_id = $users[$i];

				if (!($this_userdata = get_userdata($user_id)))
				{
					message_die(GENERAL_MESSAGE, $lang['No_user_id_specified'] );
				}

				$this_userdata['xdata'] = get_user_xdata($user_id);
			
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
	
				$sql = "DELETE FROM " . $prefix . "ina_ban
					WHERE id = $user_id
						OR username = '" . phpbb_clean_username($this_userdata['username']) . "'";
				if( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'Could not delete game ban', '', __LINE__, __FILE__, $sql);
				}

				$sql = "DELETE FROM " . $prefix . "ina_challenge_tracker
					WHERE user = $user_id";
				if( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'Could not delete game challenge count', '', __LINE__, __FILE__, $sql);
				}
	
				$sql = "DELETE FROM " . $prefix . "ina_challenge_users
					WHERE user_to = $user_id
						OR user_from = $user_id";
				if( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'Could not delete game challenges', '', __LINE__, __FILE__, $sql);
				}
	
				$sql = "DELETE FROM " . $prefix . "ina_favorites
					WHERE user = $user_id";
				if( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'Could not delete game favorites', '', __LINE__, __FILE__, $sql);
				}
				
				$sql = "DELETE FROM " . $prefix . "ina_rating_votes
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

				$sql = "DELETE FROM " . $prefix . "ina_sessions
					WHERE playing_id = $user_id";
				if( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'Could not delete game sessions', '', __LINE__, __FILE__, $sql);
				}
	
				$sql = "DELETE FROM " . $prefix . "ina_top_scores
					WHERE player = $user_id";
				if( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'Could not delete game top scores', '', __LINE__, __FILE__, $sql);
				}
	
				$sql = "DELETE FROM " . $prefix . "ina_trophy_comments
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

				$sql = "DELETE FROM " . $prefix . "links
					WHERE user_id = $user_id";
				if( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'Could not delete user links', '', __LINE__, __FILE__, $sql);
				}
			
				$sql = "DELETE FROM " . $prefix . "link_comments
					WHERE poster_id = $user_id";
				if( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'Could not delete user link comments', '', __LINE__, __FILE__, $sql);
				}
			
				$sql = "DELETE FROM " . $prefix . "link_votes
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
			}
		}
		else
   		{
   			message_die(GENERAL_ERROR, 'None selected.');
    	}	

  		$message = $lang['User_deleted'] . '<br /><br />' . sprintf($lang['Click_return_useradmin'], '<a href="' . append_sid("admin_user_list.$phpEx") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.$phpEx?pane=right") . '">', '</a>');

  		message_die(GENERAL_MESSAGE, $message);

  		break;
  
  	case "ban":
		$hidden_fields = '';

		if ( isset($HTTP_POST_VARS['user_id_list']) )
		{
			$users = $HTTP_POST_VARS['user_id_list'];
			for($i = 0; $i < count($users); $i++)
			{
				$hidden_fields .= '<input type="hidden" name="user_id_list[]" value="' . $users[$i] . '" />';
			}
		}

		$template->set_filenames(array(
		    'body' => 'admin/confirm_body.tpl')
    	);
	
		$template->assign_vars(array(
		    'MESSAGE_TITLE' => $lang['Ban'],
			'MESSAGE_TEXT' => $lang['Confirm_user_ban'],
		
			'U_INDEX' => append_sid('admin_user_list.$phpEx'),
			'L_INDEX' => $lang['User_list_title'],
			
			'L_YES' => $lang['Yes'],
			'L_NO' => $lang['No'],
		
			'S_CONFIRM_ACTION' => append_sid('admin_user_list.'.$phpEx.'?mode=confirm_ban'),
			'S_HIDDEN_FIELDS' => $hidden_fields)
		);	
		break;
  
	case "confirm_ban":
		$users = ( isset($HTTP_POST_VARS['user_id_list']) ) ?  $HTTP_POST_VARS['user_id_list'] : array();
    
    	if ( count($users) > 0 )
    	{	
		   for($i = 0; $i < count($users); $i++)
    	   {
		   		$user_id = $users[$i];
   	
   				$sql = "INSERT INTO " . BANLIST_TABLE . " (ban_userid)
				    VALUES (" . $user_id . ")";
				if ( !$db->sql_query($sql) )
				{
				    message_die(GENERAL_ERROR, "Couldn't insert ban_userid info into database", "", __LINE__, __FILE__, $sql);
				}
		    }
		}
		else
   		{
   			message_die(GENERAL_ERROR, "None selected.");
    	}	
		$message = $lang['User_banned'] . '<br /><br />' . sprintf($lang['Click_return_useradmin'], '<a href="' . append_sid("admin_user_list.$phpEx") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.$phpEx?pane=right") . '">', '</a>');

		message_die(GENERAL_MESSAGE, $message);
  		break;
  
  	default:	
		//
		// Memberlist sorting
		//
		$mode_types_text = array($lang['Sort_Username'], $lang['Active'], $lang['Sort_Joined'], $lang['Last_visit'], $lang['Sort_Posts'], $lang['Sort_Email'],);
		$mode_types = array('username', 'active', 'joindate', 'lastvisit', 'posts', 'email');

		$select_sort_mode = '<select name="smode">';
		for($i = 0; $i < count($mode_types_text); $i++)
		{
			$selected = ( $smode == $mode_types[$i] ) ? ' selected="selected"' : '';
			$select_sort_mode .= '<option value="' . $mode_types[$i] . '"' . $selected . '>' . $mode_types_text[$i] . '</option>';
		}
		$select_sort_mode .= '</select>';
	
		$select_sort_order = '<select name="order">';
		if($sort_order == 'ASC')
		{
			$select_sort_order .= '<option value="ASC" selected="selected">' . $lang['Sort_Ascending'] . '</option><option value="DESC">' . $lang['Sort_Descending'] . '</option>';
		}
		else
		{
			$select_sort_order .= '<option value="ASC">' . $lang['Sort_Ascending'] . '</option><option value="DESC" selected="selected">' . $lang['Sort_Descending'] . '</option>';
		}
		$select_sort_order .= '</select>';	

		//
		// Generate page
		//
		$template->set_filenames(array(
			'body' => 'admin/user_list_body.tpl')
		);		

		$template->assign_vars(array(
			'L_USER_LIST_TITLE' => $lang['User_admin'],
			'L_USER_LIST_DESCRIPTION' => $lang['User_list_description'],
			'L_SELECT_SORT_METHOD' => $lang['Select_sort_method'],
			'L_USERNAME' => $lang['Username'],
			'L_EMAIL' => $lang['Email'],
			'L_ORDER' => $lang['Order'],
			'L_SORT' => $lang['Sort'],
			'L_JOINED' => $lang['Joined'], 
			'L_POSTS' => $lang['Posts'], 
			'L_PM' => $lang['Private_Message'], 
			'L_ACTIVE' => $lang['Active'],
			'L_EDIT_PERMISSION' => $lang['Permissions'],
			'L_EDIT_PROFILE' => $lang['Profile'],
			'L_USERGROUP' => $lang['Groupcp'],
			'L_LAST_VISIT' => $lang['Last_logon'],		

			'L_SHOW' => $lang['Show_users'],
			'S_AMOUNT' => $amount,
	
			'S_MODE_SELECT' => $select_sort_mode,
			'S_ORDER_SELECT' => $select_sort_order,
			'S_MODE_ACTION' => append_sid("admin_user_list.$phpEx" . ( (isset($alphanum)) ? "?alphanum=$alphanum" : '' )),
		
			'S_ACTION' => append_sid("admin_user_list.$phpEx"),
			'S_ACTIVATE' => $lang['Activate'],
			'S_BAN' => $lang['Ban'],
			'S_SELECT_ONE' => $lang['Select_one']) 
		);	

		$alpha_range = range('A', 'Z');
		$alphanum_range = array_merge(array('' => 'All'), array('%23' => '#'), $alpha_range);
		foreach ( $alphanum_range as $key => $alpha )
		{
			if ( in_array($alpha,$alpha_range) ) $key = $alpha;
			$alphanum_search_url = append_sid("admin_user_list.$phpEx?mode=" . ( ( isset($HTTP_GET_VARS['smode']) || isset($HTTP_POST_VARS['smode']) ) ? $smode : 'username' ) . "&amp;sort=$sort_order&amp;alphanum=" . strtolower($key));
	
			$template->assign_block_vars('alphanumsearch', array(
				'SEARCH_SIZE' => floor(95 / sizeof($alphanum_range)) . '%',
				'SEARCH_TERM' => $alpha,
				'SEARCH_LINK' => $alphanum_search_url)
			);
		}
	
		switch( $smode )
		{
			case 'joined':
				$order_by = "user_regdate ASC LIMIT $start, " . $amount;
				break;
			case 'active':
				$order_by = "user_active ASC LIMIT $start, " . $amount;
				break;
			case 'lastvisit':
				$order_by = "user_lastvisit $sort_order LIMIT $start, " . $amount;
				break;
			case 'username':
				$order_by = "username $sort_order LIMIT $start, " . $amount;
				break;
			case 'posts':
				$order_by = "user_posts $sort_order LIMIT $start, " . $amount;
				break;
			case 'email':
				$order_by = "user_email $sort_order LIMIT $start, " . $amount;
				break;
			default:
				$order_by = "user_regdate $sort_order LIMIT $start, " . $amount;
				break;
		}

		$sql = "SELECT * 
		   FROM " . USERS_TABLE . " 
		   WHERE user_id <> " . ANONYMOUS . " $alpha_where 
		   ORDER BY $order_by";
		if( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not query users', '', __LINE__, __FILE__, $sql);
		}
	
		if ( $row = $db->sql_fetchrow($result) )
		{
			$i = 0;
			do
			{
				$username = username_level_color($row['username'], $row['user_level'], $row['user_id']);
				$user_id = $row['user_id'];
				$status = $row['user_active'];
			
				$sql = "SELECT * 
		   			 FROM " . USER_GROUP_TABLE . " 
		  			 WHERE user_id = " . $user_id;
				if( !($result2 = $db->sql_query($sql)) )
				{
				 	message_die(GENERAL_ERROR, 'Could not query group', '', __LINE__, __FILE__, $sql);
				}
	
				$group_name = "";
				
	 			while ($user_group = $db->sql_fetchrow($result2)) 
				{			  
	        		$group_status = $lang['Member'];
		
		            if ( $user_group['user_pending'] == 1)
					{
						$group_status = $lang['Pending'];
					}
		
					$group_id = $user_group['group_id'];
					
					$sql = "SELECT * 
	   					FROM " . GROUPS_TABLE . " 
   						WHERE group_single_user <> 1 
   							AND group_id = " . $group_id;
					if( !($result3 = $db->sql_query($sql)) )
					{
						message_die(GENERAL_ERROR, 'Could not query groups', '', __LINE__, __FILE__, $sql);
					}
					
					while ($group_info = $db->sql_fetchrow($result3)) 
					{
						if ( $group_name ) 
						{
							$group_name .= '<br />';
						}
		
  		            	if ( $group_info['group_moderator'] == $user_id )
  		            	{
  							$group_status = $lang['Group_Moderator'];
    	            	}
						 
				  		$group_name .= '<a href="' . $phpbb_root_path . 'groupcp.'.$phpEx.'?' . POST_GROUPS_URL . '=' . $group_info['group_id'] . '">' . $group_info['group_name'] . '</a><br />(' . $group_status . ')';
					}
				}
		
			$user_group_name = $group_name;
		
			$email_uri = append_sid($phpbb_root_path . 'profile.'.$phpEx.'?mode=email&amp;' . POST_USERS_URL . '=' . $user_id);
			$email = '<a href="' . $email_uri . '" class="gen"><img src="' . $phpbb_root_path . $images['icon_email'] . '" alt="' . $lang['Email'] . '" title="' . $lang['Email'] . '" /></a>';

			$joined = create_date($lang['DATE_FORMAT'], $row['user_regdate'], $board_config['board_timezone']);
			$posts = ( $row['user_posts'] ) ? $row['user_posts'] : 0;
			
			$last_visit = create_date($lang['DATE_FORMAT'], $row['user_lastvisit'], $board_config['board_timezone']);
		
			//status varible
			$l_active = (!$status) ? '<img src="' . $phpbb_root_path . $images['acp_delete'] . '" alt="' . $lang['No'] . '" title="' . $lang['No'] . '" />' : '<img src="' . $phpbb_root_path . $images['acp_disable'] . '" alt="' . $lang['Yes'] . '" title="' . $lang['Yes'] . '" />';
			$l2_active = (!$status) ? TRUE : FALSE;
				
			$active = $l_active;
		
			//profile varibles
			$temp_url = append_sid($phpbb_root_path . "profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=" . $user_id);
			$profile = '<a href="' . $temp_url . '" class="gen">' . $lang['Read_profile'] . '</a>';

			//post search varibles
			$temp_url = append_sid($phpbb_root_path . "search.$phpEx?search_author=" . urlencode($row['username']) . "&amp;showresults=posts");
			$search = '<a href="' . $temp_url . '" class="gen">' . $lang['Search_user_posts'] . '</a>';
		
			//pm varibles
			$temp_url = append_sid($phpbb_root_path . "privmsg.$phpEx?mode=post&amp;" . POST_USERS_URL . "=" . $user_id);
			$pm = '<a href="' . $temp_url . '"><img src="' . $phpbb_root_path . $images['icon_pm'] . '" alt="' . $lang['Send_private_message'] . '" title="' . $lang['Send_private_message'] . '" /></a>';
		
			//permissions
			$temp_url = append_sid("admin_ug_auth.$phpEx?mode=user&amp;" . POST_USERS_URL . "=" . $user_id);
			$permission = '<a href="' . $temp_url . '" class="gen"><img src="' . $phpbb_root_path . $images['icon_perm'] . '" alt="' . $lang['Auth_Control_User'] . '" title="' . $lang['Auth_Control_User'] . '" /></a>';
		
			//edit profile
			$temp_url = append_sid("admin_users.$phpEx?mode=edit&amp;" . POST_USERS_URL . "=" . $user_id);
			$edit_profile = '<a href="' . $temp_url . '" class="gen"><img src="' . $phpbb_root_path . $images['icon_mangmt'] . '" alt="' . $lang['Edit'] . ' ' . $lang['Profile'] . '" title="' . $lang['Edit'] . ' ' . $lang['Profile'] . '" /></a>';
		
			$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

			$template->assign_block_vars('memberrow', array(
				'USER_ID' => $user_id,
				'ROW_NUMBER' => $i + ( $HTTP_GET_VARS['start'] + 1 ),
				'ROW_CLASS' => $row_class,
				'USERNAME' => $username,
				'JOINED' => $joined,
				'POSTS' => $posts,
				'PROFILE' => $profile, 
				'SEARCH' => $search,
				'EMAIL' => $email,
				'ACTIVE' => $active,
				'PM_IMG' => $pm_img,
				'PM' => $pm,
				'EMAIL' => $email,		
				'DELETE' =>	$delete,
				'GROUP' => $user_group_name,
				'BAN' => $ban,
				'PERMISSION' => $permission,
				'EDIT_PROFILE' => $edit_profile,
				'LAST_VISIT' => $last_visit,
				
				'U_SEARCH_POST' => append_sid($phpbb_root_path . "search.$phpEx?search_author=" . urlencode($row['username']) . "&amp;showresults=posts"),
				'U_VIEWPROFILE' => append_sid($phpbb_root_path . "profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=$user_id"))
			);
	
			$i++;
		}
		while ( $row = $db->sql_fetchrow($result) );
	}

	if ( $smode != 'topten' || $amount < 10 )
	{
	   $sql = "SELECT count(*) AS total 
	      FROM " . USERS_TABLE . " 
	      WHERE user_id <> " . ANONYMOUS . " $alpha_where";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Error getting total users', '', __LINE__, __FILE__, $sql);
		}
	
		if ( $total = $db->sql_fetchrow($result) )
		{
			$total_members = $total['total'];
	
			$pagination = generate_pagination("admin_user_list.$phpEx?smode=$smode&order=$sort_order&amount=". $amount . ( ( isset($alphanum) ) ? "&alphanum=$alphanum" : '' ), $total_members, $amount, $start);
		}
	}
	else
	{	
		$pagination = '&nbsp;';
		$total_members = 10;
	}

	$template->assign_vars(array(
		'PAGINATION' => $pagination,
		'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / $amount ) + 1 ), ceil( $total_members / $amount )), 

		'L_GOTO_PAGE' => $lang['Goto_page'])
	);
	break;
}

$template->pparse('body');

include('./page_footer_admin.'.$phpEx);

?>