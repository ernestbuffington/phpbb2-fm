<?php
/** 
*
* @package phpBB2
* @version $Id: profile_donations.php,v 1.0.0.1 2004/09/14 16:46:15 acydburn Exp $
* @copyright (c) 2004 Zou Xiong - Loewen Enterprise
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_PROFILE);
init_userprefs($userdata);
//
// End session management
//

$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;
$start = ($start < 0) ? 0 : $start;

if ( !$userdata['session_logged_in'] )
{
    $redirect = ( isset($start) ) ? "?start=$start" : '';
    redirect(append_sid("login.$phpEx?redirect=profile_donations.$phpEx" . $redirect, true));
    exit; 
}


//
// Generate a 'Show topics in previous x days' select box. If the topicsdays var is sent
// then get it's value, find the number of topics with dates newer than it (to properly
// handle pagination) and alter the main query
//
$previous_days = array(7, 14, 30, 90, 180, 364, 400);
$previous_days_text = array($lang['7_Days'], $lang['2_Weeks'], $lang['1_Month'], $lang['3_Months'], $lang['6_Months'], $lang['1_Year'], $lang['LW_ALL_RECORDS']);

$topic_days = 30;
if ( !empty($HTTP_POST_VARS['topicdays']) || !empty($HTTP_GET_VARS['topicdays']) )
{
	$topic_days = ( !empty($HTTP_POST_VARS['topicdays']) ) ? intval($HTTP_POST_VARS['topicdays']) : intval($HTTP_GET_VARS['topicdays']);

	if($topic_days > 0 && $topic_days < 400)
	{
		$min_topic_time = time() - ($topic_days * 86400);

		$limit_topics_time = " AND lw_date >= $min_topic_time";
	}
	else
	{
		$topic_days = 400;
		$limit_topics_time = '';
	}

	if ( !empty($HTTP_POST_VARS['topicdays']) )
	{
		$start = 0;
	}
}
else
{
	$limit_topics_time = '';
	$topic_days = 30;

	$min_topic_time = time() - ($topic_days * 86400);

	$limit_topics_time = " AND lw_date >= $min_topic_time";
}

if ( !empty($HTTP_POST_VARS['mode']) || !empty($HTTP_GET_VARS['mode']) )
{
	$donormode = (!empty($HTTP_POST_VARS['mode'])) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
}
else
{
	$donormode = 'viewall';
}

$donorswhere = '';
if(strcmp($donormode, 'viewcurrent') == 0)
{	
	//format can only be 2004/08/04 yyyy/mm/dd
	$starttime = $endtime = 0;
	
	if(strlen($board_config['donate_start_time']) == 10)
	{
		$starttime = mktime(0, 0, 0, substr($board_config['donate_start_time'], 5, 2), substr($board_config['donate_start_time'], 8, 2), substr($board_config['donate_start_time'], 0, 4) );
	}
	
	if(strlen($board_config['donate_end_time']) == 10)
	{
		$endtime = mktime(0, 0, 0, substr($board_config['donate_end_time'], 5, 2), substr($board_config['donate_end_time'], 8, 2), substr($board_config['donate_end_time'], 0, 4) );
	}	
	
	if($starttime > 0)
	{
		if($endtime <= $starttime)
		{
			$donorswhere = ' AND a.lw_date >= ' . $starttime;
		}
		else
		{
			$donorswhere = ' AND a.lw_date >= ' . $starttime . ' AND a.lw_date <= ' . $endtime;
		}
	}
}

$select_topic_days = '<select name="topicdays">';
for($i = 0; $i < sizeof($previous_days); $i++)
{
	$selected = ($topic_days == $previous_days[$i]) ? ' selected="selected"' : '';
	$select_topic_days .= '<option value="' . $previous_days[$i] . '"' . $selected . '>' . $previous_days_text[$i] . '</option>';
}
$select_topic_days .= '</select>';

$user_id = 0;
if(isset($HTTP_POST_VARS['userid']))
{
	$user_id = intval(trim(htmlspecialchars($HTTP_POST_VARS['userid']))) + 0;
} 
else if(isset($HTTP_GET_VARS['userid']))
{
	$user_id = intval(trim(htmlspecialchars($HTTP_GET_VARS['userid']))) + 0;
}
else
{
	$user_id = ($userdata['user_id']);
}

if($user_id != $userdata['user_id'] && $userdata['user_level'] != ADMIN)
{
	$message = $lang['LW_NO_PRIVILEGE'] . '<br /><br />' .  sprintf($lang['Click_return_index'], '<a href="' . append_sid("index.$phpEx") . '">', '</a>');

	message_die(GENERAL_MESSAGE, $message);
}

$page_title = $lang['LW_DONATIONS'];
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

$template->set_filenames(array(
   	'body' => 'profile_donations_body.tpl')
);
make_jumpbox('viewforum.'.$phpEx);

$hidden_fields = '<input type="hidden" name="mode" value="' . $donormode . '">';

$template->assign_vars(array(
	'L_LW_MONEY' => $lang['LW_ACCT_AMOUNT'],
	'L_LW_COMMENT' => $lang['LW_ACCT_COMMENT'],
	'L_DATE' => $lang['Date'],
	'L_DISPLAY_TOPICS' => $lang['LW_DONORS_DISPLAY_FROM'],
	'S_SELECT_TOPIC_DAYS' => $select_topic_days,
	'S_RECORDS_DAYS_ACTION' => 'profile_donations.'.$phpEx,
	'LW_HIDDEN_FIELDS' => $hidden_fields)
);

$topics_count = $i = 0;
$sql = "SELECT COUNT(*) 
	FROM " . ACCT_HIST_TABLE . " a, " . USERS_TABLE . " u 
	WHERE a.comment LIKE 'Donation from%' 
		AND u.user_id = a.user_id 
	$limit_topics_time $donorswhere";
if (!($result = $db->sql_query($sql)))
{
	message_die(GENERAL_ERROR, 'Could not query total donations', '', __LINE__, __FILE__, $sql);
}

if($row = $db->sql_fetchrow($result))
{
	$topics_count = $row["COUNT(*)"];
}
$db->sql_freeresult($result);

$sql = "SELECT a.*, u.* 
	FROM " . ACCT_HIST_TABLE . " a, " . USERS_TABLE . " u
	WHERE a.comment LIKE 'Donation from%' 
		AND u.user_id = a.user_id
	$limit_topics_time $donorswhere 
	ORDER BY lw_date DESC 
	LIMIT $start, " . $board_config['topics_per_page'];
if (!($result = $db->sql_query($sql)))
{
	message_die(GENERAL_ERROR, 'Could not query donations', '', __LINE__, __FILE__, $sql);
}

while ( $row = $db->sql_fetchrow($result) ) 
{
	if($row['user_id'] == ANONYMOUS)
	{
		$last_donor = $lang['LW_ANONYMOUS_DONOR'];
	}
	else
	{
		$last_donor = '<a href="' . append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=" . $row['user_id']) . '" class="genmed">' . username_level_color($row['username'], $row['user_level'], $row['user_id']) . '</a>';
	}

    $row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

	$template->assign_block_vars('donation', array(
	    'ROW_CLASS' => $row_class, 
		'LW_DONORS_NAME' => $last_donor,
		'LW_MONEY' => $row['lw_money'] . ' ' . $board_config['paypal_currency_code'],
		'LW_DATE' => create_date($board_config['default_dateformat'], $row['lw_date'], $board_config['board_timezone']))
	);
	$i++;
}
$db->sql_freeresult($result);

if ($topics_count > 0)
{
   	$template->assign_block_vars('switch_records', array());

	$template->assign_vars(array(
		'PAGINATION' => generate_pagination("profile_donations.$phpEx?topicdays=$topic_days&amp;userid=$user_id&amp;mode=$donormode", $topics_count, $board_config['topics_per_page'], $start),
		'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / $board_config['topics_per_page'] ) + 1 ), ceil( $topics_count / $board_config['topics_per_page'] )),

		'TOTAL_RECORDS' => $topics_count,
		'L_GOTO_PAGE' => $lang['Goto_page'])
	);
}

//
// No records
//
if ($topics_count <= 0)
{
	$template->assign_block_vars('switch_lw_no_records', array());

	$template->assign_vars(array(
		'L_LW_NO_RECORDS' => sprintf($lang['LW_NO_DONATIONS'], '<a href="' . append_sid('donate.'.$phpEx) . '">', '</a>'))
	);
}

include($phpbb_root_path . 'profile_menu.'.$phpEx);

$template->pparse('body');

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>