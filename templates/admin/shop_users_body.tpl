{USER_MENU}{BAN_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_TITLE}</h1>

<p>{L_EXPLAIN}</p>

<table width="100%" cellpadding="4" cellspacing="1" align="center" class="forumline">
<tr> 
	<th class="thHead" colspan="2">{L_SHOP_INFO}</th>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_TOTAL_SHOPS}:</b></td>
	<td class="row2">{TOTAL_SHOPS}</td>
</tr>
<tr>
	<td class="row1"><b>{L_TOTAL_ITEMS}:</b></td>
	<td class="row2">{TOTAL_ITEMS}</td>
</tr>
<tr>
	<td class="row1"><b>{L_POINTS_HELD}:</b></td>
	<td class="row2">{TOTAL_HOLDING}</td>
</tr>
<tr>
	<td class="row1"><b>{L_POINTS_EARNT}:</b></td>
	<td class="row2">{TOTAL_EARNT}</td>
</tr>
</table>
<br />

<!-- BEGIN switch_are_shops -->
<table width="50%" cellpadding="4" cellspacing="1" class="forumline" align="center"><form action="{S_CONFIG_ACTION}" method="post">
<input type="hidden" name="action" value="close_shop" />
<tr> 
	<th class="thHead" colspan="2">{L_CLOSE_SHOP}</th>
</tr>
<tr>
	<td class="row1" width="38%"><b>{L_SHOP_NAME}:</b></td>
	<td class="row2"><select name="id">
<!-- END switch_are_shops -->
		<!-- BEGIN list_shops -->
		<option value="{list_shops.SHOP_ID}">{list_shops.STRING}</option>
		<!-- END list_shops -->
<!-- BEGIN switch_are_shops -->
	</select></td>
</tr>
<tr>
	<td class="row1"><b>{L_RETURN_ITEMS}:</b></td>
	<td class="row2"><input type="radio" name="items" value="1" checked="checked" /> {L_YES}&nbsp;&nbsp;<input type="radio" name="items" value="0" /> {L_NO}</td>
</tr>
<tr>
	<td class="catBottom" colspan="2" align="center"><input type="submit" value="{L_SUBMIT}" name="update" class="mainoption"></td>
</tr>
</form></table>
<br />

<table width="50%" cellpadding="4" cellspacing="1" align="center" class="forumline"><form action="{S_CONFIG_ACTION}" method="post">
<input type="hidden" name="action" value="close_shop" />
<tr> 
	<th class="thHead" colspan="2">{L_CLOSE_USER_SHOP}</th>
</tr>
<tr>
	<td class="row1" width="38%"><b>{L_USERNAME}:</b></td>
	<td class="row2"><select name="id">
<!-- END switch_are_shops -->
		<!-- BEGIN list_users -->
		<option value="{list_users.SHOP_ID}">{list_users.STRING}</option>
		<!-- END list_users -->
<!-- BEGIN switch_are_shops -->
	</select></td>
</tr>
<tr>
	<td class="row1"><b>{L_RETURN_ITEMS}:</b></td>
	<td class="row2"><input type="radio" name="items" value="1" checked="checked" /> {L_YES}&nbsp;&nbsp;<input type="radio" name="items" value="0" /> {L_NO}</td>
</tr>
<tr>
	<td class="catBottom" colspan="2" align="center"><input type="submit" value="{L_SUBMIT}" name="update" class="mainoption"></td>
</tr>
</form></table>
<br />
<!-- END switch_are_shops -->
<div align="center" class="copyright">Shop 2.6.0 &copy; 2003, {COPYRIGHT_YEAR} <a href="http://www.zarath.com/mods/" class="copyright" target="_blank">Zarath Technologies</a></div>
