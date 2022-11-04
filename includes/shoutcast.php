<?php
/***************************************************************************
 *                                shoutcast.php
 *                            -------------------
 *   begin                : Saturday, September 16, 2006
 *   copyright            : (C) 2005 - 2006 DerEine
 *   email                : info@orionmods.de
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
$phpbb_root_path = '../';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.' . $phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_INDEX);
init_userprefs($userdata);
//
// End session management
//

$gen_simple_header = TRUE;
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

/*
$template->set_filenames(array( 
      'body' => 'shoutcast_body.tpl')
); 

*/

$server2 = $board_config['shoutcast_server'];
$server2port = $board_config['shoutcast_port'];
$server2pass = $board_config['shoutcast_pass']; 

echo '<table height="100%" width="100%" cellpadding="0" cellspacing="0">
<tr>
	<td class="row1" valign="top">';

$dateix = @fsockopen('$server2', $server2port, &$errno, &$errstr);
if (!$dateix)
{
	@fclose($dateix);
}
else
{
	fputs($dateix,"GET /admin.cgi?pass=$server2pass&mode=viewxml HTTP/1.0\r\nUser-Agent: Mozilla/4.0 (compatible; MSIE 4.01; Windows NT;)\r\n\r\n");
	while (!(feof($dateix)))
	{
		$zeilex .= @fgets($dateix, 4096);
	}
	@fclose($dateix);
}

$tmpx = explode('<CURRENTLISTENERS>', $zeilex);
$tmpx = explode('</CURRENTLISTENERS>', $tmpx[1]);
$server2lauscher = $tmpx[0];
$pl2 = ' ';
$tmpx = explode('<SERVERGENRE>', $tmpx[1]);
$tmpx = explode('</SERVERGENRE>', $tmpx[1]);
$tmp2x = explode('*', $tmpx[0]);
$nick2 = $tmp2x[0];

if(count($tmp2x) == 2)
{
	$pl2 = $tmp2x[1];
}

$tmpx = explode('<SERVERTITLE>', $tmpx[1]);
$tmpx = explode('</SERVERTITLE>', $tmpx[1]);
$server2title = $tmpx[0];
$tmpx = explode('<SONGTITLE>', $tmpx[1]);
$tmpx = explode('</SONGTITLE>', $tmpx[1]);
$song2title = $tmpx[0];
$tmpx = explode('<STREAMSTATUS>', $tmpx[1]);
$tmpx = explode('</STREAMSTATUS>', $tmpx[1]);
$stream2status = $tmpx[0];

echo '<div align="right">
<a href="' . append_sid('shoutcast.'.$phpEx) . '"><img src="../images/teamspeak/refresh.gif" width="16" height="16" border"0" alt="' . $lang['Refresh'] . '" title="' . $lang['Refresh'] . '" /></a>
	
</div><div align="center">';

if ($stream2status == 1)
{
	// OnAir?
	echo '<img src="' . $phpbb_root_path . $images['icon_online'] . '" alt="' . $lang['Online'] . '" title="' . $lang['Online'] . '" />';
	// Moderator?
	echo '<br />' . $lang['Moderator'] . ': <b>' . $nick2 . '</b><br />';

	// Playlist?
	if ($pl2 == '')
	{
		echo '';
	}
	else
	{
		echo '<a href="' . $pl2 . '" target="_blank"></a>';
	}

	//Was wird gespielt?
	echo $lang['Shoutcast_track'] . ':<br /><b>' . $song2title . '</b><br />';
	echo $server2lauscher . '<br />';
	echo '<a href="http://' . $server2 . ':' . $server2port . '/listen.pls">' . $lang['Shoutcast_listen'] . '</a>';
}
else
{
	//OffAir?
	echo '<img src="' . $phpbb_root_path . $images['icon_offline'] . '" alt="' . $lang['Offline'] . '" title="' . $lang['Offline'] . '" />';
}

echo '</div></td>
	</tr>
</table>';

?>