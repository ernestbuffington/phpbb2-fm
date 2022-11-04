<?php
/***************************************************************************
 *							chatbox_about.php
 *							-------------------
 *	begin				:	Tue Feb 16 2003
 *	copyright			:	(C) 2003 Wooly Spud
 *	email				:	Wooly Spud@xgmag.com
 *
 *	$Id: chatbox_about.php,v 1.18b 2003/02/16, 20:15:39 hnt Exp $
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


<table class="formarea" width="100%" height="100%"><form name="post" action="<?php echo append_sid('messenger_view.'.$phpEx); ?>" target="ekran" method="POST" autocomplete=off onsubmit="submitonce()">
<tr>
	<td><table class="formarea" width="100%" height="100%">
	<tr>
		<td><table class="formarea" width="100%" height="100%">
		<tr>
			<th>About Chatbox</th>
		</tr>
		</table></td>
		</tr>
		<tr height="100%">
			<td Align="left" valign="middle">Original Chatbox through version 1.9 Created By <a href="http://smartor.is-root.com" target="_blank">Smartor</a><br />ChatBox v3.0 Modified by Richard McConnell
			<p><b>New in Version 2.0:</b>
			<ul>
			  <li>Message box auto focus'</li>
			</ul>
			<p><b>New in Version 3.0:</b>
			<ul>
			  <li>Modified User interface to include a title/tool bar.</li>
			  <li>Ability to send url's via a new send URL button.</li>
			  <li>Ability for users to set a color for their text.</li>
			  <li>Added the ability to manually reload the view topic page.</li>
			  <li>Added an About Page.</li>
			  <li>Added an FAQ.</li>
			</ul>
			</td>
		</tr>
		<tr>
			<td><a href="<?php echo append_sid("messenger_view.php"); ?>" target="ekran">Back to Chat<a></td>
		</tr>
		</table></td>
</tr>
</form></table>
</body>
</html>