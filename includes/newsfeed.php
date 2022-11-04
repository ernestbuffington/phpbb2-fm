<?php
/***************************************************************************
 *					 		newsfeed.php
 *                          -------------------
 *   begin                : Monday, April 08, 2002
 *   copyright            : (C) 2002 ashben (www.ashben.net / eServicesHub.com)
 *   email                : phpbb@broid.com
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
	exit;
}
	
$newsfeed = $board_config['newsfeed_rss'];
$cache_dir = $phpbb_root_path . '/' . $board_config['newsfeed_cache'];
$cachefile = $cache_dir . '/newsfeed.txt';
$newsfile = $cache_dir . '/newsfeed.htm';
$cachetimeinseconds = ( $board_config['newsfeed_cachetime'] * 60 );
$current_time = split(' ', microtime(), 2);
$newsfeedtext = '';

if ( (!file_exists($cachefile) || (!filesize($cachefile)) || ($current_time[1] - filemtime($cachefile) > $cachetimeinseconds)) )
{
	// Step 1 - Caching Routine
	if (($file = @fopen($newsfeed, 'r'))) 
	{
		$fpwrite = fopen($cachefile, 'w');

		$line = '';
		while ((!feof($file))) 
		{
			$line = fgets($file, 1024);
			fputs($fpwrite,$line);
		}

		fclose($file);
		fclose($fpwrite);
	
		// Step 2 - XML Parsing and Content Rendering Routine
		$depth = array();
		$content = array();
		$curtag = '';
		$newsbuffer = '';
		$headline_count = 1;

		function startElement($parser, $name, $attrs) 
		{
			global $depth, $curtag;

			$curtag = $name;
			$depth[$parser]++;
		}

		function endElement($parser, $name) 
		{
			global $depth, $curtag, $content, $newsbuffer, $headline_count, $board_config;

			$curtag = '';
			$depth[$parser]--;
			
			if (strtoupper($name) == "{$board_config[newsfeed_field_article]}" && $headline_count < $board_config['newsfeed_amt']) 
			{
				$newsbuffer .= "&nbsp;&bull; <a href='" . $content[0] . "' target='_blank' class='genmed'>" . $content[1] . "</a><br /> &nbsp;&nbsp; <span class='gensmall'>" . $content[3] . ", " . $content[2] . "</span>\n<br />\n";
				$headline_count++;
			}
		}

		function characterData($parser, $data) 
		{
			global $curtag, $content, $board_config;
			
			switch (strtoupper($curtag)) 
			{
				case "{$board_config[newsfeed_field_url]}":
					$content[0] = $data;
					break;
				case "{$board_config[newsfeed_field_text]}":
					$content[1] = $data;
					break;
				case "{$board_config[newsfeed_field_source]}":
					$content[2] = $data;
					break;
				case "{$board_config[newsfeed_field_time]}":
					$content[3] = $data;
					break;
			}
		}

		if (($fp = @fopen($cachefile, 'r'))) 
		{
			$xml_parser = xml_parser_create();
			xml_set_element_handler($xml_parser, 'startElement', 'endElement');
			xml_set_character_data_handler($xml_parser, 'characterData');

			$newsbuffer = '';
			while ($data = fread($fp, filesize($cachefile))) 
			{
				if (!xml_parse($xml_parser, $data, feof($fp))) 
				{
					// Error!
				}
			}
			xml_parser_free($xml_parser);
			@fclose($fp);

			// Step 3 - Generate HTML
			$fpwrite = @fopen($newsfile, 'w');
			fputs($fpwrite, $newsbuffer);
			@fclose($fpwrite);

			$newsfeedtext .= "<!-- NewsCache Updated //-->\n\n";
		} 
		else 
		{
			$newsfeedtext .= "<!-- Error/1 - NewsCache Used //-->\n\n";
		}
	} 
	else 
	{
		$newsfeedtext .= "<!-- Error/2 - NewsCache Used //-->\n\n";
	}
} 
else 
{
	$newsfeedtext .= "<!-- NewsCache Used //-->\n\n";
}

if (file_exists($newsfile))
{
	$newsfeedtext .= implode ('', file($newsfile));		
}
else				
{
	$newsfeedtext .= 'Newsfeed currently unavailable. Please try later.';
}

$newsfeedtext .= "\n\n";

?>