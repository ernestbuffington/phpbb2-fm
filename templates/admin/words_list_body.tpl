{POST_MENU}{ATTACH_MENU}
{MOD_CP_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_WORDS_TITLE}</h1>

<P>{L_WORDS_TEXT}</p>

<table width="100%" cellspacing="2" cellpadding="2" align="center"><form method="post" action="{S_WORDS_ACTION}">
<tr>
	<td><input type="submit" name="add" value="{L_ADD_WORD}" class="liteoption" /></td>
</tr>
</form></table>
<table width="100%" cellspacing="1" cellpadding="4" align="center" class="forumline">
<tr>
	<th class="thCornerL">{L_WORD}</th>
	<th class="thTop">{L_REPLACEMENT}</th>
	<th class="thCornerR" width="15%">{L_ACTION}</th>
</tr>
<!-- BEGIN words -->
<tr>
	<td class="{words.ROW_CLASS}" align="center">{words.WORD}</td>
	<td class="{words.ROW_CLASS}" align="center">{words.REPLACEMENT}</td>
	<td class="{words.ROW_CLASS}" align="right" nowrap="nowrap"><a href="{words.U_WORD_EDIT}">{L_EDIT}</a> <a href="{words.U_WORD_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- END words -->
</table>
