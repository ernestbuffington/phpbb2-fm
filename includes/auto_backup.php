<?php
/** 
*
* @package includes
* @version $Id: auto_backup.php,v 1.47.2.5 2006/03/23 17:49:42 princeomz2004 Exp $
* @copyright (c) 2006 Omar Ramadan
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
	
if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}

//
// Load default header
//
$no_page_header = TRUE;
include($phpbb_root_path . 'includes/sql_parse.'.$phpEx);

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

function random_string($length)
{
	$pattern = "1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
  
	for($i=0;$i<$length;$i++)
	{
		if( isset($key) )
		{
			$key .= $pattern{ rand(0, strlen($pattern) ) };
		}
		else
		{
			$key = $pattern{ rand(0, strlen($pattern) )};
		}
	}
	
	return $key;
}

//
// This function is used for grabbing the sequences for postgres...
//
function pg_get_sequences($crlf, $backup_type)
{
	global $db;

	$get_seq_sql = "SELECT relname 
		FROM pg_class 
		WHERE NOT relname ~ 'pg_.*'
			AND relkind = 'S' 
		ORDER BY relname";
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

			$get_props_sql = "SELECT * 
				FROM $sequence";
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
		email_message_die("Failed in get_table_def (show fields)", "", __LINE__, __FILE__, $field_query);
	} // end if..

	if ($drop == 1)
	{
		$schema_create .= "DROP TABLE `$table`;$crlf";
	} // end if

	//
	// Ok now we actually start building the SQL statements to restore the tables
	//

	$schema_create .= "CREATE TABLE `$table`($crlf";

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
		email_message_die("Failed in get_table_def (show fields)", "", __LINE__, __FILE__, $sql_pri_keys);
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
			$index_create .= 'CREATE ' . $props['unique'] . " INDEX $idx_name ON `$table` (" . $props['column_names'] . ");$crlf";
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
		email_message_die("Failed in get_table_def (show fields)", "", __LINE__, __FILE__, $sql_checks);
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
	$field_query = "SHOW FIELDS FROM `$table`;";
	$key_query = "SHOW KEYS FROM `$table`;";

	//
	// If the user has selected to drop existing tables when doing a restore.
	// Then we add the statement to drop the tables....
	//
	if ($drop == 1)
	{
		$schema_create .= "DROP TABLE IF EXISTS `$table`;$crlf";
	}

	$schema_create .= "CREATE TABLE `$table`($crlf";

	//
	// Ok lets grab the fields...
	//
	$result = $db->sql_query($field_query);
	if(!$result)
	{
		email_message_die("Failed in get_table_def (show fields)", "", __LINE__, __FILE__, $field_query);
	}

	while ($row = $db->sql_fetchrow($result))
	{
		$schema_create .= '	' . $row['Field'] . ' ' . $row['Type'];

		if(!empty($row['Default']))
		{
			$schema_create .= ' DEFAULT \'' . $row['Default'] . '\'';
		}

		if($row['Null'] !== "YES")
		{
			$schema_create .= ' NOT NULL';
		}

		if($row['Extra'] !== "")
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
		message_die("FAILED IN get_table_def (show keys)", "", __LINE__, __FILE__, $key_query);
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

	$result = $db->sql_query("SELECT * FROM `$table`");

	if (!$result)
	{
		email_message_die("Failed in get_table_content (select *)", "", __LINE__, __FILE__, "SELECT * FROM `$table`");
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
		unset($schema_vals);
		unset($schema_fields);
		unset($schema_insert);
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
				if ($empty($strVal))
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
		$schema_insert = "INSERT INTO `$table` ($schema_fields) VALUES($schema_vals);";

		$data = $handler(trim($schema_insert));
	}

	return($data);

}// end function get_table_content_postgres...

//
// This function is for getting the data from a mysql table.
//

function get_table_content_mysql($table, $handler)
{
	global $db;

	// Grab the data from the table.
	if (!($result = $db->sql_query("SELECT * FROM `$table`;")))
	{
		email_message_die("Failed in get_table_content (select *)", "", __LINE__, __FILE__, "SELECT * FROM `$table`;");
	}

	// Loop through the resulting rows and build the sql statement.
	if ($row = $db->sql_fetchrow($result))
	{
		$data .= $handler("\n#\n# Table Data for $table\n#\n");
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
			$schema_insert = "INSERT INTO `$table` $table_list VALUES(";

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
			$data .= $handler(trim($schema_insert));

		}
		while ($row = $db->sql_fetchrow($result));
	}

	return($data);
}

function output_table_content($content)
{
	global $gz;
	
	if ( gzwrite($gz, $content . "\n") )
	{
		return true;
	}
}
//
// End Functions
// -------------


//
// Begin program proper
//
			
// Get info from backup table
$sql = "SELECT *
	FROM " . BACKUP_TABLE;
if ( !($backup_result = $db->sql_query($sql)) )
{
	email_message_die($lang['Error_retrieving_auto_backup'], '', __LINE__, __FILE__, $sql);
}

$backup_table = $db->sql_fetchrow($backup_result);
$db->sql_freeresult($backup_result);
		
$backup_type = $backup_table['backup_type'];
$phpbb_only  = $backup_table['phpbb_only'];
$no_search   = $backup_table['no_search'];
$ignore_tables = explode(", ", $backup_table['ignore_tables']);

$sql = 'SHOW TABLES';
$field = "Tables_in_{$dbname}";
			
$result = $db->sql_query($sql);
while ($row = $db->sql_fetchrow($result))
{
	$current_table = $row[$field]; 
	$current_prefix = substr($current_table, 0, strlen($table_prefix));
	
	if ($phpbb_only && $current_prefix != $table_prefix)
	{
		continue;
	}
	else if ( ( $no_search && ( $current_table == SEARCH_MATCH_TABLE || $current_table == SEARCH_WORD_TABLE || $current_table == SEARCH_TABLE ) ) || in_array( $current_table, $ignore_tables ) )
	{
		continue;
	}
	else
	{
		$tables[] = $current_table;
	}
}
			
//
// Fix to support databases over 16mb and under 180mb
//
$time_of_file  = gmdate("Y-m-d_H-i", $time);
$file_name     = $time_of_file . '_GMT_db_backup_-_' . str_replace(' ', '_', $board_config['sitename']) . '_' . random_string(5) . '.sql.gz';
$file_location = $phpbb_root_path . 'uploads/backups/' . $file_name;
$gz = @gzopen($file_location,'w9');
				
//
// Build the sql script file...
//
$backup = "#\n";
$backup .= "# " . $board_config['sitename'] . " Backup Script\n";
$backup .= "#\n";
$backup .= "# Dump of tables for $dbname\n";
$backup .= "#\n# DATE : " .  gmdate("d-m-Y H:i:s", time()) . " GMT\n";
$backup .= "#\n";
$backup .= "\n";
			
// Write header
gzwrite($gz, $backup);

for($i = 0; $i < count($tables); $i++)
{
	$table_name = $tables[$i];

	$table_def_function = "get_table_def_mysql";
	$table_content_function = "get_table_content_mysql";
				
	if ( !empty($table_name) )
	{
		if($backup_type != 'data')
		{
			gzwrite($gz, "#\n# TABLE: " . $table_name . "\n#\n");
			gzwrite($gz, $table_def_function($table_name, "\n") . "\n");
		}
					
		if($backup_type != 'structure')
		{
			$table_content_function($table_name, "output_table_content");
		}
	}
}
			
@gzclose($gz);
$backup_link = '';
				
/*
Comment by: shadowpballer
configure the file name here on $time_of_file use the php time formats to decide how you want the time displayed.
On filename where phpbb_db_backup_ is you can configure that to be what you want as the first part of the file name
*/			
if ( $backup_table['write_backups_true'] )
{	
	$server_protocol = ($board_config['cookie_secure']) ? 'https://' : 'http://';
	$server_name = preg_replace('#^\/?(.*?)\/?$#', '\1', trim($board_config['server_name']));
	$server_port = ($board_config['server_port'] <> 80) ? ':' . trim($board_config['server_port']) : '';
	$script_name = preg_replace('#^\/?(.*?)\/?$#', '\1', trim($board_config['script_path']));
	$file_url = trim($server_protocol . $server_name . $server_port . '/' . $script_name . '/uploads/backups/' . $file_name, "/"); 
	$backup_link .=  "\n" . sprintf($lang['file_saved_to_backups'], $file_url) . "\n\n";
					
	// delete old backups
	$handle = @opendir($phpbb_root_path . 'uploads/backups/');
	while ( $file = @readdir($handle) )
	{
		if ( $file != "." && $file != ".." && $file != "index.htm" && $file != "index.html" )
		{ 
			$file_time = filectime($phpbb_root_path . 'uploads/backups/' . $file);
			$file_array[] = array( 'time' => $file_time, 'file' => $phpbb_root_path . 'uploads/backups/' . $file );
		}
	}
	@closedir($handle);
					
	if ( is_array( $file_array ) )
	{
		multisort($file_array, 'time', 'desc');
		$i = 1;
		$backups_to_keep = ( !empty( $backup_table['files_to_keep'] ) ) ? $backup_table['files_to_keep'] : -1;

		foreach ( $file_array as $file )	
		{
			if ( $i > $backups_to_keep && $backups_to_keep != -1 )
			{
				unlink($file['file']);
			}
			$i++;
		}
	}					
}

