<?php
/** 
*
* @package includes
* @version $Id: referers.php,v 0.2.0 11/03/2003, 22:13:02 NKieTo Exp $
* @copyright (c) 2002 NKieTo
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}

if ($_SERVER['HTTP_REFERER'] && !eregi($_SERVER['HTTP_HOST'] . $board_config['script_path'], $_SERVER['HTTP_REFERER'])) 
{
	$referer_url = phpbb_clean_username($_SERVER['HTTP_REFERER']);
	$referer_host = substr($referer_url, strpos($referer_url, '//') + 2);
	
	if (strpos($referer_host, '/') === false)
	{
		$referer_url .= '/';
	}
	else
	{
		$referer_host = substr($referer_host, 0, strpos($referer_host, '/'));
	}
	
	if (substr($referer_host, -1) == '.')
	{
		$referer_host = substr($referer_host, 0, -1);
	}	
    
    $sql = "SELECT * 
    	FROM " . REFERERS_TABLE . " 
		WHERE referer_url = '$referer_url'";
	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Could not get referers information", "", __LINE__, __FILE__, $sql);
	}
	$row = $db->sql_fetchrow($result);

	if (!$row)
	{
		$sql = "INSERT INTO " . REFERERS_TABLE . " (referer_host, referer_url, referer_ip, referer_hits, referer_firstvisit, referer_lastvisit) 
			VALUES ('$referer_host', '$referer_url', '$user_ip', 1, " . time() . ", " . time() . ")";
		if (!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Couldn't insert new referer", "", __LINE__, __FILE__, $sql);
		}
	}
	else 
	{
		$sql = "UPDATE " . REFERERS_TABLE . " 
			SET referer_hits = referer_hits + 1, referer_lastvisit = " . time() . ", referer_ip = '$user_ip' 
			WHERE referer_url = '$referer_url'"; 
		if (!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Couldn't update referers information", "", __LINE__, __FILE__, $sql);
		}
	}
	$db->sql_freeresult($result);
}

?>