<?php
/** 
*
* @package phpBB2
* @version $Id: jobs.php,v 1.1.3 zarath Exp $
* @copyright (c) 2003 Zarath
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

//
// Start page functions
//
function has_item($item, $type = 0)
{
	global $user_item_array, $user_itemid_array, $user_itemid2_array, $db, $userdata;

	if ( empty($user_item_array) )
	{
		$sql = "SELECT *
			FROM " . USER_ITEMS_TABLE . "
			WHERE user_id = " . $userdata['user_id'];
		if ( !( $result = $db->sql_query($sql) ) ) 
		{ 
			message_die(GENERAL_MESSAGE, 'Error fetching user items in function!'); 
		}
			
		if ( $count = $db->sql_numrows($result) )
		{
			$user_item_array = $user_itemid_array = $user_itemid2_array = array();

			for ( $i = 0; $i < $count; $i++)
			{
				$row = $db->sql_fetchrow($result);

				$user_item_array[] = $row['item_name'];
				$user_itemid_array[] = $row['id'];
				$user_itemid2_array[] = $row['item_id'];
			}
		}
	}

	if ( is_numeric($item) && ( ( @in_array($item, $user_itemid_array) && ($type == 1) ) || @in_array($item, $user_itemid2_array) ) ) 
	{ 
		return true; 
	}
	else if ( @in_array($item, $user_item_array) ) 
	{ 
		return true; 
	}
	else 
	{ 
		return false; 
	}
}

function requirements($requires)
{
	global $lang, $userdata;
	
	$currency_field = 'user_points';
	$currency = $board_config['points_name'];

	if (strlen($requires) > 3)
	{
		$rms = explode(';', $requires);
		for ($i = 0; $i < sizeof($rms); $i++)
		{
			$rms[$i] = trim($rms[$i]);

			if ( (substr($rms[$i], 0, 5) == 'admin') || (substr($rms[$i], 0, 3) == 'mod') )
			{
				if (substr($rms[$i], 0, 5) == 'admin' && $userdata['user_level'] != 1)
				{
					$error = 1;
					$msg .= $lang['jobs_requires_admin'] . '<br /><br />';
				}
				else if (substr($rms[$i], 0, 3) == 'mod' && $userdata['user_level'] == 0)
				{
					$error = 1;
					$msg .= $lang['jobs_requires_mod'] . '<br /><br />';
				}
			}
			else if ( substr($rms[$i], 0, 3) == 'sex' )
			{
				if ( substr($rms[$i], 3, 1) == '=' )
				{
					if ( substr($rms[$i], 4, 4) == 'male' && $userdata['user_gender'] != 1 )
					{
						$error = 1;
						$msg .= $lang['jobs_requires_male'] . '<br /><br />';
					}
					else if ( substr($rms[$i], 4, 6) == 'female' && $userdata['user_gender'] != 2 )
					{
						$error = 1;
						$msg .= $lang['jobs_requires_female'] . '<br /><br />';
					}
				}
				else if ( substr($rms[$i], 3, 1) == '!' )
				{
					if ( substr($rms[$i], 4, 4) == 'male' && $userdata['user_gender'] == 1 )
					{
						$error = 1;
						$msg .= $lang['jobs_requires_nmale'] . '<br /><br />';
					}
					else if ( substr($rms[$i], 4, 6) == 'female' && $userdata['user_gender'] == 2 )
					{
						$error = 1;
						$msg .= $lang['jobs_requires_nfemale'] . '<br /><br />';
					}
				}
			}
			else if ( substr($rms[$i], 0, 3) == 'gil' )
			{
				if ( (substr($rms[$i], 3, 1) == '=') && ($userdata[$currency_field] != substr($rms[$i], 4)) )
				{
					$error = 1;
					$msg .= sprintf($lang['jobs_requires_gil'], substr($rms[$i], 4), $currency) . '<br /><br />';
				}
				else if ( (substr($rms[$i], 3, 1) == '!') && ($userdata[$currency_field] == substr($rms[$i], 4)) )
				{
					$error = 1;
					$msg .= sprintf($lang['jobs_requires_ngil'], substr($rms[$i], 4), $currency) . '<br /><br />';
				}
				else if ( (substr($rms[$i], 3, 1) == '>') && ($userdata[$currency_field] <= substr($rms[$i], 4)) )
				{
					$error = 1;
					$msg .= sprintf($lang['jobs_requires_mgil'], substr($rms[$i], 4), $currency) . '<br /><br />';
				}
				else if ( (substr($rms[$i], 3, 1) == '<') && ($userdata[$currency_field] >= substr($rms[$i], 4)) )
				{
					$error = 1;
					$msg .= sprintf($lang['jobs_requires_lgil'], substr($rms[$i], 4), $currency) . '<br /><br />';
				}
			}
			else if ( substr($rms[$i], 0, 5) == 'posts' )
			{
				if ( (substr($rms[$i], 5, 1) == '=') && ($userdata['user_posts'] != substr($rms[$i], 6)) )
				{
					$error = 1;
					$msg .= sprintf($lang['jobs_requires_posts'], substr($rms[$i], 6)) . '<br /><br />';
				}
				else if ( (substr($rms[$i], 5, 1) == '!') && ($userdata['user_posts'] == substr($rms[$i], 6)) )
				{
					$error = 1;
					$msg .= sprintf($lang['jobs_requires_nposts'], substr($rms[$i], 6)) . '<br /><br />';
				}
				else if ( (substr($rms[$i], 5, 1) == '>') && ($userdata['user_posts'] <= substr($rms[$i], 6)) )
				{
					$error = 1;
					$msg .= sprintf($lang['jobs_requires_mposts'], substr($rms[$i], 6)) . '<br /><br />';
				}
				else if ( (substr($rms[$i], 5, 1) == '<') && ($userdata['user_posts'] >= substr($rms[$i], 6)) )
				{
					$error = 1;
					$msg .= sprintf($lang['jobs_requires_lposts'], substr($rms[$i], 6)) . '<br /><br />';
				}
			}
			else if ( substr($rms[$i], 0, 4) == 'item')
			{
				if ( (substr($rms[$i], 4, 1) == '=') && ( ( !defined('USER_ITEMS_TABLE') && substr_count($userdata['user_items'], 'ß' . substr($rms[$i], 5) . 'Þ') < 1 ) || !(has_item(substr($rms[$i], 5))) ))
				{
					$error = 1;
					$msg .= sprintf($lang['jobs_requires_item'], substr($rms[$i], 5)) . '<br /><br />';
				}
				else if ( (substr($rms[$i], 4, 1) == '!') && ( ( !(defined('USER_ITEMS_TABLE')) && (substr_count($userdata['user_items'], 'ß' . substr($rms[$i], 5) . 'Þ') > 0) ) || has_item(substr($rms[$i], 5)) ))
				{
					$error = 1;
					$msg .= sprintf($lang['jobs_requires_nitem'], substr($rms[$i], 5)) . '<br /><br />';
				}
			}
		}
	}
	
	if ($error) 
	{ 
		return $msg; 
	}
	else 
	{ 
		return 0; 
	}
}
//
// End page functions
//

define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.' . $phpEx);

//
// Include language files
//
$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_jobs.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_jobs.' . $phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_JOBS);
init_userprefs($userdata);
//
// End session management
//

//
// Start register variables
//
if ( isset($HTTP_GET_VARS['action']) || isset($HTTP_POST_VARS['action']) ) 
{ 
	$action = ( isset($HTTP_POST_VARS['action']) ) ? htmlspecialchars($HTTP_POST_VARS['action']) : htmlspecialchars($HTTP_GET_VARS['action']);
}
else 
{ 
	$action = ''; 
}

$user_id = ( isset($HTTP_GET_VARS['user_id']) ) ? intval($HTTP_GET_VARS['user_id']) : 0;

if ( !$userdata['session_logged_in'] )
{
	$redirect = 'jobs.'.$phpEx;
	$redirect .= ( isset($user_id) ) ? '&user_id=' . $user_id : '';
	header('Location: ' . append_sid("login.$phpEx?redirect=$redirect", true));
}

if ( !$board_config['jobs_status'] )
{ 
	message_die(GENERAL_MESSAGE, $lang['jobs_error_disabled']);
}

if ( empty($action) )
{
	if ( $board_config['jobs_index_body'] )
	{
		$template->set_filenames(array(
			'body' => 'jobs_extended_body.tpl')
		);
	}
	else
	{
		$template->set_filenames(array(
			'body' => 'jobs_body.tpl')
		);
	}
	make_jumpbox('viewforum.'.$phpEx);

	$sql = "SELECT * 
		FROM " . EMPLOYED_TABLE . "
		WHERE user_id = " . $userdata['user_id'];
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, sprintf($lang['jobs_error_selecting'], 'employed'), '', __LINE__, __FILE__, $sql);
	}
	$sql_count = $db->sql_numrows($result);

	if ($sql_count > 0)
	{
		for ($i = 0; $i < $sql_count; $i++)
		{
			if (!( $row = $db->sql_fetchrow($result) ))
			{
				message_die(GENERAL_ERROR, sprintf($lang['jobs_error_selecting'], 'jobs'), '', __LINE__, __FILE__, $sql);
			}
	
			$last_paid = ( $row['job_started'] == $row['last_paid'] ) ? 'Never' : create_date($board_config['default_dateformat'], $row['last_paid'], $board_config['board_timezone']);

			$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
			
			$template->assign_block_vars('listrow', array(
				'ROW' => $row_class,
				'S_MODE_ACTION' => append_sid('jobs.'.$phpEx . '?action=quit&amp;job=' . $row['job_name']),
				'JOB_PAY' => $row['job_pay'],
				'JOB_LENGTH' => duration($row['job_length']),
				'JOB_LAST_PAID' => $last_paid,
				'JOB_STARTED' => create_date($board_config['default_dateformat'], $row['job_started'], $board_config['board_timezone']),
				'JOB_NAME' => ucwords($row['job_name']))
			);
			
			$current_jobs .= (empty($current_jobs)) ? "'" . addslashes($row['job_name']) . "'" : ", '" . addslashes($row['job_name']) . "'";
		}
		$template->assign_block_vars('switch_has_job', array());
	}
	else
	{
		$template->assign_block_vars('switch_has_no_job', array());
	}

	if (strlen($current_jobs) < 3) 
	{ 
		$current_jobs = "'null'"; 
	}

	if ($sql_count < $board_config['jobs_limit'])
	{
		$sql = "SELECT * 
			FROM " . JOBS_TABLE . "
			WHERE type = 'public'
				AND name NOT IN ($current_jobs)";
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

			$sql = "SELECT * 
				FROM " . EMPLOYED_TABLE . "
				WHERE job_name= '" . $row['name'] . "'";
			if ( !($result2 = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, sprintf($lang['jobs_error_selecting'], 'jobs'), '', __LINE__, __FILE__, $sql);
			}
			$sql_count2 = $db->sql_numrows($result2);

			$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

			if ( $sql_count2 < $row['positions'] )
			{
				$template->assign_block_vars('listrow2', array(
					'ROW' => $row_class,
					'S_MODE_ACTION' => append_sid('jobs.' . $phpEx . '?action=start&amp;job=' . $row['id']),
					'JOB_ID' => $row['id'],
					'JOB_PAY' => $row['pay'],
					'JOB_PAY_TIME' => duration($row['payouttime']),
					'JOB_POSITIONS' => $row['positions'],
					'JOB_LEFT' => ($row['positions'] - $sql_count2),
					'JOB_NAME' => $row['name'])
				);
				
				$can_get_job = 1;
			}
		}
		
		if ($can_get_job)
		{
			$template->assign_block_vars('switch_can_get_job', array());
		}
		else
		{
			$template->assign_block_vars('switch_cant_get_job', array());
		}
	}
	else
	{
		$template->assign_block_vars('switch_cant_get_job', array());
	}

	$sql = "SELECT COUNT(DISTINCT t2.user_id) AS total_employed, COUNT(t2.id) AS total_jobs_taken, SUM(t1.positions) AS total_positions, COUNT(t1.name) AS total_jobs 
		FROM " . JOBS_TABLE . " as t1, " . EMPLOYED_TABLE . " as t2
		GROUP BY t1.id";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, sprintf($lang['jobs_error_selecting'], 'jobs/employed'), '', __LINE__, __FILE__, $sql);
	}
	$sql_count = $db->sql_numrows($result);
	
	if ($sql_count > 0)
	{
		if (!( $row = $db->sql_fetchrow($result) ))
		{
			message_die(GENERAL_ERROR, sprintf($lang['jobs_error_selecting'], 'jobs/employed'), '', __LINE__, __FILE__, $sql);
		}
	}
	else
	{
		$sql = "SELECT SUM(positions) AS total_positions, COUNT(name) AS total_jobs 
			FROM " . JOBS_TABLE . "
			GROUP by id";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, sprintf($lang['jobs_error_selecting'], 'jobs/employed'), '', __LINE__, __FILE__, $sql);
		}
		$sql_count = $db->sql_numrows($result);
		
		if ($sql_count > 0)
		{
			if (!( $row = $db->sql_fetchrow($result) ))
			{
				message_die(GENERAL_ERROR, sprintf($lang['jobs_error_selecting'], 'jobs/employed'), '', __LINE__, __FILE__, $sql);
			}
		}
		else
		{
			$row['total_positions'] = 0;
			$row['total_jobs'] = 0;
		}
	}

	$total_jobs = $row['total_jobs'];
	$total_employed = ($row['total_employed']) ? $row['total_employed'] : 0;
	$total_positions = $row['total_positions'];

	$sql = "SELECT COUNT(id) AS total_jobs_taken
		FROM " . EMPLOYED_TABLE;
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, sprintf($lang['jobs_error_selecting'], 'jobs/employed'), '', __LINE__, __FILE__, $sql);
	}
	$sql_count = $db->sql_numrows($result);
	
	if ($sql_count > 0)
	{
		if (!( $row = $db->sql_fetchrow($result) ))
		{
			message_die(GENERAL_ERROR, sprintf($lang['jobs_error_selecting'], 'jobs/employed'), '', __LINE__, __FILE__, $sql);
		}
	}
	else
	{
		$row['total_jobs_taken'] = 0;
	}
	
	$total_jobs_taken = $row['total_jobs_taken'];
	$total_free_jobs = $total_positions - $total_jobs_taken;

	$page_title = $lang['jobs_information'];

	$template->assign_vars(array(
		'S_MODE_ACTION' => append_sid('jobs.'.$phpEx),

		'LOCATION' => $location,
		'TOTAL_JOBS' => $total_jobs,
		'TOTAL_JOB_POSITIONS' => $total_positions,
		'TOTAL_EMPLOYED' => $total_employed,
		'TOTAL_JOBS_TAKEN' => $total_jobs_taken,
		'TOTAL_FREE_JOBS' => $total_free_jobs,

		'L_STATISTIC' => $lang['Statistic'],
		'L_VALUE' => $lang['Value'],
		'L_TITLE' => $lang['jobs_information'],
		'L_CURRENT_JOBS' => $lang['jobs_current'],
		'L_AVAILABLE_JOBS' => $lang['jobs_available'],
		'L_YOURE_UNEMPLOYED' => $lang['jobs_youre_unemployed'],
		'L_CANT_BE_EMPLOYED' => $lang['jobs_cant_be_employed'],

		'L_B_ACCEPT' => $lang['jobs_button_accept'],
		'L_B_QUIT' => $lang['jobs_button_quit'],
		'L_JOB' => $lang['jobs_job'],
		'L_PAY' => $lang['jobs_pay'],
		'L_PAY_LENGTH' => $lang['jobs_pay_length'],
		'L_STARTED' => $lang['jobs_started'],
		'L_LAST_PAID' => $lang['jobs_last_paid'],
		'L_POSITIONS' => $lang['jobs_positions'],

		'L_REMAINING' => $lang['jobs_remaining_positions'],
		'L_TAKEN' => $lang['jobs_taken_positions'],
		'L_EMPLOYED' => $lang['jobs_total_employed'],
		'L_TOTAL_POSITIONS' => $lang['jobs_total_positions'],
		'L_TOTAL_JOBS' => $lang['jobs_total_jobs'])
	);
}
else if ($action == 'start')
{
	if ( isset($HTTP_GET_VARS['job']) || isset($HTTP_POST_VARS['job']) ) 
	{ 
		$job = ( isset($HTTP_POST_VARS['job']) ) ? $HTTP_POST_VARS['job'] : $HTTP_GET_VARS['job']; 
	}
	else 
	{ 
		message_die(GENERAL_MESSAGE, sprintf($lang['jobs_error_variable'], 'job')); 
	}

	$sql = "SELECT * 
		FROM " . JOBS_TABLE . "
		WHERE id = '$job'";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, sprintf($lang['jobs_error_selecting'], 'jobs'), '', __LINE__, __FILE__, $sql);
	}
	$sql_count = $db->sql_numrows($result);

	if (!($sql_count)) 
	{ 
		message_die(GENERAL_MESSAGE, $lang['jobs_not_exist'] . '<br /><br />' . sprintf($lang['Click_return_jobs'], '<a href="' . append_sid('jobs.'.$phpEx) . '">', '</a>')); 
	}

	if (!( $row = $db->sql_fetchrow($result) ))
	{
		message_die(GENERAL_ERROR, sprintf($lang['jobs_error_selecting'], 'jobs'), '', __LINE__, __FILE__, $sql);
	}

	$sql = "SELECT * 
		FROM " . EMPLOYED_TABLE . "
		WHERE user_id = " . $userdata['user_id'];
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, sprintf($lang['jobs_error_selecting'], 'employed'), '', __LINE__, __FILE__, $sql);
	}
	$sql_count = $db->sql_numrows($result);

	if ( $sql_count >= $board_config['jobs_limit'] ) 
	{ 
		message_die(GENERAL_MESSAGE, $lang['jobs_too_many'] . '<br /><br />' . sprintf($lang['Click_return_jobs'], '<a href="' . append_sid('jobs.'.$phpEx) . '">', '</a>')); 
	}
	
	if ($row['type'] != 'public') 
	{ 
		message_die(GENERAL_MESSAGE, $lang['jobs_not_public'] . '<br /><br />' . sprintf($lang['Click_return_jobs'], '<a href="' . append_sid('jobs.'.$phpEx) . '">', '</a>')); 
	}

	$sql = "SELECT * 
		FROM " . EMPLOYED_TABLE . "
		WHERE job_name = '" . $row['name'] . "'";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, sprintf($lang['jobs_error_selecting'], 'employment'), '', __LINE__, __FILE__, $sql);
	}
	$sql_count = $db->sql_numrows($result);

	if ($sql_count >= $row['positions']) 
	{ 
		message_die(GENERAL_MESSAGE, $lang['jobs_no_positions'] . '<br /><br />' . sprintf($lang['Click_return_jobs'], '<a href="' . append_sid('jobs.'.$phpEx) . '">', '</a>')); 
	}

	//requirements check
	if (strlen($row['requires']) > 3)
	{
		$failed = requirements($row['requires']);
		if ( $failed )
		{
			message_die(GENERAL_MESSAGE, $failed); 
		}
	}	

	$sql = "SELECT * 
		FROM " . EMPLOYED_TABLE . " 
		WHERE job_name = '" . $row['name'] . "' 
			AND user_id = " . $userdata['user_id'];
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, $lang['jobs_error_temployed'], '', __LINE__, __FILE__, $sql);
	}
	$sql_count = $db->sql_numrows($result);

	if ($sql_count > 0) 
	{ 
		message_die(GENERAL_MESSAGE, $lang['jobs_already_employed'] . '<br /><br />' . sprintf($lang['Click_return_jobs'], '<a href="' . append_sid('jobs.'.$phpEx) . '">', '</a>')); 
	}

	$sql = "INSERT INTO " . EMPLOYED_TABLE . " (user_id, job_name, job_pay, job_length, last_paid, job_started)
		values(" . $userdata['user_id'] . ", '" . $row['name'] . "', '" . $row['pay'] . "', '" . $row['payouttime'] . "', " . time() . ", " . time() . ")";
	if ( !($db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, sprintf($lang['jobs_error_inserting'], 'employed'), '', __LINE__, __FILE__, $sql);
	}

	message_die(GENERAL_MESSAGE, sprintf($lang['jobs_now_employed'], $row['name']) . '<br /><br />' . sprintf($lang['Click_return_jobs'], '<a href="' . append_sid('jobs.'.$phpEx) . '">', '</a>'));
}
else if ($action == 'quit')
{
	if ( isset($HTTP_GET_VARS['job']) || isset($HTTP_POST_VARS['job']) ) 
	{ 
		$job = ( isset($HTTP_POST_VARS['job']) ) ? addslashes(stripslashes($HTTP_POST_VARS['job'])) : addslashes(stripslashes($HTTP_GET_VARS['job'])); 
	}
	else 
	{ 
		message_die(GENERAL_MESSAGE, "Could not job name variable!"); 
	}

	$sql = "SELECT * 
		FROM " . EMPLOYED_TABLE . " 
		WHERE job_name = '$job' 
			AND user_id = " . $userdata['user_id'];
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, sprintf($lang['jobs_error_selecting'], 'employed'), '', __LINE__, __FILE__, $sql);
	}
	$sql_count = $db->sql_numrows($result);

	if ($sql_count < 1) 
	{ 
		message_die(GENERAL_MESSAGE, $lang['jobs_not_employed'] . '<br /><br />' . sprintf($lang['Click_return_jobs'], '<a href="' . append_sid('jobs.'.$phpEx) . '">', '</a>')); 
	}

	$sql = "DELETE
		FROM " . EMPLOYED_TABLE . "
		WHERE job_name = '$job' 
			AND user_id = " . $userdata['user_id'];
	if ( !($db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, sprintf($lang['jobs_error_updating'], 'employed'), '', __LINE__, __FILE__, $sql);
	}

	message_die(GENERAL_MESSAGE, sprintf($lang['jobs_quit'], $job) . '<br /><br />' . sprintf($lang['Click_return_jobs'], '<a href="' . append_sid('jobs.'.$phpEx) . '">', '</a>'));
}
else 
{ 
	message_die(GENERAL_MESSAGE, "Invalid Action!"); 
}

//
// Generate the page
//
include($phpbb_root_path . 'includes/page_header.' . $phpEx);

//
// Force password update
//
if ($board_config['password_update_days'])
{
	include($phpbb_root_path . 'includes/update_password.'.$phpEx);
}

$template->pparse('body');

include($phpbb_root_path . 'includes/page_tail.' . $phpEx);

?>