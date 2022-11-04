<?php
/** 
*
* @package phpBB2
* @version $Id:	donate.php,v 1.0.0.1 2004/10/23 17:49:33 acydburn Exp $
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

if(strlen($board_config['paypal_p_acct']) <= 0)
{
	message_die(GENERAL_ERROR, $lang['LW_PAYPAL_ACCT_ERROR']);
	exit;
}

//
// template file
//
$page_title = $lang['LW_ACCT_DONATE_US'];
include($phpbb_root_path . 'includes/page_header.'.$phpEx);
$template->set_filenames(array(
	'body' => 'donate_body.tpl')
);
make_jumpbox('viewforum.'.$phpEx);

$currencydisplay = $currencyoptions = '';

if(strlen($board_config['donate_currencies']) < 4)
{
	$board_config['donate_currencies'] = $board_config['paypal_currency_code'] . ';';
}
$currencyoptions = '<select name="currency_code">';

if(strpos($board_config['donate_currencies'], 'USD', 0) !== false)
{
	$currencydisplay = 'US Dollar';
	$selected = '';
	if(strcasecmp($board_config['paypal_currency_code'], 'USD') == 0)
	{
		$selected = ' selected="selected"';
	}
	$currencyoptions .= '<option value="USD"' . $selected . '>' . $currencydisplay . '</option>';
}
if(strpos($board_config['donate_currencies'], 'EUR', 0) !== false)
{
	$currencydisplay = 'Euros';
	$selected = '';
	if(strcasecmp($board_config['paypal_currency_code'], 'EUR') == 0)
	{
		$selected = ' selected="selected"';
	}
	$currencyoptions .= '<option value="EUR"' . $selected . '>' . $currencydisplay . '</option>';
}
if(strpos($board_config['donate_currencies'], 'GBP', 0) !== false)
{
	$currencydisplay = 'Pounds Sterling';
	$selected = '';
	if(strcasecmp($board_config['paypal_currency_code'], 'GBP') == 0)
	{
		$selected = ' selected="selected"';
	}
	$currencyoptions .= '<option value="GBP"' . $selected . '>' . $currencydisplay . '</option>';
}
if(strpos($board_config['donate_currencies'], 'CAD', 0) !== false)
{
	$currencydisplay = 'Canadian Dollar';
	$selected = '';
	if(strcasecmp($board_config['paypal_currency_code'], 'CAD') == 0)
	{
		$selected = ' selected="selected"';
	}
	$currencyoptions .= '<option value="CAD"' . $selected . '>' . $currencydisplay . '</option>';
}
if(strpos($board_config['donate_currencies'], 'JPY', 0) !== false)
{
	$currencydisplay = 'Yen';
	$selected = '';
	if(strcasecmp($board_config['paypal_currency_code'], 'JPY') == 0)
	{
		$selected = ' selected="selected"';
	}
	$currencyoptions .= '<option value="JPY"' . $selected . '>' . $currencydisplay . '</option>';
}
$currencyoptions .= '</select>';

$template->assign_vars(array(
	'L_LW_TOPUP_TITLE' => $page_title, 	
	'L_LW_AMOUNT_TO_PAY' => $lang['LW_AMOUNT_TO_DONATE'], 	
	'L_LW_AMOUNT_TO_PAY_EXPLAIN' => $lang['LW_AMOUNT_TO_DONATE_EXPLAIN'],
	'L_LW_CURRENCY_TO_PAY' => $lang['LW_CURRENCY_TO_PAY'],
	'L_LW_CURRENCY_TO_PAY_EXPLAIN' => sprintf($lang['LW_CURRENCY_TO_PAY_EXPLAIN'], $board_config['donate_currencies']),
	'LW_WANT_ANONYMOUS' => $lang['LW_WANT_ANONYMOUS'],
	'L_LW_DONATE_WAY' => $lang['L_LW_DONATE_WAY'],

	'S_LW_TOPUP' => append_sid('donate.'.$phpEx),
	'LW_PAYPAL_ACTION' => append_sid('donate_confirm.'.$phpEx),	
	'LW_CURRENCY_OPTIONS' => $currencyoptions)
);
	
$template->pparse('body');

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>