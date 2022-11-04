<table width="100%" cellspacing="2" cellpadding="2" align="center">
  <tr> 
	<td colspan="2"><span class="maintitle">{L_NRESULTS}</span><br />&nbsp;</td>
  </tr>
  <tr> 
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> <a class="nav" href="{U_ALBUM}">{L_ALBUM}</a></td>
  </tr>
</table>

<!-- BEGIN switch_search -->
<form name="search" action="album_search.php">
<table width="100%" cellpadding="4" cellspacing="1" class="forumline">
<tr>
	<th class="thHead">{L_SEARCH}</th>
</tr>
<tr>
	<td class="row1" height="50" align="center"><span class="gensmall">{L_SEARCH}:&nbsp;
	<select name="mode">
		<option>{L_USERNAME}</option>
		<option>{L_PIC_TITLE}</option>
		<option>{L_PIC_DESC}</option>
	</select>
	&nbsp;{L_THAT_CONTAINS}:&nbsp; 
	<input class="post" type="text" name="search" maxlength="20">&nbsp;<input type="submit" class="liteoption" value="{L_GO}">
	</span></td>
</tr>
<tr>
	<td class="catBottom">&nbsp;</td>
</tr>
</table>
</form>
<br />
<!-- END switch_search -->

<!-- BEGIN switch_search_results -->
<table width="100%" cellpadding="4" cellspacing="1" class="forumline" align="center">
  <tr> 
  	<th class="thCornerL" nowrap="nowrap" colspan="2">&nbsp;{L_TTITLE}&nbsp;</th>
	<th class="thTop" nowrap="nowrap" width="150">&nbsp;{L_TCATEGORY}&nbsp;</th>
	<th class="thTop" nowrap="nowrap" width="100">&nbsp;{L_TSUBMITER}&nbsp;</th>
	<th class="thCornerR" nowrap="nowrap">&nbsp;{L_TSUBMITED}&nbsp;</th>
  </tr>
  <!-- BEGIN search_results -->
  <tr>
	<td class="row1" width="5%" align="center" valign="middle"><a href="{switch_search_results.search_results.U_PIC}"><img src="templates/{T_NAV_STYLE}/folder.gif" alt="" title="" /></a></td>
	<td class="row1" width="100%"><a href="{switch_search_results.search_results.U_PIC}" class="topictitle">{switch_search_results.search_results.L_PIC}</a></td>
	<td class="row2"><a href="{switch_search_results.search_results.U_CAT}" class="cattitle">{switch_search_results.search_results.L_CAT}</a></td>
	<td class="row1" align="center"><a href="{switch_search_results.search_results.U_PROFILE}" class="name">{switch_search_results.search_results.L_USERNAME}</a></td>
	<td class="row2" align="center" nowrap="nowrap">{switch_search_results.search_results.L_TIME}</td>
  </tr>
  <!-- END search_results -->
  <tr> 
	<td class="catBottom" colspan="7">&nbsp;</td>
  </tr>
</table>
<!-- END switch_search_results -->
<br />
<div align="center" class="copyright">Photo Album {ALBUM_VERSION} &copy; 2002, {COPYRIGHT_YEAR} <a href="http://smartor.is-root.com" class="copyright" target="_blank">Smartor</a></div>

