<?php
/** 
*
* @package admin
* @version $Id: admin_jobs.php,v 1.1.3 2006/02/10 22:19:01 zarath Exp $
* @copyright (c) 2004 Zarath Technologies
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', 1);

if(	!empty($setmodules) )
{
	$file = basename(__FILE__);
//	$module['Points_sys_settings']['Job_Configuration'] = $file;
	return;
}

//
// Let's set the root dir for phpBB
//
$phpbb_root_path = '../';
require($phpbb_root_path . 'extension.inc');
require('pagestart.' . $phpEx);

//
// Include language files
//
$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_jobs.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_jobs.' . $phpEx);
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_admin_jobs.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_admin_jobs.' . $phpEx);

//
// Start register variables
//
if ( isset($HTTP_GET_VARS['action']) || isset($HTTP_POST_VARS['action']) ) 
{ 
	$action = ( isset($HTTP_POST_VARS['action']) ) ? $HTTP_POST_VARS['action'] : $HTTP_GET_VARS['action']; 
}
else 
{
	$action = ''; 
}
//
// End register variables
//

//
// Start job pages
//
if (empty($action))
{
	$template->set_filenames(array(
		'body' => 'admin/jobs_config_body.tpl')
	);

	$sql = "SELECT * 
		FROM " . JOBS_TABLE;
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, sprintf($lang['jobs_error_selecting'], 'jobs'), '', __LINE__, __FILE__, $sql);
	}
	$sql_count = $db->sql_numrows($result);

	for ($i = 0; $i < $sql_count; $i++)
	{
		if (!( $row = $db->sql_fetchrow($result) ))
		{
			message_die(GENERAL_ERROR, sprintf($lang['jobs_error_selecting'], 'jobs'), '', __LINE__, __FILE__, $sql);
		}
		
		$template->assign_block_vars('listrow', array(
			'JOB_ID' => $row['id'],
			'JOB_NAME' => $row['name'])
		);
	}

	$jobs_status_enabled = ( $board_config['jobs_status'] ) ? ' checked="checked"' : '';
	$jobs_status_disabled = ( !$board_config['jobs_status'] ) ? ' checked="checked"' : '';

	$pay_type_selected1 = ( !($board_config['jobs_pay_type']) ) ? ' selected="selected"' : '';
	$pay_type_selected2 = ( ($board_config['jobs_pay_type']) ) ? ' selected="selected"' : '';

	$index_selected1 = ( !($board_config['jobs_index_body']) ) ? ' selected="selected"' : '';
	$index_selected2 = ( ($board_config['jobs_index_body']) ) ? ' selected="selected"' : '';

	$viewtopic_yes = ( $board_config['jobs_viewtopic'] ) ? ' checked="checked"' : '';
	$viewtopic_no = ( !$board_config['jobs_viewtopic'] ) ? ' checked="checked"' : '';

	$template->assign_vars(array(
		'S_CONFIG_ACTION' => append_sid('admin_jobs.'.$phpEx),
		
		'L_PAGE_EXPLAIN' => $lang['jobs_explain_main'],
						
		'L_JOB_STATUS' => $lang['jobs_status'],
		'L_JOB_NAME' => $lang['jobs_job_name'],
		'L_MAX_POSITIONS' => $lang['jobs_job_positions'],
		'L_PAY_AMOUNT' => $lang['jobs_job_pay'],
		'L_PAY_TIME' => $lang['jobs_job_time'],
		'L_REQUIREMENTS' => $lang['jobs_job_requirements'],
		'L_TYPE' => $lang['jobs_job_type'],
		'L_SECONDS' => $lang['jobs_seconds'],
		'L_PRIVATE' => $lang['jobs_private'],
		'L_PUBLIC' => $lang['jobs_public'],
		'L_PAY_TYPE' => $lang['jobs_pay_out_type'],
		'L_MAX_JOBS' => $lang['jobs_max_pp'],
		'L_PAY_PP' => $lang['jobs_pay_pp'],
		'L_PAY_ALL' => $lang['jobs_pay_all'],
		'POINTS_NAME' => $board_config['points_name'],
		'L_USERNAME' => $lang['Username'],
			
		'L_CREATE_JOB' => $lang['jobs_button_job'],
		'L_EDIT_JOBS' => $lang['jobs_button_edit'],
		'L_FIND_USERNAME' => $lang['jobs_button_find'],
		'L_EDIT_JOB' => $lang['jobs_edit_job'],
		'L_JOB_INDEX' => $lang['jobs_index'],
		'L_JOB_VIEWTOPIC' => $lang['jobs_viewtopic'],
		'L_COMPACT' => $lang['jobs_compact'],
		'L_EXTENDED' => $lang['jobs_extended'],

		'JOBS_STATUS_ENABLED' => $jobs_status_enabled,
		'JOBS_STATUS_DISABLED' => $jobs_status_disabled,
		'INDEX_1' => $index_selected1,
		'INDEX_2' => $index_selected2,
		'PAY_TYPE_1' => $pay_type_selected1,
		'PAY_TYPE_2' => $pay_type_selected2,
		'MAX_JOBS' => $board_config['jobs_limit'],
		'VIEWTOPIC_YES' => $viewtopic_yes,
		'VIEWTOPIC_NO' => $viewtopic_no,

		'TABLE_TITLE' => $lang['Jobs'] . ' ' . $lang['Setting'],
		'USER_TABLE_TITLE' => $lang['jobs_edit_jobs'],
		'JOB_TABLE_TITLE' => $lang['jobs_edit_jobs_settings'],
		'ADD_TABLE_TITLE' => $lang['jobs_create_new'])
	);
}
else if ($action == 'edit_user')
{
	if ( isset($HTTP_GET_VARS['username']) || isset($HTTP_POST_VARS['username']) ) 
	{ 
		$username = ( isset($HTTP_POST_VARS['username']) ) ? $HTTP_POST_VARS['username'] : $HTTP_GET_VARS['username']; 
	}
	else 
	{ 
		message_die(GENERAL_MESSAGE, sprintf($lang['jobs_error_variable'], 'name')); 
	}

	$template->set_filenames(array(
		'body' => 'admin/jobs_user_body.tpl')
	);

	$sql = "SELECT * 
		FROM " . JOBS_TABLE;
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, sprintf($lang['jobs_error_selecting'], 'jobs'), '', __LINE__, __FILE__, $sql);
	}
	$sql_count = $db->sql_numrows($result);

	for ($i = 0; $i < $sql_count; $i++)
	{
		if (!( $row = $db->sql_fetchrow($result) ))
		{
			message_die(GENERAL_ERROR, sprintf($lang['jobs_error_selecting'], 'jobs'), '', __LINE__, __FILE__, $sql);
		}
		
		$template->assign_block_vars('listrow', array(
			'JOB_NAME' => $row['name'])
		);
	}

	$sql = "SELECT user_id
		FROM " . USERS_TABLE . "
		WHERE username = '$username'";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, sprintf($lang['jobs_error_selecting'], 'users'), '', __LINE__, __FILE__, $sql);
	}
	$sql_count = $db->sql_numrows($result);

	if ($sql_count < 1) 
	{ 
		message_die(GENERAL_MESSAGE, "No such user exists!"); 
	}
	else
	{
		if (!( $row = $db->sql_fetchrow($result) ))
		{
			message_die(GENERAL_ERROR, sprintf($lang['jobs_error_selecting'], 'users'), '', __LINE__, __FILE__, $sql);
		}
	}
	
	$sql = "SELECT *
		FROM " . EMPLOYED_TABLE . "
		WHERE user_id = " . $row['user_id'];
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, sprintf($lang['jobs_error_selecting'], 'employed'), '', __LINE__, __FILE__, $sql);
	}
	$sql_count = $db->sql_numrows($result);

	for ($i = 0; $i < $sql_count; $i++)
	{
		if (!( $row = $db->sql_fetchrow($result) ))
		{
			message_die(GENERAL_ERROR, sprintf($lang['jobs_error_selecting'], 'employed'), '', __LINE__, __FILE__, $sql);
		}
		
		$template->assign_block_vars('listrow2', array(
			'JOB_NAME' => $row['job_name'])
		);
	}

	$template->assign_vars(array(
		'S_CONFIG_ACTION' => append_sid('admin_jobs.'.$phpEx),

		'L_ADD_JOB' => $lang['jobs_button_add'],
		'L_FIRE' => $lang['jobs_button_fire'],

		'USER_ID' => $row['user_id'],

		'TITLE' => $lang['jobs_edit_jobs'],
		'EXPLAIN' => $lang['jobs_explain_user'])
	);
}
else if ($action == 'updateuser')
{
	if ( isset($HTTP_GET_VARS['userid']) || isset($HTTP_POST_VARS['userid']) ) 
	{
		$userid = ( isset($HTTP_POST_VARS['userid']) ) ? $HTTP_POST_VARS['userid'] : $HTTP_GET_VARS['userid']; 
	}
	else 
	{ 
		message_die(GENERAL_MESSAGE, sprintf($lang['jobs_error_variable'], 'user_id')); 
	}
	if ( isset($HTTP_GET_VARS['job']) || isset($HTTP_POST_VARS['job']) ) 
	{ 
		$job = ( isset($HTTP_POST_VARS['job']) ) ? $HTTP_POST_VARS['job'] : $HTTP_GET_VARS['job']; 
	}
	else 
	{ 
		message_die(GENERAL_MESSAGE, sprintf($lang['jobs_error_variable'], 'job')); 
	}

	if ( isset($HTTP_GET_VARS['addjob']) || isset($HTTP_POST_VARS['addjob']) ) 
	{ 
		$addjob = ( isset($HTTP_POST_VARS['addjob']) ) ? $HTTP_POST_VARS['addjob'] : $HTTP_GET_VARS['addjob']; 
	}
	if ( isset($HTTP_GET_VARS['remjob']) || isset($HTTP_POST_VARS['remjob']) ) 
	{ 
		$remjob = ( isset($HTTP_POST_VARS['remjob']) ) ? $HTTP_POST_VARS['remjob'] : $HTTP_GET_VARS['remjob']; 
	}

	$sql = "SELECT *
		FROM " . EMPLOYED_TABLE . "
		WHERE user_id = '$userid'
			AND job_name = '$job'";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, sprintf($lang['jobs_error_selecting'], 'employed'), '', __LINE__, __FILE__, $sql);
	}
	$sql_count = $db->sql_numrows($result);

	if (!empty($addjob))
	{
		if ($sql_count)
		{
			message_die(GENERAL_MESSAGE, $lang['jobs_already_has_one']);
		}
		else
		{
			$sql = "SELECT *
				FROM " . JOBS_TABLE . "
				WHERE name = '$job'";
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, sprintf($lang['jobs_error_selecting'], 'employed'), '', __LINE__, __FILE__, $sql);
			}
			
			if (!( $row = $db->sql_fetchrow($result) ))
			{
				message_die(GENERAL_ERROR, sprintf($lang['jobs_error_selecting'], 'jobs'), '', __LINE__, __FILE__, $sql);
			}

			$sql = "INSERT INTO " . EMPLOYED_TABLE . " (user_id, job_name, job_pay, job_length, last_paid)
				VALUES ($userid, '" . $row['name'] . "', '" . $row['pay'] . "', '" . $row['payouttime'] . "', " . time() . ")";
		}
	}
	elseif (!empty($remjob))
	{
		if (!($sql_count))
		{
			message_die(GENERAL_MESSAGE, $lang['jobs_dont_have']);
		}
		else
		{
			$sql = "DELETE
				FROM " . EMPLOYED_TABLE . "
				WHERE user_id = '$userid'
					AND job_name = '$job'";
		}
	}
	else 
	{ 
		message_die(GENERAL_MESSAGE, $lang['jobs_no_action']); 
	}

	if ( !($db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, sprintf($lang['jobs_error_updating'], 'employed'), '', __LINE__, __FILE__, $sql);
	}

	message_die(GENERAL_MESSAGE, $lang['jobs_user_updated'] . '<br /><br />' . sprintf($lang['jobs_main_link'], "<a href=\"" . append_sid("admin_jobs.$phpEx") . "\">", "</a>") . '<br /><br />' . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>"));
}
else if ($action == 'createjob')
{
	if ( isset($HTTP_GET_VARS['paytime']) || isset($HTTP_POST_VARS['paytime']) ) 
	{ 
		$paytime = ( isset($HTTP_POST_VARS['paytime']) ) ? intval($HTTP_POST_VARS['paytime']) : intval($HTTP_GET_VARS['paytime']); 
	}
	if ( isset($HTTP_GET_VARS['positions']) || isset($HTTP_POST_VARS['positions']) ) 
	{ 
		$positions = ( isset($HTTP_POST_VARS['positions']) ) ? intval($HTTP_POST_VARS['positions']) : intval($HTTP_GET_VARS['positions']); 
	}
	if ( isset($HTTP_GET_VARS['pay']) || isset($HTTP_POST_VARS['pay']) ) 
	{ 
		$pay = ( isset($HTTP_POST_VARS['pay']) ) ? intval($HTTP_POST_VARS['pay']) : intval($HTTP_GET_VARS['pay']); 
	}
	if ( isset($HTTP_GET_VARS['requirements']) || isset($HTTP_POST_VARS['requirements']) ) 
	{ 
		$requirements = ( isset($HTTP_POST_VARS['requirements']) ) ? $HTTP_POST_VARS['requirements'] : $HTTP_GET_VARS['requirements']; 
	}
	if ( isset($HTTP_GET_VARS['name']) || isset($HTTP_POST_VARS['name']) ) 
	{ 
		$name = ( isset($HTTP_POST_VARS['name']) ) ? $HTTP_POST_VARS['name'] : $HTTP_GET_VARS['name']; 
	}
	if ( isset($HTTP_GET_VARS['jobtype']) || isset($HTTP_POST_VARS['jobtype']) ) 
	{ 
		$jobtype = ( isset($HTTP_POST_VARS['jobtype']) ) ? $HTTP_POST_VARS['jobtype'] : $HTTP_GET_VARS['jobtype']; 
	}

	if ( (!is_numeric($paytime)) || ($paytime < 0) ) 
	{ 
		$paytime = 500000; 
	}
	if ( (!is_numeric($positions)) || ($positions < 0) ) 
	{ 
		$positions = 0; 
	}
	if ( (!is_numeric($pay)) || ($pay < 0) ) 
	{ 
		$pay = 5; 
	}
	if (strlen($requirements) < 3) 
	{ 
		$requirements = "none"; 
	}
	if ( (strlen($name) > 30) || (strlen($name) < 3) ) 
	{ 
		message_die(GENERAL_MESSAGE, "Invalid Name!<br /><br />" . sprintf($lang['jobs_main_link'], "<a href=\"" . append_sid("admin_jobs.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>")); 
	}
	
	$jobtype = ( $jobtype != 'private' ) ? 'public' : 'private';

	$sql = "SELECT *
		FROM " . JOBS_TABLE . "
		WHERE name = '$name'";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, sprintf($lang['jobs_error_selecting'], 'employed'), '', __LINE__, __FILE__, $sql);
	}
	$sql_count = $db->sql_numrows($result);

	if ($sql_count) 
	{ 
		message_die(GENERAL_MESSAGE, $lang['jobs_already_exists']); 
	}

	$sql = "INSERT INTO " . JOBS_TABLE . " (name, positions, pay, payouttime, requires, type) 
		VALUES ('$name', '$positions', '$pay', '$paytime', '$requirements', '$jobtype')";
	if ( !($db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, sprintf($lang['jobs_error_inserting'], 'jobs'), '', __LINE__, __FILE__, $sql);
	}

	message_die(GENERAL_MESSAGE, $lang['jobs_created'] . "<br /><br />" . sprintf($lang['jobs_main_link'], "<a href=\"" . append_sid("admin_jobs.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>"));
}
else if ($action == 'editjob')
{
	if ( isset($HTTP_GET_VARS['job']) || isset($HTTP_POST_VARS['job']) ) 
	{ 
		$job = ( isset($HTTP_POST_VARS['job']) ) ? intval($HTTP_POST_VARS['job']) : intval($HTTP_GET_VARS['job']); 
	}

	$template->set_filenames(array(
		'body' => 'admin/jobs_edit_body.tpl')
	);

	$sql = "SELECT * 
		FROM " . JOBS_TABLE . " 
		WHERE id = '$job'";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, sprintf($lang['jobs_error_selecting'], 'jobs'), '', __LINE__, __FILE__, $sql);
	}
	if (!( $row = $db->sql_fetchrow($result) ))
	{
		message_die(GENERAL_ERROR, sprintf($lang['jobs_error_selecting'], 'jobs'), '', __LINE__, __FILE__, $sql);
	}

	$public_selected = ( $row['type'] == 'public' ) ? 'selected="selected"' : '';
	$private_selected = ( $row['type'] != 'public' ) ? 'selected="selected"' : '';

	$template->assign_vars(array(
		'S_CONFIG_ACTION' => append_sid('admin_jobs.'.$phpEx),

		'L_JOB_NAME' => $lang['jobs_job_name'],
		'L_MAX_POSITIONS' => $lang['jobs_job_positions'],
		'L_PAY_AMOUNT' => $lang['jobs_job_pay'],
		'L_PAY_TIME' => $lang['jobs_job_time'],
		'L_REQUIREMENTS' => $lang['jobs_job_requirements'],
		'L_TYPE' => $lang['jobs_job_type'],
		'L_SECONDS' => $lang['jobs_seconds'],
		'L_PRIVATE' => $lang['jobs_private'],
		'L_PUBLIC' => $lang['jobs_public'],

		'L_UPDATE_JOB' => $lang['jobs_button_update_job'],
		'L_DELETE_JOB' => $lang['jobs_button_delete_job'],

		'JOB_NAME' => $row['name'],
		'JOB_ID' => $row['id'],
		'JOB_POSITIONS' => $row['positions'],
		'JOB_PAY' => $row['pay'],
		'JOB_PAYTIME' => $row['payouttime'],
		'JOB_REQUIREMENTS' => $row['requires'],
		'JOB_PUBLIC_SELECTED' => $public_selected,
		'JOB_PRIVATE_SELECTED' => $private_selected,
		'POINTS_NAME' => $board_config['points_name'],

		'TABLE_TITLE' => $lang['jobs_edit_job'],
		'TITLE' => $lang['jobs_job'] . ' ' . $lang['Manage'],
		'EXPLAIN' => $lang['jobs_explain_jobs'])
	);
}
else if ($action == 'updatejob')
{
	if ( isset($HTTP_GET_VARS['jobid']) || isset($HTTP_POST_VARS['jobid']) ) 
	{ 
		$jobid = ( isset($HTTP_POST_VARS['jobid']) ) ? intval($HTTP_POST_VARS['jobid']) : intval($HTTP_GET_VARS['jobid']); 
	}
	if( isset($HTTP_GET_VARS['paytime']) || isset($HTTP_POST_VARS['paytime']) ) 
	{ 
		$paytime = ( isset($HTTP_POST_VARS['paytime']) ) ? intval($HTTP_POST_VARS['paytime']) : intval($HTTP_GET_VARS['paytime']); 
	}
	if( isset($HTTP_GET_VARS['positions']) || isset($HTTP_POST_VARS['positions']) ) 
	{ 
		$positions = ( isset($HTTP_POST_VARS['positions']) ) ? intval($HTTP_POST_VARS['positions']) : intval($HTTP_GET_VARS['positions']); 
	}
	if( isset($HTTP_GET_VARS['pay']) || isset($HTTP_POST_VARS['pay']) ) 
	{ 
		$pay = ( isset($HTTP_POST_VARS['pay']) ) ? intval($HTTP_POST_VARS['pay']) : intval($HTTP_GET_VARS['pay']); 
	}
	if( isset($HTTP_GET_VARS['requirements']) || isset($HTTP_POST_VARS['requirements']) ) 
	{ 
		$requirements = ( isset($HTTP_POST_VARS['requirements']) ) ? $HTTP_POST_VARS['requirements'] : $HTTP_GET_VARS['requirements']; 
	}
	if( isset($HTTP_GET_VARS['name']) || isset($HTTP_POST_VARS['name']) ) 
	{ 
		$name = ( isset($HTTP_POST_VARS['name']) ) ? $HTTP_POST_VARS['name'] : $HTTP_GET_VARS['name']; 
	}

	if( isset($HTTP_GET_VARS['delete']) || isset($HTTP_POST_VARS['delete']) ) 
	{ 
		$delete = ( isset($HTTP_POST_VARS['delete']) ) ? $HTTP_POST_VARS['delete'] : $HTTP_GET_VARS['delete']; 
	}

	if ( (!is_numeric($paytime)) || ($paytime < 0) ) 
	{ 
		$paytime = 500000; 
	}
	if ( (!is_numeric($positions)) || ($positions < 0) ) 
	{ 
		$positions = 0; 
	}
	if ( (!is_numeric($pay)) || ($pay < 0) ) 
	{ 
		$pay = 5; 
	}
	if (strlen($requirements) < 3) 
	{ 
		$requirements = "none"; 
	}
	if (strlen($name) > 30) 
	{ 
		message_die(GENERAL_MESSAGE, $lang['jobs_invalid_name'] . "<br /><br />" . sprintf($lang['jobs_main_link'], "<a href=\"" . append_sid("admin_jobs.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>")); 
	}

	$jobtype = ( $jobtype != 'private' ) ? 'public' : 'private';

	if (!empty($delete))
	{
		$sql = "SELECT * 
			FROM " . JOBS_TABLE . " 
			WHERE id = '$jobid'";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, sprintf($lang['jobs_error_selecting'], 'jobs'), '', __LINE__, __FILE__, $sql);
		}
		if (!( $row = $db->sql_fetchrow($result) ))
		{
			message_die(GENERAL_ERROR, sprintf($lang['jobs_error_selecting'], 'jobs'), '', __LINE__, __FILE__, $sql);
		}

		$sql = "DELETE 
			FROM " . EMPLOYED_TABLE . "
			WHERE job_name = '" . $row['name'] . "'";
		if ( !($db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, sprintf($lang['jobs_error_updating'], 'employed'), '', __LINE__, __FILE__, $sql);
		}
		
		$sql = "DELETE 
			FROM " . JOBS_TABLE . "
			WHERE id = " . $jobid;
		if ( !($db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, sprintf($lang['jobs_error_updating'], 'employed'), '', __LINE__, __FILE__, $sql);
		}

		message_die(GENERAL_MESSAGE, $lang['jobs_deleted'] . "<br /><br />" . sprintf($lang['jobs_main_link'], "<a href=\"" . append_sid("admin_jobs.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>"));
	}
	else
	{
		$sql = "SELECT name
			FROM " . JOBS_TABLE . "
			WHERE id = '$jobid'";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, sprintf($lang['jobs_error_selecting'], 'jobs'), '', __LINE__, __FILE__, $sql);
		}
		$sql_count = $db->sql_numrows($result);

		if ($sql_count < 1) 
		{ 
			message_die(GENERAL_MESSAGE, "No such job!"); 
		}

		if (!( $row = $db->sql_fetchrow($result) ))
		{
			message_die(GENERAL_ERROR, sprintf($lang['jobs_error_selecting'], 'jobs'), '', __LINE__, __FILE__, $sql);
		}

		$sql = "UPDATE " . EMPLOYED_TABLE . "
			SET job_name = '$name', job_pay = '$pay', job_length = '$paytime'
			WHERE job_name = '" . $row['name'] . "'";
		if ( !($db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, sprintf($lang['jobs_error_updating'], 'employed'), '', __LINE__, __FILE__, $sql);
		}

		$sql = "UPDATE " . JOBS_TABLE . "
			SET name = '$name', positions = '$positions', pay = '$pay', payouttime = '$paytime', requires = '$requirements', type = '$jobtype' 
			WHERE id = '$jobid'";
		if ( !($db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, sprintf($lang['jobs_error_updating'], 'jobs'), '', __LINE__, __FILE__, $sql);
		}

		message_die(GENERAL_MESSAGE, $lang['jobs_updated'] . "<br /><br />" . sprintf($lang['jobs_main_link'], "<a href=\"" . append_sid("admin_jobs.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>"));
	}
}
else if ($action == 'update_globals')
{
	if ( isset($HTTP_GET_VARS['jobs_status']) || isset($HTTP_POST_VARS['jobs_status']) ) 
	{ 
		$status = ( isset($HTTP_POST_VARS['jobs_status']) ) ? $HTTP_POST_VARS['jobs_status'] : $HTTP_GET_VARS['jobs_status']; 
	}
	else 
	{ 
		message_die(GENERAL_MESSAGE, sprintf($lang['jobs_error_variable'], 'jobs_status')); 
	}
	
	if ( isset($HTTP_GET_VARS['limit']) || isset($HTTP_POST_VARS['limit']) ) 
	{ 
		$limit = ( isset($HTTP_POST_VARS['limit']) ) ? $HTTP_POST_VARS['limit'] : $HTTP_GET_VARS['limit']; 
	}
	else 
	{ 
		message_die(GENERAL_MESSAGE, sprintf($lang['jobs_error_variable'], 'limit')); 
	}
	
	if ( isset($HTTP_GET_VARS['pay_type']) || isset($HTTP_POST_VARS['pay_type']) ) 
	{ 
		$pay_type = ( isset($HTTP_POST_VARS['pay_type']) ) ? $HTTP_POST_VARS['pay_type'] : $HTTP_GET_VARS['pay_type']; 
	}
	else 
	{ 
		message_die(GENERAL_MESSAGE, sprintf($lang['jobs_error_variable'], 'pay_type')); 
	}
	
	if ( isset($HTTP_GET_VARS['index_type']) || isset($HTTP_POST_VARS['index_type']) ) 
	{ 
		$index_type = ( isset($HTTP_POST_VARS['index_type']) ) ? $HTTP_POST_VARS['index_type'] : $HTTP_GET_VARS['index_type']; 
	}
	else 
	{ 
		message_die(GENERAL_MESSAGE, sprintf($lang['jobs_error_variable'], 'jobs_index_body')); 
	}

	if ( isset($HTTP_GET_VARS['jobs_viewtopic']) || isset($HTTP_POST_VARS['jobs_viewtopic']) ) 
	{ 
		$jobs_viewtopic = ( isset($HTTP_POST_VARS['jobs_viewtopic']) ) ? $HTTP_POST_VARS['jobs_viewtopic'] : $HTTP_GET_VARS['jobs_viewtopic']; 
	}
	else 
	{ 
		message_die(GENERAL_MESSAGE, sprintf($lang['jobs_error_variable'], 'jobs_viewtopic')); 
	}


	$sql = array();
	if ($index_type != $board_config['jobs_index_body'])
	{
		$sql[] = "UPDATE " . CONFIG_TABLE . " 
			SET config_value = '$index_type' 
			WHERE config_name = 'jobs_index_body'"; 
	}

	if ($status != $board_config['jobs_status'])
	{
		$sql[] = "UPDATE " . CONFIG_TABLE . " 
			SET config_value = '$status' 
			WHERE config_name = 'jobs_status'"; 
	}
	
	if ($limit != $board_config['jobs_limit'])
	{
		$sql[] = "UPDATE " . CONFIG_TABLE . " 
			SET config_value = '$limit' 
			WHERE config_name = 'jobs_limit'";
	}
	
	if ($limit != $board_config['jobs_pay_type'])
	{
		$sql[] = "UPDATE " . CONFIG_TABLE . " 
			SET config_value = '$pay_type' 
			WHERE config_name = 'jobs_pay_type'";
	}


	if ($jobs_viewtopic != $board_config['jobs_viewtopic'])
	{
		$sql[] = "UPDATE " . CONFIG_TABLE . " 
			SET config_value = '$jobs_viewtopic' 
			WHERE config_name = 'jobs_viewtopic'";
	}

	$sql_count = sizeof($sql);
	for($i = 0; $i < $sql_count; $i++)
	{
		if ( !($db->sql_query($sql[$i])) )
		{
			message_die(GENERAL_ERROR, sprintf($lang['jobs_error_updating'], 'configuration'), '', __LINE__, __FILE__, $sql);
		}
	}

	// Remove cache file
	@unlink($phpbb_root_path . 'cache/config_board.'.$phpEx);

	message_die(GENERAL_MESSAGE, $lang['jobs_global_updated'] . "<br /><br />" . sprintf($lang['jobs_main_link'], "<a href=\"" . append_sid("admin_jobs.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>"));
}
else 
{ 
	message_die(GENERAL_MESSAGE, $lang['jobs_invalid_action']); 
}

//
// Generate the page
//
$template->pparse('body');

include('page_footer_admin.' . $phpEx);

?>