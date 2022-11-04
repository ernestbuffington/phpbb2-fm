<?php
/** 
*
* @package admin
* @version $Id: admin_notepad.php,v 1.1.0 2006/02/10 22:19:01 hackett Exp $
* @copyright (c) 2003 Martyn Hackett
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
	$file = basename(__FILE__);
    $module['General']['Notepad'] = $file;

    return;
}

//
// Load default header
//
$phpbb_root_path = './../';
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);

//
// Output the authorisation details
//
$template->set_filenames(array(
   'body' => 'admin/board_notepad_body.tpl')
);

if (isset($HTTP_POST_VARS['post']))
{
	$tnote = addslashes($HTTP_POST_VARS['noteme']);
	
    $sql = "UPDATE " . ADVANCE_HTML_TABLE . " 
    	SET config_value = '" . $tnote . "' 
    	WHERE config_name = 'admin_notes'";
	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not update admin notes.', '', __LINE__, __FILE__, $sql);
	}
	
	// Remove cache file
	@unlink($phpbb_root_path . 'cache/config_html.'.$phpEx);
}

$sql = "SELECT * 
	FROM " . ADVANCE_HTML_TABLE . " 
	WHERE config_name = 'admin_notes'";
if (!$result = $db->sql_query($sql))
{
	message_die(GENERAL_ERROR, 'Could not obtain admin notes.', '', __LINE__, __FILE__, $sql);
}
$row = $db->sql_fetchrow($result);


$template->assign_vars(array(
	'L_TITLE' => $lang['Admin_notepad_title'],
	'L_TITLE_EXPLAIN' => $lang['Admin_notepad_explain'],
	
	'U_NOTEPAD' => stripslashes(htmlspecialchars($row['config_value'])))
);

$template->pparse('body');

include('./page_footer_admin.'.$phpEx);

?>