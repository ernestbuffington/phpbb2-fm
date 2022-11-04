<table align="center" width="100%" cellpadding="2" cellspacing="2">
<tr>
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> <a href="{U_ACTIVITY}" class="nav">{L_ACTIVITY}</a> -> <a href="activity_char.php" class="nav">{L_LINK}</a></td>
</tr>
</table>

<table width="100%" align="center" class="forumline" cellpadding="4" cellspacing="1"><form name="shop_settings" method="post" action="{RETURN}">
<tr>
	<td class="catHead" colspan="3" align="center"><span class="cattitle">{L_OPTIONS}</span></td>
</tr>
<tr>
	<th class="thSides" colspan="3">{L_OPTIONS_NAME}</th>
</tr>
<tr>
	<td width="25%" align="center" class="catLeft"><span class="cattitle">{L_EFFECT}</span></td>
	<td align="center" class="cat"><span class="cattitle">{L_COLOR}</span></td>
	<td width="25%"  align="center" class="catRight"><span class="cattitle">{L_COST}</span></td>
</tr>					
<tr>
	<td class="row1"><input type="checkbox" name="color_name" {NAME_COLOR}> {L_EFFECTS_COLOR}</td>
	<td align="center" class="row1"><select name="color_color_name">
		<option selected value="{NAME_COLOR_V}" class="post">{NAME_COLOR_S}</option>
		<option value="blue" class="post">{L_COLOR_BLUE}</option>
		<option value="green" class="post">{L_COLOR_GREEN}</option>
		<option value="black" class="post">{L_COLOR_BLACK}</option>
		<option value="white" class="post">{L_COLOR_WHITE}</option>
		<option value="yellow" class="post">{L_COLOR_YELLOW}</option>
		<option value="red" class="post">{L_COLOR_RED}</option>
		<option value="violet" class="post">{L_COLOR_VIOLET}</option>
		<option value="cyan" class="post">{L_COLOR_CYAN}</option>																								
	</select></td>
	<td class="row2">{NAME_COST_ONE}</td>				
</tr>	
<tr>
	<td class="row1"><input type="checkbox" name="shadow_name" {NAME_SHADOW}> {L_EFFECTS_SHADOW}</td>
	<td align="center" class="row1"><select name="shadow_color_name">
		<option selected value="{NAME_SHADOW_V}" class="post">{NAME_SHADOW_S}</option>
		<option value="blue" class="post">{L_COLOR_BLUE}</option>
		<option value="green" class="post">{L_COLOR_GREEN}</option>
		<option value="black" class="post">{L_COLOR_BLACK}</option>
		<option value="white" class="post">{L_COLOR_WHITE}</option>
		<option value="yellow" class="post">{L_COLOR_YELLOW}</option>
		<option value="red" class="post">{L_COLOR_RED}</option>
		<option value="violet" class="post">{L_COLOR_VIOLET}</option>
		<option value="cyan" class="post">{L_COLOR_CYAN}</option>																								
	</select></td>
	<td class="row2">{NAME_COST_TWO}</td>				
</tr>
<tr>
	<td class="row1"><input type="checkbox" name="glow_name" {NAME_GLOW}> {L_EFFECTS_GLOW}</td>
	<td align="center" class="row1"><select name="glow_color_name">
		<option selected value="{NAME_GLOW_V}" class="post">{NAME_GLOW_S}</option>
		<option value="blue" class="post">{L_COLOR_BLUE}</option>
		<option value="green" class="post">{L_COLOR_GREEN}</option>
		<option value="black" class="post">{L_COLOR_BLACK}</option>
		<option value="white" class="post">{L_COLOR_WHITE}</option>
		<option value="yellow" class="post">{L_COLOR_YELLOW}</option>
		<option value="red" class="post">{L_COLOR_RED}</option>
		<option value="violet" class="post">{L_COLOR_VIOLET}</option>
		<option value="cyan" class="post">{L_COLOR_CYAN}</option>																								
	</select></td>
	<td class="row2">{NAME_COST_THREE}</td>				
</tr>
<tr>
	<td class="row1" colspan="2"><input type="checkbox" name="bold_name" {NAME_BOLD}> {L_EFFECTS_BOLD}</td>
	<td class="row2">{NAME_COST_FOUR}</td>				
</tr>
<tr>
	<td class="row1" colspan="2"><input type="checkbox" name="italic_name" {NAME_ITALIC}> {L_EFFECTS_ITALIC}</td>
	<td class="row2">{NAME_COST_FIVE}</td>				
