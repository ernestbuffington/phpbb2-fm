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
 *                            admin_cat_manage.php
 *                           ----------------------
 *   Modified by CRLin
 ***************************************************************************/

if ( !defined('IN_PHPBB') || IN_ADMIN != true)
{
	die("Hacking attempt");
}

class linkdb_cat_manage  extends linkdb_public
{
	function main($action)
	{
		global $db, $template, $lang, $phpEx, $linkdb_functions;

		$mode = (isset($_REQUEST['mode'])) ? htmlspecialchars($_REQUEST['mode']) : '';
		$cat_id = (isset($_REQUEST['cat_id'])) ? intval($_REQUEST['cat_id']) : 0;
		$cat_id_other = (isset($_REQUEST['cat_id_other'])) ? intval($_REQUEST['cat_id_other']) : 0;

		if($mode == 'do_add' && !$cat_id)
		{
			$cat_id = $this->update_add_cat();
			$mode = 'add';
			if(!sizeof($this->error))
			{
				$this->_linkdb();
				$message = $lang['Catadded'] . '<br /><br />' . sprintf($lang['Click_return'], '<a href="' . append_sid("admin_linkdb.$phpEx?action=cat_manage") . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);
			}
		}
		elseif($mode == 'do_add' && $cat_id)
		{
			$cat_id = $this->update_add_cat($cat_id);
			if(!sizeof($this->error))
			{
				$this->_linkdb();
				$message = $lang['Catedited'] . '<br /><br />' . sprintf($lang['Click_return'], '<a href="' . append_sid("admin_linkdb.$phpEx?action=cat_manage") . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);
			}
		}
		elseif($mode == 'do_delete')
		{
			$this->delete_cat($cat_id);
			if(!sizeof($this->error))
			{
				$this->_linkdb();
				$message = $lang['Catsdeleted'] . '<br /><br />' . sprintf($lang['Click_return'], '<a href="' . append_sid("admin_linkdb.$phpEx?action=cat_manage") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.$phpEx?pane=right") . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);
			}
		}
		elseif($mode == 'cat_order')
		{
			$this->order_cat($cat_id_other);
		}
		elseif($mode == 'sync')
		{
			$this->sync($cat_id_other);
		}
		elseif($mode == 'sync_all')
		{
			$this->sync_all();
		}

		switch($mode)
		{
			case '':
			case 'cat_order':
			case 'sync':
			default:
				$template_file = 'admin/linkdb_admin_cat.tpl';
				$l_title = $lang['Manage_categorys'];
				$l_explain = $lang['Catexplain'];
				$s_hidden_fields = '<input type="hidden" name="mode" value="add">';
				break;
			case 'add':
				$template_file = 'admin/linkdb_admin_cat_edit.tpl';
				$l_title = $lang['Acattitle'];
				$l_explain = $lang['Catexplain'];
				$s_hidden_fields = '<input type="hidden" name="mode" value="do_add">';
				break;
			case 'edit':
				$template_file = 'admin/linkdb_admin_cat_edit.tpl';
				$l_title = $lang['Ecattitle'];
				$l_explain = $lang['Catexplain'];
				$s_hidden_fields = '<input type="hidden" name="mode" value="do_add">';
				$s_hidden_fields .= '<input type="hidden" name="cat_id" value="' . $cat_id . '">';
				break;
			case 'delete':
				$template_file = 'admin/linkdb_admin_cat_delete.tpl';
				$l_title = $lang['Dcattitle'];
				$l_explain = $lang['Catexplain'];
				$s_hidden_fields = '<input type="hidden" name="mode" value="do_delete">';
				break;
		}

		$template->set_filenames(array(
			'body' => LINKDB_TPL_PATH . $template_file)
		);

		if (sizeof($this->error)) $template->assign_block_vars('linkdb_error', array());

		$template->assign_vars(array(
			'L_CAT_TITLE' => $l_title,
			'L_CAT_EXPLAIN' => $l_explain,
			'ERROR' => (sizeof($this->error)) ? implode('<br />', $this->error) : '',

			'S_HIDDEN_FIELDS' => $s_hidden_fields,
			'S_CAT_ACTION' => append_sid("admin_linkdb.$phpEx?action=cat_manage"))
		);

		if($mode == '' || $mode == 'cat_order' || $mode == 'sync' || $mode == 'sync_all')
		{
			$template->assign_vars(array(
				'L_CREATE_CATEGORY' => $lang['Create_category'], 
				'L_EDIT' => $lang['Edit'], 
				'L_DELETE' => $lang['Delete'], 
				'L_MOVE_UP' => $lang['Move_up'], 
				'L_MOVE_DOWN' => $lang['Move_down'], 
				'L_SUB_CAT' => $lang['Sub_category'],
				'L_RESYNC' => $lang['Resync'])
			);
			$this->admin_cat_main($cat_id);
		}
		elseif($mode == 'add' || $mode == 'edit')
		{
			if($mode == 'add')
			{
				if(!$_POST['cat_parent'])
				{
					$cat_list .= '<option value="0" selected>' . $lang['None'] . '</option>';
				}
				else
				{
					$cat_list .= '<option value="0">' . $lang['None'] . '</option>';
				}
				$cat_list .= (!$_POST['cat_parent']) ? $this->jumpmenu_option() : $this->jumpmenu_option(0, 0, array($_POST['cat_parent'] => 1));
				$cat_name = (!empty($_POST['cat_name'])) ? $_POST['cat_name'] : '';
			}
			else
			{
				if (!$this->cat_rowset[$cat_id]['cat_parent']) 
				{
					$cat_list .= '<option value="0" selected>' . $lang['None'] . '</option>\n';
				}
				else
				{
					$cat_list .= '<option value="0">' . $lang['None'] . '</option>\n';
				}
				$cat_list .= $this->jumpmenu_option(0, 0, array($this->cat_rowset[$cat_id]['cat_parent'] => 1));
	
				$cat_name = $this->cat_rowset[$cat_id]['cat_name'];
			}

			$template->assign_vars(array(
				'CAT_NAME' => $cat_name,
				'L_CAT_NAME' => $lang['Catname'],
				'L_CAT_NAME_INFO' => $lang['Catnameinfo'],
				'L_CAT_PARENT' => $lang['Catparent'],
				'L_CAT_PARENT_INFO' => $lang['Catparentinfo'],
				'L_NONE' => $lang['None'],
				'L_CAT_NAME_FIELD_EMPTY' => $lang['Cat_name_missing'],
				'S_CAT_LIST' => $cat_list)
			);
		}
		elseif($mode == 'delete')
		{
			$select_cat = $this->jumpmenu_option(0, 0, array($cat_id => 1));
			$file_to_select_cat = $this->jumpmenu_option(0, 0, '', TRUE);

			$template->assign_vars(array(
				'S_SELECT_CAT' => $select_cat,
				'S_FILE_SELECT_CAT' => $file_to_select_cat,

				'LINK_SAME_CAT' => $lang['Link_same_cat'],
				'LINK_MOVE_CAT' => $lang['Link_move_cat'],

				'L_DELETE'=> $lang['Delete'],
				'L_DO_FILE' => $lang['Dellinks'],
				'L_DO_CAT' => $lang['Do_cat'],
				'L_MOVE_TO' => $lang['Move_to'],
				'L_SELECT_CAT' => $lang['Select_a_Category'],
				'L_DELETE' => $lang['Delete'],
				'L_MOVE' => $lang['Move'])
			);
		}

		$template->pparse('body');
	}

	function admin_cat_main($cat_parent = 0, $depth = 0)
	{
		global $template, $phpEx;
		static $i;

		$pre = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $depth);
		if(isset($this->subcat_rowset[$cat_parent]))
		{
			foreach($this->subcat_rowset[$cat_parent] as $subcat_id => $cat_data)
			{
				if ($cat_parent == 0) { $i = 0; $str = "cat"; }
				else { $i++; $str = ($i % 2) ? "row1" : "row2"; }
				$template->assign_block_vars('cat_row', array(
					'COLOR' => $str,
					'U_CAT' => append_sid("admin_linkdb.$phpEx?action=cat_manage&amp;cat_id" . $subcat_id),
					'U_CAT_EDIT' => append_sid("admin_linkdb.$phpEx?action=cat_manage&amp;mode=edit&amp;cat_id=$subcat_id"),
					'U_CAT_DELETE' => 	append_sid("admin_linkdb.$phpEx?action=cat_manage&amp;mode=delete&amp;cat_id=$subcat_id"),
					'U_CAT_MOVE_UP' => append_sid("admin_linkdb.$phpEx?action=cat_manage&amp;mode=cat_order&amp;move=-15&amp;cat_id_other=$subcat_id"),
					'U_CAT_MOVE_DOWN' => append_sid("admin_linkdb.$phpEx?action=cat_manage&amp;mode=cat_order&amp;move=15&amp;cat_id_other=$subcat_id"),
					'U_CAT_RESYNC' => append_sid("admin_linkdb.$phpEx?action=cat_manage&amp;mode=sync&amp;cat_id_other=$subcat_id"),
					'CAT_NAME' => $cat_data['cat_name'],
					'PRE' => $pre)
				);
				$this->admin_cat_main($subcat_id, $depth + 1);
			}
			return;
		}
		return;
	}
}
?>