<table width="100%" align="center" class="forumline" cellpadding="4" cellspacing="1"><form method="post" name="sub_rate" action="activity_rating.php{CAT_RATE}">	
<tr>
	<th class="thHead">{TITLE}</th>
</tr>
<tr>
	<td width="38%" class="row1"><b>{CHOICES}:</b></td>
	<td class="row2"><select name="rating">
		<option selected value="">{DEFAULT_RATE}</option>
		<option value="1">1</option>
		<option value="2">2</option>
		<option value="3">3</option>
		<option value="4">4</option>
		<option value="5">5</option>			
		<option value="6">6</option>
		<option value="7">7</option>
		<option value="8">8</option>
		<option value="9">9</option>
		<option value="10">10</option>
	</select></td>
</tr>
<tr>
	<td class="catBottom" align="center" colspan="2"><input type="hidden" name="mode" value="submit_rating"><input type="hidden" name="game" value="{GAME}"><input class="mainoption" type="submit" value="{SUBMIT}" onchange="document.sub_rate.submit()" /></td>	
</tr>
</form></table>		
<br />
<div align="center" class="copyright">Activity Mod Plus v1.1.0 &copy; 2003, {COPYRIGHT_YEAR} <a href="http://phpbb-amod.com" target="_blank" class="copyright">aUsTiN</a></div>
