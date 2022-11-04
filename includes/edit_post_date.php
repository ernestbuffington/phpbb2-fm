<?php
/***************************************************************************
 *						  edit_post_date.php
 *					 	  -------------------
 *   begin                : Sunday, December 01, 2002
 *   copyright            : (C) 2002 ErDrRon
 *   email                : ErDrRon@aol.com
 *
 *   $Id: edit_post_date.php,v 1.0.0 12/01/2002, 16:32:00 erdrron Exp $
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

define('IN_PHPBB', true);
$phpbb_root_path = '../';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.' . $phpEx);

// include language file
$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_edit_post_date.'.$phpEx) )
{
	$language = 'english';
}
include($phpbb_root_path . 'language/lang_' . $language . '/lang_edit_post_date.' . $phpEx);


//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_POSTING);
init_userprefs($userdata);
//
// End session management
//

if ( !$userdata['session_logged_in'] ) 
{ 
	redirect("login.".$phpEx."?redirect=index.".$phpEx); 
	exit; 
} 


//
// Pull current Post time for the Post in question
//
$sql = "SELECT post_time 
	FROM " . POSTS_TABLE . " 
	WHERE post_id = " . $_REQUEST['p'];
if( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not obtain post_time.', '', __LINE__, __FILE__, $sql);
}

$row = $db->sql_fetchrow($result);
$current_post_date = $row['post_time'];
$new_post_date = '';

//
// Initialize variables
//
if( isset($HTTP_POST_VARS['submit']) )
{ 
	if( checkdate( month_to_int($_REQUEST['edit_month']), $_REQUEST['edit_day'], $_REQUEST['edit_year'] ) )
	{
		$valid_date = '';
					
		if( $_REQUEST['edit_ampm'] == 'pm' && $_REQUEST['edit_hour'] < '12' ) 
		{
			$_REQUEST['edit_hour'] += 12;
		}
		if( $_REQUEST['edit_ampm'] == 'am' && $_REQUEST['edit_hour'] == '12' ) 
		{
			$_REQUEST['edit_hour'] = '00';
		}
		
		$enter_new_post_date = strtotime($_REQUEST['edit_day'] . ' ' . $_REQUEST['edit_month'] . ' ' . $_REQUEST['edit_year'] . ' ' . $_REQUEST['edit_hour'] . ':' . $_REQUEST['edit_minute']);

		$sql = "UPDATE " . POSTS_TABLE . " 
			SET post_time = " . $enter_new_post_date . " 
			WHERE post_id = " . $_REQUEST['p'];
		if( !$db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Failed to update new post_time.', '', __LINE__, __FILE__, $sql);
		}

		echo "<script language=\"JavaScript\">";
		echo "window.close()";
		echo "</script>";
	}
	else
	{
		$valid_date = $lang['Edit_post_invalid_date'];
	}
}

//
// Determine current day field
//
if(isset($HTTP_GET_VARS['day']) || isset($HTTP_POST_VARS['day']))
{
	$day_field = (isset($HTTP_POST_VARS['day'])) ? $HTTP_POST_VARS['day'] : $HTTP_GET_VARS['day'];
}
else
{
	$day_field = date("d", $row['post_time']);
}

//
// Determine current month field
//
if(isset($HTTP_GET_VARS['month']) || isset($HTTP_POST_VARS['month']))
{
	$month_field = (isset($HTTP_POST_VARS['month'])) ? $HTTP_POST_VARS['month'] : $HTTP_GET_VARS['month'];
}
else
{
	$month_field = date("F", $row['post_time']);
}

//
// Determine current year field
//
if(isset($HTTP_GET_VARS['year']) || isset($HTTP_POST_VARS['year']))
{
	$year_field = (isset($HTTP_POST_VARS['year'])) ? $HTTP_POST_VARS['year'] : $HTTP_GET_VARS['year'];
}
else
{
	$year_field = date("Y", $row['post_time']);
}

//
// Determine current hour field
//
if(isset($HTTP_GET_VARS['hour']) || isset($HTTP_POST_VARS['hour']))
{
	$hour_field = (isset($HTTP_POST_VARS['hour'])) ? $HTTP_POST_VARS['hour'] : $HTTP_GET_VARS['hour'];
}
else
{
	$hour_field = date("h", $row['post_time']);
}

//
// Determine current minute field
//
if(isset($HTTP_GET_VARS['minute']) || isset($HTTP_POST_VARS['minute']))
{
	$minute_field = (isset($HTTP_POST_VARS['minute'])) ? $HTTP_POST_VARS['minute'] : $HTTP_GET_VARS['minute'];
}
else
{
	$minute_field = date("i", $row['post_time']);
}

//
// Determine current ampm field
//
if(isset($HTTP_GET_VARS['ampm']) || isset($HTTP_POST_VARS['ampm']))
{
	$ampm_field = (isset($HTTP_POST_VARS['ampm'])) ? $HTTP_POST_VARS['ampm'] : $HTTP_GET_VARS['ampm'];
}
else
{
	$ampm_field = date("a", $row['post_time']);
}

//
// Assign month fields
//
$month_fields_text = array(
	$lang['datetime']['January'], 
	$lang['datetime']['February'], 
	$lang['datetime']['March'], 
	$lang['datetime']['April'],
	$lang['datetime']['May'], 
	$lang['datetime']['June'], 
	$lang['datetime']['July'], 
	$lang['datetime']['August'],
	$lang['datetime']['September'], 
	$lang['datetime']['October'], 
	$lang['datetime']['November'],
	$lang['datetime']['December']
);

$month_fields = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');

//
// Assign day fields
//
$day_fields_text = array(
	$lang['datetime']['01'], 
	$lang['datetime']['02'], 
	$lang['datetime']['03'], 
	$lang['datetime']['04'], 
	$lang['datetime']['05'],
	$lang['datetime']['06'], 
	$lang['datetime']['07'], 
	$lang['datetime']['08'], 
	$lang['datetime']['09'], 
	$lang['datetime']['10'],
	$lang['datetime']['11'], 
	$lang['datetime']['12'], 
	$lang['datetime']['13'], 
	$lang['datetime']['14'], 
	$lang['datetime']['15'],
	$lang['datetime']['16'], 
	$lang['datetime']['17'], 
	$lang['datetime']['18'], 
	$lang['datetime']['19'], 
	$lang['datetime']['20'],
	$lang['datetime']['21'], 
	$lang['datetime']['22'], 
	$lang['datetime']['23'], 
	$lang['datetime']['24'], 
	$lang['datetime']['25'],
	$lang['datetime']['26'], 
	$lang['datetime']['27'], 
	$lang['datetime']['28'], 
	$lang['datetime']['29'], 
	$lang['datetime']['30'],
	$lang['datetime']['31']
);

$day_fields = array(01, 02, 03, 04, 05, 06, 07, 08, 09, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31);

//
// Assign year fields
//
$year_fields_text = array(
	$lang['datetime']['1995'], 
	$lang['datetime']['1996'], 
	$lang['datetime']['1997'], 
	$lang['datetime']['1998'], 
	$lang['datetime']['1999'],
	$lang['datetime']['2000'], 
	$lang['datetime']['2001'], 
	$lang['datetime']['2002'], 
	$lang['datetime']['2003'], 
	$lang['datetime']['2004'],
	$lang['datetime']['2005'], 
	$lang['datetime']['2006'], 
	$lang['datetime']['2007'], 
	$lang['datetime']['2008'], 
	$lang['datetime']['2009'],
	$lang['datetime']['2010'],
	$lang['datetime']['2011'],
	$lang['datetime']['2012'],
	$lang['datetime']['2013']
);

$year_fields = array(1995, 1996, 1997, 1998, 1999, 2000, 2001, 2002, 2003, 2004, 2005, 2006, 2007, 2008, 2009, 2010, 2011, 2012, 2013);

//
// Assign hour fields
//
$hour_fields_text = array(
	$lang['datetime']['01'], 
	$lang['datetime']['02'], 
	$lang['datetime']['03'], 
	$lang['datetime']['04'], 
	$lang['datetime']['05'],
	$lang['datetime']['06'], 
	$lang['datetime']['07'], 
	$lang['datetime']['08'], 
	$lang['datetime']['09'], 
	$lang['datetime']['10'],
	$lang['datetime']['11'], 
	$lang['datetime']['12']
);

$hour_fields = array(01, 02, 03, 04, 05, 06, 07, 08, 09, 10, 11, 12);

//
// Assign minute fields
//
$minute_fields_text = array(
	$lang['datetime']['00'], 
	$lang['datetime']['01'], 
	$lang['datetime']['02'], 
	$lang['datetime']['03'], 
	$lang['datetime']['04'],
	$lang['datetime']['05'], 
	$lang['datetime']['06'], 
	$lang['datetime']['07'], 
	$lang['datetime']['08'], 
	$lang['datetime']['09'],
	$lang['datetime']['10'], 
	$lang['datetime']['11'], 
	$lang['datetime']['12'], 
	$lang['datetime']['13'], 
	$lang['datetime']['14'],
	$lang['datetime']['15'], 
	$lang['datetime']['16'], 
	$lang['datetime']['17'], 
	$lang['datetime']['18'], 
	$lang['datetime']['19'],
	$lang['datetime']['20'], 
	$lang['datetime']['21'], 
	$lang['datetime']['22'], 
	$lang['datetime']['23'], 
	$lang['datetime']['24'],
	$lang['datetime']['25'], 
	$lang['datetime']['26'], 
	$lang['datetime']['27'], 
	$lang['datetime']['28'], 
	$lang['datetime']['29'],
	$lang['datetime']['30'], 
	$lang['datetime']['31'], 
	$lang['datetime']['32'], 
	$lang['datetime']['33'], 
	$lang['datetime']['34'],
	$lang['datetime']['35'], 
	$lang['datetime']['36'], 
	$lang['datetime']['37'], 
	$lang['datetime']['38'], 
	$lang['datetime']['39'],
	$lang['datetime']['40'], 
	$lang['datetime']['41'], 
	$lang['datetime']['42'], 
	$lang['datetime']['43'], 
	$lang['datetime']['44'],
	$lang['datetime']['45'], 
	$lang['datetime']['46'], 
	$lang['datetime']['47'], 
	$lang['datetime']['48'], 
	$lang['datetime']['49'],
	$lang['datetime']['50'], 
	$lang['datetime']['51'], 
	$lang['datetime']['52'], 
	$lang['datetime']['53'], 
	$lang['datetime']['54'],
	$lang['datetime']['55'], 
	$lang['datetime']['56'], 
	$lang['datetime']['57'], 
	$lang['datetime']['58'], 
	$lang['datetime']['59']
);

$minute_fields = array(00, 01, 02, 03, 04, 05, 06, 07, 08, 09, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, 59);

//
// Assign ampm fields
//
$ampm_fields_text = array(
	$lang['datetime']['am'], 
	$lang['datetime']['pm']
);

$ampm_fields = array('am', 'pm');

//
// Set month field
//
if (count($month_fields_text) > 0)
{
	$select_month_field = '<select name="edit_month">';

	for($i = 0; $i < count($month_fields_text); $i++)
	{
		$selected = ($month_field == $month_fields[$i]) ? ' selected="selected"' : '';
		$select_month_field .= '<option value="' . $month_fields[$i] . '"' . $selected . '>' . $month_fields_text[$i] . '</option>';
	}

	$select_month_field .= '</select>';
}

//
// Set day field
//
if (count($day_fields_text) > 0)
{
	$select_day_field = '<select name="edit_day">';

	for($i = 0; $i < count($day_fields_text); $i++)
	{
		$selected = ($day_field == $day_fields[$i]) ? ' selected="selected"' : '';
		$select_day_field .= '<option value="' . $day_fields[$i] . '"' . $selected . '>' . $day_fields_text[$i] . '</option>';
	}

	$select_day_field .= '</select>';
}

//
// Set year field
//
if (count($year_fields_text) > 0)
{
	$select_year_field = '<select name="edit_year">';

	for($i = 0; $i < count($year_fields_text); $i++)
	{
		$selected = ($year_field == $year_fields[$i]) ? ' selected="selected"' : '';
		$select_year_field .= '<option value="' . $year_fields[$i] . '"' . $selected . '>' . $year_fields_text[$i] . '</option>';
	}

	$select_year_field .= '</select>';
}

//
// Set hour field
//
if (count($hour_fields_text) > 0)
{
	$select_hour_field = '<select name="edit_hour">';

	for($i = 0; $i < count($hour_fields_text); $i++)
	{
		$selected = ($hour_field == $hour_fields[$i]) ? ' selected="selected"' : '';
		$select_hour_field .= '<option value="' . $hour_fields[$i] . '"' . $selected . '>' . $hour_fields_text[$i] . '</option>';
	}

	$select_hour_field .= '</select>';
}

//
// Set minute field
//
if (count($minute_fields_text) > 0)
{
	$select_minute_field = '<select name="edit_minute">';

	for($i = 0; $i < count($minute_fields_text); $i++)
	{
		$selected = ($minute_field == $minute_fields[$i]) ? ' selected="selected"' : '';
		$select_minute_field .= '<option value="' . $minute_fields[$i] . '"' . $selected . '>' . $minute_fields_text[$i] . '</option>';
	}

	$select_minute_field .= '</select>';
}

//
// Set ampm field
//
if (count($ampm_fields_text) > 0)
{
	$select_ampm_field = '<select name="edit_ampm">';

	for($i = 0; $i < count($ampm_fields_text); $i++)
	{
		$selected = ($ampm_field == $ampm_fields[$i]) ? ' selected="selected"' : '';
		$select_ampm_field .= '<option value="' . $ampm_fields[$i] . '"' . $selected . '>' . $ampm_fields_text[$i] . '</option>';
	}

	$select_ampm_field .= '</select>';
}

//
// Build arrays and assign page template
//
$gen_simple_header = true;
include($phpbb_root_path . 'includes/page_header.'.$phpEx);
$template->set_filenames(array(
	'body' => 'edit_post_date_body.tpl')
);

//
// The following assigns all _template_ variables that may be used at any point in a template.
//
$template->assign_vars(array(
	'L_EDIT_POST_DATE_TITLE' => $lang['Edit_post_date_title'],
	'L_EDIT_POST_DATE_EXPLAIN' => $lang['Edit_post_date_explain'],
	'L_EDIT_POST_ID' => $lang['Edit_post_id'],
	'L_CURRENT_POST_DATE' => $lang['Current_post_date'],
	'L_NEW_POST_DATE' => $lang['New_post_date'],
	'L_CHANGE_POST_DATE' => $lang['Change_post_date'],
	'L_CLOSE_WINDOW' => $lang['Close_window'],

	'S_MONTH_SELECT' => $select_month_field,
	'S_DAY_SELECT' => $select_day_field,
	'S_YEAR_SELECT' => $select_year_field,
	'S_HOUR_SELECT' => $select_hour_field,
	'S_MINUTE_SELECT' => $select_minute_field,
	'S_AMPM_SELECT' => $select_ampm_field,
			
	'EDIT_POST_DATE_ICON' => '<img src="' . $phpbb_root_path . 'templates/' . ( ( $theme['image_cfg'] ) ? $theme['template_name'] . '/images/' . $theme['image_cfg'] : $theme['template_name'] . '/images' ) . '/edit_post_date_icon.gif" alt="" title="" />',
	'POST_ID' => $_REQUEST['p'],
	'CURRENT_POST_DATE' => create_date($board_config['default_dateformat'], $current_post_date, $board_config['board_timezone']),
	'EDIT_POST_VALID_DATE' => $valid_date)
);

$template->pparse('body');

function month_to_int($mon) 
{
	$months = array(
		'January' => 1,
		'February' => 2,
		'March' => 3,
		'April' => 4,
		'May' => 5,
		'June' => 6,
		'July' => 7,
		'August' => 8,
		'September' => 9,
		'October' => 10,
		'November' => 11,
		'December' => 12
	);
	
	return($months[$mon]);
}

?>