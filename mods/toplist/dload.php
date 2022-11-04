<?php
/** 
*
* @package toplist
* @version $Id: dload.php,v 1.3.8 2004/07/11 16:46:15 wyrihaximus Exp $
* @copyright (c) 2003 Cees-Jan Kiewiet
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

@set_time_limit(0);

define('IN_PHPBB', true);
$phpbb_root_path = '../../';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);
include($phpbb_root_path . 'mods/toplist/toplist_common.'.$phpEx);

toplist_startup();
toplist_antiflood_startup();

if (!toplist_get_anti_flood_data(HIN, $HTTP_GET_VARS['id'], encode_ip($user_ip)))
{
	$db->sql_query("UPDATE " . TOPLIST_TABLE . " 
		SET " . HIN . " = " . HIN . " + 1 
		WHERE id = " . $HTTP_GET_VARS['id']);
	toplist_set_anti_flood_data(HIN, $HTTP_GET_VARS['id'], $user_ip);
}

header("Location: http://" . $board_config['server_name'] . $board_config['script_path'] . "toplist.".$phpEx);
exit;

?>