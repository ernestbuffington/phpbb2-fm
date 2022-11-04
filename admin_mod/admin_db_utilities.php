<?php
/** 
*
* @package admin_mod
* @version $Id: admin_db_utilities.php,v 1.42.2.10 2003/03/04 21:02:19 acydburn Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

/*
*	We will attempt to create a file based backup of all of the data in the
*	users phpBB database.  The resulting file should be able to be imported by
*	the db_restore.php function, or by using the mysql command_line
*
*	Some functions are adapted from the upgrade_20.php script and others
*	adapted from the unofficial phpMyAdmin 2.2.0.
*/

define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	$module['Utilities_']['Database_Backup'] = $filename . "?perform=backup";

	return;
}

//
// Load default header
//
$no_page_header = TRUE;
$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);
include($phpbb_root_path . 'includes/sql_parse.'.$phpEx);

// Module Activation
if ($board_config['enable_module_backup'])
{

//
// Set VERBOSE to 1  for debugging info..
//
define("VERBOSE", 0);

//
// Increase maximum execution time, but don't complain about it if it isn't
// allowed.
//
@set_time_limit(1200);

// -----------------------
// The following functions are adapted from phpMyAdmin and upgrade_20.php
//
function gzip_PrintFourChars($Val)
{
	for ($i = 0; $i < 4; $i ++)
	{
		$return .= chr($Val % 256);
		$Val = floor($Val / 256);
	}
	return $return;
} 



//
// This function is used for grabbing the sequences for postgres...
//
function pg_get_sequences($crlf, $backup_type)
{
	global $db;

	$get_seq_sql = "SELECT relname FROM pg_class WHERE NOT relname ~ 'pg_.*'
		AND relkind = 'S' ORDER BY relname";

	$seq = $db->sql_query($get_seq_sql);

	if( !$num_seq = $db->sql_numrows($seq) )
	{

		$return_val = "# No Sequences Found $crlf";

	}
	else
	{
		$return_val = "# Sequences $crlf";
		$i_seq = 0;

		while($i_seq < $num_seq)
		{
			$row = $db->sql_fetchrow($seq);
			$sequence = $row['relname'];

			$get_props_sql = "SELECT * FROM $sequence";
			$seq_props = $db->sql_query($get_props_sql);

			if($db->sql_numrows($seq_props) > 0)
			{
				$row1 = $db->sql_fetchrow($seq_props);

				if($backup_type == 'structure')
				{
					$row['last_value'] = 1;
				}

				$return_val .= "CREATE SEQUENCE $sequence start " . $row['last_value'] . ' increment ' . $row['increment_by'] . ' maxvalue ' . $row['max_value'] . ' minvalue ' . $row['min_value'] . ' cache ' . $row['cache_value'] . "; $crlf";

			}  // End if numrows > 0

			if(($row['last_value'] > 1) && ($backup_type != 'structure'))
			{
				$return_val .= "SELECT NEXTVALE('$sequence'); $crlf";
				unset($row['last_value']);
			}

			$i_seq++;

		} // End while..

	} // End else...

	return $returnval;

} // End function...

//
// The following functions will return the "CREATE TABLE syntax for the
// varying DBMS's
//
// This function returns, will return the table def's for postgres...
//
function get_table_def_postgresql($table, $crlf)
{
	global $drop, $db;

	$schema_create = "";
	//
	// Get a listing of the fields, with their associated types, etc.
	//

	$field_query = "SELECT a.attnum, a.attname AS field, t.typname as type, a.attlen AS length, a.atttypmod as lengthvar, a.attnotnull as notnull
		FROM pg_class c, pg_attribute a, pg_type t
		WHERE c.relname = '$table'
			AND a.attnum > 0
			AND a.attrelid = c.oid
			AND a.atttypid = t.oid
		ORDER BY a.attnum";
	$result = $db->sql_query($field_query);

	if(!$result)
	{
		message_die(GENERAL_ERROR, "Failed in get_table_def (show fields)", "", __LINE__, __FILE__, $field_query);
	} // end if..

	if ($drop == 1)
	{
		$schema_create .= "DROP TABLE $table;$crlf";
	} // end if

	//
	// Ok now we actually start building the SQL statements to restore the tables
	//

	$schema_create .= "CREATE TABLE $table($crlf";

	while ($row = $db->sql_fetchrow($result))
	{
		//
		// Get the data from the table
		//
		$sql_get_default = "SELECT d.adsrc AS rowdefault
			FROM pg_attrdef d, pg_class c
			WHERE (c.relname = '$table')
				AND (c.oid = d.adrelid)
				AND d.adnum = " . $row['attnum'];
		$def_res = $db->sql_query($sql_get_default);

		if (!$def_res)
		{
			unset($row['rowdefault']);
		}
		else
		{
			$row['rowdefault'] = @pg_result($def_res, 0, 'rowdefault');
		}

		if ($row['type'] == 'bpchar')
		{
			// Internally stored as bpchar, but isn't accepted in a CREATE TABLE statement.
			$row['type'] = 'char';
		}

		$schema_create .= '	' . $row['field'] . ' ' . $row['type'];

		if (eregi('char', $row['type']))
		{
			if ($row['lengthvar'] > 0)
			{
				$schema_create .= '(' . ($row['lengthvar'] -4) . ')';
			}
		}

		if (eregi('numeric', $row['type']))
		{
			$schema_create .= '(';
			$schema_create .= sprintf("%s,%s", (($row['lengthvar'] >> 16) & 0xffff), (($row['lengthvar'] - 4) & 0xffff));
			$schema_create .= ')';
		}

		if (!empty($row['rowdefault']))
		{
			$schema_create .= ' DEFAULT ' . $row['rowdefault'];
		}

		if ($row['notnull'] == 't')
		{
			$schema_create .= ' NOT NULL';
		}

		$schema_create .= ",$crlf";

	}
	//
	// Get the listing of primary keys.
	//

	$sql_pri_keys = "SELECT ic.relname AS index_name, bc.relname AS tab_name, ta.attname AS column_name, i.indisunique AS unique_key, i.indisprimary AS primary_key
		FROM pg_class bc, pg_class ic, pg_index i, pg_attribute ta, pg_attribute ia
		WHERE (bc.oid = i.indrelid)
			AND (ic.oid = i.indexrelid)
			AND (ia.attrelid = i.indexrelid)
			AND	(ta.attrelid = bc.oid)
			AND (bc.relname = '$table')
			AND (ta.attrelid = i.indrelid)
			AND (ta.attnum = i.indkey[ia.attnum-1])
		ORDER BY index_name, tab_name, column_name ";
	$result = $db->sql_query($sql_pri_keys);

	if(!$result)
	{
		message_die(GENERAL_ERROR, "Failed in get_table_def (show fields)", "", __LINE__, __FILE__, $sql_pri_keys);
	}

	while ( $row = $db->sql_fetchrow($result))
	{
		if ($row['primary_key'] == 't')
		{
			if (!empty($primary_key))
			{
				$primary_key .= ', ';
			}

			$primary_key .= $row['column_name'];
			$primary_key_name = $row['index_name'];

		}
		else
		{
			//
			// We have to store this all this info because it is possible to have a multi-column key...
			// we can loop through it again and build the statement
			//
			$index_rows[$row['index_name']]['table'] = $table;
			$index_rows[$row['index_name']]['unique'] = ($row['unique_key'] == 't') ? ' UNIQUE ' : '';
			$index_rows[$row['index_name']]['column_names'] .= $row['column_name'] . ', ';
		}
	}

	if (!empty($index_rows))
	{
		while(list($idx_name, $props) = each($index_rows))
		{
			$props['column_names'] = ereg_replace(", $", "" , $props['column_names']);
			$index_create .= 'CREATE ' . $props['unique'] . " INDEX $idx_name ON $table (" . $props['column_names'] . ");$crlf";
		}
	}

	if (!empty($primary_key))
	{
		$schema_create .= "	CONSTRAINT $primary_key_name PRIMARY KEY ($primary_key),$crlf";
	}

	//
	// Generate constraint clauses for CHECK constraints
	//
	$sql_checks = "SELECT rcname as index_name, rcsrc
		FROM pg_relcheck, pg_class bc
		WHERE rcrelid = bc.oid
			AND bc.relname = '$table'
			AND NOT EXISTS (
				SELECT *
					FROM pg_relcheck as c, pg_inherits as i
					WHERE i.inhrelid = pg_relcheck.rcrelid
						AND c.rcname = pg_relcheck.rcname
						AND c.rcsrc = pg_relcheck.rcsrc
						AND c.rcrelid = i.inhparent
			)";
	$result = $db->sql_query($sql_checks);

	if (!$result)
	{
		message_die(GENERAL_ERROR, "Failed in get_table_def (show fields)", "", __LINE__, __FILE__, $sql_checks);
	}

	//
	// Add the constraints to the sql file.
	//
	while ($row = $db->sql_fetchrow($result))
	{
		$schema_create .= '	CONSTRAINT ' . $row['index_name'] . ' CHECK ' . $row['rcsrc'] . ",$crlf";
	}

	$schema_create = ereg_replace(',' . $crlf . '$', '', $schema_create);
	$index_create = ereg_replace(',' . $crlf . '$', '', $index_create);

	$schema_create .= "$crlf);$crlf";

	if (!empty($index_create))
	{
		$schema_create .= $index_create;
	}

	//
	// Ok now we've built all the sql return it to the calling function.
	//
	return (stripslashes($schema_create));

}

//
// This function returns the "CREATE TABLE" syntax for mysql dbms...
//
function get_table_def_mysql($table, $crlf)
{
	global $drop, $db;

	$schema_create = "";
	$field_query = "SHOW FIELDS FROM $table";
	$key_query = "SHOW KEYS FROM $table";

	//
	// If the user has selected to drop existing tables when doing a restore.
	// Then we add the statement to drop the tables....
	//
	if ($drop == 1)
	{
		$schema_create .= "DROP TABLE IF EXISTS $table;$crlf";
	}

	$schema_create .= "CREATE TABLE $table($crlf";

	//
	// Ok lets grab the fields...
	//
	$result = $db->sql_query($field_query);
	if(!$result)
	{
		message_die(GENERAL_ERROR, "Failed in get_table_def (show fields)", "", __LINE__, __FILE__, $field_query);
	}

	while ($row = $db->sql_fetchrow($result))
	{
		$schema_create .= '	' . $row['Field'] . ' ' . $row['Type'];

		if(!empty($row['Default']))
		{
			$schema_create .= ' DEFAULT \'' . $row['Default'] . '\'';
		}

		if($row['Null'] != "YES")
		{
			$schema_create .= ' NOT NULL';
		}

		if($row['Extra'] != "")
		{
			$schema_create .= ' ' . $row['Extra'];
		}

		$schema_create .= ",$crlf";
	}
	//
	// Drop the last ',$crlf' off ;)
	//
	$schema_create = ereg_replace(',' . $crlf . '$', "", $schema_create);

	//
	// Get any Indexed fields from the database...
	//
	$result = $db->sql_query($key_query);
	if(!$result)
	{
		message_die(GENERAL_ERROR, "FAILED IN get_table_def (show keys)", "", __LINE__, __FILE__, $key_query);
	}

	while($row = $db->sql_fetchrow($result))
	{
		$kname = $row['Key_name'];

		if(($kname != 'PRIMARY') && ($row['Non_unique'] == 0))
		{
			$kname = "UNIQUE|$kname";
		}

		if(!is_array($index[$kname]))
		{
			$index[$kname] = array();
		}

		$index[$kname][] = $row['Column_name'];
	}

	while(list($x, $columns) = @each($index))
	{
		$schema_create .= ", $crlf";

		if($x == 'PRIMARY')
		{
			$schema_create .= '	PRIMARY KEY (' . implode($columns, ', ') . ')';
		}
		elseif (substr($x,0,6) == 'UNIQUE')
		{
			$schema_create .= '	UNIQUE ' . substr($x,7) . ' (' . implode($columns, ', ') . ')';
		}
		else
		{
			$schema_create .= "	KEY $x (" . implode($columns, ', ') . ')';
		}
	}

	$schema_create .= "$crlf);";

	if(get_magic_quotes_runtime())
	{
		return(stripslashes($schema_create));
	}
	else
	{
		return($schema_create);
	}

} // End get_table_def_mysql


//
// This fuction will return a tables create definition to be used as an sql
// statement.
//
//
// The following functions Get the data from the tables and format it as a
// series of INSERT statements, for each different DBMS...
// After every row a custom callback function $handler gets called.
// $handler must accept one parameter ($sql_insert);
//
//
// Here is the function for postgres...
//
function get_table_content_postgresql($table, $handler)
{
	global $db;

	//
	// Grab all of the data from current table.
	//

	$result = $db->sql_query("SELECT * FROM $table");

	if (!$result)
	{
		message_die(GENERAL_ERROR, "Failed in get_table_content (select *)", "", __LINE__, __FILE__, "SELECT * FROM $table");
	}

	$i_num_fields = $db->sql_numfields($result);

	for ($i = 0; $i < $i_num_fields; $i++)
	{
		$aryType[] = $db->sql_fieldtype($i, $result);
		$aryName[] = $db->sql_fieldname($i, $result);
	}

	$iRec = 0;

	while($row = $db->sql_fetchrow($result))
	{
		$schema_vals = '';
		$schema_fields = '';
		$schema_insert = '';
		//
		// Build the SQL statement to recreate the data.
		//
		for($i = 0; $i < $i_num_fields; $i++)
		{
			$strVal = $row[$aryName[$i]];
			if (eregi("char|text|bool", $aryType[$i]))
			{
				$strQuote = "'";
				$strEmpty = "";
				$strVal = addslashes($strVal);
			}
			elseif (eregi("date|timestamp", $aryType[$i]))
			{
				if (empty($strVal))
				{
					$strQuote = "";
				}
				else
				{
					$strQuote = "'";
				}
			}
			else
			{
				$strQuote = "";
				$strEmpty = "NULL";
			}

			if (empty($strVal) && $strVal != "0")
			{
				$strVal = $strEmpty;
			}

			$schema_vals .= " $strQuote$strVal$strQuote,";
			$schema_fields .= " $aryName[$i],";

		}

		$schema_vals = ereg_replace(",$", "", $schema_vals);
		$schema_vals = ereg_replace("^ ", "", $schema_vals);
		$schema_fields = ereg_replace(",$", "", $schema_fields);
		$schema_fields = ereg_replace("^ ", "", $schema_fields);

		//
		// Take the ordered fields and their associated data and build it
		// into a valid sql statement to recreate that field in the data.
		//
		$schema_insert = "INSERT INTO $table ($schema_fields) VALUES($schema_vals);";

		$handler(trim($schema_insert));
	}

	return(true);

}// end function get_table_content_postgres...

//
// This function is for getting the data from a mysql table.
//

function get_table_content_mysql($table, $handler)
{
	global $db;

	// Grab the data from the table.
	if (!($result = $db->sql_query("SELECT * FROM $table")))
	{
		message_die(GENERAL_ERROR, "Failed in get_table_content (select *)", "", __LINE__, __FILE__, "SELECT * FROM $table");
	}

	// Loop through the resulting rows and build the sql statement.
	if ($row = $db->sql_fetchrow($result))
	{
		$handler("\n#\n# Table Data for $table\n#\n");
		$field_names = array();

		// Grab the list of field names.
		$num_fields = $db->sql_numfields($result);
		$table_list = '(';
		for ($j = 0; $j < $num_fields; $j++)
		{
			$field_names[$j] = $db->sql_fieldname($j, $result);
			$table_list .= (($j > 0) ? ', ' : '') . $field_names[$j];
			
		}
		$table_list .= ')';

		do
		{
			// Start building the SQL statement.
			$schema_insert = "INSERT INTO $table $table_list VALUES(";

			// Loop through the rows and fill in data for each column
			for ($j = 0; $j < $num_fields; $j++)
			{
				$schema_insert .= ($j > 0) ? ', ' : '';

				if(!isset($row[$field_names[$j]]))
				{
					//
					// If there is no data for the column set it to null.
					// There was a problem here with an extra space causing the
					// sql file not to reimport if the last column was null in
					// any table.  Should be fixed now :) JLH
					//
					$schema_insert .= 'NULL';
				}
				elseif ($row[$field_names[$j]] != '')
				{
					$schema_insert .= '\'' . addslashes($row[$field_names[$j]]) . '\'';
				}
				else
				{
					$schema_insert .= '\'\'';
				}
			}

			$schema_insert .= ');';

			// Go ahead and send the insert statement to the handler function.
			$handler(trim($schema_insert));

		}
		while ($row = $db->sql_fetchrow($result));
	}

	return(true);
}

function output_table_content($content)
{
	global $tempfile;

	//fwrite($tempfile, $content . "\n");
	//$backup_sql .= $content . "\n";
	echo $content ."\n";
	return;
}
//
// End Functions
// -------------


//
// Begin program proper
//
if( isset($HTTP_GET_VARS['perform']) || isset($HTTP_POST_VARS['perform']) )
{
	$perform = (isset($HTTP_POST_VARS['perform'])) ? $HTTP_POST_VARS['perform'] : $HTTP_GET_VARS['perform'];

	switch($perform)
	{
		case 'backup':
			$tables = array(
				'account_hist', 
				'advance_html', 
				'album', 
				'album_cat', 
				'album_comment', 
				'album_config', 
				'album_rate', 
				'attachments', 
				'attachments_config', 
				'attachments_desc', 
				'attach_quota', 
				'auth_access', 
				'avatartoplist',
				'backup', 
				'bank', 
				'banlist', 
				'banned_sites', 
				'banned_visitors', 
				'banner', 
				'banner_stats', 
				'banvote_voters', 
				'bookie_admin_bets', 
				'bookie_bets', 
				'bookie_bet_setter', 
				'bookie_categories', 
				'bookie_meetings', 
				'bookie_selections', 
				'bookie_selections_data', 
				'bookie_stats', 
				'bots',
				'bots_archive',
				'categories', 
				'cat_rel_cat_parents', 
				'cat_rel_forum_parents', 
				'charts', 	
				'charts_voters', 
				'chatbox', 
				'chatbox_session', 
				'config', 
				'config_nav', 
				'confirm', 
				'digests', 
				'digests_config', 
				'digests_forums', 
				'digests_log', 
				'disallow', 
				'extensions', 
				'extension_groups', 
				'flags', 
				'forbidden_extensions', 
				'forums', 
				'forums_descrip', 
				'forums_watch', 
				'forum_move', 
				'forum_prune', 
				'forum_tour', 
				'groups', 
				'guestbook',
				'guestbook_config',
				'helpdesk_emails', 
				'helpdesk_importance', 
				'helpdesk_msgs', 
				'helpdesk_reply', 
				'im_buddy_list',
				'im_config',
				'im_prefs',
				'im_sessions',
				'im_sites',
				'ina_ban', 
				'ina_categories', 
				'ina_challenge_tracker', 
				'ina_challenge_users', 
				'ina_chat', 
				'ina_cheat_fix', 
				'ina_data', 
				'ina_favorites', 
				'ina_gamble', 
				'ina_gamble_in_progress', 
				'ina_games', 
				'ina_hall_of_fame', 
				'ina_last_game_played', 
				'ina_rating_votes', 
				'ina_scores', 
				'ina_sessions', 
				'ina_top_scores', 
				'ina_trophy_comments', 
				'inline_ads', 
				'ip', 
				'jobs', 
				'jobs_employed', 
				'kb_articles', 
				'kb_categories', 
				'kb_results', 
				'kb_types', 
				'kb_wordlist', 
				'kb_wordmatch', 
				'lexicon', 
				'lexicon_cat', 
				'links', 
				'link_categories', 
				'link_comments', 
				'link_config', 
				'link_custom', 
				'link_customdata', 
				'link_votes', 
				'logs', 
				'lottery', 
				'lottery_history', 
				'medal', 
				'medal_cat', 
				'medal_mod', 
				'medal_user', 
				'meeting_comment', 
				'meeting_config', 
				'meeting_data', 
				'meeting_guestnames', 
				'meeting_user', 
				'meeting_usergroup', 
				'modules', 
				'module_admin_panel', 
				'module_cache', 
				'module_group_auth', 
				'module_info', 
				'mycalendar', 
				'mycalendar_event_types', 
				'optimize_db', 
				'pages',
				'pa_auth', 
				'pa_cat', 
				'pa_comments', 
				'pa_config', 
				'pa_custom', 
				'pa_customdata', 
				'pa_download_info', 
				'pa_files', 
				'pa_license', 
				'pa_mirrors', 
				'pa_votes', 
				'pjirc', 
				'points_logger', 
				'portal', 
				'posts', 
				'posts_edit', 
				'posts_ignore_sigav', 
				'posts_text', 
				'privmsgs', 
				'privmsgs_archive', 
				'privmsgs_text', 
				'profile_view', 
				'quota_limits', 
				'ranks', 
				'rating', 
				'rating_bias', 
				'rating_config', 
				'rating_option', 
				'rating_rank', 
				'rating_temp', 
				'referers', 
				'referral', 
				'search_results', 
				'search_wordlist', 
				'search_wordmatch', 
				'serverload', 
				'sessions', 
				'sessions_keys', 
				'shops', 
				'shop_items', 
				'shop_transactions', 
				'shout', 
				'smilies', 
				'smilies_cat', 
				'stats_smilies_index', 
				'stats_smilies_info', 
				'thanks', 
				'themes', 
				'themes_name', 
				'thread_kicker', 
				'title_infos', 
				'topics', 
				'topics_viewdata', 
				'topics_watch', 
				'toplist', 
				'toplist_anti_flood', 
				'transactions', 
				'unique_hits', 
				'users', 
				'users_comments', 
				'user_group', 
				'user_notes', 
				'user_shops', 
				'user_shops_items', 
				'vote_desc', 
				'vote_results', 
				'vote_voters', 
				'words', 
				'xdata_auth', 
				'xdata_data', 
				'xdata_fields'
		);

			$additional_tables = (isset($HTTP_POST_VARS['additional_tables'])) ? $HTTP_POST_VARS['additional_tables'] : ( (isset($HTTP_GET_VARS['additional_tables'])) ? $HTTP_GET_VARS['additional_tables'] : "" );

			$backup_type = (isset($HTTP_POST_VARS['backup_type'])) ? $HTTP_POST_VARS['backup_type'] : ( (isset($HTTP_GET_VARS['backup_type'])) ? $HTTP_GET_VARS['backup_type'] : "" );

			$gzipcompress = (!empty($HTTP_POST_VARS['gzipcompress'])) ? $HTTP_POST_VARS['gzipcompress'] : ( (!empty($HTTP_GET_VARS['gzipcompress'])) ? $HTTP_GET_VARS['gzipcompress'] : 0 );

			$drop = (!empty($HTTP_POST_VARS['drop'])) ? intval($HTTP_POST_VARS['drop']) : ( (!empty($HTTP_GET_VARS['drop'])) ? intval($HTTP_GET_VARS['drop']) : 0 );

			if(!empty($additional_tables))
			{
				if(ereg(",", $additional_tables))
				{
					$additional_tables = split(",", $additional_tables);

					for($i = 0; $i < sizeof($additional_tables); $i++)
					{
						$tables[] = trim($additional_tables[$i]);
					}

				}
				else
				{
					$tables[] = trim($additional_tables);
				}
			}

			if( !isset($HTTP_POST_VARS['backupstart']) && !isset($HTTP_GET_VARS['backupstart']))
			{
				include('../admin/page_header_admin.'.$phpEx);

				$template->set_filenames(array(
					"body" => "admin/utils_backup_body.tpl")
				);	
				
				$s_hidden_fields = "<input type=\"hidden\" name=\"perform\" value=\"backup\" /><input type=\"hidden\" name=\"drop\" value=\"1\" /><input type=\"hidden\" name=\"perform\" value=\"$perform\" />";

				$template->assign_vars(array(
					"L_DATABASE_BACKUP" => $lang['Database_Backup'],
					"L_BACKUP_EXPLAIN" => $lang['Backup_explain'],
					"L_FULL_BACKUP" => $lang['Full_backup'],
					"L_STRUCTURE_BACKUP" => $lang['Structure_backup'],
					"L_DATA_BACKUP" => $lang['Data_backup'],
					"L_ADDITIONAL_TABLES" => $lang['Additional_tables'],
					"L_START_BACKUP" => $lang['Start_backup'],
					"L_BACKUP_OPTIONS" => $lang['Backup_options'],
					"L_GZIP_COMPRESS" => $lang['Gzip_compress'],
					"S_HIDDEN_FIELDS" => $s_hidden_fields,
					"S_DBUTILS_ACTION" => append_sid("admin_db_utilities.$phpEx"))
				);
				$template->pparse("body");

				break;

			}
			else if( !isset($HTTP_POST_VARS['startdownload']) && !isset($HTTP_GET_VARS['startdownload']) )
			{
				if(is_array($additional_tables))
				{
					$additional_tables = implode(',', $additional_tables);
				}
				$template->set_filenames(array(
					"body" => "admin/admin_message_body.tpl")
				);

				$template->assign_vars(array(
					"META" => '<meta http-equiv="refresh" content="2;url=' . append_sid("admin_db_utilities.$phpEx?perform=backup&additional_tables=" . quotemeta($additional_tables) . "&backup_type=$backup_type&drop=1&amp;backupstart=1&gzipcompress=$gzipcompress&startdownload=1") . '">',

					"MESSAGE_TITLE" => $lang['Database_Backup'],
					"MESSAGE_TEXT" => $lang['Backup_download'])
				);

				include('../admin/page_header_admin.'.$phpEx);

				$template->pparse("body");

				include('../admin/page_footer_admin.'.$phpEx);

			}
			header("Pragma: no-cache");
			$do_gzip_compress = FALSE;
			if( $gzipcompress )
			{
				$phpver = phpversion();

				if($phpver >= "4.0")
				{
					if(extension_loaded("zlib"))
					{
						$do_gzip_compress = TRUE;
					}
				}
			}
			if($do_gzip_compress)
			{
				@ob_start();
				@ob_implicit_flush(0);
				header("Content-Type: application/x-gzip; name=\"" .  gmdate("d-m-Y_H-i", time()) . "_GMT_db_backup_-_" . str_replace(' ', '_', $board_config['sitename']) . ".sql.gz\"");
				header("Content-disposition: attachment; filename=" .  gmdate("d-m-Y_H-i", time()) . "_GMT_db_backup_-_" . str_replace(' ', '_', $board_config['sitename']) . ".sql.gz");
			}
			else
			{
				header("Content-Type: text/x-delimtext; name=\"" .  gmdate("d-m-Y_H-i", time()) . "_GMT_db_backup_-_" . str_replace(' ', '_', $board_config['sitename']) . ".sql\"");
				header("Content-disposition: attachment; filename=" .  gmdate("d-m-Y_H-i", time()) . "_GMT_db_backup_-_" . str_replace(' ', '_', $board_config['sitename']) . ".sql");
			}

			//
			// Build the sql script file...
			//
			echo "#\n";
			echo "# " . $board_config['sitename'] . " Backup Script\n";
			echo "# Dump of tables for $dbname\n";
			echo "#\n# DATE : " .  gmdate("d-m-Y H:i:s", time()) . " GMT\n";
			echo "#\n";

			for($i = 0; $i < sizeof($tables); $i++)
			{
				$table_name = $tables[$i];

						$table_def_function = "get_table_def_mysql";
						$table_content_function = "get_table_content_mysql";

				if($backup_type != 'data')
				{
					echo "\n#\n# TABLE: " . $prefix . $table_name . "\n#\n";
					echo $table_def_function($prefix . $table_name, "\n") . "\n";
				}

				if($backup_type != 'structure')
				{
					$table_content_function($prefix . $table_name, "output_table_content");
				}
			}
			
			echo "\n#\n# Fulltext Indexes\n# phpBB2 Backup by default does not support these\n#\n";
			echo "ALTER TABLE phpbb_link_comments DROP INDEX comment_bbcode_uid, ADD FULLTEXT comment_bbcode_uid (comment_bbcode_uid);\n";
			echo "ALTER TABLE phpbb_pa_comments DROP INDEX comment_bbcode_uid, ADD FULLTEXT comment_bbcode_uid (comment_bbcode_uid);\n";
			echo "ALTER TABLE phpbb_topics DROP INDEX topic_title, ADD FULLTEXT topic_title (topic_title);\n";
			echo "ALTER TABLE phpbb_users DROP INDEX user_skype, ADD FULLTEXT user_skype (user_skype);\n";
			
			if($do_gzip_compress)
			{
				$Size = ob_get_length();
				$Crc = crc32(ob_get_contents());
				$contents = gzcompress(ob_get_contents());
				ob_end_clean();
				echo "\x1f\x8b\x08\x00\x00\x00\x00\x00".substr($contents, 0, strlen($contents) - 4).gzip_PrintFourChars($Crc).gzip_PrintFourChars($Size);
			}
			exit;

			break;

		case 'restore':
			if(!isset($HTTP_POST_VARS['restore_start']))
			{
				//
				// Define Template files...
				//
				include('../admin/page_header_admin.'.$phpEx);

				$template->set_filenames(array(
					"body" => "admin/utils_restore_body.tpl")
				);

				$s_hidden_fields = "<input type=\"hidden\" name=\"perform\" value=\"restore\" /><input type=\"hidden\" name=\"perform\" value=\"$perform\" />";

				$template->assign_vars(array(
					"L_DATABASE_RESTORE" => $lang['Database_Restore'],
					"L_RESTORE_EXPLAIN" => $lang['Restore_explain'],
					"L_SELECT_FILE" => $lang['Select_file'],
					"L_START_RESTORE" => $lang['Start_Restore'],

					"S_DBUTILS_ACTION" => append_sid("admin_db_utilities.$phpEx"),
					"S_HIDDEN_FIELDS" => $s_hidden_fields)
				);
				$template->pparse("body");

				break;

			}
			else
			{
				//
				// Handle the file upload ....
				// If no file was uploaded report an error...
				//
				$backup_file_name = (!empty($HTTP_POST_FILES['backup_file']['name'])) ? $HTTP_POST_FILES['backup_file']['name'] : "";
				$backup_file_tmpname = ($HTTP_POST_FILES['backup_file']['tmp_name'] != "none") ? $HTTP_POST_FILES['backup_file']['tmp_name'] : "";
				$backup_file_type = (!empty($HTTP_POST_FILES['backup_file']['type'])) ? $HTTP_POST_FILES['backup_file']['type'] : "";

				if($backup_file_tmpname == "" || $backup_file_name == "")
				{
					message_die(GENERAL_MESSAGE, $lang['Restore_Error_no_file']);
				}
				//
				// If I file was actually uploaded, check to make sure that we
				// are actually passed the name of an uploaded file, and not
				// a hackers attempt at getting us to process a local system
				// file.
				//
				if( file_exists(phpbb_realpath($backup_file_tmpname)) )
				{
					if( preg_match("/^(text\/[a-zA-Z]+)|(application\/(x\-)?gzip(\-compressed)?)|(application\/octet-stream)$/is", $backup_file_type) )
					{
						if( preg_match("/\.gz$/is",$backup_file_name) )
						{
							$do_gzip_compress = FALSE;
							$phpver = phpversion();
							if($phpver >= "4.0")
							{
								if(extension_loaded("zlib"))
								{
									$do_gzip_compress = TRUE;
								}
							}

							if($do_gzip_compress)
							{
								$gz_ptr = gzopen($backup_file_tmpname, 'rb');
								$sql_query = "";
								while( !gzeof($gz_ptr) )
								{
									$sql_query .= gzgets($gz_ptr, 100000);
								}
							}
							else
							{
								message_die(GENERAL_ERROR, $lang['Restore_Error_decompress']);
							}
						}
						else
						{
							$sql_query = fread(fopen($backup_file_tmpname, 'r'), filesize($backup_file_tmpname));
						}
						//
						// Comment this line out to see if this fixes the stuff...
						//
						//$sql_query = stripslashes($sql_query);
					}
					else
					{
						message_die(GENERAL_ERROR, $lang['Restore_Error_filename'] ." $backup_file_type $backup_file_name");
					}
				}
				else
				{
					message_die(GENERAL_ERROR, $lang['Restore_Error_uploading']);
				}

				if($sql_query != "")
				{
					// Strip out sql comments...
					$sql_query = remove_remarks($sql_query);
					$pieces = split_sql_file($sql_query, ";");

					$sql_count = count($pieces);
					for($i = 0; $i < $sql_count; $i++)
					{
						$sql = trim($pieces[$i]);

						if(!empty($sql) and $sql[0] != "#")
						{
							if(VERBOSE == 1)
							{
								echo "Executing: $sql\n<br>";
								flush();
							}

							$result = $db->sql_query($sql);

							if(!$result && ( !(SQL_LAYER == 'postgresql' && eregi("drop table", $sql) ) ) )
							{
								message_die(GENERAL_ERROR, "Error importing backup file", "", __LINE__, __FILE__, $sql);
							}
						}
					}
				}

				include('../admin/page_header_admin.'.$phpEx);

				$template->set_filenames(array(
					"body" => "admin/admin_message_body.tpl")
				);

				$message = $lang['Restore_success'];

				$template->assign_vars(array(
					"MESSAGE_TITLE" => $lang['Restore'],
					"MESSAGE_TEXT" => $message)
				);

				$template->pparse("body");
				break;
			}
			break;
	}
}

include('../admin/page_footer_admin.'.$phpEx);

}
$message = $lang['Module_disabled'] . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.$phpEx?pane=right") . '">', '</a>');
message_die(GENERAL_MESSAGE, $message);

?>