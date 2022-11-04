<?php
/*
  paFileDB 3.0
  ©2001/2002 PHP Arena
  Written by Todd
  todd@phparena.net
  http://www.phparena.net
  Keep all copyright links on the script visible
  Please read the license included with this script for more information.
*/

/***************************************************************************
 *                            link_search.php
 *                           -----------------
 *   Modified by CRLin
 ***************************************************************************/

class linkdb_search extends linkdb_public
{
	function main($action)
	{
		global $template, $lang, $board_config, $phpEx, $linkdb_config, $db;
		global $images, $_REQUEST, $_POST, $phpbb_root_path, $userdata;
		
		include($phpbb_root_path . 'includes/functions_search.'.$phpEx);
		
		global $serach_LinkDB;
		include($phpbb_root_path . 'mods/linkdb/includes/functions_field.'.$phpEx);
		$custom_field = new custom_field();
		$custom_field->init();

		if ( isset($_REQUEST['search_keywords']) )
		{
			$search_keywords = htmlspecialchars($_REQUEST['search_keywords']);
		}
		else
		{
			$search_keywords = '';
		}

		$search_author = ( isset($_REQUEST['search_author']) ) ? htmlspecialchars($_REQUEST['search_author']) : '';
		
		$search_id = ( isset($_REQUEST['search_id']) ) ? intval($_REQUEST['search_id']) : 0;

		if ( isset($_REQUEST['search_terms']) )
		{
			$search_terms = ( $_REQUEST['search_terms'] == 'all' ) ? 1 : 0;
		}
		else
		{
			$search_terms = 0;
		}

		$cat_id = ( isset($_REQUEST['cat_id']) ) ? intval($_REQUEST['cat_id']) : 0;
		
		if ( isset($_REQUEST['comments_search']) )
		{
			$comments_search = ( $_REQUEST['comments_search'] == 'YES' ) ? 1 : 0;
		}
		else
		{
			$comments_search =  0;
		}

		$start = ( isset($_REQUEST['start']) ) ? intval($_REQUEST['start']) : 0;

		if( isset($_REQUEST['sort_method']) )
		{
			switch ($_REQUEST['sort_method'])
			{
				case 'link_name':
					$sort_method = 'link_name';
					break;
				case 'link_time':
					$sort_method = 'link_time';
					break;
				case 'link_hits':
					$sort_method = 'link_hits';
					break;
				case 'link_longdesc':
					$sort_method = 'link_longdesc';
					break;
				default:
					$sort_method = $linkdb_config['sort_method'];
					break;
			}
		}
		else
		{
			$sort_method = $linkdb_config['sort_method'];
		}

		if( isset($_REQUEST['sort_order']) )
		{
			switch ($_REQUEST['sort_order'])
			{
				case 'ASC':
					$sort_order = 'ASC';
					break;
				case 'DESC':
					$sort_order = 'DESC';
					break;
				default:
					$sort_order = $linkdb_config['sort_order'];
					break;
			}
		}
		else
		{
			$sort_order = $linkdb_config['sort_order'];
		}

		$limit_sql = ($start == 0) ? $linkdb_config['settings_link_page'] : $start .','. $linkdb_config['settings_link_page'];
		//
		// encoding match for workaround
		//
		$multibyte_charset = 'utf-8, big5, shift_jis, euc-kr, gb2312';

		if ( isset($_POST['submit']) ||  $search_author != '' || $search_keywords != '' || $search_id )
		{
			$store_vars = array('search_results', 'total_match_count', 'split_search', 'sort_method', 'sort_order');
			
			if($search_author != '' || $search_keywords != '')
			{
				if ( $search_author != '' && $search_keywords == '' )
				{
					$search_author = str_replace('*', '%', trim($search_author));
					
					$sql = "SELECT user_id
						FROM " . USERS_TABLE . "
						WHERE username LIKE '" . str_replace("\'", "''", $search_author) . "'";
					if ( !($result = $db->sql_query($sql)) )
					{
						message_die(GENERAL_ERROR, "Couldn't obtain list of matching users (searching for: $search_author)", "", __LINE__, __FILE__, $sql);
					}

					$matching_userids = '';
					if ( $row = $db->sql_fetchrow($result) )
					{
						do
						{
							$matching_userids .= ( ( $matching_userids != '' ) ? ', ' : '' ) . $row['user_id'];
						}
						while( $row = $db->sql_fetchrow($result) );
					}
					
					$sql = "SELECT * 
						FROM " . LINKS_TABLE . " 
						WHERE post_username LIKE '" . str_replace("\'", "''", $search_author) . "'";
					if ($matching_userids)
					{
						$sql .= " OR user_id IN ($matching_userids)";
					}
					if ( !($result = $db->sql_query($sql)) )
					{
						message_die(GENERAL_ERROR, 'Could not obtain matched links list', '', __LINE__, __FILE__, $sql);
					}

					$search_ids = array();
					while( $row = $db->sql_fetchrow($result) )
					{
						$search_ids[] = $row['link_id'];
					}
					$db->sql_freeresult($result);

					if (!$total_match_count = count($search_ids))
					{
						message_die(GENERAL_MESSAGE, $lang['No_search_match']);
					}
				}
				else if ( $search_keywords != '' )
				{
					$stopword_array = @file($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/search_stopwords.txt'); 
					$synonym_array = @file($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/search_synonyms.txt'); 
	
					$split_search = array();
					$split_search = ( !strstr($multibyte_charset, $lang['ENCODING']) ) ?  split_words(clean_words('search', stripslashes($search_keywords), $stopword_array, $synonym_array), 'search') : split(' ', $search_keywords);	

					$word_count = 0;
					$current_match_type = 'or';

					$word_match = $result_list = array();

					for($i = 0; $i < count($split_search); $i++)
					{
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
								$match_word =  addslashes('%' . str_replace('*', '', $split_search[$i]) . '%');

								$sql = "SELECT link_id 
									FROM " . LINKS_TABLE . " 
									WHERE (link_name LIKE '$match_word' 
									OR link_longdesc LIKE '$match_word')";

								if ( !($result = $db->sql_query($sql)) )
								{
									message_die(GENERAL_ERROR, 'Could not obtain matched links list', '', __LINE__, __FILE__, $sql);
								}

							$row = array();
							while( $temp_row = $db->sql_fetchrow($result) )
							{
								$row[$temp_row['link_id']] = 1;

								if ( !$word_count )
								{
									$result_list[$temp_row['link_id']] = 1;
								}
								else if ( $current_match_type == 'or' )
								{
									$result_list[$temp_row['link_id']] = 1;
								}
								else if ( $current_match_type == 'not' )
								{
									$result_list[$temp_row['link_id']] = 0;
								}
							}

							if ( $current_match_type == 'and' && $word_count )
							{
								@reset($result_list);
								while( list($file_id, $match_count) = @each($result_list) )
								{
									if ( !$row[$file_id] )
									{
										$result_list[$file_id] = 0;
									}
								}
							}
							
							if($comments_search)
							{
								$sql = "SELECT link_id 
									FROM " . LINK_COMMENTS_TABLE . " 
									WHERE (comments_title LIKE '$match_word' 
									OR comments_text LIKE '$match_word')";

								if ( !($result = $db->sql_query($sql)) )
								{
									message_die(GENERAL_ERROR, 'Could not obtain matched links list', '', __LINE__, __FILE__, $sql);
								}

								$row = array();
								while( $temp_row = $db->sql_fetchrow($result) )
								{
									$row[$temp_row['link_id']] = 1;

									if ( !$word_count )
									{
										$result_list[$temp_row['link_id']] = 1;
									}
									else if ( $current_match_type == 'or' )
									{
										$result_list[$temp_row['link_id']] = 1;
									}
									else if ( $current_match_type == 'not' )
									{
										$result_list[$temp_row['link_id']] = 0;
									}
								}

								if ( $current_match_type == 'and' && $word_count )
								{
									@reset($result_list);
									while( list($file_id, $match_count) = @each($result_list) )
									{
										if ( !$row[$file_id] )
										{
											$result_list[$file_id] = 0;
										}
									}
								}
							}

							$word_count++;

							$db->sql_freeresult($result);
						}
					}
					@reset($result_list);

					$search_ids = array();
					while( list($file_id, $matches) = each($result_list) )
					{
						if ( $matches )
						{
							$search_ids[] = $file_id;
						}
					}	
			
					unset($result_list);
					$total_match_count = count($search_ids);
				}

				//
				// Author name search 
				//
				if ( $search_author != '' )
				{
					$search_author = str_replace('*', '%', trim(str_replace("\'", "''", $search_author)));
				}	

				if ( $total_match_count )
				{			
					$where_sql = ($cat_id) ? 'AND link_catid IN (' . $this->gen_cat_ids($cat_id, '') . ')' : '';

					if ( $search_author == '')
					{
						$sql = "SELECT link_id, link_catid 
							FROM " . LINKS_TABLE . "
							WHERE link_id IN (" . implode(", ", $search_ids) . ") 
								$where_sql 
							GROUP BY link_id";
					}
					else
					{
						$from_sql = LINKS_TABLE . " f"; 
						if ( $search_author != '' )
						{
							$from_sql .= ", " . USERS_TABLE . " u";
							$where_sql .= " AND u.user_id = f.user_id AND ( u.username LIKE '$search_author' OR f.post_username LIKE '$search_author' ) ";
						}
					
						$where_sql .= ($cat_id) ? 'AND link_catid IN (' . $this->gen_cat_ids($cat_id, '') . ')' : '';

						$sql = "SELECT f.link_id, f.link_catid
							FROM $from_sql 
							WHERE f.link_id IN (" . implode(", ", $search_ids) . ") 
							$where_sql 
							GROUP BY f.link_id";
					}

					if ( !($result = $db->sql_query($sql)) )
					{
						message_die(GENERAL_ERROR, 'Could not obtain link ids', '', __LINE__, __FILE__, $sql);
					}

					$search_ids = array();
					while( $row = $db->sql_fetchrow($result) )
					{
						$search_ids[] = $row['link_id'];
					}
					$db->sql_freeresult($result);				
					$total_match_count = sizeof($search_ids);
				}
				else
				{
					message_die(GENERAL_MESSAGE, $lang['No_search_match']);
				}
			
				//
				// Finish building query (for all combinations)
				// and run it ...
				//
				$expiry_time = $current_time - $board_config['session_length'];
				$sql = "SELECT session_id
					FROM " . SESSIONS_TABLE ." 
					WHERE session_time > $expiry_time";

				if ( $result = $db->sql_query($sql) )
				{
					$delete_search_ids = array();
					while( $row = $db->sql_fetchrow($result) )
					{
						$delete_search_ids[] = "'" . $row['session_id'] . "'";
					}

					if ( count($delete_search_ids) )
					{
						$sql = "DELETE FROM " . SEARCH_TABLE . " 
							WHERE session_id NOT IN (" . implode(", ", $delete_search_ids) . ")";
						if ( !$result = $db->sql_query($sql) )
						{
							message_die(GENERAL_ERROR, 'Could not delete old search id sessions', '', __LINE__, __FILE__, $sql);
						}
					}
				}
			
				//
				// Store new result data
				//
				$search_results = implode(', ', $search_ids);
	
				$store_search_data = array();
			
				for($i = 0; $i < count($store_vars); $i++)
				{
					$store_search_data[$store_vars[$i]] = $$store_vars[$i];
				}

				$result_array = serialize($store_search_data);
				unset($store_search_data);

				mt_srand ((double) microtime() * 1000000);
				$search_id = mt_rand();

				$sql = "UPDATE " . SEARCH_TABLE . " 
					SET search_id = $search_id, search_array = '" . str_replace("\'", "''", $result_array) . "'
					WHERE session_id = '" . $userdata['session_id'] . "'";
				if ( !($result = $db->sql_query($sql)) || !$db->sql_affectedrows() )
				{
					$sql = "INSERT INTO " . SEARCH_TABLE . " (search_id, session_id, search_array) 
						VALUES($search_id, '" . $userdata['session_id'] . "', '" . str_replace("\'", "''", $result_array) . "')";
					if ( !($result = $db->sql_query($sql)) )
					{
						message_die(GENERAL_ERROR, 'Could not insert search results', '', __LINE__, __FILE__, $sql);
					}
				}
			}
			else
			{
				$search_id = intval($search_id);
				if ( $search_id )
				{
					$sql = "SELECT search_array 
						FROM " . SEARCH_TABLE . " 
						WHERE search_id = $search_id  
						AND session_id = '" . $userdata['session_id'] . "'";
					if ( !($result = $db->sql_query($sql)) )
					{
						message_die(GENERAL_ERROR, 'Could not obtain search results', '', __LINE__, __FILE__, $sql);
					}

					if ( $row = $db->sql_fetchrow($result) )
					{
						$search_data = unserialize($row['search_array']);
						for($i = 0; $i < count($store_vars); $i++)
						{
							$$store_vars[$i] = $search_data[$store_vars[$i]];
						}
					}
				}
			}

		
			if ( $search_results != '' )
			{	
				$serach_LinkDB = TRUE;
						$sql = "SELECT f1.*, AVG(r.rate_point) AS rating, COUNT(r.votes_link) AS total_votes, u.user_id, u.username, u.user_level, c.cat_id, c.cat_name, COUNT(DISTINCT cm.comments_id) AS total_comments
							FROM " . LINKS_TABLE . " AS f1
								LEFT JOIN " . LINK_CATEGORIES_TABLE . " AS c  ON f1.link_catid = c.cat_id
								LEFT JOIN " . LINK_VOTES_TABLE . " AS r ON f1.link_id = r.votes_link
								LEFT JOIN ". USERS_TABLE ." AS u ON f1.user_id = u.user_id
								LEFT JOIN " . LINK_COMMENTS_TABLE . " AS cm ON f1.link_id = cm.link_id
							WHERE f1.link_id IN ($search_results) 
								AND f1.link_approved = '1' 
							GROUP BY f1.link_id 
							ORDER BY $sort_method $sort_order 
							LIMIT $limit_sql";
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
			
				$l_search_matches = ( $total_match_count == 1 ) ? sprintf($lang['Found_search_match'], $total_match_count) : sprintf($lang['Found_search_matches'], $total_match_count);
			
				$template->assign_vars(array(
					'L_SEARCH_MATCHES' => $l_search_matches)
);

				if(!$linkdb_config['split_links'])
				{
					$template->assign_block_vars("no_split_links", array());
				}
				for($i = 0; $i < count($searchset); $i++)
				{
					$cat_url = append_sid('linkdb.'.$phpEx.'?action=category&cat_id=' . $searchset[$i]['cat_id']);
					$file_url = append_sid('linkdb.'.$phpEx.'?action=link&link_id=' . $searchset[$i]['link_id']);
					//
					// Format the date for the given file
					//

					$date = create_date($board_config['default_dateformat'], $searchset[$i]['link_time'], $board_config['board_timezone']);

					//
					// Get rating for the file and format it
					//

					$rating = ($searchset[$i]['rating'] != 0) ? round($searchset[$i]['rating'], 2) . '/10' : $lang['Not_rated'];
		
					//
					// If the file is new then put a new image in front of it
					//
		
					/*$is_new = FALSE;
					if (time() - ($linkdb_config['settings_newdays'] * 24 * 60 * 60) < $searchset[$i]['file_time'])
					{
						$is_new = TRUE;
					}*/
					
					$Sticky = ($searchset[$i]['link_pin'] == LINK_PINNED) ? $lang['Link_Sticky'] : '';

					$poster = ( $searchset[$i]['user_id'] != ANONYMOUS ) ? '<a href="' . append_sid('profile.'.$phpEx.'?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $searchset[$i]['user_id']) . '">' : '';
					$poster .= ( $searchset[$i]['user_id'] != ANONYMOUS ) ? username_level_color($searchset[$i]['username'], $searchset[$i]['user_level'], $searchset[$i]['user_id']) : $searchset[$i]['post_username'].'('.$lang['Guest'].')';
					$poster .= ( $searchset[$i]['user_id'] != ANONYMOUS ) ? '</a>' : '';

					$template->assign_block_vars('searchresults', array(
						'COLOR' => ($linkdb_config['split_links']) ? "row1" : (($i % 2) ? "row2" : "row1"),
						
						'CAT_NAME' => $searchset[$i]['cat_name'],
						'FILE_NEW_IMAGE' => $images['pa_file_new'],
						'LINK_LOGO' => $this->display_banner($searchset[$i], $row),
						'RECOM_LINK' => $Sticky,

						//'IS_NEW_FILE' => $is_new,
						'FILE_NAME' => $searchset[$i]['link_name'],
						'FILE_DESC' => $searchset[$i]['link_longdesc'],
						'FILE_SUBMITER' => $poster,
						'DATE' => $date,
						'FILE_DLS' => $searchset[$i]['link_hits'],
						'L_RATING' => '<a href="' . append_sid('linkdb.'.$phpEx.'?action=rate&amp;link_id=' . $searchset[$i]['link_id'] ) . '">'.$lang['LinkRating'].'</a>',
						'RATING' => $rating,
						'FILE_VOTES' => $searchset[$i]['total_votes'],
						'L_COMMENTS' => '<a href="' . append_sid('linkdb.'.$phpEx.'?action=comment&link_id=' . $searchset[$i]['link_id'] ) . '">'.$lang['Comments'].'</a>',
						'FILE_COMMENTS' => $searchset[$i]['total_comments'],
						'U_DELETE' => append_sid('linkdb.'.$phpEx.'?action=user_upload&do=delete&link_id=' . $searchset[$i]['link_id']),
						'U_EDIT' => append_sid('linkdb.'.$phpEx.'?action=user_upload&link_id=' . $searchset[$i]['link_id']),
						'U_FILE' => $file_url,
						'U_CAT' => $cat_url)
					);
					$custom_field->display_data($searchset[$i]['link_id']);

					if($linkdb_config['allow_vote'])
					{
						$template->assign_block_vars("searchresults.LINK_VOTE", array());
					}
					if($linkdb_config['allow_comment'])
					{
						$template->assign_block_vars("searchresults.LINK_COMMENT", array());
					}
					//if(($linkdb_config['allow_edit_link']  && $searchset[$i]['user_id'] != ANONYMOUS && $searchset[$i]['user_id'] == $userdata['user_id']) || $userdata['user_level'] == ADMIN)
					//{
						$template->assign_block_vars("searchresults.AUTH_EDIT", array());
					//}
					if(($linkdb_config['allow_delete_link'] && $searchset[$i]['user_id'] != ANONYMOUS && $searchset[$i]['user_id'] == $userdata['user_id']) || $userdata['user_level'] == ADMIN)
					{
						$template->assign_block_vars("searchresults.AUTH_DELETE", array());
					}
					if($linkdb_config['split_links'])
					{
						$template->assign_block_vars("searchresults.split_links", array());
					}
				}
				$base_url = append_sid("linkdb.$phpEx?action=search&amp;search_id=$search_id");

				$template->assign_vars(array(
					'PAGINATION' => generate_pagination($base_url, $total_match_count, $linkdb_config['settings_link_page'], $start),
					'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / $linkdb_config['settings_link_page'] ) + 1 ), ceil( $total_match_count / $linkdb_config['settings_link_page'] )),
					'LINKS' => $lang['Linkdb'],
	
					'U_INDEX' => append_sid('index.'.$phpEx),
					'U_LINK' => append_sid('linkdb.'.$phpEx),

					'L_INDEX' => sprintf($lang['Forum_Index'], $board_config['sitename']),
					
					'L_DOWNLOADS' => $lang['Hits'],
					'L_DATE' => $lang['Date'],
					'L_NAME' => $lang['Sitename'],
					'L_FILE' => $lang['Link'],
					'L_SUBMITER' => $lang['Submiter'],
					'L_VOTES' => $lang['Votes'],
					
					'L_EDIT' => $lang['Editlink'],
					'L_DELETE' => $lang['Deletelink'],
					'DELETE_IMG' => $images['icon_delpost'],
					'EDIT_IMG' => $images['icon_edit'],
				
					'L_CATEGORY' => $lang['Category']
				));
				
				$this->display($lang['Linkdb'] . ' :: ' . $lang['Link_Search'], 'link_search_result.tpl');
				return;
			}
			else
			{
				message_die(GENERAL_MESSAGE, $lang['No_search_match']);
			}
		}
		
		$dropmenu = $this->jumpmenu_option();

		$template->assign_vars(array(
			'S_SEARCH_ACTION' => append_sid('linkdb.'.$phpEx),
			'S_CAT_MENU' => $dropmenu,

			'LINKS' => $lang['Linkdb'],
	
			'U_INDEX' => append_sid('index.'.$phpEx),
			'U_LINK' => append_sid('linkdb.'.$phpEx),

			'L_YES' => $lang['Yes'],
			'L_NO' => $lang['No'],
			'L_SEARCH_OPTIONS' => $lang['Search_options'], 
			'L_SEARCH_KEYWORDS' => $lang['Search_keywords'], 
			'L_SEARCH_KEYWORDS_EXPLAIN' => $lang['Search_keywords_explain'], 
			'L_SEARCH_AUTHOR' => $lang['Search_author'],
			'L_SEARCH_AUTHOR_EXPLAIN' => $lang['Search_author_explain'], 
			'L_SEARCH_ANY_TERMS' => $lang['Search_for_any'],
			'L_SEARCH_ALL_TERMS' => $lang['Search_for_all'], 
			'L_INCLUDE_COMMENTS' => $lang['Include_comments'],
			'L_SORT_BY' => $lang['Select_sort_method'],
			'L_SORT_DIR' => $lang['Order'],
			'L_SORT_ASCENDING' => $lang['Sort_Ascending'],
			'L_SORT_DESCENDING' => $lang['Sort_Descending'],
			'L_INDEX' => sprintf($lang['Forum_Index'], $board_config['sitename']),
			'L_RATING' => $lang['Siteld'],
			'L_DOWNLOADS' => $lang['Hits'],
			'L_DATE' => $lang['Date'],
			'L_NAME' => $lang['Sitename'],
			'L_SEARCH_FOR' => $lang['Search_for'],
			'L_ALL' => $lang['All'],
			'L_CHOOSE_CAT' => $lang['Choose_cat'])
		);         
		$this->display($lang['Linkdb'] . ' :: ' . $lang['Link_Search'], 'link_search_body.tpl');
	}
}

?>