{MEDALS_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_MEDAL_TITLE}</h1>

<p>{L_MEDAL_EXPLAIN}</p>

<table cellspacing="2" cellpadding="2" align="center" width="100%"><form method="post" action="{S_MEDAL_ACTION}">
<tr>
	<td align="right"><input class="post" type="text" name="name" />&nbsp;<input type="submit" class="mainoption" name="addcat" value="{L_CREATE_NEW_MEDAL_CAT}" />&nbsp;&nbsp;<input type="submit" class="mainoption" name="addmedal" value="{L_CREATE_NEW_MEDAL}" /></td>
</tr>
</form></table>

<!-- BEGIN catrow -->
<table cellspacing="1" cellpadding="4" align="center" class="forumline" width="100%">
<tr>
	<td class="catLeft" colspan="4"><span class="cattitle">{catrow.CAT_DESC}</span></td>
	<td class="cat" align="right"><a href="{catrow.U_CAT_MOVE_UP}">{L_MOVE_UP}</a> <a href="{catrow.U_CAT_MOVE_DOWN}">{L_MOVE_DOWN}</a> <a href="{catrow.U_CAT_EDIT}">{L_EDIT}</a> <a href="{catrow.U_CAT_DELETE}">{L_DELETE}</a></td>
</tr>
<tr>
	<th class="thCornerL" width="15%">{L_MEDAL_NAME}</th>
	<th class="thTop">{L_MEDAL_IMAGE}</th>
       	<th class="thTop">{L_MEDAL_DESCRIPTION}</th>
	<th class="thTop">{L_MEDAL_MOD}</th>
	<th class="thCornerR" width="15%">{L_ACTION}</th>
</tr>
<!-- BEGIN medals -->
<tr>
	<td class="row1" align="center">{catrow.medals.MEDAL_NAME}</td>
	<td class="row2" align="center">{catrow.medals.MEDAL_IMAGE}</td>
        <td class="row1" align="center">{catrow.medals.MEDAL_DESCRIPTION}</td>
	<td class="row2" align="center"><a href="{catrow.medals.U_MEDAL_MOD}">{L_MEDAL_MOD}</a></td>
	<td class="row1" align="right"><a href="{catrow.medals.U_MEDAL_EDIT}">{L_EDIT}</a> <a href="{catrow.medals.U_MEDAL_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- END medals -->
<!-- BEGIN nomedals -->
<tr>
	<td class="row1" colspan="6" align="center"><span class="gen">{catrow.nomedals.L_NO_MEDAL_IN_CAT}</span></td>
</tr>
<!-- END nomedals -->
<tr>
	<td colspan="6" height="1" class="spaceRow"><img src="../images/spacer.gif" alt="" title="" width="1" height="1" /></td>
</tr>
</table>
<!-- END catrow -->			
<br />
<div align="center" class="copyright">Medal System 0.4.6 &copy; 2003, {COPYRIGHT_YEAR} <a href="http://macphpbbmod.sourceforge.net" target="_blank" class="copyright">ycl6</a></div>

