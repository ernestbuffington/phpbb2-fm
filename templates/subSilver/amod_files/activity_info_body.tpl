<table class="forumline" width="100%" cellspacing="1" cellpadding="4" align="center"> 
<!-- BEGIN info_box -->
<tr> 
	<td class="catHead" colspan="3" align="center"><span class="cattitle">{info_box.L_INFO_TITLE}</span></td> 
</tr>
<tr>
	<th class="thCornerL" width="33%">{info_box.L_INFO_TITLE1}</th> 
	<th class="thTop" width="33%">{info_box.L_INFO_TITLE2}</th> 
	<th class="thCornerR" width="33%">{info_box.L_INFO_TITLE3}</th>    
</tr> 
<tr> 
	<td valign="top" width="33%" class="row1">
	<fieldset class="fieldset" style="margin: 0px 0px 0px 0px;">
	<legend>{info_box.L_INFO_TITLE1} {info_box.USERNAME}</legend>
	<span class="genmed">
	{info_box.FAVORITES_LINK}
	<br />
	{info_box.TOTAL_GAMES_LINK}
	<br /><br />
	{info_box.TOTAL_CHALLENGES_SENT}
	<br />
	{info_box.TOTAL_CHALLENGES_RECIEVED}
	<br />
	{info_box.TOTAL_COMMENTS_LEFT}
	<br /><br />
	{info_box.TOTAL_TROPHIES_HELD}
<!-- END info_box -->				
<!-- BEGIN personal_info_box -->
	<br /><br />
	{personal_info_box.LAST_GAME_PLAYED}
<!-- END personal_info_box -->
<!-- BEGIN info_box -->
	<br /><br />
	{info_box.TOTAL_TIME_IN_GAMES}
	<br /><br />
	</span>
	</fieldset>					
	</td>
   	<td valign="top" width="33%" class="row2" style="line-height: 150%">
	<fieldset class="fieldset" style="margin: 0px 0px 0px 0px;">			
	<legend>{info_box.L_NEWEST_TITLE}</legend>
	<center>{info_box.LAST_GAME_PLAYED}</center>
	</fieldset>
	<br />
	<fieldset class="fieldset" style="margin: 0px 0px 0px 0px;">				
	<legend>{info_box.TROPHY_GAME_1}</legend>
	<center>{info_box.TROPHY_GAME}<br />
	{info_box.TROPHY_GAME_2}
	<br />
	{info_box.TROPHY_GAME_3}</center>
	</fieldset>
	<br />
	<fieldset class="fieldset" style="margin: 0px 0px 0px 0px;">				
	<legend>{info_box.TROPHY_TOP_HOLDER}</legend>				
	<center>{info_box.TROPHY_TOP_HOLDER1}</center>
	</fieldset>
	</td>
   	<td valign="top" width="33%" class="row1">
	<fieldset class="fieldset" style="margin: 0px 0px 0px 0px;">				
	<legend>{info_box.L_INFO_TITLE3}</legend>				
	{info_box.TOTAL_GAMES_PLAYED}
	</fieldset>
	<br />
	<fieldset class="fieldset" style="margin: 0px 0px 0px 0px;">				
	<legend>{info_box.MOST_POPULAR_1}</legend>				
	{info_box.MOST_POPULAR_2}<br />{info_box.MOST_POPULAR_3}	
	</fieldset>
	<br />
	<fieldset class="fieldset" style="margin: 0px 0px 0px 0px;">				
	<legend>{info_box.LEAST_POPULAR_1}</legend>				
	{info_box.LEAST_POPULAR_2}<br />{info_box.LEAST_POPULAR_3}
	</fieldset>
	<br />
	</span>								
	</td>
</tr>
<!-- END info_box -->
<tr> 
	<td class="catBottom" colspan="3"><table width="100%" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<form>
		<td align="center" valign="middle" nowrap="nowrap" width="33%"><select onchange="if(options[selectedIndex].value)window.location.href=(options[selectedIndex].value)">
		<option selected value="">{D_DEFAULT}</option>			
		<!-- BEGIN drop -->						
		<option value="{drop.D_SELECT_1}">{drop.D_SELECT_2}</option>
		<!-- END drop -->						
		</select>
		<noscript><input type="submit" value="Go"></noscript>
		</td>
		</form>
		<td align="center" nowrap="nowrap" width="33%" valign="top"><img src="{RANDOM_IMAGE}" alt="" title="" /> <a href="{RANDOM_GAME}" class="nav">{RANDOM_LINK}</a> <img src="{RANDOM_IMAGE}" alt="" title="" /></td>
		<form>				
		<td align="center" nowrap="nowrap" width="33%" valign="top"><select onchange="if(options[selectedIndex].value)window.location.href=(options[selectedIndex].value)">
			<option selected value="">{C_DEFAULT}</option>
			<option value="{C_DEFAULT_ALL}">{C_DEFAULT_ALL_L}</option>
			<option value="{C_CAT_PAGE}">{L_CAT_PAGE}</option>
			<!-- BEGIN cat -->						
			<option value="{cat.C_SELECT_2}">{cat.C_SELECT_1}</option>
			<!-- END cat -->						
		</select>
		<noscript><input type="submit" value="Go"></noscript>
		</td>
		</form>										
	</tr>
	</table></td> 
</tr>	
</table>
<br />				
