<?php
/** 
*
* @package phpBB2
* @version $Id: functions_toplist.php,v 1.3.8 2004/07/11 16:46:15 wyrihaximus Exp $
* @copyright (c) 2003 Cees-Jan Kiewiet
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*/

if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}

function toplist_startup()
{
	global $db, $board_config, $phpbb_root_path;
		
	if ( $board_config['toplist_prune_img_hits_interval'] != 0 )
	{
		if ( $board_config['toplist_prune_img_hits_last'] < (time() - $board_config['toplist_prune_img_hits_interval']) )
		{
			$sql = "UPDATE " . CONFIG_TABLE . " 
				SET toplist_prune_img_hits_interval = 0, toplist_prune_img_hits_last = " . time();
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not update toplist img', '', __LINE__, __FILE__, $sql);
			}
		
			// Remove cache file
			@unlink($phpbb_root_path . 'cache/config_board.'.$phpEx);
		}
	}

	if ( $board_config['toplist_prune_out_hits_interval'] != 0 )
	{
		if ( $board_config['toplist_prune_out_hits_last'] < (time() - $board_config['toplist_prune_out_hits_interval']) )
		{
			$sql = "UPDATE " . CONFIG_TABLE . " 
				SET toplist_prune_out_hits_interval = 0, toplist_prune_out_hits_last = " . time();
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not update toplist out', '', __LINE__, __FILE__, $sql);
			}

			// Remove cache file
			@unlink($phpbb_root_path . 'cache/config_board.'.$phpEx);
		}
	}
	
	if ( $board_config['toplist_prune_hin_hits_interval'] != 0 )
	{
		if ( $board_config['toplist_prune_hin_hits_last'] < (time() - $board_config['toplist_prune_hin_hits_interval']))
		{
			$sql = "UPDATE " . CONFIG_TABLE . " 
				SET toplist_prune_hin_hits_interval = 0, toplist_prune_hin_hits_last = " . time();
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not update toplist hin', '', __LINE__, __FILE__, $sql);
			}

			// Remove cache file
			@unlink($phpbb_root_path . 'cache/config_board.'.$phpEx);
		}
	}
}

function toplist_antiflood_startup()
{
	global $db, $board_config;
	
	$sql = "DELETE FROM " . TOPLIST_ANTI_FLOOD_TABLE . " 
		WHERE type = '" . IMG . "' 
			AND time < " . (time() - $board_config['toplist_anti_flood_img_hits_interval']);
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not delete toplist information (img)', '', __LINE__, __FILE__, $sql);
	}
	
	$sql = "DELETE FROM " . TOPLIST_ANTI_FLOOD_TABLE . " 
		WHERE type = '" . HIN . "' 
			AND time < " . (time() - $board_config['toplist_anti_flood_hin_hits_interval']);
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not delete toplist information (hin)', '', __LINE__, __FILE__, $sql);
	}
	
	$sql = "DELETE FROM " . TOPLIST_ANTI_FLOOD_TABLE . " 
		WHERE type = '" . OUT . "' 
			AND time < " . (time() - $board_config['toplist_anti_flood_out_hits_interval']);
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not delete toplist information (out)', '', __LINE__, __FILE__, $sql);
	}
}

function toplist_get_anti_flood_data($type, $id, $ip)
{
	global $db, $board_config;

	$result = $db->sql_query("SELECT ip FROM " . TOPLIST_ANTI_FLOOD_TABLE . " 
		WHERE id = " . $id . " 
			AND type = '" . $type . "'");
	while ($row = $db->sql_fetchrow($result))
	{
		if ($row['ip'] == $ip) 
		{
			return true;
		}
	}
	$db->sql_freeresult($result);

	return false;
}

function toplist_set_anti_flood_data($type, $id, $ip)
{
	global $db;
	
	$db->sql_query("INSERT INTO " . TOPLIST_ANTI_FLOOD_TABLE . " (id, ip, time, type) 
		VALUES ('" . $id . "', '" . $ip . "', " . time() . ", '" . $type . "')");
}

function toplist_calculate_total()
{
	global $db, $board_config;
	
	$sql = "UPDATE " . TOPLIST_TABLE . " 
		SET tot = '" . (($board_config['toplist_count_hin_hits']) ? HIN : 0) . "' + '" . (($board_config['toplist_count_out_hits']) ? OUT : 0) . "' + '" . (($board_config['toplist_count_img_hits']) ? IMG : 0) . "'";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not query toplist information', '', __LINE__, __FILE__, $sql);
	}
	
	$sql = "SELECT COUNT(id) AS users 
		FROM " . TOPLIST_TABLE . (($board_config['toplist_' . HIN . '_activation']) ? " WHERE " . HIN . " != 0" : "");
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain toplist information', '', __LINE__, __FILE__, $sql);
	}
	
	return $db->sql_fetchrow($result);
}

function gen_html_code($id)
{
	global $location, $phpEx;
	
	return '<a target="_blank" href="' . $location . 'mods/toplist/dload.'.$phpEx.'?id=' . $id . '"><img src="' . $location . 'mods/toplist/image.'.$phpEx.'?id=' . $id . '" alt="" title="" border="0" /></a>';
}

