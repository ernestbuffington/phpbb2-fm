<?php
/** 
*
* @package phpBB2
* @version $Id: digest_common.php Indemnity83 Exp $
* @copyright (c) Mark D. Hamill & Indemnity83
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
	
if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}

// Include language files
$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_digests.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_digests.' . $phpEx);
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_main.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_main.' . $phpEx);

include_once($phpbb_root_path . 'includes/functions_digests.'.$phpEx);

define('DIGEST_HTML', 1);
define('DIGEST_TEXT', 0);
define('ALL_FORUMS', 999);
define('FULL_TEXT', -1);
define('SHORT_TEXT', 1);
define('NO_TEXT', 0);
define('DUMP_VERIFY_CODE', '127dd755b816707caa5cf78da8f2de0e');

//
// Get Digest Config
//
global $digest_config;

$cache_dir = $phpbb_root_path . '/cache';
$cache_file = $cache_dir . '/config_digest.php';

function get_digest_config()
{
	global $db;

	$board_config = array();

	$sql = "SELECT *
		FROM " . DIGEST_CONFIG_TABLE;
	if(!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not query digest config information.', '', __LINE__, __FILE__, $sql);
	}
	
	while($row = $db->sql_fetchrow($result))
	{
		$digest_config[$row['config_name']] = $row['config_value'];
	}
	$db->sql_freeresult($result);

	return $digest_config;
}

if (file_exists($cache_dir) && is_dir($cache_dir) && is_writable($cache_dir))
{
	if (file_exists($cache_file))
///	if (file_exists($cache_file) && (time() < ''))
	{
		include($cache_file);
	}
	else
	{
		$digest_config = get_digest_config();
		$fp = @fopen($cache_file, 'wt+');
		if ($fp)
		{
			$lines = array();
			foreach ($digest_config as $k => $v)
			{
				if (is_int($v))
				{
					$lines[] = "'$k' => $v";
				}
				else if (is_bool($v))
				{
					$lines[] = "'$k' => " . (($v) ? 'TRUE' : 'FALSE');
				}
				else
				{
					$lines[] = "'$k' => '" . str_replace("'", "\\'", str_replace('\\', '\\\\', $v)) . "'";
				}
			}
			fwrite($fp, '<?php if (!defined(\'IN_PHPBB\')) { die(\'Hacking attempt\'); } $digest_config = array(' . implode(',', $lines) . '); ?>');
			fclose($fp);

			@chmod($cache_file, 0777);
		}
	}
}
else
{
	$digest_config = get_digest_config();
}

//
// Get Default Style 
//
$sql = "SELECT style_name
	FROM " . THEMES_TABLE . "
	WHERE themes_id = " . $board_config['default_style']; 
if(!$result = $db->sql_query($sql))
{
	message_die(GENERAL_ERROR, 'Could not query theme information.', '', __LINE__, __FILE__, $sql);
}
$row = $db->sql_fetchrow($result);
$theme_name = $row['style_name'];
$db->sql_freeresult($result);

?>