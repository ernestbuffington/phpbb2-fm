<?php
/** 
*
* @package includes
* @version $Id: constants_usage_stats.php,v 1.47.2.5 2003/01/28 17:49:42 calennert Exp $
* @copyright (c) 2003 Chris Lennert
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
	exit;
}

// Property names correspond to CONFIG_TABLE rows used by the usage_stats mod
define('BBUS_CONFIGPROP_VIEWLEVEL_NAME', 'bb_usage_stats_viewlevel');
define('BBUS_CONFIGPROP_SPECIALGRP_NAME', 'bb_usage_stats_specialgrp');
define('BBUS_CONFIGPROP_VIEWOPTIONS_NAME', 'bb_usage_stats_viewoptions');
define('BBUS_CONFIGPROP_PRSCALE_NAME', 'bb_usage_stats_prscale');
define('BBUS_CONFIGPROP_TRSCALE_NAME', 'bb_usage_stats_trscale');

// Feel free to modify if you feel you need a broader range of scaling options
define('BBUS_SCALING_MIN', 1);
define('BBUS_SCALING_MAX', 10000);

// View Level Flags
define('BBUS_VIEWLEVEL_ANONYMOUS', 1); //allow anonymous user to view stats
define('BBUS_VIEWLEVEL_SELF', 2); //allow any user to view their own stats
define('BBUS_VIEWLEVEL_USERS', 4); //allow any user to others' stats
define('BBUS_VIEWLEVEL_MODERATORS', 8); //allow moderators to view stats
define('BBUS_VIEWLEVEL_ADMINS', 16); //allow admins to view stats (DEFAULT = ON)
define('BBUS_VIEWLEVEL_SPECIALGRP', 32); //allow special group to view stats

// View Option Flags
define('BBUS_VIEWOPTION_SHOW_ALL_FORUMS', 1); // Display all categories and sections, not just those containing user's posts
define('BBUS_VIEWOPTION_PCTUTUP_COLUMN_VISIBLE', 2); // Display %UTUP Column
define('BBUS_VIEWOPTION_MISC_SECTION_VISIBLE', 4); // Display Miscellaneous Section
define('BBUS_VIEWOPTION_MISC_TOTPRUNEDPOSTS_VISIBLE', 8); // Display "Total Unpruned Posts" row in Misc Section
define('BBUS_VIEWOPTION_VIEWER_SCALABLE_PR', 16); // Allow stat viewers to change the post rate scaling factor
define('BBUS_VIEWOPTION_VIEWER_SCALABLE_TR', 32); // Allow stat viewers to change the topic rate scaling factor

// Config Property Defaults
define('BBUS_CONFIGPROP_VIEWLEVEL_DEFAULT', BBUS_VIEWLEVEL_ADMINS);
define('BBUS_CONFIGPROP_SPECIALGRP_DEFAULT', -1);  // None selected
define('BBUS_CONFIGPROP_VIEWOPTIONS_DEFAULT', BBUS_VIEWOPTION_SHOW_ALL_FORUMS);
define('BBUS_CONFIGPROP_PRSCALE_DEFAULT', 1); // Initial value for post rate scaling
define('BBUS_CONFIGPROP_TRSCALE_DEFAULT', 1); // Initial value for topic rate scaling

?>