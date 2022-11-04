<?php
/** 
*
* @package phpBB2
* @version $Id: changestyle.php,v 1.2 2003/06/29 19:00:44 charly Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);

$url = 'index.'.$phpEx; // fallback, if HTTP_REFERER is not set

if ( isset($HTTP_SERVER_VARS['HTTP_REFERER']) )
{
	$url = $HTTP_SERVER_VARS['HTTP_REFERER'];
}

if ( isset($HTTP_GET_VARS[STYLE_URL]) )
{
	$style = $HTTP_GET_VARS[STYLE_URL];

	if( strpos($url, "?" . STYLE_URL . '=') != false || strpos($url, "&" . STYLE_URL . '=') != false )
	{
		// replace STYLE_URL parameter
		$url = ereg_replace( "([\?&])" . STYLE_URL . "=[^&]*", "\\1" . STYLE_URL . "=" . $style, $url );
	}
	else
	{
		// add STYLE_URL parameter
		$url .= ( ( strpos($url, '?') != false ) ? '&' : '?' ) . STYLE_URL . "=" . $style;
	}
}

header("Location: " . $url);

?>