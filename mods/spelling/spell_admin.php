<?php
// --------------------------------------------------------------------
// phpSpell Dictionary Installation
//
// This is (c)Copyright 2002-2006 Team phpSpell.
// --------------------------------------------------------------------

define ("IN_SPELL_ADMIN", true);

$start_time = time();
$safe_mode = (bool) ini_get("safe_mode");

// Override Safe Mode
if (isset($HTTP_POST_VARS["SM"]) || isset($_REQUEST['SM'])) 
{
	$safe_mode = true;
}
if ($safe_mode == false) 
{
	@set_time_limit(6000);
}
$exec_time = ini_get("max_execution_time")-2;

// Override Exec Time
if (isset($_REQUEST["SM"]) || isset($HTTP_POST_VARS['SM'])) 
{
	if ($exec_time > 28) 
	{
		$exec_time = 28;
  	}
}

// Include Spell Configuration
include('spell_config.php');
if (!defined("PHPSPELL_CONFIG")) 
{
	exit;
}

if ($Spell_Config["DB_MODULE"] == "pspell") 
{
    echo "<font size=+2><b>You do not need to run this!</b></font>";
    exit;
}

//
// Output page header
//
echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<style type="text/css">
<!--
body { 
	background-color: #'.$theme['body_bgcolor'].';
	scrollbar-face-color: #'.$theme['tr_color2'].';
	scrollbar-highlight-color: #'.$theme['td_color2'].';
	scrollbar-shadow-color: #'.$theme['tr_color2'].';
	scrollbar-3dlight-color: #'.$theme['tr_color3'].';
	scrollbar-arrow-color: #'.$theme['body_link'].';
	scrollbar-track-color: #'.$theme['tr_color1'].';
	scrollbar-darkshadow-color: #'.$theme['th_color1'].';
}

