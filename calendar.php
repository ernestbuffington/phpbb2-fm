<?php
/** 
*
* @package phpBB2
* @version $Id: calendar.php,v 2.2.6 2004/07/11 16:46:15 marksten Exp $
* @copyright (c) marksten, mojavelinux
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

$phpbb_root_path = './';
define('IN_PHPBB', true);
include_once $phpbb_root_path . 'extension.inc';
include_once $phpbb_root_path . 'common.'.$phpEx;
include_once $phpbb_root_path . 'includes/bbcode.'.$phpEx;

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_CALENDAR);
init_userprefs($userdata);
//
// End Session management
//
	
	
// determine the information for the current date
list($today['year'], $today['month'], $today['day']) = explode('-', create_date('Y-m-d', time(), $board_config['board_timezone']));

// get the month/year offset from the get variables, or else use first day of this month
if (isset($HTTP_GET_VARS['month']) && isset($HTTP_GET_VARS['year'])) 
{
    $view_isodate = sprintf('%04d', $HTTP_GET_VARS['year']) . '-' . sprintf('%02d', $HTTP_GET_VARS['month']) . '-01 00:00:00';
} 
// get the first day of the month as an isodate
else 
{
    $view_isodate = $today['year'] . '-' . $today['month'] . '-01 00:00:00';
}

// setup the current view information
$query = "SELECT
 	MONTHNAME('$view_isodate') AS monthName,
    DATE_FORMAT('$view_isodate', '%m') AS month,
    YEAR('$view_isodate') AS year,
    DATE_FORMAT(CONCAT(YEAR('$view_isodate'), '-', MONTH('$view_isodate' + INTERVAL 1 MONTH), '-01') - INTERVAL 1 DAY, '%e') AS numDays,
	WEEKDAY('$view_isodate') AS offset";
$result = $db->sql_query($query);
$monthView = $db->sql_fetchrow($result);
$monthView['monthName'] = $lang['datetime'][$monthView['monthName']];

// [*] is this going to give us a negative number ever?? [*]
if (!$lang['Calendar_start_monday']) 
{
    $monthView['offset']++;
}

// set the page title and include the page header
$page_title = $lang['Calendar'];
include ($phpbb_root_path . 'includes/page_header.'.$phpEx);

$template->set_filenames(array(
    'body' => 'calendar_body.tpl')
);
make_jumpbox('viewforum.'.$phpEx);

// prepare the loops for running through the calendar for the current month
$numRows = ceil(($monthView['numDays'] + $monthView['offset']) / 7);
$day = 1;
$eventStack = $topicCache = $unsetOnNextDay = array();
if ($monthView['offset'] >= 7) 
{
	$monthView['offset'] = 0;
}

foreach(range(1, $numRows) as $row) 
{
    $template->assign_block_vars('date_row', array());
    foreach (range(1, 7) as $weekIndex) 
    {
        // we are before the first date
        if ( ($row == 1 && $weekIndex <= $monthView['offset'] && $monthView['offset'] < 7) ) 
		{
            if ( $weekIndex == 1 ) 
			{
                $template->assign_block_vars('date_row.date_cell', array(
                    'BLANK_COLSPAN' => $monthView['offset'])
                );
                $template->assign_block_vars('date_row.date_cell.switch_blank_cells', array());
            }
        }
        // we are after the last date
        elseif ($day > $monthView['numDays']) 
        {
            if ($day == ($monthView['numDays'] + 1)) 
            {
                $template->assign_block_vars('date_row.date_cell', array(
                    'BLANK_COLSPAN' => ($row * 7) - ($monthView['numDays'] + $monthView['offset']))
                );
                $template->assign_block_vars('date_row.date_cell.switch_blank_cells', array());

                // We have to now increment the day so that we don't repeat this cell
                $day++;
            }
        }
        // we are on a date
        else 
        {
			$format = str_replace("y", "Y", $lang['DATE_INPUT_FORMAT']);
			$eventDate = date($format, mktime( 0, 0, 0, $monthView["month"], $day, $monthView["year"]));

            $template->assign_block_vars('date_row.date_cell', array(
                'TODAY_STYLE' => $today_style, 
                'DATE_CLASS' => ($row % 2) ? 'row2' : 'row3',
                'DATE' => $day,
		    	'EVENT_DATE' => $eventDate)
            );
            $template->assign_block_vars('date_row.date_cell.switch_date_cells', array());

            // allow the template to handle how to treat the day
            if ($today['day'] == $day && $today['month'] == $monthView['month'] && $today['year'] == $monthView['year']) 
            {
                $template->assign_block_vars('date_row.date_cell.switch_date_cells.switch_date_today', array());
            }
            else 
            {
                $template->assign_block_vars('date_row.date_cell.switch_date_cells.switch_date_otherday', array());
            }

            // set the isodate for our current mark in the calendar (padding day appropriately)
            $current_isodate = $monthView['year'] . '-' . $monthView['month'] . '-' . sprintf('%02d', $day) . ' 00:00:00';

			// Chek if only one type of events needs to be shown
			if (isset($forum_id) && isset($event_type_id))
			{
				$isolateEvent = "ec.forum_id = $forum_id AND ec.event_type_id = $event_type_id AND ";
			}
			else
			{
				$isolateEvent = '';
			}
			
            $query = "SELECT
            		c.*,
                	t.topic_title,
                	t.title_compl_infos,
                	t.title_pos, 
                	t.title_compl_color,
                	t.topic_views,
                	t.topic_replies,
                	f.forum_name,
                	f.auth_read,
                	pt.post_text,
                	pt.bbcode_uid,
					ec.*,
                	(cal_interval_units = 'DAY' && cal_interval = 1 && '$current_isodate' = INTERVAL (cal_interval * (cal_repeat - 1)) DAY + cal_date) as block_end
                FROM
                    " . MYCALENDAR_TABLE . " as c,
                    " . TOPICS_TABLE . " as t,
                    " . FORUMS_TABLE . " as f,
                    " . POSTS_TEXT_TABLE . " as pt,
					" . MYCALENDAR_EVENT_TYPES_TABLE . " as ec
				WHERE
					$isolateEvent
					ec.forum_id = c.forum_id
					AND ec.event_type_id = c.event_type_id
					AND c.forum_id = f.forum_id 
                    AND c.topic_id = t.topic_id 
                    AND f.events_forum > 0 
                    AND pt.post_id = t.topic_first_post_id
                    AND '$current_isodate' >= cal_date
					AND ( cal_repeat = 0 OR ( cal_repeat > 0 AND ((cal_interval_units = 'DAY' AND ('$current_isodate' <= INTERVAL (cal_interval * (cal_repeat - 1)) DAY + cal_date))
                        OR (cal_interval_units = 'WEEK' AND ('$current_isodate' <= INTERVAL ((cal_interval * (cal_repeat - 1)) * 7) DAY + cal_date))
                        OR (cal_interval_units = 'MONTH' AND ('$current_isodate' <= INTERVAL (cal_interval * (cal_repeat - 1)) MONTH + cal_date))
                        OR (cal_interval_units = 'YEAR' AND ('$current_isodate' <= INTERVAL (cal_interval * (cal_repeat - 1)) YEAR + cal_date)))))
					AND (( cal_interval_units = 'DAY' AND (TO_DAYS('$current_isodate') - TO_DAYS(cal_date)) % cal_interval = 0) 
						OR (cal_interval_units = 'WEEK' AND (TO_DAYS('$current_isodate') - TO_DAYS(cal_date)) % (7 * cal_interval) = 0)
						OR (cal_interval_units = 'MONTH' AND DAYOFMONTH(cal_date) = DAYOFMONTH('$current_isodate') AND PERIOD_DIFF(DATE_FORMAT('$current_isodate', '%Y%m'), DATE_FORMAT(cal_date, '%Y%m')) % cal_interval = 0) 
                		OR (cal_interval_units = 'YEAR' AND DATE_FORMAT(cal_date, '%m%d') = DATE_FORMAT('$current_isodate', '%m%d') AND (YEAR('$current_isodate') - YEAR(cal_date)) % cal_interval = 0))
				ORDER BY cal_interval_units ASC, cal_date ASC, cal_repeat DESC";
			if (!$result = $db->sql_query($query)) 
			{
                message_die(GENERAL_ERROR, 'Error querying dates for calendar.', '', __LINE__, __FILE__, $query);
            }

            $numEvents = 0;
			// Unset event stack for those events that ended
			for ($i = 0; $i < sizeof($unsetOnNextStep); $i++)
			{
				if ($unsetOnNextStep[$i] != -1) 
				{
					unset($eventStack[$unsetOnNextStep[$i]]);
					$unsetOnNextStep[$i] = -1;
				}
			}
            while ($topic = $db->sql_fetchrow($result)) 
            {
                $is_auth = array();
                $is_auth = auth(AUTH_ALL, $topic['forum_id'], $userdata);
                
                if ( $is_auth['auth_read'] ) 
                { 
					// If viewer cannot post then he shouldn't see suggested events on calendar
					if (!$is_auth['auth_post'] && $topic['confirmed'] == 'N')
					{
						continue;
                    }
                    $topic_id = $topic['topic_id'];
  					$topic_title = $topic['topic_title'];
        			
        			$topic_title = capitalization($topic_title);

	 				if ($board_config['enable_quick_titles'])
					{
 						if ( $topicrow['title_pos'] )
						{
							$topic_title = (empty($topicrow['title_compl_infos'])) ? $topic_title : $topic_title . ' <span style="color: #' . $topicrow['title_compl_color'] . '">' . $topicrow['title_compl_infos'] . '</span>';
						}
						else
						{
							$topic_title = (empty($topicrow['title_compl_infos'])) ? $topic_title : '<span style="color: #' . $topicrow['title_compl_color'] . '">' . $topicrow['title_compl_infos'] . '</span> ' . $topic_title;
						}
					}
					
                    // prepare the first post text if it has not already been cached
                    if (!isset($topicCache[$topic_id])) 
                    {
                        $post_text = $topic['post_text'];
                        // if we are spilling over, reduce size...[!] should be configurable [!]
                        if (strlen($post_text) > 200) 
                        {
                            $post_text = substr($post_text, 0, 199) . '...';
                        }
                        $post_text = bbencode_second_pass($post_text, $topic['bbcode_uid']);
                        $post_text = smilies_pass($post_text); 
                        $post_text = preg_replace("/[\n\r]{1,2}/", '<br />', $post_text);
                      
                        // prepare the popup text, escaping quotes for javascript
                        $title_text = '<b>' . $lang['Topic'] . ':</b> ' . $topic_title . '<br /><b>' . $lang['Forum'] . ':</b> <i>' . $topic['forum_name'] . '</i><br /><b>' . $lang['Views'] . ':</b> ' . $topic['topic_views'] . '<br /><b>' . $lang['Replies'] . ':</b> ' . $topic['topic_replies'];
                       
                        // tack on the interval and repeat if this is a repeated event
                        if ($topic['cal_repeat'] != 1) 
                        {
                            $title_text .= '<br /><b>' . $lang['Calendar_interval'] . ':</b> ' . $topic['cal_interval'] . ' ' . (($topic['cal_interval'] == 1) ? $lang['interval'][strtolower($topic['cal_interval_units'])] : $lang['interval'][strtolower($topic['cal_interval_units']) . 's']). '<br /><b>' . $lang['Calendar_repeat'] . ':</b> ' . ($topic['cal_repeat'] ? $topic['cal_repeat'] . 'x' : 'always');
                        }

                        $title_text .= '<br />' . $post_text;
                        $title_text = str_replace(array('"', '\''), array('&quot;', '\\\''), $title_text);
                        
                        // make the url for the topic
                        $topic_url = append_sid('viewtopic.' . $phpEx . '?' . POST_TOPIC_URL . '=' . $topic_id);
                        $topicCache[$topic_id] = array(
                            'first_post' => $title_text,
                            'topic_url'  => $topic_url,
                        );
                    }

					$bgColor = ($topic["confirmed"] == 'Y' ? $topic["highlight_color"] : '#C8C8C8');
					$color = ($topic["confirmed"] == 'Y' ? '' : "color: #8D8D8D");
					
					// Now make sure color stands out on dark backgrounds
					$colorR = hexdec(substr($bgColor, 1, 2));
					$colorG = hexdec(substr($bgColor, 3, 2));
					$colorB = hexdec(substr($bgColor, 5, 2));
					if (($colorR + $colorG + $colorB) / 3.0 < 140.0)
					{
						$color = 'color: #FFFFFF'; 
					}

					$class = '';
					
                    // if we have a block event running (interval = 1 day) with this topic ID, then output our line
                    if (isset($eventStack[$topic_id])) 
                    {
                        $first_date = '';
                        $topic_text = "<font color = '$bgColor'>".(strlen($topic_title) > 50 ? substr($topic_title, 0, 48) . '...' : $topic_title)."</font>";
                        // we have to determine if we are in the right row...which is the value
                        // in the eventStack array
                        $offset = $eventStack[$topic_id] - $numEvents;
                        // if this block was running in a position other than the first, we need
                        // to correct the offset so the line keeps running along the same axis..
                        // even though the upper block has stopped.  We are going to get a 
                        // cascading effect from this until all overlapping block events stop
                        for ($offsetCount = 0; $offsetCount < $offset; $offsetCount++ ) 
						{
							$template->assign_block_vars('date_row.date_cell.switch_date_cells.date_event', array(
								'U_EVENT' => "<div class='transparent'>".$textStack[$numEvents]."</div>\n\t")
							);
							$numEvents++;
						}
                    }
                    // this is either a single day event or the start of a new block event
                    else 
					{
                        $topic_text = strlen($topic_title) > 50 ? substr($topic_title, 0, 48) . '...' : $topic_title;
						$class = "class = 'block_start'";
                    }
                    
					if ($topic['block_end']) 
					{
						$class = ($class == "" ? "class = 'block_end'" : "class = 'block_single'");
					}

                    $template->assign_block_vars('date_row.date_cell.switch_date_cells.date_event', array(
                        'U_EVENT' => "<a href=\"" . $topicCache[$topic_id]['topic_url']."&amp;back_to_calendar=calendar.$phpEx?month=" . $monthView["month"] . "%26year=" . $monthView["year"] . "\" onMouseOver=\"createTitle(this, '" . $topicCache[$topic_id]['first_post'] . "', event.pageX, event.pageY);\" onMouseOut=\"destroyTitle();\" class=\"gensmall\"><div bgcolor='$bgColor' align='left' $class style='background-color: $bgColor;$color'>$topic_text</div></a>\n\t")
                    );
                    
                    $numEvents++;

                    // Here I use a stack of sorts to keep track of block events which are
                    // still running...I sort the block start dates by date, so the overlaps
                    // will always appear in the same order...if a block ends while a lower block
                    // continues, I keep a place holder so that the line continues along the same
                    // path

                    // we are at the end of a block event
					$unsetOnNextStep[$numEvents-1] = -1;
                    if ($topic['block_end']) 
                    {
						// It is important to unset stack on the NEXT STEP, NOT NOW, since it would create
						// problems in alignment
						$unsetOnNextStep[$numEvents-1] = $topic_id;
                    }
                    
                    // we place an entry in the event stack, key as the topic, value as the row
                    // number the event should fall in, for visual block events (interval = 1 day)
                    if (!isset($eventStack[$topic_id]) && $topic['cal_interval_units'] == 'DAY' && $topic['cal_interval'] == 1) 
                    {
                        $eventStack[$topic_id] = empty($eventStack) ? 0 : sizeof($eventStack);
						$textStack[$eventStack[$topic_id]] = strlen($topic_title) > 50 ? substr($topic_title, 0, 48) . '...' : $topic_title;
                    }
                }
            }
  		 	$db->sql_freeresult($result);
            
			// This is to fix Mac IE bug that expands cell if there is nothing in it
			if ($numEvents == 0)
			{
                    $template->assign_block_vars('date_row.date_cell.switch_date_cells.date_event', array(
                        'U_EVENT' => '')
                    );
			}

			// User Birthdays
			$query = "SELECT user_id, username, DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(date_add(\"1970-01-01\", interval(user_birthday) day))), '%Y')+1 AS birthday 
				FROM " . USERS_TABLE . "
				WHERE month(date_add(\"1970-01-01\", interval(user_birthday) day)) = '" . $monthView['month'] . "'
			      AND dayofmonth(date_add(\"1970-01-01\", interval(user_birthday) day)) = '$day'
			      AND user_birthday < 999999";
			if (!$result = $db->sql_query($query)) 
			{ 
				message_die(GENERAL_ERROR, 'Error querying birthdays for calendar.', '', __LINE__, __FILE__, $query);
			} 
	
			while ($topic = $db->sql_fetchrow($result)) 
			{ 
				$topic_text = $lang['Birthday']; 
				$username = $topic['username']; 
				$username2 = sprintf($lang['Birthday_greeting_calendar'], $username); 
				$user_id = $topic['user_id']; 
				$birthday = $topic['birthday']; 
		
				$template->assign_block_vars('date_row.date_cell.switch_date_cells.date_event', array( 
					'U_EVENT' => "$first_date<a href=\"profile.php?mode=viewprofile&u=$user_id\" onMouseOver=\"createTitle(this, '<b>$topic_text:</b> $username<br />$username2', event.pageX, event.pageY);\" onMouseOut=\"destroyTitle();\" class=\"gensmall\">$topic_text: $username</a><br />") 
				); 
			} 
			$db->sql_freeresult($result);

            // Increment the day
            $day++;
        }
    }
}

if ($monthView['month'] == 12) 
{
    $nextmonth = 1;
    $nextyear = $monthView['year'] + 1; 
} 
else 
{
    $nextmonth = sprintf('%02d', $monthView['month'] + 1);
    $nextyear = $monthView['year'];
}

if ($monthView['month'] == 01) 
{
    $previousmonth = 12;
    $previousyear = $monthView['year'] - 1;
} 
else 
{
    $previousmonth = sprintf('%02d', $monthView['month'] - 1); 
    $previousyear = $monthView['year'];
}

$selectm1 = '<select name="month"> 
		<option value="01">&nbsp;' . $lang['datetime']['January'] . '&nbsp;</option> 
		<option value="02">&nbsp;' . $lang['datetime']['February'] . '&nbsp;</option> 
		<option value="03">&nbsp;' . $lang['datetime']['March'] . '&nbsp;</option> 
		<option value="04">&nbsp;' . $lang['datetime']['April'] . '&nbsp;</option> 
		<option value="05">&nbsp;' . $lang['datetime']['May'] . '&nbsp;</option> 
		<option value="06">&nbsp;' . $lang['datetime']['June'] . '&nbsp;</option> 
		<option value="07">&nbsp;' . $lang['datetime']['July'] . '&nbsp;</option> 
		<option value="08">&nbsp;' . $lang['datetime']['August'] . '&nbsp;</option> 
		<option value="09">&nbsp;' . $lang['datetime']['September'] . '&nbsp;</option> 
		<option value="10">&nbsp;' . $lang['datetime']['October'] . '&nbsp;</option> 
		<option value="11">&nbsp;' . $lang['datetime']['November'] . '&nbsp;</option> 
		<option value="12">&nbsp;' . $lang['datetime']['December'] . '&nbsp;</option>
</select>'; 

$selectm1 = str_replace('value="' . $monthView['month'] . '">', 'value="' . $monthView['month'] . '" selected="selected">', $selectm1); 

$selectm = $selectm1; 

$selecty1 = '<select name="year"> 
		<option value="2000">&nbsp;2000&nbsp;</option> 
		<option value="2001">&nbsp;2001&nbsp;</option> 
		<option value="2002">&nbsp;2002&nbsp;</option> 
		<option value="2003">&nbsp;2003&nbsp;</option> 
		<option value="2004">&nbsp;2004&nbsp;</option> 
		<option value="2005">&nbsp;2005&nbsp;</option> 
		<option value="2006">&nbsp;2006&nbsp;</option> 
		<option value="2007">&nbsp;2007&nbsp;</option> 
		<option value="2008">&nbsp;2008&nbsp;</option> 
		<option value="2009">&nbsp;2009&nbsp;</option> 
		<option value="2010">&nbsp;2010&nbsp;</option>
</select>'; 

$selecty1 = str_replace('value="' . $monthView['year'] . '">', 'value="' . $monthView['year'] . '" selected="selected">', $selecty1); 

$selecty = $selecty1; 

// prepare images and links for month navigation
$image_add_event = $images['icon_add_event'];
$image_prev_month = '<img src="' . $images['icon_left_arrow'] . '" align="middle" title="' . $lang['View_previous_month'] . '" alt="' . $lang['View_previous_month'] . '" />';
$image_next_month = '<img src="' . $images['icon_right_arrow'] . '" align="middle" title="' . $lang['View_next_month'] . '" alt="' . $lang['View_next_month'] . '" />';
$url_prev_month = append_sid('calendar.'.$phpEx.'?month=' . $previousmonth . '&amp;year= ' . $previousyear);
$url_next_month = append_sid('calendar.'.$phpEx.'?month=' . $nextmonth . '&amp;year=' . $nextyear);

$image_prev_year = '<img src="' . $images['icon_double_left_arrow'] . '" align="middle" title="' . $lang['View_previous_year'] . '" alt="' . $lang['View_previous_year'] . '" />';
$image_next_year = '<img src="' . $images['icon_double_right_arrow'] . '" align="middle" title="' . $lang['View_next_year'] . '" alt="' . $lang['View_next_year'] . '" />';
$url_prev_year = append_sid('calendar.'.$phpEx.'?month=' . $monthView['month'] . '&amp;year=' . ($monthView['year'] - 1));
$url_next_year = append_sid('calendar.'.$phpEx.'?month=' . $monthView['month'] . '&amp;year=' . ($monthView['year'] + 1));

if ($lang['Calendar_start_monday'])
{
    $template->assign_block_vars('switch_sunday_end', array());
}
else 
{
    $template->assign_block_vars('switch_sunday_beginning', array());
}

$template->assign_vars(array(
    'L_SUNDAY' => $lang['datetime']['Sunday'],
    'L_MONDAY' => $lang['datetime']['Monday'],
    'L_TUESDAY' => $lang['datetime']['Tuesday'],
    'L_WEDNESDAY' => $lang['datetime']['Wednesday'],
    'L_THURSDAY' => $lang['datetime']['Thursday'],
    'L_FRIDAY' => $lang['datetime']['Friday'],
    'L_SATURDAY' => $lang['datetime']['Saturday'],
    'L_CURRENT_MONTH' => $monthView['monthName'],
    'L_CURRENT_YEAR' => $monthView['year'],
    'L_JUMP_TO' => $lang['Jump_to'],
    'L_SUBMIT' => $lang['Go'],
    'L_ADD_EVENT' => $lang['Calendar_add_event'],
    'I_ADD_EVENT'  => $image_add_event,
    'I_PREV_MONTH' => $image_prev_month,
    'I_NEXT_MONTH' => $image_next_month,
    'U_PREV_MONTH' => $url_prev_month,
    'U_NEXT_MONTH' => $url_next_month,
    'I_PREV_YEAR'  => $image_prev_year,
    'I_NEXT_YEAR'  => $image_next_year,
    'U_PREV_YEAR'  => $url_prev_year,
    'U_NEXT_YEAR'  => $url_next_year,
    'S_MONTHS' => $selectm, 
    'S_YEARS' => $selecty)
);

//
// Add event type legend now
//
$query = "SELECT * 
	FROM " . MYCALENDAR_EVENT_TYPES_TABLE;
if (!$result = $db->sql_query($query)) 
{
	message_die(GENERAL_ERROR, 'Error retrieving event type information.', '', __LINE__, __FILE__, $query);
}

$legend = '<table style="border: 0px solid Black;" cellpadding="0" cellspacing="0">
	<tr>
		<td class="gensmall"><a class="nav" href="' . append_sid('calendar.'.$phpEx.'?month=' . $monthView['month'] . '&amp;year=' . $monthView['year']) . '">' . $lang['Legend'] . ':</a>&nbsp;&nbsp;</td>
		<td><table style="border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000; border-top: 1px solid #000000;" border="1" cellpadding="1" cellspacing="0">
		<tr>';

while ($row = $db->sql_fetchrow($result)) 
{
	$event_type_text = str_replace(" ", "&nbsp;", $row["event_type_text"]);
	$event_type_text = str_replace("\n", "&nbsp;", $event_type_text);
	$event_type_text = str_replace("\t", "&nbsp;", $event_type_text);
	
	// Now make sure color stands out on dark backgrounds
	$colorR = hexdec(substr($row["highlight_color"], 1, 2));
	$colorG = hexdec(substr($row["highlight_color"], 3, 2));
	$colorB = hexdec(substr($row["highlight_color"], 5, 2));
	if (($colorR + $colorG + $colorB) / 3.0 < 140.0)
	{
		$color = 'color: #FFFFFF'; 
	}
	else
	{
		$color = '';
	}
	$bgColor = $row["highlight_color"];

	$isolate_forum_id = $row['forum_id'];
	$isolate_event_type_id = $row['event_type_id'];
	if (isset($forum_id) && isset($event_type_id))
	{
		if (!($isolate_forum_id == $forum_id) || !($isolate_event_type_id == $event_type_id))
		{
			$color = 'color: #FFFFFF'; 
			$bgColor = '#A6A6A6';
		}
	}

	$legend .= '<td style="border-bottom: 0px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000; border-top: 0px solid #000000;" class="gensmall" bgcolor="' . $bgColor . '"><a class="gensmall" href="' . append_sid('calendar.'.$phpEx.'?month=' . $monthView['month'] . '&amp;year=' . $monthView['year'] . '&amp;forum_id=' . $isolate_forum_id . '&amp;event_type_id=' . $isolate_event_type_id) . '"><div bgcolor="' . $bgColor . '" align="left" style="background-color: ' . $bgColor . '; ' . $color . '">' . $event_type_text . '</div></a></td>';
}
$legend .= '</tr></table></td></tr></table>';

// Now set up forum chooser
//<input type="hidden" name="f" value="{FORUM_ID}">

$forumOptions = $lang[Calendar_post_event] . ' <select name="f">';
$query = "SELECT * 
	FROM " . FORUMS_TABLE. "
	WHERE forum_id > 0
		AND events_forum = 1";
if (!$result = $db->sql_query($query)) 
{
	message_die(GENERAL_ERROR, 'Error retrieving list of event forums.', '', __LINE__, __FILE__, $query);
}

$flag = true;
while ($row = $db->sql_fetchrow($result))
{
	$forum_id = $row["forum_id"];
	$forum_name = $row["forum_name"];
	$forumOptions .= '<option value="' . $forum_id . '"';
	if ($flag)
	{
		$forumOptions .= ' selected="selected"';
		$flag = false;
	}
	$forumOptions .= '>' . $forum_name . '</option>';
}
$forumOptions .= '</select>&nbsp;&nbsp;&nbsp;';


$template->assign_vars(array(
    'LEGEND'  => $legend,
	'FORUM_CHOOSER' => $forumOptions,
	'SESSION_ID' => $sid,
	'BACK_TO_CALENDAR' => 'calendar.'.$phpEx.'?month=' . $monthView["month"] . '&amp;year=' . $monthView["year"],
	'FORUM_NAME' => $row["forum_name"])
);

//
// Force password update
//
if ($board_config['password_update_days'])
{
	include($phpbb_root_path . 'includes/update_password.'.$phpEx);
}

$template->pparse('body');
 
include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>