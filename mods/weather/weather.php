<?php
/***************************************************************************
 *					  weather.php
 *                              -------------------
 *
 ****************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

function weather($weather_code)
{

	$weatherStart = '<!-- top table -->'; 
	$weatherEnd = '<!-- /current -->'; 

	$fp = @fsockopen ("weather.cnn.com", 80, $errno, $errstr, 30);
	if (!$fp) 
	{
	    echo "$errstr ($errno)<br>\n";
	} 
	else 
	{
	    fputs ($fp, "GET /weather/forecast.jsp?locCode=$weather_code HTTP/1.0\r\nHost: weather.cnn.com\r\n\r\n");
	    while (!feof($fp)) 
	    {
	        $RetrieveFile .= fgets($fp,1024);
	    }
	    @fclose ($fp);
	}

	$weatherData = eregi("$weatherStart(.*)$weatherEnd", $RetrieveFile, $DataPrint); // Acquire The Data
	$gotWeather = str_replace('face=Arial', 'face="Verdana, Arial" color="black"', $DataPrint[1]); // Change Font

	if ($weatherData == "")
	{
		$gotWeather = "<td>-No City Selected-</td>";
	}

	$pattern = '<script language="JavaScript1.1">(.*)</noscript>';

	$gotWeather = ereg_replace($pattern,"",$gotWeather);
	$gotWeather = ereg_replace("width=\"336\"","",$gotWeather);
	$gotWeather = ereg_replace("width=\"634\"","",$gotWeather);

	$gotWeather = $gotWeather . "</td></tr></table>";

	$fivedayStart = '<!-- 5day forecast -->'; 
	$fivedayEnd = '<!-- /5day forecast -->'; 

	$fivedayData = eregi("$fivedayStart(.*)$fivedayEnd", $RetrieveFile, $DataFile1); // Acquire The Data
	$gotWeather1 = str_replace('face=Arial', 'face="Verdana, Arial" color="black"', $DataFile1[1]); // Change Font

	$gotWeather = "<td><table><tr><td>" . $gotWeather . "</td><td valign=bottom>" . $gotWeather1 . "</td></tr></table></td>";

	return $gotWeather;
}

?>