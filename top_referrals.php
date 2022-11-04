<?php
/** 
*
* @package phpBB2
* @version $Id: top_referrals.php,v 1.1.0 2002 abela Exp $
* @copyright (c) 2002 John B. Abela
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
$userdata = session_pagestart($user_ip, PAGE_VIEWMEMBERS);
init_userprefs($userdata);
//
// End session management
//

if ( !$userdata['session_logged_in'] ) 
{ 
	redirect("login.".$phpEx."?redirect=top_referrals.".$phpEx); 
	exit; 
} 

if (!$board_config['referral_enable'])
{
	message_die(GENERAL_MESSAGE, $lang['Referral_Disabled']);
}
//
// Generate page
//
$page_title = $lang['Top_referrals'];
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

$template->set_filenames(array(
	'body' => 'top_referral_body.tpl')
);
make_jumpbox('viewforum.'.$phpEx);

$sql = "SELECT r.ruid, u.username, u.user_level, COUNT(r.ruid) AS total 
	FROM " . REFERRAL_TABLE . " AS r
		LEFT JOIN " . USERS_TABLE . " AS u ON r.ruid = u.user_id
	WHERE r.ruid != '-1'
	GROUP BY u.user_id
	ORDER BY total DESC 
	LIMIT 0, " . $board_config['topics_per_page'];
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not query referral users data', '', __LINE__, __FILE__, $sql);
}

$referral_rows = $db->sql_fetchrowset($result);
$referral_count = sizeof($referral_rows);

for($i = 0; $i < $referral_count; $i++)
{
	$ruid = $referral_rows[$i]['ruid'];
	$total_posts = $referral_rows[$i]['total'];

	$temp_url = append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=" . $ruid);
	$top_referral = '<a href="' . $temp_url . '" class="genmed">' . username_level_color($referral_rows[$i]['username'], $referral_rows[$i]['user_level'], $ruid) . '</a>';

	$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

	$template->assign_block_vars("referrals", array(
		'ROW_CLASS' => $row_class,
		'TOP_USER' => $top_referral,
		'TOTAL' => $total_posts)
	);
}

$template->assign_vars(array(
	'L_REFERRAL_TITLE' => $page_title,
	'L_REFERRAL_TOP_TITLE' => $lang['Top_referrals'],
	'L_REFERRAL_TOP_DESCRIPTION' => $lang['Referral_Top_About'],
	'L_REFERRAL_R_NAME' => $lang['Referral_Admin_Referral_Name'],
    'L_REFERRAL_N_NAME' => $lang['Referral_Admin_New_Name'],
	'L_REFERRAL_DATETIME' => $lang['Referral_Admin_DateTime'],
	'L_REFERRALS' => $lang['Referrals_Name'],
	'L_TOTAL_REFERRALS' => $lang['Referrals_Total'])
);

//
// Force password update
//
if ($board_config['password_update_days'])
{
	include($phpbb_root_path . 'includes/update_password.'.$phpEx);
}

$template->pparse('body');

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>