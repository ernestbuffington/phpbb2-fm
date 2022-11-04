<?php
/** 
*
* @package admin
* @version $Id: admin_bookies_config.php,v 3.0.0 2004/11/17 17:49:33 majorflam Exp $
* @copyright (c) 2004 Majorflam
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', 1);

//
// First we do the setmodules stuff for the admin cp.
//
if( !empty($setmodules) )
{
	$filename = basename(__FILE__);
//	$module['Bookmakers']['Configuration'] = $filename;

	return;
}

//
// Load default header
//
$phpbb_root_path = './../';
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
	$mode = 'config';
}

//
// Check to see what section we should load
//
switch($mode)
{
	case 'game':
		$template->assign_block_vars('switch_game', array());
		break;
	case 'misc':
		$template->assign_block_vars('switch_misc', array());
		break;
	case 'config':
	default:
		$template->assign_block_vars('switch_config', array());
		break;
}
$hidden_fields = '<input type="hidden" name="mode" value="' . $mode . '" />';

//
// Output the authorisation details
//
$template->set_filenames(array(
	'body' => 'admin/admin_bookies_config.tpl')
);

//
// Include language
//
$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_admin_bookmakers.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_admin_bookmakers.' . $phpEx);

$edit_stake = $board_config['bookie_edit_stake'];
$allow_pm = $board_config['bookie_pm'];
$each_way = $board_config['bookie_eachway'];
$user_bets = $board_config['bookie_user_bets'];
$fractional_decimal = $board_config['bookie_frac_or_dec'];
$welcome_text = $board_config['bookie_welcome'];
$allow_commission = $board_config['bookie_allow_commission'];
$min_stake = $board_config['bookie_min_bet'];
$max_stake = $board_config['bookie_max_bet'];
$restrict = $board_config['bookie_restrict'];

$leader_box .= '<option value="" selected="selected">' . $board_config['bookie_leader'] . '</option>
	<option value="5">5</option>
	<option value="10">10</option>
	<option value="15">15</option>
	<option value="20">20</option>
</select>';

$commission_box .= '<option value="" selected="selected">' . $board_config['bookie_commission'] . '%</option>';
for ( $i = 1; $i < 101; $i++ )
{
	$commission_box .= '<option value="' . $i . '">' . $i . '%</option>';
}
$commission_box .= '</select>';

if ( isset($HTTP_POST_VARS['submit']) )
{
	$edit_stake = ( isset($HTTP_POST_VARS['editstake']) ) ? ( ($HTTP_POST_VARS['editstake']) ? TRUE : 0 ) : 0;
	$allow_pm = ( isset($HTTP_POST_VARS['allowpm']) ) ? ( ($HTTP_POST_VARS['allowpm']) ? TRUE : 0 ) : 0;
	$each_way = ( isset($HTTP_POST_VARS['alloweachway']) ) ? ( ($HTTP_POST_VARS['alloweachway']) ? TRUE : 0 ) : 0;
	$user_bets = ( isset($HTTP_POST_VARS['allowuserbets']) ) ? intval($HTTP_POST_VARS['allowuserbets']) : 0;
	$fractional_decimal = ( isset($HTTP_POST_VARS['fracdec']) ) ? ( ($HTTP_POST_VARS['fracdec']) ? TRUE : 0 ) : 0;
	$leader = intval($HTTP_POST_VARS['leader']);
	$welcome_text_input = htmlspecialchars($HTTP_POST_VARS['welcome_text']);
	$commission = intval($HTTP_POST_VARS['commission']);
	$allow_commission = ( isset($HTTP_POST_VARS['allow_commission']) ) ? ( ($HTTP_POST_VARS['allow_commission']) ? TRUE : 0 ) : 0;
	$min_stake = intval($HTTP_POST_VARS['min_stake']);
	$max_stake = intval($HTTP_POST_VARS['max_stake']);
	$restrict = intval($HTTP_POST_VARS['restrict']);
	
	if ( strlen($welcome_text_input) > 250 )
	{
		$welcome_text_input = $welcome_text;
	}
	$welcome_text = $welcome_text_input;
	
	//
	// are we changing commission percentage? If so, there are some restrictions to consider...
	//
	if ( !empty($commission) )
	{
		$sql = "SELECT COUNT(*) AS pending 
			FROM " . BOOKIE_ADMIN_BETS_TABLE . "
			WHERE multi != -5";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not update config table', '', __LINE__, __FILE__, $sql);
		}
		$row = $db->sql_fetchrow($result);
		if ( $row['pending'] )
		{
			$message = $lang['bookie_config_bets_outstanding'];
			
			message_die(GENERAL_MESSAGE, $message);
		}
	}
	
	$sql = "UPDATE " . CONFIG_TABLE . "
		SET config_value = '" . str_replace("\'", "''", $welcome_text) . "'
		WHERE config_name = 'bookie_welcome'";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not update config table', '', __LINE__, __FILE__, $sql);
	}
	
	$sql = "UPDATE " . CONFIG_TABLE . "
		SET config_value = '$edit_stake'
		WHERE config_name = 'bookie_edit_stake'";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not update config table', '', __LINE__, __FILE__, $sql);
	}
	
	$sql = "UPDATE " . CONFIG_TABLE . "
		SET config_value = '$allow_pm'
		WHERE config_name = 'bookie_pm'";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not update config table', '', __LINE__, __FILE__, $sql);
	}
	
	$sql = "UPDATE " . CONFIG_TABLE . "
		SET config_value = '$each_way'
		WHERE config_name = 'bookie_eachway'";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not update config table', '', __LINE__, __FILE__, $sql);
	}
	
	$sql = "UPDATE " . CONFIG_TABLE . "
		SET config_value = '$user_bets'
		WHERE config_name = 'bookie_user_bets'";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not update config table', '', __LINE__, __FILE__, $sql);
	}
	
	$sql = "UPDATE " . CONFIG_TABLE . "
		SET config_value = '$fractional_decimal'
		WHERE config_name = 'bookie_frac_or_dec'";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not update config table', '', __LINE__, __FILE__, $sql);
	}
	
	$sql = "UPDATE " . CONFIG_TABLE . "
		SET config_value = '$allow_commission'
		WHERE config_name = 'bookie_allow_commission'";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not update config table', '', __LINE__, __FILE__, $sql);
	}
	
	$sql = "UPDATE " . CONFIG_TABLE . "
		SET config_value = '$min_stake'
		WHERE config_name = 'bookie_min_bet'";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not update config table', '', __LINE__, __FILE__, $sql);
	}
	
	$sql = "UPDATE " . CONFIG_TABLE . "
		SET config_value = '$max_stake'
		WHERE config_name = 'bookie_max_bet'";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not update config table', '', __LINE__, __FILE__, $sql);
	}
	
	$sql = "UPDATE " . CONFIG_TABLE . "
		SET config_value = '$restrict'
		WHERE config_name = 'bookie_restrict'";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not update config table', '', __LINE__, __FILE__, $sql);
	}
	
	if ( !empty($leader) )
	{
		$sql = "UPDATE " . CONFIG_TABLE . "
			SET config_value = '$leader'
			WHERE config_name = 'bookie_leader'";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not update config table', '', __LINE__, __FILE__, $sql);
		}
	}
	
	if ( !empty($commission) )
	{
		$sql = "UPDATE " . CONFIG_TABLE . "
			SET config_value = '$commission'
			WHERE config_name = 'bookie_commission'";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not update config table', '', __LINE__, __FILE__, $sql);
		}
	}
	
	// Remove cache file
	@unlink($phpbb_root_path . 'cache/config_board.'.$phpEx);

	$message = $lang['Board_config_updated'] . "<br /><br />" . sprintf($lang['Click_return_config'], "<a href=\"" . append_sid("admin_bookies_config.$phpEx?mode=$mode") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");
	
	message_die(GENERAL_MESSAGE, $message);
}
	
// Set template Vars
$template->assign_vars(array(
	'CONFIG_HEADER' => $lang['Bookies'] . ' ' . $lang['Setting'],
	'CONFIG_EXPLAIN' => sprintf($lang['Config_explain'], $lang['Bookies']),
	
	'ALLOW_EDIT_STAKE' => $lang['bookie_allow_edit_stake'],
	'ALLOW_EDIT_STAKE_EXP' => $lang['bookie_allow_edit_stake_exp'],
	'ALLOW_SEND_PM' => $lang['bookie_allow_pm'],
	'ALLOW_SEND_PM_EXP' => $lang['bookie_allow_pm_exp'],
	'LEADERBOARD' => $lang['bookie_leaderboard'],
	'LEADERBOARD_EXP' => $lang['bookie_leaderboard_exp'],
	'ALLOW_EACH_WAY' => $lang['bookie_allow_each_way'],
	'ALLOW_EACH_WAY_EXP' => $lang['bookie_allow_each_way_exp'],
	'ALLOW_USER_BETS' => $lang['bookie_allow_user_bets'],
	'ALLOW_USER_BETS_EXP' => $lang['bookie_allow_user_bets_exp'],
	'FRAC_DEC' => $lang['bookie_frac_or_dec'],
	'FRAC_DEC_EXP' => $lang['bookie_frac_or_dec_exp'],
	'L_FRAC' => $lang['bookie_fractional'],
	'L_DEC' => $lang['bookie_decimal'],
	'WELCOME' => $lang['bookie_config_welcome'],
	'WELCOME_EXP' => $lang['bookie_config_welcome_exp'],
	'L_CONDITION' => $lang['bookie_userbet_conditional'],
	'ALLOW_COMMISSION' => $lang['bookie_config_all_com'],
	'ALLOW_COMMISSION_EXP' => $lang['bookie_config_all_com_exp'],
	'COMMISSION' => $lang['bookie_config_com_per'],
	'COMMISSION_EXP' => $lang['bookie_config_com_per_exp'],
	'BOOKIE_SETTINGS' => $lang['bookie_config_bookie_set'],
	'MISC_SETTINGS' => $lang['bookie_config_bookie_misc'],
	'ALLOW_MIN_BET' => $lang['bookie_config_allow_min_bet'],
	'ALLOW_MIN_BET_EXP' => $lang['bookie_config_allow_min_bet_exp'],
	'ALLOW_MAX_BET' => $lang['bookie_config_allow_max_bet'],
	'ALLOW_MAX_BET_EXP' => $lang['bookie_config_allow_max_bet_exp'],
	'BET_RESTRICT' => $lang['bookie_bet_restrict'],
	'BET_RESTRICT_EXP' => $lang['bookie_bet_restrict_exp'],
		
	'LEADER_BOX' => $leader_box,
	'WELCOME_TEXT' => $welcome_text,
	'ALLOW_EDIT_STAKE_YES' => ( $edit_stake ) ? 'checked="checked"' : '',
	'ALLOW_EDIT_STAKE_NO' => ( !$edit_stake ) ? 'checked="checked"' : '',
	'ALLOW_PM_YES' => ( $allow_pm ) ? 'checked="checked"' : '',
	'ALLOW_PM_NO' => ( !$allow_pm ) ? 'checked="checked"' : '',
	'ALLOW_EACH_WAY_YES' => ( $each_way ) ? 'checked="checked"' : '',
	'ALLOW_EACH_WAY_NO' => ( !$each_way ) ? 'checked="checked"' : '',
	'ALLOW_USER_BETS_YES' => ( $user_bets == 1 ) ? 'checked="checked"' : '',
	'ALLOW_USER_BETS_NO' => ( !$user_bets ) ? 'checked="checked"' : '',
	'ALLOW_USER_BETS_COND' => ( $user_bets == 2 ) ? 'checked="checked"' : '',
	'ALLOW_FRACTIONAL_YES' => ( !$fractional_decimal ) ? 'checked="checked"' : '',
	'ALLOW_FRACTIONAL_NO' => ( $fractional_decimal ) ? 'checked="checked"' : '',
	'COMMISSION_BOX' => $commission_box,
	'ALLOW_COM_YES' => ( $allow_commission ) ? 'checked="checked"' : '',
	'ALLOW_COM_NO' => ( !$allow_commission ) ? 'checked="checked"' : '',
	'MIN_STAKE' => $min_stake,
	'MAX_STAKE' => $max_stake,
	'ALLOW_RESTRICT_YES' => ( $restrict ) ? 'checked="checked"' : '',
	'ALLOW_RESTRICT_NO' => ( !$restrict ) ? 'checked="checked"' : '',
	'BOOKIE_VERSION' => $board_config['bookie_version'],
		
	// All pages
	'CONFIG_SELECT' => config_select($mode, array(
		'config' => $lang['Configuration'],
		'game' => $lang['bookie_config_bookie_set'],
		'misc' => $lang['bookie_config_bookie_misc'])
	),
		
	'S_HIDDEN_FIELDS' => $hidden_fields)		
);

$template->pparse('body');

include('./page_footer_admin.'.$phpEx);

?>