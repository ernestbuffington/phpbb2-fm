<?php
/***************************************************************************
 *                              bookie_edit_bet.php
 *                            --------------------------
 *		Version			: 2.0.6
 *		Email			: majorflam@majormod.com
 *		Site			: http://www.majormod.com
 *		Copyright		: Majorflam 2004/5 
 *
 ***************************************************************************/
 
if ( !defined('IN_PHPBB') )
{
	die('Hacking attempt');
}

$template->set_filenames(array(
	'body' => 'admin/admin_bookie_setbet_new_meeting.tpl')
);

$url = append_sid("admin_bookies_setbet.$phpEx?&amp;mode=new_meeting");
		
if ( $board_config['bookie_eachway'] )
{
	$template->assign_block_vars('eachway_allowed', array());
}
		
if ( isset($HTTP_POST_VARS['submit']) && !isset($HTTP_GET_VARS['time']) )
{
	$redirect = '<meta http-equiv="refresh" content="2;url=' . $url . '">';
	$bet_time_placed = intval($HTTP_POST_VARS['bet_hour']) . ':' . intval($HTTP_POST_VARS['bet_minute']);
	$bet_date_placed = intval($HTTP_POST_VARS['bet_day']) . '-' . intval($HTTP_POST_VARS['bet_month']) . '-' . intval($HTTP_POST_VARS['bet_year']);//$HTTP_POST_VARS['bet_date'];
	$bet_meeting_placed = htmlspecialchars($HTTP_POST_VARS['bet_meeting']);
	$bet_meeting_selected = intval($HTTP_POST_VARS['selected_meeting']);
	$selected_cat = intval($HTTP_POST_VARS['selected_category']);
	$star_bet = intval($HTTP_POST_VARS['starbet']);
	$eachway = intval($HTTP_POST_VARS['eachwaybet']);
		
	if ( empty($HTTP_POST_VARS['bet_day']) || empty($HTTP_POST_VARS['bet_month']) || empty($HTTP_POST_VARS['bet_year']) )
	{
		$message = $lang['bookies_notall_fileds'] . $redirect;
		message_die(GENERAL_MESSAGE, $message);
	}
	if  ( empty($bet_meeting_placed) && empty($bet_meeting_selected) )
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
    // convert time back to a "clean" timestamp 
    // 
    $bet_timestamp_placed = $timestamp;
	
	//
	// OK we got all data, so let's check to see if a new meeting was input. If so, we'll remember it
	//
	if ( $bet_meeting_placed )
	{
		$sql = "SELECT meeting_id 
			FROM " . BOOKIE_MEETINGS_TABLE . "
			WHERE meeting = '" . str_replace("\'", "''", $bet_meeting_placed) . "'
			LIMIT 1";
		if ( !($result = $db->sql_query($sql)) ) 
		{ 
			message_die(GENERAL_ERROR, 'Error in checking meeting', '', __LINE__, __FILE__, $sql); 
		}
		$row = $db->sql_fetchrow($result);
		
		if ( !$row )
		{
			$sql = "INSERT INTO " . BOOKIE_MEETINGS_TABLE . " (meeting) 
				VALUES ('" . str_replace("\'", "''", $bet_meeting_placed) . "')";
			if (!$db->sql_query($sql))
			{ 
				message_die(GENERAL_ERROR, 'Error in entering new meeting into the DB', '', __LINE__, __FILE__, $sql); 
			}
					
			$sql = "SELECT meeting_id 
				FROM " . BOOKIE_MEETINGS_TABLE . "
				WHERE meeting = '" . str_replace("\'", "''", $bet_meeting_placed) . "'";
			if ( !($result = $db->sql_query($sql)) ) 
			{ 
				message_die(GENERAL_ERROR, 'Error in checking meeting', '', __LINE__, __FILE__, $sql); 
			}
			$row = $db->sql_fetchrow($result);

			$meeting_selected = $row['meeting_id'];
		}
		else
		{
			$meeting_selected = $row['meeting_id'];
		}
	}
	else
	{
		$meeting_selected = $bet_meeting_selected;
	}
	
	//
	// Set a new url to direct to step 2 and set the new template plus required vars
	//
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
			
	$url = append_sid("admin_bookies_setbet.$phpEx?&amp;mode=build&amp;time=$bet_timestamp_placed&amp;meeting=$meeting_selected&amp;star=$star_bet&amp;eachway=$eachway&amp;category=$selected_cat");
	
	$template->set_filenames(array(
		'body' => 'admin/admin_bookie_setbet_new_meeting2.tpl')
	);
}

