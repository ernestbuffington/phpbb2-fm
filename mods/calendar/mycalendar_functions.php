<?php
#################################################################
## Mod Title: mycalendar Mod w/selected forum integration
## Mod Version: 2.2.6
## Auther: mojavelinux <dan@mojavelinux.com>
## Author: marksten
## Description: Enables users to add events to the calendar
##              through a chosen forum.
#################################################################

/**
 * Check if this is an event forum
 *
 * Query the forums table and determine if the forum requested
 * allows the handling of calendar events.  The results are cache
 * as a static variable.
 *
 * @param  int $forum_id
 *
 * @access public
 * @return boolean
 */
function mycal_forum_check($forum_id) 
{
    global $db;
    // use static variable for caching results
    static $events_forums;

    // if we are not given a forum_id then return false
    if (is_null($forum_id) || $forum_id === '') 
    {
        return false;
    }

    if (!isset($events_forums)) 
    {
        $sql = 'SELECT forum_id, events_forum 
			FROM ' . FORUMS_TABLE; 
        if (!$results = $db->sql_query($sql)) 
        {
            message_die(GENERAL_ERROR, 'Failure when checking if forum can hold events.', '', __LINE__, __FILE__, $sql);
        }

        while ($row = $db->sql_fetchrow($results)) 
        {
            $events_forums[$row['forum_id']] = $row['events_forum'];
        }
    }

    return $events_forums[$forum_id] ? $events_forums[$forum_id] : false;
}

/**
 * Enter/delete/modifies the event in the mycalendar table
 *
 * Depending on whether the user chooses new topic or edit post, we
 * make a modification on the mycalendar table to insert or update the event
 *
 * @param  string $mode whether we are editing or posting new topic
 * @param  int    $forum_id sql id of the forum
 * @param  int    $topic_id sql id of the topic
 * @param  int    $post_id sql id of the post
 * @param  int    $event_type_id sql id of the event type
 *
 * @access private
 * @return void
 */
