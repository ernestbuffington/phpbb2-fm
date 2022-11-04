<?php

define('IN_PHPBB', true);

if (!empty($setmodules))
{
	$filename = basename(__FILE__);
	$module['pukapuka MODs']['Google Bot Detector'] = $filename;
	return;
}

//
// Let's set the root dir for phpBB
//
$phpbb_root_path = './../';
require($phpbb_root_path . 'extension.inc');
require('pagestart.' . $phpEx);

if (isset($HTTP_POST_VARS['clear']))
{
	$sql = "DELETE FROM " . BOTS_ARCHIVE_TABLE;
	if (!$db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not delete spider / robot logs', '', __LINE__, __FILE__, $sql);
	}
	
	$message = $lang['Bot_log_deleted'] . "<br /><br />" . sprintf($lang['Click_return_bot_log'], '<a href="' . append_sid('admin_logs_bots.'.$phpEx) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.'.$phpEx) . '">', '</a>');

	message_die(GENERAL_MESSAGE, $message);
}

$start = (isset($_GET['start'])) ? intval($_GET['start']) : 0;

$template->set_filenames(array(
	'body' => 'admin/logs_bots_body.tpl')
);

$sql = "SELECT * 
	FROM " . BOTS_ARCHIVE_TABLE . " 
	ORDER BY bot_time DESC";
if (!($result = $db->sql_query($sql)))
{
	message_die(GENERAL_ERROR, 'Could not obtain spider / robot logs', '', __LINE__, __FILE__, $sql);
	unset($total_row);
}
else
{
	$total_row = $db->sql_numrows($result);
}

if (isset($total_row))
{
	$pagination = generate_pagination(append_sid('admin_logs_bots.'.$phpEx), $total_row, $board_config['posts_per_page'], $start) . '&nbsp;';
}

$db->sql_freeresult($result);

$sql .= " LIMIT " .$start . ", " . $board_config['posts_per_page'];
if (!($result = $db->sql_query($sql)))
{
	message_die(GENERAL_ERROR, 'Could not obtain spider / robot logs', '', __LINE__, __FILE__, $sql);
}

if ($row = $db->sql_fetchrow($result))
{
	$i = $start;
	do
	{
		$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

		$template->assign_block_vars('bots', array(
			'ROW_CLASS' => $row_class,
			'BOT_NAME' => $row['bot_name'],
			'BOT_TIME' => sprintf(create_date($board_config['default_dateformat'], $row['bot_time'], $board_config['board_timezone'])),
			'BOT_URL' => $row['bot_url'])
		);
		$i++;
	}
	while ($row = $db->sql_fetchrow($result));

	$template->assign_vars(array(
		'PAGINATION' => $pagination,
		'PAGE_NUMBER' => sprintf($lang['Page_of'], (floor($start / $board_config['posts_per_page']) + 1), ceil($total_row / $board_config['posts_per_page'])))
	);
}
else
{
	$template->assign_block_vars('no_bots', array(
		'L_NONE' => $lang['None'])
	);
}

$template->assign_vars(array(
	'S_ACTION' => append_sid('admin_logs_bots.'.$phpEx),

	'L_DELETE_ALL' => $lang['Delete_all'], 
	'L_PAGE_TITLE' => $lang['Bot_logger'],
	'L_PAGE_EXPLAIN' => $lang['Bot_logger_explain'],
	'L_BOT_NAME' => $lang['Bot_name'],
	'L_BOT_DATE' => $lang['Date'],
	'L_BOT_URL' => $lang['Referer_url'])
);

$template->pparse('body');

include('./page_footer_admin.'.$phpEx);

?>