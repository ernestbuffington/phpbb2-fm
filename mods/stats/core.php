<?php
/** 
*
* @package stats
* @version $Id: core.php,v 4.2.8 2003/03/16 18:38:30 acydburn Exp $
* @copyright (c) 2003 Meik Sievertsen
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

/*
	Statistics Mod Core

	This is the heart of the Statistics Mod, here are all root classes defined.
*/

if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}

include($phpbb_root_path . 'mods/stats/content/bars.' . $phpEx);
include($phpbb_root_path . 'mods/stats/content/statistical.' . $phpEx);
include($phpbb_root_path . 'mods/stats/content/values.' . $phpEx);

// db cache
include($phpbb_root_path . 'mods/stats/db_cache.' . $phpEx);
include($phpbb_root_path . 'mods/stats/functions.' . $phpEx);

//
// The Core
//
class StatisticsCORE
{
	var $template_file = ''; // content template file
	var $return_limit = 10;
	var $global_array = array();
	var $use_db_cache = false;
	var $do_not_use_cache = false; // force to not use caches at all if set to true
	var $module_reloaded = false;
	var $module_variables = array();
	var $used_language = '';

	// Informations about the currently parsed module
	var $current_module_path = '';
	var $current_module_name = '';
	var $current_module_id = 0;
	var $module_info = array(); // Additional Module Informations gathered within other positions through the process

	// Data
	var $calculation_data = array();
	var $calc_index = 0;

	// Namespaces
	var $namespace_vars = array();
	var $namespace_functions = array();

	function StatisticsCore()
	{
	}
	
	// Init Module
	function start_module($db_cache_on = false)
	{
		global $stats_template, $theme, $stat_db;

		$this->use_db_cache = false;
		$this->module_info['next_update_time'] = 0;
		$this->module_info['last_update_time'] = 0;

		$stats_template = new Stats_template();
		$stats_template->set_template($theme['template_name']);

		$stat_db->begin_cached_query();

		if ((!$db_cache_on) || ($this->do_not_use_cache))
		{
			return;
		}

		// Now init our database cache. ;)
		$cache = '';
		$this->use_db_cache = true;

		if (module_use_db_cache($this->current_module_id, $cache))
		{
			$stat_db->begin_cached_query(true, $cache);
		}
	}

	// Run Module
	function run_module()
	{
		global $stats_template, $template, $stat_db, $board_config, $userdata;

		if ($this->use_db_cache)
		{
			$stat_db->end_cached_query($this->current_module_id);
		}

		$compiled_output = $stats_template->display('body');
		
		if ( ($this->module_info['last_update_time'] != 0) && ($this->module_info['next_update_time'] != 0) )
		{
			$last_update_time = create_date($board_config['default_dateformat'], $this->module_info['last_update_time'], $board_config['board_timezone']);
			$next_update_time = create_date($board_config['default_dateformat'], $this->module_info['next_update_time'], $board_config['board_timezone']);
		}
		else
		{
			$last_update_time = $next_update_time = '';
		}

		$langthings = get_lang_entries($this->current_module_name,'lang_'.$userdata['user_lang']);//$board_config['default_lang']

		$template->assign_block_vars('modules', array(
			'CURRENT_MODULE' => $compiled_output,
			'CACHED' => ($stat_db->use_cache) ? 'true' : 'false',
			'RELOADED' => (!$stat_db->use_cache && $this->use_db_cache) ? 'true' : 'false',
			'LAST_UPDATE_TIME' => $last_update_time,
			'NEXT_GUESSED_UPDATE_TIME' => $next_update_time,
			'MODULE_ID' => $this->current_module_id,
			'MODULE_SHORT_NAME' => $this->current_module_name,
			'MODULE_NAME' => $langthings[0]['value'])
		);

		if ( ($this->module_info['last_update_time'] != 0) && ($this->module_info['next_update_time'] != 0) )
		{
			$template->assign_block_vars('modules.switch_display_timestats', array());
		}

		$stats_template->destroy();
	}

