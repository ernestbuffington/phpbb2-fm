<?php
/** 
*
* @package admin
* @version $Id: admin_styles_template_edit.php,v 1.1.0 2003/08/19 011:3:17 wGEric Exp $
* @copyright (c) 2002 Fubonis
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
	$file = basename(__FILE__);
	$module['Styles']['Template_Edit_Title'] = $file;
	return;
}

$phpbb_root_path = './../';
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);

if ( isset($HTTP_POST_VARS['choose']) )
{
	if ( is_dir($phpbb_root_path . 'templates/' . $HTTP_POST_VARS['template'] . '/') )
	{
		$template->set_filenames(array(
			'files' => 'admin/styles_template_select_body.tpl')
		);
	
		$templates = '';
		
		$dir = @opendir($phpbb_root_path . 'templates/' . $HTTP_POST_VARS['template'] . '/');

		while( $file = @readdir($dir) )
		{
			if( !is_file(phpbb_realpath($phpbb_root_path . 'templates/' . $HTTP_POST_VARS['template'] . '/' . $file)) && !is_link(phpbb_realpath($phpbb_root_path . 'templates/' . $HTTP_POST_VARS['template'] . '/' . $file)) && $file != "." && $file != ".." && $file != "CVS" && $file != 'Thumbs.db' && $file != 'index.html' && $file != 'index.htm' && $file != 'js' )
			{
				$sub_dir = @opendir($phpbb_root_path . 'templates/' . $HTTP_POST_VARS['template'] . '/' . $file);

				while( $sub_file = @readdir($sub_dir) )
				{
					if( !is_file(phpbb_realpath($phpbb_root_path . 'templates/' . $HTTP_POST_VARS['template'] . '/' . $file . '/' . $sub_file)) && !is_link(phpbb_realpath($phpbb_root_path . 'templates/' . $HTTP_POST_VARS['template'] . '/' . $file . '/' . $sub_file)) && $sub_file != "." && $sub_file != ".." && $sub_file != "CVS" && $sub_file != 'Thumbs.db' && $sub_file != 'index.html' && $sub_file != 'index.htm' && $sub_file != 'images' )
					{
						$templates .=  '<option value="' . $file . '/' . $sub_file . '">' . $file . '/' . $sub_file . '</option>';
					}
				}
			}
		}

		$file = '';
		@closedir($dir);
		
		$dir = dir($phpbb_root_path . 'templates/' . $HTTP_POST_VARS['template'] . '/');
		while ( $tpl = $dir->read() )
		{
			if ( is_file($phpbb_root_path . 'templates/' . $HTTP_POST_VARS['template'] . '/' . $tpl) )
			{
				$templates .=  '<option value="' . $tpl . '">' . $tpl . '</option>';
			}
		}
		@closedir($dir);
	
		$template->assign_vars(array(
			'L_TITLE' => $lang['Template_Edit_Title'],
			'L_EXPLAIN' => $lang['Template_Edit_Explain'],
			'L_CHOOSE_TEMPLATE' => $lang['Template_Edit_Choose'],
	
			'SUBMIT_NAME' => 'file',
			'FILE_NAME' => $HTTP_POST_VARS['template'] . '/',
			'HIDDEN_THINGS' => '<input type="hidden" name="directory" value="' . $HTTP_POST_VARS['template'] . '" />',
			
			'S_TEMPLATES' => $templates,
			'S_ACTION' => append_sid('admin_styles_template_edit.' . $phpEx))
		);
	
		$template->pparse('files');
		
		include('./page_footer_admin.'.$phpEx);
	}
	else
	{
		message_die(GENERAL_ERROR, $lang['Template_Edit_No_Template']);
	}
}
else if ( isset($HTTP_POST_VARS['file']) )
{
	if ( is_file($phpbb_root_path . 'templates/' . $HTTP_POST_VARS['directory'] . '/' . $HTTP_POST_VARS['template']) )
	{
		$file_data = @implode('', @file($phpbb_root_path . 'templates/' . $HTTP_POST_VARS['directory'] . '/' . $HTTP_POST_VARS['template']));
		
		if ( !empty($file_data) )
		{
			$template->set_filenames(array(
				'edit_file' => 'admin/styles_template_edit_body.tpl')
			);
			
			$template->assign_vars(array(
				'L_TITLE' => $lang['Template_Edit_Title'],
				'L_EXPLAIN' => $lang['Template_Edit_Explain'],
				'L_EDIT_TEMPLATE' => $lang['Template_Edit'],
				'L_SUBMIT' => $lang['Submit'],
				'L_RESET' => $lang['Reset'],

				'HIDDEN_THINGS' => '<input type="hidden" name="directory" value="' . $HTTP_POST_VARS['directory'] . '" /><input type="hidden" name="file_name" value="' . $HTTP_POST_VARS['template'] . '" />',
				'SUBMIT_NAME' => 'edit',
				'FILE_NAME' => $HTTP_POST_VARS['directory'] . '/' . $HTTP_POST_VARS['template'],
				'FILE_DATA' => htmlspecialchars(trim($file_data)),

				'S_ACTION' => append_sid('admin_styles_template_edit.' . $phpEx))
			);
			
			$template->pparse('edit_file');
			
			include('./page_footer_admin.'.$phpEx);
		}
		else
		{
			message_die(GENERAL_ERROR, $lang['Template_Edit_No_Open']);
		}
	}
	else
	{
		message_die(GENERAL_ERROR, $lang['Template_Edit_No_Files']);
	}
}
else if ( isset($HTTP_POST_VARS['edit']) )
{
	if ( is_file($phpbb_root_path . 'templates/' . $HTTP_POST_VARS['directory'] . '/' . $HTTP_POST_VARS['file_name']) )
	{
		$fp = fopen($phpbb_root_path . 'templates/' . $HTTP_POST_VARS['directory'] . '/' . $HTTP_POST_VARS['file_name'], 'w');
		
		if ( $fp )
		{
     		$file_data = stripslashes(trim($HTTP_POST_VARS['file_data']));
			$file_data = str_replace ("\r", "", $file_data);

			fwrite($fp, $file_data, strlen($file_data));
			fclose($fp);
			$message = $lang['Template_Edit_Yes_Write'] . "<br /><br />" . sprintf($lang['Click_return_template_edit'], "<a href=\"" . append_sid("admin_styles_template_edit.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

			message_die(GENERAL_MESSAGE, $message);
		}
		else
		{
			message_die(GENERAL_ERROR, $lang['Template_Edit_No_Write']);
		}
	}
}
		
$template->set_filenames(array(
	'template' => 'admin/styles_template_select_body.tpl')
);

$themes = '';
$dir = dir($phpbb_root_path . 'templates/');
while ( $tpl = $dir->read() )
{
	if ( !strstr($tpl, '.') )
	{
		$themes .= '<option value="' . $tpl . '">' . $tpl . '</option>';
	}
}

$template->assign_vars(array(
	'L_TITLE' => $lang['Template_Edit_Title'],
	'L_EXPLAIN' => $lang['Template_Edit_Explain'],
	'L_CHOOSE_TEMPLATE' => $lang['Template_Edit_Choose'],

	'SUBMIT_NAME' => 'choose',
	
	'S_TEMPLATES' => $themes,
	'S_ACTION' => append_sid('admin_styles_template_edit.' . $phpEx))
);

$dir->close();

$template->pparse('template');

include('./page_footer_admin.'.$phpEx);

?>