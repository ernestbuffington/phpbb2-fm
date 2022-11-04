<?php
/** 
 *
 * @package admin
 * @version $Id: admin_bots.php,v 1.0.0 22/07/2007 2:22 AM mj Exp $
 * @copyright (c) 2007 MJ, Fully Modded phpBB
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
	$module['Bots']['Bots_manage'] = $filename;

	return;
}

//
// Load default header
//
$phpbb_root_path = "./../";
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
	$mode = '';
}


//
// Select main mode
//
if( isset($HTTP_POST_VARS['add']) || isset($HTTP_GET_VARS['add']) )
{
	//
	// Admin has selected to add a navlink.
	//
	$template->set_filenames(array(
		'body' => 'admin/bots_edit_body.tpl')
	);

	$bot_style = style_select($board_config['default_style'], 'bot_style', '../templates');

	$s_hidden_fields = '<input type="hidden" name="mode" value="savenew" />';
	
	$template->assign_vars(array(
		'L_PAGE_TITLE' => $lang['Bot_add_title'],
		'L_PAGE_EXPLAIN' => $lang['Bot_add_explain'],
		'L_BOT_NAME' => $lang['Bot_name'],
		'L_BOT_NAME_EXPLAIN' => $lang['Bot_name_explain'],
		'L_BOT_AGENT' => $lang['Bot_agent'],
		'L_BOT_AGENT_EXPLAIN' => $lang['Bot_agent_explain'],
		'L_BOT_IP' => $lang['IP_Address'],
		'L_BOT_IP_EXPLAIN' => $lang['Bot_Ip_Explain'],
		'L_BOT_STYLE' => $lang['Style'],
		'L_BOT_STYLE_EXPLAIN' => $lang['Bot_Style_Explain'],

		'BOT_STYLE' => $bot_style,
		
		'S_ACTION' => append_sid('admin_bots.'.$phpEx), 
		'S_HIDDEN_FIELDS' => $s_hidden_fields)
	);

	$template->pparse('body');
}
else if ( $mode != '' )
{
	switch( $mode )
	{
		case 'add_pending':
		case 'ignore_pending':
			$bot_id = ( !empty($HTTP_POST_VARS['id']) ) ? $HTTP_POST_VARS['id'] : $HTTP_GET_VARS['id'];
			$pending_number = ( !empty($HTTP_POST_VARS['pending']) ) ? $HTTP_POST_VARS['pending'] : $HTTP_GET_VARS['pending'];
			$pending_data = ( isset($HTTP_POST_VARS['data']) ) ? $HTTP_POST_VARS['data'] : $HTTP_GET_VARS['data'];

			$bot_id = intval($bot_id);
			$pending_number = intval($pending_number);

			$sql = "SELECT pending_" . $pending_data . " 
				FROM " . BOTS_TABLE . " 
				WHERE bot_id = " . $bot_id;
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Couldn\'t obtain bot data.', '', __LINE__, __FILE__, $sql);
			}

			$row = $db->sql_fetchrow($result);

			// seperate data into a list
			$pending_array = explode('|', $row['pending_' . $pending_data]);
	
			if ($mode == 'add_pending')
			{
				$new_data = $pending_array[($pending_number-1)*2];
			}
	
			array_splice($pending_array, ($pending_number-1)*2, 2);
			$pending = implode('|', $pending_array);
	
			// update table
			$sql = "UPDATE " . BOTS_TABLE . " 
				SET pending_" . $pending_data . " = '$pending'
				WHERE bot_id = " . $bot_id;
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Couldn\'t update data in bots table.', '', __LINE__, __FILE__, $sql);
			}			

			if ($mode == 'add_pending')
			{
				// get data from table
				$sql = "SELECT bot_" . $pending_data . " 
					FROM " . BOTS_TABLE . " 
					WHERE bot_id = " . $bot_id;
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'Couldn\'t obtain bot data.', '', __LINE__, __FILE__, $sql);
				}
	
				$row = $db->sql_fetchrow($result);
	
				// seperate data into a list
				$pending_array = explode('|', $row['bot_' . $pending_data]);
	
				// replace delimeter to prevent errors
				$new_data = str_replace('|', '&#124;', $new_data);
	
				$pending_added = false;
	
				// are we dealing with an ip or user agent?
				if ($pending_data == 'ip')
				{
					// loop through ip's
					for ( $loop = 0; $loop < sizeof($pending_array); $loop++)
					{
						$ip_found = false;
	
						for ( $limit = 9; $limit <= 15; $limit++ )
	   					{
							if (strcmp(substr($pending_array[$loop], 0, $limit) , substr($new_data, 0, $limit)) != 0)
							{
								if ($ip_found == true)
								{
									$pending_array[$loop] = substr($pending_array[$loop], 0, ($limit-1));
									$pending_added = true;
								}
							} 
							else 
							{
								$ip_found = true;
							}
						}
					}
				} 
				else 
				{
					// loop through user agent's
					for ( $loop = 0; $loop < sizeof($pending_array); $loop++)
					{
						// which user agent string is shorter?
						$smaller_string = ( ( strlen($pending_array[$loop]) > strlen($new_data) ) ? $new_data : $pending_array[$loop]);
						$larger_string = ( ( strlen($pending_array[$loop]) < strlen($new_data) ) ? $new_data : $pending_array[$loop]);
	
						// shortest user agent string too short?
						if (strlen($smaller_string) <= 6) 
						{
							continue;
						}
						
						for ( $limit = strlen($smaller_string); $limit > 6; $limit-- )
	   					{
							for ($loop2 = 0; $loop2 < (strlen($smaller_string)-$limit)+1; $loop2++)
							{
								if (strstr($larger_string, substr($smaller_string, $loop2, $limit)))
								{
									$pending_array[$loop] = $smaller_string;
									$pending_added = true;
								}
							}
						}
					}
				}

				// insert new data into array
				if (!$pending_added) 
				{
					$pending_array[] = $new_data;
				}
				$pending = implode('|', $pending_array);
	
				// update table
				$sql = "UPDATE " . BOTS_TABLE . " 
					SET bot_" . $pending_data . " = '$pending'
					WHERE bot_id = " . $bot_id;
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'Couldn\'t update data in bots table.', '', __LINE__, __FILE__, $sql);
				}
				
				$message = $lang['Bot_added'] . '<br /><br />' . sprintf($lang['Click_return_bots'], '<a href="' . append_sid('admin_bots.'.$phpEx) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.'.$phpEx.'?pane=right') . '">', '</a>');
			}
			else
			{
				$message = $lang['Bot_ignored'] . '<br /><br />' . sprintf($lang['Click_return_bots'], '<a href="' . append_sid('admin_bots.'.$phpEx) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.'.$phpEx.'?pane=right') . '">', '</a>');
			}
						
			message_die(GENERAL_MESSAGE, $message);
			break;
		
		case 'delete':
			//
			// Admin has selected to delete a menu link.
			//
			$bot_id = ( !empty($HTTP_POST_VARS['id']) ) ? $HTTP_POST_VARS['id'] : $HTTP_GET_VARS['id'];
			$bot_id = intval($bot_id);

			$sql = "DELETE FROM " . BOTS_TABLE . "
				WHERE bot_id = " . $bot_id;
			$result = $db->sql_query($sql);
			if( !$result )
			{
				message_die(GENERAL_ERROR, 'Could not delete bot.', '', __LINE__, __FILE__, $sql);
			}

			$message = $lang['Bot_deleted'] . '<br /><br />' . sprintf($lang['Click_return_bots'], '<a href="' . append_sid('admin_bots.'.$phpEx) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.'.$phpEx.'?pane=right') . '">', '</a>');

			message_die(GENERAL_MESSAGE, $message);
			break;

		case 'edit':
			//
			// Admin has selected to edit a navlink.
			//
			$bot_id = ( !empty($HTTP_POST_VARS['id']) ) ? $HTTP_POST_VARS['id'] : $HTTP_GET_VARS['id'];
			$bot_id = intval($bot_id);

			$sql = "SELECT *
				FROM " . BOTS_TABLE . "
				WHERE bot_id = " . $bot_id;
			$result = $db->sql_query($sql);
			if( !$result )
			{
				message_die(GENERAL_ERROR, 'Could not obtain bot data.', '', __LINE__, __FILE__, $sql);
			}
			$bot_data = $db->sql_fetchrow($result);

			$template->set_filenames(array(
				'body' => 'admin/bots_edit_body.tpl')
			);

			$bot_style = style_select($bot_data['bot_style'], 'bot_style', '../templates');

			$s_hidden_fields = '<input type="hidden" name="mode" value="save" /><input type="hidden" name="bot_id" value="' . $bot_data['bot_id'] . '" />';

			$template->assign_vars(array(
				'BOT_NAME' => $bot_data['bot_name'],
				'BOT_AGENT' => $bot_data['bot_agent'],
				'BOT_STYLE' => $bot_style,
				'BOT_IP' => $bot_data['bot_ip'],

				'L_PAGE_TITLE' => $lang['Bot_edit_title'],
				'L_PAGE_EXPLAIN' => $lang['Bot_add_explain'],
				'L_BOT_NAME' => $lang['Bot_name'],
				'L_BOT_NAME_EXPLAIN' => $lang['Bot_name_explain'],
				'L_BOT_AGENT' => $lang['Bot_agent'],
				'L_BOT_AGENT_EXPLAIN' => $lang['Bot_agent_explain'],
				'L_BOT_IP' => $lang['IP_Address'],
				'L_BOT_IP_EXPLAIN' => $lang['Bot_Ip_Explain'],
				'L_BOT_STYLE' => $lang['Style'],
				'L_BOT_STYLE_EXPLAIN' => $lang['Bot_Style_Explain'],

				'S_ACTION' => append_sid('admin_bots.'.$phpEx),
				'S_HIDDEN_FIELDS' => $s_hidden_fields)
			);

			$template->pparse("body");
			break;

		case "save":
			//
			// Admin has submitted changes while editing a bot.
			//
			// Get the submitted data, being careful to ensure that we only
			// accept the data we are looking for.
			//
			$bot_id = ( !empty($HTTP_POST_VARS['bot_id']) ) ? $HTTP_POST_VARS['bot_id'] : $HTTP_GET_VARS['bot_id'];
			$bot_id = intval($bot_id);
			$bot_name = ( isset($HTTP_POST_VARS['bot_name']) ) ? $HTTP_POST_VARS['bot_name'] : $HTTP_GET_VARS['bot_name'];
			$bot_agent = ( isset($HTTP_POST_VARS['bot_agent']) ) ? $HTTP_POST_VARS['bot_agent'] : $HTTP_GET_VARS['bot_agent'];
			$bot_ip = ( isset($HTTP_POST_VARS['bot_ip']) ) ? $HTTP_POST_VARS['bot_ip'] : $HTTP_GET_VARS['bot_ip'];
			$bot_style = ( isset($HTTP_POST_VARS['bot_style']) ) ? intval($HTTP_POST_VARS['bot_style']) : intval($HTTP_GET_VARS['bot_style']);

			$bot_name = trim($bot_name);
			$bot_agent = trim($bot_agent);
			$bot_ip = trim($bot_ip);

			// If no code was entered complain ...
			if ($bot_name == '' || $bot_agent == '')
			{
				message_die(GENERAL_MESSAGE, $lang['Fields_empty']);
			}

			if ( $bot_ip == '' )
			{
				if ( $bot_agent == '')
				{
					message_die(GENERAL_MESSAGE, $lang['Error_No_Agent_Or_Ip']);
				}
			}
			
			if ( $_SERVER['REMOTE_ADDR'] == $bot_ip)
			{
				message_die(GENERAL_MESSAGE, $lang['Error_Own_Ip']);
            }
			
			//
			// Proceed with updating the bots table.
			//
			$sql = "UPDATE " . BOTS_TABLE . "
				SET bot_name = '" . str_replace("\'", "''", $bot_name) . "', bot_agent = '" . str_replace("\'", "''", $bot_agent) . "', bot_ip = '" . $bot_ip . "', bot_style = $bot_style
				WHERE bot_id = $bot_id";
			if( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not update bot.', "", __LINE__, __FILE__, $sql);
			}

			$message = $lang['Bot_edited'] . "<br /><br />" . sprintf($lang['Click_return_bots'], "<a href=\"" . append_sid("admin_bots.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

			message_die(GENERAL_MESSAGE, $message);
			break;

		case "savenew":
			//
			// Admin has submitted changes while adding a new bot.
			//
			// Get the submitted data being careful to ensure the the data
			// we recieve and process is only the data we are looking for.
			//
			$bot_name = ( isset($HTTP_POST_VARS['bot_name']) ) ? $HTTP_POST_VARS['bot_name'] : $HTTP_GET_VARS['bot_name'];
			$bot_agent = ( isset($HTTP_POST_VARS['bot_agent']) ) ? $HTTP_POST_VARS['bot_agent'] : $HTTP_GET_VARS['bot_agent'];
			$bot_ip = ( isset($HTTP_POST_VARS['bot_ip']) ) ? $HTTP_POST_VARS['bot_ip'] : $HTTP_GET_VARS['bot_ip'];
			$bot_style = ( isset($HTTP_POST_VARS['bot_style']) ) ? intval($HTTP_POST_VARS['bot_style']) : intval($HTTP_GET_VARS['bot_style']);

			$bot_name = trim($bot_name);
			$bot_agent = trim($bot_agent);
			$bot_ip = trim($bot_ip);

			// If no code was entered complain ...
			if ($bot_name == '' || $bot_agent == '')
			{
				message_die(GENERAL_MESSAGE, $lang['Fields_empty']);
			}

			if ( $bot_ip == '' )
			{
				if ( $bot_agent == '')
				{
					message_die(GENERAL_MESSAGE, $lang['Error_No_Agent_Or_Ip']);
				}
			}
			
			if ( $_SERVER['REMOTE_ADDR'] == $bot_ip)
			{
				message_die(GENERAL_MESSAGE, $lang['Error_Own_Ip']);
            }

			//
			// Save the data to the bots table.
			//
			$sql = "INSERT INTO " . BOTS_TABLE . " (bot_name, bot_agent, bot_style, bot_ip)
				VALUES ('" . str_replace("\'", "''", $bot_name) . "', '" . str_replace("\'", "''", $bot_agent) . "', $bot_style, '" . $bot_ip . "')";
			$result = $db->sql_query($sql);
			if( !$result )
			{
				message_die(GENERAL_ERROR, 'Could not insert new bot.', '', __LINE__, __FILE__, $sql);
			}

			$message = $lang['Bot_added'] . "<br /><br />" . sprintf($lang['Click_return_bots'], "<a href=\"" . append_sid("admin_bots.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

			message_die(GENERAL_MESSAGE, $message);
			break;
	}
}
else
{	
	//
	// This is the main display of the page before the admin has selected
	// any options.
	//
	$sql = "SELECT *
		FROM " . BOTS_TABLE . "
		ORDER BY bot_name";
	$result = $db->sql_query($sql);
	if( !$result )
	{
		message_die(GENERAL_ERROR, 'Could not obtain bot listing.', '', __LINE__, __FILE__, $sql);
	}

	$bots = $db->sql_fetchrowset($result);

	//
	// VERY approximately calculate total site pages!
	//
	$total_posts = get_db_stat('postcount');
	$total_users = get_db_stat('usercount');
	$total_topics = get_db_stat('topiccount');
	
	$total_pages = floor($total_topics / $board_config['topics_per_page']);
	$total_pages += floor($total_posts / $board_config['posts_per_page']);
	$total_pages += $total_users + floor($total_users / 50);
	$total_pages = floor($total_pages * 1.35);

	$template->set_filenames(array(
		'body' => 'admin/bots_list_body.tpl')
	);

	$template->assign_vars(array(
		"L_PAGE_TITLE" => $lang['Bots_manage'],
		"L_PAGE_TEXT" => $lang['Bots_explain'],
		'L_BOTS_TITLE_PENDING' => $lang['Pending_Bots'],
		'L_BOTS_EXPLAIN_PENDING' => $lang['Pending_Explain'],
		'L_EDIT' => '<img src="' . $phpbb_root_path . $images['acp_edit'] . '" alt="' . $lang['Edit'] . '" title="' . $lang['Edit'] . '" />',
		'L_DELETE' => '<img src="' . $phpbb_root_path . $images['acp_delete'] . '" alt="' . $lang['Delete'] . '" title="' . $lang['Delete'] . '" />',
	
		"L_ADD" => $lang['Add_new'],
		"L_NAME" => $lang['Bot_name'],
		'L_BOT_IP' => $lang['IP_Address'],
		'L_BOT_AGENT' => $lang['Bot_agent'],
		'L_BOT_LAST_VISIT' => $lang['Last_logon'],
		'L_BOT_VISITS' => $lang['Visits'],
		'L_BOT_PAGES' => $lang['Pages'],
		'L_BOT_MARK' => $lang['Mark'],
		'L_BOT_IGNORE' => $lang['Ignore'],
		
		"S_ACTION" => append_sid("admin_bots.$phpEx"))
	);

	//
	// Loop throuh the rows of links setting block vars for the template.
	//
	for($i = 0; $i < sizeof($bots); $i++)
	{		
		$last_visits = explode('|', $bots[$i]['last_visit']);
		if ($last_visits[0] == '')
		{
			$last_visit = $lang['Never_last_logon'];
		} 
		else 
		{
			$last_visit = '<select>';
			foreach ($last_visits AS $visit)
			{
				$last_visit .= '<option>' . create_date($board_config['default_dateformat'], $visit, $board_config['board_timezone']) . '</option>';
			}
			$last_visit .= '</select>';
		}
	
		$bot_pages = $bots[$i]['bot_pages'];
		$percentage = round(($bot_pages / $total_pages) * 100);
		$bot_pages .= ' (' . (($percentage < 100) ? $percentage : 100)  . '%)';

		$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
				
		$template->assign_block_vars('bots', array(
			"ROW_CLASS" => $row_class,
			"BOT_ID" => $bots[$i]['bot_id'],
			"NAME" => $bots[$i]['bot_name'],
			'PAGES' => $bot_pages,
			'VISITS' => $bots[$i]['bot_visits'],
			'LAST_VISIT' => $last_visit,
		
			"U_BOT_EDIT" => append_sid("admin_bots.$phpEx?mode=edit&amp;id=" . $bots[$i]['bot_id']), 
			"U_BOT_DELETE" => append_sid("admin_bots.$phpEx?mode=delete&amp;id=" . $bots[$i]['bot_id']))
		);
	}

	if ( !$i )
	{
		$template->assign_block_vars('nobotrow', array(
			'NO_BOTS' => $lang['None'])
		);
	}

//
// Get pending bots 
//
$sql = "SELECT *
	FROM " . BOTS_TABLE;
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Couldn\'t query pending bots.', '', __LINE__, __FILE__, $sql);
}

$i = $pending_bots = 0;
while ($row = $db->sql_fetchrow($result))
{
	// i know its bad practice to have to almost identical statements but what the hey!
	if ( $row['pending_agent'] )
	{
		$pending_array = explode('|', $row['pending_agent']);
		if ($pending_array) 
		{
			$pending_bots = 1;
		}
		
		for ($loop = 0; $loop < sizeof($pending_array); $loop+=2)
		{
			$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
			
			$template->assign_block_vars('pendingrow', array(
				'ROW_CLASS' => $row_class,
				'BOT_NAME' => $row['bot_name'],
				'AGENT' => '<b>' . $pending_array[$loop] . '</b>',
				'IP' => '<a href="http://network-tools.com/default.asp?prog=trace&amp;host=' . $pending_array[$loop+1] . '" target="_blank">' . $pending_array[$loop+1] . '</a>',
	
				'L_ADD'=> $lang['Add'],
				'L_IGNORE' => $lang['Ignore'],
					
				'U_BOT_IGNORE' => append_sid("admin_bots.$phpEx?mode=ignore_pending&amp;id=" . $row['bot_id'] . "&amp;pending=" . (($loop / 2) + 1) . "&amp;data=agent"), 
				'U_BOT_ADD' => append_sid("admin_bots.$phpEx?mode=add_pending&amp;id=" . $row['bot_id'] . "&amp;pending=" . (($loop / 2) + 1) . "&amp;data=agent"))
			);	
			$i++;
		}
	}

	if ( $row['pending_ip'] )
	{
		$pending_array = explode('|', $row['pending_ip']);
		if ($pending_array) 
		{
			$pending_bots = 1;
		}
		
		for ($loop = 0; $loop < sizeof($pending_array); $loop+=2)
		{
			$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
			
			$template->assign_block_vars('pendingrow', array(
				'ROW_CLASS' => $row_class,
				'BOT_NAME' => $row['bot_name'],
				'AGENT' => $pending_array[$loop+1],
				'IP' => '<b><a href="http://network-tools.com/default.asp?prog=trace&amp;host=' . $pending_array[$loop] . '" target="_blank">' . $pending_array[$loop] . '</a></b>',

				'L_ADD'=> $lang['Add'],
				'L_IGNORE' => $lang['Ignore'],

				'U_BOT_IGNORE' => append_sid("admin_bots.$phpEx?mode=ignore_pending&amp;id=" . $row['bot_id'] . "&amp;pending=" . (($loop / 2) + 1) . "&amp;data=ip"), 
				'U_BOT_ADD' => append_sid("admin_bots.$phpEx?mode=add_pending&amp;id=" . $row['bot_id'] . "&amp;pending=" . (($loop / 2) + 1) . "&amp;data=ip"))
			);	
			$i++;
		}
	}
}

if ( !$pending_bots )
{
	$template->assign_block_vars('nopendingrow', array(
		'NO_BOTS' => $lang['None'])
	);
}
$db->sql_freeresult($result);

	//
	// Spit out the page.
	//
	$template->pparse("body");
}

//
// Page Footer
//
include('./page_footer_admin.'.$phpEx);

?>