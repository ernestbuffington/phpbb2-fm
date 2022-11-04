<?php
/***************************************************************************
 *                              linkdb.php
 *                            --------------
 *   begin                : Saturday, Feb 13, 2001
 *   copyright            : (C) 2001 The phpBB Group
 *   email                : support@phpbb.com
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

define('IN_PHPBB', true);

$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_LINKDB);
init_userprefs($userdata);
//
// End session management
//

//
// Include the common file
//

include($phpbb_root_path . 'mods/linkdb/includes/linkdb_common.'.$phpEx);

//
// Get action variable other wise set it to the main
//

$action = ( isset($_REQUEST['action']) ) ? htmlspecialchars($_REQUEST['action']) : 'main';

//
// an array of all expected actions
//

$actions = array('category' => 'category',
				 'link' => 'link',
				 'viewall' => 'viewall',
				 'search' => 'search',
				 'rate' => 'rate',
				 'comment' => 'comment',
				 'user_upload' => 'user_upload',
				 'main' => 'main');

//
// Lets Build the page
//

$linkdb->module($actions[$action]);
$linkdb->modules[$actions[$action]]->main($action);

include($phpbb_root_path . 'includes/page_header.'.$phpEx);

//
// page header for linkdb
//
if($action == 'category')
{
	$upload_url = append_sid("linkdb.$phpEx?action=user_upload&cat_id={$_REQUEST['cat_id']}");
}
else
{
	$upload_url = append_sid("linkdb.$phpEx?action=user_upload");
}
	
if(!$linkdb_config['lock_submit_site'] || $userdata['user_level'] == ADMIN)
{
	$template->assign_block_vars('ADD_LINK', array());
}

$template->set_filenames(array(
	'link_header' => LINKDB_TPL_PATH . "link_header.tpl")
);

$template->assign_vars(array(
	'L_SEARCH' => $lang['Link_Search'],
	'L_UPLOAD' => $lang['AddLink'],
	'L_VIEW_ALL' => $lang['Viewall'],
	'L_DESCEND_BY_HITS' => $lang['Descend_by_hits'],
	'L_DESCEND_BY_JOIN' => $lang['Descend_by_joindate'],
	
	'U_PASEARCH' => append_sid("linkdb.$phpEx?action=search"),
	'U_UPLOAD' => $upload_url,
	'U_VIEW_ALL' => append_sid("linkdb.$phpEx?action=viewall"),
	'U_DESCEND_BY_HITS' => append_sid("linkdb.$phpEx?action=viewall&&sort_method=link_hits&sort_order=DESC"),
	'U_DESCEND_BY_JOIN' => append_sid("linkdb.$phpEx?action=viewall&sort_method=link_time&sort_order=DESC"),
	
	'L_JUMP' =>  $lang['Jump'],
	'JUMPMENU' => $linkdb->modules[$linkdb->module_name]->jumpmenu_option(),
	'S_JUMPBOX_ACTION' => append_sid("linkdb.$phpEx"))
);

$template->pparse('link_header');

//
// page body for linkdb 
//
$template->set_filenames(array(
	'body' => LINKDB_TPL_PATH . $linkdb_tpl_name)
);

//
// Force password update
//
if ($board_config['password_update_days'])
{
	include($phpbb_root_path . 'includes/update_password.'.$phpEx);
}

$template->pparse('body');

//
// page footer for linkdb 
//
$template->set_filenames(array(
	'link_footer' => LINKDB_TPL_PATH .  "link_footer.tpl")
);

$template->assign_vars(array(
	'LINKDB_VERSION' => $linkdb_config['linkdb_versions'])
);

$linkdb->modules[$linkdb->module_name]->_linkdb();
	
$template->pparse('link_footer');

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>