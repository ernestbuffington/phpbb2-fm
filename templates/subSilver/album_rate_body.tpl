<script language="JavaScript" type="text/javascript">
<!--
function checkRateForm() {
	if (document.rateform.rate.value == -1)
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

<form name="rateform" action="{S_ALBUM_ACTION}" method="post" onsubmit="return checkRateForm();">
<table width="100%" cellspacing="2" cellpadding="2">
  <tr>
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> <a class="nav" href="{U_ALBUM}">{L_ALBUM}</a> -> <a class="nav" href="{U_VIEW_CAT}">{CAT_TITLE}</a></td>
  </tr>
</table>

<table width="100%" cellpadding="2" cellspacing="1" class="forumline">
  <tr>
	<th class="thHead" colspan="2">{L_RATING}</th>
  </tr>
  <tr>
	<td class="row1" align="center" width="30%"><a href="{U_PIC}" {TARGET_BLANK}><img src="{U_THUMBNAIL}" alt="{PIC_TITLE}" title="{PIC_TITLE}" vspace="20" hspace="20" /></a></td>
	<td class="row1" valign="top">
	<table width="100%" cellspacing="2" cellpadding="2">
		<tr>
		  <td align="right" valign="top" nowrap="nowrap" class="genmed">{L_PIC_TITLE}:</td>
		  <td valign="top" class="genmed" width="100%"><strong>{PIC_TITLE}</strong></td>
		</tr>
		<tr>
		  <td align="right" valign="top" nowrap="nowrap" class="genmed">{L_PIC_DESC}:</td>
		  <td valign="top" class="genmed"><strong>{PIC_DESC}</strong></td>
		</tr>
		<tr>
		  <td align="right" nowrap="nowrap" class="genmed">{L_POSTER}:</td>
		  <td class="genmed"><strong>{POSTER}</strong></td>
		</tr>
		<tr>
		  <td align="right" nowrap="nowrap" class="genmed">{L_POSTED}:</td>
		  <td class="genmed"><strong>{PIC_TIME}</strong></td>
		</tr>
		<tr>
		  <td align="right" nowrap="nowrap" class="genmed">{L_VIEW}:</td>
		  <td class="genmed"><strong>{PIC_VIEW}</strong></td>
		</tr>
	</table>
	</td>
  </tr>
  <tr>
	<td class="row1" align="center" colspan="2"><span class="gen"><br />{L_CURRENT_RATING}:&nbsp;<strong>{PIC_RATING}</strong><br />
	<br />{L_PLEASE_RATE_IT}:&nbsp;<select name="rate">
	<option value="-1">{S_RATE_MSG}</option>
	<!-- BEGIN rate_row -->
	<option value="{rate_row.POINT}">{rate_row.POINT}</option>
	<!-- END rate_row -->
	</select><br />&nbsp;</span></td>
  </tr>
  <tr>
	<td class="catBottom" align="center" colspan="2"><input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" /></td>
  </tr>
</table>
</form>
<br />
<div align="center" class="copyright">Photo Album {ALBUM_VERSION} &copy; 2002, {COPYRIGHT_YEAR} <a href="http://smartor.is-root.com" class="copyright" target="_blank">Smartor</a></div>

