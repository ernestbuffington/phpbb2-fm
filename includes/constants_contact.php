<?php
/** 
*
* @package includes
* @version $Id: constants_contact.php,v 1.0.0 2003/12/23 23:21:42 acydburn Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
	
if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}

// Set paths for including files later
define('CONTACT_PATH', $phpbb_root_path . 'mods/contact/');
define('CONTACT_URL', $phpbb_root_path . 'contact.' . $phpEx);

// Page number for session handling
define('PAGE_CONTACT', -8050);

// Table names
define('CONTACT_TABLE', $prefix.'im_buddy_list');

// Alternate table names, for possible integration of Prillian
// with other buddy/ignore hacks
define('BUDDY_TABLE', $prefix.'im_buddy_list');
define('IGNORE_TABLE', $prefix.'im_buddy_list');
define('DISALLOW_TABLE', $prefix.'im_buddy_list');


// Allows users to set themselves as buddies. This is mainly used only
// in debugged during development.  You should probably leave it at false.
//define('ALLOW_BUDDY_SELF', true);
define('ALLOW_BUDDY_SELF', false);

?>