<?php
/***************************************************************************
 *                         PJIRC MOD chat_help.php
 *                            -------------------
 *   begin                : 16. july 2004
 *   copyright            : Midnightz / AlleyKat
 *   email                :
 *
 *   $Id: chat_help.php, v 1.0.0 2004/07/16 Midnightz
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
$userdata = session_pagestart($user_ip, PAGE_CHATROOM); 
init_userprefs($userdata); 
// 
// End session management 
// 

// Pull the user template 
if ( !$userdata['session_logged_in'] ) 
{ 
	$currentstyle = $board_config['default_style']; 
} 
else 
{ 
	$currentstyle = $userdata['user_style']; 
} 

$sql = "SELECT template_name, head_stylesheet 
	FROM " . THEMES_TABLE . " 
    WHERE themes_id = " . $currentstyle; 
if( !($result = $db->sql_query($sql)) ) 
{ 
	message_die(GENERAL_ERROR, 'Could not query template information.', '', __LINE__, __FILE__, $sql); 
} 
while ($row = $db->sql_fetchrow($result)) 
{ 
	$currenttemplatename = $row['template_name']; 
	$currenttemplatecss = $row['head_stylesheet']; 
} 

$currentcssfile = 'templates/' . $currenttemplatename . '/' . $currenttemplatecss; 

?> 
<html> 
<head> 
<link rel="stylesheet" href="<?php echo $currentcssfile ?>" type="text/css" /> 
<title>PJIRC Chat Room Help</title> 
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"> 
</head>
<table width="100%" cellspacing="1" cellpadding="4" class="forumline">
<tr>
	<th class="thHead">PJIRC Chat Room Help</td>
</tr>
<tr>
	<td class="row1"><span class="gensmall">
    <b>Registering your nickname/phpBB username with Blitzed.org:</b><br />
    /msg NickServ REGISTER YourPassword YourEmail<br />
    <br />
    <b>Optional Commands for your phpBB profile for auto-login:</b><br />
    /msg NickServ Identify YourPassword<br />
    /msg ChanServ OP #ChannelName YourNickname/Username (note: sets operator status)<br />
	/msg ChanServ VOICE #ChannelName YourNickname/Username (note: sets voice status)<br />
    /beep<br />
    <br />
	<b>Registering your channelname/sitename:</b><br />
    /msg ChanServ REGISTER #DesiredChannelName ChannelPassword :: Desired Description ::<br />
    <br />
    <b>Setting Founder of channel:</b><br />
    /msg ChanServ IDENTIFY #ChannelName ChannelPassword<br />
	<br />
    <b>Setting Channel Description, Entry Message & Channel Topic:</b><br />
    /msg ChanServ SET #ChannelName DESC :: Desired Description ::<br />
    /msg ChanServ SET #ChannelName ENTRYMSG :: Desired Message ::<br />
    /msg ChanServ SET #ChannelName TOPIC :: Desired Topic ::<br />
   	<br />
	<b>Connecting to additional server/channel:</b><br />
    (Note: multi-server must be enabled in ACP)<br />
    /newserver servername serveraddress port<br />
    /join #channel<br />
    <br />
    <b>Switching from default server/channel to another:</b><br />
    (Note: multi-server must be enabled in ACP)<br />
    /server servername port<br />
    /join #channel<br />
    <br />
    <a href="http://www.blitzed.org/help/services/index.phtml" target="_parent">Basic IRC Blitzed.org Network Help</a><br />
    <a href="http://www.blitzed.org/docs/" target="_parent">Blitzed.org Network Policies</a><br />
    <a href="http://www.blitzed.org/help/banned.phtml" target="_parent">Basic Blitzed.org Banning Info</a><br />
    <a href="http://www.blitzed.org/help/services/nickserv.phtml" target="_parent">Blitzed.org NickServ Extended Help</a><br />
    <a href="http://www.blitzed.org/help/services/chanserv.phtml" target="_parent">Blitzed.org ChanServ Extended Help</a><br />
    <a href="http://www.blitzed.org/help/services/memoserv.phtml" target="_parent">Blitzed.org MemoServ Extended Help</a><br />
    <a href="http://www.blitzed.org/docs/help.php" target="_parent">Blitzed.org - Help Them Out</a>
    </span></td>
</tr>
<tr>
	<td class="catBottom">&nbsp;</td>
</tr>
</table>
</body>
</html>
