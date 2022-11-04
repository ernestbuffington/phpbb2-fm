<?php
/***************************************************************************
 *                            admin_email_search.php
 *                            -------------------
 *   begin                : Saturday, Feb 13, 2001
 *   copyright            : (C) Able2Know.com
 *   support              : http://www.able2know.com/forums/viewtopic.php?p=843365#843365
 *
 *   $Id: admin_email_search.php,v 1.72.2.10 2003/07/11 17:04:31 psotfx Exp $
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

define('IN_PHPBB', 1);

if ( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	$module['Email']['Search'] = $filename;
	return;
}

//
// Load default header
//
$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);
include($phpbb_root_path . 'includes/functions_admin.'.$phpEx);

//
// Username search
//

username_search($HTTP_POST_VARS['search_username']);

function username_search($search_match)
{
	global $db, $board_config, $template, $lang, $images, $theme, $phpEx, $phpbb_root_path;
	global $starttime, $gen_simple_header;
	
	$gen_simple_header = TRUE;

	$username_list = '';
	if ( !empty($search_match) )
	{
		$username_search = preg_replace('/\*/', '%', trim(strip_tags($search_match)));

		$sql = "SELECT user_email, user_id, username, user_level
			FROM " . USERS_TABLE . " 
			WHERE user_email LIKE '" . str_replace("\'", "''", $username_search) . "' 
				AND user_id <> " . ANONYMOUS . "
			ORDER BY user_email";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not obtain search results', '', __LINE__, __FILE__, $sql);
		}

		if ( $row = $db->sql_fetchrow($result) )
		{
			
			do
			{
				$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
			
				$username_list .= '<tr>
						<td class="' . $row_class . '"><a href="' . append_sid('admin_users.'.$phpEx.'?mode=edit&amp;' . POST_USERS_URL . '=' . $row['user_id']) . '">' . username_level_color($row['username'], $row['user_level']) . '</a></td>
						<td class="' . $row_class . '">' . $row['user_email'] . '</td>	
						<td class="' . $row_class . '" align="right"><a href="' . append_sid('admin_users.'.$phpEx.'?mode=edit&amp;' . POST_USERS_URL . '=' . $row['user_id']) . '"><img src="' . $phpbb_root_path . $images['icon_mangmt'] . '" alt="' . $lang['Edit'] . ' ' . $lang['Profile'] . '" alt="' . $lang['Edit'] . ' ' . $lang['Profile'] . '" /></a> <a href="' . append_sid($phpbb_root_path . 'profile.'.$phpEx.'?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $row['user_id']) . '"><img src=" ' . $phpbb_root_path . $images['icon_profile'] . '" alt="' . $lang['Read_profile'] . '" alt="' . $lang['Read_profile'] . '" /></a></td>
					</tr>';
				$i++;
			}
			while ( $row = $db->sql_fetchrow($result) );
		}
		else
		{
			$username_list .= '<tr>
					<td colspan="3" height="30" align="center" class="row1"><span class="gen">' . $lang['No_match'] . '</span></td>
				</tr>';
		}
		$db->sql_freeresult($result);
	}

	$template->set_filenames(array(
		'search_user_body' => 'admin/email_search_body.tpl')
	);

	$template->assign_vars(array(
		'USERNAME' => ( !empty($search_match) ) ? strip_tags($search_match) : '', 

		'L_TITLE' => $lang['Email_search'],
		'L_EXPLAIN' => $lang['Email_search_explain'],
		'L_USERNAME' => $lang['Username'], 
		'L_EMAIL' => $lang['Email_address'],
		'L_SEARCH' => $lang['Search'], 

		'S_USERNAME_OPTIONS' => $username_list, 
		'S_SEARCH_ACTION' => append_sid('admin_email_search.'.$phpEx))
	);

	if ( $username_list != '' )
	{
		$template->assign_block_vars('switch_select_name', array());
	}

	$template->pparse('search_user_body');

	include('../admin/page_footer_admin.'.$phpEx);
}

?>