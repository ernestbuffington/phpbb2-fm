<?php
/** 
*
* @package includes
* @version $Id: functions.php,v 1.133.2.34 2005/02/21 18:37:33 acydburn Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

function get_db_stat($mode)
{
	global $db;

	switch ( $mode )
	{
		case 'usercount':
			$sql = "SELECT COUNT(user_id) AS total
				FROM " . USERS_TABLE . "
				WHERE user_id <> " . ANONYMOUS;
			break;

		case 'newestuser':
			$sql = "SELECT user_id, username, user_level, user_active, user_regdate
				FROM " . USERS_TABLE . "
				WHERE user_id <> " . ANONYMOUS . "
				ORDER BY user_id DESC
				LIMIT 1";
			break;

		case 'gender_male': 
			$sql = "SELECT COUNT(user_id) AS total
				FROM " . USERS_TABLE . " 
				WHERE user_id <> " . ANONYMOUS . "
					AND user_gender = 1"; 
	            break; 
	            
		case 'gender_female': 
			$sql = "SELECT COUNT(user_id) AS total
				FROM " . USERS_TABLE . " 
				WHERE user_id <> " . ANONYMOUS . "
					AND user_gender = 2"; 
			break; 

		case 'postcount':
		case 'topiccount':
			$sql = "SELECT SUM(forum_topics) AS topic_total, SUM(forum_posts) AS post_total
				FROM " . FORUMS_TABLE;
			break;
			
		case 'pvtcount':
			$sql = "SELECT COUNT(privmsgs_id) AS total
				FROM " . PRIVMSGS_TABLE;
			break;
			
		case 'disablecount':
			$sql = "SELECT COUNT(ban_id) AS total
				FROM " . BANLIST_TABLE;
			break;
			
		case 'groupcount':
			$sql = "SELECT COUNT(group_id) AS total
				FROM " . GROUPS_TABLE . "
				WHERE group_single_user <> 1";
			break;

		case 'paymentgroupcount':
			$sql = "SELECT COUNT(group_id) AS total
				FROM " . GROUPS_TABLE . "
				WHERE group_single_user <> 1 
					AND group_type = 3";
			break;

		case 'opengroupcount':
			$sql = "SELECT COUNT(group_id) AS total
				FROM " . GROUPS_TABLE . "
				WHERE group_single_user <> 1 
					AND group_type = 0";
			break;

		case 'closedgroupcount':
			$sql = "SELECT COUNT(group_id) AS total
				FROM " . GROUPS_TABLE . "
				WHERE group_single_user <> 1 
					AND group_type = 1";
			break;

		case 'hiddengroupcount':
			$sql = "SELECT COUNT(group_id) AS total
				FROM " . GROUPS_TABLE . "
				WHERE group_single_user <> 1 
					AND group_type = 2";
			break;

		case 'newestgroup':
			$sql = "SELECT group_id, group_name
				FROM " . GROUPS_TABLE . "
				WHERE group_single_user <> 1 
						AND group_type <> 2
				ORDER BY group_id DESC
				LIMIT 1";
			break;

	}

	if ( !($result = $db->sql_query($sql)) )
	{
		return false;
	}

	$row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);

	switch ( $mode )
	{
		case 'usercount':
			return $row['total'];
			break;
		case 'newestuser':
			return $row;
			break;
       	case 'gender_male': 
	   		return $row['total']; 
	        break; 
		case 'gender_female': 
	    	return $row['total']; 
	    	break; 
		case 'postcount':
			return $row['post_total'];
			break;
		case 'topiccount':
			return $row['topic_total'];
			break;
		case 'pvtcount':
 			return $row['total'];
			break;
		case 'disablecount':
 			return $row['total'];
			break;
		case 'groupcount':
			return $row['total'];
			break;
		case 'paymentgroupcount':
			return $row['total'];
			break;
		case 'opengroupcount':
			return $row['total'];
			break;
		case 'closedgroupcount':
			return $row['total'];
			break;
		case 'hiddengroupcount':
			return $row['total'];
			break;
		case 'newestgroup':
			return $row;
			break;
	}

	return false;
}


//
// Play random sound on new private message
//
function getRandomSound($type = 'random')
{ 
	global $errors, $seed, $phpbb_root_path; 
	
	$localdir = $phpbb_root_path . 'mods/audio/';
	
	if (is_dir($localdir))
	{ 
    	$fd = opendir($localdir); 
    	$sounds = array(); 
    	while (($part = @readdir($fd)) == true)
    	{ 
			// If you want to use other formats, simply add them to (wav|mid|mp3|au) below  
     		if (eregi("(wav|mid|mp3|au)$", $part))
     		{ 
        		$sounds[] = $part; 
			} 
    	} 
    	if ($type == 'all') return $sounds; 
    	if ($seed !== true)
    	{ 
    		mt_srand ((double) microtime() * 1000000); 
     		$seed = true; 
    	} 
    	$key = mt_rand (0, sizeof($sounds)-1); 
    	return $localdir . $sounds[$key]; 
    } 
    else 
    { 
       $errors[] = $localdir . ' does not exist!'; 
       return false; 
    } 
} 

// added at phpBB 2.0.11 to properly format the username
function phpbb_clean_username($username)
{
	$username = substr(htmlspecialchars(str_replace("\'", "'", trim($username))), 0, 99);
   	$username = phpbb_rtrim($username, "\\");   
   	$username = str_replace("'", "\'", $username);

	return $username;
}


/**
* This function is a wrapper for ltrim, as charlist is only supported in php >= 4.1.0
* Added in phpBB 2.0.18
*/
function phpbb_ltrim($str, $charlist = false)
{
	if ($charlist === false)
	{
		return ltrim($str);
	}
	
	$php_version = explode('.', PHP_VERSION);

	// php version < 4.1.0
	if ((int) $php_version[0] < 4 || ((int) $php_version[0] == 4 && (int) $php_version[1] < 1))
	{
		while ($str{0} == $charlist)
		{
			$str = substr($str, 1);
		}
	}
	else
	{
		$str = ltrim($str, $charlist);
	}

	return $str;
}


/**
* Our own generator of random values
* This uses a constantly changing value as the base for generating the values
* The board wide setting is updated once per page if this code is called
* With thanks to Anthrax101 for the inspiration on this one
* Added in phpBB 2.0.20
*/
function dss_rand()
{
	global $db, $board_config, $dss_seeded;

	$val = $board_config['rand_seed'] . microtime();
	$val = md5($val);
	$board_config['rand_seed'] = md5($board_config['rand_seed'] . $val . 'a');
   
	if($dss_seeded !== true)
	{
		$sql = "UPDATE " . CONFIG_TABLE . " 
			SET config_value = '" . $board_config['rand_seed'] . "'
			WHERE config_name = 'rand_seed'";
		if( !$db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, "Unable to reseed PRNG", "", __LINE__, __FILE__, $sql);
		}

		// Remove cache file
		@unlink($phpbb_root_path . 'cache/config_board.'.$phpEx);

		$dss_seeded = true;
	}

	return substr($val, 4, 16);
}


// added at phpBB 2.0.12 to fix a bug in PHP 4.3.10 (only supporting charlist in php >= 4.1.0)
function phpbb_rtrim($str, $charlist = false)
{
   	if ($charlist === false)
   	{
		return rtrim($str);
	}
   
   	$php_version = explode('.', PHP_VERSION);

   	// php version < 4.1.0
   	if ((int) $php_version[0] < 4 || ((int) $php_version[0] == 4 && (int) $php_version[1] < 1))
   	{
   		while ($str{strlen($str)-1} == $charlist)
      	{
      		$str = substr($str, 0, strlen($str)-1);
      	}
   	}
   	else
   	{
    	$str = rtrim($str, $charlist);
   	}

   	return $str;
}


//
// Get Userdata, $user can be username or user_id. If force_str is true, the username will be forced.
//
function get_userdata($user)
{
	global $db;

	if (!is_numeric($user) || $force_str)
	{
		$user = phpbb_clean_username($user);
	}
	else
	{
		$user = intval($user);
	}

	$sql = "SELECT *
		FROM " . USERS_TABLE . " 
		WHERE ";
	$sql .= ( ( is_integer($user) ) ? "user_id = $user" : "username = '" .  str_replace("\'", "''", $user) . "'" ) . " AND user_id <> " . ANONYMOUS;
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Tried obtaining data for a non-existent user', '', __LINE__, __FILE__, $sql);
	}

	return ( $row = $db->sql_fetchrow($result) ) ? $row : false;
}

//
// Sets a specific XData point for a user to a value.  $user and $which_xdata can be either strings or IDs.
// Use the $force_something parameters to ensure that a numeric string for $user or $which_xdata will be treated
// as such.
//
function set_user_xdata($user, $which_xdata, $value, $force_user_string = false, $force_xd_string = false)
{
	global $db;

//	$value = trim(htmlspecialchars($value));
	$value = str_replace("\\'", "'", $value);
	$value = str_replace("'", "\\'", $value);

	$user_is_name = ((intval($user) == 0) || $force_user_string);
	$xd_is_name = ((intval($which_xdata) == 0) || $force_xd_string);

	if ($user_is_name)
	{
		$user = phpbb_clean_username($user);
	}

	$user_where = ($user_is_name) ? ('u.username = \'' . $user . '\'') : ('u.user_id = ' . $user );
	$field_where = ($xd_is_name) ? ('xf.code_name = \'' . $which_xdata . '\'') : ('xf.field_id = ' . $which_xdata);

	$sql = "SELECT u.user_id, xf.field_id 
		FROM " . USERS_TABLE . " u, " . XDATA_FIELDS_TABLE . " xf
		WHERE " . $user_where . " 
			AND " . $field_where . "
		LIMIT 1";
	if ( !($result = $db->sql_query($sql)) )
	{
    	message_die(GENERAL_ERROR, 'DB error while finding  a user\'s XData field to edit it', '', __LINE__, __FILE__, $sql);
 	}

 	$row = $db->sql_fetchrow($result);

    $sql = "DELETE FROM " . XDATA_DATA_TABLE . "
    	WHERE user_id = " . $row['user_id'] . " 
    		AND field_id = " . $row['field_id'] . "
    	LIMIT 1";
	if ( !($db->sql_query($sql)) )
	{
       	message_die(GENERAL_ERROR, 'Could not remove user XData', '', __LINE__, __FILE__, $sql);
	}

	if ($value !== '')
	{
    	$sql = "INSERT INTO " . XDATA_DATA_TABLE . " (user_id, field_id, xdata_value)
			VALUES (" . $row['user_id'] . ", " . $row['field_id'] . ", '" . $value . "')";
		if ( !($db->sql_query($sql)) )
		{
       		message_die(GENERAL_ERROR, 'Could not insert user XData', '', __LINE__, __FILE__, $sql);
		}
	}
}

