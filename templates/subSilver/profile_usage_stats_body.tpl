<table width="100%" cellpadding="2" cellspacing="2" align="center">
  <tr>
	<td align="right" valign="bottom"><a href="{URL_COLDESC}" class="gensmall" onClick="window.open('{URL_COLDESC}', '_coldesc', 'HEIGHT=250,resizable=yes,WIDTH=400');return false;" >{L_BBUS_COL_DESCRIPTIONS_CAPTION}</a></td>
  </tr>
</table>  
<table width="100%" cellpadding="3" cellspacing="1" align="center" class="forumline">
  <tr>
	<td class="catHead" colspan="8" align="center"><B><span class="gen">{L_BBUS_MOD_TITLE}</span></b>
  </tr>
  <tr>
	<th class="thCornerL">{L_BBUS_COLHEADER_FORUM}</th>
	<th class="thTop"nowrap="nowrap">&nbsp;{L_BBUS_COLHEADER_POSTS}&nbsp;</th>
	<th class="thTop" nowrap="nowrap">&nbsp;{L_BBUS_COLHEADER_POSTRATE}&nbsp;</th>
	<th class="thTop" nowrap="nowrap">&nbsp;{L_BBUS_COLHEADER_PCTUTP}&nbsp;</th>
<!-- BEGIN bb_usage_switch_pctutup_colhdr -->
	<th class="thTop" nowrap="nowrap">{bb_usage_switch_pctutup_colhdr.L_BBUS_COLHEADER_PCTUTUP}</th>
<!-- END bb_usage_switch_pctutup -->
	<th class="thTop" nowrap="nowrap">{L_BBUS_COLHEADER_NEWTOPICS}</th>
	<th class="thTop" nowrap="nowrap">{L_BBUS_COLHEADER_TOPICRATE}</th>	
	<th class="thCornerR" nowrap="nowrap">{L_BBUS_COLHEADER_TOPICS_WATCHED}</th>
  </tr>
<!-- BEGIN bb_usage_section_row -->
  <tr>
<form name="section_form_{bb_usage_section_row.SECTION_ID}" action="{bb_usage_section_row.U_SECTION}" method="post">
<input name="search_cat" type="hidden" value="{bb_usage_section_row.SECTION_ID}"/>
</form>
	<td class="catLeft" align="left" valign="middle"><b><a style="cursor: pointer" class="gen" onclick="document.section_form_{bb_usage_section_row.SECTION_ID}.submit()">{bb_usage_section_row.SECTION_NAME}</a></b></td>
	<td class="cat" align="center" valign="middle"><b><span class="catTitle">{bb_usage_section_row.SECTION_POST_COUNT}</span></b></td>
	<td class="cat" align="center" valign="middle"><b><span class="catTitle">{bb_usage_section_row.SECTION_POSTRATE}</span></b></td>
	<td class="cat" align="center" valign="middle"><b><span class="catTitle">{bb_usage_section_row.SECTION_POST_PCTUTP}</span></b></td>
<!-- BEGIN bb_usage_switch_pctutup_section -->
	<td class="cat" align="center" valign="middle"><b><span class="catTitle">{bb_usage_section_row.bb_usage_switch_pctutup_section.SECTION_POST_PCTUTUP}</span></b></td>
<!-- END bb_usage_switch_pctutup_section -->
	<td class="cat" align="center" valign="middle"><b><span class="catTitle">{bb_usage_section_row.SECTION_NEWTOPICS}</span></b></td>
	<td class="cat" align="center" valign="middle"><b><span class="catTitle">{bb_usage_section_row.SECTION_TOPICRATE}</span></b></td>
	<td class="catRight" align="center" valign="middle"><b><span class="catTitle">{bb_usage_section_row.SECTION_TOPICS_WATCHED}</span></b></td>
  </tr>
<!-- BEGIN bb_usage_forum_row -->
  <tr>
