<?php
/** 
*
* @package phpBB2
* @version $Id: profile_biorhythm_img.php,v 1.0.3 2/8/2005 5:24 PM mj Exp $
* @copyright (c) 2004, 2005 Fully Modded MODS
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
* Original Biorhythm by Till Gerken
* http://www.zend.com/zend/tut/dynamic.php
* Adapted for use with phpBB and the Birthday MOD 
*
*/

define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_PROFILE);
init_userprefs($userdata);
//
// End session management
//

//
// Is the user logged in?
//
if ( !$userdata['session_logged_in'] ) 
{ 
	redirect('login.'.$phpEx.'?redirect=profile_biorhythm.'.$phpEx); 
	exit; 
} 


//
// Check if we already have a date to work with,
// if not send to profile for the user to enter one
//
if ( $userdata['user_birthday'] == 999999 )
{
	$template->assign_vars(array(
		'META' => '<meta http-equiv="refresh" content="3;url=' . append_sid("profile.$phpEx?mode=editprofile&ucp=profile_info") . '">')
	);
	
	$message = $lang['bio_enter_birthday'] . '<br /><br />' . sprintf($lang['bio_click_enter_birthday'], '<a href="' . append_sid('profile.'.$phpEx.'?mode=editprofile&ucp=profile_info') . '">', '</a>');
	message_die(GENERAL_MESSAGE, $message); 
}


// Add to Board Configuration ???
// specify diagram parameters (these are global)
$diagramWidth = 710;
$diagramHeight = 400;
$daysToShow = 30;


//
// Function to draw a curve of the biorythm
// Parameters are the day number for which to draw,
// period of the specific curve and its color
//
function drawRhythm($daysAlive, $period, $color)
{
    global $daysToShow, $image, $diagramWidth, $diagramHeight;

    // get day on which to center
    $centerDay = $daysAlive - ($daysToShow / 2);

    // calculate diagram parameters
    $plotScale = ($diagramHeight - 25) / 2;
    $plotCenter = ($diagramHeight - 25) / 2;

    // draw the curve
    for($x = 0; $x <= $daysToShow; $x++)
    {
		// calculate phase of curve at this day, then Y value
		// within diagram
		$phase = (($centerDay + $x) % $period) / $period * 2 * pi();
		$y = 1 - sin($phase) * (float)$plotScale + (float)$plotCenter;

		// draw line from last point to current point
		if($x > 0)
		    imageLine($image, $oldX, $oldY, $x * $diagramWidth / $daysToShow, $y, $color);

		// save current X/Y coordinates as start point for next line
		$oldX = $x * $diagramWidth / $daysToShow;
		$oldY = $y;
	}
}

//
// Function to replace the PHP calendar addon
// If the image will not show on your server
// try uncommenting the function below
/*
function GregorianToJD ($month, $day, $year) 
{
	if ($month > 2) 
	{
    	$month = $month - 3;
   	} 
   	else 
   	{
    	$month = $month + 9;
       	$year = $year - 1;
   }
   
   $c = floor($year / 100);
   $ya = $year - (100 * $c);
   $j = floor((146097 * $c) / 4);
   $j += floor((1461 * $ya)/4);
   $j += floor(((153 * $month) + 2) / 5);
   $j += $day + 1721119;
   
   return $j;
}
*/
		
//
// Convert user birthdate to MM/DD/YYYY
// Get different parts of the date
//
$birthdate = realdate('m/d/Y', $userdata['user_birthday']); 
$birthMonth = substr($birthdate, 0, 2);
$birthDay = substr($birthdate, 3, 2);
$birthYear = substr($birthdate, 6, 4);

// Calculate the number of days this person is alive
// this works because Julian dates specify an absolute number
// of days -> the difference between Julian birthday and
// "Julian today" gives the number of days alive
//
$daysGone = abs(gregorianToJD($birthMonth, $birthDay, $birthYear) - gregorianToJD(date("m"), date("d"), date("Y")));

//
// Create image
//
$image = imageCreate($diagramWidth, $diagramHeight);

// Allocate all required colors
$colorBackgr = imageColorAllocate($image, 192, 192, 192);
$colorForegr = imageColorAllocate($image, 255, 255, 255);
$colorGrid = imageColorAllocate($image, 0, 0, 0);
$colorCross = imageColorAllocate($image, 0, 0, 0);
$colorPhysical = imageColorAllocate($image, 0, 0, 255);
$colorEmotional = imageColorAllocate($image, 255, 0, 0);
$colorIntellectual = imageColorAllocate($image, 0, 255, 0);

// Clear the image with the background color
imageFilledRectangle($image, 0, 0, $width - 1, $height - 1, $colorBackgr);

// Calculate start date for diagram and start drawing
$nrSecondsPerDay = 60 * 60 * 24;
$diagramDate = time() - ($daysToShow / 2 * $nrSecondsPerDay) + $nrSecondsPerDay;

for ($i = 1; $i < $daysToShow; $i++)
{
    $thisDate = getDate($diagramDate);
    $xCoord = ($diagramWidth / $daysToShow) * $i;

    // Draw day mark and day number
    imageLine($image, $xCoord, $diagramHeight - 25, $xCoord, $diagramHeight - 20, $colorGrid);
    imageString($image, 3, $xCoord - 5, $diagramHeight - 16, $thisDate["mday"], $colorGrid);

    $diagramDate += $nrSecondsPerDay;
}

// Draw rectangle around diagram (marks its boundaries)
imageRectangle($image, 0, 0, $diagramWidth - 1, $diagramHeight - 20, $colorGrid);

// Draw middle cross
imageLine($image, 0, ($diagramHeight - 20) / 2, $diagramWidth, ($diagramHeight - 20) / 2, $colorCross);
imageLine($image, $diagramWidth / 2, 0, $diagramWidth / 2, $diagramHeight - 20, $colorCross);

// Print descriptive text into the diagram
imageString($image, 3, 10, 10, $lang['Birthday'] . ": $birthDay.$birthMonth.$birthYear", $colorCross);
imageString($image, 3, 10, 26, $lang['bio_today'] . ": " . date("d.m.Y"), $colorCross);
imageString($image, 3, 10, $diagramHeight - 42, $lang['bio_physical'], $colorPhysical);
imageString($image, 3, 10, $diagramHeight - 58, $lang['bio_emotional'], $colorEmotional);
imageString($image, 3, 10, $diagramHeight - 74, $lang['bio_intellectual'], $colorIntellectual);

// now draw each curve with its appropriate parameters
drawRhythm($daysGone, 23, $colorPhysical);
drawRhythm($daysGone, 28, $colorEmotional);
drawRhythm($daysGone, 33, $colorIntellectual);

// Set the content type
header("Content-type: image/png");
//header("Content-type: image/gif");

// Create an interlaced image for better loading in the browser
imageInterlace($image, 1);

// Mark background color as being transparent
imageColorTransparent($image, $colorBackgr);

// Now send the picture to the client (this outputs all image data directly)
imagePNG($image);

?>