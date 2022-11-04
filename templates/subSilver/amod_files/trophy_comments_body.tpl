<table width="100%" cellspacing="2" cellpadding="2" align="center">
<tr> 
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> <a href="{U_ACTIVITY}" class="nav">{L_ACTIVITY}</a></td>
</tr>
</table>

<!-- BEGIN main -->
<table width="100%" cellspacing="1" cellpadding="4" align="center" class="forumline">
<tr> 
	<th class="thCornerL" width="25%">{main.MAIN_NAME}</th>
  	<th class="thTop" width="15%">{main.MAIN_LEFT}</th>
  	<th class="thTop" width="20%">{main.MAIN_CENTER1}</th>
  	<th class="thTop" width="20%">{main.MAIN_CENTER2}</th>		
	<th class="thCornerR" width="20%">{main.MAIN_RIGHT}</th>		
</tr>
<tr>
	<td align="center" class="row1">{main.MAIN_IMAGE}</td>
	<td align="center" class="row1">{main.TROPHY_HOLDER}</td>
	<td class="row1">{main.TROPHY_COMMENT}</td>
	<td class="row1">{main.TROPHY_SCORE}</td>
	<td class="row1">{main.TROPHY_DATE}</td>
</tr>
<!-- END main -->
<!-- BEGIN comments -->
<tr>
	<td align="center" class="{comments.ROW}">{comments.POS}</td>
	<td align="center" class="{comments.ROW}">{comments.TROPHY_HOLDER}</td>
	<td class="{comments.ROW}">{comments.TROPHY_COMMENT}</td>
	<td class="{comments.ROW}">{comments.TROPHY_SCORE}</td>
	<td class="{comments.ROW}">{comments.TROPHY_DATE}</td>
</tr>
<!-- END comments -->
<!-- BEGIN main -->
</table>
<!-- END main -->

<!-- BEGIN post_comment -->
<table class="forumline" width="100%" cellspacing="1" cellpadding="4" align="center"><form method="post" action="{post_comment.POST_LINK}">
<tr>
	<th class="thHead">{post_comment.POST_TITLE}</th>				
</tr>	
<tr> 
	<td class="row2" align="center">{post_comment.POST_IMAGE}</td>
</tr>
<tr>
	<td class="row1">{post_comment.POST_LENGTH}<br /><input type="text" name="comment" value=""><input type="hidden" value="posting_comment" name="mode"><input type="hidden" value="{post_comment.POST_GAME}" name="comment_game_name"></td>
</tr>
<tr>
	<td class="catBottom" colspan="4"><input type="submit" value="{post_comment.POST_SUBMIT}" class="mainoption" /></td>
</tr>
</form></table>
<!-- END post_comment -->	
<br />
<table width="100%" cellspacing="2" align="center"> 
<tr> 
	<td align="right" valign="middle" nowrap="nowrap">{JUMPBOX}</td> 
</tr> 
</table> 
<br />
<div align="center" class="copyright">Activity Mod Plus v1.1.0 &copy; 2003, {COPYRIGHT_YEAR} <a href="http://phpbb-amod.com" target="_blank" class="copyright">aUsTiN</a></div>
