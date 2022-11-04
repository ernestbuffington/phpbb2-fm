<?php
/** 
*
* @package phpBB2
* @version $Id:	donate_showresult.php,v 1.0.0.1 2004/10/23 17:49:33 acydburn Exp $
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

if(isset($HTTP_POST_VARS['txn_id']) || isset($HTTP_GET_VARS['tx']))
{
	$txn_id = isset($HTTP_POST_VARS['txn_id']) ? htmlspecialchars($HTTP_POST_VARS['txn_id']) : htmlspecialchars($HTTP_GET_VARS['tx']);
}
if(isset($HTTP_POST_VARS['mc_currency']) || isset($HTTP_GET_VARS['cc']))
{
	$payment_currency = isset($HTTP_POST_VARS['mc_currency']) ? htmlspecialchars($HTTP_POST_VARS['mc_currency']) : htmlspecialchars($HTTP_GET_VARS['cc']);
}
if(isset($HTTP_POST_VARS['mc_gross']) || isset($HTTP_GET_VARS['amt']))
{
	$payment_amount = isset($HTTP_POST_VARS['mc_gross']) ? htmlspecialchars($HTTP_POST_VARS['mc_gross']) : htmlspecialchars($HTTP_GET_VARS['amt']);
}
if(isset($HTTP_POST_VARS['payment_status']) || isset($HTTP_GET_VARS['st']))
{
	$payment_status = isset($HTTP_POST_VARS['payment_status']) ? htmlspecialchars($HTTP_POST_VARS['payment_status']) : htmlspecialchars($HTTP_GET_VARS['st']);
}

if(strcasecmp($payment_status, 'Completed') == 0)
{
	$message .= $lang['LW_DONATE_DONE'];
}
else if(strcasecmp($payment_status, 'Pending') == 0)
{
	$message .= $lang['LW_DONATE_DONE'];
}
else if(strcasecmp($payment_status, 'Denied') == 0)
{
	$message .= $lang['LW_DONATE_DENIED'];
}
else if(strcasecmp($payment_status, 'Failed') == 0)
{
	$message .= $lang['LW_DONATE_FAILED'];
}
else
{
	$message .= $lang['LW_DONATE_DONE'];
}
	
$message .= '<br /><br />' .  sprintf($lang['Click_return_index'], '<a href="' . append_sid("index.$phpEx") . '">', '</a>');

message_die(GENERAL_MESSAGE, $message);
exit;
		
?>