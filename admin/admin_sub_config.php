<?php
/** 
*
* @package admin
* @version $Id:	admin_sub_config.php,v 1.0.0.1 2004/10/29 17:49:33 acydburn Exp $
* @copyright (c) 2004 Loewen Enterprise - Xiong Zou
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
	$filename = basename(__FILE__);
//	$module['Donations_Subscriptions']['Configuration'] = $filename;

	return;
}

//
// Let's set the root dir for phpBB
//
$phpbb_root_path = '../';
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);
include($phpbb_root_path . 'includes/functions_selects.'.$phpEx);


//
// Check to see what mode we should operate in.
//
if( isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']) )
{
	$mode = ( isset($HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
	$mode = htmlspecialchars($mode);
}
else
{
	$mode = 'ipn';
}


//
// Include language
//
$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_ipn_grp.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_ipn_grp.' . $phpEx);


//
// Pull all config data
//
$sql = "SELECT *
	FROM " . CONFIG_TABLE;
if(!$result = $db->sql_query($sql))
{
	message_die(GENERAL_ERROR, "Could not query config information in admin_sub_config", '', __LINE__, __FILE__, $sql);
}
else
{
	while( $row = $db->sql_fetchrow($result) )
	{
		$config_name = $row['config_name'];
		$config_value = addslashes($row['config_value']);
		$default_config[$config_name] = $config_value;
		
		$new[$config_name] = ( isset($HTTP_POST_VARS[$config_name]) ) ? $HTTP_POST_VARS[$config_name] : $default_config[$config_name];
		$new[$config_name] = stripslashes($new[$config_name]);

		if( isset($HTTP_POST_VARS['submit']) )
		{
			$sql = "UPDATE " . CONFIG_TABLE . " SET config_value = '" . addslashes($new[$config_name]) . "'
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

		$message = $lang['Board_config_updated'] . "<br /><br />" . sprintf($lang['Click_return_config'], "<a href=\"" . append_sid("admin_sub_config.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

		message_die(GENERAL_MESSAGE, $message);
	}
}


//
// Check to see what section we should load
//
switch($mode)
{
	case 'currency':
		$template->assign_block_vars('switch_currency', array());
		break;
	case 'donate':
		$template->assign_block_vars('switch_donate', array());
		break;
	case 'subscribe':
		$template->assign_block_vars('switch_subscribe', array());
		break;
	case 'ipn':
	default:
		$template->assign_block_vars('switch_ipn', array());
		break;
}
$hidden_fields = '<input type="hidden" name="mode" value="' . $mode . '" />';


$template->set_filenames(array(
	'body' => 'admin/sub_config_body.tpl')
);

$list_top_donors_yes = ( $new['list_top_donors'] ) ? 'checked="checked"' : '';
$list_top_donors_no = ( !$new['list_top_donors'] ) ? 'checked="checked"' : '';

$template->assign_vars(array(
	'S_ACTION' => append_sid('admin_sub_config.'.$phpEx),

	'L_SUB_SETTINGS_TITLE' => $lang['Donations_Subscriptions'] . ' ' . $lang['Setting'],
	'L_SUB_SETTINGS_EXPLAIN' => sprintf($lang['Config_explain'],  $lang['Donations_Subscriptions']),
	
	'L_SUB_SETTINGS' => $lang['L_SUB_SETTINGS'],
	'L_SUB_EXTRA_DAYS' => $lang['L_SUB_EXTRA_DAYS'],
	'L_SUB_EXTRA_DAYS_EXPLAIN' => $lang['L_SUB_EXTRA_DAYS_EXPLAIN'],
	'L_LW_PAYPAL_SETTINGS' => $lang['LW_PAYPAL_ACCT_SETTINGS_TITLE'],
	'L_LW_OUR_PAYPAL_ACCT' => $lang['LW_OUR_PAYPAL_ACCT'],
	'L_LW_OUR_PAYPAL_ACCT_EXPLAIN' => $lang['LW_OUR_PAYPAL_ACCT_EXPLAIN'],
	'L_LW_PAYPAL_CURRENCY_CODE' => $lang['LW_ACCT_PRIMARY_CURRENCY'],
	'L_LW_PAYPAL_CURRENCY_CODE_EXPLAIN' => $lang['LW_OUR_PAYPAL_CURRENCY_CODE'],
	'L_LW_TRIAL_PERIOD' => $lang['LW_TRIAL_PERIOD'],
	'L_LW_TRIAL_PERIOD_EXPLAIN' => $lang['LW_TRIAL_PERIOD_EXPLAIN'],
	'L_DONATION_SETTINGS' => $lang['L_DONATION_SETTINGS'],
	'L_LW_BUSINESS_PAYPAL_ACCT' => $lang['L_LW_BUSINESS_PAYPAL_ACCT'],
	'L_LW_BUSINESS_PAYPAL_ACCT_EXPLAIN' => $lang['L_LW_BUSINESS_PAYPAL_ACCT_EXPLAIN'],
	'L_LW_DISPLAY_X_DONORS' => $lang['L_LW_DISPLAY_X_DONORS'],
	'L_LW_DISPLAY_X_DONORS_EXPLAIN' => $lang['L_LW_DISPLAY_X_DONORS_EXPLAIN'],
	'L_LW_DONATION_DESCRIPTION' => $lang['L_LW_DONATION_DESCRIPTION'],
	'L_LW_DONATION_DESCRIPTION_EXPLAIN' => $lang['L_LW_DONATION_DESCRIPTION_EXPLAIN'],
	'L_LW_DONATION_GOAL' => $lang['L_LW_DONATION_GOAL'],
	'L_LW_DONATION_GOAL_EXPLAIN' => $lang['L_LW_DONATION_GOAL_EXPLAIN'],
	'L_LW_DONATION_START' => $lang['L_LW_DONATION_START'],
	'L_LW_DONATION_START_EXPLAIN' => $lang['L_LW_DONATION_START_EXPLAIN'],
	'L_STARTS' => $lang['L_LW_DONATION_STARTS'],
	'L_ENDS' => $lang['L_LW_DONATION_ENDS'],
	'L_LW_DONATION_POINTS' => sprintf($lang['L_LW_DONATION_POINTS'], $board_config['points_name']),
	'L_LW_DONATION_POINTS_EXPLAIN' => sprintf($lang['L_LW_DONATION_POINTS_EXPLAIN'], $board_config['points_name']),
	'L_LW_TOP_DONORS' => $lang['L_LW_TOP_DONORS'],
	'L_TOP' => sprintf($lang['L_LW_TOP_DONORS_TITLE'], ''),
	'L_LAST' => sprintf($lang['L_LW_LAST_DONORS'], ''),
	'L_LW_POSTS_COUNTS' => $lang['L_LW_POSTS_COUNTS'],
	'L_LW_POSTS_COUNTS_EXPLAIN' => $lang['L_LW_POSTS_COUNTS_EXPLAIN'],
	'L_LW_DONATE_TOGRP_ONE' => $lang['L_LW_DONATE_TOGRP_ONE'],
	'L_LW_DONATE_TOGRP_ONE_EXPLAIN' => $lang['L_LW_DONATE_TOGRP_ONE_EXPLAIN'],
	'L_LW_TOGRPONE_AMOUNT' => $lang['L_LW_TOGRPONE_AMOUNT'],
	'L_LW_TOGRPONE_AMOUNT_EXPLAIN' => $lang['L_LW_TOGRPONE_AMOUNT_EXPLAIN'],
	'L_LW_DONATE_TOGRP_TWO' => $lang['L_LW_DONATE_TOGRP_TWO'],
	'L_LW_DONATE_TOGRP_TWO_EXPLAIN' => $lang['L_LW_DONATE_TOGRP_TWO_EXPLAIN'],
	'L_LW_TOGRPTWO_AMOUNT' => $lang['L_LW_TOGRPTWO_AMOUNT'],
	'L_LW_TOGRPTWO_AMOUNT_EXPLAIN' => $lang['L_LW_TOGRPTWO_AMOUNT_EXPLAIN'],
	'L_LW_TORANK_ID' => $lang['L_LW_TORANK_ID'],
	'L_LW_TORANK_ID_EXPLAIN' => $lang['L_LW_TORANK_ID_EXPLAIN'],
	'L_CURRENCY_GENERAL_SETTINGS' => $lang['L_CURRENCY_GENERAL_SETTINGS'],
	'L_DONATE_CURRENCY' => $lang['L_DONATE_CURRENCY'],
	'L_DONATE_CURRENCY_EXPLAIN' => $lang['L_DONATE_CURRENCY_EXPLAIN'],
	'L_DONATE_USD_TO_PRI' => $lang['L_DONATE_USD_TO_PRI'],
	'L_DONATE_EUR_TO_PRI' => $lang['L_DONATE_EUR_TO_PRI'],
	'L_DONATE_GBP_TO_PRI' => $lang['L_DONATE_GBP_TO_PRI'],
	'L_DONATE_CAD_TO_PRI' => $lang['L_DONATE_CAD_TO_PRI'],
	'L_DONATE_JPY_TO_PRI' => $lang['L_DONATE_JPY_TO_PRI'],
	'L_DONATE_USD_TO_PRI_EXPLAIN' => $lang['L_DONATE_USD_TO_PRI_EXPLAIN'],
	'L_DONATE_EUR_TO_PRI_EXPLAIN' => $lang['L_DONATE_EUR_TO_PRI_EXPLAIN'],
	'L_DONATE_GBP_TO_PRI_EXPLAIN' => $lang['L_DONATE_GBP_TO_PRI_EXPLAIN'],
	'L_DONATE_CAD_TO_PRI_EXPLAIN' => $lang['L_DONATE_CAD_TO_PRI_EXPLAIN'],
	'L_DONATE_JPY_TO_PRI_EXPLAIN' => $lang['L_DONATE_JPY_TO_PRI_EXPLAIN'],

	'DONATE_CURRENCY' => $new['donate_currencies'],
	'DONATE_USD_TO_PRI' => $new['usd_to_primary'],
	'DONATE_EUR_TO_PRI' => $new['eur_to_primary'],
	'DONATE_GBP_TO_PRI' => $new['gbp_to_primary'],
	'DONATE_CAD_TO_PRI' => $new['cad_to_primary'],
	'DONATE_JPY_TO_PRI' => $new['jpy_to_primary'],
	'TOP_DONORS_YES' => $list_top_donors_yes,
	'TOP_DONORS_NO' => $list_top_donors_no,
	'LW_POSTS_COUNTS' => $new['donate_to_posts'], 
	'LW_BUSINESS_PAYPAL_ACCT' => $new['paypal_b_acct'], 
	'LW_DISPLAY_X_DONORS' => $new['dislay_x_donors'], 
	'LW_DONATION_DESCRIPTION' => $new['donate_description'], 
	'LW_DONATION_GOAL' => $new['donate_cur_goal'], 
	'LW_DONATION_START' => $new['donate_start_time'], 
	'LW_DONATION_END' => $new['donate_end_time'], 
	'LW_DONATION_POINTS' => $new['donate_to_points'], 
	'LW_DONATE_TOGRP_ONE' => $new['donate_to_grp_one'], 
	'LW_TOGRPONE_AMOUNT' => $new['to_grp_one_amount'], 
	'LW_DONATE_TOGRP_TWO' => $new['donate_to_grp_two'], 
	'LW_TOGRPTWO_AMOUNT' => $new['to_grp_two_amount'], 
	'LW_TORANK_ID' => $new['donor_rank_id'], 
	'SUB_EXTRA_DAYS' => $new['extra_days_for_sub'],
	'LW_PAYPAL_P_ACCT' => $new['paypal_p_acct'],
	'LW_PAYPAL_CURRENCY_CODE' => $new['paypal_currency_code'],
	'LW_TRIAL_PERIOD' => $new['lw_trial_period'],
		
	// All pages
	'CONFIG_SELECT' => config_select($mode, array(
		'ipn' => $lang['LW_PAYPAL_ACCT_SETTINGS_TITLE'],
		'currency' => $lang['L_CURRENCY_GENERAL_SETTINGS'],
		'donate' => $lang['L_DONATION_SETTINGS'],
		'subscribe' => $lang['L_SUB_SETTINGS'])
	),
	
	'S_HIDDEN_FIELDS' => $hidden_fields)		
);

$template->pparse('body');

include('./page_footer_admin.'.$phpEx);

?>