<?php
/** 
*
* @package includes
* @version $Id: chinese.php,v 1.47.2.5 2004/11/18 17:49:42 niels Exp $
* @copyright (c) 2003 Niels Chr. Rød
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
	exit;
}

// the fist value is a YYYYMMDD date, it holds the ending date of the chinese year,
// the start date is asumed to start after the previous entry
// you may fill with other dates back in time
function get_chinese_year($date)
{
	$chinese = array(
		'19000000' => 'Unknown',
		'19010218' => 'Rat',
		'19020207' => 'Buffalo',
		'19030128' => 'Tiger',
		'19040215' => 'Cat',
		'19050203' => 'Dragon',
		'19060124' => 'Snake',
		'19070212' => 'Horse',
		'19080201' => 'Goat',
		'19090121' => 'Monkey',
		'19100209' => 'Cock',
		'19110129' => 'Dog',
		'19120217' => 'Pig',
	
		'19130205' => 'Rat',
		'19140125' => 'Buffalo',
		'19150213' => 'Tiger',
		'19160202' => 'Cat',
		'19170122' => 'Dragon',
		'19180210' => 'Snake',
		'19190131' => 'Horse',
		'19200219' => 'Goat',
		'19210207' => 'Monkey',
		'19220127' => 'Cock',
		'19230215' => 'Dog',
		'19240204' => 'Pig',
		
		'19250124' => 'Rat',
		'19260212' => 'Buffalo',
		'19270201' => 'Tiger',
		'19280122' => 'Cat',
		'19290209' => 'Dragon',
		'19300129' => 'Snake',
		'19310216' => 'Horse',
		'19320205' => 'Goat',
		'19330125' => 'Monkey',
		'19340213' => 'Cock',
		'19350203' => 'Dog',
		'19360123' => 'Pig',
		
		'19370210' => 'Rat',
		'19380230' => 'Buffalo',
		'19390218' => 'Tiger',
		'19400207' => 'Cat',
		'19410126' => 'Dragon',
		'19420214' => 'Snake',
		'19430204' => 'Horse',
		'19440124' => 'Goat',
		'19450212' => 'Monkey',
		'19460201' => 'Cock',
		'19470121' => 'Dog',
		'19480209' => 'Pig',
		
		'19490128' => 'Rat',
		'19500216' => 'Buffalo',
		'19510205' => 'Tiger',
		'19520126' => 'Cat',
		'19530213' => 'Dragon',
		'19540202' => 'Snake',
		'19550123' => 'Horse',
		'19560211' => 'Goat',
		'19570130' => 'Monkey',
		'19580217' => 'Cock',
		'19590207' => 'Dog',
		'19600127' => 'Pig',
		
		'19610214' => 'Rat',
		'19620204' => 'Buffalo',
		'19630124' => 'Tiger',
		'19640212' => 'Cat',
		'19650201' => 'Dragon',
		'19660120' => 'Snake',
		'19670208' => 'Horse',
		'19680129' => 'Goat',
		'19690216' => 'Monkey',
		'19700205' => 'Cock',
		'19710126' => 'Dog',
		'19720214' => 'Pig',
		
		'19730202' => 'Rat',
		'19740122' => 'Buffalo',
		'19750210' => 'Tiger',
		'19760130' => 'Cat',
		'19770217' => 'Dragon',
		'19780206' => 'Snake',
		'19790127' => 'Horse',
		'19800215' => 'Goat',
		'19810204' => 'Monkey',
		'19820124' => 'Cock',
		'19830212' => 'Dog',
		'19840201' => 'Pig',
		
		'19850219' => 'Rat',
		'19860208' => 'Buffalo',
		'19870128' => 'Tiger',
		'19880216' => 'Cat',
		'19890205' => 'Dragon',
		'19900126' => 'Snake',
		'19910214' => 'Horse',
		'19920203' => 'Goat',
		'19930122' => 'Monkey',
		'19940209' => 'Cock',
		'19950130' => 'Dog',
		'19960218' => 'Pig',
		
		'19970206' => 'Rat',
		'19980127' => 'Buffalo',
		'19990215' => 'Tiger',
		'20000204' => 'Cat',
		'20010123' => 'Dragon',
		'20020211' => 'Snake',
		'20030131' => 'Horse',
		'20040121' => 'Goat',
		'20050208' => 'Monkey',
		'20060129' => 'Cock',
		'20070218' => 'Dog',
		'20080207' => 'Pig',

		'20090125' => 'Rat',
		'20100213' => 'Buffalo',
		'20110202' => 'Tiger',
		'20120122' => 'Cat',
		'20130209' => 'Dragon',
		'20140130' => 'Snake',
		'20150218' => 'Horse',
		'20160207' => 'Goat',
		'20170127' => 'Monkey',
		'20180215' => 'Cock',
		'20190204' => 'Dog',
		'20200224' => 'Pig',	
	
		'20210211' => 'Rat',
		'20220131' => 'Buffalo',
		'20230121' => 'Tiger',
		'20240209' => 'Cat',
		'20250128' => 'Dragon',
		'20260216' => 'Snake',
		'20270205' => 'Horse',
		'20280125' => 'Goat',
		'20290212' => 'Monkey',
		'20300201' => 'Cock',
		'20310122' => 'Dog',
		'20320208' => 'Pig',

		'99999999' => 'Unknown'
	);

	reset($chinese);
	while ($date > key($chinese))
	{
		next($chinese);
	}
	return $chinese[key($chinese)];
}

?>