<table width="100%" cellspacing="2" cellpadding="2" align="center">
  <tr>
	<td class="nav"><a href="{U_ADD_CHART}" class="nav">{ADD_IMG}</a>&nbsp;&nbsp;&nbsp;<a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> <a href="{U_SHOW_LIST}" class="nav">{L_SHOW_LIST}</a></td>
  </tr>
</table>

<table width="100%" cellspacing="1" cellpadding="4" align="center" class="forumline">
  <tr>
	<td class="catHead" align="center" colspan="7"><span class="cattitle">{SITENAME} {L_CHART_NAME} - {L_WEEK} {V_WEEK_NUM}</span></td>
  </tr>
  <tr>
	<th class="thCornerL" width="5%">{L_THIS_WEEK}</th>
	<th class="thTop" width="5%">{L_LAST_WEEK}</th>
	<th class="thTop" nowrap="nowrap">{L_TITLE}, <i>{L_ALBUM}</i> - {L_ARTIST}<br />{L_LABEL} | {L_CAT_NO} | {L_WEBSITE}</th>
	<th class="thTop" nowrap="nowrap">{L_ADDED}</th>
	<th class="thTop" nowrap="nowrap">{L_RATE}</th>
	<th class="thCornerR" width="5%">{L_BEST_POS}</th>
  </tr>
<!-- BEGIN chart_block -->
  <tr>
	<td class="{chart_block.ROW_CLASS}" align="center"><span class="genmed">{chart_block.CHART_POS}</span></td>
	<td class="{chart_block.ROW_CLASS}" align="center" nowrap="nowrap"><span class="genmed">{chart_block.CHART_LAST}</span></td>
	<td class="{chart_block.ROW_CLASS}" nowrap="nowrap" valign="top"><span class="genmed"><b>{chart_block.CHART_SONG}</b>, <i>{chart_block.CHART_ALBUM}</i> - {chart_block.CHART_ARTIST}</span><br /><span class="gensmall">{chart_block.CHART_LABEL} | {chart_block.CHART_CAT_NO} | <a href="{chart_block.CHART_WEBSITE}" class="gensmall" target="_blank">{chart_block.CHART_WEBSITE}</a></span></td>
	<td class="{chart_block.ROW_CLASS}" align="center" nowrap="nowrap"><a href="{chart_block.U_POSTER}" class="genmed">{chart_block.CHART_POSTER}</a></td>
	<td class="{chart_block.ROW_CLASS}" align="center" nowrap="nowrap"><span class="gensmall"><a href="{chart_block.U_CHART_HOT}">{chart_block.HOT_IMG}</a> <a href="{chart_block.U_CHART_NOT}">{chart_block.NOT_IMG}</a><br />{chart_block.CHART_HOT_NOT}</span></td>
	<td class="{chart_block.ROW_CLASS}" align="center" nowrap="nowrap"><span class="genmed">{chart_block.CHART_BEST}</span></td>
  </tr>
<!-- END chart_block -->
  <tr>
	<td colspan="6" class="catBottom">&nbsp;</td>
  </tr>
</table>
<table width="100%" cellspacing="2" cellpadding="2" align="center">
  <tr>
	<td class="nav" valign="middle"><a href="{U_ADD_CHART}" class="nav">{ADD_IMG}</a>&nbsp;&nbsp;&nbsp;<a href="{U_INDEX}" class="nav">{L_INDEX}</a></td>
  </tr>
</table>
<br />
<table width="100%" cellspacing="2" align="center">
  <tr>
	<td align="right" valign="middle" nowrap="nowrap">{JUMPBOX}</td>
  </tr>
</table>
<br />
<div align="center" class="copyright">Charts 1.0 &copy; 2003, {COPYRIGHT_YEAR} <a href="mailto:dzidzius@forumbest.now.pl" target="_blank" class="copyright">dzidzius</a> &amp; <a href="http://phpbb-fm.com" target="_blank" class="copyright">MJ</a></div>
