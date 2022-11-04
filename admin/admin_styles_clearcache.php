<?php
/** 
 *
* @package admin
* @version $Id: admin_album_clearcache.php,v 1.0.0 2003/02/06, 21:16:46 ngoctu Exp $
* @copyright (c) 2003 Smartor
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	$module['Styles']['Clear_Cache'] = $filename;
	return;
}

//
// Load default header
//
//
// Check if the user has cancled a confirmation message.
//
$phpbb_root_path = '../';
require($phpbb_root_path . 'extension.inc');

$confirm = (isset($HTTP_POST_VARS['confirm']) || isset($_POST['confirm'])) ? TRUE : FALSE;
$cancel = ( isset($HTTP_POST_VARS['cancel']) || isset($_POST['cancel']) ) ? true : false;

$no_page_header = ($cancel) ? TRUE : FALSE; 

require('./pagestart.' . $phpEx);
include($phpbb_root_path . 'mods/stats/includes/stat_functions.'.$phpEx);

$confirm = (isset($HTTP_POST_VARS['confirm']) || isset($_POST['confirm'])) ? TRUE : FALSE;
$cancel = ( isset($HTTP_POST_VARS['cancel']) || isset($_POST['cancel']) ) ? true : false;

if ($cancel) 
{ 
	redirect('admin/' . append_sid("admin_styles_template_edit.$phpEx", true)); 
} 

if( !$confirm )
{
	$hidden_fields = '';

	//
	// Set template files
	//
	$template->set_filenames(array(
		'confirm' => 'admin/confirm_body.tpl')
	);

	$template->assign_vars(array(
		'MESSAGE_TITLE' => $lang['Confirm'],
		'MESSAGE_TEXT' => $lang['Template_clear_cache_confirm'],

		'L_NO' => $lang['No'],
		'L_YES' => $lang['Yes'],

		'S_CONFIRM_ACTION' => append_sid('admin_styles_clearcache.'.$phpEx),
		"S_HIDDEN_FIELDS" => $hidden_fields)
	);

	$template->pparse("confirm");
}
else
{
	clear_directory($board_config['xs_cache_dir']);

	$message = $lang['Template_cache_cleared_successfully'] . '<br /><br />' . sprintf($lang['Click_return_styleadmin'], '<a href="' . append_sid('admin_styles_template_edit.'.$phpEx) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.'.$phpEx.'?pane=right') . '">', '</a>');
	
	message_die(GENERAL_MESSAGE, $message);
}

include('./page_footer_admin.'.$phpEx);

?>