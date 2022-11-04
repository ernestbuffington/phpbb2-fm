<?php

if ( !defined('IN_PHPBB') )
{
	die('Hacking attempt');
}

$template->set_filenames(array( 
	'body' => 'bookies_place_bet.tpl')
);

//
// set some template switches and vars acording to configuration
//
$cat_view = intval($HTTP_GET_VARS['cat_view']);
$mode_type = htmlspecialchars($HTTP_GET_VARS['type']);
$points_name = $board_config['points_name'];
$condition = 'no';

if ( $board_config['bookie_user_bets'] )
{
	if ( $board_config['bookie_user_bets'] == 2 )
	{
		$sql = "SELECT bet_id 
			FROM " . BOOKIE_ADMIN_BETS_TABLE . "
			WHERE bet_time > " . time() . "
				AND multi != -5
			LIMIT 1";
		if ( !($result = $db->sql_query($sql)) ) 
		{ 
			message_die(GENERAL_ERROR, 'Error in checking configuration', '', __LINE__, __FILE__, $sql); 
		}
		$row = $db->sql_fetchrow($result);

		if ( !$row )
		{
			$condition = 'yes';
		}
		else
		{
			$condition = 'no';
		}
	}
	else
	{
		$condition = 'yes';
	}
	
	if ( $condition == 'yes' && !$mode_type )
	{
		$template->assign_vars(array(
			'USER_BET_INSTR' => sprintf($lang['bookie_user_bet_instr'], '<a href="' . append_sid("bookies.$phpEx?mode=placebet&type=user") . '">', '</a>'),
			'USER_BET_INSTR_DEFAULT' => sprintf($lang['bookie_user_bet_instr_def'], '<a href="' . append_sid("bookies.$phpEx?mode=placebet&type=user") . '">', '</a>'))
		);
	}
	
	if ( $condition == 'yes' && $mode_type == 'user' )
	{
		$template->assign_block_vars('switch_user_bets_on', array());
		
		//
		// OK, we need to grab the real board timezone and compare with the users.
		// if they don't match, we need to let them know
		//
		$sql = "SELECT config_value 
			FROM " . CONFIG_TABLE . "
			WHERE config_name = 'board_timezone'";
		if ( !($result = $db->sql_query($sql)) ) 
		{ 
			message_die(GENERAL_ERROR, 'Could not obtain board timezone.', '', __LINE__, __FILE__, $sql); 
		}

		$row = $db->sql_fetchrow($result);
		
		$board_timezone = intval($row['config_value']);
			
		if ( $userdata['user_timezone'] != $board_timezone )
		{
			$template->assign_block_vars('switch_user_bets_on.timezone_warning', array());
			
			$userdata['user_timezone']=intval($userdata['user_timezone']);
			$board_timezone = intval($board_timezone);
			
			if ( $userdata['user_timezone'] > 0 )
			{
				$user_timezone_disp = '+' . $userdata['user_timezone'];
			}
			else if ( $userdata['user_timezone'] == 0 )
			{
				$user_timezone_disp = '';
			}
			else
			{
				$user_timezone_disp = $userdata['user_timezone'];
			}
				
			if ( $board_timezone > 0 )
			{
				$board_timezone_disp = '+' . $board_timezone;
			}
			else if ( $board_timezone == 0 )
			{
				$board_timezone_disp = '';
			}
			else
			{
				$board_timezone_disp = $board_timezone;
			}
				
			$time_diff = intval($board_timezone-$userdata['user_timezone']);
			
			if ( $time_diff < 0 )
			{
				$direction = $lang['bookie_timezone_behind'];
				$time_diff = $time_diff * -1;
			}
			else
			{
				$direction = $lang['bookie_timezone_infront'];
			}
				
			$template->assign_vars(array(
				'WARNING' => sprintf($lang['bookie_timezone_warning'], $user_timezone_disp, $board_timezone_disp, $time_diff, $direction))
			);
		}

		//
		// Drop boxes
		//
		// hours
		$hour_box .= '<option value="" selected="selected">hh';
		for ($i = 0; $i < 24; $i++)
		{
			$i_view = ($i < 10) ? '0' . $i : $i;
			$hour_box .= '<option value="' . $i . '">' . $i_view;
		}
		$hour_box .= '</select>';
			
		// minutes
		$min_box .= '<option value="" selected="selected">mm';
		for ($i = 0; $i < 60; $i++)
		{
			$i_view = ($i < 10) ? '0' . $i : $i;
			$min_box .= '<option value="' . $i . '">' . $i_view;
		}
		$min_box .= '</select>';
			
		// Days
		$day_box .= '<option value="" selected="selected">DD';
		for ($i = 1; $i < 32; $i++)
		{
			$i_view = ($i < 10) ? '0' . $i : $i;
			$day_box .= '<option value="' . $i . '">' . $i_view;
		}
		$day_box .= '</select>';
			
		// Months
		$month_box .= '<option value="" selected="selected">MM';
		for ($i = 1; $i < 13; $i++)
		{
			$i_view = ($i < 10) ? '0' . $i : $i;
			$month_box .= '<option value="' . $i . '">' . $i_view;
		}
		$month_box .= '</select>';
		
		// Years
		$year_box .= '<option value="" selected="selected">YYYY';
		for ($i = 2006; $i < 2011; $i++)
		{
			$year_box .= '<option value="' . $i . '">' . $i;
		}
		$year_box .= '</select>';
		// end drop boxes
			
		$template->assign_vars(array(
			'BETHOUR_BOX' => $hour_box,
			'BETMINUTE_BOX' => $min_box,
			'DAY_BOX' => $day_box,
			'MONTH_BOX' => $month_box,
			'YEAR_BOX' => $year_box,
			'ENTER_DETAILS' => $lang['bookie_user_placebet'],
			'TIME_MEETING' => $lang['bookie_user_time_meeting'],
			'TIME_MEETING_EXPLAIN' => $lang['bookie_user_time_meeting_exp'],
			'MEETING' => $lang['bookie_slip_meeting'],
			'SELECTION' => $lang['bookie_user_selection'],
			'SELECTION_EXPLAIN' => $lang['bookie_user_selection_exp'],
			'EACHWAY_BET' => $lang['bookie_user_ew_bet'],
			'EACHWAY_BET_EXP' => $lang['bookie_user_ew_bet_exp'],
			'L_YES' => $lang['Yes'],
			'L_NO' => $lang['No'],
			'STAKE' => $lang['bookie_slip_stake'],
			'ON_HAND' => sprintf($lang['bookie_points_onhand'], number_format($userdata['user_points']) ),
			'POINTS_NAME' => $points_name)
		);
		
		//
		// So, do we have a user set bet then?
		//
		if ( isset($HTTP_POST_VARS['user_placebet']) )
		{
			//
			// make sure we have all fields
			//
			$redirect = '<meta http-equiv="refresh" content="2;url=' . append_sid("bookies.$phpEx?mode=placebet&amp;type=user") . '">';
			$bet_time_placed = intval($HTTP_POST_VARS['bet_hour']) . ':' . intval($HTTP_POST_VARS['bet_minute']);
			$bet_date_placed = intval($HTTP_POST_VARS['bet_day']) . '-' . intval($HTTP_POST_VARS['bet_month']) . '-' . intval($HTTP_POST_VARS['bet_year']);//$HTTP_POST_VARS['bet_date'];
			$bet_meeting_placed = htmlspecialchars($HTTP_POST_VARS['bet_meeting']);
			$bet_selection_placed = htmlspecialchars($HTTP_POST_VARS['bet_selection']);
			$eachway = intval($HTTP_POST_VARS['eachwaybet']);
			$user_bet_stake = intval($HTTP_POST_VARS['bet_stake']);
			
			if ( $user_bet_stake < $board_config['bookie_min_bet'] )
			{
				$message = sprintf($lang['bookie_stake_unsuccess_min_user'],  number_format($board_config['bookie_min_bet']), $board_config['points_name']) . $redirect;
				message_die(GENERAL_MESSAGE, $message);
			}
			if ( $user_bet_stake > $board_config['bookie_max_bet'] && intval($board_config['bookie_max_bet']) > 0 )
			{
				$message = sprintf($lang['bookie_stake_unsuccess_max_user'],  number_format($board_config['bookie_max_bet']), $board_config['points_name']) . $redirect;
				message_die(GENERAL_MESSAGE, $message);
			}
			if ( empty($HTTP_POST_VARS['bet_day']) || empty($HTTP_POST_VARS['bet_month']) || empty($HTTP_POST_VARS['bet_year']) )
			{
				$message = $lang['bookies_notall_fileds'] . $redirect;
				message_die(GENERAL_MESSAGE, $message);
			}
			if ( !$bet_meeting_placed || !$bet_selection_placed || !$user_bet_stake )
			{
				$message = $lang['bookies_notall_fileds'] . $redirect;
				message_die(GENERAL_MESSAGE, $message);
			}
				
			$getdate = explode('-', $bet_date_placed);
			$gettime = explode(':', $bet_time_placed);
			$timestamp = gmmktime(intval($gettime[0]), intval($gettime[1]), 0, intval($getdate[1]), intval($getdate[0]), intval($getdate[2])); 
			$bet_timestamp_placed = $timestamp - ($board_config['board_timezone'] * 3600);
			// make sure the time is valid
			if ( $bet_timestamp_placed < time() )
			{
				$message = $lang['bookies_invalid_date'] . $redirect;
				message_die(GENERAL_MESSAGE, $message);
			}
			
			//
			// OK we got all data, so lets' do it.... first we create an admin bet with a multi of -5
			// then we create the users bet with an admin_betid of the new admin bet.
			//
			if ( $user_bet_stake > $userdata['user_points'] )
			{
				$message = $lang['bookie_no_cash'] . $redirect;
				message_die(GENERAL_MESSAGE, $message);
			}
			else
			{
				$sql = "UPDATE " . USERS_TABLE . "
					SET user_points = user_points - $user_bet_stake
					WHERE user_id = " . $userdata['user_id'];
				if ( !$db->sql_query($sql) ) 
				{ 
					message_die(GENERAL_ERROR, 'Error deducting bet stake', '', __LINE__, __FILE__, $sql); 
				}
			}
			
			$sql = "INSERT INTO " . BOOKIE_ADMIN_BETS_TABLE . " (bet_time, bet_selection, bet_meeting, odds_1, odds_2, multi, each_way)
				VALUES ($bet_timestamp_placed, '" . str_replace("\'", "''", $bet_selection_placed) . "', '" . str_replace("\'", "''", $bet_meeting_placed) . "', 1, 1, -5, '$eachway')";
			if ( !$db->sql_query($sql) ) 
			{ 
				message_die(GENERAL_ERROR, 'Error in entering new bet', '', __LINE__, __FILE__, $sql); 
			}
			
			//
			// Now retrieve that admin betid
			//
			$sql = "SELECT bet_id 
				FROM " . BOOKIE_ADMIN_BETS_TABLE . "
				WHERE bet_time = $bet_timestamp_placed
					AND bet_selection = '" . str_replace("\'", "''", $bet_selection_placed) . "'
					AND bet_meeting = '" . str_replace("\'", "''", $bet_meeting_placed) . "'
					AND odds_1 = 1
					AND odds_2 = 1
					AND multi = -5";
			if ( !($result = $db->sql_query($sql)) ) 
			{ 
				message_die(GENERAL_ERROR, 'Error in entering new bet', '', __LINE__, __FILE__, $sql); 
			}
			$row = $db->sql_fetchrow($result);
			
			$new_betid = $row['bet_id'];
			
			//
			// now insert the users bet
			//
			$sql = "INSERT INTO " . BOOKIE_BETS_TABLE . " (user_id, time, meeting, selection, bet, odds_1, odds_2, admin_betid, each_way)
				VALUES (" . $userdata['user_id'] . ", $bet_timestamp_placed, '" . str_replace("\'", "''", $bet_meeting_placed) . "', '" . str_replace("\'", "''", $bet_selection_placed) . "', $user_bet_stake, 1, 1, $new_betid, $eachway)";
			if ( !$db->sql_query($sql) ) 
			{ 
				message_die(GENERAL_ERROR, 'Error in entering new bet', '', __LINE__, __FILE__, $sql); 
			}

			//
			// Success!
			//
			$message = $lang['bookie_bet_success'] . $bookie_redirect;

			message_die(GENERAL_MESSAGE, $message);
		}
	}
}

