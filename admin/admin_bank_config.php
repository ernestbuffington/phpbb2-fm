<?php
/** 
 *
* @package admin
* @version $Id: admin_bank_config.php,v 1.51.2.9 2004/11/18 17:49:33 acydburn Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
	$file = basename(__FILE__);
//	$module['Points_sys_settings']['Bank_settings'] = $file;
	return;
}

//
// Let's set the root dir for phpBB
//
$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);


//
// Include language files
//
$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_bank.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_bank.' . $phpEx);

if ( isset($HTTP_GET_VARS['action']) || isset($HTTP_POST_VARS['action']) ) 
{ 
	$action = ( isset($HTTP_POST_VARS['action']) ) ? $HTTP_POST_VARS['action'] : $HTTP_GET_VARS['action']; 
}
else 
{ 
	$action = ''; 
}


if ( $action == 'editaccount' )
{
	$username = ( isset($HTTP_POST_VARS['username']) ) ? $HTTP_POST_VARS['username'] : '';

	// Check username & get account information
	$user_row = get_userdata($username);
		
	$sql = "SELECT * 
		FROM " . BANK_TABLE . " 
		WHERE user_id = '" . $user_row['user_id'] . "'";
	if ( !($result = $db->sql_query($sql)) ) 
	{ 
		message_die(GENERAL_ERROR, 'Could not obtain bank information', '', __LINE__, __FILE__, $sql);
	}
	
	if ( !($db->sql_numrows($result)) )
	{
		message_die(GENERAL_MESSAGE, $lang['No_user_bank_account'] . '<br /><br />' . sprintf($lang['Click_return_config'], '<a href="' . append_sid("admin_bank_config.$phpEx") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.$phpEx?pane=right") . '">', '</a>'));
	}
	else
	{
		$row = $db->sql_fetchrow($result);
	}

	$template->assign_block_vars('edit_user', array());

	$fees_enabled = ($row['fees']) ? ' checked="checked"' : '';
	$fees_disabled = (!$row['fees']) ? ' checked="checked"' : '';
	
	$template->assign_vars(array(
		'L_BANK_TITLE' => $lang['Edit_user_account'],
		'L_BANK_EXPLAIN' => sprintf($lang['Bank_edituser_explain'], $username),
		'L_HOLDING' => $lang['Bank_balance'],
		'L_DEPOSITED' => $lang['Total_deposited'],
		'L_WITHDRAWN' => $lang['Total_withdrawn'],
		'L_FEES' => $lang['Withdraw_rate'],
		'L_POINTS' => $board_config['points_name'],
		'L_STATISTIC' => $lang['Statistic'],
		'L_VALUE' => $lang['Value'],
		
		'HOLDING' => $row['holding'],
		'WITHDRAWN' => $row['totalwithdrew'],
		'DEPOSITED' => $row['totaldeposit'],
		'S_FEES_ENABLED' => $fees_enabled,
		'S_FEES_DISABLED' => $fees_disabled,
		'USER_ID' => $user_row['user_id'])
	);
}
else if ( $action == 'updateaccount' )
{
	$user_id = ( isset($HTTP_POST_VARS['user_id']) ) ? intval($HTTP_POST_VARS['user_id']) : '';
	$holding = ( isset($HTTP_POST_VARS['holding']) ) ? intval($HTTP_POST_VARS['holding']) : 0;
	$deposited = ( isset($HTTP_POST_VARS['deposited']) ) ? intval($HTTP_POST_VARS['deposited']) : 0;
	$withdrawn = ( isset($HTTP_POST_VARS['withdrawn']) ) ? intval($HTTP_POST_VARS['withdrawn']) : 0;
	$fees = ( isset($HTTP_POST_VARS['fees']) ) ? intval($HTTP_POST_VARS['fees']) : 1;

	$sql = "SELECT * 
		FROM " . BANK_TABLE . " 
		WHERE user_id = " . $user_id;
	if ( !($result = $db->sql_query($sql)) ) 
	{ 
		message_die(GENERAL_ERROR, 'Could not obtain user bank information', '', __LINE__, __FILE__, $sql);
	}
	if ( !($db->sql_numrows($result)) ) 
	{ 
		message_die(GENERAL_MESSAGE, $lang['No_user_bank_account'] . '<br /><br />' . sprintf($lang['Click_return_config'], '<a href="' . append_sid("admin_bank_config.$phpEx") . '">', '</a>'). '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.$phpEx?pane=right") .'">', '</a>')); 
	}
	elseif ( !($row = $db->sql_fetchrow($result)) ) 
	{ 
		message_die(GENERAL_ERROR, 'Could not obtain user bank information', '', __LINE__, __FILE__, $sql);
	}

	$holding = ( $holding < 0 ) ? $row['holding'] : $holding;
	$withdrawn = ( $withdrawn < 0 ) ? $row['totalwithdrew'] : $withdrawn;
	$deposited = ( $deposited < 0 ) ? $row['totaldeposited'] : $deposited;

	$sql = "UPDATE " . BANK_TABLE . " 
		SET holding = '$holding', totalwithdrew = '$withdrawn', totaldeposit = '$deposited', fees = $fees
		WHERE user_id = " . $user_id;
	if ( !($db->sql_query($sql)) ) 
	{ 
		message_die(GENERAL_ERROR, 'Could not update user bank account', '', __LINE__, __FILE__, $sql);
	}

	message_die(GENERAL_MESSAGE, $lang['User_updated'] . '<br /><br />' . sprintf($lang['Click_return_config'], '<a href="' . append_sid("admin_bank_config.$phpEx") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.$phpEx?pane=right") . '">', '</a>'));

}
else
{
	$template->assign_block_vars('switch_config', array());
	
	//
	// Pull all config data
	//
	$sql = "SELECT *
		FROM " . CONFIG_TABLE;
	if(!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Could not query config information in admin_points_bank_config", "", __LINE__, __FILE__, $sql);
	}
	else
	{
		while( $row = $db->sql_fetchrow($result) )
		{
			$config_name = $row['config_name'];
			$config_value = $row['config_value'];
			$default_config[$config_name] = isset($HTTP_POST_VARS['submit']) ? str_replace("'", "\'", $config_value) : $config_value;
			
			$new[$config_name] = ( isset($HTTP_POST_VARS[$config_name]) ) ? $HTTP_POST_VARS[$config_name] : $default_config[$config_name];	
	
			if( isset($HTTP_POST_VARS['submit']) )
			{
				$sql = "UPDATE " . CONFIG_TABLE . " SET
					config_value = '" . str_replace("\'", "''", $new[$config_name]) . "'
					WHERE config_name = '$config_name'";
				if( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Failed to update general configuration for $config_name", "", __LINE__, __FILE__, $sql);
				}
			}
		}
		$db->sql_freeresult($result);

		if( isset($HTTP_POST_VARS['submit']) )
		{
			// Remove cache file
			@unlink($phpbb_root_path . 'cache/config_board.'.$phpEx);

			$message = $lang['Board_config_updated'] . "<br /><br />" . sprintf($lang['Click_return_config'], "<a href=\"" . append_sid("admin_bank_config.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");
	
			message_die(GENERAL_MESSAGE, $message);
		}
	}
	
	$bankopened_yes = ($new['bankopened']) ? ' checked="checked"' : '';
	$bankopened_no  = (!$new['bankopened']) ? ' checked="checked"' : '';

	//
	// Pull all bank data
	//
	$sql = "SELECT sum(holding) as holdings, SUM(totaldeposit) AS total_deposits, SUM(totalwithdrew) AS total_withdraws, COUNT(*) AS total_users
		FROM " . BANK_TABLE;
	if ( !($result = $db->sql_query($sql)) ) 
	{ 
		message_die(GENERAL_ERROR, "Could not query bank information", "", __LINE__, __FILE__, $sql);
	}
	$row2 = $db->sql_fetchrow($result);
	
	$template->assign_vars(array(
		'S_CONFIG_ACTION' => append_sid("admin_bank_config.$phpEx"),
	
		'L_BANK_TITLE' => $lang['Bank_settings'] . ' ' . $lang['Setting'],
		'L_BANK_EXPLAIN' => sprintf($lang['Config_explain'], $lang['Bank_settings']),
			
		'L_BANK_STATUS' => $lang['Bank_status'],
		'L_BANK_NAME' => $lang['Bank_name'],
		'L_BANK_INTEREST' => $lang['Interest_rate'],
		'L_BANK_INTEREST_EXPLAIN' => $lang['Percent'],
		'L_BANK_FEES' => $lang['Withdraw_rate'],
		'L_BANK_PAYOUT_TIME' => $lang['Interest_pay_time'],
		'L_BANK_PAYOUT_TIME_EXPLAIN' => $lang['Seconds'],
		'L_BANK_TOTAL_HOLDING' => $lang['Holding'],
		'L_BANK_TOTAL_DEPOSITED' => $lang['Total_deposited'],
		'L_BANK_TOTAL_WITHDRAWN' => $lang['Total_withdrawn'],
		'L_BANK_TOTAL_ACCOUNTS' => $lang['Total_accounts'],
		'L_USERNAME' => $lang['Username'],
		'L_LOOK_UP' => $lang['Edit_user_account'],
		'L_POINTS' => $board_config['points_name'],
		'L_MIN_DEPO' => $lang['Min_depo'],
		'L_MIN_WITH' => $lang['Min_with'],
		'L_DISABLE_INTEREST' => $lang['Disable_interest'],
		'L_ZERO_FOR_NONE' => $lang['Zero_for_none'],
		'L_STATISTIC' => $lang['Statistic'],
		'L_VALUE' => $lang['Value'],
		
		'S_BANKOPEN_YES' => $bankopened_yes,
		'S_BANKOPEN_NO' => $bankopened_no,
		'BANK_NAME' => $new['bankname'],
		'BANK_INTEREST' => $new['bankinterest'],
		'BANK_FEES' => $new['bankfees'],
		'BANK_PAYOUT_TIME' => $new['bankpayouttime'],
		'BANK_DISABLE_INTEREST' => $new['bank_interestcut'],
		'BANK_MIN_DEPO' => $new['bank_mindeposit'],
		'BANK_MIN_WITH' => $new['bank_minwithdraw'],
			
		'BANK_TOTAL_HOLDING' => number_format($row2['holdings']),
		'BANK_TOTAL_DEPOSITED' => number_format($row2['total_deposits']),	
		'BANK_TOTAL_WITHDRAWN' => number_format($row2['total_withdraws']),
		'BANK_TOTAL_ACCOUNTS' => number_format($row2['total_users']))
	);
}

//
// Generate the page
//
$template->set_filenames(array(
	'body' => 'admin/bank_config_body.tpl')
);

$template->pparse('body');

include('./page_footer_admin.'.$phpEx);

?>