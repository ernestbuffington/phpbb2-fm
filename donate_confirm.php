<?php
/** 
*
* @package phpBB2
* @version $Id:	donate_confirm.php,v 1.0.0.1 2004/10/23 17:49:33 acydburn Exp $
* @copyright (c) 2004 Loewen Enterprise - Xiong Zou
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);

//
// Set page ID for session management
//
$userdata = session_pagestart($user_ip, PAGE_INDEX);
init_userprefs($userdata);
//
// End session management
//

if (strlen($board_config['paypal_b_acct']) <= 0 || strlen($board_config['paypal_p_acct']) <= 0 )
{
	message_die(GENERAL_ERROR, $lang['LW_PAYPAL_ACCT_ERROR']);
}

$page_title = $lang['LW_ACCT_DONATE_US'];
include($phpbb_root_path . 'includes/page_header.'.$phpEx);
$template->set_filenames(array(
	'body' => 'donate_confirm_body.tpl')
);

$server_url = 'http://' . $board_config['server_name'];
$server_url .= ($board_config['server_port'] == 80) ? '' : ':' . $board_config['server_port'];
$server_url .= $board_config['script_path'];
$pos = strpos($board_config['script_path'], '/', (strlen($board_config['script_path']) - 2));
if($pos === false)
{
	$server_url .= '/';
}

$notifyurl = $server_url . 'donate_result.'.$phpEx;
$returnurl = $server_url . 'donate_showresult.'.$phpEx;

$anonymous = intval($HTTP_POST_VARS['lw_anonymous']) + 0;
if ($anonymous != 1)
{
	$anonymous = 0;
}

$amountopay = htmlspecialchars($HTTP_POST_VARS['amount']) + 0.00;
$currency = htmlspecialchars($HTTP_POST_VARS['currency_code']);

if (strlen($board_config['donate_currencies']) < 4) //if not set, so just use the primary currency code
{
	$board_config['donate_currencies'] = $board_config['paypal_currency_code'] . ';';
}

if ($amountopay <= 0 || strpos($board_config['donate_currencies'], $currency) === false)
{
	$message = $lang['LW_PAYMENT_DATA_ERROR'] . '<br /><br />' . sprintf($lang['Click_return_login'], '<a href="' . append_sid('donate.'.$phpEx) . '">', '</a>') . '<br /><br />' .  sprintf($lang['Click_return_index'], '<a href="' . append_sid("index.$phpEx") . '">', '</a>');

	message_die(GENERAL_MESSAGE, $message);
}

$receiveacct = $board_config['paypal_b_acct'];
if ($amountopay < 1)
{
	$receiveacct = $board_config['paypal_p_acct'];
}

//modify to add posts count
$l_lw_amount_pay = $lang['LW_AMOUNT_TO_DONATE'] . ': <b>' . $amountopay . ' ' . $currency . '</b>';

$poster_convertor = lw_cal_cash_exchange_rate($currency, $board_config) + 0; 
if ($poster_convertor <= 0)
{
	$poster_convertor = 1.0;
}
	
$lw_mny_payee = ($amountopay + 0.00) / ($poster_convertor);

if (!$userdata['session_logged_in'])
{
	// do nothing	
}
else if (intval($board_config['donate_to_points']) > 0)
{
	$l_lw_amount_pay .= '<br />' . sprintf($lang['LW_DONATION_TO_POINTS'], $board_config['points_name'], intval(intval($board_config['donate_to_points']) * $lw_mny_payee));
}
else if (intval($board_config['donate_to_posts']) > 0)
{
	$l_lw_amount_pay .= '<br />' . sprintf($lang['LW_DONATION_TO_POSTS'], $board_config['points_name'], intval(intval($board_config['donate_to_posts']) * $lw_mny_payee));
}  

$template->assign_vars(array(
	'S_LW_TOPUP'		=> append_sid('donate.'.$phpEx),
	'L_LW_TOPUP'		=> $page_title,
	'LW_PAYPAL_ACTION'	=> 'https://www.paypal.com/cgi-bin/webscr',
	'L_LW_TOPUP_TITLE'	=> $lang['LW_DONATE_CONFIRM_TITLE'],
	'L_LW_AMOUNT_TO_PAY' => $l_lw_amount_pay,
	'LW_PAYPAL_LOGO' 	=> $images['paypal_donate'],
	'LW_PAY_AMOUNT'		=> $amountopay,
	'LW_PAY_CURRENCY'	=> $currency,
	'LW_BUSINESS_ACCT'	=> $receiveacct,
	'LW_ITEM_NAME'		=> sprintf($lang['LW_DONATION_TO_WHO'], $board_config['sitename']),
	'LW_ITEM_NUMBER'	=> (($userdata['user_id']) <= 0 ? 0 : $userdata['user_id']) . '-' . $anonymous,
	'LW_NOTIFY_URL'		=> $notifyurl,
	'LW_RETURN_URL'		=> $returnurl,
	'LW_CANCEL_RETURN_URL'	=> $returnurl)
);

$template->pparse('body');

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>