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

$bet_edit=intval($HTTP_GET_VARS['bet_id']);
$bet_edited=htmlspecialchars($HTTP_GET_VARS['bet_edited']);
$expand_id=intval($HTTP_GET_VARS['expand']);

//
// Redirect and grab the details if we haven't done so already
//
if ( !$bet_edited )
{
	$template->set_filenames(array(
		'body' => 'admin/admin_bookie_setbet_edit.tpl',
		'reviewbody' => 'admin/admin_bookies_selection_review.tpl')
	);

	//
	// get data for this bet
	//
	$sql = "SELECT * 
		FROM " . BOOKIE_ADMIN_BETS_TABLE . "
		WHERE bet_id = $bet_edit";
	if ( !($result = $db->sql_query($sql)) ) 
	{ 
		message_die(GENERAL_ERROR, 'Error in building existing bets', '', __LINE__, __FILE__, $sql); 
	}
	
	$x = 1;
	while ( $bet_data = $db->sql_fetchrow($result) )
	{
		$bet_id = $bet_data['bet_id'];
		$bet_timestamp = $bet_data['bet_time'];
		$bet_meeting = '<textarea name="meeting"style="width: 150px" rows="3" cols="10" class="post">' . $bet_data['bet_meeting'] . '</textarea>';
		$bet_selection = '<textarea name="selection"style="width: 150px" rows="5" cols="10" class="post">' . $bet_data['bet_selection'] . '</textarea>';
		$odds_1 = $bet_data['odds_1'];
		$odds_2 = $bet_data['odds_2'];
		$this_starbet = $bet_data['starbet'];
		$this_eachwaybet = $bet_data['each_way'];
		$multi_id = $bet_data['multi'];
	
		if ( $x==1 )
		{
			//
			// create cat box
			//
			$sql_a = "SELECT * 
				FROM " . BOOKIE_CAT_TABLE . "
				ORDER BY cat_name ASC";
			if ( !($result_a = $db->sql_query($sql_a)) ) 
			{ 
				message_die(GENERAL_ERROR, 'Error in building category box', '', __LINE__, __FILE__, $sql_a); 
			}

			while ( $row_a = $db->sql_fetchrow($result_a) )
			{
				$selected = ( $row_a['cat_id'] == $bet_data['bet_cat'] ) ? $row_a['cat_id'] . '" selected="selected' : $row_a['cat_id'];
				$cat_box .= '<option value="' . $selected . '">' . $row_a['cat_name'];
			}
			$cat_box .= '</select>';
		}
				
		// Convert date to viewable format
		$bet_time = create_date( $board_config['default_dateformat'], $bet_timestamp, $board_config['board_timezone'] );
		
		$row_color = ( !($x % 2) ) ? $theme['td_color1'] : $theme['td_color2']; 
		$row_class = ( !($x % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
		
		if ( $board_config['bookie_frac_or_dec'] )
		{
			// convert fraction to decimal
			$odds_decimal = round((($odds_1 / $odds_2) + 1), 2);
		}
		
		$template->assign_block_vars('editbet', array(
			'ROW_COLOR' => '#' . $row_color, 
			'ROW_CLASS' => $row_class,
			'BETID' => $bet_id,
			'CURRENT_TIME' => $bet_time,
			'MEETING' => $bet_meeting,
			'SELECTION' => $bet_selection,
			'ODDS' => ( !$odds_decimal ) ? '<input class="post" type="text" style="width: 50px" maxlength="5" size="11" name="enter_odds1" value="' . $odds_1 . '" /><b>&nbsp;/&nbsp;</b><input class="post" type="text" style="width: 50px" maxlength="5" size="11" name="enter_odds2" value="' . $odds_2 . '" />' : '<input class="post" type="text" style="width: 75px" maxlength="8" size="11" name="enter_odds_dec" value="' . $odds_decimal . '" />',
			'BETHOUR_BOX' => $hour_box,
			'BETMINUTE_BOX' => $min_box,
			'DAY_BOX' => $day_box,
			'MONTH_BOX' => $month_box,
			'YEAR_BOX' => $year_box,
			'STAR_BET' => $lang['bookie_starbet'],
			'STAR_BET_EXP' => $lang['bookie_starbet_exp'],
			'EACHWAY_BET' => $lang['bookie_eachwaybet'],
			'EACHWAY_BET_EXP' => $lang['bookie_eachwaybet_exp'],
			'L_YES' => $lang['Yes'],
			'L_NO' => $lang['No'],
			'STAR_BET_ON' => ( $this_starbet ) ? 'checked="checked"' : '',
			'STAR_BET_OFF' => ( !$this_starbet ) ? 'checked="checked"' : '',
			'EACHWAY_BET_ON' => ( $this_eachwaybet ) ? 'checked="checked"' : '',
			'EACHWAY_BET_OFF' => ( !$this_eachwaybet ) ? 'checked="checked"' : '',
			'CATEGORY' => $lang['bookie_category'],
			'CATEGORY_EXPLAIN' => $lang['bookie_category_exp'],
			'CATEGORY_BOX' => $cat_box)
		); 
		$x++;
			
		if ( $board_config['bookie_eachway'] )
		{
			$template->assign_block_vars('editbet.eachway_allowed', array());
		}
	}

	//
	// set variables
	//
	$template->assign_vars(array(
		'TIME' => $lang['bookie_set_time'],
		'MEETING' => $lang['bookie_process_meeting'],
		'SELECTION' => $lang['bookie_process_selection'],
		'ODDS' => $lang['bookie_set_odds'],
		'SUBMIT' => $lang['bookie_set_submitbuton'],
		'EDIT_HEADER' => $lang['bookie_edit_header'],
		'EDIT_HEADER_EXPLAIN' => $lang['bookie_edit_header_explain'],
		'URL' => append_sid("admin_bookies_setbet.$phpEx?&amp;mode=edit&amp;bet_id=$bet_id&amp;bet_edited=1&amp;expand=$expand_id") . '#' . $expand_id,
		'CANCEL' => $lang['bookie_cancel'],
		'L_SELECTION_REVIEW' => $lang['bookie_selection_review_head'],
		'U_SELECTION_REVIEW' => ( $multi_id == -1 ) ? append_sid("admin_bookies_setbet.$phpEx?&amp;mode=selection_review&amp;review_id=$bet_edit") : append_sid("admin_bookies_setbet.$phpEx?&amp;mode=selection_review&amp;review_id=$multi_id"))
	);
		
	$template->assign_var_from_handle('SELECTION_REVIEW', 'reviewbody');
	
	//
	// Parse
	//
	include('./page_header_admin.'.$phpEx);
		
	$template->pparse('body');
		
	include('./page_footer_admin.'.$phpEx);
	exit;
}

//
// update the DB
//
	
$time_now = time();
$bet_selection_placed = htmlspecialchars($HTTP_POST_VARS['selection']);

if ( $board_config['bookie_frac_or_dec'] )
{
	$odds2_placed = 100;
	$enter_odds_dec = htmlspecialchars($HTTP_POST_VARS['enter_odds_dec']);

	//
	// make it numerical
	//
	$enter_odds_dec_int = intval($enter_odds_dec * 100);
	// now convert to odds1 value
	$enter_odds1 = ($enter_odds_dec_int / 100) - 1;
	// now multply again to get the default fractional value for the database
	$odds1_placed = $enter_odds1 * 100;
}
else
{
	$odds1_placed = intval($HTTP_POST_VARS['enter_odds1']);
	$odds2_placed = intval($HTTP_POST_VARS['enter_odds2']);
}

$bet_time_placed = intval($HTTP_POST_VARS['bet_hour']) . ':' . intval($HTTP_POST_VARS['bet_minute']);
$bet_date_placed = intval($HTTP_POST_VARS['bet_day']) . '-' . intval($HTTP_POST_VARS['bet_month']) . '-' . intval($HTTP_POST_VARS['bet_year']);
$bet_meeting_placed = htmlspecialchars($HTTP_POST_VARS['meeting']);
$this_starbet = intval($HTTP_POST_VARS['starbet']);
$this_eachway = intval($HTTP_POST_VARS['eachwaybet']);
$this_category = intval($HTTP_POST_VARS['edit_category']);

if (empty($bet_time_placed) && empty($bet_date_placed) && empty($bet_meeting_placed) && empty($bet_selection_placed) && empty($odds1_placed) && empty($odds2_placed) )
{
	$message = $lang['bookies_need_field'] . $redirect;
	message_die(GENERAL_MESSAGE, $message);
}

if ( !empty($bet_time_placed) && empty($bet_date_placed) )
{
	$message = $lang['bookies_need_time_date'] . $redirect;
	message_die(GENERAL_MESSAGE, $message);
}

if ( empty($bet_time_placed) && !empty($bet_date_placed) )
{
	$message = $lang['bookies_need_time_date'] . $redirect;
	message_die(GENERAL_MESSAGE, $message);
}

$sql = "SELECT * 
	FROM " . BOOKIE_ADMIN_BETS_TABLE . "
	WHERE bet_id = $bet_edit";
if ( !($result = $db->sql_query($sql)) )
{ 
	message_die(GENERAL_ERROR, 'Error in updating bet details in the DB', '', __LINE__, __FILE__, $sql); 
}
$row = $db->sql_fetchrow($result);

$multi = $row['multi'];
$bet_meeting = $row['bet_meeting'];
$bet_timestamp = $row['bet_time'];
$bet_selection = $row['bet_selection'];
$bet_odds1 = $row['odds_1'];
$bet_odds2 = $row['odds_2'];
			
if ( !empty($bet_meeting_placed) )
{
	$bet_meeting = $bet_meeting_placed;
}

if ( !empty($HTTP_POST_VARS['bet_month']) && !empty($HTTP_POST_VARS['bet_day']) && !empty($HTTP_POST_VARS['bet_year']))
{
	// get timestamp
	$getdate = explode('-', $bet_date_placed);
	$gettime = explode(':', $bet_time_placed);
	$timestamp = gmmktime(intval($gettime[0]), intval($gettime[1]), 0, intval($getdate[1]), intval($getdate[0]), intval($getdate[2])); 
	$bet_timestamp = $timestamp - ($board_config['board_timezone'] * 3600);
	// make sure the time is valid
	$timenow = time();
	if ( $bet_timestamp < $timenow )
	{
		$message = $lang['bookies_invalid_date'] . $redirect;
		message_die(GENERAL_MESSAGE, $message);
	}
}
						
$sql = "UPDATE " . BOOKIE_ADMIN_BETS_TABLE . "
	SET bet_meeting = '" . str_replace("\'", "''", $bet_meeting) . "', bet_time = $bet_timestamp, starbet = '$this_starbet', each_way = '$this_eachway', bet_cat = $this_category
	WHERE bet_id = $bet_edit";
if (!$db->sql_query($sql))
{ 
	message_die(GENERAL_ERROR, 'Error in updating bet details in the DB', '', __LINE__, __FILE__, $sql); 
}

$sql = "UPDATE " . BOOKIE_BETS_TABLE . "
	SET meeting = '" . str_replace("\'", "''", $bet_meeting) . "', time = $bet_timestamp, bet_cat = $this_category
	WHERE admin_betid = $bet_edit";
if (!$db->sql_query($sql))
{ 
	message_die(GENERAL_ERROR, 'Error in updating bet details in the DB', '', __LINE__, __FILE__, $sql); 
}

if ( $multi == -1 )
{
	$sql = "UPDATE " . BOOKIE_ADMIN_BETS_TABLE . "
		SET bet_meeting = '" . str_replace("\'", "''", $bet_meeting) . "', bet_time = $bet_timestamp, starbet = '$this_starbet', each_way = '$this_eachway', bet_cat = $this_category
		WHERE multi = $bet_edit";
	if (!$db->sql_query($sql))
	{ 
		message_die(GENERAL_ERROR, 'Error in updating bet details in the DB', '', __LINE__, __FILE__, $sql); 
	}

	$sql = "SELECT bet_id 
		FROM " . BOOKIE_ADMIN_BETS_TABLE . "
		WHERE multi = $bet_edit";
	if ( !($result = $db->sql_query($sql)) )
	{ 
		message_die(GENERAL_ERROR, 'Error in updating bet details in the DB', '', __LINE__, __FILE__, $sql); 
	}

	while ( $row = $db->sql_fetchrow($result) )
	{
		$normal_id = $row['bet_id'];
		
		$sql = "UPDATE " . BOOKIE_BETS_TABLE . "
			SET meeting = '" . str_replace("\'", "''", $bet_meeting) . "', time = $bet_timestamp, bet_cat = $this_category
			WHERE admin_betid = $normal_id";
		if (!$db->sql_query($sql))
		{ 
			message_die(GENERAL_ERROR, 'Error in updating bet details in the DB', '', __LINE__, __FILE__, $sql); 
		}
	}
}

if ( $multi > 0 )
{
	$sql = "UPDATE " . BOOKIE_ADMIN_BETS_TABLE . "
		SET bet_meeting = '" . str_replace("\'", "''", $bet_meeting) . "', bet_time = $bet_timestamp, starbet = '$this_starbet', each_way = '$this_eachway', bet_cat = $this_category
		WHERE multi = $multi";
	if (!$db->sql_query($sql))
	{ 
		message_die(GENERAL_ERROR, 'Error in updating bet details in the DB', '', __LINE__, __FILE__, $sql); 
	}

	$sql = "UPDATE " . BOOKIE_ADMIN_BETS_TABLE . "
		SET bet_meeting = '" . str_replace("\'", "''", $bet_meeting) . "', bet_time = $bet_timestamp, starbet = '$this_starbet', each_way = '$this_eachway', bet_cat = $this_category
		WHERE bet_id = $multi";
	if (!$db->sql_query($sql))
	{ 
		message_die(GENERAL_ERROR, 'Error in updating bet details in the DB', '', __LINE__, __FILE__, $sql); 
	}

	$sql = "SELECT bet_id 
		FROM " . BOOKIE_ADMIN_BETS_TABLE . "
		WHERE multi = '$multi'";
	if ( !($result = $db->sql_query($sql)) )
	{ 
		message_die(GENERAL_ERROR, 'Error in updating bet details in the DB', '', __LINE__, __FILE__, $sql); 
	}

	while ( $row = $db->sql_fetchrow($result) )
	{
		$normal_id = $row['bet_id'];
		
		$sql = "UPDATE " . BOOKIE_BETS_TABLE . "
			SET meeting = '" . str_replace("\'", "''", $bet_meeting) . "', time = $bet_timestamp, bet_cat = $this_category
			WHERE admin_betid = $normal_id";
		if (!$db->sql_query($sql))
		{ 
			message_die(GENERAL_ERROR, 'Error in updating bet details in the DB', '', __LINE__, __FILE__, $sql); 
		}
	}

	$sql = "SELECT bet_id 
		FROM " . BOOKIE_ADMIN_BETS_TABLE . "
		WHERE bet_id = '$multi'";
	if ( !($result = $db->sql_query($sql)) )
	{ 
		message_die(GENERAL_ERROR, 'Error in updating bet details in the DB', '', __LINE__, __FILE__, $sql); 
	}
	$row = $db->sql_fetchrow($result);

	$base_id = $row['bet_id'];

	$sql = "UPDATE " . BOOKIE_BETS_TABLE . "
		SET meeting = '" . str_replace("\'", "''", $bet_meeting) . "', time = $bet_timestamp, bet_cat = $this_category
		WHERE admin_betid = $base_id";
	if (!$db->sql_query($sql))
	{ 
		message_die(GENERAL_ERROR, 'Error in updating bet details in the DB', '', __LINE__, __FILE__, $sql); 
	}
}
// end meeting/time update

// start selection/odds update
if ( !empty($bet_selection_placed) )
{
	$bet_selection = $bet_selection_placed;
}
			
if ( !empty($odds1_placed) && !empty($odds2_placed) )
{
	$bet_odds1 = $odds1_placed;
	$bet_odds2 = $odds2_placed;
}

$sql="UPDATE " . BOOKIE_ADMIN_BETS_TABLE . "
	SET bet_selection = '" . str_replace("\'", "''", $bet_selection) . "', odds_1 = $bet_odds1, odds_2 = $bet_odds2
	WHERE bet_id = $bet_edit";
if (!$db->sql_query($sql))
{ 
	message_die(GENERAL_ERROR, 'Error in updating bet details in the DB', '', __LINE__, __FILE__, $sql); 
}
						
$sql="UPDATE " . BOOKIE_BETS_TABLE . "
	SET selection = '" . str_replace("\'", "''", $bet_selection) . "', odds_1 = $bet_odds1, odds_2 = $bet_odds2
	WHERE admin_betid = $bet_edit";
if (!$db->sql_query($sql))
{ 
	message_die(GENERAL_ERROR, 'Error in updating bet details in the DB', '', __LINE__, __FILE__, $sql); 
}
// end updates		

$redirect = '<meta http-equiv="refresh" content="2;url=' . append_sid("admin_bookies_setbet.$phpEx?expand=$expand_id") . '#' . $expand_id . '">';

$message = $lang['bookie_succesful_edit'] . $redirect;

message_die(GENERAL_MESSAGE, $message);

?>