function mycal_submit_event($mode, $forum_id, $topic_id, $post_id, $event_type_id = 0, $is_suggested = false)
{
	$confirmed = ($is_suggested ? "'N'" : "'Y'"); // Check if this event is becomes confirmed after submission
    global $db, $lang, $HTTP_POST_VARS;

    // Do nothing for a reply
    if ($mode == 'reply') 
    {
        return;
    }

    // setup defaults
    $cal_isodate = mycal_date2iso($HTTP_POST_VARS['cal_date']);
    $cal_interval = $cal_repeat = 1;
    $cal_interval_units = 'DAY';

    // get the ending date and interval information
    if (!empty($HTTP_POST_VARS['cal_interval_date']) && (isset($HTTP_POST_VARS['cal_date_end']) || !empty($HTTP_POST_VARS['cal_repeat_always']))) 
    {
        // coax the interval to a postive integer
        $cal_interval = ($tmp_interval = abs($HTTP_POST_VARS['cal_interval'])) ? $tmp_interval : 1;
        $cal_interval_units = $HTTP_POST_VARS['cal_interval_units'];
        if (!empty($HTTP_POST_VARS['cal_repeat_always'])) 
        {
            $cal_repeat = 0;
        }
        elseif ($HTTP_POST_VARS['cal_date_end'] != $lang['Date_not_specified']) 
        {
            $cal_isodate_end = mycal_date2iso($HTTP_POST_VARS['cal_date_end']);
            // make sure the end is not before the beginning, if so swap
            if ($cal_isodate_end < $cal_isodate) 
            {
                $tmp = $cal_isodate_end;
                $cal_isodate_end = $cal_isodate;
                $cal_isodate = $tmp;
            }

            // get the number of repeats between the two dates of the interval
            $sql = 'SELECT ';
            if ($cal_interval_units == 'DAY') 
            {
                $sql .= "FLOOR((TO_DAYS('$cal_isodate_end') - TO_DAYS('$cal_isodate'))/$cal_interval + 1)";
            }
            elseif ($cal_interval_units == 'WEEK') 
            {
                $sql .= "FLOOR((TO_DAYS('$cal_isodate_end') - TO_DAYS('$cal_isodate'))/(7 * $cal_interval) + 1)";
            }
            elseif ($cal_interval_units == 'MONTH') 
            {
                $sql .= "FLOOR(PERIOD_DIFF(DATE_FORMAT('$cal_isodate_end', '%Y%m'), DATE_FORMAT('$cal_isodate', '%Y%m'))/$cal_interval + 1)";
            }
            elseif ($cal_interval_units == 'YEAR') 
            {
                $sql .= "FLOOR((YEAR('$cal_isodate_end') - YEAR('$cal_isodate'))/$cal_interval + 1)";
            }

            $sql .= ' as cal_repeat';
            if (!$results = $db->sql_query($sql)) 
            {
                message_die(GENERAL_MESSAGE, 'Could not determine repeat multiplier for date entry.', '', __LINE__, __FILE__, $sql);
            }
            elseif (!$row = $db->sql_fetchrow($results))
            {
                message_die(GENERAL_MESSAGE, 'Could not determine repeat multiplier for date entry.', '', __LINE__, __FILE__, $sql);
            }
            elseif (empty($row['cal_repeat'])) 
            {
                message_die(GENERAL_MESSAGE, 'Could not determine repeat multiplier for date entry.', '', __LINE__, __FILE__, $sql);
            }

            $cal_repeat = $row['cal_repeat'];
        }
    }

    // if this is a new topic and we can post a date to it (do we have to check this) and
    // we have specified a date, then go ahead and enter it
    if ($mode == 'newtopic' && $cal_isodate && mycal_forum_check($forum_id)) 
    {
        $sql = 'INSERT INTO ' . MYCALENDAR_TABLE . " (topic_id, cal_date, cal_interval, cal_interval_units, cal_repeat, forum_id, confirmed, event_type_id) 
        		VALUES ($topic_id, '" . $cal_isodate . "', $cal_interval, '$cal_interval_units', $cal_repeat, $forum_id, $confirmed, $event_type_id)";
        if (!$db->sql_query($sql)) 
        {
            message_die(GENERAL_MESSAGE, 'Could not insert new event into calendar table.', '', __LINE__, __FILE__, $sql);
        }
    } 
    // if we are editing a post, we either update, insert or delete, depending on if date is set
    // and whether or not a date was specified, so we have to check all that stuff
    elseif ($mode == 'editpost' && mycal_forum_check($forum_id)) 
    {
        // check if not allowed to edit the calendar event since this is not the first post
        if (!mycal_first_post($topic_id, $post_id)) 
        {
            return;
        }

        $sql = 'SELECT topic_id 
			FROM ' . MYCALENDAR_TABLE . "
            WHERE topic_id = $topic_id";
        if (!$result = $db->sql_query($sql)) 
        {
            message_die(GENERAL_ERROR, 'Failure when looking up previous date entry for editing topic.', '', __LINE__, __FILE__, $sql);
        }

        // if we have an event in the calendar for this topic and this is the first post,
        // then we will affect the entry depending on if a date was provided
        if ($db->sql_numrows($result) > 0) 
        {
            // we took away the calendar date (no start date, no date)
            if (!$cal_isodate) 
            {
                $sql = 'DELETE FROM ' . MYCALENDAR_TABLE . "
					WHERE topic_id = $topic_id";
                if (!$db->sql_query($sql)) 
                {
                    message_die(GENERAL_MESSAGE, 'Could not remove event from calendar table.', '', __LINE__, __FILE__, $sql);
                }
            }
            else 
            {
                $sql = 'UPDATE ' . MYCALENDAR_TABLE . " 
                	SET cal_date = '$cal_isodate', cal_interval = $cal_interval, cal_interval_units = '$cal_interval_units', cal_repeat = $cal_repeat, confirmed = $confirmed, event_type_id = $event_type_id
      	            WHERE topic_id = $topic_id";
				if (!$db->sql_query($sql)) 
				{
					message_die(GENERAL_MESSAGE, 'Could not update event in calendar table.', '', __LINE__, __FILE__, $sql);
				}
            }
        }
        // insert the new entry if a date was provided
        elseif ($cal_isodate) 
        {
			$sql = 'INSERT INTO ' . MYCALENDAR_TABLE . " (topic_id, cal_date, cal_interval, cal_interval_units, cal_repeat, forum_id, confirmed, event_type_id) 
				VALUES ($topic_id, '$cal_isodate', $cal_interval, '$cal_interval_units', $cal_repeat, $forum_id, $confirmed, $event_type_id)";
            if (!$db->sql_query($sql)) 
			{
                message_die(GENERAL_MESSAGE, 'Could not insert new event into calendar table.', '', __LINE__, __FILE__, $sql);
            }
        }
    }
}

