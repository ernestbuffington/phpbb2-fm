
<table width="100%" cellspacing="1" cellpadding="4" align="center">
  <tr>
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> <a href="{U_VIEW_PROFILE}" class="nav">{USERNAME}</a></span></td>
  </tr>
</table>

<table cellspacing="1" cellpadding="4" width="100%" class="forumline">
  <tr>
	<td colspan="{COLSPAN}" align="center" class="catHead" nowrap="nowrap"><span class="cattitle">{PAGE_TITLE} :: {USERNAME}</span></td>
  </tr>
  <tr>
	<th class="thCornerL" nowrap="nowrap">&nbsp;{L_POSTER}&nbsp;</th>
	<th class="thTop">&nbsp;{L_COMMENTS}&nbsp;</th>
	<th class="thTop" nowrap="nowrap">&nbsp;{L_TIME}&nbsp;</th>
	<!-- BEGIN admin_privs -->
	<th width="15%" class="thCornerR">&nbsp;{L_ACTION}&nbsp;</th>
	<!-- END admin_privs -->
  </tr>
  <!-- BEGIN comments_row -->
  <tr>
	<td class="{comments_row.ROW_CLASS}">{comments_row.POSTER}</td>
	<td class="{comments_row.ROW_CLASS}">{comments_row.COMMENTS}</td>
	<td width="200" align="center" nowrap="nowrap" class="{comments_row.ROW_CLASS}"><span class="postdetails">{comments_row.TIME}</span></td>
 	<!-- BEGIN admin_privs -->
	<td align="right" class="{comments_row.ROW_CLASS}"><a href="{comments_row.admin_privs.U_EDIT_COMMENTS}">{EDIT_IMG}</a> <a href="{comments_row.admin_privs.U_DELETE_COMMENTS}">{DELETE_IMG}</a></td>
	<!-- END admin_privs -->
 </tr>
  <!-- END comments_row -->
  <!-- BEGIN switch_no_comments -->
  <tr>
	<td class="row1" colspan="{COLSPAN}" align="center" height="30"><span class="gen">{L_NO_COMMENTS}</span></td>
  </tr>
  <!-- END switch_no_comments -->
  <tr>
	<td class="catBottom" colspan="{COLSPAN}">&nbsp;</td>
  </tr>
</table>
<table width="100%" cellspacing="2" cellpadding="2" align="center">
  <tr>
	<td class="nav">{S_POST_URL}<br />{PAGE_NUMBER}</td>
	<td align="right" valign="top" class="nav">{PAGINATION}</td>
  </tr>
</table>

<table width="100%" cellspacing="2" cellpadding="2">
  <tr>
	<td align="right" valign="top">{JUMPBOX}</td>
  </tr>
</table>
