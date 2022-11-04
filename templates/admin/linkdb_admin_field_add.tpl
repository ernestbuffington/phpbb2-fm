{LINKDB_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_FIELD_TITLE}</h1>

<p>{L_FIELD_EXPLAIN}</p>

<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline"><form action="{S_FIELD_ACTION}" method="post">
<tr>
	<th colspan="2" class="thHead">{L_FIELD_TITLE}</th>
</tr>
<tr>	
	<td width="50%" class="row1"><b>{L_FIELD_NAME}:</b><br /><span class="gensmall">{L_FIELD_NAME_INFO}</span></td>
	<td class="row2"><input type="text" class="post" size="35" name="field_name" value="{FIELD_NAME}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_FIELD_DESC}:</b><br /><span class="gensmall">{L_FIELD_DESC_INFO}</span></td>
	<td class="row2"><input type="text" class="post" size="35" name="field_desc" value="{FIELD_DESC}" /></td>
</tr>
<!-- BEGIN field_data -->
<tr>
	<td class="row1"><b>{L_FIELD_DATA}:</b><br /><span class="gensmall">{L_FIELD_DATA_INFO}</span></td>
	<td class="row2"><textarea class="post" rows="6" name="data" cols="32">{FIELD_DATA}</textarea></td>
</tr>
<!-- END field_data -->
<!-- BEGIN field_regex -->
<tr>
	<td class="row1"><b>{L_FIELD_REGEX}:</b><br /><span class="gensmall">{L_FIELD_REGEX_INFO}</span></td>
	<td class="row2"><input type="text" class="post" size="35" name="regex" value="{FIELD_REGEX}" /></td>
</tr>
<!-- END field_regex -->
<!-- BEGIN field_order -->
<tr>	
	<td class="row1"><b>{L_FIELD_ORDER}:</b></td>
	<td class="row2"><input type="text" class="post" size="6" name="field_order" value="{FIELD_ORDER}" /></td>
</tr>
<!-- END field_order -->
<tr>
	<td align="center" class="catBottom" colspan="2">{S_HIDDEN_FIELDS}<input class="liteoption" type="submit" value="{L_FIELD_TITLE}" name="submit" /></td>
</tr>
</form></table>
