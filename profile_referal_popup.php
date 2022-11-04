<?php
/** 
*
* @package phpBB2
* @version $Id: profile_referal_popup.php,v 1.193.2.5 2004/11/18 17:49:37 acydburn Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
	
define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_PROFILE);
init_userprefs($userdata);
//
// End session management
//

$template->set_filenames(array(
	'body' => 'profile_popup.tpl')
);

if ( !empty($HTTP_GET_VARS['ruid']) )
{
    $ruid = intval($HTTP_GET_VARS['ruid']);
}
elseif ( !empty($HTTP_GET_VARS['ruid']) )
{
    $ruid = intval($HTTP_GET_VARS['ruid']);
}
else
{
    $ruid = intval($board_config['referral_id']);
}
//
// Generate SQL
//
$sql = "SELECT * 
	FROM " . REFERRAL_TABLE . " 
	WHERE ruid = '" . $ruid . "'";
if( !$result = $db->sql_query($sql) )
{
	message_die(GENERAL_ERROR, "Could not query referral table", $lang['Error'], __LINE__, __FILE__, $sql);
}

$referral_rows = $db->sql_fetchrowset($result);
$referral_count = sizeof($referral_rows);

$template->assign_vars(array(
	'L_TITLE' => $lang['Referrals_Total'],
	'L_VIEWER' => $lang['Referral_Admin_New_Name'],
	'L_STAMP' => $lang['Date'])
);

for($i = 0; $i < $referral_count; $i++)
{
    //
    // GET NEW USER DATA
    $nuid = $referral_rows[$i]['nuid'];
    settype($nuid, "integer");
    //
    $new_userdata = get_userdata($nuid);
	if( !$new_userdata )
    {
		$new_username = $lang['Referral_Admin_User_Delete'];
	}
	else
	{
		$temp_url = append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=" . $new_userdata['user_id']);
		$new_username = '<a href="' . $temp_url . '" class="genmed" target="_blank">' . username_level_color($new_userdata['username'], $new_userdata['user_level'], $new_userdata['user_id']) . '</a>';
	}

	$referral_date = create_date($board_config['default_dateformat'], $referral_rows[$i]['referral_time'], $board_config['board_timezone']);

	$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

	$template->assign_block_vars('row', array(
		'ROW_CLASS' => $row_class,
        'VIEW_BY' => $new_username,
		'STAMP' => $referral_date)
	);
}

$gen_simple_header = TRUE;
$page_title = $lang['Referrals_Total'];
include($phpbb_root_path . 'includes/page_header.' . $phpEx);

$template->pparse('body');

include($phpbb_root_path . 'includes/page_tail.' . $phpEx);

?>