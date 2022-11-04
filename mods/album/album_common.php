<?php
/***************************************************************************
 *							   album_common.php
 *                            -------------------
 *   begin                : Saturday, February 01, 2003
 *   copyright            : (C) 2003 Smartor
 *   email                : smartor_xp@hotmail.com
 *
 *   $Id: album_common.php,v 2.0.2 2003/03/03 22:38:24 ngoctu Exp $
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
	die('Hacking attempt');
}

//
// Include Language files
//
$language = $board_config['default_lang'];

if ( defined('IN_ADMIN') )
{	
	if ( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_admin_album.'.$phpEx) )
	{
		$language = 'english';
	}
	include($phpbb_root_path . 'language/lang_' . $language . '/lang_admin_album.' . $phpEx);
}

if ( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_main_album.'.$phpEx) )
{
	$language = 'english';
}
include($phpbb_root_path . 'language/lang_' . $language . '/lang_main_album.' . $phpEx);


//
// Get Album Config
//
$album_config = array();

$cache_file = $cache_dir . '/config_album.php';

function get_album_config()
{
	global $db;

	$album_config = array();

	$sql = "SELECT *
		FROM " . ALBUM_CONFIG_TABLE;
	if(!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Could not query album config information", "", __LINE__, __FILE__, $sql);
	}
	while( $row = $db->sql_fetchrow($result) )
	{
		$album_config[$row['config_name']] = $row['config_value'];
	}
	$db->sql_freeresult($result);

	return $album_config;
}

if (file_exists($cache_dir) && is_dir($cache_dir) && is_writable($cache_dir))
{
	if (file_exists($cache_file))
///	if (file_exists($cache_file) && (time() < ''))
	{
		include($cache_file);
	}
	else
	{
		$album_config = get_album_config();
		$fp = @fopen($cache_file, 'wt+');
		if ($fp)
		{
			$lines = array();
			foreach ($album_config as $k => $v)
			{
				if (is_int($v))
				{
					$lines[] = "'$k' => $v";
				}
				else if (is_bool($v))
				{
					$lines[] = "'$k' => " . (($v) ? 'TRUE' : 'FALSE');
				}
				else
				{
					$lines[] = "'$k' => '" . str_replace("'", "\\'", str_replace('\\', '\\\\', $v)) . "'";
				}
			}
			fwrite($fp, '<?php if (!defined(\'IN_PHPBB\')) { die(\'Hacking attempt\'); } $album_config = array(' . implode(',', $lines) . '); ?>');
			fclose($fp);

			@chmod($cache_file, 0777);
		}
	}
}
else
{
	$album_config = get_album_config();
}


//
// Set ALBUM Version
//
$template->assign_vars(array(
	'ALBUM_VERSION' => '2' . $album_config['album_version'])
);

include($album_root_path . 'album_functions.'.$phpEx);
include($album_root_path . 'clown_album_functions.'.$phpEx);

?>