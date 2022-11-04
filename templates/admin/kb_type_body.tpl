{KB_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_KB_TYPE_TITLE}</h1>

<p>{L_KB_TYPE_DESCRIPTION}</p>

<table width="100%" align="center" cellpadding="2" cellspacing="2"><form action="{S_ACTION}" method="post">
<tr>
	<td align="right"><span class="genmed">{L_CREATE_TYPE}&nbsp;<input class="post" type="text" name="new_type_name">&nbsp;&nbsp;<input type="submit" value="{L_CREATE}" class="liteoption"></span></td>
</tr>
</form></table>
<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline">
<tr>
	<th class="thCornerL">&nbsp;{L_TYPE}&nbsp;</th>
	<th class="thCornerR" width="15%">&nbsp;{L_ACTION}&nbsp;</th>	  
</tr>
<!-- BEGIN typerow -->
<tr> 	
	<td class="{typerow.ROW_CLASS}">{typerow.TYPE}</td>
	<td class="{typerow.ROW_CLASS}" align="right" nowrap="nowrap">{typerow.U_EDIT} {typerow.U_DELETE}</td>
</tr>
<!-- END typerow -->
</table>
<br />
<div align="center" class="copyright">Knowledge Base 0.7.6 Beta &copy; 2003, {COPYRIGHT_YEAR} <a href="http://eric.best-1.biz/" class="copyright" target="_blank">wGEric</a></div>
