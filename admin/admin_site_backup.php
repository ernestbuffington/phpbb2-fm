<?php

define('IN_PHPBB', 1);

//
// Let's set the root dir for phpBB
//
$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);

$template->set_filenames(array(
	'body' => 'admin/iframe_body.tpl')
);

$template->assign_vars(array(
	'IFRAME_MENU' => $utils_menu . $log_menu . $db_menu . $lang_menu,
	'IFRAME_URL' => append_sid('backup/index.'.$phpEx))
);

$template->pparse('body');

include('./page_footer_admin.'.$phpEx);

?>