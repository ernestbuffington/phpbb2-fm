<!-- BEGIN links_check -->			
<table width="100%" align="center" cellpadding="2" cellspacing="2">
<tr> 
	<td class="nav">{links_check.LINKS}</td>
</tr>
</table>											
<!-- END links_check -->
	
{ACTIVITY_INFO_SECTION}

{ACTIVITY_DAILY_SECTION}
	
{ACTIVITY_NEWEST_SECTION}

<!-- BEGIN games_on -->
<table class="forumline" width="100%" cellspacing="1" cellpadding="4" align="center"> 
<tr> 
	<th class="thCornerL" width="20%">{L_GAMES}</th>
	<th class="thTop" width="15%">{L_T_HOLDER}</th>   
	<th class="thTop" width="20%">{L_STATS}</th>  	  
	<th class="thCornerR" width="45%">{L_INFO}</th>      
</tr>
<!-- BEGIN game -->
<tr>	
	<td class="{games_on.game.ROW_CLASS}" width="20%">
	<fieldset class="fieldset" style="margin: 0px 0px 0px 0px;">
	<legend><b>{games_on.game.PROPER_NAME}</b></legend>
	<table align="center" width="100%" valign="top">
	<tr>
		<td width="50%">{games_on.game.NEW_I_LINK}{games_on.game.IMAGE_LINK}</a></td>
		<td align="center" width="50%">{games_on.game.KEYBOARD}{games_on.game.MOUSE}</td>		
	</tr>
	<tr>
		<td colspan="2"><span class="genmed">{games_on.game.LINKS}{games_on.game.DOWNLOAD_LINK}</span></td>		
	</tr>	
	</table>					
	</fieldset>			
	</td>
	<td class="{games_on.game.ROW_CLASS}" width="15%"><span class="genmed">{games_on.game.TROPHY_IMG}  {games_on.game.TOP_PLAYER}<br />{games_on.game.TOP_SCORE}<br /><br />{games_on.game.RUNNER_IMG}  {games_on.game.BEST_PLAYER}<br />{games_on.game.BEST_SCORE}<br /><br />{games_on.game.FAVORITE_GAME}</span></td>
	<td class="{games_on.game.ROW_CLASS}" width="20%"><span class="genmed">{games_on.game.SEPERATOR}<a href="{games_on.game.COMMENTS}">{games_on.game.L_COMMENTS}</a>{games_on.game.CHALLENGE}{games_on.game.LIST}<br />{games_on.game.SEPERATOR}<a href="{games_on.game.STATS}">{games_on.game.INFO}</a><br />{games_on.game.GAMES_PLAYED} {games_on.game.I_PLAYED}<br /><center>{games_on.game.POP_PIC}</center></span></td>				
	<td class="{games_on.game.ROW_CLASS}" width="45%">
	<fieldset class="fieldset" style="margin: 0px 0px 0px 0px;">
	<legend>{games_on.game.DESC2}</legend>
	{games_on.game.DESC}
	</fieldset>
	<br />
	<fieldset class="fieldset" style="margin: 0px 0px 0px 0px;">
	<legend>{games_on.game.RATING_TITLE}</legend>
	<br />{games_on.game.SEPERATOR}{games_on.game.RATING_SENT}  {games_on.game.RATING_SUBMIT}  {games_on.game.RATING_IMAGE}<br />
	</fieldset>				
	</td>
</tr>	
<!-- END game -->
<tr> 
	<form method="post" action="{S_MODE_ACTION}">
	<td class="catBottom" colspan="4" align="center"><span class="genmed">{L_SELECT_SORT_METHOD}:&nbsp;{S_MODE_SELECT}&nbsp;&nbsp;{L_ORDER}:&nbsp;{S_ORDER_SELECT} <input type="submit" name="submit" value="{L_SORT}" class="liteoption" /></span></td>
	</form>
</tr>
</table>
<table width="100%" cellspacing="2" cellpadding="2">
<tr> 
	<td class="nav">{PAGE_NUMBER}</td>
	<td align="right" class="nav">{PAGINATION}</td>
</tr>
</table>
<!-- END games_on -->

{ACTIVITY_ONLINE_SECTION}

<br />
<table width="100%" cellspacing="2" align="center"> 
<tr> 
	<td align="right" valign="middle" nowrap="nowrap">{JUMPBOX}</td> 
</tr> 
</table> 
<br />
<div align="center" class="copyright">Activity Mod Plus v1.1.0 &copy; 2003, {COPYRIGHT_YEAR} <a href="http://phpbb-amod.com" target="_blank" class="copyright">aUsTiN</a></div>
{GAMELIB_LINK}