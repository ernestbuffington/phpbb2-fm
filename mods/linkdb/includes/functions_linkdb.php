<?php
/***************************************************************************
 *                              functions_linkdb.php
 *                            ------------------------
 *   begin                : Thursday, Mar 2, 2003
 *   copyright            : (C) 2003 Mohd
 *   email                : mohdalbasri@hotmail.com
 *   Modified by CRLin 
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

if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}

//
// public linkdb class
// 

class linkdb_public extends linkdb
{
	var $modules = array();
	var $module_name = '';

	//
	// load module
	// $module name : send module name to load it
	//
	
	function adminmodule($module_name)
	{
		if (!class_exists('linkdb_' . $module_name))
		{
			global $phpbb_root_path, $phpEx;
			
			$this->module_name = $module_name;
			
			require_once($phpbb_root_path . 'mods/linkdb/admin/admin_' . $module_name . '.'.$phpEx);
			eval('$this->modules[' . $module_name . '] = new linkdb_' . $module_name . '();');

			if (method_exists($this->modules[$module_name], 'init'))
			{
				$this->modules[$module_name]->init();
			}
		}
	}
	
	function module($module_name)
	{
		if (!class_exists('linkdb_' . $module_name))
		{
			global $phpbb_root_path, $phpEx;
			
			$this->module_name = $module_name;
			
			require_once($phpbb_root_path . 'mods/linkdb/modules/link_' . $module_name . '.'.$phpEx);
			eval('$this->modules[' . $module_name . '] = new linkdb_' . $module_name . '();');

			if (method_exists($this->modules[$module_name], 'init'))
			{
				$this->modules[$module_name]->init();
			}
		}
	}
	
	//
	// this will be replaced by the loaded module
	// 

	function main($module_id = false)
	{
		return false;
	}
	
	//
	// go ahead and output the page
	// $page title : send page title
	// $tpl_name : template file name
	//

	function display($page_title1, $tpl_name)
	{
		global $page_title, $linkdb_tpl_name;
		
		$page_title = $page_title1;
		$linkdb_tpl_name = $tpl_name;
	}
}

//
// linkdb class
//

class linkdb
{
	var $cat_rowset = array();
	var $subcat_rowset = array();

	var $modified = false;

	var $auth = array();
	var $auth_global = array();
	
	var $total_cat = 0;
//	var $depth_info = array();
	
	var $error = array();


	function init()
	{
		global $db, $userdata, $debug;
		
		unset($this->cat_rowset);
		unset($this->subcat_rowset);

		$sql = 'SELECT * 
			FROM ' . LINK_CATEGORIES_TABLE . '
			ORDER BY cat_order ASC';
	
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Couldnt Query categories info', '', __LINE__, __FILE__, $sql);
		}
		$cat_rowset = $db->sql_fetchrowset($result);

		$db->sql_freeresult($result);
		
		for( $i = 0; $i < count($cat_rowset); $i++ )
		{
			$this->cat_rowset[$cat_rowset[$i]['cat_id']] = $cat_rowset[$i];
			$this->subcat_rowset[$cat_rowset[$i]['cat_parent']][$cat_rowset[$i]['cat_id']] = $cat_rowset[$i];
			$this->total_cat++;
		}
	}
	
	//
	// Jump menu function
	// $cat_id : to handle parent cat_id
	// $depth : related to function to generate tree
	// $default : the cat you wanted to be selected
	// $for_file: TRUE high category ids will be -1
	// $check_upload: if true permission for upload will be checked
	//
	
	function jumpmenu_option($cat_id = 0, $depth = 0, $default = '', $for_file = false, $check_upload = false)
	{
		static $cat_rowset = false;

		if(!is_array($cat_rowset))
		{
			if($check_upload)
			{
				if(!empty($this->cat_rowset))
				{
					foreach($this->cat_rowset as $row)
					{
						$cat_rowset[$row['cat_id']] = $row;
					}
				}
			}
			else
			{
				$cat_rowset = $this->cat_rowset;
			}
		}
		
		$cat_list .= '';

		$pre = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $depth);

		$temp_cat_rowset = $cat_rowset;

		if(!empty($temp_cat_rowset))
		{
			foreach ($temp_cat_rowset as $temp_cat_id => $cat)
			{
				if ($cat['cat_parent'] == $cat_id)
				{
					if (is_array($default))
					{
						if (isset($default[$cat['cat_id']]))
						{
							$sel = ' selected="selected"';
						}
						else
						{
							$sel = '';
						}
					}

					$cat_pre = '- ';
					$sub_cat_id = $cat['cat_id'];
					$cat_class = '';
					$cat_list .= '<option value="' . $sub_cat_id . '"' . $sel . ' ' . $cat_class . ' />' . $pre . $cat_pre . $cat['cat_name'] . '</option>';
                    $cat_list .= $this->jumpmenu_option($cat['cat_id'], $depth + 1, $default, $for_file, $check_upload);
				}
			}
			return $cat_list;
		}
		else
		{
			return;
		}
	}
	
	//
	// if there is no cat
	//
	
	function cat_empty()
	{
		return ($this->total_cat == 0) ? TRUE : FALSE;
	}
	
	
	function modified($true_false = false)
	{
		$this->modified = $true_false;
	}

	/*function last_file_in_cat($cat_id, &$file_info)
	{
		if((empty($this->cat_rowset[$cat_id]['cat_last_file_id']) && empty($this->cat_rowset[$cat_id]['cat_last_file_name']) && empty($this->cat_rowset[$cat_id]['cat_last_file_time'])) || $this->modified)
		{
			global $db;

			$sql = 'SELECT file_time, file_id, file_name, file_catid
				FROM ' . LINKS_TABLE . " 
				WHERE file_approved = '1' 
				AND file_catid IN (" . $this->gen_cat_ids($cat_id) . ")
				ORDER BY file_time DESC";

			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Couldnt Query Files info', '', __LINE__, __FILE__, $sql);
			}

			while($row = $db->sql_fetchrow($result))
			{
				$temp_cat[] = $row;
			}

			$file_info = $temp_cat[0];
			if(!empty($file_info))
			{
				$sql = 'UPDATE ' . LINK_CATEGORIES_TABLE . "
					SET cat_last_file_id = " . intval($file_info['file_id']) . ", 
					cat_last_file_name = '" . addslashes($file_info['file_name']) . "', 
					cat_last_file_time = " . intval($file_info['file_time']) . "
					WHERE cat_id = $cat_id";

				if ( !($db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'Couldnt Query Files info', '', __LINE__, __FILE__, $sql);
				}
			}
		}
		else
		{
			$file_info['file_id'] = $this->cat_rowset[$cat_id]['cat_last_file_id'];
			$file_info['file_name'] = $this->cat_rowset[$cat_id]['cat_last_file_name'];
			$file_info['file_time'] = $this->cat_rowset[$cat_id]['cat_last_file_time'];
		}
	}*/

	function generate_category_nav($cat_id)
	{
		global $template, $db, $phpEx;
		
		if($this->cat_rowset[$cat_id]['parents_data'] == '')
		{
			$cat_nav = array();
			$this->category_nav($this->cat_rowset[$cat_id]['cat_parent'], &$cat_nav);

			$sql = 'UPDATE ' . LINK_CATEGORIES_TABLE . "
				SET parents_data = '" . addslashes(serialize($cat_nav)) . "'
				WHERE cat_parent = " . $this->cat_rowset[$cat_id]['cat_parent'];

			if ( !($db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Couldnt Query categories info', '', __LINE__, __FILE__, $sql);
			}
		}
		else
		{
			$cat_nav = unserialize(stripslashes($this->cat_rowset[$cat_id]['parents_data']));
		}
		
		if(!empty($cat_nav))
		{
			foreach ($cat_nav as $parent_cat_id => $parent_name)
			{
				$template->assign_block_vars('navlinks', array(
					'CAT_NAME'	=>	$parent_name,
					'U_VIEW_CAT'	=>	append_sid('linkdb.'.$phpEx.'?action=category&cat_id=' . $parent_cat_id))
				);
			}
		}

		$template->assign_block_vars('navlinks', array(
			'CAT_NAME'	=>	$this->cat_rowset[$cat_id]['cat_name'],
			'U_VIEW_CAT'	=>	append_sid('linkdb.'.$phpEx.'?action=category&cat_id=' . $this->cat_rowset[$cat_id]['cat_id']))
		);
		
		return;
	}

	function category_nav($parent_id, &$cat_nav)
	{
		if(!empty($this->cat_rowset[$parent_id]))
		{
			$this->category_nav($this->cat_rowset[$parent_id]['cat_parent'], &$cat_nav);
			$cat_nav[$parent_id] = $this->cat_rowset[$parent_id]['cat_name'];
		}
		return;		
	}
	
	function file_in_cat($cat_id)
	{
		if($this->cat_rowset[$cat_id]['cat_links'] == -1 || $this->modified)
		{
			global $db;
		
			$sql = 'SELECT COUNT(link_id) as total_files
				FROM ' . LINKS_TABLE . " 
				WHERE link_approved = '1' 
				AND link_catid IN (" . $this->gen_cat_ids($cat_id) . ')
				ORDER BY link_time DESC';

			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Couldnt Query Files info', '', __LINE__, __FILE__, $sql);
			}

			$files_no = 0;
			if($row = $db->sql_fetchrow($result))
			{
				$files_no = $row['total_files'];
			}

			$sql = 'UPDATE ' . LINK_CATEGORIES_TABLE . "
					SET cat_links = $files_no
					WHERE cat_id = $cat_id";

			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Couldnt Query Files info', '', __LINE__, __FILE__, $sql);
			}
		}
		else
		{
			$files_no = $this->cat_rowset[$cat_id]['cat_links'];
		}
		

		return $files_no;
	}

	function gen_cat_ids($cat_id, $cat_ids = '')
	{
		if(!empty($this->subcat_rowset[$cat_id]))
		{
			foreach($this->subcat_rowset[$cat_id] as $subcat_id => $cat_row)
			{
				$cat_ids = $this->gen_cat_ids($subcat_id, $cat_ids);
			}
		}
		
		if(!empty($this->cat_rowset[$cat_id]))
		{
			$cat_ids .= ( ( $cat_ids != '' ) ? ', ' : '' ) . $cat_id;
		}
		return $cat_ids;
	}

	function category_display($cat_id = LINKDB_ROOT_CAT)
	{
		global $db, $template, $lang, $userdata, $phpEx, $images;
		global $linkdb_config, $board_config, $debug, $phpbb_root_path;
		
		if($this->cat_empty())
		{
			if ( !$userdata['session_logged_in'] )
			{
				$redirect = ($cat_id != LINKDB_ROOT_CAT) ? "linkdb.$phpEx?action=category&cat_id=$cat_id" : "linkdb.$phpEx";
				redirect(append_sid("login.$phpEx?redirect=$redirect", true));
			}
			message_die(GENERAL_ERROR, 'Either you are not allowed to view any category, or there is no category in the database');
		}

		$template->assign_block_vars('CAT_PARENT', array());

		$template->assign_vars(array(
			'L_SUB_CAT' => $lang['Sub_category'],
			'L_CATEGORY' => $lang['Category'],
			'L_FILES' => $lang['Files'])
		);

		//output the root level category
		if(isset($this->subcat_rowset[$cat_id]))
		{
			//
			// Separate link categories into $catcol columns, script by CRLin
			//
			$catnum = count($this->subcat_rowset[$cat_id]);
			$catcol = $linkdb_config['cat_col'];
			$num = intval($catnum/$catcol);
			if ($catnum % $catcol ) $num++;
			
			$template->assign_vars(array('LINK_WIDTH' => 100/$catcol));

			for( $i = 0;$i < $num; $i++)
			{
				$template->assign_block_vars('CAT_PARENT.catcol', array());
				if ( ($catnum % $catcol) && ($i==$num-1) ) $catcol = $catnum % $catcol;
				for( $j = 0;$j < $catcol; $j++)
				{
					list($subcat_id, $subcat_row) = each ($this->subcat_rowset[$cat_id]);
					
					$template->assign_block_vars('CAT_PARENT.catcol.no_cat_parent', array(
						'U_CAT' => append_sid('linkdb.'.$phpEx.'?action=category&cat_id=' . $subcat_id),
						//'SUB_CAT' => ( !empty($sub_cat) ) ? $sub_cat : $lang['None'],
						'CAT_IMAGE' => $images['folder'],
						'CAT_NAME' => $subcat_row['cat_name'],
						'FILECAT' => $this->file_in_cat($subcat_id))
					);
					$this->get_sub_cat($subcat_id);
				}
			}
		}
	}

	//
	// get all sub category in side certain category
	// $cat_id : category id
	//

	function get_sub_cat($cat_id)
	{
		global $template, $phpEx;

		if(!empty($this->subcat_rowset[$cat_id]))
		{
			$template->assign_block_vars('CAT_PARENT.catcol.no_cat_parent.SUB_CAT', array());
			$cat_id1 = $cat_id;
			$init_link_max1 = count($this->subcat_rowset[$cat_id]);
			$init_link_max = ( $init_link_max1 > 3 ) ? 3 : $init_link_max1;
			$i = 0;
			foreach($this->subcat_rowset[$cat_id] as $cat_id => $cat_row)
			{
				$template->assign_block_vars('CAT_PARENT.catcol.no_cat_parent.SUB_CAT.GEN_SUB_CAT',array(
					'U_SUB_CAT' => append_sid('linkdb.'.$phpEx.'?action=category&cat_id=' . $cat_row['cat_id']),
					'GEN_SUB_CAT' => $cat_row['cat_name'])
				);
				$i++;
				
				if ($i < $init_link_max)
				{
					$template->assign_block_vars('CAT_PARENT.catcol.no_cat_parent.SUB_CAT.GEN_SUB_CAT',array('L_COMMA' => ','));
				}
				else
				{
					if ( $init_link_max != $init_link_max1 )
					{
						$template->assign_block_vars('CAT_PARENT.catcol.no_cat_parent.SUB_CAT.GEN_SUB_CAT',array('L_COMMA' => ','));	$template->assign_block_vars('CAT_PARENT.catcol.no_cat_parent.SUB_CAT.GEN_SUB_CAT',array(
							'U_SUB_CAT' => append_sid('linkdb.'.$phpEx.'?action=category&cat_id=' . $cat_id1),
							'GEN_SUB_CAT' => '...')
						);
					}
					break;
				}
			}
		}
	}
	
	function display_files($sort_method, $sort_order, $start, $show_file_message, $cat_id = false)
	{
		global $db, $linkdb_config, $template, $board_config, $userdata;
		global $images, $lang, $phpEx, $linkdb_functions, $phpbb_root_path, $custom_field;

		if(empty($cat_id))
		{
			$cat_where = '';
		}
		else
		{
			$cat_where = "AND f1.link_catid = $cat_id";
		}

		$sql = "SELECT f1.*, AVG(r.rate_point) AS rating, COUNT(r.votes_link) AS total_votes, u.user_id, u.username, u.user_level, COUNT(DISTINCT c.comments_id) AS total_comments
			FROM " . LINKS_TABLE . " AS f1
				LEFT JOIN " . LINK_VOTES_TABLE . " AS r ON f1.link_id = r.votes_link
				LEFT JOIN " . USERS_TABLE . " AS u ON f1.user_id = u.user_id
				LEFT JOIN " . LINK_COMMENTS_TABLE . " AS c ON f1.link_id = c.link_id
			WHERE f1.link_pin = " . LINK_PINNED . "
				AND f1.link_approved = 1
			$cat_where
			GROUP BY f1.link_id 
			ORDER BY $sort_method $sort_order";	
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Couldn\'t get file info for this category', '', __LINE__, __FILE__, $sql);
		}
		
		$file_rowset = array();
		$total_file = 0;
		
		while($row = $db->sql_fetchrow($result))
		{
			$file_rowset[] = $row;
		}
		
		$db->sql_freeresult($result);

		$sql = "SELECT f1.*, AVG(r.rate_point) AS rating, COUNT(r.votes_link) AS total_votes, u.user_id, u.username, u.user_level, COUNT(DISTINCT c.comments_id) AS total_comments
			FROM " . LINKS_TABLE . " AS f1
				LEFT JOIN " . LINK_VOTES_TABLE . " AS r ON f1.link_id = r.votes_link
				LEFT JOIN " . USERS_TABLE . " AS u ON f1.user_id = u.user_id
				LEFT JOIN " . LINK_COMMENTS_TABLE . " AS c ON f1.link_id = c.link_id
			WHERE f1.link_pin <> " . LINK_PINNED . "
				AND f1.link_approved = 1
			$cat_where
			GROUP BY f1.link_id 
			ORDER BY $sort_method $sort_order";		
		if ( !($result = $linkdb_functions->sql_query_limit($sql, $linkdb_config['settings_link_page'], $start)) )
		{
			message_die(GENERAL_ERROR, 'Couldn\'t get file info for this category', '', __LINE__, __FILE__, $sql);
		}
		
		while($row = $db->sql_fetchrow($result))
		{
			$file_rowset[] = $row;
		}
	
		$db->sql_freeresult($result);

		$where_sql = (!empty($cat_id)) ? "AND link_catid = $cat_id" : '';
		$sql = "SELECT COUNT(link_id) as total_file
			FROM " . LINKS_TABLE . " 
			WHERE link_approved='1'
			$where_sql";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Couldn\'t get number of link', '', __LINE__, __FILE__, $sql);
		}
		
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);
		
		$total_file = $row['total_file'];
		unset($row);
		
		if (count($file_rowset)) 
		{
			$template->assign_block_vars('FILELIST', array());

			$action =  (empty($cat_id)) ? 'viewall' : 'category&amp;cat_id='.$cat_id;
			$template->assign_vars(array(
				'L_CATEGORY' => $lang['Category'],
				'L_LINK_SITE_DESC' => $lang['Siteld'],
				'L_DOWNLOADS' => $lang['Hits'],
				'L_POSTED' => $lang['Posted'],
				'L_DATE' => $lang['Date'],
				'L_NAME' => $lang['Sitename'],
				'L_FILE' => $lang['Link'],
				'L_SUBMITED_BY' => $lang['Submiter'],
				'L_VOTES' => $lang['Votes'],
				'L_EDIT' => $lang['Editlink'],
				'L_DELETE' => $lang['Deletelink'],
				'DELETE_IMG' => $images['icon_delpost'],
				'EDIT_IMG' => $images['icon_edit'],
					
				'L_SELECT_SORT_METHOD' => $lang['Select_sort_method'],
				'L_ORDER' => $lang['Order'],
				'L_SORT' => $lang['Sort'],

				'L_ASC' => $lang['Sort_Ascending'],
				'L_DESC' => $lang['Sort_Descending'],
		
				'SORT_NAME' => ($sort_method == 'link_name') ? 'selected="selected"' : '',
				'SORT_TIME' => ($sort_method == 'link_time') ? 'selected="selected"' : '',
				'SORT_LONGDESC' => ($sort_method == 'link_longdesc') ? 'selected="selected"' : '',
				'SORT_DOWNLOADS' => ($sort_method == 'link_hits') ? 'selected="selected"' : '',
				
				'SORT_ASC' => ($sort_order == 'ASC') ? 'selected="selected"' : '',
				'SORT_DESC' => ($sort_order == 'DESC') ? 'selected="selected"' : '',
				'PAGINATION' => generate_pagination(append_sid("linkdb.$phpEx?action=$action&amp;sort_method=$sort_method&amp;sort_order=$sort_order"), $total_file, $linkdb_config['settings_link_page'], $start),
				'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / $linkdb_config['settings_link_page'] ) + 1 ), ceil( $total_file / $linkdb_config['settings_link_page'] )),
				'ID' => $cat_id,
				'START' => $start,

				'S_ACTION_SORT' => append_sid("linkdb.$phpEx?action=$action")) 
			);

			if(!$linkdb_config['split_links'])
			{
				$template->assign_block_vars("FILELIST.no_split_links", array());
			}

			for ($i = 0; $i < count($file_rowset); $i++) 
			{
				//
				// Format the date for the given file
				//

				$date = create_date($board_config['default_dateformat'], $file_rowset[$i]['link_time'], $board_config['board_timezone']);
		
				//
				// If the file is new then put a new image in front of it
				//
		
				/*$is_new = FALSE;
				if (time() - ($linkdb_config['settings_newdays'] * 24 * 60 * 60) < $file_rowset[$i]['link_time'])
				{
					$is_new = TRUE;
				}*/
				
				$cat_name = (empty($cat_id)) ? $this->cat_rowset[$file_rowset[$i]['link_catid']]['cat_name'] : '';
				$cat_url = append_sid('linkdb.'.$phpEx.'?action=category&cat_id=' . $file_rowset[$i]['link_catid']);
				$Sticky = ($file_rowset[$i]['link_pin'] == LINK_PINNED) ? $lang['Link_Sticky'] : '';

				$file_poster = ( $file_rowset[$i]['user_id'] != ANONYMOUS ) ? '<a href="' . append_sid('profile.'.$phpEx.'?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $file_rowset[$i]['user_id']) . '" class="postdetails">' : '';
				$file_poster .= ( $file_rowset[$i]['user_id'] != ANONYMOUS ) ? username_level_color($file_rowset[$i]['username'], $file_rowset[$i]['user_level'], $file_rowset[$i]['user_id']) : $file_rowset[$i]['post_username'] . ' (' . $lang['Guest'] . ')';
				$file_poster .= ( $file_rowset[$i]['user_id'] != ANONYMOUS ) ? '</a>' : '';
	
				$file_rating = ($file_rowset[$i]['rating'] != 0) ? round($file_rowset[$i]['rating'], 2) . '/10' : $lang['Not_rated'];
				
				//
				// Assign Vars
				//
				$template->assign_block_vars("FILELIST.file_rows", array(
					'COLOR' => ($linkdb_config['split_links']) ? "row1" : (($i % 2) ? "row2" : "row1"),
					'L_NEW_FILE' => $lang['New_file'],

					'LINK_LOGO' => $this->display_banner($file_rowset[$i], $row),
					'RECOM_LINK' => $Sticky,
					'FILE_NEW_IMAGE' => $phpbb_root_path . $images['pa_file_new'],
					'FILE_NAME' => $file_rowset[$i]['link_name'],
					'FILE_DESC' => $file_rowset[$i]['link_longdesc'],
					'DATE' => $date,
					'POSTER' => $file_poster,
					'FILE_DLS' => $file_rowset[$i]['link_hits'],
					'FILE_VOTES' => $file_rowset[$i]['total_votes'],
					'L_RATING' => '<a href="' . append_sid('linkdb.'.$phpEx.'?action=rate&amp;link_id=' . $file_rowset[$i]['link_id'] ) . '" class="postdetails">' . $lang['LinkRating'] . '</a>',
					'RATING' => $file_rating,
					'L_COMMENTS' => '<a href="' . append_sid('linkdb.'.$phpEx.'?action=comment&amp;link_id=' . $file_rowset[$i]['link_id'] ) . '" class="postdetails">' . $lang['Comments'] . '</a>',
					'FILE_COMMENTS' => $file_rowset[$i]['total_comments'],
					'POST_IMAGE' => $images['icon_minipost'],
					'POST_IMAGE_ALT' => $lang['Post'],
					
					'U_DELETE' => append_sid('linkdb.'.$phpEx.'?action=user_upload&do=delete&amp;link_id=' . $file_rowset[$i]['link_id']),
					'U_EDIT' => append_sid('linkdb.'.$phpEx.'?action=user_upload&amp;link_id=' . $file_rowset[$i]['link_id']),

					'CAT_NAME' => $cat_name,
					'IS_NEW_FILE' => $is_new,

					'U_CAT' => $cat_url,
					'U_FILE' => append_sid('linkdb.'.$phpEx.'?action=link&amp;link_id=' . $file_rowset[$i]['link_id']))
				);
				$custom_field->display_data($file_rowset[$i]['link_id']);

				if($linkdb_config['allow_vote'])
				{
					$template->assign_block_vars("FILELIST.file_rows.LINK_VOTE", array());
				}
				if($linkdb_config['allow_comment'])
				{
					$template->assign_block_vars("FILELIST.file_rows.LINK_COMMENT", array());
				}
				/*if(($linkdb_config['allow_edit_link']  && $file_rowset[$i]['user_id'] != ANONYMOUS && $file_rowset[$i]['user_id'] == $userdata['user_id']) || $userdata['user_level'] == ADMIN)
				{
					$template->assign_block_vars("FILELIST.file_rows.AUTH_EDIT", array());
				}*/
				$template->assign_block_vars("FILELIST.file_rows.AUTH_EDIT", array());
				if(($linkdb_config['allow_delete_link'] && $file_rowset[$i]['user_id'] != ANONYMOUS && $file_rowset[$i]['user_id'] == $userdata['user_id']) || $userdata['user_level'] == ADMIN)
				{
					$template->assign_block_vars("FILELIST.file_rows.AUTH_DELETE", array());
				}
				if($linkdb_config['split_links'])
				{
					$template->assign_block_vars("FILELIST.file_rows.split_links", array());
				}
			}
			
		}
		else 
		{
			if ($show_file_message) {
				$template->assign_block_vars('NO_FILE', array());
				$template->assign_vars(array(
					'NO_FILE' => $show_file_message,
					'L_NO_FILES' => $lang['No_links'],
					'L_NO_FILES_CAT' => $lang['No_links_cat']) 
				);
			}
		}
	}
	
	function display_banner($link_rowset, $row)
	{
		global $linkdb_config, $phpEx, $lang;
		
		if ($linkdb_config['display_links_logo'])
		{
			$banner = "<a href=".append_sid("linkdb.$phpEx?action=link&link_id=" . $link_rowset['link_id']) . " title='" . $row['link_name'] . "' target='_blank'><img src='";
			if ($link_rowset['link_logo_src']) 
			{
				$banner .= $link_rowset['link_logo_src'];
			}
			else
			{
				$banner .= "images/links/nologo.gif";
			}
		
			$banner .= "' alt='" . $link_rowset['link_name'] . "' title='" . $link_rowset['link_name'] . "' width='" . $linkdb_config['width'] . "' height='" . $linkdb_config['height'] . "' /></a>&nbsp;";
		}
		else
		{
			$banner = $lang['No_Display_Links_Logo'];
		}
		
		return $banner;
	}

	//
	// Admin and mod functions
	//
	
	function update_add_cat($cat_id = false)
	{
		global $db, $_POST, $lang;
		
		$cat_name = ( isset($_POST['cat_name']) ) ? htmlspecialchars($_POST['cat_name']) : '';
		$cat_parent = ( isset($_POST['cat_parent']) ) ? intval($_POST['cat_parent']) : 0;
		
		if(empty($cat_name))
		{
			$this->error[] = $lang['Cat_name_missing'];
		}
		
		/*if($cat_parent)
		{
			if(!$this->cat_rowset[$cat_parent])
			{
				$this->error[] = $lang['Cat_conflict'];
			}
		}*/

		if(sizeof($this->error))
		{
			return;
		}

		$cat_name = str_replace("\'", "''", $cat_name);
		
		if(!$cat_id)
		{		
			$cat_order = 0;
			if(!empty($this->subcat_rowset[$cat_parent]))
			{
				foreach($this->subcat_rowset[$cat_parent] as $cat_data)
				{
					if($cat_order < $cat_data['cat_order'])
					{
						$cat_order = $cat_data['cat_order'];
					}
				}
			}
		
			$cat_order += 10;
			
			$sql = 'INSERT INTO ' . LINK_CATEGORIES_TABLE . " (cat_name, cat_parent, cat_order) 
				VALUES('$cat_name', $cat_parent, $cat_order)";

			if ( !($db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Couldn\'t add a new category', '', __LINE__, __FILE__, $sql);
			}
		}
		else
		{
			$sql = 'UPDATE ' . LINK_CATEGORIES_TABLE . " 
				SET cat_name = '$cat_name', cat_parent = $cat_parent
				WHERE cat_id = $cat_id";

			if ( !($db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Couldn\'t Edit this category', '', __LINE__, __FILE__, $sql);
			}
			
			if($cat_parent != $this->cat_rowset[$cat_id]['cat_parent'])
			{
				$this->reorder_cat($this->cat_rowset[$cat_id]['cat_parent']);
				$this->reorder_cat($cat_parent);				
			}
			$this->modified(TRUE);
		}
		
		if($cat_id)
		{
			return $cat_id;
		}
		else
		{
			return $db->sql_nextid();
		}
	}
	
	function delete_cat($cat_id = false)
	{
		global $db, $_POST, $lang;
		
		$file_to_cat_id = ( isset($_POST['file_to_cat_id']) ) ? intval($_POST['file_to_cat_id']) : '';
		$subcat_to_cat_id = ( isset($_POST['subcat_to_cat_id']) ) ? intval($_POST['subcat_to_cat_id']) : '';
		$file_mode = ( isset($_POST['file_mode']) ) ? htmlspecialchars($_POST['file_mode']) : 'move';
		$subcat_mode = ( isset($_POST['subcat_mode']) ) ? htmlspecialchars($_POST['subcat_mode']) : 'move';
		
		if (empty($cat_id))
		{
			$this->error[] = $lang['Cdelerror'];
		}
		else
		{
			if ( ($file_to_cat_id == -1 || empty($file_to_cat_id)) && $file_mode == 'move')
			{
				$this->error[] = $lang['Cdelerror'];
			}

			if($subcat_mode == 'move' && empty($subcat_to_cat_id))
			{
				$this->error[] = $lang['Cdelerror'];
			}
			
			if(sizeof($this->error))
			{
				return;
			}

			$sql = 'DELETE FROM ' . LINK_CATEGORIES_TABLE . " 
				WHERE cat_id = $cat_id";

			if ( !($db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Couldnt Query Info', '', __LINE__, __FILE__, $sql);
			}

			$this->reorder_cat($this->cat_rowset[$cat_id]['cat_parent']);

			if ($file_mode == 'delete')
			{
				$this->delete_links($cat_id, 'category');
			}
			else
			{
				$this->move_links($cat_id, $file_to_cat_id);
			}
					
			if($subcat_mode == 'delete')
			{
				$this->delete_subcat($cat_id, $file_mode, $file_to_cat_id);
			}
			else
			{
				$this->move_subcat($cat_id, $subcat_to_cat_id);
			}
			$this->modified(TRUE);
		}
	}

	function delete_links($id, $mode = 'file')
	{
		global $db, $phpbb_root_path, $linkdb_functions;

		if($mode == 'category')
		{
			$file_ids = array();
			$files_data = array();
			$sql = 'SELECT link_id 
				FROM ' . LINKS_TABLE . "
				WHERE link_catid = $id";

			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Couldnt select files', '', __LINE__, __FILE__, $sql);
			}

			while($row = $db->sql_fetchrow($result))
			{
				$file_ids[] = $row['link_id'];
				$files_data[] = $row;
			}

			$where_sql = "WHERE link_catid = $id";
		}
		else
		{
			$sql = 'SELECT link_id 
				FROM ' . LINKS_TABLE . "
				WHERE link_id = $id";

			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Couldnt select files', '', __LINE__, __FILE__, $sql);
			}
			
			$file_data = $db->sql_fetchrow($result);
			
			$where_sql = "WHERE link_id = $id";
		}
	
		$sql = 'DELETE FROM ' . LINKS_TABLE . "
			$where_sql";
	
		unset($where_sql);

		if ( !($db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Couldnt delete files', '', __LINE__, __FILE__, $sql);
		}

		$where_sql = ($mode != 'file' && !empty($file_ids)) ? ' IN (' . implode(', ', $file_ids) . ') ' : " = $id";
	
		$sql = 'DELETE FROM ' . LINK_CUSTOM_DATA_TABLE . "
			WHERE customdata_file$where_sql";

		if ( !($db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Couldnt delete custom data', '', __LINE__, __FILE__, $sql);
		}
		
		if($mode == 'file')
		{
			$this->modified(TRUE);
		}

		return;
	}

	function move_links($from_cat, $to_cat)
	{
		global $db;

		$sql = 'UPDATE ' . LINKS_TABLE . "
			SET link_catid = $to_cat
			WHERE link_catid = $from_cat";
		
		if ( !($db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Couldnt move files', '', __LINE__, __FILE__, $sql);
		}
		$this->modified(TRUE);
		return;	
	}
	
	function delete_subcat($cat_id, $file_mode = 'delete', $to_cat = false)
	{
		global $db;
	
		if (empty($this->subcat_rowset[$cat_id])) return;
		foreach($this->subcat_rowset[$cat_id] as $sub_cat_id => $subcat_data)
		{
			$this->delete_subcat($sub_cat_id, $file_mode, $to_cat);

			$sql = 'DELETE FROM ' . LINK_CATEGORIES_TABLE . " 
				WHERE cat_id = $sub_cat_id";

			if ( !($db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Couldnt Query Info', '', __LINE__, __FILE__, $sql);
			}
		
			if($file_mode == 'delete')
			{
				$this->delete_links($sub_cat_id, 'category');
			}
			else
			{
				$this->move_links($sub_cat_id, $to_cat);
			}
		}
		$this->modified(TRUE);
		return;
	}
	
	function move_subcat($from_cat, $to_cat)
	{
		global $db;

		$sql = 'UPDATE ' . LINK_CATEGORIES_TABLE . "
			SET cat_parent = $to_cat
			WHERE cat_parent = $from_cat";

		if ( !($db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Couldnt move Sub Category', '', __LINE__, __FILE__, $sql);
		}
		$this->modified(TRUE);
		return;	
	}

	function reorder_cat($cat_parent)
	{
		global $db;

		$sql = 'SELECT cat_id, cat_order
			FROM '. LINK_CATEGORIES_TABLE ."
			WHERE cat_parent = $cat_parent
			ORDER BY cat_order ASC";

		if( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not get list of Categories', '', __LINE__, __FILE__, $sql);
		}

		$i = 10;
		while($row = $db->sql_fetchrow($result))
		{
			$cat_id = $row['cat_id'];

			$sql = 'UPDATE ' . LINK_CATEGORIES_TABLE . "
					SET cat_order = $i
					WHERE cat_id = $cat_id";
			if( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not update order fields', '', __LINE__, __FILE__, $sql);
			}
			$i += 10;
		}
	}
	
	function order_cat($cat_id)
	{
		global $db, $_GET;

		$move = (isset($_GET['move'])) ? intval($_GET['move']) : 15;
		$cat_parent = $this->cat_rowset[$cat_id]['cat_parent'];

		$sql = 'UPDATE ' . LINK_CATEGORIES_TABLE . "
				SET cat_order = cat_order + $move
				WHERE cat_id = $cat_id";

		if( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not change category order', '', __LINE__, __FILE__, $sql);
		}

		$this->reorder_cat($cat_parent);
		$this->init();
	}

	function sync($cat_id, $init = true)
	{
		global $db;

		$cat_nav = array();
		$this->category_nav($this->cat_rowset[$cat_id]['cat_parent'], &$cat_nav);

		$sql = 'UPDATE ' . LINK_CATEGORIES_TABLE . "
			SET parents_data = ''
			WHERE cat_parent = " . $this->cat_rowset[$cat_id]['cat_parent'];

		if ( !($db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Couldnt Query categories info', '', __LINE__, __FILE__, $sql);
		}
		
		$sql = 'UPDATE ' . LINK_CATEGORIES_TABLE . "
				SET cat_links = '-1'
				WHERE cat_id = '" . $cat_id . "'";

		if ( !($db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Couldnt Query Files info', '', __LINE__, __FILE__, $sql);
		}
		if($init)
		{
			$this->init();
		}
		return;
	}
	
	function sync_all()
	{
		foreach($this->cat_rowset as $cat_id => $void)
		{
			$this->sync($cat_id, false);
		}
		$this->init();
	}

	function update_add_link($file_id = false)
	{
		global $db, $_POST, $userdata, $linkdb_config, $_REQUEST, $user_ip;
		
		$link_poster = (!empty($_POST['post_username'])) ? $_POST['post_username'] : '';
		$link_url = (!empty($_POST['link_url'])) ? $_POST['link_url'] : '';
		if( $link_url == 'http://' ) $link_url = '';
		$link_logo_src = (!empty($_POST['link_logo_src'])) ? $_POST['link_logo_src'] : '';
		if ((substr($link_logo_src,-17) == 'weblink_88x31.png') || $link_logo_src == 'http://' ) $link_logo_src = '';
		$cat_id = ( isset($_REQUEST['cat_id']) ) ? intval($_REQUEST['cat_id']) : 0;
		$file_name = ( isset($_POST['name']) ) ? htmlspecialchars($_POST['name']) : '';
		
		$file_long_desc = ( isset($_POST['long_desc']) ) ? $_POST['long_desc'] : '';
		$file_pin = ( isset($_POST['pin']) ) ? intval($_POST['pin']) : 0;
		if ( $linkdb_config['need_validation'] == 0 )
		{
			$file_approved = 1;
		}
		elseif ( $linkdb_config['need_validation'] == 1 )
		{
			$file_approved = ( isset($_POST['approved']) ) ? intval($_POST['approved']) : 0;
		}

		$file_dls = ( isset($_POST['file_download']) ) ? intval($_POST['file_download']) : 0;
		
		$file_time = time();
		
		if($cat_id == -1)
		{
			$this->error[] = $lang['Missing_field'];
		}
			
		if(empty($file_name))
		{
			$this->error[] = $lang['Missing_field'];
		}
			
		if(empty($file_long_desc))
		{
			$this->error[] = $lang['Missing_field'];
		}

		if(empty($link_url))
		{
			$this->error[] = $lang['Missing_field'];
		}
			
		if(sizeof($this->error))
		{
			return;
		}

		if(!$file_id)
		{
			$sql = 'INSERT INTO ' . LINKS_TABLE . " (user_id, poster_ip, link_name, link_longdesc, link_url, link_time, link_logo_src, link_catid, link_hits, link_pin, link_approved, post_username)
					VALUES('{$userdata['user_id']}', '$user_ip', '" . str_replace("\'", "''", $file_name) . "', '" . str_replace("\'", "''", $file_long_desc) . "', '$link_url', '$file_time', '$link_logo_src', '$cat_id', '$file_dls', '$file_pin', '$file_approved', '$link_poster')";
		}
		else
		{

			$sql = "UPDATE " . LINKS_TABLE . " 
				SET link_name = '" . str_replace("\'", "''", $file_name) . "', 
				link_longdesc = '" . str_replace("\'", "''", $file_long_desc) . "', 
				link_url = '$link_url',
				link_time = '$file_time',
				link_logo_src = '$link_logo_src',
				link_catid = '$cat_id', 
				link_hits = '$file_dls',
				link_pin = '$file_pin',
				link_approved = '$file_approved' 
				WHERE link_id = '$file_id'";
		}

		if ( !($db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Couldnt Add the link information to the database', '', __LINE__, __FILE__, $sql);
		}
		
		$this->modified(TRUE);
		
		if($file_id)
		{
			return $file_id;
		}
		else
		{
			return $db->sql_nextid();
		}
	}

	function link_approve($mode = 'do_approve', $link_id)
	{
		global $db;
		
		$link_approved = ($mode == 'do_approve') ? 1 : 0;
		
		$sql = 'UPDATE ' . LINKS_TABLE . "
			SET link_approved = $link_approved ,
			link_time = " . time() . "
			WHERE link_id = $link_id";

		if ( !($db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Couldnt Add the file information to the database', '', __LINE__, __FILE__, $sql);
		}
		$this->modified(TRUE);
	}

	function _linkdb()
	{
		if($this->modified)
		{
			$this->sync_all();
		}
	}
}
?>