<?php
/** 
*
* @package phpBB
* @version $Id: profile_subscriptions.php,v 1.0.0.1 2004/10/29 17:49:33 acydburn Exp $
* @copyright (c) 2004 Loewen Enterprise - Xiong Zou
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
    redirect(append_sid("login.$phpEx?redirect=profile_subscriptions.$phpEx" . $redirect, true));
	exit; 
} 

//
// Include language
//
$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_ipn_grp.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_ipn_grp.' . $phpEx);


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

		$limit_topics_time = "AND lw_date >= $min_topic_time";
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

$page_title = $lang['L_IPN_user_sub_info'];
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

$template->set_filenames(array(
   	'body' => 'profile_subscriptions_body.tpl')
);
make_jumpbox('viewforum.'.$phpEx); 

$hidden_fields = '<input type="hidden" name="userid" value="' . $user_id . '">';
$template->assign_vars(array(
	'L_LW_CURRENCY' => $lang['LW_ACCT_CURRENCY'],
	'L_LW_MONEY' => $lang['LW_ACCT_AMOUNT'],
	'L_LW_PLUS_MINUS' => $lang['LW_ACCT_PLUS_MINUS'],
	'L_LW_TXNID' => $lang['LW_ACCT_TXNID'],
	'L_LW_STATUS' => $lang['LW_ACCT_STATUS'],
	'L_LW_COMMENT' => $lang['LW_ACCT_COMMENT'],
	'L_DATE' => $lang['Date'],
	'L_DISPLAY_TOPICS' => $lang['LW_ACCT_DISPLAY_FROM'],
	'S_SELECT_TOPIC_DAYS' => $select_topic_days,
	'S_RECORDS_DAYS_ACTION' => 'profile_subscriptions.' . $phpEx,
	'LW_HIDDEN_FIELDS' => $hidden_fields)
);

$topics_count = $i = 0;
$sql = "SELECT COUNT(*) 
	FROM " . ACCT_HIST_TABLE . " 
	WHERE comment NOT LIKE 'Donation from%' 
	   AND user_id = $user_id 
	$limit_topics_time";
if (!($result = $db->sql_query($sql)))
{
	message_die(GENERAL_ERROR, 'Could not query total donations', '', __LINE__, __FILE__, $sql);
}

if($row = $db->sql_fetchrow($result))
{
	$topics_count = $row["COUNT(*)"];
}
$db->sql_freeresult($result);

$sql = "SELECT * 
	FROM " . ACCT_HIST_TABLE . " 
	WHERE comment NOT LIKE 'Donation from%' 
		AND user_id = $user_id
	$limit_topics_time
	ORDER BY lw_date DESC
	LIMIT $start, " . $board_config['topics_per_page'];
if (!($result = $db->sql_query($sql)))
{
	message_die(GENERAL_ERROR, 'Could not query donations', '', __LINE__, __FILE__, $sql);
}

while ( $row = $db->sql_fetchrow($result) ) 
{
    $row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

   	$template->assign_block_vars('topicrow', array(
		'ROW_CLASS' => $row_class,
	   	'LW_CURRENCY' => $row['MNY_CURRENCY'],
		'LW_MONEY' => $row['lw_money'],
		'LW_PLUS_MINUS' => ($row['lw_plus_minus'] == 1 ? $lang['LW_ACCT_CREDIT'] : $lang['LW_ACCT_DEBIT']),
		'LW_TXNID' => $row['txn_id'],
		'LW_STATUS' => $row['status'],
		'LW_DATE' => create_date($board_config['default_dateformat'], $row['lw_date'], $board_config['board_timezone']),
		'LW_COMMENT' => $row['comment'])
   	);
	$i++;
}
$db->sql_freeresult($result);

if ($topics_count > 0)
{
   	$template->assign_block_vars('switch_records', array());

	$template->assign_vars(array(
		'PAGINATION' => generate_pagination("profile_subscriptions.$phpEx?topicdays=$topic_days&amp;userid=$user_id", $topics_count, $board_config['topics_per_page'], $start),
		'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / $board_config['topics_per_page']) + 1 ), ceil( $topics_count / $board_config['topics_per_page'])),

		'TOTAL_RECORDS' => $topics_count,
		'L_GOTO_PAGE' => $lang['Goto_page'])
	);
}

//
// No records
//
if ($topics_count <= 0)
{
	$template->assign_block_vars('switch_lw_no_records', array() );

	$template->assign_vars(array(
		'L_LW_NO_RECORDS' => $lang['LW_NO_RECORDS'])
	);
}

include($phpbb_root_path . 'profile_menu.'.$phpEx);

$template->pparse('body');

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>