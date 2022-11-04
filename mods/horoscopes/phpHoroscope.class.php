<?php
/***************************************************************************
 *                                phpHoroscope.class.php
 *                            -------------------
 *   begin                : Saturday, Feb 13, 2001
 *   copyright            : (C) 2001 The phpBB Group
 *   email                : support@phpbb.com
 *
 *   $Id: phpHoroscope.class.php,v 1.99.2.3 2004/07/11 16:46:15 acydburn Exp $
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
	die('Hacking attempt');
}

define('base', 'http://horoscopes.astrology.com');
define('qbase', base . '/daily');
define('ebase', base . '/dailylong');

class phpHoroscope
{
  	var $sign;
  	var $date;
  	var $quickie;
  	var $extended;
	
	function phpHoroscope ()
  	{
    	$this->sign = 'aquarius';
    	$this->date = 'today';
    	$this->quickie = TRUE;
    	$this->extended = FALSE;
  	}

  	function safeSigns ()
  	{
  		unset($safeSigns);
    	return $safeSigns = array('aquarius', 'aries',  'cancer', 'capricorn', 'gemini', 'leo', 'libra', 'pisces', 'sagittarius', 'scorpio', 'taurus', 'virgo');
  	}

  	function safeDates ()
  	{
  		unset($safeDates);
    	return $dates = array('today', 'yesterday', 'tomorrow');
  	}

  	function setSign ($signControl)
  	{
  		if (empty($signControl)) 
  		{
      		echo "error: sign is undefined!";
      		exit;
    	}
    	if (in_array($signControl, $this->safeSigns())) 
    	{
      		$this->sign = $signControl;
    	} 
    	else 
    	{
      		echo "error: " . $signControl . " is not a valid sign!";
      		exit;
    	}
  	}

  	function setDate ($dateControl)
  	{
    	if (empty($dateControl)) 
    	{
      		echo "error: date is undefined!";
      		exit;
    	}
    	if (in_array($dateControl, $this->safeDates())) 
    	{
      		$this->date = $dateControl;
    	} 
    	else 
    	{
      		echo "error: " . $dateControl . " is not a valid date!";
      		exit;
    	}
  	}

  	function setQuickie ()
  	{
    	$this->extended = FALSE;
    	$this->quickie = TRUE;
  	}

  	function setExtended ()
  	{
  	  	$this->quickie = FALSE;
  	  	$this->extended = TRUE;
  	}

  	function buildUrl ()
  	{
    	if ($this->extended) 
    	{
      		$url = ebase;
    	} 
    	else 
    	{
      		$url = qbase;
    	}
    	if ($this->date == 'today') 
    	{
      		$url = $url . $this->sign . '.html';
    	} 
    	else 
    	{
      		$url = $url . $this->sign . substr($this->date, 0, 3) . '.html';
    	}
    	return $url;
  	}

  	function getHoroscope ()
  	{
    	$url = $this->buildUrl();
    	$file = @fopen($url, 'r');
    	if (!$file) 
    	{
    	  	echo "error: remote file '" . $url . "' could not be opened!";
      		exit;
    	}
    	while (!feof($file)) 
    	{
      		$line = @fgets($file);
      		if (eregi("</h3><br />", $line, $result)) 
      		{
        		$found = TRUE;
      		} 
      		elseif ($found) 
      		{
        		$horoscope = trim($line);
        		break;
      		}
    	}
    	@fclose($file);
    	return $horoscope;
  	}

  	function displayHoroscope ()
  	{
    	if (!empty($this->sign) && !empty($this->date)) 
    	{
      		if (in_array($this->sign, $this->safeSigns()) && in_array($this->date, $this->safeDates())) 
      		{
        		echo $this->getHoroscope();
      		} 
      		else 
      		{
        		echo "error: sign and/or date are not valid!";
        		exit;
      		}
    	} 
    	else 
    	{
      		echo "sign and/or date are undefined!";
      		exit;
    	}
  	}
}

?>