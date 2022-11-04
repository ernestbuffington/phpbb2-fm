<?php
/** 
 *
* @package admin_super
* @version $Id: admin_user_ranks.php,v 1.13.2.4 2004/03/25 15:57:20 acydburn Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

if( !empty($setmodules) )
{
	$file = basename(__FILE__);
	$module['General']['Ranks'] = $file;
	return;
}

//
// Let's set the root dir for phpBB
//
define('IN_PHPBB', 1);
$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');

$cancel = ( isset($HTTP_POST_VARS['cancel']) || isset($_POST['cancel']) ) ? true : false;
$no_page_header = $cancel;

require('./pagestart.' . $phpEx);

if ($cancel)
{
	redirect('admin_super/' . append_sid("admin_user_ranks.$phpEx", true));
}

if( isset($HTTP_GET_VARS['mode']) || isset($HTTP_POST_VARS['mode']) )
{
	$mode = (isset($HTTP_GET_VARS['mode'])) ? $HTTP_GET_VARS['mode'] : $HTTP_POST_VARS['mode'];
	$mode = htmlspecialchars($mode); 
}
else 
{
	//
	// These could be entered via a form button
	//
	if( isset($HTTP_POST_VARS['add']) )
	{
		$mode = "add";
	}
	else if( isset($HTTP_POST_VARS['save']) )
	{
		$mode = "save";
	}
	else
	{
		$mode = "";
	}
}

// Restrict mode input to valid options
$mode = ( in_array($mode, array('add', 'edit', 'save', 'delete')) ) ? $mode : '';


//
// Read a listing of uploaded icon images for use in the add or edit catefory/forum code...
//
$rank_imgs = array();
$style_dir = ( $theme['image_cfg'] ) ? $theme['template_name'] . '/images/' . $theme['image_cfg'] : $theme['template_name'] . '/images';
$dir = @opendir($phpbb_root_path . 'templates/' . $style_dir . '/ranks/');
while($file = @readdir($dir))
{
	if( !@is_dir(phpbb_realpath($phpbb_root_path . 'templates/' . $style_dir . '/ranks/' . $file)) )
	{
		$img_size = @getimagesize($phpbb_root_path . 'templates/' . $style_dir . '/ranks/' . $file);

		if( $img_size[0] && $img_size[1] )
		{
			$rank_imgs[] = $file;
		}
	}
}
@closedir($dir);

if( $mode != "" )
{
	if( $mode == "edit" || $mode == "add" )
	{
		//
		// They want to add a new rank, show the form.
		//
		$rank_id = ( isset($HTTP_GET_VARS['id']) ) ? intval($HTTP_GET_VARS['id']) : 0;
		
		$s_hidden_fields = "";
		
		if( $mode == "edit" )
		{
			if( empty($rank_id) )
			{
				message_die(GENERAL_MESSAGE, $lang['Must_select_rank']);
			}

			$sql = "SELECT * 
				FROM " . RANKS_TABLE . "
				WHERE rank_id = $rank_id";
			if(!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, "Couldn't obtain rank data", "", __LINE__, __FILE__, $sql);
			}
			
			$rank_info = $db->sql_fetchrow($result);
			$s_hidden_fields .= '<input type="hidden" name="id" value="' . $rank_id . '" />';
		}
		else
		{
			$rank_info['rank_special'] = $rank_info['rank_group'] = 0;
			$rank_info['rank_image'] = 'none.gif';
		}

		$s_hidden_fields .= '<input type="hidden" name="mode" value="save" />';

		$rank_is_special = ( $rank_info['rank_special'] ) ? 'checked="checked"' : '';
		$rank_is_not_special = ( !$rank_info['rank_special'] ) ? 'checked="checked"' : '';
		
		//
		// Create rank image dropdown
		// 
		$rank_img_list = '';
		$icon_img = 'images/spacer.gif';
		for( $i = 0; $i < sizeof($rank_imgs); $i++ )
		{
			if( $rank_imgs[$i] == $rank_info['rank_image'] )
			{
				$icon_selected = ' selected="selected"';
				$icon_img = 'templates/' . $style_dir . '/ranks/' . $rank_imgs[$i];
			}
			else
			{
				$icon_selected = '';
			}
			
			$rank_img_list .= '<option value="' . $rank_imgs[$i] . '"' . $icon_selected . '>' . $rank_imgs[$i] . '</option>';
		}

		//
		// Group Rank
		//
		$sql = "SELECT group_id, group_name
			FROM " . GROUPS_TABLE . "
			WHERE group_single_user = 0
			ORDER BY group_name";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not obtain group list', '', __LINE__, __FILE__, $sql);
		}

		$group_select = '';
		if ( $row = $db->sql_fetchrow($result) )
		{
			$group_select .= '<select name="group">';
			$group_select .= '<option value="0">' . $lang['No'] . '</option>';
			do
			{
				$selected = ( $rank_info['rank_group'] == $row['group_id'] ) ? ' selected="selected"' : '';
				$group_select .= '<option value="' . $row['group_id'] . '" title="' . $row['group_name'] . '"' . $selected . '>' . $row['group_name'] . '</option>';
			}
			while ( $row = $db->sql_fetchrow($result) );
			$group_select .= '</select>';
		}
		$db->sql_freeresult($result);

		if ( !empty($group_select) )
		{
			$template->assign_block_vars('switch_group_rank', array(
				'L_GROUP_RANK' => $lang['Group_rank'],
				'L_GROUP_RANK_EXPLAIN' => $lang['Group_rank_explain'],
				
				'GROUP_RANK_SELECT' => $group_select)
			);
		}

		$template->set_filenames(array(
			"body" => "admin/ranks_edit_body.tpl")
		);
	
		$template->assign_vars(array(
			"RANK" => $rank_info['rank_title'],
			"SPECIAL_RANK" => $rank_is_special,
			"NOT_SPECIAL_RANK" => $rank_is_not_special,
			"MINIMUM" => ( $rank_is_special ) ? '' : $rank_info['rank_min'],

			'RANK_IMG' => $phpbb_root_path . $icon_img, 
			'S_FILENAME_OPTIONS' => $rank_img_list, 
			'S_ICON_BASEDIR' => $phpbb_root_path . '/templates/' . $style_dir . '/ranks/',

			"L_RANKS_TITLE" => (($mode == 'add') ? $lang['Add'] : $lang['Edit']) . ' ' . $lang['RankFAQ_Block_Title'],
			"L_RANKS_TEXT" => $lang['Ranks_explain'],
			"L_RANK_TITLE" => $lang['RankFAQ_Title'],
			"L_RANK_SPECIAL" => $lang['Rank_special'],
			"L_RANK_MINIMUM" => $lang['RankFAQ_Min'],
			"L_RANK_IMAGE" => $lang['RankFAQ_Image'],
			"L_RANK_IMAGE_EXPLAIN" => $lang['Rank_image_explain'],
			
			"S_RANK_ACTION" => append_sid("admin_user_ranks.$phpEx"),
			"S_HIDDEN_FIELDS" => $s_hidden_fields)
		);
		
	}
	else if( $mode == "save" )
	{
		//
		// Ok, they sent us our info, let's update it.
		//
		$rank_id = ( isset($HTTP_POST_VARS['id']) ) ? intval($HTTP_POST_VARS['id']) : 0;
		$rank_title = ( isset($HTTP_POST_VARS['title']) ) ? trim($HTTP_POST_VARS['title']) : "";
		$special_rank = ( $HTTP_POST_VARS['special_rank'] == 1 ) ? TRUE : 0;
		$min_posts = ( isset($HTTP_POST_VARS['min_posts']) ) ? intval($HTTP_POST_VARS['min_posts']) : -1;
		$rank_image = ( (isset($HTTP_POST_VARS['rank_image'])) ) ? trim($HTTP_POST_VARS['rank_image']) : "";
		$group = ( $HTTP_POST_VARS['group'] > 0 ) ? intval($HTTP_POST_VARS['group']) : 0;

		if( $rank_title == '' )
		{
			message_die(GENERAL_MESSAGE, $lang['Must_select_rank']);
		}

		if( $special_rank == 1 )
		{
			$max_posts = $min_posts = -1;
			$group = 0;
		}

		//
		// The rank image has to be a jpg, gif or png
		//
		if($rank_image != '')
		{
			if ( !preg_match("/(\.gif|\.png|\.jpg)$/is", $rank_image))
			{
				$rank_image = '';
			}
		}

		if ($rank_id)
		{
			if (!$special_rank)
			{
				$sql = "UPDATE " . USERS_TABLE . " 
					SET user_rank = 0 
					WHERE user_rank = $rank_id";
				if( !$result = $db->sql_query($sql) ) 
				{
					message_die(GENERAL_ERROR, $lang['No_update_ranks'], "", __LINE__, __FILE__, $sql);
				}
			}
			
			$sql = "UPDATE " . RANKS_TABLE . "
				SET rank_title = '" . str_replace("\'", "''", $rank_title) . "', rank_special = $special_rank, rank_min = $min_posts, rank_image = '" . str_replace("\'", "''", $rank_image) . "', rank_group = $group
				WHERE rank_id = $rank_id";

			$message = $lang['Rank_updated'];
		}
		else
		{
			$sql = "INSERT INTO " . RANKS_TABLE . " (rank_title, rank_special, rank_min, rank_image, rank_group)
				VALUES ('" . str_replace("\'", "''", $rank_title) . "', $special_rank, $min_posts, '" . str_replace("\'", "''", $rank_image) . "', $group)";

			$message = $lang['Rank_added'];
		}
		
		if( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, "Couldn't update/insert into ranks table", "", __LINE__, __FILE__, $sql);
		}

		$message .= "<br /><br />" . sprintf($lang['Click_return_rankadmin'], "<a href=\"" . append_sid("admin_user_ranks.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

		message_die(GENERAL_MESSAGE, $message);
	}
	else if( $mode == "delete" )
	{
		//
		// Ok, they want to delete their rank
		//
		
		if( isset($HTTP_POST_VARS['id']) || isset($HTTP_GET_VARS['id']) )
		{
			$rank_id = ( isset($HTTP_POST_VARS['id']) ) ? intval($HTTP_POST_VARS['id']) : intval($HTTP_GET_VARS['id']);
		}
		else
		{
			$rank_id = 0;
		}
		
		$confirm = isset($HTTP_POST_VARS['confirm']);
		
		if( $rank_id && $confirm )
		{
			$sql = "DELETE FROM " . RANKS_TABLE . "
				WHERE rank_id = $rank_id";
			
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't delete rank data", "", __LINE__, __FILE__, $sql);
			}
			
			$sql = "UPDATE " . USERS_TABLE . " 
				SET user_rank = 0 
				WHERE user_rank = $rank_id";

			if( !$result = $db->sql_query($sql) ) 
			{
				message_die(GENERAL_ERROR, $lang['No_update_ranks'], "", __LINE__, __FILE__, $sql);
			}

			$message = $lang['Rank_removed'] . "<br /><br />" . sprintf($lang['Click_return_rankadmin'], "<a href=\"" . append_sid("admin_user_ranks.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

			message_die(GENERAL_MESSAGE, $message);

		}
		elseif( $rank_id && !$confirm)
	{
			// Present the confirmation screen to the user
		$template->set_filenames(array(
				'body' => 'admin/confirm_body.tpl')
		);
		
			$hidden_fields = '<input type="hidden" name="mode" value="delete" /><input type="hidden" name="id" value="' . $rank_id . '" />';
		
			$template->assign_vars(array(
				'MESSAGE_TITLE' => $lang['Confirm'],
				'MESSAGE_TEXT' => $lang['Confirm_delete_rank'],
		
				'L_YES' => $lang['Yes'],
				'L_NO' => $lang['No'],
			
				'S_CONFIRM_ACTION' => append_sid("admin_user_ranks.$phpEx"),
				'S_HIDDEN_FIELDS' => $hidden_fields)
		);
		}
		else
		{
			message_die(GENERAL_MESSAGE, $lang['Must_select_rank']);
		}
			}
			
	$template->pparse("body");
	
	include('../admin/page_footer_admin.'.$phpEx);
}

	//
	// Show the default page
	//
	$template->set_filenames(array(
		"body" => "admin/ranks_list_body.tpl")
	);
	
$sql = "SELECT r.*, g.group_name
	FROM " . RANKS_TABLE . " r
		LEFT JOIN " . GROUPS_TABLE . " g ON g.group_id = r.rank_group
	ORDER BY r.rank_special DESC, r.rank_group DESC, r.rank_min"; 
	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, "Couldn't obtain ranks data", "", __LINE__, __FILE__, $sql);
	}
	$rank_count = $db->sql_numrows($result);

	$rank_rows = $db->sql_fetchrowset($result);
$db->sql_freeresult($result);
	
	$template->assign_vars(array(
	'L_RANKS_TITLE' => $lang['Ranks_title'],
	'L_RANKS_TEXT' => $lang['Ranks_explain'],
	'L_RANK' => $lang['RankFAQ_Title'],
	'L_RANK_MINIMUM' => $lang['RankFAQ_Min'],
	'L_SPECIAL_RANK' => $lang['Group_Rank_special'],
	'L_RANKS_IMAGE' => $lang['RankFAQ_Image'], 
	'L_EDIT' => '<img src="' . $phpbb_root_path . $images['acp_edit'] . '" alt="' . $lang['Edit'] . '" title="' . $lang['Edit'] . '" />',
	'L_DELETE' => '<img src="' . $phpbb_root_path . $images['acp_delete'] . '" alt="' . $lang['Delete'] . '" title="' . $lang['Delete'] . '" />',
	'L_ADD_RANK' => $lang['Add_new'],
		
	'S_RANKS_ACTION' => append_sid("admin_user_ranks.$phpEx"))
	);
	
	for($i = 0; $i < $rank_count; $i++)
	{
		$rank = $rank_rows[$i]['rank_title'];
		$special_rank = $rank_rows[$i]['rank_special'];
		$rank_id = $rank_rows[$i]['rank_id'];
		$rank_min = $rank_rows[$i]['rank_min'];
		$rank_image = $rank_rows[$i]['rank_image']; 
		
	if( $special_rank || $rank_rows[$i]['rank_group'] > 0 )
		{
		$rank_min = '-';
		}

		$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

	$rank_is_special = ( $special_rank ) ? $lang['Yes'] : ( $rank_rows[$i]['rank_group'] > 0 ? $rank_rows[$i]['group_name'] : $lang['No'] );
		
		$template->assign_block_vars("ranks", array(
		'ROW_CLASS' => $row_class,
		'RANK' => $rank,
		'SPECIAL_RANK' => $rank_is_special,
		'RANK_MIN' => $rank_min,
		'IMAGE_DISPLAY' => ( $rank_image != '' ) ? '<img src="' . $phpbb_root_path . 'templates/' . $style_dir . '/ranks/' . $rank_image . '" alt="' . $rank . '" title="' . $rank . '" />' : "",

		'U_RANK_EDIT' => append_sid("admin_user_ranks.$phpEx?mode=edit&amp;id=$rank_id"),
		'U_RANK_DELETE' => append_sid("admin_user_ranks.$phpEx?mode=delete&amp;id=$rank_id"))
		);
	}

$template->pparse('body');

include('../admin/page_footer_admin.'.$phpEx);

?>
