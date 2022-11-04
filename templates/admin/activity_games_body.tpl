{GAMES_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_GAMES}</h1>
	
<table width="100%" cellspacing="1" cellpadding="4" align="center" class="forumline"><form action="{S_CONFIG_ACTION}" method="post">
<tr>
    <th class="thCornerL" width="5%">{L_MONEY}</th>
    <th class="thTop" width="15%">{L_BUTTON}</th>
    <th class="thTop" width="55%">{L_DESC}</th>
    <th class="thTop" width="5%">{L_REWARD}</th>
    <th class="thTop" width="10%">{L_BONUS}</th>
    <th class="thTop" width="5%">{L_FLASH}</th>
    <th class="thTop" width="5%">{L_SCORE}</th>
    <th class="thTop" width="5%">{L_GAMELIB}</th>
    <th class="thTop" width="5%" colspan="2">{L_ACTION}</th>
</tr>
<!-- BEGIN game -->
<tr>
    <td class="{game.ROW_CLASS}" align="center">{game.CHARGE}</td>
    <td class="{game.ROW_CLASS}"><img src ="./../{game.PATH}{game.NAME}.gif" alt="" title="" /></td>
    <td class="{game.ROW_CLASS}">{game.DESC}</td>
    <td class="{game.ROW_CLASS}" align="center">{game.REWARD}</td>
    <td class="{game.ROW_CLASS}" align="center">{game.BONUS}</td>
    <td class="{game.ROW_CLASS}" align="center">{game.FLASH}</td>
    <td class="{game.ROW_CLASS}" align="center">{game.SHOW_SCORE}</td>
    <td class="{game.ROW_CLASS}" align="center">{game.GAMELIB}</td>
    <td class="{game.ROW_CLASS}"><a href="{game.U_GAME_EDIT}">{L_EDIT}</a></td>
    <td class="{game.ROW_CLASS}">[<a href="{game.U_GAME_DELETE}">{L_DEL}</a>]</td>
</tr>
<!-- END game -->
<tr>
	<td colspan="11" class="catBottom" align="center"><input type="submit" name="add_game" value="{L_ADD}" class="mainoption" />&nbsp;&nbsp;<input type="submit" name="clear_scores" value="{L_RESET_SCORE}" class="liteoption" /></td>
</tr>
</form></table>
