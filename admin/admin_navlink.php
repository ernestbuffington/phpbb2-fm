<?php
/** 
 *
 * @package admin
 * @version $Id: admin_navlink.php,v 1.0.5 29/03/2007 12:26 PM mj Exp $
 * @copyright (c) 2003 MJ, Fully Modded phpBB
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License 
 *
*/

//
// First we do the setmodules stuff for the admin cp.
//
if( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	$module['General']['Navlink_title'] = $filename;

	return;
}

//
// Let's set the root dir for phpBB
//
define('IN_PHPBB', 1);
$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);

//
// Check to see what mode we should operate in.
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


// Restrict mode input to valid options
$mode = ( in_array($mode, array('add', 'edit', 'save', 'delete')) ) ? $mode : '';


//
// Read a listing of uploaded menu images for use in the add or edit menu link code...
//
$menu_imgs = array();
$style_dir = ( $theme['image_cfg'] ) ? $theme['template_name'] . '/images/' . $theme['image_cfg'] : $theme['template_name'] . '/images';
$dir = @opendir($phpbb_root_path . 'templates/' . $style_dir . '/menu/');
while($file = @readdir($dir))
{
	if( !@is_dir(phpbb_realpath($phpbb_root_path . 'templates/' . $style_dir . '/menu/' . $file)) )
	{
		$img_size = @getimagesize($phpbb_root_path . 'templates/' . $style_dir . '/menu/' . $file);

		if( $img_size[0] && $img_size[1] )
		{
			$menu_images[] = $file;
		}
	}
}
@closedir($dir);