</tr>
<tr>
	<td class="row1" colspan="2"><input type="checkbox" name="underline_name" {NAME_UNDERLINE}> {L_EFFECTS_UNDERLINE}</td>
	<td class="row2">{NAME_COST_SIX}</td>				
</tr>				
<tr>
	<th class="thSides" colspan="3">{L_OPTIONS_SAYING}</th>
</tr>
<tr>
	<td align="center" class="catLeft"><span class="cattitle">{L_EFFECT}</span></td>
	<td align="center" class="cat"><span class="cattitle">{L_COLOR}</span></td>
	<td align="center" class="catRight"><span class="cattitle">{L_COST}</span></td>
</tr>	
<tr>
	<td class="row1"><input type="checkbox" name="color_saying" {SAYING_COLOR}> {L_EFFECTS_COLOR}</td>
	<td align="center" class="row1"><select name="color_color_saying">
		<option selected value="{SAYING_COLOR_V}" class="post">{SAYING_COLOR_S}</option>
		<option value="blue" class="post">{L_COLOR_BLUE}</option>
		<option value="green" class="post">{L_COLOR_GREEN}</option>
		<option value="black" class="post">{L_COLOR_BLACK}</option>
		<option value="white" class="post">{L_COLOR_WHITE}</option>
		<option value="yellow" class="post">{L_COLOR_YELLOW}</option>
		<option value="red" class="post">{L_COLOR_RED}</option>
		<option value="violet" class="post">{L_COLOR_VIOLET}</option>
		<option value="cyan" class="post">{L_COLOR_CYAN}</option>																								
	</select></td>
	<td class="row2">{SAYING_COST_ONE}</td>				
</tr>	
<tr>
	<td class="row1"><input type="checkbox" name="shadow_saying" {SAYING_SHADOW}> {L_EFFECTS_SHADOW}</td>
	<td align="center" class="row1"><select name="shadow_color_saying">
		<option selected value="{SAYING_SHADOW_V}" class="post">{SAYING_SHADOW_S}</option>
		<option value="blue" class="post">{L_COLOR_BLUE}</option>
		<option value="green" class="post">{L_COLOR_GREEN}</option>
		<option value="black" class="post">{L_COLOR_BLACK}</option>
		<option value="white" class="post">{L_COLOR_WHITE}</option>
		<option value="yellow" class="post">{L_COLOR_YELLOW}</option>
		<option value="red" class="post">{L_COLOR_RED}</option>
		<option value="violet" class="post">{L_COLOR_VIOLET}</option>
		<option value="cyan" class="post">{L_COLOR_CYAN}</option>																								
	</select></td>
	<td class="row2">{SAYING_COST_TWO}</td>				
</tr>
<tr>
	<td class="row1"><input type="checkbox" name="glow_saying" {SAYING_GLOW}> {L_EFFECTS_GLOW}</td>
	<td align="center" class="row1"><select name="glow_color_saying">
		<option selected value="{SAYING_GLOW_V}" class="post">{SAYING_GLOW_S}</option>
		<option value="blue" class="post">{L_COLOR_BLUE}</option>
		<option value="green" class="post">{L_COLOR_GREEN}</option>
		<option value="black" class="post">{L_COLOR_BLACK}</option>
		<option value="white" class="post">{L_COLOR_WHITE}</option>
		<option value="yellow" class="post">{L_COLOR_YELLOW}</option>
		<option value="red" class="post">{L_COLOR_RED}</option>
		<option value="violet" class="post">{L_COLOR_VIOLET}</option>
		<option value="cyan" class="post">{L_COLOR_CYAN}</option>																								
	</select></td>
	<td class="row2">{SAYING_COST_THREE}</td>				
</tr>
<tr>
	<td class="row1" colspan="2"><input type="checkbox" name="bold_saying" {SAYING_BOLD}> {L_EFFECTS_BOLD}</td>
	<td class="row2">{SAYING_COST_FOUR}</td>				
</tr>
<tr>
	<td class="row1" colspan="2"><input type="checkbox" name="italic_saying" {SAYING_ITALIC}> {L_EFFECTS_ITALIC}</td>
	<td class="row2">{SAYING_COST_FIVE}</td>				
</tr>
<tr>
	<td class="row1" colspan="2"><input type="checkbox" name="underline_saying" {SAYING_UNDERLINE}> {L_EFFECTS_UNDERLINE}</td>
	<td class="row2">{SAYING_COST_SIX}</td>				
</tr>		
<tr>
	<th class="thSides" colspan="3">{L_OPTIONS_TITLE}</th>
