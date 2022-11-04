<?php
/** 
*
* @package admin
* @version $Id: admin_forum_tour.php,v 1.1.1 2003/07/22 01:00:00 oxpus Exp $
* @copyright (c) 2004 OXPUS
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	$module['Forums']['Tour'] = $filename;

	return;
}

//
// Load default header
//
$no_page_header = TRUE;
$phpbb_root_path = './../';
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);
include($phpbb_root_path . 'includes/bbcode.'.$phpEx);
include($phpbb_root_path . 'includes/functions_post.'.$phpEx);

//
// Check and set various parameters
//
$params = array('submit' => 'submit', 'id' => 'id', 'default' => 'default', 'cancel' => 'cancel', 'delete' => 'delete', 'mode' => 'mode', 'edit' => 'edit', 'move' => 'move', 'confirm' => 'confirm');

while( list($var, $param) = @each($params) )
{
	if ( !empty($HTTP_POST_VARS[$param]) || !empty($HTTP_GET_VARS[$param]) )
	{
		$$var = ( !empty($HTTP_POST_VARS[$param]) ) ? $HTTP_POST_VARS[$param] : $HTTP_GET_VARS[$param];
	}
	else
	{
		$$var = '';
	}
}

$mode = ( $mode != 'cancel' || !$cancel ) ? $mode : '';
$mode = ( $mode == 'confirm' && $confirm ) ? 'delete' : $mode;
$mode = ( $cancel ) ? '' : $mode;

function reorder_pages()
{
	global $db;

	$sql = "SELECT page_id, page_sort 
		FROM " . FORUM_TOUR_TABLE . "
		ORDER BY page_sort ASC";
	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Could not get list of forum tour pages', '', __LINE__, __FILE__, $sql);
	}

	$i = 10;

	while( $row = $db->sql_fetchrow($result) )
	{
		$sql2 = "UPDATE " . FORUM_TOUR_TABLE . "
			SET page_sort = $i
			WHERE page_id = ". $row['page_id'];
		if( !$db->sql_query($sql2) )
		{
			message_die(GENERAL_ERROR, 'Could not update order forum tour pages', '', __LINE__, __FILE__, $sql);
		}
		$i += 10;
	}
}

//
// Set variables on default values
$page_subject = $page_text = $page_bbcode_uid = $page_sort = '';
$page_title = $lang['Forum_tour'];


switch ( $mode  )
{
	case 'smilies':
		$smilies_path = $board_config['smilies_path'];
		$board_config['smilies_path'] = $phpbb_root_path . $board_config['smilies_path'];
		$emoticom = FALSE;
		$mode = 'edit';

		if( $board_config['allow_smilies'] )
		{
			generate_smilies('window', PAGE_POSTING);
		}
		$board_config['smilies_path'] = $smilies_path;
		$mode = '';

		break;

	case 'submit':
		$subject = stripslashes(trim($HTTP_POST_VARS['subject']));
		$message = stripslashes(trim($HTTP_POST_VARS['message']));
		$page_access = $HTTP_POST_VARS['page_access'];

		$error = FALSE;
		$error_msg = '';

		if ( empty($subject) )
		{
			$error = true;
			$error_msg .= ( !empty($error_msg) ) ? '<br />' . $lang['Fields_empty'] : $lang['Fields_empty'];
		}

		if ( empty($message) )
		{
			$error = true;
			$error_msg .= ( !empty($error_msg) ) ? '<br />' . $lang['Fields_empty'] : $lang['Fields_empty'];
		}

		if ( $error )
		{
			include('./page_header_admin.'.$phpEx);

			$template->set_filenames(array(
				'reg_header' => 'admin/error_body.tpl')
			);
			$template->assign_vars(array(
				'ERROR_MESSAGE' => $error_msg)
			);
			$template->pparse('reg_header');

			include('./page_footer_admin.'.$phpEx);
		}

		$subject = addslashes($subject);
		$message = addslashes($message);

		if ( $id != '' )
		{
			$bbcode_uid = trim($HTTP_POST_VARS['bbcode_uid']);
			$message = make_clickable($message);
			$message = prepare_message(trim($message), 1, 1, 1, $bbcode_uid);

			$sql = "UPDATE " . FORUM_TOUR_TABLE . "
				SET page_subject = '$subject', page_text = '$message', page_access = $page_access
				WHERE page_id = $id";
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not update forum tour page', '', __LINE__, __FILE__, $sql);
			}
		}
		else
		{
			$sql = "SELECT max(page_id) AS maxid 
				FROM " . FORUM_TOUR_TABLE;
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not check forum tour page', '', __LINE__, __FILE__, $sql);
			}
			
			while ( $row = $db->sql_fetchrow($result) )
			{
				$id = $row['maxid'];
			}
	        $db->sql_freeresult($result);

			$id++;
			$page = $id * 10;

			$bbcode_uid = make_bbcode_uid();
			$message = make_clickable($message);
			$message = prepare_message(trim($message), 0, 1, 1, $bbcode_uid);

			$sql = "INSERT INTO " . FORUM_TOUR_TABLE . " (page_id, page_subject, page_text, page_sort, bbcode_uid, page_access)
				VALUES ($id, '$subject', '$message', $page, '$bbcode_uid', $page_access)";
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not insert forum tour page', '', __LINE__, __FILE__, $sql);
			}
		}
		break;

	case 'delete':
		if ( $id == '' )
		{
			message_die(GENERAL_ERROR, 'ID unknown! No delete.', '', __LINE__, __FILE__, $sql);
		}

		$sql = "DELETE FROM " . FORUM_TOUR_TABLE . "
			WHERE page_id = $id";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not delete forum tour page', '', __LINE__, __FILE__, $sql);
		}

		reorder_pages();
		break;

	case 'confirm':
		include('./page_header_admin.'.$phpEx);
		if ( $id != '' )
		{
			$template->set_filenames(array(
				'body' => 'admin/forum_tour_confirm.tpl')
			);

			$sql = "SELECT page_subject 
				FROM " . FORUM_TOUR_TABLE . "
				WHERE page_id = $id";
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not read forum tour page', '', __LINE__, __FILE__, $sql);
			}
			while ( $row = $db->sql_fetchrow($result) )
			{
				$subject = $row['page_subject'];
			}
	        $db->sql_freeresult($result);

			$template->assign_vars(array(
				'S_ACTION' => append_sid('admin_forum_tour.'.$phpEx.'?mode=confirm&amp;id='.$id),
				'L_TITLE' => $lang['Confirm'],
				'L_SUBJECT' => sprintf($lang['Confirm_delete_ft'], htmlspecialchars(trim(stripslashes($subject)))))
			);

			$template->pparse('body');
		}
		include('./page_footer_admin.'.$phpEx);

		break;

	case  'edit':
		$subject = $message = $bbcode_uid = $page = $page_access = $access = '';

		if ( $id != 0 )

		{
			$sql = "SELECT * 
				FROM ". FORUM_TOUR_TABLE ."
				WHERE page_id = $id";
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not get forum tour page', '', __LINE__, __FILE__, $sql);
			}

			$tour_page = array();
			while( $row = $db->sql_fetchrow($result) )
			{
				$tour_page = $row;
			}
	        $db->sql_freeresult($result);

			$subject = stripslashes($tour_page['page_subject']);
			$message = stripslashes($tour_page['page_text']);
			$bbcode_uid = $tour_page['bbcode_uid'];
			$page = $tour_page['page_id'];
			$page_access = $tour_page['page_access'];

			$message = preg_replace('/\:(([a-z0-9]:)?)' . $bbcode_uid . '/s', '', $message);

			$s_action = append_sid("admin_forum_tour.$phpEx?mode=submit&id=$id");
			$l_mode = $page_title . ': ' . $lang['Edit'];
			$l_mode_explain = $lang['Forum_tour_explain'];
		}
		else
		{
			$s_action = append_sid("admin_forum_tour.$phpEx?mode=submit");
			$l_mode = $page_title . ': ' . $lang['Add_new'];
			$l_mode_explain = $lang['Forum_tour_explain'];
		}

		$page_access = ( $page_access != '' ) ? $page_access : ANONYMOUS;

		$access = '<select name="page_access">';
		$access .= '<option value="' . ANONYMOUS . '">'. $lang['Guest'] . '</option>';
		$access .= '<option value="' . USER . '">' . $lang['Registered'] . ' ' . $lang['Auth_User'] . '</option>';
		$access .= '<option value="' . MOD . '">' . $lang['Moderator'] . '</option>';
		$access .= '<option value="' . LESS_ADMIN . '">' . $lang['Super_Mod'] . '</option>';
		$access .= '<option value="' . ADMIN . '">' . $lang['Administrators'] . '</option>';
		$access .= '</select>';

		$access = str_replace('value="'.$page_access.'">', 'value="'.$page_access.'" selected="selected">', $access);

		include('./page_header_admin.'.$phpEx);

		$template->set_filenames(array(
			'body' => 'admin/forum_tour_edit_body.tpl')
		);

		Multi_BBCode();

		$smilies_path = $board_config['smilies_path'];
		$board_config['smilies_path'] = $phpbb_root_path . $board_config['smilies_path'];
		if( $board_config['allow_smilies'] )
		{
			generate_smilies('inline', PAGE_POSTING);
		}
		$board_config['smilies_path'] = $smilies_path;

		$template->assign_vars(array(
			'SUBJECT' => $subject,
			'MESSAGE' => $message,
			'FORUM_LIST' => 'javascript:forum_links()',
			'USID' => append_sid("admin_forum_tour_links.$phpEx"),
			'BBCODE_UID' => $bbcode_uid,
			'PAGE_ACCESS' => $access,
			'L_INDEX' => $lang['Forum_Index'],
			'L_MODE' => $l_mode,
			'L_MODE_EXPLAIN' => $l_mode_explain,
			'L_OPTIONS' => $lang['Options'],
			'L_SPELLCHECK' => $lang['Spellcheck'],

			'L_EXPAND_BBCODE' => $lang['Expand_bbcode'],
			'L_BBCODE_B_HELP' => $lang['bbcode_b_help'], 
			'L_BBCODE_I_HELP' => $lang['bbcode_i_help'], 
			'L_BBCODE_U_HELP' => $lang['bbcode_u_help'], 
			'L_BBCODE_Q_HELP' => $lang['bbcode_q_help'], 
			'L_BBCODE_C_HELP' => $lang['bbcode_c_help'], 
			'L_BBCODE_L_HELP' => $lang['bbcode_l_help'], 
			'L_BBCODE_O_HELP' => $lang['bbcode_o_help'], 
			'L_BBCODE_P_HELP' => $lang['bbcode_p_help'], 
			'L_BBCODE_W_HELP' => $lang['bbcode_w_help'], 
			'L_BBCODE_A_HELP' => $lang['bbcode_a_help'], 
			'L_BBCODE_A1_HELP' => $lang['bbcode_a1_help'], 		
			'L_BBCODE_S_HELP' => $lang['bbcode_s_help'], 
			'L_BBCODE_F_HELP' => $lang['bbcode_f_help'], 

			'L_BBCODE_C_HELP' => $lang['bbcode_c_help'], 
			'L_BBCODE_F1_HELP' => $lang['bbcode_f1_help'],
			'L_BBCODE_G1_HELP' => $lang['bbcode_g1_help'], 
			'L_BBCODE_H1_HELP' => $lang['bbcode_h1_help'],
			'L_BBCODE_S1_HELP' => $lang['bbcode_s1_help'], 
			'L_BBCODE_SC_HELP' => $lang['bbcode_sc_help'],		

			'L_SMILIE_CREATOR' => $lang['Smilie_creator'],
			'L_EMPTY_MESSAGE' => $lang['Empty_message'],

			'L_HIGHLIGHT_COLOR' => $lang['Highlight_color'], 
			'L_SHADOW_COLOR' => $lang['Shadow_color'],
			'L_GLOW_COLOR' => $lang['Glow_color'],
			
			'L_FONT_COLOR' => $lang['Font_color'], 
			'L_COLOR_DEFAULT' => $lang['color_default'], 
			'L_COLOR_DARK_RED' => $lang['color_dark_red'], 
			'L_COLOR_RED' => $lang['color_red'], 
			'L_COLOR_ORANGE' => $lang['color_orange'], 
			'L_COLOR_BROWN' => $lang['color_brown'], 
			'L_COLOR_YELLOW' => $lang['color_yellow'], 
			'L_COLOR_GREEN' => $lang['color_green'], 
			'L_COLOR_OLIVE' => $lang['color_olive'], 
			'L_COLOR_CYAN' => $lang['color_cyan'], 
			'L_COLOR_BLUE' => $lang['color_blue'], 
			'L_COLOR_DARK_BLUE' => $lang['color_dark_blue'], 
			'L_COLOR_INDIGO' => $lang['color_indigo'], 
			'L_COLOR_VIOLET' => $lang['color_violet'], 
			'L_COLOR_WHITE' => $lang['color_white'], 
			'L_COLOR_BLACK' => $lang['color_black'], 
			'L_COLOR_CADET_BLUE' => $lang['color_cadet_blue'],
			'L_COLOR_CORAL' => $lang['color_coral'], 
			'L_COLOR_CRIMSON' => $lang['color_crimson'], 
			'L_COLOR_TOMATO' => $lang['color_tomato'], 
			'L_COLOR_SEA_GREEN' => $lang['color_sea_green'], 
			'L_COLOR_DARK_ORCHID' => $lang['color_dark_orchid'], 
			'L_COLOR_CHOCOLATE' => $lang['color_chocolate'],
			'L_COLOR_DEEPSKYBLUE' => $lang['color_deepskyblue'], 
			'L_COLOR_GOLD' => $lang['color_gold'], 
			'L_COLOR_GRAY' => $lang['color_gray'], 
			'L_COLOR_MIDNIGHTBLUE' => $lang['color_midnightblue'], 
			'L_COLOR_DARKGREEN' => $lang['color_darkgreen'], 

			'L_FONT_SIZE' => $lang['Font_size'], 
			'L_FONT_TINY' => $lang['font_tiny'], 
			'L_FONT_SMALL' => $lang['font_small'], 
			'L_FONT_NORMAL' => $lang['font_normal'], 
			'L_FONT_LARGE' => $lang['font_large'], 
			'L_FONT_HUGE' => $lang['font_huge'], 

			'L_ALIGN_TEXT' => $lang['Align_text'], 
			'L_LEFT' => $lang['text_left'], 
			'L_CENTER' => $lang['text_center'], 
			'L_RIGHT' => $lang['text_right'], 
			'L_JUSTIFY' => $lang['text_justify'], 

			'L_FONT_FACE' => $lang['Font_face'],
	
			'L_BBCODE_CLOSE_TAGS' => $lang['Close_Tags'], 
			'L_STYLES_TIP' => $lang['Styles_tip'], 
	
			'L_SUBJECT' => $lang['Subject'],
			'L_MESSAGE' => $lang['Message_body'],
			'L_ACCESS' => $lang['Permissions'],
			'S_POST_ACTION' => $s_action,
			'S_PAGE_ID' => $page,
			'L_EMOTICONS' => $lang['Emoticons'],
			'U_MORE_SMILIES' => append_sid("admin_forum_tour.$phpEx?mode=smilies"),
			'L_CLOSE' => $lang['Cancel'],
			'U_CLOSE' => append_sid("admin_forum_tour.$phpEx"))
		);

		while( list($key, $font) = each($lang['font']) )
		{
			$template->assign_block_vars ('font_styles', array(
				'L_FONTNAME' => $font)
			);
		}
			
		$template->pparse('body');
		
		include('./page_footer_admin.'.$phpEx);
		break;

	case 'move':
		$sql = "UPDATE ". FORUM_TOUR_TABLE ."
			SET page_sort = page_sort + $move
			WHERE page_id = $id";
		if( !$db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not update order forum tour pages', '', __LINE__, __FILE__, $sql);
		}

		reorder_pages();
		break;
}

//
// Default
include('./page_header_admin.'.$phpEx);

$ids = $subject = $send = $sent_to = $page_access = array();

$sql = "SELECT page_id, page_subject, page_sort, page_access 
	FROM " . FORUM_TOUR_TABLE . "
	ORDER BY page_sort";
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not get forum tour subjects', '', __LINE__, __FILE__, $sql);
}

while ( $row = $db->sql_fetchrow($result) )
{
	$ids[] = $row['page_id'];
	$subject[] = $row['page_subject'];
	$page_sort[] = $row['page_sort'];
	$page_access[] = $row['page_access'];
}
$db->sql_freeresult($result);

$template->set_filenames(array(
	'body' => 'admin/forum_tour_body.tpl')
);

$template->assign_vars(array(
	'L_PAGE_NAME' => $page_title,
	'L_PAGE_EXPLAIN' => $lang['Forum_tour_explain'],
	'L_SUBJECT' => $lang['Subject'],
	'L_ACCESS' => $lang['Permissions'],
	'L_ACTION' => $lang['Action'],
	'L_MOVE_UP' => '<img src="' . $phpbb_root_path . $images['acp_up'] . '" alt="' . $lang['Move_up'] . '" title="' . $lang['Move_up'] . '" />',
	'L_MOVE_DOWN' => '<img src="' . $phpbb_root_path . $images['acp_down'] . '" alt="' . $lang['Move_down'] . '" title="' . $lang['Move_down'] . '" />',
	'L_EDIT' => '<img src="' . $phpbb_root_path . $images['acp_edit'] . '" alt="' . $lang['Edit'] . '" title="' . $lang['Edit'] . '" />',
	'L_DELETE' => '<img src="' . $phpbb_root_path . $images['acp_delete'] . '" alt="' . $lang['Delete'] . '" title="' . $lang['Delete'] . '" />',
	'L_NEW_SITE' => $lang['Add_new'],
	'L_PREVIEW' => $lang['Preview'],
	
	'S_ACTION_ADD' => append_sid("admin_forum_tour.$phpEx?mode=edit"))
);

if ( count($ids) <= 0 )

{
	$template->assign_block_vars('no_forum_pages', array(
		'NO_SITES' => $lang['No_forum_pages'])
	);
}
else
{
	for ( $i = 0; $i < sizeof($ids); $i++ )
	{
		$access = '';
		switch ($page_access[$i])
		{
			case ANONYMOUS:
				$access = $lang['Guest'];
				break;
			case USER:
				$access = $lang['Registered'] . ' ' . $lang['Auth_User'];
				break;
			case MOD:
				$access = $lang['Moderator'];
				break;
			case LESS_ADMIN:
				$access = $lang['Super_Mod'];
				break;
			case ADMIN:
				$access = $lang['Administrators'];
				break;
			default:
				$access = $lang['Guest'];
				break;
		}

		$template->assign_block_vars('forum_tour_pages', array(
			'ROW_CLASS' => ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'],
			'SUBJECT' => htmlspecialchars(trim(stripslashes($subject[$i]))),
			'PAGE_ACCESS' => $access,
			'S_MOVE_UP' => append_sid("admin_forum_tour.$phpEx?mode=move&amp;move=-15&amp;id=$ids[$i]"),
			'S_MOVE_DOWN' => append_sid("admin_forum_tour.$phpEx?mode=move&amp;move=15&amp;id=$ids[$i]"),
			'U_EDIT' => append_sid("admin_forum_tour.$phpEx?mode=edit&amp;id=$ids[$i]"),
			'U_DELETE' => append_sid("admin_forum_tour.$phpEx?mode=confirm&amp;id=$ids[$i]"))
		);
	}
}

$template->pparse('body');

include('./page_footer_admin.'.$phpEx);

?>