//
// Select main mode
//
if( isset($HTTP_POST_VARS['add']) || isset($HTTP_GET_VARS['add']) )
{
	//
	// Admin has selected to add a navlink.
	//

	$template->set_filenames(array(
		"body" => "admin/navlink_edit_body.tpl")
	);

	$filename_list = "";
	for( $i = 0; $i < sizeof($menu_images); $i++ )
	{
		$filename_list .= '<option value="' . $menu_images[$i] . '">' . $menu_images[$i] . '</option>';
	}

	$s_hidden_fields = '<input type="hidden" name="mode" value="savenew" />';
	
	$template->assign_vars(array(
		"L_MENU_TITLE" => $lang['Navlink_add_title'],
		"L_MENU_EXPLAIN" => $lang['Navlink_add_explain'],
		"L_MENU_NAME" => $lang['Simple_name'],
		"L_MENU_LANG" => $lang['Use_lang'],
		"L_MENU_LANG_EXPLAIN" => $lang['Use_lang_explain'],
		"L_MENU_IMG" => $lang['Image'],
		"L_MENU_URL" => $lang['URL_Link'],
		"L_MENU_ACTIVE" => $lang['Active'],
		"L_ENABLED" => $lang['Enabled'],
		"L_DISABLED" => $lang['Disabled'],

		"MENU_IMG" => $phpbb_root_path . '/templates/' . $style_dir . '/menu/' . $menu_images[0], 
		"MENU_LANG_NO" => 'checked="checked"',
		"MENU_ON" => 'checked="checked"',

		"S_MENU_ACTION" => append_sid('admin_navlink.'.$phpEx), 
		"S_HIDDEN_FIELDS" => $s_hidden_fields, 
		"S_FILENAME_OPTIONS" => $filename_list, 
		"S_MENU_BASEDIR" => $phpbb_root_path . '/templates/' . $style_dir . '/menu/')
	);

	$template->pparse("body");
}
else if ( $mode != "" )
{
	switch( $mode )
	{
		case 'delete':
			//
			// Admin has selected to delete a menu link.
			//
			$navlink_id = ( !empty($HTTP_POST_VARS['id']) ) ? intval($HTTP_POST_VARS['id']) : intval($HTTP_GET_VARS['id']);

			$sql = "DELETE FROM " . CONFIG_NAV_TABLE . "
				WHERE navlink_id = " . $navlink_id;
			$result = $db->sql_query($sql);
			if( !$result )
			{
				message_die(GENERAL_ERROR, 'Could not delete navigation link.', '', __LINE__, __FILE__, $sql);
			}

			$message = $lang['Navlink_deleted'] . '<br /><br />' . sprintf($lang['Click_return_navlink'], '<a href="' . append_sid('admin_navlink.'.$phpEx) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.'.$phpEx.'?pane=right') . '">', '</a>');

			message_die(GENERAL_MESSAGE, $message);
			break;

		case 'edit':
			//
			// Admin has selected to edit a navlink.
			//
			$navlink_id = ( !empty($HTTP_POST_VARS['id']) ) ? intval($HTTP_POST_VARS['id']) : intval($HTTP_GET_VARS['id']);

			$sql = "SELECT *
				FROM " . CONFIG_NAV_TABLE . "
				WHERE navlink_id = " . $navlink_id;
			$result = $db->sql_query($sql);
			if( !$result )
			{
				message_die(GENERAL_ERROR, 'Could not obtain navigation link.', '', __LINE__, __FILE__, $sql);
			}
			$menu_data = $db->sql_fetchrow($result);

			$filename_list = '';
			for( $i = 0; $i < sizeof($menu_images); $i++ )
			{
				if( $menu_images[$i] == $menu_data['img'] )
				{
					$menu_selected = ' selected="selected"';
					$menu_img = $menu_images[$i];
				}
				else
				{
					$menu_selected = '';
				}

				$filename_list .= '<option value="' . $menu_images[$i] . '"' . $menu_selected . '>' . $menu_images[$i] . '</option>';
			}

			$template->set_filenames(array(
				"body" => "admin/navlink_edit_body.tpl")
			);

			$s_hidden_fields = '<input type="hidden" name="mode" value="save" /><input type="hidden" name="navlink_id" value="' . $menu_data['navlink_id'] . '" />';

			$template->assign_vars(array(
				"MENU_NAME" => $menu_data['alt'],
				"MENU_LANG_YES" => ( $menu_data['use_lang'] ) ? " checked=\"checked\"" : "",
				"MENU_LANG_NO" => ( !$menu_data['use_lang'] ) ? " checked=\"checked\"" : "",
				"MENU_URL" => $menu_data['url'],
				"MENU_ON" => ( $menu_data['value'] ) ? " checked=\"checked\"" : "",
				"MENU_OFF" => ( !$menu_data['value'] ) ? " checked=\"checked\"" : "",

				"L_MENU_TITLE" => $lang['Navlink_edit_title'],
				"L_MENU_EXPLAIN" => $lang['Navlink_edit_explain'],
				"L_MENU_NAME" => $lang['Simple_name'],
				"L_MENU_LANG" => $lang['Use_lang'],
				"L_MENU_LANG_EXPLAIN" => $lang['Use_lang_explain'],
				"L_MENU_IMG" => $lang['Image'],
				"L_MENU_URL" => $lang['URL_Link'],
				"L_MENU_ACTIVE" => $lang['Active'],
				"L_ENABLED" => $lang['Yes'],
				"L_DISABLED" => $lang['No'],

				"MENU_IMG" => $phpbb_root_path . 'templates/' . $style_dir . '/menu/' . $menu_img, 

				"S_MENU_ACTION" => append_sid('admin_navlink.'.$phpEx),
				"S_HIDDEN_FIELDS" => $s_hidden_fields, 
				"S_FILENAME_OPTIONS" => $filename_list, 
				"S_MENU_BASEDIR" => $phpbb_root_path . '/templates/' . $style_dir . '/menu/')
			);

			$template->pparse("body");
			break;

		case "save":
			//
			// Admin has submitted changes while editing a link.
			//
			// Get the submitted data, being careful to ensure that we only
			// accept the data we are looking for.
			//
			$navlink_id = ( isset($HTTP_POST_VARS['navlink_id']) ) ? intval($HTTP_POST_VARS['navlink_id']) : intval($HTTP_GET_VARS['navlink_id']);
			$menu_alt = ( isset($HTTP_POST_VARS['menu_alt']) ) ? $HTTP_POST_VARS['menu_alt'] : $HTTP_GET_VARS['manu_alt'];
			$menu_lang = ( isset($HTTP_POST_VARS['menu_lang']) ) ? $HTTP_POST_VARS['menu_lang'] : $HTTP_GET_VARS['menu_lang'];
			$menu_url = ( isset($HTTP_POST_VARS['menu_url']) ) ? $HTTP_POST_VARS['menu_url'] : $HTTP_GET_VARS['menu_url'];
			$menu_img = ( isset($HTTP_POST_VARS['menu_img']) ) ? $HTTP_POST_VARS['menu_img'] : $HTTP_GET_VARS['menu_img'];
			$menu_value = ( isset($HTTP_POST_VARS['menu_value']) ) ? $HTTP_POST_VARS['menu_value'] : $HTTP_GET_VARS['menu_value'];

			$menu_alt = trim($menu_alt);
			$menu_url = trim($menu_url);
			$menu_img = trim($menu_img);

			// If no code was entered complain ...
			if ($menu_url == '' || $menu_value == '')
			{
				message_die(GENERAL_MESSAGE, $lang['Fields_empty']);
			}

			//
			// Proceed with updating the navlink table.
			//
			$sql = "UPDATE " . CONFIG_NAV_TABLE . "
				SET img = '$menu_img', alt = '" . str_replace("\'", "''", $menu_alt) . "', use_lang = '$menu_lang', url = '" . str_replace("\'", "''", $menu_url) . "', value = '$menu_value'
				WHERE navlink_id = $navlink_id";
			if( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not update navigation link.', "", __LINE__, __FILE__, $sql);
			}

			$message = $lang['Navlink_edited'] . "<br /><br />" . sprintf($lang['Click_return_navlink'], "<a href=\"" . append_sid("admin_navlink.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

			message_die(GENERAL_MESSAGE, $message);
			break;

		case "savenew":
			//
			// Admin has submitted changes while adding a new navlink.
			//
			// Get the submitted data being careful to ensure the the data
			// we recieve and process is only the data we are looking for.
			//
			$menu_alt = ( isset($HTTP_POST_VARS['menu_alt']) ) ? $HTTP_POST_VARS['menu_alt'] : $HTTP_GET_VARS['manu_alt'];
			$menu_lang = ( isset($HTTP_POST_VARS['menu_lang']) ) ? $HTTP_POST_VARS['menu_lang'] : $HTTP_GET_VARS['menu_lang'];
			$menu_url = ( isset($HTTP_POST_VARS['menu_url']) ) ? $HTTP_POST_VARS['menu_url'] : $HTTP_GET_VARS['menu_url'];
			$menu_img = ( isset($HTTP_POST_VARS['menu_img']) ) ? $HTTP_POST_VARS['menu_img'] : $HTTP_GET_VARS['menu_img'];
			$menu_value = ( isset($HTTP_POST_VARS['menu_value']) ) ? $HTTP_POST_VARS['menu_value'] : $HTTP_GET_VARS['menu_value'];

			$menu_alt = trim($menu_alt);
			$menu_url = trim($menu_url);
			$menu_img = trim($menu_img);

			// If no code was entered complain ...
			if ($menu_url == '' || $menu_value == '')
			{
				message_die(GENERAL_MESSAGE, $lang['Fields_empty']);
			}

			$sql = "SELECT MAX(nav_order) AS max_order
				FROM " . CONFIG_NAV_TABLE;
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not get order number from config nav table.', '', __LINE__, __FILE__, $sql);
			}
			$row = $db->sql_fetchrow($result);

			$max_order = $row['max_order'];
			$next_order = $max_order + 10;

			//
			// Save the data to the navlink table.
			//
			$sql = "INSERT INTO " . CONFIG_NAV_TABLE . " (img, alt, use_lang, url, nav_order, value)
				VALUES ('$menu_img', '" . str_replace("\'", "''", $menu_alt) . "', '$menu_lang', '" . str_replace("\'", "''", $menu_url) . "', $next_order, '$menu_value')";
			$result = $db->sql_query($sql);
			if( !$result )
			{
				message_die(GENERAL_ERROR, 'Could not insert new navigation link.', '', __LINE__, __FILE__, $sql);
			}

			$message = $lang['Navlink_added'] . "<br /><br />" . sprintf($lang['Click_return_navlink'], "<a href=\"" . append_sid("admin_navlink.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

			message_die(GENERAL_MESSAGE, $message);
			break;
	}
}
else
{	
	// Admin has selected to move a navlink
	if ( $HTTP_GET_VARS['order'] == 'move' )
	{
		$move = intval($HTTP_GET_VARS['move']);
		$navlink_id = ( !empty($HTTP_POST_VARS['id']) ) ? intval($HTTP_POST_VARS['id']) : intval($HTTP_GET_VARS['id']);

		if( !empty($navlink_id) )
		{
			$sql = "UPDATE " . CONFIG_NAV_TABLE . "
				SET nav_order = nav_order + $move
				WHERE navlink_id = " . $navlink_id;
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not change navlinks order.', '', __LINE__, __FILE__, $sql);
			}
	
			$sql = "SELECT *
				FROM " . CONFIG_NAV_TABLE . "
				ORDER BY nav_order";
			if( !$result2 = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not query navlink order.', '', __LINE__, __FILE__, $sql);
			}
			
			$i = 10;
			
			while ( $row = $db->sql_fetchrow($result2) )
			{
				$sql = "UPDATE " . CONFIG_NAV_TABLE . "
					SET nav_order = $i
					WHERE navlink_id = " . $row['navlink_id'];
				if( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'Could not update order fields.', '', __LINE__, __FILE__, $sql);
				}
				$i += 10;
			}
			$db->sql_freeresult($result2);
		}	
		else
		{
			message_die(GENERAL_MESSAGE, 'No navlink_id.');
		}
	}
	
	// Admin has selected to de/activate a navlink.
	if ( $HTTP_GET_VARS['status'] == 'activate' )
	{
		$activate = intval($HTTP_GET_VARS['active']);
		$navlink_id = ( !empty($HTTP_POST_VARS['id']) ) ? intval($HTTP_POST_VARS['id']) : intval($HTTP_GET_VARS['id']);

		if( !empty($navlink_id) )
		{
			$sql = "UPDATE " . CONFIG_NAV_TABLE . "
				SET value = " . $activate . "
				WHERE navlink_id = $navlink_id";
			$result = $db->sql_query($sql);
			if( !$result )
			{
				message_die(GENERAL_ERROR, 'Could not update menu link status.', '', __LINE__, __FILE__, $sql);
			}
		}	
		else
		{
			message_die(GENERAL_MESSAGE, 'No navlink_id.');
		}
	}

	
	//
	// This is the main display of the page before the admin has selected
	// any options.
	//
	$sql = "SELECT *
		FROM " . CONFIG_NAV_TABLE . "
		ORDER BY nav_order";
	$result = $db->sql_query($sql);
	if( !$result )
	{
		message_die(GENERAL_ERROR, 'Could not obtain menu links from database.', '', __LINE__, __FILE__, $sql);
	}

	$menu = $db->sql_fetchrowset($result);

	$template->set_filenames(array(
		'body' => 'admin/navlink_config.tpl')
	);

	$template->assign_vars(array(
		"L_MENU_TITLE" => $lang['Navlink_title'] . ' ' . $lang['Setting'],
		"L_MENU_TEXT" => sprintf($lang['Config_explain'], $lang['Navlink_title']) . ' ' . $lang['Navlink_explain'],
		'L_MOVE_UP' => '<img src="' . $phpbb_root_path . $images['acp_up'] . '" alt="' . $lang['Move_up'] . '" title="' . $lang['Move_up'] . '" />',
		'L_MOVE_DOWN' => '<img src="' . $phpbb_root_path . $images['acp_down'] . '" alt="' . $lang['Move_down'] . '" title="' . $lang['Move_down'] . '" />',
		'L_EDIT' => '<img src="' . $phpbb_root_path . $images['acp_edit'] . '" alt="' . $lang['Edit'] . '" title="' . $lang['Edit'] . '" />',
		'L_DELETE' => '<img src="' . $phpbb_root_path . $images['acp_delete'] . '" alt="' . $lang['Delete'] . '" title="' . $lang['Delete'] . '" />',
		
		"L_MENU_ADD" => $lang['Add_new'],
		"L_NAME" => $lang['Simple_name'],
		"L_URL" => $lang['URL_Link'],
		
		"S_HIDDEN_FIELDS" => $s_hidden_fields, 
		"S_MENU_ACTION" => append_sid("admin_navlink.$phpEx"))
	);

	//
	// Loop throuh the rows of links setting block vars for the template.
	//
	for($i = 0; $i < sizeof($menu); $i++)
	{		
		$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

		// Make a language string ...
		$lang_array = split('\.', $menu[$i]['url']);
		$use_lang = $lang['' . ucwords(strtolower($lang_array[0])) . ''];

		$menu_img = $phpbb_root_path . 'templates/' . $style_dir . '/menu/' . $menu[$i]['img'];
		if( !file_exists($menu_img) ) 
		{ 
			$menu_img = $phpbb_root_path . 'images/spacer.gif'; 
		} 
				
		$template->assign_block_vars('menu', array(
			"ROW_CLASS" => $row_class,
			"L_STATUS" => (!empty($menu[$i]['value'])) ? '<img src="' . $phpbb_root_path . $images['acp_disable'] . '" alt="' . $lang['Disable'] . '" title="' . $lang['Disable'] . '" />' : '<img src="' . $phpbb_root_path . $images['acp_enable'] . '" alt="' . $lang['Enable'] . '" title="' . $lang['Enable'] . '" />',
		
			"MENU_IMG" => $menu_img, 
			"MENU_ALT" => ( $menu[$i]['use_lang'] ) ? ( ($use_lang) ? $use_lang : $lang['No_lang_string'] ) : $menu[$i]['alt'],
			"NAME" => ( $menu[$i]['use_lang'] ) ? ( ($use_lang) ? $use_lang : '<span style="color: #FF0000">' . $lang['No_lang_string'] . '</span>' ) : $menu[$i]['alt'],
			"URL" => $menu[$i]['url'],
			
			'U_MOVE_UP' => append_sid("admin_navlink.$phpEx?order=move&amp;move=-15&amp;id=" . $menu[$i]['navlink_id']),
			'U_MOVE_DOWN' => append_sid("admin_navlink.$phpEx?order=move&amp;move=15&amp;id=" . $menu[$i]['navlink_id']),
			"U_MENU_EDIT" => append_sid("admin_navlink.$phpEx?mode=edit&amp;id=" . $menu[$i]['navlink_id']), 
			"U_MENU_DELETE" => append_sid("admin_navlink.$phpEx?mode=delete&amp;id=" . $menu[$i]['navlink_id']),
			"U_MENU_STATUS" => append_sid("admin_navlink.$phpEx?status=activate&amp;active=" . (( $menu[$i]['value'] ) ? 0 : 1) . "&amp;id=" . $menu[$i]['navlink_id']))
		);
	}

	//
	// Spit out the page.
	//
	$template->pparse("body");
}

//
// Page Footer
//
include('./page_footer_admin.'.$phpEx);

?>