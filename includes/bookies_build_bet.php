<?php
/** 
*
* @package includes
* @version $Id: bookies_build_bet.php,v 2.0.6 2004/11/17 17:49:33 majorflam Exp $
* @copyright (c) 2004 Majorflam
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

if ( !defined('IN_PHPBB') )
{
	die('Hacking attempt');
}

$bet_id = ( isset($HTTP_GET_VARS['bet_id']) ) ? intval($HTTP_GET_VARS['bet_id']) : '';
$bet_time = intval($HTTP_GET_VARS['time']);
$new_meeting = intval($HTTP_GET_VARS['meeting']);
$new_star = intval($HTTP_GET_VARS['star']);
$new_eachway = intval($HTTP_GET_VARS['eachway']);
$expand_id = ( intval($HTTP_GET_VARS['expand']) > 0 ) ? intval($HTTP_GET_VARS['expand']) : 0;
$bet_category = intval($HTTP_GET_VARS['category']);
	
if ( empty($HTTP_POST_VARS['use_templ']) && empty($HTTP_POST_VARS['no_templ']) && !isset($HTTP_GET_VARS['field_count']) )
{
	$template->set_filenames(array(
		'body' => 'admin/admin_bookie_setbet_new_meeting2.tpl')
	);
						
	$url = ( $bet_id ) ? append_sid("admin_bookies_setbet.$phpEx?&amp;mode=build&amp;bet_id=$bet_id&amp;expand=$expand_id") . '#' . $expand_id : append_sid("admin_bookies_setbet.$phpEx?&amp;mode=build&amp;time=$bet_time&amp;meeting=$new_meeting&amp;star=$new_star&amp;eachway=$new_eachway&amp;category=$bet_category");
	
	//
	// Now we need to build a selection template box
	//
	$sql = "SELECT * 
		FROM " . BOOKIE_SELECTIONS_TABLE . "
		ORDER BY selection_name ASC";
	if ( !($result = $db->sql_query($sql)) ) 
	{ 
		message_die(GENERAL_ERROR, 'Error in gathering existing bet data', '', __LINE__, __FILE__, $sql); 
	}
	
	$templ_box .= '<option value="" selected="selected">' . $lang['bookie_template_select'] . '';
	
	while ( $row=$db->sql_fetchrow($result) )
	{
		$templ_box .= '<option value="' . $row['selection_id'] . '">' . $row['selection_name'];
	}
	$templ_box .='</select>';
			
	$template->assign_vars(array(
		'URL' => $url,
		'CONFIRM_SELECTION_TYPE' => $lang['bookie_confirm_selection_type'],
		'CONFIRM_HEADER' => $lang['bookie_confirm_selection_type'],
		'CONFIRM_EXPLAIN' => $lang['bookie_confirm_selection_type_head_exp'],
		'HARDCODE' => $lang['bookie_hardcode'],
		'HARDCODE_EXPLAIN' => $lang['bookie_hardcode_exp'],
		'TEMPL' => $lang['bookie_template'],
		'TEMPL_EXPLAIN' => $lang['bookie_template_exp'],
		'TEMPL_SELECT' => $templ_box,
		'DEF_ODDS' => $lang['bookie_def_odds'],
		'DEF_ODDS_EXPLAIN' => $lang['bookie_def_odds_exp'],
		'DEF_ODDS_INPUT' => ( !$board_config['bookie_frac_or_dec'] ) ? '<input class="post" type="text" style="width: 50px" maxlength="5" size="11" name="def_odds1" />&nbsp;/&nbsp;<input class="post" type="text" style="width: 50px" maxlength="5" size="11" name="def_odds2" />' : '<input class="post" type="text" style="width: 75px" maxlength="8" size="11" name="def_odds_dec" />')
	);
}
else if (!isset($HTTP_GET_VARS['field_count']))
{
	$use_templ = intval($HTTP_POST_VARS['use_templ']);
	$no_templ = intval($HTTP_POST_VARS['no_templ']);
	$def_odds1 = (!empty($HTTP_POST_VARS['def_odds1'])) ? intval($HTTP_POST_VARS['def_odds1']) : '';
	$def_odds2 = (!empty($HTTP_POST_VARS['def_odds2'])) ? intval($HTTP_POST_VARS['def_odds2']) : '';
	$def_odds_dec = (!empty($HTTP_POST_VARS['def_odds_dec'])) ? htmlspecialchars($HTTP_POST_VARS['def_odds_dec']) : '';
	
	if ($no_templ && $use_templ)
	{
		$no_templ = '';
	}
	
	if ($no_templ)
	{
		$url = ($bet_id) ? append_sid("admin_bookies_setbet.$phpEx?&amp;mode=build&amp;bet_id=$bet_id&amp;field_count=$no_templ&amp;expand=$expand_id") . '#' . $expand_id : append_sid("admin_bookies_setbet.$phpEx?&amp;mode=build&amp;time=$bet_time&amp;meeting=$new_meeting&amp;star=$new_star&amp;field_count=$no_templ&amp;eachway=$new_eachway&amp;category=$bet_category");
		
		//
		// create a template switch with the selected amount of input fields
		//
		for ($x=1; $x < ($no_templ + 1); $x++)
		{
			$row_color = ( !($x % 2) ) ? $theme['td_color1'] : $theme['td_color2']; 
			$row_class = ( !($x % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
			
			$odds1_name = 'odds1_' . $x;
			$odds2_name = 'odds2_' . $x;
			$dec_odds_name = 'odds_dec_' . $x;
					
			$template->assign_block_vars('selections', array(
				'ROW_COLOR' => '#' . $row_color, 
				'ROW_CLASS' => $row_class,
				'SELECTION_NAME' => 'selection_' . $x,
				'ODDS' => (!$board_config['bookie_frac_or_dec']) ? '<input class="post" type="text" style="width: 50px" maxlength="5" size="11" name="' . $odds1_name . '" value="' . $def_odds1 . '" />&nbsp;/&nbsp;<input class="post" type="text" style="width: 50px" maxlength="5" size="11" name="' . $odds2_name . '" value="' . $def_odds2 . '" />' : '<input class="post" type="text" style="width: 75px" maxlength="8" size="11" name="' . $dec_odds_name . '" value="' . $def_odds_dec . '" />')
			);
		}
	}
	else if ( $use_templ )
	{
		$sql = "SELECT selection 
			FROM " . BOOKIE_SELECTIONS_DATA_TABLE . "
			WHERE selection_id = $use_templ";
		if ( !($result = $db->sql_query($sql)) ) 
		{ 
			message_die(GENERAL_ERROR, 'Error in gathering existing bet data', '', __LINE__, __FILE__, $sql); 
		}
		
		$selection_value = array();
		$x = 1;
		
		while ( $row=$db->sql_fetchrow($result) )
		{
			$row_color = ( !($x % 2) ) ? $theme['td_color1'] : $theme['td_color2']; 
			$row_class = ( !($x % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
			
			$odds1_name = 'odds1_' . $x;
			$odds2_name = 'odds2_' . $x;
			$dec_odds_name = 'odds_dec_' . $x;
			$selection_value[] = $row['selection'];
		
			$template->assign_block_vars('selections', array(
				'ROW_COLOR' => '#' . $row_color, 
				'ROW_CLASS' => $row_class,
				'SELECTION_NAME' => 'selection_' . $x,
				'ODDS' => ( !$board_config['bookie_frac_or_dec'] ) ? '<input class="post" type="text" style="width: 50px" maxlength="5" size="11" name="' . $odds1_name . '" value="' . $def_odds1 . '" />&nbsp;/&nbsp;<input class="post" type="text" style="width: 50px" maxlength="5" size="11" name="' . $odds2_name . '" value="' . $def_odds2 . '" />' : '<input class="post" type="text" style="width: 75px" maxlength="8" size="11" name="' . $dec_odds_name . '" value="' . $def_odds_dec . '" />',
				'SELECTION_VALUE' => $row['selection'])
			);
			$x++;	
		}
		
		$selection_count = sizeof($selection_value);
		$url = ($bet_id) ? append_sid("admin_bookies_setbet.$phpEx?&amp;mode=build&amp;bet_id=$bet_id&amp;field_count=$selection_count&amp;expand=$expand_id") . '#' . $expand_id : append_sid("admin_bookies_setbet.$phpEx?&amp;mode=build&amp;time=$bet_time&amp;meeting=$new_meeting&amp;star=$new_star&amp;field_count=$selection_count&amp;eachway=$new_eachway&amp;category=$bet_category");
	}
	
	$template->set_filenames(array(
		'body' => 'admin/admin_bookie_multiple_bet.tpl',
		'reviewbody' => 'admin/admin_bookies_selection_review.tpl')
	);
			
	$template->assign_vars(array(
		'URL' => $url,
		'BUILD_HEADER' => $lang['bookie_build_header'],
		'BUILD_EXPLAIN' => $lang['bookie_build_header_exp'],
		'L_SELECTION_REVIEW' => $lang['bookie_selection_review_head'],
		'U_SELECTION_REVIEW' => append_sid("admin_bookies_setbet.$phpEx?&amp;mode=selection_review&amp;review_id=$bet_id"))
	);
	
	if ($bet_id)
	{
		$template->assign_var_from_handle('SELECTION_REVIEW', 'reviewbody');
	}
}
else
{
	//
	// we got all the data so let's process!
	//
			
	// for building onto existing bet
	if ($bet_id)
	{
		// get the vars
		$fields = intval($HTTP_GET_VARS['field_count']);
		$create_multi = $bet_id;
		for ($i = 1; $i < ($fields + 1); $i++)
		{
			$selection_var = 'selection_' . $i;
			$odds1_var = 'odds1_' . $i;
			$odds2_var = 'odds2_' . $i;
			$odds_dec_var = 'odds_dec_' . $i;
			$selection = htmlspecialchars($HTTP_POST_VARS[$selection_var]);
			$odds1_placed = intval($HTTP_POST_VARS[$odds1_var]);
			$odds2_placed = intval($HTTP_POST_VARS[$odds2_var]);
			$odds_dec_placed = htmlspecialchars($HTTP_POST_VARS[$odds_dec_var]);
			
			if ( $board_config['bookie_frac_or_dec'] && !empty($odds_dec_placed) )
			{
				$odds2_placed = 100;
				//
				// make it numerical
				//
				$enter_odds_dec_int = intval($odds_dec_placed * 100);
				// now convert to odds1 value
				$enter_odds_dec = ($enter_odds_dec_int / 100) - 1;
				// now multply again to get the default fractional value for the database
				$odds1_placed = $enter_odds_dec * 100;
			}
			if ( $selection && $odds1_placed && $odds2_placed )
			{
				//
				// process the bets
				//					
				$sql = "SELECT bet_time, bet_cat, bet_meeting, starbet, each_way 
					FROM " . BOOKIE_ADMIN_BETS_TABLE . "
					WHERE bet_id = " . $create_multi;
				if ( !($result = $db->sql_query($sql)) ) 
				{ 
					message_die(GENERAL_ERROR, 'Error in gathering existing bet data', '', __LINE__, __FILE__, $sql); 
				}
				$row = $db->sql_fetchrow($result);
				
				$multi_bet_time = $row['bet_time'];
				$multi_bet_meeting = $row['bet_meeting'];
				$this_starbet = $row['starbet'];
				$this_eachway = $row['each_way'];
				$this_category = $row['bet_cat'];
				
				$sql = "INSERT INTO " . BOOKIE_ADMIN_BETS_TABLE . " (bet_time, bet_cat, bet_meeting, bet_selection, odds_1, odds_2, multi, starbet, each_way) 
					VALUES ($multi_bet_time, $this_category, '" . addslashes($multi_bet_meeting) . "', '" . str_replace("\'", "''", $selection) . "', $odds1_placed, $odds2_placed, $create_multi, '$this_starbet', '$this_eachway')";
				if (!$db->sql_query($sql))
				{ 
					message_die(GENERAL_ERROR, 'Error in entering bet details into the DB', '', __LINE__, __FILE__, $sql); 
				}
			}
		}

		$message = $lang['bookie_multiple_success'] . $redirect;

		message_die(GENERAL_MESSAGE, $message);
	}
	else if ( $bet_time )
	{
		//
		// First, we create an array of odds and selections
		//
		$selection_input = $odds1_input = $odds2_input = $odds_dec_input = array();
		$fields = intval($HTTP_GET_VARS['field_count']);
		
		for ($i = 1; $i < ($fields + 1); $i++)
		{
			$selection_var = 'selection_' . $i;
			$odds1_var = 'odds1_' . $i;
			$odds2_var = 'odds2_' . $i;
			$odds_dec_var = 'odds_dec_' . $i;
			$selection_inputed = htmlspecialchars($HTTP_POST_VARS[$selection_var]);
			$odds1_inputed = intval($HTTP_POST_VARS[$odds1_var]);
			$odds2_inputed = intval($HTTP_POST_VARS[$odds2_var]);
			$odds_dec_inputed = htmlspecialchars($HTTP_POST_VARS[$odds_dec_var]);
			
			if ( $board_config['bookie_frac_or_dec'] && !empty($odds_dec_inputed) )
			{
				$odds2_inputed = 100;
				//
				// make it numerical
				//
				$enter_odds_dec_int = intval($odds_dec_inputed*100);
				// now convert to odds1 value
				$enter_odds_dec = ($enter_odds_dec_int / 100) - 1;
				// now multply again to get the default fractional value for the database
				$odds1_inputed = $enter_odds_dec * 100;
			}
			if ( $selection_inputed && $odds1_inputed && $odds2_inputed )
			{
				$selection_input[] = $selection_inputed;
				$odds1_input[] = $odds1_inputed;
				$odds2_input[] = $odds2_inputed;
			}
		}
		
		//
		// Now, we need to create the admin base bet, including the first selection and odds
		//
		// need to grab the data for the meeting
		$sql = "SELECT meeting 
			FROM " . BOOKIE_MEETINGS_TABLE . "
			WHERE meeting_id = $new_meeting";
		if ( !($result = $db->sql_query($sql)) ) 
		{ 
			message_die(GENERAL_ERROR, 'Error in gathering existing bet data', '', __LINE__, __FILE__, $sql); 
		}
		$row = $db->sql_fetchrow($result);
		
		$bet_meeting_placed = $row['meeting'];
		$bet_selection_placed = $selection_input[0];
		$odds1_placed = $odds1_input[0];
		$odds2_placed = $odds2_input[0];
		$star_bet = $new_star;
		$eachway = $new_eachway;
		
		$bet_timestamp_placed = $bet_time - ($board_config['board_timezone'] * 3600);

		$sql = "INSERT INTO " . BOOKIE_ADMIN_BETS_TABLE . " (bet_time, bet_cat, bet_meeting, bet_selection, odds_1, odds_2, starbet, each_way) 
			VALUES ($bet_timestamp_placed, $bet_category, '" . addslashes($bet_meeting_placed) . "', '" . str_replace("\'", "''", $bet_selection_placed) . "', $odds1_placed, $odds2_placed, $star_bet, $eachway)";
		if (!$db->sql_query($sql))
		{ 
			message_die(GENERAL_ERROR, 'Error in entering bet details into the DB', '', __LINE__, __FILE__, $sql); 
		}
		
		//
		// Insert the bet setter
		//
		if ( $board_config['bookie_allow_commission'] )
		{
			$sql = "SELECT bet_id 
				FROM " . BOOKIE_ADMIN_BETS_TABLE . "
				WHERE bet_time = $bet_timestamp_placed
					AND bet_meeting = '" . addslashes($bet_meeting_placed) . "'
					AND bet_selection = '" . str_replace("\'", "''", $bet_selection_placed) . "'
					AND odds_1 = $odds1_placed
					AND odds_2 = $odds2_placed";
			if ( !($result = $db->sql_query($sql)) ) 
			{ 
				message_die(GENERAL_ERROR, 'Error in gathering existing bet data', '', __LINE__, __FILE__, $sql); 
			}
			$row = $db->sql_fetchrow($result);
		
			$setter_id = $row['bet_id'];
			
			$sql = "INSERT INTO " . BOOKIE_BET_SETTER_TABLE . " (bet_id, setter) 
				VALUES ($setter_id, " . $userdata['user_id'] . ")";
			if ( !$db->sql_query($sql) ) 
			{ 
				message_die(GENERAL_ERROR, 'Error inserting bet setter', '', __LINE__, __FILE__, $sql); 
			}
		}
		
		//
		// end placing base bet
		//
		//
		// now we place all other selections
		//
		if (sizeof($selection_input) > 1)
		{
			//
			// Grab the admin bet id
			//
			$sql = "SELECT bet_id 
				FROM " . BOOKIE_ADMIN_BETS_TABLE . "
				WHERE bet_time = $bet_timestamp_placed
					AND bet_meeting = '" . addslashes($bet_meeting_placed) . "'
					AND bet_selection = '" . str_replace("\'", "''", $bet_selection_placed) . "'
					AND odds_1 = $odds1_placed
					AND odds_2 = $odds2_placed";
			if ( !($result = $db->sql_query($sql)) ) 
			{ 
				message_die(GENERAL_ERROR, 'Error in gathering existing bet data', '', __LINE__, __FILE__, $sql); 
			}
			$row = $db->sql_fetchrow($result);
			
			$create_multi = $row['bet_id'];
			$redirect = '<meta http-equiv="refresh" content="2;url=' . append_sid("admin_bookies_setbet.$phpEx?expand=$create_multi") . '#' . $create_multi . '">';
			
			for ($i=1; $i < sizeof($selection_input); $i++)
			{
				$selection = $selection_input[$i];
				$odds1_placed = $odds1_input[$i];
				$odds2_placed = $odds2_input[$i];;
	
				$sql = "INSERT INTO " . BOOKIE_ADMIN_BETS_TABLE . " (bet_time, bet_cat, bet_meeting, bet_selection, odds_1, odds_2, multi, starbet, each_way) 
					VALUES ($bet_timestamp_placed, $bet_category, '" . addslashes($bet_meeting_placed) . "', '" . str_replace("\'", "''", $selection) . "', $odds1_placed, $odds2_placed, $create_multi, '$star_bet', '$eachway')";
				if (!$db->sql_query($sql))
				{ 
					message_die(GENERAL_ERROR, 'Error in entering bet details into the DB', '', __LINE__, __FILE__, $sql); 
				}
			}				
		}				

		$message = $lang['bookie_multiple_success'] . $redirect;

		message_die(GENERAL_MESSAGE, $message);
	}		
}

?>