<?php
/** 
*
* @package phpBB2
* @version $Id: album_search.php,v 1.5 2003/05/19 10:16:30 ngoctu Exp $
* @copyright (c) 2003 Smartor
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', true);
$phpbb_root_path = './';
$album_root_path = $phpbb_root_path . 'mods/album/';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_ALBUM);
init_userprefs($userdata);
//
// End session management
//

//
// Get general album information
//
include($album_root_path . 'album_common.'.$phpEx);

//
// Start of program proper
//
$page_title = $lang['Album_search'];
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

$template->set_filenames(array(
	'body' => 'album_search_body.tpl')
);
	
if (( isset($HTTP_POST_VARS['search']) || isset($HTTP_GET_VARS['search']) ) && ( $HTTP_POST_VARS['search'] != '' || $HTTP_GET_VARS['search'] != '' ))
{
	$template->assign_block_vars('switch_search_results', array());
		
	if ( isset($HTTP_POST_VARS['mode']) )
	{
		$m = htmlspecialchars($HTTP_POST_VARS['mode']);
	}
	else if ( isset($HTTP_GET_VARS['mode']) )
	{
		$m = htmlspecialchars($HTTP_GET_VARS['mode']);
	}
	else
	{
		message_die(GENERAL_MESSAGE, 'Bad request');
	}
			
	if ( isset($HTTP_POST_VARS['search']) )
	{
		$s = htmlspecialchars($HTTP_POST_VARS['search']);
	}
	else if ( isset($HTTP_GET_VARS['search']) )
	{
		$s = htmlspecialchars($HTTP_GET_VARS['search']);
	}
	
	if ($m == $lang['Username'])
	{
		$where = 'p.pic_username';
	}
	else if ($m == $lang['Pic_Title'])
	{
		$where = 'p.pic_title';
	}
	else if ($m == $lang['Pic_Desc'])
	{
		$where = 'p.pic_desc';
	}
				
	$sql = "SELECT p.pic_id, p.pic_title, p.pic_desc, p.pic_user_id, p.pic_username, p.pic_time, p.pic_cat_id, p.pic_approval, c.cat_id, c.cat_title
		FROM " . ALBUM_TABLE . ' AS p,' . ALBUM_CAT_TABLE . " AS c
		WHERE p.pic_approval = 1 
			AND " . $where .  " LIKE '%" . $s . "%' 
			AND p.pic_cat_id = c.cat_id OR p.pic_cat_id = 0 
			AND p.pic_approval = 1 
			AND " . $where .  " LIKE '%" . $s . "%' 
		ORDER BY p.pic_time DESC";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, "Couldn't obtain a list of matching information (searching for: $s)", "", __LINE__, __FILE__, $sql);
	}
		
	$numres = 0;
	if ( $row = $db->sql_fetchrow($result) )
	{
		$in = array();
		do
		{
			if ( !in_array($row['pic_id'], $in) ) 
			{
				$template->assign_block_vars('switch_search_results.search_results', array(
					'L_USERNAME' => $row['pic_username'],
					'U_PROFILE' => append_sid('profile.php?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $row['pic_user_id']),
						
					'L_CAT' => ($row['pic_cat_id'] == 0 ) ? 'User personal' : $row['cat_title'],
					'U_CAT' => ($row['pic_cat_id'] == 0 ) ? append_sid('album_personal.php?user_id=' . $row['pic_user_id']) : append_sid('album_cat.php?cat_id=' . $row['cat_id']),
						
					'L_PIC' => $row['pic_title'],
					'U_PIC' => append_sid('album_showpage.php?pic_id=' . $row['pic_id']),
						
					'L_TIME' => create_date($board_config['default_dateformat'], $row['pic_time'], $board_config['board_timezone']))
				);
					
				$in[$numres] = $row['pic_id'];
				$numres++;
			}
		}
		while( $row = $db->sql_fetchrow($result) );
	
		$template->assign_vars(array(
			'L_NRESULTS' => sprintf($lang['Found_search_matches'], $numres),
			'L_TCATEGORY' => $lang['Category'],
			'L_TTITLE' => $lang['Pic_Title'],
			'L_TSUBMITER' => $lang['Poster'],
			'L_TSUBMITED' => $lang['Posted'])
		);
	}
	else
	{
		message_die(GENERAL_MESSAGE, $lang['No_search_match'] . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
	}
}
else
{
	$template->assign_block_vars('switch_search', array());
	
	$template->assign_vars(array(
		'L_THAT_CONTAINS' => $lang['That_contains'],
		'L_PIC_DESC' => $lang['Pic_Desc'])
	);
}
	
$template->pparse('body');

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);	

?>