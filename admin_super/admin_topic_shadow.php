<?php
/** 
*
* @package admin
* @version $Id: admin_topic_shadow.php,v 1.6 2003/06/30 16:34:28 nivisec Exp $
* @copyright (c) 2002 Nivisec.com
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
	
define('IN_PHPBB', 1);

define('DISABLE_PREFERENCE_SAVING', false);
define('DISABLE_VERSION_CHECK', FALSE);
define('MOD_VERSION', '2.13');
define('MOD_CODE', 2);

if( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	$module['Forums']['Topic_shadows'] = $filename;
	
	return;
}

//
// Let's set the root dir for phpBB
//
$phpbb_root_path = "./../";

/****************************************************************************
/** Includes and cookie settings (with output buffering)
/***************************************************************************/
/* Make a new output buffer for this page in order to not screw up cookie
setting.  If this is disabled, settings will NEVER be saved */
if(!DISABLE_PREFERENCE_SAVING && !$board_config['gzip_compress']) ob_start();

require($phpbb_root_path . 'extension.inc');
(file_exists('pagestart.' . $phpEx)) ? require('pagestart.' . $phpEx) : require('pagestart.inc');
include_once($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_admin_topic_shadow.' . $phpEx);
require($phpbb_root_path . 'includes/functions_admin.' . $phpEx);

define('MOD_COOKIE_PREF_NAME', $board_config['cookie_name'] . '_topic_shadows');
@setcookie(MOD_COOKIE_PREF_NAME, serialize($preference_cookie), time() + 31536000, $board_config['cookie_path'], $board_config['cookie_domain'], $board_config['cookie_secure']);

/* Flush the output buffer to display the page header, if the ob_start() is
removed, this one must be removed as well or strange things will happen */
if(!DISABLE_PREFERENCE_SAVING && !$board_config['gzip_compress']) ob_end_flush();

/*******************************************************************************************
/** Get parameters.  'var_name' => 'default_value'
/** Also get any saved cookie preferences.
/******************************************************************************************/
$preference_cookie = (isset($HTTP_COOKIE_VARS[MOD_COOKIE_PREF_NAME])) ? unserialize(stripslashes($HTTP_COOKIE_VARS[MOD_COOKIE_PREF_NAME])) : array();
$preference_cookie['test'] = true;
$params = array('start' => 0, 'order' => 'DESC', 'mode' => 'topic_time', 'delete_all_before_date' => 0,
'del_month' => 1, 'del_day' => 1, 'del_year' => 1970);
$params_ignore = array('delete_all_before_date');

foreach($params as $var => $default)
{
	$$var = (isset($preference_cookie[MOD_CODE."_$var"]) && !in_array($var, $params_ignore)) ? $preference_cookie[MOD_CODE."_$var"] : $default;
	if(isset($HTTP_POST_VARS[$var]) || isset($HTTP_GET_VARS[$var]))
	{
		$preference_cookie[MOD_CODE."_$var"] = (isset($HTTP_POST_VARS[$var])) ? $HTTP_POST_VARS[$var] : $HTTP_GET_VARS[$var];
		$$var = $preference_cookie[MOD_CODE."_$var"];
	}
}

/****************************************************************************
/** Constants and Main Vars.
/***************************************************************************/
$mode_types = array('topic_time', 'topic_title');
$order_types = array('DESC', 'ASC');
$page_title = $lang['Topic_Shadow'];
$status_message = '';

/****************************************************************************
/** Functions
/***************************************************************************/
function topic_shadow_make_drop_box($prefix = 'mode')
{
	global $mode_types, $lang, $mode, $order_types, $order;
	
	$rval = '<select name="'.$prefix.'">';
	
	switch($prefix)
	{
		case 'mode':
		{
			foreach($mode_types as $val)
			{
				$selected = ($mode == $val) ? 'selected="selected"' : '';
				$rval .= "<option value=\"$val\" $selected>" . $lang[$val] . '</option>';
			}
			break;
		}
		case 'order':
		{
			foreach($order_types as $val)
			{
				$selected = ($order == $val) ? 'selected="selected"' : '';
				$rval .= "<option value=\"$val\" $selected>" . $lang[$val] . '</option>';
			}
			break;
		}
	}
	$rval .= '</select>';
	
	return $rval;
}

function ts_id_2_name($id, $mode = 'user')
{
	global $db;
	
	if ($id == '')
	{
		return '?';
	}
	
	switch($mode)
	{
		case 'user':
		{
			$sql = 'SELECT username, user_level 
				FROM ' . USERS_TABLE . "
	   			WHERE user_id = " . $id;
			if(!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Err', '', __LINE__, __FILE__, $sql);
			}
			$row = $db->sql_fetchrow($result);
			
			$row['username'] = username_level_color($row['username'], $row['user_level']);
			
			return $row['username'];
			break;
		}
		case 'forum':
		{
			$sql = "SELECT f.forum_name 
				FROM " . FORUMS_TABLE . " f, " . TOPICS_TABLE . " t
	   		   WHERE t.topic_id = " . $id . "
			   AND t.forum_id = f.forum_id";
			if(!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Err', '', __LINE__, __FILE__, $sql);
			}
			$row = $db->sql_fetchrow($result);
			return $row['forum_name'];
			break;
		}
	}
}

/*******************************************************************************************
/** Check for deletion items
/******************************************************************************************/
if ($delete_all_before_date)
{
	/* Error Checking */
	$error_message = '';
	if ($del_month < 1 || $del_month > 12)
	{
		$error_message .= $lang['Error_Month'];
	}
	if ($del_day < 1 || $del_day > 31)
	{
		$error_message .= $lang['Error_Day'];
	}
	if ($del_year < 1970 || $del_year > 2038)
	{
		$error_message .= $lang['Error_Year'];
	}
	if ($error_message != '')
	{
		message_die(GENERAL_ERROR, $error_message, '', __LINE__, __FILE__);
	}
	/* END Error Checking */
	
	$set_time = mktime(0, 0, 0, $del_month, $del_day, $del_year);
	$sql = 'DELETE FROM ' . TOPICS_TABLE . '
	   WHERE topic_status = ' . TOPIC_MOVED . "
	   AND topic_time < $set_time";
	if(!$db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, $lang['Error_Topics_Table'], '', __LINE__, __FILE__, $sql);
	}
	else
	{
		$status_message .= sprintf($lang['Del_Before_Date'], date("M-d-Y", $set_time));
		$status_message .= (SQL_LAYER == 'db2' || SQL_LAYER == 'mysql' || SQL_LAYER == 'mysql4') ? sprintf($lang['Affected_Rows'], $db->sql_affectedrows()) : '';
		sync('all_forums');
		$status_message .= sprintf($lang['Resync_Ran_On'], $lang['All_Forums']);
	}
}
else
{
	if (count($HTTP_POST_VARS))
	{
		foreach($HTTP_POST_VARS as $key => $val)
		{
			if (substr_count($key, 'delete_id_'))
			{
				$topic_id = substr($key, 10);
				
				/* Get forum info to Resync it */
				$sql = 'SELECT f.forum_id, f.forum_name, t.topic_title 
					FROM ' . TOPICS_TABLE . ' t, ' . FORUMS_TABLE . " f
			   		   WHERE t.topic_id = $topic_id
			   		   AND t.forum_id = f.forum_id";
				if (!$result = $db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, $lang['Error_Topics_Table'], '', __LINE__, __FILE__, $sql);
				}
				$forum_data_row = $db->sql_fetchrow($result);
				
				$sql = 'DELETE FROM ' . TOPICS_TABLE . '
		   			   WHERE topic_status = ' . TOPIC_MOVED . "
					   AND topic_id = $topic_id";
				if(!$db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, $lang['Error_Topics_Table'], '', __LINE__, __FILE__, $sql);
				}
				else
				{
					$status_message .= sprintf($lang['Deleted_Topic'], $forum_data_row['topic_title']);
					sync('forum', $forum_data_row['forum_id']);
					$status_message .= sprintf($lang['Resync_Ran_On'], $forum_data_row['forum_name']);
				}
			}
		}
	}
}
/*******************************************************************************************
/** Main Page
/******************************************************************************************/

