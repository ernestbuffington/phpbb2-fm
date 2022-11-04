<?php
/** 
*
* @package admin
* @version $Id: admin_bookies_plus_selections.php,v 3.0.0 2004/11/17 17:49:33 majorflam Exp $
* @copyright (c) 2004 Majorflam
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	$module['Bookmakers']['Selections'] = $filename;

	return;
}

//
// Load default header
//
$no_page_header = TRUE;
$phpbb_root_path = './../';
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);

//
// Output the authorisation details
//
$template->set_filenames(array(
	'body' => 'admin/admin_bookies_plus_selections.tpl')
);

//
// Include language
//
$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_admin_bookmakers.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_admin_bookmakers.' . $phpEx);


//
// Mode setting
//
if( isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']) )
{
	$mode = ( isset($HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
	$mode = htmlspecialchars($mode); 
}
else
{
	$mode = "";
}


if ( $mode == 'new_templ' )
{
	$template->set_filenames(array(
		'body' => 'admin/admin_bookie_plus_add_sel_template.tpl')
	);

	$url = append_sid("admin_bookies_plus_selections.$phpEx?&amp;mode=fields_set");
	
	$template->assign_vars(array(
		'NEW_HEADER' => $lang['bookie_new_template_head'],
		'NEW_EXPLAIN' => $lang['bookie_template_name_input_exp'],
		'SUBMIT' => $lang['bookie_set_submitbuton'],
		'URL' => $url,
		'CONFIRM_SELECTION_FIELDS' => $lang['bookie_confirm_sel_fields'],
		'FIELDS_EXPLAIN' => $lang['bookie_confirm_sel_fields_exp'])
	);
	
	include('./page_header_admin.'.$phpEx);

	$template->pparse('body');

	include('./page_footer_admin.'.$phpEx);
}

if ( $mode == 'fields_set' )
{
	$template->set_filenames(array(
		'body' => 'admin/admin_bookie_plus_add_sel_template_do.tpl')
	);

	$num_selections = intval($HTTP_POST_VARS['num_templ']);
	$url = append_sid("admin_bookies_plus_selections.$phpEx?&amp;mode=do_templ&amp;num_selections=$num_selections");
	
	for ( $i=1; $i<($num_selections+1); $i++ )
	{
		$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
		
		$template->assign_block_vars('templ_selections', array(
			'ROW_CLASS' => $row_class,
			'SELECTION_NAME' => 'selection_' . $i,
			'NUMBER' => $i)
		);
	}
	
	$template->assign_vars(array(
		'SUBMIT' => $lang['bookie_set_submitbuton'],
		'URL' => $url,
		'SELECTION' => $lang['bookie_new_template_head'],
		'TEMPL_NAME_INPUT' => $lang['bookie_template_name_input'],
		'TEMPL_NAME_INPUT_EXP' => $lang['bookie_template_name_input_exp'])
	);
	
	include('./page_header_admin.'.$phpEx);

	$template->pparse('body');

	include('./page_footer_admin.'.$phpEx);
}

if ( $mode == 'do_templ' )
{
	//
	// create a new template
	//
	$num_selections=intval($HTTP_GET_VARS['num_selections']);
	$templ_name=htmlspecialchars($HTTP_POST_VARS['templ_name']);
	for ( $i=1; $i<($num_selections+1); $i++ )
	{
		$input_name='selection_' . $i;
		$this_selection=htmlspecialchars($HTTP_POST_VARS[$input_name]);
		if ( $this_selection && !$name_done )
		{
			//
			// Lets input this new name
			//
			$sql=" SELECT selection_name FROM " . BOOKIE_SELECTIONS_TABLE . "
			WHERE selection_name='" . str_replace("\'", "''", $templ_name) . "'
			";
			if ( !($result = $db->sql_query($sql)) ) 
			{		
				message_die(GENERAL_ERROR, 'Error in gathering existing selection data', '', __LINE__, __FILE__, $sql); 
			}
			$row = $db->sql_fetchrow($result);
			if ( $row )
			{
				$redirect = '<meta http-equiv="refresh" content="2;url=' . append_sid("admin_bookies_plus_selections.$phpEx?") . '">';
				$message=$lang['bookie_template_exists'] . $redirect;
				message_die(GENERAL_MESSAGE, $message);
			}
			else
			{
				$sql=" INSERT INTO " . BOOKIE_SELECTIONS_TABLE . "
				(selection_name) VALUES ('" . str_replace("\'", "''", $templ_name) . "')
				";
				if ( !$db->sql_query($sql) ) 
				{		
					message_die(GENERAL_ERROR, 'Error in inserting new name to DB', '', __LINE__, __FILE__, $sql); 
				}
				$sql=" SELECT selection_id FROM " . BOOKIE_SELECTIONS_TABLE . "
				WHERE selection_name='" . str_replace("\'", "''", $templ_name) . "'
				";
				if ( !($result = $db->sql_query($sql)) ) 
				{		
					message_die(GENERAL_ERROR, 'Error in gathering existing selection data', '', __LINE__, __FILE__, $sql); 
				}
				$row = $db->sql_fetchrow($result);
				$name_done=$row['selection_id'];
			}
		}
		
		if ( $this_selection )
		{
			//
			// lets add this selection to the template
			//
			$sql=" INSERT INTO " . BOOKIE_SELECTIONS_DATA_TABLE . "
			(selection_id,selection) VALUES ('$name_done','" . str_replace("\'", "''", $this_selection) . "')
			";
			if ( !$db->sql_query($sql) ) 
			{		
				message_die(GENERAL_ERROR, 'Error in inserting new selection field to DB', '', __LINE__, __FILE__, $sql); 
			}
		}
	}
	//
	// display success message
	//
	$redirect = '<meta http-equiv="refresh" content="2;url=' . append_sid("admin_bookies_plus_selections.$phpEx?expand=$name_done") . '#' . $name_done . '">';
	$message = $lang['bookies_template_new_success'] . $redirect;
	message_die(GENERAL_MESSAGE, $message);
}

if ( $mode == 'edit' )
{
	$templ_id=intval($HTTP_GET_VARS['templ_id']);
	$num_selections=intval($HTTP_GET_VARS['num_selections']);
	
	if ( !$num_selections )
	{
		$template->set_filenames(array(
			'body' => 'admin/admin_bookies_plus_selections_edit.tpl')
		);

		//
		// retrieve the data for this template
		//
		$sql = "SELECT selection_name 
			FROM " . BOOKIE_SELECTIONS_TABLE . "
			WHERE selection_id = $templ_id";
		if ( !($result = $db->sql_query($sql)) ) 
		{		
			message_die(GENERAL_ERROR, 'Error in gathering selection data', '', __LINE__, __FILE__, $sql); 
		}
		$row = $db->sql_fetchrow($result);
		$this_templ_name=$row['selection_name'];
		
		$sql=" SELECT selection FROM " . BOOKIE_SELECTIONS_DATA_TABLE . "
		WHERE selection_id=$templ_id
		ORDER BY selection ASC
		";
		if ( !($result = $db->sql_query($sql)) ) 
		{		
			message_die(GENERAL_ERROR, 'Error in gathering selection data', '', __LINE__, __FILE__, $sql); 
		}
		$x=0;
		while ( $row = $db->sql_fetchrow($result) )
		{
			$x++;
			$row_color = ( !($x % 2) ) ? $theme['td_color1'] : $theme['td_color2']; 
			$row_class = ( !($x % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
	
			$template->assign_block_vars('templ_selections', array(
			'ROW_COLOR' => '#' . $row_color, 
			'ROW_CLASS' => $row_class,
			'SELECTION_NAME' => 'selection_' . $x,
			'SELECTION_VALUE' => $row['selection'],
			));
		}
		$url=append_sid("admin_bookies_plus_selections.$phpEx?&amp;mode=edit&amp;num_selections=$x&amp;templ_id=$templ_id");
		
		$template->assign_vars(array(
		'SUBMIT' => $lang['bookie_set_submitbuton'],
		'THIS_TEMPL_NAME' => $this_templ_name,
		'URL' => $url,
		'HEADER' => $lang['bookie_selections_edit_header'],
		'HEADER_EXPLAIN' => $lang['bookie_selections_edit_header_exp'],
		'SELECTION' => $lang['bookie_process_selection'],
		'TEMPL_NAME_INPUT' => $lang['bookie_template_name_input'],
		'TEMPL_NAME_INPUT_EXP' => $lang['bookie_template_name_input_exp'],
		));
		
		include('./page_header_admin.'.$phpEx);

		$template->pparse('body');

		include('./page_footer_admin.'.$phpEx);
	}
	if ( $num_selections )
	{
		//
		// ok lets update
		//
		$new_templ_name=htmlspecialchars($HTTP_POST_VARS['templ_name']);
		$sql=" UPDATE " . BOOKIE_SELECTIONS_TABLE . "
		SET selection_name='" . str_replace("\'", "''", $new_templ_name) . "'
		WHERE selection_id=$templ_id
		";
		if ( !$db->sql_query($sql) ) 
		{		
			message_die(GENERAL_ERROR, 'Error updating template name', '', __LINE__, __FILE__, $sql); 
		}
		
		$sql=" DELETE FROM " . BOOKIE_SELECTIONS_DATA_TABLE . "
		WHERE selection_id=$templ_id
		";
		if ( !$db->sql_query($sql) ) 
		{		
			message_die(GENERAL_ERROR, 'Error updating template values', '', __LINE__, __FILE__, $sql); 
		}
		
		for ( $i=1; $i<($num_selections+1); $i++ )
		{
			$selection_var='selection_' . $i;
			$new_selection=htmlspecialchars($HTTP_POST_VARS[$selection_var]);
			
			if ( $new_selection )
			{
				$sql=" INSERT INTO " . BOOKIE_SELECTIONS_DATA_TABLE . "
				(selection_id,selection) VALUES ('$templ_id','" . str_replace("\'", "''", $new_selection) . "')
				";
				if ( !$db->sql_query($sql) ) 
				{		
					message_die(GENERAL_ERROR, 'Error updating template values', '', __LINE__, __FILE__, $sql); 
				}
			}
		}
		$redirect = '<meta http-equiv="refresh" content="2;url=' . append_sid("admin_bookies_plus_selections.$phpEx?expand=$templ_id") . '#' . $templ_id . '">';
		$message=$lang['bookie_template_edit_success'] . $redirect;
		message_die(GENERAL_MESSAGE, $message);
	}
}

if ( $mode == 'add' )
{
	$templ_id=intval($HTTP_GET_VARS['templ_id']);
	$num_selections=( isset($HTTP_POST_VARS['num_templ']) ) ? intval($HTTP_POST_VARS['num_templ']) : intval($HTTP_GET_VARS['num_templ']);
	$added=htmlspecialchars($HTTP_GET_VARS['added']);
	
	if ( !$num_selections )
	{
		$template->set_filenames(array(
			'body' => 'admin/admin_bookie_plus_add_sel_template.tpl')
		);

		$url = append_sid("admin_bookies_plus_selections.$phpEx?&amp;mode=add&amp;templ_id=$templ_id");
		
		$template->assign_vars(array(
			'SUBMIT' => $lang['bookie_set_submitbuton'],
			'URL' => $url,
			'CONFIRM_SELECTION_FIELDS' => $lang['bookie_confirm_sel_fields'],
			'FIELDS_EXPLAIN' => $lang['bookie_confirm_sel_fields_exp'])
		);
		
		include('./page_header_admin.'.$phpEx);

		$template->pparse('body');

		include('./page_footer_admin.'.$phpEx);
	}
	if ( $num_selections && !$added )
	{				
		$sql=" SELECT selection_name FROM " . BOOKIE_SELECTIONS_TABLE . "
		WHERE selection_id=$templ_id
		";
		if ( !($result = $db->sql_query($sql)) ) 
		{		
			message_die(GENERAL_ERROR, 'Error in gathering selection data', '', __LINE__, __FILE__, $sql); 
		}
		$row=$db->sql_fetchrow($result);
		
		$templ_name = $row['selection_name'];
		
		$template->set_filenames(array(
			'body' => 'admin/admin_bookies_plus_selections_add.tpl')
		);

		$url = append_sid("admin_bookies_plus_selections.$phpEx?&amp;mode=add&amp;templ_id=$templ_id&amp;num_templ=$num_selections&amp;added=added");
		
		//
		// so how many new selections are we adding?
		//
		
		for ( $i=1; $i<($num_selections+1); $i++ )
		{
			$row_color = ( !($i % 2) ) ? $theme['td_color1'] : $theme['td_color2']; 
			$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
			$template->assign_block_vars('templ_selections', array(
			'ROW_COLOR' => '#' . $row_color, 
			'ROW_CLASS' => $row_class,
			'SELECTION_NAME' => 'selection_' . $i,
			'NUMBER' => $i,
			));
		}
		
		$template->assign_vars(array(
		'SUBMIT' => $lang['bookie_set_submitbuton'],
		'URL' => $url,
		'THIS_TEMPL_NAME' => $templ_name,
		'SELECTION' => $lang['bookie_add_to_template'],
		));
		
		include('./page_header_admin.'.$phpEx);

		$template->pparse('body');
		
		include('./page_footer_admin.'.$phpEx);
	}
	if ( $num_selections && $added )
	{
		//
		// OK, lets add the new selections
		//
		for ( $i=1; $i<($num_selections+1); $i++ )
		{
			$selection_var='selection_' . $i;
			$add_selection=htmlspecialchars($HTTP_POST_VARS[$selection_var]);
			
			if ( $add_selection )
			{
				$sql=" INSERT INTO " . BOOKIE_SELECTIONS_DATA_TABLE . "
				(selection_id,selection) VALUES ('$templ_id','" . str_replace("\'", "''", $add_selection) . "')
				";
				if ( !$db->sql_query($sql) ) 
				{		
					message_die(GENERAL_ERROR, 'Error inserting new selection data', '', __LINE__, __FILE__, $sql); 
				}
			}
		}
		$redirect = '<meta http-equiv="refresh" content="2;url=' . append_sid("admin_bookies_plus_selections.$phpEx?expand=$templ_id") . '#' . $templ_id . '">';
		$message=$lang['bookie_template_added_success'] . $redirect;
		message_die(GENERAL_MESSAGE, $message);	
	}
}

if ( $mode == 'drop' )
{
	$templ_id=intval($HTTP_GET_VARS['templ_id']);
	if ( !isset($HTTP_POST_VARS['yes']) && !isset($HTTP_POST_VARS['no']) )
	{
		$sql=" SELECT selection_name FROM " . BOOKIE_SELECTIONS_TABLE . "
		WHERE selection_id=$templ_id
		";
		if ( !($result = $db->sql_query($sql)) ) 
		{		
			message_die(GENERAL_ERROR, 'Error in gathering selection data', '', __LINE__, __FILE__, $sql); 
		}
		$row=$db->sql_fetchrow($result);
		
		$templ_name = $row['selection_name'];
		
		$template->set_filenames(array(
			'body' => 'admin/admin_bookies_plus_selections_delete.tpl')
		);

		$url = append_sid("admin_bookies_plus_selections.$phpEx?&amp;mode=drop&amp;templ_id=$templ_id");
	
		$template->assign_vars(array(
			'DELETE_CONFIRM' => $lang['bookie_delete_template_confirm'],
			'URL' => $url,
			'THIS_TEMPL_NAME' => $templ_name)
		);
	
		include('./page_header_admin.'.$phpEx);

		$template->pparse('body');

		include('./page_footer_admin.'.$phpEx);
	}
	else if ( isset($HTTP_POST_VARS['no']) )
	{
		$redirect = '<meta http-equiv="refresh" content="2;url=' . append_sid("admin_bookies_plus_selections.$phpEx?") . '">';
		$message=$lang['bookie_template_deletion_cancelled'] . $redirect;
		message_die(GENERAL_MESSAGE, $message);	
	}
	else
	{
		$sql=" DELETE FROM " . BOOKIE_SELECTIONS_TABLE . "
		WHERE selection_id=$templ_id
		";
		if ( !$db->sql_query($sql) ) 
		{		
			message_die(GENERAL_ERROR, 'Error deleting template', '', __LINE__, __FILE__, $sql); 
		}
		
		$sql=" DELETE FROM " . BOOKIE_SELECTIONS_DATA_TABLE . "
		WHERE selection_id=$templ_id
		";
		if ( !$db->sql_query($sql) ) 
		{		
			message_die(GENERAL_ERROR, 'Error deleting template', '', __LINE__, __FILE__, $sql); 
		}
		$redirect = '<meta http-equiv="refresh" content="2;url=' . append_sid("admin_bookies_plus_selections.$phpEx?") . '">';
		$message=$lang['bookie_template_deletion_success'] . $redirect;
		message_die(GENERAL_MESSAGE, $message);
	}
}

if ( !$mode )
{
	//
	// create an array of templates and their names
	//
	$selection_id=array();
	$selection_name=array();
	$sql=" SELECT * FROM " . BOOKIE_SELECTIONS_TABLE . "
	ORDER BY selection_name ASC
	";
	if ( !($result = $db->sql_query($sql)) ) 
	{		
		message_die(GENERAL_ERROR, 'Error in gathering selection data', '', __LINE__, __FILE__, $sql); 
	}
	while ( $row = $db->sql_fetchrow($result) )
	{
		$selection_id[]=$row['selection_id'];
		$selection_name[]=$row['selection_name'];
		
		$this_templ=$row['selection_id'];
		
		$add_url=append_sid("admin_bookies_plus_selections.$phpEx?&amp;mode=add&amp;templ_id=$this_templ");
		$drop_url=append_sid("admin_bookies_plus_selections.$phpEx?&amp;mode=drop&amp;templ_id=$this_templ");
		$expand_url=append_sid("admin_bookies_plus_selections.$phpEx?&amp;expand=$this_templ") . '#' . $this_templ;
		$edit_url=append_sid("admin_bookies_plus_selections.$phpEx?&amp;mode=edit&amp;templ_id=$this_templ");
		
		$template->assign_block_vars('selections', array(
		'EXPAND_URL' => $expand_url,
		'TEMPL_NAME' => $row['selection_name'],
		'EDIT' => '<a href="' . $edit_url . '"><img src="' . $phpbb_root_path . $images['icon_edit'] . '" alt="' . $lang['bookie_edit_template'] . '" title="' . $lang['bookie_edit_template'] . '" /></a>',
		'ADD_IMAGE' => '<a href="' . $add_url . '"><img src="' . $phpbb_root_path . $images['icon_bookie_add_selection'] . '" alt="' . $lang['icon_bookie_add_selection'] . '" title="' . $lang['icon_bookie_add_selection'] . '" /></a>',
		'DROP_IMAGE' => '<a href="' . $drop_url . '"><img src="' . $phpbb_root_path . $images['icon_bookie_drop_meeting'] . '" alt="' . $lang['icon_bookie_drop_template'] . '" title="' . $lang['icon_bookie_drop_template'] . '" /></a>',
		'ANCHOR' => $this_templ,
			));
			
		if ( isset($HTTP_GET_VARS['expand']) )
		{
			$expand_id=intval($HTTP_GET_VARS['expand']);
			if ( $expand_id == $this_templ )
			{
				$sql_expand=" SELECT selection FROM " . BOOKIE_SELECTIONS_DATA_TABLE . "
				WHERE selection_id=" . $expand_id . "
				ORDER BY selection ASC
				";
				if ( !($result_expand = $db->sql_query($sql_expand)) ) 
				{		
					message_die(GENERAL_ERROR, 'Error in gathering selection data', '', __LINE__, __FILE__, $sql_expand); 
				}
				$x=1;
				while ( $row_expand = $db->sql_fetchrow($result_expand) )
				{
					$this_selection=$row_expand['selection'];
					$row_color = ( !($x % 2) ) ? $theme['td_color1'] : $theme['td_color2']; 
					$row_class = ( !($x % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
		
					$template->assign_block_vars('selections.expansion', array(
					'ROW_COLOR' => '#' . $row_color, 
					'ROW_CLASS' => $row_class,
					'TEMPL_NAME' => $row['selection_name'],
					'SELECTION' => $this_selection,
					));
					$x++;
				}
			}
		}
	}
}
	
// Set template Vars
$add_selection_templ_url=append_sid("admin_bookies_plus_selections.$phpEx?&amp;mode=new_templ");
$template->assign_vars(array(
'EDIT_DELETE' => $lang['bookie_edit_delete_bet'],
'SELECTION' => $lang['bookie_process_selection'],
'THIS_TEMPL_NAME' => $lang['bookie_template_name'],
'IMG_NEW_TEMPL' => '<a href="' . $add_selection_templ_url . '"><img src="' . $phpbb_root_path . $images['icon_bookie_add_selection_template'] . '" alt="' . $lang['icon_bookie_add_selection_template'] . '" title="' . $lang['icon_bookie_add_selection_template'] . '" /></a>',
'HEADER' => $lang['bookie_selections_header'],
'HEADER_EXPLAIN' => $lang['bookie_selections_header_exp'],
'BOOKIE_VERSION' => $board_config['bookie_version'],
));

include('./page_header_admin.'.$phpEx);

$template->pparse('body');

include('./page_footer_admin.'.$phpEx);

?>