if ( $board_config['bookie_eachway'] && $condition == 'yes' && $mode_type == 'user' )
{
	$template->assign_block_vars('switch_user_bets_on.switch_eachway_allowed', array());
}
	
// OK, lets retrieve the base bets for allocating categories
$sql = "SELECT * 
	FROM " . BOOKIE_ADMIN_BETS_TABLE . "
	WHERE multi = -1
		AND bet_time > $time_now
		AND checked = 0
	ORDER BY starbet DESC, bet_time DESC, bet_meeting ASC";
if ( !($result = $db->sql_query($sql)) ) 
{ 
	message_die(GENERAL_ERROR, 'Error in building base bets', '', __LINE__, __FILE__, $sql); 
}

$base_cat = array();
while ( $row = $db->sql_fetchrow($result) )
{
	$base_cat[] = $row['bet_cat'];
}
	
if ( sizeof($base_cat) == 0  )
{
	$template->assign_block_vars('switch_no_bets', array());
}
if ( sizeof($base_cat) > 0  )
{
	$template->assign_block_vars('switch_yes_bets', array());
}

$cat_counting = array();

for ($c = 0; $c < sizeof($base_cat); $c++)
{
	if ( !in_array($base_cat[$c], $cat_counting) )
	{
		$cat_counting[] = $base_cat[$c];
	}
}
$cat_count = sizeof($cat_counting);