// Email Message
$backup_to      =  $backup_table['email'];
$backup_from    = $board_config['board_email'];
$backup_subject = $lang['auto_backup_email_subject'].gmdate("Y/m/d H:i:s", $time);
$backup_message_text = $lang['auto_backup_email_message'].gmdate("Y/m/d H:i:s", $time).".".$backup_link;
$backup_headers = 'From: ' . $board_config['sitename'] . ' <' . $backup_from . '>';
												
if ($backup_table['ftp_true'])
{
	$ftp_message = $lang['FTP_file_information'] . ":\n";
					
	// set up basic connection
	$conn_id = ftp_connect($backup_table['ftp_server']);

	// login with username and password
	$login_result = ftp_login($conn_id, $backup_table['ftp_user_name'], base64_decode($backup_table['ftp_user_pass']));

	// check connection
	if ( (!$conn_id) || (!$login_result) ) 
	{
		$ftp_message .= $lang['FTP_connection_failed'] . "!\n";
	}

	$ftp_message .= $lang['Current_directory'] . ": " . ftp_pwd($conn_id) . "\n";
	$ftp_message .= $lang['Change_directory_to'] . ": " . $backup_table['ftp_directory'] . "\n";
	
	// try to change the directory to somedir
	if ( ftp_chdir($conn_id, $backup_table['ftp_directory']) )
	{
		$ftp_message .= $lang['Current_directory']. ": " . ftp_pwd($conn_id) . "\n";
	}
	else
	{
		$ftp_message .= $lang['Couldnt_change_directory'] . "\n";
		
		// Create directory	
		$ftp_message .= $lang['Creating_directory'] . ": " . $backup_table['ftp_directory'] . "\n";
					   
		if ( ftp_mkdir($conn_id, $backup_table['ftp_directory']) )
		{
			$ftp_message .= $lang['Created_directory'] . ": " . $backup_table['ftp_directory'] . "\n";
							
			// Change directory
			ftp_chdir($conn_id, $backup_table['ftp_directory']);
		}
		else
		{
			$ftp_message .= $lang['Cant_Create_directory'] . ": " . $backup_table['ftp_directory'] . "\n";
		}
		$ftp_message .= $lang['Current_directory']. ": " . ftp_pwd($conn_id) . "\n";
	}
	
	// upload the file
	$file_opened = @fopen($file_location, 'rb');;
					
	// validate directory
	$upload = 0;
	if ( ftp_pwd($conn_id) == $backup_table['ftp_directory'] )
	{
		$upload = ftp_fput($conn_id, $file_name, $file_opened, FTP_BINARY);
	}
					
	// check upload status
	if (!$upload)
	{
		$ftp_message .= $lang['FTP_upload_failed'] . "!";
	}
	else
	{
		$ftp_message .= sprintf($lang['Uploaded_file_to'], $file_name). $backup_table['ftp_server'];
	}

	$ar_files = ftp_nlist($conn_id, $backup_table['ftp_directory']);
	
	//var_dump($ar_files);

	if (is_array($ar_files))
	{ 
		// makes sure there are files
		for ($i = 0; $i < sizeof($ar_files); $i++)
		{
			$st_file = basename($ar_files[$i]);
			if( $st_file != "." && $st_file != ".." && $st_file != "index.htm" )
			{
				$file_time =  ftp_mdtm( $conn_id, $st_file );
				$ftp_file_array[] = array( 'time' => $file_time, 'file' => $st_file );				
			}		
		}
 	}

	if ( is_array( $ftp_file_array ) )
	{
		multisort($ftp_file_array, 'time', 'desc');
		$i = 1;
		$backups_to_keep = ( !empty( $backup_table['files_to_keep'] ) ) ? $backup_table['files_to_keep'] : -1;

		foreach ( $ftp_file_array as $file )	
		{
			if ( $i > $backups_to_keep && $backups_to_keep != -1 )
			{
				ftp_delete($conn_id, $file['file']);
			}
			$i++;
		}
	}
	
	// close the FTP stream and file stream
	ftp_quit($conn_id); 
	@fclose($file_opened);		
					
	if ($backup_table['email_true'])
	{
		$backup_message_text .= "\n".$ftp_message;
	}
	else
	{
		$backup_message_text .= "\n".$ftp_message;
		email_message_die($backup_message_text, $backup_subject);
	}
}

