<?php
/***************************************************************************
 *                             linkdb_common.php
 *                            -------------------
 *   begin                : Saturday, Feb 23, 2001
 *   copyright            : (C) 2003 Mohd Web Site!
 *   email                : mohdalbasri@hotmail.com
 *   Modified by CRLin
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}

//
// addslashes to vars if magic_quotes_gpc is off
//
if(!@function_exists('slash_input_data'))
{
	function slash_input_data(&$data)
	{
		if (is_array($data))
		{
			foreach ($data as $k => $v)
			{
				$data[$k] = (is_array($v)) ? slash_input_data($v) : addslashes($v);
			}
		}
		return $data;
	}
}

//
// to make it work with php version under 4.1 and other stuff
//

if ( @phpversion()  < '4.1' )
{
	$_GET = &$HTTP_GET_VARS;
	$_POST = &$HTTP_POST_VARS;
	$_COOKIE = &$HTTP_COOKIE_VARS;
	$_SERVER = &$HTTP_SERVER_VARS;
	$_ENV = &$HTTP_ENV_VARS;
	$_FILES = &$HTTP_POST_FILES;
	$_SESSION = &$HTTP_SESSION_VARS;
}

if (!isset($_REQUEST))
{
	$_REQUEST = array_merge($_GET, $_POST, $_COOKIE);
}

if (!get_magic_quotes_gpc())
{
	$_GET = slash_input_data($_GET);
	$_POST = slash_input_data($_POST);
	$_COOKIE = slash_input_data($_COOKIE);
	$_REQUEST = slash_input_data($_REQUEST);
}

//
// Include linkdb data file
//

include($phpbb_root_path . 'mods/linkdb/includes/linkdb_constants.'.$phpEx);
include($phpbb_root_path . 'mods/linkdb/includes/functions.'.$phpEx);
include($phpbb_root_path . 'mods/linkdb/includes/functions_linkdb.'.$phpEx);

//
// Get Language
//
 
$language = $board_config['default_lang']; 

if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_linkdb.'.$phpEx) ) 
{ 
   $language = 'english'; 
} 
include($phpbb_root_path . 'language/lang_' . $language . '/lang_linkdb.' . $phpEx); 

if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_admin_linkdb.'.$phpEx) ) 
{ 
   $language = 'english'; 
} 
include($phpbb_root_path . 'language/lang_' . $language . '/lang_admin_linkdb.' . $phpEx);

$linkdb_functions = new linkdb_functions();
$linkdb_config = $linkdb_functions->linkdb_config();

$linkdb = new linkdb_public();

?>