<?php
/*-----------------------------------------------------------------------------
                 POST ASSOCIATOR - A phpBB Add-On
  ----------------------------------------------------------------------------
    Please read the file README.TXT for licensing and copyright information.
  ----------------------------------------------------------------------------
    admin_post_assoc.php - Main File
    Version: 1.0.0
    Begun: Thursday, July 21, 2005
    Last Modified: Monday, August 01, 2005
-----------------------------------------------------------------------------*/

define('IN_PHPBB', TRUE);

$my_adm_file = basename(__FILE__);

if( !empty($setmodules) )
{
	$module['Users']['Post_Associator'] = $my_adm_file;
	return;
}

// Set basic paths.
$phpbb_adm_path = './';                // Might be useful for Nuke porters?
$phpbb_root_path = $phpbb_adm_path . '../';

// Pull php extension if not already done.
require_once($phpbb_root_path . 'extension.inc');

// Set php extension into a constant, with a period prepended.
define('PHPEX', '.' . $phpEx);
// We will use the constant PHPEX instead of $phpEx from this point onward.

// Kick the tires and light the fires
require_once($phpbb_adm_path . 'pagestart' . PHPEX);
include($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_postassoc' . PHPEX);

if( isset($_REQUEST['submit']) )
{
	$guest_user =  ( isset($HTTP_POST_VARS['guest_username']) ) ? phpbb_clean_username($HTTP_POST_VARS['guest_username']) : '';
	$new_user = ( isset($HTTP_POST_VARS['username']) ) ? $HTTP_POST_VARS['username'] : '';

	if( empty($guest_user) )
	{
		message_die(GENERAL_ERROR, $lang['PTAS_No_Guest_Name'], '', __LINE__, __FILE__, '');
	}
	$new_user = get_userdata($new_user);

	$sql = 'SELECT post_id 
		FROM ' . POSTS_TABLE . ' 
		WHERE poster_id = ' . ANONYMOUS . ' 
			AND post_username = \'' . $guest_user . '\'';
	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, $lang['PTAS_No_Guest_Posts'], '', __LINE__, __FILE__, $sql);
	}
	
	if( !$db->sql_numrows($result) )
	{
		message_die(GENERAL_MESSAGE, $lang['PTAS_No_Guest_Posts'] . '<br /><br />' . sprintf($lang['PTAS_Return'], '<a href="' . append_sid($my_adm_file) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index' . PHPEX . '?pane=right') . '">', '</a>'));
	}
	$posts = $db->sql_fetchrowset($result);
	
	$db->sql_freeresult($result);

	// At this point, we have information on the posts, so we need to
	// assemble a list of post ids to check against the topic_first_post_id
	// column of the topics table.
	$topics_sql = '';
	foreach($posts as $val)
	{
		$topics_sql .= (( !empty($topics_sql) ) ? ',' : '') . $val['post_id'];
	}

	// Now, let's update the posts table.
	$sql = 'UPDATE ' . POSTS_TABLE . ' 
		SET poster_id = ' . $new_user['user_id'] . ', post_username = \'\' 
		WHERE poster_id = ' . ANONYMOUS . ' 	
			AND post_username = \'' . $guest_user . '\'';
	if( !$db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, $lang['PTAS_No_Update_Posts'], '', __LINE__, __FILE__, $sql);
	}
	// Get the number of affected posts.
	$changed_posts = $db->sql_affectedrows();

	// Now, let's update the topics table.
	$sql = 'UPDATE ' . TOPICS_TABLE . ' SET topic_poster = ' . $new_user['user_id'] . ' WHERE topic_poster = ' . ANONYMOUS . ' AND topic_first_post_id IN (' . $topics_sql . ')';
	if( !$db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, $lang['PTAS_No_Update_Topics'], '', __LINE__, __FILE__, $sql);
	}
	// Get the number of affected topics.
	$changed_topics = $db->sql_affectedrows();

	// Update user post count.
	$sql = 'UPDATE ' . USERS_TABLE . ' 
		SET user_posts = user_posts + ' . $changed_posts . ' 
		WHERE user_id = ' . $new_user['user_id'];
	if( !$db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, $lang['PTAS_No_Update_Posts_Count'], '', __LINE__, __FILE__, $sql);
	}

	// Should be done at this point.
	$message = sprintf($lang['PTAS_Done'], $changed_topics, $changed_posts) . '<br /><br />' . sprintf($lang['PTAS_Return'], '<a href="' . append_sid($my_adm_file) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index' . PHPEX . '?pane=right') . '">', '</a>');

	message_die(GENERAL_MESSAGE, $message);
}
else
{
	// Display basic page...

	$template->set_filenames(array(
		'body' => 'admin/post_associate_body.tpl')
	);

	$template->assign_vars(array(
		'L_TITLE'   => $lang['Post_Associator'],
		'L_EXPLAIN'   => $lang['PTAS_Explain'],
		'L_GUEST_NAME'   => $lang['PTAS_Guest'],
		'L_USER_NAME'   => $lang['PTAS_User'],
		'L_GUEST_NAME_EXPLAIN'   => $lang['PTAS_Guest_explain'],
		'L_USER_NAME_EXPLAIN'   => $lang['PTAS_User_explain'],
		'L_USER_SELECT'   => $lang['Select_a_User'],
		'L_LOOK_UP'   => $lang['Look_up_user'],
		'L_FIND_USERNAME'   => $lang['Find_username'],

		'U_SEARCH_USER'   => $phpbb_root_path . 'search' . PHPEX . '?mode=searchuser&amp;sid=' . $userdata['session_id'],

		'S_HIDDEN_FIELDS'   => '',
		'S_ACTION' => $phpbb_adm_path . $my_adm_file . '?sid=' . $userdata['session_id'])
	);
}

$template->pparse('body');

include($phpbb_adm_path . 'page_footer_admin' . PHPEX);

?>