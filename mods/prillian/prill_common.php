<?php
/***************************************************************************
 *                             prill_common.php
 *                            -------------------
 *   begin                : Tuesday, Jul 02, 2003
 *   version              : 0.2.0
 *   date                 : 2003/12/23 23:25
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/
 
/*
	This file "starts" Prillian by setting up common constants and variables.
	Commonly used files and routines are also included and/or executed.
*/

if ( !defined('IN_PHPBB') )
{
	die('Hacking attempt');
}

// CONSTANTS - BEGIN
// Some other constants are in constants_prillian.php
define('IN_PRILLIAN', true);
$language = $board_config['default_lang'];
define('CURRENT_LANG_PATH', $phpbb_root_path . 'language/lang_' . $language . '/');
// CONSTANTS - END

// FILES - BEGIN

include_once(PRILL_PATH . 'functions_im.' . $phpEx);
//include_once(CURRENT_LANG_PATH . 'lang_prillian.' . $phpEx);
if( !file_exists(CURRENT_LANG_PATH . 'lang_prillian.' . $phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_prillian.' . $phpEx);

// FILES - END

// VARIABLES - BEGIN

// Preset common vars.
$error = false;
$meta_headers = $refresh_javascript = $mode_append = $read_mark = '';
$im_auto_popup = 0;
$l_prillian_text = $lang['Launch_Prillian'];
$im_userdata = array();
$default_im_subject = $lang['phpBB_IM_default_subject'];
// VARIABLES - END

if( !defined('IN_NETWORK'))
{
	// Contact List requirements
	include_once(CONTACT_PATH . 'contact_common.' . $phpEx);
}
else
{
	include_once(CONTACT_PATH . 'functions_common.' . $phpEx);
}


secure_superglobals();

$cache_dir = $phpbb_root_path . '/cache';
$cache_file = $cache_dir . '/config_prill.php';

if (file_exists($cache_dir) && is_dir($cache_dir) && is_writable($cache_dir))
{
	if (file_exists($cache_file))
///	if (file_exists($cache_file) && (time() < ''))
	{
		include($cache_file);
	}
	else
	{
		$prill_config = get_prillian_config();
		$fp = @fopen($cache_file, 'wt+');
		if ($fp)
		{
			$lines = array();
			foreach ($prill_config as $k => $v)
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
			fwrite($fp, '<?php if (!defined(\'IN_PHPBB\')) { die(\'Hacking attempt\'); } $prill_config = array(' . implode(',', $lines) . '); ?>');
			fclose($fp);

			@chmod($cache_file, 0777);
		}
	}
}
else
{
	$prill_config = get_prillian_config();
}

// Skip unneeded page headers on small windows
$simple = 0;
$append_msg = '';
if( !empty($_REQUEST['simple']) || $gen_simple_header )
{
	$gen_simple_header = true;
	$simple = 1;
	$append_msg = $lang['Close_window_link'];
}

?>