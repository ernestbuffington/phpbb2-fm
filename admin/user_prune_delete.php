<?php
/** 
*
* @package admin
* @version $Id: user_prune_delete.php,v 1.2.11 2003/03/05, 19:45:51 niels Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', true);

//
// Load default header
//
$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);
include($phpbb_root_path . 'includes/emailer.'.$phpEx); 


//
// Include language
//
$language = $board_config['default_lang'];

if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_prune_users.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_prune_users.' . $phpEx);

$del_user = ( isset($HTTP_POST_VARS['del_user']) ) ? intval($HTTP_POST_VARS['del_user']) : (( isset($HTTP_GET_VARS['del_user']) ) ? intval($HTTP_GET_VARS['del_user']):'');
$mode = ( isset($HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : ( ( isset($HTTP_GET_VARS['mode']) ) ? $HTTP_GET_VARS['mode']:'');
$days = ( isset($HTTP_POST_VARS['days']) ) ? intval($HTTP_POST_VARS['days']) : (( isset($HTTP_GET_VARS['days']) ) ? intval($HTTP_GET_VARS['days']):'');

switch ($mode)
{
	case 'user_name':	
		$sql = ' FROM ' . USERS_TABLE . ' 
			WHERE username = "' . str_replace("'", "\'", $del_user) . '"';
		break;

	case 'user_id':		
		$sql = ' FROM ' . USERS_TABLE . ' 
			WHERE user_id = "' . $del_user . '"';
		break;

	case 'prune_0':	
		$mode = 'Zero posters';
	case 'zero_poster' :	
		$sql = ' FROM ' . USERS_TABLE . ' 
			WHERE user_id <> "' . ANONYMOUS . '" 
				AND user_posts = 0 
				AND user_regdate < "' . ( time() - ( 86400 * $days ) ) . '"';
		break;

	case 'prune_1':	
		$mode = 'Not logged in';
	case 'not_login': 	
		$sql=' FROM ' . USERS_TABLE . ' 
			WHERE user_id <> "' . ANONYMOUS . '" 
				AND user_lastvisit = 0 
				AND user_regdate < "' . ( time() - ( 86400 * $days ) ) . '"';
		break;

	case 'prune_2':	
		$mode = 'Not activated';
		$sql=' FROM ' . USERS_TABLE . ' 
			WHERE user_id <> "' . ANONYMOUS . '" 
				AND user_lastvisit = 0 
				AND user_active = 0 
				AND user_actkey <> "" 
				AND user_regdate < "' . ( time() - ( 86400 * $days ) ) . '"';
		break;

	case 'prune_3':  
		$mode = 'Long time visit';
		$sql = 'FROM ' . USERS_TABLE . ' 
			WHERE user_id <> "' . ANONYMOUS . '" 
				AND user_lastvisit < ' . ( time() - (86400 * 365) ) . ' 
				AND user_regdate < "' . ( time() - ( 86400 * $days ) ) . '"';
		break; 

	case 'prune_4':  
		$mode = 'Average posts';
		$sql = 'FROM ' . USERS_TABLE . ' 
			WHERE user_id <> "' . ANONYMOUS . '" 
				AND user_posts / ( ( user_lastvisit - user_regdate ) / 86400 ) < 0.1 
				AND user_regdate < "' . ( time() - ( 86400 * $days ) ) . '"';
		break; 

	default:		
		message_die(GENERAL_ERROR, 'No mode specified.', '', __LINE__, __FILE__);
		break; 
}

$sql = 'SELECT user_id, username, user_email, user_lang ' . $sql . ' 
	ORDER BY username 
	LIMIT 800';
if( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Error obtaining userdata.', '', __LINE__, __FILE__, $sql);
}
$user_list = $db->sql_fetchrowset($result);

$i = 0;
while (isset($user_list[$i]['user_id']))
{
	@set_time_limit(5);
	
	$user_id = $user_list[$i]['user_id'];
	$username = str_replace("'", "\'", $user_list[$i]['username']);
	$user_email = $user_list[$i]['user_email'];
	$user_lang =  $user_list[$i]['user_lang'];
	
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
		SET poster_id = " . DELETED . ", post_username = '" . str_replace("\\'", "''", addslashes($user_list[$i]['username'])) . "' 
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
			OR username = '" . phpbb_clean_username($user_list[$i]['username']) . "'";
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
		WHERE trans_from = '" . phpbb_clean_username($user_list[$i]['username']) . "'
			OR trans_to = '" . phpbb_clean_username($user_list[$i]['username']) . "'";
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
		WHERE ( ( privmsgs_from_userid = $user_id 
			AND privmsgs_type = " . PRIVMSGS_NEW_MAIL . " )
				OR ( privmsgs_from_userid = $user_id
			AND privmsgs_type = " . PRIVMSGS_SENT_MAIL . " )
				OR ( privmsgs_to_userid = $user_id
			AND privmsgs_type = " . PRIVMSGS_READ_MAIL . " )
				OR ( privmsgs_to_userid = $user_id
			AND privmsgs_type = " . PRIVMSGS_SAVED_IN_MAIL . " )
				OR ( privmsgs_from_userid = $user_id
			AND privmsgs_type = " . PRIVMSGS_SAVED_OUT_MAIL . " ) )";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not select all user\'s private messages', '', __LINE__, __FILE__, $sql);
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
	
	$name_list .= (($name_list) ? ', ' : '<br /><br />') . $username;
	$i++;
}

$messages .= ( (DEBUG) ? '<b>Mode:</b> ' . $mode . '<br />' : '' ) . ( ($i) ? sprintf($lang['Prune_users_number'], $i) . $name_list : $lang['Prune_no_users'] );

message_die(GENERAL_MESSAGE, $messages . '<br /><br / >' . sprintf($lang['Click_return_prune_users'], '<a href="' . append_sid('admin_user_prune.'.$phpEx) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('admin/index.'.$phpEx.'?pane=right') . '">', '</a>')); 

$template->pparse('body');

include('page_footer_admin.'.$phpEx);

?>