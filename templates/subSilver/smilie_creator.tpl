<script language="Javascript" type="text/javascript">
<!--
function makeschild()
{
	var text = document.schilderstellung.schildtext.value;
	var color = document.schilderstellung.color.value;
	var shadowcolor = document.schilderstellung.shadowcolor.value;
	var shieldshadow = document.schilderstellung.shieldshadow.value;

	{SMILIES_JS}

	if(text){
		if(smilie == "standard") var text2form = "[schild=standard fontcolor="+color+" shadowcolor="+shadowcolor+" shieldshadow="+shieldshadow+"]"+text+"[/schild]";
		else var text2form = "[schild="+smilie+" fontcolor="+color+" shadowcolor="+shadowcolor+" shieldshadow="+shieldshadow+"]"+text+"[/schild]";

		opener.document.forms['post'].message.value += text2form;
		if(!confirm("Would you like to insert an Emoticon into this post?")){
			window.close();
			 opener.document.forms['post'].message.focus();
		}else{
                         document.schilderstellung.reset();
		}
	}else{
		alert("Can not create emoticon without any text!");
	}
}
//-->
</script>

<table cellpadding="4" cellspacing="1" width="100%" class="forumline" align="center"><form name="schilderstellung">
  <tr>
        <th colspan="2" class="thHead">{L_SMILIE_CREATOR}</th>
  </tr>
  <tr>
	<td class="row1"><b class="gen">{L_SHIELDTEXT}:</b></td>
	<td class="row2"><input type="text" name="schildtext" class="post" size="30" maxlength="396" /></td>
  </tr>
  <tr>
	<td class="row1"><b class="gen">{L_FONTCOLOR}:</b></td>
	<td class="row2"><select name="color">
		<option style="color:black" value="C0C0C0">{L_COLOR_DEFAULT}</option>
		<option style="color:coral" value="FF7F50">{L_COLOR_CORAL}</option>
		<option style="color:crimson" value="DC143C">{L_COLOR_CRIMSON}</option>
		<option style="color:tomato" value="FF6347">{L_COLOR_TOMATO}</option>
		<option style="color:seagreen" value="238E68">{L_COLOR_SEA_GREEN}</option>
		<option style="color:darkorchid" value="9932CC">{L_COLOR_DARK_ORCHID}</option>
		<option style="color:darkred" value="8B0000">{L_COLOR_DARK_RED}</option>
		<option style="color:red" value="FF0000">{L_COLOR_RED}</option>
		<option style="color:orange" value="FFA500">{L_COLOR_ORANGE}</option>
		<option style="color:gold" value="FFD700">{L_COLOR_GOLD}</option>
		<option style="color:chocolate" value="D2691E">{L_COLOR_CHOCOLATE}</option>
		<option style="color:brown" value="A52A2A">{L_COLOR_BROWN}</option>
		<option style="color:yellow" value="FFFF00">{L_COLOR_YELLOW}</option>
		<option style="color:green" value="008000">{L_COLOR_GREEN}</option>
		<option style="color:darkgreen" value="006400">{L_COLOR_DARKGREEN}</option>
		<option style="color:olive" value="808000">{L_COLOR_OLIVE}</option>
		<option style="color:cyan" value="00FFFF">{L_COLOR_CYAN}</option>
		<option style="color:deepskyblue" value="00BFFF">{L_COLOR_DEEPSKYBLUE}</option>
		<option style="color:cadetblue" value="5F9EA0">{L_COLOR_CADET_BLUE}</option>
		<option style="color:blue" value="0000FF">{L_COLOR_BLUE}</option>
		<option style="color:darkblue" value="00008B">{L_COLOR_DARK_BLUE}</option>
		<option style="color:midnightblue" value="191970">{L_COLOR_MIDNIGHTBLUE}</option>
		<option style="color:indigo" value="4B0082">{L_COLOR_INDIGO}</option>
		<option style="color:violet" value="EE82EE">{L_COLOR_VIOLET}</option>
		<option style="color:white" value="FFFFFF">{L_COLOR_WHITE}</option>
		<option style="color:black" value="000000">{L_COLOR_BLACK}</option>
	</select></td>
  </tr>
  <tr>
	<td class="row1"><b class="gen">{L_SHADOWCOLOR}:</b></td>
	<td class="row2"><select name="shadowcolor">
		<option style="color:black" value="C0C0C0">{L_COLOR_DEFAULT}</option>
		<option style="color:coral" value="FF7F50">{L_COLOR_CORAL}</option>
		<option style="color:crimson" value="DC143C">{L_COLOR_CRIMSON}</option>
		<option style="color:tomato" value="FF6347">{L_COLOR_TOMATO}</option>
		<option style="color:seagreen" value="238E68">{L_COLOR_SEA_GREEN}</option>
		<option style="color:darkorchid" value="9932CC">{L_COLOR_DARK_ORCHID}</option>
		<option style="color:darkred" value="8B0000">{L_COLOR_DARK_RED}</option>
		<option style="color:red" value="FF0000">{L_COLOR_RED}</option>
		<option style="color:orange" value="FFA500">{L_COLOR_ORANGE}</option>
		<option style="color:gold" value="FFD700">{L_COLOR_GOLD}</option>
		<option style="color:chocolate" value="D2691E">{L_COLOR_CHOCOLATE}</option>
		<option style="color:brown" value="A52A2A">{L_COLOR_BROWN}</option>
		<option style="color:yellow" value="FFFF00">{L_COLOR_YELLOW}</option>
		<option style="color:green" value="00800">{L_COLOR_GREEN}</option>
		<option style="color:darkgreen" value="006400">{L_COLOR_DARKGREEN}</option>
		<option style="color:olive" value="808000">{L_COLOR_OLIVE}</option>
		<option style="color:cyan" value="00FFFF">{L_COLOR_CYAN}</option>
		<option style="color:deepskyblue" value="00BFFF">{L_COLOR_DEEPSKYBLUE}</option>
		<option style="color:cadetblue" value="5F9EA0">{L_COLOR_CADET_BLUE}</option>
		<option style="color:blue" value="0000FF">{L_COLOR_BLUE}</option>
		<option style="color:darkblue" value="00008B">{L_COLOR_DARK_BLUE}</option>
		<option style="color:midnightblue" value="191970">{L_COLOR_MIDNIGHTBLUE}</option>
		<option style="color:indigo" value="4B0082">{L_COLOR_INDIGO}</option>
		<option style="color:violet" value="EE82EE">{L_COLOR_VIOLET}</option>
		<option style="color:white" value="FFFFFF">{L_COLOR_WHITE}</option>
		<option style="color:black" value="000000">{L_COLOR_BLACK}</option>
	</select></td>
  </tr>
  <tr>
	<td class="row1"><b class="gen">{L_SHIELDSHADOW}:</b></td>
	<td class="row2"><select name="shieldshadow">
           	<option value="1" title="{L_SHIELDSHADOW_ON}">{L_SHIELDSHADOW_ON}</option>
		<option value="0" title="{L_SHIELDSHADOW_OFF}">{L_SHIELDSHADOW_OFF}</option>
	</select></td>
  </tr>
  <tr>
	<td valign="top" class="row1"><b class="gen">{L_SMILIECHOOSER}:</b></td>
	<td class="row2"><table>
	  <tr>
		{SMILIES_WAHL}
	  </tr>
	  <tr>
		<td colspan="5"><input type="radio" name="smilie" value="random" checked="checked" /> {L_RANDOM_SMILIE}</td>
	  </tr>
	  <tr>
		<td colspan="5"><input type="radio" name="smilie" value="standard" /> {L_DEFAULT_SMILIE}</td>
	  </tr>
	</table></td>
  </tr>
  <tr>
	<td class="catBottom" align="center" colspan="2"><input type="button" class="mainoption" value="{L_CREATE_SMILIE}" onClick="makeschild()" />&nbsp;&nbsp;<input type="button" class="liteoption" value="{L_STOP_CREATING}" onClick="window.close()" /></td>
  </tr>
</form></table>
