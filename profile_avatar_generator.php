<?php
/** 
*
* @package phpBB2
* @version $Id: profile_generate_avatar.php,v 2.0.0 2005/09/22 00:06:33 psotfx Exp $
* @copyright (c) 2005 WinSrev, MrBass
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);

$filename = $_GET['filename'];
$imagetext = stripslashes($_GET['imagetext']);
$size = 10;
$generator_template_path = $board_config['avatar_generator_template_path'];
$font = $generator_template_path . '/trebucbd.ttf';

switch ($_GET['imagename'])
{
	case 'Blue':
		$bgpic = $generator_template_path . '/blue.gif';
		break;
	case 'Gray':
		$bgpic = $generator_template_path . '/gray.gif';
		break;
	case 'Green':
		$bgpic = $generator_template_path . '/green.gif';
		break;
	case 'Pink':
		$bgpic = $generator_template_path . '/pink.gif';
		break;
	case 'Purple':
		$bgpic = $generator_template_path . '/purple.gif';
		break;
	case 'Red':
		$bgpic = $generator_template_path . '/red.gif';
		break;
	case 'SteelBlue':
		$bgpic = $generator_template_path . '/sblue.gif';
		break;
	case 'Aphrodite':
		$bgpic = $generator_template_path . '/aphrodite.gif';
		break;
	case 'Opera':
		$bgpic = $generator_template_path . '/opera.gif';
		break;
	case 'Firefox':
		$bgpic = $generator_template_path . '/firefox.gif';
		break;
	case 'Random':
		$num = mt_rand(1, 11);
		if ($num == 1) 
		{ 
			$bgpic = $generator_template_path . '/blue.gif'; 
		}
		else if ($num == 2) 
		{ 
			$bgpic = $generator_template_path . '/gray.gif'; 
		}
		else if ($num == 3) 
		{ 
			$bgpic = $generator_template_path . '/green.gif'; 
		}
		else if ($num == 4) 
		{ 	
			$bgpic = $generator_template_path . '/pink.gif';
		}
		else if ($num == 5) 
		{ 	
			$bgpic = $generator_template_path . '/purple.gif'; 
		}
		else if ($num == 6) 
		{ 
			$bgpic = $generator_template_path . '/red.gif'; 
		}
		else if ($num == 7) 
		{ 
			$bgpic = $generator_template_path . '/sblue.gif'; 
		}
		else if ($num == 8) 
		{ 
			$bgpic = $generator_template_path . '/darkblue.gif';
		}
		else if ($num == 9) 
		{ 
			$bgpic = $generator_template_path . '/aphrodite.gif';
		}
		else if ($num == 10) 
		{ 
			$bgpic = $generator_template_path . '/opera.gif';
		}
		else if ($num == 11) 
		{ 
			$bgpic = $generator_template_path . '/firefox.gif';
		}
		break;
	default:
		$num = mt_rand(1, 11);
		if ($num == 1) 
		{ 
			$bgpic = $generator_template_path . '/blue.gif'; 
		}
		else if ($num == 2) 
		{ 
			$bgpic = $generator_template_path . '/gray.gif'; 
		}
		else if ($num == 3) 
		{ 
			$bgpic = $generator_template_path . '/green.gif'; 
		}
		else if ($num == 4) 
		{ 
			$bgpic = $generator_template_path . '/pink.gif'; 
		}
		else if ($num == 5) 
		{ 
			$bgpic = $generator_template_path . '/purple.gif'; 
		}
		else if ($num == 6) 
		{ 
			$bgpic = $generator_template_path . '/red.gif'; 
		}
		else if ($num == 7) 
		{ 
			$bgpic = $generator_template_path . '/sblue.gif'; 
		}
		else if ($num == 8) 
		{ 
			$bgpic = $generator_template_path . '/darkblue.gif';
		}
		else if ($num == 9) 
		{ 
			$bgpic = $generator_template_path . '/aphrodite.gif';
		}
		else if ($num == 10) 
		{ 
			$bgpic = $generator_template_path . '/opera.gif';
		}
		else if ($num == 11) 
		{ 
			$bgpic = $generator_template_path . '/firefox.gif';
		}
}

$im = imagecreatefromgif($bgpic);

// Calculate the centre
for(;;)
{
	list($image_width, $image_height) = getimagesize($bgpic);
	list($left_x, , $right_x) = imagettfbbox($size, 0, $font, $imagetext);
	$text_width = $right_x - $left_x;
	if($image_width > $text_width + 5)
	{
		break;
	}
	$size = $size - .5;
	if($size == 1)
	{
		die('Script not responding to decreasing font size');
	}
}
$padding = ($image_width - $text_width) / 2;

$textcolor =  imagecolorresolve($im, 255, 255, 255);
imagettftext($im, $size, 0, $padding, 75, $textcolor, $font, $imagetext);

if($_GET['dl'])
{
	header('Content-Disposition: attachment; filename="avatar.gif"');
}

header("Content-type: image/gif");
imagegif($im, $filename);
imagegif($im);
imagedestroy($im);

?>