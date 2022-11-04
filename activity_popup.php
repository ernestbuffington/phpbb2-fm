<?php
/** 
*
* @package phpBB2
* @version $Id: activity_popup.php,v 1.1.0 2005 austin Exp $
* @copyright (c) 2003 aUsTiN-Inc
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', true);
$phpbb_root_path = './';
include_once($phpbb_root_path .'extension.inc');
include_once($phpbb_root_path .'common.'. $phpEx);
include_once($phpbb_root_path .'includes/functions_amod_plus.'. $phpEx);
include_once($phpbb_root_path .'includes/bbcode.'. $phpEx);

$mode = ($_GET['mode']) ? $_GET['mode'] : $HTTP_GET_VARS['mode'];

if (!$mode)
{
	$mode = ($_POST['mode']) ? $_POST['mode'] : $HTTP_POST_VARS['mode'];
}
		
$action = ($_GET['action']) ? $_GET['action'] : $HTTP_GET_VARS['action'];

if (!$action)
{
	$action = ($_POST['action']) ? $_POST['action'] : $HTTP_POST_VARS['action'];
}				

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_ACTIVITY);
init_userprefs($userdata);
//
// End session management
//
		
$user_id = $userdata['user_id'];
if (!$board_config['ina_guest_play'])
{
		if (!$userdata['session_logged_in'] && $user_id == ANONYMOUS)
	{
		$header_location = ( @preg_match("/Microsoft|WebSTAR|Xitami/", getenv("SERVER_SOFTWARE") ) ) ? "Refresh: 0; URL=" : "Location: ";
		header($header_location . append_sid("login.$phpEx?redirect=activity.$phpEx", true) );
		exit();
	}
}

function CheckReturnPath($id)
{
	global $lang, $phpEx, $board_config, $userdata;
			
	if(($board_config['ina_use_rating_reward']) && ($board_config['ina_rating_reward'] > 0))
	{
		$points_name = $board_config['points_name'];
		add_points($userdata['user_id'], $board_config['ina_rating_reward']);
		$msg = str_replace("%P%", $board_config['ina_rating_reward'] .' '. $points_name, $lang['rating_payout_message']);
	}
				
	if (!$id)
	{
		message_die(GENERAL_MESSAGE, $lang['rating_page_6'] .'<br /><br />'. $msg);
	}
	else
	{
		message_die(GENERAL_MESSAGE, $lang['rating_page_6'] .'<br /><br />'. $msg);
	}
}
			
$gen_simple_header = TRUE;
$template->set_filenames(array(
	'body' => 'amod_files/activity_popup_body.tpl') 
);
	
if ($mode == 'chat')
{
	$page_title = $lang['shoutbox_title'];
	$action 	= ($_GET['action'])? $_GET['action'] : $HTTP_GET_VARS['action'];
	if (!$action)
	{
		$action = ($_POST['action'])? $_POST['action'] : $HTTP_POST_VARS['action'];
	}
			
	$template->assign_block_vars('chat', array());		
	
	if (!$board_config['ina_use_shoutbox'] || $userdata['user_id'] == ANONYMOUS)
	{
		message_die(GENERAL_MESSAGE, $lang['shoutbox_closed']);
	}
		
	if ($action == 'history')
	{
		$what_day = $HTTP_GET_VARS['history'];
		
		$q = "SELECT *
			FROM ". INA_CHAT ."
			WHERE chat_date = '". $what_day ."'";
		$r 				= $db->sql_query($q);
		$past_chat 		= $db->sql_fetchrow($r);
	
		$chat_session 	= $past_chat['chat_text'];
		
		#==== Parse out any smilies
		if ($board_config['allow_smilies'])
		{
			smilies_pass($chat_session);
		}
			
		#==== Censor words if needed
		$orig_word = $replacement_word = array();
		obtain_word_list($orig_word, $replacement_word);
		$chat_session		= preg_replace($orig_word, $replacement_word, $chat_session);
							
		$start 		= str_replace('%S%', '<tr><td class="row2" colspan="2"><span class="genmed">', $chat_session);
		$end 		= str_replace('%E%', '</span></td></tr>', $start);	
		$smilies	= ($board_config['allow_smilies']) ? smilies_pass($end) : $end;
		$bbcode		= preg_replace('/\:[0-9a-z\:]+\]/si', ']', $smilies);
		$display 	= $bbcode;
		
		$template->assign_block_vars('chat.history', array(
			'CHAT'		=> $display,
			'TITLE'		=> str_replace('%D%', $past_chat['chat_date'], $lang['shoutbox_header']),
			'REFRESH'	=> $lang['shoutbox_refresh'])
		);
				
		$q = "SELECT chat_date
			FROM ". INA_CHAT ."
			WHERE chat_date <> '". $what_day ."'";
		$r 						= $db->sql_query($q);
		$n						= $db->sql_numrows($r);
		
		while ($past_days	= $db->sql_fetchrow($r))
		{
			if ($past_days['chat_date'])
			{
				$template->assign_block_vars('chat.history.dates', array(
					'HISTORY'	=> $past_days['chat_date'])
				);
			}
		}
		
		if ($n > 0)
		{
			$default = $lang['shoutbox_history'];		
		}
		else
		{
			$default = $lang['shoutbox_no_history'];
		}
				
		$template->assign_vars(array(
			'DEFAULT' => $default)
		);								
	}
		
	if ($action == 'add')
	{
		$q = "SELECT *
			FROM ". INA_CHAT ."
			WHERE chat_date = '". date('y-m-d') ."'";
		$r 				= $db->sql_query($q);
		$todays_chat 	= $db->sql_fetchrow($r);
		
		$chat_session 	= $todays_chat['chat_text'];
		
		$q = "SELECT *
			FROM ". INA_SESSIONS ."
			WHERE playing_id = '". $userdata['user_id'] ."'";
		$r 				= $db->sql_query($q);
		$playing 		= $db->sql_fetchrow($r);
		
		$is_playing 	= $playing['playing_id'];
		$is_playing_g 	= $playing['playing'];
		$is_playing_t	= $playing['playing_time'];
			
		include_once($phpbb_root_path .'includes/functions_post.'. $phpEx);
		$to_add = $HTTP_POST_VARS['msg'];		
		$to_add = trim($to_add);
		
		# Make all necessary checks.
		if (empty($to_add))
		{
			message_die(GENERAL_ERROR, $lang['shoutbox_error']);
		}
				
		if (!$board_config['allow_html'])
		{
			$html_on = 0;
		}
		else
		{
			$html_on = ($userdata['user_id'] == ANONYMOUS) ? $board_config['allow_html'] : $userdata['user_allowhtml'];
		}
		
		if (!$board_config['allow_smilies'])
		{
			$smilies_on = 0;
		}
		else
		{
			$smilies_on = ($userdata['user_id'] == ANONYMOUS) ? $board_config['allow_smilies'] : $userdata['user_allowsmile'];
		}
				
		$bbcode_on			= 0;
		$bbcode_uid 		= ($bbcode_on) ? make_bbcode_uid() : '';
		$message 			= prepare_message(trim($to_add), $html_on, $bbcode_on, $smilies_on, $bbcode_uid);
		
		#==== Same day chat or new days chat?
		if ($chat_session)
		{
			$message 		= addslashes(stripslashes($message));
			$message 		= str_replace('%S%', '%s%', $message);
			$message 		= str_replace('%E%', '%e%', $message);		
			$new_session	= '%S%<b>'. $userdata['username'] .'</b>: '. $message .'%E%';			
			$new_session 	.= $chat_session;
			$new_session	= addslashes(stripslashes($new_session));
						
			$q = "UPDATE ". INA_CHAT ."
				SET chat_text = '". $new_session ."'
				WHERE chat_date = '". date('Y-m-d') ."'";
			$db->sql_query($q);
		}
		else
		{
			$message 		= addslashes(stripslashes($message));
			$message 		= str_replace('%S%', '%s%', $message);
			$message 		= str_replace('%E%', '%e%', $message);		
			$new_session	= '%S%<b>'. $userdata['username'] .'</b>: '. $message .'%E%';
			$new_session	= addslashes(stripslashes($new_session));
						
			$q = "INSERT INTO ". INA_CHAT ."
				VALUES ('". date('Y-m-d') ."', '". $new_session ."')";
			$db->sql_query($q);				
		}
				
		#==== Reset users session to playing games if a ina session is there
		if ($is_playing)
		{
			$q = "UPDATE ". USERS_TABLE ."
				SET user_session_page = '". PAGE_PLAYING_GAMES ."', ina_cheat_fix = '". $is_playing_g ."', playing_time = '". $is_playing_t ."'
				WHERE user_id = '". $userdata['user_id'] ."'";
			$db->sql_query($q);
			
			$q = "UPDATE ". SESSIONS_TABLE ."
				SET session_page = '". PAGE_PLAYING_GAMES ."'
				WHERE session_user_id = '". $userdata['user_id'] ."'";
			$db->sql_query($q);
		}		
	}
			
	if ( ($action == 'view' || $action == 'add') && ($action != 'history') )
	{	
		$q = "SELECT *
			FROM ". INA_CHAT ."
			WHERE chat_date = '". date('y-m-d') ."'";
		$r 				= $db->sql_query($q);
		$todays_chat 	= $db->sql_fetchrow($r);
		
		$chat_session 	= $todays_chat['chat_text'];
				
		#==== Parse out any smilies
		if ($board_config['allow_smilies'])
		{
			smilies_pass($chat_session);
		}
			
		#==== Censor words if needed
		$orig_word = $replacement_word = array();
		obtain_word_list($orig_word, $replacement_word);
		$chat_session		= preg_replace($orig_word, $replacement_word, $chat_session);
							
		$start 		= str_replace('%S%', '<tr><td align="left" class="row2" colspan="3"><span class="genmed">', $chat_session);
		$end 		= str_replace('%E%', '</span></td></tr>', $start);	
		$smilies	= ($board_config['allow_smilies']) ? smilies_pass($end) : $end;
		$bbcode		= preg_replace('/\:[0-9a-z\:]+\]/si', ']', $smilies);
		$display 	= $bbcode;
		
		$template->assign_block_vars('chat.view', array(
			'CHAT'		=> $display,
			'TITLE'		=> str_replace('%D%', $todays_chat['chat_date'], $lang['shoutbox_header']),
			'SUBMIT'	=> $lang['shoutbox_submit'],
			'REFRESH'	=> $lang['shoutbox_refresh'])
		);
				
		$q = "SELECT chat_date
			FROM ". INA_CHAT ."
			WHERE chat_date <> '". date('Y-m-d') ."'";
		$r 						= $db->sql_query($q);
		$n						= $db->sql_numrows($r);
		
		while ($past_days	= $db->sql_fetchrow($r))
		{
			if ($past_days['chat_date'])
			{
				$template->assign_block_vars('chat.view.history', array(
					'HISTORY'	=> $past_days['chat_date'])
				);
			}
		}
		
		if ($n > 0)
		{
			$default = $lang['shoutbox_history'];		
		}
		else
		{
			$default = $lang['shoutbox_no_history'];
		}
				
		$template->assign_vars(array(
			'DEFAULT' => $default)
		);
	}
}
		
if ($mode == 'challenge')
{
	$who 	= ($_GET['u']) ? $_GET['u'] : $HTTP_GET_VARS['u'];
	$who_id	= ($_GET['u']) ? $_GET['u'] : $HTTP_GET_VARS['u'];
	$game 	= ($_GET['g']) ? $_GET['g'] : $HTTP_GET_VARS['g'];
    	
	if ($who == $userdata['user_id'])
	{
		message_die(GENERAL_MESSAGE, $lang['challenge_self_error']);
	}
		
	if ($userdata['user_id'] == ANONYMOUS || $who == ANONYMOUS)
	{
		message_die(GENERAL_MESSAGE, $lang['challenge_guest_error']);
	}
	
	$page_title		= $lang['challenge_link_key'];
	$returned		= ChallengeSelected($who, $game);
	$returned_data	= explode('%RETURNED%', $returned);				
	$who 			= $returned_data[0];		
	$game			= $returned_data[1];
	$message_sent 	= $board_config['ina_challenge_msg'];
	$message_sent 	= $who .', '. $message_sent;
	$message_sent 	= str_replace('%n%', $userdata['username'], $message_sent);
	$message_sent 	= str_replace('%g%', $game, $message_sent);
	$top			= $lang['pm_msg_top'];	
	$middle			= "<br>------------------------------------------------------------------<br>";
	$bottom			= $lang['pm_msg_bottom'];
	send_challenge_pm($who_id, $board_config['ina_challenge_sub'], $message_sent);
	
	$template->assign_block_vars('challenge', array(
		'MSG'	=> $top . $middle . $message_sent . $middle . $bottom,
		'TITLE'	=> $lang['challenge_information'])
	);
}
		
if ($mode == 'rate') 
{
	$template->assign_block_vars('rate', array());
	
	if (!$action)
	{
		$cat_var_id = ($_GET['id']) ? $_GET['id'] : $HTTP_GET_VARS['id'];
		$game 		= ($_GET['game']) ? $_GET['game'] : $HTTP_GET_VARS['game']; 
			
		if ($cat_var_id)
		{
			$cat_var = '?return=cat&id='. $cat_var_id;
		}
		else
		{
			$cat_var = '';		
		}
			
		if (!$game) 
		{
			message_die(GENERAL_MESSAGE, $lang['rating_page_1']);			 
		}
		
		$q = "SELECT * 
			FROM ". INA_RATINGS ." 
			WHERE game_id = '". $game ."'
				AND player = '". $userdata['user_id'] ."'"; 
		$r 		= $db -> sql_query($q);
		$row 	= $db -> sql_fetchrow($r);
			
		if ($row['player']) 
		{
			message_die(GENERAL_MESSAGE, $lang['rating_page_error_exists']);
		}
				
		$q = "SELECT * 
			FROM ". iNA_GAMES ." 
			WHERE game_id = '". $game ."'"; 
		$r 		= $db -> sql_query($q);
		$row 	= $db -> sql_fetchrow($r);
							
		$template->assign_block_vars('rate.main', array(
			 'TITLE' 		=> str_replace('%g%', $row['proper_name'], $lang['rating_page_3']),
			 'CAT_RATE'		=> $cat_var,
			 'DEFAULT_RATE'	=> $lang['rate_game_default'],
			 'CHOICES'		=> $lang['rating_page_4'],
			 'GAME'			=> $row['game_id'],
			 'SUBMIT'		=> $lang['Submit'])
		); 			
	}
			
	if($action == 'submit_rating')
	{
		$rating = ($_POST['rating']) ? $_POST['rating'] : $HTTP_POST_VARS['rating'];
		$game	= ($_POST['game']) ? $_POST['game'] : $HTTP_POST_VARS['game'];
				
		$q = "SELECT * 
			FROM ". iNA_GAMES ." 
			WHERE game_id = '". $game ."'"; 
		$r 		= $db -> sql_query($q);
		$row 	= $db -> sql_fetchrow($r);
						
		if (!$rating) 
		{
			message_die(GENERAL_MESSAGE, str_replace("%G%", $row['proper_name']), $lang['error_message']);
		}
		
		$q = "SELECT * 
			FROM ". INA_RATINGS ." 
			WHERE game_id = '". $game ."'
				AND player = '". $userdata['user_id'] ."'"; 
		$r 		= $db -> sql_query($q);
		$row 	= $db -> sql_fetchrow($r);
		
		if ($row['player']) 
		{
			message_die(GENERAL_ERROR, $lang['rating_page_error_exists']);
		}
				
		if (!$rating || !$game) 
		{
			message_die(GENERAL_ERROR, $lang['rating_page_7']);
		}
					
		$q = "INSERT INTO ". INA_RATINGS ."
			VALUES ('". $game ."', '". $rating ."', '". time() ."', '". $userdata['user_id'] ."')"; 
		$r = $db -> sql_query($q);
				
		CheckReturnPath($cat_var_id);
	}					
}
		
if ($mode == 'comments')
{
	if(($board_config['ina_disable_comments_page']) && ($userdata['user_level'] != ADMIN)) 
	{
		message_die(GENERAL_ERROR, $lang['disabled_page_error'], $lang['ban_error']);	
	}
	
	$page_title 	= $lang['comments_link_key'];
	$template->assign_block_vars('comments', array());
	$game_comment 	= ($_GET['game']) ? $_GET['game'] : $HTTP_GET_VARS['game'];
	$comment		= ($_GET['user']) ? $_GET['user'] : $HTTP_GET_VARS['user'];
	$action			= ($_GET['action']) ? $_GET['action'] : $HTTP_GET_VARS['action'];
	
	if (!$action)
	{
		$action	= ($_POST['action']) ? $_POST['action'] : $HTTP_POST_VARS['action'];
	}
	
	if($action == 'posting_comment')
	{						
		$comment_left 		= ($_POST['comment']) ? $_POST['comment'] : $HTTP_POST_VARS['comment'];
		$game_for_comment 	= ($_POST['comment_game_name']) ? $_POST['comment_game_name'] : $HTTP_POST_VARS['comment_game_name'];
		
		$q = "SELECT score
			FROM ". iNA_SCORES ."
			WHERE game_name = '". $game_for_comment ."'
				AND player = '". $userdata['username'] ."'";	
		$r 		= $db->sql_query($q);
		$row 	= $db->sql_fetchrow($r);
		
		$score 	= $row['score'];
					
		if (strlen($comment_left) > 200)
		{
			$difference = strlen($comment_left) - 200;
		
			message_die(GENERAL_ERROR, $lang['trophy_comment_2'] . $difference . $lang['trophy_comment_3'], $lang['ban_error']);
		}
				
		if (strlen($comment_left) < 2)
		{
			message_die(GENERAL_ERROR, $lang['trophy_comment_4'], $lang['ban_error']);
		}
											
        $q = "SELECT *
			FROM ". WORDS_TABLE; 
		if (!$r = $db -> sql_query($q)) 
        {
        	message_die(GENERAL_ERROR, "Error Selecting Censored Word List.", "", __LINE__, __FILE__, $q); 
 		}
 				
		while ($row	= $db -> sql_fetchrow($r))
		{
			if (eregi(quotemeta($row['word']), $comment_left))
			{
					$comment_left = str_replace($row['word'], $row['replacement'], $comment_left);
			}
		}
					
		$comment_left = addslashes(stripslashes($comment_left));
		
		$sql = "INSERT INTO ". INA_TROPHY_COMMENTS ."
			VALUES ('". $game_for_comment ."', '". $userdata['user_id'] ."', '". $comment_left ."', '". time() ."', '". $score ."')"; 
		if (!$result = $db -> sql_query($sql)) 
		{
			message_die(GENERAL_ERROR, "Error Inserting Comment Information.", "", __LINE__, __FILE__, $sql); 
		}
				
	    redirect('activity_popup.'. $phpEx .'?mode=comments&game='. $game_for_comment, TRUE);									
	}
}
			
if ( ($action == 'leave_comment') && ($comment > '0') && ($game_comment) )
{		
	$game_link = CheckGameImages($game_comment, '');
								
	$template->assign_block_vars('comments.post_comment', array(
		'POST_TITLE' 	=> $lang['trophy_comment_7'], 
		'POST_LENGTH'	=> $lang['trophy_comment_8'],
		'POST_SUBMIT'	=> $lang['trophy_comment_9'], 
		'POST_GAME'	=> $game_comment,
		'POST_LINK'	=> $phpbb_root_path .'activity_popup.'. $phpEx .'?mode=comments',
		'POST_IMAGE'	=> $game_link)
	); 										
}
			
if ( (!$action) && ($game_comment) )
{ 			
	$check_comments = ($_GET['game']) ? $_GET['game'] : $HTTP_GET_VARS['game'];
		
	#==== Trophy Holder ===================================== |
    $sql = "SELECT * 
		FROM ". INA_TROPHY ."
        WHERE game_name = '". $check_comments ."'"; 
	if (!$result = $db -> sql_query($sql)) 
    {
    	message_die(GENERAL_ERROR, "Error Retrieving Current Trophy Holder.", "", __LINE__, __FILE__, $sql); 
	}
	$trophy_row 			= $db -> sql_fetchrow($result);
	
	$current_holder_id 		= $trophy_row['player'];
	$current_holder_date	= $trophy_row['date'];
	$current_holder_score	= $trophy_row['score'];

	#==== Game Data ========================================= |
    $sql = "SELECT *
		FROM ". iNA_GAMES ."
        WHERE game_name = '". $check_comments ."'"; 
	if (!$result = $db -> sql_query($sql)) 
	{
		message_die(GENERAL_ERROR, $lang['no_game_data'], "", __LINE__, __FILE__, $sql);
	}		
	$row 		= $db->sql_fetchrow($result);
	
	$game_link 	= $row['proper_name'];
	$game_image = CheckGameImages($check_comments, $row['proper_name']);
			
	if ($row['reverse_list'])
	{
		$list_type = 'ASC';
	}
	else
	{
		$list_type = 'DESC';
	}
	
	#==== Comments Array ==================================== |
	$sql = "SELECT * 
		FROM ". INA_TROPHY_COMMENTS ."
        WHERE game = '". $check_comments ."'
		ORDER BY score $list_type"; 
	if (!$result = $db -> sql_query($sql)) 
    {
    	message_die(GENERAL_ERROR, "Error Selecting Comments.", "", __LINE__, __FILE__, $sql); 
	}			
	$trophy_comments 	= $db->sql_fetchrowset($result);
	$total_comments 	= $db->sql_numrows($result);
		
	if (!$total_comments)
	{
		message_die(GENERAL_MESSAGE, $lang['trophy_comment_10'], $lang['trophy_comment_6']);		
	}
	
	#==== User Array ======================================== |
    $sql = "SELECT user_id, username, user_level 
		FROM ". USERS_TABLE; 
	if (!$result = $db -> sql_query($sql)) 
    {
    	message_die(GENERAL_ERROR, "Error Selecting User Information.", "", __LINE__, __FILE__, $sql); 
	}
				
	$users_data = $db->sql_fetchrowset($result);
		
	for ($a = 0; $a < count($users_data); $a++)
	{
		if ($current_holder_id == $users_data[$a]['user_id'])
		{
			$current_holder_name = $users_data[$a]['username'];
			$current_holder_level = $users_data[$a]['user_level'];
			break;
		}
	}
			
	for ($a = 0; $a < $total_comments; $a++)
	{
		if ($trophy_comments[$a]['player'] == $current_holder_id)
		{
			$current_holder_comment = htmlspecialchars($trophy_comments[$a]['comment']);
			break;
		}
	}
					
	$current_holder_score = ($userdata['user_level'] == ADMIN) ? '<a href="'. append_sid('activity_popup.'. $phpEx .'?mode=comments&amp;action=delete_comment&amp;game=' . $game_comment . '&amp;player=' . $current_holder_id) . '" class="genmed">' . FormatScores($current_holder_score) . '</a>' : FormatScores($current_holder_score);
			
	$template->assign_block_vars('comments.main', array(
		'MAIN_LEFT' 		=> $lang['trophy_comment_11'], 
		'MAIN_CENTER1'		=> $lang['trophy_comment_12'],
		'MAIN_CENTER2'		=> $lang['trophy_comment_13'],			  
		'MAIN_RIGHT' 		=> $lang['Date'],
		'MAIN_IMAGE'		=> $game_image,
		'MAIN_NAME'		=> $game_link,
		'TROPHY_HOLDER'	=> '<a href="'. append_sid('profile.'.$phpEx.'?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $current_holder_id) . '" class="genmed" target="_blank">' . username_level_color($current_holder_name, $current_holder_level, $current_holder_id) . '</a>',
		'TROPHY_DATE'		=> create_date($board_config['default_dateformat'], $current_holder_date, $board_config['board_timezone']),
		'TROPHY_SCORE'		=> $current_holder_score,
		'TROPHY_COMMENT'	=> $current_holder_comment)
	); 
				
	$i 		= 0;
	$pos 	= 2;
	for ($a = 0; $a < $total_comments; $a++)
	{
		#==== Skip the trophy holder, as its already shown from above.
		if ( (htmlspecialchars($trophy_comments[$a]['comment']) != $current_holder_comment) && ($trophy_comments[$a]['date'] != $current_holder_date) )
		{															
			$row_class 			= ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2']; 
			$comment_left_text 	= htmlspecialchars($trophy_comments[$a]['comment']);
			$comment_left_date 	= create_date($board_config['default_dateformat'], $trophy_comments[$a]['date'], $board_config['board_timezone']);
			$comment_left_score = FormatScores($trophy_comments[$a]['score']);
			$comment_left_id	= $trophy_comments[$a]['player'];
			$i++;
				
			for ($b = 0; $b < count($users_data); $b++)
			{
				if ($comment_left_id == $users_data[$b]['user_id'])
				{
					$comment_left_name = $users_data[$b]['username'];
					$comment_left_level = $users_data[$b]['user_level'];
					break;
				}
			}
			$comment_left_score = ($userdata['user_level'] == ADMIN) ? '<a href="' . append_sid('activity_popup.'. $phpEx .'?mode=comments&amp;action=delete_comment&amp;game=' . $game_comment . '&amp;player=' . $comment_left_id) . '" class="genmed">' . $comment_left_score . '</a>' : $comment_left_score;
				
			$template->assign_block_vars('comments.comment', array(
				'TROPHY_HOLDER'	=> '<a href="' . append_sid('profile.'.$phpEx.'?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $comment_left_id) . '" class="genmed" target="_blank">' . username_level_color($comment_left_name, $comment_left_level, $comment_left_id) . '</a>',
				'TROPHY_DATE'		=> $comment_left_date,
				'TROPHY_SCORE'		=> $comment_left_score,
				'TROPHY_COMMENT'	=> $comment_left_text,
				'ROW'				=> $row_class,
				'POS'				=> $pos)
			);
			$pos++;
		}
	}
}	 
			
if ( ($action == 'delete_comment') && ($userdata['user_level'] == ADMIN) )
{
	$g = ($_GET['game']) ? $_GET['game'] : $HTTP_GET_VARS['game'];
	$n = ($_GET['player']) ? $_GET['player'] : $HTTP_GET_VARS['player'];

    $q = "DELETE FROM ". INA_TROPHY_COMMENTS ."
    	WHERE player = '". $n ."'
			AND game = '". $g ."'"; 
	if (!$r = $db -> sql_query($q))  
    {
    	message_die(GENERAL_ERROR, "Error Deleting Comment.", "", __LINE__, __FILE__, $q); 
	}
     
	redirect('activity_popup.'. $phpEx .'?mode=comments&game='. $g, TRUE);						
}
		
if ($mode == 'info')
{
	$game_id = (isset($HTTP_GET_VARS['g']) ) ? intval($HTTP_GET_VARS['g']) : 0;

	$sql = "SELECT * 
		FROM ". iNA_GAMES ."
		WHERE game_id = '". $game_id ."'";
	if (!$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, $lang['no_game_data'], "", __LINE__, __FILE__, $sql);
	}
		
	$game_info = $db->sql_fetchrow($result);
		
	$sql = "SELECT * 
		FROM ". INA_CATEGORY ."
		WHERE cat_id = '". $game_info['cat_id'] ."'";
	$result = $db->sql_query($sql);
	$cat_info = $db->sql_fetchrow($result);		

	if ($game_info['reverse_list'])
	{
		$list_type = 'ASC';
	}
	else
	{
		$list_type = 'DESC';
	}
	
	$sql = "SELECT * 
		FROM ". INA_TROPHY ."
		WHERE game_name = '". $game_info['game_name'] ."'";
	if (!$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, $lang['no_score_data'], "", __LINE__, __FILE__, $sql);
	}
			
	$score_info = $db->sql_fetchrow($result);

	$best_score 	= $score_info['score'];
	$best_player 	= $score_info['player'];
	$page_title 	= $game_info['proper_name']; 

	$sql = "SELECT username 
		FROM ". USERS_TABLE ."
		WHERE user_id = '". $best_player ."'";
	$result 	= $db->sql_query($sql);
	$user_info 	= $db->sql_fetchrow($result);
				
	$best_player = $user_info['username'];
		
	if ($game_info['game_charge'])
	{
		$cost = $game_info['game_charge'] . '&nbsp;'; 
	}
	else
	{
		$cost = $lang['game_free']; 
	}
	
	if ($game_info['game_charge'] > 0)
	{
		$cost .= ' ' . $board_config['points_name'];
	}
	
	if ($game_info['instructions'])
	{
		$instructions = $game_info['instructions'];
	}
	else
	{
		$instructions = $lang['game_no_instructions'];
	}
		
	$q = "SELECT MAX(date) AS last_date
		FROM ". iNA_SCORES ."
		WHERE game_name = '". $game_info['game_name'] ."'";
	$r 		= $db->sql_query($q);
	$date 	= $db->sql_fetchrow($r);
		
	$game_type 	= '';
	$game_type 	= ($game_info['game_type'] == 1) ? $lang['game_type_one'] : $game_type;
	$game_type	= ($game_info['game_type'] == 2) ? $lang['game_type_two'] : $game_type;
	$game_type 	= ($game_info['game_type'] == 3) ? $lang['game_type_three'] : $game_type;
	$game_type 	= ($game_info['game_type'] == 4) ? $lang['game_type_four'] : $game_type;		
	$borrowed 	= $game_info['played'] * $game_info['game_charge'];
	$game_date	= create_date($board_config['default_dateformat'], $date['last_date'], $board_config['board_timezone']);
		
	$template->assign_block_vars('info', array(
		'L_TITLE'		=> $lang['info_page_title'],
		'L_TITLE_2'		=> $lang['info_page_title_2'],
		'L_PLAYED'		=> $lang['info_page_played'],
		'L_PLAYER'		=> ($game_info['game_type'] != 2) ? $lang['info_page_player'] : '',
		'L_COST'		=> $lang['info_page_cost'],
		'L_SCORE'		=> ($game_info['game_type'] != 2) ? $lang['info_page_score'] : '',
		'L_BONUS'		=> $lang['info_page_bonus'],
		'L_CATEGORY'	=> $lang['info_page_category'],																					
		'L_BORROWED'	=> $lang['info_page_borrowed'],		
		'L_TYPE'		=> $lang['game_info_type'],
		'L_DATE'		=> $lang['game_info_date'],
		
		'DATE'			=> $game_date,
		'TYPE'			=> $game_type,
		'NAME' 			=> $game_info['game_name'],
		'PATH' 			=> $game_info['game_path'],
		'DESC' 			=> $game_info['game_desc'],
		'PLAYED' 		=> number_format($game_info['played']),
		'COST' 			=> number_format($cost),
		'BORROWED' 		=> number_format($borrowed),
		'CATEGORY'		=> $cat_info['cat_name'],
		'BONUS' 		=> $game_info['game_bonus'],
		'BEST_PLAYER' 	=> ($game_info['game_type'] != 2) ? $best_player : '',
		'BEST_SCORE' 	=> ($game_info['game_type'] != 2) ? FormatScores($best_score) : '',
		'INSTRUCTIONS' 	=> $instructions) 
	);		
}

//
// Generate page
//
include($phpbb_root_path .'includes/page_header.'. $phpEx);

$template->pparse('body');
	  		
include($phpbb_root_path .'includes/page_tail.'. $phpEx);

?>