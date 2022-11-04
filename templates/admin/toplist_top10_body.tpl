<table align="center" width="100%" cellpadding="4" cellspacing="1" class="forumline">
<tr>
	<td class="catHead" colspan="{COLSPAN}"><a href="{U_TOPLIST}" class="cattitle">{L_TOPLIST_TOP10}</a></td>
</tr>
<tr>
	<th class="thCornerL">{RANK}</th>
	<th class="thTop">{SITE}</th>
	<!-- BEGIN switch_toplist_view_in_hits -->
	<th class="thTop">{switch_toplist_view_in_hits.IN_HITS}</th>
	<!-- END switch_toplist_view_in_hits -->
	<!-- BEGIN switch_toplist_view_out_hits -->
	<th class="thTop">{switch_toplist_view_out_hits.OUT_HITS}</th>
	<!-- END switch_toplist_view_out_hits -->
	<!-- BEGIN switch_toplist_view_img_hits -->
	<th class="thCornerR">{switch_toplist_view_img_hits.IMG_HITS}</th>
	<!-- END switch_toplist_view_img_hits -->
</tr>
<!-- BEGIN toplist -->
<tr>
	<td class="row1" align="center">{toplist.R}</td>
	<td class="row1"><a href="{LOCATION}go.php?id={toplist.ID}" class="forumlink" target="_blank">{toplist.NAM}</a><br /><span class="gensmall">{toplist.INF}</span><br /><a href="{LOCATION}go.php?id={toplist.ID}" target="_blank"><img src="{toplist.BAN}" alt="{toplist.NAM}" title="{toplist.NAM}" /></a></td>
	<!-- BEGIN switch_toplist_view_in_hits -->
	<td class="row2" align="center">{toplist.switch_toplist_view_in_hits.HIN}</td>
	<!-- END switch_toplist_view_in_hits -->
	<!-- BEGIN switch_toplist_view_out_hits -->
  	<td class="row2" align="center">{toplist.switch_toplist_view_out_hits.OUT}</td>
	<!-- END switch_toplist_view_out_hits -->
	<!-- BEGIN switch_toplist_view_img_hits -->
	<td class="row2" align="center">{toplist.switch_toplist_view_img_hits.IMG}</td>
	<!-- END switch_toplist_view_img_hits -->
</tr>
<!-- END toplist -->
</table>
<br />