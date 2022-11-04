<table width="100%" cellspacing="2" cellpadding="2" align="center">
<tr>
	<td class="nav"><a class="nav" href="{U_INDEX}">{L_INDEX}</a></td>
</tr>
</table>

<!-- BEGIN modules -->
<a name="{modules.MODULE_ID}"></a>
<!-- --cached: {modules.CACHED}<br /> --reloaded: {modules.RELOADED}<br /> -->
{modules.CURRENT_MODULE}
<!-- BEGIN switch_display_timestats -->
<table cellpadding="4" cellspacing="1" class="forumline" width="100%"> 
<tr> 
	<td class="catLeft" align="center"><span class="gensmall">{L_LAST_UPDATE}: {modules.LAST_UPDATE_TIME}</span></td>
	<td class="catRight" align="center"><span class="gensmall">{L_NEXT_UPDATE}: {modules.NEXT_GUESSED_UPDATE_TIME}</span></td>
</tr> 
</table>
<!-- END switch_display_timestats -->
<br />
<!-- END modules -->

<table width="100%" cellspacing="2" align="center"> 
  <tr> 
	<td align="right" valign="middle" nowrap>{JUMPBOX}</td> 
  </tr> 
</table> 
<br />
<div align="center" class="copyright">Statistics 4.2.8 &copy; 2003, {COPYRIGHT_YEAR} <a href="http://opentools.de" target="_blank" class="copyright">Acyd Burn</a>
<!-- BEGIN switch_debug -->
<br />[ Time : {TIME} | SQL Time : {SQL_TIME} | {QUERY} Queries ]
<!-- END switch_debug -->
</div>