<?php
/** 
*
* @package phpBB
* @version $Id: bank.php,v 1.5.0 2003 zarath Exp $
* @copyright (c) 2002 Zarath
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.' . $phpEx);

// include language file
$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_bank.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_bank.' . $phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_BANK);
init_userprefs($userdata);
//
// End session management
//

if ( !$userdata['session_logged_in'] ) 
{ 
	redirect("login.".$phpEx."?redirect=bank.".$phpEx); 
	exit; 
} 

if ( $board_config['bankpayouttime'] < 1 ) 
{ 
     message_die(GENERAL_MESSAGE, $lang['Error_payouttime_short']);
}

if ( !$board_config['bankopened'] )
{ 
	message_die(GENERAL_MESSAGE, $lang['Error_bank_closed']); 
}

$time = time();
if (($time - $board_config['banklastrestocked']) > $board_config['bankpayouttime'])
{
	$sql = "UPDATE " . CONFIG_TABLE . " 
		SET config_value = $time
		WHERE config_name = 'banklastrestocked'";
	if ( !($db->sql_query($sql)) ) 
	{ 
		message_die(GENERAL_ERROR, 'Could not update bank last stocked time.', '', __LINE__, __FILE__, $sql);
	}

	// Remove cache file
	@unlink($phpbb_root_path . 'cache/config_board.'.$phpEx);
	
	$interesttime = (($time - $board_config['banklastrestocked']) / $board_config['bankpayouttime']);

	$sql = 'UPDATE ' . BANK_TABLE . '
		SET holding = holding + round(((holding / 100) * ' . $board_config['bankinterest'] . ') * ' . $interesttime . ') ' . ( ( $board_config['bank_interestcut'] ) ? "WHERE holding < " . $board_config['bank_interestcut'] : "" );
	if ( !($db->sql_query($sql)) ) 
	{ 
		message_die(GENERAL_ERROR, 'Could not update bank interest', '', __LINE__, __FILE__, $sql); 
	}
	header("Location: bank.php");
}

$sql = "SELECT * 
	FROM " . BANK_TABLE . " 
	WHERE user_id = " . $userdata['user_id'];
if ( !($result = $db->sql_query($sql)) ) 
{ 
	message_die(GENERAL_ERROR, 'Could not obtain user information.', '', __LINE__, __FILE__, $sql);
}
$row = $db->sql_fetchrow($result);

if ( isset($HTTP_GET_VARS['action']) || isset($HTTP_POST_VARS['action']) )
{
	$action = ( isset($HTTP_POST_VARS['action']) ) ? htmlspecialchars($HTTP_POST_VARS['action']) : htmlspecialchars($HTTP_GET_VARS['action']);
}
else
{
	$action = '';
}

// Default bank (bank-info) page
if (empty($action))
{
	$template->set_filenames(array(
		'body' => 'bank_body.tpl')
	);
	make_jumpbox('viewforum.'.$phpEx); 

	if ( !isset($row['holding']) && $userdata['user_id'] > 0 )
	{ 
		$message = $lang['No_account'] . '<br /><br />' . sprintf($lang['Click_open_account'], "<a href=\"" . append_sid("bank.$phpEx?action=createaccount") . "\">", "</a>") . '<br /><br />' . sprintf($lang['Click_return_index'], "<a href=\"" . append_sid("index.$phpEx") . "\">", "</a>"); 
		
		message_die(GENERAL_MESSAGE, $message, $lang['Bank']);
	}
	else if ( $userdata['user_id'] > 0 )
	{
		$template->assign_block_vars('has_account', array());
	}

	$sql = "SELECT sum(holding) as total_holding, count(user_id) as total_users
		FROM " . BANK_TABLE . " 
		WHERE user_id > 0";
	if ( !($result = $db->sql_query($sql)) ) 
	{ 
		message_die(GENERAL_ERROR, 'Could not obtain bank users', '', __LINE__, __FILE__, $sql); 
	}
	$b_row = $db->sql_fetchrow($result);

	$bankholdings = ( $b_row['total_holding'] ) ? $b_row['total_holding'] : 0;
	$bankusers = $b_row['total_users'];

	$withdrawtotal = (!empty($row['fees'])) ? $row['holding'] - (round($row['holding'] / 100 * $board_config['bankfees'])) : $row['holding'];

	if ( !empty($row['fees']) )
	{
		$template->assign_block_vars('switch_withdraw_fees', array());
	}
	if ( $board_config['bank_minwithdraw'] )
	{
		$template->assign_block_vars('switch_min_with', array());
	}
	if ( $board_config['bank_mindeposit'] )
	{
		$template->assign_block_vars('switch_min_depo', array());
	}

	$page_title = $board_config['bankname'] . ' :: ' . $lang['Deposit_withdraw']; 

	$template->assign_vars(array(
		'L_BANK_TITLE' => $board_config['bankname'],
		'L_OPEN_SINCE' => $lang['Bank_opened'],
		'L_INTEREST_RATE' => $lang['Interest_rate'],
		'L_WITHDRAW_RATE' => $lang['Withdraw_rate'],
		'L_TOTAL_ACCS' => $lang['Total_accounts'],
		'L_HOLDING' => $lang['Holding'],
		
		'L_BANK_INFO' => $lang['Bank_info'],
		'L_ACC_OPEN' => $lang['Account_opened'],
		'L_USER_BALANCE' => $lang['Current_balance'],
		'L_DEPOSIT_POINTS' => $lang['Deposit'] . ' ' . $board_config['points_name'],
		'L_DEPOSIT' => $lang['Deposit'],
		'L_WITHDRAW_POINTS' => $lang['Withdraw'] . ' ' . $board_config['points_name'],
		'L_WITHDRAW' => $lang['Withdraw'],
		'L_MIN_DEPO' => $lang['Min_depo'],
		'L_MIN_WITH' => $lang['Min_with'],
		'L_MAX_DEPO' => $lang['Max_amount'] . ' ' . $board_config['points_name'] . ' ' . $lang['Can_deposit'],
		'L_MAX_WITH' => $lang['Max_amount'] . ' ' . $board_config['points_name'] . ' ' . $lang['Can_withdraw'],

		'BANK_OPENED' => create_date($board_config['default_dateformat'], $board_config['bankopened'], $board_config['board_timezone']),
		'BANK_HOLDINGS' => number_format($bankholdings) . ' ' . $board_config['points_name'],
		'BANK_ACCOUNTS' => number_format($bankusers),
		'BANK_FEES' => $board_config['bankfees'],
		'BANK_INTEREST' => $board_config['bankinterest'],
		'BANK_MIN_WITH' => $board_config['bank_minwithdraw'] . ' ' . $board_config['points_name'],
		'BANK_MIN_DEPO' => $board_config['bank_mindeposit'] . ' ' . $board_config['points_name'],

		'ACC_OPENED' => create_date($board_config['default_dateformat'], $row['opentime'], $board_config['board_timezone']),
		'USER_BALANCE' => number_format($row['holding']) . ' ' . $board_config['points_name'],
		'USER_GOLD' => $userdata['user_points'],
		'USER_WITHDRAW' => $withdrawtotal,

		'U_WITHDRAW' => append_sid("bank.$phpEx?action=withdraw"),
		'U_DEPOSIT' => append_sid("bank.$phpEx?action=deposit"))
	);
}
elseif ($action == 'createaccount')
{
	if ( !$userdata['session_logged_in'] )
	{
		$redirect = "bank.$phpEx&action=createaccount";
		header('Location: ' . append_sid("login.$phpEx?redirect=$redirect", true));
	}

	if (is_numeric($row['holding'])) 
	{ 
		message_die(GENERAL_MESSAGE, $lang['Yes_account'] . '<br /><br />' . sprintf($lang['Click_return_bank'], '<a href="' . append_sid('bank.'.$phpEx) . '">', '</a>'));
	}
	else
	{
		$sql = "INSERT INTO " . BANK_TABLE . "(user_id, opentime, fees) 
			VALUES (" . $userdata['user_id'] . ", " . time() . ", 1)";
		if ( !($db->sql_query($sql)) ) 
		{ 
			message_die(GENERAL_ERROR, 'Could not add user bank account', '', __LINE__, __FILE__, $sql); 
		}

		message_die(GENERAL_MESSAGE, $lang['Welcome_bank'] . ' ' . $board_config['bankname'] . ', ' . $lang['Start_balance'] . '<br /><br />' . $lang['Your_account'] . '<br /><br >' . sprintf($lang['Click_return_bank'], '<a href="' . append_sid('bank.'.$phpEx) . '">', '</a>'));
	}
}
else if ($action == 'deposit')
{
	$deposit = ( isset($HTTP_POST_VARS['deposit']) ) ? intval($HTTP_POST_VARS['deposit']) : 0;

	if ( !$userdata['session_logged_in'] )
	{
		$redirect = 'bank.'.$phpEx;
		$redirect .= ( isset($action) ) ? '&action=' . $action : '';
		$redirect .= ( isset($deposit) ) ? '&deposit=' . $deposit : '';
		header('Location: ' . append_sid("login.$phpEx?redirect=$redirect", true));
	}

	if ( $deposit < $board_config['bank_mindeposit'] ) 
	{ 
		$message = $deposit . sprintf($lang['Deposit_small_amount'], $board_config['bank_mindeposit'], $board_config['points_name']) . "<br /><br />" . sprintf($lang['Click_return_bank'], "<a href=\"" . append_sid("bank.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_index'], "<a href=\"" . append_sid("index.$phpEx") . "\">", "</a>");
		
		message_die(GENERAL_MESSAGE, $message); 
	}
	else if ($deposit < 1) 
	{ 
		$message = $lang['Error_deposit'] . "<br /><br />" . sprintf($lang['Click_return_bank'], "<a href=\"" . append_sid("bank.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_index'], "<a href=\"" . append_sid("index.$phpEx") . "\">", "</a>");
	
		message_die(GENERAL_MESSAGE, $message);
	}
	else if ($deposit > $userdata['user_points']) 
	{ 
		$message = $lang['Error_not_enough_deposit'] . "<br /><br />" . sprintf($lang['Click_return_bank'], "<a href=\"" . append_sid("bank.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_index'], "<a href=\"" . append_sid("index.$phpEx") . "\">", "</a>");
	
		message_die(GENERAL_MESSAGE, $message);
	}
	
	$sql = "UPDATE " . USERS_TABLE . " 
		SET user_points = (user_points - $deposit)
		WHERE user_id = " . $userdata['user_id'];
	if ( !($db->sql_query($sql)) ) 
	{ 
		message_die(GENERAL_ERROR, 'Could not update users points.', '', __LINE__, __FILE__, $sql); 
	}

	$sql = "INSERT INTO " . TRANSACTION_TABLE . " (trans_date, trans_from, trans_to, trans_amount) 
		VALUES (" . time() . ", '" . str_replace("'", "\'", $userdata['username']) . "', 'Deposited to bank', $deposit)"; 
	if( !$db->sql_query($sql) ) 
	{ 
		message_die(GENERAL_ERROR, "Could not insert bank transaction record.", '', __LINE__, __FILE__, $sql); 
	}

	$sql = "UPDATE " . BANK_TABLE . " 
		SET holding = (holding + $deposit), totaldeposit = (totaldeposit + $deposit)
		WHERE user_id = " . $userdata['user_id'];
	if ( !($db->sql_query($sql)) ) 
	{ 
		message_die(GENERAL_ERROR, 'Could not update bank totals.', '', __LINE__, __FILE__, $sql); 
	}

	$message = $lang['Have_deposit'] . ' ' . number_format($deposit) . ' ' . $board_config['points_name'] . ' ' . $lang['To_account'] . '<br /><br />' . $lang['New_balance'] . ' ' . number_format(($row['holding'] + $deposit)) . ', ' . $lang['Leave_with'] . ' ' . number_format(($userdata['user_points'] - $deposit)) . ' ' . $board_config['points_name'] . '<br /><br />' . sprintf($lang['Click_return_bank'], '<a href="' . append_sid('bank.'.$phpEx) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid('index.'.$phpEx) . '">', '</a>');

	message_die(GENERAL_MESSAGE, $message);
}
else if ($action == 'withdraw')
{
	$withdraw = ( isset($HTTP_POST_VARS['withdraw']) ) ? intval($HTTP_POST_VARS['withdraw']) : 0;

	if ( !$userdata['session_logged_in'] )
	{
		$redirect = 'bank.'.$phpEx;
		$redirect .= ( isset($action) ) ? '&action=' . $action : '';
		$redirect .= ( isset($withdraw) ) ? '&withdraw=' . $withdraw : '';
		header('Location: ' . append_sid("login.$phpEx?redirect=$redirect", true));
	}

	if ( $withdraw < $board_config['bank_minwithdraw'] ) 
	{ 
		$message = sprintf($lang['Withdraw_small_amount'], $board_config['bank_minwithdraw'], $board_config['points_name']) . "<br /><br />" . sprintf($lang['Click_return_bank'], "<a href=\"" . append_sid("bank.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_index'], "<a href=\"" . append_sid("index.$phpEx") . "\">", "</a>");

		message_die(GENERAL_MESSAGE, $message); 
	}
	else if ($withdraw < 1) 
	{ 
		$message = $lang['Error_withdraw'] . "<br /><br />" . sprintf($lang['Click_return_bank'], "<a href=\"" . append_sid("bank.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_index'], "<a href=\"" . append_sid("index.$phpEx") . "\">", "</a>");

		message_die(GENERAL_MESSAGE, $message);
	}

	if (!empty($row['fees']))
	{
		$withdrawtotal = round((($withdraw / 100) * $board_config['bankfees']));
		if ( $withdrawtotal == 0 ) 
		{ 
			$withdrawtotal = 1; 
		}
	}
	else 
	{
		$withdrawtotal = 0;
	}
	$withdrawtotal = $withdrawtotal + $withdraw;

	if ($row['holding'] < $withdrawtotal) 
	{ 
		$message = $lang['Error_not_enough_withdraw'] . "<br /><br />" . sprintf($lang['Click_return_bank'], "<a href=\"" . append_sid("bank.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_index'], "<a href=\"" . append_sid("index.$phpEx") . "\">", "</a>");
	
		message_die(GENERAL_MESSAGE, $message);
	}
	
	$sql = "UPDATE " . USERS_TABLE . "
		SET user_points = (user_points + $withdraw)
		WHERE user_id = " . $userdata['user_id'];
	if ( !($db->sql_query($sql)) ) 
	{ 
		message_die(GENERAL_ERROR, 'Could not update users points.', '', __LINE__, __FILE__, $sql);
	}
			
	$sql = "INSERT INTO " . TRANSACTION_TABLE . " (trans_date, trans_from, trans_to, trans_amount) 
		VALUES ( " . time() . ", '" . str_replace("'", "\'", $userdata['username']) . "', 'Withdrawn from bank', '$withdraw')"; 
	if ( !($db->sql_query($sql)) ) 
	{ 
		message_die(GENERAL_ERROR, "Could not insert transaction.", '', __LINE__, __FILE__, $sql); 
	}

	$sql = "UPDATE " . BANK_TABLE . "
		SET holding = (holding - $withdrawtotal), totalwithdrew = (totalwithdrew + $withdraw)
		WHERE user_id = " . $userdata['user_id'];
	if ( !($db->sql_query($sql)) ) 
	{ 
		message_die(GENERAL_ERROR, 'Could not update users points.', '', __LINE__, __FILE__, $sql); 
	}
	
	$message = $lang['Have_withdraw'] . ' ' . number_format($withdraw) . ' ' . $board_config['points_name'] . ' ' . $lang['From_account'] . '<br /><br />' . $lang['New_balance'] . ' ' . number_format(($row['holding'] - $withdrawtotal)) . ', ' . $lang['Now_have'] . ' ' . number_format(($userdata['user_points'] + $withdraw))  . ' ' . $board_config['points_name'] . ' ' . $lang['On_hand'] . '<br /><br />' . sprintf($lang['Click_return_bank'], '<a href="' . append_sid('bank.'.$phpEx) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid('index.'.$phpEx) . '">', '</a>') . '</span><br />&nbsp;</td></tr>';

	message_die(GENERAL_MESSAGE, $message);
}
else 
{
	redirect('bank.'.$phpEx);
}
	
//
// Start output of page
//
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

//
// Generate the page
//
$template->pparse('body');

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>