	// Make data global to the content class
	function make_global($data)
	{
		$this->global_array = $data;
	}

	// Set and init the content class and define all namespaces
	function set_content($content_template)
	{
		global $stats_template;

		if ($content_template == 'bars')
		{
			global $content, $content_bars;

			if (empty($content_bars))
			{
				$content_bars = new Content_bars;
				$content = $content_bars;
			}
			else
			{
				$content = $content_bars;
			}

			$this->template_file = 'content_bars.tpl';
			$vars = get_class_vars(get_class($content));
			$this->namespace_vars = array();

			foreach ($vars as $name => $value ) 
			{
				$this->namespace_vars[] = $name;
			}
			
			$this->namespace_functions = get_class_methods(get_class($content));

			$stats_template->set_filenames(array(
				'body' => $this->template_file)
			);

			// Init some standard things based on the template
			$content->init_bars(array(
				'left' => '/vote_lcap.gif',
				'right' => '/vote_rcap.gif',
				'bar' => '/voting_bar.gif')
			);

		}
		else if ($content_template == 'statistical')
		{
			global $content, $content_statistical;

			if (empty($content_statistical))
			{
				$content_statistical = new Content_statistical;
				$content = $content_statistical;
			}
			else
			{
				$content = $content_statistical;
			}

			$this->template_file = 'content_statistical.tpl';
			$vars = get_class_vars(get_class($content));
			$this->namespace_vars = array();

			foreach ($vars as $name => $value ) 
			{
				$this->namespace_vars[] = $name;
			}
			
			$this->namespace_functions = get_class_methods(get_class($content));

			$stats_template->set_filenames(array(
				'body' => $this->template_file)
			);

			// Init some standard things based on the template
		}
		else if ($content_template == 'values')
		{
			global $content, $content_values;

			if (empty($content_values))
			{
				$content_values = new Content_values;
				$content = $content_values;
			}
			else
			{
				$content = $content_values;
			}

			$this->template_file = 'content_values.tpl';
			$vars = get_class_vars(get_class($content));
			$this->namespace_vars = array();

			foreach ($vars as $name => $value ) 
			{
				$this->namespace_vars[] = $name;
			}
			
			$this->namespace_functions = get_class_methods(get_class($content));

			$stats_template->set_filenames(array(
				'body' => $this->template_file)
			);

			// Init some standard things based on the template
		}
	}

	// Set current data elements
	function set_data($data, $limit = -1)
	{
		if ($limit != -1)
		{
			$this->calculation_data = array();

			for ($i = 0; $i < $limit; $i++)
			{
				$this->calculation_data[$i] = $data[$i];
			}
		}
		else
		{
			$this->calculation_data = $data;
		}

		$this->calc_index = 0;
	}

	// Return the current data element
	function data($key)
	{
		return ($this->calculation_data[$this->calc_index][$key]);
	}

	// Set and return a pre-defined variable
	// Those pre-defined variables are for special conditions and content related output
	function pre_defined($variable = '')
	{
		global $lang;

		if ($variable == 'rank')
		{
			return (array('__PRE_DEFINE_RANK__' => $lang['Rank']));
		}
		else if ($variable == 'percent')
		{
			return (array('__PRE_DEFINE_PERCENT__' => $lang['Percent']));
		}
		else if ($variable == 'graph')
		{
			return (array('__PRE_DEFINE_GRAPH__' => $lang['Graph']));
		}
		else
		{
			return ('__PRE_DEFINED_VALUE__');
		}
	}
	
	// Set the content view
	function set_view($var_name, $var_value)
	{
		global $content;

		if (!in_array($var_name, $this->namespace_vars))
		{
			$this->error_handler('Invalid Call (' . get_class($content) . '): set_view -> <b>' . $var_name . '</b>');
		}
		
		$content->$var_name = $var_value;
	}