function mycal_move_event($new_forum_id, $topic_id, $leave_shadow = false)
{
    global $db; 
    
    // if we are not leaving a shadow and the new forum doesn't do events,
    // then delete to event and return
    if (!$leave_shadow && !mycal_forum_check($new_forum_id)) 
    {
        if (!$leave_shadow) 
        {
            mycal_delete_event($topic_id, null, false);
            return;
        }
    }
    else 
    {
        $sql = 'UPDATE ' . MYCALENDAR_TABLE . "
        	SET forum_id = $new_forum_id                
        	WHERE topic_id IN ($topic_id)";
        if (!$db->sql_query($sql)) 
        {
            message_die(GENERAL_MESSAGE, 'Could not relocate the event.', '', __LINE__, __FILE__, $sql);
        }
    }
}

/**
 * Remove an event from the mycalendar table
 *
 * When a topic is removed, and not just a reply within that topic,
 * the event is deleted from the mycalendar table appropriately.
 *
 * @param  mixed  $topic_id either int or comma seperated..if $check_post is false, it must be int
 * @param  int  $post_id
 * @param  bool $check_post verify that the post is the first post and not a reply
 *
 * @access public
 * @return void
 */
function mycal_delete_event($topic_id, $post_id, $check_post = false) 
{
    global $db;

    // First we must verify that this we are deleting a whole topic...not
    // just a single post within the topic
    // we have to use two queries for old databases, even though MySQL can do it in one
    if ($check_post) 
    {
        $sql = 'SELECT c.cal_id 
			FROM ' . MYCALENDAR_TABLE . ' as c, ' . TOPICS_TABLE . " as t 
			WHERE t.topic_id = $topic_id 
				AND c.topic_id = $topic_id 
				AND t.topic_first_post_id = $post_id";
        if (!$result = $db->sql_query($sql)) 
        {
            message_die(GENERAL_MESSAGE, 'Error in query which determines if post is leading post in topic.', '', __LINE__, __FILE__, $sql);
        }
    }

    if (!$check_post || $db->sql_numrows($result) > 0) 
    {
        $sql = 'DELETE FROM ' . MYCALENDAR_TABLE . "
			WHERE topic_id IN ($topic_id)";
        if (!$db->sql_query($sql)) 
        {
            message_die(GENERAL_MESSAGE, 'Could not delete event.', '', __LINE__, __FILE__, $sql);
        }
    }
}

/**
 * Print out the event date on the page where the topic is viewed (viewtopic.php)
 *
 * This function will prepend a string to the first post in a topic
 * that declares an event date, but only if the event date has a reference
 * to a forum which allows events to be used.  In the case of a reoccuring/block date,
 * the display will be such that it explains this attribute.
 *
 * @param int  $topic_id identifier of the topic
 * @param int  $post_id identifier of the post, used to determine if this is the leading post
 * @param int  $message the message for this post, which may be altered here
 *
 * @access public
 * @return string body message
 */