//
// the rest is dependant upon which category we are viewing, if any at all
//
if ( $cat_view || $cat_count == 1 )
{
	//
	// assign sql conditions to allow for only one category being available
	//
	$sql_condition=( $cat_count == 1 ) ? "" : "AND bet_cat = $cat_view";

	//
	// OK, lets retrieve the base bets for this particular category
	//
	$sql = "SELECT * 
		FROM " . BOOKIE_ADMIN_BETS_TABLE . "
		WHERE multi = -1
		AND bet_time > $time_now
		AND checked = 0
		$sql_condition
		ORDER BY starbet DESC, bet_time DESC, bet_meeting ASC";
	if ( !($result = $db->sql_query($sql)) ) 
	{ 
		message_die(GENERAL_ERROR, 'Error in building base bets', '', __LINE__, __FILE__, $sql); 
	}
		
	$base_betid = $base_meeting = $base_timestamp = $base_star = $base_eachway = $base_restriction = array();
		
	while ( $row = $db->sql_fetchrow($result) )
	{
		$base_betid[] = $row['bet_id'];
		$base_meeting[] = $row['bet_meeting'];
		$base_timestamp[] = $row['bet_time'];
		$base_star[] = $row['starbet'];
		$base_eachway[] = $row['each_way'];

		//
		// restrictions?
		//
		if ( $board_config['bookie_restrict'] )
		{
			$this_restriction = 0;
			$sql_rest = "SELECT bet_id 
				FROM " . BOOKIE_ADMIN_BETS_TABLE . "
				WHERE multi = " . $row['bet_id'];
			if ( !($result_rest = $db->sql_query($sql_rest)) ) 
			{ 
				message_die(GENERAL_ERROR, 'Error in building base bets', '', __LINE__, __FILE__, $sql_rest); 
			}
			
			while ( $row_rest = $db->sql_fetchrow($result_rest) )
			{
				$sql_res = " SELECT admin_betid 
					FROM " . BOOKIE_BETS_TABLE . "
					WHERE admin_betid = " . $row_rest['bet_id'] . "
						AND user_id = " . $userdata['user_id'];
				if ( !($result_res = $db->sql_query($sql_res)) ) 
				{ 
					message_die(GENERAL_ERROR, 'Error in building base bets', '', __LINE__, __FILE__, $sql_res); 
				}
				$row_res = $db->sql_fetchrow($result_res);
					
				if ( $row_res )
				{
					$this_restriction = 1;
				}
			}
			
			$sql_res = "SELECT admin_betid 
				FROM " . BOOKIE_BETS_TABLE . "
				WHERE admin_betid = " . $row['bet_id'] . "
					AND user_id = " . $userdata['user_id'];
			if ( !($result_res = $db->sql_query($sql_res)) ) 
			{ 
				message_die(GENERAL_ERROR, 'Error in building base bets', '', __LINE__, __FILE__, $sql_res); 
			}
			$row_res = $db->sql_fetchrow($result_res);
				
			if ( $row_res )
			{
				$this_restriction = 1;
			}
			$base_restriction[] = $this_restriction;
		}
	}	
}

