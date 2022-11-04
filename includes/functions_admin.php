<?php
/** 
*
* @package includes
* @version $Id: functions_admin.php,v 1.5.2.3 2002/07/19 17:03:47 psotfx Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
	
//
// Simple version of jumpbox, just lists authed forums
//
function make_forum_select($box_name, $ignore_forum = false, $select_forum = '')
{
	global $db, $userdata, $lang;

	$is_auth_ary = auth(AUTH_READ, AUTH_LIST_ALL, $userdata);

	$sql = 'SELECT f.forum_id, f.forum_name
		FROM ' . CATEGORIES_TABLE . ' c, ' . FORUMS_TABLE . ' f
		WHERE f.cat_id = c.cat_id 
		ORDER BY c.cat_order, f.forum_order';
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Couldn not obtain forums information', '', __LINE__, __FILE__, $sql);
	}

	$forum_list = '';
	while( $row = $db->sql_fetchrow($result) )
	{
		if ( $is_auth_ary[$row['forum_id']]['auth_read'] && $ignore_forum != $row['forum_id'] )
		{
			$selected = ( $select_forum == $row['forum_id'] ) ? ' selected="selected"' : '';
			$forum_list .= '<option value="' . $row['forum_id'] . '"' . $selected .'>' . $row['forum_name'] . '</option>';
		}
	}

	$forum_list = ( $forum_list == '' ) ? $lang['No_forums'] : '<select name="' . $box_name . '" id="' . $box_name . '">' . $forum_list . '</select>';

	return $forum_list;
}

//
// Synchronise functions for forums/topics
//
function sync($type, $id = false)
{
	global $db;

	switch($type)
	{
		case 'all forums':
			$sql = "SELECT forum_id
				FROM " . FORUMS_TABLE;
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not get forum IDs', '', __LINE__, __FILE__, $sql);
			}

			while( $row = $db->sql_fetchrow($result) )
			{
				sync('forum', $row['forum_id']);
			}
		   	break;

		case 'all topics':
			$sql = "SELECT topic_id
				FROM " . TOPICS_TABLE;
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not get topic IDs', '', __LINE__, __FILE__, $sql);
			}

			while( $row = $db->sql_fetchrow($result) )
			{
				sync('topic', $row['topic_id']);
			}
			break;

	  	case 'forum':
			// 
			// Count not only the posts/topics of the forum itself but of all forums junior to this
			//
			$forum_ids = get_list_inferior('forum', $id, 'forum');
			
			if( empty($forum_ids) )
			{
				$forum_ids = $id;
			}

			if( !empty($forum_ids) )
			{
				$sql = "SELECT MAX(post_id) AS last_post, COUNT(post_id) AS total 
					FROM " . POSTS_TABLE . "  
					WHERE forum_id IN($forum_ids)";
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'Could not get post ID', '', __LINE__, __FILE__, $sql);
				}
	
				if ( $row = $db->sql_fetchrow($result) )
				{
					$last_post = ( $row['last_post'] ) ? $row['last_post'] : 0;
					$total_posts = ($row['total']) ? $row['total'] : 0;
				}
				else
				{
					$last_post = 0;
					$total_posts = 0;
				}
	
				$sql = "SELECT COUNT(topic_id) AS total
					FROM " . TOPICS_TABLE . "
					WHERE forum_id IN($forum_ids)";
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'Could not get topic count', '', __LINE__, __FILE__, $sql);
				}
	
				$total_topics = ( $row = $db->sql_fetchrow($result) ) ? ( ( $row['total'] ) ? $row['total'] : 0 ) : 0;
	
				$sql = "UPDATE " . FORUMS_TABLE . "
					SET forum_last_post_id = $last_post, forum_posts = $total_posts, forum_topics = $total_topics
					WHERE forum_id = $id";
				if ( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'Could not update forum', '', __LINE__, __FILE__, $sql);
				}
			}
			break;
	
		case 'topic':
			$sql = "SELECT MAX(post_id) AS last_post, MIN(post_id) AS first_post, COUNT(post_id) AS total_posts
				FROM " . POSTS_TABLE . "
				WHERE topic_id = $id";
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not get post ID', '', __LINE__, __FILE__, $sql);
			}

			if ( $row = $db->sql_fetchrow($result) )
			{
				if ($row['total_posts'])
				{
					// Correct the details of this topic
					$sql = 'UPDATE ' . TOPICS_TABLE . ' 
						SET topic_replies = ' . ($row['total_posts'] - 1) . ', topic_first_post_id = ' . $row['first_post'] . ', topic_last_post_id = ' . $row['last_post'] . "
						WHERE topic_id = $id";

					if (!$db->sql_query($sql))
					{
						message_die(GENERAL_ERROR, 'Could not update topic', '', __LINE__, __FILE__, $sql);
					}
				}
				else
				{
					// There are no replies to this topic
					// Check if it is a move stub
					$sql = 'SELECT topic_moved_id 
						FROM ' . TOPICS_TABLE . " 
						WHERE topic_id = $id";

					if (!($result = $db->sql_query($sql)))
					{
						message_die(GENERAL_ERROR, 'Could not get topic ID', '', __LINE__, __FILE__, $sql);
					}

					if ($row = $db->sql_fetchrow($result))
					{
						if (!$row['topic_moved_id'])
						{
							$sql = 'DELETE FROM ' . TOPICS_TABLE . " WHERE topic_id = $id";
			
							if (!$db->sql_query($sql))
							{
								message_die(GENERAL_ERROR, 'Could not remove topic', '', __LINE__, __FILE__, $sql);
							}
						}
					}

					$db->sql_freeresult($result);
				}
			}
			attachment_sync_topic($id);
			break;
	}
	
	return true;
}

// $mode means return inferior categories or inferior forums (== 'category' | 'forum')
// $id means to which category|forum the result have to be inferior
// $mode_of_id says $id is either a cat_id or a forum_id
function get_list_inferior($mode, $id, $mode_of_id = 'category')
{
	global $db;

	if( empty($id) || $id == 0 )
	{
		return;
	}

	switch($mode_of_id)
	{
		case 'forum': // $id is a forum_id

			switch($mode)
			{
				case 'category':
					$sql = "SELECT c.cat_id
						FROM " . CATEGORIES_TABLE . " c
						WHERE c.parent_forum_id = $id
						ORDER BY c.cat_id";
					if( !($result = $db->sql_query($sql)) )
					{
						message_die(GENERAL_ERROR, 'Could not query inferior categories', '', __LINE__, __FILE__, $sql);
					}
					while( $row = $db->sql_fetchrow($result) )
					{
						if( empty($cats_inferior) )
						{
							$cats_inferior .= $row['cat_id'];
						}
						else
						{
							$cats_inferior .= ", " . $row['cat_id'];
						}

						$return = get_list_inferior($mode, $row['cat_id'], 'category');
						if( !empty($return) )
						{
							$cats_inferior .= ", $return";
						}
					}
						
					return($cats_inferior);
					break;

				case 'forum':
					$sql = "SELECT c.cat_id
						FROM " . CATEGORIES_TABLE . " c
						WHERE c.parent_forum_id = $id
						ORDER BY c.cat_id";
					if( !($result = $db->sql_query($sql)) )
					{
						message_die(GENERAL_ERROR, 'Could not query inferior categories', '', __LINE__, __FILE__, $sql);
					}
					while( $row = $db->sql_fetchrow($result) )
					{
						$return = get_list_inferior($mode, $row['cat_id'], 'category');
						if( !empty($return) )
						{
							if( empty($forums_inferior) )
							{
								$forums_inferior = $return;
							}
							else
							{
								$forums_inferior .= ", $return";
							}
						}
					}
						
					return($forums_inferior);
					break;
				
				default:
					message_die(GENERAL_ERROR, "Wrong mode for generating list of inferior", "", __LINE__, __FILE__);
					break;
			}
	
		case 'category': // $id is a cat_id
		
			switch($mode) 
			{
				case 'category':
					// print "<br>get_list_inferior, $mode, got id: $id<br>";
					$cats_inferior = "";
					$sql = "SELECT c.cat_id
						FROM " . CATEGORIES_TABLE . " c, " . CAT_REL_CAT_PARENTS_TABLE . " ccp
						WHERE ccp.cat_id = c.cat_id
						AND ccp.parent_cat_id = $id
						ORDER BY c.cat_hier_level";
		
					if( !($result = $db->sql_query($sql)) )
					{
						message_die(GENERAL_ERROR, 'Could not query inferior categories', '', __LINE__, __FILE__, $sql);
					}
					while( $row = $db->sql_fetchrow($result) )
					{
						if( empty($cats_inferior) )
						{
							$cats_inferior .= $row['cat_id'];
						}
						else
						{
							$cats_inferior .= ", " . $row['cat_id'];
						}
					}
					// print "<br>get_list_inferior, $mode, SQL: $sql<br><br>get_list_inferior, $mode, Result: $cats_inferior<br><br>";
					return $cats_inferior;
					break;
				
				case 'forum':
					// print "<br>get_list_inferior, $mode, got id: $id<br>";
					$forums_inferior = "";
		
					// get inferior forum_ids of inferior cat_id of cat_id
					$sql = "SELECT f.forum_id
						FROM " . CAT_REL_CAT_PARENTS_TABLE . " ccp, " . FORUMS_TABLE . " f
						WHERE ccp.parent_cat_id = $id
						AND f.cat_id = ccp.cat_id
						ORDER BY f.forum_hier_level";
		
					if( !($result = $db->sql_query($sql)) )
					{
						message_die(GENERAL_ERROR, 'Could not query inferior forums', '', __LINE__, __FILE__, $sql);
					}
					while( $row = $db->sql_fetchrow($result) )
					{
						if( empty($forums_inferior) )
						{
							$forums_inferior .= $row['forum_id'];
						}
						else
						{
							$forums_inferior .= ", " . $row['forum_id'];
						}
					}
		
					// get directly inferior forum_ids of cat_id
					$sql = "SELECT forum_id
						FROM " . FORUMS_TABLE . "
						WHERE cat_id = $id";
		
					if( !($result = $db->sql_query($sql)) )
					{
						message_die(GENERAL_ERROR, 'Could not query inferior forums', '', __LINE__, __FILE__, $sql);
					}
					while( $row = $db->sql_fetchrow($result) )
					{
						if( empty($forums_inferior) )
						{
							$forums_inferior .= $row['forum_id'];
						}
						else
						{
							$forums_inferior .= ", " . $row['forum_id'];
						}
					}
		
					// print "<br>get_list_inferior, $mode, SQL: $sql<br><br>get_list_inferior, $mode, Result: $forums_inferior<br><br>";
					return $forums_inferior;
					
					break;
		
				default:
					message_die(GENERAL_ERROR, "Wrong mode for generating list of inferior", "", __LINE__, __FILE__);
					break;
			}
		break;
		
	default:
		message_die(GENERAL_ERROR, "Wrong mode for generating list of inferior", "", __LINE__, __FILE__);
		break;
	}
}

?>