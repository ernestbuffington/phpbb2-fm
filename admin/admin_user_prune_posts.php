<?php
/***************************************************************************
 *                          admin_user_prune_posts.php
 *                            -------------------
 *   begin                : Sunday, Jul 14, 2002
 *   copyright            : (C) 2001 The phpBB Group
 *   email                : support@phpbb.com
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

if( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	$module['Users']['Prune_user_posts'] = $file;
	return;
}

//
// Set phpBB Paths & Get required files
//
$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require('pagestart.' . $phpEx);
require($phpbb_root_path . 'includes/functions_search.'.$phpEx); 


//
// If button is pushed
//
if( isset($HTTP_POST_VARS['doprune']) )
{
	//
	// This function will get the last post id in a forum. If there are no more
	// posts, it will return 0, which phpBB interprets as no new posts.
	//
	function get_last_forum_post($forum_id)
	{
		global $db;
		
		$sql = "SELECT MAX(post_id) AS last_post_id 
			FROM " . POSTS_TABLE . " 
			WHERE forum_id = $forum_id";			
		$result = $db->sql_query($sql);
		
		if( !$result )
		{
		 	message_die(GENERAL_ERROR, "Could not select last post from posts table", "", __LINE__, __FILE__, $sql);
		}
		
		$last_data = $db->sql_fetchrow($result);
		
		if(!is_null($last_data['last_post_id']))
		{
			return $last_data['last_post_id'];
		}
		else
		{
			return 0;
		}	
	}
	
	//
	// Delete all related Poll Data for a Post
	//
	function delete_poll_data($topic_id)
	{
		global $db;
		
		$sql = "SELECT vote_id
			FROM " . VOTE_DESC_TABLE . "
			WHERE topic_id = $topic_id";				
		$result = $db->sql_query($sql);
		if( !$result )
		{
		 	message_die(GENERAL_ERROR, 'Could not select poll data', '', __LINE__, __FILE__, $sql);
		}
				
		$row = $db->sql_fetchrow($result);
				
		$vote_id = $row['vote_id'];
				
		$sql = "DELETE FROM " . VOTE_DESC_TABLE . " 
		   WHERE topic_id = $topic_id";			
		if( !$db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not delete poll description', '', __LINE__, __FILE__, $sql);
		}
				
		$sql = "DELETE FROM " . VOTE_RESULTS_TABLE . " 
			WHERE vote_id = $vote_id";				
		if( !$db->sql_query($sql) )
		{
		 	message_die(GENERAL_ERROR, 'Could not delete poll results', '', __LINE__, __FILE__, $sql);
		}

		$sql = "DELETE FROM " . VOTE_USERS_TABLE . " 
		   WHERE vote_id = $vote_id";				
		if( !$db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not delete poll voters', '', __LINE__, __FILE__, $sql);
		}				
	}

	//
	// This function will delete entries from the POSTS, POSTS_TEXT, update the user's
	// post count and call on the search words delete function. I rewrote this because
	// I needed to separate the polls delete from the regular deletion and also the
	// user post count wasn't incorporated into the regular delete function.
	//
 	function delete_post($post_id, $user_id)
	{
	 	global $db;
		
		$sql = "DELETE FROM " . POSTS_TABLE . "
			WHERE post_id = $post_id";
		if( !$db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, "Could not delete post from posts table", "", __LINE__, __FILE__, $sql);
		}
		
		$sql = "DELETE FROM " . POSTS_TEXT_TABLE . " 
		   	WHERE post_id = $post_id";						
		if ( !$db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Error in deleting post text', '', __LINE__, __FILE__, $sql);
		}
		
		$sql = "UPDATE " . USERS_TABLE . " 
			SET user_posts = user_posts - 1
			WHERE user_id = $user_id";		
		if ( !$db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Error updating user post count', '', __LINE__, __FILE__, $sql);
		}
		
		remove_search_post($post_id);
	}

	//
	// Due to functionality reasons, I also rewrote this function. This does a lot of the
	// work to keep the board in sync. It will send all the posts in a topic to delete,
	// delete polls properly, update forum statistics (topic count, posts count update the
	// last post id for good measure. It will also delete all shadow copies of a topic and
	// remove topic watches for a topic.
	//	
	function delete_topic($topic_id, $topic_vote, $topic_moved_id, $forum_id)
	{
	    global $db;
		
		if($topic_moved_id == '0')
		{
		 	$sql = "SELECT post_id,poster_id
				FROM " . POSTS_TABLE . "
				WHERE topic_id = $topic_id";					  
			$result = $db->sql_query($sql);
			if( !$result )
			{
			 	message_die(GENERAL_ERROR, "Could not select posts from posts table", "", __LINE__, __FILE__, $sql);
			}
			
			$row_count = $db->sql_numrows($result);
			
			while($post_data = $db->sql_fetchrow($result))
			{
		 	 	delete_post($post_data['post_id'], $post_data['poster_id']);
			}
			$db->sql_freeresult($result);
			
			$last_post_id = get_last_forum_post($forum_id);

			$sql = "UPDATE ". FORUMS_TABLE ." 
				SET forum_posts = forum_posts - $row_count, forum_topics = forum_topics - 1, forum_last_post_id = $last_post_id 
				WHERE forum_id = $forum_id";			
			$result = $db->sql_query($sql);
			if( !$result )
			{
			 	message_die(GENERAL_ERROR, "Could not update forum table", "", __LINE__, __FILE__, $sql);
			}
			
			$sql = "DELETE FROM " . TOPICS_WATCH_TABLE . " 
				WHERE topic_id = $topic_id";					
			if( !$db->sql_query($sql) )
			{
			    message_die(GENERAL_ERROR, 'Could not change topic notify data', '', __LINE__, __FILE__, $sql);
			}
			
			if( $topic_vote == '1' )
			{
				delete_poll_data($topic_id);				
			}
			
			$sql = "DELETE FROM " . TOPICS_TABLE . " 
				WHERE topic_id = $topic_id";		
			if( !$db->sql_query($sql) )
			{
			    message_die(GENERAL_ERROR, 'Could not change topic notify data', '', __LINE__, __FILE__, $sql);
			}			 	
		}
		else
		{
		 	$sql = "DELETE FROM ". TOPICS_TABLE ."
			    WHERE topic_id = $topic_id";			
			if ( !$db->sql_query($sql) )
			{
			    message_die(GENERAL_ERROR, 'Could not delete topic from topics table', '', __LINE__, __FILE__, $sql);
			}
			
			$sql = "UPDATE ". FORUMS_TABLE ." 
				SET forum_topics = forum_topics - 1";		
			if ( !$db->sql_query($sql) )
			{
			    message_die(GENERAL_ERROR, 'Could not update forum stats', '', __LINE__, __FILE__, $sql);
			}
		}			
	}
	
	//
	// This function will be the gateway to everything. It will find all the posts by the
	// user, and send for deletion if it was a topic or just the last post.
	//
  	function prune_post($post_id,$topic_id,$forum_id,$user_id)
	{
		global $db;

		$sql = "SELECT topic_id,forum_id, topic_vote, topic_first_post_id, topic_last_post_id, topic_moved_id 
		 	FROM " . TOPICS_TABLE . "
 			WHERE topic_first_post_id = $post_id 
 				OR topic_last_post_id = $post_id";
		$result = $db->sql_query($sql);
		if( !$result )
		{
			message_die(GENERAL_ERROR, "Could not check the topics table", "", __LINE__, __FILE__, $sql);
		}
		
		if( $db->sql_numrows($result) != '0' )
		{
		 	while( $topic_data = $db->sql_fetchrow($result) )
			{
		 	 	if( (($topic_data['topic_first_post_id'] == $post_id) && ($topic_data['topic_last_post_id'] == $post_id)) || ($topic_data['topic_first_post_id'] == $post_id) )
				{
			 	 	delete_topic($topic_data['topic_id'], $topic_data['topic_vote'], $topic_data['topic_moved_id'], $topic_data['forum_id']);
				}
				else
				{
			 	 	delete_post($post_id,$user_id);
				 
				 	$topic_id = $topic_data['topic_id'];
				
					$sql = "SELECT MAX(post_id) AS post_id
				 		FROM " . POSTS_TABLE . "
						WHERE topic_id = $topic_id";			 
				 	$result = $db->sql_query($sql);
				 	if( !$result )
				 	{
				     	message_die(GENERAL_ERROR, 'Could not select latest post data', '', __LINE__, __FILE__, $sql);
				 	}
				 
				 	$row = $db->sql_fetchrow($result);
				 	
					if($row['last_post_id'])
					{
						$last_post_id = $row['post_id'];
				 
				 		$sql = "UPDATE " . TOPICS_TABLE . " 
				 			SET topic_last_post_id = $last_post_id, topic_replies = topic_replies - 1
							WHERE topic_id = $topic_id";			 
				 		if( !$db->sql_query($sql) )
				 		{
				     		message_die(GENERAL_ERROR, 'Could not update topics data', '', __LINE__, __FILE__, $sql);
				 		}
					}
				 
				 	$last_post_id = get_last_forum_post($forum_id);
							
					$sql = "UPDATE " . FORUMS_TABLE . " 
						SET forum_posts = forum_posts - 1, forum_last_post_id = $last_post_id
						WHERE forum_id = $forum_id";			
				 	$result = $db->sql_query($sql);
				 	if( !$result )
				 	{
			 	  	 	message_die(GENERAL_ERROR, "Could not update forum table", "", __LINE__, __FILE__, $sql);
				 	}
					
					return 0;				  				 				 
			 	}			  	 
			}
			$db->sql_freeresult($result);
		}
		else
		{
			//
			// If post wasn't a topic (or the last in one)
			//
		 	delete_post($post_id,$user_id);
			
			$sql = "UPDATE " . FORUMS_TABLE . " 
				SET forum_posts = forum_posts - 1
				WHERE forum_id = $forum_id";	
			$result = $db->sql_query($sql);
			if( !$result )
			{
			 	message_die(GENERAL_ERROR, "Could not update forum table", "", __LINE__, __FILE__, $sql);
			}
			
			$sql = "UPDATE " . TOPICS_TABLE . " 
				SET topic_replies = topic_replies - 1
				WHERE topic_id = $topic_id";							
			$result = $db->sql_query($sql);
			if( !$result )
			{
			 	message_die(GENERAL_ERROR, "Could not update topic data", "", __LINE__, __FILE__, $sql);
			}
		}			
	}
	
	$delete_count = 0;
	
	$sql = "SELECT user_id 
		FROM " . USERS_TABLE . " 
		WHERE username = '" . $HTTP_POST_VARS['username'] . "'";
	$result = $db->sql_query($sql);
	if( !$result )
	{
	 	message_die(GENERAL_ERROR, "Could not get user data", "", __LINE__, __FILE__, $sql);
	}
	
	$fetch_data = $db->sql_fetchrow($result);
		
	$user_id = $fetch_data['user_id'];
	
	//
	// Could not find username
	//
	if($db->sql_numrows($result) == '0')
	{
		message_die(GENERAL_MESSAGE, $lang['No_user_id_specified']);
	}
	//
	// Prune posts in all forums
	//
	else if($HTTP_POST_VARS['forum_id']=='all')
	{
	 	$sql = "SELECT post_id, forum_id, topic_id
			FROM " . POSTS_TABLE . " 
			WHERE poster_id = " . $user_id;
		$result = $db->sql_query($sql);
		
		if( !$result )
		{
			message_die(GENERAL_ERROR, "Could not select posts from posts table" , "", __LINE__, __FILE__, $sql);
		}
		
		while( $post_data = $db->sql_fetchrow($result) )
		{
		 	prune_post($post_data['post_id'], $post_data['topic_id'], $post_data['forum_id'], $user_id);
			$delete_count++;
		}	
		$db->sql_freeresult($result);
	}
	else
	{
		//
		// Prune Posts for a specific forum
		//
		$forum_id = intval($HTTP_POST_VARS['forum_id']);
		
	 	$sql = "SELECT post_id,topic_id 
			FROM " . POSTS_TABLE . " 
			WHERE poster_id = $user_id 
				AND forum_id = $forum_id";
		
		$result = $db->sql_query($sql);
		
		if( !$result )
		{
			message_die(GENERAL_ERROR, "Could not select posts from posts table", "", __LINE__, __FILE__, $sql);
		}

		while( $post_data = $db->sql_fetchrow($result) )
		{
		 	$to_update += prune_post($post_data['post_id'], $post_data['topic_id'], $forum_id, $user_id);
			$delete_count++;		
		}   
		$db->sql_freeresult($result);
	}
	
	//
	// Depending on how many posts were delete, it will display different wording
	//	
	if($delete_count == 0)
	{
		$l_delete_text = $lang['Prune_result_n'];
	}
	else if($delete_count == 1)
	{
		$l_delete_text = $lang['Prune_result_s'];
	}
	else if($delete_count >= 2)
	{
		$l_delete_text = $lang['Prune_result_p'];
	}
	
	if($not_found == 1)
	{
		message_die(GENERAL_MESSAGE, $lang['No_user_id_specified']);
		
	}
	else
	{				
		$message =sprintf($l_delete_text, $delete_count) . "<br /><br />" . sprintf($lang['Click_return_User_Prune'], "<a href=\"" . append_sid("admin_user_prune_posts.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");
		
		message_die(GENERAL_MESSAGE, $message);
	}
}
else
{
	//
	// Display select screen
	//
	$sql = "SELECT forum_id, forum_name 
		FROM " . FORUMS_TABLE . " 
		WHERE forum_id > 0
		ORDER BY forum_name";
	$result = $db->sql_query($sql);
	
	if( !$result )
	{
		message_die(GENERAL_ERROR, "Could not select users from users table", "", __LINE__, __FILE__, $sql);
	}
	
	$result_rows = $db->sql_fetchrowset($result);
	$result_count = $db->sql_numrows($result);
	
	for($i = 0; $i < $result_count; $i++)
	{
		$template->assign_block_vars("forums", array(
			"FORUM_ID" => $result_rows[$i]['forum_id'],
			"FORUM_NAME" => $result_rows[$i]['forum_name'])
		);
	}
		
	$template->set_filenames(array(
	    "body" => "admin/user_prune_posts_body.tpl")
	);

	$template->assign_vars(array(
		'L_PRUNE_TITLE' => $lang['Prune_user_posts'],
		'L_PRUNE_DESC' => $lang['User_Prune_explain'],			
		'L_USER_NAME' => $lang['Username'],
		'L_FORUM_NAME' => $lang['Forum'],
		'L_BUTTON' => $lang['Do_Prune'],
		'L_FIND_USERNAME' => $lang['Find_username'],
		'L_RESET' => $lang['Reset'],

		'U_SEARCH_USER' => append_sid("./../search.$phpEx?mode=searchuser"), 
						
		'S_PRUNE_ACTION' => append_sid("admin_user_prune_posts.$phpEx"))
	);
}

$template->pparse("body");

include('page_footer_admin.'.$phpEx);

?>