<?php
/** 
*
* @package toplist
* @version $Id: toplist_image.php,v 1.3.8 2004/07/11 16:46:15 wyrihaximus Exp $
* @copyright (c) 2003 Cees-Jan Kiewiet
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

@set_time_limit(0);

define('IN_PHPBB', true);
$phpbb_root_path = '../../';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);
include($phpbb_root_path . 'mods/toplist/toplist_common.'.$phpEx);

register_shutdown_function('toplist_on_exit_image');
@set_time_limit(30);
$img = $phpbb_root_path . '/images/spacer.gif';
$socked = false;

$url_parts = @parse_url($HTTP_GET_VARS['url']);

if (empty($url_parts["host"]))
{
	$socked = false;
}
else
{
	if (!empty($url_parts["path"]))
	{
		$documentpath = $url_parts["path"];
	}
	else
	{
		$documentpath = '/';
	}
	
	if (!empty($url_parts["query"]))
	{
		$documentpath .= '?' . $url_parts["query"];
	}
	
	$host = $url_parts["host"];
	$port = $url_parts["port"];
	
	if(empty($port))
	{
		$port = 80;
	}
	
	$socket = @fsockopen($host, $port, $errno, $errstr, 30);
	
	if (!$socket)
	{
		$socked = false;
	}
	else
	{
		@fwrite($socket, "HEAD " . $documentpath . " HTTP/1.0\r\nHost: $host\r\n\r\n");
		$http_response = @fgets($socket, 22);
           	
		if(ereg("200 OK", $http_response, $regs))
		{
			$img = rawurldecode($HTTP_GET_VARS['url']);
			@fclose($socket);
			$socked = true;
		}
		else
		{
			$socked = false;
		}
	}
}

if (function_exists('getimagesize') && $board_config['toplist_dimensions'] != '' && !$socked)
{
	if ($size = getimagesize(rawurldecode($HTTP_GET_VARS['url'])))
	{	
		$dimensions = explode('#', $board_config['toplist_dimensions']);
		
		for ($i = 0; $i < sizeof($dimensions); $i++)
		{
			$image_dimensions = explode('x', $dimensions[$i]);
			
			if (($image_dimensions[0] == $size[0] && $image_dimensions[1] == $size[1]) || ($size[0] == 1 && $size[1] == 1))
			{
				$img = rawurldecode($HTTP_GET_VARS['url']);
				break;
			}
		}
	}
}
else if (!$socked)
{
	$file = @fopen(rawurldecode($HTTP_GET_VARS['url']), 'r');
	
	if($file)
	{
		$img = rawurldecode($HTTP_GET_VARS['url']);
	}
	else
	{
		$file = @fopen(rawurldecode($HTTP_GET_VARS['url']), 'rb');
		
		if ($file)
		{
			$img = rawurldecode($HTTP_GET_VARS['url']);
		}
	}
}

header("Location: " . $img);

?>