//
// Like get_userdata(), but gives the extra data from the MOD.
//
function get_user_xdata($user, $force_str = false)
{
 	global $db;

	if (!is_numeric($user) || $force_str)
	{
		$user = phpbb_clean_username($user);

		$sql = "SELECT xf.code_name, xd.xdata_value 
			FROM " . XDATA_DATA_TABLE . " xd, " . USERS_TABLE . " u, " . XDATA_FIELDS_TABLE . " xf
 			WHERE xf.field_id = xd.field_id 
 				AND xd.user_id = u.user_id 
 				AND u.username = '" .  str_replace("\'", "''", $user) . "'";
	}
	else
	{
		$user = intval($user);

		$sql = "SELECT xf.code_name, xd.xdata_value 
			FROM " . XDATA_DATA_TABLE . " xd, " . XDATA_FIELDS_TABLE . " xf
			WHERE xf.field_id = xd.field_id 
				AND xd.user_id = " . $user;
	}
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Tried obtaining XData for a non-existent user', '', __LINE__, __FILE__, $sql);
	}

	$data = array();
	while ( $row = $db->sql_fetchrow($result) )
	{
    	$data[$row['code_name']] = $row['xdata_value'];
	}
	$db->sql_freeresult($result);

	return $data;
}

//
// Returns data about the existing extra fields as an array keyed by the code_name
// and sorted in the admin-specified order.
//
function get_xd_metadata($force_refresh = false)
{
	global $db;
	static $meta = false;

	if ( !is_array($meta) || $force_refresh )
	{
		$sql = "SELECT field_id, field_name, field_desc, field_type, field_order, code_name, field_length, field_values, field_regexp, default_auth, display_viewprofile, display_register, display_posting, handle_input, allow_bbcode, allow_smilies, allow_html
			FROM " . XDATA_FIELDS_TABLE . "
			ORDER BY field_order ASC";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Failed to get metadata on extra user fields', '', __LINE__, __FILE__, $sql);
		}

		$data = array();

		while ( $row = $db->sql_fetchrow($result) )
		{
			$data[$row['code_name']] = $row;

			if ($row['field_values'] != '')
			{
				$data[$row['code_name']]['values_array'] = array('toast');
				$values = array();
				preg_match_all("/(?<!\\\)'(.*?)(?<!\\\)'/", $row['field_values'], $values);
				$data[$row['code_name']]['values_array'] = array_map(create_function('$a', "return str_replace(\"\\\\'\", \"'\", \$a);"), $values[1]);
			}
			else
			{
				$data[$row['code_name']]['values_array'] = array();
			}
		}
		$db->sql_freeresult($result);

		$meta = $data;
	}

	return $meta;
}

function xdata_auth($fields, $userid, $meta = false)
{
	global $db;

	if ($field_id == false)
	{
		$field_sql = 1;
	}
	elseif (is_array($fields))
	{
		$field_sql = 'xf.code_name IN(' . implode(', ', $fields) . ')';
	}
	else
	{
		$fields_sql = "xf.code_name = '$fields'";
	}

	if ($meta == false)
	{
		$sql = "SELECT xf.default_auth AS default_auth, xf.code_name AS code_name 
			FROM " . XDATA_FIELDS_TABLE . " xf
			WHERE $field_sql";
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Couldn\'t get metadata on fields for auth check.', '', __LINE__, __FILE__, $sql);
		}

		$meta = array();
		while ($data = $db->sql_fetchrow($result))
		{
			$meta[$data['code_name']]['default_auth'] = $data['default_auth'];
		}
		$db->sql_freeresult($result);
	}

	$sql = "SELECT xf.code_name, xa.auth_value, g.group_single_user
		FROM " . XDATA_FIELDS_TABLE . " xf, " . XDATA_AUTH_TABLE . " xa, " . USER_GROUP_TABLE . " ug, " . GROUPS_TABLE . " g
		WHERE xf.field_id = xa.field_id
			AND xa.group_id = ug.group_id
			AND xa.group_id = g.group_id
			AND ug.user_id = $userid
			AND $field_sql
		ORDER BY g.group_single_user ASC";
   	if (!($result = $db->sql_query($sql)))
   	{
		message_die(GENERAL_ERROR, 'Could not get xdata auth information.', '', __LINE__, __FILE__, $sql);
   	}

   	$auth = array();
   	foreach($meta as $key => $value)
   	{
		$auth[$key] = $value['default_auth'];
	}

   	while($data = $db->sql_fetchrow($result))
   	{
		$auth[$data['code_name']] = ( $data['auth_value'] == XD_AUTH_ALLOW);
   	}
   	$db->sql_freeresult($result);

   	if (!is_array($fields))
   	{
		return $auth[$fields];
   	}
   
   	return $auth;
}

// Jumpbox
function make_jumpbox($action, $match_forum_id = 0)
{
	global $userdata, $lang, $db, $nav_links, $template, $SID;

//	$is_auth = auth(AUTH_VIEW, AUTH_LIST_ALL, $userdata);

	$sql = "SELECT c.cat_id, c.cat_title, c.cat_order, c.parent_forum_id, c.cat_hier_level
		FROM " . CATEGORIES_TABLE . " c, " . FORUMS_TABLE . " f
		WHERE f.cat_id = c.cat_id
		GROUP BY c.cat_id, c.cat_title, c.cat_order
		ORDER BY c.cat_order";
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

		$boxstring = "\n\n" . '<select name="' . POST_FORUM_URL . '" onChange="if(this.options[this.selectedIndex].value != -1) {window.location.href = this.options[this.selectedIndex].value;}"><option value="-1">' . $lang['Select_forum'] . '</option>';

		$forum_rows = array();
		while ( $row = $db->sql_fetchrow($result) )
		{
			$forum_rows[] = $row;
		}
		$db->sql_freeresult($result);

		$is_auth_ary = array();
		$is_auth_ary = auth(AUTH_VIEW, AUTH_LIST_ALL, $userdata, $forum_rows);

		if ( $total_forums = sizeof($forum_rows) )
		{
				$boxstring .= recursive_jumpbox($category_rows, $forum_rows, $total_categories, $total_forums, $match_forum_id, 0, 0);
		}

		$boxstring .= '</select>';
	}
	else
	{
		$boxstring = "\n\n" . '<br><select name="' . POST_FORUM_URL . '" onChange="if(this.options[this.selectedIndex].value != -1) {window.location.href = this.options[this.selectedIndex].value;}"><option value="-1"></option>';
	}

	// Let the jumpbox work again in sites having additional session id checks. 
// 	if ( !empty($SID) )
//	{
		$boxstring .= '<input type="hidden" name="sid" value="' . $userdata['session_id'] . '" />';
//	}

	$template->set_filenames(array(
		'jumpbox' => 'jumpbox.tpl')
	);
	
	$template->assign_vars(array(
		'L_GO' => $lang['Go'],
		'L_JUMP_TO' => $lang['Jump_to'],
		'L_SELECT_FORUM' => $lang['Select_forum'],

		'S_JUMPBOX_SELECT' => $boxstring,
		'S_JUMPBOX_ACTION' => append_sid($action))
	);
	
	$template->assign_var_from_handle('JUMPBOX', 'jumpbox');

	return;
}

//
// Going recursively through the hierarchie and creating a list 
function recursive_jumpbox(&$category_rows, &$forum_rows, &$total_categories, &$total_forums, &$match_forum_id, $current_hierarchie, $parent_forum_id)
{
	global $phpEx;

	for($i = 0; $i < $total_categories; $i++)
	{
    	if ( $category_rows[$i]['cat_hier_level'] != $current_hierarchie || $category_rows[$i]['parent_forum_id'] != $parent_forum_id )
		continue;
		
		$boxstring_forums = '';

		$class = 'h' . ($current_hierarchie%3);

		for($j = 0; $j < $total_forums; $j++)
		{
			if ( $forum_rows[$j]['cat_id'] == $category_rows[$i]['cat_id'] && $forum_rows[$j]['auth_view'] <= AUTH_REG && $forum_rows[$j]['forum_hier_level'] == $current_hierarchie )
			{
				$selected = ( $forum_rows[$j]['forum_id'] == $match_forum_id ) ? ' selected="selected"' : '';

				if ( $forum_rows[$j]['forum_issub'] == FORUM_ISNOSUB)
				{
					$boxstring_forums .= '<option value="' . append_sid("viewforum.$phpEx?" . POST_FORUM_URL . '=' . $forum_rows[$j]['forum_id']) . '" class="' . $class . '"' . $selected . '>' . space($forum_rows[$j]['forum_hier_level']*2, '&nbsp;') . "- " . $forum_rows[$j]['forum_name'] . '</option>' . "\n";
				}
				else
				{
					$boxstring_forums .='<option value="' . append_sid("index.$phpEx?" . POST_HIERARCHIE_URL . "=" . ($forum_rows[$j]['forum_hier_level']+1) . "&" . POST_PARENTFORUM_URL . "=" . $forum_rows[$j]['forum_id']) . '" class="' . $class . 'sf" '. $selected . '>' .	space($forum_rows[$j]['forum_hier_level']*2, '&nbsp;') . "- " . $forum_rows[$j]['forum_name'] .	'</option>' . "\n";
					$boxstring_forums .= recursive_jumpbox($category_rows, $forum_rows, $total_categories, $total_forums, $match_forum_id, $current_hierarchie+1, $forum_rows[$j]['forum_id']);
					$boxstring_forums .= '<option value="-1" class="' . $class . 'c">&nbsp;</option>' . "\n";
				}
			}
		}

		if ( $boxstring_forums != '' )
		{
			$boxstring .= '<option value="-1" class="' . $class . 'c">&nbsp;</option>' . "\n";
			$boxstring .= '<option value="-1" class="' . $class . 'c">' . space($category_rows[$i]['cat_hier_level']*2, '&nbsp;') . $category_rows[$i]['cat_title']  . '</option>' . "\n";
			$boxstring .= '<option value="-1" class="' . $class . 'c">' . space($category_rows[$i]['cat_hier_level']*2, '&nbsp;') . space(strlen($category_rows[$i]['cat_title'])*1.2, '-') . '</option>' . "\n";
			//$boxstring .= '<option value="-1">' . space($category_rows[$i]['cat_hier_level']*2, '&nbsp;') . '----------------</option>' . "\n";
			$boxstring .= $boxstring_forums;
		}
	}
	return $boxstring;
}


//
// returns a string of $number length containing only $string
function space($number = 1, $string = " ")
{
  	$spaces = '';
  	for($i=0; $i < $number; $i++)
	{
		$spaces .= $string;
	}
 	
 	return $spaces;
}

// 
// Include language files
// 
function language_include($category)
{
	global $phpbb_root_path, $board_config, $lang, $faq;

	$dirname = $phpbb_root_path . 'language/lang_' . $board_config['default_lang'];

	$dir = opendir($dirname);

	while($file = readdir($dir))
	{
		if( ereg("^lang_" . $category, $file) && is_file($dirname . "/" . $file) && !is_link($dirname . "/" . $file) )
		{
			$incname = str_replace("lang_" . $category, "", $file);
			include($dirname . '/lang_' . $category . $incname);
		}
	}

	closedir($dir);
}

