<?php
/**************************************************************************************************************************
 *                                                    phpbbWapGate
 **************************************************************************************************************************
 *										               wapmenu.php
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

require 'wapheader.php';

echo $header;

echo "<anchor>" . $lang['wap_forum_home'] . "<go href=\"" . append_sid("wap.$phpEx") . "\" /></anchor><br/>\n";

//-------------------------------------------------------------------------------------------------------------------------
//---[ Forum Lists: if you have a large number of forums, replace $many_forums variable in wapconfig.php ]-----------------
//-------------------------------------------------------------------------------------------------------------------------
echo "<anchor>" . $lang['wap_forum_list'] . "<go href=\"" . append_sid("wapmisc.php?action=forumlist") . "\" /></anchor>". $aflink . $gklink . $lolink . $pzlink;

//-------------------------------------------------------------------------------------------------------------------------
//---[ The rest of the links ]---------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------
echo "<anchor>" . $lang['wap_new_topic'] . "<go href=\"" . append_sid("wapnew.$phpEx") . "\" /></anchor><br/>\n";
echo "<anchor>" . $lang['wap_write_new_pm'] . "<go href=\"" . append_sid("wappm.$phpEx?action=new") . "\" /></anchor><br/>\n";
echo "<anchor>" . $lang['wap_read_pm'] . "<go href=\"" . append_sid("wappm.$phpEx") . "\" /></anchor><br/>\n";
echo "<anchor>" . $lang['wap_whos_online'] . "<go href=\"" . append_sid("wapmisc.php?action=online") . "\" /></anchor><br/>\n";

//-------------------------------------------------------------------------------------------------------------------------
//---[ Login block ]-------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------
$login = ($userdata['session_logged_in']) ? "<anchor>" . $lang['wap_logout'] . "<go href=\"" . append_sid("waplogin.$phpEx?logout=1") . "\" /></anchor>" : "<anchor>" . $lang['wap_login'] . "<go href=\"" . append_sid("waplogin.php") . "\" /></anchor>";
echo $login . "<br/>\n";


echo $footer;

?>