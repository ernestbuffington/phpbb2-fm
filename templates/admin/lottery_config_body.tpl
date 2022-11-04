{CONFIG_MENU}{USERCOM_MENU}{SERVER_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_LOTTERY_TITLE}</h1>

<p>{L_LOTTERY_TITLE_EXPLAIN}</p>

<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline"><form action="{S_CONFIG_ACTION}" method="post">
<tr>
	<th class="thHead" colspan="2">{L_LOTTERY_TITLE}</th>
</tr>
<tr> 
   	<td class="row1" width="50%"><b>{L_LOTTERY_STATUS}:</b></td>
   	<td class="row2"><input type="radio" name="lottery_status" value="1" {S_LOTTERY_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="lottery_status" value="0" {S_LOTTERY_NO} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_LOTTERY_NAME}:</b></td>
    	<td class="row2"><input class="post" type="text" size="30" maxlength="32" name="lottery_name" value="{LOTTERY_NAME}" /></td>
</tr>
<tr> 
	<td class="row1"><b>{L_LOTTERY_AUTO_RESTART}:</b></td>
	<td class="row2"><input type="radio" name="lottery_reset" value="1" {S_LOTTERY_RESET_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="lottery_reset" value="0" {S_LOTTERY_RESET_NO} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_LOTTERY_BASE_AMOUNT}:</b></td>
    	<td class="row2"> <input class="post" type="text" size="5" maxlength="5" name="lottery_base" value="{LOTTERY_BASE_AMOUNT}" /> {L_POINTS}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_LOTTERY_ENTRY_COST}:</b></td>
    	<td class="row2"><input class="post" type="text" size="5" maxlength="5" name="lottery_cost" value="{LOTTERY_ENTRY_COST}" /> {L_POINTS}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_LOTTERY_DRAW_PERIODS}:</b></td>
    	<td class="row2"><select name="lottery_length">
		<option value="{LOTTERY_DRAW_PERIODS_SELECT}">* {LOTTERY_DRAW_PERIODS_SELECTED}</option>
		<option value="86400">{L_LOTTERY_DAY}</option>
		<option value="604800">7 {L_LOTTERY_DAYS}</option>
		<option value="1209600">14 {L_LOTTERY_DAYS}</option>
		<option value="2592000">30 {L_LOTTERY_DAYS}</option>
		<option value="31536000">365 {L_LOTTERY_DAYS}</option>
	</select></td>
</tr>
<tr> 
	<td class="row1"><b>{L_LOTTERY_TICKETS_ALLOWED}:</b></td>
	<td class="row2"><input type="radio" name="lottery_ticktype" value="0" {S_LOTTERY_TICKETS_ALLOWED_SINGLE} /> {L_LOTTERY_SINGLE}&nbsp;&nbsp;<input type="radio" name="lottery_ticktype" value="1" {S_LOTTERY_TICKETS_ALLOWED_MULTIPLE} /> {L_LOTTERY_MULTIPLE}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_LOTTERY_MULT_TICKETS}:</b></td>
	<td class="row2"><input type="radio" name="lottery_mb" value="0" {S_LOTTERY_MB_NO} /> {L_NO}&nbsp;&nbsp;<input type="radio" name="lottery_mb" value="1" {S_LOTTERY_MB_YES} /> {L_YES}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_LOTTERY_MULT_TICKETS}:</b><br /><span class="gensmall">{L_LOTTERY_MULT_TICKETS_EXPLAIN}</span></td>
    	<td class="row2"><input class="post" type="text" size="5" maxlength="5" name="lottery_mb_amount" value="{LOTTERY_MB_AMOUNT}" /></td>
</tr>
<tr> 
	<td class="row1"><b>{L_LOTTERY_FULL_DISPLAY}:</b></td>
	<td class="row2"><input type="radio" name="lottery_show_entries" value="1" {S_LOTTERY_DISPLAY_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="lottery_show_entries" value="0" {S_LOTTERY_DISPLAY_NO} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_LOTTERY_ITEM_POOL}:</b></td>
	<td class="row2"><input type="radio" name="lottery_items" value="1" {S_LOTTERY_ITEMS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="lottery_items" value="0" {S_LOTTERY_ITEMS_NO} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_LOTTERY_HISTORY}:</b></td>
	<td class="row2"><input type="radio" name="lottery_history" value="1" {S_LOTTERY_POOL_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="lottery_history" value="0" {S_LOTTERY_POOL_NO} /> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_LOTTERY_ENTRIES_TOTAL}:</b></td>
    	<td class="row2">{LOTTERY_TOTAL_ENTRIES}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_LOTTERY_LEFT_TIME}:</b></td>
    	<td class="row2">{LOTTERY_DURATION}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_LOTTERY_LOTTERY_POOL}:</b></td>
    	<td class="row2">{LOTTERY_POOL} {L_POINTS}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_LOTTERY_WON_LAST}:</b></td>
    	<td class="row2">{LOTTERY_LAST_WON}</td>
