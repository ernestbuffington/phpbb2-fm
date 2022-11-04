<?php
/** 
*
* @package admin
* @version $Id: admin_user_prune.php,v 1.0 2003 niels Exp $
* @copyright (c) 2003 Niels Chr. Denmark
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
	
define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
	$file = basename(__FILE__);
	$module['Users']['Prune'] = $file;
	return;
}

//
// Load default header
//
$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);

//
// Include language
//
$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_prune_users.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_prune_users.' . $phpEx);

if (isset($HTTP_POST_VARS['config']))
{
	$config_value = ( isset($HTTP_POST_VARS['user_prune_notify']) ) ? intval($HTTP_POST_VARS['user_prune_notify']) : 0;

	$sql = "UPDATE " . CONFIG_TABLE . "
		SET config_value = $config_value
		WHERE config_name = 'user_prune_notify'";
	if ( !($result = $db->sql_query($sql)) ) 
	{ 
		message_die(GENERAL_ERROR, "Failed to update general configuration for user_prune_notify", "", __LINE__, __FILE__, $sql);
	}

	// Remove cache file
	@unlink($phpbb_root_path . 'cache/config_board.'.$phpEx);
	
	$message = $lang['Board_config_updated'] . "<br /><br />" . sprintf($lang['Click_return_config'], "<a href=\"" . append_sid("admin_user_prune.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

	message_die(GENERAL_MESSAGE, $message);
}

$sql = $default = array();

//
// from here you can define you own delete creterias, if you makes more, 
// then you should also edit the files lang_main.php, and the file admin/admin_users_prune_delete.php, 
// so they have the same options
//
// Users who have never posted
$sql[0] = ' AND user_posts = 0';
$default[0] = 365;

// Users who have never logged in
$sql[1] = ' AND user_lastvisit = 0';
$default[1] = 30;

// Users not activated 
$sql[2] = ' AND user_lastvisit = 0 AND user_active = 0';
$default[2] = 30;

// Users not visited within one year (365 days)
$sql[3] = ' AND user_lastvisit < ' . ( time() - (86400 * 365) );
$default[3] = 30;

// Users with less than 0.1 posts per day avg.
$sql[4] = ' AND user_posts / ( ( user_lastvisit - user_regdate ) / 86400 ) < 0.1';
$default[4] = 365;


// Create dropdown
$options = '<option title="' . $lang['1_Day'] . '" value="1">&nbsp;' . $lang['1_Day'] . '</option>
	<option title="' . sprintf($lang['X_Days'], 7) . '" value="7">&nbsp;' . sprintf($lang['X_Days'], 7) . '</option>
	<option title="' . sprintf($lang['X_Weeks'], 2) . '" value="14">&nbsp;' . sprintf($lang['X_Weeks'], 2) . '</option>
	<option title="' . sprintf($lang['X_Weeks'], 3) . '" value="21">&nbsp;' . sprintf($lang['X_Weeks'], 3) . '</option>
	<option title="' . $lang['1_Month'] . '" value="30">&nbsp;' . $lang['1_Month'] . '</option>
	<option title="' . sprintf($lang['X_Months'], 2) . '" value="60">&nbsp;' . sprintf($lang['X_Months'], 2) . '</option>
	<option title="' . sprintf($lang['X_Months'], 3) . '" value="90">&nbsp;' . sprintf($lang['X_Months'], 3) . '</option>
	<option title="' . sprintf($lang['X_Months'], 6) . '" value="180">&nbsp;' . sprintf($lang['X_Months'], 6) . '</option>
	<option title="' . sprintf($lang['X_Months'], 9) . '" value="270">&nbsp;' . sprintf($lang['X_Months'], 9) . '</option>
	<option title="' . $lang['1_Year'] . '" value="365">&nbsp;' . $lang['1_Year'] . '</option>
	<option title="' . sprintf($lang['X_Years'], 2) . '" value="730">&nbsp;' . sprintf($lang['X_Years'], 2) . '</option>
	<option title="' . sprintf($lang['X_Years'], 5) . '" value="1825">&nbsp;' . sprintf($lang['X_Years'], 5) . '</option>
	<option title="' . sprintf($lang['X_Years'], 10) . '" value="3650">&nbsp;' . sprintf($lang['X_Years'], 10) . '</option>
  	</select>';
  	
//
// Generate page
//
$template->set_filenames(array(
	'body' => 'admin/user_prune_body.tpl')
);

$n = 0;
while ( !empty($sql[$n]) )
{
	$vars = 'days_' . $n;

	$default[$n] = ($default[$n]) ? $default[$n] : 10;
	$days[$n] = ( isset($HTTP_GET_VARS[$vars]) ) ? $HTTP_GET_VARS[$vars] : (( isset($HTTP_POST_VARS[$vars]) ) ? intval($HTTP_POST_VARS[$vars]) : $default[$n]);

	// make a extra option if the parsed days value does not already exisit
	if (!strpos($options, "value=\"" . $days[$n]))
	{
		$options = '<option title="' . sprintf($lang['X_Days'], $days[$n]) . '" value="' . $days[$n] . '">&nbsp;' . sprintf($lang['X_Days'], $days[$n]) . '</option>' . $options;
	}
	$select[$n] = '<select name="days_' . $n . '" onchange="SetDays();">' . str_replace("value=\"" . $days[$n] . "\">&nbsp;", "value=\"" . $days[$n] . "\" selected=\"selected\">&nbsp;* ", $options);

	if(!($result = $db->sql_query('SELECT user_id, username, user_level FROM ' . USERS_TABLE . ' WHERE user_id <> "' . ANONYMOUS . '"' . $sql[$n].' AND user_regdate < "' . ( time() -( 86400 * $days[$n] ) ) . '" ORDER BY username LIMIT 800')))
	{
		message_die(GENERAL_ERROR, 'Error obtaining userdata' . $sql[$n], '', __LINE__, __FILE__, $sql[$n]);
	}
	
	$user_list = $db->sql_fetchrowset($result);
	$user_count = sizeof($user_list);
	for($i = 0; $i < $user_count; $i++)
	{
		$user_list[$i]['username'] = username_level_color($user_list[$i]['username'], $user_list[$i]['user_level'], $user_list[$i]['user_id']);

		$list[$n] .= ' <a href="' . append_sid($phpbb_root_path . 'profile.'.$phpEx.'?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $user_list[$i]['user_id']) . '"' . $style_color . ' class="gensmall">' . $user_list[$i]['username'] . '</a>,';
	}
	$db->sql_freeresult($result);
	
	$template->assign_block_vars('prune_list', array(
		"L_PRUNE" => $lang['Prune_commands'][$n],
		"L_PRUNE_EXPLAIN" => sprintf($lang['Prune_explain'][$n], $days[$n]),
		
		"LIST" => $list[$n],
		"USER_COUNT" => $user_count,
		
		"S_DAYS" => $select[$n],
		
		"S_PRUNE_USERS" => append_sid('admin_user_prune.'.$phpEx),
		"U_PRUNE" => '<a href="' . append_sid('user_prune_delete.'.$phpEx.'?mode=prune_' . $n . '&days=' . $days[$n]) . '" onClick="return confirm(\'' . sprintf($lang['Prune_on_click'], $user_count) . '\')" title="' . $lang['Prune_Action'] . '">' . $lang['Prune_commands'][$n].'</a>')
	);
	$n++;
}

$template->assign_vars(array(
	"L_PRUNE_USERS" => $lang['Prune_users'],
	"L_PRUNE_USERS_EXPLAIN" => $lang['Prune_users_explain'],

	'L_CONFIGURATION' => $lang['Prune_users'] . ' ' . $lang['Setting'],
	'L_PRUNE_EMAIL' => $lang['Prune_email'],
	'L_PRUNE_EMAIL_EXPLAIN' => $lang['Prune_email_explain'],

	'PRUNE_EMAIL_YES' => ( $board_config['user_prune_notify'] ) ? 'checked="checked"' : '', 
	'PRUNE_EMAIL_NO' => ( !$board_config['user_prune_notify'] ) ? 'checked="checked"' : '', 
	'S_CONFIG_ACTION' => append_sid('admin_user_prune.'.$phpEx),
	
	"L_PRUNE_LIST" => $lang['Prune_user_list'],
	"L_USERS" => $lang['Number_users'],
	"L_DAYS" => $lang['Days'],
	"L_SELECT" => $lang['Select_one'])
);

$template->pparse('body');

include('page_footer_admin.'.$phpEx);

?>