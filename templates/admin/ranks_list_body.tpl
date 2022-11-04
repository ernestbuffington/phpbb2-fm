{POST_MENU}{ATTACH_MENU}
{MOD_CP_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_RANKS_TITLE}</h1>

<p>{L_RANKS_TEXT}</p>

<table width="100%" cellspacing="2" cellpadding="2" align="center"><form method="post" action="{S_RANKS_ACTION}">
<tr>
	<td><input type="submit" class="liteoption" name="add" value="{L_ADD_RANK}" /></td>
</tr>
</form></table>
<table width="100%" cellspacing="1" cellpadding="4" align="center" class="forumline">
<tr>
	<th class="thCornerL">{L_RANK}</th>
       	<th class="thTop">{L_RANK_MINIMUM}</th>
	<th class="thTop">{L_SPECIAL_RANK}</th>
	<th class="thTop">{L_RANKS_IMAGE}</th>
	<th class="thCornerR">{L_ACTION}</th>
</tr>
<!-- BEGIN ranks -->
<tr>
	<td class="{ranks.ROW_CLASS}" align="center">{ranks.RANK}</td>
      	<td class="{ranks.ROW_CLASS}" align="center">{ranks.RANK_MIN}</td>
	<td class="{ranks.ROW_CLASS}" align="center">{ranks.SPECIAL_RANK}</td>
	<td class="{ranks.ROW_CLASS}" align="center">{ranks.IMAGE_DISPLAY}</td> 
	<td class="{ranks.ROW_CLASS}" align="right" width="15%"><a href="{ranks.U_RANK_EDIT}">{L_EDIT}</a> <a href="{ranks.U_RANK_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- END ranks -->			
</table>
