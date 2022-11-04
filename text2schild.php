<?php
/** 
*
* @package phpBB
* @version $Id: text2schild.php,v 1.186.2.43 2005/07/19 20:01:21 acydburn Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', TRUE);
$phpbb_root_path = './';
include_once($phpbb_root_path .'extension.inc');
include_once($phpbb_root_path .'common.'. $phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, $forum_id, $topic_id);
init_userprefs($userdata);
//
// End session management
//

$raute = '#';
$schriftfarbe = $raute . $HTTP_GET_VARS['fontcolor'];

if ( $HTTP_GET_VARS['shadowcolor'] == '' )
{
	$schattenfarbe = '';
}
else
{
	$schattenfarbe = $raute . $HTTP_GET_VARS['shadowcolor'];
}

if ( $HTTP_GET_VARS['smilie'] == 'standard' )
{
	$smilie = $std_smilie;
}
else
{
	$smilie = $HTTP_GET_VARS['smilie'];
}

$std_smilie = 1;
$schildschatten = $HTTP_GET_VARS['shieldshadow'];

$anz_smilie = -1;
$hdl = @opendir('./images/smiles/schild/');
while ($res = @readdir($hdl))
{
	if (strtolower(substr($res, (strlen($res) - 3), 3)) == 'png') 
	{
		$anz_smilie++;
	}
}
@closedir($hdl);


$gd_info = gd_info();

if ( (!$gd_info["FreeType Support"]) || (!file_exists($schriftdatei)) )
{
	$schriftwidth = 6;
	$schriftheight = 8;
}
else
{
	if((!$schriftheight) || (!$schriftwidth))
	{
		$schriftwidth = imagefontwidth($schriftdatei);
		$schriftheight = imagefontheight($schriftdatei);
	}
}
$schriftheight += 2;


if (!$text) 
{
	$text = $_GET["text"];
}

//
// Define censored word matches
//
if ( !$board_config['allow_swearywords'] )
{
	$orig_word = $replacement_word = array();
	obtain_word_list($orig_word, $replacement_word);
}
else if ( !$userdata['user_allowswearywords'] )
{
	$orig_word = $replacement_word = array();
	obtain_word_list($orig_word, $replacement_word);
}

if( !empty($orig_word) )
{
	$text = preg_replace($orig_word, $replacement_word, $text);
}

$text = stripslashes($text);
$text = str_replace('&lt;', '<', $text);
$text = str_replace('&gt;', '>', $text);

while(substr_count($text, '<'))
{
	$text = ereg_replace(substr($text, strpos($text, '<'), (strpos($text, '>') - strpos($text, '<') + 1)), '', $text);
}

if (!$text) 
{
	$text = 'no text'; // Error
}

if(strlen($text) > 33)
{
	$worte = split(" ", $text);

	if(is_array($worte))
	{
		$i = 0;
		foreach($worte as $wort)
		{
			if((strlen($output[$i] . ' ' . $wort) < 33) && (!substr_count($wort, "[SM")))
			{
				$output[$i] .= " ".$wort;
			}
			else
			{
				if($i <= 11)
				{
					if($zeichenzahl < strlen($output[$i])) $zeichenzahl = strlen($output[$i]);
					$i++;
					$output[$i] = $wort;
				}
			}
		}
	}
	else
	{
		$zeichenzahl = 33;
		$output[0] = substr($text, 0, 30) . '...';
	}
}
else
{
	$zeichenzahl = strlen($text);
	$output[0] = $text;
}

if(count($output) > 12) 
{
	$output[12] = substr($output[12], 0, 30) . '...';
}

$width = ($zeichenzahl * $schriftwidth) + 6;
$height = (count($output) * $schriftheight) + 34;
if ($width < 60) 
{
	$width = 60;
}

mt_srand((double)microtime() * 3216549);
if ($smilie == 'random') 
{
	$smilie = mt_rand(1, $anz_smilie);
}
if (!$smilie)
{
	if ($std_smilie) 
	{
		$smilie = $std_smilie;
	}
	else 
	{
		$smilie = mt_rand(1, $anz_smilie);
	}
}

$smilie = imagecreatefrompng('images/smiles/schild/smilie' . $smilie . '.png');
$schild = imagecreatefrompng('images/smiles/schild/schild.png');
$img = imagecreate($width, $height);

$bgcolor = imagecolorallocate ($img, 111, 252, 134);
$txtcolor = imagecolorallocate ($img, hexdec(substr(str_replace('#', '', $schriftfarbe), 0, 2)), hexdec(substr(str_replace('#', '', $schriftfarbe), 2, 2)), hexdec(substr(str_replace('#', '', $schriftfarbe), 4, 2)));
$txt2color = imagecolorallocate ($img, hexdec(substr(str_replace('#', '', $schattenfarbe), 0, 2)), hexdec(substr(str_replace('#', '', $schattenfarbe), 2, 2)), hexdec(substr(str_replace('#', '', $schattenfarbe), 4, 2)));
$bocolor = imagecolorallocate ($img, 0, 0, 0);
$schcolor = imagecolorallocate ($img, 255, 255, 255);
$schatten1color = imagecolorallocate ($img, 235, 235, 235);
$schatten2color = imagecolorallocate ($img, 219, 219, 219);

$smiliefarbe = imagecolorsforindex($smilie, imagecolorat($smilie, 5, 14));

imagesetpixel($schild, 1, 14, imagecolorallocate($schild, ($smiliefarbe["red"] + 52), ($smiliefarbe["green"] + 59), ($smiliefarbe["blue"] + 11)));
imagesetpixel($schild, 2, 14, imagecolorallocate($schild, ($smiliefarbe["red"] + 50), ($smiliefarbe["green"] + 52), ($smiliefarbe["blue"] + 50)));
imagesetpixel($schild, 1, 15, imagecolorallocate($schild, ($smiliefarbe["red"] + 50), ($smiliefarbe["green"] + 52), ($smiliefarbe["blue"] + 50)));
imagesetpixel($schild, 2, 15, imagecolorallocate($schild, ($smiliefarbe["red"] + 22), ($smiliefarbe["green"] + 21), ($smiliefarbe["blue"] + 35)));
imagesetpixel($schild, 1, 16, imagecolorat($smilie, 5, 14));
imagesetpixel($schild, 2, 16, imagecolorat($smilie, 5, 14));
imagesetpixel($schild, 5, 16, imagecolorallocate($schild, ($smiliefarbe["red"] + 22), ($smiliefarbe["green"] + 21), ($smiliefarbe["blue"] + 35)));
imagesetpixel($schild, 6, 16, imagecolorat($smilie, 5, 14));
imagesetpixel($schild, 5, 15, imagecolorallocate($schild, ($smiliefarbe["red"] + 52), ($smiliefarbe["green"] + 59), ($smiliefarbe["blue"] + 11)));
imagesetpixel($schild, 6, 15, imagecolorallocate($schild, ($smiliefarbe["red"] + 50), ($smiliefarbe["green"] + 52), ($smiliefarbe["blue"] + 50)));


imagecopy ($img, $schild, ($width / 2 - 3), 0, 0, 0, 6, 4); // Bildteil kopieren
imagecopy ($img, $schild, ($width / 2 - 3), ($height - 24), 0, 5, 9, 17); // Bildteil kopieren
imagecopy ($img, $smilie, ($width / 2 + 6), ($height - 24), 0, 0, 23, 23); // Bildteil kopieren

imagefilledrectangle($img, 0, 4, $width, ($height - 25), $bocolor);
imagefilledrectangle($img, 1, 5, ($width - 2), ($height - 26), $schcolor);

if($schildschatten)
{
	imagefilledpolygon($img, array((($width - 2) / 2 + ((($width - 2) / 4) - 3)), 5, (($width - 2) / 2 + ((($width - 2) / 4) + 3)), 5, (($width - 2) / 2 - ((($width - 2) / 4) - 3)), ($height - 26), (($width - 2) / 2 - ((($width - 2) / 4) + 3)), ($height - 26)), 4, $schatten1color);
	imagefilledpolygon($img, array((($width - 2) / 2 + ((($width - 2) / 4) + 4)), 5, ($width - 2), 5, ($width - 2), ($height - 26), (($width - 2) / 2 - ((($width - 2) / 4) - 4)), ($height - 26)), 4, $schatten2color);
}

$i = 0;
while($i < sizeof($output))
{
	if ( ((!$gd_info["FreeType Support"]) || (!file_exists($schriftdatei))) )
	{
		if ($schattenfarbe) 
		{
			imagestring($img, 2, (($width - (strlen(trim($output[$i])) * $schriftwidth) - 2) / 2 + 1), ($i * $schriftheight + 6), trim($output[$i]), $txt2color);
		}
		imagestring($img, 2, (($width - (strlen(trim($output[$i])) * $schriftwidth) - 2) / 2), ($i * $schriftheight + 5), trim($output[$i]), $txtcolor);
	}
	else
	{
		if ($schattenfarbe) 
		{
			imagettftext($img, $schriftheight, 0, (($width - (strlen(trim($output[$i])) * $schriftwidth) - 2) / 2 + 1), ($i * $schriftheight + $schriftheight + 4), $txt2color, $schriftdatei, trim($output[$i]));
		}
		imagettftext($img, $schriftheight, 0, (($width - (strlen(trim($output[$i])) * $schriftwidth) - 2) / 2), ($i * $schriftheight + $schriftheight + 3), $txtcolor, $schriftdatei, trim($output[$i]));
	}
	$i++;
}


imagecolortransparent($img, $bgcolor);  // Dummybg als transparenz setzen
imageinterlace($img, 1);

header("Content-Type: image/png");
Imagepng($img, '', 9);   // Compression
ImageDestroy($img);
ImageDestroy($schild);
ImageDestroy($smilie);

?>