<?php
/***************************************************************************
 *							messenger_send.php
 *							-------------------
 *	begin				:	Sun July 08 2002
 *	copyright			:	(C) 2002 Smartor
 *	email				:	smartor_xp@hotmail.com
 *
 *	$Id: messenger_send.php,v 1.18b 2002/8/03, 20:15:39 hnt Exp $
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
<script language="JavaScript">
<!--
function clear_text1()
{
	if (document.post.message.value == "Enter Message")
	{
		document.post.message.value = "";
	}
}

function clear_text2()
{
	if (document.post.color.value == "<Color>")
	{
		document.post.color.value = "";
	}
}

function submitmsg()
{
	document.post.url.value =  "";
	document.post.sent.value = document.post.message.value;
	document.post.message.value = "";
	document.post.message.focus();
}

function submiturl()
{
	document.post.sent.value =  "";
	document.post.url.value = document.post.message.value;
	document.post.message.value = "";
	document.post.submit();
	document.post.url.value =  "";
	document.post.message.focus();
}

//-->
</script>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload="window.scrollTo(0,99999);" link="#006699">

<table class="formarea" width="100%"><form name="post" action="<?php echo append_sid('messenger_view.'.$phpEx); ?>" target="ekran" method="POST" autocomplete=off onsubmit="submitmsg()">
<input type="hidden" name="nick" value="<?php echo $nick; ?>">
<tr>
	<td align="left"><a href="javascript:void(0);" onclick="window.open('../../posting.php?mode=smilies', '_chatboxsmilies', 'HEIGHT=300,resizable=yes,scrollbars=yes,WIDTH=275');">Smilies</a>&nbsp;</td>
	<td align="right">
		Color: <input type="text" name="color" size="10" maxlength="10" value="#000000" class="editbox">&nbsp;
		<input type="text" name="message" size="35" maxlength="<?php echo $chatbox_config['max_msg_len']; ?>" value="Enter Message" onFocus="clear_text1()" <?php if (isset($chatbox_config['vietuni'])) echo " onkeyup='initTyper(this);'"; ?> class="editbox">&nbsp;
		<input type="hidden" name="sent" value="">
		<input type="hidden" name="url" value="">
		<input type="submit" name="submit_button" value="<?php echo $lang['Send']; ?>" class="button">
		<input type="button" onclick="submiturl();" name="submit_url" value="Send URL" class="button">
	</td>
</tr>
</form></table>
</body>
</html>
