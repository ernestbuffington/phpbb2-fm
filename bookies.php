<?php 
/** 
*
* @package phpBB2
* @version $Id: bookies.php, v 3.0.0 2004/11/17 17:49:34 majorflam Exp $
* @copyright (c) 2004 Majorflam
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', true); 
$phpbb_root_path = './'; 
include($phpbb_root_path . 'extension.inc'); 
include($phpbb_root_path . 'common.'.$phpEx); 

//
// Start session management 
//
$userdata = session_pagestart($user_ip, PAGE_BOOKIES); 
init_userprefs($userdata); 
//
// End session management
//
	
	
//
// Include language
//
$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_bookmakers.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_bookmakers.' . $phpEx);
	
	
if ( !$userdata['session_logged_in'] ) 
{ 
	redirect("login.".$phpEx."?redirect=bookies.".$phpEx); 
	exit; 
} 

//
// Generate page
//
$page_title = $lang['Bookies'];
include($phpbb_root_path . 'includes/page_header.'.$phpEx); 

$template->set_filenames(array( 
	'body' => 'bookies.tpl') 
);
make_jumpbox('viewforum.'.$phpEx);

$random_num = rand(1, 1000);
$old_rand = ( isset($HTTP_GET_VARS['pending']) ) ? intval($HTTP_GET_VARS['pending']) : 0;

// General Config
$new_bet_img = '<a href="' . append_sid("bookies.$phpEx?mode=placebet") . '"><img src="' . $images['icon_bookie_place_bet'] . '" title="' . $lang['icon_bookie_place_bet'] . '" alt="' . $lang['icon_bookie_place_bet'] . '" /></a>';
$your_stats = '<a href="' . append_sid('bookie_yourstats.'.$phpEx) . '"><img src="' . $images['icon_bookie_yourstats'] . '" title="' . $lang['bookie_yourstats_title'] . '" alt="' . $lang['bookie_yourstats_title'] . '" /></a>';
$all_stats = '<a href="' . append_sid('bookie_allstats.'.$phpEx) . '"><img src="' . $images['icon_bookie_allstats'] . '" title="' . $lang['bookie_allstats_title'] . '" alt="' . $lang['bookie_allstats_title'] . '" /></a>';
$yourstats_redirect = '<meta http-equiv="refresh" content="2;url=' . append_sid("bookie_yourstats.$phpEx?") . '">';
$bookie_redirect = '<meta http-equiv="refresh" content="2;url=' . append_sid("bookies.$phpEx?") . '">';
$bookie_redirect_pending = '<meta http-equiv="refresh" content="2;url=' . append_sid("bookies.$phpEx?pending=$random_num") . '#' . $random_num . '">';

$leader_expand = htmlspecialchars($HTTP_GET_VARS['leader']);

$bookie_redirect_error = '<meta http-equiv="refresh" content="5;url=' . append_sid("bookies.$phpEx?") . '">';

if ( $board_config['bookie_welcome'] )
{
	$template->assign_block_vars('switch_welcome_message', array());
	
	$template->assign_vars(array(
		'BOOKIES_HEADER' => $lang['bookie_header'],
		'BOOKIES_WELCOME' => $board_config['bookie_welcome'])
	);
}

$mode = htmlspecialchars($HTTP_GET_VARS['mode']);

if ( $mode == 'delete' )
{
	if ( $board_config['bookie_edit_stake'] != 1 )
	{
		$message = $lang['Not_Authorised'] . $bookie_redirect; 
      	message_die(GENERAL_MESSAGE, $message); 
	} 

	$bet_id = intval($HTTP_GET_VARS['bet_id']);
	$sql = "SELECT bet, user_id, admin_betid, time 
		FROM " . BOOKIE_BETS_TABLE . "
		WHERE bet_id = " . $bet_id;
	if ( !($result = $db->sql_query($sql)) ) 
	{ 
		message_die(GENERAL_ERROR, 'Error in deleting pending bet', '', __LINE__, __FILE__, $sql); 
	}
	$row = $db->sql_fetchrow($result);

	//
	// OK, let's make sure there have been no url tricks
	//
	if ( $row['time'] < time() ) 
	{ 
		$message = $lang['Not_Authorised'] . $bookie_redirect; 
		message_die(GENERAL_MESSAGE, $message); 
	}

	if ( $row['user_id'] != $userdata['user_id'] )
	{
		$message = $lang['Not_Authorised'] . $bookie_redirect;
		message_die(GENERAL_MESSAGE, $message);
	}
	else if ( isset($HTTP_POST_VARS['confirm']) )
	{
		$del_stake = $row['bet'];
		$del_user = $row['user_id'];
		$del_admin_betid = $row['admin_betid'];
		
		// pay the stake back
		$sql = "UPDATE " . USERS_TABLE . "
			SET user_points = user_points + $del_stake
			WHERE user_id = " . $del_user;
		if ( !$db->sql_query($sql) ) 
		{ 
			message_die(GENERAL_ERROR, 'Error in deleting pending bet', '', __LINE__, __FILE__, $sql); 
		}

		// now remove the bet and it's details
		$sql = "DELETE FROM " . BOOKIE_BETS_TABLE . "
			WHERE bet_id = " . $bet_id;
		if ( !$db->sql_query($sql) ) 
		{ 
			message_die(GENERAL_ERROR, 'Error in deleting pending bet', '', __LINE__, __FILE__, $sql); 
		}

		// Remove the commission for this bet?
		$sql = "SELECT multi 
			FROM " . BOOKIE_ADMIN_BETS_TABLE . "
			WHERE bet_id = $del_admin_betid
			LIMIT 1";
		if ( !($result = $db->sql_query($sql)) ) 
		{ 
			message_die(GENERAL_ERROR, 'Error in deleting pending bet', '', __LINE__, __FILE__, $sql); 
		}
		$row = $db->sql_fetchrow($result);
		if ( $row['multi'] == -1 )
		{
			$sql = "UPDATE " . BOOKIE_BET_SETTER_TABLE . "
				SET commission = commission - " . intval(( $del_stake*( $board_config['bookie_commission']/100) )) . "
				WHERE bet_id = $del_admin_betid
					AND setter != " . $userdata['user_id'];
			if ( !$db->sql_query($sql) ) 
			{ 
				message_die(GENERAL_ERROR, 'Error in deducting commission', '', __LINE__, __FILE__, $sql); 
			}
		}
		else
		{
			$sql = "UPDATE " . BOOKIE_BET_SETTER_TABLE . "
				SET commission = commission - " . intval(( $del_stake*( $board_config['bookie_commission']/100) )) . "
				WHERE bet_id = " . $row['multi'] . "
					AND setter != " . $userdata['user_id'];
			if ( !$db->sql_query($sql) ) 
			{ 
				message_die(GENERAL_ERROR, 'Error in deducting commission', '', __LINE__, __FILE__, $sql); 
			}
		}
		
		// remove the admin bet if it's a user set bet
		$sql = "SELECT multi 
			FROM " . BOOKIE_ADMIN_BETS_TABLE . "
			WHERE bet_id = $del_admin_betid
			LIMIT 1";
		if ( !($result = $db->sql_query($sql)) ) 
		{ 
			message_die(GENERAL_ERROR, 'Error in deleting pending bet', '', __LINE__, __FILE__, $sql); 
		}
		$row = $db->sql_fetchrow($result);
		
		if ( $row['multi'] == -5 )
		{
			$sql = "DELETE FROM " . BOOKIE_ADMIN_BETS_TABLE . "
				WHERE bet_id = " . $del_admin_betid;
			if ( !$db->sql_query($sql) ) 
			{ 
				message_die(GENERAL_ERROR, 'Error in deleting pending bet', '', __LINE__, __FILE__, $sql); 
			}
		}
		
		$message = $lang['bookie_delete_success'] . $bookie_redirect;
		
		message_die(GENERAL_MESSAGE, $message);
	}
	else if ( isset($HTTP_POST_VARS['cancel']) )
	{
			$message = $lang['bookie_action_cancelled'] . $bookie_redirect;
			message_die(GENERAL_MESSAGE, $message);
	}
	else
	{
		$template->set_filenames(array( 
    	    'body' => 'confirm_body.tpl') 
		);
		
		$template->assign_vars(array(
			'MESSAGE_TITLE' => $lang['bookie_confirm_delbet_title'],
			'S_CONFIRM_ACTION' => append_sid("bookies.$phpEx?mode=delete&amp;bet_id=$bet_id"),
			'MESSAGE_TEXT' => $lang['bookie_confirm_delbet_text'],
			'L_YES' => $lang['Yes'],
			'L_NO' => $lang['No'])
		);
		
		$template->pparse('body');

		// standard page footer 
		include($phpbb_root_path . 'includes/page_tail.'.$phpEx);
	}
}

if ( !$mode )
{
	// Are there any bets to show
	// are we updating stakes?
	if ( $board_config['bookie_edit_stake'] == 1 )
	{
		if ( isset($HTTP_POST_VARS['change_stake'] ) )
		{
			$points_now = $userdata['user_points'];
			$time = time();
			$userID = $userdata['user_id'];
			
			$sql = "SELECT bet_id, time, bet 
				FROM " . BOOKIE_BETS_TABLE . "
				WHERE user_id = $userID
					AND time > $time
					AND checked = 0";
			if ( !($result = $db->sql_query($sql)) ) 
			{ 
				message_die(GENERAL_ERROR, 'Error in building existing bets', '', __LINE__, __FILE__, $sql); 
			}
			
			while ( $row = $db->sql_fetchrow($result) )
			{
				$stake_name = $row['time'];
				$change_betid = $row['bet_id'];
				$old_stake = $row['bet'];
				
				if ( !empty($HTTP_POST_VARS[$change_betid . $stake_name] ) )
				{
					$new_stake=intval($HTTP_POST_VARS[$change_betid . $stake_name]);
					if ( $new_stake < $board_config['bookie_min_bet'] )
					{
						$message = sprintf($lang['bookie_stake_update_unsuccess_min'],  number_format($board_config['bookie_min_bet']), $board_config['points_name']) . $bookie_redirect_error;

						message_die(GENERAL_MESSAGE, $message);
					}
					
					if ( $new_stake > $board_config['bookie_max_bet'] && intval($board_config['bookie_max_bet']) > 0 )
					{
						$message = sprintf($lang['bookie_stake_update_unsuccess_max'], number_format($board_config['bookie_max_bet']), $board_config['points_name']) . $bookie_redirect_error;

						message_die(GENERAL_MESSAGE, $message);
					}
					
					if ( $new_stake > $old_stake )
					{
						$subtraction = $new_stake - $old_stake;
						
						if ( $points_now < $subtraction )
						{
							$message = $lang['bookie_no_cash'] . $bookie_redirect_pending;
							message_die(GENERAL_MESSAGE, $message);
						}
						
						if ( $points_now == $subtraction || $points_now > $subtraction )
						{
							// take points
							$sql_a = "UPDATE " . USERS_TABLE . "
								SET user_points = user_points - '$subtraction'
								WHERE user_id = " . $userID;
							if (!$db->sql_query($sql_a))
							{ 
								message_die(GENERAL_ERROR, 'Error in updating points in the DB', '', __LINE__, __FILE__, $sql_a); 
							}
							
							$points_now = $points_now - $subtraction;
						}
					}	
					if ( $new_stake < $old_stake )
					{
						$addition=$old_stake-$new_stake;
						// add points
						$sql_a = "UPDATE " . USERS_TABLE . "
							SET user_points = user_points + '$addition'
							WHERE user_id = " . $userID;
						if (!$db->sql_query($sql_a))
						{ 
							message_die(GENERAL_ERROR, 'Error in updating points in the DB', '', __LINE__, __FILE__, $sql_a); 
						}
						
						$points_now = $points_now + $addition;
					}
					
					$sql_a = "UPDATE " . BOOKIE_BETS_TABLE . "
						SET bet = $new_stake
						WHERE bet_id = " . $change_betid;
					if (!$db->sql_query($sql_a))
					{ 
						message_die(GENERAL_ERROR, 'Error in updating new stake in the DB', '', __LINE__, __FILE__, $sql_a); 
					}
					
					$changed = 'yes';
					$differential = ( $subtraction ) ? $subtraction * -1 : $addition;
					$com_change = intval($differential * ($board_config['bookie_commission'] / 100));
					
					// Remove the commission for this bet
					$sql_z = "SELECT admin_betid 
						FROM " . BOOKIE_BETS_TABLE . "
						WHERE bet_id = " . $change_betid;
					if ( !($result_z = $db->sql_query($sql_z)) ) 
					{ 
						message_die(GENERAL_ERROR, 'Error in deleting pending bet', '', __LINE__, __FILE__, $sql_z); 
					}
					$row_z = $db->sql_fetchrow($result_z);
					
					$this_change_betid = $row_z['admin_betid'];
					
					$sql_z = "SELECT multi 
						FROM " . BOOKIE_ADMIN_BETS_TABLE . "
						WHERE bet_id = $this_change_betid
						LIMIT 1";
					if ( !($result_z = $db->sql_query($sql_z)) ) 
					{ 
						message_die(GENERAL_ERROR, 'Error in deleting pending bet', '', __LINE__, __FILE__, $sql_z); 
					}
					$row_z = $db->sql_fetchrow($result_z);
					
					if ( $row_z['multi'] == -1 )
					{
						$sql_z = "UPDATE " . BOOKIE_BET_SETTER_TABLE . "
							SET commission = commission - $com_change
							WHERE bet_id = $this_change_betid
								AND setter != " . $userdata['user_id'];
						if ( !$db->sql_query($sql_z) ) 
						{ 
							message_die(GENERAL_ERROR, 'Error in deducting commission', '', __LINE__, __FILE__, $sql_z); 
						}
					}
					else
					{
						$sql_z = "UPDATE " . BOOKIE_BET_SETTER_TABLE . "
							SET commission = commission - $com_change
							WHERE bet_id = " . $row_z['multi'] . "
								AND setter != " . $userdata['user_id'];
						if ( !$db->sql_query($sql_z) ) 
						{ 
							message_die(GENERAL_ERROR, 'Error in deducting commission', '', __LINE__, __FILE__, $sql_z); 
						}
					}
				}
			}
			$db->sql_freeresult($result);

			if ( $changed == 'yes' )
			{				
				$message = $lang['bookie_stake_update_success'] . $bookie_redirect_pending;
				
				message_die(GENERAL_MESSAGE, $message);
			}
		}
	}
	
	$points_name = $board_config['points_name'];
			
	// Leaderboard Start
	// Build leaderboard
	// Update net positions in the database
	$sql = "SELECT * 
		FROM " . BOOKIE_STATS_TABLE . " 
		ORDER BY netpos";
	if ( !($result = $db->sql_query($sql)) ) 
	{ 
		message_die(GENERAL_ERROR, 'Error in getting leaderboard data', '', __LINE__, __FILE__, $sql); 
	}
	
	while ( $row = $db->sql_fetchrow($result) )
	{
		$lead_user_ID = $row['user_id'];
		$winnings = $row['total_win'];
		$losses = $row['total_lose'];
		$net_position = $winnings - $losses;
		
		$sql = "UPDATE " . BOOKIE_STATS_TABLE . " 
			SET netpos = '$net_position' 
			WHERE user_id = " . $lead_user_ID;
		if (!$db->sql_query($sql))
		{ 
			message_die(GENERAL_ERROR, 'Error updating net positions into the stats DB', '', __LINE__, __FILE__, $sql); 
		}
		
		$stat_count++;
	}
	$db->sql_freeresult($result);

	// Set the template array
	$bk_winnings = $bk_losses = $bk_totalbets = $bk_net_position = 0;
	$leader_limit = $board_config['bookie_leader'];
	
	$sql = "SELECT * 
		FROM " . BOOKIE_STATS_TABLE . " 
		ORDER BY netpos DESC";
	if ( !($result = $db->sql_query($sql)) ) 
	{ 
		message_die(GENERAL_ERROR, 'Error in getting leaderboard data', '', __LINE__, __FILE__, $sql); 
	}
	$board_leader_limit = $leader_limit;
	
	if ( $leader_expand )
	{
		$leader_limit = $stat_count;
	}
	
	$x = 1;
	while ( $row = $db->sql_fetchrow($result) )
	{
		$lead_user_ID = $row['user_id'];
		$winnings = $row['total_win'];
		$losses = $row['total_lose'];
		$net_position = $row['netpos'];
		$total_bets = $row['bets_placed'];
		$bk_winnings = $bk_winnings + $losses;
		$bk_losses = $bk_losses + $winnings;
		$bk_totalbets = $bk_totalbets + $total_bets;
		$bk_net_position = $bk_net_position - $net_position;	
			
		if ( $x < $leader_limit || $x == $leader_limit )
		{
			if ( $net_position < 0 )
			{
				$netpos_color = '#993300';
			}
			else
			{
				$netpos_color = '#000000';
			}
				
			// Get username from userid
			$sql_a = "SELECT username, user_level 
				FROM " . USERS_TABLE . " 
				WHERE user_id = " . $lead_user_ID;
			$result_a = $db->sql_query($sql_a);
			$row_a = $db->sql_fetchrow($result_a);
			$db->sql_freeresult($result_a);
	
			$bet_username = username_level_color($row_a['username'], $row_a['user_level'], $lead_user_ID);
			
			$row_class = ( !($x % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

			$template->assign_block_vars('leader', array(
				'ROW_CLASS' => $row_class,
				'POSITION' => $x,
				'USERNAME' => '<a href=' . append_sid("bookie_yourstats.$phpEx?viewmode=viewuser&" . POST_USERS_URL . "=$lead_user_ID") . ' class="gensmall">' . $bet_username . '</a>',
				'TOTALBETS' => number_format($total_bets),
				'TOTALWIN' => number_format($winnings),
				'TOTALLOSE' => number_format($losses),
				'NETPOS' => number_format($net_position),
				'NETPOS_COLOR' => $netpos_color)
			);
			$leader_disp_cnt++; 
		}
		$x++;
	}
	$db->sql_freeresult($result);

	if ( $x > 1 )
	{
		$template->assign_block_vars('switch_yes_stats', array());
	}
	
	if ( $bk_net_position < 0 )
	{
		$bk_netpos_color = '#993300';
	}
	else
	{
		$bk_netpos_color = '#000000';
	}
	
	if ( ($leader_disp_cnt) < $board_leader_limit )
	{
		$leader_info = sprintf($lang['bookie_leader_info'], ($leader_disp_cnt));
	}
	else if ( !$leader_expand && $stat_count > $board_leader_limit )
	{
		$leader_info = sprintf($lang['bookie_leader_info_expand'], ($leader_disp_cnt), $stat_count, '<a href="' . append_sid("bookies.$phpEx?leader=expand") . '">', '</a>');
	}
	else
	{
		$leader_info = sprintf($lang['bookie_leader_expanded'], ($leader_disp_cnt));
	}
	
	// End Leaderboard
	// get list of bets this user has put on
	$userID = $userdata['user_id'];
	$time = time();
	
	$sql = "SELECT * 
		FROM " . BOOKIE_BETS_TABLE . "
		WHERE user_id = $userID
			AND time > $time
			AND checked = 0
		ORDER BY time, meeting, selection";
	if ( !($result = $db->sql_query($sql)) ) 
	{ 
		message_die(GENERAL_ERROR, 'Error in building existing bets', '', __LINE__, __FILE__, $sql); 
	}
	
	$pen_x = 1;
	while ( $row = $db->sql_fetchrow($result) )
	{
		// show pending bets
		$pending_betid = $row['bet_id'];
		$pending_meeting = $row['meeting'];
		$pending_timestamp = $row['time'];
		$pending_selection = $row['selection'];
		$pending_ew = $row['each_way'];
		
		// convert fraction to decimal
		if ( $board_config['bookie_frac_or_dec'] )
		{
			$odds_decimal = number_format((($row['odds_1'] / $row['odds_2']) + 1), 2);
		}
	
		$pending_odds = ($odds_decimal) ? $odds_decimal : $row['odds_1'] . '/' . $row['odds_2'];
		$pending_stake = $row['bet'];
		
		$stake_box_pend = ( $board_config['bookie_edit_stake'] == 1 ) ? '<input class="post" type="text" maxlength="6" size="11" value="' . $pending_stake . '" name="' . $pending_betid . $pending_timestamp . '" />' : $pending_stake;
		
		// Convert date to viewable format
		$pending_bet_time = create_date($board_config['default_dateformat'], $pending_timestamp, $board_config['board_timezone']);
			
		if ( $last_pend_meeting == $pending_meeting && $last_pend_time == $pending_timestamp )
		{
			$pending_meeting = '<i>' . $pending_meeting . '</i>';
			$pending_bet_time = '<i>' . $pending_bet_time . '</i>';
		}
		else
		{
			$pending_meeting = '<b>' . $pending_meeting . '</b>';
			$pending_bet_time = '<b>' . $pending_bet_time . '</b>';
		}

		$delete_url = ( $board_config['bookie_edit_stake'] == 1 ) ? append_sid("bookies.$phpEx?&amp;mode=delete&amp;bet_id=$pending_betid") : '';
			
		$row_class = ( !($pen_x % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
			
		$template->assign_block_vars('pending', array(
			'ROW_CLASS' => $row_class,
			'MEETING' => $pending_meeting,
			'DATE' => $pending_bet_time,
			'SELECTION' => ( $pending_ew ) ? $pending_selection . '<br /><b>(' . $lang['bookie_slip_each_way'] . ')</b>' : $pending_selection,
			'ODDS' => $pending_odds,
			'STAKE_BOX_PEND' => $stake_box_pend,
			'DEL_IMG' => ($board_config['bookie_edit_stake'] == 1) ? '<a href="' . $delete_url . '"><img src="' . $images['icon_delpost'] . '" alt="' . $lang['bookie_delete_bet'] . '" title="' . $lang['bookie_delete_bet'] . '" /></a>' : $pen_x)
		);
		$pen_x++;

		$last_pend_meeting = $row['meeting'];
		$last_pend_time = $row['time'];
	}
	$db->sql_freeresult($result);

	if ($pen_x > 1)
	{
		$template->assign_block_vars('switch_bets_pending', array());
	}
}

$users_points = $userdata['user_points'];
		
// Get username from user_id
$user_name = $userdata['username'];
$time_now = time();

if ( $mode == 'placebet' )
{
	include_once($phpbb_root_path . 'includes/bookies_place_bet.'.$phpEx);
}

// Set template Vars
$template->assign_vars(array(
	'POINTS_NAME' => $points_name,
	'USER_POINTS' => $users_points,
	'BOOKIES_PLACE_BET' => $lang['bookie_place_bet'],
	'USER_NAME' => $user_name,
	'TIME_EXPLAIN' => $lang['bookie_slip_time_explain'],
	'BOOKIES_SLIP_DATE' => $lang['bookie_slip_date'],
	'BOOKIES_SLIP_MEETING' => $lang['bookie_slip_meeting'],
	'MEETING_EXPLAIN' => $lang['bookie_slip_meeting_explain'],
	'BOOKIES_SLIP_SELECTION' => $lang['bookie_slip_selection_odds'],
	'SELECTION_EXPLAIN' => $lang['bookie_slip_selection_explain'],
	'BOOKIES_SLIP_STAKE' => $lang['bookie_slip_stake'],
	'STAKE_EXPLAIN' => $lang['bookie_slip_stake_explain'],
	'YOUR_STATS' => $your_stats,
	'ALL_STATS' => $all_stats,
	'BK_WINNINGS' => number_format($bk_winnings),
	'BK_LOSSES' => number_format($bk_losses),
	'BK_TOTALBETS' => number_format($bk_totalbets),
	'BK_NET_POSITION' => number_format($bk_net_position),
	'BOOKIES_LEADER_HEADER' => $lang['bookie_leader_header'],
	'LEADER_USERNAME' => $lang['bookie_slip_username'],
	'LEADER_TOTALBETS' => $lang['bookie_total_bets'],
	'LEADER_TOTALWIN' => $lang['bookie_totalwin'],
	'LEADER_TOTALLOSE' => $lang['bookie_totalloss'],
	'LEADER_NETPOS' => $lang['bookie_netpos'],
	'BET_HEADER' => $lang['bookie_place_bet'],
	'BET_HEADER_NONE' => $lang['bookie_bet_header_none'],
	'BOOKIE_SLIP_ODDS' => $lang['bookie_odds'],
	'PENDING_HEADER' => $lang['bookie_pending_header'],
	'BOOKIES_CHANGE_STAKE' => $lang['bookie_change_stake'],
	'BOOKIES_SLIP_EACH_WAY' => $lang['bookie_slip_each_way'],
	'NEW_BET' => $new_bet_img,
	'PENDING_ANCHOR' => (!$old_rand) ? $random_num : $old_rand,
	'L_PLACE_BET' => $lang['bookie_placing_bet'],
	'BET_HEADER_NONE_TITLE' => $lang['bookie_bet_header_none_title'],
	'BET_INSTR' => ($cat_count != 1) ? $lang['bookie_bet_instr'] : $lang['bookie_bet_instr_default'],
	'POINTS_INFO' => sprintf($lang['bookie_points_onhand'], '<b>' . number_format($userdata['user_points']) . '&nbsp;' . $board_config['points_name'] . '</b>' ),
	'PENDING_INFO' => $lang['bookie_pending_info'],
	'LEADER_INFO' => $leader_info,
	'BOOKIE_VERSION' => $board_config['bookie_version'],
		
	'S_ACTION' => append_sid('bookies.'.$phpEx))
);

$template->pparse('body');

include($phpbb_root_path . 'includes/page_tail.'.$phpEx); 

?>