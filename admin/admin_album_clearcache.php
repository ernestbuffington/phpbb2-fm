<?php
/** 
*
* @package admin
* @version $Id: admin_album_clearcache.php,v 1.0.0 2003/02/06, 21:16:46 ngoctu Exp $
* @copyright (c) 2003 Smartor
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', true);

if( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	$module['Album']['Clear_Cache'] = $filename;
	return;
}

//
// Let's set the root dir for phpBB
//
$phpbb_root_path = '../';
$album_root_path = $phpbb_root_path . 'mods/album/';
require($phpbb_root_path . 'extension.inc');

$confirm = (isset($HTTP_POST_VARS['confirm']) || isset($_POST['confirm'])) ? TRUE : FALSE;
$cancel = ( isset($HTTP_POST_VARS['cancel']) || isset($_POST['cancel']) ) ? true : false;

$no_page_header = ($cancel) ? TRUE : FALSE; 

require('./pagestart.' . $phpEx);
include($album_root_path . 'album_common.'.$phpEx);

$confirm = (isset($HTTP_POST_VARS['confirm']) || isset($_POST['confirm'])) ? TRUE : FALSE;
$cancel = ( isset($HTTP_POST_VARS['cancel']) || isset($_POST['cancel']) ) ? true : false;

if ($cancel) 
{ 
	redirect('admin/' . append_sid("admin_album_clearcache.$phpEx", true)); 
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
		'MESSAGE_TEXT' => $lang['Album_clear_cache_confirm'],

		'L_NO' => $lang['No'],
		'L_YES' => $lang['Yes'],

		'S_CONFIRM_ACTION' => append_sid("admin_album_clearcache.$phpEx"),
		"S_HIDDEN_FIELDS" => $hidden_fields)
	);
		
	$template->pparse("confirm");
}
else
{
	$cache_dir = @opendir('../' . ALBUM_CACHE_PATH);

	while( $cache_file = @readdir($cache_dir) )
	{
		if( preg_match('/(\.gif$|\.png$|\.jpg|\.jpeg)$/is', $cache_file) )
		{
			@unlink('../' . ALBUM_CACHE_PATH . $cache_file);
		}
	}

	@closedir($cache_dir);

	$message = $lang['Thumbnail_cache_cleared_successfully'] . '<br /><br />' . sprintf($lang['Click_return_config'], '<a href="' . append_sid('admin_album_config.'.$phpEx) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.'.$phpEx.'?pane=right') . '">', '</a>');
	
	message_die(GENERAL_MESSAGE, $message);
}

include('./page_footer_admin.'.$phpEx);

?>