//
// lets retrieve the categories and display the bets if required
//
$cat_ids = array();
$sql_cat = "SELECT * 
	FROM " . BOOKIE_CAT_TABLE . "
	ORDER BY cat_name ASC";
if ( !($result_cat = $db->sql_query($sql_cat)) ) 
{ 
	message_die(GENERAL_ERROR, 'Error in building bet categories', '', __LINE__, __FILE__, $sql_cat); 
}

while ( $row_cat = $db->sql_fetchrow($result_cat) )
{
	if ( in_array($row_cat['cat_id'], $base_cat) )
	{
		$cat_ids[] = $row_cat['cat_id'];
		$this_id = $row_cat['cat_id'];
		$cat_name = $row_cat['cat_name'];

		//
		// let's do a count of how many meetings and bets are available for each category
		//
		$sql=" SELECT count(*) AS meetings 
			FROM " . BOOKIE_ADMIN_BETS_TABLE . "
			WHERE multi = -1
				AND bet_time > $time_now
				AND checked = 0
				AND bet_cat = $this_id";
		if ( !($result = $db->sql_query($sql)) ) 
		{ 
			message_die(GENERAL_ERROR, 'Error in building bet categories', '', __LINE__, __FILE__, $sql); 
		}
		$row = $db->sql_fetchrow($result);
		
		$tot_meetings = $row['meetings'];
			
		$template->assign_block_vars('cats', array(
			'CAT' => ( $cat_count != 1 && $this_id != $cat_view ) ? '<a href="' . append_sid("bookies.$phpEx?mode=placebet&cat_view=$this_id#$this_id") . '" class="cattitle">' . $cat_name . ' : ' . sprintf($lang['bookie_cat_info'],$tot_meetings) . '</a>' : $cat_name,
			'ANCHOR' => $this_id)
		);			
		
		if ( $cat_view == $this_id || $cat_count == 1 )
		{
			$template->assign_block_vars('switch_yes_bets.catview', array());
			// ok, now we need to assign selections and odds for the drop down
			$x = 1;
			$eachway_available = 0;

			for ($i = 0; $i < sizeof($base_meeting); $i++)
			{ 
				if ( $base_eachway[$i] )
				{
					$eachway_available = 1;
				}
			}
	
			for ($i = 0; $i < sizeof($base_meeting); $i++)
			{
				$drops = 0;
				if ( !$base_restriction[$i] )
				{
					$stake_box = '<input class="post" type="text" style="width: 50px" maxlength="6" size="11" name="' . $x . '" />';
					$selection_namebox[$x] .= '<option value="" selected="selected">' . $lang['bookie_select_namebox'];
					$this_base_meeting = $base_meeting[$i];
					$this_base_timestamp = $base_timestamp[$i];
					$this_base_betid = $base_betid[$i];
					$select_name = $this_base_timestamp . $this_base_betid;

					if ( $eachway_available && !$eachway_needed )
					{
						$template->assign_block_vars('switch_yes_bets.switch_each_way', array());
						$eachway_needed = 1;
					}
							
					// get the base selection and odds
					$sql = "SELECT * 
						FROM " . BOOKIE_ADMIN_BETS_TABLE . "
						WHERE bet_id = '$this_base_betid'";
					if ( !($result = $db->sql_query($sql)) ) 
					{ 
						message_die(GENERAL_ERROR, 'Error in building base bets', '', __LINE__, __FILE__, $sql); 
					}
					$row = $db->sql_fetchrow($result);
					
					$this_base_selection = $row['bet_selection'];
					
					if ( $board_config['bookie_frac_or_dec'] )
					{
						// convert fraction to decimal
						$odds_decimal = number_format((($row['odds_1'] / $row['odds_2']) + 1), 2);
					}
					$this_base_odds1 = $row['odds_1'];
					$this_base_odds2 = $row['odds_2'];
					// now check to see if user has this selection placed
					$userID = $userdata['user_id'];
					
					$sql = "SELECT admin_betid 
						FROM " . BOOKIE_BETS_TABLE . "
						WHERE admin_betid = $this_base_betid
							AND user_id = $userID";
					if ( !($result = $db->sql_query($sql)) ) 
					{ 
						message_die(GENERAL_ERROR, 'Error in building base bets', '', __LINE__, __FILE__, $sql); 
					}
					$row = $db->sql_fetchrow($result);

					// now, if the bet doesn't exist we can include it in the drop down
					if ( empty($row['admin_betid']) )
					{
						$selection_name = ( !$board_config['bookie_frac_or_dec'] ) ? $this_base_selection . ' @ ' . $this_base_odds1 . '/' . $this_base_odds2 : $this_base_selection . ' @ ' . $odds_decimal;
						$selection_namebox[$x] .= '<option value="' . $this_base_selection . '#' . $this_base_odds1 . '#' . $this_base_odds2 . '">' . $selection_name;
						$drops++;
					}
					else
					{
						//
						// so the user has this bet set, but let's do some each way checks
						// we'll allow the user to place two bets ONLY on an each way meeting
						// and if they choose 2 wins or 2 each ways, then that's their problem
						//
						if ( $base_eachway[$i] && $board_config['bookie_eachway'] )
						{
							$sql = "SELECT count(*) AS placed 
								FROM " . BOOKIE_BETS_TABLE . "
								WHERE admin_betid = $this_base_betid
									AND user_id = $userID";
							if ( !($result = $db->sql_query($sql)) ) 
							{ 
								message_die(GENERAL_ERROR, 'Error in building base bets', '', __LINE__, __FILE__, $sql); 
							}
							
							if ( $placed = $db->sql_fetchrow($result) )
							{
								if ( $placed['placed'] < 2 )
								{
									$selection_name = ( !$board_config['bookie_frac_or_dec'] ) ? $this_base_selection . ' @ ' . $this_base_odds1 . '/' . $this_base_odds2 : $this_base_selection . ' @ ' . $odds_decimal;
									$selection_namebox[$x] .= '<option value="' . $this_base_selection . '#' . $this_base_odds1 . '#' . $this_base_odds2 . '">' . $selection_name;
									$drops++;
								}
							}
						}
					}
					
					// next we assign all selections for the multiple bets
					$sql = "SELECT * 
						FROM " . BOOKIE_ADMIN_BETS_TABLE . "
						WHERE multi = '$this_base_betid'
						ORDER BY odds_1 ASC, bet_selection ASC";
					if ( !($result = $db->sql_query($sql)) ) 
					{ 
						message_die(GENERAL_ERROR, 'Error in building base bets', '', __LINE__, __FILE__, $sql); 
					}
					
					while ( $row=$db->sql_fetchrow($result) )
					{
						$this_multi_selection = $row['bet_selection'];
					
						if ( $board_config['bookie_frac_or_dec'] )
						{
							// convert fraction to decimal
							$multi_odds_decimal = number_format((($row['odds_1'] / $row['odds_2']) + 1), 2);
						}
						
						$this_multi_odds1 = $row['odds_1'];
						$this_multi_odds2 = $row['odds_2'];
						$this_multi_betid = $row['bet_id'];
						// now check to see if user has this selection placed
						$userID = $userdata['user_id'];
						
						$sql_a = "SELECT admin_betid 
							FROM " . BOOKIE_BETS_TABLE . "
							WHERE admin_betid = $this_multi_betid
								AND user_id = $userID";
						if ( !($result_a = $db->sql_query($sql_a)) ) 
						{ 
							message_die(GENERAL_ERROR, 'Error in building base bets', '', __LINE__, __FILE__, $sql_a); 
						}
						$row_a = $db->sql_fetchrow($result_a);
						
						// now, if the bet doesn't exist we can include it in the drop down 
						if ( empty($row_a['admin_betid']) )
						{
							$selection_name = ( !$board_config['bookie_frac_or_dec'] ) ? $this_multi_selection . ' @ ' . $this_multi_odds1 . '/' . $this_multi_odds2 : $this_multi_selection . ' @ ' . $multi_odds_decimal;
							$selection_namebox[$x] .= '<option value="' . $this_multi_selection . '#' . $this_multi_odds1 . '#' . $this_multi_odds2 . '">' . $selection_name;
							$drops++;
						}
						else
						{
							//
							// so the user has this bet set, but let's do some each way checks
							// we'll allow the user to place two bets ONLY on an each way meeting
							// and if they choose 2 wins or 2 each ways, then that's their problem
							//
							if ( $base_eachway[$i] && $board_config['bookie_eachway'] )
							{
								$sql_z = "SELECT count(*) AS placed 
									FROM " . BOOKIE_BETS_TABLE . "
									WHERE admin_betid = $this_multi_betid
										AND user_id = $userID";
								if ( !($result_z = $db->sql_query($sql_z)) ) 
								{ 
									message_die(GENERAL_ERROR, 'Error in building base bets', '', __LINE__, __FILE__, $sql_z); 
								}
								
								if ( $placed = $db->sql_fetchrow($result_z) )
								{
									if ( $placed['placed'] < 2 )
									{
										$selection_name = ( !$board_config['bookie_frac_or_dec'] ) ? $this_multi_selection . ' @ ' . $this_multi_odds1 . '/' . $this_multi_odds2 : $this_multi_selection . ' @ ' . $multi_odds_decimal;
										$selection_namebox[$x] .= '<option value="' . $this_multi_selection . '#' . $this_multi_odds1 . '#' . $this_multi_odds2 . '">' . $selection_name;
										$drops++;
									}
								}
							}
						}
					}
					
					// now we assign the template vars
					// Convert date to viewable format
					$bet_time = create_date( $board_config['default_dateformat'], $this_base_timestamp, $board_config['board_timezone'] );
							
					$row_color = ( !($i % 2) ) ? $theme['td_color1'] : $theme['td_color2']; 
					$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
						
					$ew_check_name = 'ew_' . $this_base_betid;
						
					if ( !$drops )
					{
						$selection_namebox[$x] = '';
						$selection_namebox[$x] .='<option value="" selected="selected">' . $lang['bookie_no_sels_avail'];
						$selection_namebox[$x] .='</select>';
					}
							
					$template->assign_block_vars('cats.bets', array(
						'ROW_COLOR' => '#' . $row_color, 
						'ROW_CLASS' => $row_class,
						'DATE' => $bet_time,
						'MEETING' => $this_base_meeting,
						'SELECTION' => $selection_namebox[$x],
						'STAKE_BOX' => ( $drops ) ? $stake_box : '',
						'SELECT_NAME' => $select_name,
						'STAR' => ( $base_star[$i] ) ? '<img src="' . $images['icon_bookie_star'] . '" alt="' . $lang['bookie_star_alt'] . '" title="' . $lang['bookie_star_alt'] . '" />' : '',
						'EACH_WAY_CHECKBOX' => ( $base_eachway[$i] ) ? '<input type="checkbox" name="' . $ew_check_name . '" value="1" />' : '')
					); 
					$x++;
					
					if ( $eachway_available )
					{
						$template->assign_block_vars('cats.bets.switch_this_each_way', array());
					}
				}
				else
				{
					$selection_namebox[$x] = '';
					$selection_namebox[$x] .='<option value="" selected="selected">' . $lang['bookie_no_sels_avail'];
					$selection_namebox[$x] .='</select>';
					$bet_time = create_date( $board_config['default_dateformat'], $base_timestamp[$i], $board_config['board_timezone'] );
					
					$row_color = ( !($i % 2) ) ? $theme['td_color1'] : $theme['td_color2']; 
					$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
					
					$template->assign_block_vars('cats.bets', array(
						'ROW_COLOR' => '#' . $row_color, 
						'ROW_CLASS' => $row_class,
						'DATE' => $bet_time,
						'MEETING' => $base_meeting[$i],
						'SELECTION' => $selection_namebox[$x],
						'STAKE_BOX' => '',
						'SELECT_NAME' => '',
						'STAR' => ( $base_star[$i] ) ? '<img src="' . $images['icon_bookie_star'] . '" alt="' . $lang['bookie_star_alt'] . '" title="' . $lang['bookie_star_alt'] . '" />' : '',
						'EACH_WAY_CHECKBOX' => '')
					); 
					$x++;
					
					if ( $eachway_available )
					{
						$template->assign_block_vars('cats.bets.switch_this_each_way', array());
					}
				}
			}
		}
	}
}

