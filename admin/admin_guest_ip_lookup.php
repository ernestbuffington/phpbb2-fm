<?php
/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	return;
}

//
// Let's set the root dir for phpBB
//
$phpbb_root_path = './../';
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);

$ip = (( !empty($HTTP_GET_VARS['ip']) ) ? $HTTP_GET_VARS['ip'] : '' );

if ( !$ip )
{
	$message = $lang['No_IP_Specified'] . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.$phpEx?pane=right") . '">', '</a>');

	message_die(GENERAL_MESSAGE, $message);
}

//
// Set template files
//
$template->set_filenames(array(
	'body' => 'admin/admin_guest_ip_lookup.tpl')
);

$encoded_ip = encode_ip($ip);

$template->assign_vars(array(
	'L_IP_INFO' => $lang['IP_info'],
	'L_OTHER_USERS' => $lang['Users_this_IP'],
	'L_SEARCH' => $lang['Search'],

	'SEARCH_IMG' => $phpbb_root_path . $images['icon_search'])
);

//
// Get users who've posted under this IP
//
$sql = "SELECT u.user_id, u.username, u.user_level, COUNT(*) as postings 
	FROM " . USERS_TABLE . " u, " . POSTS_TABLE . " p 
	WHERE p.poster_id = u.user_id 
		AND p.poster_ip = '" . $encoded_ip . "'
	GROUP BY u.user_id, u.username
	ORDER BY postings DESC";
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not get posters information based on IP', '', __LINE__, __FILE__, $sql);
}

if ( $row = $db->sql_fetchrow($result) )
{
	$i = 0;
	do
	{
		$id = $row['user_id'];
		$username = ( $id == ANONYMOUS ) ? $lang['Guest'] : $row['username'];

		$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

		$template->assign_block_vars('userrow', array(
			'ROW_CLASS' => $row_class, 
			'USERNAME' => username_level_color($username, $row['user_level'], $id),
			'POSTS' => $row['postings'] . ' ' . ( ( $row['postings'] == 1 ) ? $lang['Post'] : $lang['Posts'] ),
			'L_SEARCH_POSTS' => sprintf($lang['Search_user_posts'], $username), 

			'U_PROFILE' => ($id == ANONYMOUS) ? $phpbb_root_path . 'modcp.' . $phpEx . '?mode=ip&amp;' . POST_POST_URL . '=' . $post_id . '&amp;' . POST_TOPIC_URL . '=' . $topic_id . '&amp;sid=' . $userdata['session_id'] : append_sid($phpbb_root_path . 'profile.' . $phpEx . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $id),
			'U_SEARCHPOSTS' => append_sid($phpbb_root_path . 'search.' . $phpEx . '?search_author=' . urlencode($username) . '&amp;showresults=topics'))
		);
		$i++; 
	}
	while ( $row = $db->sql_fetchrow($result) );
}
else
{
	$message = $lang['No_Matches'] . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.$phpEx?pane=right") . '">', '</a>');

	message_die(GENERAL_MESSAGE, $message);
}

$template->pparse('body');

include('./page_footer_admin.'.$phpEx);

?>