font,th,td,p { font-size: '.$theme['fontsize2'].'px; color: #'.$theme['body_text'].'; font-family: '.$theme['fontface1'].'; }

h1 {
	font-weight: bold; font-size: 22px; font-family: "'.$theme['fontface2'].'",'.$theme['fontface1'].';
	text-decoration: none; line-height: 120%; color: #'.$theme['body_text'].';
}

td.row1	{ background-color: #'.$theme['tr_color1'].'; }
td.row2	{ background-color: #'.$theme['tr_color2'].'; }

th {
	color: #'.$theme['fontcolor3'].'; font-size: #'.$theme['fontsize3'].'px; font-weight: bold; 
	background-color: #'.$theme['body_link'].';
	background-image: url('.$phpbb_root_path . 'templates/'.$theme['template_name'].'/images/'.$theme['th_class2'].');
	border: #'.$theme['td_color2'].'; border-style: solid; height: 25px; border-width: 0px 0px 0px 0px; 
}

td.catBottom {
	font-size: '.$theme['fontsize3'].'px; 
	background-color: #'.$theme['tr_color3'].'; 
	background-image: url('.$phpbb_root_path . 'templates/'.$theme['template_name'].'/images/'.$theme['th_class1'].');
	border: #'.$theme['td_color2'].'; border-style: solid; height: 28px; border-width: 0px 0px 0px 0px;
}

input,textarea, select {
	color: #'.$theme['body_text'].';
	font: normal '.$theme['fontsize2'].'px '.$theme['fontface1'].';
	border-color: #'.$theme['body_text'].';
	border-top-width : 1px; 
	border-right-width : 1px; 
	border-bottom-width : 1px; 
	border-left-width : 1px;  
}

input.post, textarea.post, select {
	background-color: #'.$theme['td_color2'].';
}

input { text-indent : 2px; }

input.mainoption {
	background-color: #'.$theme['td_color1'].';
	font-weight: bold;
}
-->
</style>
</head>';
  
if ($safe_mode || $exec_time < 5998) 
{
	echo "<body onload=\"sfmform.submit();\">";
} 
else 
{
    echo "<body>";
}

// Language Support
if ($Spell_Config["Default_Language"] == "") 
{
    echo "Configuration file is missing language setting.<br>Please set \$Spell_Config[\"Default_Language\"] to your language in the <b>spell_config.php</b> file.";
    exit;
}

if (isset($_REQUEST["CL"])) 
{
	$CL = $_REQUEST["CL"];
}
else if (isset($HTTP_POST_VARS["CL"])) 
{
	$CL = $HTTP_POST_VARS["CL"];
}
else 
{
	$CL = 0;
}

if (!isset($Spell_Config["Languages_Supported"][$CL])) 
{
	$Current_Language = $Spell_Config["Default_Language"];
}
else 
{
	$Current_Language = $Spell_Config["Languages_Supported"][$CL];
}

include ("spell_".$Current_Language.".".$phpEx);
$valid_charlist = $Language_Character_List;

// Globalize variables
if (isset($_REQUEST['submit'])) 
{
	$update_config = $_REQUEST['submit'];
}
else if (isset($HTTP_POST_VARS['submit'])) 
{
	$update_config = $HTTP_POST_VARS['submit'];
}

if (isset($_REQUEST["Install_Dictionary"])) 
{
	$Install_Dictionary = $_REQUEST["Install_Dictionary"];
}
else if (isset($HTTP_POST_VARS["Install_Dictionary"])) 
{
	$Install_Dictionary = $HTTP_POST_VARS["Install_Dictionary"];
}

if (isset($_REQUEST["Clear_Dictionary"])) 
{
	$Clear_Dictionary = $_REQUEST["Clear_Dictionary"];
}
else if (isset($HTTP_POST_VARS["Clear_Dictionary"])) 
{
	$Clear_Dictionary = $HTTP_POST_VARS["Clear_Dictionary"];
}

if (isset($_REQUEST["New_Word_To_Add"])) 
{
	$New_Word_To_Add = $_REQUEST["New_Word_To_Add"];
}
else if (isset($HTTP_POST_VARS["New_Word_To_Add"])) 
{
	$New_Word_To_Add = $HTTP_POST_VARS["New_Word_To_Add"];
}

if (isset($_REQUEST["Offset"])) 
{
	$Offset = $_REQUEST["Offset"];
}
else if (isset($HTTP_POST_VARS["Offset"])) 
{
	$Offset = $HTTP_POST_VARS["Offset"];
}

if (isset($_REQUEST["WP"])) 
{
	$WP = $_REQUEST["WP"];
}
else if (isset($HTTP_POST_VARS["WP"])) 
{
	$WP = $HTTP_POST_VARS["WP"];
}

if (isset($_REQUEST["WA"])) 
{
	$WA = $_REQUEST["WA"];
}
else if (isset($HTTP_POST_VARS["WA"])) 
{
	$WA = $HTTP_POST_VARS["WA"];
}

if (isset($_REQUEST["SM"])) 
{
	$SM = $_REQUEST["SM"];
}
else if (isset($HTTP_POST_VARS["SM"])) 
{
	$SM = $HTTP_POST_VARS["SM"];
}

if (isset($Install_Dictionary) && $Install_Dictionary == "NONE") 
{
	unset($Install_Dictionary);
}

if (isset($update_config)) 
{
	$enable_spellcheck = (isset($HTTP_POST_VARS['enable_spellcheck'])) ? intval($HTTP_POST_VARS['enable_spellcheck']) : 0;
	
	$sql = "UPDATE " . CONFIG_TABLE . "
		SET config_value = $enable_spellcheck
		WHERE config_name = 'enable_spellcheck'";
	if( !$db->sql_query($sql) )
	{
		echo "Failed to update general configuration for enable_spellcheck";
	}

	// Remove cache file
	@unlink($phpbb_root_path . 'cache/config_board.'.$phpEx);
	
	echo '<meta http-equiv="refresh" content="0;url=' . append_sid('spell_admin.'.$phpEx) . '">';
	exit;
}
  
if (!isset($Install_Dictionary) && !isset($Clear_Dictionary) && !isset($New_Word_To_Add)) 
{

?>
	<script LANGUAGE="javascript">
	<!--
    Submitted=false;
    function submitform(link)
    {
    	if (Submitted) return (false);
       	link.value = "Procesessing...";
       	Submitted = true;
       	return (true);
	}
	// -->
  	</script>
    <?php echo $table . '<h1>' . $lang['Spellcheck'] . '</h1><p>' . $lang['Spell_explain'];
    	
	if ($safe_mode) 
	{	
    	echo "(Safe Mode - $exec_time secs)";
	} 
	else if ($exec_time < 5998) 
	{
    	echo "(Max time - $exec_time secs)";
    }
    
    $enabled_yes = ($board_config['enable_spellcheck']) ? ' checked="checked"' : '';
    $enabled_no = (!$board_config['enable_spellcheck']) ? ' checked="checked"' : '';
    
    ?>
    </p>

    <table cellpadding="4" cellspacing="1" align="center" width="100%" class="forumline"><form name="config" method="post">
    <tr>
    	<th colspan="2" class="thHead"><?php echo $lang['Spell_title']; ?></th>
    </tr>
	<tr>
    	<td class="row1" width="50%"><b><?php echo $lang['Spell_enable']; ?>:</b></td>
    	<td class="row2"><input type="radio" name="enable_spellcheck" value="1"<?php echo $enabled_yes; ?>> <?php echo $lang['Yes']; ?>&nbsp;&nbsp;<input type="radio" name="enable_spellcheck" value="0"<?php echo $enabled_no; ?>> <?php echo $lang['No']; ?></td>
    </tr>
     <tr>
    	<td colspan="2" class="catBottom" align="center"><input class="mainoption" name="submit" type="submit" value="<?php echo $lang['Submit']; ?>" /></td>
	</tr>
   </form></table>
    <br />
	
	<table cellpadding="4" cellspacing="1" align="center" width="100%" class="forumline"><form name="DictForm" method="post">
    <tr>
    	<th colspan="2" class="thHead"><?php echo $lang['Spell_import']; ?></th>
    </tr>
    <tr>
    	<td class="row1" width="50%"><b class="genmed"><?php echo $lang['Spell_dict']; ?>:</b></td>
        <td class="row2"><input type="hidden" name="formtype" value="1"><select name="Install_Dictionary">
        <?php
        if ($dir = @opendir('.')) 
        {
        	while (($file = @readdir($dir)) !== false) 
            {
            	$pos = strpos(strtolower($file), ".dic") ;
				if ($pos == true) 
				{
					echo "<option value='$file'>$file</option>";
				}
            }
            @closedir($dir);
		}
		?>
        </select></td>
	</tr>
    <tr>
    	<td class="row1"><b class="genmed"><?php echo $lang['Spell_lang']; ?>:</b></td>
        <td class="row2"><?php Do_Languages(); ?></td>
	</tr>
    <tr>
    	<td class="row1"><b class="genmed"><?php echo $lang['Spell_clear']; ?>:</b></td>
        <td class="row2"><input type="checkbox" name="Clear_Dictionary" /></td>
	</tr>
    <tr>
    	<td class="row1"><b class="genmed"><?php echo $lang['Spell_safe']; ?>:</b></td>
        <td class="row2"><input type="checkbox" name="SM" checked="checked" /></td>
	</tr>
    <tr>
    	<td colspan="2" class="catBottom" align="center"><input class="mainoption" type="submit" value="<?php echo $lang['Submit']; ?>" onclick="return(submitform(this));"></td>
	</tr>
    </form></table>
    <br />
   		
	<table cellpadding="4" cellspacing="1" align="center" width="100%" class="forumline"><form name="WordForm" method="post">
    <tr>
    	<th colspan="2" class="thHead"><?php echo $lang['Add_new_word']; ?></th>
	</tr>
    <tr>
    	<td class="row1" width="50%"><b class="genmed"><?php echo $lang['Word']; ?>:</b></td>
        <td class="row2"><input type="hidden" name="formtype" value="2"><input class="post" type="text" size="35" name="New_Word_To_Add" value="" /></td>
	</tr>
    <tr>
    	<td class="row1"><b class="genmed"><?php echo $lang['Spell_lang']; ?>:</b></td>
        <td class="row2"><?php Do_Languages(); ?></td>
	</tr>
    <tr>
    	<td colspan="2" class="catBottom" align="center"><input class="mainoption" type="submit" value="<?php echo $lang['Add_new_word']; ?>" /></td>
	</tr>
    </form></table>
<?php
} 
else 
{
    echo "<span style=\"font:8pt verdana\">";
}

$words_added = $words_processed = 0;

function Do_Languages()
{
	global $Spell_Config;
	
	if (sizeof($Spell_Config["Languages_Supported"]) == 1) 
	{
    	echo "<input type=\"hidden\" name=\"CL\" value=\"0\">";
    	echo $Spell_Config["Languages_Supported"][0];
  	}
  	else 
  	{
  		echo "<select name=\"CL\">";
    	for ($i=0; $i < sizeof($Spell_Config["Languages_Supported"]); $i++) 
    	{
      		echo "<option value=\"$i\">".$Spell_Config["Languages_Supported"][$i];
    	}
    	echo "</select>";
  	}
}

function Add_Word($word_to_add)
{
  	global $words_added, $words_processed, $safe_mode;

  	$word_to_add = strtolower($word_to_add);

  	if (DB_Check_Word($word_to_add)) 
  	{
  		return (false);
	}
	
  	$tr_word_to_add = Translate_Word($word_to_add);
  	$metacode = Word_Sound_Function($tr_word_to_add);

  	DB_Add_Word($word_to_add, $metacode);
  	$words_added++;
}

function Install_Dictionary($Dictionary, $Dictionary_Offset=0)
{
  	global $words_processed;
  	global $start_time, $safe_mode, $exec_time;

  	$last_time = 0;

  	// Create the Table
  	if ($Dictionary_Offset == 0) 
  	{
    	DB_Create_Table();
  	}

  	// Open the File
  	$FileSize = filesize($Dictionary);
  	$fp = @fopen($Dictionary, 'r');
  	if (!$fp) 
  	{
		message_die(CRITICAL_ERROR, "Unable to open dictionary file: ".$Dictionary);
  	}
  	if ($Dictionary_Offset != 0) 
  	{
  		@fseek($fp, $Dictionary_Offset);
  	}
  	while (!feof($fp)) 
  	{
    	$data = trim(@fgets($fp, 4096));
    	if ($data != "") 
    	{
      		$words_processed++;
      		Add_Word($data);
      		$end_time = time() - $start_time;
      		if ($end_time > $last_time) 
      		{
       			$loc = @ftell($fp);
       			$Percent = round(($loc / $FileSize)*100, 0);
      			echo "Processed: $words_processed... (".$Percent."%)<br>";
      			flush();
      		}
      		$last_time = $end_time;

      		if ($end_time > $exec_time) 
			{ 
        		$loc = @ftell($fp);
        		@fclose($fp);
        		SafeMode_Script($Dictionary, $loc);
      		}
    	}
  	}
  	@fclose($fp);
}

function SafeMode_Script($Dictionary, $Offset)
{
   	global $words_added, $words_processed, $SM, $CL;
   	
   	echo "<br /><b>Safe mode</b> is enabled or <b>Max execution time</b> is a hard coded value on this server.<br />The dictionary installation process will require you to continue several times to install all the words in this dictionary.</span></body>";
   	echo "<form name=\"sfmform\" method=\"post\">";
   	echo "<input type=\"hidden\" name=\"Install_Dictionary\" value=\"$Dictionary\">";
   	echo "<input type=\"hidden\" name=\"Offset\" value=\"$Offset\">";
   	echo "<input type=\"hidden\" name=\"WP\" value=\"$words_processed\">";
   	echo "<input type=\"hidden\" name=\"WA\" value=\"$words_added\">";
   	echo "<input type=\"hidden\" name=\"CL\" value=\"$CL\">";
   	if (isset($SM)) 
   	{
   		echo "<input type=\"hidden\" name=\"SM\" value=\"1\">";
   	}
   	echo "<center><input class=\"mainoption\" type=\"submit\" name=\"Continue\" value=\"Continue\"></center>";
   	echo "</form>";
   	echo "</html>";
   	exit;
}

function Clear_Dictionary()
{
  	DB_Drop_Table();
	echo 'Existing Table &amp; data has been removed from your database.<br /><br />';
}

// Main Routine
if (isset($New_Word_To_Add)) 
{
  	$New_Word_To_Add = stripslashes($New_Word_To_Add);
  	Add_Word($New_Word_To_Add);
	echo "<br /><b><font size=+1>Word Added ($Current_Language)....</font></b><br />";
  	echo "Click <a href=\"spell_admin.".$phpEx."\">Here</a> to install another language or another word.";
}

if (isset($Clear_Dictionary)) 
{
	Clear_Dictionary();
}

if (isset($Install_Dictionary) && $Install_Dictionary != "NONE") 
{
	if (isset($Offset)) 
	{
    	echo "<b>Continuing Installation of $Install_Dictionary...</b><br />";
    	if (isset($WP)) 
    	{
    		$words_processed = $WP;
    	}
    	if (isset($WA)) 
    	{
    		$words_added = $WA;
    	}
    	Install_Dictionary($Install_Dictionary, $Offset);
  	} 
  	else 
  	{
    	echo "<b>Installing $Install_Dictionary ($Current_Language)...</b><br />";
    	Install_Dictionary($Install_Dictionary);
  	}
  	
  	echo "<br /><b><font size=+1>Completed Installation of $Install_Dictionary...</font></b><br />";
	echo "Processed a total of <b>$words_processed</b> words, added a total of <b>$words_added</b>.<br />";
  	echo "<br />Please make sure you delete this file after all installations are complete!<br /><br />";
  	echo "Click <a href=\"spell_admin.".$phpEx."\">Here</a> to install another dictionary or another word.";
}

echo '</span></body></html>';

?>