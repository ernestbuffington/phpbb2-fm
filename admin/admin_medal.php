<?php
/** 
*
* @package admin
* @version $Id: admin_medal.php,v 1.40.2.10 2003/01/05 02:36:00 ycl6 Exp $
* @copyright (c) 2003 ycl6
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
	$file = basename(__FILE__);
	$module['Medals']['Manage'] = "$file";
	return;
}

//
// Let's set the root dir for phpBB
//
$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);

//
// Read a listing of uploaded medal images for use in the add or edit medal code...
//
$medal_icons = array();
$dir = @opendir($phpbb_root_path . 'images/medals/');
while($file = @readdir($dir))
{
	if( !@is_dir(phpbb_realpath($phpbb_root_path . '/images/medals/' . $file)) )
	{
		$img_size = @getimagesize($phpbb_root_path . 'images/medals/' . $file);

		if( $img_size[0] && $img_size[1] )
		{
			$medal_icons[] = $file;
		}
	}
}
@closedir($dir);


//
// Mode setting
//
if( isset($HTTP_GET_VARS['mode']) || isset($HTTP_POST_VARS['mode']) )
{
	$mode = ( isset($HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
	$mode = htmlspecialchars($mode); 
}
else 
{
	//
	// These could be entered via a form button
	//
	if( isset($HTTP_POST_VARS['addmedal']) )
	{
		$mode = "addmedal";
	}
	else if( isset($HTTP_POST_VARS['addcat']) )
	{
		$mode = "addcat";
	}
	else if( isset($HTTP_POST_VARS['submit']) )
	{
		$mode = "submit";
	}
	else
	{
		$mode = "";
	}
}

// ------------------
// Begin function block
//
function get_medal_info($mode, $id)
{
	global $db;

	switch($mode)
	{
		case 'category':
			$table = MEDAL_CAT_TABLE;
			$idfield = 'cat_id';
			$namefield = 'cat_title';
			break;

		case 'medal':
			$table = MEDAL_TABLE;
			$idfield = 'medal_id';
			$namefield = 'medal_name';
			break;

		default:
			message_die(GENERAL_ERROR, "Wrong mode for generating select list", "", __LINE__, __FILE__);
			break;
	}
	
	$sql = "SELECT count(*) as total
		FROM $table";
	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, "Couldn't get Medal(s) / Medal Category information", "", __LINE__, __FILE__, $sql);
	}
	$count = $db->sql_fetchrow($result);
	$count = $count['total'];

	$sql = "SELECT *
		FROM $table
		WHERE $idfield = $id"; 
	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, "Couldn't get Medal(s) / Medal Category information", "", __LINE__, __FILE__, $sql);
	}

	if( $db->sql_numrows($result) != 1 )
	{
		message_die(GENERAL_ERROR, "Medal(s) / Medal Category doesn't exist or multiple Medal(s) / Medal categories with ID $id", "", __LINE__, __FILE__);
	}

	$return = $db->sql_fetchrow($result);
	$return['number'] = $count;
	
	return $return;
}

function get_medal_list($mode, $id, $select)
{
	global $db;

	switch($mode)
	{
		case 'category':
			$table = MEDAL_CAT_TABLE;
			$idfield = 'cat_id';
			$namefield = 'cat_title';
			break;

		default:
			message_die(GENERAL_ERROR, "Wrong mode for generating select list", "", __LINE__, __FILE__);
			break;
	}

	$sql = "SELECT * FROM $table";
	if( $select == TRUE )
	{
		$sql .= " WHERE $idfield <> $id";
	}
	else
	{
		$sql .= " ORDER BY $idfield ASC";
	}

	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, "Couldn't get list of Medal Categories/Medals", "", __LINE__, __FILE__, $sql);
	}

	$cat_list = "";

	while( $row = $db->sql_fetchrow($result) )
	{
		$s = "";
		if ($row[$idfield] == $id)
		{
			$s = " selected=\"selected\"";
		}
		$catlist .= "<option value=\"$row[$idfield]\"$s>" . $row[$namefield] . "</option>\n";
	}

	return($catlist);
}

function renumber_medal_order($mode, $cat = 0)
{
	global $db;

	switch($mode)
	{
		case 'category':
			$table = MEDAL_CAT_TABLE;
			$idfield = 'cat_id';
			$orderfield = 'cat_order';
			$cat = 0;
			break;

		default:
			message_die(GENERAL_ERROR, "Wrong mode for generating select list", "", __LINE__, __FILE__);
			break;
	}

	$sql = "SELECT * FROM $table";
	if( !empty($cat) )
	{
		$sql .= " WHERE $catfield = $cat";
	}
	$sql .= " ORDER BY $orderfield ASC";


	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, "Couldn't get list of Categories", "", __LINE__, __FILE__, $sql);
	}

	$i = 10;
	$inc = 10;

	while( $row = $db->sql_fetchrow($result) )
	{
		$sql = "UPDATE $table
			SET $orderfield = $i
			WHERE $idfield = " . $row[$idfield];
		if( !$db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, "Couldn't update order fields", "", __LINE__, __FILE__, $sql);
		}
		$i += 10;
	}

}
//
// End function block
// ------------------

if( !empty($mode) ) 
{
	if( isset($HTTP_POST_VARS[POST_MEDAL_URL]) || isset($HTTP_GET_VARS[POST_MEDAL_URL]) )
	{
		$medal_id = ( isset($HTTP_POST_VARS[POST_MEDAL_URL]) ) ? intval($HTTP_POST_VARS[POST_MEDAL_URL]) : intval($HTTP_GET_VARS[POST_MEDAL_URL]);
	}
	else 
	{ 
		$medal_id = 0; 
	}

	if( isset($HTTP_POST_VARS[MEDAL_CAT_URL]) || isset($HTTP_GET_VARS[MEDAL_CAT_URL]) )
	{
		$cat_id = ( isset($HTTP_POST_VARS[MEDAL_CAT_URL]) ) ? intval($HTTP_POST_VARS[MEDAL_CAT_URL]) : intval($HTTP_GET_VARS[MEDAL_CAT_URL]);
	}
	else 
	{ 
		$cat_id = 0; 
	}

	switch($mode)
	{
		case 'addmedal':
		case 'editmedal':
			//
			// Show form to create/modify a medal
			//
			if( $mode == "editmedal" )
			{
				$s_hidden_fields = '';

				if( empty($medal_id) )
				{
					message_die(GENERAL_MESSAGE, $lang['Must_select_medal']);
				}

				$row = get_medal_info('medal', $medal_id);
				$cat_id = $row['cat_id'];
				$medal_name = $row['medal_name'];
				$medal_description = $row['medal_description'];
				$medal_image = $row['medal_image'];
				
				$s_hidden_fields .= '<input type="hidden" name="' . POST_MEDAL_URL . '" value="' . $medal_id . '" />';
			}
			else
			{
				$medal_name = ( isset($HTTP_POST_VARS['name']) ) ? trim($HTTP_POST_VARS['name']) : '';
				$medal_description = '';
				$medal_image = 'none.gif';
			}

			$catlist = get_medal_list('category', $cat_id, FALSE);

			$medal_list = '';
			for( $i = 0; $i < sizeof($medal_icons); $i++ )
			{
				if( $medal_icons[$i] == $medal_image)
				{
					$medal_selected = ' selected="selected"';
					$medal_img = $medal_icons[$i];
				}
				else
				{
					$medal_selected = '';
				}
				$medal_list .= '<option value="' . $medal_icons[$i] . '"' . $medal_selected . '>' . $medal_icons[$i] . '</option>';
			}

			$template->set_filenames(array(
				"body" => "admin/medals_edit_body.tpl")
			);
			
			$s_hidden_fields .= '<input type="hidden" name="mode" value="savemedal" />';

			$template->assign_vars(array(
				"MEDAL_NAME" => $medal_name,
				"MEDAL_DESCRIPTION" => $medal_description,
				"IMAGE" => ( $medal_image ) ? $medal_image : "",

				"L_NEW_MEDAL" => ( $medal_name ) ? $medal_name : $lang['New_medal'],
				"L_MEDAL_TITLE" => $lang['Medal_admin'],
				"L_MEDAL_EXPLAIN" => $lang['Medal_admin_explain'],
				"L_MEDAL_NAME" => $lang['medal_name'],
				"L_CATEGORY" => $lang['Category'],
				"L_MEDAL_DESCRIPTION" => $lang['medal_description'],
				"L_MEDAL_IMAGE" => $lang['medal_image'],
				"L_MEDAL_IMAGE_EXPLAIN" => $lang['medal_image_explain'],
	
				'MEDAL_ICON' => $phpbb_root_path . 'images/medals/' . $medal_img, 
				'S_FILENAME_OPTIONS' => $medal_list, 
				'S_ICON_BASEDIR' => $phpbb_root_path . 'images/medals/',
		
				"S_MEDAL_ACTION" => append_sid("admin_medal.$phpEx"),
				"S_CAT_LIST" => $catlist,
				"S_HIDDEN_FIELDS" => $s_hidden_fields)
			);
			$template->pparse("body");
			break;

		case 'savemedal':
			//
			// save new or editted medal setting
			//
			$medal_name = ( isset($HTTP_POST_VARS['medal_name']) ) ? trim($HTTP_POST_VARS['medal_name']) : "";
			$medal_description = ( isset($HTTP_POST_VARS['medal_description']) ) ? trim($HTTP_POST_VARS['medal_description']) : "";
			$medal_image = ( (isset($HTTP_POST_VARS['medal_image'])) ) ? trim($HTTP_POST_VARS['medal_image']) : "";

			if ( $medal_name == '' ) 
			{ 
				message_die(GENERAL_MESSAGE, $lang['No_medal_name']); 
			}

			if ( $medal_description == '' ) 
			{ 
				message_die(GENERAL_MESSAGE, $lang['No_medal_description']); 
			}

			if ( $medal_image == '' ) 
			{ 
				message_die(GENERAL_MESSAGE, $lang['No_medal_image']); 
			}

			if( $medal_image != '' ) 
			{
				if ( !preg_match("/(\.gif|\.png|\.jpg)$/is", $medal_image)) 
				{
				       $medal_image = ''; 
				}
			}

			if ($medal_id)
			{			
				$sql = "UPDATE " . MEDAL_TABLE . "
					SET medal_name = '" . str_replace("\'", "''", $medal_name) . "', cat_id = $cat_id, medal_description = '" . str_replace("\'", "''", $medal_description) . "', medal_image = '" . str_replace("\'", "''", $medal_image) . "'
					WHERE medal_id = $medal_id";

				$message = $lang['Updated_medal'];
			}
			else
			{
				$sql = "INSERT INTO " . MEDAL_TABLE . " (medal_name, cat_id, medal_description, medal_image)
					VALUES ('" . str_replace("\'", "''", $medal_name) . "', $cat_id, '" . str_replace("\'", "''", $medal_description) . "', '" . str_replace("\'", "''", $medal_image) . "')";

				$message = $lang['Added_new_medal'];
			}
		
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't update/insert into medal table", "", __LINE__, __FILE__, $sql);
			}

			$message .= "<br /><br />" . sprintf($lang['Click_return_medaladmin'], "<a href=\"" . append_sid("admin_medal.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

			message_die(GENERAL_MESSAGE, $message);

			break;

		case 'delete':
			//
			// delate medal
			//
			if( $medal_id )
			{
				$sql = "DELETE FROM " . MEDAL_TABLE . "
					WHERE medal_id = $medal_id";
			
				if( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't delete medal data", "", __LINE__, __FILE__, $sql);
				}

				$message = $lang['Deleted_medal'] . "<br /><br />" . sprintf($lang['Click_return_medaladmin'], "<a href=\"" . append_sid("admin_medal.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

				message_die(GENERAL_MESSAGE, $message);

			}
			else
			{
				message_die(GENERAL_MESSAGE, $lang['Must_select_medal']);
			}

			break;

		case 'addcat':
			//
			// Create a medal category in the DB
			//
			$categoryname = ( isset($HTTP_POST_VARS['name']) ) ? trim($HTTP_POST_VARS['name']) : "";
			if( $categoryname == '') 
			{ 
				message_die(GENERAL_ERROR, "Can't create a medal category without a name"); 
			}

			$sql = "SELECT MAX(cat_order) AS max_order
				FROM " . MEDAL_CAT_TABLE;
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't get order number from medal categories table", "", __LINE__, __FILE__, $sql);
			}
			$row = $db->sql_fetchrow($result);

			$max_order = $row['max_order'];
			$next_order = $max_order + 10;

			//
			// There is no problem having duplicate medal names so we won't check for it.
			//
			$sql = "INSERT INTO " . MEDAL_CAT_TABLE . " (cat_title, cat_order)
				VALUES ('" . str_replace("\'", "''", $categoryname) . "', $next_order)";
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't insert row in medal categories table", "", __LINE__, __FILE__, $sql);
			}

			$message = $lang['Added_new_category'] . "<br /><br />" . sprintf($lang['Click_return_medaladmin'], "<a href=\"" . append_sid("admin_medal.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

			message_die(GENERAL_MESSAGE, $message);

			break;

		case 'editcat':
			//
			// Show form to modify a medal category
			//
			$s_hidden_fields = "";

			if( empty($cat_id) ) 
			{ 
				message_die(GENERAL_MESSAGE, $lang['Must_select_medalcat']); 
			}

			$sql = "SELECT * FROM " . MEDAL_CAT_TABLE . "
				WHERE cat_id = $cat_id";
			if(!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, "Couldn't obtain medal category data", "", __LINE__, __FILE__, $sql);
			}
			
			$medalcat_info = $db->sql_fetchrow($result);

			$s_hidden_fields .= '<input type="hidden" name="' . MEDAL_CAT_URL . '" value="' . $cat_id . '" />';
			$s_hidden_fields .= '<input type="hidden" name="mode" value="savecat" />';

			$template->set_filenames(array(
				"body" => "admin/medals_editcat_body.tpl")
			);

			$template->assign_vars(array(
				"MEDAL_CAT_TITLE" => $medalcat_info['cat_title'],

				'L_EDIT_CATEGORY' => $lang['Edit_Category'],
				"L_EDIT_CATEGORY_EXPLAIN" => $lang['Edit_Category_explain'],
				"L_CATEGORY" => $lang['Category'],
			
				"S_MEDAL_ACTION" => append_sid("admin_medal.$phpEx"),
				"S_SUBMIT_VALUE" => $lang['Update'],
				"S_HIDDEN_FIELDS" => $s_hidden_fields)
			);
			$template->pparse("body");
			break;

		case 'savecat':
			//
			// Modify a medal category in the DB
			//
			$sql = "UPDATE " . MEDAL_CAT_TABLE . "
				SET cat_title = '" . str_replace("\'", "''", $HTTP_POST_VARS['cat_title']) . "'
				WHERE cat_id = " . $cat_id;
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't update medal category information", "", __LINE__, __FILE__, $sql);
			}

			$message = $lang['Updated_medal_category'] . "<br /><br />" . sprintf($lang['Click_return_medaladmin'], "<a href=\"" . append_sid("admin_medal.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

			message_die(GENERAL_MESSAGE, $message);

			break;

		case 'deletecat':
			//
			// Show form to delete a medal category
			//
			$catinfo = get_medal_info('category', $cat_id);
			$delname = $catinfo['cat_title'];

			if ($catinfo['number'] == 1)
			{
				$sql = "SELECT count(*) as total
					FROM ". MEDAL_TABLE;
				if( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't get medal count", "", __LINE__, __FILE__, $sql);
				}
				$count = $db->sql_fetchrow($result);
				$count = $count['total'];

				if ($count > 0)
				{
					message_die(GENERAL_ERROR, $lang['Must_delete_medal']);
				}
				else
				{
					$select_to = $lang['Nowhere_to_move'];
				}
			}
			else
			{
				$select_to = '<select name="to_id">';
				$select_to .= get_medal_list('category', $cat_id, TRUE);
				$select_to .= '</select>';
			}

			$template->set_filenames(array(
				"body" => "admin/medals_delete_body.tpl")
			);

			$s_hidden_fields = '<input type="hidden" name="mode" value="movedelcat" /><input type="hidden" name="from_id" value="' . $cat_id . '" />';

			$template->assign_vars(array(
				'NAME' => $delname, 

				'L_CATEGORY_DELETE' => $lang['Category_delete'], 
				'L_CATEGORY_DELETE_EXPLAIN' => $lang['Category_delete_explain'], 
				'L_MOVE_MEDALS' => $lang['Move_medals'], 
				'L_CATEGORY_NAME' => $lang['Category_name'], 
				
				'S_HIDDEN_FIELDS' => $s_hidden_fields,
				'S_MEDAL_ACTION' => append_sid("admin_medal.$phpEx"), 
				'S_SELECT_TO' => $select_to,
				'S_SUBMIT_VALUE' => $lang['Move_and_Delete'])
			);
			$template->pparse("body");
			break;

		case 'movedelcat':
			//
			// Move or delete a medal category in the DB
			//
			$from_id = intval($HTTP_POST_VARS['from_id']);
			$to_id = intval($HTTP_POST_VARS['to_id']);

			if (!empty($to_id))
			{
				$sql = "SELECT *
					FROM " . MEDAL_CAT_TABLE . "
					WHERE cat_id IN ($from_id, $to_id)";
				if( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't verify existence of categories", "", __LINE__, __FILE__, $sql);
				}
				if($db->sql_numrows($result) != 2)
				{
					message_die(GENERAL_ERROR, "Ambiguous category ID", "", __LINE__, __FILE__);
				}

				$sql = "UPDATE " . MEDAL_TABLE . "
					SET cat_id = $to_id
					WHERE cat_id = $from_id";
				if( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't move medals to other medal category", "", __LINE__, __FILE__, $sql);
				}
			}

			$sql = "DELETE FROM " . MEDAL_CAT_TABLE ."
				WHERE cat_id = $from_id";
				
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't delete medal category", "", __LINE__, __FILE__, $sql);
			}

			$message = $lang['Deleted_medal_category'] . "<br /><br />" . sprintf($lang['Click_return_medaladmin'], "<a href=\"" . append_sid("admin_medal.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

			message_die(GENERAL_MESSAGE, $message);

			break;

		case 'cat_order':
			//
			// Change order of categories in the DB
			//
			$move = intval($HTTP_GET_VARS['move']);

			$sql = "UPDATE " . MEDAL_CAT_TABLE . "
				SET cat_order = cat_order + $move
				WHERE cat_id = $cat_id";
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't change medal category order", "", __LINE__, __FILE__, $sql);
			}

			renumber_medal_order('category');
			$show_index = TRUE;
			break;

		case 'moderator':
			//
			// medal moderator settings
			//
			$s_hidden_fields = "";

			if( empty($medal_id) ) 
			{ 
				message_die(GENERAL_MESSAGE, $lang['Must_select_medal']); 
			}

			$s_hidden_fields .= '<input type="hidden" name="' . POST_MEDAL_URL . '" value="' . $medal_id . '" />';
			$row = get_medal_info('medal', $medal_id);

			$sql = "SELECT m.mod_id, u.user_id, u.username
				FROM " . MEDAL_MOD_TABLE . " m, " . USERS_TABLE . " u
				WHERE u.user_id = m.user_id
					AND m.medal_id = $medal_id
					AND m.user_id <> 0
					AND u.user_id <> " . ANONYMOUS . "
				ORDER BY u.user_id ASC";
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not select current user_id medal moderator list', '', __LINE__, __FILE__, $sql);
			}

			$user_list = array();
			$user_list = $db->sql_fetchrowset($result);
			$db->sql_freeresult($result);
		
			$userlist = '';
			$select_userlist = '';
			for($i = 0; $i < count($user_list); $i++)
			{
				$select_userlist .= '<option value="' . $user_list[$i]['mod_id'] . '">' . $user_list[$i]['username'] . '</option>';
				$userlist .= ( $userlist != '' ) ? ', ' . '<a href="' . append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=" . $user_list[$i]['user_id']) . '">' . $user_list[$i]['username'] . '</a>' : '<a href="' . append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=" . $user_list[$i]['user_id']) . '">' . $user_list[$i]['username'] . '</a>';
			}

			$userlist = ( $userlist ) ? $userlist : $lang['No_medal_mod'];

			if( $select_userlist == '' ) 
			{ 
				$select_userlist = '<option value="-1">' . $lang['No_medal_mod'] . '</option>'; 
			}

			$select_userlist = '<select name="unmod_user[]" multiple="multiple" size="5" style="width: 250px;">' . $select_userlist . '</select>';
	
			$s_hidden_fields .= '<input type="hidden" name="mode" value="submit" />';

			$template->set_filenames(array(
				"body" => "admin/medals_moderator_body.tpl")
			);

			$template->assign_vars(array(
				"MEDAL_NAME" => $row['medal_name'],
				"MEDAL_DESCRIPTION" => $row['medal_description'],
				"MEDAL_MODERATORS" => $userlist,
			
				"L_MEDAL_DESCRIPTION" => $lang['medal_description'],
				"L_MEDAL_MOD_TITLE" => $lang['Medal_mod_admin'],
				"L_MEDAL_MOD_EXPLAIN" => $lang['Medal_mod_admin_explain'],
				"L_MEDAL_NAME" => $lang['medal_name'],
				"L_MEDAL_DESCRIPTION" => $lang['medal_description'],
				"L_MEDAL_MOD" => $lang['Medal_mod'],
				"L_MOD_USER" => $lang['Medal_mod_username'],
				"L_UNMOD_USER" => $lang['Medal_unmod_username'],
				"L_UNMOD_USER_EXPLAIN" => $lang['Medal_unmod_username_explain'],
				"L_USERNAME" => $lang['Username'], 
				"L_LOOK_UP" => $lang['Look_up_User'],
				"L_FIND_USERNAME" => $lang['Find_username'],
				'L_SUBMIT' => $lang['Submit'],
				'L_RESET' => $lang['Reset'],
				'U_SEARCH_USER' => append_sid("./../search.$phpEx?mode=searchuser"),
		
				"S_UNMOD_USERLIST_SELECT" => $select_userlist,
				"S_MEDAL_ACTION" => append_sid("admin_medal.$phpEx"),
				"S_HIDDEN_FIELDS" => $s_hidden_fields)
			);
			$template->pparse("body");
			break;

		case 'submit':
			//
			// save medal moderator settings
			//
			$user_modsql = '';

			$user_list = array();
			if ( !empty($HTTP_POST_VARS['username']) )
			{
				$this_userdata = get_userdata(phpbb_clean_username($HTTP_POST_VARS['username']));
				
				if( !$this_userdata ) 
				{ 
					message_die(GENERAL_MESSAGE, $lang['No_user_id_specified'] ); 
				}

				$user_list[] = $this_userdata['user_id'];
			}
	
			$sql = "SELECT * FROM " . MEDAL_MOD_TABLE . "
				WHERE medal_id = $medal_id";

			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, "Couldn't obtain medal moderator list information", "", __LINE__, __FILE__, $sql);
			}

			$current_modlist = $db->sql_fetchrowset($result);
			$db->sql_freeresult($result);
	
			for($i = 0; $i < count($user_list); $i++)
			{
				$in_modlist = false;

				for($j = 0; $j < count($current_modlist); $j++)
				{
					if ( $user_list[$i] == $current_modlist[$j]['user_id'] ) 
					{ 
						$in_modlist = true; 
					}
				}

				if ( !$in_modlist )
				{
					$sql = "INSERT INTO " . MEDAL_MOD_TABLE . " (medal_id, user_id)
						VALUES (" . $medal_id . ", " . $user_list[$i] . ")";
					if ( !$db->sql_query($sql) )
					{
						message_die(GENERAL_ERROR, "Couldn't insert user_id info into database", "", __LINE__, __FILE__, $sql);
					}
				}
			}

			$where_sql = '';

			$user_list = array();
			if ( isset($HTTP_POST_VARS['unmod_user']) )
			{
				$user_list = $HTTP_POST_VARS['unmod_user'];

				for($i = 0; $i < count($user_list); $i++)
				{
					if ( $user_list[$i] != -1 ) 
					{ 
						$where_sql .= ( ( $where_sql != '' ) ? ', ' : '' ) . intval($user_list[$i]); 
					}
				}
			}

			if ( $where_sql != '' )
			{
				$sql = "DELETE FROM " . MEDAL_MOD_TABLE . "
					WHERE mod_id IN ($where_sql)";
				if ( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't delete medal moderator info from database", "", __LINE__, __FILE__, $sql);
				}
			}

			$message = $lang['Medal_mod_update_sucessful'] . '<br /><br />' . sprintf($lang['Click_return_medal_mod_admin'], '<a href="' . append_sid("admin_medal.$phpEx?mode=moderator&" . POST_MEDAL_URL . "=$medal_id") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_medaladmin'], '<a href="' . append_sid("admin_medal.$phpEx") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.$phpEx?pane=right") . '">', '</a>');

			message_die(GENERAL_MESSAGE, $message);
			break;

		default:
			message_die(GENERAL_MESSAGE, $lang['No_mode']);
			break;
	}
	if ($show_index != TRUE)
	{
		include('./page_footer_admin.'.$phpEx);
		exit;
	}
}

//
// Show the default page
//
$template->set_filenames(array(
	"body" => "admin/medals_list_body.tpl")
);
	
$template->assign_vars(array(
	"L_MEDAL_TITLE" => $lang['Medal_admin'],
	"L_MEDAL_EXPLAIN" => $lang['Medal_admin_explain'],
	"L_MEDAL_NAME" => $lang['medal_name'],
	"L_MEDAL_DESCRIPTION" => $lang['medal_description'],
	"L_MEDAL_IMAGE" => $lang['medal_image'],
	"L_MEDAL_MOD" => $lang['Medal_mod'],
	'L_MOVE_UP' => '<img src="' . $phpbb_root_path . $images['acp_up'] . '" alt="' . $lang['Move_up'] . '" title="' . $lang['Move_up'] . '" />',
	'L_MOVE_DOWN' => '<img src="' . $phpbb_root_path . $images['acp_down'] . '" alt="' . $lang['Move_down'] . '" title="' . $lang['Move_down'] . '" />',
	'L_EDIT' => '<img src="' . $phpbb_root_path . $images['acp_edit'] . '" alt="' . $lang['Edit'] . '" title="' . $lang['Edit'] . '" />',
	'L_DELETE' => '<img src="' . $phpbb_root_path . $images['acp_delete'] . '" alt="' . $lang['Delete'] . '" title="' . $lang['Delete'] . '" />',
	"L_CREATE_NEW_MEDAL" => $lang['New_medal'],
	"L_CREATE_NEW_MEDAL_CAT" => $lang['Create_category'],
	
	"S_MEDAL_ACTION" => append_sid("admin_medal.$phpEx"))
);

$sql = "SELECT cat_id, cat_title, cat_order
	FROM " . MEDAL_CAT_TABLE . "
	ORDER BY cat_order";
	
if( !$q_categories = $db->sql_query($sql) )
{
	message_die(GENERAL_ERROR, "Could not query medal categories list", "", __LINE__, __FILE__, $sql);
}

$category_rows = array();
if( $total_categories = $db->sql_numrows($q_categories) )
{
	$category_rows = $db->sql_fetchrowset($q_categories);

	$sql = "SELECT *
		FROM " . MEDAL_TABLE . "
		ORDER BY cat_id, medal_name";
	if(!$q_medals = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Could not query medal information", "", __LINE__, __FILE__, $sql);
	}

	$medal_rows = array();	
	if( $medal_count = $db->sql_numrows($q_medals) )
	{
		$medal_rows = $db->sql_fetchrowset($q_medals);
	}

	//
	// Okay, let's build the index
	//
	for($i = 0; $i < $total_categories; $i++)
	{
		$cat_id = $category_rows[$i]['cat_id'];

		$template->assign_block_vars("catrow", array( 
			'CAT_ID' => $cat_id,
			'CAT_DESC' => $category_rows[$i]['cat_title'],
			'U_CAT_EDIT' => append_sid("admin_medal.$phpEx?mode=editcat&amp;" . MEDAL_CAT_URL . "=$cat_id"),
			'U_CAT_DELETE' => append_sid("admin_medal.$phpEx?mode=deletecat&amp;" . MEDAL_CAT_URL . "=$cat_id"),
			'U_CAT_MOVE_UP' => append_sid("admin_medal.$phpEx?mode=cat_order&amp;move=-15&amp;" . MEDAL_CAT_URL . "=$cat_id"),
			'U_CAT_MOVE_DOWN' => append_sid("admin_medal.$phpEx?mode=cat_order&amp;move=15&amp;" . MEDAL_CAT_URL . "=$cat_id"))
		);

		for($j = 0; $j < $medal_count; $j++)
		{
			$medal_id = $medal_rows[$j]['medal_id'];

			if ($medal_rows[$j]['cat_id'] == $cat_id)
			{
				$template->assign_block_vars("catrow.medals", array(
					"MEDAL_NAME" => $medal_rows[$j]['medal_name'],
					"MEDAL_DESCRIPTION" => $medal_rows[$j]['medal_description'],
					"MEDAL_IMAGE" => ( $medal_rows[$j]['medal_image'] ) ? '<img src="' . $phpbb_root_path . 'images/medals/' . $medal_rows[$j]['medal_image'] . '" />' : '',
			
					"U_MEDAL_MOD" => append_sid("admin_medal.$phpEx?mode=moderator&amp;" . POST_MEDAL_URL . "=$medal_id"),
					"U_MEDAL_EDIT" => append_sid("admin_medal.$phpEx?mode=editmedal&amp;" . POST_MEDAL_URL . "=$medal_id"),
					"U_MEDAL_DELETE" => append_sid("admin_medal.$phpEx?mode=delete&amp;" . POST_MEDAL_URL . "=$medal_id"))
				);
				
				$nomedals = 1;
	
			} // if ... medalid == catid
			else $nomedals = 0;

		} // for ... medals

	} // for ... categories
//	if ( $nomedals == 0 )
//	{
//		$template->assign_block_vars("catrow.nomedals", array(
//			"ROW_COLOR" => $theme['td_color1'],
//			"ROW_CLASS" => $theme['td_class1'],
//			"L_NO_MEDAL_IN_CAT" => $lang['No_medal_in_cat'])
//		);
//	} // if ... $nomedals
} // if ... total_categories

$template->pparse("body");

include('./page_footer_admin.'.$phpEx);

?>