function mycal_show_event($topic_id, $post_id, $message) 
{
    global $db, $lang, $phpbb_root_path, $phpEx;
    
    $format = $lang['DATE_SQL_FORMAT'];

    $sql = 'SELECT e.* , c.cal_date, DATE_FORMAT(c.cal_date, "' . $format . '") AS cal_date_f, c.cal_interval, c.cal_interval_units, c.cal_repeat, c.confirmed, DATE_FORMAT(c.cal_date + INTERVAL (c.cal_repeat - 1) DAY, "%M %D, %Y") AS cal_date_end
        	FROM ' . MYCALENDAR_TABLE . ' as c, ' . TOPICS_TABLE . ' as t, ' . FORUMS_TABLE . ' as f, ' . MYCALENDAR_EVENT_TYPES_TABLE . " as e
            WHERE e.forum_id = f.forum_id 
        		AND e.event_type_id = c.event_type_id 
        		AND t.topic_id = $topic_id 
        		AND c.topic_id = $topic_id 
        		AND c.forum_id = f.forum_id 
        		AND f.events_forum > 0 
        		AND t.topic_first_post_id = $post_id";
    if (!$result = $db->sql_query($sql)) 
    {
 		message_die(GENERAL_ERROR, 'Failure when looking up date entry for topic', '', __LINE__, __FILE__, $sql);
    }

    // we found a calendar event, so let's append it
    if ( $db->sql_numrows($result) > 0 ) 
    {
        $row = $db->sql_fetchrow($result);
        $cal_date = $row['cal_date'];
        $cal_date_f = $row['cal_date_f'];
        $cal_interval = $row['cal_interval'];
        $cal_repeat = $row['cal_repeat'];
        $confirmed = $row['confirmed'];
        $event['message'] = '<b><a href=" ' . append_sid($phpbb_root_path . 'calendar.'.$phpEx) . '" class="gen">' . ($confirmed == 'Y' ? $lang['Calendar_event_title'] : $lang['Calendar_suggested_event_title']) . '</a>:</b> <i>' . mycal_translate_date($cal_date_f) . '</i>';
        // if this is more than a single date event, then dig deeper
        if ($row['cal_repeat'] != 1) 
        {
            // if this is a repeating or block event (repeat > 1), show end date!
            if ($row['cal_repeat'] > 1) 
            {
                // have to pull a little tweak on WEEK since the interval doesn't exist in SQL
                $units = $row['cal_interval_units'] == 'WEEK' ? '* 7 DAY' : $row['cal_interval_units'];
                // we have to do another query here because SQL does not allow interval units to be a field variable
                $sql2 = 'SELECT DATE_FORMAT(\'' . $cal_date . '\' + INTERVAL ' . $cal_interval . ' * (' . $cal_repeat . ' - 1) ' . $units . ', "' . $format . '") AS cal_date_end ';
                if (!$results2 = $db->sql_query($sql2)) 
                {
                    message_die(GENERAL_ERROR, 'Failure when calculating end date.', '', __LINE__, __FILE__, $sql2);
                }
                $row2 = $db->sql_fetchrow($results2);
                $cal_date_end = $row2['cal_date_end'];
                $event['message'] .= ' - <i>' . mycal_translate_date($cal_date_end) . '</i>';
            }

            // we have a non-continuous interval or a 'forever' repeating event, so we will explain it
            if (!($row['cal_interval_units'] == 'DAY' && $cal_interval == 1 && $cal_repeat != 0)) 
            {
                $units = ($row['cal_interval'] == 1) ? $lang['interval'][strtolower($row['cal_interval_units'])] : $lang['interval'][strtolower($row['cal_interval_units']) . 's'];
                $repeat = $row['cal_repeat'] ? $row['cal_repeat'] . 'x': $lang['Calendar_repeat_forever'];
                $event['message'] .= '<br /><b>' .  $lang['Calendar_interval'] .  ':</b> ' .  $row['cal_interval'] .  ' ' .  $units .  '<br /><b>' .  $lang['Calendar_repeat'] .  ':</b> ' .  $repeat;
            }
        }
        $event['message'] .= '<br /><b>' . $lang['Event_type'] . ':</b> <i>' . $row['event_type_text'] . '</i>';

        $message = '<div style="padding-left: 5px;">' . $event['message'] . '</div><hr />' . $message;
    } 

    return $message;
}

