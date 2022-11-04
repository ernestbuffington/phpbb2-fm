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
 *                            admin_link_manage.php
 *                           -----------------------
 *   Modified by CRLin 2004/07/05
 ***************************************************************************/

if ( !defined('IN_PHPBB') || IN_ADMIN != true)
{
	die("Hacking attempt");
}

class linkdb_link_manage extends linkdb_public
{
	function main($action)
	{
		global $phpbb_root_path, $db, $template, $lang, $phpEx, $linkdb_functions, $linkdb_config, $images;

		include($phpbb_root_path . 'mods/linkdb/includes/functions_field.'.$phpEx);

		$custom_field = new custom_field();
		$custom_field->init();

		$cat_id = (isset($_REQUEST['cat_id'])) ? intval($_REQUEST['cat_id']) : 0;
		$link_id = (isset($_REQUEST['link_id'])) ? intval($_REQUEST['link_id']) : 0;
		$file_ids = (isset($_POST['file_ids'])) ? array_map('intval', $_POST['file_ids']) : array();
		$start = ( isset($_REQUEST['start']) ) ? intval($_REQUEST['start']) : 0;

		$mode = (isset($_REQUEST['mode'])) ? htmlspecialchars($_REQUEST['mode']) : '';
		$mode_js = (isset($_REQUEST['mode_js'])) ? htmlspecialchars($_REQUEST['mode_js']) : '';
		$mode = (isset($_POST['addfile'])) ? 'add' : $mode;
		$mode = (isset($_POST['delete'])) ? 'delete' : $mode;
		$mode = (isset($_POST['approve'])) ? 'do_approve' : $mode;
		$mode = (isset($_POST['unapprove'])) ? 'do_unapprove' : $mode;
		$mode = (empty($mode)) ? $mode_js : $mode;

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
			}
		}
		else
		{
			$sort_order = $linkdb_config['sort_order'];
		}

		$s_file_actions = array('unapproved' => $lang['Unapproved_links'], 
								'approved' => $lang['Approved_links'], 
								'file_cat' => $lang['Link_cat'], 
								'' => $lang['All_links']
								);

		switch($mode)
		{
			case '':
			case 'unapproved':
			case 'approved':
			case 'broken':
			case 'do_approve':
			case 'do_unapprove':
			case 'delete':
			case 'file_cat':
			default:
				$template_file = 'admin/linkdb_admin_link.tpl';
				$l_title = $lang['Link_manage'];
				$l_explain = $lang['Linkexplain'];
				//$s_hidden_fields = '<input type="hidden" name="mode" value="add">';
				break;
			case 'add':
				$template_file = 'admin/linkdb_admin_link_edit.tpl';
				$l_title = $lang['AddLink'];
				$l_explain = $lang['Linkexplain'];
				$s_hidden_fields = '<input type="hidden" name="mode" value="do_add">';
				break;
			case 'edit':
			case 'do_add':
				$template_file = 'admin/linkdb_admin_link_edit.tpl';
				$l_title = $lang['Elinktitle'];
				$l_explain = $lang['Linkexplain'];
				$s_hidden_fields = '<input type="hidden" name="mode" value="do_add">';
				$s_hidden_fields .= '<input type="hidden" name="link_id" value="' . $link_id . '">';
				break;
		}

		if($mode == 'do_add' && !$link_id)
		{
			$link_id = $this->update_add_link();
			$custom_field->file_update_data($link_id);
			$this->_linkdb();
			$mode = 'edit';
	
			$message = $lang['Linkadded'] . '<br /><br />' . sprintf($lang['Click_return'], '<a href="' . append_sid("admin_linkdb.$phpEx?action=link_manage") . '">', '</a>');
			message_die(GENERAL_MESSAGE, $message);	
		}
		elseif($mode == 'do_add' && $link_id)
		{
			$link_id = $this->update_add_link($link_id);
			$custom_field->file_update_data($link_id);
			$this->_linkdb();
			$mode = 'edit';
			$message = $lang['Linkedited'] . '<br /><br />' . sprintf($lang['Click_return'], '<a href="' . append_sid("admin_linkdb.$phpEx?action=link_manage") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.$phpEx?pane=right") . '">', '</a>');
			message_die(GENERAL_MESSAGE, $message);
		}
		elseif($mode == 'delete')
		{
			if(is_array($file_ids) && !empty($file_ids))
			{
				foreach($file_ids as $temp_file_id)
				{
					$this->delete_links($temp_file_id);
				}
			}
			else
			{
				$this->delete_links($link_id);
			}
			$this->_linkdb();
		}
		elseif($mode == 'do_approve' || $mode == 'do_unapprove')
		{
			if(is_array($file_ids) && !empty($file_ids))
			{
				foreach($file_ids as $temp_file_id)
				{
					$this->link_approve($mode, $temp_file_id);
				}
			}
			else
			{
				$this->link_approve($mode, $link_id);
			}
			$this->_linkdb();
		}

		$template->set_filenames(array(
			'body' => LINKDB_TPL_PATH . $template_file)
		);

		$template->assign_vars(array(
			'L_FILE_TITLE' => $l_title,
			'L_FILE_EXPLAIN' => $l_explain,
			'L_ADD_FILE' => $lang['AddLink'],
			'L_DELETE_APPROVE' => $lang['delete_link_approve'],
			'L_DELETE_CANCEL' => $lang['delete_link_cancel'],
			'L_DELETE_LINKS' => $lang['delete_links'],
			'L_SELECT_LINKS' => $lang['select_links'],

			'S_HIDDEN_FIELDS' => $s_hidden_fields,
			'S_FILE_ACTION' => append_sid("admin_linkdb.$phpEx?action=link_manage"))
		);


		if(in_array($mode, array('', 'unapproved', 'approved', 'broken', 'do_approve', 'do_unapprove', 'delete', 'file_cat')))
		{
			$mode = (in_array($mode, array('do_approve', 'do_unapprove', 'delete'))) ? '' : $mode;

			if($mode != 'unapproved' && $mode != 'broken')
			{
				$where_sql = ($mode == 'file_cat') ? "AND link_catid = '$cat_id'" : '';
				$sql = "SELECT f1.link_name, f1.link_approved, f1.link_id, f1.link_url, f1.user_id, f1.post_username, u.user_id, u.username
					FROM " . LINKS_TABLE . " as f1
						LEFT JOIN ". USERS_TABLE ." AS u ON f1.user_id = u.user_id
					WHERE f1.link_approved = 1
					$where_sql
					ORDER BY link_time DESC";

					if($mode == '' || $mode == 'file_cat' || $mode == 'approved')
					{
						if( (!$result = $db->sql_query($sql)) )
						{
							message_die(GENERAL_ERROR, 'Couldn\'t get file info', '', __LINE__, __FILE__, $sql);
						}
		
						$total_files = $db->sql_numrows($result);
					}

				if ( !($result = $linkdb_functions->sql_query_limit($sql, $linkdb_config['settings_link_page'], $start)) )
				{
					message_die(GENERAL_ERROR, 'Couldn\'t get file info', '', __LINE__, __FILE__, $sql);
				}
				while($row = $db->sql_fetchrow($result))
				{
					$approved_rowset[] = $row;
				}
			}
	
			if($mode == '' || $mode == 'unapproved' || $mode == 'approved' || $mode == 'broken' || $mode == 'file_cat')
			{
				if($mode == '')
				{
					$limit = 5;
					$temp_start = 0;
				}
				else
				{
					$limit = $linkdb_config['settings_link_page'];
					$temp_start = $start;
				}
		
				if($mode == '' || $mode == 'unapproved')
				{
					$sql = "SELECT f1.link_name, f1.link_approved, f1.link_id, f1.link_url, f1.user_id, f1.post_username, u.user_id, u.username
						FROM " . LINKS_TABLE . " as f1
						LEFT JOIN ". USERS_TABLE ." AS u ON f1.user_id = u.user_id
						WHERE link_approved = '0'
						ORDER BY link_time DESC";

					if($mode == 'unapproved')
					{
						if( (!$result = $db->sql_query($sql)) )
						{
							message_die(GENERAL_ERROR, 'Couldn\'t get file info', '', __LINE__, __FILE__, $sql);
						}
			
						$total_files = $db->sql_numrows($result);
					}

					if ( !($result = $linkdb_functions->sql_query_limit($sql, $limit, $temp_start)) )
					{
						message_die(GENERAL_ERROR, 'Couldn\'t get file info', '', __LINE__, __FILE__, $sql);
					}

					while($row = $db->sql_fetchrow($result))
					{
						$unapproved_file_rowset[] = $row;
					}
				}
		
				if($mode == '' || $mode == 'broken')
				{
					$sql = "SELECT f1.link_name, f1.link_approved, f1.link_id, f1.link_url, f1.user_id, f1.post_username, u.user_id, u.username
						FROM " . LINKS_TABLE . " as f1
						LEFT JOIN ". USERS_TABLE ." AS u ON f1.user_id = u.user_id
						ORDER BY link_time DESC";

					if($mode == 'broken')
					{
						if( (!$result = $db->sql_query($sql)) )
						{
							message_die(GENERAL_ERROR, 'Couldn\'t get file info', '', __LINE__, __FILE__, $sql);
						}
			
						$total_files = $db->sql_numrows($result);
					}

					if ( !($result = $linkdb_functions->sql_query_limit($sql, $limit, $temp_start)) )
					{
						message_die(GENERAL_ERROR, 'Couldn\'t get file info', '', __LINE__, __FILE__, $sql);
					}

					while($row = $db->sql_fetchrow($result))
					{
						$broken_file_rowset[] = $row;
					}
				}
		
				if($mode == '')
				{
					$global_array = array(0 => array('lang_var' => $lang['Unapproved_links'],
						 							 'row_set' => $unapproved_file_rowset,
													 'approval' => 'approve'),
		   								  1 => array('lang_var' => $lang['Approved_links'],
													 'row_set' => $approved_rowset,
													 'approval' => 'unapprove')
										  /*2 => array('lang_var' => $lang['Broken_files'],
													 'row_set' => $broken_file_rowset,
 													 'approval' => 'both')*/ );
				}
				elseif($mode == 'approved' || $mode == 'file_cat')
				{
					$global_array = array(0 => array('lang_var' => $lang['Approved_links'],
													 'row_set' => $approved_rowset,
													 'approval' => 'unapprove'));
				}
				elseif($mode == 'unapproved')
				{
					$global_array = array(0 => array('lang_var' => $lang['Unapproved_links'],
													 'row_set' => $unapproved_file_rowset,
													 'approval' => 'approve'));
				}
				elseif($mode == 'broken')
				{
					$global_array = array(0 => array('lang_var' => $lang['Broken_files'],
													 'row_set' => $broken_file_rowset,
													 'approval' => 'both'));
				}
			}
	
			$s_file_list = '';
			foreach($s_file_actions as $file_mode => $lang_var)
			{
				$s = '';
				if($mode == $file_mode)
				{
					$s = ' selected="selected"';
				}
				$s_file_list .= '<option value="' . $file_mode . '"' . $s . '>' . $lang_var . '</option>';
			}
	
			$cat_list = '<select name="cat_id">';
			if (!$this->cat_rowset[$cat_id]['cat_parent']) 
			{
				$cat_list .= '<option value="0" selected>' . $lang['None'] . '</option>\n';
			}
			else
			{
				$cat_list .= '<option value="0">' . $lang['None'] . '</option>\n';
			}
			$cat_list .= $this->jumpmenu_option(0, 0, array($cat_id => 1), true);
			$cat_list .= '</select>';
	
			$template->assign_vars(array(
				'EDIT' => '<img src="' . $phpbb_root_path . $images['acp_edit'] . '" alt="' . $lang['Edit'] . '" title="' . $lang['Edit'] . '" />',
				'DELETE' => '<img src="' . $phpbb_root_path . $images['acp_delete'] . '" alt="' . $lang['Delete'] . '" title="' . $lang['Delete'] . '" />',
				'L_CATEGORY' => $lang['Category'],
				'L_MODE' => $lang['View'],
				'L_GO' => $lang['Go'],
				'L_DELETE_FILE' => $lang['Delete_selected'],
				'L_APPROVE_FILE' => $lang['Approve_selected'],
				'L_UNAPPROVE_FILE' => $lang['Unapprove_selected'],
				'L_NO_FILES' => $lang['No_link'],
		
				'PAGINATION' => generate_pagination(append_sid("admin_linkdb.$phpEx?action=link_manage&amp;mode=$mode&amp;sort_method=$sort_method&amp;sort_order=$sort_order&cat_id=$cat_id"), $total_files, $linkdb_config['settings_link_page'], $start),
				'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / $linkdb_config['settings_link_page'] ) + 1 ), ceil( $total_files / $linkdb_config['settings_link_page'] )),
		
				'S_CAT_LIST' => $cat_list,
				'S_MODE_SELECT' => $s_file_list)
			);
	
			foreach($global_array as $files_data)
			{
				$approve = false;
				$unapprove = false;
				if($files_data['approval'] == 'both')
				{
					$approve = $unapprove = true;
				}
				elseif($files_data['approval'] == 'approve')
				{
					$approve = true;
				}
				elseif($files_data['approval'] == 'unapprove')
				{
					$unapprove = true;
				}

				$template->assign_block_vars('file_mode', array(
					'L_FILE_MODE' => $files_data['lang_var'],
					'DATA' => (isset($files_data['row_set'])) ? TRUE : FALSE,
					'APPROVE' => $approve,
					'UNAPPROVE' => $unapprove)
				);

				if(isset($files_data['row_set']))
				{
					$i = $start + 1; $j = 0;
					foreach($files_data['row_set'] as $file_data)
					{
						$approve_mode = ($file_data['link_approved']) ? 'do_unapprove' : 'do_approve';
				
						$file_poster = ( $file_data['user_id'] != ANONYMOUS ) ? '<a href="' . append_sid('../profile.'.$phpEx.'?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $file_data['user_id']) . '" target=_blank>' : '';
						$file_poster .= ( $file_data['user_id'] != ANONYMOUS ) ? $file_data['username'] : $file_data['post_username'].'('.$lang['Guest'].')';
						$file_poster .= ( $file_data['user_id'] != ANONYMOUS ) ? '</a>' : '';
				
						$template->assign_block_vars('file_mode.file_row', array(
							'COLOR' => (++$j % 2) ? "row1" : "row2",
							'FILE_NAME' => "<a href=".$file_data['link_url']." target=_blank>".$file_data['link_name']."</a>",
							'FILE_SUBMITED_BY' => $file_poster,
							'FILE_NUMBER' => $i++,
							'FILE_ID' => $file_data['link_id'],
							'U_FILE_EDIT' => append_sid("admin_linkdb.$phpEx?action=link_manage&amp;mode=edit&link_id={$file_data['link_id']}"),
							'U_FILE_DELETE' => append_sid("admin_linkdb.$phpEx?action=link_manage&amp;mode=delete&link_id={$file_data['link_id']}"),
							'U_FILE_APPROVE' => append_sid("admin_linkdb.$phpEx?action=link_manage&amp;mode=$approve_mode&link_id={$file_data['link_id']}"),
							'APPROVE' => ($file_data['link_approved']) ? '<img src="' . $phpbb_root_path . $images['acp_enable'] . '" alt="' . $lang['Unapprove'] . '" title="' . $lang['Unapprove'] . '" />' : '<img src="' . $phpbb_root_path . $images['acp_disable'] . '" alt="' . $lang['Approve'] . '" title="' . $lang['Approve'] . '" />')
						);
		
					}
				}
				else
				{
					$template->assign_block_vars('file_mode.no_data', array());
				}
			}
		}
		elseif($mode == 'add' || $mode == 'edit' || $mirrors)
		{
			if($mode == 'add')
			{
				$link_name = '';
				$link_longdesc = '';
				$link_logo_src = 'http://';
				$file_cat_list = $this->jumpmenu_option(0, 0, '', true);
				$file_download = 0;
				$pin_checked_yes = '';
				$pin_checked_no = ' checked';
				$approved_checked_yes = ' checked';
				$approved_checked_no = '';
				$file_url = 'http://';
				$custom_exist = $custom_field->display_edit();
			}
			else
			{
				$sql = 'SELECT *
					FROM ' . LINKS_TABLE . "
					WHERE link_id = $link_id";
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'Couldn\'t get file info', '', __LINE__, __FILE__, $sql);
				}
				$file_info = $db->sql_fetchrow($result);
		
				$link_name = $file_info['link_name'];
				$link_longdesc = $file_info['link_longdesc'];
				$link_logo_src = $file_info['link_logo_src'];
				if (empty($link_logo_src)) $link_logo_src = 'http://';
				$file_cat_list = $this->jumpmenu_option(0, 0, array($file_info['link_catid'] => 1), true);
				$file_download = intval($file_info['link_hits']);
				$pin_checked_yes = ($file_info['link_pin']) ? ' checked' : '';
				$pin_checked_no = (!$file_info['link_pin']) ? ' checked' : '';
				$approved_checked_yes = ($file_info['link_approved']) ? ' checked' : '';
				$approved_checked_no = (!$file_info['link_approved']) ? ' checked' : '';
				$file_url = $file_info['link_url'];
				$custom_exist = $custom_field->display_edit($link_id);
			}
	
			$template->assign_vars(array(
				'LINK_CAT_NOT_ALLOW' => $lang['Cat_not_allow'],
				'FILE_NAME' => $link_name,
				'FILE_LONG_DESC' => $link_longdesc,
		
				'LINK_LOGO_SRC' => $link_logo_src,

				'LINK_URL' => $file_url,
				'FILE_DOWNLOAD' => $file_download,
				'PIN_CHECKED_YES' => $pin_checked_yes,
				'PIN_CHECKED_NO' => $pin_checked_no,
				'APPROVED_CHECKED_YES' => $approved_checked_yes,
				'APPROVED_CHECKED_NO' => $approved_checked_no,
		
				'L_FILE_APPROVED' => $lang['Approved'],
				'L_FILE_APPROVED_INFO' => $lang['Approved_info'],
				'L_ADDTIONAL_FIELD' => $lang['Addtional_field'],
				'L_FILE_NAME' => $lang['Sitename'],
				'L_FILE_NAME_INFO' => $lang['Sitenameinfo'],
				'L_FILE_LONG_DESC' => $lang['Siteld'],
				'L_FILE_LONG_DESC_INFO' => $lang['Siteldinfo'],
		
				'L_LINK_LOGO' => $lang['Link_logo'],
				'L_LINK_LOGO_SRC' => sprintf($lang['Link_logo_src1'],$linkdb_config['width'],$linkdb_config['height']),
				'L_PREVIEW' => $lang['Preview'],

				'L_FILE_URL' => $lang['Siteurl'],
				'L_FILE_UPLOAD' => $lang['File_upload'],
				'L_FILEINFO_UPLOAD' => $lang['Fileinfo_upload'],
				'L_FILE_CAT' => $lang['Sitecat'],
				'L_FILE_CAT_INFO' => $lang['Sitecatinfo'],
				'L_FILE_PINNED' => $lang['Linkpin'],
				'L_FILE_PINNED_INFO' => $lang['Linkpininfo'],
				'L_FILE_DOWNLOAD' => $lang['Link_hits'],
				'L_NO' => $lang['No'],
				'L_YES' => $lang['Yes'],

				'LINK_NAME_FIELD' => $lang['Link_name_field'],
				'LINK_URL_FIELD' => $lang['Link_url_field'],
				'LINK_LONG_DES_FIELD' => $lang['Link_long_des_field'],

				'S_POSTICONS' => $file_posticons,
				'S_LICENSE_LIST' => $file_license,
				'S_CAT_LIST' => $file_cat_list)
			);
		}
		if ($custom_exist) $template->assign_block_vars('custom_exit', array());

		if (sizeof($this->error)) $template->assign_block_vars('linkdb_error', array());

		$template->assign_vars(array(
			'ERROR' => (sizeof($this->error)) ? implode('<br />', $this->error) : '')
		);

		$template->pparse('body');
	}
}
?>