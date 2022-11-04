<?php
/** 
*
* @package mini_cal
* @version $Id: mini_cal_config.php,v 2.0.4 2005 netclectic Exp $
* @copyright (c) 2004 netclectic - Adrian Cockburn
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

if ( !defined('IN_MINI_CAL') )
{
	die("Hacking attempt");
}

define('MINI_CAL_CALENDAR_VERSION', 'MYCAL');
define('MINI_CAL_LIMIT', $board_config['minical_event_lmt']);
define('MINI_CAL_DAYS_AHEAD', $board_config['minical_upcoming']);
define('MINI_CAL_DATE_SEARCH', $board_config['minical_search']);

// First Day of the Week - 0=Sunday, 1=Monday...6=Saturday
// if you change this remember to change the short day names in lang_main_mini_cal.php
define('MINI_CAL_FDOW', 0);

// Defines the css class to use for mini cal days urls 
define('MINI_CAL_DAY_LINK_CLASS', 'gensmall');

// Defines the css class to use for mini cal today date
define('MINI_CAL_TODAY_CLASS', 'gensmall');

// defines the authentication level required to be able to view the upcoming events
// this relates to the permission level assigned to forum
// possible values:
//		auth_view, auth_read, auth_post, auth_reply, auth_edit, 
//		auth_delte, auth_sticky, auth_announce, auth_vote, auth_pollcreate
define('MINI_CAL_EVENT_AUTH_LEVEL', 'auth_view');

define('MINI_CAL_DATE_PATTERNS', serialize(array('/%a/', '/%b/', '/%c/', '/%d/', '/%e/', '/%m/', '/%y/', '/%Y/', '/%H/', '/%k/', '/%h/', '/%l/', '/%i/', '/%s/', '/%p/')));

?>