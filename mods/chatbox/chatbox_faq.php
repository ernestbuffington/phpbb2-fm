<?php
/***************************************************************************
 *							chatbox_faq.php
 *							-------------------
 *	begin				:	Tue Feb 16 2003
 *	copyright			:	(C) 2003 ArchonPheonix
 *	email				:	archonpheonix@hotmail.com
 *
 *	$Id: chatbox_faq.php,v 1.18b 2003/02/16, 20:15:39 hnt Exp $
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
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" link="#006699">

<table class="formarea" width="100%" height="100%"><form name="post" action="<?php echo append_sid('messenger_view.'.$phpEx); ?>" target="ekran" method="POST" autocomplete=off onsubmit="submitonce()">
<tr>
	<td><table class="formarea" width="100%" height="100%">
	<tr>
		<td><table class="formarea" width="100%" height="100%">
		<tr>
			<th>ChatBox - FAQ</th>
		</tr>
		</table></td>
	</tr>
	<tr height="100%">
		<td align="left" valign="top" >
		<b><u>How do i use Smilies?</u></b>
		<br />Smilies can be used in the chat just like in the boards. Simply put in the smiley code and it will be displayed. If you dont know all the smilies codes then click the smilies link in the submit bar to chose a smiley. Clicking on a smiley will add its code to the message box.
		<br /><br />
		<b><u>What is User Text Color?</u></b>
		<br />User text color allows users to select a color for their text. users can set their text to any color they like using one of 2 methods.<br />1. Using Hex Values. e.g. #800000 for dark red.<br />2. Using single word color description. e.g. Red, Blue, Green, or Lime.
		<br /><br />
		<b><u>How do i Send Normal Messages?</u></b>
		<br />Enter your message into the message field (located next to the color box) and either press enter or click send.
		<br /><br />
		<b><u>Why can i not use HTML code?</u></b>
		<br />Html is not allowed due to possible exploitation and script includes.
		<br /><br />
		<b><u>How do i Send URL links?</u></b>
		<br />Enter the address into the message field and click the "send URL" button. Do not include "http://" in the URL because it is automatically added for you. All links open up in a new window.<br /><br /><b>Note:</b> Clicking on a url is at your own risk. This site is not resposible for the contents of sites visited by a hyperlink.
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