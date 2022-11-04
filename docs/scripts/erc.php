<?php
/***************************************************************************
 *                                  erc.php
 *                            -------------------
 *   begin                : Fri Feb 07, 2003
 *   copyright            : (C) 2004 Philipp Kordowich
 *                          Parts: (C) 2002 The phpBB Group
 *
 *   part of DB Maintenance Mod 1.3.1
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

define('IN_PHPBB', 1);
$phpbb_root_path = '../';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'config.'.$phpEx);
include($phpbb_root_path . 'includes/constants.'.$phpEx);
include($phpbb_root_path . 'includes/functions.'.$phpEx);
include($phpbb_root_path . 'includes/functions_dbmtnc.'.$phpEx);
include($phpbb_root_path . 'includes/db.'.$phpEx);

//
// addslashes to vars if magic_quotes_gpc is off
// this is a security precaution to prevent someone
// trying to break out of a SQL statement.
//
if( !get_magic_quotes_gpc() )
{
	if( is_array($HTTP_GET_VARS) )
	{
		while( list($k, $v) = each($HTTP_GET_VARS) )
		{
			if( is_array($HTTP_GET_VARS[$k]) )
			{
				while( list($k2, $v2) = each($HTTP_GET_VARS[$k]) )
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

	if( is_array($HTTP_POST_VARS) )
	{
		while( list($k, $v) = each($HTTP_POST_VARS) )
		{
			if( is_array($HTTP_POST_VARS[$k]) )
			{
				while( list($k2, $v2) = each($HTTP_POST_VARS[$k]) )
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

	if( is_array($HTTP_COOKIE_VARS) )
	{
		while( list($k, $v) = each($HTTP_COOKIE_VARS) )
		{
			if( is_array($HTTP_COOKIE_VARS[$k]) )
			{
				while( list($k2, $v2) = each($HTTP_COOKIE_VARS[$k]) )
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

$mode = ( isset($HTTP_POST_VARS['mode']) ) ? htmlspecialchars($HTTP_POST_VARS['mode']) : (( isset($HTTP_GET_VARS['mode']) ) ? htmlspecialchars($HTTP_GET_VARS['mode']) : 'start');
$option = ( isset($HTTP_POST_VARS['option']) ) ? htmlspecialchars($HTTP_POST_VARS['option']) : '';

// Before doing anything else send config.php if requested
if ( $mode == 'download' )
{
	// Get and convert Variables
	$new_dbms = ( isset($HTTP_GET_VARS['ndbms']) ) ? $HTTP_GET_VARS['ndbms'] : '';
	$new_dbhost = ( isset($HTTP_GET_VARS['ndbh']) ) ? $HTTP_GET_VARS['ndbh'] : '';
	$new_dbname = ( isset($HTTP_GET_VARS['ndbn']) ) ? $HTTP_GET_VARS['ndbn'] : '';
	$new_dbuser = ( isset($HTTP_GET_VARS['ndbu']) ) ? $HTTP_GET_VARS['ndbu'] : '';
	$new_dbpasswd = ( isset($HTTP_GET_VARS['ndbp']) ) ? $HTTP_GET_VARS['ndbp'] : '';
	$new_table_prefix = ( isset($HTTP_GET_VARS['ntp']) ) ? $HTTP_GET_VARS['ntp'] : '';

	$var_array = array('new_dbms', 'new_dbhost', 'new_dbname', 'new_dbuser', 'new_dbpasswd', 'new_table_prefix');
	reset($var_array);
	while (list(, $var) = each ($var_array))
	{
		$$var = stripslashes($$var);
		$$var = str_replace("'", "\\'", str_replace("\\", "\\\\", $$var));
	}

	// Create the config.php
	$data = "<?php\n" . 
		"\n" .
		"//\n" .
		"// phpBB 2.x auto-generated config file\n" .
		"// Do not change anything in this file!\n" .
		"//\n" .
		"\n" .
		"\$dbms = '$new_dbms';\n" .
		"\n" .
		"\$dbhost = '$new_dbhost';\n" .
		"\$dbname = '$new_dbname';\n" .
		"\$dbuname = '$new_dbuser';\n" .
		"\$dbpasswd = '$new_dbpasswd';\n" .
		"\n" .
		"\$table_prefix = '$new_table_prefix';\n" .
		"\n" .
		"define('PHPBB_INSTALLED', true);\n" .
		"\n" .
		"?>";
	header('Content-type: text/plain');
	header("Content-Disposition: attachment; filename=config.$phpEx");
	echo $data;
	exit;
}

// Load a language if one was selected
if ( isset($HTTP_POST_VARS['lg']) || isset($HTTP_GET_VARS['lg']) )
{
	$lg = ( isset($HTTP_POST_VARS['lg']) ) ? htmlspecialchars($HTTP_POST_VARS['lg']) : htmlspecialchars($HTTP_GET_VARS['lg']);
	if ( file_exists(@phpbb_realpath($phpbb_root_path . 'language/lang_' . $lg . '/lang_dbmtnc.'.$phpEx)) )
	{
		include($phpbb_root_path . 'language/lang_' . $lg . '/lang_dbmtnc.' . $phpEx);
		include($phpbb_root_path . 'language/lang_' . $lg . '/lang_main.' . $phpEx);
	}
	else
	{
		$lg = '';
	}
}
else
{
	$lg = '';
}

// If no language was selected, check for available languages
if ($lg == '')
{
	$dirname = 'language';
	$dir = opendir($phpbb_root_path . $dirname);
	$lang_list = Array();
	while ( $file = readdir($dir) )
	{
		if (preg_match('#^lang_#i', $file) && !is_file(@phpbb_realpath($phpbb_root_path . $dirname . '/' . $file)) && !is_link(@phpbb_realpath($phpbb_root_path . $dirname . '/' . $file)) && is_file(@phpbb_realpath($phpbb_root_path . $dirname . '/' . $file . '/lang_dbmtnc.' . $phpEx)))
		{
			$filename = trim(str_replace("lang_", "", $file));
			$lang_list[] = $filename;
		}
	}
	closedir($dir);
	if (count($lang_list) == 1)
	{
		$lg = $lang_list[0];
		include($phpbb_root_path . 'language/lang_' . $lg . '/lang_dbmtnc.' . $phpEx);
		include($phpbb_root_path . 'language/lang_' . $lg . '/lang_main.' . $phpEx);
	}
	else // Try to load english language
	{
		if ( file_exists(@phpbb_realpath($phpbb_root_path . 'language/lang_english/lang_dbmtnc.'.$phpEx)) )
		{
			include($phpbb_root_path . 'language/lang_english/lang_dbmtnc.' . $phpEx);
			include($phpbb_root_path . 'language/lang_english/lang_main.' . $phpEx);
			$mode = 'select_lang';
		}
		else
		{
			$lang['Forum_Home'] = 'Forum Home';
			$lang['ERC'] = 'Emergency Recovery Console';
			$lang['Submit_text'] = 'Send';
			$lang['Select_Language'] = 'Select a language';
			$lang['no_selectable_language'] = 'No selectable language exist';
			$mode = 'select_lang';
		}
	}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;">
<meta http-equiv="Content-Style-Type" content="text/css">
<title><?php echo $lang['ERC']; ?></title>
<style type="text/css">
<!--
font,th,td,p,body { font-family: "Trebuchet MS",Verdana, Arial, Helvetica, sans-serif; font-size: 10pt }
th,td { vertical-align: top }
a:link,a:active,a:visited { color : #006699; }
a:hover		{ text-decoration: underline; color : #DD6900;}
hr	{ height: 0px; border: solid #D1D7DC 0px; border-top-width: 1px;}
.maintitle,h1,h2	{font-weight: bold; font-size: 22px; font-family: "Trebuchet MS",Verdana, Arial, Helvetica, sans-serif; text-decoration: none; line-height : 120%; color : #000000;}
-->
</style>
</head>
<body bgcolor="#FFFFFF" text="#000000" link="#006699" vlink="#5584AA">

<table width="100%" cellspacing="0" cellpadding="10" align="center">
	<tr>
		<td><table width="100%" cellspacing="0" cellpadding="0">
			<tr>
				<td><img src="../docs/images/logo_phpBB.gif" alt="<?php echo $lang['Forum_Home']; ?>" vspace="1" /></td>
				<td align="center" width="100%" valign="middle"><span class="maintitle"><?php echo $lang['ERC']; ?></span><br />
					<?php echo ($option == '') ? '' : $lang[$option] ?></td>
			</tr>
		</table></td>
	</tr>
</table>

<br />

<?php
switch($mode)
{
	case 'select_lang':
?>
<form action="<?php echo basename(__FILE__); ?>" method="post">
<table  cellspacing="0" cellpadding="10">
	<tr>
		<td><table width="100%" cellspacing="0" cellpadding="0">
			<tr>
				<td><b><?php echo $lang['Select_Language']; ?>:</b></td>
				<td width="10">&nbsp;</td>
				<td><?php echo language_select('english', 'lg', 'dbmtnc'); ?>&nbsp;<input type="submit" value="<?php echo $lang['Submit_text']; ?>" /></td>
			</tr>
		</table></td>
	</tr>
</table>
</form>
<?php
		break;
	case 'start':
?>
<form action="<?php echo basename(__FILE__); ?>" method="post">
<table cellspacing="0" cellpadding="10">
	<tr>
		<td><table width="100%" cellspacing="0" cellpadding="0">
			<tr>
				<td nowrap="nowrap"><b><?php echo $lang['Select_Option']; ?>:</b></td>
				<td width="10">&nbsp;</td>
				<td><input type="hidden" name="lg" value="<?php echo $lg ?>" />
					<input type="hidden" name="mode" value="datainput" />
					<select size="1" name="option">
					<option value="cls"><?php echo $lang['cls']; ?></option>
<?php
	if ( check_mysql_version() )
	{
?>
					<option value="rdb"><?php echo $lang['rdb']; ?></option>
<?php
	}
?>
					<option value="cct"><?php echo $lang['cct']; ?></option>
					<option value="rpd"><?php echo $lang['rpd']; ?></option>
					<option value="rcd"><?php echo $lang['rcd']; ?></option>
					<option value="rld"><?php echo $lang['rld']; ?></option>
					<option value="rtd"><?php echo $lang['rtd']; ?></option>
					<option value="dgc"><?php echo $lang['dgc']; ?></option>
					<option value="cbl"><?php echo $lang['cbl']; ?></option>
					<option value="raa"><?php echo $lang['raa']; ?></option>
					<option value="mua"><?php echo $lang['mua']; ?></option>
					<option value="rcp"><?php echo $lang['rcp']; ?></option>
				</select>&nbsp;<input type="submit" value="<?php echo $lang['Submit_text']; ?>" /></td>
			</tr>
			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>
			<tr>
				<td nowrap="nowrap"><b><?php echo $lang['Option_Help']; ?>:</td>
				<td>&nbsp;</td>
				<td><?php echo $lang['Option_Help_Text']; ?></td>
			</tr>
		</table></td>
	</tr>
</table>
</form>
<?php
		break;
	case 'datainput':
		if ( $option != 'rcp' )
		{
?>
<form action="<?php echo basename(__FILE__); ?>" method="post">
<table cellspacing="0" cellpadding="10">
<?php
			if ( $option != 'rld' && $option != 'rtd' )
			{
?>
	<tr>
		<td><b><?php echo $lang['Authenticate_methods']; ?>:</b></td>
	</tr>
	<tr>
		<td><?php echo $lang['Authenticate_methods_help_text']; ?></td>
	</tr>
<?php
			}
			else
			{
?>
	<tr>
		<td><b><?php echo $lang['Authenticate_user_only']; ?>:</b></td>
	</tr>
	<tr>
		<td><?php echo $lang['Authenticate_user_only_help_text']; ?></td>
	</tr>
<?php
			}
?>
	<tr>
		<td>
			<table cellspacing="0" cellpadding="0">
				<tr>
					<td><b><?php echo $lang['Admin_Account']; ?></b></td>
<?php
			if ( $option != 'rld' && $option != 'rtd' )
			{
?>
					<td width="20">&nbsp;</td>
					<td><b><?php echo $lang['Database_Login']; ?></b></td>
<?php
			}
?>
				</tr>
				<tr>
					<td>
						<table cellspacing="0" cellpadding="2">
							<tr>
								<td><input type="radio" name="auth_method" value="board" checked="checked" /></td>
								<td><?php echo $lang['Username']; ?>:</td>
								<td><input type="text" name="board_user" size="30" maxlength="25" /></td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td><?php echo $lang['Password']; ?>:</td>
								<td><input type="password" name="board_password" size="30" maxlength="100" /></td>
							</tr>
						</table>
					</td>
<?php
			if ( $option != 'rld' && $option != 'rtd' )
			{
?>
					<td>&nbsp;</td>
					<td>
						<table cellspacing="0" cellpadding="2">
							<tr>
								<td><input type="radio" name="auth_method" value="db" /></td>
								<td><?php echo $lang['Username']; ?>:</td>
								<td><input type="text" name="db_user" size="30" /></td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td><?php echo $lang['Password']; ?>:</td>
								<td><input type="password" name="db_password" size="30" /></td>
							</tr>
						</table>
					</td>
<?php
			}
?>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
<?php
		}
		else
		{
?>
<form action="<?php echo basename(__FILE__); ?>" method="post">
<table cellspacing="0" cellpadding="10">
<?php
		}
		switch ($option)
		{
			case 'cls': // Clear Sessions
?>
	<tr>
		<td><?php echo $lang['cls_info']; ?></td>
	</tr>
<?php
				break;
			case 'rdb': // Repair Database
?>
	<tr>
		<td><?php echo $lang['rdb_info']; ?></td>
	</tr>
<?php
				break;
			case 'cct': // Check config table
?>
	<tr>
		<td><?php echo $lang['cct_info']; ?></td>
	</tr>
<?php
				break;
			case 'rpd': // Reset path data
				// Get path information
				$secure_cur = get_config_data('cookie_secure');
				if (!empty($HTTP_SERVER_VARS['SERVER_PROTOCOL']) || !empty($HTTP_ENV_VARS['SERVER_PROTOCOL']))
				{
					$protocol = (!empty($HTTP_SERVER_VARS['SERVER_PROTOCOL'])) ? $HTTP_SERVER_VARS['SERVER_PROTOCOL'] : $HTTP_ENV_VARS['SERVER_PROTOCOL'];
					$secure_rec = ( strtolower(substr($protocol, 0 , 5)) == 'https' ) ? '1' : '0';
				}
				else
				{
					$secure_rec = '0';
				}
				$domain_cur = get_config_data('server_name');
				if (!empty($HTTP_SERVER_VARS['SERVER_NAME']) || !empty($HTTP_ENV_VARS['SERVER_NAME']))
				{
					$domain_rec = (!empty($HTTP_SERVER_VARS['SERVER_NAME'])) ? $HTTP_SERVER_VARS['SERVER_NAME'] : $HTTP_ENV_VARS['SERVER_NAME'];
				}
				else if (!empty($HTTP_SERVER_VARS['HTTP_HOST']) || !empty($HTTP_ENV_VARS['HTTP_HOST']))
				{
					$domain_rec = (!empty($HTTP_SERVER_VARS['HTTP_HOST'])) ? $HTTP_SERVER_VARS['HTTP_HOST'] : $HTTP_ENV_VARS['HTTP_HOST'];
				}
				else
				{
					$domain_rec = '';
				}
				$port_cur = get_config_data('server_port');
				if (!empty($HTTP_SERVER_VARS['SERVER_PORT']) || !empty($HTTP_ENV_VARS['SERVER_PORT']))
				{
					$port_rec = (!empty($HTTP_SERVER_VARS['SERVER_PORT'])) ? $HTTP_SERVER_VARS['SERVER_PORT'] : $HTTP_ENV_VARS['SERVER_PORT'];
				}
				else
				{
					$port_rec = '80';
				}
				$path_cur = get_config_data('script_path');
				$path_rec = str_replace('admin', '', dirname($HTTP_SERVER_VARS['PHP_SELF']));
?>
	<tr>
		<td>
			<table cellspacing="2" cellpadding="0">
				<tr>
					<td>&nbsp;</td>
					<td colspan="2"><b><?php echo $lang['cur_setting']; ?></b></td>
					<td width="10">&nbsp;</td>
					<td colspan="2"><b><?php echo $lang['rec_setting']; ?></b></td>
				</tr>
				<tr>
					<td><b><?php echo $lang['secure']; ?></b></td>
					<td><input type="radio" name="secure_select" value="0"<?php echo ( $secure_cur == $secure_rec ) ? ' checked="checked"' : '' ?> /></td>
					<td><?php echo $lang[($secure_cur == '1') ? 'secure_yes' : 'secure_no' ]; ?></td>
					<td>&nbsp;</td>
					<td><input type="radio" name="secure_select" value="1"<?php echo ( $secure_cur != $secure_rec ) ? ' checked="checked"' : '' ?> /></td>
					<td><input type="radio" name="secure" value="1"<?php echo ( $secure_rec == '1' ) ? ' checked="checked"' : '' ?> /><?php echo $lang['secure_yes']; ?><input type="radio" name="secure" value="0"<?php echo ( $secure_rec == '0' ) ? ' checked="checked"' : '' ?> /><?php echo $lang['secure_no']; ?></td>
				</tr>
				<tr>
					<td><b><?php echo $lang['domain']; ?></b></td>
					<td><input type="radio" name="domain_select" value="0"<?php echo ( $domain_cur == $domain_rec ) ? ' checked="checked"' : '' ?> /></td>
					<td><?php echo htmlspecialchars($domain_cur); ?></td>
					<td>&nbsp;</td>
					<td><input type="radio" name="domain_select" value="1"<?php echo ( $domain_cur != $domain_rec ) ? ' checked="checked"' : '' ?> /></td>
					<td><input type="input" name="domain" value="<?php echo htmlspecialchars($domain_rec); ?>" maxlength="255" size="40" /></td>
				</tr>
				<tr>
					<td><b><?php echo $lang['port']; ?></b></td>
					<td><input type="radio" name="port_select" value="0"<?php echo ( $port_cur == $port_rec ) ? ' checked="checked"' : '' ?> /></td>
					<td><?php echo htmlspecialchars($port_cur); ?></td>
					<td>&nbsp;</td>
					<td><input type="radio" name="port_select" value="1"<?php echo ( $port_cur != $port_rec ) ? ' checked="checked"' : '' ?> /></td>
					<td><input type="input" name="port" value="<?php echo htmlspecialchars($port_rec); ?>" maxlength="5" size="5" /></td>
				</tr>
				<tr>
					<td><b><?php echo $lang['path']; ?></b></td>
					<td><input type="radio" name="path_select" value="0"<?php echo ( $path_cur == $path_rec ) ? ' checked="checked"' : '' ?> /></td>
					<td><?php echo htmlspecialchars($path_cur); ?></td>
					<td>&nbsp;</td>
					<td><input type="radio" name="path_select" value="1"<?php echo ( $path_cur != $path_rec ) ? ' checked="checked"' : '' ?> /></td>
					<td><input type="input" name="path" value="<?php echo htmlspecialchars($path_rec); ?>" maxlength="255" size="40" /></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td><?php echo $lang['rpd_info']; ?></td>
	</tr>
<?php
				break;
			case 'rcd': // Reset cookie data
				// Get cookie information
				$cookie_domain = get_config_data('cookie_domain');
				$cookie_name = get_config_data('cookie_name');
				$cookie_path = get_config_data('cookie_path');
?>
	<tr>
		<td>
			<table cellspacing="2" cellpadding="0">
				<tr>
					<td><b><?php echo $lang['Cookie_domain']; ?></b></td>
					<td><input type="input" name="cookie_domain" value="<?php echo htmlspecialchars($cookie_domain); ?>" maxlength="255" size="40" /></td>
				</tr>
				<tr>
					<td><b><?php echo $lang['Cookie_name']; ?></b></td>
					<td><input type="input" name="cookie_name" value="<?php echo htmlspecialchars($cookie_name); ?>" maxlength="16" size="40" /></td>
				</tr>
				<tr>
					<td><b><?php echo $lang['Cookie_path']; ?></b></td>
					<td><input type="input" name="cookie_path" value="<?php echo htmlspecialchars($cookie_path); ?>" maxlength="255" size="40" /></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td><?php echo $lang['rcd_info']; ?></td>
	</tr>
<?php
				break;
			case 'rld': // Reset language data
?>
	<tr>
		<td>
			<table cellspacing="2" cellpadding="0">
				<tr>
					<td><b><?php echo $lang['select_language']; ?>:</b></td>
					<td><?php echo language_select('english', 'new_lang'); ?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td><?php echo $lang['rld_info']; ?></td>
	</tr>
<?php
				break;
			case 'rtd': // Reset template data
				$sql = "SELECT count(*) AS themes_count
					FROM " . THEMES_TABLE;
				if ( !($result = $db->sql_query($sql)) )
				{
					erc_throw_error("Couldn't count records of themes table!", __LINE__, __FILE__, $sql);
				}
				if ( $row = $db->sql_fetchrow($result) )
				{
					$themes_count = $row['themes_count'];
				}
				else
				{
					$themes_count = 0;
				}
				$db->sql_freeresult($result);
?>
	<tr>
		<td>
			<table cellspacing="2" cellpadding="0">
<?php
				if ($themes_count != 0)
				{
?>
				<tr>
					<td><input type="radio" name="method" value="select_theme" checked="checked" /></td>
					<td><?php echo $lang['select_theme']; ?></td>
					<td><?php echo style_select('', 'new_style'); ?></td>
				</tr>
<?php
				}
?>
				<tr>
					<td><input type="radio" name="method" value="recreate_theme"<?php echo ( $themes_count == 0 ) ? ' checked="checked"' : '' ?> /></td>
					<td colspan="2"><?php echo $lang['reset_thmeme']; ?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td><?php echo ($themes_count != 0) ? $lang['rtd_info'] : $lang['rtd_info_no_theme'] ; ?></td>
	</tr>
<?php
				break;
			case 'dgc': // disable GZip compression 
?>
	<tr>
		<td><?php echo $lang['dgc_info']; ?></td>
	</tr>
<?php
				break;
			case 'cbl': // Clear ban list 
?>
	<tr>
		<td><?php echo $lang['cbl_info']; ?></td>
	</tr>
<?php
				break;
			case 'raa': // Remove all administrators
?>
	<tr>
		<td><?php echo $lang['raa_info']; ?></td>
	</tr>
<?php
				break;
			case 'mua': // Grant user admin privileges
?>
	<tr>
		<td>
			<table cellspacing="2" cellpadding="0">
				<tr>
					<td><b><?php echo $lang['new_admin_user']; ?>:</b></td>
					<td><input type="input" name="username" maxlength="30" size="25" /></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td><?php echo $lang['mua_info']; ?></td>
	</tr>
<?php
				break;
			case 'rcp': // Recreate config.php
				$available_dbms = array(
					'mysql'=> array(
						'LABEL'			=> 'MySQL 3.x'
					), 
					'mysql4' => array(
						'LABEL'			=> 'MySQL 4.x'
					), 
					'postgres' => array(
						'LABEL'			=> 'PostgreSQL 7.x'
					), 
					'mssql' => array(
						'LABEL'			=> 'MS SQL Server 7/2000'
					),
					'msaccess' => array(
						'LABEL'			=> 'MS Access [ ODBC ]'
					),
					'mssql-odbc' =>	array(
						'LABEL'			=> 'MS SQL Server [ ODBC ]'
					));
				$dbms_select = '<select name="new_dbms">';
				while (list($dbms_name, $details) = @each($available_dbms))
				{
					$dbms_select .= '<option value="' . $dbms_name . '">' . $details['LABEL'] . '</option>';
				}
				$dbms_select .= '</select>';

?>
	<tr>
		<td>
			<table cellspacing="2" cellpadding="0">
				<tr>
					<td><b><?php echo $lang['dbms']; ?>:</b></td>
					<td><?php echo $dbms_select; ?></td>
				</tr>
				<tr>
					<td><b><?php echo $lang['DB_Host']; ?>:</b></td>
					<td><input type="input" name="new_dbhost" size="30" /></td>
				</tr>
				<tr>
					<td><b><?php echo $lang['DB_Name']; ?>:</b></td>
					<td><input type="input" name="new_dbname" size="30" /></td>
				</tr>
				<tr>
					<td><b><?php echo $lang['DB_Username']; ?>:</b></td>
					<td><input type="input" name="new_dbuser" size="30" /></td>
				</tr>
				<tr>
					<td><b><?php echo $lang['DB_Password']; ?>:</b></td>
					<td><input type="password" name="new_dbpasswd" size="30" /></td>
				</tr>
				<tr>
					<td><b><?php echo $lang['Table_Prefix']; ?>:</b></td>
					<td><input type="input" name="new_table_prefix" size="30" /></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td><?php echo $lang['rcp_info']; ?></td>
	</tr>
<?php
				break;
			default:
?>
</table>
</form>
<p><b>Invalid Option</b></p>
</body>
</html>
<?php
				die();
		}
?>
	<tr>
		<td>
			<input type="hidden" name="option" value="<?php echo $option; ?>" />
			<input type="hidden" name="mode" value="execute" />
			<input type="hidden" name="lg" value="<?php echo $lg ?>" />
			<input type="submit" value="<?php echo $lang['Submit_text']; ?>" />
			- <a href="<?php echo $HTTP_SERVER_VARS['PHP_SELF'] . '?lg=' . $lg; ?>"><?php echo $lang['Cancel']; ?></a>
		</td>
	</tr>
</table>
</form>
<?php
		break;
	case 'execute':
		switch ($option)
		{
			case 'cls': // Clear Sessions
				check_authorisation();
				$sql = "DELETE FROM " . SESSIONS_TABLE;
				$result = $db->sql_query($sql);
				if( !$result )
				{
					erc_throw_error("Couldn't delete session table!", __LINE__, __FILE__, $sql);
				}
				$sql = "DELETE FROM " . SEARCH_TABLE;
				$result = $db->sql_query($sql);
				if( !$result )
				{
					erc_throw_error("Couldn't delete search result table!", __LINE__, __FILE__, $sql);
				}
				success_message($lang['cls_success']);
				break;
			case 'rdb': // Clear Sessions
				check_authorisation();
				if ( !check_mysql_version() )
				{
?>
	<p><span style="color:red"><?php echo $lang['Old_MySQL_Version'] ?></span></p>
<?php
				}
				else
				{
?>
	<p><?php echo $lang['Repairing_tables'] ?>:</p>
	<ul>
<?php
					for($i = 0; $i < count($tables); $i++)
					{
						$tablename = $table_prefix . $tables[$i];
						$sql = "REPAIR TABLE $tablename";
						$result = $db->sql_query($sql);
						if ( !$result )
						{
							throw_error("Couldn't repair table!", __LINE__, __FILE__, $sql);
						}
						if ( $row = $db->sql_fetchrow($result) )
						{
							if ($row['Msg_type'] == 'status')
							{
?>
		<li><?php echo "$tablename: " . $lang['Table_OK']?></li>
<?php
							}
							else //  We got an error
							{
								// Check whether the error results from HEAP-table type
								$sql2 = "SHOW TABLE STATUS LIKE '$tablename'";
								$result2 = $db->sql_query($sql2);
								$row2 = $db->sql_fetchrow($result2);
								if ( $row2['Type'] == 'HEAP' )
								{
									// Table is from HEAP-table type
?>
		<li><?php echo "$tablename: " . $lang['Table_HEAP_info']?></li>
<?php
								}
								else
								{
?>
		<li><?php echo "<b>$tablename:</b> " . htmlspecialchars($row['Msg_text'])?></li>
<?php
								}
								$db->sql_freeresult($result2);
							}
						}
						$db->sql_freeresult($result);
					}
?>
	</ul>
<?php
					success_message($lang['rdb_success']);
				}
				break;
			case 'cct': // Check config table
				check_authorisation();

				// Update config data to match current configuration
				if (!empty($HTTP_SERVER_VARS['SERVER_PROTOCOL']) || !empty($HTTP_ENV_VARS['SERVER_PROTOCOL']))
				{
					$protocol = (!empty($HTTP_SERVER_VARS['SERVER_PROTOCOL'])) ? $HTTP_SERVER_VARS['SERVER_PROTOCOL'] : $HTTP_ENV_VARS['SERVER_PROTOCOL'];
					if ( strtolower(substr($protocol, 0 , 5)) == 'https' )
					{
						$default_config['cookie_secure'] = '1';
					}
				}
				if (!empty($HTTP_SERVER_VARS['SERVER_NAME']) || !empty($HTTP_ENV_VARS['SERVER_NAME']))
				{
					$default_config['server_name'] = (!empty($HTTP_SERVER_VARS['SERVER_NAME'])) ? $HTTP_SERVER_VARS['SERVER_NAME'] : $HTTP_ENV_VARS['SERVER_NAME'];
				}
				else if (!empty($HTTP_SERVER_VARS['HTTP_HOST']) || !empty($HTTP_ENV_VARS['HTTP_HOST']))
				{
					$default_config['server_name'] = (!empty($HTTP_SERVER_VARS['HTTP_HOST'])) ? $HTTP_SERVER_VARS['HTTP_HOST'] : $HTTP_ENV_VARS['HTTP_HOST'];
				}
				if (!empty($HTTP_SERVER_VARS['SERVER_PORT']) || !empty($HTTP_ENV_VARS['SERVER_PORT']))
				{
					$default_config['server_port'] = (!empty($HTTP_SERVER_VARS['SERVER_PORT'])) ? $HTTP_SERVER_VARS['SERVER_PORT'] : $HTTP_ENV_VARS['SERVER_PORT'];
				}
				$default_config['script_path'] = str_replace('admin', '', dirname($HTTP_SERVER_VARS['PHP_SELF']));
				$sql = "SELECT Min(topic_time) as startdate FROM " . TOPICS_TABLE;
				if ( $result = $db->sql_query($sql) )
				{
					if ( ($row = $db->sql_fetchrow($result)) && $row['startdate'] > 0 )
					{
						$default_config['board_startdate'] = $row['startdate'];
					}
				}

				// Start the job				
?>
	<p><?php echo $lang['Restoring_config'] . ':'; ?></p>
	<ul>
<?php
				reset($default_config);
				while (list($key, $value) = each($default_config))
				{
					$sql = 'SELECT config_value FROM ' . CONFIG_TABLE . "
						WHERE config_name = '$key'";
					$result = $db->sql_query($sql);
					if ( !$result )
					{
						erc_throw_error("Couldn't query config table!", __LINE__, __FILE__, $sql);
					}
					if ( !($row = $db->sql_fetchrow($result)) )
					{
						echo("<li><b>$key:</b> $value</li>\n");
						$sql = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value)
							VALUES ('$key', '$value')";
						$result = $db->sql_query($sql);
						if ( !$result )
						{
							erc_throw_error("Couldn't update config table!", __LINE__, __FILE__, $sql);
						}
					}
				}
?>
	</ul>
<?php
				success_message($lang['cct_success']);
				break;
			case 'rpd': // Reset path data
				check_authorisation();
				// Get variables
				$secure_select = ( isset($HTTP_POST_VARS['secure_select']) ) ? intval($HTTP_POST_VARS['secure_select']) : 1;
				$domain_select = ( isset($HTTP_POST_VARS['domain_select']) ) ? intval($HTTP_POST_VARS['domain_select']) : 1;
				$port_select = ( isset($HTTP_POST_VARS['port_select']) ) ? intval($HTTP_POST_VARS['port_select']) : 1;
				$path_select = ( isset($HTTP_POST_VARS['path_select']) ) ? intval($HTTP_POST_VARS['path_select']) : 1;
				$secure = ( isset($HTTP_POST_VARS['secure']) ) ? intval($HTTP_POST_VARS['secure']) : 0;
				$domain = ( isset($HTTP_POST_VARS['domain']) ) ? str_replace("\\'", "''", $HTTP_POST_VARS['domain']) : '';
				$port = ( isset($HTTP_POST_VARS['port']) ) ? str_replace("\\'", "''", $HTTP_POST_VARS['port']) : '';
				$path = ( isset($HTTP_POST_VARS['path']) ) ? str_replace("\\'", "''", $HTTP_POST_VARS['path']) : '';
				
				if ($secure_select == 1)
				{
					$sql = "UPDATE " . CONFIG_TABLE . "
						SET config_value = '$secure'
						WHERE config_name = 'cookie_secure'";
					$result = $db->sql_query($sql);
					if( !$result )
					{
						erc_throw_error("Couldn't update config table!", __LINE__, __FILE__, $sql);
					}
				}
				if ($domain_select == 1)
				{
					$sql = "UPDATE " . CONFIG_TABLE . "
						SET config_value = '$domain'
						WHERE config_name = 'server_name'";
					$result = $db->sql_query($sql);
					if( !$result )
					{
						erc_throw_error("Couldn't update config table!", __LINE__, __FILE__, $sql);
					}
				}
				if ($port_select == 1)
				{
					$sql = "UPDATE " . CONFIG_TABLE . "
						SET config_value = '$port'
						WHERE config_name = 'server_port'";
					$result = $db->sql_query($sql);
					if( !$result )
					{
						erc_throw_error("Couldn't update config table!", __LINE__, __FILE__, $sql);
					}
				}
				if ($path_select == 1)
				{
					$sql = "UPDATE " . CONFIG_TABLE . "
						SET config_value = '$path'
						WHERE config_name = 'script_path'";
					$result = $db->sql_query($sql);
					if( !$result )
					{
						erc_throw_error("Couldn't update config table!", __LINE__, __FILE__, $sql);
					}
				}
				success_message($lang['rpd_success']);
				break;
			case 'rcd': // Reset cookie data
				check_authorisation();
				// Get variables
				$cookie_domain = ( isset($HTTP_POST_VARS['cookie_domain']) ) ? str_replace("\\'", "''", $HTTP_POST_VARS['cookie_domain']) : '';
				$cookie_name = ( isset($HTTP_POST_VARS['cookie_name']) ) ? str_replace("\\'", "''", $HTTP_POST_VARS['cookie_name']) : '';
				$cookie_path = ( isset($HTTP_POST_VARS['cookie_path']) ) ? str_replace("\\'", "''", $HTTP_POST_VARS['cookie_path']) : '';

				$sql = "UPDATE " . CONFIG_TABLE . "
					SET config_value = '$cookie_domain'
					WHERE config_name = 'cookie_domain'";
				$result = $db->sql_query($sql);
				if( !$result )
				{
					erc_throw_error("Couldn't update config table!", __LINE__, __FILE__, $sql);
				}
				$sql = "UPDATE " . CONFIG_TABLE . "
					SET config_value = '$cookie_name'
					WHERE config_name = 'cookie_name'";
				$result = $db->sql_query($sql);
				if( !$result )
				{
					erc_throw_error("Couldn't update config table!", __LINE__, __FILE__, $sql);
				}
				$sql = "UPDATE " . CONFIG_TABLE . "
					SET config_value = '$cookie_path'
					WHERE config_name = 'cookie_path'";
				$result = $db->sql_query($sql);
				if( !$result )
				{
					erc_throw_error("Couldn't update config table!", __LINE__, __FILE__, $sql);
				}
				success_message($lang['rcd_success']);
				break;
			case 'rld': // Reset language data
				check_authorisation();
				$new_lang = ( isset($HTTP_POST_VARS['new_lang']) ) ? str_replace("\\'", "''", $HTTP_POST_VARS['new_lang']) : '';
				$board_user = isset($HTTP_POST_VARS['board_user']) ? trim(htmlspecialchars($HTTP_POST_VARS['board_user'])) : '';
				$board_user = substr(str_replace("\\'", "'", $board_user), 0, 25);
				$board_user = str_replace("'", "\\'", $board_user);

				if ( is_file(@phpbb_realpath($phpbb_root_path . 'language/lang_' . $new_lang . '/lang_main.' . $phpEx)) && is_file(@phpbb_realpath($phpbb_root_path . 'language/lang_' . $new_lang . '/lang_admin.' . $phpEx)) )
				{
					$sql = "UPDATE " . USERS_TABLE . "
						SET user_lang = '$new_lang'
						WHERE username = '$board_user'";
					$result = $db->sql_query($sql);
					if( !$result )
					{
						erc_throw_error("Couldn't update user table!", __LINE__, __FILE__, $sql);
					}
					$sql = "UPDATE " . CONFIG_TABLE . "
						SET config_value = '$new_lang'
						WHERE config_name = 'default_lang'";
					$result = $db->sql_query($sql);
					if( !$result )
					{
						erc_throw_error("Couldn't update config table!", __LINE__, __FILE__, $sql);
					}
					success_message($lang['rld_success']);
				}
				else
				{
					success_message($lang['rld_failed']);
				}
				break;
			case 'rtd': // Reset template data
				check_authorisation();
				$method = ( isset($HTTP_POST_VARS['method']) ) ? htmlspecialchars($HTTP_POST_VARS['method']) : '';
				$new_style = ( isset($HTTP_POST_VARS['new_style']) ) ? intval($HTTP_POST_VARS['new_style']) : 0;
				$board_user = isset($HTTP_POST_VARS['board_user']) ? trim(htmlspecialchars($HTTP_POST_VARS['board_user'])) : '';
				$board_user = substr(str_replace("\\'", "'", $board_user), 0, 25);
				$board_user = str_replace("'", "\\'", $board_user);

				if ($method == 'recreate_theme')
				{
					$sql = "INSERT INTO " . THEMES_TABLE . "
						(template_name, style_name, head_stylesheet, body_background, body_bgcolor, body_text, body_link, body_vlink, body_alink, body_hlink, tr_color1, tr_color2, tr_color3, tr_class1, tr_class2, tr_class3, th_color1, th_color2, th_color3, th_class1, th_class2, th_class3, td_color1, td_color2, td_color3, td_class1, td_class2, td_class3, fontface1, fontface2, fontface3, fontsize1, fontsize2, fontsize3, fontcolor1, fontcolor2, fontcolor3, span_class1, span_class2, span_class3, img_size_poll, img_size_privmsg) VALUES
						('subSilver', 'subSilver', 'subSilver.css', '', 'E5E5E5', '000000', '006699', '5493B4', '', 'DD6900', 'EFEFEF', 'DEE3E7', 'D1D7DC', '', '', '', '98AAB1', '006699', 'FFFFFF', 'cellpic1.gif', 'cellpic3.gif', 'cellpic2.jpg', 'FAFAFA', 'FFFFFF', '', 'row1', 'row2', '', 'Verdana, Arial, Helvetica, sans-serif', 'Trebuchet MS', 'Courier, \\'Courier New\\', sans-serif', 10, 11, 12, '444444', '006600', 'FFA34F', '', '', '', NULL, NULL)";
					$result = $db->sql_query($sql);
					if( !$result )
					{
						erc_throw_error("Couldn't update themes table!", __LINE__, __FILE__, $sql);
					}
					$method = 'select_theme';
					$new_style = $db->sql_nextid();
?>
	<p><?php echo $lang['rtd_restore_success'];?></p>
<?php
				}
				if ($method == 'select_theme')
				{
					$sql = "UPDATE " . USERS_TABLE . "
						SET user_style = $new_style
						WHERE username = '$board_user'";
					$result = $db->sql_query($sql);
					if( !$result )
					{
						erc_throw_error("Couldn't update user table!", __LINE__, __FILE__, $sql);
					}
					$sql = "UPDATE " . CONFIG_TABLE . "
						SET config_value = '$new_style'
						WHERE config_name = 'default_style'";
					$result = $db->sql_query($sql);
					if( !$result )
					{
						erc_throw_error("Couldn't update config table!", __LINE__, __FILE__, $sql);
					}
					success_message($lang['rtd_success']);
				}
				break;
			case 'dgc': // Disable GZip compression 
				check_authorisation();
					$sql = "UPDATE " . CONFIG_TABLE . "
						SET config_value = '0'
						WHERE config_name = 'gzip_compress'";
					$result = $db->sql_query($sql);
					if( !$result )
					{
						erc_throw_error("Couldn't update config table!", __LINE__, __FILE__, $sql);
					}
				success_message($lang['dgc_success']);
				break;
			case 'cbl': // Clear ban list 
				check_authorisation();
				$sql = "DELETE FROM " . BANLIST_TABLE;
				$result = $db->sql_query($sql);
				if( !$result )
				{
					erc_throw_error("Couldn't delete ban list table!", __LINE__, __FILE__, $sql);
				}
				$sql = "DELETE FROM " . DISALLOW_TABLE;
				$result = $db->sql_query($sql);
				if( !$result )
				{
					erc_throw_error("Couldn't delete disallowed users table!", __LINE__, __FILE__, $sql);
				}
				$sql = "SELECT user_id FROM " . USERS_TABLE . "
					WHERE user_id = " . ANONYMOUS;
				$result = $db->sql_query($sql);
				if ( !$result )
				{
					erc_throw_error("Couldn't get user information!", __LINE__, __FILE__, $sql);
				}
				if ( $row = $db->sql_fetchrow($result) ) // anonymous user exists
				{
					success_message($lang['cbl_success']);
				}
				else // anonymous user does not exist
				{
					// Recreate entry
					$sql = "INSERT INTO " . USERS_TABLE . " (user_id, username, user_level, user_regdate, user_password, user_email, user_icq, user_website, user_occ, user_from, user_interests, user_sig, user_viewemail, user_style, user_aim, user_yim, user_msnm, user_posts, user_attachsig, user_allowsmile, user_allowhtml, user_allowbbcode, user_allow_pm, user_notify_pm, user_allow_viewonline, user_rank, user_avatar, user_lang, user_timezone, user_dateformat, user_actkey, user_newpasswd, user_notify, user_active)
						VALUES (" . ANONYMOUS . ", 'Anonymous', 0, 0, '', '', '', '', '', '', '', '', 0, NULL, '', '', '', 0, 0, 1, 0, 1, 0, 1, 1, NULL, '', '', 0, '', '', '', 0, 0)";
					$result = $db->sql_query($sql);
					if ( !$result )
					{
						throw_error("Couldn't add user data!", __LINE__, __FILE__, $sql);
					}
					success_message($lang['cbl_success_anonymous']);
				}
				break;
			case 'raa': // Remove all administrators
				check_authorisation();
				// Get userdata to check for current user
				$auth_method = ( isset($HTTP_POST_VARS['auth_method']) ) ? htmlspecialchars($HTTP_POST_VARS['auth_method']) : '';
				$board_user = isset($HTTP_POST_VARS['board_user']) ? trim(htmlspecialchars($HTTP_POST_VARS['board_user'])) : '';
				$board_user = substr(str_replace("\\'", "'", $board_user), 0, 25);

				$sql = "SELECT user_id, username
					FROM " . USERS_TABLE . "
					WHERE user_level = " . ADMIN;
				$result = $db->sql_query($sql);
				if ( !$result )
				{
					erc_throw_error("Couldn't get user data!", __LINE__, __FILE__, $sql);
				}
?>
	<p><?php echo $lang['Removing_admins'] . ':'; ?></p>
	<ul>
<?php
				while ( $row = $db->sql_fetchrow($result) )
				{
					if ( $auth_method != 'board' || $board_user != $row['username'] )
					{
						// Checking whether user is a moderator
						if( check_mysql_version() )
						{
							$sql2 = "SELECT ug.user_id
								FROM " . USER_GROUP_TABLE . " ug
									INNER JOIN " . AUTH_ACCESS_TABLE . " aa ON ug.group_id = aa.group_id
								WHERE ug.user_id = " . $row['user_id'] . " AND ug.user_pending <> 1 AND aa.auth_mod = 1";
						}
						else
						{
							$sql2 = "SELECT ug.user_id
								FROM " . USER_GROUP_TABLE . " ug, " .
									AUTH_ACCESS_TABLE . " aa
								WHERE ug.group_id = aa.group_id
									AND ug.user_id = " . $row['user_id'] . "
									AND ug.user_pending <> 1 AND aa.auth_mod = 1";
						}
						$result2 = $db->sql_query($sql2);
						if ( !$result2 )
						{
							erc_throw_error("Couldn't get moderator data!", __LINE__, __FILE__, $sql2);
						}
						$new_state = intval(( $row2 = $db->sql_fetchrow($result2) ) ? MOD : USER);
						$db->sql_freeresult($result2);
						$sql2 = "UPDATE " . USERS_TABLE . "
							SET user_level = $new_state
							WHERE user_id = " . $row['user_id'];
						$result2 = $db->sql_query($sql2);
						if ( !$result2 )
						{
							erc_throw_error("Couldn't update user data!", __LINE__, __FILE__, $sql2);
						}
?>
	<li><?php echo htmlspecialchars($row['username']) ?></li>
<?php
					}
				}
				$db->sql_freeresult($result);
?>
	</ul>
<?php
				success_message($lang['raa_success']);
				break;
			case 'mua': // Grant user admin privileges
				check_authorisation();
				$username = ( isset($HTTP_POST_VARS['username']) ) ? str_replace("\\'", "''", $HTTP_POST_VARS['username']) : '';

				$sql = "UPDATE " . USERS_TABLE . "
					SET user_active = 1, user_level = " . ADMIN . "
					WHERE username = '$username' AND user_id <> -1";
				$result = $db->sql_query($sql);
				if( !$result )
				{
					erc_throw_error("Couldn't update user table!", __LINE__, __FILE__, $sql);
				}
				$affected_rows = $db->sql_affectedrows();
				if ($affected_rows == 0)
				{
					success_message($lang['mua_failed']);
				}
				else
				{
					success_message($lang['mua_success']);
				}
				break;
			case 'rcp': // Recreate config.php
				// Get Variables
				$var_array = array('new_dbms', 'new_dbhost', 'new_dbname', 'new_dbuser', 'new_dbpasswd', 'new_table_prefix');
				reset($var_array);
				while (list(, $var) = each ($var_array))
				{
					$$var = ( isset($HTTP_POST_VARS[$var]) ) ? stripslashes($HTTP_POST_VARS[$var]) : '';
				}

?>
	<p><b><?php echo $lang['New_config_php']; ?>:</b></p>
	<table cellspacing="0" cellpadding="0">
		<tr>
			<td width="20">&nbsp;</td>
			<td>
				&lt;?php<br />
				<br />
				//<br />
				// phpBB 2.x auto-generated config file<br />
				// Do not change anything in this file!<br />
				//<br />
				<br />
				$dbms = '<?php echo htmlspecialchars(str_replace("'", "\\'", str_replace("\\", "\\\\", $new_dbms))); ?>';<br />
				<br />
				$dbhost = '<?php echo htmlspecialchars(str_replace("'", "\\'", str_replace("\\", "\\\\", $new_dbhost))); ?>';<br />
				$dbname = '<?php echo htmlspecialchars(str_replace("'", "\\'", str_replace("\\", "\\\\", $new_dbname))); ?>';<br />
				$dbuname = '<?php echo htmlspecialchars(str_replace("'", "\\'", str_replace("\\", "\\\\", $new_dbuser))); ?>';<br />
				$dbpasswd = '<?php echo htmlspecialchars(str_replace("'", "\\'", str_replace("\\", "\\\\", $new_dbpasswd))); ?>';<br />
				<br />
				$table_prefix = '<?php echo htmlspecialchars(str_replace("'", "\\'", str_replace("\\", "\\\\", $new_table_prefix))); ?>';<br />
				<br />
				define('PHPBB_INSTALLED', true);<br />
				<br />
				?&gt;
			</td>
		</tr>
	</table>
<?php
				$ndbms = urlencode($new_dbms);
				$ndbh = urlencode($new_dbhost);
				$ndbn = urlencode($new_dbname);
				$ndbu = urlencode($new_dbuser);
				$ndbp = urlencode($new_dbpasswd);
				$ntp = urlencode($new_table_prefix);
				success_message(sprintf($lang['rcp_success'], "<a href=\"" . $HTTP_SERVER_VARS['PHP_SELF'] . "?mode=download&ndbms=$ndbms&ndbh=$ndbh&ndbn=$ndbn&ndbu=$ndbu&ndbp=$ndbp&ntp=$ntp\">", '</a>'));
				break;
			default:
?>
<p><b>Invalid Option</b></p>
</body>
</html>
<?php
				die();
		}
		break;
	default:
?>
<p><b>Invalid Option</b></p>
</body>
</html>
<?php
		die();
}
?>
<br />
</body>
</html>
