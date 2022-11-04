<?php
/** 
*
* @package admin
* @version $Id: admin_lottery_config.php,v 2.2.0 2004/11/18 17:49:33 zarath Exp $
* @copyright (c) 2004 Zarath Technologies
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
	$file = basename(__FILE__);
//	$module['Points_sys_settings']['Lottery_title'] = $file;
	return;
}

//
// Let's set the root dir for phpBB
//
$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);

//
// Include language file
//
$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_lottery.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_lottery.' . $phpEx); 


//
// Pull all lottery data
//
$sql = "SELECT *
	FROM " . LOTTERY_TABLE . "
	WHERE id > 0";
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, sprintf($lang['lottery_error_selecting'], 'lottery'), '', __LINE__, __FILE__, $sql);
}
$sql_count = $db->sql_numrows($result);

$time_left = ( $board_config['lottery_start'] ) ? ($board_config['lottery_start'] + $board_config['lottery_length']) - time() : '-1';
$duration_left = duration($time_left);
$pool = ($sql_count * $board_config['lottery_cost']) + $board_config['lottery_base'];
$total_entries = $sql_count;

//
// Begin Items listing for addition to prize pool
// ONLY do this if the shop items are enabled, incase there is no shop!
//
if ( $board_config['lottery_items'] )
{
	$sql = "SELECT id, name
		FROM " . SHOPITEMS_TABLE . "
		ORDER BY name";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, sprintf($lang['lottery_error_selecting'], 'shop_items'), '', __LINE__, __FILE__, $sql);
	}

	$sql_count = $db->sql_numrows($result);

	if ( $sql_count < 1 )
	{
		//
		// Code to toggle no history!
		//
		$template->assign_block_vars('switch_no_items', array(
			'MESSAGE' => $lang['lottery_no_items'])
		);
	}
	else
	{
		//
		// Loop over the items in the DB and add them to a drop down after RANDOM item!
		//
		for ($i = 0; $i < $sql_count; $i++)
		{
			if (!( $row = $db->sql_fetchrow($result) ))
			{
				message_die(GENERAL_ERROR, sprintf($lang['lottery_error_selecting'], 'shop items'), '', __LINE__, __FILE__, $sql);
			}

			$template->assign_block_vars('item_listrow', array(
				'ITEM_ID' => $row['id'],
				'ITEM_NAME' => $row['name'])
			);
		}

		$template->assign_block_vars('switch_are_items', array());
	}

	//
	// Begin items listing for items already in prize pool!
	// ONLY do this if the shop items are enabled, incase there is no shop!
	//
	$item_array = explode(';', $board_config['lottery_win_items']);
	$item_count = sizeof($item_array);

	if ( ($item_count > 0) && (!empty($item_array[0])) )
	{
		for ($i = 0; $i < $item_count; $i++)
		{
			$item_array[$i] = ( $item_array[$i] == "random" ) ? $lang['lottery_rand'] : $item_array[$i];

			$template->assign_block_vars('pool_listrow', array(
				'ITEM_NAME' => $item_array[$i])
			);
		}

		$template->assign_block_vars('switch_pool_items', array());
	}

	//
	// Begin listing of all shops. This is for the RAND settings!
	// 
	$sql = "SELECT *
		FROM " . SHOPS_TABLE . "
		ORDER BY shopname";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, sprintf($lang['lottery_error_selecting'], 'shops'), '', __LINE__, __FILE__, $sql);
	}

	$sql_count = $db->sql_numrows($result);

	if ( $sql_count > 0 )
	{
		//
		// Loop over the shops list results!
		//
		for ($i = 0; $i < $sql_count; $i++)
		{
			if (!( $row = $db->sql_fetchrow($result) ))
			{
				message_die(GENERAL_ERROR, sprintf($lang['lottery_error_selecting'], 'shop'), '', __LINE__, __FILE__, $sql);
			}

			$shop_selected = ( $board_config['lottery_random_shop'] == $row['shopname'] ) ? 'SELECTED' : '';

			$template->assign_block_vars('rand_listrow', array(
				'SHOP_NAME' => $row['shopname'],
				'SELECTED' => $shop_selected)
			);
		}
	}

	$template->assign_block_vars('switch_enabled_items', array());
}

//
// Grab last winner from lottery -- ORDERED BY TIME
//
$sql = "SELECT t1.*, t2.username, t2.user_level
	FROM " . LOTTERY_HISTORY_TABLE . " as t1, " . USERS_TABLE . " as t2
	WHERE t2.user_id = t1.user_id
	ORDER BY time DESC";
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, sprintf($lang['lottery_error_selecting'], 'lottery history'), '', __LINE__, __FILE__, $sql);
}

if ( $db->sql_numrows($result) > 0 )
{
	if (!( $row = $db->sql_fetchrow($result) ))
	{
		message_die(GENERAL_ERROR, sprintf($lang['lottery_error_selecting'], 'lottery'), '', __LINE__, __FILE__, $sql);
	}
	$last_won = username_level_color($row['username'], $row['user_level'], $row['user_id']);
}
else 
{ 
	$last_won = $lang['lottery_no_one']; 
}


//
// Pull all config data
//
$sql = "SELECT *
	FROM " . CONFIG_TABLE . "
	WHERE config_name 
	LIKE '%lottery_%'";
if(!$result = $db->sql_query($sql))
{
	message_die(GENERAL_ERROR, "Could not query config information in admin_points_lottery_config", "", __LINE__, __FILE__, $sql);
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
		else if( isset($HTTP_POST_VARS['item_pool']) )
		{
			if ( isset($HTTP_GET_VARS['del_item']) || isset($HTTP_POST_VARS['del_item']) ) 
			{ 
				$del_item = ( isset($HTTP_POST_VARS['del_item']) ) ? $HTTP_POST_VARS['del_item'] : $HTTP_GET_VARS['del_item']; 
			}
			
			if ( isset($HTTP_GET_VARS['add_item']) || isset($HTTP_POST_VARS['add_item']) ) 
			{ 
				$add_item = ( isset($HTTP_POST_VARS['add_item']) ) ? $HTTP_POST_VARS['add_item'] : $HTTP_GET_VARS['add_item']; 
			}

			if ( !empty($del_item) )
			{
				if ( isset($HTTP_GET_VARS['item_name']) || isset($HTTP_POST_VARS['item_name']) ) 
				{ 
					$item_id = ( isset($HTTP_POST_VARS['item_name']) ) ? $HTTP_POST_VARS['item_name'] : $HTTP_GET_VARS['item_name']; 
				}
				else 
				{ 
					message_die(GENERAL_MESSAGE, "Cannot read item_name variable!"); 
				}
			
				$item_id = ( $item_id == 'Random' ) ? 'random' : $item_id;
				
				if ( substr($board_config['lottery_win_items'], 0, strlen($item_id)) == $item_id )
				{
					$lottery_items = substr_replace($board_config['lottery_win_items'], '', 0, strlen($item_id . ';'));
				}
				else
				{
					$lottery_items = substr_replace($board_config['lottery_win_items'], '', strpos($board_config['lottery_win_items'], ';' . $item_id), strlen(';' . $item_id));
				}		

				$sql = "UPDATE " . CONFIG_TABLE . "
					SET config_value = '" . addslashes($lottery_items) . "'
					WHERE config_name = 'lottery_win_items'";
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, sprintf($lang['lottery_error_updating'], 'config'), '', __LINE__, __FILE__, $sql);
				}

				message_die(GENERAL_MESSAGE, $lang['lottery_item_removed'] . "<br /><br />" . sprintf($lang['Click_return_config'], '<a href="' . append_sid('admin_lottery_config.'.$phpEx) . '">', '</a>') . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>"));
			}
			elseif ( !empty($add_item) )
			{
				if ( isset($HTTP_GET_VARS['item_id']) || isset($HTTP_POST_VARS['item_id']) ) 
				{ 
					$item_id = ( isset($HTTP_POST_VARS['item_id']) ) ? intval($HTTP_POST_VARS['item_id']) : intval($HTTP_GET_VARS['item_id']); 
				}
				else 
				{ 
					message_die(GENERAL_MESSAGE, "Cannot read item_id variable!"); 
				}

				if ( $item_id != 'random' )
				{
					$sql = "SELECT *
						FROM " . SHOPITEMS_TABLE . "
						WHERE id = '$item_id'";
					if ( !($result = $db->sql_query($sql)) )
					{
						message_die(GENERAL_ERROR, sprintf($lang['lottery_error_selecting'], 'shop items'), '', __LINE__, __FILE__, $sql);
					}

					if ( !($db->sql_numrows($result)) )
					{
						message_die(GENERAL_MESSAGE, $lang['lottery_no_item']);
					}

					if (!( $row = $db->sql_fetchrow($result) ))
					{
						message_die(GENERAL_ERROR, sprintf($lang['lottery_error_selecting'], 'shop items'), '', __LINE__, __FILE__, $sql);
					}

					$lottery_items = ( empty($board_config['lottery_win_items']) ) ? $row['name'] : $board_config['lottery_win_items'] . ';' . $row['name'];
				}
				else
				{
					$lottery_items = ( empty($board_config['lottery_win_items']) ) ? 'random' : $board_config['lottery_win_items'] . ';random';
				}

				$sql = "UPDATE " . CONFIG_TABLE . "
					SET config_value = '" . addslashes($lottery_items) . "'
					WHERE config_name = 'lottery_win_items'";	
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, sprintf($lang['lottery_error_updating'], 'config'), '', __LINE__, __FILE__, $sql);
				}

				message_die(GENERAL_MESSAGE, $lang['lottery_item_added'] . "<br /><br />" . sprintf($lang['Click_return_config'], '<a href="' . append_sid('admin_lottery_config.'.$phpEx) . '">', '</a>') . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>"));
			}
			else 
			{ 
				message_die(GENERAL_MESSAGE, $lang['lottery_invalid_action']); 
			}
		}
	}
	$db->sql_freeresult($result);

	if( isset($HTTP_POST_VARS['submit']) )
	{
		// Remove cache file
		@unlink($phpbb_root_path . 'cache/config_board.'.$phpEx);

		$message = $lang['Board_config_updated'] . "<br /><br />" . sprintf($lang['Click_return_config'], "<a href=\"" . append_sid("admin_lottery_config.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

		message_die(GENERAL_MESSAGE, $message);
	}
}

$lottery_status_yes = ($new['lottery_status']) ? 'checked="checked"' : '';
$lottery_status_no  = (!$new['lottery_status']) ? 'checked="checked"' : '';

$lottery_reset_yes = ($new['lottery_reset']) ? 'checked="checked"' : '';
$lottery_reset_no  = (!$new['lottery_reset']) ? 'checked="checked"' : '';

$lottery_ticktype_yes = ($new['lottery_ticktype']) ? 'checked="checked"' : '';
$lottery_ticktype_no  = (!$new['lottery_ticktype']) ? 'checked="checked"' : '';

$lottery_mb_yes = ($new['lottery_mb']) ? 'checked="checked"' : '';
$lottery_mb_no  = (!$new['lottery_mb']) ? 'checked="checked"' : '';

$lottery_show_entries_yes = ($new['lottery_show_entries']) ? 'checked="checked"' : '';
$lottery_show_entries_no  = (!$new['lottery_show_entries']) ? 'checked="checked"' : '';

$lottery_items_yes = ($new['lottery_items']) ? 'checked="checked"' : '';
$lottery_items_no  = (!$new['lottery_items']) ? 'checked="checked"' : '';

$lottery_history_yes = ($new['lottery_history']) ? 'checked="checked"' : '';
$lottery_history_no  = (!$new['lottery_history']) ? 'checked="checked"' : '';

$template->set_filenames(array(
	'body' => 'admin/lottery_config_body.tpl')
);

$template->assign_vars(array(
	"S_CONFIG_ACTION" => append_sid("admin_lottery_config.$phpEx"),

	"L_LOTTERY_TITLE" => $lang['Lottery_title'] . ' ' . $lang['Setting'],
	"L_LOTTERY_TITLE_EXPLAIN" => $lang['Lottery_title_explain'],
	"L_LOTTERY_STATUS" => $lang['lottery_status'],
	"L_LOTTERY_NAME" => $lang['lottery_name'],
	"L_LOTTERY_AUTO_RESTART" => $lang['lottery_auto_restart'],
	"L_LOTTERY_BASE_AMOUNT" => $lang['lottery_base_pool'],
	"L_LOTTERY_ENTRY_COST" => $lang['lottery_ticket_cost'],
	"L_LOTTERY_DRAW_PERIODS" => $lang['lottery_draw_periods'],
	"L_LOTTERY_TICKETS_ALLOWED" => $lang['lottery_tickets_allowed'],
	"L_LOTTERY_MULT_TICKETS" => $lang['lottery_mult_tickets'],
	"L_LOTTERY_MULT_TICKETS_EXPLAIN" => $lang['lottery_max'],
	"L_LOTTERY_FULL_DISPLAY" => $lang['lottery_full_display'],
	"L_LOTTERY_ITEM_POOL" => $lang['lottery_item_pool'],
	"L_LOTTERY_HISTORY" => $lang['lottery_history'],
	"L_LOTTERY_SINGLE" => $lang['lottery_single'],
	"L_LOTTERY_MULTIPLE" => $lang['lottery_multiple'],
	"L_LOTTERY_ENTRIES_TOTAL" => $lang['lottery_current_entries'],
	"L_LOTTERY_LEFT_TIME" => $lang['lottery_time_draw'],
	"L_LOTTERY_LOTTERY_POOL" => $lang['lottery_total_pool'],
	"L_LOTTERY_WON_LAST" => $lang['lottery_last_winner'],
	"L_LOTTERY_DAY" => $lang['1_Day'],
	"L_LOTTERY_DAYS" => $lang['lottery_days'],
	"L_LOTTERY_ITEMS_TITLE" => $lang['lottery_items_table'],
	"L_LOTTERY_RAND_ITEMS_TITLE" => $lang['lottery_items_settings'],
	"L_LOTTERY_FROM_SHOP" => $lang['lottery_from_shop'],
	"L_LOTTERY_ALL_SHOPS" => $lang['lottery_all_shops'],
	"L_LOTTERY_MIN_COST" => $lang['lottery_min_cost'],
	"L_LOTTERY_MAX_COST" => $lang['lottery_max_cost'],
	"L_LOTTERY_CURRENT_ITEMS" => $lang['lottery_current_items'],
	"L_LOTTERY_REMOVE_ITEM" => $lang['lottery_remove_item'],
	"L_LOTTERY_UPDATE_ITEM" => $lang['lottery_update_item'],
	"L_LOTTERY_ADD_ITEMS" => $lang['lottery_add_items'],
	"L_LOTTERY_RANDOM" => $lang['lottery_rand'],
	"L_LOTTERY_ADD_ITEM" => $lang['lottery_add_item'],

	"S_LOTTERY_YES" => $lottery_status_yes,
	"S_LOTTERY_NO" => $lottery_status_no,
	"LOTTERY_NAME" => $new['lottery_name'],
	"S_LOTTERY_RESET_YES" => $lottery_reset_yes,
	"S_LOTTERY_RESET_NO" => $lottery_reset_no,
	"LOTTERY_BASE_AMOUNT" => $new['lottery_base'],
	"LOTTERY_ENTRY_COST" => $new['lottery_cost'],
	"LOTTERY_DRAW_PERIODS" => $new['lottery_length'],
	"LOTTERY_DRAW_PERIODS_SELECT" => $board_config['lottery_length'],
	"LOTTERY_DRAW_PERIODS_SELECTED" => duration($board_config['lottery_length']),
	"S_LOTTERY_TICKETS_ALLOWED_MULTIPLE" => $lottery_ticktype_yes,
	"S_LOTTERY_TICKETS_ALLOWED_SINGLE" => $lottery_ticktype_no,
	"S_LOTTERY_MB_YES" => $lottery_mb_yes,
	"S_LOTTERY_MB_NO" => $lottery_mb_no,
	"LOTTERY_MB_AMOUNT" => $new['lottery_mb_amount'],
	"S_LOTTERY_DISPLAY_YES" => $lottery_show_entries_yes,
	"S_LOTTERY_DISPLAY_NO" => $lottery_show_entries_no,
	"S_LOTTERY_ITEMS_YES" => $lottery_items_yes,
	"S_LOTTERY_ITEMS_NO" => $lottery_items_no,
	"S_LOTTERY_POOL_YES" => $lottery_history_yes,
	"S_LOTTERY_POOL_NO" => $lottery_history_no,
	"LOTTERY_LAST_WON" => $last_won,
	"LOTTERY_TOTAL_ENTRIES" => $total_entries,
	"LOTTERY_POOL" => $pool,
	"LOTTERY_DURATION" => $duration_left,
	"LOTTERY_RAND_COST_MIN" => $new['lottery_item_mcost'],
	"LOTTERY_RAND_COST_MAX" => $new['lottery_item_xcost'])
);

$template->pparse("body");

include('./page_footer_admin.'.$phpEx);

?>