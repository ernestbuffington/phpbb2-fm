<?php
/*
  paFileDB 3.0
  ©2001/2002 PHP Arena
  Written by Todd
  todd@phparena.net
  http://www.phparena.net
  Keep all copyright links on the script visible
  Please read the license included with this script for more information.
*/

/***************************************************************************
 *                            functions.php
 *                           ---------------
 *   Modified by CRLin
 ***************************************************************************/

if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}

class linkdb_functions
{
	function set_config($config_name, $config_value)
	{
		global $db, $linkdb_config;
		
		$sql = "UPDATE " . LINK_CONFIG_TABLE . " SET
			config_value = '" . str_replace("\'", "''", $config_value) . "'
			WHERE config_name = '$config_name'";
		if( !$db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, "Failed to update linkdb configuration for $config_name", "", __LINE__, __FILE__, $sql);
		}

		if (!$db->sql_affectedrows() && !isset($linkdb_config[$config_name]))
		{
			$sql = 'INSERT INTO ' . LINK_CONFIG_TABLE . " (config_name, config_value)
				VALUES ('$config_name', '" . str_replace("\'", "''", $config_value) . "')";
			if( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Failed to update linkdb configuration for $config_name", "", __LINE__, __FILE__, $sql);
			}
		}

		$linkdb_config[$config_name] = $config_value;
	}

	function linkdb_config() 
	{
		global $db;

		$sql = "SELECT * 
			FROM " . LINK_CONFIG_TABLE;
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Couldnt query linkdb configuration', '', __LINE__, __FILE__, $sql);
		}
	
		while ($row = $db->sql_fetchrow($result))
		{
			$linkdb_config[$row['config_name']] = trim($row['config_value']);
		}

		$db->sql_freeresult($result);

		return ($linkdb_config);
	}

	function obtain_ranks(&$ranks)
	{
		global $db;

		$sql = "SELECT *
			FROM " . RANKS_TABLE . "
			ORDER BY rank_special, rank_min DESC";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, "Could not obtain ranks information.", '', __LINE__, __FILE__, $sql);
		}

		$ranks = array();
		while ( $row = $db->sql_fetchrow($result) )
		{
			$ranks[] = $row;
		}
		$db->sql_freeresult($result);
	}

	function sql_query_limit($query, $total, $offset = 0)
	{
		global $db;
		
		$query .= ' LIMIT ' . ((!empty($offset)) ? $offset . ', ' . $total : $total);
		return $db->sql_query($query);
	}

	function get_rating($link_id, $link_rating = '')
	{
		global $db, $lang;
	
		$sql = "SELECT AVG(rate_point) AS rating 
			FROM " . LINK_VOTES_TABLE . " 
			WHERE votes_link = '" . $link_id . "'";
		if(!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Couldnt rating info for the giving link', '', __LINE__, __FILE__, $sql);
		}

		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);
		$link_rating = $row['rating'];

		return ($link_rating != 0) ? round($link_rating, 2) . ' / 10' : $lang['Not_rated'];
	}

	function update_voter_info($link_id, $rating)
	{
		global $user_ip, $db, $userdata, $lang;
		
		$where_sql = ( $userdata['user_id'] != ANONYMOUS ) ? "user_id = '" . $userdata['user_id'] . "'" : "votes_ip = '" . $user_ip . "'";
		
		$sql = "SELECT user_id, votes_ip 
			FROM " . LINK_VOTES_TABLE . " 
			WHERE $where_sql
			AND votes_link = '" . $link_id . "'
			LIMIT 1";
		if(!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Couldnt Query User id', '', __LINE__, __FILE__, $sql);
		}
		
		if(!$db->sql_numrows($result))
		{
			$sql = "INSERT INTO " . LINK_VOTES_TABLE . " (user_id, votes_ip, votes_link, rate_point) 
				VALUES ('" . $userdata['user_id'] . "', '" . $user_ip . "', '" . $link_id . "','" . $rating . "')";
			if(!($db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'Couldnt Update Votes Table Info', '', __LINE__, __FILE__, $sql);
			}
		}
		else
		{
			message_die(GENERAL_MESSAGE, $lang['Rerror']);
		}
		
		$db->sql_freeresult($result);
	}	
}

?>