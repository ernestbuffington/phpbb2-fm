<?php
/** 
*
* @package includes
* @version $Id: bookies_delete_bet.php,v 2.0.6 2004/11/18 17:49:42 acydburn Exp $
* @copyright (c) 2004 Majorflam
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
	
if ( !defined('IN_PHPBB') )
{
	die('Hacking attempt');
}

$enter_bet_id = intval($HTTP_GET_VARS['bet_id']);
$delete_confirm = htmlspecialchars($HTTP_GET_VARS['delete_confirm']);
$delete_type = htmlspecialchars($HTTP_GET_VARS['type']);
$expand_id = intval($HTTP_GET_VARS['expand']);

if ( $delete_confirm )
{
	if ( $delete_type == 'one' )
	{
		// if it was a multi base bet, we now need a new base bet
		$sql = "SELECT multi 
			FROM " . BOOKIE_ADMIN_BETS_TABLE . "
			WHERE bet_id = '$enter_bet_id'";
		if ( !($result = $db->sql_query($sql)) ) 
		{ 
			message_die(GENERAL_ERROR, 'Error in deleting bet', '', __LINE__, __FILE__, $sql); 
		}
		$row = $db->sql_fetchrow($result);
		
		if ( $row['multi'] == -1 )
		{
			$sql = "SELECT bet_id 
				FROM " . BOOKIE_ADMIN_BETS_TABLE . "
				WHERE multi = '$enter_bet_id'
				LIMIT 1";
			if ( !($result = $db->sql_query($sql)) ) 
			{ 
				message_die(GENERAL_ERROR, 'Error in deleting bet', '', __LINE__, __FILE__, $sql); 
			}
			$row = $db->sql_fetchrow($result);

			$new_basebet_id = $row['bet_id'];

			if ( $new_basebet_id )
			{
				$sql = "UPDATE " . BOOKIE_ADMIN_BETS_TABLE . "
					SET multi = -1
					WHERE bet_id = '$new_basebet_id'";
				if ( !($result = $db->sql_query($sql)) ) 
				{ 
					message_die(GENERAL_ERROR, 'Error in deleting bet', '', __LINE__, __FILE__, $sql); 
				}
			
				$sql = " UPDATE " . BOOKIE_ADMIN_BETS_TABLE . "
					SET multi = '$new_basebet_id'
					WHERE multi = '$enter_bet_id'";
				if ( !($result = $db->sql_query($sql)) ) 
				{ 
						message_die(GENERAL_ERROR, 'Error in deleting bet', '', __LINE__, __FILE__, $sql); 
				}
					
				$sql = "UPDATE " . BOOKIE_BET_SETTER_TABLE . "
					SET bet_id = '$new_basebet_id'
					WHERE bet_id = '$enter_bet_id'";
				if ( !($result = $db->sql_query($sql)) ) 
				{ 
						message_die(GENERAL_ERROR, 'Error in deleting bet', '', __LINE__, __FILE__, $sql); 
				}
				$commission_multi = $new_basebet_id;
			}
			else
			{
				$commission_multi = $enter_bet_id;
				$delete_type='all';
			}
		}
		else
		{
			$commission_multi = $row['multi'];
		}
	}
	else
	{
		$commission_multi = $enter_bet_id;
	}
	
	//
	// do we need an array of bet id's?
	//
	if ( $delete_type == 'all' )
	{
		$betid_array = array();
		$sql = "SELECT bet_id 
			FROM " . BOOKIE_ADMIN_BETS_TABLE . "
			WHERE multi = $enter_bet_id";
		if ( !($result = $db->sql_query($sql)) ) 
		{ 
			message_die(GENERAL_ERROR, 'Error in deleting bet', '', __LINE__, __FILE__, $sql); 
		}

		while ( $row=$db->sql_fetchrow($result) )
		{
			$betid_array[] = $row['bet_id'];
		}
	}
		
	$sql_where = ($delete_type == 'one') ? ' WHERE bet_id = ' . $enter_bet_id : ' WHERE bet_id = ' . $enter_bet_id . ' OR multi = ' . $enter_bet_id;
		
	$sql = "DELETE FROM " . BOOKIE_ADMIN_BETS_TABLE . "$sql_where";
	if ( !($result = $db->sql_query($sql)) ) 
	{ 
		message_die(GENERAL_ERROR, 'Error in deleting bet', '', __LINE__, __FILE__, $sql); 
	}
	
	//
	// refund the bets for the base bet
	//
	$sql = "SELECT bet, user_id 
		FROM " . BOOKIE_BETS_TABLE . "
		WHERE admin_betid = '$enter_bet_id'";
	if ( !($result = $db->sql_query($sql)) ) 
	{ 
		message_die(GENERAL_ERROR, 'Error in deleting bet', '', __LINE__, __FILE__, $sql); 
	}
	
	while ( $row = $db->sql_fetchrow($result) )
	{
		$user_bet = $row['bet'];
		$user_deletebet = $row['user_id'];
		
		$sql_a = "UPDATE " . USERS_TABLE . "
			SET user_points = user_points + '$user_bet'
			WHERE user_id = '$user_deletebet'";
		if ( !($result_a = $db->sql_query($sql_a)) ) 
		{ 
			message_die(GENERAL_ERROR, 'Error in refunding bet', '', __LINE__, __FILE__, $sql_a); 
		}
			
		// remove any commission if neccessary
		$sql_a = "UPDATE " . BOOKIE_BET_SETTER_TABLE . "
			SET commission = commission - " . intval(($user_bet * ($board_config['bookie_commission'] / 100))) . "
			WHERE bet_id = $commission_multi
				AND setter != " . $user_deletebet;
		if ( !$db->sql_query($sql_a) ) 
		{ 
			message_die(GENERAL_ERROR, 'Error in deducting commission', '', __LINE__, __FILE__, $sql_a); 
		}
	}
	
	$sql_b = "DELETE FROM " . BOOKIE_BETS_TABLE . "
		WHERE admin_betid = '$enter_bet_id'";
	if ( !($result_b = $db->sql_query($sql_b)) ) 
	{ 
		message_die(GENERAL_ERROR, 'Error in deleting bet', '', __LINE__, __FILE__, $sql_b); 
	}
	
	//
	// refund for all bets???
	//
	if ( $delete_type == 'all' )
	{
		for ($i = 0; $i < sizeof($betid_array); $i++)
		{
			//
			// refund the bets for the other bets
			//
			$sql = "SELECT bet, user_id 
				FROM " . BOOKIE_BETS_TABLE . "
				WHERE admin_betid = " . $betid_array[$i];
			if ( !($result = $db->sql_query($sql)) ) 
			{ 
				message_die(GENERAL_ERROR, 'Error in deleting bet', '', __LINE__, __FILE__, $sql); 
			}
			
			while ( $row = $db->sql_fetchrow($result) )
			{
				$user_bet = $row['bet'];
				$user_deletebet = $row['user_id'];
				
				$sql_a = "UPDATE " . USERS_TABLE . "
					SET user_points = user_points + '$user_bet'
					WHERE user_id = '$user_deletebet'";
				if ( !($result_a = $db->sql_query($sql_a)) ) 
				{ 
					message_die(GENERAL_ERROR, 'Error in refunding bet', '', __LINE__, __FILE__, $sql_a); 
				}
					
				// remove any commission if neccessary
				$sql_a = "UPDATE " . BOOKIE_BET_SETTER_TABLE . "
					SET commission = commission - " . intval(($user_bet * ($board_config['bookie_commission'] / 100))) . "
					WHERE bet_id = $commission_multi
						AND setter != " . $user_deletebet;
				if ( !$db->sql_query($sql_a) ) 
				{ 
					message_die(GENERAL_ERROR, 'Error in deducting commission', '', __LINE__, __FILE__, $sql_a); 
				}
			}
	
			$sql_b = "DELETE FROM " . BOOKIE_BETS_TABLE . "
				WHERE admin_betid = " . $betid_array[$i];
			if ( !($result_b = $db->sql_query($sql_b)) ) 
			{ 
				message_die(GENERAL_ERROR, 'Error in deleting bet', '', __LINE__, __FILE__, $sql_b); 
			}
		}
	}
	
	//
	// let's count the remaining bets, and if there are none, we must remove the commission details
	//
}
else if ( $enter_bet_id )
{
	$bet_edit = $enter_bet_id;

	$template->set_filenames(array(
		'body' => 'admin/admin_bookie_setbet_delete.tpl')
	);

	//
	// get data for this bet
	//
	$sql_where = ($delete_type == 'one') ? ' WHERE bet_id = ' . $bet_edit : ' WHERE bet_id = ' . $bet_edit . ' OR multi = ' . $bet_edit;
	$sql = "SELECT * FROM " . BOOKIE_ADMIN_BETS_TABLE . "$sql_where";
	if ( !($result = $db->sql_query($sql)) ) 
	{ 
		message_die(GENERAL_ERROR, 'Error in building existing bets', '', __LINE__, __FILE__, $sql); 
	}
	
	$x = 1;
	while ( $bet_data = $db->sql_fetchrow($result) )
	{
		$this_bet_id = $bet_data['bet_id'];
		$bet_timestamp = $bet_data['bet_time'];
		$bet_meeting = $bet_data['bet_meeting'];
		$bet_selection = $bet_data['bet_selection'];
		$bet_odds1 = $bet_data['odds_1'];
		$bet_odds2 = $bet_data['odds_2'];
				
		// Convert date to viewable format
		$bet_time = create_date( $board_config['default_dateformat'], $bet_timestamp, $board_config['board_timezone'] );
			
		if ( $board_config['bookie_frac_or_dec'] )
		{
			// convert fraction to decimal
			$odds_decimal = number_format((($bet_odds1 / $bet_odds2) + 1), 2);
		}
		
		$row_color = ( !($x % 2) ) ? $theme['td_color1'] : $theme['td_color2']; 
		$row_class = ( !($x % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
		
		$template->assign_block_vars('editbet', array(
			'ROW_COLOR' => '#' . $row_color, 
			'ROW_CLASS' => $row_class,
			'BETID' => $this_bet_id,
			'CURRENT_TIME' => $bet_time,
			'MEETING' => $bet_meeting,
			'SELECTION' => $bet_selection,
			'ODDS' => ( $odds_decimal ) ? $odds_decimal : $bet_odds1 . '/' . $bet_odds2)
		); 
		$x++;
	}

	//
	// set variables
	//
	$template->assign_vars(array(
		'TIME' => $lang['bookie_set_time'],
		'MEETING' => $lang['bookie_process_meeting'],
		'SELECTION' => $lang['bookie_process_selection'],
		'ODDS' => $lang['bookie_set_odds'],
		'SUBMIT' => $lang['bookie_set_deletebuton'],
		'EDIT_HEADER' => $lang['bookie_delete_header'],
		'EDIT_HEADER_EXPLAIN' => $lang['bookie_delete_header_explain'],
		'URL' => append_sid("admin_bookies_setbet.$phpEx?&amp;mode=delete&amp;bet_id=$enter_bet_id&amp;delete_confirm=confirm&amp;type=$delete_type&amp;expand=$expand_id") . '#' . $expand_id,
		'CANCEL' => $lang['bookie_cancel'])
	);
	
	//
	// Parse
	//
	include('./page_header_admin.'.$phpEx);

	$template->pparse('body');

	include('./page_footer_admin.'.$phpEx);
	exit;
}

?>