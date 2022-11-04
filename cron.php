<?php
/***************************************************************************
 *                                cron.php
 *                            -------------------
 *   begin                : Thursday, Aug 31, 2006
 *   copyright            : (C) 2006 Omar Ramadan
 *   email                : princeomz2004@hotmail.com
 *
 ***************************************************************************/

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

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_INDEX);
init_userprefs($userdata);
//
// End session management
//

if (!$board_config['enable_autobackup'])
{
	message_die(GENERAL_MESSAGE, $lang['Autobackup_disabled']);
}

include($phpbb_root_path . 'includes/cron.'.$phpEx);

header("Cache-control: no-cache");
header("Content-Type: image/gif");

echo base64_decode("R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==");

exit;

?>