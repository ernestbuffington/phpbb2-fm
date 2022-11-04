<?php
/** 
*
* @package phpBB2
* @version $Id: kb.php,v 2.0.0 2003/03/31 00:06:33 psotfx Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);
include($phpbb_root_path . 'includes/bbcode.'.$phpEx);
include($phpbb_root_path . 'includes/functions_post.'.$phpEx);
include($phpbb_root_path . 'includes/functions_kb.'.$phpEx);
include($phpbb_root_path . 'includes/functions_search.'.$phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_KB);
init_userprefs($userdata);
//
// End session management
//

//
// Include language file
//
$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_kb.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_kb.' . $phpEx);

$show_new = true;

//options
if ( !$board_config['allow_html'] )
{
   $html_on = 0;
}
else
{
 	$html_on = ( $submit || $refresh ) ? ( ( !empty($HTTP_POST_VARS['disable_html']) ) ? 0 : TRUE ) : ( ( $userdata['user_id'] == ANONYMOUS ) ? $board_config['allow_html'] : $userdata['user_allowhtml'] );
}

if ( !$board_config['allow_bbcode'] )
{
   $bbcode_on = 0;
}
else
{
 	$bbcode_on = ( $submit || $refresh ) ? ( ( !empty($HTTP_POST_VARS['disable_bbcode']) ) ? 0 : TRUE ) : ( ( $userdata['user_id'] == ANONYMOUS ) ? $board_config['allow_bbcode'] : $userdata['user_allowbbcode'] );
}

if ( !$board_config['allow_smilies'] )
{
   $smilies_on = 0;
}
else
{
 	$smilies_on = ( $submit || $refresh ) ? ( ( !empty($HTTP_POST_VARS['disable_smilies']) ) ? 0 : TRUE ) : ( ( $userdata['user_id'] == ANONYMOUS ) ? $board_config['allow_smilies'] : $userdata['user_allowsmile'] );
}


//
//page number
//
if ( isset($HTTP_POST_VARS['page_num']) || isset($HTTP_GET_VARS['page_num']) )
{
	$page_num = ( isset($HTTP_POST_VARS['page_num']) ) ? intval($HTTP_POST_VARS['page_num']) : intval($HTTP_GET_VARS['page_num']);
	$page_num = $page_num - 1;
}
else
{
    $page_num = 0;
}


//
// Define initial vars
//
if ( isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']) )
{
	$mode = ( isset($HTTP_POST_VARS['mode']) ) ? htmlspecialchars($HTTP_POST_VARS['mode']) : htmlspecialchars($HTTP_GET_VARS['mode']);
}
else
{
	if ( $article )
	{
		$mode = 'article';
	}
	else if ( $cat )
	{
		$mode = 'cat';
	}
	else if ( $add )
	{
		$mode = 'add';
	}
	else if ( $search )
	{
		$mode = 'search';
	}
	else if ( $edit )
	{
		$mode = 'edit';
	}
	else
	{
		$mode = '';
	}
}

$is_admin = ( $userdata['user_level'] == ADMIN && $userdata['session_logged_in'] ) ? TRUE : 0;

switch ($mode)
{
	case 'article':
		$article_id = intval($HTTP_GET_VARS['k']);
	
		$sql = "SELECT *
			FROM " . KB_ARTICLES_TABLE . "
			WHERE article_id = $article_id";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, "Could not obtain article data", '', __LINE__, __FILE__, $sql);
		}
	
		if ( $row = $db->sql_fetchrow($result) )
		{
			$article_title = $row['article_title'];
	   
	   		$article_category_id = $row['article_category_id'];	
	   		$category = get_kb_cat($article_category_id);
	   		$article_category_name = $category['category_name'];

			$temp_url = append_sid($phpbb_root_path . "kb.$phpEx?mode=cat&amp;cat=$article_category_id");
	   		$category = '<a href="' . $temp_url . '" class="topictitle">' . $article_category_name . '</a>';
	
			$date = create_date($board_config['default_dateformat'], $row['article_date'], $board_config['board_timezone']);
	
	   		// author information
	   		$author_id = $row['article_author_id'];	

	   		if ( $author_id == 0 )
	   		{
	    		$author = ( $username != '' ) ? $lang['Guest'] : $row['username'];
	   		}
	   		else
	   		{
	       		$author_name = get_kb_author($author_id);
	   
	       		$temp_url = append_sid($phpbb_root_path . "profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=$author_id");
	       		$author = '<a href="' . $temp_url . '">' . $author_name . '</a>';
	   		}
	
	   		$art_pages = explode('[page]', $row['article_body']);
	   		$article = trim($art_pages[$page_num]);
	   		$description = $row['article_description'];
	   
	   		$type_id = $row['article_type'];
	   		$type = get_kb_type($type_id);
	   		$topic_id = $row['topic_id'];
	   
	   		$new_views = $row['views'] + 1;
	   		$views = '<b>' . $lang['Views'] . ':</b> ' . $new_views;
		}	
	
		if ( $page_num == 0 )
		{	
	   		$sql = "UPDATE " . KB_ARTICLES_TABLE . " 
	   			SET views = '" . $new_views . "'
		    	WHERE article_id = " . $article_id;
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, "Could not update article's views", '', __LINE__, __FILE__, $sql);
			}
  		}

		//
		// Define censored word matches
		//
		if ( !$board_config['allow_swearywords'] )
		{
			$orig_word = $replacement_word = array();
			obtain_word_list($orig_word, $replacement_word);
		}
		else if ( !$userdata['user_allowswearywords'] )
		{	
			$orig_word = $replacement_word = array();
			obtain_word_list($orig_word, $replacement_word);
		}
	
		//
		// Was a highlight request part of the URI?
		//
		$highlight_match = $highlight = '';
		if (isset($HTTP_GET_VARS['highlight']))
		{
		    // Split words and phrases
			$words = explode(' ', trim(htmlspecialchars(urldecode($HTTP_GET_VARS['highlight']))));

			for($i = 0; $i < sizeof($words); $i++)
			{
			    if (trim($words[$i]) != '')
				{
				    $highlight_match .= (($highlight_match != '') ? '|' : '') . str_replace('*', '\w*', preg_quote($words[$i], '#'));
				}
	    	}
			unset($words);

			$highlight = urlencode($HTTP_GET_VARS['highlight']);
		}	
	
		if ( !$board_config['allow_html'] ) 
    	{ 
			$article = preg_replace('#(<)([\/]?.*?)(>)#is', "&lt;\\2&gt;", $article); 
		}
	
		//
		// Parse message
		//
		$bbcode_uid = $row['bbcode_uid'];
		
		if ( $bbcode_uid != '' )
		{
			$article = ( $board_config['allow_bbcode'] ) ? bbencode_second_pass($article, $bbcode_uid) : preg_replace("/\:$bbcode_uid/si", '', $article);
		}

		$article = make_clickable($article);

	 	//
	 	// ed2k link and add all
		//
		$article = make_addalled2k_link($article, $row['article_id']);

		//
		// Parse smilies
		//
		if ( $board_config['allow_smilies'] )
		{
			$article = smilies_pass($article, $board_config['kb_forum_id']);		
		}
		else
		{
			if( $board_config['smilie_removal1'] )
			{
				$article = smilies_code_removal($article);
			}
		}

		//
		// Highlight active words (primarily for search)
		//
		if ($highlight_match)
		{
			// This has been back-ported from 3.0 CVS
			$article = preg_replace('#(?!<.*)(?<!\w)(' . $highlight_match . ')(?!\w|[^<>]*>)#i', '<b style="color:#'.$theme['fontcolor4'].'">\1</b>', $article);
			$message = preg_replace('#(?!<.*)(?<!\w)(' . $highlight_match . ')(?!\w|[^<>]*>)#i', '<b style="color:#'.$theme['fontcolor4'].'">\1</b>', $message);
		}

		//
		// Replace naughty words
		//
		if( !empty($orig_word) )
		{
			$article_title = preg_replace($orig_word, $replacement_word, $article_title);
			$article = str_replace('\"', '"', substr(@preg_replace('#(\>(((?>([^><]+|(?R)))*)\<))#se', "@preg_replace(\$orig_word, \$replacement_word, '\\0')", '>' . $article . '<'), 1, -1));
		}

		//
		// Replace newlines (we use this rather than nl2br because
		// till recently it wasn't XHTML compliant)
		//
		$article = str_replace("\n", "\n<br />\n", $article);
		$article = word_wrap_pass($article);
		
		$page_title = $article_title;
		include($phpbb_root_path . 'includes/page_header.'.$phpEx);
		
		make_jumpbox('viewforum.'.$phpEx);
	
		//load header
		include ($phpbb_root_path ."includes/kb_header.".$phpEx);
	
		//edit links
		$edit_img = $edit = '';
		if ( ( $userdata['user_id'] == $author_id && $board_config['kb_allow_edit'] ) || ( $is_admin ) )
		{			
			$temp_url = append_sid("kb.$phpEx?mode=edit&amp;k=" . $article_id);
			$edit_img = '<a href="' . $temp_url . '"><img src="' . $images['icon_edit'] . '" alt="' . $lang['Edit_delete_post'] . '" title="' . $lang['Edit_delete_post'] . '" /></a>';
			$edit = '<a href="' . $temp_url . '">' . $lang['Edit_delete_post'] . '</a>';
		}
	
		//
		// Build page
		//	
 		$template->set_filenames(array(
			'body' => 'kb_article_body.tpl')
		);
		
		if ( !$article_title )
		{
		    $message = $lang['Article_not_exsist'] . '<br /><br />' . sprintf($lang['Click_return_kb'], '<a href="' . append_sid("kb.$phpEx") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid("index.$phpEx") . '">', '</a>');
	
	     	 message_die(GENERAL_MESSAGE, $message);
	  	}
	  	else
		{
			if ( $board_config['kb_comments'] && !$topic_id && $row['approved'] )	   
			{		  
				// choose a user
			  	$user_id = $board_config['kb_admin_id'];

				// initialise the userdata
		  		$sql = "SELECT * 
		  			FROM " . USERS_TABLE . " 
		  			WHERE user_id = $user_id";	  		
		  		if ( !($result = $db->sql_query($sql)) )
		  		{
	  	      		message_die(CRITICAL_ERROR, 'Could not obtain data from user table', '', __LINE__, __FILE__, $sql);
				}
		  		$user = $db->sql_fetchrow($result);
		  	
		  		$temp_url = "http://" . $board_config['server_name'] . $board_config['script_path'] . "kb.".$phpEx."?mode=article&k=".$article_id;
				$message = "[b]" . $lang['Category'] . ":[/b] " . $article_category_name . " (" . $article_category_id . ")\n";
		  		$message .= "[b]" . $lang['Article_type'] . ":[/b] " . $type . "\n\n";
		  		$message .= "[b]" . $lang['Article_title'] . ":[/b] " . addslashes($article_title) . "\n";
		  		$message .= "[b]" . $lang['Author'] . ":[/b] " . $author_name . "\n";
		  		$message .= "[b]" . $lang['Article_description'] . ":[/b] " . addslashes($row['article_description']) . "\n\n";
		  		$message .= "[b][url=" . $temp_url . "]" . $lang['Read_full_article'] . "[/url][/b]";
	
		  		$subject = '[KB] ' . addslashes($article_title);
		  		$forum_id = $board_config['kb_forum_id'];
	
		  		$topic_data = insert_post($message, $subject, $forum_id, $user['user_id'], $user['username'], $user['user_attachsig']);
		  
		  		$sql = "UPDATE " . KB_ARTICLES_TABLE . " 
		  			SET topic_id = " . $topic_data['topic_id'] . " 
		 	 		WHERE article_id = " . $article_id;		 
		  		if ( !($result = $db->sql_query($sql)) )
		  		{
   	   	  	  		message_die(GENERAL_ERROR, "Could not update article data", '', __LINE__, __FILE__, $sql);
				}
				
		  		$topic_id = $topic_data['topic_id'];
			}
			
			if ( $board_config['kb_comments'] )
			{       
	    		$sql = "SELECT topic_id, topic_replies 
			    	FROM " . TOPICS_TABLE . " 
			    	WHERE topic_id = " . $topic_id;
				if ( !($result = $db->sql_query($sql)) )
				{
		    		message_die(GENERAL_ERROR, 'Error getting comments', '', __LINE__, __FILE__, $sql);
				}	
				$topic = $db->sql_fetchrow($result4);	  
	   
	    		$temp_url = append_sid($phpbb_root_path . "viewtopic.php?t=" . $topic['topic_id']);
	    		$comments = '<b>' . $lang['Comments'] . ':</b><a href="' . $temp_url . '" class="gen"> ' . $topic['topic_replies'] . ' - ' . $lang['Post_comments'] . '</a>';
	
	    		$template->assign_block_vars('switch_comments', array());
			}
			else
			{
	    		$comments = '';
			}
	
			$path_kb = ' ';
			$path_kb_array = array();
			get_kb_nav($article_category_id);
	
			$template->assign_vars(array(
				'L_ARTICLE_DESCRIPTION' => $lang['Article_description'],
				'L_ARTICLE_DATE' => $lang['Date'],
				'L_ARTICLE_TYPE' => $lang['Article_type'],
				'L_ARTICLE_CATEGORY' => $lang['Category'],
				'L_ARTICLE_AUTHOR' => $lang['Author'],
				'L_GOTO_PAGE' => $lang['Goto_page'],
		
				'ARTICLE_TITLE' => $article_title,
				'ARTICLE_AUTHOR' => $author,
				'ARTICLE_CATEGORY' => $category,
				'ARTICLE_TEXT' => $article,
				'ARTICLE_DESCRIPTION' => $description,
				'ARTICLE_DATE' => $date,
				'ARTICLE_TYPE' => $type,
				'EDIT_IMG' => $edit_img,
				'EDIT' => $edit,
				'VIEWS' => $views,
		
				'PATH' => $path_kb,
		
				'COMMENTS' => $comments)
			);
	
			//
			//article pages
			//
			if ( sizeof($art_pages) > 1 )
			{
				$template->assign_block_vars('switch_pages', array());
		
				$i = 0;
				while ($i < sizeof($art_pages))
				{
					$page_number = $i + 1;
					
					if( $page_num != $i )
					{
					    $temp_url = append_sid($phbb_root_path . "kb.$phpEx?mode=article&k=$article_id&page_num=$page_number");
					    $page_link = '<a href="' . $temp_url . '" class="nav">' . $page_number . '</a>';
					}
					else
					{
			    		$page_link = $page_number;
					}
			
					if ( $i < sizeof($art_pages) - 1 )
					{
			    		$page_link .= ', ';
					}
					
					$template->assign_block_vars('switch_pages.pages', array(
			    		'PAGE_LINK' => $page_link)
					);
		    		$i++;
				}
			}
		}
		break;

	case 'cat':
		$category_id = intval($HTTP_GET_VARS['cat']);
		$category = get_kb_cat($category_id);		
		$category_name = $category['category_name'];
		
		$page_title = $category_name;
		include($phpbb_root_path . 'includes/page_header.'.$phpEx);
		
		make_jumpbox('viewforum.'.$phpEx, $category_id);
		
		//load header
		include ($phpbb_root_path ."includes/kb_header.".$phpEx);
		
		$template->set_filenames(array(
			'body' => 'kb_cat_body.tpl')
		);
	
		if ( !$category_name )
		{
	    	$message = $lang['Category_not_exsist'] . '<br /><br />' . sprintf($lang['Click_return_kb'], '<a href="' . append_sid("kb.$phpEx") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid("index.$phpEx") . '">', '</a>');

      		message_die(GENERAL_MESSAGE, $message);
		}
  		else
		{
			//get sub-cats
			get_kb_cat_subs($category_id);
	
			$path_kb = ' ';
			$path_kb_array = array();
			get_kb_nav($category_id);
	
			$template->assign_vars(array(
				'L_CATEGORY_NAME' => $category_name,
				'L_ARTICLE' => $lang['Article'],
				'L_ARTICLE_TYPE' => $lang['Article_type'],
				'L_ARTICLE_CATEGORY' => $lang['Category'],
				'L_ARTICLE_DATE' => $lang['Date'],
				'L_ARTICLE_AUTHOR' => $lang['Author'],
				'L_VIEWS' => $lang['Views'],
		
				'L_CATEGORY' => $lang['Category_sub'],
				'L_ARTICLES' => $lang['Articles'],
		
				'PATH' => $path_kb,
		
				'U_CAT' => append_sid($phpbb_root_path . 'kb.'.$phpEx.'?mode=cat&cat=' . $category_id))
			);
	
			get_kb_articles($category_id, '1', 'articlerow');
		}
		break;
	
	case 'add':
		$category_id = ( isset($HTTP_GET_VARS['cat']) ) ? intval($HTTP_GET_VARS['cat']) : intval($HTTP_POST_VARS['cat']);

  		//show article form
		if ( !$HTTP_POST_VARS['article_submit'] || $HTTP_POST_VARS['preview'] )
		{
			$page_title = $lang['Add_article'];
	   		include($phpbb_root_path . 'includes/page_header.'.$phpEx);
	
	   		make_jumpbox('viewforum.'.$phpEx);

	   		//
	   		// HTML toggle selection
	   		//
	   		if ( $board_config['allow_html'] )
	   		{
	   	  		$html_status = $lang['HTML_is_ON'];
	   		}
	   		else
	   		{
	   			$html_status = $lang['HTML_is_OFF'];
	   		}

	   		//
	   		// BBCode toggle selection
	   		//
	   		if ( $board_config['allow_bbcode'] )
	   		{
				$bbcode_status = $lang['BBCode_is_ON'];
	   		}
	   		else
	   		{
	   	  		$bbcode_status = $lang['BBCode_is_OFF'];
           	}

	   		//
	   		// Smilies toggle selection
	   		//
	   		if ( $board_config['allow_smilies'] )
	   		{
	   	  		$smilies_status = $lang['Smilies_are_ON'];
			}
	   		else
	   		{
				if( $board_config['smilie_removal1'] )
				{
					$smilies_status = $lang['Smilies_are_REMOVED'];
				}
				else
				{
					$smilies_status = $lang['Smilies_are_OFF'];
				}
			}
	   
	   		// Generate smilies listing for page output
	   		if( $board_config['allow_smilies'] )
			{
		   		generate_smilies('inline', PAGE_POSTING);
	   		}
	   		
	   		//load header
	   		include ($phpbb_root_path ."includes/kb_header.".$phpEx);
	
	   		if ( !$is_admin )
	   		{
	       		if ( !$userdata['session_logged_in'] && $board_config['kb_allow_anon'] != ALLOW_ANON )
	   	   		{
		       		$message = $lang['No_add'] . '<br /><br />' . sprintf($lang['Click_return_kb'], '<a href="' . append_sid("kb.$phpEx") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid("index.$phpEx") . '">', '</a>');

					message_die(GENERAL_MESSAGE, $message);
		   		}
	   		}
	
	   		// Set up page
	   		$template->set_filenames(array(
				'body' => 'kb_add_body.tpl')
	   		);
	   
	   		if ( !$userdata['session_logged_in'] && $board_config['kb_allow_anon'] == ALLOW_ANON )
	   		{
	       		$template->assign_block_vars('switch_name',array());
	   		}
	   		
			Multi_BBCode();	

			while( list($key, $font) = each($lang['font']) )
			{
				$template->assign_block_vars ('font_styles', array(
					'L_FONTNAME' => $font)
				);
			}

	   		$template->assign_vars(array(
				'L_ADD_ARTICLE' => $lang['Add_article'],
		  		'L_ARTICLE_TITLE' => $lang['Article_title'],
		  		'L_ARTICLE_DESCRIPTION' => $lang['Article_description'],
				'L_ITEMS_REQUIRED' => $lang['Items_required'],
				'L_ARTICLE_TEXT' => $lang['Article_text'],
				'L_ARTICLE_TYPE' => $lang['Article_type'],
		  		'L_PREVIEW' => $lang['Preview'],
		  		'L_SELECT' => $lang['Select'],		  
		  		'L_NAME' => $lang['Username'],
		  		'L_OPTIONS' => $lang['Options'],
				'L_SPELLCHECK' => $lang['Spellcheck'],
				'L_COPY_TO_CLIPBOARD' => $lang['Copy_to_clipboard'],
				'L_COPY_TO_CLIPBOARD_EXPLAIN' => $lang['Copy_to_clipboard_explain'],
				'L_HIGHLIGHT_TEXT' => $lang['Highlight_text'],
				'L_POST_SUBJECT' => $lang['Post_subject'],
		  		
		  		'S_ACTION' => append_sid('kb.'.$phpEx.'?mode=add'),
		  		'HTML_STATUS' => $html_status,
		  		'BBCODE_STATUS' => sprintf($bbcode_status, '<a href="' . append_sid('faq.'.$phpEx.'?mode=bbcode') . '" target="_phpbbcode">', '</a>'), 
		  		'SMILIES_STATUS' => $smilies_status,
		  		'S_HIDDEN_FIELDS' => '<input type="hidden" name="cat" value="' . $category_id . '">',
		  		'PREVIEW_DATE' => create_date($board_config['default_dateformat'], time(), $board_config['board_timezone']),
		
				'L_EXPAND_BBCODE' => $lang['Expand_bbcode'],
				'L_BBCODE_B_HELP' => $lang['bbcode_b_help'], 
				'L_BBCODE_I_HELP' => $lang['bbcode_i_help'], 
				'L_BBCODE_U_HELP' => $lang['bbcode_u_help'], 
				'L_BBCODE_Q_HELP' => $lang['bbcode_q_help'], 
				'L_BBCODE_C_HELP' => $lang['bbcode_c_help'], 
				'L_BBCODE_L_HELP' => $lang['bbcode_l_help'], 
				'L_BBCODE_O_HELP' => $lang['bbcode_o_help'], 
				'L_BBCODE_P_HELP' => $lang['bbcode_p_help'], 
				'L_BBCODE_W_HELP' => $lang['bbcode_w_help'], 
				'L_BBCODE_A_HELP' => $lang['bbcode_a_help'], 
				'L_BBCODE_A1_HELP' => $lang['bbcode_a1_help'], 				
				'L_BBCODE_S_HELP' => $lang['bbcode_s_help'], 
				'L_BBCODE_F_HELP' => $lang['bbcode_f_help'], 
				'L_BBCODE_C_HELP' => $lang['bbcode_c_help'], 
				'L_BBCODE_F1_HELP' => $lang['bbcode_f1_help'],
				'L_BBCODE_G1_HELP' => $lang['bbcode_g1_help'], 
				'L_BBCODE_H1_HELP' => $lang['bbcode_h1_help'],
				'L_BBCODE_S1_HELP' => $lang['bbcode_s1_help'], 
				'L_BBCODE_SC_HELP' => $lang['bbcode_sc_help'],

				'L_SMILIE_CREATOR' => $lang['Smilie_creator'],
			  	'L_EMPTY_MESSAGE' => $lang['Empty_message'],
			
				'L_FONT_COLOR' => $lang['Font_color'], 
				'L_COLOR_DEFAULT' => $lang['color_default'], 
				'L_COLOR_DARK_RED' => $lang['color_dark_red'], 
				'L_COLOR_RED' => $lang['color_red'], 
				'L_COLOR_ORANGE' => $lang['color_orange'], 
				'L_COLOR_BROWN' => $lang['color_brown'], 
				'L_COLOR_YELLOW' => $lang['color_yellow'], 
				'L_COLOR_GREEN' => $lang['color_green'], 
				'L_COLOR_OLIVE' => $lang['color_olive'], 
				'L_COLOR_CYAN' => $lang['color_cyan'], 
				'L_COLOR_BLUE' => $lang['color_blue'], 
				'L_COLOR_DARK_BLUE' => $lang['color_dark_blue'], 
				'L_COLOR_INDIGO' => $lang['color_indigo'], 
				'L_COLOR_VIOLET' => $lang['color_violet'], 
				'L_COLOR_WHITE' => $lang['color_white'], 
				'L_COLOR_BLACK' => $lang['color_black'], 
				'L_COLOR_CADET_BLUE' => $lang['color_cadet_blue'],
				'L_COLOR_CORAL' => $lang['color_coral'], 
				'L_COLOR_CRIMSON' => $lang['color_crimson'], 
				'L_COLOR_TOMATO' => $lang['color_tomato'], 
				'L_COLOR_SEA_GREEN' => $lang['color_sea_green'], 
				'L_COLOR_DARK_ORCHID' => $lang['color_dark_orchid'], 
				'L_COLOR_CHOCOLATE' => $lang['color_chocolate'],
				'L_COLOR_DEEPSKYBLUE' => $lang['color_deepskyblue'], 
				'L_COLOR_GOLD' => $lang['color_gold'], 
				'L_COLOR_GRAY' => $lang['color_gray'], 
				'L_COLOR_MIDNIGHTBLUE' => $lang['color_midnightblue'], 
				'L_COLOR_DARKGREEN' => $lang['color_darkgreen'], 

			  	'L_FONT_SIZE' => $lang['Font_size'], 
			  	'L_FONT_TINY' => $lang['font_tiny'], 
			  	'L_FONT_SMALL' => $lang['font_small'], 
			  	'L_FONT_NORMAL' => $lang['font_normal'], 
			  	'L_FONT_LARGE' => $lang['font_large'], 
			  	'L_FONT_HUGE' => $lang['font_huge'], 	

				'L_ALIGN_TEXT' => $lang['Align_text'], 
				'L_LEFT' => $lang['text_left'], 
				'L_CENTER' => $lang['text_center'], 
				'L_RIGHT' => $lang['text_right'], 
				'L_JUSTIFY' => $lang['text_justify'], 
		
				'L_FONT_FACE' => $lang['Font_face'],
				'L_HIGHLIGHT_COLOR' => $lang['Highlight_color'], 
				'L_SHADOW_COLOR' => $lang['Shadow_color'],
				'L_GLOW_COLOR' => $lang['Glow_color'],
	
			  	'L_BBCODE_CLOSE_TAGS' => $lang['Close_Tags'], 
			  	'L_STYLES_TIP' => $lang['Styles_tip'])
		   	);
		   		
			if( !$HTTP_POST_VARS['preview'] )
			{
	   			get_kb_type_list($type_id);	
			}
		}
		
		// PreText HIDE/SHOW
		if ( $board_config['kb_show_pt'] ) 
		{
			// Pull Header/Body info.		
			$pt_body = $board_config['kb_pt_body'];		
			$template->assign_vars(array(
				'PRETEXT_BODY' => $pt_body )
			);
		}
	
		if( $HTTP_POST_VARS['preview'] )
		{
			//
			// Define censored word matches
			//
			if ( !$board_config['allow_swearywords'] )
			{
				$orig_word = $replacement_word = array();
				obtain_word_list($orig_word, $replacement_word);
			}	
			else if ( !$userdata['user_allowswearywords'] )
			{
				$orig_word = $replacement_word = array();
				obtain_word_list($orig_word, $replacement_word);
			}

			$message = $HTTP_POST_VARS['message'];
			
			$bbcode_uid = make_bbcode_uid();

			$preview_message = stripslashes(prepare_message($message, $html_on, $bbcode_on, $smilies_on, $bbcode_uid)); 
	
			$message = stripslashes($message);

			if ( $row['bbcode_uid'] != '' )
			{
				$message = preg_replace('/\:(([a-z0-9]:)?)' . $row['bbcode_uid'] . '/s', '', $message);
			}

			$preview_message = bbencode_first_pass($preview_message, $bbcode_uid);
			
			$preview_message = bbencode_second_pass($preview_message, $bbcode_uid);

			//
			// Replace naughty words
			//
			if( !empty($orig_word) )
			{
				$preview_message = preg_replace($orig_word, $replacement_word, $preview_message);
			}
	
			$preview_message = make_clickable($preview_message);
	
	 		//
 			// ed2k link and add all
			//
			$preview_message = make_addalled2k_link($preview_message, $row['article_id']);

			if ( $smilies_on )
			{
				$preview_message = smilies_pass($preview_message);
			}
	
			$preview_message = str_replace("\n", '<br />', $preview_message);
	
			$template->set_filenames(array(
				'preview' => 'kb_add_preview.tpl')
			);
	
			get_kb_type_list($HTTP_POST_VARS['type_id']);
		
			$template->assign_vars(array(
				'ARTICLE_TITLE' => $HTTP_POST_VARS['article_name'],
				'ARTICLE_DESC' => $HTTP_POST_VARS['article_desc'],
				'ARTICLE_BODY' => $message,
				'USERNAME' => $HTTP_POST_VARS['username'],
				'PREVIEW_MESSAGE' => $preview_message)
			);
			$template->assign_var_from_handle('KB_PREVIEW_BOX', 'preview');
		}
	
		// post article
		if ( $HTTP_POST_VARS['article_submit'] )
		{
			$page_title = $lang['Add_article'];
	   		include($phpbb_root_path . 'includes/page_header.'.$phpEx);
	
	   		make_jumpbox('viewforum.'.$phpEx);
	   
	   		//load header
	   		include ($phpbb_root_path . 'includes/kb_header.'.$phpEx);
	   
	  		if ( !$HTTP_POST_VARS['article_name'] || !$HTTP_POST_VARS['article_desc'] || !$HTTP_POST_VARS['message'] )
	   		{
	   			$message = $lang['Fields_empty'] . '<br /><br />' . sprintf($lang['Click_return_kb'], '<a href="' . append_sid('kb.'.$phpEx.'?mode=add') . '">', '</a>');
		  		
		  		message_die(GENERAL_MESSAGE, $message);
	   		}
	   		
	   		$article_text = $HTTP_POST_VARS['message'];
	   
	   		$bbcode_uid = make_bbcode_uid();
	   		$error_msg = '';	      
	   		$article_text = bbencode_first_pass($article_text, $bbcode_uid);
     	   	$category = $category_id;
	   		$title = $HTTP_POST_VARS['article_name'];
	   		$description = $HTTP_POST_VARS['article_desc'];
	   		$date = time();
	   		$author_id = $userdata['user_id'];	   
	   		$type = $HTTP_POST_VARS['type_id'];
	   		$username = $HTTP_POST_VARS['username'];
	   
	   		$approve = 0;	   
	   		if ( ( !$board_config['kb_approve_new'] ) || ( $is_admin ) || ( $userdata['user_level'] == LESS_ADMIN ) || ( $userdata['user_level'] == MOD ) )
	   		{
	   	  		$approve = 1;
		  		update_kb_number($category, '+ 1');
	   		}
   
	   		$sql = "INSERT INTO " . KB_ARTICLES_TABLE . " (article_category_id , article_title , article_description , article_date , article_author_id , username , bbcode_uid , article_body , article_type , approved, views) 
	   			VALUES ('$category', '$title', '$description', '$date', '$author_id', '$username', '$bbcode_uid', '$article_text', '$type', '$approve', '0')";   
	   		if ( !($results = $db->sql_query($sql)) )
	   		{
	       		message_die(GENERAL_ERROR, "Could not submit aritcle", '', __LINE__, __FILE__, $sql);
			}

	   		if ( !$approve )
	   		{	   
	        	email_kb_admin($board_config['kb_notify']);
	   		}
	   
	   		if ( $approve == 1 && $board_config['kb_comments'] )
	   		{		  
		  		$sql = "SELECT * 
		  			FROM " . KB_ARTICLES_TABLE . " 
		  			WHERE article_date = '" . $date . "'";
	   	  		if ( !($results = $db->sql_query($sql)) )
				{
	    			message_die(GENERAL_ERROR, "Could not get aritcle id", '', __LINE__, __FILE__, $sql);
	   	  		}
	   	  		$row = $db->sql_fetchrow($results);
		  
		  		$article_id = $row['article_id'];
		  
		  		// choose a user
		  		$user_id = $board_config['kb_admin_id'];

		  		// initialise the userdata
		  		$sql = "SELECT * 
		  			FROM " . USERS_TABLE . " 
		  			WHERE user_id = $user_id";
		  		if ( !($result = $db->sql_query($sql)) )
		  		{
	  	      		message_die(CRITICAL_ERROR, 'Could not obtain data from user table', '', __LINE__, __FILE__, $sql);
		  		}
		  		$user = $db->sql_fetchrow($result);
		  		
		  		init_userprefs($user);
	
		  		$cat = get_kb_cat($row['article_category_id']);
		  		$type = get_kb_type($row['article_type']);
		  		$author = get_kb_author($row['article_author_id'], FALSE);
	
		  		$temp_url = "http://" . $board_config['server_name'] . $board_config['script_path'] . "kb.".$phpEx."?mode=article&k=".$article_id;
		  		$message = "[b]" . $lang['Category'] . ":[/b] " . addslashes($cat['category_name']) . " (" . $row['article_category_id'] . ")\n";
		  		$message .= "[b]" . $lang['Article_type'] . ":[/b] " . $type . "\n\n";
		  		$message .= "[b]" . $lang['Article_title'] . ":[/b] " . addslashes($row['article_title']) . "\n";
		  		$message .= "[b]" . $lang['Author'] . ":[/b] " . $author . "\n";
		  		$message .= "[b]" . $lang['Article_description'] . ":[/b] " . addslashes($row['article_description']) . "\n\n";
		  		$message .= "[b][url=" . $temp_url . "]" . $lang['Read_full_article'] . "[/url][/b]";
	
		  		$subject = '[KB] ' . addslashes($row['article_title']);
		  		$forum_id = $board_config['kb_forum_id'];
	
		  		$topic_data = insert_post($message, $subject, $forum_id, $user['user_id'], $user['username'], $user['user_attachsig']);
		  
		  		$sql = "UPDATE " . KB_ARTICLES_TABLE . " 
		  			SET topic_id = " . $topic_data['topic_id'] . " 
		 	 		WHERE article_id = " . $article_id;
		 		if ( !($result = $db->sql_query($sql)) )
		  		{
   	   	  	  		message_die(GENERAL_ERROR, "Could not update article data", '', __LINE__, __FILE__, $sql);
	      		}
	   		}
	   
	   		if ($approve == 1)
	   		{
	       		add_kb_words($article_id, $row['article_body']);
	   		}
  
	   		$message = $lang['Article_submitted'] . '<br /><br />' . sprintf($lang['Click_return_kb'], '<a href="' . append_sid("kb.$phpEx") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid("index.$phpEx") . '">', '</a>');

	   		message_die(GENERAL_MESSAGE, $message);	   
		}	
		break;

	case 'edit':
		$article_id = ( isset($HTTP_GET_VARS['k']) ) ? intval($HTTP_GET_VARS['k']) : intval($HTTP_POST_VARS['k']);	
	
		//show article form
		if ( !$HTTP_POST_VARS['article_submit'] || $HTTP_POST_VARS['preview'] )
		{
			$sql = "SELECT *
		  		FROM " . KB_ARTICLES_TABLE . "
		  		WHERE article_id = $article_id";
	    	if ( !($result = $db->sql_query($sql)) )
			{
	    	    message_die(GENERAL_ERROR, "Could not obtain article data", '', __LINE__, __FILE__, $sql);
	    	}
			$row = $db->sql_fetchrow($result);
		  
		  	$article_name = htmlspecialchars($row['article_title']); 
        	$article_category = $row['article_category_id']; 
        	$article_desc = htmlspecialchars($row['article_description']); 
        	$article_body = htmlspecialchars($row['article_body']);
		  	$article_type = $row['article_type'];
		  	$bbcode_uid = $row['bbcode_uid'];
		  	$topic = $row['topic_id'];
		  	$author = $row['article_author_id'];
		  
		  	if ( $row['bbcode_uid'] != '' )
		  	{
				$article_body = preg_replace('/\:(([a-z0-9]:)?)' . $row['bbcode_uid'] . '/s', '', $article_body);
		  	}

	   		//
	   		// HTML toggle selection
	   		//
	   		if ( $board_config['allow_html'] )
	   		{
	   	  		$html_status = $lang['HTML_is_ON'];
	   		}
	   		else
	   		{
	   	  		$html_status = $lang['HTML_is_OFF'];
	   		}

	   		//
	   		// BBCode toggle selection
	   		//
	   		if ( $board_config['allow_bbcode'] )
	   		{
	      		$bbcode_status = $lang['BBCode_is_ON'];
	   		}
	   		else
	   		{
	   	  		$bbcode_status = $lang['BBCode_is_OFF'];
			}

	   		//
	   		// Smilies toggle selection
	  		//
	   		if ( $board_config['allow_smilies'] )
	   		{
	   			$smilies_status = $lang['Smilies_are_ON'];
	   		}
	   		else
	   		{
				if( $board_config['smilie_removal1'] )
				{
					$smilies_status = $lang['Smilies_are_REMOVED'];
				}
				else
				{
					$smilies_status = $lang['Smilies_are_OFF'];
				}
	   		}
	   
	   		// Generate smilies listing for page output
	   		if( $board_config['allow_smilies'] )
			{
				generate_smilies('inline', PAGE_POSTING);
			}
			   

	   		//load header
	   		$page_title = $lang['Edit_article'];
	   		include($phpbb_root_path . 'includes/page_header.'.$phpEx);
	   		include ($phpbb_root_path . 'includes/kb_header.'.$phpEx);
	   
	   		if ( !$is_admin )
	   		{
	       		if ( ( $userdata['user_id'] != $author && $userdata['session_logged_in'] ) )
	   	   		{
		       		$message = $lang['No_edit'] . '<br /><br />' . sprintf($lang['Click_return_kb'], '<a href="' . append_sid("kb.$phpEx") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid("index.$phpEx") . '">', '</a>');

	    	   		message_die(GENERAL_MESSAGE, $message);
		   		}
	   		}
	
	   		//set up page
	   		$template->set_filenames(array(
				'body' => 'kb_add_body.tpl')
	   		);
	   		make_jumpbox('viewforum.'.$phpEx);

	   		if ( $is_admin || $userdata['user_id'] == $author )
	   		{
	       		$template->assign_block_vars('switch_edit', array());
	   		}

			Multi_BBCode();

			while( list($key, $font) = each($lang['font']) )
			{
				$template->assign_block_vars ('font_styles', array(
					'L_FONTNAME' => $font)
				);
			}
	
	   		$template->assign_vars(array(
				'L_ADD_ARTICLE' => $lang['Edit_article'],
		  		'L_ARTICLE_TITLE' => $lang['Article_title'],
		  		'L_ARTICLE_DESCRIPTION' => $lang['Article_description'],
		  		'L_ARTICLE_TEXT' => $lang['Article_text'],
		  		'L_ARTICLE_TYPE' => $lang['Article_type'],
		  		'L_ARTICLE_CATEGORY' => $lang['Category'],
		  		'L_TOPIC' => $lang['Topic'],
				'L_SPELLCHECK' => $lang['Spellcheck'],
				'L_COPY_TO_CLIPBOARD' => $lang['Copy_to_clipboard'],
				'L_HIGHLIGHT_TEXT' => $lang['Highlight_text'],
		  		'L_PREVIEW' => $lang['Preview'],
				'L_POST_SUBJECT' => $lang['Post_subject'],
				'L_ITEMS_REQUIRED' => $lang['Items_required'],
	  	
		  		'L_FAQ' => $lang['FAQ'],
		  		'L_HOWTO' => $lang['HowTo'],
		  		'L_INFO' => $lang['Info'],
		  		'L_TUTORIAL' => $lang['Tutorial'],
		  	
		  		'L_OPTIONS' => $lang['Options'],
		  		'S_ACTION' => append_sid('kb.'.$phpEx.'?mode=edit'),
		  		'HTML_STATUS' => $html_status,
		  		'BBCODE_STATUS' => sprintf($bbcode_status, '<a href="' . append_sid("faq.$phpEx?mode=bbcode") . '" target="_phpbbcode">', '</a>'), 
		  		'SMILIES_STATUS' => $smilies_status,
		  	
		  		'ARTICLE_TITLE' => $article_name,
		  		'ARTICLE_DESC' => $article_desc,
		  		'ARTICLE_BODY' => $article_body,
		  		'PREVIEW_DATE' => create_date($board_config['default_dateformat'], time(), $board_config['board_timezone']),
		  		'TOPIC' => $topic,
		  		'S_HIDDEN_FIELDS' => '<input type="hidden" name="k" value="' . $article_id . '"><input type="hidden" name="bbcode_uid" value="' . $bbcode_uid . '"><input type="hidden" name="author_id" value="' . $author . '">',
			
				'L_EXPAND_BBCODE' => $lang['Expand_bbcode'],
				'L_BBCODE_B_HELP' => $lang['bbcode_b_help'], 
				'L_BBCODE_I_HELP' => $lang['bbcode_i_help'], 
				'L_BBCODE_U_HELP' => $lang['bbcode_u_help'], 
				'L_BBCODE_Q_HELP' => $lang['bbcode_q_help'], 
				'L_BBCODE_L_HELP' => $lang['bbcode_l_help'], 
				'L_BBCODE_O_HELP' => $lang['bbcode_o_help'], 
				'L_BBCODE_P_HELP' => $lang['bbcode_p_help'], 
				'L_BBCODE_W_HELP' => $lang['bbcode_w_help'], 
				'L_BBCODE_A_HELP' => $lang['bbcode_a_help'], 
				'L_BBCODE_A1_HELP' => $lang['bbcode_a1_help'], 
				'L_BBCODE_S_HELP' => $lang['bbcode_s_help'], 
				'L_BBCODE_F_HELP' => $lang['bbcode_f_help'], 
				'L_BBCODE_C_HELP' => $lang['bbcode_c_help'], 
				'L_BBCODE_F1_HELP' => $lang['bbcode_f1_help'],
				'L_BBCODE_G1_HELP' => $lang['bbcode_g1_help'], 
				'L_BBCODE_H1_HELP' => $lang['bbcode_h1_help'],
				'L_BBCODE_S1_HELP' => $lang['bbcode_s1_help'], 	
				'L_BBCODE_SC_HELP' => $lang['bbcode_sc_help'],

				'L_SMILIE_CREATOR' => $lang['Smilie_creator'],
			  	'L_EMPTY_MESSAGE' => $lang['Empty_message'],

				'L_HIGHLIGHT_COLOR' => $lang['Highlight_color'], 
				'L_SHADOW_COLOR' => $lang['Shadow_color'],
				'L_GLOW_COLOR' => $lang['Glow_color'],

				'L_FONT_COLOR' => $lang['Font_color'], 
				'L_COLOR_DEFAULT' => $lang['color_default'], 
				'L_COLOR_DARK_RED' => $lang['color_dark_red'], 
				'L_COLOR_RED' => $lang['color_red'], 
				'L_COLOR_ORANGE' => $lang['color_orange'], 
				'L_COLOR_BROWN' => $lang['color_brown'], 
				'L_COLOR_YELLOW' => $lang['color_yellow'], 
				'L_COLOR_GREEN' => $lang['color_green'], 
				'L_COLOR_OLIVE' => $lang['color_olive'], 
				'L_COLOR_CYAN' => $lang['color_cyan'], 
				'L_COLOR_BLUE' => $lang['color_blue'], 
				'L_COLOR_DARK_BLUE' => $lang['color_dark_blue'], 
				'L_COLOR_INDIGO' => $lang['color_indigo'], 
				'L_COLOR_VIOLET' => $lang['color_violet'], 
				'L_COLOR_WHITE' => $lang['color_white'], 
				'L_COLOR_BLACK' => $lang['color_black'], 
				'L_COLOR_CADET_BLUE' => $lang['color_cadet_blue'],
				'L_COLOR_CORAL' => $lang['color_coral'], 
				'L_COLOR_CRIMSON' => $lang['color_crimson'], 
				'L_COLOR_TOMATO' => $lang['color_tomato'], 
				'L_COLOR_SEA_GREEN' => $lang['color_sea_green'], 
				'L_COLOR_DARK_ORCHID' => $lang['color_dark_orchid'], 
				'L_COLOR_CHOCOLATE' => $lang['color_chocolate'],
				'L_COLOR_DEEPSKYBLUE' => $lang['color_deepskyblue'], 
				'L_COLOR_GOLD' => $lang['color_gold'], 
				'L_COLOR_GRAY' => $lang['color_gray'], 
				'L_COLOR_MIDNIGHTBLUE' => $lang['color_midnightblue'], 
				'L_COLOR_DARKGREEN' => $lang['color_darkgreen'], 

			  	'L_FONT_SIZE' => $lang['Font_size'], 
			  	'L_FONT_TINY' => $lang['font_tiny'], 
			  	'L_FONT_SMALL' => $lang['font_small'], 
			  	'L_FONT_NORMAL' => $lang['font_normal'], 
			  	'L_FONT_LARGE' => $lang['font_large'], 
			  	'L_FONT_HUGE' => $lang['font_huge'], 	

				'L_ALIGN_TEXT' => $lang['Align_text'], 
				'L_LEFT' => $lang['text_left'], 
				'L_CENTER' => $lang['text_center'], 
				'L_RIGHT' => $lang['text_right'], 
				'L_JUSTIFY' => $lang['text_justify'], 
		
				'L_FONT_FACE' => $lang['Font_face'],
				'L_HIGHLIGHT_COLOR' => $lang['Highlight_color'], 
				'L_SHADOW_COLOR' => $lang['Shadow_color'],
				'L_GLOW_COLOR' => $lang['Glow_color'],

			  	'L_BBCODE_CLOSE_TAGS' => $lang['Close_Tags'], 
		 		'L_STYLES_TIP' => $lang['Styles_tip'])
	   		);	
	   
	   		// PreText HIDE/SHOW
			if ( $board_config['kb_show_pt'] ) 
			{
				// Pull Header/Body info.		
				$pt_body = $board_config['kb_pt_body'];		
				$template->assign_vars(array(
					'PRETEXT_BODY' => $pt_body)
				);
			}
			
			if( !$HTTP_POST_VARS['preview'] )
			{
				get_kb_type_list($article_type);
				get_kb_cat_list($article_category);
			}
		}
		
		if( $HTTP_POST_VARS['preview'] )
		{
			$sql = "SELECT bbcode_uid
		  		FROM " . KB_ARTICLES_TABLE . "
		  		WHERE article_id = $article_id";
			if ( !($result = $db->sql_query($sql)) )
			{
	    		message_die(GENERAL_ERROR, 'Could not obtain article bbcode_uid.', '', __LINE__, __FILE__, $sql);
			}
			$row = $db->sql_fetchrow($result);	
		
			//
			// Define censored word matches
			//
			if ( !$board_config['allow_swearywords'] )
			{	
				$orig_word = $replacement_word = array();
				obtain_word_list($orig_word, $replacement_word);
			}
			else if ( !$userdata['user_allowswearywords'] )
			{
				$orig_word = $replacement_word = array();
				obtain_word_list($orig_word, $replacement_word);
			}

			$message = $HTTP_POST_VARS['message'];
			
			$preview_message = stripslashes(prepare_message($message, $html_on, $bbcode_on, $smilies_on, $bbcode_uid));
		
			$message = stripslashes($message);
			$message = preg_replace('/\:(([a-z0-9]:)?)' . $row['bbcode_uid'] . '/s', '', $message);

			$preview_message = bbencode_first_pass($preview_message, $bbcode_uid);
			
			$preview_message = bbencode_second_pass($preview_message, $bbcode_uid);

			$preview_message = make_clickable($preview_message);

	 		//
 			// ed2k link and add all
			//
			$preview_message = make_addalled2k_link($preview_message, $article_id);

			if( $smilies_on )
			{
				$preview_message = smilies_pass($preview_message);
			}

			//
			// Replace naughty words
			//
			if( !empty($orig_word) )
			{
				$preview_message = preg_replace($orig_word, $replacement_word, $preview_message);
			}

			$preview_message = str_replace("\n", '<br />', $preview_message);
	
			$template->set_filenames(array(
				'preview' => 'kb_add_preview.tpl')
			);
			
			get_kb_type_list($HTTP_POST_VARS['type_id']);
			get_kb_cat_list($HTTP_POST_VARS['category_id']);

			$template->assign_vars(array(
				'ARTICLE_TITLE' => $HTTP_POST_VARS['article_name'],
				'ARTICLE_DESC' => $HTTP_POST_VARS['article_desc'],
				'ARTICLE_BODY' => $message,
			
				'PREVIEW_MESSAGE' => $preview_message)
			);
	
			$template->assign_var_from_handle('KB_PREVIEW_BOX', 'preview');
		}
	
		// update article
		if ( $HTTP_POST_VARS['article_submit'] )
		{		   	   
	   		$title = $HTTP_POST_VARS['article_name'];
	   		$description = $HTTP_POST_VARS['article_desc'];
	   		$article_text = $HTTP_POST_VARS['message'];

		   	if ( empty($title) || empty($description) || empty($article_text) )
		   	{
				message_die(GENERAL_MESSAGE, 'Please fill out all parts of the form.<br /><br />Click <a href="' . append_sid('kb.'.$phpEx.'?mode=add') . '">Here</a> to return to the form');
	   		}
	   		
	   
	   		$sql = "SELECT article_category_id, approved
		  		FROM " . KB_ARTICLES_TABLE . "
				WHERE article_id = $article_id";
	    	if ( !($result = $db->sql_query($sql)) )
		  	{
	        	message_die(GENERAL_ERROR, 'Could not obtain article category/approved data.', '', __LINE__, __FILE__, $sql);
	    	}
			$row = $db->sql_fetchrow($result);
	   
	   		$bbcode_uid = $HTTP_POST_VARS['bbcode_uid'];
	   		$old_approve = $row['approved'];
	   
			$error_msg = '';	      
	   		$article_text = bbencode_first_pass($article_text, $bbcode_uid);
	   		$category = $HTTP_POST_VARS['category_id'];
	   		$date = time();
	   		$author_id = $HTTP_POST_VARS['author_id'];	   
	   		$type = $HTTP_POST_VARS['type_id'];
	   		$topic = $HTTP_POST_VARS['topic'];
	   
	   		$old_category = $row['article_category_id'];
     
     		if ( $old_category != $category )
	   		{
	       		update_kb_number($old_category, '- 1');
				// This needs to be confirmed .....
	       		if ( ( !$board_config['kb_approve_edit'] ) || ( $is_admin ) )
// 	       		if ( ( !$board_config['kb_approve_edit'] ) || ( !$is_admin ) )
	       		{
	        		update_kb_number($category, '+ 1');
         		}
     		}
	   
	  		if ( ( !$board_config['kb_approve_edit'] ) || ( $is_admin ) )
	   		{
	   	  		$approve = 1;		  
		  		if ( $old_approve != 1 )
		  		{
		      		update_kb_number($category, '+ 1');
		  		}
	   		}
	   		else
	   		{
	   	   		$approve = 2;
		   		if ( $old_approve == 1 && $old_category == $category )
		   		{		   
		    		update_kb_number($category, '- 1');
		   		}
	   		}

	   		$sql = "UPDATE " . KB_ARTICLES_TABLE . "
	   			SET article_category_id = '$category', article_title = '$title', article_description = '$description', article_date = '$date', article_author_id = '$author_id', article_body = '$article_text', article_type = '$type', approved = '$approve', topic_id = '$topic' 
				WHERE article_id = '$article_id'";		
			if ( !($edit_article = $db->sql_query($sql)) )
	   		{
	   			message_die(GENERAL_ERROR, 'Could not update aritcle.', '', __LINE__, __FILE__, $sql);
	   		}

	   		if ( !$approve )
	   		{	   
	    	    email_kb_admin($board_config['kb_notify']);
	   		}
	   
	   		if ($approve == 1)
	   		{
	       		add_kb_words($article_id, $article_text);
	 		}
	   
	    	$message = $lang['Article_Edited'];
	   		$message .=  ( $is_admin ) ? '' : $lang['Article_Edited2'];
	   		$message .=  '<br /><br />' . sprintf($lang['Click_return_kb'], '<a href="' . append_sid("kb.$phpEx") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid("index.$phpEx") . '">', '</a>');

			message_die(GENERAL_MESSAGE, $message);	   
		}	
		break;

	default:
		$page_title = $lang['Kb'];
		include($phpbb_root_path . 'includes/page_header.'.$phpEx);
		include($phpbb_root_path . 'includes/kb_header.'.$phpEx);
	
		$template->set_filenames(array(
			'body' => 'kb_index_body.tpl')
		);
		make_jumpbox('viewforum.'.$phpEx);
	
		$template->assign_vars(array(
			'L_CATEGORY' => $lang['Category'],
			'L_ARTICLES' => $lang['Articles'])
		);	
	
    	get_kb_cat_index();

		break;
}

if (!empty($board_config['enable_spellcheck']))
{
	$template->assign_block_vars('switch_spellcheck', array());
}

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