/**
 * Print out the selection box for selecting date
 *
 * When a new post is added or the first post in topic is edited, the poster
 * will be presented with an event date selection box if posting to an event forum
 *
 * @param  string $mode
 * @param  int $topic_id
 * @param  int $post_id
 * @param  int $forum_id
 * @param  object $template
 *
 * @access private
 * @return void
 */
function mycal_generate_entry($mode, $forum_id, $topic_id, $post_id, &$template) 
{
    global $db, $lang, $HTTP_POST_VARS, $HTTP_GET_VARS;

    // if this is a reply or not an event forum or if we are editing and it is not the first post, just return
    if ($mode == 'reply' || !mycal_forum_check($forum_id) || ($mode == 'editpost' && !mycal_first_post($topic_id, $post_id))) 
    {
        return;
    }

    // set up defaults first in case we don't find any event information (such as a new post)
    $cal_interval = 1;
    $cal_interval_units = 'DAY';
    $cal_repeat_always = '';
    $cal_date_end = $lang['Date_not_specified'];
    $cal_date = $lang['Date_not_specified'];

    // we only want to get the date if this is an edit fresh, and not preview
    if (isset($HTTP_POST_VARS['cal_date']) && isset($HTTP_POST_VARS['cal_date_end'])) 
    {
        $cal_date = $HTTP_POST_VARS['cal_date'];
        $cal_date_end = $HTTP_POST_VARS['cal_date_end'];
    }
    // we only want to get the date if this is an edit fresh, and not preview
    if (isset($HTTP_GET_VARS['cal_date'])) 
	{
        $cal_date = $HTTP_GET_VARS['cal_date'];
    }
    // okay we are starting an edit on the post, let's get the required info from the tables
    elseif ($mode == 'editpost') 
    {
        // setup the format used for the form
        $format = str_replace(array('m', 'd', 'y'), array('%m', '%d', '%Y'), strtolower($lang['DATE_INPUT_FORMAT']));

        // grab the event info for this topic
        $sql = 'SELECT DATE_FORMAT(cal_date, "' . $format . '") AS cal_date, cal_interval, cal_interval_units, cal_repeat, event_type_id 
			FROM ' . MYCALENDAR_TABLE . "
            WHERE topic_id = $topic_id";
        if (!$results = $db->sql_query($sql))
        {
            message_die(GENERAL_ERROR, 'Failure when looking up previous date entry for editing topic', '', __LINE__, __FILE__, $sql);
        }

        if ($row = $db->sql_fetchrow($results)) 
        {
            $cal_interval_units = $row['cal_interval_units'];
            $cal_interval = $row['cal_interval'];
            $cal_date = $row['cal_date'];
            $event_type_id = $row['event_type_id'];
           // only if the repeat is more than 1 day (meaning it actually repeats) do we get the end date
            // else it is just a single event
            if ($row['cal_repeat'] > 1) 
            {
                // have to pull a little tweak on WEEK since the interval doesn't exist in SQL
                $units = $row['cal_interval_units'] == 'WEEK' ? '* 7 DAY' : $row['cal_interval_units'];

                // we have to do another query here because SQL does not allow interval units to be a field variable
                $sql2 = 'SELECT DATE_FORMAT(cal_date + INTERVAL cal_interval * (cal_repeat - 1) ' . $units . ', "' . $format . '") AS cal_date_end
					FROM ' . MYCALENDAR_TABLE . ' 
                    WHERE topic_id = ' . $topic_id;
                if (!$results2 = $db->sql_query($sql2)) 
                {
                    message_die(GENERAL_ERROR, 'Failure when looking up end date for previous date entry on topic', '', __LINE__, __FILE__, $sql2);
                }

                $row2 = $db->sql_fetchrow($results2);
                $cal_date_end = $row2['cal_date_end'];
            }
            elseif ($row['cal_repeat'] == 1) 
            {
                $cal_interval = 1;
                $cal_interval_units = 'DAY';
                $cal_date_end = $lang['Date_not_specified'];
            }
            else 
            {
                $cal_repeat_always = 'checked="checked"';
                $cal_date_end = $lang['Date_not_specified'];
            }
        }
    }

    // generate a list of months for the current language so javascript can pass it up to the calendar
    $localizedMonthList = array();
    foreach (array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December') as $month) 
    {
        $localizedMonthList[] = '\'' . $lang['datetime'][$month] . '\'';
    }
    $localizedMonthList = implode(',', $localizedMonthList);

    // generate a list of weekdays for the current language so javascript can pass it up to the calendar
    $localizedWeekdayList = array();
    foreach (array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday') as $weekday) 
    {
        $localizedWeekdayList[] = '\'' . strtoupper(substr($lang['datetime'][$weekday], 0, 1)) . '\'';
    }
    $localizedWeekdayList = implode(',', $localizedWeekdayList);

    $interval_units = array(
        'DAY'   => mycal_pluralize($lang['interval']['day'], $lang['interval']['days']),
        'WEEK'  => mycal_pluralize($lang['interval']['week'], $lang['interval']['weeks']),
        'MONTH' => mycal_pluralize($lang['interval']['month'], $lang['interval']['months']),
        'YEAR'  => mycal_pluralize($lang['interval']['year'], $lang['interval']['years']),
    );
    
    $interval_unit_options = '';
    foreach($interval_units as $unit => $name) 
    {
        $interval_unit_options .= '<option value="' . $unit . '"';
        if ($cal_interval_units == $unit) 
        {
            $interval_unit_options .= ' selected="selected"';
        }
        $interval_unit_options .= '>' . $name . '</option>';
    }

    $template->assign_block_vars('switch_cal_form', array());

	$category_select = '<select name="event_type_id">';
	$sql = "SELECT * 
		FROM " . MYCALENDAR_EVENT_TYPES_TABLE . " 
		WHERE forum_id = $forum_id";
	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, "Couldn't load forum's event categories", "", __LINE__, __FILE__, $sql);
	}
	
	while ($row = $db->sql_fetchrow($result))
	{
		$category_select .= '<option value="' . $row['event_type_id'] . '"' . (($row['event_type_id'] == $event_type_id) ? ' selected="selected"' : '') . '>' . $row["event_type_text"] . '</option>';
	}
	$db->sql_freeresult($result);
	$category_select .= '</select>';

    $template->assign_vars(array(
        'L_CALENDAR_EVENT'          => $lang['Calendar_event_title'],
        'L_EVENT_START'             => $lang['Event_Start'],
        'L_EVENT_END'               => $lang['Event_End'],
        'L_CALENDAR_ADVANCED'       => $lang['Calendar_advanced_form'],
        'L_SELECT_START_DATE'       => $lang['Select_start_date'],
        'L_CLEAR_DATE'              => $lang['Clear_Date'],
        'L_CAL_REPEAT_FOREVER'      => $lang['Calendar_repeat_forever'],
        'CAL_DATE'                  => $cal_date,
        'CAL_DATE_END'              => $cal_date_end,
        'CAL_ADVANCED_FORM'         => ($cal_date_end != $lang['Date_not_specified'] || $cal_repeat_always) ? '' : 'none',
        'CAL_ADVANCED_FORM_ON'      => ($cal_date_end != $lang['Date_not_specified'] || $cal_repeat_always) ? 'checked="checked"' : '',
        'CAL_NO_DATE'               => $lang['Date_not_specified'],
        'CAL_INTERVAL'              => $cal_interval,
        'CAL_INTERVAL_UNIT_OPTIONS' => $interval_unit_options,
        'CAL_REPEAT_ALWAYS'         => $cal_repeat_always,
        'CAL_DATE_FORMAT'           => $lang['DATE_INPUT_FORMAT'],
        'CAL_START_MONDAY'          => $lang['Calendar_start_monday'] ? 'true' : 'false',
        'CAL_MONTH_LIST'            => $localizedMonthList,
        'CAL_WEEKDAY_LIST'          => $localizedWeekdayList,
        'DATE_SELECTOR_TITLE'       => $lang['Date_selector'],
        'CAL_NEXT_YEAR'             => $lang['View_next_year'],
        'CAL_PREVIOUS_YEAR'         => $lang['View_previous_year'],
        'CAL_NEXT_MONTH'            => $lang['View_next_month'],
        'CAL_PREVIOUS_MONTH'        => $lang['View_previous_month'],
        'L_EVENT_TYPE_OPTION'        => $lang['Event_type'],
		'EVENT_TYPES'				=> $category_select)
	);
}

