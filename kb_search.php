<?php
/** 
*
* @package phpBB2
* @version $Id: kb_search.php,v 1.0.0 2005/03/15 18:34:34 acydburn Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
	
define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);
include($phpbb_root_path . 'includes/functions_kb.'.$phpEx);
include($phpbb_root_path . 'includes/bbcode.'.$phpEx);
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


//
// Define initial vars
//
if ( isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']) )
{
	$mode = ( isset($HTTP_POST_VARS['mode']) ) ? htmlspecialchars($HTTP_POST_VARS['mode']) : htmlspecialchars($HTTP_GET_VARS['mode']);
}
else
{
	$mode = '';
}

if ( isset($HTTP_POST_VARS['search_keywords']) || isset($HTTP_GET_VARS['search_keywords']) )
{
	$search_keywords = ( isset($HTTP_POST_VARS['search_keywords']) ) ? $HTTP_POST_VARS['search_keywords'] : $HTTP_GET_VARS['search_keywords'];
}
else
{
	$search_keywords = '';
}

if ( !$search_keywords || $search_keywords == '' )
{
    $mode = '';
}

$search_id = ( isset($HTTP_GET_VARS['search_id']) ) ? $HTTP_GET_VARS['search_id'] : '';

$show_results = ( isset($HTTP_POST_VARS['show_results']) ) ? $HTTP_POST_VARS['show_results'] : ( ( isset($HTTP_GET_VARS['show_results']) ) ? $HTTP_GET_VARS['show_results'] : 'posts' );

if ( isset($HTTP_POST_VARS['search_terms']) )
{
	$search_terms = ( $HTTP_POST_VARS['search_terms'] == 'all' ) ? 1 : 0;
}
else
{
	$search_terms = 0;
}

if ( isset($HTTP_POST_VARS['search_fields']) )
{
	$search_fields = ( $HTTP_POST_VARS['search_fields'] == 'all' ) ? 1 : 0;
}
else
{
	$search_fields = 0;
}

$sort_by = ( isset($HTTP_POST_VARS['sort_by']) ) ? intval($HTTP_POST_VARS['sort_by']) : 0;

if ( isset($HTTP_POST_VARS['sort_dir']) )
{
	$sort_dir = ( $HTTP_POST_VARS['sort_dir'] == 'DESC' ) ? 'DESC' : 'ASC';
}
else
{
	$sort_dir =  'DESC';
}

$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;

//
// encoding match for workaround
//
$multibyte_charset = 'utf-8, big5, shift_jis, euc-kr, gb2312';

//
// Begin core code
//
switch($mode)
{
    case "results":
		$store_vars = array('search_results', 'total_match_count', 'split_search', 'sort_by', 'sort_dir', 'show_results', 'return_chars');
		$search_results = ''; 

		//
		// Search ID Limiter, decrease this value if you experience further timeout problems with searching forums
		$limiter = 5000;
		$current_time = time();
	
	    //
		// Cycle through options ...
		//
		if ( $search_keywords != '' )
		{
			//
			// Flood control
			//
			$where_sql = ($userdata['user_id'] == ANONYMOUS) ? "se.session_ip = '$user_ip'" : 'se.session_user_id = ' . $userdata['user_id'];
			$sql = 'SELECT MAX(sr.search_time) AS last_search_time
				FROM ' . SEARCH_TABLE . ' sr, ' . SESSIONS_TABLE . " se
				WHERE sr.session_id = se.session_id
					AND $where_sql";
			if ($result = $db->sql_query($sql))
			{
				if ($row = $db->sql_fetchrow($result))
				{
					if (intval($row['last_search_time']) > 0 && ($current_time - intval($row['last_search_time'])) < intval($board_config['search_flood_interval']))
					{
						message_die(GENERAL_MESSAGE, $lang['Search_Flood_Error']);
					}
				}
			}
		
		    if ( $search_keywords != '' )
			{
   				$stopword_array = @file($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/search_stopwords.txt');
				$synonym_array = @file($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/search_synonyms.txt'); 

				// Fix for PHP 5.0.5+
				$lang_encoding = strstr($multibyte_charset, $lang['ENCODING']);
				$search_keywords = stripslashes($search_keywords);
				$clean_words = clean_words('search', $search_keywords, $stopword_array, $synonym_array);
				$split_search = ( !$lang_encoding ) ?  split_words($clean_words, 'search') : split(' ', $search_keywords);    
		
//				$split_search = array();
//				$stripped_keywords = stripslashes($search_keywords);
//				$split_search = ( !strstr($multibyte_charset, $lang['ENCODING']) ) ?  split_words(clean_words('search', $stripped_keywords, $stopword_array, $synonym_array), 'search') : split(' ', $search_keywords);	
//				unset($stripped_keywords);

				$search_msg_only = ( !$search_fields ) ? "AND m.title_match = 0" : ( ( strstr($multibyte_charset, $lang['ENCODING']) ) ? '' : '' );

				$word_count = 0;
				$current_match_type = 'or';

				$word_match = $result_list = array();

				for($i = 0; $i < sizeof($split_search); $i++)
				{
					if ( strlen(str_replace(array('*', '%'), '', trim($split_search[$i]))) < $board_config['search_min_chars'] )
					{
						$split_search[$i] = '';
						continue;
					}
				
				    switch ( $split_search[$i] )
					{
					    case 'and':
						    $current_match_type = 'and';
							break;

						case 'or':
						    $current_match_type = 'or';
							break;

						case 'not':
							$current_match_type = 'not';
							break;

						default:
							if ( !empty($search_terms) )
							{
							    $current_match_type = 'and';
							}

							if ( !strstr($multibyte_charset, $lang['ENCODING']) )
							{
							    $match_word = str_replace('*', '%', $split_search[$i]);
								$sql = "SELECT m.article_id 
								    FROM " . KB_WORD_TABLE . " w, " . KB_MATCH_TABLE . " m 
									WHERE w.word_text LIKE '$match_word' 
									    AND m.word_id = w.word_id 
										AND w.word_common <> 1 
										$search_msg_only";
							}
							else
							{
							    $match_word =  addslashes('%' . str_replace('*', '', $split_search[$i]) . '%');
								$search_msg_only = ( $search_fields ) ? "OR article_title LIKE '$match_word'" : '';
								$sql = "SELECT article_id
								    FROM " . KB_ARTICLE_TABLE . "
									WHERE article_body  LIKE '$match_word'
									$search_msg_only";
							}
							if ( !($result = $db->sql_query($sql)) )
							{
							    message_die(GENERAL_ERROR, 'Could not obtain matched articles list', '', __LINE__, __FILE__, $sql);
							}

							$row = array();
							while( $temp_row = $db->sql_fetchrow($result) )
							{
							    $row[$temp_row['post_id']] = 1;

								if ( !$word_count )
								{
								    $result_list[$temp_row['article_id']] = 1;
								}
								else if ( $current_match_type == 'or' )
								{
								    $result_list[$temp_row['article_id']] = 1;
								}
								else if ( $current_match_type == 'not' )
								{
								    $result_list[$temp_row['article_id']] = 0;
							    }
							}

							if ( $current_match_type == 'and' && $word_count )
							{
							    @reset($result_list);
							    while( list($article_id, $match_count) = @each($result_list) )
							    {
								    if ( !$row[$post_id] )
									{
									    $result_list[$post_id] = 0;
									}
								}
							}

						    $word_count++;

						    $db->sql_freeresult($result);
					    }
			    	}

					@reset($result_list);

					$search_ids = array();
					while( list($article_id, $matches) = each($result_list) )
					{
				        if ( $matches )
						{
					        $search_ids[] = $article_id;
						}
					}	
			
					unset($result_list);
					$total_match_count = sizeof($search_ids);
				}
		
				//
				// Store new result data
				//
				$search_results = implode(', ', $search_ids);
				$per_page = $board_config['topics_per_page'];

				//
				// Combine both results and search data (apart from original query)
				// so we can serialize it and place it in the DB
				//
				$store_search_data = array();

				//
				// Limit the character length (and with this the results displayed at all following pages) to prevent
				// truncated result arrays. Normally, search results above 12000 are affected.
				// - to include or not to include
				/*
				$max_result_length = 60000;
				if (strlen($search_results) > $max_result_length)
				{
			        $search_results = substr($search_results, 0, $max_result_length);
					$search_results = substr($search_results, 0, strrpos($search_results, ','));
					$total_match_count = sizeof(explode(', ', $search_results));
			    }
				*/

				for($i = 0; $i < sizeof($store_vars); $i++)
				{
			        $store_search_data[$store_vars[$i]] = $$store_vars[$i];
				}

				$result_array = serialize($store_search_data);
				unset($store_search_data);

				mt_srand ((double) microtime() * 1000000);
				$search_id = mt_rand();

				$sql = "UPDATE " . KB_SEARCH_TABLE . " 
			        SET search_id = $search_id, search_array = '" . str_replace("\'", "''", $result_array) . "'
					WHERE session_id = '" . $userdata['session_id'] . "'";
		    	if ( !($result = $db->sql_query($sql)) || !$db->sql_affectedrows() )
				{
			        $sql = "INSERT INTO " . KB_SEARCH_TABLE . " (search_id, session_id, search_array) 
				    	 VALUES($search_id, '" . $userdata['session_id'] . "', '" . str_replace("\'", "''", $result_array) . "')";
			    	if ( !($result = $db->sql_query($sql)) )
					{
				        message_die(GENERAL_ERROR, 'Could not insert search results', '', __LINE__, __FILE__, $sql);
					}
			    }
				else
				{
					$search_id = intval($search_id); 
					if ( $search_id ) 
					{
				        $sql = "SELECT search_array 
					        FROM " . KB_SEARCH_TABLE . " 
						    WHERE search_id = $search_id  
						        AND session_id = '". $userdata['session_id'] . "'";
				        if ( !($result = $db->sql_query($sql)) )
						{
					        message_die(GENERAL_ERROR, 'Could not obtain search results', '', __LINE__, __FILE__, $sql);
						}

						if ( $row = $db->sql_fetchrow($result) )
						{
					        $search_data = unserialize($row['search_array']);
							for($i = 0; $i < sizeof($store_vars); $i++)
							{
						        $$store_vars[$i] = $search_data[$store_vars[$i]];
							}
						}
					}
				}
			
				//
				// Look up data ...
				//
				if ( $search_results != '' )
				{
			        $sql = "SELECT t.*, u.username, u.user_id, u.user_level 
				        FROM " . KB_ARTICLES_TABLE . " t, " . USERS_TABLE . " u 
					    WHERE t.article_id IN ($search_results) 
					        AND t.article_author_id = u.user_id";	
						
				    $per_page = $board_config['topics_per_page'];

					$sql .= " ORDER BY t.article_title $sort_dir LIMIT $start, " . $per_page;

					if ( !$result = $db->sql_query($sql) )
					{
				        message_die(GENERAL_ERROR, 'Could not obtain search results', '', __LINE__, __FILE__, $sql);
					}

		  			$searchset = array();
					while( $row = $db->sql_fetchrow($result) )
					{
				        $searchset[] = $row;
					}
		
					$db->sql_freeresult($result);		
		
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
				}

				//
				// Output header
				//
				$page_title = $lang['Search'];
				include($phpbb_root_path . 'includes/page_header.'.$phpEx);	
				include($phpbb_root_path . 'includes/kb_header.'.$phpEx);

				$template->set_filenames(array(
					'body' => 'kb_search_results.tpl')
				);
				make_jumpbox('viewforum.'.$phpEx);

				$l_search_matches = ( $total_match_count == 1 ) ? sprintf($lang['Found_search_match'], $total_match_count) : sprintf($lang['Found_search_matches'], $total_match_count);

				$template->assign_vars(array(
			        'L_SEARCH_MATCHES' => $l_search_matches, 
			    	'L_ARTICLE' => $lang['Article'])
			    );

				$highlight_active = '';
				$highlight_match = array();
				for($j = 0; $j < sizeof($split_search); $j++ )
				{
			        $split_word = $split_search[$j];

					if ( $split_word != 'and' && $split_word != 'or' && $split_word != 'not' )
					{
				        $highlight_match[] = '#\b(' . str_replace("*", "([\w]+)?", $split_word) . ')\b#is';
						$highlight_active .= " " . $split_word;

						for ($k = 0; $k < sizeof($synonym_array); $k++)
						{ 
					        list($replace_synonym, $match_synonym) = split(' ', trim(strtolower($synonym_array[$k]))); 

							if ( $replace_synonym == $split_word )
							{
						        $highlight_match[] = '#\b(' . str_replace("*", "([\w]+)?", $replace_synonym) . ')\b#is';
						    	$highlight_active .= ' ' . $match_synonym;
							}
						} 
					}
				}

			    $highlight_active = urlencode(trim($highlight_active));
		
				for($i = 0; $i < sizeof($searchset); $i++)
				{
			        $article_url = append_sid("kb.$phpEx?mode=article&amp;k=" . $searchset[$i]['article_id'] . "&amp;highlight=$highlight_active");
			
					$post_date = create_date($board_config['default_dateformat'], $searchset[$i]['article_date'], $board_config['board_timezone']);

					$message = $searchset[$i]['article_body'];
					$article_title = $searchset[$i]['article_title'];
					$article_id = $searchset[$i]['article_id'];

					$cat = get_kb_cat($searchset[$i]['article_category_id']);
					$temp_url = append_sid($phpbb_root_path . 'kb.'.$phpEx.'?mode=cat&amp;cat=' . $searchset[$i]['article_category_id']);
					$category = '<a href="' . $temp_url . '" class="name">' . $cat['category_name'] . '</a>';
					
		  			$type = get_kb_type($searchset[$i]['article_type']);

					$message = '';

					if ( sizeof($orig_word) )
					{
				        $article_title = preg_replace($orig_word, $replacement_word, $searchset[$i]['article_title']);
					}
						
					$article_author = '<a href="' . append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . '=' . $searchset[$i]['user_id']) . '" class="name">';
					$article_author .= username_level_color($searchset[$i]['username'], $searchset[$i]['user_level'], $searchset[$i]['user_id']);
					$article_author .= '</a>';

					$template->assign_block_vars('searchresults', array( 
						'ARTICLE_ID' => $article_id,
						'ARTICLE_AUTHOR' => $article_author, 
						'ARTICLE_TITLE' => $article_title,
						'ARTICLE_DESCRIPTION' => $searchset[$i]['article_description'],
						'ARTICLE_CATEGORY' => $category,
						'ARTICLE_TYPE' => $type,
							 
						'U_VIEW_ARTICLE' => $article_url)
					);
				}
		    }

			$base_url = "kb_search.$phpEx?search_id=$search_id";

			$template->assign_vars(array(
			    'PAGINATION' => generate_pagination($base_url, $total_match_count, $per_page, $start),
			    'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / $per_page ) + 1 ), ceil( $total_match_count / $per_page )), 

				'L_AUTHOR' => $lang['Author'],
				'L_MESSAGE' => $lang['Message'],
				'L_TOPICS' => $lang['Article'],
				'L_TYPE' => $lang['Article_type'],
				'L_CATEGORY' => $lang['Category'])
		    );
		
		break;
		
	default:
	
	    //
		// Output the basic page
		//
		$page_title = $lang['Search'];
		include($phpbb_root_path . 'includes/page_header.'.$phpEx);
		include($phpbb_root_path . 'includes/kb_header.'.$phpEx);
		
		$template->set_filenames(array(
		    'body' => 'kb_search_body.tpl')
		);
		make_jumpbox('viewforum.'.$phpEx);

		$template->assign_vars(array(
		    'L_SEARCH_QUERY' => $lang['Search_query'], 
			'L_SEARCH_KEYWORDS' => $lang['Search_keywords'], 
			'L_SEARCH_KEYWORDS_EXPLAIN' => $lang['Search_keywords_explain'], 
			'L_SEARCH_ANY_TERMS' => $lang['Search_for_any'],
			'L_SEARCH_ALL_TERMS' => $lang['Search_for_all'],  

			'S_SEARCH_ACTION' => append_sid("kb_search.$phpEx?mode=results"),
			'S_HIDDEN_FIELDS' => '',
			'S_SEARCH' => $lang['Search'])
		);
			
	
	    break;		
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