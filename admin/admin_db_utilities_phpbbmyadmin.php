<?php
/** 
*
* @package admin
* @version $Id: admin_db_utilities_phpbbmyadmin.php,v 0.3.4 2004 Exp $
* @copyright (c) 2003, 2004 Armin Altorffer
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
	
	
define('IN_PHPBB', 1);

if (!empty($setmodules))
{
	$file = append_sid(basename(__FILE__));
	$module['Utilities_']['PhpBBMyAdmin'] = $file;
	return;
}

//
// Let's set the root dir for phpBB
//
$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);

// include language file
$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_phpbbmyadmin.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_phpbbmyadmin.' . $phpEx);

// Going the powerful way here, using $file in links and not a literal text:
$file = append_sid(basename(__FILE__));

// Define the template file to use:
$template->set_filenames(array(
	'body' => 'admin/utils_phpbbmyadmin_body.tpl')
);

// Get HTTP_GET/POST info
// mode:
$mode = isset($HTTP_GET_VARS['mode']) ? htmlspecialchars($HTTP_GET_VARS['mode']) : '';
if (isset($HTTP_POST_VARS['submit']))
{
	$mode = 'submit';
}
if (isset($HTTP_POST_VARS['repairall']))
{
	$mode = 'repairall';
}
if (isset($HTTP_POST_VARS['optimizeall']))
{
	$mode = 'optimizeall';
}
if (isset($HTTP_POST_VARS['go_with_selected']))
{
	$mode = 'with_selected';
}
// get variables:
$tablename = isset($HTTP_GET_VARS['tablename']) ? htmlspecialchars($HTTP_GET_VARS['tablename']) : '';
$this_query = isset($HTTP_GET_VARS['this_query']) ? $HTTP_GET_VARS['this_query'] : '';
$confirm = isset($HTTP_GET_VARS['confirm']) ? htmlspecialchars($HTTP_GET_VARS['confirm']) : '';
$first = isset($HTTP_GET_VARS['first']) ? htmlspecialchars($HTTP_GET_VARS['first']) : '';
$sort = isset($HTTP_GET_VARS['sort']) ? htmlspecialchars($HTTP_GET_VARS['sort']) : '';
$order = isset($HTTP_GET_VARS['order']) ? htmlspecialchars($HTTP_GET_VARS['order']) : '';
$with_selected = isset($HTTP_POST_VARS['with_selected']) ? htmlspecialchars($HTTP_POST_VARS['with_selected']) : '';
$with_selected_table_list = isset($HTTP_POST_VARS['with_selected_table_list']) ? $HTTP_POST_VARS['with_selected_table_list'] : '';
$force_normal_die = isset($HTTP_GET_VARS['force_normal_die']) ? htmlspecialchars($HTTP_GET_VARS['force_normal_die']) : '';
if (isset($HTTP_POST_VARS['this_query']))
{
	$this_query = $HTTP_POST_VARS['this_query'];
}
// Done with HTTP_GET/POST info

// Build With selected select box:
$template->assign_vars(array(
	"L_WITH_SELECTED_WORD" => $lang['SQL_Admin_With_Selected_Word'],
	"L_WITH_SELECTED_DROP" => $lang['SQL_Admin_Drop_Word'],
	"L_WITH_SELECTED_EMPTY" => $lang['SQL_Admin_Empty_Word'],
	"L_WITH_SELECTED_OPTIMIZE" => $lang['SQL_Admin_Optimize'],
	"L_WITH_SELECTED_REPAIR" => $lang['SQL_Admin_Repair_Word'])
);
//

// BEGIN go with selected
if(!strcmp($mode, 'with_selected') && (!empty($with_selected_table_list)))
{
	$do_these_queries = '';
	$message = '';
	foreach($with_selected_table_list as $table)
	{
		switch($with_selected)
		{
			case 'optimize':
				$do_these_queries .= 'OPTIMIZE TABLE ' . $table . ';';
				$message .= 'OPTIMIZE TABLE ' . $table . ';<br />';
				break;
			case 'repair':
				$do_these_queries .= 'REPAIR TABLE ' . $table . ';';
				$message .= 'REPAIR TABLE ' . $table . ';<br />';
				break;
			case 'empty':
				$dbms == 'mysql' ? $do_these_queries .= 'DELETE FROM ' . $table . ';' : $do_these_queries .= 'TRUNCATE TABLE ' . $table . ';';
				$message .= ($dbms == 'mysql' ? 'DELETE FROM ' . $table . ';<br />' : 'TRUNCATE TABLE ' . $table . ';<br />');
				break;
			case 'drop':
				$do_these_queries .= 'DROP TABLE ' . $table . ';';
				$message .= 'DROP TABLE ' . $table . ';<br />';
				break;
		}
	}
	$message .= $lang['SQL_Admin_Confirm'];
	$message .= '<br /><a href="' . $file . '&amp;mode=submit&amp;this_query=' . $do_these_queries . '&amp;force_normal_die=yes" class="gen">' . $lang['Yes'] . '</a> / ';
	$message .= '<a href="' . $file . '" class="gen">' . $lang['No'] . '</a>';
	message_die (GENERAL_MESSAGE, $message);
}
// END go with selected.

// BEGIN submit
if(!strcmp($mode, 'submit'))
{
	if (empty($this_query))
	{
		message_die(GENERAL_MESSAGE, sprintf($lang['SQL_Admin_No_Query'], '<a href="' . $file . '">', '</a>' , '<a href="index.php?pane=right&amp;' . $userdata['session_id'] . '">', '</a>'));
	}
	// Add a semi-colon to the end if there isn't one:
	if (!strrpos($this_query, ";"))
	{
		$this_query .= ";";
	}
	$this_query = stripslashes ($this_query);
	// Cut into multiple queries:
	$queries = explode(';', $this_query, (count(explode(';', $this_query)) - 1));
	// For the final normal die message:
	$queriesdone = "";
	foreach($queries as $query)
	{
		// Add a semi-colon to the end if there isn't one:
		if (!strrpos($query, ";"))
		{
			$query .= ";";
		}
		// Confirmation message required?
		if ($confirm == 'yes')
		{
			$message = $query;
			$message .= '<br />' . $lang['SQL_Admin_Confirm'];
			$message .= '<br /><a href="' . $file . '&amp;mode=submit&amp;this_query=' . $this_query . '" class="gen">' . $lang['Yes'] . '</a> / ';
			$message .= '<a href="' . $file . '" class="gen">' . $lang['No'] . '</a>';
			message_die (GENERAL_MESSAGE, $message);
		}
		$result = $db->sql_query($query);
		if (!$result)
		{
			message_die(GENERAL_ERROR, sprintf($lang['SQL_Admin_Error_In_Query'], $query, '<a href="' . $file . '" class="gen">', '</a>' , '<a href="index.php?pane=right&amp;' . $userdata['session_id'] . '" class="gen">', '</a>'));
		}
		// Show results or not?
		$donormaldie = TRUE;
		$queriesdone .= '<br />' . $query;
		// Handle output of SELECT statements
		$query_words = explode(" ", $query);
		// if ((strtoupper($query_words[0]) == 'SELECT') && ($force_normal_die != 'yes'))
		if ($db->sql_numrows($result) && ($force_normal_die != 'yes'))
		{
			// Apparently we have some form of output.
			$donormaldie = FALSE;

			// Turn it ON ON ON ON:
			$template->assign_block_vars('switch_submit_result', array());
			// Remember the number of fields (aka columns) and the number of rows:
			$field_count = $db->sql_numfields($result);
			$row_count = $db->sql_numrows($result);
			// Some general assigning:
			$template->assign_vars(array(
				'SUBMIT_RESULT_FIELD_COUNT' => $field_count,
				'SUBMIT_RESULT_QUERY' => $query)
			);
			// The field header:
			for ($i = 0; $i < $field_count; $i++)
			{
				$field[$i] = $db->sql_fieldname($i, $result);
				$template->assign_block_vars('switch_submit_result.submit_result_fields', array(
					'SUBMIT_RESULT_FIELD_NAME' => $db->sql_fieldname($i, $result))
				);
			}
			// OK, we have the data... let's put it out to a thingy!
			while ($row = $db->sql_fetchrow($result))
			{
				$template->assign_block_vars('switch_submit_result.submit_result_data', array());
				for ($i = 0; $i < $field_count; $i++)
				{
					$template->assign_block_vars('switch_submit_result.submit_result_data.submit_result_data_row', array(
						'SUBMIT_RESULT_DATA' => htmlspecialchars($row[$field[$i]]))
					);
				}
			}
		}
		//
	}
	// done with this query:
	if ($donormaldie)
	{
		message_die(GENERAL_MESSAGE, sprintf($lang['SQL_Admin_Success_Query'], $queriesdone, '<a href="' . $file . '" class="gen">', '</a>' , '<a href="index.php?pane=right&amp;' . $userdata['session_id'] . '" class="gen">', '</a>'));
	}
}
// END submit

// BEGIN repair all
if(!strcmp($mode,'repairall'))
{
	// Retrieve table list:
	$sql = 'SHOW TABLE STATUS';
	if (!$result = $db->sql_query($sql))
	{
		// This makes no sense, the board would be dead... :P
		message_die(GENERAL_ERROR, $lang['SQL_Admin_Tables_Error']);
	}
	$tables = array();
	$counter = 0;
	while ($row = $db->sql_fetchrow($result))
	{
		$counter++;
		$tables[$counter] = $row['Name'];
	}
	$tablecount = $counter;

	// Repair All
	for ($i = 1; $i <= $tablecount; $i++)
	{
		$sql = 'REPAIR TABLE ' . $tables[$i];
		if (!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, sprintf($lang['SQL_Admin_Repair_Error'], $tables[$i]));
		}
	}

	// Successfully repaired all.
	message_die(GENERAL_MESSAGE, sprintf($lang['SQL_Admin_Repair_Done'], $tablecount, '<a href="' . $file . '" class="gen">', '</a>' , '<a href="index.php?pane=right&amp;' . $userdata['session_id'] . '" class="gen">', '</a>'));
}
// END repair all

// BEGIN optimize all
if(!strcmp($mode,'optimizeall'))
{
	// Retrieve table list:
	$sql = 'SHOW TABLE STATUS';
	if (!$result = $db->sql_query($sql))
	{
		// This makes no sense, the board would be dead... :P
		message_die(GENERAL_ERROR, $lang['SQL_Admin_Tables_Error']);
	}
	$tables = array();
	$counter = 0;
	while ($row = $db->sql_fetchrow($result))
	{
		$counter++;
		$tables[$counter] = $row['Name'];
	}
	$tablecount = $counter;

	// Optimize All
	for ($i = 1; $i <= $tablecount; $i++)
	{
		$sql = 'OPTIMIZE TABLE ' . $tables[$i];
		if (!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, sprintf($lang['SQL_Admin_Optimize_Error'], $tables[$i]));
		}
	}

	// Successfully optimized all.
	message_die(GENERAL_MESSAGE, sprintf($lang['SQL_Admin_Optimize_Done'], $tablecount, '<a href="' . $file . '" class="gen">', '</a>' , '<a href="index.php?pane=right&amp;' . $userdata['session_id'] . '" class="gen">', '</a>'));
}
// END optimize all

// Done with buttons...

// Any options given?

// BEGIN browse
if (!strcmp($mode, 'browse'))
// Browse a table...
{
	if (empty($tablename))
	// No table?
	{
		message_die(GENERAL_ERROR, $lang['SQL_Admin_No_Table']);
	}
	// Switch browse on:
	$template->assign_block_vars('switch_table_browse', array());

	// Build the SQL command:
	if (empty($sort))
	{
		$sort = 'ASC';
	}
	if (empty($first))
	{
		$first = 0;
	}
	if (!empty($order))
	{
		$sql = 'SELECT * FROM ' . $tablename . ' ORDER BY ' . $order . ' ' . $sort . ' LIMIT ' . $first . ', 30';
	}
	else
	{
		$sql = 'SELECT * FROM ' . $tablename . ' LIMIT 0, 30';
	}
	// Done building the SQL command, let's start...

	$result = $db->sql_query($sql);
	if (!$result)
	{
		// No results?
		message_die(GENERAL_ERROR, sprintf($lang['SQL_Admin_Browse_Error'], $tablename));
	}
	// Remember the number of fields (aka columns) and the number of rows:
	$field_count = $db->sql_numfields($result);
	$row_count = $db->sql_numrows($result);
	// The field header:
	for ($i = 0; $i < $field_count; $i++)
	{
		$field[$i] = $db->sql_fieldname($i, $result);
		$template->assign_block_vars('switch_table_browse.table_browse_fields', array(
			'TABLE_BROWSE_FIELD_ORDER' => $file . '&amp;mode=browse&amp;tablename=' . $tablename . '&amp;order=' . $db->sql_fieldname($i, $result),
			'TABLE_BROWSE_FIELD_NAME' => $db->sql_fieldname($i, $result))
		);
	}
	// Some general stuff (mostly language stuff):
	$template->assign_vars(array(
		'L_TABLE_BROWSE_DELETE' => $lang['Delete'])
	);
	// OK, we have the data... let's put it out to a thingy!
	$k = 0;
	while ($row = $db->sql_fetchrow($result))
	{
		$row_class = ( !($k % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

		$template->assign_block_vars('switch_table_browse.table_browse_data', array(
			'ROW_CLASS' => $row_class,
			'TABLE_BROWSE_DELETE' => $file . '&amp;mode=submit&amp;this_query=DELETE FROM ' . $tablename . ' WHERE ' . $field[0] . '=\'' . $row[$field[0]] . '\'&amp;confirm=yes')
		);
		for ($i = 0; $i < $field_count; $i++)
		{
			$template->assign_block_vars('switch_table_browse.table_browse_data.table_browse_data_field', array(
				'ROW_CLASS' => $row_class,
				'TABLE_BROWSE_DATA' => htmlspecialchars($row[$field[$i]]))
			);
		}
		$k++;
	}

	// Before building the browsing menu, be sure to pick something to browse by if nothing was specified so far:
	if (empty($order))
	{
		$order = $field[0];
	}
	
	// OK, let us build that menu...
	$template->assign_block_vars('switch_table_browse.table_browse_menu', array(
		'BROWSE_MENU_COLSPAN' => $field_count + 1,
		'FIRST_PAGE' => $file . '&amp;mode=browse&amp;tablename=' . $tablename . '&amp;first=0&amp;sort=' . $sort . '&amp;order=' . $order,
		'L_FIRST_PAGE' => $lang['SQL_Admin_First_Page'],
		'NEXT_PAGE' => $file . '&amp;mode=browse&amp;tablename=' . $tablename . '&amp;first=' . ($first + 30) . '&amp;sort=' . $sort . '&amp;order=' . $order,
		'L_NEXT_PAGE' => $lang['SQL_Admin_Next_Page'],
		'PREVIOUS_PAGE' => ($first - 30) > 0 ? $file . '&amp;mode=browse&amp;tablename=' . $tablename . '&amp;first=' . ($first - 30) . '&amp;sort=' . $sort . '&amp;order=' . $order : $file . '&amp;mode=browse&amp;tablename=' . $tablename . '&amp;first=0&amp;sort=' . $sort . '&amp;order=' . $order,
		'L_PREVIOUS_PAGE' => $lang['SQL_Admin_Prev_Page'],
		'SORT_ASC' => $file . '&amp;mode=browse&amp;tablename=' . $tablename . '&amp;first=' . $first . '&amp;sort=ASC&amp;order=' . $order,
		'L_SORT_ASC' => $lang['SQL_Admin_ASC_Word'],
		'SORT_DESC' => $file . '&amp;mode=browse&amp;tablename=' . $tablename . '&amp;first=' . $first . '&amp;sort=DESC&amp;order=' . $order,
		'L_SORT_DESC' => $lang['SQL_Admin_DESC_Word'])
	);
}
// END browse

// BEGIN structure
if (!strcmp($mode, 'showtable'))
// Show a specific table:
{
	if (empty($tablename))
	// No table specified...
	{
		message_die(GENERAL_ERROR, $lang['SQL_Admin_No_Table']);
	}

	// switch show on:
	$template->assign_block_vars('switch_table_structure', array());

	$sql = 'SHOW COLUMNS FROM ' . $tablename;

	$result = $db->sql_query($sql);
	if (!$result)
	{
		// Eeeeeh... no columns perhaps?
		message_die(GENERAL_ERROR, $lang['SQL_Admin_Columns_Error']);
	}
	// some general stuff:
	$template->assign_vars(array(
		'L_TABLE_STRUCTURE_TABLENAME' => sprintf($lang['SQL_Admin_Columns_Title'], $tablename),
		'L_TABLE_STRUCTURE_DROP' => $lang['SQL_Admin_Drop_Word'],
		'L_TABLE_STRUCTURE_FIELD' => $lang['SQL_Admin_Field_Word'],
		'L_TABLE_STRUCTURE_TYPE' => $lang['Type'],
		'L_TABLE_STRUCTURE_NULL' => $lang['SQL_Admin_Null_Word'],
		'L_TABLE_STRUCTURE_KEY' => $lang['SQL_Admin_Key_Word'],
		'L_TABLE_STRUCTURE_DEFAULT' => $lang['SQL_Admin_Default_Word'],
		'L_TABLE_STRUCTURE_EXTRA' => $lang['SQL_Admin_Extra_Word'])
	);
	// Found data (obviously), let's build an output...
	while ($row = $db->sql_fetchrow($result))
	{
		$template->assign_block_vars('switch_table_structure.actual_table_structure', array(
			'TABLE_STRUCTURE_DROP' => $file . '&amp;mode=submit&amp;this_query=ALTER TABLE ' . $tablename . ' DROP ' . $row['Field'] . '&amp;confirm=yes',
			'TABLE_STRUCTURE_FIELD' => $row['Field'],
			'TABLE_STRUCTURE_TYPE' => $row['Type'],
			'TABLE_STRUCTURE_NULL' => $row['Null'],
			'TABLE_STRUCTURE_KEY' => $row['Key'],
			'TABLE_STRUCTURE_DEFAULT' => $row['Default'],
			'TABLE_STRUCTURE_EXTRA' => $row['Extra'])
		);
	}
}
// END structure

// BEGIN table list
// Retrieving the currently existing tables:
$sql = 'SHOW TABLE STATUS';
$result = $db->sql_query($sql);
if (!$result)
{
	// This makes no sense, the board would be dead... :P
	message_die(GENERAL_ERROR, $lang['SQL_Admin_Tables_Error']);
}

// Start table list:
$totaldatalength = 0;
while ($row = $db->sql_fetchrow($result))
{
	$row_class = ( !($tablecount % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

	$template->assign_block_vars('table_list', array(
		'ROW_CLASS' => $row_class,
		'TABLE_NAME' => $row['Name'],
		'TABLE_STRUCTURE' => $file . '&amp;mode=showtable&amp;tablename=' . $row['Name'],
		'TABLE_BROWSE' => $file . '&amp;mode=browse&amp;tablename=' . $row['Name'],
		'TABLE_OPTIMIZE' => $file . '&amp;mode=submit&amp;this_query=OPTIMIZE TABLE ' . $row['Name'],
		'TABLE_REPAIR' => $file . '&amp;mode=submit&amp;this_query=REPAIR TABLE ' . $row['Name'],
		'TABLE_EMPTY' => (!strcmp($dbms, 'mysql3')) ? $file . '&amp;mode=submit&amp;this_query=DELETE FROM ' . $row['Name'] . '&amp;confirm=yes' : $file . '&amp;mode=submit&amp;this_query=TRUNCATE TABLE ' . $row['Name'] . '&amp;confirm=yes',
		'TABLE_DROP' => $file . '&amp;mode=submit&amp;this_query=DROP TABLE ' . $row['Name'] . '&amp;confirm=yes',
		'TABLE_TYPE' => $row['Type'],
		'TABLE_ROWS' => $row['Rows'],
		'TABLE_DATA_LENGTH' => $row['Data_length'],
		'TABLE_OPTIMIZATION_LEVEL' => $row['Data_length'] > 0 ? round(100 - ((100 * $row['Data_free']) / $row['Data_length']), 2) : 100)
	);
	
	$tablecount++;
	
	// Add this to the total data length:
	$totaldatalength += $row['Data_length'];
	$totaldatalength += $row['Index_length'];
}
// Make total data length string:
if ($totaldatalength < 1024)
{
	$totaldatalengthstring = $totaldatalength . ' Bytes';
}
elseif ($totaldatalength < (1024 * 1024))
{
	$totaldatalengthstring = round(($totaldatalength / 1024), 2) . ' Kb';
}
else
{
	$totaldatalengthstring = round(($totaldatalength / (1024 * 1024)), 2) . ' Mb';
}
//
$template->assign_vars(array(
	'TABLE_TITLE' => sprintf($lang['SQL_Admin_Tables_Title'], $tablecount, $totaldatalengthstring))
);
// END table list


//
// Optimize database cronfiguration
//
// If has been clicked the button configure
if( isset( $HTTP_POST_VARS['cron'] ) || isset( $HTTP_POST_VARS['only_tables'] ) )
{
	$sql = "UPDATE " . OPTIMIZE_DB_TABLE . " 
		SET show_tables = '" . $HTTP_POST_VARS['only_tables'] . "' ";
	if( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not configure show tables', '', __LINE__, __FILE__, $sql);
	}

	if ( isset( $HTTP_POST_VARS['cron'] ) )
	{
		// Update db optimize cronfiguration
		$sql = "UPDATE " . OPTIMIZE_DB_TABLE . " 
			SET cron_every = " . $HTTP_POST_VARS['cron_every'] . ", cron_enable = '"  . $HTTP_POST_VARS['enable_optimize_cron'] . "', cron_next = " . ( time() + $HTTP_POST_VARS['cron_every'] ) . ", show_tables = '" . $HTTP_POST_VARS['only_tables'] . "', empty_tables = '" . $HTTP_POST_VARS['empty_table'] . "'";
		if( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not optimize database cronfiguration', '', __LINE__, __FILE__, $sql);
		}
	}
}

	
$sql_opt = "SELECT * 
	FROM " . OPTIMIZE_DB_TABLE;
$opt_conf_result = $db->sql_query($sql_opt);

if( !( $opt_conf = $db->sql_fetchrow($opt_conf_result) ) )
{
	message_die(GENERAL_ERROR, 'Could not obtain db optimize configuration.', '', __LINE__, __FILE__, $sql);
}

switch ( $opt_conf['cron_every'] ) 
{
	case 2592000:
    	$month = 'selected="selcted"';
        break;
    case 1296000:
        $weeks2 = 'selected="selcted"';
        break;
    case 604800:
        $week = 'selected="selcted"';
        break;
	case 259200:
        $days3 = 'selected="selcted"';
        break;
	case 86400:
        $day = 'selected="selcted"';
        break;
	case 28800:
        $hours8 = 'selected="selcted"';
        break;
	case 14400:
        $hours4 = 'selected="selcted"';
        break;
	case 3600:
        $hour = 'selected="selcted"';
        break;
	case 1800:
        $minutes30 = 'selected="selcted"';
        break;
	case 30:
        $seconds30 = 'selected="selcted"';
        break;
	default: 
		$day = 'selected="selcted"';
}

// Select a cron every
$template->assign_block_vars('sel_cron_every', array(
	'30SECONDS' => $seconds30,
	'MONTH' => $month,
	'2WEEKS' => $weeks2,
	'WEEK' => $week,
	'3DAYS' => $days3,
	'DAY' => $day,
	'4HOURS' => $hours4,
	'8HOURS' => $hours8,
	'HOUR' => $hour,
	'30MINUTES' => $minutes30)
);

$opt_conf['cron_enable'] != 1 ? $enable_cron_no = 'checked="checked"' : $enable_cron_yes = 'checked="checked"';
$opt_conf['empty_tables'] != 1 ? $empty_table_no = 'checked="checked"' : $empty_table_yes = 'checked="checked"';
		
if ( $opt_conf['cron_enable'] != 1 || $opt_conf['cron_next'] == 0 )
{
	$next_cron = $lang['Not_available'];
	$performed_cron = $lang['Not_available'];
}
else 
{
	$next_cron = create_date($lang['DATE_FORMAT'], $opt_conf['cron_next'], $board_config['board_timezone']);
	$performed_cron = $opt_conf['cron_count'];
}

//
// assign some general stuff
//
$template->assign_vars(array(
	'L_TABLE_NAME' => $lang['SQL_Admin_Name_Word'],
	'L_TABLE_ACTIONS' => $lang['SQL_Admin_Actions_Word'],
	'L_TABLE_TYPE' => $lang['Type'],
	'L_TABLE_ROWS' => $lang['SQL_Admin_Rows_Word'],
	'L_TABLE_DATA_LENGTH' => $lang['SQL_Admin_Size'],
	'L_TABLE_OPTIMIZATION_LEVEL' => $lang['SQL_Admin_Optimization_Word'],
	'L_TABLE_STRUCTURE' => $lang['SQL_Admin_Structure_Word'],
	'L_TABLE_BROWSE' => $lang['SQL_Admin_Browse_Word'],
	'L_TABLE_OPTIMIZE' => $lang['Optimize'],
	'L_TABLE_REPAIR' => $lang['SQL_Admin_Repair_Word'],
	'L_TABLE_EMPTY' => $lang['SQL_Admin_Empty_Word'],
	'L_TABLE_DROP' => $lang['SQL_Admin_Drop_Word'],

	'L_CRON_TITLE' => $lang['Cron_title'],
	'L_ENABLE_CRON' => $lang['Optimize_Enable_cron'],
	'L_CRON_EVERY' => $lang['Optimize_Cron_every'],
	'L_NEXT_CRON_ACTION' => $lang['Optimize_Next_cron_action'],
	'NEXT_CRON' => $next_cron,
	'L_PERFORMED_CRON' => $lang['Optimize_Performed_Cron'],
	'PERFORMED_CRON' => $performed_cron,
	'L_EMPTY_TABLES' => sprintf($lang['Optimize_Empty_Table'], $table_prefix),
	'L_SHOW_TABLE' => $lang['Optimize_Show_Table'],
	'S_ENABLE_CRON_YES' => $enable_cron_yes,
	'S_ENABLE_CRON_NO' => $enable_cron_no,
	'S_EMPTY_TABLE_YES' => $empty_table_yes,
	'S_EMPTY_TABLE_NO' => $empty_table_no,
	
	'SQL_ACTION' => $file,
	'HEADER' => $lang['SQL_Admin_Title'],
	'QUERY_TITLE' => $lang['SQL_Admin_Query_Title'],
	'COPYRIGHT' => $lang['SQL_Admin_Copyright'],
	'OPTIMIZE_ALL_BUTTON' => $lang['SQL_Admin_Optimize_All_Button'],
	'REPAIR_ALL_BUTTON' => $lang['SQL_Admin_Repair_All_Button'],
	'L_ACTION' => $lang['Action'])
);

if ( $board_config['enable_ip_logger'] )
{
	$template->assign_block_vars('switch_ip_logger', array());
}

// parse body
$template->pparse('body');

// required stuff:
include('./page_footer_admin.'.$phpEx);

?>