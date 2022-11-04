<?php
/***************************************************************************
 *                               viewtopic_karma.php
 *                            -------------------
 *   begin                : Thursday, Jan 24, 2004
 *   copyright            : (C) Nome
 *   email                : nome@bk.ru
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

unset($x);

define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);

// $_GET variables
if ( isset($_GET['t']) ) 
{ 
	$topic_id = intval($_GET['t']); 
} 
else 
{ 
	die("Hacking attempt"); 
}

if ( isset($_GET['u']) ) 
{ 
	$user = intval($_GET['u']); 
} 
else 
{ 
	die("Hacking attempt"); 
}

if ( isset($_GET['x']) ) 
{ 
	$x = htmlspecialchars($_GET['x']); 
} 
else 
{ 
	die("Hacking attempt"); 
}

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_PROFILE);
init_userprefs($userdata);
//
// End session management
//
	
if ( !$board_config['allow_karma'] )
{
	$message = $lang['Karma_disabled'] . '<br /><br />' . sprintf($lang['Click_return_topic'], '<a href="' . append_sid('viewtopic.'.$phpEx.'?' . POST_TOPIC_URL . '=' . $topic_id) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid('index.'.$phpEx) . '">', '</a>');

	message_die(GENERAL_MESSAGE, $message);
}

if ( !$userdata['session_logged_in'] ) 
{ 
	redirect("login.".$phpEx."?redirect=viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id"); 
	exit; 
} 
else
{
	// get last time user tried a karma vote
	$sql = "SELECT karma_time 
		FROM " . USERS_TABLE . " 
		WHERE user_id = " . $userdata['user_id']; 
	$result = $db->sql_query($sql);
	
	$array = mysql_fetch_array($result);
	
	$time_old = $array[0];
	
	// make sure no one votes for themselves
	$sql = "SELECT user_id 
		FROM " . USERS_TABLE . " 
		WHERE user_id = " . $userdata['user_id']; 
	$result = $db->sql_query($sql);

	$array = mysql_fetch_array($result);

	$voter_id = $array[0];

	if( $voter_id == $user )
	{
		message_die(GENERAL_ERROR, $lang['No_Self_Karma'] . '<br /><br />' . sprintf($lang['Click_return_topic'], '<a href="' . append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid('index.'.$phpEx) . '">', '</a>'));
	}
	else
	{
		$time = time();
		$diff = $time - $time_old;
		
		if( $diff >= 3600 * $board_config['karma_flood_interval'] || $userdata['user_level'] > $board_config['karma_admins'] ) //make sure they haven't voted in the last hour or if they're a mod or admin, they can continue
		{
			if ( $x == 'applaud' )
			{
				$sql = "SELECT karma_plus 
					FROM " . USERS_TABLE . " 
					WHERE user_id = " . $user; //Find the good guy
				$result = $db->sql_query($sql); 
				
				$array = mysql_fetch_array($result);
 				
 				$karma = $array[0];
				
				// We only up karma by one
				$karma = $karma + 1;
				// Here comes the db update 
				$karma_update = "UPDATE " . USERS_TABLE . " 
					SET karma_plus = '$karma' 
					WHERE user_id = " . $user;	
			}
			else
			// If someone tries to fake the x input, that someone will get bad karma ;)
			{
				$sql = "SELECT karma_minus 
					FROM " . USERS_TABLE . " 
					WHERE user_id = '$user'"; //Find the bad guy
				$result = $db->sql_query($sql); 
				
				$array = mysql_fetch_array($result);
 				
 				$karma = $array[0];
				
				// We only up karma by one
				$karma = $karma + 1;
				// Here comes the db update 
				$karma_update = "UPDATE " . USERS_TABLE . " 
					SET karma_minus = '$karma' 
					WHERE user_id = " . $user;
			}

			// Update the database with current time() for voter
			$time_update = "UPDATE " . USERS_TABLE . " 
				SET karma_time = '$time' 
				WHERE user_id = " . $userdata['user_id'];
			$result = $db->sql_query($karma_update);
			
			$time_result = $db->sql_query($time_update);
	
			if($result&&$time_result) //Both gotta happen...
			{	   
				if(!isset($topic_id))
				{
    				header('Location:' . append_sid("index.$phpEx"));
   					break;
 				}
				else
				{
					header('Location:' . append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id"));
				}
			}
			else
			{
				$message = $lang['Critical_Error'] . '<br /><br />' . sprintf($lang['Click_return_topic'], '<a href="' . append_sid('viewtopic.'.$phpEx.'?' . POST_TOPIC_URL . '=' . $topic_id) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid('index.'.$phpEx) . '">', '</a>');
	
				message_die(GENERAL_MESSAGE, $message);
			}
		}
		else
		{
			$message = $lang['Too_Soon'] . '<br /><br />' . sprintf($lang['Click_return_topic'], '<a href="' . append_sid('viewtopic.'.$phpEx.'?' . POST_TOPIC_URL . '=' . $topic_id) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid('index.'.$phpEx) . '">', '</a>');
	
			message_die(GENERAL_MESSAGE, $message);
		}
	}
}

?>