	// Define content view
	function define_view($function_call, $data, $auth_data = 0)
	{
		global $content;

		if (!in_array($function_call, $this->namespace_functions))
		{
			$this->error_handler('Invalid Call(' . get_class($content) . '): define_view -> <b>' . $function_call . '</b>');
		}

		// bar content class: set_columns
		return ($content->$function_call($data, $auth_data));
	}

	// Assign specific things to current content view
	function assign_defined_view($function_call, $data)
	{
		global $content;

		if (!in_array($function_call, $this->namespace_functions))
		{
			$this->error_handler('Invalid Call(' . get_class($content) . '): assign_defined_view -> <b>' . $function_call . '</b>');
		}

		// bar content class: align_rows
		return ($content->$function_call($data));
	}

	// Set content Header
	function set_header($header_lang)
	{
		global $stats_template, $userdata;

		$langthings = get_lang_entries($this->current_module_name,'lang_'.$userdata['user_lang']);//$board_config['default_lang']

		$stats_template->assign_vars(array(
		'L_TOP' => $langthings[1000]['value'],
		'TOP_WIDTH' => $langthings[1001]['value'],
			'MODULE_NAME' => $header_lang)
		);
	}

	// Statistics Mod Error Handler
	function error_handler($msg, $debug_info = '')
	{
		// TODO
		// Here have to be something to stop the module and procceed with the next one.
		mess_die(GENERAL_ERROR, '<br />Module ' . $this->current_module_name . ' has a problem:<br /><br />' . $msg . '<br />' . $debug_info . '<br />');
	}

	// Get all defines
	function get_user_defines()
	{
		return ($this->module_variables[$this->current_module_id]);
	}
	
	//
	// STAT_FUNCTIONS
	//

	// $stat_functions->sort_data()
	function sort_data ($sort_array, $key, $sort_order, $pre_string_sort = -1) 
	{
		global $stat_functions;
		
		return ($stat_functions->sort_data($sort_array, $key, $sort_order, $pre_string_sort));
	}

	// $stat_functions->generate_link()
	function generate_link($url, $placeholder, $append = '')
	{
		global $stat_functions;

		return ($stat_functions->generate_link($url, $placeholder, $append));
	}

	// $stat_functions->generate_image_link()
	function generate_image_link($url, $alt, $append = '')
	{
		global $stat_functions;

		return ($stat_functions->generate_image_link($url, $alt, $append));
	}

	/* $stat_functions->forum_auth()
	function forum_auth($userdata, $auth = AUTH_VIEW)
	{
		global $stat_functions;

		return ($stat_functions->forum_auth($userdata, $auth));
	}*/

	//
	// STAT_DB
	//
	
	// $stat_db->sql_query()
	function sql_query($sql_statement, $error_message, $transaction = FALSE)
	{
		global $stat_db, $db;

		$result = $stat_db->sql_query($sql_statement, $transaction);
		
		if (!$result)
		{
			$error = $db->sql_error();
			$this->error_handler($error_message, $error['message'] . '<br />SQL Statement: ' . $sql_statement);
		}
	}

	// $stat_db->sql_fetchrow()
	function sql_fetchrow($database_id)
	{
		global $stat_db;

		return ($stat_db->sql_fetchrow($database_id));
	}

	// $stat_db->sql_fetchrowset()
	function sql_fetchrowset($database_id)
	{
		global $stat_db;

		return ($stat_db->sql_fetchrowset($database_id));
	}

	// $stat_db->sql_numrows()
	function sql_numrows($database_id)
	{
		global $stat_db;

		return ($stat_db->sql_numrows($database_id));
	}
	
}

$core = $stat_db = $stat_functions = $content = $content_bars = $content_statistical = $content_values = '';

