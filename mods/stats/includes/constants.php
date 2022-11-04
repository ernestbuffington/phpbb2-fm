<?php
/** 
*
* @package stats
* @version $Id: constants.php,v 4.2.8 2003/03/16 18:38:30 acydburn Exp $
* @copyright (c) 2003 Meik Sievertsen
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}

// Define Table Constants
define('MODULES_TABLE', $prefix . 'modules');
define('MODULE_INFO_TABLE', $prefix . 'module_info');
define('STATS_CONFIG_TABLE', $prefix . 'stats_config');
define('CACHE_TABLE', $prefix . 'module_cache');
define('MODULE_ADMIN_TABLE', $prefix . 'module_admin_panel');
define('SMILIE_INDEX_TABLE', 'stats_smilies_index');
define('SMILIE_INFO_TABLE', 'stats_smilies_info');
define('MODULE_GROUP_AUTH_TABLE', $prefix . 'module_group_auth');

define('STATS_DEBUG', true); // Debug Mode
// define('STATS_DEBUG', false); // Disable Debug Mode

// Cache Defines
define('HIGHEST_PRIORITY', 100);
define('LOWEST_PRIORITY', 101);
define('EQUAL_PRIORITY', 102);

?>