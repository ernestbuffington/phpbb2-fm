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
 *                            link_link.php
 *                           ---------------
 *   Modified by CRLin
 ***************************************************************************/

class linkdb_link extends linkdb_public
{
	function main($action)
	{
		global $_REQUEST, $lang, $db, $user_ip;

		if ( isset($_REQUEST['link_id']) )
		{
			$link_id = intval($_REQUEST['link_id']);
		}
		else
		{
			message_die(GENERAL_MESSAGE, $lang['Link_not_exist']);
		}
		
		$sql = 'SELECT *
			FROM ' . LINKS_TABLE . "
			WHERE link_id = $link_id
			AND link_approved = 1";

		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Couldnt select linkdb', '', __LINE__, __FILE__, $sql);
		}

		//
		// Id doesn't match with any link in the database another nice error message
		//
		if(!$file_data = $db->sql_fetchrow($result))
		{
			message_die(GENERAL_MESSAGE, $lang['Link_not_exist']);
		}
		
		$db->sql_freeresult($result);
		
		$link_url = $file_data['link_url'];

		//if($user_ip != $file_data['last_user_ip'])
		//{
			//
			// Update counter
			//
			$link_hits = intval($file_data['link_hits']) + 1;
			$sql = 'UPDATE ' . LINKS_TABLE . " 
				SET link_hits = $link_hits, last_user_ip = '$user_ip' 
				WHERE link_id = $link_id";

			if ( !($db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Couldnt Update Links table', '', __LINE__, __FILE__, $sql);
			}
		//}	
		
		// header("Location: $link_url");
		echo '<script>location.replace("' . $link_url . '")</script>';
		exit();
	}
}