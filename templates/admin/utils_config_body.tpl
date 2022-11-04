{UTILS_MENU}{LOG_MENU}{DB_MENU}{LANG_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_DBMTNC_TITLE}</h1>

<p>{L_CONFIG_INFO}</p>

<table width="100%" cellpadding="4" cellspacing="1" align="center" class="forumline"><form action="{S_CONFIG_ACTION}" method="post">
	<tr>
	  <th class="thHead" colspan="2">{L_DBMTNC_TITLE}</th>
	</tr>
	<tr>
		<td class="row1" width="50%"><b>{L_DISALLOW_POSTCOUNTER}:</b><br /><span class="gensmall">{L_DISALLOW_POSTCOUNTER_EXPLAIN}</span></td>
		<td class="row2" nowrap="nowrap"><input type="radio" name="disallow_postcounter" value="1" {DISALLOW_POSTCOUNTER_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="disallow_postcounter" value="0" {DISALLOW_POSTCOUNTER_NO} /> {L_NO}</td>
	</tr>
	<tr>
		<td class="row1"><b>{L_DISALLOW_REBUILD}:</b><br /><span class="gensmall">{L_DISALLOW_REBUILD_EXPLAIN}</span></td>
		<td class="row2"><input type="radio" name="disallow_rebuild" value="1" {DISALLOW_REBUILD_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="disallow_rebuild" value="0" {DISALLOW_REBUILD_NO} /> {L_NO}</td>
	</tr>
<!-- BEGIN rebuild_settings -->
	<tr>
	  <th class="thHead" colspan="2">{L_REBUILD_CONFIG}</th>
	</tr>
	<tr>
		<td class="row2" colspan="2"><span class="gensmall">{L_REBUILD_SETTINGS_EXPLAIN}</span></td>
	</tr>
	<tr>
		<td class="row1"><b>{L_REBUILDCFG_TIMELIMIT}:</b><br /><span class="gensmall">{L_REBUILDCFG_TIMELIMIT_EXPLAIN}</span></td>
		<td class="row2"><input class="post" type="text" maxlength="5" size="5" name="rebuildcfg_timelimit" value="{REBUILDCFG_TIMELIMIT}" /></td>
	</tr>
	<tr>
		<td class="row1"><b>{L_REBUILDCFG_TIMEOVERWRITE}:</b><br /><span class="gensmall">{L_REBUILDCFG_TIMEOVERWRITE_EXPLAIN}</span></td>
		<td class="row2"><input class="post" type="text" maxlength="5" size="5" name="rebuildcfg_timeoverwrite" value="{REBUILDCFG_TIMEOVERWRITE}" /></td>
	</tr>
	<tr>
		<td class="row1"><b>{L_REBUILDCFG_MAXMEMORY}:</b><br /><span class="gensmall">{L_REBUILDCFG_MAXMEMORY_EXPLAIN}</span></td>
		<td class="row2"><input class="post" type="text" maxlength="5" size="5" name="rebuildcfg_maxmemory" value="{REBUILDCFG_MAXMEMORY}" /></td>
	</tr>
	<tr>
		<td class="row1"><b>{L_REBUILDCFG_MINPOSTS}:</b><br /><span class="gensmall">{L_REBUILDCFG_MINPOSTS_EXPLAIN}</span></td>
		<td class="row2"><input class="post" type="text" maxlength="3" size="3" name="rebuildcfg_minposts" value="{REBUILDCFG_MINPOSTS}" /></td>
	</tr>
	<tr>
		<td class="row1"><b>{L_REBUILDCFG_PHP3ONLY}:</b><br /><span class="gensmall">{L_REBUILDCFG_PHP3ONLY_EXPLAIN}</span></td>
		<td class="row2"><input type="radio" name="rebuildcfg_php3only" value="1" {REBUILDCFG_PHP3ONLY_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="rebuildcfg_php3only" value="0" {REBUILDCFG_PHP3ONLY_NO} /> {L_NO}</td>
	</tr>
	<tr>
		<td class="row1"><b>{L_REBUILDCFG_PHP4PPS}:</b><br /><span class="gensmall">{L_REBUILDCFG_PHP4PPS_EXPLAIN}</span></td>
		<td class="row2"><input class="post" type="text" maxlength="3" size="3" name="rebuildcfg_php4pps" value="{REBUILDCFG_PHP4PPS}" /></td>
	</tr>
	<tr>
		<td class="row1"><b>{L_REBUILDCFG_PHP3PPS}:</b><br /><span class="gensmall">{L_REBUILDCFG_PHP3PPS_EXPLAIN}</span></td>
		<td class="row2"><input class="post" type="text" maxlength="3" size="3" name="rebuildcfg_php3pps" value="{REBUILDCFG_PHP3PPS}" /></td>
	</tr>
<!-- END rebuild_settings -->
<!-- BEGIN currentrebuild_settings -->
	<tr>
	  <th class="thHead" colspan="2">{L_CURRENTREBUILD_CONFIG}</th>
	</tr>
	<tr>
		<td class="row2" colspan="2"><span class="gensmall">{L_CURRENTREBUILD_SETTINGS_EXPLAIN}</span></td>
	</tr>
	<tr>
		<td class="row1"><b>{L_REBUILD_POS}:</b><br /><span class="gensmall">{L_REBUILD_POS_EXPLAIN}</span></td>
		<td class="row2"><input class="post" type="text" maxlength="10" size="8" name="rebuild_pos" value="{REBUILD_POS}" /></td>
	</tr>
	<tr>
		<td class="row1"><b>{L_REBUILD_END}:</b><br /><span class="gensmall">{L_REBUILD_END_EXPLAIN}</span></td>
		<td class="row2"><input class="post" type="text" maxlength="10" size="8" name="rebuild_end" value="{REBUILD_END}" /></td>
	</tr>
<!-- END currentrebuild_settings -->
	<tr>
		<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" />
		</td>
	</tr>
</form></table>
