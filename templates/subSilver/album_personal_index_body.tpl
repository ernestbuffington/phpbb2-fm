<table width="100%" cellspacing="2" cellpadding="2"><form name="search" action="album_search.php">
<tr>
	<td><span class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> <a href="{U_ALBUM}" class="nav">{L_ALBUM}</a></span></td>
	<td align="right"><span class="gensmall">{L_SEARCH}:&nbsp; <select name="mode">
		<option>{L_USERNAME}</option>
		<option>{L_PIC_TITLE}</option>
		<option>{L_PIC_DESC}</option>
	</select> &nbsp;{L_THAT_CONTAINS}:&nbsp; <input class="post" type="text" name="search" maxlength="20">&nbsp;<input type="submit" class="liteoption" value="{L_GO}" /></span></td>
</tr>
</form></table>
<table width="100%" cellpadding="4" cellspacing="1" class="forumline">
<tr>
	<th width="60%" nowrap="nowrap" class="thCornerL">&nbsp;{L_USERS_PERSONAL_GALLERIES}&nbsp;</th>
	<th width="10%" class="thTop" nowrap="nowrap">&nbsp;{L_JOINED}&nbsp;</th>
	<th width="60" class="thTop" nowrap="nowrap">&nbsp;{L_PICS}&nbsp;</th>
	<th class="thCornerR" nowrap="nowrap">&nbsp;{L_LAST_PIC_DATE}&nbsp;</th>
</tr>
<!-- BEGIN memberrow -->
<tr>
	<td height="28" class="{memberrow.ROW_CLASS}">&nbsp;<span class="gen"><a href="{memberrow.U_VIEWGALLERY}" class="gen">{memberrow.USERNAME}</a></span></td>
	<td class="{memberrow.ROW_CLASS}" align="center" nowrap="nowrap"><span class="gensmall">&nbsp;{memberrow.JOINED}&nbsp;</span></td>
	<td class="{memberrow.ROW_CLASS}" align="center"><span class="gensmall">{memberrow.PICS}</span></td>
	<td class="{memberrow.ROW_CLASS}" align="center" nowrap="nowrap"><span class="gensmall">&nbsp;{memberrow.LAST_PIC}&nbsp;</span></td>
</tr>
<!-- END memberrow -->
  <!-- BEGIN no_pics -->
  <tr>
	<td class="row1" align="center" height="50" colspan="4"><span class="gen">{L_NO_PICS}</span></td>
  </tr>
  <!-- END no_pics -->
<tr>
	<form method="post" action="{S_MODE_ACTION}">
	<td class="catBottom" colspan="4" align="center"><span class="gensmall">{L_SELECT_SORT_METHOD}:&nbsp;{S_MODE_SELECT}&nbsp;&nbsp;{L_ORDER}:&nbsp;{S_ORDER_SELECT}&nbsp;&nbsp;<input type="submit" name="submit" value="{L_GO}" class="liteoption" /></span></td>
	</form>
</tr>
</table>

<table width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td class="nav">{PAGE_NUMBER}</td>
	<td align="right" class="nav">{PAGINATION}</td>
 </tr>
</table>
<br />
<div align="center" class="copyright">Photo Album {ALBUM_VERSION} &copy; 2002, {COPYRIGHT_YEAR} <a href="http://smartor.is-root.com" class="copyright" target="_blank">Smartor</a></div>
