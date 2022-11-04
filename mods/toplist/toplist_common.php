<?php
/** 
*
* @package toplist
* @version $Id: common.php,v 1.3.8 2004/07/11 16:46:15 wyrihaximus Exp $
* @copyright (c) 2003 Cees-Jan Kiewiet
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}

define('IMG', 'img');
define('OUT', 'out');
define('HIN', 'hin');

$location = 'http' . (($board_config['cookie_secure']) ? 's' : '') . '://' . $board_config['server_name'] . $board_config['script_path'];
$sid = $userdata['session_id'];

$sql_array = array();

include($phpbb_root_path . 'includes/functions_toplist.'.$phpEx);

?>