//
// Initialise user settings on page load
function init_userprefs($userdata, $mode = '')
{
	global $db, $HTTP_GET_VARS, $HTTP_POST_VARS, $HTTP_COOKIE_VARS;
	global $board_config, $theme, $images;
	global $lang, $phpEx, $phpbb_root_path;
	global $nav_links;

	if ($mode != 'digest')
	{
		global $template;
	}

	if ( $userdata['user_id'] != ANONYMOUS )
	{
		if ( !empty($userdata['user_lang']))
		{
			$default_lang = phpbb_ltrim(basename(phpbb_rtrim($userdata['user_lang'])), "'");
		}

		if ( !empty($userdata['user_dateformat']) )
		{
			$board_config['default_dateformat'] = $userdata['user_dateformat'];
		}

		if ( !empty($userdata['user_clockformat']) )
		{
			$board_config['default_clock'] = $userdata['user_clockformat'];
		}

		if ( isset($userdata['user_timezone']) )
		{
			$board_config['board_timezone'] = $userdata['user_timezone'];
		}
	}
	else
	{
		$default_lang = phpbb_ltrim(basename(phpbb_rtrim($board_config['default_lang'])), "'");
	}

	if ( !file_exists(@phpbb_realpath($phpbb_root_path . 'language/lang_' . $default_lang . '/lang_main.'.$phpEx)) )
	{
		if ( $userdata['user_id'] != ANONYMOUS )
		{
			// For logged in users, try the board default language next
			$default_lang = phpbb_ltrim(basename(phpbb_rtrim($board_config['default_lang'])), "'");
		}
		else
		{
			// For guests it means the default language is not present, try english
			// This is a long shot since it means serious errors in the setup to reach here,
			// but english is part of a new install so it's worth us trying
			$default_lang = 'english';
		}

		if ( !file_exists(@phpbb_realpath($phpbb_root_path . 'language/lang_' . $default_lang . '/lang_main.'.$phpEx)) )
		{
			message_die(GENERAL_ERROR, 'Could not locate valid language pack');
		}
	}

	// If we've had to change the value in any way then let's write it back to the database
	// before we go any further since it means there is something wrong with it
	if ( $userdata['user_id'] != ANONYMOUS && $userdata['user_lang'] !== $default_lang )
	{
		$sql = 'UPDATE ' . USERS_TABLE . "
			SET user_lang = '" . $default_lang . "'
			WHERE user_lang = '" . $userdata['user_lang'] . "'";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not update user language info');
		}

		$userdata['user_lang'] = $default_lang;
	}
	elseif ( $userdata['user_id'] == ANONYMOUS && $board_config['default_lang'] !== $default_lang )
	{
		$sql = 'UPDATE ' . CONFIG_TABLE . "
			SET config_value = '" . $default_lang . "'
			WHERE config_name = 'default_lang'";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not update user language info');
		}
		
		// Remove cache file
		@unlink($phpbb_root_path . 'cache/config_board.'.$phpEx);
	}
	
	$board_config['default_lang'] = $default_lang;

	language_include('main');
	language_include('quick_title');

	if ( defined('IN_ADMIN') )
	{
		if( !file_exists(@phpbb_realpath($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_admin.'.$phpEx)) )
		{
			$board_config['default_lang'] = 'english';
		}

		language_include('admin');
	}

	include_attach_lang();
	
	board_disable();

	if ( defined('IN_ADMIN') )
	{
		if( !file_exists(@phpbb_realpath($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_admin_page_permissions.'.$phpEx)) )
		{
			$board_config['default_lang'] = 'english';
		}

		include($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_admin_page_permissions.' . $phpEx);
	}
	// Next, the main permissions module
	include_once($phpbb_root_path . 'includes/page_permissions.php');

	//
	// Set up style
	//
	if ( !$board_config['override_user_style'] )
	{
		if ( isset($HTTP_GET_VARS[STYLE_URL]) )
		{
			$style = urldecode($HTTP_GET_VARS[STYLE_URL]);
			if ( $theme = setup_style($style) )
			{
				if ( $userdata['user_id'] != ANONYMOUS )
				{
					// user logged in --> save new style ID in user profile
					$sql = "UPDATE " . USERS_TABLE . " 
						SET user_style = " . $theme['themes_id'] . "
						WHERE user_id = " . $userdata['user_id'];
					if ( !$db->sql_query($sql) )
					{
						message_die(GENERAL_ERROR, 'Error updating user style', '', __LINE__, __FILE__, $sql);
					}

					$userdata['user_style'] = $theme['themes_id'];
				} 
				else 
				{
					// user not logged in --> save new style ID in cookie
					setcookie($board_config['cookie_name'] . '_style', $style, time() + 31536000, $board_config['cookie_path'], $board_config['cookie_domain'], $board_config['cookie_secure']);
				}
				return;
			}
		}


		if ( $userdata['user_id'] == ANONYMOUS && isset($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_style']) )
		{
			$style = $HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_style'];
			if ( $theme = setup_style($style) )
			{
				return;
			}
		}
		if ( $userdata['user_id'] != ANONYMOUS && $userdata['user_style'] > 0 )
		{
			if ( $theme = setup_style($userdata['user_style'], $mode) )
			{
				return;
			}
		}
	}

	if (IS_ROBOT)
	{
		$sql = "SELECT bot_style 
			FROM " . BOTS_TABLE . " 
			WHERE bot_name = '" . IS_ROBOT . "'";
		$result = $db->sql_query($sql);
		
		$row = $db->sql_fetchrow($result);
		$theme = setup_style($row['bot_style']);
		$db->sql_freeresult($result);
	} 
	else 
	{
		$theme = setup_style($board_config['default_style'], $mode);
	}

	//
	// Mozilla navigation bar
	// Default items that should be valid on all pages.
	// Defined here to correctly assign the Language Variables
	// and be able to change the variables within code.
	//
	$nav_links['top'] = array ( 
		'url' => append_sid($phpbb_root_path . 'index.' . $phpEx),
		'title' => $lang['Forum_Index']
	);
	$nav_links['search'] = array ( 
		'url' => append_sid($phpbb_root_path . 'search.' . $phpEx),
		'title' => $lang['Search']
	);
	$nav_links['help'] = array ( 
		'url' => append_sid($phpbb_root_path . 'faq.' . $phpEx),
		'title' => $lang['FAQ']
	);
	$nav_links['author'] = array ( 
		'url' => append_sid($phpbb_root_path . 'memberlist.' . $phpEx),
		'title' => $lang['Memberlist']
	);

	return;
}

function setup_style($style, $mode = '')
{
	global $db, $board_config, $images, $phpbb_root_path, $template_name, $userdata;

	if ($mode != 'digest')
	{
		global $template;
	}
	
	while ( $theme_ok == FALSE )
	{
		if ( !is_numeric($style) )
		{
			$sql = "SELECT *
				FROM " . THEMES_TABLE . "
				WHERE style_name = '$style'";
		}
		else
		{
			$sql = "SELECT *
				FROM " . THEMES_TABLE . "
				WHERE themes_id = " . (int) $style;
		}
		if ( !($result = $db->sql_query($sql)) )
		{	
			message_die(GENERAL_ERROR, 'Could not query database for theme info', '', __LINE__, __FILE__, $sql);
		}
	
		if ( !($row = $db->sql_fetchrow($result)) )
		{
			// We are trying to setup a style which does not exist in the database
			// Try to fallback to the board default (if the user had a custom style)
			// and then any users using this style to the default if it succeeds
			if ( $style != $board_config['default_style'])
			{
				$sql = "SELECT *
					FROM " . THEMES_TABLE . "
					WHERE themes_id = " . (int) $board_config['default_style'];
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'Could not query database for theme info', '', __LINE__, __FILE__, $sql);
				}
	
				if ( $row = $db->sql_fetchrow($result) )
				{
					$db->sql_freeresult($result);
	
					$sql = "UPDATE " . USERS_TABLE . "
						SET user_style = " . (int) $board_config['default_style'] . "
						WHERE user_style = '" . $style . "'";
					if ( !($result = $db->sql_query($sql)) )
					{
						message_die(GENERAL_ERROR, 'Could not update user theme info', '', __LINE__, __FILE__, $sql);
					}
				}
				else
				{
					message_die(GENERAL_ERROR, 'Could not open ' . $row['style_name'] . ' template config file');
				}
			}
			else
			{
				message_die(GENERAL_ERROR, 'Could not open ' . $row['style_name'] . ' template config file');
			}
		}
		
		if ( $row['theme_public'] == TRUE || $userdata['user_level'] == ADMIN || $style == $board_config['default_style'] )
		{
			$theme_ok = TRUE;
		} 
		else 
		{
			$style = $board_config['default_style'];
		}
	}

	$template_path = 'templates';
	$template_name = '/' . $row['template_name'] ;

	$template = new Template($phpbb_root_path . $template_path . $template_name);

	if ( defined('IN_ADMIN') )
	{
		$template->set_rootdir($phpbb_root_path . $template_path);
	}

	if ( $template )
	{
		$current_template_path = $template_path . $template_name;
		$current_image_cfg = ( $row['image_cfg'] ) ? '/images/' . $row['image_cfg'] : '/images';
		@include($phpbb_root_path . $current_template_path . '/' . $template_name . '.cfg');
					
		if ( !defined('TEMPLATE_CONFIG') )
		{
			message_die(GENERAL_ERROR, 'Could not get theme data for ' . (intval($style) == 0 ? 'style_name' : 'themes_id') . ': <b>' . $style . '</b>');	
		}

		$img_lang = ( file_exists(@phpbb_realpath($phpbb_root_path . $current_template_path . $current_image_cfg . '/lang_' . $board_config['default_lang'])) ) ? $board_config['default_lang'] : 'english';
		
		while( list($key, $value) = @each($images) )
		{
			if ( !is_array($value) )
			{
				$images[$key] = str_replace('{LANG}', 'lang_' . $img_lang, $value);
			}
		}
	}

	return $row;
}

function setup_forum_style($forum_id)
{
	global $db, $board_config;
	
	$sql = "SELECT forum_template 
		FROM " . FORUMS_TABLE . " 
		WHERE forum_id = " . $forum_id;
	if ( !($result = $db->sql_query($sql)) )
	{	
		message_die(GENERAL_ERROR, 'Could not query database for forum theme info', '', __LINE__, __FILE__, $sql);
	}
	$forum_data = $db->sql_fetchrow($result);	
	$db->sql_freeresult($result);
	
	$style_id = (int) $forum_data['forum_template'];
	
	// Check if this style exists in database
	if ($style_id)
	{	
		$sql = "SELECT * 
			FROM " . THEMES_TABLE . "
			WHERE themes_id = " . $style_id;
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not query database for theme info', '', __LINE__, __FILE__, $sql);
		}
		
		// We are trying to use a style which does not exist in the database
		// Reset the forum_template
		if ( !($row = $db->sql_fetchrow($result)) )
		{
			$sql = "UPDATE " . FORUMS_TABLE . "
				SET forum_template = 0
				WHERE forum_id = " . $forum_id;
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not update forum theme info', '', __LINE__, __FILE__, $sql);
			}
			
			$style_id = $board_config['default_style'];
		}
		else
		{
			$board_config['default_style'] = $style_id;
			$board_config['override_user_style'] = true;
		}
	}	
	
	return;
}

function encode_ip($dotquad_ip)
{
	$ip_sep = explode('.', $dotquad_ip);
	return sprintf('%02x%02x%02x%02x', $ip_sep[0], $ip_sep[1], $ip_sep[2], $ip_sep[3]);
}

function decode_ip($int_ip)
{
	$hexipbang = explode('.', chunk_split($int_ip, 2, '.'));
	return hexdec($hexipbang[0]). '.' . hexdec($hexipbang[1]) . '.' . hexdec($hexipbang[2]) . '.' . hexdec($hexipbang[3]);
}

//
// Create date/time from format and timezone
//
function create_date($format, $gmepoch, $tz)
{
	global $board_config, $lang;
	static $translate;

	if ( empty($translate) && $board_config['default_lang'] != 'english' )
	{
		@reset($lang['datetime']);
		while ( list($match, $replace) = @each($lang['datetime']) )
		{
			$translate[$match] = $replace;
		}
	}

	return ( !empty($translate) ) ? strtr(@gmdate($format, $gmepoch + (3600 * $tz)), $translate) : @gmdate($format, $gmepoch + (3600 * $tz));
}

//
// Pagination routine, generates
// page number sequence
//
function get_page($num_items, $per_page, $start_item)
{
	$total_pages = ceil($num_items/$per_page);

	if ( $total_pages == 1 )
	{
		return '1';
		exit;
	}

	$on_page = floor($start_item / $per_page) + 1;
	$page_string = '';

	for($i = 0; $i < $total_pages + 1; $i++)
	{
		if( $i == $on_page ) 
		{
			$page_string = $i;
		}
	}
	return $page_string;
}
	
function generate_pagination($base_url, $num_items, $per_page, $start_item, $add_prevnext_text = TRUE)
{
	global $lang;

	$total_pages = ceil($num_items/$per_page);

	if ( $total_pages == 1 )
	{
		return '';
	}

	$on_page = floor($start_item / $per_page) + 1;

	$page_string = '';
	if ( $total_pages > 10 )
	{
		$init_page_max = ( $total_pages > 3 ) ? 3 : $total_pages;

		for($i = 1; $i < $init_page_max + 1; $i++)
		{
			$page_string .= ( $i == $on_page ) ? '<b>' . $i . '</b>' : '<a href="' . append_sid($base_url . "&amp;start=" . ( ( $i - 1 ) * $per_page ) ) . '">' . $i . '</a>';
			if ( $i <  $init_page_max )
			{
				$page_string .= ", ";
			}
		}

		if ( $total_pages > 3 )
		{
			if ( $on_page > 1  && $on_page < $total_pages )
			{
				$page_string .= ( $on_page > 5 ) ? ' ... ' : ', ';

				$init_page_min = ( $on_page > 4 ) ? $on_page : 5;
				$init_page_max = ( $on_page < $total_pages - 4 ) ? $on_page : $total_pages - 4;

				for($i = $init_page_min - 1; $i < $init_page_max + 2; $i++)
				{
					$page_string .= ($i == $on_page) ? '<b>' . $i . '</b>' : '<a href="' . append_sid($base_url . "&amp;start=" . ( ( $i - 1 ) * $per_page ) ) . '">' . $i . '</a>';
					if ( $i <  $init_page_max + 1 )
					{
						$page_string .= ', ';
					}
				}

				$page_string .= ( $on_page < $total_pages - 4 ) ? ' ... ' : ', ';
			}
			else
			{
				$page_string .= ' ... ';
			}

			for($i = $total_pages - 2; $i < $total_pages + 1; $i++)
			{
				$page_string .= ( $i == $on_page ) ? '<b>' . $i . '</b>'  : '<a href="' . append_sid($base_url . "&amp;start=" . ( ( $i - 1 ) * $per_page ) ) . '">' . $i . '</a>';
				if( $i <  $total_pages )
				{
					$page_string .= ", ";
				}
			}
		}
	}
	else
	{
		for($i = 1; $i < $total_pages + 1; $i++)
		{
			$page_string .= ( $i == $on_page ) ? '<b>' . $i . '</b>' : '<a href="' . append_sid($base_url . "&amp;start=" . ( ( $i - 1 ) * $per_page ) ) . '">' . $i . '</a>';
			if ( $i <  $total_pages )
			{
				$page_string .= ', ';
			}
		}
	}

	if ( $add_prevnext_text )
	{
		if ( $on_page > 1 )
		{
			$page_string = ' <a href="' . append_sid($base_url . "&amp;start=" . ( ( $on_page - 2 ) * $per_page ) ) . '">' . $lang['Previous'] . '</a>&nbsp;&nbsp;' . $page_string;
		}

		if ( $on_page < $total_pages )
		{
			$page_string .= '&nbsp;&nbsp;<a href="' . append_sid($base_url . "&amp;start=" . ( $on_page * $per_page ) ) . '">' . $lang['Next'] . '</a>';
		}

	}

	$page_string = $lang['Goto_page'] . ' ' . $page_string;

	return $page_string;
}

//
// This does exactly what preg_quote() does in PHP 4-ish
// If you just need the 1-parameter preg_quote call, then don't bother using this.
//
function phpbb_preg_quote($str, $delimiter)
{
	$text = preg_quote($str);
	$text = str_replace($delimiter, '\\' . $delimiter, $text);
	
	return $text;
}

//
// Obtain list of naughty words and build preg style replacement arrays for use by the
// calling script, note that the vars are passed as references this just makes it easier
// to return both sets of arrays
//
function obtain_word_list(&$orig_word, &$replacement_word)
{
	global $db;

	//
	// Define censored word matches
	//
	$sql = "SELECT word, replacement
		FROM  " . WORDS_TABLE;
	if( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not get censored words from database', '', __LINE__, __FILE__, $sql);
	}

	if ( $row = $db->sql_fetchrow($result) )
	{
		do 
		{
			$orig_word[] = '#\b(' . str_replace('\*', '\w*?', preg_quote($row['word'], '#')) . ')\b#i';
			$replacement_word[] = $row['replacement'];
		}
		while ( $row = $db->sql_fetchrow($result) );
	}

	return true;
}

//
// This is general replacement for die(), allows templated
// output in users (or default) language, etc.
//
// $msg_code can be one of these constants:
//
// GENERAL_MESSAGE : Use for any simple text message, eg. results 
// of an operation, authorisation failures, etc.
//
// GENERAL ERROR : Use for any error which occurs _AFTER_ the 
// common.php include and session code, ie. most errors in 
// pages/functions
//
// CRITICAL_MESSAGE : Used when basic config data is available but 
// a session may not exist, eg. banned users
//
// CRITICAL_ERROR : Used when config data cannot be obtained, eg
// no database connection. Should _not_ be used in 99.5% of cases
//
function message_die($msg_code, $msg_text = '', $msg_title = '', $err_line = '', $err_file = '', $sql = '')
{
	global $db, $template, $board_config, $theme, $lang, $phpEx, $phpbb_root_path, $nav_links, $gen_simple_header, $images;
	global $userdata, $user_ip, $session_length;
	global $starttime;
	global $HTTP_COOKIE_VARS;

	if (defined('HAS_DIED'))
	{
		die("<b>Website Error:</b><br />message_die() was called multiple times. This isn't supposed to happen.<br /><br />Please contact a site administrator to report this error.<hr />If this is happening after an update process, possible cause are incompatible files on the server, or the <u>install/</u> directory has not been deleted.<br /><br />Please check you have deleted the <u>install/</u> directory, the files on the server have been updated with the latest version, and files from previous versions do not exist.");
	}
	
	define('HAS_DIED', 1);
	
	$sql_store = $sql;
	
	//
	// Get SQL error if we are debugging. Do this as soon as possible to prevent 
	// subsequent queries from overwriting the status of sql_error()
	//
	if ( ($board_config['debug_value'] || DEBUG) && ( $msg_code == GENERAL_ERROR || $msg_code == CRITICAL_ERROR ) ) 
	{
		$sql_error = $db->sql_error();

		$debug_text = '';

		if ( $sql_error['message'] != '' )
		{
			$debug_text .= '<br /><br />SQL Error : ' . $sql_error['code'] . ' ' . $sql_error['message'];
		}

		if ( $sql_store != '' )
		{
			$debug_text .= "<br /><br />$sql_store";
		}

		if ( $err_line != '' && $err_file != '' )
		{
			$debug_text .= '<br /><br />Line : ' . $err_line . '<br />File : ' . basename($err_file);
		}
	}

	if( empty($userdata) && ( $msg_code == GENERAL_MESSAGE || $msg_code == GENERAL_ERROR ) )
	{
		$userdata = session_pagestart($user_ip, PAGE_INDEX);
		init_userprefs($userdata);
	}

	//
	// If the header hasn't been output then do it
	//
	if ( !defined('HEADER_INC') && $msg_code != CRITICAL_ERROR )
	{
		if ( empty($lang) )
		{
			if ( !empty($board_config['default_lang']) )
			{
				include($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_main.'.$phpEx);
			}
			else
			{
				include($phpbb_root_path . 'language/lang_english/lang_main.'.$phpEx);
			}
		}

		if ( empty($template) || empty($theme) )
		{
			$theme = setup_style($board_config['default_style']);
		}

		//
		// Load the Page Header
		//
		if ( !defined('IN_ADMIN') )
		{
			include($phpbb_root_path . 'includes/page_header.'.$phpEx);
		}
		else
		{
			include($phpbb_root_path . 'admin/page_header_admin.'.$phpEx);
		}
	}

	switch($msg_code)
	{
		case GENERAL_MESSAGE:
			if ( $msg_title == '' )
			{
				$msg_title = $lang['Information'];
			}
			break;

		case CRITICAL_MESSAGE:
			if ( $msg_title == '' )
			{
				$msg_title = $lang['Critical_Information'];
			}
			break;

		case GENERAL_ERROR:
			if ( $msg_text == '' )
			{
				$msg_text = $lang['An_error_occured'];
			}

			if ( $msg_title == '' )
			{
				$msg_title = $lang['General_Error'];
			}
			break;

		case CRITICAL_ERROR:
			//
			// Critical errors mean we cannot rely on _ANY_ DB information being
			// available so we're going to dump out a simple echo'd statement
			//
			include($phpbb_root_path . 'language/lang_english/lang_main.'.$phpEx);

			if ( $msg_text == '' )
			{
				$msg_text = $lang['A_critical_error'];
			}

			if ( $msg_title == '' )
			{
				$msg_title = '<b>' . $lang['Website'] . ' ' . $lang['Critical_Error'] . '</b>';
			}
			break;
	}

	//
	// Add on DEBUG info if we've enabled debug mode and this is an error. This
	// prevents debug info being output for general messages should DEBUG be
	// set TRUE by accident (preventing confusion for the end user!)
	//
	if ( ($board_config['debug_value'] || DEBUG) && ( $msg_code == GENERAL_ERROR || $msg_code == CRITICAL_ERROR ) ) 
	{
		if ( $debug_text != '' )
		{
			$msg_text = $msg_text . '<br /><br /><b><u>DEBUG MODE</u></b>' . $debug_text;
		}
	}

	if ( $msg_code != CRITICAL_ERROR )
	{
		if ( !empty($lang[$msg_text]) )
		{
			$msg_text = $lang[$msg_text];
		}

		if ( !defined('IN_ADMIN') )
		{
			$template->set_filenames(array(
				'message_body' => 'message_body.tpl')
			);
		}
		else
		{
			$template->set_filenames(array(
				'message_body' => 'admin/admin_message_body.tpl')
			);
		}

		$template->assign_vars(array(
			'MESSAGE_TITLE' => $msg_title,
			'MESSAGE_TEXT' => $msg_text)
		);
		
		if ( $gen_simple_header )
		{
			$template->assign_vars(array(
				'U_INDEX' => '', 
				'L_INDEX' => '')
			);
		}
		$template->pparse('message_body');

		// Adding e-mail here so we can be aware of errors.  Avoid using phpBB-specific
		// code (such as the built-in emailer class), in case that's part of the error.
		if ( $msg_code == GENERAL_ERROR && $board_config['debug_email'] ) 
		{
			if (isset($board_config['board_email'])) 
			{
				$email_from = 'From: ' . $board_config['board_email'] . "\n";
				$email_to = $board_config['board_email'];
				$email_username = ( isset($userdata['username']) ) ? $userdata['username'] : '';
				$email_username = ( $email_username == '' ) ? 'no defined username' : $email_username;
				$email_subject = $board_config['sitename'] . ': ' . $lang['An_error_occured'];
				$email_body = $msg_title . " " . $lang['Message'] . ":\n\n" . $msg_text . "\n\n\n" . $lang['Date'] . ": " . date('d-M-Y h:s', time()) . " GMT\n" . $lang['IP_Address'] . ": " . $_SERVER['REMOTE_ADDR'] . "\n" . $lang['Username'] . ": " . $email_username . "\n" . $lang['url'] . ": " . $board_config['server_name'] . $_SERVER['REQUEST_URI'] . "\n\nFORM:\n" . print_r($_POST, true);

				mail($email_to, $email_subject, $email_body, $email_from);
			}
		}

		if ( !defined('IN_ADMIN') )
		{
			include($phpbb_root_path . 'includes/page_tail.'.$phpEx);
		}
		else
		{
			include($phpbb_root_path . 'admin/page_footer_admin.'.$phpEx);
		}
	}
	else
	{
		// Adding e-mail here so we can be aware of errors.  Avoid using phpBB-specific
		// code (such as the built-in emailer class), in case that's part of the error.
		if ( $msg_code == GENERAL_ERROR && $board_config['debug_email'] ) 
		{
			if (isset($board_config['board_email'])) 
			{
				$email_from = 'From: ' . $board_config['board_email'] . "\n";
				$email_to = $board_config['board_email'];
				$email_username = ( isset($userdata['username']) ) ? $userdata['username'] : '';
				$email_username = ( $email_username == '' ) ? 'no defined username' : $email_username;
				$email_subject = $board_config['sitename'] . ': ' . $lang['An_error_occured'];
				$email_body = $msg_title . " " . $lang['Message'] . ":\n\n" . $msg_text . "\n\n\n" . $lang['Date'] . ": " . date('d-M-Y h:s', time()) . " GMT\n" . $lang['IP_Address'] . ": " . $_SERVER['REMOTE_ADDR'] . "\n" . $lang['Username'] . ": " . $email_username . "\n" . $lang['url'] . ": " . $board_config['server_name'] . $_SERVER['REQUEST_URI'] . "\n\nFORM:\n" . print_r($_POST, true);

				mail($email_to, $email_subject, $email_body, $email_from);
			}
		}

		echo "<html>\n<body>\n" . $msg_title . "\n<br /><br />\n" . $msg_text . "</body>\n</html>";
	}

	exit;
}

//
// This function is for compatibility with PHP 4.x's realpath()
// function.  In later versions of PHP, it needs to be called
// to do checks with some functions.  Older versions of PHP don't
// seem to need this, so we'll just return the original value.
// dougk_ff7 <October 5, 2002>
function phpbb_realpath($path)
{
	global $phpbb_root_path, $phpEx;

	return (!@function_exists('realpath') || !@realpath($phpbb_root_path . 'includes/functions.'.$phpEx)) ? $path : @realpath($path);
}

function redirect($url)
{
	global $db, $board_config;

	if (!empty($db))
	{
		$db->sql_close();
	}

	if (strstr(urldecode($url), "\n") || strstr(urldecode($url), "\r") || strstr(urldecode($url), ';url'))
	{
    	message_die(GENERAL_ERROR, 'Tried to redirect to potentially insecure url.');
   	} 
	
	$server_protocol = ($board_config['cookie_secure']) ? 'https://' : 'http://';
	$server_name = preg_replace('#^\/?(.*?)\/?$#', '\1', trim($board_config['server_name']));
	$server_port = ($board_config['server_port'] <> 80) ? ':' . trim($board_config['server_port']) : '';
	$script_name = preg_replace('#^\/?(.*?)\/?$#', '\1', trim($board_config['script_path']));
	$script_name = ($script_name == '') ? $script_name : '/' . $script_name;
	$url = preg_replace('#^\/?(.*?)\/?$#', '/\1', trim($url));

	// Redirect via an HTML form for PITA webservers
	if (@preg_match('/Microsoft|WebSTAR|Xitami/', getenv('SERVER_SOFTWARE')))
	{
		header('Refresh: 0; URL=' . $server_protocol . $server_name . $server_port . $script_name . $url);
		echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"><html><head><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"><meta http-equiv="refresh" content="0; url=' . $server_protocol . $server_name . $server_port . $script_name . $url . '"><title>Redirect</title></head><body><div align="center">If your browser does not support meta redirection please click <a href="' . $server_protocol . $server_name . $server_port . $script_name . $url . '">HERE</a> to be redirected</div></body></html>';
		exit;
	}

	// Behave as per HTTP/1.1 spec for others
	header('Location: ' . $server_protocol . $server_name . $server_port . $script_name . $url);
	exit;
}

function make_hours($base_time)
{
	global $lang;
	
	$years = floor($base_time / 31536000);
	$base_time = $base_time - ($years * 31536000);
	$weeks = floor($base_time / 604800);
	$base_time = $base_time - ($weeks * 604800);
	$days = floor($base_time / 86400);
	$base_time = $base_time - ($days * 86400);
	$hours = floor($base_time / 3600);
	$base_time = $base_time - ($hours * 3600);
	$min = floor($base_time / 60);
	$sek = $base_time - ($min * 60);
	if ($sek < 10) $sek ='0' . $sek;
	if ($min < 10) $min ='0' . $min;
	if ($hours < 10) $hours ='0' . $hours;
	$result = (( $years ) ? $years . ' ' . ( ( $years == 1 ) ? $lang['Year'] : $lang['Years']) . ', ' : '') . (( $years || $weeks ) ? $weeks . ' ' . (( $weeks == 1 ) ? $lang['Week'] : $lang['Weeks']).', ':'').(($years || $weeks || $days) ? $days . ' ' . (( $days == 1 ) ? $lang['Day'] : $lang['Days']).', ':'') . (( $hours ) ? $hours . ':' : '00:') . (( $min ) ? $min . ':' : '00:') . $sek;
	
	return ( $result ) ? $result : $lang['None'];
}

// Add function mkrealdate for Birthday
// the originate php "mktime()", does not work proberly on all OS, especially when going back in time 
// before year 1970 (year 0), this function "mkrealtime()", has a mutch larger valid date range, 
// from 1901 - 2099. it returns a "like" UNIX timestamp divided by 86400, so 
// calculation from the originate php date and mktime is easy. 
// mkrealdate, returns the number of day (with sign) from 1.1.1970. 
function mkrealdate($day, $month, $birth_year)
{
	// range check months
	if ($month < 1 || $month > 12) 
	{
		return "error";
	}
	
	// range check days
	switch ($month)
	{
		case 1: if ($day > 31) 
			return "error";
			break;
		case 2: if ($day > 29) 
			return "error";
			$epoch = $epoch + 31;
			break;
		case 3: if ($day > 31) 
			return "error";
			$epoch = $epoch + 59;
			break;
		case 4: if ($day > 30) 	
			return "error";
			$epoch = $epoch + 90;
			break;
		case 5: if ($day > 31) 
			return "error";
			$epoch = $epoch + 120;
			break;
		case 6: if ($day > 30) 
			return "error";
			$epoch = $epoch + 151;
			break;
		case 7: if ($day > 31) 
			return "error";
			$epoch = $epoch + 181;
			break;
		case 8: if ($day > 31) 
			return "error";
			$epoch = $epoch + 212;
			break;
		case 9: if ($day > 30) 
			return "error";
			$epoch = $epoch + 243;
			break;
		case 10: if ($day > 31) 
			return "error";
			$epoch = $epoch + 273;
			break;
		case 11: if ($day > 30) 
			return "error";
			$epoch=$epoch + 304; 
			break;
		case 12: if ($day > 31) 
			return "error";
			$epoch=$epoch + 334; 
			break;
	}
	
	$epoch = $epoch + $day;
	$epoch_Y = sqrt(($birth_year - 1970) * ($birth_year - 1970));
	$leapyear = round((($epoch_Y + 2) / 4) - .5);
	if (($epoch_Y + 2) % 4 == 0)
	{
		// curent year is leapyear
		$leapyear--;
		if ($birth_year > 1970 && $month >= 3) 
		{
			$epoch = $epoch + 1;
		}
		if ($birth_year < 1970 && $month < 3) 
		{
			$epoch = $epoch - 1;
		}
	} 
	else if ($month == 2 && $day > 28) 
	{
		return 'error'; //only 28 days in feb.
	}
	//year
	if ($birth_year > 1970)
	{
		$epoch = $epoch + $epoch_Y * 365 - 1 + $leapyear;
	}
	else
	{
		$epoch = $epoch - $epoch_Y * 365 - 1 - $leapyear;
	}
	
	return $epoch;
}

// Add function realdate for Birthday MOD
// the originate php "date()", does not work proberly on all OS, especially when going back in time
// before year 1970 (year 0), this function "realdate()", has a mutch larger valid date range,
// from 1901 - 2099. it returns a "like" UNIX date format (only date, related letters may be used, due to the fact that
// the given date value should already be divided by 86400 - leaving no time information left)
// a input like a UNIX timestamp divided by 86400 is expected, so
// calculation from the originate php date and mktime is easy.
// e.g. realdate ("m d Y", 3) returns the string "1 3 1970"

// UNIX users should replace this function with the below code, since this should be faster
//
//function realdate($date_syntax="Ymd",$date=0) 
//{ return create_date($date_syntax,$date*86400+1,0); }

function realdate($date_syntax = 'Ymd', $date = 0)
{
	global $lang;
	$i = 2;
	if ($date >= 0)
	{
	 	return create_date($date_syntax, $date * 86400 + 1, 0);
	} 
	else
	{
		$year= -(date % 1461);
		$days = $date + $year * 1461;
		while ($days < 0)
		{
			$year--;
			$days += 365;
			if ($i++ == 3)
			{
				$i=0;
				$days++;
			}
		}
	}
	$leap_year = ($i == 0) ? TRUE : FALSE;
	$months_array = ($i == 0) ? array (0, 31, 60, 91, 121, 152, 182, 213, 244, 274, 305, 335, 366) : array (0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334, 365);
	for ($month=1; $month < 12; $month++)
	{
		if ($days < $months_array[$month]) 
		{
			break;
		}
	}
	
	$day = $days - $months_array[$month-1] + 1;
	//you may gain speed performance by remove som of the below entry's if they are not needed/used
	return strtr ($date_syntax, array(
		'a' => '',
		'A' => '',
		'\\d' => 'd',
		'd' => ($day > 9) ? $day : '0' . $day,
		'\\D' => 'D',
		'D' => $lang['day_short'][($date-3)%7],
		'\\F' => 'F',
		'F' => $lang['month_long'][$month-1],
		'g' => '',
		'G' => '',
		'H' => '',
		'h' => '',
		'i' => '',
		'I' => '',
		'\\j' => 'j',
		'j' => $day,
		'\\l' => 'l',
		'l' => $lang['day_long'][($date-3)%7],
		'\\L' => 'L',
		'L' => $leap_year,
		'\\m' => 'm',
		'm' => ($month > 9) ? $month : '0' . $month,
		'\\M' => 'M',
		'M' => $lang['month_short'][$month-1],
		'\\n' => 'n',
		'n' => $month,
		'O' => '',
		's' => '',
		'S' => '',
		'\\t' => 't',
		't' => $months_array[$month] - $months_array[$month-1],
		'w' => '',
		'\\y' => 'y',
		'y' => ($year > 29) ? $year - 30 : $year + 70,
		'\\Y' => 'Y',
		'Y' => $year + 1970,
		'\\z' => 'z',
		'z' => $days,
		'\\W' => '',
		'W' => '') 
	);
}

function serverload() 
{	
	global $db;
	
	// Delete old page counts
	$sql = "DELETE FROM " . SERVERLOAD_TABLE . " 
		WHERE time < " . (time() - 300);
	if( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not delete Server Load entries', '', __LINE__, __FILE__, $sql);
	}
       
	// Insert the current page count
	$sql = "INSERT INTO " . SERVERLOAD_TABLE . " (time) 
		VALUES (" . time() . ")";
    if( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not update Server Load entries', '', __LINE__, __FILE__, $sql);
	}

	// Get page count (number of rows in the table)
	$sql = "SELECT time 
		FROM " . SERVERLOAD_TABLE;
    if( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain Server Load entries', '', __LINE__, __FILE__, $sql);
	}
    
    return $db->sql_numrows($result);
}

//
// Checks the unique hits table and adds a user if they haven't visited
// the forums during the past time interval, defined as $expires.
//
// Returns the number of unique hits during the time interval.
//
function uniquehits() 
{	
	global $db, $user_ip, $board_config;

	// Purge expired hits from the table
	$sql = "DELETE FROM " . UNIQUE_HITS_TABLE . " 
		WHERE time < " . ( time() - ($board_config['uniquehits_time'] * 60) );
	if( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not delete unique hits', '', __LINE__, __FILE__, $sql);
	}

	// First check to see if this $user_ip exists in the table
	$sql = "SELECT user_ip 
		FROM " . UNIQUE_HITS_TABLE . " 
		WHERE user_ip = '" . $user_ip . "'";	
    if( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not check unique hits', '', __LINE__, __FILE__, $sql);
	}

	// If the $user_ip doesn't exists then add it to the table as another unique hit
	if ( !($row = $db->sql_fetchrow($result)) )
	{
		// Insert the unique hit
		$sql = "INSERT INTO " . UNIQUE_HITS_TABLE . " (user_ip, time)
			VALUES ('$user_ip', " . time() . ")";
		if( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not update unique hits', '', __LINE__, __FILE__, $sql);
		}
	}

	// Get the number of rows in the table (unique hits)
	$sql = "SELECT user_ip 
		FROM " . UNIQUE_HITS_TABLE;
	if( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain unique hits', '', __LINE__, __FILE__, $sql);
	}
	
    return $db->sql_numrows($result);
}

function real_path($url)
{
	global $board_config;
	
    $server_protocol = ( $board_config['cookie_secure'] ) ? 'https://' : 'http://';
    $server_name = preg_replace('#^\/?(.*?)\/?$#', '\1', trim($board_config['server_name']));
    $server_port = ( $board_config['server_port'] <> 80 ) ? ':' . trim($board_config['server_port']) : '';
    $script_name = preg_replace('#^\/?(.*?)\/?$#', '\1', trim($board_config['script_path']));
    $script_name = ( $script_name == '' ) ? $script_name : '/' . $script_name;
    
    return $server_protocol . $server_name . $server_port . $script_name . '/' . $url;
}

function UpdateTrophyStats()
{
	global $db, $table_prefix;
		
	$q = "UPDATE " . USERS_TABLE . "
		SET user_trophies = 0
		WHERE user_trophies <> 0";	
	$r = $db->sql_query($q);
			
	$q = "SELECT player
		FROM " . $table_prefix . "ina_top_scores
		GROUP BY player";
	$r = $db->sql_query($q);
	while($row = $db->sql_fetchrow($r))
	{
		$who = $row['player'];
		
		$q1 = "SELECT COUNT(*) AS trophies
	    	FROM " . $table_prefix . "ina_top_scores
			WHERE player = '$who'
			GROUP BY player";
		$r1	= $db->sql_query($q1);
		$row = $db->sql_fetchrow($r1);
		$total_trophies	= $row['trophies'];	

		$q2 = "UPDATE " . USERS_TABLE . "
			SET user_trophies = '$total_trophies'
		   	WHERE user_id = '$who'";
		$r2 = $db->sql_query($q2);
	}
	
	return;
}

function CheckGamesDeletion()
{
	global $db, $table_prefix, $board_config, $phpbb_root_path;
	
	$q = "SELECT config_value
		FROM ". CONFIG_TABLE ."
		WHERE config_name = 'current_ina_date'";
	$r = $db->sql_query($q);
	$row = $db->sql_fetchrow($r);
	
	$next_deletion = $row['config_value'];
	$explode_it	= explode('-', $next_deletion);
	$d_month = $explode_it[1]; 
	$t_date	= date('Y-m-d');
	$x_date	= explode('-', $t_date);
	$c_month = $x_date[1];
	
	if($d_month == $c_month)
	{
	}
	else
	{
		if ($board_config['ina_delete'] == 1)
		{
			$q = "TRUNCATE " . iNA_SCORES;			
			$r = $db->sql_query($q);

			$q 	= "TRUNCATE ". $table_prefix ."ina_trophy_comments";			
			$r = $db->sql_query($q);

			$q = "UPDATE ". CONFIG_TABLE ."
		   		SET config_value = '$t_date'
		   		WHERE config_name = 'current_ina_date'";
			$r = $db->sql_query($q);
			
			// Remove cache file
			@unlink($phpbb_root_path . 'cache/config_board.'.$phpEx);
		}			
	}
	
	return;
}

function user_new_post_count($user_id, $user_lastvisit)
{ 
	global $db, $lang;
	
	$sql = "SELECT COUNT(post_id) AS total 
		FROM " . POSTS_TABLE . " 
		WHERE post_time >= $user_lastvisit 
			AND poster_id != $user_id"; 
	$result = $db->sql_query($sql); 

	if ( $result ) 
	{ 
		$row = $db->sql_fetchrow($result); 
		$lang['Search_new'] = $lang['Search_new'] . ' (' . $row['total'] . ')'; 
	} 
	
	return;
} 


function color_groups()
{
	global $db, $board_config, $userdata;
	
	$groupdata = array();
	if ( $board_config['color_groups'] = true )
	{
		$rows = $group_users = array();
		$staff_sql = ($userdata['user_level'] == USER) ? 'AND g.group_type <> ' . GROUP_HIDDEN : '';
		$sql = "SELECT g.group_name, g.group_colors, ug.group_id, u.username, u.user_id, u.group_priority
			FROM " . GROUPS_TABLE . " g, " . USER_GROUP_TABLE . " ug, " . USERS_TABLE . " u
			WHERE g.group_single_user != 1
				AND g.group_colored <> 0
				AND ug.user_pending = 0
				AND ug.group_id = g.group_id
				AND ug.user_id = u.user_id
				$staff_sql
			ORDER BY g.group_order, g.group_name";
		if ( ! $result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not get group data from database', '', __LINE__, __FILE__, $sql);
		}
		while ( $row = $db->sql_fetchrow($result) )
		{
			$row['group_colors'] = unserialize($row['group_colors']);
			$rows[] = $row;
		}
		$db->sql_freeresult($result);

		for($i = 0; $i < sizeof($rows); $i++)
		{
			$groupdata['userdata'][$rows[$i]['user_id']] = array(
				'user_id' => $rows[$i]['user_id'],
				'username' => $rows[$i]['username'],
				'group_priority' => $rows[$i]['group_priority'],
			);
			
			$group_users[$rows[$i]['group_id']][$rows[$i]['user_id']] = $rows[$i]['user_id'];
			
			$groupdata['groupdata'][$rows[$i]['group_id']] = array(
				'group_id' => $rows[$i]['group_id'],
				'group_color' => $rows[$i]['group_colors'],
				'group_name' => $rows[$i]['group_name'],
				'group_users' => $group_users[ $rows[$i]['group_id'] ],
			);
		}
	}
	
	return $groupdata;
}


//
// Formats the username with level color and group color if applicable
// Level color will always override group color, e.g. ADMIN, MOD color is preferred
//
function username_level_color($username, $user_level, $user_id = '')
{
	global $userdata, $color_groups, $board_config, $theme;

	if ( $user_level == ADMIN ) // Administrator
	{
		$bold = (!empty($theme['adminbold'])) ? 'b' : 'span';
		$username = '<' . $bold . ' style="color: #' . $theme['adminfontcolor'] . '">' . $username . '</' . $bold . '>';
		
		return $username; 
	}
	else if ( $user_level == LESS_ADMIN ) // Jnr. Administrator / Super Moderator
	{
		$bold = (!empty($theme['supermodbold'])) ? 'b' : 'span';
		$username = '<' . $bold . ' style="color: #' . $theme['supermodfontcolor'] . '">' . $username . '</' . $bold . '>';

		return $username; 
	}
	else if ( $user_level == MOD ) // Moderator
	{
		$bold = (!empty($theme['modbold'])) ? 'b' : 'span';
		$username = '<' . $bold . ' style="color: #' . $theme['modfontcolor'] . '">' . $username . '</' . $bold . '>';

		return $username; 
	}

	if ( !$color_groups )
	{
		return $username;
	}
	if ( !is_array($color_groups['groupdata']) )
	{
		return $username;
	}
	
	foreach ( $color_groups['groupdata'] AS $group_data )
	{
		if ( !$userdata['session_logged_in'] )
		{
			$group_color = $group_data['group_color'][$board_config['default_style']];
		}
		else
		{
			$group_color = $group_data['group_color'][$userdata['user_style']];
		}
		if ( !$group_color )
		{
			$match_found = false;
			foreach ( $group_data['group_color'] AS $color )
			{
				if ( !$match_found )
				{
					if ( $color )
					{
						$group_color = $color;
						$match_found = true;
					}
				}
			}
		}
		if ( $color_groups['userdata'][$user_id]['group_priority'] == $group_data['group_id'] || !$color_groups['userdata'][$user_id]['group_priority'] )
		{
			if ( in_array ( $user_id, $group_data['group_users'] ) )
			{
				$username = '<b style="color: #' . $group_color . '">' . $username . '</b>';

				return $username; 
			}
		}
	}
	
	return $username;
}


//
// Password Protected Forums
//
function password_check ($mode, $id, $password, $redirect)
{
	global $db, $template, $theme, $images, $board_config, $lang, $phpEx, $phpbb_root_path, $userdata;
	global $HTTP_COOKIE_VARS;

	$cookie_name = $board_config['cookie_name'];
	$cookie_path = $board_config['cookie_path'];
	$cookie_domain = $board_config['cookie_domain'];
	$cookie_secure = $board_config['cookie_secure'];

	switch($mode)
	{
		case 'topic':
			$sql = "SELECT topic_password AS password 
				FROM " . TOPICS_TABLE . " 
				WHERE topic_id = $id";
			$passdata = ( isset($HTTP_COOKIE_VARS[$cookie_name . '_tpass']) ) ? unserialize(stripslashes($HTTP_COOKIE_VARS[$cookie_name . '_tpass'])) : '';
			$savename = $cookie_name . '_tpass';
		break;
		case 'forum':
			$sql = "SELECT forum_password AS password 
				FROM " . FORUMS_TABLE . " 
				WHERE forum_id = $id";
			$passdata = ( isset($HTTP_COOKIE_VARS[$cookie_name . '_fpass']) ) ? unserialize(stripslashes($HTTP_COOKIE_VARS[$cookie_name . '_fpass'])) : '';
			$savename = $cookie_name . '_fpass';
		break;
		default:
			$sql = $passdata = '';
		break;
	}

	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Could not retrieve password', '', __LINE__, __FILE__, $sql);
	}

	$row = $db->sql_fetchrow($result);
	if( $password != $row['password'] )
	{
		$message = $lang['Incorrect_forum_password'] . '<br /><br />' . sprintf($lang['Click_return_login'], '<a href="' . $redirect . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid('index.'.$phpEx) . '">', '</a>');
		message_die(GENERAL_MESSAGE, $message);
	}

	$passdata[$id] = md5($password);
	setcookie($savename, serialize($passdata), 0, $cookie_path, $cookie_domain, $cookie_secure);

	$template->assign_vars(array(
		'META' => '<meta http-equiv="refresh" content="3; url="' . $redirect . '" />')
	);

	$message = $lang['Password_login_success'] . '<br /><br />' . sprintf($lang['Click_return_page'], '<a href="' . $redirect . '">', '</a>');
	
	message_die(GENERAL_MESSAGE, $message);
}

function password_box ($mode, $s_form_action)
{
	global $db, $template, $theme, $images, $board_config, $lang, $phpEx, $phpbb_root_path, $userdata;

	$page_title = $lang['Enter_forum_password'];
	include($phpbb_root_path . 'includes/page_header.'.$phpEx);

	$template->set_filenames(array(
		'body' => 'viewpassword_body.tpl')
	);

	$template->assign_vars(array(
		'L_ENTER_PASSWORD' => $page_title,
		'L_ENTER_PASSWORD_EXPLAIN' => $lang['Forum_password_explain'],
		'L_PASSWORD' => $lang['Enter_password'],

		'S_FORM_ACTION' => $s_form_action)
	);

	$template->pparse('body');
	
	include($phpbb_root_path . 'includes/page_tail.'.$phpEx);
}


// 
// PayPal IPN Group Subscriptions
//
function lw_convert_period_basis($pbasis)
{
	$grp_period_basis = '';
	if ( strcasecmp($pbasis, 'D') == 0 )
	{
		$grp_period_basis = 'Day(s)';
	}
	if ( strcasecmp($pbasis, 'W') == 0 )
	{
		$grp_period_basis = 'Week(s)';
	}
	if ( strcasecmp($pbasis, 'M') == 0 )
	{
		$grp_period_basis = 'Month(s)';
	}
	if ( strcasecmp($pbasis, 'Y') == 0 )
	{
		$grp_period_basis = 'Year(s)';
	}
	
	return $grp_period_basis;
}

function lw_check_membership(&$userinfo)
{
	global $db;

	$result = 0;
	if ( $userinfo['user_level'] != ADMIN && $userinfo['user_level'] != LESS_ADMIN && $userinfo['user_level'] != MOD )
	{
		$sql = "SELECT * FROM " . GROUPS_TABLE . " 
			WHERE group_type = " . GROUP_PAYMENT . " 
				AND group_amount > 0 
				AND group_moderator <> " . $userinfo['user_id'];
		if ( !($result = $db->sql_query($sql)) )
		{
			return $result;
		}
		$group_infos = array();
		if( ($group_info = $db->sql_fetchrow($result)) )
		{
			do
			{
				$group_infos[] = $group_info;
			}
			while( $group_info = $db->sql_fetchrow($result) );
		}
		$groupwhere = '';
		for($i = 0; $i < count($group_infos); $i++)
		{
			if($i == 0)
			{
				$groupwhere .= "(";
			}
			$groupwhere .= "group_id = " . $group_infos[$i]['group_id'];
			if($i < (sizeof($group_infos) - 1))
			{
				$groupwhere .= " OR ";
			}
			else
			{
				$groupwhere .= ") AND user_id = " . $userinfo['user_id'] . " AND ug_expire_date < " . time();
			}
		}
		if(strlen($groupwhere) > 0)
		{
			$sql = "DELETE FROM " . USER_GROUP_TABLE . " 
				WHERE $groupwhere";
			if( !($result = $db->sql_query($sql)) )
			{
				//do nothing
				return $result;
			}
		}
	}
	$result = 1;

	return $result;
}

function lw_grap_sys_paypal_acct()
{
	global $board_config;

	//get payment account, use business account first, if not exist, then choose personal account
	$paypalaccount = "";
	if ( strlen($board_config['paypal_p_acct']) <= 0 && strlen($board_config['paypal_b_acct']) <= 0)
	{
		return $paypalaccount;
	}
	if ( isset($board_config['paypal_b_acct']) && strlen($board_config['paypal_b_acct']) > 0)
	{
		$paypalaccount = $board_config['paypal_b_acct'];
	}
	else
	{
		$paypalaccount = $board_config['paypal_p_acct'];
	}
	
	return $paypalaccount;
}

function lw_cal_cash_exchange_rate($currency, $configuration)
{
	$convertor = 1.0;
	if ( strcasecmp($currency, 'USD') == 0)
	{
		$convertor = $configuration['usd_to_primary'];
	}
	else if ( strcasecmp($currency, 'EUR') == 0)
	{
		$convertor = $configuration['eur_to_primary'];
	}
	else if ( strcasecmp($currency, 'GBP') == 0)
	{
		$convertor = $configuration['gbp_to_primary'];
	}
	else if ( strcasecmp($currency, 'CAD') == 0)
	{
		$convertor = $configuration['cad_to_primary'];
	}
	else if ( strcasecmp($currency, 'JPY') == 0)
	{
		$convertor = $configuration['jpy_to_primary'];
	}
	else if ( strcasecmp($currency, 'AUD') == 0)
	{
		$convertor = $configuration['aud_to_primary'];
	}
	if ( $convertor <= 0 )
	{
		$convertor = 1.0;
	}
	
	return $convertor;
}


//
// Disable board if needed
//
function board_disable()
{
	global $board_config, $lang, $userdata;

	// avoid multiple function calls
	static $called = false;
	if ($called == true)
	{
		return;
	}
	$called = true;

	if (!empty($board_config['board_disable']) && !defined('IN_ADMIN') && !defined('IN_LOGIN'))
	{
		$disable_mode = explode(',', $board_config['board_disable_mode']);
		$user_level = ($userdata['user_id'] == ANONYMOUS) ? ANONYMOUS : $userdata['user_level'];

		if (in_array($user_level, $disable_mode))
		{
			$disable_message = (!empty($board_config['board_disable_text'])) ? $board_config['board_disable_text'] : $lang['Board_disable'];
			message_die(GENERAL_MESSAGE, str_replace("\n", '<br />', $disable_message), 'Information');
		}
		else
		{
			define('BOARD_DISABLE', true);
		}
	}
}

//
// Capatalization of topic title
//
function capitalization($topic_title)
{
	global $board_config;
	
	switch($board_config['capitalization'])
	{
		case 1: 
			$topic_title = strtoupper($topic_title);
	        break;
	    case 2: 
	    	$topic_title = strtolower($topic_title);
	        break;
	    case 3: 
	    	$topic_title = ucfirst($topic_title);
	        break;
	    case 4: 
	    	$topic_title = ucwords($topic_title);
	        break;
	    default: 
	    	break;
	}
	
	return $topic_title;
}

//
// Replace special characters in the skype username
//
function prepare_skype_http($var)
{
	$old[] = '.';
	$new[] = '%2E';
	$old[] = '_';
	$new[] = '%5F';
	$old[] = ',';
	$new[] = '%2C';
	$old[] = '@';
	$new[] = '%40';
	$old[] = '-';
	$new[] = '%2D';
	
	$var = str_replace($old, $new, $var);
	
	return $var;
}

//
// Subscriptions
//
function lw_write_header_reminder()
{
	global $db, $userdata, $board_config, $phpbb_root_path, $phpEx, $lang;
	
	$lwuserreminder = '';	
	if ( $userdata['user_level'] != ADMIN && $userdata['user_level'] != LESS_ADMIN && $userdata['user_level'] != MOD )
	{
		$count = 0;
		$sql = "SELECT g.*, ug.* 
			FROM " . GROUPS_TABLE . " g, " . USER_GROUP_TABLE . " ug 
			WHERE g.group_type = " . GROUP_PAYMENT . " 
				AND g.group_amount > 0 
				AND g.group_id = ug.group_id 
				AND ug.user_id = " . $userdata['user_id'];
		if ( ($result = $db->sql_query($sql)) )
		{
			$language = $board_config['default_lang'];
			if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_ipn_grp.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_ipn_grp.' . $phpEx);
			
			if( ($group_info = $db->sql_fetchrow($result)) )
			{
				$lwuserreminder .= sprintf($lang['L_IPN_Subscribe_header_welcome'], $userdata['username']);
				$lwuserreminder .= '<br /><select id="group_id" name="group_id">';
				do
				{
					$lwuserreminder .= '<option value="' . $group_info['group_id'] . '">' . $group_info['group_name'] . sprintf($lang['L_IPN_Subscribe_expire_date'], create_date($userdata['user_dateformat'], $group_info['ug_expire_date'], $userdata['user_timezone'])) . '</option>';
				}
				while( $group_info = $db->sql_fetchrow($result) );
				$lwuserreminder .= '</select>';
				$count = 1;
			}
		}
		
		if ( $count == 0 )
		{
			$lwuserreminder = sprintf($lang['LW_Welcome_Nopaid_Member'], $userdata['username']);			
		}
		
		if ( $userdata['user_rank'] > 0 )
		{
			$sql = "SELECT rank_id, rank_title  
				FROM " . RANKS_TABLE . " 
				WHERE rank_id = " . $userdata['user_rank'];
			if ( ($resultr = $db->sql_query($sql)) )
			{
				if ( $rowr = $db->sql_fetchrow($resultr) )
				{
					if ( strcmp(trim($rowr['rank_title']), trim(VIP_RANK_TITLE) ) == 0 )
					{
						$lwuserreminder = sprintf($lang['LW_YOU_ARE_VIP'], $userdata['username']);
					}
				}	
			}
		}
 	}

 	return $lwuserreminder;
}

//
// Function checks whether user is authorized to use moderator tags
// $mod_permission not 0 for admins and moderators
//
function check_mod_tags ($mod_permission, $message)
{
	if ( (!$mod_permission) && (preg_match("/\[mod\:\S+?\]/si", $message)) )
    {
		return true;
	}
}

//
// Function gets new registering users today,
// yesterday & this week for board index stats
// 
function GetNewStats()
{
	global $db, $lang, $board_config;
		
	$today = time();
	$minutes = date('is', $today);
	$hour = $today - (60 * ($minutes[0].$minutes[1])) - ($minutes[2].$minutes[3]); 
	$date = create_date('H', $today, $board_config['board_timezone']);
	$todayT = $hour - (3600 * $date);
				
	$yesterday 	= time() - 86400;
	$minutes = date('is', $yesterday);
	$hour = $yesterday - (60 * ($minutes[0].$minutes[1])) - ($minutes[2].$minutes[3]); 
	$date = create_date('H', $yesterday, $board_config['board_timezone']);
	$yester = $hour - (3600 * $date); 
				
	$this_week = time();
	$minutes = date('is', $this_week);
	$hour_now = $this_week - (60 * ($minutes[0].$minutes[1])) - ($minutes[2].$minutes[3]); 
	$date = date('H');
	$time_today = $hour_now - (3600 * $date); 
	$thisweek = $time_today - ((date('w', $time_today) -1) * 86400);

//	$thisweek2 = time() - (7 * 86400);
//	echo $thisweek . '<hr />' . $thisweek2;
					
	$sql = "SELECT user_regdate
		FROM " . USERS_TABLE;
	if( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not query user_regdate information', '', __LINE__, __FILE__, $sql);
	}
	
	$result = $db->sql_query($sql);		
	$t = $db->sql_fetchrowset($result);
	$t_t = $db->sql_numrows($result);
		
	$today_count = $yesterday_count = $lweek_count = 0;
	for ($j = 0; $j < $t_t; $j++)
	{		
		if ($t[$j]['user_regdate'] >= $todayT)
		{
			$today_count++;															
		}
	}
							
	for ($j = 0; $j < $t_t; $j++)
	{		
		if (($t[$j]['user_regdate'] >= $yester) && ($t[$j]['user_regdate'] < $todayT))
		{
			$yesterday_count++;															
		}
	}
			
	for ($j = 0; $j < $t_t; $j++)
	{		
		if ($t[$j]['user_regdate'] >= $thisweek)
		{
			$lweek_count++;															
		}
	}												
													
	$info_line = str_replace("%T%", $today_count, $lang['new_members_key']);
	$info_line2 = str_replace("%Y%", $yesterday_count, $info_line);
	$info = str_replace("%W%", $lweek_count, $info_line2);		
	
	$db->sql_freeresult($result);
	
	return $info;
}


//
// Function logs moderator actions
//
function log_action($action, $topic_id, $user_id, $username)
{
	global $db;

	$sql = "SELECT session_ip
		FROM " . SESSIONS_TABLE . "
		WHERE session_user_id = $user_id ";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not select session_ip', '', __LINE__, __FILE__, $sql);
	}
	$row = $db->sql_fetchrow($result);

	$sql = "INSERT INTO " . LOGS_TABLE . " (mode, topic_id, user_id, username, user_ip, time)
		VALUES ('$action', $topic_id, $user_id, '" . addslashes($username) . "', '" . $row['session_ip'] . "', " . time() . ")";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not insert data into logs table', '', __LINE__, __FILE__, $sql);
	}
}

// 
// This function checks whether the user agent or ip is
// listed as a bot and returns true otherwise false.
// 
function is_robot() 
{ 
	global $db, $board_config, $lang, $_SERVER, $REMOTE_ADDR, $HTTP_USER_AGENT;

	// get required user data
	$user_ip = (isset($_SERVER['REMOTE_ADDR']) ) ? $_SERVER['REMOTE_ADDR'] : $REMOTE_ADDR; 
	$user_agent = (isset($_SERVER['HTTP_USER_AGENT']) ) ? $_SERVER['HTTP_USER_AGENT'] : $HTTP_USER_AGENT; 

	// get bot table data
	$sql = "SELECT *
		FROM " . BOTS_TABLE;
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain bot data.', '', __LINE__, __FILE__, $sql);
	}

	// loop through bots table
	while ($row = $db->sql_fetchrow($result))
	{
		// clear vars
		$agent_match = $ip_match = 0;

		// check for user agent match
		foreach (explode('|', $row['bot_agent']) AS $bot_agent)
		{
			if ($row['bot_agent'] && $bot_agent != '' && preg_match('#' . preg_quote($bot_agent, '#') . '#i', $user_agent)) 
			{
				$agent_match = 1;

				if ($board_config['enable_bot_tracking'])
				{
					$temp_bot_url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'] . (($_SERVER['QUERY_STRING'] != '') ? '?' . $_SERVER['QUERY_STRING'] : '');
	
					$sql = "INSERT INTO " . BOTS_ARCHIVE_TABLE . " (bot_name, bot_time, bot_url) 
						VALUES('" . $row['bot_name'] . "', " . time() . ", '" . phpbb_clean_username($temp_bot_url) . "')";
					if (!($result = $db->sql_query($sql)))
					{
						message_die(GENERAL_ERROR, 'Could not insert bot visit.', '', __LINE__, __FILE__, $sql);
					}
			
					if ($board_config['enable_bot_email'])
					{
						$email_from = 'From: ' . $board_config['board_email'] . "\n";
						$email_to = $board_config['board_email']; 
						$email_subject = sprintf($lang['Bot_subject'], $row['bot_name']);
						$email_body = sprintf($lang['Bot_text'], $row['bot_name']);
				
						mail($email_to, $email_subject, $email_body, $email_from);
					} 
				}
			}
		}
		
		// check for ip match
		foreach (explode('|', $row['bot_ip']) AS $bot_ip)
		{
			if ($row['bot_ip'] && $bot_ip != '' && strpos($user_ip, $bot_ip) === 0)
			{
				$ip_match = 1;
				break;
			}
		}

		// if both ip and agent matched update table and return true
		if ($agent_match == 1 && $ip_match == 1)
		{
			// get time - seconds from epoch
			$today = time();
			$last_visits = explode('|', $row['last_visit']);

			// if half an hour has passed since last visit
			if (($today - (($last_visits[0] == '') ? 0 : $last_visits[0])) > 2700)
			{
				for ($i = ((4 > sizeof($last_visits)) ? sizeof($last_visits) : 4); $i >= 0; $i--)
				{
					if ($last_visits[$i-1] != '') 
					{
						$last_visits[$i] = $last_visits[$i-1];
					}
				}
				
				// increment the new visit counter
				$row['bot_visits']++;

				// clear prior indexed pages
				$row['bot_pages'] = 1;
			} 
			else 
			{
				// add to indexed pages
				$row['bot_pages']++;
			}

			$last_visits[0] = $today;

			// compress it all together
			$last_visit = implode('|', $last_visits);

			// update table
			$sql = "UPDATE " . BOTS_TABLE . " 
				SET last_visit = '$last_visit', bot_visits = '" . $row['bot_visits'] . "', bot_pages = '" . $row['bot_pages'] . "'
				WHERE bot_id = " . $row['bot_id'];
			if ( !($result2 = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Couldn\'t update data in bots table.', '', __LINE__, __FILE__, $sql);
			}

			return $row['bot_name'];
		} 
		else 
		{
			if ($agent_match == 1 || $ip_match == 1)
			{
				// get data from table
				$sql = "SELECT pending_" . ((!$agent_match) ? 'agent' : 'ip') . " 
					FROM " . BOTS_TABLE . " 
					WHERE bot_id = " . $row['bot_id'];
				if ( !($result2 = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'Could not obtain bot data.', '', __LINE__, __FILE__, $sql);
				}

				$row2 = $db->sql_fetchrow($result2);

				// add ip/agent to the list
				$pending_array = (( $row2['pending_' . ((!$agent_match) ? 'agent' : 'ip')] ) ? explode('|', $row2['pending_' . ((!$agent_match) ? 'agent' :  'ip')]) : array());

				$found = 0;
				if ( sizeof($pending_array) )
				{
					for ($loop = 0; $loop < sizeof($pending_array); $loop+=2)
					{
						if ($pending_array[$loop] == ((!$agent_match) ? $user_agent : $user_ip)) 
						{
							$found = 1;
						}
					}
				}
				
				if ($found == 0) 
				{
					$pending_array[] = ((!$agent_match) ? str_replace('|', '&#124;', $user_agent) : $user_ip);
					$pending_array[] = ((!$agent_match) ? $user_ip : str_replace('|', '&#124;', $user_agent));
				}
				
				$pending = implode('|', $pending_array);

				// update table
				$sql = "UPDATE " . BOTS_TABLE . " 
					SET pending_" . ((!$agent_match) ? 'agent' : 'ip') . " = '$pending'
					WHERE bot_id = " . $row['bot_id'];
				if ( !($result2 = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'Couldn\'t update data in bots table.', '', __LINE__, __FILE__, $sql);
				}
			}		
		}
	}
	$db->sql_freeresult($result);

	return 0;
}

//
// Function medal system
//
function check_medal_mod($medal_id)
{
	global $db, $userdata;
	
	$sql = "SELECT *
		FROM " . MEDAL_MOD_TABLE . "  
		WHERE medal_id = " . $medal_id;
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain user and medal information', '', __LINE__, __FILE__, $sql);
	}

	$found = false;
	while ( $row= $db->sql_fetchrow($result) )
	{
		$medal_moderator = $row['user_id'];

		if ( $medal_moderator == $userdata['user_id'] )
		{
			$found = true;
		}
	}
	$db->sql_freeresult($result);
	
	return $found;
}

?>