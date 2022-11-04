<script language="JavaScript" type="text/javascript">
<!--
function checkRateForm() 
{
	if (document.rateform.rating.value == -1)
	{
		return false;
	}
	else
	{
		return true;
	}
}
// -->
</script>

<table width="100%" cellpadding="2" cellspacing="2">
<tr>
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> <a href="{U_LINK}" class="nav">{LINKS}</a>
	<!-- BEGIN navlinks -->
	-> <a href="{navlinks.U_VIEW_CAT}" class="nav">{navlinks.CAT_NAME}</a>
	<!-- END navlinks -->
	-> {FILE_NAME} -> {L_RATE}</td>
</tr>
</table>

<table width="100%" cellpadding="4" cellspacing="1" class="forumline"><form name="rateform" action="{S_RATE_ACTION}" method="post" onsubmit="return checkRateForm();">
<tr> 
	<th colspan="2" class="thHead">{L_RATE}</th>
</tr>
<tr> 
	<td class="row1" rowspan="2">{LINK_LOGO}</td>
	<td class="row1" width="100%"><span class="genmed">&nbsp;{L_RATING}: {RATING} ({FILE_VOTES} {L_VOTES})<br />{RATEINFO}</span></td>
</tr>
<tr>
	<td class="row1"><select name="rating">
		<option value="-1" selected>{L_RATE}</option>
		<!-- BEGIN rate_row -->
		<option value="{rate_row.POINT}">{rate_row.POINT}</option>
		<!-- END rate_row -->
	</select></td>
</tr>
<tr> 
	<td colspan="2" class="catBottom" align="center"><input type="hidden" name="action" value="rate"><input type="hidden" name="link_id" value="{ID}"><input class="mainoption" type="submit" value="{L_RATE}" name="submit" /></td>
</tr>
</form></table>
