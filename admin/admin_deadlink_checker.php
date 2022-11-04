<?php
/***************************************************************************
 *                              admin_link_checker.php
 *                            -------------------
 *   begin                : Thursday, Jul 12, 2001
 *   copyright            : (C) 2001 The phpBB Group
 *   email                : support@phpbb.com
 *
 *   $Id: admin_link_checker.php,v 1.51.2.9 2004/11/18 17:49:33 acydburn Exp $
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

define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	$module['Utilities_']['Dead_link_checker'] = $filename;
	return;
}

//
// Let's set the root dir for phpBB
//
$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);

$template->assign_vars(array( 
    'L_TITLE' => $lang['Dead_link_checker'],
	'L_EXPLAIN' => $lang['DL_explain'],	
	'L_URL' => $lang['DL_url']) 
); 

$template->set_filenames(array( 
	'body' => 'admin/utils_deadlink_checker_body.tpl') 
); 

$template->pparse('body'); 

include('./page_footer_admin.'.$phpEx);

?>