{TOPIC_MENU}{FORUM_MENU}{VOTE_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_CONFIGURATION}</h1>

<p>{KICKER_EXPLAIN}</p>

<table width="100%" cellpadding="4" cellspacing="1" align="center" class="forumline"><form action="{S_CONFIG_ACTION}" method="post">	
<tr>
	<th class="thHead" colspan="2">{L_CONFIGURATION}</th>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_ENABLE_KICKER}:</b></td>
	<td class="row2"><input type="radio" name="enable_kicker" value="1"{ENABLE_KICKER_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="enable_kicker" value="0"{ENABLE_KICKER_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="config" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</form></table>
<br />

<h1>{KICKER_TABLE}</h1>

<table width="100%" cellpadding="2" cellspacing="2" align="center"><form action="{S_CONFIG_ACTION}" method="post">	
<tr> 
	<td align="right"><span class="genmed">{L_SELECT_SORT_METHOD} {S_MODE_SELECT} {L_ORDER} {S_ORDER_SELECT} <input type="submit" name="submit" value="{L_SORT}" class="liteoption" /></span></td>
</tr> 
</form></table>
<table width="100%" cellpadding="4" cellspacing="1" align="center" class="forumline"><form action="{S_THREAD_KICKER_ADMIN}" method="post">	
<tr>
	<th class="thCornerL" nowrap="nowrap">{KICKED}</th>
	<th class="thTop" nowrap="nowrap">{THREAD}</th>
	<th class="thTop" nowrap="nowrap">{DATE}</th>
	<th class="thTop" nowrap="nowrap">{KICKED_BY}</th>		
	<th class="thCornerR" nowrap="nowrap">{UNKICK}</th>
</tr>
<!-- BEGIN kicker -->
<tr> 
	<td class="{kicker.ROW_CLASS}" align="center">{kicker.KICKED}</td>
	<td class="{kicker.ROW_CLASS}" align="center">{kicker.THREAD}</td>
	<td class="{kicker.ROW_CLASS}" align="center">{kicker.DATE}</td>
	<td class="{kicker.ROW_CLASS}" align="center">{kicker.KICKED_BY}</td>
	<td class="{kicker.ROW_CLASS}" align="center">{kicker.CHECKBOX}</td>
</tr>
<!-- END kicker -->
<tr>
	<td class="catBottom" colspan="5" align="right"><input type="submit" name="unkick_marked" value="{KICK_MARKED}" class="liteoption" />&nbsp;&nbsp;<input type="submit" name="unkick_all" value="{UNKICK_ALL}" class="liteoption" /></td>
</tr>
</form></table>
<table width="100%" align="center" cellspacing="2" cellpadding="2"> 
<tr> 
	<td class="nav">{PAGE_NUMBER}</td>
	<td align="right" class="nav">{PAGINATION}</td>
</tr> 
</table> 
<br />
<div align="center" class="copyright">Thread Kicker v1.0.4 &copy 2004, 2005 <a href="http://www.majormod.com" class="copyright" target="_blank">Majorflam</a></div>

