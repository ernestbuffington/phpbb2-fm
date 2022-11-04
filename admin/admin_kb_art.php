<?php
/** 
*
* @package admin
* @version $Id: admin_kb_art.php,v 1.0.0 2003/01/05 02:36:00 psotfx Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
	$file = basename(__FILE__);
	$module['KB_title']['Art_man'] = $file;
	return;
}

//
// Load default header
//
$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);
include($phpbb_root_path . 'includes/functions_admin.'.$phpEx);
include($phpbb_root_path . 'includes/functions_kb.'.$phpEx);
include_once($phpbb_root_path . 'includes/bbcode.'.$phpEx);
include_once($phpbb_root_path . 'includes/functions_post.'.$phpEx);
include_once($phpbb_root_path . 'includes/functions_search.'.$phpEx);

//
// Include language
//
$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_kb.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_kb.' . $phpEx);


if ( isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']) )
{
	$mode = ( isset($HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
	$mode = htmlspecialchars($mode); 
}
else
{
	if ( $approve )
	{
		$mode = 'approve';
	}
	else if ( $unapprove )
	{
		$mode = 'unapprove';
	}
	else if ( $delete )
	{
		$mode = 'delete';
	}
	else
	{
		$mode = '';
	}
}

switch( $mode )
{
 	case 'approve':
		$article_id = intval($HTTP_GET_VARS['a']);
	
		$topic_sql = '';
		if ( $board_config['kb_comments'] )
		{
		    $sql = "SELECT * 
		    	FROM " . KB_ARTICLES_TABLE . " 
		    	WHERE article_id = " . $article_id;	
			if ( !($results = $db->sql_query($sql)) )
			{
		        message_die(GENERAL_ERROR, "Could not obtain article data", '', __LINE__, __FILE__, $sql);
			}

			$row = $db->sql_fetchrow($results);
		
			if ( !$row['topic_id'] )
			{		
			    // choose a user
				$user_id = $board_config['kb_admin_id'];
	
				// initialise the userdata
				$sql = "SELECT * 
					FROM " . USERS_TABLE . " 
					WHERE user_id = $user_id";
				if ( !($result = $db->sql_query($sql)) )
				{
		   	        message_die(GENERAL_ERROR, 'Could not obtain userdata.', '', __LINE__, __FILE__, $sql);
				}
				$user = $db->sql_fetchrow($result);
				init_userprefs($user);
				
				$cat = get_kb_cat($row['article_category_id']);
			    $type = get_kb_type($row['article_type']);
			  	$author = get_kb_author($row['article_author_id']);
	
				$temp_url = "http://" . $board_config['server_name'] . $board_config['script_path'] . "kb.".$phpEx."?mode=article&k=".$article_id;
			    $message = "[b]Category:[/b] " . $row['article_category_id'] . " " . $cat['category_name'] . "\n";
			  	$message .= "[b]Article Type:[/b] " . $type . "\n\n";
			  	$message .= "[b]Article Title:[/b] " . $row['article_title'] . "\n";
			  	$message .= "[b]Author:[/b] " . $author . "\n";
			  	$message .= "[b]Article Description:[/b] " . $row['article_description'] . "\n\n";
			  	$message .= "[b][url=" . $temp_url . "]" . $lang['Read_full_article'] . "[/url][/b]";
		
				$subject = '[KB] ' . $row['article_title'];
				$forum_id = $board_config['kb_forum_id'];
		
				$topic_data = insert_post($message, $subject, $forum_id, $user['user_id'], $user['username'], $user['user_attachsig']);
				$topic_sql = ", topic_id = " . $topic_data['topic_id'];
			}
		}
		
		$sql = "UPDATE " . KB_ARTICLES_TABLE . "
			SET approved = 1 " . $topic_sql . "
			WHERE article_id = " . $article_id;
		if ( !($result = $db->sql_query($sql)) )
		{
   		   message_die(GENERAL_ERROR, "Could not update article data", '', __LINE__, __FILE__, $sql);
		}
	
		$sql = "SELECT article_category_id, article_body 
			FROM " . KB_ARTICLES_TABLE . "
	 		WHERE article_id = " . $article_id;
		if ( !($result = $db->sql_query($sql)) )	
		{
   	  		message_die(GENERAL_ERROR, "Could not obtain article category", '', __LINE__, __FILE__, $sql);
	 	}

	 	if ( $article = $db->sql_fetchrow($result) )
	 	{
	 	 	$article_category_id = $article['article_category_id'];
			$body = $article['article_body'];
		}
	 
	 	update_kb_number($article_category_id, '+ 1');
	 
	 	add_kb_words($article_id, $body);
	
		$message = $lang['Article_approved'] . '<br /><br />' . sprintf($lang['Click_return_article_manager'], '<a href="' . append_sid("admin_kb_art.$phpEx") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.$phpEx?pane=right") . '">', '</a>');

		message_die(GENERAL_MESSAGE, $message);
		break;

	case 'unapprove':
		$article_id = $HTTP_GET_VARS['a'];
	
		$sql = "UPDATE " . KB_ARTICLES_TABLE . " 
			SET approved = 0
		 	WHERE article_id = " . $article_id;
		if ( !($result = $db->sql_query($sql)) )
		{
   			message_die(GENERAL_ERROR, "Could not update article data", '', __LINE__, __FILE__, $sql);
		}
	
		$sql = "SELECT article_category_id 
			FROM " . KB_ARTICLES_TABLE . "
	 		WHERE article_id = " . $article_id;
		if ( !($result = $db->sql_query($sql)) )
		{
   	  		message_die(GENERAL_ERROR, "Could not obtain article category", '', __LINE__, __FILE__, $sql);
	 	}

	 	if ( $article = $db->sql_fetchrow($result) )
	 	{
	 	 	$article_category_id = $article['article_category_id'];
	 	}
	 
	 	update_kb_number($article_category_id, '- 1');
	
		$message = $lang['Article_unapproved'] . '<br /><br />' . sprintf($lang['Click_return_article_manager'], '<a href="' . append_sid("admin_kb_art.$phpEx") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.$phpEx?pane=right") . '">', '</a>');

		message_die(GENERAL_MESSAGE, $message);
		break;
	
	case 'delete':
		if ($HTTP_GET_VARS['c'] == "yes")
		{	
			$article_id = intval($HTTP_GET_VARS['a']);
	
			$sql = "SELECT article_category_id, approved, topic_id  
	 			FROM " . KB_ARTICLES_TABLE . "
				WHERE article_id = " . $article_id;
			if ( !($result = $db->sql_query($sql)) )
			{
   	  			message_die(GENERAL_ERROR, "Could not obtain article category", '', __LINE__, __FILE__, $sql);
			}

	 		if ( $article = $db->sql_fetchrow($result) )
	 		{
	  			$article_category_id = $article['article_category_id'];
	 		}

			if ($article['approved'] == 1)
			{
	 			update_kb_number($article_category_id, '- 1');
			}
	
			if ( $board_config['kb_del_topic'] && $article['topic_id'] )
			{
				$topic = $article['topic_id'];
	
				$sql = "SELECT poster_id, COUNT(post_id) AS posts 
					FROM " . POSTS_TABLE . " 
					WHERE topic_id = " . $topic . "  
					GROUP BY poster_id";
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'Could not get poster id information', '', __LINE__, __FILE__, $sql);
				}

				$count_sql = array();
				while ( $row = $db->sql_fetchrow($result) )
				{
					$count_sql[] = "UPDATE " . USERS_TABLE . " 
						SET user_posts = user_posts - " . $row['posts'] . " 
						WHERE user_id = " . $row['poster_id'];
				}
				$db->sql_freeresult($result);
	
				if ( sizeof($count_sql) )
				{
					for($i = 0; $i < sizeof($count_sql); $i++)
					{
						if ( !$db->sql_query($count_sql[$i]) )
						{
							message_die(GENERAL_ERROR, 'Could not update user post count information', '', __LINE__, __FILE__, $sql);
						}
					}
				}
			
				$sql = "SELECT forum_id 
				    FROM " . TOPICS_TABLE . "
					WHERE topic_id = $topic";
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'Could not get forum id information', '', __LINE__, __FILE__, $sql);
				}
		
				$forum_ids = array();
				while ( $row = $db->sql_fetchrow($result) )
				{
					$forum_id = $row['forum_id'];
				}
				$db->sql_freeresult($result);
				
				$sql = "SELECT post_id 
					FROM " . POSTS_TABLE . " 
					WHERE topic_id = $topic";
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'Could not get post id information', '', __LINE__, __FILE__, $sql);
				}		

				$post_id_sql = '';
				while ( $row = $db->sql_fetchrow($result) )
				{
					$post_id_sql .= ( ( $post_id_sql != '' ) ? ', ' : '' ) . $row['post_id'];
				}
				$db->sql_freeresult($result);
	
				//
				// Got all required info so go ahead and start deleting everything
				//
				$sql = "DELETE 
					FROM " . TOPICS_TABLE . " 
					WHERE topic_id = $topic 
						OR topic_moved_id = $topic";
				if ( !$db->sql_query($sql, BEGIN_TRANSACTION) )
				{
					message_die(GENERAL_ERROR, 'Could not delete topics', '', __LINE__, __FILE__, $sql);
				}	

				if ( $post_id_sql != '' )
				{
					$sql = "DELETE 
						FROM " . POSTS_TABLE . " 
						WHERE post_id = $topic";
					if ( !$db->sql_query($sql) )
					{
						message_die(GENERAL_ERROR, 'Could not delete posts', '', __LINE__, __FILE__, $sql);
					}
	
					$sql = "DELETE 
						FROM " . POSTS_TEXT_TABLE . " 
						WHERE post_id = $topic";
					if ( !$db->sql_query($sql) )
					{
						message_die(GENERAL_ERROR, 'Could not delete posts text', '', __LINE__, __FILE__, $sql);
					}
	
					remove_search_post($post_id_sql);
				}

				$sql = "DELETE 
					FROM " . TOPICS_WATCH_TABLE . " 
					WHERE topic_id = $topic";
				if ( !$db->sql_query($sql, END_TRANSACTION) )
				{
					message_die(GENERAL_ERROR, 'Could not delete watched post list', '', __LINE__, __FILE__, $sql);
				}
				sync('forum', $forum_id);
			}
	
			$sql = "DELETE FROM  " . KB_ARTICLES_TABLE . " 
				WHERE article_id = " . $article_id;
			if ( !($result = $db->sql_query($sql)) )
			{
  				message_die(GENERAL_ERROR, "Could not delete article data", '', __LINE__, __FILE__, $sql);
			}	
		
			$message = $lang['Article_deleted'] . '<br /><br />' . sprintf($lang['Click_return_article_manager'], '<a href="' . append_sid("admin_kb_art.$phpEx") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.$phpEx?pane=right") . '">', '</a>');
	
			message_die(GENERAL_MESSAGE, $message);
		}
		else
		{
		 	$message = $lang['Confirm_art_delete'] . '<br /><br />' . sprintf($lang['Confirm_art_delete_yes'], '<a href="' . append_sid("admin_kb_art.$phpEx?mode=delete&amp;c=yes&amp;a=" . $HTTP_GET_VARS['a']) . '">', '</a>') . '<br /><br />' . sprintf($lang['Confirm_art_delete_no'], '<a href="' . append_sid("admin_kb_art.$phpEx") . '">', '</a>');

			message_die(GENERAL_MESSAGE, $message);
		}
		break;
	
	default:
		//
		// Generate page
		//
		$template->set_filenames(array(
		    'body' => 'admin/kb_art_body.tpl')
		);
		
		$template->assign_vars(array(
		    'L_ARTICLE' => $lang['Article'],
			'L_ARTICLE_CAT' => $lang['Category'],
			'L_ARTICLE_TYPE' => $lang['Article_type'],
			'L_ARTICLE_AUTHOR' => $lang['Author'],
			'L_ACTION' => $lang['Action'],
			'L_APPROVED' => $lang['Art_approved'],
			'L_NOT_APPROVED' => $lang['Art_not_approved'],
			'L_EDITED' => $lang['Art_edit'],
			'L_KB_ART_TITLE' => $lang['Art_man'],
			'L_KB_ART_DESCRIPTION' => $lang['KB_art_description'])
		);
	
		//edited articles
		get_kb_articles('', 2, 'editrow');
		//need to be approved
		get_kb_articles('', 0, 'notrow');
		//Articles that are approved
		get_kb_articles('', 1, 'approverow');
		break;
}

$template->pparse('body');

include('./page_footer_admin.'.$phpEx);

?>