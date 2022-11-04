<?php
/** 
*
* @package admin
* @version $Id: admin_bookies.php,v 3.0.0 2004/11/17 17:49:33 majorflam Exp $
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
	$module['Bookmakers']['Process_Bets'] = $filename;

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
	'body' => 'admin/admin_bookies.tpl')
);

// Get language Variables
include_once($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_admin_bookmakers.' . $phpEx);


// OK, we need to grab the real board timezone and compare with the users.
// if they don't match, we need to let them know
//
$sql = "SELECT config_value 
	FROM " . CONFIG_TABLE . "
	WHERE config_name = 'board_timezone'";
if ( !($result = $db->sql_query($sql)) ) 
{ 
	message_die(GENERAL_ERROR, 'Error in entering new bet', '', __LINE__, __FILE__, $sql); 
}
$row = $db->sql_fetchrow($result);
$board_timezone = intval($row['config_value']);
if ( $userdata['user_timezone'] != $board_timezone )
{
	$message = $lang['bookie_timezone_warning'];
	message_die(GENERAL_MESSAGE, $message);
}

// pay commission
$sql_a = "SELECT bet_id 
	FROM " . BOOKIE_ADMIN_BETS_TABLE . "
	WHERE bet_time < " . time() . "
		AND checked = 0
		AND multi = -1";
if ( !($result_a = $db->sql_query($sql_a)) ) 
{ 
	message_die(GENERAL_ERROR, 'Error paying commission', '', __LINE__, __FILE__, $sql_a); 
}
while ( $row_a = $db->sql_fetchrow($result_a) )
{
	$ad_bse_id=$row_a['bet_id'];
	
	$sql = "SELECT commission, setter, paid 
		FROM " . BOOKIE_BET_SETTER_TABLE . "
		WHERE bet_id = $ad_bse_id";
	if ( !($result = $db->sql_query($sql)) ) 
	{ 
		message_die(GENERAL_ERROR, 'Error paying commission', '', __LINE__, __FILE__, $sql); 
	}
	$row = $db->sql_fetchrow($result);
	$commission = $row['commission'];
	$setter = $row['setter'];
	$com_paid = $row['paid'];
		
	if ( $setter && !$com_paid )
	{
		$sql = " UPDATE " . USERS_TABLE . "
			SET user_points = user_points+'$commission'
			WHERE user_id = $setter";
		if (!$db->sql_query($sql))
		{ 
			message_die(GENERAL_ERROR, 'Error paying commission', '', __LINE__, __FILE__, $sql); 
		}
			
		$sql=" UPDATE " . BOOKIE_BET_SETTER_TABLE . "
			SET paid = 1
			WHERE bet_id = $ad_bse_id";
		if (!$db->sql_query($sql))
		{ 
			message_die(GENERAL_ERROR, 'Error paying commission', '', __LINE__, __FILE__, $sql); 
		}
	}
	$setter = '';
	$com_paid = '';
	$commission = 0;
}
// end commission

$mode=htmlspecialchars($HTTP_GET_VARS['mode']);
if ( $mode )
{
	if ( isset($HTTP_POST_VARS['betid_arr']) )
	{
		$this_betid_arr=explode(':', htmlspecialchars($HTTP_POST_VARS['betid_arr']));
	}
	else
	{
		message_die(GENERAL_MESSAGE, $lang['bookie_no_bets_to_process']);
	}
	$winner_sel=array();
	$these_odds1=array();
	$these_odds2=array();
	for ( $i=0; $i<count($this_betid_arr); $i++ )
	{
		$sent_betid=$this_betid_arr[$i];
		$winner_var='winner_' . $sent_betid;
		$winner_sel[$sent_betid]=htmlspecialchars($HTTP_POST_VARS[$winner_var]);
		$odds_append_dec='enter_odds_dec_' . $sent_betid;
		$odds_append_one='enter_odds1_' . $sent_betid;
		$odds_append_two='enter_odds2_' . $sent_betid;
		if ( $board_config['bookie_frac_or_dec'] )
		{
			$these_odds2[$sent_betid]=100;
			$these_odds_dec = htmlspecialchars($HTTP_POST_VARS[$odds_append_dec]);
			$these_odds_display=number_format($these_odds_dec, 2);
			//
			// make it numerical
			//
			$these_odds_dec_int=intval($these_odds_dec*100);
			// now convert to odds1 value
			$these_oddsone=($these_odds_dec_int/100)-1;
			// now multply again to get the default fractional value for the database
			$these_odds1[$sent_betid]=$these_oddsone*100;
		}
		else
		{
			$these_odds1[$sent_betid] = intval($HTTP_POST_VARS[$odds_append_one]);
			$these_odds2[$sent_betid] = intval($HTTP_POST_VARS[$odds_append_two]);
		}
	}
}

// let's purge any bets that haven't been used
$timenow=time();
$sql=" SELECT bet_id FROM " . BOOKIE_ADMIN_BETS_TABLE . "
	WHERE bet_time < $timenow
		AND checked = 0
		AND each_way != -1
		AND multi != -5";
if ( !($result = $db->sql_query($sql)) ) 
{ 
	message_die(GENERAL_ERROR, 'Error in purging unused bets', '', __LINE__, __FILE__, $sql); 
}
while ( $row = $db->sql_fetchrow($result) )
{
	$this_betid = $row['bet_id'];
	$sql_a=" SELECT admin_betid 
		FROM " . BOOKIE_BETS_TABLE . "
		WHERE admin_betid = $this_betid
		LIMIT 1";
	if ( !($result_a = $db->sql_query($sql_a)) ) 
	{ 
		message_die(GENERAL_ERROR, 'Error in purging unused bets', '', __LINE__, __FILE__, $sql_a); 
	}
	$row_a = $db->sql_fetchrow($result_a);
	if ( empty($row_a['admin_betid']) )
	{
		$sql_b = "DELETE FROM " . BOOKIE_ADMIN_BETS_TABLE . "
			WHERE bet_id = $this_betid";
		if ( !($result_b = $db->sql_query($sql_b)) ) 
		{ 
			message_die(GENERAL_ERROR, 'Error in deleting unused bets', '', __LINE__, __FILE__, $sql_b); 
		}
	}
	else
	{
		//
		// lets deal with each way bets
		//
		$sql_a = " SELECT * FROM " . BOOKIE_BETS_TABLE . "
		WHERE admin_betid=$this_betid
			AND each_way = 1
			LIMIT 1";
		if ( !($result_a = $db->sql_query($sql_a)) ) 
		{ 
			message_die(GENERAL_ERROR, 'Error in purging unused bets', '', __LINE__, __FILE__, $sql_a); 
		}
		$row_a = $db->sql_fetchrow($result_a);
		//
		// if there's an each way bet on this selection, then we need to create a new admin
		// bet and update the bets table
		//
		if ( $row_a )
		{
			 $sql_b=" INSERT INTO " . BOOKIE_ADMIN_BETS_TABLE . " (bet_time, bet_selection, bet_meeting, odds_1, odds_2, multi, each_way)
			 	VALUES (" . $row_a['time'] . ", '" . addslashes($row_a['selection']) . "', '" . addslashes($row_a['meeting']) . "', " . $row_a['odds_1'] . ", " . $row_a['odds_2'] . ", -2, -1)";
			if ( !$db->sql_query($sql_b) ) 
			{ 
				message_die(GENERAL_ERROR, 'Error in purging unused bets', '', __LINE__, __FILE__, $sql_b); 
			}
			//
			// now assign this new admin bet to the user bet
			//
			$sql_b=" SELECT bet_id FROM " . BOOKIE_ADMIN_BETS_TABLE . "
			WHERE bet_time=" . $row_a['time'] . "
				AND bet_meeting = '" . addslashes($row_a['meeting']) . "'
				AND bet_selection = '" . addslashes($row_a['selection']) . "'
				AND odds_1 = " . $row_a['odds_1'] . "
				AND odds_2 = " . $row_a['odds_2'] . "
				AND multi = -2";
			if ( !($result_b = $db->sql_query($sql_b)) ) 
			{ 
				message_die(GENERAL_ERROR, 'Error in purging unused bets', '', __LINE__, __FILE__, $sql_b); 
			}
			$row_b = $db->sql_fetchrow($result_b);
			$sql_c = "UPDATE " . BOOKIE_BETS_TABLE . "
				SET admin_betid = " . $row_b['bet_id'] . ", each_way = -1
				WHERE admin_betid = $this_betid
				AND each_way = 1";
			if ( !$db->sql_query($sql_c) ) 
			{ 
				message_die(GENERAL_ERROR, 'Error in purging unused bets', '', __LINE__, __FILE__, $sql_c); 
			}
		}
	}
}
for ( $loop=0; $loop<count($this_betid_arr); $loop++ )
{
	// Set General Config
	$bold_text = '';
	$bold_textoff = '';
	$enter_betid = $this_betid_arr[$loop];
	$enter_win = $winner_sel[$enter_betid];
	$enter_odds1=$these_odds1[$enter_betid];
	$enter_odds2=$these_odds2[$enter_betid];

// Process Bet
if ( !empty($enter_betid) )
{
	if ( empty($enter_odds1) || empty($enter_odds2) )
	{
		$sql = "SELECT * FROM " . BOOKIE_ADMIN_BETS_TABLE . " 
			WHERE bet_id = $enter_betid";
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
	$sql_a = "SELECT * FROM " . BOOKIE_BETS_TABLE . " 
		WHERE admin_betid = '$enter_betid'";
	if ( !($result_a = $db->sql_query($sql_a)) ) 
	{ 
		message_die(GENERAL_ERROR, 'Error in getting userID to update Stats Table', '', __LINE__, __FILE__, $sql_a); 
	}
	while ( $row_a = $db->sql_fetchrow($result_a) )
	{
		$bet_userID = $row_a['user_id'];
		$pm_tosend[] = $bet_userID;
		$bet_stake = $row_a['bet'];
		
		$sql = "SELECT user_id 
			FROM " . BOOKIE_STATS_TABLE . " 
			WHERE user_id = '$bet_userID'";
		if ( !($result = $db->sql_query($sql)) ) 
		{ 
			message_die(GENERAL_ERROR, 'Error in getting userID to update Stats Table', '', __LINE__, __FILE__, $sql); 
		}
		$row = $db->sql_fetchrow($result);
		$stat_userID = $row['user_id'];
		if ( $stat_userID != $bet_userID )
		{
			$sql = "INSERT INTO " . BOOKIE_STATS_TABLE . " (user_id) 
				VALUES ('$bet_userID')";
			if (!$db->sql_query($sql))
			{ 
				message_die(GENERAL_ERROR, 'Error inserting userid details into the stats DB', '', __LINE__, __FILE__, $sql); 
			}
		}
		$sql = "UPDATE " . BOOKIE_STATS_TABLE . "
			SET total_lose = total_lose + '$bet_stake', bets_placed = bets_placed + 1
			WHERE user_id = '$bet_userID'";
		if (!$db->sql_query($sql))
		{ 
			message_die(GENERAL_ERROR, 'Error updating total loss into the stats DB', '', __LINE__, __FILE__, $sql); 
		}
												
		$sql = "UPDATE " . BOOKIE_BETS_TABLE . "
			SET odds_1 = '$enter_odds1', odds_2 = '$enter_odds2', win_lose = win_lose - '$bet_stake', checked = 1, bet_result = 2
			WHERE admin_betid = '$enter_betid' 
				AND user_id = '$bet_userID'";
		if (!$db->sql_query($sql))
		{ 
			message_die(GENERAL_ERROR, 'Error updating odds1 into the stats DB', '', __LINE__, __FILE__, $sql); 
		}
	}
	$sql = "DELETE FROM " . BOOKIE_ADMIN_BETS_TABLE . " 
		WHERE bet_id = '$enter_betid'";
	if (!$db->sql_query($sql))
	{ 
		message_die(GENERAL_ERROR, 'Error updating admin bets in DB', '', __LINE__, __FILE__, $sql); 
	}
}
			
if ( $enter_win == 'YES' || $enter_win == 'yes' )
{		
	$sql_a = "SELECT * 
		FROM " . BOOKIE_BETS_TABLE . " 
		WHERE admin_betid = '$enter_betid'";
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
		
		$sql = "SELECT user_id 
			FROM " . BOOKIE_STATS_TABLE . " 
			WHERE user_id = '$bet_userID'";
		if ( !($result = $db->sql_query($sql)) ) 
		{ 
			message_die(GENERAL_ERROR, 'Error in getting userID to update Stats Table', '', __LINE__, __FILE__, $sql); 
		}
		$row = $db->sql_fetchrow($result);
		$stat_userID = $row['user_id'];
		if ( $stat_userID != $bet_userID )
		{
			$sql = "INSERT INTO " . BOOKIE_STATS_TABLE . " (user_id) 
				VALUES ('$bet_userID')";
			if (!$db->sql_query($sql))
			{ 
				message_die(GENERAL_ERROR, 'Error inserting userid details into the stats DB', '', __LINE__, __FILE__, $sql); 
			}
		}
		$sql = "UPDATE " . BOOKIE_STATS_TABLE . "
			SET total_win = total_win + '$bet_won', bets_placed = bets_placed + 1
			WHERE user_id = '$bet_userID'";
		if (!$db->sql_query($sql))
		{ 
			message_die(GENERAL_ERROR, 'Error updating total loss into the stats DB', '', __LINE__, __FILE__, $sql); 
		}
											
		$sql = "UPDATE " . BOOKIE_BETS_TABLE . "
			SET odds_1 = '$enter_odds1', odds_2 = '$enter_odds2',checked=1,win_lose='$bet_won', bet_result=1
			WHERE admin_betid = '$enter_betid' 
				AND user_id = '$bet_userID'";
		if (!$db->sql_query($sql))
		{ 
			message_die(GENERAL_ERROR, 'Error updating odds1 into the stats DB', '', __LINE__, __FILE__, $sql); 
		}
										
		$sql = "UPDATE " . USERS_TABLE . " 
			SET user_points = user_points + '$winnings' 
			WHERE user_id = '$bet_userID'";
		if (!$db->sql_query($sql))
		{ 
			message_die(GENERAL_ERROR, 'Error updating winnings', '', __LINE__, __FILE__, $sql); 
		}
	}
	$sql = "DELETE FROM " . BOOKIE_ADMIN_BETS_TABLE . " 
		WHERE bet_id = '$enter_betid' ";
	if (!$db->sql_query($sql))
	{ 
		message_die(GENERAL_ERROR, 'Error updating bet in admin DB', '', __LINE__, __FILE__, $sql); 
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
	$sql = "DELETE FROM " . BOOKIE_ADMIN_BETS_TABLE . " WHERE bet_id='$enter_betid' ";
	if (!$db->sql_query($sql))
	{ 
		message_die(GENERAL_ERROR, 'Error updating odds2 into the stats DB', '', __LINE__, __FILE__, $sql); 
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
	$sql = "DELETE FROM " . BOOKIE_ADMIN_BETS_TABLE . "
	WHERE bet_id='$enter_betid'
	";
	if (!$db->sql_query($sql))
	{ 
		message_die(GENERAL_ERROR, 'Error updating admin bets in DB', '', __LINE__, __FILE__, $sql); 
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
	$sql = "DELETE FROM " . BOOKIE_ADMIN_BETS_TABLE . "
	WHERE bet_id='$enter_betid'
	";
	if (!$db->sql_query($sql))
	{ 
		message_die(GENERAL_ERROR, 'Error updating admin bets in DB', '', __LINE__, __FILE__, $sql); 
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
	
			$bookies_subject=$lang['bookie_set_pm_mesage'];
			$message= $lang['bookie_set_pm_mesage'] . '
			
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
	}
	$bet_processed++;
}
if ( $bet_processed )
{
	$bookie_redirect='<meta http-equiv="refresh" content="2;url=' . append_sid("admin_bookies.$phpEx?") . '">';
	$message=$lang['bookie_set_process_success'] . $bookie_redirect;
	message_die(GENERAL_MESSAGE, $message, '', '', '', '');
}

$sql=" SELECT count(*) as total FROM " . BOOKIE_ADMIN_BETS_TABLE . "
WHERE multi=-5
AND bet_time<" . time() . "
";
if ( !($result = $db->sql_query($sql)) ) 
{ 
	message_die(GENERAL_ERROR, 'Error in getting bets to build processing table', '', __LINE__, __FILE__, $sql); 
}
$row = $db->sql_fetchrow($result);
$userbet_count=$row['total'];

//
// for normal admin bets
//
$na_betid_arr='';
$timenow = time();
$sql = "SELECT * FROM " . BOOKIE_ADMIN_BETS_TABLE . " WHERE bet_time<'$timenow'
AND checked=0
AND multi>-2
ORDER BY bet_time DESC,bet_meeting DESC ";
if ( !($result = $db->sql_query($sql)) ) 
{ 
	message_die(GENERAL_ERROR, 'Error in getting bets to build processing table', '', __LINE__, __FILE__, $sql); 
}
$x=1;
while ( $row = $db->sql_fetchrow($result) ) 
{ 
	if ( $normal_done != 'done' )
	{
		$normal_done = 'done';
		$template->assign_block_vars('normal_need', array());
	}
	$betid = $row['bet_id'];
	$bet_timestamp = $row['bet_time'];
	$meeting = $row['bet_meeting'];
	$selection = $row['bet_selection'];
	$odds_1 = $row['odds_1'];
	$odds_2 = $row['odds_2'];
	
	// Convert date to viewable format
	$bet_time = create_date( $board_config['default_dateformat'], $bet_timestamp, $board_config['board_timezone'] );
	$orig_time = create_date( $board_config['default_dateformat'], $orig_timestamp, $board_config['board_timezone'] );
		
	$row_color = ( !($x % 2) ) ? $theme['td_color1'] : $theme['td_color2']; 
	$row_class = ( !($x % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
		
	if ( $meeting == $last_meeting && $bet_timestamp == $last_timestamp )
	{
		$meeting='<i>' . $meeting . '</i>';
		$bet_time='<i>' . $bet_time . '</i>';
	}
	else
	{
		$meeting='<b>' . $meeting . '</b>';
		$bet_time='<b>' . $bet_time . '</b>';
	}
	
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
	'SELECTION' => $selection,
	'ODDS' => ( !$odds_decimal ) ? '<input class="post" type="text" style="width: 50px" maxlength="5" size="11" name="enter_odds1_' . $betid . '" value="' . $odds_1 . '" /><b>&nbsp;/&nbsp;</b><input class="post" type="text" style="width: 50px" maxlength="5" size="11" name="enter_odds2_' . $betid . '" value="' . $odds_2 . '" />' : '<input class="post" type="text" style="width: 75px" maxlength="8" size="11" name="enter_odds_dec_' . $betid . '" value="' . $odds_decimal . '" />',
	'WINNER' => 'winner_' . $betid,
	'GO' => $bet_id,
	));
	$last_meeting= $row['bet_meeting'];
	$last_timestamp=$row['bet_time'];
	$na_betid_arr=( $x != 1 ) ? $na_betid_arr . ':' . $betid : $betid;
	$x++;
}
if ( $normal_done != 'done' && !$userbet_count )
	{
		$template->assign_block_vars('normal_not_need', array());
	}

//
// for admin each way
//
$ae_betid_arr='';
$last_meeting= '';
$last_timestamp='';
$timenow = time();
$sql = "SELECT * FROM " . BOOKIE_ADMIN_BETS_TABLE . " WHERE bet_time<'$timenow'
AND checked=0
AND multi=-2
ORDER BY bet_time DESC,bet_meeting DESC ";
if ( !($result = $db->sql_query($sql)) ) 
{ 
	message_die(GENERAL_ERROR, 'Error in getting bets to build processing table', '', __LINE__, __FILE__, $sql); 
}
$x=1;

while ( $row = $db->sql_fetchrow($result) ) 
{ 
	if ( $ew_done != 'done' )
	{
		$ew_done = 'done';
		$template->assign_block_vars('ew_need', array());
	}
	$betid = $row['bet_id'];
	$bet_timestamp = $row['bet_time'];
	$meeting = $row['bet_meeting'];
	$selection = $row['bet_selection'];
	$odds_1 = $row['odds_1'];
	$odds_2 = $row['odds_2'];
	
	// Convert date to viewable format
	$bet_time = create_date( $board_config['default_dateformat'], $bet_timestamp, $board_config['board_timezone'] );
	$orig_time = create_date( $board_config['default_dateformat'], $orig_timestamp, $board_config['board_timezone'] );
		
	$row_color = ( !($x % 2) ) ? $theme['td_color1'] : $theme['td_color2']; 
	$row_class = ( !($x % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
		
	if ( $meeting == $last_meeting && $bet_timestamp == $last_timestamp )
	{
		$meeting='<i>' . $meeting . '</i>';
		$bet_time='<i>' . $bet_time . '</i>';
	}
	else
	{
		$meeting='<b>' . $meeting . '</b>';
		$bet_time='<b>' . $bet_time . '</b>';
	}
	if ( $board_config['bookie_frac_or_dec'] )
	{
	// convert fraction to decimal
	$odds_decimal=round( (($odds_1/$odds_2)+1), 2);
	}	
	$template->assign_block_vars('process_ew_bet', array(
	'ROW_COLOR' => '#' . $row_color, 
	'ROW_CLASS' => $row_class,
	'ROW_TEXTCOLOR' => $row_textcolor,
	'BETID' => $betid,
	'TIME' => $bet_time,
	'MEETING' => $meeting,
	'SELECTION' => $selection,
	'ODDS' => ( !$odds_decimal ) ? '<input class="post" type="text" style="width: 50px" maxlength="5" size="11" name="enter_odds1_' . $betid . '" value="' . $odds_1 . '" /><b>&nbsp;/&nbsp;</b><input class="post" type="text" style="width: 50px" maxlength="5" size="11" name="enter_odds2_' . $betid . '" value="' . $odds_2 . '" />' : '<input class="post" type="text" style="width: 75px" maxlength="8" size="11" name="enter_odds_dec_' . $betid . '" value="' . $odds_decimal . '" />',
	'WINNER' => 'winner_' . $betid,
	'GO' => $bet_id,
	));
	$last_meeting= $row['bet_meeting'];
	$last_timestamp=$row['bet_time'];
	$ae_betid_arr=( $x != 1 ) ? $ae_betid_arr . ':' . $betid : $betid;
	$x++;
}
if ( $ew_done != 'done' && !$userbet_count )
	{
		$template->assign_block_vars('ew_not_need', array());
	}
	
//
// for normal user bets
//
$nu_betid_arr='';
$timenow = time();
$last_meeting= '';
$last_timestamp='';
$sql = "SELECT * FROM " . BOOKIE_ADMIN_BETS_TABLE . " WHERE bet_time<'$timenow'
AND checked=0
AND multi=-5
AND each_way=0
ORDER BY bet_time DESC,bet_meeting DESC ";
if ( !($result = $db->sql_query($sql)) ) 
{ 
	message_die(GENERAL_ERROR, 'Error in getting bets to build processing table', '', __LINE__, __FILE__, $sql); 
}
$x=1;
while ( $row = $db->sql_fetchrow($result) ) 
{ 
	if ( $user_normal_done != 'done' )
	{
		$user_normal_done = 'done';
		$template->assign_block_vars('user_normal_need', array());
	}
	$betid = $row['bet_id'];
	$bet_timestamp = $row['bet_time'];
	$meeting = $row['bet_meeting'];
	$selection = $row['bet_selection'];
	$odds_1 = $row['odds_1'];
	$odds_2 = $row['odds_2'];
	
	// Convert date to viewable format
	$bet_time = create_date( $board_config['default_dateformat'], $bet_timestamp, $board_config['board_timezone'] );
	$orig_time = create_date( $board_config['default_dateformat'], $orig_timestamp, $board_config['board_timezone'] );
		
	$row_color = ( !($x % 2) ) ? $theme['td_color1'] : $theme['td_color2']; 
	$row_class = ( !($x % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
		
	if ( $meeting == $last_meeting && $bet_timestamp == $last_timestamp )
	{
		$meeting='<i>' . $meeting . '</i>';
		$bet_time='<i>' . $bet_time . '</i>';
	}
	else
	{
		$meeting='<b>' . $meeting . '</b>';
		$bet_time='<b>' . $bet_time . '</b>';
	}
	if ( $board_config['bookie_frac_or_dec'] )
	{
	// convert fraction to decimal
	$odds_decimal=round( (($odds_1/$odds_2)+1), 2);
	}	
	$template->assign_block_vars('processbet_user_norm', array(
	'ROW_COLOR' => '#' . $row_color, 
	'ROW_CLASS' => $row_class,
	'ROW_TEXTCOLOR' => $row_textcolor,
	'BETID' => $betid,
	'TIME' => $bet_time,
	'MEETING' => $meeting,
	'SELECTION' => $selection,
	'ODDS' => ( !$odds_decimal ) ? '<input class="post" type="text" style="width: 50px" maxlength="5" size="11" name="enter_odds1_' . $betid . '" value="' . $odds_1 . '" /><b>&nbsp;/&nbsp;</b><input class="post" type="text" style="width: 50px" maxlength="5" size="11" name="enter_odds2_' . $betid . '" value="' . $odds_2 . '" />' : '<input class="post" type="text" style="width: 75px" maxlength="8" size="11" name="enter_odds_dec_' . $betid . '" value="' . $odds_decimal . '" />',
	'WINNER' => 'winner_' . $betid,
	'GO' => $bet_id,
	));
	$last_meeting= $row['bet_meeting'];
	$last_timestamp=$row['bet_time'];
	$nu_betid_arr=( $x != 1 ) ? $nu_betid_arr . ':' . $betid : $betid;
	$x++;
}

//
// for each way user bets
//
$ue_betid_arr='';
$timenow = time();
$last_meeting= '';
$last_timestamp='';
$sql = "SELECT * FROM " . BOOKIE_ADMIN_BETS_TABLE . " WHERE bet_time<'$timenow'
AND checked=0
AND multi=-5
AND each_way=1
ORDER BY bet_time DESC,bet_meeting DESC ";
if ( !($result = $db->sql_query($sql)) ) 
{ 
	message_die(GENERAL_ERROR, 'Error in getting bets to build processing table', '', __LINE__, __FILE__, $sql); 
}
$x=1;
while ( $row = $db->sql_fetchrow($result) ) 
{ 
	if ( $user_ew_done != 'done' )
	{
		$user_ew_done = 'done';
		$template->assign_block_vars('user_ew_need', array());
	}
	$betid = $row['bet_id'];
	$bet_timestamp = $row['bet_time'];
	$meeting = $row['bet_meeting'];
	$selection = $row['bet_selection'];
	$odds_1 = $row['odds_1'];
	$odds_2 = $row['odds_2'];
	
	// Convert date to viewable format
	$bet_time = create_date( $board_config['default_dateformat'], $bet_timestamp, $board_config['board_timezone'] );
	$orig_time = create_date( $board_config['default_dateformat'], $orig_timestamp, $board_config['board_timezone'] );
		
	$row_color = ( !($x % 2) ) ? $theme['td_color1'] : $theme['td_color2']; 
	$row_class = ( !($x % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
		
	if ( $meeting == $last_meeting && $bet_timestamp == $last_timestamp )
	{
		$meeting='<i>' . $meeting . '</i>';
		$bet_time='<i>' . $bet_time . '</i>';
	}
	else
	{
		$meeting='<b>' . $meeting . '</b>';
		$bet_time='<b>' . $bet_time . '</b>';
	}
	if ( $board_config['bookie_frac_or_dec'] )
	{
	// convert fraction to decimal
	$odds_decimal=round( (($odds_1/$odds_2)+1), 2);
	}	
	$template->assign_block_vars('processbet_user_ew', array(
	'ROW_COLOR' => '#' . $row_color, 
	'ROW_CLASS' => $row_class,
	'ROW_TEXTCOLOR' => $row_textcolor,
	'BETID' => $betid,
	'TIME' => $bet_time,
	'MEETING' => $meeting,
	'SELECTION' => $selection,
	'ODDS' => ( !$odds_decimal ) ? '<input class="post" type="text" style="width: 50px" maxlength="5" size="11" name="enter_odds1_' . $betid . '" value="' . $odds_1 . '" /><b>&nbsp;/&nbsp;</b><input class="post" type="text" style="width: 50px" maxlength="5" size="11" name="enter_odds2_' . $betid . '" value="' . $odds_2 . '" />' : '<input class="post" type="text" style="width: 75px" maxlength="8" size="11" name="enter_odds_dec_' . $betid . '" value="' . $odds_decimal . '" />',
	'WINNER' => 'winner_' . $betid,
	'GO' => $bet_id,
	));
	$last_meeting= $row['bet_meeting'];
	$last_timestamp=$row['bet_time'];
	$ue_betid_arr=( $x != 1 ) ? $ue_betid_arr . ':' . $betid : $betid;
	$x++;
}
	
// Set template Vars
$template->assign_vars(array(
'PROCESS_HEADER' => $lang['bookie_process_header'],
'PROCESS_EXPLAIN' => $lang['bookie_process_explain'],
'PROCESS_CURRENT' => $lang['bookie_process_current'],
'PROCESS_BETID' => $lang['bookie_process_betid'],
'PROCESS_USERNAME' => $lang['bookie_process_username'],
'PROCESS_TIME' => $lang['bookie_process_time'],
'PROCESS_MEETING' => $lang['bookie_process_meeting'],
'PROCESS_SELECTION' => $lang['bookie_process_selection'],
'PROCESS_STAKE' => $lang['bookie_process_stake'],
'PROCESS_ENTER' => $lang['bookie_process_enter'],
'PROCESS_ODDS_ENTER' => $lang['bookie_process_oddsenter'],
'WINNER' => $lang['bookie_process_winenter'],
'ODDS_ODDS' => $lang['bookie_process_oddsenter'],
'WINNER_NAMEBOX' => $winner_namebox,
'PLACE_TIME' => $lang['bookie_place_time'],
'PROCESS_BUTTON'  => $lang['bookie_process_button'],
'PROCESS_GO' => $lang['bookie_process_go'],
'L_YES' => $lang['Yes'],
'L_NO' => $lang['No'],
'L_EWW' => $lang['bookie_process_ew_win'],
'L_EWP' => $lang['bookie_process_ew_place'],
'L_REF' => $lang['bookie_process_refund'],
'PROCESS_CURRENT_EW' => $lang['bookie_process_current_ew'],
'PROCESS_CURRENT_NO' => ( $board_config['bookie_eachway'] ) ? $lang['bookie_process_current_no'] : $lang['bookie_process_current_no_normal'],
'PROCESS_CURRENT_EW_NO' => ( $board_config['bookie_eachway'] ) ? $lang['bookie_process_current_ew_no'] : '',
'PROCESS_USER_EW' => $lang['bookie_process_user_ew'],
'PROCESS_USER_EW_EXP' => $lang['bookie_process_user_ew_exp'],
'PROCESS_USER_NORM' => $lang['bookie_process_user_norm'],
'PROCESS_USER_NORM_EXP' => $lang['bookie_process_user_norm_exp'],
'BOOKIE_VERSION' => $board_config['bookie_version'],
'NA_BETID_ARR' => $na_betid_arr,
'AE_BETID_ARR' => $ae_betid_arr,
'NU_BETID_ARR' => $nu_betid_arr,
'UE_BETID_ARR' => $ue_betid_arr,
'URL' => append_sid("admin_bookies.$phpEx?&amp;mode=process_na"),
));

include('./page_header_admin.'.$phpEx);

$template->pparse('body');

include('./page_footer_admin.'.$phpEx);

?>