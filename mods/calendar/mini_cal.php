<?php
/***************************************************************************
 *                             mini_cal.php
 *                            -------------------
 *   Author  		: 	netclectic - Adrian Cockburn - phpbb@netclectic.com
 *   Created 		: 	Sunday, July 14, 2002
 *	 Last Updated	:	Thursday, March 25, 2004
 *
 *	 Version		: 	MINI_CAL - 2.0.4
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

if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}

define ('IN_MINI_CAL', 1);

include_once($phpbb_root_path . 'mods/calendar/mini_cal_config.'.$phpEx);
include_once($phpbb_root_path . 'mods/calendar/mini_cal_common.'.$phpEx);
include_once($phpbb_root_path . 'mods/calendar/calendarSuite.'.$phpEx);

// get the mode (if any)
if( isset($HTTP_GET_VARS['mode']) || isset($HTTP_POST_VARS['mode']) )
{
	$mini_cal_mode = ( isset($HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
}
$mini_cal_mode = ($mini_cal_mode == 'personal') ? $mini_cal_mode : 'default';
    
// get the user (for personal calendar)
if( isset($HTTP_GET_VARS[POST_USERS_URL]) || isset($HTTP_POST_VARS[POST_USERS_URL]) )
{
	$mini_cal_user = ( isset($HTTP_POST_VARS[POST_USERS_URL]) ) ? intval($HTTP_POST_VARS[POST_USERS_URL]) : intval($HTTP_GET_VARS[POST_USERS_URL]);
}
    
// get the calendar month
$mini_cal_month = 0;
if( isset($HTTP_GET_VARS['month']) || isset($HTTP_POST_VARS['month']) )
{
	$mini_cal_month = ( isset($HTTP_POST_VARS['month']) ) ? intval($HTTP_POST_VARS['month']) : intval($HTTP_GET_VARS['month']);
}

// initialise our calendarsuite class
$mini_cal = new calendarSuite();
    
// setup our mini_cal template
$template->set_filenames(array(
	'mini_cal_body' => 'calendar_mini_body.tpl')
);

// initialise some variables
$mini_cal_today = create_date('Ymd', time(), $board_config['board_timezone']);
$s_cal_month = ($mini_cal_month != 0) ? $mini_cal_month . ' month' : $mini_cal_today;
$mini_cal->getMonth($s_cal_month);
$mini_cal_count = MINI_CAL_FDOW;
$mini_cal_this_year = $mini_cal->dateYYYY;
$mini_cal_this_month = $mini_cal->dateMM;
$mini_cal_this_day = $mini_cal->dateDD;
$mini_cal_month_days = $mini_cal->daysMonth;

if ( MINI_CAL_CALENDAR_VERSION != 'NONE' )
{
	// include the required events calendar support
    $mini_cal_inc = 'mini_cal_' . MINI_CAL_CALENDAR_VERSION;
    include_once($phpbb_root_path . 'mods/calendar/' . $mini_cal_inc . '.' . $phpEx);
    
    // include the required events calendar support
    $mini_cal_auth = getMiniCalForumsAuth($userdata);
    $mini_cal_event_days = getMiniCalEventDays($mini_cal_auth['view']);
    getMiniCalEvents($mini_cal_auth);
	getMiniCalPostForumsList($mini_cal_auth['post']);
}
    
// output the days for the current month 
// if MINI_CAL_DATE_SEARCH = POSTS then hyperlink any days which have already past
// if MINI_CAL_DATE_SEARCH = EVENTS then hyperkink any which have events
for ($i = 0; $i < $mini_cal_month_days;) 
{
	// is this the first day of the week?
	if ($mini_cal_count == MINI_CAL_FDOW)
	{
		$template->assign_block_vars('mini_cal_row', array());
	}
        
    // is this a valid weekday?
	if ($mini_cal_count == ($mini_cal->day[$i][7])) 
	{
    	$mini_cal_this_day = $mini_cal->day[$i][0];
        
    	$d_mini_cal_today = $mini_cal_this_year . ( ($mini_cal_this_month <= 9) ? '0' . $mini_cal_this_month : $mini_cal_this_month ) . ( ($mini_cal_this_day <= 9) ? '0' . $mini_cal_this_day : $mini_cal_this_day );
        $mini_cal_day = ( $mini_cal_today == $d_mini_cal_today ) ? '<b class="' . MINI_CAL_TODAY_CLASS . '">'.$mini_cal_this_day.'</b>' : $mini_cal_this_day;

        if ( (MINI_CAL_CALENDAR_VERSION != 'NONE') && (MINI_CAL_DATE_SEARCH == 'EVENTS') )
        {
        	$mini_cal_day_link = '<a href="' . getMiniCalSearchURL($d_mini_cal_today) . '" class="' . MINI_CAL_DAY_LINK_CLASS . '" title="' . $lang['Search'] . '">' . ( $mini_cal_day ) . '</a>';
        	$mini_cal_day = ( in_array($mini_cal_this_day, $mini_cal_event_days) ) ? $mini_cal_day_link : $mini_cal_day;
        }
        else
        {
        	$nix_mini_cal_today = gmmktime($board_config['board_timezone'], 0, 0, $mini_cal_this_month, $mini_cal_this_day, $mini_cal_this_year);
            $mini_cal_day_link = '<a href="' . append_sid($phpbb_root_path . "search.$phpEx?search_id=mini_cal&amp;d=" . $nix_mini_cal_today) . '" class="' . MINI_CAL_DAY_LINK_CLASS . '" title="' . $lang['Search'] . '">' . ( $mini_cal_day ) . '</a>';
            $mini_cal_day = ( $mini_cal_today >= $d_mini_cal_today ) ? $mini_cal_day_link : $mini_cal_day;
		}
            
		$template->assign_block_vars('mini_cal_row.mini_cal_days', array(
			'MINI_CAL_DAY' => $mini_cal_day)
		); 
		$i++;
	} 
    // no day
	else 
	{
		$template->assign_block_vars('mini_cal_row.mini_cal_days', array(
			'MINI_CAL_DAY' => ' ')
		); 
	}

    // is this the last day of the week?
    if ($mini_cal_count == 6)
    {
    	// if so then reset the count
        $mini_cal_count = 0;
	}
    else
    {
    	// otherwise increment the count
        $mini_cal_count++;
	}
}
	
// output our general calendar bits
$prev_qs = setQueryStringVal('month', $mini_cal_month -1);
$next_qs = setQueryStringVal('month', $mini_cal_month +1);
$prev_month = '<a href="' . append_sid($HTTP_SERVER_VARS["PHP_SELF"] .  $prev_qs) . '" class="gen"><img src="' . $images['icon_left_arrow'] . '" title="' . $lang['View_previous_month'] . '" alt="&lt;&lt;" /></a>';
$next_month = '<a href="' . append_sid($HTTP_SERVER_VARS["PHP_SELF"] .  $next_qs) . '" class="gen"><img src="' . $images['icon_right_arrow'] . '" title="' . $lang['View_next_month'] . '" alt="&gt;&gt;" /></a>';
$template->assign_vars(array(
	'L_MINI_CAL_MONTH' => $lang['mini_cal']['long_month'][$mini_cal->day[0][4]] . ' ' . $mini_cal->day[0][5],
	'L_MINI_CAL_ADD_EVENT' => $lang['Mini_Cal_add_event'],
	'L_MINI_CAL_CALENDAR' => $lang['Calendar'], 
	'L_MINI_CAL_EVENTS' => $lang['Mini_Cal_events'], 
    'L_MINI_CAL_NO_EVENTS' => $lang['None'],
	'L_MINI_CAL_SUN' => $lang['mini_cal']['day'][1], 
	'L_MINI_CAL_MON' => $lang['mini_cal']['day'][2], 
	'L_MINI_CAL_TUE' => $lang['mini_cal']['day'][3], 
	'L_MINI_CAL_WED' => $lang['mini_cal']['day'][4], 
	'L_MINI_CAL_THU' => $lang['mini_cal']['day'][5], 
	'L_MINI_CAL_FRI' => $lang['mini_cal']['day'][6], 
	'L_MINI_CAL_SAT' => $lang['mini_cal']['day'][7], 
	
	'U_MINI_CAL_CALENDAR' => append_sid($phpbb_root_path . 'mycalendar.'.$phpEx),
	'U_MINI_CAL_ADD_EVENT' => append_sid($phpbb_root_path . 'posting.'.$phpEx . '?mode=newtopic&amp;' . POST_FORUM_URL . '=' . MINI_CAL_EVENTS_FORUM), 
    'U_PREV_MONTH' => $prev_month,
	'U_NEXT_MONTH' => $next_month)
);
    
// check for birthdays mod
if ( isset($template->_tpldata['.'][0]['L_WHOSBIRTHDAY_TODAY']) || isset($template->vars['L_WHOSBIRTHDAY_TODAY']) )
{
	$template->assign_block_vars('switch_mini_cal_birthdays', array());
}
    
// finally assign our mini_cal stuff to the template engine for output
$template->assign_var_from_handle('MINI_CAL_OUTPUT', 'mini_cal_body');

?>