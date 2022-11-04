<table width="100%" cellpadding="2" cellspacing="2" align="center">
<tr>
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> <a href="{U_ACTIVITY}" class="nav">{L_ACTIVITY}</a></td>
</tr>
</table>

<!-- BEGIN search_switch -->
<table class="forumline" width="100%" cellspacing="1" cellpadding="4" align="center"><form name="game_search" action="activity.php?page=search" method="post">
<tr> 
	<th class="thHead" colspan="2">{SEARCH_TITLE}</th> 
</tr>
<tr>
	<td class="row1" width="38%"><b>{L_TOP_LEFT}:</b></td>
	<td class="row2"><select name="top_left">	
		<option selected value="flash">{L_TL_OPTION_1}</option>
		<option value="glib">{L_TL_OPTION_2}</option>				
	</select></td>
</tr>
<tr>
	<td class="row1"><b>{L_TOP_RIGHT}:</b></td> 		
	<td class="row2"><select name="top_right">	
		<option value="desc">{L_TR_OPTION_1}</option>
		<option selected value="name">{L_TR_OPTION_2}</option>				
		<option value="reverse">{L_TR_OPTION_3}</option>								
	</select></td>
</tr>
<tr>
	<td class="row1"><b>{L_WILDCARD}</b></td>
	<td class="row2"><input type="checkbox" name="wildcard" /></td> 		
</tr>
<tr> 
	<td class="row1"><b>{L_QUERY}:</b></td>
	<td class="row2"><input type="text" name="query" size="20" value="" class="post" /></td> 		
</tr>
<tr>
	<td class="catBottom" align="center" colspan="2""><input type="hidden" name="mode" value="search"><input type="submit" class="liteoption" value="{L_SEARCH}" onchange="game_search.submit()" /></td>
</tr>			
</form></table>
<br />
<!-- END search_switch -->

<table class="forumline" width="100%" cellspacing="1" cellpadding="4" align="center">
<!-- BEGIN search_results --> 
<tr> 
	<td class="{search_results.ROW_CLASS}" width="20%" align="center"><span class="genmed">{search_results.IMAGE}</span></td> 
	<td class="{search_results.ROW_CLASS}" width="30%"><span class="genmed">{search_results.NAME}</span></td> 		
	<td class="{search_results.ROW_CLASS}" width="50%"><span class="genmed">{search_results.DESC}</span></td> 		
</tr>
<!-- END search_results -->
</table>
<br />
<table width="100%" cellspacing="2" align="center"> 
<tr> 
	<td align="right" valign="middle" nowrap="nowrap">{JUMPBOX}</td> 
</tr> 
</table> 
<br />
<div align="center" class="copyright">Activity Mod Plus v1.1.0 &copy; 2003, {COPYRIGHT_YEAR} <a href="http://phpbb-amod.com" target="_blank" class="copyright">aUsTiN</a></div>
