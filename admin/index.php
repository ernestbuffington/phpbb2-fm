<?php
/** 
*
* @package admin
* @version $Id: index.php,v 1.40.2.6 2004/07/11 16:46:15 acydburn Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', 1);

//
// Load default header
//
$no_page_header = TRUE;
$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);

//
// Begin function
//
function inarray($needle, $haystack)
{ 
	for($i = 0; $i < sizeof($haystack); $i++ )
	{ 
		if( $haystack[$i] == $needle )
		{ 
			return true; 
		} 
	} 
	return false; 
}

//
// Include language file
//
$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_admin_pafiledb.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_admin_pafiledb.' . $phpEx);


//
// Generate relevant output
//
include('./page_header_admin.'.$phpEx);

$template->set_filenames(array(
	'body' => 'admin/index_body.tpl')
);
	
//
// Get forum statistics
//
$administrator_names = $less_administrator_names = $moderator_names = $deactivated_names = '';

$total_posts = get_db_stat('postcount');
$total_users = get_db_stat('usercount');
$total_topics = get_db_stat('topiccount');
$total_pvt = get_db_stat('pvtcount');
$total_disable = get_db_stat('disablecount');
$total_male = get_db_stat('gender_male');
$total_female = get_db_stat('gender_female');

$start_date = create_date($board_config['default_dateformat'], $board_config['board_startdate'], $board_config['board_timezone']);

$boarddays = ( time() - $board_config['board_startdate'] ) / 86400;

$posts_per_day = sprintf("%.2f", $total_posts / $boarddays);
$topics_per_day = sprintf("%.2f", $total_topics / $boarddays);
$pvt_per_day = sprintf("%.2f", $total_pvt / $boarddays);
    $pvt_per_user = sprintf("%.2f", $total_pvt / $total_users);
	$users_per_day = sprintf("%.2f", $total_users / $boarddays);

	// Attachment stats
	if (!intval($attach_config['allow_ftp_upload']))
	{
		if ( ($attach_config['upload_dir'][0] == '/') || ( ($attach_config['upload_dir'][0] != '/') && ($attach_config['upload_dir'][1] == ':') ) )
		{
			$upload_dir = $attach_config['upload_dir'];
		}
		else
		{
			$upload_dir = '../' . $attach_config['upload_dir'];
		}
	}
	else
	{
		$upload_dir = $attach_config['download_path'];
	}
	include($phpbb_root_path . 'mods/attachments/includes/functions_admin.'.$phpEx);
	
	$sql = "SELECT COUNT(*) AS total_attachments
		FROM " . ATTACHMENTS_DESC_TABLE;
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Error getting total attachments', '', __LINE__, __FILE__, $sql);
	}
	$total = $db->sql_fetchrow($result);
	
	$total_attachments = $total['total_attachments'];
	$attachments_per_day = sprintf("%.2f", $total_attachments / $boarddays);

	// Avatar stats
	$avatar_dir_size = 0;

	if ($avatar_dir = @opendir($phpbb_root_path . $board_config['avatar_path']))
	{
		while( $file = @readdir($avatar_dir) )
		{
			if( $file != '.' && $file != '..' && $file != 'index.html' && $file != 'index.htm' && $file != 'Thumbs.db' )
			{
				$avatar_dir_size += @filesize($phpbb_root_path . $board_config['avatar_path'] . '/' . $file);
			}
		}
		@closedir($avatar_dir);

		//
		// This bit of code translates the avatar directory size into human readable format
		// Borrowed the code from the PHP.net annoted manual, origanally written by:
		// Jesse (jesse@jess.on.ca)
		//
		if($avatar_dir_size >= 1048576)
		{
			$avatar_dir_size = round($avatar_dir_size / 1048576 * 100) / 100 . " MB";
		}
		else if($avatar_dir_size >= 1024)
		{
			$avatar_dir_size = round($avatar_dir_size / 1024 * 100) / 100 . " KB";
		}
		else
		{
			$avatar_dir_size = $avatar_dir_size . " Bytes";
		}
	}
	else
	{
		// Couldn't open Avatar dir.
		$avatar_dir_size = $lang['Not_available'];
	}

	if($posts_per_day > $total_posts)
	{
		$posts_per_day = $total_posts;
	}

	if($topics_per_day > $total_topics)
	{
		$topics_per_day = $total_topics;
	}

	if($users_per_day > $total_users)
	{
		$users_per_day = $total_users;
	}

	if($pvt_per_day > $total_pvt)
	{
		$pvt_per_day = $total_pvt;
	}

	if($attachments_per_day > $total_attachments)
	{
		$attachments_per_day = $total_attachments;
	}

	//
	// DB size ... MySQL only
	//
	// This code is heavily influenced by a similar routine
	// in phpMyAdmin 2.2.0
	//
	if( preg_match("/^mysql/", SQL_LAYER) )
	{
		$sql = "SELECT VERSION() AS mysql_version";
		if($result = $db->sql_query($sql))
		{
			$row = $db->sql_fetchrow($result);
			$version = $row['mysql_version'];

			if( preg_match("/^(3\.23|4\.|5\.)/", $version) )
			{
				$db_name = ( preg_match("/^(3\.23\.[6-9])|(3\.23\.[1-9][1-9])|(4\.)|(5\.)/", $version) ) ? "`$dbname`" : $dbname;

				$sql = "SHOW TABLE STATUS 
					FROM " . $db_name;
				if($result = $db->sql_query($sql))
				{
					$tabledata_ary = $db->sql_fetchrowset($result);

					$counter = $dbsize = $size_users_tables = $size_pvt_tables = $size_posts_tables = $size_search_tables = 0;
					for($i = 0; $i < sizeof($tabledata_ary); $i++)
					{
						if( $tabledata_ary[$i]['Type'] != "MRG_MyISAM" )
						{
							if( $prefix != "" )
							{
								if( strstr($tabledata_ary[$i]['Name'], $prefix) )
								{
									$counter++;
									$dbsize += $tabledata_ary[$i]['Data_length'] + $tabledata_ary[$i]['Index_length'];
								}
								
								if( $tabledata_ary[$i]['Name'] == PRIVMSGS_TABLE || $tabledata_ary[$i]['Name'] == PRIVMSGS_TEXT_TABLE)
								{
									$size_pvt_tables += $tabledata_ary[$i]['Data_length'] + $tabledata_ary[$i]['Index_length'];
								}
								
								if( $tabledata_ary[$i]['Name'] == POSTS_TABLE || $tabledata_ary[$i]['Name'] == POSTS_TEXT_TABLE)
								{
									$size_posts_tables += $tabledata_ary[$i]['Data_length'] + $tabledata_ary[$i]['Index_length'];
								}
								
								if( $tabledata_ary[$i]['Name'] == SEARCH_WORD_TABLE || $tabledata_ary[$i]['Name'] == SEARCH_MATCH_TABLE)
								{
									$size_search_tables += $tabledata_ary[$i]['Data_length'] + $tabledata_ary[$i]['Index_length'];
								}
								
								if( $tabledata_ary[$i]['Name'] == USERS_TABLE || $tabledata_ary[$i]['Name'] == TABLE_USER_SHOPS || $tabledata_ary[$i]['Name'] == TABLE_USER_SHOP_ITEMS)
								{
									$size_users_tables += $tabledata_ary[$i]['Data_length'] + $tabledata_ary[$i]['Index_length'];
								}
							}
							else
							{
								$dbsize += $tabledata_ary[$i]['Data_length'] + $tabledata_ary[$i]['Index_length'];
							}
						}
					}
					$tablecount = $counter;
				} // Else we couldn't get the table status.
			}
			else
			{
				$dbsize = $lang['Not_available'];
			}
		}
		else
		{
			$dbsize = $lang['Not_available'];
		}
	}
	else if( preg_match("/^mssql/", SQL_LAYER) )
	{
		$sql = "SELECT ((SUM(size) * 8.0) * 1024.0) AS dbsize 
			FROM sysfiles"; 
		if( $result = $db->sql_query($sql) )
		{
			$dbsize = ( $row = $db->sql_fetchrow($result) ) ? intval($row['dbsize']) : $lang['Not_available'];
		}
		else
		{
			$dbsize = $lang['Not_available'];
		}
	}
	else
	{
		$dbsize = $lang['Not_available'];
	}

	if ( is_integer($dbsize) )
	{
		if( $dbsize >= 1048576 )
		{
			$dbsize = sprintf("%.2f MB", ( $dbsize / 1048576 ));
		}
		else if( $dbsize >= 1024 )
		{
			$dbsize = sprintf("%.2f KB", ( $dbsize / 1024 ));
		}
		else
		{
			$dbsize = sprintf("%.2f Bytes", $dbsize);
		}
	}

	$attachment_dir_size = get_formatted_dirsize();

	$size_pvt_tables = sprintf("%.2f MB", ($size_pvt_tables / 1048576));
	$size_posts_tables = sprintf("%.2f MB", ($size_posts_tables / 1048576));
	$size_search_tables = sprintf("%.2f MB", ($size_search_tables / 1048576));
	$size_users_tables = sprintf("%.2f MB", ($size_users_tables / 1048576));
	$max_pvt_per_user = $board_config['max_inbox_privmsgs'] + $board_config['max_savebox_privmsgs'] + $board_config['max_sentbox_privmsgs'];
	$max_pvt_forum = intval($max_pvt_per_user * $total_users);
	$perc_pvt = sprintf('%.2f', ($total_pvt / $max_pvt_forum));
	$perc_pvt = $perc_pvt * 100 . ' %';

	// Check for new version
	if(!$board_config['callhome_disable'])
	{
  		// Check for new version of Fully Modded phpBB
  		$current_fm_version = $board_config['fm_version'];
  		$errno = 0;
   		$errstr = $fm_version_info = '';
		$fm_cache_file = $phpbb_root_path . 'cache/tpls/update_' . $board_config['default_lang'] . '_fm' . $board_config['fm_version'] . '.php';
	
		$do_update = true;
		if(@file_exists($fm_cache_file))
		{
			$last_update = 0;
			$version_info = '';
			@include($fm_cache_file);
			if($last_update && !empty($version_info) && $last_update > (time() - $cache_update))
			{
				$do_update = false;
			}	
			else
			{
				$version_info = '';
			}
		}
	
		if($do_update)
		{
	    	if ($fsock = @fsockopen('www.phpbb-fm.com', 80, $errno, $errstr, 10))
	   		{
	    		@fputs($fsock, "GET /updatecheck/20x.txt HTTP/1.1\r\n");
	    		@fputs($fsock, "HOST: www.phpbb-fm.com\r\n");
	    		@fputs($fsock, "Connection: close\r\n\r\n");
		
		      	$get_info = false;
		      	while (!@feof($fsock))
		      	{
		        	if ($get_info)
		        	{
		            	$fm_version_info .= @fread($fsock, 1024);
		         	}
		         	else
		         	{
		         		if (@fgets($fsock, 1024) == "\r\n")
		            	{
		            	   $get_info = true;
		            	}
		         	}
		      	}
		      	@fclose($fsock); 
		    
		      	$latest_fm_version = $fm_version_info;
		
		    	if ($latest_fm_version == $current_fm_version)
		    	{
		    		$version_info = '';
		   	 	}
		    	else
		    	{
		    		$version_info = '<p class="errorpage"><span class="errorpageh1">' . $lang['Attention'] . '</span><br />' . $lang['Version_fm_not_up_to_date'] . '<br />' . sprintf($lang['Latest_fm_version_info'], $latest_fm_version) . ' - ' . sprintf($lang['Current_fm_version_info'], $board_config['fm_version']) . '</p>';
		   		}
			}
			else
		   	{
		   		if ($errstr)
		    	{
		        	$version_info = '<p class="errorpage">' . sprintf($lang['Connect_socket_error'], $errstr) . '</p>';
		      	}
		      	else
		      	{
		      		$version_info = '<p class="errorpage">' . $lang['Socket_functions_disabled'] . '</p>';
		      	}
		   	}

			if(@$f = @fopen($fm_cache_file, 'w'))
			{
				$search = array('\\', '\'');
				$replace = array('\\\\', '\\\'');
				@fwrite($f, '<' . '?php if (!defined(\'IN_PHPBB\')) { die(\'Hacking attempt\'); } $last_update = ' . time() . '; $version_info = \'' . str_replace($search, $replace, $version_info) . '\'; ?' . '>');	
				@fclose($f);
				@chmod($fm_cache_file, 0777);
			}
		}
	}
	else
	{
		$version_info = '<p class="errorpage">' . sprintf($lang['Is_Disable_Callhome'], '<a href="' . append_sid('admin_board.'.$phpEx) . '">', '</a>') . '</p>';
	}
	
	// Inactive users
	$sql = "SELECT COUNT(user_id) AS total
		FROM " . USERS_TABLE . "
		WHERE user_active = 0
			AND user_id <> " . ANONYMOUS;
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, "Couldn't get statistic data.", '', __LINE__, __FILE__, $sql);
	}
	if ( $row = $db->sql_fetchrow($result) )
	{
		$total_deactivated_users = $row['total'];
	}
	else
	{
		message_die(GENERAL_ERROR, "Couldn't get statistic data.", '', __LINE__, __FILE__, $sql);
	}
	$db->sql_freeresult($result);
	
	if (!empty($total_deactivated_users))
	{
		$sql = "SELECT user_id, username, user_level
			FROM " . USERS_TABLE . "
			WHERE user_active = 0
				AND user_id <> " . ANONYMOUS . "
			ORDER BY username";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, "Couldn't get statistic data.", '', __LINE__, __FILE__, $sql);
		}
		while ( $row = $db->sql_fetchrow($result) )
		{
			$deactivated_names .= (($deactivated_names == '') ? '' : ', ') . '<a href="' . append_sid('admin_users.'.$phpEx.'?mode=edit&amp;' . POST_USERS_URL . '=' . $row['user_id']) . '">' . username_level_color($row['username'], $row['user_level'], $row['user_id']) . '</a>';
		}
		$db->sql_freeresult($result);
	}
	
	// Mods
	$sql = "SELECT COUNT(user_id) AS total
		FROM " . USERS_TABLE . "
		WHERE user_level = " . MOD . "
			AND user_active = 1
			AND user_id <> " . ANONYMOUS;
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, "Couldn't get statistic data.", '', __LINE__, __FILE__, $sql);
	}
	if ( $row = $db->sql_fetchrow($result) )
	{
		$total_moderators = $row['total'];
	}
	else
	{
		message_die(GENERAL_ERROR, "Couldn't get statistic data.", '', __LINE__, __FILE__, $sql);
	}
	$db->sql_freeresult($result);

	if (!empty($total_moderators))
	{
		$sql = "SELECT user_id, username, user_level
			FROM " . USERS_TABLE . "
			WHERE user_level = " . MOD . "
				AND user_active = 1
				AND user_id <> " . ANONYMOUS . "
			ORDER BY username";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, "Couldn't get statistic data.", '', __LINE__, __FILE__, $sql);
		}
		while ( $row = $db->sql_fetchrow($result) )
		{
			$moderator_names .= (($moderator_names == '') ? '' : ', ') . '<a href="' . append_sid('admin_users.'.$phpEx.'?mode=edit&amp;' . POST_USERS_URL . '=' . $row['user_id']) . '">' . username_level_color($row['username'], $row['user_level'], $row['user_id']) . '</a>';
		}
		$db->sql_freeresult($result);
	}
	
	// Super Mods
	$sql = "SELECT COUNT(user_id) AS total
		FROM " . USERS_TABLE . "
		WHERE user_level = " . LESS_ADMIN . "
			AND user_active = 1
			AND user_id <> " . ANONYMOUS;
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, "Couldn't get statistic data.", '', __LINE__, __FILE__, $sql);
	}
	if ( $row = $db->sql_fetchrow($result) )
	{
		$total_less_administrators = $row['total'];
	}
	else
	{
		message_die(GENERAL_ERROR, "Couldn't get statistic data.", '', __LINE__, __FILE__, $sql);
	}
	$db->sql_freeresult($result);

	if (!empty($total_less_administrators))
	{
		$sql = "SELECT user_id, username, user_level
			FROM " . USERS_TABLE . "
			WHERE user_level = " . LESS_ADMIN . "
				AND user_active = 1
				AND user_id <> " . ANONYMOUS . "
			ORDER BY username";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, "Couldn't get statistic data.", '', __LINE__, __FILE__, $sql);
		}
		while ( $row = $db->sql_fetchrow($result) )
		{
			$less_administrator_names .= (($less_administrator_names == '') ? '' : ', ') . '<a href="' . append_sid('admin_users.'.$phpEx.'?mode=edit&amp;' . POST_USERS_URL . '=' . $row['user_id']) . '">' . username_level_color($row['username'], $row['user_level'], $row['user_id']) . '</a>';
		}
		$db->sql_freeresult($result);
	}
		
	// Admins
	$sql = "SELECT COUNT(user_id) AS total
		FROM " . USERS_TABLE . "
		WHERE user_level = " . ADMIN . "
			AND user_active = 1
			AND user_id <> " . ANONYMOUS;
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, "Couldn't get statistic data.", '', __LINE__, __FILE__, $sql);
	}
	if ( $row = $db->sql_fetchrow($result) )
	{
		$total_administrators = $row['total'];
	}
	else
	{
		message_die(GENERAL_ERROR, "Couldn't get statistic data.", '', __LINE__, __FILE__, $sql);
	}
	$db->sql_freeresult($result);

	if (!empty($total_administrators))
	{
		$sql = "SELECT user_id, username, user_level
			FROM " . USERS_TABLE . "
			WHERE user_level = " . ADMIN . "
				AND user_active = 1
				AND user_id <> " . ANONYMOUS . "
			ORDER BY username";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, "Couldn't get statistic data.", '', __LINE__, __FILE__, $sql);
		}
		while ( $row = $db->sql_fetchrow($result) )
		{
			$administrator_names .= (($administrator_names == '') ? '' : ', ') . '<a href="' . append_sid('admin_users.'.$phpEx.'?mode=edit&amp;' . POST_USERS_URL . '=' . $row['user_id']) . '">' . username_level_color($row['username'], $row['user_level'], $row['user_id']) . '</a>';
		}
		$db->sql_freeresult($result);
	}
		
	$template->assign_vars(array(		
		'NUMBER_OF_POSTS' => number_format($total_posts),
		'POSTS_PER_DAY' => $posts_per_day,
		'NUMBER_OF_TOPICS' => number_format($total_topics),
		'TOPICS_PER_DAY' => $topics_per_day,
		'NUMBER_OF_PVT' => $total_pvt,
		'PVT_PER_DAY' => $pvt_per_day,
		'MAX_PVT_PER_USER' => $max_pvt_per_user,
		'PVT_PER_USER' => $pvt_per_user,
		'NUMBER_DATA_DISABLE' => $total_disable,
		'PERC_PVT' => $perc_pvt,
		'NUMBER_OF_USERS' => number_format($total_users),
		'USERS_PER_DAY' => $users_per_day,
		'TOTAL_MALE' => number_format($total_male),
		'TOTAL_FEMALE' => number_format($total_female),
		'GENDER_RATIO' => ( $total_female && $total_male ) ? number_format(($total_female / $total_male * 100), 2) . ' %' : $lang['Not_available'],
		'NUMBER_OF_ATTACHMENTS' => number_format($total_attachments),
		'ATTACHMENTS_PER_DAY' => $attachments_per_day,
		'ATTACHMENT_DIR_SIZE' => $attachment_dir_size,
		'START_DATE' => $start_date,
		'AVATAR_DIR_SIZE' => $avatar_dir_size,
		'DB_SIZE' => $dbsize, 
		'SIZE_POSTS_TABLES' => $size_posts_tables,
		'SIZE_SEARCH_TABLES' => $size_search_tables,
		'SIZE_PVT_TABLES' => $size_pvt_tables,
		'SIZE_USERS_TABLES' => $size_users_tables,
		'VERSION_INFO' => $version_info,
		'TABLECOUNT' => ( $tablecount == TABLES_REQ ) ? $tablecount : (( $tablecount ) ? '<span class="error">' . $tablecount . '</span>' : $lang['Not_available']), 
		'PHPBB_VERSION' => '2' . $board_config['version'],
		'PHPBBFM_VERSION' => $board_config['fm_version'],
		'NUMBER_OF_DEACTIVATED_USERS' => $total_deactivated_users,
		'NAMES_OF_DEACTIVATED' => (!empty($deactivated_names)) ? '(' . $deactivated_names . ')' : '',
		'NUMBER_OF_ADMINISTRATORS' => $total_administrators,
		'NAMES_OF_ADMINISTRATORS' => (!empty($administrator_names)) ? '(' . $administrator_names . ')' : '',
		'NUMBER_OF_LESS_ADMIN' => $total_less_administrators,
		'NAMES_OF_LESS_ADMIN' => (!empty($less_administrator_names)) ? '(' . $less_administrator_names . ')' : '',
		'NUMBER_OF_MODERATORS' => $total_moderators,
		'NAMES_OF_MODERATORS' => (!empty($moderator_names)) ? '(' . $moderator_names . ')' : '',
	
		'U_NUMBER_DATA_DISABLE' => append_sid('admin_ban.'.$phpEx),
		'U_NUMBER_USERS' => append_sid('admin_user_list.'.$phpEx),
 		'U_NUMBER_PVT' => append_sid('admin_priv_msgs.'.$phpEx),
 	 	'U_NUMBER_ATTACHMENTS' => append_sid('admin_attach_cp.'.$phpEx.'?view=attachments'),
		'U_TABLECOUNT' => append_sid('admin_db_utilities_phpbbmyadmin.'.$phpEx),
 		'S_USER_ACTION' => append_sid('admin_users.'.$phpEx),
			
		'L_NUMBER_DEACTIVATED_USERS' => $lang['Thereof_deactivated_users'],
		'L_NAME_DEACTIVATED_USERS' => $lang['Deactivated_Users'],
		'L_NUMBER_MODERATORS' => $lang['Thereof_Moderators'],
		'L_NAME_MODERATORS' => $lang['Moderators'],
		'L_NUMBER_ADMINISTRATORS' => $lang['Thereof_Administrators'],
		'L_NAME_ADMINISTRATORS' => $lang['Administrators'],
		'L_NUMBER_LESS_ADMIN' => $lang['Thereof_Less_Administrators'],
		'L_NAME_LESS_ADMIN' => sprintf($lang['Super_Mod_online_color'], '', ''),
		'L_PHPBBFM_VERSION' => $lang['phpbbfm_version'],
		'L_PHPBB_VERSION' => $lang['phpbb_version'],
		'L_FORUM_STATS' => $lang['Forum_stats'],
		'L_FORUM_STATS_EXPLAIN' => $lang['Forum_stats_explain'],
		'L_WHO_IS_ONLINE' => $lang['Who_is_Online'],
		'L_USERNAME' => $lang['Username'],
		'L_LOCATION' => $lang['Location'],
		'L_LAST_UPDATE' => $lang['Last_updated'],
		'L_IP_ADDRESS' => $lang['IP_Address'],
		'L_STATISTIC' => $lang['Statistic'],
		'L_VALUE' => $lang['Value'],
		'L_NUMBER_POSTS' => $lang['Number_posts'],
		'L_POSTS_PER_DAY' => $lang['Posts_per_day'],
		'L_NUMBER_TOPICS' => $lang['Number_topics'],
		'L_TOPICS_PER_DAY' => $lang['Topics_per_day'],
		'L_NUMBER_PVT' => $lang['Number_pvt'],
		'L_PVT_PER_DAY' => $lang['Pvt_per_day'],
		'L_MAX_PVT_PER_USER' => $lang['Max_pvt_per_user'],
		'L_PVT_PER_USER' => $lang['Pvt_per_user'],
		'L_NUMBER_DATA_DISABLE' => $lang['Number_data_disable'],
 		'L_PERC_PVT' => $lang['Perc_pvt'],
		'L_NUMBER_USERS' => $lang['Number_users'],
		'L_NUMBER_ATTACHMENTS' => $lang['Number_of_attachments'],
		'L_ATTACHMENTS_PER_DAY' => $lang['Attachments_per_day'],
		'L_ATTACHMENT_DIR_SIZE' => $lang['Total_filesize'],
		'L_USERS_PER_DAY' => $lang['Users_per_day'],
		'L_NUMBER_FEMALE' => $lang['Number_females'],
		'L_NUMBER_MALE' => $lang['Number_males'],
		'L_GENDER_RATIO' => $lang['Gender_ratio'],
		'L_BOARD_STARTED' => $lang['Board_started'],
		'L_AVATAR_DIR_SIZE' => $lang['Avatar_dir_size'],
		'L_DB_SIZE' => $lang['Database_size'], 
		'L_SIZE_PVT_TABLES' => $lang['Size_pvt_tables'],
		'L_SIZE_POSTS_TABLES' => $lang['Size_posts_tables'],
		'L_SIZE_SEARCH_TABLES' => $lang['Size_search_tables'],
		'L_SIZE_USERS_TABLES' => $lang['Size_users_tables'],
		'L_TABLES' => $lang['Number_of_db_tables'],
		'L_FORUM_LOCATION' => $lang['Forum_Location'],
		'L_STARTED' => $lang['Login'],
		'L_LOOK_UP' => $lang['Look_up_User'])
	);


	//
	// Get users online information.
	//
	$sql = "SELECT u.user_id, u.username, u.user_level, u.user_session_time, u.user_session_page, s.session_logged_in, s.session_ip, s.session_start 
		FROM " . USERS_TABLE . " u, " . SESSIONS_TABLE . " s
		WHERE s.session_logged_in = " . TRUE . " 
			AND u.user_id = s.session_user_id 
			AND u.user_id <> " . ANONYMOUS . " 
			AND s.session_time >= " . ( time() - ($board_config['whosonline_time'] * 60) ) . " 
		ORDER BY u.user_session_time DESC";
	if(!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Couldn't obtain regd user/online information.", "", __LINE__, __FILE__, $sql);
	}
	$onlinerow_reg = $db->sql_fetchrowset($result);

	$sql = "SELECT session_page, session_logged_in, session_time, session_ip, session_start   
		FROM " . SESSIONS_TABLE . "
		WHERE session_logged_in = 0
			AND is_robot = 0
			AND session_time >= " . ( time() - ($board_config['whosonline_time'] * 60) ) . "
		ORDER BY session_time DESC";
	if(!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Couldn't obtain guest user/online information.", "", __LINE__, __FILE__, $sql);
	}
	$onlinerow_guest = $db->sql_fetchrowset($result);

	$sql = "SELECT session_page, session_logged_in, session_time, session_ip, session_start, is_robot   
		FROM " . SESSIONS_TABLE . "
		WHERE session_logged_in = 0
			AND is_robot != '0'
			AND session_time >= " . ( time() - ($board_config['whosonline_time'] * 60) ) . "
		GROUP BY is_robot
		ORDER BY session_time DESC";
	if(!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Couldn't obtain robot/online information.", "", __LINE__, __FILE__, $sql);
	}
	$onlinerow_spider = $db->sql_fetchrowset($result);

	$sql = "SELECT forum_name, forum_id
		FROM " . FORUMS_TABLE;
	if($forums_result = $db->sql_query($sql))
	{
		while($forumsrow = $db->sql_fetchrow($forums_result))
		{
			$forum_data[$forumsrow['forum_id']] = $forumsrow['forum_name'];
		}
	}
	else
	{
		message_die(GENERAL_ERROR, "Couldn't obtain user/online forums information.", "", __LINE__, __FILE__, $sql);
	}

	$reg_userid_ary = array();

	if( sizeof($onlinerow_reg) )
	{
		$registered_users = 0;

		for($i = 0; $i < sizeof($onlinerow_reg); $i++)
		{
			if( !inarray($onlinerow_reg[$i]['user_id'], $reg_userid_ary) )
			{
				$reg_userid_ary[] = $onlinerow_reg[$i]['user_id'];

				$username = username_level_color($onlinerow_reg[$i]['username'], $onlinerow_reg[$i]['user_level'], $onlinerow_reg[$i]['user_id']);

				if ( $onlinerow_reg[$i]['user_session_page'] == PAGE_ACTIVITY || $onlinerow_reg[$i]['user_session_page'] == PAGE_PLAYING_GAMES )
				{
					$username = '<b style="color: #' . str_replace('#', '', $board_config['ina_online_list_color']) . '">' . $onlinerow_reg[$i]['username'] . '</b>';
				}

				if( $onlinerow_reg[$i]['user_allow_viewonline'] || $userdata['user_level'] == ADMIN )
				{
					$registered_users++;
					$hidden = FALSE;
				}
				else
				{
					$hidden_users++;
					$hidden = TRUE;
				}

				if( $onlinerow_reg[$i]['user_session_page'] < 1 )
				{				
					switch($onlinerow_reg[$i]['user_session_page'])
					{
						case PAGE_INDEX:
							$location = $lang['Forum_Index'];
							$location_url = "../index.$phpEx";
							break;
						case PAGE_PORTAL:
							$location = $lang['Viewing_portal'];
							$location_url = "../portal.$phpEx";
							break;
						case PAGE_POSTING:
							$location = $lang['Posting_message'];
							$location_url = "index.$phpEx?pane=right";
							break;
						case PAGE_LOGIN:
							$location = $lang['Logging_on'];
							$location_url = "../login.$phpEx";
							break;
						case PAGE_SEARCH:
							$location = $lang['Searching_forums'];
							$location_url = "../search.$phpEx";
							break;
						case PAGE_PROFILE:
							$location = $lang['Viewing_profile'];
							$location_url = "index.$phpEx?pane=right";
							break;
						case PAGE_VIEWONLINE:
							$location = $lang['Viewing_online'];
							$location_url = "../viewonline.$phpEx";
							break;
						case PAGE_VIEWMEMBERS:
							$location = $lang['Viewing_member_list'];
							$location_url = "../memberlist.$phpEx";
							break;
						case PAGE_PRIVMSGS:
							$location = $lang['Viewing_priv_msgs'];
							$location_url = "index.$phpEx?pane=right";
							break;
						case PAGE_FAQ:
							$location = $lang['Viewing_FAQ'];
							$location_url = "../faq.$phpEx";
							break;
						case PAGE_SMILES:
							$location = $lang['Viewing_Smilies'];
							$location_url = "../smilies.$phpEx";
							break;
						case PAGE_TELL_FRIEND:
							$location = $lang['Viewing_Tell_Friend'];
							$location_url = "../tellafriend.$phpEx";
							break;
						case PAGE_LINKS:
							$location = $lang['Viewing_Links'];
							$location_url = "../linkdb.$phpEx";
							break;
						case PAGE_DOWNLOAD:
							$location = $lang['Viewing_Download'];
							$location_url = "../dload.$phpEx";
							break;  
						case PAGE_TOPIC_VIEW:
							$location = $lang['Viewing_topic_views'];
							$location_url = "../memberlist.$phpEx";
							break;
						case PAGE_TOPICS_STARTED:
							$location = $lang['Viewing_topics_started'];
							$location_url = "../topics.$phpEx";
							break;
						case PAGE_STAFF:
							$location = $lang['Viewing_staff'];
							$location_url = "../staff.$phpEx";
							break;
						case PAGE_ALBUM:
							$location = $lang['Viewing_album'];
							$location_url = "../album.$phpEx";
							break;
						case PAGE_ALBUM_PERSONAL:
							$location = $lang['Viewing_album_personal'];
							$location_url = "../album_personal_index.$phpEx";
							break;
						case PAGE_ALBUM_PICTURE:
							$location = $lang['Viewing_album_pic'];
							$location_url = "../album.$phpEx";
							break;
						case PAGE_ALBUM_SEARCH:
							$location = $lang['Searching_album'];
							$location_url = "../album_search.$phpEx";
							break;
	            		case PAGE_ALBUM_RSS: 
            			   	$location = $lang['Viewing_RSS']; 
            			   	$location_url = "album_rss.$phpEx"; 
            			   	break; 
						case PAGE_ATTACHMENTS:
							$location = $lang['Viewing_attachments'];
							$location_url = "../attachments.$phpEx";
							break;
						case PAGE_STATISTICS:
							$location = $lang['Viewing_stats'];
							$location_url = "../statistics.$phpEx";
							break;
						case PAGE_TRANSACTIONS:
							$location = $lang['Global_Trans'];
							$location_url = "../transactions.$phpEx";
							break;				
						case PAGE_CALENDAR:
							$location = $lang['Viewing_calendar'];
							$location_url = "../calendar.$phpEx";
							break;
						case PAGE_BANK:
							$location = $lang['Viewing_bank'];
							$location_url = "../bank.$phpEx";
							break;
						case PAGE_SHOP:
							$location = $lang['Viewing_shop'];
							$location_url = "../shop.$phpEx";
							break;
						case PAGE_RATINGS:
							$location = $lang['Viewing_ratings'];
							$location_url = "../ratings.$phpEx";
							break;
						case PAGE_CHATROOM:
							$location = $lang['Viewing_chatroom'];
							$location_url = "../chatroom.$phpEx";
							break;
						case PAGE_IMLIST:
							$location = $lang['Viewing_IM_list'];
							$location_url = "../imlist.$phpEx";
							break;
						case PAGE_TOPLIST:
							$location = $lang['Viewing_toplist'];
							$location_url = "../toplist.$phpEx";
							break;
						case PAGE_LOTTERY:
							$location = $lang['Viewing_lottery'];
							$location_url = "../lottery.$phpEx";
							break;
						case PAGE_CHARTS:
							$location = $lang['Viewing_charts'];
							$location_url = "../charts.php?action=list";
							break;
						case PAGE_BANLIST:
							$location = $lang['Viewing_banlist'];
							$location_url = "../banlist.$phpEx";
							break;
						case PAGE_KB:
							$location = $lang['Viewing_KB'];
							$location_url = "../kb.$phpEx";
							break;
						case PAGE_SHOUTBOX:
						case PAGE_SHOUTBOX_MAX:
							$location = $lang['Viewing_Shoutbox'];
							$location_url = "../shoutbox_max.$phpEx";
							break;
						case PAGE_REDIRECT: 
							if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_banner.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_banner.' . $phpEx);	
							if ($row['session_topic'])
							{
								$sql = "SELECT banner_description 
									FROM " . BANNERS_TABLE . " 
									WHERE banner_id = " . $row['session_topic'];
								
								if ( $result2 = $db->sql_query($sql) )
								{
									$banner_data = $db->sql_fetchrow($result2);
								}
								else
								{	
									message_die(GENERAL_ERROR, 'Could not obtain redirect online information', '', __LINE__, __FILE__, $sql);
								}
								$location_url = append_sid("redirect.$phpEx?banner_id=" . $row['session_topic']);
								$location = $lang['Left_via_banner'] .' --> '.$banner_data['banner_description'];
							} 
							else
							{
								$location_url = "index.$phpEx?pane=right";
								$location = $lang['Left_via_banner'];
							}
							break;
						case PAGE_PRILLIAN:
							if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_prillian.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_prillian.' . $phpEx);
							$location = $lang['Prillian'];
							$location_url = "index.$phpEx?pane=right";
							break;
						case PAGE_CONTACT:
							if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_contact.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_contact.' . $phpEx);
							$location = $lang['Contact_Management'];
							$location_url = "index.$phpEx?pane=right";
							break;
						case PAGE_MEETING:
							$location = $lang['Meeting'];
							$location_url = "../meeting.$phpEx";
							break;
						case PAGE_HELPDESK:
							$location = $lang['Viewing_helpdesk'];
							$location_url = "../helpdesk.$phpEx";
							break;
						case PAGE_ACTIVITY:
							$location = $lang['Activity'];
							$location_url = "../activity.$phpEx";
							break;
						case PAGE_PLAYING_GAMES:
							$location = $lang['Activity'];
							$location_url = "../activity.$phpEx";
							break;
						case PAGE_BOOKIES:
							$location = $lang['bookies'];
							$location_url = "../bookies.$phpEx";
							break;
						case PAGE_BOOKIE_YOURSTATS:
							$location = $lang['bookie_yourstats'];
							$location_url = "../bookie_yourstats.$phpEx";
							break;
						case PAGE_BOOKIE_ALLSTATS:
							$location = $lang['bookie_allstats'];
							$location_url = "../bookie_allstats.$phpEx";
							break;
						case PAGE_LEXICON:
							$location = sprintf($lang['Viewing_Lexicon'], $board_config['lexicon_title']);
							$location_url = "../lexicon.$phpEx";
							break;
						case PAGE_SITEMAP:
							$location = $lang['Sitemap_viewing'];
							$location_url = "../sitemap.$phpEx";
							break;
						case PAGE_AUCTIONS:
							if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_auctions.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_auctions.' . $phpEx);
							$location = $lang['Auctions_viewing'];
							$location_url = "../auctions.$phpEx";
							break;
        		 	   case PAGE_RSS: 
        		 	      	$location = $lang['Viewing_RSS']; 
        		 	      	$location_url = "../rss.$phpEx"; 
        		 	      	break; 
						case PAGE_JOBS:
							$location = $lang['Viewing_jobs'];
							$location_url = "../jobs.$phpEx";
							break;
						case PAGE_AVATAR_TOPLIST:
							$location = $lang['Viewing_avatar_toplist'];
							$location_url = "../avatarsuite_toplist.$phpEx";
							break;
						case PAGE_AVATAR_LIST:
							$location = $lang['Viewing_avatar_list'];
							$location_url = "../avatarsuite_listavatarts.$phpEx";
							break;
						case PAGE_GUESTBOOK:
							$location = $lang['Viewing_guestbook'];
							$location_url = "../guestbook.$phpEx";
							break;
						case PAGE_MEDALS:
							$location = $lang['Medals'];
							$location_url = "../medals.$phpEx";
							break;
					// Fully Modded site specific only!
						case PAGE_FMINDEX:
							$location = $lang['FM_Index'];
							$location_url = "../index_fm.$phpEx";
							break;
				 		default:
							$location = $lang['Forum_Index'];
							$location_url = "../index.$phpEx";
					}
				}
				else
				{
					$location_url = append_sid("admin_forums.$phpEx?mode=editforum&amp;" . POST_FORUM_URL . "=" . $onlinerow_reg[$i]['user_session_page']);
					$location = $forum_data[$onlinerow_reg[$i]['user_session_page']];
				}

				$row_class = ( $registered_users % 2 ) ? $theme['td_class1'] : $theme['td_class2'];

				$reg_ip = decode_ip($onlinerow_reg[$i]['session_ip']);

				$template->assign_block_vars("reg_user_row", array(
					"ROW_CLASS" => $row_class,
					"USERNAME" => $username, 
					"STARTED" => create_date($board_config['default_dateformat'], $onlinerow_reg[$i]['session_start'], $board_config['board_timezone']), 
					"LASTUPDATE" => create_date($board_config['default_dateformat'], $onlinerow_reg[$i]['user_session_time'], $board_config['board_timezone']),
					"FORUM_LOCATION" => $location,
					"IP_ADDRESS" => $reg_ip, 

					"U_WHOIS_IP" => 'http://network-tools.com/default.asp?prog=trace&amp;host=' . $reg_ip, 
					"U_USER_PROFILE" => append_sid("admin_users.$phpEx?mode=edit&amp;" . POST_USERS_URL . "=" . $onlinerow_reg[$i]['user_id']),
					"U_FORUM_LOCATION" => append_sid($location_url))
				);
			}
		}
	}
	else
	{
		$template->assign_vars(array(
			"L_NO_REGISTERED_USERS_BROWSING" => $lang['No_users_browsing'])
		);
	}

	//
	// Guest users
	//
	if( sizeof($onlinerow_guest) )
	{
		$guest_users = 0;

		for($i = 0; $i < sizeof($onlinerow_guest); $i++)
		{
			$guest_userip_ary[] = $onlinerow_guest[$i]['session_ip'];
			$guest_users++;

			if( $onlinerow_guest[$i]['session_page'] < 1 )
			{		
				switch( $onlinerow_guest[$i]['session_page'] )
				{
					case PAGE_INDEX:
						$location = $lang['Forum_Index'];
						$location_url = "../index.$phpEx";
						break;
					case PAGE_PORTAL:
						$location = $lang['Viewing_portal'];
						$location_url = "../portal.$phpEx";
						break;
					case PAGE_POSTING:
						$location = $lang['Posting_message'];
						$location_url = "index.$phpEx?pane=right";
						break;
					case PAGE_LOGIN:
						$location = $lang['Logging_on'];
						$location_url = "../login.$phpEx";
						break;
					case PAGE_SEARCH:
						$location = $lang['Searching_forums'];
						$location_url = "../search.$phpEx";
						break;
					case PAGE_PROFILE:
						$location = $lang['Viewing_profile'];
						$location_url = "index.$phpEx?pane=right";
						break;
					case PAGE_VIEWONLINE:
						$location = $lang['Viewing_online'];
						$location_url = "../viewonline.$phpEx";
						break;
					case PAGE_VIEWMEMBERS:
						$location = $lang['Viewing_member_list'];
						$location_url = "../memberlist.$phpEx";
						break;
					case PAGE_PRIVMSGS:
						$location = $lang['Viewing_priv_msgs'];
						$location_url = "index.$phpEx?pane=right";
						break;
					case PAGE_FAQ:
						$location = $lang['Viewing_FAQ'];
						$location_url = "../faq.$phpEx";
						break;
					case PAGE_SMILES:
						$location = $lang['Viewing_Smilies'];
						$location_url = "../smilies.$phpEx";
						break;
					case PAGE_TELL_FRIEND:
						$location = $lang['Viewing_Tell_Friend'];
						$location_url = "../tellafriend.$phpEx";
						break;
					case PAGE_LINKS:
						$location = $lang['Viewing_Links'];
						$location_url = "../linkdb.$phpEx";
						break;
					case PAGE_DOWNLOAD:
						$location = $lang['Viewing_Download'];
						$location_url = "../dload.$phpEx";
						break; 
					case PAGE_TOPIC_VIEW:
						$location = $lang['Viewing_topic_views'];
						$location_url = "../memberlist.$phpEx";
						break;
					case PAGE_TOPICS_STARTED:
						$location = $lang['Viewing_topics_started'];
						$location_url = "../topics.$phpEx";
						break;
					case PAGE_STAFF:
						$location = $lang['Viewing_staff'];
						$location_url = "../staff.$phpEx";
						break;
					case PAGE_ALBUM:
						$location = $lang['Viewing_album'];
						$location_url = "../album.$phpEx";
						break;
					case PAGE_ALBUM_PERSONAL:
						$location = $lang['Viewing_album_personal'];
						$location_url = "../album_personal_index.$phpEx";
						break;
					case PAGE_ALBUM_PICTURE:
						$location = $lang['Viewing_album_pic'];
						$location_url = "../album.$phpEx";
						break;
					case PAGE_ALBUM_SEARCH:
						$location = $lang['Searching_album'];
						$location_url = "../album_search.$phpEx";
						break;
					case PAGE_ALBUM_RSS: 
            	   		$location = $lang['Viewing_RSS']; 
            	   		$location_url = "album_rss.$phpEx"; 
            	   		break; 
					case PAGE_ATTACHMENTS:
						$location = $lang['Viewing_attachments'];
						$location_url = "../attachments.$phpEx";
						break;
					case PAGE_STATISTICS:
						$location = $lang['Viewing_stats'];
						$location_url = "../statistics.$phpEx";
						break;
					case PAGE_TRANSACTIONS:
						$location = $lang['Global_Trans'];
						$location_url = "../transactions.$phpEx";
						break;				
					case PAGE_CALENDAR:
						$location = $lang['Viewing_calendar'];
						$location_url = "../calendar.$phpEx";
						break;
					case PAGE_BANK:
						$location = $lang['Viewing_bank'];
						$location_url = "../bank.$phpEx";
						break;
					case PAGE_SHOP:
						$location = $lang['Viewing_shop'];
						$location_url = "../shop.$phpEx";
						break;
					case PAGE_RATINGS:
						$location = $lang['Viewing_ratings'];
						$location_url = "../ratings.$phpEx";
						break;
					case PAGE_CHATROOM:
						$location = $lang['Viewing_chatroom'];
						$location_url = "../chatroom.$phpEx";
						break;
					case PAGE_IMLIST:
						$location = $lang['Viewing_IM_list'];
						$location_url = "../imlist.$phpEx";
						break;
					case PAGE_TOPLIST:
						$location = $lang['Viewing_toplist'];
						$location_url = "../toplist.$phpEx";
						break;
					case PAGE_LOTTERY:
						$location = $lang['Viewing_lottery'];
						$location_url = "../lottery.$phpEx";
						break;
					case PAGE_CHARTS:
						$location = $lang['Viewing_charts'];
						$location_url = "../charts.php?action=list";
						break;
					case PAGE_BANLIST:
						$location = $lang['Viewing_banlist'];
						$location_url = "../banlist.$phpEx";
						break;
					case PAGE_KB:
						$location = $lang['Viewing_KB'];
						$location_url = "../kb.$phpEx";
						break;
					case PAGE_SHOUTBOX:
					case PAGE_SHOUTBOX_MAX:
						$location = $lang['Viewing_Shoutbox'];
						$location_url = "../shoutbox_max.$phpEx";
						break;
					case PAGE_REDIRECT: 
						if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_banner.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_banner.' . $phpEx);	
						if ($row['session_topic'])
						{
							$sql = "SELECT banner_description 
								FROM " . BANNERS_TABLE . " 
								WHERE banner_id = " . $row['session_topic'];
							
							if ( $result2 = $db->sql_query($sql) )
							{
								$banner_data = $db->sql_fetchrow($result2);
							}
							else
							{	
								message_die(GENERAL_ERROR, 'Could not obtain redirect online information', '', __LINE__, __FILE__, $sql);
							}
							$location_url = append_sid("redirect.$phpEx?banner_id=" . $row['session_topic']);
							$location = $lang['Left_via_banner'] .' --> '.$banner_data['banner_description'];
						} 
						else
						{
							$location_url = "index.$phpEx?pane=right";
							$location = $lang['Left_via_banner'];
						}
						break;
					case PAGE_PRILLIAN:
						if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_prillian.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_prillian.' . $phpEx);
						$location = $lang['Prillian'];
						$location_url = "index.$phpEx?pane=right";
						break;
					case PAGE_CONTACT:
						if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_contact.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_contact.' . $phpEx);
						$location = $lang['Contact_Management'];
						$location_url = "index.$phpEx?pane=right";
						break;
					case PAGE_MEETING:
						$location = $lang['Meeting'];
						$location_url = "../meeting.$phpEx";
						break;
					case PAGE_HELPDESK:
						$location = $lang['Viewing_helpdesk'];
						$location_url = "../helpdesk.$phpEx";
						break;
					case PAGE_ACTIVITY:
						$location = $lang['Activity'];
						$location_url = "../activity.$phpEx";
						break;
					case PAGE_PLAYING_GAMES:
						$location = $lang['Activity'];
						$location_url = "../activity.$phpEx";
						break;
					case PAGE_BOOKIES:
						$location = $lang['bookies'];
						$location_url = "../bookies.$phpEx";
						break;
					case PAGE_BOOKIE_YOURSTATS:
						$location = $lang['bookie_yourstats'];
						$location_url = "../bookie_yourstats.$phpEx";
						break;
					case PAGE_BOOKIE_ALLSTATS:
						$location = $lang['bookie_allstats'];
						$location_url = "../bookie_allstats.$phpEx";
						break;
					case PAGE_LEXICON:
						$location = sprintf($lang['Viewing_Lexicon'], $board_config['lexicon_title']);
						$location_url = "../lexicon.$phpEx";
						break;
					case PAGE_SITEMAP:
						$location = $lang['Sitemap_viewing'];
						$location_url = "../sitemap.$phpEx";
						break;
					case PAGE_AUCTIONS:
						if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_auctions.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_auctions.' . $phpEx);
						$location = $lang['Auctions_viewing'];
						$location_url = "../auctions.$phpEx";
						break;
        		    case PAGE_RSS: 
        		       	$location = $lang['Viewing_RSS']; 
        		       	$location_url = "../rss.$phpEx"; 
        		       	break; 
					case PAGE_JOBS:
						$location = $lang['Viewing_jobs'];
						$location_url = "../jobs.$phpEx";
						break;
					case PAGE_AVATAR_TOPLIST:
						$location = $lang['Viewing_avatar_toplist'];
						$location_url = "../avatarsuite_toplist.$phpEx";
						break;
					case PAGE_AVATAR_LIST:
						$location = $lang['Viewing_avatar_list'];
						$location_url = "../avatarsuite_listavatarts.$phpEx";
						break;
					case PAGE_GUESTBOOK:
						$location = $lang['Viewing_guestbook'];
						$location_url = "../guestbook.$phpEx";
						break;
					case PAGE_MEDALS:
						$location = $lang['Medals'];
						$location_url = "../medals.$phpEx";
						break;
					// Fully Modded site specific only!
					case PAGE_FMINDEX:
						$location = $lang['FM_Index'];
						$location_url = "../index_fm.$phpEx";
						break;
					default:
						$location = $lang['Forum_Index'];
						$location_url = "../index.$phpEx";
				}
			}
			else
			{
				$location_url = append_sid("admin_forums.$phpEx?mode=editforum&amp;" . POST_FORUM_URL . "=" . $onlinerow_guest[$i]['session_page']);
				$location = $forum_data[$onlinerow_guest[$i]['session_page']];
			}

			$row_class = ( $guest_users % 2 ) ? $theme['td_class1'] : $theme['td_class2'];

			$guest_ip = decode_ip($onlinerow_guest[$i]['session_ip']);
			$guest_ip_lookup = "admin_guest_ip_lookup.$phpEx?ip=" . decode_ip($onlinerow_guest[$i]['session_ip']);

			$template->assign_block_vars("guest_user_row", array(
				"ROW_CLASS" => $row_class,
				"USERNAME" => $lang['Guest'],
				"STARTED" => create_date($board_config['default_dateformat'], $onlinerow_guest[$i]['session_start'], $board_config['board_timezone']), 
				"LASTUPDATE" => create_date($board_config['default_dateformat'], $onlinerow_guest[$i]['session_time'], $board_config['board_timezone']),
				"FORUM_LOCATION" => $location,
				"IP_ADDRESS" => $guest_ip, 

				"L_GUEST_IP_LOOKUP" => $lang['Poster'],

				"U_WHOIS_IP" => "http://network-tools.com/default.asp?prog=trace&amp;host=$guest_ip", 
				"U_GUEST_IP_LOOKUP" => append_sid($guest_ip_lookup),
				"U_FORUM_LOCATION" => append_sid($location_url))
			);
		}
	}
	else
	{
		$template->assign_vars(array(
			"L_NO_GUESTS_BROWSING" => $lang['No_users_browsing'])
		);
	}

	//
	// Search engine spiders 
	//
	if ( sizeof($onlinerow_spider) )
	{
		$spider_users = 0;

		for($i = 0; $i < sizeof($onlinerow_spider); $i++)
		{
			$spider_userip_ary[] = $onlinerow_spider[$i]['session_ip'];
			$spider_users++;

			if( $onlinerow_spider[$i]['session_page'] < 1 )
			{
				switch( $onlinerow_spider[$i]['session_page'] )
				{
					case PAGE_INDEX:
						$location = $lang['Forum_Index'];
						$location_url = "../index.$phpEx";
						break;
					case PAGE_PORTAL:
						$location = $lang['Viewing_portal'];
						$location_url = "../portal.$phpEx";
						break;
					case PAGE_POSTING:
						$location = $lang['Posting_message'];
						$location_url = "index.$phpEx?pane=right";
						break;
					case PAGE_LOGIN:
						$location = $lang['Logging_on'];
						$location_url = "../login.$phpEx";
						break;
					case PAGE_SEARCH:
						$location = $lang['Searching_forums'];
						$location_url = "../search.$phpEx";
						break;
					case PAGE_PROFILE:
						$location = $lang['Viewing_profile'];
						$location_url = "index.$phpEx?pane=right";
						break;
					case PAGE_VIEWONLINE:
						$location = $lang['Viewing_online'];
						$location_url = "../viewonline.$phpEx";
						break;
					case PAGE_VIEWMEMBERS:
						$location = $lang['Viewing_member_list'];
						$location_url = "../memberlist.$phpEx";
						break;
					case PAGE_PRIVMSGS:
						$location = $lang['Viewing_priv_msgs'];
						$location_url = "index.$phpEx?pane=right";
						break;
					case PAGE_FAQ:
						$location = $lang['Viewing_FAQ'];
						$location_url = "../faq.$phpEx";
						break;
					case PAGE_SMILES:
						$location = $lang['Viewing_Smilies'];
						$location_url = "../smilies.$phpEx";
						break;
					case PAGE_TELL_FRIEND:
						$location = $lang['Viewing_Tell_Friend'];
						$location_url = "../tellafriend.$phpEx";
						break;
					case PAGE_LINKS:
						$location = $lang['Viewing_Links'];
						$location_url = "../linkdb.$phpEx";
						break;
					case PAGE_DOWNLOAD:
						$location = $lang['Viewing_Download'];
						$location_url = "../dload.$phpEx";
						break; 
					case PAGE_TOPIC_VIEW:
						$location = $lang['Viewing_topic_views'];
						$location_url = "../memberlist.$phpEx";
						break;
					case PAGE_TOPICS_STARTED:
						$location = $lang['Viewing_topics_started'];
						$location_url = "../topics.$phpEx";
						break;
					case PAGE_STAFF:
						$location = $lang['Viewing_staff'];
						$location_url = "../staff.$phpEx";
						break;
					case PAGE_ALBUM:
						$location = $lang['Viewing_album'];
						$location_url = "../album.$phpEx";
						break;
					case PAGE_ALBUM_PERSONAL:
						$location = $lang['Viewing_album_personal'];
						$location_url = "../album_personal_index.$phpEx";
						break;
					case PAGE_ALBUM_PICTURE:
						$location = $lang['Viewing_album_pic'];
						$location_url = "../album.$phpEx";
						break;
					case PAGE_ALBUM_SEARCH:
						$location = $lang['Searching_album'];
						$location_url = "../album_search.$phpEx";
						break;
					case PAGE_ALBUM_RSS: 
            	   		$location = $lang['Viewing_RSS']; 
            	   		$location_url = "album_rss.$phpEx"; 
            	   		break; 
					case PAGE_ATTACHMENTS:
						$location = $lang['Viewing_attachments'];
						$location_url = "../attachments.$phpEx";
						break;
					case PAGE_STATISTICS:
						$location = $lang['Viewing_stats'];
						$location_url = "../statistics.$phpEx";
						break;
					case PAGE_TRANSACTIONS:
						$location = $lang['Global_Trans'];
						$location_url = "../transactions.$phpEx";
						break;				
					case PAGE_CALENDAR:
						$location = $lang['Viewing_calendar'];
						$location_url = "../calendar.$phpEx";
						break;
					case PAGE_BANK:
						$location = $lang['Viewing_bank'];
						$location_url = "../bank.$phpEx";
						break;
					case PAGE_SHOP:
						$location = $lang['Viewing_shop'];
						$location_url = "../shop.$phpEx";
						break;
					case PAGE_RATINGS:
						$location = $lang['Viewing_ratings'];
						$location_url = "../ratings.$phpEx";
						break;
					case PAGE_CHATROOM:
						$location = $lang['Viewing_chatroom'];
						$location_url = "../chatroom.$phpEx";
						break;
					case PAGE_IMLIST:
						$location = $lang['Viewing_IM_list'];
						$location_url = "../imlist.$phpEx";
						break;
					case PAGE_TOPLIST:
						$location = $lang['Viewing_toplist'];
						$location_url = "../toplist.$phpEx";
						break;
					case PAGE_LOTTERY:
						$location = $lang['Viewing_lottery'];
						$location_url = "../lottery.$phpEx";
						break;
					case PAGE_CHARTS:
						$location = $lang['Viewing_charts'];
						$location_url = "../charts.php?action=list";
						break;
					case PAGE_BANLIST:
						$location = $lang['Viewing_banlist'];
						$location_url = "../banlist.$phpEx";
						break;
					case PAGE_KB:
						$location = $lang['Viewing_KB'];
						$location_url = "../kb.$phpEx";
						break;
					case PAGE_SHOUTBOX:
					case PAGE_SHOUTBOX_MAX:
						$location = $lang['Viewing_Shoutbox'];
						$location_url = "../shoutbox_max.$phpEx";
						break;
					case PAGE_REDIRECT: 
						if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_banner.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_banner.' . $phpEx);	
						if ($row['session_topic'])
						{
							$sql = "SELECT banner_description 
								FROM " . BANNERS_TABLE . " 
								WHERE banner_id = " . $row['session_topic'];
							
							if ( $result2 = $db->sql_query($sql) )
							{
								$banner_data = $db->sql_fetchrow($result2);
							}
							else
							{	
								message_die(GENERAL_ERROR, 'Could not obtain redirect online information', '', __LINE__, __FILE__, $sql);
							}
							$location_url = append_sid("redirect.$phpEx?banner_id=" . $row['session_topic']);
							$location = $lang['Left_via_banner'] .' --> '.$banner_data['banner_description'];
						} 
						else
						{
							$location_url = "index.$phpEx?pane=right";
							$location = $lang['Left_via_banner'];
						}
						break;
					case PAGE_PRILLIAN:
						if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_prillian.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_prillian.' . $phpEx);
						$location = $lang['Prillian'];
						$location_url = "index.$phpEx?pane=right";
						break;
					case PAGE_CONTACT:
						if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_contact.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_contact.' . $phpEx);
						$location = $lang['Contact_Management'];
						$location_url = "index.$phpEx?pane=right";
						break;
					case PAGE_MEETING:
						$location = $lang['Meeting'];
						$location_url = "../meeting.$phpEx";
						break;
					case PAGE_HELPDESK:
						$location = $lang['Viewing_helpdesk'];
						$location_url = "../helpdesk.$phpEx";
						break;
					case PAGE_ACTIVITY:
						$location = $lang['Activity'];
						$location_url = "../activity.$phpEx";
						break;
					case PAGE_PLAYING_GAMES:
						$location = $lang['Activity'];
						$location_url = "../activity.$phpEx";
						break;
					case PAGE_BOOKIES:
						$location = $lang['bookies'];
						$location_url = "../bookies.$phpEx";
						break;
					case PAGE_BOOKIE_YOURSTATS:
						$location = $lang['bookie_yourstats'];
						$location_url = "../bookie_yourstats.$phpEx";
						break;
					case PAGE_BOOKIE_ALLSTATS:
						$location = $lang['bookie_allstats'];
						$location_url = "../bookie_allstats.$phpEx";
						break;
					case PAGE_LEXICON:
						$location = sprintf($lang['Viewing_Lexicon'], $board_config['lexicon_title']);
						$location_url = "../lexicon.$phpEx";
						break;
					case PAGE_SITEMAP:
						$location = $lang['Sitemap_viewing'];
						$location_url = "../sitemap.$phpEx";
						break;
					case PAGE_AUCTIONS:
						if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_auctions.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_auctions.' . $phpEx);
						$location = $lang['Auctions_viewing'];
						$location_url = "../auctions.$phpEx";
						break;
        		    case PAGE_RSS: 
        		       	$location = $lang['Viewing_RSS']; 
        		       	$location_url = "../rss.$phpEx"; 
        		       	break; 
					case PAGE_JOBS:
						$location = $lang['Viewing_jobs'];
						$location_url = "../jobs.$phpEx";
						break;
					case PAGE_AVATAR_TOPLIST:
						$location = $lang['Viewing_avatar_toplist'];
						$location_url = "../avatarsuite_toplist.$phpEx";
						break;
					case PAGE_AVATAR_LIST:
						$location = $lang['Viewing_avatar_list'];
						$location_url = "../avatarsuite_listavatarts.$phpEx";
						break;
					case PAGE_GUESTBOOK:
						$location = $lang['Viewing_guestbook'];
						$location_url = "../guestbook.$phpEx";
						break;
					case PAGE_MEDALS:
						$location = $lang['Medals'];
						$location_url = "../medals.$phpEx";
						break;
				// Fully Modded site specific only!
					case PAGE_BB:
						$location = $lang['BB_Viewing'];
						$location_url = "../bb_index.$phpEx";
						break;
					case PAGE_FMINDEX:
						$location = $lang['FM_Index'];
						$location_url = "../index_fm.$phpEx";
						break;
					default:
						$location = $lang['Forum_Index'];
						$location_url = "../index.$phpEx";
				}
			}
			else
			{
				$location_url = append_sid("admin_forums.$phpEx?mode=editforum&amp;" . POST_FORUM_URL . "=" . $onlinerow_spider[$i]['session_page']);
				$location = $forum_data[$onlinerow_spider[$i]['session_page']];
			}

			$row_class = ( $spider_users % 2 ) ? $theme['td_class1'] : $theme['td_class2'];

			$spider_ip = decode_ip($onlinerow_spider[$i]['session_ip']);

			$template->assign_block_vars("spider_user_row", array(
				"ROW_CLASS" => $row_class,
				"USERNAME" => '<b style="color: #' . $theme['botfontcolor'] . '">' . $onlinerow_spider[$i]['is_robot'] . '</b>',
				"STARTED" => create_date($board_config['default_dateformat'], $onlinerow_spider[$i]['session_start'], $board_config['board_timezone']), 
				"LASTUPDATE" => create_date($board_config['default_dateformat'], $onlinerow_spider[$i]['session_time'], $board_config['board_timezone']),
				"FORUM_LOCATION" => $location,
				"IP_ADDRESS" => $spider_ip, 

				"U_WHOIS_IP" => "http://network-tools.com/default.asp?prog=trace&amp;host=$spider_ip", 
				"U_FORUM_LOCATION" => append_sid($location_url))
			);
		}

	}
	else
	{
		$template->assign_vars(array(
			"L_NO_SPIDERS_BROWSING" => $lang['No_users_browsing'])
		);
	}
	
	$template->pparse("body");

	include('./page_footer_admin.'.$phpEx);

?>