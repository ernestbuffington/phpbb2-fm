<?php
/***************************************************************************
 *                               profile_comments.php
 *                            ------------------
 *   copyright            : ©2003 Freakin' Booty ;-P
 *   version              : 0.1.3
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
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);


//
// Parameters
//
$mode = ( isset($HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : ( ( isset($HTTP_GET_VARS['mode']) ) ? $HTTP_GET_VARS['mode'] : '' );

$start = ( isset($HTTP_POST_VARS['start']) ) ? $HTTP_POST_VARS['start'] : ( ( isset($HTTP_GET_VARS['start']) ) ? $HTTP_GET_VARS['start'] : 0 );
$start = ($start < 0) ? 0 : $start;

$comments = ( isset($HTTP_POST_VARS['comments']) ) ? htmlspecialchars(trim(strip_tags($HTTP_POST_VARS['comments']))) : '';

$submit = ( isset($HTTP_POST_VARS['submit']) ) ? TRUE : 0;
$confirm = ( isset($HTTP_POST_VARS['confirm']) ) ? TRUE : 0;
$cancel = ( isset($HTTP_POST_VARS['cancel']) ) ? TRUE : 0;

$user_id = ( isset($HTTP_POST_VARS[POST_USERS_URL]) ) ? $HTTP_POST_VARS[POST_USERS_URL] : ( ( isset($HTTP_GET_VARS[POST_USERS_URL]) ) ? $HTTP_GET_VARS[POST_USERS_URL] : 0 );
$comment_id = ( isset($HTTP_POST_VARS['commentid'])) ? $HTTP_POST_VARS['commentid'] : ( ( isset($HTTP_GET_VARS['commentid']) ) ? $HTTP_GET_VARS['commentid'] : 0);

$sid = (isset($HTTP_POST_VARS['sid'])) ? $HTTP_POST_VARS['sid'] : 0;

if( $mode != 'editpost' && $mode != 'delete' )
{
	if( empty($user_id) || $user_id == ANONYMOUS )
	{
		message_die(GENERAL_MESSAGE, 'No_user_id_specified');
	}

	$profiledata = get_userdata(intval($user_id));
}
else
{
	if( empty($comment_id) )
	{
		message_die(GENERAL_MESSAGE, 'No_comment_id_specified');
	}
}


//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_PROFILE);
init_userprefs($userdata);
//
// End session management
//

if( !$userdata['session_logged_in'] )
{
	$header_location = (@preg_match('/Microsoft|WebSTAR|Xitami/', getenv('SERVER_SOFTWARE')) ) ? 'Refresh: 0; URL=' : 'Location: ';
	header($header_location . append_sid("login.$phpEx?redirect=profile_comments.$phpEx?mode=$mode&" . POST_USERS_URL . "=$user_id", true));
	exit;
}

if ( $cancel )
{
	$header_location = (@preg_match('/Microsoft|WebSTAR|Xitami/', getenv('SERVER_SOFTWARE'))) ? 'Refresh: 0; URL=' : 'Location: ';
	header($header_location . append_sid("profile_comments.$phpEx?" . POST_USERS_URL . "=$user_id", true));
	exit;
}


//
// If you want all users (except guests) to be able to post comments, comment the 4 lines below (add // in front of them).
//
if ( !$userdata['user_level'] == ADMIN || !$userdata['user_level'] == LESS_ADMIN || !$userdata['user_level'] == MOD )
{
	message_die(GENERAL_MESSAGE, 'Not_authorised');
}


if ( $mode == 'post' || $mode == 'editpost' )
{
	if ( $user_id == $userdata['user_id'] )
	{
		message_die(GENERAL_MESSAGE, $lang['No_comments_yourself'] . '<br /><br />' . sprintf($lang['Click_return_viewprofile'], '<a href="' . append_sid('profile.'.$phpEx.'?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $userdata['user_id']) . '">', '</a>'));
	}


	if( !$submit )
	{
		if( $mode == 'editpost' )
		{
			$sql = "SELECT user_id, comments 
				FROM " . USERS_COMMENTS_TABLE . " 
				WHERE comment_id = $comment_id";
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not query comments information', '', __LINE__, __FILE__, $sql);
			}

			if( !$row = $db->sql_fetchrow($result) )
			{
				message_die(GENERAL_MESSAGE, 'No_such_comment_exists');
			}

			$user_id = $row['user_id'];
			$comments = $row['comments'];
		}

		$page_title = $lang['Profile_comments'];
		include($phpbb_root_path . 'includes/page_header.'.$phpEx);

		$template->set_filenames(array(
			'body' => 'profile_comment_edit_body.tpl')
		);
		make_jumpbox ('viewforum.'.$phpEx);

		$s_hidden_fields = '<input type="hidden" name="sid" value="' . $userdata['session_id'] . '" />';
		$s_hidden_fields .= ( $mode == 'editpost' ) ? '<input type="hidden" name="mode" value="editpost" />' : '<input type="hidden" name="mode" value="post" />';
		$s_hidden_fields .= '<input type="hidden" name="' . POST_USERS_URL . '" value="' . $user_id . '" />';
		$s_hidden_fields .= ( $mode == 'editpost' ) ?'<input type="hidden" name="commentid" value="' . $comment_id . '" />' : '';

		$username = get_userdata($user_id);
		
		$template->assign_vars(array(
			'MESSAGE_TITLE' => $page_title,
			'L_COMMENTS' => $lang['Comments'],
			'L_EMPTY_COMMENTS' => $lang['Empty_comments'],

			'USERNAME' => $username['username'],
			'COMMENTS' => $comments,

			'U_COMMENTS' => append_sid("profile_comments.$phpEx?" . POST_USERS_URL . "=$user_id"),

			'S_POST_ACTION' => append_sid("profile_comments.$phpEx"),
			'S_HIDDEN_FIELDS' => $s_hidden_fields)
		);

		$template->pparse('body');

		include($phpbb_root_path . 'includes/page_tail.'.$phpEx);
		exit;
	}

	else if( $submit )
	{
		//
		// Session id check
		//
		if( $userdata['session_id'] != $sid )
		{
			message_die(GENERAL_MESSAGE, 'Invalid session');
		}


		$current_time = time();
		if( $mode == 'post' )
		{
			$sql = "SELECT MAX(time) AS last_time 
				FROM " . USERS_COMMENTS_TABLE . " 
				WHERE poster_id = " . $userdata['user_id'];
			if( $result = $db->sql_query($sql) )
			{
				if( $row = $db->sql_fetchrow($result) )
				{
					if (intval($row['last_time']) > 0 && ($current_time - intval($row['last_time'])) < intval($board_config['flood_interval']))
					{
						message_die(GENERAL_MESSAGE, $lang['Flood_Error']);
					}
				}
			}
		}

		if( empty($comments) )
		{
			message_die(GENERAL_MESSAGE, $lang['Empty_comments']);
		}

		$sql = ( $mode == 'editpost' ) ? "UPDATE " . USERS_COMMENTS_TABLE . " SET comments = '" . str_replace("\'", "''", $comments) . "', time = $current_time WHERE comment_id = $comment_id" : "INSERT INTO " . USERS_COMMENTS_TABLE . " (user_id, poster_id, comments, time) VALUES ($user_id, " . $userdata['user_id'] . ", '" . str_replace("\'", "''", $comments) . "', $current_time)";
		if( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not insert comments information', '', __LINE__, __FILE__, $sql);
		}

		$message = ( ( $mode == 'editpost' ) ? $lang['Comments_edited'] : $lang['Comments_added'] ) . '<br /><br />' . sprintf($lang['Click_return_comments'], '<a href="' . append_sid("profile_comments.$phpEx?" . POST_USERS_URL . "=$user_id") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_viewprofile'], '<a href="' . append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=$user_id") . '">', '</a>');
	
		message_die(GENERAL_MESSAGE, $message);
	}
}


if( $mode == 'delete' )
{
	$sql = "SELECT * 
		FROM " . USERS_COMMENTS_TABLE . " 
		WHERE comment_id = $comment_id";
	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Could not retrieve comments information', '', __LINE__, __FILE__, $sql);
	}
	if( !$row = $db->sql_fetchrow($result) )
	{
		message_die(GENERAL_MESSAGE, 'No_such_comments');
	}

	$user_id = $row['user_id'];

	if( !$confirm )
	{
		$s_hidden_fields = '<input type="hidden" name="sid" value="' . $userdata['session_id'] . '" />';
		$s_hidden_fields .= '<input type="hidden" name="mode" value="delete" />';
		$s_hidden_fields .= '<input type="hidden" name="commentid" value="' . $comment_id . '" />';
		$s_hidden_fields .= '<input type="hidden" name="' . POST_USERS_URL . '" value="' . $user_id . '" />';

		$page_title = $lang['Confirmation'];
		include($phpbb_root_path . 'includes/page_header.'.$phpEx);

		$template->set_filenames(array(
			'body' => 'confirm_body.tpl')
		);

		$template->assign_vars(array(
			'MESSAGE_TITLE' => $lang['Confirmation'],
			'MESSAGE_TEXT' => $lang['Confirm_delete_comments'],

			'L_YES' => $lang['Yes'],
			'L_NO' => $lang['No'],

			'S_CONFIRM_ACTION' => append_sid("profile_comments.$phpEx"),
			'S_HIDDEN_FIELDS' => $s_hidden_fields)
		);

		$template->pparse('body');

		include($phpbb_root_path . 'includes/page_tail.'.$phpEx);
		exit;
	}

	if( $confirm )
	{
		$sql = "DELETE FROM " . USERS_COMMENTS_TABLE . " 
			WHERE comment_id = $comment_id";
		if( !$db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not delete comments information', '', __LINE__, __FILE__, $sql);
		}

		$message = $lang['Comments_deleted'] . '<br /><br />' . sprintf($lang['Click_return_comments'], '<a href="' . append_sid("profile_comments.$phpEx?" . POST_USERS_URL . "=$user_id") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_viewprofile'], '<a href="' . append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=$user_id") . '">', '</a>');
		
		message_die(GENERAL_MESSAGE, $message);
	}
}


//
// Default page
//
$sql = "SELECT uc.*, u.user_id AS poster_id, u.username AS poster_name, u.user_level AS poster_level, time
	FROM " . $table_prefix . "users_comments uc, " . USERS_TABLE . " u
	WHERE uc.user_id = $user_id
		AND u.user_id = uc.poster_id
	ORDER BY time DESC
	LIMIT $start, " . $board_config['posts_per_page'];
if( !$result = $db->sql_query($sql) )
{
	message_die(GENERAL_ERROR, 'Could not retrieve comments', '', __LINE__, __FILE__, $sql);
}

$comments_row = array();
while( $row = $db->sql_fetchrow($result) )
{
	$comments_row[] = $row;
}
$db->sql_freeresult($result);

$sql_total = "SELECT COUNT(comment_id) AS total 
	FROM " . USERS_COMMENTS_TABLE . " 
	WHERE user_id = $user_id";
if( !$result = $db->sql_query($sql_total) )
{
	message_die(GENERAL_ERROR, 'Could not retrieve comments total', '', __LINE__, __FILE__, $sql_total);
}

$row = $db->sql_fetchrow($result);
$total_comments = $row['total'];


//
// Output data to page
//
$page_title = $lang['Profile_comments'];
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

$template->set_filenames(array(
	'body' => 'profile_comments_all_body.tpl')
);
make_jumpbox ('viewforum.'.$phpEx);

$template->assign_vars(array(
	'L_POSTER' => $lang['Poster'],
	'L_COMMENTS' => $lang['Comments'],
	'L_TIME' => $lang['Posted'],
	'L_NO_COMMENTS' => $lang['No_comments'],
	'L_ACTION' => $lang['Action'],
	
	'EDIT_IMG' => '<img src="' . $images['acp_edit'] . '" alt="' . $lang['Edit'] . '" title="' . $lang['Edit'] . '" />',
	'DELETE_IMG' => '<img src="' . $images['acp_delete'] . '" alt="' . $lang['Delete'] . '" title="' . $lang['Delete'] . '" />',

	'COLSPAN' => ( $userdata['user_level'] == ADMIN ) ? 4 : 3,
	'USERNAME' => $profiledata['username'],

	'U_VIEW_PROFILE' => append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=$user_id"),
	'U_READ_COMMENTS' => append_sid("profile_comments.$phpEx?" . POST_USERS_URL . "=$user_id"),

	'S_POST_URL' => '<a href="' . append_sid("profile_comments.$phpEx?mode=post&amp;" . POST_USERS_URL . "=$user_id") . '">' . $lang['Post_comments'] . '</a>',
	'S_HIDDEN_FIELDS' => $s_hidden_fields)
);

if( $userdata['user_level'] == ADMIN )
{
	$template->assign_block_vars ('admin_privs', array());
}

if( sizeof($comments_row) == 0 )
{
	$template->assign_block_vars('switch_no_comments', array ());
}

else
{
	for($i = 0; $i < count($comments_row); $i++)
	{
		$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

		$template->assign_block_vars('comments_row', array(
			'ROW_CLASS' => $row_class,

			'POSTER' => username_level_color($comments_row[$i]['poster_name'], $comments_row[$i]['poster_level'], $comments_row[$i]['poster_id']),
			'COMMENTS' => $comments_row[$i]['comments'],
			'TIME' => create_date($board_config['default_dateformat'], $comments_row[$i]['time'], $board_config['board_timezone']))
		);

		if( $userdata['user_level'] == ADMIN )
		{
			$template->assign_block_vars ('comments_row.admin_privs', array(
				'U_EDIT_COMMENTS' => append_sid("profile_comments.$phpEx?mode=editpost&amp;commentid=" . $comments_row[$i]['comment_id']),
				'U_DELETE_COMMENTS' => append_sid("profile_comments.$phpEx?mode=delete&amp;commentid=" . $comments_row[$i]['comment_id']))
			);
		}
	}

	$template->assign_vars(array(
		'PAGINATION' => generate_pagination("profile_comments.$phpEx?" . POST_USERS_URL . "=$user_id", $total_comments, $board_config['posts_per_page'], $start),
		'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / intval($board_config['posts_per_page']) ) + 1 ), ceil( $total_comments / intval($board_config['posts_per_page']) )))
	);
}

$template->pparse('body');

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>