<?php
/** 
*
* @package includes
* @version $Id: db_optimize.php,v 1.0.0 2003/09/23 09:27:30 sko22 Exp $
* @copyright (c) 2003 Sko22
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
	
if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}

//
// Optimize database config
//
$sql = "SELECT * 
	FROM " . OPTIMIZE_DB_TABLE;
$opt_result = $db->sql_query($sql);

if(!($opt_row = $db->sql_fetchrow($opt_result)))
{
	message_die(GENERAL_ERROR, 'Could not obtain db optimize configuration.', '', __LINE__, __FILE__, $sql);
}

if ( $opt_row['cron_enable'] )
{
	$current_time = time();
	
	//
	// Check cron next 
	//
	if ( ($opt_row['cron_next'] <= $current_time) )
	{
		ignore_user_abort();

		//
		// Get tables list
		//
		$list = mysql_list_tables($dbname); 

		while ($tab = $db->sql_fetchrow($list)) 
		{ 
			//
			// Optimize tables 
			//
			$sql = "OPTIMIZE TABLES $tab[0]"; 
			if (!$result = $db->sql_query($sql))
			{ 
				message_die(GENERAL_ERROR, 'Could not optimize database via cron job.', '', __LINE__, __FILE__, $sql);
			} 
		} 

		$sql = "UPDATE " . OPTIMIZE_DB_TABLE . " 
			SET cron_next = " . ( $current_time + $opt_row['cron_every'] ) . ", cron_count = cron_count + 1";
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not update next optimize cron job time.', '', __LINE__, __FILE__, $sql);
		}

		if ( $opt_row['empty_tables'] )
		{
			$sql = "TRUNCATE TABLE " . IP_TABLE;
			if (!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'Could not truncate _ip table.', '', __LINE__, __FILE__, $sql);
			}
		}	
	}
}

?>