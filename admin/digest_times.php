<?php 
/***************************************************************************
 *                           digest_times.php
 *                           ----------------
 *   begin                : Monday, Jul 12, 2004
 *   copyright            : (C) 2001 The phpBB Group
 *   email                : support@phpbb.com
 *
 *   $Id:
 *
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

define('IN_PHPBB', true); 

$phpbb_root_path = './../'; 
include($phpbb_root_path . 'extension.inc'); 
require('./pagestart.' . $phpEx);
include($phpbb_root_path . 'digests_common.'.$phpEx);

$user_timezone = intval($HTTP_GET_VARS[user_timezone]); 

// Load templates
$gen_simple_header = TRUE;
$page_title = $lang['Digest_times'];

$template->set_filenames(array( 
   'body' => 'admin/digest_times.tpl') 
); 

// Send vars to template
$template->assign_vars(array( 
	'L_PAGE_TITLE' => $lang['Digest_times'],
	'L_PAGE_DESCRIPTION' => $lang['Digest_times_explain'],
	'L_CLOSE_WINDOW' => "<a href='javascript:window.close();'>".$lang['Close_window']."</a>",
	'L_SERVER' => $lang['Server_time'],
	'L_BOARD' => $lang['Board_time'],
	'L_USER' => $lang['User_time'],
	) 
); 

$sql = "SELECT *
	FROM " . CONFIG_TABLE . "
	WHERE config_name = 'board_timezone'";

	$row = $db->sql_fetchrow($db->sql_query($sql));
	$board_timezone = $row['config_value'];

$curr_time = time();
$server_time = date("H:i:s", $curr_time);
$board_time = create_date("H:i:s", $curr_time, $board_timezone);
$user_time = create_date("H:i:s", $curr_time, $user_timezone);

$template->assign_block_vars('times_row', array(
	'SERVER_TIME' => $server_time,
	'BOARD_TIME' => $board_time,
	'USER_TIME' => $user_time,
	)
);

$template->pparse('body');

include('./page_footer_admin.'.$phpEx);

?> 