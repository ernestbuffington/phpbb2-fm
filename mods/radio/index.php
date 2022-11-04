<?php
/***************************************************************************
 *                                index.php
 *                            -------------------
 *   begin                : unknown
 *   copyright            : (C) Pelle van der Scheer
 *   email                : beheerder@hotmail.com
 *
 *   $Id: index.php,v 1.4.0 Exp $
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
include($phpbb_root_path . 'includes/page_header.' . $phpEx);

//
// Language
//
$radio_lang = array(
	'mute' => 'Mute',
	'stop' => 'Stop',
	'play' => 'Play',
	'volume' => 'Volume',
	'title' => 'Livestream Radio',
	'no_choice_made_yet' => 'You have not selected a station yet',
	'you_are_listening_to' => 'You are currently listening to',
	'controls' => 'Controls',
	'on' => 'On',
	'off' => 'Off',
	'now_playing' => 'Now Playing',
	'play_url' => 'Play an URL',
	'custom_url' => 'Custom URL',
	'listen_broadband' => 'Listen Broadband',
	'broadband' => 'Broadband',
	'real_media' => 'Real Media',
	'win_media' => 'Windows Media'
);

//
// Settings
//
// If Type is set to 1 the controls are shown as a default on the windows media player
$settings = array('width' => 200, 'height' => 150, 'type' => 0);


//
// Start XML PARSER 
//
$insideitem = false;
$oidar = $tag = $r_name = $url = $broadband = $type = $num = '';

function startElement($parser, $name, $attrs) 
{
	global $insideitem, $tag, $r_name, $url, $broadband, $type, $num, $oidar;
	
	if ( $insideitem ) 
	{
		$tag = $name;
	} 
	elseif ( $name == 'STATION' ) 
	{
		$insideitem = true;
	}
}

function endElement($parser, $name) 
{
	global $insideitem, $tag, $r_name, $url, $broadband, $type, $num, $oidar;
	
	if ( $name == 'STATION' ) 
	{
		$oidar[trim($num)] = array('name' => trim($r_name), 'url' => trim($url), 'broadband' => trim($broadband), 'type' => trim($type));
		
		$r_name = $url = $broadband = $type = $num = '';
		$insideitem = false;
		
		return $oidar;
	}
}

function characterData($parser, $data) 
{
	global $insideitem, $tag, $r_name, $url, $broadband, $type, $num, $oidar;
	
	if ($insideitem) 
	{
		switch ($tag) 
		{
			case 'NAME':
				$r_name .= $data;
				break;
			case 'URL':
				$url .= $data;
				break;
			case 'BROADBAND':
				$broadband .= $data;
				break;
			case 'TYPE':
				$type .= $data;
				break;
			case 'NUM':
				$num .= $data;
				break;
		}
	}
}

$xml_parser = xml_parser_create();
xml_set_element_handler($xml_parser, "startElement", "endElement");
xml_set_character_data_handler($xml_parser, "characterData");
$fp = fopen('radio.xml','r') or die('Could not read Radio XML data file.');

while ($data = fread($fp, 4096))
	xml_parse($xml_parser, $data, feof($fp))
		or die(sprintf("XML error: %s at line %d", 
			xml_error_string(xml_get_error_code($xml_parser)), 
			xml_get_current_line_number($xml_parser)));
fclose($fp);
xml_parser_free($xml_parser);


//
// Undefined variables:
//
$oidar[0] = array('name' => '', 'url' => '', 'broadband' => '', 'type' => '');

$final = '';
if (isset($_GET['radio'])) 
{ 
	$radio = $_GET['radio']; 
}
else
{ 
	$radio = 0; 
}
if (isset($_GET['bb'])) 
{ 
	$bb = $_GET['bb']; 
}
else
{ 
	$bb = ''; 
}


//
// Volume array
//
$vol =  array( '0' => 0, '1' => 10, '2' => 20, '3' => 30, '4' => 40, '5' => 50, '6' => 60, '7' => 70, '8' => 80, '9' => 90, '10' => 100 );


//
// Go through every radio station and list it
//
$count = 1;
for (;;)
{
	if ( !isset($oidar[$count]['name']) )
	{ 
		break; 
	}
	
	if ( $oidar[$count]['broadband'] == '' )
	{ 
		$broadband = ''; 
	} 
	else
	{ 
		$broadband = '&nbsp;[<a title="' . $radio_lang['listen_broadband'] . '" href="index.php?radio=' . $count . '&bb=1" class="gensmall"><span style="color: #990000">BB</span></a>]';
	}
	
	if ( $oidar[$count]['type'] == 'rm' )
	{ 
		$ctypen = '&nbsp;[<a title="' . $radio_lang['real_media'] . '" href="#" class="gensmall"><span style="color: #FF0000">rm</span></a>]'; 
	} 
	else 
	{ 
		$ctypen = '[<a title="' . $radio_lang['win_media'] . '" href="#" class="gensmall"><span style="color: #0000FF">wm</span></a>]'; 
	}	
	
	$final = $final . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $ctypen . ' <a href="index.php?radio=' . $count . '" class="gensmall"><u>' . $oidar[$count]['name'] . '</u></a>' . $broadband . '<br />';
	$count++;
}


//
// Set current url
//
if ( $bb == 1 )
{ 
	$curl = $oidar[$radio]['broadband']; 
} 
else 
{ 
	$curl = $oidar[$radio]['url']; 
}


//
// Set current name
//
if ( $radio > 0 )
{
	if ( $bb == 1 )
	{
		$cname = $oidar[$radio]['name'] . ' ' . $radio_lang['broadband'];
	} 
	else 
	{
		$cname = $oidar[$radio]['name'];
	}
} 
else 
{
	$cname = $radio_lang['no_choice_made_yet'];
}

?>
<script language="Javascript" type="text/javascript"> 
<!--
function changeMute() 
{
	Player.settings.mute = 'true';
	mute.innerHTML = '<a href="#" onClick="changeMute2();">|<? echo ($radio_lang["mute"]) ?>*|</a> <a href="#" onClick="javascript:Player.controls.play();">|<? echo ($radio_lang["play"]) ?>|</a> <a href="#" onClick="javascript:Player.controls.stop();">|<? echo ($radio_lang["stop"]) ?>|</a> |<? echo ($radio_lang["controls"]) ?>:| <a href="#" onClick="javascript:ControlsOn();"><? echo ($radio_lang["on"]) ?></a> <a href="#" onClick="javascript:ControlsOff();"> <? echo ($radio_lang["off"]) ?></a>';
}
function changeMute2() 
{
	Player.settings.mute = 'false';
	mute.innerHTML = '<a href="#" onClick="changeMute();">|<? echo ($radio_lang["mute"]) ?>|</a> <a href="#" onClick="javascript:Player.controls.play();">|<? echo ($radio_lang["play"]) ?>|</a> <a href="#" onClick="javascript:Player.controls.stop();">|<? echo ($radio_lang["stop"]) ?>|</a> |<? echo ($radio_lang["controls"]) ?>:| <a href="#" onClick="javascript:ControlsOn();"><? echo ($radio_lang["on"]) ?></a> <a href="#" onClick="javascript:ControlsOff();"> <? echo ($radio_lang["off"]) ?></a>';
}
function ControlsOn() 
{
	Player.uiMode = 'mini';
}
function ControlsOff() 
{
	Player.uiMode = 'none';
}
//-->
</script>
<body leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0">

<table height='100%' cellpadding='4' cellspacing='1' width='100%' class='forumline'>
  <tr>
    <th colspan='2' class='thHead'><? echo ($radio_lang['title']); ?></th>
  </tr>
  <tr>
    <td class='catLeft' colspan='2'><b class='genmed'><? echo ($radio_lang['you_are_listening_to'] . ': <u>' . $cname . '</u>'); ?></b></td>
  </tr>
  <tr> 
   <td class='row1' valign='top'><span class='gensmall'><br />

<?php			
//
// Checks if user wants to display controls
//
if ( $settings['type'] == 1 ) 
{
	$uiMode = 'mini';
	$ShowControls = $ShowStatusBar = $ShowDisplay = 1;
} 
else 
{
	$uiMode = 'none';
	$ShowControls = $ShowStatusBar = $ShowDisplay = 0;
}

//
// Display all radio channels
//
echo ($final);

?>
	
  </span>
    </td>
    <td class='row2' align='center'><span class='gensmall'>
      
<?php
if ($oidar[$radio]['type'] == 'nsv')
{
	print <<< EOF
	<OBJECT id=nsvplayx codeBase='http://www.nullsoft.com/nsv/embed/nsvplayx_vp3_mp3.cab#Version=-1,-1,-1,-1' width='{$settings['width']}' height='{$settings['height']}' border='0' classid='clsid:C5E28B9D-0A68-4B50-94E9-E8F6B4697514'>
	<PARAM NAME='Location' VALUE='$curl' ref>
	<param name='_Version' value='65536'>
	<PARAM NAME='controls' VALUE='ControlPanel,StatusBar'>
	<param name='_ExtentX' value='16933'>
	<param name='_ExtentY' value='12700'>
	<param name='_StockProps' value='0'>
	<param name='Bandwidth' value>
	<embed type='application/x-nsv-vp3-mp3' width='{$settings['width']}' height='{$settings['height']}' codebase='http://www.nullsoft.com/nsv/embed/nsvmoz_vp3_mp3.xpi' location='$curl'></embed>
	</object>
EOF; 
}
else if ($oidar[$radio]['type'] == 'rm')
{
	print <<< EOF
	<OBJECT ID=video1 CLASSID='clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA' HEIGHT=60 WIDTH={$settings['width']}>
	<PARAM NAME='controls' VALUE='ControlPanel,StatusBar'>
	<PARAM NAME='console' VALUE='Clip1'>
	<PARAM NAME='autostart' VALUE='true'>
	<PARAM NAME='src' VALUE='$curl'>
        <EMBED SRC='$curl' CONSOLE='Clip1' CONTROLS='ControlPanel,StatusBar' HEIGHT=60 WIDTH={$settings['width']} AUTOSTART=true>
	</OBJECT>
EOF;
} 
else 
{
	print <<< EOF
	<OBJECT ID='Player' height='{$settings['height']}' width='{$settings['width']}' CLASSID='CLSID:6BF52A52-394A-11d3-B153-00C04F79FAA6'>
	<PARAM name='URL' value='$curl'>
	<PARAM name='uiMode' value='$uiMode'>
	<PARAM name='mute' value='false'>
	<PARAM name='ShowControls' value='$ShowControls'>
	<PARAM name='ShowStatusBar' value='$ShowStatusBar'>
	<PARAM name='ShowDisplay' value='$ShowDisplay'>
	<EMBED type='application/x-mplayer2' pluginspage = 'http://www.microsoft.com/Windows/MediaPlayer/' SRC='$curl' name='Player' width={$settings['width']} height={$settings['height']} AutoStart='true' showcontrols='$ShowControls' showstatusbar='$ShowStatusBar' showdisplay='$ShowDisplay'>
	</EMBED>
	</OBJECT> 
EOF;
}

?>

	</span></td>
    </tr>
	<tr> 
    <td class='catBottom' align='center' colspan='2'><span class='gensmall'>
      <div id='mute'><b><? echo($radio_lang['controls']); ?>:</b> <a href='#' onClick='javascript:Player.uiMode = "mini";' class='gensmall'><? echo($radio_lang['on']); ?></a> / <a href='#' onClick='javascript:Player.uiMode = "none";' class='gensmall'><? echo($radio_lang['off']); ?></a> &nbsp; &nbsp; &nbsp; [ <a href='#' onClick='changeMute();' class='gensmall'><? echo($radio_lang['mute']); ?></a>&nbsp; &nbsp;<a href='#' onClick='javascript:Player.controls.play();' class='gensmall'><? echo($radio_lang['play']); ?></a>&nbsp; &nbsp;<a href='#' onClick='javascript:Player.controls.stop();' class='gensmall'><? echo($radio_lang['stop']); ?></a> ]
	  </div>
      <div id="volume">
	    <b><? echo($radio_lang['volume']); ?>:</b>&nbsp;
	  
<?php
while (list($key,$val) = each($vol)) 
{
	if ( intval($key) >= 0 )
	{
		echo "<a href='#' onClick='javascript:Player.settings.volume= $val;' class='gensmall'>" . $key . "</a>\n";
	}
}
?>
	
      </div>
    </span></td>
	</tr>
</table>