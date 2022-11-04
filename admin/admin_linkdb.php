<?php
/** 
*
* @package admin
* @version $Id: admin_board.php,v 1.51.2.15 2006/02/10 22:19:01 grahamje Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', true);

if( !empty($setmodules) )
{
	$filename = basename(__FILE__);
//	$module['Linkdb']['Configuration'] = $filename . "?action=setting";
	$module['Linkdb']['Link_cat_manage'] = $filename . "?action=cat_manage";
	$module['Linkdb']['Link_manage'] = $filename . "?action=link_manage";
	$module['Linkdb']['Link_man_field'] = $filename . "?action=link_custom";
	return;
}

//
// Load default header
//
$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);

include($phpbb_root_path . 'mods/linkdb/includes/linkdb_common.'.$phpEx);

//
// Get action variable other wise set it to the main
//

$action = ( isset($_REQUEST['action']) ) ? htmlspecialchars($_REQUEST['action']) : 'setting';

//
// an array of all expected actions
//

$actions = array('setting' => 'setting',
				 'cat_manage' => 'cat_manage', 
				 'link_manage' => 'link_manage',
				 'link_custom' => 'link_custom');

//
// Lets Build the page
//

$linkdb->adminmodule($actions[$action]);
$linkdb->modules[$actions[$action]]->main($action);

$linkdb->modules[$actions[$action]]->_linkdb();

include('./page_footer_admin.'.$phpEx);
?>