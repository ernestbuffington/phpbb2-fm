<?php 
/** 
*
* @package admin
* @version $Id: admin_lexicon_edit.php, v 0.0.5.02 2005/06/08 00:42:00 AmigaLink Exp $
* @copyright (c) 2005 AmigaLink
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', true);

// First we do the setmodules stuff for the admin cp.
if( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	$module['Lexicon']['Management'] = $filename;
	$module['Lexicon']['Add_new'] = $filename."?mode=new";
	return;
}

// Load default header
$phpbb_root_path = './../';
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);
include($phpbb_root_path . 'includes/bbcode.'.$phpEx);
include($phpbb_root_path . 'includes/functions_post.'.$phpEx);

//
// Include language file
//
$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_admin_lexicon.'.$phpEx) )
{
	$language = 'english';
}
include($phpbb_root_path . 'language/lang_' . $language . '/lang_admin_lexicon.' . $phpEx);


//
// Check and set various parameters
// mode
( isset($HTTP_GET_VARS['mode']) || isset($HTTP_POST_VARS['mode']) ) ? $mode = ( isset($HTTP_POST_VARS['mode']) ) ? htmlspecialchars($HTTP_POST_VARS['mode']) : htmlspecialchars($HTTP_GET_VARS['mode']) : $mode = '';
// Keyword id
( isset($HTTP_GET_VARS['id']) || isset($HTTP_POST_VARS['id']) ) ? $keyword_id = ( isset($HTTP_POST_VARS['id']) ) ? intval($HTTP_POST_VARS['id']) : intval($HTTP_GET_VARS['id']) : $keyword_id = '';
// Keyword
( isset($HTTP_GET_VARS['keyword']) || isset($HTTP_POST_VARS['keyword']) ) ? $keyword = ( isset($HTTP_POST_VARS['keyword']) ) ? htmlspecialchars($HTTP_POST_VARS['keyword']) : htmlspecialchars($HTTP_GET_VARS['keyword']) : $keyword = '';
// Keyword explanation
( isset($HTTP_GET_VARS['explanation']) || isset($HTTP_POST_VARS['explanation']) ) ? $explanation = ( isset($HTTP_POST_VARS['explanation']) ) ? htmlspecialchars($HTTP_POST_VARS['explanation']) : htmlspecialchars($HTTP_GET_VARS['explanation']) : $explanation = '';
// Categorie id
( isset($HTTP_GET_VARS['cat']) || isset($HTTP_POST_VARS['cat']) ) ? $categorie_id = ( isset($HTTP_POST_VARS['cat']) ) ? intval($HTTP_POST_VARS['cat']) : intval($HTTP_GET_VARS['cat']) : $categorie_id = 0;

$html_on = false;  //  true geht nicht aufgrund von der htmlspecialchars() nutzung bei der variablen übergabe!!!
$bbcode_on = true;
$smile_on = false;

$template->set_filenames(array(
	'body' => 'admin/lexicon_edit_body.tpl')
);

switch ( $mode )
{
	// new keyword
	case 'new':
		$template->assign_block_vars('switch_add_keyword', array());

		// Grab lexicon categories
		$sql = "SELECT cat_id, cat_titel 
			FROM " . LEXICON_CAT_TABLE;
		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not get categories list', '', __LINE__, __FILE__, $sql);
		}
		
		while($row = $db->sql_fetchrow($result))
		{
			$lexicon_categories[$row['cat_id']] = $row['cat_titel'];
		}
		$db->sql_freeresult($result);

		// categories dropdown list
		$lexicon_cat_selector = '<select name="cat" style="width:80px">';
		foreach($lexicon_categories as $cat_id => $cat_titel)
		{
			$selected = ( $cat_id == $categorie_id ) ? 'selected="selected"' : '';
			$lexicon_cat_selector .= '<option value="'.$cat_id;
			($cat_id == '0') ? $lexicon_cat_selector .='" '.$selected.'>'.$cat_titel.'</option>' : $lexicon_cat_selector .= '" '.$selected.'>'.$cat_titel.'</option>';
		}
		$lexicon_cat_selector .= '</select>';

		$admin_lexicon_action_url = append_sid("admin_lexicon_edit.$phpEx?mode=new_keyword_confirms");
		$mode = '';

		$template->assign_vars(array(
			'KEYWORD_ADMINISTRATION_EDIT' => $lang['Keyword_administration_new'],
			'KEYWORD_ADMIN_EDIT_EXPLAIN' => $lang['Keyword_admin_new_explain'],

			'KEYWORD' => $keyword,
			'EXPLANATION_BODY' => $explanation,

			'S_CAT_SELECTOR' => $lexicon_cat_selector,
			'S_ACTION' => $admin_lexicon_action_url)
		);

		break;

	// confirms new keyword
	case 'new_keyword_confirms':
		$admin_lexicon_action_url = append_sid("admin_lexicon_edit.$phpEx");
		($categorie_id == 0) ? $categorie_id = 1 : '';
		$bbcode_uid = make_bbcode_uid();
		$explanation = prepare_message(trim($explanation), $html_on, $bbcode_on, $smile_on, $bbcode_uid);
		$mode = '';

		// insert new keyword in database
		$sql = "INSERT INTO " . LEXICON_ENTRY_TABLE . " (id, keyword, explanation, bbcode_uid, cat) 
			VALUES ('', '$keyword', '$explanation', '$bbcode_uid', $categorie_id)"; 
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Error update lexicon entry', '', __LINE__, __FILE__, $sql);
		}

		// Grab lexicon categories
		$sql = "SELECT cat_id, cat_titel 
			FROM " . LEXICON_CAT_TABLE;
		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not get categories list', '', __LINE__, __FILE__, $sql);
		}
		
		while($row = $db->sql_fetchrow($result))
		{
			$lexicon_categories[$row['cat_id']] = $row['cat_titel'];
		}
		$db->sql_freeresult($result);

		$message = '<br />' . sprintf($lang['Keyword_caused'], $keyword, $lexicon_categories[$categorie_id]);
		
		$message .= '<br /><br /><meta http-equiv="refresh" content="3;url=' . $admin_lexicon_action_url . '">';
		
		message_die(GENERAL_MESSAGE, $message);

		break;

	// delete keyword
	case 'delete':
		$template->assign_block_vars('switch_delete_keyword', array());

		$sql = "SELECT * FROM " . LEXICON_ENTRY_TABLE . "
			WHERE id = " . $keyword_id;
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Error getting lexicon entry', '', __LINE__, __FILE__, $sql);
		}
		
		while ( $row = $db->sql_fetchrow($result) )
		{
				$keyword = $row['keyword'];
				$explanation = $row['explanation'];
				$bbcode_uid = $row['bbcode_uid'];
				$categorie_id = $row['cat'];
		}
		$db->sql_freeresult($result);
		
		// Grab lexicon categories
		$sql = "SELECT cat_id, cat_titel 
			FROM " . LEXICON_CAT_TABLE;
		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not get categories list', '', __LINE__, __FILE__, $sql);
		}
		
		while($row = $db->sql_fetchrow($result))
		{
			$lexicon_categories[$row['cat_id']] = $row['cat_titel'];
		}
		$db->sql_freeresult($result);

		$explanation = smilies_pass($explanation);
		$explanation = bbencode_second_pass($explanation, $bbcode_uid);
		$explanation = make_clickable($explanation);
		$explanation = unprepare_message($explanation);
		$explanation = str_replace("\n", "\n<br />\n", $explanation);
		$admin_lexicon_action_url = append_sid("admin_lexicon_edit.$phpEx?id=$keyword_id&mode=deletion_confirms");
		$mode = '';

		$template->assign_vars(array(
			'KEYWORD_ADMINISTRATION_DELETE' => $lang['Keyword_administration_delete'],
			'KEYWORD_ADMIN_DELETE_EXPLAIN' => $lang['Keyword_admin_delete_explain'],

			'KEYWORD' => $keyword,
			'EXPLANATION_BODY' => $explanation,
			'CATEGORIE' => $lexicon_categories[$categorie_id],

			'S_ACTION' => $admin_lexicon_action_url)
		);

		break;

	// Keyword deletion confirms
	case 'deletion_confirms':
		$sql = "SELECT keyword FROM " . LEXICON_ENTRY_TABLE . "
			WHERE id = " . $keyword_id;
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Error getting lexicon entry', '', __LINE__, __FILE__, $sql);
		}
		
		while ( $row = $db->sql_fetchrow($result) )
		{
				$keyword = $row['keyword'];
		}
		$db->sql_freeresult($result);

		$sql = "DELETE FROM " . LEXICON_ENTRY_TABLE . "
			WHERE id = " . $keyword_id;
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Error update lexicon entry', '', __LINE__, __FILE__, $sql);
		}

		$admin_lexicon_action_url = append_sid("admin_lexicon_edit.$phpEx");
		$mode = '';

		$message = '<br />' . sprintf($lang['Keyword_deleted'], $keyword);
		
		$message .= '<br /><br /><meta http-equiv="refresh" content="3;url=' . $admin_lexicon_action_url . '">';
		
		message_die(GENERAL_MESSAGE, $message);

		break;

	// edit keyword
	case 'edit':
		$template->assign_block_vars('switch_add_keyword', array());

		$sql = "SELECT * FROM " . LEXICON_ENTRY_TABLE . "
			WHERE id = " . $keyword_id;
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Error getting lexicon entry', '', __LINE__, __FILE__, $sql);
		}
		
		while ( $row = $db->sql_fetchrow($result) )
		{
				$keyword = $row['keyword'];
				$explanation = $row['explanation'];
				$categorie_id = $row['cat'];
				$bbcode_uid = $row['bbcode_uid'];
		}
		$db->sql_freeresult($result);

		// Grab lexicon categories
		$sql = "SELECT cat_id, cat_titel 
			FROM " . LEXICON_CAT_TABLE;
		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not get categories list', '', __LINE__, __FILE__, $sql);
		}
		
		while($row = $db->sql_fetchrow($result))
		{
			$lexicon_categories[$row['cat_id']] = $row['cat_titel'];
		}
		$db->sql_freeresult($result);

		// categories dropdown list
		$lexicon_cat_selector = '<select name="cat">';
		foreach($lexicon_categories as $cat_id => $cat_titel)
		{
			$selected = ( $cat_id == $categorie_id ) ? 'selected="selected"' : '';
			$lexicon_cat_selector .= '<option value="'.$cat_id;
			($cat_id == '0') ? $lexicon_cat_selector .='" '.$selected.'>'.$cat_titel.'</option>' : $lexicon_cat_selector .= '" '.$selected.'>'.$cat_titel.'</option>';
		}
		$lexicon_cat_selector .= '</select>';

		$explanation = preg_replace('/\:(([a-z0-9]:)?)' . $bbcode_uid . '/s', '', $explanation);
		$explanation = unprepare_message($explanation);
		$admin_lexicon_action_url = append_sid("admin_lexicon_edit.$phpEx?id=$keyword_id&mode=edit_confirms");
		$mode = '';

		$template->assign_vars(array(
			'KEYWORD_ADMINISTRATION_EDIT' => $lang['Keyword_administration_edit'],
			'KEYWORD_ADMIN_EDIT_EXPLAIN' => $lang['Keyword_admin_edit_explain'],

			'KEYWORD' => $keyword,
			'EXPLANATION_BODY' => $explanation,

			'S_ACTION' => $admin_lexicon_action_url,
			'S_CAT_SELECTOR' => $lexicon_cat_selector)
		);

		break;

	// edit keyword confirms
	case 'edit_confirms':
		$template->assign_vars(array(
			'KEYWORD_ADMINISTRATION_EDIT' => $lang['Keyword_administration'],
			'KEYWORD_ADMIN_EDIT_EXPLAIN' => $lang['Keyword_admin_edit_explain'])
		);
		
		($categorie_id == 0) ? $categorie_id = 1 : '';
		$bbcode_uid = make_bbcode_uid();
		$explanation = prepare_message(trim($explanation), $html_on, $bbcode_on, $smile_on, $bbcode_uid);

		$sql = "UPDATE " . LEXICON_ENTRY_TABLE . " 
			SET keyword = '" . $keyword . "', explanation = '" . $explanation . "', bbcode_uid = '" . $bbcode_uid . "', cat = '" . $categorie_id . "' 
			WHERE id = " . $keyword_id;
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Error update lexicon entry', '', __LINE__, __FILE__, $sql);
		}

		$admin_lexicon_action_url = append_sid("admin_lexicon_edit.$phpEx");
		$mode = '';

		$message = '<br />' . sprintf($lang['Keyword_worked_on'], $keyword);
		
		$message .= '<br /><br /><meta http-equiv="refresh" content="3;url=' . $admin_lexicon_action_url . '">';
		
		message_die(GENERAL_MESSAGE, $message);

		break;

	// overview (default)
	default:
		$template->assign_block_vars('switch_lexicon_edit_overview', array());

		// grab lexicon categories
		$sql = "SELECT cat_id, cat_titel 
			FROM " . LEXICON_CAT_TABLE;
		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not get categories list', '', __LINE__, __FILE__, $sql);
		}
		
		while($row = $db->sql_fetchrow($result))
		{
			$lexicon_categories[$row['cat_id']] = $row['cat_titel'];
		}
		$categories = count($lexicon_categories);
		$db->sql_freeresult($result);

		// read lexicon entrys 
		$sql = "SELECT * FROM " . LEXICON_ENTRY_TABLE . " 
			ORDER BY keyword, cat ASC"; 
		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not get lexicon entrys', '', __LINE__, __FILE__, $sql);
		}

		$i = 0;
		while ($row = $db->sql_fetchrow($result)) 
		{ 
			$id = $row['id']; 
			$keyword = $row['keyword']; 
			$explanation = $row['explanation']; 
			// define categorie names
			$categorie = $lexicon_categories[$row['cat']];
			if ( $categorie == '' )
			{
				$categorie = $lexicon_categories[$row['cat']];
			}
			if ( $lexicon_categories[$row['cat']] == 'default' )
			{
				$categorie = '<i>' . $categorie . '</i>';
			}

			$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

			$template->assign_block_vars('switch_lexicon_edit_overview.entry_row', array(
				'ROW_CLASS' => $row_class,
				'KEYWORD' => $keyword,
				'CATEGORIE' => $categorie,

				'S_EDIT' => append_sid("admin_lexicon_edit.$phpEx?mode=edit&id=$id"),
				'S_DELETE' => append_sid("admin_lexicon_edit.$phpEx?mode=delete&id=$id"))
			);

			$i++;
		}
		$db->sql_freeresult($result);

		$template->assign_vars(array(
			'KEYWORD_ADMINISTRATION' => $lang['Keyword_administration'],
			'KEYWORD_ADMIN_EXPLAIN' => $lang['Keyword_admin_explain'])
		);

		break;
} // overview end

$template->assign_vars(array(
	'S_CANCEL' => append_sid('admin_lexicon_edit.'.$phpEx),

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
	'L_BBCODE_S_HELP' => $lang['bbcode_s_help'], 
	'L_BBCODE_F_HELP' => $lang['bbcode_f_help'], 

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

	'L_FONT_SIZE' => $lang['Font_size'], 
	'L_FONT_TINY' => $lang['font_tiny'], 
	'L_FONT_SMALL' => $lang['font_small'], 
	'L_FONT_NORMAL' => $lang['font_normal'], 
	'L_FONT_LARGE' => $lang['font_large'], 
	'L_FONT_HUGE' => $lang['font_huge'], 

	'L_BBCODE_CLOSE_TAGS' => $lang['Close_Tags'], 
	'L_STYLES_TIP' => $lang['Styles_tip'], 

	'L_CATEGORIE' => $lang['Category'],
	'L_EXPLANATION' => $lang['Explanation'],
	'L_EMPTY_EXPLANATION' => $lang['Explanation_empty'],
	'L_KEYWORD' => $lang['Keyword'],
	'L_EMPTY_KEYWORD' => $lang['Keyword_empty'],
	'L_ACTION' => $lang['Action'],
	'EDIT' => '<img src="' . $phpbb_root_path . $images['acp_edit'] . '" alt="' . $lang['Edit'] . '" title="' . $lang['Edit'] . '" />',
	'DELETE' => '<img src="' . $phpbb_root_path . $images['acp_delete'] . '" alt="' . $lang['Delete'] . '" title="' . $lang['Delete'] . '" />',
	'L_CANCEL' => $lang['Cancel'])
);

$template->pparse('body');

include('./page_footer_admin.'.$phpEx);

?>