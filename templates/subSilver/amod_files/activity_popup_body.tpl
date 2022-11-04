<!-- BEGIN chat -->
<!-- BEGIN history -->
<table class="forumline" align="center" cellpadding="4" cellspacing="1" width="100%">
<tr>
	<th class="thHead" colspan="2">{chat.history.TITLE}</th>
</tr>	
<tr>	
	<form method="post" name="refresh" action="activity_popup.php?mode=chat&action=view">		
	<td class="row1" valign="middle"><input type="submit" class="liteoption" value="{chat.history.REFRESH}" onchange="document.refresh.submit()" /></td>
	</form>
	<form name="history_chat">
	<td class="row1" valign="middle"><select name="history" onchange="if(options[selectedIndex].value)window.location.href=(options[selectedIndex].value)">
		<option value="">{DEFAULT}</option>
		<!-- BEGIN dates -->
		<option value="activity_popup.php?mode=chat&action=history&history={chat.history.dates.HISTORY}">{chat.history.dates.HISTORY}</option>			
		<!-- END dates -->
	</select>
	<noscript><input type=submit value="Go"></noscript>
	</td>				
	</form>			
</tr>	
{chat.history.CHAT}
</table>
<br />
<!-- END history -->
<!-- BEGIN view -->
<table class="forumline" align="center" cellpadding="4" cellspacing="1" width="100%">
<tr>
	<th class="thHead" colspan="3">{chat.view.TITLE}</th>
</tr>	
<tr>	
	<form method="post" name="add_chat" action="activity_popup.php?mode=chat">
	<td class="row1" valign="middle" width="60%"><input type="hidden" value="add" name="action"><input type="text" value="" size="30" name="msg" class="post"> &nbsp;<input type="submit" class="liteoption" value="{chat.view.SUBMIT}" onchange="document.add_chat.submit()" /></td>
	</form>
	<form method="post" name="refresh" action="activity_popup.php?mode=chat&action=view">	
	<td class="row1" align="center" valign="middle" width="15%"><input type="submit" class="liteoption" value="{chat.view.REFRESH}" onchange="document.refresh.submit()" /></td>
	</form>
	<form name="history_chat">
	<td class="row1" align="center" valign="middle" width="15%"><select name="history" onchange="if(options[selectedIndex].value)window.location.href=(options[selectedIndex].value)">
		<option value="t">{DEFAULT}</option>
		<!-- BEGIN history -->
		<option value="activity_popup.php?mode=chat&action=history&history={chat.view.history.HISTORY}">{chat.view.history.HISTORY}</option>			
		<!-- END history -->
		</select>
	<noscript><input type=submit value="Go"></noscript>
	</td>		
	</form>			
</tr>	
{chat.view.CHAT}
</table>
<!-- END view -->
<!-- END chat -->


<!-- BEGIN rate -->
<!-- BEGIN main -->
<table class="forumline" align="center" cellpadding="4" cellspacing="1" width="100%"><form method="post" name="sub_rate" action="activity_popup.php?mode=rate">	
<tr>
	<th class="thHead" colspan="2">{rate.main.TITLE}</th>
</tr>
<tr>
	<td width="38%" class="row1"><b>{rate.main.CHOICES}</b></td>
	<td class="row2"><select name="rating">
		<option selected value="">{rate.main.DEFAULT_RATE}</option>
		<option value="1">1</option>
		<option value="2">2</option>
		<option value="3">3</option>
		<option value="4">4</option>
		<option value="5">5</option>			
		<option value="6">6</option>
		<option value="7">7</option>
		<option value="8">8</option>
		<option value="9">9</option>
		<option value="10">10</option>
	</select> &nbsp;<input type="hidden" name="action" value="submit_rating"><input type="hidden" name="game" value="{rate.main.GAME}"><input class="mainoption" type="submit" value="{rate.main.SUBMIT}" onchange="document.sub_rate.submit()" /></td>	
</tr>
</form></table>		
<!-- END main -->
<!-- END rate -->


<!-- BEGIN comments -->
<!-- BEGIN main -->
<table class="forumline" align="center" cellpadding="4" cellspacing="1" width="100%">
<tr> 
	<th class="thCornerL" width="5%">{comments.main.MAIN_NAME}</th>
	<th class="thTop" width="25%">{comments.main.MAIN_LEFT}</th>
	<th class="thTop" width="30%">{comments.main.MAIN_CENTER1}</th>
	<th class="thTop" width="15%">{comments.main.MAIN_CENTER2}</th>		
	<th class="thCornerR" width="25%">{comments.main.MAIN_RIGHT}</th>		