if ($backup_table['email_true'])
{
	// Obtain file upload vars
	$fileatt      = $file_location;
	$fileatt_type = 'application/x-gzip';
	$fileatt_name = $file_name;

	// Read the file to be attached ('rb' = read binary)	
	$file = @fopen($fileatt,'rb');
					
	// Generate a boundary string
	$semi_rand = md5($time);
	$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";

	// Add the headers for a file attachment
	$backup_headers .= "\nMIME-Version: 1.0\n" .
		"Content-Type: multipart/mixed;\n" .
		" boundary=\"{$mime_boundary}\"";

	// Add a multipart boundary above the plain message
	$backup_message = "This is a multi-part message in MIME format.\n\n" .
		"--{$mime_boundary}\n" .
		"Content-Type: text/plain; charset=\"iso-8859-1\"\n" .
		"Content-Transfer-Encoding: 7bit\n\n" .
	
	$backup_message_text . "\n\n";

	// Base64 encode the file data
	// $data = chunk_split(base64_encode($data));
	// removed to keep php memory_limit low
	// combined with below
					
	// Add file attachment to the message
	$backup_message .= "--{$mime_boundary}\n" .
		"Content-Type: {$fileatt_type};\n" .
		" name=\"{$fileatt_name}\"\n" .
		//"Content-Disposition: attachment;\n" .
		//" filename=\"{$fileatt_name}\"\n" .
		"Content-Transfer-Encoding: base64\n\n" .
		chunk_split( base64_encode( fread( $file, filesize($fileatt) ) ) ) . "\n\n" .
		"--{$mime_boundary}--\n";


	// Send the message
	if ( $board_config['smtp_delivery'] )
	{
		include($phpbb_root_path . 'includes/smtp.'.$phpEx);
		smtpmail($backup_to, $backup_subject, $backup_message, $backup_headers);
	}
	else
	{
		mail($backup_to, $backup_subject, $backup_message, $backup_headers);
	}
					
	// unset data
	@fclose($file);
	unset($backup_message);
}
				
// remove file from /backups if you dont want it
if (!$backup_table['write_backups_true'])
{
	@unlink($file_location);
}

?>