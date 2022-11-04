{LANG_MENU}{UTILS_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_TITLE}</h1>

<p>{L_EXPLAIN}</p>

<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline">
<tr>
	<th class="thHead" colspan="2">{L_TITLE}</th>
</tr>
<!-- BEGIN blockrow -->
<tr>
	<td class="catLeft" width="100%"><span class="cattitle"><a name="{blockrow.BLOCK_ANCHOR}"></a>{blockrow.BLOCK_TITLE}</span></td>
	<td width="150" class="catRight" align="right" nowrap="nowrap"><a href="{blockrow.U_BLOCK_MOVE_DOWN}">{L_MOVE_DOWN}</a> <a href="{blockrow.U_BLOCK_MOVE_UP}">{L_MOVE_UP}</a> <a href="{blockrow.U_BLOCK_EDIT}">{L_EDIT}</a> <a href="{blockrow.U_BLOCK_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- BEGIN questrow -->
<tr> 
	<td class="row1" valign="top" width="100%"><span class="gen"><a href="{blockrow.questrow.U_QUEST}" target="_new">{blockrow.questrow.QUEST_TITLE}</a></span></td>
	<td class="row2" align="right" nowrap="nowrap"><a href="{blockrow.questrow.U_QUEST_MOVE_UP}">{L_MOVE_UP}</a> <a href="{blockrow.questrow.U_QUEST_MOVE_DOWN}">{L_MOVE_DOWN}</a> <a href="{blockrow.questrow.U_QUEST_EDIT}">{L_EDIT}</a> <a href="{blockrow.questrow.U_QUEST_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- END questrow -->
<!-- BEGIN no_questions -->
<tr>
	<td class="row1" align="center" colspan="2"><span class="gen">{L_NO_QUESTIONS}</span></td>
</tr>
<!-- END no_questions -->
<tr> 
	<form method="post" action="{S_ACTION}">
	<td class="row2" valign="top" colspan="2"><input class="post" name="quest_title" type="text" value="" size="35" /><input name="mode" type="hidden" value="quest_new"><input name="block" type="hidden" value="{blockrow.BLOCK_NUMBER}"> <input class="liteoption" type="submit" value="{L_ADD_QUESTION}" name="submit" /></td>
	</form>
</tr>
<tr>
	<td colspan="2" height="1" class="spaceRow"><img src="../images/spacer.gif" alt="" width="1" height="1" alt="" /></td>
</tr>
<!-- END blockrow -->
<!-- BEGIN no_blocks -->
<tr>
	<td class="catLeft" colspan="2"><span class="cattitle">{L_NO_BLOCKS}</span></td>
</tr>
<!-- END no_blocks -->
<tr>
	<form method="post" action="{S_ACTION}">
	<td class="catLeft" colspan="2"><input class="post" name="block_title" type="text" value="" size="35" /><input name="mode" type="hidden" value="block_new"> <input class="liteoption" type="submit" value="{L_ADD_BLOCK}" name="submit" /></td>
	</form>
</tr>
</table>
