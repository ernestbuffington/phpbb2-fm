<?php 
/** 
*
* @package admin
* @version $Id: admin_lexicon_cat.php, v 0.0.4.02 2005/06/08 12:23:00 AmigaLink Exp $
* @copyright (c) 2005 AmigaLink
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
	

define('IN_PHPBB', true);

// First we do the setmodules stuff for the admin cp.
if( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	$module['Lexicon']['Categories'] = $filename;
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
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_admin_lexicon.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_admin_lexicon.' . $phpEx);


// Check and set various parameters
// mode
( isset($HTTP_GET_VARS['mode']) || isset($HTTP_POST_VARS['mode']) ) ? $mode = ( isset($HTTP_POST_VARS['mode']) ) ? htmlspecialchars($HTTP_POST_VARS['mode']) : htmlspecialchars($HTTP_GET_VARS['mode']) : $mode = '';
// Categorie_title
( isset($HTTP_GET_VARS['categorie']) || isset($HTTP_POST_VARS['categorie']) ) ? $categorie = ( isset($HTTP_POST_VARS['categorie']) ) ? htmlspecialchars($HTTP_POST_VARS['categorie']) : htmlspecialchars($HTTP_GET_VARS['categorie']) : $categorie = '';
// Categorie id
( isset($HTTP_GET_VARS['cat']) || isset($HTTP_POST_VARS['cat']) ) ? $categorie_id = ( isset($HTTP_POST_VARS['cat']) ) ? intval($HTTP_POST_VARS['cat']) : intval($HTTP_GET_VARS['cat']) : $categorie_id = 0;

$template->set_filenames(array(
	"body" => "admin/lexicon_cat_body.tpl")
);

switch ( $mode )
{
	// new
	case 'new':
		$admin_lexicon_action_url = append_sid("admin_lexicon_cat.$phpEx");

		// check if new categorie title already exists 
		$sql = "SELECT * 
			FROM " . LEXICON_CAT_TABLE;
		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not get categories list', '', __LINE__, __FILE__, $sql);
		}
		
		while($row = $db->sql_fetchrow($result))
		{
			$lexicon_categories[$row['cat_id']] = $row['cat_titel'];
		}
		
		if (array_search($categorie, $lexicon_categories))
		{
			$db->sql_freeresult($result);
			$message = '<br />' . sprintf($lang['Categorie_already_exists'], $categorie);
		}
		else
		{
			$db->sql_freeresult($result);

			// if not then generate
			$sql="INSERT INTO " . LEXICON_CAT_TABLE . " ( cat_id, cat_titel) 
				VALUES ('', '$categorie')"; 
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Error update lexicon cat-table', '', __LINE__, __FILE__, $sql);
			}

			$message = '<br />' . sprintf($lang['Categorie_caused'], $categorie);
		}

		$message .= '<br /><br /><meta http-equiv="refresh" content="3;url=' . $admin_lexicon_action_url . '">';
		
		message_die(GENERAL_MESSAGE, $message);

		$mode = '';
		break;

	// edit
	case 'edit':
		$template->assign_block_vars('switch_cat_edit', array());

		$template->assign_vars(array(
			'CATEGORIE_ADMINISTRATION' => $lang['Manage_categorys'],
			'CATEGORIE_ADMIN_EXPLAIN' => $lang['Categorie_edit_explain'],

			'CATEGORIE' => $categorie,

			'S_ACTION' => append_sid("admin_lexicon_cat.$phpEx?mode=edit_confirms&cat=$categorie_id"),
			'S_CANCEL' => append_sid("admin_lexicon_cat.$phpEx"),

			'L_ADD_CATEGORIE' => $lang['Create_category'],
			'L_CATEGORIE' => $lang['Category'],
			'L_EMPTY_CATEGORIE' => $lang['Categorie_empty'],
			'L_CANCEL' => $lang['Cancel'])
		);

		$mode = '';
		break;

	// edit confirms
	case 'edit_confirms':
		// check if new categorie title already exists 
		$sql = "SELECT * 
			FROM " . LEXICON_CAT_TABLE;
		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not get categories list', '', __LINE__, __FILE__, $sql);
		}
		while($row = $db->sql_fetchrow($result))
		{
			$lexicon_categories[$row['cat_id']] = $row['cat_titel'];
		}
		if (array_search($categorie, $lexicon_categories))
		{
			$db->sql_freeresult($result);
			$message = '<br />' . sprintf($lang['Categorie_already_exists'], $categorie);

			$admin_lexicon_action_url = append_sid("admin_lexicon_cat.$phpEx?mode=edit&categorie=$categorie&cat=$categorie_id");
			$mode = '';
		}
		else
		{
			$sql = "UPDATE " . LEXICON_CAT_TABLE . " 
				SET cat_titel = '" . $categorie . "' 
				WHERE cat_id = " . $categorie_id;
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Error update lexicon cat-table', '', __LINE__, __FILE__, $sql);
			}

			$admin_lexicon_action_url = append_sid("admin_lexicon_cat.$phpEx");
			$mode = '';

			$message = '<br />' . sprintf($lang['Categorie_worked_on'], $categorie);
		}

		$message .= '<br /><br /><meta http-equiv="refresh" content="3;url=' . $admin_lexicon_action_url . '">';
		
		message_die(GENERAL_MESSAGE, $message);

		break;

	// delete
	case 'delete':
		$sql = "DELETE FROM " . LEXICON_CAT_TABLE . " 
			WHERE cat_id = " . $categorie_id;
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Error update lexicon category', '', __LINE__, __FILE__, $sql);
		}

		$sql = "UPDATE " . LEXICON_ENTRY_TABLE . " 	
			SET cat = 1 
			WHERE cat = " . $categorie_id;
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Error update lexicon cat-table', '', __LINE__, __FILE__, $sql);
		}

		$admin_lexicon_action_url = append_sid("admin_lexicon_cat.$phpEx");
		$mode = '';

		$message = '<br />' . sprintf($lang['Categorie_deleted'], $categorie);
		
		$message .= '<br /><br /><meta http-equiv="refresh" content="3;url=' . $admin_lexicon_action_url . '">';
		
		message_die(GENERAL_MESSAGE, $message);

		break;

	// overview (default)
	default:
		$template->assign_block_vars('switch_lexicon_cat_overview', array());

		// grab lexicon categories
		$sql = "SELECT cat_id, cat_titel 
			FROM " . LEXICON_CAT_TABLE;
		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not get categories list', '', __LINE__, __FILE__, $sql);
		}

		$i = 0;
		while($row = $db->sql_fetchrow($result))
		{
			$cat_id = $row['cat_id'];
			$lexicon_categories[$row['cat_id']] = $row['cat_titel'];
			if ($row['cat_titel'] == 'default')
			{
				$categorie = '<i>' . $row['cat_titel'] . '</i>';
			}
			else
			{
				$categorie = $row['cat_titel'];
			}
			$categorie_lang = @$lang[$row['cat_titel']];
			if ($categorie_lang == ' ')
			{
				$categorie_lang = '<span style="color: #FF0000">*</span> ' . $lang['generally'];
			}
			if ($categorie_lang == '')
			{
				$categorie_lang = '<i>' . $lang['no_entry'] . '</i>';
			}

			$row_class = ( !($i % 2) ) ? $theme['td_class2'] : $theme['td_class1'];

			if ($cat_id != 1)
			{
				$s_edit = '<a href="'.append_sid("admin_lexicon_cat.$phpEx?mode=edit&categorie=$categorie&cat=$cat_id").'"><img src="' . $phpbb_root_path . $images['acp_edit'] . '" alt="' . $lang['Edit'] . '" title="' . $lang['Edit'] . '" /></a>';
				$confirm_delete = sprintf($lang['Delete_categorie'], $categorie);
				$s_delete = '<a href="'.append_sid("admin_lexicon_cat.$phpEx?mode=delete&cat=$cat_id").'"  onclick="return confirm(\''.$confirm_delete.'\')"><img src="' . $phpbb_root_path . $images['acp_delete'] . '" alt="' . $lang['Delete'] . '" title="' . $lang['Delete'] . '" /></a>';
			}
			else
			{
				$row_class = 'row3';
				$s_edit = $s_delete = '';
			}

			$template->assign_block_vars('switch_lexicon_cat_overview.cat_row', array(
				'ROW_CLASS' => $row_class,
				'CATEGORIE' => $categorie,
				'CATEGORIE_LANG' => $categorie_lang,

				'S_EDIT' => $s_edit,
				'S_DELETE' => $s_delete)
			);

			$i++;
		}
		$categories = count($lexicon_categories);
		$db->sql_freeresult($result);

		$template->assign_vars(array(
			'CATEGORIE_ADMINISTRATION' => $lang['Manage_categorys'],
			'CATEGORIE_ADMIN_EXPLAIN' => $lang['Configuration_cat_explain'],

			'S_ACTION' => append_sid("admin_lexicon_cat.$phpEx?mode=new"),
			'S_CANCEL' => append_sid("admin_lexicon_cat.$phpEx"),

			'L_CREATE' => $lang['Create'],
			'L_ADD_CATEGORIE' => $lang['Create_category'],
			'L_CATEGORIE' => $lang['Category'],
			'L_CATEGORIE_LANG' => $lang['Categorie_lang'],
			'L_EMPTY_CATEGORIE' => $lang['Categorie_empty'],
			'L_ACTION' => $lang['Action'])
		);

		break;
} // overview end

$template->pparse("body");

include('./page_footer_admin.'.$phpEx);

?>