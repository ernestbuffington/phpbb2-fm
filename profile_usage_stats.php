<?php
/** 
*
* @package phpBB2
* @version $Id: aprofile_usage_stats.php,v 1.1.0 2003 calennert Exp $
* @copyright (c) 2003 Chris Lennert
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);
include($phpbb_root_path . 'includes/constants_usage_stats.' . $phpEx);

// Include language file
$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_admin_usage_stats.'.$phpEx) )
{
	$language = 'english';
}
include($phpbb_root_path . 'language/lang_' . $language . '/lang_admin_usage_stats.' . $phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PROFILE);
init_userprefs($userdata);
//
// End session management
//
		
if ($userdata['user_level'] != ADMIN)
{
	message_die(GENERAL_MESSAGE, $lang['Not_Authorised']);
}

$page_title = $lang['BBUS_Col_Descriptions_Caption'];
$gen_simple_header = TRUE;
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

$template->set_filenames(array(
	'bbus_coldesc_template' => 'profile_popup_usage_stats.tpl')
);

if ( ($board_config[BBUS_CONFIGPROP_VIEWOPTIONS_NAME] & BBUS_VIEWOPTION_PCTUTUP_COLUMN_VISIBLE) != 0 )
{
    $template->assign_block_vars('bb_usage_switch_pctutup_coldesc', array(
        'L_BBUS_COLHEADER_PCTUTUP' => $lang['BBUS_ColHeader_PctUTUP'],
        'L_BBUS_COLHEADER_PCTUTUP_EXPLAIN' => $lang['BBUS_ColHeader_PctUTUP_Explain'])
    );
}

$template->assign_vars(array(
    'L_CLOSE_WINDOW' => $lang['Close_window'],
    'L_BBUS_COL_DESCRIPTIONS_CAPTION' => $lang['BBUS_Col_Descriptions_Caption'],
    'L_BBUS_COLHEADER_FORUM' => $lang['Forum'],
    'L_BBUS_COLHEADER_POSTS' => $lang['Posts'],
    'L_BBUS_COLHEADER_POSTRATE' => $lang['BBUS_ColHeader_PostRate'],
    'L_BBUS_COLHEADER_PCTUTP' => $lang['BBUS_ColHeader_PctUTP'],
    'L_BBUS_COLHEADER_NEWTOPICS' => $lang['BBUS_ColHeader_NewTopics'],
    'L_BBUS_COLHEADER_TOPICRATE' => $lang['BBUS_ColHeader_TopicRate'],
    'L_BBUS_COLHEADER_TOPICS_WATCHED' => $lang['BBUS_ColHeader_Topics_Watched'],

    'L_BBUS_COLHEADER_POSTS_EXPLAIN' => $lang['BBUS_ColHeader_Posts_Explain'],
    'L_BBUS_COLHEADER_POSTRATE_EXPLAIN' => $lang['BBUS_ColHeader_PostRate_Explain'],
    'L_BBUS_COLHEADER_PCTUTP_EXPLAIN' => $lang['BBUS_ColHeader_PctUTP_Explain'],
    'L_BBUS_COLHEADER_NEWTOPICS_EXPLAIN' => $lang['BBUS_ColHeader_NewTopics_Explain'],
    'L_BBUS_COLHEADER_TOPICRATE_EXPLAIN' => $lang['BBUS_ColHeader_TopicRate_Explain'],
    'L_BBUS_COLHEADER_TOPICS_WATCHED_EXPLAIN' => $lang['BBUS_ColHeader_Topics_Watched_Explain'],

    'L_BBUS_COLHEADER_HEADER' => $lang['BBUS_ColHeader_Header'],
    'L_BBUS_COLHEADER_DESCRIPTION' => $lang['BBUS_ColHeader_Description'])
);

$template->pparse('bbus_coldesc_template');

include($phpbb_root_path . 'includes/page_tail.'.$phpEx); 

?>