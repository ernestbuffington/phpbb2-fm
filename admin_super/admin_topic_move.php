<?php
/***************************************************************************
*                             admin_topic_move.php
*                              -------------------
*     begin                : Mon Jul 31, 2001
*     copyright            : (C) 2001 The phpBB Group
*     email                : support@phpbb.com
*
*     $Id: admin_topic_move.php,v 1.00 2003/01/13 11:20:00 psotfx Exp $
*
****************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

define('IN_PHPBB', true);

if ( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	$module['Forums']['Move_topics'] = $filename;

	return;
}

//
// Load default header
//
$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);
require($phpbb_root_path . 'includes/functions_move.'.$phpEx);
require($phpbb_root_path . 'includes/functions_admin.'.$phpEx); 

//
// Get the forum ID for moving
//
if( isset($HTTP_GET_VARS[POST_FORUM_URL]) || isset($HTTP_POST_VARS[POST_FORUM_URL]) )
{
	$forum_id = ( isset($HTTP_POST_VARS[POST_FORUM_URL]) ) ? $HTTP_POST_VARS[POST_FORUM_URL] : $HTTP_GET_VARS[POST_FORUM_URL];

	if( $forum_id == -1 )
	{
		$forum_sql = '';
	}
	else
	{
		$forum_id = intval($forum_id);
		$forum_sql = "AND forum_id = $forum_id";
	}
}
else
{
	$forum_id = '';
	$forum_sql = '';
}

//
// Get the forum ID for moving the topics into
//
if( isset($HTTP_GET_VARS['movetoforum']) || isset($HTTP_POST_VARS['movetoforum']) )
{
	$move_forum_id = ( isset($HTTP_POST_VARS['movetoforum']) ) ? $HTTP_POST_VARS['movetoforum'] : $HTTP_GET_VARS['movetoforum'];

	if( $move_forum_id == -1 )
	{
		$move_forum_sql = '';
	}
	else
	{
		$move_forum_id = intval($move_forum_id);
		$move_forum_sql = "AND forum_id = $move_forum_id";
	}
}
else
{
	$move_forum_id = '';
	$move_forum_sql = '';
}

//
// Get a list of forum's or the data for the forum that we are moving topics out of.
//
$sql = "SELECT f.*
	FROM " . FORUMS_TABLE . " f, " . CATEGORIES_TABLE . " c
	WHERE c.cat_id = f.cat_id
		$forum_sql
	ORDER BY c.cat_order ASC, f.forum_order ASC";
if( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not obtain list of forums for moving', '', __LINE__, __FILE__, $sql);
}

$forum_rows = array();
while( $row = $db->sql_fetchrow($result) )
{
	$forum_rows[] = $row;
}

//
// Get a list of forum's or the data for the forum that we are moving topics into.
//
$move_sql = "SELECT f.*
	FROM " . FORUMS_TABLE . " f, " . CATEGORIES_TABLE . " c
	WHERE c.cat_id = f.cat_id
	$move_forum_sql
	ORDER BY c.cat_order ASC, f.forum_order ASC";
if( !($result = $db->sql_query($move_sql)) )
{
	message_die(GENERAL_ERROR, 'Could not obtain list of forums for moving to', '', __LINE__, __FILE__, $move_sql);
}

$move_forum_rows = array();
while( $move_row = $db->sql_fetchrow($result) )
{
	$move_forum_rows[] = $move_row;
}

//
// Check for submit to be equal to Move. If so then proceed with the moving.
//
if( isset($HTTP_POST_VARS['domove']) )
{
	$movedays = ( isset($HTTP_POST_VARS['movedays']) ) ? intval($HTTP_POST_VARS['movedays']) : 0;
	$destination_id = $HTTP_POST_VARS['movetoforum'];

	// Convert days to seconds for timestamp functions...
	$movedate = time() - ( $movedays * 86400 );

	$template->set_filenames(array(
		'body' => 'admin/topic_move_result_body.tpl')
	);

	for($i = 0; $i < count($forum_rows); $i++)
	{
		$p_result = move($forum_rows[$i]['forum_id'], $movedate, $destination_id);
		sync('forum', $move_forum_rows[$i]['forum_id']);
		sync('forum', $forum_rows[$i]['forum_id']);
	
		$row_color = ( !($i % 2) ) ? $theme['td_color1'] : $theme['td_color2'];
		$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
	
		$template->assign_block_vars('move_results', array(
			'ROW_COLOR' => '#' . $row_color, 
			'ROW_CLASS' => $row_class, 
			'FORUM_NAME' => $forum_rows[$i]['forum_name'],
			'FORUM_MOVE_NAME' => $move_forum_rows[$i]['forum_name'],
			'FORUM_TOPICS' => $p_result['topics'],
			'FORUM_POSTS' => $p_result['posts'])
		);
	}

	$template->assign_vars(array(
		'L_FORUM' => $lang['Forum'],
		'L_FORUM_MOVE' => $lang['Forum_Move_To'],
		'L_TOPICS_MOVED' => $lang['Topics_moved'],
		'L_POSTS_MOVED' => $lang['Posts_moved'],
		'L_MOVE_RESULT' => $lang['Move_success'])
	);
}
else
{
	//
	// If they haven't selected a forum for pruning yet then
	// display a select box to use for pruning.
	//
	if( empty($HTTP_POST_VARS[POST_FORUM_URL]) )
	{
		//
		// Output a selection table if no forum id has been specified.
		//
		$template->set_filenames(array(
			'body' => 'admin/topic_move_select_body.tpl')
		);

		$select_list = '<select name="' . POST_FORUM_URL . '">';
		$select_list .= '<option value="-1">' . $lang['All_Forums'] . '</option>';

		for($i = 0; $i < count($forum_rows); $i++)
		{
			$select_list .= '<option value="' . $forum_rows[$i]['forum_id'] . '">' . $forum_rows[$i]['forum_name'] . '</option>';
		}
		$select_list .= '</select>';

		//
		// Assign the template variables.
		//
		$template->assign_vars(array(
			'L_FORUM_MOVE' => $lang['Move_topics'],
			'L_FORUM_MOVE_EXPLAIN' => $lang['Forum_Move_explain'], 
			'L_SELECT_FORUM' => $lang['Select_a_Forum'], 
			'L_LOOK_UP' => $lang['Look_up_Forum'],

			'S_FORUMMOVE_ACTION' => append_sid("admin_topic_move.$phpEx"),
			'S_FORUMS_SELECT' => $select_list)
		);
	}
	else
	{
		$forum_id = intval($HTTP_POST_VARS[POST_FORUM_URL]);
		
		//
		// Output the form to retrieve Prune information.
		//
		$template->set_filenames(array(
			'body' => 'admin/topic_move_body.tpl')
		);

		$forum_name = ( $forum_id == -1 ) ? $lang['All_Forums'] : $forum_rows[0]['forum_name'];

		//
		// Get a list of forum's or the data for the forum that we are pruning.
		//
		$move_sql = "SELECT f.*
			FROM " . FORUMS_TABLE . " f, " . CATEGORIES_TABLE . " c
			WHERE c.cat_id = f.cat_id
			$move_forum_sql
			ORDER BY c.cat_order ASC, f.forum_order ASC";
		if( !($result = $db->sql_query($move_sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not obtain list of forums for moving', '', __LINE__, __FILE__, $move_sql);
		}

		$move_forum_rows = array();
		while( $row = $db->sql_fetchrow($result) )
		{
			$move_forum_rows[] = $row;
		}

		//
		// Output the forum selection box and days text box
		//
		
		$move_forum_data = '<select name="movetoforum">';

		for($i = 0; $i < count($move_forum_rows); $i++)
		{
			$move_forum_data .= '<option value="' . $move_forum_rows[$i]['forum_id'] . '">' . $move_forum_rows[$i]['forum_name'] . '</option>';
		}
		$move_forum_data .= '</select>';

		$move_data .= '<input class="post" type="text" name="movedays" size="4"> ' . $lang['Days'];

		$hidden_input = '<input type="hidden" name="' . POST_FORUM_URL . '" value="' . $forum_id . '">';

		//
		// Assign the template variables.
		//
		$template->assign_vars(array(
			'FORUM_NAME' => $forum_name,

			'L_FORUM' => $lang['Forum'], 
			'L_FORUM_MOVE' => $lang['Move_topics'],
			'L_FORUM_MOVE_EXPLAIN' => $lang['Forum_Move_explain'], 
			'L_DO_MOVE' => $lang['Do_Move'],
			'L_MOVE_TOPICS_NOT_POSTED' => $lang['Move_topics_not_posted'],
			'L_MOVE_DESTINATION' => $lang['Move_destination'],

			'S_FORUMMOVE_ACTION' => append_sid("admin_topic_move.$phpEx"),
			'S_MOVE_FORUM' => $move_forum_data,
			'S_MOVE_DATA' => $move_data,
			'S_HIDDEN_VARS' => $hidden_input)
		);
	}
}
//
// Actually output the page here.
//
$template->pparse('body');

include('../admin/page_footer_admin.'.$phpEx);

?>