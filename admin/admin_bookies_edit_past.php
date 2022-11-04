<?php
/** 
*
* @package admin
* @version $Id: admin_bookies_edit_past.php,v 3.0.0 2004/11/17 17:49:33 majorflam Exp $
* @copyright (c) 2004 Majorflam
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', 1);

//
// First we do the setmodules stuff for the admin cp.
//
if( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	$module['Bookmakers']['Edit_Past_Bets'] = $filename;

	return;
}

//
// Load default header
//
$no_page_header = TRUE;
$phpbb_root_path = './../';
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);

//
// Output the authorisation details
//
$template->set_filenames(array(
	'body' => 'admin/admin_bookie_edit_past.tpl')
);

//
// Include language
//
$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_admin_bookmakers.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_admin_bookmakers.' . $phpEx);

// OK, we need to grab the real board timezone and compare with the users.
// if they don't match, we need to let them know
//
$sql=" SELECT config_value FROM " . CONFIG_TABLE . "
WHERE config_name='board_timezone'
";
if ( !($result = $db->sql_query($sql)) ) 
{ 
	message_die(GENERAL_ERROR, 'Error in entering new bet', '', __LINE__, __FILE__, $sql); 
}
$row=$db->sql_fetchrow($result);
$board_timezone=intval($row['config_value']);
if ( $userdata['user_timezone'] != $board_timezone )
{
	$message = $lang['bookie_timezone_warning'];
	message_die(GENERAL_MESSAGE, $message);
}

// set the template switches
$mode=htmlspecialchars($HTTP_GET_VARS['mode']);

if ( !$mode )
{
	$template->assign_block_vars('select_mode', array());
	$url=append_sid("admin_bookies_edit_past.$phpEx?mode=edit_mode");
	
	//
	// Drop boxes
	//
	
	// hours
	$hour_box .= '<option value="" selected="selected">hh';
	for ( $i=0; $i<24; $i++ )
	{
		$i_view = ( $i<10 ) ? '0' . $i : $i;
		$hour_box .= '<option value="' . $i . '">' . $i_view;
	}
	$hour_box .= '</select>';
	
	// minutes
	$min_box .= '<option value="" selected="selected">mm';
	for ( $i=0; $i<60; $i++ )
	{
		$i_view = ( $i<10 ) ? '0' . $i : $i;
		$min_box .= '<option value="' . $i . '">' . $i_view;
	}
	$min_box .= '</select>';
	
	// Days
	$day_box .= '<option value="" selected="selected">DD';
	for ( $i=1; $i<32; $i++ )
	{
		$i_view = ( $i<10 ) ? '0' . $i : $i;
		$day_box .= '<option value="' . $i . '">' . $i_view;
	}
	$day_box .= '</select>';
	
	// Months
	$month_box .= '<option value="" selected="selected">MM';
	for ( $i=1; $i<13; $i++ )
	{
		$i_view = ( $i<10 ) ? '0' . $i : $i;
		$month_box .= '<option value="' . $i . '">' . $i_view;
	}
	$month_box .= '</select>';
	
	// Years
	$year_box .= '<option value="" selected="selected">YYYY';
	for ( $i=2005; $i<2010; $i++ )
	{
		$year_box .= '<option value="' . $i . '">' . $i;
	}
	$year_box .= '</select>';
	
	// end drop boxes
	
	$template->assign_vars(array(
	'SELECT_BY_TIME' => $lang['bookie_select_by_time'],
	'SELECT_BY_TIME_EXP' => $lang['bookie_select_by_time_exp'],
	'SUBMIT' => $lang['Submit'],
	'URL' => $url,
	'BETHOUR_BOX' => $hour_box,
	'BETMINUTE_BOX' => $min_box,
	'DAY_BOX' => $day_box,
	'MONTH_BOX' => $month_box,
	'YEAR_BOX' => $year_box,
	));
}
else if ( $mode == 'edit_mode' )
{
	$template->assign_block_vars('edit_mode', array());
	$timestamp=( isset($HTTP_GET_VARS['timestamp']) ) ? intval($HTTP_GET_VARS['timestamp']) : 0;
	$redirect='<meta http-equiv="refresh" content="2;url=' . append_sid("admin_bookies_edit_past.$phpEx?") . '">';
	if ( !$timestamp )
	{
		$bet_time_placed = intval($HTTP_POST_VARS['bet_hour']) . ':' . intval($HTTP_POST_VARS['bet_minute']);
		$bet_date_placed = intval($HTTP_POST_VARS['bet_day']) . '-' . intval($HTTP_POST_VARS['bet_month']) . '-' . intval($HTTP_POST_VARS['bet_year']);
		
		if ( empty($HTTP_POST_VARS['bet_day']) || empty($HTTP_POST_VARS['bet_month']) || empty($HTTP_POST_VARS['bet_year']) )
		{
			$message = $lang['bookies_notall_fileds'] . $redirect;
			message_die(GENERAL_MESSAGE, $message);
		}
		
		$getdate = explode('-', $bet_date_placed);
		$gettime = explode(':', $bet_time_placed);
		$timestamp = gmmktime(intval($gettime[0]), intval($gettime[1]), 0, intval($getdate[1]), intval($getdate[0]), intval($getdate[2])); 
		$bet_timestamp_placed = $timestamp - ($board_config['board_timezone'] * 3600);
		// make sure the time is valid
		if ( $bet_timestamp_placed > time() )
		{
			$message = $lang['bookies_invalid_date_future'] . $redirect;
			message_die(GENERAL_MESSAGE, $message);
		}
	}
	else
	{
		$bet_timestamp_placed=$timestamp;
	}
	
	//
	// OK, we got a timestamp so let's see if we get a match
	//
	$sql=" SELECT count(*) AS total FROM " . BOOKIE_BETS_TABLE . "
	WHERE time=$bet_timestamp_placed
	";
	if ( !($result = $db->sql_query($sql)) ) 
	{ 
		message_die(GENERAL_ERROR, 'Error in retrieving bets', '', __LINE__, __FILE__, $sql); 
	}
	$row=$db->sql_fetchrow($result);
	if ( !$row['total'] )
	{
		$message = $lang['bookies_edit_time_nomatch'] . $redirect;
		message_die(GENERAL_MESSAGE, $message);
	}
	//
	// we got a match, so lets retrieve and display!
	//
	$this_admin_betid=array();
	$sql=" SELECT admin_betid, bet_result FROM " . BOOKIE_BETS_TABLE . "
	WHERE time=$bet_timestamp_placed
	ORDER BY meeting ASC, selection ASC
	";
	if ( !($result = $db->sql_query($sql)) ) 
	{ 
		message_die(GENERAL_ERROR, 'Error in retrieving bets', '', __LINE__, __FILE__, $sql); 
	}
	while( $row=$db->sql_fetchrow($result) )
	{
		$bet_result=$row['bet_result'];
		if ( !$bet_result )
		{
			$message = $lang['bookie_cannot_edit_past'];
			message_die(GENERAL_MESSAGE, $message);
		}
		$this_admin_betid[]=$row['admin_betid'];		
	}
	//
	// now count through the array and remove the doublers
	//
	$check_id=array();
	for ( $i=0; $i<count($this_admin_betid); $i++ )
	{
		$needle=$this_admin_betid[$i];
		
		if ( $needle != '*' )
		{
			$this_admin_betid[$i]='*';
			for ( $j=0; $j<count($this_admin_betid); $j++ )
			{
				if ( $this_admin_betid[$j] == $needle )
				{
					$this_admin_betid[$j] = '*';
				}
			}
			$check_id[]=$needle;
		}
	}
	//
	// now we have an array of bet_id's so we can grab data and allow processing
	//
	for ( $i=0; $i<count($check_id); $i++ )
	{
		$x=$i+1;
		$sql=" SELECT * FROM " . BOOKIE_BETS_TABLE . "
		WHERE admin_betid=" . $check_id[$i] . "
		LIMIT 1
		";
		if ( !($result = $db->sql_query($sql)) ) 
		{ 
			message_die(GENERAL_ERROR, 'Error in retrieving bets', '', __LINE__, __FILE__, $sql); 
		}
		$row=$db->sql_fetchrow($result);
		$betid = $row['admin_betid'];
		$bet_timestamp = $row['time'];
		$meeting = $row['meeting'];
		$selection = $row['selection'];
		$odds_1 = $row['odds_1'];
		$odds_2 = $row['odds_2'];
		$eachway=$row['each_way'];
		$bet_result=$row['bet_result'];
		
		// Convert date to viewable format
		$bet_time = create_date( $board_config['default_dateformat'], $bet_timestamp, $board_config['board_timezone'] );
		$orig_time = create_date( $board_config['default_dateformat'], $orig_timestamp, $board_config['board_timezone'] );
			
		$row_color = ( !($x % 2) ) ? $theme['td_color1'] : $theme['td_color2']; 
		$row_class = ( !($x % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
			
		if ( $board_config['bookie_frac_or_dec'] )
		{
		// convert fraction to decimal
		$odds_decimal=round( (($odds_1/$odds_2)+1), 2);
		}	
		$template->assign_block_vars('processbet', array(
		'ROW_COLOR' => '#' . $row_color, 
		'ROW_CLASS' => $row_class,
		'ROW_TEXTCOLOR' => $row_textcolor,
		'BETID' => $betid,
		'TIME' => $bet_time,
		'MEETING' => $meeting,
		'SELECTION' => ( $eachway == 0 ) ? $selection : $selection . '<br /><b>(' . $lang['bookie_pm_eachway'] . ')</b>',
		'ODDS' => ( !$odds_decimal ) ? '<input class="post" type="text" style="width: 50px" maxlength="5" size="11" name="enter_odds1" value="' . $odds_1 . '" /><b>&nbsp;/&nbsp;</b><input class="post" type="text" style="width: 50px" maxlength="5" size="11" name="enter_odds2" value="' . $odds_2 . '" />' : '<input class="post" type="text" style="width: 75px" maxlength="8" size="11" name="enter_odds_dec" value="' . $odds_decimal . '" />',
		'WINNER' => 'winner_' . $betid,
		'GO' => $bet_id,
		'URL' => append_sid("admin_bookies_edit_past.$phpEx?mode=process&amp;bet_id=$betid"),
		'YES_CHECKED' => ( $bet_result == 1 ) ? 'CHECKED="CHECKED"' : '',
		'EWW_CHECKED' => ( $bet_result == 4 ) ? 'CHECKED="CHECKED"' : '',
		'EWP_CHECKED' => ( $bet_result == 5 ) ? 'CHECKED="CHECKED"' : '',
		'NO_CHECKED' => ( $bet_result == 2 ) ? 'CHECKED="CHECKED"' : '',
		'REF_CHECKED' => ( $bet_result == 3 ) ? 'CHECKED="CHECKED"' : '',
		));
		
		if ( $eachway != 0 )
		{
			$template->assign_block_vars('processbet.eachway', array());
		}
		else
		{
			$template->assign_block_vars('processbet.normal', array());
		}
	}
}
else
{
	//
	// so, we're re-processing then. First thing to do, is undo all these bets
	//
	$bet_id=intval($HTTP_GET_VARS['bet_id']);
	
	// create an array of details for each instance of this bet
	
	$sql=" SELECT * FROM " . BOOKIE_BETS_TABLE . "
	WHERE admin_betid=$bet_id
	";
	if ( !($result = $db->sql_query($sql)) ) 
	{ 
		message_die(GENERAL_ERROR, 'Error in re-processing bets', '', __LINE__, __FILE__, $sql); 
	}
	while ( $row=$db->sql_fetchrow($result) )
	{
		$bet_result=$row['bet_result'];
		if ( !$bet_result )
		{
			$message = $lang['bookie_cannot_edit_past'];
			message_die(GENERAL_MESSAGE, $message);
		}
		$send_timestamp=$row['time'];
		$sql_field=( $row['win_lose'] > 0 ) ? "total_win=total_win-" . $row['win_lose'] . ", netpos=netpos-" . $row['win_lose'] : "total_lose=total_lose-" . $row['win_lose'] . ", netpos=netpos+" . $row['win_lose'];
		// now we update the stats to reflect the reversal
		$sql_a=" UPDATE " . BOOKIE_STATS_TABLE . "
		SET bets_placed=bets_placed-1, $sql_field
		WHERE user_id=" . $row['user_id'];
		if ( !$db->sql_query($sql_a) ) 
		{ 
			message_die(GENERAL_ERROR, 'Error in re-processing bets', '', __LINE__, __FILE__, $sql_a); 
		}
		// now we take back the winnings if there were any win or EWW
		if ( $bet_result == 1 || $bet_result == 4 )
		{
			$sql_b=" UPDATE " . USERS_TABLE . "
			SET user_points=user_points-" . ( $row['win_lose']+$row['bet'] ) . "
			WHERE user_id=" . $row['user_id'];
			if ( !$db->sql_query($sql_b) ) 
			{ 
				message_die(GENERAL_ERROR, 'Error in re-processing bets', '', __LINE__, __FILE__, $sql_b); 
			}
		}
		// now we take back the winnings if it was an EWP
		if ( $bet_result == 5 )
		{
			$sql_b=" UPDATE " . USERS_TABLE . "
			SET user_points=user_points-" . ( $row['win_lose']+( $row['bet']/2 ) ) . "
			WHERE user_id=" . $row['user_id'];
			if ( !$db->sql_query($sql_b) ) 
			{ 
				message_die(GENERAL_ERROR, 'Error in re-processing bets', '', __LINE__, __FILE__, $sql_b); 
			}
		}
		// now, if it was a refund we need to take that back
		if ( $bet_result == 3 )
		{
			$sql_b=" UPDATE " . USERS_TABLE . "
			SET user_points=user_points-" . $row['bet']  . "
			WHERE user_id=" . $row['user_id'];
			if ( !$db->sql_query($sql_b) ) 
			{ 
				message_die(GENERAL_ERROR, 'Error in re-processing bets', '', __LINE__, __FILE__, $sql_b); 
			}
		}
		// and if it was a losing bet, then we need to do nothing!
	}
	// lastly we make all these bets seem like they were never processed
	$sql=" UPDATE " . BOOKIE_BETS_TABLE . "
	SET win_lose=0, bet_result=0
	WHERE admin_betid=$bet_id
	";
	if ( !$db->sql_query($sql) ) 
	{ 
		message_die(GENERAL_ERROR, 'Error in re-processing bets', '', __LINE__, __FILE__, $sql); 
	}
	//
	// OK, so reversal is complete so let's reprocess the bet
	//
	// deal with odds first
	$sent_betid=intval($HTTP_GET_VARS['bet_id']);
	$winner_var='winner_' . $sent_betid;
	$winner_sel=htmlspecialchars($HTTP_POST_VARS[$winner_var]);
	if ( $board_config['bookie_frac_or_dec'] )
	{
		$enter_odds2=100;
		$enter_odds_dec = htmlspecialchars($HTTP_POST_VARS['enter_odds_dec']);
		$enter_odds_display=number_format($enter_odds_dec, 2);
		//
		// make it numerical
		//
		$enter_odds_dec_int=intval($enter_odds_dec*100);
		// now convert to odds1 value
		$enter_odds1=($enter_odds_dec_int/100)-1;
		// now multply again to get the default fractional value for the database
		$enter_odds1=$enter_odds1*100;
	}
	else
	{
		$enter_odds1 = intval($HTTP_POST_VARS['enter_odds1']);
		$enter_odds2 = intval($HTTP_POST_VARS['enter_odds2']);
	}
	$enter_betid = $sent_betid;
	$enter_win = $winner_sel;
	
	//
	// process bet
	//
	if ( !empty($enter_betid) )
	{
		if ( empty($enter_odds1) || empty($enter_odds2) )
		{
			$sql = "SELECT * FROM " . BOOKIE_BETS_TABLE . " WHERE admin_betid=$enter_betid LIMIT 1";
			if ( !($result = $db->sql_query($sql)) ) 
			{ 
				message_die(GENERAL_ERROR, 'Error in checking for assigned odds', '', __LINE__, __FILE__, $sql); 
			}
			$row = $db->sql_fetchrow($result);
			$check_odds1 = $row['odds_1'];
			$check_odds2 = $row['odds_2'];
			if ( $check_odds1 > 0 && $check_odds2 > 0 )
			{
				$enter_odds1 = $check_odds1;
				$enter_odds2 = $check_odds2;
			}
			else
			{
				message_die(GENERAL_MESSAGE, $lang['bookie_no_odds'], "", "", "", "");
			}
		}
	$pm_tosend = array();
	if ( $enter_win == 'NO' || $enter_win == 'no' )
	{
		$sql_a = "SELECT * FROM " . BOOKIE_BETS_TABLE . " WHERE admin_betid='$enter_betid' ";
		if ( !($result_a = $db->sql_query($sql_a)) ) 
			{ 
				message_die(GENERAL_ERROR, 'Error in getting userID to update Stats Table', '', __LINE__, __FILE__, $sql_a); 
			}
		while ( $row_a = $db->sql_fetchrow($result_a) )
		{
			$bet_userID = $row_a['user_id'];
			$pm_tosend[] = $bet_userID;
			$bet_stake = $row_a['bet'];
			
			$sql = "SELECT user_id FROM " . BOOKIE_STATS_TABLE . " WHERE user_id='$bet_userID' ";
			if ( !($result = $db->sql_query($sql)) ) 
			{ 
				message_die(GENERAL_ERROR, 'Error in getting userID to update Stats Table', '', __LINE__, __FILE__, $sql); 
			}
			$row = $db->sql_fetchrow($result);
			$stat_userID = $row['user_id'];
			if ( $stat_userID != $bet_userID )
			{
				$sql = "INSERT INTO " . BOOKIE_STATS_TABLE . " (user_id) VALUES ('$bet_userID') ";
				if (!$db->sql_query($sql))
				{ 
					message_die(GENERAL_ERROR, 'Error inserting userid details into the stats DB', '', __LINE__, __FILE__, $sql); 
				}
			}
			$sql = "UPDATE " . BOOKIE_STATS_TABLE . "
			SET total_lose=total_lose+'$bet_stake', bets_placed=bets_placed+1
			WHERE user_id='$bet_userID' ";
			if (!$db->sql_query($sql))
			{ 
				message_die(GENERAL_ERROR, 'Error updating total loss into the stats DB', '', __LINE__, __FILE__, $sql); 
			}
													
			$sql = "UPDATE " . BOOKIE_BETS_TABLE . "
			SET odds_1='$enter_odds1',odds_2='$enter_odds2',win_lose=win_lose-'$bet_stake',checked=1, bet_result=2
			WHERE admin_betid='$enter_betid' 
			AND user_id='$bet_userID'
			";
			if (!$db->sql_query($sql))
			{ 
				message_die(GENERAL_ERROR, 'Error updating odds1 into the stats DB', '', __LINE__, __FILE__, $sql); 
			}
		}
	}
				
	if ( $enter_win == 'YES' || $enter_win == 'yes' )
	{		
		$sql_a = "SELECT * FROM " . BOOKIE_BETS_TABLE . " WHERE admin_betid='$enter_betid' ";
		if ( !($result_a = $db->sql_query($sql_a)) ) 
		{ 
			message_die(GENERAL_ERROR, 'Error in getting userID to update Stats Table', '', __LINE__, __FILE__, $sql_a); 
		}
		while ( $row_a = $db->sql_fetchrow($result_a) )
		{
			$bet_userID = $row_a['user_id'];
			$pm_tosend[] = $bet_userID;
			$bet_stake = $row_a['bet'];
			
			// calculate winnings
			$multiplier = $enter_odds1 / $enter_odds2;
			$bet_won = intval($multiplier * $bet_stake);
			$winnings = $bet_won + $bet_stake;
			
			$sql = "SELECT user_id FROM " . BOOKIE_STATS_TABLE . " WHERE user_id='$bet_userID' ";
			if ( !($result = $db->sql_query($sql)) ) 
			{ 
				message_die(GENERAL_ERROR, 'Error in getting userID to update Stats Table', '', __LINE__, __FILE__, $sql); 
			}
			$row = $db->sql_fetchrow($result);
			$stat_userID = $row['user_id'];
			if ( $stat_userID != $bet_userID )
			{
				$sql = "INSERT INTO " . BOOKIE_STATS_TABLE . " (user_id) VALUES ('$bet_userID') ";
				if (!$db->sql_query($sql))
				{ 
					message_die(GENERAL_ERROR, 'Error inserting userid details into the stats DB', '', __LINE__, __FILE__, $sql); 
				}
			}
			$sql = "UPDATE " . BOOKIE_STATS_TABLE . "
			SET total_win=total_win+'$bet_won',bets_placed=bets_placed+1
			WHERE user_id='$bet_userID'
			";
			if (!$db->sql_query($sql))
			{ 
				message_die(GENERAL_ERROR, 'Error updating total loss into the stats DB', '', __LINE__, __FILE__, $sql); 
			}
												
			$sql = "UPDATE " . BOOKIE_BETS_TABLE . "
			SET odds_1='$enter_odds1',odds_2='$enter_odds2',checked=1,win_lose='$bet_won', bet_result=1
			WHERE admin_betid='$enter_betid' 
			AND user_id='$bet_userID'
			";
			if (!$db->sql_query($sql))
			{ 
				message_die(GENERAL_ERROR, 'Error updating odds1 into the stats DB', '', __LINE__, __FILE__, $sql); 
			}
											
			$sql = "UPDATE " . USERS_TABLE . " SET user_points=user_points+'$winnings' WHERE user_id='$bet_userID' ";
			if (!$db->sql_query($sql))
			{ 
				message_die(GENERAL_ERROR, 'Error updating winnings', '', __LINE__, __FILE__, $sql); 
			}
		}
	}
							
	if ( $enter_win == 'EWW' || $enter_win == 'eww' )
	{
		$sql_a = "SELECT * FROM " . BOOKIE_BETS_TABLE . " WHERE admin_betid='$enter_betid' ";
		if ( !($result_a = $db->sql_query($sql_a)) ) 
		{ 
			message_die(GENERAL_ERROR, 'Error in getting userID to update Stats Table', '', __LINE__, __FILE__, $sql_a); 
		}
		while ( $row_a = $db->sql_fetchrow($result_a) )
		{
			$bet_userID = $row_a['user_id'];
			$pm_tosend[] = $bet_userID;
			$bet_stake = $row_a['bet'];
			
			// calculate winnings
			$bet_ew_stake = $bet_stake / 2;
			$multiplier = $enter_odds1 / $enter_odds2;
			$ew_multiplier = $multiplier / 4;
			// calculate the half on the nose
			$nose_win = intval($bet_ew_stake * $multiplier);
			
			// calculate the half on the place
			$place_win = intval($bet_ew_stake * $ew_multiplier);
			
			$bet_won = $nose_win + $place_win;
			$winnings = $bet_won + $bet_stake;					
			
			$sql = "SELECT user_id FROM " . BOOKIE_STATS_TABLE . " WHERE user_id='$bet_userID' ";
			if ( !($result = $db->sql_query($sql)) ) 
			{ 
				message_die(GENERAL_ERROR, 'Error in getting userID to update Stats Table', '', __LINE__, __FILE__, $sql); 
			}
			$row = $db->sql_fetchrow($result);
			$stat_userID = $row['user_id'];
			if ( $stat_userID != $bet_userID )
			{
				$sql = "INSERT INTO " . BOOKIE_STATS_TABLE . " (user_id) VALUES ('$bet_userID') ";
				if (!$db->sql_query($sql))
				{ 
					message_die(GENERAL_ERROR, 'Error inserting userid details into the stats DB', '', __LINE__, __FILE__, $sql); 
				}
			}
			$sql = "UPDATE " . BOOKIE_STATS_TABLE . "
			SET total_win=total_win+'$bet_won',bets_placed=bets_placed+1
			WHERE user_id='$bet_userID'
			";
			if (!$db->sql_query($sql))
			{ 
				message_die(GENERAL_ERROR, 'Error updating total loss into the stats DB', '', __LINE__, __FILE__, $sql); 
			}
												
			$sql = "UPDATE " . BOOKIE_BETS_TABLE . "
			SET odds_1='$enter_odds1',odds_2='$enter_odds2',checked=1,win_lose='$bet_won', bet_result=4
			WHERE admin_betid='$enter_betid' 
			AND user_id='$bet_userID'
			";
			if (!$db->sql_query($sql))
			{ 
				message_die(GENERAL_ERROR, 'Error updating odds1 into the stats DB', '', __LINE__, __FILE__, $sql); 
			}
											
			$sql = "UPDATE " . USERS_TABLE . "
			SET user_points=user_points+'$winnings'
			WHERE user_id='$bet_userID'
			";
			if (!$db->sql_query($sql))
			{ 
				message_die(GENERAL_ERROR, 'Error updating winnings', '', __LINE__, __FILE__, $sql); 
			}
		}
	}
			
	if ( $enter_win == 'EWP' || $enter_win == 'ewp' )
	{
		$sql_a = "SELECT * FROM " . BOOKIE_BETS_TABLE . " WHERE admin_betid='$enter_betid' ";
		if ( !($result_a = $db->sql_query($sql_a)) ) 
		{ 
			message_die(GENERAL_ERROR, 'Error in getting userID to update Stats Table', '', __LINE__, __FILE__, $sql_a); 
		}
		while ( $row_a = $db->sql_fetchrow($result_a) )
		{
			$bet_userID = $row_a['user_id'];
			$pm_tosend[] = $bet_userID;
			$bet_stake = $row_a['bet'];
			
			// calculate winnings
			$bet_ew_stake = $bet_stake / 2;
			$multiplier = $enter_odds1 / $enter_odds2;
			$ew_multiplier = $multiplier / 4;
			// calculate the half on the nose
			
			// calculate the half on the place
			$place_win = intval($bet_ew_stake * $ew_multiplier);
			
			$bet_won = $place_win;
			$winnings = $bet_won + $bet_ew_stake;					
			
			$sql = "SELECT user_id FROM " . BOOKIE_STATS_TABLE . " WHERE user_id='$bet_userID' ";
			if ( !($result = $db->sql_query($sql)) ) 
			{ 
				message_die(GENERAL_ERROR, 'Error in getting userID to update Stats Table', '', __LINE__, __FILE__, $sql); 
			}
			$row = $db->sql_fetchrow($result);
			$stat_userID = $row['user_id'];
			if ( $stat_userID != $bet_userID )
			{
				$sql = "INSERT INTO " . BOOKIE_STATS_TABLE . " (user_id) VALUES ('$bet_userID') ";
				if (!$db->sql_query($sql))
				{ 
					message_die(GENERAL_ERROR, 'Error inserting userid details into the stats DB', '', __LINE__, __FILE__, $sql); 
				}
			}
			$sql = "UPDATE " . BOOKIE_STATS_TABLE . "
			SET total_win=total_win+'$bet_won',bets_placed=bets_placed+1
			WHERE user_id='$bet_userID'
			";
			if (!$db->sql_query($sql))
			{ 
				message_die(GENERAL_ERROR, 'Error updating total loss into the stats DB', '', __LINE__, __FILE__, $sql); 
			}
												
			$sql = "UPDATE " . BOOKIE_BETS_TABLE . "
			SET odds_1='$enter_odds1',odds_2='$enter_odds2',checked=1,win_lose='$bet_won', bet_result=5
			WHERE admin_betid='$enter_betid' 
			AND user_id='$bet_userID'
			";
			if (!$db->sql_query($sql))
			{ 
				message_die(GENERAL_ERROR, 'Error updating odds1 into the stats DB', '', __LINE__, __FILE__, $sql); 
			}
											
			$sql = "UPDATE " . USERS_TABLE . "
			SET user_points=user_points+'$winnings'
			WHERE user_id='$bet_userID'
			";
			if (!$db->sql_query($sql))
			{ 
				message_die(GENERAL_ERROR, 'Error updating winnings', '', __LINE__, __FILE__, $sql); 
			}
		}
	}
	if ( $enter_win == 'ref' || $enter_win == 'REF' )
	{
		$sql_a = "SELECT * FROM " . BOOKIE_BETS_TABLE . "
		WHERE admin_betid='$enter_betid'
		";
		if ( !($result_a = $db->sql_query($sql_a)) ) 
		{ 
			message_die(GENERAL_ERROR, 'Error in getting userID to update Stats Table', '', __LINE__, __FILE__, $sql_a); 
		}
		while ( $row_a = $db->sql_fetchrow($result_a) )
		{
			$bet_userID = $row_a['user_id'];
			$pm_tosend[] = $bet_userID;
			$bet_stake = $row_a['bet'];
			// Pay the stake back
			$sql = "UPDATE " . USERS_TABLE . "
			SET user_points=user_points+'$bet_stake'
			WHERE user_id='$bet_userID'
			";
			if (!$db->sql_query($sql))
			{ 
				message_die(GENERAL_ERROR, 'Error updating winnings', '', __LINE__, __FILE__, $sql); 
			}
			// Show the bet as refunded
			$sql = "UPDATE " . BOOKIE_BETS_TABLE . "
			SET odds_1='$enter_odds1',odds_2='$enter_odds2',checked=1,win_lose=0, bet_result=3
			WHERE admin_betid='$enter_betid' 
			AND user_id='$bet_userID'
			";
			if (!$db->sql_query($sql))
			{ 
				message_die(GENERAL_ERROR, 'Error updating odds1 into the stats DB', '', __LINE__, __FILE__, $sql); 
			}
			// update bets placed for the stats
			$sql = "UPDATE " . BOOKIE_STATS_TABLE . "
			SET bets_placed=bets_placed+1
			WHERE user_id='$bet_userID'
			";
			if (!$db->sql_query($sql))
			{ 
				message_die(GENERAL_ERROR, 'Error updating total loss into the stats DB', '', __LINE__, __FILE__, $sql); 
			}
		}			
	}
	if ( $board_config['bookie_pm'] == 1 )
	{
		for ($i=0; $i < count($pm_tosend); $i++)
		{
			$pms_tosend=$pm_tosend[$i];
			$sql=" SELECT * FROM " . BOOKIE_BETS_TABLE . "
			WHERE admin_betid=$enter_betid
			AND user_id=$pms_tosend";
			if ( !($result = $db->sql_query($sql)) ) 
			{ 
				message_die(GENERAL_ERROR, 'Error in getting userID to send PM', '', __LINE__, __FILE__, $sql); 
			}
				$bet_data = $db->sql_fetchrow($result);
				include_once($phpbb_root_path . 'includes/bbcode.'.$phpEx);
				include_once($phpbb_root_path . 'includes/functions_post.'.$phpEx);
				include_once($phpbb_root_path . 'common.' . $phpEx);
				include_once($phpbb_root_path . 'includes/functions_bookies.' . $phpEx);
				$user_to_id=$bet_data['user_id'];
				$winnings=$bet_data['win_lose'];
				if ( $winnings == 0 )
				{
					$winnings='None';
				}
				if ( $winnings < 0 )
				{
					$winnings='[color=red]' . $winnings . '[/color]';
				}
				if ( $board_config['bookie_frac_or_dec'] )
				{
					$odds=$enter_odds_display;
				}
				else
				{
					$odds=$bet_data['odds_1'] . '/' . $bet_data['odds_2'];
				}
				if ( $bet_data['each_way'] != 0 )
				{
					$bet_selection_pm=$bet_data['selection'] . ' (' . $lang['bookie_pm_eachway'] . ')';
				}
				else
				{
					$bet_selection_pm=$bet_data['selection'];
				}
				
				switch ($bet_data['bet_result'])
				{
					case 0:
					$bet_result=( !$checked ) ? '[i]' . $lang['bookie_res_pending'] . '[/i]' : '[i]' . $lang['bookie_res_undefined'] . '[/i]';
					$display_stake=$stake;
					break;
					
					case 1:
					$bet_result='[b]' . $lang['bookie_res_win'] . '[/b]';
					$display_stake=$stake;
					break;
					
					case 2:
					$bet_result='[b]' . $lang['bookie_res_loss'] . '[/b]';
					$display_stake=$stake;
					break;
					
					case 3:
					$bet_result='[b]' . $lang['bookie_res_refund'] . '[/b]';
					$display_stake=$stake;
					break;
					
					case 4:
					$bet_result='[b]' . $lang['bookie_res_win'] . '[/b]';
					$display_stake=$stake;
					break;
					
					case 5:
					$bet_result='[b]' . $lang['bookie_res_place'] . '[/b]';
					$display_stake=$stake/2;
					break;
					
					default:
					$bet_result='[i]' . $lang['bookie_res_undefined'] . '[/i]';
					$display_stake=$stake;
					break;
				}
		
				$bookies_subject=$lang['bookie_set_pm_mesage_reprocess'];
				$message= $lang['bookie_set_pm_mesage_reprocess_exp'] . '
				
				[b]' . $lang['bookie_process_selection'] . ':[/b] ' . $bet_selection_pm . '
				[b]' . $lang['bookie_process_meeting'] . ':[/b] ' . $bet_data['meeting'] . '
				[b]' . $lang['bookie_process_oddsenter'] . ':[/b]' . $odds . '
				[b]' . $lang['bookie_process_stake'] . ':[/b] ' . $bet_data['bet'] . '
				[b]' . $lang['bookie_pm_result'] . ':[/b] ' . $bet_result . '
				[b]' . $lang['bookie_pm_winnings'] . ':[/b] ' . $winnings;
				$send_email=1;
				bookies_send_pm($user_to_id, $bookies_subject, $message, $send_email, $userdata['user_id']);
			}
		}
		$bookie_redirect='<meta http-equiv="refresh" content="2;url=' . append_sid("admin_bookies_edit_past.$phpEx?mode=edit_mode&amp;timestamp=$send_timestamp") . '">';
		$message=$lang['bookie_set_process_success'] . $bookie_redirect;
		message_die(GENERAL_MESSAGE, $message, '', '', '', '');
	}	
}
$template->assign_vars(array(
'HEADER' => $lang['bookie_edit_past_header'],
'PROCESS_TIME' => $lang['bookie_process_time'],
'PROCESS_MEETING' => $lang['bookie_process_meeting'],
'PROCESS_SELECTION' => $lang['bookie_process_selection'],
'ODDS_ODDS' => $lang['bookie_process_oddsenter'],
'WINNER' => $lang['bookie_process_winenter'],
'PROCESS_GO' => $lang['bookie_process_go'],
'L_YES' => $lang['Yes'],
'L_NO' => $lang['No'],
'L_EWW' => $lang['bookie_process_ew_win'],
'L_EWP' => $lang['bookie_process_ew_place'],
'L_REF' => $lang['bookie_process_refund'],
'BOOKIE_VERSION' => $board_config['bookie_version'],
)); 

include('./page_header_admin.'.$phpEx);

$template->pparse('body');

include('./page_footer_admin.'.$phpEx);

?>