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
 *                          admin_link_custom.php
 *                         -----------------------
 *   Modified by CRLin
 ***************************************************************************/

if ( !defined('IN_PHPBB') || IN_ADMIN != true)
{
	die("Hacking attempt");
}

class linkdb_link_custom extends linkdb_public
{
	function main($action)
	{
		global $phpbb_root_path, $template, $lang, $phpEx;

		include($phpbb_root_path . 'mods/linkdb/includes/functions_field.'.$phpEx);

		$custom_field = new custom_field();
		$custom_field->init();

		$mode = (isset($_REQUEST['mode'])) ? htmlspecialchars($_REQUEST['mode']) : 'select';
		//
		// check if there is a data in the database
		//
		if (empty($custom_field->field_rowset) && $mode == 'select') $mode = 'add';
		$mode = (isset($_POST['add'])) ? 'add' : $mode;
		$mode = (isset($_POST['edit'])) ? 'edit' : $mode;
		$mode = (isset($_POST['delete'])) ? 'delete' : $mode;
		$field_id = (isset($_REQUEST['field_id'])) ? intval($_REQUEST['field_id']) : 0;
		$field_type = (isset($_REQUEST['field_type'])) ? intval($_REQUEST['field_type']) : $custom_field->field_rowset[$field_id]['field_type'];
		$field_ids = (isset($_REQUEST['field_ids'])) ? $_REQUEST['field_ids'] : '';
		$submit = (isset($_POST['submit'])) ? TRUE : FALSE;

		switch($mode)
		{
			case 'addfield':
				$template_file = 'admin/linkdb_admin_field_add.tpl';
				break;
			case 'edit':
				$template_file = 'admin/linkdb_admin_select_field.tpl';
				break;
			case 'add':
				$template_file = 'admin/linkdb_admin_select_field_type.tpl';
				break;
			case 'delete':
				$template_file = 'admin/linkdb_admin_field_delete.tpl';
				break;
			case 'select':
				$template_file = 'admin/linkdb_admin_field.tpl';
				break;
		}

		if($submit)
		{
			if($mode == 'do_add' && !$field_id)
			{
				$custom_field->update_add_field($field_type);

				$message = $lang['Fieldadded'] . '<br /><br />' . sprintf($lang['Click_return'], '<a href="' . append_sid('admin_linkdb.'.$phpEx.'?action=link_custom') . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.'.$phpEx.'?pane=right') . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);
			}
			elseif($mode == 'do_add' && $field_id)
			{
				$custom_field->update_add_field($field_type, $field_id);
		
				$message = $lang['Fieldedited'] . '<br /><br />' . sprintf($lang['Click_return'], '<a href="' . append_sid('admin_linkdb.'.$phpEx.'?action=link_custom') . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.'.$phpEx.'?pane=right') . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);
			}
			elseif($mode == 'delete')
			{
				foreach($field_ids as $key => $value)
				{
					$custom_field->delete_field($key);
				}
		
				$message = $lang['Fieldsdel'] . '<br /><br />' . sprintf($lang['Click_return'], '<a href="' . append_sid('admin_linkdb.'.$phpEx.'?action=link_custom') . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.'.$phpEx.'?pane=right') . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);
			}
		}

		$template->set_filenames(array(
			'body' => LINKDB_TPL_PATH . $template_file)
		);
		
		switch($mode)
		{
			case 'add':
			case 'addfield':
				$l_title = $lang['Afieldtitle'];
				if ($field_id) $l_title = $lang['Efieldtitle'];
				break;
			case 'edit':
				$l_title = $lang['Efieldtitle'];
				break;
			case 'delete':
				$l_title = $lang['Dfieldtitle'];
				break;
			case 'select':
				$l_title = $lang['Link_man_field'];
				break;
		}

		if($mode == 'add')
		{
			$s_hidden_fields = '<input type="hidden" name="mode" value="addfield">';
		}
		elseif($mode == 'addfield')
		{
			$s_hidden_fields = '<input type="hidden" name="field_type" value="' . $field_type . '">';
			$s_hidden_fields .= '<input type="hidden" name="field_id" value="' . $field_id . '">';
			$s_hidden_fields .= '<input type="hidden" name="mode" value="do_add">';
		}
		elseif($mode == 'edit')
		{
			$s_hidden_fields = '<input type="hidden" name="mode" value="addfield">';
		}
		elseif($mode == 'delete')
		{
			$s_hidden_fields = '<input type="hidden" name="mode" value="delete">';
		}

		$template->assign_vars(array(
			'L_FIELD_TITLE' => $l_title,
			'L_FIELD_EXPLAIN' => $lang['Fieldexplain'],

			'S_HIDDEN_FIELDS' => $s_hidden_fields,
			'ADD_FIELD' => $lang['Afieldtitle'],
			'EDIT_FIELD' => $lang['Efieldtitle'],
			'DELETE_FIELD' => $lang['Dfieldtitle'],
			'S_FIELD_ACTION' => append_sid("admin_linkdb.$phpEx?action=link_custom"))
		);

		if($mode == 'addfield')
		{
			if($field_id)
			{
				$data = $custom_field->get_field_data($field_id);
			}

			$template->assign_vars(array(
				'L_FIELD_NAME' => $lang['Fieldname'],
				'L_FIELD_NAME_INFO' => $lang['Fieldnameinfo'],
				'L_FIELD_DESC' => $lang['Fielddesc'],
				'L_FIELD_DESC_INFO' => $lang['Fielddescinfo'],
				'L_FIELD_DATA' => $lang['Field_data'],
				'L_FIELD_DATA_INFO' => $lang['Field_data_info'],
				'L_FIELD_REGEX' => $lang['Field_regex'],
				'L_FIELD_REGEX_INFO' => sprintf($lang['Field_regex_info'], '<a href="http://www.php.net/manual/en/function.preg-match.php" target="_blank">', '</a>'),
				'L_FIELD_ORDER' => $lang['Field_order'],
			
				'FIELD_NAME' => $data['custom_name'],
				'FIELD_DESC' => $data['custom_description'],
				'FIELD_DATA' => $data['data'],
				'FIELD_REGEX' => $data['regex'],
				'FIELD_ORDER' => $data['field_order'])
			);
			if ( $field_type != INPUT && $field_type != TEXTAREA )
			{
				$template->assign_block_vars('field_data', array());
			}
			if ( $field_type == INPUT || $field_type == TEXTAREA )
			{
				$template->assign_block_vars('field_regex', array());
			}
			if ( $field_id )
			{
				$template->assign_block_vars('field_order', array());
			}
		}
		elseif($mode == 'add')
		{
			$field_types = array(INPUT => $lang['Input'], TEXTAREA => $lang['Textarea'], RADIO => $lang['Radio'], SELECT => $lang['Select'], SELECT_MULTIPLE => $lang['Select_multiple'], CHECKBOX => $lang['Checkbox']);
	
			$field_type_list = '<select name="field_type">';
			foreach($field_types as $key => $value)
			{
				$field_type_list .= '<option value="' . $key . '">' . $value . '</option>';
			}
			$field_type_list .= '</select>';

			$template->assign_vars(array(
				'S_SELECT_FIELD_TYPE' => $field_type_list)
			);
		}
		elseif($mode == 'edit' || $mode == 'delete' || $mode == 'select' )
		{
			$template->assign_vars(array(
				'L_SELECT_FIELDS' => $lang['select_fields'],
				'L_SELECT_FIELD' => $lang['select_field'],
			));
			foreach($custom_field->field_rowset as $field_id => $field_data)
			{
				$template->assign_block_vars('field_row', array(
					'FIELD_ID' => $field_id,
					'FIELD_NAME' => $field_data['custom_name'],
					'FIELD_DESC' => $field_data['custom_description']
				));
			}
		}

		$template->pparse('body');
	}
}
?>