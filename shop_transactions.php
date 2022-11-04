<?php
/** 
*
* @package phpBB2
* @version $Id: shop_transactions.php,v 0.90 2003/1/04 20:00:00 LaZeR Exp $
* @copyright (c) 2003 LaZeR : Original Concept by Conan Tran
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);

// Include language file
$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_shop.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_shop.' . $phpEx);

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
	redirect("login.".$phpEx."?redirect=shop_transactions.".$phpEx); 
	exit; 
} 

if ( !$board_config['shop_trans_enable'] ) 
{ 
	message_die(GENERAL_MESSAGE, $lang['Shop_trans_disabled'] . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid('index.'.$phpEx) .'">', '</a>')); 
}

$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;
$start = ($start < 0) ? 0 : $start;

if ( isset($HTTP_GET_VARS['mode']) || isset($HTTP_POST_VARS['mode']) )
{
	$mode = ( isset($HTTP_POST_VARS['mode']) ) ? htmlspecialchars($HTTP_POST_VARS['mode']) : htmlspecialchars($HTTP_GET_VARS['mode']);
}
else
{
	$mode = 'trans_date';
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
$mode_types_text = array($lang['Date'], $lang['Username'], $lang['Trans_Item'], $lang['Trans_Type'], $lang['Trans_Cost'], $lang['Sort_Top_Ten_Trans']);
$mode_types = array('transdate', 'transuser', 'transitem', 'transtype', 'transtotal', 'topten');

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
$page_title = $lang['Shop_Transaction'];
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

$template->set_filenames(array(
	'body' => 'shop_transactions_body.tpl')
);
make_jumpbox('viewforum.'.$phpEx);

$template->assign_vars(array(
	'L_SELECT_SORT_METHOD' => $lang['Sort_by'],
	'L_ORDER' => $lang['Order'],

	'L_TRANS_USER' => $lang['Username'],
	'L_TRANS_ITEM' => $lang['Trans_Item'],
	'L_TRANS_TOTAL' => $lang['Trans_Cost'],
	'L_SHOPTRANS_DATE' => $lang['Date'],
	'L_MONEY_SYMBOL' => $lang['Money_Symbol'],
	'L_TRANS_TYPE' => $lang['Trans_Type'],

	'S_MODE_SELECT' => $select_sort_mode,
	'S_ORDER_SELECT' => $select_sort_order,
	'S_MODE_ACTION' => append_sid('shop_transactions.'.$phpEx))
);

switch( $mode )
{
	case 'transdate':
		$order_by = "shoptrans_date $sort_order LIMIT $start, " . $board_config['topics_per_page'];
		break;
	case 'transuser':
		$order_by = "trans_user $sort_order LIMIT $start, " . $board_config['topics_per_page'];
		break;
	case 'transitem':
		$order_by = "trans_item $sort_order LIMIT $start, " . $board_config['topics_per_page'];
		break;
	case 'transtype':
    	$order_by = "trans_type $sort_order LIMIT $start, " . $board_config['topics_per_page'];
		break;
	case 'transtotal':
		$order_by = "trans_total $sort_order LIMIT $start, " . $board_config['topics_per_page'];
		break;
	case 'topten':
		$order_by = "trans_total DESC LIMIT 10";
		break;
	default:
		$order_by = "shoptrans_date DESC LIMIT $start, " . $board_config['topics_per_page'];
		break;
}

$sql = "SELECT s.*, u.user_id, u.username, u.user_level
	FROM " . SHOPTRANS_TABLE . " s, " . USERS_TABLE . " u 
		WHERE s.trans_user = u.user_id
	ORDER BY $order_by";
if( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not obtain shop transaction data.', '', __LINE__, __FILE__, $sql);
}

if ( $row = $db->sql_fetchrow($result) )
{
	$template->assign_block_vars('trans', array());

	$i = 0;
	do
	{
		$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

		$template->assign_block_vars('transrow', array(
			'ROW_NUMBER' => $i + ( $HTTP_GET_VARS['start'] + 1 ),
			'ROW_CLASS' => $row_class,
			'TRANS_USER' => username_level_color($row['username'], $row['user_level'], $row['user_id']),
			'TRANS_ITEM' => $row['trans_item'],
			'TRANS_TYPE' => $row['trans_type'],
			'TRANS_TOTAL' => ((!empty($row['trans_total'])) ? $row['trans_total'] : 0),
			'SHOPTRANS_DATE' => create_date($board_config['default_dateformat'], $row['shoptrans_date'], $board_config['board_timezone']),
			
			'U_VIEWPROFILE' => append_sid('profile.'.$phpEx.'?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $row['user_id']))
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
	$sql = "SELECT COUNT(*) AS total
		FROM " . SHOPTRANS_TABLE;
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Error getting total transactions', '', __LINE__, __FILE__, $sql);
	}

	if ( $total = $db->sql_fetchrow($result) )
	{
		$total_transactions = $total['total'];

		$pagination = generate_pagination("shop_transactions.$phpEx?mode=$mode&amp;order=$sort_order", $total_transactions, $board_config['topics_per_page'], $start). '&nbsp;';
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

$template->pparse('body');

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>