<br />
<table width="100%" cellpadding="2" cellspacing="2"><form method="get" name="jumpbox" action="{S_JUMPBOX_ACTION}" onSubmit="if(document.jumpbox.cat_id.value == -1){return false;}">
<input type="hidden" name="action" value="category" />
<tr>
	<td align="right"><select name="cat_id" onchange="if(this.options[this.selectedIndex].value != -1){ forms['jumpbox'].submit() }">
		<option value="-1">{L_JUMP}</option>
		{JUMPMENU}
	</select></td>
</tr>
</form></table>
<div align="center" class="copyright">LinkDB {LINKDB_VERSION} &copy; 2003, {COPYRIGHT_YEAR} <a href="http://www.phpbb.com/phpBB/viewtopic.php?t=215032" target="_blank" class="copyright">CRLin</a></div>