</tr>
<!-- BEGIN switch_enabled_items -->
<tr>
	<th class="thHead" colspan="2">{L_LOTTERY_RAND_ITEMS_TITLE}</th>
</tr>
<tr>
	<td class="row1"><b>{L_LOTTERY_FROM_SHOP}:</b></td>
	<td class="row2"><select name="rand_shop">
		<option value="">{L_LOTTERY_ALL_SHOPS}</option>
<!-- END switch_enabled_items -->
<!-- BEGIN rand_listrow -->
		<option value="{rand_listrow.SHOP_NAME}" {rand_listrow.SELECTED}>{rand_listrow.SHOP_NAME}</option>
<!-- END rand_listrow -->
<!-- BEGIN switch_enabled_items -->
	</select></td>
</tr>
<tr>
	<td class="row1"><b>{L_LOTTERY_MIN_COST}:</b></td>
	<td class="row2"><input type="text" name="lottery_item_mcost" value="{LOTTERY_RAND_COST_MIN}" size="10" maxlength="15" class="post" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_LOTTERY_MAX_COST}:</b></td>
	<td class="row2"><input type="text" name="lottery_item_xcost" value="{LOTTERY_RAND_COST_MAX}" size="10" maxlength="15" class="post" /></td>
</tr>
<!-- END switch_enabled_items -->
<tr>
	<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>

<!-- BEGIN switch_enabled_items -->
</table>
<br />
<table align="center" cellpadding="4" cellspacing="1" class="forumline">
<tr> 
	<th class="thHead" colspan="2">{L_LOTTERY_ITEMS_TITLE}</th>
</tr>
<!-- END switch_enabled_items -->
<!-- BEGIN switch_pool_items -->
<tr>
	<form action="{S_CONFIG_ACTION}" method="post">
	<td width="50%" class="row1"><b>{L_LOTTERY_CURRENT_ITEMS}:</b></td>
	<td class="row2"><select name="item_name">
<!-- END switch_pool_items -->
<!-- BEGIN pool_listrow -->
		<option value="{pool_listrow.ITEM_NAME}">{pool_listrow.ITEM_NAME}</option>
<!-- END pool_listrow -->
<!-- BEGIN switch_pool_items -->
	</select> <input type="hidden" name="del_item" value="del_item" /><input type="submit" value="{L_LOTTERY_REMOVE_ITEM}" name="item_pool" class="liteoption" /></td>
	</form>
</tr>
<!-- END switch_pool_items -->
<!-- BEGIN switch_are_items -->
<tr>
	<form action="{S_CONFIG_ACTION}" method="post">
	<td class="row1"><b>{L_LOTTERY_ADD_ITEMS}:</b></td>
	<td class="row2"><select name="item_id">
		<option value="random">{L_LOTTERY_RANDOM}</option>
<!-- END switch_are_items -->
<!-- BEGIN item_listrow -->
		<option value="{item_listrow.ITEM_ID}">{item_listrow.ITEM_NAME}</option>
<!-- END item_listrow -->
<!-- BEGIN switch_are_items -->
	</select> <input type="hidden" name="add_item" value="add_item" /><input type="submit" value="{L_LOTTERY_ADD_ITEM}" name="item_pool" class="liteoption" /></td>
	</form>
</tr>
<!-- END switch_are_items -->
<!-- BEGIN switch_no_items -->
<tr>
	<td class="row1" colspan="2" align="center" height="30"><span class="gen">{switch_no_items.MESSAGE}</span></td>
</tr>
<!-- END switch_no_items -->
</form></table>
<br />
<div align="center" class="copyright">Lottery 2.2.0 &copy; 2004, {COPYRIGHT_YEAR} <a href="http://www.zarath.com" class="copyright" target="_blank">Zarath Technologies</a></div>