<form name="forum_form_{bb_usage_section_row.bb_usage_forum_row.FORUM_ID}" action="{bb_usage_section_row.bb_usage_forum_row.FORUM_URL}" method="post">
<input name="search_forum" type="hidden" value="{bb_usage_section_row.bb_usage_forum_row.FORUM_ID}"/>
</form>
	<td class="row1" width="100%"><a style="cursor: pointer" class="gen" onclick="document.forum_form_{bb_usage_section_row.bb_usage_forum_row.FORUM_ID}.submit()">{bb_usage_section_row.bb_usage_forum_row.FORUM_NAME}</a></td>
	<td class="row2" align="center" valign="middle"><span class="gen">{bb_usage_section_row.bb_usage_forum_row.FORUM_POST_COUNT}</span></td>
	<td class="row2" align="center" valign="middle"><span class="gen">{bb_usage_section_row.bb_usage_forum_row.FORUM_POSTRATE}</span></td>
	<td class="row3" align="center" valign="middle"><span class="gen">{bb_usage_section_row.bb_usage_forum_row.FORUM_POST_PCTUTP}</span></td>
<!-- BEGIN bb_usage_switch_pctutup_forum -->
	<td class="row3" align="center" valign="middle"><span class="gen">{bb_usage_section_row.bb_usage_forum_row.bb_usage_switch_pctutup_forum.FORUM_POST_PCTUTUP}</span></td>
<!-- END bb_usage_switch_pctutup_forum -->
	<td class="row2" align="center" valign="middle"><span class="gen">{bb_usage_section_row.bb_usage_forum_row.FORUM_NEWTOPICS}</span></td>
	<td class="row2" align="center" valign="middle"><span class="gen">{bb_usage_section_row.bb_usage_forum_row.FORUM_TOPICRATE}</span></td>
	<td class="row2" align="center" valign="middle"><span class="gen">{bb_usage_section_row.bb_usage_forum_row.FORUM_TOPICS_WATCHED}</span></td>
  </tr>
<!-- END bb_usage_forum_row -->
<!-- END bb_usage_section_row -->

<!-- BEGIN bb_usage_switch_scaling_row -->
<form method="post" name="scale_form" action="{bb_usage_switch_scaling_row.U_SCALE}">
  <tr>
	<td class="cat" align="right" valign="middle"><b><span class="genmed" style="font-weight: bold; color: #006699">{bb_usage_switch_scaling_row.SCALE_TEXT}</span></b></td>
	<td class="cat" align="right" valign="middle">&nbsp;</td>
	<td class="cat" align="center" valign="middle">{bb_usage_switch_scaling_row.PRSCALE_SELECT_LIST}</td>
	<td class="cat" align="right" valign="middle">&nbsp;</td>
<!-- BEGIN pctutup_filler_cell -->
	{bb_usage_switch_scaling_row.pctutup_filler_cell.FILLER_CELL}
<!-- END bb_usage_scaling_filler_cell -->
	<td class="cat" align="right" valign="middle">&nbsp;</td>
	<td class="cat" align="center" valign="middle">{bb_usage_switch_scaling_row.TRSCALE_SELECT_LIST}</td>
	<td class="cat" align="right" valign="middle">&nbsp;</td>
  </tr>
</form>
<!-- END bb_usage_switch_scaling_row -->

<!-- BEGIN bb_usage_row_noposts -->
  <tr>
	<td class="row1" colspan="8" align="center" height="30"><span class="gen">{bb_usage_row_noposts.L_BBUS_MSG_NOPOSTS}</span></td>
  </tr>
<!-- END bb_usage_row_noposts -->

<!-- BEGIN bb_usage_switch_miscellaneous_info -->
  <tr>
  	<th class="thTop" height="20" nowrap="nowrap" colspan="8">{bb_usage_switch_miscellaneous_info.L_BBUS_COLHDR_MISC}</th>
  </tr>

<!-- BEGIN bb_usage_switch_misc_prunedposts -->
  <tr>
	<td class="row1" width="100%" colspan="{bb_usage_switch_miscellaneous_info.bb_usage_switch_misc_prunedposts.BBUS_PRUNED_POSTS_COLSPAN1}"><span class="gen">{bb_usage_switch_miscellaneous_info.bb_usage_switch_misc_prunedposts.L_BBUS_PRUNED_POSTS}</span></td>
	<td class="row2" align="center" valign="middle" colspan="{bb_usage_switch_miscellaneous_info.bb_usage_switch_misc_prunedposts.BBUS_PRUNED_POSTS_COLSPAN2}"><span class="gen">{bb_usage_switch_miscellaneous_info.bb_usage_switch_misc_prunedposts.BBUS_PRUNED_POSTS}</span></td>
  </tr>
<!-- END bb_usage_switch_misc_prunedposts -->

<!-- END bb_usage_switch_miscellaneous_info  -->
</table>