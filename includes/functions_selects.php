<?php
/** 
*
* @package includes
* @version $Id: functions_selects.php,v 1.3.2.4 2002/12/22 12:20:35 psotfx Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

//
// Pick a language, any language ...
//
function language_select($default, $select_name = 'language', $dirname = 'language')
{
	global $phpEx, $phpbb_root_path;

	$dir = opendir($phpbb_root_path . $dirname);

	$lang = array();
	while ( $file = readdir($dir) )
	{
		if (preg_match('#^lang_#i', $file) && !is_file(@phpbb_realpath($phpbb_root_path . $dirname . '/' . $file)) && !is_link(@phpbb_realpath($phpbb_root_path . $dirname . '/' . $file)))
		{
			$filename = trim(str_replace("lang_", "", $file));
			$displayname = preg_replace("/^(.*?)_(.*)$/", "\\1 [ \\2 ]", $filename);
			$displayname = preg_replace("/\[(.*?)_(.*)\]/", "[ \\1 - \\2 ]", $displayname);
			$lang[$displayname] = $filename;
		}
	}

	closedir($dir);

	@asort($lang);
	@reset($lang);

	$lang_select = '<select name="' . $select_name . '">';
	while ( list($displayname, $filename) = @each($lang) )
	{
		$selected = ( strtolower($default) == strtolower($filename) ) ? ' selected="selected"' : '';
		$lang_select .= '<option value="' . $filename . '"' . $selected . ' title="' . ucwords($displayname) . '">' . ucwords($displayname) . '</option>';
	}
	$lang_select .= '</select>';

	return $lang_select;
}

function language_select2($default, $select_name = 'language', $dirname = 'language') 
{ 
	global $phpEx; 

	$dir = opendir($dirname); 

	$lang = array(); 
	while ( $file = readdir($dir) ) 
	{ 
		if ( ereg("^lang_", $file) && !is_file($dirname . "/" . $file) && !is_link($dirname . "/" . $file) ) 
		{ 
			$filename = trim(str_replace("lang_", "", $file)); 
			$displayname = preg_replace("/^(.*?)_(.*)$/", "\\1 [ \\2 ]", $filename); 
			$displayname = preg_replace("/\[(.*?)_(.*)\]/", "[ \\1 - \\2 ]", $displayname); 
			$lang[$displayname] = $filename; 
		} 
	} 

	closedir($dir); 

	@asort($lang); 
	@reset($lang); 

	$lang_select = '<select name="' . $select_name . '">'; 
	while ( list($displayname, $filename) = @each($lang) ) 
	{ 
		$selected = ( strtolower($default) == strtolower($filename) ) ? ' selected="selected"' : ''; 
		$lang_select .= '<option value="' . $filename . '"' . $selected . ' title="' . ucwords($displayname) . '">' . ucwords($displayname) . '</option>'; 
	} 
		$lang_select .= '</select>'; 
	
	return $lang_select; 
}

//
// Pick a template/theme combo, 
//
function style_select($default_style, $select_name = 'style', $dirname = 'templates')
{
	global $db, $userdata, $board_config, $lang;

	$sql = "SELECT themes_id, style_name, theme_public
		FROM " . THEMES_TABLE . "
		ORDER BY style_name, themes_id";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, "Couldn't query themes table", "", __LINE__, __FILE__, $sql);
	}

	$style_select = '<select name="' . $select_name . '">';
	if ( defined('IN_ADMIN') && eregi('admin/admin_forums.'.$phpEx, $_SERVER['PHP_SELF']) )
	{
		$style_select .= '<option value="0">' . $lang['Default_style'] . '</option>';
	}
	while ( $row = $db->sql_fetchrow($result) )
	{
		$selected = ( $row['themes_id'] == $default_style ) ? ' selected="selected"' : '';

		if ( $row['theme_public'] == TRUE || $userdata['user_level'] == ADMIN || $row['themes_id'] == $board_config['default_style'] ) 
		{
			$style_select .= '<option value="' . $row['themes_id'] . '"' . $selected . ' title="' . $row['style_name'] . '">' . $row['style_name'] . '</option>';
		}
	}
	$db->sql_freeresult($result);
	$style_select .= '</select>';

	return $style_select;
}

//
// Pick a timezone
//
function tz_select($default, $select_name = 'timezone')
{
	global $sys_timezone, $lang;

	if ( !isset($default) )
	{
		$default == $sys_timezone;
	}
	$tz_select = '<select name="' . $select_name . '">';

	while( list($offset, $zone) = @each($lang['tz']) )
	{
		$selected = ( $offset == $default ) ? ' selected="selected"' : '';
		$zone2 = (strlen($zone) > 50) ? substr($zone, 0, 50) . '...' : $zone;
		$tz_select .= '<option value="' . $offset . '"' . $selected . ' title="' . $zone . '">' . $zone2 . '</option>';
	}
	$tz_select .= '</select>';

	return $tz_select;
}

function admin_tz_select($default, $select_name = 'timezone')
{
	global $sys_timezone, $lang;

	if ( !isset($default) )
	{
		$default == $sys_timezone;
	}
	$tz_select = '<select name="' . $select_name . '">';

	while( list($offset, $zone) = @each($lang['tz_short']) )
	{
		$selected = ( $offset == $default ) ? ' selected="selected"' : '';
		$zone2 = (strlen($zone) > 50) ? substr($zone, 0, 50) . '...' : $zone;
		$tz_select .= '<option value="' . $offset . '"' . $selected . ' title="' . $zone . '">' . $zone2 . '</option>';
	}
	$tz_select .= '</select>';

	return $tz_select;
}

//
// Pick a (canned) date format
//
function date_format_select($default, $timezone, $select_name = 'dateformat')
{
	global $board_config;

	// Include any valid PHP date format strings here, in your preferred order
	$date_formats = array(
		'D d M, H:i',
		'D d M, g:i a',
		'D d M, Y',
		'D d M, Y H:i',
		'D d M, Y g:i a',
		'D M d, Y',
		'D M d, Y H:i',
		'D M d, Y g:i a',
		'jS F, H:i', 
		'jS F, g:i a', 
		'jS F Y', 
		'jS F Y, H:i', 
		'jS F Y, g:i a', 
		'F jS, H:i',
		'F jS, g:i a',
		'F jS Y',
		'F jS Y, H:i',
		'F jS Y, g:i a',
		'j/n/Y',
		'j/n/Y, H:i',
		'j/n/Y, g:i a',
		'j.n.Y',
		'j.n.Y, H:i',
		'j.n.Y, g:i a',
		'n/j/Y',
		'n/j/Y, H:i',
		'n/j/Y, g:i a',
		'n.j.Y',
		'n.j.Y, H:i',
		'n.j.Y, g:i a',
		'Y-m-d',
		'Y-m-d, H:i',
		'Y-m-d, g:i a'
	);

	if ( !isset($timezone) )
	{
		$timezone == $board_config['board_timezone'];
	}
	$now = time() + (3600 * $timezone);

	$df_select = '<select name="' . $select_name . '">';
	for ($i = 0; $i < sizeof($date_formats); $i++)
	{
		$format = $date_formats[$i];
		$display = date($format, $now);
		$df_select .= '<option value="' . $format . '"';
		if (isset($default) && ($default == $format))
		{
			$df_select .= ' selected="selected"';
		}
		$df_select .= ' title="' . $display . '">' . $display . '</option>';
	}
	$df_select .= '</select>';

	return $df_select;
}

function admin_date_format_select($default, $timezone, $select_name = 'default_dateformat')
{
	global $board_config;

	// Include any valid PHP date format strings here, in your preferred order
	$date_formats = array(
		'D d M, H:i',
		'D d M, g:i a',
		'D d M, Y',
		'D d M, Y H:i',
		'D d M, Y g:i a',
		'D M d, Y',
		'D M d, Y H:i',
		'D M d, Y g:i a',
		'jS F, H:i', 
		'jS F, g:i a', 
		'jS F Y', 
		'jS F Y, H:i', 
		'jS F Y, g:i a', 
		'F jS, H:i',
		'F jS, g:i a',
		'F jS Y',
		'F jS Y, H:i',
		'F jS Y, g:i a',
		'j/n/Y',
		'j/n/Y, H:i',
		'j/n/Y, g:i a',
		'j.n.Y',
		'j.n.Y, H:i',
		'j.n.Y, g:i a',
		'n/j/Y',
		'n/j/Y, H:i',
		'n/j/Y, g:i a',
		'n.j.Y',
		'n.j.Y, H:i',
		'n.j.Y, g:i a',
		'Y-m-d',
		'Y-m-d, H:i',
		'Y-m-d, g:i a'
	);

	if ( !isset($timezone) )
	{
		$timezone == $board_config['board_timezone'];
	}
	$now = time() + (3600 * $timezone);

	$df_select = '<select name="' . $select_name . '">';
	for ($i = 0; $i < sizeof($date_formats); $i++)
	{
		$format = $date_formats[$i];
		$display = date($format, $now);
		$df_select .= '<option value="' . $format . '"';
		if (isset($default) && ($default == $format))
		{
			$df_select .= ' selected="selected"';
		}
		$df_select .= ' title="' . $display . '">' . $display . '</option>';
	}
	$df_select .= '</select>';

	return $df_select;
}

//
// Pick a page transition
//
function page_transition_select($default, $select_name = 'page_transition')
{
	global $board_config, $lang;

	// Include any valid JS page transitions here, in your preferred order
	$transitions = array(
		'' => $lang['Disabled'],
		'<script language="javascript" type="text/javascript" src="templates/js/leaves.js"></script>' => $lang['Autumn_Leaves'],
		'<script language="javascript" type="text/javascript" src="templates/js/snow.js"></script>' => $lang['Christmas_Snow'],
		'<script language="javascript" type="text/javascript" src="templates/js/ghosts.js"></script>' => $lang['Ghosts'], 
		'<script language="javascript" type="text/javascript" src="templates/js/birds.js"></script>' => $lang['Birds'], 
		'<meta http-equiv="page-enter" content="blendTrans(Duration=4.0)">' => $lang['Blend_In'],
		'<meta http-equiv="page-enter" content="RevealTrans(Duration=4,Transition=0)">' => $lang['Box_In'],
		'<meta http-equiv="page-enter" content="RevealTrans(Duration=4,Transition=1)">' => $lang['Box_Out'],
		'<meta http-equiv="page-enter" content="RevealTrans(Duration=4,Transition=10)">' => $lang['Checkerboard_Across'],
		'<meta http-equiv="page-enter" content="RevealTrans(Duration=4,Transition=11)">' => $lang['Checkerboard_Down'],
		'<meta http-equiv="page-enter" content="RevealTrans(Duration=4,Transition=2)">' => $lang['Circle_In'],
		'<meta http-equiv="page-enter" content="RevealTrans(Duration=4,Transition=3)">' => $lang['Circle_Out'],
		'<meta http-equiv="page-enter" content="RevealTrans(Duration=4,Transition=9)">' => $lang['Horizontal_Blinds'],
		'<meta http-equiv="page-enter" content="RevealTrans(Duration=4,Transition=23)">' => $lang['Random_'],
		'<meta http-equiv="page-enter" content="RevealTrans(Duration=4,Transition=21)">' => $lang['Random_Bars_Horizontal'],
		'<meta http-equiv="page-enter" content="RevealTrans(Duration=4,Transition=22)">' => $lang['Random_Bars_Vertical'],
		'<meta http-equiv="page-enter" content="RevealTrans(Duration=4,Transition=12)">' => $lang['Random_Dissolve'],
		'<meta http-equiv="page-enter" content="RevealTrans(Duration=4,Transition=15)">' => $lang['Split_Horizontal_In'],
		'<meta http-equiv="page-enter" content="RevealTrans(Duration=4,Transition=16)">' => $lang['Split_Horizontal_Out'],
		'<meta http-equiv="page-enter" content="RevealTrans(Duration=4,Transition=13)">' => $lang['Split_Vertical_In'],
		'<meta http-equiv="page-enter" content="RevealTrans(Duration=4,Transition=14)">' => $lang['Split_Vertical_Out'],
		'<meta http-equiv="page-enter" content="RevealTrans(Duration=4,Transition=18)">' => $lang['Strips_Left_Down'],
		'<meta http-equiv="page-enter" content="RevealTrans(Duration=4,Transition=17)">' => $lang['Strips_Left_Up'],
		'<meta http-equiv="page-enter" content="RevealTrans(Duration=4,Transition=20)">' => $lang['Strips_Right_Down'],
		'<meta http-equiv="page-enter" content="RevealTrans(Duration=4,Transition=19)">' => $lang['Strips_Right_Up'],
		'<meta http-equiv="page-enter" content="RevealTrans(Duration=4,Transition=8)">' => $lang['Vertical_Blinds'],
		'<meta http-equiv="page-enter" content="RevealTrans(Duration=4,Transition=5)">' => $lang['Wipe_Down'],
		'<meta http-equiv="page-enter" content="RevealTrans(Duration=4,Transition=7)">' => $lang['Wipe_Left'],
		'<meta http-equiv="page-enter" content="RevealTrans(Duration=4,Transition=6)">' => $lang['Wipe_Right'],
		'<meta http-equiv="page-enter" content="RevealTrans(Duration=4,Transition=4)">' => $lang['Wipe_Up'],
	);
	
	$pt_select = '<select name="' . $select_name . '">';
	foreach ($transitions AS $format => $display)
	{
		$selected = (isset($default) && ($format == $default)) ? ' selected="selected"' : '';
		$pt_select .= "<option value='" . $format . "'" . $selected . ' title="' . $display . '">' . $display . '</option>';
	}
	$pt_select .= '</select>';

	return $pt_select;
}


//
// Pick a clock format
//
function clock_format_select($default, $select_name = "clockformat")
{
	global $board_config, $lang, $phpbb_root_path;

	//
	// Read a listing of uploaded clocks ...
	//
	$dir = @opendir($phpbb_root_path . 'images/clock/');
	while($file = @readdir($dir))
	{
		if( !@is_dir(phpbb_realpath($phpbb_root_path . 'images/clock/' . $file)) )
		{
			if ($file == "." || $file == ".." || $file == "index.htm" || $file == "index.html" || $file == "Thumbs.db")
			{
				continue;
			}
			$clock[] = $file;
		}
	}
	@closedir($dir);
	sort($clock);

	$cf_select = '<select name="' . $select_name . '">';
	for ($i = 0; $i < sizeof($clock); $i++)
	{
		$cf_select .= '<option value="' . $clock[$i] . '"';
		if (isset($default) && ($default == $clock[$i]))
		{
			$cf_select .= ' selected="selected"';
		}
		$cf_select .= ' title="' . ucfirst(substr($clock[$i], 0, -4)) . '">' . ucfirst(substr($clock[$i], 0, -4)) . '</option>';
	}
	$cf_select .= '</select>';

	return $cf_select;
}


//
// Pick A forum
//	
function forum_select($default, $select_name)
{  
  	global $db, $lang;
  
  	$sql = "SELECT * 
  		FROM " . FORUMS_TABLE . "
		ORDER BY forum_name ASC";        
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, "Couldn't get list of Forums", "", __LINE__, __FILE__, $sql);
    }

  	$forum_select = '<select name="' . $select_name . '">';
  	$forum_select .= '<option value="0">' . $lang['Disabled'] . '</option>';
  	while ( $row = $db->sql_fetchrow($result) )
  	{
    	$selected = ( $row['forum_id'] == $default ) ? ' selected="selected"' : '';
    	$forum_select .= '<option value="' . $row['forum_id'] . '"' . $selected . ' title="' . $row['forum_name'] . '">' . $row['forum_name'] . '</option>';
    }
   	$db->sql_freeresult($result);
  	$forum_select .= '</select>';
  	
  	return $forum_select;
}  


//
// Disable modes
//
function disable_mode_select($default, $select_name = 'board_disable_mode')
{
	global $lang;

	if (!is_array($default))
	{
		$default = explode(',', $default);
	}

	$disable_select = '<select name="' . $select_name . '[]" size="5" multiple="multiple">';
	foreach ($lang['Board_disable_mode_opt'] AS $const => $name)
	{
		$selected = (in_array($const, $default)) ? ' selected="selected"' : '';
		$disable_select .= '<option value="' . $const . '"' . $selected . '>' . $name . '</option>';
	}
	$disable_select .= '</select>';

	return $disable_select;
}


// Pick a ADMIN CP config section
function config_select($default, $sections)
{
	global $lang, $phpEx;
	
	$config_select = '<select name="mode" style="width: 200px" onChange="if (this.options[this.selectedIndex].value != \'\') this.form.submit();">';
	foreach ($sections AS $mode => $name)
	{
		$selected = (isset($default) && ($mode == $default)) ? ' selected="selected"' : '';
		$config_select .= '<option value="' . $mode . '" title="' . $name . '"' . $selected . '>' . $name. '</option>';
	}
	$config_select .= '</select>';

	return $config_select;
}

function config_optgroup_select($default, $sections)
{
	global $phpEx;
	
	$config_select = '<select name="mode" style="width: 200px" onChange="if (this.options[this.selectedIndex].value != \'\') this.form.submit();">';
	for ( $row = 0; $row < sizeof($sections); $row++ )
	{
		for ( $column = 0; $column < 1; $column++ )
		{	
			$selected = (isset($default) && ($sections[$row]['MODE'] == $default)) ? ' selected="selected"' : '';

			$config_select .= ($sections[$row]['OPT'] == 1) ? '<optgroup label="' . $sections[$row]['LANG'] . '">' : ''; 
			$config_select .= (!$sections[$row]['OPT']) ? '<option value="' . $sections[$row]['MODE'] . '" title="' . $sections[$row]['LANG'] . '"' . $selected . ' />' . $sections[$row]['LANG'] . '</option>' : '';
			$config_select .= ($sections[$row]['OPT'] == -1) ? '</optgroup>' : ''; 
		}
	}
	$config_select .= '</select>';

	return $config_select;
}

?>