/**
 * Do a conversion from 01/01/2000 to the unix timestamp
 *
 * Convert from the date used in the form to a unix timestamp, but
 * do it based on the user preference for date formats
 *
 * @param  string date (all numbers < 10 must be '0' padded at this point)
 *
 * @access public
 * @return int unix timestamp
 */
function mycal_date2iso($in_stringDate) 
{
    global $lang;

    if ($in_stringDate == $lang['Date_not_specified']) 
    {
        return false;
    }

    // find the first punctuation character, which will be our delimiter
    $tmp_format = str_replace('y', 'yyyy', str_replace('m', 'mm', str_replace('d', 'dd', strtolower($lang['DATE_INPUT_FORMAT']))));
    $tmp_yOffset = strpos($tmp_format, 'y');
    $tmp_mOffset = strpos($tmp_format, 'm');
    $tmp_dOffset = strpos($tmp_format, 'd');

    // remap the parts to variables, at this point we assume it is coming through the wire 0 padded
    $year  = substr($in_stringDate, $tmp_yOffset, 4);
    $month = substr($in_stringDate, $tmp_mOffset, 2);
    $day   = substr($in_stringDate, $tmp_dOffset, 2);
    return $year . '-' . $month . '-' . $day . ' 00:00:00';
}

/**
 * Determine if this post is the first post in a topic
 *
 * Simply query the topics table and determine if this post is
 * the first post in the topic...important since calendar events
 * can only be attached to the first post
 *
 * @param  int topic_id
 * @param  int post_id
 *
 * @access public
 * @return boolean is first post
 */