function toplist_on_exit_image()
{
	global $phpbb_root_path;
	
	header("Location: " . $phpbb_root_path . "images/spacer.gif");
	exit;
}

function toplist_make_img_dg($imgfile, $text = '', $location = '')
{
	global $board_config, $db, $phpbb_root_path;
	
	list($width, $height, $type, $attr) = getimagesize($phpbb_root_path . 'images/toplist/' . $imgfile);
	
	switch($type)
	{
		case 1:
			if(function_exists('imagecreatefromgif'))
			{
				$img = imagecreatefromgif($phpbb_root_path . 'images/toplist/' . $imgfile);
				if(!$img)
				{
					header('Location:' . $location . 'images/toplist/' . $imgfile);
					exit;
				}
			}
			else
			{
				header('Location:' . $location . 'images/tolist/' . $imgfile);
				exit;
			}
			break;
			
		case 2:
			if(function_exists('imagecreatefromjpeg'))
			{
				$img = imagecreatefromjpeg($phpbb_root_path . 'images/toplist/' . $imgfile);
				if(!$img)
				{
					header('Location:' . $location . 'images/toplist/' . $imgfile);
					exit;
				}
			}
			else
			{
				header('Location:' . $location . 'images/toplist/' . $imgfile);
				exit;
			}
			break;
			
		case 3:
			if(function_exists('imagecreatefrompng'))
			{
				$img = imagecreatefrompng($phpbb_root_path . 'images/toplist/' . $imgfile);
				if(!$img)
				{
					header('Location:' . $location . 'images/toplist/' . $imgfile);
					exit;
				}
			}
			else
			{
				header('Location:' . $location . 'images/toplist/' . $imgfile);
				exit;
			}
			break;
			
		default:
			header('Location:' . $location . 'images/toplist/' . $imgfile);
			exit;
			break;
	}
	
	if(function_exists('imagecreatetruecolor') && $type != 1)
	{
		$bg = imagecreatetruecolor($width, $height + 15);
	}
	elseif(function_exists('imagecreate'))
	{
		$bg = imagecreate($width, $height + 15);
	}
	else
	{
		header('Location:' . $location . 'images/toplist/' . $imgfile);
		exit;
	}
	
	if(function_exists('imagecolorallocate'))
	{
		$red = imagecolorallocate($bg, 255, 0, 0);
		$green = imagecolorallocate($bg, 0, 255, 0);
		$blue = imagecolorallocate($bg, 0, 0, 255);
		$white = imagecolorallocate($bg, 255, 255, 255);
		$black = imagecolorallocate($bg, 0, 0, 0);
		//$bgcolor = imagecolorallocate($bg, hexdec(substr($theme['body_bgcolor'],0,2)), hexdec(substr($theme['body_bgcolor'],2,2)), hexdec(substr($theme['body_bgcolor'],4,2)));
		//$fncolor = imagecolorallocate($bg, hexdec(substr($theme['body_text'],0,2)), hexdec(substr($theme['body_text'],2,2)), hexdec(substr($theme['body_text'],4,2)));
	}
	else
	{
		header('Location:' . $location . 'images/toplist/' . $imgfile);
		exit;
	}
	
	if(function_exists('imagefill'))
	{
		imagefill($bg, 0, 0, $white);
	}
	else
	{
		header('Location:' . $location . 'images/toplist/' . $imgfile);
		exit;
	}
	
	if(function_exists('imagestring'))
	{
		if(empty($text))
		{
			toplist_calculate_total();
			$rank = 0;
			$sql = "SELECT id 
				FROM " . TOPLIST_TABLE . (($board_config['toplist_' . HIN . '_activation'] == 1) ? " WHERE " . HIN . " != 0" : "") . " 
				ORDER BY tot DESC, nam ASC, inf ASC, owner DESC";
			if ( !($result = $db->sql_query($sql)) )
			{
				header('Location:' . $location . 'images/toplist/' . $imgfile);
				exit;
			}
			
			while( ($row = $db->sql_fetchrow($result)))
			{
				$rank++;
				if($row['id'] == $HTTP_GET_VARS['id'])
				{
					break;
				}
			}
			imagestring($bg, 2, 1, $height + 1, "Rank #" . $rank, $red);
		}
		else
		{
			imagestring($bg, 2, 1, $height + 1, $text, $red);
		}
	}
	else
	{
		header('Location:' . $location . 'images/toplist/' . $imgfile);
		exit;
	}
	
	if(function_exists('imagecopy'))
	{
		imagecopy($bg, $img, 0, 0, 0, 0, $width, $height);
	}
	else
	{
		header('Location:' . $location . 'images/toplist/' . $imgfile);
		exit;
	}
	
	if(function_exists('imagepng') && function_exists('imagedestroy'))
	{
		header('Content-Type: image/png'); 
		imagepng($bg); 
		imagedestroy($bg);
		exit;
	}
	else
	{
		header('Location:' . $location . 'images/toplist/' . $imgfile);
		exit;
	}
}

?>