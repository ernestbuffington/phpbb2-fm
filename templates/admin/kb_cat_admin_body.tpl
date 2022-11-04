{KB_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_KB_CAT_TITLE}</h1>

<p>{L_KB_CAT_DESCRIPTION}</p>

<table align="center" width="100%" cellpadding="4" cellspacing="1" class="forumline"
<tr>
	<th class="thCornerL">&nbsp;{L_CATEGORY}&nbsp;</th>
	<th class="thTop" width="50">&nbsp;{L_ARTICLES}&nbsp;</th>
	<th class="thCornerR" width="15%">&nbsp;{L_ACTION}&nbsp;</th>	  
</tr>
<!-- BEGIN catrow -->
<tr> 	
	<td class="{catrow.ROW_CLASS}"><b>{catrow.CATEGORY}</b><br />{catrow.CAT_DESCRIPTION}</td>
	<td class="{catrow.ROW_CLASS}" align="center">{catrow.CAT_ARTICLES}</td>
	<td class="{catrow.ROW_CLASS}" align="right" nowrap="nowrap">{catrow.U_UP} {catrow.U_DOWN} {catrow.U_EDIT} {catrow.U_DELETE}</td>
</tr>
<!-- BEGIN subrow -->
<tr> 	
	<td width="100%" class="{catrow.subrow.ROW_CLASS}"><b>{catrow.subrow.INDENT}{catrow.subrow.CATEGORY}</b><br />{catrow.subrow.INDENT}{catrow.subrow.CAT_DESCRIPTION}</td>
	<td class="{catrow.subrow.ROW_CLASS}" align="center">{catrow.subrow.CAT_ARTICLES}</td>
	<td class="{catrow.subrow.ROW_CLASS}" align="right" nowrap="nowrap">{catrow.subrow.U_UP} {catrow.subrow.U_DOWN} {catrow.subrow.U_EDIT} {catrow.subrow.U_DELETE}</td>
</tr>
<!-- END subrow -->
<!-- END catrow -->
</table>
<br />
<div align="center" class="copyright">Knowledge Base 0.7.6 Beta &copy; 2003, {COPYRIGHT_YEAR} <a href="http://eric.best-1.biz/" class="copyright" target="_blank">wGEric</a></div>
