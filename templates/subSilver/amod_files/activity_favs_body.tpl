<table align="center" width="100%" cellpadding="2" cellspacing="2">
<tr>
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> <a href="{U_ACTIVITY}" class="nav">{L_ACTIVITY}</a></td>
</tr>
</table>

<table class="forumline" width="100%" cellspacing="1" cellpadding="4" align="center"> 
<tr> 
	<th class="thCornerL" width="20%">{L_GAMES}</th>
      	<th class="thTop" width="15%">{L_T_HOLDER}</th>   
      	<th class="thTop" width="20%">{L_STATS}</th>  	  
      	<th class="thCornerR" width="45%">{L_INFO}</th>      
</tr>
<!-- BEGIN game -->
<tr>
	<td class="{game.ROW_CLASS}" width="20%">
	<fieldset class="fieldset" style="margin: 0px 0px 0px 0px;">
	<legend><b>{game.PROPER_NAME}</b></legend>			
	<table align="center" width="100%" valign="top">
	<tr>
		<td width="50%">{game.NEW_I_LINK}{game.IMAGE_LINK}</a></td>
		<td align="center" width="50%">{game.KEYBOARD}{game.MOUSE}</td>		
	</tr>
	<tr>
		<td colspan="2"><span class="genmed">{game.LINKS}{game.DOWNLOAD_LINK}</span></td>		
	</tr>	
	</table>					
	</fieldset>
	</td>
	<td class="{game.ROW_CLASS}" width="15%"><span class="genmed"><img src="{game.TROPHY_LINK}" alt="" title="" /> {game.TOP_PLAYER}<br />{game.TOP_SCORE}</span></td>
	<td class="{game.ROW_CLASS}" width="20%"><span class="genmed"><b>{game.SEPERATOR}</b> {game.LIST}<br /><b>{game.SEPERATOR}</b> <a href="{game.STATS}">{game.INFO}</a><br /><b>{game.SEPERATOR}</b> {game.GAMES_PLAYED}<b>{game.I_PLAYED}<br /><center>{game.POP_PIC}</center></span></td>		
	<td class="{game.ROW_CLASS}" width="45%">
	<fieldset class="fieldset" style="margin: 0px 0px 0px 0px;">
	<legend>{game.DESC2}</legend>
	{game.DESC}
	</fieldset>				
	</td>
</tr>	
<!-- END game -->
<tr>
	<td class="catBottom" colspan="4">&nbsp;</td>
</tr>
</table>
<br />
<table width="100%" cellspacing="2" align="center"> 
<tr> 
	<td align="right" valign="middle" nowrap="nowrap">{JUMPBOX}</td> 
</tr> 
</table> 
<br />
<div align="center" class="copyright">Activity Mod Plus v1.1.0 &copy; 2003, {COPYRIGHT_YEAR} <a href="http://phpbb-amod.com" target="_blank" class="copyright">aUsTiN</a></div>