function init_core()
{
	global $board_config, $core, $stat_db, $stat_functions, $db, $userdata;

	$core = new StatisticsCORE;
	
	if ($board_config['stat_return_limit'] != '')
	{
		$core->return_limit = intval($board_config['stat_return_limit']);
	}

	// Get Module Variables
	$sql = "SELECT module_id, config_name, config_value, config_type 
		FROM " . MODULE_ADMIN_TABLE;
	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not find Module Admin Table', '', __LINE__, __FILE__, $sql);
	}
	
	$rows = $db->sql_fetchrowset($result);
	$num_rows = $db->sql_numrows($result);

	for ($i = 0; $i < $num_rows; $i++)
	{
		$module_id = intval($rows[$i]['module_id']);

		switch (trim($rows[$i]['config_type']))
		{
			case 'number':
				$core->module_variables[$module_id][trim($rows[$i]['config_name'])] = intval($rows[$i]['config_value']);
				break;
		}
	}

	$stat_db = new StatisticsDB;
	$stat_functions = new StatisticsFUNCTIONS;

	$stat_functions->init_auth_settings($userdata);

}

	// Borrowed from - Last visit MOD
	function make_hour($base_time)
	{
		global $lang;
		$years = floor($base_time/31536000);
		$base_time = $base_time - ($years*31536000);
		$weeks = floor($base_time/604800);
		$base_time = $base_time - ($weeks*604800);
		$days = floor($base_time/86400);
		$base_time = $base_time - ($days*86400);
		$hours = floor($base_time/3600);
		$base_time = $base_time - ($hours*3600);
		$min = floor($base_time/60);
		$sek = $base_time - ($min*60);
		if ($sek<10) $sek ='0'.$sek;
		if ($min<10) $min ='0'.$min;
		if ($hours<10) $hours ='0'.$hours;
		$result=(($years)?$years.' '.(($years==1)?$lang['Year']:$lang['Years']).', ':'').
		(($years || $weeks)?$weeks.' '.(($weeks==1)?$lang['Week']:$lang['Weeks']).', ':'').
		(($years || $weeks || $days) ? $days.' '.(($days==1)?$lang['Day']:$lang['Days']).', ':'').
		(($hours)?$hours.':':'00:').(($min)?$min.':' :'00:').$sek;
		return ($result)?$result:$lang['None'];
	}
	// Borrowed from - Last visit MOD


