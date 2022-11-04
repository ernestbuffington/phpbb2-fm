<?php
/*
  paFileDB 3.1
  �2001/2002 PHP Arena
  Written by Todd
  todd@phparena.net
  http://www.phparena.net
  Keep all copyright links on the script visible
  Please read the license included with this script for more information.
  This script was programmed by Andrew Langland <andy@razza.org>
*/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
	$file = basename(__FILE__);
	$module['Download'][$lang['Fchecker']] = $file;
	
	return;
}

$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);
include($phpbb_root_path . 'mods/pafiledb/pafiledb_common.'.$phpEx);

$this_dir = $phpbb_root_path . $pafiledb_config['upload_dir'];

$html_path = get_formated_url() . '/' . $pafiledb_config['upload_dir']; 

if( isset($HTTP_GET_VARS['safety']) || isset($HTTP_POST_VARS['safety']) )
{
    $safety = (isset($HTTP_POST_VARS['safety'])) ? intval($HTTP_POST_VARS['safety']) : intval($HTTP_GET_VARS['safety']);
}

$template->set_filenames(array(
    'admin' => 'admin/pa_admin_file_checker.tpl')
);

if ($safety == 1)
{
	$saved = 0;

	$template->assign_block_vars("check", array());

	$template->assign_vars(array(
		'L_FILE_CHECKER_SP1' => $lang['Checker_sp1'])
	);

	$sql = "SELECT * 
		FROM " . PA_FILES_TABLE;
	if ( !($overall_result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Couldnt Query info', '', __LINE__, __FILE__, $sql);
	}

	while ($temp = $db->sql_fetchrow($overall_result))
	{
		$temp_dlurl = $temp['file_dlurl'];
		if (substr($temp_dlurl,0,strlen($html_path)) !== $html_path)
		{
			continue;
		}

		if (!is_file($this_dir."/".str_replace($html_path, "", $temp_dlurl))) 
		{
/*			$sql = "DELETE FROM " . PA_FILES_TABLE . " WHERE file_dlurl = '" . $temp_dlurl . "'";
			if ( !($db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Couldnt Query info', '', __LINE__, __FILE__, $sql);
			}*/
			$template->assign_block_vars("check.check_step1", array(
				'DEL_DURL' => $temp_dlurl)
			);
		}
	}

	$template->assign_vars(array(
		'L_FILE_CHECKER_SP2' => $lang['Checker_sp2'])
	);
	
	$sql = "SELECT * 
		FROM " . PA_FILES_TABLE;
	if ( !($overall_result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Couldnt Query info', '', __LINE__, __FILE__, $sql);
	}
	while ($temp = $db->sql_fetchrow($overall_result))
	{
		$temp_ssurl = $temp['file_ssurl'];
		$temp_file_id = $temp['file_id'];
		if (substr($temp_ssurl,0,strlen($html_path)) !== $html_path)
		{
			continue;
		}

		if (!is_file($this_dir."/".str_replace($html_path, "", $temp_ssurl))) 
		{
			/*$sql = "UPDATE " . PA_FILES_TABLE . " 
				SET file_ssurl='' 
				WHERE file_id = '" . $temp_file_id . "'";
			if ( !($db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Couldnt Query info', '', __LINE__, __FILE__, $sql);
			}*/

			$template->assign_block_vars("check.check_step2", array(
				'DEL_SSURL' => $temp_file_id)
			);
		}
	}

	$template->assign_vars(array(
		'L_FILE_CHECKER_SP3' => $lang['Checker_sp3'])
	);

	$files = opendir($this_dir);
	while ($temp = readdir($files))
	{
		if ($temp == "." || $temp == ".." || $temp == "index.html")
		{
			continue;
		}
		if (!is_file($this_dir.$temp))
		{
			continue;
		}

		$sql = "SELECT * 
			FROM " . PA_FILES_TABLE . " 
			WHERE file_dlurl = '" . $html_path.$temp . "' 
				OR file_ssurl = '" . $html_path.$temp . "'";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Couldnt Query info', '', __LINE__, __FILE__, $sql);
		}
		$numhits = $db->sql_numrows($result);

		if (!$numhits) 
		{
			$saved = $saved + filesize($this_dir.$temp);
			//unlink($this_dir.$temp);
			$template->assign_block_vars("check.check_step3", array(
				'DEL_FILE' => $temp)
			);
		}

	}
	closedir($files);

	if($saved == 0)
	{
		$saved = 'n/a';
	}
	elseif($saved >= 1073741824)
	{
		$saved = round($saved / 1073741824 * 100) / 100 . ' GBs';
	}
	elseif($saved >= 1048576)
	{
		$saved = round($saved / 1048576 * 100) / 100 . ' MBs';
	}
	elseif($saved >= 1024)
	{
		$saved = round($saved / 1024 * 100) / 100 . ' KBs';
	}
	else
	{
		$saved = $saved . ' Bytes';
	}

	$template->assign_vars(array(
		'L_FILE_CHECKER_SAVED' => $lang['Checker_saved'],
		'SAVED' => $saved)
	);
	$template->pparse('admin');
}
else 
{
	$template->assign_block_vars("perform", array());

	$lang['File_saftey'] = str_replace("{html_path}", $html_path, $lang['File_saftey']);

	$template->assign_vars(array(
		'L_FILE_CHECKER' => $lang['Fchecker'],
  		'L_FCHECKER_EXPLAIN' => $lang['File_checker_explain'],
		'L_FILE_PERFORM' => $lang['File_checker_perform'],
		'L_FILE_SAFTEY' => $lang['File_saftey'])
	);

    $template->pparse('admin');
}

include('./page_footer_admin.'.$phpEx);

?>