//
// process the info if required
//
$bet_done = 0;
if ( isset($HTTP_POST_VARS['placebet']) )
{
	$points_now = $userdata['user_points'];
	// count through the bets, and insert the valid ones.
	$x = 1;
	for ($i = 0; $i < sizeof($base_betid); $i++)
	{
		// have they entered anything for a particular base bet?
		if ( !empty($HTTP_POST_VARS[$x]) && !empty($HTTP_POST_VARS[$base_timestamp[$i] . $base_betid[$i]]) )
		{
			$this_stake = intval($HTTP_POST_VARS[$x]);
			
			if ( $this_stake < $board_config['bookie_min_bet'] )
			{
				$redirect = '<meta http-equiv="refresh" content="3;url=' . append_sid("bookies.$phpEx?") . '">';
				$message = sprintf($lang['bookie_stake_unsuccess_min_admin'],  number_format($board_config['bookie_min_bet']), $board_config['points_name']) . $redirect;
				message_die(GENERAL_MESSAGE, $message);
			}
			if ( $this_stake > $board_config['bookie_max_bet'] && intval($board_config['bookie_max_bet']) > 0 )
			{
				$redirect = '<meta http-equiv="refresh" content="3;url=' . append_sid("bookies.$phpEx?") . '">';
				$message = sprintf($lang['bookie_stake_unsuccess_max_admin'],  number_format($board_config['bookie_max_bet']), $board_config['points_name']) . $redirect;
				message_die(GENERAL_MESSAGE, $message);
			}
			
			$this_selection_data = explode('#', $HTTP_POST_VARS[$base_timestamp[$i] . $base_betid[$i]]);
			$this_selection = htmlspecialchars($this_selection_data[0]);
			$this_odds1 = intval($this_selection_data[1]);
			$this_odds2 = intval($this_selection_data[2]);
			$this_userid = $userdata['user_id'];
			$this_timestamp = $base_timestamp[$i];
			$this_meeting = $base_meeting[$i];
			$each_way_var = 'ew_' . $base_betid[$i];
			$each_way_selected = intval($HTTP_POST_VARS[$each_way_var]); 
			
			// Get the admin bet id
			$sql = "SELECT bet_id 
				FROM " . BOOKIE_ADMIN_BETS_TABLE . "
				WHERE bet_time = $this_timestamp
					AND bet_selection = '" . str_replace("\'", "''", $this_selection) . "'
					AND bet_meeting = '" . addslashes($this_meeting) . "'
					AND odds_1 = $this_odds1
					AND odds_2 = $this_odds2";
			if ( !($result = $db->sql_query($sql)) ) 
			{ 
				message_die(GENERAL_ERROR, 'Error in building base bets', '', __LINE__, __FILE__, $sql); 
			}
			$row = $db->sql_fetchrow($result);
			
			$this_admin_betid = $row['bet_id'];
			
			// do they have enough cash?
			if ( $points_now < $this_stake )
			{
				$message = $lang['bookie_no_cash'] . $bookie_redirect_pending;
				message_die(GENERAL_MESSAGE, $message);
			}
			
			// OK, so let's insert the bet
			$sql = "INSERT INTO " . BOOKIE_BETS_TABLE . " (user_id, time, meeting, selection, bet, odds_1, odds_2, admin_betid, each_way)
				VALUES ($this_userid, $this_timestamp, '" . addslashes($this_meeting) . "', '" . str_replace("\'", "''", $this_selection) . "', $this_stake, $this_odds1, $this_odds2, $this_admin_betid, '$each_way_selected')";
			if (!$db->sql_query($sql))
			{ 
				message_die(GENERAL_ERROR, 'Error in entering bet details into the DB', '', __LINE__, __FILE__, $sql); 
			}
				
			if ( $board_config['bookie_allow_commission'] )
			{
				$commission_value = intval($this_stake * ($board_config['bookie_commission'] / 100));
	
				//
				// pay the commission if the bet placer isn't the bet setter
				//
				$sql = "SELECT multi 
					FROM " . BOOKIE_ADMIN_BETS_TABLE . "
					WHERE bet_id = $this_admin_betid";
				if ( !($result = $db->sql_query($sql)) ) 
				{ 
					message_die(GENERAL_ERROR, 'Error paying commission', '', __LINE__, __FILE__, $sql); 
				}
				$row = $db->sql_fetchrow($result);
				
				if ( $row['multi'] == -1 )
				{
					$setter_id = $this_admin_betid;
				}
				else
				{
					$setter_id = $row['multi'];
				}
				
				$sql = "SELECT setter 
					FROM " . BOOKIE_BET_SETTER_TABLE . "
					WHERE bet_id = $setter_id
					LIMIT 1";
				if ( !($result = $db->sql_query($sql)) ) 
				{ 
					message_die(GENERAL_ERROR, 'Error paying commission', '', __LINE__, __FILE__, $sql); 
				}
				$row = $db->sql_fetchrow($result);
					
				if ( $row['setter'] && $row['setter'] != $userdata['user_id'] )
				{
					$sql = "UPDATE " . BOOKIE_BET_SETTER_TABLE . "
						SET commission = commission + $commission_value
						WHERE setter = " . $row['setter'] . "
							AND bet_id = $setter_id";
					if ( !$db->sql_query($sql) ) 
					{ 
						message_die(GENERAL_ERROR, 'Error paying commission', '', __LINE__, __FILE__, $sql); 
					}
				}
				//
				// end commission
				//
			}
				
			// subtract the points
			$sql = "UPDATE " . USERS_TABLE . "
				SET user_points = user_points - $this_stake
				WHERE user_id = $this_userid";
			if (!$db->sql_query($sql))
			{ 
				message_die(GENERAL_ERROR, 'Error in deducting stake', '', __LINE__, __FILE__, $sql); 
			}
			
			$points_now = $points_now - $this_stake;
			$bet_done++;
		} 
		$x++;
	}
	
	if ( $bet_done > 0 )
	{
		$message = $lang['bookie_bet_success'] . $bookie_redirect_pending;
		message_die(GENERAL_MESSAGE, $message);
	}
}

?>