<?php
/** 
*
* @package admin
* @version $Id: admin_bookies_setbet.php,v 3.0.0 2004/11/17 17:49:33 majorflam Exp $
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
	$module['Bookmakers']['Set_Bets'] = $filename;

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
	'body' => 'admin/admin_bookie_setbet.tpl')
);

//
// Include language
//
$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_admin_bookmakers.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_admin_bookmakers.' . $phpEx);

//
// Mode setting
//
if( isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']) )
{
	$mode = ( isset($HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
	$mode = htmlspecialchars($mode); 
}
else
{
	$mode = "";
}


if ( intval($HTTP_GET_VARS['expand']) < 1 )
{
	$redirect = '<meta http-equiv="refresh" content="2;url=' . append_sid("admin_bookies_setbet.$phpEx?") . '">';
}
else
{
	$redirect_expandid=intval($HTTP_GET_VARS['expand']);
	$redirect = '<meta http-equiv="refresh" content="2;url=' . append_sid("admin_bookies_setbet.$phpEx?expand=$redirect_expandid") . '#' . $redirect_expandid . '">';
}
$add_meeting_url=append_sid("admin_bookies_setbet.$phpEx?&amp;mode=new_meeting");

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

//
// Are we updating default date and category?
//
if ( isset($HTTP_POST_VARS['update_def_date']) )
{
	$min=( $HTTP_POST_VARS['def_minute'] != 'mm' ) ? intval($HTTP_POST_VARS['def_minute']) : 'mm';
	$hour=( $HTTP_POST_VARS['def_hour'] != 'hh' ) ? intval($HTTP_POST_VARS['def_hour']) : 'hh';
	$mon=( $HTTP_POST_VARS['def_month'] != 'MM' ) ? intval($HTTP_POST_VARS['def_month']) : 'MM';
	$day=( $HTTP_POST_VARS['def_day'] != 'DD' ) ? intval($HTTP_POST_VARS['def_day']) : 'DD';
	$year=( $HTTP_POST_VARS['def_year'] != 'YYYY' ) ? intval($HTTP_POST_VARS['def_year']) : 'YYYY';
	$cat=intval($HTTP_POST_VARS['def_cat']);
	
	$def_date_stamp=$hour . '*' . $min . '*' . $day . '*' . $mon . '*' . $year;
	
	$sql=" UPDATE " . CONFIG_TABLE . "
	SET config_value='$def_date_stamp'
	WHERE config_name='bookie_default_date'
	";
	if ( !$db->sql_query($sql) ) 
	{ 
		message_die(GENERAL_ERROR, 'Error updating default date', '', __LINE__, __FILE__, $sql); 
	}
	
	$sql=" UPDATE " . CONFIG_TABLE . "
	SET config_value='$cat'
	WHERE config_name='bookie_def_cat'
	";
	if ( !$db->sql_query($sql) ) 
	{ 
		message_die(GENERAL_ERROR, 'Error updating default date', '', __LINE__, __FILE__, $sql); 
	}
	
	$message=$lang['bookie_defdate_success'] . $redirect;
	message_die(GENERAL_MESSAGE, $message);
}

//
// Drop boxes
//
if ( $mode == 'edit' )
{
	$sql=" SELECT bet_time FROM " . BOOKIE_ADMIN_BETS_TABLE . "
	WHERE bet_id=" . intval($HTTP_GET_VARS['bet_id']);
	if ( !($result = $db->sql_query($sql)) ) 
	{ 
		message_die(GENERAL_ERROR, 'Error in retrieving timestamp', '', __LINE__, __FILE__, $sql); 
	}
	$row=$db->sql_fetchrow($result);
	$timestamp=$row['bet_time'];
	$board_config['bookie_default_date'] = create_date( "H*i*d*m*Y", $timestamp, $board_config['board_timezone'] );
}
$default_date=explode('*', $board_config['bookie_default_date']);
// hours
$selected='';
if ( $default_date[0] != 'hh' )
{
	$hour_box .= '<option value="hh">hh';
}
else
{
	$hour_box .= '<option value="hh" selected="selected">hh';
}
for ( $i=0; $i<24; $i++ )
{
	if ( $default_date[0] == $i && $default_date[0] != 'hh' )
	{
		$selected='selected="selected"';
	}
	else
	{
		$selected='';
	}
	$i_view = ( $i<10 ) ? '0' . $i : $i;
	$hour_box .= '<option value="' . $i . '" ' . $selected . '>' . $i_view;
}
$hour_box .= '</select>';

// minutes
if ( $default_date[1] != 'mm' )
{
	$min_box .= '<option value="mm">mm';
}
else
{
	$min_box .= '<option value="mm" selected="selected">mm';
}
for ( $i=0; $i<60; $i++ )
{
	if ( $default_date[1] == $i && $default_date[1] != 'mm' )
	{
		$selected='selected="selected"';
	}
	else
	{
		$selected='';
	}
	$i_view = ( $i<10 ) ? '0' . $i : $i;
	$min_box .= '<option value="' . $i . '" ' . $selected . '>' . $i_view;
}
$min_box .= '</select>';

// Days
if ( $default_date[2] != 'DD' )
{
	$day_box .= '<option value="DD">DD';
}
else
{
	$day_box .= '<option value="DD" selected="selected">DD';
}
for ( $i=1; $i<32; $i++ )
{
	if ( $default_date[2] == $i )
	{
		$selected='selected="selected"';
	}
	else
	{
		$selected='';
	}
	$i_view = ( $i<10 ) ? '0' . $i : $i;
	$day_box .= '<option value="' . $i . '" ' . $selected . '>' . $i_view;
}
$day_box .= '</select>';

// Months
if ( $default_date[3] != 'MM' )
{
	$month_box .= '<option value="MM">MM';
}
else
{
	$month_box .= '<option value="MM" selected="selected">MM';
}
for ( $i=1; $i<13; $i++ )
{
	if ( $default_date[3] == $i )
	{
		$selected='selected="selected"';
	}
	else
	{
		$selected='';
	}
	$i_view = ( $i<10 ) ? '0' . $i : $i;
	$month_box .= '<option value="' . $i . '" ' . $selected . '>' . $i_view;
}
$month_box .= '</select>';

// Years
if ( $default_date[4] != 'YYYY' )
{
	$year_box .= '<option value="YYYY">YYYY';
}
else
{
	$year_box .= '<option value="YYYY" selected="selected">YYYY';
}
for ( $i=2005; $i<2010; $i++ )
{
	if ( $default_date[4] == $i )
	{
		$selected='selected="selected"';
	}
	else
	{
		$selected='';
	}
	$year_box .= '<option value="' . $i . '" ' . $selected . '>' . $i;
}
$year_box .= '</select>';

// end drop boxes

//
// Categories
//
$cat_name=array();
$sql=" SELECT * FROM " . BOOKIE_CAT_TABLE . "
ORDER BY cat_name ASC
";
if ( !($result = $db->sql_query($sql)) ) 
{ 
	message_die(GENERAL_ERROR, 'Error in building existing bets', '', __LINE__, __FILE__, $sql); 
}
while ( $row = $db->sql_fetchrow($result) )
{
	$key=$row['cat_id'];
	$cat_name[$key]=$row['cat_name'];
	if ( $mode != 'edit' )
	{
	$selected=( $row['cat_id'] == $board_config['bookie_def_cat'] ) ? $row['cat_id'] . '" selected="selected' : $row['cat_id'];
	$cat_box .= '<option value="' . $selected . '">' . $row['cat_name'];
}
}
if ( $mode != 'edit' )
{
$cat_box .= '</select>';
}
$time_now=time();

if ( $mode == 'selection_review' )
{
	$review_id=intval($HTTP_GET_VARS['review_id']);
	$sql_expand=" SELECT * FROM " . BOOKIE_ADMIN_BETS_TABLE . "
	WHERE multi=$review_id
	OR bet_id=$review_id
	ORDER BY bet_selection ASC";
	if ( !($result_expand = $db->sql_query($sql_expand)) ) 
	{ 
		message_die(GENERAL_ERROR, 'Error in building existing bets', '', __LINE__, __FILE__, $sql_expand); 
	}
				
	$x=1;
	while ( $expand_data = $db->sql_fetchrow($result_expand) )
	{
		$bet_id=$expand_data['bet_id'];
		$bet_timestamp=$expand_data['bet_time'];
		$bet_meeting=$expand_data['bet_meeting'];
		$bet_selection=$expand_data['bet_selection'];
		$bet_odds1=$expand_data['odds_1'];
		$bet_odds2=$expand_data['odds_2'];
		$star_status=$expand_data['starbet'];
		
		// Convert date to viewable format
		$bet_time = create_date( $board_config['default_dateformat'], $bet_timestamp, $board_config['board_timezone'] );
		
		//
		// decimal odds?
		//
		if ( $board_config['bookie_frac_or_dec'] )
		{
			// convert fraction to decimal
			$odds_decimal=number_format( (($bet_odds1/$bet_odds2)+1), 2);
		}
				
		$row_color = ( !($x % 2) ) ? $theme['td_color1'] : $theme['td_color2']; 
		$row_class = ( !($x % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
				
		$template->assign_block_vars('betreview', array(
		'ROW_COLOR' => '#' . $row_color, 
		'ROW_CLASS' => $row_class,
		'TIME' => $bet_time,
		'MEETING' => $bet_meeting,
		'SELECTION' => $bet_selection,
		'ODDS' => ( $odds_decimal ) ? $odds_decimal : $bet_odds1 . '/' . $bet_odds2,
		'STAR_IMAGE' => ( $star_status ) ? '<img src="' . $phpbb_root_path . $images['icon_bookie_star'] . '" height="20" width="20" alt="' . $lang['bookie_star_alt'] . '" title="' . $lang['bookie_star_alt'] . '" />' : '',
		));
		$x++;
	}
	//
	// assign the template for the iframe
	//
	$template->set_filenames(array(
		'review_body' => 'admin/admin_bookies_selection_review_body.tpl')
	);

	$template->assign_vars(array(
		'TIME' => $lang['bookie_set_time'],
		'MEETING' => $lang['bookie_process_meeting'],
		'SELECTION' => $lang['bookie_process_selection'],
		'ODDS' => $lang['bookie_set_odds'])
	);
	
	include('./page_header_admin.'.$phpEx);
	
	$template->pparse('review_body');
	
	exit;
}

//
// Are we building?
//

if ( $mode == 'build' )
{
	include_once($phpbb_root_path . 'includes/bookies_build_bet.' . $phpEx);
}
//
// Are we creating a new meeting?
//
if ( $mode == 'new_meeting' )
{
	include_once($phpbb_root_path . 'includes/bookies_new_meeting.' . $phpEx);	
}

//Are we deleting
if ( $mode == 'delete' && !isset($HTTP_POST_VARS['cancel']) )
{
	include_once($phpbb_root_path . 'includes/bookies_delete_bet.' . $phpEx);	
}
//
// end deletion
//

//
// for editing
//
if ( $mode == 'edit' && !isset($HTTP_POST_VARS['cancel']) )
{
	include_once($phpbb_root_path . 'includes/bookies_edit_bet.' . $phpEx);
}
//
// end editing
//


//
// Build Table of set bets
//
$sql=" SELECT * FROM " . BOOKIE_ADMIN_BETS_TABLE . "
WHERE checked=0
AND bet_time>" . time() . "
AND multi=-1
ORDER BY bet_time ASC,bet_meeting ASC";
if ( !($result = $db->sql_query($sql)) ) 
{ 
	message_die(GENERAL_ERROR, 'Error in building existing bets', '', __LINE__, __FILE__, $sql); 
}
			
while ( $bet_data = $db->sql_fetchrow($result) )
{
	$bet_id=$bet_data['bet_id'];
	$bet_timestamp=$bet_data['bet_time'];
	$bet_meeting=$bet_data['bet_meeting'];
	$star_status=$bet_data['starbet'];
	$bet_cat=$bet_data['bet_cat'];
					
	// Convert date to viewable format
	$bet_time = create_date( $board_config['default_dateformat'], $bet_timestamp, $board_config['board_timezone'] );
	$expand_url=append_sid("admin_bookies_setbet.$phpEx?&amp;expand=$bet_id") . '#' . $bet_id;
	$drop_url=append_sid("admin_bookies_setbet.$phpEx?&amp;mode=delete&amp;bet_id=$bet_id&amp;type=all");
	$add_url=append_sid("admin_bookies_setbet.$phpEx?&amp;mode=build&amp;bet_id=$bet_id&amp;expand=$bet_id") . '#' . $bet_id;
					
	$template->assign_block_vars('processbet', array(
	'TIME' => $bet_time,
	'MEETING' => $bet_meeting,
	'STAR_IMAGE' => ( $star_status ) ? '<img src="' . $phpbb_root_path . $images['icon_bookie_star'] . '" height="15" width="15" alt="' . $lang['bookie_star_alt'] . '" title="' . $lang['bookie_star_alt'] . '" />' : '',
	'ADD_IMAGE' => '<a href="' . $add_url . '"><img src="' . $phpbb_root_path . $images['icon_bookie_add_selection'] . '" alt="' . $lang['icon_bookie_add_selection'] . '" title="' . $lang['icon_bookie_add_selection'] . '" /></a>',
	'DROP_IMAGE' => '<a href="' . $drop_url . '"><img src="' . $phpbb_root_path . $images['icon_bookie_drop_meeting'] . '" alt="' . $lang['icon_bookie_drop_meeting'] . '" title="' . $lang['icon_bookie_drop_meeting'] . '" /></a>',
	'EXPAND_URL' => $expand_url,
	'ANCHOR' => $bet_id,
	'CATEGORY' => $cat_name[$bet_cat],		
	));
	if ( isset($HTTP_GET_VARS['expand']) )
	{
		$expand_id=intval($HTTP_GET_VARS['expand']);
		if ( $expand_id == $bet_id )
		{
			$sql_expand=" SELECT * FROM " . BOOKIE_ADMIN_BETS_TABLE . "
			WHERE multi=$expand_id
			OR bet_id=$expand_id
			ORDER BY bet_selection ASC";
			if ( !($result_expand = $db->sql_query($sql_expand)) ) 
			{ 
				message_die(GENERAL_ERROR, 'Error in building existing bets', '', __LINE__, __FILE__, $sql_expand); 
			}
				
			$x=1;
			while ( $expand_data = $db->sql_fetchrow($result_expand) )
			{
			$bet_id=$expand_data['bet_id'];
			$bet_selection=$expand_data['bet_selection'];
			$bet_odds1=$expand_data['odds_1'];
			$bet_odds2=$expand_data['odds_2'];
			$star_status=$expand_data['starbet'];
			$bet_cat=$bet_data['bet_cat'];
			$edit_url=append_sid("admin_bookies_setbet.$phpEx?&amp;mode=edit&amp;bet_id=$bet_id&amp;expand=$expand_id") . '#' . $expand_id;
			$delete_url=append_sid("admin_bookies_setbet.$phpEx?&amp;mode=delete&amp;bet_id=$bet_id&amp;type=one&amp;expand=$expand_id") . '#' . $expand_id;
			
			$row_color = ( !($x % 2) ) ? $theme['td_color1'] : $theme['td_color2']; 
			$row_class = ( !($x % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
			
			//
			// decimal odds?
			//
			if ( $board_config['bookie_frac_or_dec'] )
			{
				// convert fraction to decimal
				$odds_decimal=number_format( (($bet_odds1/$bet_odds2)+1), 2);
			}
			
			$template->assign_block_vars('processbet.expansion', array(
			'ROW_COLOR' => '#' . $row_color, 
			'ROW_CLASS' => $row_class,
			'SELECTION' => $bet_selection,
			'ODDS' => ( $odds_decimal ) ? $odds_decimal : $bet_odds1 . '/' . $bet_odds2,
			'EDIT' => '<a href="' . $edit_url . '"><img src="' . $phpbb_root_path . $images['acp_edit'] . '" alt="' . $lang['Edit'] . '" title="' . $lang['Edit'] . '" /></a>',
			'DELETE' => '<a href="' . $delete_url . '"><img src="' . $phpbb_root_path . $images['acp_delete'] . '" alt="' . $lang['Delete'] . '" title="' . $lang['Delete'] . '" /></a>',
			'STAR_IMAGE' => ( $star_status ) ? '<img src="' . $phpbb_root_path . $images['icon_bookie_star'] . '" height="20" width="20" alt="' . $lang['bookie_star_alt'] . '" title="' . $lang['bookie_star_alt'] . '" />' : '',
			'CATEGORY' => $cat_name[$bet_cat],
			));
			$x++;
			}
		}		
	} 	
}
if ( $bet_id )
{
	$template->assign_block_vars('switch_bets_set', array());
}
else
{
	$template->assign_block_vars('switch_no_bets', array());
}	

// Set template Vars
$template->assign_vars(array(
'SET_HEADER_EXPLAIN' => $lang['bookie_set_header_explain'],
'SET_HEADER' => $lang['bookie_set_header'],
'TIME' => $lang['bookie_set_time'],
'MEETING' => $lang['bookie_process_meeting'],
'SELECTION' => $lang['bookie_process_selection'],
'ODDS' => $lang['bookie_set_odds'],
'INPUT' => $lang['bookie_set_input'],
'INPUT_EXPLAIN' => $lang['bookie_set_input_explain'],
'ENTER_DETAILS' => $lang['bookie_set_enterdetails'],
'TIME_MEETING' => $lang['bookie_slip_time'],
'TIME_MEETING_EXPLAIN' => $lang['bookie_slip_time_explain'],
'MEETING_EXPLAIN' => $lang['bookie_slip_meeting_explain'],
'SELECTION_EXPLAIN' => $lang['bookie_slip_selection_explain'],
'ENTER_ODDS_BET' => $lang['bookie_set_enterodds'],
'ENTER_ODDS_EXPLAIN' => $lang['bookie_set_enterodds_explain'],
'SUBMIT' => $lang['bookie_set_submitbuton'],
'BETHOUR_BOX' => $hour_box,
'BETMINUTE_BOX' => $min_box,
'DAY_BOX' => $day_box,
'MONTH_BOX' => $month_box,
'YEAR_BOX' => $year_box,
'BUILDER_INPUT' => $lang['bookie_build_input'],
'BUILDER_MEETING_EXPLAIN' => $lang['bookie_build_meet_explain'],
'MEETING_BOX' => $builder_namebox,
'EDIT_DELETE' => $lang['bookie_edit_delete_bet'],
'BUILDER_INPUT_REMINDER' => $lang['bookie_builder_input_reminder'],
'INPUT_REMINDER' => $lang['bookie_input_reminder'],
'CURRENT_BETS' => $lang['bookie_current_bets'],
'CURRENT_BETS_EXPLAIN' => $lang['bookie_current_bets_explain'],
'STAR_BET' => $lang['bookie_starbet'],
'STAR_BET_EXP' => $lang['bookie_starbet_exp'],
'L_YES' => $lang['Yes'],
'L_NO' => $lang['No'],
'NO_BETS' => $lang['bookie_nobets'],
'INFORMATION' => $lang['Information'],
'IMG_NEW_MEETING' => '<a href="' . $add_meeting_url . '"><img src="' . $phpbb_root_path . $images['icon_bookie_add_meeting'] . '" alt="' . $lang['icon_bookie_add_meeting'] . '" title="' . $lang['icon_bookie_add_meeting'] . '" /></a>',
'CATEGORY' => $lang['bookie_category'],
'DEF_DATE_EXPLAIN' => $lang['bookie_def_date_explain'],
'DEFAULT_DATE' => $lang['bookie_def_date'],
'DEFAULT_DATE_EXPLAIN' => $lang['bookie_slip_time_explain'],
'UPDATE' => $lang['Update'],
'CATEGORY_BOX' => $cat_box,
'DEFAULT_CAT' => $lang['bookie_def_cat'],
'DEFAULT_CAT_EXPLAIN' => $lang['bookie_def_cat_exp'],
'DEFAULT_VARS' => $lang['bookie_default_vars'],
'BOOKIE_VERSION' => $board_config['bookie_version'],
));

include('./page_header_admin.'.$phpEx);

$template->pparse('body');

include('./page_footer_admin.'.$phpEx);

?>