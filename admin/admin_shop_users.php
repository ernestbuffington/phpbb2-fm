<?php
/** 
*
* @package admin
* @version $Id: admin_shop_users.php,v 1.0 2004 zarath Exp $
* @copyright (c) 2004 Zarath Technologies
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
	$file = basename(__FILE__);
	$module['Users']['Shop'] = $file;
	return;
}

//
// Load default header
//
$phpbb_root_path = '../'; 
require($phpbb_root_path . 'extension.inc'); 
require('./pagestart.' . $phpEx); 

//
// Include language files
//
$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_shop.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_shop.' . $phpEx);


//
// Register action var
//
if ( isset($HTTP_GET_VARS['action']) || isset($HTTP_POST_VARS['action']) ) 
{ 
	$action = ( isset($HTTP_POST_VARS['action']) ) ? htmlspecialchars($HTTP_POST_VARS['action']) : htmlspecialchars($HTTP_GET_VARS['action']); 
}
else 
{ 
	$action = ''; 
}


if ( empty($action) )
{
	$template->set_filenames(array(
		'body' => 'admin/shop_users_body.tpl')
	);

	// Get a list of user shops!
	$sql = "SELECT a.*, b.username, b.user_level
		FROM " . TABLE_USER_SHOPS . " a, " . USERS_TABLE . " as b
		WHERE b.user_id = a.user_id
		ORDER BY b.username";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain personal shop data.', '', __LINE__, __FILE__, $sql);
	}

	$sql_num_rows = $db->sql_numrows($result);

	for ( $i = 0; $i < $sql_num_rows; $i++ )
	{
		if ( !($row = $db->sql_fetchrow($result)) )
		{
			message_die(GENERAL_ERROR, 'Could not obtain personal shop data.', '', __LINE__, __FILE__, $sql);
		}

		$sql = "SELECT *
			FROM " . TABLE_USER_SHOP_ITEMS . " 
			WHERE shop_id = '{$row['id']}'";
		if ( !($result2 = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not obtain personal shop item data.', '', __LINE__, __FILE__, $sql);
		}
		
		$count = $db->sql_numrows($result2);

		$template->assign_block_vars('list_users', array(
			'SHOP_ID' => $row['id'],
			'STRING' => $row['username'])
		);

		$template->assign_block_vars('list_shops', array(
			'SHOP_ID' => $row['id'],
			'STRING' => $row['shop_name'] .' [' . $count . ' ' . $lang['Items'] . ']')
		);
	}

	if ( $sql_num_rows )
	{
		$template->assign_block_vars('switch_are_shops', array());

		$sql = "SELECT *
			FROM " . TABLE_USER_SHOP_ITEMS;
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not obtain personal shop item data.', '', __LINE__, __FILE__, $sql);
		}
		$total_items = $db->sql_numrows($result);

		$sql = "SELECT SUM(amount_holding) AS total_amount, SUM(amount_earnt) AS total_earnt
			FROM " . TABLE_USER_SHOPS;
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not obtain personal shop data.', '', __LINE__, __FILE__, $sql);
		}
		if ( !($row = $db->sql_fetchrow($result)) )
		{
			message_die(GENERAL_ERROR, 'Could not obtain personal shop data.', '', __LINE__, __FILE__, $sql);
		}
	}

	$template->assign_vars(array(
		'TOTAL_SHOPS' => $sql_num_rows,
		'TOTAL_ITEMS' => ($total_items) ? $total_items : 0,
		'TOTAL_HOLDING' => ($row['total_amount']) ? $row['total_amount'] : 0,
		'TOTAL_EARNT' => ($row['total_earnt']) ? $row['total_earnt'] : 0,

		'L_TITLE' => $lang['US_title'],
		'L_EXPLAIN' => $lang['US_explain'],
		'L_SHOP_INFO' => $lang['US_shop_info'],
		'L_TOTAL_SHOPS' => $lang['US_total_shops'],
		'L_TOTAL_ITEMS' => $lang['US_total_items'],
		'L_POINTS_HELD' => sprintf($lang['US_points_held'], $board_config['points_name']),	
		'L_POINTS_EARNT' => sprintf($lang['US_points_earnt'], $board_config['points_name']),
		
		'L_CLOSE_SHOP' => $lang['Close_shop'],
		'L_CLOSE_USER_SHOP' => $lang['Close_user_shop'],
		'L_SHOP_NAME' => $lang['Shop_Name'],
		'L_USERNAME' => $lang['Username'],
		'L_RETURN_ITEMS' => $lang['Return'] . ' ' . $lang['Items'],
				
		'S_CONFIG_ACTION' => append_sid('admin_shop_users.'.$phpEx))
	);
}
elseif ( $action == 'close_shop' )
{
	//
	// Register Vars
	//
	if ( isset($HTTP_GET_VARS['id']) || isset($HTTP_POST_VARS['id']) ) 
	{ 
		$id = ( isset($HTTP_POST_VARS['id']) ) ? intval($HTTP_POST_VARS['id']) : intval($HTTP_GET_VARS['id']); 
	}
	else 
	{ 
		$id = -1; 
	}
	if ( isset($HTTP_GET_VARS['items']) || isset($HTTP_POST_VARS['items']) ) 
	{ 
		$items = ( isset($HTTP_POST_VARS['items']) ) ? intval($HTTP_POST_VARS['items']) : intval($HTTP_GET_VARS['items']); 
	}
	else 
	{ 
		$items = 1; 
	}
	
	$sql = "SELECT *
		FROM " . TABLE_USER_SHOPS . "
		WHERE id = '$id'";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain personal shop data.', '', __LINE__, __FILE__, $sql);
	}

	$sql_num_rows = $db->sql_numrows($result);

	if ( !($sql_num_rows) ) 
	{ 
		message_die(GENERAL_MESSAGE, $lang['Error_Invalid_Shop'] . '<br /><br />' . sprintf($lang['US_click_return'], '<a href="' . append_sid('admin_shop_users.'.$phpEx) . '">', '</a>') .  '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.'.$phpEx.'?pane=right') . '">', '</a>')); 
	}
	else
	{
		// If set, return user items
		if ( $items )
		{
			$sql = "SELECT *
				FROM " . TABLE_USER_SHOP_ITEMS . "
				WHERE shop_id = '$id'";
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not obtain personal shop item data.', '', __LINE__, __FILE__, $sql);
			}

			$sql_num_rows = $db->sql_numrows($result);

			for ( $i = 0; $i < $sql_num_rows; $i++ )
			{
				if ( !($row = $db->sql_fetchrow($result)) )
				{
					message_die(GENERAL_ERROR, 'Could not obtain personal shop item data.', '', __LINE__, __FILE__, $sql);
				}

				$append_items .= 'ß' . $row['name'] . 'Þ';
			}

			$sql = "SELECT *
				FROM " . USERS_TABLE . "
				WHERE user_id = '{$row['user_id']}'"; 
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not obtain userdata.', '', __LINE__, __FILE__, $sql);
			}

			$sql_num_rows = $db->sql_numrows($result);

			if ( $sql_num_rows )
			{
				if ( !($row = $db->sql_fetchrow($result)) )
				{
					message_die(GENERAL_ERROR, 'Could not obtain userdata.', '', __LINE__, __FILE__, $sql);
				}

				$new_items = addslashes($row['user_items'] . $append_items);

				$sql = "UPDATE " . USERS_TABLE . "
					SET user_items = '$new_items'
					WHERE user_id = '{$row['user_id']}'";
				if ( !($db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'Could not update userdata.', '', __LINE__, __FILE__, $sql);
				}
			}
			$msg .= '<br />' . sprintf($lang['US_items_returned'], $row['username']);
		}

		// DELETE SHOP
		$sql = "DELETE
			FROM " . TABLE_USER_SHOPS . "
			WHERE id = '$id'";
		if ( !($db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not delete personal shop.', '', __LINE__, __FILE__, $sql);
		}

		// Delete Shop Items
		$sql = "DELETE
			FROM " . TABLE_USER_SHOP_ITEMS . "
			WHERE shop_id = '$id'";
		if ( !($db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not delete personal shop items.', '', __LINE__, __FILE__, $sql);
		}

		message_die(GENERAL_MESSAGE, $lang['US_delete_success'] . $msg . '<br /><br />' . sprintf($lang['US_click_return'], '<a href="' . append_sid('admin_shop_users.'.$phpEx) . '">', '</a>') .  '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.'.$phpEx.'?pane=right') . '">', '</a>'));
	}
}
else 
{ 
	header("Location: admin_shop_users.".$phpEx); 
}

$template->pparse("body");

include('./page_footer_admin.'.$phpEx);

?>
