<table width="100%" cellspacing="2" cellpadding="2" align="center">
<tr> 
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> <a href="{U_ACTIVITY}" class="nav">{L_ACTIVITY}</a></td>
</tr>
</table>

<table class="forumline" width="100%" cellspacing="1" cellpadding="4" align="center">
<tr>
	<th class="thCornerR" width="5%">#</th>
	<th class="thTop" align="center" width="{WIDTH1}">{L_HIGHSCORE} {DASH} {TITLE}</th>
	<th class="thTop" align="center" width="{WIDTH2}">{L_SCORE}</th>
	<th class="thCornerL" align="center" width="{WIDTH3}">{L_PLAYED}</th>
</tr>
<!-- BEGIN scores -->
<tr>
	<td class="{scores.ROW_CLASS}" align="center">{scores.POS}</td>
	<td class="{scores.ROW_CLASS}"><span class="gen">{scores.NAME}</span></td>
	<td class="{scores.ROW_CLASS}" align="{scores.ALIGN}">{scores.SCORE}</td>
	<td class="{scores.ROW_CLASS}" align="center">{scores.DATE}</td>
</tr>
<!-- BEGIN scores_stats -->
<tr>
	<td class="{scores.ROW_CLASS}" colspan="4"><span class="gen">{scores.scores_stats.STATS}</span></td>
</tr>
<!-- END scores_stats --> 
<!-- END scores -->
<tr>
	<td class="catBottom" colspan="4">&nbsp;</td>
</tr>
</table>
<br />
<table width="100%" cellspacing="2" align="center"> 
<tr> 
	<td align="right" valign="middle" nowrap="nowrap">{JUMPBOX}</td> 
</tr> 
</table> 
<br />
<div align="center" class="copyright">Activity Mod Plus v1.1.0 &copy; 2003, {COPYRIGHT_YEAR} <a href="http://phpbb-amod.com" target="_blank" class="copyright">aUsTiN</a></div>