$template->set_filenames(array(
	'body' => 'admin/topic_shadow_body.tpl')
);

if ($status_message != '')
{
	$template->assign_block_vars('statusrow', array());
}

$template->assign_vars(array(
'L_DELETE_FROM_EXPLAN' => $lang['Delete_From_Date'],
'L_DELETE_BEFORE' => $lang['Delete_Before_Date_Button'],
'L_MONTH' => $lang['Month'],
'L_DAY' => $lang['Day'],
'L_YEAR' => $lang['Year'],
'L_SELECT_SORT_METHOD' => $lang['Select_sort_method'],
'L_TITLE' => $lang['Title'],
'L_TIME' => $lang['Time'],
'L_POSTER' => $lang['Poster'],
'L_MOVED_TO' => $lang['Moved_To'],
'L_PAGE_NAME' => $page_title,
'L_ORDER' => $lang['Order'],
'L_NO_TOPICS_FOUND' => $lang['No_Shadow_Topics'],
'L_STATUS' => $lang['Status'],
'L_PAGE_DESC' => $lang['TS_Desc'],
'L_MOVED_FROM' => $lang['Moved_From'],

'I_STATUS_MESSAGE' => $status_message,

	'S_MONTH' => date('m'),
	'S_DAY' => date('d'),
	'S_YEAR' => date('Y'),
'S_MODE' => $mode,
'S_ORDER' => $order,
'S_MODE_SELECT' => topic_shadow_make_drop_box('mode'),
'S_ORDER_SELECT' => topic_shadow_make_drop_box('order'),
'S_MODE_ACTION' => append_sid($HTTP_SERVER_VARS['PHP_SELF']))
);

/* See if we actually have any shadow topics */
$sql = "SELECT COUNT(topic_status) AS count 
	FROM " . TOPICS_TABLE . "
   	WHERE topic_status = " . TOPIC_MOVED;
if(!$result = $db->sql_query($sql))
{
	message_die(GENERAL_ERROR, $lang['Error_Topics_Table'], '', __LINE__, __FILE__, $sql);
}
$row = $db->sql_fetchrow($result);

if ($row['count'] <= 0)
{
	$template->assign_block_vars('emptyrow', array());
}
else
{
	$sql = "SELECT * 
		FROM " . TOPICS_TABLE . "
   		WHERE topic_status = " . TOPIC_MOVED . "
   		ORDER BY " . $mode . " " . $order;
	if(!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, $lang['Error_Topics_Table'], '', __LINE__, __FILE__, $sql);
	}
	
	$i = 0;
	while ($messages = $db->sql_fetchrow($result))
	{
		$template->assign_block_vars('topicrow', array(
		'ROW_CLASS' => (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'],
		'TITLE' => $messages['topic_title'],
		'MOVED_TO' => ts_id_2_name($messages['topic_moved_id'], 'forum'),
		'MOVED_FROM' => ts_id_2_name($messages['topic_id'], 'forum'),
		'POSTER' => ts_id_2_name($messages['topic_poster']),
		'TIME' => create_date($lang['DATE_FORMAT'], $messages['topic_time'], $board_config['board_timezone']),
		'TOPIC_ID' => $messages['topic_id'])
		);
		$i++;
	}
}

$template->pparse('body');

include('../admin/page_footer_admin.'.$phpEx);

?>