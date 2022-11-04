<?php
/** 
*
* @package phpBB2
* @version $Id: medals.php,v 2.0.2 2003/01/05 02:36:00 ycl6 Exp $
* @copyright (c) 2003 ycl6
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
$userdata = session_pagestart($user_ip, PAGE_MEDALS);
init_userprefs($userdata);
//
// End session management
//

//
// Obtain initial var settings
//
if ( isset($HTTP_GET_VARS[POST_MEDAL_URL]) || isset($HTTP_POST_VARS[POST_MEDAL_URL]) )
{
	$medal_id = ( isset($HTTP_POST_VARS[POST_MEDAL_URL]) ) ? intval($HTTP_POST_VARS[POST_MEDAL_URL]) : intval($HTTP_GET_VARS[POST_MEDAL_URL]);
}
else
{
	$medal_id = '';
}

if ( isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']) )
{
	$mode = ( isset($HTTP_POST_VARS['mode']) ) ? htmlspecialchars($HTTP_POST_VARS['mode']) : htmlspecialchars($HTTP_GET_VARS['mode']);
}
else
{
	$mode = '';
}

//
// Category
//
$sql = "SELECT cat_id, cat_title, cat_order
	FROM " . MEDAL_CAT_TABLE . "
	ORDER BY cat_order";
if( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not query medal categories list', '', __LINE__, __FILE__, $sql);
}

$category_rows = array();
while ($row = $db->sql_fetchrow($result) ) 
{
	$category_rows[] = $row;
}
$db->sql_freeresult($result);

if( ( $total_categories = count($category_rows) ) )
{
	$sql = "SELECT * 
		FROM " . MEDAL_TABLE . "
		ORDER BY medal_name";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain medal information', '', __LINE__, __FILE__, $sql);
	}

	$medal_data = array();
	while ($row = $db->sql_fetchrow($result) ) 
	{
		$medal_data[] = $row;
	}
	$db->sql_freeresult($result);

	if ( !($total_medals = sizeof($medal_data)) )
	{
		message_die(GENERAL_MESSAGE, $lang['No_medal']);
	}

	// Obtain list of moderators of each medal
	$sql = "SELECT u.user_id, u.username, u.user_level, mm.medal_id
		FROM " . USERS_TABLE . " u, " . MEDAL_MOD_TABLE . " mm
		WHERE u.user_id = mm.user_id
		GROUP BY u.user_id, u.username, mm.medal_id
		ORDER BY mm.medal_id, u.user_id";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not query medal moderator information', '', __LINE__, __FILE__, $sql);
	}

	$medal_moderators = array();
	while( $row = $db->sql_fetchrow($result) )
	{
		$medal_moderators[$row['medal_id']][] = '<a href="' . append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=" . $row['user_id']) . '" class="genmed">' . username_level_color($row['username'], $row['user_level'], $row['user_id']) . '</a>';
	}
	$db->sql_freeresult($result);

	// Obtain list of users of each medal
	$sql = "SELECT u.user_id, u.username, u.user_level, mu.medal_id
		FROM " . USERS_TABLE . " u, " . MEDAL_USER_TABLE . " mu
		WHERE u.user_id = mu.user_id
		GROUP BY u.user_id, u.username, mu.medal_id
		ORDER BY u.user_id";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not query medal userlist information', '', __LINE__, __FILE__, $sql);
	}

	$medal_users = array();
	while( $row = $db->sql_fetchrow($result) )
	{
		$medal_users[$row['medal_id']][] = '<a href="' . append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=" . $row['user_id']) . '" class="genmed">' . username_level_color($row['username'], $row['user_level'], $row['user_id']) . '</a>';
	}
	$db->sql_freeresult($result);
	
	//
	// Start output of page
	//
	$page_title = $lang['Medal_Information'];
	include ($phpbb_root_path . 'includes/page_header.'.$phpEx);

	$template->set_filenames(array(
		'body' => 'medals_body.tpl')
	);
	make_jumpbox('viewforum.'.$phpEx);

	$template->assign_vars(array(
		'L_USERS_LIST' => $lang['External_members'],
		'L_MEDAL_INFORMATION' => $lang['Medal_Information'],
		'L_MEDAL_NAME' => $lang['Medals'],
		'L_MEDAL_MODERATOR' => $lang['Medal_moderator'],
		'L_MEDAL_IMAGE' => $lang['Medal_image'],
		'L_LINK_TO_CP' => $lang['Link_to_cp'])
	);

	//
	// Okay, let's build the index
	//
	for($i = 0; $i < $total_categories; $i++)
	{
		$cat_id = $category_rows[$i]['cat_id'];

		//
		// Should we display this category/medal set?
		//
		$display_medal = FALSE;
		for($k = 0; $k < $total_medals; $k++)
		{
			if ( $medal_data[$k]['cat_id'] == $cat_id )
			{
				$display_medal = TRUE;
			}
		}

		if ( $display_medal )
		{
			$template->assign_block_vars('catrow', array(
				'CAT_ID' => $cat_id,
				'CAT_DESC' => $category_rows[$i]['cat_title'])
			);
	
			for($j = 0; $j < $total_medals; $j++)
			{
				if ( $medal_data[$j]['cat_id'] == $cat_id )
				{
					$medal_id = $medal_data[$j]['medal_id'];

					if ( sizeof($medal_moderators[$medal_id]) > 0 )
					{
						$moderator_list = implode(', ', $medal_moderators[$medal_id]);
					}
					else
					{
						$moderator_list = $lang['No_medal_mod'];
					}
					
					if ( sizeof($medal_users[$medal_id]) > 0 )
					{
						$user_list = implode(', ', $medal_users[$medal_id]);
					}
					else
					{
						$user_list = $lang['No_medal_members'];
					}
					
					$template->assign_block_vars('catrow.medals', array(
						'MEDAL_ID' => $medal_data[$j]['medal_name'],
						'MEDAL_NAME' => $medal_data[$j]['medal_name'],
						'MEDAL_DESCRIPTION'  => $medal_data[$j]['medal_description'],
						'MEDAL_IMAGE' => ($medal_data[$j]['medal_image'] == '') ? '' : '<img src="images/medals/' . $medal_data[$j]['medal_image'] . '" alt="' . $medal_data[$j]['medal_name'] . '" title="' . $medal_data[$j]['medal_name'] . '" />',
						'MEDAL_MOD' => $moderator_list, 
						'USERS_LIST' => $user_list,
						'U_MEDAL_CP' => append_sid("medalcp.$phpEx?" . POST_MEDAL_URL . "=" . $medal_data[$j]['medal_id'] . "&amp;sid=" . $userdata['session_id']))
					);

					$is_moderator = check_medal_mod($medal_id);

					if ( $is_moderator || $userdata['user_level'] == ADMIN )
					{
						$template->assign_block_vars('catrow.medals.switch_mod_option', array());
					}
				}
			}
		}
	} // for ... categories

}// if ... total_categories
else
{
	message_die(GENERAL_MESSAGE, $lang['No_medal']);
}

//
// page footer
//
$template->pparse('body');

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>
