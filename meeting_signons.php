<?php
/** 
*
* @package phpBB
* @version $Id: meeting_signons.php,v 1.3.18 Exp oxpus $
* @copyright (c) 2004 OXPUS
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
	
define('IN_PHPBB', true);
define('IN_MEETING', true);
$phpbb_root_path = './'; 
include($phpbb_root_path . 'extension.inc'); 
include($phpbb_root_path . 'common.'.$phpEx); 
include($phpbb_root_path . 'includes/bbcode.'.$phpEx);
include($phpbb_root_path . 'includes/functions_post.'.$phpEx);

//
// Start session management 
//
$userdata = session_pagestart($user_ip, PAGE_MEETING); 
init_userprefs($userdata); 
//
// End session management 
//

if ( !$userdata['session_logged_in'] || $userdata['user_id'] == ANONYMOUS )
{
	redirect(append_sid("login.$phpEx"));
}

$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_meeting.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_meeting.' . $phpEx);

$gen_meeting_header = FALSE;

// Check and set various parameters
$start = (isset($HTTP_POST_VARS['start'])) ? intval($HTTP_POST_VARS['start']) : intval($HTTP_GET_VARS['start']);

$params = array(
	'sort_field' => 'sort_field',
	'sort_order' => 'sort_order',
	'filter' => 'filter',
	'filter_by' => 'filter_by',
	'closed' => 'closed',
);

while( list($var, $param) = @each($params) )
{
	if ( !empty($HTTP_POST_VARS[$param]) || !empty($HTTP_GET_VARS[$param]) )
	{
		$$var = ( !empty($HTTP_POST_VARS[$param]) ) ? $HTTP_POST_VARS[$param] : $HTTP_GET_VARS[$param];
	}
	else
	{
		$$var = '';
	}
}

// Prepare sorting and filter variables
$sort_field = ( $sort_field == '' ) ? 'meeting_time' : $sort_field;
$sort_order = ( $sort_order == '' ) ? 'DESC' : $sort_order;
$filter_by = ( $filter_by == '' ) ? 'none' : $filter_by;
$sql_filter = ( $filter_by == 'none' ) ? '' : ( ( $filter != '' ) ? " AND $filter_by LIKE ('$filter')" : '' );

// Create the sort and filter fields
$sort_by_field = '<select name="sort_field">';
$sort_by_field .= '<option value="meeting_subject">'.$lang['Meeting_subject'].'</option>';
$sort_by_field .= '<option value="meeting_time">'.$lang['Time'].'</option>';
$sort_by_field .= '<option value="meeting_until">'.$lang['Meeting_until'].'</option>';
$sort_by_field .= '<option value="meeting_location">'.$lang['Meeting_location'].'</option>';
$sort_by_field .= '</select>';
$sort_by_field = str_replace('value="'.$sort_field.'">', 'value="'.$sort_field.'" selected="selected">', $sort_by_field);

$sort_by_order = '<select name="sort_order">';
$sort_by_order .= '<option value="ASC">'.$lang['Sort_Ascending'].'</option>';
$sort_by_order .= '<option value="DESC">'.$lang['Sort_Descending'].'</option>';
$sort_by_order .= '</select>';
$sort_by_order = str_replace('value="'.$sort_order.'">', 'value="'.$sort_order.'" selected="selected">', $sort_by_order);

$filter_by_field = '<select name="filter_by">';
$filter_by_field .= '<option value="none">---</option>';
$filter_by_field .= '<option value="meeting_subject">'.$lang['Meeting_subject'].'</option>';
$filter_by_field .= '<option value="meeting_location">'.$lang['Meeting_location'].'</option>';
$filter_by_field .= '</select>';
$filter_by_field = str_replace('value="'.$filter_by.'">', 'value="'.$filter_by.'" selected="selected">', $filter_by_field);

$closed_no = $closed_yes = $closed_period = $closed_none = '';
$current_time = time();

switch ($closed)
{
	case 2:
		$sql_closed .= ' AND m.meeting_until < '.$current_time.' AND m.meeting_time > '.$current_time;
		$closed_yes = 'checked="checked"';
		break;
	case 3:
		$sql_closed .= ' AND  m.meeting_time < '.$current_time;
		$closed_period = 'checked="checked"';
		break;
	case 4:
		$sql_closed = '';
		$closed_none = 'checked="checked"';
		break;
	default:
		$sql_closed .= ' AND m.meeting_until > '.$current_time;
		$closed_no = 'checked="checked"';
		break;
}

// Check for existing meetings for this user and prepare the pagination if needed
$sql = "SELECT u.meeting_id 
	FROM " . MEETING_USER_TABLE . " u, " . MEETING_DATA_TABLE . " m
	WHERE u.user_id = " . $userdata['user_id'] . "
		AND u.meeting_id = m.meeting_id
		$sql_filter $sql_closed";
if ( !$result = $db->sql_query($sql) )
{
	message_die(GENERAL_ERROR, 'Could not get meeting data', '', __LINE__, __FILE__, $sql);
}

$total_meetings = $db->sql_numrows($result);
$db->sql_freeresult($result);

// Load header and templates
$page_title = $lang['Meeting_viewlist'];
include($phpbb_root_path.'includes/page_header.'.$phpEx);

$template->set_filenames(array(
	'body' => 'meeting_signlist_body.tpl')
);

// Send the default strings to the template
$template->assign_vars(array(
	'L_MEETING' => $page_title,
	'L_MEETING_SIGNON' => $lang['Meeting_signons'],
	'L_MEETING_TIME' => $lang['Time'],
	'L_MEETING_UNTIL' => $lang['Meeting_until'],
	'L_MEETING_SUBJECT' => $lang['Meeting_subject'],
	'L_MEETING_CLOSED' => $lang['Meeting_closed'],
	'L_SORT_BY_FIELD' => $lang['Sort'],
	'L_SORT_BY_ORDER' => $lang['Order'],
	'L_FILTER_BY_FIELD' => $lang['Meeting_filter'],
	'L_GO' => $lang['Submit'],
	'L_CLOSED_NO' => $lang['Meeting_open'],
	'L_CLOSED_YES' => $lang['Meeting_closed'],
	'L_CLOSED_PERIOD' => $lang['Meeting_no_period'],
	'L_CLOSED_NONE' => $lang['Meeting_all'],

	'SORT_BY_FIELD' => $sort_by_field,
	'SORT_BY_ORDER' => $sort_by_order,
	'FILTER_BY_FIELD' => $filter_by_field,
	'FILTER_FIELD' => $filter,
	'CLOSED_NO' => $closed_no,
	'CLOSED_YES' => $closed_yes,
	'CLOSED_PERIOD' => $closed_period,
	'CLOSED_NONE' => $closed_none,

	'U_MEETING' => append_sid("meeting.$phpEx"),
	'S_ACTION' => append_sid("meeting_signons.$phpEx"))
);

if ($total_meetings)
{
	if ($total_meetings > $board_config['topics_per_page'])
	{
		$pagination = generate_pagination("meeting_signons.$phpEx?sort_field=$sort_field&sort_order=$sort_order&filter_by=$filter_by&filter=$filter&closed=$closed", $total_meetings, $board_config['total_topics'], $start);
	}
	else
	{
		$pagination = '';
	}

	// Read the meeting data
	$sql = "SELECT m.meeting_id, m.meeting_time, m.meeting_until, m.meeting_subject, u.meeting_sure FROM " . MEETING_USER_TABLE . " u, " . MEETING_DATA_TABLE . " m
		WHERE u.user_id = " . $userdata['user_id'] . "
			AND u.meeting_id = m.meeting_id
			$sql_filter
			$sql_closed
		ORDER BY $sort_field $sort_order
		LIMIT $start, " . $board_config['topics_per_page'];
	if ( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Could not get meeting data', '', __LINE__, __FILE__, $sql);
	}

	$meetingrow = array();
	while ( $row = $db->sql_fetchrow($result) )
	{
		$meetingrow[] = $row;
	}
	$db->sql_freeresult($result);

	for ($i = 0; $i < sizeof($meetingrow); $i++)
	{
		$meeting_id = $meetingrow[$i]['meeting_id'];
		$meeting_subject = stripslashes($meetingrow[$i]['meeting_subject']);

		$meeting_check_time = $meetingrow[$i]['meeting_time'];
		$meeting_check_until = $meetingrow[$i]['meeting_until'];

		$meeting_time = create_date($board_config['default_dateformat'], $meetingrow[$i]['meeting_time'], $board_config['board_timezone']);
		$meeting_until = create_date($board_config['default_dateformat'], $meetingrow[$i]['meeting_until'], $board_config['board_timezone']);

		$meeting_closed = ( $meeting_check_time < time() ) ? $lang['Yes'] : ( ( $meeting_check_until < time() ) ? $lang['Meeting_no_period'] : $lang['No'] );
		
		$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

		$template->assign_block_vars('meeting_overview_row', array(
			'ROW_CLASS' => $row_class,
			'MEETING_SUBJECT' => $meeting_subject,
			'MEETING_TIME' => $meeting_time,
			'MEETING_UNTIL' => $meeting_until,
			'MEETING_CLOSED' => $meeting_closed,
			'MEETING_DETAIL' => $lang['Meeting_detail'],
			'U_MEETING_DETAIL' => append_sid("meeting.$phpEx?mode=detail&amp;m_id=$meeting_id&amp;start=$start"))
		);
	}
}
else
{
	// Output message if no meeting was found
	$template->assign_block_vars('no_meeting_row', array(
		'L_NO_MEETING' => $lang['No_meeting'])
	);
}

//
// Force password update
//
if ($board_config['password_update_days'])
{
	include($phpbb_root_path . 'includes/update_password.'.$phpEx);
}

// Parse and display the page
$template->pparse('body');

// Include the board footer with phpBB copyright (we hope so!)
include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>