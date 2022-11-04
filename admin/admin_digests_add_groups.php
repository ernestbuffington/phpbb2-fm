<?php
/***************************************************************************
 *                           admin_digests_add_groups.php
 *                           ----------------------------
 *   begin                : Monday, Jul 12, 2004
 *   copyright            : (C) 2001 The phpBB Group
 *   email                : support@phpbb.com
 *
 *   $Id:
 *
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

if(!empty($setmodules))
{
	$filename = basename(__FILE__);
	$module['Email_Digests']['Add Group Digest'] = $filename;
	return;
}

$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);
include($phpbb_root_path . 'digests_common.'.$phpEx);

$params = array('mode' => 'mode', 'group_id' => 'group_id', 'group_valid' => 'group_valid');

while(list($var, $param) = @each($params))
{
	if (!empty($HTTP_POST_VARS[$param]) || !empty($HTTP_GET_VARS[$param]))
	{
		$$var = (!empty($HTTP_POST_VARS[$param])) ? htmlspecialchars($HTTP_POST_VARS[$param]) : htmlspecialchars($HTTP_GET_VARS[$param]);
	}
	else
	{
		$$var = "";
	}
}

$template->set_filenames(array(
	'body' => 'admin/auth_select_body.tpl')
);

if (($mode == 'lookup') && ($group_valid == TRUE))
{
	$template->assign_vars(array(
		"META" => '<meta http-equiv="refresh" content="0; url=' . append_sid($phpbb_root_path . "digests." . $phpEx . "?user_id=$group_id&mode=admin&digest_type=1") . '">')
	);

	$template->set_filenames(array(
		'header' => 'admin/page_header.tpl')
	);

	$template->pparse('header');
}

$ug_list = ug_select('', 'group_id');

$group_valid = ($ug_list != $lang['Digest_no_groups']) ? TRUE : FALSE;

$template->assign_vars(array(
	'L_AUTH_TITLE' => $lang['Digest_group_title'],
	'L_AUTH_EXPLAIN' => $lang['Digest_group_explain'],
	'L_AUTH_SELECT' => $lang['Select_group'],
	'L_LOOK_UP' => $lang['Digest_select_group'],

	'S_AUTH_SELECT' => $ug_list,
	'S_AUTH_ACTION' => append_sid("admin_digests_add_groups.$phpEx?mode=lookup&group_valid=$group_valid"),
	)
);

$template->pparse('body');

include('./page_footer_admin.'.$phpEx);

?>