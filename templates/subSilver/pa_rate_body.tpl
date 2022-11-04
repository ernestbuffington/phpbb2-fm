<script language="JavaScript" type="text/javascript">
<!--
function checkRateForm() {
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

<!-- INCLUDE pa_header.tpl -->

<table width="100%" cellpadding="2" cellspacing="2">
  <tr>
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> <a href="{U_DOWNLOAD_HOME}" class="nav">{DOWNLOAD}</a><!-- BEGIN navlinks --> -> <a href="{navlinks.U_VIEW_CAT}" class="nav">{navlinks.CAT_NAME}</a><!-- END navlinks --> -> <a href="{U_FILE_NAME}" class="nav">{FILE_NAME}</a> -> {L_RATE}</td>
  </tr>
</table>

<table width="100%" cellpadding="4" cellspacing="1" class="forumline"><form name="rateform" action="{S_RATE_ACTION}" method="post" onsubmit="return checkRateForm();">
  <tr> 
	<th colspan="2" class="thHead">{L_RATE}</th>
  </tr>
  <tr> 
	<td class="row1" width="90%"><span class="genmed">{RATEINFO}</span></td>
	<td class="row2"><select name="rating">
		<option value="-1" selected="selected">{L_RATE}</option>
		<option value="1">{L_R1}</option>
		<option value="2">{L_R2}</option>
		<option value="3">{L_R3}</option>
		<option value="4">{L_R4}</option>
		<option value="5">{L_R5}</option>
		<option value="6">{L_R6}</option>
		<option value="7">{L_R7}</option>
		<option value="8">{L_R8}</option>
		<option value="9">{L_R9}</option>
		<option value="10">{L_R10}</option>
	</select></td>
  </tr>
  <tr> 
	<td colspan="2" class="catBottom" align="center"><input type="hidden" name="action" value="rate"><input type="hidden" name="file_id" value="{ID}"><input class="liteoption" type="submit" value="{L_RATE}" name="submit" />&nbsp;</td>
  </tr>
</form></table>

<!-- INCLUDE pa_footer.tpl -->