function mycal_first_post($topic_id, $post_id)
{
    global $db;

    $sql = 'SELECT ' . "topic_first_post_id = $post_id as first_post " .
           'FROM ' . TOPICS_TABLE . ' as t ' .
           'WHERE ' . "topic_id = $topic_id"; 
    if (!$results = $db->sql_query($sql)) 
    {
        message_die(GENERAL_ERROR, 'Failure when determining if post is leading post in topic.', '', __LINE__, __FILE__, $sql);
    }

    $row = $db->sql_fetchrow($results);
    // if this is not the first post, then get out of here
    if (!$row['first_post']) 
    {
        return false;
    }
    
    return true;
}

// if I were to add timezone stuff it would be here
function mycal_translate_date($in_date)
{
    global $lang, $board_config;

    return $board_config['default_lang'] == 'english' ? $in_date : strtr($in_date, $lang['datetime']);
}

/**
 * Universal single/plural option generator
 *
 * This function will take a singular word and its plural counterpart and will
 * combine them by either appending a (s) to the singular word if the plural word
 * is formed this way, or will slash separate the singular and plural words.
 * Example: week(s), country/countries
 *
 * @param string $in_singular singular word
 * @param string $in_plural plural word
 *
 * @access public
 * @author moonbase
 * @return string combined singular/plural contruct
 */
function mycal_pluralize($in_singular, $in_plural)
{
    if ( stristr($in_plural, $in_singular) )
    {
        return substr($in_plural, 0, strlen($in_singular)) . ((strlen($in_plural) > strlen($in_singular)) ? '(' . substr($in_plural, strlen($in_singular)) . ')' : '');
    }

    return $in_singular . '/' . $in_plural;
}

?>