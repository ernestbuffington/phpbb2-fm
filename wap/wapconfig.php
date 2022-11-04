<?php
/**************************************************************************************************************************
 *                                                    phpbbWapGate
 **************************************************************************************************************************
 *										              wapconfig.php
 *									              --------------------
 *											   version 2.0 - modified 12.09.04 
 **************************************************************************************************************************
 *														AUTHORS:
 *
 *									Chirs Gray								  Valentin Vornicu
 *							   info@plus-media.co.uk						valentin@mathlinks.ro
 *							    www.plus-media.co.uk			              www.mathlinks.ro
 * 
 *************************************************************************************************************************/

// ---[ please ignore the below line ]------------------------------------------------------------------------------------
$wapconfig = array();					

//-------------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------
//---[ this is the only place you should edit something! ]-----------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------
// change this to your Default Wap Site Title (insteaf of Wap Forum)
$wapconfig['site_title'] = "Forum :: WAP Version"; 		

// change this to the number of topics you want to be able to display from one forum
$maxtopics = "100"; 

// if you have many forums, put 1 below, instead of 0
$many_forums = 1; 

//-------------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------
//---[ do not edit any other lines, unless you really know what you are doing! ]-------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------

// the directory of the wap files and the phpbb default directory, this assuming that you have respected the instructions 
// and placed the wap dir where it should be placed, or you used easymod to install it
$wappath = getcwd();

// this applies to both linux, unix based servers, and windows servers
$phpbb_root_path = preg_replace ("/(.*)\/\w+/","\$1/", $wappath); 
$phpbb_root_path = preg_replace ("/(.*\\\\)\w+/","\$1", $phpbb_root_path);

// the url of the forum.
$server = $_SERVER['SERVER_NAME']; 
$url_path = $_SERVER['SCRIPT_NAME']; 
$url_path = "http://" . $server . preg_replace("/(\/.*)\/\w+\/wap.*\.php/","\$1/", $url_path); 

// the name of the dir in which the wap files are in 
$wapdir = str_replace( $phpbb_root_path, '', $wappath); 

// specifies which files show the full footer
$footer_show = array(
	"$wappath/waplogin.php",
	"$wappath/wapreply.php",
	"$wappath/wapmenu.php"
);

?>