</tr>
<tr>
	<td class="catLeft" align="center"><span class="cattitle">{L_EFFECT}</span></td>
	<td align="center" class="cat"><span class="cattitle">{L_COLOR}</span></td>
	<td align="center" class="catRight"><span class="cattitle">{L_COST}</span></td>
</tr>	
<tr>
	<td class="row1"><input type="checkbox" name="color_title" {TITLE_COLOR}> {L_EFFECTS_COLOR}</td>
	<td align="center" class="row1"><select name="color_color_title">
		<option selected value="{TITLE_COLOR_V}" class="post">{TITLE_COLOR_S}</option>
		<option value="blue" class="post">{L_COLOR_BLUE}</option>
		<option value="green" class="post">{L_COLOR_GREEN}</option>
		<option value="black" class="post">{L_COLOR_BLACK}</option>
		<option value="white" class="post">{L_COLOR_WHITE}</option>
		<option value="yellow" class="post">{L_COLOR_YELLOW}</option>
		<option value="red" class="post">{L_COLOR_RED}</option>
		<option value="violet" class="post">{L_COLOR_VIOLET}</option>
		<option value="cyan" class="post">{L_COLOR_CYAN}</option>																								
	</select></td>
	<td class="row2">{TITLE_COST_ONE}</td>		
</tr>		
<tr>
	<td class="row1"><input type="checkbox" name="shadow_title" {TITLE_SHADOW}> {L_EFFECTS_SHADOW}</td>
	<td align="center" class="row1"><select name="shadow_color_title">
		<option selected value="{TITLE_SHADOW_V}" class="post">{TITLE_SHADOW_S}</option>
		<option value="blue" class="post">{L_COLOR_BLUE}</option>
		<option value="green" class="post">{L_COLOR_GREEN}</option>
		<option value="black" class="post">{L_COLOR_BLACK}</option>
		<option value="white" class="post">{L_COLOR_WHITE}</option>
		<option value="yellow" class="post">{L_COLOR_YELLOW}</option>
		<option value="red" class="post">{L_COLOR_RED}</option>
		<option value="violet" class="post">{L_COLOR_VIOLET}</option>
		<option value="cyan" class="post">{L_COLOR_CYAN}</option>																								
	</select></td>
	<td class="row2">{TITLE_COST_TWO}</td>				
</tr>
<tr>
	<td class="row1"><input type="checkbox" name="glow_title" {TITLE_GLOW}> {L_EFFECTS_GLOW}</td>
	<td align="center" class="row1"><select name="glow_color_title">
		<option selected value="{TITLE_GLOW_V}" class="post">{TITLE_GLOW_S}</option>
		<option value="blue" class="post">{L_COLOR_BLUE}</option>
		<option value="green" class="post">{L_COLOR_GREEN}</option>
		<option value="black" class="post">{L_COLOR_BLACK}</option>
		<option value="white" class="post">{L_COLOR_WHITE}</option>
		<option value="yellow" class="post">{L_COLOR_YELLOW}</option>
		<option value="red" class="post">{L_COLOR_RED}</option>
		<option value="violet" class="post">{L_COLOR_VIOLET}</option>
		<option value="cyan" class="post">{L_COLOR_CYAN}</option>																								
	</select></td>
	<td class="row2">{TITLE_COST_THREE}</td>				
</tr>
<tr>
	<td class="row1" colspan="2"><input type="checkbox" name="bold_title" {TITLE_BOLD}> {L_EFFECTS_BOLD}</td>
	<td class="row2">{TITLE_COST_FOUR}</td>				
</tr>
<tr>
	<td class="row1" colspan="2"><input type="checkbox" name="italic_title" {TITLE_ITALIC}> {L_EFFECTS_ITALIC}</td>
	<td class="row2">{TITLE_COST_FIVE}</td>				
</tr>
<tr>
	<td class="row1" colspan="2"><input type="checkbox" name="underline_title" {TITLE_UNDERLINE}> {L_EFFECTS_UNDERLINE}</td>
	<td class="row2">{TITLE_COST_SIX}</td>				
</tr>
<tr>
	<td colspan="3" align="center" class="catBottom"><input type="hidden" name="action" value="save_settings"><input type="submit" value="{L_SUBMIT}" onclick="document.shop_settings.submit()" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>	
</form></table>
<br />
<table width="100%" cellspacing="2" align="center"> 
<tr> 
	<td align="right" valign="middle" nowrap="nowrap">{JUMPBOX}</td> 
</tr> 
</table> 
<br />
<div align="center" class="copyright">Activity Mod Plus v1.1.0 &copy; 2003, {COPYRIGHT_YEAR} <a href="http://phpbb-amod.com" target="_blank" class="copyright">aUsTiN</a></div>