</tr>
<tr>
	<td align="center" class="row2">{comments.main.MAIN_IMAGE}</td>
	<td class="row2">{comments.main.TROPHY_HOLDER}</td>
	<td class="row2">{comments.main.TROPHY_COMMENT}</td>
	<td align="center" class="row2">{comments.main.TROPHY_SCORE}</td>
	<td class="row2">{comments.main.TROPHY_DATE}</td>
</tr>
<!-- END main -->
<!-- BEGIN comment -->
<tr>
	<td align="center" class="{comments.comment.ROW}">{comments.comment.POS}</td>
	<td class="{comments.comment.ROW}">{comments.comment.TROPHY_HOLDER}</td>
	<td class="{comments.comment.ROW}">{comments.comment.TROPHY_COMMENT}</td>
	<td align="center" class="{comments.comment.ROW}">{comments.comment.TROPHY_SCORE}</td>
	<td class="{comments.comment.ROW}">{comments.comment.TROPHY_DATE}</td>
</tr>
<!-- END comment -->
<!-- BEGIN main -->
</table>
<!-- END main -->

<!-- BEGIN post_comment -->
<table class="forumline" align="center" cellpadding="4" cellspacing="1" width="100%"><form method="post" action="{comments.post_comment.POST_LINK}">
<tr>
  	<th class="thHead">{comments.post_comment.POST_TITLE}</th>				
</tr>	
<tr> 
	<td class="row2">{comments.post_comment.POST_IMAGE}</td>
</tr>
<tr>
	<td class="row1">{comments.post_comment.POST_LENGTH}<br /><input type="text" name="comment" value="" class="post" /><input type="hidden" value="posting_comment" name="action"><input type="hidden" value="{comments.post_comment.POST_GAME}" name="comment_game_name"></td>
</tr>
<tr>
	<td class="catBottom" align="center"><input type="submit" value="{comments.post_comment.POST_SUBMIT}" class="mainoption" /></td>
</tr>		
</table>
</form>	
<!-- END post_comment -->	
<!-- END comments -->


<!-- BEGIN challenge -->
<table class="forumline" align="center" cellpadding="4" cellspacing="1" width="100%">
<tr>
	<th class="thHead">{challenge.TITLE}</th>
</tr>
<tr>
	<td class="row1">{challenge.MSG}</td>
</tr>
</table>
<!-- END challenge -->


<!-- BEGIN info -->
<table class="forumline" align="center" cellpadding="4" cellspacing="1" width="100%">
<tr>
	<th class="thHead" colspan="2">{info.L_TITLE}</th>
</tr>
<tr>
	<td class="row1" width="38%" align="center"><img src="{info.PATH}/{info.NAME}.gif" alt="" title="" /></td>
	<td class="row2">{info.DESC}</td>
</tr>
<tr>
	<td class="row1"><b>{info.L_CATEGORY}:</b></td>
	<td class="row2">{info.CATEGORY}</td>
</tr>
<tr>
	<td class="row1"><b>{info.L_TYPE}:</b></td>
	<td class="row2">{info.TYPE}</td>
</tr>			
<tr>
	<td class="row1"><b>{info.L_PLAYED}:</b></td>
	<td class="row2">{info.PLAYED}</td>
</tr>
<tr>
	<td class="row1"><b>{info.L_DATE}:</b></td>
	<td class="row2">{info.DATE}</td>
</tr>		
<tr>
	<td class="row1"><b>{info.L_COST}:</b></td>
	<td class="row2">{info.COST}</td>
</tr>
<tr>
	<td class="row1"><b>{info.L_BORROWED}:</b></td>
	<td class="row2">{info.BORROWED}</td>
</tr>	
<tr>
	<td class="row1"><b>{info.L_BONUS}:</b></td>
	<td class="row2">{info.BONUS}</td>
</tr>
<tr>
	<td class="row1"><b>{info.L_PLAYER}:</b></td>
	<td class="row2">{info.BEST_PLAYER}</td>
</tr>
<tr>
	<td class="row1"><b>{info.L_SCORE}:</b></td>
	<td class="row2">{info.BEST_SCORE}</td>
</tr>
<tr>
	<th class="thTop" colspan="2">{info.L_TITLE_2}</th>
</tr>
<tr>
	<td class="row1" colspan="2">{info.INSTRUCTIONS}</td>
</tr>
</table>
<!-- END info -->