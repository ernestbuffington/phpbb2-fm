<?php
/** 
*
* @package includes
* @version $Id: guestbook.php,v 2.2.0 2006/02/5 12:02:15 nardi Exp $
* @copyright (c) 2001 Cricca Hi!Wap
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}

// include language file
$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_guestbook.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_guestbook.' . $phpEx);


class guestbook
{
    var $guestbook_config = array();
    var $patterns_b = "#\[b\](.*?)\[/b\]#si";
    var $patterns_i = "#\[i\](.*?)\[/i\]#si";
    var $patterns_u = "#\[u\](.*?)\[/u\]#si";
	var $patterns_img = "#\[img\]([^?].*?)\[/img\]#si";
	var $patterns_url0 = "#\[url\](.*?)\[/url\]#si";
	var $patterns_url = "#\[url\]([\w]+?://[^ \"\n\r\t<]*?)\[/url\]#is";
	var $patterns_url1 = "#\[url\]((www|ftp)\.[^ \"\n\r\t<]*?)\[/url\]#is";
	var $patterns_url2 = "#\[url=([\w]+?://[^ \"\n\r\t<]*?)\](.*?)\[/url\]#is";
	var $patterns_url3 = "#\[url=((www|ftp)\.[^ \"\n\r\t<]*?)\](.*?)\[/url\]#is";
	var $patterns_url4 = "#(^|[\n ])([\w]+?://[^ \"\n\r\t<]*)#is";
	var $patterns_url5 = "#(^|[\n ])((www|ftp)\.[^ \"\t\n\r<]*)#is";
	var $patterns_email = "#\[email\]([a-z0-9&\-_.]+?@[\w\-]+\.([\w\-\.]+\.)?[\w]+)\[/email\]#si";
	var $patterns_email1 = "#(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)#i";
    var $patterns_code = "#\[code\](.*?)\[/code\]#si";
    var $patterns_code1 = "#\[/code\]#si";
    var $patterns_code2 = "#\[code\]#si";
    var $patterns_quote = "#\[quote\](.*?)\[/quote\]#si";
    var $patterns_quote1 = "#\[quote=\"(.*?)\"\](.*?)\[/quote\]#si";
    var $patterns_quote2 = "#/\[quote=\"(.*?)\"\]/#si";
    var $patterns_quote3 = "#\[/quote\]#si";
    var $patterns_quote4 = "#\[quote\]#si";
    var $patterns_color = "#\[color=(\#[0-9A-F]{6}|[a-z]+)\](.*?)\[/color\]#si";
    var $patterns_color1 = "#\[color=(\#[0-9A-F]{6}|[a-z]+)\]#si";
    var $patterns_color2 = "#\[/color\]#si";
    var $patterns_size = "#\[size=([1-2]?[0-9])\](.*?)\[/size\]#si";
    var $patterns_size1 = "#\[size=([1-2]?[0-9])\]#si";
    var $patterns_size2 = "#\[/size\]#si";
    
  	function guestbook()
  	{
    	global $db;
    
    	$sql = "SELECT * 
    		FROM " . GUESTBOOK_CONFIG_TABLE;
    	if( !($result = $db->sql_query($sql)) )
    	{
	    	message_die(GENERAL_ERROR, "Could not query config information from guestbook", "", __LINE__, __FILE__, $sql);
      	}

    	while ( $row = $db->sql_fetchrow($result) )
      	{
        	$this->guestbook_config[$row['config_name']] = $row['config_value'];
      	}
      	$db->sql_freeresult($result);
  	}
  
  	function guest_config()
  	{
    	return $this->guestbook_config;
  	}
  
  	function guest_counter()
  	{
    	$this->guestbook_config['contatore'] = intval($this->guestbook_config['contatore']) + 1;
    	$this->update_guestbook('contatore', $this->guestbook_config['contatore']);
  	}

	function globalRegCheck( $string ) 
	{
 		if ( preg_match($this->patterns_img, $string) 
 		|| preg_match($this->patterns_url, $string)
   		|| preg_match($this->patterns_url1, $string)
   		|| preg_match($this->patterns_url2, $string)
   		|| preg_match($this->patterns_url3, $string)
   		|| preg_match($this->patterns_url4, $string)
   		|| preg_match($this->patterns_url5, $string)
   		|| preg_match($this->patterns_email, $string)
   		|| preg_match($this->patterns_email1, $string)
   		|| preg_match($this->patterns_code, $string)
   		|| preg_match($this->patterns_code1, $string)
   		|| preg_match($this->patterns_code2, $string)
   		|| preg_match($this->patterns_quote, $string)
   		|| preg_match($this->patterns_quote1, $string)
   		|| preg_match($this->patterns_quote2, $string)
   		|| preg_match($this->patterns_quote3, $string)
   		|| preg_match($this->patterns_quote4, $string)
   		|| preg_match($this->patterns_color, $string)
   		|| preg_match($this->patterns_color1, $string)
   		|| preg_match($this->patterns_color2, $string)
   		|| preg_match($this->patterns_size, $string)
   		|| preg_match($this->patterns_size1, $string) 
   		|| preg_match($this->patterns_size2, $string) )
		{
	 		return true;
   		}
  		else 
  		{
        	return false;
 	   	}
  	}

	function word_wrap( $text, $size, $force = true, $tag = '' ) 
	{
    	if( $this->guestbook_config['word_wrap'] ) 
    	{
			$withN = explode("\n\r", trim($text));
			$globalWrap = '';
			for ($a = 0; $za = sizeof($withN), $a < $za; $a++ ) 
			{
				$ex = explode(" ", trim($withN[$a]));
				for ($b = 0; $zb = sizeof($ex), $b < $zb; $b++ ) 
				{
					if ($this->globalRegCheck($ex[$b]) ) 
					{
						$globalWrap .= $ex[$b];
					}
					else 
					{
						$provvisory = '';
						$where = 0;
						if ($force == false) 
						{
							if ( strlen($ex[$b]) > $size && !is_numeric($ex[$b]) ) 
							{
								if ($tag != '') 
								{
							   		for($c = 0; $zc = (ceil(strlen($ex[$b]) / $size)), $c < $zc; $c++) 
							   		{
										$provvisory .= substr($ex[$b], $where, $size).$tag;
										$where += $size;
							   		}
								} 
								else 
								{
							  		$provvisory = substr($ex[$b], $where, $size);
								}
							}
							else 
							{
								$provvisory = $ex[$b];
							}
						}
						else 
						{
							if (strlen($ex[$b]) > $size) 
							{
                            	if ($tag != '') 
                            	{
							   		for($c = 0; $zc = (ceil(strlen($ex[$b]) / $size)), $c < $zc; $c++) 
							   		{
										$provvisory .= substr($ex[$b], $where, $size).$tag;
										$where += $size;
							   		}
								} 
								else 
								{
							  		$provvisory = substr($ex[$b],$where,$size);
								}
							}
							else 
							{
								$provvisory = $ex[$b];
							}
						}
						$globalWrap .= $provvisory;
					}
					$globalWrap .= " ";
				}
				$globalWrap .= "\n\r";
			}
		
			return $globalWrap;
      	}
      	else 
      	{
        	return $text;
        }
	}

	function check_post_error ($message)
	{
  		global $lang;
   
   		//
   		//blocco dei msg che hanno solo un riporta
   		//
   		if ( $this->guestbook_config['no_only_quote'] )
        {
			$text = preg_replace('/(\[quote)(.*)(\[\/quote\])/si', '', $message);
        	if (trim($text) == '')  
        	{
        		return array('error' => true, 'error_msg' => $lang['Empty_message']);
        	}
    	}
    	
    	//
   		//blocco dei msg che hanno solo smilies
   		//
   		if ( $this->guestbook_config['no_only_smilies'] )
     	{
       		$text =  smilies_pass($message);
       		$text = preg_replace("/(\<img)(.*?)(\>)/si", "", $text);
       		if ($this->check_bbcode_error($text) == '')  
       		{
       			return array('error' => true, 'error_msg' => $lang['Empty_message']);
     		}
        }
        
      	//
   		//blocco dei msg che hanno solo bbcode
   		//
   		if ($this->check_bbcode_error($message) == '')
     	{
        	return array('error' => true, 'error_msg' => $lang['Empty_message']);
     	}
     
    	return array('error' => false, 'error_msg' => '');
	}

	function check_bbcode_error ($text)
	{
    	if (trim($text) == '') 
    	{
    		return $text;
    	}
    	
  		$text = preg_replace ($this->patterns_b, '\\1', $text);
  		$text = ($text != '') ? trim(preg_replace ($this->patterns_i, '\\1', $text)) : $text;
  		$text = ($text != '') ? trim(preg_replace ($this->patterns_u, '\\1', $text)) : $text;
  		$text = ($text != '') ? trim(preg_replace ($this->patterns_img, '\\1', $text)) : $text;
  		$text = ($text != '') ? trim(preg_replace ($this->patterns_url0, '\\1', $text)) : $text;
  		$text = ($text != '') ? trim(preg_replace ($this->patterns_url, '\\1', $text)) : $text;
  		$text = ($text != '') ? trim(preg_replace ($this->patterns_url1, '\\1', $text)) : $text;
  		$text = ($text != '') ? trim(preg_replace ($this->patterns_url2, '\\2', $text)) : $text;
  		$text = ($text != '') ? trim(preg_replace ($this->patterns_url3, '\\2', $text)) : $text;
  		$text = ($text != '') ? trim(preg_replace ($this->patterns_email, '\\1', $text)) : $text;
  		$text = ($text != '') ? trim(preg_replace ($this->patterns_email1, '\\2', $text)) : $text;
  		$text = ($text != '') ? trim(preg_replace ($this->patterns_code, '\\1', $text)) : $text;
  		$text = ($text != '') ? trim(preg_replace ($this->patterns_quote, '\\1', $text)) : $text;
  		$text = ($text != '') ? trim(preg_replace ($this->patterns_quote1, '\\2', $text)) : $text;
  		$text = ($text != '') ? trim(preg_replace ($this->patterns_color, '\\2', $text)) : $text;
  		$text = ($text != '') ? trim(preg_replace ($this->patterns_size, '\\2', $text)) : $text;

		return $text;
	}

	function update_guestbook($config_name, $config_value)
	{
    	global $db;
    
    	$sql = "UPDATE " . GUESTBOOK_CONFIG_TABLE . " 
    		SET config_value = '" . str_replace("\'", "''", $config_value) . "'
		    WHERE config_name = '$config_name'";
		if( !$db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, "Failed to update general configuration for $config_name", "", __LINE__, __FILE__, $sql);
		}

     	$this->guestbook_config[$config_name] = $config_value;
  	}
  
 	function ed($t) 
 	{
    	$password = $this->guestbook_config['password'];
    	$password = date('Ymd') - $password;
      	$r = md5($password);
      	$c = 0;
      	$v = '';
      	for ($i = 0; $i < strlen($t); $i++) 
      	{
        	if ($c == strlen($r)) 
        	{
        		$c = 0;
         	}
         	$v.= substr($t, $i, 1) ^ substr($r, $c, 1);
         	$c++;
      	}
      	
      	return $v;
   	}
   
   function encrypt($t)
   {
   		srand((double)microtime() * 1000000);
      	$r = md5(rand(0, 32000));
      	$c = 0;
      	$v = '';
      	for ($i = 0; $i < strlen($t); $i++)
      	{
        	if ($c == strlen($r)) 
        	{
        		$c = 0;
        	}
         	$v.= substr($r, $c, 1) . (substr($t, $i, 1) ^ substr($r, $c, 1));
         	$c++;
      	}
      	
      	return base64_encode($this->ed($v));
	}
   
   	function decrypt($t) 
   	{
    	$t = $this->ed(base64_decode($t));
    	$v = '';
      	for ($i = 0; $i < strlen($t); $i++)
      	{
        	$md5 = substr($t, $i, 1);
         	$i++;
         	$v.= (substr($t, $i, 1) ^ $md5);
      	}
      	
      	return $v;
   	}
}

?>