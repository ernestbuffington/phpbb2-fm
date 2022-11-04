<?php
/***************************************************************************
 *							chatbox_admin.php
 *							-------------------
 *	begin				:	Sun July 08 2002
 *	copyright			:	(C) 2002 Smartor
 *	email				:	smartor_xp@hotmail.com
 *
 *	$Id: chatbox_admin.php,v 1.18b 2002/8/03, 20:15:39 hnt Exp $
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
$phpbb_root_path = '../../';
include_once($phpbb_root_path . 'extension.inc');
include_once($phpbb_root_path . 'common.'.$phpEx);
include_once($phpbb_root_path . '/mods/chatbox/chatbox_config.'.$phpEx);

error_reporting  (E_ERROR | E_WARNING | E_PARSE); // This will NOT report uninitialized variables
set_magic_quotes_runtime(0); // Disable magic_quotes_runtime

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_INDEX);
init_userprefs($userdata);
//
// End session management
//

// Check User Session
if (!$userdata['session_logged_in'])
{
	echo "Please login to chat";
	exit();
}

$nick = $userdata['username'];

?>

<html>
<head>

<title><?php echo $lang['ChatBox']; ?></title>

<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $lang['ENCODING']; ?>">

<link rel="stylesheet" href="<?php echo $chatbox_config['stylesheet']?>" type="text/css">

</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload="window.scrollTo(0,99999);" link="#006699">


<form name="post" action="<?php echo append_sid('messenger_view.'.$phpEx); ?>" target="ekran" method="POST" autocomplete=off onsubmit="submitonce()">

<table class="formarea" width="100%" height="100%">
	<tr>
		<td>
		<table class="formarea" width="100%" height="100%">
		<tr>
		<table class="formarea" width="100%" height="100%">
		<th>
		Chatbox Options
		</th>
		</table>
		</tr>
		<tr height="100%">
		<td nowrap Align="Center" valign="middle">
		Feature Not Emplemented Yet.
		</td>
		<tr>
		<td>
		<a href="<?php echo append_sid("messenger_view.php"); ?>" target="ekran">Back to Chat<a>
		</td>
		</tr>
		</tr>
		</table>
		</td>
	</tr>
</table>
</form>
</body>
</html>