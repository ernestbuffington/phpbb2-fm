
<table width="100%" cellspacing="2" cellpadding="2" align="center">
<tr>
	<td class="nav"><a class="nav" href="{U_INDEX}">{L_INDEX}</a></td>
</tr>
</table>

<table class="forumline" width="100%" cellspacing="1" cellpadding="4">
<tr>
	<th class="thHead">{SITENAME} - {L_REGISTRATION}</th>
</tr>
<tr>
	<td class="row1" align="center"><table width="80%" cellspacing="2" cellpadding="2" align="center">
	<tr>
		<td><span class="genmed"><br />{L_AGREEMENT}<br /><br /></span></td>
	</tr>
	<tr>
		<form action="{U_AGREE_OVER13}" method="post" name="form1"> 
		<td align="center"><input class="liteoption" name="Submit" type="submit" id="Submit" value="{L_AGREE_OVER_13}" /></td>
		</form>
	</tr>
	<!-- BEGIN switch_coppa_on -->
	<tr>
		<form action="{switch_coppa_on.U_AGREE_UNDER13}" method="post" name="form2">
		<td align="center"><input class="liteoption" name="Submit" type="submit" id="Submit" value="{switch_coppa_on.L_AGREE_UNDER_13}" /></td>
		</form>
	</tr>
	<!-- END switch_coppa_on -->
	<tr>
		<form name="form3" method="post" action="{U_INDEX}"> 
		<td align="center"><input class="liteoption" type="submit" name="Submit" value="{L_DO_NOT_AGREE}" /></td>
		</form> 
	</tr>
	</table></td>
</tr>
<tr>
	<td class="catBottom">&nbsp;</td>
</tr>
</table>