//
// Create a bet meeting box
//
if ( !isset($HTTP_POST_VARS['submit']) && !isset($HTTP_GET_VARS['time']) )
{
	$sql = "SELECT * 
		FROM " . BOOKIE_MEETINGS_TABLE . "
		ORDER BY meeting ASC";
	if ( !($result = $db->sql_query($sql)) ) 
	{ 
		message_die(GENERAL_ERROR, 'Error in building meeting box', '', __LINE__, __FILE__, $sql); 
	}
	
	$meeting_box .= '<option value="" selected="selected">' . $lang['bookie_select_meeting'] . '';
	while ( $row = $db->sql_fetchrow($result) )
	{
		$meeting_box .= '<option value="' . $row['meeting_id'] . '">' . $row['meeting'];
	}
	$meeting_box .= '</select>';
}
				
$template->assign_vars(array(
	'BETHOUR_BOX' => $hour_box,
	'BETMINUTE_BOX' => $min_box,
	'DAY_BOX' => $day_box,
	'MONTH_BOX' => $month_box,
	'YEAR_BOX' => $year_box,
	'SUBMIT' => $lang['bookie_set_submitbuton'],
	'TIME_MEETING' => $lang['bookie_slip_time'],
	'TIME_MEETING_EXPLAIN' => $lang['bookie_slip_time_explain'],
	'ENTER_DETAILS' => $lang['bookie_set_enterdetails'],
	'MEETING' => $lang['bookie_process_meeting'],
	'MEETING_EXPLAIN' => $lang['bookie_slip_meeting_explain'],
	'URL' => $url,
	'STAR_BET' => $lang['bookie_starbet'],
	'STAR_BET_EXP' => $lang['bookie_starbet_exp'],
	'EACHWAY_BET' => $lang['bookie_eachwaybet'],
	'EACHWAY_BET_EXP' => $lang['bookie_eachwaybet_exp'],
	'L_YES' => $lang['Yes'],
	'L_NO' => $lang['No'],
	'MEETING_BOX' => $meeting_box,
	'HARDCODE' => $lang['bookie_hardcode'],
	'HARDCODE_EXPLAIN' => $lang['bookie_hardcode_exp'],
	'TEMPL' => $lang['bookie_template'],
	'TEMPL_EXPLAIN' => $lang['bookie_template_exp'],
	'CONFIRM_HEADER' => $lang['bookie_confirm_selection_type'],
	'CONFIRM_EXPLAIN' => $lang['bookie_confirm_selection_type_head_exp'],
	'TEMPL_SELECT' => $templ_box,
	'HEADER' => $lang['bookie_new_meeting_header'],
	'EXPLAIN' => $lang['bookie_new_meeting_header_exp'],
	'CATEGORY' => $lang['bookie_category'],
	'CATEGORY_EXPLAIN' => $lang['bookie_category_exp'],
	'CATEGORY_BOX' => $cat_box,
	'DEF_ODDS' => $lang['bookie_def_odds'],
	'DEF_ODDS_EXPLAIN' => $lang['bookie_def_odds_exp'],
	'DEF_ODDS_INPUT' => ( !$board_config['bookie_frac_or_dec'] ) ? '<input class="post" type="text" style="width: 50px" maxlength="5" size="11" name="def_odds1" />&nbsp;/&nbsp;<input class="post" type="text" style="width: 50px" maxlength="5" size="11" name="def_odds2" />' : '<input class="post" type="text" style="width: 75px" maxlength="8" size="11" name="def_odds_dec" />')
);
		
include('./page_header_admin.'.$phpEx);
		
$template->pparse('body');
		
include('./page_footer_admin.'.$phpEx);
exit;

?>