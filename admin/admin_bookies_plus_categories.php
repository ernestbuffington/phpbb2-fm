<?php
/** 
*
* @package admin
* @version $Id: admin_bookies_plus_categories.php,v 3.0.0 2004/11/17 17:49:33 majorflam Exp $
* @copyright (c) 2004 Majorflam
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	$module['Bookmakers']['Meetings_Categories'] = $filename;

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
	'body' => 'admin/admin_bookies_plus_categories.tpl')
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


//
// get the default category
//
$sql = "SELECT cat_name 
	FROM " . BOOKIE_CAT_TABLE . "
	WHERE cat_id = 1";
if ( !($result = $db->sql_query($sql)) ) 
{ 
	message_die(GENERAL_ERROR, 'Error retrieving default category', '', __LINE__, __FILE__, $sql); 
}
$row = $db->sql_fetchrow($result);

$default_cat = $row['cat_name'];
$add_category_url = append_sid("admin_bookies_plus_categories.$phpEx?mode=add");

//
// Are we deleting checked?
//
if ( isset($HTTP_POST_VARS['delete_marked']) )
{
	//
	// OK, let's run through the meetings and delete where appropiate
	//
	$sql = "SELECT cat_id 
		FROM " . BOOKIE_CAT_TABLE;
	if ( !($result = $db->sql_query($sql)) ) 
	{ 
		message_die(GENERAL_ERROR, 'Error retrieving categories', '', __LINE__, __FILE__, $sql); 
	}
	$deleted = 0;
	while ( $row=$db->sql_fetchrow($result) )
	{
		$check_id = 'check_' . $row['cat_id'];
		$this_check = intval($HTTP_POST_VARS[$check_id]);
		if ( $this_check )
		{
			$sql_a = "DELETE FROM " . BOOKIE_CAT_TABLE . "
				WHERE cat_id = " . $row['cat_id'];
			if ( !$db->sql_query($sql_a) ) 
			{ 
				message_die(GENERAL_ERROR, 'Error deleting category', '', __LINE__, __FILE__, $sql_a); 
			}
			$deleted++;
		}
	}
	if ( $deleted )
	{
		$redirect = '<meta http-equiv="refresh" content="2;url=' . append_sid("admin_bookies_plus_categories.$phpEx?") . '">';
		
		$message = $deleted . ' ' . $lang['bookie_category_delete_success'] . $redirect;
	
		message_die(GENERAL_MESSAGE, $message);
	}
}
if ( isset($HTTP_POST_VARS['delete_all']) )
{
	$template->set_filenames(array(
		'body' => 'admin/admin_bookies_plus_selections_delete.tpl')
	);

	$url = append_sid("admin_bookies_plus_categories.$phpEx?mode=deleteallconfirm");
	
	$template->assign_vars(array(
		'L_YES' => $lang['Yes'],
		'L_NO' => $lang['No'],
		'DELETE_CONFIRM' => $lang['bookie_delete_category_all_confirm'],
		'URL' => $url,
		'THIS_TEMPL_NAME' => $lang['bookie_delete_all_categories'])
	);
	
	include('./page_header_admin.'.$phpEx);

	$template->pparse('body');

	include('./page_footer_admin.'.$phpEx);
}
if ( $mode == 'deleteallconfirm' && isset($HTTP_POST_VARS['yes']) )
{
	$sql = "DELETE FROM " . BOOKIE_CAT_TABLE . "
		WHERE cat_id != 1";
	if ( !$db->sql_query($sql) ) 
	{ 
		message_die(GENERAL_ERROR, 'Error deleting category', '', __LINE__, __FILE__, $sql); 
	}
	
	$redirect = '<meta http-equiv="refresh" content="2;url=' . append_sid("admin_bookies_plus_categories.$phpEx?") . '">';
	
	$message = $lang['bookie_category_delete_all_success'] . $redirect;
	
	message_die(GENERAL_MESSAGE, $message);
}
if ( $mode == 'edit' )
{
	if ( !isset($HTTP_POST_VARS['edit']) )
	{
		$template->set_filenames(array(
			'body' => 'admin/admin_bookies_plus_categories_edit.tpl')
		);
		
		$cat_id = intval($HTTP_GET_VARS['cat_id']);
		
		$sql = "SELECT cat_name 
			FROM " . BOOKIE_CAT_TABLE . "
			WHERE cat_id = $cat_id";
		if ( !($result = $db->sql_query($sql)) ) 
		{ 
			message_die(GENERAL_ERROR, 'Error retrieving categories', '', __LINE__, __FILE__, $sql); 
		}
		$row = $db->sql_fetchrow($result);
		
		$cat_name = $row['cat_name'];
		$url = append_sid("admin_bookies_plus_categories.$phpEx?mode=edit&amp;cat_id=$cat_id");
	
		$template->assign_vars(array(
			'URL' => $url,
			'THIS_CAT' => $cat_name,
			'CATEGORY' => $lang['bookie_category'],
			'SUBMIT' => $lang['bookie_set_submitbuton'],
			'HEADER' => $lang['bookie_categories_edit_header'],
			'HEADER_EXPLAIN' => $lang['bookie_categories_edit_header_exp'])
		);
	
		include('./page_header_admin.'.$phpEx);

		$template->pparse('body');

		include('./page_footer_admin.'.$phpEx);
	}
	else
	{
		$cat_id = intval($HTTP_GET_VARS['cat_id']);
		$new_cat = htmlspecialchars($HTTP_POST_VARS['edit_category']);
		
		if ( strlen($new_cat) > 30 )
		{
			$redirect = '<meta http-equiv="refresh" content="2;url=' . append_sid("admin_bookies_plus_categories.$phpEx?") . '">';
			$message = $lang['bookie_category_name_long'] . $redirect;
			message_die(GENERAL_MESSAGE, $message);
		}
		
		$sql = "UPDATE " . BOOKIE_CAT_TABLE . "
			SET cat_name = '" . str_replace("\'", "''", $new_cat) . "'
			WHERE cat_id = $cat_id";
		if ( !$db->sql_query($sql) ) 
		{ 
			message_die(GENERAL_ERROR, 'Error updating Category name', '', __LINE__, __FILE__, $sql); 
		}
		
		$redirect = '<meta http-equiv="refresh" content="2;url=' . append_sid("admin_bookies_plus_categories.$phpEx?") . '">';
		
		$message = $lang['bookie_category_edit_success'] . $redirect;
		
		message_die(GENERAL_MESSAGE, $message);
	}
}
if ( $mode == 'add' )
{
	if ( !isset($HTTP_POST_VARS['add_cat']) )
	{
		$template->set_filenames(array(
			'body' => 'admin/admin_bookies_plus_categories_add.tpl')
		);
	
		$url = append_sid("admin_bookies_plus_categories.$phpEx?mode=add");
		
		$template->assign_vars(array(
			'HEADER' => $lang['icon_bookie_add_category'],
			'HEADER_EXPLAIN' => $lang['bookie_add_cat_explain'],
			'SUBMIT' => $lang['Submit'],
			'URL' => $url)
		);
		
		include('./page_header_admin.'.$phpEx);
	
		$template->pparse('body');
	
		include('./page_footer_admin.'.$phpEx);
	}
	else if ( !empty($HTTP_POST_VARS['add_cat']) )
	{
		$new_cat = htmlspecialchars($HTTP_POST_VARS['add_cat']);
		
		if ( strlen($new_cat) > 30 )
		{
			$redirect = '<meta http-equiv="refresh" content="2;url=' . append_sid("admin_bookies_plus_categories.$phpEx?mode=add") . '">';
			
			$message = $lang['bookie_category_name_long'] . $redirect;
			
			message_die(GENERAL_MESSAGE, $message);
		}
		
		$sql = "INSERT INTO " . BOOKIE_CAT_TABLE . " (cat_name) 
			VALUES ('" . str_replace("\'", "''", $new_cat) . "')";
		if ( !$db->sql_query($sql) ) 
		{ 
			message_die(GENERAL_ERROR, 'Error inserting new Category name', '', __LINE__, __FILE__, $sql); 
		}
		
		$redirect = '<meta http-equiv="refresh" content="2;url=' . append_sid("admin_bookies_plus_categories.$phpEx?") . '">';
		
		$message = $lang['bookie_category_add_success'] . $redirect;
		
		message_die(GENERAL_MESSAGE, $message);
	}
}
//
// retrieve the categories
//
$sql = "SELECT * 
	FROM " . BOOKIE_CAT_TABLE . "
	ORDER BY cat_name ASC";
if ( !($result = $db->sql_query($sql)) ) 
{ 
	message_die(GENERAL_ERROR, 'Error retrieving meetings', '', __LINE__, __FILE__, $sql); 
}

while ( $row = $db->sql_fetchrow($result) )
{
	$cat_name = $row['cat_name'];
	$cat_id = $row['cat_id'];
	$edit_url = append_sid("admin_bookies_plus_categories.$phpEx?&amp;mode=edit&amp;cat_id=$cat_id");
		
	$template->assign_block_vars('cats', array(
		'CAT' => $cat_name,
		'CHECK_NAME' => 'check_' . $cat_id,
		'EDIT_IMG' => '<a href="' . $edit_url . '"><img src="' . $phpbb_root_path . $images['acp_edit'] . '" alt="' . $lang['Edit'] . '" title="' . $lang['Edit'] . '" /></a>')
	);
		
	if ( $cat_id != 1 )
	{
		$template->assign_block_vars('cats.delete_allow', array());
	}
}
$db->sql_freeresult($result);

$template->assign_vars(array(
	'DELETE_ALL' => $lang['bookie_delete_all'],
	'DELETE_MARKED' => $lang['bookie_delete_marked'],
	'CATEGORY' => $lang['bookie_category'],
	'EDIT' => $lang['bookie_edit_category'],
	'DELETE' => $lang['bookie_delete_category'],
	'HEADER' => $lang['bookie_category_header'],
	'HEADER_EXPLAIN' => sprintf($lang['bookie_category_header_exp'], $default_cat),
	'IMG_NEW_CATEGORY' => '<a href="' . $add_category_url . '"><img src="' . $phpbb_root_path . $images['icon_bookie_add_category'] . '" alt="' . $lang['icon_bookie_add_category'] . '" title="' . $lang['icon_bookie_add_category'] . '" /></a>',
	'BOOKIE_VERSION' => $board_config['bookie_version'])
);

include('./page_header_admin.'.$phpEx);

$template->pparse('body');

include('./page_footer_admin.'.$phpEx);

?>