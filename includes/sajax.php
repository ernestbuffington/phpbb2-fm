<?php

//
// SAJAX ( http://www.modernmethod.com/sajax/ )
// Modified by wGEric & *=Matt=*
//

if ( !isset($sajax) )
{
	$sajax = new sajax;
}
else
{
	return;
}

class sajax
{
	var $debug_mode = false;
	var $export_list = array();
	var $request_type = 'GET';
	var $remote_uri = '';
	var $js_has_been_shown = false;
	

	function init($url)
	{
		global $phpbb_root_path;

		$this->remote_uri = $phpbb_root_path . $url;
	}
	
	function handle_client_request()
	{
		global $HTTP_GET_VARS, $HTTP_POST_VARS;

		$mode = '';
		
		if (! empty($HTTP_GET_VARS['rs']) )
		{
			$mode = 'get';
		}
		
		if ( !empty($HTTP_POST_VARS['rs']) )
		{
			$mode = 'post';
		}
			
		if ( empty($mode) )
		{
			return;
		}

		if ($mode == 'get')
		{
			// Bust cache in the head
			header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT');    // Date in the past
			header ('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT'); // always modified
			header ('Cache-Control: no-cache, must-revalidate');  // HTTP/1.1
			header ('Pragma: no-cache');                          // HTTP/1.0
			$func_name = $HTTP_GET_VARS['rs'];
			if ( !empty($HTTP_GET_VARS['rsargs']) )
			{
				$args = $HTTP_GET_VARS['rsargs'];
			}
			else
			{
				$args = array();
			}
		}
		else
		{
			$func_name = $HTTP_POST_VARS['rs'];
			if ( !empty($HTTP_POST_VARS['rsargs']) ) 
			{
				$args = $HTTP_POST_VARS['rsargs'];
			}
			else
			{
				$args = array();
			}
		}
		
		if ( !in_array($func_name, $this->export_list) )
		{
			echo "-:$func_name not callable";
		}
		else
		{
			echo "+:";
			$result = call_user_func_array($func_name, $args);
			echo $result;
		}
		exit;
	}
	
	function get_common_js()
	{
		global $lang, $board_config, $is_auth;
		$t = strtoupper($this->request_type);
		if ( $t != 'GET' && $t != 'POST' )
		{
			return "// Invalid type: $t.. \n\n";
		}
		
		ob_start();
		?>
// remote scripting library
// (c) copyright 2005 modernmethod, inc
// edited by *=Matt=*
var I_REQUEST_TYPE 	= "<?php echo $t; ?>";
var I_DEBUG_MODE 	= <?php echo $this->debug_mode ? "true" : "false"; ?>;
var I_ADD_POLLS		= <?php echo $is_auth['auth_pollcreate'] ? "true" : "false"; ?>;
var I_MOVE		= <?php echo $is_auth['auth_mod'] ? "true" : "false"; ?>;

function sajax_debug(text)
{
	if (I_DEBUG_MODE)
	{
		alert("RSD: " + text);
	}
}
function sajax_init_object() 
{
 	sajax_debug("sajax_init_object() called..")
 	var A;
	try 
	{
		A = new ActiveXObject("Msxml2.XMLHTTP");
	}
	catch (e)
	{
		try
		{
			A = new ActiveXObject("Microsoft.XMLHTTP");
		} 
		catch (oc) 
		{
			A = null;
		}
	}
	if(!A && typeof XMLHttpRequest != "undefined")
	{
		A = new XMLHttpRequest();
	}
	if (!A)
	{
		sajax_debug("Could not create connection object.");
	}
	return A;
}
function sajax_do_call(func_name, args)
{
	var i, x, n;
	var uri;
	var post_data;
	uri = "<?php echo $this->remote_uri; ?>";
	if (I_REQUEST_TYPE == "GET")
	{
		if (uri.indexOf("?") == -1)
		{
			uri = uri + "?rs=" + escape(func_name);
		}
		else
		{
			uri = uri + "&rs=" + escape(func_name);
		}

		if( args[args.length-1] == "2")
		{
			for (i = 0; i < args.length-3; i++)
			{
				uri = uri + "&rsargs[]=" + escape(ag(args[i]));
				uri = uri + "&rsrnd=" + new Date().getTime();
				post_data = null;
			}
		}
		else
		{
			for (i = 0; i < args.length-1; i++)
			{
				uri = uri + "&rsargs[]=" + escape(ag(args[i]));
				uri = uri + "&rsrnd=" + new Date().getTime();
				post_data = null;
			}
		}
	} 
	else 
	{
		post_data = "rs=" + escape(func_name);

		if( args[args.length-1] == 2 )
		{
			for (i = 0; i < args.length-3; i++)
			{
				post_data = post_data + "&rsargs[]=" + escape(ag(args[i]));
			}
		}
		else
		{
			for (i = 0; i < args.length-1; i++) 
			{
				post_data = post_data + "&rsargs[]=" + escape(ag(args[i]));
			}
		}
	}
	x = sajax_init_object();
	x.open(I_REQUEST_TYPE, uri, true);

	if (I_REQUEST_TYPE == "POST")
	{
		x.setRequestHeader("Method", "POST " + uri + " HTTP/1.1");
		x.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=" + config['use_charset']);
	}

	x.onreadystatechange = function()
		{
			if (x.readyState != 4)
			{
				return;
			}

			sajax_debug("received " + x.responseText);

			var status;
			var data;
			status = x.responseText.charAt(0);
			data = x.responseText.substring(2);
			if (status == "-")
			{
				alert("Error: " + data);
				ih();
				return true;
			}
			else
			{
				if( args[args.length-1] == 2 )
				{
					args[args.length-3](data,args[args.length-2]);
				}
				else
				{
					args[args.length-1](data);
				}
			}
		}

	x.send(post_data);
	sajax_debug(func_name + " uri = " + uri + "/post = " + post_data);
	sajax_debug(func_name + " waiting..");
	delete x;
}
<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function show_common_js()
	{
		return $this->get_common_js();
	}
	
	// javascript escape a value
	function esc($val)
	{
		return str_replace('"', '\\\\"', $val);
	}

	function get_one_stub($func_name)
	{
	ob_start();	
?>
function x_<?php echo $func_name; ?>()
{
	sajax_do_call("<?php echo $func_name; ?>", x_<?php echo $func_name; ?>.arguments);
}

<?php
	$html = ob_get_contents();
	ob_end_clean();
	return $html;
	}
	
	function show_one_stub($func_name)
	{
		return $this->get_one_stub($func_name);
	}
	
	function export()
	{		
		$n = func_num_args();
		for ($i = 0; $i < $n; $i++) {
			$this->export_list[] = func_get_arg($i);
		}
	}
	
	function get_javascript()
	{
		$html = '';
		if ( !$this->js_has_been_shown )
		{
			$html .= $this->get_common_js();
			$this->js_has_been_shown = true;
		}
		foreach ($this->export_list as $func) {
			$html .= $this->get_one_stub($func);
		}

		return $html;
	}
	
	function show_javascript()
	{
		global $template;
		$template->assign_var('AJAX_JS', $this->get_javascript());
		return;
	}
}
?>
