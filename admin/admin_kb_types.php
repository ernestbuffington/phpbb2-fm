<?php
/** 
*
* @package admin
* @version $Id: admin_kb_types.php,v 1.0.0 2003/01/05 02:36:00 psotfx Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
	$file = basename(__FILE__);
	$module['KB_title']['Types_man'] = $file;
	return;
}

function get_types($id, $select)
{
 	global $db;

    $idfield = 'id';
	$namefield = 'type';

	$sql = "SELECT *
		FROM " . KB_TYPES_TABLE;
	if( $select == 0 )
	{
		$sql .= " WHERE $idfield <> $id";
	}
	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, "Couldn't get list of types", "", __LINE__, __FILE__, $sql);
	}

	$typelist = '';
	while( $row = $db->sql_fetchrow($result) )
	{
		$typelist .= "<option value=\"$row[$idfield]\"$s>" . $row[$namefield] . "</option>\n";
	}
	$db->sql_freeresult($result);

	return($typelist);
}

//
// Load default header
//
$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);
include($phpbb_root_path . 'includes/functions_admin.'.$phpEx);

//
// Include language
//
$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_kb.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_kb.' . $phpEx);

if ( isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']) )
{
	$mode = ( isset($HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
	$mode = htmlspecialchars($mode); 
}
else
{
	if ( $create )
	{
		$mode = 'create';
	}
	else if ( $edit )
	{
		$mode = 'edit';
	}
	else if ( $delete )
	{
		$mode = 'delete';
	}
	else
	{
		$mode = '';
	}
}

switch ( $mode )
{
  	case 'create':
  		$type_name = trim($HTTP_POST_VARS['new_type_name']);
	   
	   	if ( !$type_name )
	   	{
	   		message_die(GENERAL_ERROR, $lang['Empty_type_admin']);
	   	}	  
		
	   	$sql = "INSERT INTO " . KB_TYPES_TABLE . " (type) 
			VALUES ('$type_name')";
 		if ( !($results = $db->sql_query($sql)) )
	   	{
	    	message_die(GENERAL_ERROR, "Could not create type", '', __LINE__, __FILE__, $sql);
	   	}

	   	$message = $lang['Type_created'] . '<br /><br />' . sprintf($lang['Click_return_type_manager'], '<a href="' . append_sid("admin_kb_types.$phpEx") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.$phpEx?pane=right") . '">', '</a>');

	   	message_die(GENERAL_MESSAGE, $message);	
  	   	break;

	case 'edit':
		if ( !$HTTP_POST_VARS['submit'] )
		{
   			$type_id = $HTTP_GET_VARS['cat'];
	   
	   		$sql = "SELECT * 
	   			FROM " . KB_TYPES_TABLE . " 
	   			WHERE id = '" . $type_id . "'";
		 	if ( !($results = $db->sql_query($sql)) )
	   		{
   	  			message_die(GENERAL_ERROR, 'Could not obtain kb type.', '', __LINE__, __FILE__, $sql);
	   		}
	   
	   		if ( $type = $db->sql_fetchrow($results) )
	   		{
	  	  		$type = $type['type'];
	   		}
  
			//
 	   		// Generate page
  	   		//
  	   		$template->set_filenames(array(
				'body' => 'admin/kb_cat_edit_body.tpl')
       		);

  			$template->assign_vars(array( 
	        	'L_EDIT_TITLE' => $lang['Types_man'],
	        	'L_EDIT_DESCRIPTION' => $lang['KB_types_description'],
				'L_CATEGORY' => $lang['Article_type'],
				'L_CAT_SETTINGS' => $lang['Types_man'],
				'L_ITEMS_REQUIRED' => $lang['Items_required'],
				
				'S_ACTION' => append_sid($phpbb_root_path . "admin/admin_kb_types.$phpEx?mode=edit"),
				'CAT_NAME' => $type,
			
				'S_HIDDEN' => '<input type="hidden" name="typeid" value="' . $type_id . '">')
			);
  		}
  		else if ($HTTP_POST_VARS['submit'] )
  		{
   			$type_id = $HTTP_POST_VARS['typeid'];
		   	$type_name = trim($HTTP_POST_VARS['catname']);
		   
		   	if ( !$type_name )
		   	{
		   		message_die(GENERAL_ERROR, $lang['Empty_type_admin']);
		   	}
		   
		   	$sql = "UPDATE " . KB_TYPES_TABLE . " 
		   		SET type = '" . $type_name . "' 
		   		WHERE id = " . $type_id;
			if ( !($results = $db->sql_query($sql)) )
		   	{
		    	message_die(GENERAL_ERROR, "Could not update type", '', __LINE__, __FILE__, $sql);
		   	}
	
		   	$message = $lang['Type_edited'] . '<br /><br />' . sprintf($lang['Click_return_type_manager'], '<a href="' . append_sid("admin_kb_types.$phpEx") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.$phpEx?pane=right") . '">', '</a>');
	
		   	message_die(GENERAL_MESSAGE, $message);	
	  	}
	  	break;
  
  	case 'delete':
		if ( !$HTTP_POST_VARS['submit'] )
  		{
   	   		$type_id = $HTTP_GET_VARS['cat'];
  
  	   		$sql = "SELECT *  
       			FROM " . KB_TYPES_TABLE . " 
       			WHERE id = '" . $type_id . "'";
			if ( !($cat_result = $db->sql_query($sql)) )
		   	{
		   		message_die(GENERAL_ERROR, "Could not obtain type", '', __LINE__, __FILE__, $sql);
		   	}
	
		   	if ( $type = $db->sql_fetchrow($cat_result) )
		   	{
		   		$type_name = $type['type'];
		   	}
	  
	  	   	//
	 	   	// Generate page
	  	   	//
	  	   	$template->set_filenames(array(
				'body' => 'admin/kb_cat_del_body.tpl')
	       	);

  	   		$template->assign_vars(array(
	       		'L_DELETE_TITLE' => $lang['Types_man'],
		   		'L_DELETE_DESCRIPTION' => $lang['Type_delete_desc'],
		   	
		   		'L_CAT_DELETE' => $lang['Delete'],
		   		'L_CATEGORY' => $lang['Article_type'],
		   		'L_MOVE_CONTENTS' => $lang['Change_type'],
		   		'L_DELETE_ARTICLES' => $lang['Delete_articles'],
		   		'L_DELETE' => $lang['Move_and_Delete'],
		   			
		   		'S_HIDDEN_FIELDS' => '<input type="hidden" name="typeid" value="' . $type_id .'">',
		   		'S_SELECT_TO' => get_types($type_id, 0),
		   		'S_ACTION' => append_sid('admin_kb_types.'.$phpEx.'?mode=delete'),
		   
		   		'CAT_NAME' => $type_name)
			);  
  		}
  		else if ( $HTTP_POST_VARS['submit'] )
  		{
   	   		$new_type = $HTTP_POST_VARS['move_id'];
	   		$old_type = $HTTP_POST_VARS['typeid'];
  
  	   		if ( $new_type )
	   		{  
   	      		$sql = "UPDATE " . KB_ARTICLES_TABLE . " 
   	      			SET article_type = '$new_type' 
			   		WHERE article_type = '$old_type'";
		      	if ( !($move_result = $db->sql_query($sql)) )
		      	{
		   	  		message_die(GENERAL_ERROR, "Could not update articles", '', __LINE__, __FILE__, $sql);
		      	}
		   	}
	   	
		   	$sql = "DELETE FROM " . KB_TYPES_TABLE . " 
		   		WHERE id = $old_type";
			if ( !($delete_result = $db->sql_query($sql)) )
		   	{
		   		  message_die(GENERAL_ERROR, "Could not delete type", '', __LINE__, __FILE__, $sql);
		   	}
		   	
		   	$message = $lang['Type_deleted'] . '<br /><br />' . sprintf($lang['Click_return_type_manager'], '<a href="' . append_sid("admin_kb_types.$phpEx") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.$phpEx?pane=right") . '">', '</a>');
	
		   	message_die(GENERAL_MESSAGE, $message);
		}
	  	break;
  
  	default:
 		//
 		// Generate page
 		//
 		$template->set_filenames(array(
			'body' => 'admin/kb_type_body.tpl')
  		);

	  	$template->assign_vars(array(
	    	'L_KB_TYPE_TITLE' => $lang['Types_man'],
	  	  	'L_KB_TYPE_DESCRIPTION' => $lang['KB_types_description'],
	  
  		  	'L_CREATE_TYPE' => $lang['Create_type'],
		  	'L_CREATE' => $lang['Create'],
  		  	'L_TYPE' => $lang['Article_type'],
  		  	'L_ACTION' => $lang['Action'],
	  
		  	'S_ACTION' => append_sid($phpbb_root_path . "admin/admin_kb_types.$phpEx?mode=create"))
   		);
	  
  		// Get types
  		$sql = "SELECT *  
	   	 	FROM " . KB_TYPES_TABLE;
		if ( !($cat_result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not obtain kb types.', '', __LINE__, __FILE__, $sql);
		}
	
		while ( $type = $db->sql_fetchrow($cat_result) )
		{			
			$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
			
			$template->assign_block_vars('typerow', array(
				'ROW_CLASS' => $row_class,
				'TYPE' => $type['type'],			
				'U_EDIT' => '<a href="' . append_sid('admin_kb_types.'.$phpEx.'?mode=edit&amp;cat=' . $type['id']) . '"><img src="' . $phpbb_root_path . $images['acp_edit'] . '" alt="' . $lang['Edit'] . '" title="' . $lang['Edit']  . '" /></a>',
				'U_DELETE' => '<a href="' . append_sid('admin_kb_types.'.$phpEx.'?mode=delete&amp;cat=' . $type['id']) . '"><img src="' . $phpbb_root_path . $images['acp_delete'] . '" alt="' . $lang['Delete'] . '" title="' . $lang['Delete'] . '" /></a>')
			);
			$i++;
		}
		$db->sql_freeresult($cat_result);
		break;
}

$template->pparse('body');

include('./page_footer_admin.'.$phpEx);

?>