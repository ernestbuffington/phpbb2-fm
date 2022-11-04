<?php
/** 
*
* @package admin
* @version $Id: admin_styles.php,v 1.27.2.9 2002/12/21 19:09:57 psotfx Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
	$file = basename(__FILE__);
	$module['Styles']['Add_new'] = "$file?mode=addnew";
	$module['Styles']['Create_new'] = "$file?mode=create";
	$module['Styles']['Manage'] = $file;
	$module['Styles']['Export'] = "$file?mode=export";
	return;
}

//
// Load default header
//
//
// Check if the user has cancled a confirmation message.
//
$phpbb_root_path = "./../"; 
require($phpbb_root_path . 'extension.inc'); 

$confirm = (isset($HTTP_POST_VARS['confirm']) || isset($_POST['confirm'])) ? TRUE : FALSE;
$cancel = ( isset($HTTP_POST_VARS['cancel']) || isset($_POST['cancel']) ) ? true : false;

$no_page_header = (!empty($HTTP_POST_VARS['send_file']) || !empty($_POST['send_file']) || $cancel) ? TRUE : FALSE;

require('./pagestart.' . $phpEx); 

$confirm = (isset($HTTP_POST_VARS['confirm']) || isset($_POST['confirm'])) ? TRUE : FALSE;
$cancel = ( isset($HTTP_POST_VARS['cancel']) || isset($_POST['cancel']) ) ? true : false;

if ($cancel) 
{ 
	redirect('admin/' . append_sid("admin_styles_users.$phpEx", true)); 
} 

if( isset($HTTP_GET_VARS['mode']) || isset($HTTP_POST_VARS['mode']) )
{
	$mode = ( isset($HTTP_GET_VARS['mode']) ) ? $HTTP_GET_VARS['mode'] : $HTTP_POST_VARS['mode'];
	$mode = htmlspecialchars($mode); 
}
else 
{
	$mode = "";
}

switch( $mode )
{
	case "addnew":
	default:
		$install_to = ( isset($HTTP_GET_VARS['install_to']) ) ? urldecode($HTTP_GET_VARS['install_to']) : $HTTP_POST_VARS['install_to'];
		$style_name = ( isset($HTTP_GET_VARS['style']) ) ? urldecode($HTTP_GET_VARS['style']) : $HTTP_POST_VARS['style'];

		// Create a style_id
		$sql = "SELECT MAX(themes_id) AS max_style_id
			FROM " . THEMES_TABLE;
		if( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not get max style_id from themes table.', '', __LINE__, __FILE__, $sql);
		}
		$row = $db->sql_fetchrow($result);

		$max_style_id = $row['max_style_id'];
		
		if ( $max_style_id <= 0 )
		{
			$style_id = 1;
		}
		else
		{
			$style_id = $max_style_id + 1;
		}
	
		if( isset($install_to) )
		{
			include($phpbb_root_path . 'templates/' . basename($install_to) . '/theme_info.cfg');

			$template_name = $$install_to;
			$found = FALSE; 
			
			for($i = 0; $i < count($template_name) && !$found; $i++)
			{
				if( $template_name[$i]['style_name'] == $style_name )
				{
					while(list($key, $val) = each($template_name[$i]))
					{
						$db_fields[] = $key;
						$db_values[] = str_replace("\'", "''" , $val);
					}
				}
			}
					
			$sql = "INSERT INTO " . THEMES_TABLE . " (themes_id, ";

			for($i = 0; $i < count($db_fields); $i++)
			{
				$sql .= $db_fields[$i];
				if($i != (count($db_fields) - 1))
				{
					$sql .= ", ";
				}
				
			}

			$sql .= ") VALUES ($style_id, ";

			for($i = 0; $i < count($db_values); $i++)
			{
				$sql .= "'" . $db_values[$i] . "'";
				if($i != (count($db_values) - 1))
				{
					$sql .= ", ";
				}
			}
			$sql .= ")";
			
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Could not insert theme data!", "", __LINE__, __FILE__, $sql);
			}
			
			$message = $lang['Theme_installed'] . "<br /><br />" . sprintf($lang['Click_return_styleadmin'], "<a href=\"" . append_sid("admin_styles_users.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

			message_die(GENERAL_MESSAGE, $message);
		}
		else
		{
			$installable_themes = array();
			
			if( $dir = @opendir($phpbb_root_path. "templates/") )
			{
				while( $sub_dir = @readdir($dir) )
				{
					if( !is_file(phpbb_realpath($phpbb_root_path . 'templates/' .$sub_dir)) && !is_link(phpbb_realpath($phpbb_root_path . 'templates/' .$sub_dir)) && $sub_dir != "." && $sub_dir != ".." && $sub_dir != "CVS" )
					{
						if( @file_exists(@phpbb_realpath($phpbb_root_path. "templates/" . $sub_dir . "/theme_info.cfg")) )
						{
							include($phpbb_root_path. "templates/" . $sub_dir . "/theme_info.cfg");
							
							for($i = 0; $i < count($$sub_dir); $i++)
							{
								$working_data = $$sub_dir;
								
								$style_name = $working_data[$i]['style_name'];
														
								$sql = "SELECT themes_id 
									FROM " . THEMES_TABLE . " 
									WHERE style_name = '" . str_replace("\'", "''", $style_name) . "'";
								if(!$result = $db->sql_query($sql))
								{
									message_die(GENERAL_ERROR, "Could not query themes table!", "", __LINE__, __FILE__, $sql);
								}

								if(!$db->sql_numrows($result))
								{
									$installable_themes[] = $working_data[$i];
								}
							}
						}
					}
				}
				
				$template->set_filenames(array(
					"body" => "admin/styles_addnew_body.tpl")
				);
				
		
				$template->assign_vars(array(
					"L_STYLES_TITLE" => $lang['Add_style'],
					"L_STYLES_ADD_TEXT" => $lang['Styles_addnew_explain'],
					"L_STYLE" => $lang['Style'],
					"L_TEMPLATE" => $lang['Template'],
					"L_INSTALL" => $lang['Install'])
				);
					
				for($i = 0; $i < count($installable_themes); $i++)
				{
					$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
		
					$template->assign_block_vars("styles", array(
						"ROW_CLASS" => $row_class,
						"STYLE_NAME" => $installable_themes[$i]['style_name'],
						"TEMPLATE_NAME" => $installable_themes[$i]['template_name'],

						"U_STYLES_INSTALL" => append_sid("admin_styles.$phpEx?mode=addnew&amp;style=" . urlencode($installable_themes[$i]['style_name']) . "&amp;install_to=" . urlencode($installable_themes[$i]['template_name'])))
					);
				
				}
				$template->pparse("body");
					
			}
			closedir($dir);
		}
		break;
	
	case "create":
		$selected['theme_public'] = $selected['adminbold'] = $selected['supermodbold'] = $selected['modbold'] = TRUE;		
		$selected['style_version'] = '1.0.0';

	case "edit":
		$submit = ( isset($HTTP_POST_VARS['submit']) ) ? TRUE : 0;
		
		if( $submit )
		{
			//	
			// DAMN! Thats alot of data to validate...
			//
			$updated['style_name'] = $HTTP_POST_VARS['style_name'];
			$updated['style_version'] = $HTTP_POST_VARS['style_version'];
			$updated['template_name'] = $HTTP_POST_VARS['template_name'];
			$updated['theme_public'] = $HTTP_POST_VARS['theme_public'];
			$updated['theme_header'] = $HTTP_POST_VARS['theme_header'];
			$updated['theme_footer'] = $HTTP_POST_VARS['theme_footer'];
			$updated['head_stylesheet'] = $HTTP_POST_VARS['head_stylesheet'];
			$updated['image_cfg'] = $HTTP_POST_VARS['image_cfg'];
			$updated['body_background'] = $HTTP_POST_VARS['body_background'];
			$updated['body_bgcolor'] = $HTTP_POST_VARS['body_bgcolor'];
			$updated['body_text'] = $HTTP_POST_VARS['body_text'];
			$updated['body_link'] = $HTTP_POST_VARS['body_link'];
			$updated['body_vlink'] = $HTTP_POST_VARS['body_vlink'];
			$updated['body_alink'] = $HTTP_POST_VARS['body_alink'];
			$updated['body_hlink'] = $HTTP_POST_VARS['body_hlink'];
			$updated['tr_color1'] = $HTTP_POST_VARS['tr_color1'];
			$updated_name['tr_color1_name'] = $HTTP_POST_VARS['tr_color1_name'];
			$updated['tr_color2'] = $HTTP_POST_VARS['tr_color2'];
			$updated_name['tr_color2_name'] = $HTTP_POST_VARS['tr_color2_name'];
			$updated['tr_color3'] = $HTTP_POST_VARS['tr_color3'];
			$updated_name['tr_color3_name'] = $HTTP_POST_VARS['tr_color3_name'];
			$updated['tr_class1'] = $HTTP_POST_VARS['tr_class1'];
			$updated_name['tr_class1_name'] = $HTTP_POST_VARS['tr_class1_name'];
			$updated['tr_class2'] = $HTTP_POST_VARS['tr_class2'];
			$updated_name['tr_class2_name'] = $HTTP_POST_VARS['tr_class2_name'];
			$updated['tr_class3'] = $HTTP_POST_VARS['tr_class3'];
			$updated_name['tr_class3_name'] = $HTTP_POST_VARS['tr_class3_name'];
			$updated['th_color1'] = $HTTP_POST_VARS['th_color1'];
			$updated_name['th_color1_name'] = $HTTP_POST_VARS['th_color1_name'];
			$updated['th_color2'] = $HTTP_POST_VARS['th_color2'];
			$updated_name['th_color2_name'] = $HTTP_POST_VARS['th_color2_name'];
			$updated['th_color3'] = $HTTP_POST_VARS['th_color3'];
			$updated_name['th_color3_name'] = $HTTP_POST_VARS['th_color3_name'];
			$updated['th_class1'] = $HTTP_POST_VARS['th_class1'];
			$updated_name['th_class1_name'] = $HTTP_POST_VARS['th_class1_name'];
			$updated['th_class2'] = $HTTP_POST_VARS['th_class2'];
			$updated_name['th_class2_name'] = $HTTP_POST_VARS['th_class2_name'];
			$updated['th_class3'] = $HTTP_POST_VARS['th_class3'];
			$updated_name['th_class3_name'] = $HTTP_POST_VARS['th_class3_name'];
			$updated['td_color1'] = $HTTP_POST_VARS['td_color1'];
			$updated_name['td_color1_name'] = $HTTP_POST_VARS['td_color1_name'];
			$updated['td_color2'] = $HTTP_POST_VARS['td_color2'];
			$updated_name['td_color2_name'] = $HTTP_POST_VARS['td_color2_name'];
			$updated['td_color3'] = $HTTP_POST_VARS['td_color3'];
			$updated_name['td_color3_name'] = $HTTP_POST_VARS['td_color3_name'];
			$updated['td_class1'] = $HTTP_POST_VARS['td_class1'];
			$updated_name['td_class1_name'] = $HTTP_POST_VARS['td_class1_name'];
			$updated['td_class2'] = $HTTP_POST_VARS['td_class2'];
			$updated_name['td_class2_name'] = $HTTP_POST_VARS['td_class2_name'];
			$updated['td_class3'] = $HTTP_POST_VARS['td_class3'];
			$updated_name['td_class3_name'] = $HTTP_POST_VARS['td_class3_name'];
			$updated['fontface1'] = $HTTP_POST_VARS['fontface1'];
			$updated_name['fontface1_name'] = $HTTP_POST_VARS['fontface1_name'];
			$updated['fontface2'] = $HTTP_POST_VARS['fontface2'];
			$updated_name['fontface2_name'] = $HTTP_POST_VARS['fontface2_name'];
			$updated['fontface3'] = $HTTP_POST_VARS['fontface3'];
			$updated_name['fontface3_name'] = $HTTP_POST_VARS['fontface3_name'];
			$updated['fontsize1'] = intval($HTTP_POST_VARS['fontsize1']);
			$updated_name['fontsize1_name'] = $HTTP_POST_VARS['fontsize1_name'];
			$updated['fontsize2'] = intval($HTTP_POST_VARS['fontsize2']);
			$updated_name['fontsize2_name'] = $HTTP_POST_VARS['fontsize2_name'];
			$updated['fontsize3'] = intval($HTTP_POST_VARS['fontsize3']);
			$updated_name['fontsize3_name'] = $HTTP_POST_VARS['fontsize3_name'];
			$updated['fontcolor1'] = $HTTP_POST_VARS['fontcolor1'];
			$updated_name['fontcolor1_name'] = $HTTP_POST_VARS['fontcolor1_name'];
			$updated['fontcolor2'] = $HTTP_POST_VARS['fontcolor2'];
			$updated_name['fontcolor2_name'] = $HTTP_POST_VARS['fontcolor2_name'];
			$updated['fontcolor3'] = $HTTP_POST_VARS['fontcolor3'];
			$updated_name['fontcolor3_name'] = $HTTP_POST_VARS['fontcolor3_name'];
			$updated['fontcolor4'] = $HTTP_POST_VARS['fontcolor4'];
			$updated_name['fontcolor4_name'] = $HTTP_POST_VARS['fontcolor4_name'];
			$updated['adminfontcolor'] = $HTTP_POST_VARS['adminfontcolor']; 
			$updated['supermodfontcolor'] = $HTTP_POST_VARS['supermodfontcolor']; 
			$updated['modfontcolor'] = $HTTP_POST_VARS['modfontcolor']; 
			$updated['playersfontcolor'] = $HTTP_POST_VARS['playersfontcolor']; 
			$updated['botfontcolor'] = $HTTP_POST_VARS['botfontcolor']; 
			$updated['span_class1'] = $HTTP_POST_VARS['span_class1'];
			$updated_name['span_class1_name'] = $HTTP_POST_VARS['span_class1_name'];
			$updated['span_class2'] = $HTTP_POST_VARS['span_class2'];
			$updated_name['span_class2_name'] = $HTTP_POST_VARS['span_class2_name'];
			$updated['span_class3'] = $HTTP_POST_VARS['span_class3'];
			$updated_name['span_class3_name'] = $HTTP_POST_VARS['span_class3_name'];
			$updated['hr_color1'] = $HTTP_POST_VARS['hr_color1'];
			$updated_name['hr_color1_name'] = $HTTP_POST_VARS['hr_color1_name'];
			$updated['hr_color2'] = $HTTP_POST_VARS['hr_color2'];
			$updated_name['hr_color2_name'] = $HTTP_POST_VARS['hr_color2_name'];
			$updated['hr_color3'] = $HTTP_POST_VARS['hr_color3'];
			$updated_name['hr_color3_name'] = $HTTP_POST_VARS['hr_color3_name'];
			$updated['hr_color4'] = $HTTP_POST_VARS['hr_color4'];
			$updated_name['hr_color4_name'] = $HTTP_POST_VARS['hr_color4_name'];
			$updated['hr_color5'] = $HTTP_POST_VARS['hr_color5'];
			$updated_name['hr_color5_name'] = $HTTP_POST_VARS['hr_color5_name'];
			$updated['hr_color6'] = $HTTP_POST_VARS['hr_color6'];
			$updated_name['hr_color6_name'] = $HTTP_POST_VARS['hr_color6_name'];
			$updated['hr_color7'] = $HTTP_POST_VARS['hr_color7'];
			$updated_name['hr_color7_name'] = $HTTP_POST_VARS['hr_color7_name'];
			$updated['hr_color8'] = $HTTP_POST_VARS['hr_color8'];
			$updated_name['hr_color8_name'] = $HTTP_POST_VARS['hr_color8_name'];
			$updated['hr_color9'] = $HTTP_POST_VARS['hr_color9'];
			$updated_name['hr_color9_name'] = $HTTP_POST_VARS['hr_color9_name'];
			$updated['jb_color1'] = $HTTP_POST_VARS['jb_color1'];
			$updated_name['jb_color1_name'] = $HTTP_POST_VARS['jb_color1_name'];
			$updated['jb_color2'] = $HTTP_POST_VARS['jb_color2'];
			$updated_name['jb_color2_name'] = $HTTP_POST_VARS['jb_color2_name'];
			$updated['jb_color3'] = $HTTP_POST_VARS['jb_color3'];
			$updated_name['jb_color3_name'] = $HTTP_POST_VARS['jb_color3_name'];
			$updated['adminbold'] = intval($HTTP_POST_VARS['adminbold']);
			$updated['supermodbold'] = intval($HTTP_POST_VARS['supermodbold']);
			$updated['modbold'] = intval($HTTP_POST_VARS['modbold']);

			$style_id = intval($HTTP_POST_VARS['style_id']);
			
			//
			// Wheeeew! Thank heavens for copy and paste and search and replace :D
			//

			// If user is creating a style, create a style_id
			if ( !$style_id )
			{
				$sql = "SELECT MAX(themes_id) AS max_style_id
					FROM " . THEMES_TABLE;
				if( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'Could not get max style_id from themes table.', '', __LINE__, __FILE__, $sql);
				}
				$row = $db->sql_fetchrow($result);
		
				$max_style_id = $row['max_style_id'];
				
				if ( $max_style_id <= 0 )
				{
					$style_id = 1;
				}
				else
				{
					$style_id = $max_style_id + 1;
				}
			}
						
			if($mode == "edit")
			{
				$sql = "UPDATE " . THEMES_TABLE . " SET ";
				$count = 0;

				while(list($key, $val) = each($updated))
				{
					if($count != 0)
					{
						$sql .= ", ";
					}

					//
					// I don't like this but it'll keep MSSQL from throwing
					// an error and save me alot of typing
					//
					$sql .= ( stristr($key, "fontsize") ) ? "$key = $val" : "$key = '" . str_replace("\'", "''", $val) . "'";

					$count++;
				}
				
				$sql .= " WHERE themes_id = $style_id";

				if(!$result = $db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, "Could not update themes table!", "", __LINE__, __FILE__, $sql);
				}
				
				//
				// Check if there's a names table entry for this style
				//
				$sql = "SELECT themes_id 
					FROM " . THEMES_NAME_TABLE . " 
					WHERE themes_id = $style_id";
				if(!$result = $db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, "Could not get data from themes_name table", "", __LINE__, __FILE__, $sql);
				}
				
				if($db->sql_numrows($result) > 0)
				{
					$sql = "UPDATE " . THEMES_NAME_TABLE . " 
						SET ";
					$count = 0;
					while(list($key, $val) = each($updated_name))
					{
						if($count != 0)
						{
							$sql .= ", ";
						}
			
						$sql .= "$key = '$val'";
			
						$count++;
					}
					
					$sql .= " WHERE themes_id = $style_id";
				}
				else
				{
					//
					// Nope, no names entry so we create a new one.
					//
					$sql = "INSERT INTO " . THEMES_NAME_TABLE . " (themes_id, ";
					while(list($key, $val) = each($updated_name))
					{
						$fields[] = $key;
						$vals[] = str_replace("\'", "''", $val);
					}

					for($i = 0; $i < count($fields); $i++)
					{
						if($i > 0)
						{
							$sql .= ", ";
						}
						$sql .= $fields[$i];
					}
					
					$sql .= ") VALUES ($style_id, ";
					for($i = 0; $i < count($vals); $i++)
					{
						if($i > 0)
						{
							$sql .= ", ";
						}
						$sql .= "'" . $vals[$i] . "'";
					}
					
					$sql .= ")";
				}
										
				if(!$result = $db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, "Could not update themes name table!", "", __LINE__, __FILE__, $sql);
				}
							
				$message = $lang['Theme_updated'] . "<br /><br />" . sprintf($lang['Click_return_styleadmin'], "<a href=\"" . append_sid("admin_styles_users.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

				message_die(GENERAL_MESSAGE, $message);
			}
			else
			{
				//
				// First, check if we already have a style by this name
				//
				$sql = "SELECT themes_id 
					FROM " . THEMES_TABLE . " 
					WHERE style_name = '" . str_replace("\'", "''", $updated['style_name']) . "'";
				if(!$result = $db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, "Could not query themes table", "", __LINE__, __FILE__, $sql);
				}
				
				if($db->sql_numrows($result))
				{
					message_die(GENERAL_ERROR, $lang['Style_exists'], $lang['Error']);
				}				
				
				while(list($key, $val) = each($updated))
				{
					$field_names[] = $key;

					if(stristr($key, "fontsize"))
					{
						$values[] = "$val";
					}
					else
					{
						$values[] = "'" . str_replace("\'", "''", $val) . "'";
					}
				}
				
				$sql = "INSERT 
					INTO " . THEMES_TABLE . " (themes_id, ";
				for($i = 0; $i < count($field_names); $i++)
				{
					if($i != 0)
					{
						$sql .= ", ";
					}
					$sql .= $field_names[$i];
				}
				
				$sql .= ") VALUES ($style_id, ";
				for($i = 0; $i < count($values); $i++)
				{
					if($i != 0)
					{
						$sql .= ", ";
					}
					$sql .= $values[$i];
				}
				$sql .= ")";
				
				if(!$result = $db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, "Could not update themes table!", "", __LINE__, __FILE__, $sql);
				}
							
				// 
				// Insert names data
				//
				$sql = "INSERT INTO " . THEMES_NAME_TABLE . " (themes_id, ";
				while(list($key, $val) = each($updated_name))
				{
					$fields[] = $key;
					$vals[] = $val;
				}

				for($i = 0; $i < count($fields); $i++)
				{
					if($i > 0)
					{
						$sql .= ", ";
					}
					$sql .= $fields[$i];
				}
				
				$sql .= ") VALUES ($style_id, ";
				for($i = 0; $i < count($vals); $i++)
				{
					if($i > 0)
					{
					$sql .= ", ";
					}
				$sql .= "'" . $vals[$i] . "'";
				}
				
				$sql .= ")";
										
				if(!$result = $db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, "Could not insert themes name table!", "", __LINE__, __FILE__, $sql);
				}
				
				$message = $lang['Theme_created'] . "<br /><br />" . sprintf($lang['Click_return_styleadmin'], "<a href=\"" . append_sid("admin_styles_users.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

				message_die(GENERAL_MESSAGE, $message);
			}
		}
		else
		{
			if($mode == "edit")
			{
				$template->assign_block_vars('switch_noedit', array());

				$themes_title = $lang['Edit_theme'];
				$themes_explain = $lang['Edit_theme_explain'];
				
				$style_id = intval($HTTP_GET_VARS['style_id']);
				
				$selected_names = array();
				$selected_values = array();
				// 
				// Fetch the Theme Info from the db
				//
				$sql = "SELECT * 
					FROM " . THEMES_TABLE . " 
					WHERE themes_id = $style_id";
				if(!$result = $db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, "Could not get data from themes table", "", __LINE__, __FILE__, $sql);
				}
				
				if ( $selected_values = $db->sql_fetchrow($result) )
				{
					while(list($key, $val) = @each($selected_values))
					{
						$selected[$key] = $val;
					}
				}
				
				//
				// Fetch the Themes Name data
				//
				$sql = "SELECT * 
					FROM " . THEMES_NAME_TABLE . " 
					WHERE themes_id = $style_id";
				if(!$result = $db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, "Could not get data from themes name table", "", __LINE__, __FILE__, $sql);
				}
				
				if ( $selected_names = $db->sql_fetchrow($result) )
				{
					while(list($key, $val) = @each($selected_names))
					{
						$selected[$key] = $val;
					}
				}

				$s_hidden_fields = '<input type="hidden" name="style_id" value="' . $style_id . '" />';
			}
			else
			{
				$themes_title = $lang['Create_theme'];
				$themes_explain = $lang['Create_theme_explain'] . ' ' . $lang['Color_explain'];

				$template->assign_block_vars('switch_edit', array());
			}
			
			$template->set_filenames(array(
				"body" => "admin/styles_edit_body.tpl")
			);
			
		
			if( $dir = @opendir($phpbb_root_path . 'templates/') )
			{	
				$s_template_select = '<select name="template_name">';
				while( $file = @readdir($dir) )
				{	
					if( !is_file(phpbb_realpath($phpbb_root_path . 'templates/' . $file)) && !is_link(phpbb_realpath($phpbb_root_path . 'templates/' . $file)) && $file != "." && $file != ".." && $file != "CVS" && $file != 'admin' && $file != 'mod' && $file != 'js' )
					{
						if($file == $selected['template_name'])
						{
							$s_template_select .= '<option value="' . $file . '" selected="selected">' . $file . "</option>\n";
						}
						else
						{
							$s_template_select .= '<option value="' . $file . '">' . $file . "</option>\n";
						}
					}
				}
				$s_template_select .= '</select>';
			}
			else
			{
				message_die(GENERAL_MESSAGE, $lang['No_template_dir']);
			}

			$s_hidden_fields .= '<input type="hidden" name="mode" value="' . $mode . '" />';

			$theme_public_yes = ( $selected['theme_public'] ) ? "checked=\"checked\"" : "";
			$theme_public_no = ( !$selected['theme_public'] ) ? "checked=\"checked\"" : "";

			$theme_header_yes = ( $selected['theme_header'] ) ? "checked=\"checked\"" : "";
			$theme_header_no = ( !$selected['theme_header'] ) ? "checked=\"checked\"" : "";

			$theme_footer_yes = ( $selected['theme_footer'] ) ? "checked=\"checked\"" : "";
			$theme_footer_no = ( !$selected['theme_footer'] ) ? "checked=\"checked\"" : "";

			$bold_admin_yes = ( $selected['adminbold'] ) ? "checked=\"checked\"" : "";
			$bold_admin_no = ( !$selected['adminbold'] ) ? "checked=\"checked\"" : "";

			$bold_supermod_yes = ( $selected['supermodbold'] ) ? "checked=\"checked\"" : "";
			$bold_supermod_no = ( !$selected['supermodbold'] ) ? "checked=\"checked\"" : "";

			$bold_mod_yes = ( $selected['modbold'] ) ? "checked=\"checked\"" : "";
			$bold_mod_no = ( !$selected['modbold'] ) ? "checked=\"checked\"" : "";

			$template->assign_vars(array(
				"L_THEMES_TITLE" => $themes_title,
				"L_THEMES_EXPLAIN" => $themes_explain,
				"L_THEME_NAME" => $lang['Theme_name'],
				"L_THEME_VERSION" => $lang['Version'],
				"L_TEMPLATE" => $lang['Template'],
				"L_THEME_SETTINGS" => $lang['Theme_settings'],
				"L_THEME_ELEMENT" => $lang['Theme_element'],
				"L_SIMPLE_NAME" => $lang['Simple_name'],
				"L_VALUE" => $lang['Value'],
				"L_THEME_PUBLIC" => $lang['Theme_public'],
				"L_THEME_PUBLIC_EXPLAIN" => $lang['Theme_public_explain'],
				"L_THEME_HEADER" => $lang['Theme_header'],
				"L_THEME_FOOTER" => $lang['Theme_footer'],
				"L_STYLESHEET" => $lang['Stylesheet'],
				"L_STYLESHEET_EXPLAIN" => $lang['Stylesheet_explain'],
				"L_IMAGE_CFG" => $lang['Image_cfg'],
				"L_IMAGE_CFG_EXPLAIN" => $lang['Image_cfg_explain'],
				"L_BACKGROUND_IMAGE" => $lang['Background_image'],
				"L_BACKGROUND_COLOR" => $lang['Background_color'],
				"L_BODY_TEXT_COLOR" => $lang['Text_color'],
				"L_BODY_LINK_COLOR" => $lang['Link_color'],
				"L_BODY_VLINK_COLOR" => $lang['VLink_color'],
				"L_BODY_ALINK_COLOR" => $lang['ALink_color'],
				"L_BODY_HLINK_COLOR" => $lang['HLink_color'],
				"L_TR_COLOR1" => $lang['Tr_color1'],
				"L_TR_COLOR2" => $lang['Tr_color2'],
				"L_TR_COLOR3" => $lang['Tr_color3'],
				"L_TR_CLASS1" => $lang['Tr_class1'],
				"L_TR_CLASS2" => $lang['Tr_class2'],
				"L_TR_CLASS3" => $lang['Tr_class3'],
				"L_TH_COLOR1" => $lang['Th_color1'],
				"L_TH_COLOR2" => $lang['Th_color2'],
				"L_TH_COLOR3" => $lang['Th_color3'],
				"L_TH_CLASS1" => $lang['Th_class1'],
				"L_TH_CLASS2" => $lang['Th_class2'],
				"L_TH_CLASS3" => $lang['Th_class3'],
				"L_TD_COLOR1" => $lang['Td_color1'],
				"L_TD_COLOR2" => $lang['Td_color2'],
				"L_TD_COLOR3" => $lang['Td_color3'],
				"L_TD_CLASS1" => $lang['Td_class1'],
				"L_TD_CLASS2" => $lang['Td_class2'],
				"L_TD_CLASS3" => $lang['Td_class3'],
				"L_FONTFACE_1" => $lang['fontface1'],
				"L_FONTFACE_2" => $lang['fontface2'],
				"L_FONTFACE_3" => $lang['fontface3'],
				"L_FONTSIZE_1" => $lang['fontsize1'],
				"L_FONTSIZE_2" => $lang['fontsize2'],
				"L_FONTSIZE_3" => $lang['fontsize3'],
				"L_FONTCOLOR_1" => $lang['fontcolor1'],
				"L_FONTCOLOR_2" => $lang['fontcolor2'],
				"L_FONTCOLOR_3" => $lang['fontcolor3'],
				"L_FONTCOLOR_4" => $lang['fontcolor4'],
				"L_HR_COLOR1" => $lang['Hr_color1'],
				"L_HR_COLOR2" => $lang['Hr_color2'],
				"L_HR_COLOR3" => $lang['Hr_color3'],
				"L_HR_COLOR4" => $lang['Hr_color4'],
				"L_HR_COLOR5" => $lang['Hr_color5'],
				"L_HR_COLOR6" => $lang['Hr_color6'],
				"L_HR_COLOR7" => $lang['Hr_color7'],
				"L_HR_COLOR8" => $lang['Hr_color8'],
				"L_HR_COLOR9" => $lang['Hr_color9'],
				"L_JB_COLOR1" => $lang['Jb_color1'],
				"L_JB_COLOR2" => $lang['Jb_color2'],
				"L_JB_COLOR3" => $lang['Jb_color3'],
				"L_FONTCOLOR_ADMIN" => $lang['adminfontcolor'], 
				"L_FONTCOLOR_SUPERMOD" => $lang['supermodfontcolor'], 
				"L_FONTCOLOR_MOD" => $lang['modfontcolor'], 
				"L_FONTCOLOR_PLAYERS" => $lang['playersfontcolor'], 
				"L_FONTCOLOR_BOT" => $lang['botfontcolor'], 
				"L_BOLD_ADMIN" => $lang['adminbold'], 
				"L_BOLD_SUPERMOD" => $lang['supermodbold'], 
				"L_BOLD_MOD" => $lang['modbold'], 
				"L_SPAN_CLASS_1" => $lang['span_class1'],
				"L_SPAN_CLASS_2" => $lang['span_class2'],
				"L_SPAN_CLASS_3" => $lang['span_class3'],
				"L_SAVE_SETTINGS" => $lang['Save_Settings'], 

				"THEME_NAME" => $selected['style_name'],
				"THEME_VERSION" => $selected['style_version'],
				"HEAD_STYLESHEET" => $selected['head_stylesheet'],
				"THEME_PUBLIC_YES" => $theme_public_yes,
				"THEME_PUBLIC_NO" => $theme_public_no,
				"THEME_HEADER_YES" => $theme_header_yes,
				"THEME_HEADER_NO" => $theme_header_no,
				"THEME_FOOTER_YES" => $theme_footer_yes,
				"THEME_FOOTER_NO" => $theme_footer_no,
				"IMAGE_CFG" => $selected['image_cfg'],
				"BODY_BACKGROUND" => $selected['body_background'],
				"BODY_BGCOLOR" => $selected['body_bgcolor'],
				"BODY_TEXT_COLOR" => $selected['body_text'],
				"BODY_LINK_COLOR" => $selected['body_link'],
				"BODY_VLINK_COLOR" => $selected['body_vlink'],
				"BODY_ALINK_COLOR" => $selected['body_alink'],
				"BODY_HLINK_COLOR" => $selected['body_hlink'],
				"TR_COLOR1" => $selected['tr_color1'],
				"TR_COLOR2" => $selected['tr_color2'],
				"TR_COLOR3" => $selected['tr_color3'],
				"TR_CLASS1" => $selected['tr_class1'],
				"TR_CLASS2" => $selected['tr_class2'],
				"TR_CLASS3" => $selected['tr_class3'],
				"TH_COLOR1" => $selected['th_color1'],
				"TH_COLOR2" => $selected['th_color2'],
				"TH_COLOR3" => $selected['th_color3'],
				"TH_CLASS1" => $selected['th_class1'],
				"TH_CLASS2" => $selected['th_class2'],
				"TH_CLASS3" => $selected['th_class3'],
				"TD_COLOR1" => $selected['td_color1'],
				"TD_COLOR2" => $selected['td_color2'],
				"TD_COLOR3" => $selected['td_color3'],
				"TD_CLASS1" => $selected['td_class1'],
				"TD_CLASS2" => $selected['td_class2'],
				"TD_CLASS3" => $selected['td_class3'],
				"FONTFACE1" => $selected['fontface1'],
				"FONTFACE2" => $selected['fontface2'],
				"FONTFACE3" => $selected['fontface3'],
				"FONTSIZE1" => $selected['fontsize1'],
				"FONTSIZE2" => $selected['fontsize2'],
				"FONTSIZE3" => $selected['fontsize3'],
				"FONTCOLOR1" => $selected['fontcolor1'],
				"FONTCOLOR2" => $selected['fontcolor2'],
				"FONTCOLOR3" => $selected['fontcolor3'],
				"FONTCOLOR4" => $selected['fontcolor4'],
				"SPAN_CLASS1" => $selected['span_class1'],
				"SPAN_CLASS2" => $selected['span_class2'],
				"SPAN_CLASS3" => $selected['span_class3'],
				"HR_COLOR1" => $selected['hr_color1'],
				"HR_COLOR2" => $selected['hr_color2'],
				"HR_COLOR3" => $selected['hr_color3'],
				"HR_COLOR4" => $selected['hr_color4'],
				"HR_COLOR5" => $selected['hr_color5'],
				"HR_COLOR6" => $selected['hr_color6'],
				"HR_COLOR7" => $selected['hr_color7'],
				"HR_COLOR8" => $selected['hr_color8'],
				"HR_COLOR9" => $selected['hr_color9'],
				"JB_COLOR1" => $selected['jb_color1'],
				"JB_COLOR2" => $selected['jb_color2'],
				"JB_COLOR3" => $selected['jb_color3'],
				"ADMINFONTCOLOR" => $selected['adminfontcolor'], 
				"SUPERMODFONTCOLOR" => $selected['supermodfontcolor'], 
				"MODFONTCOLOR" => $selected['modfontcolor'], 
				"PLAYERSFONTCOLOR" => $selected['playersfontcolor'], 
				"BOTFONTCOLOR" => $selected['botfontcolor'], 
				"BOLD_ADMIN_YES" => $bold_admin_yes,
				"BOLD_ADMIN_NO" => $bold_admin_no,
				"BOLD_SUPERMOD_YES" => $bold_supermod_yes,
				"BOLD_SUPERMOD_NO" => $bold_supermod_no,
				"BOLD_MOD_YES" => $bold_mod_yes,
				"BOLD_MOD_NO" => $bold_mod_no,

				"TR_COLOR1_NAME" => ( $selected['tr_color1_name'] ) ? $selected['tr_color1_name'] : $lang['Style_name_tr_color1'],
				"TR_COLOR2_NAME" => ( $selected['tr_color2_name'] ) ? $selected['tr_color2_name'] : $lang['Style_name_tr_color2'],
				"TR_COLOR3_NAME" => ( $selected['tr_color3_name'] ) ? $selected['tr_color3_name'] : $lang['Style_name_tr_color3'],
				"TR_CLASS1_NAME" => $selected['tr_class1_name'],
				"TR_CLASS2_NAME" => $selected['tr_class2_name'],
				"TR_CLASS3_NAME" => $selected['tr_class3_name'],
				"TH_COLOR1_NAME" => $selected['th_color1_name'],
				"TH_COLOR2_NAME" => ( $selected['th_color2_name'] ) ? $selected['th_color2_name'] : $lang['Style_name_th_color2'],
				"TH_COLOR3_NAME" => ( $selected['th_color3_name'] ) ? $selected['th_color3_name'] : $lang['Style_name_th_color3'],
				"TH_CLASS1_NAME" => ( $selected['th_class1_name'] ) ? $selected['th_class1_name'] : $lang['Style_name_th_class1'],
				"TH_CLASS2_NAME" => ( $selected['th_class2_name'] ) ? $selected['th_class2_name'] : $lang['Style_name_th_class2'],
				"TH_CLASS3_NAME" => ( $selected['th_class3_name'] ) ? $selected['th_class3_name'] : $lang['Style_name_th_class3'],
				"TD_COLOR1_NAME" => ( $selected['td_color1_name'] ) ? $selected['td_color1_name'] : $lang['Style_name_td_color1'],
				"TD_COLOR2_NAME" => ( $selected['td_color2_name'] ) ? $selected['td_color2_name'] : $lang['Style_name_td_color2'],
				"TD_COLOR3_NAME" => $selected['td_color3_name'],
				"TD_CLASS1_NAME" => ( $selected['td_class1_name'] ) ? $selected['td_class1_name'] : $lang['Style_name_td_class1'],
				"TD_CLASS2_NAME" => ( $selected['td_class2_name'] ) ? $selected['td_class2_name'] : $lang['Style_name_td_class2'],
				"TD_CLASS3_NAME" => $selected['td_class3_name'],
				"FONTFACE1_NAME" => ( $selected['fontface1_name'] ) ? $selected['fontface1_name'] : $lang['Style_name_fontface1'],
				"FONTFACE2_NAME" => ( $selected['fontface2_name'] ) ? $selected['fontface2_name'] : $lang['Style_name_fontface2'],
				"FONTFACE3_NAME" => ( $selected['fontface3_name'] ) ? $selected['fontface3_name'] : $lang['Style_name_fontface3'],
				"FONTSIZE1_NAME" => ( $selected['fontsize1_name'] ) ? $selected['fontsize1_name'] : $lang['Style_name_fontsize1'],
				"FONTSIZE2_NAME" => ( $selected['fontsize2_name'] ) ? $selected['fontsize2_name'] : $lang['Style_name_fontsize2'],
				"FONTSIZE3_NAME" => ( $selected['fontsize3_name'] ) ? $selected['fontsize3_name'] : $lang['Style_name_fontsize3'],
				"FONTCOLOR1_NAME" => ( $selected['fontcolor1_name'] ) ? $selected['fontcolor1_name'] : $lang['Style_name_fontcolor1'],
				"FONTCOLOR2_NAME" => ( $selected['fontcolor2_name'] ) ? $selected['fontcolor2_name'] : $lang['Style_name_fontcolor2'],
				"FONTCOLOR3_NAME" => ( $selected['fontcolor3_name'] ) ? $selected['fontcolor3_name'] : $lang['Style_name_fontcolor3'],
				"FONTCOLOR4_NAME" => ( $selected['fontcolor4_name'] ) ? $selected['fontcolor4_name'] : $lang['Style_name_fontcolor4'],
				"SPAN_CLASS1_NAME" => $selected['span_class1_name'],
				"SPAN_CLASS2_NAME" => $selected['span_class2_name'],
				"SPAN_CLASS3_NAME" => $selected['span_class3_name'],

				"HR_COLOR1_NAME" => ( $selected['hr_color1_name'] ) ? $selected['hr_color1_name'] : $lang['Style_name_hr_color1'],
				"HR_COLOR2_NAME" => ( $selected['hr_color2_name'] ) ? $selected['hr_color2_name'] : $lang['Style_name_hr_color2'],
				"HR_COLOR3_NAME" => ( $selected['hr_color3_name'] ) ? $selected['hr_color3_name'] : $lang['Style_name_hr_color3'],
				"HR_COLOR4_NAME" => ( $selected['hr_color4_name'] ) ? $selected['hr_color4_name'] : $lang['Style_name_hr_color4'],
				"HR_COLOR5_NAME" => ( $selected['hr_color5_name'] ) ? $selected['hr_color5_name'] : $lang['Style_name_hr_color5'],
				"HR_COLOR6_NAME" => ( $selected['hr_color6_name'] ) ? $selected['hr_color6_name'] : $lang['Style_name_hr_color6'],
				"HR_COLOR7_NAME" => ( $selected['hr_color7_name'] ) ? $selected['hr_color7_name'] : $lang['Style_name_hr_color7'],
				"HR_COLOR8_NAME" => ( $selected['hr_color8_name'] ) ? $selected['hr_color8_name'] : $lang['Style_name_hr_color8'],
				"HR_COLOR9_NAME" => ( $selected['hr_color9_name'] ) ? $selected['hr_color9_name'] : $lang['Style_name_hr_color9'],

				"JB_COLOR1_NAME" => ( $selected['jb_color1_name'] ) ? $selected['jb_color1_name'] : $lang['Style_name_jb_color1'],
				"JB_COLOR2_NAME" => ( $selected['jb_color2_name'] ) ? $selected['jb_color2_name'] : $lang['Style_name_jb_color2'],
				"JB_COLOR3_NAME" => ( $selected['jb_color3_name'] ) ? $selected['jb_color3_name'] : $lang['Style_name_jb_color3'],

				"S_THEME_ACTION" => append_sid("admin_styles.$phpEx"),
				"S_TEMPLATE_SELECT" => $s_template_select,
				"S_HIDDEN_FIELDS" => $s_hidden_fields)
			);
			
			$template->pparse("body");
		}
		break;

	case "export";
		if($HTTP_POST_VARS['export_template'])
		{
			$template_name = $HTTP_POST_VARS['export_template'];

			$sql = "SELECT * 
				FROM " . THEMES_TABLE . " 
				WHERE template_name = '" . str_replace("\'", "''", $template_name) . "'"; 
			if(!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, "Could not get theme data for selected template", "", __LINE__, __FILE__, $sql);
			}
			
			$theme_rowset = $db->sql_fetchrowset($result);
			
			if( count($theme_rowset) == 0 )
			{
				message_die(GENERAL_MESSAGE, $lang['No_themes']);
			}
			
			$theme_data = '<?php'."\n\n";
			$theme_data .= "//\n// phpBB 2.x auto-generated theme config file for $template_name\n// Do not change anything in this file!\n//\n\n";

			for($i = 0; $i < count($theme_rowset); $i++)
			{
				while(list($key, $val) = each($theme_rowset[$i]))
				{
					if(!intval($key) && $key != "0" && $key != "themes_id")
					{
						$theme_data .= '$' . $template_name . "[$i]['$key'] = \"" . addslashes($val) . "\";\n";
					}
				}
				$theme_data .= "\n";
			}
			
			$theme_data .= '?' . '>'; // Done this to prevent highlighting editors getting confused!
			
			@umask(0111);

			$fp = @fopen($phpbb_root_path . 'templates/' . basename($template_name) . '/theme_info.cfg', 'w');

			if( !$fp )
			{
				//
				// Unable to open the file writeable do something here as an attempt
				// to get around that...
				//
				$s_hidden_fields = '<input type="hidden" name="theme_info" value="' . htmlspecialchars($theme_data) . '" />';
				$s_hidden_fields .= '<input type="hidden" name="send_file" value="1" /><input type="hidden" name="mode" value="export" />';
				
				$download_form = '<form action="' . append_sid("admin_styles.$phpEx") . '" method="post"><input class="mainoption" type="submit" name="submit" value="' . $lang['Download'] . '" />' . $s_hidden_fields;

				$template->set_filenames(array(
					"body" => "message_body.tpl")
				);

				$template->assign_vars(array(
					"MESSAGE_TITLE" => $lang['Export_themes'],
					"MESSAGE_TEXT" => $lang['Download_theme_cfg'] . "<br /><br />" . $download_form)
				);

				$template->pparse('body');
				exit();
			}

			$result = @fputs($fp, $theme_data, strlen($theme_data));
			fclose($fp);
			
			$message = $lang['Theme_info_saved'] . "<br /><br />" . sprintf($lang['Click_return_styleadmin'], "<a href=\"" . append_sid("admin_styles_users.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

			message_die(GENERAL_MESSAGE, $message);

		}
		else if($HTTP_POST_VARS['send_file'])
		{
			
			header("Content-Type: text/x-delimtext; name=\"theme_info.cfg\"");
			header("Content-disposition: attachment; filename=theme_info.cfg");

			echo stripslashes($HTTP_POST_VARS['theme_info']);
		}
		else
		{
			$template->set_filenames(array(
				"body" => "admin/styles_exporter.tpl")
			);
				
			if( $dir = @opendir($phpbb_root_path . 'templates/') )
			{	
				$s_template_select = '<select name="export_template">';
				while( $file = @readdir($dir) )
				{	
					if( !is_file(phpbb_realpath($phpbb_root_path . 'templates/' . $file)) && !is_link(phpbb_realpath($phpbb_root_path . 'templates/' .$file)) && $file != "." && $file != ".." && $file != "CVS" && $file != 'admin' && $file != 'mod' && $file != 'js' )
					{
						$s_template_select .= '<option value="' . $file . '">' . $file . "</option>\n";
					}
				}
				$s_template_select .= '</select>';
			}
			else
			{
				message_die(GENERAL_MESSAGE, $lang['No_template_dir']);
			}
			
			$template->assign_vars(array(
				"L_STYLE_EXPORTER" => $lang['Export_themes'],
				"L_EXPORTER_EXPLAIN" => $lang['Export_explain'],
				"L_TEMPLATE_SELECT" => $lang['Select_template'],

				"S_EXPORTER_ACTION" => append_sid("admin_styles.$phpEx?mode=export"),
				"S_TEMPLATE_SELECT" => $s_template_select)
			);
			
			$template->pparse("body");
			
		}
		break;

	case "delete":
		$style_id = ( isset($HTTP_GET_VARS['style_id']) ) ? intval($HTTP_GET_VARS['style_id']) : intval($HTTP_POST_VARS['style_id']);
		
		if( !$confirm )
		{
			if($style_id == $board_config['default_style'])
			{
				message_die(GENERAL_MESSAGE, $lang['Cannot_remove_style']);
			}
			
			$hidden_fields = '<input type="hidden" name="mode" value="'.$mode.'" /><input type="hidden" name="style_id" value="'.$style_id.'" />';
			
			//
			// Set template files
			//
			$template->set_filenames(array(
				"confirm" => "admin/confirm_body.tpl")
			);

			$template->assign_vars(array(
				"MESSAGE_TITLE" => $lang['Confirm'],
				"MESSAGE_TEXT" => $lang['Confirm_delete_style'],

				"L_YES" => $lang['Yes'],
				"L_NO" => $lang['No'],

				"S_CONFIRM_ACTION" => append_sid("admin_styles.$phpEx"),
				"S_HIDDEN_FIELDS" => $hidden_fields)
			);

			$template->pparse("confirm");

		}
		else
		{
			//
			// The user has confirmed the delete. Remove the style, the style element
			// names and update any users and forums who might be using this style
			//
			$sql = "DELETE FROM " . THEMES_TABLE . " 
				WHERE themes_id = $style_id";
			if(!$result = $db->sql_query($sql, BEGIN_TRANSACTION))
			{
				message_die(GENERAL_ERROR, "Could not remove style data!", "", __LINE__, __FILE__, $sql);
			}
			
			//
			// There may not be any theme name data so don't throw an error
			// if the SQL dosen't work
			//
			$sql = "DELETE FROM " . THEMES_NAME_TABLE . " 
				WHERE themes_id = $style_id";
			$db->sql_query($sql);
			
			$sql = "UPDATE " . FORUMS_TABLE . " 
				SET forum_template = " . $board_config['default_style'] . " 
				WHERE forum_template = $style_id";
			if(!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not update forum custom template style information', '', __LINE__, __FILE__, $sql);
			}
			
			$sql = 'UPDATE ' . IM_PREFS_TABLE . ' 
				SET themes_id = ' . $board_config['default_style'] . " 
				WHERE themes_id = $style_id";
			if(!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not update user IM style information', '', __LINE__, __FILE__, $sql);
			}

			$message = $lang['Style_removed'] . "<br /><br />" . sprintf($lang['Click_return_styleadmin'], "<a href=\"" . append_sid("admin_styles_users.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

			message_die(GENERAL_MESSAGE, $message);
		}
		break;
}

if (empty($HTTP_POST_VARS['send_file']))
{
	include('./page_footer_admin.'.$phpEx);
}

?>