//
// This is general replacement for die(), allows templated
// output in users (or default) language, etc.
//
// $msg_code can be one of these constants:
//
// GENERAL_MESSAGE : Use for any simple text message, eg. results 
// of an operation, authorisation failures, etc.
//
// GENERAL ERROR : Use for any error which occurs _AFTER_ the 
// common.php include and session code, ie. most errors in 
// pages/functions
//
// CRITICAL_MESSAGE : Used when basic config data is available but 
// a session may not exist, eg. banned users
//
// CRITICAL_ERROR : Used when config data cannot be obtained, eg
// no database connection. Should _not_ be used in 99.5% of cases
//
function mess_die($msg_code, $msg_text = '', $msg_title = '', $err_line = '', $err_file = '', $sql = '')
{
	global $db, $template, $board_config, $theme, $lang, $phpEx, $phpbb_root_path, $nav_links, $gen_simple_header, $images;
	global $userdata, $user_ip, $session_length;
	global $starttime;

	if(defined('HAS_DIED'))
	{
		die("message_die() was called multiple times. This isn't supposed to happen. Was message_die() used in page_tail.php?");
	}
	
	define('HAS_DIED', 1);
	

	$sql_store = $sql;
	
	//
	// Get SQL error if we are debugging. Do this as soon as possible to prevent 
	// subsequent queries from overwriting the status of sql_error()
	//
	if ( DEBUG && ( $msg_code == GENERAL_ERROR || $msg_code == CRITICAL_ERROR ) )
	{
		$sql_error = $db->sql_error();

		$debug_text = '';

		if ( $sql_error['message'] != '' )
		{
			$debug_text .= '<br /><br />SQL Error : ' . $sql_error['code'] . ' ' . $sql_error['message'];
		}

		if ( $sql_store != '' )
		{
			$debug_text .= "<br /><br />$sql_store";
		}

		if ( $err_line != '' && $err_file != '' )
		{
			$debug_text .= '<br /><br />Line : ' . $err_line . '<br />File : ' . basename($err_file);
		}
	}

	if( empty($userdata) && ( $msg_code == GENERAL_MESSAGE || $msg_code == GENERAL_ERROR ) )
	{
		$userdata = session_pagestart($user_ip, PAGE_INDEX);
		init_userprefs($userdata);
	}

	//
	// If the header hasn't been output then do it
	//
	if ( !defined('HEADER_INC') && $msg_code != CRITICAL_ERROR )
	{
		if ( empty($lang) )
		{
			if ( !empty($board_config['default_lang']) )
			{
				include($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_main.'.$phpEx);
			}
			else
			{
				include($phpbb_root_path . 'language/lang_english/lang_main.'.$phpEx);
			}
		}

		if ( empty($template) || empty($theme) )
		{
			$theme = setup_style($board_config['default_style']);
		}

		//
		// Load the Page Header
		//
		if ( !defined('IN_ADMIN') )
		{
			include($phpbb_root_path . 'includes/page_header.'.$phpEx);
		}
		else
		{
			include($phpbb_root_path . 'admin/page_header_admin.'.$phpEx);
		}
	}

	switch($msg_code)
	{
		case GENERAL_MESSAGE:
			if ( $msg_title == '' )
			{
				$msg_title = $lang['Information'];
			}
			break;

		case CRITICAL_MESSAGE:
			if ( $msg_title == '' )
			{
				$msg_title = $lang['Critical_Information'];
			}
			break;

		case GENERAL_ERROR:
			if ( $msg_text == '' )
			{
				$msg_text = $lang['An_error_occured'];
			}

			if ( $msg_title == '' )
			{
				$msg_title = $lang['General_Error'];
			}
			break;

		case CRITICAL_ERROR:
			//
			// Critical errors mean we cannot rely on _ANY_ DB information being
			// available so we're going to dump out a simple echo'd statement
			//
			include($phpbb_root_path . 'language/lang_english/lang_main.'.$phpEx);

			if ( $msg_text == '' )
			{
				$msg_text = $lang['A_critical_error'];
			}

			if ( $msg_title == '' )
			{
				$msg_title = 'phpBB : <b>' . $lang['Critical_Error'] . '</b>';
			}
			break;
	}

	//
	// Add on DEBUG info if we've enabled debug mode and this is an error. This
	// prevents debug info being output for general messages should DEBUG be
	// set TRUE by accident (preventing confusion for the end user!)
	//
	if ( DEBUG && ( $msg_code == GENERAL_ERROR || $msg_code == CRITICAL_ERROR ) )
	{
		if ( $debug_text != '' )
		{
			$msg_text = $msg_text . '<br /><br /><b><u>DEBUG MODE</u></b>' . $debug_text;
		}
	}

	if ( $msg_code != CRITICAL_ERROR )
	{
		if ( !empty($lang[$msg_text]) )
		{
			$msg_text = $lang[$msg_text];
		}

		if ( !defined('IN_ADMIN') )
		{
			$template->set_filenames(array(
				'message_body' => 'message_body.tpl')
			);
		}
		else
		{
			$template->set_filenames(array(
				'message_body' => 'admin/admin_message_body.tpl')
			);
		}

		$template->assign_vars(array(
			'MESSAGE_TITLE' => $msg_title,
			'MESSAGE_TEXT' => $msg_text)
		);
		$template->pparse('message_body');

		if ( !defined('IN_ADMIN') )
		{
			include($phpbb_root_path . 'includes/page_tail.'.$phpEx);
		}
		else
		{
			include($phpbb_root_path . 'admin/page_footer_admin.'.$phpEx);
		}
	}
	else
	{
		echo "<html>\n<body>\n" . $msg_title . "\n<br /><br />\n" . $msg_text . "</body>\n</html>";
	}
}

?>