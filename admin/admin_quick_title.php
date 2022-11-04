<?php
/** 
*
* @package admin
* @version $Id: admin_quick_title.php,v 1.5.1a 2006/06/06 11:26:00 Exp $
* @copyright (c) 2003 Xavier Olive
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
	$file = basename(__FILE__);
	$module['Forums']['Quick_Title_infos'] = $file;
	return;
}

//
// Let's set the root dir for phpBB
//
$phpbb_root_path = './../';
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);

$start = ( isset($HTTP_GET_VARS['start']) ) ? $HTTP_GET_VARS['start'] : 0;
$start = ($start < 0) ? 0 : $start;

if( isset($HTTP_GET_VARS['mode']) || isset($HTTP_POST_VARS['mode']) )
{
	$mode = ($HTTP_GET_VARS['mode']) ? htmlspecialchars($HTTP_GET_VARS['mode']) : htmlspecialchars($HTTP_POST_VARS['mode']);
}
else 
{
	//
	// These could be entered via a form button
	//
	if( isset($HTTP_POST_VARS['add']) )
	{
		$mode = 'add';
	}
	else if( isset($HTTP_POST_VARS['save']) )
	{
		$mode = 'save';
	}
	else if( isset($HTTP_POST_VARS['config']) )
	{
		$mode = 'config';
	}
	else
	{
		$mode = '';
	}
}

if( $mode != '' )
{
	if( $mode == 'edit' || $mode == 'add' )
	{
		//
		// They want to add a new title info, show the form.
		//
		$title_id = ( isset($HTTP_GET_VARS['id']) ) ? intval($HTTP_GET_VARS['id']) : 0;
		
		$s_hidden_fields = '';
		
		if( $mode == 'edit' )
		{
			if( empty($title_id) )
			{
				message_die(GENERAL_ERROR, 'No title_id');
			}

			$sql = "SELECT * 
				FROM " . TITLE_INFOS_TABLE . "  
				WHERE id = $title_id";
			if(!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not obtain title data', '', __LINE__, __FILE__, $sql);
			}
			
			$title_info = $db->sql_fetchrow($result);
			
			$s_hidden_fields .= '<input type="hidden" name="id" value="' . $title_id . '" />';
		}
		else
		{

		}

		$s_hidden_fields .= '<input type="hidden" name="mode" value="save" />';
		
		$template->set_filenames(array(
			'body' => 'admin/quick_topic_title_edit_body.tpl')
		);

		$title_pos_left = ( $title_info['title_pos'] ) ? ' checked="checked"' : '';
		$title_pos_right = ( !$title_info['title_pos'] ) ? ' checked="checked"' : '';

		if ( !empty($title_info['info_color']) )
		{
			$template->assign_block_vars('no_info_color', array());
		}

		$template->assign_vars(array(
			'S_TITLE_ACTION' => append_sid('admin_quick_title.'.$phpEx),
			'S_HIDDEN_FIELDS' => $s_hidden_fields,

			'TITLE_INFO' => str_replace("\"", "'", $title_info['title_info']),
			'ADMIN_CHECKED' => ($title_info['admin_auth'] == 1) ? ' checked="checked"' : '',
			'SUPERMOD_CHECKED' => ($title_info['supermod_auth'] == 1) ? ' checked="checked"' : '',
			'MOD_CHECKED' => ($title_info['mod_auth'] == 1) ? ' checked="checked"' : '',
			'POSTER_CHECKED' => ($title_info['poster_auth'] == 1) ? ' checked="checked"' : '',
			'DATE_FORMAT' => $title_info['date_format'],
			'S_POS_LEFT' => $title_pos_left,
			'S_POS_RIGHT' => $title_pos_right,
			'COLOR_INFO' => str_replace("\"", "'", $title_info['info_color']),
			
			'ADMIN_TITLE' => $lang['Quick_Title_infos'],
			'ADMIN_TITLE_EXPLAIN' => $lang['Quick_title_explain'],	
			'L_ITEMS_REQUIRED' => $lang['Items_required'],
			'L_TITLE' => $lang['Quick_Title'],
			'L_PERM_INFO' => $lang['Permissions'],
			'L_PERM_EXPLAIN' => $lang['Title_perm_info_explain'],
			'ADMIN' => $lang['Auth_Admin'],
		    'SUPERMOD' => $lang['Auth_SuperMod'],
			'MODERATOR' => $lang['Moderator'],
			'POSTER' => $lang['Poster'],
			'L_DATE_FORMAT' => $lang['Date'],
			'L_DATE_FORMAT_EXPLAIN' => $lang['Date_format_explain'],		
			'L_TITLE_POS' => $lang['Title_pos'],
			'L_TITLE_POS_EXPLAIN' => $lang['Title_pos_explain'],
			'L_RIGHT' => $lang['text_right'],
			'L_LEFT' => $lang['text_left'],

			'L_COLOR_INFO' => $lang['Font_color'],
			'L_COLOR_INFO_EXPLAIN' => $lang['Color_info_explain'],

			'I_PICK_COLOR' => $phpbb_root_path . $images['acp_icon_pickcolor'])
		);		
	}
	else if( $mode == 'save' )
	{
		//
		// Ok, they sent us our info, let's update it.
		//	
		$title_id = ( isset($HTTP_POST_VARS['id']) ) ? intval($HTTP_POST_VARS['id']) : 0;
		$name = ( isset($HTTP_POST_VARS['title_info']) ) ? trim($HTTP_POST_VARS['title_info']) : '';
		$admin = (!empty($HTTP_POST_VARS['admin_auth']) ) ? 1 : 0;
		$supermod = (!empty($HTTP_POST_VARS['supermod_auth']) ) ? 1 : 0;
		$mod = (!empty($HTTP_POST_VARS['mod_auth']) ) ? 1 : 0;
		$poster = (!empty($HTTP_POST_VARS['poster_auth']) ) ? 1 : 0;
		$date = ( isset($HTTP_POST_VARS['date_format']) ) ? trim($HTTP_POST_VARS['date_format']) : '';
		$title_pos = (!empty($HTTP_POST_VARS['title_pos']) ) ? 1 : 0 ;
		$info_color = ( isset($HTTP_POST_VARS['info_color']) ) ? trim($HTTP_POST_VARS['info_color']) : '';

		if( $name == '' )
		{
			message_die(GENERAL_MESSAGE, $lang['Must_select_title'] . '<br /><br />' . sprintf($lang['Click_return_titleadmin'], '<a href="' . append_sid('admin_quick_title.'.$phpEx.'?mode=add') . '">', '</a>'));
		}

		if ( !empty($info_color) )
		{
			if ( ctype_xdigit($info_color) == false || strlen($info_color) <> 6 )
			{
				message_die(GENERAL_MESSAGE, $lang['Color_error'] . '<br /><br />' . sprintf($lang['Click_return_titleadmin'], '<a href="' . append_sid('admin_quick_title.'.$phpEx.'?mode=add') . '">', '</a>'));
			}
		}

		if ($title_id)
		{
			$sql = "UPDATE " . TITLE_INFOS_TABLE . " 
				SET title_info = '" . str_replace("\'", "''", $name) . "', info_color = '" . str_replace("\'", "''", $info_color) . "', date_format = '" . str_replace("\'", "''", $date) . "', title_pos = $title_pos, admin_auth = $admin, supermod_auth = $supermod, mod_auth = $mod, poster_auth = $poster
				WHERE id = $title_id";
			$message = $lang['Title_updated'];
		}
		else
		{
			$sql = "INSERT INTO " . TITLE_INFOS_TABLE . " (title_info, info_color, admin_auth, supermod_auth, mod_auth, poster_auth, date_format, title_pos)
				VALUES ('" . str_replace("\'", "''", $name) . "', '" . str_replace("\'", "''", $info_color) . "', $admin, $supermod, $mod, $poster,'" . str_replace("\'", "''", $date) . "', $title_pos)";
			$message = $lang['Title_added'];
		}
		
		if( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not update/insert into title_infos table', '', __LINE__, __FILE__, $sql);
		}

		$message .= '<br /><br />' . sprintf($lang['Click_return_titleadmin'], "<a href=\"" . append_sid("admin_quick_title.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

		message_die(GENERAL_MESSAGE, $message);
	}
	else if( $mode == 'delete' )
	{
		//
		// Ok, they want to delete their title
		//
		if( isset($HTTP_POST_VARS['id']) || isset($HTTP_GET_VARS['id']) )
		{
			$title_id = ( isset($HTTP_POST_VARS['id']) ) ? intval($HTTP_POST_VARS['id']) : intval($HTTP_GET_VARS['id']);
		}
		else
		{
			$title_id = 0;
		}
		
		if( $title_id )
		{
			$sql = "DELETE FROM " . TITLE_INFOS_TABLE . "  
				WHERE id = $title_id";
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not delete title data.', '', __LINE__, __FILE__, $sql);
			}
			$message = $lang['Title_removed'] . '<br /><br />' . sprintf($lang['Click_return_titleadmin'], "<a href=\"" . append_sid("admin_quick_title.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

			message_die(GENERAL_MESSAGE, $message);
		}
		else
		{
			message_die(GENERAL_MESSAGE, $lang['Must_select_title']);
		}
	}
	else if( $mode == 'config' )
	{
		$config_value = ( isset($HTTP_POST_VARS['enable_quick_titles']) ) ? intval($HTTP_POST_VARS['enable_quick_titles']) : 0;

		$sql = "UPDATE " . CONFIG_TABLE . "
			SET config_value = $config_value
			WHERE config_name = 'enable_quick_titles'";
		if ( !($result = $db->sql_query($sql)) ) 
		{ 
			message_die(GENERAL_ERROR, "Failed to update general configuration for admin_quick_title", "", __LINE__, __FILE__, $sql);
		}
	
		// Remove cache file
		@unlink($phpbb_root_path . 'cache/config_board.'.$phpEx);
	
		$message = $lang['Board_config_updated'] . "<br /><br />" . sprintf($lang['Click_return_titleadmin'], "<a href=\"" . append_sid("admin_quick_title.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");
	
		message_die(GENERAL_MESSAGE, $message);
	}
	else
	{
		/**
		* They didn't feel like giving us any information. Oh, too bad, we'll just display the list then...
		*/
		$template->set_filenames(array(
			'body' => 'admin/quick_topic_title_body.tpl')
		);

		$sql = "SELECT * 
			FROM " . TITLE_INFOS_TABLE . "  
			ORDER BY id";
		if( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Couldn\'t obtain title data', '', __LINE__, __FILE__, $sql);
		}

		$title_rows = $db->sql_fetchrowset($result);
		$title_count = sizeof($title_rows);

		$template->assign_vars(array(
			'ADMIN_TITLE' => $lang['Quick_Title_infos'],
			'ADMIN_TITLE_EXPLAIN' => $lang['Quick_title_explain'],
			'ADD_NEW' => $lang['Add_new'],
		
			'HEAD_AUTH' => $lang['Permissions'],
			'HEAD_DATE' => $lang['Date'],
			'HEAD_POS' => $lang['Title_pos'],
			'L_EDIT' => '<img src="' . $phpbb_root_path . $images['acp_edit'] . '" alt="' . $lang['Edit'] . '" title="' . $lang['Edit'] . '" />',
			'L_DELETE' => '<img src="' . $phpbb_root_path . $images['acp_delete'] . '" alt="' . $lang['Delete'] . '" title="' . $lang['Delete'] . '" />',

			'L_CONFIGURATION' => $lang['Configuration'],
			'L_ENABLE_QUICK_TITLES' => $lang['Enable_quick_titles'],
			'ENABLE_QUICK_TITLES_YES' => ( $board_config['enable_quick_titles'] ) ? 'checked="checked"' : '',
			'ENABLE_QUICK_TITLES_NO' => ( !$board_config['enable_quick_titles'] ) ? 'checked="checked"' : '', 
			'U_CONFIG_ACTION' => append_sid('admin_quick_title.'.$phpEx),

			'PAGINATION' => $pagination,
			'S_TITLE_ACTION' => append_sid("admin_quick_title.$phpEx"))
		);

		for( $i = 0; $i < $title_count; $i++)
		{
			$title_id  = $title_rows[$i]['id'];
			
			$perm = ($title_rows[$i]['admin_auth'] == 1) ? '<b style="color: #' . $theme['adminfontcolor'] . '">' . $lang['Auth_Admin'] . '</b><br />' : '';
			$perm .= ($title_rows[$i]['supermod_auth'] == 1) ? '<b style="color: #' . $theme['supermodfontcolor'] . '">' . $lang['Auth_SuperMod'] . '</b><br />' : '';
			$perm .= ($title_rows[$i]['mod_auth'] == 1) ? '<b style="color: #' . $theme['modfontcolor'] . '">' . $lang['Moderator'] . '</b><br />' : '';
			$perm .= ($title_rows[$i]['poster_auth'] == 1) ? $lang['Poster'] : '';
	
			$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

			$template->assign_block_vars("title", array(
				'ROW_CLASS'      => $row_class,
				'TITLE'          => $title_rows[$i]['title_info'],
				'COLOR_INFO'     => trim($title_rows[$i]['info_color']),
				'PERMISSIONS'    => $perm,
				'DATE_FORMAT'    => $title_rows[$i]['date_format'],
				'TITLE_POS'      => $title_rows[$i]['title_pos'],

				'U_TITLE_EDIT'   => append_sid("admin_quick_title.$phpEx?mode=edit&amp;id=$title_id"),
				'U_TITLE_DELETE' => append_sid("admin_quick_title.$phpEx?mode=delete&amp;id=$title_id"))
			);
		}
	}
}
else
{
	//
	// Show the default page
	//
	$template->set_filenames(array(
		'body' => 'admin/quick_topic_title_body.tpl')
	);

	$sql = "SELECT * 
		FROM " . TITLE_INFOS_TABLE . "  
		ORDER BY id
		LIMIT $start, " . $board_config['topics_per_page'];
	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain title data.', '', __LINE__, __FILE__, $sql);
	}
		
	$title_rows = $db->sql_fetchrowset($result);
	$title_count = sizeof($title_rows);

	$sql = "SELECT count(*) AS total
		FROM " . TITLE_INFOS_TABLE;
	if ( !($result = $db->sql_query($sql)) ) 
	{ 
		message_die(GENERAL_ERROR, 'Error getting total informations for title', '', __LINE__, __FILE__, $sql); 
	}

	if ( $total = $db->sql_fetchrow($result) ) 
	{ 
		$total_records = $total['total']; 
	
		$pagination = generate_pagination("admin_quick_title.$phpEx?mode=$mode", $total_records, $board_config['topics_per_page'], $start). ' '; 
	} 

	$template->assign_vars(array(
		'ADMIN_TITLE' => $lang['Quick_Title_infos'],
		'ADMIN_TITLE_EXPLAIN' => $lang['Quick_title_explain'],
		'ADD_NEW' => $lang['Add_new'],

		'L_CONFIGURATION' => $lang['Configuration'],
		'L_ENABLE_QUICK_TITLES' => $lang['Enable_quick_titles'],
		'ENABLE_QUICK_TITLES_YES' => ( $board_config['enable_quick_titles'] ) ? 'checked="checked"' : '',
		'ENABLE_QUICK_TITLES_NO' => ( !$board_config['enable_quick_titles'] ) ? 'checked="checked"' : '', 
		'U_CONFIG_ACTION' => append_sid('admin_quick_title.'.$phpEx),
		
		'HEAD_AUTH' => $lang['Permissions'],
		'HEAD_DATE' => $lang['Date'],
		'HEAD_POS' => $lang['Title_pos'],
		'L_EDIT' => '<img src="' . $phpbb_root_path . $images['acp_edit'] . '" alt="' . $lang['Edit'] . '" title="' . $lang['Edit'] . '" />',
		'L_DELETE' => '<img src="' . $phpbb_root_path . $images['acp_delete'] . '" alt="' . $lang['Delete'] . '" title="' . $lang['Delete'] . '" />',

		'PAGINATION' => $pagination,
		'S_TITLE_ACTION' => append_sid("admin_quick_title.$phpEx"))
	);
		
	for( $i = 0; $i < $title_count; $i++)
	{
		$title_id = $title_rows[$i]['id'];
		
		$perm = ($title_rows[$i]['admin_auth'] == 1) ? '<b style="color: #' . $theme['adminfontcolor'] . '">' . $lang['Auth_Admin'] . '</b><br />' : '';
		$perm .= ($title_rows[$i]['supermod_auth'] == 1) ? '<b style="color: #' . $theme['supermodfontcolor'] . '">' . $lang['Auth_SuperMod'] . '</b><br />' : '';
		$perm .= ($title_rows[$i]['mod_auth'] == 1) ? '<b style="color: #' . $theme['modfontcolor'] . '">' . $lang['Moderator'] . '</b><br />' : '';
		$perm .= ($title_rows[$i]['poster_auth'] == 1) ? $lang['Poster'] : '';

	    if ( $title_rows[$i]['title_pos'] )
	    {
			$title_pos_translation = $lang['text_left'];
		}
		else
		{
			$title_pos_translation = $lang['text_right'];
		}

		$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

		$template->assign_block_vars("title", array(
			'ROW_CLASS' => $row_class,
			'TITLE' => $title_rows[$i]['title_info'],
			'COLOR_INFO' => trim($title_rows[$i]['info_color']),
			'PERMISSIONS' => $perm,
			'DATE_FORMAT' => $title_rows[$i]['date_format'],
			'TITLE_POS' => $title_pos_translation,

			'U_TITLE_EDIT' => append_sid("admin_quick_title.$phpEx?mode=edit&amp;id=$title_id"),
			'U_TITLE_DELETE' => append_sid("admin_quick_title.$phpEx?mode=delete&amp;id=$title_id"))
		);	
	}
}

$template->pparse('body');

include('./page_footer_admin.'.$phpEx);

?>