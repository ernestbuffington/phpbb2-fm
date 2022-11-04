<?php
/** 
*
* @package phpBB2
* @version $Id: topic_subscribe.php,v 1.0.0 2004/03/20 mosymuis Exp $
* @copyright (c) 2004 mosymuis
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_INDEX);
init_userprefs($userdata);
//
// End session management
//


if ( isset($HTTP_GET_VARS['subscribe_to']) )
{
	$subscribe_to = intval($HTTP_GET_VARS['subscribe_to']);

	$mode = 'subscribe';

	if ( !$userdata['session_logged_in'] )
	{
		redirect(append_sid("login.$phpEx?redirect=topic_subscribe.$phpEx?subscribe_to=$subscribe_to", true));
	}
}
else if ( isset($HTTP_GET_VARS['unsubscribe_from']) ) 
{
	$unsubscribe_from = intval($HTTP_GET_VARS['unsubscribe_from']);

	$mode = 'unsubscribe';

	if ( !$userdata['session_logged_in'] )
	{
		redirect(append_sid("login.$phpEx?redirect=topic_subscribe.$phpEx?unsubscribe_from=$unsubscribe_from", true));
	}
} 
else 
{
	if ( $userdata['session_logged_in'] )
	{
		message_die(GENERAL_ERROR, 'Select_topic');
	} 
	else 
	{
		redirect(append_sid("login.$phpEx", true));
	}
}

if ( $userdata['user_level'] != ADMIN ) 
{
	message_die(GENERAL_MESSAGE, $lang['Not_Authorised']);
}

switch($mode)
{
	case 'subscribe':
		$page_title = $lang['Subscribe_members'];

		$sql = "SELECT topic_title
			FROM " . TOPICS_TABLE . "
			WHERE topic_id = $subscribe_to";
	break;

	case 'unsubscribe':
		$page_title = $lang['Unsubscribe_members'];

		$sql = "SELECT topic_title
			FROM " . TOPICS_TABLE . "
			WHERE topic_id = $unsubscribe_from";
	break;
}

if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not obtain topic information', '', __LINE__, __FILE__, $sql);
}

if ( $row = $db->sql_fetchrow($result) )
{
	$topic_title = $row['topic_title'];
}
else 
{
	message_die(GENERAL_ERROR, 'Topic_error');
}

switch($mode)
{
	case 'subscribe':
		$sql = "SELECT user_id
			FROM " . TOPICS_WATCH_TABLE . "
			WHERE topic_id = $subscribe_to";
		if( !($result = $db->sql_query($sql)) )
		{
			message_die(CRITICAL_ERROR, "Could not query users watching topic", "", __LINE__, __FILE__, $sql);
		}
		
		$users_watching = array();
		while ( $row = $db->sql_fetchrow($result) )
		{
			$users_watching[] = $row['user_id'];
		}

		$users_watching_sql = sizeof($users_watching) ? "AND user_id NOT IN(" . implode(', ', $users_watching) . ")" : '';

		$sql = "SELECT user_id
			FROM " . USERS_TABLE . "
			WHERE user_id <> " . ANONYMOUS . "
			$users_watching_sql";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not get members information', '', __LINE__, __FILE__, $sql);
		}

		$sql = "INSERT INTO " . TOPICS_WATCH_TABLE . " (topic_id, user_id, notify_status) 
			VALUES ";
		if ( $db->sql_numrows($result) )
		{
			for( $i = 0; $row = $db->sql_fetchrow($result); $i++ )
			{
				$sql .= "($subscribe_to, " . $row['user_id'] . ", " . TOPIC_WATCH_UN_NOTIFIED . "), ";
			}
			$sql = substr($sql, 0, strlen($sql)-2);
		
			if ( $db->sql_query($sql) )
			{
				$message = sprintf($lang['Subscribe_successful'], $i);
			} 
			else 
			{
				message_die(GENERAL_ERROR, 'Could not insert users in topic watch table', '', __LINE__, __FILE__, $sql);
			}
		} 
		else 
		{
			$message = $lang['All_members_subscribed'];
		}
	break;

	case 'unsubscribe':
		$sql = "DELETE FROM " . TOPICS_WATCH_TABLE . "
			WHERE topic_id = $unsubscribe_from";
		if ( $db->sql_query($sql) )
		{
			$message = $lang['Unsubscribe_successful'];
		} 
		else 
		{
			message_die(GENERAL_ERROR, 'Could not delete users from topic watch table', '', __LINE__, __FILE__, $sql);
		}
	break;
}

include($phpbb_root_path . 'includes/page_header.'.$phpEx);

$template->set_filenames(array(
	'body' => 'topic_subscribe.tpl')
);

$template->assign_vars(array(
	'L_TITLE' => $page_title . ': ' . $topic_title,
	'U_RETURN_TO_TOPIC' => sprintf($lang['Click_return_topic'], '<a href="' . append_sid('viewtopic.'.$phpEx.'?' . POST_TOPIC_URL . '=' . (($mode == 'subscribe') ? $subscribe_to : $unsubscribe_from)) . '">', '</a>'),

	'MESSAGE' => $message)
);

//
// Force password update
//
if ($board_config['password_update_days'])
{
	include($phpbb_root_path . 'includes/update_password.'.$phpEx);
}

$template->pparse('body');

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>