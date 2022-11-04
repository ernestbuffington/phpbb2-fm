<?php
/** 
*
* @package includes
* @version $Id: functions_kb.php,v 1.0.1 2003/01/13 18:54:16 psotfx Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

if (!defined('IN_PHPBB'))
{
	die('Hacking attempt');
}

//
// get_quick_stats();
// gets number of articles
//
function get_quick_stats()
{
    global $db, $template, $lang;
	
	$sql = "SELECT * 
		FROM " . KB_TYPES_TABLE . " 
		ORDER BY type";
	if ( !($result = $db->sql_query($sql)) )
	{
	    message_die(GENERAL_ERROR, "Error getting quick stats", '', __LINE__, __FILE__, $sql);
	}
	
	$template->assign_vars(array(
	    'L_QUICK_STATS' => $lang['Quick_stats'])
	);
	
	while( $type = $db->sql_fetchrow($result) )
	{
    	$type_id = $type['id'];
		$type_name = $type['type'];
	
		$sql = "SELECT COUNT(article_id) 
			FROM " . KB_ARTICLES_TABLE . " 
			WHERE article_type = " . $type_id . "
				AND approved = 1";
		if ( !($count = $db->sql_query($sql)) )
		{
	        message_die(GENERAL_ERROR, 'Could not obtain quick stats', '', __LINE__, __FILE__, $sql);
		}

		$number_count = $i = 0;
		$number = array();
		while ( $number = $db->sql_fetchrow($count) )
		{
		    $number_count = $number_count + $number[$i];
		    $i++;
	  	}
		$db->sql_freeresult($count);

   		$template->assign_block_vars('quick_stats', array(
			'Q_TYPE_NAME' => $type_name,
			'Q_TYPE_AMOUNT' => $number_count)
		);
	}
	
	return $template;
}
 
 
//
// get author of article
//
function get_kb_author($id, $level = TRUE)
{
    global $db, $theme;
 
    $sql = "SELECT username, user_level  
    	FROM " . USERS_TABLE . " 
      	WHERE user_id = $id";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain author data.', '', __LINE__, __FILE__, $sql);
	}
	
	if ( $row = $db->sql_fetchrow($result) )
	{	
		if ($level)
		{
		  	$name = username_level_color($row['username'], $row['user_level'], $row['user_id']);
		}
		else
		{
		  	$name = '[color=#' . $theme['adminfontcolor'] . '][b]' . $row['username'] . '[/b][/color]';
		}
	}
	else
	{
	      $name = '';
	}
	  
	return $name;
}


//
// get type of article
//
function get_kb_type($id)
{
    global $db;
	
    $sql = "SELECT type  
       	FROM " . KB_TYPES_TABLE . " 
      	WHERE id = $id";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, "Could not obtain article type data", '', __LINE__, __FILE__, $sql);
	}

	if ( $row = $db->sql_fetchrow($result) )
	{	
		$type = $row['type'];
	}
	$db->sql_freeresult($result);
	 
	return $type;
}

//
// get category for article
//
function get_kb_cat($id)
{
    global $db;
	
	$sql = "SELECT *  
      	FROM " . KB_CATEGORIES_TABLE . " 
		WHERE category_id = $id";
	 if ( !($result = $db->sql_query($sql)) )
	 {
		message_die(GENERAL_ERROR, "Could not obtain category data", '', __LINE__, __FILE__, $sql);
	 }
	 
	 $row = $db->sql_fetchrow($result);
	 
	 return $row;
}

//
// get_kb_nav($cat_id)
// gets parents for category
//
function get_kb_nav($parent)
{
    global $db, $phpbb_root_path, $phpEx;
	global $path_kb, $path_kb_array;
	
	$sql = "SELECT * 
		FROM " . KB_CATEGORIES_TABLE . " 
		WHERE category_id = " . $parent;
	
	if ( !($result = $db->sql_query($sql)) )
	 {
		message_die(GENERAL_ERROR, "Could not obtain category data", '', __LINE__, __FILE__, $sql);
	 }
	 
	 $row = $db->sql_fetchrow($result);
	
	 $temp_url = append_sid($phpbb_root_path . 'kb.'.$phpEx.'?mode=cat&amp;cat='. $row['category_id']);		   
	 $path_kb_array[] .= '-> <a href="' . $temp_url . '" class="nav">' . $row['category_name'] . '</a> ';
	 
	 if ( $row['parent'] != '0' )
	 {
	     get_kb_nav($row['parent']);
		 return;
	 }
	 
	 $path_kb_array2 = array_reverse($path_kb_array);
	 
	 $i = 0;
	 while($i <= sizeof($path_kb_array2))
	 {
		 $path_kb .= $path_kb_array2[$i];
		 $i++;
	 }
	 
	 return;
}

//
// get articles for the category
//
function get_kb_articles($id = false, $approve, $block)
{
    global $db, $template, $images, $phpEx, $phpbb_root_path, $board_config, $lang;
	
	$sql = "SELECT * 
		FROM " . KB_ARTICLES_TABLE . " 
		WHERE";
	if ( $id )
	{
	    $sql .= " article_category_id = " . $id . " AND";
	}
	$sql .= " approved = " . $approve;
	
	if ( defined('IN_ADMIN') )
	{
	    $sql .= " ORDER BY article_id";
	}
	else
	{
	    $sql .= " ORDER BY article_date DESC";
	}
	if ( !($article_result = $db->sql_query($sql)) )
	{
	   message_die(GENERAL_ERROR, "Could not obtain article data", '', __LINE__, __FILE__, $sql);
	}

	while($article = $db->sql_fetchrow($article_result))
	{	
		$article_description = $article['article_description'];
		$article_cat = $article['article_category_id'];
		
	   	//type
	   	$type_id = $article['article_type'];	   
	   	$article_type = get_kb_type($type_id);		
		
	   	$article_date = create_date($board_config['default_dateformat'], $article['article_date'], $board_config['board_timezone']);
	   	$article_id = $article['article_id'];
		
	   	// author information
	   	$author_id = $article['article_author_id'];
	   	if ( $author_id == -1 )
	   	{
	   	    $author = ( $username != '' ) ? $lang['Guest'] : $article['username'];
	   	}
	   	else
	   	{
	    	$author_name = get_kb_author($author_id);
	   
	       	$temp_url = append_sid($phpbb_root_path . "profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=$author_id");
	       	$author = '<a href="' . $temp_url . '" class="genmed">' . $author_name . '</a> <a href="' . append_sid('kb.'.$phpEx.'?mode=article&amp;k=' . $article_id) . '"><img src="' . $phpbb_root_path . $images['icon_latest_reply'] . '" alt="" title="" /></a>';
	   	}
		
	   	$views = $article['views'];
		
	   	$article_title = $article['article_title'];
	   	$temp_url = append_sid($phpbb_root_path . 'kb.'.$phpEx.'?mode=article&amp;k=' . $article_id);
	   	$article = '<a href="' . $temp_url . '" class="topictitle">' . $article_title . '</a>';
	   
	   	$approve = '';
	   	$delete = '';
	   	$category_name = '';   
	   	
	   	if ( defined('IN_ADMIN') )
	   	{
	   	    $category = get_kb_cat($article_cat);
			$category_name = $category['category_name'];

		   	if ( $block != 'approverow' )
		   	{
		   		//approve
		   		$temp_url = append_sid($phpbb_root_path . "admin/admin_kb_art.$phpEx?mode=approve&amp;a=$article_id");
		   	   	$approve = '<a href="' . $temp_url . '"><img src="' . $phpbb_root_path . $images['acp_disable'] . '" alt="' . $lang['Approve'] . '" title="' . $lang['Approve'] . '" /></a>';
		   	}
		   	else
		   	{		   
			   	//approve
			   	$temp_url = append_sid($phpbb_root_path . "admin/admin_kb_art.$phpEx?mode=unapprove&amp;a=$article_id");
			   	$approve = '<a href="' . $temp_url . '"><img src="' . $phpbb_root_path . $images['acp_enable'] . '" alt="' . $lang['Unapprove'] . '" title="' . $lang['Unapprove'] . '" /></a>';
		   	}
	
		   	//delete
		   	$temp_url = append_sid($phpbb_root_path . "admin/admin_kb_art.$phpEx?mode=delete&amp;a=$article_id");
		   	$delete = '<a href="' . $temp_url . '"><img src="' . $phpbb_root_path . $images['acp_delete'] . '" alt="' . $lang['Delete'] . '" title="' . $lang['Delete'] . '" /></a>';
	  	}
	   
	   	$template->assign_block_vars($block, array(
			'ARTICLE' => $article,
			'ARTICLE_DESCRIPTION' => $article_description,
			'ARTICLE_TYPE' => $article_type,
			'ARTICLE_DATE' => $article_date,
			'ARTICLE_AUTHOR' => $author,
			'CATEGORY' => $category_name,
			'ART_VIEWS' => $views,
			
			'U_APPROVE' => $approve,
			'U_DELETE' => $delete)
		);
	}
	$db->sql_freeresult($article_result);

	
	return $template;
}

//
// update number of articles in a category
//
function update_kb_number($id, $change)
{
    global $db;
	
	// update number of articles in category if article has been approved
	$sql = "SELECT * 
		FROM " . KB_CATEGORIES_TABLE . " 
		WHERE category_id = " . $id;
	if ( !($results = $db->sql_query($sql)) )
	{
   	  	message_die(GENERAL_ERROR, 'Could not obtain approved article count.', '', __LINE__, __FILE__, $sql);
	}
	
	if ( $cat = $db->sql_fetchrow($results) )
	{ 
	    $new_number = $cat['number_articles'] . $change;
	}	
	$db->sql_freeresult($results);
	
	$sql = "UPDATE " . KB_CATEGORIES_TABLE . " 
		SET number_articles = " . $new_number . " 
		WHERE category_id = " . $id;
	if ( !($result = $db->sql_query($sql)) )
	{
   	    message_die(GENERAL_ERROR, "Could not update category data", '', __LINE__, __FILE__, $sql);
	}
	
	if ($cat['parent'] != 0)
	{
	    update_kb_number($cat['parent'], $change);
	}
	  
	return;
}

//
// email admin
//
function email_kb_admin($action)
{
    global $lang, $emailer, $board_config, $db, $phpbb_root_path, $phpEx;
	
	if ( $action == 2 )
	{	    
		$email_body = $lang['Email_body'];
	   
       	include($phpbb_root_path . 'includes/emailer.'.$phpEx); 
	   	$emailer = new emailer($board_config['smtp_delivery']); 

	   	$email_headers = 'From: ' . $board_config['board_email'] . "\nReturn-Path: " . $board_config['board_email'] . "\n"; 

	   	$emailer->email_address($board_config['board_email']); 
	   	$emailer->set_subject($lang['New_article']); 
	   	$emailer->extra_headers($email_headers); 
	   	$emailer->msg = $email_body; 

	   	$emailer->send(); 
	   	$emailer->reset();

	}
	else if ( $action == 1 )
	{
		$sql = "UPDATE " . USERS_TABLE . " 
			SET user_new_privmsg = '1', user_last_privmsg = '9999999999'
			WHERE user_id = " . $board_config['kb_admin_id'];
		if ( !($result = $db->sql_query($sql)) )
        {
			message_die(GENERAL_ERROR, 'Could not update users table', '', __LINE__, __FILE__, $sql);
        }

		$user_id = $board_config['kb_admin_id'];		   
        $new_article_subject = $lang['New_article'];
        $new_article = $lang['Email_body'];
        $privmsgs_date = date("U");
		   
        $sql = "INSERT INTO " . PRIVMSGS_TABLE . " (privmsgs_type, privmsgs_subject, privmsgs_from_userid, privmsgs_to_userid, privmsgs_date, privmsgs_enable_html, privmsgs_enable_bbcode, privmsgs_enable_smilies, privmsgs_attach_sig) 
        	VALUES ('0', '" . $new_article_subject . "', '" . $user_id . "', '" . $user_id . "', '" . $privmsgs_date . "', '0', '1', '1', '0')";
        if ( !$db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not insert private message sent info', '', __LINE__, __FILE__, $sql);
		}
		$privmsg_sent_id = $db->sql_nextid();
		$privmsgs_text = $lang['register_pm_subject'];

        $sql = "INSERT INTO " . PRIVMSGS_TEXT_TABLE . " (privmsgs_text_id, privmsgs_text) 
        	VALUES ($privmsg_sent_id, '" . $new_article . "')";
        if ( !$db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not insert private message sent text', '', __LINE__, __FILE__, $sql);
		}
	}
	return;
}

//
// get categories for index
//
function get_kb_cat_index($parent = 0)
{
    global $db, $template, $phpbb_root_path, $phpEx;
	
	$sql = "SELECT *  
    	FROM " . KB_CATEGORIES_TABLE . " 
		WHERE parent = " . $parent . " 
		ORDER BY cat_order";	
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, "Could not obtain category data", '', __LINE__, __FILE__, $sql);
	}
	 
	while ( $category = $db->sql_fetchrow($result) )
	{		
		$template->assign_block_vars('catrow', array(
			'U_CATEGORY' => append_sid($phpbb_root_path . "kb.$phpEx?mode=cat&amp;cat=" . $category['category_id']),
			'CATEGORY' => $category['category_name'],
			'CAT_DESCRIPTION' => $category['category_details'],
			'CAT_ARTICLES' => $category['number_articles'])
		);
	}	 
	$db->sql_freeresult($result);

	return $template;
}

//
// get sub categories for articles
//
function get_kb_cat_subs($parent)
{
    global $db, $template, $phpbb_root_path, $phpEx;
	
	$sql = "SELECT *  
    	FROM " . KB_CATEGORIES_TABLE . " 
		WHERE parent = " . $parent . " 
		ORDER BY cat_order";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, "Could not obtain sub-category data", '', __LINE__, __FILE__, $sql);
	}
	if ( !($result2 = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, "Could not obtain sub-category data", '', __LINE__, __FILE__, $sql);
	}
 
	if ( $category2 = $db->sql_fetchrow($result2) )
	{		
		if ( $category2['category_details'] != '' )
	 	{
	        $template->assign_block_vars('switch_sub_cats', array());
	    }
	}
	
	while ( $category = $db->sql_fetchrow($result) )
	{		
		$template->assign_block_vars('switch_sub_cats.catrow', array(
			'U_CATEGORY' => append_sid($phpbb_root_path . "kb.$phpEx?mode=cat&amp;cat=" . $category['category_id']),
			'CATEGORY' => $category['category_name'],
			'CAT_DESCRIPTION' => $category['category_details'],
			'CAT_ARTICLES' => $category['number_articles'])
		);
	}	
	$db->sql_freeresult($result);

	return $template;
}

//
// get category list for adding and editing articles
//
function get_kb_cat_list($sel_id)
{
    global $db, $template;
	
	$sql = "SELECT *  
    	FROM " . KB_CATEGORIES_TABLE;
	if ( !$row = $db->sql_query($sql) )
	{
	     message_die(GENERAL_ERROR, 'Could not obtain category information.', '', __LINE__, __FILE__, $sql);
	}

	while ( $row = $db->sql_fetchrow($result) )
	{	
		$selected = ( $row['category_id'] == $sel_id ) ? ' selected="selected"' : '';

		$category = '<option value="' . $row['category_id'] . '"' . $selected . '>' . $row['category_name'] . '</option>';
		
		$template->assign_block_vars('switch_edit.categories', array(
			'CATEGORY' => $category)
		);
	}
	$db->sql_freeresult($result);

	return;
}

//
// get type list for adding and editing articles
//
function get_kb_type_list($sel_id)
{
    global $db, $template;
	
	$sql = "SELECT *  
    	FROM " . KB_TYPES_TABLE . "
    	ORDER BY id";
	if ( !($row = $db->sql_query($sql)) )
	{
	   message_die(GENERAL_ERROR, "Could not obtain category information", '', __LINE__, __FILE__, $sql);
	}
	
	while ( $row = $db->sql_fetchrow($result) )
	{	
		$selected = ( $row['id'] == $sel_id ) ? ' selected="selected"' : '';
		$type = '<option value="' . $row['id'] . '"' . $selected . '>' . $row['type'] . '</option>';
		   
		$template->assign_block_vars('types', array(
		    'TYPE' => $type)
		);
	}
	$db->sql_freeresult($result);

	return;
}

//
// insert post for site updates
// By netclectic - Adrian Cockburn
//
function insert_post($message, $subject, $forum_id, $user_id, $user_name, $user_attach_sig, $topic_id = NULL, $topic_type = POST_NORMAL, $do_notification = false, $notify_user = false, $current_time = 0, $error_die_function = '', $html_on = 0, $bbcode_on = 1, $smilies_on = 1)
{
    global $db, $board_config, $user_ip;

    // initialise some variables
    $topic_vote = 0; 
    $poll_title = $poll_options = $poll_length = '';
    $mode = 'reply'; 

    $bbcode_uid = ($bbcode_on) ? make_bbcode_uid() : ''; 
    $error_die_function = ($error_die_function == '') ? "message_die" : $error_die_function;
    $current_time = ($current_time == 0) ? time() : $current_time;
    
    // parse the message and the subject 
    $message = str_replace("\'", "''", prepare_message(trim($message), $html_on, $bbcode_on, $smilies_on, $bbcode_uid)); 
    $subject = str_replace("\'", "''", trim($subject)); 
    $username = str_replace("\'", "''", trim(strip_tags($user_name))); 
    
    // if this is a new topic then insert the topic details
    if ( is_null($topic_id) )
    {
        $mode = 'newtopic'; 
        $sql = "INSERT INTO " . TOPICS_TABLE . " (topic_title, topic_poster, topic_time, forum_id, topic_status, topic_type, topic_vote) 
        	VALUES ('$subject', '" . $user_id . "', $current_time, $forum_id, " . TOPIC_UNLOCKED . ", $topic_type, $topic_vote)";
        if ( !$db->sql_query($sql, BEGIN_TRANSACTION) )
        {
            $error_die_function(GENERAL_ERROR, 'Error in posting', '', __LINE__, __FILE__, $sql);
        }
        $topic_id = $db->sql_nextid();
    }

    // insert the post details using the topic id
    $sql = "INSERT INTO " . POSTS_TABLE . " (topic_id, forum_id, poster_id, post_username, post_time, poster_ip, enable_bbcode, enable_html, enable_smilies, enable_sig) 
    	VALUES ($topic_id, $forum_id, '" . $user_id . "', '$username', $current_time, '$user_ip', $bbcode_on, $html_on, $smilies_on, $user_attach_sig)";
    if ( !$db->sql_query($sql, BEGIN_TRANSACTION) )
    {
        $error_die_function(GENERAL_ERROR, 'Error in posting', '', __LINE__, __FILE__, $sql);
    }
    $post_id = $db->sql_nextid();
    
    // insert the actual post text for our new post
    $sql = "INSERT INTO " . POSTS_TEXT_TABLE . " (post_id, post_subject, bbcode_uid, post_text) 	
    	VALUES ($post_id, '$subject', '$bbcode_uid', '" . str_replace("'", "\'", $message) . "')";
    if ( !$db->sql_query($sql, BEGIN_TRANSACTION) )
    {
        $error_die_function(GENERAL_ERROR, 'Error in posting', '', __LINE__, __FILE__, $sql);
    }
    
    // update the post counts etc.
    $newpostsql = ($mode == 'newtopic') ? ',forum_topics = forum_topics + 1' : '';
    $sql = "UPDATE " . FORUMS_TABLE . " 
    	SET forum_posts = forum_posts + 1, forum_last_post_id = $post_id$newpostsql 	
		WHERE forum_id = $forum_id";
    if ( !$db->sql_query($sql, BEGIN_TRANSACTION) )
    {
        $error_die_function(GENERAL_ERROR, 'Error in posting', '', __LINE__, __FILE__, $sql);
    }
    
    // update the first / last post ids for the topic
    $first_post_sql = ( $mode == 'newtopic' ) ? ", topic_first_post_id = $post_id  " : ' , topic_replies=topic_replies+1'; 
    $sql = "UPDATE " . TOPICS_TABLE . " 
    	SET topic_last_post_id = $post_id$first_post_sql
        WHERE topic_id = $topic_id";
    if ( !$db->sql_query($sql, BEGIN_TRANSACTION) )
    {
        $error_die_function(GENERAL_ERROR, 'Error in posting', '', __LINE__, __FILE__, $sql);
    }
    
    // update the user's post count and commit the transaction
    $sql = "UPDATE " . USERS_TABLE . " 
    	SET user_posts = user_posts + 1
		WHERE user_id = $user_id";
    if ( !$db->sql_query($sql, END_TRANSACTION) )
    {
        $error_die_function(GENERAL_ERROR, 'Error in posting', '', __LINE__, __FILE__, $sql);
    }
    
    // add the search words for our new post
    add_search_words('', $post_id, stripslashes($message), stripslashes($subject));
    
    // do we need to do user notification
    if ( ($mode == 'reply') && $do_notification )
    {
        $post_data = array();
        user_notification($mode, $post_data, $subject, $forum_id, $topic_id, $post_id, $notify_user);
    }
    
    // if all is well then return the id of our new post
    return array('post_id'=>$post_id, 'topic_id'=>$topic_id);
}

function add_kb_words($post_id, $post_text, $post_title = '')
{
	global $db, $phpbb_root_path, $board_config, $lang;

	$stopword_array = @file($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . "/search_stopwords.txt"); 
	$synonym_array = @file($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . "/search_synonyms.txt"); 

	// Fix for PHP 5.0.5 +
	$entry = clean_words('post', $post_text, $stopword_array, $synonym_array);
	$search_raw_words['text'] = split_words($entry);
	$search_raw_words['title'] = split_words($entry); 

//	$search_raw_words = array();
//	$search_raw_words['text'] = split_words(clean_words('post', $post_text, $stopword_array, $synonym_array));
//	$search_raw_words['title'] = split_words(clean_words('post', $post_title, $stopword_array, $synonym_array));

	$word = array();
	$word_insert_sql = array();
	while ( list($word_in, $search_matches) = @each($search_raw_words) )
	{
		$word_insert_sql[$word_in] = '';
		if ( !empty($search_matches) )
		{
			for ($i = 0; $i < count($search_matches); $i++)
			{ 
				$search_matches[$i] = trim($search_matches[$i]);

				if( $search_matches[$i] != '' ) 
				{
					$word[] = $search_matches[$i];
					if ( !strstr($word_insert_sql[$word_in], "'" . $search_matches[$i] . "'") )
					{
						$word_insert_sql[$word_in] .= ( $word_insert_sql[$word_in] != "" ) ? ", '" . $search_matches[$i] . "'" : "'" . $search_matches[$i] . "'";
					}
				} 
			}
		}
	}

	if ( count($word) )
	{
		sort($word);

		$prev_word = '';
		$word_text_sql = '';
		$temp_word = array();
		for($i = 0; $i < count($word); $i++)
		{
			if ( $word[$i] != $prev_word )
			{
				$temp_word[] = $word[$i];
				$word_text_sql .= ( ( $word_text_sql != '' ) ? ', ' : '' ) . "'" . $word[$i] . "'";
			}
			$prev_word = $word[$i];
		}
		$word = $temp_word;

		$check_words = array();
		switch( SQL_LAYER )
		{
			case 'postgresql':
			case 'msaccess':
			case 'mssql-odbc':
			case 'oracle':
			case 'db2':
				$sql = "SELECT word_id, word_text     
					FROM " . SEARCH_WORD_TABLE . " 
					WHERE word_text IN ($word_text_sql)";
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'Could not select words', '', __LINE__, __FILE__, $sql);
				}

				while ( $row = $db->sql_fetchrow($result) )
				{
					$check_words[$row['word_text']] = $row['word_id'];
				}
				$db->sql_freeresult($result);

				break;
		}

		$value_sql = '';
		$match_word = array();
		for ($i = 0; $i < count($word); $i++)
		{ 
			$new_match = true;
			if ( isset($check_words[$word[$i]]) )
			{
				$new_match = false;
			}

			if ( $new_match )
			{
				switch( SQL_LAYER )
				{
					case 'mysql':
					case 'mysql4':
						$value_sql .= ( ( $value_sql != '' ) ? ', ' : '' ) . '(\'' . $word[$i] . '\', 0)';
						break;
					case 'mssql':
						$value_sql .= ( ( $value_sql != '' ) ? ' UNION ALL ' : '' ) . "SELECT '" . $word[$i] . "', 0";
						break;
					default:
						$sql = "INSERT INTO " . KB_WORD_TABLE . " (word_text, word_common) 
							VALUES ('" . $word[$i] . "', 0)"; 
						if( !$db->sql_query($sql) )
						{
							message_die(GENERAL_ERROR, 'Could not insert new word', '', __LINE__, __FILE__, $sql);
						}
						break;
				}
			}
		}

		if ( $value_sql != '' )
		{
			$sql = "INSERT IGNORE INTO " . KB_WORD_TABLE . " (word_text, word_common) 
				VALUES $value_sql"; 
			if ( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not insert new word', '', __LINE__, __FILE__, $sql);
			}
		}
	}

	while( list($word_in, $match_sql) = @each($word_insert_sql) )
	{
		$title_match = ( $word_in == 'title' ) ? 1 : 0;

		if ( $match_sql != '' )
		{
			$sql = "INSERT INTO " . KB_MATCH_TABLE . " (article_id, word_id, title_match) 
				SELECT $post_id, word_id, $title_match  
					FROM " . KB_WORD_TABLE . " 
					WHERE word_text IN ($match_sql)"; 
			if ( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not insert new word matches', '', __LINE__, __FILE__, $sql);
			}
		}
	}

	if ($mode == 'single')
	{
		remove_common('single', 4/10, $word);
	}

	return;
}

?>