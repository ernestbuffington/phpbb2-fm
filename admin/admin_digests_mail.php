<?php
/***************************************************************************
 *                            admin_digests_mail.php
 *                            ----------------------
 *   begin                : Saturday, Oct 23, 2004
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
	$module['Email_Digests']['Mail Digests'] = $filename;
	return;
}

$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);
include($phpbb_root_path . 'digests_common.'.$phpEx);

$params = array('mode' => 'mode');

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
	'body' => 'admin/digests_mail.tpl')
);

if ($mode == 'HTML')
{
	$template->assign_vars(array(
		"META" => '<meta http-equiv="refresh" content="0; url=' . append_sid($phpbb_root_path .  "/admin/mail_digests.$phpEx?mode=HTML") . '">')
	);

	$template->set_filenames(array(
		'header' => 'admin/page_header.tpl')
	);

	$template->pparse('header');
}

$template->assign_vars(array(
	'L_AUTH_TITLE' => $lang['Digest_mail_title'],
	'L_AUTH_EXPLAIN' => $lang['Digest_mail_explain'],
	'L_LOOK_UP' => $lang['Digest_run_mail'],

	'S_AUTH_ACTION' => append_sid("admin_digests_mail.$phpEx?mode=HTML"))
);

$template->pparse('body');

include('./page_footer_admin.'.$phpEx);

?>