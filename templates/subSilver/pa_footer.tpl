<br />
<table width="100%" cellpading="2" cellspacing="2" align="center"><form method="get" name="jumpbox" action="{S_JUMPBOX_ACTION}" onSubmit="if(document.jumpbox.cat_id.value == -1){return false;}">
<input type="hidden" name="action" value="category" />
<tr>
	<td valign="top" align="right"><select name="cat_id" onchange="if(this.options[this.selectedIndex].value != -1){ forms['jumpbox'].submit() }">
		<option value="-1">{L_JUMP}</option>
		{JUMPMENU}
	</select></td>
</tr>
</form></table>
<br />
<div align="center" class="copyright">paFileDB phpBB Integration 0.0.9d &amp; <a href="http://www.mx-system.com/" target="_blank" class="copyright">MX Addon 1.2</a> &copy; 2003, 2007 Mohd & Haplo</a></div>
