<?php
/** 
*
* @package admin_mod
* @version $Id: admin_smilies.php,v 1.51.2.15 2006/02/10 22:19:01 grahamje Exp $
* @copyright (c) 2001 The phpBB Group, 2004 Afkamm
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', 1);

//
// First we do the setmodules stuff for the admin cp.
//
if ( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	$module['General']['Smilies'] = $filename;

	return;
}

//
// Load default header
//
$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');

$cancel = ( isset($HTTP_POST_VARS['cancel']) || isset($_POST['cancel']) ) ? true : false;
$no_page_header = $cancel;

if ((!empty($HTTP_GET_VARS['export_pack']) && $HTTP_GET_VARS['export_pack'] == 'send') || (!empty($_GET['export_pack']) && $_GET['export_pack'] == 'send'))
{	
	$no_page_header = true;
}

require('./pagestart.' . $phpEx);

if ($cancel)
{
	redirect('admin_mod/' . append_sid("admin_smilies.$phpEx", true));
}

//
// Include language
//
$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_admin_smiley_categories.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_admin_smiley_categories.' . $phpEx);

// Module Activation
if ($board_config['enable_module_smilies'])
{
	//
	// Check to see what mode we should operate in.
	//
	if ( isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']) )
	{
		$mode = ( isset($HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
		$mode = htmlspecialchars($mode);
	}
	else
	{
	$mode = '';
	}

	//
// Function for reading the smilies/paks in the smiley directory.
	//
function read_smiles_directory($path)
{
	global $smiley_images, $smiley_paks, $phpbb_root_path;

	$dir = $phpbb_root_path . $path;
	$dir2 = @opendir($dir);

	$smiley_images = $smiley_paks = array();
	$array_count = 0;
	while( $file = @readdir($dir2) )
	{
		if( !@is_dir(phpbb_realpath($dir . '/' . $file)) )
		{
			$img_size = @getimagesize($dir . '/' . $file);

			if ( $img_size[0] && $img_size[1] )
			{
				if( !($file == 'blank_icon.gif') )
				{
				$smiley_images[] = $file;
					$array_count++;
				}
			}
			else if ( eregi('.pak$', $file) )
			{
				$smiley_paks[] = $file;
			}
			else if( eregi('.pak2$', $file) )
			{	
				$smiley_paks[] = $file;
			}
		}
	}

	@closedir($dir);

	// Sorting an empty array sends out a PHP error message. :-/
	if( $array_count )
	{
		sort($smiley_images);
	}
}

//
// Get all the data for the categories and put them into an array.
//
$sql = "SELECT *
	FROM " . SMILIES_CAT_TABLE . "
	ORDER BY cat_order";
if( $result = $db->sql_query($sql) )
{
	if( $number = $db->sql_numrows($result) )
{
		$num_cats = 0;
		$array_cat_data = array();

		while( $cats = $db->sql_fetchrow($result) )
		{
			// Note to self, multi-array is ordered by cat_order 
			// and because arrays start at '0', use '-1' to get correct data!

			$array_cat_data[$num_cats]['cat_id'] = $cats['cat_id'];
			$array_cat_data[$num_cats]['cat_name'] = stripslashes($cats['cat_name']);
			$array_cat_data[$num_cats]['description'] = stripslashes($cats['description']);
			$array_cat_data[$num_cats]['cat_order'] = $cats['cat_order'];
			$array_cat_data[$num_cats]['cat_perms'] = $cats['cat_perms'];
			$array_cat_data[$num_cats]['cat_group'] = $cats['cat_group'];
			$array_cat_data[$num_cats]['cat_forum'] = $cats['cat_forum'];
			$array_cat_data[$num_cats]['cat_special'] = $cats['cat_special'];
			$array_cat_data[$num_cats]['cat_open'] = $cats['cat_open'];
			$array_cat_data[$num_cats]['cat_icon_url'] = $cats['cat_icon_url'];
			$array_cat_data[$num_cats]['smilies_popup'] = $cats['smilies_popup'];

			$num_cats++;
		}
		$db->sql_freeresult($result);
	}
	else
	{
		// If there are no categories, then automatically create one. =D
		$sql = "SELECT forum_id
			FROM " . FORUMS_TABLE . "
			WHERE forum_id > 0
			ORDER BY forum_order";
	if ( !($result = $db->sql_query($sql)) )
	{
			message_die(GENERAL_ERROR, 'Could not obtain forums information', '', __LINE__, __FILE__, $sql);
	}

		$forum_ids = array();

	while( $row = $db->sql_fetchrow($result) )
	{
			$forum_ids[] = $row;
	}
		$db->sql_freeresult($result);
	
		if( $count = count($forum_ids) )
		{
			$forum_string = '';
			for( $i=0; $i<$count; $i++ )
	{
				$forum_string .= $forum_ids[$i]['forum_id'] . ' ';
			}
			$forum_string .= 999;
			$forum_string = trim($forum_string);
			
			$sql = "INSERT INTO " . SMILIES_CAT_TABLE . " (cat_name, description, cat_order, cat_perms, cat_forum, cat_special, smilies_popup)
				VALUES ('phpBB', 'The Default phpBB2 Smilies', 1, 10, '" . $forum_string . "' , -2, '410|300|8|1|0|0')";
			if( !$result = $db->sql_query($sql) )
		{
				message_die(GENERAL_ERROR, "Couldn't insert new category into database.", "", __LINE__, __FILE__, $sql);
			}
			
			$sql = "SELECT smilies_id
				FROM " . SMILIES_TABLE . "
				ORDER BY smilies_id";
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't get smiley data from database", "", __LINE__, __FILE__, $sql);
			}

			$smiley_ids = array();
			while( $row = $db->sql_fetchrow($result) )
			{
				$smiley_ids[] = $row;
		}
			$db->sql_freeresult($result);
			
			for($i=0; $i<count($smiley_ids); $i++ )
			{
				$sql = "UPDATE " . SMILIES_TABLE . "
					SET cat_id = 1, smilies_order = '" . ($i + 1) . "'
					WHERE smilies_id = " . $smiley_ids[$i]['smilies_id'];
				if( !$result = $db->sql_query($sql) )
		{
					message_die(GENERAL_ERROR, "Couldn't update smiley database.", "", __LINE__, __FILE__, $sql);
				}
			}
			
			$num_cats = 1;			
		}
	}
		}
else
{
	message_die(GENERAL_ERROR, "Couldn't obtain smiley category data for arrays from database", "", __LINE__, __FILE__, $sql);
	}

//
// Select main mode
//
if( isset($HTTP_POST_VARS['cat_add']) || isset($HTTP_GET_VARS['cat_add']) )
{
	//
	// Display the "Add a Category" page.
	//
	if( $board_config['smilie_usergroups'] )
	{
		// Get usergroup details for dropdown menu.
		$sql = "SELECT group_id, group_name
			FROM " . GROUPS_TABLE . "
			WHERE group_single_user <> " . TRUE . "
			ORDER BY group_name";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Error getting group information', '', __LINE__, __FILE__, $sql);
		}

		$usergroup_ids = array();

		while( $row = $db->sql_fetchrow($result) )
		{
			$usergroup_ids[] = $row;
		}
		$db->sql_freeresult($result);

		if( $total_usergroups = count($usergroup_ids) )
		{
			$usergroups = '';
			for( $i=0; $i<$total_usergroups; $i++ )
			{
				$bg_colour = ( !($i % 2) ) ? '#CCFFCC' : '#FFFFCC';
				$usergroups .= '<option value="' . $usergroup_ids[$i]['group_id'] . '" style="background-color: ' . $bg_colour . ';">' . $usergroup_ids[$i]['group_name'] . '</option>';
			}
		}

		$template->assign_block_vars("usergroups", array(
			"SIZE1" => $total_usergroups,
			"S_USERGROUPS" => $usergroups)
		);
	}

	// Get forum details for dropdown menu.
	$sql = "SELECT c.cat_id, c.cat_title, f.forum_id, f.forum_name
		FROM " . CATEGORIES_TABLE . " c, " . FORUMS_TABLE . " f
		WHERE f.cat_id = c.cat_id
		ORDER BY c.cat_order, f.forum_order";
	if( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain forums information', '', __LINE__, __FILE__, $sql);
	}

	$forum_ids = array();

	while( $row = $db->sql_fetchrow($result) )
	{
		$forum_ids[] = $row;
}
	$db->sql_freeresult($result);

	// Create forum dropdown menu
	if( $total_forums = count($forum_ids) )
{
		$total_cats = 0;
		for( $j=0; $j<$total_forums; $j++ )
	{
			$id = ( $id != 0 ) ? $id : 0;
			if( $forum_ids[$j]['cat_id'] != $id )
	{
				$forum_list .= '<option value="-1" style="background-color: #' . $theme['hr_color9'] . '">' . $forum_ids[$j]['cat_title'] . '</option>';
				$id = $forum_ids[$j]['cat_id'];
				$total_cats++;
			}
			$forum_list .=  '<option value="' . $forum_ids[$j]['forum_id'] . '">' . $forum_ids[$j]['forum_name'] . '</option>';
		}
	}

	$forum_list .= '<option value="-1" style="background-color: #' . $theme['hr_color9'] . '">&nbsp;</option>';
	$forum_list .= '<option value="999">' . $lang['private_messages'] . '</option>';

	// Create view permissions dropdown list.
	$view_permissions = '';
	while( list($key, $val) = each($lang['perms']) )
	{
		$view_permissions .= '<option value="' . $key . '">' . $val . '</option>';
	}

	// Create dropdown smiley filename list.
	read_smiles_directory($board_config['smilie_icon_path']);

	// Create category icon dropdown list.
	$category_icon = '';
	for( $i=0; $i<count($smiley_images); $i++ )
			{
		$category_icon .= '<option value="' . $smiley_images[$i] . '">' . $smiley_images[$i] . '</option>';
			}
	$category_icon2 = '<option value="blank_icon.gif">' . $lang['select_cat_icon'] . '</option>' . $category_icon;

	// Create category order dropdown list.
	$category_order = '';
	for( $i=0; $i<$num_cats; $i++ )
	{
		$j = $i + 1;
		$category_order .= '<option value="' . $array_cat_data[$i]['cat_id'] . '|' . $array_cat_data[$i]['cat_order'] . '">' . $j . '</option>';
		}
	
	// Create special dropdown list.
	$cat_special = '';
	while( list($key, $val) = each($lang['special']) )
	{
		$cat_special .= '<option value="' . $key . '">' . $val . '</option>';
	}

	$s_hidden_fields = '<input type="hidden" name="cat_id" value="" />';

	$template->set_filenames(array(
		"body" => "admin/smile_catadd_body.tpl")
	);

	$template->assign_vars(array(
		"L_ADD" => $lang['Add_cat'],
		'L_ITEMS_REQUIRED' => $lang['Items_required'],
		"L_ADD_DESC" => $lang['add_desc'],
		"L_CAT_NAME" => $lang['cat_name'],
		"L_CAT_NAME_EXPLAIN" => $lang['add_desc_explain'],
		"L_CAT_DESC" => $lang['cat_description'],
		"L_CAT_DESC_EXPLAIN" => $lang['add_desc_explain'],
		"L_CAT_ICON" => $lang['cat_icon'],
		"L_VIEWABLE_BY" => $lang['viewable_by'],
		"L_VIEWABLE_BY_EXPLAIN" => $lang['viewable_by_explain'],
		"L_USERGROUPS" => $lang['usergroups'],
		"L_USERGROUPS_EXPLAIN" => $lang['usergroups_explain'],
		"L_FORUMS" => $lang['forums'],
		"L_FORUMS_EXPLAIN" => $lang['forums_explain'],
		"L_PERMS0" => $lang['perms']['50'],
		"L_PERMS1" => $lang['perms']['10'],
		"L_PERMS2" => $lang['perms']['20'],
		"L_PERMS3" => $lang['perms']['30'],
		"L_PERMS4" => $lang['perms']['40'],
		"L_ORDER" => $lang['order_position'],
		"L_FIRST" => $lang['order_first'],
		"L_LAST" => $lang['order_last'],
		"L_AFTER" => $lang['order_after'],
		"L_ORDER_CHANGE" => $lang['order_change'],
		"L_CAT_SPECIAL" => $lang['cat_special'],
		"L_CAT_SPECIAL_EXPLAIN" => $lang['cat_special_explain'],
		"L_CAT_OPEN" => $lang['cat_open'],
		"L_CAT_OPEN_EXPLAIN" => $lang['cat_open_explain'],
		"L_POPUP_WINDOW" => $lang['popup_title'],
		"L_POPUP_DESCRIPTION" => $lang['popup_description'],
		"L_POPUP_GROUP_LIST" => $lang['popup_group_list'],
		"L_POPUP_GROUP" => $lang['popup_group'],
		"L_POPUP_LIST" => $lang['popup_list'],
		"L_POPUP_GROUP_COLS" => $lang['popup_group_cols'],
		"L_POPUP_LIST_COLS" => $lang['popup_list_cols'],
		"L_PER_PAGE" => $lang['smilies_per_page'],
		"L_PER_PAGE_LIMIT" => $lang['smilies_no_limit'],
		"L_POPUP_X" => $lang['popup_x'],
		"L_POPUP_SIZE" => $lang['popup_size'],
		"L_POPUP_SIZE_ATTRIBS" => $lang['popup_size_attribs'],

		"CAT_COUNT" => $num_cats,
		"SIZE2" => $total_forums + $total_cats + 2,
		"S_HIDDEN_FIELDS" => $s_hidden_fields,
		"S_FORUMS" => $forum_list,
		"S_CAT_ICON" => $category_icon2,
		"S_CAT_ORDER" => $category_order,
		"S_VIEW_PERMS" => $view_permissions,
		"S_CAT_SPECIAL" => $cat_special,
		"U_SMILEY_IMG" => $phpbb_root_path . $board_config['smilie_icon_path'] . '/blank_icon.gif',
		"U_SMILEY_BASEDIR" => $phpbb_root_path . $board_config['smilie_icon_path'],
		"U_SMILEY_ACTION" => append_sid("admin_smilies.$phpEx"))
	);
}
else if( isset($HTTP_POST_VARS['cat_add_submit']) || isset($HTTP_GET_VARS['cat_add_submit']) )
{
	//
	// "Add a category" data has been submitted.
	//

		//
	// Get the submitted data being careful to ensure the the data
	// we recieve and process is only the data we are looking for.
		//
	$cat_name = ( isset($HTTP_POST_VARS['cat_name']) ) ? trim($HTTP_POST_VARS['cat_name']) : '';
	$cat_desc = ( isset($HTTP_POST_VARS['cat_desc']) ) ? trim($HTTP_POST_VARS['cat_desc']) : '';
	$cat_icon = ( isset($HTTP_POST_VARS['cat_icon']) ) ? trim($HTTP_POST_VARS['cat_icon']) : '';
	$cat_perms = ( isset($HTTP_POST_VARS['cat_view_perms']) ) ? intval($HTTP_POST_VARS['cat_view_perms']) : '';
	$cat_groups = ( isset($HTTP_POST_VARS['cat_groups']) ) ? $HTTP_POST_VARS['cat_groups'] : ''; // Array.
	$cat_forums = ( isset($HTTP_POST_VARS['cat_forums']) ) ? $HTTP_POST_VARS['cat_forums'] : ''; // Array.
	$order = ( isset($HTTP_POST_VARS['order']) ) ? trim($HTTP_POST_VARS['order']) : '';
	$special = ( isset($HTTP_POST_VARS['special']) ) ? intval($HTTP_POST_VARS['special']) : '';
	$cat_open = ( isset($HTTP_POST_VARS['cat_open']) ) ? intval($HTTP_POST_VARS['cat_open']) : '';
	$popup_group_list = ( isset($HTTP_POST_VARS['popup_group_list']) ) ? intval($HTTP_POST_VARS['popup_group_list']) : '';
	$popup_group_cols = ( isset($HTTP_POST_VARS['popup_group_cols']) ) ? intval($HTTP_POST_VARS['popup_group_cols']) : '';
	$popup_list_cols = ( isset($HTTP_POST_VARS['popup_list_cols']) ) ? intval($HTTP_POST_VARS['popup_list_cols']) : '';
	$popup_per_page = ( isset($HTTP_POST_VARS['popup_per_page']) ) ? intval($HTTP_POST_VARS['popup_per_page']) : '';
	$popup_width = ( isset($HTTP_POST_VARS['popup_width']) ) ? intval($HTTP_POST_VARS['popup_width']) : '';
	$popup_height = ( isset($HTTP_POST_VARS['popup_height']) ) ? intval($HTTP_POST_VARS['popup_height']) : '';

	// Check to make sure that both boxes were filled, if not then complain.
	if( ($cat_name != '') || ($cat_desc != '') )
			{
		$cat_icon = ( $cat_icon == 'blank_icon.gif' ) ? '' : $cat_icon;

		$group_string = '';
		for( $i=0; $i<count($cat_groups); $i++ )
		{
			if( $cat_groups[$i] != '-1' )
			{
				$group_string .= $cat_groups[$i] . ' ';
			}
		}
		$group_string = trim($group_string);

		$forum_string = '';
		for( $i=0; $i<count($cat_forums); $i++ )
		{
			if( $cat_forums[$i] != '-1' )
			{
				$forum_string .= $cat_forums[$i] . ' ';
			}
		}
		$forum_string = trim($forum_string);

		// Quotes aren't good, so remove them.
		$cat_name = str_replace("'", "", $cat_name);
		$cat_name = str_replace("\"", "", $cat_name);
		$cat_desc = str_replace("'", "", $cat_desc);
		$cat_desc = str_replace("\"", "", $cat_desc);

		if( $order == 'last' )
		{
			// Increase category total by 1.
			$num_cats++;

			// Insert the new category. cat_id is 'auto_increment' and hidden has a default value of 1 inserted.
			$sql = "INSERT INTO " . SMILIES_CAT_TABLE . " (cat_name, description, cat_order, cat_perms, cat_group, cat_forum, cat_special, cat_open, cat_icon_url, smilies_popup)
				VALUES ('" . $cat_name . "','" . $cat_desc . "','" . $num_cats . "','" . $cat_perms . "','" . $group_string . "','" . $forum_string . "','" . $special . "','" . $cat_open . "','" . $cat_icon . "','" . $popup_width . "|" . $popup_height . "|" . $popup_group_cols . "|" . $popup_list_cols . "|" . $popup_group_list . "|" . $popup_per_page . "')";
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't insert new category into database.", "", __LINE__, __FILE__, $sql);
			}
			else
			{
				$message = $lang['add_success'] . "<br /><br />" . sprintf($lang['Click_return_cat_add'], "<a href=\"" . append_sid("admin_smilies.$phpEx?cat_add") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx") . "\">", "</a>");
			}
		}
		else if( $order == 'first' )
		{
			// Update the category order.
			$sql = "UPDATE " . SMILIES_CAT_TABLE . "
				SET cat_order = cat_order + 1";
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't update category database.", "", __LINE__, __FILE__, $sql);
			}
			else
			{
				// Update the smilies category order.
				$sql = "UPDATE " . SMILIES_TABLE . "
					SET cat_id = cat_id + 1";
				if( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't update smiley database.", "", __LINE__, __FILE__, $sql);
				}

				$sql = "INSERT INTO " . SMILIES_CAT_TABLE . " (cat_name, description, cat_order, cat_perms, cat_group, cat_forum, cat_special, cat_open, cat_icon_url, smilies_popup)
					VALUES ('" . $cat_name . "','" . $cat_desc . "','1','" . $cat_perms . "','" . $group_string . "','" . $forum_string . "','" . $special . "','" . $cat_open . "','" . $cat_icon . "','" . $popup_width . "|" . $popup_height . "|" . $popup_group_cols . "|" . $popup_list_cols . "|" . $popup_group_list . "|" . $popup_per_page . "')";
				if ( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't insert new category into database.", "", __LINE__, __FILE__, $sql);
				}
				else
				{
					$message = $lang['add_success'] . "<br /><br />" . sprintf($lang['Click_return_cat_add'], "<a href=\"" . append_sid("admin_smilies.$phpEx?cat_add") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx") . "\">", "</a>");
				}
				}
			}
		else if( $order == 'after' )
		{
			$ordernum = ( isset($HTTP_POST_VARS['ordernum']) ) ? $HTTP_POST_VARS['ordernum'] : '';
			list($cat_id, $cat_order) = explode("|", $ordernum);

			// Update the category order.
			$sql = "UPDATE " . SMILIES_CAT_TABLE . "
				SET cat_order = cat_order + 1
				WHERE cat_order > '" . $cat_order . "'";
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't update category database.", "", __LINE__, __FILE__, $sql);
			}
			else
			{
				// Update the smilies category order.
				$sql = "UPDATE " . SMILIES_TABLE . "
					SET cat_id = cat_id + 1
					WHERE cat_id > '" . $cat_order . "'";
				if ( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't update smiley database.", "", __LINE__, __FILE__, $sql);
				}

				$order = $cat_order+1;

				$sql = "INSERT INTO " . SMILIES_CAT_TABLE . " (cat_name, description, cat_order, cat_perms, cat_group, cat_forum, cat_special, cat_open,  cat_icon_url, smilies_popup)
					VALUES ('" . $cat_name . "','" . $cat_desc . "','" . $order . "','" . $cat_perms . "','" . $group_string . "','" . $forum_string . "','" . $special . "','" . $cat_open . "','" . $cat_icon . "','" . $popup_width . "|" . $popup_height . "|" . $popup_group_cols . "|" . $popup_list_cols . "|" . $popup_group_list . "|" . $popup_per_page . "')";
				if( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't insert new category into database.", "", __LINE__, __FILE__, $sql);
				}
				else
				{
					$message = $lang['add_success'] . "<br /><br />" . sprintf($lang['Click_return_cat_add'], "<a href=\"" . append_sid("admin_smilies.$phpEx?cat_add") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx") . "\">", "</a>");
				}
			}
		}
				}
	else
	{
		$message = $lang['add_fail'] . "<br /><br />" . sprintf($lang['Click_return_cat_add'], "<a href=\"javascript:history.go(-1)\">", "</a>");
			}

	message_die(GENERAL_MESSAGE, $message);
}
else if( isset($HTTP_POST_VARS['cat_edit']) || isset($HTTP_GET_VARS['cat_edit']) )
{
	//
	// Display the "Edit a Category" page.
	//
	$select_cat = ( isset($HTTP_POST_VARS['selectcat']) ) ? intval($HTTP_POST_VARS['selectcat']) : intval($HTTP_GET_VARS['selectcat']);

	if( $select_cat )
	{
		// Get forum details for dropdown menu
		$sql = "SELECT c.cat_id, c.cat_title, f.forum_id, f.forum_name
			FROM " . CATEGORIES_TABLE . " c, " . FORUMS_TABLE . " f
			WHERE f.cat_id = c.cat_id
			ORDER BY c.cat_order, f.forum_order";
		if( !($result = $db->sql_query($sql)) )
			{
			message_die(GENERAL_ERROR, 'Could not obtain forums information', '', __LINE__, __FILE__, $sql);
			}

		$forum_ids = array();

		while( $row = $db->sql_fetchrow($result) )
		{
			$forum_ids[] = $row;
		}
		$db->sql_freeresult($result);

		// Create forum dropdown menu
		$forums_array = explode(" ", $array_cat_data[$select_cat-1]['cat_forum']);
		
		if( $total_forums = count($forum_ids) )
		{
			$total_cats = 0;
			$forum_list = '';
			for( $i=0; $i<$total_forums; $i++ )
			{
				$id = ( $id != 0 ) ? $id : 0;
				if( $forum_ids[$i]['cat_id'] != $id )
		{
					$forum_list .= '<option value="-1" style="background-color: #CCFFCC">' . $forum_ids[$i]['cat_title'] . '</option>';
					$id = $forum_ids[$i]['cat_id'];
					$total_cats++;
		}

				$match = 0;
				for( $j=0; $j<count($forums_array); $j++ )
				{
					if( $forums_array[$j] == $forum_ids[$i]['forum_id']  )
		{
						$match++;
		}
				}
				$selected = ( $match ) ? ' selected="selected"' : '';

				$forum_list .=  '<option value="' . $forum_ids[$i]['forum_id'] . '" style="background-color: #FFFFCC"' . $selected . '>' . $forum_ids[$i]['forum_name'] . '</option>';
			}
		}

		$forum_list .= '<option value="-1" style="background-color: #CCFFCC">&nbsp;</option>';
		for( $i = 0; $i < sizeof($forums_array); $i++ )
			{
			$selected = ( $forums_array[$i] == '999' ) ? ' selected="selected"' : '';
		}
		$forum_list .= '<option value="999" style="background-color: #FFFFCC"' . $selected . '>' . $lang['Private_Messages'] . '</option>';

		// Create view permissions dropdown list.
		$view_permissions = '';
		while( list($key, $val) = each($lang['perms']) )
				{
			$selected = ( $key == $array_cat_data[$select_cat-1]['cat_perms'] ) ? ' selected="selected"' : '';
			$view_permissions .= '<option value="' . $key . '"' . $selected . '>' . $val . '</option>';
		}
		// Create dropdown smiley filename list.
		read_smiles_directory($board_config['smilie_icon_path']);

		// Create dropdown category icon list.
		$category_icon = '<option value="blank_icon.gif">' . $lang['select_cat_icon'] . '</option>';
		for( $i=0; $i<count($smiley_images); $i++ )
					{
			$selected = ( $smiley_images[$i] == $array_cat_data[$select_cat-1]['cat_icon_url'] ) ? ' selected="selected"' : '';
			$category_icon .= '<option value="' . $smiley_images[$i] . '"' . $selected . '>' . $smiley_images[$i] . '</option>';
		}

		// Create category dropdown lists.
		$category_list = $category_order = '';
		for( $i=1; $i<=$num_cats; $i++ )
						{
			$selected = ( $i == $select_cat ) ? ' selected="selected"' : '';
			$category_list .= '<option value="' . $i . '"' . $selected . '>' . $array_cat_data[$i-1]['cat_name'] . '</option>';
			
			$selected = ( $select_cat == $array_cat_data[$i-1]['cat_order'] ) ? ' selected="selected"' : '';
			$category_order .= '<option value="' . $i . '"' . $selected . '>' . $i . '</option>';
						}

		// Create special dropdown list.
		$cat_special = '';
		while( list($key, $val) = each($lang['special']) )
						{
			$selected = ( $key == $array_cat_data[$select_cat-1]['cat_special'] ) ? ' selected="selected"' : '';
			$cat_special .= '<option value="' . $key . '"' . $selected . '>' . $val . '</option>';
						}
		
		list($width, $height, $group_cols, $list_cols, $group_list, $smilies_per_page) = explode("|", $array_cat_data[$select_cat-1]['smilies_popup']);

		$template->set_filenames(array(
			"body" => "admin/smile_catedit_body.tpl")
		);
		
		$template->assign_block_vars("cat_edit", array(
			"NAME" => $array_cat_data[$select_cat-1]['cat_name'],
			"DESC" => $array_cat_data[$select_cat-1]['description'],
			"GROUP_SELECT" => ( !$group_list ) ? ' selected="selected"' : '',
			"LIST_SELECT" => ( $group_list ) ? ' selected="selected"' : '',
			"GROUP_COLS" => $group_cols,
			"LIST_COLS" => $list_cols,
			"PERPAGE" => $smilies_per_page,
			"WIDTH" => $width,
			"HEIGHT" => $height,
			"SIZE2" => $total_forums + $total_cats + 2,
			"CAT_OPEN_YES" => ( $array_cat_data[$select_cat-1]['cat_open'] ) ? ' checked="checked"' : '',
			"CAT_OPEN_NO" => ( !$array_cat_data[$select_cat-1]['cat_open'] ) ? ' checked="checked"' : '',
			"S_CAT_ID" => $array_cat_data[$select_cat-1]['cat_id'] . '|' . $array_cat_data[$select_cat-1]['cat_order'],
			"S_CAT_FORUMS" => $forum_list,
			"S_CAT_ICON" => $category_icon,
			"S_CAT_ORDER" => $category_order,
			"S_VIEW_PERMS" => $view_permissions,
			"S_CAT_SPECIAL" => $cat_special,
			"U_SMILEY_IMG" => ( $array_cat_data[$select_cat-1]['cat_icon_url'] ) ? $phpbb_root_path . $board_config['smilie_icon_path'] . '/' . $array_cat_data[$select_cat-1]['cat_icon_url'] : $phpbb_root_path . $board_config['smilie_icon_path'] . '/blank_icon.gif',
			"U_SMILEY_ACTION2" => append_sid("admin_smilies.$phpEx"),
			"U_MORE_SMILIES" => append_sid("admin_smilies.$phpEx?mode=popup_test"))
		);

		if( $board_config['smilie_usergroups'] )
		{
			// Get usergroup details for dropdown menu.
			$sql = "SELECT group_id, group_name
				FROM " . GROUPS_TABLE . "
				WHERE group_single_user <> " . TRUE . "
				ORDER BY group_name";
			if ( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Error getting group information', '', __LINE__, __FILE__, $sql);
					}

			$usergroup_ids = array();

			while( $row = $db->sql_fetchrow($result) )
					{
				$usergroup_ids[] = $row;
					}
			$db->sql_freeresult($result);

			$groups_array = explode(" ", $array_cat_data[$select_cat-1]['cat_group']);

			if( $total_usergroups = count($usergroup_ids) )
			{
				$usergroups = '';
				for( $i=0; $i<$total_usergroups; $i++ )
				{
					$bg_colour = ( !($i % 2) ) ? '#CCFFCC' : '#FFFFCC';

					$match = 0;
					for( $j=0; $j<count($groups_array); $j++ )
					{
						if( $groups_array[$j] == $usergroup_ids[$i]['group_id']  )
						{
							$match++;
						}
					}
					$selected = ( $match ) ? ' selected="selected"' : '';

					$usergroups .=  '<option value="' . $usergroup_ids[$i]['group_id'] . '" style="background-color: ' . $bg_colour . ';"' . $selected . '>' . $usergroup_ids[$i]['group_name'] . '</option>';
				}
			}

			$template->assign_block_vars("cat_edit.usergroups", array(
				"SIZE1" => $total_usergroups,
				"S_USERGROUPS" => $usergroups)
			);
		}

		$template->assign_block_vars('switch_items_req', array());

		$template->assign_vars(array(
			"L_EDIT" => $lang['Edit_Category'],
			"L_EDIT_DESC" => $lang['edit_desc'] . $lang['edit_desc2'],
			"L_SELECT_CAT" => $lang['select_cat'],
			'L_ITEMS_REQUIRED' => $lang['Items_required'],
			"L_CAT_NAME_EXPLAIN" => $lang['add_desc_explain'],
			"L_CAT_DESC_EXPLAIN" => $lang['add_desc_explain'],
			"L_GO" => $lang['Go'],
			"L_CAT_NAME" => $lang['cat_name'],
			"L_CAT_DESC" => $lang['cat_description'],
			"L_CAT_ICON" => $lang['cat_icon'],
			"L_VIEWABLE_BY" => $lang['viewable_by'],
			"L_VIEWABLE_BY_EXPLAIN" => $lang['viewable_by_explain'],
			"L_USERGROUPS" => $lang['usergroups'],
			"L_USERGROUPS_EXPLAIN" => $lang['usergroups_explain'],
			"L_FORUMS" => $lang['forums'],
			"L_FORUMS_EXPLAIN" => $lang['forums_explain'],
			"L_PERMS0" => $lang['perms']['50'],
			"L_PERMS1" => $lang['perms']['10'],
			"L_PERMS2" => $lang['perms']['20'],
			"L_PERMS3" => $lang['perms']['30'],
			"L_PERMS4" => $lang['perms']['40'],
			"L_ORDER" => $lang['order_position'],
			"L_ORDER_CHANGE" => $lang['order_change'],
			"L_CAT_SPECIAL" => $lang['cat_special'],
			"L_CAT_SPECIAL_EXPLAIN" => $lang['cat_special_explain'],
			"L_CAT_OPEN" => $lang['cat_open'],
			"L_CAT_OPEN_EXPLAIN" => $lang['cat_open_explain'],
			"L_POPUP_WINDOW" => $lang['popup_title'],
			"L_POPUP_DESCRIPTION" => $lang['popup_description'],
			"L_POPUP_GROUP_LIST" => $lang['popup_group_list'],
			"L_POPUP_GROUP" => $lang['popup_group'],
			"L_POPUP_LIST" => $lang['popup_list'],
			"L_POPUP_GROUP_COLS" => $lang['popup_group_cols'],
			"L_POPUP_LIST_COLS" => $lang['popup_list_cols'],
			"L_PER_PAGE" => $lang['smilies_per_page'],
			"L_PER_PAGE_LIMIT" => $lang['smilies_no_limit'],
			"L_POPUP_X" => $lang['popup_x'],
			"L_POPUP_SIZE" => $lang['popup_size'],
			"L_POPUP_SIZE_ATTRIBS" => $lang['popup_size_attribs'],
			"L_POPUP_COLS" => $lang['popup_columns'],
			"L_POPUP_TEST" => $lang['popup_size_test'],
			"L_POPUP_TEST2" => $lang['popup_size_test2'],
			"L_POPUP_ALERT" => $lang['popup_alert'],
			"L_DELETE_CAT" => $lang['edit_delete'],
			"L_DELETE" => $lang['edit_delete_explain'],
		
			"S_CAT_LIST" => $category_list,
			"U_SMILEY_ACTION1" => append_sid("admin_smilies.$phpEx"),
			"U_SMILEY_BASEDIR" => $phpbb_root_path . $board_config['smilie_icon_path'])
		);
		}
		else
		{
		$category_list = '';
		for( $i=1; $i<=$num_cats; $i++ )
		{
			$category_list .= '<option value="' . $i . '">' . $array_cat_data[$i-1]['cat_name'] . '</option>';
		}
		
		$template->set_filenames(array(
			"body" => "admin/smile_catedit_body.tpl")
		);
		
		$template->assign_vars(array(
			"L_EDIT" => $lang['Edit_Category'],
			"L_EDIT_DESC" => $lang['edit_desc'],
			"L_SELECT_CAT" => $lang['select_cat'],
			"L_GO" => $lang['Go'],

			"S_CAT_LIST" => $category_list,
			"U_SMILEY_ACTION" => append_sid("admin_smilies.$phpEx"))
		);		
	}
}
else if( isset($HTTP_POST_VARS['cat_edit_submit']) || isset($HTTP_GET_VARS['cat_edit_submit']) )
{
	//
	// "Edit a Category" data has been submitted.
	//

		//
	// Get the submitted data being careful to ensure the the data
	// we recieve and process is only the data we are looking for.
		//
	$confirm = isset($HTTP_POST_VARS['confirm']);
	$catid = ( isset($HTTP_POST_VARS['cat_id']) ) ? $HTTP_POST_VARS['cat_id'] : ''; // 0|0 Array.
	$cat_name = ( isset($HTTP_POST_VARS['cat_name']) ) ? trim($HTTP_POST_VARS['cat_name']) : '';
	$cat_desc = ( isset($HTTP_POST_VARS['cat_desc']) ) ? trim($HTTP_POST_VARS['cat_desc']) : '';
	$cat_icon = ( isset($HTTP_POST_VARS['cat_icon']) ) ? trim($HTTP_POST_VARS['cat_icon']) : '';
	$cat_perms = ( isset($HTTP_POST_VARS['cat_view_perms']) ) ? intval($HTTP_POST_VARS['cat_view_perms']) : '';
	$cat_group = ( isset($HTTP_POST_VARS['cat_groups']) ) ? $HTTP_POST_VARS['cat_groups'] : ''; // Array.
	$cat_forum = ( isset($HTTP_POST_VARS['cat_forums']) ) ? $HTTP_POST_VARS['cat_forums'] : ''; // Array.
	$ordernum = ( isset($HTTP_POST_VARS['ordernum']) ) ? intval($HTTP_POST_VARS['ordernum']) : '';
	$special = ( isset($HTTP_POST_VARS['special']) ) ? intval($HTTP_POST_VARS['special']) : '';
	$cat_open = ( isset($HTTP_POST_VARS['cat_open']) ) ? intval($HTTP_POST_VARS['cat_open']) : '';
	$popup_group_list = ( isset($HTTP_POST_VARS['popup_group_list']) ) ? intval($HTTP_POST_VARS['popup_group_list']) : '';
	$popup_group_cols = ( isset($HTTP_POST_VARS['popup_group_cols']) ) ? intval($HTTP_POST_VARS['popup_group_cols']) : '';
	$popup_list_cols = ( isset($HTTP_POST_VARS['popup_list_cols']) ) ? intval($HTTP_POST_VARS['popup_list_cols']) : '';
	$popup_per_page = ( isset($HTTP_POST_VARS['popup_per_page']) ) ? intval($HTTP_POST_VARS['popup_per_page']) : '';
	$popup_width = ( isset($HTTP_POST_VARS['popup_width']) ) ? intval($HTTP_POST_VARS['popup_width']) : '';
	$popup_height = ( isset($HTTP_POST_VARS['popup_height']) ) ? intval($HTTP_POST_VARS['popup_height']) : '';
	$delete = ( isset($HTTP_POST_VARS['delete']) ) ? intval($HTTP_POST_VARS['delete']) : '';

	list($cat_id, $cat_order) = explode("|", $catid);
	
	// Category has been given its marching orders?
	// If it still contains smilies then they can kiss their asses goodbye. :)
	if( $delete )
	{
		if( $confirm )
		{
			if( $cat_id )
			{
				// Delete category.
				$sql = "DELETE FROM " . SMILIES_CAT_TABLE . "
					WHERE cat_id = '" . $cat_id . "'";
				if( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't delete category from database.", "", __LINE__, __FILE__, $sql);
				}
				else
				{
					// Update the category order field.
					$sql = "UPDATE " . SMILIES_CAT_TABLE . "
						SET cat_order = cat_order - 1
						WHERE cat_order > '" . $cat_order . "'";
					if( !$result = $db->sql_query($sql) )
					{
						message_die(GENERAL_ERROR, "Couldn't update category database.", "", __LINE__, __FILE__, $sql);
			}

					// Delete smilies.
					$sql = "DELETE FROM " . SMILIES_TABLE . "
						WHERE cat_id = '" . $cat_order . "'";
					if( !$result = $db->sql_query($sql) )
					{
						message_die(GENERAL_ERROR, "Couldn't delete smilies from database.", "", __LINE__, __FILE__, $sql);
					}
					else
					{
						// Update the smilies category order field.
						$sql = "UPDATE " . SMILIES_TABLE . "
							SET cat_id = cat_id - 1
							WHERE cat_id > '" . $cat_order . "'";
						if( !$result = $db->sql_query($sql) )
						{
							message_die(GENERAL_ERROR, "Couldn't update smiley database.", "", __LINE__, __FILE__, $sql);
						}
						else
						{
							$message = $lang['cat_del_success'] . "<br /><br />" . sprintf($lang['Click_return_cat_edit'], "<a href=\"" . append_sid("admin_smilies.$phpEx?cat_edit") . "\">", "</a>") . "<br /><br />"  . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx") . "\">", "</a>");
						}
					}
				}
			}
			else
			{
				$message = $lang['cat_del_fail'] . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx") . "\">", "</a>");
			}

			message_die(GENERAL_MESSAGE, $message);
		}
		else
		{
			// Present the confirmation screen to the user
			$template->set_filenames(array(
				"body" => "admin/confirm_body.tpl")
			);

			$hidden_fields = '<input type="hidden" name="cat_edit_submit" /><input type="hidden" name="cat_id" value="' . $catid . '" /><input type="hidden" name="cat_name" value="' . $cat_name . '" /><input type="hidden" name="cat_desc" value="' . $cat_desc . '" /><input type="hidden" name="cat_icon" value="' . $cat_icon . '" /><input type="hidden" name="cat_view_perms" value="' . $cat_perms . '" /><input type="hidden" name="cat_forum" value="' . $cat_forum . '" /><input type="hidden" name="ordernum" value="' . $ordernum . '" /><input type="hidden" name="special" value="' . $special . '" /><input type="hidden" name="popup_per_page" value="' . $popup_per_page . '" /><input type="hidden" name="popup_group_list" value="' . $popup_group_list . '" /><input type="hidden" name="popup_group_cols" value="' . $popup_group_cols . '" /><input type="hidden" name="popup_list_cols" value="' . $popup_list_cols . '" /><input type="hidden" name="popup_width" value="' . $popup_width . '" /><input type="hidden" name="popup_height" value="' . $popup_height . '" /><input type="hidden" name="delete" value="' . $delete . '" />';

			$template->assign_vars(array(
				"MESSAGE_TITLE" => $lang['Confirm'],
				"MESSAGE_TEXT" => $lang['Confirm_del_cat'],

				"L_YES" => $lang['Yes'],
				"L_NO" => $lang['No'],

				"S_CONFIRM_ACTION" => append_sid("admin_smilies.$phpEx"),
				"S_HIDDEN_FIELDS" => $hidden_fields)
			);
		}
	}
	else
	{
		// Check to make sure that both boxes were filled, if not then complain. Also check for category ID as you can't edit without it.
		if( ($cat_id != '') || ($cat_name != '') || ($cat_desc != '') )
		{
			$cat_icon = ( $cat_icon == 'blank_icon.gif' ) ? '' : $cat_icon;

			// Check viewable by permissions is correct.
			$cat_perms = ( $cat_perms == '-1' ) ? 0 : $cat_perms;

			// Get the selected usergroups.
			$group_string = '';
			for( $i=0; $i<count($cat_group); $i++ )
			{
				if( $cat_group[$i] != '-1' )
				{
					$group_string .= $cat_group[$i] . ' ';
				}
			}
			$group_string = trim($group_string);

			// Get the selected forums.
			$forum_string = '';
			for( $i=0; $i<count($cat_forum); $i++ )
			{
				if( $cat_forum[$i] != '-1' )
				{
					$forum_string .= $cat_forum[$i] . ' ';
				}
			}
			$forum_string = trim($forum_string);

			// Quotes aren't good, so remove them.
			$cat_name = str_replace("'", "", $cat_name);
			$cat_name = str_replace("\"", "", $cat_name);
			$cat_desc = str_replace("'", "", $cat_desc);
			$cat_desc = str_replace("\"", "", $cat_desc);

			// If the category hasn't moved.
			if( $ordernum == $cat_order )
			{
				// Update the category's new details.
				$sql = "UPDATE " . SMILIES_CAT_TABLE . "
					SET cat_name = '" . $cat_name . "', description = '" . $cat_desc . "', cat_perms = '" . $cat_perms . "', cat_group = '" . $group_string . "', cat_forum = '" . $forum_string . "', cat_special = '" . $special . "', cat_open = '" . $cat_open . "', cat_icon_url = '" . $cat_icon . "', smilies_popup = '" . $popup_width . "|" . $popup_height . "|" . $popup_group_cols . "|" . $popup_list_cols . "|" . $popup_group_list . "|" . $popup_per_page . "'
					WHERE cat_id = '" . $cat_id . "'";
				if( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't update the category after it was edited.", "", __LINE__, __FILE__, $sql);
				}
				else
				{
					$message = $lang['edit_success'] . "<br /><br />" . sprintf($lang['Click_return_cat_edit'], "<a href=\"" . append_sid("admin_smilies.$phpEx?cat_edit") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx") . "\">", "</a>");
				}
			}

			// If the category been moved Up.
			if( $ordernum > $cat_order )
			{
				// First we update ALL the order values.
				$sql = "UPDATE " . SMILIES_CAT_TABLE . "
					SET cat_order = cat_order - 1
					WHERE cat_order >= '" . $cat_order . "'
						AND cat_order <= '" . $ordernum . "'";
				if( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't update category database.", "", __LINE__, __FILE__, $sql);
				}
				else
				{
					// Secondly we update only the affected category order value.
					$sql = "UPDATE " . SMILIES_CAT_TABLE . "
						SET cat_name = '" . $cat_name . "', description = '" . $cat_desc . "', cat_order = '" . $ordernum . "', cat_perms = '" . $cat_perms . "', cat_group = '" . $group_string . "', cat_forum = '" . $forum_string . "', cat_special = '" . $special . "', cat_open = '" . $cat_open . "', cat_icon_url = '" . $cat_icon . "', smilies_popup = '" . $popup_width . "|" . $popup_height . "|" . $popup_group_cols . "|" . $popup_list_cols . "|" . $popup_group_list . "|" . $popup_per_page . "'
						WHERE cat_id = '" . $cat_id . "'";
					if( !$result = $db->sql_query($sql) )
					{
						message_die(GENERAL_ERROR, "Couldn't update category database.", "", __LINE__, __FILE__, $sql);
					}

					// Third we update only the smilies in the affected category.
					$sql = "UPDATE " . SMILIES_TABLE . "
						SET cat_id = -1
						WHERE cat_id = '" . $cat_order . "'";
					if( !$result = $db->sql_query($sql) )
					{
						message_die(GENERAL_ERROR, "Couldn't update smiley database.", "", __LINE__, __FILE__, $sql);
					}
					else
					{
						// Fourth we update all the smilies.
						$sql = "UPDATE " . SMILIES_TABLE . "
							SET cat_id = cat_id - 1
							WHERE cat_id >= '" . $cat_order . "'
								AND cat_id <= '" . $ordernum . "'";
						if( !$result = $db->sql_query($sql) )
						{
							message_die(GENERAL_ERROR, "Couldn't update smiley database.", "", __LINE__, __FILE__, $sql);
						}
						else
						{
							// Fifth we update only the smilies in the affected category.
							$sql = "UPDATE " . SMILIES_TABLE . "
								SET cat_id = '" . $ordernum . "'
								WHERE cat_id = -1";
							if( !$result = $db->sql_query($sql) )
							{
								message_die(GENERAL_ERROR, "Couldn't update smiley database.", "", __LINE__, __FILE__, $sql);
							}
							else
							{
								$message = $lang['edit_success_down'] . "<br /><br />" . sprintf($lang['Click_return_cat_edit'], "<a href=\"" . append_sid("admin_smilies.$phpEx?cat_edit") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("admin_smilies.$phpEx") . "\">", "</a>");
							}
						}
					}
				}
			}

			// If the category been moved Down.
			if( $ordernum < $cat_order )
			{
				// First we update ALL the order values.
				$sql = "UPDATE " . SMILIES_CAT_TABLE . "
					SET cat_order = cat_order + 1
					WHERE cat_order >= '" . $ordernum . "'
						AND cat_order <= '" . $cat_order . "'";
				if( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't update category database.", "", __LINE__, __FILE__, $sql);
				}
				else
				{
					// Secondly we update only the affected category order value.
					$sql = "UPDATE " . SMILIES_CAT_TABLE . "
						SET cat_name = '" . $cat_name . "', description = '" . $cat_desc . "', cat_order = '" . $ordernum . "', cat_perms = '" . $cat_perms . "', cat_group = '" . $group_string . "', cat_forum = '" . $forum_string . "', cat_special = '" . $special . "', cat_open = '" . $cat_open . "', cat_icon_url = '" . $cat_icon . "', smilies_popup = '" . $popup_width . "|" . $popup_height . "|" . $popup_group_cols . "|" . $popup_list_cols . "|" . $popup_group_list . "|" . $popup_per_page . "'
						WHERE cat_id = '" . $cat_id . "'";
					if( !$result = $db->sql_query($sql) )
					{
						message_die(GENERAL_ERROR, "Couldn't update category database.", "", __LINE__, __FILE__, $sql);
					}

					// Third we update only the smilies in the affected category.
					$sql = "UPDATE " . SMILIES_TABLE . "
						SET cat_id = -1
						WHERE cat_id = '" . $cat_order . "'";
					if( !$result = $db->sql_query($sql) )
					{
						message_die(GENERAL_ERROR, "Couldn't update smiley database.", "", __LINE__, __FILE__, $sql);
					}
					else
					{
						// Fourth we update all the smilies.
						$sql = "UPDATE " . SMILIES_TABLE . "
							SET cat_id = cat_id + 1
							WHERE cat_id >= '" . $ordernum . "'
								AND cat_id <= '" . $cat_order . "'";
						if( !$result = $db->sql_query($sql) )
						{
							message_die(GENERAL_ERROR, "Couldn't update smiley database.", "", __LINE__, __FILE__, $sql);
						}
						else
						{
							// Fifth we update only the smilies in the affected category.
							$sql = "UPDATE " . SMILIES_TABLE . "
								SET cat_id = '" . $ordernum . "'
								WHERE cat_id = -1";
							if( !$result = $db->sql_query($sql) )
							{
								message_die(GENERAL_ERROR, "Couldn't update smiley database.", "", __LINE__, __FILE__, $sql);
							}
							else
							{
								$message = $lang['edit_success_up'] . "<br /><br />" . sprintf($lang['Click_return_cat_edit'], "<a href=\"" . append_sid("admin_smilies.$phpEx?cat_edit") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx") . "\">", "</a>");
							}
						}
					}
				}
			}
		}
		else
		{
			$message = $lang['edit_fail'] . "<br /><br />" . sprintf($lang['Click_return_cat_edit'], "<a href=\"" . append_sid("admin_smilies.$phpEx?cat_edit") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx") . "\">", "</a>");
		}

		message_die(GENERAL_MESSAGE, $message);
		}
	}
else if( isset($HTTP_POST_VARS['cat_view']) || isset($HTTP_GET_VARS['cat_view']) )
	{
	//
	// Admin has selected to view one or more categories. Category Viewing Page.
	//

	//
	// Get the submitted data being careful to ensure the the data
	// we recieve and process is only the data we are looking for.
	//
	$multi_cat1 = ( isset($HTTP_POST_VARS['multi_cats']) ) ? $HTTP_POST_VARS['multi_cats'] : ''; // Array.
	$multi_cat2 = ( isset($HTTP_GET_VARS['multi_cats']) ) ? $HTTP_GET_VARS['multi_cats'] : ''; // 0|0 Array.

	if( $multi_cat1 )
		{	
		$count = count($multi_cat1);
		$cat = $multi_cat1;
		$cats_viewed = $count + $count;
		for( $i=0; $i<$count; $i++ )
		{
			list($id, $order) = explode("|", $cat[$i]);

			$cats_viewed .= '|' . $id . '|' . $order;
		}
	}
	else if( $multi_cat2 )
	{
		$cat_count = explode("|", $multi_cat2);
		$cat = array();
		for( $i=1; $i<=$cat_count[0]; $i++ )
		{
			if( !($i % 2) )
			{
			}
			else
			{
				$cat[] = $cat_count[$i] . '|' . $cat_count[$i+1];
			}
		}
		$count = count($cat);
		$cats_viewed = $multi_cat2;
	}
	
	if( $cats_viewed )
	{

		$template->set_filenames(array(
			"body" => "admin/smile_catview_body.tpl")
		);
			
		$template->assign_block_vars("cat_view", array(
			"MULTI_CATS" => $cats_viewed)
		);
		
		for( $i=0; $i<$count; $i++ )
		{
			// Get list of categories to display from array.
			list($cat_id, $cat_order) = explode("|", $cat[$i]);

			// Get all smiley data for the category.
			$sql = "SELECT smilies_id, code, smile_url, emoticon
			FROM " . SMILIES_TABLE ."
				WHERE cat_id = '" . $cat_order . "'
				ORDER BY smilies_order ASC";
			if ( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't obtain smilies from database", "", __LINE__, __FILE__, $sql);
			}

			$smile_row = $db->sql_fetchrowset($result);
			$smile_count = $db->sql_numrows($result);

			if( $smile_count )
			{
				$smiley_list = '';
				$rowset = array();
				$unique_smilies = 0;

				for( $j=0; $j<$smile_count; $j++ )
				{
					$smiley_list .= '<img src="' . $phpbb_root_path . $board_config['smilies_path'] . '/' . $smile_row[$j]['smile_url'] . '" title="' . $lang['click_edit'] . '" alt="' . $lang['click_edit'] . '" onmouseover="this.style.cursor=\'hand\';" onClick="editsmiley(\'' . append_sid("admin_smilies.$phpEx?smiley_add&amp;id=" . $smile_row[$j]['smilies_id']) . '\')" /> ';

					if( empty($rowset[$smile_row[$j]['smile_url']]) )
					{
						$rowset[$smile_row[$j]['smile_url']]['emoticon'] = $smile_row[$j]['emoticon'];
						$unique_smilies++;
					}
				}
				unset($rowset);
			}
			else
			{
				$unique_smilies = 0;
				$smiley_list = $lang['cat_view_empty'];
			}

			$template->assign_vars(array(
				"L_PAGE_TITLE" => $lang['Manage_smilies'],
				"L_PAGE_DESCRIPTION" => $lang['cat_view_description1'],
				"L_CATEGORY_NAME" => $lang['Category'],
				"L_CATEGORY_DESC" => $lang['Category'] . ' ' . $lang['Forum_desc'],
				"L_SMILEY_COUNT" => $lang['Smilies'],
				"L_CATEGORY_OPTIONS" => $lang['Options'],
				"L_MASS_EDIT" => $lang['mass_edit'])
			);

			$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
		
			$template->assign_block_vars("cat_view.smile_categories", array(
				"ROW_CLASS" => $row_class,

				"CATEGORY_NUM" => $cat_order,
				"CATEGORY_NAME" => $array_cat_data[$cat_order-1]['cat_name'],
				"CATEGORY_DESC" => $array_cat_data[$cat_order-1]['description'],
				"SMILEY_COUNT" => sprintf($lang['smiley_count'], $smile_count, $unique_smilies),

				"S_SMILEY_LIST" => $smiley_list,
				"S_CATEGORY_EDIT" => append_sid("admin_smilies.$phpEx?mass_edit_view&amp;orig_cat=" . $cat_id . "|" . $cat_order))
			);
		}
	}
	else
	{
		// Create category dropdown menu.
		$category_view = '';
		for( $i=0; $i<$num_cats; $i++ )
		{
			$category_view .= '<option value="' . $array_cat_data[$i]['cat_id'] . '|' . $array_cat_data[$i]['cat_order'] . '">&nbsp;' . $array_cat_data[$i]['cat_name'] . '&nbsp;</option>';
		}

		$template->set_filenames(array(
			"body" => "admin/smile_catview_body.tpl")
		);

		$template->assign_vars(array(
			"L_CAT_VIEW_TITLE" => $lang['Manage_smilies'],
			"L_CAT_VIEW_DESC" => $lang['cat_view_description2'])
		);

		$template->assign_block_vars("cat_select", array(
			"S_CAT_VIEW" => $category_view,
			"S_SMILEY_ACTION" => append_sid("admin_smilies.$phpEx"))
		);
	}
	}
else if( isset($HTTP_POST_VARS['mass_edit_view']) || isset($HTTP_GET_VARS['mass_edit_view']) )
	{
	//
	// Admin has selected to mass edit a category.
	//

	//
	// Get the submitted data being careful to ensure the the data
	// we recieve and process is only the data we are looking for.
	//
	$cat = ( isset($HTTP_GET_VARS['orig_cat']) ) ? $HTTP_GET_VARS['orig_cat'] : ''; // 0|0 Array.
	$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : '';

	list($cat_id, $cat_order) = explode("|", $cat);

	// Get all the smilies for the selected category.
	$sql = "SELECT * 
		FROM " . SMILIES_TABLE . "
		WHERE cat_id = '" . $cat_order . "'
		ORDER BY smilies_order
			ASC";
	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, "Couldn't select smilies from database.", "", __LINE__, __FILE__, $sql);
	}

	$smile_row = $db->sql_fetchrowset($result);
	$smile_count = $db->sql_numrows($result);

	// If the category contains no smilies then output this message.
	$cat_empty = ( !$smile_count ) ? '<tr><td colspan="6" class="row2"><span class="gen">' . $lang['cat_view_empty'] . '</span></td></tr>' : '';

	// Code for pagination.
	if( $array_cat_data[$cat_order-1]['smilies_per_page'] == 0 )
	{
		$per_page = $smiley_stop = $per_page_total = $smile_count;
		$smiley_start = 0;
	}
	else
	{
		$per_page = ( $array_cat_data[$cat_order-1]['smilies_per_page'] > $smile_count ) ? $smile_count : $array_cat_data[$cat_order-1]['smilies_per_page'];
		$page_num = ( $start <= 0 ) ? 1 : ($start / $per_page) + 1;
		$smiley_start = ($per_page * $page_num) - $per_page;
		$smiley_stop = ( ($per_page * $page_num) > $smile_count ) ? $smile_count : $smiley_start + $per_page;
		$per_page_total = $smiley_stop - $smiley_start;
	}

	if( $smile_count )
	{
		$pagination = generate_pagination("admin_smilies.$phpEx?mass_edit_view&amp;orig_cat=$cat_id|$cat_order", $smile_count, $per_page, $start, FALSE);

		$rowset = array();
		$unique_smilies = 0;
		for( $i=0; $i<$smile_count; $i++ )
		{
			if( empty($rowset[$smile_row[$i]['smile_url']]) )
			{
				$rowset[$smile_row[$i]['smile_url']]['emoticon'] = $smile_row[$i]['emoticon'];
				$unique_smilies++;
			}
		}
		unset($rowset);
	}
	else
		{
		$unique_smilies = 0;
		}

	$s_hidden_fields = '<input type="hidden" name="orig_cat" value="' . $cat_id . '|' . $cat_order . '" /><input type="hidden" name="start" value="' . $start . '" /><input type="hidden" name="total" value="' . $per_page_total . '" />';

	$template->set_filenames(array(
		"body" => "admin/smile_massedit_body.tpl")
	);

		$template->assign_vars(array(
		"L_PAGE_TITLE" => $lang['Edit'] . ' ' . $lang['Smilies'],
		"L_PAGE_DESCRIPTION" => $lang['mass_edit_description'],
		"L_CATEGORY" => $lang['Category'],
		"L_CATEGORY_DESC" => $lang['cat_description'],
		"L_CATEGORY_OPTIONS" => $lang['smiley_cat_options'],
		"L_SMILEY_COUNT" => $lang['Smilies'],

		"L_CODE" => $lang['Code'],
		"L_SMILE" => $lang['Smiley'],
		"L_EMOT" => $lang['col_emotion'],
		"L_MOVE" => $lang['Move'],
		"L_ORDER" => $lang['Order'],
		"L_DELETE" => $lang['Delete'],

		"L_TICK_ALL" => $lang['Mark_all'],
		"L_UNTICK_ALL" => $lang['Unmark_all'],
		"L_IMPORT_EXPORT" => $lang['import_export'],
		"L_SMILEY_ADD" => $lang['smile_add'],
		"L_MASS_EDIT_SUBMIT" => $lang['mass_edit_submit'],

		"SMILEY_COUNT" => sprintf($lang['smiley_count'], $smile_count, $unique_smilies),
		"S_CAT_EMPTY" => $cat_empty,
		"S_CAT_NAME" => $array_cat_data[$cat_order-1]['cat_name'],
		"S_CAT_DESCRIPTION" => $array_cat_data[$cat_order-1]['description'],
		"S_HIDDEN_FIELDS" => $s_hidden_fields,
		"S_SMILEY_TOTAL" => $smile_count,
			"S_SMILEY_ACTION" => append_sid("admin_smilies.$phpEx"),
		"S_PAGINATION" => ( $pagination ) ? $pagination : 1)
		);

	// Loop through the rows of smilies.
	for( $i=$smiley_start; $i<$smiley_stop; $i++ )
	{
		// Setup the drop down list of categories for each smiley.
		$category_list = '';
		for( $j=0; $j<$num_cats; $j++ )
		{
			$selected1 = ( $array_cat_data[$j]['cat_order'] == $cat_order ) ? ' selected="selected"' : '';
			$category_list .= '<option value="' . $array_cat_data[$j]['cat_id'] . '|' . $array_cat_data[$j]['cat_order'] . '"' . $selected1 . '>' . $array_cat_data[$j]['cat_name'] . '</option>';
	}

		// Setup the dropdown list of order numbers for each smiley.
		$order = '';
		for( $k=1; $k<=$smile_count; $k++ )
	{
			$selected2 = ( $k == $smile_row[$i]['smilies_order'] ) ? ' selected="selected"' : '';
			$order .= '<option value="' . $smile_row[$i]['smilies_order'] . '|' . $k . '"' . $selected2 . '>' . $k . '</option>';
		}

		// Replace htmlentites for < and > with actual character.
		$smile_row[$i]['code'] = str_replace('&lt;', '<', $smile_row[$i]['code']);
		$smile_row[$i]['code'] = str_replace('&gt;', '>', $smile_row[$i]['code']);

		$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

		$template->assign_block_vars("smiles", array(
			"ROW_CLASS" => $row_class,

			"SMILEY_CODE" => $smile_row[$i]['code'],
			"SMILEY_EMOTICON" => $smile_row[$i]['emoticon'],
			"SMILEY_IMG" => $phpbb_root_path . $board_config['smilies_path'] . '/' . $smile_row[$i]['smile_url'],
			"SMILEY_URL" => $smile_row[$i]['smile_url'],
			"SMILEY_ORDER" => $order,
			"SMILEY_COUNT" => $i+1,
			"SMILEY_ID" => $smile_row[$i]['smilies_id'],

			"CATEGORY_LIST" => $category_list,
			"SMILEY_ORDER_ACTION" => append_sid("admin_smilies.$phpEx?mode=smiley_order&amp;id=" . $smile_row[$i]['smilies_id'] . "&amp;cat=" . $cat_id . "|" . $cat_order . "&amp;start=" . $start))
		);
	}
}
else if( isset($HTTP_POST_VARS['mass_edit_submit']) || isset($HTTP_GET_VARS['mass_edit_submit']) )
		{
			//
	// Admin has submitted multiple changes.
			//

	$total = ( isset($HTTP_POST_VARS['total']) ) ? intval($HTTP_POST_VARS['total']) : '';
	$orig_cat = ( isset($HTTP_POST_VARS['orig_cat']) ) ? $HTTP_POST_VARS['orig_cat'] : ''; // 0|0 Array.

	list($cat_id, $cat_order) = explode("|", $orig_cat);

	$dels = $edits = $errors = 0;

	// For pagination
	$start = ( isset($HTTP_POST_VARS['start']) ) ? intval($HTTP_POST_VARS['start']) : '0';

	if( $start )
	{
		$start1 = $start + 1;
		$total1 = $total + $start;
	}
	else
	{
		$start1 = 1;
		$total1 = $total;
	}

	for( $i=$start1; $i<=$total1; $i++ )
	{
		// I can now start to receieve the smiley ids which will allow me to get the others.
		$smiley_id = ( isset($HTTP_POST_VARS["id$i"]) ) ? $HTTP_POST_VARS["id$i"] : $HTTP_GET_VARS["id$i"];

		// Get the submitted data from the other fields now.
		$smiley_code = ( isset($HTTP_POST_VARS["code$smiley_id"]) ) ? trim($HTTP_POST_VARS["code$smiley_id"]) : '';
		$smiley_emot = ( isset($HTTP_POST_VARS["emot$smiley_id"]) ) ? trim($HTTP_POST_VARS["emot$smiley_id"]) : '';
		$smiley_cat = ( isset($HTTP_POST_VARS["cat$smiley_id"]) ) ? $HTTP_POST_VARS["cat$smiley_id"] : ''; // Array.
		$smiley_del = ( isset($HTTP_POST_VARS["del$smiley_id"]) ) ? intval($HTTP_POST_VARS["del$smiley_id"]) : '';

		// Get the order value for the smiley as it may have changed if there has been deletions.
		$sql = "SELECT smilies_order
			FROM " . SMILIES_TABLE . "
			WHERE smilies_id = " . $smiley_id;
		if( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, "Couldn't select category database.", "", __LINE__, __FILE__, $sql);
		}
		$res = $db->sql_fetchrow($result);
		$smilies_order = $res['smilies_order'];

		// Has the smiley been selected for deletion?
		if( $smiley_del == 1 )
			{
			// Delete the smiley.
				$sql = "DELETE FROM " . SMILIES_TABLE . "
					WHERE smilies_id = " . $smiley_id;
			if( !$result = $db->sql_query($sql) )
				{
				message_die(GENERAL_ERROR, "Couldn't delete smiley from the database.", "", __LINE__, __FILE__, $sql);
			}
			else
			{
				// Update the smilies order.
				$sql = "UPDATE " . SMILIES_TABLE . "
					SET smilies_order = smilies_order - 1
					WHERE cat_id = '" . $cat_order . "'
						AND smilies_order > " . $smilies_order;
				if( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't update smiley database.", "", __LINE__, __FILE__, $sql);
				}

				$dels++;
			}
		}
		else
		{
			if( $smiley_code )
			{
				// $smiley_cat contains two values, the new category's id and order values.
				list($cat_id2, $cat_order2) = explode("|", $smiley_cat);

				// Convert < and > to proper htmlentities for parsing.
				$smiley_code = str_replace('<', '&lt;', $smiley_code);
				$smiley_code = str_replace('>', '&gt;', $smiley_code);

				// Check to see if the category has changed.
				if ( $cat_id == $cat_id2 )
				{
					// Category hasn't changed. Update smiley details.
					$sql = "UPDATE " . SMILIES_TABLE . "
						SET code = '" . $smiley_code . "', emoticon = '" . $smiley_emot . "'
						WHERE smilies_id = " . $smiley_id;
					if( !$result = $db->sql_query($sql) )
					{
						message_die(GENERAL_ERROR, "Couldn't update smiley database.", "", __LINE__, __FILE__, $sql);
					}
				}
				else
				{
					// Category has changed. Get smiley count for new category.
					$sql = "SELECT *
						FROM " . SMILIES_TABLE . "
						WHERE cat_id = " . $cat_order2;
					if( !$result = $db->sql_query($sql) )
					{
						message_die(GENERAL_ERROR, "Couldn't select category database.", "", __LINE__, __FILE__, $sql);
					}
					$smile_count = $db->sql_numrows($result);

					$lastsmile = $smile_count + 1;
		
					// Move smiley to new category and update all the data.
					$sql = "UPDATE " . SMILIES_TABLE . "
						SET code = '" . $smiley_code . "', emoticon = '" . $smiley_emot . "', cat_id = '" . $cat_order2 . "', smilies_order='" . $lastsmile . "'
						WHERE smilies_id=" . $smiley_id;
					if( !$result = $db->sql_query($sql) )
					{
						message_die(GENERAL_ERROR, "Couldn't update smiley database.", "", __LINE__, __FILE__, $sql);
					}
					else
					{
						// Update the old category's smilies order.
						$sql = "UPDATE " . SMILIES_TABLE . "
							SET smilies_order = smilies_order - 1
							WHERE cat_id = '" . $cat_order . "'
								AND smilies_order > " . $smilies_order;
						if( !$result = $db->sql_query($sql) )
						{
							message_die(GENERAL_ERROR, "Couldn't update smiley database.", "", __LINE__, __FILE__, $sql);
						}
					}
				}
				$edits++;
			}
			else
			{
				$errors++;
			}
		}
	}

	$plural1 = ( $dels == 1 ) ? $lang['multi_delete1'] : $lang['multi_delete2'];
	$plural2 = ( $edits == 1 ) ? $lang['multi_updated1'] : $lang['multi_updated2'];
	$plural3 = ( $errors == 1 ) ? $lang['smiley_errors1'] : $lang['smiley_errors2'];

	$message = sprintf($plural2, $edits) . sprintf($plural1, $dels) . "<br /><br />" . sprintf($plural3, $errors, $total) . "<br /><br />" . sprintf($lang['Click_return_mass_edit'], "<a href=\"" . append_sid("admin_smilies.$phpEx?mass_edit_view&amp;orig_cat=$cat_id|$cat_order&amp;start=$start") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx") . "\">", "</a>");

	message_die(GENERAL_MESSAGE, $message);
			}
else if ( isset($HTTP_POST_VARS['smiley_add']) || isset($HTTP_GET_VARS['smiley_add']) )
{
			//
	// Admin has selected to edit/add a smiley.
			//
	$smiley_id = ( isset($HTTP_GET_VARS['id']) ) ? intval($HTTP_GET_VARS['id']) : '';

	read_smiles_directory($board_config['smilies_path']);

	// Are you editing or adding?
	if( $smiley_id )
	{
		// If editing a smiley then get its details.
		$sql = "SELECT smilies_id, code, smile_url, emoticon, cat_id, smilies_order
					FROM " . SMILIES_TABLE . "
					WHERE smilies_id = " . $smiley_id;
		if( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'Could not obtain emoticon information', "", __LINE__, __FILE__, $sql);
				}
		$smile_row = $db->sql_fetchrow($result);

		$code = $smile_row['code'];
		$smile_url = $smile_row['smile_url'];
		$emoticon = $smile_row['emoticon'];
		$category = $smile_row['cat_id'];

		$smiley_title = $lang['Edit'] . ' ' . $lang['Smiley'];
		$smiley_description = $lang['smiley_edit_desc'];

		$s_hidden_fields = ( isset($HTTP_GET_VARS['multi_cats']) ) ? '<input type="hidden" name="multi_cats" value="' . $HTTP_GET_VARS['multi_cats'] . '" />' : ''; // 0|0 Array.

		$s_hidden_fields .= '<input type="hidden" name="id_order" value="' . $array_cat_data[$category-1]['cat_id'] . '|' . $smile_row['cat_id'] . '|' . $smile_row['smilies_order'] . '" /><input type="hidden" name="smile_id" value="' . $smile_row['smilies_id'] . '" />';

		$template->assign_block_vars("delete", array());
	}
	else
	{
		$orig_cat = ( isset($HTTP_POST_VARS['orig_cat']) ) ? $HTTP_POST_VARS['orig_cat'] : ''; // 0|0 Array.

		$s_hidden_fields = ( $orig_cat ) ? '<input type="hidden" name="orig_cat" value="' . $orig_cat . '" />' : '';

		list($cat_id, $cat_order) = explode("|", $orig_cat);

		$code = $smile_url = $emoticon = '';
		$category = $cat_order;
		$smile_url = $smiley_images[0];
		$smiley_title = $lang['Add'] . ' ' . $lang['Smiley'];
		$smiley_description = $lang['smiley_add_desc'];

		$s_hidden_fields .= '<input type="hidden" name="add" value="1" />';
	}

	// Create dropdown category list.
	$category_list = '';
	for( $i=0; $i<$num_cats; $i++ )
	{
		$smiley_selected = ( $array_cat_data[$i]['cat_order'] == $category ) ? ' selected="selected"' : '';

		// value = cat_id value | cat_order value.
		$category_list .= '<option value="' . $array_cat_data[$i]['cat_id'] . '|' . $array_cat_data[$i]['cat_order'] . '"' . $smiley_selected . '>' . $array_cat_data[$i]['cat_name'] . '</option>';
	}

	// Create dropdown smiley filename list.
	$filename_list = '';
				for( $i = 0; $i < count($smiley_images); $i++ )
				{
		if ( $smiley_images[$i] == $smile_url )
					{
			$smiley_selected = ' selected="selected"';
						$smiley_edit_img = $smiley_images[$i];
					}
					else
					{
			$smiley_selected = '';
					}

					$filename_list .= '<option value="' . $smiley_images[$i] . '"' . $smiley_selected . '>' . $smiley_images[$i] . '</option>';
				}

				$template->set_filenames(array(
		"body" => "admin/smile_smiley_body.tpl")
				);

				$template->assign_vars(array(
		"L_SMILEY_TITLE" => $smiley_title,
		"L_SMILEY_DESCRIPTION" => $smiley_description,
					"L_SMILEY_CODE" => $lang['smiley_code'],
					"L_SMILEY_URL" => $lang['smiley_url'],
					"L_SMILEY_EMOTION" => $lang['smiley_emot'],
		"L_SMILEY_CATEGORY" => $lang['smiley_category'],
		"L_SMILEY_DELETE" => $lang['smiley_delete'],
		'L_ITEMS_REQUIRED' => $lang['Items_required'],

		"SMILEY_CODE" => $code,
		"SMILEY_EMOTICON" => $emoticon,
		"SMILEY_IMG" => $phpbb_root_path . $board_config['smilies_path'] . '/' . $smile_url,

		"S_CATEGORY_OPTIONS" => $category_list,
					"S_HIDDEN_FIELDS" => $s_hidden_fields,
					"S_FILENAME_OPTIONS" => $filename_list,
		"S_SMILEY_BASEDIR" => $phpbb_root_path . $board_config['smilies_path'],
		"S_SMILEY_ACTION" => append_sid("admin_smilies.$phpEx"))
				);
}
else if ( isset($HTTP_POST_VARS['smiley_add_submit']) || isset($HTTP_GET_VARS['smiley_add_submit']) )
{
			//
			// Admin has submitted changes while editing a smiley.
			//

			//
				// Get the submitted data, being careful to ensure that we only
				// accept the data we are looking for.
			//
	$confirm = isset($HTTP_POST_VARS['confirm']);
	$smile_id = ( isset($HTTP_POST_VARS['smile_id']) ) ? intval($HTTP_POST_VARS['smile_id']) : '';
			$smile_code = ( isset($HTTP_POST_VARS['smile_code']) ) ? trim($HTTP_POST_VARS['smile_code']) : '';
			$smile_url = ( isset($HTTP_POST_VARS['smile_url']) ) ? trim($HTTP_POST_VARS['smile_url']) : '';
	$smile_emoticon = ( isset($HTTP_POST_VARS['smile_emoticon']) ) ? trim($HTTP_POST_VARS['smile_emoticon']) : '';
	$smile_cat = ( isset($HTTP_POST_VARS['smile_category']) ) ? $HTTP_POST_VARS['smile_category'] : ''; // 0|0 Array.
	$smile_add = ( isset($HTTP_POST_VARS['add']) ) ? intval($HTTP_POST_VARS['add']) : intval($HTTP_GET_VARS['add']);
	$smile_delete = ( isset($HTTP_POST_VARS['delete']) ) ? intval($HTTP_POST_VARS['delete']) : '';
	$orig_id_order = ( isset($HTTP_POST_VARS['id_order']) ) ? $HTTP_POST_VARS['id_order'] : ''; // 0|0 Array
	$multi_cats = ( isset($HTTP_POST_VARS['multi_cats']) ) ? $HTTP_POST_VARS['multi_cats'] : ''; // 0|0 Array
	$orig_cat = ( isset($HTTP_POST_VARS['orig_cat']) ) ? $HTTP_POST_VARS['orig_cat'] : ''; // 0|0 Array
         
				// If no code was entered complain ...
	if( ($smile_code == '') || ($smile_url == '') ) 
	{ 
		message_die(GENERAL_MESSAGE, $lang['Fields_empty']); 
	}

	//
				// Convert < and > to proper htmlentities for parsing.
	//
				$smile_code = str_replace('<', '&lt;', $smile_code);
				$smile_code = str_replace('>', '&gt;', $smile_code);

	// Get the two values from $smile_cat_name variable.
	list($cat_id, $cat_order) = explode("|", $smile_cat);
	
	// Get smiley count for category.
	$sql = "SELECT *
		FROM " . SMILIES_TABLE . "
		WHERE cat_id = " . $cat_order;
	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, "Couldn't select category database.", "", __LINE__, __FILE__, $sql);
	}
	$smile_count = $db->sql_numrows($result);

	$lastsmiley = $smile_count + 1;

	if ( $smile_add == 1 )
	{
		// Create a new smiley.
		$sql = "INSERT INTO " . SMILIES_TABLE . " (code, smile_url, emoticon, cat_id, smilies_order) 
			VALUES('" . str_replace("\'", "''", $smile_code) . "', '" . str_replace("\'", "''", $smile_url) . "', '" . str_replace("\'", "''", $smile_emoticon) . "', '" . $cat_order . "', '" . $lastsmiley . "')";
		if( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, "Couldn't insert new smiley into database", "", __LINE__, __FILE__, $sql);
		}
		else
		{
			if( $orig_cat )
			{
				$message = $lang['smiley_add_success'] . "<br /><br />" . sprintf($lang['Click_return_mass_edit'], "<a href=\"" . append_sid("admin_smilies.$phpEx?mass_edit_view&amp;orig_cat=$orig_cat") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx") . "\">", "</a>");
			}
			else
			{
				$message = $lang['smiley_add_success'] . "<br /><br />" . sprintf($lang['Click_return_mass_edit'], "<a href=\"" . append_sid("admin_smilies.$phpEx?smiley_add") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx") . "\">", "</a>");
			}
		}
	}
	else
	{
		// Get the three values from $orig_id_order variable. Original cat data.
		list($cat_id1, $cat_order1, $smilies_order1) = explode("|", $orig_id_order);

		// If smiley is selected for deletion...
		if( $smile_delete == 1 )
		{
			if( $confirm )
			{
				// Delete smiley from the smiley table.
				$sql = "DELETE FROM " . SMILIES_TABLE . "
					WHERE smilies_id = " . $smile_id;
				if( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't delete smiley from database", "", __LINE__, __FILE__, $sql);
				}
				else
				{
					// Update the smilies order.
					$sql = "UPDATE " . SMILIES_TABLE . "
						SET smilies_order = smilies_order - 1
						WHERE cat_id = '" . $cat_order1 . "'
							AND smilies_order > " . $smilies_order1;
					if( !$result = $db->sql_query($sql) )
					{
						message_die(GENERAL_ERROR, "Couldn't update smiley in database", "", __LINE__, __FILE__, $sql);
					}

					if( $orig_cat )
					{
						$message = $lang['smiley_del_success'] . "<br /><br />" . sprintf($lang['Click_return_mass_edit'], "<a href=\"" . append_sid("admin_smilies.$phpEx?mass_edit_view&amp;orig_cat=$orig_cat") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx") . "\">", "</a>");
					}
					else
					{
						$message = $lang['smiley_del_success'] . "<br /><br />" . sprintf($lang['Click_return_cat_view'], "<a href=\"" . append_sid("admin_smilies.$phpEx?cat_view&amp;multi_cats=$multi_cats") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx") . "\">", "</a>");
					}
				}
			}
			else
			{
				// Present the confirmation screen to the user
				$template->set_filenames(array(
					"body" => "admin/confirm_body.tpl")
				);

				$hidden_fields = '<input type="hidden" name="smiley_add_submit" /><input type="hidden" name="smile_id" value="' . $smile_id . '" /><input type="hidden" name="smile_code" value="' . $smile_code . '" /><input type="hidden" name="smile_url" value="' . $smile_url . '" /><input type="hidden" name="smile_emoticon" value="' . $smile_emoticon . '" /><input type="hidden" name="smile_category" value="' . $smile_cat . '" /><input type="hidden" name="delete" value="' . $smile_delete . '" /><input type="hidden" name="id_order" value="' . $orig_id_order . '" /><input type="hidden" name="multi_cats" value="' . $multi_cats . '" />';

				$template->assign_vars(array(
					"MESSAGE_TITLE" => $lang['Confirm'],
					"MESSAGE_TEXT" => $lang['Confirm_del_smiley'],

					"L_YES" => $lang['Yes'],
					"L_NO" => $lang['No'],

					"S_CONFIRM_ACTION" => append_sid("admin_smilies.$phpEx"),
					"S_HIDDEN_FIELDS" => $hidden_fields)
				);
			}
		}
		else
		{
			if( $cat_id1 != $cat_id )
			{
				// A different category has been selected.

				// Update smiley details.
				$sql = "UPDATE " . SMILIES_TABLE . "
					SET code = '" . str_replace("\'", "''", $smile_code) . "', smile_url = '" . str_replace("\'", "''", $smile_url) . "', emoticon = '" . str_replace("\'", "''", $smile_emoticon) . "', cat_id = '" . $cat_order . "', smilies_order = '" . $lastsmiley . "'
					WHERE smilies_id = " . $smile_id;
				if( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't update smiley database.", "", __LINE__, __FILE__, $sql);
				}
				else
				{
					// Update smiley order for old category.
					$sql = "UPDATE " . SMILIES_TABLE . "
						SET smilies_order = smilies_order - 1
						WHERE cat_id = '" . $cat_order1 . "'
							AND smilies_order > " . $smilies_order1;
					if( !$result = $db->sql_query($sql) )
					{
						message_die(GENERAL_ERROR, "Couldn't update smiley database.", "", __LINE__, __FILE__, $sql);
					}
				}
			}
			else
			{
				// Still the same category.

				// Update smiley details.
				$sql = "UPDATE " . SMILIES_TABLE . "
					SET code = '" . str_replace("\'", "''", $smile_code) . "', smile_url = '" . str_replace("\'", "''", $smile_url) . "', emoticon = '" . str_replace("\'", "''", $smile_emoticon) . "'
					WHERE smilies_id = " . $smile_id;
				if( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't update smilies info", "", __LINE__, __FILE__, $sql);
				}
			}

			if( $orig_cat )
			{
				$message = $lang['smiley_edit_success'] . "<br /><br />" . sprintf($lang['Click_return_mass_edit'], "<a href=\"" . append_sid("admin_smilies.$phpEx?mass_edit_view&amp;orig_cat=$orig_cat") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx") . "\">", "</a>");
			}
			else
			{
				$message = $lang['smiley_edit_success'] . "<br /><br />" . sprintf($lang['Click_return_cat_view'], "<a href=\"" . append_sid("admin_smilies.$phpEx?cat_view&amp;multi_cats=$multi_cats") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx") . "\">", "</a>");
			}
		}
	}

	if( $smile_delete && !$confirm )
	{
	}
	else
	{
				message_die(GENERAL_MESSAGE, $message);
	}
}
else if ( isset($HTTP_POST_VARS['upload']) || isset($HTTP_GET_VARS['upload']) )
{
			//
	// Display the Upload Smilies page.
			//
	$upload_amount = ( isset($HTTP_POST_VARS['upload_amount']) ) ? intval($HTTP_POST_VARS['upload_amount']) : '1';
	$uploading = ( isset($HTTP_POST_VARS['uploading']) ) ? trim($HTTP_POST_VARS['uploading']) : '';
	$max_file_size = ( isset($HTTP_POST_FILES['MAX_FILE_SIZE']) ) ? intval($HTTP_POST_FILES['MAX_FILE_SIZE']) : $board_config['smilie_max_filesize'];

	$path = $phpbb_root_path . $board_config['smilies_path'] . '/';
	
	if( $uploading )
	{
		// I could use !empty() or isset(), but then the variable is never empty even when no file has been choosen so there's no point. :-/
		// eg: If the form is submitted and no file has been choosen.
		// Array ( [name] => Array ( [0] => ) [type] => Array ( [0] => ) [tmp_name] => Array ( [0] => ) [error] => Array ( [0] => 4 ) [size] => Array ( [0] => 0 ) )
		$userfile = $HTTP_POST_FILES['userfile'];

		// Also, I found that if the uploaded file is too large then the array is empty apart from the name and the error number.
		// eg: <input type="hidden" name="MAX_FILE_SIZE" value="10000" /> and picture.gif = 50KB returns the following.
		// Array ( [name] => Array ( [0] => picture.gif ) [type] => Array ( [0] => ) [tmp_name] => Array ( [0] => ) [error] => Array ( [0] => 2 ) [size] => Array ( [0] => 0 ) )
		//
		// The $HTTP_POST_FILES['userfile']['error'] part didn't arrive until PHP 4.2.0 so the code must work around that fact.
		
		// I could just count $HTTP_POST_FILES['userfile']['name'] but if any boxes were blank then that would cause problems.
		$file_count = ( isset($HTTP_POST_VARS['file_count']) ) ? intval($HTTP_POST_VARS['file_count']) : '1';

		$message = '<table align="center" cellspacing="2" cellpadding="2">';
		$error = FALSE;
		
		for( $i=0; $i<$file_count; $i++ )
		{
			if( @phpversion() >= '4.2.0' )
			{	
				// http://uk.php.net/manual/en/features.file-upload.errors.php
			//	$error = ( $userfile['error'][$i] == 0 ) ? FALSE : ( $userfile['error'][$i] == 1 ) ? TRUE : ( $userfile['error'][$i] == 2 ) ? TRUE : ( $userfile['error'][$i] == 3 ) ? TRUE : ( $userfile['error'][$i] == 4 ) ? TRUE : ( $userfile['error'][$i] == 6 ) ? TRUE : ( $userfile['error'][$i] == 7 ) ? TRUE : FALSE;
				$error = ( $userfile['error'][$i] == 0 ) ? FALSE : TRUE;
				if( $error == TRUE )
				{
					$message .= '<tr>
							<td align="right">' . $lang['upload_failed_no_file'] . '</td>
							<td>&nbsp;--&nbsp;</td>
							<td>' . $lang['upload_failed'] . '</td>
							<td>&nbsp;--&nbsp;</td>
							<td>' . $lang['php_file_error'][$userfile['error'][$i]] . '</td>
						</tr>';
				}
			}
			else
			{
				if( !empty($userfile['name'][$i]) && ( empty($userfile['tmp_name'][$i]) || $userfile['tmp_name'][$i] == 'none' ) && $userfile['size'][$i] == 0 )
				{	
					// If there's a name, but no tmp_name or size, then we have exceeded the MAX_FILE_SIZE (see above)
					$message .= '<tr>
							<td align="right">' . $userfile['name'][$i] . '</td>
							<td>&nbsp;--&nbsp;</td>
							<td>' . $lang['upload_failed'] . '</td>
							<td>&nbsp;--&nbsp;</td>
							<td>' . $lang['php_file_error'][2] . '</td>
						</tr>';
					$error = TRUE;
				}
				if( empty($userfile['tmp_name'][$i]) && $userfile['tmp_name'][$i] == 'none' && $userfile['size'][$i] == 0 )
				{
					// No file has been choosen.
					$message .= '<tr>
							<td align="right">' . $lang['upload_failed_no_file'] . '</td>	
							<td>&nbsp;--&nbsp;</td>
							<td>' . $lang['upload_failed'] . '</td>	
							<td>&nbsp;--&nbsp;</td>
							<td>' . $lang['php_file_error'][4] . '</td>
						</tr>';
					$error = TRUE;
				}
			}
 
 			if( $error == FALSE )
 			{
				// Was there an uploaded file?
				if( is_uploaded_file($userfile['tmp_name'][$i]) )
				{
					// Is the filesize below the max filesize?
					if( $max_file_size > $userfile['size'][$i] )
					{
						// Does the uploaded file have the right extension?
						list($name, $ext) = explode(".", basename($userfile['name'][$i]));

						if( (($ext == "gif") && ($userfile['type'][$i] == "image/gif")) || (($ext == "jpe") && (($userfile['type'][$i] == "image/jpeg") || ($userfile['type'][$i] == "image/pjpeg"))) || (($ext == "jpg") && (($userfile['type'][$i] == "image/jpeg") || ($userfile['type'][$i] == "image/pjpeg"))) || (($ext == "jpeg") && (($userfile['type'][$i] == "image/jpeg") || ($userfile['type'][$i] == "image/pjpeg"))) || (($ext == "png") && (($userfile['type'][$i] == "image/png") || ($userfile['type'][$i] == "image/x-png"))) || (($ext == "pak") && ($userfile['type'][$i] == "text/plain")) || (($ext == "pak2") && ($userfile['type'][$i] == "text/plain")) )
						{
							// Does the file already exist?
							if( !file_exists($path . basename($userfile['name'][$i])) )
							{							
								// Copy file.							
								if( @copy($userfile['tmp_name'][$i], $path . basename($userfile['name'][$i])) )
								{
									@chmod($path . basename($userfile['name'][$i]), 0644);
									$message .= '<tr>
											<td align="right">' . $userfile['name'][$i] . '</td>
											<td>&nbsp;--&nbsp;</td>
											<td>' . $lang['upload_success'] . '</td>
											<td>&nbsp;--&nbsp;</td>
											<td>' . $lang['php_file_error'][0] . '</td>
										</tr>';
								}
								else
								{
									$message .= '<tr>
											<td align="right">' . $userfile['name'][$i] . '</td>
											<td>&nbsp;--&nbsp;</td>
											<td>' . $lang['upload_failed'] . '</td>
											<td>&nbsp;--&nbsp;</td>
											<td>' . $lang['upload_failed_reason1'] . '</td>
										</tr>';
								}
							}
							else
							{
								$message .= '<tr>
										<td align="right">' . $userfile['name'][$i] . '</td>
										<td>&nbsp;--&nbsp;</td>
										<td>' . $lang['upload_failed'] . '</td>
										<td>&nbsp;--&nbsp;</td>
										<td>' . $lang['upload_failed_reason2'] . '</td>
									</tr>';
							}
						}
						else
						{
							$message .= '<tr>
									<td align="right">' . $userfile['name'][$i] . '</td>
									<td>&nbsp;--&nbsp;</td>
									<td>' . $lang['upload_failed'] . '</td>
									<td>&nbsp;--&nbsp;</td>
									<td>' . $lang['upload_failed_reason3'] . '</td>
								</tr>';
						}
					}
					else
					{
						$message .= '<tr>
								<td align="right">' . $userfile['name'][$i] . '</td>
								<td>&nbsp;--&nbsp;</td>
								<td>' . $lang['upload_failed'] . '</td>
								<td>&nbsp;--&nbsp;</td>
								<td>' . $lang['php_file_error'][2] . '</td>
							</tr>';
					}

					@unlink($userfile['tmp_name'][$i]); // Delete tmp file.
				}
				else
				{
					$message .= '<tr>
							<td align="right">' . $userfile['name'][$i] . '</td>
							<td>&nbsp;--&nbsp;</td>
							<td>' . $lang['upload_failed'] . '</td>
							<td>&nbsp;--&nbsp;</td>
							<td>' . $lang['php_file_error'][4] . '</td>
						</tr>';	
				}
			}
		}

		$message .= '</table>';
		$message .= print_r($userfile) . "<br /><br />" . sprintf($lang['Click_return_upload'], "<a href=\"" . append_sid("admin_smilies.$phpEx?upload") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx") . "\">", "</a>");

		message_die(GENERAL_MESSAGE, $message);
	}
	else
	{
		$total_boxes = 10; // Maximum amount of upload boxes.
		$upload_boxes = '';
		for( $i=1; $i<=$total_boxes; $i++ )
		{
			$selected = ( $upload_amount == $i ) ? ' selected="selected"' : '';
			$upload_boxes .= '<option value="' . $i . '"' . $selected . '>' . $i . '</option>';
		}
		
		for( $i=0; $i<$upload_amount; $i++ )
		{
			$template->assign_block_vars("files", array());
		}
	
		$template->set_filenames(array(
			"body" => "admin/smile_upload_body.tpl")
		);

		$template->assign_vars(array(
			"L_UPLOAD_TITLE" => $lang['Upload'] . ' ' . $lang['Smilies'],
			"L_UPLOAD_DESCRIPTION" =>  sprintf($lang['upload_desc'], $board_config['smilies_path'], $max_file_size),
			"L_UPLOAD_WARNING" => ( is_writeable($phpbb_root_path . $board_config['smilies_path']) ) ? '' : sprintf($lang['upload_warning'], $board_config['smilies_path']),
			"L_UPLOAD_AMOUNT" => $lang['upload_amount'],

			"S_UPLOAD_BOXES" => $upload_boxes,
			"S_FILE_COUNT" => $upload_amount,
			"S_MAX_FILE_SIZE" => $max_file_size,
			"S_SMILEY_CAT_ACTION" => append_sid("admin_smilies.$phpEx?upload"))
		);
	}
}
else if( isset($HTTP_GET_VARS['import_pack']) || isset($HTTP_POST_VARS['import_pack']) )
{
			//
	// Display the "Smiley Pack Import" page.
			//
	read_smiles_directory($board_config['smilies_path']);

	// Create *.PAK file dropdown list.
	$paks = '<option value="0">' . $lang['select_paks'] . '</option>';
	while( list($key, $value) = @each($smiley_paks) )
	{
		if( !empty($value) )
		{
			$paks .= '<option value="' . $value . '">' . $value . '</option>';
		}
	}
	// Create category dropdown menu.
	$category_import = '';
	for( $i=0; $i<$num_cats; $i++ )
				{
		$category_import .= '<option value="' . $array_cat_data[$i]['cat_order'] . '">' . $array_cat_data[$i]['cat_name'] . '</option>';
				}

	$template->set_filenames(array(
		"body" => "admin/smile_import_body.tpl")
	);

	$template->assign_vars(array(
		"L_IMPORT_TITLE" => $lang['Smilie_import_pak_title'],
		"L_IMPORT_DESCRIPTION" =>  sprintf($lang['import_desc'], $board_config['smilies_path']),
		'L_IMPORT_DESC_EXPLAIN' => $lang['import_desc_explain'],
		'L_ITEMS_REQUIRED' => $lang['Items_required'],
		"L_SELECT_PAK" => $lang['choose_smiley_pak'],
		"L_SELECT_IMPORT" => $lang['import_cat'],
		"L_DEL_EXISTING" => $lang['delete_smiley'],
		"L_DEL_EXISTING_ALL" => $lang['delete_all'],
		"L_CONFLICTS" => $lang['smiley_conflicts'],
		"L_REPLACE_EXISTING" => $lang['existing_replace'], 
		"L_KEEP_EXISTING" => $lang['existing_keep'],

		"S_CAT_PAK" => $paks,
		"S_CAT_IMPORT" => $category_import,
		"S_SMILEY_CAT_ACTION" => append_sid("admin_smilies.$phpEx"))
	);
}
else if( isset($HTTP_GET_VARS['import_pack_submit']) || isset($HTTP_POST_VARS['import_pack_submit']) )
{
			//
	// Admin is importing a list, a "Smiley Pack"
			//
	$smile_pak = ( isset($HTTP_POST_VARS['smile_pak']) ) ? trim($HTTP_POST_VARS['smile_pak']) : '';
	$cat_order = ( isset($HTTP_POST_VARS['import_cat']) ) ? intval($HTTP_POST_VARS['import_cat']) : '';
	$delete_smiley = ( isset($HTTP_POST_VARS['del_smiley']) ) ? intval($HTTP_POST_VARS['del_smiley']) : '';
	$delete_all = ( isset($HTTP_POST_VARS['del_all']) ) ? intval($HTTP_POST_VARS['del_all']) : '';
	$replace_existing = ( isset($HTTP_POST_VARS['replace']) ) ? intval($HTTP_POST_VARS['replace']) : '';

			//
	// Used to separate the smiley fields for import/export *.pak files..
			//
	$delimeter  = '=+:';

	list($name, $ext) = explode(".", $smile_pak);

	if( !empty($ext) && $ext == "pak" )
	{
		// Open file, read contents.
		$fcontents = @file($phpbb_root_path . $board_config['smilies_path'] . '/'. $smile_pak);

		if( empty($fcontents) )
		{
			message_die(GENERAL_MESSAGE, $lang['pak_file_empty']);
		}

		// Delete existing smilies before importing?
		if( $delete_smiley )
		{
			$sql = "DELETE FROM " . SMILIES_TABLE . "
				WHERE cat_id = " . $cat_order;
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't delete current smilies.", "", __LINE__, __FILE__, $sql);
			}

			$order_num = 0;
			}
			else
			{
			// Put all the codes for the category in an array.
			$sql = "SELECT code
				FROM " . SMILIES_TABLE . "
				WHERE cat_id = " . $cat_order;
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't get current smilies.", "", __LINE__, __FILE__, $sql);
			}
			$cur_smilies = $db->sql_fetchrowset($result);

			$order_num = count($cur_smilies);

			for( $i=0; $i<$order_num; $i++ )
			{
				$k = $cur_smilies[$i]['code'];
				$smiles[$k] = 1;
			}
			}

		$replace_count = $add_count = 0;

		for( $i=0; $i<count($fcontents); $i++ )
		{
			// Take each line and separate the fields.
			$smile_data = explode($delimeter, trim(addslashes($fcontents[$i])));

			for( $j=2; $j<count($smile_data); $j++ )
			{
				//
				// Replace > and < with the proper html_entities for matching.
				//
				$smile_data[$j] = str_replace("<", "&lt;", $smile_data[$j]);
				$smile_data[$j] = str_replace(">", "&gt;", $smile_data[$j]);
				$k = $smile_data[$j];

				if( $smiles[$k] == 1 )
				{
					// What should be done if there are conflicts?
					if( $replace_existing )
					{
						// Replace existing smiley.
						$sql = "UPDATE " . SMILIES_TABLE . " 
							SET smile_url = '" . str_replace("\'", "''", $smile_data[0]) . "', emoticon = '" . str_replace("\'", "''", $smile_data[1]) . "' 
							WHERE code = '" . str_replace("\'", "''", $smile_data[$j]) . "'
								AND cat_id = " . $cat_order;
			if( !$result = $db->sql_query($sql) )
			{
							message_die(GENERAL_ERROR, "Couldn't update smiley database", "", __LINE__, __FILE__, $sql);
			}

						$replace_count++;
					}
				}
				else
				{
					// Add new smiley.
					$order_num++;

					$sql = "INSERT INTO " . SMILIES_TABLE . " (code, smile_url, emoticon, cat_id, smilies_order)
						VALUES('" . str_replace("\'", "''", $smile_data[$j]) . "', '" . str_replace("\'", "''", $smile_data[0]) . "', '" . str_replace("\'", "''", $smile_data[1]) . "', '" . $cat_order . "', '" . $order_num . "')";
					if( !$result = $db->sql_query($sql) )
				{
						message_die(GENERAL_ERROR, "Couldn't insert smiley into database", "", __LINE__, __FILE__, $sql);
					}

					$add_count++;
				}
			}
				}

		$smiles = ( $add_count == 0 || $add_count >= 2 ) ? $lang['smilies'] : $lang['smiley'];

		$message = sprintf($lang['smiley_import_success1'], $add_count, $smiles, $replace_count) . "<br /><br />" . sprintf($lang['Click_return_import'], "<a href=\"" . append_sid("admin_smilies.$phpEx?import_pack") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx") . "\">", "</a>");
		}
	else if( !empty($ext) && $ext == "pak2" )
	{
		// Open file, read contents.
		$fcontents = @file($phpbb_root_path . $board_config['smilies_path'] . '/'. $smile_pak);

		if( empty($fcontents) )
		{
			message_die(GENERAL_MESSAGE, $lang['pak_file_empty']);
	}

		$cat_data = explode($delimeter, trim(addslashes($fcontents[7])));

		// I want to make sure that an old PAK2 file is not being used.  
		// v2.0.3 added a new field as did v2.0.4, the total fields should be 10.
		if( count($cat_data) != 10 )
	{
			message_die(GENERAL_MESSAGE, $lang['pak_file_old']);
		}

		// Delete all categories and smilies before importing.
		if( $delete_all )
	{
			$sql = "TRUNCATE " . SMILIES_TABLE;
			if( !$result = $db->sql_query($sql) )
		{
				message_die(GENERAL_ERROR, "Couldn't delete smilies.", "", __LINE__, __FILE__, $sql);
		}

			$sql = "TRUNCATE " . SMILIES_CAT_TABLE;
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't delete categories.", "", __LINE__, __FILE__, $sql);
			}

			$num_cats = 0;
	}

		$cat_count = 0;
		$order_array = array();

		// Import the categories.
		for( $i=0; $i<count($fcontents); $i++ )
		{
			if( ($fcontents[$i]{0} == '#') && ($fcontents[$i]{1} != '#') )
			{
				// Take each line and separate the fields.
				$cat_data = explode($delimeter, trim(addslashes($fcontents[$i])));

				$num_cats++;
				$cat_data[0] = str_replace('#', '', $cat_data[0]);
  
				$sql = "INSERT INTO " . SMILIES_CAT_TABLE . " (cat_name, description, cat_order, cat_perms, cat_group, cat_forum, cat_special, cat_open, cat_icon_url, smilies_popup)
					VALUES ('" . str_replace("\'", "''", $cat_data[0]) . "','" . str_replace("\'", "''", $cat_data[1]) . "','" . $num_cats . "','" . $cat_data[3] . "','" . $cat_data[4] . "','" . $cat_data[5] . "','" . $cat_data[6] . "','" . $cat_data[7] . "','" . str_replace("\'", "''", $cat_data[8]) . "','" . $cat_data[9] . "')";
				if( !$result = $db->sql_query($sql) )
	{
					message_die(GENERAL_ERROR, "Couldn't insert new category into database.", "", __LINE__, __FILE__, $sql);
	}
	else
	{
					$cat_count++;
					$order_array[$cat_data[2]] = $num_cats;
				}
			}
	}

		$smiley_count = 0;

		// Import the smilies.
		for( $i=0; $i<count($fcontents); $i++ )
	{
			if( $fcontents[$i]{0} != '#' )
			{
				// Take each line and separate the fields.
				$smile_data = explode($delimeter, trim(addslashes($fcontents[$i])));

				$sql = "INSERT INTO " . SMILIES_TABLE . " (code, smile_url, emoticon, cat_id, smilies_order)
					VALUES('" . str_replace("\'", "''", $smile_data[2]) . "', '" . str_replace("\'", "''", $smile_data[0]) . "', '" . str_replace("\'", "''", $smile_data[1]) . "', '" . $order_array[$smile_data[3]] . "', '" . $smile_data[4] . "')";
		if( !$result = $db->sql_query($sql) )
		{
					message_die(GENERAL_ERROR, "Couldn't insert smiley into database", "", __LINE__, __FILE__, $sql);
				}
				else
				{
					$smiley_count++;
				}
			}
		}

		$cats = ( $cat_count == 0 || $cat_count >= 2 ) ? $lang['categories'] : $lang['category'];
		$smiles = ( $smiley_count == 0 || $smiley_count >= 2 ) ? $lang['smilies'] : $lang['smiley'];

		$message = sprintf($lang['smiley_import_success2'], $cat_count, $cats, $smiley_count, $smiles) . "<br /><br />" . sprintf($lang['Click_return_import'], "<a href=\"" . append_sid("admin_smilies.$phpEx?import_pack") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx") . "\">", "</a>");
	}
	else
	{
		$message = $lang['smiley_import_fail'] . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx") . "\">", "</a>");
	}

	message_die(GENERAL_MESSAGE, $message);	
}
else if( isset($HTTP_POST_VARS['export_pack']) || isset($HTTP_GET_VARS['export_pack']) )
{
	//
	// Display the "Smiley Pack Export" page.
	//

	// Create export dropdown menu.
	$category_export = '<option value="0" selected="selected">' . $lang['export_all'] . '</option>';
	for( $i=0; $i<$num_cats; $i++ )
	{
		$category_export .= '<option value="' . $array_cat_data[$i]['cat_order'] . '">' . $array_cat_data[$i]['cat_name'] . '</option>';
	}

	$template->set_filenames(array(
		"body" => "admin/smile_export_body.tpl")
	);

	$template->assign_vars(array(
		"L_EXPORT_TITLE" => $lang['Smilie_export_pak_title'],
		"L_EXPORT_DESCRIPTION" => $lang['export_desc'],
		"L_EXPORT_TYPE" => $lang['export_type'],
		"L_EXPORT_TYPE_PAK" => $lang['export_type_pak'],
		"L_EXPORT_TYPE_CAT" => $lang['export_type_cat'],
		"L_SELECT_EXPORT" => $lang['export_cat'],

		"S_CAT_FORUM" => $forum_list,
		"S_CAT_PAK" => $smile_paks_select,
		"S_CAT_EXPORT" => $category_export,
		"S_SMILEY_CAT_ACTION" => append_sid("admin_smilies.$phpEx"))
	);
		}
else if( isset($HTTP_POST_VARS['export_pack_submit']) || isset($HTTP_GET_VARS['export_pack_submit']) )
{
	//
	// Admin has selected to export a smiley pak.
	//
	$cat_order = ( isset($HTTP_POST_VARS['export_cat']) ) ? intval($HTTP_POST_VARS['export_cat']) : intval($HTTP_GET_VARS['cat']);
	$export_type = ( isset($HTTP_POST_VARS['export_type']) ) ? intval($HTTP_POST_VARS['export_type']) : intval($HTTP_GET_VARS['type']);
	$pack_submit = ( isset($HTTP_GET_VARS['export_pack_submit']) ) ? trim($HTTP_GET_VARS['export_pack_submit']) : '';

	//
	// Used to separate the smiley fields for import/export *.pak files..
	//
	$delimeter  = '=+:';

	if( $pack_submit == "send" )
	{
		if( $export_type )
		{
			// Contains category data.
			if( $cat_order )
			{
				$sql1 = "SELECT *
					FROM " . SMILIES_CAT_TABLE . "
					WHERE cat_order = '" . $cat_order . "'
					ORDER BY cat_order";

				$sql2 = "SELECT *
					FROM " . SMILIES_TABLE . "
					WHERE cat_id = '" . $cat_order . "'
					ORDER BY cat_id, smilies_order";

				$filename = $array_cat_data[$cat_order-1]['cat_name'];
			}
			else
			{
				$sql1 = "SELECT *
					FROM " . SMILIES_CAT_TABLE . "
					ORDER BY cat_order";

				$sql2 = "SELECT *
			FROM " . SMILIES_TABLE . "
					ORDER BY cat_id, smilies_order";
				$filename = 'smiles';
			}

			if( !$result1 = $db->sql_query($sql1) )
			{
				message_die(GENERAL_ERROR, "Could not get category list", "", __LINE__, __FILE__, $sql1);
			}
			else
			{
				if( !$result2 = $db->sql_query($sql2) )
				{
					message_die(GENERAL_ERROR, "Could not get smiley list", "", __LINE__, __FILE__, $sql2);
				}

					$resultset1 = $db->sql_fetchrowset($result1);
					$resultset2 = $db->sql_fetchrowset($result2);
					
					$smile_pak = $lang['pak_header'];

				for ($i = 0; $i < sizeof($resultset1); $i++ )
					{
						$smile_pak .= '#';
						$smile_pak .= $resultset1[$i]['cat_name'] . $delimeter;
						$smile_pak .= $resultset1[$i]['description'] . $delimeter;
						$smile_pak .= $resultset1[$i]['cat_order'] . $delimeter;
						$smile_pak .= $resultset1[$i]['cat_perms'] . $delimeter;
						$smile_pak .= $resultset1[$i]['cat_group'] . $delimeter;
						$smile_pak .= $resultset1[$i]['cat_forum'] . $delimeter;
						$smile_pak .= $resultset1[$i]['cat_special'] . $delimeter;
						$smile_pak .= $resultset1[$i]['cat_open'] . $delimeter;
						$smile_pak .= $resultset1[$i]['cat_icon_url'] . $delimeter;
					$smile_pak .= $resultset1[$i]['smilies_popup'] . "\n";
					}

				for ($i = 0; $i < sizeof($resultset2); $i++ )
					{
						$smile_pak .= $resultset2[$i]['smile_url'] . $delimeter;
						$smile_pak .= $resultset2[$i]['emoticon'] . $delimeter;
						$smile_pak .= $resultset2[$i]['code'] . $delimeter;
						$smile_pak .= $resultset2[$i]['cat_id'] . $delimeter;
					$smile_pak .= $resultset2[$i]['smilies_order'] . "\n";
					}

					header("Content-Type: text/x-delimtext; name=\"$filename.pak2\"");
					header("Content-disposition: attachment; filename=$filename.pak2");

					echo $smile_pak;

				exit;
			}
		}
		else
		{
			if( $cat_order )
			{
				$where = ' WHERE cat_id = ' . $cat_order;
				$filename = $array_cat_data[$cat_order-1]['cat_name'];
			}
			else
			{
				$filename = 'smiles';
			}

			$sql = "SELECT code, smile_url, emoticon
				FROM " . SMILIES_TABLE . $where . "
				ORDER BY cat_id, smilies_order";
		if( !$result = $db->sql_query($sql) )
		{
				message_die(GENERAL_ERROR, "Could not get smiley list", "", __LINE__, __FILE__, $sql);
			}
			
				$resultset = $db->sql_fetchrowset($result);

			$smile_pak = '';
			for ($i = 0; $i < sizeof($resultset); $i++ )
				{
					$smile_pak .= $resultset[$i]['smile_url'] . $delimeter;
					$smile_pak .= $resultset[$i]['emoticon'] . $delimeter;
				$smile_pak .= $resultset[$i]['code'] . "\n";
				}

				header("Content-Type: text/x-delimtext; name=\"$filename.pak\"");
				header("Content-disposition: attachment; filename=$filename.pak");

				echo $smile_pak;

		exit;
	}
	}

	$type = ( $export_type == 1 ) ? 2 : '';

	$message = sprintf($lang['export_smiles'], "<a href=\"" . append_sid("admin_smilies.$phpEx?export_pack_submit=send&amp;cat=" . $cat_order . "&amp;type=" . $export_type, true) . "\">", "</a>", $type, $type, $type) . "<br /><br />" . sprintf($lang['Click_return_export'], "<a href=\"" . append_sid("admin_smilies.$phpEx?export_pack") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx") . "\">", "</a>");

	message_die(GENERAL_MESSAGE, $message);

}
else if( isset($HTTP_POST_VARS['view_perms']) || isset($HTTP_GET_VARS['view_perms']) )
{
	//
	// Display the "View Permissions" Page.
	//

	//
	// More or less took all this code from admin_forums.php.
	//
	$sql = "SELECT cat_id, cat_title, cat_order
		FROM " . CATEGORIES_TABLE . "
		ORDER BY cat_order";
	if( !$cat_result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, "Could not query categories list", "", __LINE__, __FILE__, $sql);
	}

	if( $total_categories = $db->sql_numrows($cat_result) )
	{
		$category_rows = $db->sql_fetchrowset($cat_result);

		$sql = "SELECT forum_id, cat_id, forum_name, forum_desc
			FROM " . FORUMS_TABLE . "
			ORDER BY cat_id, forum_order";
		if( !$forum_result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, "Could not query forums information", "", __LINE__, __FILE__, $sql);
		}

		if( $total_forums = $db->sql_numrows($forum_result) )
		{
			$forum_rows = $db->sql_fetchrowset($forum_result);
		}

		// Start outputting categories.
		for( $i=0; $i<$total_categories; $i++ )
		{
			$cat_id = $category_rows[$i]['cat_id'];

			$template->assign_block_vars('catrow', array( 
				'CAT_DESC' => $category_rows[$i]['cat_title'],
				'U_VIEWCAT' => append_sid($phpbb_root_path . "index.$phpEx?" . POST_CAT_URL . "=$cat_id"))
			);

			// Start outputting forums.
			for( $j=0; $j<$total_forums; $j++ )
			{
				$forum_id = $forum_rows[$j]['forum_id'];

				if( $forum_rows[$j]['cat_id'] == $cat_id )
				{
					$row_class = ( !($j % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

					$forum_smilies = $pm_categories = $open_categories = '';
					
					for( $k=0; $k<$num_cats; $k++ )
					{
						$forums_array = explode(" ", $array_cat_data[$k]['cat_forum']);

						for( $l=0; $l<count($forums_array); $l++ )
						{
							if( $forums_array[$l] == $forum_id )
							{
								$forum_smilies .= '<tr><td width="50%" valign="top" nowrap="nowrap" class="' . $row_class . '"><a href="' . append_sid("admin_smilies.$phpEx?cat_edit&amp;selectcat=" . $array_cat_data[$k]['cat_order']) . '" title="' . $lang['click_edit'] . '"><span class="gen">' . $array_cat_data[$k]['cat_name'] . '</span></a></td><td>-</td>';

								if( $array_cat_data[$k]['cat_special'] == '-2' )
								{
									$forum_smilies .= '<td width="50%" nowrap="nowrap" class="' . $row_class . '">' . $lang['perms'][$array_cat_data[$k]['cat_perms']] . '</td></tr>';
								}
								else
								{
									$forum_smilies .= '<td width="50%" nowrap="nowrap" class="' . $row_class . '">' . $lang['perms'][$array_cat_data[$k]['cat_perms']] . '<br /><span class="gensmall" style="color: red">' . $lang['special'][$array_cat_data[$k]['cat_special']] . '(SP)</span></td></tr>';
		}
							}
							// PM's start here.
							if( $forums_array[$l] == 999 )
							{
								$pm_categories .= '<tr><td width="50%" valign="top" nowrap="nowrap"><a href="' . append_sid("admin_smilies.$phpEx?cat_edit&amp;selectcat=" . $array_cat_data[$k]['cat_order']) . '" title="' . $lang['click_edit'] . '"><span class="gen">' . $array_cat_data[$k]['cat_name'] . '</span></a></td><td>-</td>';
					
								if( $array_cat_data[$k]['cat_special'] == '-2' )
								{
									$pm_categories .= '<td width="50%" nowrap="nowrap">' . $lang['perms'][$array_cat_data[$k]['cat_perms']] . '</td></tr>';
								}
								else
								{
									$pm_categories .= '<td width="50%" nowrap="nowrap">' . $lang['perms'][$array_cat_data[$k]['cat_perms']] . '<br /><span class="gensmall" style="color: red">' . $lang['special'][$array_cat_data[$k]['cat_special']] . '(SP)</span></td></tr>';
								}
							}
						}
						// Open's start here.
						if( $cat_open = $array_cat_data[$k]['cat_open'] )
						{
							$open_categories .= '<tr><td width="50%" valign="top" nowrap="nowrap"><a href="' . append_sid("admin_smilies.$phpEx?cat_edit&amp;selectcat=" . $array_cat_data[$k]['cat_order']) . '" title="' . $lang['click_edit'] . '"><span class="gen">' . $array_cat_data[$k]['cat_name'] . '</span></a></td><td>-</td>';
							
							if( $array_cat_data[$k]['cat_special'] == '-2' )
							{
								$open_categories .= '<td width="50%" nowrap="nowrap">' . $lang['perms'][$array_cat_data[$k]['cat_perms']] . '</td></tr>';
							}
							else
							{
								$open_categories .= '<td width="50%" nowrap="nowrap">' . $lang['perms'][$array_cat_data[$k]['cat_perms']] . '<br /><span class="gensmall" style="color: red">' . $lang['special'][$array_cat_data[$k]['cat_special']] . '(SP)</span></td></tr>';
							}
						}
					}

					$pm_categories = ( $pm_categories ) ? $pm_categories : '<tr><td colspan="2" align="center" valign="middle">--</td></tr>';

					$open_categories = ( $open_categories ) ? $open_categories : $lang['no_open_categories'];
					
					$forum_smilies = ( $forum_smilies ) ? $forum_smilies : '<tr><td colspan="2" align="center" valign="middle">--</td></tr>';

					$template->assign_block_vars('catrow.forumrow', array(
						'FORUM_NAME' => $forum_rows[$j]['forum_name'],
						'FORUM_DESC' => $forum_rows[$j]['forum_desc'],
						'FORUM_SMILIES' => $forum_smilies,
						'ROW_CLASS' => $row_class,
						'U_VIEWFORUM' => append_sid($phpbb_root_path . "viewforum.$phpEx?" . POST_FORUM_URL . "=$forum_id"))
					);
				}
			}
		}
	}

	if( $board_config['smilie_usergroups'] )
	{
		$sql = "SELECT group_id, group_name, group_description
			FROM " . GROUPS_TABLE . "
			WHERE group_single_user <> " . TRUE;
		if( $result = $db->sql_query($sql) )
		{
			$group_num = 0;
			$group_rows = array();
		while ( $row = $db->sql_fetchrow($result) )
		{
				$group_rows[] = $row;
				$group_num++;
			}
				
			$template->assign_block_vars('usergroups', array()
			);

			for( $i=0; $i<$group_num; $i++ )
			{
				$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

				$group_categories = '';
				for( $j=0; $j<$num_cats; $j++ )
				{
					$group_array = explode(" ", $array_cat_data[$j]['cat_group']);

					for( $k=0; $k<count($group_array); $k++ )
			{
						if( $group_array[$k] == $group_rows[$i]['group_id'] )
						{
							$group_categories .= '<tr><td width="50%" valign="top" nowrap="nowrap"><a href="' . append_sid("admin_smilies.$phpEx?cat_edit&amp;selectcat=" . $array_cat_data[$j]['cat_order']) . '" title="' . $lang['click_edit'] . '"><span class="gen">' . $array_cat_data[$j]['cat_name'] . '</span></a></td><td>-</td>';
							
							if( $array_cat_data[$j]['cat_special'] == '-2' )
							{
								$group_categories .= '<td width="50%" nowrap="nowrap">' . $lang['perms'][$array_cat_data[$j]['cat_perms']] . '</td></tr>';
							}
							else
				{
								$group_categories .= '<td width="50%" nowrap="nowrap">' . $lang['perms'][$array_cat_data[$j]['cat_perms']] . '<br /><span class="gensmall" style="color: red">' . $lang['special'][$array_cat_data[$j]['cat_special']] . '(SP)</span></td></tr>';
							}
				}
			}
		}

				$template->assign_block_vars('usergroups.grouprow', array(
					'ROW_CLASS' => $row_class,
					'U_USERGROUP' => append_sid($phpbb_root_path . "groupcp.$phpEx?" . POST_GROUPS_URL . "=" . $group_rows[$i]['group_id']),
					'USERGROUP_NAME' => $group_rows[$i]['group_name'],
					'USERGROUP_DESC' => $group_rows[$i]['group_description'],
					'USERGROUP_SMILIES' => $group_categories)
				);
	}
		}
	}
	
	$template->set_filenames(array(
		"body" => "admin/smile_perms_body.tpl")
	);

	$template->assign_vars(array(
		'L_PAGE_TITLE' => $lang['Smiley'] . ' ' . $lang['Permissions'], 
		'L_PAGE_DESC' => $lang['perms_desc'],
		'L_UG_HEADER1' => $lang['perms_ug_header1'],
		'L_UG_HEADER2' => $lang['perms_ug_header2'],
		'L_HEADER1' => $lang['perms_header1'],
		'L_HEADER2' => $lang['perms_header2'],
		'L_HEADER3' => $lang['perms_header3'],
		'L_HEADER4' => $lang['perms_header4'],
		'ROW_SPAN' => $total_forums + $total_categories + $total_categories + 1,
		'PM_SMILIES' => $pm_categories,
		'OPEN_CATEGORIES' => $open_categories)
	);
}
else if( isset($HTTP_POST_VARS['unused_smilies']) || isset($HTTP_GET_VARS['unused_smilies']) )
{
		//
	// Display the "View Unused Smilies" Page.
		//

	// Note to self, because of pagination, data comes in from POST *and* GET!
	$cat = ( isset($HTTP_POST_VARS['cat']) ) ? intval($HTTP_POST_VARS['cat']) : intval($HTTP_GET_VARS['cat']);
	$code = ( isset($HTTP_POST_VARS['code']) ) ? intval($HTTP_POST_VARS['code']) : intval($HTTP_GET_VARS['code']);
	$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : '';
	$per_page2 = ( isset($HTTP_POST_VARS['per_page']) ) ? intval($HTTP_POST_VARS['per_page']) : intval($HTTP_GET_VARS['per_page']);


	// Read all the images in the smiles folder and put them into an array.
	read_smiles_directory($board_config['smilies_path']);

	// Get all installed smiley urls and put them into an array.
	$sql = "SELECT smile_url
		FROM " . SMILIES_TABLE;
	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, "Couldn't get smiley data from database", "", __LINE__, __FILE__, $sql);
	}

	$rowset = array();
	while( $row = $db->sql_fetchrow($result) )
	{
		if( empty($rowset[$row['smile_url']]) )
		{
			$rowset[$row['smile_url']] = $row['smile_url'];
		}
	}	
	$db->sql_freeresult($result);

	// Find the differences between the two arrays.
	// PHP4 has a built-in function, but I can't use it. Must work with PHP3. :)
	sort($smiley_images);
	sort($rowset);

	$smiley = array();

	sort($smiley_images);
	sort($rowset);

	$a = $b = 0;

	$maxa = count($smiley_images)-1;
	$maxb = count($rowset)-1;

	while( true )
	{
		if( $a > $maxa )
		{
			// done; rest of $used isn't in $all
			break;
		}
		if( $b > $maxb )
		{
			// rest of $all is unused
			for( ; $a <= $maxa; $a++ )
			{
				$smiley[] = $smiley_images[$a];
			}
			break;
		}

		if( $smiley_images[$a] > $rowset[$b] )
		{
			// $used[$b] isn't in $all?
			$b++;
			continue;
		}

		if( $smiley_images[$a] == $rowset[$b] )
		{
			// $all[$a] is used
			$a++;
			$b++;
			continue;
		}

		$smiley[] = $smiley_images[$a];

		$a++;
	}

	sort($smiley);
	
	$num_smilies = count($smiley);

	// Create category dropdown menu.
	$category_list = '';
	for( $i=0; $i<$num_cats; $i++ )
	{
		$j = $i + 1;
		$selected = ( $cat == $j ) ? ' selected="selected"' : '';
		$category_list .= '<option value="' . $j . '"' . $selected . '>' . $array_cat_data[$i]['cat_name'] . '</option>';
	}

	// Stops you being sent back to an empty page if you've
	// just added all the smilies from the last page.
	if( $start == $num_smilies )
	{
		$start = $start - $per_page2;
	}

	// Calculations for pagination.
	if( $per_page2 == 0 )
	{
		$per_page = ( $num_smilies > 50 ) ? 50 : $num_smilies;
		$smiley_start = 0;
		$smiley_stop = $num_smilies;
		$per_page_total = $num_smilies;
	}
	else
	{
		$per_page = ( $per_page2 > $num_smilies ) ? $num_smilies : $per_page2;
		$page_num = ( $start <= 0 ) ? 1 : ($start / $per_page) + 1;
		$smiley_start = ($per_page * $page_num) - $per_page;
		$smiley_stop = ( ($per_page * $page_num) > $num_smilies ) ? $num_smilies : $smiley_start + $per_page;
		$per_page_total = $smiley_stop - $smiley_start;
	}

	if( $num_smilies )
		{
		$pagination = generate_pagination("admin_smilies.$phpEx?unused_smilies&amp;code=$code&amp;cat=$cat&amp;per_page=$per_page2", $num_smilies, $per_page, $start, FALSE);
		}

	$s_hidden_fields = '<input type="hidden" name="start" value="' . $start . '" /><input type="hidden" name="cat" value="' . $cat . '" /><input type="hidden" name="code" value="' . $code . '" /><input type="hidden" name="per_page" value="' . $per_page2 . '" /><input type="hidden" name="total" value="' . $per_page_total . '" />';

		$template->set_filenames(array(
		"body" => "admin/smile_unused_body.tpl")
		);

		$template->assign_vars(array(
		"L_SMILEY_FILENAME_CODE" => $lang['smiley_filename_code'],
		"L_PER_PAGE" => $lang['smilies_per_page'],
		"L_SELECT_CAT" => $lang['add_to_category'],
		"L_REFRESH" => $lang['Refresh'],

		"L_TITLE" => $lang['Smilie_unused_title'],
		"L_DESCRIPTION" => $lang['unused_desc'],
		"L_CODE" => $lang['Code'],
		"L_SMILE" => $lang['Smiley'],
		"L_EMOTION" => $lang['col_emotion'],
		"L_CATEGORY" => $lang['Category'],
		"L_ADD" => $lang['Add'],
		"L_TICK_ALL" => $lang['Mark_all'],
		"L_UNTICK_ALL" => $lang['Unmark_all'],

		"CODE1" => ( $code ) ? ' checked="checked"' : '',
		"CODE2" => ( !$code ) ? ' checked="checked"' : '',
		"PER_PAGE" => $per_page,
		"S_CAT_LIST" => $category_list,
		"S_PAGINATION" => ( $pagination ) ? $pagination : '<span class="gen"><b>1</b></span>',
			"S_HIDDEN_FIELDS" => $s_hidden_fields,
			"S_SMILEY_ACTION" => append_sid("admin_smilies.$phpEx"))
		);

		//
	// Show a list of smilies that are not installed.
		//
	if( $num_smilies )
		{
		$image_count = 1;

		for( $i=$smiley_start; $i<$smiley_stop; $i++ )
		{
			// Setup the dropdown list of categories for each smiley.
			$category_list = '';
			for ($j=0; $j<$num_cats; $j++)
			{
				$selected = ( $array_cat_data[$j]['cat_order'] == $cat ) ? ' selected="selected"' : '';
				$category_list .= '<option value="' . $array_cat_data[$j]['cat_id'] . '|' . $array_cat_data[$j]['cat_order'] . '"' . $selected . '>' . $array_cat_data[$j]['cat_name'] . '</option>';
			}

			// Remove .gif from filename so filename can be used as the smiley code.
			$name = ( $code == 1 ) ? ':'.basename($smiley[$i], ".gif").':' : '';

			$row_color = ( !($i % 2) ) ? $theme['td_color1'] : $theme['td_color2'];
			$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

			$template->assign_block_vars("smiles", array(
				"ROW_COLOR" => "#" . $row_color,
				"ROW_CLASS" => $row_class,
			
				"SMILEY_CODE" => $name,
				"SMILEY_IMG" => $phpbb_root_path . $board_config['smilies_path'] . '/' . $smiley[$i],
				"SMILEY_URL" => $smiley[$i],
				"SMILEY_ID" => $image_count,
				"SMILEY_ORDER" => $image_count,

				"CATEGORY_LIST" => $category_list)
			);

			$image_count++;
		}
	}
}
else if( isset($HTTP_POST_VARS['unused_submit']) || isset($HTTP_GET_VARS['unused_submit']) )
{
	//
	// Admin has selected unused smilies to add.
	//

	//
	// Get the submitted data, being careful to ensure that we only
	// accept the data we are looking for.
	//
	$total = ( isset($HTTP_POST_VARS['total']) ) ? intval($HTTP_POST_VARS['total']) : '';
	$code = ( isset($HTTP_POST_VARS['code']) ) ? intval($HTTP_POST_VARS['code']) : 0;
	$start = ( isset($HTTP_POST_VARS['start']) ) ? intval($HTTP_POST_VARS['start']) : 0;
	$cat = ( isset($HTTP_POST_VARS['cat']) ) ? intval($HTTP_POST_VARS['cat']) : 1;

	$additions = $errors = 0;

	for( $i=1; $i<=$total; $i++ )
	{
		// Get the submitted data from the other fields now.
		$smiley_code = ( isset($HTTP_POST_VARS["code$i"]) ) ? trim($HTTP_POST_VARS["code$i"]) : '';
		$smiley_url = ( isset($HTTP_POST_VARS["url$i"]) ) ? trim($HTTP_POST_VARS["url$i"]) : '';
		$smiley_emot = ( isset($HTTP_POST_VARS["emot$i"]) ) ? trim($HTTP_POST_VARS["emot$i"]) : '';
		$smiley_cat = ( isset($HTTP_POST_VARS["cat$i"]) ) ? $HTTP_POST_VARS["cat$i"] : ''; // 0|0 Array.
		$smiley_add = ( isset($HTTP_POST_VARS["add$i"]) ) ? intval($HTTP_POST_VARS["add$i"]) : '';

		if( $smiley_add )
		{
			if( $smiley_code )
			{
				// $smiley_cat contains two values, the category's id and order values.
				list($cat_id, $cat_order) = explode("|", $smiley_cat);

				// If no code was entered complain ...
				if( $smiley_code == '' ) { message_die(MESSAGE, $lang['Fields_empty']); }

				//
				// Convert < and > to proper htmlentities for parsing.
				//
				$smiley_code = str_replace('<', '&lt;', $smiley_code);
				$smiley_code = str_replace('>', '&gt;', $smiley_code);

				// Get smiley count for category.
				$sql = "SELECT *
					FROM " . SMILIES_TABLE . "
					WHERE cat_id = " . $cat_order;
				if( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't select category database.", "", __LINE__, __FILE__, $sql);
				}
				$smile_count = $db->sql_numrows($result);

				$lastsmiley = $smile_count + 1;

				// Insert a new smiley.
				$sql = "INSERT INTO " . SMILIES_TABLE . " (code, smile_url, emoticon, cat_id, smilies_order) 
					VALUES('" . str_replace("\'", "''", $smiley_code) . "', '" . str_replace("\'", "''", $smiley_url) . "', '" . str_replace("\'", "''", $smiley_emot) . "', '" . $cat_order . "', '" . $lastsmiley . "')";
				if( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't insert new smiley into database", "", __LINE__, __FILE__, $sql);
				}
				else
				{
					$additions++;
				}
			}
			else
			{
				$errors++;
			}
		}
	}

	$plural1 = ( $additions == 1 ) ? $lang['smiley_multi_add_success1'] : $lang['smiley_multi_add_success2'];
	$plural2 = ( $errors == 1 ) ? $lang['smiley_errors1'] : $lang['smiley_errors2'];

	$message = sprintf($plural1, $additions) . " " .  sprintf($plural2, $errors, $total) . "<br /><br />" . sprintf($lang['Click_return_unused'], "<a href=\"" . append_sid("admin_smilies.$phpEx?unused_smilies&amp;cat=$cat&amp;code=$code&amp;start=$start") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx") . "\">", "</a>");

	message_die(GENERAL_MESSAGE, $message);
}
else if( $mode != '' )
{
	switch( $mode )
	{
		case "smiley_order":
			//
			// Admin has changed a smiley order. Smiley List Utility Page
			//

			//
			// Get the submitted data being careful to ensure the the data
			// we recieve and process is only the data we are looking for.
			//
			$smiley_id = ( isset($HTTP_GET_VARS['id']) ) ? intval($HTTP_GET_VARS['id']) : '';
			$cat = ( isset($HTTP_GET_VARS['cat']) ) ? $HTTP_GET_VARS['cat'] : ''; // 0|0 Array.
			$old_order = ( isset($HTTP_GET_VARS['old']) ) ? intval($HTTP_GET_VARS['old']) : '';
			$new_order = ( isset($HTTP_GET_VARS['new']) ) ? intval($HTTP_GET_VARS['new']) : '';
			$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : '';

			// Get the two values from $cat variable.
			list($cat_id, $cat_order) = explode("|", $cat);

			// Check for order change
			if( $old_order != $new_order )
			{
				// Has the smiley been moved Up?
				if( $new_order > $old_order )
				{
					// Update smilies order.
					$sql = "UPDATE " . SMILIES_TABLE . "
						SET smilies_order = smilies_order - 1
						WHERE cat_id = '" . $cat_order . "'
							AND smilies_order > '" . $old_order . "'
							AND smilies_order <= " . $new_order;
					if( !$result = $db->sql_query($sql) )
					{
						message_die(GENERAL_ERROR, "Couldn't update smiley database.", "", __LINE__, __FILE__, $sql);
					}
					else
					{
						// Now update smilies order to that what it was changed to.
						$sql = "UPDATE " . SMILIES_TABLE . "
							SET smilies_order = '" . $new_order . "'
							WHERE smilies_id = " . $smiley_id;
						if( !$result = $db->sql_query($sql) )
						{
							message_die(GENERAL_ERROR, "Couldn't update smiley database.", "", __LINE__, __FILE__, $sql);
						}
						else
						{
							$message = sprintf($lang['smiley_order_success'], $old_order, $new_order) . "<br /><br />" . sprintf($lang['Click_return_mass_edit'], "<a href=\"" . append_sid("admin_smilies.$phpEx?mass_edit_view&amp;orig_cat=$cat_id|$cat_order&amp;start=$start") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx") . "\">", "</a>");
						}
					}
				}

				// Has the smiley been moved Down?
				if( $new_order < $old_order )
				{
					// Update smilies order.
					$sql = "UPDATE " . SMILIES_TABLE . "
						SET smilies_order = smilies_order + 1
						WHERE cat_id = '" . $cat_order . "'
							AND smilies_order >= '" . $new_order . "'
							AND smilies_order < " . $old_order;
					if( !$result = $db->sql_query($sql) )
					{
						message_die(GENERAL_ERROR, "Couldn't update smiley database.", "", __LINE__, __FILE__, $sql);
					}
					else
					{
						// Now update smilies order to that what it was changed to.
						$sql = "UPDATE " . SMILIES_TABLE . "
							SET smilies_order = '" . $new_order . "'
							WHERE smilies_id = " . $smiley_id;
						if( !$result = $db->sql_query($sql) )
						{
							message_die(GENERAL_ERROR, "Couldn't update smiley database.", "", __LINE__, __FILE__, $sql);
						}
						else
						{
							$message = sprintf($lang['smiley_order_success'], $old_order, $new_order) . "<br /><br />" . sprintf($lang['Click_return_mass_edit'], "<a href=\"" . append_sid("admin_smilies.$phpEx?mass_edit_view&amp;orig_cat=$cat_id|$cat_order&amp;start=$start") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx") . "\">", "</a>");
						}
					}
				}
			}
			else
			{
				$message = sprintf($lang['smiley_order_nochange'], $old_order, $new_order) . "<br /><br />" . sprintf($lang['Click_return_mass_edit'], "<a href=\"" . append_sid("admin_smilies.$phpEx?mass_edit_view&amp;orig_cat=$cat_id|$cat_order&amp;start=$start") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx") . "\">", "</a>");
			}
			message_die(GENERAL_MESSAGE, $message);

		break;

		case "popup_test":
			//
			// Admin has selected to test the popup window size.
			//

			//
			// Get the submitted data, being careful to ensure that we only
			// accept the data we are looking for.
			//
			$cat = ( !empty($HTTP_GET_VARS['cat']) ) ? intval($HTTP_GET_VARS['cat']) : '1';
			$start = ( !empty($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : '0';
			$width = ( !empty($HTTP_GET_VARS['width']) ) ? intval($HTTP_GET_VARS['width']) : '410';
			$height = ( !empty($HTTP_GET_VARS['height']) ) ? intval($HTTP_GET_VARS['height']) : '300';
			$smiley_group = ( !empty($HTTP_GET_VARS['grolis']) ) ? intval($HTTP_GET_VARS['grolis']) : '0';
			$group_columns = ( !empty($HTTP_GET_VARS['gcol']) ) ? intval($HTTP_GET_VARS['gcol']) : '9';
			$list_columns = ( !empty($HTTP_GET_VARS['lcol']) ) ? intval($HTTP_GET_VARS['lcol']) : '1';
			$pop_per_page = ( !empty($HTTP_GET_VARS['perp']) ) ? intval($HTTP_GET_VARS['perp']) : '0';

			$gen_simple_header = TRUE;
			
			$template->set_filenames(array(
				"body" => "admin/smile_popup_body.tpl")
			);

			$sql = "SELECT emoticon, code, smile_url   
				FROM " . SMILIES_TABLE . " 
				WHERE cat_id = $cat
				ORDER BY smilies_order";
			if( $result = $db->sql_query($sql) )
			{
				$num_smilies = 0;
				$rowset = $rowset2 = array();
				
				while( $row = $db->sql_fetchrow($result) )
				{
					if( empty($rowset2[$row['smile_url']]) )
					{
						$rowset2[$row['smile_url']] = $row['smile_url'];

						$rowset[$num_smilies]['smile_url'] = $row['smile_url'];
						$rowset[$num_smilies]['code'] = str_replace("'", "\\'", str_replace('\\', '\\\\', $row['code']));
						$rowset[$num_smilies]['emoticon'] = $row['emoticon'];
						
						$num_smilies++;
					} 
				}
				unset($rowset2);
				
				if( $num_smilies )
				{
					$smilies_split_row = $window_columns - 1;
					$s_colspan = $row = $col = 0;

					if( $pop_per_page == 0 )
					{
						$per_page = $smiley_stop = $num_smilies;
						$smiley_start = 0;
					}
					else
					{
						$per_page = ( $pop_per_page > $num_smilies ) ? $num_smilies : $pop_per_page;
						$page_num = ( $start <= 0 ) ? 1 : ($start / $per_page) + 1;
						$smiley_start = ($per_page * $page_num) - $per_page;
						$smiley_stop = ( ($per_page * $page_num) > $num_smilies ) ? $num_smilies : $smiley_start + $per_page;
					}

					if( $smiley_group && $list_columns != 0 )
					{
						$template->assign_block_vars('smiley_list', array());
						$group = 'smiley_list.';
						$smilies_split_row = $list_columns - 1;
					}
					else
					{
						$template->assign_block_vars('smiley_group', array());
						$group = 'smiley_group.';
						$smilies_split_row = $group_columns - 1;
					}
						
					for( $i=$smiley_start; $i<$smiley_stop; $i++ )
					{
						if( !$col )
						{
							$template->assign_block_vars($group . 'smilies_row', array());
						}

						$template->assign_block_vars($group . 'smilies_row.smilies_col', array(
							'SMILEY_CODE' => $rowset[$i]['code'],
							'SMILEY_CODE2' => str_replace("\\", "", $rowset[$i]['code']),
							'SMILEY_IMG' => $phpbb_root_path . $board_config['smilies_path'] . '/' . $rowset[$i]['smile_url'],
							'SMILEY_DESC' => $rowset[$i]['emoticon'])
						);

						$s_colspan = max($s_colspan, $col + 1);

						if( $col == $smilies_split_row )
						{
							if( $row == $per_page )
							{
								break;
							}
							$col = 0;
							$row++;
						}
						else
						{
							$col++;
						}
					}

					if( $smiley_group && ($list_columns != 0) && ($col != 0) && ($col < $per_page) && ($row != 0) )
					{
						$template->assign_block_vars('smiley_list.smilies_row.smilies_odd', array(
							'S_SMILIES_ODD_COLSPAN' => ($list_columns - $col) * 2)
						);
					}

					$pagination = generate_pagination("admin_smilies.$phpEx?mode=popup_test&amp;cat=$cat&amp;perp=$pop_per_page&amp;width=$width&amp;height=$height&amp;grolis=$smiley_group&amp;gcol=$group_columns&amp;=lcol=$list_column", $num_smilies, $per_page, $start, FALSE);

					$template->assign_vars(array(
						'S_WIDTH' =>  $width,
						'S_HEIGHT' =>  $height,
						'L_EMOTICONS' => $lang['Emoticons'],
						'L_CLOSE_WINDOW' => $lang['Close_window'],
						'S_PAGINATION' => $pagination,
						'S_SMILIES_COLSPAN' => $s_colspan)
					);
				}
			}
			
		break;
	}
}
else
{
	redirect(append_sid('admin_board.'.$phpEx.'?mode=smilies&amp;sid=' . $userdata['session_id']));
		}

	//
		// Spit out the page.
	//
		$template->pparse("body");

//
	// Page Footer
//
	include('../admin/page_footer_admin.'.$phpEx);
}
$message = $lang['Module_disabled'] . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.$phpEx?pane=right") . '">', '</a>');
message_die(GENERAL_MESSAGE, $message);

?>