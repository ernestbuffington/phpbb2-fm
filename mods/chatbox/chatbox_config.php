<?php
/***************************************************************************
 *							chatbox_config.php
 *							-------------------
 *	begin				:	Sun July 07 2002
 *	copyright			:	(C) 2002 Smartor
 *	email				:	smartor_xp@hotmail.com
 *
 *	$Id: chatbox_config.php,v 1.18b 2002/8/03, 20:24:31 hnt Exp $
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

if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}

error_reporting(E_ERROR | E_WARNING | E_PARSE);

include_once($phpbb_root_path . './mods/chatbox/chatbox_function.'.$phpEx);

define("_CHATBOX_VERSION", "3.1"); // DO NOT CHANGE THIS

define("_CHATBOX_SYSTEM_MSG", "<span style='color: red'>System Msg</span>");

$cfg_chatname = $board_config['sitename'];

$chatbox_config = array (
	'refresh_time'	=>	'10',
	'delete_time'	=>	'300',
	'offline_time'	=>	'300',
	'away_time'		=>	'150',
	'stylesheet'	=>	'chatbox.css',
	'show_amount'	=>	'100',	// amount of chats to show
	'max_msg_len'	=>	'300',
	'direction'		=>	'1',	// 1 = new posts at bottom,  others = new posts at the top
);

?>