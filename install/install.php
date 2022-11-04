<?php
/** 
*
* @package install
* @version $Id: install.php,v 1.6.2.13 2005/03/15 18:33:16 acydburn Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

$required_php_version = '4.4.1';

// ---------
// FUNCTIONS
//
function page_header($text, $form_action = false)
{
	global $phpEx, $lang;

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $lang['ENCODING']; ?>">
<meta http-equiv="Content-Style-Type" content="text/css">
<title><?php echo $lang['Welcome_install'];?></title>
<style type="text/css">
* { margin-top: 0; }
body {  font-family: "Lucida Grande", Verdana, Helvetica, Arial, sans-serif; color: #536482; background: #E4EDF0 url("../templates/subSilver/images/bg_header.gif") 0 0 repeat-x; font-size: 62.5%; scrollbar-face-color: #DCE1E5; scrollbar-highlight-color: #FFFFFF; scrollbar-shadow-color: #DCE1E5; scrollbar-3dlight-color: #C7CFD7; scrollbar-arrow-color:  #006699; scrollbar-track-color: #ECECEC; scrollbar-darkshadow-color: #98AAB1; margin-top: 0; }
font,th,td { font-size: 11px; color: #323D4F; font-family: Verdana, Arial, Helvetica, sans-serif }
p { margin-bottom: 1.0em; line-height: 1.5em; font-size: 11px; }
ul { list-style: disc; margin: 0 0 1em 2em; }
a:link,a:active,a:visited { color: #006699 ;text-decoration: none; }
a:hover	{ text-decoration: underline; color: #DD6900; }
hr { border: 0 none; border-top: 1px solid #C7CFD7; margin-bottom: 5px; padding-bottom: 5px; height: 1px; }
.bodyline { background-color: #FFFFFF; border: 1px #98AAB1 solid; }
td.row1	{ background-color: #ECECEC; }
td.row2	{ background-color: #DCE1E5; }
th { font-weight: bold; border: #FFFFFF; border-style: solid; height: 25px; color: #FFA34F; font-size: 11px; font-weight: bold; background-color: #006699; background-image: url(../templates/subSilver/images/cellpic3.gif); }
td.catBottom { height: 28px; border-width: 0px 0px 0px 0px; font-size: 12px; background-image: url(../templates/subSilver/images/cellpic1.gif); background-color:#C7CFD7; border: #FFFFFF; border-style: solid; }
th.thHead { font-size: 12px; border-width: 0px 0px 0px 0px; }
td.catBottom { border-width: 0px 0px 0px 0px; }
.copyright { font-size: 10px; color: #444444; letter-spacing: -1px; }
a.copyright { color: #444444; text-decoration: none; }
a.copyright:hover { color: #323D4F; text-decoration: underline; }
h1 { margin: 0; font: bold 1.8em "Lucida Grande", 'Trebuchet MS', Verdana, sans-serif; text-decoration: none; color: #323D4F; }
input, select { color: #323D4F; background-color: #FFFFFF; font: normal 11px Verdana, Arial, Helvetica, sans-serif; border-color: #323D4F; border-top-width : 1px; border-right-width : 1px; border-bottom-width : 1px; border-left-width : 1px; }
input { text-indent : 2px; }
input.mainoption { background-color: #FAFAFA; font-weight: bold; }
img, .forumline img { border: 0; }
.ok { color: #009900; }
.error { color: #D20000; }
#wrap { padding: 0 20px 0px 20px; min-width: 615px; }
#page-header { text-align: right; background: url("../templates/subSilver/images/logo_acp_phpBB.gif") 0 0 no-repeat; height: 84px; }
#page-header h1 { font-family: "Lucida Grande", Verdana, Arial, Helvetica, sans-serif; color: #323D4F; font-size: 1.5em; font-weight: normal; }
#page-header p { font-size: 1.1em; }
</style>
</head>
<body topmargin="0" bgcolor="#FFFFFF" text="#323D4F" link="#006699" vlink="#5493B4">
<a name="top"></a>

<div id="wrap">
	<div id="page-header"><br />
		<h1><?php echo $lang['Welcome_install'];?></h1>
		<p></p>
	</div>
</div>
<table align="center" width="98%" cellpadding="10" cellspacing="0">
<tr> 
	<td class="bodyline"><table width="100%" cellpadding="5" cellspacing="0">
  	<tr> 
  		<td>
  	
		<h1><?php echo $lang['Welcome_install'];?></h1>
		
		<p><?php echo $text; ?></p>

		<br />
					
		<table width="75%" align="center" cellpadding="4" cellspacing="1" class="forumline"><form action="<?php echo ($form_action) ? $form_action : 'install.'.$phpEx; ?>" name="install" method="post">
<?php

}

function page_footer()
{

?>
			</form></table></td>
	</tr>
	<tr>
		<td colspan="2" height="1"><img src="../images/spacer.gif" height="1" alt="" title="" /></td>
	</tr>
	</table></td>
</tr>
</table>
<div align="center" class="copyright">
Powered by <a href="http://www.phpbb.com/" target="_blank" class="copyright">phpBB</a> &copy; 2001, <?php echo date('Y'); ?> phpBB Group
<br />Modified by <a href="http://phpbb-fm.com/" target="_blank" class="copyright" title="Fully Modded phpBB">Fully Modded phpBB</a> &copy; 2002, <?php echo date('Y'); ?>
</div>
</body>
</html>
		
<?php

}

function page_common_form($hidden, $submit)
{
	?>
	<tr> 
		<td class="catBottom" align="center" colspan="4"><?php echo $hidden; ?><input type="submit" value="<?php echo $submit; ?>" class="mainoption" /></td>
	</tr>
<?php
}

function page_upgrade_form()
{
	global $lang;

?>
					<tr>
						<td class="row2" align="center" colspan="4"><?php echo $lang['continue_upgrade']; ?></td>
					</tr>
					<tr>
						<td class="catBottom" align="center" colspan="4"><input type="submit" name="upgrade_now" value="<?php echo $lang['upgrade_submit']; ?>" /></td>
					</tr>
<?php 

}

function page_error($error_title, $error)
{

?>
					<tr>
						<th class="thHead"><?php echo $error_title; ?></th>
					</tr>
					<tr>
						<td class="row1" height="100"><?php echo $error; ?></td>
					</tr>
<?php

}

// Guess an initial language ... borrowed from phpBB 2.2 it's not perfect, 
// really it should do a straight match first pass and then try a "fuzzy"
// match on a second pass instead of a straight "fuzzy" match.
function guess_lang()
{
	global $phpbb_root_path, $HTTP_SERVER_VARS;

	// The order here _is_ important, at least for major_minor
	// matches. Don't go moving these around without checking with
	// me first - psoTFX
	$match_lang = array(
		'arabic'					=> 'ar([_-][a-z]+)?', 
		'bulgarian'					=> 'bg', 
		'catalan'					=> 'ca', 
		'czech'						=> 'cs', 
		'danish'					=> 'da', 
		'german'					=> 'de([_-][a-z]+)?',
		'english'					=> 'en([_-][a-z]+)?', 
		'estonian'					=> 'et', 
		'finnish'					=> 'fi', 
		'french'					=> 'fr([_-][a-z]+)?', 
		'greek'						=> 'el', 
		'spanish_argentina'			=> 'es[_-]ar', 
		'spanish'					=> 'es([_-][a-z]+)?', 
		'gaelic'					=> 'gd', 
		'galego'					=> 'gl', 
		'gujarati'					=> 'gu', 
		'hebrew'					=> 'he', 
		'hindi'						=> 'hi', 
		'croatian'					=> 'hr', 
		'hungarian'					=> 'hu', 
		'icelandic'					=> 'is', 
		'indonesian'				=> 'id([_-][a-z]+)?', 
		'italian'					=> 'it([_-][a-z]+)?', 
		'japanese'					=> 'ja([_-][a-z]+)?', 
		'korean'					=> 'ko([_-][a-z]+)?', 
		'latvian'					=> 'lv', 
		'lithuanian'				=> 'lt', 
		'macedonian'				=> 'mk', 
		'dutch'						=> 'nl([_-][a-z]+)?', 
		'norwegian'					=> 'no', 
		'punjabi'					=> 'pa', 
		'polish'					=> 'pl', 
		'portuguese_brazil'			=> 'pt[_-]br', 
		'portuguese'				=> 'pt([_-][a-z]+)?', 
		'romanian'					=> 'ro([_-][a-z]+)?', 
		'russian'					=> 'ru([_-][a-z]+)?', 
		'slovenian'					=> 'sl([_-][a-z]+)?', 
		'albanian'					=> 'sq', 
		'serbian'					=> 'sr([_-][a-z]+)?', 
		'slovak'					=> 'sv([_-][a-z]+)?', 
		'swedish'					=> 'sv([_-][a-z]+)?', 
		'thai'						=> 'th([_-][a-z]+)?', 
		'turkish'					=> 'tr([_-][a-z]+)?', 
		'ukranian'					=> 'uk([_-][a-z]+)?', 
		'urdu'						=> 'ur', 
		'viatnamese'				=> 'vi',
		'chinese_traditional_taiwan' => 'zh[_-]tw',
		'chinese_simplified'		=> 'zh', 
	);

	if (isset($HTTP_SERVER_VARS['HTTP_ACCEPT_LANGUAGE']))
	{
		$accept_lang_ary = explode(',', $HTTP_SERVER_VARS['HTTP_ACCEPT_LANGUAGE']);
		for ($i = 0; $i < sizeof($accept_lang_ary); $i++)
		{
			@reset($match_lang);
			while (list($lang, $match) = each($match_lang))
			{
				if (preg_match('#' . $match . '#i', trim($accept_lang_ary[$i])))
				{
					if (file_exists(@phpbb_realpath($phpbb_root_path . 'language/lang_' . $lang)))
					{
						return $lang;
					}
				}
			}
		}
	}

	return 'english';
	
}
//
// FUNCTIONS
// ---------

// Begin
error_reporting  (E_ERROR | E_WARNING | E_PARSE); // This will NOT report uninitialized variables
set_magic_quotes_runtime(0); // Disable magic_quotes_runtime

// PHP5 with register_long_arrays off?
if (!isset($HTTP_POST_VARS) && isset($_POST))
{
	$HTTP_POST_VARS = $_POST;
	$HTTP_GET_VARS = $_GET;
	$HTTP_SERVER_VARS = $_SERVER;
	$HTTP_COOKIE_VARS = $_COOKIE;
	$HTTP_ENV_VARS = $_ENV;
	$HTTP_POST_FILES = $_FILES;

	// _SESSION is the only superglobal which is conditionally set
	if (isset($_SESSION))
	{
		$HTTP_SESSION_VARS = $_SESSION;
	}
}

// Slash data if it isn't slashed
if (!get_magic_quotes_gpc())
{
	if (is_array($HTTP_GET_VARS))
	{
		while (list($k, $v) = each($HTTP_GET_VARS))
		{
			if (is_array($HTTP_GET_VARS[$k]))
			{
				while (list($k2, $v2) = each($HTTP_GET_VARS[$k]))
				{
					$HTTP_GET_VARS[$k][$k2] = addslashes($v2);
				}
				@reset($HTTP_GET_VARS[$k]);
			}
			else
			{
				$HTTP_GET_VARS[$k] = addslashes($v);
			}
		}
		@reset($HTTP_GET_VARS);
	}

	if (is_array($HTTP_POST_VARS))
	{
		while (list($k, $v) = each($HTTP_POST_VARS))
		{
			if (is_array($HTTP_POST_VARS[$k]))
			{
				while (list($k2, $v2) = each($HTTP_POST_VARS[$k]))
				{
					$HTTP_POST_VARS[$k][$k2] = addslashes($v2);
				}
				@reset($HTTP_POST_VARS[$k]);
			}
			else
			{
				$HTTP_POST_VARS[$k] = addslashes($v);
			}
		}
		@reset($HTTP_POST_VARS);
	}

	if (is_array($HTTP_COOKIE_VARS))
	{
		while (list($k, $v) = each($HTTP_COOKIE_VARS))
		{
			if (is_array($HTTP_COOKIE_VARS[$k]))
			{
				while (list($k2, $v2) = each($HTTP_COOKIE_VARS[$k]))
				{
					$HTTP_COOKIE_VARS[$k][$k2] = addslashes($v2);
				}
				@reset($HTTP_COOKIE_VARS[$k]);
			}
			else
			{
				$HTTP_COOKIE_VARS[$k] = addslashes($v);
			}
		}
		@reset($HTTP_COOKIE_VARS);
	}
}

// Begin main prog
define('IN_PHPBB', true);
// Uncomment the following line to completely disable the ftp option...
// define('NO_FTP', true);
$phpbb_root_path = './../';
include($phpbb_root_path.'extension.inc');

// Initialise some basic arrays
$userdata = array();
$lang = array();
$error = false;

// Include some required functions
include($phpbb_root_path.'includes/constants.'.$phpEx);
include($phpbb_root_path.'includes/functions.'.$phpEx);
include($phpbb_root_path.'includes/sessions.'.$phpEx);

// Define schema info
$available_dbms = array(
	'mysql' => array(
		'LABEL'			=> 'MySQL',
		'SCHEMA'		=> 'mysql', 
		'DELIM'			=> ';',
		'DELIM_BASIC' 	=> ';',
		'COMMENTS'		=> 'remove_remarks'
	), 
);

// Obtain various vars
$confirm = (isset($HTTP_POST_VARS['confirm'])) ? true : false;
$cancel = (isset($HTTP_POST_VARS['cancel'])) ? true : false;

if (isset($HTTP_POST_VARS['install_step']) || isset($HTTP_GET_VARS['install_step']))
{
	$install_step = (isset($HTTP_POST_VARS['install_step'])) ? $HTTP_POST_VARS['install_step'] : $HTTP_GET_VARS['install_step'];
}
else
{
	$install_step = '';
}

$upgrade = 0;
$dbms = 'mysql';

$dbhost = (!empty($HTTP_POST_VARS['dbhost'])) ? $HTTP_POST_VARS['dbhost'] : 'localhost';
$dbuser = (!empty($HTTP_POST_VARS['dbuser'])) ? $HTTP_POST_VARS['dbuser'] : '';
$dbpasswd = (!empty($HTTP_POST_VARS['dbpasswd'])) ? $HTTP_POST_VARS['dbpasswd'] : '';
$dbname = (!empty($HTTP_POST_VARS['dbname'])) ? $HTTP_POST_VARS['dbname'] : '';

$table_prefix = (!empty($HTTP_POST_VARS['prefix'])) ? $HTTP_POST_VARS['prefix'] : '';

$admin_name = (!empty($HTTP_POST_VARS['admin_name'])) ? $HTTP_POST_VARS['admin_name'] : '';
$admin_pass1 = (!empty($HTTP_POST_VARS['admin_pass1'])) ? $HTTP_POST_VARS['admin_pass1'] : '';
$admin_pass2 = (!empty($HTTP_POST_VARS['admin_pass2'])) ? $HTTP_POST_VARS['admin_pass2'] : '';

$ftp_path = (!empty($HTTP_POST_VARS['ftp_path'])) ? $HTTP_POST_VARS['ftp_path'] : '';
$ftp_user = (!empty($HTTP_POST_VARS['ftp_user'])) ? $HTTP_POST_VARS['ftp_user'] : '';
$ftp_pass = (!empty($HTTP_POST_VARS['ftp_pass'])) ? $HTTP_POST_VARS['ftp_pass'] : '';

if (isset($HTTP_POST_VARS['lang']) && preg_match('#^[a-z_]+$#', $HTTP_POST_VARS['lang']))
{
	$language = strip_tags($HTTP_POST_VARS['lang']);
}
else
{
	$language = guess_lang();
}

$board_email = (!empty($HTTP_POST_VARS['board_email'])) ? $HTTP_POST_VARS['board_email'] : '';
$script_path = (!empty($HTTP_POST_VARS['script_path'])) ? $HTTP_POST_VARS['script_path'] : str_replace('install', '', dirname($HTTP_SERVER_VARS['PHP_SELF']));

if (!empty($HTTP_POST_VARS['server_name']))
{
	$server_name = $HTTP_POST_VARS['server_name'];
}
else
{
	// Guess at some basic info used for install..
	if (!empty($HTTP_SERVER_VARS['SERVER_NAME']) || !empty($HTTP_ENV_VARS['SERVER_NAME']))
	{
		$server_name = (!empty($HTTP_SERVER_VARS['SERVER_NAME'])) ? $HTTP_SERVER_VARS['SERVER_NAME'] : $HTTP_ENV_VARS['SERVER_NAME'];
	}
	else if (!empty($HTTP_SERVER_VARS['HTTP_HOST']) || !empty($HTTP_ENV_VARS['HTTP_HOST']))
	{
		$server_name = (!empty($HTTP_SERVER_VARS['HTTP_HOST'])) ? $HTTP_SERVER_VARS['HTTP_HOST'] : $HTTP_ENV_VARS['HTTP_HOST'];
	}
	else
	{
		$server_name = '';
	}
}

if (!empty($HTTP_POST_VARS['server_port']))
{
	$server_port = $HTTP_POST_VARS['server_port'];
}
else
{
	if (!empty($HTTP_SERVER_VARS['SERVER_PORT']) || !empty($HTTP_ENV_VARS['SERVER_PORT']))
	{
		$server_port = (!empty($HTTP_SERVER_VARS['SERVER_PORT'])) ? $HTTP_SERVER_VARS['SERVER_PORT'] : $HTTP_ENV_VARS['SERVER_PORT'];
	}
	else
	{
		$server_port = '80';
	}
}

// Open config.php ... if it exists
if (@file_exists(@phpbb_realpath('config.'.$phpEx)))
{
	include($phpbb_root_path.'config.'.$phpEx);
}

// Is phpBB already installed? Yes? Redirect to the index
if (defined("PHPBB_INSTALLED"))
{
	redirect('../index.'.$phpEx);
}

// Import language file, setup template ...
include($phpbb_root_path.'language/lang_' . $language . '/lang_main.'.$phpEx);
include($phpbb_root_path.'language/lang_' . $language . '/lang_admin.'.$phpEx);

// Ok for the time being I'm commenting this out whilst I'm working on
// better integration of the install with upgrade as per Bart's request
// JLH
if ($upgrade == 1)
{
	// require('upgrade.'.$phpEx);
	$install_step = 1;
}

// What do we need to do?
if (!empty($HTTP_POST_VARS['send_file']) && $HTTP_POST_VARS['send_file'] == 1 && empty($HTTP_POST_VARS['upgrade_now']))
{
	header('Content-Type: text/x-delimtext; name="config.' . $phpEx . '"');
	header('Content-disposition: attachment; filename="config.' . $phpEx . '"');

	// We need to stripslashes no matter what the setting of magic_quotes_gpc is
	// because we add slashes at the top if its off, and they are added automaticlly 
	// if it is on.
	echo stripslashes($HTTP_POST_VARS['config_data']);

	exit;
}
else if (!empty($HTTP_POST_VARS['send_file']) && $HTTP_POST_VARS['send_file'] == 2)
{
	$s_hidden_fields = '<input type="hidden" name="config_data" value="' . htmlspecialchars(stripslashes($HTTP_POST_VARS['config_data'])) . '" />';
	$s_hidden_fields .= '<input type="hidden" name="ftp_file" value="1" />';

	if ($upgrade == 1)
	{
		$s_hidden_fields .= '<input type="hidden" name="upgrade" value="1" />';
	}

	page_header($lang['ftp_instructs']);

?>
					<tr>
						<th class="thHead" colspan="4"><?php echo $lang['ftp_info']; ?></th>
					</tr>
					<tr>
						<td class="row1" width="50%"><b><?php echo $lang['ftp_path']; ?>:</b></td>
						<td colspan="3" class="row2"><input type="text" name="ftp_dir"></td>
					</tr>
					<tr>
						<td class="row1"><b><?php echo $lang['ftp_username']; ?>:</b></td>
						<td colspan="3" class="row2"><input type="text" name="ftp_user"></td>
					</tr>
					<tr>
						<td class="row1"><b><?php echo $lang['ftp_password']; ?>:</b></td>
						<td colspan="3" class="row2"><input type="password" name="ftp_pass"></td>
					</tr>
<?php

	page_common_form($s_hidden_fields, $lang['Transfer_config']);
	page_footer();
	exit;

}
else if (!empty($HTTP_POST_VARS['ftp_file']))
{
	// Try to connect ...
	$conn_id = @ftp_connect('localhost');
	$login_result = @ftp_login($conn_id, "$ftp_user", "$ftp_pass");

	if (!$conn_id || !$login_result)
	{
		page_header($lang['NoFTP_config']);

		// Error couldn't get connected... Go back to option to send file...
		$s_hidden_fields = '<input type="hidden" name="config_data" value="' . htmlspecialchars(stripslashes($HTTP_POST_VARS['config_data'])) . '" />';
		$s_hidden_fields .= '<input type="hidden" name="send_file" value="1" />';

		// If we're upgrading ...
		if ($upgrade == 1)
		{
			$s_hidden_fields .= '<input type="hidden" name="upgrade" value="1" />';
			$s_hidden_fields .= '<input type="hidden" name="dbms" value="'.$dmbs.'" />';
			$s_hidden_fields .= '<input type="hidden" name="prefix" value="'.$table_prefix.'" />';
			$s_hidden_fields .= '<input type="hidden" name="dbhost" value="'.$dbhost.'" />';
			$s_hidden_fields .= '<input type="hidden" name="dbname" value="'.$dbname.'" />';
			$s_hidden_fields .= '<input type="hidden" name="dbuser" value="'.$dbuser.'" />';
			$s_hidden_fields .= '<input type="hidden" name="dbpasswd" value="'.$dbpasswd.'" />';
			$s_hidden_fields .= '<input type="hidden" name="install_step" value="1" />';
			$s_hidden_fields .= '<input type="hidden" name="admin_pass1" value="1" />';
			$s_hidden_fields .= '<input type="hidden" name="admin_pass2" value="1" />';
			$s_hidden_fields .= '<input type="hidden" name="server_port" value="'.$server_port.'" />';
			$s_hidden_fields .= '<input type="hidden" name="server_name" value="'.$server_name.'" />';
			$s_hidden_fields .= '<input type="hidden" name="script_path" value="'.$script_path.'" />';
			$s_hidden_fields .= '<input type="hidden" name="board_email" value="'.$board_email.'" />';

			page_upgrade_form();
		}
		else
		{
			page_common_form($s_hidden_fields, $lang['Download_config']);

		}

		page_footer();
		exit;
	}
	else
	{
		// Write out a temp file...
		$tmpfname = @tempnam('/tmp', 'cfg');

		@unlink($tmpfname); // unlink for safety on php4.0.3+

		$fp = @fopen($tmpfname, 'w');

		@fwrite($fp, stripslashes($HTTP_POST_VARS['config_data']));

		@fclose($fp);

		// Now ftp it across.
		@ftp_chdir($conn_id, $ftp_dir);

		$res = ftp_put($conn_id, 'config.'.$phpEx, $tmpfname, FTP_ASCII);

		@ftp_quit($conn_id);

		unlink($tmpfname);

		if ($upgrade == 1)	
		{
			require('upgrade.'.$phpEx);
			exit;
		}

		// Ok we are basically done with the install process let's go on 
		// and let the user configure their board now. We are going to do 
		// this by calling the admin_board.php from the normal board admin
		// section.
		$s_hidden_fields = '<input type="hidden" name="username" value="' . $admin_name . '" />';
		$s_hidden_fields .= '<input type="hidden" name="password" value="' . $admin_pass1 . '" />';
		$s_hidden_fields .= '<input type="hidden" name="redirect" value="../admin/index.'.$phpEx.'" />';
		$s_hidden_fields .= '<input type="hidden" name="submit" value="' . $lang['Login'] . '" />';

		page_header($lang['Inst_Step_2']);
		page_common_form($s_hidden_fields, $lang['Finish_Install']);
		page_footer();
		exit();
	}
}
else if ((empty($install_step) || $admin_pass1 != $admin_pass2 || empty($admin_pass1) || empty($dbhost)))
{
	// Ok we haven't installed before so lets work our way through the various
	// steps of the install process.  This could turn out to be quite a lengty 
	// process.

	// Step 0 gather the pertinant info for database setup...
	// Namely dbms, dbhost, dbname, dbuser, and dbpasswd.
	$instruction_text = $lang['Inst_Step_0'];

	if (!empty($install_step))
	{
		if ((($HTTP_POST_VARS['admin_pass1'] != $HTTP_POST_VARS['admin_pass2'])) ||
			(empty($HTTP_POST_VARS['admin_pass1']) || empty($dbhost)) && $HTTP_POST_VARS['cur_lang'] == $language)
		{
			$error = $lang['Password_mismatch'];
		}
	}

	$dirname = $phpbb_root_path . 'language';
	$dir = opendir($dirname);

	$lang_options = array();
	while ($file = readdir($dir))
	{
		if (preg_match('#^lang_#i', $file) && !is_file(@phpbb_realpath($dirname . '/' . $file)) && !is_link(@phpbb_realpath($dirname . '/' . $file)))
		{
			$filename = trim(str_replace('lang_', '', $file));
			$displayname = preg_replace('/^(.*?)_(.*)$/', '\1 [ \2 ]', $filename);
			$displayname = preg_replace('/\[(.*?)_(.*)\]/', '[ \1 - \2 ]', $displayname);
			$lang_options[$displayname] = $filename;
		}
	}

	closedir($dir);

	@asort($lang_options);
	@reset($lang_options);

	$lang_select = '<select name="lang" onchange="this.form.submit()">';
	while (list($displayname, $filename) = @each($lang_options))
	{
		$selected = ($language == $filename) ? ' selected="selected"' : '';
		$lang_select .= '<option value="' . $filename . '"' . $selected . '>' . ucwords($displayname) . '</option>';
	}
	$lang_select .= '</select>';

	$s_hidden_fields = '<input type="hidden" name="install_step" value="1" />';
	$s_hidden_fields .= '<input type="hidden" name="cur_lang" value="' . $language . '" />';

	page_header($instruction_text);

	// Check for gd version
	if (function_exists('gd_info')) 
	{
		$info = gd_info();
		$keys = array_keys($info);
	
		for ( $i=0; $i < 1; $i++ )
		{
			$gd_pass = TRUE;
			$gd_version = $info[$keys[$i]];
		}
	} 
	else 
	{
		$gd_pass = FALSE;
		$gd_version = 'n/a'; 
	}

	// Check for server version
	define('SAPI_NAME', php_sapi_name());
	if (preg_match('#(Apache)/([0-9\.]+)\s#siU', $_SERVER['SERVER_SOFTWARE'], $wsregs))
	{
		$server = "$wsregs[1] v$wsregs[2]";
		if (SAPI_NAME == 'cgi' OR SAPI_NAME == 'cgi-fcgi')
		{
			$addsapi = true;
		}
	}
	else if (preg_match('#Microsoft-IIS/([0-9\.]+)#siU', $SERVER['SERVER_SOFTWARE'], $wsregs))
	{
		$server = "IIS v$wsregs[1]";
		$addsapi = true;
	}
	else if (preg_match('#Zeus/([0-9\.]+)#siU', $SERVER['SERVER_SOFTWARE'], $wsregs))
	{
		$server = "Zeus v$wsregs[1]";
		$addsapi = true;
	}
	else if (strtoupper($_SERVER['SERVER_SOFTWARE']) == 'APACHE')	
	{
		$server = 'Apache';
		if (SAPI_NAME == 'cgi' OR SAPI_NAME == 'cgi-fcgi')
		{
			$addsapi = true;
		}
	}
	else
	{
		$server = SAPI_NAME;
	}	
	
	if ($addsapi)
	{
		$server .= ' (' . SAPI_NAME . ')';
	}

?>
	<tr>
		 <th class="thHead" colspan="4"><?php echo $lang['Web_server'] . ' ' . $lang['Information']; ?></th>
	 </tr>
	<tr>
		<td class="row1" width="38%"><b><?php echo $lang['register_globals']; ?>:</b></td>
		<td class="row2"><?php echo ((@ini_get('register_globals') == 1 || strtolower(@ini_get('register_globals')) == 'on') ? '<span class="error">' . 'ON' . '</span>' : 'OFF'); ?></td>
		<td class="row2" width="16" align="center"><img src="../templates/subSilver/images/ajax_<?php echo ((@ini_get('register_globals') == 1 || strtolower(@ini_get('register_globals')) == 'on') ? 'no' : 'yes'); ?>.png" alt="<?php echo ((@ini_get('register_globals') == 1 || strtolower(@ini_get('register_globals')) == 'on') ? 'FAILED' : 'PASSED'); ?>" title="<?php echo ((@ini_get('register_globals') == 1 || strtolower(@ini_get('register_globals')) == 'on') ? 'FAILED' : 'PASSED'); ?>" /></td>
		<td class="row2" align="center"><b class="<?php echo ((@ini_get('register_globals') == 1 || strtolower(@ini_get('register_globals')) == 'on') ? 'error">FAILED' : 'ok">PASSED'); ?></span></td>
	</tr>
	<tr>
		<td class="row1"><b><?php echo $lang['safe_mode']; ?>:</b></td>
		<td class="row2"><?php echo ((@ini_get('safe_mode') == 1 || strtolower(@ini_get('safe_mode')) == 'on') ? '<span class="error">' . 'ON' . '</span>' : 'OFF'); ?></td>
		<td class="row2" align="center"><img src="../templates/subSilver/images/ajax_<?php echo ((@ini_get('safe_mode') == 1 || strtolower(@ini_get('safe_mode')) == 'on') ? 'no' : 'yes'); ?>.png" alt="<?php echo ((@ini_get('safe_mode') == 1 || strtolower(@ini_get('safe_mode')) == 'on') ? 'FAILED' : 'PASSED'); ?>" title="<?php echo ((@ini_get('safe_mode') == 1 || strtolower(@ini_get('safe_mode')) == 'on') ? 'FAILED' : 'PASSED'); ?>" /></td>
		<td class="row2" align="center"><b class="<?php echo (($info_register_globals_alt == 'FAILED') ? 'error">FAILED' : 'ok">PASSED'); ?></span></td>
	</tr>
	<tr>
		<td class="row1"><b><?php echo $lang['Web_server']; ?>:</b></td>
		<td class="row2"><?php echo $server . ' (' . PHP_OS; ?>)</td>
		<td class="row2" align="center"><img src="../templates/subSilver/images/ajax_yes.png" alt="PASSED" title="PASSED" /></td>
		<td class="row2" align="center"><b class="ok">PASSED</span></td>
	</tr>
	</tr>
		<td class="row1"><b><?php echo $lang['MySQL']; ?>:</b></td>
		<td class="row2" width="200"><?php echo mysql_get_client_info(); ?></td>
		<td class="row2" align="center"><img src="../templates/subSilver/images/ajax_yes.png" alt="PASSED" title="PASSED" /></td>
		<td class="row2" align="center"><b class="ok">PASSED</span></td>
	</tr>
	<tr>
		<td class="row1"><b><?php echo sprintf($lang['php_version'], '', ''); ?>:</b></td>
		<td class="row2"><?php echo @phpversion(); ?></td>
		<td class="row2" align="center"><img src="../templates/subSilver/images/ajax_<?php echo ((version_compare($required_php_version, $required_php_version) < 0) ? 'no' : 'yes'); ?>.png" alt="<?php echo ((version_compare($required_php_version, @phpversion()) < 0) ? 'FAILED' : 'PASSED'); ?>" title="<?php echo ((version_compare($required_php_version, @phpversion()) < 0) ? 'FAILED' : 'PASSED'); ?>" /></td>
		<td class="row2" align="center"><b class="ok">PASSED</span></td>
	</tr>
	<tr>
		<td class="row1"><b><?php echo $lang['gd_version']; ?>:</b></td>
		<td class="row2"><?php echo $gd_version; ?></td>
		<td class="row2" align="center"><img src="../templates/subSilver/images/ajax_<?php echo (($gd_pass) ? 'yes' : 'no'); ?>.png" alt="<?php echo (($gd_pass) ? 'PASSED' : 'FAILED'); ?>" title="<?php echo (($gd_pass) ? 'PASSED' : 'FAILED'); ?>" /></td>
		<td class="row2" align="center"><b class="<?php echo (($gd_pass) ? 'ok">PASSED' : 'error">FAILED'); ?></span></td>
	</tr>
	<tr>
		<td class="row1"><b><?php echo $lang['memory_limit']; ?>:</b></td>
		<td class="row2" colspan="3"><?php echo ( (@ini_get('memory_limit') && (@ini_get('memory_limit') != -1) ) ? ini_get('memory_limit') : $lang['None']); ?></td>
	</tr>
<?php
// Stop script if PHP version does not meet min. requirement
//if (version_compare('4.4.0', $required_php_version) < 0)
if (version_compare(@phpversion(), $required_php_version) < 0)
{
	echo '<tr>
			<th class="thHead" colspan="4">' . $lang['Critical_Error'] . '</th>
		</tr>
		<tr>
			<td colspan="4" class="row1" height="30" align="center">Sorry, the PHP version on this server does not meet the minimum requirements for Fully Modded phpBB.<br /><br />Please contact your host to update it.<br /><br />Minimum required PHP version: <b>' . $required_php_version . '</b></span><td>
		</tr>';
	page_footer();
	exit; 
}
?>
					<tr>
						<th class="thHead" colspan="4"><?php echo $lang['DB_config']; ?></th>
					</tr>
					<tr>
						<td class="row1"><b><?php echo $lang['DB_Host']; ?>:</b></td>
						<td colspan="3" class="row2"><input type="text" size="25" name="dbhost" value="<?php echo ($dbhost != '') ? $dbhost : ''; ?>" /></td>
					</tr>
					<tr>
						<td class="row1"><b><?php echo $lang['DB_Name']; ?>:</b></td>
						<td colspan="3" class="row2"><input type="text" size="25" name="dbname" value="<?php echo ($dbname != '') ? $dbname : ''; ?>" /></td>
					</tr>
					<tr>
						<td class="row1"><b><?php echo $lang['DB_Username']; ?>:</b></td>
						<td colspan="3" class="row2"><input type="text" size="25" name="dbuser" value="<?php echo ($dbuser != '') ? $dbuser : ''; ?>" /></td>
					</tr>
					<tr>
						<td class="row1"><b><?php echo $lang['DB_Password']; ?>:</b></td>
						<td colspan="3" class="row2"><input type="password" size="25" name="dbpasswd" value="<?php echo ($dbpasswd != '') ? $dbpasswd : ''; ?>" /></td>
					</tr>
					<tr>
						<td class="row1"><b><?php echo $lang['Table_Prefix']; ?>:</b></td>
						<td colspan="3" class="row2"><input type="text" size="15" name="prefix" value="<?php echo (!empty($table_prefix)) ? $table_prefix : "phpbb_"; ?>" /></td>
					</tr>
					<tr>
						<th class="thHead" colspan="4"><?php echo $lang['Admin_config']; ?></th>
					</tr>
<?php

	if ($error)
	{
?>
					<tr>
						<td class="row2" colspan="4" align="center"><b style="color: #FF0000">* <?php echo $error; ?> *</b></td>
					</tr>
<?php

	}
?>
					<tr>
						<td class="row1"><b><?php echo $lang['Server_name']; ?>:</b></td>
						<td colspan="3" class="row2"><input type="text" size="25" name="server_name" value="<?php echo $server_name; ?>" /></td>
					</tr> 
					<tr>
						<td class="row1"><b><?php echo $lang['Server_port']; ?>:</b></td>
						<td colspan="3" class="row2"><input type="text" size="5" name="server_port" value="<?php echo $server_port; ?>" /></td>
					</tr>
					<tr>
						<td class="row1"><b><?php echo $lang['Script_path']; ?>:</b></td>
						<td colspan="3" class="row2"><input type="text" size="25" name="script_path" value="<?php echo $script_path; ?>" /></td>
					</tr>
					<tr>
						<td class="row1"><b><?php echo $lang['Admin_email']; ?>:</b></td>
						<td colspan="3" class="row2"><input type="text" size="25" name="board_email" value="<?php echo ($board_email != '') ? $board_email : ''; ?>" /></td>
					</tr> 
					<tr>
						<td class="row1"><b><?php echo $lang['Admin_Username']; ?>:</b></td>
						<td colspan="3" class="row2"><input type="text" size="25" name="admin_name" value="<?php echo ($admin_name != '') ? $admin_name : ''; ?>" /></td>
					</tr>
					<tr>
						<td class="row1"><b><?php echo $lang['Admin_Password']; ?>:</b></td>
						<td colspan="3" class="row2"><input type="password" size="25" name="admin_pass1" value="<?php echo ($admin_pass1 != '') ? $admin_pass1 : ''; ?>" /></td>
					</tr>
					<tr>
						<td class="row1"><b><?php echo $lang['Admin_Password'] . ': [' . $lang['Confirm']; ?>]</b></td>
						<td colspan="3" class="row2"><input type="password" size="25" name="admin_pass2" value="<?php echo ($admin_pass2 != '') ? $admin_pass2 : ''; ?>" /></td>
					</tr>
					<tr>
						<td class="row1"><b><?php echo $lang['Default_language']; ?>:</b></td>
						<td colspan="3" class="row2"><?php echo $lang_select; ?></td>
					</tr>
<?php

	page_common_form($s_hidden_fields, $lang['Start_Install']);
	page_footer();
	exit;
}
else
{
	// Go ahead and create the DB, then populate it
	//
	// MS Access is slightly different in that a pre-built, pre-
	// populated DB is supplied, all we need do here is update
	// the relevant entries
	if (isset($dbms))
	{
		switch($dbms)
		{
			case 'msaccess':
			case 'mssql-odbc':
				$check_exts = 'odbc';
				$check_other = 'odbc';
				break;

			case 'mssql':
				$check_exts = 'mssql';
				$check_other = 'sybase';
				break;

			case 'mysql':
			case 'mysql4':
				$check_exts = 'mysql';
				$check_other = 'mysql';
				break;

			case 'postgres':
				$check_exts = 'pgsql';
				$check_other = 'pgsql';
				break;
		}

		if (!extension_loaded($check_exts) && !extension_loaded($check_other))
		{	
			page_header('<h1>' . $lang['Install'] . '</h1>', '');
			page_error($lang['Installer_Error'], $lang['Install_No_Ext']);
			page_footer();
			exit;
		}

		include($phpbb_root_path.'includes/db.'.$phpEx);
	}

	$dbms_schema = $available_dbms[$dbms]['SCHEMA'] . '_schema.sql';
	$dbms_basic = $available_dbms[$dbms]['SCHEMA'] . '_basic.sql';

	$remove_remarks = $available_dbms[$dbms]['COMMENTS'];;
	$delimiter = $available_dbms[$dbms]['DELIM']; 
	$delimiter_basic = $available_dbms[$dbms]['DELIM_BASIC']; 

	if ($install_step == 1)
	{
		if ($upgrade != 1)
		{
			if ($dbms != 'msaccess')
			{
				// Load in the sql parser
				include($phpbb_root_path.'includes/sql_parse.'.$phpEx);

				// Ok we have the db info go ahead and read in the relevant schema
				// and work on building the table.. probably ought to provide some
				// kind of feedback to the user as we are working here in order
				// to let them know we are actually doing something.
				$sql_query = @fread(@fopen($dbms_schema, 'r'), @filesize($dbms_schema));
				$sql_query = preg_replace('/phpbb_/', $table_prefix, $sql_query);

				$sql_query = $remove_remarks($sql_query);
				$sql_query = split_sql_file($sql_query, $delimiter);

				for ($i = 0; $i < sizeof($sql_query); $i++)
				{
					if (trim($sql_query[$i]) != '')
					{
						if (!($result = $db->sql_query($sql_query[$i])))
						{
							$error = $db->sql_error();
			
							page_header('<h1>' . $lang['Install'] . '</h1>', '');
							page_error($lang['Installer_Error'], $lang['Install_db_error'] . '<br />' . $error['message']);
							page_footer();
							exit;
						}
					}
				}
		
				// Ok tables have been built, let's fill in the basic information
				$sql_query = @fread(@fopen($dbms_basic, 'r'), @filesize($dbms_basic));
				$sql_query = preg_replace('/phpbb_/', $table_prefix, $sql_query);

				$sql_query = $remove_remarks($sql_query);
				$sql_query = split_sql_file($sql_query, $delimiter_basic);

				for($i = 0; $i < sizeof($sql_query); $i++)
				{
					if (trim($sql_query[$i]) != '')
					{
						if (!($result = $db->sql_query($sql_query[$i])))
						{
							$error = $db->sql_error();

							page_header('<h1>' . $lang['Install'] . '</h1>', '');
							page_error($lang['Installer_Error'], $lang['Install_db_error'] . '<br />' . $error['message']);
							page_footer();
							exit;
						}
					}
				}
			}

			// Ok at this point they have entered their admin password, let's go 
			// ahead and create the admin account with some basic default information
			// that they can customize later, and write out the config file.  After
			// this we are going to pass them over to the admin_forum.php script
			// to set up their forum defaults.
			$error = '';

			// Update the default admin user with their information.
			$insert_config = array(
				'board_startdate'			=> time(),
				'stat_install_date'  		=> time(),
				'ftr_installed' 			=> time(),
				'ina_daily_game_date'		=> date('Y-m-d'),
				'current_ina_date'			=> date('Y-m-d'),
				'meta_date_creation_day' 	=> date('d'),
				'meta_date_creation_month' 	=> date('m'),
				'meta_date_creation_year' 	=> date('Y'),
				'default_lang'				=> str_replace("\'", "''", $language),
			);

			while (list($config_name, $config_value) = each($insert_config))
			{
				$sql = "INSERT INTO " . $table_prefix . "config (config_name, config_value) 
					VALUES ('" . $config_name . "', '" . $config_value . "')";
				if (!$db->sql_query($sql))
				{
					$error .= "Could not insert config values :: " . $sql . " :: " . __LINE__ . " :: " . __FILE__ . "<br /><br />";
				}
			}

			$update_config = array(
				'board_email'			=> $board_email,
				'script_path'			=> $script_path,
				'server_port'			=> $server_port,
				'server_name'			=> $server_name,
				'toplist_button_1_l'	=> 'http://' . $server_name,
				'toplist_button_2_l'	=> 'http://' . $server_name,
				'paypal_p_acct'			=> $board_email,
				'paypal_b_acct'			=> $board_email,
				'meta_reply_to'			=> $board_email,
				'meta_author'			=> $board_email,
				'meta_owner'			=> $board_email,
			);

			while (list($config_name, $config_value) = each($update_config))
			{
				$sql = "UPDATE " . $table_prefix . "config 
					SET config_value = '$config_value' 
					WHERE config_name = '$config_name'";
				if (!$db->sql_query($sql))
				{
					$error .= "Could not update config values :: " . $sql . " :: " . __LINE__ . " :: " . __FILE__ . "<br /><br />";
				}
			}

			$admin_pass_md5 = ($confirm && $userdata['user_level'] == ADMIN) ? $admin_pass1 : md5($admin_pass1);

			$sql = "UPDATE " . $table_prefix . "users 
				SET username = '" . str_replace("\'", "''", $admin_name) . "', user_password='" . str_replace("\'", "''", $admin_pass_md5) . "', user_lang = '" . str_replace("\'", "''", $language) . "', user_email='" . str_replace("\'", "''", $board_email) . "'
				WHERE username = 'Admin'";
			if (!$db->sql_query($sql))
			{
				$error .= "Could not update admin info :: " . $sql . " :: " . __LINE__ . " :: " . __FILE__ . "<br /><br />";
			}

			$sql = "UPDATE " . $table_prefix . "users 
				SET user_regdate = " . time();
			if (!$db->sql_query($sql))
			{
				$error .= "Could not update user_regdate :: " . $sql . " :: " . __LINE__ . " :: " . __FILE__ . "<br /><br />";
			}

			$sql = "UPDATE " . $table_prefix . "link_config 
				SET config_value = 	'http://" . $server_name . "'
				WHERE config_name = 'site_url'";
			if (!$db->sql_query($sql))
			{
				$error .= "Could not update link config values :: " . $sql . " :: " . __LINE__ . " :: " . __FILE__ . "<br /><br />";
			}

			$sql = "UPDATE " . $table_prefix . "links
				SET link_time = " . time();
			if (!$db->sql_query($sql))
			{
				$error .= "Could not update demo links :: " . $sql . " :: " . __LINE__ . " :: " . __FILE__ . "<br /><br />";
			}

			$sql = "UPDATE " . $table_prefix . "kb_articles
				SET article_date = " . time();
			if (!$db->sql_query($sql))
			{
				$error .= "Could not update demo kb article :: " . $sql . " :: " . __LINE__ . " :: " . __FILE__ . "<br /><br />";
			}

			$sql = "UPDATE " . $table_prefix . "posts
				SET post_time = " . time();
			if (!$db->sql_query($sql))
			{
				$error .= "Could not update demo post :: " . $sql . " :: " . __LINE__ . " :: " . __FILE__ . "<br /><br />";
			}

			$sql = "UPDATE " . $table_prefix . "topics
				SET topic_time = " . time();
			if (!$db->sql_query($sql))
			{
				$error .= "Could not update demo topic :: " . $sql . " :: " . __LINE__ . " :: " . __FILE__ . "<br /><br />";
			}

			$sql = "UPDATE " . $table_prefix . "referral
				SET referral_time = " . time();
			if (!$db->sql_query($sql))
			{
				$error .= "Could not update referral time :: " . $sql . " :: " . __LINE__ . " :: " . __FILE__ . "<br /><br />";
			}

			$sql = "UPDATE " . $table_prefix . "helpdesk_emails
				SET e_addr = '" . $board_email . "'";
			if (!$db->sql_query($sql))
			{
				$error .= "Could not update helpdesk default email address :: " . $sql . " :: " . __LINE__ . " :: " . __FILE__ . "<br /><br />";
			}

			$sql = "UPDATE " . $table_prefix . "backup
				SET email = '" . $board_email . "'";
			if (!$db->sql_query($sql))
			{
				$error .= "Could not update backup default email address :: " . $sql . " :: " . __LINE__ . " :: " . __FILE__ . "<br /><br />";
			}
			
			$sql = "UPDATE " . $table_prefix . "backup
				SET last_run = " . time();
			if (!$db->sql_query($sql))
			{
				$error .= "Could not update backup last run time:: " . $sql . " :: " . __LINE__ . " :: " . __FILE__ . "<br /><br />";
			}


/*			//
			// Check file and directory permissions...
			// Attempt to set permissions and create any missing directories
			//
			echo "<h1>Checking file permissions</h1>\n";
			echo "<p>Progress ::";

			// 0666
			$chmod_files = array(
				'cache/config_page_perms.'.$phpEx,
				'cache/reflog.txt',
				'cache/semaphore.ref',
				'language/lang_english/lang_bbcode.'.$phpEx,
				'language/lang_english/lang_faq.'.$phpEx,
				'language/lang_english/lang_faq_moderator.'.$phpEx
			);
	
			foreach ($chmod_files AS $dir)
			{
				if (!is_writeable($phpbb_root_path . $dir))
				{
					@chmod($phpbb_root_path . $dir, 0666);
				}
				echo '<li>' . $dir . ' &nbsp; <b class="' . (is_writeable($phpbb_root_path . $dir) ? 'ok">Writeable' : 'error">NOT WRITEABLE') . '</b></li>';
			}
				
			echo '</p>';
		
			echo "<h1>Checking directory permissions</h1>\n";
			echo "<p>Progress ::";
		
			// 0777
			$chmod_dirs = array(
				'admin/backup/', 
				'cache/', 
				'cache/backup/',
				'cache/tpls/',
				'images/smiles/',
				'modules/',
				'modules/pakfiles/',
				'uploads/',
				'uploads/album/',
				'uploads/album/thumbs/',
				'uploads/attachments/',
				'uploads/attachments/thumbs/',
				'uploads/backups/',
				'uploads/pafiledb/',
				'uploads/pafiledb/screenshots/',
				'uploads/user_avatars/',
				'uploads/user_avatars/tmp/',
				'uploads/user_photos/'		
			);
			
			foreach ($chmod_dirs AS $dir)
			{
				$exists = $write = false;
		
				// Try to create the directory if it does not exist
				if (!file_exists($phpbb_root_path . $dir))
				{
					@mkdir($phpbb_root_path . $dir, 0777);
					@chmod($phpbb_root_path . $dir, 0777);
				}
		
				// Now really check
				if (file_exists($phpbb_root_path . $dir) && is_dir($phpbb_root_path . $dir))
				{
					if (!is_writeable($phpbb_root_path . $dir))
					{
						@chmod($phpbb_root_path . $dir, 0777);
					}
					$exists = true;
				}
		
				// Now check if it is writeable by storing a simple file
				$fp = @fopen($phpbb_root_path . $dir . 'test_lock', 'wb');
				if ($fp !== false)
				{
					$write = true;
				}
				@fclose($fp);
		
				@unlink($phpbb_root_path . $dir . 'test_lock');	
		
				$exists = ($exists) ? '' : '<b class="error">NOT FOUND</b>, ';
				$write = ($write) ? '<b class="ok">Writeable</b>' : '<b class="error">NOT WRITEABLE</b>';
		
				echo '<li>' . $dir . ' - 0777 &nbsp; ' . $exists . $write . '</li>';
			}
		
			echo '</p>';
*/
			if ($error != '')
			{
				page_header('<h1>' . $lang['Install'] . '</h1>', '');
				page_error($lang['Installer_Error'], $lang['Install_db_error'] . '<br /><br />' . $error);
				page_footer();
				exit;
			}
		}

		if (!$upgrade_now)
		{
			// Write out the config file.
			$config_data = '<?php'."\n\n";
			$config_data .= "// phpBB 2.x auto-generated config file\n// Do not change anything in this file!\n\n";
			$config_data .= '$dbms = \'' . $dbms . '\';' . "\n\n";
			$config_data .= '$dbhost = \'' . $dbhost . '\';' . "\n";
			$config_data .= '$dbname = \'' . $dbname . '\';' . "\n";
			$config_data .= '$dbuser = \'' . $dbuser . '\';' . "\n";
			$config_data .= '$dbpasswd = \'' . $dbpasswd . '\';' . "\n\n";
			$config_data .= '$table_prefix = \'' . $table_prefix . '\';' . "\n\n";
			$config_data .= 'define(\'PHPBB_INSTALLED\', true);'."\n\n";	
			$config_data .= '?' . '>'; // Done this to prevent highlighting editors getting confused!

			@umask(0111);
			$no_open = FALSE;

			// Unable to open the file writeable do something here as an attempt
			// to get around that...
			if (!($fp = @fopen($phpbb_root_path . 'config.'.$phpEx, 'w')))
			{
				$s_hidden_fields = '<input type="hidden" name="config_data" value="' . htmlspecialchars($config_data) . '" />';

				if (@extension_loaded('ftp') && !defined('NO_FTP'))
				{
					page_header($lang['Unwriteable_config'] . '<p>' . $lang['ftp_option'] . '</p>');

?>
					<tr>
						<th class="thHead" colspan="2"><?php echo $lang['ftp_choose']; ?></th>
					</tr>
					<tr>
						<td class="row1" width="50%"><b><?php echo $lang['Attempt_ftp']; ?>:</b></td>
						<td class="row2"><input type="radio" name="send_file" value="2"></td>
					</tr>
					<tr>
						<td class="row1"><b><?php echo $lang['Send_file']; ?>:</b></td>
						<td class="row2"><input type="radio" name="send_file" value="1"></td>
					</tr>
<?php 

				}
				else
				{
					page_header($lang['Unwriteable_config']);
					$s_hidden_fields .= '<input type="hidden" name="send_file" value="1" />';
				}

				if ($upgrade == 1)
				{
					$s_hidden_fields .= '<input type="hidden" name="upgrade" value="1" />';
					$s_hidden_fields .= '<input type="hidden" name="dbms" value="'.$dbms.'" />';
					$s_hidden_fields .= '<input type="hidden" name="prefix" value="'.$table_prefix.'" />';
					$s_hidden_fields .= '<input type="hidden" name="dbhost" value="'.$dbhost.'" />';
					$s_hidden_fields .= '<input type="hidden" name="dbname" value="'.$dbname.'" />';
					$s_hidden_fields .= '<input type="hidden" name="dbuser" value="'.$dbuser.'" />';
					$s_hidden_fields .= '<input type="hidden" name="dbpasswd" value="'.$dbpasswd.'" />';
					$s_hidden_fields .= '<input type="hidden" name="install_step" value="1" />';
					$s_hidden_fields .= '<input type="hidden" name="admin_pass1" value="1" />';
					$s_hidden_fields .= '<input type="hidden" name="admin_pass2" value="1" />';
					$s_hidden_fields .= '<input type="hidden" name="server_port" value="'.$server_port.'" />';
					$s_hidden_fields .= '<input type="hidden" name="server_name" value="'.$server_name.'" />';
					$s_hidden_fields .= '<input type="hidden" name="script_path" value="'.$script_path.'" />';
					$s_hidden_fields .= '<input type="hidden" name="board_email" value="'.$board_email.'" />';

					page_upgrade_form();

				}
				else
				{
					page_common_form($s_hidden_fields, $lang['Download_config']);
				}

				page_footer();
				exit;
			}

			$result = @fputs($fp, $config_data, strlen($config_data));

			@fclose($fp);
			$upgrade_now = $lang['upgrade_submit'];
		}

		// First off let's check and see if we are supposed to be doing an upgrade.
		if ($upgrade == 1 && $upgrade_now == $lang['upgrade_submit'])
		{
			define('INSTALLING', true);
			require('upgrade.'.$phpEx);
			exit;
		}

		// Ok we are basically done with the install process let's go on 
		// and let the user configure their board now. We are going to do
		// this by calling the admin_board.php from the normal board admin
		// section.
		$s_hidden_fields = '<input type="hidden" name="username" value="' . $admin_name . '" />';
		$s_hidden_fields .= '<input type="hidden" name="password" value="' . $admin_pass1 . '" />';
		$s_hidden_fields .= '<input type="hidden" name="redirect" value="admin/index.'.$phpEx.'" />';
		$s_hidden_fields .= '<input type="hidden" name="login" value="true" />';

		page_header('', '../login.'.$phpEx);
		page_error($lang['Finish_Install'], $lang['Inst_Step_2']);
		page_common_form($s_hidden_fields, $lang['Finish_Install']);
		page_footer();
		exit;
	}
}

?>