<?php
/** 
 *
* @package admin_super
* @version $Id: admin_forums.php,v 1.40.2.10 2003/01/05 02:36:00 psotfx Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
	$file = basename(__FILE__);
	$module['Forums']['Manage'] = $file;
	return;
}

//
// Load default header
//
$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);
include($phpbb_root_path . 'includes/functions_admin.'.$phpEx);
include_once($phpbb_root_path . 'mods/subjectchk/admin.'.$phpEx);
include($phpbb_root_path . 'includes/functions_selects.'.$phpEx);


//
// Read a listing of uploaded icon images for use in the add or edit category/forum code...
//
$forum_icons = array();
$dir = @opendir($phpbb_root_path . 'templates/' . $theme['template_name'] . '/images/icon/');
while($file = @readdir($dir))
{
	if( !@is_dir(phpbb_realpath($phpbb_root_path . 'templates/' . $theme['template_name'] . '/images/icon/' . $file)) )
	{
		$img_size = @getimagesize($phpbb_root_path . 'templates/' . $theme['template_name'] . '/images/icon/' . $file);

		if( $img_size[0] && $img_size[1] )
		{
			$forum_icons[] = $file;
		}
	}
}
@closedir($dir);


$forum_auth_ary = array(
	'auth_view' => AUTH_ALL, 
	'auth_read' => AUTH_ALL, 
	'auth_post' => AUTH_REG, 
	'auth_reply' => AUTH_REG, 
	'auth_edit' => AUTH_REG, 
	'auth_delete' => AUTH_REG, 
	'auth_sticky' => AUTH_MOD, 
	'auth_announce' => AUTH_MOD, 
	'auth_vote' => AUTH_REG, 
	'auth_pollcreate' => AUTH_REG,
	'auth_suggest_event' => AUTH_REG,
	'auth_ban' => AUTH_MOD, 
	'auth_voteban' => AUTH_REG, 
	'auth_greencard' => AUTH_ADMIN, 
	'auth_bluecard' => AUTH_REG,
	'auth_attachments' => AUTH_REG,
	'auth_download' => AUTH_REG
);


//
// Mode setting
//
if( isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']) )
{
	$mode = ( isset($HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
	$mode = htmlspecialchars($mode); 
}
else
{
	$mode = '';
}

$hierarchie_level = ( !empty($HTTP_GET_VARS[POST_HIERARCHIE_URL]) ) ? $HTTP_GET_VARS[POST_HIERARCHIE_URL] : 0;
$parent_forum = ( !empty($HTTP_GET_VARS[POST_PARENTFORUM_URL]) ) ? $HTTP_GET_VARS[POST_PARENTFORUM_URL] : 0;

// ------------------
// Begin function block
//
function rebuild_forum_issubs()
{
	global $db;

	// first get cats and forums
	$sql = "SELECT *
		FROM " . CATEGORIES_TABLE . "
		WHERE cat_hier_level >= 1
		ORDER BY cat_order, cat_id";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, "Couldn't obtain category list.", "", __LINE__, __FILE__, $sql);
	}
	
	$category_rows = array();
	while ( $row = $db->sql_fetchrow($result) )
	{
		$category_rows[] = $row;
	}
	
	if ( $total_categories = count($category_rows) )
	{
		$sql = "SELECT *
			FROM " . FORUMS_TABLE . "
			ORDER BY cat_id, forum_order";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not obtain forums information', '', __LINE__, __FILE__, $sql);
		}

		$forum_rows = array();
		while ( $row = $db->sql_fetchrow($result) )
		{
			$forum_rows[] = $row;
		}

		if ( $total_forums = count($forum_rows) )
		{
			$sql_set_issub = '';
			for($i=0;$i<$total_forums;$i++)
			{
				for($j=0;$j<$total_categories;$j++)
				{
					if( $forum_rows[$i]['forum_id'] == $category_rows[$j]['parent_forum_id'] )
					{
						$sql_set_issub .= $forum_rows[$i]['forum_id'] . ' ,';
						break;
					}
				} // for ... categories
			} // for ... forums

			// last char is ',' and thus scrap
			$sql_set_issub = substr($sql_set_issub, 0, -1);
			
			$sql = "UPDATE " . FORUMS_TABLE . "
				SET forum_issub = " . FORUM_ISNOSUB;
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, "Couldn't clear forums tables 'forum_issub'.", "", __LINE__, __FILE__, $sql);
			}

			$sql = "UPDATE " . FORUMS_TABLE . "
				SET forum_issub = " . FORUM_ISSUB . "
				WHERE forum_id IN ($sql_set_issub)";
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, "Couldn't set forums tables 'forum_issub'.", "", __LINE__, __FILE__, $sql);
			}
		} // if ... total_forums
	} // if ... total_categories
}

function rebuild_hierarchie_tables()
{
	global $db;

	// init arrays
	$parent_forums = $parent_categories = $cat_rel_cat = $cat_rel_forum = array(); // forum_id's, cat_id's, parent_cat_id, parent_forum_id

	// Lets walk through the hierarchie
	// first get cats and forums
	$sql = "SELECT *
		FROM " . CATEGORIES_TABLE . "
		ORDER BY cat_order, cat_id";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, "Couldn't obtain category list.", "", __LINE__, __FILE__, $sql);
	}
	
	$category_rows = array();
	while ( $row = $db->sql_fetchrow($result) )
	{
		$category_rows[] = $row;
	}
	$db->sql_freeresult($result);
	
	if ( $total_categories = sizeof($category_rows) )
	{
		$sql = "SELECT *
			FROM " . FORUMS_TABLE . "
			ORDER BY cat_id, forum_order";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not obtain forums information', '', __LINE__, __FILE__, $sql);
		}

		$forum_rows = array();
		while ( $row = $db->sql_fetchrow($result) )
		{
			$forum_rows[] = $row;
		}
		$db->sql_freeresult($result);

		if ( $total_forums = sizeof($forum_rows) )
		{
      rebuild_hierarchie_tables_recursive($cat_rel_cat, $cat_rel_forum, $category_rows, $forum_rows, $parent_categories, $parent_forums, $total_categories, $total_forums, 0, 0);
		}
	}
	
	$total_cat_rel_cat = sizeof($cat_rel_cat);
	if( $total_cat_rel_cat > 0 )
	{
		$sql_cat_rel_cat = '(' . $cat_rel_cat[0]['cat_id'] . ',' . $cat_rel_cat[0]['parent_cat_id'] . ')';
		for($i=1; $i < $total_cat_rel_cat; $i++)
		{
			$sql_cat_rel_cat .= ', (' . $cat_rel_cat[$i]['cat_id'] . ',' . $cat_rel_cat[$i]['parent_cat_id'] . ')';
		}
	}
	
	$total_cat_rel_forum = sizeof($cat_rel_forum);
	if( $total_cat_rel_forum > 0 )
	{
		$sql_cat_rel_forum = '(' . $cat_rel_forum[0]['cat_id'] . ',' . $cat_rel_forum[0]['parent_forum_id'] . ')';
		for($i=1; $i < $total_cat_rel_forum; $i++)
		{
			$sql_cat_rel_forum .= ', (' . $cat_rel_forum[$i]['cat_id'] . ',' . $cat_rel_forum[$i]['parent_forum_id'] . ')';
		}
	}

	// Empty hierarchie tables
	$sql = "DELETE FROM " . CAT_REL_CAT_PARENTS_TABLE;
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, "Couldn't empty categorie hierarchie table.", "", __LINE__, __FILE__, $sql);
	}
	
	$sql = "DELETE FROM " . CAT_REL_FORUM_PARENTS_TABLE;
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, "Couldn't empty forum hierarchie table.", "", __LINE__, __FILE__, $sql);
	}
	
	// Insert new data
	if( isset($sql_cat_rel_cat) )
	{
		$sql = "INSERT INTO " . CAT_REL_CAT_PARENTS_TABLE . " VALUES " . $sql_cat_rel_cat;
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, "Couldn't insert categorie data into hierarchie tables.", "", __LINE__, __FILE__, $sql);
		}
	}
	
	if( isset($sql_cat_rel_forum) )
	{
		$sql = "INSERT INTO " . CAT_REL_FORUM_PARENTS_TABLE . " VALUES " . $sql_cat_rel_forum;
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, "Couldn't insert forum data into hierarchie tables.", "", __LINE__, __FILE__, $sql);
		}
	}
}

function rebuild_hierarchie_tables_recursive(&$cat_rel_cat, &$cat_rel_forum, &$category_rows, &$forum_rows, &$parent_categories, &$parent_forums, &$total_categories, &$total_forums, $current_hierarchie, $parent_forum_id)
{
	for($i = 0; $i < $total_categories; $i++)
	{
    	if ( $category_rows[$i]['cat_hier_level'] != $current_hierarchie || $category_rows[$i]['parent_forum_id'] != $parent_forum_id )
		continue;
		
		if( sizeof($parent_categories) > 0 )
		{
			foreach($parent_categories as $categorie)
			{
				$arr = array('cat_id' => $category_rows[$i]['cat_id'], 'parent_cat_id' => $categorie);
				array_push($cat_rel_cat, $arr);
			}
		}
		
		if( sizeof($parent_forums) > 0 )
		{
			foreach($parent_forums as $forum)
			{
				$arr = array('cat_id' => $category_rows[$i]['cat_id'], 'parent_forum_id' => $forum);
				array_push($cat_rel_forum, $arr);
			}
		}
		
		array_push($parent_categories, $category_rows[$i]['cat_id']);

		for($j = 0; $j < $total_forums; $j++)
		{
			if ( $forum_rows[$j]['cat_id'] == $category_rows[$i]['cat_id'] && $forum_rows[$j]['forum_hier_level'] == $current_hierarchie )
			{
				array_push($parent_forums, $forum_rows[$j]['forum_id']);
	      if ( $forum_rows[$j]['forum_issub'] == FORUM_ISSUB)
				{
					rebuild_hierarchie_tables_recursive($cat_rel_cat, $cat_rel_forum, $category_rows, $forum_rows, $parent_categories, $parent_forums, $total_categories, $total_forums, $current_hierarchie+1, $forum_rows[$j]['forum_id']);
				}
				array_pop($parent_forums);
			}
		}
		array_pop($parent_categories);
	}
}

function get_info($mode, $id)
{
	global $db;

	switch($mode)
	{
		case 'category':
			$table = CATEGORIES_TABLE;
			$idfield = 'cat_id';
			$namefield = 'cat_title';
			break;

		case 'forum':
			$table = FORUMS_TABLE;
			$idfield = 'forum_id';
			$namefield = 'forum_name';
			break;

		default:
			message_die(GENERAL_ERROR, "Wrong mode for generating select list", "", __LINE__, __FILE__);
			break;
	}
	
	$sql = "SELECT COUNT(*) as total
		FROM $table";
	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, "Couldn't get Forum/Category information", "", __LINE__, __FILE__, $sql);
	}
	$count = $db->sql_fetchrow($result);
	$count = $count['total'];

	$sql = "SELECT *
		FROM $table
		WHERE $idfield = $id"; 
	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, "Couldn't get Forum/Category information", "", __LINE__, __FILE__, $sql);
	}

	if( $db->sql_numrows($result) != 1 )
	{
		message_die(GENERAL_ERROR, "Forum/Category doesn't exist or multiple forums/categories with ID $id", "", __LINE__, __FILE__);
	}

	$return = $db->sql_fetchrow($result);
	$return['number'] = $count;
	
	return $return;
}

function get_list($mode, $id, $select, $restriction="")
{
	global $db;

	switch($mode)
	{
		case 'category':
			$selectfield = '*';
			$table = CATEGORIES_TABLE;
			$idfield = 'cat_id';
			$namefield = 'cat_title';
			if( !empty($restriction) )
			{
				$wherefield = $idfield . " NOT IN (" . $restriction . ")";
			}
			break;

		case 'forum':
			$selectfield = '*';
			$table = FORUMS_TABLE;
			$idfield = 'forum_id';
			$namefield = 'forum_name';
			if( !empty($restriction) )
			{
				$wherefield = $idfield . " NOT IN (" . $restriction . ")";
			}
			break;

		case 'cattitle_forum':
			$selectfield = 'CONCAT(c.cat_title, ", ", f.forum_name) as category_forum_name, f.forum_id';
			$table = CATEGORIES_TABLE . " c, " . FORUMS_TABLE . " f";
			$idfield = 'forum_id';
			$namefield = 'category_forum_name';
			if( !empty($restriction) )
			{
				$wherefield = $idfield . " NOT IN (" . $restriction . ") AND ";
			}
			$wherefield .= 'f.cat_id = c.cat_id';
			$orderfield = 'c.cat_hier_level';
			break;

		default:
			message_die(GENERAL_ERROR, "Wrong mode for generating select list", "", __LINE__, __FILE__);
			break;
	}

	$sql = "SELECT $selectfield
		FROM $table";
	if( $select == 0 )
	{
		$sql .= " WHERE $idfield <> $id ";
		if( isset($wherefield) )
		{
			$sql .= "AND " . $wherefield;
		}
	}
	else
	{
		if( isset($wherefield) )
		{
			$sql .= " WHERE " . $wherefield;
		}
	}
	if( isset($orderfield) )
	{
		$sql .= " ORDER BY " . $orderfield;
	}

	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, "Couldn't get list of Categories/Forums", "", __LINE__, __FILE__, $sql);
	}

	$cat_list = '';

	while( $row = $db->sql_fetchrow($result) )
	{
		$s = '';
		if ($row[$idfield] == $id)
		{
			$s = ' selected="selected"';
		}
		$catlist .= "<br /><option value=\"$row[$idfield]\"$s>" . $row[$namefield] . "</option>\n";
	}
	return($catlist);
}

function renumber_order($mode, $cat = 0)
{
	global $db;

	switch($mode)
	{
		case 'category':
			$table = CATEGORIES_TABLE;
			$idfield = 'cat_id';
			$orderfield = 'cat_order';
			$cat = 0;
			break;

		case 'forum':
			$table = FORUMS_TABLE;
			$idfield = 'forum_id';
			$orderfield = 'forum_order';
			$catfield = 'cat_id';
			break;

		default:
			message_die(GENERAL_ERROR, "Wrong mode for generating select list", "", __LINE__, __FILE__);
			break;
	}

	$sql = "SELECT * FROM $table";
	if( $cat != 0)
	{
		$sql .= " WHERE $catfield = $cat";
	}
	$sql .= " ORDER BY $orderfield ASC";

	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, "Couldn't get list of Categories", "", __LINE__, __FILE__, $sql);
	}

	$i = $inc = 10;

	while( $row = $db->sql_fetchrow($result) )
	{
		$sql = "UPDATE $table
			SET $orderfield = $i
			WHERE $idfield = " . $row[$idfield];
		if( !$db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, "Couldn't update order fields", "", __LINE__, __FILE__, $sql);
		}
		$i += 10;
	}
	$db->sql_freeresult($result);
}

function typeout_hierarchie_recursive(&$category_rows, &$forum_rows, &$total_categories, &$total_forums, $current_hierarchie, $parent_forum_id, $start_hierarchie = -1)
{
  	global $template, $phpEx, $phpbb_root_path, $lang, $theme, $images;

	if( $start_hierarchie == -1 )
	{
		$start_hierarchie = $current_hierarchie;
	}
	
	for($i = 0; $i < $total_categories; $i++)
	{
		if ( $category_rows[$i]['cat_hier_level'] != $current_hierarchie || $category_rows[$i]['parent_forum_id'] != $parent_forum_id )
			continue;

		$cat_id = $category_rows[$i]['cat_id'];

		$current_block = "catrowh0";
		for($j=1;$j<= ( $current_hierarchie - $start_hierarchie ) ;$j++)
		{
			$current_block .= ".forumrowh".($j-1).".catrowh$j";
		}
			
		$template->assign_block_vars($current_block, array( 
			'CAT_ID' => $cat_id,
			'CAT_DESC' => $category_rows[$i]['cat_title'],
			'CAT_ICON' => ( $category_rows[$i]['cat_icon'] && $category_rows[$i]['cat_icon'] != 'icon0.gif' && $category_rows[$i]['cat_icon'] != 'none.gif' ) ? '<img src="' . $phpbb_root_path . 'templates/' . $theme['template_name'] . '/images/icon/' . $category_rows[$i]['cat_icon'] . '" alt="" />' : '',

			'U_CAT_EDIT' => append_sid("admin_forums.$phpEx?mode=editcat&amp;" . POST_CAT_URL . "=$cat_id"),
			'U_CAT_DELETE' => append_sid("admin_forums.$phpEx?mode=deletecat&amp;" . POST_CAT_URL . "=$cat_id"),
			'U_CAT_MOVE_UP' => append_sid("admin_forums.$phpEx?mode=cat_order&amp;move=-1&amp;" . POST_CAT_URL . "=$cat_id"),
			'U_CAT_MOVE_DOWN' => append_sid("admin_forums.$phpEx?mode=cat_order&amp;move=1&amp;" . POST_CAT_URL . "=$cat_id"),
			'U_VIEWCAT' => append_sid($phpbb_root_path."index.$phpEx?" . POST_HIERARCHIE_URL . "=" . $category_rows[$i]['cat_hier_level'] . "&" . POST_PARENTFORUM_URL . "=" . $category_rows[$i]['parent_forum_id'] . "&" . POST_CAT_URL . "=$cat_id"))
		);

		for($j = 0; $j < $total_forums; $j++)
		{
			if ( $forum_rows[$j]['cat_id'] == $cat_id && $forum_rows[$j]['forum_hier_level'] == $current_hierarchie )
			{
				$forum_id = $forum_rows[$j]['forum_id'];

				if ( $forum_rows[$j]['forum_issub'] )
				{
					$u_forum_viewasroot = "admin_forums.$phpEx?" . POST_HIERARCHIE_URL . "=" . ($forum_rows[$j]['forum_hier_level']+1) . "&" . POST_PARENTFORUM_URL . "=" . $forum_rows[$j]['forum_id'];
					$l_forum_viewasroot = $lang['Forum_viewasroot'];

					$viewforum = $phpbb_root_path . "index.$phpEx?" . POST_HIERARCHIE_URL . "=" . ($forum_rows[$j]['forum_hier_level']+1) . "&" .	POST_PARENTFORUM_URL . "=" . $forum_id;
				}
				else
				{
					$u_forum_viewasroot = $l_forum_viewasroot = '';

					$viewforum = $phpbb_root_path."viewforum.$phpEx?" . POST_FORUM_URL . "=$forum_id";
				}
				
				// Folder image
				if ( $forum_rows[$j]['forum_status'] == FORUM_LOCKED )
				{
					$folder_image = $phpbb_root_path . $images['forum_locked']; 
				}
				else if ($forum_rows[$j]['auth_view'] == 5)
				{
					$folder_image = $phpbb_root_path . $images['forum_admin']; 
				}
				else if ($forum_rows[$j]['forum_issub'])
				{
					$folder_image = $phpbb_root_path . $images['forum_subforum'];
				}
				else if ($forum_rows[$j]['forum_external'])
				{	
					$folder_image = ($forum_rows[$j]['forum_ext_image']) ? $forum_rows[$j]['forum_ext_image'] : $phpbb_root_path . $images['forum_external'];
				}
				else
				{
					$folder_image = $phpbb_root_path . $images['forum']; 
				}

				$template->assign_block_vars("$current_block.forumrowh".($current_hierarchie-$start_hierarchie),	array(
					'ROW_COLOR' => $row_color,
					'FORUM_FOLDER_IMG' => $folder_image,
					'FORUM_FOLDER_ALT' => '',
					'FORUM_ID' => $forum_rows[$j]['forum_id'],
					'FORUM_NAME' => $forum_rows[$j]['forum_name'],
					'FORUM_DESC' => $forum_rows[$j]['forum_desc'],
					'NUM_TOPICS' => $forum_rows[$j]['forum_topics'],
					'NUM_POSTS' => $forum_rows[$j]['forum_posts'],
					'FORUM_ICON' => ( $forum_rows[$j]['forum_icon'] && $forum_rows[$j]['forum_icon'] != 'icon0.gif' && $forum_rows[$j]['forum_icon'] != 'none.gif' ) ? '<img src="' . $phpbb_root_path . 'templates/' . $theme['template_name'] . '/images/icon/' . $forum_rows[$j]['forum_icon'] . '" alt="" align="left" />' : '',

					'U_VIEWFORUM' => append_sid($viewforum),
					'U_FORUM_EDIT' => append_sid("admin_forums.$phpEx?mode=editforum&amp;" . POST_FORUM_URL . "=$forum_id"),
					'U_FORUM_DELETE' => append_sid("admin_forums.$phpEx?mode=deleteforum&amp;" . POST_FORUM_URL . "=$forum_id"),
					'U_FORUM_MOVE_UP' => append_sid("admin_forums.$phpEx?mode=forum_order&amp;move=-1&amp;" . POST_FORUM_URL . "=$forum_id"),
					'U_FORUM_MOVE_DOWN' => append_sid("admin_forums.$phpEx?mode=forum_order&amp;move=1&amp;" . POST_FORUM_URL . "=$forum_id"),
					'U_FORUM_RESYNC' => append_sid("admin_forums.$phpEx?mode=forum_sync&amp;" . POST_FORUM_URL . "=$forum_id"),
					'U_EDIT_PERMS' => append_sid("admin_forumauth.$phpEx?adv=1&amp;" . POST_FORUM_URL . "=$forum_id"), 
					'U_FORUM_VIEWASROOT' => append_sid($u_forum_viewasroot),

					'L_FORUM_ID' => $lang['Forum_id'],
					'L_FORUM_VIEWASROOT' => $l_forum_viewasroot)
				);
				
	      if ( $forum_rows[$j]['forum_issub'] && $current_hierarchie < $start_hierarchie + 2 )
				{
					typeout_hierarchie_recursive($category_rows, $forum_rows, $total_categories, $total_forums, $current_hierarchie+1, $forum_id, $start_hierarchie);
				}
			}// if ... forumid == catid
		} // for ... forums
	} // for ... categories
}

function typeout_hidden_posts($current_hierarchie = -1, $parent_forum_id = -1)
{
	global $db, $template, $phpEx;

	$sql = "SELECT CONCAT(c.cat_title, ', ', f.forum_name) AS cat_forum_title, f.forum_id, COUNT(*) AS total_posts
		FROM " . FORUMS_TABLE . " f, " . POSTS_TABLE . " p, " . CATEGORIES_TABLE . " c
		WHERE f.forum_issub = 1
		AND p.forum_id = f.forum_id
		AND f.cat_id = c.cat_id
		AND f.forum_hier_level >= $current_hierarchie";
	if( $parent_forum_id > 0 && $current_hierarchie > -1 )
	{
		$foruminfo = get_info('forum', $parent_forum_id);
		$inferiorforums = get_list_inferior('forum', $foruminfo['cat_id']);
		$sql .= " AND f.forum_id IN ($inferiorforums)";
	}
	
	$sql .= " GROUP BY f.forum_name";
	
	if( !($result = $db->sql_query($sql)) || $db->sql_numrows($result) == 0)
	{
		$template->assign_block_vars('switch_nohiddenposts', array());
	}
	else
	{
		while( $row = $db->sql_fetchrow($result) )
		{
			$sql = "SELECT f.forum_id, f.forum_name, f.forum_issub, COUNT(*) AS total_topics
			FROM " . FORUMS_TABLE . " f, " . TOPICS_TABLE . " t
			WHERE f.forum_issub = '1'
			AND t.forum_id = f.forum_id
			GROUP BY f.forum_name";
	
			if( !($result2 = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not query hierarchie title', '', __LINE__, __FILE__, $sql);
			}
			$row2 = $db->sql_fetchrow($result2);

			$template->assign_block_vars('hiddenpostsrow', array(
				'FORUM_NAME' => $row['cat_forum_title'],
				'U_VIEWFORUM' => append_sid('../index.'.$phpEx.'?'. POST_HIERARCHIE_URL . '=' . $row2['forum_issub'] . '&amp;' . POST_PARENTFORUM_URL . '=' . $row['forum_id']),
				'NUM_TOPICS' => $row2['total_topics'],
				'NUM_POSTS' => $row['total_posts'],
				'U_POSTS_DELETE' => append_sid("admin_forums.$phpEx?mode=deleteposts&amp;" . POST_FORUM_URL . "=" . $row['forum_id']),
				'U_POSTS_MOVE' => append_sid("admin_forums.$phpEx?mode=moveposts&amp;" . POST_FORUM_URL . "=" . $row['forum_id']))
			);
		}
	}
}
//
// End function block
// ------------------

if( !empty($HTTP_POST_VARS['event_type_option_text']) || !empty($HTTP_POST_VARS['add_event_type_option_text'])  )
{
	$event_type_options = $event_type_colors = array();
	if ( !empty($HTTP_POST_VARS['event_type_option_text']) )
	{
		while( (list($category_id, $event_type_option_text) = @each($HTTP_POST_VARS['event_type_option_text'])) && (list($category_id, $event_type_color) = @each($HTTP_POST_VARS['event_type_color'])))
		{
			if( isset($HTTP_POST_VARS['del_event_type_option'][$category_id]) )
			{
				unset($event_type_options[$category_id]);
				unset($event_type_colors[$category_id]);
			}
			else if ( !empty($event_type_option_text) ) 
			{
				$event_type_options[$category_id] = htmlspecialchars(trim(stripslashes($event_type_option_text)));
				$event_type_colors[$category_id] = htmlspecialchars(trim(stripslashes($event_type_color)));
			}
		}
	}

	if (!empty($HTTP_POST_VARS['add_event_type_option_text']) )
	{
		$event_type_options[] = htmlspecialchars(trim(stripslashes($HTTP_POST_VARS['add_event_type_option_text'])));
		$event_type_colors[] = htmlspecialchars(trim(stripslashes($HTTP_POST_VARS['add_event_type_color'])));
	}
}
else if ($mode == 'editforum')
{
	$sql = "SELECT * FROM " . MYCALENDAR_EVENT_TYPES_TABLE . " 
		WHERE forum_id = " . intval($HTTP_GET_VARS[POST_FORUM_URL]);
	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain forum event categories.', '', __LINE__, __FILE__, $sql);
	}
	
	while ($row = $db->sql_fetchrow($result))
	{
		$event_type_options[$row['event_type_id']] = $row['event_type_text'];
		$event_type_colors[$row['event_type_id']] = $row['highlight_color'];
	}
	$db->sql_freeresult($result);
}

if (isset($add_event_type_option) || isset($del_event_type_option) || isset($edit_event_type_option))
{
		$mode = 'editforum';
}

//
// Begin program proper
//
if( isset($HTTP_POST_VARS['addforum']) || isset($HTTP_POST_VARS['addcategory']) )
{
	$mode = ( isset($HTTP_POST_VARS['addforum']) ) ? "addforum" : "addcat";

	if( $mode == "addforum" )
	{
		$cat_id = $HTTP_POST_VARS['addforum'];
		$cat_id = intval($cat_id);
		
		// 
		// stripslashes needs to be run on this because slashes are added when the forum name is posted
		//
		$forumname = htmlspecialchars(strip_tags(stripslashes($HTTP_POST_VARS['forumname'][$cat_id])));
	}
}

if( !empty($HTTP_POST_VARS['password']) )
{
	if( !preg_match("#^[A-Za-z0-9]{3,20}$#si", $HTTP_POST_VARS['password']) )
	{
		message_die(GENERAL_MESSAGE, $lang['Only_alpha_num_chars']);
	}
}

if( !empty($mode) ) 
{
	switch($mode)
	{
		case 'addforum':
		case 'editforum':
			//
			// Show form to create/modify a forum
			//
			if ($mode == 'editforum')
			{
				// $newmode determines if we are going to INSERT or UPDATE after posting?
				$l_title = $lang['Edit_forum'];
				$newmode = 'modforum';
				$buttonvalue = $lang['Update'];

				$forum_id = intval($HTTP_GET_VARS[POST_FORUM_URL]);

				if ($forum_id == 0) 
				{
					$forum_id = intval($HTTP_POST_VARS[POST_FORUM_URL]);
				}

				$row = get_info('forum', $forum_id);

				$cat_id = $row['cat_id'];
				$forumname = $row['forum_name'];
				$forumdesc = $row['forum_desc'];
				$forumstatus = $row['forum_status'];
				$forumicon = $row['forum_icon'];
				$forum_external = $row['forum_external'];
				$forum_redirect_url = $row['forum_redirect_url'];
				$forum_ext_newwin = $row['forum_ext_newwin'];
				$forum_ext_image = $row['forum_ext_image'];
				$forumissub = $row['forum_issub'];
				$forumposts = $row['forum_posts'];
				$forumtopics = $row['forum_topics'];
				$issub_enabled = ( $row['forum_issub'] ) ? $issub_enabled = 'checked="checked"' : '';
				$image_ever_thumb = $row['image_ever_thumb'];
				$events_forum = $row['events_forum'];
				$icon_enable = $row['icon_enable'];
				$answered_enable = $row['answered_enable'];
				$points_disabled = $row['points_disabled'];	
				$forum_views = $row['forum_views'];
				$forum_regdate_limit = $row['forum_regdate_limit'];
				$forum_enter_limit = $row['forum_enter_limit'];
				$forum_rules = $row['forum_rules'];
				$display_moderators = $row['display_moderators'];
				$amazon_display = $row['amazon_display'];
				$forumthank = $row['forum_thank'];
				$forumsort = $row['forum_sort'];
				$forum_digest = $row['forum_digest'];
				$forum_password = $row['forum_password'];
				$hidestatus = $row['hide_forum_on_index'];
				$catstatus = $row['hide_forum_in_cat'];
				$index_posts = $row['index_posts'];
				$forum_template = style_select($row['forum_template'], 'forum_template', '../templates');
				$stop_bumping_yes = ($row['stop_bumping'] == TRUE) ? 'checked="checked"' : '';
				$stop_bumping_no = ($row['stop_bumping'] == FALSE ) ? 'checked="checked"' : '';
				switch ($board_config['stop_bumping'])
				{
					case 1:
						$stop_bumping_text = $lang['Stop_bumping_on'];
						break;
					case 2:
						$stop_bumping_text = $lang['Stop_bumping_fs'];
						break;
					default:
						$stop_bumping_text = $lang['Stop_bumping_off'];
						break;
				}
				$stop_bumping_fselect_explain = sprintf($lang['Stop_bumping_fselect_explain'], $stop_bumping_text);
				$forum_toggle = $row['forum_toggle'];	
				$index_lasttitle = $row['index_lasttitle'];	
				$display_pic_alert = $row['display_pic_alert'];	

				//
				// start forum prune stuff.
				//
				if( $row['prune_enable'] )
				{
					$prune_enabled = "checked=\"checked\"";
					$sql = "SELECT *
               			FROM " . PRUNE_TABLE . "
               			WHERE forum_id = $forum_id";
					if(!$pr_result = $db->sql_query($sql))
					{
						 message_die(GENERAL_ERROR, 'Auto-Prune: Could not read auto_prune table.', '', __LINE__, __FILE__, $sql);
        			}

					$pr_row = $db->sql_fetchrow($pr_result);
				}
				else
				{
					$prune_enabled = '';
				}

				//
				// start forum move stuff.
				//
				if( $row['move_enable'] )
				{
					$move_enabled = 'checked="checked"';
					$sql = "SELECT *
               			FROM " . MOVE_TABLE . "
               			WHERE forum_id = $forum_id";
					if(!$mv_result = $db->sql_query($sql))
					{
						 message_die(GENERAL_ERROR, 'Auto-Move: Could not read auto_move table.', '', __LINE__, __FILE__, $sql);
        			}

					$mv_row = $db->sql_fetchrow($mv_result);
				}
				else
				{
					$move_enabled = '';
				}
				$forum_dest = $mv_row['forum_dest'];
			}
			else
			{
				$l_title = $lang['Create_forum'];
				$newmode = 'createforum';
				$buttonvalue = $lang['Create_forum'];

				$stop_bumping_yes = $forumdesc = $forum_id = $prune_enabled = $move_enabled = $forum_dest = $forum_redirect_url = $forum_ext_image = $forum_rules = $forum_password = $forumsort = $forum_regdate_limit = '';
				$display_pic_alert = $index_lasttitle = $forum_toggle = $forum_external = $forum_ext_newwin = $forumissub = $forumposts = $forumtopics = $image_ever_thumb = $events_forum = $icon_enable = $answered_enable = $amazon_display = $forum_views = $forum_digest = $forum_enter_limit = $forumstatus = $forumthank = $hidestatus = $catstatus = FORUM_UNLOCKED;
				$points_disabled = $display_moderators = $index_posts = 1;
				$forumicon = 'none.gif';
				$forum_template = style_select('', 'forum_template', '../templates');
				
				$stop_bumping_no = 'checked="checked"';
				switch ($board_config['stop_bumping'])
				{
					case 1:
						$stop_bumping_text = $lang['Stop_bumping_on'];
						break;
					case 2:
						$stop_bumping_text = $lang['Stop_bumping_fs'];
						break;
					default:
						$stop_bumping_text = $lang['Stop_bumping_off'];
						break;
				}
				$stop_bumping_fselect_explain = sprintf($lang['Stop_bumping_fselect_explain'], $stop_bumping_text);
			}

			$exclude = get_list_inferior('category', $cat_id);
			$catlist = get_list('category', $cat_id, TRUE, $exclude);
			$forumlist = make_forum_select('forum_dest', '', $forum_dest);

			$forumstatus == ( FORUM_LOCKED ) ? $forumlocked = 'selected="selected"' : $forumunlocked = 'selected="selected"';
			
			// These two options ($lang['Status_unlocked'] and $lang['Status_locked']) seem to be missing from the language files.
			$lang['Status_unlocked'] = isset($lang['Status_unlocked']) ? $lang['Status_unlocked'] : 'Unlocked';
			$lang['Status_locked'] = isset($lang['Status_locked']) ? $lang['Status_locked'] : 'Locked';
			
			$statuslist = "<option value=\"" . FORUM_UNLOCKED . "\" $forumunlocked>" . $lang['Status_unlocked'] . "</option>\n";
			$statuslist .= "<option value=\"" . FORUM_LOCKED . "\" $forumlocked>" . $lang['Status_locked'] . "</option>\n";

			$forumsort == ( SORT_ALPHA ) ? $sortalpha = ' selected="selected"' : $sortfpdate = ' selected="selected"';
			$sort_order = "<option value=\"" . SORT_ALPHA . "\" $sortalpha>" . $lang['Sort_alpha'] . "</option>\n";
			$sort_order .= "<option value=\"" . SORT_FPDATE . "\" $sortfpdate>" . $lang['Sort_fpdate'] . "</option>\n"; 

			$forum_icon_list = '';
			for( $i = 0; $i < sizeof($forum_icons); $i++ )
			{
				if( $forum_icons[$i] == $forumicon )
				{
					$icon_selected = ' selected="selected"';
					$icon_img = $forum_icons[$i];
				}
				else
				{
					$icon_selected = '';
				}
				$forum_icon_list .= '<option value="' . $forum_icons[$i] . '"' . $icon_selected . '>' . $forum_icons[$i] . '</option>';
			}

			$template->set_filenames(array(
				'body' => 'admin/forum_edit_body.tpl',
				'categories' => 'admin/forum_events_body.tpl')
			);

			$subchk->print_forum_fields();

			$s_hidden_fields = '<input type="hidden" name="mode" value="' . $newmode .'" /><input type="hidden" name="' . POST_FORUM_URL . '" value="' . $forum_id . '" />';

			$template->assign_vars(array(
				'S_FORUM_ACTION' => append_sid("admin_forums.$phpEx"),
				'S_HIDDEN_FIELDS' => $s_hidden_fields,
				'S_SUBMIT_VALUE' => $buttonvalue, 
				'S_CAT_LIST' => $catlist,
				'S_STATUS_LIST' => $statuslist,
				'S_PRUNE_ENABLED' => $prune_enabled,
				'S_MOVE_ENABLED' => $move_enabled,
				'S_FORUM_LIST' => $forumlist,
				'S_ISSUB_ENABLED' => $issub_enabled,
				'INDEX_POSTS_YES' => ($index_posts) ? 'checked="checked"' : '',			
				'INDEX_POSTS_NO' => (!$index_posts) ? 'checked="checked"' : '',
				'TOPICS' => $forumtopics,
				'POSTS' => $forumposts,
				'FORUM_VIEWS' => $forum_views,
				'PRUNE_DAYS' => ( isset($pr_row['prune_days']) ) ? $pr_row['prune_days'] : 7,
				'PRUNE_FREQ' => ( isset($pr_row['prune_freq']) ) ? $pr_row['prune_freq'] : 1,
				'MOVE_DAYS' => ( isset($mv_row['move_days']) ) ? $mv_row['move_days'] : 7,
				'MOVE_FREQ' => ( isset($mv_row['move_freq']) ) ? $mv_row['move_freq'] : 1,
				'FORUM_NAME' => $forumname,
				'FORUM_ICON' => $phpbb_root_path . 'templates/' . $theme['template_name'] . '/images/icon/' . $icon_img, 
				'S_FILENAME_OPTIONS' => $forum_icon_list, 
				'S_ICON_BASEDIR' => $phpbb_root_path . 'templates/' . $theme['template_name'] . '/images/icon/',
				'I_PICK_COLOR' => $phpbb_root_path . $images['acp_icon_pickcolor'],

				'FORUM_EXTERNAL_YES' => ($forum_external) ? 'checked="checked"' : '',
				'FORUM_EXTERNAL_NO' => (!$forum_external) ? 'checked="checked"' : '',
				'FORUM_REDIRECT_URL' => $forum_redirect_url,
				'FORUM_EXT_NEWWIN_YES' => ($forum_ext_newwin) ? 'checked="checked"' : '',
				'FORUM_EXT_NEWWIN_NO' => (!$forum_ext_newwin) ? 'checked="checked"' : '',
				'FORUM_EXT_IMAGE' => $forum_ext_image,
				'IMAGE_EVER_THUMBS_YES' => ($image_ever_thumb) ? 'checked="checked"' : '',
				'IMAGE_EVER_THUMBS_NO' => (!$image_ever_thumb) ? 'checked="checked"' : '',
				'CAL_EVENTS_YES' => ($events_forum) ? 'checked="checked"' : '',
				'CAL_EVENTS_NO' => (!$events_forum) ? 'checked="checked"' : '',
				'ICON_ENABLE_YES' => ($icon_enable) ? 'checked="checked"' : '',
				'ICON_ENABLE_NO' => (!$icon_enable) ? 'checked="checked"' : '',
				'ANSWERED_ENABLE_YES' => ($answered_enable) ? 'checked="checked"' : '',
				'ANSWERED_ENABLE_NO' => (!$answered_enable) ? 'checked="checked"' : '',
				'POINTS_DISABLED_YES' => ($points_disabled) ? 'checked="checked"' : '',
				'POINTS_DISABLED_NO' => (!$points_disabled) ? 'checked="checked"' : '',
				'FORUM_ENTER_LIMIT' => $forum_enter_limit,
				'FORUM_REGDATE_LIMIT' => $forum_regdate_limit,
				'FORUM_RULES' => $forum_rules,
				'DESCRIPTION' => $forumdesc,
				'MODERATORS_YES' => ($display_moderators) ? 'checked="checked"' : '',
				'MODERATORS_NO' => (!$display_moderators) ? 'checked="checked"' : '',
				'AMAZON_YES' => ($amazon_display) ? 'checked="checked"' : '',
				'AMAZON_NO' => (!$amazon_display) ? 'checked="checked"' : '',
				'THANK_YES' => ($forumthank) ? 'checked="checked"' : '',
				'THANK_NO' => (!$forumthank) ? 'checked="checked"' : '',
				'FORUM_DIGEST_CHECKED_YES' => ($forum_digest) ? 'checked="checked"' : '',			
				'FORUM_DIGEST_CHECKED_NO' => (!$forum_digest) ? 'checked="checked"' : '',
				'S_SORT_ORDER' => $sort_order,
				'FORUM_PASSWORD' => $forum_password,
				'HIDE_STATUS_YES' => ($hidestatus) ? 'checked="checked"' : '',			
				'HIDE_STATUS_NO' => (!$hidestatus) ? 'checked="checked"' : '',
				'HIDE_CAT_STATUS_YES' => ($catstatus) ? 'checked="checked"' : '',			
				'HIDE_CAT_STATUS_NO' => (!$catstatus) ? 'checked="checked"' : '',
				'FORUM_TEMPLATE' => $forum_template,
				'STOP_BUMPING_YES' => $stop_bumping_yes,
				'STOP_BUMPING_NO' => $stop_bumping_no,
				'FORUM_TOGGLE_YES' => ($forum_toggle) ? 'checked="checked"' : '',			
				'FORUM_TOGGLE_NO' => (!$forum_toggle) ? 'checked="checked"' : '',
				'INDEX_LASTTITLE_YES' => ($index_lasttitle) ? 'checked="checked"' : '',			
				'INDEX_LASTTITLE_NO' => (!$index_lasttitle) ? 'checked="checked"' : '',
				'DISPLAY_PIC_ALERT_YES' => ($display_pic_alert) ? 'checked="checked"' : '',			
				'DISPLAY_PIC_ALERT_NO' => (!$display_pic_alert) ? 'checked="checked"' : '',
					
				'L_DISPLAY_PIC_ALERT' => $lang['Display_pic_alert'],
				'L_DISPLAY_PIC_ALERT_EXPLAIN' => $lang['Display_pic_alert_explain'],
				'L_STOP_BUMPING' => $lang['Stop_bumping_fselect'],
				'L_STOP_BUMPING_EXPLAIN' => $stop_bumping_fselect_explain,
				'L_FORUM_TOGGLES' => $lang['Forum_toggles'],
				'L_EVENTS_FORUM' => $lang['Events_Forum'],
				'L_CREATE_CATEGORY' => $lang['CreateCat'],
				'L_CREATE_FORUM' => $lang['CreateFor'],
				'L_FORUM_TOPICS' => $lang['Topics'],
				'L_FORUM_POSTS' => $lang['Posts'],
				'L_FORUM_ISSUB' => $lang['Forum_issub'],
				'L_FORUM_TITLE' => $l_title, 
				'L_FORUM_EXPLAIN' => $lang['Forum_edit_delete_explain'], 
				'L_FORUM_SETTINGS' => $lang['Forum_settings'], 
				'L_FORUM_NAME' => $lang['Forum_name'], 
				'L_CATEGORY' => $lang['Forum'] . ' ' . $lang['Category'], 
				'L_FORUM_DESCRIPTION' => $lang['Forum_desc'],
				'L_FORUM_DESCRIPTION_EXPLAIN' => $lang['group_description_explain'],
				'L_FORUM_RULES' => $lang['Forum_rules'],
				'L_FORUM_STATUS' => $lang['Forum_status'],
				'L_FORUM_ICON' => $lang['Forum_icon'],
				'L_AUTO_PRUNE' => $lang['Forum_pruning'],
				'L_ENABLED' => $lang['Enabled'],
				'L_PRUNE_DAYS' => $lang['prune_days'],
				'L_PRUNE_FREQ' => $lang['prune_freq'],
				'L_DAYS' => $lang['Days'],
				'L_AUTO_MOVE' => $lang['Forum_moving'],
				'L_MOVE_DAYS' => $lang['move_days'],
				'L_MOVE_FREQ' => $lang['move_freq'],
				'L_MOVE_DEST' => $lang['move_dest'],
				'L_ICON_ENABLED' => $lang['Enable_msg_icons'], 
				'L_ANSWERED_ENABLED' => $lang['Enable_answered_icons'], 
				'L_POINTS_DISABLED' => sprintf($lang['Points_disabled'], $board_config['points_name']),
				'L_EXTERNAL_SETTINGS' => $lang['Forum_external_mang'],
				'L_FORUM_EXTERNAL' => $lang['Forum_external'],
				'L_FORUM_REDIRECT_URL' => $lang['Forum_redirect_url'],
				'L_FORUM_EXT_NEWWIN' => $lang['Forum_ext_newwin'],
				'L_FORUM_EXT_IMAGE' => $lang['Forum_ext_image'],
				'L_IMAGE_EVER_THUMBS' => $lang['Image_ever_thumb'],
				'L_IMAGE_EVER_THUMBS_EXPLAIN' => $lang['Image_ever_thumb_explain'],
				'L_FORUM_VIEWS' => $lang['Forum'] . ' ' . $lang['Views'],
				'L_FORUM_REGDATE_LIMIT' => $lang['Forum_regdate_limit'],
				'L_FORUM_REGDATE_LIMIT_EXPLAIN' => $lang['Forum_regdate_limit_explain'],
				'L_FORUM_ENTER_LIMIT' => $lang['Forum_enter_limit'],
				'L_FORUM_ENTER_LIMIT_EXPLAIN' => $lang['Forum_enter_limit_explain'],
				'L_FORUM_THANK' => $lang['use_thank'],
				'L_SORT' => $lang['Forum_sort_order'],
				'L_DIGEST' => $lang['Allow_forum_digest'],
				'L_PASSWORD' => $lang['Forum_password'],
				'L_HIDE_STATUS' => $lang['Hide_status'],
				'L_HIDE_CAT_STATUS' => $lang['Hide_cat_status'],
				'L_HIDE_CAT_STATUS_EXPLAIN' => $lang['Hide_cat_status_explain'],
				'L_AMAZON_DISPLAY' => $lang['Display_Amazon_Ads'],
				'L_INDEX_POSTS' => $lang['index_posts'],
				'L_INDEX_LASTTITLE' => $lang['Index_lasttitle'],
				'L_SUBTEMPLATE' => $lang['Subtemplate'],
				'L_SUBTEMPLATE_EXPLAIN' => $lang['Subtemplate_explain'],
				'L_FORUM_TOGGLE' => $lang['Forum_toggle'],
				'L_DISPLAY_MODERATORS' => $lang['Display_moderators'])
			);
	
		if ( $mode != 'addforum' && $events_forum )
		{
			$template->assign_vars(array(
				'L_NEW' => $lang['Create_new'],
				'L_ADD_EVENT_TYPE' => $lang['Add_event_type'],  
				'L_ADD_EVENT_TYPE_EXPLAIN' => $lang['Add_event_type_explain'],   
					'L_EVENT_TYPE_TEXT' => $lang['Event_type'],  
					'L_EVENT_TYPE_COLOR' => $lang['Event_color'],  
				'L_ADD_CATEGORY_OPTION' => $lang['Mini_Cal_add_event'],
					'L_UPDATE_OPTION' => $lang['Update'],
				'L_DELETE_OPTION' => $lang['Delete'], 
				'L_EVENT_TYPE_DELETE' => $lang['Delete_event_types'])
			);

			$template->assign_block_vars('switch_event_type_delete_toggle', array());

			if( !empty($event_type_options) )
			{
				while( (list($category_id, $event_type_option_text) = each($event_type_options)) && (list($category_id, $event_type_color) = each($event_type_colors)))
				{
					$template->assign_block_vars('event_type_option_rows', array(
						'EVENT_TYPE_OPTION' => str_replace('"', '&quot;', $event_type_option_text), 
						'EVENT_TYPE_COLOR' => $event_type_color, 
	
						'S_EVENT_TYPE_OPTION_NUM' => $category_id)
					);
				}
			}

			$template->assign_var_from_handle('EVENT_TYPES_BOX', 'categories');
		}

		$template->pparse("body");
		break;

		case 'createforum':
			//
			// Create a forum in the DB
			//
			if( trim($HTTP_POST_VARS['forumname']) == '' )
			{
				message_die(GENERAL_MESSAGE, "Can't create a forum without a name");
			}

			$sql = "SELECT MAX(forum_order) AS max_order
				FROM " . FORUMS_TABLE . "
				WHERE cat_id = " . intval($HTTP_POST_VARS[POST_CAT_URL]);
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't get order number from forums table", "", __LINE__, __FILE__, $sql);
			}
			$row = $db->sql_fetchrow($result);

			$max_order = $row['max_order'];
			$next_order = $max_order + 10;
			
			$sql = "SELECT MAX(forum_id) AS max_id
				FROM " . FORUMS_TABLE;
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't get order number from forums table", "", __LINE__, __FILE__, $sql);
			}
			$row = $db->sql_fetchrow($result);

			$max_id = $row['max_id'];
			
			if ( $max_id <= 0 )
			{
				$next_id = 1;
			}
			else
			{
			$next_id = $max_id + 1;
			}
			
			//
			// Default permissions of public :: 
			//
			$field_sql = $value_sql = '';
			while( list($field, $value) = each($forum_auth_ary) )
			{
				$field_sql .= ", $field";
				$value_sql .= ", $value";

			}

			$subchk->add_to_insert();

			//
			// Get hierarchie
			//
			$cat_id = intval($HTTP_POST_VARS[POST_CAT_URL]);
			
			$sql = "SELECT cat_hier_level
				FROM " . CATEGORIES_TABLE . "
				WHERE cat_id = $cat_id";
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't get hierarchie from categories table", "", __LINE__, __FILE__, $sql);
			}
			$row = $db->sql_fetchrow($result);

			$hier_level = $row['cat_hier_level'];

			// There is no problem having duplicate forum names so we won't check for it.
			$sql = "INSERT INTO " . FORUMS_TABLE . " (forum_id, forum_name, forum_icon, cat_id, forum_desc, forum_order, forum_status, forum_template, forum_external, forum_redirect_url, forum_ext_newwin, forum_ext_image, forum_hier_level, forum_issub, prune_enable, image_ever_thumb, move_enable, events_forum, icon_enable, answered_enable, points_disabled, forum_views, forum_rules, forum_regdate_limit, forum_enter_limit, display_moderators, amazon_display, forum_thank, forum_digest, forum_password, hide_forum_on_index, hide_forum_in_cat, forum_sort, stop_bumping, forum_toggle, index_lasttitle, display_pic_alert, index_posts" . $field_sql . ")
				VALUES ('" . $next_id . "', '" . htmlspecialchars(strip_tags(str_replace("\'", "''", $HTTP_POST_VARS['forumname']))) . "', '" . str_replace("\'", "''", $HTTP_POST_VARS['forumicon']) . "', " . $cat_id . ", '" . str_replace("\'", "''", $HTTP_POST_VARS['forumdesc']) . "', $next_order, " . intval($HTTP_POST_VARS['forumstatus']) . ", " . intval($HTTP_POST_VARS['forum_template']) . ", " . intval($HTTP_POST_VARS['forum_external']) . ", '" . $HTTP_POST_VARS['forum_redirect_url'] . "', " . intval($HTTP_POST_VARS['forum_ext_newwin']) . ", '" . $HTTP_POST_VARS['forum_ext_image'] . "', " . $hier_level . ", " . FORUM_ISNOSUB . ", " . intval($HTTP_POST_VARS['prune_enable']) . ", " . intval($HTTP_POST_VARS['image_ever_thumb']) . ", " . intval($HTTP_POST_VARS['move_enable']) . ", " . intval($HTTP_POST_VARS['events_forum']) . ", " .  intval($HTTP_POST_VARS['answered_enable']) . ", " .  intval($HTTP_POST_VARS['answered_enable']) . ", " .  intval($HTTP_POST_VARS['points_disabled']) . ", " .  intval($HTTP_POST_VARS['forum_views']) . ", '" . str_replace("\'", "''", $HTTP_POST_VARS['forum_rules']) . "', " . intval($HTTP_POST_VARS['forum_regdate_limit']) . ", " . intval($HTTP_POST_VARS['forum_enter_limit']) . ", "  . intval($HTTP_POST_VARS['display_moderators']) . ", "  . intval($HTTP_POST_VARS['amazon_display']) . ", " . intval($HTTP_POST_VARS['forumthank']) . ", " . intval($HTTP_POST_VARS['forum_digest']) . ", '" . str_replace("\'", "''", $HTTP_POST_VARS['password']) . "', " . intval($HTTP_POST_VARS['hide_forum_on_index']) . ", " . intval($HTTP_POST_VARS['hide_forum_in_cat']) . ", '" . str_replace("\'", "''", $HTTP_POST_VARS['forumsort']) . "', " . intval($HTTP_POST_VARS['stop_bumping']) . ", " . intval($HTTP_POST_VARS['forum_toggle']) . ", " . intval($HTTP_POST_VARS['index_lasttitle']) . ", " . intval($HTTP_POST_VARS['display_pic_alert']) . ", " . intval($HTTP_POST_VARS['index_posts']) . $value_sql . ")";
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't insert row in forums table", "", __LINE__, __FILE__, $sql);
			}

			if( $HTTP_POST_VARS['prune_enable'] )
			{

				if( $HTTP_POST_VARS['prune_days'] == "" || $HTTP_POST_VARS['prune_freq'] == "")
				{
					message_die(GENERAL_MESSAGE, $lang['Set_prune_data']);
				}

				$sql = "INSERT INTO " . PRUNE_TABLE . " (forum_id, prune_days, prune_freq)
					VALUES('" . $next_id . "', " . intval($HTTP_POST_VARS['prune_days']) . ", " . intval($HTTP_POST_VARS['prune_freq']) . ")";
				if( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't insert row in prune table", "", __LINE__, __FILE__, $sql);
				}
			}

			if( $HTTP_POST_VARS['move_enable'] )
			{

				if( $HTTP_POST_VARS['move_days'] == '' || $HTTP_POST_VARS['move_freq'] == '' || $HTTP_POST_VARS['forum_dest'] == '' )
				{
					message_die(GENERAL_MESSAGE, $lang['Set_move_data']);
				}

				$sql = "INSERT INTO " . MOVE_TABLE . " (forum_id, forum_dest, move_days, move_freq)
					VALUES('" . $next_id . "', " . intval($HTTP_POST_VARS['forum_dest']) . ", " . intval($HTTP_POST_VARS['move_days']) . ", " . intval($HTTP_POST_VARS['move_freq']) . ")";
				if( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't insert row in move table", "", __LINE__, __FILE__, $sql);
				}
			}

			$message = $lang['Forums_updated'] . "<br /><br />" . sprintf($lang['Click_return_forumadmin'], "<a href=\"" . append_sid("admin_forums.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

			message_die(GENERAL_MESSAGE, $message);

			break;

		case 'modforum':
			// Get last cat_id of forum
			//
			$forum_id = intval($HTTP_POST_VARS[POST_FORUM_URL]);
			$new_cat_id = intval($HTTP_POST_VARS[POST_CAT_URL]);
		
			$sql = "SELECT cat_id
				FROM " . FORUMS_TABLE . "
				WHERE forum_id = $forum_id";
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't get old cat_id from forums table", "", __LINE__, __FILE__, $sql);
			}
			$row = $db->sql_fetchrow($result);

			$old_cat_id = $row['cat_id'];

			if( $new_cat_id != $old_cat_id )
			{
				rebuild_hierarchie_tables();
			}

			// Get hierarchie
			$sql = "SELECT cat_hier_level
				FROM " . CATEGORIES_TABLE . "
				WHERE cat_id = $new_cat_id";
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't get hierarchie from categories table", "", __LINE__, __FILE__, $sql);
			}
			$row = $db->sql_fetchrow($result);

			$hier_level = $row['cat_hier_level'];

			// Modify a forum in the DB
			if( isset($HTTP_POST_VARS['prune_enable']))
			{
				if( $HTTP_POST_VARS['prune_enable'] != 1 )
				{
					$HTTP_POST_VARS['prune_enable'] = 0;
				}
			}

			if( isset($HTTP_POST_VARS['move_enable']))
			{
				if( $HTTP_POST_VARS['move_enable'] != 1 )
				{
					$HTTP_POST_VARS['move_enable'] = 0;
				}
			}

			$sql = "UPDATE " . FORUMS_TABLE . "
				SET forum_name = '" . htmlspecialchars(strip_tags(str_replace("\'", "''", $HTTP_POST_VARS['forumname']))) . "', cat_id = $new_cat_id, forum_desc = '" . str_replace("\'", "''", $HTTP_POST_VARS['forumdesc']) . "', forum_icon = '" . str_replace("\'", "''", $HTTP_POST_VARS['forumicon']) . "', forum_status = " . intval($HTTP_POST_VARS['forumstatus']) . ", forum_template = " . intval($HTTP_POST_VARS['forum_template']) . ", forum_external = " . intval($HTTP_POST_VARS['forum_external']) . ", forum_redirect_url = '" . str_replace("\'", "''", $HTTP_POST_VARS['forum_redirect_url']) . "', forum_ext_newwin = " . intval($HTTP_POST_VARS['forum_ext_newwin']) . ", forum_ext_image = '" . str_replace("\'", "''", $HTTP_POST_VARS['forum_ext_image']) . "', forum_hier_level = " . $hier_level . ", prune_enable = " . intval($HTTP_POST_VARS['prune_enable']) . ", image_ever_thumb = " . intval($HTTP_POST_VARS['image_ever_thumb']) . ", move_enable = " . intval($HTTP_POST_VARS['move_enable']) . ", events_forum = " . intval($HTTP_POST_VARS['events_forum']) . ", icon_enable = " . intval($HTTP_POST_VARS['icon_enable']) . ", answered_enable = " . intval($HTTP_POST_VARS['answered_enable']) . ", points_disabled = " . intval($HTTP_POST_VARS['points_disabled']) . ", forum_toggle = " . intval($HTTP_POST_VARS['forum_toggle']) . ", index_lasttitle = " . intval($HTTP_POST_VARS['index_lasttitle']) . ", display_pic_alert = " . intval($HTTP_POST_VARS['display_pic_alert']) . ", forum_views = " . intval($HTTP_POST_VARS['forum_views']) . ", forum_rules = '" . str_replace("\'", "''", $HTTP_POST_VARS['forum_rules']) . "', forum_regdate_limit = " . intval($HTTP_POST_VARS['forum_regdate_limit']) . ", forum_enter_limit = " . intval($HTTP_POST_VARS['forum_enter_limit']) . ", display_moderators = " . intval($HTTP_POST_VARS['display_moderators']) . ", amazon_display = " . intval($HTTP_POST_VARS['amazon_display']) . ", forum_thank = " . intval($HTTP_POST_VARS['forumthank']) . ", forum_digest = " . intval($HTTP_POST_VARS['forum_digest']) . ", forum_password = '" . str_replace("\'", "''", $HTTP_POST_VARS['password']) . "', hide_forum_on_index = " . intval($HTTP_POST_VARS['hide_forum_on_index']) . ", hide_forum_in_cat = " . intval($HTTP_POST_VARS['hide_forum_in_cat']) . ", forum_sort = '" . str_replace("\'", "''", $HTTP_POST_VARS['forumsort']) . "', stop_bumping = " . intval($HTTP_POST_VARS['stop_bumping']) . ", index_posts = " . intval($HTTP_POST_VARS['index_posts']) . "
				WHERE forum_id = " . $forum_id;
			$subchk->add_to_update();
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't update forum information", "", __LINE__, __FILE__, $sql);
			}

			if( $HTTP_POST_VARS['prune_enable'] == 1 )
			{
				if( $HTTP_POST_VARS['prune_days'] == "" || $HTTP_POST_VARS['prune_freq'] == "" )
				{
					message_die(GENERAL_MESSAGE, $lang['Set_prune_data']);
				}

				$sql = "SELECT *
					FROM " . PRUNE_TABLE . "
					WHERE forum_id = " . intval($HTTP_POST_VARS[POST_FORUM_URL]);
				if( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't get forum Prune Information","",__LINE__, __FILE__, $sql);
				}

				if( $db->sql_numrows($result) > 0 )
				{
					$sql = "UPDATE " . PRUNE_TABLE . "
						SET prune_days = " . intval($HTTP_POST_VARS['prune_days']) . ", prune_freq = " . intval($HTTP_POST_VARS['prune_freq']) . "
				 		WHERE forum_id = " . intval($HTTP_POST_VARS[POST_FORUM_URL]);
				}
				else
				{
					$sql = "INSERT INTO " . PRUNE_TABLE . " (forum_id, prune_days, prune_freq)
						VALUES(" . intval($HTTP_POST_VARS[POST_FORUM_URL]) . ", " . intval($HTTP_POST_VARS['prune_days']) . ", " . intval($HTTP_POST_VARS['prune_freq']) . ")";
				}

				if( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't Update Forum Prune Information","",__LINE__, __FILE__, $sql);
				}
			}

			if( $HTTP_POST_VARS['move_enable'] == 1 )
			{
				if( $HTTP_POST_VARS['move_days'] == "" || $HTTP_POST_VARS['move_freq'] == "" || $HTTP_POST_VARS['forum_dest'] == "" )
				{
					message_die(GENERAL_MESSAGE, $lang['Set_prune_data']);
				}

				$sql = "SELECT *
					FROM " . MOVE_TABLE . "
					WHERE forum_id = " . intval($HTTP_POST_VARS[POST_FORUM_URL]);
				if( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't get forum Move Information","",__LINE__, __FILE__, $sql);
				}

				if( $db->sql_numrows($result) > 0 )
				{
					$sql = "UPDATE " . MOVE_TABLE . "
						SET	forum_dest = " . intval($HTTP_POST_VARS['forum_dest']) . ", move_days = " . intval($HTTP_POST_VARS['move_days']) . ", move_freq = " . intval($HTTP_POST_VARS['move_freq']) . "
				 		WHERE forum_id = " . intval($HTTP_POST_VARS[POST_FORUM_URL]);
				}
				else
				{
					$sql = "INSERT INTO " . MOVE_TABLE . " (forum_id, forum_dest, move_days, move_freq)
						VALUES(" . intval($HTTP_POST_VARS[POST_FORUM_URL]) . ", " . intval($HTTP_POST_VARS['forum_dest']) . ", " . intval($HTTP_POST_VARS['move_days']) . ", " . intval($HTTP_POST_VARS['move_freq']) . ")";
				}

				if( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't Update Forum Move Information","",__LINE__, __FILE__, $sql);
				}
			}

			$sql = "DELETE FROM " . MYCALENDAR_EVENT_TYPES_TABLE . " 
				WHERE forum_id = " . intval($HTTP_POST_VARS[POST_FORUM_URL]);
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not delete existing forum event type data.', '',__LINE__, __FILE__, $sql);
			}
			
			if( !empty($event_type_options) )
			{
				while( (list($category_id, $event_type_option_text) = each($event_type_options)) &&	(list($category_id, $event_type_color) = each($event_type_colors)))
				{
					$sql = "INSERT INTO " . MYCALENDAR_EVENT_TYPES_TABLE . " (forum_id, event_type_id, event_type_text, highlight_color) 
							VALUES(" . intval($HTTP_POST_VARS[POST_FORUM_URL]) . ", $category_id, '" . str_replace("'", "\'", $event_type_option_text) . "', '$event_type_color')";
					if( !$result = $db->sql_query($sql) )
					{
						message_die(GENERAL_ERROR, 'Could not insert forum event type data.', '',__LINE__, __FILE__, $sql);
					}
				}
			}

			rebuild_hierarchie_tables();

			$message = $lang['Forums_updated'] . "<br /><br />" . sprintf($lang['Click_return_forumadmin'], "<a href=\"" . append_sid("admin_forums.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

			message_die(GENERAL_MESSAGE, $message);

			break;
			
		case 'addcat':
		case 'editcat':
			// Show form to edit a category
			//
			if( $mode == 'editcat' )
			{
			
				$l_title = $lang['Edit_Category'];
				$newmode = 'modcat';
				$buttonvalue = $lang['Update'];

				$cat_id = intval($HTTP_GET_VARS[POST_CAT_URL]);
	
				$row = get_info('category', $cat_id);
				$categoryname = $row['cat_title'];
				$parent_forum_id = $row['parent_forum_id'];
				$catsponsorimg = $row['cat_sponsor_img'];
				$catsponsorurl = $row['cat_sponsor_url'];
				$catsponsoralt = $row['cat_sponsor_alt'];
				$caticon = $row['cat_icon'];
			}
			else
			{
				$l_title = $lang['Create_category'];
				$newmode = 'createcat';
				$buttonvalue = $lang['Create_category'];
				$parent_forum_id = 0;
				$catsponsorimg = $catsponsorurl = $catsponsoralt = '';
				$caticon = 'none.gif';
			}

			$exclude = get_list_inferior('forum', $cat_id);
			$forumlist = '<select name="' . POST_PARENTFORUM_URL . '">' . '<option value="0">' . $lang['hierarchie_root'] . '</option>' . get_list('cattitle_forum', $parent_forum_id, TRUE, $exclude) . '</select>';

			//
			// Create icon dropdown
			// 
			$cat_icon_list = '';
			for( $i = 0; $i < sizeof($forum_icons); $i++ )
			{
				if( $forum_icons[$i] == $caticon )
				{
					$icon_selected = ' selected="selected"';
					$icon_img = $forum_icons[$i];
				}
				else
				{
					$icon_selected = '';
				}
				$cat_icon_list .= '<option value="' . $forum_icons[$i] . '"' . $icon_selected . '>' . $forum_icons[$i] . '</option>';
			}

			$template->set_filenames(array(
				"body" => "admin/category_edit_body.tpl")
			);

			$s_hidden_fields = '<input type="hidden" name="mode" value="' . $newmode . '" /><input type="hidden" name="' . POST_CAT_URL . '" value="' . $cat_id . '" />';

			$template->assign_vars(array(
				'CAT_TITLE' => $categoryname,
				'PARENT_FORUM_ID' => $forumlist,
				'CAT_SPONSOR_IMG' => $catsponsorimg,
				'CAT_SPONSOR_URL' => $catsponsorurl,
				'CAT_SPONSOR_ALT' => $catsponsoralt,
				'CAT_ICON' => $phpbb_root_path . 'templates/' . $theme['template_name'] . '/images/icon/' . $icon_img, 
				'S_FILENAME_OPTIONS' => $cat_icon_list, 
				'S_ICON_BASEDIR' => $phpbb_root_path . '/templates/' . $theme['template_name'] . '/images/icon/',

				'L_EDIT_CATEGORY' => $l_title,
				'L_EDIT_CATEGORY_EXPLAIN' => $lang['Edit_Category_explain'], 
				'L_CATEGORY' => $lang['Cat_name'], 
				'L_PARENT_FORUM_ID' => $lang['Cat_parent_forum_id'],
		            'L_CAT_SPONSOR_IMG' => $lang['Cat_sponsor_img'], 
		            'L_CAT_SPONSOR_URL' => $lang['Cat_sponsor_url'], 
		            'L_CAT_SPONSOR_ALT' => $lang['Cat_sponsor_alt'], 
				'L_CATEGORY_ICON' => $lang['Category_icon'],
				'L_PHPBB_ROOT_DIR' => $lang['phpBB_root_dir'],

				'S_HIDDEN_FIELDS' => $s_hidden_fields, 
				'S_SUBMIT_VALUE' => $buttonvalue, 
				'S_FORUM_ACTION' => append_sid("admin_forums.$phpEx"))
			);

			$template->pparse("body");
			break;

		case 'createcat':
			// Create a category in the DB
			if( trim($HTTP_POST_VARS['cat_title']) == '')
			{
				message_die(GENERAL_MESSAGE, "Can't create a category without a name");
			}

			$sql = "SELECT MAX(cat_order) AS max_order
				FROM " . CATEGORIES_TABLE;
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't get order number from categories table", "", __LINE__, __FILE__, $sql);
			}
			$row = $db->sql_fetchrow($result);

			$max_order = $row['max_order'];
			$next_order = $max_order + 10;

			// Get hierarchie level
			$parent_forum_id = intval($HTTP_POST_VARS[POST_PARENTFORUM_URL]);
			if( $parent_forum_id > 0 )
			{
				$sql = "SELECT forum_hier_level
					FROM " . FORUMS_TABLE ."
					WHERE forum_id = $parent_forum_id";
				if( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't get hierarchie level from forums table", "", __LINE__, __FILE__, $sql);
				}
				$row = $db->sql_fetchrow($result);
				
				$parent_hierarchie = $row['forum_hier_level'];
				$next_hier_level = $parent_hierarchie + 1;
			}
			else
			{
				$next_hier_level = 0;
			}

			//
			// There is no problem having duplicate forum names so we won't check for it.
			//
			$sql = "INSERT INTO " . CATEGORIES_TABLE . " (cat_title, cat_icon, cat_order, parent_forum_id, cat_hier_level)
				VALUES ('" . str_replace("\'", "''", $HTTP_POST_VARS['cat_title']) . "', '" . str_replace("\'", "''", $HTTP_POST_VARS['cat_icon']) . "', $next_order, $parent_forum_id, $next_hier_level)";
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't insert row in categories table", "", __LINE__, __FILE__, $sql);
			}

			rebuild_forum_issubs();			
			rebuild_hierarchie_tables();
			sync('all forums', 0);

			$message = $lang['Forums_updated'] . "<br /><br />" . sprintf($lang['Click_return_forumadmin'], "<a href=\"" . append_sid("admin_forums.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

			message_die(GENERAL_MESSAGE, $message);

			break;
			
		case 'modcat':
			// Modify a category in the DB
			$parent_forum_id = intval($HTTP_POST_VARS[POST_PARENTFORUM_URL]);
			if( $parent_forum_id > 0 )
			{
				$sql = "SELECT forum_hier_level
					FROM " . FORUMS_TABLE ."
					WHERE forum_id = $parent_forum_id";
				if( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't get hierarchie level from forums table", "", __LINE__, __FILE__, $sql);
				}
				$row = $db->sql_fetchrow($result);
				
				$parent_hierarchie = $row['forum_hier_level'];
				$next_hier_level = $parent_hierarchie + 1;
			}
			else
			{
				$next_hier_level = 0;
			}

			$sql = "UPDATE " . CATEGORIES_TABLE . "
				SET cat_title = '" . htmlspecialchars(strip_tags(str_replace("\'", "''", $HTTP_POST_VARS['cat_title']))) . "', parent_forum_id = $parent_forum_id, cat_hier_level = $next_hier_level, cat_sponsor_img = '" . str_replace("\'", "''", $HTTP_POST_VARS['cat_sponsor_img']) . "', cat_sponsor_alt = '" . str_replace("\'", "''", $HTTP_POST_VARS['cat_sponsor_alt']) . "', cat_sponsor_url = '" . str_replace("\'", "''", $HTTP_POST_VARS['cat_sponsor_url']) . "', cat_icon = '" . str_replace("\'", "''", $HTTP_POST_VARS['cat_icon']) . "'
				WHERE cat_id = " . intval($HTTP_POST_VARS[POST_CAT_URL]);
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't update forum information", "", __LINE__, __FILE__, $sql);
			}

			rebuild_forum_issubs();
			rebuild_hierarchie_tables();
			sync('all forums', 0);

			$message = $lang['Forums_updated'] . "<br /><br />" . sprintf($lang['Click_return_forumadmin'], "<a href=\"" . append_sid("admin_forums.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

			message_die(GENERAL_MESSAGE, $message);

			break;
			
		case 'deleteforum':
			// Show form to delete a forum
			$forum_id = intval($HTTP_GET_VARS[POST_FORUM_URL]);

			$foruminfo = get_info('forum', $forum_id);
			
			$select_to_posts = '<select name="to_id_posts">';
			$select_to_posts .= "<option value=\"-1\"$s>" . $lang['Delete_all_posts'] . "</option>\n";
			$select_to_posts .= get_list('forum', $forum_id, 0);
			$select_to_posts .= '</select>';
			
			if( $foruminfo['forum_issub'] == FORUM_ISSUB )
			{
				$select_to_cats = '<select name="to_id_cats">';
				$exclude = get_list_inferior('forum', $foruminfo['cat_id']);
				$select_to_cats .= get_list('cattitle_forum', $forum_id, 0, $exclude);
				$select_to_cats .= '</select>';
			}
			else
			{
				$select_to_cats = $lang['Forum_isnosub'];
			}

			$buttonvalue = $lang['Move_and_Delete'];

			$newmode = 'movedelforum';

			$name = $foruminfo['forum_name'];

			$template->set_filenames(array(
				"body" => "admin/forum_delete_body.tpl")
			);

			$s_hidden_fields = '<input type="hidden" name="mode" value="' . $newmode . '" /><input type="hidden" name="from_id" value="' . $forum_id . '" />';

			$template->assign_vars(array(
				'NAME' => $name, 

				'L_FORUM_DELETE' => $lang['Forum_delete'], 
				'L_FORUM_DELETE_EXPLAIN' => $lang['Forum_delete_explain'], 
				'L_MOVE_CONTENTS' => $lang['Move_contents'], 
				'L_MOVE_CONTENTS_POSTS' => $lang['Move_contents_posts'], 
				'L_MOVE_CONTENTS_CATS' => $lang['Move_contents_cats'], 
				'L_FORUM_NAME' => $lang['Forum_name'], 

				"S_HIDDEN_FIELDS" => $s_hidden_fields,
				'S_FORUM_ACTION' => append_sid("admin_forums.$phpEx"), 
				'S_SELECT_TO' => $select_to,
				'S_SELECT_TO_POSTS' => $select_to_posts,
				'S_SELECT_TO_CATS' => $select_to_cats,
				'S_SUBMIT_VALUE' => $buttonvalue)
			);

			$template->pparse("body");
			break;

		case 'movedelforum':
			//
			// Move or delete a forum in the DB
			//
			$from_id = intval($HTTP_POST_VARS['from_id']);
			$to_id_posts = intval($HTTP_POST_VARS['to_id_posts']);
			$to_id_cats = intval($HTTP_POST_VARS['to_id_cats']);

			// Either delete or move all posts in a forum
			if($to_id_posts == -1)
			{
				// Delete polls in this forum
				$sql = "SELECT v.vote_id 
					FROM " . VOTE_DESC_TABLE . " v, " . TOPICS_TABLE . " t 
					WHERE t.forum_id = $from_id 
						AND v.topic_id = t.topic_id";
				if (!($result = $db->sql_query($sql)))
				{
					message_die(GENERAL_ERROR, "Couldn't obtain list of vote ids", "", __LINE__, __FILE__, $sql);
				}

				if ($row = $db->sql_fetchrow($result))
				{
					$vote_ids = '';
					do
					{
						$vote_ids .= (($vote_ids != '') ? ', ' : '') . $row['vote_id'];
					}
					while ($row = $db->sql_fetchrow($result));

					$sql = "DELETE FROM " . VOTE_DESC_TABLE . " 
						WHERE vote_id IN ($vote_ids)";
					$db->sql_query($sql);

					$sql = "DELETE FROM " . VOTE_RESULTS_TABLE . " 
						WHERE vote_id IN ($vote_ids)";
					$db->sql_query($sql);

					$sql = "DELETE FROM " . VOTE_USERS_TABLE . " 
						WHERE vote_id IN ($vote_ids)";
					$db->sql_query($sql);
				}
				$db->sql_freeresult($result);
				
				include($phpbb_root_path . "includes/prune.$phpEx");
				prune($from_id, 0, true); // Delete everything from forum
			}
			else
			{
				$sql = "SELECT *
					FROM " . FORUMS_TABLE . "
					WHERE forum_id IN ($from_id, $to_id_posts)";
				if( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't verify existence of forums", "", __LINE__, __FILE__, $sql);
				}

				if($db->sql_numrows($result) != 2)
				{
					message_die(GENERAL_ERROR, "Ambiguous forum ID's", "", __LINE__, __FILE__);
				}
				
				$sql = "UPDATE " . TOPICS_TABLE . "
					SET forum_id = $to_id_posts
					WHERE forum_id = $from_id";
				if( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't move topics to other forum", "", __LINE__, __FILE__, $sql);
				}
				
				$sql = "UPDATE " . POSTS_TABLE . "
					SET	forum_id = $to_id_posts
					WHERE forum_id = $from_id";
				if( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't move posts to other forum", "", __LINE__, __FILE__, $sql);
				}
				
				// Now transfer event types
				$sql = "SELECT max(t.event_type_id) AS max_id 
					FROM " . MYCALENDAR_EVENT_TYPES_TABLE . " t 
					WHERE forum_id = $to_id_posts";
				if( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't obtain event information of other forum!", "", __LINE__,__FILE__, $sql);
				}
				$row = $db->sql_fetchrow($result);
				
				$max_id = $row['max_id'];
				
				$sql = "SELECT min(t.event_type_id) AS min_id 
					FROM " . MYCALENDAR_EVENT_TYPES_TABLE . " t 
					WHERE forum_id = $from_id";
				if( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't obtain event information of source forum!", "", __LINE__,__FILE__, $sql);
				}
				$row = $db->sql_fetchrow($result);
				
				$min_id = $row['min_id'];
				$offset = $max_id - $min_id + 1;
				
				$sql = "UPDATE " . MYCALENDAR_EVENT_TYPES_TABLE . " 
					SET event_type_id = event_type_id + $offset, forum_id = $to_id_posts 
					WHERE forum_id = $from_id";
				if( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't update event types in source forum!", "", __LINE__,__FILE__, $sql);
				}
				
				$sql = "UPDATE " . MYCALENDAR_TABLE . "
					SET event_type_id = event_type_id + $offset, forum_id = $to_id_posts 
					WHERE forum_id = $from_id";
				if( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't move events to other forum!", "", __LINE__,__FILE__, $sql);
				}
			}
			
			// Move Subcategories
			if( !empty($to_id_cats) )
			{
				$sql = "SELECT *
					FROM " . FORUMS_TABLE . "
					WHERE forum_id IN ($from_id, $to_id_cats)";
				if( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't verify existence of forums", "", __LINE__, __FILE__, $sql);
				}
				if($db->sql_numrows($result) != 2)
				{
					message_die(GENERAL_ERROR, "Ambiguous forum ID's", "", __LINE__, __FILE__);
				}

				if( $to_id_cats > 0 )
				{
					$sql = "SELECT forum_hier_level
						FROM " . FORUMS_TABLE ."
						WHERE forum_id = $to_id_cats";
					if( !$result = $db->sql_query($sql) )
					{
						message_die(GENERAL_ERROR, "Couldn't get hierarchie level from forums table", "", __LINE__, __FILE__, $sql);
					}
					$row = $db->sql_fetchrow($result);
				
					$parent_hierarchie = $row['forum_hier_level'];
					$next_hier_level = $parent_hierarchie;
				}
				else
				{
					$next_hier_level = 0;
				}

				$sql = "UPDATE " . CATEGORIES_TABLE . "
					SET parent_forum_id = $to_id_cats, cat_hier_level = $next_hier_level
					WHERE parent_forum_id = $from_id";
				if( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't move categories to other forum", "", __LINE__, __FILE__, $sql);
				}
			}

			// Alter Mod level if appropriate - 2.0.4
			$sql = "SELECT ug.user_id 
				FROM " . AUTH_ACCESS_TABLE . " a, " . USER_GROUP_TABLE . " ug 
				WHERE a.forum_id <> $from_id 
					AND a.auth_mod = 1
					AND ug.group_id = a.group_id";
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't obtain moderator list", "", __LINE__, __FILE__, $sql);
			}

			if ($row = $db->sql_fetchrow($result))
			{
				$user_ids = '';
				do
				{
					$user_ids .= (($user_ids != '') ? ', ' : '' ) . $row['user_id'];
				}
				while ($row = $db->sql_fetchrow($result));

				$sql = "SELECT ug.user_id 
					FROM " . AUTH_ACCESS_TABLE . " a, " . USER_GROUP_TABLE . " ug 
					WHERE a.forum_id = $from_id 
						AND a.auth_mod = 1 
						AND ug.group_id = a.group_id
						AND ug.user_id NOT IN ($user_ids)";
				if( !$result2 = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't obtain moderator list", "", __LINE__, __FILE__, $sql);
				}
					
				if ($row = $db->sql_fetchrow($result2))
				{
					$user_ids = '';
					do
					{
						$user_ids .= (($user_ids != '') ? ', ' : '' ) . $row['user_id'];
					}
					while ($row = $db->sql_fetchrow($result2));

					$sql = "UPDATE " . USERS_TABLE . " 
						SET user_level = " . USER . " 
						WHERE user_id IN ($user_ids) 
							AND user_level <> " . ADMIN;
					$db->sql_query($sql);
				}
				$db->sql_freeresult($result);

			}
			$db->sql_freeresult($result2);

			$sql = "DELETE FROM " . FORUMS_TABLE . "
				WHERE forum_id = $from_id";
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't delete forum", "", __LINE__, __FILE__, $sql);
			}
			
			$sql = "DELETE FROM " . AUTH_ACCESS_TABLE . "
				WHERE forum_id = $from_id";
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't delete forum", "", __LINE__, __FILE__, $sql);
			}
			
			$sql = "DELETE FROM " . PRUNE_TABLE . "
				WHERE forum_id = $from_id";
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't delete forum prune information", "", __LINE__, __FILE__, $sql);
			}

			$sql = "DELETE FROM " . MOVE_TABLE . "
				WHERE forum_id = $from_id";
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't delete forum move information", "", __LINE__, __FILE__, $sql);
			}

			$sql = "DELETE FROM " . MYCALENDAR_TABLE . "
				WHERE forum_id = $from_id";
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't delete calendar events", "", __LINE__, __FILE__, $sql);
	            }
	        
			$sql = "DELETE FROM " . MYCALENDAR_EVENT_TYPES_TABLE . "
				WHERE forum_id = $from_id";
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't delete event categories", "", __LINE__, __FILE__, $sql);
	        }

			$sql = "DELETE FROM " . FORUMS_WATCH_TABLE . "
				WHERE forum_id = $from_id";
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't delete user forum subscriptions", "", __LINE__, __FILE__, $sql);
	            }

			$sql = "DELETE FROM " . DIGEST_FORUMS_TABLE . "
				WHERE forum_id = $from_id";
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't delete digest forum data", "", __LINE__, __FILE__, $sql);
			}

			rebuild_forum_issubs();
			rebuild_hierarchie_tables();
			sync('all forums', 0);

			$message = $lang['Forums_updated'] . "<br /><br />" . sprintf($lang['Click_return_forumadmin'], "<a href=\"" . append_sid("admin_forums.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

			message_die(GENERAL_MESSAGE, $message);

			break;
			
		case 'deletecat':
			//
			// Show form to delete a category
			//
			$cat_id = intval($HTTP_GET_VARS[POST_CAT_URL]);

			$buttonvalue = $lang['Move_and_Delete'];
			$newmode = 'movedelcat';
			$catinfo = get_info('category', $cat_id);
			$name = $catinfo['cat_title'];
			$caticon = $catinfo['cat_icon'];

			if ($catinfo['number'] == 1)
			{
				$sql = "SELECT count(*) as total
					FROM ". FORUMS_TABLE;
				if( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't get Forum count", "", __LINE__, __FILE__, $sql);
				}
				$count = $db->sql_fetchrow($result);
				$count = $count['total'];

				if ($count > 0)
				{
					message_die(GENERAL_ERROR, $lang['Must_delete_forums']);
				}
				else
				{
					$select_to = $lang['Nowhere_to_move'];
				}
			}
			else
			{
				$select_to = '<select name="to_id">';
				$exclude = get_list_inferior('category', $cat_id);
				$select_to .= get_list('category', $cat_id, 0, $exclude);
				$select_to .= '</select>';
			}

			$template->set_filenames(array(
				"body" => "admin/cat_delete_body.tpl")
			);

			$s_hidden_fields = '<input type="hidden" name="mode" value="' . $newmode . '" /><input type="hidden" name="from_id" value="' . $cat_id . '" />';

			$template->assign_vars(array(
				'NAME' => $name, 

				'L_CAT_DELETE' => $lang['Cat_delete'], 
				'L_CAT_DELETE_EXPLAIN' => $lang['Cat_delete_explain'], 
				'L_MOVE_CONTENTS' => $lang['Move_contents'], 
				'L_CAT_NAME' => $lang['Cat_name'], 
		
				'S_HIDDEN_FIELDS' => $s_hidden_fields,
				'S_CAT_ACTION' => append_sid("admin_forums.$phpEx"), 
				'S_SELECT_TO' => $select_to,
				'S_SUBMIT_VALUE' => $buttonvalue)
			);

			$template->pparse("body");
			break;

		case 'movedelcat':
			//
			// Move or delete a category in the DB
			//
			$from_id = intval($HTTP_POST_VARS['from_id']);
			$to_id = intval($HTTP_POST_VARS['to_id']);

			if (!empty($to_id))
			{
				$sql = "SELECT *
					FROM " . CATEGORIES_TABLE . "
					WHERE cat_id IN ($from_id, $to_id)";
				if( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't verify existence of categories", "", __LINE__, __FILE__, $sql);
				}
				if($db->sql_numrows($result) != 2)
				{
					message_die(GENERAL_ERROR, "Ambiguous category ID's", "", __LINE__, __FILE__);
				}

				if( $to_id > 0 )
				{
					$sql = "SELECT forum_hier_level
						FROM " . FORUMS_TABLE ."
						WHERE forum_id = $to_id";
					if( !$result = $db->sql_query($sql) )
					{
						message_die(GENERAL_ERROR, "Couldn't get hierarchie level from forums table", "", __LINE__, __FILE__, $sql);
					}
					$row = $db->sql_fetchrow($result);
	
					$parent_hierarchie = $row['cat_hier_level'];
					$next_hier_level = $parent_hierarchie + 1;
				}
				else
				{
					$next_hier_level = 0;
				}

				$sql = "UPDATE " . FORUMS_TABLE . "
					SET cat_id = $to_id, forum_hier_level = $next_hier_level
					WHERE cat_id = $from_id";
				if( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't move forums to other category", "", __LINE__, __FILE__, $sql);
				}
			}

			$sql = "DELETE FROM " . CATEGORIES_TABLE ."
				WHERE cat_id = $from_id";
				
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't delete category", "", __LINE__, __FILE__, $sql);
			}

			rebuild_forum_issubs();
			rebuild_hierarchie_tables();
			sync('all forums', 0);
	
			$message = $lang['Categories_updated'] . "<br /><br />" . sprintf($lang['Click_return_forumadmin'], "<a href=\"" . append_sid("admin_forums.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

			message_die(GENERAL_MESSAGE, $message);

			break;

		case 'forum_order':
			//
			// Change order of forums in the DB
			//
			$move = intval($HTTP_GET_VARS['move']);
			$forum_id = intval($HTTP_GET_VARS[POST_FORUM_URL]);

			$forum_info = get_info('forum', $forum_id);

			$cat_id = $forum_info['forum_id'];
			
			if( $move <= 0 )
			{
				$wherefield = "WHERE forum_order < " . $forum_info['forum_order'] . "
					AND forum_hier_level = " . $forum_info['forum_hier_level'] . "
					ORDER BY forum_order DESC LIMIT 1";
			}
			else
			{
				$wherefield = "WHERE forum_order > " . $forum_info['forum_order'] . "
					AND forum_hier_level = " . $forum_info['forum_hier_level'] . "
					ORDER BY forum_order ASC LIMIT 1";
			}

			$sql = "SELECT *
				FROM " . FORUMS_TABLE . " ";
			$sql .= $wherefield;
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't get forums order", "", __LINE__, __FILE__, $sql);
			}
			if( !$row = $db->sql_fetchrow($result) )
			{
				$moveval = 0;
			}
			else
			{
				if( $move <= 0 )
				{
					$moveval = intval($row['forum_order']) - 5;
				}
				else
				{
					$moveval = intval($row['forum_order']) + 5;
				}
			}

			$sql = "UPDATE " . FORUMS_TABLE . "
				SET forum_order = $moveval
				WHERE forum_id = $forum_id";
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't change category order", "", __LINE__, __FILE__, $sql);
			}

			renumber_order('forum', $forum_info['cat_id']);
			$show_index = TRUE;

			break;
			
		case 'cat_order':
			//
			// Change order of categories in the DB
			//
			$move = intval($HTTP_GET_VARS['move']);
			$cat_id = intval($HTTP_GET_VARS[POST_CAT_URL]);
			$cat_info = get_info('category', $cat_id);
			
			if( $move <= 0 )
			{
				$wherefield = "WHERE cat_order < " . $cat_info['cat_order'] . "
					AND cat_hier_level = " . $cat_info['cat_hier_level'] . "
					ORDER BY cat_order DESC LIMIT 1";
			}
			else
			{
				$wherefield = "WHERE cat_order > " . $cat_info['cat_order'] . "
					AND cat_hier_level = " . $cat_info['cat_hier_level'] . "
					ORDER BY cat_order ASC LIMIT 1";
			}
		
			$sql = "SELECT *
				FROM " . CATEGORIES_TABLE . " ";
			$sql .= $wherefield;
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't get category order", "", __LINE__, __FILE__, $sql);
			}
			if( !$row = $db->sql_fetchrow($result) )
			{
				$moveval = 0;
			}
			else
			{
				if( $move <= 0 )
				{
					$moveval = intval($row['cat_order']) - 5;
				}
				else
				{
					$moveval = intval($row['cat_order']) + 5;
				}
			}

			$sql = "UPDATE " . CATEGORIES_TABLE . "
				SET cat_order = $moveval
				WHERE cat_id = $cat_id";
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't change category order", "", __LINE__, __FILE__, $sql);
			}

			renumber_order('category');
			$show_index = TRUE;

			break;

		case 'deleteposts':
			// Show form to delete posts and topics
			//
			$forum_id = intval($HTTP_GET_VARS[POST_FORUM_URL]);

			$newmode = 'movedelposts';
			$foruminfo = get_info('forum', $forum_id);
			$catinfo = get_info('category', $foruminfo['cat_id']);
			$name = $catinfo['cat_title'] . ", " . $foruminfo['forum_name'];
			
			$s_hidden_fields = '<input type="hidden" name="mode" value="' . $newmode . '" /><input type="hidden" name="' . POST_FORUM_URL . '" value=' . $forum_id . '" />';

			$template->set_filenames(array(
				"body" => "admin/forum_posts_delete_body.tpl")
			);

			$template->assign_vars(array(
				'NAME' => $name, 

				'L_POSTS_DELETE' => $lang['Posts_delete'], 
				'L_POSTS_DELETE_EXPLAIN' => $lang['Posts_delete_explain'], 
				'L_FORUM_NAME' => $lang['Forum_name'], 
				
				'S_HIDDEN_FIELDS' => $s_hidden_fields,
				'S_POSTS_ACTION' => append_sid("admin_forums.$phpEx"), 
				'S_SUBMIT_VALUE' => $lang['Delete'])
			);
			
			$template->pparse("body");
			
			break;
			
		case 'moveposts':
			// Show form to move posts and topics
			//
			$forum_id = intval($HTTP_GET_VARS[POST_FORUM_URL]);

			$newmode = 'movedelposts';
			$foruminfo = get_info('forum', $forum_id);
			$catinfo = get_info('category', $foruminfo['cat_id']);
			$name = $catinfo['cat_title'] . ", " . $foruminfo['forum_name'];
			
			$select_to = '<select name="to_id">';
			$select_to .= get_list('cattitle_forum', $forum_id, 1);
			$select_to .= '</select>';

			$s_hidden_fields = '<input type="hidden" name="mode" value="' . $newmode . '" /><input type="hidden" name="' . POST_FORUM_URL . '" value=' . $forum_id . '" />';

			$template->set_filenames(array(
				"body" => "admin/forum_posts_move_body.tpl")
			);

			$template->assign_vars(array(
				'NAME' => $name, 

				'L_POSTS_MOVE' => $lang['Posts_move'], 
				'L_POSTS_MOVE_EXPLAIN' => $lang['Posts_move_explain'], 
				'L_FORUM_NAME' => $lang['Forum_name'], 
				'L_MOVE_POSTS_TO' => $lang['Move_contents_posts'],
				
				'S_SELECT_TO' => $select_to,
				'S_HIDDEN_FIELDS' => $s_hidden_fields,
				'S_POSTS_ACTION' => append_sid("admin_forums.$phpEx"), 
				'S_SUBMIT_VALUE' => $lang['Move'])
			);
			
			$template->pparse("body");
			
			break;
			
		case 'movedelposts':
			$forum_id = intval($HTTP_POST_VARS[POST_FORUM_URL]);
			$to_id = intval($HTTP_POST_VARS['to_id']);

			if( empty($to_id) )
			{
				include($phpbb_root_path . "includes/prune.$phpEx");
				prune($forum_id, 0); // Delete everything from forum
			}
			else
			{
				$sql = "SELECT *
					FROM " . FORUMS_TABLE . "
					WHERE forum_id IN ($forum_id, $to_id)";
				if( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't verify existence of forums", "", __LINE__, __FILE__, $sql);
				}
				if($db->sql_numrows($result) != 2)
				{
					message_die(GENERAL_ERROR, "Ambiguous forum ID's", "", __LINE__, __FILE__);
				}
				$sql = "UPDATE " . TOPICS_TABLE . "
					SET forum_id = $to_id
					WHERE forum_id = $forum_id";
				if( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't move topics to other forum", "", __LINE__, __FILE__, $sql);
				}
				$sql = "UPDATE " . POSTS_TABLE . "
					SET	forum_id = $to_id
					WHERE forum_id = $forum_id";
				if( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't move posts to other forum", "", __LINE__, __FILE__, $sql);
				}
				sync('all forums', 0);
			}

			$message = $lang['Forums_updated'] . "<br /><br />" . sprintf($lang['Click_return_forumadmin'], "<a href=\"" . append_sid("admin_forums.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

			message_die(GENERAL_MESSAGE, $message);
			
			break;

		case 'forum_sync':
			sync('forum', intval($HTTP_GET_VARS[POST_FORUM_URL]));
			rebuild_forum_issubs();
			rebuild_hierarchie_tables();
			$show_index = TRUE;

			break;

		default:
			message_die(GENERAL_MESSAGE, $lang['No_mode']);
			break;
	}

	if ($show_index != TRUE)
	{
		include('../admin/page_footer_admin.'.$phpEx);
		exit;
	}
}

//
// Start page proper
//
$template->set_filenames(array(
	"body" => "admin/forum_admin_body.tpl")
);

$template->assign_vars(array(
	'S_FORUM_ACTION' => append_sid("admin_forums.$phpEx"),
	
	'L_FORUM_TITLE' => $lang['Forum_admin'], 
	'L_FORUM_EXPLAIN' => $lang['Forum_admin_explain'], 
	'L_CREATE_FORUM' => $lang['Create_forum'], 
	'L_CREATE_CATEGORY' => $lang['Create_category'], 
	'L_INDEX' => $lang['Forum_Index'],
	'L_MOVE_UP' => '<img src="' . $phpbb_root_path . $images['acp_up'] . '" alt="' . $lang['Move_up'] . '" title="' . $lang['Move_up'] . '" />',
	'L_MOVE_DOWN' => '<img src="' . $phpbb_root_path . $images['acp_down'] . '" alt="' . $lang['Move_down'] . '" title="' . $lang['Move_down'] . '" />',
	'L_EDIT' => '<img src="' . $phpbb_root_path . $images['acp_edit'] . '" alt="' . $lang['Edit'] . '" title="' . $lang['Edit'] . '" />',
	'L_DELETE' => '<img src="' . $phpbb_root_path . $images['acp_delete'] . '" alt="' . $lang['Delete'] . '" title="' . $lang['Delete'] . '" />',
	'L_RESYNC' => '<img src="' . $phpbb_root_path . $images['acp_sync'] . '" alt="' . $lang['Resync'] . '" title="' . $lang['Resync'] . '" />',
	'L_PERMS' => '<img src="' . $phpbb_root_path . $images['acp_perm'] . '" alt="' . $lang['Permissions'] . '" title="' . $lang['Permissions'] . '" />')
);

$sql = "SELECT cat_id, cat_title, cat_order, cat_hier_level, parent_forum_id, cat_icon
	FROM " . CATEGORIES_TABLE . "
	ORDER BY cat_order";
if( !$q_categories = $db->sql_query($sql) )
{
	message_die(GENERAL_ERROR, "Could not query categories list", "", __LINE__, __FILE__, $sql);
}

if( $total_categories = $db->sql_numrows($q_categories) )
{
	$category_rows = $db->sql_fetchrowset($q_categories);

	$sql = "SELECT *
		FROM " . FORUMS_TABLE . "
		ORDER BY cat_id, forum_order";
	if(!$q_forums = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Could not query forums information", "", __LINE__, __FILE__, $sql);
	}

	if( $total_forums = $db->sql_numrows($q_forums) )
	{
		$forum_rows = $db->sql_fetchrowset($q_forums);
	}

	//
	// Get the hierarchie if necessary
	//
	if($hierarchie_level > 0 && $total_categories > 0 && $parent_forum != 0)
	{
		$sql = "SELECT concat(c.cat_title, ', ', f.forum_name) AS hierarchie_title
			FROM " . CATEGORIES_TABLE . " c, " . FORUMS_TABLE . " f
			WHERE f.forum_id = $parent_forum
			AND c.cat_id = f.cat_id";
		if( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not query hierarchie title', '', __LINE__, __FILE__, $sql);
		}

		if( $row = $db->sql_fetchrow($result) )
		{
			$template->assign_block_vars('switch_last_hierarchielevel', array());

			$template->assign_vars(array(
				'U_DEEPESTNAVINDEX' => append_sid("admin_forums.$phpEx?" . POST_HIERARCHIE_URL . "=" . $hierarchie_level . "&" . POST_PARENTFORUM_URL . "=" . $parent_forum),
				'L_DEEPESTNAVINDEX' => $row['hierarchie_title'])
			);
		}

		$row = get_info('forum', $parent_forum);
		$cat_id = $row['cat_id'];

		$sql = "SELECT concat(c.cat_title, ', ', f.forum_name) AS hierarchie_title, f.forum_id, f.forum_hier_level + 1 AS hierarchie_level
			FROM " . CATEGORIES_TABLE . " c, " . CAT_REL_CAT_PARENTS_TABLE . " ccp, " . FORUMS_TABLE . " f, " . CAT_REL_FORUM_PARENTS_TABLE . " cfp
			WHERE ccp.parent_cat_id = c.cat_id
			AND ccp.cat_id = $cat_id
			AND cfp.parent_forum_id = f.forum_id
			AND cfp.cat_id = $cat_id
			AND f.cat_id = c.cat_id
			ORDER BY c.cat_hier_level, f.forum_hier_level";
		if( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not query hierarchie title', '', __LINE__, __FILE__, $sql);
		}

		while( $row = $db->sql_fetchrow($result) )
		{
			$template->assign_block_vars("navrow", array(
				'U_SUBINDEX' => append_sid("admin_forums.$phpEx?" . POST_HIERARCHIE_URL . "=" . $row['hierarchie_level'] . "&" . POST_PARENTFORUM_URL . "=" . $row['forum_id']),
				'L_SUBINDEX' => $row['hierarchie_title'])
			);
		}
		$db->sql_freeresult($result);
	}

	//
	// Okay, let's build the index
	//
		typeout_hierarchie_recursive($category_rows, $forum_rows, $total_categories, $total_forums, $hierarchie_level, $parent_forum);
	
	// Now we fill the 'hidden posts' table
	$template->assign_vars(array(
		'L_HIDDENPOSTS' => $lang['Title_hidden_posts'],
		'L_MOVE_POSTS' => '<img src="' . $phpbb_root_path . $images['acp_move'] . '" alt="' . $lang['Move'] . '" title="' . $lang['Move'] . '" />',
		'L_NOHIDDENPOSTS' => $lang['no_hidden_posts'])
	);

	typeout_hidden_posts($hierarchie_level, $parent_forum);

}// if ... total_categories

$template->pparse("body");

include('../admin/page_footer_admin.'.$phpEx);

?>
