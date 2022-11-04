<?php
/** 
*
* @package phpBB2
* @version $Id: profile_mypoints.php,v 0.99 2002/11/16 09:20:00 conanqtran Exp $
* @copyright (c) 2002 Conan Tran
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
$userdata = session_pagestart($user_ip, PAGE_TRANSACTIONS);
init_userprefs($userdata);
//
// End session management
//

if ( !$userdata['session_logged_in'] ) 
{ 
	redirect("login.".$phpEx."?redirect=profile_mypoints.".$phpEx); 
	exit; 
} 


//
// Generate page
//
$page_title = $lang['My_Trans'];
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

$template->set_filenames(array(
	'body' => 'profile_mypoints_body.tpl')
);
make_jumpbox('viewforum.'.$phpEx);

$template->assign_vars(array(
	'L_TRANS_FROM' => $lang['Trans_From'],
	'L_TRANS_TO' => $lang['Trans_To'],
	'L_TRANS_AMOUNT' => $board_config['points_name'],
	'L_TRANS_REASON' => $lang['Points_reason'],
	'L_TRANS_DATE' => $lang['Date'],
	'L_MY_TRANS' => $lang['My_Trans'],
	'L_GLOBAL_TRANS' => $lang['Global_Trans'],
	'L_VIEW_THE_REST' => $lang['View_The_Rest'],
	'L_TOTAL_TRANS' => $lang['Total_Trans'],
	'L_RECENT_TRANS_TO' => $lang['Recent_Trans_To'],
	'L_RECENT_TRANS_FROM' => $lang['Recent_Trans_From'])
);

//
// Show all transactions made to your account
//
$sql = "SELECT t.*, u.user_id, u.user_level
	FROM " . TRANSACTION_TABLE . " t
		LEFT JOIN " . USERS_TABLE . " AS u ON t.trans_from = u.username
		WHERE t.trans_to = '" . phpbb_clean_username($userdata['username']) . "'
	ORDER BY t.trans_date DESC 
	LIMIT 0, 10";
if( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not query transaction', '', __LINE__, __FILE__, $sql);
}

if ( $row = $db->sql_fetchrow($result) )
{
	$i = 0;
	do
	{
		$trans_from = username_level_color($row['trans_from'], $row['user_level'], $row['user_id']);
		$trans_amount = ( $row['trans_amount'] ) ? $row['trans_amount'] : 0;
		$trans_reason = ( $row['trans_reason'] ) ? $row['trans_reason'] : $lang['None'];

		$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

		$template->assign_block_vars('transtorow', array(
			'ROW_NUMBER' => $i + ( $HTTP_GET_VARS['start'] + 1 ),
			'ROW_CLASS' => $row_class,
			'TRANS_FROM' => $trans_from,
			'TRANS_AMOUNT' => $trans_amount,
			'TRANS_REASON' => $trans_reason,
			'TRANS_DATE' => create_date($board_config['default_dateformat'], $row['trans_date'], $board_config['board_timezone']))
		);

		$i++;
	}
	while ( $row = $db->sql_fetchrow($result) );
}

//
// Now show total amount of points sent to your account
//
$sql = "SELECT sum(trans_amount) AS total_sent_to
	FROM " . TRANSACTION_TABLE . "
	WHERE trans_to = '" . phpbb_clean_username($userdata['username']) . "'";
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Error getting total transactions', '', __LINE__, __FILE__, $sql);
}

if ( $total_sent_to = $db->sql_fetchrow($result) )
{
	$total_money = $total_sent_to['total_sent_to'];
	
	$template->assign_block_vars('totalsendto', array(
		'TOTAL_SENT_TO' => (!empty($total_money)) ? $total_money : 0)
	);
}

//
// Show all transactions that you made through your account
//
$sql = "SELECT t.*, u.user_id, u.user_level
	FROM " . TRANSACTION_TABLE . " t
		LEFT JOIN " . USERS_TABLE . " AS u ON t.trans_to = u.username
		WHERE t.trans_from = '" . phpbb_clean_username($userdata['username']) . "'
	ORDER BY trans_date DESC 
	LIMIT 0, 10";
if( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not query transaction', '', __LINE__, __FILE__, $sql);
}

if ( $row = $db->sql_fetchrow($result) )
{
	$i = 0;
	do
	{
		$trans_to = username_level_color($row['trans_to'], $row['user_level'], $row['user_id']);
		$trans_amount = ( $row['trans_amount'] ) ? $row['trans_amount'] : 0;
		$trans_reason = ( $row['trans_reason'] ) ? $row['trans_reason'] : $lang['None'];
		$trans_date = create_date($board_config['default_dateformat'], $row['trans_date'], $board_config['board_timezone']);

		$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

		$template->assign_block_vars('transfromrow', array(
			'ROW_NUMBER' => $i + ( $HTTP_GET_VARS['start'] + 1 ),
			'ROW_CLASS' => $row_class,
			'TRANS_FROM' => $trans_from,
			'TRANS_TO' => $trans_to,
			'TRANS_AMOUNT' => $trans_amount,
			'TRANS_REASON' => $trans_reason,
			'TRANS_DATE' => $trans_date)
		);

		$i++;
	}
	while ( $row = $db->sql_fetchrow($result) );
}

//
// Now show total amount of point you have sent through your account
//
$sql = "SELECT sum(trans_amount) AS total_sent_from
	FROM " . TRANSACTION_TABLE . "
	WHERE trans_from = '" . phpbb_clean_username($userdata['username']) . "'";
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Error getting total transactions', '', __LINE__, __FILE__, $sql);
}

if ( $total_sent_from = $db->sql_fetchrow($result) )
{
	$total_money = $total_sent_from['total_sent_from'];
	
	$template->assign_block_vars('totalsendfrom', array(
		'TOTAL_SENT_FROM' => (!empty($total_money)) ? $total_money : 0)
	);
}

include($phpbb_root_path . 'profile_menu.'.$phpEx);

$template->pparse('body');

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>