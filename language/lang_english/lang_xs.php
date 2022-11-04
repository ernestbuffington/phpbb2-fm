<?php
/** 
*
* @package lang_english
* @version $Id: lang_xs.php,v 1.133.2.33 2004/11/18 17:49:42 acydburn Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

$lang['xs_settings_cache'] = 'Cache Settings';

$lang['xs_warning'] = 'Warning: cache cannot be written.';
$lang['xs_warning_explain'] = 'Check if you have created directory where cache is stored and changed access mode (chmod) to 777 so script could write cache there. If your server is running in safe mode you might consider using "." as filename separator so all cache would be written in same directory (see separator setting below).<br /><br />If cache doesn\'t work on your server for some reason don\'t worry - eXtreme Styles mod increases forum speed many times even without cache.';

$lang['xs_updated'] = 'Configuration updated.';
$lang['xs_updated_explain'] = 'You need to refresh this page before new configuration takes effect. <a href="%s">Click here</a> to refresh page.';

// Options
$lang['xs_use_cache'] = 'Template cache';
$lang['xs_cache_explain'] = 'Cache is saved to disk and will speed up template load times as there is be no need to compile a template every time it is shown.';
$lang['xs_auto_compile'] = 'Automatically save cache';
$lang['xs_auto_compile_explain'] = 'This will automatically compile templates if needed and save to cache directory.';
$lang['xs_auto_recompile'] = 'Automatically re-compile cache';
$lang['xs_auto_recompile_explain'] = 'This will automatically re-compile the cache if the original template file was changed.';
$lang['xs_separator'] = 'Filename separator';
$lang['xs_separator_explain'] = 'If set to "/" then cache will be saved in subdirectories by template name, e.g. the file "subSilver/overall_header.tpl" would be saved to the cache directory as "subSilver/overall__header.php", if set to something else like "." then all cache will be saved to one directory, e.g. the file "subSilver/overall_header.tpl" would be saved to cache directory as "subSilver.overall_header.php"';
$lang['xs_php'] = 'Extension of cache filenames';
$lang['xs_php_explain'] = 'This is extension of cached files. Files are stored in php format so default extension is "php". Do not include dot';
$lang['xs_def_template'] = 'Default template directory';
$lang['xs_def_template_explain'] = 'If tpl file is not found in current template directory then the template system will look for same file in this directory, e.g. if current template is "myTemplate" and script requires file "myTemplate/myfile.tpl" and that file does not exist the template system will look for the file "subSilver/myfile.tpl". Leave blank to disable this feature.';
$lang['xs_check_switches'] = 'Check switches while compiling';
$lang['xs_check_switches_explain'] = 'This feature checks for errors in templates. Turning it off will speed up compilation, but compiler might skip errors in templates if they exist.<br /><br />Smart check will check templates for errors and automatically fix all known errors. Works little bit slower than simple check, but sometimes templates display properly only when error check is disabled. This happens because of bad HTML coding.<br /><br />If cache feature is disabled then for faster compilation it is better to turn this off.';
$lang['xs_check_switches_1'] = 'Smart check';
$lang['xs_check_switches_2'] = 'Simple check';
$lang['xs_use_isset'] = 'Check variables with isset() in compiled code';

// Debug info
$lang['xs_debug_header'] = 'Debug Information';
$lang['xs_debug_explain'] = 'This is debug information used to find and fix problems when configuring the cache.';
$lang['xs_debug_vars'] = 'Template Variables';
$lang['xs_debug_tpl_name'] = 'Template filename';
$lang['xs_debug_cache_filename'] = 'Cache filename';
$lang['xs_debug_data'] = 'Debug data';

$lang['xs_check_hdr'] = 'Checking cache for %s';
$lang['xs_check_filename'] = 'Error: invalid filename';
$lang['xs_check_openfile1'] = 'Error: cannot open file "%s". Will try to create directories...';
$lang['xs_check_openfile2'] = 'Error: cannot open file "%s" for the second time. Giving up...';
$lang['xs_check_nodir'] = 'Checking "%s" - no such directory.';
$lang['xs_check_nodir2'] = 'Error: cannot create directory "%s" - you might need to check permissions.';
$lang['xs_check_createddir'] = 'Created directory "%s"';
$lang['xs_check_dir'] = 'Checking "%s" - directory exists.';
$lang['xs_check_ok'] = 'Opened file "%s" for writing. Everything seems to be ok.';


// Styles
$lang['xs_styles_set_default'] = 'set default';
$lang['xs_styles_no_override'] = 'do not override user settings';
$lang['xs_styles_do_override'] = 'override user settings';
$lang['xs_styles_switch_all'] = 'switch all users to this style';
$lang['xs_styles_switch_all2'] = 'switch all users to:';
$lang['xs_styles_defstyle'] = 'default style';
$lang['xs_styles_available'] = 'Available styles';

?>