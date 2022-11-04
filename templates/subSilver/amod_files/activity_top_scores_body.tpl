<table width="100%" cellspacing="2" cellpadding="2" align="center">
<tr> 
	<td class="nav" valign="bottom"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> <a href="{U_ACTIVITY}" class="nav">{L_ACTIVITY}</a> -> <a href="{U_T_LINK}" class="nav">{L_T_LINK}</a></td>
	<!-- BEGIN admin -->
	<td align="right"><table align="right" class="bodyline" cellpadding="4" cellspacing="1" align="center">
	<tr>
		<form method="post" name="del_top" action="activity.php?page=trophy">	
		<td nowrap="nowrap" class="row1" align="right"><b>{admin.L_DELETE_SPECIFIC}</b> &nbsp;<select name="delete_score">
			<option selected value="" disabled="disabled">{admin.L_DEFAULT_ONE}</option>
	<!-- END admin -->				
			<!-- BEGIN admin_drop_one -->				
			<option value="{admin_drop_one.GAME_NAME}">{admin_drop_one.GAME_NAME}</option>
			<!-- END admin_drop_one -->				
	<!-- BEGIN admin -->		
		</select> &nbsp; 
		<input type="hidden" name="action" value="delete_specific_score"><input class="liteoption" type="submit" value="{admin.L_DELETE}" onchange="document.del_top.submit()" /></td>	
		</form>
	</tr>
	<tr>
		<form method="post" name="del_all" action="activity.php?page=trophy">			
		<td nowrap="nowrap" class="row1" align="right"><b>{admin.L_DELETE_ALL}</b> &nbsp;<input type="hidden" name="action" value="delete_all_scores"><input class="liteoption" type="submit" value="{admin.L_DELETE}" onchange="document.del_all.submit()" /></td>	
		</form>
	</tr>
	</table></td>		
	<!-- END admin -->
</tr>
</table>

<!-- BEGIN top_scores -->
<table class="forumline" align="center" cellpadding="4" cellspacing="1" width="100%">
<tr>
	<th class="thCornerL" nowrap="nowrap">{top_scores.HEADER_ONE}</th>
	<th class="thTop" nowrap="nowrap"">{top_scores.HEADER_TWO}</th>
	<th class="thTop" nowrap="nowrap">{top_scores.HEADER_THREE}</th>
	<th class="thTop" nowrap="nowrap">{top_scores.HEADER_FOUR}</th>
	<th class="thCornerR" nowrap="nowrap">{top_scores.HEADER_FIVE}</th>			
</tr>
<!-- END top_scores -->	
<!-- BEGIN top_scores_rows -->
<tr>	
	<td align="center" class="{top_scores_rows.ROW_CLASS}">{top_scores_rows.GAME_IMAGE}</td>
	<td class="{top_scores_rows.ROW_CLASS}" width="75%">{top_scores_rows.USER_SEARCH}</td>
	<td align="center" class="{top_scores_rows.ROW_CLASS}">{top_scores_rows.SCORE}</td>
	<td align="center" class="{top_scores_rows.ROW_CLASS}" nowrap="nowrap">{top_scores_rows.DATE}</td>
	<td align="center" class="{top_scores_rows.ROW_CLASS}" nowrap="nowrap">{top_scores_rows.PM_PROFILE}</td>					
</tr>
<!-- END top_scores_rows -->
<tr>
	<td colspan="5" class="catBottom">&nbsp;</td>
</tr>
</table>
<table width="100%" cellpadding="2" cellspacing="2" align="center">
<tr>
	<td class="nav">{PAGE_NUMBER}</td>
	<td class="nav" align="right">{PAGINATION}</td>
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

