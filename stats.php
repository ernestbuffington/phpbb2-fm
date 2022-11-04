<?php
 /***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/
define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);
$topics = get_db_stat('topiccount'); $posts = get_db_stat('postcount'); $users = get_db_stat('usercount'); 
echo "<table><tr><td><div>" . $board_config['sitename'] . "|" . $board_config['site_desc'] . "|" . $board_config['cookie_secure'] . "|" . $board_config['server_name'] . "|" . $board_config['script_path'] . "|" . $board_config['server_port'] . "|" . $board_config['board_startdate'] . "|" . $topics . "|" . $posts . "|" . $users . "</div></td></tr></table>" . $board_config['fm_version'];
?>