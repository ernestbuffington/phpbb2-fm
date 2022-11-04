<?php
/** 
*
* @package phpBB2
* @version $Id: chatroom.php,v 1.2.0 2004/08/11 Midnightz Exp $
* @copyright (c) 2004 Midnightz
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_CHATROOM);
init_userprefs($userdata);
//
// End session management
//

$server_url = real_path('');

//If popup, simple header/footer
if( $pjirc_config['irc_popup_onoff'] )
{
	$gen_simple_header = TRUE;
}

// If PJIRC is ON show screen - OFF is waaay down
if( $pjirc_config['irc_status'] )
{
	// Checks if guests are allowed 
   	if( $pjirc_config['irc_allow_guests'] ) 
   	{                             
   		// Setting: Guests allowed
      	if ( !$userdata['session_logged_in'] ) 
      	{                           
      		// guests allowed, guest
          	$userdata['username'] = $pjirc_config['irc_guestname'];        //sets name for guests 
          	$pjirc_lang = strtolower($board_config['default_lang']);       //lang - guests 
          	$currentstyle = $board_config['default_style'];                //style - guests 
      	}
      	else 
      	{                                                             // guests allowed, user
        	$pjirc_lang = $userdata['user_lang'];                          //lang - user 
        	if ( $board_config['override_user_style'] )
        	{
        		$currentstyle = $board_config['default_style'];             //style - user overridden
          	}
          	else
            {
            	$currentstyle = $userdata['user_style'];                    //style - user
      		} 
   		} 
    }
	else 
	{    
		// Setting: Guests NOT allowed 
      	if ( !$userdata['session_logged_in'] ) 
      	{                           
      		// guests not allowed, guest
           	redirect(append_sid("login.".$phpEx."?redirect=chat.".$phpEx, true)); //login first 
           	exit; 
      	} 
      	else 
      	{                                                             // guests not allowed, user
        	$pjirc_lang = $userdata['user_lang'];                          //lang - user
        	if ( $board_config['override_user_style'] )
        	{
            	$currentstyle = $board_config['default_style'];             //style - user overridden
          	}
          	else
            {
            	$currentstyle = $userdata['user_style'];                    //style - user
			} 
   		}
	}

	// Check for lang file or set default
   	if ( !is_file(@phpbb_realpath($phpbb_root_path . 'mods/chatroom/' . $pjirc_lang . '.txt')) )
	{
		$pjirc_lang = $pjirc_config['irc_language'];                     //lang if file not exist
	}
	
	// Pull the template and related vars
	$sql = "SELECT template_name
   		FROM " . THEMES_TABLE . "
    	WHERE themes_id = " . $currentstyle;
	if( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not query template information', '', __LINE__, __FILE__, $sql);
   	}
   	while ($row = $db->sql_fetchrow($result))
   		
   	$currenttemplatename = $row['template_name'];

   	$currenttemplatefile = 'mods/chatroom/templates/' . $currenttemplatename . '.' . $phpEx;
   	if ( !is_file(@phpbb_realpath($phpbb_root_path . $currenttemplatefile)) )
   	{
   		$currenttemplatefile = 'mods/chatroom/templates/' . $pjirc_config['irc_template'] . '.' . $phpEx;
	}
   	include($currenttemplatefile);

   	$currentchattemplate = $templatelooks;
   	$currentchattable = $templatebackground;
   	$currentchatvb1 = $templateVBcol1;
   	$currentchatvb2 = $templateVBcol2;
   	$currentchatvb3 = $templateVBcol3;
   	$currentchatvb4 = $templateVBcol4;
   	$currentchatvb5 = $templateVBcol5;
   	$currentchatvb6 = $templateVBcol6;
   	$currentchatvb7 = $templateVBcol7;
   	$currentchatvb8 = $templateVBcol8;
   	$currentchatvb9 = $templateVBcol9;
   	$currentchatvb10 = $templateVBcol10;
   	$currentchatvb11 = $templateVBcol11;
   	$currentpic1 = $templatepic1;
   	$currentpic2 = $templatepic2;
   	$currentstylecolordef = $templatecolorz;
   	$currentfontdef = $templatefontz; 
	
   	if ($pjirc_config['irc_style_selector_definition'] == '') 
    {
    	$pjirc_config['irc_style_selector_definition'] = $currentstylecolordef; 
	}
	
   	if ($pjirc_config['irc_font_style_definition'] == '') 
	{
		$pjirc_config['irc_font_style_definition'] = $currentfontdef;
	}
	
	// Find out background setting and generate param codes for it
   	switch ($pjirc_config['irc_background_which'])
   	{
		case 0:
        	$backgroundparam = '<param name="style:backgroundimage" value="false">';
            break;
       	case 1:
            $backgroundparam = '<param name="style:backgroundimage" value="true"><param name="style:backgroundimage1" value="all all 0 '.$currentpic1.'">';
            break;
       	case 2:
            $backgroundparam = '<param name="style:backgroundimage" value="true"><param name="style:backgroundimage1" value="all all 0 '.$currentpic2.'">';
            break;
       	case 3:
            $backgroundparam = '<param name="style:backgroundimage" value="true"><param name="style:backgroundimage1" value="all all 0 '.$pjirc_config['irc_background_file'].'">';
            break;
	}

	// Nickname styling handler 
   	if ( $pjirc_config['irc_style_nick_left'] != '' ) 
   	{ 
    	$nickstyler = '<param name="pixx:nickprefix" value="'.$pjirc_config['irc_style_nick_left'].'">'; 
    	$nickstyler .= '<param name="pixx:nickpostfix" value="'.$pjirc_config['irc_style_nick_right'].'">'; 
   	} 
   	else 
   	{
   		$nickstyler = '';
	}
	
	// Smilies auto-enter control 
   	if ( $pjirc_config['irc_smilies_enter'] ) 
   	{ 
    	$smiliesenter = ";document.pjirc.validateText()"; 
   	} 
   	else 
   	{ 
    	$smiliesenter = ''; 
   	} 

	// Let's pull the smilies and make a giant var for the template
   	$sql = "SELECT emoticon, code, smile_url 
    	FROM " . SMILIES_TABLE . " 
       	ORDER BY smilies_id"; 
	if ( !($result = $db->sql_query($sql)) ) 
   	{ 
    	message_die(GENERAL_ERROR, "error getting smilies", '', __LINE__, __FILE__, $sql); 
   	}	 

   	$i = 1; 
   	$smilies = ''; 

   	while ($row = $db->sql_fetchrow($result)) 
   	{ 
   		$smilies .= '<param name="style:smiley' . $i . '" value="' . $row['code'] . ' ../' . $board_config['smilies_path'] . '/' . $row['smile_url'] . '">'; 
    	$smileyurl[$i] = $row['smile_url']; 
    	$smileycode[$i] = $row['code']; 
    	$smileyemote[$i] = $row['emoticon']; 
    	$i = $i + 1; 
   	} 


   	if ( !$pjirc_config['irc_smilies'] ) 
   	{ 
    	$smileybuttons = ''; 
   	} 
   	else 
   	{ 
    	$smileybuttons = $emotebuff = ''; 
      	$smileycount = $i; 
      	$smileydisplaycount = $i = 0;

      	while ( $i < $smileycount ) 
      	{ 
        	if ( $smileydisplaycount < $pjirc_config['irc_smilies_count'] ) 
        	{ 
        		if ( $emotebuff != $smileyurl[$i] ) 
           		{ 
              		$smileydisplaycount++; 
              		$smileybuttons .= '<img src="' . $board_config['smilies_path'] . '/' . $smileyurl[$i] . '" name="'.$smileyemote[$i].'" alt="'.$smileyemote[$i].'" style="cursor: hand; " onClick="document.pjirc.setFieldText(document.pjirc.getFieldText()+\''.$smileycode[$i].'\');document.pjirc.requestSourceFocus()'.$smiliesenter.'">&nbsp;';
              		if ( $smileydisplaycount%$pjirc_config['irc_smilies_lines'] == 0)
              		{
              			$smileybuttons .= '<br />';
           			}
           		}
           		$emotebuff = $smileyurl[$i]; 
        	} 
        	$i++; 
      	} 
   	}

	// Test for IRC-acceptable user name - still need >> [] & {} apparently <<
	$nick_name = ereg_replace("[^0-9a-zA-Z_`\e\|\^\{\}\-]", "_", $userdata['username']);
	if ( ereg_replace("[0-9\-]", "X", $nick_name{0})=="X" )
	{
	   $nick_name = "^" . $nick_name;
	}
      
	// MirrorBOT control
   	if ( !$pjirc_config['irc_bot_overall'] ) 
   	{
    	$bot_popup = $bot_var = $bot_commandz = '';
   	} 
   	else 
   	{ 
    	$bot_popup = '<param name="pixx:popupmenustring9" value="Help Topics">';
    	$switch1 = ( $pjirc_config['irc_bot_switch1'] ) ? 'true' : 'false'; 
    	$switch2 = ( $pjirc_config['irc_bot_switch2'] ) ? 'true' : 'false'; 
    	$bot_var = "<param name='plugin2' value='adnd.DescBot'><param name='descbot:respondercmd' value='!'><param name='descbot:scriptvariables' value='true'><param name='descbot:nickresponder' value='".$switch1."'><param name='descbot:textresponders' value='".$switch2."'>"; 
		include('mods/chatroom/botz/mirrorbot.'.$phpEx); 
    	$bot_var .= $mirrorbot;
    	$bot_commandz = "setTimeout('document.pjirc.requestSourceFocus();document.pjirc.setFieldText(\"/msg \$chan !\$me\");document.pjirc.validateText();', ".$pjirc_config['irc_bot_timer'].");";
   	}

	// Extra channels
	if ( $pjirc_config['irc_channel2_on'] ) 
    {
    	$userdata['irc_commands'] .= ";/join ".$pjirc_config['irc_channel2'];
    	if ( $pjirc_config['irc_channel3_on'] ) 
     	{
        	$userdata['irc_commands'] .= ";/join ".$pjirc_config['irc_channel3'];
        }
   	}

	// Parse real servername -> alias
   	$serverparts = explode(".", $pjirc_config['irc_server']); 
   	$serveralias = $serverparts[(count($serverparts)-2)]; 

	// Multi-server controls into topscript 'commandz'
   	if ( $pjirc_config['irc_multiserver'] ) 
   	{ 
    	$multiserverparts = explode(".", $pjirc_config['irc_multiserver_server']); 
    	$multiserveralias = $multiserverparts[(count($multiserverparts)-2)]; 
    	$multiservercommandz = "setTimeout('document.pjirc.sendString(\"/newserver ".$multiserveralias." ".$pjirc_config['irc_multiserver_server']." ".$pjirc_config['irc_multiserver_port']."\");', ".$pjirc_config['irc_multiserver_delay'].");"; 
   	} 
   	else 
   	{ 
    	$multiservercommandz = ''; 
   	}

	// Audio Player
   	$dir = @opendir($phpbb_root_path.'mods/chatroom/player/'); 
   	$count = 0; 
   	while( $file = @readdir($dir) ) 
   	{ 
		if( !@is_dir(phpbb_realpath($phpbb_root_path.'mods/chatroom/player/'.$file)) ) 
        {
        	if( preg_match('/(\.wav$|\.mid$|\.mp3$|\.ogg$|\.rm$|\.au$|\.asf$|\.wma$)$/is', $file) ) 
            { 
            	$chat_mp3s[$count] = $file; 
                $count++; 
            } 
		} 
   	} 
   	@closedir($dir);
   	if ( sizeof($chat_mp3s) > 1 ) // error handling for sort 
   	sort($chat_mp3s); // sort by number
   	$player_count = sizeof($chat_mp3s); 
   	$player_list = "if ((musiccount > " . $player_count . ") || (musiccount < 1)){musiccount=1;}\n"; 

   	for( $i = 0; $i < $player_count; $i++ ) 
   	{ 
    	$player_list .= 'if (musiccount==' . ($i+1) . '){document.all.music.src="' . $server_url . 'mods/chatroom/player/' . $chat_mp3s[$i] . '";}'; 
   	}

	//Here starts the actual page
   	$page_title = $lang['Chat_Room'];
   	include($phpbb_root_path . 'includes/page_header.'.$phpEx);

   	$template->set_filenames(array(
   	    'body' => 'chat_body.tpl')
   	);

   	$template->assign_vars(array(
   		'PJIRC_MOD_VERSION' => $pjirc_config['irc_mod_version'],
     	'SITEPATH' => $server_url . '/',
     	'L_TITLE' => $page_title,
     	'NICKNAME' => $nick_name,
     	'USERNAME' => $userdata['username'],
     	'SERVER' => $pjirc_config['irc_server'],
     	'PORT' => $pjirc_config['irc_port'],
     	'CHANNEL' => $pjirc_config['irc_channel'],
     	'LANGUAGE' => $pjirc_lang,
     	'CURRENTCHATTEMPLATE' => $currentchattemplate,
     	'CURRENTCHATTABLE' => $currentchattable,
     	'PLAYERCOUNT' =>  $player_count,
     	'PLAYERLIST' =>  $player_list,
     	'CURRENTCHATVB1' => $currentchatvb1,
     	'CURRENTCHATVB2' => $currentchatvb2,
     	'CURRENTCHATVB3' => $currentchatvb3,
     	'CURRENTCHATVB4' => $currentchatvb4,
     	'CURRENTCHATVB5' => $currentchatvb5,
     	'CURRENTCHATVB6' => $currentchatvb6,
     	'CURRENTCHATVB7' => $currentchatvb7,
     	'CURRENTCHATVB8' => $currentchatvb8,
     	'CURRENTCHATVB9' => $currentchatvb9,
     	'CURRENTCHATVB10' => $currentchatvb10,
     	'CURRENTCHATVB11' => $currentchatvb11,
     	'SHOW_CONNECT' => ( $pjirc_config['irc_show_connect'] ) ? 'true' : 'false',
     	'SHOW_CHANLIST' => ( $pjirc_config['irc_show_chanlist'] ) ? 'true' : 'false',
     	'SHOW_ABOUT' => ( $pjirc_config['irc_show_about'] ) ? 'true' : 'false',
     	'SHOW_HELP' => ( $pjirc_config['irc_show_help'] ) ? 'true' : 'false', 
     	'HELPPAGE' => $server_url . append_sid('chat_help.'.$phpEx),
     	'SHOW_CLOSE' => ( $pjirc_config['irc_show_close'] ) ? 'true' : 'false',
     	'SHOW_STATUS' => ( $pjirc_config['irc_show_status'] ) ? 'true' : 'false',
     	'SHOW_DOCK' => ( $pjirc_config['irc_show_dock'] ) ? 'true' : 'false',
     	'SHOW_NICKFIELD' => ( $pjirc_config['irc_show_nickfield'] ) ? 'true' : 'false',
     	'TIME_STAMP' => ( $pjirc_config['irc_time_stamp'] ) ? 'true' : 'false',
     	'TOPICSCROLLER' => $pjirc_config['irc_topicscroller'],
     	'QUIT_MESSAGE' => $pjirc_config['irc_quit'],
     	'SMILIESBUTTONS' => $smileybuttons,
     	'SMILIES' => $smilies,
     	'SOUND_BEEP' => $pjirc_config['irc_sound_beep'],
     	'SOUND_BEEP_DELAY' => $pjirc_config['irc_enter_timer'],
     	'SOUND_QUERY' => $pjirc_config['irc_sound_query'],
     	'SOUND_PROFILE' => $pjirc_config['irc_sound_profile'],
     	'SOUND_IM' => $pjirc_config['irc_sound_im'],
     	'SOUND_IGNORE' => $pjirc_config['irc_sound_ignore'],
     	'SOUND_UNIGNORE' => $pjirc_config['irc_sound_unignore'],
     	'SOUND_AWAY' => $pjirc_config['irc_sound_away'],
     	'SOUND_BACK' => $pjirc_config['irc_sound_back'],
     	'SOUND_CLEAR' => $pjirc_config['irc_sound_clear'],
     	'SOUND_WHOIS' => $pjirc_config['irc_sound_whois'],
     	'SOUND_HELP' => $pjirc_config['irc_sound_help'],
     	'MIRRORBOT' => $bot_var,
     	'BOT_POPUP' => $bot_popup,
     	'BOT_COMMANDZ' => $bot_commandz,
     	'AUTHJOINLIST' => $pjirc_config['irc_auth_joinlist'],
     	'MULTISERVERALIAS' =>  $multiserveralias,
     	'MULTISERVERCOMMANDZ' =>  $multiservercommandz,
     	'MULTISERVER' => ( $pjirc_config['irc_multiserver'] ) ? 'true' : 'false',
     	'USE_INFO' => ( $pjirc_config['irc_use_info'] ) ? 'true' : 'false',
     	'STYLE_SELECTOR' => ( $pjirc_config['irc_style_selector'] ) ? 'true' : 'false',
     	'STYLE_SELECTOR_DEFINITION' => $pjirc_config['irc_style_selector_definition'],
     	'FONT_STYLE' => ( $pjirc_config['irc_font_style'] ) ? 'true' : 'false',
     	'FONT_STYLE_DEFINITION' => $pjirc_config['irc_font_style_definition'],
     	'NICK_STYLE' => $nickstyler,
     	'SHOW_HIGHLIGHT' => ( $pjirc_config['irc_show_highlight'] ) ? 'true' : 'false',
     	'HIGHLIGHTCOLOR' => $pjirc_config['irc_highlightcolor'],
     	'HIGHLIGHTWORDS' => $pjirc_config['irc_highlightwords'],
     	'BACKGROUND' => $backgroundparam)
   	);	

   	$commands = explode(";", $userdata['irc_commands']);
   	$i = 0;
   	while ($i < sizeof($commands))
   	{
   		$template->assign_block_vars('commands', array(
        	'COMMAND' => trim($commands[$i]),
           	'NUMBER' => $i + 2)
		);
    	$i++;
	}

	// Here goes the sound loop sound loop sound loop
   	$sounds1 = explode(' ', $pjirc_config['irc_soundwords1']); 
   	$i = 0; 
   	while ($i < sizeof($sounds1)) 
   	{ 
       	$template->assign_block_vars('sounds1', array( 
        	'SOUNDWORD1' => trim($sounds1[$i]), 
           	'SOUND1' => $pjirc_config['irc_sound1'], 
           	'SOUND1NUMBER' => $i + 1) 
        ); 
    	$i++; 
   	} 
    $soundcount = $i; 

	// 2nd time around time around time around
   	$sounds2 = explode(' ', $pjirc_config['irc_soundwords2']); 
   	$i = 0; 
   	while ($i < sizeof($sounds2)) 
   	{ 
       	$template->assign_block_vars('sounds2', array( 
        	'SOUNDWORD2' => trim($sounds2[$i]), 
           	'SOUND2' => $pjirc_config['irc_sound2'], 
			'SOUND2NUMBER' => $i + 1 + $soundcount) 
		); 
    	$i++; 
    }

	//
	// Force password update
	//
	if ($board_config['password_update_days'])
	{	
		include($phpbb_root_path . 'includes/update_password.'.$phpEx);
	}

    $template->pparse('body');
}
else
{
   	message_die(GENERAL_MESSAGE, 'IRC_disabled');
}

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>