<?php
/** 
*
* @package phpBB2
* @version $Id: transactions.php,v 0.90 2003/031/06 20:00:00 conanqtran Exp $
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

$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;
$start = ($start < 0) ? 0 : $start;

if ( isset($HTTP_GET_VARS['mode']) || isset($HTTP_POST_VARS['mode']) )
{
	$mode = ( isset($HTTP_POST_VARS['mode']) ) ? htmlspecialchars($HTTP_POST_VARS['mode']) : htmlspecialchars($HTTP_GET_VARS['mode']);
}
else
{
	$mode = 'transdate';
}

if(isset($HTTP_POST_VARS['order']))
{
	$sort_order = ($HTTP_POST_VARS['order'] == 'ASC') ? 'ASC' : 'DESC';
}
else if(isset($HTTP_GET_VARS['order']))
{
	$sort_order = ($HTTP_GET_VARS['order'] == 'ASC') ? 'ASC' : 'DESC';
}
else
{
	$sort_order = 'ASC';
}

//
// Transactions sorting
//
$mode_types_text = array($lang['Sort_Trans_Date'], $lang['Sort_Trans_From'], $lang['Sort_Trans_To'], $lang['Sort_Trans_Amount'], $lang['Sort_Top_Ten_Trans']);
$mode_types = array('transdate', 'transfrom', 'transto', 'transamount', 'toptentrans');

$select_sort_mode = '<select name="mode">';
for($i = 0; $i < sizeof($mode_types_text); $i++)
{
	$selected = ( $mode == $mode_types[$i] ) ? ' selected="selected"' : '';
	$select_sort_mode .= '<option value="' . $mode_types[$i] . '"' . $selected . '>' . $mode_types_text[$i] . '</option>';
}
$select_sort_mode .= '</select>';

$select_sort_order = '<select name="order">';
if($sort_order == 'ASC')
{
	$select_sort_order .= '<option value="ASC" selected="selected">' . $lang['Sort_Ascending'] . '</option><option value="DESC">' . $lang['Sort_Descending'] . '</option>';
}
else
{
	$select_sort_order .= '<option value="ASC">' . $lang['Sort_Ascending'] . '</option><option value="DESC" selected="selected">' . $lang['Sort_Descending'] . '</option>';
}
$select_sort_order .= '</select>';

//
// Generate page
//
$page_title = $lang['Global_Trans'];
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

$template->set_filenames(array(
	'body' => 'profile_transactions_body.tpl')
);
make_jumpbox('viewforum.'.$phpEx);

$template->assign_vars(array(
	'L_SELECT_SORT_METHOD' => $lang['Sort_by'],
	'L_ORDER' => $lang['Order'],

	'L_TRANS_FROM' => $lang['Trans_From'],
	'L_TRANS_TO' => $lang['Trans_To'],
	'L_TRANS_AMOUNT' => $board_config['points_name'],
	'L_TRANS_DATE' => $lang['Date'],
	'L_TRANS_REASON' => $lang['Points_reason'],
	'L_CUSTOM_POINT_NAME' => $lang['Custom_Point_Name'],

	'S_MODE_SELECT' => $select_sort_mode,
	'S_ORDER_SELECT' => $select_sort_order,
	'S_MODE_ACTION' => append_sid("transactions.$phpEx"))
);

switch( $mode )
{
	case 'transdate':
		$order_by = "t.trans_date $sort_order LIMIT $start, " . $board_config['topics_per_page'];
		break;
	case 'transfrom':
		$order_by = "t.trans_from $sort_order LIMIT $start, " . $board_config['topics_per_page'];
		break;
	case 'transto':
		$order_by = "t.trans_to $sort_order LIMIT $start, " . $board_config['topics_per_page'];
		break;
	case 'transamount':
		$order_by = "t.trans_amount $sort_order LIMIT $start, " . $board_config['topics_per_page'];
		break;
	case 'toptentrans':
		$order_by = "t.trans_amount DESC LIMIT 10";
		break;
	default:
		$order_by = "t.trans_date DESC LIMIT $start, " . $board_config['topics_per_page'];
		break;
}

$sql = "SELECT t.*, u.user_id, u.user_level, u2.user_id AS user_id_to, u2.user_level AS user_level_to
	FROM " . TRANSACTION_TABLE . " t
		LEFT JOIN " . USERS_TABLE . " AS u ON t.trans_from = u.username
		LEFT JOIN " . USERS_TABLE . " AS u2 ON t.trans_to = u2.username
	ORDER BY $order_by";
if( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not obtain transaction data.', '', __LINE__, __FILE__, $sql);
}

if ( $row = $db->sql_fetchrow($result) )
{
	$template->assign_block_vars('trans', array());

	$i = 0;
	do
	{
		$trans_from = username_level_color($row['trans_from'], $row['user_level'], $row['user_id']);
		$trans_to = username_level_color($row['trans_to'], $row['user_level_to'], $row['user_id_to']);
		$trans_amount = ( $row['trans_amount'] ) ? $row['trans_amount'] : 0;
		$trans_reason = ( $row['trans_reason'] ) ? $row['trans_reason'] : $lang['None'];

		$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

		$template->assign_block_vars('transrow', array(
			'ROW_NUMBER' => $i + ( $HTTP_GET_VARS['start'] + 1 ),
			'ROW_CLASS' => $row_class,
			'TRANS_FROM' => $trans_from,
			'TRANS_TO' => $trans_to,
			'TRANS_AMOUNT' => $trans_amount,
			'TRANS_REASON' => $trans_reason,
			'TRANS_DATE' => create_date($board_config['default_dateformat'], $row['trans_date'], $board_config['board_timezone']))
		);

		$i++;
	}
	while ( $row = $db->sql_fetchrow($result) );
}
else
{
	$template->assign_block_vars('none', array(
		'L_NONE' => $lang['None'])
	);
}

if ( $mode != 'topten' || $board_config['topics_per_page'] < 10 )
{
	$sql = "SELECT count(*) AS total
		FROM " . TRANSACTION_TABLE;
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Error getting total transactions', '', __LINE__, __FILE__, $sql);
	}

	if ( $total = $db->sql_fetchrow($result) )
	{
		$total_transactions = $total['total'];

		$pagination = generate_pagination("transactions.$phpEx?mode=$mode&amp;order=$sort_order", $total_transactions, $board_config['topics_per_page'], $start). '&nbsp;';
	}
}
else
{
	$pagination = '&nbsp;';
	$total_transactions = 10;
}

$template->assign_vars(array(
	'TOTAL_TRANSACTIONS' => $total_transactions,
	'PAGINATION' => $pagination,
	'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / $board_config['topics_per_page'] ) + 1 ), ceil( $total_transactions / $board_config['topics_per_page'] )),
	'L_GOTO_PAGE' => $lang['Goto_page'])
);

include($phpbb_root_path . 'profile_menu.'.$phpEx);

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