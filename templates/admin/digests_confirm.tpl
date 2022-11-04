{DIGESTS_MENU}{EMAIL_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_CONFIRM_TITLE}</h1>

<p>{L_CONFIRM_EXPLAIN}</p>

<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline"><form method="post" action="{S_AUTH_ACTION}">
<tr>
	<th class="thHead" colspan="2">{L_CONFIRM_SETTINGS}</th>
</tr>
<tr> 
	<td class="row1" width="50%"><b>{L_CONFIRM_TYPE}:</b></td> 
      	<td class="row2"><input type="radio" checked name="confirm_type" value="1" {CONFIRM_USER} /> {L_USER}&nbsp;&nbsp;<input type="radio" name="confirm_type" value="2" {CONFIRM_GROUP} /> {L_GROUP}</td> 
</tr>
<tr>
	<td class="row1"><b>{L_ALL_GROUPS_CHECKED}:</b></td>
	<td class="row2"><input type="checkbox" name="all_groups" value="1" {ALL_GROUPS}></td>
</tr>
<tr> 
	<td class="row1"><b>{L_CONFIRM_GROUP}:</b></td> 
      	<td class="row2">{S_GROUP_SELECT}</td> 
</tr>
<tr> 
	<td class="row1"><b>{L_CONFIRM_DAYS}:</b></td> 
      	<td class="row2"><input class="post" type="text" name="confirm_days" size="5" maxlength="2" value="14" /></td> 
</tr>
<tr>
	<td class="catBottom" colspan="2" align="center"><input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" /></td>
</tr>
</form></table>