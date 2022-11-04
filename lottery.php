<?php
/** 
*
* @package phpBB
* @version $Id: lottery.php,v 2.2.0 2006 zarath Exp $
* @copyright (c) 2004 Zarath Technologies
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);

//
// Include language file
//
$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_lottery.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_lottery.' . $phpEx); 

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_LOTTERY);
init_userprefs($userdata);
//
// End session management
//


//
// Register action variable!
//
if ( isset($HTTP_GET_VARS['action']) || isset($HTTP_POST_VARS['action']) ) 
{ 
	$action = ( isset($HTTP_POST_VARS['action']) ) ? htmlspecialchars($HTTP_POST_VARS['action']) : htmlspecialchars($HTTP_GET_VARS['action']); 
}
else 
{ 
	$action = ''; 
}


//
// Is lottery disabled?
//
if ( !$board_config['lottery_status'] ) 
{ 
	message_die(GENERAL_MESSAGE, $lang['lottery_disabled'] . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid('index.'.$phpEx) . '">', '</a>')); 
}


//
// Get user's current entry information
//
$sql = "SELECT *
	FROM " . LOTTERY_TABLE . "
	WHERE user_id = " . $userdata['user_id'];
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, sprintf($lang['lottery_error_selecting'], 'lottery'), '', __LINE__, __FILE__, $sql);
}
$sql_count = $db->sql_numrows($result);


//
// Check multiple tickets?
//
$tickbuy = ( ($board_config['lottery_ticktype'] == 0) && ($sql_count > 0) ) ? 0 : 1;


//
// Check if lottery should be drawn
//
$timeleft = ( $board_config['lottery_start'] + $board_config['lottery_length'] ) - time();

if ( $timeleft < 1 )
{
	$sql = "SELECT *
		FROM " . LOTTERY_TABLE;
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, sprintf($lang['lottery_error_selecting'], 'lottery'), '', __LINE__, __FILE__, $sql);
	}
	$sql_count = $db->sql_numrows($result);

	if ($sql_count > 0)
	{
		//
		// Calculate current prizepool
		//
		$pool = ($sql_count * $board_config['lottery_cost']) + $board_config['lottery_base'];

		//
		// Select winner
		//
		list($usec, $sec) = explode(' ', microtime());
		$seed = (float) $sec + ((float) $usec * 100000);

		srand($seed);

		$randnum = rand(1, $sql_count);
		$randnum--;
		for ($i = 0; $i < $sql_count; $i++) 
		{
			if (!( $row = $db->sql_fetchrow($result) ))
			{
				message_die(GENERAL_ERROR, sprintf($lang['lottery_error_selecting'], 'lottery'), '', __LINE__, __FILE__, $sql);
			}

			if ($i == $randnum)
			{
				//
				// Get winner's name and current items (incase items need to be added!)
				//
				$sql = "SELECT *
					FROM " . USERS_TABLE . "
					WHERE user_id = " . $row['user_id'];
				if ( !($result2 = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, sprintf($lang['lottery_error_selecting'], 'users'), '', __LINE__, __FILE__, $sql);
				}
				if (!( $row2 = $db->sql_fetchrow($result2) ))
				{
					message_die(GENERAL_ERROR, sprintf($lang['lottery_error_selecting'], 'users'), '', __LINE__, __FILE__, $sql);
				}
				$winnername = addslashes($row2['username']);
			}
		}

		//
		// Explode items array, loop over items array to make sure all item exist
		// Replace all RANDOM (lowercase) items with items from price to price [in store]
		//
		$item_array = explode(';', $board_config['lottery_win_items']);
		$add_items = array();
		for ($i = 0; $i < sizeof($item_array); $i++)
		{
			$item_array[$i] = trim($item_array[$i]);

			if ( strtolower($item_array[$i]) == 'random' )
			{
				$shop_sql = ( !empty($board_config['lottery_random_shop']) ) ? "AND shop = '" . addslashes($board_config['lottery_random_shop']) . "'" : '';
				$sql = "SELECT name
					FROM " . SHOPITEMS_TABLE . "
					WHERE cost > '" . $board_config['lottery_item_mcost'] . "'
						AND cost < '" . $board_config['lottery_item_xcost'] . "'
						" . $shop_sql . "
					ORDER BY RAND() 
					LIMIT 0, 1";
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, sprintf($lang['lottery_error_selecting'], 'shop items'), '', __LINE__, __FILE__, $sql);
				}
				$sql_count = $db->sql_numrows($result);

				if ( $sql_count > 0 )
				{
					if (!( $item_row = $db->sql_fetchrow($result) ))
					{
						message_die(GENERAL_ERROR, sprintf($lang['lottery_error_selecting'], 'shop items'), '', __LINE__, __FILE__, $sql);
					}
					$add_items[] = $item_row['name'];
				}
			}
			elseif ( !empty($item_array[$i]) )
			{
				$sql = "SELECT *
					FROM " . SHOPITEMS_TABLE . "
					WHERE name = '" . addslashes($item_array[$i]) . "'";
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, sprintf($lang['lottery_error_selecting'], 'shop items'), '', __LINE__, __FILE__, $sql);
				}
				$sql_count = $db->sql_numrows($result);

				if ( $sql_count > 0 )
				{
					$add_items[] = $item_row['name'];
				}
			}
		}
		
		if ( sizeof($add_items) > 0 )
		{
			$new_items = addslashes($row2['user_items'] . 'п' . implode('оп', $add_items) . 'о');

			//
			// Add up new total items & insert into database
			//
			$sql = "UPDATE " . USERS_TABLE . "
				SET user_items = '$new_items'
				WHERE user_id = " . $row2['user_id'];
			if ( !($db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, sprintf($lang['lottery_error_updating'], 'users'), '', __LINE__, __FILE__, $sql);
			}
		}

		//
		// Add up new total points & insert into database
		//
		$sql = "UPDATE " . USERS_TABLE . "
			SET user_points = user_points + $pool
			WHERE user_id = " . $row2['user_id'];
		if ( !($db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, sprintf($lang['lottery_error_updating'], 'users'), '', __LINE__, __FILE__, $sql);
		}

		$sql = "INSERT INTO " . LOTTERY_HISTORY_TABLE . " (user_id, amount, time)
			VALUES (" . $row2['user_id'] . ", '$pool', " . time() . ")";
		if ( !($db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, sprintf($lang['lottery_error_inserting'], 'lottery history'), '', __LINE__, __FILE__, $sql);
		}
		
		//
		// Send PM to winner
		//
		$sql = "UPDATE " . USERS_TABLE . " 
			SET user_new_privmsg = 1, user_last_privmsg = 9999999999
			WHERE user_id = " . $row2['user_id'];
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not update users table', '', __LINE__, __FILE__, $sql);
		}

		$winner_pm_subject = $lang['Greeting_Messaging'];
		$winner_pm_text = $lang['lottery_winner_pm'];
		$privmsgs_date = date('U');
		$sql = "INSERT INTO " . PRIVMSGS_TABLE . " (privmsgs_type, privmsgs_subject, privmsgs_from_userid, privmsgs_to_userid, privmsgs_date, privmsgs_enable_html, privmsgs_enable_bbcode, privmsgs_enable_smilies, privmsgs_attach_sig) 
			VALUES (0, '" . $winner_pm_subject . "', 2, " . $row2['user_id'] . ", " . $privmsgs_date . ", 0, 1, 1, 0)";
		if ( !$db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not insert private message sent info for winning user.', '', __LINE__, __FILE__, $sql);
		}
		$privmsg_sent_id = $db->sql_nextid();

		$sql = "INSERT INTO " . PRIVMSGS_TEXT_TABLE . " (privmsgs_text_id, privmsgs_text) 
			VALUES ($privmsg_sent_id, '" . str_replace("\'", "''", addslashes(sprintf($winner_pm_text, $pool, $board_config['points_name'], $board_config['lottery_name'], $board_config['sitename']))) . "')";
		if ( !$db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not insert private message sent text for winning user.', '', __LINE__, __FILE__, $sql);
		}
	}
	
	//
	// Begin reset of lottery
	//
	$sql = "DELETE FROM " . LOTTERY_TABLE;
	if ( !($db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, sprintf($lang['lottery_error_deleting'], 'lottery'), '', __LINE__, __FILE__, $sql);
	}
	
	if ($board_config['lottery_reset'])
	{ 
		$sql = "UPDATE " . CONFIG_TABLE . "
			SET config_value = " . time() . "
			WHERE config_name = 'lottery_start'";
		if ( !($db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, sprintf($lang['lottery_error_updating'], 'config'), '', __LINE__, __FILE__, $sql);
		}

		// Remove cache file
		@unlink($phpbb_root_path . 'cache/config_board.'.$phpEx);
	}
	else
	{
		$sql = "UPDATE " . CONFIG_TABLE . "
			SET config_value = 0
			WHERE config_name = 'lottery_status'";
		if ( !($db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, sprintf($lang['lottery_error_updating'], 'config'), '', __LINE__, __FILE__, $sql);
		}
		
		$sql = "UPDATE " . CONFIG_TABLE . "
			SET config_value = 0
			WHERE config_name = 'lottery_start'";
		if ( !($db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, sprintf($lang['lottery_error_updating'], 'config'), '', __LINE__, __FILE__, $sql);
		}

		// Remove cache file
		@unlink($phpbb_root_path . 'cache/config_board.'.$phpEx);
	}
	
	header('Location: lottery.'.$phpEx);
	exit;
}


//
// Default lottery page
//
if ( empty($action) )
{
	//
	// Begin action checking and toggling!
	//
	if ( ($tickbuy) || ($board_config['lottery_history']) )
	{
		if ( $tickbuy )
		{
			//
			// Begin switch to allow user to buy a ticket!
			//
			if ( $board_config['lottery_mb'] )
			{
				$template->assign_block_vars('switch_tickets_multi', array());
			}
			else
			{
				$template->assign_block_vars('switch_tickets_single', array());
			}
		}
		
		if ( $board_config['lottery_history'] )
		{
			$template->assign_block_vars('switch_view_history', array());

			$sql = "SELECT *
				FROM " . LOTTERY_HISTORY_TABLE . "
				WHERE user_id = '{$userdata['user_id']}'";
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, sprintf($lang['lottery_error_selecting'], 'lottery'), '', __LINE__, __FILE__, $sql);
			}
			if ( $db->sql_numrows($result) > 0 )
			{
				$template->assign_block_vars('switch_view_personal', array());
			}
		}
		
		$template->assign_block_vars('switch_are_actions', array());
	}

	//
	// Begin switch to allow full display (full pot & current entries)!
	//
	if ( $board_config['lottery_show_entries'] )
	{
		$template->assign_block_vars('switch_full_display', array());

		$sql = "SELECT *
			FROM " . LOTTERY_TABLE;
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, sprintf($lang['lottery_error_selecting'], 'lottery'), '', __LINE__, __FILE__, $sql);
		}
		$total_entries = $db->sql_numrows($result);
		
		$total_pool = ($board_config['lottery_cost'] * $total_entries) + $board_config['lottery_base'];
	}

	//
	// Begin checks, switch and rearrangements of items in lottery
	//
	if ( ($board_config['lottery_items']) && (strlen($board_config['lottery_win_items']) > 3) )
	{
		$lottery_items = str_replace(';', ', ', $board_config['lottery_win_items']);
		
		$template->assign_block_vars('switch_items', array());
	}

	//
	// Grab last winner from lottery -- ORDERED BY TIME
	//
	$sql = "SELECT t1.*, t2.username, t2.user_level
		FROM " . LOTTERY_HISTORY_TABLE . " as t1, " . USERS_TABLE . " as t2
		WHERE t2.user_id = t1.user_id
		ORDER BY time DESC";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, sprintf($lang['lottery_error_selecting'], 'lottery history'), '', __LINE__, __FILE__, $sql);
	}

	if ( $db->sql_numrows($result) > 0 )
	{
		if (!( $row = $db->sql_fetchrow($result) ))
		{
			message_die(GENERAL_ERROR, sprintf($lang['lottery_error_selecting'], 'lottery'), '', __LINE__, __FILE__, $sql);
		}
		
		$template->assign_block_vars('switch_last_winner', array(
			'WINNER_NAME' => username_level_color($row['username'], $row['user_level'], $row['user_id']),
			
			'U_VIEWPROFILE' => append_sid('profile.'.$phpEx.'?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $row['user_id']))
		);
	}

	$page_title = $board_config['lottery_name'];

	$template->set_filenames(array(
		'body' => 'lottery_body.tpl')
	);	
	make_jumpbox('viewforum.'.$phpEx);

	$template->assign_vars(array(
		'S_MODE_ACTION' => append_sid('lottery.'.$phpEx),

		'TICKETS_OWNED' => $sql_count,
		'L_PRIZE_BASE' => $board_config['lottery_base'],
		'L_TICKET_COST' => $board_config['lottery_cost'],
		'L_TOTAL_PRIZE' => $total_pool,
		'L_CURRENT_ENTRIES' => $total_entries,
		'L_ITEM_PRIZE' => $lottery_items,
		'L_LOTTOQUOTE' => ( $total_entries > 0 ) ? round($sql_count / $total_entries * 100, 1) : 0,
		'L_WIN_QUOTA' => $lang['lottery_win_quota'],

		'L_CURRENCY' => $board_config['points_name'],
		'L_DURATION' => duration($timeleft),
		'L_NAME' => $board_config['lottery_name'],
		'L_INFO_TITLE' => $board_config['lottery_name'] . ' ' . $lang['lottery_information'],
		'L_ACTIONS_TITLE' => $lang['lottery_actions'],
		'L_TICKET_OWNED' => $lang['lottery_tickets_owned'],
		'L_TICKETS_COST' => $lang['lottery_ticket_cost'],
		'L_BASE_POOL' => $lang['lottery_base_pool'],
		'L_CURRENT_POOL' => $lang['lottery_current_entries'],
		'L_TOTAL_POOL' => $lang['lottery_total_pool'],
		'L_ITEM_DRAW' => $lang['lottery_item_draw'],
		'L_TIME_DRAW' => $lang['lottery_time_draw'],
		'L_LAST_WINNER' => $lang['lottery_last_winner'],

		'I_BUY_TICKET' => $lang['lottery_buy_ticket'],
		'I_BUY_TICKETS' => $lang['lottery_buy_tickets'],
		'I_VIEW_HISTORY' => $lang['lottery_view_history'],
		'I_VIEW_PHISTORY' => $lang['lottery_view_phistory'])
	);
}
elseif ($action == 'options')
{
	if ( !$userdata['session_logged_in'] )
	{
		$redirect = "lottery.$phpEx&action=options";
		header('Location: ' . append_sid("login.$phpEx?redirect=$redirect", true));
		exit;
	}

	//
	// Register the globals for both actions [allows checks]
	//
	if ( isset($HTTP_GET_VARS['amount']) || isset($HTTP_POST_VARS['amount']) ) 
	{ 
		$amount = ( isset($HTTP_POST_VARS['amount']) ) ? intval($HTTP_POST_VARS['amount']) : intval($HTTP_GET_VARS['amount']); 
	}
	else 
	{ 
		$amount = ''; 
	}
	
	if ( isset($HTTP_GET_VARS['view_history']) || isset($HTTP_POST_VARS['view_history']) ) 
	{ 
		$view_history = ( isset($HTTP_POST_VARS['view_history']) ) ? htmlspecialchars($HTTP_POST_VARS['view_history']) : htmlspecialchars($HTTP_GET_VARS['view_history']); 
	}
	else 
	{ 
		$view_history = ''; 
	}
	
	if ( isset($HTTP_GET_VARS['view_personal']) || isset($HTTP_POST_VARS['view_personal']) ) 
	{ 
		$view_personal = ( isset($HTTP_POST_VARS['view_personal']) ) ? htmlspecialchars($HTTP_POST_VARS['view_personal']) : htmlspecialchars($HTTP_GET_VARS['view_personal']); 
	}
	else 
	{ 
		$view_personal = ''; 
	}
	
	if ( isset($HTTP_GET_VARS['start']) || isset($HTTP_POST_VARS['start']) ) 
	{ 
		$start = ( isset($HTTP_POST_VARS['start']) ) ? intval($HTTP_POST_VARS['start']) : intval($HTTP_GET_VARS['start']); 
	}
	else 
	{ 
		$start = 0; 
	}

	if ( isset($HTTP_GET_VARS['buy_ticket']) || isset($HTTP_POST_VARS['buy_ticket']) ) 
	{ 
		$buy_ticket = ( isset($HTTP_POST_VARS['buy_ticket']) ) ? $HTTP_POST_VARS['buy_ticket'] : $HTTP_GET_VARS['buy_ticket']; 
	}
	else 
	{ 
		$buy_ticket = ''; 
	}

	if ( isset($HTTP_GET_VARS['buy_tickets']) || isset($HTTP_POST_VARS['buy_tickets']) ) 	
	{	 
		$buy_tickets = ( isset($HTTP_POST_VARS['buy_tickets']) ) ? $HTTP_POST_VARS['buy_tickets'] : $HTTP_GET_VARS['buy_tickets']; 
	}
	else 
	{ 
		$buy_tickets = ''; 
	}

	if ( !empty($buy_ticket) || !empty($buy_tickets) )
	{
		//
		// Make sure they can buy a ticket & have enough gil!
		//
		if ( !($tickbuy) )
		{
			$message = $lang['lottery_too_many_tickets'] . "<br /><br />" . sprintf($lang['lottery_click_return'], "<a href=\"" . append_sid("lottery.$phpEx") . "\">", "</a>");
	
			message_die(GENERAL_MESSAGE, $message);
		}

		$amount = ( ($amount < 1) || ($amount > 9999) ) ? 1 : $amount;

		//
		// Make sure if buying MULTIPLE tickets, they are allowed to -- and it doesn't exceed the max
		//
		if ( !($board_config['lottery_ticktype']) || !($board_config['lottery_mb']) )
		{
			$amount = 1;
		}
		else
		{
			if ($amount > $board_config['lottery_mb_amount']) 
			{ 
				$amount = $board_config['lottery_mb_amount']; 
			}
		}

		//
		// Check if total entries in the prize > $mb_amount
		//
		$sql = "SELECT COUNT(*) AS total_tickets
			FROM " . LOTTERY_TABLE . "
			WHERE user_id = " . $userdata['user_id'];
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, sprintf($lang['lottery_error_selecting'], 'lottery'), '', __LINE__, __FILE__, $sql);
		}
		if ( !($row = $db->sql_fetchrow($result)) )
		{
			message_die(GENERAL_ERROR, sprintf($lang['lottery_error_selecting'], 'lottery'), '', __LINE__, __FILE__, $sql);
		}
		if ( ( $row['total_tickets'] + $amount ) > $board_config['lottery_mb_amount'] )
		{
			$amount = ( $board_config['lottery_mb_amount'] - $row['total_tickets'] );
			if ( $amount < 1 ) 
			{
				message_die(GENERAL_MESSAGE, $lang['lottery_too_many_tickets']); 
			}
		}

		$ticket_cost = $board_config['lottery_cost'] * $amount;

		//
		// Begin checks on multiple currencies, if they exist!
		//
		if ( !($cash_done) )		
		{
			if ( ($userdata['user_points'] - $ticket_cost) < 0 )
			{
				$msg = ( $amount == 1 ) ? $lang['lottery_purchased_ticket'] : sprintf($lang['lottery_purchased_tickets'], $amount);
			
				$message = $lang['lottery_purchased_ne'] . $board_config['points_name'] . $msg . "<br /><br />" . sprintf($lang['lottery_click_return'], "<a href=\"" . append_sid("lottery.$phpEx") . "\">", "</a>");
	
				message_die(GENERAL_MESSAGE, $message);
			}

			$sql = "UPDATE " . USERS_TABLE . "
				SET user_points = user_points - $ticket_cost
				WHERE user_id = " . $userdata['user_id'];
			if ( !($db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, sprintf($lang['lottery_error_updating'], 'users'), '', __LINE__, __FILE__, $sql);
			}
		}

		$sql = "INSERT INTO " . LOTTERY_TABLE . " (user_id)
			VALUES (" . $userdata['user_id'] . ")";
		for ($i = 0; $i < $amount; $i++)
		{
			if ( !($db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, sprintf($lang['lottery_error_inserting'], 'lottery'), '', __LINE__, __FILE__, $sql);
			}
		}
		$msg = ( $amount < 2 ) ? sprintf($lang['lottery_ticket_bought'], $board_config['lottery_name']) : sprintf($lang['lottery_tickets_bought'], $amount, $board_config['lottery_name']);
		
		$message = $msg . "<br /><br />" . sprintf($lang['lottery_click_return'], "<a href=\"" . append_sid("lottery.$phpEx") . "\">", "</a>");
	
		message_die(GENERAL_MESSAGE, $message);
	}
	elseif ( !empty($view_history) || ( !empty($view_personal) ) )
	{
		//
		// History Layers, these will be fairly basic, just a straight check of SQL and output to a template.
		// Two choices, self history (will probably be empty most of them...) and full history. Not sure how
		// I'm going to implement the second, at the moment it's just view all history. :P
		//
			
		$template->set_filenames(array(
			'body' => 'lottery_history_body.tpl')
		);
		make_jumpbox('viewforum.'.$phpEx);

		//
		// Make sure the lottery history is enabled! [or at least viewable]
		//
		if ( !($board_config['lottery_history']) ) 
		{ 
			message_die(GENERAL_MESSAGE, $lang['lottery_history_disabled']); 
		}

		if ( !empty($view_personal) )
		{
			$sql = "SELECT t1.*, t2.username, t2.user_level
				FROM " . LOTTERY_HISTORY_TABLE . " as t1, " . USERS_TABLE . " as t2
				WHERE t1.user_id = " . $userdata['user_id'] . "
					AND t2.user_id = t1.user_id
				ORDER BY time DESC
				LIMIT $start, " . $board_config['topics_per_page'];

			// Pagination SQL Query...
			$page_sql = "SELECT COUNT(*) AS total
				FROM " . LOTTERY_HISTORY_TABLE . "
				WHERE user_id = " . $userdata['user_id'];
		}
		elseif ( !empty($view_history) )
		{
			$sql = "SELECT t1.*, t2.username, t2.user_level
				FROM " . LOTTERY_HISTORY_TABLE . " as t1, " . USERS_TABLE . " as t2
				WHERE t2.user_id = t1.user_id
				ORDER BY time DESC
				LIMIT $start, " . $board_config['topics_per_page'];

			// Pagination SQL Query...
			$page_sql = "SELECT COUNT(*) AS total
				FROM " . LOTTERY_HISTORY_TABLE;
		}
		else 
		{ 
			message_die(GENERAL_MESSAGE, $lang['lottery_no_history_type']); 
		}

		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, sprintf($lang['lottery_error_selecting'], 'lottery history'), '', __LINE__, __FILE__, $sql);
		}
		$sql_count = $db->sql_numrows($result);

		if ( $sql_count < 1 )
		{
			//
			// Code to toggle no history!
			//
			$template->assign_block_vars('switch_no_history', array(
				'MESSAGE' => $lang['lottery_no_history'])
			);
		}
		else
		{
			//
			// Begin of loops over history to directly output it on the history page! :)
			//
			for ($i = 0; $i < $sql_count; $i++)
			{
				if (!( $row = $db->sql_fetchrow($result) ))
				{
					message_die(GENERAL_ERROR, sprintf($lang['lottery_error_selecting'], 'lottery history'), '', __LINE__, __FILE__, $sql);
				}

				$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

				$template->assign_block_vars('listrow', array(
					'ROW_CLASS' => $row_class,
					'HISTORY_NUM' => $i + 1 + $start,
                    'HISTORY_WINNER' => username_level_color($row['username'], $row['user_level'], $row['user_id']),
					'HISTORY_AMOUNT' => $row['amount'],
     				'HISTORY_CURRENCY' => $board_config['points_name'],
					'HISTORY_TIME' => create_date($board_config['default_dateformat'], $row['time'], $board_config['board_timezone']),
					
					'U_VIEWPROFILE' => append_sid('profile.'.$phpEx.'?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $row['user_id']))
				);
			}

			$template->assign_block_vars('switch_title_info', array());
		}

		//
		// Begin pagination based on topics per page... I REALLY hate pagination, but it's the only way to stop
		// Massive build ups on a single page.
		//
		if ( !($result = $db->sql_query($page_sql)) )
		{
			message_die(GENERAL_ERROR, sprintf($lang['lottery_error_selecting'], 'lottery history'), '', __LINE__, __FILE__, $sql);
		}
		$sql_count = $db->sql_numrows($result);

		if ( ($total = $db->sql_fetchrow($result)) && ($sql_count > 0) )
		{
			$total_history = $total['total'];

			if ( $total_history > $board_config['topics_per_page'] )
			{
				$pagination = generate_pagination("lottery.$phpEx?action=options&amp;view_history=$view_history&amp;view_personal=$view_personal", $total_history, $board_config['topics_per_page'], $start). '&nbsp;';
			}
			else
			{
				$pagination = '&nbsp;';
			}
		}

		//
		// Finished pagination, now wrapping up the page and displaying it...
		// 
		$page_title = $board_config['lottery_name'];
		$next_location = ' -> <a href="' . append_sid("lottery.$phpEx") . '" class="nav">' . $page_title . '</a>';

		$template->assign_vars(array(
			'L_HISTORY' => $lang['lottery_current_history'],
			'L_ID' => $lang['lottery_ID'],
			'L_WINNER' => $lang['lottery_winner'],
			'L_AMOUNT_WON' => $lang['lottery_amount_won'],
			'L_TIME_WON' => $lang['lottery_time_won'],

			'LOCATION' => $next_location,
			'PAGINATION' => $pagination,
			'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / $board_config['topics_per_page'] ) + 1 ), ceil( $total_history / $board_config['topics_per_page'] )), 
			'L_GOTO_PAGE' => $lang['Goto_page'])
		);
		
		$template->assign_block_vars('', array());
	}
	else
	{
		header('Location: lottery.'.$phpEx);
	}
}
else 
{
	message_die(GENERAL_MESSAGE, $lang['lottery_invalid_command']);
}

//
// Start output of page
//
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

//
// Force password update
//
if ($board_config['password_update_days'])
{
	include($phpbb_root_path . 'includes/update_password.'.$phpEx);
}

$template->pparse('body');

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>